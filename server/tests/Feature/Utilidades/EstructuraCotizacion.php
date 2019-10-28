<?php

namespace Tests\Feature\Utilidades;

use Tests\Feature\Utilidades\EstructuraPedido;
use Tests\Feature\Utilidades\EstructuraCliente;
use Tests\Feature\Utilidades\EstructuraEmpleado;
use Tests\Feature\Utilidades\EstructuraJsonHelper;
use Tests\Feature\Utilidades\EstructuraObservacion;
use Tests\Feature\Utilidades\EstructuraClienteTelefono;
use Tests\Feature\Utilidades\EstructuraClienteDomicilio;
use Tests\Feature\Utilidades\EstructuraCotizacionEstado;
use Tests\Feature\Utilidades\AtributosClienteRazonSocial;

trait EstructuraCotizacion
{
    use EstructuraJsonHelper;
    use EstructuraCliente;
    use EstructuraPedido;
    use EstructuraObservacion;
    use EstructuraEmpleado;
    use EstructuraClienteTelefono;
    use EstructuraClienteDomicilio;
    use AtributosClienteRazonSocial;
    use EstructuraCotizacionEstado;

    private $atributosCotizacion = [
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
    ];
}
