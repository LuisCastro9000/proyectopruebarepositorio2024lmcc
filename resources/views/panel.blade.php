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
    <link href="{{ asset('assets/css/newStyles.css?v=' . time()) }}" rel="stylesheet" type='text/css'>
    <!-- Head Libs -->
    <script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
    <script data-pace-options='{ "ajax": false, "selectors": [ "img" ]}' src="{{ asset('assets/js/pace.min.js') }}">
    </script>
    <!--<script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>-->
    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pace-js@latest/pace-theme-default.min.css">-->


    <style>
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

        .btn-vehicular {
            background-color: #f8973d;
            color: #ffff;
        }

        .btn-vehicular:hover {
            background-color: #F98518;
        }

        .z-index_Grafico {
            opacity: .99;
        }

        .fondoResponsivo {
            width: 400px;
            height: 200px;
        }
    </style>
</head>

<body id="body" class="sidebar-horizontal" onload="startTime()">
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
            {{-- Nuevo Codigo --}}
            @if ($mensajeSunat->Estado == 1)
                <div class="alert alert-danger text-center" role="alert">
                    <span>{{ $mensajeSunat->Descripcion }}</span>
                </div>
            @endif
            {{-- Fin --}}


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
                            @if ($mensajeActualizacion->IdRubro == $empresa->IdRubro)
                                @if ($mensajeActualizacion->Estado == 1)
                                    <section class="d-flex  align-items-center">
                                        <marquee behavior="scroll" direction="left" class="fs-24 text-danger">
                                            {{ $mensajeActualizacion->Descripcion }}
                                        </marquee>
                                        @if ($mensajeActualizacion->UrlVideo != null)
                                            <article>
                                                <a target="_blank" href="{{ $mensajeActualizacion->UrlVideo }}"
                                                    class="bagde badge-secondary"><span
                                                        class="badge badge-secondary fs-14"><i
                                                            class='bx bxs-hand-left bx-flip-vertical fs-18 mr-1'></i>Ver
                                                        Video</span></a>
                                            </article>
                                        @endif
                                    </section>
                                @endif
                            @elseif($mensajeActualizacion->IdRubro == 0)
                                @if ($mensajeActualizacion->Estado == 1)
                                    <section class="d-flex  align-items-center">
                                        <marquee behavior="scroll" direction="left" class="fs-24 text-danger">
                                            {{ $mensajeActualizacion->Descripcion }}
                                        </marquee>
                                        @if ($mensajeActualizacion->UrlVideo != null)
                                            <article>
                                                <a target="_blank" href="{{ $mensajeActualizacion->UrlVideo }}"
                                                    class="bagde badge-secondary"><span
                                                        class="badge badge-secondary fs-14"><i
                                                            class='bx bxs-hand-left bx-flip-vertical fs-18 mr-1'></i>Ver
                                                        Video</span></a>
                                            </article>
                                        @endif
                                    </section>
                                @endif
                            @endif
                            <div class="widget-bg zi-1" style="background-color: #eff2f7">
                                <div class="widget-body py-2">
                                    <section class="d-flex justify-content-between align-items-center">
                                        <div class="d-block">
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

                                        <div class="fondoResponsivo d-none d-lg-block">
                                            @if ($logo !== null && str_contains($logo, config('variablesGlobales.urlDominioAmazonS3')))
                                                <img src="{{ $logo }}" alt=""
                                                    class="img-fluid float-right" style="height: 100%">
                                            @else
                                                <img src="{{ config('variablesGlobales.urlDominioAmazonS3') . $logo }}"
                                                    alt="" class="img-fluid float-right"
                                                    style="height: 100%">
                                            @endif
                                        </div>
                                    </section>
                                </div>
                                <!-- /.widget-body -->
                            </div>

                            @if (in_array($fechahoy, $fechasDeCaducidadCdt))
                                <section>
                                    <div class="w-100 py-3 text-center" style="background-color: #f8e7eb">
                                        <span style="color: #F1416C;">
                                            El Certificado Digital Tributario esta por vencer el
                                            {{ date('Y-m-d', strtotime($selectUsuarioSuscripcion->FechaFinalCDT)) }},
                                            proceda a renovarlo antes
                                            que se inhabilite el envío de documentos electrónicos
                                        </span>
                                    </div>
                                </section>
                            @endif
                            @if (count($fechasDeCaducidadCdt) >= 1 &&
                                    $fechahoy > end($fechasDeCaducidadCdt) &&
                                    $fechahoy < $selectUsuarioSuscripcion->FechaFinalCDT)
                                <section>
                                    <div class="w-100 py-3 text-center" style="background-color: #f8e7eb">
                                        <span style="color: #F1416C;">
                                            El Certificado Digital Tributario esta por vencer el
                                            {{ date('Y-m-d', strtotime($selectUsuarioSuscripcion->FechaFinalCDT)) }},
                                            proceda a renovarlo antes
                                            que se inhabilite el envío de documentos electrónicos
                                        </span>
                                    </div>
                                </section>
                            @endif

                            @if (count($fechasDeCaducidadCdt) >= 1 && $fechahoy > $selectUsuarioSuscripcion->FechaFinalCDT)
                                <section>
                                    <div class="w-100 py-3 text-center" style="background-color: #f8e7eb">
                                        <span style="color: #F1416C;">
                                            El Certificado Digital Tributario ya venció en la fecha
                                            {{ date('Y-m-d', strtotime($selectUsuarioSuscripcion->FechaFinalCDT)) }},
                                            proceda a renovarlo para habilitar el envío de documentos electrónicos
                                        </span>
                                    </div>
                                </section>
                            @endif
                            <!-- /.widget-bg -->
                        </div>
                        <!-- /.widget-holder -->

                        {{-- ICONOS --}}
                        <div class="col-12 d-flex justify-content-center justify-content-lg-end flex-wrap mb-5">
                            @if ($permisosBotones->contains('Nombre', 'Area Administracion'))
                                <a href=" {{ url('area-administrativa') }}">
                                    <button type="button" class="btn btn-administracion btn-sm"><i
                                            class='bx bxs-user-detail fs-20  mr-1'></i> Area Administrativa</button>
                                </a>
                            @endif
                            @if ($permisosBotones->contains('Nombre', 'Area Vehicular'))
                                <a href=" {{ url('area-vehicular') }}">
                                    <button type="button" class="btn btn-vehicular btn-sm ml-2"><i
                                            class='bx bxs-car-mechanic fs-20 mr-1'></i> Area Vehicular</button>
                                </a>
                            @endif
                            @if ($permisosBotones->contains('Nombre', 'Area Facturacion'))
                                <a href="{{ url('area-facturacion') }}">
                                    <button type="button" class="btn btn-facturacion btn-sm ml-2"><i
                                            class='bx bx-file fs-20 mr-1'></i> Area Facturación</button>
                                </a>
                            @endif
                        </div>

                        {{-- FIN --}}

                        {{-- GRAFIVO RADAR --}}
                        <div class="col-lg-6 col-12 mb-4">
                            <div class="card">
                                <div id="graficoComprobantes">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12 mb-4 z-index_Grafico">
                            <div class="card ">
                                <div id="graficoComprasVentas">
                                </div>
                            </div>
                        </div>
                        @if ($usuarioSelect->IdOperador == 2)
                            <div class="col-lg-12 col-12 mb-4 z-index_Grafico">
                                <div class="card ">
                                    <div id="graficoVentas">
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($permisos->contains('IdPermiso', 1))
                            {{-- @if ($usuarioSelect->IdOperador == 2 && $usuarioSelect->Cliente == 1) --}}
                            @if ($usuarioSelect->IdOperador == 2)
                                <div
                                    class="widget-holder widget-full-height widget-no-padding widget-flex radius-5 col-12 mt-2 mb-4">
                                    <div class="widget-bg">
                                        <div class="widget-body">
                                            <div class="container-fluid">
                                                <div class="row text-inverse"
                                                    style="background: linear-gradient(#72c0fc,#5CB7FC)">
                                                    <!-- /.col-3 -->
                                                    <div class="col-lg-3 col-md-6  col-sm-6 col-12 text-center pd-30 justify-content-center"
                                                        style="border-right: 1px solid rgba(255,255,255,0.3)">
                                                        <h6
                                                            class="text-muted headings-font-family my-0 text-uppercase fs-11 fw-bold lh-16 d-block">
                                                            Total de venta diaria:</h6>
                                                        @if ($totalVentasDiaria[0]->ImporteTotal == null)
                                                            <h4 class="mt-0 mb-1">S/ <span class="counter">0.00</span>
                                                            </h4>
                                                        @else
                                                            <h4 class="mt-0 mb-1">S/ <span
                                                                    class="counter">{{ $totalVentasDiaria[0]->ImporteTotal }}</span>
                                                            </h4>
                                                        @endif
                                                    </div>
                                                    <!-- /.col-3 -->
                                                    <div class="col-lg-3 col-md-6  col-sm-6 col-12 text-center pd-30 justify-content-center"
                                                        style="border-right: 1px solid rgba(255,255,255,0.3)">
                                                        <h6
                                                            class="text-muted headings-font-family my-0 text-uppercase fs-11 fw-bold lh-16 d-block">
                                                            Total de ganancia diaria:</h6>
                                                        @if ($totalGananciaDiaria[0]->GananciaTotal == null)
                                                            <h4 class="mt-0 mb-1">S/ <span class="counter">0.00</span>
                                                            </h4>
                                                        @else
                                                            <h4 class="mt-0 mb-1">S/ <span
                                                                    class="counter">{{ $totalGananciaDiaria[0]->GananciaTotal }}</span>
                                                            </h4>
                                                        @endif
                                                    </div>
                                                    <!-- /.col-3 -->
                                                    <div class="col-lg-3 col-md-6  col-sm-6 col-12 text-center pd-30 justify-content-center"
                                                        style="border-right: 1px solid rgba(255,255,255,0.3)">
                                                        <h6
                                                            class="text-muted headings-font-family my-0 text-uppercase fs-11 fw-bold lh-16 d-block">
                                                            Ventas del mes en curso:</h6>
                                                        @if ($totalVentasMensual[0]->ImporteTotal == null)
                                                            <h4 class="mt-0 mb-1">S/ <span class="counter">0.00</span>
                                                            </h4>
                                                        @else
                                                            <h4 class="mt-0 mb-1">S/ <span
                                                                    class="counter">{{ $totalVentasMensual[0]->ImporteTotal }}</span>
                                                            </h4>
                                                        @endif
                                                    </div>
                                                    <!-- /.col-3 -->
                                                    <div
                                                        class="col-lg-3 col-md-6  col-sm-6 col-12 text-center pd-30 justify-content-center">
                                                        <h6
                                                            class="text-muted headings-font-family my-0 text-uppercase fs-11 fw-bold lh-16 d-block">
                                                            Ganancia del mes en curso:</h6>
                                                        @if ($totalGananciaMensual[0]->GananciaTotal == null)
                                                            <h4 class="mt-0 mb-1">S/ <span class="counter">0.00</span>
                                                            </h4>
                                                        @else
                                                            <h4 class="mt-0 mb-1">S/ <span
                                                                    class="counter">{{ $totalGananciaMensual[0]->GananciaTotal }}</span>
                                                            </h4>
                                                        @endif
                                                    </div>
                                                </div>
                                                <!-- /.row -->
                                            </div>
                                            <!-- /.container-fluid -->
                                        </div>
                                        <!-- /.widget-body -->
                                    </div>
                                    <!-- /.widget-bg -->
                                </div>
                            @endif
                        @endif

                        {{-- OCULTAR GRAFICO PARA OPERARIOS QUE NO TIENEN EL SUB NIVEL BANCOS-->TIPO DE CAMBIO --}}
                        @if ($subniveles->contains('IdSubNivel', 46))
                            @if ($usuarioSelect->IdOperador == 2 || ($usuarioSelect->IdOperador == 8 && $usuarioSelect->Cliente == 1))
                                <div class="col-lg-12 col-12 mt-5 mb-4 z-index_Grafico">
                                    <div class="card ">
                                        <div id="graficoVentasDolares">
                                        </div>
                                    </div>
                                </div>
                                @if ($permisos->contains('IdPermiso', 1))
                                    {{-- REPORTE EN DOLARES --}}
                                    <div
                                        class="widget-holder widget-full-height widget-no-padding widget-flex radius-5 col-12">
                                        <div class="widget-bg">
                                            <div class="widget-body">
                                                <div class="container-fluid">
                                                    <div class="row text-inverse"
                                                        style="background: linear-gradient(#72c0fc,#5CB7FC)">
                                                        <div class="col-lg-3 col-md-6  col-sm-6 col-12 text-center pd-30 justify-content-center"
                                                            style="border-right: 1px solid rgba(255,255,255,0.3)">
                                                            <h6
                                                                class="text-muted headings-font-family my-0 text-uppercase fs-11 fw-bold lh-16 d-block">
                                                                Total de venta diaria:</h6>
                                                            @if ($totalVentasDiariaDolares[0]->ImporteTotal == null)
                                                                <h4 class="mt-0 mb-1">$ <span
                                                                        class="counter">0.00</span></h4>
                                                            @else
                                                                <h4 class="mt-0 mb-1">$ <span
                                                                        class="counter">{{ $totalVentasDiariaDolares[0]->ImporteTotal }}</span>
                                                                </h4>
                                                            @endif
                                                        </div>
                                                        <!-- /.col-6 -->
                                                        <div class="col-lg-3 col-md-6 col-sm-6 col-12 text-center pd-30 justify-content-center"
                                                            style="border-right: 1px solid rgba(255,255,255,0.3)">
                                                            <h6
                                                                class="text-muted headings-font-family my-0 text-uppercase fs-11 fw-bold lh-16 d-block">
                                                                Total de ganancia diaria:</h6>
                                                            @if ($totalGananciaDiariaDolares[0]->GananciaTotal == null)
                                                                <h4 class="mt-0 mb-1">$ <span
                                                                        class="counter">0.00</span></h4>
                                                            @else
                                                                <h4 class="mt-0 mb-1">$ <span
                                                                        class="counter">{{ $totalGananciaDiariaDolares[0]->GananciaTotal }}</span>
                                                                </h4>
                                                            @endif
                                                        </div>
                                                        <!-- /.col-6 -->
                                                        <div class="col-lg-3 col-md-6 col-sm-6 col-12 text-center pd-30 justify-content-center"
                                                            style="border-right: 1px solid rgba(255,255,255,0.3)">
                                                            <h6
                                                                class="text-muted headings-font-family my-0 text-uppercase fs-11 fw-bold lh-16 d-block">
                                                                Ventas del mes en curso:</h6>
                                                            @if ($totalVentasMensualDolares[0]->ImporteTotal == null)
                                                                <h4 class="mt-0 mb-1">$ <span
                                                                        class="counter">0.00</span></h4>
                                                            @else
                                                                <h4 class="mt-0 mb-1">$ <span
                                                                        class="counter">{{ $totalVentasMensualDolares[0]->ImporteTotal }}</span>
                                                                </h4>
                                                            @endif
                                                        </div>
                                                        <!-- /.col-6 -->
                                                        <div
                                                            class="col-lg-3 col-md-6 col-sm-6 col-12 text-center pd-30 justify-content-center">
                                                            <h6
                                                                class="text-muted headings-font-family my-0 text-uppercase fs-11 fw-bold lh-16 d-block">
                                                                Ganancia del mes en curso:</h6>
                                                            @if ($totalGananciaMensualDolares[0]->GananciaTotal == null)
                                                                <h4 class="mt-0 mb-1">$ <span
                                                                        class="counter">0.00</span></h4>
                                                            @else
                                                                <h4 class="mt-0 mb-1">$ <span
                                                                        class="counter">{{ $totalGananciaMensualDolares[0]->GananciaTotal }}</span>
                                                                </h4>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <!-- /.row -->
                                                </div>
                                                <!-- /.container-fluid -->
                                            </div>
                                            <!-- /.widget-body -->
                                        </div>
                                        <!-- /.widget-bg -->
                                    </div>
                                    {{-- FIN --}}
                                @endif
                            @endif
                        @endif
                        {{-- FIN --}}

                        <div class="col-lg-6 col-12 mt-5 mb-4">
                            <div class="card ">
                                <div id="graficoMasVendidosXmes">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-12 mt-5 mb-4">
                            <div class="card">
                                <div id="graficoMasVendidoXDias">
                                </div>
                            </div>
                        </div>
                        {{-- FIN --}}
                    </div>
                    <!-- /.widget-list -->
                </div>

                <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog"
                    aria-labelledby="basicModal" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="">
                                <div class="text-center">
                                    <h6 class="text-danger">ATENCIÓN</h6>
                                </div>
                            </div>
                            <div class="modal-body">
                                <div>
                                    <label class="fs-14 negrita" id="textoAtencion"></label>
                                </div>
                                <div>
                                    <label class="fs-12 negrita"><span id="totalPendientes"
                                            class="fs-12 text-danger"></span></label>
                                </div>
                                <div>
                                    <label class="fs-12 negrita"><span id="totalResumen"
                                            class="fs-12 text-danger"></span></label>
                                </div>
                                <div>
                                    <label class="fs-12 negrita"><span id="totalguiaRemision"
                                            class="fs-12 text-danger"></span></label>
                                </div>
                                <div>
                                    <label class="fs-12 negrita"><span id="totalBajaDocumentos"
                                            class="fs-12 text-danger"></span></label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="form-actions btn-list mt-3">
                                    <button class="btn btn-info" type="button" data-dismiss="modal">Aceptar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Nuevo Modal --}}
                <div class="modal fade actualizarDatosUsuario" tabindex="-1" role="dialog"
                    aria-labelledby="myLargeModalLabel" aria-hidden="true" id="myModa">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-body px-4">
                                <div class="row">
                                    <div class="col-12 my-4">
                                        <p class="text-justify fs-16 font-weight-bold text-danger">Por favor debe
                                            actualizar sus datos</p>
                                    </div>
                                    <hr>
                                    <div class="col-12">
                                        @csrf
                                        <div class="form-group">
                                            <label for="formGroupExampleInput">Nombre</label>
                                            <input type="text" class="form-control py-2" id="nombre"
                                                value="{{ $usuarioSelect->Nombre }}" name="nombre">
                                        </div>
                                        <div class="form-group">
                                            <label for="formGroupExampleInput2">Dirección</label>
                                            <input type="text" class="form-control py-2" id="direccion"
                                                value="{{ $usuarioSelect->Direccion }}" name="direccion">
                                        </div>
                                        <div class="form-group">
                                            <label for="formGroupExampleInput2">Dni</label>
                                            <input type="text" class="form-control py-2" id="dni"
                                                value="{{ $usuarioSelect->DNI }}" name="dni">
                                        </div>
                                        <div class="form-group">
                                            <label for="formGroupExampleInput2">Celular</label>
                                            <input type="text" class="form-control py-2" id="celular"
                                                value="{{ $usuarioSelect->Telefono }}" name="celular">
                                            <span id="celular-error" class="error-message text-danger"></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="formGroupExampleInput2">Email</label>
                                            <input type="text" class="form-control py-2" id="email"
                                                value="{{ $usuarioSelect->Email }}" name="email">
                                            <span id="correo-error" class="error-message text-danger"></span>
                                        </div>
                                        <div class="text-center mt-4">
                                            <x-buttonLoader class='btnActualizarPerfil' color='btn btn--verde'
                                                value="btnActualizarDatosModificados">
                                                @slot('textoBoton', 'Entendido')
                                                @slot('textoLoader', 'Actualizando')
                                            </x-buttonLoader>
                                        </div>
                                    </div>
                                    <div class="col-12 text-center mt-3">
                                        <hr>
                                        <div class="d-flex justify-content-around flex-wrap">
                                            @if ($selectUsuarioSuscripcion != null)
                                                <section class="mr-0 mr-md-4">
                                                    <span>Fecha Fin de Renovación</span><br>
                                                    <span
                                                        class="font-weight-bold fs-16">{{ date('Y-m-d', strtotime($selectUsuarioSuscripcion->FechaFinalContrato)) }}</span>
                                                </section>
                                                <section>
                                                    <span>Caducidad de Certificado Digital Tributario</span><br>
                                                    <span
                                                        class="font-weight-bold fs-16">{{ date('Y-m-d', strtotime($selectUsuarioSuscripcion->FechaFinalCDT)) }}</span>
                                                </section>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Fin --}}
                @include('modal._modalActualizarEmailUsuarioCliente')
            </main>
            <!-- /.main-wrappper -->
        </div>
        <!-- /.content-wrapper -->
        <!-- FOOTER -->
        @include('schemas.schemaFooter')

    </div>
    <!--/ #wrapper -->
    <!-- Scripts -->
    {{-- SCRIPT BOXICONS --}}
    <script src="https://unpkg.com/boxicons@2.1.1/dist/boxicons.js"></script>
    {{-- FIN --}}
    <script src="{{ asset('assets/js/libreriasExternas/apexCharts/apexcharts.min.js') }}"></script>
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
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script> --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script src="{{ asset('assets/js/respuestaAjax/respuestaSweetAlert2.js?v=' . time()) }}"></script> --}}
    {{-- <script src="{{ asset('assets/js/perfil/actualizarPerfil.js') }}"></script> --}}
    <script src="{{ asset('assets/js/scriptGlobal/buttonLoaderScript.js') }}"></script>

    <script>
        let $numeroDia = @json($fechaMensaje);
        let $codigoAdmin = @json($codigoAdmin);
        let $fechahoy = @json($fechahoy);
        let $fechaActualizacion = @json($fechaActualizacion);

        if ($fechahoy != $fechaActualizacion) {
            if ($numeroDia == 17 && $codigoAdmin == 1) {
                $(function() {
                    $('.actualizarDatosUsuario').modal({
                        backdrop: 'static',
                        keyboard: false
                    }, 'show');
                })
            }
        }
        // Codigo para validar si el correo del usuario cliente es valido
        // if ($codigoAdmin == 1 && $fechaActualizacion != '2023-08-21') {
        //     $(function() {
        //         $('#modalActualizarEmailUsuarioCliente').modal({
        //             backdrop: 'static',
        //             keyboard: false
        //         }, 'show');
        //     })
        // }

        $(document).on('click', function(e) {
            if (e.target.matches('.btnActualizarPerfil') || e.target.matches('.btnActualizarPerfil *')) {
                const elementoPadre = $(e.target).closest('.btnActualizarPerfil');
                const valorBtn = elementoPadre.val();
                showButtonLoader('.btnActualizarPerfil');
                $('.error-message').text('');
                let email = '';
                let celularError = '';
                let correoError = '';

                if (valorBtn === 'btnActualizarEmailUsuarioCliente') {
                    email = $("#inputEmailCliente").val();
                } else {
                    email = $("#email").val();
                }
                $.ajax({
                    type: 'post',
                    url: 'actualizar-perfil',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "valorBtn": valorBtn,
                        "nombre": $("#nombre").val(),
                        "direccion": $("#direccion").val(),
                        "dni": $("#dni").val(),
                        "celular": $("#celular").val(),
                        "email": email
                    },
                    success: function(data) {
                        console.log(data);
                        if (data.error == 'error') {
                            hideButtonLoader('.btnActualizarPerfil');
                            $('#celular-error').text(data.celularError);
                            $('#correo-error').text(
                                `${data.correoError} ${data.formatoCorreoError}`);
                            $('#correousuario-error').text(
                                `${data.correoError} ${data.formatoCorreoError}`);
                            $('#inputEmailCliente').addClass('border border-danger');
                        }
                        if (data[0] == 'Success') {
                            Swal.fire({
                                icon: 'success',
                                text: data[1],
                                showConfirmButton: false,
                                timer: 1500
                            })
                            $("#modalActualizarEmailUsuarioCliente").modal('hide');
                            $(".actualizarDatosUsuario").modal('hide');
                        }
                    }
                });
            }
        });
    </script>

    <script>
        // NUEVO CODIGO VERIFICAR INCONSISTENCIAS solo para usuarios diferentes del superAdministrador
        @if ($usuarioSelect->IdOperador !== 1 && count($articuloConInconsistencias) > 0)
            Swal.fire({
                title: "<h5 style='color:red'>Se detectó Intermitencia en su linea Internet</h5>",
                html: "<span style='font-size:16px'>Se requiere regularizar su Stock, haga click en el botón ENTENDIDO para proceder. </span>",
                icon: 'warning',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                stopkeydownPropagation: true,
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Entendido',
                yPadding: '1rem'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "administracion/almacen/emparejar-stock";
                }
            })
        @endif

        // NUEVO CODIGO VERIFICAR ACTUALIZACION DE CONTRASEÑA
        const contrasenaActualizada = @json($contrasenaActualizada);
        if (contrasenaActualizada == 'false') {
            Swal.fire({
                title: 'Actualizar contraseña',
                text: 'Es obligatorio actualizar contraseña por su seguridad',
                icon: 'info',
                confirmButtonText: 'Entendido',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = 'cambiar-contrasena';
                }
            })
        }
    </script>

    <script>
        function startTime() {
            var today = new Date();
            var Hora = today.getHours();
            var Minutos = today.getMinutes();
            var Segundos = today.getSeconds();
            if (Hora <= 9) Hora = "0" + Hora;
            if (Minutos <= 9) Minutos = "0" + Minutos;
            if (Segundos <= 9) Segundos = "0" + Segundos;
            var Dia = new Array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
            var Mes = new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre",
                "Octubre", "Noviembre", "Diciembre");
            var Anio = today.getFullYear();
            var Fecha = Dia[today.getDay()] + ", " + today.getDate() + " de " + Mes[today.getMonth()] + " de " + Anio +
                "<br> Hora: ";
            var Script, Total;
            Script = Fecha + Hora + ":" + Minutos + ":" + Segundos;
            Total = Script;
            document.getElementById("hora").innerHTML = Total;
            setTimeout(function() {
                startTime();
            }, 500);
        }
    </script>
    <script>
        $(function() {
            mkNotifications({
                positionY: 'right',
                positionX: 'top',
                scrollable: true,
                rtl: false,
                max: 5
            });

            var compPendientes = <?php echo json_encode(count($comprobantesPendientes)); ?>;
            var resumenPendientes = <?php echo json_encode(count($resumenPendientes)); ?>;
            var guiaRemisionPendientes = <?php echo json_encode(count($guiasRemisionPendientes)); ?>;
            var bajaDocumentosPendientes = <?php echo json_encode(count($bajaDocumentosPendientes)); ?>;
            if (compPendientes > 0 || resumenPendientes > 0 || guiaRemisionPendientes > 0 ||
                bajaDocumentosPendientes > 0) {
                $("#textoAtencion").text("No olvides de enviar tus documentos electrónicos a Sunat");
                if (compPendientes > 0) {
                    mkNoti(
                        'Alerta',
                        'Hay Facturas y/o Notas Electrónicas por enviar', {
                            status: 'warning',
                            dismissable: true,
                            duration: 7000,
                        }
                    );
                }
                if (resumenPendientes > 0) {
                    mkNoti(
                        'Alerta',
                        'Hay Boletas y/o Notas Electrónicas por enviar', {
                            status: 'warning',
                            dismissable: true,
                            duration: 8000,
                        }
                    );
                }
                if (guiaRemisionPendientes > 0) {
                    mkNoti(
                        'Alerta',
                        'Hay Guías de Remisión Electrónicas por enviar', {
                            status: 'warning',
                            dismissable: true,
                            duration: 9000,
                        }
                    );
                }
                if (bajaDocumentosPendientes > 0) {
                    mkNoti(
                        'Alerta',
                        'Hay Bajas de Documentos Pendientes por enviar', {
                            status: 'warning',
                            dismissable: true,
                            duration: 10000,
                        }
                    );
                }
            }

            var usuarioSuscripcion = <?php echo json_encode($usuarioSuscripcion); ?>;
            var mensajeAlerta = <?php echo json_encode($mensajeAlerta); ?>;

            if (usuarioSuscripcion != 0) {
                if (mensajeAlerta == 1) {
                    mkNoti(
                        'Suscripción Mensual',
                        'Tu ciclo de suscripción de AUTOCONTROL esta por finalizar', {
                            status: 'info',
                            dismissable: true,
                            duration: 11000,
                        }
                    );
                }
                if (mensajeAlerta == 2) {
                    mkNoti(
                        'Suscripción Semestral',
                        'Tu ciclo de suscripción de AUTOCONTROL esta por finalizar', {
                            status: 'info',
                            dismissable: true,
                            duration: 11000,
                        }
                    );
                }
                if (mensajeAlerta == 3) {
                    mkNoti(
                        'Suscripción Anual',
                        'Tu ciclo de suscripción de AUTOCONTROL esta por finalizar', {
                            status: 'info',
                            dismissable: true,
                            duration: 11000,
                        }
                    );
                }
                if (mensajeAlerta == 4) {
                    mkNoti(
                        'Suscripción Caducado',
                        'Tu suscripción ha finalizado. Renueva tu pago para seguir con nuestro servicio.', {
                            status: 'danger',
                            dismissable: true,
                            duration: 11000,
                        }
                    );
                }
            }
        });
    </script>



    <script>
        var arrayComprobantes = <?php echo json_encode($arrayComprobantes); ?>;
        var arrayTotalComprobantes = <?php echo json_encode($arrayTotalComprobantes); ?>;
        var options = {
            series: arrayTotalComprobantes,
            chart: {
                height: 390,
                type: 'radialBar',
            },
            plotOptions: {
                radialBar: {
                    offsetY: 0,
                    startAngle: 0,
                    endAngle: 270,
                    hollow: {
                        margin: 5,
                        size: '30%',
                        background: 'transparent',
                        image: undefined,
                    },
                    dataLabels: {
                        name: {
                            show: false,
                        },
                        value: {
                            show: false,
                        }
                    }
                }
            },
            colors: ['#1ab7ea', '#0084ff', '#39539E', '#0077B5'],
            labels: arrayComprobantes,
            legend: {
                show: true,
                floating: true,
                fontSize: '16px',
                position: 'left',
                // offsetX: 160,
                offsetX: 50,
                offsetY: 15,
                labels: {
                    useSeriesColors: true,
                },
                markers: {
                    size: 0
                },
                formatter: function(seriesName, opts) {
                    return seriesName + ":  " + opts.w.globals.series[opts.seriesIndex]
                },
                itemMargin: {
                    vertical: 3
                }
            },
            responsive: [{
                    breakpoint: 450,
                    options: {
                        legend: {
                            show: true,
                            offsetX: 0,
                            offsetY: 50
                        }
                    }
                },
                {
                    breakpoint: 600,
                    options: {
                        legend: {
                            show: true,
                            offsetX: 30,
                            offsetY: 15
                        }
                    }
                },
                {
                    breakpoint: 900,
                    options: {
                        legend: {
                            show: true,
                            offsetX: 80,
                            offsetY: 15
                        }
                    }
                }
            ]
        };

        var chart = new ApexCharts(document.querySelector("#graficoComprobantes"), options);
        chart.render();



        var arrayTotalVentas = <?php echo json_encode($arrayTotalVentas); ?>;
        var arrayTotalCompras = <?php echo json_encode($arrayTotalCompras); ?>;
        var arrayFechasVentasCompras = <?php echo json_encode($arrayFechasVentasCompras); ?>;

        var options = {
            series: [{
                name: 'Ventas',
                type: 'column',
                data: arrayTotalVentas,
            }, {
                name: 'Compras',
                type: 'column',
                data: arrayTotalCompras,
            }],
            chart: {
                height: 350,
                type: 'line',
                stacked: false,
                offsetY: 20,
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: [1, 1, 4]
            },
            title: {
                text: 'Reporte de compras y ventas',
                align: 'center'
            },
            xaxis: {
                categories: arrayFechasVentasCompras,
            },
            yaxis: [{
                    axisTicks: {
                        show: true,
                    },
                    axisBorder: {
                        show: true,
                        color: '#008FFB'
                    },
                    labels: {
                        style: {
                            colors: '#008FFB',
                        }
                    },
                    title: {
                        text: "Ventas por Mes",
                        style: {
                            color: '#008FFB',
                        }
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                {
                    seriesName: 'Ventas',
                    opposite: true,
                    axisTicks: {
                        show: true,
                    },
                    axisBorder: {
                        show: true,
                        color: '#00E396'
                    },
                    labels: {
                        style: {
                            colors: '#00E396',
                        }
                    },
                    title: {
                        text: "Compras por Mes",
                        style: {
                            color: '#00E396',
                        }
                    },
                },

            ],
            tooltip: {
                fixed: {
                    enabled: true,
                    position: 'topLeft', // topRight, topLeft, bottomRight, bottomLeft
                    offsetY: 30,
                    offsetX: 60
                },
            },
            legend: {
                horizontalAlign: 'left',
                offsetX: 40
            }
        };

        var chart = new ApexCharts(document.querySelector("#graficoComprasVentas"), options);
        chart.render();


        var arrayVentasRealizadas = <?php echo json_encode($arrayVentasRealizadas); ?>;
        var arrayVentasMontoTotal = <?php echo json_encode($arrayVentasMontoTotal); ?>;
        var arrayVentasFechas = <?php echo json_encode($arrayVentasFechas); ?>;

        var options = {
            series: [{
                name: 'Monto Total Ventas',
                type: 'column',
                data: arrayVentasMontoTotal,
            }, {
                name: 'Ventas Realizadas',
                type: 'line',
                data: arrayVentasRealizadas,
            }],
            chart: {
                height: 350,
                type: 'line',
            },
            stroke: {
                width: [0, 4]
            },
            title: {
                text: 'Reporte de Ventas X Mes en Soles',
                margin: 50,
                align: 'center'
            },
            dataLabels: {
                enabled: true,
                enabledOnSeries: [1]
            },
            labels: arrayVentasFechas,
            xaxis: {
                //   type: 'datetime'
            },
            yaxis: [{
                title: {
                    text: 'Máximo de ventas Por Mes',
                },

            }, {
                opposite: true,
                title: {
                    text: 'Máximo de ventas Realizadas por Mes'
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#graficoVentas"), options);
        chart.render();


        var arrayVentasRealizadasDolares = <?php echo json_encode($arrayVentasRealizadasDolares); ?>;
        var arrayVentasMontoTotalDolares = <?php echo json_encode($arrayVentasMontoTotalDolares); ?>;
        var arrayVentasFechasDolares = <?php echo json_encode($arrayVentasFechasDolares); ?>;
        var options = {
            series: [{
                name: 'Monto Total Ventas',
                type: 'column',
                data: arrayVentasMontoTotalDolares,
            }, {
                name: 'Ventas Realizadas',
                type: 'line',
                data: arrayVentasRealizadasDolares,
            }],
            chart: {
                height: 350,
                type: 'line',
            },
            stroke: {
                width: [0, 4]
            },
            title: {
                text: 'Reporte de Ventas X Mes en Dólares',
                margin: 50,
                align: 'center'
            },
            colors: ['#00E396', '#008FFB'],
            dataLabels: {
                enabled: true,
                enabledOnSeries: [1]
            },
            labels: arrayVentasFechasDolares,
            xaxis: {
                //   type: 'datetime'
            },
            yaxis: [{
                title: {
                    text: 'Máximo de ventas Por Mes',
                },

            }, {
                opposite: true,
                title: {
                    text: 'Máximo de ventas Realizadas por Mes'

                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#graficoVentasDolares"), options);
        chart.render();


        var totalMasVendidoXmes = <?php echo json_encode($totalMasVendidoXmes); ?>;
        var descripcionMasVendidoXmes = <?php echo json_encode($descripcionMasVendidoXmes); ?>;
        var options = {
            series: totalMasVendidoXmes,
            chart: {
                type: 'polarArea',
                // height: 460
            },
            // labels: descripcionMasVendidoXmes,
            labels: descripcionMasVendidoXmes,
            title: {
                text: 'Productos/Servicios mas comercializado del Mes',
                // offsetY: 20,
                margin: 50,
                align: 'center'
            },
            yaxis: {
                tickAmount: 1
            },
            legend: {
                position: 'bottom'
            },
            stroke: {
                colors: ['#fff']
            },
            fill: {
                opacity: 0.8
            },
            responsive: [{
                breakpoint: 600,
                options: {
                    chart: {
                        // width: 400
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#graficoMasVendidosXmes"), options);
        chart.render();

        var totalMasVendidoXdia = <?php echo json_encode($totalMasVendidoXdia); ?>;
        var descripcionMasVendidoXdia = <?php echo json_encode($descripcionMasVendidoXdia); ?>;

        var options = {
            series: totalMasVendidoXdia,
            chart: {
                //   width: 550,
                type: 'polarArea',
                height: 460
            },
            // labels: ['Rose A', 'Rose B', 'Rose C', 'Rose D', 'Rose E'],
            labels: descripcionMasVendidoXdia,
            fill: {
                opacity: 1
            },
            title: {
                text: 'Productos/Servicios mas comercializado del Día',
                // offsetY: 20,
                margin: 50,
                align: 'center'
            },
            colors: ['#5CEDBC', '#A897E1', '#FF8899', '#FECC6C', '#5CB7FC'],
            // colors: ['#13D8AA', '#03A9F4', '#F9A3A4', '#775DD0', '#F9C80E'],
            stroke: {
                width: 1,
                colors: undefined
            },
            yaxis: {
                show: false
            },
            legend: {
                position: 'bottom'
            },
            plotOptions: {
                polarArea: {
                    rings: {
                        strokeWidth: 0
                    },
                    spokes: {
                        strokeWidth: 0
                    },
                }
            },
            // theme: {
            //   monochrome: {
            //     enabled: true,
            //     color: '#255aee',
            //     shadeTo: 'light',
            //     shadeIntensity: 0.6,
            //   }
            // }
        };

        var chart = new ApexCharts(document.querySelector("#graficoMasVendidoXDias"), options);
        chart.render();
    </script>
</body>

</html>
