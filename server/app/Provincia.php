<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\{ Eloquence ,Mappable };

class Provincia extends Model
{
    use SoftDeletes, Eloquence, Mappable;

    protected $table = 'provincias';
    protected $primaryKey = 'id_provincia';

    protected $maps = [
        'id'                => 'id_provincia',
        'nombre'            => 'nom_provincia',
    ];

    protected $appends  = ['id', 'nombre',];

    protected $visibble = ['id', 'nombre',];

    protected $hidden   = ['nom_provincia', 'id_provincia',];

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
    protected $fillable = ['nombre',];

    public function localidades()
    {
        return $this->hasMany('App\Localidad', 'id_provincia');
    }
}
