<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaEstadoPedido extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tabla = 'estado_pedido';

    /**
     * Run the migrations.
     * @table estado_pedido
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tabla, function (Blueprint $table) {
            $table->integer('id_estado')->length(11)->autoIncrement();
            $table->string('desc_estado', 70);
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
