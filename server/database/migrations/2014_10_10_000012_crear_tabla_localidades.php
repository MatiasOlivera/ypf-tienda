<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaLocalidades extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tabla = 'localidades';

    /**
     * Run the migrations.
     * @table localidades
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tabla, function (Blueprint $table) {
            $table->integer('id_localidad')->length(11)->autoIncrement();
            $table->string('nom_localidad', 60);
            $table->integer('id_provincia')->length(11);

            $table->index(["id_provincia"], 'id_provincia');

            $table->foreign('id_provincia', 'localidades_ibfk_1')
                ->references('id_provincia')->on('provincias')
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
