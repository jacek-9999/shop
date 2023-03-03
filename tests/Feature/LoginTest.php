<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    private $email    = 'test@example.com';
    private $password = 'password';

    public function test_successful_login(): void
    {

        $response = $this->post('/api/login', [
            'email' => $this->email,
            'password' => $this->password,
        ]);
        $responseData = json_decode($response->content());

        $this->assertEquals('User Logged In Successfully', $responseData->message);
        $this->assertTrue($responseData->status);
        $response->assertStatus(200);
    }

    public function test_access_to_restricted_route_with_token(): void
    {
        $token    = 'Bearer ' . $this->getToken();
        $route    = 'api/shops/1';
        $response = $this->get($route, ['Authorization' => $token, 'Accept' => 'application/json']);
        $response->assertStatus(200);
        $responseData = json_decode($response->content());
        $this->assertEquals(1, $responseData->id);
    }

    public function test_access_to_restricted_route_with_wrong_token(): void
    {
        $route    = 'api/shops/1';
        $response = $this->get($route, ['Authorization' => 'wrong token', 'Accept' => 'application/json']);
        $response->assertStatus(401);
        $this->assertEquals('{"message":"Unauthenticated."}', $response->content());
    }

    public function test_access_to_restricted_route_with_wrong_token_without_json_header(): void
    {
        $route    = 'api/shops/1';
        $response = $this->get($route, ['Authorization' => 'wrong token']);
        // redirect to web home site
        $response->assertStatus(302);
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
