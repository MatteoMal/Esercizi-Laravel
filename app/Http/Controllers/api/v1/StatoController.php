<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\StatoResource;
use App\Models\Stato;
use Illuminate\Http\Request;

class StatoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stato = Stato::all();
        if(request("idContatto") != null) {
            $stato = $stato->where("idContatto", request("idContatto"));
        }
        return $stato;
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
     * @param  \App\Models\Stato  $stato
     * @return \Illuminate\Http\Response
     */
    public function show(Stato $stato)
    {
        return new StatoResource($stato);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stato  $stato
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stato $stato)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stato  $stato
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stato $stato)
    {
        //
    }
}
