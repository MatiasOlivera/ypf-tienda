<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Cotizacion;
use App\CotizacionProducto;
use Illuminate\Http\Request;
use App\Auxiliares\Respuesta;
use App\Auxiliares\MensajeError;
use App\Auxiliares\MensajeExito;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\ActualizarCotizacionProducto;
use App\Http\Requests\Cotizacion\Producto\CotizacionProductoRequest;

class CotizacionProductoController extends Controller
{
    use ActualizarCotizacionProducto;

    protected $controladorBase;
    protected $modeloSingular;
    protected $modeloPlural;
    protected $generoModelo;

    public function __construct()
    {
        $this->modeloPlural = 'productos';
        $this->modeloSingular = 'producto';
        $this->generoModelo = 'masculino';
        $this->controladorBase = new BaseController($this->modeloSingular, $this->modeloPlural, $this->generoModelo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\CotizacionProductoRequest  $request
     * @param  \App\Cotizacion  $cotizacion
     * @return \Illuminate\Http\Response
     */
    public function update(CotizacionProductoRequest $request, Cotizacion $cotizacion)
    {
        $inputProductos = $request->input('productos');
        return $this->actualizarProductos($cotizacion, $inputProductos);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cotizacion  $cotizacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Cotizacion $cotizacion, CotizacionProducto $cotizacionProducto)
    {
        $nombre = "El producto {$cotizacionProducto->producto->nombre}";
        return $this->controladorBase->destroy($cotizacionProducto, $nombre);
    }
}
