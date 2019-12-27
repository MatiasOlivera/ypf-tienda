<?php

namespace App;

use Carbon\Carbon;
use App\Auxiliares\TipoUsuario;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ClienteUsuario extends Authenticatable implements JWTSubject
{
    use SoftDeletes, Notifiable;
    use TipoUsuario;

    protected $guard = 'cliente';

    /**
     * spatie/laravel-permission
     */
    use HasRoles;
    protected $guard_name = 'cliente';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cliente_usuarios';

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
    protected $fillable = [
        'name', 'email', 'password', 'id_cliente'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function cliente()
    {
        return $this->belongsTo('App\Cliente', 'id_cliente', 'id_cliente');
    }

    /**
     * Obtener los productos favoritos del usuario
     */
    public function productosFavoritos()
    {
        return $this->belongsToMany('App\Producto', 'productos_favoritos', 'cliente_usuario_id', 'producto_id')
            ->as('favorito')
            ->withTimestamps();
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
}
