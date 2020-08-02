<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $table = 'games';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'release_date', 'release_flag', 'thumbnail_path',
        'category', 'infomation', 'url',
    ];

    protected $casts = [
        'release_flag' => 'boolean',
    ];

    public static function getReleasedGames()
    {
        return self::where('release_flag', true)->orderBy('release_date', 'desc')->get();
    }
}
