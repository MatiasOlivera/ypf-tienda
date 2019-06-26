<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaConCliente extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tabla = 'con_cliente';

    /**
     * Run the migrations.
     * @table con_cliente
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tabla, function (Blueprint $table) {
            $table->integer('id')->length(11)->autoIncrement();
            $table->integer('id_cliente')->length(11);
            $table->integer('area')->nullable()->default(null);
            $table->integer('tel')->nullable()->default(null);
            $table->string('mail', 200)->nullable()->default(null);
            $table->enum('estado', ['0', '1'])->default('1');
            $table->string('nombre_contacto', 60)->nullable()->default(null);

            $table->foreign('id_cliente', 'con_cliente_ibfk_1')
                ->references('id_cliente')->on('clientes')
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
