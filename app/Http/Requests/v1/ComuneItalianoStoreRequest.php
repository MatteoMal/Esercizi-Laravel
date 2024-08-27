<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;

class ComuneItalianoStoreRequest extends FormRequest
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
            "idComuneItaliano" => "required|integer",
           "nome" => "required|string|max:45",
           "regione" => "required|string|max:45",
           "capoluogo" => "required|string|max:50",
           "provincia" => "required|string|max:45",
           "siglaAutomobilistica"  =>"required|string|max:5",
           "capInizio" => "required|string|max:50",
           "capFine" => "required|string|max:50",
           "cap" => "required|string|max:50",
           "multicap" => "required|string|max:50"
        ];
    }
}
