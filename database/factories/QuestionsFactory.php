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
$factory->define(App\Question::class, function (Faker\Generator $faker) {
    return [
        'question' => $faker->sentence(12),
        'description' => $faker->paragraph(2),
        'template' => $faker->randomElement(['cards', 'simple','2column']),
        'min_options' => 0, // $faker->numberBetween(0,1)
        'max_options' => $faker->numberBetween(1,3),
        'display_cost' => $faker->boolean(),
        'random_order' => $faker->boolean()
    ];
});
