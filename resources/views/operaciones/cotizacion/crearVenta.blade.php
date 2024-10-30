<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/center-circle.css') }}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Generar Cotizacion</title>
    <!-- CSS -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/material-icons/material-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/mono-social-icons/monosocialiconsfont.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('assets/vendors/feather-icons/feather.css') }}" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet"
        type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css"
        rel="stylesheet" type="text/css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/css/perfect-scrollbar.min.css"
        rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/newStyles.css?v=' . time()) }}" rel="stylesheet" type='text/css'>
    <link href="{{ asset('assets/css/responsive.dataTables.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/loading.min.css') }}" rel="stylesheet" type="text/css">
    <link href="jquery.nice-number.css" rel="stylesheet">
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
                            <h6 class="page-title-heading mr-0 mr-r-5">Crear Cotizacion</h6>
                        </div>
                        <!-- /.page-title-left -->
                    </div>
                    @if (session('error'))
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('status'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('caja'))
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('caja') }}
                            <a href="../../caja/cierre-caja"><button class="btn btn-info">Ir a Caja</button></a>
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
                                    <div class="widget-body clearfix form-material">
                                        <!--{!! Form::open([
                                            'url' => '/operaciones/ventas/realizar-venta',
                                            'method' => 'POST',
                                            'files' => true,
                                            'class' => 'form-material',
                                        ]) !!}
                                                {{ csrf_field() }}-->

                                        <div class="row">
                                            <div class="col-md-4">
                                                @if ($subpermisos->contains('IdSubPermisos', 35))
                                                    <div class="form-group">
                                                        <div class="radiobox">
                                                            <label>
                                                                <input id="radio1" type="radio"
                                                                    class="ik radioTipoCotizacion" name="option"
                                                                    value="1" checked="checked">
                                                                <span class="label-text">Comercial</span>
                                                            </label>
                                                        </div>
                                                        <!-- /.radiobox -->
                                                        <div class="radiobox radio-success">
                                                            <label>
                                                                <input id="radio2" type="radio"
                                                                    class="ik radioTipoCotizacion" name="option"
                                                                    value="2"> <span
                                                                    class="label-text">Vehicular</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="radiobox">
                                                        <label>
                                                            <input id="radio1" type="radio"
                                                                class="ik radioTipoCotizacion" name="option"
                                                                value="1" checked="checked">
                                                            <span class="label-text">Comercial</span>
                                                        </label>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><label>Serie</label></div>
                                                        </div>
                                                        <input id="serie" class="form-control"
                                                            placeholder="Serie" value="{{ $Serie }}"
                                                            type="text" name="serie" maxlength="4" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><label>Número</label></div>
                                                        </div>
                                                        <input id="numero" class="form-control"
                                                            placeholder="Número" value="{{ $Numero }}"
                                                            type="text" maxlength="8" name="numero" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <select class="m-b-10 form-control select2-hidden-accessible"
                                                        id="clientes" name="cliente" data-placeholder="Choose"
                                                        data-toggle="select2" tabindex="-1" aria-hidden="true">
                                                        <option value="0">-</option>
                                                    </select>
                                                    <small class="text-muted"><strong>Seleccione el
                                                            Cliente</strong></small>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    @if ($subniveles->contains('IdSubNivel', 46))
                                                        <select id="tipoMoneda" class="form-control"
                                                            name="tipoMoneda">
                                                            @foreach ($tipoMoneda as $tipMon)
                                                                @if ($tipMon->IdTipoMoneda < 3)
                                                                    <option value="{{ $tipMon->IdTipoMoneda }}">
                                                                        {{ $tipMon->Nombre }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <select id="tipoMoneda" class="form-control" disabled
                                                            name="tipoMoneda">
                                                            @foreach ($tipoMoneda as $tipMon)
                                                                <option value="{{ $tipMon->IdTipoMoneda }}">
                                                                    {{ $tipMon->Nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                    <label> Moneda</label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <div class="input-group border-bottom">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><label>Fecha</label></div>
                                                            <input id="datepicker2" type="hidden"
                                                                name="fechaEmitida" value="{{ $fecha }}" />
                                                        </div>
                                                        <span class="mt-2 ml-3">{{ $fecha }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input id="datepicker" type="date"
                                                            data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                            class="form-control" name="fechaFinal"
                                                            min="<?php echo date('Y-m-d'); ?>">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><i
                                                                    class="list-icon material-icons">date_range</i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <small class="text-muted"><strong>Fecha de
                                                            vencimiento</strong></small>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($subpermisos->contains('IdSubPermisos', 35))
                                            <div class="row" id="seccionCotizacionVehicular">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select class="m-b-10 form-control select2-hidden-accessible"
                                                            id="operario" name="operario" data-toggle="select2"
                                                            tabindex="-1" aria-hidden="true">
                                                            <option value="0">-</option>
                                                            @foreach ($operarios as $operario)
                                                                <option value="{{ $operario->IdOperario }}">
                                                                    {{ $operario->Nombres }}</option>
                                                            @endforeach
                                                        </select>
                                                        <small class="text-muted"><strong>Seleccione
                                                                Operario</strong></small>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-append">
                                                                <div class="input-group-text"><label>Color</label>
                                                                </div>
                                                            </div>
                                                            <input id="color" class="form-control" type="text"
                                                                name="color" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-append">
                                                                <div class="input-group-text"><label>Año</label></div>
                                                            </div>
                                                            <input id="anio" class="form-control" type="text"
                                                                name="anio" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select class="m-b-10 form-control select2-hidden-accessible"
                                                            id="atencion" name="atencion" data-toggle="select2"
                                                            tabindex="-1" aria-hidden="true">
                                                            <option value="0">-</option>
                                                            @foreach ($tiposAtenciones as $atencion)
                                                                <option value="{{ $atencion->IdTipoAtencion }}">
                                                                    {{ $atencion->Descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                        <small class="text-muted"><strong>Seleccione Tipo
                                                                Atención</strong></small>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <small class="text-muted"><strong>Fecha Venc.
                                                                    Soat</strong></small>
                                                            <div class="input-group">
                                                                <input id="vencSoat" type="date"
                                                                    data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                                    class="form-control" name="vencSoat">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <small class="text-muted"><strong>Fecha Revis.
                                                                    Técnica</strong></small>
                                                            <div class="input-group">
                                                                <input id="vencRevTecnica" type="date"
                                                                    data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                                    class="form-control" name="vencRevTecnica">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select class="m-b-10 form-control select2-hidden-accessible"
                                                            id="inventario" name="inventario"
                                                            data-placeholder="Choose" data-toggle="select2"
                                                            tabindex="-1" aria-hidden="true">
                                                            <option value="0">-</option>
                                                        </select>
                                                        <small class="text-muted"><strong>Seleccione Código de
                                                                Inventario</strong></small>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">
                                                                    <label>Kilometraje</label>
                                                                </div>
                                                            </div>
                                                            <input id="kilometro" class="form-control"
                                                                placeholder="Kilometro" value="" type="text"
                                                                name="kilometro" maxlength="9">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-append">
                                                                <div class="input-group-text"><label>Horometro</label>
                                                                </div>
                                                            </div>
                                                            <input id="campo2" class="form-control"
                                                                placeholder="Horometro" value="" type="text"
                                                                name="horometro" maxlength="9">
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Nuevo codigo --}}
                                                @if ($modulosSelect->contains('IdModulo', 7))
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <select
                                                                class="m-b-10 form-control select2-hidden-accessible"
                                                                id="mantenimientoActual" name="mantenimientoActual"
                                                                data-toggle="select2" tabindex="-1"
                                                                aria-hidden="true" disabled>
                                                                @for ($i = 1000; $i <= 350000; $i = $i + 500)
                                                                    @if ($i == 1000)
                                                                        <option
                                                                            value="{{ number_format($i, 0, ',', ' ') }} Km"
                                                                            selected>
                                                                            {{ number_format($i, 0, ',', ' ') }} Km
                                                                        </option>
                                                                    @else
                                                                        <option
                                                                            value="{{ number_format($i, 0, ',', ' ') }} Km">
                                                                            {{ number_format($i, 0, ',', ' ') }} Km
                                                                        </option>
                                                                    @endif
                                                                @endfor
                                                            </select>
                                                            <small class="text-muted"><strong>Mantenimiento
                                                                    Actual</strong></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text"><label>Próximo
                                                                            Mantenimiento</label>
                                                                    </div>
                                                                </div>
                                                                <input id="proximoMantenimiento" class="form-control"
                                                                    placeholder="" value="1 500 km" type="text"
                                                                    name="proximoMantenimiento" disabled>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text"><label>Prox.
                                                                            Mantenimento en DÍAS</label>
                                                                    </div>
                                                                </div>
                                                                <input id="periodoProximoMantenimiento"
                                                                    class="form-control" placeholder="Número de Días"
                                                                    value="" type="text"
                                                                    name="periodoProximoMantenimiento" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                {{-- Fin --}}

                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <textarea class="form-control" id="trabajos" rows="5" name="trabajos" maxlength="600"></textarea>
                                                        <label>Trabajos a realizar</label>
                                                    </div>
                                                </div>
                                                <input id="placaVehicular" class="form-control" type="text"
                                                    name="placaVehicular" hidden>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-append">
                                                                <div class="input-group-text"><label>Nro Flota</label>
                                                                </div>
                                                            </div>
                                                            <input id="flota" class="form-control"
                                                                placeholder="Nro Flota"type="text" name="flota"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="row">
                                            @if ($exonerado == 1 && $sucExonerado == 1)
                                                <div class="col-md-4">
                                                    <select id="tipoVenta" class="form-control" name="tipoVenta">
                                                        <option value="1">Venta Op. Gravada</option>
                                                        <option selected value="2">Venta Op. Exonerada</option>
                                                    </select>
                                                </div>
                                            @else
                                                <input id="tipoVenta" type="text" name="tipoVenta" value="1"
                                                    hidden>
                                            @endif
                                            @if ($subniveles->contains('IdSubNivel', 46) && $bandVentaSolesDolares == 1)
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="checkbox" id="ventaSolesDolares"
                                                            name="ventaSolesDolares"><span
                                                            class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Venta Soles y
                                                            Dólares</span>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-md-3">
                                                <div class="form-group" id="cambioVentas">
                                                    <label for="valorCambioVentas">Tipo de Cambio a Soles</label>
                                                    <input type="text" id="valorCambioVentas"
                                                        name="valorCambioVentas" class="form-control" value="0"
                                                        readonly>
                                                </div>
                                                <div class="form-group" id="cambioCompras">
                                                    <label for="valorCambioCompras">Tipo de Cambio a Dólares</label>
                                                    <input type="text" id="valorCambioCompras"
                                                        name="valorCambioCompras" class="form-control" value="0"
                                                        readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <br />
                                        <div class="row">
                                            <!--<div class="col-lg-2 col-md-3 col-sm-4 col-5">
                                                        <div class="mt-2">
                                                            <a href="#" data-toggle="modal" data-target=".bs-modal-lg-productos"><button id="agregarArticulo" disabled class="btn btn-info"><i class="list-icon material-icons">add_circle</i> Agregar <span class="caret"></span></button></a>
                                                        </div>
                                                    </div>-->
                                            <div class="col-12 d-md-flex flex-wrap justify-content-center">
                                                <div class="mt-2">
                                                    <a class="p-1" href="#" data-toggle="modal"
                                                        data-target=".bs-modal-lg-productos-soles"><button
                                                            id="agregarArticuloSoles" class="btn btn-info">Agregar
                                                            Productos Soles <span class="caret"></span></button></a>
                                                </div>
                                                <div class="mt-2">
                                                    <a class="p-1" href="#" data-toggle="modal"
                                                        data-target=".bs-modal-lg-productos-dolares"><button
                                                            id="agregarArticuloDolares" class="btn btn-info d-none">
                                                            Agregar
                                                            Productos Dólares <span class="caret"></span></button></a>
                                                </div>
                                                <div class="mt-2">
                                                    <a class="p-1" href="#" data-toggle="modal"
                                                        data-target=".bs-modal-lg-grupos-soles"><button
                                                            id="agregarGruposSoles" class="btn btn-success"> Agregar
                                                            Paquetes Soles <span class="caret"></span></button></a>
                                                </div>
                                                <div class="mt-2">
                                                    <a class="p-1" href="#" data-toggle="modal"
                                                        data-target=".bs-modal-lg-grupos-dolares"><button
                                                            id="agregarGruposDolares"
                                                            class="btn btn-success d-none">Agregar
                                                            Paquetes Dólares <span class="caret"></span></button></a>
                                                </div>
                                                {{-- Nuevo Codigo --}}
                                                @if ($usuarioSelect->ActivarPaquetePromo == 1)
                                                    <div class="mt-2">
                                                        <a class="p-2" href="#" data-toggle="modal"
                                                            data-target=".bs-modal-lg-paquetesPromocionales-soles"><button
                                                                id="agregarPaquetesPromocionalesSoles"
                                                                class="btn btn--rojo" style="position: relative"><img
                                                                    width="35px"
                                                                    style="position: absolute; top:0; left:0"
                                                                    src="{{ asset('/assets/img/iconoPromocion.png') }}"
                                                                    alt=""><span class="pl-3">Paquete
                                                                    Promoción
                                                                    Soles</span></button></a>
                                                    </div>

                                                    <div class="mt-2">
                                                        <a class="p-1" href="#" data-toggle="modal"
                                                            data-target=".bs-modal-lg-paquetesPromocionales-dolares"><button
                                                                id="agregarPaquetesPromocionalesDolares"
                                                                class="btn btn--rojo d-none"
                                                                style="position: relative"><img width="35px"
                                                                    style="position: absolute; top:0; left:0"
                                                                    src="{{ asset('/assets/img/iconoPromocion.png') }}"
                                                                    alt=""><span class="pl-3">Paquete
                                                                    Promoción
                                                                    Dólares</span></button></a>
                                                    </div>
                                                @endif
                                                {{-- Fin --}}
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <div id="armarArray" hidden="">
                                                </div>
                                                <table id="tablaAgregado" class="table table-responsive-lg"
                                                    style="width:100%">
                                                    <thead>
                                                        <tr class="bg-primary-contrast">
                                                            <th scope="col">Código
                                                            </th>
                                                            <th scope="col">Descripción</th>
                                                            <th scope="col">Detalle</th>
                                                            <th scope="col">Und/Medida</th>
                                                            <th scope="col">Precio</th>
                                                            <th scope="col">C/Dcto</th>
                                                            <th scope="col">Cantidad</th>
                                                            <th scope="col">Importe</th>
                                                            <th scope="col">Acciones</th>
                                                            <th scope="col" class="ganancia">Ganancia</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="body">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-lg-8 col-md-12">
                                                <div class="form-group">
                                                    <textarea id="observacion" class="form-control" rows="5" name="observacion" maxlength="1000"></textarea>
                                                    <label>Observación</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-8">
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>Op Gravada:</label>
                                                    </div>
                                                    <div class="col-lg-7 col-8">
                                                        <input id="opGravada" name="opGravada" type="text"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>Op Exonerada:</label>
                                                    </div>
                                                    <div class="col-lg-7 col-8">
                                                        <input id="opExonerado" name="opExonerado" type="text"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>Descuento:</label>
                                                    </div>
                                                    <div class="col-lg-7 col-8">
                                                        <input id="descuento" name="descuento" type="text"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>IGV (18%):</label>
                                                    </div>
                                                    <div class="col-lg-7 col-8">
                                                        <input id="igv" type="text" name="igv" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>Total:</label>
                                                    </div>
                                                    <div class="col-lg-7 col-8">
                                                        <input id="total" type="text" name="total" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input hidden type="text" id="editarPrecio" name="editarPrecio"
                                            class="form-control" value="{{ $editarPrecio }}">
                                        <div class="form-actions btn-list mt-3 d-flex justify-content-end">
                                            <button id="btnGenerar" class="btn btn-primary" type="button">Crear
                                                Cotización</button>
                                        </div>
                                        <!--{!! Form::close() !!}-->
                                    </div>

                                    @include('operaciones.cotizacion._modalArticulosSoles')
                                    @include('operaciones.cotizacion._modalArticulosDolares')

                                    <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog"
                                        aria-labelledby="basicModal" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h6>AVISO</h6>
                                                </div>
                                                <div class="modal-body form-material">
                                                    <div>
                                                        <label class="fs-12 negrita">Si es la primera vez que trabaja
                                                            con comprobantes electrónicos deje la serie y número
                                                            correlativo de las facturas y boletas como están por
                                                            defecto, caso contrario edite la serie y número correlativo
                                                            de cada uno según SUNAT.</label>
                                                    </div>
                                                    <div class="mt-2">
                                                        <text class="fs-11">NOTA: La serie y número correlativo ya
                                                            sea Factura o Boleta solamente se ingresara una vez. Luego
                                                            se manejara automáticamente.</text>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="form-actions btn-list mt-3">
                                                        <button class="btn btn-info" type="button"
                                                            data-dismiss="modal">Aceptar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="tipoCambio" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"
                                        data-keyboard="false">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Configurar Tipo de Cambio</h6>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="form-group">
                                                        <label for="soles">Tipo de Cambio Compras</label>
                                                        <input id="tipoCambioCompras" class="form-control"
                                                            name="TipoCambioCompras">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="soles">Tipo de Cambio Ventas</label>
                                                        <input id="tipoCambioVentas" class="form-control"
                                                            name="TipoCambioVentas">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button id="btnTipoCambio" onclick="guardaTipoCambio(this);"
                                                        class="btn btn-primary btnEliminar">Aceptar</button>
                                                    <button id="btnTipoCambioCancelar" class="btn btn-secondary"
                                                        data-dismiss="modal">Cancelar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Grupos --}}
                                    @include('operaciones.cotizacion._modalGruposSoles')
                                    @include('operaciones.cotizacion._modalGruposDolares')
                                    {{-- Fin --}}
                                    {{-- Paquete Promocionales --}}
                                    @include('operaciones.cotizacion._modalPaquetePromoSoles')
                                    @include('operaciones.cotizacion._modalPaquetePromoDolares')
                                    @include('operaciones.cotizacion._modalDetallePaquetePromo')
                                    {{-- Fin --}}


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
            <!-- /.main-wrapper -->
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>

    <script src="{{ asset('assets/js/jquery.loading.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="{{ asset('assets/js/libreriasExternas/libreriaSweetAlert1/libreriaSweetalert1.js') }}"></script>
    <script src="{{ asset('assets/js/operaciones/cotizacion/cargarArticulosModal.js?v=' . time()) }}"></script>
    <script src="{{ asset('assets/js/operaciones/cotizacion/scriptOperacionesArticulos.js?v=' . time()) }}"></script>
    <script src="{{ asset('assets/js/operaciones/cotizacion/scriptValidarDescuento.js?v=' . time()) }}"></script>

    <script src="{{ asset('assets/js/operaciones/cotizacion/scriptPeticionesAjax.js?v=' . time()) }}"></script>
    <script>
        const variablesJs = {
            routeBuscarProductos: "{{ route('articulos.buscar-productos-ajax') }}",
            routeBuscarServicios: "{{ route('articulos.buscar-servicios-ajax') }}",
            routePaginarProductos: "{{ route('articulos.paginar-productos-ajax') }}?page=",
            routePaginarServicios: "{{ route('articulos.paginar-servicios-ajax') }}?page=",
            routeGuardarTipoCambio: "{{ route('tipo-cambio.store-ajax') }}",
            routeObtenerTipoCambio: "{{ route('tipo-cambio.obtener-ajax') }}",
            routeObtenerInformacionCliente: "{{ route('cotizacion.obtener-informacion-cliente') }}",
            routeVerCotizacionGenerada: "{{ route('cotizacion.ver-cotizacion-generada', ['id' => ':id']) }}",
            routeObtenerArticulosGrupo: "{{ route('cotizacion.obtener-articulos-grupo') }}",
            routeObtenerDataInventario: "{{ route('cotizacion.obtener-data-inventario') }}",
            routeObtenerDataVehiculo: "{{ route('cotizacion.obtener-data-vehiculo') }}",
            igv: @json(config('variablesGlobales.Igv')),
            token: "{{ csrf_token() }}",
            operacionCrud: 'crear',
        }
    </script>


    <script>
        $('#atencion').change(function() {
            if ($('#atencion option:selected').val() == 1 || $('#atencion option:selected').val() == 6) {
                $('#mantenimientoActual').removeAttr('disabled')
                $('#proximoMantenimiento').removeAttr('disabled')
                $('#periodoProximoMantenimiento').removeAttr('disabled')
            } else {
                $('#mantenimientoActual').attr('disabled', 'disabled')
                $('#proximoMantenimiento').attr('disabled', 'disabled')
                $('#periodoProximoMantenimiento').attr('disabled', 'disabled')
            }
        });


        $('#mantenimientoActual').change(function(e) {
            var indice = $("#mantenimientoActual option:selected").index() + 1;
            var texto = $('#mantenimientoActual option:eq(' + indice + ')').val();
            $('#proximoMantenimiento').val(texto)
        });
    </script>

    <script>
        $(function() {
            getCliente(-1, 1);
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1;

            var yyyy = today.getFullYear();
            if (dd < 10) {
                dd = '0' + dd;
            }
            if (mm < 10) {
                mm = '0' + mm;
            }
            var today = yyyy + '-' + mm + '-' + dd;
            $("#datepicker").val(today);
            $("#datepicker2").val(today);
        });

        function getFecha(date) {
            var today = new Date(date);
            var dd = today.getDate();
            var mm = today.getMonth() + 1;

            var yyyy = today.getFullYear();
            if (dd < 10) {
                dd = '0' + dd;
            }
            if (mm < 10) {
                mm = '0' + mm;
            }
            var today = yyyy + '-' + mm + '-' + dd;
            return today;
        }
    </script>
    <script>
        // Varibles para validar el maximo descuento
        let descuentoMaximoSoles = @json($usuarioSelect->DescuentoMaximoSoles);
        let descuentoMaximoDolares = @json($usuarioSelect->DescuentoMaximoDolares);
        let isAdministrador = @json($usuarioSelect->Rol);

        $(function() {
            $('#plazoCredito').hide();
            $('#interes').hide();
            $('.ganancia').hide();
            $("#cambioVentas").hide();
            $("#cambioCompras").hide();
            var totalVentas = @json($totalVentas);
            sucExonerado = @json($sucExonerado);
            if (totalVentas == 0) {
                $("#mostrarmodal").modal("show");
            }

            $("#clientes").on('change', function() {
                var idCliente = $("#clientes").val();
                if (idCliente != 0) {
                    $('#agregarArticulo').removeAttr('disabled');
                } else {
                    $('#agregarArticulo').attr('disabled', 'disabled');
                }
            });
        });

        function enviar() {
            const moduloCronogramaActivo = @json($moduloCronogramaActivo);
            $('#btnGenerar').attr("disabled", true);
            const placaVehicular = $("#placaVehicular").val();
            const vencSoat = $("#vencSoat").val();
            const vencRevTecnica = $("#vencRevTecnica").val();
            const valorCambioVentas = $("#valorCambioVentas").val();
            const valorCambioCompras = $("#valorCambioCompras").val();
            const checkCotizacionSolesConDolares = $('#ventaSolesDolares').prop('checked') ? 1 : 0;

            $.ajax({
                type: 'post',
                url: 'realizar-venta',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "placaVehicular": placaVehicular,
                    "vencSoat": vencSoat,
                    "vencRevTecnica": vencRevTecnica,
                    "valorCambioVentas": valorCambioVentas,
                    "valorCambioCompras": valorCambioCompras,
                    "checkCotizacionSolesConDolares": checkCotizacionSolesConDolares,
                    "moduloCronogramaActivo": moduloCronogramaActivo,
                    'cotizacion': cotizacion
                },
                success: function(data) {
                    if (data.respuesta == 'error') {
                        $('#btnGenerar').attr("disabled", false);
                        swal({
                            title: "Error al enviar los Datos!",
                            text: data.mensaje,
                            icon: "error",
                        })
                        return;
                    }
                    if (data.respuesta == 'success') {
                        swal({
                            title: "Exito",
                            text: data.mensaje,
                            icon: "success",
                        }).then((value) => {
                            window.location =
                                '../../operaciones/cotizacion/comprobante-generado/' +
                                data.id;
                        });
                        return;
                    }
                }
            });
        }
    </script>
    <script>
        // Evento para obtener los clientes dentro del select Clientes
        $(".radioTipoCotizacion").change(function() {
            $('#clientes').empty();
            $('#clientes').append('<option value="0">-</option>');
            $.showLoading({
                name: 'circle-fade',
            });
            valorOpction = $('input.radioTipoCotizacion:checked').val();;
            tipoCotizacion = $('input.radioTipoCotizacion:checked').val();;
            getCliente(-1, valorOpction);
        });
        $('#btnTipoCambioCancelar').click(function() {
            $("#tipoMoneda").val("1");
            $("#ventaSolesDolares").prop("checked", false);
        });

        // Eventos para eliminar los articulos al cambiar de opcion tipoVenta y tipoMoneda
        $('#tipoVenta').change(function() {
            limpiarArticulos();
        })

        $('#tipoMoneda').change(function() {
            limpiarArticulos();
        })

        const capturarDatosCotizacionDom = () => {
            const atencion = $('#atencion').val();
            const tipoCotizacion = $('input.radioTipoCotizacion:checked').val();
            let idVehiculo = '';
            let idCliente = $('#clientes').val();
            let mantenimientoActual = null;
            let proximoMantenimiento = null;
            let periodoProximoMantenimiento = null;
            let idTipoAtencion = 5;
            if (atencion == 1 || atencion == 6) {
                mantenimientoActual = $("#mantenimientoActual").val();
                proximoMantenimiento = $("#proximoMantenimiento").val();
                periodoProximoMantenimiento = $("#periodoProximoMantenimiento").val();
            }
            if (tipoCotizacion == 2) {
                idTipoAtencion = $('#atencion').val();
                idVehiculo = parseInt($('#clientes').val(), 10);
                const clienteEncontrado = clientesCotizacion.find(objeto => objeto.IdCliente === idVehiculo);
                idCliente = clienteEncontrado ? clienteEncontrado.IdClienteVehicular : null;
            }
            const datosCotizacion = {
                IdCliente: idCliente,
                IdTipoMoneda: $("#tipoMoneda").val(),
                IdCheckIn: $('#inventario').val(),
                IdOperario: $("#operario").val(),
                Serie: $("#serie").val(),
                Numero: $("#numero").val(),
                IdEstadoCotizacion: 1,
                IdTipoAtencion: idTipoAtencion,
                TipoCotizacion: tipoCotizacion,
                FechaCreacion: $("#datepicker2").val(),
                FechaFin: $("#datepicker").val(),
                Campo0: idVehiculo,
                Campo1: $("#kilometro").val(),
                Campo2: $("#campo2").val(),
                TipoVenta: $('#tipoVenta').val(),
                Exonerada: $('#descuento').val(),
                Trabajos: $("#trabajos").val(),
                Observacion: $("#observacion").val(),
                Estado: 1,
                MantenimientoActual: mantenimientoActual,
                ProximoMantenimiento: proximoMantenimiento,
                PeriodoProximoMantenimiento: periodoProximoMantenimiento
            };
            cotizacion.setDatosCotizacion(datosCotizacion);
        }
    </script>


    {{-- NUEVA FUNCION PAGINACION GRUPOS --}}
    <script>
        $(document).on('click', '.pagGruposSoles a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var textoNombreGrupo = $('#inputBuscarGruposSoles').val();
            getGrupos(page, textoNombreGrupo, 1);
        });

        $(document).on('click', '.pagGruposDolares a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var textoNombreGrupo = $('#inputBuscarGruposDolares').val();
            getGrupos(page, textoNombreGrupo, 2);
        });

        function getGrupos(page, textoNombreGrupo, tipoMoneda) {
            $.ajax({
                type: 'get',
                url: 'grupos?page=' + page,
                data: {
                    'textoNombreGrupo': textoNombreGrupo,
                    'tipoMoneda': tipoMoneda
                },
                success: function(data) {
                    if (tipoMoneda == 1) {
                        cardXGrupo = '#cardXGrupoSoles';
                        paginasGrupos = '#paginasGruposSoles';
                    } else {
                        cardXGrupo = '#cardXGrupoDolares';
                        paginasGrupos = '#paginasGruposDolares';
                    }
                    cargarGruposEnModal(data, cardXGrupo, paginasGrupos);
                }
            });
        }

        $("#inputBuscarGruposSoles").keyup(function() {
            var textoBusqueda = $("#inputBuscarGruposSoles").val();
            if (textoBusqueda.length > 2 || textoBusqueda.length == 0) {
                ajaxBuscarGrupoInput(textoBusqueda, 1);
            }
        });

        $("#inputBuscarGruposDolares").keyup(function() {
            var textoBusqueda = $("#inputBuscarGruposDolares").val();
            if (textoBusqueda.length > 2 || textoBusqueda.length == 0) {
                ajaxBuscarGrupoInput(textoBusqueda, 2);
            }
        });

        function ajaxBuscarGrupoInput(textoBusqueda, tipoMoneda) {
            $.ajax({
                type: 'get',
                url: 'buscar-grupo',
                data: {
                    'textoBuscar': textoBusqueda,
                    'tipoMoneda': tipoMoneda
                },
                success: function(data) {
                    if (tipoMoneda == 1) {
                        cardXGrupo = '#cardXGrupoSoles';
                        paginasGrupos = '#paginasGruposSoles';
                    } else {
                        cardXGrupo = '#cardXGrupoDolares';
                        paginasGrupos = '#paginasGruposDolares';
                    }
                    cargarGruposEnModal(data, cardXGrupo, paginasGrupos);
                }
            });
        }
    </script>
    {{-- FIN --}}

    {{-- NUEVA FUNCION PAGINACION PAQUETES PROMOCIONALES --}}
    <script>
        $(document).on('click', '.pagPaquetesPomocionalesSoles a', function(e) {
            e.preventDefault();
            const page = $(this).attr('href').split('page=')[1];
            const textoNombrePaquetePromoSol = $('#inputBuscarPaquetePromocionalSoles').val();
            getPaquetesPromocionales(page, textoNombrePaquetePromoSol, 1);
        });

        $(document).on('click', '.pagPaquetesPomocionalesDolares a', function(e) {
            e.preventDefault();
            const page = $(this).attr('href').split('page=')[1];
            const textoNombrePaquetePromoDolar = $('#inputBuscarPaquetePromocionalSoles').val();
            getPaquetesPromocionales(page, textoNombrePaquetePromoDolar, 2);
        });

        function getPaquetesPromocionales(page, textoNombrePaquetePromocional, tipoMoneda) {
            $.ajax({
                type: 'get',
                url: 'paginacion-paquete-promocional?page=' + page,
                data: {
                    'textoNombrePaquete': textoNombrePaquetePromocional,
                    'tipoMoneda': tipoMoneda
                },
                success: function(data) {
                    let moneda;
                    if (tipoMoneda == 1) {
                        moneda = 'S/';
                        cardPaquetePromocional = '#cardPaquetesPromocionalesSoles';
                        paginasPaquetesPromocional = '#paginasPaquetesPomocionalesSoles';
                    } else {
                        moneda = '$';
                        cardPaquetePromocional = '#cardPaquetesPromocionalesDolares';
                        paginasPaquetesPromocional = '#paginasPaquetesPomocionalesDolares';
                    }
                    cargarPaquetesPromoEnModal(data, cardPaquetePromocional, paginasPaquetesPromocional,
                        moneda);
                }
            });
        }

        $("#inputBuscarPaquetePromocionalSoles").keyup(function() {
            var textoBusqueda = $("#inputBuscarPaquetePromocionalSoles").val();
            if (textoBusqueda.length > 2 || textoBusqueda.length == 0) {
                ajaxBuscarPaquetePromocionalInput(textoBusqueda, 1);
            }
        });

        $("#inputBuscarPaquetePromocionalDolares").keyup(function() {
            var textoBusqueda = $("#inputBuscarPaquetePromocionalDolares").val();
            if (textoBusqueda.length > 2 || textoBusqueda.length == 0) {
                ajaxBuscarPaquetePromocionalInput(textoBusqueda, 2);
            }
        });

        function ajaxBuscarPaquetePromocionalInput(textoBusqueda, tipoMoneda) {
            $.ajax({
                type: 'get',
                url: 'buscar-paquete-promocional',
                data: {
                    'textoBuscar': textoBusqueda,
                    'tipoMoneda': tipoMoneda
                },
                success: function(data) {
                    let moneda = '';
                    if (tipoMoneda == 1) {
                        moneda = 'S/';
                        cardPaquetePormocional = '#cardPaquetesPromocionalesSoles';
                        paginasPaquetesPromocional = '#paginasPaquetesPomocionalesSoles';
                    } else {
                        moneda = '$';
                        cardPaquetePormocional = '#cardPaquetesPromocionalesDolares';
                        paginasPaquetesPromocional = '#paginasPaquetesPomocionalesDolares';
                    }
                    cargarPaquetesPromoEnModal(data, cardPaquetePormocional, paginasPaquetesPromocional,
                        moneda);
                }
            });
        }
    </script>
    {{-- FIN --}}

</body>

</html>
