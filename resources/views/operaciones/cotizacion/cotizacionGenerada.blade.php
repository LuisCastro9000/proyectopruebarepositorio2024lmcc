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

    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/css/perfect-scrollbar.min.css"
        rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">

    <link href="{{ asset('assets/css/responsive.dataTables.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/newStyles.css?v=' . rand(1, 99)) }}" rel="stylesheet" type='text/css'>
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
                        <div class="page-title-left mb-sm-0 mb-2">
                            <h6 class="page-title-heading mr-0 mr-r-5">Detalles de Cotizacion</h6>
                        </div>
                        <!-- /.page-title-left -->
                        <div class="page-title-right">
                            <div class=" d-flex flex-wrap">

                                {{-- SE AGREGO EL BOTON DE IMPRIMIR --}}
                                <a class="p-1"
                                    href="../../cotizacion/imprimir/{{ $cotizacionSelect->IdCotizacion }} "
                                    target="_blank"><button class="btn btn-block btn-autocontrol-naranja ripple"><i
                                            class="list-icon material-icons color-icon fs-20">print</i></button></a>
                                {{-- FIN --}}
                                <a class="p-1"
                                    href="../../cotizacion/descargar/{{ $cotizacionSelect->IdCotizacion }}"
                                    target="_blank"><button class="btn btn-block btn-success ripple"><i
                                            class="list-icon material-icons fs-20">picture_as_pdf</i></button></a>
                                <a class="p-1" href="#" data-toggle="modal" data-target=".bs-modal-sm-primary"
                                    onclick="cargarCorreo()"><button class="btn btn-block btn-success ripple"><i
                                            class="list-icon material-icons fs-20">mail</i></button></a>

                                @if ($cotizacionSelect->TipoCotizacion == 1)
                                    <a class="p-1"
                                        href="../../cotizacion/descargar-nuevopdf/{{ $cotizacionSelect->IdCotizacion }}"
                                        target="_blank"><button class="btn btn-block btn-success ripple">Disp.
                                            Stock</button></a>
                                @endif
                                @if ($cotizacionSelect->TipoCotizacion == 2)
                                    <a class="p-1"
                                        href="../../cotizacion/descargar-orden/{{ $cotizacionSelect->IdCotizacion }}"
                                        target="_blank"><button class="btn btn-block btn-success ripple"><i
                                                class="list-icon material-icons fs-20">picture_as_pdf</i>Orden de
                                            Servicio</button></a>
                                @endif
                                @if (
                                    $cotizacionSelect->IdEstadoCotizacion == 1 ||
                                        $cotizacionSelect->IdEstadoCotizacion == 3 ||
                                        $cotizacionSelect->IdEstadoCotizacion == 5)
                                    <a class="p-1"
                                        href="../../cotizacion/convertir/{{ $cotizacionSelect->IdCotizacion }}"><button
                                            class="btn btn-block btn-success ripple"><i
                                                class="list-icon material-icons fs-20">add_to_photos</i> Convertir
                                            Cotizacion</button></a>
                                @endif
                                {{-- <a class="p-1" href="javascript:void(0);"><img class="logo-expand" alt=""
                                        width="40" src="{{ asset('assets/img/whatsapp.png') }}" data-toggle="modal"
                                        data-target="#modalWhatsapp"></a> --}}
                                <a target="_blank" class="p-1"
                                    href="../comprobante-generado/W-{{ $cotizacionSelect->IdCotizacion }}"><img
                                        class="logo-expand" alt="" width="40"
                                        src="{{ asset('assets/img/whatsapp.png') }}"></a>
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
                                    <div class="widget-body clearfix">
                                        <div class="ecommerce-invoice">
                                            <div class="d-sm-flex">
                                                <div class="col-md-6">
                                                    <h5>Cotizacion:
                                                        {{ $cotizacionSelect->Serie }}-{{ $numeroCeroIzq }}</h5>
                                                    @if ($cotizacionSelect->IdEstadoCotizacion != 5)
                                                        @if ($cotizacionSelect->IdEstadoCotizacion == 1)
                                                            <span class="bg-abierto">Abierto</span>
                                                        @elseif($cotizacionSelect->IdEstadoCotizacion == 2)
                                                            <span class="bg-enProceso">En Proceso</span>
                                                        @elseif($cotizacionSelect->IdEstadoCotizacion == 3)
                                                            <span class=" bg-finalizado">Finalizado</span>
                                                        @elseif($cotizacionSelect->IdEstadoCotizacion == 4)
                                                            <span class="bg-cerrado">Cerrado</span>
                                                        @elseif($cotizacionSelect->IdEstadoCotizacion == 6)
                                                            <span class="bg-baja">Baja</span>
                                                        @endif
                                                        @if (count($arrayComprobantes) > 0)
                                                            @php $a = 0; @endphp
                                                            @foreach ($arrayComprobantes as $arrayComprobante)
                                                                @php $a++; @endphp
                                                                @if ($a == 1)
                                                                    <a
                                                                        href="../../ventas/comprobante-generado/{{ $arrayComprobante->IdVentas }}">{{ $arrayComprobante->Serie }}-{{ $arrayComprobante->Numero }}</a>
                                                                @else
                                                                    / <a
                                                                        href="../../ventas/comprobante-generado/{{ $arrayComprobante->IdVentas }}">{{ $arrayComprobante->Serie }}-{{ $arrayComprobante->Numero }}</a>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                    <br>
                                                    @if (count($amortizaciones) > 0)
                                                        <a href="#" data-toggle="modal"
                                                            data-target=".bs-modal-lg-amortizaciones"
                                                            class="btn btn-info mt-2"><i
                                                                class="list-icon material-icons">visibility</i> Ver
                                                            Amortizaciones</a>
                                                        {{-- Nuevo boton --}}

                                                        <a class="p-1" href="#" data-toggle="modal"
                                                            data-target=".modalImprimirTicketAmortizacion"><button
                                                                class="btn btn-primary ripple mt-2 mb-md-0 mb-4">Imprimir
                                                                Ticket Amortización</button></a>

                                                        {{-- Fin --}}
                                                    @endif
                                                </div>
                                                <div class="col-md-6 text-right d-none d-sm-block">
                                                    <strong>CAJERO:</strong> {{ $cotizacionSelect->Usuario }}
                                                    <br><strong>SUCURSAL:</strong> {{ $cotizacionSelect->Sucursal }}
                                                    <br><strong>DIRECCIÓN:</strong> {{ $cotizacionSelect->Local }}
                                                    @if (strtolower($cotizacionSelect->TipoDoc) == 'ruc' && $cotizacionSelect->PersonaContacto != null)
                                                        <br><span class="badge badge-warning fs-12"><strong
                                                                class="text-break">CONTACTO:</strong>
                                                            <b
                                                                class="fs-15">{{ $cotizacionSelect->PersonaContacto }}</b></span>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 d-block d-sm-none"><strong>CAJERO:</strong>
                                                    {{ $cotizacionSelect->Usuario }}
                                                    <br><strong>SUCURSAL:</strong> {{ $cotizacionSelect->Sucursal }}
                                                    <br><strong>DIRECCIÓN:</strong> {{ $cotizacionSelect->Local }}
                                                    @if (strtolower($cotizacionSelect->TipoDoc) == 'ruc' && $cotizacionSelect->PersonaContacto != null)
                                                        <br><span class="badge badge-warning fs-12"><strong
                                                                class="text-break">CONTACTO:</strong>
                                                            <b
                                                                class="fs-15">{{ $cotizacionSelect->PersonaContacto }}</b></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- /.row -->
                                            <hr>
                                            @if ($modulosSelect->contains('IdModulo', 7))
                                                @if ($cotizacionSelect->IdTipoAtencion == 1 || $cotizacionSelect->IdTipoAtencion == 6)
                                                    @if (
                                                        $cotizacionSelect->MantenimientoActual != null &&
                                                            $cotizacionSelect->ProximoMantenimiento != null &&
                                                            $cotizacionSelect->PeriodoProximoMantenimiento != null)
                                                        <div class="col-12 breadcrumb d-flex justify-content-between">
                                                            <article>Mantenimiento Actual:
                                                                <span
                                                                    class="font-weight-bold">{{ $cotizacionSelect->MantenimientoActual }}</span>
                                                            </article>
                                                            <article>Próximo Mantenimiento:
                                                                <span
                                                                    class="font-weight-bold">{{ $cotizacionSelect->ProximoMantenimiento }}</span>
                                                            </article>
                                                            <article>Período Próximo Mantenimiento:
                                                                <span
                                                                    class="font-weight-bold">{{ $cotizacionSelect->PeriodoProximoMantenimiento }}
                                                                    Días</span>
                                                            </article>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endif
                                            <div class="d-sm-flex">
                                                <div class="col-md-6 mt-2">
                                                    <h6>Cliente:</h6>
                                                    @if ($cotizacionSelect->TipoCotizacion == 2)
                                                        @if ($modulosSelect->contains('IdModulo', 5) && $vehiculo->IdSeguro > 2)
                                                            <strong>NOMBRE/RAZ. SOCIAL:</strong>
                                                            {{ $seguro }}
                                                            <br><strong>RESPONSABLE ASEGURADO:</strong>
                                                            {{ $cotizacionSelect->RazonSocial }}
                                                        @else
                                                            <strong>NOMBRE/RAZ. SOCIAL:</strong>
                                                            {{ $cotizacionSelect->RazonSocial }}
                                                            @if ($cotizacionSelect->IdTipoDocumento == 2)
                                                                <br><strong>NOMBRE COMERCIAL:</strong>
                                                                {{ $cotizacionSelect->Nombres }}
                                                            @endif
                                                        @endif
                                                    @else
                                                        <strong>NOMBRE/RAZ. SOCIAL:</strong>
                                                        {{ $cotizacionSelect->RazonSocial }}
                                                        @if ($cotizacionSelect->IdTipoDocumento == 2)
                                                            <br><strong>NOMBRE COMERCIAL:</strong>
                                                            {{ $cotizacionSelect->Nombres }}
                                                        @endif
                                                    @endif
                                                    <br><strong>DOCUMENTO:</strong>
                                                    {{ $cotizacionSelect->NumeroDocumento }}
                                                    <br><strong>DIRECCIÓN:</strong>
                                                    {{ $cotizacionSelect->DirCliente }}
                                                </div>
                                                <div class="col-md-6 text-right mt-2 d-none d-sm-block">
                                                    <h6>Detalles:</h6>
                                                    <strong>FECHA:</strong> <span
                                                        class="text-muted">{{ $formatoFecha }}</span>
                                                    <br><strong>HORA:</strong> <span
                                                        class="text-muted">{{ $formatoHora }}</span>
                                                    <br><strong>MONTO A PAGAR:</strong> <span
                                                        class="text-muted">{{ $cotizacionSelect->Total }}</span>
                                                    <br><strong>TIPO MONEDA:</strong> <span class="text-muted">
                                                        @if ($cotizacionSelect->IdTipoMoneda == 1)
                                                            Soles
                                                        @else
                                                            Dólares
                                                        @endif
                                                    </span>
                                                    <br><strong>TIPO OPERACIÓN:</strong>
                                                    {{ $cotizacionSelect->TipoVenta }}<span class="text-muted">
                                                        @if ($cotizacionSelect->TipoVenta == 1)
                                                            Gravada
                                                        @else
                                                            Exonerada
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="col-md-6 mt-2 d-block d-sm-none">
                                                    <h6>Detalles:</h6>
                                                    <strong>FECHA:</strong> <span
                                                        class="text-muted">{{ $formatoFecha }}</span>
                                                    <br><strong>HORA:</strong> <span
                                                        class="text-muted">{{ $formatoHora }}</span>
                                                    <br><strong>MONTO A PAGAR:</strong> <span
                                                        class="text-muted">{{ $cotizacionSelect->Total }}</span>
                                                    <br><strong>TIPO MONEDA:</strong> <span class="text-muted">
                                                        @if ($cotizacionSelect->IdTipoMoneda == 1)
                                                            Soles
                                                        @else
                                                            Dólares
                                                        @endif
                                                    </span>
                                                    <br><strong>TIPO
                                                        OPERACIÓN:</strong>{{ $cotizacionSelect->TipoVenta }} <span
                                                        class="text-muted">
                                                        @if ($cotizacionSelect->TipoVenta == 1)
                                                            Gravada
                                                        @else
                                                            Exonerada
                                                        @endif
                                                    </span>
                                                    @if (4 == 1)
                                                        <br><strong>TIPO DE PAGO:</strong> <span
                                                            class="text-muted">Contado</span>
                                                        <br><strong>PAGO EFECTIVO:</strong> <span
                                                            class="text-muted">{{ $cotizacionSelect->MontoEfectivo }}</span>
                                                        <br><strong>PAGO CON TARJETA:</strong> <span
                                                            class="text-muted">{{ $cotizacionSelect->MontoTarjeta }}</span>
                                                    @else
                                                        <br><strong>TIPO DE PAGO:</strong> <span
                                                            class="text-muted">Crédito</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <hr>
                                            <!-- /.row -->
                                            @if ($cotizacionSelect->TipoCotizacion == 2)
                                                <div class="border container m-2">
                                                    <table width="100%">
                                                        <tr>
                                                            <td><strong>OPERARIO : </strong> {{ $operario }}
                                                            </td>
                                                            <td><strong>PLACA : </strong>
                                                                {{ $vehiculo->PlacaVehiculo }} </td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>COLOR : </strong> {{ $color }} </td>
                                                            <td><strong>AÑO: </strong> {{ $anio }} </td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>MARCA: </strong>
                                                                {{ $vehiculo->NombreMarca }}
                                                            </td>
                                                            <td><strong>MODELO : </strong>
                                                                {{ $vehiculo->NombreModelo }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>KILOMETRAJE : </strong>
                                                                {{ $cotizacionSelect->Campo1 }} </td>
                                                            <td><strong>HOROMETRO: </strong>
                                                                {{ $cotizacionSelect->Campo2 }} </td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>VENC. SOAT : </strong> {{ $fechaSoat }}
                                                            </td>
                                                            <td><strong>VENC. REV. TÉCNICA: </strong>
                                                                {{ $fechaRevTec }} </td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>TRABAJO A REALIZAR: </strong>
                                                                {{ $cotizacionSelect->Trabajos }}</td>
                                                            @if ($cotizacionSelect->IdCheckIn != null)
                                                                <td><a target="_blank"
                                                                        href="../../../vehicular/CheckIn/documento-generado/{{ $cotizacionSelect->IdCheckIn }}"><button
                                                                            class="btn btn-primary">Ver Detalle de
                                                                            Inventario</button></a></td>
                                                            @endif
                                                        </tr>
                                                    </table>
                                                </div>
                                            @endif

                                            <hr class="border-0">
                                            <table id="table" class="table table-bordered table-responsive-sm"
                                                style="width: 100%">
                                                <thead>
                                                    <tr class="bg-success-dark text-white">
                                                        <th class="text-center">Código</th>
                                                        <th>Descripción</th>
                                                        <th>Detalle</th>
                                                        <th>Marca</th>
                                                        <th class="text-center">Uni/Medida</th>
                                                        @if ($cotizacionSelect->TipoVenta == 1 && $usuarioSelect->OpcionPrecioSinIgv == 1)
                                                            <th class="text-center">Precio Sin IGV</th>
                                                        @endif
                                                        <th class="text-center">Precio Venta</th>
                                                        <th class="text-center">Descuento</th>
                                                        <th class="text-center">Cantidad</th>
                                                        <th class="text-center">Importe</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($items as $item)
                                                        <tr>
                                                            <td scope="row" style="width: 110px">
                                                                {{ $item->Cod }}
                                                                @if ($item->Fecha_actualizacion)
                                                                    <div
                                                                        class="badge-autoncontrol__danger fs-6 d-flex flex-column w-100 fs-10 px-1">
                                                                        <span class="fs-6">Actualizado
                                                                            el:</span><span
                                                                            class="fs-6">{{ $item->Fecha_actualizacion }}</span>
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            <td>{{ $item->Descripcion }}</td>
                                                            <td>{{ $item->Detalle }}</td>
                                                            <td>{{ $item->Marca }}</td>
                                                            <td>
                                                                {{ $item->TextUnidad }}
                                                            </td>
                                                            @if ($cotizacionSelect->TipoVenta == 1 && $usuarioSelect->OpcionPrecioSinIgv == 1)
                                                                <td>{{ number_format($item->PrecioUnidadReal / config('variablesGlobales.Igv'), 2, '.', ',') }}
                                                            @endif
                                                            <td>{{ number_format($item->PrecioUnidadReal, 2, '.', ',') }}
                                                            </td>
                                                            <td>{{ number_format($item->Descuento, 2, '.', ',') }}
                                                            </td>
                                                            <td>{{ $item->Cantidad }}</td>
                                                            <td class="text-center">
                                                                {{ number_format($item->Importe, 2, '.', ',') }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    @foreach ($itemsPaquetePromocional as $item)
                                                        <tr>
                                                            <td scope="row">{{ $item->Codigo }}</td>
                                                            <td>{{ $item->NombrePaquete }}</td>
                                                            <td>{{ $item->Detalle }}</td>
                                                            <td></td>
                                                            <td> {{ $item->TextUnidad }} </td>
                                                            @if ($cotizacionSelect->TipoVenta == 1 && $usuarioSelect->OpcionPrecioSinIgv == 1)
                                                                <td>{{ number_format($item->PrecioUnidadReal / config('variablesGlobales.Igv'), 2, '.', ',') }}
                                                            @endif
                                                            <td>{{ number_format($item->PrecioUnidadReal, 2, '.', ',') }}
                                                            </td>
                                                            <td>{{ number_format($item->Descuento, 2, '.', ',') }}
                                                            </td>
                                                            <td>{{ $item->Cantidad }}</td>
                                                            <td class="text-center">
                                                                {{ number_format($item->Importe, 2, '.', ',') }}
                                                                <br>
                                                                <button
                                                                    onclick="verDetallePaquetePromocional({{ $item->IdPaquetePromocional }})"
                                                                    class="btn btn-primary ml-1 p-1"><i
                                                                        class="list-icon material-icons fs-16">visibility</i></button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>

                                            </table>
                                            <div class="row mt-4">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Observación</label>
                                                        <textarea id="observacion" class="form-control" rows="4">{{ $cotizacionSelect->Observacion }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 invoice-sum">
                                                    <ul class="list-unstyled">
                                                        <li>
                                                            @if ($cotizacionSelect->TipoVenta == 1)
                                                                OP. GRAVADAS:
                                                            @else
                                                                OP. EXONERADAS:
                                                            @endif
                                                            {{ number_format($cotizacionSelect->SubTotal, 2, '.', ',') }}
                                                        </li>
                                                        <li>DESCUENTO:
                                                            {{ number_format($cotizacionSelect->Exonerada, 2, '.', ',') }}
                                                        </li>
                                                        <li>
                                                            @if ($cotizacionSelect->TipoVenta == 1)
                                                                IGV(18%):
                                                            @else
                                                                IGV(0%):
                                                            @endif
                                                            {{ number_format($cotizacionSelect->Igv, 2, '.', ',') }}
                                                        </li>
                                                        <li><strong>TOTAL :
                                                                {{ number_format($cotizacionSelect->Total, 2, '.', ',') }}</strong>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="form-actions btn-list mt-3">
                                                <a href="../consultar-cotizacion"><button class="btn btn-success"
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

                <div class="modal modal-success fade bs-modal-sm-primary" tabindex="-1" role="dialog"
                    aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
                    <div class="modal-dialog modal-sm">
                        {!! Form::open([
                            'url' => '/operaciones/cotizacion/enviar-correo/' . $cotizacionSelect->IdCotizacion,
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
                                <button type="submit" class="btn btn-success">Enviar</button>
                                <button type="button" class="btn btn-success" data-dismiss="modal">Cancelar</button>
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
                            'url' => '/operaciones/cotizacion/imprimir/' . $cotizacionSelect->IdCotizacion,
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

                <div class="modal modal-primary fade bs-modal-lg-amortizaciones " tabindex="-1" role="dialog"
                    aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header text-inverse">
                                <h6 class="modal-title" id="mySmallModalLabel2">Amortizaciones</h6>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <table id="table" class="table table-responsive-sm">
                                        <thead>
                                            <tr class="bg-primary">
                                                <th scope="col">Fecha Amortizado</th>
                                                <th scope="col">Tipo Pago</th>
                                                <th scope="col">Monto Amortizado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($amortizaciones as $amortizacion)
                                                <tr>
                                                    <td>{{ $amortizacion->FechaIngreso }}</td>
                                                    <td>
                                                        @if ($amortizacion->FormaPago == 1)
                                                            Pago Efectivo
                                                        @elseif($amortizacion->FormaPago == 2)
                                                            POS
                                                        @else
                                                            Transferencia Bancaria
                                                        @endif
                                                    </td>
                                                    <td>{{ $amortizacion->Monto }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Modal enviar pdf x Whatsapp --}}
                <div class="modal fade" id="modalWhatsapp" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            {!! Form::open(['url' => '/operaciones/cotizacion/guardar-pdf', 'method' => 'POST', 'files' => true]) !!}
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
                                            <input type="hidden" name="idCotizacion"
                                                value="{{ $cotizacionSelect->IdCotizacion }}">
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
                </div>
                {{-- Fin --}}

                {{-- MODAL IMPRIMIR TICKET DE AMORTIZACION --}}
                <div class="modal modal-primary fade modalImprimirTicketAmortizacion" tabindex="-1" role="dialog"
                    aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
                    <div class="modal-dialog modal-sm">
                        <form method="POST" target='_blank'
                            action="{{ route('imprimirTicketAmortizacion', $cotizacionSelect->IdCotizacion) }}">
                            @csrf
                            {{-- {!! Form::open([
                            'url' => '{{ route('imprimirTicketAmortizacion', 'a-' . $cotizacionSelect->IdCotizacion) }}',
                            'method' => 'POST',
                            'files' => true,
                            'class' => 'form-material',
                            'target' => '_blank',
                        ]) !!} --}}
                            <div class="modal-content">
                                <div class="modal-header text-inverse">
                                    <h6 class="modal-title" id="mySmallModalLabel2">Imprimir Amortización</h6>
                                </div>
                                <div class="modal-body">
                                    <div class="container">
                                        <label>Seleccionar tipo de impresión:</label>
                                        <select id="selectImpre" class="form-control" name="selectImpre">
                                            <option value="6">A4</option>
                                            <option value="7">Ticket</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Imprimir</button>
                                    <button type="button" class="btn btn-primary"
                                        data-dismiss="modal">Cancelar</button>
                                </div>
                            </div>
                            {{-- {!! Form::close() !!} --}}
                        </form>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                {{-- Fin --}}

                {{-- MODAL MOSTRAR ITEMS DE PAQUETE PROMOCIONAL --}}
                <div class="modal detallePaquetePromocional" id="detallePaquetePromocional" tabindex="-1"
                    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="text-center mt-4">
                                <h5 class="modal-title" id="exampleModalLabel">Detalle Paquete
                                    Promocional</h5>
                                <hr>
                            </div>
                            <div class="modal-body">
                                <table id="tableDetalle" class="table table-responsive-lg" style="width:100%">
                                    <thead>
                                        <tr class="bg-primary-contrast">
                                            <th scope="col" data-tablesaw-priority="persist">
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
                                        <input id="totalPaquete" class="input-transparent" type="text"
                                            name="totalPaquete" readonly>
                                    </article>
                                </section>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- FIN --}}
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
    </script>

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
    </script>
    <script>
        function cargarCorreo() {
            var venta = 0;
            var numCeroIzq = 0;
            $('#inpCorreo').val(venta['Email']);
            $('#inpCliente').val(venta['Nombres']);
            $('#inpComprobante').val(venta['Serie'] + '-' + numCeroIzq);
        }
    </script>
</body>

</html>
