<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name'=>$faker->name,
        'price'=>$faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 30)
    ];
});
