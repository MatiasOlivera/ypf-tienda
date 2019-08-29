<?php

namespace Tests\Feature\app;

use Tests\TestCase;
use App\PedidoEstado;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraPedidoEstado;

class PedidoEstadoTest extends TestCase
{
    use RefreshDatabase;
    use EstructuraPedidoEstado;

    public function test_deberia_crear_un_estado()
    {
        $estado = factory(PedidoEstado::class)->create();
        $estadoDB = PedidoEstado::findOrFail($estado->id)->toArray();

        foreach ($this->atributosPedidoEstado as $atributo) {
            $this->assertArrayHasKey($atributo, $estadoDB);
        }
    }

    public function test_deberia_llenar_los_atributos_fillable_de_estado()
    {
        $entrada = factory(PedidoEstado::class)->make()->toArray();

        $estado = new PedidoEstado();
        $estado->fill($entrada);
        $guardado = $estado->save();

        $this->assertTrue($guardado);

        unset($entrada['id']);
        $this->assertArraySubset($entrada, $estado->toArray());
    }
}
