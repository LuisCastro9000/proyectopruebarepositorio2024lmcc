<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <!--<link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon.png">-->
    <!--<link rel="stylesheet" href="{{ asset('assets/css/pace.css') }}">-->
    <link rel="stylesheet" href="{{ asset('assets/css/center-circle.css') }}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Panel</title>

    {{-- iconos --}}
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CSS -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/material-icons/material-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/mono-social-icons/monosocialiconsfont.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('assets/vendors/feather-icons/feather.css') }}" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/css/perfect-scrollbar.min.css"
        rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
        type="text/css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet"
        type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/jqvmap.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet"
        type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"
        rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/mk-notifications.min.css') }}" rel="stylesheet" />
    <!-- Head Libs -->
    <script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
    <script data-pace-options='{ "ajax": false, "selectors": [ "img" ]}' src="{{ asset('assets/js/pace.min.js') }}">
    </script>

    <style>
        .f-size-18 {
            font-size: 18px;
        }

        .btn-administracion {
            background-color: mediumseagreen;
            color: #ffff;
            cursor: pointer;
        }

        .btn-facturacion {
            background-color: #0095E8;
            color: #ffff;
            cursor: pointer;
        }

        .nav-item {
            /* background-color: #EFF2F5; */
            background-color: #faecdf;
            border-radius: 5px;
        }

        .nav-item .active {
            background-color: #F98518 !important;
        }

        .nav-link {
            color: #F98518;
        }
    </style>
</head>

<body class="sidebar-horizontal" onload="startTime()">
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
                <!-- =================================== -->
                <!-- Different data widgets ============ -->
                <!-- =================================== -->
                <div class="container">
                    <div class="widget-list row">
                        <div class="widget-holder widget-full-height col-12">
                            @if (session('status'))
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    {{ session('status') }}
                                </div>
                            @endif
                            @if ($mensajeMostrar->Estado == 1)
                                <div class="text-center">
                                    <marquee behavior="scroll" direction="left" class="fs-24 text-danger">
                                        {{ $mensajeMostrar->Descripcion }}
                                    </marquee>
                                </div>
                            @endif
                            <div class="widget-bg zi-1" style="background-color: #eff2f7">
                                <div class="widget-body py-2">
                                    <div class="pos-0 zi-n-1 d-none d-lg-block"
                                        style="background-repeat: no-repeat;background-size: auto 100%;background-position: 95% center; background-image: url('{{ $logo }}')">
                                    </div>
                                    <div class="media d-sm-flex d-block">
                                        <div class="media-body">
                                            <h4 class="fw-300">Bienvenido, {{ $usuarioSelect->Nombre }}</h4>
                                            @if ($usuarioSelect->IdOperador != 1)
                                                {!! Form::open(['url' => '/cambiar-sucursal', 'method' => 'POST', 'files' => true, 'class' => 'form-material']) !!}
                                                {{ csrf_field() }}
                                                <h6 class="fw-300 text-body">SUCURSAL:
                                                    @if ($usuarioSelect->IdOperador == 2)
                                                        <select class="fs-16" name="sucursal">
                                                            @foreach ($sucursales as $sucursal)
                                                                @if ($sucursal->IdSucursal == $usuarioSelect->IdSucursal)
                                                                    <option class="fs-13" selected
                                                                        value="{{ $sucursal->IdSucursal }}">
                                                                        {{ $sucursal->Nombre }}
                                                                    </option>
                                                                @else
                                                                    <option class="fs-13"
                                                                        value="{{ $sucursal->IdSucursal }}">
                                                                        {{ $sucursal->Nombre }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        <button class="btn btn-default fs-11"
                                                            type="submit">Cambiar</button>
                                                    @else
                                                        <select class="fs-16" name="sucursal" disabled>
                                                            @foreach ($sucursales as $sucursal)
                                                                @if ($sucursal->IdSucursal == $usuarioSelect->IdSucursal)
                                                                    <option class="fs-13" selected
                                                                        value="{{ $sucursal->IdSucursal }}">
                                                                        {{ $sucursal->Nombre }}
                                                                    </option>
                                                                @else
                                                                    <option class="fs-13"
                                                                        value="{{ $sucursal->IdSucursal }}">
                                                                        {{ $sucursal->Nombre }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </h6>
                                                {!! Form::close() !!}
                                                <div id="hora"></div>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                                <!-- /.widget-body -->
                            </div>
                            <!-- /.widget-bg -->
                        </div>
                    </div>
                    <!-- /.widget-list -->

                    {{-- ICONOS --}}
                    <div class="row">
                        <div
                            class="col-12 d-flex justify-content-center justify-content-lg-between flex-wrap align-items-center mb-2">
                            <div class="alert alert-warning col-12 col-lg-6" role="alert">
                                Seleccione las siguientes opciones para ver los reportes graficos.
                            </div>
                            <div class="icon d-flex justify-content-center flex-wrap">
                                <a href=" {{ url('panel') }}">
                                    <button type="button" class="btn btn-secondary btn-sm"><i
                                            class='bx bx-share fs-20 icono-vehicular mr-1'></i> Ir Panel
                                        Principal</button>
                                </a>
                                <a href=" {{ url('area-administrativa') }}">
                                    <button type="button" class="btn btn-administracion btn-sm ml-2"><i
                                            class='bx bxs-user-detail fs-20 mr-1'></i> Area Administrativa</button>
                                </a>
                                <a href="  ">
                                    <button type="button" class="btn btn-facturacion btn-sm ml-2"><i
                                            class='bx bx-file fs-20  mr-1'></i> Area Facturación</button>
                                </a>
                            </div>
                        </div>
                    </div>
                    {{-- FIN --}}

                    <div class="row ">
                        <section class="col-12">
                            <article class="mt-4">
                                <ul class="nav nav-pills mb-3 d-flex justify-content-lg-around  justify-content-center"
                                    id="pills-tab" role="tablist">
                                    <li class="nav-item mb-lg-0 mb-2 " role="presentation">
                                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill"
                                            href="#pills-home" role="tab" aria-controls="pills-home"
                                            aria-selected="true">Status Vehicular</a>
                                    </li>
                                    <li class="nav-item mb-lg-0 mb-2 ml-2 ml-lg-0" role="presentation">
                                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill"
                                            href="#pills-profile" role="tab" aria-controls="pills-profile"
                                            aria-selected="false">Checklist</a>
                                    </li>

                                    <li class="nav-item mb-lg-0 mb-2 ml-2 ml-lg-0" role="presentation">
                                        <a class="nav-link" id="pills-contact-tab" data-toggle="pill"
                                            href="#pills-contact" role="tab" aria-controls="pills-contact"
                                            aria-selected="false">Histórico de Atenciones</a>
                                    </li>
                                    <li class="nav-item mb-lg-0 mb-2 ml-2 ml-lg-0" role="presentation">
                                        <a class="nav-link" id="pills-contact-tab" data-toggle="pill"
                                            href="#pills-contact" role="tab" aria-controls="pills-contact"
                                            aria-selected="false">Soat y Rev Tecnica</a>
                                    </li>
                                    <li class="nav-item mb-lg-0 mb-2 ml-2 ml-lg-0" role="presentation">
                                        <a class="nav-link" id="pills-contact-tab" data-toggle="pill"
                                            href="#pills-contact" role="tab" aria-controls="pills-contact"
                                            aria-selected="false">Productividad Mecánico</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                        aria-labelledby="pills-home-tab">
                                        <div id="graficoFlujoVenta"></div>
                                    </div>
                                    <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                        aria-labelledby="pills-profile-tab">
                                        <div id="graficoFlujoVenta"></div>
                                    </div>
                                    <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                                        aria-labelledby="pills-contact-tab">
                                        <div id="graficoFlujoVenta"></div>
                                    </div>
                                </div>
                            </article>
                        </section>
                    </div>
                </div>
            </main>

        </div>

        <!-- FOOTER -->
        @include('schemas.schemaFooter')
        <!-- FIN -->

    </div>

    {{-- SCRIPT BOXICONS --}}
    <script src="https://unpkg.com/boxicons@2.1.1/dist/boxicons.js"></script>
    {{-- FIN --}}
    {{-- SCRIPT DEL NUEVO GRAFICO  REPORTE VENTAS --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    {{-- FIN --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.9/metisMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/1.9.2/countUp.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/jquery.vmap.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/jquery.vmap.usa.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-circle-progress/1.2.2/circle-progress.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="{{ asset('assets/js/moment-lang.js') }}"></script>
    <script src="{{ asset('assets/js/mk-notifications.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>

    <script>
        var options = {
            series: [{
                name: 'series1',
                data: [31, 40, 28, 51, 42, 109, 100]
            }, {
                name: 'series2',
                data: [11, 32, 45, 32, 34, 52, 41]
            }],
            chart: {
                height: 350,
                type: 'area'
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                type: 'datetime',
                categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z",
                    "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z",
                    "2018-09-19T06:30:00.000Z"
                ]
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm'
                },
            },
        };

        var chart = new ApexCharts(document.querySelector("#graficoFlujoVenta"), options);
        chart.render()
    </script>


</body>

</html>
