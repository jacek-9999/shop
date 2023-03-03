<?php

namespace App\Services;

use GuzzleHttp\Client;

class WeatherService
{
    private string $key;
    private string $url = 'https://api.openweathermap.org/data/2.5/weather';
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->key = env('OPEN_WEATHER_MAP_KEY');
    }


    public function downloadWeatherData($latitude, $longitude)
    {
        /*
         * https://openweathermap.org/current
         */
        $response = $this
            ->client
            ->get($this->url . "?lat=$latitude&lon=$longitude&appid=$this->key")
            ->getBody();
        $content = json_decode($response->getContents(), 1);
        return $content ?? [];
    }
}
