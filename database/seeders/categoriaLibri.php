<?php

namespace Database\Seeders;

use App\Models\CategoriaLibro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class categoriaLibri extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CategoriaLibro::create(['idCategoriaLibro' => 1, 'nome' => 'fantascienza']);
        CategoriaLibro::create(['idCategoriaLibro' => 2, 'nome' => 'fantasia']);
        CategoriaLibro::create(['idCategoriaLibro' => 3, 'nome' => 'avventura']);
    }
}
