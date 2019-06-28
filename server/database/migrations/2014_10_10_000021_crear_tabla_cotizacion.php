<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaCotizacion extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tabla = 'cotizacion';

    /**
     * Run the migrations.
     * @table cotizacion
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tabla, function (Blueprint $table) {
            $table->integer('id_cot')->length(11)->autoIncrement();
            $table->integer('id_us')->length(11);
            $table->integer('id_cliente')->length(11);
            $table->integer('id_rz')->length(11);
            $table->string('fecha_pedido', 10);
            $table->integer('estado')->length(11);
            $table->enum('cons_final', ['0', '1'])->default('0');
            $table->string('plazo', 128)->nullable()->default(null);
            $table->integer('id_con')->length(11)->nullable()->default(null);
            $table->integer('id_dom')->length(11);
            $table->integer('id_pedido')->length(11)->nullable()->default(null);
            $table->integer('id_observacion')->length(11)->nullable()->default('1');

            $table->foreign('estado', 'cotizacion_estado')
                ->references('id_apro')->on('aprobacion')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('id_us', 'cotizacion_ibfk_1')
                ->references('ID_ven')->on('usuarios')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('id_cliente', 'cotizacion_ibfk_2')
                ->references('id_cliente')->on('clientes')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('id_rz', 'cotizacion_ibfk_5')
                ->references('id_razon')->on('razones_sociales')
                ->onDelete('no action')
                ->onUpdate('cascade');

            $table->foreign('id_con', 'cotizacion_ibfk_6')
                ->references('id')->on('con_cliente')
                ->onDelete('no action')
                ->onUpdate('cascade');

            $table->foreign('id_pedido', 'cotizacion_ibfk_7')
                ->references('id_pedido')->on('pedido')
                ->onDelete('no action')
                ->onUpdate('cascade');

            $table->foreign('id_observacion', 'cotizacion_ibfk_8')
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
