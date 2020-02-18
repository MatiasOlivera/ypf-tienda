<?php

namespace Tests\Feature\Http\Controllers;

use App\Producto;
use Tests\ApiTestCase;
use AutorizacionSeeder;
use App\CategoriaProducto;
use CategoriaProductoSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProductoResource;
use Tests\Feature\Utilidades\AuthHelper;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Utilidades\Api\ProductoApi;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Tests\Feature\Utilidades\EstructuraProducto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraJsonHelper;

class ProductosControllerComoEmpleadoTest extends ApiTestCase
{
    use AuthHelper;
    use ProductoApi;
    use EstructuraJsonHelper;
    use EloquenceSolucion;
    use EstructuraProducto;
    use RefreshDatabase;

    protected $usuario;
    protected $cabeceras;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CategoriaProductoSeeder::class);
        $this->seed(AutorizacionSeeder::class);

        $login = $this->loguearseComoEmpleado();
        $this->usuario = $login['usuario'];
        $this->cabeceras = $login['cabeceras'];
    }

    private function getEstructuraProductos(): array
    {
        $paginacion = $this->estructuraPaginacion;
        unset($paginacion['paginacion']['rutas']);
        return array_merge(['productos'], $paginacion);
    }

    private function getImagen()
    {
        return UploadedFile::fake()->image('imagen.jpg', 200, 200)->size(256);
    }

    private function getNombreImagen($producto, UploadedFile $imagen)
    {
        $id = $producto['id'];
        $extension = $imagen->extension();
        return "$id.$extension";
    }

    private function getURLImagen($nombreArchivo)
    {
        return Storage::disk('productos')->url($nombreArchivo);
    }

    /**
     * No debería obtener ningun producto
     */
    public function testElEmpleadoNoDeberiaObtenerNingunProducto()
    {
        $respuesta = $this->obtenerProductos();

        $estructura = $this->getEstructuraProductos();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson(['productos' => []]);
    }

    /**
     * Debería obtener productos
     *
     * @return void
     */
    public function testElEmpleadoDeberiaObtenerProductos()
    {
        factory(CategoriaProducto::class, 10)
            ->create()
            ->each(function ($categoria) {
                $categoria->productos()->save(factory(Producto::class)->make());
            });

        $respuesta = $this->obtenerProductos();

        $estructura = $this->getEstructuraProductos();
        $productos = Producto::orderBy('nombre', 'ASC')->get();
        $coleccionProductos = ProductoResource::collection($productos)->resolve();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson(['productos' => $coleccionProductos]);
    }

    /**
     * Debería crear un producto sin imagen
     */
    public function testElEmpleadoDeberiaCrearUnProductoSinImagen()
    {
        $producto = factory(Producto::class)->make();
        $productoArray = $producto->toArray();
        unset($productoArray['imagen']);

        $this->usuario->givePermissionTo('crear productos');
        $respuesta = $this->crearProducto($productoArray);

        $estructura = $this->getEstructuraProductoComoEmpleado();
        $recursoProducto = ProductoResource::make($producto)->resolve();
        unset(
            $recursoProducto['id'],
            $recursoProducto['created_at'],
            $recursoProducto['updated_at']
        );

        $respuesta
            ->assertStatus(201)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'producto' => $recursoProducto,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'GUARDADO',
                    'descripcion' => "El producto {$producto->nombre} ha sido creado"
                ]
            ]);
    }

    /**
     * Debería crear un producto con imagen
     */
    public function testElEmpleadoDeberiaCrearUnProductoConImagen()
    {
        $producto = factory(Producto::class)->make();
        $productoArray = $producto->toArray();

        Storage::fake('productos');

        $imagen = $this->getImagen();
        $productoArray['imagen'] = $imagen;

        $this->usuario->givePermissionTo('crear productos');
        $respuesta = $this->crearProducto($productoArray);

        $estructura = $this->getEstructuraProductoComoEmpleado();

        $productoGuardado = $respuesta->getData(true)['producto'];

        $productoEsperado = $productoArray;
        $nombreArchivo = $this->getNombreImagen($productoGuardado, $imagen);
        $productoEsperado['imagen'] = $this->getURLImagen($nombreArchivo);

        $respuesta
            ->assertStatus(201)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'producto' => $productoEsperado,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'GUARDADO',
                    'descripcion' => "El producto {$producto->nombre} ha sido creado"
                ]
            ]);

        Storage::disk('productos')->assertExists($nombreArchivo);
    }

    /**
     * Debería obtener un producto
     */
    public function testElEmpleadoDeberiaObtenerUnProducto()
    {
        $producto = factory(Producto::class)->create();

        $respuesta = $this->obtenerProducto($producto->id);

        $estructura = $this->getEstructuraProductoComoEmpleado();
        $recursoProducto = ProductoResource::make($producto)->resolve();

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertExactJson([
                'producto' => $recursoProducto
            ]);
    }

    /**
     * Debería editar un producto sin imagen
     */
    public function testElEmpleadoDeberiaEditarUnProductoSinImagen()
    {
        $producto = factory(Producto::class)->create();
        $productoActualizado = factory(Producto::class)->make();
        $productoArray = $productoActualizado->toArray();
        unset($productoArray['imagen']);

        $this->usuario->givePermissionTo('actualizar productos');
        $respuesta = $this->actualizarProducto($producto->id, $productoArray);

        $estructura = $this->getEstructuraProductoComoEmpleado();

        $productoEsperado = $productoArray;
        unset($productoEsperado['updated_at']);

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'producto' => $productoEsperado,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ACTUALIZADO',
                    'descripcion' => "El producto {$productoEsperado['nombre']} ha sido modificado"
                ]
            ]);
    }

    /**
     * Debería editar un producto con imagen
     */
    public function testElEmpleadoDeberiaEditarUnProductoConImagen()
    {
        $producto = factory(Producto::class)->create();

        $productoArray = $producto->toArray();
        $imagen = $this->getImagen();
        $productoArray['imagen'] = $imagen;

        $this->usuario->givePermissionTo('actualizar productos');
        $respuesta = $this->actualizarProducto($producto->id, $productoArray);

        $estructura = $this->getEstructuraProductoComoEmpleado();

        $productoEsperado = $productoArray;
        $nombreArchivo = $this->getNombreImagen($producto, $imagen);
        $productoEsperado['imagen'] = $this->getURLImagen($nombreArchivo);
        unset($productoEsperado['updated_at']);

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'producto' => $productoEsperado,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ACTUALIZADO',
                    'descripcion' => "El producto {$producto->nombre} ha sido modificado"
                ]
            ]);
    }

    /**
     * Debería eliminar un producto
     */
    public function testElEmpleadoDeberiaEliminarUnProducto()
    {
        $producto = factory(Producto::class)->create();

        $this->usuario->givePermissionTo('eliminar productos');
        $respuesta = $this->eliminarProducto($producto->id);

        $estructura = $this->getEstructuraProductoComoEmpleado();

        $recursoProducto = ProductoResource::make($producto)->resolve();
        unset($recursoProducto['updated_at'], $recursoProducto['deleted_at']);

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'producto' => $recursoProducto,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ELIMINADO',
                    'descripcion' => "El producto {$producto->nombre} ha sido eliminado"
                ]
            ]);
    }

    /**
     * Debería restaurar un producto
     */
    public function testElEmpleadoDeberiaRestaurarUnProducto()
    {
        $producto = factory(Producto::class)->create();

        $this->usuario->givePermissionTo('eliminar productos');

        $this->eliminarProducto($producto->id);
        $respuesta = $this->restaurarProducto($producto->id);

        $estructura = $this->getEstructuraProductoComoEmpleado();

        $recursoProducto = ProductoResource::make($producto)->resolve();
        unset($recursoProducto['updated_at']);

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'producto' => $recursoProducto,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'RESTAURADO',
                    'descripcion' => "El producto {$producto->nombre} ha sido dado de alta"
                ]
            ]);
    }
}
