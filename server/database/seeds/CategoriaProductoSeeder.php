<?php

use App\CategoriaProducto;
use Illuminate\Database\Seeder;

class CategoriaProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categorias = [
            ['descripcion' => 'Autos'],
            ['descripcion' => 'Motos'],
            ['descripcion' => 'Náutica'],
            ['descripcion' => 'Agro']
        ];

        foreach ($categorias as $categoria) {
            $nuevaCategoria = new CategoriaProducto();
            $nuevaCategoria->fill($categoria);
            $nuevaCategoria->save();
        }
    }
}
