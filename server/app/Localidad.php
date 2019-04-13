<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

class Localidad extends Model
{
    use SoftDeletes, Eloquence, Mappable;

    protected $table = 'localidades';
    protected $primaryKey = 'id_localidad';

    protected $maps = [
        'id'                => 'id_localidad',
        'nombre'            => 'nom_localidad',
        'provincia_id'      => 'id_provincia',
    ];

    protected $appends  = ['id', 'nombre', 'provincia_id',];

    protected $visibble = ['id', 'nombre', 'provincia_id',];

    protected $hidden   = ['id_localidad', 'nom_localidad', 'id_provincia',];
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
    protected $fillable = ['id', 'nombre', 'provincia_id',];


    public function provincia()
    {
        return $this->belongsTo('App\Provincia', 'id_provincia');
    }
}
