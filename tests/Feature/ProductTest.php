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
    /**
     * CREATE-1
     */
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
    /**
     * CREATE-2
     */
    public function test_client_can_create_a_product_name_is_not_sended(){
          // Given

          $productData = [
            'name' => '',
            'price' => '23.30'
        ];

        // When
        $response = $this->json('POST', '/api/products', $productData);

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);

        // Assert the response has the correct structure

        $response->assertJsonStructure([
            'errors'=>[[
                'code',
                'title'
            ]]
            ]);

        // Assert the product was created
        // with the correct data
        $error=[
            "code" => "Error-1",
            "title" =>"Uprocessable Entity"
        ];
        //respuesta del jason de error
        $response->assertJsonFragment(['errors'=>[$error]]);
    }
     /**
     * CREATE-3
     */
    public function test_client_can_create_a_product_price_is_not_sended(){
          // Given

          $productData = [
            'name' => 'product',
            'price' => ''
        ];

        // When
        $response = $this->json('POST', '/api/products', $productData);

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);

        // Assert the response has the correct structure

        $response->assertJsonStructure([
            'errors'=>[[
                'code',
                'title'
            ]]
            ]);

        // Assert the product was created
        // with the correct data
        $error=[
            "code" => "Error-1",
            "title" =>"Uprocessable Entity"
        ];
        //respuesta del jason de error
        $response->assertJsonFragment(['errors'=>[$error]]);
    }
    /**
    * CREATE-4
    */
    public function test_client_can_create_a_product_price_is_not_number(){
        // Given

        $productData = [
          'name' => 'product',
          'price' => 'not number'
      ];

      // When
      $response = $this->json('POST', '/api/products', $productData);

      // Then
      // Assert it sends the correct HTTP Status
      $response->assertStatus(422);

      // Assert the response has the correct structure

      $response->assertJsonStructure([
          'errors'=>[[
              'code',
              'title'
          ]]
          ]);

      // Assert the product was created
      // with the correct data
      $error=[
          "code" => "Error-1",
          "title" =>"Uprocessable Entity"
      ];
      //respuesta del jason de error
      $response->assertJsonFragment(['errors'=>[$error]]);
    }
    /**
    * CREATE-5
    */
    public function test_client_can_create_a_product_price_is_less_or_equal_to_0(){
        // Given

        $productData = [
          'name' => 'product',
          'price' => '-3'
      ];

      // When
      $response = $this->json('POST', '/api/products', $productData);

      // Then
      // Assert it sends the correct HTTP Status
      $response->assertStatus(422);

      // Assert the response has the correct structure

      $response->assertJsonStructure([
          'errors'=>[[
              'code',
              'title'
          ]]
          ]);

      // Assert the product was created
      // with the correct data
      $error=[
          "code" => "Error-1",
          "title" =>"Uprocessable Entity"
      ];
      //respuesta del jason de error
      $response->assertJsonFragment(['errors'=>[$error]]);
    }

    /**
     * UPDATE-1
     */
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

        //given
        $productData = [
            'name' => 'update',
            'price' => '32.90'
        ];

        $response = $this->json('PUT', '/api/products/'.$product['id'], $productData);
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(200);
        //la repuesta tiene la estructura json dada
        $response->assertJsonStructure([
            'id',
            'name',
            'price'
        ]);

        //la repuesta es el json con los datos correctos
        $response->assertJsonFragment([
            'id'=>$product['id'],
            'name' => 'update',
            'price' => '32.90'
        ]);

        // Assert the product was updated
        // with the correct data
        $this->assertDatabaseHas(
            'products',
            [
                'id' =>$product['id'] ,
                'name' => 'update',
                'price' => '32.90'
            ]
        );
    }
    /**
     * UPDATE-2
     */
    public function test_client_can_update_a_product_price_is_not_number()
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

        //given
        $productData = [
            'name' => 'update',
            'price' => ''
        ];

        $response = $this->json('PUT', '/api/products/'.$product['id'], $productData);
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);
        //la repuesta tiene la estructura json dada
        $response->assertJsonStructure([
            'errors'=>[[
                'code',
                'title'
            ]]
            ]);

        // Assert the product was created
        // with the correct data
        $error=[
            "code" => "Error-1",
            "title" =>"Uprocessable Entity"
        ];
        //respuesta del jason de error
        $response->assertJsonFragment(['errors'=>[$error]]);

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
        $product['price']=(string)$product['price'];
        //given
        $response= $this->GET('/api/products/'.$product['id']);
         // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(200);
        //la repuesta tiene la estructura json dada
         // Assert the response has the correct structure
        $response->assertJsonStructure([
            'id',
            'name',
            'price'
        ]);
        //la repuesta es el json con los datos correctos
        // Assert the product was created
        // with the correct data


        $response->assertJsonFragment([
            'name' =>   $product['name'],
            'price' =>  $product['price']
        ]);
    }

    public function test_client_can_show_a_list_of_products()
    {
         // creo una lista de productos
         factory(Product::class,20)->create();
        //given
        $response= $this->GET('/api/products');
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(200);
        // Assert the response has a list of 20 json
        //la repuesta contiene una lista de 20 json
        $response->assertJsonCount(20);

        // Assert the response has the correct structure in each object at list
        $response->assertJsonStructure(['*'=>[
            'id',
            'name',
            'price'
        ]]);

    }



    public function test_client_can_delete_a_product()
    {   //creo un producto
        $product=factory(Product::class)->create();
        //verifico que exista el producto con los datos correctos
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
       //given
        $response= $this->delete('/api/products/'.$product['id']);
        // Assert it sends the correct HTTP Status
        $response->assertStatus(204);
        // verifico que no exista el producto en la base de datos
        $this->assertDatabaseMissing('products',
        [
            'id' =>$product['id'] ,
            'name' => $product['name'],
            'price' => $product['price']
        ]);
    }
}

