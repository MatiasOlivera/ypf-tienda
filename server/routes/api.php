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


//login

Route::post('/auth/login',  'AuthController@login');

//Registro
Route::post('/usuarios', 'UsersController@store');

//Auth Group
Route::group(['prefix' => 'auth', 'middleware' => ['jwt.auth',],], function () {
    Route::get('usuario', 'AuthController@usuario');
    Route::post('renovar', 'AuthController@renovar');
    Route::post('logout', 'AuthController@logout');
});

//Provincias
Route::get('/provincias', 'ProvinciaController@index')->name('Provincia.index');

//localidades
Route::get('/provincias/{provincia}/localidades', 'LocalidadController@index')->name('Localidad.index');

Route::middleware('jwt.auth')->group(function () {
    //usuarios Group
    Route::apiResource('/usuarios', 'UsersController')->parameters(['usuarios' => 'user']);

    Route::post('/usuarios/{user}/restaurar/', 'UsersController@restore')->name('usuarios.restore');

    Route::group(['prefix' => 'clientes',], function () {

        /*
        *  clientes Group
        */
        Route::get('/', 'ClientesController@index')->name('clientes.index');

        Route::post('/', 'ClientesController@store')->name('clientes.store');

        Route::get('/{cliente}', 'ClientesController@show')->name('clientes.show');

        Route::put('/{cliente}', 'ClientesController@update')->name('clientes.update');

        Route::delete('/{cliente}', 'ClientesController@destroy')->name('clientes.destroy');

        Route::post('/{cliente}/restaurar/', 'ClientesController@restore')->name('clientes.restore');

        /*
         *  cliente Domicilio
         */
        Route::get('/{cliente}/domicilios', 'ClienteDomicilioController@index')->name('ClienteDomicilio.index');

        Route::post('/{cliente}/domicilios', 'ClienteDomicilioController@store')->name('ClienteDomicilio.store');

        Route::get('/domicilios/{domicilio}', 'ClienteDomicilioController@show')->name('ClienteDomicilio.show');

        Route::put('/domicilios/{domicilio}', 'ClienteDomicilioController@update')->name('ClienteDomicilio.update');

        Route::delete('/domicilios/{domicilio}', 'ClienteDomicilioController@destroy')->name('ClienteDomicilio.destroy');

        Route::post('/domicilios/{domicilio}/restaurar/', 'ClienteDomicilioController@restore')->name('ClienteDomicilio.restore');

        /*
         *  cliente Telefono
         */
        Route::get('/{cliente}/telefonos', 'ClienteTelefonoController@index')->name('ClienteTelefono.index');

        Route::post('/{cliente}/telefonos', 'ClienteTelefonoController@store')->name('ClienteTelefono.store');

        Route::get('/telefonos/{telefono}', 'ClienteTelefonoController@show')->name('ClienteTelefono.show');

        Route::put('/telefonos/{telefono}', 'ClienteTelefonoController@update')->name('ClienteTelefono.update');

        Route::delete('/telefonos/{telefono}', 'ClienteTelefonoController@destroy')->name('ClienteTelefono.destroy');

        Route::post('/telefonos/{telefono}/restaurar/', 'ClienteTelefonoController@restore')->name('ClienteTelefonos.restore');

        /*
         *  cliente Mails
         */
        Route::get('/{cliente}/emails', 'ClienteMailController@index')->name('ClienteMails.index');

        Route::post('/{cliente}/emails', 'ClienteMailController@store')->name('ClienteMails.store');

        Route::get('/emails/{mail}', 'ClienteMailController@show')->name('ClienteMails.show');

        Route::put('/emails/{mail}', 'ClienteMailController@update')->name('ClienteMails.update');

        Route::delete('/emails/{mail}', 'ClienteMailController@destroy')->name('ClienteMails.destroy');

        Route::post('/emails/{mail}/restaurar/', 'ClienteMailController@restore')->name('ClienteMails.restore');

        /*
         *  cliente Razon
         */
        Route::get('/{cliente}/razones', 'ClienteRazonSocialController@index')->name('RazonesCliente');

        Route::post('/{cliente}/razones', 'ClienteRazonSocialController@store')->name('CrearRazonCliente');

        Route::post('/{cliente}/razones/{razonSocial}', 'ClienteRazonSocialController@asociar')->name('AsociarRazonesCliente');

        Route::delete('/{cliente}/razones/{razonSocial}', 'ClienteRazonSocialController@desasociar')->name('DesasociarRazonesCliente');

        Route::group(['prefix' => '/razones',], function () {
            Route::get('/{razonSocial}', 'ClienteRazonSocialController@show')->name('ClienteRazones.show');

            Route::put('/{razonSocial}', 'ClienteRazonSocialController@update')->name('ClienteRazones.update');

            Route::delete('/{razonSocial}', 'ClienteRazonSocialController@destroy')->name('ClienteRazones.destroy');

            Route::post('/{razonSocial}/restaurar/', 'ClienteRazonSocialController@restore')->name('ClienteRazones.restore');
        });
    });
});
