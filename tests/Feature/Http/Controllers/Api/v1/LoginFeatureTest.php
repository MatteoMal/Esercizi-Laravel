<?php
  
namespace Tests\Feature\Http\Controllers\Api\v1;

use App\Http\Controllers\api\v1\AccediController;
use Tests\TestCase;

class LoginFeatureTest extends TestCase {

    
    /*
    * @test
    */
    public function test_login(){
        //Richiamo la funzione per il login inserendo un utente random
        $login = AccediController::show(str()->random());
        //Richiedo la risorsa appena creata all'endpoint
        $response = $this->json('GET', $this->$login);
        //Controllo che mi dia una risposta 200 OK
        $response->assertStatus(200);
        //Controllo che la risposta sia corretta
        $response->assertJson(['data' => $response]);
    }

}





?>