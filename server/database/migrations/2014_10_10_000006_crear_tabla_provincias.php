<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaProvincias extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tabla = 'provincias';

    /**
     * Run the migrations.
     * @table provincias
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tabla, function (Blueprint $table) {
            $table->integer('id_provincia')->length(11)->autoIncrement();
            $table->string('nom_provincia', 30);
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
