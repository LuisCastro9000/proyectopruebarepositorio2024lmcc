<?php

use Illuminate\Support\Facades\Route;

// RUTAS ADMINISTRACION/ALMACEN
Route::namespace('App\Http\Controllers\Administracion\Almacen')->prefix('administracion/almacen')->group(function () {
    // rutas Productos
    Route::resource('/productos', 'ArticulosController');
    Route::get('/productos/{id}/delete', 'ArticulosController@delete');
    Route::get('/eliminacion-personalizada', 'ArticulosController@eliminacionPersonalizada');
    Route::post('/eliminacion-personalizada/eliminar', 'ArticulosController@eliminacionCompletada');
    Route::post('/productos/importar', 'ArticulosController@importar');
    Route::get('/productos/create/verificar-tipo-cambio', 'ArticulosController@verificarTipoCambio');
    Route::get('/descargar-formato', 'ArticulosController@descargarFormato');
    Route::get('/verificar-producto/{id}', 'ArticulosController@verificarProducto');
    Route::get('/exportar-excel-productos', 'ArticulosController@exportExcel');
    Route::get('/administracion/almacen/producto-sucursal', 'Administracion\Almacen\ArticulosController@showSucursal');
    Route::get('/administracion/almacen/producto-sucursal/{id}', 'Administracion\Almacen\ArticulosController@createSucursal');
    Route::post('/administracion/almacen/guardar-sucursal', 'Administracion\Almacen\ArticulosController@storeSucursal');
    Route::post('administracion/almacen/productos_sucursal', 'Administracion\Almacen\ArticulosController@storeProdSucursal');

    // rutas Servicios
    Route::get('/servicios/lista-servicios', 'ServiciosController@getVistaEliminacionMasiva')->name('vistaServiciosEliminacionMasiva');
    Route::post('/servicios/datos/eliminacion-masiva', 'ServiciosController@eliminacionMasiva')->name('serviciosEliminacionMasiva');
    Route::resource('/servicios', 'ServiciosController');
    Route::get('/servicios/{id}/delete', 'ServiciosController@delete');
    Route::get('/buscar-servicios', 'ServiciosController@search');
    Route::get('/servicios-almacen', 'ServiciosController@paginationServicios');
    Route::get('/exportar-excel-servicios', 'ServiciosController@exportExcel');
    Route::post('/administracion/almacen/productos/importar-excel-servicios', 'ServiciosController@importarExcelServicios');
    Route::get('/administracion/almacen/descargar-formato-excel-servicios', 'ServiciosController@descargarFormatoExcel');

    // rutas categorias
    Route::resource('/categorias', 'CategoriasController');
    Route::get('/categorias/{id}/delete', 'CategoriasController@delete');
    Route::get('/buscar-categorias', 'CategoriasController@search');
    Route::get('/categorias-almacen', 'CategoriasController@paginationCategorias');

    // rutas marcas
    Route::resource('/marcas', 'MarcasController');
    Route::get('/marcas/{id}/delete', 'MarcasController@delete');
    Route::get('/buscar-marcas', 'MarcasController@search');
    Route::get('/marcas-almacen', 'MarcasController@paginationMarcas');

    // rutas bajaProductos
    Route::resource('/baja-productos', 'BajaProductosController')->names('baja-productos');
    Route::get('/search-codigo-producto', 'BajaProductosController@searchCodigoProducto');
    Route::get('/search-productos', 'BajaProductosController@searchProducto');
    Route::get('/productos-baja', 'BajaProductosController@paginationProductos');
    Route::get('/baja-productos/documento/{id}/{id1}', 'BajaProductosController@getPdf')->name('baja-productos.obtener-pdf');

    // rutas regularizarStock
    Route::resource('/emparejar-stock', 'EmparejarStockController');
    Route::post('/actualizar-stock', 'EmparejarStockController@actualizarStock');
    Route::get('/validar-clave', 'EmparejarStockController@validarClave');
    Route::get('/stock-articulos', 'EmparejarStockController@verVistaStockArticulos');

});

// RUTAS ADMINISTRACION/STAFF
Route::namespace('App\Http\Controllers\Administracion\Staff')->prefix('administracion/staff')->group(function () {
    // rutas clientes
    Route::resource('/clientes', 'ClientesController');
    Route::post('/guardar-cliente', 'ClientesController@guardar');
    Route::get('/clientes/{id}/delete', 'ClientesController@delete');
    Route::get('/clientes/create/consultar-clientes', 'ClientesController@consultarDoc');
    Route::get('/clientes/create/consultar-provincias', 'ClientesController@consultarProvincias');
    Route::get('/clientes/create/consultar-distritos', 'ClientesController@consultarDistritos');
    Route::get('/clientes/{id}/edit/consultar-provincias', 'ClientesController@consultarProvincias');
    Route::get('/clientes/{id}/edit/consultar-distritos', 'ClientesController@consultarDistritos');
    Route::get('/excel-clientes', 'ClientesController@exportExcel');
    Route::post('/filtrar-clientes', 'ClientesController@filtrar');
    Route::post('/importar-excel-clientes', 'ClientesController@importarExcelClientes');
    Route::get('/descargar/formato-excel-clientes', 'ClientesController@descargarFormatoExcel');

    // rutas proveedores
    Route::resource('/proveedores', 'ProveedoresController');
    Route::get('/proveedores/{id}/delete', 'ProveedoresController@delete');
    Route::get('/proveedores/create/consultar-proveedores', 'ProveedoresController@consultarDoc');
    Route::get('/proveedores/create/consultar-provincias', 'ProveedoresController@consultarProvincias');
    Route::get('/proveedores/create/consultar-distritos', 'ProveedoresController@consultarDistritos');
    Route::get('/proveedores/{id}/edit/consultar-provincias', 'ProveedoresController@consultarProvincias');
    Route::get('/proveedores/{id}/edit/consultar-distritos', 'ProveedoresController@consultarDistritos');
    Route::get('/administracion/staff/excel-proveedores', 'ProveedoresController@exportExcel');
});

// RUTAS ADMINISTRACION/BANCOS
Route::namespace('App\Http\Controllers\Administracion\Banco')->prefix('administracion/bancos')->group(function () {
    // rutas cuentas-Bancarias
    Route::resource('/cuentas-bancarias', 'BancosController')->names('cuentas-bancarias');
    Route::Post('/cuentas-bancarias/ajax/registrar-transferencia', 'BancosController@storeTransferencia')->name('cuentas-bancarias.store-transferencia');
    Route::get('/cuentas-bancarias/ajax/traer-gastos', 'BancosController@listarGastos')->name('cuentas-bancarias.obtener-gastos');
    Route::post('/cuentas-bancarias/ajax/registrar-ingreso-salida', 'BancosController@registrar')->name('cuentas-bancarias.store-ingreso-salida');

    Route::post('/cuentas-bancarias/ajax/actualizar-ingreso-salida', 'BancosController@updateIngresoSalida')->name('cuentas-bancarias.update-ingreso-salida');

    // rutas tipo-Cambio
    Route::resource('/tipo-cambio', 'TipoCambioController')->names('tipo-cambio');
});

Route::namespace('App\Http\Controllers\Administracion')->prefix('administracion')->group(function () {
    // RUTAS ADMINISTRACION/USUARIOS
    // rutas usuarios
    Route::resource('/usuarios', 'UsuariosController');
    Route::get('/usuarios/{id}/delete', 'UsuariosController@delete');
    Route::post('/usuarios/actualizar-mensaje', 'UsuariosController@actualizarMensaje');
    Route::get('/usuarios/config-suscripcion/{id}', 'UsuariosController@configurarSuscripcion');
    Route::post('/usuarios/suscripcion-finalizada', 'UsuariosController@finalizarSuscripcion');
    Route::get('/usuarios-suscripciones', 'UsuariosController@mostrarSuscripciones');
    Route::post('/usuarios-suscripciones/guardar-cambios', 'UsuariosController@guardarCambiosSuscripciones');
    Route::Post('/usuarios/datos/AgregarIdSucursal-Suscripcion', 'UsuariosController@actualizarSuscripcionConIdSucursal')->name('AgregarIdSucursal-Suscripcion');

    Route::get('/usuarios/lista-xml/{id}', 'UsuariosController@listadoXml');
    Route::post('/usuarios/lista-xml/{id}', 'UsuariosController@buscarArchivosXML');
    Route::post('/usuarios/guardar-xml', 'UsuariosController@guardarXML');
    Route::post('/usuarios/comprimir-documentos/{id}', 'UsuariosController@comprimirDocumentos')->name('comprimir-documentos');
    Route::get('/usuarios/lista-documentos/{id}', 'UsuariosController@mostrarVistaListaDocumentos')->name('lista-documentos');
    Route::post('/usuarios/enviar-documentos-zip/{id}', 'UsuariosController@enviarDocumentosZip');
    Route::get('/usuarios/enviar-xml-cdr/{id}', 'UsuariosController@enviarCorreoUsuario')->name('enviarDocumentosUsuarios');

    // RUTAS ADMINISTRACION/PERMISOS
    // rutas permisos
    Route::resource('/permisos', 'PermisosController');
    Route::get('/permisos/{id}/delete', 'PermisosController@delete');
    Route::get('/modulos/{id}', 'PermisosController@seleccionarModulos');
    Route::post('/modulos/guardar', 'PermisosController@guardarModulos');
    Route::get('/completar-modulos/{id}', 'PermisosController@completarModulos');
    Route::get('/permisos/lista-permisos/{id}', 'PermisosController@verVistaListaPermisos');
    Route::get('/permiso/lista-permisos-sistema', 'PermisosController@verVistaPermisosDelSistema');
    Route::post('/permisos/datos/asignar-permisos-administradores', 'PermisosController@guardarPermisosAdministradores');

    // RUTAS ADMINISTRACION/SUCURSALES
    // rutas sucursales
    Route::resource('/administracion/sucursales', 'Administracion\SucursalesController');
    Route::get('/administracion/sucursales/{id}/delete', 'Administracion\SucursalesController@delete');
    Route::post('/administracion/sucursales-filtrar', 'Administracion\SucursalesController@filtrar');
    Route::get('/administracion/sucursales/create/consultar-provincias', 'Administracion\SucursalesController@consultarProvincias');
    Route::get('/administracion/sucursales/create/consultar-distritos', 'Administracion\SucursalesController@consultarDistritos');
    Route::get('/administracion/sucursales/{id}/edit/consultar-provincias', 'Administracion\SucursalesController@consultarProvincias');
    Route::post('/administracion/sucursales/{id}/edit/consultar-distritos', 'Administracion\SucursalesController@consultarDistritos');

    // RUTAS ADMINISTRACION/GASTOS
    // rutas gastos
    Route::resource('/gastos', 'GastosController');
    Route::get('/listar-gastos', 'GastosController@listarGastos');
    Route::get('/crear-gastos', 'GastosController@crearGastos');
    Route::post('/actualizar-gasto', 'GastosController@actualizarGasto');

});

Route::namespace('App\Http\Controllers\Administracion\PlanesSuscripcion')->prefix('administracion')->group(function () {
    Route::resource('/planes-suscripcion', 'PlanesSuscripcionController')->names('planesSuscripcion');
});
