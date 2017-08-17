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
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\Post::class, function (Faker\Generator $faker) {

    return [
        'flag' => $faker->boolean,
        'msg' => $faker->streetAddress,
        'ret' => $faker->buildingNumber,
    ];
});

$factory->define(App\Models\Product::class, function (Faker\Generator $faker) {

    return [
        'wine_name' => $faker->name,
        'important' => $faker->sentence,
        'description' => $faker->sentence,
    ];
});

$factory->define(App\Models\Grape::class, function (Faker\Generator $faker) {

    return [
        'grape_name' => $faker->colorName,
    ];
});

$factory->define(App\Models\Country::class, function (Faker\Generator $faker) {

    return [
        'country_name' => $faker->country,
    ];
});

$factory->define(App\Models\District::class, function (Faker\Generator $faker) {

    return [
        'district_name' => $faker->company,
    ];
});

$factory->define(App\Models\Cover::class, function (Faker\Generator $faker) {

    return [
        'cover_url' => $faker->imageUrl(640,480,null,true,null),
    ];
});

$factory->define(App\Models\Album::class, function (Faker\Generator $faker) {

    return [
        'album_url' => $faker->imageUrl(640,480,null,true,null),
    ];
});