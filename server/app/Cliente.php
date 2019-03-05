<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $fillable = ['dni', 'cliente', 'obsevacion', ];

    public function user()
    {
        return $this->belongsTo('App\User', 'id_cliente', 'id_cliente');
    }
}
