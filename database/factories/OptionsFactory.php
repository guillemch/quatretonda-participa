<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Option::class, function (Faker\Generator $faker) {
    return [
        'option' => $faker->sentence(6),
        'description' => $faker->text(300),
        'motivation' => $faker->paragraph(6),
        'attachments' => '',
        'cost' => $faker->numberBetween(0,100000),
        'salt' => str_random(12),
    ];
});
