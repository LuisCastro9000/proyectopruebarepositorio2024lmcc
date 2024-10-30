<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pace.css') }}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Cotizacion Generada</title>
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
    <link href="{{ asset('assets/css/newStyles.css?v=' . time()) }}" rel="stylesheet" type='text/css'>
    <link href="{{ asset('assets/css/loading.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/responsive.dataTables.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" type="text/css">
    <!-- Head Libs -->
    <script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
    <script data-pace-options='{ "ajax": false, "selectors": [ "img" ]}' src="{{ asset('assets/js/pace.min.js') }}">
    </script>

    <style>
        /* Estilos personalizados para justificar el texto en SweetAlert */
        .swal-text {
            text-align: justify;
        }
        .texto-seleccionado {
            color: black;
            font-size: 14px;
        }
        .medioPago-seleccionado{
            background-color: #e9e9e9;
            border-radius: 8px;
            padding-top: 10px;
        }
    </style>
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
                            <h6 class="page-title-heading mr-0 mr-r-5">Convertir Cotizacion</h6>
                        </div>
                        <!-- /.page-title-left -->
                        <div class="page-title-right">
                            <div class="row mr-b-50 mt-2">
                                <div class="col-12 mr-b-20 d-flex">

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
                                    <div class="widget-body clearfix  form-material">

                                        @if ($tipo == 2)
                                            <div class="row">
                                                @if ($modulosSelect->contains('IdModulo', 5) && $idSeguro > 2)
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <input type="checkbox" id="facturarCliente"
                                                                name="facturarCliente"><span
                                                                class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Facturar a
                                                                Cliente</span>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="form-group" hidden>
                                                        <input type="checkbox" id="facturarCliente"
                                                            name="facturarCliente" checked><span
                                                            class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Facturar a
                                                            Cliente</span>
                                                    </div>
                                                @endif
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">
                                                                    <label>Responsable</label>
                                                                </div>
                                                            </div>
                                                            <input id="responsable" class="form-control"
                                                                placeholder="Responsable" type="text"
                                                                name="responsable" value="{{ $nombreCli }}"
                                                                maxlength="4" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-append">
                                                                <div class="input-group-text"><label>Placa</label>
                                                                </div>
                                                            </div>
                                                            <input id="placa" class="form-control"
                                                                placeholder="Placa" type="text" maxlength="8"
                                                                name="placa" value="{{ $placa }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                </div>

                                            </div>
                                        @endif
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <select id="selectTipoComp" class="form-control"
                                                        name="tipoComprobante">
                                                        <option value="0">-</option>
                                                        @if ($modulosSelect->contains('IdModulo', 4))
                                                            @foreach ($tipoComprobante as $tipCom)
                                                                <option value="{{ $tipCom->IdTipoComprobante }}">
                                                                    {{ $tipCom->Descripcion }}</option>
                                                            @endforeach
                                                        @elseif($modulosSelect->contains('IdModulo', 1))
                                                            <option value="3">Ticket</option>
                                                        @endif
                                                    </select>
                                                    <label>Tipo Comprobante</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><label>Serie</label></div>
                                                        </div>
                                                        <input id="serie" class="form-control"
                                                            placeholder="Serie" type="text" name="serie"
                                                            maxlength="4" readonly>
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
                                                            placeholder="Número" type="text" maxlength="8"
                                                            name="numero" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Cliente</label>
                                                    <!--<select class="form-control" id="clientes" name="cliente">-->
                                                    <select class="m-b-10 form-control select2-hidden-accessible"
                                                        id="clientes" name="cliente" data-placeholder="Choose"
                                                        data-toggle="select2" tabindex="-1" aria-hidden="true">
                                                        @if ($modulosSelect->contains('IdModulo', 5) && $idSeguro > 2)
                                                            <option selected value="1">{{ $seguro }}
                                                            </option>
                                                        @else
                                                            <option value="0">-</option>
                                                        @endif
                                                    </select>
                                                    <small class="text-muted"><strong>Seleccione el
                                                            Cliente</strong></small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <select id="tipoMoneda" class="form-control" disabled
                                                        name="tipoMoneda">
                                                        @foreach ($tipoMoneda as $tipMon)
                                                            @if ($idTipoMoneda == $tipMon->IdTipoMoneda)
                                                                <option selected value="{{ $tipMon->IdTipoMoneda }}">
                                                                    {{ $tipMon->Nombre }}</option>
                                                            @else
                                                                <option value="{{ $tipMon->IdTipoMoneda }}">
                                                                    {{ $tipMon->Nombre }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <label> Moneda</label>
                                                </div>
                                            </div>
                                            <!--@if ($idTipoMoneda == 2 && $amortizaciones == 0)
<div class="col-md-2">
                                            <div class="form-group">
                                                @if ($conversionCotiMoneda == 1)
<input type="checkbox" id="ventaSoles" checked disabled name="ventaSoles"><span class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Vender en soles</span>
@else
<input type="checkbox" id="ventaSoles" name="ventaSoles"><span class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Vender en soles</span>
@endif
                                            </div>
                                         </div>
@endif-->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="input-group border-bottom">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><label>Fecha</label></div>
                                                            <input type="hidden" id="datepicker" name="fecha"
                                                                value="{{ $fecha }}" />
                                                        </div>
                                                        <span class="mt-2 ml-3">{{ $fecha }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($tipo == 2)
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select class="m-b-10 form-control select2-hidden-accessible"
                                                            id="operario" name="operario" data-toggle="select2"
                                                            tabindex="-1" aria-hidden="true">
                                                            <option value="0">-</option>
                                                            @foreach ($operarios as $operario)
                                                                @if ($IdOperario == $operario->IdOperario)
                                                                    <option selected
                                                                        value="{{ $operario->IdOperario }}">
                                                                        {{ $operario->Nombres }}</option>
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
                                                                <div class="input-group-text">
                                                                    <label>Kilometraje</label>
                                                                </div>
                                                            </div>
                                                            <input id="campo1" class="form-control"
                                                                placeholder="Kilometro" value="{{ $kilometro }}"
                                                                type="text" name="kilometro" maxlength="9">
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
                                                                placeholder="Horometro" value="{{ $horometro }}"
                                                                type="text" name="horometro" maxlength="9">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <textarea id="trabajos" class="form-control" rows="3" name="trabajos">{{ $Trabajos }}</textarea>
                                                        <label>Trabajo a realizar</label>
                                                    </div>
                                                </div>
                                            </div>
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
                                                            @if ($tipoVenta == 1)
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
                                            <div class="col-md-3">
                                                <label for="retencion">Retención</label>
                                                <label class="switch p-2">
                                                    <input id="retencion" disabled type="checkbox" name="retencion">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-3" id="ordenCompraActivo">
                                                <div class="form-group">
                                                    <label for="ordenCompra">Orden de Compra</label>
                                                    <input type="text" id="ordenCompra"
                                                        name="ordenCompra" class="form-control" value="" maxlength="20">
                                                </div>
                                            </div>
                                        </div>
                                        <!--<div class="row">
                                        <div class="col-lg-2 col-md-3 col-sm-4 col-5">
                                            <div class="mt-2">
                                                <a href="#" data-toggle="modal" data-target=".bs-modal-lg-productos">
                                                    @if ($idEstadoCotizacion == 1)
<button id="agregarArticulo" disabled class="btn btn-info"><i class="list-icon material-icons">add_circle</i> Agregar <span class="caret"></span></button>
@else
<button disabled class="btn btn-info"><i class="list-icon material-icons">add_circle</i> Agregar <span class="caret"></span></button>
@endif
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-4 col-5">

                                        </div>
                                    </div>-->
                                        <br />
                                        <!--<div class="row" style="border: 1px solid #ecdcdc; padding: 10px;">
          <div class="col-md-3">
           <input type="checkbox" id="lector" name="activarLector"><span class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Lector de Códigos</span>
          </div>
          <div class="col-md-5">
           <div class="input-group">
                                                      <input type="text" id="inputBuscarCodigoProductos" name="textoBuscarCodigo" placeholder="Buscar por Codigo de barras" autofocus="" class="form-control fs-16 fw-400">
           </div>
          </div>
          <div class="col-md-4">
                                            <div class="row align-items-center" id="content-radio">

                                            </div>
          </div>
         </div>-->
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <div><span id="textoMensaje" class="text-danger"></span></div>
                                            </div>
                                            <div class="col-md-12">
                                                <div id="armarArray" hidden="">
                                                </div>
                                                <table id="tablaAgregado" class="table table-responsive-lg"
                                                    style="width:100%">
                                                    <thead>
                                                        <tr class="bg-success-contrast">
                                                            <th scope="col" data-tablesaw-priority="persist">Código
                                                            </th>
                                                            <th scope="col">Descripción</th>
                                                            <th scope="col">Detalle</th>
                                                            <th scope="col">Und/Medida</th>
                                                            <th scope="col">Precio</th>
                                                            <th scope="col">C/Dcto</th>
                                                            <th scope="col">Cantidad</th>
                                                            <th scope="col">Importe</th>
                                                            <th scope="col">Acciones</th>
                                                            <th style="display:none"; scope="col"
                                                                class="ganancia">Ganancia</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="body">
                                                        @php $fechaCoti=date('Y-m-d') @endphp
                                                        @php $fechaFinal= date("Y-m-d", strtotime($FechaFinal))@endphp


                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-lg-8 col-md-12">
                                                <div class="form-group">
                                                    <textarea id="observacion" class="form-control" rows="8" name="observacion" maxlength="1000">{{ $Observacion }}</textarea>
                                                    <label>Observación</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-8">
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>Op Gravada:</label>
                                                    </div>
                                                    <div class="col-lg-5 col-8">
                                                        @if ($tipoVenta == 1)
                                                            <input id="subtotal" name="subtotal" type="text"
                                                                readonly>
                                                        @else
                                                            <input id="subtotal" name="subtotal" type="text"
                                                                value="0.00" readonly>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>Op Exonerada:</label>
                                                    </div>
                                                    <div class="col-lg-5 col-8">
                                                        @if ($tipoVenta == 2)
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
                                                        <input id="exonerada" name="exonerada" value=""
                                                            type="text" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>IGV (18%):</label>
                                                    </div>
                                                    <div class="col-lg-5 col-8">
                                                        <input id="igv" type="text" value=""
                                                            name="igv" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>Total:</label>
                                                    </div>
                                                    <div class="col-lg-5 col-8">
                                                        <input id="total" type="text" value=""
                                                            name="total" readonly>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-lg-5 col-8">
                                                        <label>Amort. Total:</label>
                                                    </div>
                                                    <div class="col-lg-5 col-8">
                                                        <input id="amortTotal" type="text" value=""
                                                            name="amortTotal" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>Pend. por Pagar:</label>
                                                    </div>
                                                    <div class="col-lg-5 col-8">
                                                        <input id="pendientePagar" type="text" value=""
                                                            name="pendientePagar" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <select class="form-control" id="tipoPago" name="tipoPago">
                                                        <option value="1">Contado</option>
                                                        @if (floatval($amortizaciones) == 0)
                                                            @if ($modulosSelect->contains('IdModulo', 3))
                                                                <option value="2">Crédito</option>
                                                            @endif
                                                        @endif
                                                    </select>
                                                    <label>Tipo de Pago</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="ventaDetraccion">Detracción</label>
                                                <label class="switch p-2">
                                                    <input id="ventaDetraccion" disabled type="checkbox" name="ventaDetraccion">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div id="plazoCredito" class="form-group">
                                                    <label class="form-control-label">Dias</label>
                                                    <div class="input-group">
                                                        <input id="_plazoCredito" type="number" step="any"
                                                            class="form-control" name="plazoCredito"
                                                            value="{{ old('plazoCredito') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-4" id="detraccion">
                                            <fieldset class="fieldset fieldset--bordeCeleste">
                                                <legend class="legend legend--colorNegro">Datos de Detracción:
                                                </legend>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <select class="form-control" id="medioPago" name="medioPago">
                                                                    @foreach ($medioPagos as $medioPago)
                                                                        <option value="{{ $medioPago->IdMedioPago }}">{{ $medioPago->Codigo }} - {{$medioPago->Descripcion}}</option>
                                                                    @endforeach
                                                            </select>
                                                            <label>Medios de Pagos</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <select class="form-control" id="bienServicio" name="bienServicio">
                                                                    @foreach ($bienesServicios as $bienServicio)
                                                                        @if($bienServicio->IdBienesServicios == 18)
                                                                        <option value="{{ $bienServicio->IdBienesServicios }}" selected>{{ $bienServicio->CodigoSunat }} - {{$bienServicio->Descripcion}}</option>
                                                                        @else
                                                                        <option value="{{ $bienServicio->IdBienesServicios }}">{{ $bienServicio->CodigoSunat }} - {{$bienServicio->Descripcion}}</option>
                                                                        @endif
                                                                    @endforeach
                                                            </select>
                                                            <label>Código de bienes y servicio</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="valorDetraccion">Detracción (%)</label>
                                                                <input type="number" id="valorDetraccion" name="valorDetraccion"
                                                                class="form-control" value="12" min="3"
                                                                max="12" onchange="bloquearValor();">
                                                        </div>
                                                        <div class="form-group">
                                                            <text class="text-danger">CUIDADO: Solo modifique esta cantidad si
                                                                su cliente le solicita y trabaja con un monto diferente al
                                                                12%</text>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-md-4" id="fondoEfectivo">
                                                <div id="interes" hidden class="form-group">
                                                    <label class="form-control-label">Interés (%)</label>
                                                    <div class="input-group">
                                                        <input id="_interes" type="number" step="any"
                                                            class="form-control" name="interes" value="0">
                                                    </div>
                                                </div>
                                                <div id="efectivo" class="form-group">
                                                    <label class="form-control-label">Monto Pagado(Efectivo)</label>
                                                    <div class="input-group">
                                                        <input id="pagoEfec" onchange="vuelto();" type="number"
                                                            step="any" class="form-control" name="pagoEfectivo"
                                                            value="{{ old('pagoEfectivo') }}">
                                                    </div>
                                                </div>
                                                <div id="vuelto" class="form-group">
                                                    <text class="form-control-label fw-600 fs-10">VUELTO
                                                        (EFECTIVO)</text>
                                                    <div class="input-group">
                                                        <input id="vueltoEfec" readonly type="number" step="any"
                                                            class="form-control" name="vueltoEfectivo"
                                                            value="{{ old('vueltoEfectivo') }}">
                                                    </div>
                                                </div>
                                                <div id="textoDetraccion" class="form-group">
                                                    <text class="text-danger">CUIDADO: Solo modifique esta cantidad si
                                                        su cliente le solicita y trabaja con un monto diferente al
                                                        12%</text>
                                                </div>
                                            </div>
                                            <div id="tarjeta" class="col-md-4">
                                                @if ($modulosSelect->contains('IdModulo', 2))
                                                    <div class="form-group">
                                                        <select id="tipoTarjeta" class="form-control"
                                                            name="tipoTarjeta">
                                                            <option value="1">Visa</option>
                                                            <option value="2">MasterCard</option>
                                                        </select>
                                                        <label class="form-control-label">Tarjeta
                                                            Crédito/Débito</label>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-control-label">4 últimos dígitos</label>
                                                        <div class="input-group">
                                                            <input id="numTarjeta" type="text"
                                                                class="form-control" name="numTarjeta" minlength="4"
                                                                maxlength="4" value="{{ old('numTarjeta') }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-control-label">Monto Pagado(Con
                                                            tarjeta)</label>
                                                        <div class="input-group">
                                                            <input id="pagoTarjeta" type="number" step="any"
                                                                class="form-control" name="pagoTarjeta"
                                                                value="{{ old('pagoTarjeta') }}">
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-4" id="cuentaCorriente">
                                                <div class="form-group">
                                                    <select class="form-control" id="cuentaBancaria"
                                                        name="cuentaBancaria">
                                                        <option value="0">Seleccione cuenta bancaria</option>
                                                        @foreach ($cuentas as $banco)
                                                            <option value="{{ $banco->IdBanco }}">{{ $banco->Banco }}
                                                                - {{ $banco->NumeroCuenta }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label>Cuenta Bancaria</label>
                                                </div>
                                                <div class="form-group">
                                                    <text
                                                        style="font-size: .75em;top: 0;opacity: 1;font-weight: 700;">FECHA
                                                        ABONO DEPÓSITO</text>
                                                    <div class="input-group">
                                                        <input id="date" type="text"
                                                            class="form-control datepicker" name="fechaPagoCuenta"
                                                            data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                            data-date-end-date="0d" autocomplete="off"
                                                            onkeydown="return false" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-control-label">Numero Operación</label>
                                                    <div class="input-group">
                                                        <input id="nroOperacion" type="text" class="form-control"
                                                            name="nroOperacion" value="{{ old('nroOperacion') }}"
                                                            disabled>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-control-label">Monto (Cuenta Bancaria)</label>
                                                    <div class="input-group">
                                                        <input id="pagoCuenta" type="number" step="any"
                                                            class="form-control" name="montoCuenta"
                                                            value="{{ old('montoCuenta') }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions btn-list mt-3">
                                            <input type="hidden" id="IdC" value="{{ $IdCliente }}" />
                                            <input type="hidden" id="idSeguro" value="{{ $idSeguro }}" />
                                            <input type="hidden" id="tipoCoti" value="{{ $tipo }}" />
                                            <input type="hidden" id="cotizacion" value="{{ $IdCotizacion }}" />
                                            <input type="hidden" id="estadoCotizacion"
                                                value="{{ $idEstadoCotizacion }}" />
                                            <!--<input type="hidden" id="valorCambio" name="valorCambio" value="0">-->
                                            <!--<input type="hidden" id="valorVentaSoles" name="valorVentaSoles" value="0">-->
                                            <!--<input type="hidden" id="conversionCotiMoneda" name="conversionCotiMoneda" value="{{ $conversionCotiMoneda }}">-->
                                            <input type="hidden" id="valorCambioVentas" name="valorCambioVentas"
                                                class="form-control" value="0">
                                            <input type="hidden" id="valorCambioCompras" name="valorCambioCompras"
                                                class="form-control" value="0">
                                            <input hidden type="text" id="switchDetraccion" name="switchDetraccion"
                                                class="form-control" value="0">
                                            <input hidden type="text" id="switchRetencion" name="switchRetencion"
                                                class="form-control" value="0">
                                            <button id="btnGenerar" class="btn btn-success" type="button"
                                                onclick="enviar();">Generar</button>

                                        </div>

                                        <div class="modal fade bs-modal-lg-productos" tabindex="-1" role="dialog"
                                            aria-labelledby="myLargeModalLabel" aria-hidden="true"
                                            style="display: none">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <div class="tabs tabs-bordered">
                                                            <ul class="nav nav-tabs">
                                                                <li class="nav-item"><a class="nav-link active"
                                                                        href="#tab-productos" data-toggle="tab"
                                                                        aria-controls="tab-productos">Productos</a>
                                                                </li>
                                                                <li class="nav-item"><a class="nav-link"
                                                                        href="#tab-servicios" data-toggle="tab"
                                                                        aria-controls="tab-servicios">Servicios</a>
                                                                </li>
                                                            </ul>
                                                            <div class="tab-content">
                                                                <div class="tab-pane active" id="tab-productos">
                                                                    <!--<h5 class="modal-title" id="myLargeModalLabel">Listado de Productos</h5>-->
                                                                    <div class="clearfix">
                                                                        <div class="form-group form-material">
                                                                            <div class="row">
                                                                                <div class="col-6">
                                                                                    <label
                                                                                        class="form-control-label fs-14 fw-500">Buscar
                                                                                        Producto</label>
                                                                                </div>
                                                                                <div class="col-6">
                                                                                    <!--  <input type="checkbox" id="lector" name="activarLector"><span class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Lector de Códigos</span>-->
                                                                                </div>
                                                                            </div>
                                                                            <div class="input-group">
                                                                                <input type="text"
                                                                                    id="inputBuscarProductos"
                                                                                    name="textoBuscar"
                                                                                    class="form-control fs-16 fw-400">
                                                                                <!--    <input type="text" id="inputBuscarCodigoProductos" name="textoBuscarCodigo" autofocus="" class="form-control fs-16 fw-400"> -->
                                                                            </div>
                                                                        </div>

                                                                        <!-- Products List -->
                                                                        <div id="listaProductos"
                                                                            class="ecommerce-products list-unstyled row">
                                                                            @foreach ($productos as $producto)
                                                                                <div
                                                                                    class="product col-12 col-md-6 idem-{{ $producto->IdArticulo }}">
                                                                                    <div class="card-body">
                                                                                        <div class="row">
                                                                                            <div class="col-12">
                                                                                                @if ($idTipoMoneda == 1)
                                                                                                    S/
                                                                                                @else
                                                                                                    $
                                                                                                @endif
                                                                                                <span
                                                                                                    id="p2-{{ $producto->IdArticulo }}"
                                                                                                    class="product-price fs-16">
                                                                                                    {{ $producto->Precio }}</span>
                                                                                            </div>
                                                                                            <div class="col-12">
                                                                                                <span
                                                                                                    id="p1-{{ $producto->IdArticulo }}"
                                                                                                    class="product-title font-weight-bold fs-16">{{ $producto->Descripcion }}</span>
                                                                                            </div>
                                                                                            <div class="col-12">
                                                                                                <span
                                                                                                    class="text-muted">{{ $producto->Marca }}</span>
                                                                                            </div>

                                                                                        </div>

                                                                                        <input hidden
                                                                                            id="p3-{{ $producto->IdArticulo }}"
                                                                                            value="{{ $producto->UM }}" />
                                                                                        <!-- esto puse 1 -->
                                                                                        <input hidden
                                                                                            id="IdUnidadMedida-{{ $producto->IdArticulo }}"
                                                                                            value="{{ $producto->IdUnidadMedida }}" />
                                                                                        <!-- esto puse 1 -->


                                                                                        <div class="form-group mt-2"
                                                                                            hidden>
                                                                                            <label
                                                                                                class="col-form-label-sm">Cantidad
                                                                                            </label>
                                                                                            @if ($producto->Stock < 1)
                                                                                                <input
                                                                                                    id="p4-{{ $producto->IdArticulo }}"
                                                                                                    type="number"
                                                                                                    min="0"
                                                                                                    value="0"
                                                                                                    class=" text-center" />
                                                                                            @else
                                                                                                <input
                                                                                                    id="p4-{{ $producto->IdArticulo }}"
                                                                                                    type="number"
                                                                                                    min="1"
                                                                                                    value="1"
                                                                                                    max="{{ $producto->Stock }}"
                                                                                                    class=" text-center" />
                                                                                            @endif
                                                                                        </div>

                                                                                        <div class="form-group" hidden>
                                                                                            <label
                                                                                                class="col-form-label-sm">Descuento
                                                                                            </label>
                                                                                            <input
                                                                                                id="p5-{{ $producto->IdArticulo }}"
                                                                                                value="0.0"
                                                                                                class="text-center" />
                                                                                        </div>

                                                                                        <div hidden>
                                                                                            <div
                                                                                                class="form-group col-12">
                                                                                                <label
                                                                                                    class="col-form-label-sm">Costo</label>
                                                                                                <input
                                                                                                    id="p6-{{ $producto->IdArticulo }}"
                                                                                                    value="{{ $producto->Costo }}"
                                                                                                    class="form-control text-center" />
                                                                                            </div>
                                                                                        </div>
                                                                                        <div hidden>
                                                                                            <div
                                                                                                class="form-group col-12">
                                                                                                <label
                                                                                                    class="col-form-label-sm">Stock
                                                                                                </label>
                                                                                                <input
                                                                                                    id="p7-{{ $producto->IdArticulo }}"
                                                                                                    value="{{ $producto->Stock }}"
                                                                                                    class="form-control text-center" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="card-footer">
                                                                                        <div
                                                                                            class="product-info col-12">
                                                                                            @if ($producto->Stock < 1)
                                                                                                <a class="bg-info color-white fs-12 disabled"
                                                                                                    href="javascript:void(0);">
                                                                                                    Agotado
                                                                                                </a>
                                                                                            @else
                                                                                                <a class="bg-info color-white fs-12"
                                                                                                    onclick="agregarProducto({{ $producto->IdArticulo }})"
                                                                                                    href="javascript:void(0);">
                                                                                                    <i
                                                                                                        class="list-icon material-icons">add</i>Agregar
                                                                                                </a>
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                        <!-- /.ecommerce-products -->
                                                                        <!-- Product Navigation -->
                                                                        <div class="col-md-12">
                                                                            <nav aria-label="Page navigation">
                                                                                <ul id="paginasProductos"
                                                                                    class="pagination pagination-md d-flex justify-content-center pagProd">
                                                                                    @if ($productos->onFirstPage())
                                                                                        <li class="page-item"><a
                                                                                                class="page-link disabled"
                                                                                                aria-label="Previous"><span
                                                                                                    aria-hidden="true"><i
                                                                                                        class="feather feather-chevrons-left"></i></span></a>
                                                                                        </li>
                                                                                        <li class="page-item"><a
                                                                                                class="page-link disabled"
                                                                                                aria-label="Previous"><span
                                                                                                    aria-hidden="true"><i
                                                                                                        class="feather feather-chevron-left"></i></span></a>
                                                                                        </li>
                                                                                    @else
                                                                                        <li class="page-item"><a
                                                                                                class="page-link"
                                                                                                href="productos?page=1"
                                                                                                aria-label="Previous"><span
                                                                                                    aria-hidden="true"><i
                                                                                                        class="feather feather-chevrons-left"></i></span></a>
                                                                                        </li>
                                                                                        <li class="page-item"><a
                                                                                                class="page-link"
                                                                                                href="{{ $productos->previousPageUrl() }}"
                                                                                                aria-label="Previous"><span
                                                                                                    aria-hidden="true"><i
                                                                                                        class="feather feather-chevron-left"></i></span></a>
                                                                                        </li>
                                                                                    @endif

                                                                                    @if ($productos->currentPage() < 3)
                                                                                        @for ($i = 1; $i <= 5; $i++)
                                                                                            @if ($i > 0 && $i <= $productos->lastPage())
                                                                                                @if ($i == $productos->currentPage())
                                                                                                    <li
                                                                                                        class="page-item active">
                                                                                                        <a class="page-link"
                                                                                                            href="javascript:void(0);">{{ $i }}</a>
                                                                                                    </li>
                                                                                                @else
                                                                                                    <li
                                                                                                        class="page-item">
                                                                                                        <a class="page-link"
                                                                                                            href="productos?page={{ $i }}">{{ $i }}</a>
                                                                                                    </li>
                                                                                                @endif
                                                                                            @endif
                                                                                        @endfor
                                                                                    @elseif($productos->lastPage() > 2)
                                                                                        @if ($productos->currentPage() > $productos->lastPage() - 2)
                                                                                            @for ($i = $productos->currentPage() - 4; $i <= $productos->lastPage(); $i++)
                                                                                                @if ($i > 0 && $i <= $productos->lastPage())
                                                                                                    @if ($i == $productos->currentPage())
                                                                                                        <li
                                                                                                            class="page-item active">
                                                                                                            <a class="page-link"
                                                                                                                href="javascript:void(0);">{{ $i }}</a>
                                                                                                        </li>
                                                                                                    @else
                                                                                                        <li
                                                                                                            class="page-item">
                                                                                                            <a class="page-link"
                                                                                                                href="productos?page={{ $i }}">{{ $i }}</a>
                                                                                                        </li>
                                                                                                    @endif
                                                                                                @endif
                                                                                            @endfor
                                                                                        @endif
                                                                                    @endif
                                                                                    @if ($productos->currentPage() >= 3 && $productos->currentPage() <= $productos->lastPage() - 2)
                                                                                        @for ($i = $productos->currentPage() - 2; $i <= $productos->currentPage() + 2; $i++)
                                                                                            @if ($i > 0 && $i <= $productos->lastPage())
                                                                                                @if ($i == $productos->currentPage())
                                                                                                    <li
                                                                                                        class="page-item active">
                                                                                                        <a class="page-link"
                                                                                                            href="javascript:void(0);">{{ $i }}</a>
                                                                                                    </li>
                                                                                                @else
                                                                                                    <li
                                                                                                        class="page-item">
                                                                                                        <a class="page-link"
                                                                                                            href="productos?page={{ $i }}">{{ $i }}</a>
                                                                                                    </li>
                                                                                                @endif
                                                                                            @endif
                                                                                        @endfor
                                                                                    @endif
                                                                                    @if ($productos->hasMorePages())
                                                                                        <li class="page-item"><a
                                                                                                class="page-link"
                                                                                                href="{{ $productos->nextPageUrl() }}"
                                                                                                aria-label="Next"><span
                                                                                                    aria-hidden="true"><i
                                                                                                        class="feather feather-chevron-right"></i></span></a>
                                                                                        </li>
                                                                                        <li class="page-item"><a
                                                                                                class="page-link"
                                                                                                href="productos?page={{ $productos->lastPage() }}"
                                                                                                aria-label="Next"><span
                                                                                                    aria-hidden="true"><i
                                                                                                        class="feather feather-chevrons-right"></i></span></a>
                                                                                        </li>
                                                                                    @else
                                                                                        <li class="page-item"><a
                                                                                                class="page-link disabled"
                                                                                                aria-label="Next"><span
                                                                                                    aria-hidden="true"><i
                                                                                                        class="feather feather-chevron-right"></i></span></a>
                                                                                        </li>
                                                                                        <li class="page-item"><a
                                                                                                class="page-link disabled"
                                                                                                aria-label="Next"><span
                                                                                                    aria-hidden="true"><i
                                                                                                        class="feather feather-chevrons-right"></i></span></a>
                                                                                        </li>
                                                                                    @endif
                                                                                </ul>
                                                                            </nav>
                                                                        </div>
                                                                        <!-- /.col-md-12 -->
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane" id="tab-servicios">
                                                                    <div class="clearfix">
                                                                        <div class="form-group form-material">
                                                                            <label
                                                                                class="form-control-label fs-14 fw-400">Buscar
                                                                                Servicio</label>
                                                                            <div class="input-group">
                                                                                <input type="text"
                                                                                    id="inputBuscarServicios"
                                                                                    name="textoBuscar"
                                                                                    class="form-control fs-16 fw-400">
                                                                            </div>
                                                                        </div>
                                                                        <!-- Products List -->
                                                                        <div id="listaServicios"
                                                                            class="ecommerce-products list-unstyled row">
                                                                            @foreach ($servicios as $servicio)
                                                                                <div class="product col-12 col-md-6">
                                                                                    <div class="card-body">
                                                                                        <div class="row">
                                                                                            <div class="col-12">
                                                                                                @if ($idTipoMoneda == 1)
                                                                                                    S/
                                                                                                @else
                                                                                                    $
                                                                                                @endif
                                                                                                <span
                                                                                                    id="s2-{{ $servicio->IdArticulo }}"
                                                                                                    class="product-price fs-16">{{ $servicio->Precio }}</span>
                                                                                            </div>
                                                                                            <div class="col-12">
                                                                                                <span
                                                                                                    id="s1-{{ $servicio->IdArticulo }}"
                                                                                                    class="product-title font-weight-bold fs-16">{{ $servicio->Descripcion }}</span>
                                                                                            </div>
                                                                                        </div>
                                                                                        <input hidden
                                                                                            id="s6-{{ $servicio->IdArticulo }}"
                                                                                            value="{{ $servicio->UM }}" />
                                                                                        <div class="form-group mt-2"
                                                                                            hidden>
                                                                                            <label
                                                                                                class="col-form-label-sm">Cantidad
                                                                                            </label>
                                                                                            <input
                                                                                                id="s5-{{ $servicio->IdArticulo }}"
                                                                                                type="number"
                                                                                                min="1"
                                                                                                value="1"
                                                                                                class="text-center" />
                                                                                        </div>
                                                                                        <div class="form-group" hidden>
                                                                                            <label
                                                                                                class="col-form-label-sm">Descuento
                                                                                            </label>
                                                                                            <input
                                                                                                id="s3-{{ $servicio->IdArticulo }}"
                                                                                                type="text"
                                                                                                value="0.0"
                                                                                                class="text-center" />
                                                                                        </div>
                                                                                        <div hidden>
                                                                                            <div
                                                                                                class="form-group col-12">
                                                                                                <label
                                                                                                    class="col-form-label-sm">Costo</label>
                                                                                                <input
                                                                                                    id="s4-{{ $servicio->IdArticulo }}"
                                                                                                    value="{{ $servicio->Costo }}"
                                                                                                    class=" text-center" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="card-footer">
                                                                                        <div
                                                                                            class="product-info col-12">
                                                                                            <a class="bg-info color-white fs-12"
                                                                                                onclick="agregarServicio({{ $servicio->IdArticulo }})"
                                                                                                href="javascript:void(0);">
                                                                                                <i
                                                                                                    class="list-icon material-icons">add</i>Agregar
                                                                                            </a>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- /.card-footer -->

                                                                                    <!-- /.card -->
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                        <!-- /.ecommerce-products -->
                                                                        <!-- Product Navigation -->
                                                                        <div class="col-md-12">
                                                                            <nav aria-label="Page navigation">
                                                                                <ul id="paginasServicios"
                                                                                    class="pagination pagination-md d-flex justify-content-center pagServ">
                                                                                    @if ($servicios->onFirstPage())
                                                                                        <li class="page-item"><a
                                                                                                class="page-link disabled"
                                                                                                aria-label="Previous"><span
                                                                                                    aria-hidden="true"><i
                                                                                                        class="feather feather-chevrons-left"></i></span></a>
                                                                                        </li>
                                                                                        <li class="page-item"><a
                                                                                                class="page-link disabled"
                                                                                                aria-label="Previous"><span
                                                                                                    aria-hidden="true"><i
                                                                                                        class="feather feather-chevron-left"></i></span></a>
                                                                                        </li>
                                                                                    @else
                                                                                        <li class="page-item"><a
                                                                                                class="page-link"
                                                                                                href="servicios?page=1"
                                                                                                aria-label="Previous"><span
                                                                                                    aria-hidden="true"><i
                                                                                                        class="feather feather-chevrons-left"></i></span></a>
                                                                                        </li>
                                                                                        <li class="page-item"><a
                                                                                                class="page-link"
                                                                                                href="{{ $servicios->previousPageUrl() }}"
                                                                                                aria-label="Previous"><span
                                                                                                    aria-hidden="true"><i
                                                                                                        class="feather feather-chevron-left"></i></span></a>
                                                                                        </li>
                                                                                    @endif
                                                                                    @if ($servicios->currentPage() < 3)
                                                                                        @for ($i = 1; $i <= 5; $i++)
                                                                                            @if ($i > 0 && $i <= $servicios->lastPage())
                                                                                                @if ($i == $servicios->currentPage())
                                                                                                    <li
                                                                                                        class="page-item active">
                                                                                                        <a class="page-link"
                                                                                                            href="javascript:void(0);">{{ $i }}</a>
                                                                                                    </li>
                                                                                                @else
                                                                                                    <li
                                                                                                        class="page-item">
                                                                                                        <a class="page-link"
                                                                                                            href="servicios?page={{ $i }}">{{ $i }}</a>
                                                                                                    </li>
                                                                                                @endif
                                                                                            @endif
                                                                                        @endfor
                                                                                    @elseif($servicios->lastPage() > 2)
                                                                                        @if ($servicios->currentPage() > $servicios->lastPage() - 2)
                                                                                            @for ($i = $servicios->currentPage() - 4; $i <= $servicios->lastPage(); $i++)
                                                                                                @if ($i > 0 && $i <= $servicios->lastPage())
                                                                                                    @if ($i == $servicios->currentPage())
                                                                                                        <li
                                                                                                            class="page-item active">
                                                                                                            <a class="page-link"
                                                                                                                href="javascript:void(0);">{{ $i }}</a>
                                                                                                        </li>
                                                                                                    @else
                                                                                                        <li
                                                                                                            class="page-item">
                                                                                                            <a class="page-link"
                                                                                                                href="servicios?page={{ $i }}">{{ $i }}</a>
                                                                                                        </li>
                                                                                                    @endif
                                                                                                @endif
                                                                                            @endfor
                                                                                        @endif
                                                                                    @endif
                                                                                    @if ($servicios->currentPage() >= 3 && $servicios->currentPage() <= $servicios->lastPage() - 2)
                                                                                        @for ($i = $servicios->currentPage() - 2; $i <= $servicios->currentPage() + 2; $i++)
                                                                                            @if ($i > 0 && $i <= $servicios->lastPage())
                                                                                                @if ($i == $servicios->currentPage())
                                                                                                    <li
                                                                                                        class="page-item active">
                                                                                                        <a class="page-link"
                                                                                                            href="javascript:void(0);">{{ $i }}</a>
                                                                                                    </li>
                                                                                                @else
                                                                                                    <li
                                                                                                        class="page-item">
                                                                                                        <a class="page-link"
                                                                                                            href="servicios?page={{ $i }}">{{ $i }}</a>
                                                                                                    </li>
                                                                                                @endif
                                                                                            @endif
                                                                                        @endfor
                                                                                    @endif
                                                                                    @if ($servicios->hasMorePages())
                                                                                        <li class="page-item"><a
                                                                                                class="page-link"
                                                                                                href="{{ $servicios->nextPageUrl() }}"
                                                                                                aria-label="Next"><span
                                                                                                    aria-hidden="true"><i
                                                                                                        class="feather feather-chevron-right"></i></span></a>
                                                                                        </li>
                                                                                        <li class="page-item"><a
                                                                                                class="page-link"
                                                                                                href="servicios?page={{ $servicios->lastPage() }}"
                                                                                                aria-label="Next"><span
                                                                                                    aria-hidden="true"><i
                                                                                                        class="feather feather-chevrons-right"></i></span></a>
                                                                                        </li>
                                                                                    @else
                                                                                        <li class="page-item"><a
                                                                                                class="page-link disabled"
                                                                                                aria-label="Next"><span
                                                                                                    aria-hidden="true"><i
                                                                                                        class="feather feather-chevron-right"></i></span></a>
                                                                                        </li>
                                                                                        <li class="page-item"><a
                                                                                                class="page-link disabled"
                                                                                                aria-label="Next"><span
                                                                                                    aria-hidden="true"><i
                                                                                                        class="feather feather-chevrons-right"></i></span></a>
                                                                                        </li>
                                                                                    @endif
                                                                                </ul>
                                                                            </nav>
                                                                        </div>
                                                                        <!-- /.col-md-12 -->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button"
                                                            class="btn btn-success btn-rounded ripple text-left"
                                                            data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog"
                                            aria-labelledby="basicModal" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h6 class="text-danger">Aviso Importante</h6>
                                                    </div>
                                                    <div class="modal-body form-material">
                                                        <div>
                                                            <label class="fs-14 negrita">Nota Importante</label>
                                                            <p class="fs-15negrita">Los precios de algunos productos, o
                                                                otros valores pudierón haber <strong>cambiado</strong>,
                                                                esto se debe a que la fecha limite de la cotizacion ha
                                                                expirado....Tenga esto en consideración</p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <div class="form-actions btn-list mt-3">
                                                            <button class="btn btn-danger" type="button"
                                                                data-dismiss="modal">Aceptar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- MODAL PAQUETE ITEMS PAQUETE PROMOCIONAL --}}
                                        <div class="modal detallePaquetePromocional" id="detallePaquetePromocional"
                                            tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                            aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="text-center mt-4">
                                                        <h5 class="modal-title" id="exampleModalLabel">Detalle Paquete
                                                            Promocional</h5>
                                                        <hr>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table id="tableDetalle" class="table table-responsive-lg"
                                                            style="width:100%">
                                                            <thead>
                                                                <tr class="bg-primary-contrast">
                                                                    <th scope="col"
                                                                        data-tablesaw-priority="persist">
                                                                        Código</th>
                                                                    <th scope="col">Descripción</th>
                                                                    <th scope="col">Precio</th>
                                                                    <th scope="col">Cantidad</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="tableDetalleBody">
                                                            </tbody>
                                                        </table>
                                                        <section class="d-flex justify-content-end align-items-center">
                                                            <label class="mr-2">Total:</label>
                                                            <article id="contenedorInputTotal">
                                                                <input id="totalPaquete" class="input-transparent"
                                                                    type="text" name="totalPaquete" readonly>
                                                            </article>
                                                        </section>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- FIN --}}


                                        {{-- MODAL REGISTRAR CUENTA DE DETRACCIONES --}}
                                        <div class="modal fade" id="ModalCuentaDetracciones" tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModal3Label" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form id="formRegistrarCuentaDetracciones"
                                                        action="{{ route('cuentas-bancarias.create') }}"
                                                        method="POST">
                                                        @method('GET')
                                                        <div class="modal-header d-flex justify-content-center">
                                                            <h6 class="modal-title" id="exampleModal3Label">Cuenta de
                                                                Detracciones </h6>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="text-dark text-justify">De acuerdo a las
                                                                nuevas normativas
                                                                de SUNAT, es obligatorio
                                                                registrar su cuenta de detracciones. Este requisito es
                                                                fundamental para garantizar el cumplimiento de las
                                                                regulaciones fiscales.</p>
                                                            <input type="hidden" name="idCuentraDetracciones"
                                                                value="9">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-primary">Registrar
                                                                Cuenta en el ERP</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- FIN --}}
                                        <!--<div class="modal fade" id="tipoCambio" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                <h6 class="modal-title">Configurar Tipo de Cambio</h6>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="form-group">
                                                        <label for="soles">Tipo de Cambio Compras</label>
                                                        <input id="tipoCambioCompras" class="form-control" name="TipoCambioCompras">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="soles">Tipo de Cambio Ventas</label>
                                                        <input id="tipoCambioVentas"  class="form-control" name="TipoCambioVentas">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button id="btnTipoCambio" onclick="guardaTipoCambio();" class="btn btn-primary btnEliminar">Aceptar</button>
                                                    <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>-->
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
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
    <script src="{{ asset('assets/js/bootstrap-input-spinner.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-numbercontrol.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.nice-number.js') }}"></script>

    <script src="{{ asset('assets/js/libreriasExternas/libreriaSweetAlert1/libreriaSweetalert1.js') }}"></script>
    <script src="{{ asset('assets/js/libreriasExternas/loadingOverlay/loadingoverlay.min.js') }}"></script>
    <script src="{{ asset('assets/js/utilidades/utilidades.js?v=' . time()) }}"></script>
    <script>
        // Varibles para validar el maximo descuento
        let descuentoMaximoSoles = @json($usuarioSelect->DescuentoMaximoSoles);
        let descuentoMaximoDolares = @json($usuarioSelect->DescuentoMaximoDolares);
        let isAdministrador = @json($usuarioSelect->Rol);

        $(document).on('change', (e) => {
            const inputModificarDescuento = $(e.target).closest('.inputModificarDescuento');

            if (inputModificarDescuento.length > 0 && isAdministrador !== 'Administrador') {
                const tipoMoneda = $("#tipoMoneda").val();
                const descuentoActual = inputModificarDescuento.val();
                if (descuentoMaximoSoles !== null && tipoMoneda == 1) {
                    if (parseFloat(descuentoActual) > parseFloat(descuentoMaximoSoles)) {
                        $(e.target).val(descuentoMaximoSoles);
                        inputModificarDescuento.trigger('change');
                        swal("Descuento Máximo por items",
                            `Ha excedido el límite del descuento máximo (${descuentoMaximoSoles}) por items en soles, establecido por el administrador.`,
                            "warning");
                    }
                }
                if (descuentoMaximoDolares !== null && tipoMoneda == 2) {
                    if (parseFloat(descuentoActual) > parseFloat(descuentoMaximoDolares)) {
                        inputModificarDescuento.val(descuentoMaximoDolares);
                        inputModificarDescuento.trigger('change');
                        swal("Descuento Máximo por items",
                            `Ha excedido el límite del descuento máximo (${descuentoMaximoDolares}) por items en dólares, establecido por el administrador.`,
                            "warning");
                    }
                }
            }
        });
    </script>
    <script type="text/javascript">
        $(function() {
            $(document).ready(function() {
                $('#agregarArticulo').attr('disabled', 'disabled');
                $('#table').DataTable({
                    responsive: true,
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "searching": false
                });
            });

            var valorCambio = <?php echo json_encode($valorCambio); ?>;
            var idTipoMoneda = <?php echo json_encode($idTipoMoneda); ?>;

            if (valorCambio != null) {
                $("#valorCambioVentas").val(valorCambio["TipoCambioVentas"]);
                $("#valorCambioCompras").val(valorCambio["TipoCambioCompras"]);
            }
            /*if(idTipoMoneda == 2 && valorCambio == null){
                alert("Primero debe configurar tipo de cambio");
                window.location='../../../administracion/bancos/tipo-cambio';     
            }else{
                $("#valorCambio").val(valorCambio["TipoCambioVentas"]);
            }*/

            /*var conversionCotiMoneda = <?php echo json_encode($conversionCotiMoneda); ?>;
            if(conversionCotiMoneda == 1){
                $("#valorVentaSoles").val(1);
            }else{
                $("#valorVentaSoles").val(0);
            }*/

        });
    </script>
    <script>
        $(function() {

            <?php if($fechaCoti > $fechaFinal): ?>
            $("#mostrarmodal").modal("show");
            <?php endif; ?>

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
            var dateBanco = dd + '/' + mm + '/' + yyyy;
            $("#date").val(dateBanco);
        });
    </script>
    <script>
        var jsonStock = [];
        var total = 0;
        var subtotal = 0;
        var igvTotal = 0;
        var exonerada = 0;
        var datosProductos;
        var array = [];
        var arrayIds = [];
        var banderita = 0;
        var opExonerado = 0;
        var iden = 1;
        var banderaServicio = 0;

        $(function() {
            $('#plazoCredito').hide();
            //$('#interes').hide();
            $('#detraccion').hide();
            $('#textoDetraccion').hide();
            $('.ganancia').hide();
            $('#ordenCompraActivo').hide();

            $("#selectTipoComp").on('change', function() {
                $.showLoading({
                    name:'circle-fade',
                });
                var tipoDocumento = $("#selectTipoComp").val();
                var idC = $("#IdC").val();
                var tipo = <?php echo json_encode($tipo); ?>;
                var tipoMoneda = $("#tipoMoneda").val();
                var tipoVenta = $('#tipoVenta').val();
                var tipoPago = $("#tipoPago").val();
                $('#clientes option').remove();
                $('#clientes').append('<option value="0">-</option>');
                $.ajax({
                    type: 'get',
                    url: '../obtener-informacion',
                    data: {
                        'tipoDoc': tipoDocumento
                    },
                    success: function(result) {
                        if (result.error) {
                            $('#serie').val('');
                            $('#numero').val('');
                            //$('#agregarArticulo').attr('disabled','disabled');
                            alert('Seleccione el Documento');
                            $('#ordenCompraActivo').hide();
                        } else {
                            //$('#agregarArticulo').removeAttr('disabled');
                            $('#serie').val(result.serie);
                            $('#numero').val(result.numero);
                            $('#agregarArticulo').removeAttr('disabled');
                            if ($("#facturarCliente:checked").val() || tipo == 1) {

                                for (var i = 0; i < result.clientes.length; i++) {
                                    if (idC == result.clientes[i]["IdCliente"]) {
                                        $('#clientes').append('<option selected value="' +
                                            result.clientes[i]["IdCliente"] + '">' + result
                                            .clientes[i]["RazonSocial"] + '</option>');
                                        //$('#agregarArticulo').removeAttr('disabled');
                                    } else {
                                        $('#clientes').append('<option value="' + result
                                            .clientes[i]["IdCliente"] + '">' + result
                                            .clientes[i]["RazonSocial"] + '</option>');
                                    }
                                }
                            }
                            if (tipoDocumento == 3) {
                                alert(
                                    "Este documento será no contable para fines internos, se recomienda luego canjear por boleta o factura"
                                );
                                $('#ordenCompraActivo').hide();
                            }else{
                                $('#ordenCompraActivo').show();
                            }


                            if (tipoMoneda == 1) {
                                var totalDetraccion = total;
                            } else {
                                var valorCambioVentas = $("#valorCambioVentas").val();
                                var totalDetraccion = parseFloat(total * valorCambioVentas);
                            }

                            manejarBotones(totalDetraccion, tipoDocumento, tipoVenta, banderaServicio);

                            /*if (totalDetraccion >= 700 && tipoDocumento == 2 && tipoVenta ==
                                1) {
                                if (banderaServicio > 0 && tipoPago == 2) {
                                    $('#detraccion').show();
                                    $('#textoDetraccion').show();
                                    $('#retencion').attr("disabled", true).prop("checked",
                                        false);
                                    $("#switchRetencion").val(0);
                                } else {
                                    $('#retencion').attr("disabled", false);
                                    $('#detraccion').hide();
                                    $('#textoDetraccion').hide();
                                }
                            } else {
                                $('#detraccion').hide();
                                $('#textoDetraccion').hide();
                                $('#retencion').attr("disabled", true);
                                $("#retencion").prop("checked", false);
                                $("#switchRetencion").val(0);
                            }*/
                        }
                        $.hideLoading();
                    }
                });
            });

            $("#clientes").on('change', function() {
                var idCliente = $("#clientes").val();
                if (idCliente != 0) {
                    $('#agregarArticulo').removeAttr('disabled');
                } else {
                    $('#agregarArticulo').attr('disabled', 'disabled');
                }
            });

            $("#tipoPago").on('change', function() {
                var tipo = $("#tipoPago").val();
                var tipoDocumento = $("#selectTipoComp").val();
                var tipoMoneda = $("#tipoMoneda").val();
                var tipoVenta = $('#tipoVenta').val();
                if (tipo == "1") {
                    $('#plazoCredito').hide();
                    //$('#interes').hide();
                    $('#efectivo').show();
                    $('#vuelto').show();
                    $('#tarjeta').show();
                    $('#cuentaCorriente').show();
                    $('#detraccion').hide();
                    $('#textoDetraccion').hide();

                    /*if (tipoMoneda == 1) {
                        if (total >= 700 && parseFloat(tipoDocumento) == 2 && tipoVenta == 1) {
                            $('#retencion').attr("disabled", false);
                        } else {
                            $('#retencion').attr("disabled", true);
                            $("#retencion").prop("checked", false);
                            $("#switchRetencion").val(0);
                        }
                    } else {
                        var valorCambioVentas = $("#valorCambioVentas").val();
                        var totalDetraccion = parseFloat(total * valorCambioVentas);
                        if (totalDetraccion >= 700 && tipoDocumento == 2 && tipoVenta == 1) {
                            $('#retencion').attr("disabled", false);
                        } else {
                            $('#retencion').attr("disabled", true);
                            $("#retencion").prop("checked", false);
                            $("#switchRetencion").val(0);
                        }
                    }*/
                } else {
                    $('#plazoCredito').show();
                    //$('#interes').show();
                    $('#efectivo').hide();
                    $('#vuelto').hide();
                    $('#tarjeta').hide();
                    $('#cuentaCorriente').hide();


                    /*var tipoDocumento = $("#selectTipoComp").val();
                    var tipoMoneda = $("#tipoMoneda").val();
                    var tipoVenta = $('#tipoVenta').val();

                    if (tipoMoneda == 1) {
                        var totalDetraccion = total;
                    } else {
                        var valorCambioVentas = $("#valorCambioVentas").val();
                        var totalDetraccion = parseFloat(total * valorCambioVentas);
                    }

                    if (totalDetraccion >= 700 && tipoDocumento == 2 && tipoVenta == 1) {
                        if (banderaServicio > 0 && tipo == 2) {
                            $('#detraccion').show();
                            $('#textoDetraccion').show();
                            $('#retencion').attr("disabled", true).prop("checked", false);
                            $("#switchRetencion").val(0);
                        } else {
                            $('#retencion').attr("disabled", false);
                            $('#detraccion').hide();
                            $('#textoDetraccion').hide();
                        }
                    } else {
                        $('#detraccion').hide();
                        $('#textoDetraccion').hide();
                        $('#retencion').attr("disabled", true);
                        $("#retencion").prop("checked", false);
                        $("#switchRetencion").val(0);
                    }*/
                }
            });

            $("#cuentaBancaria").on('change', function() {
                var tipoBan = $("#cuentaBancaria").val();
                if (tipoBan == "0") {
                    $('#pagoCuenta').attr("disabled", true);
                    $('#nroOperacion').attr("disabled", true);
                    $('#date').attr("disabled", true);
                    $('#pagoCuenta').val('0');
                } else {
                    $('#pagoCuenta').attr("disabled", false);
                    $('#nroOperacion').attr("disabled", false);
                    $('#date').attr("disabled", false);
                }
            });

            $("#facturarCliente").click(function() {
                if ($("#facturarCliente").is(':checked')) {
                    $('#clientes option').remove();
                    $('#clientes').append('<option value="0">-</option>');
                } else {
                    var seguro = <?php echo json_encode($seguro); ?>;
                    $('#clientes option').remove();
                    $('#clientes').append('<option value="1">' + seguro + '</option>');
                }
            });

            $("#retencion").click(function() {
                if ($(this).is(':checked')) {
                    $("#switchRetencion").val(1);
                    $('#ventaDetraccion').attr("disabled", true);
                    alert(
                        "Cuidado: Solo active esta opción si la Empresa receptora de la factura es: Agente de Retención de IGV"
                    );
                } else {
                    $('#ventaDetraccion').attr("disabled", false);
                    $("#switchRetencion").val(0);

                    $('#pagoEfec').attr("disabled", false).val("");
                    $('#pagoTarjeta').attr("disabled", false).val("");
                    $('#pagoCuenta').attr("disabled", false).val("");
                }
            });

            $("#ventaDetraccion").click(function() {
                if ($(this).is(':checked')) {
                    $('#detraccion').show();
                    $('#switchDetraccion').val(1);
                    $('#retencion').attr("disabled", true);
                    $('#medioPago option[value="3"]').prop('selected', true);   
                    verificarMedioPago();   
                } else {
                    $('#detraccion').hide();
                    $("#switchDetraccion").val(0);
                    $('#retencion').attr("disabled", false);

                    $('#pagoEfec').attr("disabled", false).val("");
                    $('#pagoTarjeta').attr("disabled", false).val("");
                    $('#pagoCuenta').attr("disabled", true).val("");
                    $('#cuentaBancaria').attr("disabled", false);

                    $('#cuentaCorriente').removeClass("medioPago-seleccionado");
                    $('#fondoEfectivo').removeClass("medioPago-seleccionado");
                    $('#tarjeta').removeClass("medioPago-seleccionado");

                    alert(
                        "La factura cumple con requisitos de Detracción obligatoria por Sunat, en caso desea generarla sin detracción bajo su responsabilidad consulte antes con su contador."
                    );
                }
            });

            $("#medioPago").on('change', function() {
                verificarMedioPago();
            });
        });
    </script>

    <script>
        function agregarProducto(id) {
            var bandStock = -1;

            var descripcion = $('#p1-' + id).text();
            var unidadMedida = $('#p3-' + id).val();
            var precio = $('#p2-' + id).text();
            var cantidad = $('#p4-' + id).val();
            var descuento = $('#p5-' + id).val();
            var costo = $('#p6-' + id).val();
            var stock = $('#p7-' + id).val();
            var idUnidadMedida = $('#IdUnidadMedida-' + id).val();
            var tipoVenta = $('#tipoVenta').val();

            var amortizacion = <?php echo json_encode($amortizaciones); ?>;

            if (arrayIds.includes(id) == true) {
                alert("Producto ya agregado, por favor modificar la cantidad si desea agregar más");
                return 0;
            } else {

                for (var k = 0; k < jsonStock.length; k++) {
                    if (jsonStock[k]["IdProducto"] == id) {
                        bandStock = k;
                    }
                }

                if (tipoVenta == 2) {
                    precio = parseFloat(precio / 1.18);
                }

                if (bandStock != -1) // si lo encontro
                {
                    jsonStock[bandStock].StockInicial = parseFloat(jsonStock[bandStock].StockInicial) - 1;
                    jsonStock[bandStock].Stock = parseFloat(jsonStock[bandStock].Stock) + 1;
                    if (parseFloat(stock) < parseFloat(jsonStock[bandStock].Stock)) {
                        jsonStock[bandStock].Stock = parseFloat(jsonStock[bandStock].Stock) - 1;
                        parseFloat(jsonStock[bandStock].StockInicial) + 1;
                        alert("Insuficiente stock de este producto");
                    } else {
                        productoEnTabla(id, descripcion, null, unidadMedida, precio, cantidad, descuento, costo, stock,
                            idUnidadMedida, tipoVenta, 1, parseFloat(amortizacion));
                    }

                } else {
                    datosProductos = new Array();
                    datosProductos.IdProducto = id;
                    datosProductos.StockInicial = parseFloat(stock) - 1;
                    datosProductos.Stock = 1;
                    jsonStock.push(datosProductos);
                    productoEnTabla(id, descripcion, null, unidadMedida, precio, cantidad, descuento, costo, stock,
                        idUnidadMedida, tipoVenta, 1, parseFloat(amortizacion));

                }
            }
        }

        function productoEnTabla(id, descripcion, detalle, unidadMedida, precio, cantidad, descuento, costo, stock,
            idUnidadMedida, tipoVenta, idEstadoCotizacion, amortizacion, idTipoMoneda) {
            var _precio = parseFloat(precio * cantidad);
            if (parseFloat(descuento) >= parseFloat(_precio)) {
                alert("El descuento tiene que ser menor que el precio");
            } else {

                if (idEstadoCotizacion == 1) {
                    if (parseFloat(cantidad) > parseFloat(stock)) {
                        alert("Sobrepaso el límite de stock del artículo: " + descripcion);
                    } else {
                        productoEnTablaVerificado(id, descripcion, detalle, unidadMedida, precio, cantidad, descuento,
                            costo, stock, idUnidadMedida, tipoVenta, idEstadoCotizacion, amortizacion, idTipoMoneda);
                    }
                } else {
                    productoEnTablaVerificado(id, descripcion, detalle, unidadMedida, precio, cantidad, descuento, costo,
                        stock, idUnidadMedida, tipoVenta, idEstadoCotizacion, amortizacion, idTipoMoneda);
                }
            }
        }

        function productoEnTablaVerificado(id, descripcion, detalle, unidadMedida, precio, cantidad, descuento, costo,
            stock, idUnidadMedida, tipoVenta, idEstadoCotizacion, amortizacion, idTipoMoneda) {
            $('#total').val('');
            $('#exonerada').val('');

            if (idUnidadMedida == 1) {
                step = '';
                bandInput = 'false';
            } else {
                step = '0.05';
                bandInput = 'true';
            }

            if (detalle == null) {
                detalle = '';
            }

            if (idEstadoCotizacion == 1 || idEstadoCotizacion == 5) {
                readonly = '';
            } else {
                readonly = 'readonly';
            }

            var importe = parseFloat(parseFloat(precio) * parseFloat(cantidad));
            var importeFinal = parseFloat(importe) - parseFloat(descuento);
            var ganancia = parseFloat(importe) - parseFloat(parseFloat(costo) * parseFloat(cantidad)) - parseFloat(
                descuento);
            var t = $('#tablaAgregado');
            var fila = '<tr id="row' + iden + '"><td><input id="pro' + iden +
                '" name="Codigo[]" readonly type="text" value="PRO-' + id + '" style="width:80px">' +
                '</td><td id="descrip' + iden + '">' + descripcion +
                '</td><td id="detalle' + iden + '"><input name="Detalle[]" type="text" value="' + detalle + '">' +
                '</td><td id="um' + iden + '">' + unidadMedida +
                '</td><td><input id="prec' + iden + '" name="Precio[]" onchange="calcular(this, ' + iden + ');" min="' +
                redondeo(precio) + '" type="number" value="' + redondeo(precio) +
                '" step="any" style="width:100px" readonly>' +
                '</td><td><input id="desc' + iden +
                '" class="inputModificarDescuento" data-tipo-moneda="' + idTipoMoneda +
                '" name="Descuento[]" onchange="calcular(this, ' + iden +
                ');" step="any" type="number" min="0" value="' + redondeo(descuento) + '" style="width:100px">' +
                '</td><td><input id="cant' + iden + '" name="Cantidad[]" onkeydown="return ' + bandInput +
                ';" onchange="calcular(this, ' + iden + ');" readonly step="' + step + '" type="number" min="1" max="' +
                stock + '" value="' + cantidad + '" style="width:60px">' +
                '</td><td><input id="imp' + iden + '" name="Importe[]" readonly type="number" value="' + redondeo(
                    importeFinal) + '" step="any"  style="width:100px">' +
                '</td><td id="gan' + iden + '" hidden>' + redondeo(ganancia) +
                '</td><td hidden><input id="unidMed' + iden + '" name="unidMed[]" value="' + idUnidadMedida + '">' +
                '</td><td hidden><input id="tipo' + iden + '" name="tipo[]" value="1">' +
                '</td><td><input id="text' + iden + '" type="hidden" name="TextUni[]" value="' + unidadMedida +
                '"/><button id="btn' + iden + '" onclick="quitar(' + iden + ',' + id +
                ', 1)" class="btn btn-success p-1"><i class="list-icon material-icons fs-16">clear</i></button>' +
                '</td>' +
                '</tr>';
            $('#tablaAgregado tr:last').after(fila);

            iden++;

            if (tipoVenta == 1) {
                $('#subtotal').val('');
                $('#igv').val('');
                var igv = parseFloat((18 / 100) + 1);
                total += parseFloat(importeFinal);
                subtotal = parseFloat(total) / parseFloat(igv);
                igvTotal = parseFloat(total) - parseFloat(subtotal);
                exonerada += parseFloat(descuento);
                pendientePago = parseFloat(total) - parseFloat(amortizacion);

                $('#subtotal').val(redondeo(subtotal));
                $('#opExonerado').val(redondeo(opExonerado));
                $('#igv').val(redondeo(igvTotal));
                $('#exonerada').val(redondeo(exonerada));
                $('#total').val(redondeo(total));
                $('#amortTotal').val(redondeo(amortizacion));
                $('#pendientePagar').val(redondeo(pendientePago));

            } else {
                $('#opExonerado').val('');
                total += parseFloat(importeFinal);
                opExonerado = parseFloat(total);
                exonerada += parseFloat(descuento);
                pendientePago = parseFloat(total) - parseFloat(amortizacion);

                $('#subtotal').val(redondeo(subtotal));
                $('#opExonerado').val(redondeo(opExonerado));
                $('#igv').val(redondeo(igvTotal));
                $('#exonerada').val(redondeo(exonerada));
                $('#total').val(redondeo(total));
                $('#amortTotal').val(redondeo(amortizacion));
                $('#pendientePagar').val(redondeo(pendientePago));
            }

            var tipoDocumento = $("#selectTipoComp").val();
            var tipoMoneda = $("#tipoMoneda").val();
            var tipoPago = $("#tipoPago").val();

            if (tipoMoneda == 1) {
                var totalDetraccion = total;
            } else {
                var valorCambioVentas = $("#valorCambioVentas").val();
                var totalDetraccion = parseFloat(total * valorCambioVentas);
            }


            /*if (totalDetraccion >= 700 && tipoDocumento == 2 && tipoVenta == 1) {
                if (banderaServicio > 0 && tipoPago == 2) {
                    $('#detraccion').show();
                    $('#textoDetraccion').show();
                    $('#retencion').attr("disabled", true).prop("checked", false);
                    $("#switchRetencion").val(0);
                } else {
                    $('#retencion').attr("disabled", false);
                    $('#detraccion').hide();
                    $('#textoDetraccion').hide();
                }
            } else {
                $('#detraccion').hide();
                $('#textoDetraccion').hide();
                $('#retencion').attr("disabled", true);
                $("#retencion").prop("checked", false);
                $("#switchRetencion").val(0);
            }*/

            arrayIds.push(parseInt(id));
        }

        function agregarServicio(id) {
            if (arrayIds.includes(id) == true) {
                alert("Servicio ya agregado, por favor de modificar la cantidad si desea agregar más");
            } else {
                var descripcion = $('#s1-' + id).text();
                var unidadMedida = 'ZZ';
                var precio = $('#s2-' + id).text();
                var cantidad = $('#s5-' + id).val();
                var descuento = $('#s3-' + id).val();
                var costo = $('#s4-' + id).val();
                var tipoVenta = $('#tipoVenta').val();
                var amortizacion = <?php echo json_encode($amortizaciones); ?>;

                if (tipoVenta == 2) {
                    precio = parseFloat(precio / 1.18);
                }
                servicioEnTabla(id, descripcion, null, unidadMedida, precio, cantidad, descuento, costo, tipoVenta, 1,
                    parseFloat(amortizacion), null);
            }
        }

        function servicioEnTabla(id, descripcion, detalle, unidadMedida, precio, cantidad, descuento, costo, tipoVenta,
            $idEstadoCotizacion, amortizacion, etiqueta, idTipoMoneda) {
            var _precio = parseFloat(precio * cantidad);
            if (parseFloat(descuento) >= parseFloat(_precio)) {
                alert("El descuento tiene que ser menor que el precio");
            } else {
                $('#total').val('');
                $('#exonerada').val('');
                banderaServicio = banderaServicio + 1;
                var importe = parseFloat(parseFloat(precio) * parseFloat(cantidad));
                var importeFinal = parseFloat(importe) - parseFloat(descuento);
                var ganancia = parseFloat(importe) - parseFloat(parseFloat(costo) * parseFloat(cantidad)) - parseFloat(
                    descuento);
                var t = $('#tablaAgregado');

                if (detalle == null) {
                    detalle = '';
                }
                // Nuevo codigo
                if (etiqueta == "PaquetePromocional") {
                    backgroundColor = 'bg-celeste ';
                    dato = 'PAQ';
                    readOnly = 'readonly';
                    boton = '<button id="btn' + iden + '" onclick="verDetallePaquetePromocional(' + id +
                        ')" class="btn btn-primary ml-1 p-1"><i class="list-icon material-icons fs-16">visibility</i></button>'
                } else {
                    backgroundColor = 'bg-none';
                    dato = 'SER';
                    boton = '';
                    readOnly = '';
                }
                // Fin
                //console.log(etiqueta);

                // $('#tablaAgregado tr:last').after('<tr id="row' + iden + '"><td><input id="pro' + iden +
                //     '" name="Codigo[]" readonly type="text" value="SER-' + id + '" style="width:80px">' +
                //     '</td><td id="descrip' + iden + '">' + descripcion +
                //     '</td><td id="detalle' + iden + '"><input name="Detalle[]" type="text" value="' + detalle + '">' +
                //     '</td><td id="um' + iden + '">' + unidadMedida +
                //     '</td><td><input id="prec' + iden + '" name="Precio[]" onchange="calcular(this, ' + iden +
                //     ', 4);" step="any" readonly type="number" value="' + redondeo(precio) + '" style="width:100px">' +
                //     '</td><td><input id="desc' + iden + '" name="Descuento[]" step="any" onchange="calcular(this, ' +
                //     iden + ');" type="number" min="0" value="' + redondeo(descuento) + '" style="width:100px">' +
                //     '</td><td><input id="cant' + iden +
                //     '" name="Cantidad[]" step="any" onkeydown="return false;" onchange="calcular(this, ' + iden +
                //     ');" readonly type="number" min="1" value="' + cantidad + '" style="width:100px">' +
                //     '</td><td><input id="imp' + iden + '" name="Importe[]" step="any" readonly type="number" value="' +
                //     redondeo(importeFinal) + '" style="width:100px">' +
                //     '</td><td id="gan' + iden + '" hidden>' + redondeo(ganancia) +
                //     '</td><td hidden><input id="unidMed' + iden + '" name="unidMed[]" value="11">' +
                //     '</td><td hidden><input id="tipo' + iden + '" name="tipo[]" value="4">' +
                //     '</td><td><input id="text' + iden + '" type="hidden" name="TextUni[]" value="' + unidadMedida +
                //     '"/><button id="btn' + iden + '" onclick="quitar(' + iden + ',' + id +
                //     ', 2)" class="btn btn-success"><i class="list-icon material-icons fs-16">clear</i></button>' +
                //     '</td>' +
                //     '</tr>');

                $('#tablaAgregado tr:last').after('<tr class="' + backgroundColor + '" id="row' + iden +
                    '"><td><input id="pro' + iden + '" name="Codigo[]" readonly type="text" value="' + dato + '-' + id +
                    '" style="width:80px">' +
                    '</td><td id="descrip' + iden + '">' + descripcion +
                    '</td><td id="detalle' + iden + '"><input name="Detalle[]" type="text" value="' + detalle + '">' +
                    '</td><td id="um' + iden + '">' + unidadMedida +
                    '</td><td><input id="prec' + iden + '" name="Precio[]" onchange="calcular(this, ' + iden +
                    ', 4);" step="any" readonly type="number" value="' + redondeo(precio) + '" style="width:100px">' +
                    '</td><td><input id="desc' + iden + '" class="inputModificarDescuento" data-tipo-moneda="' +
                    idTipoMoneda +
                    '" name="Descuento[]" step="any" onchange="calcular(this, ' +
                    iden +
                    ');" type="number" min="0" value="' + redondeo(descuento) + '" style="width:100px">' +
                    '</td><td><input id="cant' + iden +
                    '" name="Cantidad[]" step="any" onkeydown="return false;" onchange="calcular(this, ' + iden +
                    ');" readonly type="number" min="1" value="' + cantidad + '" style="width:60px">' +
                    '</td><td><input id="imp' + iden + '" name="Importe[]" step="any" readonly type="number" value="' +
                    redondeo(importeFinal) + '" style="width:100px">' +
                    '</td><td id="gan' + iden + '" hidden>' + redondeo(ganancia) +
                    '</td><td hidden><input id="unidMed' + iden + '" name="unidMed[]" value="11">' +
                    '</td><td hidden><input id="tipo' + iden + '" name="tipo[]" value="4">' +
                    '</td><td><input id="text' + iden + '" type="hidden" name="TextUni[]" value="' + unidadMedida +
                    '"/><button id="btn' + iden + '" onclick="quitar(' + iden + ',' + id +
                    ', 2)" class="btn btn-success p-1"><i class="list-icon material-icons fs-16">clear</i></button>' +
                    boton +
                    '</td>' +
                    '</tr>');

                iden++;

                if (tipoVenta == 1) {
                    $('#subtotal').val('');
                    $('#igv').val('');
                    var igv = parseFloat((18 / 100) + 1);
                    total += parseFloat(importeFinal);
                    subtotal = parseFloat(total) / parseFloat(igv);
                    igvTotal = parseFloat(total) - parseFloat(subtotal);
                    exonerada += parseFloat(descuento);
                    pendientePago = parseFloat(total) - parseFloat(amortizacion);

                    $('#subtotal').val(redondeo(subtotal));
                    $('#opExonerado').val(redondeo(opExonerado));
                    $('#igv').val(redondeo(igvTotal));
                    $('#exonerada').val(redondeo(exonerada));
                    $('#total').val(redondeo(total));
                    $('#amortTotal').val(redondeo(amortizacion));
                    $('#pendientePagar').val(redondeo(pendientePago));
                } else {
                    $('#opExonerado').val('');
                    total += parseFloat(importeFinal);
                    opExonerado = parseFloat(total);
                    exonerada += parseFloat(descuento);
                    pendientePago = parseFloat(total) - parseFloat(amortizacion);

                    $('#subtotal').val(redondeo(subtotal));
                    $('#opExonerado').val(redondeo(opExonerado));
                    $('#igv').val(redondeo(igvTotal));
                    $('#exonerada').val(redondeo(exonerada));
                    $('#total').val(redondeo(total));
                    $('#amortTotal').val(redondeo(amortizacion));
                    $('#pendientePagar').val(redondeo(pendientePago));

                }

                arrayIds.push(parseInt(id));
            }
        }

        function quitar(id, i, tipo) { //agregue una parametro mas el tipo
            var bandStock = 0;
            $('#total').val('');
            $('#exonerada').val('');

            var stock = $('#p7-' + i).val();

            var ide = $('#pro' + id).val();
            var tipoVenta = $('#tipoVenta').val();
            var precio = $('#prec' + id).val();
            var cantidad = $('#cant' + id).val();
            var descuento = $('#desc' + id).val();
            var importeFinal = $('#imp' + id).val();
            var ganancia = $('#gan' + id).text();
            var uniMed = $('#unidMed' + id).val();
            var amortizacion = <?php echo json_encode($amortizaciones); ?>;
            var idTipoMoneda = <?php echo json_encode($idTipoMoneda); ?>;
            //var valorVentaSoles = $('#valorVentaSoles').val();
            if (uniMed == 11) {
                banderaServicio = banderaServicio - 1;
            }

            for (var j = 0; j < jsonStock.length; j++) {
                if (jsonStock[j]["IdProducto"] == i) {
                    bandStock = j;
                }
            }
            //var newCodigo = ide.substring(4);
            if (tipo == 1) {
                jsonStock[bandStock].StockInicial = parseFloat(jsonStock[bandStock].StockInicial) + parseFloat(cantidad);
                jsonStock[bandStock].Stock = parseFloat(jsonStock[bandStock].Stock) - parseFloat(cantidad);
            }

            if (tipoVenta == 1) {
                $('#subtotal').val('');
                $('#igv').val('');

                var igv = parseFloat((18 / 100) + 1);
                total -= parseFloat(importeFinal);
                subtotal = parseFloat(total) / parseFloat(igv);
                igvTotal = parseFloat(total) - parseFloat(subtotal);
                exonerada -= parseFloat(descuento);
                pendientePago = parseFloat(total) - parseFloat(amortizacion);

                $('#subtotal').val(redondeo(subtotal));
                $('#opExonerado').val(redondeo(opExonerado));
                $('#igv').val(redondeo(igvTotal));
                $('#exonerada').val(redondeo(exonerada));
                $('#total').val(redondeo(total));
                $('#amortTotal').val(redondeo(amortizacion));
                $('#pendientePagar').val(redondeo(pendientePago));
            } else {
                $('#opExonerado').val('');
                total -= parseFloat(importeFinal);
                opExonerado = parseFloat(total);
                exonerada -= parseFloat(descuento);
                pendientePago = parseFloat(total) - parseFloat(amortizacion);

                $('#subtotal').val(redondeo(subtotal));
                $('#opExonerado').val(redondeo(opExonerado));
                $('#igv').val(redondeo(igvTotal));
                $('#exonerada').val(redondeo(exonerada));
                $('#total').val(redondeo(total));
                $('#amortTotal').val(redondeo(amortizacion));
                $('#pendientePagar').val(redondeo(pendientePago));
            }

            if (parseFloat(amortizacion) > parseFloat(total)) {
                $('#btnGenerar').attr("disabled", true);
            }

            $('#row' + id).remove();
            $('#' + id).remove();

            /*if(valorVentaSoles == 1 && idTipoMoneda == 2){
                $('#textoMensaje').show();
                $('#textoMensaje').text("Atención: Esta cotización sera vendido en soles y al quitar un item generará una venta parcial, las ventas parciales posteriores deberán ser también vendidos en soles obligatoriamente con el mismo tipo de cambio de esta venta");
            }*/

            var tipoDocumento = $("#selectTipoComp").val();
            var tipoPago = $("#tipoPago").val();
            var tipoMoneda = $("#tipoMoneda").val();

            if (tipoMoneda == 1) {
                var totalDetraccion = total;
            } else {
                var valorCambioVentas = $("#valorCambioVentas").val();
                var totalDetraccion = parseFloat(total * valorCambioVentas);
            }

            manejarBotones(totalDetraccion, tipoDocumento, tipoVenta, banderaServicio);

            /*if (totalDetraccion >= 700 && tipoDocumento == 2 && tipoVenta == 1) {
                if (banderaServicio > 0 && tipoPago == 2) {
                    $('#detraccion').show();
                    $('#textoDetraccion').show();
                    $('#retencion').attr("disabled", true).prop("checked", false);
                    $("#switchRetencion").val(0);
                } else {
                    $('#retencion').attr("disabled", false);
                    $('#detraccion').hide();
                    $('#textoDetraccion').hide();
                }
            } else {
                $('#detraccion').hide();
                $('#textoDetraccion').hide();
                $('#retencion').attr("disabled", true);
                $("#retencion").prop("checked", false);
                $("#switchRetencion").val(0);
            }*/

            /////////////////////////////////

            var index = arrayIds.indexOf(i);
            if (index > -1) {
                arrayIds.splice(index, 1);
            }

        }

        function calcular(idRow, id) {

            var bandStock = -1;
            var banderita = -1;
            var row = idRow.parentNode.parentNode;
            var codigo = row.cells[0].getElementsByTagName('input')[0].value;
            var idPro = codigo.substring(4);
            var precio = row.cells[4].getElementsByTagName('input')[0].value;
            var descuento = row.cells[5].getElementsByTagName('input')[0].value;
            var cantidad = row.cells[6].getElementsByTagName('input')[0].value;

            if (parseFloat(descuento) < 0 || parseFloat(precio) < 0) {
                alert('El Descuento y/o Precio no puede ser negativo');
                descuento = 0;
                $("#desc" + id).val(descuento);
            }
            if (parseFloat(cantidad) <= 0) {
                alert('La cantidad no puede ser negativo o cero');
                cantidad = 1;
                $("#cant" + id).val(cantidad);
            }

            var ganancia = $('#gan' + id).text();
            var tipoVenta = $('#tipoVenta').val();
            var porcentaje;

            var t = 0;
            var cantidad2 = 0;
            var filasT = document.getElementById('tablaAgregado').getElementsByTagName('tr');
            //var cantidadTipo = $('#cantidadTipoUnidad-'+idPro).val();

            var stock = $('#cant' + id).attr('max');
            var descuentoMax = parseFloat(precio) * parseFloat(cantidad);

            if (parseFloat(descuentoMax) >= parseFloat(descuento)) {
                //var stock = $('#p7-'+idPro).val();
                for (var i = 0; i < jsonStock.length; i++) {
                    if (jsonStock[i]["IdProducto"] == idPro) {
                        banderita = i;
                    }
                }

                var impTotal = parseFloat((parseFloat(precio) * parseFloat(cantidad)) - parseFloat(descuento));
                resultadoCalculo(id, codigo, precio, descuento, cantidad, impTotal, ganancia, banderita, stock, tipoVenta);

            } else {
                descuentoMax = (parseFloat(precio) * parseFloat(cantidad)) - parseFloat(0.10);
                $('#desc' + id).val(redondeo(descuentoMax));
                $('#imp' + id).val(0.10);
                var impTotal = parseFloat(0.10);
                resultadoCalculo(id, codigo, precio, descuento, cantidad, impTotal, ganancia, banderita, stock, tipoVenta);
            }


        }

        function resultadoCalculo(id, codigo, precio, descuento, cantidad, impTotal, ganancia, banderita, stock,
            tipoVenta) {
            var impAnterior = $('#imp' + id).val();
            $('#imp' + id).val(redondeo(impTotal));
            $('#prec' + id).val(redondeo(precio));

            var filas = $("#tablaAgregado").find("tr");
            var sumTotal = 0;
            var sumDescuento = 0;
            var sumTotalGravada = 0;
            var sumTotalExonerada = 0;
            var amortizacion = <?php echo json_encode($amortizaciones); ?>;

            if (banderita != -1) {
                jsonStock[banderita]["Stock"] = 0;
                jsonStock[banderita]["StockInicial"] = parseFloat(stock);

                for (i = 1; i < filas.length; i++) { //Recorre las filas 1 a 1
                    var celdas = $(filas[i]).find("td"); //devolverá las celdas de una fila
                    _codigo = $($(celdas[0]).children("input")[0]).val();
                    _descuento = $($(celdas[5]).children("input")[0]).val();
                    _total = $($(celdas[7]).children("input")[0]).val();
                    _cantidad = $($(celdas[6]).children("input")[0]).val();
                    if (tipoVenta == 1) {
                        sumTotalGravada += parseFloat(_total);
                    } else {
                        sumTotalExonerada += parseFloat(_total);
                    }
                    sumDescuento += parseFloat(_descuento);

                    if (_codigo == codigo) {
                        jsonStock[banderita]["Stock"] += parseFloat(_cantidad);
                        jsonStock[banderita]["StockInicial"] -= parseFloat(_cantidad);
                    }

                }

                if (jsonStock[banderita]["Stock"] <= parseFloat(stock)) {
                    for (var i = 0; i < array.length; i++) {
                        if (array[i]["Row"] == id) {
                            array[i]["Cantidad"] = parseFloat(cantidad);
                            array[i]["Descuento"] = parseFloat(descuento);
                            array[i]["Ganancia"] = parseFloat(parseFloat(ganancia) * parseFloat(cantidad)) - parseFloat(
                                descuento);
                            $('#ganancia' + id).val(parseFloat(parseFloat(ganancia) * parseFloat(cantidad)) - parseFloat(
                                descuento));
                        }
                    }
                    sumTotal += parseFloat(sumTotalGravada) + parseFloat(sumTotalExonerada);

                    var igv = parseFloat((18 / 100) + 1);
                    var _subtotal = parseFloat(sumTotalGravada) / parseFloat(igv);
                    var _igvTotal = parseFloat(sumTotalGravada) - parseFloat(_subtotal);

                    total = sumTotal;
                    subtotal = _subtotal;
                    opExonerado = sumTotalExonerada;
                    igvTotal = _igvTotal;
                    exonerada = sumDescuento;

                    $('#subtotal').val(redondeo(subtotal));
                    $('#opExonerado').val(redondeo(opExonerado));
                    $('#exonerada').val(redondeo(sumDescuento));
                    $('#igv').val(redondeo(igvTotal));
                    $('#total').val(redondeo(sumTotal));

                    var pago = $('#pagoEfec').val();

                    if ($.isNumeric(pago)) {
                        var vuelto = parseFloat(pago) - (parseFloat(total) - parseFloat(amortizacion));
                        $('#vueltoEfec').val(redondeo(vuelto));
                    }
                } else {
                    jsonStock[banderita].Stock = parseFloat(jsonStock[banderita].Stock, 10) - 1;
                    jsonStock[banderita].StockInicial = parseFloat(jsonStock[banderita].StockInicial, 10) + 1;
                    $('#cant' + id).val(parseFloat(cantidad) - 1);
                    $('#imp' + id).val(redondeo(impAnterior));

                    alert("Sobrepaso el límite de este artículo en stock");
                }

            } else {

                for (i = 1; i < filas.length; i++) { //Recorre las filas 1 a 1
                    var celdas = $(filas[i]).find("td"); //devolverá las celdas de una fila
                    _codigo = $($(celdas[0]).children("input")[0]).val();
                    _descuento = $($(celdas[5]).children("input")[0]).val();
                    _total = $($(celdas[7]).children("input")[0]).val();
                    _cantidad = $($(celdas[6]).children("input")[0]).val();
                    if (tipoVenta == 1) {
                        sumTotalGravada += parseFloat(_total);
                    } else {
                        sumTotalExonerada += parseFloat(_total);
                    }
                    sumDescuento += parseFloat(_descuento);
                }


                for (var i = 0; i < array.length; i++) {
                    if (array[i]["Row"] == id) {
                        array[i]["Cantidad"] = parseFloat(cantidad);
                        array[i]["Descuento"] = parseFloat(descuento);
                        array[i]["Ganancia"] = parseFloat(parseFloat(ganancia) * parseFloat(cantidad)) - parseFloat(
                            descuento);
                        $('#ganancia' + id).val(parseFloat(parseFloat(ganancia) * parseFloat(cantidad)) - parseFloat(
                            descuento));
                    }
                }
                sumTotal += parseFloat(sumTotalGravada) + parseFloat(sumTotalExonerada);

                var igv = parseFloat((18 / 100) + 1);
                var _subtotal = parseFloat(sumTotalGravada) / parseFloat(igv);
                var _igvTotal = parseFloat(sumTotalGravada) - parseFloat(_subtotal);

                total = sumTotal;
                subtotal = _subtotal;
                opExonerado = sumTotalExonerada;
                igvTotal = _igvTotal;
                exonerada = sumDescuento;

                $('#subtotal').val(redondeo(subtotal));
                $('#opExonerado').val(redondeo(opExonerado));
                $('#exonerada').val(redondeo(sumDescuento));
                $('#igv').val(redondeo(_igvTotal));
                $('#total').val(redondeo(sumTotal));

                var pago = $('#pagoEfec').val();

                if ($.isNumeric(pago)) {
                    var vuelto = parseFloat(pago) - (parseFloat(total) - parseFloat(amortizacion));
                    $('#vueltoEfec').val(redondeo(vuelto));
                }
            }

            var tipoDocumento = $("#selectTipoComp").val();
            var tipoMoneda = $("#tipoMoneda").val();

            if (tipoMoneda == 1) {
                var totalDetraccion = total;
            } else {
                var valorCambioVentas = $("#valorCambioVentas").val();
                var totalDetraccion = parseFloat(total * valorCambioVentas);
            }

            pendientePago = parseFloat(total) - parseFloat(amortizacion);
            if(parseFloat(total) <= parseFloat(amortizacion)){
                alert("El total tiene que ser mayor a la amortización realizada");
            }
            $('#pendientePagar').val(redondeo(pendientePago));
            manejarBotones(totalDetraccion, tipoDocumento, tipoVenta, banderaServicio);
        }

        function bloquearValor(){
            valor = $("#valorDetraccion").val();
            if(valor < 3 || valor > 12){
                alert("El porcentaje de detracción tiene que ser entre el 3 o 12 por ciento");
                $("#valorDetraccion").val(12);
            }
        }

        function manejarBotones(totalDetraccion, tipoDocumento, tipoVenta, banderaServicio){
            if (totalDetraccion >= 700 && tipoDocumento == 2 && tipoVenta == 1) {
                    if (banderaServicio > 0) {
                        $('#detraccion').show();
                        $('#retencion').attr("disabled", true).prop("checked", false);
                        $("#switchRetencion").val(0);
                        $('#ventaDetraccion').attr("disabled", false).prop("checked", true);
                        $('#switchDetraccion').val(1);
                        $('#medioPago option[value="3"]').prop('selected', true);

                        $('#pagoEfec').attr("disabled", true).val("");
                        $('#pagoTarjeta').attr("disabled", true).val("");
                        $('#pagoCuenta').attr("disabled", false).val("");

                        verificarMedioPago();
                        mostrarPopUpDetraccion();

                        
                    } else {
                        $('#retencion').attr("disabled", false).prop("checked",false);
                        $('#detraccion').hide();
                        $('#ventaDetraccion').attr("disabled", true).prop("checked", false);
                        $('#switchDetraccion').val(0);

                        $('#pagoEfec').attr("disabled", false).val("");
                        $('#pagoTarjeta').attr("disabled", false).val("");
                        $('#pagoCuenta').attr("disabled", true).val("");
                        
                        $('#cuentaCorriente').removeClass("medioPago-seleccionado");
                        $('#fondoEfectivo').removeClass("medioPago-seleccionado");
                        $('#tarjeta').removeClass("medioPago-seleccionado");
                    }
                } else {
                    $('#detraccion').hide();
                    $('#retencion').attr("disabled", true).prop("checked", false);
                    $("#switchRetencion").val(0);
                    $('#ventaDetraccion').attr("disabled", true).prop("checked", false);
                    $('#switchDetraccion').val(0);

                    $('#pagoEfec').attr("disabled", false).val("");
                    $('#pagoTarjeta').attr("disabled", false).val("");
                    $('#pagoCuenta').attr("disabled", true).val("");

                    $('#cuentaCorriente').removeClass("medioPago-seleccionado");
                    $('#fondoEfectivo').removeClass("medioPago-seleccionado");
                    $('#tarjeta').removeClass("medioPago-seleccionado");
                    
            }       
        }

        function verificarMedioPago(){
            var medio = $("#medioPago").val();
            if (medio == 1 || medio == 3) {
                $('#pagoEfec').attr("disabled", true).val("");
                $('#pagoTarjeta').attr("disabled", true).val("");
                $('#pagoCuenta').attr("disabled", false);
                $('#cuentaBancaria').attr("disabled", false);
                $('#cuentaCorriente').addClass("medioPago-seleccionado");
                $('#fondoEfectivo').removeClass("medioPago-seleccionado");
                $('#tarjeta').removeClass("medioPago-seleccionado");
            } else {
                if(medio == 5 || medio == 6){
                    $('#pagoEfec').attr("disabled", true).val("");
                    $('#pagoTarjeta').attr("disabled", false);
                    $('#tarjeta').addClass("medioPago-seleccionado");
                    $('#fondoEfectivo').removeClass("medioPago-seleccionado");
                }else{
                    $('#pagoEfec').attr("disabled", false);
                    $('#pagoTarjeta').attr("disabled", true).val("");
                    $('#fondoEfectivo').addClass("medioPago-seleccionado");
                    $('#tarjeta').removeClass("medioPago-seleccionado");
                }
                $('#pagoCuenta').attr("disabled", true).val("");
                $('#cuentaBancaria').attr("disabled", true);
                $('#cuentaBancaria option[value="0"]').prop('selected', true);
                $('#cuentaCorriente').removeClass("medioPago-seleccionado");
            }
        }

        function mostrarPopUpDetraccion(){
            const tieneModuloFacturacion = @json($modulosSelect).some(modulo => modulo.IdModulo === 4);
            const cuentaDetraccion = @json($cuentaDetraccion);
            if (tieneModuloFacturacion && cuentaDetraccion == null) {
                swal({
                        title: "Importante!",
                        text: "De acuerdo a las  nuevas normativas de SUNAT, es obligatorio registrar su cuenta de detracciones. Este requisito es fundamental para garantizar el cumplimiento de las regulaciones fiscales.",
                        icon: "warning",
                        buttons: {
                            cancel: true,
                            confirm: "Registrar cuenta en el ERP",
                        },
                        dangerMode: true,
                    })
                    .then((confirm) => {
                        if (confirm) {
                            utilidades.showLoadingOverlay();
                            $(`#formRegistrarCuentaDetracciones`).submit();
                        }
                    });
            }    
        }

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

        function enviar() {

            $.LoadingOverlay("show", {
                image: '../../assets/img/logo1.png',
                text: 'Espere un momento por favor...',
                imageAnimation: '1.5s fadein',
                textColor: "#f6851a",
                textResizeFactor: '0.3',
                textAutoResize: true
            });
            var moduloCronogramaActivo = @json($moduloCronogramaActivo);
            var idTipoComp = $("#selectTipoComp").val();
            var serie = $("#serie").val();
            var inicioComp = serie.substring(0, 1);
            if ((idTipoComp == 1 && inicioComp == 'B') || (idTipoComp == 2 && inicioComp == 'F') || (idTipoComp == 3 &&
                    inicioComp == 'T')) {
                //$('#btnGenerar').attr("disabled", true);
                //$('#btnGenerarImprimir').attr("disabled", true);
                var responsable = $("#responsable").val();
                var placa = $("#placa").val();
                var kilometro = $("#campo1").val();
                var horometro = $("#campo2").val();
                var tipoCotizacion = $("#tipoCoti").val();
                var cotizacion = $("#cotizacion").val();
                var operario = $("#operario").val();
                var trabajos = $("#trabajos").val();
                var cliente = $("#clientes").val();
                var fecha = $("#datepicker").val();
                var numero = $("#numero").val();
                var observacion = $("#observacion").val();
                var subtotal = $("#subtotal").val();
                var opExonerado = $('#opExonerado').val();
                var exonerada = $("#exonerada").val();
                var igv = $("#igv").val();
                var total = $("#total").val();
                var pendienteTotal = $('#pendientePagar').val();
                var amortizacionTotal = $('#amortTotal').val();
                var tipoPago = $("#tipoPago").val();
                var plazoCredito = $("#_plazoCredito").val();
                var interes = $("#_interes").val();
                var pagoEfectivo = $("#pagoEfec").val();
                var vueltoEfectivo = $("#vueltoEfec").val();
                var tipoTarjeta = $("#tipoTarjeta").val();
                var numTarjeta = $("#numTarjeta").val();
                var pagoTarjeta = $("#pagoTarjeta").val();
                var antiguosids = $("input[name='Id[]']").map(function() {
                    return $(this).val();
                }).get();
                var ids = $("input[name='Codigo[]']").map(function() {
                    return $(this).val().substring(4);
                }).get();
                var codigos = $("input[name='Codigo[]']").map(function() {
                    return $(this).val();
                }).get();
                var detalles = $("input[name='Detalle[]']").map(function() {
                    return $(this).val();
                }).get();
                var precios = $("input[name='Precio[]']").map(function() {
                    return $(this).val();
                }).get();
                var descuentos = $("input[name='Descuento[]']").map(function() {
                    return $(this).val();
                }).get();
                var cantidades = $("input[name='Cantidad[]']").map(function() {
                    return $(this).val();
                }).get();
                var importes = $("input[name='Importe[]']").map(function() {
                    return $(this).val();
                }).get();
                var ganancias = $("input[name='Ganancia[]']").map(function() {
                    return $(this).val();
                }).get();
                var unidMedidas = $("input[name='unidMed[]']").map(function() {
                    return $(this).val();
                }).get();
                var textMedidas = $("input[name='TextUni[]']").map(function() {
                    return $(this).val();
                }).get();
                var tipos = $("input[name='tipo[]']").map(function() {
                    return $(this).val();
                }).get();
                var tipoMoneda = $("#tipoMoneda").val();
                var montoCuenta = $("#pagoCuenta").val();
                var nroOperacion = $("#nroOperacion").val();
                var cuentaBancaria = $("#cuentaBancaria").val();
                var dateBanco = $("#date").val();
                var tipoVenta = $('#tipoVenta').val();
                var estadoCotizacion = $('#estadoCotizacion').val();
                var idSeguro = $('#idSeguro').val();
                var facturarCliente = $('#facturarCliente').val();
                //var valorCambio = $("#valorCambio").val();
                //var ventaSoles = $("#ventaSoles").val();
                //var valorVentaSoles = $('#valorVentaSoles').val();
                var bienServicio = $("#bienServicio").val();
                var medioPago = $("#medioPago").val();
                var idC = $("#IdC").val();
                var valorCambioVentas = $("#valorCambioVentas").val();
                var valorCambioCompras = $("#valorCambioCompras").val();
                var valorDetraccion = $("#valorDetraccion").val();
                var retencion = $("#switchRetencion").val();
                var detraccion = $("#switchDetraccion").val();
                var ordenCompra = $("#ordenCompra").val();

                $.ajax({
                    type: 'post',
                    url: '../convertir-venta',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "placa": placa,
                        "kilometro": kilometro,
                        "horometro": horometro,
                        "tipoCotizacion": tipoCotizacion,
                        "cotizacion": cotizacion,
                        "idTipoComp": idTipoComp,
                        "cliente": cliente,
                        "fechaEmitida": fecha,
                        "serie": serie,
                        "numero": numero,
                        "observacion": observacion,
                        "subtotal": subtotal,
                        "opExonerado": opExonerado,
                        "exonerada": exonerada,
                        "igv": igv,
                        "total": total,
                        "pendienteTotal": pendienteTotal,
                        "amortizacionTotal": amortizacionTotal,
                        "tipoPago": tipoPago,
                        "plazoCredito": plazoCredito,
                        "interes": interes,
                        "pagoEfectivo": pagoEfectivo,
                        "vueltoEfectivo": vueltoEfectivo,
                        "tipoTarjeta": tipoTarjeta,
                        "numTarjeta": numTarjeta,
                        "pagoTarjeta": pagoTarjeta,
                        "Id": ids,
                        "Codigo": codigos,
                        "Detalle": detalles,
                        "Precio": precios,
                        "Descuento": descuentos,
                        "Cantidad": cantidades,
                        "Importe": importes,
                        "Ganancia": ganancias,
                        "UnidMedida": unidMedidas,
                        "Tipo": tipos,
                        "TipoMoneda": tipoMoneda,
                        "MontoCuenta": montoCuenta,
                        "nroOperacion": nroOperacion,
                        "CuentaBancaria": cuentaBancaria,
                        "DateBanco": dateBanco,
                        "tipoVenta": tipoVenta,
                        "TextUnida": textMedidas,
                        "trabajos": trabajos,
                        "operario": operario,
                        "idEstadoCotizacion": estadoCotizacion,
                        "idSeguro": idSeguro,
                        "facturarCliente": facturarCliente,
                        "idC": idC,
                        "valorCambioVentas": valorCambioVentas,
                        "valorCambioCompras": valorCambioCompras,
                        "valorDetraccion": valorDetraccion,
                        "bienServicio": bienServicio,
                        "medioPago": medioPago,
                        "detraccion": detraccion,
                        "retencion": retencion,
                        "ordenCompra": ordenCompra,
                        "moduloCronogramaActivo": moduloCronogramaActivo
                    },
                    success: function(data) {
                        $('#btnGenerar').attr("disabled", false);
                        $('#btnGenerarImprimir').attr("disabled", false);
                        if (data[0] == 'alert1') {
                            alert(data[1]);
                            setTimeout(function() {
                                        $.LoadingOverlay("hide");
                                    }, 500);
                        } else {
                            if (data[0] == 'alert9') {
                                alert(data[1]);
                                window.location = '../../../caja/cierre-caja';
                            } else {
                                if (data[0] == 'alert12') {
                                    alert(data[1]);
                                    setTimeout(function() {
                                        $.LoadingOverlay("hide");
                                    }, 500);
                                    mostrarPopUpDetraccion();
                                } else {
                                    if (data[0] == 'error') {
                                        alert(data[1]);
                                        setTimeout(function() {
                                            $.LoadingOverlay("hide");
                                        }, 500);
                                    } else {
                                        if (data[0] == 'verificar') {
                                            alert(data[1]);
                                            alert(data[2]);
                                            window.location = 'validar-documento/' +
                                                data[2];
                                        } else {
                                            alert(data[1]);
                                            window.location =
                                                '../../ventas/comprobante-generado/' +
                                                data[2];
                                        }
                                    }
                                }
                            } 
                        }
                    }
                });
            } else {
                alert('El comprobante electrónico seleccionado no coincide con la Serie, Por favor vuelva a seleccionar');
            }
        }

        /*function guardaTipoCambio(){ 
            var tipoCambioCompras = $("#tipoCambioCompras").val();
            var tipoCambioVentas = $("#tipoCambioVentas").val();
            $.ajax({
                    type : 'post',
                    url : 'guardar-tipo-cambio',
                    data:{
                        "_token": "{{ csrf_token() }}",
                        "tipoCambioCompras":tipoCambioCompras,
                        "tipoCambioVentas":tipoCambioVentas
                    },
                    success:function(data){
                        //console.log(data);
                        if(data[0] == 'success'){
                            $("#valorCambio").val(tipoCambioVentas);
                            $("#tipoCambio").modal("hide");
                        }
                        alert(data[1]);
                    }
                });
        }*/

        $.fn.delayPasteKeyUp = function(fn, ms) {
            var timer = 0;
            $(this).on("propertychange input", function() {
                clearTimeout(timer);
                timer = setTimeout(fn, ms);
            });
        };

        $("#inputBuscarCodigoProductos").delayPasteKeyUp(function() {
            $('#content-radio').html('');
            var bandStock = -1;
            var codigo = $("#inputBuscarCodigoProductos").val();
            if (codigo != '') {
                $.ajax({
                    type: 'get',
                    url: 'buscar-codigo-producto',
                    data: {
                        'codigoBusqueda': codigo
                    },
                    success: function(data) {
                        if (data.length > 0) {
                            var id = data[0]["IdArticulo"];
                            var descripcion = data[0]["Descripcion"];
                            var unidadMedida = data[0]["UM"];
                            var precio = data[0]["Precio"];
                            var cantidad = '1';
                            var descuento = '0.0';
                            var costo = data[0]["Costo"];
                            var stock = data[0]["Stock"];
                            var select = '';
                            var idUnidadMedida = 1;
                            if (arrayIds.includes(parseInt(id)) == true) {
                                alert(
                                    "Producto ya agregado, por favor de modificar la cantidad si desea agregar más"
                                );
                                $("#inputBuscarCodigoProductos").val("");
                            } else {
                                if (stock > 0) {

                                    if (arrayIds.includes(parseInt(id)) == true) {
                                        alert(
                                            "Producto ya agregado, por favor de modificar la cantidad si desea agregar más........"
                                        );
                                    } else {
                                        for (var k = 0; k < jsonStock.length; k++) {
                                            if (jsonStock[k].codigo == id) {
                                                bandStock = k;
                                            }
                                        }

                                        if (bandStock != -1) // si lo encontro
                                        {
                                            if (jsonStock[bandStock].stockReal - 1 >= 0) {
                                                jsonStock[bandStock].stockReal = jsonStock[bandStock]
                                                    .stockReal - 1;
                                                productoEnTabla(id, descripcion, null, unidadMedida,
                                                    precio, cantidad, descuento, costo, stock,
                                                    idUnidadMedida, 1);
                                                $("#inputBuscarCodigoProductos").val("");
                                                return 0;
                                            } else {
                                                alert("Insuficiente stock de este producto");
                                                return 0;
                                            }
                                        } else {
                                            if (stock - 1 >= 0) {
                                                jsonStock.push({
                                                    "codigo": id,
                                                    "stock": stock,
                                                    "stockReal": stock - 1
                                                });
                                                productoEnTabla(id, descripcion, null, unidadMedida,
                                                    precio, cantidad, descuento, costo, stock,
                                                    idUnidadMedida, 1);
                                                $("#inputBuscarCodigoProductos").val("");
                                                return 0;
                                            } else {
                                                alert("Insuficiente stock de este producto");
                                                return 0;
                                            }
                                        }
                                    }
                                    //productoEnTabla(id, descripcion, unidadMedida, precio, cantidad, descuento, costo, stock);
                                } else {
                                    alert("Producto sin stock");
                                }
                                $("#inputBuscarCodigoProductos").val("");
                            }
                        } else {
                            $("#inputBuscarCodigoProductos").val("");
                            alert("No se encontro producto");
                        }
                    }
                }); //fin ajax
            } //fin if
        }, 500);

        function agregarArray(datos) {
            var newArray = [];
            if (array.length > 0) {
                array.push(datos);
                $('#armarArray').append('<div id="' + datos.Row + '"><input name="Id[]" value="' + datos.Id + '"></input>' +
                    '<input id="ganancia' + datos.Row + '" name="Ganancia[]" value="' + datos.Ganancia + '"></input>' +
                    '</div>');
                //}
            } else {
                array.push(datos);
                $('#armarArray').append('<div id="' + datos.Row + '"><input name="Id[]" value="' + datos.Id + '"></input>' +
                    '<input id="ganancia' + datos.Row + '" name="Ganancia[]" value="' + datos.Ganancia + '"></input>' +
                    '</div>');
            }
        }

        function vuelto() {
            var total = $('#total').val();
            var pago = $('#pagoEfec').val();
            var amortizacion = <?php echo json_encode($amortizaciones); ?>;
            if (amortizacion == null) {
                amortizacion = 0;
            }
            var pendientePagar = parseFloat(total) - parseFloat(amortizacion)
            if (parseFloat(pago) >= parseFloat(pendientePagar)) {
                var vuelto = parseFloat(pago) - parseFloat(pendientePagar);
                $('#vueltoEfec').val(redondeo(vuelto));
            } else {
                $('#vueltoEfec').val('');
            }
        }
    </script>

    <script>
        $(document).on('click', '.pagProd a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            getProductos(page);
            //location.hash = page;
            //alert(page);
        });

        function getProductos(page) {
            var textoBusqueda = $('#inputBuscarProductos').val();
            var stock = '';
            var tipoMoneda = $("#tipoMoneda").val();

            $.ajax({
                type: 'get',
                url: '../productos?page=' + page,
                data: {
                    'textoBuscar': textoBusqueda,
                    'tipoMoneda': tipoMoneda
                },
                success: function(data) {
                    var moneda;
                    if (tipoMoneda == 1) {
                        moneda = 'S/';
                    } else {
                        moneda = '$';
                    }
                    $('#listaProductos').empty();
                    for (var i = 0; i < data["data"].length; i++) {
                        if (data["data"][i]["Stock"] < 1) {
                            stock =
                                '<a class="bg-info color-white fs-12 disabled" href="javascript:void(0);">Agotado</a>';
                        } else {
                            stock = '<a class="bg-info color-white fs-12" onclick="agregarProducto(' + data[
                                    "data"][i]["IdArticulo"] + ')" href="javascript:void(0);">' +
                                '<i class="list-icon material-icons">add</i>Agregar' +
                                '</a>';
                        }


                        $('#listaProductos').append('<div class="product col-12 col-md-6">' +
                            '<div class="card-body">' +
                            '<div class="row">' +
                            '<div class="col-12">' + moneda +
                            '<span id="p2-' + data["data"][i]["IdArticulo"] +
                            '" class="product-price fs-16">' + data["data"][i]["Precio"] + '</span>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<span id="p1-' + data["data"][i]["IdArticulo"] +
                            '" class="product-title font-weight-bold fs-16">' + data["data"][i][
                                "Descripcion"
                            ] + '</span>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<span class="text-muted">' + data["data"][i]["Marca"] + '</span>' +
                            '</div>' +
                            '</div>' +
                            '<input hidden id="p3-' + data["data"][i]["IdArticulo"] + '" value="' + data[
                                "data"][i]["UM"] + '"/>' +
                            '<input hidden id="IdUnidadMedida-' + data["data"][i]["IdArticulo"] +
                            '" value="' + data["data"][i]["IdUnidadMedida"] + '"/>' +
                            '<div class="form-group mt-2" hidden>' +
                            '<label class="col-form-label-sm">Cantidad </label>' +
                            '<input id="p4-' + data["data"][i]["IdArticulo"] +
                            '" type="number" min="1" value="1" max="' + data["data"][i]["Stock"] +
                            '" class="text-center" />' +
                            '</div>' +
                            '<div class="form-group" hidden>' +
                            '<label class="col-form-label-sm">Descuento </label>' +
                            '<input id="p5-' + data["data"][i]["IdArticulo"] +
                            '" value="0.0" class="text-center" />' +
                            '</div>' +
                            '<div hidden>' +
                            '<div class="form-group col-12">' +
                            '<label class="col-form-label-sm">Costo</label>' +
                            '<input id="p6-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]
                            ["Costo"] + '" class="form-control text-center" />' +
                            '</div>' +
                            '</div>' +
                            '<div hidden>' +
                            '<div class="form-group col-12">' +
                            '<label class="col-form-label-sm">Stock</label>' +
                            '<input id="p7-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]
                            ["Stock"] + '" class="form-control text-center"/>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="card-footer">' +
                            '<div class="product-info col-12">' + stock +
                            '</div>' +
                            '</div>' +
                            '</div>');
                    }

                    $('#paginasProductos').empty();
                    var primero = '';
                    var ultimo = '';
                    var anterior = '';
                    var paginas = '';
                    var siguiente = '';
                    if (data["prev_page_url"] !== null) {
                        primero = '<li class="page-item"><a class="page-link" href="' + data["first_page_url"] +
                            '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
                        anterior = '<li class="page-item"><a class="page-link" href="' + data["prev_page_url"] +
                            '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>';
                    } else {
                        primero =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
                        anterior =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>';
                    }


                    if (data["current_page"] < 3) {
                        for (var i = 1; i <= 5 + 2; i++) {
                            if (i > 0 && i <= data["last_page"]) {
                                if (i == data["current_page"]) {
                                    paginas +=
                                        '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                        i + '</a></li>';
                                } else {
                                    paginas +=
                                        '<li class="page-item"><a class="page-link" href="productos?page=' + i +
                                        '">' + i + '</a></li>';
                                }
                            }
                        }
                    } else {
                        if (data["last_page"] > 2) {
                            if (data["current_page"] > data["last_page"] - 2) {
                                for (var i = data["current_page"] - 4; i <= data["last_page"]; i++) {
                                    if (i > 0 && i <= data["last_page"]) {
                                        if (i == data["current_page"]) {
                                            paginas +=
                                                '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                                i + '</a></li>';
                                        } else {
                                            paginas +=
                                                '<li class="page-item"><a class="page-link" href="productos?page=' +
                                                i + '">' + i + '</a></li>';
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if (data["current_page"] >= 3 && data["current_page"] <= data["last_page"] - 2) {
                        for (var i = data["current_page"] - 2; i <= data["current_page"] + 2; i++) {
                            if (i > 0 && i <= data["last_page"]) {
                                if (i == data["current_page"]) {
                                    paginas +=
                                        '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                        i + '</a></li>';
                                } else {
                                    paginas +=
                                        '<li class="page-item"><a class="page-link" href="productos?page=' + i +
                                        '">' + i + '</a></li>';
                                }
                            }
                        }
                    }


                    if (data["next_page_url"] !== null) {
                        siguiente = '<li class="page-item"><a class="page-link" href="productos?page=' + (data[
                                "current_page"] + 1) +
                            '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
                        ultimo = '<li class="page-item"><a class="page-link" href="productos?page=' + data[
                                "last_page"] +
                            '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
                    } else {
                        siguiente =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
                        ultimo =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
                    }

                    var concatenacion = primero + anterior + paginas + siguiente + ultimo;
                    $('#paginasProductos').append(concatenacion);
                }
            });
        }

        $(document).on('click', '.pagServ a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            getServicios(page);
            //location.hash = page;
            //alert(page);
        });

        function getServicios(page) {
            var textoBusqueda = $('#inputBuscarServicios').val();
            var tipoMoneda = $("#tipoMoneda").val();
            $.ajax({
                type: 'get',
                url: '../servicios?page=' + page,
                data: {
                    'textoBuscar': textoBusqueda,
                    'tipoMoneda': tipoMoneda
                },
                success: function(data) {
                    var moneda;
                    if (tipoMoneda == 1) {
                        moneda = 'S/';
                    } else {
                        moneda = '$';
                    }
                    $('#listaServicios').empty();
                    for (var i = 0; i < data["data"].length; i++) {
                        $('#listaServicios').append('<div class="product col-12 col-md-6">' +
                            '<div class="card-body">' +
                            '<div class="row">' +
                            '<div class="col-12">' + moneda +
                            '<span id="s2-' + data["data"][i]["IdArticulo"] +
                            '" class="product-price fs-16">' + data["data"][i]["Precio"] + '</span>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<span id="s1-' + data["data"][i]["IdArticulo"] +
                            '" class="product-title font-weight-bold fs-16">' + data["data"][i][
                                "Descripcion"
                            ] + '</span>' +
                            '</div>' +
                            '</div>' +
                            '<div class="form-group mt-2" hidden>' +
                            '<label class="col-form-label-sm">Cantidad </label>' +
                            '<input id="s5-' + data["data"][i]["IdArticulo"] +
                            '" type="number" min="1" value="1" class="text-center" />' +
                            '</div>' +
                            '<div class="form-group" hidden>' +
                            '<label class="col-form-label-sm">Descuento </label>' +
                            '<input id="s3-' + data["data"][i]["IdArticulo"] +
                            '" value="0.0" class="text-center" />' +
                            '</div>' +
                            '</div>' +
                            '<div class="card-footer">' +
                            '<div class="product-info col-12">' +
                            '<a class="bg-info color-white fs-12" onclick="agregarServicio(' + data["data"][
                                i
                            ]["IdArticulo"] + ')" href="javascript:void(0);">' +
                            '<i class="list-icon material-icons">add</i>Agregar' +
                            '</a>' +
                            '</div>' +
                            '</div>' +
                            '</div>');
                    }

                    $('#paginasServicios').empty();
                    var primero = '';
                    var ultimo = '';
                    var anterior = '';
                    var paginas = '';
                    var siguiente = '';
                    if (data["prev_page_url"] !== null) {
                        primero = '<li class="page-item"><a class="page-link" href="' + data["first_page_url"] +
                            '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
                        anterior = '<li class="page-item"><a class="page-link" href="' + data["prev_page_url"] +
                            '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>';
                    } else {
                        anterior =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a>';
                    }

                    if (data["current_page"] < 3) {
                        for (var i = 1; i <= 5 + 2; i++) {
                            if (i > 0 && i <= data["last_page"]) {
                                if (i == data["current_page"]) {
                                    paginas +=
                                        '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                        i + '</a></li>';
                                } else {
                                    paginas +=
                                        '<li class="page-item"><a class="page-link" href="servicios?page=' + i +
                                        '">' + i + '</a></li>';
                                }
                            }
                        }
                    } else {
                        if (data["last_page"] > 2) {
                            if (data["current_page"] > data["last_page"] - 2) {
                                for (var i = data["current_page"] - 4; i <= data["last_page"]; i++) {
                                    if (i > 0 && i <= data["last_page"]) {
                                        if (i == data["current_page"]) {
                                            paginas +=
                                                '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                                i + '</a></li>';
                                        } else {
                                            paginas +=
                                                '<li class="page-item"><a class="page-link" href="servicios?page=' +
                                                i + '">' + i + '</a></li>';
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if (data["current_page"] >= 3 && data["current_page"] <= data["last_page"] - 2) {
                        for (var i = data["current_page"] - 2; i <= data["current_page"] + 2; i++) {
                            if (i > 0 && i <= data["last_page"]) {
                                if (i == data["current_page"]) {
                                    paginas +=
                                        '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                        i + '</a></li>';
                                } else {
                                    paginas +=
                                        '<li class="page-item"><a class="page-link" href="servicios?page=' + i +
                                        '">' + i + '</a></li>';
                                }
                            }
                        }
                    }

                    if (data["next_page_url"] !== null) {
                        siguiente = '<li class="page-item"><a class="page-link" href="servicios?page=' + (data[
                                "current_page"] + 1) +
                            '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
                        ultimo = '<li class="page-item"><a class="page-link" href="servicios?page=' + data[
                                "last_page"] +
                            '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
                    } else {
                        siguiente =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
                        ultimo =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
                    }

                    var concatenacion = primero + anterior + paginas + siguiente + ultimo;
                    $('#paginasServicios').append(concatenacion);
                }
            });
        }
    </script>
    <script>
        $("#inputBuscarProductos").keyup(function() {
            var textoBusqueda = $("#inputBuscarProductos").val();
            if (textoBusqueda.length > 3 || textoBusqueda.length == 0) {
                var stock = '';
                var tipoMoneda = $("#tipoMoneda").val();

                $.ajax({
                    type: 'get',
                    url: '../buscar-productos',
                    data: {
                        'textoBuscar': textoBusqueda,
                        'tipoMoneda': tipoMoneda
                    },
                    success: function(data) {
                        $('#listaProductos').empty();
                        var moneda;
                        if (tipoMoneda == 1) {
                            moneda = 'S/';
                        } else {
                            moneda = '$';
                        }
                        //console.log(data["data"][0]["Stock"]);
                        for (var i = 0; i < data["data"].length; i++) {
                            if (data["data"][i]["Stock"] < 1) {
                                stock =
                                    '<a class="bg-info color-white fs-12 disabled" href="javascript:void(0);">Agotado</a>';
                            } else {
                                stock =
                                    '<a class="bg-info color-white fs-12" onclick="agregarProducto(' +
                                    data["data"][i]["IdArticulo"] + ')" href="javascript:void(0);">' +
                                    '<i class="list-icon material-icons">add</i>Agregar' +
                                    '</a>';
                            }


                            $('#listaProductos').append('<div class="product col-12 col-md-6">' +
                                '<div class="card-body">' +
                                '<div class="row">' +
                                '<div class="col-12">' + moneda +
                                '<span id="p2-' + data["data"][i]["IdArticulo"] +
                                '" class="product-price fs-16">' + data["data"][i]["Precio"] +
                                '</span>' +
                                '</div>' +
                                '<div class="col-12">' +
                                '<span id="p1-' + data["data"][i]["IdArticulo"] +
                                '" class="product-title font-weight-bold fs-16">' + data["data"][i][
                                    "Descripcion"
                                ] + '</span>' +
                                '</div>' +
                                '<div class="col-12">' +
                                '<span class="text-muted">' + data["data"][i]["Marca"] + '</span>' +
                                '</div>' +
                                '</div>' +
                                '<input hidden id="p3-' + data["data"][i]["IdArticulo"] +
                                '" value="' + data["data"][i]["UM"] + '"/>' +
                                '<input hidden id="IdUnidadMedida-' + data["data"][i][
                                    "IdArticulo"
                                ] + '" value="' + data["data"][i]["IdUnidadMedida"] +
                                '"/>' +
                                '<div class="form-group mt-2" hidden>' +
                                '<label class="col-form-label-sm">Cantidad </label>' +
                                '<input id="p4-' + data["data"][i]["IdArticulo"] +
                                '" type="number" min="1" value="1" max="' + data["data"][i][
                                    "Stock"
                                ] + '" class="text-center" />' +
                                '</div>' +
                                '<div class="form-group" hidden>' +
                                '<label class="col-form-label-sm">Descuento </label>' +
                                '<input id="p5-' + data["data"][i]["IdArticulo"] +
                                '" value="0.0" class="text-center" />' +
                                '</div>' +
                                '<div hidden>' +
                                '<div class="form-group col-12">' +
                                '<label class="col-form-label-sm">Costo</label>' +
                                '<input id="p6-' + data["data"][i]["IdArticulo"] + '" value="' +
                                data["data"][i]["Costo"] + '" class="form-control text-center" />' +
                                '</div>' +
                                '</div>' +
                                '<div hidden>' +
                                '<div class="form-group col-12">' +
                                '<label class="col-form-label-sm">Stock </label>' +
                                '<input id="p7-' + data["data"][i]["IdArticulo"] + '" value="' +
                                data["data"][i]["Stock"] + '" class="form-control text-center"/>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '<div class="card-footer">' +
                                '<div class="product-info col-12">' + stock +
                                '</div>' +
                                '</div>' +
                                '</div>');
                        }

                        $('#paginasProductos').empty();
                        var primero = '';
                        var ultimo = '';
                        var anterior = '';
                        var paginas = '';
                        var siguiente = '';
                        if (data["prev_page_url"] !== null) {
                            primero = '<li class="page-item"><a class="page-link" href="' + data[
                                    "first_page_url"] +
                                '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
                            anterior = '<li class="page-item"><a class="page-link" href="' + data[
                                    "prev_page_url"] +
                                '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>';
                        } else {
                            primero =
                                '<li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
                            anterior =
                                '<li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>';
                        }


                        if (data["current_page"] < 3) {
                            for (var i = 1; i <= 5 + 2; i++) {
                                if (i > 0 && i <= data["last_page"]) {
                                    if (i == data["current_page"]) {
                                        paginas +=
                                            '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                            i + '</a></li>';
                                    } else {
                                        paginas +=
                                            '<li class="page-item"><a class="page-link" href="productos?page=' +
                                            i + '">' + i + '</a></li>';
                                    }
                                }
                            }
                        } else {
                            if (data["last_page"] > 2) {
                                if (data["current_page"] > data["last_page"] - 2) {
                                    for (var i = data["current_page"] - 4; i <= data[
                                            "last_page"]; i++) {
                                        if (i > 0 && i <= data["last_page"]) {
                                            if (i == data["current_page"]) {
                                                paginas +=
                                                    '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                                    i + '</a></li>';
                                            } else {
                                                paginas +=
                                                    '<li class="page-item"><a class="page-link" href="productos?page=' +
                                                    i + '">' + i + '</a></li>';
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if (data["current_page"] >= 3 && data["current_page"] <= data["last_page"] -
                            2) {
                            for (var i = data["current_page"] - 2; i <= data["current_page"] + 2; i++) {
                                if (i > 0 && i <= data["last_page"]) {
                                    if (i == data["current_page"]) {
                                        paginas +=
                                            '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                            i + '</a></li>';
                                    } else {
                                        paginas +=
                                            '<li class="page-item"><a class="page-link" href="productos?page=' +
                                            i + '">' + i + '</a></li>';
                                    }
                                }
                            }
                        }


                        if (data["next_page_url"] !== null) {
                            siguiente =
                                '<li class="page-item"><a class="page-link" href="productos?page=' + (
                                    data["current_page"] + 1) +
                                '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
                            ultimo =
                                '<li class="page-item"><a class="page-link" href="productos?page=' +
                                data["last_page"] +
                                '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
                        } else {
                            siguiente =
                                '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
                            ultimo =
                                '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
                        }

                        var concatenacion = primero + anterior + paginas + siguiente + ultimo;
                        $('#paginasProductos').append(concatenacion);
                    }

                });
            }
        });

        $("#inputBuscarServicios").keyup(function() {
            var textoBusqueda = $("#inputBuscarServicios").val();
            if (textoBusqueda.length > 3 || textoBusqueda.length == 0) {
                var tipoMoneda = $("#tipoMoneda").val();
                $.ajax({
                    type: 'get',
                    url: '../buscar-servicios',
                    data: {
                        'textoBuscar': textoBusqueda,
                        'tipoMoneda': tipoMoneda
                    },
                    success: function(data) {
                        $('#listaServicios').empty();
                        var moneda;
                        if (tipoMoneda == 1) {
                            moneda = 'S/';
                        } else {
                            moneda = '$';
                        }
                        for (var i = 0; i < data["data"].length; i++) {
                            $('#listaServicios').append('<div class="product col-12 col-md-6">' +
                                '<div class="card-body">' +
                                '<div class="row">' +
                                '<div class="col-12">' + moneda +
                                '<span id="s2-' + data["data"][i]["IdArticulo"] +
                                '" class="product-price fs-16">' + data["data"][i]["Precio"] +
                                '</span>' +
                                '</div>' +
                                '<div class="col-12">' +
                                '<span id="s1-' + data["data"][i]["IdArticulo"] +
                                '" class="product-title font-weight-bold fs-16">' + data["data"][i][
                                    "Descripcion"
                                ] + '</span>' +
                                '</div>' +
                                '</div>' +
                                '<div class="form-group mt-2" hidden>' +
                                '<label class="col-form-label-sm">Cantidad </label>' +
                                '<input id="s5-' + data["data"][i]["IdArticulo"] +
                                '" type="number" min="1" value="1" class="text-center" />' +
                                '</div>' +
                                '<div class="form-group" hidden>' +
                                '<label class="col-form-label-sm">Descuento </label>' +
                                '<input id="s3-' + data["data"][i]["IdArticulo"] +
                                '" value="0.0" class="text-center" />' +
                                '</div>' +
                                '</div>' +
                                '<div class="card-footer">' +
                                '<div class="product-info col-12">' +
                                '<a class="bg-info color-white fs-12" onclick="agregarServicio(' +
                                data["data"][i]["IdArticulo"] + ')" href="javascript:void(0);">' +
                                '<i class="list-icon material-icons">add</i>Agregar' +
                                '</a>' +
                                '</div>' +
                                '</div>' +
                                '</div>');
                        }

                        $('#paginasServicios').empty();
                        var primero = '';
                        var ultimo = '';
                        var anterior = '';
                        var paginas = '';
                        var siguiente = '';
                        if (data["prev_page_url"] !== null) {
                            primero = '<li class="page-item"><a class="page-link" href="' + data[
                                    "first_page_url"] +
                                '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
                            anterior = '<li class="page-item"><a class="page-link" href="' + data[
                                    "prev_page_url"] +
                                '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>';
                        } else {
                            anterior =
                                '<li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a>';
                        }

                        if (data["current_page"] < 3) {
                            for (var i = 1; i <= 5 + 2; i++) {
                                if (i > 0 && i <= data["last_page"]) {
                                    if (i == data["current_page"]) {
                                        paginas +=
                                            '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                            i + '</a></li>';
                                    } else {
                                        paginas +=
                                            '<li class="page-item"><a class="page-link" href="servicios?page=' +
                                            i + '">' + i + '</a></li>';
                                    }
                                }
                            }
                        } else {
                            if (data["last_page"] > 2) {
                                if (data["current_page"] > data["last_page"] - 2) {
                                    for (var i = data["current_page"] - 4; i <= data[
                                            "last_page"]; i++) {
                                        if (i > 0 && i <= data["last_page"]) {
                                            if (i == data["current_page"]) {
                                                paginas +=
                                                    '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                                    i + '</a></li>';
                                            } else {
                                                paginas +=
                                                    '<li class="page-item"><a class="page-link" href="servicios?page=' +
                                                    i + '">' + i + '</a></li>';
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if (data["current_page"] >= 3 && data["current_page"] <= data["last_page"] -
                            2) {
                            for (var i = data["current_page"] - 2; i <= data["current_page"] + 2; i++) {
                                if (i > 0 && i <= data["last_page"]) {
                                    if (i == data["current_page"]) {
                                        paginas +=
                                            '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                            i + '</a></li>';
                                    } else {
                                        paginas +=
                                            '<li class="page-item"><a class="page-link" href="servicios?page=' +
                                            i + '">' + i + '</a></li>';
                                    }
                                }
                            }
                        }

                        if (data["next_page_url"] !== null) {
                            siguiente =
                                '<li class="page-item"><a class="page-link" href="servicios?page=' + (
                                    data["current_page"] + 1) +
                                '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
                            ultimo =
                                '<li class="page-item"><a class="page-link" href="servicios?page=' +
                                data["last_page"] +
                                '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
                        } else {
                            siguiente =
                                '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
                            ultimo =
                                '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
                        }

                        var concatenacion = primero + anterior + paginas + siguiente + ultimo;
                        $('#paginasServicios').append(concatenacion);
                    }

                });
            }
        });

        $('#lector').on('click', function() {
            var band_lector = $("#selectTipoComp").val();
            if (!isNaN(band_lector) && band_lector > 0) {
                if ($(this).is(':checked')) {
                    $('#inputBuscarCodigoProductos').show();
                } else {
                    $('#inputBuscarProductos').show();
                    $('#inputBuscarCodigoProductos').hide();
                    $('#content-radio').html(''); //esto lo agrege
                }
            } else {
                alert('Seleccione el tipo de comprobante o cliente');
                $('#lector').prop('checked', false);
            }
        });

        $(function() {
            $('#inputBuscarCodigoProductos').hide();
            /*var conversionCotiMoneda = <?php echo json_encode($conversionCotiMoneda); ?>;
            if(conversionCotiMoneda == 1){
                var tipoCambioGuardado = <?php echo json_encode($tipoCambioGuardado); ?>;
                cargarConvertirCotizacion(2, tipoCambioGuardado);
                $('#textoMensaje').text("Atención: Esta contización ya tiene ventas parciales en soles, las ventas posteriores también serán convertidos en soles, usando el tipo de cambio de la fecha que se genero la primera venta");
            }else{
                $('#textoMensaje').hide();
                cargarConvertirCotizacion(1, 0);
            }*/
            cargarConvertirCotizacion(1, 0);
        });

        function cargarConvertirCotizacion(estado, valor) {
            var items = <?php echo json_encode($items); ?>;
            var tipoVenta = <?php echo json_encode($tipoVenta); ?>;
            var idEstadoCotizacion = <?php echo json_encode($idEstadoCotizacion); ?>;
            var amortizacion = <?php echo json_encode($amortizaciones); ?>;
            var idUnidadMedida = 1;

            for (var i = 0; i < items.length; i++) {
                if (tipoVenta == 2) {
                    var precio = parseFloat(items[i]["artPrecio"]);
                } else {
                    var precio = parseFloat(items[i]["artPrecio"]);
                }

                if (parseInt(items[i]["IdTipo"], 10) == 1) {
                    datosProductos = new Array();
                    datosProductos.IdProducto = items[i]["IdArticulo"];
                    datosProductos.StockInicial = parseFloat(items[i]["Stock"]) - parseFloat(items[i]["Cantidad"]);
                    datosProductos.Stock = parseFloat(items[i]["Cantidad"]);
                    jsonStock.push(datosProductos);
                    if (estado == 1) {
                        productoEnTabla(items[i]["IdArticulo"], items[i]["Descripcion"], items[i]["Detalle"], items[i][
                                "TextUnidad"
                            ], precio, items[i]["Cantidad"], parseFloat(items[i]["Descuento"]), parseFloat(items[i][
                                "Costo"
                            ]), parseFloat(items[i]["Stock"]), items[i]["IdUnidadMedida"], tipoVenta,
                            idEstadoCotizacion, parseFloat(amortizacion), items[i]["IdTipoMoneda"]);
                    } else {
                        productoEnTabla(items[i]["IdArticulo"], items[i]["Descripcion"], items[i]["Detalle"], items[i][
                            "TextUnidad"
                        ], parseFloat(precio * parseFloat(valor)), items[i]["Cantidad"], parseFloat(items[i][
                            "Descuento"
                        ]), parseFloat(items[i]["Costo"]), parseFloat(items[i]["Stock"]), items[i][
                            "IdUnidadMedida"
                        ], tipoVenta, idEstadoCotizacion, parseFloat(amortizacion), items[i]["IdTipoMoneda"]);
                    }

                } else {
                    if (estado == 1) {
                        servicioEnTabla(items[i]["IdArticulo"], items[i]["Descripcion"], items[i]["Detalle"], items[i][
                            "TextUnidad"
                        ], precio, items[i]["Cantidad"], parseFloat(items[i]["Descuento"]), parseFloat(items[i][
                            "Costo"
                        ]), tipoVenta, idEstadoCotizacion, parseFloat(amortizacion), null, items[i]["IdTipoMoneda"]);
                    } else {
                        servicioEnTabla(items[i]["IdArticulo"], items[i]["Descripcion"], items[i]["Detalle"], items[i][
                                "TextUnidad"
                            ], parseFloat(precio * parseFloat(valor)), items[i]["Cantidad"], parseFloat(items[i][
                                "Descuento"
                            ]), parseFloat(items[i]["Costo"]), tipoVenta, idEstadoCotizacion, parseFloat(amortizacion),
                            null, items[i]["IdTipoMoneda"]);
                    }
                }
            }

            // items paquetes paquetes promocionales
            let itemsP = @json($itemsPaquetePromocional);
            for (let i = 0; i < itemsP.length; i++) {
                var precio = parseFloat(itemsP[i]["Importe"]);
                if (parseInt(itemsP[i]["EstadoPaquete"], 10) == 1) {
                    alert("Sobrepaso el límite de stock de un artículo en el paquete: " + itemsP[i]["NombrePaquete"]);
                } else {
                    servicioEnTabla(itemsP[i]["IdPaquetePromocional"], itemsP[i]["NombrePaquete"], itemsP[i][
                        "Detalle"
                    ], itemsP[i]["TextUnidad"], precio, itemsP[i]["Cantidad"], parseFloat(itemsP[i][
                        "Descuento"
                    ]), parseFloat(itemsP[i][
                        "Costo"
                    ]), tipoVenta, idEstadoCotizacion, parseFloat(amortizacion), itemsP[i]["Etiqueta"], items[i][
                        "IdTipoMoneda"
                    ]);
                }
            }
            // fin

            /*var tipoDocumento = $("#selectTipoComp").val();
            var tipoMoneda = $("#tipoMoneda").val();
            var tipoPago = $("#tipoPago").val();

            if (tipoMoneda == 1) {
                var totalDetraccion = total;
            } else {
                var valorCambioVentas = $("#valorCambioVentas").val();
                var totalDetraccion = parseFloat(total * valorCambioVentas);
            }

            if (totalDetraccion >= 700 && tipoDocumento == 2 && tipoVenta == 1) {
                if (banderaServicio > 0 && tipoPago == 2) {
                    $('#detraccion').show();
                    $('#textoDetraccion').show();
                    $('#retencion').attr("disabled", true).prop("checked", false);
                    $("#switchRetencion").val(0);
                } else {
                    $('#retencion').attr("disabled", false);
                    $('#detraccion').hide();
                    $('#textoDetraccion').hide();
                }
            } else {
                $('#detraccion').hide();
                $('#textoDetraccion').hide();
                $('#retencion').attr("disabled", true);
                $("#retencion").prop("checked", false);
                $("#switchRetencion").val(0);
            }*/

            if (parseFloat(amortizacion) > parseFloat(total)) {
                $('#btnGenerar').attr("disabled", true);
            }
        }
    </script>


    <script>
        function verDetallePaquetePromocional($idPaquete) {
            $('#tableDetalle').find('#tableDetalleBody').remove();
            $('#tableDetalle').append('<tbody id="tableDetalleBody"></tbody>')
            $('#totalPaquete').val('');
            var tipoVenta = $('#tipoVenta').val();
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
</body>

</html>
