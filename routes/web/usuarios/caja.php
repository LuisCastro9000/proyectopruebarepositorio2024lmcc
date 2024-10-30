<?php

use Illuminate\Support\Facades\Route;

// RUTAS CAJA
Route::namespace('App\Http\Controllers\Caja')->prefix('caja')->group(function () {
    // ruta apertura-Cierre-Caja
    Route::get('/cierre-caja', 'CierreCajaController');
    Route::post('/abrir-caja', 'CierreCajaController@abrirCaja');
    Route::post('/cerrar-caja', 'CierreCajaController@cerrarCaja');
    Route::post('/cierre-caja/enviar-correo/{id}', 'CierreCajaController@enviarCorreo');
    Route::post('/cierre-caja/imprimir', 'CierreCajaController@imprimir');

    // ruta ingresosEgresos
    Route::get('/ingresos-egresos', 'IngresosEgresosController');
    Route::post('/generar-ingreso', 'IngresosEgresosController@generarIngreso');
    Route::post('/generar-egreso', 'IngresosEgresosController@generarEgreso');
    Route::post('/actualizar-egreso-ingreso', 'IngresosEgresosController@actualizarIngresoEgreso');
    Route::get('/comprobar-clave', 'IngresosEgresosController@comprobarClave');
    Route::get('/traer-gastos', 'IngresosEgresosController@listarGastos');

});
