<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Auth extends Model
{
    use HasFactory;

    protected $table = 'auth';
    protected $primaryKey = 'idAuth';

    protected $fillable = [
        "idContatto",
        "user",
        "sfida",
        "secretJWT",
        "inizioSfida",
        "obbligoCambio"
    ];

    public static function esisteUtenteValidoPerLogin($user) {
        $tmp = DB::table('contatti')->join('auth', 'contatti.idContatto', '=', 'auth.idContatto')->where('contatti.idContatto', '=', 1)->where('auth.user', '=', $user)->select('auth.idContatto')->get()->count();
        return ($tmp > 0) ? true : false;
    }

    public static function esisteUtente($user) {
        $tmp = DB::table('auth')->where('auth.user', '=', $user)->select('auth.idContatto')->get()->count();
        return ($tmp > 0) ? true : false;
    }
}
