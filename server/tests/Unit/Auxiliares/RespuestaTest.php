<?php

namespace Tests\Unit;

use App\Auxiliares\{Respuesta, MensajeExito, MensajeError};
use Tests\TestCase;
use Exception;

class RespuestaTest extends TestCase
{
    /**
     * Debería devolver una respuesta correcta
     **/
    public function testDevolverRespuestaCorrecta()
    {
        $datos = ['personaje' => 'Groot'];
        $mensaje = new MensajeExito('Operación completada!');
        $actual = Respuesta::exito($datos, $mensaje, 200);

        $esperado = [
            'personaje' => 'Groot',
            'mensaje' => [
                'tipo' => 'exito',
                'codigo' => null,
                'descripcion' => 'Operación completada!'
            ]
        ];

        $this->assertEquals(200, $actual->getStatusCode());
        $this->assertEquals($esperado, $actual->original);
    }

    /**
     * Debería lanzar una excepción si el código de estado es el de una respuesta con errores
     */
    public function testLanzarExcepcionEstadoIncorrectoMetodoExito()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            "El código de estado no se encuentra dentro del rango de respuestas correctas"
        );

        $mensaje = new MensajeExito('Operación completada!');
        Respuesta::exito([], $mensaje, 400);
    }

     /**
     * Debería devolver una respuesta con datos pero sin mensaje
     **/
    public function testDevolverRespuestaSinMensaje()
    {
        $datos = ['personaje' => 'Groot'];
        $actual = Respuesta::exito($datos, null, 200);

        $esperado = ['personaje' => 'Groot'];

        $this->assertEquals($esperado, $actual->original);
    }

    /**
     * Debería devolver una respuesta con el mensaje de error
     **/
    public function testDevolverRespuestaError()
    {
        $mensaje = new MensajeError('Oops, operación fallida!', 'FALLO');
        $actual = Respuesta::error($mensaje, 500);

        $esperado = [
            'mensaje' => [
                'tipo' => 'error',
                'codigo' => 'FALLO',
                'descripcion' => 'Oops, operación fallida!'
            ]
        ];

        $this->assertEquals(500, $actual->getStatusCode());
        $this->assertEquals($esperado, $actual->original);
    }

    /**
     * Debería lanzar una excepción si el código de estado es el de una respuesta exitosa
     */
    public function testLanzarExcepcionEstadoIncorrectoMetodoError()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            "El código de estado no se encuentra dentro del rango de respuestas con errores"
        );

        $mensaje = new MensajeError('Oops, operación fallida!', 'FALLO');
        Respuesta::error($mensaje, 200);
    }
}
