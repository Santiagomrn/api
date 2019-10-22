<?php

namespace App\Http\Resources;
use App\Http\Resources\Product as ProductResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $this->collection->transform(function (Product $product) {
            return (new ProductData($product));
        });

        return [
            'data'=>$this->collection,

        ];

    }
}
