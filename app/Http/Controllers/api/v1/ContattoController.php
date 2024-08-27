<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\AuthStoreRequest;
use App\Http\Requests\v1\ContattoStoreRequest;
use App\Http\Requests\v1\ContattoUpdateRequest;
use App\Http\Requests\v1\PasswordStoreRequest;
use App\Http\Resources\v1\AuthResource;
use App\Http\Resources\v1\ContattoCollection;
use App\Http\Resources\v1\ContattoCompletoCollection;
use App\Http\Resources\v1\ContattoResource;
use App\Models\Auth;
use App\Models\Contatto;
use App\Models\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ContattoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contatto = null;
        if (Gate::allows('leggere')) {
           $contatto = Contatto::all();
           return new ContattoCollection($contatto);
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
    public function store(ContattoStoreRequest $request)
    {
        if (Gate::allows('creare')) {
            if (Gate::allows('Amministratore') || $request->visualizzato){
            $data = $request->validated();
            $contatto = Contatto::create($data);
            return new ContattoResource($contatto);
         }
         else {
            abort(404, 'PE_0003');
        }}
          else {
             abort(403, 'PE_0006');
         }
    }

    public function registra(ContattoStoreRequest $request, AuthStoreRequest $request2, PasswordStoreRequest $request3){
        //TABELLA CONTATTI
        $data = $request->validated();
        $contatto = Contatto::create($data);

        //TABELLA AUTH
        $data2 = $request2->validated();
        $auth = Auth::create($data2);

        //TABELLA PASSWORDS
        $data3 = $request3->validated();
        $password = Password::create($data3);
        return new ContattoResource($contatto, $auth, $password);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Contatto $contatto)
    {
        if (Gate::allows('leggere')){
            if (Gate::allows('Amministratore') || $contatto->visualizzato){
                return new ContattoResource($contatto);
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
    public function update(ContattoUpdateRequest $request, Contatto $contatto)
    {
        if (Gate::allows('aggiornare')){
            if (Gate::allows('Amministratore') || $contatto->visualizzato){
            $data = $request->validated();
            $contatto->fill($data);
            $contatto->save();
            return new ContattoResource($contatto);
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
    public function destroy(Contatto $contatto)
    {
        if (Gate::allows('eliminare')){
            if (Gate::allows('Amministratore') || $contatto->visualizzato){
            $contatto->deleteOrFail();
            return response()->noContent();
        } else {
            abort(404, 'PE_0003');
        } } else {
            abort(403, 'PE_0005');
        }
    }
}
