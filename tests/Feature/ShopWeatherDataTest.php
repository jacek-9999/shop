<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopWeatherDataTest extends TestCase
{
    private $email    = 'test@example.com';
    private $password = 'password';

    public function testReadOneShop(): void
    {
        $token    = 'Bearer ' . $this->getToken();
        $route    = 'api/shops/1/weather';
        $response = $this->get($route, ['Authorization' => $token, 'Accept' => 'application/json']);
        $response->assertStatus(200);
        $weatherApiResponse = json_decode($response->content());
        $this->assertEquals(3600, $weatherApiResponse->timezone);
        $this->assertEquals('Starosielce', $weatherApiResponse->name);
        $this->assertTrue(isset($weatherApiResponse->visibility));
        $this->assertTrue(isset($weatherApiResponse->weather));
        $this->assertTrue(isset($weatherApiResponse->clouds));
        $this->assertTrue(isset($weatherApiResponse->id));
    }

    private function getToken()
    {
        $response = $this->post('/api/login', [
            'email' => $this->email,
            'password' => $this->password,
        ]);
        $responseData = json_decode($response->content());
        return $responseData->token;
    }
}
