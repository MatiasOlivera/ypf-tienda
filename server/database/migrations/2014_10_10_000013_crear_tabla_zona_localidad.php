<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaZonaLocalidad extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tabla = 'zona_localidad';

    /**
     * Run the migrations.
     * @table zona_localidad
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tabla, function (Blueprint $table) {
            $table->integer('id_zona')->length(11);
            $table->integer('id_localidad')->length(11);

            $table->primary(['id_zona', 'id_localidad']);

            $table->foreign('id_zona', 'zona_localidad_ibfk_1')
                ->references('id_zona')->on('zonas')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('id_localidad', 'zona_localidad_ibfk_2')
                ->references('id_localidad')->on('localidades')
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
