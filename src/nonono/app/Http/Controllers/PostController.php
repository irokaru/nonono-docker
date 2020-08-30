<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;

use App\Lib\CommonUtil;

use App\Models\Post;
use App\Models\PostCategory;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class PostController extends Controller
{
    const PAGINATION = 20;

    protected static $_validate_store = [
        'title'        => 'required|string|min:1|max:64',
        'date'         => 'required|date',
        'release_flag' => 'required|boolean',
        'detail'       => 'required|string|min:1|max:12800',
        'categories'   => 'required|array|min:0',
        'categories.*' => 'required|string|distinct|min:1|max:32',
    ];

    protected static $_validate_update = [
        'id'           => 'required|integer|min:1',
        'title'        => 'required|string|min:1|max:64',
        'date'         => 'required|date',
        'release_flag' => 'required|boolean',
        'detail'       => 'required|string|min:1|max:12800',
        'categories'   => 'required|array|min:0',
        'categories.*' => 'required|array|distinct|min:1|max:32',
    ];

    public function __construct()
    {
        $this->middleware('auth:api')->only(['indexAll', 'store']);
    }

    public function index()
    {
        return PostResource::collection(Post::findAllReleasedPosts(static::PAGINATION));
    }

    public function indexAll()
    {
        return PostResource::collection(Post::findAll(static::PAGINATION));
    }

    public function indexAsCategory(Request $request)
    {
        $category = trim($request->category);

        $validate_category = ['category' => 'required|string|min:1|max:32',];
        $validator = Validator::make(['category' => $category], $validate_category);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        return PostResource::collection(Post::findAllReleasedPostAsCategory($category, static::PAGINATION));
    }

    public function show(Request $request)
    {
        $validate_show = [
            'post_id' => 'required|integer|min:1',
        ];

        $validator = Validator::make(['post_id' => $request->post_id], $validate_show);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $post = Post::findOne($request->post_id);

        if (!$post) {
            return response()->json(['error' => 'not found'], 404);
        }

        return PostResource::make($post);
    }

    public function indexOfCategories()
    {
        return PostCategory::countCategories();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), static::$_validate_store);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = static::formatPost($request);

        $post_id = Post::insertOne($data);
        PostCategory::insertSome($post_id, $data['categories']);

        $save = CommonUtil::isTesting() ? true : static::savePostDetail($post_id, $data['detail']);

        if (!$save) {
            return response()->json(['error' => 'failed file output'], 400);
        }

        return response()->json(['status' => 'success', 'id' => $post_id], 200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), static::$_validate_update);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = static::formatPost($request);

        if (!Post::updateOne($data)) {
            return response()->json(['error' => 'not exist post'], 400);
        }

        $categories_now = PostCategory::findAllAsPostId($data['id'])->toArray();
        $instCategory   = static::compareCategories($data['categories'], $categories_now);

        foreach($instCategory['delete'] as $category) {
            PostCategory::deleteOne($category['id']);
        }

        foreach ($instCategory['insert'] as $category) {
            PostCategory::insertOne($data['id'], $category['category']);
        }

        $save = CommonUtil::isTesting() ? true : static::savePostDetail($data['id'], $data['detail']);

        if (!$save) {
            return response()->json(['error' => 'failed file output'], 400);
        }

        return response()->json(['status' => 'success', 'id' => $data['id']], 200);
    }

    // -----------------------------------------------------------------

    /**
     * リクエストを良い感じにして返すやつ
     * @param Illuminate\Http\Request $request
     * @return array
     */
    protected static function formatPost(Request $request): array
    {
        $flip_keys = [
            'id', 'title', 'date', 'release_flag', 'detail', 'categories',
        ];

        $array = $request->toArray();

        if (isset($array['date'])) {
            $array['date'] = date('Y-m-d', strtotime($array['date']));
        }

        return array_intersect_key($array, array_flip($flip_keys));
    }

    /**
     * 記事の内容をファイルとして保存する
     * @param int $post_id
     * @param string $content
     * @return bool
     */
    protected static function savePostDetail($post_id, $content): bool
    {
        return Storage::disk('local')->put("posts/$post_id.md", $content);
    }

    /**
     * 新しいカテゴリ一覧と古いカテゴリ一覧を比較してレコードの操作を返す
     * @param array $new
     * @param array $old
     * @return array
     */
    public static function compareCategories($new, $old): array
    {
        $array = [
            'insert' => [],
            'delete' => [],
        ];

        $array['insert'] = static::arrayDiffRecursive(['category'], $new, $old);
        $array['delete'] = static::arrayDiffRecursive(['category'], $old, $new);

        return $array;
    }

    /**
     * 連想配列の差分を見つけて返す
     * @param array $keys
     * @param array $array1
     * @param array ...$arrayn
     * @return array
     */
    protected static function arrayDiffRecursive($keys, $array1, ...$arrayn)
    {
        foreach ($arrayn as $arrayi) {
            $array1 = array_udiff($array1, $arrayi, function($a, $b) use ($keys){
                foreach ($keys as $key) {
                    $cmp = $a[$key] <=> $b[$key];

                    if ($cmp) {
                        return $cmp;
                    }
                }
                return $cmp;
            });
        }

        return $array1;
    }
}
