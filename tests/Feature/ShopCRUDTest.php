<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Faker;

class ShopCRUDTest extends TestCase
{
    private $email    = 'test@example.com';
    private $password = 'password';

    public function testReadOneShop(): void
    {
        $token    = 'Bearer ' . $this->getToken();
        $route    = 'api/shops/1';
        $response = $this->get($route, ['Authorization' => $token, 'Accept' => 'application/json']);
        $response->assertStatus(200);
        $responseData = json_decode($response->content());
        $this->assertEquals(1, $responseData->id);
        $this->assertTrue(isset($responseData->created_at));
        $this->assertTrue(isset($responseData->updated_at));
        $this->assertTrue(isset($responseData->name));
        $this->assertTrue(isset($responseData->postcode));
        $this->assertTrue(isset($responseData->country));
        $this->assertTrue(isset($responseData->city));
        $this->assertTrue(isset($responseData->street));
        $this->assertTrue(isset($responseData->latitude));
        $this->assertTrue(isset($responseData->longitude));
    }

    public function testReadManyShops(): void
    {
        $token    = 'Bearer ' . $this->getToken();
        $route    = 'api/shops';
        $response = $this->get($route, ['Authorization' => $token, 'Accept' => 'application/json']);
        $response->assertStatus(200);
        $responseData = json_decode($response->content());
        $this->assertEquals(10, count($responseData));
    }

    public function testReadManyShopsPagination(): void
    {
        $token    = 'Bearer ' . $this->getToken();
        $route    = 'api/shops?skip=0&take=5';
        $response = $this->get($route, ['Authorization' => $token, 'Accept' => 'application/json']);
        $response->assertStatus(200);
        $responseData = json_decode($response->content());
        $this->assertEquals(5, count($responseData));
    }

    public function testSaveNewShop(): void
    {
        $token    = 'Bearer ' . $this->getToken();
        $route    = 'api/shops';
        $faker    = Faker\Factory::create();
        $shop     = [
            'name'      => $faker->company,
            'postcode'  => $faker->postcode,
            'country'   => $faker->country,
            'city'      => $faker->city,
            'street'    => $faker->streetAddress,
            'latitude'  => $faker->latitude,
            'longitude' => $faker->longitude,
        ];
        $response = $this->post($route, $shop, ['Authorization' => $token, 'Accept' => 'application/json']);
        $response->assertStatus(201);
        $responseData = json_decode($response->content(), 1);
        $this->assertTrue(isset($responseData['id']));
        $this->assertEquals($shop['name'],      $responseData['name']);
        $this->assertEquals($shop['postcode'],  $responseData['postcode']);
        $this->assertEquals($shop['country'],   $responseData['country']);
        $this->assertEquals($shop['city'],      $responseData['city']);
        $this->assertEquals($shop['street'],    $responseData['street']);
        $this->assertEquals($shop['latitude'],  $responseData['latitude']);
        $this->assertEquals($shop['longitude'], $responseData['longitude']);
    }

    public function testUpdateShop()
    {
        // 1/2 create new shop
        $token        = 'Bearer ' . $this->getToken();
        $createRoute  = 'api/shops';
        $faker        = Faker\Factory::create();
        $shop = [
            'name'      => $faker->company,
            'postcode'  => $faker->postcode,
            'country'   => $faker->country,
            'city'      => $faker->city,
            'street'    => $faker->streetAddress,
            'latitude'  => $faker->latitude,
            'longitude' => $faker->longitude,
        ];
        $responseCreate = $this->post($createRoute, $shop, ['Authorization' => $token, 'Accept' => 'application/json']);
        $createdShopId = json_decode($responseCreate->content(), 1)['id'];

        // 2/2 update created shop
        $newCountry     = $faker->country;
        $updateRoute    = 'api/shops/'.$createdShopId;
        $responseUpdate = $this->put($updateRoute, ['country' => $newCountry], ['Authorization' => $token, 'Accept' => 'application/json']);
        $updateResult   = json_decode($responseUpdate->content(), 1);
        $this->assertEquals($newCountry, $updateResult['country']);
    }

    public function testDeleteShop()
    {
        // 1/2 create new shop
        $token        = 'Bearer ' . $this->getToken();
        $createRoute  = 'api/shops';
        $faker        = Faker\Factory::create();
        $shop = [
            'name'      => $faker->company,
            'postcode'  => $faker->postcode,
            'country'   => $faker->country,
            'city'      => $faker->city,
            'street'    => $faker->streetAddress,
            'latitude'  => $faker->latitude,
            'longitude' => $faker->longitude,
        ];
        $responseCreate = $this->post($createRoute, $shop, ['Authorization' => $token, 'Accept' => 'application/json']);
        $createdShopId = json_decode($responseCreate->content(), 1)['id'];

        // 2/2 delete created shop
        $newCountry     = $faker->country;
        $updateRoute    = 'api/shops/'.$createdShopId;
        $responseUpdate = $this->delete($updateRoute, [], ['Authorization' => $token, 'Accept' => 'application/json']);
        $deleteResult   = json_decode($responseUpdate->content(), 1);
        $this->assertEquals(['deleted' => true], $deleteResult);

        $route    = 'api/shops/' . $createdShopId;
        $response = $this->get($route, ['Authorization' => $token, 'Accept' => 'application/json']);
        $response->assertStatus(404);
        $this->assertEquals('{"status":"not found"}', $response->content());
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
