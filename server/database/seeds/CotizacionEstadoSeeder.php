<?php

use App\CotizacionEstado;
use Illuminate\Database\Seeder;

class CotizacionEstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $estados = [
            ['descripcion' => 'Anulado'],
            ['descripcion' => 'Aprobado'],
            ['descripcion' => 'Desaprobado'],
            ['descripcion' => 'Pendiente'],
        ];

        foreach ($estados as $estado) {
            $nuevoEstado = new CotizacionEstado();
            $nuevoEstado->fill($estado);
            $nuevoEstado->save();
        }
    }
}
