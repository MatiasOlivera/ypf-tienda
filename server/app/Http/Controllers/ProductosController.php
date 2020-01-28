<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Auxiliares\Consulta;
use Illuminate\Http\Request;
use App\Auxiliares\Respuesta;
use App\Auxiliares\MensajeError;
use App\Auxiliares\MensajeExito;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProductoResource;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\ProductoCollection;
use App\Http\Requests\Producto\ProductosRequest;
use App\Http\Requests\Producto\CrearProductoRequest;
use App\Http\Requests\Producto\ActualizarProductoRequest;

class ProductosController extends Controller
{
    private $controladorBase;
    private $generoModelo = 'masculino';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductosRequest $request)
    {
        try {
            $soloFavoritos = $request->input('soloFavoritos', false);

            if (Auth::check() && Auth::user()->esCliente() && $soloFavoritos === true) {
                $modelo = Producto::with('usuariosQueMarcaronComoFavorito')
                    ->whereHas('usuariosQueMarcaronComoFavorito', function (Builder $consulta) {
                        $consulta->where('cliente_usuario_id', Auth::id());
                    });
            } else {
                $modelo = 'Producto';
            }

            $parametros = [
                'modelo' => $modelo,
                'campos' => [
                    'id',
                    'codigo',
                    'nombre',
                    'presentacion',
                    'imagen_ruta',
                    'precio_por_mayor',
                    'consumidor_final',
                    'id_categoria',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ],
                'relaciones' => null,
                'buscar' => $request->input('buscar', null),
                'eliminados' => $request->input('eliminados', false),
                'paginado' => [
                    'porPagina' => $request->input('porPagina', 10),
                    'ordenarPor' => $request->input('ordenarPor', 'nombre'),
                    'orden' => $request->input('orden', 'ASC'),
                ]
            ];

            $consulta = new Consulta();
            $productos = $consulta->ejecutarConsulta($parametros);

            return new ProductoCollection($productos);
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError();
            $mensajeError->obtenerTodos('los productos');

            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Producto\CrearProductoRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CrearProductoRequest $request)
    {
        $inputs = $request->only(
            'codigo',
            'nombre',
            'presentacion',
            'precio_por_mayor',
            'consumidor_final',
            'id_categoria'
        );
        $nombre = "El producto {$request->input('nombre')}";

        try {
            $producto = new Producto();
            $producto->fill($inputs);
            $guardado = $producto->save();

            if ($guardado) {
                $imagenRuta = $this->guardarImagen($request, $producto);

                if (!is_null($imagenRuta)) {
                    $producto->imagen_ruta = $imagenRuta;
                    $producto->save();
                }

                $productoGuardado = Producto::findOrFail($producto->id);

                $mensajeExito = new MensajeExito();
                $mensajeExito->guardar($nombre, $this->generoModelo);

                return (new ProductoResource($productoGuardado))
                    ->additional(['mensaje' => $mensajeExito->toJson()])
                    ->response()
                    ->setStatusCode(201);
            }
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError();
            $mensajeError->guardar($nombre, $this->generoModelo);
            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        return new ProductoResource($producto);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\ActualizarProductoRequest  $request
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(ActualizarProductoRequest $request, Producto $producto)
    {
        try {
            $nombres = [
                'exito' => "El producto {$request->input('nombre')}",
                'error' => "El producto $producto->nombre"
            ];

            $inputs = $request->only(
                'codigo',
                'nombre',
                'presentacion',
                'precio_por_mayor',
                'consumidor_final',
                'id_categoria'
            );

            $producto->fill($inputs);
            $producto->imagen_ruta = $this->guardarImagen($request, $producto);
            $actualizado = $producto->save();

            if ($actualizado) {
                $mensajeExito = new MensajeExito();
                $mensajeExito->actualizar($nombres['exito'], $this->generoModelo);

                return (new ProductoResource($producto))
                    ->additional(['mensaje' => $mensajeExito->toJson()]);
            }
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError();
            $mensajeError->actualizar($nombres['error'], $this->generoModelo);

            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        $nombre = "El producto $producto->nombre";

        try {
            $eliminado = $producto->delete();

            if ($eliminado) {
                $mensajeExito = new MensajeExito();
                $mensajeExito->eliminar($nombre, $this->generoModelo);

                return (new ProductoResource($producto))
                    ->additional(['mensaje' => $mensajeExito->toJson()]);
            }
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError();
            $mensajeError->eliminar($nombre, $this->generoModelo);

            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Restaurar el producto que ha sido eliminado
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function restore(Producto $producto)
    {
        $nombre = "El producto $producto->nombre";

        try {
            $restaurado = $producto->restore();

            if ($restaurado) {
                $mensajeExito = new MensajeExito();
                $mensajeExito->restaurar($nombre, $this->generoModelo);

                return (new ProductoResource($producto))
                    ->additional(['mensaje' => $mensajeExito->toJson()]);
            }
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError();
            $mensajeError->restaurar($nombre, $this->generoModelo);

            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Guardar la imagen en el disco
     */
    private function guardarImagen(Request $request, Producto $producto): ?string
    {
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');

            if ($imagen->isValid()) {
                // Nombre del archivo
                $id = $producto->id;
                $extension = $imagen->extension();
                $nombreArchivo = "$id.$extension";

                // Almacenar la imagen nueva
                $rutaNueva = Storage::disk('productos')->putFileAs('', $imagen, $nombreArchivo);
                $rutaAnterior = $producto->imagen_ruta;

                // Eliminar la imagen anterior si la extension es distinta
                if (!is_null($rutaAnterior) && $rutaNueva !== $rutaAnterior) {
                    Storage::disk('productos')->delete($rutaAnterior);
                }

                return $rutaNueva;
            }
        }

        return null;
    }
}
