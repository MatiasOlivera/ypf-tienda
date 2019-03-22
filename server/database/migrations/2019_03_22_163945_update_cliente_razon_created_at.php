<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateClienteRazonCreatedAt extends Migration
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
                            cliente_razon
                        LEFT JOIN
                            razones_sociales ON razones_sociales.id_razon = cliente_razon.id_razon
                        SET
                            cliente_razon.created_at = razones_sociales.created_at
                        WHERE
                            cliente_razon.id > 0
                        AND
                            cliente_razon.created_at IS NULL;

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
                            cliente_razon
                        SET
                            created_at = null
                        WHERE
                                id > 0
                            AND
                                created_at IS NOT NULL;
        ');
    }
}
