<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaRazonesSociales extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tabla = 'razones_sociales';

    /**
     * Run the migrations.
     * @table razones_sociales
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tabla, function (Blueprint $table) {
            $table->integer('id_razon')->length(11)->autoIncrement();
            $table->string('nombre', 100);
            $table->string('cuit', 17);
            $table->integer('id_loc')->length(11);
            $table->string('calle', 70)->nullable()->default(null);
            $table->integer('altura')->nullable()->default(null);
            $table->integer('area_tel')->nullable()->default(null);
            $table->integer('tel')->nullable()->default(null);
            $table->string('mail', 150)->nullable()->default(null);
            $table->enum('estado', ['0', '1']);
            $table->string('fecha_carga', 10);

            $table->index(["area_tel", "tel"], 'telefono');
            $table->unique(["mail"], 'mail');
            $table->unique(["cuit"], 'cuit');

            $table->foreign('id_loc', 'razones_sociales_ibfk_1')
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
