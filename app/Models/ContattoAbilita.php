<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContattoAbilita extends Model
{
    use HasFactory;

    protected $table = 'contattiabilita';
    protected $primaryKey = 'idContattoAbilita';

    protected $fillable = [
        'nome',
        'sku'
    ];
}
