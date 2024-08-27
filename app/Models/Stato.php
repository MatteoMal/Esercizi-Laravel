<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stato extends Model
{
    use HasFactory;

    protected $table = 'stati';
    protected $primaryKey = 'idStato';

    protected $fillable = [
        "nome"
    ];
}
