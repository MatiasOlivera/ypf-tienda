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
        $mensaje->guardar('El producto Elaion F50');
        $actual = $mensaje->getObjeto();

        $esperado = [
            'tipo' => 'error',
            'codigo' => 'NO_GUARDADO',
            'descripcion' => 'El producto Elaion F50 no ha sido creado debido a un error interno'
        ];

        $this->assertEquals($esperado, $actual);
    }

    public function testDeberiaCrearMensajeActualizar()
    {
        $mensaje = new MensajeError();
        $mensaje->actualizar('El producto Elaion F50');
        $actual = $mensaje->getObjeto();

        $esperado = [
            'tipo' => 'error',
            'codigo' => 'NO_ACTUALIZADO',
            'descripcion' => 'El producto Elaion F50 no ha sido actualizado debido a un error interno'
        ];

        $this->assertEquals($esperado, $actual);
    }

    public function testDeberiaCrearMensajeEliminar()
    {
        $mensaje = new MensajeError();
        $mensaje->eliminar('El producto Elaion F50');
        $actual = $mensaje->getObjeto();

        $esperado = [
            'tipo' => 'error',
            'codigo' => 'NO_ELIMINADO',
            'descripcion' => 'El producto Elaion F50 no ha sido eliminado debido a un error interno'
        ];

        $this->assertEquals($esperado, $actual);
    }

    public function testDeberiaCrearMensajeRestaurar()
    {
        $mensaje = new MensajeError();
        $mensaje->restaurar('El producto Elaion F50');
        $actual = $mensaje->getObjeto();

        $esperado = [
            'tipo' => 'error',
            'codigo' => 'NO_RESTAURADO',
            'descripcion' => 'El producto Elaion F50 no ha sido dado de alta debido a un error interno'
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
