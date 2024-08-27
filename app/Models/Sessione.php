<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Sessione extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sessioni';
    protected $primaryKey = 'idSessione';

    protected $fillable = [
        "idContatto",
        "token",
        "inizioSessione"
    ];

    //Aggiorna la sessione per il contatto e il token passato

    public static function aggiornaSessione($idContatto, $tk){
       $where = ["idContatto" => $idContatto, "token" => $tk];
       $arr = ["inizioSessione" => time()];
       DB::table("sessioni")->updateOrInsert($where, $arr);
    }

    //Elimina la sessione per il contatto passato

    public static function eliminaSessione($idContatto){
       DB::table("sessioni")->where("idContatto", $idContatto)->delete();
    }

    public static function datiSessione($token){
        if (Sessione::esisteSessione($token)) {
            // return DB::table("sessioni")->where("token", $token)->first();
            return Sessione::where("token", $token)->get()->first();
        } else {
            return null;
        }
    }

    //Controlla se esiste la sessione col token passato

    public static function esisteSessione($token) {
        return DB::table("sessioni")->where("token", $token)->exists();
    }
}
