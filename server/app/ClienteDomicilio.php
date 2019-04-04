<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClienteDomicilio extends Model
{
    protected $table = 'dom_cliente';
    protected $primaryKey = 'id_dom';


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
    protected $fillable = ['id_dom', 'id_cliente', 'id_loc', 'calle', 'numero_altura', 'acla'];

    public function cliente()
    {
        return $this->belongsTo('App\Cliente', 'id_cliente');
    }

    public function localidad()
    {
        return $this->belongsTo('App\Localidad', 'id_loc');
    }
}
