<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PostCategory extends Model
{
    protected $table = 'post_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id', 'category',
    ];

    protected $casts = [
        'post_id' => 'integer',
    ];

    public $timestamps = false;

    public function post()
    {
        return $this->belongsTo('App\Models\Post', 'post_id');
    }

    // -----------------------------------------------------------------

    /**
     * 各カテゴリの記事数を返す
     * @return Illuminate\Support\Collection
     */
    public static function countCategories()
    {
        return self::groupBy('category')->select('category', DB::raw('count(*) as count'))
                   ->orderBy('category', 'asc')->get();
    }

    /**
     * idを基に1件カテゴリを返す
     * @param int $id
     * @return Illuminate\Database\Eloquent\Model|null
     */
    public static function findOne($id)
    {
        return self::find($id);
    }

    /**
     * 指定した記事のカテゴリを返す
     * @param int $post_id
     * @return Illuminate\Support\Collection
     */
    public static function findAllAsPostId($post_id)
    {
        return self::where('post_id', $post_id)->get();
    }

    /**
     * 指定した記事のカテゴリをコンマ区切りで返す
     * @param int $post_id
     * @return Illuminate\Database\Eloquent\Model|null
     */
    public static function findOneCategoriesAsPost($post_id)
    {
        return self::select(DB::raw('group_concat(category) as categories'))
                   ->where('post_id', $post_id)->groupBy('post_id')->first();
    }

    /**
     * 各記事のカテゴリをコンマ区切りで返す
     * @return Illuminate\Support\Collection
     */
    public static function findAllCategoriesAsPost()
    {
        return self::select('post_id', DB::raw('group_concat(category) as categories'))
                   ->groupBy('post_id')->get();
    }

    /**
     * 1件追加する
     * @param int $post_id
     * @param string $category
     * @return int
     */
    public static function insertOne($post_id, $category)
    {
        $result = self::create([
            'post_id'  => $post_id,
            'category' => $category,
        ]);
        return $result->id;
    }

    /**
     * 複数件追加する
     * @param int $post_id
     * @param array $categories
     * @return array
     */
    public static function insertSome($post_id, $categories)
    {
        $result = [];

        foreach($categories as $category) {
            $result[] = self::insertOne($post_id, $category);
        }

        return $result;
    }

    /**
     * 1件削除する
     * @param int $id
     * @return bool
     */
    public static function deleteOne($id)
    {
        $category = self::findOne($id);
        if ($category === null) {
            false;
        }

        $category->delete();
        return true;
    }
}
