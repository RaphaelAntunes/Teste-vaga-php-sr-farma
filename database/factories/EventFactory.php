<?php
use Faker\Generator as Faker;

$factory->define(App\Models\Event::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'descricao' => $faker->paragraph,
        'start' => $faker->dateTimeBetween('now', '+30 days')->format('Y-m-d H:i:s'),
        'end' => $faker->dateTimeBetween('+31 days', '+60 days')->format('Y-m-d H:i:s'),
        'usr_responsavel' => $faker->email,
    ];
});
