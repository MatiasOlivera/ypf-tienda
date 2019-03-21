<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTablaConClienteDeletedAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("UPDATE con_cliente
                        SET
                            deleted_at = (SELECT  CURRENT_TIMESTAMP)
                        WHERE
                                estado = '0'
                            AND
                                id > 0
                            AND
                                deleted_at IS NULL;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("UPDATE con_cliente
                        SET
                            deleted_at = null
                        WHERE
                                estado = '0'
                            AND
                                id > 0
                            AND
                                deleted_at IS NOT NULL;
        ");
    }
}
