<?php

namespace Tests\Feature;

use App\User;
use Exception;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\BaseController;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BaseControllerTest extends TestCase
{
    use RefreshDatabase;

    private function crearUnaNuevaInstancia()
    {
        $parametros = [
            'modelo' => 'User',
            'inputs' => [
                'name' => 'John',
                'email' => 'John@email.com',
                'password' => Hash::make(12345678)
            ]
        ];

        $nombres = [
            'exito' => 'El usuario John',
            'error' => 'al usuario John'
        ];

        $controller = new BaseController('usuario', 'usuarios');
        return $controller->store($parametros, $nombres);
    }

    /**
     * Debería obtener un listado de instancias
     *
     * @return void
     */
    public function testDeberiaObtenerUnListadoDeInstancias()
    {
        $respuestaCreado = $this->crearUnaNuevaInstancia();
        $this->assertEquals(201, $respuestaCreado->status());

        $parametros = [
            'modelo' => 'User',
            'buscar' => null,
            'campos' => null,
            'relaciones' => null,
            'eliminados' => null,
            'paginado' => [
                'porPagina' => 10,
                'ordenarPor' => null,
                'orden' => null
            ]
        ];

        $mensaje = 'los usuarios';

        $controller = new BaseController('usuario', 'usuarios');
        $respuestaIndex = $controller->index($parametros, $mensaje);

        $status = $respuestaIndex->status();
        $datos = $respuestaIndex->getData(true);
        $esperado = User::all()->toArray();

        $this->assertEquals(200, $status);

        $this->assertArrayHasKey('usuarios', $datos);
        $this->assertEquals($esperado, $datos['usuarios']);

        $this->assertArrayHasKey('paginacion', $datos);

        $claves = [
            'total',
            'porPagina',
            'paginaActual',
            'ultimaPagina',
            'rutas',
            'desde',
            'hasta'
        ];

        foreach ($claves as $clave) {
            $this->assertArrayHasKey($clave, $datos['paginacion']);
        }

        $clavesRutas = [
            'primeraPagina',
            'ultimaPagina',
            'siguientePagina',
            'paginaAnterior',
            'base'
        ];

        foreach ($clavesRutas as $clave) {
            $this->assertArrayHasKey($clave, $datos['paginacion']['rutas']);
        }
    }

    /**
     * Index debería devolver un mensaje de error si hay un problema
     *
     * @return void
     */
    public function testIndexDeberiaDevolverMensajeError()
    {
        $controller = new BaseController('usuario', 'usuarios');
        $parametros = ['modelo' => 'NoExiste'];
        $nombre = 'los usuarios';
        $respuestaIndex = $controller->index($parametros, $nombre);

        $status = $respuestaIndex->status();
        $datos = $respuestaIndex->getData(true);

        $this->assertEquals(500, $status);

        $mensaje = [
            'tipo' => 'error',
            'codigo' => 'NO_OBTENIDOS',
            'descripcion' => 'Hubo un error al consultar el listado de los usuarios'
        ];

        $this->assertArrayHasKey('mensaje', $datos);
        $this->assertEquals($mensaje, $datos['mensaje']);
    }

    /**
     * Debería almacenar una nueva instancia
     *
     * @return void
     */
    public function testDeberiaAlmacenarUnaNuevaInstancia()
    {
        $respuesta = $this->crearUnaNuevaInstancia();
        $datos = $respuesta->getData(true);

        $this->assertEquals(201, $respuesta->status());

        $this->assertArrayHasKey('usuario', $datos);
        $this->assertEquals('John@email.com', $datos['usuario']['email']);

        $mensaje = [
            'tipo' => 'exito',
            'codigo' => 'GUARDADO',
            'descripcion' => 'El usuario John ha sido creado'
        ];

        $this->assertArrayHasKey('mensaje', $datos);
        $this->assertEquals($mensaje, $datos['mensaje']);
    }

    /**
     * Debería fallar cuando intente guardar una instancia repetida
     *
     * @return void
     */
    public function testDeberiaFallarCuandoIntenteGuardarUnaInstanciaRepetida()
    {
        $original = $this->crearUnaNuevaInstancia();
        $repetida = $this->crearUnaNuevaInstancia();

        $status = $repetida->status();
        $datos = $repetida->getData(true);

        $this->assertEquals(500, $status);

        $mensaje = [
            'tipo' => 'error',
            'codigo' => 'NO_GUARDADO',
            'descripcion' => 'Hubo un error al intentar guardar al usuario John'
        ];

        $this->assertArrayHasKey('mensaje', $datos);
        $this->assertEquals($mensaje, $datos['mensaje']);
    }

    /**
     * Store debería devolver un mensaje de error si hay un problema
     *
     * @return void
     */
    public function testStoreDeberiaDevolverMensajeError()
    {
        $controller = new BaseController('usuario', 'usuarios');
        $parametros = ['modelo' => 'NoExiste'];
        $nombres = ['exito' => 'El usuario', 'error' => 'al usuario'];
        $respuesta = $controller->store($parametros, $nombres);

        $status = $respuesta->status();
        $datos = $respuesta->getData(true);

        $this->assertEquals(500, $status);

        $mensaje = [
            'tipo' => 'error',
            'codigo' => 'NO_GUARDADO',
            'descripcion' => 'Hubo un error al intentar guardar al usuario'
        ];

        $this->assertArrayHasKey('mensaje', $datos);
        $this->assertEquals($mensaje, $datos['mensaje']);
    }

    /**
     * Debería obtener una instancia
     *
     * @return void
     */
    public function testDeberiaObtenerUnaInstancia()
    {
        $respuestaCreado = $this->crearUnaNuevaInstancia();
        $this->assertEquals(201, $respuestaCreado->status());

        $instancia = $respuestaCreado->getData(true)['usuario'];

        $controller = new BaseController('usuario', 'usuarios');
        $respuestaIndex = $controller->show($instancia);

        $status = $respuestaIndex->status();
        $datos = $respuestaIndex->getData(true);

        $this->assertEquals(200, $status);

        $this->assertArrayHasKey('usuario', $datos);
        $this->assertEquals($instancia, $datos['usuario']);
    }

    /**
     * Debería actualizar una instancia existente
     *
     * @return void
     */
    public function testDeberiaActualizarUnaInstanciaExistente()
    {
        $respuestaCreado = $this->crearUnaNuevaInstancia();
        $id = $respuestaCreado->getData(true)['usuario']['id'];

        $parametros = [
            'instancia' => User::find($id),
            'inputs' => [
                'name' => 'John Doe',
                'email' => 'JohnDoe@email.com'
            ]
        ];

        $nombres =  [
            'exito' => 'John',
            'error' => 'a John'
        ];

        $controller = new BaseController('usuario', 'usuarios');
        $respuestaActualizado = $controller->update($parametros, $nombres);

        $status = $respuestaActualizado->status();
        $datos = $respuestaActualizado->getData(true);

        $this->assertEquals(200, $status);

        $this->assertArrayHasKey('usuario', $datos);
        $this->assertEquals('JohnDoe@email.com', $datos['usuario']['email']);

        $mensaje = [
            'tipo' => 'exito',
            'codigo' => 'ACTUALIZADO',
            'descripcion' => 'John ha sido modificado'
        ];

        $this->assertArrayHasKey('mensaje', $datos);
        $this->assertEquals($mensaje, $datos['mensaje']);
    }

    /**
     * Update debería devolver un mensaje de error si hay un problema
     *
     * @return void
     */
    public function testUpdateDeberiaDevolverMensajeError()
    {
        $controller = new BaseController('usuario', 'usuarios');
        $parametros = ['instancia' => 'NoExiste'];
        $nombres = ['exito' => 'El usuario', 'error' => 'al usuario'];
        $respuesta = $controller->update($parametros, $nombres);

        $status = $respuesta->status();
        $datos = $respuesta->getData(true);

        $this->assertEquals(500, $status);

        $mensaje = [
            'tipo' => 'error',
            'codigo' => 'NO_ACTUALIZADO',
            'descripcion' => 'Hubo un error al intentar modificar al usuario'
        ];

        $this->assertArrayHasKey('mensaje', $datos);
        $this->assertEquals($mensaje, $datos['mensaje']);
    }

    /**
     * Debería eliminar una instancia
     *
     * @return void
     */
    public function testDeberiaEliminarUnaInstancia()
    {
        $respuestaCreado = $this->crearUnaNuevaInstancia();
        $this->assertEquals(201, $respuestaCreado->status());
        $id = $respuestaCreado->getData(true)['usuario']['id'];

        $instancia = User::find($id);

        $nombres = [
            'exito' => 'John',
            'error' => 'a John'
        ];

        $controller = new BaseController('usuario', 'usuarios');
        $respuestaMostrar = $controller->destroy($instancia, $nombres);

        $status = $respuestaMostrar->status();
        $datos = $respuestaMostrar->getData(true);

        $this->assertEquals(200, $status);

        $this->assertArrayHasKey('usuario', $datos);
        $this->assertNotNull($datos['usuario']['deleted_at']);

        $mensaje = [
            'tipo' => 'exito',
            'codigo' => 'ELIMINADO',
            'descripcion' => 'John ha sido eliminado'
        ];

        $this->assertArrayHasKey('mensaje', $datos);
        $this->assertEquals($mensaje, $datos['mensaje']);
    }

    /**
     * Destroy debería devolver un mensaje de error si hay un problema
     *
     * @return void
     */
    public function testDestroyDeberiaDevolverMensajeError()
    {
        $controller = new BaseController('usuario', 'usuarios');
        $parametros = ['instancia' => 'NoExiste'];
        $nombres = ['exito' => 'El usuario', 'error' => 'al usuario'];
        $respuesta = $controller->destroy($parametros, $nombres);

        $status = $respuesta->status();
        $datos = $respuesta->getData(true);

        $this->assertEquals(500, $status);

        $mensaje = [
            'tipo' => 'error',
            'codigo' => 'NO_ELIMINADO',
            'descripcion' => 'Hubo un error al intentar eliminar al usuario'
        ];

        $this->assertArrayHasKey('mensaje', $datos);
        $this->assertEquals($mensaje, $datos['mensaje']);
    }

    /**
     * Debería restaurar una instancia eliminada
     *
     * @return void
     */
    public function testDeberiaRestaurarUnaInstanciaEliminada()
    {
        $respuestaCreado = $this->crearUnaNuevaInstancia();
        $this->assertEquals(201, $respuestaCreado->status());
        $id = $respuestaCreado->getData(true)['usuario']['id'];

        $instancia = User::find($id);

        $nombres = [
            'exito' => 'John',
            'error' => 'a John'
        ];

        $controller = new BaseController('usuario', 'usuarios');
        $respuestaMostrar = $controller->restore($instancia, $nombres);

        $status = $respuestaMostrar->status();
        $datos = $respuestaMostrar->getData(true);

        $this->assertEquals(200, $status);

        $this->assertArrayHasKey('usuario', $datos);
        $this->assertNull($datos['usuario']['deleted_at']);

        $mensaje = [
            'tipo' => 'exito',
            'codigo' => 'RESTAURADO',
            'descripcion' => 'John ha sido dado de alta'
        ];

        $this->assertArrayHasKey('mensaje', $datos);
        $this->assertEquals($mensaje, $datos['mensaje']);
    }

    /**
     * Restore debería devolver un mensaje de error si hay un problema
     *
     * @return void
     */
    public function testRestoreDeberiaDevolverMensajeError()
    {
        $controller = new BaseController('usuario', 'usuarios');
        $parametros = ['instancia' => 'NoExiste'];
        $nombres = ['exito' => 'El usuario', 'error' => 'al usuario'];
        $respuesta = $controller->restore($parametros, $nombres);

        $status = $respuesta->status();
        $datos = $respuesta->getData(true);

        $this->assertEquals(500, $status);

        $mensaje = [
            'tipo' => 'error',
            'codigo' => 'NO_RESTAURADO',
            'descripcion' => 'Hubo un error al intentar dar de alta al usuario'
        ];

        $this->assertArrayHasKey('mensaje', $datos);
        $this->assertEquals($mensaje, $datos['mensaje']);
    }

    public function testDeberiaUtilizarElMismoNombreEnAmbosMensajes()
    {
        $parametros = [
            'modelo' => 'User',
            'inputs' => [
                'name' => 'John',
                'email' => 'John@email.com',
                'password' => Hash::make(12345678)
            ]
        ];

        $nombre = 'El usuario Juan';

        $controller = new BaseController('usuario', 'usuarios');
        $respuesta = $controller->store($parametros, $nombre);
        $datos = $respuesta->getData(true);

        $mensaje = [
            'tipo' => 'exito',
            'codigo' => 'GUARDADO',
            'descripcion' => 'El usuario Juan ha sido creado'
        ];

        $this->assertArrayHasKey('mensaje', $datos);
        $this->assertEquals($mensaje, $datos['mensaje']);
    }

    public function testDeberiaUtilizarNombresDistintosEnCadaMensaje()
    {
        $parametros = [
            'modelo' => 'User',
            'inputs' => [
                'name' => 'John',
                'email' => 'John@email.com',
                'password' => Hash::make(12345678)
            ]
        ];

        $nombres = [
            'exito' => 'El usuario Juan',
            'error' => 'al usuario Juan'
        ];

        $controller = new BaseController('usuario', 'usuarios');
        $respuesta = $controller->store($parametros, $nombres);
        $datos = $respuesta->getData(true);

        $mensaje = [
            'tipo' => 'exito',
            'codigo' => 'GUARDADO',
            'descripcion' => 'El usuario Juan ha sido creado'
        ];

        $this->assertArrayHasKey('mensaje', $datos);
        $this->assertEquals($mensaje, $datos['mensaje']);
    }

    public function testDeberiaLanzarUnaExcepcionCuandoNoExistaLaClaveExito()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("El argumento nombre debe tener la clave exito");

        $parametros = [];
        $nombres = ['error' => 'al usuario Juan'];

        $controller = new BaseController('usuario', 'usuarios');
        $controller->store($parametros, $nombres);
    }

    public function testDeberiaLanzarUnaExcepcionCuandoNoExistaLaClaveError()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("El argumento nombre debe tener la clave error");

        $parametros = [];
        $nombres = ['exito' => 'El usuario Juan'];

        $controller = new BaseController('usuario', 'usuarios');
        $controller->store($parametros, $nombres);
    }

    public function testDeberiaLanzarUnaExcepcionCuandoNombreNoEsUnTipoValido()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("El argumento nombre debe ser string o array");

        $parametros = [];
        $nombre = 123;

        $controller = new BaseController('usuario', 'usuarios');
        $controller->store($parametros, $nombre);
    }
}
