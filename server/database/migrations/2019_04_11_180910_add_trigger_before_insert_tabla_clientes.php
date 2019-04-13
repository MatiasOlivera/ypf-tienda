<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTriggerBeforeInsertTablaClientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE TRIGGER insert_clientes BEFORE INSERT ON clientes
                        FOR EACH ROW
                        BEGIN
                            SET new.f_carga = (SELECT DATE(NOW()));
                            SET new.estado = 1;
                        END;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER insert_clientes;');
    }
}
