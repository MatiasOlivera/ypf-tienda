<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Cotizacion;
use App\Observacion;
use App\CotizacionEstado;
use App\Auxiliares\Consulta;
use Illuminate\Http\Request;
use App\Auxiliares\Respuesta;
use App\Auxiliares\MensajeError;
use App\Auxiliares\MensajeExito;
use App\Http\Requests\Cotizacion\CotizacionesRequest;
use App\Http\Resources\Cotizacion\CotizacionResource;
use App\Http\Controllers\ActualizarCotizacionProducto;
use App\Http\Resources\Cotizacion\CotizacionCollection;
use App\Http\Requests\Cotizacion\CrearCotizacionRequest;
use App\Http\Requests\Cotizacion\ActualizarCotizacionRequest;

class CotizacionController extends Controller
{
    use ActualizarCotizacionProducto;

    protected $baseController;
    protected $modeloSingular;
    protected $modeloPlural;
    protected $generoModelo;

    public function __construct()
    {
        $this->modeloPlural = 'cotizaciones';
        $this->modeloSingular = 'cotizacion';
        $this->generoModelo = 'femenino';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CotizacionesRequest $request)
    {
        try {
            $consulta = Cotizacion::with([
                'cliente:id,nombre',
                'razonSocial:id,denominacion',
                'cotizacionEstado:id,descripcion',
                'empleado:id,nombre'
            ]);

            $parametros = [
                'modelo' => $consulta,
                'campos' => [
                    'id',
                    'empleado_id',
                    'cliente_id',
                    'razon_id',
                    'fecha_pedido',
                    'estado_id',
                    'consumidor_final',
                    'plazo',
                    'telefono_id',
                    'domicilio_id',
                    'pedido_id',
                    'observacion_id',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ],
                'relaciones' => null,
                'buscar' => $request->query("buscar", null),
                'eliminados' => $request->query("eliminados", false),
                'paginado'  => [
                    'porPagina' => $request->query("porPagina", 10),
                    'ordenarPor' => $request->query("ordenarPor", 'created_at'),
                    'orden' => $request->query("orden", 'DESC'),
                ]
            ];

            $consulta = new Consulta();
            $productos = $consulta->ejecutarConsulta($parametros);

            return new CotizacionCollection($productos);
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError();
            $mensajeError->obtenerTodos('las cotizaciones');

            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\CrearCotizacionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CrearCotizacionRequest $request)
    {
        $inputs = $request->only($request->getCampos());
        $nombre = "La cotización";

        try {
            $estadoPendiente = CotizacionEstado::where('descripcion', 'Pendiente')->first();

            $cotizacion = new Cotizacion();
            $cotizacion->fill($inputs);
            $cotizacion->cotizacionEstado()->associate($estadoPendiente);
            $guardada = $cotizacion->save();

            if ($guardada) {
                // Guardar la observación
                $observacion = $request->input('observacion');

                if ($observacion) {
                    $cotizacion->observacion()->create(['descripcion' => $observacion]);
                }

                // Guardar los productos
                $productos = $request->input('productos');

                foreach ($productos as $producto) {
                    $productoDB = Producto::select('codigo')->findOrFail($producto['producto_id']);

                    $inputProductos[] = [
                        'codigo' => $productoDB->codigo,
                        'cantidad' => $producto['cantidad'],
                        'precio' => $producto['precio']
                    ];
                }

                $cotizacion->productos()->createMany($inputProductos);

                // Crear la respuesta
                $cotizacionGuardada = Cotizacion::with([
                    'empleado',
                    'cliente',
                    'razonSocial',
                    'cotizacionEstado',
                    'telefono',
                    'domicilio',
                    'observacion',
                    'pedido',
                    'productos.producto'
                ])->findOrFail($cotizacion->id);

                $mensajeExito = new MensajeExito();
                $mensajeExito->guardar($nombre, $this->generoModelo);

                return (new CotizacionResource($cotizacionGuardada))
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
     * @param  \App\Cotizacion  $cotizacion
     * @return \Illuminate\Http\Response
     */
    public function show(Cotizacion $cotizacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\ActualizarCotizacionRequest  $request
     * @param  \App\Cotizacion  $cotizacion
     * @return \Illuminate\Http\Response
     */
    public function update(ActualizarCotizacionRequest $request, Cotizacion $cotizacion)
    {
        $inputs = $request->only([
            'razon_id',
            'estado_id',
            'consumidor_final',
            'plazo',
            'telefono_id',
            'domicilio_id'
        ]);
        $nombre = "La cotización";

        try {
            $cotizacion->fill($inputs);
            $actualizada = $cotizacion->save();

            $observacion = $request->input('observacion');
            $this->actualizarObservacion($cotizacion, $observacion);

            if ($actualizada) {
                // Agregar, actualizar o quitar los productos
                $inputProductos = $request->input('productos');
                $this->actualizarProductos($cotizacion, $inputProductos);

                // Crear la respuesta
                $cotizacionActualizada = Cotizacion::with([
                    'empleado',
                    'cliente',
                    'razonSocial',
                    'cotizacionEstado',
                    'telefono',
                    'domicilio',
                    'observacion',
                    'pedido',
                    'productos.producto'
                ])->findOrFail($cotizacion->id);

                $mensajeExito = new MensajeExito();
                $mensajeExito->actualizar($nombre, $this->generoModelo);

                return (new CotizacionResource($cotizacionActualizada))
                    ->additional(['mensaje' => $mensajeExito->toJson()])
                    ->response()
                    ->setStatusCode(200);
            }
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError();
            $mensajeError->actualizar($nombre, $this->generoModelo);
            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cotizacion  $cotizacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cotizacion $cotizacion)
    {
        //
    }

    /**
     * Crear o actualizar la observación
     *
     * @param Cotizacion $cotizacion
     * @param string|null $inputObservacion
     * @return bool
     */
    private function actualizarObservacion(Cotizacion $cotizacion, ?string $inputObservacion): bool
    {
        $observacion = $cotizacion->observacion;

        if (is_null($observacion) && is_string($inputObservacion)) {
            $nuevaObservacion = new Observacion();
            $nuevaObservacion->descripcion = $inputObservacion;
            $guardada = $nuevaObservacion->save();

            if ($guardada) {
                $cotizacion->observacion_id = $nuevaObservacion->id;
                return $cotizacion->save();
            }
        }

        if ($observacion && $observacion->descripcion !== $inputObservacion) {
            $observacion->descripcion = $inputObservacion;
            return $observacion->save();
        } else {
            return true;
        }

        return false;
    }
}
