<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaPedidoEntrega extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tabla = 'pedido_entrega';

    /**
     * Run the migrations.
     * @table pedido_entrega
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tabla, function (Blueprint $table) {
            $table->integer('id_pedido')->length(11);
            $table->integer('id_entrega')->length(11)->default('1');
            $table->dateTime('fecha');

            $table->primary(['id_pedido', 'id_entrega']);

            $table->foreign('id_pedido', 'fk_pedido')
                ->references('id_pedido')->on('pedido')
                ->onDelete('cascade')
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
