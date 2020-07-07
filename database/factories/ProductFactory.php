<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Faker\Generator as Faker;
use Webpatser\Uuid\Uuid;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'uuid' => Uuid::generate(4)->string,
        'user_id' => factory(User::class)->create()->id,
        'name' => $faker->name, 
        'description' => $faker->text, 
        'category_id' => factory(Category::class)->create()->id,
        'price' => mt_rand(1, 1000),
        'stock' => rand(0, 100),
    ];
});
