<?php

namespace App\Providers;

use App\Cliente;
use App\Producto;
use App\Localidad;
use App\Provincia;
use App\ClienteMail;
use App\ClienteUsuario;
use App\ClienteTelefono;
use App\ClienteDomicilio;
use App\CategoriaProducto;
use App\ClienteRazonSocial;
use App\Policies\ClientePolicy;
use App\Policies\ProductoPolicy;
use App\Policies\LocalidadPolicy;
use App\Policies\ProvinciaPolicy;
use App\Policies\ClienteEmailPolicy;
use Illuminate\Support\Facades\Gate;
use App\Policies\ClienteUsuarioPolicy;
use App\Policies\ClienteTelefonoPolicy;
use App\Policies\ClienteDomicilioPolicy;
use App\Policies\CategoriaProductoPolicy;
use App\Policies\ClienteRazonSocialPolicy;
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
        ClienteRazonSocial::class => ClienteRazonSocialPolicy::class,
        ClienteUsuario::class => ClienteUsuarioPolicy::class,
        Provincia::class => ProvinciaPolicy::class,
        Localidad::class => LocalidadPolicy::class,
        CategoriaProducto::class => CategoriaProductoPolicy::class,
        Producto::class => ProductoPolicy::class,
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

        /**
         * Cliente razón social
         */
        Gate::define('ver_cliente_razones', 'App\Policies\ClienteRazonSocialPolicy@index');
        Gate::define('crear_cliente_razon', 'App\Policies\ClienteRazonSocialPolicy@create');
        Gate::define('asociar_cliente_y_razon_social', 'App\Policies\ClienteRazonSocialPolicy@asociar');
        Gate::define('desasociar_cliente_y_razon_social', 'App\Policies\ClienteRazonSocialPolicy@desasociar');

        /**
         * Localidad
         */
        Gate::define('ver_localidades', 'App\Policies\LocalidadPolicy@list');
    }
}
