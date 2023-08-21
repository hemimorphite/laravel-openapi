<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:3|max:255',
            //'products.*.name' => 'nullable',
            //'products.*.price' => 'nullable',
        ];
    }

    /**
    * Handle a failed validation attempt.
    *
    * @param  \Illuminate\Contracts\Validation\Validator  $validator
    *
    * @return \Illuminate\Http\Response
    */
    protected function failedValidation(Validator $validator)
    {   
        $errors = $validator->errors();
        
        return response()->json([
            "message" => "Invalid data provided",
            "errors" => $errors,
        ], 422);
    }
}
