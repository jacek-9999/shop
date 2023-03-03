<?php

namespace Tests\Feature;

use App\Services\WeatherService;
use GuzzleHttp\Client;
use Tests\TestCase;
use Faker;

class WeatherTest extends TestCase
{
    public function testWeatherService(): void
    {
        $client = new Client();
        $weatherService = new WeatherService($client);
        $faker = Faker\Factory::create();
        $latitude  = $faker->latitude();
        $longitude = $faker->longitude();
        $output = $weatherService->downloadWeatherData($latitude, $longitude);
        $this->assertTrue(isset($output['coord']));
        $this->assertTrue(isset($output['weather']));
        $this->assertTrue(isset($output['base']));
//        $output = [];
//        var_dump($output);exit;
//        $this->assertEquals([], $output);
//        $this->fail();
    }
}
