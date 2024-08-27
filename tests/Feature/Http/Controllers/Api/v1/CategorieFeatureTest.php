<?php

namespace Tests\Feature\Http\Controllers\Api\v1;

use App\Models\CategoriaLibro;
use App\Models\TipologiaIndirizzo;
use App\Models\TipologiaRecapito;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategorieFeatureTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /*
        * Struttura della risorsa analizzabile
        *
        * @const
        */
        protected const STRUTTURA = ['idCategoriaLibro', 'nome'];

        /*
        * Risorsa per la creazione dell'endpoint
        *
        * @const
        */
        protected const RISORSA = 'categorieLibri';

        /** @test */ 
    public function lista_tutte_categorie()
    {
        //creare i dati del model attraverso la factory
        $tipo = CategoriaLibro::factory()->count(rand(1,5))->create()->map(
            function ($model){
              $arr = $this->ritornaStrutturaJsonSingola();
              $dati = $model->only($arr);
              $arrTmp = array();
              foreach ($arr as $item){
                $arrTmp[$item] = $dati[$item];
              }
              return $arrTmp;
        })->toArray();

        //interrogare l'endpoint
        $response = $this->json('GET', $this->ritornaUrl());
        //controllare che ci dia una risposta 200 OK
        $response->assertStatus(200);
        //verificare che la struttura della risposta sia corretta
        $response->assertJsonStructure(['data'=>$this->ritornaStrutturaJsonMultipla()]);
        //verificare che la risposta sia anche corretta
        $response->assertJson(['data'=>$tipo]);
    }

    /*
    * @test
     */
    public function lista_tutte_categorie_vuoto(){
        //Richiedo la risorsa senza aver inserito nulla
        $response = $this->json('GET',$this->ritornaUrl());
        //controllo che mi dia una risposta 200 OK
        $response->assertStatus(200);
        //verifico che la risposta sia corretta e quindi vuota
        $response->assertJson(['data'=>[]]);
    }

    /*
    * @test
     */

     public function crea_nuova_categoria(){
        //Creo i dati da inserire nel database a partire dalla factory
        //Il make() crea ma non inserisce in DB
        $requestDati = CategoriaLibro::factory()->make()->only(['nome']);
        //Invio la richiesta di inserimento dati
        $response = $this->json('POST', $this->ritornaUrl(), $requestDati[]);
        //Mi aspetto una risposta di tipo 201 risorsa creata
        $response->assertStatus(201);
        //Verifico che la struttura della risposta sia corretta
        $response->assertJsonStructure(['data' => $this->ritornaStrutturaJsonSingola()]);
        //Verifico che la risposta sia anche corretta
        $response->assertJson(['data' => $requestDati]);
     }

     /*
    * @test
     */

     public function leggi_singola_categoria(){
       //Creo ed inserisco la risorsa nel DB
       $tipo = CategoriaLibro::factory()->create();
       //Richiedo la risorsa appena creata all'endpoint
       $response = $this->json('GET', $this->ritornaUrl($tipo->idCategoriaLibro));
       //Controllo che mi dia una risposta 200 OK
       $response->assertStatus(200);
       //Verifico che la struttura della risposta sia corretta
       $response->assertJsonStructure(['data' => $this->ritornaStrutturaJsonSingola()]);
       //Pulisco la risposta del model con i dati necessari al confronto
       $risposta = $tipo->only($this->ritornaStrutturaJsonSingola());
       //Verifico che la risposta sia anche corretta
       $response->assertJson(['data' => $risposta]);
     }

     /*
    * @test
     */

     public function leggi_singola_categoria_vuota(){
       //Creo un idTipologiaIndirizzo casuale
       $id = rand(1,100);
       //Richiedo la risorsa appena creata all'endpoint 
       $response = $this->json('GET', $this->ritornaUrl($id));
       //Controllo che mi dia una risposta 404 File Not Found
       $response->assertStatus(404);
     }

     /*
    * @test
     */

     public function update_categoria(){
        //Creo ed inserisco la risorsa nel DB
        $tipo = CategoriaLibro::factory()->create();
        //Creo dei dati da aggiornare
        $requestDati = CategoriaLibro::factory()->make()->only(['nome']);
        //Invio la risorsa appena creata all'endpoint per fare l'upload
        $response = $this->json('PUT', $this->ritornaUrl($tipo->idCategoriaLibro), $requestDati[]);
        //Controllo che mi dia una risposta 200 OK
        $response->assertStatus(200);
        //Estraggo i dati dalla response
        $response = array('nome' => $response->json()["data"]["nome"]);
        //Verifico che la risposta sia anche corretta
        $this->assertEquals($requestDati, $response);
     }

     /*
    * @test
     */

     public function update_categoria_vuota(){
       //Creo un idTipologiaIndirizzo casuale
       $id = rand(1,10);
       //Creo dei dati da aggiornare
       $requestDati = CategoriaLibro::factory()->make()->only(['nome']);
       //Invio la risorsa appena creata all'endpoint per fare l'upload
       $response = $this->json('PUT', $this->ritornaUrl($id), $requestDati[]);
       //Controllo che mi dia una risposta 404 File Not Found
       $response->assertStatus(404);
       //Controllo che il ritorno sia nullo
       $this->assertNull(CategoriaLibro::find($id));
     }

     /*
    * @test
     */

     public function delete_categoria(){
       //Creo ed inserisco la risorsa nel DB
       $tipo = CategoriaLibro::factory()->create();
       //Invio la risorsa all'endpoint per fare la DELETE
       $response = $this->json('DELETE', $this->ritornaUrl($tipo->idCategoriaLibro));
       //Mi aspetto uno status 204 No Content
       $response->assertStatus(204);
       //Controllo che non esista più la risorsa a DB
       $this->assertNull(CategoriaLibro::find($tipo->idCategoriaLibro));
     }

     /*
     * @test
     */

     public function delete_categoria_vuota(){
      //Creo un idTipologiaIndirizzo casuale
      $id = rand(1,10);
      //Invio la risorsa all'endpoint per fare la DELETE
      $response = $this->json('DELETE', $this->ritornaUrl($id));
      //Controllo che mi dia una risposta 404 File Not Found
      $response->assertStatus(404);
    }


    //PROTECTED

    protected function ritornaStrutturaJsonMultipla(){
        return ['*'=>$this->ritornaStrutturaJsonSingola()];
    } 

    protected function ritornaStrutturaJsonSingola(){
        return self::STRUTTURA;
    } 

    protected function ritornaUrl($id = null){
      $url = '/api/v1/' . self::RISORSA;
      if ($id != null){
        $url = $url . '/' . $id;
      }
      return $url;
    }
}
