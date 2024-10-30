<?php

use Illuminate\Support\Facades\Route;

Route::namespace('App\Http\Controllers')->group(function () {
    Route::post('/cambiar-sucursal', 'PerfilController@cambiarSucursal');
    Route::get('/cambiar-contrasena', 'PerfilController@cambiarContrasena');
    Route::get('/cambiar-contrasena-comprobacion-de-permiso', 'PerfilController@cambiarContrasena');
    Route::post('/actualizando-contrasena', 'PerfilController@actualizarContrasena');
    Route::get('/configurar-empresa', 'PerfilController@configurarEmpresa');
    Route::get('/consultar-provincias', 'PerfilController@consultarProvincias');
    Route::get('/consultar-distritos', 'PerfilController@consultarDistritos');
    Route::post('/actualizando-empresa', 'PerfilController@actualizarEmpresa');
    Route::post('/importando-certificado', 'PerfilController@importarCertificado');
    Route::get('/crear-firma-digital', 'PerfilController@crearFirmaDigital');
    Route::post('/actualizando-Firma-digital', 'PerfilController@actualizarFirmaDigital');
});
