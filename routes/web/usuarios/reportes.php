<?php
use Illuminate\Support\Facades\Route;

// RUTAS REPORTES\VENTAS
Route::namespace('App\Http\Controllers\Reportes\Ventas')->prefix('reportes/ventas')->group(function () {
    // rutas clientes
    Route::resource('/clientes', 'ReporteClienteController');
    Route::get('/excel-clientes/{id?}/{id2?}/{id3?}/{id4?}/{id5?}', 'ReporteClienteController@exportExcel');

    // rutas productos-servicios
    Route::resource('/productos', 'ReporteProductoController');
    Route::post('/productos/enviar-correo-excel/{id?}/{id2?}', 'ReporteProductoController@enviarCorreoExcel');
    Route::get('/productos/correo-excel/{id?}/{id2?}/{id3?}', 'ReporteProductoController@correoExcel');
    Route::post('/productos/datos-correo', 'ReporteProductoController@guardarDatosCorreoExcel');
    Route::post('/productos/actualizar-filtros', 'ReporteProductoController@actualizarFiltros');
    Route::get('/excel-productos/{id?}/{id2?}/{id3?}/{id4?}/{id5?}', 'ReporteProductoController@exportExcel');
    Route::get('/nuevo-excel-productos/{id?}/{id2?}', 'ReporteProductoController@exportExcelProductosXcategoria');

    // rutas vendedores
    Route::resource('/vendedores', 'ReporteVendedorController');
    Route::get('/excel-vendedores/{id?}/{id2?}/{id3?}/{id4?}/{id5?}', 'ReporteVendedorController@exportExcel');

});

// RUTAS REPORTES\COMPRAS
Route::namespace('App\Http\Controllers\Reportes\Compras')->prefix('reportes/compras')->group(function () {
    // rutas productos
    Route::get('/excel-productos/{id?}/{id2?}/{id3?}/{id4?}/{id5?}', 'ReporteProductoController@exportExcel');
    Route::resource('/productos', 'ReporteProductoController');
    Route::get('/descargar-excel-productos/{id?}/{id2?}/{id3?}/{id4?}/{id5?}', 'ReporteProductoController@exportExcel');
    Route::get('/cargar-select', 'ReporteProductoController@cargarSelect');

    // rutas proveedores
    Route::resource('/proveedores', 'ReporteProveedorController');
    Route::get('/excel-proveedores/{id?}/{id2?}/{id3?}/{id4?}/{id5?}', 'ReporteProveedorController@exportExcel');
    Route::get('/excel-proveedores/{id?}/{id2?}/{id3?}/{id4?}/{id5?}', 'ReporteProveedorController@exportExcel');

    // rutas ordenes-compra
    Route::resource('/ordenes-compra', 'ReporteOrdenCompraController');
    Route::post('/reporte-ordenes-compra', 'ReporteOrdenCompraController@filtraOrdenCompra');
    Route::get('/exportar-excel-orden/{id?}/{id2?}/{id3?}/{id4?}', 'ReporteOrdenCompraController@exportarExcelOrdenCompra');

});

// RUTAS REPORTES\GERENCIALES
Route::namespace('App\Http\Controllers\Reportes\Gerenciales')->prefix('reportes/gerenciales')->group(function () {
    // rutas mas-vendidos
    Route::get('/mas-vendidos/excel-MasVendidos/{id?}/{id2?}/{id3?}/{id4?}/{id5?}', 'ReportesMasVendidosController@exportExcel');
    Route::get('/mas-vendidos/excel-productos-no-vendidos/{id?}/{id2?}/{id3?}', 'ReportesMasVendidosController@exportExcelProductosNoVendidos');
    Route::post('/imprimir-detalle-caja', 'ReportesFinesDeDiaController@imprimirDetalleCaja');
    Route::resource('/mas-vendidos', 'ReportesMasVendidosController');

    // rutas compras-ventas
    Route::get('/comprasVentas/excel-CompraVentas/{id?}/{id2?}/{id3?}/{id4?}', 'ReportesComprasVentasMensualController@exportExcel');
    Route::resource('/compras-ventas', 'ReportesComprasVentasMensualController');

    // rutas ganancias
    Route::resource('/ganancias', 'ReportesGananciasController');

    // ruta clientes-top
    Route::get('/clientesTop/excel-clientesTop/{id?}/{id2?}/{id3?}/{id4?}', 'ReporteClienteTopController@exportExcel');
    Route::resource('/clientes-top', 'ReporteClienteTopController');

    // ruta fines-dias
    Route::post('/imprimir-detalle-caja', 'ReportesFinesDeDiaController@imprimirDetalleCaja');
    Route::resource('/fines-de-dia', 'ReportesFinesDeDiaController');
    Route::get('/excel-FinesDia/{id?}/{id2?}/{id3?}/{id4?}/{id5?}', 'ReportesFinesDeDiaController@exportExcel');

    // rutas ingresos-egresos
    Route::resource('ingresos-egresos', 'ReportesIngresosEgresosController');
    Route::get('excel-ingresos-egresos/{id?}/{id2?}/{id3?}/', 'ReportesIngresosEgresosController@exportExcel');

});

// RUTAS REPORTES\FACTURACION
Route::namespace('App\Http\Controllers\Reportes\Facturacion')->prefix('reportes/facturacion')->group(function () {
    // rutas resumen-diario
    Route::resource('/resumen-diario', 'ReportesResumenDiarioController');
    Route::get('/resumen-diario/cdr/{ruc}/{id}', 'ReportesResumenDiarioController@descargarCDR');
    Route::get('/resumen-diario/xml/{ruc}/{id}', 'ReportesResumenDiarioController@descargarXML');
    Route::get('/resumen-diario/enviar-ticket/{id}', 'ReportesResumenDiarioController@enviarTicket');
    Route::get('/resumen-diario/enviar-ticket-admin/{id}/{idUsuario}/{idSucursal}', 'ReportesResumenDiarioController@enviarTicketAdmin');
    Route::get('/ver-resumenes-diario-pendientes', 'ReportesResumenDiarioController@verResumenesDiario');
    Route::post('/cambiar-estado-resumen-diario', 'ReportesResumenDiarioController@cambiarResumenesDiario');

    // rutas ventas
    Route::get('/registro-ventas-electronicas', 'ReporteVentasElectronicas@index');
    Route::post('/registro-ventas-electronicas', 'ReporteVentasElectronicas@store');
    Route::get('/registro-excel/{id}/{id2}/{id3}', 'ReporteVentasElectronicas@exportExcel');
    Route::get('/registro-excel-ple/{id}/{id2}/{id3}', 'ReporteVentasElectronicas@exportExcelPLE');
    Route::get('/registro-ventas-texto-plano/{id}/{id2}/{id3}', 'ReporteVentasElectronicas@exportTextoPlano');

    // rutas baja-documento
    Route::resource('/baja-documentos', 'ReportesBajaDocumentosController');
    Route::get('/baja-documentos/cdr/{ruc}/{id}', 'ReportesBajaDocumentosController@descargarCDR');
    Route::get('/baja-documentos/xml/{ruc}/{id}', 'ReportesBajaDocumentosController@descargarXML');
    Route::get('/ver-baja-documentos-pendientes', 'ReportesBajaDocumentosController@verBajaDocumentos');

    // rutas facturas-pendientes
    Route::resource('/facturas-pendientes', 'FacturasPendientesController');
    Route::post('/cambiar-estado-factura', 'FacturasPendientesController@updateEstadoDocumento');
    Route::post('/enviando-sunat', 'FacturasPendientesController@enviarSunat');
    Route::get('/ver-facturas-pendientes', 'FacturasPendientesController@verFacturasPendientes');

    // rutas guias-remision-pendientes
    Route::get('/guias', 'ReporteGuiaController@index');
    Route::post('/guias', 'ReporteGuiaController@store');
    Route::get('/guias/excel-guias/{id?}/{id2?}/{id3?}/{id4?}/{id5?}', 'ReporteGuiaController@exportExcel');

    Route::resource('/guias-remision-pendientes', 'GuiaRemisionPendientesController');
    Route::post('/enviando-guias-sunat', 'GuiaRemisionPendientesController@enviarSunat');
    Route::get('/ver-guias-remision-pendientes', 'GuiaRemisionPendientesController@verGuiasRemisionPendientes');
    Route::post('/cambiar-estado-guia-remision', 'GuiaRemisionPendientesController@updateEstadoGuiasRemisionPendiente');

    Route::get('/mostrar-documentos', 'EmisionResumenDiarioController@mostrarDocumentos');
    Route::resource('/emitir-resumen-diario', 'EmisionResumenDiarioController');
    Route::post('/emitir-resumen-diario/enviar-documentos', 'EmisionResumenDiarioController@enviarDocumentos');

    Route::get('/baja-documentos/enviar-ticket/{id}/{tipo}', 'EnvioBajaDocumentosController@enviarTicket');
    Route::resource('/generar-baja-documentos', 'EnvioBajaDocumentosController');
    Route::post('/generar-baja-documentos/enviar-documentos', 'EnvioBajaDocumentosController@enviarDocumentos');

});

// RUTAS REPORTES\ALMACEN
Route::namespace('App\Http\Controllers\Reportes\Almacen')->prefix('reportes/almacen')->group(function () {
    // rutas reporte-stock
    Route::resource('/stock', 'ReporteStockController');
    Route::get('/imprimir/{id}/{id2}', 'ReporteStockController@imprimir');
    Route::get('/excel-stock/{id}/{id2}', 'ReporteStockController@exportExcel');
    Route::post('/enviar-correo/{id}/{id2}', 'ReporteStockController@enviarCorreo');

    // rutas reporte-stock-historico
    Route::resource('/stock-por-fecha', 'ReporteStockPorFechaController')->names('reporteStockPorFecha');;
    Route::get('/exportar-excel-stock-por-fechas/{id}', 'ReporteStockPorFechaController@exportarExcel')->name('exportarExcelStockPorFecha');

    // rutas reporte-kardex
    Route::resource('/kardex', 'ReporteKardexController');
    Route::get('/seleccionar-local', 'ReporteKardexController@selectLocal');
    Route::get('/kardex-antiguo', 'ReporteKardexController@kardexAntiguo');
    Route::post('/kardex-antiguo-filtrar', 'ReporteKardexController@filtrarKardexAntiguo');
    Route::get('/emparejar-kardex', 'ReporteKardexController@emparejarKardex');
    Route::post('/emparejando', 'ReporteKardexController@emparejando');
    Route::post('/correo-kardex/{id}/{id2}/{id3}/{id4}/{id5}/{id6}', 'ReporteKardexController@enviarCorreo');
    Route::get('/excel-kardex/{id?}/{id2?}/{id3?}/{id4?}/{id5?}/{id6?}/{id7?}/{id8?}', 'ReporteKardexController@exportExcel');

    // ruta reporte baja-productos
    Route::resource('/baja-productos', 'ReporteBajaProductoController');
    Route::get('/excel-baja-productos/{id}/{id2}/{id3}', 'ReporteBajaProductoController@exportExcel');

    // ruta reporte-transpaso
    Route::resource('traspasos', 'ReporteTraspasosController');
    Route::get('excel-traspasos/{id}/{id2}/{id3}/{id4}/{id5}', 'ReporteTraspasosController@exportExcel');

    // ruta reporte-regularizacion-inventario
    Route::resource('/regularizacion-inventario', 'ReporteRegularizacionStockController');
    Route::post('/filtrar-articulo', 'ReporteRegularizacionStockController@filtrarArticulo');
    Route::get('/excel-articulo/{id?}/{id1?}/{id2?}/{id3?}', 'ReporteRegularizacionStockController@exportarExcel');

    // ruta reporte-productos-eliminados
    route::resource('/productos-eliminados', 'ReporteProductosEliminadosController');
    route::get('/descargar-excel/{id?}/{id1?}/{id2?}/{id3?}', 'ReporteProductosEliminadosController@exportExcel');

    // ruta reporte-consolidado-movimientos-inventario
    Route::resource('movimiento-inventario', 'ReporteConsolidadoMovimientosInventarioController')->names('inventario');
    Route::get('consultar-inventario', 'ReporteConsolidadoMovimientosInventarioController@consultarInventario')->name('consultarInventario');
    Route::get('exportar-inventario/{id1}/{id2}', 'ReporteConsolidadoMovimientosInventarioController@exportarInventario')->name('exportarInventario');

});

// RUTAS REPORTES\COBRANZAS
Route::namespace('App\Http\Controllers\Reportes\Cobranzas')->prefix('reportes/cobranzas')->group(function () {
    // rutas reporte-ventas-por-cobrar
    Route::resource('/ventas-por-cobrar', 'VentasPorCobrarController');
    Route::get('/excel-por-cobrar/{id}/{id2}/{id3}/{id4}', 'VentasPorCobrarController@exportExcel');

    // rutas reporte-creditos-vencidos
    Route::resource('/creditos-vencidos', 'CreditosVencidosController');
    Route::get('/excel-creditos-Vencidos/{id}/{id2}/{id3}/', 'CreditosVencidosController@exportExcel');

    // rutas reporte-cobros-parciales
    Route::resource('/pagos-parciales', 'PagosParcialesController');
    Route::get('/excel-pagos-parciales/{id}/{id2}/{id3}/', 'PagosParcialesController@exportExcel');

    // rutas reporte-cobros-totales
    Route::resource('/pagos-totales', 'PagosTotalesController');
    Route::get('/excel-pagos-totales/{id}/{id2}/{id3}/', 'PagosTotalesController@exportExcel');

    // rutas reporte-clientes-morosos
    Route::resource('/clientes-morosos', 'ClientesMorososController');
    Route::get('/excel-clientes-morosos/{id}/{id2}/{id3}/', 'ClientesMorososController@exportExcel');

});

// RUTAS REPORTES\PAGOS
Route::namespace('App\Http\Controllers\Reportes\Pagos')->prefix('reportes/pagos')->group(function () {
    // rutas reporte-pagos-compras
    Route::resource('/reportes/pagos/compras-por-pagar', 'ComprasPorPagarController');
    Route::get('/excel-por-pagar/{id}/{id2}/{id3}/{id4}', 'ComprasPorPagarController@exportExcel');

    // rutas reporte-pagos-parciales
    Route::resource('/reportes/pagos/pagos-parciales', 'PagosParcialesController');
    Route::get('/excel-pagos-parciales/{id}/{id2}/{id3}/', 'PagosParcialesController@exportExcel');

    // rutas reporte-pagos-totales
    Route::resource('/reportes/pagos/pagos-totales', 'PagosTotalesController');
    Route::get('/excel-pagos-totales/{id}/{id2}/{id3}/', 'PagosTotalesController@exportExcel');

});

// RUTAS REPORTES\FINACIEROS
Route::namespace('App\Http\Controllers\Reportes\Financieros')->prefix('reportes/financieros')->group(function () {
    // rutas reporte-gastos
    Route::resource('/gastos', 'ReporteGastosController');
    Route::get('/excel-gastos/{id}/{id2}/{id3}/{id4}/{id5}', 'ReporteGastosController@exportExcel');

    // rutas reporte-pagos-bancos
    Route::resource('/bancos', 'ReporteBancosController');
    Route::get('/excel-bancos/{id}/{id2}/{id3}/{id4}', 'ReporteBancosController@exportExcel');

});

// RUTAS REPORTES\COTIZACION
Route::namespace('App\Http\Controllers\Reportes\Cotizacion')->prefix('reportes/cotizacion')->group(function () {
    // rutas reporte-amortizacion
    Route::resource('/amortizaciones', 'AmortizacionController');
    Route::get('/excel-amortizacion/{id}/{id2}/{id3}/{id4}', 'AmortizacionController@exportExcel');

});
