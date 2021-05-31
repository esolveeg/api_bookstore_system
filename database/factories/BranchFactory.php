<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Branch;
use Faker\Generator as Faker;

$factory->define(Branch::class, function (Faker $faker) {
	$name =  $faker->name;
    return [
        "name" => $name,
		"email" => $faker->email,
		"address" => $faker->address,
		"phone" => rand(1001234567,1098765432),
		"rent" => rand(3000,8000),
		"bills" => rand(2000,5000)
    ];
});
