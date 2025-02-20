<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Sessione;
use Illuminate\Http\Request;

class SessioneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sessione = Sessione::all();
        if(request("idContatto") != null) {
            $sessione = $sessione->where("idContatto", request("idContatto"));
        }
        return $sessione;
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
     * @param  \App\Models\Sessione  $sessione
     * @return \Illuminate\Http\Response
     */
    public function show(Sessione $sessione)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sessione  $sessione
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sessione $sessione)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sessione  $sessione
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sessione $sessione)
    {
        //
    }
}
