<?php

use Illuminate\Support\Facades\Route;

// RUTAS OPERACIONES/VENTAS
Route::namespace('App\Http\Controllers\Operaciones\Ventas')->prefix('operaciones/ventas')->group(function () {
    // rutas ventas
    Route::resource('/realizar-venta', 'VentasController');
    Route::get('/comprobante-generado/{id}', 'VentasController@verFacturaGenerada');
    Route::get('/validar-documento/{id}', 'VentasController@validarDocumento');
    Route::get('/documentos/obtener-informacion', 'VentasController@obtenerInformacion');
    Route::post('/guardar-tipo-cambio', 'VentasController@guardarTipoCambio');
    Route::get('/placas-clientes', 'VentasController@placasClientes');
    Route::get('/obtener-items-paquete-promocional', 'VentasController@getDetallePaquetePromocional');
    Route::get('/select-anticipo', 'VentasController@selectAnticipo');
    Route::get('/selects-productos', 'VentasController@selectProductos');
    Route::get('/obtener-generado/{id}', 'VentasController@obtenerFacturaGenerada');
    Route::get('/xml/{ruc}/{id}', 'VentasController@descargarXML');
    Route::get('/cdr/{ruc}/{id}', 'VentasController@descargarCDR');
    Route::get('/descargar/{id}', 'VentasController@descargarPDF');
    Route::get('/descargarAlmacen/{id}', 'VentasController@descargarValeAlmacenPDF');
    Route::post('/imprimir/{id}', 'VentasController@imprimirPDF');
    Route::post('/enviar-correo/{id}', 'VentasController@enviarCorreo');
    Route::get('/verificar-tipo-cambio', 'VentasController@verificarTipoCambio');
    Route::post('/crear-cliente', 'VentasController@crearCliente');
    Route::get('/consultar-clientes', 'VentasController@consultarDoc');
    Route::get('/buscar-codigo-producto', 'VentasController@searchCodigoProducto');
    Route::post('/crear-vehiculo', 'VentasController@crearVehiculo');
    Route::post('/guardar-pdf', 'VentasController@storePdfForWhatsapp');
    Route::get('/porcentaje-descuento', 'VentasController@porcentajeDescuento');

    // rutas notasCredito
    Route::resource('/nota-credito-debito', 'NotaCreditoDebitoController')->names('nota-credito');
    Route::get('/{tipo}-nota-credito-debito-{id}', 'NotaCreditoDebitoController@selectVentaAceptada');
    Route::get('/datos-nota-credito', 'NotaCreditoDebitoController@getDatosNotaCredito');
    Route::get('/items-nota-credito', 'NotaCreditoDebitoController@getItemsNotaCredito');

    // rutas guiaRemision
    Route::get('/obtener-informacion', 'GuiaRemisionController@obtenerInformacion');
    Route::resource('/guia-remision', 'GuiaRemisionController');
    Route::get('/buscar-productos-guias', 'GuiaRemisionController@searchProducto');
    Route::get('/productos-guias', 'GuiaRemisionController@paginationProductos');
    Route::get('/obtener-datos', 'GuiaRemisionController@selectVentaAceptada');
    Route::get('/consultar-provincias', 'GuiaRemisionController@consultarProvincias');
    Route::get('/consultar-distritos', 'GuiaRemisionController@consultarDistritos');
    Route::get('/mostrar-documentos', 'GuiaRemisionController@mostrarDocumentos');
    Route::get('/mostrar-sucursales', 'GuiaRemisionController@mostrarSucursales');

    // rutas anticipos
    Route::resource('/anticipos', 'AnticiposController');
    Route::get('/anticipos/completar-anticipo/{id}', 'AnticiposController@completarFacturaAnticipo');
    Route::post('/finalizar-anticipo', 'AnticiposController@finalizarAnticipo');
});

// RUTAS OPERACIONES/COTIZACION
Route::namespace('App\Http\Controllers\Operaciones\Cotizacion')->prefix('operaciones/cotizacion')->group(function () {
    Route::get('/buscar-codigo-producto', 'CotizacionController@searchCodigoProducto');
    Route::get('/convertir/buscar-codigo-producto', 'CotizacionController@searchCodigoProducto');
    Route::post('/convertir-venta', 'CotizacionController@saveVenta');
    Route::get('/obtener-informacion', 'CotizacionController@obtenerInformacion')->name('cotizacion.obtener-informacion-cliente');
    Route::get('/data-vehiculo', 'CotizacionController@dataVehiculo')->name('cotizacion.obtener-data-vehiculo');
    Route::get('/data-inventario', 'CotizacionController@dataCheckList')->name('cotizacion.obtener-data-inventario');
    Route::get('/porcentaje-descuento', 'CotizacionController@porcentajeDescuento');
    Route::get('/realizar-cotizacion', 'CotizacionController@index');
    Route::post('/realizar-venta', 'CotizacionController@store')->name('cotizacion.store');
    Route::get('/comprobante-generado/{id}', 'CotizacionController@verCotizacionGenerada')->name('cotizacion.ver-cotizacion-generada');
    Route::get('/consultar-cotizacion', 'CotizacionController@consultarCotizacion');
    Route::post('/consultar-cotizacion', 'CotizacionController@filtrarCotizacion');
    Route::get('/excel-cotizacion/{id}/{id2}/{id3}/{id4}/{id5}', 'CotizacionController@exportExcel');
    Route::post('/consultar-cotizacion/amortizar', 'CotizacionController@amortizar');
    Route::post('/consultar-cotizacion/dar-baja', 'CotizacionController@darBaja');

    Route::get('/estados-cotizacion/{id}', 'CotizacionController@estadosCotizacion');
    Route::post('/actualizar-estados', 'CotizacionController@actualizarEstadosCotizacion');
    Route::get('/editar/{id}', 'CotizacionController@editarCotizacion');
    Route::post('/actualizar-cotCotizacion\CotizacionController@actualizarCotizacion');

    Route::get('/descargar/{id}', 'CotizacionController@descargarPDF');
    Route::post('/enviar-correo/{id}', 'CotizacionController@enviarCorreo');
    Route::get('/convertir/{id}', 'CotizacionController@convertirCotizacion');
    Route::get('/descargar-orden/{id}', 'CotizacionController@descargarOrdenPDF');
    Route::get('/descargar-nuevopdf/{id}', 'CotizacionController@descargarNuevoPDF');
    Route::get('/verificar-tipo-cambio', 'CotizacionController@verificarTipoCambio');
    Route::get('/imprimir/{id}', 'CotizacionController@imprimirPDF');
    Route::post('/imprimir-ticket/{id}', 'CotizacionController@imprimirPDF')->name('imprimirTicketAmortizacion');
    Route::post('/guardar-pdf', 'CotizacionController@storePdfForWhatsapp');

    // Nuevas rutas Agregar grupos a cotizaciones
    Route::get('/obtener-items-grupo', 'CotizacionController@obtenerItemsGrupo')->name('cotizacion.obtener-articulos-grupo');
    Route::get('/grupos', 'CotizacionController@paginationGrupos');
    Route::get('/buscar-grupo', 'CotizacionController@searchGrupo');
    // obtener items paquete promocional promocionales
    Route::get('/obtener-items-paquete-promocional', 'CotizacionController@getDetallePaquetePromocional');
    Route::get('/paginacion-paquete-promocional', 'CotizacionController@paginationPaquetesPromocionales');
    Route::get('/buscar-paquete-promocional', 'CotizacionController@searchPaquetesPromocionales');
    Route::get('/duplicar-cotizacion/{id}', 'CotizacionController@duplicarCotizacion')->name('operaciones.cotizacion.duplicar');

    Route::put('consultar-cotizacion/ajax/revetir-estado-cotizacion', 'CotizacionController@revertirEstadoConAjax')->name('cotizacion.revertir-estado');
});

// RUTAS OPERACIONES/COMPRAS
Route::namespace('App\Http\Controllers\Operaciones')->prefix('operaciones/compras')->group(function () {
    Route::resource('/lista-compras', 'ComprasController')->names('storeCompra');
    Route::get('/validar-password-supervisor', 'ComprasController@validarPasswordSupervisor');
    Route::post('/lista-compras/actualizar-documento', 'ComprasController@actualizarDocumento');
    Route::get('/crear-compra', 'ComprasController@crearCompra');
    Route::get('/crear-compra/comprobar-existencia', 'ComprasController@comprobarExistencia');
    Route::get('/crear-compra/select-productos', 'ComprasController@selectProductos');
    Route::get('/crear-compra/select-productos-sin-tipo-cambio', 'ComprasController@selectProductosSinTipoCambio');
    Route::get('/editar-compra/{id}', 'ComprasController@editarCompraPendiente');
    Route::get('/editar-compra/enviar-datos/comprobar-existencia', 'ComprasController@comprobarExistencia');
    Route::get('/editar-compra/enviar-datos/select-productos', 'ComprasController@selectProductos');
    Route::get('/descargar-vale-compras/{id}', 'ComprasController@descargarValeComprasPDF');
    Route::get('/comprobante-generado/{id}', 'ComprasController@verComprobanteGenerado');
    Route::get('/imprimir/{id}', 'ComprasController@imprimirPDF');
    Route::get('/descargar/{id}', 'ComprasController@descargarPDF');
    Route::post('/anular-compra', 'ComprasController@anularCompra');
    Route::get('/verificar-compra', 'ComprasController@verificarCompra');
    Route::post('/crear-compra/guardar-egreso-compra', 'ComprasController@storeEgresoCompra');
    Route::get('/orden-compra', 'ComprasController@mostrarVistaGenerarOrdenCompra');
    Route::post('/orden-compra/guardar-orden-compra', 'ComprasController@storeOrdenCompra');
    Route::get('/operaciones/compras/comprobante-orden-compra/{id}', 'ComprasController@verComprobanteOrdenCompra');
    Route::post('/enviar-correo/{id}', 'ComprasController@enviarCorreo');
    Route::get('/imprimir-orden-compra/{id}', 'ComprasController@imprimirPdfOrdenCompra');
    Route::get('/descargar-orden-compra/{id}', 'ComprasController@descargarPdfOrdenCompra');
    Route::get('/convertir-orden-compra/{id}', 'ComprasController@mostrarVistaConvertirOrdenCompra');
    Route::get('/convertir-orden-compra/enviar-datos/validar-serie-numero', 'ComprasController@comprobarExistencia');
    Route::post('/convertir-orden-compra/finalizar-compra', 'ComprasController@store');
    Route::post('/convertir-orden-compra/enviar-datos/guardar-tipo-cambio', 'ComprasController@guardarTipoCambio');
});

// RUTAS OPERACIONES/ORDENCOMPRAS
Route::namespace('App\Http\Controllers\Operaciones\OrdenesCompra')->prefix('operaciones')->group(function () {
    Route::resource('/ordenes-compra', 'OrdenesCompraController')->names('ordenDeCompra');
    Route::get('/ordenes-compra/documento/{id}/{id1}', 'OrdenesCompraController@getDocumentoPdf')->name('obtenerDocumentoPdf');
    Route::get('/ordenes-compra/convertir/{id}', 'OrdenesCompraController@verVistaConvertirOrden')->name('verVistaConvertirOrden');
    Route::post('/ordenes-compra/convertir/guardar-tipo-cambio', 'OrdenesCompraController@storeTipoCambio');
});
