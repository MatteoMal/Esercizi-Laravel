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
        Schema::create('comuni_italiani', function (Blueprint $table) {
            $table->id('idComuneItaliano');
            $table->string('nome', 45);
            $table->string('regione', 45);
            $table->string('capoluogo');
            $table->string('provincia', 45);
            $table->char('siglaAutomobilistica', 2);
            $table->string('capInizio');
            $table->string('capFine');
            $table->string('cap');
            $table->char('multicap', 2);
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
        Schema::dropIfExists('comuni_italiani');
    }
};
