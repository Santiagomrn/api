<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

use function PHPSTORM_META\type;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data'=>[
                'type'=>'required',
                'attributes'=>[
                    'name'=>'required',
                    'price'=> 'numeric|gt:0|required'
                ]
            ]
        ];
    }
 //funcion protegida que usa use Illuminate\Contracts\Validation\Validator;
// y use Illuminate\Http\Exceptions\HttpResponseException;
    protected function failedValidation(Validator $validator) {
        $error=[
            "code" => "ERROR-1",
            "title" =>"Uprocessable Entity"
        ];
        throw new HttpResponseException(response()->json(['errors'=>[$error]],422));
}
}
