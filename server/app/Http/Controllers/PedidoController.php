<?php

namespace App\Http\Controllers;

use App\Pedido;
use App\Cotizacion;
use App\PedidoEstado;
use App\PedidoEntregaEstado;
use App\Auxiliares\Consulta;
use Illuminate\Http\Request;
use App\Auxiliares\Respuesta;
use App\Auxiliares\MensajeError;
use App\Auxiliares\MensajeExito;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Pedido\PedidosRequest;
use App\Http\Resources\Pedido\PedidoResource;
use App\Http\Resources\Pedido\PedidoCollection;
use App\Http\Requests\Pedido\CrearPedidoRequest;

class PedidoController extends Controller
{
    protected $generoModelo;

    public function __construct()
    {
        $this->generoModelo = 'masculino';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PedidosRequest $request)
    {
        try {
            $consulta = Pedido::with([
                'cliente:id,nombre',
                'entregaEstado:id,descripcion',
                'empleado:id,nombre',
                'domicilio.localidad:id,nombre'
            ]);

            $entregaEstado = $request->input('entrega_estado');

            if ($entregaEstado === 'pendiente') {
                $estadoPendiente = PedidoEntregaEstado::where('descripcion', 'Pendiente')->first();
                $estadoEntregaParcial = PedidoEntregaEstado::where('descripcion', 'Entrega Parcial')->first();

                $consulta
                    ->where('entrega_estado_id', $estadoPendiente->id)
                    ->orWhere('entrega_estado_id', $estadoEntregaParcial->id);
            }

            $parametros = [
                'modelo' => $consulta,
                'campos' => [
                    'id',
                    'empleado_id',
                    'cliente_id',
                    'razon_id',
                    'fecha_pedido',
                    'estado_id',
                    'fecha_entrega',
                    'entrega_estado_id',
                    'consumidor_final',
                    'plazo',
                    'telefono_id',
                    'domicilio_id',
                    'observacion_id'
                ],
                'relaciones' => null,
                'buscar' => $request->input("buscar", null),
                'eliminados' => $request->input("eliminados", false),
                'paginado'  => [
                    'porPagina' => $request->input("porPagina", 10),
                    'ordenarPor' => $request->input("ordenarPor", 'id'),
                    'orden' => $request->input("orden", 'DESC'),
                ]
            ];

            $consulta = new Consulta();
            $pedidos = $consulta->ejecutarConsulta($parametros);

            return new PedidoCollection($pedidos);
        } catch (\Throwable $th) {
            $mensajeError = new MensajeError();
            $mensajeError->obtenerTodos('los pedidos');

            return Respuesta::error($mensajeError, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CrearPedidoRequest $request)
    {
        $cotizacionId = $request->input('cotizacion_id');
        $nombre = "El pedido";

        try {
            $cotizacion = Cotizacion::findOrFail($cotizacionId);

            if (!is_null($cotizacion->pedido_id)) {
                $mensaje = "$nombre no ha sido creado debido a que ya existe un pedido relacionado con esta cotización";
                $mensajePedidoExistente = new MensajeError($mensaje, "EXISTENTE");

                return Respuesta::error($mensajePedidoExistente, 500);
            }

            $cotizacionArray = $cotizacion->toArray();


            DB::beginTransaction();

            $cotizacionGuardada = false;
            $productosGuardados = false;

            // crear el modelo pedido estado y pedido entrega estado
            $pedido = new Pedido();
            $pedido->fill($cotizacionArray);

            $estado = PedidoEstado::where('descripcion', 'Pendiente')->first();
            $pedido->pedidoEstado()->associate($estado);

            $entregaPendiente = PedidoEntregaEstado::where('descripcion', 'Pendiente')->first();
            $pedido->entregaEstado()->associate($entregaPendiente);

            $guardado = $pedido->save();

            if ($guardado) {
                $cotizacion->pedido_id = $pedido->id;
                $cotizacionGuardada = $cotizacion->save();

                $productos = $cotizacion->productos->toArray();
                $pedido->productos()->createMany($productos);
                $productosGuardados = $pedido->save();
            }

            if ($guardado && $cotizacionGuardada && $productosGuardados) {
                DB::commit();

                // Crear la respuesta
                $pedidoGuardado = $this->obtenerPedido($pedido->id);

                $mensajeExito = new MensajeExito();
                $mensajeExito->guardar($nombre, $this->generoModelo);

                return (new PedidoResource($pedidoGuardado))
                    ->additional(['mensaje' => $mensajeExito->toJson()])
                    ->response()
                    ->setStatusCode(201);
            } else {
                DB::rollBack();

                throw new Exception("$nombre no ha sido creado debido a un error en las consultas");
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
     * @param  \App\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function show(Pedido $pedido)
    {
        $pedidoConRelaciones = $this->obtenerPedido($pedido->id);
        return new PedidoResource($pedidoConRelaciones);
    }

    /**
     * Obtener el pedido con sus relaciones
     *
     * @param integer $id
     * @return Pedido
     */
    private function obtenerPedido(int $id)
    {
        return Pedido::with([
            'empleado',
            'cliente',
            'razonSocial',
            'pedidoEstado',
            'telefono',
            'domicilio',
            'observacion',
            'productos.producto'
        ])
        ->findOrFail($id);
    }
}
