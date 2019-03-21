<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTablaClientesDeletedAt extends Migration
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
                            deleted_at = (SELECT  CURRENT_TIMESTAMP)
                        where
                            estado = 0
                        AND
                            id_cliente > 0
                        AND
                            deleted_at IS NULL;
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
                            deleted_at = null
                        where
                            estado = 0
                        AND
                            id_cliente > 0
                        AND
                            deleted_at  IS NOT NULL;
        ');
    }
}
