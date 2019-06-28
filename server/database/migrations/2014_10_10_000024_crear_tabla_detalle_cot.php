<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaDetalleCot extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tabla = 'detalle_cot';

    /**
     * Run the migrations.
     * @table detalle_cot
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tabla, function (Blueprint $table) {
            $table->bigIncrements('id_det');
            $table->integer('id_cot')->length(11);
            $table->string('codigo_p', 20);
            $table->decimal('cant_prod', 20, 2);
            $table->decimal('precio_producto', 20, 2);
            $table->integer('estado')->length(11)->default('1');

            $table->unique(["id_cot", "codigo_p", "precio_producto"], 'producto_precio_uk');

            $table->foreign('id_cot', 'detalle_cot_ibfk_1')
                ->references('id_cot')->on('cotizacion')
                ->onDelete('no action')
                ->onUpdate('cascade');

            $table->foreign('codigo_p', 'detalle_cot_ibfk_2')
                ->references('codigo_prod')->on('productos')
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
