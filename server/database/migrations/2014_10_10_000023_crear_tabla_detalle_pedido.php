<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaDetallePedido extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tabla = 'detalle_pedido';

    /**
     * Run the migrations.
     * @table detalle_pedido
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tabla, function (Blueprint $table) {
            $table->integer('id_det_pedido')->length(11)->autoIncrement();
            $table->integer('id_pedido')->length(11);
            $table->string('codigo_p', 20);
            $table->decimal('cant_prod', 20, 2);
            $table->decimal('precio_producto', 20, 2);

            $table->unique(["id_pedido", "codigo_p", "precio_producto"], 'id_pedido_2');

            $table->foreign('id_pedido', 'detalle_pedido_ibfk_1')
                ->references('id_pedido')->on('pedido')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('codigo_p', 'detalle_pedido_ibfk_2')
                ->references('codigo_prod')->on('productos')
                ->onDelete('restrict')
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
