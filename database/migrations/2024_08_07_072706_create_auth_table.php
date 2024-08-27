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
        Schema::create('auth', function (Blueprint $table) {
            $table->id('idAuth');
            $table->integer('idContatto');
            $table->string('user', 255);
            $table->string('sfida', 255);
            $table->string('secretJWT', 255);
            $table->integer('inizioSfida');
            $table->tinyInteger('obbligoCambio', 3);
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
        Schema::dropIfExists('auth');
    }
};
