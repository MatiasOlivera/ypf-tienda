<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaCategorias extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tabla = 'categorias';

    /**
     * Run the migrations.
     * @table categorias
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tabla, function (Blueprint $table) {
            $table->integer('ID_CAT_prod')->length(11)->autoIncrement();
            $table->string('desc_cat', 200);
            $table->enum('estado', ['0', '1'])->default('1');

            $table->unique(["desc_cat"], 'desc_cat');
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
