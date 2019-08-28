<?php

namespace Tests\Feature\app;

use App\Empleado;
use Tests\TestCase;
use App\EmpleadoCargo;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Utilidades\EstructuraEmpleado;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmpleadoTest extends TestCase
{
    use RefreshDatabase;
    use EstructuraEmpleado;

    public function test_deberia_crear_un_empleado()
    {
        $empleado = factory(Empleado::class)->create();
        $empleadoDB = Empleado::findOrFail($empleado->id)->toArray();

        foreach ($this->atributosEmpleado as $atributo) {
            $this->assertArrayHasKey($atributo, $empleadoDB);
        }
    }

    public function test_deberia_llenar_los_atributos_fillable_de_empleado()
    {
        $entrada = factory(Empleado::class)->make()->toArray();

        $empleado = new Empleado();
        $empleado->fill($entrada);
        $guardado = $empleado->save();

        $this->assertTrue($guardado);

        unset($entrada['id']);
        $this->assertArraySubset($entrada, $empleado->toArray());
    }

    public function test_deberia_acceder_a_la_relacion_cargo()
    {
        $empleado = factory(Empleado::class)->create();
        $cargo = $empleado->cargo;

        $this->assertInstanceOf(EmpleadoCargo::class, $cargo);
        $this->assertEquals($empleado->cargo_id, $cargo->id);
    }
}
