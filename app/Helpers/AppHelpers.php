<?php


namespace App\Helpers;

use App\Models\Contatto;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AppHelpers{
    public static function aggiornaRegoleHelper($rules){


        //Versione 9.x di Laravel
        //$newRules = Arr::map($rules, function($value, $key){
       //     return str_replace("required|", "", $value);
        //});

         $newRules = array(); 
            foreach ($rules as $key => $value){
                $newRules[$key] = str_replace("required|", "", $value);
            }
         return $newRules;
    }

    public static function cifra($testo, $chiave){
      $testoCifrato = AesCtr::encrypt($testo, $chiave, 256);
      return base64_encode($testoCifrato);
    }

    //Estrai i nomi dei campi della tabella sul DB

    public static function colonneTabellaDB($tabella){
      $SQL = "SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema='" . DB::connection()->getDatabaseName() . "' AND table_name='" . $tabella . "';";
      $tmp = DB::select($SQL);
      return $tmp;
    }

    public static function creaPasswordCifrata($password, $sale, $sfida){
         $hashPasswordESale = AppHelpers::nascondiPassword($password, $sale);
         $hashFinale = AppHelpers::cifra($hashPasswordESale, $sfida);
         return $hashFinale;
    }

    public static function creaTokenSessione($idContatto, $secretJWT, $usaDa = null, $scade = null){
         $maxTime = 15 * 24 * 60 * 60; //Il token scade sempre dopo 15gg max
         $recordContatto = Contatto::where("idContatto", $idContatto)->first();
         $t = time();
         $nbf = ($usaDa == null) ? $t : $usaDa;
         $exp = ($scade == null) ? $nbf + $maxTime : $scade;
         $ruolo = $recordContatto->ruoli[0];
         $idRuolo = $ruolo->idGruppo;
         $abilita = $ruolo->abilita->toArray();
         $abilita = array_map(function ($arr) {
            return $arr["idAbilita"];
         }, $abilita);

         $arr = array(
          "iss" => "http://127.0.0.1:8000",
          "aud" => null,
          "iat" => $t,
          "nbf" => $nbf,
          "exp" => $exp,
          "data" => array(
            "idContatto" => $idContatto,
            "idStato" => $recordContatto->idStato,
            "idRuolo" => $idRuolo,
            "abilita" => $abilita,
            "nome" => trim($recordContatto->nome . " " . $recordContatto->cognome)
          )
          );
          $token = JWT::encode($arr, $secretJWT, 'HS256');
          return $token;
         
    }

    public static function decifra($testoCifrato, $chiave){
        $testoCifrato = base64_decode($testoCifrato);
        return AesCtr::decrypt($testoCifrato, $chiave, 256);
    }

    public static function isAdmin($idRuolo) {
      return ($idRuolo == 1) ? true : false;
    }

    public static function nascondiPassword($psw, $sale){
      return hash("sha512", $sale . $psw);
    }

    public static function rispostaCustom($dati, $msg = null, $err = null){
      $response = array();
      $response["data"] = $dati;
      if ($msg != null) $response["message"] = $msg;
      if ($err != null) $response["error"] = $err;
      return $response;
    }

    public static function validaToken($token, $secretJWT, $sessione) {
        $rit = null;
        $payload = JWT::decode($token, new Key($secretJWT, 'HS256'));
        echo ("VALIDA 1<br>");
        if ($payload->iat <= $sessione->inizioSessione) {
        if ($payload->data->idContatto == $sessione->idContatto) {
                 $rit = $payload;
                //echo ("VALIDA 2<br>");
}
        }
   return $rit;
   }

    }