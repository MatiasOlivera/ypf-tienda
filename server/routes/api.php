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
Route::group(['prefix' => 'auth', 'middleware' => ['jwt.auth', ], ], function () {
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

    //mails Group
    Route::apiResource('/mails', 'MailController')
        ->parameters(['mails' => 'mail']);
    //Restaurar mail
    Route::post('/mails/{mail}/restaurar/', 'MailController@restore')->name('mails.restore');

    //Telefonos Group
    Route::apiResource('/telefonos', 'TelefonoController')
        ->parameters(['telefonos' => 'telefono']);
    //Restaurar telefono
    Route::post('/telefonos/{telefono}/restaurar/', 'TelefonoController@restore')->name('telefonos.restore');

    //Razones Group
    Route::apiResource('/razones', 'RazonSocialController')
        ->parameters(['razones' => 'razonSocial']);
    //Restaurar razon
    Route::post('/razones/{razonSocial}/restaurar/', 'RazonSocialController@restore')->name('razones.restore');
});
