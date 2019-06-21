<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaRecurso extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tabla = 'recurso';

    /**
     * Run the migrations.
     * @table recurso
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tabla, function (Blueprint $table) {
            $table->integer('ID_recurso')->length(11)->autoIncrement();
            $table->string('nombre', 45);
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
