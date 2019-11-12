<?php

namespace App;

use App\Pedido;
use Sofa\Eloquence\Mappable;
use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Model;

class PedidoEntregaEstado extends Model
{
    use Eloquence, Mappable;

    protected $table = 'estado_pedido';
    protected $primaryKey = 'id_estado';

    protected $maps = [
        'id' => 'id_estado',
        'descripcion' => 'desc_estado'
    ];

    protected $appends = [
        'id',
        'descripcion'
    ];

    protected $hidden = [
        'id_estado',
        'desc_estado'
    ];

    protected $fillable = ['descripcion'];

    public $timestamps = false;

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'est_entrega', 'id_estado');
    }
}
