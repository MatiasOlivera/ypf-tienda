<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClienteRazonSocial extends Model
{
    use SoftDeletes;

    protected $table = 'razones_sociales';
    protected $primaryKey = 'id_razon';

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
    protected $fillable = ['nombre', 'cuit', 'id_loc', 'calle', 'altura', 'area_tel', 'tel', 'mail', 'fecha_carga',];

    public function localidad()
    {
        return $this->belongsTo('App\Localidad', 'id_loc');
    }

    public function clientes()
    {
        return $this->belongsToMany('App\Cliente', 'cliente_razon', 'id_razon', 'id_cliente')->with(['localidad',])->withTimestamps();
    }
}
