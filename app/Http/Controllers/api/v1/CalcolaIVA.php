<?php
  namespace App\Http\Controllers\api\v1;

  use App\Http\Controllers\Controller;
  use Illuminate\Http\Request;

  class CalcolaIVA extends Controller {

    /**
     * Calcola l'IVA
     * 
     * @param $number numero passato
     * @return \Illuminate\Http\Response
     */
    public static function calcola($number){
        $iva = 22;
        $ris = $number / 100 * $iva;
        $arr = array("data" => $ris, "err" => null, "message" => null);
        return $arr;
    }


  }






?>