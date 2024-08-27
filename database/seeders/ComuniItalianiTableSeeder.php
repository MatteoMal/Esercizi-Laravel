<?php

namespace Database\Seeders;

use App\Models\ComuneItaliano;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComuniItalianiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = storage_path("app/csv_db/comuniItaliani.csv");$file = fopen($csv, "r");
      while (($data = fgetcsv($file, 200, ",")) !== false) {ComuneItaliano::create(["idComuneItaliano" => $data[0],"nome" => $data[1],"regione" => $data[2],"capoluogo" => $data[3],"provincia" => $data[4],"siglaAutomobilistica" => $data[5], "capInizio" => $data[6], "capFine" => $data[7], "cap" => $data[8], "multicap" => $data[9]]);}}
    }
