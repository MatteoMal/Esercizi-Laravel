<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class LibroResource extends JsonResource
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

    public function getCampi(){
        return [
            'idLibro' => $this->idLibro,
            'titolo' => $this->titolo,
            'trama' => $this->trama
        ];
    }
}
