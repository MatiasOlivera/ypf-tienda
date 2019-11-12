<?php

namespace App;

use App\Pedido;
use Sofa\Eloquence\Mappable;
use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Model;

class PedidoEstado extends Model
{
    use Eloquence, Mappable;

    protected $table = 'aprobacion';
    protected $primaryKey = 'id_apro';

    protected $maps = [
        'id' => 'id_apro',
        'descripcion' => 'desc_apro'
    ];

    protected $appends = [
        'id',
        'descripcion'
    ];

    protected $hidden = [
        'id_apro',
        'desc_apro'
    ];

    protected $fillable = ['descripcion'];

    public $timestamps = false;

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'estado', 'id_apro');
    }
}
