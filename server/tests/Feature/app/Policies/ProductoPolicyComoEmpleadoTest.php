<?php

namespace Tests\Feature\app\Policies;

use App\Producto;
use Tests\ApiTestCase;
use AutorizacionSeeder;
use CategoriaProductoSeeder;
use Tests\Feature\Utilidades\AuthHelper;
use Tests\Feature\Utilidades\Api\ProductoApi;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductoPolicyComoEmpleadoTest extends ApiTestCase
{
    use AuthHelper;
    use ProductoApi;
    use RefreshDatabase;
    use EloquenceSolucion;

    protected $usuario;
    protected $cabeceras;
    protected $producto;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(AutorizacionSeeder::class);
        $this->seed(CategoriaProductoSeeder::class);

        $login = $this->loguearseComoEmpleado();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];

        $this->producto = factory(Producto::class)->create();
    }

    /**
     * Ver productos
     */

    public function test_el_empleado_puede_ver_los_productos()
    {
        $respuesta = $this->obtenerProductos();
        $respuesta->assertOk();
    }

    /**
     * Ver producto
     */

    public function test_el_empleado_puede_ver_el_producto()
    {
        $respuesta = $this->obtenerProducto($this->producto->id);
        $respuesta->assertOk();
    }

    /**
     * Crear producto
     */

    public function test_el_empleado_puede_crear_un_producto()
    {
        $this->usuario->givePermissionTo('crear productos');

        $respuesta = $this->crearProducto();
        $respuesta->assertStatus(422);
    }

    public function test_el_empleado_no_puede_crear_un_producto()
    {
        $respuesta = $this->crearProducto();
        $respuesta->assertStatus(403);
    }

    /**
     * Actualizar producto
     */

    public function test_el_empleado_puede_actualizar_un_producto()
    {
        $this->usuario->givePermissionTo('actualizar productos');

        $respuesta = $this->actualizarProducto($this->producto->id);
        $respuesta->assertStatus(422);
    }


    public function test_el_empleado_no_puede_actualizar_un_producto()
    {
        $respuesta = $this->actualizarProducto($this->producto->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Eliminar producto
     */

    public function test_el_empleado_puede_eliminar_un_producto()
    {
        $this->usuario->givePermissionTo('eliminar productos');

        $respuesta = $this->eliminarProducto($this->producto->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_eliminar_un_producto()
    {
        $respuesta = $this->eliminarProducto($this->producto->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Restaurar producto
     */

    public function test_el_empleado_puede_restaurar_un_producto()
    {
        $this->usuario->givePermissionTo('eliminar productos');

        $respuesta = $this->restaurarProducto($this->producto->id);
        $respuesta->assertOk();
    }

    public function test_el_empleado_no_puede_restaurar_un_producto()
    {
        $respuesta = $this->restaurarProducto($this->producto->id);
        $respuesta->assertStatus(403);
    }
}
