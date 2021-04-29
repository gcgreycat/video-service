<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Video;
use Faker\Generator as Faker;

$factory->define(Video::class, function (Faker $faker) {
    return [
        'title' => $faker->text(50),
        'is_free' => $faker->boolean,
        'purchase_duration' => $faker->numberBetween(10000, 100000),
    ];
});

$factory->state(Video::class, 'free', [
    'is_free' => true,
]);

$factory->state(Video::class, 'non-free', [
    'is_free' => false,
]);
