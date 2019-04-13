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

    /*
    *  clientes Group
    */

    Route::post('/clientes/{cliente}/restaurar/', 'ClientesController@restore')->name('clientes.restore');

    Route::apiResource('/clientes', 'ClientesController');

    Route::group(['prefix' => 'clientes',], function () {
        /*
         *  cliente Domicilio
         */
        Route::get('/{cliente}/domicilios', 'ClienteDomicilioController@index')->name('DomiciliosCliente');

        Route::post('/{cliente}/domicilios', 'ClienteDomicilioController@store')->name('CrearDomiciliosCliente');

        Route::apiResource('/domicilios', 'ClienteDomicilioController')->parameters(['domicilios' => 'domicilio']);

        Route::post('/domicilios/{domicilio}/restaurar/', 'ClienteDomicilioController@restore')->name('ClienteDomicilio.restore');

        /*
         *  cliente Telefono
         */
        Route::get('/{cliente}/telefonos', 'ClienteTelefonoController@index')->name('TelefonosCliente');

        Route::post('/{cliente}/telefono', 'ClienteTelefonoController@store')->name('CrearTelefonosCliente');

        Route::apiResource('/telefonos', 'ClienteTelefonoController')->parameters(['telefonos' => 'telefono']);

        Route::post('/telefonos/{telefono}/restaurar/', 'ClienteTelefonoController@restore')->name('ClienteTelefonos.restore');

        /*
         *  cliente Mails
         */
        Route::get('/{cliente}/emails', 'ClienteMailController@index')->name('MailsCliente');

        Route::post('/{cliente}/emails', 'ClienteMailController@store')->name('CrearMailsCliente');

        Route::apiResource('/mails', 'ClienteMailController')->parameters(['mails' => 'mail']);

        Route::post('/mails/{mail}/restaurar/', 'ClienteMailController@restore')->name('ClienteMails.restore');

        /*
         *  cliente Razon
         */
        Route::get('/{cliente}/razones', 'ClienteRazonSocialController@index')->name('RazonesCliente');

        Route::post('/{cliente}/razon', 'ClienteRazonSocialController@store')->name('CrearRazonCliente');

        Route::post('/{cliente}/razon/{razonSocial}', 'ClienteRazonSocialController@asociar')->name('AsociarRazonesCliente');

        Route::delete('/{cliente}/razon/{razonSocial}', 'ClienteRazonSocialController@desasociar')->name('DesasociarRazonesCliente');
    });

    /**
     * Razones Group
     */
    // Route::group(['prefix' => 'razones',], function () {
    // Route::apiResource('/razones', 'ClienteRazonSocialController')->parameters(['razones' => 'razonSocial']);

    // Route::post('/razones/{razonSocial}/restaurar/', 'ClienteRazonSocialController@restore')->name('ClienteRazones.restore');
    // });
});
