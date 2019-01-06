<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(\App\Task::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'description' => $faker->text,
        'budget' => $faker->numberBetween(0, 100),
        'location' => $faker->name,
        'due_date' => $faker->date(),
        'due_time' => $faker->word,
        'user_id' => function() {
            return factory(\App\User::class)->create()->id;
        }
    ];
});