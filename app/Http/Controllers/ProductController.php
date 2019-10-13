<?php

namespace App\Http\Controllers;

use App\Product; //se incluye para usar el model Product Larabel toma como nombre del modelo el plural products
use Illuminate\Http\Request;
use PhpParser\Node\Name;
use Illuminate\Support\Facades\Validator;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $products=Product::get(); //obtengo los productos de la base de datos utilizando el modelo Product
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
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'price'=> 'numeric|gt:0|required'

        ]);

        if ($validator->fails()) {
            $error=[
                "code" => "Error-1",
                "title" =>"Uprocessable Entity"
            ];
            return response()->json(['errors'=>[$error]],422);
        }


        // si el producto es validado correctamente
        $product = Product::create($request->all());//obtengo todo el contenido del la DB

        // Return a response with a product json
        // representation and a 201 status code
        return response()->json($product,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product=Product::find($id);//busco el producto el la base usando el modelo product
        //validar la existencia del producto con ese ID
        if($product==''){
            $error=[
                "code" => "Error-2",
                "title" =>"Not Found"
            ];
            return response()->json(['errors'=>[$error]],404);
        }
        return response()->json($product,200);
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
    public function update($id,Request $request)
    {
        //validar datos del request
        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'price'=> 'numeric|gt:0|required'

        ]);

        if ($validator->fails()) {
            $error=[
                "code" => "Error-1",
                "title" =>"Uprocessable Entity"
            ];
            return response()->json(['errors'=>[$error]],422);
        }
        //consultar a la base de datos
        $product=Product::find($id);// busco el producto a actualizar con base a la id
        //validación de que exite la entidad con la id adecuada
        if($product==''){
            $error=[
                "code" => "Error-2",
                "title" =>"Not Found"
            ];
            return response()->json(['errors'=>[$error]],404);
        }
        $product->update($request->all());//actualizo el producto
        return response()->json($product,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product=Product::find($id);
        if($product==''){
            $error=[
                "code" => "Error-2",
                "title" =>"Not Found"
            ];
            return response()->json(['errors'=>[$error]],404);
        }
        $product->delete();// Destruyo el producto
        return response("",204);
    }
}
