<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\LibroStoreRequest;
use App\Http\Requests\v1\LibroUpdateRequest;
use App\Http\Resources\v1\LibroResource;
use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LibroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResource
     */
    public function index()
    {
        return Libro::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LibroStoreRequest $request)
    {
        if (Gate::allows('creare')) {
            if (Gate::allows('Amministratore') || $request->visualizzato){
            $data = $request->validated();
            $libro = Libro::create($data);
            return new LibroResource($libro);
         }
         else {
            abort(404, 'PE_0003');
        }}
          else {
             abort(403, 'PE_0006');
         }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Libro $libro)
    {
        return new LibroResource($libro);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LibroUpdateRequest $request, Libro $libro)
    {
        if (Gate::allows('aggiornare')){
            if (Gate::allows('Amministratore') || $libro->visualizzato){
            $data = $request->validated();
            $libro->fill($data);
            $libro->save();
            return new LibroResource($libro);
        } else {
            abort(404, 'PE_0003');
        } 
    } else {
            abort(403, 'PE_0004');
        };
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Libro $libro)
    {
        if (Gate::allows('eliminare')){
            $libro->deleteOrFail();
            return response()->noContent();
        } else {
            abort(403, 'PE_0005');
        }
    }
}
