
<?php

use Illuminate\Support\Facades\Route;

// RUTAS ADMINISTRACION
Route::middleware(['web', 'isAdmin'])->namespace('App\Http\Controllers\Admin\Administracion')->prefix('admin/administracion')->group(function () {

    // Rutas administracion Cotizaciones
    Route::get('/cotizaciones', 'CotizacionesController@index')->name('admin.cotizaciones.index');
    Route::get('/cotizaciones/ajax/obtener-cotizaciones', 'CotizacionesController@getCotizacionesAjax')->name('admin.cotizaciones.obtener');
    Route::put('/cotizaciones/ajax/datos/actualizar-estado', 'CotizacionesController@updateEstado')->name('admin.cotizaciones.update-estado');

    // Rutas administracion Ventas
    Route::get('/ventas', 'VentasController@index')->name('admin.ventas.index');
    Route::get('/ventas/buscar-articulos', 'VentasController@buscarArticulos')->name('admin.ventas.buscar');
    Route::put('/ventas/reponer-stock-articulos', 'VentasController@reponerStock')->name('admin.ventas.reponer-stock');
    Route::put('/ventas/actualizar-hash-qr', 'VentasController@actualizarHashAndQr')->name('admin.ventas.actualizar-hash-qr');

    // Rutas administracion TipoCambio
    Route::get('/tipo-cambio', 'TipoCambioController@index')->name('admin.tipo-cambio.index');
    Route::get('/tipo-cambio/obtener', 'TipoCambioController@obtenerTipoCambioDelDia')->name('admin.tipo-cambio.obtener');
    Route::put('/tipo-cambio/actualizar', 'TipoCambioController@actualizartipoCambio')->name('admin.tipo-cambio.actualizar');

});
