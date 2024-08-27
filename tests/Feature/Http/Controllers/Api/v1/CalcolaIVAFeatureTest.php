<?php
  
namespace Tests\Feature\Http\Controllers\Api\v1;

use App\Http\Controllers\api\v1\CalcolaIVA;
use Tests\TestCase;

class CalcolaIVAFeatureTest extends TestCase {

    
    /*
    * @test
    */
    public function test_iva(){
        //Richiamo la funzione per il calcolo dell'IVA inserendo un numero random
        $calcolo = CalcolaIVA::calcola(rand(1,200));
        //Richiedo la risorsa appena creata all'endpoint
        $response = $this->json('GET', $this->$calcolo);
        //Controllo che mi dia una risposta 200 OK
        $response->assertStatus(200);
        //Controllo che la risposta sia corretta
        $response->assertJson(['data' => $response]);
    }

}





?>