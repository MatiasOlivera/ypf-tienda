<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\{ Eloquence, Mappable };

class ClienteMail extends Model
{
    use SoftDeletes, Eloquence, Mappable;

    protected $table = 'con_cliente';

    protected $maps = [
        'cliente_id' => 'id_cliente',
    ];

   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['cliente_id', 'mail',];

    protected $appends  = ['cliente_id',];

    protected $visibble = ['cliente_id', 'mail',];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['id_cliente','area', 'tel', 'nombre_contacto', 'estado',];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function cliente()
    {
        return $this->belongsTo('App\Cliente', 'id_cliente', 'id_cliente');
    }
}
