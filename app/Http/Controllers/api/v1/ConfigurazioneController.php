<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\ConfigurazioneCollection;
use App\Http\Resources\v1\ConfigurazioneCompletoCollection;
use App\Http\Resources\v1\ConfigurazioneCompletoResource;
use App\Http\Resources\v1\ConfigurazioneResource;
use App\Models\Configurazione;
use Illuminate\Http\Request;

class ConfigurazioneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(request('tipo'));
        return Configurazione::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Configurazione  $configurazione
     * @return \Illuminate\Http\Response
     */
    public function show(Configurazione $configurazione)
    {
        // return new ConfigurazioneCompletoResource($configurazione);

        $risorsa = null;
        if (request("tipo") != null && request("tipo")=="completo"){
            $risorsa = new ConfigurazioneCompletoResource($configurazione);
        } else {
            $risorsa = new ConfigurazioneResource($configurazione);
        }
        return $risorsa;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Configurazione  $configurazione
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Configurazione $configurazione)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Configurazione  $configurazione
     * @return \Illuminate\Http\Response
     */
    public function destroy(Configurazione $configurazione)
    {
        //
    }
}
