<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/img/favicon.png')}}">
    <link rel="stylesheet" href="{{asset('assets/css/pace.css')}}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Ventas</title>
    <!-- CSS -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/vendors/material-icons/material-icons.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/vendors/mono-social-icons/monosocialiconsfont.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/vendors/feather-icons/feather.css')}}" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/css/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet" type="text/css">

    <link href="{{asset('assets/css/responsive.dataTables.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/datatables.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/main.css')}}" rel="stylesheet" type="text/css">
    <!-- Head Libs -->
    <script src="{{asset('assets/js/modernizr.min.js')}}"></script>
    <script data-pace-options='{ "ajax": false, "selectors": [ "img" ]}' src="{{asset('assets/js/pace.min.js')}}"></script>
</head>

<body class="sidebar-horizontal">
    <div id="wrapper" class="wrapper">
        <!-- HEADER & TOP NAVIGATION -->
        @include('schemas.schemaHeader')
    <div class="content-wrapper">
        <!-- SIDEBAR -->
        <aside class="site-sidebar clearfix">
            <div class="container">
                @include('schemas.schemaSideNav')
            </div>
            <!-- /.container -->
        </aside>
        <!-- /.site-sidebar -->
        <main class="main-wrapper clearfix">
            <!-- Page Title Area -->
            <div class="container">
                <div class="row page-title clearfix">
                    <div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Listado de Ventas</h6>
                        <p class="page-title-description mr-0 d-none d-md-inline-block">descripción breve</p>
                    </div>
                    <!-- /.page-title-left -->
                    <div class="page-title-right">
                        <div class="row mr-b-50 mt-2">
                            <div class="col-12 mr-b-20 d-sm-block d-none">
                                <a href="../../operaciones/ventas/realizar-venta/create"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-26">note</i>  Nuevo</button></a>
                            </div>
                            <div class="col-12 mr-b-20 d-sm-none d-block">
                                <a href="../../operaciones/ventas/realizar-venta/create"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-26">note</i></button></a>
                            </div>
                        </div>
                    </div>
                    <!-- /.page-title-right -->
                </div>
                @if (session('status'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('status') }}
                    </div>
                @endif
                <!-- /.page-title -->
            </div>
            <!-- /.container -->
            <!-- =================================== -->
            <!-- Different data widgets ============ -->
            <!-- =================================== -->
            <div class="container">
                <div class="widget-list">
                    <div class="row">
                        <div class="col-md-12 widget-holder">
                            <div class="widget-bg">
                                <!--<div class="widget-heading clearfix">
                                    <h5>TableSaw</h5>
                                </div>-->
                                <!-- /.widget-heading -->
                                <div class="widget-body clearfix">
                                    <!--<p>Listado de ventas</p>-->
                                    <table id="table" class="table table-responsive-sm" style="width:100%">
                                        <thead>
                                            <tr class="bg-primary">
                                                <th scope="col">ID</th>
                                                <th scope="col">Fecha</th>
                                                <th scope="col">Cliente</th>
                                                <th scope="col">Usuario</th>
                                                <th scope="col">Código</th>
                                                <th scope="col">Total</th>
                                                <th scope="col">Estado</th>
                                                <th scope="col">Opciones</th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                                @foreach($facturasVentas as $factura)
                                                <tr>
                                                    <td scope="row">{{$factura->IdVentas}}</td>
                                                    <td>{{$factura->FechaCreacion}}</td>
                                                    <td>{{$factura->Nombres}}</td>
                                                    <td>{{$factura->Usuario}}</td>
                                                    <td>{{$factura->Serie}}-{{$factura->Numero}}</td>
                                                    <td>{{$factura->Total}}</td>
                                                    <td>{{$factura->Estado}}</td>
                                                    <td class="text-center">
                                                        <a href="../ventas/comprobante-generado/{{$factura->IdVentas}}"><i class="list-icon material-icons">visibility</i></a>
                                                        <a href="../ventas/descargar/{{$factura->IdVentas}}"><i class="list-icon material-icons">picture_as_pdf</i></a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                    </table>
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
        <div class="container"><span class="fs-13 heading-font-family">Copyright @ 2017. All rights reserved <a class="fw-800" href="https://kinetic.dharansh.in">WiseOwl Admin</a> by <a class="fw-800" href="https://themeforest.net/user/unifato">Unifato</a></span>
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

    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/template.js')}}"></script>
    <script src="{{asset('assets/js/custom.js')}}"></script>
    <script type="text/javascript">
        $(function() {
            $(document).ready(function () {
                $('#table').DataTable({
                    responsive: true,
                    language: {
                        processing:     "Procesando...",
                        search:         "Buscar:",
                        lengthMenu:     "Mostrar _MENU_ registros",
                        info:           "Registros del _START_ al _END_ de un total de _TOTAL_",
                        infoEmpty:      "Registros del 0 al 0 de un total de 0 registros",
                        infoFiltered:   "",
                        infoPostFix:    "",
                        loadingRecords: "Cargando...",
                        zeroRecords:    "No se encontraron resultados",
                        emptyTable:     "Ningún dato disponible en esta tabla",
                        paginate: {
                            first:      "Primero",
                            previous:   "Anterior",
                            next:       "Siguiente",
                            last:       "Último"
                        },
                        aria: {
                            sortAscending:  ": Activar para ordenar la columna de manera ascendente",
                            sortDescending: ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                });
            });
        });

    </script>
</body>

</html>

