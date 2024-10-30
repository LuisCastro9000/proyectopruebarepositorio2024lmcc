<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pace.css') }}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Cambiar contraseña</title>
    <!-- CSS -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/material-icons/material-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/mono-social-icons/monosocialiconsfont.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('assets/vendors/feather-icons/feather.css') }}" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/css/perfect-scrollbar.min.css"
        rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <!-- Head Libs -->

    <script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
    <script data-pace-options='{ "ajax": false, "selectors": [ "img" ]}' src="{{ asset('assets/js/pace.min.js') }}">
    </script>
</head>

<body class="sidebar-horizontal">
    <div id="wrapper" class="wrapper">
        <!-- HEADER & TOP NAVIGATION -->
        @include('schemas.schemaHeader')
        <!-- /.navbar -->
        <div class="content-wrapper">
            <!-- SIDEBAR -->
            <aside class="site-sidebar clearfix">
                <div class="container">
                    <nav class="sidebar-nav">
                        <ul class="nav in side-menu">
                            <li class="current-page menu-item-has-children active"><a href="../../panel"><i
                                        class="list-icon material-icons">storage</i> <span
                                        class="hide-menu">Administración</span></a>
                                <ul class="list-unstyled sub-menu">
                                    <li class="menu-item-has-children"><a href="javascript:void(0);">Almacén</a>
                                        <ul class="list-unstyled sub-menu">
                                            <li><a href="productos">Productos</a></li>
                                            <li><a href="servicios">Servicios</a></li>
                                            <li><a href="categorias">Categorías</a></li>
                                            <li><a href="marcas">Marcas</a></li>
                                            <li><a href="baja-productos">Baja de Productos</a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item-has-children"><a href="javascript:void(0);">Staff</a>
                                        <ul class="list-unstyled sub-menu">
                                            <li><a href="../staff/clientes">Clientes</a></li>
                                            <li><a href="../staff/proveedores">Proveedores</a></li>
                                            <li><a href="../staff/operadores">Operador</a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item-has-children"><a href="javascript:void(0);">Vehicular</a>
                                        <ul class="list-unstyled sub-menu">
                                            <li><a href="javascript:void(0);">Empresa de Transportes</a></li>
                                            <li><a href="javascript:void(0);">Vehículos</a></li>
                                            <li><a href="javascript:void(0);">Conductor</a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item-has-children"><a href="javascript:void(0);">Finanzas</a>
                                        <ul class="list-unstyled sub-menu">
                                            <li><a href="javascript:void(0);">Cuentas Bancarias</a></li>
                                            <li><a href="javascript:void(0);">Tarjetas de Crédito/Débito</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="../usuarios">Usuarios</a>
                                    </li>
                                    <li><a href="../operadores">Permisos</a>
                                    </li>
                                    <li><a href="../sucursales">Sucursales</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="menu-item-has-children"><a href="javascript:void(0);"><i
                                        class="list-icon material-icons">shopping_cart</i> <span
                                        class="hide-menu">Operaciones</span></a>
                                <ul class="list-unstyled sub-menu">
                                    <li class="menu-item-has-children"><a href="javascript:void(0);">Ventas</a>
                                        <ul class="list-unstyled sub-menu">
                                            <li><a href="../../operaciones/ventas/realizar-venta">Realizar Venta (Boleta
                                                    / Factura)</a></li>
                                            <li><a href="javascript:void(0);">Generar Nota de Crédito / Débito</a></li>
                                            <li><a href="javascript:void(0);">Generar Guía de Remitente</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="../../operaciones/compras">Compras</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="menu-item-has-children"><a href="javascript:void(0);"><i
                                        class="list-icon material-icons">dvr</i> <span class="hide-menu">Caja</span></a>
                                <ul class="list-unstyled sub-menu">
                                    <li><a href="javascript:void(0);">Ingresos / Egresos</a></li>
                                    <li><a href="javascript:void(0);">Transferencias</a></li>
                                    <li><a href="javascript:void(0);">Fin de Turno</a></li>
                                </ul>
                            </li>
                            <li><a href="javascript:void(0);"><i class="list-icon material-icons">monetization_on</i>
                                    <span class="hide-menu">Cobranzas</span></a>
                            </li>
                            <li class="menu-item-has-children"><a href="javascript:void(0);"><i
                                        class="list-icon material-icons">assignment</i> <span
                                        class="hide-menu">Consultas</span></a>
                                <ul class="list-unstyled sub-menu">
                                    <li><a href="javascript:void(0);">Productos / Servicios y Precios</a></li>
                            </li><a href="javascript:void(0);">Clientes / Operador / Proveedor</a></li>
                            <li><a href="javascript:void(0);">Emp. Transp. / Vehículo / Conductor</a></li>
                            </li><a href="javascript:void(0);">Boletas / Facturas (Reimpresión)</a></li>
                        </ul>
                        </li>
                        <li class="menu-item-has-children"><a href="javascript:void(0);"><i
                                    class="list-icon material-icons">assessment</i> <span
                                    class="hide-menu">Reportes</span></a>
                            <ul class="list-unstyled sub-menu">
                                <li class="menu-item-has-children"><a href="javascript:void(0);">Ventas</a>
                                    <ul class="list-unstyled sub-menu">
                                        <li><a href="../../reportes/ventas/vendedores">Vendedor</a></li>
                                        <li><a href="../../reportes/ventas/productos">Producto</a></li>
                                        <li><a href="../../reportes/ventas/clientes">Cliente</a></li>
                                        <li><a href="../../reportes/ventas/clientes-top">Cliente TOP</a></li>
                                    </ul>
                                </li>
                                <li class="menu-item-has-children"><a href="javascript:void(0);">Compras</a>
                                    <ul class="list-unstyled sub-menu">
                                        <li><a href="../../reportes/compras/productos">Productos</a></li>
                                        <li><a href="../../reportes/compras/proveedores">Proveedores</a></li>
                                    </ul>
                                </li>
                                <li class="menu-item-has-children"><a href="javascript:void(0);">Gerenciales</a>
                                    <ul class="list-unstyled sub-menu">
                                        <li><a href="../../reportes/gerenciales/mas-vendidos">Los más vendidos</a></li>
                                        <li><a href="../../reportes/gerenciales/compras-ventas">Compras y ventas</a>
                                        </li>
                                        <li><a href="javascript:void(0);">Ganancias y perdidas</a></li>
                                        <li><a href="javascript:void(0);">Stock crítico de reposición</a></li>
                                        <li><a href="javascript:void(0);">Fines de día</a></li>
                                    </ul>
                                </li>
                                <li class="menu-item-has-children"><a href="javascript:void(0);">Facturación</a>
                                    <ul class="list-unstyled sub-menu">
                                        <li><a href="javascript:void(0);">Resumen Diario(emitidos y rechazados)</a>
                                        </li>
                                        <li><a href="javascript:void(0);">Baja de documentos</a></li>
                                    </ul>
                                </li>
                                <li class="menu-item-has-children"><a href="javascript:void(0);">Almacén</a>
                                    <ul class="list-unstyled sub-menu">
                                        <li><a href="javascript:void(0);">Stock</a></li>
                                        <li><a href="javascript:void(0);">Kardex</a></li>
                                    </ul>
                                </li>
                                <li class="menu-item-has-children"><a href="javascript:void(0);">Cobranzas</a>
                                    <ul class="list-unstyled sub-menu">
                                        <li><a href="javascript:void(0);">Ventas por cobrar</a></li>
                                        <li><a href="javascript:void(0);">Cuentas por pagar</a></li>
                                        <li><a href="javascript:void(0);">Créditos vencidos</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        </ul>
                        <!-- /.side-menu -->
                    </nav>
                    <!-- /.sidebar-nav -->
                </div>
                <!-- /.container -->
            </aside>
            <!-- /.site-sidebar -->
            <main class="main-wrapper clearfix">
                <!-- Page Title Area -->
                <div class="container">
                    <div class="row page-title clearfix">
                        <div class="page-title-left">
                            <h6 class="page-title-heading mr-0 mr-r-5">Cambiar Contraseña</h6>
                        </div>
                        <!-- /.page-title-left -->
                        <div class="page-title-right">
                            <div class="row mr-b-50 mt-2">
                                <div class="col-12 mr-b-20 d-sm-block d-none">
                                    <a href="../almacen/productos/create"><button
                                            class="btn btn-block btn-primary ripple"><i
                                                class="list-icon material-icons fs-28">add</i> Agregar</button></a>
                                </div>
                                <div class="col-12 mr-b-20 d-sm-none d-block">
                                    <a href="../almacen/productos/create"><button
                                            class="btn btn-block btn-primary ripple"><i
                                                class="list-icon material-icons fs-28">add</i></button></a>
                                </div>
                            </div>
                        </div>
                        <!-- /.page-title-right -->
                    </div>
                    <!-- /.page-title -->
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <li>{{ $message }}</li>
                        </div>
                    @endif
                </div>
                <!-- /.container -->
                <!-- =================================== -->
                <!-- Different data widgets ============ -->
                <!-- =================================== -->
                <div class="container">
                    <div class="widget-list">
                        <div class="row">
                            <div class="col-md-12 widget-holder">
                                <div class="widget-bg-transparent">
                                    <div class="widget-body clearfix">
                                        {!! Form::open(['url' => 'administracion/almacen/buscar-productos', 'method' => 'POST']) !!}
                                        {{ csrf_field() }}
                                        <div class="form-input-icon-right d-flex">
                                            <input type="text" id="inputBuscar" name="textoBuscar"
                                                class="form-control fs-22 fw-300 border-0"
                                                placeholder="Buscar Producto" value="{{ $textoBuscar }}">
                                            <button class="btn btn-info m-1" type="submit"><i
                                                    class="list-icon material-icons fs-22">search</i></button>
                                            @if ($textoBuscar != '' || $textoBuscar != null)
                                                <a href="javascript:void(0);"><button
                                                        class="btn btn-block btn-default ripple m-1"
                                                        onclick="limpiar()"><i
                                                            class="list-icon material-icons fs-22">clear</i></button></a>
                                            @endif
                                        </div>
                                        <hr>
                                        {!! Form::close() !!}


                                        <!-- Products List -->
                                        <div class="ecommerce-products list-unstyled row">
                                            @foreach ($articulos as $articulo)
                                                <div class="product col-12 col-sm-6 col-md-4">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <a href="javascript:void(0);">
                                                                <img src="{{ asset('assets/demo/e-commerce/12.jpeg') }}"
                                                                    alt="">
                                                                <!--<span class="triangle-top-right"></span>-->
                                                            </a>
                                                        </div>
                                                        <!-- /.card-header -->
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <h5 class="product-title">
                                                                        {{ $articulo->Descripcion }}</h5>
                                                                </div>
                                                                <div class="col-12">
                                                                    <span class="product-price">S/
                                                                        {{ $articulo->Precio }}</span>
                                                                </div>
                                                            </div>
                                                            <!-- /.d-flex --> <span
                                                                class="text-muted">{{ $articulo->Marca }}</span>
                                                        </div>
                                                        <!-- /.card-body -->
                                                        <div class="card-footer">
                                                            <div class="product-info"><a
                                                                    href="productos/{{ $articulo->IdArticulo }}/edit"><i
                                                                        class="list-icon material-icons">edit</i>Editar</a>
                                                            </div>
                                                            <div class="product-info"><a
                                                                    onclick="modalEliminar({{ $articulo->IdArticulo }})"
                                                                    href="javascript:void(0);"><i
                                                                        class="list-icon material-icons">remove_circle</i>Eliminar</a>
                                                            </div>
                                                        </div>
                                                        <!--<div class="card-footer">
                                                        <div class="product-info"><a href="javascript:void(0);"><i class="list-icon material-icons">remove_red_eye</i>&nbsp; 2,350</a>
                                                        </div>
                                                        <div class="product-info"><a href="javascript:void(0);"><i class="list-icon material-icons">comment</i>&nbsp; 362</a>
                                                        </div>
                                                    </div>-->
                                                        <!-- /.card-footer -->
                                                    </div>
                                                    <!-- /.card -->
                                                </div>
                                            @endforeach
                                        </div>
                                        <!-- /.ecommerce-products -->
                                        <!-- Product Navigation -->
                                        <div class="col-md-12">
                                            @php $pagination_range = 1; @endphp
                                            <nav aria-label="Page navigation">
                                                <ul class="pagination pagination-lg d-flex justify-content-center">
                                                    @if ($articulos->onFirstPage())
                                                        <li class="page-item"><a class="page-link disabled"
                                                                aria-label="Previous"><span aria-hidden="true"><i
                                                                        class="feather feather-chevron-left"></i></span></a>
                                                        @else
                                                        <li class="page-item"><a class="page-link"
                                                                href="{{ $articulos->previousPageUrl() }}"
                                                                aria-label="Previous"><span aria-hidden="true"><i
                                                                        class="feather feather-chevron-left"></i></span></a>
                                                    @endif
                                                    </li>
                                                    @for ($i = 1; $i <= $articulos->lastPage(); $i++)
                                                        @if ($i == $articulos->currentPage())
                                                            <li class="page-item active"><a class="page-link"
                                                                    href="javascript:void(0);">{{ $i }}</a>
                                                            </li>
                                                        @else
                                                            <li class="page-item"><a class="page-link"
                                                                    href="categorias?page={{ $i }}">{{ $i }}</a>
                                                            </li>
                                                        @endif
                                                    @endfor
                                                    @if ($articulos->hasMorePages())
                                                        <li class="page-item"><a class="page-link"
                                                                href="{{ $articulos->nextPageUrl() }}"
                                                                aria-label="Next"><span aria-hidden="true"><i
                                                                        class="feather feather-chevron-right"></i></span></a>
                                                        @else
                                                        <li class="page-item"><a class="page-link disabled"
                                                                aria-label="Next"><span aria-hidden="true"><i
                                                                        class="feather feather-chevron-right"></i></span></a>
                                                    @endif
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                        <!-- /.col-md-12 -->
                                    </div>
                                    <!-- /.widget-body -->
                                </div>
                                <!-- /.widget-bg -->
                            </div>
                            <!-- /.widget-holder -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.widget-list -->
                </div>
                <!-- /.container -->
            </main>
            <!-- /.main-wrappper -->

        </div>
        <!-- /.content-wrapper -->
        <!-- FOOTER -->
        <footer class="footer bg-primary text-inverse text-center">
            <div class="container"><span class="fs-13 heading-font-family">Copyright @ 2017. All rights reserved <a
                        class="fw-800" href="https://kinetic.dharansh.in">WiseOwl Admin</a> by <a class="fw-800"
                        href="https://themeforest.net/user/unifato">Unifato</a></span>
            </div>
            <!-- /.container -->
        </footer>

    </div>
    <!--/ #wrapper -->
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.9/metisMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/perfect-scrollbar.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
</body>
