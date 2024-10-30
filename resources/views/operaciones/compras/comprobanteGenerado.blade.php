<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pace.css') }}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Comprobante Generado</title>
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
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link href="{{ asset('assets/css/newStyles.css?v=' . time()) }}" rel="stylesheet" type='text/css'>
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
                    <div class="d-flex justify-content-between align-items-center flex-wrap my-3">
                        <div class="page-title-left">
                            <h6 class="page-title-heading mr-0 mr-r-5">Detalles de Compra</h6>
                        </div>
                        <!-- /.page-title-left -->
                        <input type="hidden" name="vale" value="vale">
                        <div class="page-title-right">
                            <div class="d-flex flex-wrap">
                                @if ($compraSelect->Estado != 'Pendiente')
                                    <a class="p-1"
                                        href="../../compras/descargar-vale-compras/{{ $compraSelect->IdCompras }}"
                                        target="_blank"><button class="btn btn-block btn-info ripple"><i
                                                class="list-icon material-icons fs-20 mr-1">picture_as_pdf</i>Vale
                                            Almacen</button></a>
                                @endif
                                <a class="p-1" href="../../compras/imprimir/{{ $compraSelect->IdCompras }}"
                                    target="_blank"><button class="btn btn-block btn-primary ripple"><i
                                            class="list-icon material-icons fs-20">print</i></button></a>
                                <a class="p-1" href="../../compras/descargar/{{ $compraSelect->IdCompras }}"><button
                                        class="btn btn-block btn-primary ripple"><i
                                            class="list-icon material-icons fs-20">picture_as_pdf</i></button></a>

                            </div>
                        </div>
                    </div>
                    @if (session('status'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($arrayItems != null)
                        <div class="alert alert-danger"> 
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <p>Atención: Los siguientes productos tienen valor de Costo igual o mayor que el Precio</p>
                            @foreach ($arrayItems as $item)
                            <p>- {{ $item }}</p><br>
                            @endforeach
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
                                                    <h5>Factura:
                                                        {{ $compraSelect->Serie }}-{{ $compraSelect->Numero }}</h5>
                                                </div>
                                                <div class="col-md-6 text-right d-none d-sm-block">
                                                    <strong>PROVEEDOR:</strong> {{ $compraSelect->Nombres }}
                                                    <br><strong>RAZ. SOCIAL:</strong> {{ $compraSelect->RazonSocial }}
                                                    <br><strong>{{ $compraSelect->TipoDoc }}:</strong>
                                                    {{ $compraSelect->NumeroDocumento }}
                                                    <br><strong>DIRECCIÓN:</strong> {{ $compraSelect->DirProveedor }}
                                                    @if (strtolower($compraSelect->TipoDoc) == 'ruc' && $compraSelect->PersonaContacto != null)
                                                        <br><span class="badge badge-warning fs-12"><strong
                                                                class="text-break">CONTACTO:</strong>
                                                            <b
                                                                class="fs-15">{{ $compraSelect->PersonaContacto }}</b></span>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 d-block d-sm-none"><strong>PROVEEDOR:</strong>
                                                    {{ $compraSelect->Nombres }}
                                                    <br><strong>RAZ. SOCIAL:</strong> {{ $compraSelect->RazonSocial }}
                                                    <br><strong>{{ $compraSelect->TipoDoc }}:</strong>
                                                    {{ $compraSelect->NumeroDocumento }}
                                                    <br><strong>DIRECCIÓN:</strong> {{ $compraSelect->DirProveedor }}
                                                    @if (strtolower($compraSelect->TipoDoc) == 'ruc' && $compraSelect->PersonaContacto != null)
                                                        <br><span class="badge badge-warning fs-12"><strong
                                                                class="text-break">CONTACTO:</strong>
                                                            <b
                                                                class="fs-15">{{ $compraSelect->PersonaContacto }}</b></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- /.row -->
                                            <hr>
                                            <div class="d-sm-flex">
                                                <div class="col-md-6">
                                                    <h6 class="mr-t-0">Usuario</h6><strong>NOMBRES:</strong>
                                                    {{ $compraSelect->Usuario }}
                                                    <br><strong>DNI:</strong> {{ $compraSelect->DNI }}
                                                    <br><strong>SUCURSAL:</strong> {{ $compraSelect->Sucursal }}
                                                    <br><strong>DIRECCIÓN:</strong> {{ $compraSelect->Local }}
                                                </div>
                                                <div class="col-md-6 text-right d-none d-sm-block">
                                                    <h6 class="mr-t-0">Detalles:</h6>
                                                    <strong>FECHA:</strong> <span
                                                        class="text-muted">{{ $formatoFecha }}</span>
                                                    <br><strong>HORA:</strong> <span
                                                        class="text-muted">{{ $formatoHora }}</span>
                                                    <br><strong>MONTO A PAGAR:</strong> <span
                                                        class="text-muted">{{ $compraSelect->Total }}</span>
                                                    <br><strong>TIPO MONEDA:</strong> <span class="text-muted">
                                                        @if ($compraSelect->IdTipoMoneda == 1)
                                                            Soles
                                                        @else
                                                            Dólares
                                                        @endif
                                                    </span>
                                                    <br><strong>TIPO OPERACIÓN:</strong> <span class="text-muted">
                                                        @if ($compraSelect->TipoCompra == 1)
                                                            Gravada
                                                        @else
                                                            Exonerada
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="col-md-6 d-block d-sm-none">
                                                    <h6 class="mr-t-0">Detalles:</h6>
                                                    <strong>FECHA:</strong> <span
                                                        class="text-muted">{{ $formatoFecha }}</span>
                                                    <br><strong>HORA:</strong> <span
                                                        class="text-muted">{{ $formatoHora }}</span>
                                                    <br><strong>MONTO A PAGAR:</strong> <span
                                                        class="text-muted">{{ $compraSelect->Total }}</span>
                                                    <br><strong>TIPO MONEDA:</strong> <span class="text-muted">
                                                        @if ($compraSelect->IdTipoMoneda == 1)
                                                            Soles
                                                        @else
                                                            Dólares
                                                        @endif
                                                    </span>
                                                    <br><strong>TIPO OPERACIÓN:</strong> <span class="text-muted">
                                                        @if ($compraSelect->TipoCompra == 1)
                                                            Gravada
                                                        @else
                                                            Exonerada
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            <!-- /.row -->
                                            <hr class="border-0">
                                            <table id="table" class="table table-bordered table-responsive-sm"
                                                style="width: 100%">
                                                <thead>
                                                    <tr class="bg-primary-dark text-white">
                                                        <th class="text-center">Código</th>
                                                        <th>Descripción</th>
                                                        <th class="text-center">Uni/Medida</th>
                                                        <th class="text-center">Precio Venta</th>
                                                        <th class="text-center">Precio Costo</th>
                                                        <th class="text-center">Cantidad</th>
                                                        <th class="text-center">Importe</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($items as $item)
                                                        <tr>
                                                            <td scope="row">{{ $item->Cod }}</td>
                                                            <td>{{ $item->Descripcion }}</td>
                                                            <td>{{ $item->UniMedida }}</td>
                                                            @if ($compraSelect->TipoCompra == 1)
                                                                <td>{{ $item->Precio }}</td>
                                                                <td>{{ $item->PrecioCosto }}</td>
                                                            @else
                                                                <td>{{ round($item->Precio / 1.18, 2) }}</td>
                                                                <td>{{ round($item->PrecioCosto / 1.18, 2) }}</td>
                                                            @endif
                                                            <td>{{ $item->Cantidad }}</td>
                                                            <td>{{ $item->Importe }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <div class="row mt-4">
                                                <div class="col-lg-8 col-md-12">
                                                    <div class="form-group">
                                                        <label>Observación</label>
                                                        <textarea id="observacion" class="form-control" rows="4">{{ $compraSelect->Observacion }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <!--<p>Thanks for your business</p>
                                                <ul class="text-muted small">
                                                    <li>Aeserunt tenetur cum nihil repudiandae perferendis fuga vitae corporis!</li>
                                                    <li>Laborum, necessitatibus recusandae ullam at iusto dolore.</li>
                                                    <li>Voluptatum aperiam voluptates quasi!</li>
                                                    <li>Assumenda, iusto, consequuntur corporis atque culpa saepe magnam recusandae</li>
                                                    <li>Possimus odio ipsam magni sint reiciendis unde amet</li>
                                                </ul>-->
                                                </div>
                                                <div class="col-md-4 invoice-sum">
                                                    <ul class="list-unstyled">
                                                        <li>
                                                            @if ($compraSelect->TipoCompra == 1)
                                                                OP. GRAVADAS:
                                                            @else
                                                                OP. EXONERADAS:
                                                            @endif
                                                            {{ $compraSelect->Subtotal }}
                                                        </li>
                                                        <li>
                                                            @if ($compraSelect->TipoCompra == 1)
                                                                IGV(18%):
                                                            @else
                                                                IGV(0%):
                                                            @endif
                                                            {{ $compraSelect->IGV }}
                                                        </li>
                                                        <li><strong>TOTAL : {{ $compraSelect->Total }}</strong>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="form-actions btn-list mt-3">
                                                <a href="../../compras/lista-compras"><button class="btn btn-primary"
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

            function redondeo(num) {
                /*var flotante = parseFloat(numero);
                var resultado = Math.round(flotante * Math.pow(10, decimales)) / Math.pow(10, decimales);
                return resultado;*/

                if (num == 0 || num == "0.00") return "0.00";
                if (!num || num == 'NaN') return '-';
                if (num == 'Infinity') return '&#x221e;';
                num = num.toString().replace(/\$|\,/g, '');
                if (isNaN(num))
                    num = "0";
                sign = (num == (num = Math.abs(num)));
                num = Math.floor(num * 100 + 0.50000000001);
                cents = num % 100;
                num = Math.floor(num / 100).toString();
                if (cents < 10)
                    cents = "0" + cents;
                for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
                    num = num.substring(0, num.length - (4 * i + 3)) + '' + num.substring(num.length - (4 * i + 3));
                return (((sign) ? '' : '-') + num + '.' + cents);
            }
        </script>

</body>

</html>
