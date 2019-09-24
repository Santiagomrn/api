<?php

namespace Tests\Feature;

use App\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{

    use RefreshDatabase;
    public function test_client_can_create_a_product()
    {

        // Given

        $productData = [
            'name' => 'Super Product',
            'price' => '23.30'
        ];

        // When
        $response = $this->json('POST', '/api/products', $productData);

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(201);

        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'id',
            'name',
            'price'
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'name' => 'Super Product',
            'price' => '23.30'
        ]);

        $body = $response->decodeResponseJson();

        // Assert product is on the database
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $body['id'],
                'name' => 'Super Product',
                'price' => '23.30'
            ]
        );
    }

    public function test_client_can_show_a_product()
    {

        //inserto el producto
        $product=factory(Product::class)->create();
        //exite un producto con una ID
        $this->assertDatabaseHas(
            'products',
            [
                'id' =>$product['id'] ,
                'name' => $product['name'],
                'price' => $product['price']
            ]
        );
        $product['id']=(string)$product['id'];

        $response= $this->GET('/api/products/'.$product['id']);


        $response->assertStatus(200);
        //la repuesta tiene la estructura json dada
        $response->assertJsonStructure([
            'id',
            'name',
            'price'
        ]);
        //la repuesta es el json con los datos correctos
        $product['price']=(string)$product['price'];
        $response->assertJsonFragment([
            'name' =>   $product['name'],
            'price' =>  $product['price']
        ]);
    }

    public function test_client_can_show_a_list_of_products()
    {
         // creo una lista de productos
         factory(Product::class,20)->create();

        $response= $this->GET('/api/products');

        $response->assertStatus(200);
        //la repuesta contiene una lista de 20 jsons
        $response->assertJsonCount(20);

    }

    public function test_client_can_update_a_product()
    {
        //inserto el producto
        $product=factory(Product::class)->create();
        //exite el producto con una ID
        $this->assertDatabaseHas(
            'products',
            [
                'id' =>$product['id'] ,
                'name' => $product['name'],
                'price' => $product['price']
            ]
        );
        $product['id']=(string)$product['id'];
        $product['price']=(string)$product['price'];
        $productData = [
            'name' => $product['name'],
            'price' => $product['price']
        ];

        $response = $this->json('PUT', '/api/products/'.$product['id'], $productData);


        $response->assertStatus(200);
        //la repuesta tiene la estructura json dada
        $response->assertJsonStructure([
            'id',
            'name',
            'price'
        ]);
        //la repuesta es el json con los datos correctos

        $response->assertJsonFragment([
            'name' =>   $product['name'],
            'price' =>  $product['price']
        ]);

        $this->assertDatabaseHas(
            'products',
            [
                'id' =>$product['id'] ,
                'name' => $product['name'],
                'price' => $product['price']
            ]
        );
    }

    public function test_client_can_delete_a_product()
    {
        $product=factory(Product::class)->create();

        $this->assertDatabaseHas(
            'products',
            [
                'id' =>$product['id'] ,
                'name' => $product['name'],
                'price' => $product['price']
            ]
        );
        $product['id']=(string)$product['id'];
        $product['price']=(string)$product['price'];
        $response= $this->delete('/api/products/'.$product['id']);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('products',
        [
            'id' =>$product['id'] ,
            'name' => $product['name'],
            'price' => $product['price']
        ]);




    }
}

