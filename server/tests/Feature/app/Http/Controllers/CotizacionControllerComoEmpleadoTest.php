<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Cotizacion;
use Tests\ApiTestCase;
use AutorizacionSeeder;
use CotizacionEstadoSeeder;
use CategoriaProductoSeeder;
use Tests\Feature\Utilidades\AuthHelper;
use Tests\Feature\Utilidades\Api\CotizacionApi;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraCotizacion;
use Tests\Feature\Utilidades\EstructuraJsonHelper;
use App\Http\Resources\Cotizacion\CotizacionCollection;

class CotizacionControllerComoEmpleadoTest extends ApiTestCase
{
    use AuthHelper;
    use CotizacionApi;
    use RefreshDatabase;
    use EloquenceSolucion;
    use EstructuraJsonHelper;
    use EstructuraCotizacion;

    protected $usuario;
    protected $cabeceras;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CotizacionEstadoSeeder::class);
        $this->seed(CategoriaProductoSeeder::class);
        $this->seed(AutorizacionSeeder::class);

        $login = $this->loguearseComoEmpleado();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];
    }

    private function getEstructuraCotizaciones(): array
    {
        $paginacion = $this->estructuraPaginacion;
        unset($paginacion['paginacion']['rutas']);
        return array_merge(['cotizaciones'], $paginacion);
    }

    public function test_el_empleado_no_deberia_obtener_ninguna_cotizacion()
    {
        $this->usuario->givePermissionTo('ver cotizaciones');

        $respuesta = $this->obtenerCotizaciones();

        $estructura = $this->getEstructuraCotizaciones();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson(['cotizaciones' => []]);
    }

    public function test_el_empleado_deberia_obtener_cotizaciones()
    {
        factory(Cotizacion::class, 10)->create();

        $this->usuario->givePermissionTo('ver cotizaciones');
        $respuesta = $this->obtenerCotizaciones();

        $estructura = $this->getEstructuraCotizaciones();

        $cotizacionesTabla = Cotizacion::orderBy('created_at', 'DESC')->paginate(10);
        $cotizacionesColeccion = new CotizacionCollection($cotizacionesTabla);
        $cotizacionesRespuesta = $cotizacionesColeccion->response()->getData(true);

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson($cotizacionesRespuesta);
    }
}
