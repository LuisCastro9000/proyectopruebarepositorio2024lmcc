<?php

use Illuminate\Support\Facades\Route;

// RUTAS PANEL
Route::namespace('App\Http\Controllers')->group(function () {
    Route::get('/panel', 'PanelController@__invoke');
    Route::post('actualizar-perfil', 'PanelController@accionActualizarPerfil');
});

// RUTAS AREAS
Route::namespace('App\Http\Controllers\Areas')->group(function () {
    // RUTAS AREA-ADMINISTRATIVA
    Route::resource('area-facturacion', 'AreaFacturacionController');
    Route::get('area-facturacion/consulta/ajax', 'AreaFacturacionController@getDatosDocumentosAjax')->name('getDatosDocumentosFacturacion');

    // RUTAS AREA-FACTURACION
    Route::resource('area-administrativa', 'AreaAdministrativaController');
    Route::post('area-administrativa/reportes', 'AreaAdministrativaController@index');
    Route::get('area-administrativa/reportes/ajax', 'AreaAdministrativaController@getDatosBancos');

});
