<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaPedido extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tabla = 'pedido';

    /**
     * Run the migrations.
     * @table pedido
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tabla, function (Blueprint $table) {
            $table->integer('id_pedido')->length(11)->autoIncrement();
            $table->integer('id_us')->length(11);
            $table->integer('id_cliente')->length(11);
            $table->integer('id_rz')->length(11);
            $table->string('fecha_pedido', 10);
            $table->integer('estado')->length(11);
            $table->string('fecha_entrega', 10)->nullable()->default(null);
            $table->integer('est_entrega')->length(11);
            $table->integer('id_dom_cliente')->length(11);
            $table->enum('cons_final', ['0', '1'])->default('0');
            $table->integer('id_con')->length(11)->nullable()->default(null);
            $table->string('plazo', 128)->nullable()->default(null);
            $table->integer('id_observacion')->length(11)->nullable()->default(null);
            $table->integer('remito')->nullable()->default(null);
            $table->dateTime('generado')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('id_us', 'pedido_ibfk_1')
                ->references('ID_ven')->on('usuarios')
                ->onDelete('no action')
                ->onUpdate('cascade');

            $table->foreign('id_cliente', 'pedido_ibfk_2')
                ->references('id_cliente')->on('clientes')
                ->onDelete('no action')
                ->onUpdate('cascade');

            $table->foreign('estado', 'pedido_ibfk_3')
                ->references('id_apro')->on('aprobacion')
                ->onDelete('no action')
                ->onUpdate('cascade');

            $table->foreign('est_entrega', 'pedido_ibfk_4')
                ->references('id_estado')->on('estado_pedido')
                ->onDelete('no action')
                ->onUpdate('cascade');

            $table->foreign('id_rz', 'pedido_ibfk_5')
                ->references('id_razon')->on('razones_sociales')
                ->onDelete('no action')
                ->onUpdate('cascade');

            $table->foreign('id_dom_cliente', 'pedido_ibfk_6')
                ->references('id_dom')->on('dom_cliente')
                ->onDelete('no action')
                ->onUpdate('cascade');

            $table->foreign('id_con', 'pedido_ibfk_7')
                ->references('id')->on('con_cliente')
                ->onDelete('no action')
                ->onUpdate('cascade');

            $table->foreign('id_observacion', 'pedido_ibfk_8')
                ->references('id')->on('observacion')
                ->onDelete('restrict')
                ->onUpdate('restrict');
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
