<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTriggerOnInsertConCliente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE TRIGGER insert_con_cliente AFTER INSERT ON con_cliente
                        FOR EACH ROW
                        BEGIN

                            SET @cliente     := new.id_cliente;
                            SET @id_contacto := new.id;
                            SET @mail        := new.mail;
                            SET @created     := (SELECT  CURRENT_TIMESTAMP);

                                IF @mail IS NOT NULL THEN
                                    INSERT INTO cliente_mails (cliente_id, mail, contacto_id,  created_at)
                                                        value (@cliente,   @mail, @id_contacto,  @created);
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
        DB::unprepared('DROP TRIGGER insert_con_cliente;');
    }
}
