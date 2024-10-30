<?php

use Illuminate\Support\Facades\Route;

Route::namespace('App\Http\Controllers\ClasesPublicas')->group(function () {
    Route::get('validar-clave-supervisor', 'ValidarClaveSupervisorController')->name('validarClaveSupervisor');

    // Articulos
    Route::get('/articulos-paginacion-productos', 'ArticulosController@paginationProductos')->name('articulos.paginar-productos-ajax');
    Route::get('/articulos-paginacion-servicios', 'ArticulosController@paginationServicios')->name('articulos.paginar-servicios-ajax');
    Route::get('/articulos-buscar-productos', 'ArticulosController@searchProducto')->name('articulos.buscar-productos-ajax');
    Route::get('/articulos-buscar-servicios', 'ArticulosController@searchServicio')->name('articulos.buscar-servicios-ajax');

    // Tipo de cambio
    Route::get('/tipo-cambio', 'TipoCambioController@obtenerTipoCambio')->name('tipo-cambio.obtener-ajax');
    Route::post('/tipo-cambio-guardar', 'TipoCambioController@guardarTipoCambio')->name('tipo-cambio.store-ajax');

});
