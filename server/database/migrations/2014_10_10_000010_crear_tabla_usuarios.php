<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaUsuarios extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tabla = 'usuarios';

    /**
     * Run the migrations.
     * @table usuarios
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tabla, function (Blueprint $table) {
            $table->integer('ID_ven')->length(11)->autoIncrement();
            $table->integer('dni_ven');
            $table->string('apellido', 80);
            $table->string('nombre', 80);
            $table->string('fe_na', 10)->nullable()->default(null);
            $table->char('sexo', 1)->nullable()->default(null);
            $table->string('pass');
            // estado era del tipo bit
            $table->tinyInteger('estado')->default(1);
            $table->integer('id_cargo')->length(11)->default('100');

            $table->unique(["dni_ven"], 'dni_ven');

            $table->foreign('id_cargo', 'FK_cargo')
                ->references('id_cargo')->on('cargo')
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
