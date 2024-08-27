<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class ComuneItalianoResource extends JsonResource
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
        "idComuneItaliano" => $this->idComuneItaliano,
        "nome" => $this->nome,
        "regione" => $this->regione,
        "capoluogo" => $this->capoluogo,
        "provincia" => $this->provincia,
        "siglaAutomobilistica" => $this->siglaAutomobilistica,
        "capInizio" => $this->capInizio,
        "capFine" => $this->capFine,
        "cap" => $this->cap,
        "multicap" => $this->multicap
    ];
}
}
