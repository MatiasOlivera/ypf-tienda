<?php

namespace Tests\Feature\app\Policies;

use Tests\ApiTestCase;
use AutorizacionSeeder;
use App\CategoriaProducto;
use Tests\Feature\Utilidades\AuthHelper;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\Api\CategoriaProductoApi;

class CategoriaProductoPolicyComoEmpleadoTest extends ApiTestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EloquenceSolucion;
    use CategoriaProductoApi;

    protected $usuario;
    protected $cabeceras;
    protected $categoriaProducto;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(AutorizacionSeeder::class);

        $login = $this->loguearseComoEmpleado();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];

        $this->categoriaProducto = factory(CategoriaProducto::class)->create();
    }

    /**
     * Crear categoria
     */

    public function test_el_empleado_puede_crear_una_categoria()
    {
        $this->usuario->givePermissionTo('crear categorias de producto');

        $respuesta = $this->crearCategoria();
        $respuesta->assertStatus(422);
    }

    public function test_el_empleado_no_puede_crear_una_categoria()
    {
        $respuesta = $this->crearCategoria();
        $respuesta->assertStatus(403);
    }

    /**
     * Actualizar categoria
     */

    public function test_el_empleado_puede_actualizar_una_categoria()
    {
        $this->usuario->givePermissionTo('actualizar categorias de producto');

        $respuesta = $this->actualizarCategoria($this->categoriaProducto->id);
        $respuesta->assertStatus(422);
    }

    public function test_el_empleado_no_puede_actualizar_una_categoria()
    {
        $respuesta = $this->actualizarCategoria($this->categoriaProducto->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Eliminar categoria
     */

    public function test_el_empleado_puede_eliminar_una_categoria()
    {
        $this->usuario->givePermissionTo('eliminar categorias de producto');

        $respuesta = $this->eliminarCategoria($this->categoriaProducto->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_eliminar_una_categoria()
    {
        $respuesta = $this->eliminarCategoria($this->categoriaProducto->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Restaurar categoria
     */

    public function test_el_empleado_puede_restaurar_una_categoria()
    {
        $this->usuario->givePermissionTo('eliminar categorias de producto');

        $respuesta = $this->restaurarCategoria($this->categoriaProducto->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_restaurar_una_categoria()
    {
        $respuesta = $this->restaurarCategoria($this->categoriaProducto->id);
        $respuesta->assertStatus(403);
    }
}
