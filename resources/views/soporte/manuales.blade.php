		@extends('layouts.app')
		@section('title', 'Soporte')
		@section('content')	
            <div class="container">
                <div class="widget-list">
                    <div class="row justify-content-center">
                        <div class="col-md-4 col-sm-6 widget-holder">
                            <div class="widget-bg">
                                <ul class="nav flex-column flex-nowrap">
                                    <li class="nav-item">
                                        <a class="nav-link collapsed fw-800 fs-15 text-info" href="#administracion" data-toggle="collapse" data-target="#administracion">Administración<i class="list-icon material-icons">chevron_right</i></a>
                                        <div id="administracion" class="collapse" aria-expanded="false">
                                            <ul class="flex-column pl-2 nav">
                                                <li class="nav-item">
                                                    <a class="nav-link collapsed py-1 fw-500 text-info" href="#almacen" data-toggle="collapse" data-target="#almacen">Almacén<i class="list-icon material-icons">chevron_right</i></a>
                                                    <div id="almacen" class="collapse" aria-expanded="false">
                                                        <ul class="flex-column nav pl-4">
                                                            <li class="nav-item">
                                                                <a id="bproductos" class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Productos <i class="list-icon material-icons">ondemand_video</i></a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a id="bservicios" class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Servicios <i class="list-icon material-icons">ondemand_video</i></a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a id="bcategorias" class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Categorías <i class="list-icon material-icons">ondemand_video</i></a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a id="bmarcas" class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Marcas <i class="list-icon material-icons">ondemand_video</i></a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a id="bbajaproductos" class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Baja de Productos <i class="list-icon material-icons">ondemand_video</i></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link collapsed py-1 fw-500 text-info" href="#staff" data-toggle="collapse" data-target="#staff">Staff<i class="list-icon material-icons">chevron_right</i></a>
                                                    <div id="staff" class="collapse" aria-expanded="false">
                                                        <ul class="flex-column nav pl-4">
                                                            <li class="nav-item">
                                                                <a id="bclientes" class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Clientes <i class="list-icon material-icons">ondemand_video</i></a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a id="bproveedores" class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Proveedores <i class="list-icon material-icons">ondemand_video</i></a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a id="boperador" class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Operadores <i class="list-icon material-icons">ondemand_video</i></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                                <li class="nav-item">
                                                    <a id="busuarios" class="nav-link py-1 fw-500 text-info" href="javascript:void(0);">Usuarios <i class="list-icon material-icons">ondemand_video</i></a>
                                                </li>
                                                <li class="nav-item">
                                                    <a id="bpermisos" class="nav-link py-1 fw-500 text-info" href="javascript:void(0);">Permisos <i class="list-icon material-icons">ondemand_video</i></a>
                                                </li>
                                                <li class="nav-item">
                                                    <a id="bsucursales" class="nav-link py-1 fw-500 text-info" href="javascript:void(0);">Sucursales <i class="list-icon material-icons">ondemand_video</i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link collapsed fw-800 fs-15 text-info" href="#operaciones" data-toggle="collapse" data-target="#operaciones">Operaciones<i class="list-icon material-icons">chevron_right</i></a>
                                        <div id="operaciones" class="collapse" aria-expanded="false">
                                            <ul class="flex-column pl-2 nav">
                                                <li class="nav-item">
                                                    <a class="nav-link py-1 fw-500 text-info" href="#ventas" data-toggle="collapse" data-target="#ventas">Ventas<i class="list-icon material-icons">chevron_right</i></a>
                                                    <div id="ventas" class="collapse" aria-expanded="false">
                                                        <ul class="flex-column nav pl-4">
                                                            <li class="nav-item">
                                                                <a id="bventas" class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Boleta / Factura <i class="list-icon material-icons">ondemand_video</i></a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Nota de Crédito / Débito</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Guía de Remitente</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                                <li class="nav-item">
                                                    <a id="bcompras" class="nav-link py-1 fw-500 text-info" href="javascript:void(0);">Compras <i class="list-icon material-icons">ondemand_video</i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link collapsed fw-800 fs-15 text-info" href="#caja" data-toggle="collapse" data-target="#caja">Caja<i class="list-icon material-icons">chevron_right</i></a>
                                        <div id="caja" class="collapse" aria-expanded="false">
                                            <ul class="flex-column pl-2 nav">
                                                <li class="nav-item">
                                                    <a class="nav-link py-1 fw-400 text-info" href="javascript:void(0);">Ingresos / Egresos</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a id="bApeCieCaja" class="nav-link py-1 fw-400 text-info" href="javascript:void(0);">Apertura / Cierre Caja <i class="list-icon material-icons">ondemand_video</i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link collapsed fw-800 fs-15 text-info" href="#">Cobranzas</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link collapsed fw-800 fs-15 text-info" href="#consultas" data-toggle="collapse" data-target="#consultas">Consultas<i class="list-icon material-icons">chevron_right</i></a>
                                        <div id="consultas" class="collapse" aria-expanded="false">
                                            <ul class="flex-column pl-2 nav">
                                                <li class="nav-item">
                                                    <a id="bcprecios" class="nav-link py-1 fw-500 text-info" href="javascript:void(0);">Precios / Stock <i class="list-icon material-icons">ondemand_video</i></a>
                                                </li>
                                                <li class="nav-item">
                                                    <a id="bcclientes" class="nav-link py-1 fw-500 text-info" href="javascript:void(0);">Clientes <i class="list-icon material-icons">ondemand_video</i></a>
                                                </li>
                                                <li class="nav-item">
                                                    <a id="bcventas" class="nav-link py-1 fw-500 text-info" href="javascript:void(0);">Ventas: Boletas / Facturas <i class="list-icon material-icons">ondemand_video</i></a>
                                                </li>
                                                <li class="nav-item">
                                                    <a id="bccompras" class="nav-link py-1 fw-500 text-info" href="javascript:void(0);">Compras: Boletas / Facturas <i class="list-icon material-icons">ondemand_video</i></a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link py-1 fw-500 text-info" href="javascript:void(0);">Notas Créditos / Débitos</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link py-1 fw-500 text-info" href="javascript:void(0);">Guías de Remisión</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link collapsed fw-800 fs-15 text-info" href="#reportes" data-toggle="collapse" data-target="#reportes">Reportes<i class="list-icon material-icons">chevron_right</i></a>
                                        <div id="reportes" class="collapse" aria-expanded="false">
                                            <ul class="flex-column pl-2 nav">
                                                <li class="nav-item">
                                                    <a class="nav-link py-1 fw-500 text-info" href="#rventas" data-toggle="collapse" data-target="#rventas">Ventas<i class="list-icon material-icons">chevron_right</i></a>
                                                    <div id="rventas" class="collapse" aria-expanded="false">
                                                        <ul class="flex-column nav pl-4">
                                                            <li class="nav-item">
                                                                <a class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Vendedor</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Productos</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Cliente</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link py-1 fw-500 text-info" href="#rcompras" data-toggle="collapse" data-target="#rcompras">Compras<i class="list-icon material-icons">chevron_right</i></a>
                                                    <div id="rcompras" class="collapse" aria-expanded="false">
                                                        <ul class="flex-column nav pl-4">
                                                            <li class="nav-item">
                                                                <a class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Productos</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Proveedores</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link py-1 fw-500 text-info" href="#rgerenciales" data-toggle="collapse" data-target="#rgerenciales">Gerenciales<i class="list-icon material-icons">chevron_right</i></a>
                                                    <div id="rgerenciales" class="collapse" aria-expanded="false">
                                                        <ul class="flex-column nav pl-4">
                                                            <li class="nav-item">
                                                                <a class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Lo más Vendidos</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Compras y Ventas</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Ganancias</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Clientes Top</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Fines de Día</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link py-1 fw-500 text-info" href="#rfacturacion" data-toggle="collapse" data-target="#rfacturacion">Facturación<i class="list-icon material-icons">chevron_right</i></a>
                                                    <div id="rfacturacion" class="collapse" aria-expanded="false">
                                                        <ul class="flex-column nav pl-4">
                                                            <li class="nav-item">
                                                                <a class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Resumen Diario</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Baja de Documentos</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Facturas Pendientes</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link py-1 fw-500 text-info" href="#ralmacen" data-toggle="collapse" data-target="#ralmacen">Almacén<i class="list-icon material-icons">chevron_right</i></a>
                                                    <div id="ralmacen" class="collapse" aria-expanded="false">
                                                        <ul class="flex-column nav pl-4">
                                                            <li class="nav-item">
                                                                <a class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Stock</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Kardex</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link py-1 fw-500 text-info" href="#rcobranzas" data-toggle="collapse" data-target="#rcobranzas">Cobranzas<i class="list-icon material-icons">chevron_right</i></a>
                                                    <div id="rcobranzas" class="collapse" aria-expanded="false">
                                                        <ul class="flex-column nav pl-4">
                                                            <li class="nav-item">
                                                                <a class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Ventas por Cobrar</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Créditos Vencidos</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Pagos Parciales</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link p-1 fw-400 text-info" href="javascript:void(0);">Clientes con Morosidad</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link collapsed fw-800 fs-15 text-info">Soporte</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-6 widget-holder">
                            <div class="widget-bg text-center">
                                <div id="productos" class="text-center collapse" aria-expanded="false">
                                    <h6>Creación de Producto</h6>
                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/QnQWyNElumM" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                                <div id="servicios" class="text-center collapse" aria-expanded="false">
                                    <h6>Manejo de Servicios</h6>
                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/QB3Qkoj7hFk" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                                <div id="categorias" class="text-center collapse" aria-expanded="false">
                                    <h6>Creación de Categoría</h6>
                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/2PNmTcbt0pI" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                                <div id="marcas" class="text-center collapse multi-collapse" aria-expanded="false">
                                    <h6>Creación de Marca</h6>
                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/rU7XTTqBgTE" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                                <div id="bajaproductos" class="text-center collapse multi-collapse" aria-expanded="false">
                                    <h6>Baja de Productos</h6>
                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/CdmBLn5sHBU" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                                <div id="clientes" class="text-center collapse" aria-expanded="false">
                                    <h6>Creación de Clientes</h6>
                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/RRZcMCIO0-Y" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                                <div id="proveedores" class="text-center collapse" aria-expanded="false">
                                    <h6>Creación de Proveedores</h6>
                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/g6r5fuvva24" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                                <div id="operador" class="text-center collapse" aria-expanded="false">
                                    <h6>Creación de Operador</h6>
                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/_A7PVXdH8_E" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                                <div id="usuarios" class="text-center collapse" aria-expanded="false">
                                    <h6>Creación de Usuarios</h6>
                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/iStTzyFTNs0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                                <div id="permisos" class="text-center collapse" aria-expanded="false">
                                    <h6>Asignar Permisos</h6>
                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/jOqQelcaCJY" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                                <div id="sucursales" class="text-center collapse" aria-expanded="false">
                                    <h6>Creación de Sucursales</h6>
                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/aDB4x27jVeU" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                                <div id="permisos" class="text-center collapse" aria-expanded="false">
                                    <h6>Asignar Permisos</h6>
                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/jOqQelcaCJY" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                                <div id="sucursales" class="text-center collapse" aria-expanded="false">
                                    <h6>Creación de Sucursales</h6>
                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/eQEWgNG0dcM" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                                <div id="vventas" class="text-center collapse" aria-expanded="false">
                                    <h6>Generar Venta</h6>
                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/IMfy01bOx54" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                                
                                <div id="compras" class="text-center collapse" aria-expanded="false">
                                    <h6>Generar Compra</h6>
                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/v9lfp0Hw30s" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                                <div id="ccaja" class="text-center collapse" aria-expanded="false">
                                    <h6>Apertura / Cierre de Caja</h6>
                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/lDxjFojfEJU" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                                
                                <div id="cprecios" class="text-center collapse" aria-expanded="false">
                                    <h6>Precios / Stock</h6>
                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/2hWUlShHJEk" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                                <div id="cconsultas" class="text-center collapse" aria-expanded="false">
                                    <h6>Panel Consultas</h6>
                                    <div class="embed-responsive embed-responsive-16by9 videoSize">
                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/PbpWkVJjZrE" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <!-- /.widget-holder -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.widget-list -->
                
                
            </div>
            <!-- /.container -->
		@stop			
			
	@section('scripts')		
    <script>
    $(function() {
        $("#productos").hide();
        $("#servicios").hide();
        $("#categorias").hide();
        $("#marcas").hide();
        $("#bajaproductos").hide();
        $("#clientes").hide();
        $("#proveedores").hide();
        $("#operador").hide();
        $("#usuarios").hide();
        $("#permisos").hide();
        $("#sucursales").hide();
        $("#vventas").hide();
        $("#compras").hide();
        $("#ccaja").hide();
        $("#cprecios").hide();
        $("#cconsultas").hide();
        
        $("#bproductos").click(function(){
            $("#productos").show();
            $("#servicios").hide();
            $("#categorias").hide();
            $("#marcas").hide();
            $("#bajaproductos").hide();
            $("#clientes").hide();
            $("#proveedores").hide();
            $("#operador").hide();
            $("#usuarios").hide();
            $("#permisos").hide();
            $("#sucursales").hide();
            $("#vventas").hide();
            $("#compras").hide();
            $("#ccaja").hide();
            $("#cprecios").hide();
            $("#cconsultas").hide();
        });
        $("#bservicios").click(function(){
            $("#productos").hide();
            $("#servicios").show();
            $("#categorias").hide();
            $("#marcas").hide();
            $("#bajaproductos").hide();
            $("#clientes").hide();
            $("#proveedores").hide();
            $("#operador").hide();
            $("#usuarios").hide();
            $("#permisos").hide();
            $("#sucursales").hide();
            $("#vventas").hide();
            $("#compras").hide();
            $("#ccaja").hide();
            $("#cprecios").hide();
            $("#cconsultas").hide();
        });
        $("#bcategorias").click(function(){
            $("#productos").hide();
            $("#servicios").hide();
            $("#categorias").show();
            $("#marcas").hide();
            $("#bajaproductos").hide();
            $("#clientes").hide();
            $("#proveedores").hide();
            $("#operador").hide();
            $("#usuarios").hide();
            $("#permisos").hide();
            $("#sucursales").hide();
            $("#vventas").hide();
            $("#compras").hide();
            $("#ccaja").hide();
            $("#cprecios").hide();
            $("#cconsultas").hide();
        });
        $("#bmarcas").click(function(){
            $("#productos").hide();
            $("#servicios").hide();
            $("#categorias").hide();
            $("#marcas").show();
            $("#bajaproductos").hide();
            $("#clientes").hide();
            $("#proveedores").hide();
            $("#operador").hide();
            $("#usuarios").hide();
            $("#permisos").hide();
            $("#sucursales").hide();
            $("#vventas").hide();
            $("#compras").hide();
            $("#ccaja").hide();
            $("#cprecios").hide();
            $("#cconsultas").hide();
        });
        $("#bbajaproductos").click(function(){
            $("#productos").hide();
            $("#servicios").hide();
            $("#categorias").hide();
            $("#marcas").hide();
            $("#bajaproductos").show();
            $("#clientes").hide();
            $("#proveedores").hide();
            $("#operador").hide();
            $("#usuarios").hide();
            $("#permisos").hide();
            $("#sucursales").hide();
            $("#vventas").hide();
            $("#compras").hide();
            $("#ccaja").hide();
            $("#cprecios").hide();
            $("#cconsultas").hide();
        });
        $("#bclientes").click(function(){
            $("#productos").hide();
            $("#servicios").hide();
            $("#categorias").hide();
            $("#marcas").hide();
            $("#bajaproductos").hide();
            $("#clientes").show();
            $("#proveedores").hide();
            $("#operador").hide();
            $("#usuarios").hide();
            $("#permisos").hide();
            $("#sucursales").hide();
            $("#vventas").hide();
            $("#compras").hide();
            $("#ccaja").hide();
            $("#cprecios").hide();
            $("#cconsultas").hide();
        });
        $("#bproveedores").click(function(){
            $("#productos").hide();
            $("#servicios").hide();
            $("#categorias").hide();
            $("#marcas").hide();
            $("#bajaproductos").hide();
            $("#clientes").hide();
            $("#proveedores").show();
            $("#operador").hide();
            $("#usuarios").hide();
            $("#permisos").hide();
            $("#sucursales").hide();
            $("#vventas").hide();
            $("#compras").hide();
            $("#ccaja").hide();
            $("#cprecios").hide();
            $("#cconsultas").hide();
        });
        $("#boperador").click(function(){
            $("#productos").hide();
            $("#servicios").hide();
            $("#categorias").hide();
            $("#marcas").hide();
            $("#bajaproductos").hide();
            $("#clientes").hide();
            $("#proveedores").hide();
            $("#operador").show();
            $("#usuarios").hide();
            $("#permisos").hide();
            $("#sucursales").hide();
            $("#vventas").hide();
            $("#compras").hide();
            $("#ccaja").hide();
            $("#cprecios").hide();
            $("#cconsultas").hide();
        });
        $("#busuarios").click(function(){
            $("#productos").hide();
            $("#servicios").hide();
            $("#categorias").hide();
            $("#marcas").hide();
            $("#bajaproductos").hide();
            $("#clientes").hide();
            $("#proveedores").hide();
            $("#operador").hide();
            $("#usuarios").show();
            $("#permisos").hide();
            $("#sucursales").hide();
            $("#vventas").hide();
            $("#compras").hide();
            $("#ccaja").hide();
            $("#cprecios").hide();
            $("#cconsultas").hide();
        });
        $("#bpermisos").click(function(){
            $("#productos").hide();
            $("#servicios").hide();
            $("#categorias").hide();
            $("#marcas").hide();
            $("#bajaproductos").hide();
            $("#clientes").hide();
            $("#proveedores").hide();
            $("#operador").hide();
            $("#usuarios").hide();
            $("#permisos").show();
            $("#sucursales").hide();
            $("#vventas").hide();
            $("#compras").hide();
            $("#ccaja").hide();
            $("#cprecios").hide();
            $("#cconsultas").hide();
        });
        $("#bsucursales").click(function(){
            $("#productos").hide();
            $("#servicios").hide();
            $("#categorias").hide();
            $("#marcas").hide();
            $("#bajaproductos").hide();
            $("#clientes").hide();
            $("#proveedores").hide();
            $("#operador").hide();
            $("#usuarios").hide();
            $("#permisos").hide();
            $("#sucursales").show();
            $("#vventas").hide();
            $("#compras").hide();
            $("#ccaja").hide();
            $("#cprecios").hide();
            $("#cconsultas").hide();
        });
        $("#bventas").click(function(){
            $("#productos").hide();
            $("#servicios").hide();
            $("#categorias").hide();
            $("#marcas").hide();
            $("#bajaproductos").hide();
            $("#clientes").hide();
            $("#proveedores").hide();
            $("#operador").hide();
            $("#usuarios").hide();
            $("#permisos").hide();
            $("#sucursales").hide();
            $("#vventas").show();
            $("#compras").hide();
            $("#ccaja").hide();
            $("#cprecios").hide();
            $("#cconsultas").hide();
        });
        $("#bcompras").click(function(){
            $("#productos").hide();
            $("#servicios").hide();
            $("#categorias").hide();
            $("#marcas").hide();
            $("#bajaproductos").hide();
            $("#clientes").hide();
            $("#proveedores").hide();
            $("#operador").hide();
            $("#usuarios").hide();
            $("#permisos").hide();
            $("#sucursales").hide();
            $("#vventas").hide();
            $("#compras").show();
            $("#ccaja").hide();
            $("#cprecios").hide();
            $("#cconsultas").hide();
        });
        $("#bApeCieCaja").click(function(){
            $("#productos").hide();
            $("#servicios").hide();
            $("#categorias").hide();
            $("#marcas").hide();
            $("#bajaproductos").hide();
            $("#clientes").hide();
            $("#proveedores").hide();
            $("#operador").hide();
            $("#usuarios").hide();
            $("#permisos").hide();
            $("#sucursales").hide();
            $("#vventas").hide();
            $("#compras").hide();
            $("#ccaja").show();
            $("#cprecios").hide();
            $("#cconsultas").hide();
        });
        $("#bcprecios").click(function(){
            $("#productos").hide();
            $("#servicios").hide();
            $("#categorias").hide();
            $("#marcas").hide();
            $("#bajaproductos").hide();
            $("#clientes").hide();
            $("#proveedores").hide();
            $("#operador").hide();
            $("#usuarios").hide();
            $("#permisos").hide();
            $("#sucursales").hide();
            $("#vventas").hide();
            $("#compras").hide();
            $("#ccaja").hide();
            $("#cprecios").show();
            $("#cconsultas").hide();
        });
        $("#bcclientes").click(function(){
            consultas();
        });
        $("#bcventas").click(function(){
            consultas();
        });
        $("#bccompras").click(function(){
            consultas();
        });
        
        function consultas(){
            $("#productos").hide();
            $("#servicios").hide();
            $("#categorias").hide();
            $("#marcas").hide();
            $("#bajaproductos").hide();
            $("#clientes").hide();
            $("#proveedores").hide();
            $("#operador").hide();
            $("#usuarios").hide();
            $("#permisos").hide();
            $("#sucursales").hide();
            $("#vventas").hide();
            $("#compras").hide();
            $("#ccaja").hide();
            $("#cprecios").hide();
            $("#cconsultas").show();
        }
        
        /*$(".btn-success").click(function(){
            $(".collapse").collapse('show');
        });
        $(".btn-warning").click(function(){
            $(".collapse").collapse('hide');
        });*/
    });
    </script>
	@stop






