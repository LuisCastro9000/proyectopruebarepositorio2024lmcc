<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon.png') }}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Factura Generada</title>
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
                            <h6 class="page-title-heading mr-0 mr-r-5">Detalles de ventas</h6>
                        </div>
                        <!-- /.page-title-left -->
                        <div class="page-title-right">
                            <div class="d-flex flex-wrap align-items-center">
                                <!--<a class="p-1" href="../../ventas/imprimir/{{ $ventaSelect->IdVentas }}" target="_blank"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-28">print</i></button></a>-->
                                <a class="p-1" href="../../ventas/descargarAlmacen/{{ $ventaSelect->IdVentas }}"
                                    target="_blank"><button class="btn btn-block btn-info ripple"><i
                                            class="list-icon material-icons fs-20 mr-1">picture_as_pdf</i>Vale
                                        Almacen</button></a>

                                @if ($ventaSelect->IdTipoComprobante != 3)
                                    <a class="p-1"
                                        href="../../ventas/xml/{{ $rucEmpresa }}/{{ $ventaSelect->IdVentas }}"
                                        target="_blank"><button class="btn btn-block btn-primary ripple"><i
                                                class="list-icon material-icons fs-20">code</i></button></a>
                                @endif
                                @if ($empresa->TipoImpresion == 'A4')
                                    {!! Form::open([
                                        'url' => '/operaciones/ventas/imprimir/' . $ventaSelect->IdVentas,
                                        'method' => 'POST',
                                        'files' => true,
                                        'target' => '_blank',
                                    ]) !!}
                                    <input type="hidden" name="selectImpre" value="1">
                                    <button type="submit" class="btn btn-block btn-primary ripple"><i
                                            class="list-icon material-icons fs-20">print</i></button>
                                    {!! Form::close() !!}
                                @elseif ($empresa->TipoImpresion == 'Ticket')
                                    {!! Form::open([
                                        'url' => '/operaciones/ventas/imprimir/' . $ventaSelect->IdVentas,
                                        'method' => 'POST',
                                        'files' => true,
                                        'target' => '_blank',
                                    ]) !!}
                                    <input type="hidden" name="selectImpre" value="3">
                                    <button type="submit" class="btn btn-block btn-primary ripple"><i
                                            class="list-icon material-icons fs-20">print</i></button>
                                    {!! Form::close() !!}
                                @else
                                    <a class="p-1" href="#" data-toggle="modal"
                                        data-target=".bs-modal-sm-print"><button
                                            class="btn btn-block btn-primary ripple"><i
                                                class="list-icon material-icons fs-20">print</i></button></a>
                                @endif
                                <a class="p-1" href="../../ventas/descargar/{{ $ventaSelect->IdVentas }}"
                                    target="_blank"><button class="btn btn-block btn-primary ripple"><i
                                            class="list-icon material-icons fs-20">picture_as_pdf</i></button></a>
                                @if ($ventaSelect->IdTipoComprobante != 3)
                                    <a class="p-1" href="#" data-toggle="modal"
                                        data-target=".bs-modal-sm-primary" onclick="cargarCorreo()"><button
                                            class="btn btn-block btn-primary ripple"><i
                                                class="list-icon material-icons fs-20">mail</i></button></a>
                                @endif
                                {{-- <a class="p-2" href="https://wa.me/51934301929?text=Mi%20https://easyfactperu2021.s3.us-west-2.amazonaws.com/checklist/C-1660166283.png%20"
                                    target="_blank"><img class="logo-expand" alt="" width="40"
                                        src="{{ asset('assets/img/whatsapp.png') }}"></a> --}}

                                {{-- <a class="p-2" href="../../ventas/guardar-pdf/{{ $ventaSelect->IdVentas }}"
                                    target="_blank"><img class="logo-expand" alt="" width="40"
                                        src="{{ asset('assets/img/whatsapp.png') }}"></a> --}}
                                {{-- /operaciones/ventas/imprimir/' . $ventaSelect->IdVentas --}}

                                {{-- <a class="p-1" href="javascript:void(0);"><img class="logo-expand" alt=""
                                        width="40" src="{{ asset('assets/img/whatsapp.png') }}" data-toggle="modal"
                                        data-target="#modalWhatsapp"></a> --}}
                                <a target="_blank" class="p-1"
                                    href="../comprobante-generado/W-{{ $ventaSelect->IdVentas }}"><img
                                        class="logo-expand" alt="" width="40"
                                        src="{{ asset('assets/img/whatsapp.png') }}"></a>

                            </div>
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
                    @if (intval($ventaSelect->CodigoDoc) >= 100 &&
                            intval($ventaSelect->CodigoDoc) <= 1999 &&
                            $ventaSelect->Estado == 'Pendiente')
                        <div class="alert alert-warning">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            El Comprobante Electrónico se agrego a la lista de Facturas Pendientes
                        </div>
                    @endif

                    @if (intval($ventaSelect->CodigoDoc) >= 2000 && intval($ventaSelect->CodigoDoc) <= 3999)
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            El Comprobante Electrónico fue Rechazada
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
                                                    <h5>{{ $ventaSelect->TipoComp }}:
                                                        {{ $ventaSelect->Serie }}-{{ $numeroCeroIzq }}</h5>
                                                </div>
                                                <div class="col-md-6 text-right d-none d-sm-block">
                                                    <strong>CAJERO:</strong> {{ $ventaSelect->Usuario }}
                                                    <br><strong>SUCURSAL:</strong> {{ $ventaSelect->Sucursal }}
                                                    <br><strong>DIRECCIÓN:</strong> {{ $ventaSelect->Local }}
                                                </div>
                                                <div class="col-md-6 d-block d-sm-none"><strong>CAJERO:</strong>
                                                    {{ $ventaSelect->Usuario }}
                                                    <br><strong>SUCURSAL:</strong> {{ $ventaSelect->Sucursal }}
                                                    <br><strong>DIRECCIÓN:</strong> {{ $ventaSelect->Local }}
                                                </div>
                                            </div>
                                            <!-- /.row -->
                                            <hr>
                                            <div class="d-sm-flex">
                                                <div class="col-md-6 mt-2">
                                                    <fieldset class="fieldset fieldset--bordeCeleste">
                                                        <legend class="legend legend--colorNegro">Cliente:
                                                        </legend>
                                                        @if ($idSeguro > 2)
                                                            <strong>NOMBRE/RAZ. SOCIAL:</strong> {{ $seguroNombre }}
                                                            <br><strong>RESPONSABLE ASEGURADO:</strong>
                                                            {{ $ventaSelect->RazonSocial }}
                                                        @else
                                                            <strong>NOMBRE/RAZ. SOCIAL:</strong>
                                                            {{ $ventaSelect->RazonSocial }}
                                                            @if ($ventaSelect->IdTipoDocumento == 2)
                                                                <br><strong>NOMBRE COMERCIAL:</strong>
                                                                {{ $ventaSelect->Nombres }}
                                                            @endif
                                                        @endif
                                                        <br><strong>{{ $ventaSelect->TipoDoc }}:</strong>
                                                        {{ $ventaSelect->NumeroDocumento }}
                                                        <br><strong>DIRECCIÓN:</strong> {{ $ventaSelect->DirCliente }}
                                                        @if ($ventaSelect->Placa != null)
                                                            <br><strong>Placa:</strong> {{ $ventaSelect->Placa }}
                                                        @endif
                                                    </fieldset>
                                                    @if ($ventaSelect->IdTipoPago == 2)
                                                    <fieldset class="fieldset fieldset--bordeCeleste">
                                                        <legend class="legend legend--colorNegro">Información del
                                                            Crédito:</legend>
                                                        <strong>Cuota:</strong> 1
                                                        <br><strong> Monto:</strong>
                                                        @if ($ventaSelect->Detraccion == 1)
                                                        {{ number_format($ventaSelect->Total - $ventaSelect->Total * $ventaSelect->PorcentajeDetraccion / 100, 2, '.', ',') }}
                                                        @else
                                                            {{ number_format($ventaSelect->Total, 2) }}
                                                        @endif
                                                        <br><strong> Fecha Pago:</strong>
                                                        {{ $fechaPago }}
                                                    </fieldset>
                                                    @endif

                                                    @if ($ventaSelect->Retencion == 1)
                                                        <fieldset class="fieldset fieldset--bordeCeleste">
                                                            <legend class="legend legend--colorNegro">Otros Datos:
                                                            </legend>
                                                            <strong>Base Imp. de Retención:</strong>
                                                            {{ number_format($ventaSelect->Total + $ventaSelect->Amortizacion, 2) }}
                                                            <br><strong> Porcentaje de Retención:</strong> 3% / <strong>
                                                                Monto
                                                                de Retención:</strong>
                                                            {{ number_format(($ventaSelect->Total + $ventaSelect->Amortizacion) * 0.03, 2) }}
                                                        </fieldset>
                                                    @endif
                                                    @if ($ventaSelect->Anticipo > 2)
                                                        <fieldset class="fieldset fieldset--bordeCeleste">
                                                            <legend class="legend legend--colorNegro text-danger">Datos
                                                                de Comprobante Anticipado:</legend>
                                                            <br><strong>Comprobante: </strong>
                                                            {{ $anticipoSelect->Serie }}-{{ $anticipoSelect->Numero }}
                                                            <br><strong>Monto Anticipado: </strong>
                                                            {{ $anticipoSelect->Total }}
                                                            <br><strong>Fecha Emitida: </strong>
                                                            {{ $formatoFechaAnticipo }}
                                                        </fieldset>
                                                        <br><strong class="text-danger">Comprobante Electrónico Emitida
                                                            con Pago Anticipado</strong>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 text-right mt-2 d-none d-sm-block">
                                                    <fieldset class="fieldset fieldset--bordeCeleste">
                                                        <legend class="legend legend--colorNegro">Detalles:</legend>
                                                        <strong>FECHA:</strong> <span
                                                            class="text-muted">{{ $formatoFecha }}</span>
                                                        <br><strong>HORA:</strong> <span
                                                            class="text-muted">{{ $formatoHora }}</span>
                                                        <br><strong>MONTO PAGADO:</strong> <span
                                                            class="text-muted">{{ $ventaSelect->Total }}</span>
                                                        <br><strong>MONTO AMORTIZADO:</strong> <span
                                                            class="text-muted">{{ $ventaSelect->Amortizacion }}</span>
                                                        <br><strong>TIPO MONEDA:</strong> <span class="text-muted">
                                                            @if ($ventaSelect->IdTipoMoneda == 1)
                                                                Soles
                                                            @else
                                                                Dólares
                                                            @endif
                                                        </span>
                                                        @if ($ventaSelect->IdTipoPago == 1)
                                                            <br><strong>TIPO DE PAGO:</strong> <span
                                                                class="text-muted">Contado</span>
                                                            @if ($ventaSelect->MontoEfectivo !== null && $ventaSelect->MontoEfectivo !== '0.00')
                                                                <br><strong>PAGO EFECTIVO:</strong> <span
                                                                    class="text-muted">{{ $ventaSelect->MontoEfectivo }}</span>
                                                            @endif
                                                            @if ($ventaSelect->MontoTarjeta !== null && $ventaSelect->MontoTarjeta !== '0.00')
                                                                <br><strong>PAGO CON TARJETA:</strong> <span
                                                                    class="text-muted">{{ $ventaSelect->MontoTarjeta }}</span>
                                                            @endif
                                                            @if ($ventaSelect->MontoCuentaBancaria !== null && $ventaSelect->MontoCuentaBancaria !== '0.00')
                                                                <br><strong>PAGO CON DEPÓSITO:</strong> <span
                                                                    class="text-muted">{{ $ventaSelect->MontoCuentaBancaria }}</span>
                                                                @if (isset($ventaSelect->NumeroCuentaBancaria))
                                                                    <br><strong>NÚMERO DE CUENTA:</strong> <span
                                                                        class="text-muted">{{ $ventaSelect->NumeroCuentaBancaria }}</span>
                                                                @endif
                                                                @if (isset($ventaSelect->NumeroOperacionBancaria))
                                                                    <br><strong>NÚMERO DE OPERACIÓN:</strong> <span
                                                                        class="text-muted">{{ $ventaSelect->NumeroOperacionBancaria }}</span>
                                                                @endif
                                                            @endif
                                                        @else
                                                            <br><strong>TIPO DE PAGO:</strong> <span
                                                                class="text-muted">Crédito</span>
                                                        @endif
                                                        </span>
                                                        <br><strong>TIPO OPERACIÓN:</strong> <span class="text-muted">
                                                            @if ($ventaSelect->TipoVenta == 1)
                                                                Gravada
                                                            @else
                                                                Exonerada
                                                            @endif
                                                        </span>
                                                        @if($ventaSelect->OrdenCompra != null && $ventaSelect->OrdenCompra != "")
                                                            <br><strong>ORDEN DE COMPRA:</strong> <span class="text-muted"> {{$ventaSelect->OrdenCompra}}</span>
                                                        @endif
                                                    </fieldset>
                                                    @if ($ventaSelect->Detraccion == 1)
                                                        <fieldset class="fieldset fieldset--bordeCeleste">
                                                            <legend class="legend legend--colorNegro">Información
                                                                de Detracción:</legend>
                                                            <strong> Medio de Pago:</strong>
                                                            {{$codMedioPago->Codigo}} - {{$codMedioPago->Descripcion}}
                                                            <br>
                                                            <strong> Código de bien y Servicio:</strong>
                                                            {{$codDetraccion->CodigoSunat}} - {{$codDetraccion->Descripcion}}
                                                            <br>
                                                            <strong> Nro Cuenta de Detracción:</strong>
                                                            @if ($cuentaDetracciones != null)
                                                                {{ $cuentaDetracciones->NumeroCuenta }}
                                                            @else
                                                                -
                                                            @endif
                                                            <br>
                                                            <strong> Porcentaje Detracción (%):</strong>
                                                            {{ $ventaSelect->PorcentajeDetraccion }}
                                                            <br>
                                                            <strong> Monto Detracción:</strong>
                                                            {{ number_format(number_format((($ventaSelect->Total + $ventaSelect->Amortizacion) * $ventaSelect->PorcentajeDetraccion) / 100, 0), 2, '.', ',') }}
                                                        </fieldset>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 mt-2 d-block d-sm-none">
                                                    <fieldset class="fieldset fieldset--bordeCeleste">
                                                        <legend class="legend legend--colorNegro">Detalles:</legend>
                                                        <strong>FECHA:</strong> <span
                                                            class="text-muted">{{ $formatoFecha }}</span>
                                                        <br><strong>HORA:</strong> <span
                                                            class="text-muted">{{ $formatoHora }}</span>
                                                        <br><strong>MONTO PAGADO:</strong> <span
                                                            class="text-muted">{{ $ventaSelect->Total }}</span>
                                                        <br><strong>MONTO AMORTIZADO:</strong> <span
                                                            class="text-muted">{{ $ventaSelect->Amortizacion }}</span>
                                                        <br><strong>TIPO MONEDA:</strong> <span class="text-muted">
                                                            @if ($ventaSelect->IdTipoMoneda == 1)
                                                                Soles
                                                            @else
                                                                Dólares
                                                            @endif
                                                        </span>
                                                        @if ($ventaSelect->IdTipoPago == 1)
                                                            <br><strong>TIPO DE PAGO:</strong> <span
                                                                class="text-muted">Contado</span>
                                                            @if ($ventaSelect->MontoEfectivo !== null && $ventaSelect->MontoEfectivo !== '0.00')
                                                                <br><strong>PAGO EFECTIVO:</strong> <span
                                                                    class="text-muted">{{ $ventaSelect->MontoEfectivo }}</span>
                                                            @endif
                                                            @if ($ventaSelect->MontoTarjeta !== null && $ventaSelect->MontoTarjeta !== '0.00')
                                                                <br><strong>PAGO CON TARJETA:</strong> <span
                                                                    class="text-muted">{{ $ventaSelect->MontoTarjeta }}</span>
                                                            @endif
                                                            @if ($ventaSelect->MontoCuentaBancaria !== null && $ventaSelect->MontoCuentaBancaria !== '0.00')
                                                                <br><strong>PAGO CON DEPÓSITO:</strong> <span
                                                                    class="text-muted">{{ $ventaSelect->MontoCuentaBancaria }}</span>
                                                                @if (isset($ventaSelect->NumeroCuentaBancaria))
                                                                    <br><strong>NÚMERO DE CUENTA:</strong> <span
                                                                        class="text-muted">{{ $ventaSelect->NumeroCuentaBancaria }}</span>
                                                                @endif
                                                                @if (isset($ventaSelect->NumeroOperacionBancaria))
                                                                    <br><strong>NÚMERO DE OPERACIÓN:</strong> <span
                                                                        class="text-muted">{{ $ventaSelect->NumeroOperacionBancaria }}</span>
                                                                @endif
                                                            @endif
                                                        @else
                                                            <br><strong>TIPO DE PAGO:</strong> <span
                                                                class="text-muted">Crédito</span>
                                                        @endif
                                                    </span>
                                                    <br><strong>TIPO OPERACIÓN:</strong> <span class="text-muted">
                                                        @if ($ventaSelect->TipoVenta == 1)
                                                            Gravada
                                                        @else
                                                            Exonerada
                                                        @endif
                                                    </span>
                                                    @if($ventaSelect->OrdenCompra != null && $ventaSelect->OrdenCompra != "")
                                                        <br><strong>ORDEN DE COMPRA:</strong> <span class="text-muted"> {{$ventaSelect->OrdenCompra}}</span>
                                                    @endif
                                                    </fieldset>
                                                    @if ($ventaSelect->Detraccion == 1)
                                                            <fieldset class="fieldset fieldset--bordeCeleste">
                                                                <legend class="legend legend--colorNegro">Información
                                                                    de Detracción:</legend>
                                                                    <strong> Medio de Pago:</strong>
                                                                    {{$codMedioPago->Codigo}} - {{$codMedioPago->Descripcion}}
                                                                    <br>
                                                                    <strong> Código de Bien y Servicio:</strong>
                                                                    {{$codDetraccion->CodigoSunat}} - {{$codDetraccion->Descripcion}}
                                                                    <br>
                                                                <strong> Nro Cuenta de Detracción:</strong>
                                                                @if ($cuentaDetracciones != null)
                                                                    {{ $cuentaDetracciones->NumeroCuenta }}
                                                                @else
                                                                    -
                                                                @endif
                                                                <br>
                                                                <strong> Porcentaje Detracción (%):</strong>
                                                                {{ $ventaSelect->PorcentajeDetraccion }}
                                                                <br>
                                                                <strong> Monto Detracción:</strong>
                                                                {{ number_format(number_format((($ventaSelect->Total + $ventaSelect->Amortizacion)* $ventaSelect->PorcentajeDetraccion) / 100, 0), 2, '.', ',') }}
                                                            </fieldset>
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- /.row -->
                                            <hr class="border-0">
                                            <table id="table" class="table table-bordered table-responsive-sm"
                                                style="width: 100%">
                                                <thead>
                                                    <tr class="bg-primary-dark text-white">
                                                        <th class="text-center">Código</th>
                                                        <th>Descripción / Detalle</th>
                                                        <th class="text-center">Uni/Medida</th>
                                                        <th class="text-center">Precio Venta</th>
                                                        <th class="text-center">Descuento</th>
                                                        <th class="text-center">Cantidad</th>
                                                        <th class="text-center">Importe</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($items as $item)
                                                        @if ($item->Gratuito == 1)
                                                            @php $backgroundColor = 'background-color: #d3d3d3'; @endphp
                                                        @else
                                                            @php $backgroundColor = 'background-color: none'; @endphp
                                                        @endif
                                                        <tr style="{{ $backgroundColor }}">
                                                            <td scope="row">{{ $item->Cod }}</td>
                                                            <td>{{ $item->Descripcion }} / {{ $item->Detalle }}
                                                            </td>
                                                            <td>
                                                                {{ $item->TextUnidad }}
                                                            </td>
                                                            <td>{{ $item->PrecioUnidadReal }}</td>
                                                            <td>{{ $item->Descuento }}</td>
                                                            <td>{{ $item->Cantidad }}</td>
                                                            <td>{{ $item->Importe }}</td>
                                                            @if ($item->IdPaquetePromocional > 0)
                                                                <td>
                                                                    <button
                                                                        onclick="verDetallePaquetePromocional({{ $item->IdPaquetePromocional }})"
                                                                        class="btn btn-primary ml-1 p-1"><i
                                                                            class="list-icon material-icons fs-16">visibility</i>
                                                                    </button>
                                                                </td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <div class="row mt-4">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Observación</label>
                                                        <textarea id="observacion" class="form-control" rows="5">{{ $ventaSelect->Observacion }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 invoice-sum">
                                                    <ul class="list-unstyled">
                                                        <li>
                                                            @if ($ventaSelect->TipoVenta == 1)
                                                                OP. GRAVADAS:
                                                            @else
                                                                OP. EXONERADAS:
                                                            @endif
                                                            {{ number_format($ventaSelect->Subtotal, 2, '.', ',') }}
                                                        </li>
                                                        <li>OP GRATUITAS:
                                                            {{ number_format($ventaSelect->Gratuita, 2, '.', ',') }}
                                                        </li>
                                                        @if ($ventaSelect->Anticipo > 3)
                                                            <li>ANTICIPOS (con IGV):
                                                                {{ number_format($anticipoSelect->Total, 2, '.', ',') }}
                                                            </li>
                                                        @endif
                                                        <li>DESCUENTO:
                                                            {{ number_format($ventaSelect->Exonerada, 2, '.', ',') }}
                                                        </li>
                                                        <li>
                                                            @if ($ventaSelect->TipoVenta == 1)
                                                                IGV(18%):
                                                            @else
                                                                IGV(0%):
                                                            @endif
                                                            {{ number_format($ventaSelect->IGV, 2, '.', ',') }}
                                                        </li>
                                                        <li><strong>TOTAL :
                                                                {{ number_format($ventaSelect->Total + $ventaSelect->Amortizacion, 2, '.', ',') }}</strong>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="form-actions btn-list mt-3">
                                                <a href="../realizar-venta"><button class="btn btn-primary"
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

                <div class="modal modal-primary fade bs-modal-sm-primary" tabindex="-1" role="dialog"
                    aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
                    <div class="modal-dialog modal-sm">
                        {!! Form::open([
                            'url' => '/operaciones/ventas/enviar-correo/' . $ventaSelect->IdVentas,
                            'method' => 'POST',
                            'files' => true,
                            'class' => 'form-material',
                        ]) !!}
                        <div class="modal-content">
                            <div class="modal-header text-inverse">
                                <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                                <h6 class="modal-title" id="mySmallModalLabel2">Enviar por correo</h6>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <label>Correo cliente:</label>
                                    <input id="inpCorreo" class="form-control" name="correo" />
                                    <input id="inpCliente" hidden class="form-control" name="cliente" />
                                    <input id="inpComprobante" hidden class="form-control" name="comprobante" />
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Enviar</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>

                <div class="modal modal-primary fade bs-modal-sm-print" tabindex="-1" role="dialog"
                    aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
                    <div class="modal-dialog modal-sm">
                        {!! Form::open([
                            'url' => '/operaciones/ventas/imprimir/' . $ventaSelect->IdVentas,
                            'method' => 'POST',
                            'files' => true,
                            'class' => 'form-material',
                            'target' => '_blank',
                        ]) !!}
                        <div class="modal-content">
                            <div class="modal-header text-inverse">
                                <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                                <h6 class="modal-title" id="mySmallModalLabel2">Imprimir comprobante</h6>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <label>Seleccionar tipo de impresión:</label>
                                    <select id="selectImpre" class="form-control" name="selectImpre">
                                        <option value="1">A4</option>
                                        <option value="3">Ticket</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Imprimir</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>


                {{-- Modal enviar pdf x Whatsapp --}}
                <div class="modal fade" id="modalWhatsapp" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            {!! Form::open(['url' => '/operaciones/ventas/guardar-pdf', 'method' => 'POST', 'files' => true]) !!}
                            <div class="modal-body">
                                <div class="row mt-4">
                                    <div class="col-12 text-center">
                                        <div class="form-group">
                                            @if ($numeroCelular != null)
                                                <label class="fs-16" for="numeroCelular">Número de celular
                                                    Registrado como teléfono de contacto del cliente</label>
                                            @else
                                                <label class="fs-16" for="numeroCelular">Ingresar número de
                                                    celular</label>
                                            @endif
                                            <input type="text" class="form-control text-center"
                                                name="numeroCelular" id="numeroCelular"
                                                value="{{ $numeroCelular }}">
                                            <input type="hidden" name="idVenta"
                                                value="{{ $ventaSelect->IdVentas }}">
                                        </div>
                                        @if ($numeroCelular != null)
                                            <p>Si aún es válido Continue caso contrario <br> vuelva a digitarlo
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    onclick="cerrarVentana()">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Enviar</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    {{-- Fin --}}
                </div>
                {{-- Fin --}}



                @include('operaciones.cotizacion._modalDetallePaquetePromo')
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

    <script>
        var id = <?php echo json_encode($idIconoWhatsapp); ?>;
        if (id.startsWith('W-')) {
            $(document).ready(function() {
                $('#modalWhatsapp').modal('toggle')
            });
        }

        $('#modalWhatsapp').on('shown.bs.modal', function() {
            $('#numeroCelular').focus();
        })

        function cerrarVentana() {
            window.close();
        }

        function verDetallePaquetePromocional($idPaquete) {
            var tipoVenta = @json($ventaSelect->TipoVenta);
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
                    $('#totalPaquete').val(redondeo(total));
                }
            })
            $('.detallePaquetePromocional').modal('show');
        }
    </script>
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
            if (num == 0 && num == "0.00") return "0.00";
            if (!num && num == 'NaN') return '-';
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
        function cargarCorreo() {
            var venta = <?php echo json_encode($ventaSelect); ?>;
            var numCeroIzq = <?php echo json_encode($numeroCeroIzq); ?>;
            $('#inpCorreo').val(venta['Email']);
            $('#inpCliente').val(venta['Nombres']);
            $('#inpComprobante').val(venta['Serie'] + '-' + numCeroIzq);
        }
    </script>
</body>

</html>
