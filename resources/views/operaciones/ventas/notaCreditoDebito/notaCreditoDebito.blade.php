<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pace.css') }}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Generar Notas de Crédito</title>
    <!-- CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet"
        type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/material-icons/material-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/mono-social-icons/monosocialiconsfont.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('assets/vendors/feather-icons/feather.css') }}" rel="stylesheet" type="text/css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css"
        rel="stylesheet" type="text/css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/css/perfect-scrollbar.min.css"
        rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">

    <link href="{{ asset('assets/css/responsive.dataTables.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/newStyles.css?v=' . rand(1, 99)) }}" rel="stylesheet" type='text/css'>
    <link href="{{ asset('assets/css/loading.min.css') }}" rel="stylesheet" type="text/css">
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
                            <h6 class="page-title-heading mr-0 mr-r-5">Generar Nota de Crédito / Débito</h6>
                        </div>
                        <!-- /.page-title-left -->
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </div>
                    @endif
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
                                    <div class="widget-body clearfix">
                                        {!! Form::open([
                                            'url' => '/operaciones/ventas/nota-credito-debito',
                                            'method' => 'POST',
                                            'files' => true,
                                            'class' => 'form-material',
                                            'id' => 'myform',
                                        ]) !!}
                                        {{ csrf_field() }}
                                        <div class="row">
                                            <div class="col-md-4" id="col-seleccioneDocumento">
                                                <select class="m-b-10 form-control docSelect"
                                                    data-placeholder="Seleccione Documento" data-toggle="select2">
                                                    <option value="0">Seleccione Comprobante</option>
                                                    @foreach ($reportesVentasAceptados as $reporteAceptado)
                                                        <option value="{{ $reporteAceptado->IdVentas }}"
                                                            {{ $idVentasConsultas ? 'selected' : '' }}>
                                                            {{ $reporteAceptado->Serie }}-{{ $reporteAceptado->Numero }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-0">
                                                <!--<a href="#" id="btnEnvio" title="Detalles"
                                                    class="btn btn-block btn-primary ripple"><i
                                                        class="list-icon material-icons fs-20">add</i></a>-->
                                                <input hidden id="idVenta" class="form-control" name="idVenta"
                                                    value="{{ $idVenta }}">
                                                <input hidden id="idTipoComprobante" class="form-control"
                                                    name="idTipoComprobante" value="{{ $idTipoComprobante }}">
                                                <input hidden id="codComprobante" class="form-control"
                                                    name="codComprobante" value="{{ $codComprobante }}">
                                                <input hidden id="tipoMoneda" class="form-control" name="tipoMoneda"
                                                    value="{{ $tipoMoneda }}">
                                                <input hidden id="tipoVenta" class="form-control" name="tipoVenta"
                                                    value="{{ $tipoVenta }}">
                                                <input hidden id="tipoPago" class="form-control" name="tipoPago"
                                                    value="{{ $idTipoPago }}">
                                                <input hidden id="plazoCredito" class="form-control"
                                                    name="plazoCredito" value="{{ $plazoCredito }}">
                                                <input hidden id="retencion" class="form-control" name="retencion"
                                                    value="{{ $retencion }}">
                                                <input hidden id="porcentajeDetraccion" class="form-control"
                                                    name="porcentajeDetraccion" value="{{ $porcentajeDetraccion }}">
                                                <input hidden id="fechaFactura" class="form-control"
                                                    name="fechaFactura" value="">
                                                <input hidden id="anticipo" class="form-control"
                                                    name="anticipo" value="">
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><label>Moneda</label></div>
                                                        </div>
                                                        <input id="moneda" type="text" class="form-control"
                                                            name="moneda" readonly>
                                                        <!--@if ($tipoMoneda == 1)
<input type="text" class="form-control" name="moneda"
                                                                readonly value="Soles">
@elseif($tipoMoneda == 2)
<input type="text" class="form-control" name="moneda"
                                                                readonly value="Dólares">
@endif-->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="input-group border-bottom">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><label>Fecha</label></div>
                                                            <input type="hidden" id="datepicker" type="date"
                                                                class="form-control datepicker" name="fechaEmitida">
                                                        </div>
                                                        <span class="mt-2 ml-3">{{ $fecha }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    @if ($deshabilitado == 1)
                                                        <select id="selectTipoComp" class="form-control" disabled
                                                            name="tipoNota">
                                                        @else
                                                            <select id="selectTipoComp" class="form-control"
                                                                name="tipoNota">
                                                    @endif
                                                    <option value="0">-</option>
                                                    <option value="1">Nota Crédito</option>
                                                    <!--<option value="2">Nota Débito</option>-->
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
                                                            placeholder="Serie" type="text" maxlength="4"
                                                            name="serie" readonly>
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
                                                    <div class="input-group">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><label>Cliente</label></div>
                                                        </div>
                                                        <input id="cliente" type="text" class="form-control"
                                                            name="cliente" readonly value="{{ $cliente }}">
                                                    </div>
                                                </div>
                                                <input id="idCliente" type="text" class="form-control"
                                                    name="idCliente" hidden value="{{ $idCliente }}">
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <select id="motivo" class="form-control" name="motivo">

                                                    </select>
                                                    <label>Motivo</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div id="montoNC" class="col-md-4">
                                                <label>Monto a modificar</label>
                                                <div class="form-group">
                                                    <input id="montoVentaNC" type="number" class="form-control"
                                                        value="{{ $importeTotal }}" name="montoNC">
                                                </div>
                                            </div>
                                            <div id="diasNC" class="col-md-4">
                                                <label>Cantidad de días desde que se emitio la factura</label>
                                                <div class="form-group">
                                                    <input id="plazoDiasNC" type="number" class="form-control"
                                                        value="{{ $plazoCredito }}" name="diasNC">
                                                </div>
                                            </div>
                                            <div id="fechaNC" class="col-md-4">
                                                <label>Nueva fecha de pago</label>
                                                <div class="form-group">
                                                    <input id="fechaPagoNC" type="date" class="form-control"
                                                        name="fechaNC" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <table id="tablaAgregado" class="table table-responsive-lg"
                                                    style="width:100%">
                                                    <thead>
                                                        <tr class="bg-primary-contrast">
                                                            <th class="text-center">Código</th>
                                                            <th>Descripción</th>
                                                            <th class="text-center">Uni/Medida</th>
                                                            <th class="text-center">Precio</th>
                                                            <th class="text-center">Descuento</th>
                                                            <th class="text-center">Cantidad</th>
                                                            <th class="text-center">Importe</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="body">
                                                        <!-- @foreach ($items as $item)
@if ($item->Gratuito == 1)
@php $backgroundColor = 'background-color: #d3d3d3'; @endphp
@else
@php $backgroundColor = 'background-color: none'; @endphp
@endif
                                                            <tr style="{{ $backgroundColor }}">
                                                                <td><input style="width: 100px" class="text-left"
                                                                        name="codigo[]" type="text" readonly
                                                                        value="{{ $item->Cod }}"></td>
                                                                <td><input class="text-left" name="descripcion[]"
                                                                        type="text"
                                                                        value="{{ $item->Descripcion }} {{ $item->Detalle }}">
                                                                </td>
                                                                <td class="text-center">{{ $item->TextUnidad }}</td>
                                                                <td><input style="width: 100px" class="text-right"
                                                                        onchange="calcular(this, {{ $item->IdVentasArticulo }});"
                                                                        name="precio[]" step="any" type="number"
                                                                        min="0"
                                                                        value="{{ $item->PrecioUnidadReal }}" readonly
                                                                        ></td>
                                                                <td><input style="width: 100px" class="text-right"
                                                                        onchange="calcular(this, {{ $item->IdVentasArticulo }});"
                                                                        name="descuento[]" step="any"
                                                                        type="number" min="0"
                                                                        value="{{ $item->Descuento }}" readonly></td>
                                                                <td><input style="width: 100px" class="text-center"
                                                                        onchange="calcular(this, {{ $item->IdVentasArticulo }});"
                                                                        name="cantidad[]" type="number"
                                                                        min="1"
                                                                        value="{{ $item->Cantidad * $item->CantidadReal }}"
                                                                        readonly></td>
                                                                <td><input style="width: 100px" class="text-right"
                                                                        id="{{ $item->IdVentasArticulo }}"
                                                                        class="text-center" name="importe[]"
                                                                        step="any" type="number" readonly
                                                                        value="{{ $item->Importe }}"></td>
                                                                <td hidden><input name="gratuitos[]"
                                                                        value="{{ $item->Gratuito }}"></td>
                                                            </tr>
@endforeach-->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-lg-8 col-md-12">
                                                <div class="form-group">
                                                    <textarea class="form-control" rows="5" name="observacion"></textarea>
                                                    <label>Observación</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-8">
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>Op Gravada:</label>
                                                    </div>
                                                    <div class="col-lg-5 col-8">
                                                        <input id="subtotal" name="subtotal" type="text" readonly
                                                            value="{{ $opGravada }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>Op Exonerada:</label>
                                                    </div>
                                                    <div class="col-lg-5 col-8">
                                                        <input id="opExonerado" name="opExonerado" type="text"
                                                            readonly value="{{ $opExonerada }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>Op Gratuita:</label>
                                                    </div>
                                                    <div class="col-lg-5 col-8">
                                                        <input id="opGratuita" name="opGratuita" type="text"
                                                            readonly value="{{ $opGratuita }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>Descuento:</label>
                                                    </div>
                                                    <div class="col-lg-5 col-8">
                                                        <input id="exonerada" name="exonerada" type="text"
                                                            readonly value="{{ $descuento }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>IGV (18%):</label>
                                                    </div>
                                                    <div class="col-lg-5 col-8">
                                                        <input id="igv" type="text" name="igv" readonly
                                                            value="{{ $igv }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>Total:</label>
                                                    </div>
                                                    <div class="col-lg-5 col-8">
                                                        <input id="total" type="text" name="total" readonly
                                                            value="{{ $importeTotal }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions btn-list mt-3">
                                            <button id="btnGenerar" class="btn btn-primary"
                                                type="submit">Generar</button>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                    <div class="modal fade bs-modal-lg-comprobantes" tabindex="-1" role="dialog"
                                        aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <table id="tabla" class="table table-responsive-lg"
                                                        style="width:100%">
                                                        <thead>
                                                            <tr class="bg-primary-contrast">
                                                                <th>Agregar</th>
                                                                <th scope="col">Fecha</th>
                                                                <th scope="col">Cliente</th>
                                                                <th scope="col">Código</th>
                                                                <th scope="col">Total</th>
                                                                <th scope="col">Estado</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($reportesVentasAceptados as $reporteAceptado)
                                                                <tr>
                                                                    <td><a href="nota-credito-debito-{{ $reporteAceptado->IdVentas }}"
                                                                            title="Detalles"><i
                                                                                class="list-icon material-icons">check</i></a>
                                                                    </td>
                                                                    <td>{{ $reporteAceptado->FechaCreacion }}</td>
                                                                    <td>{{ $reporteAceptado->Nombres }}</td>
                                                                    <td>{{ $reporteAceptado->Serie }}-{{ $reporteAceptado->Numero }}
                                                                    </td>
                                                                    <td>{{ $reporteAceptado->Total }}</td>
                                                                    <td>{{ $reporteAceptado->Estado }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
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
                                                    <h6>AVISO</h6>
                                                </div>
                                                <div class="modal-body form-material">
                                                    <div>
                                                        <label class="fs-12 negrita">Si es la primera que trabaja con
                                                            comprobantes electrónicos deje la serie y número correlativo
                                                            de las notas de crédito o débito como están por defecto,
                                                            caso contrario edite la serie y número correlativo de cada
                                                            uno según SUNAT.</label>
                                                    </div>
                                                    <div class="mt-2">
                                                        <text class="fs-11">NOTA: La serie y número correlativo ya sea
                                                            Nota de Crédito o Débito solamente se ingresara una vez.
                                                            Luego se manejara automáticamente.</text>
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
                                <!-- /.widget-bg -->
                            </div>
                            <!--<div class="title m-b-md">
                            {!! QrCode::size(300)->generate('B001|1|0001') !!}
                         </div>-->
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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/respuestaAjax/respuestaSweetAlert2.js?v=' . time()) }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.9/metisMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/perfect-scrollbar.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>-->

    <script src="{{ asset('assets/js/loadingoverlay.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.loading.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    <script>
        $(document).ready(function() {
            if ({{ $idVentasConsultas != '' }}) {
                $('.docSelect').trigger('change');
                $('#col-seleccioneDocumento').addClass('disabled-elemento');
            }
        });
    </script>
    <script type="text/javascript">
        var bandOpc = 0;
        var textUrl = '';
        var impTotal = 0;
        var subtotal = 0;
        var igvTotal = 0;
        var exonerada = 0;
        var opExonerado = 0;
        var opGratuita = 0;

        $('[data-toggle="select2"]').select2();

        $('.docSelect').change(function() {
            id = $(this).val();
            doc = $(this).select2('data')[0]["text"];
            tipo = doc.substring(0, 1);

            $('#selectTipoComp option[value=0]').prop('selected', true);
            $("#serie").val('');
            $("#numero").val('');
            $('#motivo').empty();

            if (id == 0) {
                $("#selectTipoComp").prop("disabled", true);
            } else {
                /*$.showLoading({
                    name: 'square-flip',
                });*/
                //$.LoadingOverlay("show");
                $.LoadingOverlay("show", {
                    image: '../../assets/img/logo1.png',
                    text: 'Espere un momento por favor...',
                    imageAnimation: '1.5s fadein',
                    textColor: "#f6851a",
                    textResizeFactor: '0.3',
                    textAutoResize: true
                });
                //var image = 'img/logo1.png';
                $.ajax({
                    type: 'get',
                    url: 'datos-nota-credito',
                    data: {
                        'id': id,
                        'option': tipo
                    },
                    success: function(data) {
                        if (data != null) {
                            console.log(data);
                            $('#tablaAgregado tr:gt(0)').remove();
                            $("#selectTipoComp").prop("disabled", false);

                            $("#idVenta").val(data["idVenta"]);
                            $("#idTipoComprobante").val(data["idTipoComprobante"]);
                            $("#idCliente").val(data["idCliente"]);
                            $("#codComprobante").val(data["codComprobante"]);
                            $("#tipoMoneda").val(data["tipoMoneda"]);
                            $("#tipoVenta").val(data["tipoVenta"]);
                            $("#tipoPago").val(data["idTipoPago"]);
                            $("#plazoCredito").val(data["plazoCredito"]);
                            $("#retencion").val(data["retencion"]);
                            $("#anticipo").val(data["anticipo"]);
                            $("#porcentajeDetraccion").val(data["porcentajeDetraccion"]);
                            if (data["tipoMoneda"] == 1) {
                                moneda = "Soles";
                            } else {
                                moneda = "Dolares";
                            }




                            $("#moneda").val(moneda);
                            $("#cliente").val(data["cliente"]);

                            for (var i = 0; i < data["items"].length; i++) {
                                if (data["items"][i]["Gratuito"] == 1) {
                                    backgroundColor = 'background-color: #d3d3d3';
                                } else {
                                    backgroundColor = 'background-color: none';
                                }

                                if (data["items"][i]["Detalle"] == null) {
                                    detalle = "";
                                } else {
                                    detalle = data["items"][i]["Detalle"];
                                }

                                var fila = '<tr style="' + backgroundColor + '">' +
                                    '<td><input style="width: 100px" class="text-left" name="codigo[]" type="text" readonly value="' +
                                    data["items"][i]["Cod"] + '"></td>' +
                                    '<td><input class="text-left" name="descripcion[]" type="text" value="' +
                                    data["items"][i]["Descripcion"] + ' ' + detalle + '"></td>' +
                                    '<td class="text-center">' + data["items"][i]["TextUnidad"] +
                                    '</td>' +
                                    '<td><input style="width: 100px" class="text-right" name="precio[]" step="any" type="number" min="0" value="' +
                                    data["items"][i]["PrecioUnidadReal"] + '" readonly ></td>' +
                                    '<td><input style="width: 100px" class="text-right" name="descuento[]" step="any" type="number" min="0" value="' +
                                    data["items"][i]["Descuento"] + '" readonly></td>' +
                                    '<td><input style="width: 100px" class="text-center" name="cantidad[]" type="number" min="1" value="' +
                                    data["items"][i]["Cantidad"] + '" readonly></td>' +
                                    '<td><input style="width: 100px" class="text-right" class="text-center" name="importe[]" step="any" type="number" readonly value="' +
                                    data["items"][i]["Importe"] + '"></td>' +
                                    '<td hidden><input name="gratuitos[]" value="' + data["items"][i][
                                        "Gratuito"
                                    ] + '"></td>' +
                                    '</tr>';
                                $('#tablaAgregado tr:last').after(fila);
                            }

                            impTotal = data["importeTotal"];
                            subtotal = data["subTotal"];
                            igvTotal = data["igv"];
                            exonerada = data["descuento"];
                            opExonerado = data["opExonerada"];
                            opGratuita = data["opGratuita"];

                            $("#subtotal").val(data["subTotal"]);
                            $("#opExonerado").val(data["opExonerada"]);
                            $("#opGratuita").val(data["opGratuita"]);
                            $("#exonerada").val(data["descuento"]);
                            $("#igv").val(data["igv"]);
                            $("#total").val(impTotal);

                            $("#montoVentaNC").val(impTotal);
                            $("#plazoDiasNC").val(data["plazoCredito"]);
                            $("#fechaFactura").val(data["fechaEmisionFac"]);

                            var nuevoPlazoDias = data["plazoCredito"];
                            var nuevafechaPagoNC = data["fechaEmisionFac"];
                            var _nuevaFecha = new Date(nuevafechaPagoNC);
                            _nuevaFecha.setDate(_nuevaFecha.getDate() + parseInt(nuevoPlazoDias, 10));
                            mostrarFechaPagoNC(_nuevaFecha);
                            //$("#fechaPagoNC").val(data["fechaNC"]);

                        } else {
                            respuestaInfoMensaje(null, 'No se encontraron datos');
                        }
                        //$.hideLoading();
                        setTimeout(function() {
                            $.LoadingOverlay("hide");
                        }, 500);
                    }
                });
            }
            //textUrl = tipo + '-nota-credito-debito-' + ever;
            //alert(textUrl)
            //$('#btnEnvio').prop('href', textUrl);
        });

        $('#btnEnvio').click(function(e) {
            if (bandOpc == 0) {
                e.preventDefault();
                alert("Seleccione el Comprobante..... ");
            } else {

            }
        });

        $('#btnGenerar').on('click', function() {
            var myForm = $("form#myform");
            if (myForm) {
                $(this).attr('disabled', true);
                $(myForm).submit();
            }
        });
    </script>

    <script>
        $(function() {
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
        });
    </script>

    <script>
        $(function() {
            var fechaEmisionFac = <?php echo json_encode($fechaEmisionFac); ?>;
            var plazoDias = <?php echo json_encode($plazoCredito); ?>;
            if (fechaEmisionFac != '') {
                var nuevaFecha = new Date(fechaEmisionFac);

                nuevaFecha.setDate(nuevaFecha.getDate() + plazoDias);

                mostrarFechaPagoNC(nuevaFecha);
            }
        });
    </script>
    <script type="text/javascript">
        $(function() {
            $(document).ready(function() {
                $('#tabla').DataTable({
                    responsive: true,
                    "order": [
                        [0, "desc"]
                    ],
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
        $(function() {
            var total = <?php echo json_encode(count($totalNotas)); ?>;
            var totalSucursales = <?php echo json_encode($totalSucursales); ?>;
            var inicioComprobante = <?php echo json_encode($inicioComprobante); ?>;
            $("#fechaNC").hide();
            $("#diasNC").hide();
            $("#montoNC").hide();

            if (total == 0) {
                $("#mostrarmodal").modal("show");
            }

            $("#selectTipoComp").on('change', function() {
                var comprobante = $("#codComprobante").val();
                var iniComprobante = comprobante.substring(0, 1);
                var cod = '';
                var nota = $("#selectTipoComp").val();
                var numero;
                if (nota != 0) {
                    $('#motivo option').remove();
                    var idSucursal = <?php echo json_encode($idSucursal); ?>;
                    var _numero = '';
                    if (nota == 1) {
                        if (iniComprobante == 'B') {
                            notas = <?php echo json_encode($notasCreditoB); ?>;
                            totalNotas = <?php echo json_encode(count($notasCreditoB)); ?>;
                            motivos = <?php echo json_encode($motivosCredito->whereNotIn('IdMotivo', [23])); ?>;
                            cod = 'BC';
                        } else {
                            idTipoPago = $("#tipoPago").val();
                            if (idTipoPago == 1) {
                                motivos = <?php echo json_encode($motivosCredito->whereNotIn('IdMotivo', [23])); ?>;
                            } else {
                                motivos = <?php echo json_encode($motivosCredito); ?>;
                            }
                            notas = <?php echo json_encode($notasCreditoF); ?>;
                            totalNotas = <?php echo json_encode(count($notasCreditoF)); ?>;
                            cod = 'FC';
                        }
                        if (totalNotas == 0) {
                            if (totalSucursales == 0) {
                                _numero = '01'
                            } else {
                                _numero = PadLeft(totalSucursales["Total"], 2);
                            }
                        } else {
                            _numero = notas[0]["Serie"].substring(2);

                        }
                        serie = cod + _numero
                        $("#serie").val(serie);
                        if (totalNotas == 0) {
                            document.getElementById("serie").readOnly = true;
                            document.getElementById("numero").readOnly = true;
                            /*$("#serie").css({
                                "border": "1px solid #ff1a1a"
                            });
                            $("#numero").css({
                                "border": "1px solid #ff1a1a"
                            });*/
                        } else {
                            document.getElementById("serie").readOnly = true;
                            document.getElementById("numero").readOnly = true;
                            /*$("#serie").css({
                                "border": ""
                            });
                            $("#numero").css({
                                "border": ""
                            });*/
                        }
                    }
                    if (nota == 2) {
                        var notas = <?php echo json_encode($notasDebito); ?>;
                        var totalNotas = <?php echo json_encode(count($notasDebito)); ?>;
                        var motivos = <?php echo json_encode($motivosDebito); ?>;
                        if (iniComprobante == 'B') {
                            cod = 'BD';
                        } else {
                            cod = 'FD';
                        }
                        if (totalNotas == 0) {
                            if (totalSucursales == 0) {
                                _numero = '01'
                            } else {
                                _numero = PadLeft(totalSucursales["Total"], 2);
                            }
                        } else {
                            _numero = notas[0]["Serie"].substring(2);
                        }
                        serie = cod + _numero
                        $("#serie").val(serie);
                        if (totalNotas == 0) {
                            document.getElementById("serie").readOnly = false;
                            document.getElementById("numero").readOnly = false;
                            $("#serie").css({
                                "border": "1px solid #ff1a1a"
                            });
                            $("#numero").css({
                                "border": "1px solid #ff1a1a"
                            });
                        } else {
                            document.getElementById("serie").readOnly = true;
                            document.getElementById("numero").readOnly = true;
                            $("#serie").css({
                                "border": ""
                            });
                            $("#numero").css({
                                "border": ""
                            });
                        }
                    }

                    if (totalNotas > 0) {

                        maxValue = 0;
                        tmpIndice = 0;

                        for (var k = 0; k < notas.length; k++) {
                            if (notas[k].Serie == $("#serie").val()) {
                                if (notas[k]["IdCreditoDebito"] > maxValue) {
                                    maxValue = notas[k]["IdCreditoDebito"];
                                    //tmpIndice = k;   
                                }
                            }
                        }

                        if (maxValue == 0) {
                            numero = PadLeft(1, 8);
                        } else {
                            var ultimoNumero = parseInt(notas[tmpIndice]["Numero"]) + 1;
                            numero = PadLeft(ultimoNumero, 8);
                        }
                    } else {
                        if (iniComprobante == 'B') {
                            $inicioCorrelativo = parseInt(inicioComprobante[1]["Correlativo"], 10);

                        } else {
                            $inicioCorrelativo = parseInt(inicioComprobante[0]["Correlativo"], 10);
                        }
                        numero = PadLeft($inicioCorrelativo, 8);
                    }

                    $("#numero").val(numero);
                    var anticipo = $("#anticipo").val();
                    for (var i = 0; i < motivos.length; i++) {
                        //$('#contenido').append('<option id="'+galeria[i]["IdAlbum"]+'" value="'+galeria[i]["Titulo"]+'"></option>'); 
                        if(anticipo > 2){
                            if(motivos[i]["IdMotivo"] == 1 || motivos[i]["IdMotivo"] == 6){
                                $('#motivo').append('<option value="' + motivos[i]["IdMotivo"] + '">' + motivos[i][
                                "Descripcion"
                            ] + '</option>');
                            }
                        }else{
                            $('#motivo').append('<option value="' + motivos[i]["IdMotivo"] + '">' + motivos[i][
                                "Descripcion"
                            ] + '</option>');
                        }
                    }

                } else {
                    document.getElementById("serie").readOnly = true;
                    document.getElementById("numero").readOnly = true;
                    $("#serie").css({
                        "border": ""
                    });
                    $("#numero").css({
                        "border": ""
                    });
                    $("#serie").val('');
                    $("#numero").val('');
                    $('#motivo').empty();
                }
            });

            $("#motivo").on('change', function() {

                /*$.showLoading({
                    name: 'circle-fade',
                });*/

                $.LoadingOverlay("show");

                var idMotivo = $("#motivo").val();
                var idVenta = $("#idVenta ").val();
                $.ajax({
                    type: 'get',
                    url: 'items-nota-credito',
                    data: {
                        'idVenta': idVenta
                    },
                    success: function(data) {
                        if (data != null) {
                            console.log(data);
                            $('#tablaAgregado tr:gt(0)').remove();
                            iden = 1;
                            for (var i = 0; i < data.length; i++) {
                                if (data[i]["Gratuito"] == 1) {
                                    backgroundColor = 'background-color: #d3d3d3';
                                } else {
                                    backgroundColor = 'background-color: none';
                                }

                                if (idMotivo == 23) {
                                    precio = "0.00";
                                    importe = "0.00";
                                } else {
                                    precio = data[i]["PrecioUnidadReal"];
                                    importe = data[i]["Importe"];
                                }

                                if (idMotivo == 7) {
                                    readOnlyCantidad = "";
                                    backGrounColorCantidad = ";background-color: #d6d9ff";
                                    botonEliminar = '<td><button onclick="quitar(' + iden +
                                        ')" class="btn btn-primary" style="width:40px"><i class="list-icon material-icons fs-16">clear</i></button></td>';
                                } else {
                                    readOnlyCantidad = "readonly";
                                    backGrounColorCantidad = "";
                                    botonEliminar = "";
                                }

                                if (data[i]["Detalle"] == null) {
                                    detalle = '';
                                } else {
                                    detalle = data[i]["Detalle"];
                                }

                                var fila = '<tr id="row' + iden + '" style="' +
                                    backgroundColor + '">' +
                                    '<td><input id="pro' + iden +
                                    '" style="width: 100px" class="text-left" name="codigo[]" type="text" readonly value="' +
                                    data[i]["Cod"] + '"></td>' +
                                    '<td><input class="text-left" name="descripcion[]" type="text" value="' +
                                    data[i]["Descripcion"] + ' ' + detalle + '"></td>' +
                                    '<td class="text-center">' + data[i]["TextUnidad"] +
                                    '</td>' +
                                    '<td><input id="prec' + iden +
                                    '" style="width: 100px" class="text-right" name="precio[]" step="any" type="number" min="0" value="' +
                                    precio + '" readonly ></td>' +
                                    '<td><input id="desc' + iden +
                                    '" style="width: 100px" class="text-right" name="descuento[]" step="any" type="number" min="0" value="' +
                                    data[i]["Descuento"] + '" readonly></td>' +
                                    '<td><input id="cant' + iden + '" style="width: 100px ' +
                                    backGrounColorCantidad + '" onchange="calcular(' + iden +
                                    ', ' + data[i]["Cantidad"] +
                                    ');"  class="text-center" name="cantidad[]" type="number" min="1" max="' +
                                    data[i]["Cantidad"] + '" value="' + data[i]["Cantidad"] +
                                    '" ' + readOnlyCantidad + '></td>' +
                                    '<td><input id="imp' + iden +
                                    '" style="width: 100px" class="text-right" class="text-center" name="importe[]" step="any" type="number" readonly value="' +
                                    importe + '"></td>' +
                                    '<td hidden><input id="grat' + iden +
                                    '" name="gratuitos[]" value="' + data[i]["Gratuito"] +
                                    '"></td>' +
                                    botonEliminar +
                                    '</tr>';
                                $('#tablaAgregado tr:last').after(fila);
                                iden++;
                            }

                            if (idMotivo == 7) {
                                respuestaInfoMensaje('Atención',
                                    'Ingresar las cantidades de items que se devolverá y quitar los que el cliente conservara en su poder'
                                );
                                //alert("Modificar solo las cantidades a devolver");
                            }

                            if (idMotivo == 23) {
                                respuestaInfoMensaje('Atención',
                                    'Las Notas de Crédito tipo 13 enviará los precios y montos en 0'
                                );
                                //alert("Las Notas de Crédito tipo 13 enviara los precios y montos en 0");
                                $("#fechaNC").show();
                                $("#diasNC").show();
                                $("#montoNC").show();
                            } else {
                                $("#fechaNC").hide();
                                $("#diasNC").hide();
                                $("#montoNC").hide();
                            }

                        } else {
                            respuestaInfoMensaje(null, 'No se encontraron datos');
                            //alert("no se encontraron datos");
                        }

                        //$.hideLoading();
                        setTimeout(function() {
                            $.LoadingOverlay("hide");
                        }, 500);
                    }
                });
            });

            $("#plazoDiasNC").on("change", function() {
                var nuevoPlazoDias = $("#plazoDiasNC").val();
                var nuevafechaPagoNC = $("#fechaFactura").val();
                var _nuevaFecha = new Date(nuevafechaPagoNC);
                _nuevaFecha.setDate(_nuevaFecha.getDate() + parseInt(nuevoPlazoDias, 10));
                mostrarFechaPagoNC(_nuevaFecha);
            });
        });

        function mostrarFechaPagoNC(nuevaFecha) {
            var dd = nuevaFecha.getDate();
            var mm = nuevaFecha.getMonth() + 1;

            var yyyy = nuevaFecha.getFullYear();
            if (dd < 10) {
                dd = '0' + dd;
            }
            if (mm < 10) {
                mm = '0' + mm;
            }
            var nuevaFecha = yyyy + '-' + mm + '-' + dd;
            $("#fechaPagoNC").val(nuevaFecha);
        }

        function PadLeft(value, length) {
            return (value.toString().length < length) ? PadLeft("0" + value, length) :
                value;
        }

        function redondeo(num) {
            /*var flotante = parseFloat(numero);
            var resultado = Math.round(flotante * Math.pow(10, decimales)) / Math.pow(10, decimales);
            return resultado;*/

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

        function calcular(idRow, stock) {
            //var row = idRow.parentNode.parentNode;
            var tipoVenta = $('#tipoVenta').val();
            var precio = $('#prec' + idRow).val();
            var cantidad = $('#cant' + idRow).val();
            var descuento = $('#desc' + idRow).val();

            if (parseFloat(cantidad) > stock || parseFloat(cantidad) < 1) {
                alert('La cantidad a devolver no puede exceder a la cantidad de la factura o ser menor que 1');
                $("#cant" + idRow).val(stock);
            }

            cantidad = $('#cant' + idRow).val();

            var importeProducto = parseFloat(parseFloat(precio) * parseInt(cantidad, 10) - parseFloat(descuento));

            $('#imp' + idRow).val(redondeo(importeProducto));

            var filas = $("#tablaAgregado").find("tr");
            var sumTotal = 0;
            var sumDescuento = 0;
            var sumTotalGravada = 0;
            var sumTotalExonerada = 0;
            var sumTotalGratuito = 0;

            for (i = 1; i < filas.length; i++) { //Recorre las filas 1 a 1
                var celdas = $(filas[i]).find("td"); //devolverá las celdas de una fila
                //_codigo = $($(celdas[0]).children("input")[0]).val();
                _precio = $($(celdas[3]).children("input")[0]).val();
                _descuento = $($(celdas[4]).children("input")[0]).val();
                _cantidad = $($(celdas[5]).children("input")[0]).val();
                _importe = $($(celdas[6]).children("input")[0]).val();
                _gratuito = $($(celdas[7]).children("input")[0]).val();
                if (_gratuito == 1) {
                    sumTotalGratuito += parseFloat(_importe);
                } else {
                    if (tipoVenta == 1) {
                        sumTotalGravada += parseFloat(_importe);
                    } else {
                        sumTotalExonerada += parseFloat(_importe);
                    }
                }
                sumDescuento += parseFloat(_descuento);
            }

            sumTotal += parseFloat(sumTotalGravada) + parseFloat(sumTotalExonerada);

            var igv = parseFloat((18 / 100) + 1);
            var _subtotal = parseFloat(sumTotalGravada) / parseFloat(igv);
            var _igvTotal = parseFloat(sumTotalGravada) - parseFloat(_subtotal);

            impTotal = sumTotal;
            subtotal = _subtotal;
            opExonerado = sumTotalExonerada;
            igvTotal = _igvTotal;
            exonerada = sumDescuento;
            opGratuita = sumTotalGratuito;

            $('#subtotal').val(redondeo(subtotal));
            $('#opExonerado').val(redondeo(opExonerado));
            $('#opGratuita').val(redondeo(opGratuita));
            $('#igv').val(redondeo(igvTotal));
            $('#exonerada').val(redondeo(exonerada));
            $('#total').val(redondeo(impTotal));
        }

        function quitar(idRow) {

            //var stock = $('#p7-' + i).val();
            //var ide = $('#pro' + idRow).val();
            var tipoVenta = $('#tipoVenta').val();
            var precio = $('#prec' + idRow).val();
            var cantidad = $('#cant' + idRow).val();
            var descuento = $('#desc' + idRow).val();
            var importeFinal = $('#imp' + idRow).val();
            //var ganancia = $('#gan' + id).text();
            //var uniMed = $('#unidMed' + id).val();
            //var cantidadTipo = $('#cantTipo' + id).val();
            //var tipoMoneda = $("#tipoMoneda").val();
            var gratuitoPro = $('#grat' + idRow).val();

            if (tipoVenta == 1) {
                if (gratuitoPro == 1) {
                    $('#opGratuita').val('');
                    opGratuita -= importeFinal;
                } else {
                    $('#subtotal').val('');
                    $('#igv').val('');

                    var igv = parseFloat((18 / 100) + 1);
                    impTotal -= parseFloat(importeFinal);
                    subtotal = parseFloat(impTotal) / parseFloat(igv);
                    igvTotal = parseFloat(impTotal) - parseFloat(subtotal);
                    exonerada -= parseFloat(descuento);
                }

                $('#subtotal').val(redondeo(subtotal));
                $('#opExonerado').val(redondeo(opExonerado));
                $('#opGratuita').val(redondeo(opGratuita));
                $('#igv').val(redondeo(igvTotal));
                $('#exonerada').val(redondeo(exonerada));
                $('#total').val(redondeo(impTotal));

            } else {
                if (gratuitoPro == 1) {
                    $('#opGratuita').val('');
                    opGratuita -= importeFinal;
                } else {
                    $('#opExonerado').val('');
                    impTotal -= parseFloat(importeFinal);
                    opExonerado = parseFloat(impTotal);
                    exonerada -= parseFloat(descuento);
                }

                $('#subtotal').val(redondeo(subtotal));
                $('#opExonerado').val(redondeo(opExonerado));
                $('#opGratuita').val(redondeo(opGratuita));
                $('#igv').val(redondeo(igvTotal));
                $('#exonerada').val(redondeo(exonerada));
                $('#total').val(redondeo(impTotal));
            }

            $('#row' + idRow).remove();
        }
    </script>
</body>

</html>
