<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTablaConClienteCreatedAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('SET SQL_SAFE_UPDATES = 0;

                        UPDATE
                            con_cliente
                        LEFT JOIN
                            clientes ON clientes.id_cliente = con_cliente.id_cliente
                        SET
                            con_cliente.created_at = clientes.created_at
                        WHERE
                                con_cliente.id > 0
                            AND
                                con_cliente.id_cliente > 0
                            AND
                                con_cliente.created_at IS NULL;
                        SET SQL_SAFE_UPDATES = 1;

        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('UPDATE
                        con_cliente
                    SET
                        con_cliente.created_at = null
                    WHERE
                            con_cliente.id > 0
                        AND
                            con_cliente.id_cliente > 0
                        AND
                            con_cliente.created_at IS NOT NULL;
        ');
    }
}
