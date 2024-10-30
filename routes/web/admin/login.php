<?php

use Illuminate\Support\Facades\Route;

// RUTAS LOGIN
Route::namespace('App\Http\Controllers\Admin')->prefix('admin')->group(function () {
    Route::get('/inicia-sesion', 'LoginAdminController')->name('admin.login');
    Route::post('/login', 'LoginAdminController@loguin')->name('admin.inicia-sesion');
    Route::get('/cerrar-sesion', 'LoginAdminController@logout')->name('admin.cerrar-sesion');
});

// RUTAS REGISTRAR PAGO PLATAFORMA
Route::namespace('App\Http\Controllers\PagoSuscripcion')->group(function () {
    Route::get('/registrar-pago', 'PagoSuscripcionController@create')->name('registro-pago.create');
    Route::post('/registrar-pago/store', 'PagoSuscripcionController@store')->name('registro-pago.store');
    Route::get('/registrar-pago/ajax/consulta-ruc', 'PagoSuscripcionController@consultarRuc')->name('registro-pago.consulta-ruc');
});
