<?php

namespace App\Http\Controllers;

use App\Producto;
use Illuminate\Http\Request;
use App\Auxiliares\Respuesta;
use App\Auxiliares\MensajeError;
use App\Auxiliares\MensajeExito;
use App\Http\Requests\Producto\CrearProductoRequest;

class ProductosController extends Controller
{
    private $controladorBase;
    private $modeloSingular = 'producto';
    private $modeloPlural = 'productos';
    private $generoModelo = 'masculino';

    public function __construct()
    {
        $this->controladorBase = new BaseController($this->modeloSingular, $this->modeloPlural, $this->generoModelo);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
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
                $productoGuardado = Producto::find($producto->id);

                $mensajeExito = new MensajeExito();
                $mensajeExito->guardar($nombre, $this->generoModelo);

                return Respuesta::exito([$this->modeloSingular => $productoGuardado], $mensajeExito, 201);
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        //
    }
}
