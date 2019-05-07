<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\{ Eloquence, Mappable };

class ClienteTelefono extends Model
{
    use SoftDeletes, Eloquence, Mappable;

    protected $table = 'con_cliente';
    protected $primaryKey = 'id';

    protected $maps = [
        'cliente_id'        => 'id_cliente',
        'telefono'          => 'tel',
        'nombreContacto'    => 'nombre_contacto',
    ];

    protected $appends  = ['telefono', 'nombreContacto', 'cliente_id',];

    protected $visibble = ['id', 'area', 'telefono', 'nombreContacto', 'cliente_id',];

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
    protected $fillable = ['area', 'telefono', 'nombreContacto', 'id_cliente',];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden   = ['tel', 'nombre_contacto', 'mail', 'estado', 'id_cliente',];


    public function cliente()
    {
        return $this->belongsTo('App\Cliente', 'id_cliente', 'id_cliente');
    }
}
