<?php

use App\PedidoEntregaEstado;
use Illuminate\Database\Seeder;

class PedidoEntregaEstadoSeeder extends Seeder
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
            $nuevoEstado = new PedidoEntregaEstado();
            $nuevoEstado->fill($estado);
            $nuevoEstado->save();
        }
    }
}
