<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\PlayerPriceWatch;
use Faker\Generator as Faker;

$factory->define(PlayerPriceWatch::class, function (Faker $faker) {
    return [
        'user_id' => 0,
        'futbin_id' => 0,
        'title' => 'temp',
        'min_amount' => 0,
        'max_amount' => 0,
    ];
});
