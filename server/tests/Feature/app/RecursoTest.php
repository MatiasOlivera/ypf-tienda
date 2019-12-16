<?php

namespace Tests\Feature\app;

use App\Recurso;
use App\Empleado;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Tests\Feature\Utilidades\EstructuraRecurso;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecursoTest extends TestCase
{
    use RefreshDatabase;
    use EloquenceSolucion;
    use EstructuraRecurso;

    public function test_deberia_crear_un_recurso()
    {
        $recurso = factory(Recurso::class)->create();
        $recursoDB = Recurso::findOrFail($recurso->id)->toArray();

        foreach ($this->atributosRecurso as $atributo) {
            $this->assertArrayHasKey($atributo, $recursoDB);
        }
    }

    public function test_deberia_llenar_los_atributos_fillable_de_recurso()
    {
        $entrada = factory(Recurso::class)->make()->toArray();

        $recurso = new Recurso();
        $recurso->fill($entrada);
        $guardado = $recurso->save();

        $this->assertTrue($guardado);

        unset($entrada['id']);
        $this->assertArraySubset($entrada, $recurso->toArray());
    }

    public function test_deberia_acceder_a_la_relacion_permisos()
    {
        $recurso = factory(Recurso::class)->states('permisos')->create();
        $empleado = $recurso->permisos()->first();

        $this->assertInstanceOf(Empleado::class, $empleado);
        $this->assertEquals($recurso->id, $empleado->permiso->ID_recurso);
    }
}
