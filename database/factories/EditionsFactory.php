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
$factory->define(App\Edition::class, function (Faker\Generator $faker) {
    $start_date = \Carbon\Carbon::today()->subWeeks(1);
    $end_date = \Carbon\Carbon::today()->addWeeks(1);

    return [
        'name' => 'Test edition',
        'description' => $faker->paragraph(2, false),
        'start_date' => $start_date,
        'end_date' => $end_date,
        'publish_results' => $end_date,
        'docs' => "Document 1\nDocument 2",
        'voting_places' => "Ajuntament\nBiblioteca",
        'sidebar' => '',
        'published' => 1,
    ];
});
