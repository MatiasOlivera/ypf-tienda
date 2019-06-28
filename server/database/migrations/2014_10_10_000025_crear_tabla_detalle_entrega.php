<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaDetalleEntrega extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tabla = 'detalle_entrega';

    /**
     * Run the migrations.
     * @table detalle_entrega
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tabla, function (Blueprint $table) {
            $table->integer('id_pedido')->length(11);
            $table->integer('id_entrega')->length(11);
            $table->integer('id_det_pedido')->length(11);
            $table->decimal('cantidad', 20, 2);

            $table->primary(['id_pedido', 'id_entrega', 'id_det_pedido']);

            $table->foreign(['id_pedido', 'id_entrega'], 'fk_pedido_entrega')
                ->references(['id_pedido', 'id_entrega'])->on('pedido_entrega')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('id_det_pedido', 'fk_producto_precio')
                ->references('id_det_pedido')->on('detalle_pedido')
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
