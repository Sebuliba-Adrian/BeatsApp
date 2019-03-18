<?php

use App\Models\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        "comment"=> $faker->text,
        "track_id"=>$faker->randomNumber(),
        "user_id"=> $faker->randomNumber(),
    ];
});
