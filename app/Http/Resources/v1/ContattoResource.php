<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class ContattoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return $this->getCampi();
    }
    protected function getCampi() 
{
    return [
        "idContatto" => $this->idContatto,
        "idGruppo" => $this->idGruppo,
        "idStato" => $this->idStato,
        "nome" => $this->nome,
        "cognome" => $this->cognome,
        "sesso" => $this->sesso,
        "codiceFiscale" => $this->codiceFiscale,
        "partitaIva" => $this->partitaIva,
        "cittadinanza" => $this->cittadinanza,
        "idNazioneNascita" => $this->idNazioneNascita,
        "cittaNascita" => $this->cittaNascita,
        "provinciaNascita" => $this->provinciaNascita,
        "dataNascita" => $this->dataNascita
    ];
}
}
