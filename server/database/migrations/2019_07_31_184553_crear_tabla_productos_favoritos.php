<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaProductosFavoritos extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tabla = 'productos_favoritos';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tabla, function (Blueprint $table) {
            $table->unsignedInteger('cliente_usuario_id');
            $table->integer('producto_id')->length(11);
            $table->timestamps();

            $table->primary(['cliente_usuario_id', 'producto_id']);

            $table->foreign('cliente_usuario_id')
                ->references('id')
                ->on('cliente_usuarios');

            $table->foreign('producto_id')
                ->references('id')
                ->on('productos');
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
