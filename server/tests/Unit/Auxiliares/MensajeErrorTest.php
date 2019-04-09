<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Auxiliares\MensajeError;

class MensajeErrorTest extends TestCase
{
    public function testDeberiaCrearUnaInstanciaDeMensajeError()
    {
        $mensaje = new MensajeError('Oops, operación fallida!', 'FALLO');
        $actual = $mensaje->getObjeto();

        $esperado = [
            'tipo' => 'error',
            'codigo' => 'FALLO',
            'descripcion' => 'Oops, operación fallida!'
        ];

        $this->assertEquals($esperado, $actual);
    }
}
