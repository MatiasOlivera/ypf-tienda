<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CopiarMailsAClienteMails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Se asegura de eliminar el procedimiento almacenado. Sin esta línea
         * no funcionan las migraciones (`php artisan migrate:refresh`) ni los
         * tests con PHPUnit
         *
         * Evita el error:
         *
         * SQLSTATE[42000]: Syntax error or access violation: 1304
         * PROCEDURE mover_mails already exists
         */
        DB::unprepared("DROP PROCEDURE IF EXISTS mover_mails;");

        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `mover_mails`()
                SQL SECURITY INVOKER
            BEGIN
                DECLARE fin         INT DEFAULT FALSE;
                DECLARE contacto_id INT(11);
                DECLARE cliente_id  INT(11);
                DECLARE email       varchar(250);
                DECLARE creado      DATETIME;
                DECLARE eliminado   DATETIME;
                DECLARE mails  CURSOR FOR SELECT id, id_cliente, mail, created_at, deleted_at FROM con_cliente WHERE mail IS NOT NULL;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin = TRUE;

            OPEN mails;
                copiar_mails: LOOP

                        FETCH mails INTO contacto_id, cliente_id, email, creado, eliminado;

                        IF fin THEN
                            LEAVE copiar_mails;
                        END IF;

                        INSERT INTO cliente_mails (contacto_id, cliente_id, mail, created_at, deleted_at)
                                            VALUES (contacto_id, cliente_id, email, creado,     eliminado);

                    END LOOP copiar_mails;
                CLOSE mails;
            END;
        ");
        DB::statement('call `mover_mails`();');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("TRUNCATE TABLE cliente_mails;");
        DB::unprepared("DROP PROCEDURE IF EXISTS mover_mails;");
    }
}
