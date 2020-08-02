<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Game;
use Faker\Generator as Faker;

$factory->define(Game::class, function (Faker $faker) {
    return [
        'title'          => $faker->title,
        'release_date'   => $faker->date,
        'release_flag'   => true,
        'thumbnail_path' => '/img/game/hoge.jpg',
        'category'       => 'RPG',
        'infomation'     => $faker->text(30),
        'url'            => $faker->url,
    ];
});
