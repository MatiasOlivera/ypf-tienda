<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Doctrine\DBAL\Types\Type;

class ModificarLaColumnaFechaCargaDeRazonesSociales extends Migration
{
    public function __construct()
    {
        DB::getDoctrineSchemaManager()
            ->getDatabasePlatform()
            ->registerDoctrineTypeMapping('enum', 'string');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            ALTER TABLE `razones_sociales` CHANGE `fecha_carga`
            `fecha_carga` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('razones_sociales', function (Blueprint $table) {
            $table->string('fecha_carga', 10)->change();
        });
    }
}
