<?php

namespace App;

use App\Cotizacion;
use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\{ Eloquence, Mappable };
use Illuminate\Database\Eloquent\SoftDeletes;

class ClienteDomicilio extends Model
{
    use SoftDeletes, Eloquence, Mappable;

    protected $table = 'dom_cliente';
    protected $primaryKey = 'id_dom';
    protected $maps = [
        'id'                => 'id_dom',
        'cliente_id'        => 'id_cliente',
        'localidad_id'      => 'id_loc',
        'numero'            => 'numero_altura',
        'aclaracion'        => 'acla',
    ];

    protected $appends  = ['id', 'cliente_id', 'localidad_id', 'numero', 'aclaracion'];

    protected $visibble = ['id', 'cliente_id', 'localidad_id', 'calle', 'numero', 'aclaracion'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['cliente_id', 'localidad_id', 'calle', 'numero', 'aclaracion'];

    protected $hidden   = ['id_dom', 'id_cliente', 'id_loc', 'numero_altura', 'acla', 'estado',];

    public function cliente()
    {
        return $this->belongsTo('App\Cliente', 'id_cliente');
    }

    public function localidad()
    {
        return $this->belongsTo('App\Localidad', 'id_loc')->with(['provincia',]);
    }

    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class, 'id_cot');
    }
}
