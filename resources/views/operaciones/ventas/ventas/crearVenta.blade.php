<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/center-circle.css') }}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Generar Venta</title>
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
    <link href="{{ asset('assets/css/newStyles.css?v=' . rand(1, 99)) }}" rel="stylesheet" type='text/css'>
    <link href="{{ asset('assets/css/loading.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/responsive.dataTables.css') }}" rel="stylesheet" type="text/css">
    <link href="jquery.nice-number.css" rel="stylesheet">
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
                            <h6 class="page-title-heading mr-0 mr-r-5">Generar Venta</h6>
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
                                                <div class="form-group">
                                                    <select id="selectTipoComp" class="form-control"
                                                        name="tipoComprobante">
                                                        <option value="0">-</option>
                                                        @if ($modulosSelect->contains('IdModulo', 4))
                                                            @foreach ($tipoComprobante as $tipCom)
                                                                @if ($ventaRapida == 1 && $tipCom->IdTipoComprobante == 3)
                                                                    <option selected
                                                                        value="{{ $tipCom->IdTipoComprobante }}">
                                                                        {{ $tipCom->Descripcion }}</option>
                                                                @elseif($ventaRapida == 2 && $tipCom->IdTipoComprobante == 1)
                                                                    <option selected
                                                                        value="{{ $tipCom->IdTipoComprobante }}">
                                                                        {{ $tipCom->Descripcion }}</option>
                                                                @else
                                                                    <option value="{{ $tipCom->IdTipoComprobante }}">
                                                                        {{ $tipCom->Descripcion }}</option>
                                                                @endif
                                                            @endforeach
                                                        @elseif($modulosSelect->contains('IdModulo', 1))
                                                            @if ($ventaRapida == 1)
                                                                <option selected value="3">Ticket</option>
                                                            @else
                                                                <option value="3">Ticket</option>
                                                            @endif
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
                                                        <input id="serie" class="form-control" placeholder="Serie"
                                                            type="text" name="serie" maxlength="4"
                                                            value="{{ $serie }}" readonly>
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
                                                            name="numero" value="{{ $numero }}" readonly>
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
                                                        @if ($ventaRapida == 1)
                                                            @foreach ($clientesTickets as $clienTick)
                                                                @if ($clienTick->RazonSocial == 'Varios')
                                                                    <option selected
                                                                        value="{{ $clienTick->IdCliente }}">
                                                                        {{ $clienTick->RazonSocial }}</option>
                                                                @else
                                                                    <option value="{{ $clienTick->IdCliente }}">
                                                                        {{ $clienTick->RazonSocial }}</option>
                                                                @endif
                                                            @endforeach
                                                        @elseif($ventaRapida == 2)
                                                            @foreach ($clientesBoletas as $clienBol)
                                                                @if ($clienBol->RazonSocial == 'Varios')
                                                                    <option selected
                                                                        value="{{ $clienBol->IdCliente }}">
                                                                        {{ $clienBol->RazonSocial }}</option>
                                                                @else
                                                                    <option value="{{ $clienBol->IdCliente }}">
                                                                        {{ $clienBol->RazonSocial }}</option>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <small class="text-muted"><strong>Seleccione el
                                                            Cliente</strong></small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
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
                                                    <label> Moneda del Comprobante</label>
                                                </div>
                                            </div>
                                            @if ($subniveles->contains('IdSubNivel', 46) && $bandVentaSolesDolares == 1)
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="checkbox" id="ventaSolesDolares"
                                                            name="ventaSolesDolares"><span
                                                            class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Combinar productos
                                                            creados en Soles y Dólares</span>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <div class="input-group border-bottom">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><label>Fecha</label></div>
                                                            <input type="hidden" id="datepicker" name="fecha"
                                                                value="" />
                                                            <span class="mt-2 ml-3">{{ $fecha }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($exonerado == 1 && $sucExonerado == 1)
                                                <div class="col-md-3">
                                                    <select id="tipoVenta" class="form-control" name="tipoVenta">
                                                        <option selected value="1">Venta Op. Gravada</option>
                                                        <option value="2">Venta Op. Exonerada</option>
                                                    </select>
                                                </div>
                                            @else
                                                <input hidden id="tipoVenta" type="text" name="tipoVenta"
                                                    value="1">
                                            @endif
                                            @if ($idRubro == 11)
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="checkbox" id="placaVehicular"
                                                            name="placaVehicular"><span
                                                            class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Agregar Placa</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3" id="selectPlacaView">
                                                    <div class="form-group">
                                                        <select id="placaSelect" class="form-control" name="placaSelect">
                                                            <option value="0">-</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3" id="btnPlacaView">
                                                    <div class="form-group">
                                                        <a class="p-1" href="#" data-toggle="modal"
                                                            data-target=".bs-modal-lg-placas"
                                                            onclick="cargarDatosClientes()"><button
                                                                class="btn btn-success">Crear Vehículo</button></a>
                                                    </div>
                                                    <!--<button type="text" id="inputPlaca" class="form-control" name="inputPlaca">-->
                                                </div>
                                            @else
                                                <input id="placaSelect" type="text" name="placaSelect"
                                                    class="form-control" value="0" hidden>
                                            @endif
                                            
                                            <div class="col-md-3">
                                                <label for="retencion">Retención</label>
                                                <label class="switch p-2">
                                                    <input id="retencion" disabled type="checkbox" name="retencion">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            @if ($opcionAnticipo == 1)
                                                <div class="col-md-3">
                                                    <label for="pagoAnticipado">Pago Anticipado</label>
                                                    <label class="switch p-2">
                                                        <input id="pagoAnticipado" type="checkbox"
                                                            name="pagoAnticipado">
                                                        <span class="slider round"></span>
                                                    </label>
                                                </div>
                                            @else
                                                <div class="col-md-3" hidden>
                                                    <label for="pagoAnticipado">Pago Anticipado</label>
                                                    <label class="switch p-2">
                                                        <input id="pagoAnticipado" type="checkbox"
                                                            name="pagoAnticipado">
                                                        <span class="slider round"></span>
                                                    </label>
                                                </div>
                                            @endif
                                            <div class="col-md-3" id="ordenCompraActivo">
                                                <div class="form-group">
                                                    <label for="ordenCompra">Orden de Compra</label>
                                                    <input type="text" id="ordenCompra"
                                                        name="ordenCompra" class="form-control" value="" maxlength="20"
                                                        >
                                                </div>
                                            </div>
                                            <div class="col-md-3" id="cambioVentas">
                                                <div class="form-group">
                                                    <label for="valorCambioVentas">Tipo de Cambio a Soles</label>
                                                    <input type="text" id="valorCambioVentas"
                                                        name="valorCambioVentas" class="form-control" value="0"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3" id="cambioCompras">
                                                <div class="form-group">
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
                                                    @if ($ventaRapida > 0)
<a href="#" data-toggle="modal" data-target=".bs-modal-lg-productos"><button id="agregarArticulo" class="btn btn-info"><i class="list-icon material-icons">add_circle</i> Agregar <span class="caret"></span></button></a>
@else
<a href="#" data-toggle="modal" data-target=".bs-modal-lg-productos"><button id="agregarArticulo" disabled class="btn btn-info"><i class="list-icon material-icons">add_circle</i> Agregar <span class="caret"></span></button></a>
@endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-4 col-sm-6">
                                                        <div class="mt-2">
                                                            <a href="#" data-toggle="modal" data-target=".bs-modal-lg-productos-soles"><button id="agregarArticuloSoles" class="btn btn-info"><i class="list-icon material-icons">add_circle</i> Agregar Productos Soles <span class="caret"></span></button></a>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                                        <div class="mt-2">
                                                            <a href="#" data-toggle="modal" data-target=".bs-modal-lg-productos"><button id="agregarArticuloDolares" class="btn btn-info"><i class="list-icon material-icons">add_circle</i> Agregar Productos Dólares <span class="caret"></span></button></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-3 col-sm-4 col-5">
                                                    <div class="mt-2">
                                                        <a href="#" data-toggle="modal" data-target=".bs-modal-lg-cliente"><button class="btn btn-info">Nuevo Cliente</button></a>
                                                    </div>
                                                </div>-->
                                            <div class="col-12 d-md-flex">
                                                <div class="mt-2">
                                                    <a class="p-1" href="#" data-toggle="modal"
                                                        data-target=".bs-modal-lg-productos-soles"><button
                                                            id="agregarArticuloSoles" class="btn btn-info"><i
                                                                class="list-icon material-icons">add_circle</i> Agregar
                                                            Productos Soles <span class="caret"></span></button></a>
                                                </div>
                                                <div class="mt-2">
                                                    <a class="p-1" href="#" data-toggle="modal"
                                                        data-target=".bs-modal-lg-productos-dolares"><button
                                                            id="agregarArticuloDolares" disabled
                                                            class="btn btn-info"><i
                                                                class="list-icon material-icons">add_circle</i> Agregar
                                                            Productos Dólares <span class="caret"></span></button></a>
                                                </div>
                                                <div class="mt-2">
                                                    <a class="p-1" href="#" data-toggle="modal"
                                                        data-target=".bs-modal-lg-cliente"><button
                                                            class="btn btn-info">Nuevo Cliente</button></a>
                                                </div>
                                            </div>
                                        </div>
                                        <br>

                                        <div class="row" style="border: 1px solid #ecdcdc; padding: 10px;">
                                            <div class="col-md-3">
                                                @if ($ventaRapida > 0)
                                                    <input type="checkbox" id="lector" name="activarLector"
                                                        checked="checked"><span
                                                        class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Lector de Códigos</span>
                                                @else
                                                    <input type="checkbox" id="lector" name="activarLector"><span
                                                        class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Lector de Códigos</span>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <input type="text" id="inputBuscarCodigoProductos"
                                                        name="textoBuscarCodigo"
                                                        placeholder="Buscar por Codigo de barras"
                                                        class="form-control fs-16 fw-400">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row align-items-center" id="content-radio">

                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <div id="textoAlertaExonerado"><span class="text-danger">Atención: Si
                                                        su compra la realizo incluido IGV tenga en cuenta que su
                                                        ganancia debe ser mayor a 18%</span></div>
                                            </div>
                                            <div class="col-md-12">
                                                <div id="armarArray" hidden="">
                                                </div>
                                                <table id="tablaAgregado" class="table table-responsive-lg"
                                                    style="width:100%">
                                                    <thead>
                                                        <tr class="bg-primary-contrast">
                                                            <th scope="col" data-tablesaw-priority="persist"
                                                                width="100">Código</th>
                                                            <th scope="col" width="100">Descripción</th>
                                                            <th scope="col" width="100">Detalle</th>
                                                            <th scope="col" width="100">Und/Medida</th>
                                                            <th scope="col" width="100">Precio</th>
                                                            <th scope="col" width="100">Dcto. Tot</th>
                                                            <th scope="col" width="100">Cantidad</th>
                                                            <th scope="col" width="100">Importe</th>
                                                            <th scope="col" width="100">Quitar</th>
                                                            <th scope="col" width="100" class="ganancia">
                                                                Ganancia</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="body">
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group d-flex">
                                                    <div
                                                        style="width: 40px; height: 20px; background: #d3d3d3; border: 0.5px solid #000;">
                                                    </div><span class="text-black fs-12 pl-1"> Producto y/o Servicio
                                                        Gratuito</span>
                                                </div>
                                                <div id="textoVentaExcedido"><span class="text-danger">NOTA: Según
                                                        decreto Legislativo 1529 desde el 1 de abril de 2022 es
                                                        obligatorio bancarizar las operaciones a partir de S/ 2.000 o
                                                        US$ 500</span></div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-lg-8 col-md-12">
                                                <div class="form-group">
                                                    <textarea id="observacion" class="form-control" rows="5" name="observacion" maxlength="500"></textarea>
                                                    <label>Observación</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-8">
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>Op Gravada:</label>
                                                    </div>
                                                    <div class="col-lg-5 col-8">
                                                        <input id="subtotal" name="subtotal" type="text"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>Op Exonerada:</label>
                                                    </div>
                                                    <div class="col-lg-5 col-8">
                                                        <input id="opExonerado" name="opExonerado" type="text"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>Op Gratuita:</label>
                                                    </div>
                                                    <div class="col-lg-5 col-8">
                                                        <input id="opGratuita" name="opGratuita" type="text"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>Descuento:</label>
                                                    </div>
                                                    <div class="col-lg-5 col-8">
                                                        <input id="exonerada" name="exonerada" type="text"
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
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <select class="form-control" id="tipoPago" name="tipoPago">
                                                        <option value="1">Contado</option>
                                                        @if ($modulosSelect->contains('IdModulo', 3))
                                                            <option value="2">Crédito</option>
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
                                                    <div id="efectivo" class="form-group">
                                                        <label id="textoEfectivo" class="form-control-label">Monto Pagado(Efectivo)</label>
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
                                                            <label id="textoTarjeta" class="form-control-label">Monto Pagado(Con
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
                                                            @foreach ($cuentasSoles as $banco)
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
                                                        <label id="textoCuentaBancaria" class="form-control-label">Monto (Cuenta Bancaria)</label>
                                                        <div class="input-group">
                                                            <input id="pagoCuenta" type="number" step="any"
                                                                class="form-control" name="montoCuenta"
                                                                value="{{ old('montoCuenta') }}" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                            
                                            <input hidden type="text" id="editarPrecio" name="editarPrecio"
                                                class="form-control" value="{{ $editarPrecio }}">
                                            <input hidden type="text" id="banderaVentaSolesDolares"
                                                name="banderaVentaSolesDolares" class="form-control" value="0">
                                            <input hidden type="text" id="switchDetraccion" name="switchDetraccion"
                                                class="form-control" value="0">
                                            <input hidden type="text" id="switchRetencion" name="switchRetencion"
                                                class="form-control" value="0">
                                            <input hidden type="text" id="switchAnticipado"
                                                name="switchAnticipado" class="form-control" value="0">
                                        </div>
                                        <div class="form-actions btn-list mt-3">
                                            <button id="btnGenerar" class="btn btn-primary" type="button"
                                                onclick="enviar();">Generar</button>
                                        </div>
                                        <!--{!! Form::close() !!}-->
                                    </div>

                                    <div class="modal fade bs-modal-lg-cliente" tabindex="-1" role="dialog"
                                        aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h6>Crear Cliente</h6>
                                                </div>
                                                <div class="modal-body">
                                                    <div id="mensaje">

                                                    </div>
                                                    <div class="widget-body clearfix">
                                                        <div class="row form-material">
                                                            <div class="col-md-5">
                                                                <div class="form-group">
                                                                    <select id="tipoDoc" class="form-control"
                                                                        name="tipoDocumento">
                                                                        @foreach ($tipoDoc as $doc)
                                                                            <option
                                                                                value="{{ $doc->IdTipoDocumento }}">
                                                                                {{ $doc->Descripcion }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <label for="tipoDoc">Tipo Documento</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <div class="form-group">
                                                                    <input id="numDoc" class="form-control"
                                                                        placeholder="Número de Documento"
                                                                        type="text" name="numDoc" maxlength="12">
                                                                    <label for="numDoc">Número de Documento</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <button id="consultar"
                                                                    class="btn btn-primary">Buscar</button>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="nombre">Nombre / Razón social</label>
                                                                    <input id="razonSocial" class="form-control"
                                                                        placeholder="Razón Social" type="text"
                                                                        name="razonSocial">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group has-error">
                                                                    <label for="razonSocial">Nombre Comercial</label>
                                                                    <input id="nombre" class="form-control"
                                                                        placeholder="Nombre" type="text"
                                                                        name="nombreComercial">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="departamento">Departamento</label>
                                                                    <select id="departamento" class="form-control"
                                                                        name="departamento">
                                                                        <option value="0">-</option>
                                                                        @foreach ($departamentos as $departamento)
                                                                            <option
                                                                                value="{{ $departamento->IdDepartamento }}">
                                                                                {{ $departamento->Nombre }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="provincia">Provincia</label>
                                                                    <select id="provincia" class="form-control"
                                                                        name="provincia">

                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="distrito">Distrito</label>
                                                                    <select id="distrito" class="form-control"
                                                                        name="distrito">

                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="form-group">
                                                                    <label for="direccion">Dirección</label>
                                                                    <input id="direccion" class="form-control"
                                                                        placeholder="Dirección" type="text"
                                                                        name="direccion">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="telefono">Teléfono</label>
                                                                    <input id="telefono" class="form-control"
                                                                        placeholder="Teléfono" type="text"
                                                                        name="telefono">

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3 col-6">
                                                                <label for="condicion">Condición</label>
                                                                <p id="condicion">-</p>
                                                            </div>
                                                            <div class="col-md-3 col-6">
                                                                <label for="estado">Estado</label>
                                                                <p id="estado">-</p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="email">Email</label>
                                                                    <input id="email" class="form-control"
                                                                        placeholder="Email" type="email"
                                                                        name="email">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-actions btn-list mt-3">
                                                            <button class="btn btn-primary" onclick="crearCliente();"
                                                                type="button">Crear</button>
                                                            <button type="button"
                                                                class="btn btn-default ripple text-left"
                                                                data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </div>
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
                                                        <label class="fs-12 negrita">Si es la primera vez que trabaja
                                                            con comprobantes electrónicos deje la serie y número
                                                            correlativo de las facturas y boletas como están por
                                                            defecto, caso contrario edite la serie y número correlativo
                                                            de cada uno según SUNAT.</label>
                                                    </div>
                                                    <div class="mt-2">
                                                        <text class="fs-11">NOTA: La serie y número correlativo ya sea
                                                            Factura o Boleta solamente se ingresara una vez. Luego se
                                                            manejara automáticamente.</text>
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
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <!--<div class="modal-header">
                                                    <h6 class="modal-title">Configurar Tipo de Cambio</h6>
                                                </div>-->
                                                <div class="modal-body">
                                                    <fieldset class="fieldset fieldset--bordeCeleste">
                                                    <legend class="legend legend--colorNegro">Tipo Cambio:</legend>
                                                        <div class="form-group">
                                                            <label for="soles">TC Compras</label>
                                                            <input id="tipoCambioCompras" class="form-control" type="number"
                                                                name="TipoCambioCompras">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="soles">TC Ventas</label>
                                                            <input id="tipoCambioVentas" class="form-control" type="number"
                                                                name="TipoCambioVentas">
                                                        </div>
                                                    </fieldset>
                                                    <fieldset class="fieldset fieldset--bordeCeleste">
                                                    <legend class="legend legend--colorNegro">Tipo de Cambio Sunat:</legend>
                                                        <div class="form-group">
                                                            <label for="soles"><span>TCS Compras</span></label>
                                                            <input id="tipoCambioComprasSunat" class="form-control" type="number"
                                                                name="TipoCambioComprasSunat">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="soles"><span>TCS ventas</span></label>
                                                            <input id="tipoCambioVentasSunat" class="form-control" type="number"
                                                                name="TipoCambioVentasSunat">
                                                        </div>
                                                    </fieldset>
                                                </div>
                                               
                                                <div class="modal-footer">
                                                    <button id="btnTipoCambio" onclick="guardaTipoCambio();"
                                                        class="btn btn-primary btnEliminar">Aceptar</button>
                                                    <button id="btnCerrarTipoCambio" onclick="cerrarTipoCambio();"
                                                        class="btn btn-secondary"
                                                        data-dismiss="modal">Cancelar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade bs-modal-lg-productos-soles" role="dialog"
                                        aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <div class="tabs tabs-bordered">
                                                        <ul class="nav nav-tabs">
                                                            <li class="nav-item"><a class="nav-link active"
                                                                    href="#tab-productos-soles" data-toggle="tab"
                                                                    aria-controls="tab-productos-soles">Productos</a>
                                                            </li>
                                                            <li class="nav-item"><a class="nav-link"
                                                                    href="#tab-servicios-soles" data-toggle="tab"
                                                                    aria-controls="tab-servicios-soles">Servicios</a>
                                                            </li>
                                                        </ul>
                                                        <div class="tab-content">
                                                            <div class="tab-pane active" id="tab-productos-soles">
                                                                <!--<h5 class="modal-title" id="myLargeModalLabel">Listado de Productos</h5>-->
                                                                <div class="clearfix">
                                                                    <div class="form-group form-material">
                                                                        <div class="row">
                                                                            <div class="col-sm-6 col-12">
                                                                                <select
                                                                                    class="form-control select2-hidden-accessible"
                                                                                    id="categoriaSoles"
                                                                                    name="categoriaSoles"
                                                                                    data-placeholder="Choose"
                                                                                    tabindex="-1"
                                                                                    data-toggle="select2"
                                                                                    aria-hidden="true">
                                                                                    <option value="0">Seleccionar
                                                                                        Categoría</option>
                                                                                    @foreach ($categorias as $categoria)
                                                                                        <option
                                                                                            value="{{ $categoria->IdCategoria }}">
                                                                                            {{ $categoria->Nombre }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-sm-6 col-12">
                                                                                <input type="checkbox"
                                                                                    id="lector2Soles" value="1"
                                                                                    name="activarLector"><span
                                                                                    class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Excluir
                                                                                    Marca</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <i
                                                                            class="feather feather-search pos-absolute pos-right vertical-center pr-4"></i>
                                                                        <input type="search"
                                                                            id="inputBuscarProductosSoles"
                                                                            name="textoBuscar"
                                                                            placeholder="Buscar producto..."
                                                                            class="form-control form-control-rounded heading-font-family fs-16 pr-5 pl-3">
                                                                    </div>

                                                                    <!-- Products List -->
                                                                    <div id="listaProductosSoles"
                                                                        class="ecommerce-products list-unstyled row">
                                                                        @foreach ($productos as $producto)
                                                                            <div
                                                                                class="product col-12 col-md-6 idem-{{ $producto->IdArticulo }}">
                                                                                <div class="card-body">
                                                                                    <div class="row">
                                                                                        <div class="col-6 d-flex">
                                                                                            <span class="fs-16"
                                                                                                style="line-height: 1;">S/</span>
                                                                                            <span
                                                                                                id="p2-{{ $producto->IdArticulo }}"
                                                                                                class="product-price fs-16">{{ $producto->Precio }}</span>
                                                                                            <span style="display: none"
                                                                                                id="precioDescuento-{{ $producto->IdArticulo }}"
                                                                                                class="product-price fs-16">{{ $producto->PrecioDescuento1 }}</span>
                                                                                        </div>
                                                                                        @if ($sucExonerado == 1)
                                                                                            <div class="col-6">S/
                                                                                                <span
                                                                                                    class="text-danger product-price fs-16">{{ number_format($producto->Precio / 1.18, 2) }}</span>
                                                                                            </div>
                                                                                        @endif
                                                                                        <div class="col-12">
                                                                                            <span
                                                                                                id="p1-{{ $producto->IdArticulo }}"
                                                                                                class="product-title font-weight-bold fs-16">{{ $producto->Descripcion }}</span>
                                                                                        </div>
                                                                                        <div class="col-12">
                                                                                            <span
                                                                                                class="text-success fs-12">{{ $producto->Codigo }}</span>
                                                                                        </div>
                                                                                        <div class="col-12">
                                                                                            <span
                                                                                                class="text-muted fs-13">{{ $producto->Marca }}
                                                                                                /
                                                                                                {{ $producto->Categoria }}
                                                                                                / </span> <span
                                                                                                class="text-danger fs-13">Stock
                                                                                                :
                                                                                                {{ $producto->Stock }}
                                                                                            </span>
                                                                                        </div>
                                                                                        @if ($usuarioSelect->OpcionOperacionGratuita == 1)
                                                                                            <div class="col-12">
                                                                                                <label
                                                                                                    for="gratuita">Gratuita</label>
                                                                                                <label
                                                                                                    class="switch p-2">
                                                                                                    <input
                                                                                                        id="switchGratuita-{{ $producto->IdArticulo }}"
                                                                                                        type="checkbox"
                                                                                                        class="switchGratuita">
                                                                                                    <span
                                                                                                        class="slider round"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        @endif


                                                                                        <!-- esto puse 2 -->
                                                                                        <div class="col-12">
                                                                                            <div
                                                                                                class="row align-items-center">
                                                                                                <div
                                                                                                    class="radiobox radiobox-primary col-6">
                                                                                                    <label>
                                                                                                        <input
                                                                                                            id="chkUnidad-{{ $producto->IdArticulo }}"
                                                                                                            onclick="seleccionarRadio({{ $producto->IdArticulo }},'u', 0);"
                                                                                                            type="radio"
                                                                                                            checked="checked">
                                                                                                        <span
                                                                                                            class="label-text">Unidad</span>
                                                                                                    </label>
                                                                                                </div>
                                                                                                @if ($producto->CantidadTipo > 0)
                                                                                                    <div
                                                                                                        class="radiobox radiobox-primary col-6">
                                                                                                        <label>
                                                                                                            <input
                                                                                                                id="chkTipoUn-{{ $producto->IdArticulo }}"
                                                                                                                onclick="seleccionarRadio({{ $producto->IdArticulo }},'t', 0);"
                                                                                                                type="radio"><span
                                                                                                                id="nomProducto-{{ $producto->IdArticulo }}"
                                                                                                                class="label-text">{{ $producto->NombreTipo }}</span>
                                                                                                            <input
                                                                                                                hidden
                                                                                                                id="idTipoUnidad-{{ $producto->IdArticulo }}"
                                                                                                                type="text"
                                                                                                                value="{{ $producto->IdTipoUnidad }}">
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @endif
                                                                                                <div hidden>
                                                                                                    <input
                                                                                                        id="cantidadTipoUnidad-{{ $producto->IdArticulo }}"
                                                                                                        value="{{ $producto->CantidadTipo }}">
                                                                                                    <input
                                                                                                        id="descuentoTipoUnidad-{{ $producto->IdArticulo }}"
                                                                                                        value="{{ $producto->DescuentoTipo }}">
                                                                                                    <input
                                                                                                        id="precioTipoUnidad-{{ $producto->IdArticulo }}"
                                                                                                        value="{{ $producto->PrecioTipo }}">
                                                                                                </div>
                                                                                                @if ($producto->VentaMayor1 > 0)
                                                                                                    <div
                                                                                                        class="radiobox radiobox-primary col-6">
                                                                                                        <label>
                                                                                                            <input
                                                                                                                id="chkxMayor-{{ $producto->IdArticulo }}"
                                                                                                                onclick="seleccionarRadio({{ $producto->IdArticulo }},'m', 0);"
                                                                                                                type="radio"><span
                                                                                                                class="label-text">Por
                                                                                                                mayor</span>
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @endif
                                                                                                <div hidden>
                                                                                                    <input
                                                                                                        id="descuento1-{{ $producto->IdArticulo }}"
                                                                                                        value="{{ $producto->Descuento1 }}">
                                                                                                    <input
                                                                                                        id="precioDescuento1-{{ $producto->IdArticulo }}"
                                                                                                        value="{{ $producto->PrecioDescuento1 }}">
                                                                                                </div>
                                                                                                <div class="col-6">
                                                                                                    <select
                                                                                                        id="ventasMayor-{{ $producto->IdArticulo }}"
                                                                                                        class="form-control"
                                                                                                        style="display: none">
                                                                                                        @if ($producto->VentaMayor1 > 0)
                                                                                                            <option
                                                                                                                value="1">
                                                                                                                <=
                                                                                                                    {{ $producto->VentaMayor1 }}</option>
                                                                                                        @endif
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <!-- esto puse 2 -->

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
                                                                                        <div class="form-group col-12">
                                                                                            <label
                                                                                                class="col-form-label-sm">Costo</label>
                                                                                            <input
                                                                                                id="p6-{{ $producto->IdArticulo }}"
                                                                                                value="{{ $producto->Costo }}"
                                                                                                class="form-control text-center" />
                                                                                        </div>
                                                                                    </div>
                                                                                    <div hidden>
                                                                                        <div class="form-group col-12">
                                                                                            <label
                                                                                                class="col-form-label-sm">Stock
                                                                                            </label>
                                                                                            <input
                                                                                                id="p7-{{ $producto->IdArticulo }}"
                                                                                                value="{{ $producto->Stock }}"
                                                                                                class="form-control text-center" />
                                                                                        </div>
                                                                                    </div>
                                                                                    <div hidden>
                                                                                        <input
                                                                                            id="p8-{{ $producto->IdArticulo }}"
                                                                                            value="{{ $producto->TipoOperacion }}"
                                                                                            class="form-control text-center" />
                                                                                    </div>
                                                                                    <div hidden>
                                                                                        <input
                                                                                            id="p9-{{ $producto->IdArticulo }}"
                                                                                            value="{{ $producto->IdTipoMoneda }}"
                                                                                            class="form-control text-center" />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="card-footer">
                                                                                    <div class="product-info col-12">
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
                                                                            <ul id="paginasProductosSoles"
                                                                                class="pagination pagination-md d-flex justify-content-center pagProdSoles">
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
                                                                                                <li class="page-item">
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
                                                                                                <li class="page-item">
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
                                                            <div class="tab-pane" id="tab-servicios-soles">
                                                                <div class="clearfix">
                                                                    <div class="form-group">
                                                                        <i
                                                                            class="feather feather-search pos-absolute pos-right vertical-center pr-4"></i>
                                                                        <input type="search"
                                                                            id="inputBuscarServiciosSoles"
                                                                            name="textoBuscar"
                                                                            placeholder="Buscar servicio..."
                                                                            class="form-control form-control-rounded heading-font-family fs-16 pr-5 pl-3">
                                                                    </div>
                                                                    <!-- Products List -->
                                                                    <div id="listaServiciosSoles"
                                                                        class="ecommerce-products list-unstyled row">
                                                                        @foreach ($servicios as $servicio)
                                                                            <div class="product col-12 col-md-6">
                                                                                <div class="card-body">
                                                                                    <div class="row">
                                                                                        <div class="col-6">S/
                                                                                            <span
                                                                                                id="s2-{{ $servicio->IdArticulo }}"
                                                                                                class="product-price fs-16">{{ $servicio->Precio }}</span>
                                                                                        </div>
                                                                                        @if ($sucExonerado == 1)
                                                                                            <div class="col-6">S/
                                                                                                <span
                                                                                                    class="text-danger product-price fs-16">{{ number_format($servicio->Precio / 1.18, 2) }}</span>
                                                                                            </div>
                                                                                        @endif
                                                                                        <div class="col-12">
                                                                                            <span
                                                                                                id="s1-{{ $servicio->IdArticulo }}"
                                                                                                class="product-title font-weight-bold fs-16">{{ $servicio->Descripcion }}</span>
                                                                                        </div>
                                                                                        <div class="col-12">
                                                                                            <span
                                                                                                class="text-success fs-12">{{ $servicio->Codigo }}</span>
                                                                                        </div>
                                                                                        @if ($usuarioSelect->OpcionOperacionGratuita == 1)
                                                                                            <div class="col-12">
                                                                                                <label
                                                                                                    for="gratuita">Gratuita</label>
                                                                                                <label
                                                                                                    class="switch p-2">
                                                                                                    <input
                                                                                                        id="switchGratuita-{{ $servicio->IdArticulo }}"
                                                                                                        type="checkbox"
                                                                                                        class="switchGratuita">
                                                                                                    <span
                                                                                                        class="slider round"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        @endif
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
                                                                                        <div class="form-group col-12">
                                                                                            <label
                                                                                                class="col-form-label-sm">Costo</label>
                                                                                            <input
                                                                                                id="s4-{{ $servicio->IdArticulo }}"
                                                                                                value="{{ $servicio->Costo }}"
                                                                                                class=" text-center" />
                                                                                        </div>
                                                                                    </div>
                                                                                    <div hidden>
                                                                                        <input
                                                                                            id="s7-{{ $servicio->IdArticulo }}"
                                                                                            value="{{ $servicio->IdTipoMoneda }}"
                                                                                            class="form-control text-center" />
                                                                                    </div>
                                                                                </div>

                                                                                <div class="card-footer">
                                                                                    <div class="product-info col-12">
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
                                                                            <ul id="paginasServiciosSoles"
                                                                                class="pagination pagination-md d-flex justify-content-center pagServSoles">
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
                                                                                                <li class="page-item">
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
                                                                                                <li class="page-item">
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

                                    <div class="modal fade bs-modal-lg-productos-dolares" role="dialog"
                                        aria-labelledby="myLargeModalLabel" aria-hidden="true"
                                        style="display: none">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <div class="tabs tabs-bordered">
                                                        <ul class="nav nav-tabs">
                                                            <li class="nav-item"><a class="nav-link active"
                                                                    href="#tab-productos-dolares" data-toggle="tab"
                                                                    aria-controls="tab-productos-dolares">Productos</a>
                                                            </li>
                                                            <li class="nav-item"><a class="nav-link"
                                                                    href="#tab-servicios-dolares" data-toggle="tab"
                                                                    aria-controls="tab-servicios-dolares">Servicios</a>
                                                            </li>
                                                        </ul>
                                                        <div class="tab-content">
                                                            <div class="tab-pane active" id="tab-productos-dolares">
                                                                <!--<h5 class="modal-title" id="myLargeModalLabel">Listado de Productos</h5>-->
                                                                <div class="clearfix">
                                                                    <div class="form-group form-material">
                                                                        <div class="row">
                                                                            <div class="col-sm-6 col-12">
                                                                                <select
                                                                                    class="form-control select2-hidden-accessible"
                                                                                    id="categoriaDolares"
                                                                                    name="categoriaDolares"
                                                                                    data-placeholder="Choose"
                                                                                    tabindex="-1"
                                                                                    data-toggle="select2"
                                                                                    aria-hidden="true">
                                                                                    <option value="0">Seleccionar
                                                                                        Categoría</option>
                                                                                    @foreach ($categorias as $categoria)
                                                                                        <option
                                                                                            value="{{ $categoria->IdCategoria }}">
                                                                                            {{ $categoria->Nombre }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-sm-6 col-12">
                                                                                <input type="checkbox"
                                                                                    id="lector2Dolares"
                                                                                    value="1"
                                                                                    name="activarLector"><span
                                                                                    class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Excluir
                                                                                    Marca</span>
                                                                            </div>
                                                                        </div>
                                                                        <!--<div class="form group mt-2">
                                                                            <label class="form-control-label fs-14 fw-500">Buscar Producto</label>
                                                                            <input type="text" id="inputBuscarProductos" name="textoBuscar" class="form-control fs-16 fw-400">
                                                                        </div>-->
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <i
                                                                            class="feather feather-search pos-absolute pos-right vertical-center pr-4"></i>
                                                                        <input type="search"
                                                                            id="inputBuscarProductosDolares"
                                                                            name="textoBuscar"
                                                                            placeholder="Buscar producto..."
                                                                            class="form-control form-control-rounded heading-font-family fs-16 pr-5 pl-3">
                                                                    </div>

                                                                    <!-- Products List -->
                                                                    <div id="listaProductosDolares"
                                                                        class="ecommerce-products list-unstyled row">
                                                                        @foreach ($productosDolares as $_producto)
                                                                            <div
                                                                                class="product col-12 col-md-6 idem-{{ $_producto->IdArticulo }}">
                                                                                <div class="card-body">
                                                                                    <div class="row">
                                                                                        <div class="col-6 d-flex">
                                                                                            <span class="fs-16"
                                                                                                style="line-height: 1;">$</span>
                                                                                            <span
                                                                                                id="p2-{{ $_producto->IdArticulo }}"
                                                                                                class="product-price fs-16">{{ $_producto->Precio }}</span>
                                                                                            <span
                                                                                                style="display: none"
                                                                                                id="precioDescuento-{{ $_producto->IdArticulo }}"
                                                                                                class="product-price fs-16">{{ $_producto->PrecioDescuento1 }}</span>
                                                                                        </div>
                                                                                        @if ($sucExonerado == 1)
                                                                                            <div class="col-6">$
                                                                                                <span
                                                                                                    class="text-danger product-price fs-16">{{ number_format($_producto->Precio / 1.18, 2) }}</span>
                                                                                            </div>
                                                                                        @endif
                                                                                        <div class="col-12">
                                                                                            <span
                                                                                                id="p1-{{ $_producto->IdArticulo }}"
                                                                                                class="product-title font-weight-bold fs-16">{{ $_producto->Descripcion }}</span>
                                                                                        </div>
                                                                                        <div class="col-12">
                                                                                            <span
                                                                                                class="text-success fs-12">{{ $_producto->Codigo }}</span>
                                                                                        </div>
                                                                                        <div class="col-12">
                                                                                            <span
                                                                                                class="text-muted fs-13">{{ $_producto->Marca }}
                                                                                                /
                                                                                                {{ $_producto->Categoria }}
                                                                                                / </span> <span
                                                                                                class="text-danger fs-13">Stock
                                                                                                :
                                                                                                {{ $_producto->Stock }}
                                                                                            </span>
                                                                                        </div>
                                                                                        <div class="col-12">
                                                                                            <label
                                                                                                for="gratuita">Gratuita</label>
                                                                                            <label class="switch p-2">
                                                                                                <input
                                                                                                    id="switchGratuita-{{ $_producto->IdArticulo }}"
                                                                                                    type="checkbox"
                                                                                                    class="switchGratuita">
                                                                                                <span
                                                                                                    class="slider round"></span>
                                                                                            </label>
                                                                                        </div>

                                                                                        <!-- esto puse 2 -->
                                                                                        <div class="col-12">
                                                                                            <div
                                                                                                class="row align-items-center">
                                                                                                <div
                                                                                                    class="radiobox radiobox-primary col-6">
                                                                                                    <label>
                                                                                                        <input
                                                                                                            id="chkUnidad-{{ $_producto->IdArticulo }}"
                                                                                                            onclick="seleccionarRadio({{ $_producto->IdArticulo }},'u', 0);"
                                                                                                            type="radio"
                                                                                                            checked="checked">
                                                                                                        <span
                                                                                                            class="label-text">Unidad</span>
                                                                                                    </label>
                                                                                                </div>
                                                                                                @if ($_producto->CantidadTipo > 0)
                                                                                                    <div
                                                                                                        class="radiobox radiobox-primary col-6">
                                                                                                        <label>
                                                                                                            <input
                                                                                                                id="chkTipoUn-{{ $_producto->IdArticulo }}"
                                                                                                                onclick="seleccionarRadio({{ $_producto->IdArticulo }},'t', 0);"
                                                                                                                type="radio"><span
                                                                                                                id="nomProducto-{{ $_producto->IdArticulo }}"
                                                                                                                class="label-text">{{ $_producto->NombreTipo }}</span>
                                                                                                            <input
                                                                                                                hidden
                                                                                                                id="idTipoUnidad-{{ $_producto->IdArticulo }}"
                                                                                                                type="text"
                                                                                                                value="{{ $_producto->IdTipoUnidad }}">
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @endif
                                                                                                <div hidden>
                                                                                                    <input
                                                                                                        id="cantidadTipoUnidad-{{ $_producto->IdArticulo }}"
                                                                                                        value="{{ $_producto->CantidadTipo }}">
                                                                                                    <input
                                                                                                        id="descuentoTipoUnidad-{{ $_producto->IdArticulo }}"
                                                                                                        value="{{ $_producto->DescuentoTipo }}">
                                                                                                    <input
                                                                                                        id="precioTipoUnidad-{{ $_producto->IdArticulo }}"
                                                                                                        value="{{ $_producto->PrecioTipo }}">
                                                                                                </div>
                                                                                                @if ($_producto->VentaMayor1 > 0)
                                                                                                    <div
                                                                                                        class="radiobox radiobox-primary col-6">
                                                                                                        <label>
                                                                                                            <input
                                                                                                                id="chkxMayor-{{ $_producto->IdArticulo }}"
                                                                                                                onclick="seleccionarRadio({{ $_producto->IdArticulo }},'m', 0);"
                                                                                                                type="radio"><span
                                                                                                                class="label-text">Por
                                                                                                                mayor</span>
                                                                                                        </label>
                                                                                                    </div>
                                                                                                @endif
                                                                                                <div hidden>
                                                                                                    <input
                                                                                                        id="descuento1-{{ $_producto->IdArticulo }}"
                                                                                                        value="{{ $_producto->Descuento1 }}">
                                                                                                    <input
                                                                                                        id="precioDescuento1-{{ $_producto->IdArticulo }}"
                                                                                                        value="{{ $_producto->PrecioDescuento1 }}">
                                                                                                </div>
                                                                                                <div class="col-6">
                                                                                                    <select
                                                                                                        id="ventasMayor-{{ $_producto->IdArticulo }}"
                                                                                                        class="form-control"
                                                                                                        style="display: none">
                                                                                                        @if ($_producto->VentaMayor1 > 0)
                                                                                                            <option
                                                                                                                value="1">
                                                                                                                <=
                                                                                                                    {{ $_producto->VentaMayor1 }}</option>
                                                                                                        @endif
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <!-- esto puse 2 -->

                                                                                    </div>

                                                                                    <input hidden
                                                                                        id="p3-{{ $_producto->IdArticulo }}"
                                                                                        value="{{ $_producto->UM }}" />
                                                                                    <!-- esto puse 1 -->
                                                                                    <input hidden
                                                                                        id="IdUnidadMedida-{{ $_producto->IdArticulo }}"
                                                                                        value="{{ $_producto->IdUnidadMedida }}" />
                                                                                    <!-- esto puse 1 -->


                                                                                    <div class="form-group mt-2"
                                                                                        hidden>
                                                                                        <label
                                                                                            class="col-form-label-sm">Cantidad
                                                                                        </label>
                                                                                        @if ($_producto->Stock < 1)
                                                                                            <input
                                                                                                id="p4-{{ $_producto->IdArticulo }}"
                                                                                                type="number"
                                                                                                min="0"
                                                                                                value="0"
                                                                                                class=" text-center" />
                                                                                        @else
                                                                                            <input
                                                                                                id="p4-{{ $_producto->IdArticulo }}"
                                                                                                type="number"
                                                                                                min="1"
                                                                                                value="1"
                                                                                                max="{{ $_producto->Stock }}"
                                                                                                class=" text-center" />
                                                                                        @endif
                                                                                    </div>

                                                                                    <div class="form-group" hidden>
                                                                                        <label
                                                                                            class="col-form-label-sm">Descuento
                                                                                        </label>
                                                                                        <input
                                                                                            id="p5-{{ $_producto->IdArticulo }}"
                                                                                            value="0.0"
                                                                                            class="text-center" />
                                                                                    </div>

                                                                                    <div hidden>
                                                                                        <div
                                                                                            class="form-group col-12">
                                                                                            <label
                                                                                                class="col-form-label-sm">Costo</label>
                                                                                            <input
                                                                                                id="p6-{{ $_producto->IdArticulo }}"
                                                                                                value="{{ $_producto->Costo }}"
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
                                                                                                id="p7-{{ $_producto->IdArticulo }}"
                                                                                                value="{{ $_producto->Stock }}"
                                                                                                class="form-control text-center" />
                                                                                        </div>
                                                                                    </div>
                                                                                    <div hidden>
                                                                                        <input
                                                                                            id="p8-{{ $_producto->IdArticulo }}"
                                                                                            value="{{ $_producto->TipoOperacion }}"
                                                                                            class="form-control text-center" />
                                                                                    </div>
                                                                                    <div hidden>
                                                                                        <input
                                                                                            id="p9-{{ $_producto->IdArticulo }}"
                                                                                            value="{{ $_producto->IdTipoMoneda }}"
                                                                                            class="form-control text-center" />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="card-footer">
                                                                                    <div class="product-info col-12">
                                                                                        @if ($_producto->Stock < 1)
                                                                                            <a class="bg-info color-white fs-12 disabled"
                                                                                                href="javascript:void(0);">
                                                                                                Agotado
                                                                                            </a>
                                                                                        @else
                                                                                            <a class="bg-info color-white fs-12"
                                                                                                onclick="agregarProducto({{ $_producto->IdArticulo }})"
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
                                                                            <ul id="paginasProductosDolares"
                                                                                class="pagination pagination-md d-flex justify-content-center pagProdDolares">
                                                                                @if ($productosDolares->onFirstPage())
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
                                                                                            href="{{ $productosDolares->previousPageUrl() }}"
                                                                                            aria-label="Previous"><span
                                                                                                aria-hidden="true"><i
                                                                                                    class="feather feather-chevron-left"></i></span></a>
                                                                                    </li>
                                                                                @endif

                                                                                @if ($productosDolares->currentPage() < 3)
                                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                                        @if ($i > 0 && $i <= $productosDolares->lastPage())
                                                                                            @if ($i == $productosDolares->currentPage())
                                                                                                <li
                                                                                                    class="page-item active">
                                                                                                    <a class="page-link"
                                                                                                        href="javascript:void(0);">{{ $i }}</a>
                                                                                                </li>
                                                                                            @else
                                                                                                <li class="page-item">
                                                                                                    <a class="page-link"
                                                                                                        href="productos?page={{ $i }}">{{ $i }}</a>
                                                                                                </li>
                                                                                            @endif
                                                                                        @endif
                                                                                    @endfor
                                                                                @elseif($productosDolares->lastPage() > 2)
                                                                                    @if ($productosDolares->currentPage() > $productosDolares->lastPage() - 2)
                                                                                        @for ($i = $productosDolares->currentPage() - 4; $i <= $productosDolares->lastPage(); $i++)
                                                                                            @if ($i > 0 && $i <= $productosDolares->lastPage())
                                                                                                @if ($i == $productosDolares->currentPage())
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
                                                                                @if ($productosDolares->currentPage() >= 3 && $productosDolares->currentPage() <= $productosDolares->lastPage() - 2)
                                                                                    @for ($i = $productosDolares->currentPage() - 2; $i <= $productosDolares->currentPage() + 2; $i++)
                                                                                        @if ($i > 0 && $i <= $productosDolares->lastPage())
                                                                                            @if ($i == $productosDolares->currentPage())
                                                                                                <li
                                                                                                    class="page-item active">
                                                                                                    <a class="page-link"
                                                                                                        href="javascript:void(0);">{{ $i }}</a>
                                                                                                </li>
                                                                                            @else
                                                                                                <li class="page-item">
                                                                                                    <a class="page-link"
                                                                                                        href="productos?page={{ $i }}">{{ $i }}</a>
                                                                                                </li>
                                                                                            @endif
                                                                                        @endif
                                                                                    @endfor
                                                                                @endif
                                                                                @if ($productosDolares->hasMorePages())
                                                                                    <li class="page-item"><a
                                                                                            class="page-link"
                                                                                            href="{{ $productosDolares->nextPageUrl() }}"
                                                                                            aria-label="Next"><span
                                                                                                aria-hidden="true"><i
                                                                                                    class="feather feather-chevron-right"></i></span></a>
                                                                                    </li>
                                                                                    <li class="page-item"><a
                                                                                            class="page-link"
                                                                                            href="productos?page={{ $productosDolares->lastPage() }}"
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
                                                            <div class="tab-pane" id="tab-servicios-dolares">
                                                                <div class="clearfix">
                                                                    <div class="form-group">
                                                                        <!--<label class="form-control-label fs-14 fw-400">Buscar Servicio</label>
                                                                        <div class="input-group">
                                                                            <input type="text" id="inputBuscarServicios" name="textoBuscar" class="form-control fs-16 fw-400">
                                                                        </div>-->
                                                                        <i
                                                                            class="feather feather-search pos-absolute pos-right vertical-center pr-4"></i>
                                                                        <input type="search"
                                                                            id="inputBuscarServiciosDolares"
                                                                            name="textoBuscar"
                                                                            placeholder="Buscar servicio..."
                                                                            class="form-control form-control-rounded heading-font-family fs-16 pr-5 pl-3">

                                                                    </div>
                                                                    <!-- Products List -->
                                                                    <div id="listaServiciosDolares"
                                                                        class="ecommerce-products list-unstyled row">
                                                                        @foreach ($serviciosDolares as $_servicio)
                                                                            <div class="product col-12 col-md-6">
                                                                                <div class="card-body">
                                                                                    <div class="row">
                                                                                        <div class="col-6">$
                                                                                            <span
                                                                                                id="s2-{{ $_servicio->IdArticulo }}"
                                                                                                class="product-price fs-16">{{ $_servicio->Precio }}</span>
                                                                                        </div>
                                                                                        @if ($sucExonerado == 1)
                                                                                            <div class="col-6">$
                                                                                                <span
                                                                                                    class="text-danger product-price fs-16">{{ number_format($_servicio->Precio / 1.18, 2) }}</span>
                                                                                            </div>
                                                                                        @endif
                                                                                        <div class="col-12">
                                                                                            <span
                                                                                                id="s1-{{ $_servicio->IdArticulo }}"
                                                                                                class="product-title font-weight-bold fs-16">{{ $_servicio->Descripcion }}</span>
                                                                                        </div>
                                                                                        <div class="col-12">
                                                                                            <span
                                                                                                class="text-success fs-12">{{ $_servicio->Codigo }}</span>
                                                                                        </div>
                                                                                        <div class="col-12">
                                                                                            <label
                                                                                                for="gratuita">Gratuita</label>
                                                                                            <label class="switch p-2">
                                                                                                <input
                                                                                                    id="switchGratuita-{{ $_servicio->IdArticulo }}"
                                                                                                    type="checkbox"
                                                                                                    class="switchGratuita">
                                                                                                <span
                                                                                                    class="slider round"></span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <input hidden
                                                                                        id="s6-{{ $_servicio->IdArticulo }}"
                                                                                        value="{{ $_servicio->UM }}" />
                                                                                    <div class="form-group mt-2"
                                                                                        hidden>
                                                                                        <label
                                                                                            class="col-form-label-sm">Cantidad
                                                                                        </label>
                                                                                        <input
                                                                                            id="s5-{{ $_servicio->IdArticulo }}"
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
                                                                                            id="s3-{{ $_servicio->IdArticulo }}"
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
                                                                                                id="s4-{{ $_servicio->IdArticulo }}"
                                                                                                value="{{ $_servicio->Costo }}"
                                                                                                class=" text-center" />
                                                                                        </div>
                                                                                    </div>
                                                                                    <div hidden>
                                                                                        <input
                                                                                            id="s7-{{ $_servicio->IdArticulo }}"
                                                                                            value="{{ $_servicio->IdTipoMoneda }}"
                                                                                            class="form-control text-center" />
                                                                                    </div>
                                                                                </div>

                                                                                <div class="card-footer">
                                                                                    <div class="product-info col-12">
                                                                                        <a class="bg-info color-white fs-12"
                                                                                            onclick="agregarServicio({{ $_servicio->IdArticulo }})"
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
                                                                            <ul id="paginasServiciosDolares"
                                                                                class="pagination pagination-md d-flex justify-content-center pagServDolares">
                                                                                @if ($serviciosDolares->onFirstPage())
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
                                                                                            href="{{ $serviciosDolares->previousPageUrl() }}"
                                                                                            aria-label="Previous"><span
                                                                                                aria-hidden="true"><i
                                                                                                    class="feather feather-chevron-left"></i></span></a>
                                                                                    </li>
                                                                                @endif
                                                                                @if ($serviciosDolares->currentPage() < 3)
                                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                                        @if ($i > 0 && $i <= $serviciosDolares->lastPage())
                                                                                            @if ($i == $serviciosDolares->currentPage())
                                                                                                <li
                                                                                                    class="page-item active">
                                                                                                    <a class="page-link"
                                                                                                        href="javascript:void(0);">{{ $i }}</a>
                                                                                                </li>
                                                                                            @else
                                                                                                <li class="page-item">
                                                                                                    <a class="page-link"
                                                                                                        href="servicios?page={{ $i }}">{{ $i }}</a>
                                                                                                </li>
                                                                                            @endif
                                                                                        @endif
                                                                                    @endfor
                                                                                @elseif($serviciosDolares->lastPage() > 2)
                                                                                    @if ($serviciosDolares->currentPage() > $serviciosDolares->lastPage() - 2)
                                                                                        @for ($i = $serviciosDolares->currentPage() - 4; $i <= $serviciosDolares->lastPage(); $i++)
                                                                                            @if ($i > 0 && $i <= $serviciosDolares->lastPage())
                                                                                                @if ($i == $serviciosDolares->currentPage())
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
                                                                                @if ($serviciosDolares->currentPage() >= 3 && $serviciosDolares->currentPage() <= $serviciosDolares->lastPage() - 2)
                                                                                    @for ($i = $serviciosDolares->currentPage() - 2; $i <= $serviciosDolares->currentPage() + 2; $i++)
                                                                                        @if ($i > 0 && $i <= $serviciosDolares->lastPage())
                                                                                            @if ($i == $serviciosDolares->currentPage())
                                                                                                <li
                                                                                                    class="page-item active">
                                                                                                    <a class="page-link"
                                                                                                        href="javascript:void(0);">{{ $i }}</a>
                                                                                                </li>
                                                                                            @else
                                                                                                <li class="page-item">
                                                                                                    <a class="page-link"
                                                                                                        href="servicios?page={{ $i }}">{{ $i }}</a>
                                                                                                </li>
                                                                                            @endif
                                                                                        @endif
                                                                                    @endfor
                                                                                @endif
                                                                                @if ($serviciosDolares->hasMorePages())
                                                                                    <li class="page-item"><a
                                                                                            class="page-link"
                                                                                            href="{{ $serviciosDolares->nextPageUrl() }}"
                                                                                            aria-label="Next"><span
                                                                                                aria-hidden="true"><i
                                                                                                    class="feather feather-chevron-right"></i></span></a>
                                                                                    </li>
                                                                                    <li class="page-item"><a
                                                                                            class="page-link"
                                                                                            href="servicios?page={{ $serviciosDolares->lastPage() }}"
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

                                    <div class="modal fade bs-modal-lg-placas" role="dialog"
                                        aria-labelledby="myLargeModalLabel" aria-hidden="true"
                                        style="display: none">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h6>Crear Vehículo</h6>
                                                </div>
                                                <div class="modal-body">
                                                    <div id="mensajeVehiculo">

                                                    </div>
                                                    <div class="widget-body clearfix">
                                                        <div class="row form-material">
                                                            <div class="col-md-4">
                                                                <input id="clienteSelect" readonly
                                                                    class="form-control" type="text">
                                                                <small
                                                                    class="text-muted"><strong>Cliente</strong></small>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <select
                                                                        class="m-b-10 form-control select2-hidden-accessible"
                                                                        id="tipoVehiculo" name="tipoVehiculo"
                                                                        data-placeholder="Tipo"
                                                                        data-toggle="select2" tabindex="-1"
                                                                        aria-hidden="true">
                                                                        <option value="1">Vehículo de 4 ruedas
                                                                        </option>
                                                                        <option value="2">Vehículo de 2 ruedas
                                                                        </option>
                                                                    </select>
                                                                    <small class="text-muted"><strong>Seleccione Tipo
                                                                            Vehiculo</strong></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div id="auto" class="form-group">
                                                                    <input id="placa" class="form-control"
                                                                        placeholder="Placa Auto" type="text"
                                                                        name="placa" maxlength="7">
                                                                    <label for="nombre">Placa</label>
                                                                </div>
                                                                <div id="moto" class="form-group">
                                                                    <input id="placaMoto" class="form-control"
                                                                        placeholder="Placa Moto" type="text"
                                                                        name="placaMoto">
                                                                    <label for="nombre">Placa</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <select
                                                                        class="m-b-10 form-control select2-hidden-accessible"
                                                                        id="anio" name="anio"
                                                                        data-placeholder="Año" data-toggle="select2"
                                                                        tabindex="-1" aria-hidden="true">
                                                                        @foreach ($arrayAnio as $anio)
                                                                            <option value="{{ $anio }}">
                                                                                {{ $anio }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <small class="text-muted"><strong>Seleccione
                                                                            año</strong></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <input id="color" class="form-control"
                                                                        placeholder="Color" type="text"
                                                                        name="color">
                                                                    <label>Color</label>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <select
                                                                        class="m-b-10 form-control select2-hidden-accessible"
                                                                        id="marca" name="marca"
                                                                        data-placeholder="Marca"
                                                                        data-toggle="select2" tabindex="-1"
                                                                        aria-hidden="true">
                                                                        <option value="">-</option>
                                                                        @foreach ($marcas as $marca)
                                                                            <option
                                                                                value="{{ $marca->IdMarcaGeneral }}">
                                                                                {{ $marca->NombreMarca }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <small class="text-muted"><strong>Seleccione La
                                                                            Marca</strong></small>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <select
                                                                        class="m-b-10 form-control select2-hidden-accessible"
                                                                        id="modelo" name="modelo"
                                                                        data-placeholder="Modelo"
                                                                        data-toggle="select2" tabindex="-1"
                                                                        aria-hidden="true">
                                                                        <option value="">-</option>
                                                                        @foreach ($modelos as $modelo)
                                                                            <option
                                                                                value="{{ $modelo->IdModeloGeneral }}">
                                                                                {{ $modelo->NombreModelo }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <small class="text-muted"><strong>Seleccione El
                                                                            Modelo</strong></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <select
                                                                        class="m-b-10 form-control select2-hidden-accessible"
                                                                        id="tipo" name="tipo"
                                                                        data-placeholder="Tipo"
                                                                        data-toggle="select2" tabindex="-1"
                                                                        aria-hidden="true">
                                                                        <option value=""></option>
                                                                        @foreach ($tipos as $tipo)
                                                                            <option
                                                                                value="{{ $tipo->IdTipoGeneral }}">
                                                                                {{ $tipo->NombreTipo }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <small class="text-muted"><strong>Seleccione El
                                                                            Tipo</strong></small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-actions btn-list mt-3">
                                                            <button class="btn btn-primary"
                                                                onclick="crearVehiculo();"
                                                                type="button">Crear</button>
                                                            <button type="button"
                                                                class="btn btn-default ripple text-left"
                                                                data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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
    <!--<script src="{{ asset('assets/js/loadingoverlay.min.js') }}"></script>-->

    <script src="{{ asset('assets/js/libreriasExternas/libreriaSweetAlert1/libreriaSweetalert1.js') }}"></script>
    <script src="{{ asset('assets/js/libreriasExternas/loadingOverlay/loadingoverlay.min.js') }}"></script>
    <script src="{{ asset('assets/js/utilidades/utilidades.js?v=' . time()) }}"></script>
    <script>
        $(document).ready(function() {
            let descuentoMaximoSoles = @json($usuarioSelect->DescuentoMaximoSoles);
            let descuentoMaximoDolares = @json($usuarioSelect->DescuentoMaximoDolares);
            let isAdministrador = @json($usuarioSelect->Rol);

            // Delegando el evento al document
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

            
            
            // verificar si tiene creada cuenta Detraccion
            mostrarPopUpDetraccion();
            
        });
    </script>
    <script>
        $('#tipoDoc').change(function() {
            var option = $('#tipoDoc option:selected').val();
            if (option == 3 || option == 4) {
                $('#consultar').attr('disabled', 'disabled');
            } else {
                $('#consultar').removeAttr('disabled');
            }
        })
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
            var dateBanco = dd + '/' + mm + '/' + yyyy;
            $("#date").val(dateBanco);
        });
    </script>
    <script>
        var jsonStock = [];
        var datosProductos;
        var total = 0;
        var subtotal = 0;
        var igvTotal = 0;
        var exonerada = 0;
        var opExonerado = 0;
        var opGratuita = 0;
        var iden = 1;
        var array = [];
        var arrayIds = [];
        var arrayIdTipoVenta = [];
        var arrayIdPorMayor = [];
        var banderita = 0;
        var sucExonerado = 0;
        var banderaServicio = 0;
        let opcionOperacionGratuita = @json($usuarioSelect->OpcionOperacionGratuita);
        $(function() {
            $('#plazoCredito').hide();
            $('.ganancia').hide();
            $('#textoVentaExcedido').hide();
            $('#textoAlertaExonerado').hide();
            $("#cambioVentas").hide();
            $("#cambioCompras").hide();
            $('#detraccion').hide();
            $('#selectPlacaView').hide();
            $('#btnPlacaView').hide();
            $('#ordenCompraActivo').hide();
            //$("#valorDetraccion").attr('disabled','disabled');
            //$('#agregarArticuloSoles').hide();
            //$('#agregarArticuloDolares').hide();
            var totalVentas = <?php echo json_encode(count($totalVentas)); ?>;
            sucExonerado = <?php echo json_encode($sucExonerado); ?>;
            if (totalVentas == 0) {
                $("#mostrarmodal").modal("show");
            }

            $("#selectTipoComp").on('change', function() {
                $.showLoading({
                    name: 'circle-fade',
                });
                var tipoDocumento = $("#selectTipoComp").val();
                var tipoMoneda = $("#tipoMoneda").val();
                var tipoVenta = $('#tipoVenta').val();
                var tipoPago = $("#tipoPago").val();
                var idC = $("#IdC").val();
                $('#clientes option').remove();
                $('#clientes').append('<option value="0">-</option>');

                $.ajax({
                    type: 'get',
                    url: 'documentos/obtener-informacion',
                    data: {
                        'tipoDoc': tipoDocumento
                    },
                    success: function(result) {
                        if (result.error) {
                            $('#serie').val('');
                            $('#numero').val('');
                            alert('Seleccione el Documento');
                            $('#ordenCompraActivo').hide();
                        } else {
                            $('#serie').val(result.serie);
                            $('#numero').val(result.numero);
                            $('#agregarArticulo').removeAttr('disabled');
                            for (var i = 0; i < result.clientes.length; i++) {
                                if (idC == result.clientes[i]["IdCliente"]) {
                                    $('#clientes').append('<option selected value="' + result
                                        .clientes[i]["IdCliente"] + '">' + result.clientes[
                                            i]["RazonSocial"] + ' - ' + result.clientes[i][
                                            "NumeroDocumento"
                                        ] + '</option>');
                                } else {
                                    $('#clientes').append('<option value="' + result.clientes[i]
                                        ["IdCliente"] + '">' + result.clientes[i][
                                            "RazonSocial"
                                        ] + '</option>');
                                }
                            }

                            if (tipoDocumento == 3) {
                                alert(
                                    "Este documento será no contable para fines internos, se recomienda luego canjear por boleta o factura"
                                );
                                $('#pagoAnticipado').attr("disabled", true).prop("checked",
                                    false);
                                $("#switchAnticipado").val(0);
                                $('#ordenCompraActivo').hide();
                            } else {
                                $('#pagoAnticipado').attr("disabled", false);
                                $('#ordenCompraActivo').show();
                            }

                            if (tipoMoneda == 1) {
                                var totalDetraccion = total;
                            } else {
                                var valorCambioVentas = $("#valorCambioVentas").val();
                                var totalDetraccion = parseFloat(total * valorCambioVentas);
                            }

                            manejarBotones(totalDetraccion, tipoDocumento, tipoVenta, banderaServicio);
                            

                            $("#selectPlacaView").hide();
                            $('#btnPlacaView').hide();
                            $("#placaVehicular").prop("checked", false);
                            $('#placaSelect option').remove();
                            $('#placaSelect').append('<option value="0" selected>Seleccionar Placa</option>');
                        }
                        $.hideLoading();
                    }
                });
            });

            $("#clientes").on('change', function() {
                var idCliente = $("#clientes").val();
                $("#selectPlacaView").hide();
                $('#btnPlacaView').hide();
                $("#placaVehicular").prop("checked", false);
                $('#placaSelect option').remove();
                $('#placaSelect').append('<option value="0" selected>Seleccionar Placa</option>');
                if (idCliente != 0) {
                    $('#agregarArticulo').removeAttr('disabled');
                    $('#placaVehicular').attr("disabled", false);
                } else {
                    $('#placaVehicular').attr("disabled", true);
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
                    $('#efectivo').show();
                    $('#vuelto').show();
                    $('#tarjeta').show();
                    $('#cuentaCorriente').show();
                    
                    //$("#valorDetraccion").attr('disabled', true);
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
                    $('#efectivo').hide();
                    $('#vuelto').hide();
                    $('#tarjeta').hide();
                    $('#cuentaCorriente').hide();

                    
                    //$("#valorDetraccion").attr('disabled', false);
                    /*if (tipoMoneda == 1) {
                        var totalDetraccion = total;
                    } else {
                        var valorCambioVentas = $("#valorCambioVentas").val();
                        var totalDetraccion = parseFloat(total * valorCambioVentas);
                    }

                    if (totalDetraccion >= 700 && tipoDocumento == 2 && tipoVenta == 1) {
                        if (banderaServicio > 0 ) {
                            $('#retencion').attr("disabled", true).prop("checked", false);
                            $("#switchRetencion").val(0);
                        } else {
                            $('#retencion').attr("disabled", false);
                        }
                    } else {
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
                    $('#pagoCuenta').val("");
                } else {
                    $('#pagoCuenta').attr("disabled", false);
                    $('#nroOperacion').attr("disabled", false);
                    $('#date').attr("disabled", false);
                }
            });

            $("#medioPago").on('change', function() {
                verificarMedioPago();
            });
        });

        //funcion traida de test3
        function seleccionarRadio(idProducto, tipo, barra_clear) {

            if (tipo == "u") {

                $("#chkTipoUn-" + idProducto).prop("checked", false);
                $("#chkxMayor-" + idProducto).prop("checked", false);
                document.getElementById('ventasMayor-' + idProducto).style.display = "none";
                document.getElementById('p2-' + idProducto).style.display = "block";
                document.getElementById('precioDescuento-' + idProducto).style.display = "none";
            }
            if (tipo == "t") {

                $("#chkUnidad-" + idProducto).prop("checked", false);
                $("#chkxMayor-" + idProducto).prop("checked", false);
                document.getElementById('ventasMayor-' + idProducto).style.display = "none";
                document.getElementById('p2-' + idProducto).style.display = "block";
                document.getElementById('precioDescuento-' + idProducto).style.display = "none";
            }
            if (tipo == "m") {

                $("#chkTipoUn-" + idProducto).prop("checked", false);
                $("#chkUnidad-" + idProducto).prop("checked", false);
                document.getElementById('ventasMayor-' + idProducto).style.display = "block";
                document.getElementById('p2-' + idProducto).style.display = "none";
                document.getElementById('precioDescuento-' + idProducto).style.display = "block";
            }
        }
        //////////////////////////fin ////////////////////////

        function PadLeft(value, length) {
            return (value.toString().length < length) ? PadLeft("0" + value, length) : value;
        }

        function agregarProducto(id) {
            var valorCambioVentas = $("#valorCambioVentas").val();
            var valorCambioCompras = $("#valorCambioCompras").val();
            var banderaVentaSolesDolares = $("#banderaVentaSolesDolares").val();
            var tipoMoneda = $("#tipoMoneda").val();
            if ((banderaVentaSolesDolares == 1 || tipoMoneda == 2) && (parseFloat(valorCambioVentas) == 0 || parseFloat(
                    valorCambioCompras) == 0)) {
                $(".bs-modal-lg-productos-soles").modal("hide");
                $(".bs-modal-lg-productos-dolares").modal("hide");
                $("#tipoCambio").modal("show");
            } else {
                var bandStock = -1;
                var descripcion = $('#p1-' + id).text();
                var unidadMedida = $('#p3-' + id).val();
                var precio = $('#p2-' + id).text();
                var cantidad = $('#p4-' + id).val();
                var descuento = $('#p5-' + id).val();
                var costo = $('#p6-' + id).val();
                var stock = $('#p7-' + id).val();
                var tipoOperacion = $('#p8-' + id).val();
                var idTipoMoneda = $('#p9-' + id).val();
                var tipoUnidad = $('#chkUnidad' + id);
                var idUnidadMedida = $('#IdUnidadMedida-' + id).val();
                var tipoVenta = $('#tipoVenta').val();
                var cantInput;
                var editarPrecio = $("#editarPrecio").val();
                var gratuitoPro;
                if ($('#switchGratuita-' + id).is(':checked')) {
                    gratuitoPro = 1;
                } else {
                    gratuitoPro = 0;
                }
                


                if ($('#chkUnidad-' + id).is(':checked')) {

                    if (arrayIds.includes(id) == true) {
                        alert("Producto ya agregado, por favor modificar la cantidad si desea agregar más");
                        return 0;
                    } else {
                        for (var i = 0; i < jsonStock.length; i++) {
                            if (jsonStock[i]["IdProducto"] == id) {
                                bandStock = i;
                            }
                        }
                        /*if(valorVentaSoles == 1){
                            precio = parseFloat(precio) * parseFloat(valorCambioVentas);
                        }*/
                        if (banderaVentaSolesDolares == 1) {
                            if (tipoMoneda == 1 && idTipoMoneda == 2) {
                                precio = parseFloat(precio) * parseFloat(valorCambioVentas);
                            }
                            if (tipoMoneda == 2 && idTipoMoneda == 1) {
                                precio = parseFloat(precio) / parseFloat(valorCambioCompras);
                            }
                        }
                        if (tipoVenta == 2) {
                            precio = parseFloat(precio / 1.18);
                        }
                        cantInput = 1;
                        var cantTipo = 1;
                        if (bandStock != -1) {
                            jsonStock[bandStock].StockInicial = parseFloat(jsonStock[bandStock].StockInicial) - 1;
                            jsonStock[bandStock].Stock = parseFloat(jsonStock[bandStock].Stock) + 1;
                            if (parseFloat(stock) < parseFloat(jsonStock[bandStock].Stock)) {
                                jsonStock[bandStock].Stock = parseFloat(jsonStock[bandStock].Stock) - 1;
                                parseFloat(jsonStock[bandStock].StockInicial) + 1;
                                alert("Insuficiente stock de este producto");
                            } else {
                                productoEnTabla(id, descripcion, unidadMedida, precio, cantidad, descuento, costo, stock,
                                    idUnidadMedida, cantInput, cantTipo, tipoOperacion, tipoVenta, editarPrecio, 1,
                                    tipoMoneda, gratuitoPro);
                            }
                        } else {
                            datosProductos = new Array();
                            datosProductos.IdProducto = id;
                            datosProductos.StockInicial = parseInt(stock, 10) - 1;
                            datosProductos.Stock = 1;
                            jsonStock.push(datosProductos);
                            productoEnTabla(id, descripcion, unidadMedida, precio, cantidad, descuento, costo, stock,
                                idUnidadMedida, cantInput, cantTipo, tipoOperacion, tipoVenta, editarPrecio, 1,
                                tipoMoneda, gratuitoPro);
                        }
                    }

                }
                if ($('#chkTipoUn-' + id).is(':checked')) {
                    if (arrayIdTipoVenta.includes(parseInt(id)) == true) {
                        alert("Producto ya agregado, por favor de modificar la cantidad si desea agregar más");
                    } else {


                        cantidad = 1;
                        var cantidadTipo = $('#cantidadTipoUnidad-' + id).val();
                        var nombreTipoVenta = $('#nomProducto-' + id).text();
                        var porcentajeDesc = $('#descuentoTipoUnidad-' + id).val();
                        var idUnidadMedida = $('#idTipoUnidad-' + id).val();
                        var precioTipoUnidad = $('#precioTipoUnidad-' + id).val();

                        nombreTipoVenta = nombreTipoVenta + ' x ' + cantidadTipo;

                        for (var i = 0; i < jsonStock.length; i++) {
                            if (jsonStock[i]["IdProducto"] == id) {
                                bandStock = i;
                            }
                        }

                        cantInput = 1;
                        var cantTipo = cantidadTipo;
                        if (parseFloat(cantidadTipo) > 0) {
                            if (bandStock != -1) {
                                jsonStock[bandStock].StockInicial = parseFloat(jsonStock[bandStock].StockInicial) -
                                    parseFloat(cantidadTipo);
                                jsonStock[bandStock].Stock = parseFloat(jsonStock[bandStock].Stock) + parseFloat(
                                    cantidadTipo);
                                if (parseFloat(stock) < parseFloat(jsonStock[bandStock].Stock)) {
                                    jsonStock[bandStock].Stock = parseFloat(jsonStock[bandStock].Stock) - parseFloat(
                                        cantidadTipo);
                                    jsonStock[bandStock].StockInicial = parseFloat(jsonStock[bandStock].StockInicial) +
                                        parseFloat(cantidadTipo);
                                    alert("Insuficiente stock de este producto");
                                } else {
                                    if (precioTipoUnidad > 0) {
                                        precio = precioTipoUnidad;
                                        /*if(valorVentaSoles == 1){
                                            precio = parseFloat(precio) * parseFloat(valorCambioVentas);
                                        }*/
                                        if (banderaVentaSolesDolares == 1) {
                                            if (tipoMoneda == 1 && idTipoMoneda == 2) {
                                                precio = parseFloat(precio) * parseFloat(valorCambioVentas);
                                            }
                                            if (tipoMoneda == 2 && idTipoMoneda == 1) {
                                                precio = parseFloat(precio) / parseFloat(valorCambioCompras);
                                            }
                                        }
                                        if (tipoVenta == 2) {
                                            precio = parseFloat(precio / 1.18);
                                        }
                                    }

                                    productoEnTabla(id, descripcion, nombreTipoVenta, precio, cantidad, descuento, costo,
                                        stock, idUnidadMedida, cantInput, cantTipo, tipoOperacion, tipoVenta,
                                        editarPrecio, 2, tipoMoneda, gratuitoPro);

                                }
                            } else {
                                if (parseInt(cantidadTipo, 10) <= parseInt(stock, 10)) {
                                    precio = precioTipoUnidad;
                                    /*if(valorVentaSoles == 1){
                                        precio = parseFloat(precio) * parseFloat(valorCambioVentas);
                                    }*/
                                    if (banderaVentaSolesDolares == 1) {
                                        if (tipoMoneda == 1 && idTipoMoneda == 2) {
                                            precio = parseFloat(precio) * parseFloat(valorCambioVentas);
                                        }
                                        if (tipoMoneda == 2 && idTipoMoneda == 1) {
                                            precio = parseFloat(precio) / parseFloat(valorCambioCompras);
                                        }
                                    }
                                    if (tipoVenta == 2) {
                                        precio = parseFloat(precio / 1.18);
                                    }
                                    datosProductos = new Array();
                                    datosProductos.IdProducto = id;
                                    datosProductos.StockInicial = parseInt(stock, 10) - parseInt(cantidadTipo, 10);
                                    datosProductos.Stock = parseInt(cantidadTipo, 10);
                                    jsonStock.push(datosProductos);
                                    productoEnTabla(id, descripcion, unidadMedida, precio, cantidad, descuento, costo,
                                        stock, idUnidadMedida, cantInput, cantTipo, tipoOperacion, tipoVenta,
                                        editarPrecio, 2, tipoMoneda, gratuitoPro);
                                } else {
                                    alert("Insuficiente stock de este producto");
                                }
                            }
                        } else {
                            alert('Configurar cantidad para este tipo de venta');
                        }
                    }

                }
                if ($('#chkxMayor-' + id).is(':checked')) {
                    if (arrayIdPorMayor.includes(parseInt(id)) == true) {
                        alert("Producto ya agregado, por favor de modificar la cantidad si desea agregar más");
                    } else {
                        var opcion = $('#ventasMayor-' + id).val();
                        if (opcion == null) {
                            alert('Configurar cantidad para realizar ventas por mayor');
                        } else {
                            var desc = $('#ventasMayor-' + id + ' option:selected').text();
                            cantidad = desc.substring(4);

                            for (var i = 0; i < jsonStock.length; i++) {
                                if (jsonStock[i]["IdProducto"] == id) {
                                    bandStock = i;
                                }
                            }

                            var porcentajeDesc = '';
                            precio = $('#precioDescuento1-' + id).val();
                            cantInput = cantidad;
                            unidadMedida = 'Por Mayor';
                            var cantTipo = 1;
                            /*if(valorVentaSoles == 1){
                                precio = parseFloat(precio) * parseFloat(valorCambioVentas);
                            }*/
                            if (banderaVentaSolesDolares == 1) {
                                if (tipoMoneda == 1 && idTipoMoneda == 2) {
                                    precio = parseFloat(precio) * parseFloat(valorCambioVentas);
                                }
                                if (tipoMoneda == 2 && idTipoMoneda == 1) {
                                    precio = parseFloat(precio) / parseFloat(valorCambioCompras);
                                }
                            }
                            if (tipoVenta == 2) {
                                precio = parseFloat(precio / 1.18);
                            }

                            if (bandStock != -1) {
                                jsonStock[bandStock].StockInicial = parseFloat(jsonStock[bandStock].StockInicial) -
                                    parseFloat(cantidad);
                                jsonStock[bandStock].Stock = parseFloat(jsonStock[bandStock].Stock) + parseFloat(cantidad);
                                if (parseFloat(stock) < parseFloat(jsonStock[bandStock].Stock)) {
                                    jsonStock[bandStock].Stock = parseFloat(jsonStock[bandStock].Stock) - parseFloat(
                                        cantidad);
                                    jsonStock[bandStock].StockInicial = parseFloat(jsonStock[bandStock].StockInicial) +
                                        parseFloat(cantidad);
                                    alert("Insuficiente stock de este producto");
                                } else {
                                    productoEnTabla(id, descripcion, unidadMedida, precio, cantidad, descuento, costo,
                                        stock, idUnidadMedida, cantInput, cantTipo, tipoOperacion, tipoVenta,
                                        editarPrecio, 3, tipoMoneda, gratuitoPro);
                                }
                            } else {
                                if (parseInt(cantidad, 10) <= parseInt(stock, 10)) {
                                    datosProductos = new Array();
                                    datosProductos.IdProducto = id;
                                    datosProductos.StockInicial = parseInt(stock, 10) - parseInt(cantidad, 10);
                                    datosProductos.Stock = parseInt(cantidad, 10);
                                    jsonStock.push(datosProductos);
                                    productoEnTabla(id, descripcion, unidadMedida, precio, cantidad, descuento, costo,
                                        stock, idUnidadMedida, cantInput, cantTipo, tipoOperacion, tipoVenta,
                                        editarPrecio, 3, tipoMoneda, gratuitoPro);
                                } else {
                                    alert("Insuficiente stock de este producto");
                                }
                            }

                        }
                    }
                }
            }
        }

        function productoEnTabla(id, descripcion, unidadMedida, precio, cantidad, descuento, costo, stock, idUnidadMedida,
            cantInput, cantTipo, tipoOperacion, tipoVenta, editarPrecio, tipo, tipoMoneda, gratuitoPro, ) {
            if (parseFloat(descuento) >= parseFloat(precio)) {
                alert("El descuento tiene que ser menor que el precio");
            } else {

                if (parseFloat(cantidad) > parseFloat(stock)) {
                    alert("Sobrepaso el límite de este artículo en stock");
                } else {
                    $('#total').val('');
                    $('#exonerada').val('');

                    //idUnidadMedida=tipo;
                    var min;
                    var importe = parseFloat(parseFloat(precio) * parseFloat(cantidad));
                    var importeFinal = parseFloat(importe) - parseFloat(descuento);
                    if (tipo == 1 || tipo == 2) {
                        min = 1;
                        cantidad = cantTipo;
                    } else {
                        min = cantidad;
                    }
                    var readonly;
                    if (editarPrecio == 1) {
                        readonly = '';
                    } else {
                        readonly = 'readonly';
                    }
                    var step;
                    var bandInput;
                    var backgroundColor;
                    var readonlyDes;
                    if (idUnidadMedida == 1) {
                        step = '';
                        bandInput = 'false';
                    } else {
                        step = '0.05';
                        bandInput = 'true';
                    }
                    var ganancia = parseFloat(importe) - parseFloat(parseFloat(costo) * parseFloat(cantidad)) - parseFloat(
                        descuento);
                    if (gratuitoPro == 1) {
                        backgroundColor = 'background-color: #d3d3d3';
                        readonlyDes = 'readonly';
                    } else {
                        backgroundColor = 'background-color: none';
                        readonlyDes = '';
                    }
                    var t = $('#tablaAgregado');
                    var fila = '<tr id="row' + iden + '" style="' + backgroundColor + '"><td><input id="pro' + iden +
                        '" name="Codigo[]" readonly type="text" value="PRO-' + id + '" style="width:80px">' +
                        '</td><td id="descrip' + iden + '" style="width:120px">' + descripcion +
                        '</td><td id="detalle' + iden + '"><input name="Detalle[]" type="text" style="width:100px">' +
                        '</td><td id="um' + iden + '" style="width:80px">' + unidadMedida +
                        '</td><td style="width:80px"><input id="prec' + iden +
                        '" name="Precio[]" onchange="calcular(this, ' + iden + ', ' + tipo + ');" min="' + redondeo(
                            precio) + '" type="number" value="' + redondeo(precio) + '" step="any" style="width:80px" ' +
                        readonly + '>' +
                        '</td><td style="width:80px"><input id="desc' + iden +
                        '" name="Descuento[]" class="inputModificarDescuento" data-tipo-moneda="' + tipoMoneda +
                        '" onchange="calcular(this, ' + iden + ', ' +
                        tipo +
                        ');" step="any" min="0" type="number" value="' + redondeo(descuento) + '" style="width:80px" ' +
                        readonlyDes + '>' +
                        '</td><td style="width:80px"><input id="cant' + iden +
                        '" name="Cantidad[]" onchange="calcular(this, ' + iden + ', ' + tipo + ');" type="number" step="' +
                        step + '" min="' + min + '" max="' + stock + '" value="' + cantInput + '" style="width:80px">' +
                        '</td><td style="width:80px"><input id="imp' + iden +
                        '" name="Importe[]" readonly type="number" value="' + redondeo(importeFinal) +
                        '" step="any"  style="width:80px">' +
                        '</td><td hidden><input id="gan' + iden + '" name="Ganancia[]" value="' + ganancia + '">' +
                        '</td><td hidden><input id="unidMed' + iden + '" name="unidMed[]" value="' + idUnidadMedida + '">' +
                        '</td><td hidden><input id="tipo' + iden + '" name="tipo[]" value="' + tipo + '">' +
                        '</td><td hidden><input id="cantTipo' + iden + '" name="cantTipo[]" value="' + cantTipo + '">' +
                        '</td><td hidden><input id="id' + iden + '" name="Id[]" value="' + id + '">' +
                        '</td><td hidden><input id="gratuitos' + iden + '" name="gratuitos[]" value="' + gratuitoPro +
                        '">' +
                        '</td><td style="width:80px"><input id="text' + iden + '" type="hidden" name="TextUni[]" value="' +
                        unidadMedida + '"/><button id="btn' + iden + '" onclick="quitar(' + iden + ',' + id + ', ' + tipo +
                        ')" class="btn btn-primary" style="width:40px"><i class="list-icon material-icons fs-16">clear</i></button>' +
                        '</td>' +
                        '</tr>';
                    $('#tablaAgregado tr:last').after(fila);
                    iden++;

                    if (tipoVenta == 1) {
                        if (gratuitoPro == 1) {
                            $('#opGratuita').val('');
                            opGratuita += importeFinal;
                        } else {
                            $('#subtotal').val('');
                            $('#igv').val('');
                            var igv = parseFloat((18 / 100) + 1);
                            total += parseFloat(importeFinal);
                            subtotal = parseFloat(total) / parseFloat(igv);
                            igvTotal = parseFloat(total) - parseFloat(subtotal);
                            exonerada += parseFloat(descuento);
                        }

                        $('#subtotal').val(redondeo(subtotal));
                        $('#opExonerado').val(redondeo(opExonerado));
                        $('#opGratuita').val(redondeo(opGratuita));
                        $('#igv').val(redondeo(igvTotal));
                        $('#exonerada').val(redondeo(exonerada));
                        $('#total').val(redondeo(total));

                    } else {
                        if (gratuitoPro == 1) {
                            $('#opGratuita').val('');
                            opGratuita += importeFinal;
                        } else {
                            $('#opExonerado').val('');
                            total += parseFloat(importeFinal);
                            opExonerado = parseFloat(total);
                            exonerada += parseFloat(descuento);
                        }

                        $('#subtotal').val(redondeo(subtotal));
                        $('#opExonerado').val(redondeo(opExonerado));
                        $('#opGratuita').val(redondeo(opGratuita));
                        $('#igv').val(redondeo(igvTotal));
                        $('#exonerada').val(redondeo(exonerada));
                        $('#total').val(redondeo(total));

                    }

                    var tipoDocumento = $("#selectTipoComp").val();
                    if (tipoMoneda == 1) {
                        if (total > 2000 && parseFloat(tipoDocumento) < 3) {
                            $('#textoVentaExcedido').show();
                        } else {
                            $('#textoVentaExcedido').hide();
                        }
                    } else {
                        if (total > 500 && parseFloat(tipoDocumento) < 3) {
                            $('#textoVentaExcedido').show();
                        } else {
                            $('#textoVentaExcedido').hide();
                        }
                    }

                    //var tipoDocumento = $("#selectTipoComp").val();
                    //var tipoPago = $("#tipoPago").val();

                    if (tipoMoneda == 1) {
                        var totalDetraccion = total;
                    } else {
                        var valorCambioVentas = $("#valorCambioVentas").val();
                        var totalDetraccion = parseFloat(total * valorCambioVentas);
                    }

                    manejarBotones(totalDetraccion, tipoDocumento, tipoVenta, banderaServicio);


                    if (tipo == 1) {
                        arrayIds.push(parseInt(id));
                    } else if (tipo == 2) {
                        arrayIdTipoVenta.push(parseInt(id));
                    } else {
                        arrayIdPorMayor.push(parseInt(id));
                    }
                }
            }
        }

        function agregarServicio(id) {
            if (arrayIds.includes(id) == true) {
                alert("Producto ya agregado, por favor de modificar la cantidad si desea agregar más");
            } else {
                var valorCambioVentas = $("#valorCambioVentas").val();
                var valorCambioCompras = $("#valorCambioCompras").val();
                var banderaVentaSolesDolares = $("#banderaVentaSolesDolares").val();
                var tipoMoneda = $("#tipoMoneda").val();
                if ((banderaVentaSolesDolares == 1 || tipoMoneda == 2) && (parseFloat(valorCambioVentas) == 0 || parseFloat(
                        valorCambioCompras) == 0)) {
                    $(".bs-modal-lg-productos-soles").modal("hide");
                    $(".bs-modal-lg-productos-dolares").modal("hide");
                    $("#tipoCambio").modal("show");
                } else {
                    banderaServicio = banderaServicio + 1;
                    $('#total').val('');
                    $('#exonerada').val('');
                    var descripcion = $('#s1-' + id).text();
                    var unidadMedida = 'ZZ';
                    var precio = $('#s2-' + id).text();
                    var cantidad = $('#s5-' + id).val();
                    var descuento = $('#s3-' + id).val();
                    var costo = $('#s4-' + id).val();
                    var idTipoMoneda = $('#s7-' + id).val();
                    var tipoVenta = $('#tipoVenta').val();
                    var editarPrecio = $("#editarPrecio").val();
                    var gratuitoPro;
                    if ($('#switchGratuita-' + id).is(':checked')) {
                        gratuitoPro = 1;
                    } else {
                        gratuitoPro = 0;
                    }
                    //var valorVentaSoles = $("#valorVentaSoles").val();

                    servicioEnTabla(id, descripcion, unidadMedida, precio, cantidad, descuento, costo, tipoVenta,
                        editarPrecio, banderaVentaSolesDolares, valorCambioVentas, valorCambioCompras, idTipoMoneda,
                        gratuitoPro, 0);
                }
            }
        }

        function servicioEnTabla(id, descripcion, unidadMedida, precio, cantidad, descuento, costo, tipoVenta, editarPrecio,
            banderaVentaSolesDolares, valorCambioVentas, valorCambioCompras, idTipoMoneda, gratuitoPro, anticipo) {
            if (parseFloat(descuento) > parseFloat(precio)) {
                alert("El descuento tiene que ser menor que el precio");
            } else {

                var tipoMoneda = $("#tipoMoneda").val();
                if (banderaVentaSolesDolares == 1) {
                    if (tipoMoneda == 1 && idTipoMoneda == 2) {
                        precio = parseFloat(precio) * parseFloat(valorCambioVentas);
                    }
                    if (tipoMoneda == 2 && idTipoMoneda == 1) {
                        precio = parseFloat(precio) / parseFloat(valorCambioCompras);
                    }
                }
                /*if(valorVentaSoles == 1){
                    precio = parseFloat(precio) * parseFloat(valorCambioVentas);
                }*/
                if (tipoVenta == 2) {
                    precio = parseFloat(precio / 1.18);
                }
                var importe = parseFloat(parseFloat(precio) * parseFloat(cantidad, 10));
                var importeFinal = parseFloat(importe) - parseFloat(descuento);
                var ganancia = parseFloat(importe) - parseFloat(parseFloat(costo) * parseFloat(cantidad)) - parseFloat(
                    descuento);
                var readonly;
                if (editarPrecio == 1) {
                    readonly = '';
                } else {
                    readonly = 'readonly';
                }
                if (gratuitoPro == 1) {
                    backgroundColor = 'background-color: #d3d3d3';
                    readonlyDes = 'readonly';
                } else {
                    backgroundColor = 'background-color: none';
                    readonlyDes = '';
                }
                if (anticipo == 1) {
                    readonlyAnticipo = 'readonly';
                    readonlyDes = 'readonly';
                } else {
                    readonlyAnticipo = '';
                }
                var t = $('#tablaAgregado');
                $('#tablaAgregado tr:last').after('<tr id="row' + iden + '" style="' + backgroundColor +
                    '"><td><input id="pro' + iden + '" name="Codigo[]" readonly type="text" value="SER-' + id +
                    '" style="width:80px">' +
                    '</td><td id="descrip' + iden + '" style="width:100px">' + descripcion +
                    '</td><td id="detalle' + iden + '"><input name="Detalle[]" type="text" style="width:120px">' +
                    '</td><td id="um' + iden + '" style="width:80px">' + unidadMedida +
                    '</td><td style="width:80px"><input id="prec' + iden +
                    '" name="Precio[]" onchange="calcular(this, ' + iden + ', 4);" step="any" value="' + redondeo(
                        precio) + '" style="width:80px" ' + readonly + '>' +
                    '</td><td style="width:80px"><input id="desc' + iden +
                    '" name="Descuento[]" class="inputModificarDescuento" data-tipo-moneda="' + idTipoMoneda +
                    '" step="any" type="number" onchange="calcular(this, ' +
                    iden +
                    ',4);" min="0" value="' + redondeo(descuento) + '" style="width:80px" ' + readonlyDes + '>' +
                    '</td><td style="width:80px"><input id="cant' + iden +
                    '" name="Cantidad[]" step="any" type="number" onchange="calcular(this, ' + iden +
                    ',4);" min="1" max="9999" value="' + cantidad + '" style="width:80px" ' + readonlyAnticipo + '>' +
                    '</td><td style="width:80px"><input id="imp' + iden +
                    '" name="Importe[]" step="any" readonly type="number" value="' + redondeo(importeFinal) +
                    '" style="width:80px">' +
                    '</td><td id="gan' + iden + '" hidden>' + redondeo(ganancia) +
                    '</td><td hidden><input id="unidMed' + iden + '" name="unidMed[]" value="11">' +
                    '</td><td hidden><input id="tipo' + iden + '" name="tipo[]" value="4">' +
                    '</td><td hidden><input id="cantTipo' + iden + '" name="cantTipo[]" value="1">' +
                    '</td><td hidden><input id="id' + iden + '" name="Id[]" value="' + id + '">' +
                    '</td><td hidden><input id="gratuitos' + iden + '" name="gratuitos[]" value="' + gratuitoPro +
                    '">' +
                    '</td><td style="width:80px"><input id="text' + iden + '" type="hidden" name="TextUni[]" value="' +
                    unidadMedida + '"/><button id="btn' + iden + '" onclick="quitar(' + iden + ',' + id +
                    ',4)" class="btn btn-primary" style="width:40px"><i class="list-icon material-icons fs-16">clear</i></button>' +
                    '</td>' +
                    '</tr>');

                iden++;

                if (tipoVenta == 1) {
                    if (gratuitoPro == 1) {
                        $('#opGratuita').val('');
                        opGratuita += importeFinal;
                    } else {
                        $('#subtotal').val('');
                        $('#igv').val('');
                        var igv = parseFloat((18 / 100) + 1);
                        total += parseFloat(importeFinal);
                        subtotal = parseFloat(total) / parseFloat(igv);
                        igvTotal = parseFloat(total) - parseFloat(subtotal);
                        exonerada += parseFloat(descuento);
                    }

                    $('#subtotal').val(redondeo(subtotal));
                    $('#opExonerado').val(redondeo(opExonerado));
                    $('#opGratuita').val(redondeo(opGratuita));
                    $('#igv').val(redondeo(igvTotal));
                    $('#exonerada').val(redondeo(exonerada));
                    $('#total').val(redondeo(total));
                } else {
                    if (gratuitoPro == 1) {
                        $('#opGratuita').val('');
                        opGratuita += importeFinal;
                    } else {
                        $('#opExonerado').val('');
                        total += parseFloat(importeFinal);
                        opExonerado = parseFloat(total);
                        exonerada += parseFloat(descuento);
                    }

                    $('#subtotal').val(redondeo(subtotal));
                    $('#opExonerado').val(redondeo(opExonerado));
                    $('#opGratuita').val(redondeo(opGratuita));
                    $('#igv').val(redondeo(igvTotal));
                    $('#exonerada').val(redondeo(exonerada));
                    $('#total').val(redondeo(total));
                }
                arrayIds.push(id);

                var tipoDocumento = $("#selectTipoComp").val();
                if (tipoMoneda == 1) {
                    if (total > 2000 && parseFloat(tipoDocumento) < 3) {
                        $('#textoVentaExcedido').show();
                    } else {
                        $('#textoVentaExcedido').hide();
                    }
                } else {
                    if (total > 500 && parseFloat(tipoDocumento) < 3) {
                        $('#textoVentaExcedido').show();
                    } else {
                        $('#textoVentaExcedido').hide();
                    }
                }


                var tipoPago = $("#tipoPago").val();

                if (tipoMoneda == 1) {
                    var totalDetraccion = total;
                } else {
                    var valorCambioVentas = $("#valorCambioVentas").val();
                    var totalDetraccion = parseFloat(total * valorCambioVentas);
                }

                manejarBotones(totalDetraccion, tipoDocumento, tipoVenta, banderaServicio);
                
            }
        }

        function quitar(id, i, tipo) { //agregue una parametro mas el tipo
            var bandStock = -1;
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
            var cantidadTipo = $('#cantTipo' + id).val();
            var tipoMoneda = $("#tipoMoneda").val();
            var gratuitoPro = $('#gratuitos' + id).val();

            if (uniMed == 11) {
                banderaServicio = banderaServicio - 1;
            }

            for (var j = 0; j < jsonStock.length; j++) {
                if (jsonStock[j]["IdProducto"] == i) {
                    bandStock = j;
                }
            }

            if (tipo == 1 || tipo == 3) {
                jsonStock[bandStock].StockInicial = parseFloat(jsonStock[bandStock].StockInicial) + parseFloat(cantidad);
                jsonStock[bandStock].Stock = parseFloat(jsonStock[bandStock].Stock) - parseFloat(cantidad);
            } else if (tipo == 2) {
                //var cantidadTipo = $('#cantidadTipoUnidad-'+i).val();
                cantidad = parseFloat(cantidad) * parseFloat(cantidadTipo)
                jsonStock[bandStock].StockInicial = parseFloat(jsonStock[bandStock].StockInicial) + parseFloat(cantidad);
                jsonStock[bandStock].Stock = parseFloat(jsonStock[bandStock].Stock) - parseFloat(cantidad);
            }

            if (tipoVenta == 1) {
                if (gratuitoPro == 1) {
                    $('#opGratuita').val('');
                    opGratuita -= importeFinal;
                } else {
                    $('#subtotal').val('');
                    $('#igv').val('');

                    var igv = parseFloat((18 / 100) + 1);
                    total -= parseFloat(importeFinal);
                    subtotal = parseFloat(total) / parseFloat(igv);
                    igvTotal = parseFloat(total) - parseFloat(subtotal);
                    exonerada -= parseFloat(descuento);
                }

                $('#subtotal').val(redondeo(subtotal));
                $('#opExonerado').val(redondeo(opExonerado));
                $('#opGratuita').val(redondeo(opGratuita));
                $('#igv').val(redondeo(igvTotal));
                $('#exonerada').val(redondeo(exonerada));
                $('#total').val(redondeo(total));

            } else {
                if (gratuitoPro == 1) {
                    $('#opGratuita').val('');
                    opGratuita -= importeFinal;
                } else {
                    $('#opExonerado').val('');
                    total -= parseFloat(importeFinal);
                    opExonerado = parseFloat(total);
                    exonerada -= parseFloat(descuento);
                }

                $('#subtotal').val(redondeo(subtotal));
                $('#opExonerado').val(redondeo(opExonerado));
                $('#opGratuita').val(redondeo(opGratuita));
                $('#igv').val(redondeo(igvTotal));
                $('#exonerada').val(redondeo(exonerada));
                $('#total').val(redondeo(total));
            }
            $('#row' + id).remove();
            $('#' + id).remove();

            var tipoDocumento = $("#selectTipoComp").val();
            if (tipoMoneda == 1) {
                if (total > 2000 && parseFloat(tipoDocumento) < 3) {
                    $('#textoVentaExcedido').show();
                } else {
                    $('#textoVentaExcedido').hide();
                }
            } else {
                if (total > 500 && parseFloat(tipoDocumento) < 3) {
                    $('#textoVentaExcedido').show();
                } else {
                    $('#textoVentaExcedido').hide();
                }
            }

            //var tipoDocumento = $("#selectTipoComp").val();
            var tipoPago = $("#tipoPago").val();

            if (tipoMoneda == 1) {
                var totalDetraccion = total;
            } else {
                var valorCambioVentas = $("#valorCambioVentas").val();
                var totalDetraccion = parseFloat(total * valorCambioVentas);
            }

            manejarBotones(totalDetraccion, tipoDocumento, tipoVenta, banderaServicio);


            if (tipo == 1 || tipo == 4) {
                var index = arrayIds.indexOf(i);
                if (index > -1) {
                    arrayIds.splice(index, 1);
                }
            } else if (tipo == 2) {
                var indexTipo = arrayIdTipoVenta.indexOf(i);
                if (indexTipo > -1) {
                    arrayIdTipoVenta.splice(indexTipo, 1);
                }
            } else {
                var indexTipo = arrayIdPorMayor.indexOf(i);
                if (indexTipo > -1) {
                    arrayIdPorMayor.splice(indexTipo, 1);
                }
            }
        }

        function agregarArray(datos) {
            var newArray = [];
            if (array.length > 0) {
                /*var bandera = false;
                for(var i=0; i<array.length; i++){
                    if(datos.Id == array[i]["Id"]){
                        bandera = true;
                        array[i]["Cantidad"] += parseInt(datos.Cantidad,10);
                        array[i]["Descuento"] += parseFloat(datos.Descuento);
                        array[i]["Importe"] += parseFloat(datos.Importe);
                        array[i]["Ganancia"] += parseFloat(datos.Ganancia);
                        $('#'+datos.Id).replaceWith('<div id="'+datos.Id+'"><input name="_Id[]" value="'+datos.Id+'"></input>'
                                                    +'<input name="_Codigo[]" value="'+datos.Codigo+'"></input>'
                                                    +'<input name="_Cantidad[]" value="'+array[i]["Cantidad"]+'"></input>'
                                                    +'<input name="_Descuento[]" value="'+array[i]["Descuento"]+'"></input>'
                                                    +'<input name="_Importe[]" value="'+array[i]["Importe"]+'"></input>'
                                                    +'<input name="_Ganancia[]" value="'+array[i]["Ganancia"]+'"></input>'
                                                    +'</div>');

                    }
                }
                if(!bandera){*/
                array.push(datos);
                $('#armarArray').append('<div id="' + datos.Row + '"><input name="Id[]" value="' + datos.Id + '"></input>' +
                    '<input name="Ganancia[]" value="' + datos.Ganancia + '"></input>' +
                    '</div>');
                //}
            } else {
                array.push(datos);
                $('#armarArray').append('<div id="' + datos.Row + '"><input name="Id[]" value="' + datos.Id + '"></input>' +
                    '<input name="Ganancia[]" value="' + datos.Ganancia + '"></input>' +
                    '</div>');
            }
        }

        function quitarArray(datos) {

            if (array.length > 0) {
                for (var i = 0; i < array.length; i++) {
                    if (datos.Id == array[i]["Id"] && datos.Tipo == array[i]["Tipo"]) {
                        if (array[i]["Tipo"] == 2) {
                            var cantidadTipo = $('#cantTipo' + datos.Row).val();
                            array[i]["Cantidad"] -= parseInt(cantidadTipo, 10);
                        } else {
                            array[i]["Cantidad"] -= parseInt(datos.Cantidad, 10);
                        }
                        array[i]["Ganancia"] -= parseFloat(datos.Ganancia);
                        $('#' + datos.Row).replaceWith('<div id="' + datos.Row + '"><input name="Id[]" value="' + datos.Id +
                            '"></input>' +
                            '<input id="ganancia' + datos.Row + '" name="Ganancia[]" value="' + array[i]["Ganancia"] +
                            '"></input>' +
                            '</div>');

                        //array.splice(i, 1);
                    }
                    if (array[i]["Cantidad"] == 0) {
                        $('#' + datos.Row).replaceWith('<div id="' + datos.Row + '">');
                    }
                }

            }
        }

        function calcular(idRow, id, tipo) {
            var bandStock = -1;
            var banderita = -1;
            var cuenta = 0;
            var row = idRow.parentNode.parentNode;
            var codigo = row.cells[0].getElementsByTagName('input')[0].value;
            var idPro = codigo.substring(4);
            var precio = row.cells[4].getElementsByTagName('input')[0].value;
            var descuento = row.cells[5].getElementsByTagName('input')[0].value;
            var cantidad = row.cells[6].getElementsByTagName('input')[0].value;

            if (parseFloat(descuento) < 0 || parseFloat(precio) < 0) {
                alert('El Descuento no puede  ser negativo o  menor a cero');
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

            var cantidadTotal = 0;
            var t = 0;
            var cantidad2 = 0;
            var filasT = document.getElementById('tablaAgregado').getElementsByTagName('tr');
            var cantidadTipo = $('#cantTipo' + id).val();


            var stock = $('#cant' + id).attr('max');

            var descuentoMax = parseFloat(precio) * parseFloat(cantidad);

            if (parseFloat(descuentoMax) >= parseFloat(descuento)) {
                //var stock = $('#p7-'+idPro).val();
                for (var i = 0; i < jsonStock.length; i++) {
                    if (jsonStock[i]["IdProducto"] == idPro) {
                        banderita = i;
                    }
                }

                if (tipo == 1 || tipo == 3) {
                    var impTotal = parseFloat((parseFloat(precio) * parseFloat(cantidad)) - parseFloat(descuento));
                    resultadoCalculo(id, codigo, precio, descuento, cantidad, impTotal, ganancia, banderita, idPro, stock,
                        tipoVenta, tipo);
                } else {
                    //var cantidadTipo = $('#cantidadTipoUnidad-'+idPro).val();
                    var impTotal = parseFloat((parseFloat(precio) * parseFloat(cantidad)) - parseFloat(descuento));
                    resultadoCalculo(id, codigo, precio, descuento, cantidad, impTotal, ganancia, banderita, idPro, stock,
                        tipoVenta, tipo);
                }
            } else {
                descuentoMax = (parseFloat(precio) * parseFloat(cantidad)) - parseFloat(0.10);
                $('#desc' + id).val(redondeo(descuentoMax));
                $('#imp' + id).val(0.10);
                var impTotal = parseFloat(0.10);
                resultadoCalculo(id, codigo, precio, descuento, cantidad, impTotal, ganancia, banderita, idPro, stock,
                    tipoVenta, tipo);
            }
        }

        function stopEvento(id) {

            //alert("Insuficiente stock de este producto");
            var filasT = document.getElementById('tablaAgregado').getElementsByTagName('tr');
            filasT[id].cells[6].getElementsByTagName('input')[0].value = 1;
            //alert(pk);
            /* $('#imp'+id).val(parseFloat(0.0));
            				$('#cant'+id).val(parseInt(1));
            				$('#prec'+id).val(redondeo(0.0)); */
            return false;
        }

        function verificar() {
            var filasT = document.getElementById('tablaAgregado').getElementsByTagName('tr');
            var sumador = 0;
            var nombre = '';
            for (var k = 0; k < jsonStock.length; k++) {
                t = 0;
                for (var h = 0; h < filasT.length; h++) {
                    if (t != 0) {
                        var pk = filasT[h].cells[0].getElementsByTagName('input')[0].value;

                        if (pk.indexOf('PRO-' + jsonStock[k].codigo) !== -1) {

                            medida = filasT[h].cells[9].getElementsByTagName('input')[0].value;
                            if (medida != 1) {
                                var cantidadTipo = $('#cantidadTipoUnidad-' + jsonStock[k].codigo).val();

                                fila = filasT[h].cells[6].getElementsByTagName('input')[0].value;
                                fila = parseInt(fila) * cantidadTipo;
                                sumador = sumador + parseInt(fila);
                                nombre = filasT[h].cells[1].innerHTML;

                                /* fila = filasT[h].cells[6].getElementsByTagName('input')[0].value;
                                fila = parseInt(fila) * cantidadTipo;
                                cuenta = cuenta + parseInt(fila); */
                            } else {
                                fila = filasT[h].cells[6].getElementsByTagName('input')[0].value;
                                //fila = parseInt(fila) * cantidadTipo;
                                sumador = sumador + parseInt(fila);
                                nombre = filasT[h].cells[1].innerHTML;

                            }
                            //alert(sumador);
                        }
                    }
                    t++;
                }

                if (sumador > jsonStock[k].stock) {
                    alert("Hay un error  : producto " + nombre + " en el stock ..... el total de Stock es : " + jsonStock[k]
                        .stock + " y uds suma :" + sumador);
                }

                t = 0;
                sumador = 0;
            }
        }

        function resultadoCalculo(id, codigo, precio, descuento, cantidad, impTotal, ganancia, banderita, idPro, stock,
            tipoVenta, tipo) {
            var impAnterior = $('#imp' + id).val();
            $('#imp' + id).val(redondeo(impTotal));
            $('#prec' + id).val(redondeo(precio));
            var tipoMoneda = $("#tipoMoneda").val();

            var filas = $("#tablaAgregado").find("tr");
            var sumTotal = 0;
            var sumDescuento = 0;
            var sumTotalGravada = 0;
            var sumTotalExonerada = 0;
            var sumTotalGratuito = 0;

            if (banderita != -1) {
                jsonStock[banderita]["Stock"] = 0;
                jsonStock[banderita]["StockInicial"] = parseFloat(stock);

                for (i = 1; i < filas.length; i++) { //Recorre las filas 1 a 1
                    var celdas = $(filas[i]).find("td"); //devolverá las celdas de una fila
                    _codigo = $($(celdas[0]).children("input")[0]).val();
                    _descuento = $($(celdas[5]).children("input")[0]).val();
                    _total = $($(celdas[7]).children("input")[0]).val();
                    _cantidad = $($(celdas[6]).children("input")[0]).val();
                    _tipo = $($(celdas[11]).children("input")[0]).val();
                    _gratuito = $($(celdas[13]).children("input")[0]).val();
                    if (_gratuito == 1) {
                        sumTotalGratuito += parseFloat(_total);
                    } else {
                        if (tipoVenta == 1) {
                            sumTotalGravada += parseFloat(_total);
                        } else {
                            sumTotalExonerada += parseFloat(_total);
                        }
                    }

                    sumDescuento += parseFloat(_descuento);

                    if (_tipo != 4) {
                        if (_codigo == codigo) {
                            if (_tipo == 2) {
                                var cantidadTipo = $($(celdas[11]).children("input")[0]).val();
                                _cantidad = parseFloat(_cantidad) * parseFloat(cantidadTipo);
                            }
                            jsonStock[banderita]["Stock"] += parseFloat(_cantidad);
                            jsonStock[banderita]["StockInicial"] -= parseFloat(_cantidad);
                        }
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
                    opGratuita = sumTotalGratuito;

                    $('#subtotal').val(redondeo(subtotal));
                    $('#opExonerado').val(redondeo(opExonerado));
                    $('#opGratuita').val(redondeo(sumTotalGratuito));
                    $('#exonerada').val(redondeo(sumDescuento));
                    $('#igv').val(redondeo(igvTotal));
                    $('#total').val(redondeo(total));

                    var tipoDocumento = $("#selectTipoComp").val();
                    if (tipoMoneda == 1) {
                        if (total > 2000 && parseFloat(tipoDocumento) < 3) {
                            $('#textoVentaExcedido').show();
                        } else {
                            $('#textoVentaExcedido').hide();
                        }
                    } else {
                        if (total > 500 && parseFloat(tipoDocumento) < 3) {
                            $('#textoVentaExcedido').show();
                        } else {
                            $('#textoVentaExcedido').hide();
                        }
                    }

                    var pago = $('#pagoEfec').val();

                    if ($.isNumeric(pago)) {
                        var vuelto = parseFloat(pago) - parseFloat(total);
                        $('#vueltoEfec').val(redondeo(vuelto));
                    }
                } else {
                    if (tipo == 2) {
                        var cantidadTipo = $('#cantTipo' + id).val();
                        cantidad = parseFloat(cantidadTipo);
                        jsonStock[banderita].Stock = parseFloat(jsonStock[banderita].Stock) - parseFloat(cantidad);
                        jsonStock[banderita].StockInicial = parseFloat(jsonStock[banderita].StockInicial) + parseFloat(
                            cantidad);
                        $('#cant' + id).val(parseFloat($('#cant' + id).val()) - 1);
                        $('#imp' + id).val(redondeo(impAnterior));
                    } else {
                        jsonStock[banderita].Stock = parseFloat(jsonStock[banderita].Stock) - 1;
                        jsonStock[banderita].StockInicial = parseFloat(jsonStock[banderita].StockInicial) + 1;
                        $('#cant' + id).val(parseFloat(cantidad) - 1);
                        $('#imp' + id).val(redondeo(impAnterior));
                    }

                    //var row = idRow.parentNode.parentNode;
                    //row.cells[6].getElementsByTagName('input')[0].value = parseInt(cantidad) - parseInt(step,10);
                    alert("Sobrepaso el límite de este artículo en stock");
                }
            } else {

                for (i = 1; i < filas.length; i++) { //Recorre las filas 1 a 1
                    var celdas = $(filas[i]).find("td"); //devolverá las celdas de una fila
                    _codigo = $($(celdas[0]).children("input")[0]).val();
                    _descuento = $($(celdas[5]).children("input")[0]).val();
                    _total = $($(celdas[7]).children("input")[0]).val();
                    _cantidad = $($(celdas[6]).children("input")[0]).val();
                    _tipo = $($(celdas[11]).children("input")[0]).val();
                    _gratuito = $($(celdas[13]).children("input")[0]).val();
                    if (_gratuito == 1) {
                        sumTotalGratuito += parseFloat(_total);
                    } else {
                        if (tipoVenta == 1) {
                            sumTotalGravada += parseFloat(_total);
                        } else {
                            sumTotalExonerada += parseFloat(_total);
                        }
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
                opGratuita = sumTotalGratuito;

                $('#subtotal').val(redondeo(subtotal));
                $('#opExonerado').val(redondeo(opExonerado));
                $('#opGratuita').val(redondeo(sumTotalGratuito));
                $('#exonerada').val(redondeo(sumDescuento));
                $('#igv').val(redondeo(_igvTotal));
                $('#total').val(redondeo(total));

                var tipoDocumento = $("#selectTipoComp").val();
                if (tipoMoneda == 1) {
                    if (total > 2000 && parseFloat(tipoDocumento) < 3) {
                        $('#textoVentaExcedido').show();
                    } else {
                        $('#textoVentaExcedido').hide();
                    }
                } else {
                    if (total > 500 && parseFloat(tipoDocumento) < 3) {
                        $('#textoVentaExcedido').show();
                    } else {
                        $('#textoVentaExcedido').hide();
                    }
                }

                var pago = $('#pagoEfec').val();

                if ($.isNumeric(pago)) {
                    var vuelto = parseFloat(pago) - parseFloat(total);
                    $('#vueltoEfec').val(redondeo(vuelto));
                }
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

            manejarBotones(totalDetraccion, tipoDocumento, tipoVenta, banderaServicio);

            /*if (totalDetraccion >= 700 && tipoDocumento == 2 && tipoVenta == 1) {
                if (banderaServicio > 0) {
                    $('#detraccion').show();
                    $('#retencion').attr("disabled", true).prop("checked", false);
                    $("#switchRetencion").val(0);
                    $('#ventaDetraccion').attr("disabled", false).prop("checked", true);
                    $('#switchDetraccion').val(1);
                } else {
                    $('#detraccion').hide();
                    $('#switchDetraccion').val(0);
                    var switchAnticipo = $("#switchAnticipado").val();
                    $('#ventaDetraccion').attr("disabled", false).prop("checked", false);
                    if (switchAnticipo == 1) {
                        $('#retencion').attr("disabled", true);
                        $("#switchRetencion").val(0);
                    } else {
                        $('#retencion').attr("disabled", false);
                    }
                }
            } else {
                $('#detraccion').hide();
                $('#retencion').attr("disabled", true).prop("checked", false);
                $("#switchRetencion").val(0);
                $('#ventaDetraccion').attr("disabled", true).prop("checked", false);
                $('#switchDetraccion').val(0);
            }*/
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

        function vuelto() {
            var total = $('#total').val();
            var pago = $('#pagoEfec').val();

            if (parseFloat(pago) >= parseFloat(total)) {
                var vuelto = parseFloat(pago) - parseFloat(total);
                $('#vueltoEfec').val(redondeo(vuelto));
            } else {
                $('#vueltoEfec').val('');
            }
        }

        function redondeo(num) {
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

        function bloquearValor(){
            valor = $("#valorDetraccion").val();
            if(valor < 3 || valor > 12){
                alert("El porcentaje de detracción tiene que ser entre el 3 o 12 por ciento");
                $("#valorDetraccion").val(12);
            }
            
        }

        function enviar() {

            /*window.scrollBy(0, -window.innerHeight);
            $.showLoading({
                name:'circle-fade',
            });*/

            //$('#btnGenerar').attr("disabled", true);
            //$('#btnGenerarImprimir').attr("disabled", true);
            $.LoadingOverlay("show", {
                image: '../../assets/img/logo1.png',
                text: 'Espere un momento por favor...',
                imageAnimation: '1.5s fadein',
                textColor: "#f6851a",
                textResizeFactor: '0.3',
                textAutoResize: true
            });

            var idTipoComp = $("#selectTipoComp").val();
            var serie = $("#serie").val();
            var inicioComp = serie.substring(0, 1);
            if ((idTipoComp == 1 && inicioComp == 'B') || (idTipoComp == 2 && inicioComp == 'F') || (idTipoComp == 3 &&
                    inicioComp == 'T')) {
                var cliente = $("#clientes").val();
                var fecha = $("#datepicker").val();
                var numero = $("#numero").val();
                var observacion = $("#observacion").val();
                var subtotal = $("#subtotal").val();
                var opExonerado = $('#opExonerado').val();
                var opGratuita = $('#opGratuita').val();
                var exonerada = $("#exonerada").val();
                var igv = $("#igv").val();
                var total = $("#total").val();
                var tipoPago = $("#tipoPago").val();
                var plazoCredito = $("#_plazoCredito").val();
                var pagoEfectivo = $("#pagoEfec").val();
                var vueltoEfectivo = $("#vueltoEfec").val();
                var tipoTarjeta = $("#tipoTarjeta").val();
                var numTarjeta = $("#numTarjeta").val();
                var pagoTarjeta = $("#pagoTarjeta").val();
                
                var ids = $("input[name='Id[]']").map(function() {
                    return $(this).val();
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
                var gratuitos = $("input[name='gratuitos[]']").map(function() {
                    return $(this).val();
                }).get();
                var tipoMoneda = $("#tipoMoneda").val();
                var montoCuenta = $("#pagoCuenta").val();
                var nroOperacion = $("#nroOperacion").val();
                var cuentaBancaria = $("#cuentaBancaria").val();
                var dateBanco = $("#date").val();
                var tipoVenta = $('#tipoVenta').val();
                var valorCambioVentas = $("#valorCambioVentas").val();
                var valorCambioCompras = $("#valorCambioCompras").val();
                var banderaVentaSolesDolares = $("#banderaVentaSolesDolares").val();
                var valorDetraccion = $("#valorDetraccion").val();
                var bienServicio = $("#bienServicio").val();
                var medioPago = $("#medioPago").val();
                var retencion = $("#switchRetencion").val();
                var anticipo = $("#switchAnticipado").val();
                var detraccion = $("#switchDetraccion").val();
                var placa = $("#placaSelect option:selected").text().trim();
                var idPlaca = $("#placaSelect").val();
                var ordenCompra = $("#ordenCompra").val();
                //var valorVentaSoles = $('#valorVentaSoles').val();

                $.ajax({
                    type: 'post',
                    url: 'realizar-venta',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "idTipoComp": idTipoComp,
                        "cliente": cliente,
                        "fechaEmitida": fecha,
                        "serie": serie,
                        "numero": numero,
                        "observacion": observacion,
                        "subtotal": subtotal,
                        "opExonerado": opExonerado,
                        "opGratuita": opGratuita,
                        "exonerada": exonerada,
                        "igv": igv,
                        "total": total,
                        "tipoPago": tipoPago,
                        "plazoCredito": plazoCredito,
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
                        "TextUnida": textMedidas,
                        "Tipo": tipos,
                        "gratuitos": gratuitos,
                        "TipoMoneda": tipoMoneda,
                        "MontoCuenta": montoCuenta,
                        "nroOperacion": nroOperacion,
                        "CuentaBancaria": cuentaBancaria,
                        "DateBanco": dateBanco,
                        "tipoVenta": tipoVenta,
                        "valorCambioVentas": valorCambioVentas,
                        "valorCambioCompras": valorCambioCompras,
                        "banderaVentaSolesDolares": banderaVentaSolesDolares,
                        "valorDetraccion": valorDetraccion,
                        "bienServicio": bienServicio,
                        "medioPago": medioPago,
                        "detraccion": detraccion,
                        "retencion": retencion,
                        "anticipo": anticipo,
                        "idPlaca": idPlaca,
                        "placa": placa,
                        "ordenCompra": ordenCompra
                    },

                    success: function(data) {
                        $('#btnGenerar').attr("disabled", false);
                        if (data[0] == 'alert1') {
                            alert(data[1]);
                            setTimeout(function() {
                                $.LoadingOverlay("hide");
                            }, 500);
                            //$.hideLoading();
                        } else {
                            if (data[0] == 'alert9') {
                                alert(data[1]);
                                //$.hideLoading();
                                window.location = '../../caja/cierre-caja';
                            } else {
                                if (data[0] == 'alert11') {
                                    alert(data[1]);
                                    setTimeout(function() {
                                        $.LoadingOverlay("hide");
                                    }, 500);
                                    $("#tipoCambio").modal("show");
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
                                            //$.hideLoading();
                                        } else {
                                            if (data[0] == 'verificar') {
                                                alert(data[1]);
                                                //$.hideLoading();
                                                window.location =
                                                    'validar-documento/' + data[2];
                                            } else {
                                                if (data[0] == 'cerrar') {
                                                    alert(data[1]);
                                                    window.location = '/';
                                                } else {
                                                    alert(data[1]);
                                                    //$.hideLoading();
                                                    window.location =
                                                        '../../operaciones/ventas/comprobante-generado/' +
                                                        data[2];
                                                }
                                            }
                                        }
                                    }
                                }
                            } 
                        }
                    }
                });
            } else {
                setTimeout(function() {
                    $.LoadingOverlay("hide");
                }, 500);
                alert('El comprobante electrónico seleccionado no coincide con la Serie, Por favor vuelva a seleccionar');
            }
        }

        function guardaTipoCambio() {
            var tipoCambioCompras = $("#tipoCambioCompras").val();
            var tipoCambioVentas = $("#tipoCambioVentas").val();
            var tipoCambioComprasSunat = $("#tipoCambioComprasSunat").val();
            var tipoCambioVentasSunat = $("#tipoCambioVentasSunat").val();
            var tipoMoneda = $("#tipoMoneda").val();
            var banderaVentaSolesDolares = $("#banderaVentaSolesDolares").val();
            $.ajax({
                type: 'post',
                url: 'guardar-tipo-cambio',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "tipoCambioCompras": tipoCambioCompras,
                    "tipoCambioVentas": tipoCambioVentas,
                    "tipoCambioComprasSunat": tipoCambioComprasSunat,
                    "tipoCambioVentasSunat": tipoCambioVentasSunat
                },
                success: function(data) {
                    //console.log(data);
                    if (data[0] == 'success') {
                        //$("#banderaVentaSolesDolares").val(1);
                        if (banderaVentaSolesDolares == 1) {
                            $('#agregarArticuloSoles').removeAttr('disabled');
                            $('#agregarArticuloDolares').removeAttr('disabled');
                            if (tipoMoneda == 1) {
                                $("#cambioCompras").hide();
                                $("#cambioVentas").show();
                            } else {
                                $("#cambioVentas").hide();
                                $("#cambioCompras").show();
                            }
                        }
                        $("#valorCambioVentas").val(tipoCambioVentas);
                        $("#valorCambioCompras").val(tipoCambioCompras);
                        $("#tipoCambio").modal("hide");
                    }
                    alert(data[1]);
                }
            });
        }

        function cerrarTipoCambio() {
            $("#ventaSolesDolares").prop("checked", false);
            $("#banderaVentaSolesDolares").val(0);
            //alert("cerro modal");
        }

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
            var tipoMoneda = $("#tipoMoneda").val();
            if (codigo != '') {
                $.ajax({
                    type: 'get',
                    url: 'buscar-codigo-producto',
                    data: {
                        'codigoBusqueda': codigo,
                        'tipoMoneda': tipoMoneda
                    },
                    success: function(data) {
                        if (data.length > 0) {
                            if (data.length == 1) {
                                var id = data[0]["IdArticulo"];
                                var descripcion = data[0]["Descripcion"];
                                var unidadMedida = data[0]["UM"];
                                var precio = data[0]["Precio"];
                                var cantidad = 1;
                                var descuento = '0.0';
                                var costo = data[0]["Costo"];
                                var stock = data[0]["Stock"];
                                var exoneracion = data[0]["Exonerado"];
                                var idTipoMoneda = data[0]["IdTipoMoneda"];
                                var valorCambioVentas = $("#valorCambioVentas").val();
                                var valorCambioCompras = $("#valorCambioCompras").val();
                                var editarPrecio = $("#editarPrecio").val();
                                var tipo = data[0]["IdTipo"];
                                var idUnidadMedida = data[0]["IdUnidadMedida"];
                                var select = '';
                                var tipoVenta = $('#tipoVenta').val();
                                var banderaVentaSolesDolares = $("#banderaVentaSolesDolares").val();
                                //var valorVentaSoles = $('#valorVentaSoles').val();
                                if (arrayIds.includes(parseInt(id)) == true) {
                                    alert(
                                        "Producto ya agregado, por favor de modificar la cantidad si desea agregar más"
                                    );
                                    $("#inputBuscarCodigoProductos").val("");
                                } else {
                                    if (stock > 0 || tipo == 2) {
                                        if (tipo == 1) {
                                            for (var i = 0; i < jsonStock.length; i++) {
                                                if (jsonStock[i]["IdProducto"] == id) {
                                                    bandStock = i;
                                                }
                                            }

                                            if (banderaVentaSolesDolares == 1) {
                                                if (tipoMoneda == 1 && idTipoMoneda == 2) {
                                                    precio = parseFloat(precio) * parseFloat(
                                                        valorCambioVentas);
                                                }
                                                if (tipoMoneda == 2 && idTipoMoneda == 1) {
                                                    precio = parseFloat(precio) / parseFloat(
                                                        valorCambioCompras);
                                                }
                                            }

                                            /*if(valorVentaSoles == 1){
                                                precio = parseFloat(precio) * parseFloat(valorCambioVentas);
                                            }*/
                                            if (tipoVenta == 2) {
                                                precio = parseFloat(precio / 1.18);
                                            }
                                            var cantInput = 1;
                                            var cantTipo = 1;
                                            var gratuitoPro = 0;
                                            if (bandStock != -1) {
                                                jsonStock[bandStock].StockInicial = parseFloat(
                                                    jsonStock[bandStock].StockInicial) - 1;
                                                jsonStock[bandStock].Stock = parseFloat(jsonStock[
                                                    bandStock].Stock) + 1;
                                                if (parseFloat(stock) < parseFloat(jsonStock[bandStock]
                                                        .Stock)) {
                                                    jsonStock[bandStock].Stock = parseFloat(jsonStock[
                                                        bandStock].Stock) - 1;
                                                    parseFloat(jsonStock[bandStock].StockInicial) + 1;
                                                    alert("Insuficiente stock de este producto");
                                                } else {
                                                    productoEnTabla(id, descripcion, unidadMedida,
                                                        precio, cantidad, descuento, costo, stock,
                                                        idUnidadMedida, cantInput, cantTipo,
                                                        exoneracion, tipoVenta, editarPrecio, 1,
                                                        tipoMoneda, gratuitoPro);
                                                }
                                            } else {
                                                datosProductos = new Array();
                                                datosProductos.IdProducto = id;
                                                datosProductos.StockInicial = parseInt(stock, 10) - 1;
                                                datosProductos.Stock = 1;
                                                jsonStock.push(datosProductos);
                                                productoEnTabla(id, descripcion, unidadMedida, precio,
                                                    cantidad, descuento, costo, stock,
                                                    idUnidadMedida, cantInput, cantTipo,
                                                    exoneracion, tipoVenta, editarPrecio, 1,
                                                    tipoMoneda, gratuitoPro);
                                            }
                                        } else {
                                            unidadMedida = 'ZZ';
                                            servicioEnTabla(id, descripcion, unidadMedida, precio,
                                                cantidad, descuento, costo, tipoVenta, editarPrecio,
                                                banderaVentaSolesDolares, valorCambioVentas,
                                                valorCambioCompras, idTipoMoneda, 0, 0);
                                        }
                                    } else {
                                        alert("Insuficiente stock de este producto");
                                    }
                                    $("#inputBuscarCodigoProductos").val("");
                                }
                            } else {
                                $("#inputBuscarCodigoProductos").val("");
                                alert(
                                    "Se encontro más de un producto con el mismo código de barras, por favor agregar producto con el botón de AGREGAR"
                                );
                            }
                        } else {
                            $("#inputBuscarCodigoProductos").val("");
                            alert("No se encontro producto");
                        }
                    }
                }); //fin ajax
            } //fin if
        }, 500);
    </script>
    <script>
        $(function() {
            $("#tipoMoneda").on('change', function() {
                var tipoMoneda = $("#tipoMoneda").val();
                var banderaVentaSolesDolares = $("#banderaVentaSolesDolares").val();
                $.ajax({
                    type: 'get',
                    url: 'selects-productos',
                    data: {
                        'tipoMoneda': tipoMoneda
                    },
                    success: function(data) {
                        if (banderaVentaSolesDolares == 1) {
                            if (tipoMoneda == 1) {
                                $("#cambioCompras").hide();
                                $("#cambioVentas").show();
                            } else {
                                $("#cambioVentas").hide();
                                $("#cambioCompras").show();
                            }
                        } else {
                            if (tipoMoneda == 1) {
                                //moneda = 'S/';
                                $('#agregarArticuloSoles').removeAttr('disabled');
                                $('#agregarArticuloDolares').attr('disabled', 'disabled');
                            } else {
                                //moneda = '$';

                                $('#agregarArticuloSoles').attr('disabled', 'disabled');
                                $('#agregarArticuloDolares').removeAttr('disabled');
                                if (data[0].length > 0) {
                                    $("#valorCambioCompras").val(data[0][0][
                                        "TipoCambioCompras"
                                    ]);
                                    $("#valorCambioVentas").val(data[0][0]["TipoCambioVentas"]);
                                } else {
                                    $("#tipoCambio").modal("show");
                                }
                                //$("#tipoCambio").modal("show");
                            }
                        }


                        var cuentas;

                        if (tipoMoneda == 1) {
                            cuentas = <?php echo json_encode($cuentasSoles); ?>;

                        } else {
                            cuentas = <?php echo json_encode($cuentasDolares); ?>;
                        }
                        $('#cuentaBancaria option').remove();
                        $('#cuentaBancaria').append(
                            '<option selected value="0">Seleccione cuenta bancaria</option>'
                        );
                        for (var j = 0; j < cuentas.length; j++) {
                            //$('#contenido').append('<option id="'+galeria[i]["IdAlbum"]+'" value="'+galeria[i]["Titulo"]+'"></option>'); 
                            $('#cuentaBancaria').append('<option value="' + cuentas[j][
                                    "IdBanco"
                                ] + '">' + cuentas[j]["Banco"] + ' - ' +
                                cuentas[j]["NumeroCuenta"] + '</option>');
                        }

                        arrayIds.splice(0, arrayIds.length);
                        arrayIdTipoVenta.splice(0, arrayIdTipoVenta.length);
                        arrayIdPorMayor.splice(0, arrayIdPorMayor.length);
                        $('#tablaAgregado tr:gt(0)').remove();
                        $('#armarArray div').remove();

                        opGratuita = parseFloat(0);
                        opExonerado = parseFloat(0);
                        total = parseFloat(0);
                        igvTotal = parseFloat(0);
                        subtotal = parseFloat(0);
                        exonerada = parseFloat(0);

                        $('#total').val('');
                        $('#exonerada').val('');
                        $('#subtotal').val('');
                        $('#igv').val('');
                        $('#opExonerado').val('');
                        $('#opGratuita').val('');

                        jsonStock = [];

                    }
                });
            });

            $("#tipoVenta").on('change', function() {
                var tipVenta = $("#tipoVenta").val();
                if (tipVenta == 2) {
                    $('#textoAlertaExonerado').show();
                } else {
                    $('#textoAlertaExonerado').hide();
                }
                arrayIds.splice(0, arrayIds.length);
                arrayIdTipoVenta.splice(0, arrayIdTipoVenta.length);
                arrayIdPorMayor.splice(0, arrayIdPorMayor.length);
                $('#tablaAgregado tr:gt(0)').remove();
                $('#armarArray div').remove();

                opGratuita = parseFloat(0);
                opExonerado = parseFloat(0);
                total = parseFloat(0);
                igvTotal = parseFloat(0);
                subtotal = parseFloat(0);
                exonerada = parseFloat(0);

                $('#total').val('');
                $('#exonerada').val('');
                $('#subtotal').val('');
                $('#igv').val('');
                $('#opExonerado').val('');
                $('#opGratuita').val('');

                jsonStock = [];
            });

            $("#categoriaSoles").on('change', function() {
                var textoBusqueda = $("#inputBuscarProductosSoles").val();
                var idCategoria = $("#categoriaSoles").val();
                if ($("#lector2Soles:checked").val()) {
                    sinMarca = 1;
                } else {
                    sinMarca = 0;
                }
                ajaxBuscarInput(textoBusqueda, idCategoria, 1, sinMarca);
            });

            $("#categoriaDolares").on('change', function() {
                var textoBusqueda = $("#inputBuscarProductosDolares").val();
                var idCategoria = $("#categoriaDolares").val();
                if ($("#lector2Dolares:checked").val()) {
                    sinMarca = 1;
                } else {
                    sinMarca = 0;
                }
                ajaxBuscarInput(textoBusqueda, idCategoria, 2, sinMarca);
            });
        });
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
        $('#lector2Soles').click(function() {
            var textoBusqueda = $("#inputBuscarProductosSoles").val();
            var idCategoria = $("#categoriaSoles").val();
            if ($("#lector2Soles:checked").val()) {
                sinMarca = 1;
            } else {
                sinMarca = 0;
            }
            ajaxBuscarInput(textoBusqueda, idCategoria, 1, sinMarca);
        });

        $('#lector2Dolares').click(function() {
            var textoBusqueda = $("#inputBuscarProductosDolares").val();
            var idCategoria = $("#categoriaDolares").val();
            if ($("#lector2Dolares:checked").val()) {
                sinMarca = 1;
            } else {
                sinMarca = 0;
            }
            ajaxBuscarInput(textoBusqueda, idCategoria, 1, sinMarca);
        });

        $("#inputBuscarProductos").keyup(function() {
            ajaxBuscarInput();
        });

        $("#inputBuscarProductosSoles").keyup(function() {
            var textoBusqueda = $("#inputBuscarProductosSoles").val();
            if (textoBusqueda.length > 3 || textoBusqueda.length == 0) {
                var idCategoria = $("#categoriaSoles").val();
                if ($("#lector2Soles:checked").val()) {
                    sinMarca = 1;
                } else {
                    sinMarca = 0;
                }
                ajaxBuscarInput(textoBusqueda, idCategoria, 1, sinMarca);
            }
        });

        $("#inputBuscarProductosDolares").keyup(function() {
            var textoBusqueda = $("#inputBuscarProductosDolares").val();
            if (textoBusqueda.length > 3 || textoBusqueda.length == 0) {
                var idCategoria = $("#categoriaDolares").val();
                if ($("#lector2Dolares:checked").val()) {
                    sinMarca = 1;
                } else {
                    sinMarca = 0;
                }
                ajaxBuscarInput(textoBusqueda, idCategoria, 2, sinMarca);
            }
        });


        function ajaxBuscarInput(textoBusqueda, idCategoria, tipoMoneda, sinMarca) {
            //var idCategoria = $("#categoria").val();
            //var sinMarca = $("#lector2:checked").val();
            //var tipoMoneda = $("#tipoMoneda").val();

            var stock = '';
            var listadoProductos = '';
            var paginasProductos = '';
            ////comienza
            //var select = '';
            var u = "u";
            var t = "t";
            var m = "m";
            ////
            $.ajax({
                type: 'get',
                // url: 'buscar-productos',
                url: "{{ route('articulos.buscar-productos-ajax') }}",
                data: {
                    'textoBuscar': textoBusqueda,
                    'sinMarca': sinMarca,
                    'tipoMoneda': tipoMoneda,
                    'idCategoria': idCategoria
                },
                success: function(data) {
                    //console.log(data);
                    if (tipoMoneda == 1) {
                        listadoProductos = '#listaProductosSoles';
                        paginasProductos = '#paginasProductosSoles';
                    } else {
                        listadoProductos = '#listaProductosDolares';
                        paginasProductos = '#paginasProductosDolares';
                    }

                    $('' + listadoProductos + '').empty();

                    var moneda;
                    if (tipoMoneda == 1) {
                        moneda = 'S/';
                    } else {
                        moneda = '$';
                    }


                    for (var i = 0; i < data["data"].length; i++) {
                        var chbTipounidad = '';
                        var chbPorMayor = '';
                        var select = '';
                        var codigo = '';
                        if (data["data"][i]["Codigo"] != null) {
                            codigo = data["data"][i]["Codigo"];
                        }

                        if (data["data"][i]["Stock"] < 1) {
                            stock =
                                '<a class="bg-info color-white fs-12 disabled" href="javascript:void(0);">Agotado</a>';
                        } else {
                            stock = '<a class="bg-info color-white fs-12" onclick="agregarProducto(' + data[
                                    "data"][i]["IdArticulo"] + ')" href="javascript:void(0);">' +
                                '<i class="list-icon material-icons">add</i>Agregar' +
                                '</a>';
                        }

                        ////////////aqui agrege

                        u = "seleccionarRadio('" + data["data"][i]["IdArticulo"] + "','u', 0)";
                        t = "seleccionarRadio('" + data["data"][i]["IdArticulo"] + "','t', 0)";
                        m = "seleccionarRadio('" + data["data"][i]["IdArticulo"] + "','m', 0)";

                        if (data["data"][i]["CantidadTipo"] > 0) {
                            chbTipounidad = '<div class="radiobox radiobox-primary col-6">' +
                                '<label>' +
                                '<input id="chkTipoUn-' + data["data"][i]["IdArticulo"] + '" onclick="' + t +
                                '" type="radio"><span id="nomProducto-' + data["data"][i]["IdArticulo"] +
                                '" class="label-text">' + data["data"][i]["NombreTipo"] + '</span>' +
                                '<input hidden id="idTipoUnidad-' + data["data"][i]["IdArticulo"] +
                                '" type="text" value="' + data["data"][i]["IdTipoUnidad"] + '">' +
                                '</label>' +
                                '</div>';
                        }

                        if (data["data"][i]["VentaMayor1"] > 0) {
                            chbPorMayor = '<div class="radiobox radiobox-primary col-6">' +
                                '<label>' +
                                '<input id="chkxMayor-' + data["data"][i]["IdArticulo"] + '" onclick="' + m +
                                '" type="radio"><span class="label-text">Por mayor</span>' +
                                '</label>' +
                                '</div>';
                            select = '<option value="1"> <= ' + data["data"][i]["VentaMayor1"] + '</option>';
                        }

                        ///////////////

                        var precioExo = '';
                        if (sucExonerado == 1) {
                            precioExo = '<div class="col-6">' + moneda +
                                '<span id="p2-' + data["data"][i]["IdArticulo"] +
                                '" class="text-danger product-price fs-16">' + redondeo(parseFloat(data["data"][
                                    i
                                ]["Precio"]) / 1.18) + '</span>' +
                                '</div>';
                        }
                        let columnaOperacionGratuita = '';
                        if (opcionOperacionGratuita == 1) {
                            columnaOperacionGratuita = '<div class="col-12">' +
                                '<label for="gratuita">Gratuita</label>' +
                                '<label class="switch p-2">' +
                                '<input id="switchGratuita-' + data["data"][i]["IdArticulo"] +
                                '" type="checkbox" class="switchGratuita">' +
                                '<span class="slider round"></span></div>';
                        }

                        $('' + listadoProductos + '').append('<div class="product col-12 col-md-6">' +
                            '<div class="card-body">' +
                            '<div class="row">' +
                            '<div class="col-6">' +
                            '<span class="fs-16" style="line-height: 1;">' + moneda + '</span>' +
                            '<span id="p2-' + data["data"][i]["IdArticulo"] +
                            '" class="product-price fs-16">' + data["data"][i]["Precio"] + '</span>' +
                            '<span style="display: none" id="precioDescuento-' + data["data"][i][
                                "IdArticulo"
                            ] + '" class="product-price fs-16">' + data["data"][i]["PrecioDescuento1"] +
                            '</span>' +
                            '</div>' +
                            precioExo +
                            '<div class="col-12">' +
                            '<span id="p1-' + data["data"][i]["IdArticulo"] +
                            '" class="product-title font-weight-bold fs-16">' + data["data"][i][
                                "Descripcion"
                            ] + '</span>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<span class="text-success fs-12">' + codigo + '</span>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<span class="text-muted fs-13">' + data["data"][i]["Marca"] + ' / ' + data[
                                "data"][i]["Categoria"] +
                            ' / </span><span class="text-danger fs-13">Stock : ' + data["data"][i][
                                "Stock"
                            ] + '</span>' +
                            '</div>' +
                            columnaOperacionGratuita +
                            '<div class="col-12">' +
                            '<div class="row align-items-center">' +
                            '<div class="radiobox radiobox-primary col-6">' +
                            '<label>' +
                            '<input id="chkUnidad-' + data["data"][i]["IdArticulo"] + '" onclick="' + u +
                            '" type="radio" checked="checked"> <span class="label-text">Unidad</span>' +
                            '</label>' +
                            '</div>' +
                            chbTipounidad +
                            '<div hidden>' +
                            '<input id="cantidadTipoUnidad-' + data["data"][i]["IdArticulo"] + '" value="' +
                            data["data"][i]["CantidadTipo"] + '">' +
                            '<input id="descuentoTipoUnidad-' + data["data"][i]["IdArticulo"] +
                            '" value="' + data["data"][i]["DescuentoTipo"] + '">' +
                            '<input id="precioTipoUnidad-' + data["data"][i]["IdArticulo"] + '" value="' +
                            data["data"][i]["PrecioTipo"] + '">' +
                            '</div>' +
                            chbPorMayor +
                            '<div hidden>' +
                            '<input id="descuento1-' + data["data"][i]["IdArticulo"] + '" value="' + data[
                                "data"][i]["Descuento1"] + '">' +
                            '<input id="precioDescuento1-' + data["data"][i]["IdArticulo"] + '" value="' +
                            data["data"][i]["PrecioDescuento1"] + '">' +
                            '</div>' +
                            '<div class="col-6">' +
                            '<select id="ventasMayor-' + data["data"][i]["IdArticulo"] +
                            '" class="form-control" name="ventaPorMayor" style="display: none">' + select +
                            '</select>' +
                            '</div>' +
                            '</div>' +
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
                            '<label class="col-form-label-sm">Stock </label>' +
                            '<input id="p7-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]
                            ["Stock"] + '" class="form-control text-center"/>' +
                            '</div>' +
                            '</div>' +
                            '<div hidden>' +
                            '<input id="p8-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]
                            ["Exonerado"] + '" class="form-control text-center"/>' +
                            '</div>' +
                            '<div hidden>' +
                            '<input id="p9-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]
                            ["IdTipoMoneda"] + '" class="form-control text-center"/>' +
                            '</div>' +
                            '</div>' +
                            '<div class="card-footer">' +
                            '<div class="product-info col-12">' + stock +
                            '</div>' +
                            '</div>' +
                            '</div>');
                    }

                    $('' + paginasProductos + '').empty();
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
                    $('' + paginasProductos + '').append(concatenacion);
                }

            });
        }

        /*$("#inputBuscarServicios" ).keyup(function() {
            var textoBusqueda = $("#inputBuscarServicios").val();
            var tipoMoneda = $("#tipoMoneda").val();
            $.ajax({
                        type : 'get',
                        // url : 'buscar-servicios',
                        url: "{{ route('articulos.buscar-servicios-ajax') }}",
                        data:{'textoBuscar':textoBusqueda,
                              'tipoMoneda':tipoMoneda
                        },
                        success:function(data){
                            $('#listaServicios').empty();
                            var moneda;
                            
                            if(tipoMoneda == 1){
                                moneda = 'S/';
                            }else{
                                moneda = '$';
                            }
                            for(var i=0; i<data["data"].length; i++){
                                var precioExoDol = '';
                                var codigo = '';
                                if(sucExonerado == 1){
                                    precioExoDol = '<div class="col-6">'+moneda+
                                        '<span  class="text-danger product-price fs-16">'+redondeo(parseFloat(data["data"][i]["Precio"])/1.18)+'</span>'+
                                    '</div>';
                                }
                                if(data["data"][i]["Codigo"] != null){
                                    codigo = data["data"][i]["Codigo"];
                                }
                                $('#listaServicios').append('<div class="product col-12 col-md-6">'+
                                                                    '<div class="card-body">'+
                                                                        '<div class="row">'+
                                                                            '<div class="col-6">'+moneda+
                                                                                '<span id="s2-'+data["data"][i]["IdArticulo"]+'" class="product-price fs-16">'+data["data"][i]["Precio"]+'</span>'+
                                                                            '</div>'+
                                                                            precioExoDol+
                                                                            '<div class="col-12">'+
                                                                                '<span id="s1-'+data["data"][i]["IdArticulo"]+'" class="product-title font-weight-bold fs-16">'+data["data"][i]["Descripcion"]+'</span>'+
                                                                            '</div>'+
                                                                            '<div class="col-12">'+
                                                                                '<span class="text-success fs-12">'+codigo+'</span>'+
                                                                            '</div>'+
                                                                        '</div>'+
                                                                        '<div class="form-group mt-2" hidden>'+
                                                                            '<label class="col-form-label-sm">Cantidad </label>'+
                                                                            '<input id="s5-'+data["data"][i]["IdArticulo"]+'" type="number" min="1" value="1" class="text-center" />'+
                                                                        '</div>'+
                                                                        '<div class="form-group" hidden>'+
                                                                            '<label class="col-form-label-sm">Descuento </label>'+
                                                                            '<input id="s3-'+data["data"][i]["IdArticulo"]+'" value="0.0" class="text-center" />'+
                                                                        '</div>'+
                                                                    '</div>'+
                                                                    '<div class="card-footer">'+
                                                                        '<div class="product-info col-12">'+
                                                                            '<a class="bg-info color-white fs-12" onclick="agregarServicio('+data["data"][i]["IdArticulo"]+')" href="javascript:void(0);">'+
                                                                                '<i class="list-icon material-icons">add</i>Agregar'+
                                                                            '</a>'+
                                                                        '</div>'+
                                                                    '</div>'+
                                                            '</div>');
                            }

                            $('#paginasServicios').empty();
                            var primero = '';
                            var ultimo = '';
                            var anterior = '';
                            var paginas = '';
                            var siguiente = '';
                            if(data["prev_page_url"] !== null){
                                primero = '<li class="page-item"><a class="page-link" href="'+data["first_page_url"]+'" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
                                anterior = '<li class="page-item"><a class="page-link" href="'+data["prev_page_url"]+'" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>';
                            }else{
                                anterior = '<li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a>';
                            }

                            for(var i=data["current_page"] - 2; i<=data["current_page"] + 2; i++){
                                if(i > 0 && i <= data["last_page"]){
                                    if(i == data["current_page"]){
                                        paginas += '<li class="page-item active"><a class="page-link" href="javascript:void(0);">'+i+'</a></li>';
                                    }else{
                                        paginas += '<li class="page-item"><a class="page-link" href="productos?page='+i+'">'+i+'</a></li>';
                                    }
                                }
                            }

                            if(data["next_page_url"] !== null){
                                siguiente = '<li class="page-item"><a class="page-link" href="'+data["next_page_url"]+'" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
                                ultimo = '<li class="page-item"><a class="page-link" href="'+data["last_page_url"]+'" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
                            }else{
                                siguiente = '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
                                ultimo = '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
                            }

                            var concatenacion = primero + anterior + paginas + siguiente + ultimo;
                            $('#paginasServicios').append(concatenacion);
                        }

                    });
        });*/

        $("#inputBuscarServiciosSoles").keyup(function() {
            var textoBusqueda = $("#inputBuscarServiciosSoles").val();
            if (textoBusqueda.length > 3 || textoBusqueda.length == 0) {
                ajaxBuscarServiciosInput(textoBusqueda, 1);
            }
        });

        $("#inputBuscarServiciosDolares").keyup(function() {
            var textoBusqueda = $("#inputBuscarServiciosDolares").val();
            if (textoBusqueda.length > 3 || textoBusqueda.length == 0) {
                ajaxBuscarServiciosInput(textoBusqueda, 2);
            }
        });

        function ajaxBuscarServiciosInput(textoBusqueda, tipoMoneda) {
            $.ajax({
                type: 'get',
                // url: 'buscar-servicios',
                url: "{{ route('articulos.buscar-servicios-ajax') }}",
                data: {
                    'textoBuscar': textoBusqueda,
                    'tipoMoneda': tipoMoneda
                },
                success: function(data) {
                    if (tipoMoneda == 1) {
                        listadoServicios = '#listaServiciosSoles';
                        paginasServicios = '#paginasServiciosSoles';
                    } else {
                        listadoServicios = '#listaServiciosDolares';
                        paginasServicios = '#paginasServiciosDolares';
                    }
                    $('' + listadoServicios + '').empty();
                    var moneda;

                    if (tipoMoneda == 1) {
                        moneda = 'S/';
                    } else {
                        moneda = '$';
                    }
                    for (var i = 0; i < data["data"].length; i++) {
                        var precioExoDol = '';
                        var codigo = '';
                        if (sucExonerado == 1) {
                            precioExoDol = '<div class="col-6">' + moneda +
                                '<span  class="text-danger product-price fs-16">' + redondeo(parseFloat(data[
                                    "data"][i]["Precio"]) / 1.18) + '</span>' +
                                '</div>';
                        }
                        if (data["data"][i]["Codigo"] != null) {
                            codigo = data["data"][i]["Codigo"];
                        }
                        let columnaOperacionGratuita = '';
                        if (opcionOperacionGratuita == 1) {
                            columnaOperacionGratuita = '<div class="col-12">' +
                                '<label for="gratuita">Gratuita</label>' +
                                '<label class="switch p-2">' +
                                '<input id="switchGratuita-' + data["data"][i]["IdArticulo"] +
                                '" type="checkbox" class="switchGratuita">' +
                                '<span class="slider round"></span></div>';
                        }
                        $('' + listadoServicios + '').append('<div class="product col-12 col-md-6">' +
                            '<div class="card-body">' +
                            '<div class="row">' +
                            '<div class="col-6">' + moneda +
                            '<span id="s2-' + data["data"][i]["IdArticulo"] +
                            '" class="product-price fs-16">' + data["data"][i]["Precio"] + '</span>' +
                            '</div>' +
                            precioExoDol +
                            '<div class="col-12">' +
                            '<span id="s1-' + data["data"][i]["IdArticulo"] +
                            '" class="product-title font-weight-bold fs-16">' + data["data"][i][
                                "Descripcion"
                            ] + '</span>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<span class="text-success fs-12">' + codigo + '</span>' +
                            '</div>' +
                            '<div class="col-12">' +
                            columnaOperacionGratuita +
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
                            '<div hidden>' +
                            '<div class="form-group col-12">' +
                            '<label class="col-form-label-sm">Costo</label>' +
                            '<input id="s4-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]
                            ["Costo"] + '" class=" text-center" />' +
                            '</div>' +
                            '</div>' +
                            '<div hidden>' +
                            '<input id="s7-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]
                            ["IdTipoMoneda"] + '" class="form-control text-center"/>' +
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

                    $('' + paginasServicios + '').empty();
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

                    for (var i = data["current_page"] - 2; i <= data["current_page"] + 2; i++) {
                        if (i > 0 && i <= data["last_page"]) {
                            if (i == data["current_page"]) {
                                paginas +=
                                    '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                    i + '</a></li>';
                            } else {
                                paginas += '<li class="page-item"><a class="page-link" href="productos?page=' +
                                    i + '">' + i + '</a></li>';
                            }
                        }
                    }

                    if (data["next_page_url"] !== null) {
                        siguiente = '<li class="page-item"><a class="page-link" href="' + data[
                                "next_page_url"] +
                            '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
                        ultimo = '<li class="page-item"><a class="page-link" href="' + data["last_page_url"] +
                            '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
                    } else {
                        siguiente =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
                        ultimo =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
                    }

                    var concatenacion = primero + anterior + paginas + siguiente + ultimo;
                    $('' + paginasServicios + '').append(concatenacion);
                }

            });
        }

        /*$("#ventaSoles").click( function(){
            if($(this).is(':checked') ){
                if(arrayIds.length > 0){
                    //$("#ventaSoles").prop('checked', false);
                    alert("Si desea hacer venta convertida a Soles necesita quitar los items que ya fueron agregados");
                }else{
                    $.ajax({
                        type : 'get',
                        url : 'verificar-tipo-cambio',
                        success:function(data){
                            if(data.length > 0){
                                $("#valorVentaSoles").val(1);
                                $("#valorCambioVentas").val(data[0]["TipoCambioVentas"]);
                                $("#textoVentaSoles").show();

                                var cuentas = <?php echo json_encode($cuentasSoles); ?>;
                                $('#cuentaBancaria option').remove();
                                $('#cuentaBancaria').append('<option selected value="0">Seleccione cuenta bancaria</option>');
                                for(var j=0; j<cuentas.length; j++){
                                    $('#cuentaBancaria').append('<option value="'+cuentas[j]["IdBanco"]+'">'+cuentas[j]["Banco"]+' - '+cuentas[j]["NumeroCuenta"]+'</option>');     
                                }
                            }else{
                                alert("Primero debe configurar tipo de cambio");
                                window.location='../../administracion/bancos/tipo-cambio';
                            }
                        }
                    });
                }
            }else{
                if(arrayIds.length > 0){
                    //$("#ventaSoles").prop('checked', true);
                    alert("Si desea hacer venta en Dólares necesita quitar los items que ya fueron agregados");
                }else{
                    $("#valorVentaSoles").val(0);
                    $("#valorCambioVentas").val(0);
                    $('#textoVentaSoles').hide();

                    var cuentas = <?php echo json_encode($cuentasDolares); ?>;
                    $('#cuentaBancaria option').remove();
                    $('#cuentaBancaria').append('<option selected value="0">Seleccione cuenta bancaria</option>');
                    for(var j=0; j<cuentas.length; j++){
                        $('#cuentaBancaria').append('<option value="'+cuentas[j]["IdBanco"]+'">'+cuentas[j]["Banco"]+' - '+cuentas[j]["NumeroCuenta"]+'</option>');     
                    }
                }
            }
        });*/

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

        $("#pagoAnticipado").click(function() {

            var idTipoComp = $("#selectTipoComp").val();


            $('#agregarArticuloSoles').attr('disabled', false);
            $('#agregarArticuloDolares').attr('disabled', false);
            $("#ventaSolesDolares").prop("disabled", false);

            arrayIds.splice(0, arrayIds.length);
            arrayIdTipoVenta.splice(0, arrayIdTipoVenta.length);
            arrayIdPorMayor.splice(0, arrayIdPorMayor.length);
            $('#tablaAgregado tr:gt(0)').remove();
            $('#armarArray div').remove();

            opGratuita = parseFloat(0);
            opExonerado = parseFloat(0);
            total = parseFloat(0);
            igvTotal = parseFloat(0);
            subtotal = parseFloat(0);
            exonerada = parseFloat(0);

            $('#total').val('');
            $('#exonerada').val('');
            $('#subtotal').val('');
            $('#igv').val('');
            $('#opExonerado').val('');
            $('#opGratuita').val('');

            jsonStock = [];
            if (idTipoComp != 3) {
                if ($(this).is(':checked')) {
                    $("#tipoPago option[value=1]").prop('selected', true);
                    $('#tipoPago').prop('disabled', 'disabled');
                    $('#plazoCredito').hide();
                    $('#efectivo').show();
                    $('#vuelto').show();
                    $('#tarjeta').show();
                    $('#cuentaCorriente').show();
                    $('#detraccion').hide();
                    $("#switchAnticipado").val(1);
                    $('#agregarArticuloSoles').attr('disabled', 'disabled');
                    $('#agregarArticuloDolares').attr('disabled', 'disabled');
                    var tipoMoneda = $("#tipoMoneda").val();
                    var tipoVenta = $('#tipoVenta').val();
                    var banderaVentaSolesDolares = $("#banderaVentaSolesDolares").val();
                    var valorCambioVentas = $("#valorCambioVentas").val();
                    var valorCambioCompras = $("#valorCambioCompras").val();
                    $("#ventaSolesDolares").prop("disabled", true);

                    //$('#retencion').attr("disabled", true).prop("checked", false);
                    //$("#switchRetencion").val(0);

                    $.ajax({
                        type: 'get',
                        url: 'select-anticipo',
                        data: {
                            'tipoMoneda': tipoMoneda
                        },
                        success: function(data) {
                            servicioEnTabla(data[0][0]["IdArticulo"], data[0][0]["Descripcion"], 'ZZ',
                                0, 1, 0, 0, tipoVenta,
                                1, banderaVentaSolesDolares, valorCambioVentas, valorCambioCompras,
                                tipoMoneda, 0, 1);
                            alert(
                                "Nota: Se generará esta factura con pago Anticipado, ingrese el precio dentro del item"
                            );
                        }
                    });

                } else {
                    $("#switchAnticipado").val(0);
                    $('#tipoPago').prop('disabled', false);

                    //$('#retencion').attr("disabled", false);
                }
            } else {
                alert(
                    "Los Pagos Anticipados solo aplica a Facturas y Boletas"
                );
                $('#pagoAnticipado').attr("disabled", true).prop("checked", false);
                $("#switchAnticipado").val(0);
            }
        });

        $("#ventaSolesDolares").click(function() {
            var tipoMoneda = $("#tipoMoneda").val();
            if ($(this).is(':checked')) {
                $("#banderaVentaSolesDolares").val(1);
                $.ajax({
                    type: 'get',
                    url: 'selects-productos',
                    data: {
                        'tipoMoneda': tipoMoneda
                    },
                    success: function(data) {
                        if (data[0].length > 0) {
                            $('#agregarArticuloSoles').removeAttr('disabled');
                            $('#agregarArticuloDolares').removeAttr('disabled');
                            $("#valorCambioCompras").val(data[0][0]["TipoCambioCompras"]);
                            $("#valorCambioVentas").val(data[0][0]["TipoCambioVentas"]);
                            if (tipoMoneda == 1) {
                                $("#cambioVentas").show();
                                $("#cambioCompras").hide();
                            } else {
                                $("#cambioCompras").show();
                                $("#cambioVentas").hide();
                            }
                        } else {
                            $("#tipoCambio").modal("show");
                        }
                    }
                });
                $('#pagoAnticipado').attr("disabled", true).prop("checked", false);
                $("#switchAnticipado").val(0);
            } else {
                $("#banderaVentaSolesDolares").val(0);
                $("#cambioCompras").hide();
                $("#cambioVentas").hide();
                if (tipoMoneda == 1) {
                    $('#agregarArticuloSoles').removeAttr('disabled');
                    $('#agregarArticuloDolares').attr('disabled', 'disabled');

                } else {
                    $('#agregarArticuloSoles').attr('disabled', 'disabled');
                    $('#agregarArticuloDolares').removeAttr('disabled');
                }
                arrayIds.splice(0, arrayIds.length);
                arrayIdTipoVenta.splice(0, arrayIdTipoVenta.length);
                arrayIdPorMayor.splice(0, arrayIdPorMayor.length);
                $('#tablaAgregado tr:gt(0)').remove();
                $('#armarArray div').remove();

                opGratuita = parseFloat(0);
                opExonerado = parseFloat(0);
                total = parseFloat(0);
                igvTotal = parseFloat(0);
                subtotal = parseFloat(0);
                exonerada = parseFloat(0);

                $('#total').val('');
                $('#exonerada').val('');
                $('#subtotal').val('');
                $('#igv').val('');
                $('#opExonerado').val('');
                $('#opGratuita').val('');

                jsonStock = [];

                $('#pagoAnticipado').attr("disabled", false).prop("checked", false);
            }
        });

        $("#placaVehicular").click(function() {
            $('#placaSelect option').remove();
            var cliente = $('#clientes').val();
            if (cliente != 0) {
                if ($(this).is(':checked')) {
                    var idCliente = $("#clientes").val();
                    $.ajax({
                        type: 'get',
                        url: 'placas-clientes',
                        data: {
                            'idCliente': idCliente
                        },
                        success: function(data) {
                            if (data.length > 0) {
                                $('#placaSelect').append(
                                    '<option value="0">Seleccionar Placa</option>');
                                for (var i = 0; i < data.length; i++) {
                                    $('#placaSelect').append('<option value="' + data[i]["IdVehiculo"] +
                                        '">' + data[i]["PlacaVehiculo"] + '</option>');
                                }
                                $("#selectPlacaView").show();
                                $('#btnPlacaView').show();
                            } else {
                                alert(
                                    "No se encontraron placas vehiculares con este cliente, por favor registre uno"
                                )
                                $('#placaSelect').append(
                                    '<option value="0">Seleccionar Placa</option>');
                                $("#selectPlacaView").show();
                                $('#btnPlacaView').show();
                            }
                        }
                    });
                } else {
                    $("#selectPlacaView").hide();
                    $('#btnPlacaView').hide();
                    $('#placaSelect').append('<option value="0" selected>Seleccionar Placa</option>');
                }
            } else {
                $("#placaVehicular").prop("checked", false);
                alert("Seleccione cliente");
            }
        });
    </script>
    <script>
        function crearCliente() {
            $.showLoading({
                name: 'circle-fade',
            });

            $.ajax({
                type: 'post',
                url: 'crear-cliente',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "nombreComercial": $('#nombre').val(),
                    "razonSocial": $("#razonSocial").val(),
                    "tipoDoc": $("#tipoDoc option:selected").val(),
                    "numDoc": $("#numDoc").val(),
                    "direccion": $("#direccion").val(),
                    "telefono": $("#telefono").val(),
                    "email": $("#email").val(),
                    "departamento": $("#departamento").val(),
                    "provincia": $("#provincia").val(),
                    "distrito": $("#distrito").val()
                },
                success: function(data) {
                    if (data[0]["IdCliente"] == '' || data[0]["IdCliente"] == null) {
                        $('#mensaje').empty();
                        $('#mensaje').append('<div class="alert alert-danger">' +
                            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                            data +
                            '</div>');
                    } else {
                        alert('Se creo cliente correctamente');
                        window.location = '../../operaciones/ventas/realizar-venta';
                    }
                    $.hideLoading();
                }
            });
        }

        function crearVehiculo() {
            $.showLoading({
                name: 'circle-fade',
            });

            $.ajax({
                type: 'post',
                url: 'crear-vehiculo',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "cliente": $('#clientes').val(),
                    "tipoVehiculo": $('#tipoVehiculo').val(),
                    "placa": $("#placa").val(),
                    "placaMoto": $("#placaMoto").val(),
                    "anio": $("#anio option:selected").val(),
                    "color": $("#color").val(),
                    "marca": $("#marca").val(),
                    "modelo": $("#modelo").val(),
                    "tipo": $("#tipo").val()
                },
                success: function(data) {
                    if (data[0] == 'alert') {
                        $('#mensajeVehiculo').append('<div class="alert alert-danger">' + data[1] + '</div>');
                    } else {
                        $('#placaSelect').append('<option value="' + data[1]["IdVehiculo"] + '" selected>' +
                            data[1]["PlacaVehiculo"] + '</option>');
                        $('.bs-modal-lg-placas').modal('hide');
                        alert('Se creo vehiculo correctamente');

                    }

                    $.hideLoading();
                }
            });
        }

        function cargarDatosClientes() {
            var clienteSelect = $("#clientes option:selected").text().trim();
            //alert(clienteSelect);
            $("#clienteSelect").val(clienteSelect);
        }
    </script>
    <script>
        $(document).on('click', '.pagProdSoles a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var textoBusqueda = $('#inputBuscarProductosSoles').val();
            var idCategoria = $("#categoriaSoles").val();
            getProductos(page, textoBusqueda, idCategoria, 1);
        });

        $(document).on('click', '.pagProdDolares a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var textoBusqueda = $('#inputBuscarProductosDolares').val();
            var idCategoria = $("#categoriaDolares").val();
            getProductos(page, textoBusqueda, idCategoria, 2);
        });

        function getProductos(page, textoBusqueda, idCategoria, tipoMoneda) {
            var stock = '';
            //var tipoMoneda = $("#tipoMoneda").val();
            //var idCategoria = $("#categoria").val();
            /////////////////////////////////////aqui agregue

            var u = "u";
            var t = "t";
            var m = "m";
            //////////////////////////////////////////

            $.ajax({
                type: 'get',
                // url: 'productos?page=' + page,
                url: "{{ route('articulos.paginar-productos-ajax') }}?page=" + page,
                data: {
                    'textoBuscar': textoBusqueda,
                    'tipoMoneda': tipoMoneda,
                    'idCategoria': idCategoria
                },
                success: function(data) {
                    //var inicio = data["to"] - 1;
                    var moneda;
                    if (tipoMoneda == 1) {
                        moneda = 'S/';
                    } else {
                        moneda = '$';
                    }
                    if (tipoMoneda == 1) {
                        listadoProductos = '#listaProductosSoles';
                        paginasProductos = '#paginasProductosSoles';
                    } else {
                        listadoProductos = '#listaProductosDolares';
                        paginasProductos = '#paginasProductosDolares';
                    }
                    $('' + listadoProductos + '').empty();
                    for (var i = 0; i < data["data"].length; i++) {
                        var chbTipounidad = '';
                        var chbPorMayor = '';
                        var select = '';
                        var codigo = '';
                        if (data["data"][i]["Codigo"] != null) {
                            codigo = data["data"][i]["Codigo"];
                        }
                        if (data["data"][i]["Stock"] < 1) {
                            stock =
                                '<a class="bg-info color-white fs-12 disabled" href="javascript:void(0);">Agotado</a>';
                        } else {
                            stock = '<a class="bg-info color-white fs-12" onclick="agregarProducto(' + data[
                                    "data"][i]["IdArticulo"] + ')" href="javascript:void(0);">' +
                                '<i class="list-icon material-icons">add</i>Agregar' +
                                '</a>';
                        }
                        ///////////////////////////////////////////////Agrege esto y comente el resto

                        u = "seleccionarRadio('" + data["data"][i]["IdArticulo"] + "','u', 0)";
                        t = "seleccionarRadio('" + data["data"][i]["IdArticulo"] + "','t', 0)";
                        m = "seleccionarRadio('" + data["data"][i]["IdArticulo"] + "','m', 0)";

                        if (data["data"][i]["CantidadTipo"] > 0) {
                            chbTipounidad = '<div class="radiobox radiobox-primary col-6">' +
                                '<label>' +
                                '<input id="chkTipoUn-' + data["data"][i]["IdArticulo"] + '" onclick="' + t +
                                '" type="radio"><span id="nomProducto-' + data["data"][i]["IdArticulo"] +
                                '" class="label-text">' + data["data"][i]["NombreTipo"] + '</span>' +
                                '<input hidden id="idTipoUnidad-' + data["data"][i]["IdArticulo"] +
                                '" type="text" value="' + data["data"][i]["IdTipoUnidad"] + '">' +
                                '</label>' +
                                '</div>';
                        }

                        if (data["data"][i]["VentaMayor1"] > 0) {
                            chbPorMayor = '<div class="radiobox radiobox-primary col-6">' +
                                '<label>' +
                                '<input id="chkxMayor-' + data["data"][i]["IdArticulo"] + '" onclick="' + m +
                                '" type="radio"><span class="label-text">Por mayor</span>' +
                                '</label>' +
                                '</div>';
                            select = '<option value="1"> <= ' + data["data"][i]["VentaMayor1"] + '</option>';
                        }

                        var precioExo = '';
                        if (sucExonerado == 1) {
                            precioExo = '<div class="col-6">' + moneda +
                                '<span id="p2-' + data["data"][i]["IdArticulo"] +
                                '" class="text-danger product-price fs-16">' + redondeo(parseFloat(data["data"][
                                    i
                                ]["Precio"]) / 1.18) + '</span>' +
                                '</div>';
                        }
                        let columnaOperacionGratuita = '';
                        if (opcionOperacionGratuita == 1) {
                            columnaOperacionGratuita = '<div class="col-12">' +
                                '<label for="gratuita">Gratuita</label>' +
                                '<label class="switch p-2">' +
                                '<input id="switchGratuita-' + data["data"][i]["IdArticulo"] +
                                '" type="checkbox" class="switchGratuita">' +
                                '<span class="slider round"></span></div>';
                        }
                        $('' + listadoProductos + '').append('<div class="product col-12 col-md-6">' +
                            '<div class="card-body">' +
                            '<div class="row">' +
                            '<div class="col-6">' +
                            '<span class="fs-16" style="line-height: 1;">' + moneda + '</span>' +
                            '<span id="p2-' + data["data"][i]["IdArticulo"] +
                            '" class="product-price fs-16">' + data["data"][i]["Precio"] + '</span>' +
                            '<span style="display: none" id="precioDescuento-' + data["data"][i][
                                "IdArticulo"
                            ] + '" class="product-price fs-16">' + data["data"][i]["PrecioDescuento1"] +
                            '</span>' +
                            '</div>' +
                            precioExo +
                            '<div class="col-12">' +
                            '<span id="p1-' + data["data"][i]["IdArticulo"] +
                            '" class="product-title font-weight-bold fs-16">' + data["data"][i][
                                "Descripcion"
                            ] + '</span>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<span class="text-success fs-12">' + codigo + '</span>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<span class="text-muted fs-13">' + data["data"][i]["Marca"] + ' / ' + data[
                                "data"][i]["Categoria"] +
                            ' / </span><span class="text-danger fs-13">Stock : ' + data["data"][i][
                                "Stock"
                            ] + '</span>' +
                            '</div>' +
                            columnaOperacionGratuita +
                            '<div class="col-12">' +
                            '<div class="row align-items-center">' +
                            '<div class="radiobox radiobox-primary col-6">' +
                            '<label>' +
                            '<input id="chkUnidad-' + data["data"][i]["IdArticulo"] + '" onclick="' + u +
                            '" type="radio" checked="checked"> <span class="label-text">Unidad</span>' +
                            '<input hidden id="idTipoUnidad-' + data["data"][i]["IdArticulo"] +
                            '" type="text" value="' + data["data"][i]["IdTipoUnidad"] + '">' +
                            '</label>' +
                            '</div>' +
                            chbTipounidad +
                            '<div hidden>' +
                            '<input id="cantidadTipoUnidad-' + data["data"][i]["IdArticulo"] + '" value="' +
                            data["data"][i]["CantidadTipo"] + '">' +
                            '<input id="descuentoTipoUnidad-' + data["data"][i]["IdArticulo"] +
                            '" value="' + data["data"][i]["DescuentoTipo"] + '">' +
                            '<input id="precioTipoUnidad-' + data["data"][i]["IdArticulo"] + '" value="' +
                            data["data"][i]["PrecioTipo"] + '">' +
                            '</div>' +
                            chbPorMayor +
                            '<div hidden>' +
                            '<input id="descuento1-' + data["data"][i]["IdArticulo"] + '" value="' + data[
                                "data"][i]["Descuento1"] + '">' +
                            '<input id="precioDescuento1-' + data["data"][i]["IdArticulo"] + '" value="' +
                            data["data"][i]["PrecioDescuento1"] + '">' +
                            '</div>' +
                            '<div class="col-6">' +
                            '<select id="ventasMayor-' + data["data"][i]["IdArticulo"] +
                            '" class="form-control" name="ventaPorMayor" style="display: none">' + select +
                            '</select>' +
                            '</div>' +
                            '</div>' +
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
                            '<div hidden>' +
                            '<input id="p8-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]
                            ["Exonerado"] + '" class="form-control text-center"/>' +
                            '</div>' +
                            '<div hidden>' +
                            '<input id="p9-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]
                            ["IdTipoMoneda"] + '" class="form-control text-center"/>' +
                            '</div>' +
                            '</div>' +
                            '<div class="card-footer">' +
                            '<div class="product-info col-12">' + stock +
                            '</div>' +
                            '</div>' +
                            '</div>');

                        /*    $('#listaProductos').append('<div class="product col-12 col-md-6">'+
                                                                '<div class="card-body">'+
                                                                    '<div class="row">'+
                                                                        '<div class="col-12">S/'+
                                                                            '<span id="p2-'+data["data"][i]["IdArticulo"]+'" class="product-price fs-16">'+data["data"][i]["Precio"]+'</span>'+
                                                                        '</div>'+
                                                                        '<div class="col-12">'+
                                                                            '<span id="p1-'+data["data"][i]["IdArticulo"]+'" class="product-title font-weight-bold fs-16">'+data["data"][i]["Descripcion"]+'</span>'+
                                                                        '</div>'+
                                                                        '<div class="col-12">'+
                                                                            '<span class="text-muted">'+data["data"][i]["Marca"]+'</span>'+
                                                                        '</div>'+
                                                                    '</div>'+
                                                                    '<input hidden id="p3-'+data["data"][i]["IdArticulo"]+'" value="'+data["data"][i]["UM"]+'"/>'+
                                                                    '<div class="form-group mt-2" hidden>'+
                                                                        '<label class="col-form-label-sm">Cantidad </label>'+
                                                                            '<input id="p4-'+data["data"][i]["IdArticulo"]+'" type="number" min="1" value="1" max="'+data["data"][i]["Stock"]+'" class="text-center" />'+
                                                                    '</div>'+
                                                                    '<div class="form-group" hidden>'+
                                                                        '<label class="col-form-label-sm">Descuento </label>'+
                                                                        '<input id="p5-'+data["data"][i]["IdArticulo"]+'" value="0.0" class="text-center" />'+
                                                                    '</div>'+
                                                                    '<div hidden>'+
                                                                        '<div class="form-group col-12">'+
                                                                            '<label class="col-form-label-sm">Costo</label>'+
                                                                            '<input id="p6-'+data["data"][i]["IdArticulo"]+'" value="'+data["data"][i]["Costo"]+'" class="form-control text-center" />'+
                                                                        '</div>'+
                                                                    '</div>'+
                                                                    '<div hidden>'+
                                                                        '<div class="form-group col-12">'+
                                                                            '<label class="col-form-label-sm">Stock</label>'+
                                                                            '<input id="p7-'+data["data"][i]["IdArticulo"]+'" value="'+data["data"][i]["Stock"]+'" class="form-control text-center"/>'+
                                                                        '</div>'+
                                                                    '</div>'+
                                                                '</div>'+
                                                                '<div class="card-footer">'+
                                                                    '<div class="product-info col-12">'+stock+
                                                                    '</div>'+
                                                                '</div>'+
                                                        '</div>'); */
                    }


                    $('' + paginasProductos + '').empty();
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
                    $('' + paginasProductos + '').append(concatenacion);
                }
            });
        }

        $(document).on('click', '.pagServSoles a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var textoBusqueda = $('#inputBuscarServiciosSoles').val();
            getServicios(page, textoBusqueda, 1);
        });

        $(document).on('click', '.pagServDolares a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var textoBusqueda = $('#inputBuscarServiciosDolares').val();
            getServicios(page, textoBusqueda, 2);
        });

        function getServicios(page, textoBusqueda, tipoMoneda) {
            //var textoBusqueda = $('#inputBuscarServicios').val();
            //var tipoMoneda = $("#tipoMoneda").val();
            $.ajax({
                type: 'get',
                // url: 'servicios?page=' + page,
                url: "{{ route('articulos.paginar-servicios-ajax') }}?page=" + page,
                data: {
                    'textoBuscar': textoBusqueda,
                    'tipoMoneda': tipoMoneda
                },
                success: function(data) {
                    var moneda;
                    if (tipoMoneda == 1) {
                        moneda = 'S/';
                        listadoServicios = '#listaServiciosSoles';
                        paginasServicios = '#paginasServiciosSoles';
                    } else {
                        moneda = '$';
                        listadoServicios = '#listaServiciosDolares';
                        paginasServicios = '#paginasServiciosDolares';
                    }
                    $('' + listadoServicios + '').empty();
                    for (var i = 0; i < data["data"].length; i++) {
                        var precioExoDol = '';
                        var codigo = '';
                        if (sucExonerado == 1) {
                            precioExoDol = '<div class="col-6">' + moneda +
                                '<span  class="text-danger product-price fs-16">' + redondeo(parseFloat(data[
                                    "data"][i]["Precio"]) / 1.18) + '</span>' +
                                '</div>';
                        }
                        if (data["data"][i]["Codigo"] != null) {
                            codigo = data["data"][i]["Codigo"];
                        }
                        let columnaOperacionGratuita = '';
                        if (opcionOperacionGratuita == 1) {
                            columnaOperacionGratuita = '<div class="col-12">' +
                                '<label for="gratuita">Gratuita</label>' +
                                '<label class="switch p-2">' +
                                '<input id="switchGratuita-' + data["data"][i]["IdArticulo"] +
                                '" type="checkbox" class="switchGratuita">' +
                                '<span class="slider round"></span></div>';
                        }
                        $('' + listadoServicios + '').append('<div class="product col-12 col-md-6">' +
                            '<div class="card-body">' +
                            '<div class="row">' +
                            '<div class="col-6">' + moneda +
                            '<span id="s2-' + data["data"][i]["IdArticulo"] +
                            '" class="product-price fs-16">' + data["data"][i]["Precio"] + '</span>' +
                            '</div>' +
                            precioExoDol +
                            '<div class="col-12">' +
                            '<span id="s1-' + data["data"][i]["IdArticulo"] +
                            '" class="product-title font-weight-bold fs-16">' + data["data"][i][
                                "Descripcion"
                            ] + '</span>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<span class="text-success fs-12">' + codigo + '</span>' +
                            '</div>' +
                            columnaOperacionGratuita +
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
                            '<div hidden>' +
                            '<div class="form-group col-12">' +
                            '<label class="col-form-label-sm">Costo</label>' +
                            '<input id="s4-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]
                            ["Costo"] + '" class=" text-center" />' +
                            '</div>' +
                            '</div>' +
                            '<div hidden>' +
                            '<input id="s7-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]
                            ["IdTipoMoneda"] + '" class="form-control text-center"/>' +
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

                    $('' + paginasServicios + '').empty();
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
                    $('' + paginasServicios + '').append(concatenacion);
                }
            });
        }
    </script>
    <script>
        $(function() {
            $('#consultar').on('click', function() {
                var tipDoc = $("#tipoDoc option:selected").val();
                var numdoc = $("#numDoc").val();
                $.ajax({
                    type: 'get',
                    url: 'consultar-clientes',
                    data: {
                        'idDoc': tipDoc,
                        'numDoc': numdoc
                    },
                    success: function(data) {
                        $('#departamento option[value="0"]').prop('selected', true);
                        $('#distrito option').remove();
                        $('#provincia option').remove();

                        if ((data[0]) == 1) {
                            if (tipDoc == 1) {
                                $('#nroDocumento').val(data[2]);
                                $('#razonSocial').val(data[3]);
                                $('#nombreComercial').val("-");
                                $('#direccion').val("-");
                                $('#condicion').text("-");
                                $('#estado').text("-");
                            }
                            if (tipDoc == 2) {
                                //$('#tipoDocumento').val(data[1]);
                                $('#nroDocumento').val(data[2]);
                                $('#razonSocial').val(data[3]);
                                $('#nombreComercial').val(data[4]);
                                $('#direccion').val(data[5]);
                                $('#condicion').text(data[9]);
                                $('#estado').text(data[10]);
                                if (data[6] != null) {
                                    $('#departamento option[value="' + data[6] + '"]').prop(
                                        'selected', true);

                                    $('#provincia').append('<option value="0">-</option>');
                                    for (var i = 0; i < data[7][1].length; i++) {
                                        if (data[7][1][i]["IdProvincia"] == data[7][0]) {
                                            $('#provincia').append('<option selected value="' +
                                                data[7][1][i]["IdProvincia"] + '">' + data[
                                                    7][1][i]["Nombre"] + '</option>');
                                        } else {
                                            $('#provincia').append('<option value="' + data[7][
                                                    1
                                                ][i]["IdProvincia"] + '">' + data[7][1]
                                                [i]["Nombre"] + '</option>');
                                        }
                                    }

                                    $('#distrito').append('<option value="0">-</option>');
                                    for (var j = 0; j < data[8][1].length; j++) {
                                        if (data[8][1][j]["IdDistrito"] == data[8][0]) {
                                            $('#distrito').append('<option selected value="' +
                                                data[8][1][j]["IdDistrito"] + '">' + data[8]
                                                [1][j]["Nombre"] + '</option>');
                                        } else {
                                            $('#distrito').append('<option value="' + data[8][1]
                                                [j]["IdDistrito"] + '">' + data[8][1][j][
                                                    "Nombre"
                                                ] + '</option>');
                                        }
                                    }
                                }
                            }
                            if (tipDoc == 3) {
                                alert("El Servicio no funciona para Pasaportes");
                            }
                        } else {
                            $('#nombreComercial').val("");
                            $('#razonSocial').val("");
                            $('#direccion').val("");
                            $('#condicion').text("-");
                            $('#estado').text("-");
                            alert(data[3]);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert("servicio no disponible");
                    }
                });
            });

        });
    </script>

    <script>
        $(function() {
            var ventaRapida = <?php echo json_encode($ventaRapida); ?>;
            if (ventaRapida > 0) {
                $('#inputBuscarCodigoProductos').show();
                $('#inputBuscarCodigoProductos').focus();
                if(ventaRapida == 2){
                    $('#ordenCompraActivo').show();
                }
            } else {
                $('#inputBuscarCodigoProductos').hide();
            }

            $('#lector').on('click', function() {
                var band_lector = $("#selectTipoComp").val();

                // modifique esta parte .....
                //if(typeof num == 'number')
                if (!isNaN(band_lector) && band_lector > 0) {
                    if ($(this).is(':checked')) {
                        //$('#inputBuscarProductos').hide();
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
        });
    </script>
    <script>
        $(function() {
            $("#departamento").on('change', function() {
                var departamento = $("#departamento").val();

                $.ajax({
                    type: 'get',
                    url: 'consultar-provincias',
                    data: {
                        'departamento': departamento
                    },
                    success: function(data) {
                        if (data.length > 0) {
                            $('#distrito option').remove();
                            $('#provincia option').remove();
                            $('#distrito').append('<option value="0">-</option>');
                            $('#provincia').append('<option value="0">-</option>');
                            for (var i = 0; i < data.length; i++) {
                                //$('#contenido').append('<option id="'+galeria[i]["IdAlbum"]+'" value="'+galeria[i]["Titulo"]+'"></option>');
                                $('#provincia').append('<option value="' + data[i][
                                        "IdProvincia"
                                    ] + '">' + data[i]["Nombre"] +
                                    '</option>');
                            }
                        } else {
                            $('#provincia option').remove();
                            $('#distrito option').remove();
                        }
                    }
                });
            });

            $("#provincia").on('change', function() {
                var provincia = $("#provincia").val();
                $.ajax({
                    type: 'get',
                    url: 'consultar-distritos',
                    data: {
                        'provincia': provincia
                    },
                    success: function(data) {
                        if (data.length > 0) {
                            $('#distrito option').remove();
                            $('#distrito').append('<option value="0">-</option>');
                            for (var i = 0; i < data.length; i++) {
                                //$('#contenido').append('<option id="'+galeria[i]["IdAlbum"]+'" value="'+galeria[i]["Titulo"]+'"></option>');
                                $('#distrito').append('<option value="' + data[i][
                                    "IdDistrito"
                                ] + '">' + data[i]["Nombre"] + '</option>');
                            }
                        } else {
                            $('#distrito option').remove();
                        }
                    }
                });
            });
        });
    </script>
    <script>
        $(function() {
            $("#moto").hide();
            $('#placa').keyup(function() {
                var val = this.value.replace(/[^a-zA-Z0-9]/g, '')
                var newVal = '';
                while (val.length > 3) {
                    newVal += val.substr(0, 3) + '-';
                    val = val.substr(3);
                }
                newVal += val;
                this.value = newVal;
            });

            $("#tipoVehiculo").on('change', function() {
                var valor = $("#tipoVehiculo").val();
                if (valor == "1") {
                    $("#auto").show();
                    $("#moto").hide();
                } else {
                    $("#auto").hide();
                    $("#moto").show();
                }
            });
        });
    </script>
</body>

</html>
