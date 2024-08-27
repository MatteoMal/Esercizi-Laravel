<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\CategoriaLibroStoreRequest;
use App\Http\Requests\v1\CategoriaLibroUpdateRequest;
use App\Http\Resources\v1\CategoriaLibroCollection;
use App\Http\Resources\v1\CategoriaLibroResource;
use App\Models\CategoriaLibro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoriaLibroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResource
     */
    public function index()
    {
        $categoriaLibro = null;
        if (Gate::allows('leggere')){
           if (Gate::allows('Amministratore')){
              $categoriaLibro = CategoriaLibro::all();
           } else {
              $categoriaLibro = CategoriaLibro::all()->where('visualizzato', 1);
           }
              return new CategoriaLibroCollection($categoriaLibro);
         } else {
            abort(403, 'PE_0001');
         }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoriaLibroStoreRequest $request)
    {
        if (Gate::allows('creare')) {
            $data = $request->validated();
            $categoriaLibro = CategoriaLibro::create($data);
            return new CategoriaLibroResource($categoriaLibro);
         } else {
             abort(403, 'PE_0006');
         }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CategoriaLibro $categoriaLibro)
    {
        if (Gate::allows('leggere')){
            if (Gate::allows('Amministratore') || $categoriaLibro->visualizzato){
                return new CategoriaLibroResource($categoriaLibro);
            } else {
                abort(404, 'PE_0003');
            }
        } else {
            abort(403, 'PE_0002');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoriaLibroUpdateRequest $request, CategoriaLibro $categoriaLibro)
    {
        if (Gate::allows('aggiornare')){
            $data = $request->validated();
            $categoriaLibro->fill($data);
            $categoriaLibro->save();
            return new CategoriaLibroResource($categoriaLibro);
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
    public function destroy(CategoriaLibro $categoriaLibro)
    {
        if (Gate::allows('eliminare')){
            $categoriaLibro->deleteOrFail();
            return response()->noContent();
        } else {
            abort(403, 'PE_0005');
        }
    }
}
