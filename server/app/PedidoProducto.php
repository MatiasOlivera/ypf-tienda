<?php

namespace App;

use App\Pedido;
use App\Producto;
use Sofa\Eloquence\Mappable;
use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Model;

class PedidoProducto extends Model
{
    use Eloquence, Mappable;

    protected $table = 'detalle_pedido';
    protected $primaryKey = 'id_det_pedido';

    protected $maps = [
        'id' => 'id_det_pedido',
        'pedido_id' => 'id_pedido',
        'codigo' => 'codigo_p',
        'cantidad' => 'cant_prod',
        'precio' => 'precio_producto'
    ];

    protected $appends = [
        'id',
        'pedido_id',
        'codigo',
        'cantidad',
        'precio'
    ];

    protected $hidden = [
        'id_det_pedido',
        'id_pedido',
        'codigo_p',
        'cant_prod',
        'precio_producto'
    ];

    protected $fillable = [
        'pedido_id',
        'codigo',
        'cantidad',
        'precio'
    ];

    public $timestamps = false;

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido', 'id_pedido');
    }

    public function producto()
    {
        return $this->hasOne(Producto::class, 'codigo_prod', 'codigo_p');
    }
}
