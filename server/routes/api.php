<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


/**
 * Login
 */
Route::post('/auth/cliente/login', 'AuthController@clienteLogin');
Route::post('/auth/empleado/login', 'AuthController@empleadoLogin');

/**
 * Registro
 */
Route::post('/clientes/usuarios', 'ClienteUsuarioController@store');

/**
 * Autenticación
 */
Route::group(['prefix' => 'auth', 'middleware' => ['auth.tipo', 'jwt.auth',],], function () {
    Route::get('usuario', 'AuthController@usuario');
    Route::post('renovar', 'AuthController@renovar');
    Route::post('logout', 'AuthController@logout');
});

/**
 * Productos
 */
Route::name('publica.')->prefix('publica')->group(function () {
    Route::name('productos.')->prefix('productos')->group(function () {
        Route::get('/', 'ProductosController@index')
            ->middleware('can:list,App\Producto')
            ->name('index');

        Route::get('/{producto}', 'ProductosController@show')
            ->middleware('can:view,producto')
            ->name('show');
    });
});

/**
 * Categorías de producto
 */
Route::name('categorias-productos.')->prefix('categorias-productos')->group(function () {
    Route::get('/', 'CategoriaProductoController@index')
        ->middleware('can:list,App\CategoriaProducto')
        ->name('index');

    Route::get('/{categoriaProducto}', 'CategoriaProductoController@show')
        ->middleware('can:view,categoriaProducto')
        ->name('show');
});

Route::middleware(['auth.tipo', 'jwt.auth'])->group(function () {
    /**
     * Clientes
     */
    Route::group(['prefix' => 'clientes',], function () {
        /**
         * Usuarios
         */
        Route::name('usuarios.')->prefix('usuarios')->group(function () {
            Route::get('/', 'ClienteUsuarioController@index')
                ->middleware('can:list,App\ClienteUsuario')
                ->name('index');

            Route::post('/', 'ClienteUsuarioController@store')
                ->middleware('can:create,App\ClienteUsuario')
                ->name('store');

            Route::get('/{user}', 'ClienteUsuarioController@show')
                ->middleware('can:view,user')
                ->name('show');

            Route::put('/{user}', 'ClienteUsuarioController@update')
                ->middleware('can:update,user')
                ->name('update');

            Route::delete('/{user}', 'ClienteUsuarioController@destroy')
                ->middleware('can:delete,user')
                ->name('destroy');

            Route::post('/{user}/restaurar/', 'ClienteUsuarioController@restore')
                ->middleware('can:restore,user')
                ->name('restore');
        });

        Route::get('/', 'ClientesController@index')->name('clientes.index');

        Route::post('/', 'ClientesController@store')->name('clientes.store');

        Route::get('/{cliente}', 'ClientesController@show')->name('clientes.show');

        Route::put('/{cliente}', 'ClientesController@update')->name('clientes.update');

        Route::delete('/{cliente}', 'ClientesController@destroy')->name('clientes.destroy');

        Route::post('/{cliente}/restaurar/', 'ClientesController@restore')->name('clientes.restore');

        /*
         *  Domicilio
         */
        Route::get('/{cliente}/domicilios', 'ClienteDomicilioController@index')
            ->middleware('can:ver_cliente_domicilios,cliente')
            ->name('ClienteDomicilio.index');

        Route::post('/{cliente}/domicilios', 'ClienteDomicilioController@store')
            ->middleware('can:crear_cliente_domicilio,cliente')
            ->name('ClienteDomicilio.store');

        Route::get('/{cliente}/domicilios/{domicilio}', 'ClienteDomicilioController@show')
            ->middleware('can:view,domicilio')
            ->name('ClienteDomicilio.show');

        Route::put('/{cliente}/domicilios/{domicilio}', 'ClienteDomicilioController@update')
            ->middleware('can:update,domicilio')
            ->name('ClienteDomicilio.update');

        Route::delete('/{cliente}/domicilios/{domicilio}', 'ClienteDomicilioController@destroy')
            ->middleware('can:delete,domicilio')
            ->name('ClienteDomicilio.destroy');

        Route::post('/{cliente}/domicilios/{domicilio}/restaurar/', 'ClienteDomicilioController@restore')
            ->middleware('can:restore,domicilio')
            ->name('ClienteDomicilio.restore');

        /*
         *  Teléfono
         */
        Route::get('/{cliente}/telefonos', 'ClienteTelefonoController@index')
            ->middleware('can:ver_cliente_telefonos,cliente')
            ->name('ClienteTelefono.index');

        Route::post('/{cliente}/telefonos', 'ClienteTelefonoController@store')
            ->middleware('can:crear_cliente_telefono,cliente')
            ->name('ClienteTelefono.store');

        Route::get('/{cliente}/telefonos/{telefono}', 'ClienteTelefonoController@show')
            ->middleware('can:view,telefono')
            ->name('ClienteTelefono.show');

        Route::put('/{cliente}/telefonos/{telefono}', 'ClienteTelefonoController@update')
            ->middleware('can:update,telefono')
            ->name('ClienteTelefono.update');

        Route::delete('/{cliente}/telefonos/{telefono}', 'ClienteTelefonoController@destroy')
            ->middleware('can:delete,telefono')
            ->name('ClienteTelefono.destroy');

        Route::post('/{cliente}/telefonos/{telefono}/restaurar/', 'ClienteTelefonoController@restore')
            ->middleware('can:restore,telefono')
            ->name('ClienteTelefonos.restore');

        /*
         *  Emails
         */
        Route::get('/{cliente}/emails', 'ClienteMailController@index')
            ->middleware('can:ver_cliente_emails,cliente')
            ->name('ClienteMails.index');

        Route::post('/{cliente}/emails', 'ClienteMailController@store')
            ->middleware('can:crear_cliente_email,cliente')
            ->name('ClienteMails.store');

        Route::get('/{cliente}/emails/{mail}', 'ClienteMailController@show')
            ->middleware('can:view,mail')
            ->name('ClienteMails.show');

        Route::put('/{cliente}/emails/{mail}', 'ClienteMailController@update')
            ->middleware('can:update,mail')
            ->name('ClienteMails.update');

        Route::delete('/{cliente}/emails/{mail}', 'ClienteMailController@destroy')
            ->middleware('can:delete,mail')
            ->name('ClienteMails.destroy');

        Route::post('/{cliente}/emails/{mail}/restaurar/', 'ClienteMailController@restore')
            ->middleware('can:restore,mail')
            ->name('ClienteMails.restore');

        /*
         *  Razón social
         */
        Route::group(['prefix' => '/{cliente}/razones',], function () {
            Route::get('/', 'ClienteRazonSocialController@index')
                ->middleware('can:ver_cliente_razones,cliente')
                ->name('RazonesCliente');

            Route::post('/', 'ClienteRazonSocialController@store')
                ->middleware('can:crear_cliente_razon,cliente')
                ->name('CrearRazonCliente');

            Route::post('/{razonSocial}/asociar', 'ClienteRazonSocialController@asociar')
                ->middleware('can:asociar_cliente_y_razon_social,cliente,razonSocial')
                ->name('AsociarRazonesCliente');

            Route::delete('/{razonSocial}/desasociar', 'ClienteRazonSocialController@desasociar')
                ->middleware('can:desasociar_cliente_y_razon_social,cliente,razonSocial')
                ->name('DesasociarRazonesCliente');

            Route::get('/{razonSocial}', 'ClienteRazonSocialController@show')
                ->middleware('can:view,razonSocial')
                ->name('ClienteRazones.show');

            Route::put('/{razonSocial}', 'ClienteRazonSocialController@update')
                ->middleware('can:update,razonSocial')
                ->name('ClienteRazones.update');

            Route::delete('/{razonSocial}', 'ClienteRazonSocialController@destroy')
                ->middleware('can:delete,razonSocial')
                ->name('ClienteRazones.destroy');

            Route::post('/{razonSocial}/restaurar/', 'ClienteRazonSocialController@restore')
                ->middleware('can:restore,razonSocial')
                ->name('ClienteRazones.restore');
        });

        /**
         * Cotizaciones
         */
        Route::name('cotizaciones.')->group(function () {
            Route::prefix('{cliente}/cotizaciones')->group(function () {
                Route::get('/', 'CotizacionController@indexByClienteId')
                    ->name('indexByClienteId');

                Route::post('/', 'CotizacionController@store')
                    ->name('store');
            });

            Route::prefix('cotizaciones')->group(function () {
                Route::get('/{cotizacion}', 'CotizacionController@show')
                    ->name('show');

                Route::put('/{cotizacion}', 'CotizacionController@update')
                    ->name('update');

                Route::delete('/{cotizacion}', 'CotizacionController@destroy')
                    ->name('destroy');
            });
        });
    });

     /**
     * Provincia
     */
    Route::name('provincias.')->prefix('provincias')->group(function () {
        Route::get('/', 'ProvinciaController@index')
            ->middleware('can:list,App\Provincia')
            ->name('index');

        Route::post('/', 'ProvinciaController@store')
            ->middleware('can:create,App\Provincia')
            ->name('store');

        Route::get('/{provincia}', 'ProvinciaController@show')
            ->middleware('can:view,provincia')
            ->name('show');

        Route::put('/{provincia}', 'ProvinciaController@update')
            ->middleware('can:update,provincia')
            ->name('update');

        Route::delete('/{provincia}', 'ProvinciaController@destroy')
            ->middleware('can:delete,provincia')
            ->name('destroy');

        Route::post('/{provincia}/restaurar/', 'ProvinciaController@restore')
            ->middleware('can:restore,provincia')
            ->name('restore');
    });

    /**
     * Localidad
     */
    Route::group(['prefix' => '/provincias',], function () {
        Route::get('/{provincia}/localidades', 'LocalidadController@index')
            ->middleware('can:ver_localidades,provincia')
            ->name('Localidad.index');
    });

    Route::group(['prefix' => '/localidades',], function () {
        Route::post('/', 'LocalidadController@store')
            ->middleware('can:create,App\Localidad')
            ->name('Localidad.store');

        Route::get('/{localidad}', 'LocalidadController@show')
            ->middleware('can:view,localidad')
            ->name('Localidad.show');

        Route::put('/{localidad}', 'LocalidadController@update')
            ->middleware('can:update,localidad')
            ->name('Localidad.update');

        Route::delete('/{localidad}', 'LocalidadController@destroy')
            ->middleware('can:delete,localidad')
            ->name('Localidad.destroy');

        Route::post('/{localidad}/restaurar/', 'LocalidadController@restore')
            ->middleware('can:restore,localidad')
            ->name('Localidad.restore');
    });

    /**
     * Categorias de producto
     */
    Route::name('categorias-productos.')->prefix('categorias-productos')->group(function () {
        Route::post('/', 'CategoriaProductoController@store')
            ->middleware('can:create,App\CategoriaProducto')
            ->name('store');

        Route::put('/{categoriaProducto}', 'CategoriaProductoController@update')
            ->middleware('can:update,categoriaProducto')
            ->name('update');

        Route::delete('/{categoriaProducto}', 'CategoriaProductoController@destroy')
            ->middleware('can:delete,categoriaProducto')
            ->name('destroy');

        Route::post('/{categoriaProducto}/restaurar/', 'CategoriaProductoController@restore')
            ->middleware('can:restore,categoriaProducto')
            ->name('restore');
    });

    /**
     * Productos
     */
    Route::name('productos.')->prefix('productos')->group(function () {
        Route::get('/', 'ProductosController@index')
            ->middleware('can:list,App\Producto')
            ->name('index');

        Route::get('/{producto}', 'ProductosController@show')
            ->middleware('can:view,producto')
            ->name('show');

        Route::post('/', 'ProductosController@store')
            ->middleware('can:create,App\Producto')
            ->name('store');

        Route::put('/{producto}', 'ProductosController@update')
            ->middleware('can:update,producto')
            ->name('update');

        Route::delete('/{producto}', 'ProductosController@destroy')
            ->middleware('can:delete,producto')
            ->name('destroy');

        Route::post('/{producto}/restaurar/', 'ProductosController@restore')
            ->middleware('can:restore,producto')
            ->name('restore');
    });

    /**
     * Productos favoritos
     */
    Route::post('productos/{producto}/favorito', 'ProductosFavoritosController@asociar')
        ->middleware('can:administrar_favoritos')
        ->name('productos.es_favorito');

    Route::delete('productos/{producto}/favorito', 'ProductosFavoritosController@desasociar')
        ->middleware('can:administrar_favoritos')
        ->name('productos.no_es_favorito');

    /**
     * Cotizaciones
     */
    Route::get('/cotizaciones', 'CotizacionController@index')
        ->name('cotizaciones.index');

    Route::group(['prefix' => 'cotizaciones',], function () {
        Route::put('/{cotizacion}/productos', 'CotizacionProductoController@update')
            ->name('CotizacionProducto.update');

        Route::delete('/productos/{cotizacion_producto}', 'CotizacionProductoController@destroy')
            ->name('CotizacionProducto.destroy');
    });
});
