<?php

use Faker\Generator as Faker;

$factory->define(App\Xpath::class, function (Faker $faker) {
    return [
        'xpath' => $faker->creditCardNumber,
    ];
});
