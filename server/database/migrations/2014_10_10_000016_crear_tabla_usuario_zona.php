<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaUsuarioZona extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tabla = 'usuario_zona';

    /**
     * Run the migrations.
     * @table usuario_zona
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tabla, function (Blueprint $table) {
            $table->integer('id');
            $table->integer('id_us')->length(11);
            $table->integer('id_zona')->length(11);
            $table->enum('estado', ['0', '1']);

            $table->index(["id_us", "id_zona"], 'id_us');

            $table->foreign('id_us', 'usuario_zona_ibfk_1')
                ->references('ID_ven')->on('usuarios')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('id_zona', 'usuario_zona_ibfk_2')
                ->references('id_zona')->on('zonas')
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
