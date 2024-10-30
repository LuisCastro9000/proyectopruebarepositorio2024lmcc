<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pace.css') }}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Editar Cotizacion</title>
    <!-- CSS -->
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
    <link href="{{ asset('assets/css/responsive.dataTables.css') }}" rel="stylesheet" type="text/css">
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
                    <div class="row page-title clearfix">
                        <div class="page-title-left">
                            <h6 class="page-title-heading mr-0 mr-r-5">Editar Cotizacion</h6>
                            @if ($cotizacionSelect->IdEstadoCotizacion != 5)
                                @if ($cotizacionSelect->IdEstadoCotizacion == 1)
                                    <span class="bg-abierto">Abierto</span>
                                @elseif($cotizacionSelect->IdEstadoCotizacion == 2)
                                    <span class="bg-enProceso">En Proceso</span>
                                @elseif($cotizacionSelect->IdEstadoCotizacion == 3)
                                    <span class=" bg-finalizado">Finalizado</span>
                                @elseif($cotizacionSelect->IdEstadoCotizacion == 4)
                                    <span class="bg-cerrado">Cerrado</span>
                                @endif
                            @endif
                        </div>
                        <div class="page-title-center">
                        </div>
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
                                                <input hidden id="idCoti" class="form-control" type="text"
                                                    value="{{ $cotizacionSelect->IdCotizacion }}" readonly>
                                                <input hidden id="tipoCoti" class="form-control tipoCotizacion"
                                                    type="text" name="tipoCoti"
                                                    value="{{ $cotizacionSelect->TipoCotizacion }}" readonly>
                                                @if ($subpermisos->contains('IdSubPermisos', 35))
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-append">
                                                                <div class="input-group-text"><label>Tipo</label></div>
                                                            </div>
                                                            @if ($cotizacionSelect->TipoCotizacion == 2)
                                                                <input class="form-control" type="text"
                                                                    value="Vehicular" readonly>
                                                            @else
                                                                <input class="form-control" type="text"
                                                                    value="Comercial" readonly>
                                                            @endif
                                                        </div>
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
                                                            placeholder="Serie"
                                                            value="{{ $cotizacionSelect->Serie }}" type="text"
                                                            name="serie" maxlength="4" readonly>
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
                                                            placeholder="Número"
                                                            value="{{ $cotizacionSelect->Numero }}" type="text"
                                                            maxlength="8" name="numero" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Cliente</label>
                                                    <!--<select class="form-control" id="clientes" name="cliente">-->
                                                    {{-- <select class="m-b-10 form-control select2-hidden-accessible"
                                                        id="clientes" name="cliente" disabled
                                                        data-placeholder="Choose" data-toggle="select2"
                                                        tabindex="-1" aria-hidden="true">
                                                        @foreach ($clientes as $cliente)
                                                            @if ($cliente->IdCliente == $idCliente)
                                                                <option value="{{ $cliente->IdCliente }}" selected>
                                                                    {{ $cliente->RazonSocial }}</option>
                                                            @else
                                                                <option value="{{ $cliente->IdCliente }}">
                                                                    {{ $cliente->RazonSocial }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select> --}}
                                                    <select class="m-b-10 form-control select2-hidden-accessible"
                                                        id="clientes" name="cliente" disabled
                                                        data-placeholder="Choose" data-toggle="select2"
                                                        tabindex="-1" aria-hidden="true">
                                                        <option> {{ $clienteCotizacion }}</option>
                                                    </select>
                                                    <small class="text-muted"><strong>Seleccione el
                                                            Cliente</strong></small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <select id="tipoMoneda" class="form-control" disabled
                                                        name="tipoMoneda">
                                                        {{-- @foreach ($tipoMoneda as $tipMon)
                                                            @if ($idTipoMoneda == $tipMon->IdTipoMoneda)
                                                                <option selected value="{{ $tipMon->IdTipoMoneda }}">
                                                                    {{ $tipMon->Nombre }}</option>
                                                            @else
                                                                <option value="{{ $tipMon->IdTipoMoneda }}">
                                                                    {{ $tipMon->Nombre }}</option>
                                                            @endif
                                                        @endforeach --}}
                                                        <option selected
                                                            value="{{ $cotizacionSelect->IdTipoMoneda }}">
                                                            {{ $cotizacionSelect->Moneda }}</option>
                                                    </select>
                                                    <label> Moneda</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="input-group border-bottom">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><label>Fecha</label></div>
                                                            <input id="datepicker2" type="hidden"
                                                                name="fechaEmitida"
                                                                value="{{ date('Y-m-d', strtotime($fecha)) }}" />
                                                        </div>
                                                        <span class="mt-2 ml-3">{{ $fecha }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($subpermisos->contains('IdSubPermisos', 35))
                                            @if ($cotizacionSelect->TipoCotizacion == 2)
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <select
                                                                class="m-b-10 form-control select2-hidden-accessible"
                                                                id="operario" name="operario" data-toggle="select2"
                                                                tabindex="-1" aria-hidden="true"
                                                                {{ $cotizacionSelect->IdEstadoCotizacion == 3 ? 'disabled' : '' }}>
                                                                <option value="0">-</option>
                                                                @foreach ($operarios as $operario)
                                                                    @if ($operario->IdOperario == $cotizacionSelect->IdOperario)
                                                                        <option value="{{ $operario->IdOperario }}"
                                                                            selected>{{ $operario->Nombres }}</option>
                                                                    @else
                                                                        <option value="{{ $operario->IdOperario }}">
                                                                            {{ $operario->Nombres }}</option>
                                                                    @endif
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
                                                                <input id="color" class="form-control"
                                                                    type="text" name="color"
                                                                    value="{{ $dataVehiculo->Color }}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text"><label>Año</label>
                                                                    </div>
                                                                </div>
                                                                <input id="anio" class="form-control"
                                                                    type="text" name="anio"
                                                                    value="{{ $dataVehiculo->Anio }}" readonly>
                                                            </div>
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
                                                                    placeholder="Kilometro"
                                                                    value="{{ $cotizacionSelect->Campo1 }}"
                                                                    type="text" name="kilometro" maxlength="9"
                                                                    {{ $cotizacionSelect->IdEstadoCotizacion == 3 ? 'readonly' : '' }}>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text">
                                                                        <label>Horometro</label>
                                                                    </div>
                                                                </div>
                                                                <input id="horometro" class="form-control"
                                                                    placeholder="Horometro"
                                                                    value="{{ $cotizacionSelect->Campo2 }}"
                                                                    type="text" name="horometro" maxlength="9"
                                                                    {{ $cotizacionSelect->IdEstadoCotizacion == 3 ? 'readonly' : '' }}>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text"><label>Nro
                                                                            Flota</label></div>
                                                                </div>
                                                                <input id="flota" class="form-control"
                                                                    placeholder="Nro Flota"type="text" name="flota"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Nuevo codigo --}}
                                                    @if ($modulosSelect->contains('IdModulo', 7))
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <select id="mantenimientoActual" class="form-control"
                                                                    name="mantenimientoActual" disabled>
                                                                    @if ($cotizacionSelect->MantenimientoActual != null)
                                                                        <option
                                                                            value="{{ $cotizacionSelect->MantenimientoActual }}">
                                                                            {{ $cotizacionSelect->MantenimientoActual }}
                                                                        </option>
                                                                    @else
                                                                        <option value=""></option>
                                                                        @for ($i = 1000; $i <= 200000; $i = $i + 500)
                                                                            <option
                                                                                value="{{ number_format($i, 0, ',', ' ') }} Km">
                                                                                {{ number_format($i, 0, ',', ' ') }}
                                                                                Km
                                                                            </option>
                                                                        @endfor
                                                                    @endif
                                                                </select>
                                                                <label> Mantenimiento Actual</label>
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
                                                                    <input id="proximoMantenimiento"
                                                                        class="form-control" placeholder=""
                                                                        value="{{ $cotizacionSelect->ProximoMantenimiento }}"
                                                                        type="text" name="proximoMantenimiento"
                                                                        disabled>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <div class="input-group-append">
                                                                        <div class="input-group-text"><label>Periodo
                                                                                Próximo Mantenimiento</label>
                                                                        </div>
                                                                    </div>
                                                                    <input id="periodoProximoMantenimiento"
                                                                        class="form-control"
                                                                        placeholder="Número de Días"
                                                                        value="{{ $cotizacionSelect->PeriodoProximoMantenimiento }}"
                                                                        type="text"
                                                                        name="periodoProximoMantenimiento" disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    {{-- Fin --}}

                                                    @if (!empty($checkIn))
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <small class="text-muted"><strong>Código de
                                                                        Inventario</strong></small>
                                                                <input class="form-control"
                                                                    placeholder="Nro Flota"type="text" name="flota"
                                                                    value="{{ $checkIn->Serie }} - {{ $checkIn->Correlativo }} / {{ $checkIn->FechaEmision }}"
                                                                    readonly>
                                                                <input type="hidden" id="inventario"
                                                                    value="{{ $cotizacionSelect->IdCheckIn }}">
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="col-md-4"> <br>
                                                            <div class="form-group">
                                                                <select
                                                                    class="m-b-10 form-control select2-hidden-accessible"
                                                                    id='inventario' name="selectIdCheckList"
                                                                    data-placeholder="Choose" data-toggle="select2"
                                                                    tabindex="-1" aria-hidden="true">
                                                                    <option value="0">
                                                                        -
                                                                    </option>
                                                                    @foreach ($listaCheckList as $check)
                                                                        <option value="{{ $check->IdCheckIn }}">
                                                                            Cód.:
                                                                            {{ $check->Serie }}-{{ $check->Correlativo }}
                                                                            / Emitido:
                                                                            {{ date('Y-m-d', strtotime($check->FechaEmision)) }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <small class="text-muted"><strong>Seleccione Código de
                                                                        Inventario</strong></small>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    {{-- -Nuevo----------- --}}
                                                    <div class="col-md-4">
                                                        <br>
                                                        <div class="form-group">
                                                            <select
                                                                class="m-b-10 form-control select2-hidden-accessible"
                                                                id="atencion" name="atencion" data-toggle="select2"
                                                                tabindex="-1" aria-hidden="true" disabled>
                                                                <option value="5">-</option>
                                                                @foreach ($tiposAtenciones as $atencion)
                                                                    @if ($atencion->IdTipoAtencion == $cotizacionSelect->IdTipoAtencion)
                                                                        <option
                                                                            value="{{ $atencion->IdTipoAtencion }}"
                                                                            selected>{{ $atencion->Descripcion }}
                                                                        </option>
                                                                    @else
                                                                        <option
                                                                            value="{{ $atencion->IdTipoAtencion }}">
                                                                            {{ $atencion->Descripcion }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                            <small class="text-muted"><strong>Seleccione Tipo
                                                                    Atención</strong></small>
                                                        </div>
                                                    </div>
                                                    {{-- Fin --}}
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <small class="text-muted"><strong>Fecha Venc.
                                                                        Soat</strong></small>
                                                                <div class="input-group">
                                                                    <input id="vencSoat" type="date"
                                                                        data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                                        class="form-control" name="vencSoat"
                                                                        {{ $cotizacionSelect->IdEstadoCotizacion == 3 ? 'disabled' : '' }}>
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
                                                                        class="form-control" name="vencRevTecnica"
                                                                        {{ $cotizacionSelect->IdEstadoCotizacion == 3 ? 'disabled' : '' }}>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <textarea class="form-control" id="trabajos" rows="5" name="trabajos"
                                                                {{ $cotizacionSelect->IdEstadoCotizacion == 3 ? 'readonly' : '' }}>{{ $cotizacionSelect->Trabajos }}</textarea>
                                                            <label>Trabajos a realizar</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif

                                        <div class="row">
                                            @if ($exonerado == 1 && $sucExonerado == 1)
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-append">
                                                                <div class="input-group-text"><label>Tipo
                                                                        Operación</label></div>
                                                            </div>
                                                            @if ($cotizacionSelect->TipoVenta == 1)
                                                                <input type="text" class="form-control"
                                                                    name="tipoVenta" value="Op. Gravada" readonly>
                                                                <input id="tipoVenta" type="text" name="tipoVenta"
                                                                    value="1" hidden>
                                                            @else
                                                                <input type="text" class="form-control"
                                                                    name="tipoVenta" value="Op. Exonerada" readonly>
                                                                <input id="tipoVenta" type="text" name="tipoVenta"
                                                                    value="2" hidden>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <input id="tipoVenta" type="text" name="tipoVenta" value="1"
                                                    hidden>
                                            @endif
                                            @if ($subniveles->contains('IdSubNivel', 46) && $bandVentaSolesDolares == 1)
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="checkbox" id="ventaSolesDolares"
                                                            name="ventaSolesDolares"
                                                            {{ $cotizacionSelect->IdEstadoCotizacion == 3 ? 'disabled' : '' }}><span
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
                                            <div class="col-md-2 col-2">
                                                <input id="idEstadoCotizacion" type="text"
                                                    name="idEstadoCotizacion"
                                                    value="{{ $cotizacionSelect->IdEstadoCotizacion }}" hidden>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 d-md-flex">
                                                <div class="mt-2">
                                                    <a class="p-1" href="#" data-toggle="modal"
                                                        data-target=".bs-modal-lg-productos-soles"><button
                                                            id="agregarArticuloSoles"
                                                            {{ $cotizacionSelect->IdEstadoCotizacion == 3 ? 'disabled' : '' }}
                                                            class="btn btn-info"><i
                                                                class="list-icon material-icons d-none">add_circle</i>
                                                            Agregar Productos Soles <span
                                                                class="caret"></span></button></a>
                                                </div>
                                                <div class="mt-2">
                                                    <a class="p-1" href="#" data-toggle="modal"
                                                        data-target=".bs-modal-lg-productos-dolares"><button
                                                            id="agregarArticuloDolares"
                                                            {{ $cotizacionSelect->IdEstadoCotizacion == 3 ? 'disabled' : '' }}
                                                            class="btn btn-info"><i
                                                                class="list-icon material-icons">add_circle</i>
                                                            Agregar Productos Dólares <span
                                                                class="caret"></span></button></a>
                                                </div>
                                                @if ($subniveles->contains('IdSubNivel', 80) && $usuarioSelect->ActivarPaquetePromo == 1)
                                                    <div class="mt-2">
                                                        <a class="p-1" href="#" data-toggle="modal"
                                                            data-target=".bs-modal-lg-paquetesPromocionales-soles"><button
                                                                id="agregarPaquetesPromocionalesSoles"
                                                                {{ $cotizacionSelect->IdEstadoCotizacion == 3 ? 'disabled' : '' }}
                                                                class="btn btn--rojo d-none">Paquete Promoción Soles
                                                                <span class="caret"></span></button></a>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a class="p-1" href="#" data-toggle="modal"
                                                            data-target=".bs-modal-lg-paquetesPromocionales-dolares"><button
                                                                id="agregarPaquetesPromocionalesDolares"
                                                                {{ $cotizacionSelect->IdEstadoCotizacion == 3 ? 'disabled' : '' }}
                                                                class="btn btn--rojo d-none">
                                                                Paquete Promoción Dólares <span
                                                                    class="caret"></span></button></a>
                                                    </div>
                                                @endif
                                                {{-- @if ($cotizacionSelect->IdTipoMoneda == 1)
                                                    <div class="mt-2">
                                                        <a class="p-1" href="#" data-toggle="modal"
                                                            data-target=".bs-modal-lg-productos-soles"><button
                                                                id="agregarArticuloSoles"
                                                                {{ $cotizacionSelect->IdEstadoCotizacion == 3 ? 'disabled' : '' }}
                                                                class="btn btn-info"><i
                                                                    class="list-icon material-icons">add_circle</i>
                                                                Agregar Productos Soles <span
                                                                    class="caret"></span></button></a>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a class="p-1" href="#" data-toggle="modal"
                                                            data-target=".bs-modal-lg-productos-dolares"><button
                                                                id="agregarArticuloDolares" disabled
                                                                class="btn btn-info"><i
                                                                    class="list-icon material-icons">add_circle</i>
                                                                Agregar Productos Dólares <span
                                                                    class="caret"></span></button></a>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a class="p-1" href="#" data-toggle="modal"
                                                            data-target=".bs-modal-lg-paquetesPromocionales-soles"><button
                                                                id="agregarPaquetesPromocionalesSoles"
                                                                class="btn btn--indigo">Paquete Promoción Soles <span
                                                                    class="caret"></span></button></a>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a class="p-1" href="#" data-toggle="modal"
                                                            data-target=".bs-modal-lg-paquetesPromocionales-dolares"><button
                                                                id="agregarPaquetesPromocionalesDolares"
                                                                class="btn btn--indigo" disabled>
                                                                Paquete Promoción Dólares <span
                                                                    class="caret"></span></button></a>
                                                    </div>
                                                @else
                                                    <div class="mt-2">
                                                        <a class="p-1" href="#" data-toggle="modal"
                                                            data-target=".bs-modal-lg-productos-soles"><button
                                                                id="agregarArticuloSoles" disabled
                                                                class="btn btn-info"><i
                                                                    class="list-icon material-icons">add_circle</i>
                                                                Agregar Productos Soles <span
                                                                    class="caret"></span></button></a>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a class="p-1" href="#" data-toggle="modal"
                                                            data-target=".bs-modal-lg-productos-dolares"><button
                                                                id="agregarArticuloDolares"
                                                                {{ $cotizacionSelect->IdEstadoCotizacion == 3 ? 'disabled' : '' }}
                                                                class="btn btn-info"><i
                                                                    class="list-icon material-icons">add_circle</i>
                                                                Agregar Productos Dólares <span
                                                                    class="caret"></span></button></a>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a class="p-1" href="#" data-toggle="modal" disabled
                                                            data-target=".bs-modal-lg-paquetesPromocionales-soles"><button
                                                                id="agregarPaquetesPromocionalesSoles"
                                                                class="btn btn--indigo">Paquete Promoción Soles <span
                                                                    class="caret"></span></button></a>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a class="p-1" href="#" data-toggle="modal"
                                                            data-target=".bs-modal-lg-paquetesPromocionales-dolares"><button
                                                                id="agregarPaquetesPromocionalesDolares"
                                                                class="btn btn--indigo">
                                                                Paquete Promoción Dólares <span
                                                                    class="caret"></span></button></a>
                                                    </div>
                                                @endif --}}
                                            </div>
                                        </div>
                                        <br />
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <table id="tablaAgregado" class="table table-responsive-lg"
                                                    style="width:100%">
                                                    <thead>
                                                        <tr class="bg-primary-contrast">
                                                            <th scope="col" data-tablesaw-priority="persist">Código
                                                            </th>
                                                            <th scope="col">Descripción</th>
                                                            <th scope="col">Detalle</th>
                                                            <th scope="col">Und/Medida</th>
                                                            <th scope="col">Precio</th>
                                                            <th scope="col">C/Dcto</th>
                                                            <th scope="col">Cantidad</th>
                                                            <th scope="col">Importe</th>
                                                            @if ($cotizacionSelect->IdEstadoCotizacion != 3)
                                                                <th scope="col">Acciones</th>
                                                            @endif
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
                                                    <textarea id="observacion" class="form-control" rows="5" name="observacion" maxlength="1000">{{ $cotizacionSelect->Observacion }}</textarea>
                                                    <label>Observación</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-8">
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>Op Gravada:</label>
                                                    </div>
                                                    <div class="col-lg-5 col-8">
                                                        @if ($cotizacionSelect->TipoVenta == 1)
                                                            <input id="opGravada" name="opGravada" type="text"
                                                                readonly>
                                                        @else
                                                            <input id="opGravada" name="opGravada" type="text"
                                                                value="0.00" readonly>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>Op Exonerada:</label>
                                                    </div>
                                                    <div class="col-lg-5 col-8">
                                                        @if ($cotizacionSelect->TipoVenta == 2)
                                                            <input id="opExonerado" name="opExonerado" type="text"
                                                                readonly>
                                                        @else
                                                            <input id="opExonerado" name="opExonerado" type="text"
                                                                value="0.00" readonly>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>Descuento:</label>
                                                    </div>
                                                    <div class="col-lg-5 col-8">
                                                        <input id="descuento" name="descuento" type="text"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>IGV (18%):</label>
                                                    </div>
                                                    <div class="col-lg-5 col-8">
                                                        <input id="igv" type="text" name="igv" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>Total:</label>
                                                    </div>
                                                    <div class="col-lg-5 col-8">
                                                        <input id="total" type="text" name="total" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input hidden type="text" id="banderaVentaSolesDolares"
                                            name="banderaVentaSolesDolares" class="form-control" value="0">
                                        <div class="form-actions btn-list mt-3">
                                            <button id="btnGenerar" class="btn btn-primary"
                                                type="button">Actualizar</button>
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
                                </div>

                                <div class="modal fade" id="tipoCambio" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
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

                                {{-- MODAL PAQUETE ITEMS PAQUETE PROMOCIONAL --}}
                                {{-- Nuevo codigo Paquete Promocionales --}}
                                @include('operaciones.cotizacion._modalPaquetePromoSoles')
                                @include('operaciones.cotizacion._modalPaquetePromoDolares')
                                @include('operaciones.cotizacion._modalDetallePaquetePromo')
                                {{-- Fin --}}
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
        <input type="hidden" name="" id="inputIdEstadoCotizacion"
            value="{{ $cotizacionSelect->IdEstadoCotizacion }}">
        <input type="hidden" name="" id="inputIdClienteCotization"
            value="{{ $cotizacionSelect->IdCliente }}">
        <input type="hidden" name="" id="inputIdCotizacion" value="{{ $cotizacionSelect->IdCotizacion }}">
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

    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    {{-- Script editar --}}
    <script src="{{ asset('assets/js/operaciones/cotizacion/scriptOperacionesArticulos.js?v=' . time()) }}"></script>
    <script src="{{ asset('assets/js/operaciones/cotizacion/scriptPeticionesAjax.js?v=' . time()) }}"></script>

    <script src="{{ asset('assets/js/operaciones/cotizacion/cargarArticulosModal.js?v=' . time()) }}"></script>
    <script src="{{ asset('assets/js/libreriasExternas/loadingOverlay/loadingoverlay.min.js') }}"></script>
    <script src="{{ asset('assets/js/operaciones/cotizacion/scriptValidarDescuento.js?v=' . time()) }}"></script>

    <script>
        let sucExonerado = @json($sucExonerado);
        let datosCotizacion = @json($cotizacionSelect);
        let igv = @json(config('variablesGlobales.Igv'));
        // Varibles para validar el maximo descuento
        let descuentoMaximoSoles = @json($usuarioSelect->DescuentoMaximoSoles);
        let descuentoMaximoDolares = @json($usuarioSelect->DescuentoMaximoDolares);
        let isAdministrador = @json($usuarioSelect->Rol);
        /**
         * Actualizando el valor de la variable 'desabilitado' definida en cargarArticulosModal.js.
         * Esta variable se utiliza para deshabilitar el botón de 'agregarArticulo' cuando el stock es igual a 0.
         */
        desabilitado = @json($deshabilidato);

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
            operacionCrud: 'editar'
        }
    </script>
    <script>
        $(function() {
            let selectAtencion = document.getElementById('atencion').value;
            let selectMantenimientoActual = document.querySelector('#mantenimientoActual');
            let inputProximoMantenimiento = document.querySelector('#proximoMantenimiento');
            let inputPeriodoProximoMantenimiento = document.querySelector('#periodoProximoMantenimiento');
            if (selectAtencion == 1 || selectAtencion == 6) {
                if (selectMantenimientoActual.value == "" && inputProximoMantenimiento.value == "" &&
                    inputPeriodoProximoMantenimiento.value == "") {
                    selectMantenimientoActual.removeAttribute('disabled');
                    inputProximoMantenimiento.removeAttribute('disabled');
                    inputPeriodoProximoMantenimiento.removeAttribute('disabled');
                }
            }
            selectMantenimientoActual.addEventListener('change', (event) => {
                var indice = event.target.selectedIndex;
                var indice = indice + 1;
                var valueSelectOptions = event.target.options[indice].value;
                inputProximoMantenimiento.value = valueSelectOptions;
            });
        })
    </script>
    <script>
        $(function() {
            var now = new Date();
            var dateString = moment(now).format('YYYY-MM-DD');
            /*var dd = today.getDate();
            var mm = today.getMonth() + 1;

            var yyyy = today.getFullYear();
            if (dd < 10) {
              dd = '0' + dd;
            }
            if (mm < 10) {
              mm = '0' + mm;
            }
            var today = yyyy + '-' + mm + '-' + dd;*/
            // $("#datepicker").val(dateString);
            //$("#datepicker2").val(dateString);datepicker2
        });

        $(function() {
            var date1 = @json($fechaSoat);
            var date2 = @json($fechaRevTec);
            var dateCoti = @json($fecha);
            console.log(dateCoti);
            console.log(`fecha coti ${getFecha(dateCoti)}`);
            var fecha1 = getFecha(date1.replaceAll('-', '/'));
            var fecha2 = getFecha(date2.replaceAll('-', '/'));
            var fechaCoti = getFecha(dateCoti.replaceAll('-', '/'));
            $("#vencSoat").val(fecha1);
            $("#vencRevTecnica").val(fecha2);
            $("#datepicker2").val(fechaCoti);
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
            var dateString = yyyy + '-' + mm + '-' + dd;
            return dateString;
        }
    </script>
    <script>
        $(function() {
            var idEstadoCotizacion = @json($cotizacionSelect->IdEstadoCotizacion);
            var items = @json($items);
            var tipoVenta = @json($tipoVenta);
            var idUnidadMedida = 1;
            for (var i = 0; i < items.length; i++) {
                const codigo = items[i]["Codigo"];

                let maximaCantidad = 0;
                if (codigo.includes('PRO')) {
                    maximaCantidad = redondearAnumero(items[i]["Stock"]) + redondearAnumero(items[i][
                        "Cantidad"
                    ])
                }
                const nuevoArticulo = crearObjetoArticulo(items[i], $tipoArticulo = 'articulosBackend',
                    operacionCrud = 'editar');
                articuloEnTablaDom({
                    nuevoArticulo,
                    id: items[i]["IdArticulo"],
                    descripcion: items[i]["Descripcion"],
                    idTipoMoneda: items[i]["IdTipoMoneda"],
                    idUnidadMedida: items[i]["IdUnidadMedida"],
                    stock: maximaCantidad,
                    accion: 'crearArticuloBackend'
                });
                console.log(nuevoArticulo);
                // =====================================
                showIconoCandado(codigo);
                // =====================================
            }
            /* Codigo para cargar los paquetes promocionales en la tabla  */
            var items = @json($listaPaquetePromo);
            for (var i = 0; i < items.length; i++) {
                const nuevoArticulo = {
                    idCliente: items[i]["IdCliente"],
                    idArticulo: items[i]["IdArticulo"],
                    codigo: items[i]["Codigo"],
                    detalle: items[i]["Detalle"],
                    descuento: items[i]["Descuento"],
                    verificaTipo: items[i]["VerificaTipo"],
                    cantidad: Number(items[i]["Cantidad"]),
                    cantidadReal: items[i]["CantidadReal"],
                    precioVenta: parseFloat(items[i]["PrecioUnidadReal"]),
                    textUnidad: items[i]["TextUnidad"],
                    ganancia: items[i]["Ganancia"],
                    importe: items[i]["Importe"],
                    idPaquetePromocional: items[i]["IdPaquetePromocional"]
                }
                articuloEnTablaDom({
                    nuevoArticulo,
                    id: items[i]["IdPaquetePromocional"],
                    descripcion: items[i][
                        "NombrePaquete"
                    ],
                    idTipoMoneda: items[i]["IdTipoMoneda"],
                    accion: 'crearArticuloBackend'
                });
                // =====================================
                showIconoCandado(codigo);
                // =====================================
            }

            if (idEstadoCotizacion == 3) {
                alert(
                    "Para la edición en estado Finalizado solo se podrán realizar edición de descuentos de productos"
                );
            } else {
                alert(
                    "Para la edición se tomara en cuenta el tipo de cambio del día que se genero la cotización");
            }
        });


        // Nueva funcion agregar paquetes en la tabla
        $(function() {
            const $listaItemsPaquetePromo = @json($listaItemsPaquetePromo);
            const $idTipoMoneda = @json($idTipoMoneda);
            // =========
            crearArrayArticulosPaquetePromo($listaItemsPaquetePromo, accion = 'crearArticuloDesdeBackend');
            // =========

            if ($idTipoMoneda == 1) {
                $('#agregarPaquetesPromocionalesDolares').addClass('d-none');
                $('#agregarArticuloDolares').addClass('d-none');
                $('#agregarPaquetesPromocionalesSoles').removeClass('d-none');
                $('#agregarArticuloSoles').removeClass('d-none');
            } else {
                $('#agregarPaquetesPromocionalesDolares').removeClass('d-none');
                $('#agregarArticuloDolares').removeClass('d-none');
                $('#agregarPaquetesPromocionaleSoles').addClass('d-none');
                $('#agregarArticuloSoles').addClass('d-none');
            }
            $("#cambioVentas").hide();
            $("#cambioCompras").hide();
        });

        const capturarDatosCotizacionDom = () => {
            // CAMPO 0  = IdVehiculo | CAMPO 1 = Kilometraje | CAMPO 2 = Horometro
            // Campos a editar IdOperario, Kilometraje, Horometro, CódigoInventario, Trabajos, Observacion
            const datosCotizacionn = {
                IdCliente: datosCotizacion.IdCliente,
                IdTipoMoneda: datosCotizacion.IdTipoMoneda,
                IdCheckIn: $('#inventario').val(),
                IdOperario: $("#operario").val(),
                Serie: datosCotizacion.Serie,
                Numero: datosCotizacion.Numero,
                IdEstadoCotizacion: datosCotizacion.IdEstadoCotizacion,
                IdTipoAtencion: datosCotizacion.IdTipoAtencion,
                TipoCotizacion: datosCotizacion.TipoCotizacion,
                FechaCreacion: datosCotizacion.FechaCreacion,
                FechaFin: datosCotizacion.FechaFin,
                Campo0: datosCotizacion.Campo0, // CAMPO 0  = IdVehiculo
                Campo1: $("#kilometro").val(), // CAMPO 1 = Kilometraje
                Campo2: $("#horometro").val(), // CAMPO 2 = Horometro
                TipoVenta: datosCotizacion.TipoVenta,
                Trabajos: $("#trabajos").val(),
                Observacion: $("#observacion").val(),
                Estado: datosCotizacion.Estado,
                MantenimientoActual: $("#mantenimientoActual").val(),
                ProximoMantenimiento: $("#proximoMantenimiento").val(),
                PeriodoProximoMantenimiento: $("#periodoProximoMantenimiento").val(),
            };
            cotizacion.setDatosCotizacion(datosCotizacionn);
        }

        function enviar() {

            if ({{ $cotizacionSelect->TipoCotizacion }} === 2) {
                @if ($subpermisos->contains('IdSubPermisos', 35))
                    ejecutarPeticionAjax();
                @else
                    swal({
                        title: "Permiso no habilitado!",
                        text: 'Para poder editar tiene que tener activo el permiso Vehicular/Adminstración, informe a su administrador para su repectiva activación',
                        icon: "info",
                    })
                @endif

            } else {
                ejecutarPeticionAjax();
            }
        }

        // ============================================================================
        function obtenerArticulosNuevos(articulosAnteriores, articulosNuevos) {
            return articulosNuevos.filter(articulosNuevo => !articulosAnteriores.some(articulosAnteriores =>
                articulosAnteriores.Codigo === articulosNuevo.Codigo)).map(objModificado => ({
                ...objModificado,
                EstadoEditar: 'Nuevo'
            }));
        }

        function obtenerArticulosEliminados(articulosAnteriores, articulosNuevos) {
            return articulosAnteriores.filter(articulosAnterior => !articulosNuevos.some(articulosNuevos =>
                articulosNuevos.Codigo === articulosAnterior.Codigo)).map(objModificado => ({
                ...objModificado,
                EstadoEditar: 'Eliminado'
            }));
        }

        function obtenerArticulosModificados(articulosAnteriores, articulosNuevos) {
            return articulosNuevos.filter(articulosNuevo => {
                const obj1Correspondiente = articulosAnteriores.find(articulosAnterior => articulosAnterior
                    .Codigo === articulosNuevo.Codigo);
                return obj1Correspondiente && !sonIguales(obj1Correspondiente, articulosNuevo);
            }).map(objModificado => ({
                ...objModificado,
                EstadoEditar: 'Modificado'
            }));
        }

        function sonIguales(articulosAnterior, articulosNuevo) {
            const detalleArticuloNuevo = articulosNuevo.Detalle == '' ? null : articulosNuevo.Detalle;
            return articulosAnterior.Detalle == detalleArticuloNuevo && articulosAnterior.PrecioUnidadReal == articulosNuevo
                .PrecioUnidadReal && articulosAnterior.Descuento == articulosNuevo.Descuento && redondearAnumero(
                    articulosAnterior.Cantidad) == redondearAnumero(articulosNuevo.Cantidad);
        }
        // ============================================================================

        function ejecutarPeticionAjax() {
            articulosNuevo = obtenerArticulosNuevos(@json($items), cotizacion.articulos);
            articulosEliminados = obtenerArticulosEliminados(@json($items), cotizacion.articulos);
            articulosModificados = obtenerArticulosModificados(@json($items), cotizacion.articulos);
            const arrayArticulosParaInsertar = [...articulosNuevo, ...articulosModificados]
            const articulosParaEliminar = [...articulosEliminados, ...articulosModificados];
            const articulosParaKardex = [...articulosNuevo, ...articulosEliminados, ...articulosModificados];

            const articulosParaInsertar = arrayArticulosParaInsertar.map(objeto => {
                // Agregar la fecha de Modificacion
                const objetoConFechaModificacion = {
                    ...objeto,
                    Fecha_actualizacion: objeto.EstadoEditar === 'Modificado' ? "{{ now() }}" : null
                };
                // Eliminar la propiedad estado
                const {
                    EstadoEditar,
                    ...objetoSinEstado
                } = objetoConFechaModificacion;

                return objetoSinEstado;
            });

            $('#btnGenerar').attr("disabled", true);
            if (cotizacion.articulos.length < 1) {
                $('#btnGenerar').attr("disabled", false);
                swal({
                    title: "Por favor agregue los articulos",
                    text: data.mensaje,
                    icon: "error",
                })
                return;
            }
            $.LoadingOverlay("show", {
                image: '',
                text: 'Procesando la actualización ...',
                imageAnimation: '1.5s fadein',
                textColor: "#f6851a",
                textResizeFactor: '0.3',
                textAutoResize: true
            });
            $.ajax({
                type: 'post',
                url: '../actualizar-cotizacion',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "placa": @json($placa),
                    "vencSoat": $("#vencSoat").val(),
                    "vencRevTecnica": $("#vencRevTecnica").val(),
                    "moduloCronogramaActivo": @json($moduloCronogramaActivo),
                    "idCotizacion": $("#idCoti").val(),
                    'cotizacion': cotizacion,
                    'articulosAnteriores': @json($items),
                    'articulosPaquetePromoNuevos': arrayArticulosPaquetePromo,
                    'articulosPaquetePromoAnteriores': @json($listaItemsPaquetePromo),

                    'articulosParaInsertar': articulosParaInsertar,
                    'articulosParaEliminar': articulosParaEliminar,
                    'articulosParaKardex': articulosParaKardex
                },
                success: function(data) {
                    console.log(data);
                    if (data.respuesta == 'error') {
                        $('#btnGenerar').attr("disabled", false);
                        swal({
                            title: "Error al enviar los Datos!",
                            text: data.mensaje,
                            icon: "error",
                        })
                    }
                    if (data.respuesta == 'errorStock') {
                        $('#btnGenerar').attr("disabled", false);
                        swal({
                            title: "Error al enviar los Datos!",
                            text: data.mensaje,
                            icon: "error",
                            content: {
                                element: 'div',
                                attributes: {
                                    innerHTML: (
                                        `<p class="text-left ml-3"><b>${data.articulos.map(element => `_ ${element.Descripcion} - Stock Maximo: ${element.Stock}`).join("<br>")}</b></p>`
                                    )
                                }
                            },
                        });

                    }
                    if (data.respuesta == 'errorTransaccion') {
                        swal({
                            title: "Error al enviar los Datos!",
                            text: data.mensaje,
                            icon: "error",
                        })
                    }
                    if (data.respuesta == 'success') {
                        swal({
                            title: "Exito",
                            text: data.mensaje,
                            icon: "success",
                        }).then((value) => {
                            window.location =
                                '../../../operaciones/cotizacion/comprobante-generado/' +
                                data.id;
                        });
                    }
                    $.LoadingOverlay("hide");
                },
                error: function(error) {
                    console.error('Error en la solicitud Ajax', error);
                }
            });
        }
    </script>
    <script type="text/javascript">
        $(function() {
            $(document).ready(function() {
                $('#tablaProductos').DataTable({
                    responsive: true,
                    language: {
                        processing: "Procesando...",
                        search: "Buscar:",
                        lengthMenu: "Mostrar _MENU_ registros",
                        info: "Registros del _START_ al _END_ de un total de _TOTAL_",
                        infoEmpty: "Registros del 0 al 0 de un total de 0 registros",
                        infoFiltered: "",
                        infoPostFix: "",
                        loadingRecords: "Cargando...",
                        zeroRecords: "No se encontraron resultados",
                        emptyTable: "Ningún dato disponible en esta tabla",
                        paginate: {
                            first: "Primero",
                            previous: "Anterior",
                            next: "Siguiente",
                            last: "Último"
                        },
                        aria: {
                            sortAscending: ": Activar para ordenar la columna de manera ascendente",
                            sortDescending: ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                });
                $('#tablaServicios').DataTable({
                    responsive: true,
                    language: {
                        processing: "Procesando...",
                        search: "Buscar:",
                        lengthMenu: "Mostrar _MENU_ registros",
                        info: "Registros del _START_ al _END_ de un total de _TOTAL_",
                        infoEmpty: "Registros del 0 al 0 de un total de 0 registros",
                        infoFiltered: "",
                        infoPostFix: "",
                        loadingRecords: "Cargando...",
                        zeroRecords: "No se encontraron resultados",
                        emptyTable: "Ningún dato disponible en esta tabla",
                        paginate: {
                            first: "Primero",
                            previous: "Anterior",
                            next: "Siguiente",
                            last: "Último"
                        },
                        aria: {
                            sortAscending: ": Activar para ordenar la columna de manera ascendente",
                            sortDescending: ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                });
            });
        });
    </script>

    <script>
        function verDetallePaquetePromocional($idPaquete) {
            var tipoVenta = @json($tipoVenta);
            $('#tableDetalle').find('#tableDetalleBody').remove();
            $('#tableDetalle').append('<tbody id="tableDetalleBody"></tbody>')
            $('#totalPaquete').val('');
            $.ajax({
                type: 'GET',
                url: '../obtener-items-paquete-promocional',
                data: {
                    'idPaquete': $idPaquete,
                },
                success: function(data) {
                    var total = 0;
                    var importe = 0;
                    for (var i = 0; i < data.length; i++) {
                        if (data[i]['idTipoItems'] == 1) {
                            var codigo = "PRO"
                        } else {
                            var codigo = "SER"
                        }
                        if (tipoVenta == 2) {
                            precio = redondeo(parseFloat(data[i]['Precio'] / 1.18));
                        } else {
                            precio = data[i]['Precio'];
                        }
                        var fila = '<tr>' +
                            '<td>' + codigo + '-' + data[i]['IdArticulo'] + '</td>' +
                            '<td>' + data[i]['NombreArticulo'] + '</td>' +
                            '<td>' + precio + '</td>' +
                            '<td>' + data[i]['cantidad'] + '</td>' +
                            '</tr>';
                        importe = parseFloat(precio) * data[i]['cantidad'];
                        total += importe;
                        // $('#tableDetalle tr:last').after(fila);
                        // $('#tableDetalleBody').find('tbody').append( fila );
                        $('#tableDetalleBody').append(fila);
                    }
                    $('#totalPaquete').val(total);
                }
            })
            $('.detallePaquetePromocional').modal('show');
        }
    </script>

    <script>
        // Codigo para paginar los paquetes promocionales

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
                url: '../paginacion-paquete-promocional?page=' + page,
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

        // Codigo para paginar buscar los paquetes promocionales
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
                url: '../buscar-paquete-promocional',
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
</body>

</html>
