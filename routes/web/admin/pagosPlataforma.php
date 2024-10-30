<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'isAdmin'])->namespace('App\Http\Controllers\Admin\PagoSuscripcion')->prefix('admin')->group(function () {

    Route::resource('/pagos-suscripcion', 'PagoSuscripcionController')->names('pagos-plan-sucripcion');
    Route::get('/pagos-suscripcion/ajax/sucursales', 'PagoSuscripcionController@getSuscripcionesAjax')->name('pago-sucripcion.obtener-suscripcion');
    Route::post('/pagos-suscripcion/renovar-suscripcion', 'PagoSuscripcionController@renovarSuscripcion')->name('pago-sucripcion.renovar-suscripcion');
});
