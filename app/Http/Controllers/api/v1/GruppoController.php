<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\GruppoResource;
use App\Models\Gruppo;
use Illuminate\Http\Request;

class GruppoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gruppo = Gruppo::all();
        if(request("idContatto") != null) {
            $gruppo = $gruppo->where("idContatto", request("idContatto"));
        }
        return $gruppo;
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
     * @param  \App\Models\Gruppo  $gruppo
     * @return \Illuminate\Http\Response
     */
    public function show(Gruppo $gruppo)
    {
        return new GruppoResource($gruppo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Gruppo  $gruppo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gruppo $gruppo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Gruppo  $gruppo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gruppo $gruppo)
    {
        //
    }
}
