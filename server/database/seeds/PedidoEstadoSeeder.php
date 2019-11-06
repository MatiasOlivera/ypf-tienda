<?php

use App\PedidoEstado;
use Illuminate\Database\Seeder;

class PedidoEstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $estados = [
            ['descripcion' => 'Pendiente'],
            ['descripcion' => 'Entrega Parcial'],
            ['descripcion' => 'Entregado'],
            ['descripcion' => 'Anulado'],
        ];

        foreach ($estados as $estado) {
            $nuevoEstado = new PedidoEstado();
            $nuevoEstado->fill($estado);
            $nuevoEstado->save();
        }
    }
}
