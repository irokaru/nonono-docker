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

    public $timestamps = false;

    public function post()
    {
        return $this->belongsTo('App\Models\Post', 'post_id');
    }

    // -----------------------------------------------------------------

    /**
     * 各カテゴリの記事数を返す
     */
    public static function countCategories()
    {
        return self::groupBy('category')->select('category', DB::raw('count(*) as count'))->get();
    }

    /**
     * 指定した記事のカテゴリをコンマ区切りで返す
     * @param int $post_id
     */
    public static function findOneCategoriesAsPost($post_id)
    {
        return self::select(DB::raw('group_concat(category) as categories'))
                   ->where('post_id', $post_id)->groupBy('post_id')->first();
    }

    /**
     * 各記事のカテゴリをコンマ区切りで返す
     */
    public static function findAllCategoriesAsPost()
    {
        return self::select('post_id', DB::raw('group_concat(category) as categories'))
                   ->groupBy('post_id')->get();
    }
}
