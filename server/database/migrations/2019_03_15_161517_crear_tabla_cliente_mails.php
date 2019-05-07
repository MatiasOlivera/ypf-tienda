<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaClienteMails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cliente_mails', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cliente_id')->length(11);
            $table->string('mail');
            $table->integer('contacto_id')->length(11)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['cliente_id', 'mail']);

            $table->foreign('cliente_id')->references('id_cliente')->on('clientes');
            $table->foreign('contacto_id')->references('id')->on('con_cliente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cliente_mails');
    }
}
