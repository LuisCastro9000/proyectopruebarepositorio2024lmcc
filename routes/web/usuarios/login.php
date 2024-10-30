<?php

use Illuminate\Support\Facades\Route;

Route::namespace('App\Http\Controllers')->group(function () {
    Route::get('/', 'LoginController@__invoke');
    Route::post('/iniciando', 'LoginController@loginUsuario');
    Route::get('/cerrarSesion', 'LoginController@loginOut');
});
