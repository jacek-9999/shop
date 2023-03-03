<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $faker = Faker\Factory::create();

        return [
            'name' => $faker->word,
            'ean' => $faker->ean13(),
            'sku' => $faker->numerify(str_repeat('#', 12)),
        ];
    }
}
