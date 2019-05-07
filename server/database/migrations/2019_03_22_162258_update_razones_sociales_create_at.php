<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRazonesSocialesCreateAt extends Migration
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
                            razones_sociales
                        SET
                            created_at = fecha_carga
                        WHERE
                                id_razon > 0
                            AND
                                created_at IS NULL;

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
                            razones_sociales
                        SET
                            created_at = null
                        WHERE
                                id_razon > 0
                            AND
                                created_at IS NOT NULL;
        ');
    }
}
