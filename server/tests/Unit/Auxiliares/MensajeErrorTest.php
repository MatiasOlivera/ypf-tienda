<?php

namespace Tests\Unit;

use Tests\TestCase;
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

    public function testDeberiaCrearMensajeGuardar()
    {
        $mensaje = new MensajeError();
        $mensaje->guardar('el producto Elaion F50');
        $actual = $mensaje->getObjeto();

        $esperado = [
            'tipo' => 'error',
            'codigo' => 'NO_GUARDADO',
            'descripcion' => 'Hubo un error al intentar guardar el producto Elaion F50'
        ];

        $this->assertEquals($esperado, $actual);
    }

    public function testDeberiaCrearMensajeActualizar()
    {
        $mensaje = new MensajeError();
        $mensaje->actualizar('el producto Elaion F50');
        $actual = $mensaje->getObjeto();

        $esperado = [
            'tipo' => 'error',
            'codigo' => 'NO_ACTUALIZADO',
            'descripcion' => 'Hubo un error al intentar modificar el producto Elaion F50'
        ];

        $this->assertEquals($esperado, $actual);
    }

    public function testDeberiaCrearMensajeEliminar()
    {
        $mensaje = new MensajeError();
        $mensaje->eliminar('el producto Elaion F50');
        $actual = $mensaje->getObjeto();

        $esperado = [
            'tipo' => 'error',
            'codigo' => 'NO_ELIMINADO',
            'descripcion' => 'Hubo un error al intentar eliminar el producto Elaion F50'
        ];

        $this->assertEquals($esperado, $actual);
    }

    public function testDeberiaCrearMensajeRestaurar()
    {
        $mensaje = new MensajeError();
        $mensaje->restaurar('al producto Elaion F50');
        $actual = $mensaje->getObjeto();

        $esperado = [
            'tipo' => 'error',
            'codigo' => 'NO_RESTAURADO',
            'descripcion' => 'Hubo un error al intentar dar de alta al producto Elaion F50'
        ];

        $this->assertEquals($esperado, $actual);
    }

    public function testDeberiaCrearMensajeObtener()
    {
        $mensaje = new MensajeError();
        $mensaje->obtener('del producto Elaion F50');
        $actual = $mensaje->getObjeto();

        $esperado = [
            'tipo' => 'error',
            'codigo' => 'NO_OBTENIDO',
            'descripcion' => 'Hubo un error al consultar los datos del producto Elaion F50'
        ];

        $this->assertEquals($esperado, $actual);
    }

    public function testDeberiaCrearMensajeObtenerTodos()
    {
        $mensaje = new MensajeError();
        $mensaje->obtenerTodos('productos');
        $actual = $mensaje->getObjeto();

        $esperado = [
            'tipo' => 'error',
            'codigo' => 'NO_OBTENIDOS',
            'descripcion' => 'Hubo un error al consultar el listado de productos'
        ];

        $this->assertEquals($esperado, $actual);
    }

    public function testDeberiaCrearMensajeRelacion()
    {
        $mensaje = new MensajeError();
        $mensaje->relacion('los productos del pedido');
        $actual = $mensaje->getObjeto();

        $esperado = [
            'tipo' => 'error',
            'codigo' => 'NO_OBTENIDO',
            'descripcion' => 'Hubo un error al consultar los productos del pedido'
        ];

        $this->assertEquals($esperado, $actual);
    }
}
