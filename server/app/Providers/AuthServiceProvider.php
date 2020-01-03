<?php

namespace App\Providers;

use App\Cliente;
use App\ClienteMail;
use App\ClienteTelefono;
use App\ClienteDomicilio;
use App\Policies\ClientePolicy;
use App\Policies\ClienteEmailPolicy;
use Illuminate\Support\Facades\Gate;
use App\Policies\ClienteTelefonoPolicy;
use App\Policies\ClienteDomicilioPolicy;
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
        Cliente::class => ClientePolicy::class,
        ClienteDomicilio::class => ClienteDomicilioPolicy::class,
        ClienteTelefono::class => ClienteTelefonoPolicy::class,
        ClienteMail::class => ClienteEmailPolicy::class,
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

        /**
         * Cliente domicilio
         */
        Gate::define('ver_cliente_domicilios', "App\Policies\ClienteDomicilioPolicy@index");
        Gate::define('crear_cliente_domicilio', 'App\Policies\ClienteDomicilioPolicy@create');

        /**
         * Cliente teléfono
         */
        Gate::define('ver_cliente_telefonos', "App\Policies\ClienteTelefonoPolicy@index");
        Gate::define('crear_cliente_telefono', "App\Policies\ClienteTelefonoPolicy@create");

        /**
         * Cliente email
         */
        Gate::define('ver_cliente_emails', 'App\Policies\ClienteEmailPolicy@index');
        Gate::define('crear_cliente_email', 'App\Policies\ClienteEmailPolicy@create');
    }
}
