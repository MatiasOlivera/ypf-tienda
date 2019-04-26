<?php

namespace App\Providers;

use App\User;
use App\Cliente;
use App\ClienteDomicilio;
use App\ClienteMail;
use App\ClienteTelefono;
use App\ClienteRazonSocial;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();

        //User
        Route::bind('user', function ($id) {
            return User::withTrashed()
                ->where('id', $id)
                ->firstOrFail();
        });

        //Cliente
        Route::bind('cliente', function ($id) {
            return Cliente::withTrashed()
                ->where('id_cliente', $id)
                ->firstOrFail();
        });

        //ClienteDomicilio
        Route::bind('domicilio', function ($id) {
            return ClienteDomicilio::withTrashed()
                ->where('id_dom', $id)
                ->firstOrFail();
        });

        //ClienteMail
        Route::bind('mail', function ($id) {
            return ClienteMail::withTrashed()
                ->where('id', $id)
                ->firstOrFail();
        });

        //ClienteTelefono
        Route::bind('telefono', function ($id) {
            return ClienteTelefono::withTrashed()
                ->where('id', $id)
                ->firstOrFail();
        });

        //ClienteRazonSocial
        Route::bind('razonSocial', function ($id) {
            return ClienteRazonSocial::withTrashed()
                ->where('id_razon', $id)
                ->firstOrFail();
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}
