<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EliminarTablasUtilizadasParaAutorizacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Cargos
         */
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropForeign('FK_cargo');
            $table->dropColumn('id_cargo');
        });
        Schema::dropIfExists('cargo');

        // Permisos
        Schema::dropIfExists('permiso');

        // Recursos
        Schema::dropIfExists('recurso');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /**
         * Cargos
         */
        Schema::create('cargo', function (Blueprint $table) {
            $table->integer('id_cargo')->length(11)->autoIncrement();
            $table->string('nombre', 45);
        });

        Schema::table('usuarios', function (Blueprint $table) {
            $table->integer('id_cargo')->length(11)->default('100');
            $table->foreign('id_cargo', 'FK_cargo')
                ->references('id_cargo')->on('cargo')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });

        /**
         * Recursos
         */
        Schema::create('recurso', function (Blueprint $table) {
            $table->integer('ID_recurso')->length(11)->autoIncrement();
            $table->string('nombre', 45);
        });

        /**
         * Permisos
         */
        Schema::create('permiso', function (Blueprint $table) {
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
}
