<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\BlogPost;
use Faker\Generator as Faker;

$factory->define(BlogPost::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(8),
        'content' => $faker->paragraphs(6,true),
        'created_at' => $faker->dateTimeBetween('-3 months'),

    ];
});
$factory->state(BlogPost::class, 'new_title',function (Faker $faker){
    return [
        'title' => 'A new post for testing',
        // 'content' => 'This is the content part of the new post'
    ];
});
