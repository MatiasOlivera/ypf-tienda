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

Route::middleware('jwt.auth')->group(function () {
    //usuarios Group
    Route::apiResource('/usuarios', 'UsersController')
        ->parameters(['usuarios' => 'user']);

    //Restaurar usuario
    Route::post('/usuarios/{user}/restaurar/', 'UsersController@restore')->name('usuarios.restore');

    //clientes Group
    Route::apiResource('/clientes', 'ClientesController')
        ->parameters(['clientes' => 'cliente']);
    //Restaurar cliente
    Route::post('/clientes/{cliente}/restaurar/', 'ClientesController@restore')->name('clientes.restore');

    /*
     *  cliente Domicilio
     */
    Route::get('clientes/{cliente}/domicilios', 'ClienteDomicilioController@index')->name('DomiciliosCliente');

    Route::post('clientes/{cliente}/domicilios', 'ClienteDomicilioController@store')->name('CrearDomiciliosCliente');

    Route::put('clientes/{cliente}/domicilios/{domicilio}', 'ClienteDomicilioController@update')->name('UpdateDomicilioCliente');

    /*
     *  cliente Telefono
     */

    Route::get('clientes/{cliente}/telefonos', 'ClienteTelefonoController@index')->name('TelefonosCliente');

    Route::post('clientes/{cliente}/telefono', 'ClienteTelefonoController@store')->name('CrearTelefonosCliente');

    Route::put('clientes/{cliente}/telefono/{telefono}', 'ClienteTelefonoController@update')->name('UpdateTelefonosCliente');

    /*
     *  cliente Mails
     */

    Route::get('clientes/{cliente}/emails', 'ClienteMailController@index')->name('MailsCliente');

    Route::post('clientes/{cliente}/emails', 'ClienteMailController@store')->name('CrearMailsCliente');

    Route::put('clientes/{cliente}/emails/{mail}', 'ClienteMailController@update')->name('UpdateMailsCliente');

    /*
     *  cliente Razon
     */
    Route::get('clientes/{cliente}/razones', 'ClienteRazonSocialController@index')->name('RazonesCliente');

    Route::post('clientes/{cliente}/razon', 'ClienteRazonSocialController@store')->name('CrearRazonCliente');

    Route::post('clientes/{cliente}/razon/{razonSocial}', 'ClienteRazonSocialController@asociar')->name('AsociarRazonesCliente');

    Route::delete('clientes/{cliente}/razon/{razonSocial}', 'ClienteRazonSocialController@desasociar')->name('DesasociarRazonesCliente');



    //mails Group
    Route::apiResource('/mails', 'ClienteMailController')
        ->parameters(['mails' => 'mail']);
    //Restaurar mail
    Route::post('/mails/{mail}/restaurar/', 'ClienteMailController@restore')->name('mails.restore');

    //Telefonos Group
    Route::apiResource('/telefonos', 'ClienteTelefonoController')
        ->parameters(['telefonos' => 'telefono']);
    //Restaurar telefono
    Route::post('/telefonos/{telefono}/restaurar/', 'ClienteTelefonoController@restore')->name('telefonos.restore');

    //Razones Group
    Route::apiResource('/razones', 'ClienteRazonSocialController')
        ->parameters(['razones' => 'razonSocial']);
    //Restaurar razon
    Route::post('/razones/{razonSocial}/restaurar/', 'ClienteRazonSocialController@restore')->name('razones.restore');

    //Domicilios Group
    Route::apiResource('/razones', 'ClienteDomicilioController')
        ->parameters(['domicilios' => 'domicilio']);
    //Restaurar domicilio
    Route::post('/razones/{razonSocial}/restaurar/', 'ClienteRazonSocialController@restore')->name('razones.restore');
});
