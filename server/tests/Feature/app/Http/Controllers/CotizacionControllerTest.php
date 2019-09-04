<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Cotizacion;
use Tests\TestCase;
use Tests\Feature\Utilidades\AuthHelper;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraCotizacion;
use Tests\Feature\Utilidades\EstructuraJsonHelper;
use App\Http\Resources\Cotizacion\CotizacionCollection;

class CotizacionControllerTest extends TestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EloquenceSolucion;
    use EstructuraJsonHelper;
    use EstructuraCotizacion;

    private function getEstructuraCotizaciones(): array
    {
        $paginacion = $this->estructuraPaginacion;
        unset($paginacion['paginacion']['rutas']);
        return array_merge(['cotizaciones'], $paginacion);
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
}
