<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaDomCliente extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tabla = 'dom_cliente';

    /**
     * Run the migrations.
     * @table dom_cliente
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tabla, function (Blueprint $table) {
            $table->integer('id_dom')->length(11)->autoIncrement();
            $table->integer('id_cliente')->length(11);
            $table->integer('id_loc')->length(11);
            $table->string('calle', 70)->nullable()->default(null);
            $table->integer('numero_altura')->nullable()->default(null);
            $table->string('acla', 200)->nullable()->default(null);
            $table->enum('estado', ['0', '1'])->default('1');

            $table->foreign('id_cliente', 'dom_cliente_ibfk_1')
                ->references('id_cliente')->on('clientes')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('id_loc', 'localidad')
                ->references('id_localidad')->on('localidades')
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
