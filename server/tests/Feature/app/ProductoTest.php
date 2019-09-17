<?php

namespace Tests\Feature\app;

use App\Producto;
use Tests\TestCase;
use CategoriaProductoSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Utilidades\EstructuraProducto;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductoTest extends TestCase
{
    use RefreshDatabase;
    use EstructuraProducto;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CategoriaProductoSeeder::class);
    }

    public function test_deberia_crear_un_producto()
    {
        $producto = factory(Producto::class)->create();
        $productoDB = Producto::findOrFail($producto->id)->toArray();

        foreach ($this->atributosProducto as $atributo) {
            $this->assertArrayHasKey($atributo, $productoDB);
        }
    }

    public function test_deberia_llenar_los_atributos_fillable_de_producto()
    {
        $entrada = factory(Producto::class)->make()->toArray();

        $producto = new Producto();
        $producto->fill($entrada);
        $guardado = $producto->save();

        $this->assertTrue($guardado);
    }
}
