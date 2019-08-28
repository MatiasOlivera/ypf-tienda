<?php

namespace Tests\Feature\app;

use Tests\TestCase;
use App\EmpleadoCargo;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraEmpleadoCargo;

class EmpleadoCargoTest extends TestCase
{
    use RefreshDatabase;
    use EstructuraEmpleadoCargo;

    public function test_deberia_crear_un_cargo()
    {
        $cargo = factory(EmpleadoCargo::class)->create();
        $cargoDB = EmpleadoCargo::findOrFail($cargo->id)->toArray();

        foreach ($this->atributosEmpleadoCargo as $atributo) {
            $this->assertArrayHasKey($atributo, $cargoDB);
        }
    }

    public function test_deberia_llenar_los_atributos_fillable_de_empleado()
    {
        $entrada = factory(EmpleadoCargo::class)->make()->toArray();

        $cargo = new EmpleadoCargo();
        $cargo->fill($entrada);
        $guardado = $cargo->save();

        $this->assertTrue($guardado);

        unset($entrada['id']);
        $this->assertArraySubset($entrada, $cargo->toArray());
    }
}
