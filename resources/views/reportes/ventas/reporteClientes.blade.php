@extends('layouts.app')
@section('title', 'Reporte de clientes')
@section('content')
    <style>
        .box-1 {
            background-image: linear-gradient(135deg, #f1be8f 10%, #F6851A 100%);
            border-radius: 5px;
        }

        .box-2 {
            background-image: linear-gradient(135deg, #ABDCFF 10%, #0396FF 100%);
        }

        .box-3 {
            background-image: linear-gradient(135deg, #bd859f 10%, #B53471 100%);
            /* background-color: #F6851A; */
        }

        .box-4 {
            background-image: linear-gradient(135deg, #5eade6 10%, #0061A6 100%);
            /* background-color: #F6851A; */
        }

        .box-texto {
            color: #ffff;
        }

        .box-texto-small {
            color: #ffff;
            font-size: 17px;
        }

        .card-datos {
            border: none !important;
            width: 24%;
            height: 105px;
        }

        @media(max-width: 1200px) {
            .card-datos {
                width: 50%;
            }
        }

        @media(max-width: 900px) {
            .card-datos {
                width: 50%;
            }
        }

        @media (max-width: 546px) {
            .card-datos {
                width: 100%;
                margin-bottom: 4px;
            }

            .box-texto {
                font-size: 34px;
            }

            .box-texto-small {
                font-size: 20px;
            }
        }
    </style>
    <div class="container">
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
        {!! Form::open(['url' => '/reportes/ventas/clientes', 'method' => 'POST', 'files' => true, 'id' => 'form']) !!}
        {{ csrf_field() }}
        <div class="row clearfix">

            <div class="col-md-3 mt-4 order-md-0">
                <div class="form-group form-material">
                    <label>Cliente</label>
                    <select class="m-b-10 form-control select2-hidden-accessible" id="cliente" name="cliente"
                        data-placeholder="Seleccione producto" data-toggle="select2" tabindex="-1" aria-hidden="true">
                        <option value="0">Seleccione Cliente</option>
                        @foreach ($clientes as $cliente)
                            @if ($inputcliente == $cliente->IdCliente)
                                <option value="{{ $cliente->IdCliente }} " selected>{{ $cliente->Nombre }}</option>
                            @else
                                <option value="{{ $cliente->IdCliente }} ">{{ $cliente->Nombre }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3 mt-4 order-md-1">
                <div class="form-group form-material">
                    <label>Tipo Pago</label>
                    <select id="tipoPago" class="form-control" name="tipoPago">
                        <option value="0">Todo</option>
                        <option value="1">Contado</option>
                        <option value="2">Crédito</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 mt-4 order-md-2">
                <x-selectorFiltrosFechas :obtenerDatos="'false'" :clases="'form-material'"></x-selectorFiltrosFechas>
            </div>
            <div class="col-md-1 mt-4 col-6 order-md-2">
                <div class="form-group container ">
                    <br>
                    <?php $inputcliente = $inputcliente == '' ? 0 : $inputcliente; ?>
                    <button id="boton" type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </div>
            <div class="col-md-2 col-6 mt-4 order-md-3">
                <div class="form-group">
                    <br>
                    <a class="p-0" target="_blank"
                        href='{{ url("reportes/ventas/excel-clientes/$inputcliente/$IdTipoPago/$fecha/$ini/$fin") }}'>
                        <span class="btn bg-excel ripple">
                            <i class="list-icon material-icons fs-20">explicit</i>XCEL
                        </span>
                    </a>
                </div>
            </div>
        </div>
        <x-inputFechasPersonalizadas :mostrarBoton="'false'" />
        {!! Form::close() !!}
    </div>
    <!-- /.container -->
    <!-- =================================== -->
    <!-- Different data widgets ============ -->
    <!-- =================================== -->
    <div class="container">
        {{-- <div class="row">
            <div class="col-12">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#soles" role="tab"
                            aria-controls="pills-home" aria-selected="true">Reporte en Soles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#dolares" role="tab"
                            aria-controls="pills-profile" aria-selected="false">Reporte en Dólares</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="soles" role="tabpanel" aria-labelledby="pills-home-tab">
                        @if ($inputcliente > 1 && count($graficoCliente) >= 1)
                            <div class=" col-12 ">
                                <div class="d-flex justify-content-md-between flex-wrap mb-5">
                                    <div class="card card-datos  box-1">
                                        <div class="card-body">
                                            <h4 class="card-title box-texto">S/.
                                                {{ number_format($ventasContado, 2, '.', ',') }}
                                            </h4>
                                            <h6 class="card-subtitle mb-2 box-texto-small">Ventas Contado</h6>
                                        </div>
                                    </div>
                                    <div class="card card-datos box-2">
                                        <div class="card-body">
                                            <h4 class="card-title box-texto">S/.
                                                {{ number_format($ventasCredito, 2, '.', ',') }}
                                            </h4>
                                            <h6 class="card-subtitle mb-2  box-texto-small">Ventas Crédito</h6>
                                        </div>
                                    </div>
                                    <div class="card card-datos box-3">
                                        <div class="card-body">
                                            <h4 class="card-title box-texto">S/.
                                                {{ number_format($descuentoContado, 2, '.', ',') }}
                                            </h4>
                                            <h6 class="card-subtitle mb-2  box-texto-small">Descuento Contado</h6>
                                        </div>
                                    </div>
                                    <div class="card card-datos box-4">
                                        <div class="card-body">
                                            <h4 class="card-title box-texto">S/.
                                                {{ number_format($descuentoCredito, 2, '.', ',') }}
                                            </h4>
                                            <h6 class="card-subtitle mb-2 box-texto-small">Descuento Crédito</h6>
                                        </div>
                                    </div>
                                </div>
                        @endif
                        @if (count($graficoCliente) >= 1)
                            @if ($inputcliente > 0)
                                @if ($fecha == 1 || $fecha == 2 || $fecha == 3)
                                    <div id="graficoClientes" style="min-height: 350px">
                                    </div>
                                @else
                                    <div id="graficoUnicoCliente">
                                    </div>
                                @endif
                            @else
                                <div id="graficoClientes" style="min-height: 350px">
                                </div>
                            @endif
                        @else
                            <div class="col-md-12 col-12  m-auto">
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>No se encontraron Datos!</strong> Por Favor aplique los filtros para realizar su
                                    consulta.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="tab-pane fade" id="dolares" role="tabpanel" aria-labelledby="pills-profile-tab">
                    @if ($inputcliente > 1 && count($graficoCliente) >= 1)
                        <div class=" col-12 ">
                            <div class="d-flex justify-content-md-between flex-wrap">

                                <div class="card card-datos  box-1">
                                    <div class="card-body">
                                        <h4 class="card-title box-texto">$
                                            {{ number_format($ventasContadoDolares, 2, '.', ',') }}</h4>
                                        <h6 class="card-subtitle mb-2 box-texto-small">Ventas Contado</h6>
                                    </div>
                                </div>
                                <div class="card card-datos box-2">
                                    <div class="card-body">
                                        <h4 class="card-title box-texto">$
                                            {{ number_format($ventasCreditoDolares, 2, '.', ',') }}</h4>
                                        <h6 class="card-subtitle mb-2  box-texto-small">Ventas Crédito</h6>
                                    </div>
                                </div>
                                <div class="card card-datos box-3">
                                    <div class="card-body">
                                        <h4 class="card-title box-texto">$
                                            {{ number_format($descuentoContadoDolares, 2, '.', ',') }}</h4>
                                        <h6 class="card-subtitle mb-2  box-texto-small">Descuento Contado</h6>
                                    </div>
                                </div>
                                <div class="card card-datos box-4">
                                    <div class="card-body">
                                        <h4 class="card-title box-texto">$
                                            {{ number_format($descuentoCreditoDolares, 2, '.', ',') }}</h4>
                                        <h6 class="card-subtitle mb-2 box-texto-small">Descuento Crédito</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div> --}}

        {{-- <div class="row mb-4">
            @if ($inputcliente > 1 && count($graficoCliente) >= 1)
                <div class=" col-12 ">
                    <div class="d-flex justify-content-md-between flex-wrap">

                        <div class="card card-datos  box-1">
                            <div class="card-body">
                                <h4 class="card-title box-texto">S/. {{ number_format($ventasContado, 2, '.', ',') }}
                                </h4>
                                <h6 class="card-subtitle mb-2 box-texto-small">Ventas Contado</h6>
                            </div>
                        </div>
                        <div class="card card-datos box-2">
                            <div class="card-body">
                                <h4 class="card-title box-texto">S/. {{ number_format($ventasCredito, 2, '.', ',') }}
                                </h4>
                                <h6 class="card-subtitle mb-2  box-texto-small">Ventas Crédito</h6>
                            </div>
                        </div>
                        <div class="card card-datos box-3">
                            <div class="card-body">
                                <h4 class="card-title box-texto">S/. {{ number_format($descuentoContado, 2, '.', ',') }}
                                </h4>
                                <h6 class="card-subtitle mb-2  box-texto-small">Descuento Contado</h6>
                            </div>
                        </div>
                        <div class="card card-datos box-4">
                            <div class="card-body">
                                <h4 class="card-title box-texto">S/. {{ number_format($descuentoCredito, 2, '.', ',') }}
                                </h4>
                                <h6 class="card-subtitle mb-2 box-texto-small">Descuento Crédito</h6>
                            </div>
                        </div>
                    </div>
                </div>

                <div class=" col-12 ">
                    <div class="d-flex justify-content-md-between flex-wrap">

                        <div class="card card-datos  box-1">
                            <div class="card-body">
                                <h4 class="card-title box-texto">$
                                    {{ number_format($ventasContadoDolares, 2, '.', ',') }}</h4>
                                <h6 class="card-subtitle mb-2 box-texto-small">Ventas Contado</h6>
                            </div>
                        </div>
                        <div class="card card-datos box-2">
                            <div class="card-body">
                                <h4 class="card-title box-texto">$
                                    {{ number_format($ventasCreditoDolares, 2, '.', ',') }}</h4>
                                <h6 class="card-subtitle mb-2  box-texto-small">Ventas Crédito</h6>
                            </div>
                        </div>
                        <div class="card card-datos box-3">
                            <div class="card-body">
                                <h4 class="card-title box-texto">$
                                    {{ number_format($descuentoContadoDolares, 2, '.', ',') }}</h4>
                                <h6 class="card-subtitle mb-2  box-texto-small">Descuento Contado</h6>
                            </div>
                        </div>
                        <div class="card card-datos box-4">
                            <div class="card-body">
                                <h4 class="card-title box-texto">$
                                    {{ number_format($descuentoCreditoDolares, 2, '.', ',') }}</h4>
                                <h6 class="card-subtitle mb-2 box-texto-small">Descuento Crédito</h6>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div> --}}
    </div>


    <div class="container">
        <div class="widget-list">
            {{-- <div class="row">
            <div class="col-md-3"></div>
                <div class="col-12 col-md-6 widget-holder widget-full-content widget-full-height">
                    <div class="widget-bg">
                        <div class="widget-heading">
                            <h5 class="widget-title">Reporte Cliente</h5>
                        </div>
                        <div class="widget-body">
                            <div class="" style="height: 400px">
                                <!--<canvas id="_chartJsPie"></canvas>-->
                                <canvas id="myChart" height="200"></canvas>
                            </div>
                        </div>
                        <!-- /.widget-body -->
                    </div>
                </div>
                <div class="col-md-3 ">
                </div>
            </div> --}}

            {{-- GRAFICO CLIENTES --}}
            <div class="row mb-4">

                {{-- <div class="col-md-3">
                <div id="ventasContado" class="form-group">
                    <label class="form-control-label">Total Ventas: Contado</label>
                    <div class="input-group">
                        <input id="vContado" type="text" class="form-control">
                    </div>
                </div>
                </div>
                <div class="col-md-3">
                    <div id="ventasCredito" class="form-group">
                        <label class="form-control-label">Total Ventas: Crédito</label>
                        <div class="input-group">
                            <input id="vCredito" type="text" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div id="descuentoContado" class="form-group">
                        <label class="form-control-label">Total Descuento: Contado</label>
                        <div class="input-group">
                            <input id="dContado" type="text" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div id="descuentoCredito" class="form-group">
                        <label class="form-control-label">Total Descuento: Crédito</label>
                        <div class="input-group">
                            <input id="dCredito" type="text" class="form-control">
                        </div>
                    </div>
                </div> --}}

                @if (count($graficoCliente) >= 1)
                    @if ($inputcliente > 0)
                        {{-- GARFICOS --}}
                        <div class="col-12">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#soles"
                                        role="tab" aria-controls="pills-home" aria-selected="true">Reporte en Soles</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#dolares"
                                        role="tab" aria-controls="pills-profile" aria-selected="false">Reporte en
                                        Dólares</a>
                                </li>
                                <li>
                                    <a href="https://www.youtube.com/watch?v=WcPY5cJ2YIs" target="_blank">
                                        <span class="btn btn-autocontrol-naranja ripple text-white">
                                            Video Instructivo <i
                                                class="list-icon material-icons fs-24 color-icon">videocam</i>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                {{-- Grafico en Soles --}}
                                <div class="tab-pane fade show active mt-3" id="soles" role="tabpanel"
                                    aria-labelledby="pills-home-tab">
                                    <div class=" col-12 ">
                                        <div class="d-flex justify-content-md-between flex-wrap mb-5">
                                            <div class="card card-datos  box-1">
                                                <div class="card-body">
                                                    <h4 class="card-title box-texto">S/.
                                                        {{ number_format($ventasContado, 2, '.', ',') }}
                                                    </h4>
                                                    <h6 class="card-subtitle mb-2 box-texto-small">Ventas Contado</h6>
                                                </div>
                                            </div>
                                            <div class="card card-datos box-2">
                                                <div class="card-body">
                                                    <h4 class="card-title box-texto">S/.
                                                        {{ number_format($ventasCredito, 2, '.', ',') }}
                                                    </h4>
                                                    <h6 class="card-subtitle mb-2  box-texto-small">Ventas Crédito</h6>
                                                </div>
                                            </div>
                                            <div class="card card-datos box-3">
                                                <div class="card-body">
                                                    <h4 class="card-title box-texto">S/.
                                                        {{ number_format($descuentoContado, 2, '.', ',') }}
                                                    </h4>
                                                    <h6 class="card-subtitle mb-2  box-texto-small">Descuento Contado</h6>
                                                </div>
                                            </div>
                                            <div class="card card-datos box-4">
                                                <div class="card-body">
                                                    <h4 class="card-title box-texto">S/.
                                                        {{ number_format($descuentoCredito, 2, '.', ',') }}
                                                    </h4>
                                                    <h6 class="card-subtitle mb-2 box-texto-small">Descuento Crédito</h6>
                                                </div>
                                            </div>
                                            @if ($fecha == 1 || $fecha == 2 || $fecha == 3)
                                                <div class="col-md-12 col-12 mt-5 d-flex justify-content-center">
                                                    <div id="graficoClientesSoles">
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-md-12 col-12 mt-5">
                                                    <div id="graficoUnicoCliente">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                {{-- Fin --}}
                                {{-- Grafico en Dolares --}}
                                <div class="tab-pane fade mt-3" id="dolares" role="tabpanel"
                                    aria-labelledby="pills-profile-tab">
                                    <div class=" col-12 ">
                                        <div class="d-flex justify-content-md-between flex-wrap">
                                            <div class="card card-datos  box-1">
                                                <div class="card-body">
                                                    <h4 class="card-title box-texto">$
                                                        {{ number_format($ventasContadoDolares, 2, '.', ',') }}</h4>
                                                    <h6 class="card-subtitle mb-2 box-texto-small">Ventas Contado</h6>
                                                </div>
                                            </div>
                                            <div class="card card-datos box-2">
                                                <div class="card-body">
                                                    <h4 class="card-title box-texto">$
                                                        {{ number_format($ventasCreditoDolares, 2, '.', ',') }}</h4>
                                                    <h6 class="card-subtitle mb-2  box-texto-small">Ventas Crédito</h6>
                                                </div>
                                            </div>
                                            <div class="card card-datos box-3">
                                                <div class="card-body">
                                                    <h4 class="card-title box-texto">$
                                                        {{ number_format($descuentoContadoDolares, 2, '.', ',') }}
                                                    </h4>
                                                    <h6 class="card-subtitle mb-2  box-texto-small">Descuento Contado
                                                    </h6>
                                                </div>
                                            </div>
                                            <div class="card card-datos box-4">
                                                <div class="card-body">
                                                    <h4 class="card-title box-texto">$
                                                        {{ number_format($descuentoCreditoDolares, 2, '.', ',') }}
                                                    </h4>
                                                    <h6 class="card-subtitle mb-2 box-texto-small">Descuento Crédito
                                                    </h6>
                                                </div>
                                            </div>
                                            @if ($fecha == 1 || $fecha == 2 || $fecha == 3)
                                                <div class="col-md-12 col-12 mt-5 d-flex justify-content-center">
                                                    <div id="graficoClientesDolares">
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-md-12 col-12 mt-5">
                                                    <div id="graficoUnicoClienteDolares">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                {{-- fin --}}
                            </div>
                        </div>
                        {{-- FIN --}}
                    @else
                        <div class="col-md-12 col-12">
                            <a href="https://www.youtube.com/watch?v=WcPY5cJ2YIs" target="_blank">
                                <span class="btn btn-autocontrol-naranja ripple text-white">
                                    Video Instructivo <i class="list-icon material-icons fs-24 color-icon">videocam</i>
                                </span>
                            </a>
                            <br><br>
                            <div class="card d-flex justify-content-center align-items-center ">
                                <br>
                                <div id="graficoClientes" style="min-height: 350px">
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="col-md-12 col-12  m-auto">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>No se encontraron Datos!</strong> Por Favor aplique los filtros para realizar su
                            consulta.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            {{-- FIN --}}

            <div class="row mt-5">
                <div class="col-md-12 widget-holder">
                    <div class="widget-bg">
                        <!-- /.widget-heading -->
                        <div class="widget-body clearfix">
                            <!--<p>Listado de ventas</p>-->
                            <table id="table" class="table table-responsive-sm" style="width:100%">
                                <thead>
                                    <tr class="bg-primary">
                                        <th scope="col">Fecha Emitida</th>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Código</th>
                                        <th scope="col">Tipo de Pago</th>
                                        <th scope="col">Tipo Venta</th>
                                        <th scope="col">Tipo Moneda</th>
                                        <th scope="col">Descuento</th>
                                        <th scope="col">Total Pago</th>
                                        <th scope="col">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reporteClientes as $reportCliente)
                                        <tr>
                                            <td>{{ $reportCliente->FechaCreacion }}</td>
                                            <td>{{ $reportCliente->Nombres }}</td>
                                            <td>{{ $reportCliente->Serie }} - {{ $reportCliente->Numero }}
                                            </td>
                                            @if ($reportCliente->IdTipoPago == 1)
                                                <td>Contado</td>
                                            @else
                                                <td>Crédito</td>
                                            @endif
                                            <td>{{ $reportCliente->TipoVenta == 1 ? 'Gravada' : 'Exonerada' }}
                                            </td>
                                            <td>{{ $reportCliente->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}
                                            </td>
                                            <td>{{ $reportCliente->Exonerada }}</td>
                                            <td>{{ $reportCliente->Total }}</td>
                                            <td>{{ $reportCliente->Estado }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
    <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="text-success">Reportes de Ventas - Clientes</h6>
                </div>
                <div class="modal-body form-material">
                    <div>
                        <label class="fs-14 negrita">Reporte del Mes</label>
                        <p class="fs-15negrita">Se mostraran solo las ventas de este mes....... Si desea ver
                            ventas
                            anteriores utilize los filtros</p>
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
@stop

<!-- Estas variables son usadas en el archivo assets/js/utilidades/scriptFechasPersonalizadas.js-->
@section('variablesJs')
    <script>
        const variablesBlade = {
            fecha: @json($fecha),
            fechaInicial: @json($fechaInicial),
            fechaFinal: @json($fechaFinal),
        }
    </script>
@endsection

@section('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/1.9.2/countUp.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/jquery.vmap.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/jquery.vmap.usa.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-circle-progress/1.2.2/circle-progress.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


    {{-- <script>
        $(document).ready(function() {
            $('#graficoUnicoCliente').hide();
        });
    </script> --}}

    <script>
        var ArrayMes = [];
        var grafUnicoCliente = <?php echo json_encode($grafUnicoCliente); ?>;
        var arrayFechasFiltros = <?php echo json_encode($arrayFechasFiltros); ?>;
        var grafUnicoClienteDolares = <?php echo json_encode($grafUnicoClienteDolares); ?>;
        var arrayFechasDolares = <?php echo json_encode($arrayFechasDolares); ?>;
        var graficoTotal = <?php echo json_encode($grafTotal); ?>;
        var graficoCliente = <?php echo json_encode($grafCliente); ?>;


        // GRAFICO TODOS LOS CLIENTE SOLES
        var options = {
            series: [<?= implode(',', $grafTotal) ?>],
            chart: {
                width: 800,
                type: 'pie',
            },
            // colors: ['#33b2df', '#546E7A', '#d4526e', '#13d8aa', '#A5978B', '#2b908f', '#f9a3a4', '#90ee7e',
            //         '#f48024', '#69d2e7'
            //     ],
            dataLabels: {
                enabled: false
            },

            labels: [<?= implode(',', $grafCliente) ?>],
            responsive: [{
                breakpoint: 400,
                options: {
                    chart: {
                        width: 400
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }, {
                breakpoint: 800,
                options: {
                    chart: {
                        width: 600
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }, {
                breakpoint: 1000,
                options: {
                    chart: {
                        width: 600
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }, {
                breakpoint: 1600,
                options: {
                    chart: {
                        width: 600
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };
        var chart = new ApexCharts(document.querySelector("#graficoClientes"), options);
        chart.render();

        // GRAFICO UNICO CLIENTE SOLES

        var options = {
            series: [{
                name: 'Total',
                data: grafUnicoCliente,
            }],
            chart: {
                type: 'area',
                height: 350,
                zoom: {
                    enabled: false
                }
            },
            colors: ['#30BC98'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'straight'
            },

            title: {
                text: 'REPORTE UNICO CLIENTE',
                align: 'left'
            },
            subtitle: {
                text: 'Price Movements',
                align: 'left'
            },

            xaxis: {
                // type: 'category',
                categories: arrayFechasFiltros,
                // labels: {
                //     formatter: function(value, timestamp) {
                //         return new Date(timestamp) // The formatter function overrides format property
                //     },
                // }
            },

            yaxis: {
                opposite: false,
            },
            legend: {
                horizontalAlign: 'right',
            }


        };
        var chart = new ApexCharts(document.querySelector("#graficoUnicoCliente"), options);
        chart.render();

        // GRAFICO UNICO CLIENTE CIRCULAR SOLES
        var nombreVentasSoles = <?php echo json_encode($nombreVentasSoles); ?>;
        var totalVentasSoles = <?php echo json_encode($totalVentasSoles); ?>;

        var options = {
            series: [totalVentasSoles],
            chart: {
                width: 600,
                type: 'pie',
            },
            // colors: ['#33b2df', '#546E7A', '#d4526e', '#13d8aa', '#A5978B', '#2b908f', '#f9a3a4', '#90ee7e',
            //         '#f48024', '#69d2e7'
            //     ],
            dataLabels: {
                enabled: false
            },

            labels: [nombreVentasSoles],
            responsive: [{
                breakpoint: 400,
                options: {
                    chart: {
                        width: 300
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }, {
                breakpoint: 600,
                options: {
                    chart: {
                        width: 400
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }, {
                breakpoint: 800,
                options: {
                    chart: {
                        width: 600
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }, {
                breakpoint: 1600,
                options: {
                    chart: {
                        width: 500
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };
        var chart = new ApexCharts(document.querySelector("#graficoClientesSoles"), options);
        chart.render();

        // GRAFICO UNICO CLIENTE DOLARES

        var options = {
            series: [{
                name: 'Total',
                data: grafUnicoClienteDolares,
            }],
            chart: {
                type: 'area',
                height: 350,
                zoom: {
                    enabled: false
                }
            },
            colors: ['#30BC98'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'straight'
            },

            title: {
                text: 'REPORTE UNICO CLIENTE',
                align: 'left'
            },
            subtitle: {
                text: 'Price Movements',
                align: 'left'
            },

            xaxis: {
                // type: 'category',
                categories: arrayFechasDolares,
                // labels: {
                //     formatter: function(value, timestamp) {
                //         return new Date(timestamp) // The formatter function overrides format property
                //     },
                // }
            },

            yaxis: {
                opposite: false,
            },
            legend: {
                horizontalAlign: 'right',
            }
        };

        var chart = new ApexCharts(document.querySelector("#graficoUnicoClienteDolares"), options);
        chart.render();

        // GRAFICO UNICO CLIENTE CIRCULAR DOLARES
        var nombreClienteDolares = <?php echo json_encode($nombreVentasDolares); ?>;
        var totalVentasDolares = <?php echo json_encode($totalVentasDolares); ?>;

        var options = {
            series: [totalVentasDolares],
            chart: {
                width: 600,
                type: 'pie',
            },
            // colors: ['#33b2df', '#546E7A', '#d4526e', '#13d8aa', '#A5978B', '#2b908f', '#f9a3a4', '#90ee7e',
            //         '#f48024', '#69d2e7'
            //     ],
            dataLabels: {
                enabled: false
            },

            labels: [nombreClienteDolares],
            responsive: [{
                breakpoint: 400,
                options: {
                    chart: {
                        width: 300
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }, {
                breakpoint: 600,
                options: {
                    chart: {
                        width: 400
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }, {
                breakpoint: 800,
                options: {
                    chart: {
                        width: 600
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }, {
                breakpoint: 1600,
                options: {
                    chart: {
                        width: 500
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };
        var chart = new ApexCharts(document.querySelector("#graficoClientesDolares"), options);
        chart.render();
    </script>


    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [<?= implode(',', $grafCliente) ?>],
                datasets: [{
                    label: 'Compras',
                    data: [<?= implode(',', $grafTotal) ?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }],
                    xAxes: [{
                        ticks: {
                            display: false
                        }
                    }]
                }
            }
        });
    </script>



    <script>
        $(function() {
            var bandModal = <?php echo json_encode($IdTipoPago); ?>;

            if (bandModal == '') {
                $("#mostrarmodal").modal("show");
            }
            $('#ventasContado').hide();
            $('#ventasCredito').hide();
            $('#descuentoContado').hide();
            $('#descuentoCredito').hide();
            var idTipoPago = <?php echo json_encode($IdTipoPago); ?>;
            var cliente = <?php echo json_encode($inputcliente); ?>;
            if (cliente != '' && cliente != null) {
                var ventasContado = <?php echo json_encode($ventasContado); ?>;
                var ventasCredito = <?php echo json_encode($ventasCredito); ?>;
                var descuentoContado = <?php echo json_encode($descuentoContado); ?>;
                var descuentoCredito = <?php echo json_encode($descuentoCredito); ?>;
                $('#ventasContado').show();
                $('#ventasCredito').show();
                $('#descuentoContado').show();
                $('#descuentoCredito').show();
                $('#vContado').val(redondeo(ventasContado));
                $('#vCredito').val(redondeo(ventasCredito));
                $('#dContado').val(redondeo(descuentoContado));
                $('#dCredito').val(redondeo(descuentoCredito));
            }
            $('#tipoPago option[value=' + idTipoPago + ']').prop('selected', true);
        });

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
    </script>
    <script type="text/javascript">
        $(function() {
            $(document).ready(function() {
                $('#table').DataTable({
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
@stop
