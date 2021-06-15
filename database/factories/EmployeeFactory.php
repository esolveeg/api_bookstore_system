<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Employee;
use Faker\Generator as Faker;

$factory->define(Employee::class, function (Faker $faker) {
    return [
        "name"		=>	$faker->name,
		"email"		=>	$faker->email,
		"phone"		=>	rand(1001234567,1098765432),
		"salary"	=>	rand(2000,5000),
		"branch_id" => factory(App\Branch::class),
    ];
});
