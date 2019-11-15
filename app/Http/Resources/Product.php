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
    {

        return [

            'type'=>'Products',
            'id'=> $this->id,
            'attributes'=>[
                'name'=>$this->name,
                'price'=> $this->price
            ],
            'links'=>[
                'self'=>config('app.url')."/api/products/".$this->id
            ]


        ];
    }
}
