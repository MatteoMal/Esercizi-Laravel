<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;

class AuthStoreRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
           "idAuth" => "required|integer",
           "idContatto" => "required|integer",
           "user" => "required|string|max:255",
           "sfida" => "string|max:255",
           "secretJWT" => "string|max:255",
           "inizioSfida" => "integer",
           "obbligoCampo" => "integer"
        ];
    }
}
