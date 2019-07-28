<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\{ Eloquence, Mappable };

class ClienteRazonSocial extends Model
{
    use SoftDeletes, Eloquence, Mappable;

    protected $table = 'razones_sociales';
    protected $primaryKey = 'id_razon';

    protected $maps = [
        'id'            => 'id_razon',
        'denominacion'  => 'nombre',
        'localidad_id'  => 'id_loc',
        'numero'        => 'altura',
        'area'          => 'area_tel',
        'telefono'      => 'tel',
        'email'         => 'mail'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'denominacion',
        'cuit',
        'localidad_id',
        'calle',
        'numero',
        'area',
        'telefono',
        'email',
    ];

    protected $appends  = ['id', 'denominacion', 'localidad_id', 'numero', 'area', 'telefono',];

    protected $visibble = [
        'id',
        'denominacion',
        'cuit',
        'localidad_id',
        'calle',
        'numero',
        'area',
        'telefono',
        'email',
    ];

    protected $hidden   = ['id_razon', 'nombre', 'id_loc', 'altura', 'area_tel', 'tel', 'mail', 'fecha_carga', 'estado',];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];


    public function localidad()
    {
        return $this->belongsTo('App\Localidad', 'id_loc');
    }

    public function clientes()
    {
        return $this->belongsToMany('App\Cliente', 'cliente_razon', 'id_razon', 'id_cliente')
            ->with(['localidad',])
            ->withTimestamps();
    }
}
