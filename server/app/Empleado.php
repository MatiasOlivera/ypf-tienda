<?php

namespace App;

use App\Cotizacion;
use App\EmpleadoCargo;
use Sofa\Eloquence\Mappable;
use Sofa\Eloquence\Eloquence;
use Illuminate\Foundation\Auth\User;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;

class Empleado extends User implements JWTSubject
{
    use Notifiable, Eloquence, Mappable;

    protected $guard = 'empleado';

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

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function cargo()
    {
        return $this->belongsTo(EmpleadoCargo::class, 'id_cargo');
    }

    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class, 'id_cot');
    }
}
