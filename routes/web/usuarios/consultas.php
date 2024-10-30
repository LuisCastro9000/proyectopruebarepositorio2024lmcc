<?PHP
use Illuminate\Support\Facades\Route;

// RUTAS CONSULTAS
Route::namespace('App\Http\Controllers\Consultas')->prefix('consultas')->group(function () {
    // rutas precios
    Route::get('/porcentaje-descuento', 'ConsultaPreciosController@porcentajeDescuento');
    Route::post('/precios', 'ConsultaPreciosController@consulta');
    Route::get('/precios/exportar-Excel/{id}/{id2}', 'ConsultaPreciosController@exportExcel');
    Route::get('/precios', 'ConsultaPreciosController@consulta');

    // rutas clientes
    Route::get('/clientes', 'ConsultaClientesController@consulta');

    // rutas ventas-boletas-facturas
    Route::resource('/ventas-boletas-facturas', 'ConsultaVentasBoletaFacturaController');
    Route::post('/ventas-boletas-facturas/anulando', 'ConsultaVentasBoletaFacturaController@anular');
    Route::get('/obtener-datos', 'ConsultaVentasBoletaFacturaController@obtenerDatos');

    // rutas compras-boletas-facturas
    Route::resource('/compras-boletas-facturas', 'ConsultaComprasBoletaFacturaController');

    // rutas notas-credito
    Route::resource('/notas-credito-debito', 'ConsultaNotasCreditoDebitoController');
    Route::get('/notas-credito-debito/descargar/{id}', 'ConsultaNotasCreditoDebitoController@descargarPDF');
    Route::get('/notas-credito-debito/xml/{ruc}/{id}', 'ConsultaNotasCreditoDebitoController@descargarXML');
    Route::get('/notas-credito-debito/cdr/{ruc}/{id}', 'ConsultaNotasCreditoDebitoController@descargarCDR');
    Route::get('/notas-credito-debito/detalles/{id}/{tipo}', 'ConsultaNotasCreditoDebitoController@detallesNotaCreditoDebito');
    Route::post('/notas-credito-debito/descontar/{id}', 'ConsultaNotasCreditoDebitoController@descontarMonto');
    Route::post('/notas-credito-debito/enviar-correo/{id}', 'ConsultaNotasCreditoDebitoController@enviarCorreo');
    Route::post('/notas-credito-debito/imprimir/{id}', 'ConsultaNotasCreditoDebitoController@imprimirPDF');
    Route::post('/notas-credito-debito/anulando', 'ConsultaNotasCreditoDebitoController@anular');
    Route::get('/notas-credito-debito/ajax/obtener-monto-cuenta', 'ConsultaNotasCreditoDebitoController@getDatosCuenta')->name('notas-credito.obtener-monto-cuenta');

    // rutas guias-remision
    Route::resource('/guias-remision', 'ConsultaGuiasRemisionController');
    Route::get('/guias-remision/descargar/{id}', 'ConsultaGuiasRemisionController@descargarPDF');
    Route::get('/guias-remision/xml/{ruc}/{id}', 'ConsultaGuiasRemisionController@descargarXML');
    Route::get('/guias-remision/cdr/{ruc}/{id}', 'ConsultaGuiasRemisionController@descargarCDR');
    Route::get('/guias-remision/detalles/{id}', 'ConsultaGuiasRemisionController@detallesGuiaRemision');
    Route::post('/guias-remision/enviar-correo/{id}', 'ConsultaGuiasRemisionController@enviarCorreo');
    Route::post('/guias-remision/imprimir/{id}', 'ConsultaGuiasRemisionController@imprimirPDF');

});
