<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTriggerOnUpdateClienteRazon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE TRIGGER update_cliente_razon BEFORE UPDATE ON `cliente_razon`
                        FOR EACH ROW
                        BEGIN

                            SET @id_contacto := OLD.id_razon;
                            SET @updated := (SELECT CURRENT_TIMESTAMP);
                            SET @estado := new.estado;

                            IF old.estado = "0" AND new.estado = "1" THEN
                                SET @eliminado := null;
                            END IF;

                            IF old.estado = "1" AND new.estado = "0"  THEN
                                SET @eliminado := (SELECT CURRENT_TIMESTAMP);
                            END IF;


                            IF (old.deleted_at IS NULL AND new.deleted_at IS NOT NULL) THEN
                                SET @estado =  "0" ;
                                SET @eliminado := new.deleted_at;
                            END IF;

                            IF (old.deleted_at IS NOT NULL AND new.deleted_at IS NULL) THEN
                                SET @estado = "1" ;
                                SET @eliminado := new.deleted_at;
                            END IF;

                            SET new.estado := @estado;
                            SET new.deleted_at := @eliminado;
                            SET new.updated_at := @updated;

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
        DB::unprepared('DROP TRIGGER update_cliente_razon;');
    }
}
