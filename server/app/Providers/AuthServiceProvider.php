<?php

namespace App\Providers;

use App\Cliente;
use App\Policies\ClientePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        Cliente::class => ClientePolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /**
         * Si el usuario es "super administrador" otorgar de forma implicita
         * todos los permisos, para evitar asignarlos a nivel de base de datos.
         */
        Gate::before(function ($usuario, $habilidad) {
            return $usuario->esEmpleado() && $usuario->hasRole('super administrador')
                ? true
                : null;
        });
    }
}
