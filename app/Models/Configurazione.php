<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Configurazione extends Model
{
    use HasFactory;

    protected $table = 'configurazioni';
    protected $primaryKey = 'idConfigurazione';

    protected $fillable = [
        "chiave",
        "valore"
    ];

    public static function leggiValore($chiave){
      $select = DB::table('configurazioni')->select('configurazioni.valore')->where('configurazioni.chiave', '=', $chiave)->get();
      return $select;
    }
}
