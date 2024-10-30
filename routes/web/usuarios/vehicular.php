<?php

use Illuminate\Support\Facades\Route;

// RUTAS VEHICULAR/ADMINISTRACION
Route::namespace('App\Http\Controllers\Vehicular\Administracion')->prefix('vehicular/administracion')->group(function () {
    // rutas tipo vehiculo
    Route::get('/tipo', 'TipoController@index');
    Route::get('/tipo/crear', 'TipoController@create');
    Route::post('/tipo/salvar', 'TipoController@store');
    Route::get('/tipo/{id}/edit', 'TipoController@edit');
    Route::put('/tipo/{id}', 'TipoController@update');
    Route::get('/tipo/{id}/delete', 'TipoController@delete');

    // rutas  marca
    Route::get('/marca', 'MarcaController@index');
    Route::get('/marca/crear', 'MarcaController@create');
    Route::post('/marca/salvar', 'MarcaController@store');
    Route::get('/marca/{id}/edit', 'MarcaController@edit');
    Route::put('/marca/{id}', 'MarcaController@update');
    Route::get('/marca/{id}/delete', 'MarcaController@delete');

    // rutas modelo
    Route::get('/modelo', 'ModeloController@index');
    Route::get('/modelo/crear', 'ModeloController@create');
    Route::post('/modelo/salvar', 'ModeloController@store');
    Route::get('/modelo/{id}/edit', 'ModeloController@edit');
    Route::put('/modelo/{id}', 'ModeloController@update');
    Route::get('/modelo/{id}/delete', 'ModeloController@delete');

    // rutas vehiculo
    Route::get('/lista-vehiculos', 'VehicularController@index');
    Route::get('/filtrar-vehículo', 'VehicularController@filtrar');
    Route::get('/{id}/edit', 'VehicularController@edit');
    Route::get('/crear', 'VehicularController@create');
    Route::post('/salvar', 'VehicularController@store');
    Route::put('/{id}', 'VehicularController@update');
    Route::get('/{id}/delete', 'VehicularController@delete');
    Route::get('/crear/consultar-clientes', 'VehicularController@consultarDoc');

    // rutas operario
    Route::resource('/operario', 'OperarioController');
    Route::get('/operario/{id}/delete', 'OperarioController@delete');

    // rutas grupos
    Route::resource('/paquetes', 'GruposController');
    Route::get('/create', 'GruposController@create');
    Route::post('/paquetes/crear-paquete', 'GruposController@store');
    Route::post('/paquetes/actualizar-paquete', 'GruposController@actualizar');
    Route::get('/paquetes/detalle-paquete/{id}', 'GruposController@verDetalleGrupo');
    Route::get('/paquetes/editar/{id}', 'GruposController@editarGrupo');
    Route::get('/paquetes/eliminar-grupo/{id}', 'GruposController@eliminarGrupo');
    Route::get('/paginationProductos', 'GruposController@paginationProductos');
    Route::get('/paginationServicios', 'GruposController@paginationServicios');
    Route::get('/buscar-productos', 'GruposController@searchProducto');
    Route::get('/buscar-servicios', 'GruposController@searchServicio');

    // rutas paquetes promocionales
    Route::resource('/paquetes-promocionales', 'PaquetesController');
    Route::get('/crear-paquete-promocional', 'PaquetesController@verVistaCrearPaquete');
    Route::get('/detalle-paquete_promocional/{id}', 'PaquetesController@verVistaDetallePaquete');
    Route::get('/editar-paquete_promocional/{id}', 'PaquetesController@verVistaEditarPaquete');
    Route::post('/actualizar-paquete-promocional', 'PaquetesController@actualizar');
    Route::get('/eliminar-paquete_promocional/{id}', 'PaquetesController@eliminarPaquete');
});

// RUTAS VEHICULAR/GESTIONTALLER
Route::namespace('App\Http\Controllers\Vehicular\GestionTaller')->prefix('vehicular')->group(function () {
    // rutas checkList
    Route::resource('/check-in', 'CheckInController')->names('checkList');
    Route::post('/check-in-filtrar', 'CheckInController@filtrar');
    Route::get('/CheckIn/documento-generado/{id}', 'CheckInController@inventarioGenerado');
    Route::post('/CheckIn/actualizar-check-list', 'CheckInController@updateCheckList');
    Route::get('/exportar-excel/{id?}/{id2?}/{id3?}', 'CheckInController@exportarExcel');
    Route::get('/editar-inventario/{id}', 'CheckInController@mostarVistaEditarInventario');
    Route::get('/comprobar-permiso', 'CheckInController@comprobarPermiso');
    Route::get('/documento/{id}/{id1}', 'CheckInController@generarPdf')->name('generarPdfInventario');
    Route::get('/check-in/ajax/datos-tipo-vehiculo', 'CheckInController@consultarDatosTipoVehiculo')->name('consultarDatosTipoVehiculo');

    // rutas cronogramaMantenimiento
    Route::get('/gestion-taller/cronograma-mantenimiento', 'NotificarMantenimientoController@index')->name('notificar-mantenimiento.index');
    Route::get('/gestion-taller/cronograma-mantenimiento/exportar-excel/{id1?}/{id2?}/{id3?}', 'NotificarMantenimientoController@exportarExcel')->name('notificar-mantenimiento.excel');
    Route::get('/gestion-taller/cronograma-mantenimiento/filtrar-datos', 'NotificarMantenimientoController@consultarVehiculo')->name('notificar-mantenimiento.consultar');

    // rutas paquetes controlCalidad
    Route::resource('/gestion-taller/control-calidad', 'ControlCalidadController')->names('controlCalidad');
    Route::get('/gestion-taller/control-calidad/crear/obtener-datos-vehiculo', 'ControlCalidadController@getVehiculo')->name('control-calidad.obtener-datos-vehiculo');
    Route::get('/gestion-taller/control-calidad/documento/{id}/{id1}', 'ControlCalidadController@generarPdfControlCalidad')->name('imprimirControlCalidad');
    Route::post('/gestion-taller/control-calidad/guardar-firma-digital', 'ControlCalidadController@guardarFirmaDigital')->name('guardarFirmaDigital');
    Route::post('/gestion-taller/control-calidad/ajax/guardar-firma-digital', 'ControlCalidadController@guardarFirmaDigitalConAjax')->name('guardarFirmaDigitalConAjax');
    Route::resource('/checkList-moto', 'CheckListForMotoController')->names('checkListForMoto');

    // rutas monitoreoAtenciones
    Route::get('/gestion-taller/monitoreo-atencion', 'MonitoreoAtencionController')->name('monitoreo-atencion');
    Route::get('/gestion-taller/monitoreo-atencion/vehiculos-taller', 'MonitoreoAtencionController@getEstadosAtencion')->name('monitoreo-atencion.obtener-atenciones');
    Route::get('/gestion-taller/monitoreo-atencion/exportar-excel/{opcion?}/{fechaIni?}/{fechaFin?}', 'MonitoreoAtencionController@exportarExcel')->name('monitoreo-atencion.exportar-excel');

});

// RUTAS VEHICULAR/CONSULTAS
Route::namespace('App\Http\Controllers\Vehicular\Consultas')->prefix('vehicular/consultas')->group(function () {
    // rutas atencionesVehiculares
    Route::get('/atenciones-vehiculares', 'ConsultaAtencionVehicularController@index');
    Route::post('/atenciones-vehiculares', 'ConsultaAtencionVehicularController@store');
    Route::get('/atenciones-vehiculares/ver-bitacora/{id}', 'ConsultaAtencionVehicularController@verBitacora');
    Route::get('/atenciones-vehiculares/descargar/{id}', 'ConsultaAtencionVehicularController@descargarPDF');

});

// RUTAS VEHICULAR/REPORTES
Route::namespace('App\Http\Controllers\Vehicular\Reportes')->prefix('vehicular/reportes')->group(function () {
    // rutas placa => ventas por Vehiculo
    Route::resource('/placa', 'PlacaController');
    Route::get('/excel-placa/{id}/{id2}/{id3}/{id4}', 'PlacaController@exportExcel');

    // rutas mecanico => productividad por Mecanico
    Route::resource('/mecanico', 'MecanicoController');
    Route::get('/excel-mecanico/{id}/{id2}/{id3}/{id4}', 'MecanicoController@exportExcel');

    // rutas vehiculo
    Route::resource('/vehiculo', 'VehiculoController');
    Route::get('/excel-vehiculo/{id}/{id2}/{id3}', 'VehiculoController@exportExcel');

    // rutas gananciaVehicular
    Route::resource('/ganancias-por-placa', 'GananciaVehicularController');
    Route::post('/filtrar-ganancias', 'GananciaVehicularController@filtrarGanancias');
    Route::get('/excel-ganancia/{id?}/{id2?}/{id3?}/{id4?}/{id5?}', 'GananciaVehicularController@ExportExcel');

    // rutas vehiculosAtendidos
    Route::resource('/vehiculos-atendidos', 'VehiculosAtendidosController')->names('vehiculosAtendidos');
    Route::get('/vehiculos-atendidos/ajax/vehiculo-por-estado', 'VehiculosAtendidosController@getVehiculosAjaxXEstado');

    // rutas tipoAtencion
    Route::resource('/tipo-atencion', 'TipoAtencionController')->names('reporteTipoAtencion');
    Route::get('/tipo-atencion/excel/{id1}/{id2?}/{id3?}', 'TipoAtencionController@exportExcel')->name('exportarReporteTipoAtencion');
});
