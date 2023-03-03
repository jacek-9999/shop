<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use App\Models\Shop;
use App\Models\Product;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Shop::factory()->create([
            'name'      => 'ACME',
            'postcode'  => '1232132131',
            'country'   => 'POLAND',
            'city'      => 'Białystok',
            'street'    => 'Szkolna 17',
            'latitude'  => '53.12340',
            'longitude' => '23.08638',
        ]);

        User::factory(4)->create();
        Shop::factory(24)->has(Product::factory(1875))->create();
        User::factory()->create([
            'first_name' => 'Janusz',
            'last_name' => 'Nosacz',
            'email' => 'test@example.com',
        ]);
    }
}
