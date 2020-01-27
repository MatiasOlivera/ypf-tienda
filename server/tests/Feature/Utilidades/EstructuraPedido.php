<?php

namespace Tests\Feature\Utilidades;

trait EstructuraPedido
{
    private $atributosPedido = [
        'id',
        'empleado_id',
        'cliente_id',
        'razon_id',
        'fecha_pedido',
        'estado_id',
        'entrega_estado_id',
        'consumidor_final',
        'plazo',
        'telefono_id',
        'domicilio_id',
        'observacion_id'
    ];
}
