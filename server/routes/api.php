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
Route::post('/auth/login', 'AuthController@login');

/**
 * Registro
 */
Route::post('/usuarios', 'UsersController@store');

/**
 * Autenticación
 */
Route::group(['prefix' => 'auth', 'middleware' => ['jwt.auth',],], function () {
    Route::get('usuario', 'AuthController@usuario');
    Route::post('renovar', 'AuthController@renovar');
    Route::post('logout', 'AuthController@logout');
});

/**
 * Productos
 */
Route::apiResource('productos', 'ProductosController')
    ->only(['index', 'show'])
    ->parameters(['productos' => 'producto']);

/**
 * Categorías de producto
 */
Route::apiResource('categorias-productos', 'CategoriaProductoController')
    ->only(['index', 'show'])
    ->parameters(['categorias-productos' => 'categoriaProducto']);

Route::middleware('jwt.auth')->group(function () {
    /**
     * Usuarios
     */
    Route::apiResource('/usuarios', 'UsersController')->parameters(['usuarios' => 'user']);

    Route::post('/usuarios/{user}/restaurar/', 'UsersController@restore')->name('usuarios.restore');

    /**
     * Clientes
     */
    Route::group(['prefix' => 'clientes',], function () {
        Route::get('/', 'ClientesController@index')->name('clientes.index');

        Route::post('/', 'ClientesController@store')->name('clientes.store');

        Route::get('/{cliente}', 'ClientesController@show')->name('clientes.show');

        Route::put('/{cliente}', 'ClientesController@update')->name('clientes.update');

        Route::delete('/{cliente}', 'ClientesController@destroy')->name('clientes.destroy');

        Route::post('/{cliente}/restaurar/', 'ClientesController@restore')->name('clientes.restore');

        /*
         *  Domicilio
         */
        Route::get('/{cliente}/domicilios', 'ClienteDomicilioController@index')->name('ClienteDomicilio.index');

        Route::post('/{cliente}/domicilios', 'ClienteDomicilioController@store')->name('ClienteDomicilio.store');

        Route::get('/{cliente}/domicilios/{domicilio}', 'ClienteDomicilioController@show')
            ->name('ClienteDomicilio.show');

        Route::put('/{cliente}/domicilios/{domicilio}', 'ClienteDomicilioController@update')
            ->name('ClienteDomicilio.update');

        Route::delete('/{cliente}/domicilios/{domicilio}', 'ClienteDomicilioController@destroy')
            ->name('ClienteDomicilio.destroy');

        Route::post('/{cliente}/domicilios/{domicilio}/restaurar/', 'ClienteDomicilioController@restore')
            ->name('ClienteDomicilio.restore');

        /*
         *  Teléfono
         */
        Route::get('/{cliente}/telefonos', 'ClienteTelefonoController@index')->name('ClienteTelefono.index');

        Route::post('/{cliente}/telefonos', 'ClienteTelefonoController@store')->name('ClienteTelefono.store');

        Route::get('/{cliente}/telefonos/{telefono}', 'ClienteTelefonoController@show')->name('ClienteTelefono.show');

        Route::put('/{cliente}/telefonos/{telefono}', 'ClienteTelefonoController@update')
            ->name('ClienteTelefono.update');

        Route::delete('/{cliente}/telefonos/{telefono}', 'ClienteTelefonoController@destroy')
            ->name('ClienteTelefono.destroy');

        Route::post('/{cliente}/telefonos/{telefono}/restaurar/', 'ClienteTelefonoController@restore')
            ->name('ClienteTelefonos.restore');

        /*
         *  Emails
         */
        Route::get('/{cliente}/emails', 'ClienteMailController@index')->name('ClienteMails.index');

        Route::post('/{cliente}/emails', 'ClienteMailController@store')->name('ClienteMails.store');

        Route::get('/{cliente}/emails/{mail}', 'ClienteMailController@show')->name('ClienteMails.show');

        Route::put('/{cliente}/emails/{mail}', 'ClienteMailController@update')->name('ClienteMails.update');

        Route::delete('/{cliente}/emails/{mail}', 'ClienteMailController@destroy')->name('ClienteMails.destroy');

        Route::post('/{cliente}/emails/{mail}/restaurar/', 'ClienteMailController@restore')
            ->name('ClienteMails.restore');

        /*
         *  Razón social
         */
        Route::group(['prefix' => '/{cliente}/razones',], function () {
            Route::get('/', 'ClienteRazonSocialController@index')->name('RazonesCliente');

            Route::post('/', 'ClienteRazonSocialController@store')->name('CrearRazonCliente');

            Route::post('/{razonSocial}/asociar', 'ClienteRazonSocialController@asociar')
                ->name('AsociarRazonesCliente');

            Route::delete('/{razonSocial}/desasociar', 'ClienteRazonSocialController@desasociar')
                ->name('DesasociarRazonesCliente');


            Route::get('/{razonSocial}', 'ClienteRazonSocialController@show')->name('ClienteRazones.show');

            Route::put('/{razonSocial}', 'ClienteRazonSocialController@update')->name('ClienteRazones.update');

            Route::delete('/{razonSocial}', 'ClienteRazonSocialController@destroy')->name('ClienteRazones.destroy');

            Route::post('/{razonSocial}/restaurar/', 'ClienteRazonSocialController@restore')
                ->name('ClienteRazones.restore');
        });
    });

     /**
     * Provincia
     */
    Route::apiResource('/provincias', 'ProvinciaController')->parameters(['provincias' => 'provincia']);

    Route::post('provincias/{provincia}/restaurar', 'ProvinciaController@restore')
        ->name('provincias.restore');

    /**
     * Localidad
     */
    Route::group(['prefix' => '/provincias',], function () {
        Route::get('/{provincia}/localidades', 'LocalidadController@index')->name('Localidad.index');
    });

    Route::group(['prefix' => '/localidades',], function () {
        Route::post('/', 'LocalidadController@store')->name('Localidad.store');

        Route::get('/{localidad}', 'LocalidadController@show')->name('Localidad.show');

        Route::put('/{localidad}', 'LocalidadController@update')->name('Localidad.update');

        Route::delete('/{localidad}', 'LocalidadController@destroy')->name('Localidad.destroy');

        Route::post('/{localidad}/restaurar/', 'LocalidadController@restore')->name('Localidad.restore');
    });

    /**
     * Categorias de producto
     */
    Route::apiResource('categorias-productos', 'CategoriaProductoController')
        ->except(['index', 'show'])
        ->parameters(['categorias-productos' => 'categoriaProducto']);

    Route::post('categorias-productos/{categoriaProducto}/restaurar', 'CategoriaProductoController@restore')
        ->name('categorias.restore');

    /**
     * Productos
     */
    Route::apiResource('productos', 'ProductosController')
        ->except(['index', 'show'])
        ->parameters(['productos' => 'producto']);

    Route::post('productos/{producto}/restaurar', 'ProductosController@restore')
        ->name('productos.restore');

    /**
     * Productos favoritos
     */
    Route::post('productos/{producto}/favorito', 'ProductosFavoritosController@asociar')
        ->name('productos.es_favorito');

    Route::delete('productos/{producto}/favorito', 'ProductosFavoritosController@desasociar')
        ->name('productos.no_es_favorito');

    /**
     * Cotizaciones
     */
    Route::apiResource('cotizaciones', 'CotizacionController')
        ->parameters(['cotizaciones' => 'cotizacion']);

    Route::group(['prefix' => 'cotizaciones',], function () {
        Route::put('/{cotizacion}/productos', 'CotizacionProductoController@update')
            ->name('CotizacionProducto.update');

        Route::delete('/productos/{cotizacion_producto}', 'CotizacionProductoController@destroy')
            ->name('CotizacionProducto.destroy');
    });

    /**
     * Pedidos
     */
    Route::apiResource('pedidos', 'PedidoController')
        ->parameters(['pedidos' => 'pedido']);
});
