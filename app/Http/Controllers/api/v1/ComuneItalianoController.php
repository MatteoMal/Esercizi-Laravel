<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\ComuneItalianoStoreRequest;
use App\Http\Requests\v1\ComuneItalianoUpdateRequest;
use App\Http\Resources\v1\ComuneItalianoCollection;
use App\Http\Resources\v1\ComuneItalianoCompletoCollection;
use App\Http\Resources\v1\ComuneItalianoResource;
use App\Models\ComuneItaliano;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ComuneItalianoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(request('tipo'));
        return ComuneItaliano::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ComuneItalianoStoreRequest $request)
    {
        if (Gate::allows('creare')) {
            $data = $request->validated();
            $comuneItaliano = ComuneItaliano::create($data);
            return new ComuneItalianoResource($comuneItaliano);
         } else {
             abort(403, 'PE_0006');
         }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ComuneItaliano  $comuneItaliano
     * @return \Illuminate\Http\Response
     */
    public function show(ComuneItaliano $comuneItaliano)
    {
        return new ComuneItalianoResource($comuneItaliano);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ComuneItaliano  $comuneItaliano
     * @return \Illuminate\Http\Response
     */
    public function update(ComuneItalianoUpdateRequest $request, ComuneItaliano $comuneItaliano)
    {
        if (Gate::allows('aggiornare')){
            $data = $request->validated();
            $comuneItaliano->fill($data);
            $comuneItaliano->save();
            return new ComuneItalianoResource($comuneItaliano);
        } else {
            abort(403, 'PE_0004');
        };
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ComuneItaliano  $comuneItaliano
     * @return \Illuminate\Http\Response
     */
    public function destroy(ComuneItaliano $comuneItaliano)
    {
        if (Gate::allows('eliminare')){
            $comuneItaliano->deleteOrFail();
            return response()->noContent();
        } else {
            abort(403, 'PE_0005');
        }
    }
}
