<?php

namespace App;

use App\Cliente;
use App\Empleado;
use App\Cotizacion;
use App\Observacion;
use App\PedidoEstado;
use App\PedidoProducto;
use App\ClienteTelefono;
use App\ClienteDomicilio;
use App\CotizacionEstado;
use App\ClienteRazonSocial;
use Sofa\Eloquence\Mappable;
use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use Eloquence, Mappable;

    protected $table = 'pedido';
    protected $primaryKey = 'id_pedido';

    protected $maps = [
        'id' => 'id_pedido',
        'empleado_id' => 'id_us',
        'cliente_id' => 'id_cliente',
        'razon_id' => 'id_rz',
        'cotizacion_estado_id' => 'estado',
        'pedido_estado_id' => 'est_entrega',
        'consumidor_final' => 'cons_final',
        'telefono_id' => 'id_con',
        'domicilio_id' => 'id_dom_cliente',
        'observacion_id' => 'id_observacion'
    ];

    protected $appends = [
        'id',
        'empleado_id',
        'cliente_id',
        'razon_id',
        'cotizacion_estado_id',
        'pedido_estado_id',
        'consumidor_final',
        'telefono_id',
        'domicilio_id',
        'observacion_id'
    ];

    protected $hidden = [
        'id_pedido',
        'id_us',
        'id_cliente',
        'id_rz',
        'estado',
        'est_entrega',
        'cons_final',
        'id_con',
        'id_dom_cliente',
        'id_observacion',
        'remito',
        'generado'
    ];

    protected $fillable = [
        'empleado_id',
        'cliente_id',
        'razon_id',
        'cotizacion_estado_id',
        'pedido_estado_id',
        'fecha_pedido',
        'consumidor_final',
        'plazo',
        'telefono_id',
        'domicilio_id',
        'observacion_id'
    ];

    public $timestamps = false;

    public function productos()
    {
        return $this->hasMany(PedidoProducto::class, 'id_pedido', 'id_pedido');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_us', 'ID_ven');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id_cliente');
    }

    public function razonSocial()
    {
        return $this->belongsTo(ClienteRazonSocial::class, 'id_rz', 'id_razon');
    }

    public function cotizacionEstado()
    {
        return $this->belongsTo(CotizacionEstado::class, 'estado', 'id_apro');
    }

    public function pedidoEstado()
    {
        return $this->belongsTo(PedidoEstado::class, 'est_entrega', 'id_estado');
    }

    public function telefono()
    {
        return $this->belongsTo(ClienteTelefono::class, 'id_con', 'id');
    }

    public function domicilio()
    {
        return $this->belongsTo(ClienteDomicilio::class, 'id_dom_cliente', 'id_dom');
    }

    public function observacion()
    {
        return $this->hasOne(Observacion::class, 'id', 'id_observacion');
    }

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'id_pedido', 'id_pedido');
    }
}
