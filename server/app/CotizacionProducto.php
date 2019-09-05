<?php

namespace App;

use App\Cotizacion;
use Sofa\Eloquence\Mappable;
use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Model;

class CotizacionProducto extends Model
{
    use Eloquence, Mappable;

    protected $table = 'detalle_cot';
    protected $primaryKey = 'id_det';

    protected $maps = [
        'id' => 'id_det',
        'cotizacion_id' => 'id_cot',
        'codigo' => 'codigo_p',
        'cantidad' => 'cant_prod',
        'precio' => 'precio_producto'
    ];

    protected $appends = [
        'id',
        'cotizacion_id',
        'codigo',
        'cantidad',
        'precio'
    ];

    protected $hidden = [
        'id_det',
        'id_cot',
        'codigo_p',
        'cant_prod',
        'precio_producto',
        'estado'
    ];

    protected $fillable = [
        'cotizacion_id',
        'codigo',
        'cantidad',
        'precio'
    ];

    public $timestamps = false;

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'id_cot', 'id_cot');
    }
}
