<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComuneItaliano extends Model
{
    use HasFactory;

    protected $table = 'comuni_italiani';
    protected $primaryKey = 'idComuneItaliano';

    protected $fillable = [
        "nome",
        "regione",
        "capoluogo",
        "provincia",
        "siglaAutomobilistica",
        "capInizio",
        "capFine",
        "cap",
        "multicap"
    ];

}
