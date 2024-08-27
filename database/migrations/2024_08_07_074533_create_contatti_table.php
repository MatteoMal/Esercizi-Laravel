<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contatti', function (Blueprint $table) {
            $table->id('idContatto');
            $table->integer('idGruppo');
            $table->integer('idStato');
            $table->string("nome", 45);
            $table->string("cognome", 45);
            $table->tinyInteger("sesso");
            $table->string("codiceFiscale", 45);
            $table->string("partitaIva", 45);
            $table->string("cittadinanza", 45);
            $table->tinyInteger("idNazioneNascita");
            $table->string("cittaNascita", 45);
            $table->string("provinciaNascita", 45);
            $table->date("dataNascita");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contatti');
    }
};
