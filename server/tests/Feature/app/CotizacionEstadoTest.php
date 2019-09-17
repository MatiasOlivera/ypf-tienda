<?php

namespace Tests\Feature\app;

use App\Cotizacion;
use Tests\TestCase;
use App\CotizacionEstado;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraCotizacionEstado;

class CotizacionEstadoTest extends TestCase
{
    use RefreshDatabase;
    use EloquenceSolucion;
    use EstructuraCotizacionEstado;

    public function test_deberia_crear_un_estado()
    {
        $estado = factory(CotizacionEstado::class)->create();
        $estadoDB = CotizacionEstado::findOrFail($estado->id)->toArray();

        foreach ($this->atributosCotizacionEstado as $atributo) {
            $this->assertArrayHasKey($atributo, $estadoDB);
        }
    }

    public function test_deberia_llenar_los_atributos_fillable_de_estado()
    {
        $entrada = factory(CotizacionEstado::class)->make()->toArray();

        $estado = new CotizacionEstado();
        $estado->fill($entrada);
        $guardado = $estado->save();

        $this->assertTrue($guardado);

        unset($entrada['id']);
        $this->assertArraySubset($entrada, $estado->toArray());
    }

    public function test_deberia_acceder_a_la_relacion_cotizaciones()
    {
        $estado = factory(CotizacionEstado::class)->create();

        $nuevaCotizacion = factory(Cotizacion::class)->make([
            'estado_id' => $estado->id
        ]);

        $estado->cotizaciones()->save($nuevaCotizacion);

        $cotizacion = $estado->cotizaciones()->first();

        $this->assertInstanceOf(Cotizacion::class, $cotizacion);
        $this->assertEquals($cotizacion->estado_id, $estado->id);
    }
}
