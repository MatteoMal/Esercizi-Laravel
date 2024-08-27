<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Gruppo;
use Illuminate\Foundation\Auth\User as ClassPerDate;

class Contatto extends ClassPerDate
{
    use HasFactory;

    protected $primaryKey = "idContatto";
    protected $table = "contatti";

    protected $with = ['recapiti', 'indirizzi', 'crediti'];
    
    protected $fillable = [
        'idGruppo',
        'idStato',
        'nome',
        'cognome',
        'sesso',
        'codiceFiscale',
        'partitaIva',
        'cittadinanza',
        'idNazioneNascita',
        'cittaNascita',
        'provinciaNascita',
        'dataNascita'
    ];

    //Aggiungi i ruoli per il contatto sulla tabella contatti_contattiRuoli

    public static function aggiungiContattoRuoli($idContatto, $idRuoli){
       $contatto = Contatto::where("idContatto", $idContatto)->firstOrFail();
       if (is_string($idRuoli)){
        $tmp = explode(',', $idRuoli);
       } else {
        $tmp = $idRuoli;
       }
       $contatto->ruoli()->attach($tmp);
       return $contatto->ruoli;
    }

    //Elimina i ruoli per il contatto sulla tabella contatti_contattiRuoli

    public static function eliminaContattoRuoli($idContatto, $idRuoli){
        $contatto = Contatto::where("idContatto", $idContatto)->firstOrFail();
       if (is_string($idRuoli)){
        $tmp = explode(',', $idRuoli);
       } else {
        $tmp = $idRuoli;
       }
       $contatto->ruoli()->detach($tmp);
       return $contatto->ruoli;
    }

    public static function sincronizzaContattoRuoli($idContatto, $idGruppo){
        $tmp = ContattoRuolo::create([
            "idContatto" => $idContatto,
            "idGruppo" => $idGruppo
          ]);
          return $tmp;
    }
}
