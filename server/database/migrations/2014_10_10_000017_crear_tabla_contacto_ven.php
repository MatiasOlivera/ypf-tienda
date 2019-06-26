<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaContactoVen extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tabla = 'contacto_ven';

    /**
     * Run the migrations.
     * @table contacto_ven
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tabla, function (Blueprint $table) {
            $table->integer('id_con_ven')->length(11)->autoIncrement();
            $table->integer('id_ven')->length(11);
            $table->integer('area');
            $table->integer('cel');
            $table->string('mail', 200);

            $table->unique(["mail"], 'mail');
            $table->unique(["area", "cel"], 'area y cel');

            $table->foreign('id_ven', 'contacto_ven_ibfk_1')
                ->references('ID_ven')->on('usuarios')
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
