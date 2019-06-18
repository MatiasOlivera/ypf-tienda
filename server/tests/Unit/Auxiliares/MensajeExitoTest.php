<?php

namespace Tests\Unit;

use Tests\TestCase;
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

    public function testDeberiaCrearMensajeGuardar()
    {
        $mensaje = new MensajeExito();
        $mensaje->guardar('Elaion F50');
        $actual = $mensaje->getObjeto();

        $esperado = [
            'tipo' => 'exito',
            'codigo' => 'GUARDADO',
            'descripcion' => 'Elaion F50 ha sido creado'
        ];

        $this->assertEquals($esperado, $actual);
    }

    public function testDeberiaCrearMensajeActualizar()
    {
        $mensaje = new MensajeExito();
        $mensaje->actualizar('Elaion F50');
        $actual = $mensaje->getObjeto();

        $esperado = [
            'tipo' => 'exito',
            'codigo' => 'ACTUALIZADO',
            'descripcion' => 'Elaion F50 ha sido modificado'
        ];

        $this->assertEquals($esperado, $actual);
    }

    public function testDeberiaCrearMensajeEliminar()
    {
        $mensaje = new MensajeExito();
        $mensaje->eliminar('Elaion F50');
        $actual = $mensaje->getObjeto();

        $esperado = [
            'tipo' => 'exito',
            'codigo' => 'ELIMINADO',
            'descripcion' => 'Elaion F50 ha sido eliminado'
        ];

        $this->assertEquals($esperado, $actual);
    }

    public function testDeberiaCrearMensajeRestaurar()
    {
        $mensaje = new MensajeExito();
        $mensaje->restaurar('Elaion F50');
        $actual = $mensaje->getObjeto();

        $esperado = [
            'tipo' => 'exito',
            'codigo' => 'RESTAURADO',
            'descripcion' => 'Elaion F50 ha sido dado de alta'
        ];

        $this->assertEquals($esperado, $actual);
    }

}
