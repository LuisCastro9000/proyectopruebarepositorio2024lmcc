<?PHP
use Illuminate\Support\Facades\Route;

// RUTAS COBRANZAS
Route::namespace('App\Http\Controllers\Cobranzas')->group(function () {
    // rutas cobranzas
    Route::resource('/cobranzas', 'CobranzasController');
    Route::get('/cobranzas/cobranzas-moneda', 'DetalleCobranzaController');
    Route::get('/detalle-cobranza/{id}', 'DetalleCobranzaController');
    Route::get('/detalle-cobranza/pagos-detalles/{id}', 'DetalleCobranzaController@pagosDetalle');
    Route::get('/detalle-cobranza/realizar-cobro/{id}/{tipoMoneda}', 'RealizarCobroController');
    Route::post('/detalle-cobranza/realizar-cobro', 'RealizarCobroController@cobrar');

    // rutas pagos
    Route::resource('/pagos', 'PagosController');
    Route::get('/detalle-pago/{id}', 'DetallePagoController');
    Route::get('/detalle-pago/pagos-proveedores-detalles/{id}', 'DetallePagoController@pagosProveedoresDetalle');
    Route::get('/detalle-pago/realizar-pago/{id}/{tipoMoneda}', 'RealizarPagoController');
    Route::post('/detalle-pago/realizar-pago', 'RealizarPagoController@pagar');
    Route::get('/detalle-pago/realizar-pago/traer-gastos', 'RealizarPagoController@obtenerGastos')->name('pagos.obtener-gastos');

});
