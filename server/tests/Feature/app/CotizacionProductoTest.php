<?php

namespace Tests\Feature\app;

use App\Producto;
use App\Cotizacion;
use Tests\TestCase;
use App\CotizacionProducto;
use CotizacionEstadoSeeder;
use CategoriaProductoSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraCotizacionProducto;

class CotizacionProductoTest extends TestCase
{
    use RefreshDatabase;
    use EstructuraCotizacionProducto;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CotizacionEstadoSeeder::class);
        $this->seed(CategoriaProductoSeeder::class);
    }

    public function test_deberia_crear_un_producto()
    {
        $cotizacion = factory(Cotizacion::class)->states('productos')->create();
        $producto = $cotizacion->productos()->first();

        $productoDB = CotizacionProducto::findOrFail($producto->id)->toArray();

        foreach ($this->atributosCotizacionProducto as $atributo) {
            $this->assertArrayHasKey($atributo, $productoDB);
        }
    }

    public function test_deberia_llenar_los_atributos_fillable_de_producto()
    {
        $entrada = factory(CotizacionProducto::class)->make()->toArray();
        $entrada['cotizacion_id'] = factory(Cotizacion::class)->create()->id;

        $producto = new CotizacionProducto();
        $producto->fill($entrada);
        $guardado = $producto->save();

        $this->assertTrue($guardado);

        unset($entrada['id']);
        $this->assertArraySubset($entrada, $producto->toArray());
    }

    public function test_deberia_acceder_a_la_relacion_cotizacion()
    {
        $cotizacionId = factory(Cotizacion::class)->create()->id;
        $producto = factory(CotizacionProducto::class)->make([
            'cotizacion_id' => $cotizacionId
        ]);

        $cotizacion = $producto->cotizacion;

        $this->assertInstanceOf(Cotizacion::class, $cotizacion);
        $this->assertEquals($cotizacion->id, $producto->cotizacion_id);
    }

    public function test_deberia_acceder_a_la_relacion_producto()
    {
        $cotizacionId = factory(Cotizacion::class)->create()->id;
        $cotizacionProducto = factory(CotizacionProducto::class)->create([
            'cotizacion_id' => $cotizacionId
        ]);

        $producto = $cotizacionProducto->producto;

        $this->assertInstanceOf(Producto::class, $producto);
        $this->assertEquals($producto->codigo, $cotizacionProducto->codigo);
    }
}
