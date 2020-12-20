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
     * @param int $post_id
     * @return \Illuminate\Database\Eloquent\Model|object|static|null
     */
    public static function findOne($post_id)
    {
        return static::withCategories()->where(['id' => $post_id, 'release_flag' => true])->first();
    }

    /**
     * 全件返す
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function findAll($pagination = null)
    {
        $q = static::withCategories();
        if ($pagination !== null || !is_int($pagination)) {
            return $q->get();
        } else {
            return $q->paginate($pagination);
        }
    }

    /**
     * 公開済みの記事一覧を返す
     * @param null|int $pagination
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function findAllReleasedPosts($pagination = null)
    {
        $q = static::withCategories()->select(static::$_select_list_columns)
                   ->where('release_flag', true)->orderBy('date', 'desc');

        if ($pagination !== null || !is_int($pagination)) {
            return $q->paginate($pagination);
        } else {
            return $q->get();
        }
    }

    /**
     * 公開済みの新着記事を返す
     * @param null|int $limit
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function findLatestReleasedPosts($limit = 4)
    {
        return  static::withCategories()->select(static::$_select_list_columns)
                      ->where('release_flag', true)->orderBy('date', 'desc')->take($limit)->get();
    }

    /**
     * カテゴリを基に記事一覧を返す
     * @param null|string $category
     * @param null|int $pagination
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function findAllReleasedPostAsCategory($category = null, $pagination = null)
    {
        $q = static::withCategories()
                   ->select(static::$_select_list_columns)
                   ->where('release_flag', true)
                   ->whereHas('post_categories', function ($q) use ($category) {$q->where('category', $category);})
                   ->orderBy('date', 'desc');

        if ($pagination !== null || !is_int($pagination)) {
            return $q->paginate($pagination);
        } else {
            return $q->get();
        }
    }

    /**
     * 1件追加する
     * @param array $post
     * @return int
     */
    public static function insertOne($post)
    {
        $post = self::create([
            'title'        => $post['title'],
            'date'         => $post['date'],
            'release_flag' => $post['release_flag'],
        ]);

        return $post->id;
    }

    /**
     * 1件更新する
     * @param array $post
     * @return bool
     */
    public static function updateOne($post)
    {
        $target = self::where('id', $post['id'])->first();
        if ($target === null) {
            return false;
        }

        $target->update([
            'title'        => $post['title'],
            'date'         => $post['date'],
            'release_flag' => $post['release_flag'],
        ]);

        return true;
    }

    // -----------------------------------------------------------------

    /**
     * カテゴリを付与したビルダを返す
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected static function withCategories()
    {
        return self::with('post_categories');
    }
}
