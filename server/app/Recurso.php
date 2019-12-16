<?php

namespace App;

use App\Empleado;
use App\EmpleadoPermiso;
use Sofa\Eloquence\Mappable;
use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Model;

class Recurso extends Model
{
    use Eloquence, Mappable;

    protected $table = 'recurso';
    protected $primaryKey = 'ID_recurso';

    protected $maps = [
        'id' => 'ID_recurso'
    ];

    protected $appends = ['id'];

    protected $hidden = ['ID_recurso'];

    protected $fillable = ['nombre'];

    public $timestamps = false;

    public function permisos()
    {
        return $this->belongsToMany(Empleado::class, 'permiso', 'ID_recurso', 'ID_ven')
            ->as('permiso')
            ->using(EmpleadoPermiso::class)
            ->withPivot('ver', 'crear', 'editar', 'borrar');
    }
}
