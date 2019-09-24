<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/greeting', function (Request $request){
    return "Hello World!";
});

Route::POST('/products', 'ProductController@store');
/* agrego la ruta de /products y uso el cotrolador llamando a la función store */

Route::GET('/products', 'ProductController@index');
/* agrego la ruta de /products y uso el cotrolador llamando a la función index */

Route::PUT('/products/{id}', 'ProductController@update');
/* agrego la ruta de /products y uso el cotrolador llamando a la función update */

Route::get('/products/{id}', 'ProductController@show');
/* agrego la ruta de /products y uso el cotrolador llamando a la función show */

Route::delete('/products/{id}', 'ProductController@destroy');
/* agrego la ruta de /products y uso el cotrolador llamando a la función delete */
