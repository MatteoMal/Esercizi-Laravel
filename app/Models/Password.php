<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Password extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'passwords';
    protected $primaryKey = 'idPassword';
    protected $fillable = [
        'idContatto',
        'psw',
        'sale'
    ];

    //Ritorna il record della password attualmente usata

    public static function passwordAttuale($idContatto){
        $record = Password::where("idContatto", $idContatto)->orderBy("idPassword", "desc")->firstOrFail();
        return $record;
    }
}
