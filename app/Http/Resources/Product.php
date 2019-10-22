<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    { //transformo los datos al formato json deseado
        $this->resource=(new ProductData($this->resource));

        return [
            'data'=>$this->resource
        ];
    }
}
