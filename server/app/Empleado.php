<?php

namespace App;

use App\EmpleadoCargo;
use Sofa\Eloquence\Mappable;
use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use Eloquence, Mappable;

    protected $table = 'usuarios';
    protected $primaryKey = 'ID_ven';

    protected $maps = [
        'id' => 'ID_ven',
        'documento' => 'dni_ven',
        'fecha_nacimiento' => 'fe_na',
        'password' => 'pass',
        'cargo_id' => 'id_cargo'
    ];

    protected $appends = [
        'id',
        'documento',
        'fecha_nacimiento',
        'password',
        'cargo_id'
    ];

    protected $hidden = [
        'ID_ven',
        'dni_ven',
        'fe_na',
        'pass',
        'estado',
        'id_cargo'
    ];

    protected $dates = ['fecha_nacimiento'];

    protected $fillable = [
        'documento',
        'nombre',
        'apellido',
        'fecha_nacimiento',
        'sexo',
        'password',
        'cargo_id'
    ];

    public $timestamps = false;

    public function cargo()
    {
        return $this->belongsTo(EmpleadoCargo::class, 'id_cargo');
    }
}
