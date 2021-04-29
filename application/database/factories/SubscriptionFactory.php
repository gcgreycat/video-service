<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Subscription;
use Faker\Generator as Faker;

$factory->define(Subscription::class, function (Faker $faker) {
    return [
        'time_start_at' => $faker->dateTimeBetween('-1 week'),
        'package_id' => null,
        'user_id' => null,
    ];
});
