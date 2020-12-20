<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Post;
use App\Models\PostCategory;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'title'        => $faker->text(16),
        'date'         => $faker->date(),
        'release_flag' => false,
    ];
});

$factory->define(PostCategory::class, function (Faker $faker, $attributes) {
    return [
        'post_id'  => isset($attributes['post_id']) ? $attributes['post_id'] : factory(Post::class)->create()->id,
        'category' => $faker->word,
    ];
});
