<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ActualizarElValorPorDefectoDeIdObservacionEnCotizacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cotizacion', function (Blueprint $table) {
            $table->integer('id_observacion')->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cotizacion', function (Blueprint $table) {
            $table->integer('id_observacion')->default('1')->change();
        });
    }
}
