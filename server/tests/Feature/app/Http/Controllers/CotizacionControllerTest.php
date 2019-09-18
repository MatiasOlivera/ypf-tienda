<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Producto;
use App\Cotizacion;
use Tests\TestCase;
use CotizacionEstadoSeeder;
use CategoriaProductoSeeder;
use Tests\Feature\Utilidades\AuthHelper;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraCotizacion;
use Tests\Feature\Utilidades\EstructuraJsonHelper;
use App\Http\Resources\Cotizacion\CotizacionResource;
use App\Http\Resources\Cotizacion\CotizacionCollection;

class CotizacionControllerTest extends TestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EloquenceSolucion;
    use EstructuraJsonHelper;
    use EstructuraCotizacion;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CotizacionEstadoSeeder::class);
        $this->seed(CategoriaProductoSeeder::class);
    }

    private function getEstructuraCotizaciones(): array
    {
        $paginacion = $this->estructuraPaginacion;
        unset($paginacion['paginacion']['rutas']);
        return array_merge(['cotizaciones'], $paginacion);
    }

    private function getCotizacion($id): array
    {
        $cotizacion = Cotizacion::with([
            'empleado',
            'cliente',
            'razonSocial',
            'cotizacionEstado',
            'telefono',
            'domicilio',
            'observacion',
            'pedido',
            'productos.producto'
        ])->findOrFail($id);
        $recurso = new CotizacionResource($cotizacion);
        $respuesta = $recurso->response()->getData(true);
        return $respuesta['cotizacion'];
    }

    public function test_no_deberia_obtener_ninguna_cotizacion()
    {
        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', 'api/cotizaciones');

        $estructura = $this->getEstructuraCotizaciones();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson(['cotizaciones' => []]);
    }

    public function test_deberia_obtener_cotizaciones()
    {
        factory(Cotizacion::class, 10)->create();

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', 'api/cotizaciones');

        $estructura = $this->getEstructuraCotizaciones();

        $cotizacionesTabla = Cotizacion::orderBy('created_at', 'DESC')->paginate(10);
        $cotizacionesColeccion = new CotizacionCollection($cotizacionesTabla);
        $cotizacionesRespuesta = $cotizacionesColeccion->response()->getData(true);

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson($cotizacionesRespuesta);
    }

    public function test_deberia_crear_una_cotizacion()
    {
        $nuevaCotizacion = factory(Cotizacion::class)
            ->states('productos')
            ->create()
            ->toArray();

        $productos = $this->getCotizacion($nuevaCotizacion['id'])['productos'];

        foreach ($productos as $producto) {
            $inputProductos[] = [
                'id' => $producto['producto']['id'],
                'cantidad' => $producto['cantidad'],
                'precio' => $producto['precio']
            ];
        }

        $nuevaCotizacion['productos'] = $inputProductos;

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', 'api/cotizaciones', $nuevaCotizacion);

        $id = $respuesta->getData(true)['cotizacion']['id'];
        $cotizacionRespuesta = $this->getCotizacion($id);

        $respuesta
            ->assertStatus(201)
            ->assertExactJson([
                'cotizacion' => $cotizacionRespuesta,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'GUARDADO',
                    'descripcion' => 'La cotización ha sido creada'
                ]
            ]);
    }
}
