<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'date', 'release_flag',
    ];

    protected $casts = [
        'release_flag' => 'boolean',
    ];

    public function post_categories()
    {
        return $this->hasMany('App\Models\PostCategory');
    }

    protected static $_select_list_columns = [
        'id', 'title', 'date',
    ];

    // -----------------------------------------------------------------

    /**
     * 1件返す
     */
    public static function findOne($post_id)
    {
        return self::with('post_categories')->find($post_id)->first();
    }


    /**
     * 全件返す
     */
    public static function findAll($pagination = null)
    {
        if ($pagination !== null || !is_int($pagination)) {
            return self::all();
        } else {
            return self::paginate($pagination);
        }
    }

    /**
     * 公開済みの記事一覧を返す
     * @param null|int $pagination
     */
    public static function findAllReleasedPosts($pagination = null)
    {
        $q = self::with('post_categories')->select(static::$_select_list_columns)
                 ->where('release_flag', true)->orderBy('date', 'desc');

        if ($pagination !== null || !is_int($pagination)) {
            return $q->paginate($pagination);
        } else {
            return $q->get();
        }
    }

    /**
     * カテゴリを基に記事一覧を返す
     * @param null|string $category
     * @param null|int $pagination
     */
    public static function findAllReleasedPostAsCategory($category = null, $pagination = null)
    {
        $q = self::with('post_categories')->select(static::$_select_list_columns)
                 ->where('release_flag', true)
                 ->whereHas('post_categories', function ($q) use ($category) {$q->where('category', $category);})
                 ->orderBy('date', 'desc');

        if ($pagination !== null || !is_int($pagination)) {
            return $q->paginate($pagination);
        } else {
            return $q->get();
        }
    }
}
