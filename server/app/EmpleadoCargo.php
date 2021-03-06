<?php

namespace App;

use App\Empleado;
use Sofa\Eloquence\Mappable;
use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Model;

class EmpleadoCargo extends Model
{
    use Eloquence, Mappable;

    protected $table = 'cargo';
    protected $primaryKey = 'id_cargo';

    protected $maps = [
        'id' => 'id_cargo'
    ];

    protected $appends = ['id'];

    protected $hidden = ['id_cargo'];

    protected $fillable = ['nombre'];

    public $timestamps = false;

    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'id_cargo');
    }
}
