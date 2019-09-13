<?php

use App\CategoriaProducto;
use Faker\Generator as Faker;

$factory->define(App\Producto::class, function (Faker $faker) {
    return [
        'codigo' => (string) $faker->randomNumber(6),
        'nombre' => $faker->word(),
        'presentacion' => $faker->sentence(2),
        'precio_por_mayor' => (string) $faker->randomFloat(2, 0, 10000),
        'consumidor_final' => (string) $faker->randomFloat(2, 0, 10000),

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
