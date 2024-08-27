<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accesso extends Model
{
    use HasFactory;

    protected $table = 'accessi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'idContatto',
        'ip',
        'autenticato'
    ];

    //Aggiungi tentativo fallito per l'idContatto

    public static function aggiungiAccesso($idContatto){
        Accesso::eliminaTentativi($idContatto);
        return Accesso::nuovoRecord($idContatto, 1);
    }

    //Elimina tentativo per utente loggato
    
    public static function eliminaTentativi($idContatto){
        $tmp = Accesso::where("idContatto", $idContatto)->where("autenticato", 0);
        $tmp->deleteOrFail();
    }

    //Aggiungi tentativo fallito per l'idContatto

    public static function aggiungiTentativoFallito($idContatto){
        return Accesso::nuovoRecord($idContatto, 0);
    }

    //Conta quanti tentativi per l'idContatto sono registrati

    public static function contaTentativi($idContatto){
       $tmp = Accesso::where("idContatto", $idContatto)->where("autenticato", 0)->count();
       return $tmp;
    }

    public static function nuovoRecord($idContatto, $autenticato){
       $tmp = Accesso::create([
         "idContatto" => $idContatto,
         "autenticato" => $autenticato,
         "ip" => request()->ip()
       ]);
       return $tmp;
    }
}
