<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaPermiso extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tabla = 'permiso';

    /**
     * Run the migrations.
     * @table permiso
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tabla, function (Blueprint $table) {
            $table->integer('ID_ven')->length(11);
            $table->integer('ID_recurso')->length(11);
            $table->tinyInteger('ver')->nullable()->default(null);
            $table->tinyInteger('crear')->nullable()->default(null);
            $table->tinyInteger('editar')->nullable()->default(null);
            $table->tinyInteger('borrar')->nullable()->default(null);

            $table->primary(['ID_ven', 'ID_recurso']);

            $table->foreign('ID_recurso', 'FK_permiso_recurso')
                ->references('ID_recurso')->on('recurso')
                ->onDelete('no action')
                ->onUpdate('cascade');

            $table->foreign('ID_ven', 'FK_permiso_usuario')
                ->references('ID_ven')->on('usuarios')
                ->onDelete('no action')
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
