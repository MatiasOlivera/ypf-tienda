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

    public function testDeberiaCrearMensajeGuardarConGeneroMasculino()
    {
        $mensaje = new MensajeExito();
        $mensaje->guardar('El producto Elaion F50', 'masculino');
        $actual = $mensaje->getObjeto();

        $esperado = [
            'tipo' => 'exito',
            'codigo' => 'GUARDADO',
            'descripcion' => 'El producto Elaion F50 ha sido creado'
        ];

        $this->assertEquals($esperado, $actual);
    }

    public function testDeberiaCrearMensajeGuardarConGeneroFemenino()
    {
        $mensaje = new MensajeExito();
        $mensaje->guardar('La cotización', 'femenino');
        $actual = $mensaje->getObjeto();

        $esperado = [
            'tipo' => 'exito',
            'codigo' => 'GUARDADO',
            'descripcion' => 'La cotización ha sido creada'
        ];

        $this->assertEquals($esperado, $actual);
    }

    public function testDeberiaCrearMensajeActualizarConGeneroMasculino()
    {
        $mensaje = new MensajeExito();
        $mensaje->actualizar('El producto Elaion F50', 'masculino');
        $actual = $mensaje->getObjeto();

        $esperado = [
            'tipo' => 'exito',
            'codigo' => 'ACTUALIZADO',
            'descripcion' => 'El producto Elaion F50 ha sido modificado'
        ];

        $this->assertEquals($esperado, $actual);
    }

    public function testDeberiaCrearMensajeActualizarConGeneroFemenino()
    {
        $mensaje = new MensajeExito();
        $mensaje->actualizar('La cotización', 'femenino');
        $actual = $mensaje->getObjeto();

        $esperado = [
            'tipo' => 'exito',
            'codigo' => 'ACTUALIZADO',
            'descripcion' => 'La cotización ha sido modificada'
        ];

        $this->assertEquals($esperado, $actual);
    }

    public function testDeberiaCrearMensajeEliminarConGeneroMasculino()
    {
        $mensaje = new MensajeExito();
        $mensaje->eliminar('El producto Elaion F50', 'masculino');
        $actual = $mensaje->getObjeto();

        $esperado = [
            'tipo' => 'exito',
            'codigo' => 'ELIMINADO',
            'descripcion' => 'El producto Elaion F50 ha sido eliminado'
        ];

        $this->assertEquals($esperado, $actual);
    }

    public function testDeberiaCrearMensajeEliminarConGeneroFemenino()
    {
        $mensaje = new MensajeExito();
        $mensaje->eliminar('La cotización', 'femenino');
        $actual = $mensaje->getObjeto();

        $esperado = [
            'tipo' => 'exito',
            'codigo' => 'ELIMINADO',
            'descripcion' => 'La cotización ha sido eliminada'
        ];

        $this->assertEquals($esperado, $actual);
    }

    public function testDeberiaCrearMensajeRestaurarConGeneroMasculino()
    {
        $mensaje = new MensajeExito();
        $mensaje->restaurar('El producto Elaion F50', 'masculino');
        $actual = $mensaje->getObjeto();

        $esperado = [
            'tipo' => 'exito',
            'codigo' => 'RESTAURADO',
            'descripcion' => 'El producto Elaion F50 ha sido dado de alta'
        ];

        $this->assertEquals($esperado, $actual);
    }


    public function testDeberiaCrearMensajeRestaurarConGeneroFemenino()
    {
        $mensaje = new MensajeExito();
        $mensaje->restaurar('La cotización', 'femenino');
        $actual = $mensaje->getObjeto();

        $esperado = [
            'tipo' => 'exito',
            'codigo' => 'RESTAURADO',
            'descripcion' => 'La cotización ha sido dada de alta'
        ];

        $this->assertEquals($esperado, $actual);
    }
}
