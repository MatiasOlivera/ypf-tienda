<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaClientes extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tabla = 'clientes';

    /**
     * Run the migrations.
     * @table clientes
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tabla, function (Blueprint $table) {
            $table->integer('id_cliente')->length(11)->autoIncrement();
            $table->integer('dni')->nullable()->default(null);
            $table->string('cliente', 100);
            $table->string('obsevacion', 200)->nullable()->default(null);
            $table->string('otros', 150)->nullable()->default(null);
            $table->string('f_carga', 10);
            // estado era del tipo bit
            $table->tinyInteger('estado')->default(1);
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
