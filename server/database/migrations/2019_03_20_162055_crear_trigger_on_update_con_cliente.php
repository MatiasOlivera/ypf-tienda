<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTriggerOnUpdateConCliente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE TRIGGER update_contactos_mail BEFORE UPDATE ON `con_cliente`
                        FOR EACH ROW
                        BEGIN

                            SET @id_contacto := OLD.id;
                            SET @creado := old.created_at;
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

                            IF new.mail IS NOT NULL THEN

                                UPDATE cliente_mails
                                    SET
                                      email=new.mail,
                                      created_at = @creado,
                                      updated_at = @updated,
                                      deleted_at = @eliminado
                                 WHERE
                                    contacto_id = @id_contacto;

                            END IF;


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
        DB::unprepared('DROP TRIGGER update_contactos_mail;');
    }
}
