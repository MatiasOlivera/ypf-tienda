<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CopiaTablaClientesFechaDeCargaACreatedAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('UPDATE clientes
                        SET
                            created_at = f_carga
                        WHERE
                            id_cliente >0;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('UPDATE clientes
                        SET
                            created_at = null
                        WHERE
                            id_cliente >0;
         ');
    }
}
