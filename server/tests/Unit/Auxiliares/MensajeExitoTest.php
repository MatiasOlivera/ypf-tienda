<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Auxiliares\MensajeExito;

class MensajeExitoTest extends TestCase
{
    public function testDeberiaCrearUnaInstanciaDeMensajeExito()
    {
        $mensaje = new MensajeExito('Operación completada!', 'COMPLETADO');
        $actual = $mensaje->getObjeto();

        $esperado = [
            'tipo' => 'exito',
            'codigo' => 'COMPLETADO',
            'descripcion' => 'Operación completada!'
        ];

        $this->assertEquals($esperado, $actual);
    }
}
