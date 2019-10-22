<?php

namespace App\Http\Controllers;

use App\Product; //se incluye para usar el model Product Larabel toma como nombre del modelo el plural products
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use PhpParser\Node\Name;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Product as ProductResource;
use App\Http\Resources\ProductCollection;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products=Product::get(); //obtengo los productos de la base de datos utilizando el modelo Product
        $products= new ProductCollection($products);
        return response()->json($products,200); //respondo con un json listando los productos y devolviendo un estatus 200
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        // si el producto es validado correctamente
        $data=$request['data']['attributes']; //obtengo los atributos del producto
        $product =  Product::create($data);//obtengo todo el contenido del la DB

        // Return a response with a product json
        // representation and a 201 status code
        return response()->json(new ProductResource($product),201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product=Product::findorfail($id);//busco el producto el la base usando el modelo product


       return response()->json(new ProductResource($product),200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update($id,ProductRequest $request)
    {
        $data=$request['data']['attributes']; //obtengo los atributos del producto
        //consultar a la base de datos
        $product=Product::findorfail($id);// busco el producto a actualizar con base a la id
        $product->update($data);//actualizo el producto
        return response()->json( new ProductResource($product),200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product=Product::findorfail($id);

        $product->delete();// Destruyo el producto
        return response("",204);
    }
}
