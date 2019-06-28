<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaClienteRazon extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tabla = 'cliente_razon';

    /**
     * Run the migrations.
     * @table cliente_razon
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tabla, function (Blueprint $table) {
            $table->integer('id')->length(11)->autoIncrement();
            $table->integer('id_cliente')->length(11);
            $table->integer('id_razon')->length(11);
            $table->enum('estado', ['0', '1'])->default('1');

            // era la clave primaria pero ahora se esta usando id como clave primaria
            $table->unique(['id_cliente', 'id_razon']);
            $table->unique(["id"], 'id');

            $table->foreign('id_razon', 'cliente_razon_ibfk_1')
                ->references('id_razon')->on('razones_sociales')
                ->onDelete('no action')
                ->onUpdate('cascade');

            $table->foreign('id_cliente', 'cliente_razon_ibfk_2')
                ->references('id_cliente')->on('clientes')
                ->onDelete('no action')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tabla);
    }
}
