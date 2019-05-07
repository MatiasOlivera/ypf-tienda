<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablaConClienteDelUniqueIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('con_cliente', function (Blueprint $table) {
            $table->dropIndex('area');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('con_cliente', function (Blueprint $table) {
            $table->unique(['area', 'tel'], 'area');
        });
    }
}
