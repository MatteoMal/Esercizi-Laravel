<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gruppo extends Model
{
    use HasFactory;

    protected $table = 'gruppi';
    protected $primaryKey = 'idGruppo';

    protected $fillable = [
        "nome"
    ];

    public function abilita(){
        return $this->belongsToMany(ContattoAbilita::class, 'contattiruoli_contattiabilita', 'idContattoRuolo', 'idContattoAbilita');
    }

    public static function aggiungiRuoloAbilita($idRuolo, $idAbilita){
       $gruppo = Gruppo::where('idGruppo', $idRuolo)->firstOrFail();
       if (is_string($idAbilita)){
        $tmp = explode(',' , $idAbilita);
       } else {
        $tmp = $idAbilita;
       }
       $gruppo->abilita()->attach($tmp);
       return $gruppo->abilita;
    }
}
