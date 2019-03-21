<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Telefono extends Model
{
    protected $table = 'con_cliente';

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
    protected $fillable = ['id_cliente', 'area', 'tel', 'nombre_contacto', ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['mail',  'estado', ];

    public function cliente()
    {
        return $this->belongsTo('App\Cliente', 'id_cliente', 'id_cliente');
    }
}
