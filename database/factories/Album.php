<?php

use App\Models\Album;
use Faker\Generator as Faker;

$factory->define(Album::class, function (Faker $faker) {
    return [
        "title"=> $faker->text,
        "genre_id"=>$faker->randomNumber(),
        "release_date"=>$faker->date(),
        "user_id"=>$faker->randomNumber(),

    ];
});
