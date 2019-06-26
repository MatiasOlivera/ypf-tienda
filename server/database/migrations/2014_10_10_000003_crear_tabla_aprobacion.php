<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaAprobacion extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tabla = 'aprobacion';

    /**
     * Run the migrations.
     * @table aprobacion
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tabla, function (Blueprint $table) {
            $table->integer('id_apro')->length(11)->autoIncrement();
            $table->string('desc_apro', 11);

            $table->unique(["desc_apro"], 'desc_apro');
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
