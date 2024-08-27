<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Libro extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'libri';
    protected $primaryKey = 'idLibro';

    protected $fillable = [
        'titolo',
        'trama'
    ];
}
