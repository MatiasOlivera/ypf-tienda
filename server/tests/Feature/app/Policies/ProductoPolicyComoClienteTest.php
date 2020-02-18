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
use Tests\Feature\Utilidades\Api\ProductoFavoritoApi;

class ProductoPolicyComoClienteTest extends ApiTestCase
{
    use AuthHelper;
    use ProductoApi;
    use RefreshDatabase;
    use EloquenceSolucion;
    use ProductoFavoritoApi;

    protected $usuario;
    protected $cabeceras;
    protected $producto;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(AutorizacionSeeder::class);
        $this->seed(CategoriaProductoSeeder::class);

        $login = $this->loguearseComoCliente();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];

        $this->producto = factory(Producto::class)->create();
    }

    /**
     * Ver productos
     */

    public function test_el_cliente_usuario_puede_ver_los_productos()
    {
        $respuesta = $this->obtenerProductos();
        $respuesta->assertOk();
    }

    /**
     * Ver producto
     */

    public function test_el_cliente_usuario_puede_ver_el_producto()
    {
        $respuesta = $this->obtenerProducto($this->producto->id);
        $respuesta->assertOk();
    }

    /**
     * Crear producto
     */

    public function test_el_cliente_usuario_no_puede_crear_un_producto()
    {
        $respuesta = $this->crearProducto();
        $respuesta->assertStatus(403);
    }

    /**
     * Actualizar producto
     */

    public function test_el_cliente_usuario_no_puede_actualizar_un_producto()
    {
        $respuesta = $this->actualizarProducto($this->producto->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Eliminar producto
     */

    public function test_el_cliente_usuario_no_puede_eliminar_un_producto()
    {
        $respuesta = $this->eliminarProducto($this->producto->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Restaurar producto
     */

    public function test_el_cliente_usuario_no_puede_restaurar_un_producto()
    {
        $respuesta = $this->restaurarProducto($this->producto->id);
        $respuesta->assertStatus(403);
    }

    /**
     * Agregar producto como favorito
     */

    public function test_el_cliente_usuario_puede_agregar_el_producto_como_favorito()
    {
        $respuesta = $this->agregarFavorito($this->producto->id);
        $respuesta->assertOk();
    }

    /**
     * Eliminar producto de favoritos
     */

    public function test_el_cliente_usuario_puede_eliminar_el_producto_de_favoritos()
    {
        $this->agregarFavorito($this->producto->id);
        $respuesta = $this->eliminarFavorito($this->producto->id);
        $respuesta->assertOk();
    }
}
