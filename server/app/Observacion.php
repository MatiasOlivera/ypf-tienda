<?php

namespace App;

use App\Pedido;
use App\Cotizacion;
use Illuminate\Database\Eloquent\Model;

class Observacion extends Model
{
    protected $table = 'observacion';
    protected $primaryKey = 'id';

    protected $hidden = ['estado'];

    protected $fillable = ['descripcion'];

    public $timestamps = false;

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'id', 'id_observacion');
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id', 'id_observacion');
    }
}
