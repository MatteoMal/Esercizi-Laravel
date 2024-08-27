<?php

namespace Tests\Feature\Http\Controllers\Api\v1;

use App\Helpers\AppHelpers;
use App\Models\Auth;
use App\Models\Configurazione;
use App\Models\Contatto;
use App\Models\ContattoAbilita;
use App\Models\Gruppo;
use App\Models\Libro;
use App\Models\Sessione;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Session\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;

class LibriControllerTest extends TestCase {

    use RefreshDatabase;


    protected function impostaAmbiente(){
        $this->impostaConfigurazioni();
        $n = Configurazione::all()->count();
        $this->assertEquals($n, 4);

        $this->impostaDBAbilita();
        $n = ContattoAbilita::all()->count();
        $this->assertEquals($n, 4);

        $this->impostaDBRuolo();
        $n = Gruppo::all()->count();
        $this->assertEquals($n, 3);

        $this->impostaDBRuoloAbilita();
        $this->impostaGate();
    }

    protected function impostaConfigurazioni(){
        Configurazione::create(['idConfigurazione' => 1, 'chiave' => 'maxLoginErrati', 'valore' => '5']);
        Configurazione::create(['idConfigurazione' => 2, 'chiave' => 'durataSfida', 'valore' => '30']);
        Configurazione::create(['idConfigurazione' => 3, 'chiave' => 'durataSessione', 'valore' => '300']);
        Configurazione::create(['idConfigurazione' => 4, 'chiave' => 'storicoPsw', 'valore' => '3']);
    }

    protected function impostaContatto(){
        $utente = hash("sha512", trim("Utente"));
        $sfida = hash("sha512", trim("Sfida"));
        $secret = trim(Str::random(20));

        $contatto = Contatto::factory()->create();
        $contatto->idStato = 1;
        $contatto->archiviato = 0;
        $contatto->save();

        $auth = new Auth();
        $auth->idContatto = $contatto->idContatto;
        $auth->secretJWT = $secret;
        $auth->user = $utente;
        $auth->sfida = $sfida;
        $auth->inizioSfida = time();
        $auth->save();
        return $contatto;
    }

    protected function impostaDBAbilita(){
        $arr = ["Leggere", "Creare", "Aggiornare", "Eliminare"];
        foreach ($arr as $item){
            ContattoAbilita::create([
                'nome' => $item,
                'sku' => strtolower($item)
            ]);
        }
    }

    protected function impostaDBRuolo(){
        $arr = ["Amministratore", "Utente", "Ospite"];
        foreach ($arr as $item){
            Gruppo::create([
                'nome' => $item,
                'deleted_at' => null
            ]);
        }
    }

    protected function impostaDBRuoloAbilita(){
        $idRuolo = 1;
        $arrAbilita = [1, 2, 3, 4];
        Gruppo::aggiungiRuoloAbilita($idRuolo, $arrAbilita);
        $idRuolo = 2;
        $arrAbilita = [1];
        Gruppo::aggiungiRuoloAbilita($idRuolo, $arrAbilita);
    }

    protected function impostaGate(){
        Gruppo::all()->each(function (Gruppo $gruppo) {
            Gate::define($gruppo->nome, function (Contatto $contatto) use ($gruppo) {
              // echo ($gruppo . "-");
              return $contatto->gruppi->contains('nome', $gruppo->nome);
            });
            }
        );
        // Gate basati su multipli ruoli
        ContattoAbilita::all()->each(function (ContattoAbilita $abilita) {
            // echo ($abilita . "-");
            Gate::define($abilita->sku, function (Contatto $contatto) use ($abilita) {
                $check = false;
                foreach ($contatto->gruppi as $item) {
                    if ($item->abilita->contains('sku', $abilita->sku)) {
                        $check = true;
                        break;
                    }
                }
                return $check;
            });
        });
        
    }

    protected function impostaToken($contatto){
        $sessione = Sessione::factory()->create()->first();
        $sessione->idContatto = $contatto->idContatto;
        $auth = Auth::where("idContatto", $contatto->idContatto)->first();
        $token = AppHelpers::creaTokenSessione($contatto->idContatto, $auth->secretJWT);
        $sessione->token = $token;
        $sessione->save();
        $sessione = Sessione::where("idContatto", $contatto->idContatto)->first();
        $this->assertEquals($token, $sessione->token);
        return $token;
    }

    protected static function pulisciArray($arrKey){
        $key = array_search("libri", $arrKey);
        if ($key !== false) array_splice($arrKey, $key, 1);
        $key = array_search("deleted_at", $arrKey);
        if ($key !== false) array_splice($arrKey, $key, 1);
        $key = array_search("created_at", $arrKey);
        if ($key !== false) array_splice($arrKey, $key, 1);
        $key = array_search("updated_at", $arrKey);
        if ($key !== false) array_splice($arrKey, $key, 1);
        return $arrKey;
    }

    protected function ritornaStrutturaJsonMultiplaLibro($admin = 0){
        return ['*' => $this->ritornaStrutturaJsonSingolaLibro($admin)];
    }

    protected function ritornaStrutturaJsonSingolaLibro($admin = 0){
       if ($admin == 1){
         $arr = ['idLibro', 'titolo', 'trama', 'created_at', 'updated_at', 'deleted_at'];
       } else {
         $arr = ['idLibro', 'titolo', 'trama'];
       }
       return $arr;
    }

    protected const RISORSA = 'libri';

    protected function ritornaUrlLibro($id = null){
        $url = '/api/v1/' . self::RISORSA;
        if ($id != null){
          $url = $url . '/' . $id;
        }
        return $url;
      }

    /*
    * @test
    */

    public function test_tutti_libri(){
        $this->impostaAmbiente();

        $contatto = $this->impostaContatto();
        $token = $this->impostaToken($contatto);
        // $sessione = Sessione::where("idContatto", $contatto->idContatto)->first();
        // $this->assertEquals($token, $sessione->token);

        $libriModel = Libro::factory()->count(rand(1,4))->create();
        //TESTO COME ADMIN
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 1);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', $this->ritornaUrlLibro());

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => $this->ritornaStrutturaJsonMultiplaLibro(1)]);
        $response->assertJson(['data' => $libriModel->toArray()]);

        //TESTO COME UTENTE
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 2);

        $tmpModel = $libriModel->map(
            function ($model) {
                $arr = $this->ritornaStrutturaJsonSingolaLibro(0);
                $dati = $model->only($arr);
                $tmp = array();
                foreach ($arr as $item) {
                    if ($item == 'libri') {
                        $tmp[$item] = array();
                    } else {
                        $tmp[$item] = $dati[$item];
                    }
                }
                return $tmp;
            }
        );
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', $this->ritornaUrlLibro());

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => $this->ritornaStrutturaJsonMultiplaLibro(0)]);

        $response->assertJson(['data' => $tmpModel->toArray()]);

        //TESTO COME OSPITE
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 3);

        $response = $this->json('GET', $this->ritornaUrlLibro());

        $response->assertStatus(403);
    }

    /*
    * @test
    */

    public function test_tutti_libri_vuoto(){
        $this->impostaAmbiente();
        $contatto = $this->impostaContatto();
        $token = $this->impostaToken($contatto);

        //TESTO COME ADMIN
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 1);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', $this->ritornaUrlLibro());

        $response->assertStatus(200);
        $response->assertJson(['data' => []]);

        //TESTO COME UTENTE
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 2);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', $this->ritornaUrlLibro());

        $response->assertStatus(200);
        $response->assertJson(['data' => []]);

        //TESTO COME OSPITE
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 3);

        $response = $this->json('GET', $this->ritornaUrlLibro());

        $response->assertStatus(403);
    }

    /*
    * @test
    */

    public function test_creo_libri(){
        $this->impostaAmbiente();
        $contatto = $this->impostaContatto();
        $token = $this->impostaToken($contatto);

        //TESTO COME ADMIN
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 1);
        $arrKey = $this->ritornaStrutturaJsonSingolaLibro(1);
        $arrKey = LibriControllerTest::pulisciArray($arrKey);
        $requestData = Libro::factory()->make()->only($arrKey);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', $this->ritornaUrlLibro(), $requestData);

        $response->assertStatus(201);

        $id = $response['data']['idLibro'];
        $requestData['idLibro'] = $id;

        $response->assertJsonStructure(['data' => $arrKey]);
        $response->assertJson(['data' => $requestData]);

        //TESTO COME UTENTE
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 2);

        $arrKey = $this->ritornaStrutturaJsonSingolaLibro(0);
        $arrKey = LibriControllerTest::pulisciArray($arrKey);
        $requestData = Libro::factory()->make()->only($arrKey);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', $this->ritornaUrlLibro(), $requestData);

        $response->assertStatus(403);

        //TESTO COME OSPITE
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 3);

        $response = $this->json('POST', $this->ritornaUrlLibro(), $requestData);

        $response->assertStatus(403);
    }

    /*
    * @test
    */

    public function test_leggo_singolo_libro(){
        $this->impostaAmbiente();
        $contatto = $this->impostaContatto();
        $token = $this->impostaToken($contatto);

        //TESTO COME ADMIN
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 1);
        $arrKey = $this->ritornaStrutturaJsonSingolaLibro(1);
        $arrKey = LibriControllerTest::pulisciArray($arrKey);
        $libriModel = Libro::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', $this->ritornaUrlLibro($libriModel->idLibro));

        $response->assertStatus(200);

        $arrKey = $this->ritornaStrutturaJsonSingolaLibro(1);
        $response->assertJsonStructure(['data' => $arrKey]);
        $response->assertJson(['data' => $libriModel->toArray()]);

        //TESTO COME UTENTE
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 2);
        $arrKey = $this->ritornaStrutturaJsonSingolaLibro(0);
        $arrKey = LibriControllerTest::pulisciArray($arrKey);
        $libriModel = Libro::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', $this->ritornaUrlLibro($libriModel->idLibro));

        $response->assertStatus(200);

        $libriModel = $libriModel->only($arrKey);
        $libriModel['libri'] = array();

        $response->assertJsonStructure(['data' => $arrKey]);
        $response->assertJson(['data' => $libriModel]);

        //TESTO COME OSPITE
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 3);
        $arrKey = $this->ritornaStrutturaJsonSingolaLibro(0);
        $arrKey = LibriControllerTest::pulisciArray($arrKey);
        $libriModel = Libro::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', $this->ritornaUrlLibro($libriModel->idLibro));

        $response->assertStatus(403);
    }

    /*
    * @test
    */

    public function test_leggo_singolo_libro_vuoto(){
        $this->impostaAmbiente();
        $contatto = $this->impostaContatto();
        $token = $this->impostaToken($contatto);

        //TESTO COME ADMIN
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 1);
        $id = rand(1,10);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', $this->ritornaUrlLibro($id));

        $response->assertStatus(404);

        //TESTO COME UTENTE
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 2);
        $id = rand(1,10);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', $this->ritornaUrlLibro($id));

        $response->assertStatus(404);

        //TESTO COME OSPITE
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 3);
        $id = rand(1,10);

        $response = $this->json('GET', $this->ritornaUrlLibro($id));

        $response->assertStatus(403);
    }

    /*
    * @test
    */

    public function test_modifico_singolo_libro(){
        $this->impostaAmbiente();
        $contatto = $this->impostaContatto();
        $token = $this->impostaToken($contatto);

        //TESTO COME ADMIN
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 1);
        $arrKey = $this->ritornaStrutturaJsonSingolaLibro(1);
        $arrKey = LibriControllerTest::pulisciArray($arrKey);
        $libriModel = Libro::factory()->create();
        $requestData = Libro::factory()->make()->only('titolo');

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', $this->ritornaUrlLibro($libriModel->idLibro), $requestData);

        $response->assertStatus(200);
        //controllo che il nuovo valore sia stato aggiornato
        $ritorno = array("titolo" => $requestData["titolo"]);
        $response->assertJson(['data' => $ritorno]);
        //verifico che il nuovo valore sia a DB
        $libriModel->refresh();
        $ritorno = array('titolo' => $libriModel['titolo']);
        $response->assertJson(['data' => $ritorno]);

        //TESTO COME UTENTE
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 2);
        $arrKey = $this->ritornaStrutturaJsonSingolaLibro(0);
        $arrKey = LibriControllerTest::pulisciArray($arrKey);
        $libriModel = Libro::factory()->create();
        $requestData = Libro::factory()->make()->only('titolo');

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', $this->ritornaUrlLibro($libriModel->idLibro), $requestData);

        $response->assertStatus(403);

        //TESTO COME OSPITE
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 3);
        $arrKey = $this->ritornaStrutturaJsonSingolaLibro(0);
        $arrKey = LibriControllerTest::pulisciArray($arrKey);
        $libriModel = Libro::factory()->create();
        $requestData = Libro::factory()->make()->only('titolo');

        $response = $this->json('PUT', $this->ritornaUrlLibro($libriModel->idLibro), $requestData);

        $response->assertStatus(403);
    }

    /*
    * @test
    */

    public function test_modifico_singolo_libro_vuoto(){
        $this->impostaAmbiente();
        $contatto = $this->impostaContatto();
        $token = $this->impostaToken($contatto);

        //TESTO COME ADMIN
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 1);
        $id = rand(1,10);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', $this->ritornaUrlLibro($id));

        $response->assertStatus(403);

        //TESTO COME UTENTE
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 2);
        $id = rand(1,10);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', $this->ritornaUrlLibro($id));

        $response->assertStatus(403);

        //TESTO COME OSPITE
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 3);
        $id = rand(1,10);

        $response = $this->json('PUT', $this->ritornaUrlLibro($id));

        $response->assertStatus(403);
    }

    /*
    * @test
    */

    public function test_cancello_singolo_libro(){
        $this->impostaAmbiente();
        $contatto = $this->impostaContatto();
        $token = $this->impostaToken($contatto);

        //TESTO COME ADMIN
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 1);
        $libriModel = Libro::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('DELETE', $this->ritornaUrlLibro(), $libriModel->idLibro);

        $response->assertStatus(204);

        //TESTO COME UTENTE
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 2);
        $libriModel = Libro::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('DELETE', $this->ritornaUrlLibro(), $libriModel->idLibro);

        $response->assertStatus(403);

        //TESTO COME OSPITE
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 3);
        $libriModel = Libro::factory()->create();

        $response = $this->json('DELETE', $this->ritornaUrlLibro($libriModel->idLibro));

        $response->assertStatus(403);
    }

    /*
    * @test
    */

    public function test_cancello_singolo_libro_vuoto(){
        $this->impostaAmbiente();
        $contatto = $this->impostaContatto();
        $token = $this->impostaToken($contatto);

        //TESTO COME ADMIN
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 1);
        $id = rand(1,10);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('DELETE', $this->ritornaUrlLibro($id));

        $response->assertStatus(404);

        //TESTO COME UTENTE
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 2);
        $id = rand(1,10);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('DELETE', $this->ritornaUrlLibro($id));

        $response->assertStatus(403);

        //TESTO COME OSPITE
        $ruoli = Contatto::sincronizzaContattoRuoli($contatto->idContatto, 3);
        $id = rand(1,10);

        $response = $this->json('DELETE', $this->ritornaUrlLibro($id));

        $response->assertStatus(403);
    }
}




?>