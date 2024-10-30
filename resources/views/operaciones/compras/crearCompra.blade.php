<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/center-circle.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/scriptCompras.js') }}">
    <link href="{{ asset('assets/css/loading.min.css') }}" rel="stylesheet" type="text/css">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Generar Compra</title>
    <!-- CSS -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/material-icons/material-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/mono-social-icons/monosocialiconsfont.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('assets/vendors/feather-icons/feather.css') }}" rel="stylesheet" type="text/css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.css" rel="stylesheet"
        type="text/css">
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
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" type="text/css">
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
                    <br />
                    <div class="row page-title clearfix">
                        <div class="page-title-left">
                            <h6 class="page-title-heading mr-0 mr-r-5">Generar Compra</h6>
                        </div>
                        <!-- /.page-title-left -->
                    </div>
                    {{-- <div class="row">
                        <div class="col">
                            <div id="mensaje">
                            </div>
                        </div>
                    </div> --}}
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
                                            'url' => '/operaciones/compras',
                                            'method' => 'POST',
                                            'files' => true,
                                            'class' => 'form-material',
                                        ]) !!}
                                        {{ csrf_field() }}-->
                                        <div class="row">
                                            <div class="col-12 col-md-4">
                                                <div class="form-group">
                                                    <select id="selectTipoComp" class="form-control"
                                                        name="tipoComprobante">
                                                        <option value="0">-</option>
                                                        @foreach ($tipoComprobante as $tipCom)
                                                            <option value="{{ $tipCom->IdTipoComprobante }}">
                                                                {{ $tipCom->Descripcion }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label>Tipo Comprobante</label>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><label>Serie</label></div>
                                                        </div>
                                                        <input id="serie" class="form-control" placeholder="Serie"
                                                            type="text" name="serie">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><label>Número</label></div>
                                                        </div>
                                                        <input id="numero" class="form-control"
                                                            placeholder="Número" name="numero" type="number"
                                                            onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Proveedor</label>
                                                    <select class="m-b-10 form-control select2-hidden-accessible"
                                                        id="proveedores" name="proveedor" data-placeholder="Choose"
                                                        data-toggle="select2" tabindex="-1" aria-hidden="true">
                                                        <option value="0">-</option>
                                                    </select>
                                                    <small class="text-muted"><strong>Seleccione el
                                                            Proveedor</strong></small>
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
                                                    <label>Moneda</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    {{-- <div class="input-group">
                                                        <input id="datepicker" readonly type="text"
                                                            class="form-control" name="fechaEmitida"
                                                            value="{{ $fecha }}" required>
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><i
                                                                    class="list-icon material-icons">date_range</i>
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                                    {{-- Nuevo codigo --}}
                                                    <div class="input-group">
                                                        <input id="datepicker" type="text"
                                                            class="form-control datepicker " name="fechaEmitida"
                                                            data-plugin-options='{"autoclose": true, "format": "dd-mm-yyyy"}'
                                                            autocomplete="off" onkeydown="return false"
                                                            data-date-end-date="0d" value="{{ $fecha }}"
                                                            required>
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><i
                                                                    class="list-icon material-icons">date_range</i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- Fin --}}
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                @if ($exonerado == 1 && $sucExonerado == 1)
                                                    <select id="tipoCompra" class="form-control" name="tipoCompra">
                                                        <option value="1">Venta Op. Gravada</option>
                                                        <option value="2" selected>Venta Op. Exonerada</option>
                                                    </select>
                                                @else
                                                    <input id="tipoCompra" type="text" name="tipoCompra"
                                                        value="1" hidden>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="dropdown mt-2 mb-2">
                                                    {{-- disabled --}}
                                                    <button id="agregarArticulo" class="btn btn-info ripple"
                                                        type="button" data-toggle="modal"
                                                        data-target=".bs-modal-lg-productos"><i
                                                            class="list-icon material-icons">add_circle</i> Agregar
                                                        <span class="caret"></span>
                                                    </button>
                                                    <!-- /.dropdown-menu -->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <div id="armarArray" hidden>
                                                </div>
                                                <table id="tablaAgregado" class="table table-responsive-lg"
                                                    style="width:100%">
                                                    <thead>
                                                        <tr class="bg-primary-contrast">
                                                            <th scope="col" data-tablesaw-priority="persist">Código
                                                            </th>
                                                            <th scope="col">Descripción</th>
                                                            <th scope="col">Und/Medida</th>
                                                            <th scope="col">Precio Venta</th>
                                                            <th scope="col">Precio Costo</th>
                                                            <th scope="col">Cantidad</th>
                                                            <th scope="col">Importe</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-lg-8 col-md-12">
                                                <div class="form-group">
                                                    <textarea id="observacion" class="form-control" rows="4" name="observacion"></textarea>
                                                    <label>Observación</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-8">
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>Op Gravada:</label>
                                                    </div>
                                                    <div class="col-lg-5 col-8">
                                                        <input id="opGravada" name="subtotal" type="text"
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
                                        <br> <br>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <select class="form-control" id="tipoPago" name="tipoPago">
                                                        <option value="1">Contado</option>
                                                        <option value="2">Crédito</option>
                                                    </select>
                                                    <label>Forma de Pago</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div id="plazoCredito" class="form-group">
                                                    <label class="form-control-label">Dias</label>
                                                    <div class="input-group">
                                                        <input id="_plazoCredito" type="number" step="any"
                                                            class="form-control" name="plazoCredito" value="1"
                                                            min="1">
                                                    </div>
                                                </div>
                                                <div id="interes" class="form-group">
                                                    <label class="form-control-label">Interés (%)</label>
                                                    <div class="input-group">
                                                        <input id="_interes" type="number" step="any"
                                                            class="form-control" name="interes" min="0">
                                                    </div>
                                                </div>
                                                <div id="efectivo" class="form-group">
                                                    <label class="form-control-label">Monto (Efectivo)</label>
                                                    <div class="input-group">
                                                        <input id="montoEfectivo" type="number" step="any"
                                                            class="form-control" name="montoEfec" value="0.00">
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="cuentaCorriente" class="col-md-4">
                                                <div class="form-group">
                                                    <select class="form-control" id="cuentaBancaria"
                                                        name="cuentaBancaria">
                                                        <option value="0">Seleccione cuenta bancaria</option>
                                                        @foreach ($cuentasSoles as $banco)
                                                            <option value="{{ $banco->IdBanco }}">
                                                                {{ $banco->Banco }}
                                                                - {{ $banco->NumeroCuenta }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label>Cuenta Bancaria</label>
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        style="font-size: .75em;top: 0;opacity: 1;font-weight: 700;">FECHA
                                                        ABONO DEPÓSITO</label>
                                                    <div class="input-group">
                                                        <input id="fechaDepositoCompra" type="text"
                                                            class="form-control datepicker" name="fechaDepositoCompra"
                                                            data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                            data-date-end-date="0d" autocomplete="off"
                                                            onkeydown="return false" value="{{ $fecha }}"
                                                            disabled>
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
                                                            class="form-control" name="montoCuenta" value="0.00"
                                                            disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <input hidden type="text" id="valorCambio" name="valorCambio"
                                                class="form-control" value="0">
                                        </div>
                                        {{-- seccion Registrar Egreso --}}
                                        @if ($caja != null)
                                            @include('operaciones.compras._registrarEgresoCaja')
                                        @endif
                                        {{-- Fin --}}
                                        <div
                                            class="form-actions btn-list mt-3 d-flex justify-content-md-end justify-content-center  flex-wrap">
                                            <button id="btnCompraPendiente" class="btn btn--verde guardarCompra"
                                                value="Pendiente" data-estado-compra='Pendiente'
                                                type="submit">Guardar
                                                Compra Como Pendiente</button>

                                            <button id="btnCompraFinalizada" class="btn btn--celeste guardarCompra"
                                                value="Finalizado" data-estado-compra='Registrado'
                                                type="submit">Finalizar
                                                Compra</button>
                                        </div>
                                        <div class="col-12 text-center text-md-right mt-4">
                                            <p class="fs-15">*Las compras Pendientes se guardarán Temporalmente <span
                                                    class="font-weight-bold"> SIN AFECTAR EL STOCK </span> hasta
                                                Finalizarlo</p>
                                        </div>
                                        <!--{!! Form::close() !!}-->
                                    </div>
                                    <div class="modal fade bs-modal-lg-productos" role="dialog"
                                        aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <h6 class="modal-title" id="myLargeModalLabel">Listado de
                                                        Productos</h6>
                                                    <div class="widget-body clearfix">
                                                        <div class="row">
                                                            <div class="col-12 col-md-6">
                                                                <select class="form-control select2-hidden-accessible"
                                                                    id="categoria" name="categoria"
                                                                    data-placeholder="Choose" tabindex="-1"
                                                                    data-toggle="select2" aria-hidden="true">
                                                                    <option value="0">Seleccionar Categoría
                                                                    </option>
                                                                    @foreach ($categorias as $categoria)
                                                                        <option value="{{ $categoria->IdCategoria }}">
                                                                            {{ $categoria->Nombre }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group mt-2">
                                                            <i
                                                                class="feather feather-search pos-absolute pos-right vertical-center pr-4"></i>
                                                            <input type="search" id="inputBuscarProductos"
                                                                name="textoBuscar" placeholder="Buscar producto..."
                                                                class="form-control form-control-rounded heading-font-family fs-16 pr-5 pl-3">
                                                        </div>
                                                        <!-- Products List -->
                                                        <div id="listaProductos"
                                                            class="ecommerce-products list-unstyled row">
                                                            @foreach ($productos as $producto)
                                                                <div class="product col-12 col-md-6">
                                                                    <div class="card">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-12 text-center">
                                                                                    <span
                                                                                        id="p1-{{ $producto->IdArticulo }}"
                                                                                        class="product-title font-weight-bold fs-16">{{ $producto->Descripcion }}</span>
                                                                                    <hr>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <label
                                                                                        class="col-form-label-sm">Precio
                                                                                        Costo</label>
                                                                                    <section class="d-flex">
                                                                                        <span class="fs-22"
                                                                                            style="line-height: 2;">S/</span>
                                                                                        <input
                                                                                            id="p2-{{ $producto->IdArticulo }}"
                                                                                            class="form-control product-price fs-16"
                                                                                            value="{{ $producto->Costo }}"
                                                                                            type="number"
                                                                                            step="any" />
                                                                                    </section>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <span
                                                                                        class="text-muted">{{ $producto->Marca }}</span>
                                                                                </div>
                                                                            </div>

                                                                            <input hidden
                                                                                id="p3-{{ $producto->IdArticulo }}"
                                                                                value="{{ $producto->UM }}" />

                                                                            <div class="form-group col-12" hidden>
                                                                                <label
                                                                                    class="col-form-label-sm">Cantidad</label>
                                                                                <input
                                                                                    id="p4-{{ $producto->IdArticulo }}"
                                                                                    type="number" min="1"
                                                                                    value="1"
                                                                                    class="form-control text-center" />
                                                                            </div>

                                                                            <div class="row my-3">
                                                                                <div class="col-12">
                                                                                    <label
                                                                                        class="col-form-label-sm">Precio
                                                                                        Venta</label>
                                                                                </div>
                                                                                @if ($sucExonerado == 1)
                                                                                    <div class="col-6 text-center">
                                                                                        <input
                                                                                            id="p5-{{ $producto->IdArticulo }}"
                                                                                            value="{{ $producto->Precio }}"
                                                                                            class="form-control text-success text-center"
                                                                                            readonly />
                                                                                        <span
                                                                                            class="badge badge-warning">Con
                                                                                            IGV</span>
                                                                                    </div>
                                                                                    <div class="col-6 text-center">
                                                                                        <input
                                                                                            value="{{ number_format($producto->Precio / 1.18, 2) }}"
                                                                                            class="form-control text-danger text-center"
                                                                                            readonly />
                                                                                        <span
                                                                                            class="badge badge-warning">Sin
                                                                                            IGV</span>
                                                                                    </div>
                                                                                @else
                                                                                    <div class="col-12">
                                                                                        <input
                                                                                            id="p5-{{ $producto->IdArticulo }}"
                                                                                            value="{{ $producto->Precio }}"
                                                                                            class="form-control text-success text-center"
                                                                                            readonly />
                                                                                    </div>
                                                                                @endif
                                                                            </div>

                                                                            {{-- <div class="form-group col-12">
                                                                                <label class="col-form-label-sm">Precio
                                                                                    Venta</label>
                                                                                <div class="row d-flex">
                                                                                    @if ($sucExonerado == 1)
                                                                                        <div class="col-6 text-center">
                                                                                            <input
                                                                                                id="p5-{{ $producto->IdArticulo }}"
                                                                                                value="{{ $producto->Precio }}"
                                                                                                class="form-control text-success text-center"
                                                                                                readonly />
                                                                                            <span
                                                                                                class="badge badge-warning">Sin
                                                                                                IGV</span>
                                                                                        </div>
                                                                                        <div class="col-6 text-center">
                                                                                            <input
                                                                                                value="{{ number_format($producto->Precio / 1.18, 2) }}"
                                                                                                class="form-control text-danger text-center"
                                                                                                readonly />
                                                                                            <span
                                                                                                class="badge badge-warning">Con
                                                                                                IGV</span>
                                                                                        </div>
                                                                                    @else
                                                                                        <div class="col-12">
                                                                                            <input
                                                                                                id="p5-{{ $producto->IdArticulo }}"
                                                                                                value="{{ $producto->Precio }}"
                                                                                                class="form-control text-success text-center"
                                                                                                readonly />
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            </div> --}}

                                                                            <div class="row">
                                                                                <div class="col-12">
                                                                                    @if ($producto->Codigo != null)
                                                                                        <span
                                                                                            class="text-success font-weight-bold fs-14">Codigo
                                                                                            Barra:
                                                                                            {{ $producto->Codigo }}<span>
                                                                                    @endif
                                                                                </div>
                                                                            </div>

                                                                            <input hidden
                                                                                id="p6-{{ $producto->IdArticulo }}"
                                                                                value="{{ $producto->TipoOperacion }}" />
                                                                            <input hidden
                                                                                id="IdUnidadMedida-{{ $producto->IdArticulo }}"
                                                                                value="{{ $producto->IdUnidadMedida }}" />
                                                                        </div>

                                                                        <div class="card-footer">
                                                                            <div class="product-info col-12">
                                                                                <a class="bg-info color-white botonAgregarProducto"
                                                                                    onclick="agregarProducto({{ $producto->IdArticulo }})"
                                                                                    href="javascript:void(0);">
                                                                                    <i
                                                                                        class="list-icon material-icons">add</i>Agregar
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- /.card -->
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
                                                                        <li class="page-item"><a class="page-link"
                                                                                href="productos?page=1"
                                                                                aria-label="Previous"><span
                                                                                    aria-hidden="true"><i
                                                                                        class="feather feather-chevrons-left"></i></span></a>
                                                                        </li>
                                                                        <li class="page-item"><a class="page-link"
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
                                                                                    <li class="page-item active"><a
                                                                                            class="page-link"
                                                                                            href="javascript:void(0);">{{ $i }}</a>
                                                                                    </li>
                                                                                @else
                                                                                    <li class="page-item"><a
                                                                                            class="page-link"
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
                                                                                        <li class="page-item active"><a
                                                                                                class="page-link"
                                                                                                href="javascript:void(0);">{{ $i }}</a>
                                                                                        </li>
                                                                                    @else
                                                                                        <li class="page-item"><a
                                                                                                class="page-link"
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
                                                                                    <li class="page-item active"><a
                                                                                            class="page-link"
                                                                                            href="javascript:void(0);">{{ $i }}</a>
                                                                                    </li>
                                                                                @else
                                                                                    <li class="page-item"><a
                                                                                            class="page-link"
                                                                                            href="productos?page={{ $i }}">{{ $i }}</a>
                                                                                    </li>
                                                                                @endif
                                                                            @endif
                                                                        @endfor
                                                                    @endif
                                                                    @if ($productos->hasMorePages())
                                                                        <li class="page-item"><a class="page-link"
                                                                                href="{{ $productos->nextPageUrl() }}"
                                                                                aria-label="Next"><span
                                                                                    aria-hidden="true"><i
                                                                                        class="feather feather-chevron-right"></i></span></a>
                                                                        </li>
                                                                        <li class="page-item"><a class="page-link"
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
                                                <div class="modal-footer">
                                                    <button type="button"
                                                        class="btn btn-success btn-rounded ripple text-left"
                                                        data-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    {{-- MODAL GENERAR GASTO --}}
                                    @if ($caja != null)
                                        <div class="modal fade" id="modadGuardarCompraComoGasto" tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModal3Label" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="header mt-3">
                                                        <section
                                                            class="d-flex justify-content-between align-items-center flex-wrap">
                                                            <label for="check" class="fs-16">Guardar Compra como
                                                                Egreso</label><br>
                                                            <label class="switch ">
                                                                <input id="checkActivarEnvio" type="checkbox"
                                                                    name="checkActivarEnvio">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </section>
                                                        <hr>
                                                    </div>
                                                    <div class="modal-body">
                                                        <section class="text-center mt-3">
                                                            <span
                                                                class="fs-28 badge badge-danger">{{ $datosCaja->CajaTotalSoles }}</span>
                                                            <p>Total actual en caja</p>
                                                        </section>
                                                        <div class="form-group">
                                                            <label for="monto">Total a descontar</label>
                                                            <input class="form-control" id="inputMontoEgreso"
                                                                name="monto" type="number" min="0.01"
                                                                step="any" required />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Descripción</label>
                                                            <textarea id="inputDescripcion" class="form-control" rows="4" name="descripcion"></textarea>
                                                            <span
                                                                class="text-danger font-size">{{ $errors->first('observacion') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancelar</button>
                                                        <button id="btnGuardarEgreso" type="submit"
                                                            class="btn btn-primary">Aceptar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
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
    <!--/ #wrapper -->
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.9/metisMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/perfect-scrollbar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ asset('assets/js/jquery.loading.min.js') }}"></script>

    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="{{ asset('assets/js/scriptCompras.js') }}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="{{ asset('assets/js/respuestaAjax/respuestaSweetAlert1.js?v=' . time()) }}"></script>

    {{-- <script src="{{ asset('assets/js/operaciones/compras/scriptCompraEgreso.js?v=' . time()) }}"></script> --}}
    <script src="{{ asset('assets/js/operaciones/compras/scriptStoreCompra.js?v=' . time()) }}"></script>
    <script src="{{ asset('assets/js/operaciones/compras/scriptEgresoCaja.js?v=' . time()) }}"></script>

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
            var today = dd + '-' + mm + '-' + yyyy;
            $("#datepicker").val(today);
        });
    </script>
    <script>
        let urlCompra = "{{ route('storeCompra.store') }}";
        let token = "{{ csrf_token() }}";
        var total = 0;
        var subtotal = 0;
        var igvTotal = 0;
        var iden = 1;
        var array = [];
        var arrayIds = [];
        var opExonerado = 0;
        var sucExonerado = 0;
        // Nuevo codigo Egreso
        let datosCaja = @json($datosCaja);

        
        function changeMontoCaja(tipoMoneda) {
            if (tipoMoneda == 1) {
                $('#totalCaja').text(redondeo(datosCaja.CajaTotalSoles))
                totalCaja = datosCaja.CajaTotalSoles;
            } else {
                $('#totalCaja').text(redondeo(datosCaja.CajaTotalDolares))
                totalCaja = datosCaja.CajaTotalDolares;
            }
        }
        $(function() {
            const idTipoMoneda = $('#tipoMoneda').val();
            changeMontoCaja(idTipoMoneda);
        })
        // Fin
        $(function() {
            $('#plazoCredito').hide();
            $('#interes').hide();
            sucExonerado = @json($sucExonerado);
            $("#selectTipoComp").on('change', function() {
                var tipoDocumento = $("#selectTipoComp").val();
                var serie = $("#serie").val();
                var numero = $("#numero").val();
                var proveedores;
                var numero;
                if (tipoDocumento != 0) {
                    setDatosProveedores();
                    // $('#proveedores option').remove();
                    // $('#proveedores').append('<option value="0">-</option>');
                    // $('#agregarArticulo').attr('disabled', 'disabled');
                    // proveedores = @json($proveedoresTickets);
                    // var datosVentas = @json($comprasTicket);
                    // var ventas = @json($comprasTicket);

                    // for (var i = 0; i < proveedores.length; i++) {
                    //     $('#proveedores').append('<option value="' + proveedores[i]["IdProveedor"] + '">' +
                    //         proveedores[i]["Nombre"] + '</option>');
                    // }
                    // $("#proveedores").val('0');
                } else {
                    $('#proveedores option').remove();
                    $('#proveedores').append('<option value="0">-</option>');
                    $('#agregarArticulo').attr('disabled', 'disabled');
                }
            });

            $("#proveedores").on('change', function() {
                var idProveedor = $("#proveedores").val();
                if (idProveedor != 0) {
                    $('#agregarArticulo').removeAttr('disabled');
                } else {
                    $('#agregarArticulo').attr('disabled', 'disabled');
                }
                // insertarDataTextArea();
            });

            $("#tipoPago").on('change', function() {
                var tipo = $("#tipoPago").val();
                if (tipo == "1") {
                    $('#plazoCredito').hide();
                    $('#interes').hide();
                    $('#efectivo').show();
                    $('#cuentaCorriente').show();
                    //  desactivarcheckGuardarEgreso(0);
                } else {
                    $('#plazoCredito').show();
                    $('#interes').show();
                    $('#efectivo').hide();
                    $('#cuentaCorriente').hide();
                    //  desactivarcheckGuardarEgreso(1);
                }
            });

            $("#cuentaBancaria").on('change', function() {
                var tipoBan = $("#cuentaBancaria").val();
                if (tipoBan == "0") {
                    $('#pagoCuenta').attr("disabled", true);
                    $('#nroOperacion').attr("disabled", true);
                    $('#pagoCuenta').val('0');
                    $('#fechaDepositoCompra').attr("disabled", true);
                    //  desactivarcheckGuardarEgreso(0);
                } else {
                    $('#pagoCuenta').attr("disabled", false);
                    $('#nroOperacion').attr("disabled", false);
                    $('#fechaDepositoCompra').attr("disabled", false);
                    //  desactivarcheckGuardarEgreso(1);
                }
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

    <script>
        $(function() {
            $("#tipoMoneda").on('change', function() {
                var tipoMoneda = $("#tipoMoneda").val();
                // Nuevo Codigo Egreso
                //  changeMontoCaja(tipoMoneda);
                // changeSelector(tipoMoneda);
                // Fin
                $.ajax({
                    type: 'get',
                    url: 'crear-compra/select-productos',
                    data: {
                        'tipoMoneda': tipoMoneda
                    },
                    success: function(data) {
                        if (data[1] == 1) {
                            $('#valorCambio').val(data[2]);
                            data = data[0];
                            $('#listaProductos').empty();
                            $("#inputBuscarProductos").val('');
                            var moneda;
                            if (tipoMoneda == 1) {
                                moneda = 'S/';
                            } else {
                                moneda = '$';
                            }
                            var precioExo = '';
                            for (var i = 0; i < data["data"].length; i++) {
                                if (data["data"][i]["Codigo"] != null) {
                                    var codigo =
                                        '<div class="row">' +
                                        '<div class="col-12">' +
                                        '<span class="text-success font-weight-bold fs-14">Codigo Barra: ' +
                                        data["data"][i]["Codigo"] + '</span>' +
                                        '</div>' +
                                        '</div>'
                                } else {
                                    var codigo = "";
                                }

                                if (sucExonerado == 1) {
                                    precioExo = '<div class = "row my-3">' +
                                        '<div class = "col-12">' +
                                        '<label class ="col-form-label-sm"> Precio  Venta </label>' +
                                        '</div>' +
                                        '<div class="col-12 col-md-6 text-center">' +
                                        '<input id="p5-' + data["data"][i]["IdArticulo"] +
                                        '" value="' +
                                        data["data"][i]
                                        ["Precio"] +
                                        '" class="form-control text-success text-center" readonly />' +
                                        '<span class="badge badge-warning">Con IGV</span>' +
                                        '</div>' +
                                        '<div class="col-12 col-md-6 text-center">' +
                                        '<input value="' + redondeo(parseFloat(data["data"][i][
                                                "Precio"
                                            ]) /
                                            1.18) +
                                        '" class="form-control text-danger text-center" readonly />' +
                                        '<span class="badge badge-warning">Sin IGV</span>' +
                                        '</div>' +
                                        '</div>';

                                } else {
                                    precioExo = '<div class = "row my-3">' +
                                        '<div class = "col-12">' +
                                        '<label class ="col-form-label-sm"> Precio  Venta </label>' +
                                        '</div>' +
                                        '<div class="col-12 text-center">' +
                                        '<input id="p5-' + data["data"][i]["IdArticulo"] +
                                        '" value="' + data["data"][i]["Precio"] +
                                        '" class="form-control text-success text-center" readonly />' +
                                        '</div>' +
                                        '</div>';
                                }

                                $('#listaProductos').append(
                                    '<div class="product col-12 col-md-6">' +
                                    '<div class="card">' +
                                    '<div class="card-body">' +
                                    '<div class="row">' +
                                    '<div class="col-12 text-center">' +
                                    '<span id="p1-' + data["data"][i]["IdArticulo"] +
                                    '" class="product-title font-weight-bold fs-16">' +
                                    data["data"][i]["Descripcion"] + '</span><hr>' +
                                    '</div>' +
                                    '<div class="col-12">' +
                                    '<label class="col-form-label-sm">Precio Costo</label>' +
                                    '<section class="d-flex">' +
                                    '<span class="fs-22" style="line-height: 2;">' +
                                    moneda + '</span>' +
                                    '<input id="p2-' + data["data"][i]["IdArticulo"] +
                                    '" class="form-control product-price fs-16" value="' +
                                    data["data"][i][
                                        "Costo"
                                    ] + '" type="number" step="any"/>' +
                                    ' </section>' +
                                    '</div>' +
                                    '<div class="col-12">' +
                                    '<span class="text-muted">' + data["data"][i]["Marca"] +
                                    '</span>' +
                                    '</div>' +
                                    '</div>' +
                                    '<input hidden id="p3-' + data["data"][i][
                                        "IdArticulo"
                                    ] + '" value="' + data["data"][i]["UM"] +
                                    '"/>' +
                                    '<div class="form-group col-12" hidden>' +
                                    '<label class="col-form-label-sm">Cantidad</label>' +
                                    '<input id="p4-' + data["data"][i]["IdArticulo"] +
                                    '" type="number" min="1" value="1" class="form-control text-center" />' +
                                    '</div>' +
                                    precioExo +
                                    codigo +
                                    '</div>' +
                                    '<div class="card-footer">' +
                                    '<div class="product-info col-12">' +
                                    '<a class="bg-info color-white botonAgregarProducto" onclick="agregarProducto(' +
                                    data["data"][i]["IdArticulo"] +
                                    ')" href="javascript:void(0);">' +
                                    '<i class="list-icon material-icons">add</i>Agregar' +
                                    '</a>' +
                                    '</div>' +
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
                                primero = '<li class="page-item"><a class="page-link" href="' +
                                    data["first_page_url"] +
                                    '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
                                anterior = '<li class="page-item"><a class="page-link" href="' +
                                    data["prev_page_url"] +
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

                            if (data["current_page"] >= 3 && data["current_page"] <= data[
                                    "last_page"] - 2) {
                                for (var i = data["current_page"] - 2; i <= data[
                                        "current_page"] + 2; i++) {
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
                                    '<li class="page-item"><a class="page-link" href="productos?page=' +
                                    (data["current_page"] + 1) +
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

                            var concatenacion = primero + anterior + paginas + siguiente +
                                ultimo;
                            $('#paginasProductos').append(concatenacion);


                            var cuentas;

                            if (tipoMoneda == 1) {
                                cuentas = @json($cuentasSoles);

                            } else {
                                cuentas = @json($cuentasDolares);
                            }
                            $('#cuentaBancaria option').remove();
                            $('#cuentaBancaria').append(
                                '<option selected value="0">Seleccione cuenta bancaria</option>'
                            );
                            for (var j = 0; j < cuentas.length; j++) {
                                //$('#contenido').append('<option id="'+galeria[i]["IdAlbum"]+'" value="'+galeria[i]["Titulo"]+'"></option>');
                                $('#cuentaBancaria').append('<option value="' + cuentas[j][
                                    "IdBanco"
                                ] + '">' + cuentas[j]["Banco"] + ' - ' + cuentas[j][
                                    "NumeroCuenta"
                                ] + '</option>');
                            }

                            limpiarArticulos();

                        } else {
                            alert("Primero debe configurar tipo de cambio");
                            // RUTA CAMBIADA PARA TIPO DE CAMBIO
                            window.location = '../../administracion/bancos/tipo-cambio';
                            // Fin
                        }
                    }

                });
            });

            // $("#tipoCompra").on('change', function() {
            //     arrayIds.splice(0, arrayIds.length);
            //     compra.eliminarArticulos();
            //     $('#tablaAgregado tr:gt(0)').remove();
            //     $('#armarArray div').remove();

            //     opExonerado = parseFloat(0);
            //     total = parseFloat(0);
            //     igvTotal = parseFloat(0);
            //     subtotal = parseFloat(0);
            //     exonerada = parseFloat(0);

            //     $('#total').val('');
            //     $('#subtotal').val('');
            //     $('#igv').val('');
            //     $('#opExonerado').val('');
            //     // Nuevo Codigo Egreso
            //     changeSelector(null);
            //     // Fin
            // });

            $("#categoria").on('change', function() {
                var idCategoria = $("#categoria").val();
                var textoBusqueda = $("#inputBuscarProductos").val();
                var tipoMoneda = $("#tipoMoneda").val();
                $.ajax({
                    type: 'get',
                    // url: 'crear-compra/buscar-productos',
                    url: "{{ route('articulos.buscar-productos-ajax') }}",
                    data: {
                        'textoBuscar': textoBusqueda,
                        'tipoMoneda': tipoMoneda,
                        'idCategoria': idCategoria
                    },
                    success: function(data) {
                        $('#listaProductos').empty();
                        var moneda;
                        if (tipoMoneda == 1) {
                            moneda = 'S/';
                        } else {
                            moneda = '$';
                        }
                        var precioExo = '';
                        for (var i = 0; i < data["data"].length; i++) {
                            if (data["data"][i]["Codigo"] != null) {
                                var codigo =
                                    '<div class="row">' +
                                    '<div class="col-12">' +
                                    '<span class="text-success font-weight-bold fs-14">Codigo Barra: ' +
                                    data["data"][i]["Codigo"] + '</span>' +
                                    '</div>' +
                                    '</div>'
                            } else {
                                var codigo = "";
                            }

                            if (sucExonerado == 1) {
                                precioExo = '<div class = "row my-3">' +
                                    '<div class = "col-12">' +
                                    '<label class ="col-form-label-sm"> Precio  Venta </label>' +
                                    '</div>' +
                                    '<div class="col-12 col-md-6 text-center">' +
                                    '<input id="p5-' + data["data"][i]["IdArticulo"] +
                                    '" value="' +
                                    data["data"][i]
                                    ["Precio"] +
                                    '" class="form-control text-success text-center" readonly />' +
                                    '<span class="badge badge-warning">Con IGV</span>' +
                                    '</div>' +
                                    '<div class="col-12 col-md-6 text-center">' +
                                    '<input value="' + redondeo(parseFloat(data["data"][i][
                                            "Precio"
                                        ]) /
                                        1.18) +
                                    '" class="form-control text-danger text-center" readonly />' +
                                    '<span class="badge badge-warning">Sin IGV</span>' +
                                    '</div>' +
                                    '</div>';

                            } else {
                                precioExo = '<div class = "row my-3">' +
                                    '<div class = "col-12">' +
                                    '<label class ="col-form-label-sm"> Precio  Venta </label>' +
                                    '</div>' +
                                    '<div class="col-12 text-center">' +
                                    '<input id="p5-' + data["data"][i]["IdArticulo"] +
                                    '" value="' + data["data"][i]["Precio"] +
                                    '" class="form-control text-success text-center" readonly />' +
                                    '</div>' +
                                    '</div>';
                            }
                            $('#listaProductos').append(
                                '<div class="product col-12 col-md-6">' +
                                '<div class="card">' +
                                '<div class="card-body">' +
                                '<div class="row">' +
                                '<div class="col-12 text-center">' +
                                '<span id="p1-' + data["data"][i]["IdArticulo"] +
                                '" class="product-title font-weight-bold fs-16">' + data[
                                    "data"][i]["Descripcion"] + '</span><hr>' +
                                '</div>' +
                                '<div class="col-12">' +
                                '<label class="col-form-label-sm">Precio Costo</label>' +
                                '<section class="d-flex">' +
                                '<span class="fs-22" style="line-height: 2;">' + moneda +
                                '</span>' +
                                '<input id="p2-' + data["data"][i]["IdArticulo"] +
                                '" class="form-control product-price fs-16" value="' + data[
                                    "data"][i][
                                    "Costo"
                                ] + '" type="number" step="any"/>' +
                                ' </section>' +
                                '</div>' +
                                '<div class="col-12">' +
                                '<span class="text-muted">' + data["data"][i]["Marca"] +
                                '</span>' +
                                '</div>' +
                                '</div>' +
                                '<input hidden id="p3-' + data["data"][i]["IdArticulo"] +
                                '" value="' + data["data"][i]["UM"] + '"/>' +
                                '<div class="form-group col-12" hidden>' +
                                '<label class="col-form-label-sm">Cantidad</label>' +
                                '<input id="p4-' + data["data"][i]["IdArticulo"] +
                                '" type="number" min="1" value="1" class="form-control text-center" />' +
                                '</div>' +
                                precioExo +
                                codigo +
                                '</div>' +
                                '<div class="card-footer">' +
                                '<div class="product-info col-12">' +
                                '<a class="bg-info color-white botonAgregarProducto" onclick="agregarProducto(' +
                                data["data"][i]["IdArticulo"] +
                                ')" href="javascript:void(0);">' +
                                '<i class="list-icon material-icons">add</i>Agregar' +
                                '</a>' +
                                '</div>' +
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
                            primero = '<li class="page-item"><a class="page-link" href="' +
                                data["first_page_url"] +
                                '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
                            anterior = '<li class="page-item"><a class="page-link" href="' +
                                data["prev_page_url"] +
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

                        if (data["current_page"] >= 3 && data["current_page"] <= data[
                                "last_page"] - 2) {
                            for (var i = data["current_page"] - 2; i <= data["current_page"] +
                                2; i++) {
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
                                '<li class="page-item"><a class="page-link" href="productos?page=' +
                                (data["current_page"] + 1) +
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
            });
        });
    </script>
    <script>
        $("#inputBuscarProductos").keyup(function() {
            var textoBusqueda = $("#inputBuscarProductos").val();
            if (textoBusqueda.length > 3 || textoBusqueda.length == 0) {
                var tipoMoneda = $("#tipoMoneda").val();
                var idCategoria = $("#categoria").val();
                $.ajax({
                    type: 'get',
                    // url: 'crear-compra/buscar-productos',
                    url: "{{ route('articulos.buscar-productos-ajax') }}",
                    data: {
                        'textoBuscar': textoBusqueda,
                        'tipoMoneda': tipoMoneda,
                        'idCategoria': idCategoria
                    },
                    success: function(data) {
                        $('#listaProductos').empty();
                        var moneda;
                        if (tipoMoneda == 1) {
                            moneda = 'S/';
                        } else {
                            moneda = '$';
                        }
                        var precioExo = '';
                        for (var i = 0; i < data["data"].length; i++) {
                            if (data["data"][i]["Codigo"] != null) {
                                var codigo =
                                    '<div class="row">' +
                                    '<div class="col-12">' +
                                    '<span class="text-success font-weight-bold fs-14">Codigo Barra: ' +
                                    data["data"][i]["Codigo"] + '</span>' +
                                    '</div>' +
                                    '</div>'
                            } else {
                                var codigo = "";
                            }

                            if (sucExonerado == 1) {
                                // precioExo = '<div class="col-6 text-center">' +
                                //     '<input id="p5-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]
                                //     ["Precio"] + '" class="form-control text-success text-center" readonly />' +
                                //     '<span class="badge badge-warning">Sin IGV</span>' +
                                //     '</div>' +
                                //     '<div class="col-6 text-center">' +
                                //     '<input value="' + redondeo(parseFloat(data["data"][i]["Precio"]) / 1.18) +
                                //     '" class="form-control text-danger text-center" readonly />' +
                                //     '<span class="badge badge-warning">Con IGV</span>' +
                                //     '</div>';

                                precioExo = '<div class = "row my-3">' +
                                    '<div class = "col-12">' +
                                    '<label class ="col-form-label-sm"> Precio  Venta </label>' +
                                    '</div>' +
                                    '<div class="col-12 col-md-6 text-center">' +
                                    '<input id="p5-' + data["data"][i]["IdArticulo"] + '" value="' +
                                    data["data"][i]
                                    ["Precio"] +
                                    '" class="form-control text-success text-center" readonly />' +
                                    '<span class="badge badge-warning">Con  IGV</span>' +
                                    '</div>' +
                                    '<div class="col-12 col-md-6 text-center">' +
                                    '<input value="' + redondeo(parseFloat(data["data"][i]["Precio"]) /
                                        1.18) +
                                    '" class="form-control text-danger text-center" readonly />' +
                                    '<span class="badge badge-warning">Sin IGV</span>' +
                                    '</div>' +
                                    '</div>';

                            } else {
                                // precioExo = '<div class="col-12">' +
                                //     '<input id="p5-' + data["data"][i]["IdArticulo"] + '" value="' +
                                //     data["data"][i]["Precio"] +
                                //     '" class="form-control text-success text-center" readonly />' +
                                //     '</div>';
                                precioExo = '<div class = "row my-3">' +
                                    '<div class = "col-12">' +
                                    '<label class ="col-form-label-sm"> Precio  Venta </label>' +
                                    '</div>' +
                                    '<div class="col-12 text-center">' +
                                    '<input id="p5-' + data["data"][i]["IdArticulo"] +
                                    '" value="' + data["data"][i]["Precio"] +
                                    '" class="form-control text-success text-center" readonly />' +
                                    '</div>' +
                                    '</div>';
                            }
                            $('#listaProductos').append('<div class="product col-12 col-md-6">' +
                                '<div class="card">' +
                                '<div class="card-body">' +
                                '<div class="row">' +
                                '<div class="col-12 text-center">' +
                                '<span id="p1-' + data["data"][i]["IdArticulo"] +
                                '" class="product-title font-weight-bold fs-16">' + data["data"][i][
                                    "Descripcion"
                                ] + '</span><hr>' +
                                '</div>' +
                                '<div class="col-12">' +
                                '<label class="col-form-label-sm">PrecioCosto</label>' +
                                '<section class="d-flex">' +
                                '<span class="fs-22" style="line-height: 2;">' + moneda +
                                '</span>' +
                                '<input id="p2-' + data["data"][i]["IdArticulo"] +
                                '" class="form-control product-price fs-16" value="' + data["data"][
                                    i
                                ][
                                    "Costo"
                                ] + '" type="number" step="any"/>' +
                                ' </section>' +
                                '</div>' +
                                '<div class="col-12">' +
                                '<span class="text-muted">' + data["data"][i]["Marca"] + '</span>' +
                                '</div>' +
                                '</div>' +
                                '<input hidden id="p3-' + data["data"][i]["IdArticulo"] +
                                '" value="' +
                                data["data"][i]["UM"] + '"/>' +
                                '<div class="form-group col-12" hidden>' +
                                '<label class="col-form-label-sm">Cantidad</label>' +
                                '<input id="p4-' + data["data"][i]["IdArticulo"] +
                                '" type="number" min="1" value="1" class="form-control text-center" />' +
                                '</div>' +
                                precioExo +
                                codigo +
                                '</div>' +
                                '<div class="card-footer">' +
                                '<div class="product-info col-12">' +
                                '<a class="bg-info color-white botonAgregarProducto" onclick="agregarProducto(' +
                                data[
                                    "data"][i]["IdArticulo"] + ')" href="javascript:void(0);">' +
                                '<i class="list-icon material-icons">add</i>Agregar' +
                                '</a>' +
                                '</div>' +
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
                                '<li class="page-item"><a class="page-link" href="productos?page=' +
                                (data["current_page"] + 1) +
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
    </script>
    <script>
        $(document).on('click', '.pagProd a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            getProductos(page);
        });

        function getProductos(page) {
            var textoBusqueda = $("#inputBuscarProductos").val();
            var tipoMoneda = $("#tipoMoneda").val();
            var idCategoria = $("#categoria").val();
            $.ajax({
                type: 'get',
                // url: 'crear-compra/productos?page=' + page,
                url: "{{ route('articulos.paginar-productos-ajax') }}?page=" + page,
                data: {
                    'textoBuscar': textoBusqueda,
                    'tipoMoneda': tipoMoneda,
                    'idCategoria': idCategoria
                },
                success: function(data) {
                    console.log(textoBusqueda);
                    console.log(tipoMoneda);
                    console.log(idCategoria);
                    console.log(data);
                    var inicio = data["to"] - 1;
                    var moneda;
                    if (tipoMoneda == 1) {
                        moneda = 'S/';
                    } else {
                        moneda = '$';
                    }
                    var precioExo = '';
                    $('#listaProductos').empty();
                    for (var i = 0; i < data["data"].length; i++) {
                        if (data["data"][i]["Codigo"] != null) {
                            var codigo =
                                '<div class="row">' +
                                '<div class="col-12">' +
                                '<span class="text-success font-weight-bold fs-14">Codigo Barra: ' +
                                data["data"][i]["Codigo"] + '</span>' +
                                '</div>' +
                                '</div>'
                        } else {
                            var codigo = "";
                        }
                        if (sucExonerado == 1) {
                            precioExo = '<div class = "row my-3">' +
                                '<div class = "col-12">' +
                                '<label class ="col-form-label-sm"> Precio  Venta </label>' +
                                '</div>' +
                                '<div class="col-12 col-md-6 text-center">' +
                                '<input id="p5-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]
                                ["Precio"] + '" class="form-control text-success text-center" readonly />' +
                                '<span class="badge badge-warning">Con IGV</span>' +
                                '</div>' +
                                '<div class="col-12 col-md-6 text-center">' +
                                '<input value="' + redondeo(parseFloat(data["data"][i]["Precio"]) / 1.18) +
                                '" class="form-control text-danger text-center" readonly />' +
                                '<span class="badge badge-warning">Sin IGV</span>' +
                                '</div>' +
                                '</div>';
                        } else {
                            precioExo = '<div class = "row my-3">' +
                                '<div class = "col-12">' +
                                '<label class ="col-form-label-sm"> Precio  Venta </label>' +
                                '</div>' +
                                '<div class="col-12 text-center">' +
                                '<input id="p5-' + data["data"][i]["IdArticulo"] +
                                '" value="' + data["data"][i]["Precio"] +
                                '" class="form-control text-success text-center" readonly />' +
                                '</div>' +
                                '</div>';
                        }
                        $('#listaProductos').append('<div class="product col-12 col-md-6">' +
                            '<div class="card">' +
                            '<div class="card-body">' +
                            '<div class="row">' +
                            '<div class="col-12 text-center">' +
                            '<span id="p1-' + data["data"][i]["IdArticulo"] +
                            '" class="product-title font-weight-bold fs-16">' + data["data"][i][
                                "Descripcion"
                            ] + '</span><hr>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<label class="col-form-label-sm">Precio Costo</label>' +
                            '<section class="d-flex">' +
                            '<span class="fs-22" style="line-height: 2;">' + moneda + '</span>' +
                            '<input id="p2-' + data["data"][i]["IdArticulo"] +
                            '" class="form-control product-price fs-16" value="' + data["data"][i][
                                "Costo"
                            ] + '" type="number" step="any"/>' +
                            ' </section>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<span class="text-muted">' + data["data"][i]["Marca"] + '</span>' +
                            '</div>' +
                            '</div>' +
                            '<input hidden id="p3-' + data["data"][i]["IdArticulo"] + '" value="' + data[
                                "data"][i]["UM"] + '"/>' +
                            '<div class="form-group col-12" hidden>' +
                            '<label class="col-form-label-sm">Cantidad</label>' +
                            '<input id="p4-' + data["data"][i]["IdArticulo"] +
                            '" type="number" min="1" value="1" class="form-control text-center" />' +
                            '</div>' +
                            precioExo +
                            codigo +
                            '</div>' +
                            '<div class="card-footer">' +
                            '<div class="product-info col-12">' +
                            '<a class="bg-info color-white botonAgregarProducto" onclick="agregarProducto(' +
                            data["data"][i][
                                "IdArticulo"
                            ] + ')" href="javascript:void(0);">' +
                            '<i class="list-icon material-icons">add</i>Agregar' +
                            '</a>' +
                            '</div>' +
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
    </script>
    <script>
        $('input[name=serie]').on('keypress', function(event) {
            var regex = new RegExp("^[a-zA-Z0-9]+$");
            var key = String.fromCharCode(event.charCode);
            if (!regex.test(key)) {
                event.preventDefault();
                return false;
            }
        });
    </script>

    <script>
        $("#proveedores").on('change', function() {
            $.showLoading({
                name: 'circle-fade',
            });
            var idProveedor = $(this).val();
            var serie = $("#serie").val();
            var numero = $("#numero").val();
            $.ajax({
                type: 'get',
                url: 'crear-compra/comprobar-existencia',
                data: {
                    'idProveedor': idProveedor,
                    'serie': serie,
                    'numero': numero,
                },
                success: function(data) {
                    if (data[0] == 'error') {
                        $('#agregarArticulo').attr('disabled', 'true')
                        // alert(data[1]);
                        setDatosProveedores();
                        // limpiarSelectProveedores();
                        swal("Error", data[1], "error");
                    }
                    if (data[0] == 'errorDuplicado') {
                        $('#agregarArticulo').attr('disabled', 'true')
                        setDatosProveedores();
                        $("#serie").val("");
                        $("#numero").val("");
                        $("#serie").focus();
                        swal("Error", data[1], "error");
                    }
                    if (data[0] == 'success') {
                        swal("Excelente!", data[1],
                            "success");
                    }
                    $.hideLoading();
                }
            });
        });

        // function limpiarSelectProveedores() {
        //     $('.select2-selection__rendered').text('');
        // }

        function setDatosProveedores() {
            $('#proveedores option').remove();
            $('#proveedores').append('<option value="0">-</option>');
            $('#agregarArticulo').attr('disabled', 'disabled');

            proveedores = @json($proveedoresTickets);
            for (var i = 0; i < proveedores.length; i++) {
                $('#proveedores').append('<option value="' + proveedores[i]["IdProveedor"] + '">' +
                    proveedores[i]["Nombre"] + ' - ' + proveedores[i]["NumeroDocumento"] + '</option>');
            }
        }
    </script>

    <script>
        // FUNCIONES GUARDAR EGRESO
        // let checkGuardarEgreso = $("#checkGuardarEgreso");

        // function calcularNuevoMontoCaja(total, totalCaja) {
        //     $('#montoEgreso').text(redondeo(total));
        //     let nuevoTotalCaja = parseFloat(redondeo(totalCaja)) - parseFloat(redondeo(total));
        //     console.log(`total ${total}, totalCaja ${totalCaja} nuevoTotalCaja ${nuevoTotalCaja}`);
        //     if (total != 0) {
        //         $('#nuevoTotalCaja').text(redondeo(nuevoTotalCaja));
        //     } else {
        //         $('#nuevoTotalCaja').text('0.00');
        //     }
        // }

        // function//  desactivarcheckGuardarEgreso(checked) {
        //     if (checkGuardarEgreso.is(':checked')) {
        //         checkGuardarEgreso.prop('checked', false)
        //         bloquearSeccionEgreso();
        //     }
        //     if (checked == 0) {
        //         $('#seccionCrearEgreso').show();
        //     } else {
        //         $('#seccionCrearEgreso').hide();
        //     }
        // }

        // function isTotalCompraMayorQueCaja(total, totalCaja, mensaje) {
        //     if (checkGuardarEgreso.is(':checked')) {
        //         if (parseFloat(redondeo(total)) >= parseFloat(redondeo(totalCaja))) {
        //             checkGuardarEgreso.prop('checked', false);
        //             bloquearSeccionEgreso();
        //             repuestaErrorValidacion(mensaje);
        //         } else {
        //             desbloquearSeccionEgreso();
        //         }
        //     }
        // }

        // $("#checkGuardarEgreso").click(function() {
        //     if ($(this).is(':checked')) {
        //         const mensaje =
        //             'Error supero el monto total de Caja';
        //         isTotalCompraMayorQueCaja(total, totalCaja, mensaje)
        //     } else {
        //         bloquearSeccionEgreso();
        //     }
        // });

        // function changeSelector(tipoMoneda) {
        //     $('#montoEgreso').text('0.00');
        //     $('#nuevoTotalCaja').text('0.00');
        //     if (checkGuardarEgreso.is(':checked')) {
        //         checkGuardarEgreso.prop('checked', false);
        //         bloquearSeccionEgreso();
        //     }
        //     if (tipoMoneda == 1) {
        //         $('#totalCaja').text(datosCaja.CajaTotalSoles)
        //     }
        //     if (tipoMoneda == 2) {
        //         $('#totalCaja').text(datosCaja.CajaTotalDolares);
        //     }
        // }

        // function desbloquearSeccionEgreso() {
        //     $('#seccionCrearEgreso').removeClass('bg-muted');
        //     $('.card-informativo').addClass('bg-celeste');
        //     $('#inputDescripcionEgreso').attr('disabled', 'true');
        // }


        // function bloquearSeccionEgreso() {
        //     $('#seccionCrearEgreso').addClass('bg-muted');
        //     $('.card-informativo').removeClass('bg-celeste');
        //     $('#inputDescripcionEgreso').attr('disabled', 'false');
        // }

        // function insertarDataTextArea() {
        //     let tipoDocumento = $("#selectTipoComp option:selected").text();
        //     const serie = $("#serie").val();
        //     const numero = $("#numero").val();
        //     let proveedor = $("#proveedores").children("option:selected").text();
        //     $('#inputDescripcionEgreso').val(tipoDocumento.trimStart() + ' de compra ' + serie + '-' +
        //         numero + ' de ' + proveedor);
        // }
        // Fin
    </script>

</body>

</html>
