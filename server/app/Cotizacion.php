<?php

namespace App;

use App\Observacion;
use App\ClienteTelefono;
use App\ClienteDomicilio;
use App\CotizacionEstado;
use App\ClienteRazonSocial;
use Sofa\Eloquence\Mappable;
use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cotizacion extends Model
{
    use SoftDeletes, Eloquence, Mappable;

    protected $table = 'cotizacion';
    protected $primaryKey = 'id_cot';

    protected $maps = [
        'id' => 'id_cot',
        'empleado_id' => 'id_us',
        'cliente_id' => 'id_cliente',
        'razon_id' => 'id_rz',
        'estado_id' => 'estado',
        'consumidor_final' => 'cons_final',
        'telefono_id' => 'id_con',
        'domicilio_id' => 'id_dom',
        'pedido_id' => 'id_pedido',
        'observacion_id' => 'id_observacion'
    ];

    protected $appends = [
        'id',
        'empleado_id',
        'cliente_id',
        'razon_id',
        'estado_id',
        'consumidor_final',
        'telefono_id',
        'domicilio_id',
        'pedido_id',
        'observacion_id'
    ];

    protected $hidden = [
        'id_cot',
        'id_us',
        'id_cliente',
        'id_rz',
        'estado',
        'cons_final',
        'id_con',
        'id_dom',
        'id_pedido',
        'id_observacion'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
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
        'observacion_id'
    ];

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

    public function telefono()
    {
        return $this->belongsTo(ClienteTelefono::class, 'id_con');
    }

    public function domicilio()
    {
        return $this->belongsTo(ClienteDomicilio::class, 'id_dom');
    }

    public function observacion()
    {
        return $this->hasOne(Observacion::class, 'id', 'id_observacion');
    }
}
