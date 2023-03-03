<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker;

class ShopFactory extends Factory
{
    public function definition(): array
    {
        $faker = Faker\Factory::create();
        return [
            'name'      => $faker->company,
            'postcode'  => $faker->postcode,
            'country'   => $faker->country,
            'city'      => $faker->city,
            'street'    => $faker->streetAddress,
            'latitude'  => $faker->latitude,
            'longitude' => $faker->longitude,
        ];
    }
}
