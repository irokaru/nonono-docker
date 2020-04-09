<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\History;
use Faker\Generator as Faker;

$factory->define(History::class, function (Faker $faker) {
    return [
        'date'        => $faker->date,
        'discription' => $faker->text,
    ];
});
