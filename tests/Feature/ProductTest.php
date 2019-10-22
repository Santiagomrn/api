<?php

namespace Tests\Feature;

use App\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function PHPSTORM_META\type;

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
            'data'=>[
                'type'=>'Products',
                'attributes'=>[
                    'name'=>'Other class homework',
                    'price'=> '30'
                ]
            ]
        ];

        // When
        $response = $this->json('POST', '/api/products', $productData);

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(201);

        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'data'=>[
                'type',
                'id',
                'attributes'=>[
                    'name',
                    'price'
                ],
                'links'=>[
                    'self'
                ]
            ]
        ]);
        $body = $response->decodeResponseJson();
        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'data'=>[
                "type"=>$body['data']['type'],
                "id"=>$body['data']['id'] ,
                'attributes'=>[
                    'name'=>$body['data']['attributes']['name'],
                    'price'=>$body['data']['attributes']['price']
                ],
                'links'=>[
                    'self'=>"http://127.0.0.1:8000/api/products/".$body['data']['id']
                ]
            ]
        ]);



        // Assert product is on the database
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $body['data']['id'],
                'name' => $productData['data']['attributes']['name'],
                'price' => $productData['data']['attributes']['price']
            ]
        );
    }
    /**
     * CREATE-2
     */
    public function test_client_can_create_a_product_name_is_not_sended(){
          // Given

          $productData = [
            'data'=>[
                'type'=>'Products',
                'attributes'=>[
                    'name'=>'',
                    'price'=> '1'
                ]
            ]
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
            "code" => "ERROR-1",
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
            'data'=>[
                'type'=>'Products',
                'attributes'=>[
                    'name'=>'Other class homework',
                    'price'=> ''
                ]
            ]
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
            "code" => "ERROR-1",
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
            'data'=>[
                'type'=>'Products',
                'attributes'=>[
                    'name'=>'Other class homework',
                    'price'=> 'w'
                ]
            ]
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
          "code" => "ERROR-1",
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
            'data'=>[
                'type'=>'Products',
                'attributes'=>[
                    'name'=>'Other class homework',
                    'price'=> '-20'
                ]
            ]
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
          "code" => "ERROR-1",
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
            'data'=>[
                'type'=>'Products',
                'attributes'=>[
                    'name'=>'Other class homework',
                    'price'=> '30'
                ]
            ]
        ];
        $response = $this->json('PUT', '/api/products/'.$product['id'], $productData);
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(200);
        //la repuesta tiene la estructura json dada
               // Assert the response has the correct structure
               $response->assertJsonStructure([
                'data'=>[
                    'type',
                    'id',
                    'attributes'=>[
                        'name',
                        'price'
                    ],
                    'links'=>[
                        'self'
                    ]
                ]
            ]);
            $body = $response->decodeResponseJson();
            // Assert the product was created
            // with the correct data
            $response->assertJsonFragment([
                'data'=>[
                    "type"=>$body['data']['type'],
                    "id"=>$body['data']['id'] ,
                    'attributes'=>[
                        'name'=>$body['data']['attributes']['name'],
                        'price'=>$body['data']['attributes']['price']
                    ],
                    'links'=>[
                        'self'=>"http://127.0.0.1:8000/api/products/".$body['data']['id']
                    ]
                ]
            ]);



            // Assert product is on the database
            $this->assertDatabaseHas(
                'products',
                [
                    'id' => $body['data']['id'],
                    'name' => $productData['data']['attributes']['name'],
                    'price' => $productData['data']['attributes']['price']
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
            'data'=>[
                'type'=>'Products',
                'attributes'=>[
                    'name'=>'Other class homework',
                    'price'=> 'w'
                ]
            ]
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
            "code" => "ERROR-1",
            "title" =>"Uprocessable Entity"
        ];
        //respuesta del jason de error
        $response->assertJsonFragment(['errors'=>[$error]]);

    }
    /**
     * UPDATE-3
    */
    public function test_client_can_update_a_product_price_is_less_or_equal_to_0()
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
            'data'=>[
                'type'=>'Products',
                'attributes'=>[
                    'name'=>'Other class homework',
                    'price'=> '-20'
                ]
            ]
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
            "code" => "ERROR-1",
            "title" =>"Uprocessable Entity"
        ];
        //respuesta del jason de error
        $response->assertJsonFragment(['errors'=>[$error]]);
    }
    /**
     * UPDATE-4
    */
    public function test_client_can_update_a_product_id_not_exist()
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
        //generamos una id inexistente
        $id=$product['id']+1;
        $productData = [
            'data'=>[
                'type'=>'Products',
                'attributes'=>[
                    'name'=>'update',
                    'price'=> '20'
                ]
            ]
        ];

        $response = $this->json('PUT', '/api/products/'.$id, $productData);
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(404);
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
            "code" => "ERROR-2",
            "title" =>"Not Found"
        ];
        //respuesta del jason de error
        $response->assertJsonFragment(['errors'=>[$error]]);
    }
    /**
     * SHOW-1
     */
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
            'data'=>[
                'type',
                'id',
                'attributes'=>[
                    'name',
                    'price'
                ],
                'links'=>[
                    'self'
                ]
            ]
        ]);
        $body = $response->decodeResponseJson();
        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'data'=>[
                "type"=>$body['data']['type'],
                "id"=>$body['data']['id'] ,
                'attributes'=>[
                    'name'=>$body['data']['attributes']['name'],
                    'price'=>$body['data']['attributes']['price']
                ],
                'links'=>[
                    'self'=>"http://127.0.0.1:8000/api/products/".$body['data']['id']
                ]
            ]
        ]);

    }
    /**
     * SHOW-2
     */
    public function test_client_can_show_a_product_id_not_exist()
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
        //generamos una id inexistente
        $id=$product['id']+1;
        //given
        $response= $this->GET('/api/products/'.$id);
         // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(404);
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
            "code" => "ERROR-2",
            "title" =>"Not Found"
        ];
        //respuesta del jason de error
        $response->assertJsonFragment(['errors'=>[$error]]);

    }
    /**
     * DELETE-1
     */
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
    /**
     * DELETE-2
     */
    public function test_client_can_delete_a_product_id_not_exist()
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
        //genero una id inexistente
        $id=$product['id']+1;
       //given
        $response= $this->delete('/api/products/'.$id);
        // Assert it sends the correct HTTP Status
        $response->assertStatus(404);
        // verifico que no exista el producto en la base de datos
        $response->assertJsonStructure([
            'errors'=>[[
                'code',
                'title'
            ]]
            ]);

        // Assert the product was created
        // with the correct data
        $error=[
            "code" => "ERROR-2",
            "title" =>"Not Found"
        ];
        //respuesta del jason de error
        $response->assertJsonFragment(['errors'=>[$error]]);

    }
    /**
     * LIST-1
     */
    public function test_client_can_show_a_list_of_products()
    {
         // creo una lista de dos productos
        factory(Product::class,2)->create();
        //given
        $response= $this->GET('/api/products');
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(200);
        // Assert the response has a list of 2 json
        //la repuesta contiene una lista de 2 json
        $response->assertJsonCount(1);

        // Assert the response has the correct structure in each object at list
        $response->assertJsonStructure([
            'data'=>['*'=> [
                        'type',
                        'id',
                        'attributes'=>[
                            'name',
                            'price'
                        ],
                        'links'=>[
                            'self'
                        ]
                    ]
                ]
        ]);

    }
    /**
     * LIST-2
     */
    public function test_client_can_show_a_list_of_products_whitout_products()
    {

        //given
        $response= $this->GET('/api/products');
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(200);
        // Assert the response has a list of 0
        //la repuesta contiene una lista de 0
        $response->assertJsonCount(1);

        // Assert the response has the correct structure in each object at list
        $response->assertJsonStructure(['data'=>[]]);

    }


}

