<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
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

    public function __construct()
    {
        $this->middleware('auth:api')->only(['indexAll']);
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
        static::savePostDetail($post_id, $data['detail']);

        if (PostCategory::insertSame($post_id, $data['categories'])) {
            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['error' => 'failed'], 400);
    }

    public function update(Request $request)
    {
        // TODO
        return response()->json(['status' => 'success'], 200);
    }

    // -----------------------------------------------------------------

    /**
     * リクエストを良い感じに
     * @param Illuminate\Http\Request $request
     * @return array
     */
    protected static function formatPost($request): array
    {
        $flip_keys = [
            'id', 'title', 'date', 'detail', 'categories',
        ];
        return array_intersect_key($request->toArray(), array_flip($flip_keys));
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
}
