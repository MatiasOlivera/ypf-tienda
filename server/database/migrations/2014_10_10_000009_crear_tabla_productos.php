<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaProductos extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tabla = 'productos';

    /**
     * Run the migrations.
     * @table productos
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tabla, function (Blueprint $table) {
            $table->integer('id')->length(11)->autoIncrement();
            $table->string('codigo_prod', 20);
            $table->string('nom_prod', 200);
            $table->string('presentacion', 60);
            $table->integer('ID_CAT')->length(11);
            $table->decimal('costo', 10, 2)->nullable()->default('0.00');
            $table->decimal('por_mayor', 10, 2)->nullable()->default('0.00');
            $table->integer('iva')->nullable()->default(null);
            $table->integer('id_marca')->nullable()->default(null);
            $table->enum('estado', ['0', '1'])->default('1');
            $table->decimal('cons_fin', 10, 2)->nullable()->default('0.00');

            $table->index(["codigo_prod"], 'codigo_prod');
            $table->index(["id_marca"], 'id_marca');

            $table->foreign('ID_CAT', 'productos_ibfk_1')
                ->references('ID_CAT_prod')->on('categorias')
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
