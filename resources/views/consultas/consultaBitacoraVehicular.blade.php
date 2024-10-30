<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pace.css') }}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bitacora Vehicular</title>
    <!-- CSS -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/material-icons/material-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/mono-social-icons/monosocialiconsfont.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('assets/vendors/feather-icons/feather.css') }}" rel="stylesheet" type="text/css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/css/perfect-scrollbar.min.css"
        rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">

    <link href="{{ asset('assets/css/responsive.dataTables.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" type="text/css">
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
                            <h6 class="page-title-heading mr-0 mr-r-5">Detalles de la Atencion</h6>
                        </div>
                        <!-- /.page-title-left -->
                        <div class="page-title-right">
                            <div class="row mr-b-50 mt-2">
                                <div class="col-12 mr-b-20 d-flex">
                                    <!-- <a class="p-1" href="#" data-toggle="modal" data-target=".bs-modal-sm-print"><button class="btn btn-block btn-success ripple"><i class="list-icon material-icons fs-20">print</i></button></a>  -->
                                    <a class="p-1"
                                        href="../../atenciones-vehiculares/descargar/{{ $bitacoraSelect->IdAtencion }}"
                                        target="_blank"><button class="btn btn-block btn-info ripple"><i
                                                class="list-icon material-icons fs-20">picture_as_pdf</i>Descargar</button></a>
                                </div>
                                <!--<div class="col-12 mr-b-20 d-sm-none d-block">
                                <a href="#" data-toggle="modal" data-target=".bs-modal-sm-primary" onclick="cargarCorreo()"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-28">mail</i></button></a>
                            </div>-->
                            </div>
                        </div>
                    </div>
                    @if (session('status'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('error') }}
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
                                    <div class="widget-body clearfix">
                                        <div class="ecommerce-invoice">
                                            <div class="d-sm-flex">
                                                <div class="col-md-6">
                                                    <h5>Documento : {{ $bitacoraSelect->Documento }}</h5>
                                                    {{-- <a href="../../../operaciones/cotizacion/comprobante-generado/{{$bitacoraSelect->IdVentas}}" target="_blank" rel="noopener noreferrer">{{ $bitacoraSelect->serie }}-{{ $bitacoraSelect->Numero }}</a> --}}
                                                    @if ($bitacoraSelect->IdCotizacion != null)
                                                        <a target="_blank"
                                                            href="../../../operaciones/cotizacion/comprobante-generado/{{ $bitacoraSelect->IdCotizacion }}">{{ $bitacoraSelect->serie }}-{{ $bitacoraSelect->Numero }}</a>
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- /.row -->
                                            <hr>
                                            <div class="d-sm-flex">
                                                <div class="col-md-6 mt-2">
                                                    <h6>Cliente:</h6>
                                                    <strong>NOMBRE/RAZ. SOCIAL:</strong>
                                                    {{ $bitacoraSelect->RazonSocial }}
                                                    <br><strong>NOMBRE COMERCIAL:</strong>
                                                    {{ $bitacoraSelect->RazonSocial }}

                                                    <br><strong>DOCUMENTO:</strong>
                                                    {{ $bitacoraSelect->NumeroDocumento }}
                                                    <br><strong>DIRECCIÓN:</strong> {{ $bitacoraSelect->Direccion }}
                                                </div>
                                                <div class="col-md-6 text-right mt-2 d-none d-sm-block">
                                                    <h6>Detalles:</h6>
                                                    <strong>FECHA:</strong> <span
                                                        class="text-muted">{{ $formatoFecha }}</span>
                                                    <br><strong>HORA:</strong> <span
                                                        class="text-muted">{{ $formatoHora }}</span>
                                                    <br><strong>MONTO A PAGAR:</strong> <span
                                                        class="text-muted">{{ $bitacoraSelect->Total }}</span>
                                                    <br><strong>TIPO MONEDA:</strong> <span
                                                        class="text-muted">@if ($bitacoraSelect->IdTipoMoneda == 1) Soles @else Dólares @endif</span>
                                                    <br><strong>TIPO OPERACIÓN:</strong> <span
                                                        class="text-muted">@if ($bitacoraSelect->TipoVenta == 1) Gravada @else Exonerada @endif</span>
                                                </div>
                                                <div class="col-md-6 mt-2 d-block d-sm-none">
                                                    <h6>Detalles:</h6>
                                                    <strong>FECHA:</strong> <span
                                                        class="text-muted">{{ $formatoFecha }}</span>
                                                    <br><strong>HORA:</strong> <span
                                                        class="text-muted">{{ $formatoHora }}</span>
                                                    <br><strong>MONTO A PAGAR:</strong> <span
                                                        class="text-muted">{{ $bitacoraSelect->Total }}</span>
                                                    @if (4 == 1)
                                                        <br><strong>TIPO DE PAGO:</strong> <span
                                                            class="text-muted">Contado</span>
                                                        <br><strong>PAGO EFECTIVO:</strong> <span
                                                            class="text-muted">{{ $bitacoraSelect->Total }}</span>
                                                        <br><strong>PAGO CON TARJETA:</strong> <span
                                                            class="text-muted">{{ $bitacoraSelect->Total }}</span>
                                                    @else
                                                        <br><strong>TIPO DE PAGO:</strong> <span
                                                            class="text-muted">Crédito</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <hr>
                                            <!-- /.row -->
                                            @if (2 == 2)
                                                <div class="border container m-2">
                                                    <table width="100%">
                                                        <div class="row d-sm-flex mt-3 mb-2">

                                                            <div class="col-md-6">
                                                                <div><strong>OPERARIO : </strong> {{ $operario }}
                                                                </div>
                                                                <div><strong>COLOR : </strong>
                                                                    {{ $bitacoraSelect->Color }}</div>
                                                                <div><strong>MARCA: </strong>
                                                                    {{ $bitacoraSelect->NombreMarca }}</div>
                                                                <div><strong>KILOMETRAJE : </strong>
                                                                    {{ $bitacoraSelect->Kilometro }}</div>
                                                                <div><strong>VENC. SOAT : </strong>
                                                                    {{ $bitacoraSelect->FechaSoat }}</div>

                                                            </div>
                                                            <div class="col-md-6">
                                                                <div><strong>PLACA : </strong>
                                                                    {{ $bitacoraSelect->PlacaVehiculo }}</div>
                                                                <div><strong>AÑO: </strong>
                                                                    {{ $bitacoraSelect->Anio }}
                                                                </div>
                                                                <div><strong>MODELO : </strong>
                                                                    {{ $bitacoraSelect->NombreModelo }}</div>
                                                                <div><strong>HOROMETRO: </strong>
                                                                    {{ $bitacoraSelect->Horometro }}</div>
                                                                <div><strong>VENC. REV. TÉCNICA: </strong>
                                                                    {{ $bitacoraSelect->FechaRevTecnica }}</div>
                                                            </div>

                                                            <div class="col-md-12">
                                                                <br><strong>TRABAJO A REALIZAR: </strong>
                                                                {{ $bitacoraSelect->Trabajos }}
                                                            </div>
                                                        </div>
                                                    </table>
                                                </div>
                                            @endif
                                            <hr class="border-0">
                                            <table id="table" class="table table-bordered table-responsive-sm"
                                                style="width: 100%">
                                                <thead>
                                                    <tr class="bg-info-dark text-white">
                                                        <th class="text-center">Código</th>
                                                        <th>Descripción</th>
                                                        <th>Detalle</th>
                                                        {{-- <th>Marca</th> --}}
                                                        <th class="text-center">Uni/Medida</th>
                                                        <th class="text-center">Precio Venta</th>
                                                        <th class="text-center">Descuento</th>
                                                        <th class="text-center">Cantidad</th>
                                                        <th class="text-center">Importe</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($items as $item)
                                                        <tr>
                                                            <td scope="row">{{ $item->Codigo }}</td>
                                                            <td>{{ $item->Descripcion }}</td>
                                                            <td>{{ $item->Detalle }}</td>
                                                            {{-- <td>{{ $item->Marca }}</td> --}}
                                                            <td>
                                                                {{ $item->TextUnidad }}
                                                            </td>
                                                            <td>{{ number_format($item->PrecioUnidadReal, 2, '.', ',') }}
                                                            </td>
                                                            <td>{{ number_format($item->Descuento, 2, '.', ',') }}
                                                            </td>
                                                            <td>{{ $item->Cantidad }}</td>
                                                            <td>{{ number_format($item->Importe, 2, '.', ',') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <div class="row mt-4">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Observación</label>
                                                        <textarea id="observacion" class="form-control"
                                                            rows="4">{{ $bitacoraSelect->Observacion }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 invoice-sum">
                                                    <ul class="list-unstyled">
                                                        <li>SUBTOTAL: {{ $bitacoraSelect->SubTotal }}</li>
                                                        <li>DESCUENTO: {{ $bitacoraSelect->Exonerada }}</li>
                                                        <li>IGV(18%): {{ $bitacoraSelect->Igv }}</li>
                                                        <li><strong>TOTAL : {{ $bitacoraSelect->Total }}</strong>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="form-actions btn-list mt-3">
                                                <a href="javascript: history.go(-1)"><button class="btn btn-info"
                                                        type="button">Volver</button></a>
                                            </div>
                                            <!-- /.row -->
                                        </div>
                                        <!-- /.ecommerce-invoice -->
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
        @include('schemas.schemaFooter')
    </div>
    <!--/ #wrapper -->
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.9/metisMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            $(document).ready(function() {
                $('#table').DataTable({
                    responsive: true,
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "searching": false
                });
            });

        });
    </script>
    <script>
        function cargarCorreo() {
            var venta = 0;
            var numCeroIzq = 0;
            $('#inpCorreo').val(venta['Email']);
            $('#inpCliente').val(venta['Nombres']);
            $('#inpComprobante').val(venta['Serie'] + '-' + numCeroIzq);
        }
    </script>
</body>

</html>
