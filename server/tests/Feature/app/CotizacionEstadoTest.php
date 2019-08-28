<?php

namespace Tests\Feature\app;

use Tests\TestCase;
use App\CotizacionEstado;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraCotizacionEstado;

class CotizacionEstadoTest extends TestCase
{
    use RefreshDatabase;
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
}
