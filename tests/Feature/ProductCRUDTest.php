<?php

namespace Tests\Feature;

use Tests\TestCase;
use Faker;

class ProductCRUDTest extends TestCase
{
    private $email    = 'test@example.com';
    private $password = 'password';

    public function testCreateProduct(): void
    {
        $token          = 'Bearer ' . $this->getToken();
        $createRoute    = 'api/products';
        $faker          = Faker\Factory::create();
        $ean            = $faker->ean13();
        $sku            = $faker->numerify(str_repeat('#', 12));
        $productForSave = [
            'ean'     => $ean,
            'sku'     => $sku,
            'name'    => 'gumowce',
            'shop_id' => 1,
        ];
        $responseCreate  = $this->post($createRoute, $productForSave, ['Authorization' => $token, 'Accept' => 'application/json']);
        $createdProductId = json_decode($responseCreate->content(), 1)['sku'];

        $route    = 'api/products/'.$createdProductId;
        $response = $this->get($route, ['Authorization' => $token, 'Accept' => 'application/json']);
        $response->assertStatus(200);
        $savedProduct = json_decode($response->content());
        $this->assertEquals($ean, $savedProduct->ean);
        $this->assertEquals($sku, $savedProduct->sku);
        $this->assertEquals('gumowce', $savedProduct->name);
        $this->assertEquals(1, $savedProduct->shop_id);
    }

    public function testDeleteProduct(): void
    {
        // 1/2 create product
        $token          = 'Bearer ' . $this->getToken();
        $createRoute    = 'api/products';
        $faker          = Faker\Factory::create();
        $ean            = $faker->ean13();
        $sku            = $faker->numerify(str_repeat('#', 12));
        $productForSave = [
            'ean'     => $ean,
            'sku'     => $sku,
            'name'    => 'gumowce',
            'shop_id' => 1,
        ];
        $responseCreate  = $this->post($createRoute, $productForSave, ['Authorization' => $token, 'Accept' => 'application/json']);
        $responseCreate->assertStatus(201);
        $createdProductId = json_decode($responseCreate->content(), 1)['sku'];

        // 2/2 delete product
        $route    = 'api/products/'.$createdProductId;
        $response = $this->delete($route, ['Authorization' => $token, 'Accept' => 'application/json']);
        $response->assertStatus(200);
        $response = $this->get($route, ['Authorization' => $token, 'Accept' => 'application/json']);
        $response->assertStatus(404);
    }

    public function testPaginateProduct(): void
    {
        $token          = 'Bearer ' . $this->getToken();
        $indexProductsRoute    = 'api/products';

        $responseCreate  = $this->get($indexProductsRoute, ['Authorization' => $token, 'Accept' => 'application/json']);
        $defaultPagination = json_decode($responseCreate->content(), 1);
        $this->assertEquals(10, count($defaultPagination));

        $indexProductsRoutePagination    = 'api/products?skip=0&take=5';
        $responseCreate  = $this->get($indexProductsRoutePagination, ['Authorization' => $token, 'Accept' => 'application/json']);
        $customPagination = json_decode($responseCreate->content(), 1);
        $this->assertEquals(5, count($customPagination));
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
