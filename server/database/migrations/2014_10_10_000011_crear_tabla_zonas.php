<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaZonas extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tabla = 'zonas';

    /**
     * Run the migrations.
     * @table zonas
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tabla, function (Blueprint $table) {
            $table->integer('id_zona')->length(11)->autoIncrement();
            $table->integer('prov_zona')->length(11);
            $table->string('desc_zona', 70);

            $table->index(["prov_zona"], 'prov_zona');

            $table->foreign('prov_zona', 'zonas_ibfk_1')
                ->references('id_provincia')->on('provincias')
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
