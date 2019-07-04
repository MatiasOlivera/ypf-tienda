<?php

namespace Tests\Feature\Http\Controllers;

use App\Producto;
use Tests\TestCase;
use App\CategoriaProducto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Utilidades\AuthHelper;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraJsonHelper;

class ProductosControllerTest extends TestCase
{
    use AuthHelper;
    use EstructuraJsonHelper;
    use RefreshDatabase;

    private $estructuraProducto = [
        'producto' => [
            'id',
            'codigo',
            'nombre',
            'presentacion',
            'precio_por_mayor',
            'consumidor_final',
            'imagen',
            'id_categoria',
            'created_at',
            'updated_at',
            'deleted_at'
        ]
    ];

    private function getEstructuraProductos(): array
    {
        return array_merge(['productos'], $this->estructuraPaginacion);
    }

    private function getEstructuraProducto(): array
    {
        return array_merge($this->estructuraProducto, $this->estructuraMensaje);
    }

    private function crearProducto($cabeceras)
    {
        $categoria = ['descripcion' => 'Automotriz Alta Gama'];
        $respuestaCategoria = $this
            ->withHeaders($cabeceras)
            ->json('POST', 'api/categorias-productos', $categoria);

        $idCategoria = $respuestaCategoria->getData(true)['categoria']['id'];

        $producto = [
            'codigo' => '181696',
            'nombre' => 'ELAION F50 d1 0W-20 12/1',
            'presentacion' => 'Caja 12u / 1 litro',
            'precio_por_mayor' => 150,
            'consumidor_final' => 200,
            'id_categoria' => $idCategoria
        ];

        $respuestaProducto = $this
            ->withHeaders($cabeceras)
            ->json('POST', 'api/productos', $producto);

        return $respuestaProducto->getData(true)['producto'];
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
    public function testNoDeberiaObtenerNingunProducto()
    {
        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', 'api/productos');

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
    public function testDeberiaObtenerProductos()
    {
        factory(CategoriaProducto::class, 10)->create()->each(function ($categoria) {
            $categoria->productos()->save(factory(Producto::class)->make());
        });

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', 'api/productos');

        $estructura = $this->getEstructuraProductos();
        $productos = Producto::orderBy('nombre', 'ASC')->get()->toArray();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson(['productos' => $productos]);
    }

    /**
     * Debería crear un producto sin imagen
     */
    public function testDeberiaCrearUnProductoSinImagen()
    {
        $cabeceras = $this->loguearseComo('defecto');

        $categoria = ['descripcion' => 'Automotriz Alta Gama'];

        $respuestaCategoria = $this
            ->withHeaders($cabeceras)
            ->json('POST', 'api/categorias-productos', $categoria);

        $idCategoria = $respuestaCategoria->getData(true)['categoria']['id'];

        $producto = [
            'codigo' => '181696',
            'nombre' => 'ELAION F50 d1 0W-20 12/1',
            'presentacion' => 'Caja 12u / 1 litro',
            'precio_por_mayor' => 150,
            'consumidor_final' => 200,
            'id_categoria' => $idCategoria
        ];

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', 'api/productos', $producto);

        $estructura = $this->getEstructuraProducto();

        $respuesta
            ->assertStatus(201)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'producto' => $producto,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'GUARDADO',
                    'descripcion' => 'El producto ELAION F50 d1 0W-20 12/1 ha sido creado'
                ]
            ]);
    }

    /**
     * Debería crear un producto con imagen
     */
    public function testDeberiaCrearUnProductoConImagen()
    {
        $cabeceras = $this->loguearseComo('defecto');

        $categoria = ['descripcion' => 'Automotriz Alta Gama'];

        $respuestaCategoria = $this
            ->withHeaders($cabeceras)
            ->json('POST', 'api/categorias-productos', $categoria);

        $idCategoria = $respuestaCategoria->getData(true)['categoria']['id'];

        Storage::fake('productos');
        $imagen = $this->getImagen();

        $producto = [
            'codigo' => '181696',
            'nombre' => 'ELAION F50 d1 0W-20 12/1',
            'presentacion' => 'Caja 12u / 1 litro',
            'precio_por_mayor' => 150,
            'consumidor_final' => 200,
            'id_categoria' => $idCategoria,
            'imagen' => $imagen
        ];

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', 'api/productos', $producto);

        $estructura = $this->getEstructuraProducto();

        $productoGuardado = $respuesta->getData(true)['producto'];
        $nombreArchivo = $this->getNombreImagen($productoGuardado, $imagen);

        $productoEsperado = $producto;
        $productoEsperado['imagen'] = $this->getURLImagen($nombreArchivo);

        $respuesta
            ->assertStatus(201)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'producto' => $productoEsperado,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'GUARDADO',
                    'descripcion' => 'El producto ELAION F50 d1 0W-20 12/1 ha sido creado'
                ]
            ]);

        Storage::disk('productos')->assertExists($nombreArchivo);
    }

    /**
     * Debería obtener un producto
     */
    public function testDeberiaObtenerUnProducto()
    {
        $cabeceras = $this->loguearseComo('defecto');

        $productoGuardado = $this->crearProducto($cabeceras);
        $id = $productoGuardado['id'];

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', "api/productos/$id");

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($this->estructuraProducto)
            ->assertExactJson([
                'producto' => $productoGuardado
            ]);
    }

    /**
     * Debería editar un producto sin imagen
     */
    public function testDeberiaEditarUnProductoSinImagen()
    {
        $cabeceras = $this->loguearseComo('defecto');

        $productoGuardado = $this->crearProducto($cabeceras);
        $idProducto = $productoGuardado['id'];
        $idCategoria = $productoGuardado['id_categoria'];

        Storage::fake('productos');
        $imagen = $this->getImagen();

        $producto = [
            'codigo' => '181696',
            'nombre' => 'ELAION F50 d1 0W-20',
            'presentacion' => '12 x 1 litro',
            'precio_por_mayor' => 150,
            'consumidor_final' => 200,
            'id_categoria' => $idCategoria,
            'imagen' => $imagen
        ];

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('PUT', "api/productos/$idProducto", $producto);

        $estructura = $this->getEstructuraProducto();

        $productoActualizado = $respuesta->getData(true)['producto'];
        $nombreArchivo = $this->getNombreImagen($productoActualizado, $imagen);

        $productoEsperado = array_merge($productoGuardado, $productoActualizado);
        unset($productoEsperado['updated_at']);
        $productoEsperado['imagen'] = $this->getURLImagen($nombreArchivo);

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'producto' => $productoEsperado,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ACTUALIZADO',
                    'descripcion' => 'El producto ELAION F50 d1 0W-20 ha sido modificado'
                ]
            ]);

        Storage::disk('productos')->assertExists($nombreArchivo);
    }

    /**
     * Debería editar un producto con imagen
     */
    public function testDeberiaEditarUnProductoConImagen()
    {
        $cabeceras = $this->loguearseComo('defecto');

        $productoGuardado = $this->crearProducto($cabeceras);
        $idProducto = $productoGuardado['id'];
        $idCategoria = $productoGuardado['id_categoria'];

        $producto = [
            'codigo' => '181696',
            'nombre' => 'ELAION F50 d1 0W-20',
            'presentacion' => '12 x 1 litro',
            'precio_por_mayor' => 150,
            'consumidor_final' => 200,
            'id_categoria' => $idCategoria
        ];

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('PUT', "api/productos/$idProducto", $producto);

        $estructura = $this->getEstructuraProducto();

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'producto' => $producto,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ACTUALIZADO',
                    'descripcion' => 'El producto ELAION F50 d1 0W-20 ha sido modificado'
                ]
            ]);
    }

    /**
     * Debería eliminar un producto
     */
    public function testDeberiaEliminarUnProducto()
    {
        $cabeceras = $this->loguearseComo('defecto');

        $productoGuardado = $this->crearProducto($cabeceras);
        $id = $productoGuardado['id'];

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('DELETE', "api/productos/$id");

        $estructura = $this->getEstructuraProducto();

        $productoDB = Producto::withTrashed()
            ->where('id', $id)
            ->firstOrFail()
            ->toArray();

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertExactJson([
                'producto' => $productoDB,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ELIMINADO',
                    'descripcion' => 'El producto ELAION F50 d1 0W-20 12/1 ha sido eliminado'
                ]
            ]);
    }

    /**
     * Debería restaurar un producto
     */
    public function testDeberiaRestaurarUnProducto()
    {
        $cabeceras = $this->loguearseComo('defecto');

        $productoGuardado = $this->crearProducto($cabeceras);
        $id = $productoGuardado['id'];

        $this
            ->withHeaders($cabeceras)
            ->json('DELETE', "api/productos/$id");

        $respuesta = $this->withHeaders($cabeceras)
            ->json('POST', "api/productos/$id/restaurar");

        $estructura = $this->getEstructuraProducto();

        $productoDB = Producto::withTrashed()
            ->where('id', $id)
            ->firstOrFail()
            ->toArray();

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertExactJson([
                'producto' => $productoDB,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'RESTAURADO',
                    'descripcion' => 'El producto ELAION F50 d1 0W-20 12/1 ha sido dado de alta'
                ]
            ]);
    }
}
