<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Observacion extends Model
{
    protected $table = 'observacion';
    protected $primaryKey = 'id';

    protected $hidden = ['estado'];

    protected $fillable = ['descripcion'];

    public $timestamps = false;
}
