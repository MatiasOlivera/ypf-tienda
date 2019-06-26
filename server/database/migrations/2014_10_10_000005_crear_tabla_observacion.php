<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaObservacion extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tabla = 'observacion';

    /**
     * Run the migrations.
     * @table observacion
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tabla, function (Blueprint $table) {
            $table->integer('id')->length(11)->autoIncrement();
            $table->string('descripcion', 250)->nullable()->default(null);
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
