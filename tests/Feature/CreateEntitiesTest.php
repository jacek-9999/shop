<?php

namespace Tests\Feature;

//use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\Shop;
use Tests\TestCase;
use Faker;

class CreateEntitiesTest extends TestCase
{
//    use RefreshDatabase;

    public function testCreatesEntities(): void
    {
        $faker = Faker\Factory::create();
        $shop = new Shop();
        $shop->name      = $faker->company;
        $shop->postcode  = $faker->postcode;
        $shop->country   = $faker->country;
        $shop->city      = $faker->city;
        $shop->street    = $faker->streetAddress;
        $shop->latitude  = $faker->latitude;
        $shop->longitude = $faker->longitude;
        $shop->save();
        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product->name = $faker->word;
            $product->ean  = $faker->ean13();
            $product->sku  = $faker->numerify(str_repeat('#', 12));
            $product->shop_id = $shop->id;
            $product->save();
        }
        $this->fail();
    }
}
