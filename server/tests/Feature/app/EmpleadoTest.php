<?php

namespace Tests\Feature\app;

use App\Recurso;
use App\Empleado;
use App\Cotizacion;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Tests\Feature\Utilidades\EstructuraEmpleado;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmpleadoTest extends TestCase
{
    use RefreshDatabase;
    use EloquenceSolucion;
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

    public function test_deberia_acceder_a_la_relacion_cotizaciones()
    {
        $empleado = factory(Empleado::class)->create();

        $nuevaCotizacion = factory(Cotizacion::class)->make([
            'empleado_id' => $empleado->id
        ]);

        $empleado->cotizaciones()->save($nuevaCotizacion);

        $cotizacion = $empleado->cotizaciones()->first();

        $this->assertInstanceOf(Cotizacion::class, $cotizacion);
        $this->assertEquals($cotizacion->empleado_id, $empleado->id);
    }

    public function test_deberia_acceder_a_la_relacion_permisos()
    {
        $empleado = factory(Empleado::class)->states('permisos')->create();
        $recurso = $empleado->permisos()->first();

        $this->assertInstanceOf(Recurso::class, $recurso);
        $this->assertEquals($empleado->id, $recurso->permiso->ID_ven);
    }
}
