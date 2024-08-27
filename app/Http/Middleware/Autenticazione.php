<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Api\v1\AccediController;
use App\Models\Contatto;
use App\Models\Gruppo;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Autenticazione
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        //$token = $_SERVER["HTTP_AUTORIZATION"];
        //$token = trim(str_replace("Bearer", "", $token));
        // Il codice sopra necessita di modifiche al server Apache
        //$token = $_SERVER["PHP_AUTH_PW"];
        //print_r($token);
        $token = $request->bearerToken();
        $payload = AccediController::verificaToken($token);
        if ($payload != null){
            $contatto = Contatto::where("idContatto", $payload->data->idContatto)->firstOrFail();
            if ($contatto->idStato == 1) {
                //print_r($contatto->gruppi->pluck("nome")->toArray());
                Auth::login($contatto);
                $request["contattiRuoli"] = $contatto->gruppi->pluck('nome')->toArray();
                return $next($request);
            } else {
                abort(403, 'TK_0002');
            }
        } else {
            abort(403, 'TK_0001');
        }
}
}