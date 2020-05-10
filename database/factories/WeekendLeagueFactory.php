<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\WeekendLeague;
use Faker\Generator as Faker;

$factory->define(WeekendLeague::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
    ];
});
