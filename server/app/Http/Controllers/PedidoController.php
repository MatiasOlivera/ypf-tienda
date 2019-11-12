<?php

namespace App\Http\Controllers;

use App\Pedido;
use App\PedidoEntregaEstado;
use App\Auxiliares\Consulta;
use Illuminate\Http\Request;
use App\Auxiliares\Respuesta;
use App\Auxiliares\MensajeError;
use App\Http\Requests\Pedido\PedidosRequest;
use App\Http\Resources\Pedido\PedidoCollection;

class PedidoController extends Controller
{
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function show(Pedido $pedido)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function edit(Pedido $pedido)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pedido $pedido)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pedido $pedido)
    {
        //
    }
}
