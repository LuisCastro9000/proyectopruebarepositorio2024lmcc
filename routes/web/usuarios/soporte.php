
<?php

use Illuminate\Support\Facades\Route;

Route::namespace('App\Http\Controllers\Soporte')->group(function () {
    Route::resource('/soporte', 'SoporteController');
    Route::get('/soporte-consultar-facturas', 'SoporteController@consultarFacturas')->name('consultarFacturas');
    Route::get('/soporte-ajax-facturas', 'SoporteController@getFacturasClientesErpConAjax')->name('getFacturas');
    Route::get('/soporte-descargar-factura/{id}/{id1}/{id2}', 'SoporteController@descargarFactura')->name('descargarFacturaClienteErp');
});
