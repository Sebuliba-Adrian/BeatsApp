<?php

use App\Models\Track;
use Faker\Generator as Faker;

$factory->define(Track::class, function (Faker $faker) {
    return [
        "title" => $faker->text,
        "file_url"=> $faker->imageUrl(),
        "album_id"=> $faker->randomNumber(),
    ];
});
