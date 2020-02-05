<?php

use App\Producto;
use App\CategoriaProducto;
use Faker\Generator as Faker;

$factory->define(App\Producto::class, function (Faker $faker) {
    return [
        'codigo' => function () use ($faker) {
            $ejecutar = true;

            while ($ejecutar) {
                $codigo = (string) $faker->randomNumber(6);
                $productoExistente = Producto::where('codigo', $codigo)->first();

                if (is_null($productoExistente)) {
                    $ejecutar = false;
                    return $codigo;
                }
            }
        },
        'nombre' => $faker->word(),
        'presentacion' => $faker->sentence(2),
        'precio_por_mayor' => number_format($faker->randomFloat(2, 0, 10000), 2, '.', ''),
        'consumidor_final' => number_format($faker->randomFloat(2, 0, 10000), 2, '.', ''),

        // Categoría
        'ID_CAT' => function () {
            $categoria = CategoriaProducto::inRandomOrder()->first();

            if ($categoria === null) {
                throw new Exception("Debes usar el seeder CategoriaProductoSeeder");
            }

            return $categoria->id;
        }
    ];
});
