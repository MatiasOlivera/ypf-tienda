<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Cotizacion;
use App\CotizacionEstado;
use App\Auxiliares\Consulta;
use Illuminate\Http\Request;
use App\Auxiliares\Respuesta;
use App\Auxiliares\MensajeError;
use App\Auxiliares\MensajeExito;
use App\Http\Requests\Cotizacion\CotizacionesRequest;
use App\Http\Resources\Cotizacion\CotizacionResource;
use App\Http\Resources\Cotizacion\CotizacionCollection;
use App\Http\Requests\Cotizacion\CrearCotizacionRequest;

class CotizacionController extends Controller
{
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
                    $productoDB = Producto::select('codigo')->findOrFail($producto['id']);

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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cotizacion  $cotizacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cotizacion $cotizacion)
    {
        //
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
}
