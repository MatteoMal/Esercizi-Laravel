<?php

use App\Http\Controllers\api\v1\AccediController;
use App\Http\Controllers\api\v1\CalcolaIVA;
use App\Http\Controllers\api\v1\CategoriaLibroController;
use App\Http\Controllers\Api\v1\ComuneItalianoController;
use App\Http\Controllers\Api\v1\ContattoController;
use App\Http\Controllers\api\v1\LibroController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  //  return $request->user();
//});

if (!defined('_VERS')) {
    define('_VERS', 'v1');
}

//API aperte

Route::get(_VERS . "/calcolaiva/{number}", [CalcolaIVA::class, "calcola"]);


Route::get(_VERS . "/accedi/{utente}/{hash?}", [AccediController::class, "show"]);
Route::get(_VERS . "/searchMail/{utente}", [AccediController::class, "searchMail"]);
Route::post(_VERS . "/registrazione/", [ContattoController::class, "registra"]);


Route::get(_VERS . '/libri', [LibroController::class, 'index']);
Route::get(_VERS . '/libri/{libro}', [LibroController::class, 'show']);


Route::get(_VERS . '/comuniItaliani', [ComuneItalianoController::class, 'index']);
Route::get(_VERS . '/comuniItaliani/{comuneItaliano}', [ComuneItalianoController::class, 'show']);


Route::get(_VERS . '/configurazioni', [ComuneItalianoController::class, 'index']);
Route::get(_VERS . '/configurazioni/{configurazione}', [ComuneItalianoController::class, 'show']);

//API con autenticazione UTENTE
Route::middleware(['autenticazione', 'contattoRuolo:Amministratore,Utente'])->group(function (){

  //CATEGORIELIBRI
  Route::get(_VERS . '/categorieLibri', [CategoriaLibroController::class, 'index']);
  Route::get(_VERS . '/categorieLibri/{categoriaLibro}', [CategoriaLibroController::class, 'show']);


  //CONTATTI
  Route::get(_VERS . "/contatti/{idContatto}", [ContattoController::class, "show"]);
  Route::put(_VERS . "/contatti/{idContatto}", [ContattoController::class, "update"]);
  Route::post(_VERS . "/contatti", [ContattoController::class, "store"]);
  Route::delete(_VERS . "/contatti/{contatto}", [ContattoController::class, "destroy"]);


  //LIBRI
  Route::put(_VERS . "/libri/{libro}", [LibroController::class, "update"]);
  Route::post(_VERS . "/libri", [LibroController::class, "store"]);
  
});


//API con autenticazione ADMIN
Route::middleware(['autenticazione', 'contattoRuolo:Amministratore'])->group(function (){

  //CATEGORIELIBRI
  Route::post(_VERS . '/categorieLibri', [CategoriaLibroController::class, 'store']);
  Route::put(_VERS . '/categorieLibri/{categoriaLibro}', [CategoriaLibroController::class, 'update']);
  Route::delete(_VERS . '/categorieLibri/{categoriaLibro}', [CategoriaLibroController::class, 'destroy']);


  //COMUNIITALIANI
  Route::post(_VERS . '/comuniItaliani', [ComuneItalianoController::class, 'store']);
  Route::put(_VERS . '/comuniItaliani/{comuneItaliano}', [ComuneItalianoController::class, 'update']);
  Route::delete(_VERS . '/comuniItaliani/{comuneItaliano}', [ComuneItalianoController::class, 'destroy']);


  //CONTATTI
  Route::get(_VERS . "/contatti", [ContattoController::class, "index"]);


  //LIBRI
  Route::delete(_VERS . "/libri/{libro}", [LibroController::class, "destroy"]);

});