<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';

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
    protected $fillable = ['dni', 'cliente', 'obsevacion', 'otros', ];

    public function users()
    {
        return $this->hasMany('App\User', 'id_cliente', 'id_cliente');
    }

    public function domicilios()
    {
        return $this->hasMany('App\ClienteDomicilio', 'id_cliente', 'id_cliente')->with(['localidad', ]);
    }

    public function telefonos()
    {
        return $this->hasMany('App\ClienteTelefono', 'id_cliente', 'id_cliente')->whereNotNull('area');
    }

    public function mails()
    {
        return $this->hasMany('App\ClienteMail', 'id_cliente', 'id_cliente')->whereNotNull('mail');
    }

    public function razonesSociales()
    {
        return $this->belongsToMany('App\ClienteRazonSocial', 'cliente_razon', 'id_cliente', 'id_razon')->with(['localidad', ])->withTimestamps();
    }
}
