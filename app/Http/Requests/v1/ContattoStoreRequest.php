<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;

class ContattoStoreRequest extends FormRequest
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
            "idContatto" => "required|integer",
           "idGruppo" => "required|integer",
           "idStato" => "required|integer",
           "nome" => "required|string|max:45",
           "cognome" => "required|string|max:45",
           "sesso"  =>"required|integer",
           "codiceFiscale" => "required|string|max:45",
           "partitaIva" => "required|string|max:45",
           "cittadinanza" => "required|string|max:45",
           "idNazioneNascita" => "required|integer",
           "cittaNascita" => "required|string|max:45",
           "provinciaNascita" => "required|string|max:45",
           "dataNascita" => "required|date"
        ];
    }
}
