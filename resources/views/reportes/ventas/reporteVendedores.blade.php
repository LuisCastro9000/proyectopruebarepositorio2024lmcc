@extends('layouts.app')
@section('title', 'Reporte de vendedores')
@section('content')



    {{-- Estilos de prueba --}}
    <style>
        .box-1 {
            background-image: linear-gradient(135deg, #f1be8f 10%, #F6851A 100%);
            border-radius: 5px;
        }

        .box-2 {
            background-image: linear-gradient(135deg, #9877a1 10%, #6B327C 100%);
        }

        .box-3 {
            background-image: linear-gradient(135deg, #bd859f 10%, #B53471 100%);
            /* background-color: #F6851A; */
        }

        .box-4 {
            background-image: linear-gradient(135deg, #5eade6 10%, #0061A6 100%);
            /* background-color: #F6851A; */
        }

        .box-5 {
            background-image: linear-gradient(135deg, #a797d6 10%, #775DD0 100%);
            /* background-color: #F6851A; */
        }

        .box-6 {
            background-image: linear-gradient(135deg, #f0daae 10%, #FEB019 100%);
            /* background-color: #F6851A; */
        }

        .box-7 {
            background-image: linear-gradient(135deg, #e78685 10%, #eb4d4b 100%);
            /* background-color: #F6851A; */
        }

        .box-8 {
            background-image: linear-gradient(135deg, #75ddc3 10%, #10ac84 100%);
            /* background-color: #F6851A; */
        }

        .nav-item .active {
            background-color: #10ac84 !important;
        }

        .box-numero {
            font-size: 24px;
            color: #ffff;
        }

        .card-datos {
            border: none !important;
            width: 24%;
            height: 105px;
        }

        .box-texto {
            color: #ffff;
            font-size: 18px
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

            .box-numero {
                font-size: 34px;
            }

            .box-texto {
                font-size: 20px;
            }
        }
    </style>
    {{-- Fin --}}


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
        {!! Form::open(['url' => '/reportes/ventas/vendedores', 'method' => 'POST', 'files' => true]) !!}
        {{ csrf_field() }}
        <div class="row clearfix">
            <div class="col-md-3 mt-4 order-md-0">
                <div class="form-group form-material">
                    <label>Vendedor</label>
                    <select class="m-b-10 form-control select2-hidden-accessible" id="vendedor" name="vendedor"
                        data-placeholder="Seleccione vendedor" data-toggle="select2" tabindex="-1" aria-hidden="true">
                        <option value="0">Seleccione Vendedor</option>
                        @foreach ($vendedores as $vendedor)
                            @if ($inputvendedor == $vendedor->IdUsuario)
                                <option value="{{ $vendedor->IdUsuario }}" selected>{{ $vendedor->Nombre }}</option>
                            @else
                                <option value="{{ $vendedor->IdUsuario }}">{{ $vendedor->Nombre }}</option>
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
                <x-selectorFiltrosFechas :obtenerDatos="'false'" :clases="'form-material'" />
            </div>
            <div class="col-md-1 col-4 mt-4 order-md-3">
                <div class="form-group">
                    <br>
                    {{-- <?php $inputvendedor = $inputvendedor == '' ? 0 : $inputvendedor; ?> --}}
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </div>
            <div class="col-md-2 col-4 mt-4 order-md-4">
                <br>
                <a class="m-1" target="_blank"
                    href='{{ url("reportes/ventas/excel-vendedores/$inputvendedor/$IdTipoPago/$fecha/$ini/$fin") }}'>
                    <span class="btn bg-excel ripple">
                        <i class="list-icon material-icons fs-20">explicit</i>XCEL
                    </span>
                </a>
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
        <div class="widget-list">
            {{-- <div class="row">
                <div class="col-md-3"></div>
                <div class="col-12 col-md-6 widget-holder widget-full-content widget-full-height">
                    <div class="widget-bg">
                        <div class="widget-heading">
                            <h5 class="widget-title">Reporte Vendedor</h5>
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

            @if (count($reporteVendedores) >= 1)
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab"
                            aria-controls="pills-home" aria-selected="true">Soles</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
                            aria-controls="pills-profile" aria-selected="false">Dolares</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab"
                            aria-controls="pills-contact" aria-selected="false">Ambos</a>
                    </li>
                    <li>
                        <a class="m-4" href="https://www.youtube.com/watch?v=WcPY5cJ2YIs" target="_blank">
                            <span class="btn btn-autocontrol-naranja ripple text-white">
                                Video Instructivo <i class="list-icon material-icons fs-24 color-icon">videocam</i>
                            </span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        Soles
                        {{-- Cuadros de soles --}}
                        <div class="row mb-2 mb-m-0">
                            <div class=" col-12 ">
                                <div class="d-flex justify-content-md-between flex-wrap">
                                    @foreach ($reporteVendedorGraficoSoles as $item)
                                        <div class="card card-datos  box-1">
                                            <div class="card-body">
                                                @if ($inputvendedor > 1)
                                                    <h3 class="card-title box-numero">{{ $item->Nombre }}</h3>
                                                @else
                                                    <h3 class="card-title box-numero">TODOS</h3>
                                                @endif
                                                <h6 class="card-subtitle mb-2 box-texto">Vendedor</h6>
                                            </div>
                                        </div>
                                        <div class="card card-datos  box-2">
                                            <div class="card-body">
                                                {{-- @if ($item->IdTipoPago == 1)
                                            <h3 class="card-title box-numero">CONTADO</h3>
                                        @else
                                            <h3 class="card-title box-numero">CREDITO</h3>
                                        @endif --}}

                                                @if ($IdTipoPago == 0)
                                                    <h3 class="card-title box-numero">TODOS</h3>
                                                @elseif ($IdTipoPago == 1)
                                                    <h3 class="card-title box-numero">CONTADO</h3>
                                                @elseif ($IdTipoPago == 2)
                                                    <h3 class="card-title box-numero">CREDITO</h3>
                                                @endif
                                                <h6 class="card-subtitle mb-2 box-texto">Tipo</h6>
                                            </div>
                                        </div>
                                        <div class="card card-datos  box-3">
                                            <div class="card-body">
                                                <h3 class="card-title box-numero">{{ $item->Total }}</h3>
                                                <h6 class="card-subtitle mb-2 box-texto">Ventas</h6>
                                            </div>
                                        </div>
                                        <div class="card card-datos  box-4">
                                            <div class="card-body">
                                                <h3 class="card-title box-numero">S/.
                                                    {{ number_format($item->totalventas, 2, '.', ',') }}</h3>
                                                <h6 class="card-subtitle mb-2 box-texto">Monto</h6>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2 ">
                            <div class=" col-12 ">
                                <div class="d-flex justify-content-md-between flex-wrap">
                                    @foreach ($reporteVendedorGraficoSoles as $item)
                                        <div class="card card-datos  box-5">
                                            <div class="card-body">
                                                <h3 class="card-title box-numero">{{ $item->totalBaja }}</h3>
                                                <h6 class="card-subtitle mb-2 box-texto">Bajas</h6>
                                            </div>
                                        </div>
                                        <div class="card card-datos  box-6">
                                            <div class="card-body">
                                                <h3 class="card-title box-numero">
                                                    S/.{{ number_format($item->montoBajas, 2, '.', ',') }}</h3>
                                                <h6 class="card-subtitle mb-7 box-texto">Monto</h6>
                                            </div>
                                        </div>
                                        <div class="card card-datos  box-7">
                                            <div class="card-body">
                                                <h3 class="card-title box-numero">{{ $item->TotalNotas }}</h3>
                                                <h6 class="card-subtitle mb-2 box-texto">Notas de Crédito</h6>
                                            </div>
                                        </div>
                                        <div class="card card-datos  box-8">
                                            <div class="card-body">
                                                <h3 class="card-title box-numero">S/.
                                                    {{ number_format($item->montoNotas, 2, '.', ',') }}</h3>
                                                <h6 class="card-subtitle mb-2 box-texto">Monto</h6>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        {{-- Fin --}}
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        Dolares
                        {{-- Cuadros de dolares --}}
                        <div class="row mb-2 mb-m-0">
                            <div class=" col-12 ">
                                <div class="d-flex justify-content-md-between flex-wrap">
                                    @foreach ($reporteVendedorGraficoDolares as $item)
                                        <div class="card card-datos  box-1">
                                            <div class="card-body">
                                                @if ($inputvendedor > 1)
                                                    <h3 class="card-title box-numero">{{ $item->Nombre }}</h3>
                                                @else
                                                    <h3 class="card-title box-numero">TODOS</h3>
                                                @endif
                                                <h6 class="card-subtitle mb-2 box-texto">Vendedor</h6>
                                            </div>
                                        </div>
                                        <div class="card card-datos  box-2">
                                            <div class="card-body">
                                                @if ($IdTipoPago == 0)
                                                    <h3 class="card-title box-numero">TODOS</h3>
                                                @elseif ($IdTipoPago == 1)
                                                    <h3 class="card-title box-numero">CONTADO</h3>
                                                @elseif ($IdTipoPago == 2)
                                                    <h3 class="card-title box-numero">CREDITO</h3>
                                                @endif
                                                <h6 class="card-subtitle mb-2 box-texto">Tipo</h6>
                                            </div>
                                        </div>
                                        <div class="card card-datos  box-3">
                                            <div class="card-body">
                                                <h3 class="card-title box-numero">{{ $item->Total }}</h3>
                                                <h6 class="card-subtitle mb-2 box-texto">Ventas</h6>
                                            </div>
                                        </div>
                                        <div class="card card-datos  box-4">
                                            <div class="card-body">
                                                <h3 class="card-title box-numero">$
                                                    {{ number_format($item->totalventas, 2, '.', ',') }}</h3>
                                                <h6 class="card-subtitle mb-2 box-texto">Monto</h6>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class=" col-12 ">
                                <div class="d-flex justify-content-md-between flex-wrap">
                                    @foreach ($reporteVendedorGraficoDolares as $item)
                                        <div class="card card-datos  box-5">
                                            <div class="card-body">
                                                <h3 class="card-title box-numero">{{ $item->totalBaja }}</h3>
                                                <h6 class="card-subtitle mb-2 box-texto">Bajas</h6>
                                            </div>
                                        </div>
                                        <div class="card card-datos  box-6">
                                            <div class="card-body">
                                                <h3 class="card-title box-numero">
                                                    ${{ number_format($item->montoBajas, 2, '.', ',') }}</h3>
                                                <h6 class="card-subtitle mb-7 box-texto">Monto</h6>
                                            </div>
                                        </div>
                                        <div class="card card-datos  box-7">
                                            <div class="card-body">
                                                <h3 class="card-title box-numero">{{ $item->TotalNotas }}</h3>
                                                <h6 class="card-subtitle mb-2 box-texto">Notas de Crédito</h6>
                                            </div>
                                        </div>
                                        <div class="card card-datos  box-8">
                                            <div class="card-body">
                                                <h3 class="card-title box-numero">$
                                                    {{ number_format($item->montoNotas, 2, '.', ',') }}</h3>
                                                <h6 class="card-subtitle mb-2 box-texto">Monto</h6>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        {{-- Fin --}}
                    </div>
                    <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                        Soles y Dolares
                        {{-- Cuadros de soles --}}
                        <div class="row mb-2 mb-m-0">
                            <div class=" col-12 ">
                                <div class="d-flex justify-content-md-between flex-wrap">
                                    @foreach ($reporteVendedorGraficoSoles as $item)
                                        <div class="card card-datos  box-1">
                                            <div class="card-body">
                                                @if ($inputvendedor > 1)
                                                    <h3 class="card-title box-numero">{{ $item->Nombre }}</h3>
                                                @else
                                                    <h3 class="card-title box-numero">TODOS</h3>
                                                @endif
                                                <h6 class="card-subtitle mb-2 box-texto">Vendedor</h6>
                                            </div>
                                        </div>
                                        <div class="card card-datos  box-2">
                                            <div class="card-body">
                                                {{-- @if ($item->IdTipoPago == 1)
                                            <h3 class="card-title box-numero">CONTADO</h3>
                                        @else
                                            <h3 class="card-title box-numero">CREDITO</h3>
                                        @endif --}}

                                                @if ($IdTipoPago == 0)
                                                    <h3 class="card-title box-numero">TODOS</h3>
                                                @elseif ($IdTipoPago == 1)
                                                    <h3 class="card-title box-numero">CONTADO</h3>
                                                @elseif ($IdTipoPago == 2)
                                                    <h3 class="card-title box-numero">CREDITO</h3>
                                                @endif
                                                <h6 class="card-subtitle mb-2 box-texto">Tipo</h6>
                                            </div>
                                        </div>
                                        <div class="card card-datos  box-3">
                                            <div class="card-body">
                                                <h3 class="card-title box-numero">{{ $item->Total }}</h3>
                                                <h6 class="card-subtitle mb-2 box-texto">Ventas</h6>
                                            </div>
                                        </div>
                                        <div class="card card-datos  box-4">
                                            <div class="card-body">
                                                <h3 class="card-title box-numero">S/.
                                                    {{ number_format($item->totalventas, 2, '.', ',') }}</h3>
                                                <h6 class="card-subtitle mb-2 box-texto">Monto</h6>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2 ">
                            <div class=" col-12 ">
                                <div class="d-flex justify-content-md-between flex-wrap">
                                    @foreach ($reporteVendedorGraficoSoles as $item)
                                        <div class="card card-datos  box-5">
                                            <div class="card-body">
                                                <h3 class="card-title box-numero">{{ $item->totalBaja }}</h3>
                                                <h6 class="card-subtitle mb-2 box-texto">Bajas</h6>
                                            </div>
                                        </div>
                                        <div class="card card-datos  box-6">
                                            <div class="card-body">
                                                <h3 class="card-title box-numero">
                                                    S/.{{ number_format($item->montoBajas, 2, '.', ',') }}</h3>
                                                <h6 class="card-subtitle mb-7 box-texto">Monto</h6>
                                            </div>
                                        </div>
                                        <div class="card card-datos  box-7">
                                            <div class="card-body">
                                                <h3 class="card-title box-numero">{{ $item->TotalNotas }}</h3>
                                                <h6 class="card-subtitle mb-2 box-texto">Notas de Crédito</h6>
                                            </div>
                                        </div>
                                        <div class="card card-datos  box-8">
                                            <div class="card-body">
                                                <h3 class="card-title box-numero">S/.
                                                    {{ number_format($item->montoNotas, 2, '.', ',') }}</h3>
                                                <h6 class="card-subtitle mb-2 box-texto">Monto</h6>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        {{-- Fin --}}

                        {{-- Cuadros de dolares --}}
                        <div class="row mb-2 mb-m-0">
                            <div class=" col-12 ">
                                <div class="d-flex justify-content-md-between flex-wrap">
                                    @foreach ($reporteVendedorGraficoDolares as $item)
                                        <div class="card card-datos  box-1">
                                            <div class="card-body">
                                                @if ($inputvendedor > 1)
                                                    <h3 class="card-title box-numero">{{ $item->Nombre }}</h3>
                                                @else
                                                    <h3 class="card-title box-numero">TODOS</h3>
                                                @endif
                                                <h6 class="card-subtitle mb-2 box-texto">Vendedor</h6>
                                            </div>
                                        </div>
                                        <div class="card card-datos  box-2">
                                            <div class="card-body">
                                                {{-- @if ($item->IdTipoPago == 1)
                                            <h3 class="card-title box-numero">CONTADO</h3>
                                        @else
                                            <h3 class="card-title box-numero">CREDITO</h3>
                                        @endif --}}
                                                @if ($IdTipoPago == 0)
                                                    <h3 class="card-title box-numero">TODOS</h3>
                                                @elseif ($IdTipoPago == 1)
                                                    <h3 class="card-title box-numero">CONTADO</h3>
                                                @elseif ($IdTipoPago == 2)
                                                    <h3 class="card-title box-numero">CREDITO</h3>
                                                @endif
                                                <h6 class="card-subtitle mb-2 box-texto">Tipo</h6>
                                            </div>
                                        </div>
                                        <div class="card card-datos  box-3">
                                            <div class="card-body">
                                                <h3 class="card-title box-numero">{{ $item->Total }}</h3>
                                                <h6 class="card-subtitle mb-2 box-texto">Ventas</h6>
                                            </div>
                                        </div>
                                        <div class="card card-datos  box-4">
                                            <div class="card-body">
                                                <h3 class="card-title box-numero">$
                                                    {{ number_format($item->totalventas, 2, '.', ',') }}</h3>
                                                <h6 class="card-subtitle mb-2 box-texto">Monto</h6>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class=" col-12 ">
                                <div class="d-flex justify-content-md-between flex-wrap">
                                    @foreach ($reporteVendedorGraficoDolares as $item)
                                        <div class="card card-datos  box-5">
                                            <div class="card-body">
                                                <h3 class="card-title box-numero">{{ $item->totalBaja }}</h3>
                                                <h6 class="card-subtitle mb-2 box-texto">Bajas</h6>
                                            </div>
                                        </div>
                                        <div class="card card-datos  box-6">
                                            <div class="card-body">
                                                <h3 class="card-title box-numero">
                                                    $ {{ number_format($item->montoBajas, 2, '.', ',') }}</h3>
                                                <h6 class="card-subtitle mb-7 box-texto">Monto</h6>
                                            </div>
                                        </div>
                                        <div class="card card-datos  box-7">
                                            <div class="card-body">
                                                <h3 class="card-title box-numero">{{ $item->TotalNotas }}</h3>
                                                <h6 class="card-subtitle mb-2 box-texto">Notas de Crédito</h6>
                                            </div>
                                        </div>
                                        <div class="card card-datos  box-8">
                                            <div class="card-body">
                                                <h3 class="card-title box-numero">$
                                                    {{ number_format($item->montoNotas, 2, '.', ',') }}</h3>
                                                <h6 class="card-subtitle mb-2 box-texto">Monto</h6>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        {{-- Fin --}}
                    </div>
                </div>
                @if ($inputvendedor > 1)
                    {{-- Graficos --}}
                    <div class="row my-4">
                        <div class="col-md-6 col-12">
                            <div class="card">
                                <br>
                                <div class="textoSoles" style="height: 480px">
                                    <div id="graficoSoles" height="380"></div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="card">
                                <br>
                                <div class="textoSoles" style="height: 480px">
                                    <div id="graficoDolares" height="380"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Fin --}}
                @endif
            @else
                <div class="col-md-12 col-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong class="alert-texto">No se encontraron Datos!</strong> Por Favor aplique los filtros para
                        realizar su
                        consulta.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            @endif



            {{-- NUEVO GRAFICO REPORTE VENDEDOR EN DOLARES --}}
            {{-- @if (count($reporteVendedores) >= 1)
                @if ($inputvendedor > 1)
                    <div class="row mb-2 mb-m-0">
                        <div class=" col-12 ">
                            <div class="d-flex justify-content-md-between flex-wrap">
                                @foreach ($reporteVendedorGraficoDolares as $item)
                                    <div class="card card-datos  box-1">
                                        <div class="card-body">
                                            @if ($inputvendedor > 1)
                                                <h3 class="card-title box-numero">{{ $item->Nombre }}</h3>
                                            @else
                                                <h3 class="card-title box-numero">TODOS</h3>
                                            @endif
                                            <h6 class="card-subtitle mb-2 box-texto">Vendedor</h6>
                                        </div>
                                    </div>
                                    <div class="card card-datos  box-2">
                                        <div class="card-body">
                                            @if ($item->IdTipoPago == 1)
                                                <h3 class="card-title box-numero">CONTADO</h3>
                                            @else
                                                <h3 class="card-title box-numero">CREDITO</h3>
                                            @endif
                                            <h6 class="card-subtitle mb-2 box-texto">Tipo</h6>
                                        </div>
                                    </div>
                                    <div class="card card-datos  box-3">
                                        <div class="card-body">
                                            <h3 class="card-title box-numero">{{ $item->Total }}</h3>
                                            <h6 class="card-subtitle mb-2 box-texto">Ventas</h6>
                                        </div>
                                    </div>
                                    <div class="card card-datos  box-4">
                                        <div class="card-body">
                                            <h3 class="card-title box-numero">S/.
                                                {{ number_format($item->totalventas, 2, '.', ',') }}</h3>
                                            <h6 class="card-subtitle mb-2 box-texto">Monto</h6>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <div class=" col-12 ">
                            <div class="d-flex justify-content-md-between flex-wrap">
                                @foreach ($reporteVendedorGraficoDolares as $item)
                                    <div class="card card-datos  box-5">
                                        <div class="card-body">
                                            <h3 class="card-title box-numero">{{ $item->totalBaja }}</h3>
                                            <h6 class="card-subtitle mb-2 box-texto">Bajas</h6>
                                        </div>
                                    </div>
                                    <div class="card card-datos  box-6">
                                        <div class="card-body">
                                            <h3 class="card-title box-numero">
                                                S/.{{ number_format($item->montoBajas, 2, '.', ',') }}</h3>
                                            <h6 class="card-subtitle mb-7 box-texto">Monto</h6>
                                        </div>
                                    </div>
                                    <div class="card card-datos  box-7">
                                        <div class="card-body">
                                            <h3 class="card-title box-numero">{{ $item->TotalNotas }}</h3>
                                            <h6 class="card-subtitle mb-2 box-texto">Notas de Crédito</h6>
                                        </div>
                                    </div>
                                    <div class="card card-datos  box-8">
                                        <div class="card-body">
                                            <h3 class="card-title box-numero">S/.
                                                {{ number_format($item->montoNotas, 2, '.', ',') }}</h3>
                                            <h6 class="card-subtitle mb-2 box-texto">Monto</h6>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row my-4">
                        <div class="col-md-6 col-12">
                            <div class="card">
                                <br>
                                <div class="textoSoles" style="height: 480px">
                                    <div id="graficoSoles" height="380"></div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="card">
                                <br>
                                <div class="textoSoles" style="height: 480px">
                                    <div id="graficoDolares" height="380"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row mb-2">
                        <div class=" col-12 ">
                            <div class="d-flex justify-content-md-between flex-wrap">
                                @foreach ($reporteVendedorGraficoDolares as $item)
                                    <div class="card card-datos  box-1">
                                        <div class="card-body">
                                            @if ($inputvendedor > 1)
                                                <h3 class="card-title box-numero">{{ $item->Nombre }}</h3>
                                            @else
                                                <h3 class="card-title box-numero">TODOS</h3>
                                            @endif
                                            <h6 class="card-subtitle mb-2 box-texto">Vendedor</h6>
                                        </div>
                                    </div>
                                    <div class="card card-datos  box-2">
                                        <div class="card-body">
                                            @if ($IdTipoPago == 0)
                                                <h3 class="card-title box-numero">TODOS</h3>
                                            @elseif ($IdTipoPago == 1)
                                                <h3 class="card-title box-numero">CONTADO</h3>
                                            @elseif ($IdTipoPago == 2)
                                                <h3 class="card-title box-numero">CREDITO</h3>
                                            @endif
                                            <h6 class="card-subtitle mb-2 box-texto">Tipo Pago</h6>
                                        </div>
                                    </div>
                                    <div class="card card-datos  box-3">
                                        <div class="card-body">
                                            <h3 class="card-title box-numero">{{ $item->Total }}</h3>
                                            <h6 class="card-subtitle mb-2 box-texto">Ventas</h6>
                                        </div>
                                    </div>
                                    <div class="card card-datos  box-4">
                                        <div class="card-body">
                                            <h3 class="card-title box-numero">S/.
                                                {{ number_format($item->totalventas, 2, '.', ',') }}</h3>
                                            <h6 class="card-subtitle mb-2 box-texto">Monto</h6>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2 ">
                        <div class=" col-12 ">
                            <div class="d-flex justify-content-md-between flex-wrap">
                                @foreach ($reporteVendedorGraficoDolares as $item)
                                    <div class="card card-datos  box-5">
                                        <div class="card-body">
                                            <h3 class="card-title box-numero">{{ $item->totalBaja }}</h3>
                                            <h6 class="card-subtitle mb-2 box-texto">Bajas</h6>
                                        </div>
                                    </div>
                                    <div class="card card-datos  box-6">
                                        <div class="card-body">
                                            <h3 class="card-title box-numero">
                                                S/.{{ number_format($item->montoBajas, 2, '.', ',') }}</h3>
                                            <h6 class="card-subtitle mb-7 box-texto">Monto</h6>
                                        </div>
                                    </div>
                                    <div class="card card-datos  box-7">
                                        <div class="card-body">
                                            <h3 class="card-title box-numero">{{ $item->TotalNotas }}</h3>
                                            <h6 class="card-subtitle mb-2 box-texto">Notas Crédito</h6>
                                        </div>
                                    </div>
                                    <div class="card card-datos  box-8">
                                        <div class="card-body">
                                            <h3 class="card-title box-numero">S/.
                                                {{ number_format($item->montoNotas, 2, '.', ',') }}</h3>
                                            <h6 class="card-subtitle mb-2 box-texto">Monto</h6>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="col-md-12 col-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong class="alert-texto">No se encontraron Datos!</strong> Por Favor aplique los filtros para
                        realizar su
                        consulta.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            @endif --}}
            {{-- FIN --}}

            <div class="row">
                {{-- <div class="row m-2">
                    <div class="col-md-12">
                        <div id="textoSoles">
                            <label class="fs-16">VENTAS SOLES</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div id="ventasContadoEfectivo" class="form-group">
                            <label class="form-control-label">T. Ventas: Contado(Efectivo)</label>
                            <div class="input-group">
                                <input id="vContadoEfectivo" type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div id="ventasContadoVisa" class="form-group">
                            <label class="form-control-label">T. Ventas: Contado(VISA)</label>
                            <div class="input-group">
                                <input id="vContadoVisa" type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div id="ventasContadoMastercard" class="form-group">
                            <label class="form-control-label">T. Ventas: Contado(MASTERCARD)</label>
                            <div class="input-group">
                                <input id="vContadoMastercard" type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div id="ventasContadoCuentasCorrientes" class="form-group">
                            <label class="form-control-label">T. Ventas: Contado(Ctas Ctes)</label>
                            <div class="input-group">
                                <input id="vContadoCuentasCorrientes" type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div id="ventasCredito" class="form-group">
                            <label class="form-control-label">T. Ventas: Crédito</label>
                            <div class="input-group">
                                <input id="vCredito" type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div id="descuentoContado" class="form-group">
                            <label class="form-control-label">T. Descuento: Contado</label>
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
                    </div>
                    <div class="col-md-3">
                        <div id="amortSoles" class="form-group">
                            <label class="form-control-label">T. Amortización: Contado</label>
                            <div class="input-group">
                                <input id="amortizacionSoles" type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                @if ($subniveles->contains('IdSubNivel', 46))
                    <div class="row m-2">
                        <div class="col-md-12">
                            <div id="textoDolares">
                                <label class="fs-16">VENTAS DÓLARES</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div id="ventasContadoEfectivoDolares" class="form-group">
                                <label class="form-control-label">T. Ventas: Contado(Efectivo)</label>
                                <div class="input-group">
                                    <input id="vContadoEfectivoDolares" type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div id="ventasContadoCuentasCorrientesDolares" class="form-group">
                                <label class="form-control-label">T. Ventas: Contado(Ctas Ctes)</label>
                                <div class="input-group">
                                    <input id="vContadoCuentasCorrientesDolares" type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div id="ventasCreditoDolares" class="form-group">
                                <label class="form-control-label">T. Ventas: Crédito</label>
                                <div class="input-group">
                                    <input id="vCreditoDolares" type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div id="descuentoContadoDolares" class="form-group">
                                <label class="form-control-label">T. Descuento: Contado</label>
                                <div class="input-group">
                                    <input id="dContadoDolares" type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div id="descuentoCreditoDolares" class="form-group">
                                <label class="form-control-label">Total Descuento: Crédito</label>
                                <div class="input-group">
                                    <input id="dCreditoDolares" type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div id="amortDolares" class="form-group">
                                <label class="form-control-label">T. Amortización: Contado</label>
                                <div class="input-group">
                                    <input id="amortizacionDolares" type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif --}}

                {{-- FIN --}}

                <div class="col-md-12 widget-holder mt-3">
                    <div class="widget-bg">
                        <!-- /.widget-heading -->
                        <div class="widget-body clearfix">
                            <!--<p>Listado de ventas</p>-->
                            <table id="table" class="table table-responsive-sm" style="width:100%">
                                <thead>
                                    <tr class="bg-primary">
                                        <th scope="col">Fecha Emitida</th>
                                        <th scope="col">Vendedor</th>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Código</th>
                                        <th scope="col">Documento Cliente</th>
                                        <th scope="col">Tipo Pago</th>
                                        <th scope="col">T. Efectivo</th>
                                        <th scope="col">T.Tarjeta</th>
                                        <th scope="col">Ctas ctes</th>
                                        <th scope="col">T. Pago</th>
                                        <th scope="col">Amortización</th>
                                        <th scope="col">Descuento</th>
                                        <th scope="col">Tipo Moneda</th>
                                        <th scope="col">Tipo Venta</th>
                                        <th scope="col">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reporteVendedores as $reportVendedor)
                                        <tr>
                                            <td>{{ $reportVendedor->FechaCreacion }}</td>
                                            <td>{{ $reportVendedor->Usuario }}</td>
                                            <td>{{ $reportVendedor->Nombres }}</td>
                                            <td>{{ $reportVendedor->Serie }} - {{ $reportVendedor->Numero }}</td>
                                            <td>{{ $reportVendedor->Documento }}</td>
                                            @if ($reportVendedor->IdTipoPago == 1)
                                                <td>Contado</td>
                                            @else
                                                <td>Crédito</td>
                                            @endif
                                            <td>{{ $reportVendedor->MontoEfectivo }}</td>
                                            <td>{{ $reportVendedor->MontoTarjeta }}</td>
                                            <td>{{ $reportVendedor->MontoCuentaBancaria }}</td>
                                            <td>{{ $reportVendedor->Total }}</td>
                                            <td>{{ $reportVendedor->Amortizacion }}</td>
                                            <td>{{ $reportVendedor->Exonerada }}</td>
                                            <td>{{ $reportVendedor->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                                            <td>{{ $reportVendedor->TipoVenta == 1 ? 'Gravada' : 'Exonerada' }}</td>
                                            <td>{{ $reportVendedor->Estado }}</td>
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
    </div>

    <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="text-success">Reportes de Ventas - Vendedor</h6>
                </div>
                <div class="modal-body form-material">
                    <div>
                        <label class="fs-14 negrita">Reporte del Mes</label>
                        <p class="fs-15negrita">Se mostraran solo las ventas de este mes....... Si desea ver ventas
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
    <!-- /.container -->
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

    {{-- SCRIPT DEL NUEVO GRAFICO  REPORTE VENTAS --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        var options = {
            series: [{
                name: [<?= implode(',', $grafCliente) ?>],
                type: 'column',
                data: [<?= implode(',', $grafTotal) ?>]
            }],
            chart: {
                height: 350,
                type: 'line',
                stacked: false
            },
            dataLabels: {
                enabled: true
            },
            stroke: {
                width: [1, 1, 4]
            },
            title: {
                text: 'Reporte Vendedor',
                // align: 'left',
                offsetX: 20
            },
            xaxis: {
                // categories: ["ENERO","FEBRERO"],
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
                        text: "Income (thousand crores)",
                        style: {
                            color: '#008FFB',
                        }
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                {
                    seriesName: 'Income',
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
                        text: "Operating Cashflow (thousand crores)",
                        style: {
                            color: '#00E396',
                        }
                    },
                },
                {
                    seriesName: 'Revenue',
                    opposite: true,
                    axisTicks: {
                        show: true,
                    },
                    axisBorder: {
                        show: true,
                        color: '#FEB019'
                    },
                    labels: {
                        style: {
                            colors: '#FEB019',
                        },
                    },
                    title: {
                        text: "Revenue (thousand crores)",
                        style: {
                            color: '#FEB019',
                        }
                    }
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
        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();


        // Grafico Soles
        var options = {
            series: [{
                data: [<?= implode(',', $resultadoDataSoles) ?>]
            }],
            chart: {
                type: 'bar',
                height: 480
            },
            plotOptions: {
                bar: {
                    barHeight: '100%',
                    distributed: true,
                    horizontal: true,
                    dataLabels: {
                        position: 'bottom'
                    },
                }
            },
            colors: ['#33b2df', '#546E7A', '#d4526e', '#13d8aa', '#A5978B', '#2b908f', '#f9a3a4', '#90ee7e',
                '#f48024', '#69d2e7'
            ],
            dataLabels: {
                enabled: true,
                textAnchor: 'start',
                style: {
                    colors: ['#0061A6']
                },
                formatter: function(val, opt) {
                    return opt.w.globals.labels[opt.dataPointIndex] + ":  " + val
                },
                offsetX: 0,
                dropShadow: {
                    enabled: false
                }
            },

            stroke: {
                width: 1,
                colors: ['#fff']
            },
            xaxis: {
                categories: ['Contado Efectivo', ' Contado T. visa', 'ContadoT. M. Card', 'Contado Ctas. Ctes.',
                    'Credito', ' Descuento Contado ', 'Descuento Credito', 'Amortizaciones'
                ],


            },
            yaxis: {
                labels: {
                    show: false
                }
            },
            title: {
                text: 'VENTAS EN SOLES',
                align: 'center',
                floating: true
            },
            subtitle: {
                text: '',
                align: 'center',
            },
            tooltip: {
                theme: 'dark',
                x: {
                    show: false
                },
                y: {
                    title: {
                        formatter: function() {
                            return ''
                        }
                    }
                }
            }
        };
        var chart = new ApexCharts(document.querySelector("#graficoSoles"), options);
        chart.render();

        //  Grafico Dolares
        var options = {
            series: [{
                data: [<?= implode(',', $resultadoDataDolares) ?>]
            }],
            chart: {
                type: 'bar',
                height: 480
            },
            plotOptions: {
                bar: {
                    barHeight: '100%',
                    distributed: true,
                    horizontal: true,
                    dataLabels: {
                        position: 'bottom'
                    },
                }
            },
            colors: ['#0fb9b1', '#10ac84', '#feca57', '#ff6b81', '#5f27cd', '#0abde3'],
            dataLabels: {
                enabled: true,
                textAnchor: 'start',
                style: {
                    colors: ['#0061A6']
                },
                formatter: function(val, opt) {
                    return opt.w.globals.labels[opt.dataPointIndex] + ":  " + val
                },
                offsetX: 0,
                dropShadow: {
                    enabled: false
                }
            },
            stroke: {
                width: 1,
                colors: ['#fff']
            },
            xaxis: {
                categories: [
                    'Contado Efectivo', 'Contado Ctas. Ctes.', 'Credito', 'Descuento Contado', 'Descuento Credito',
                    'Amortizaciones'
                ],


            },
            yaxis: {
                labels: {
                    show: false
                }
            },
            title: {
                text: 'VENTAS EN DOLARES',
                align: 'center',
                floating: true
            },
            subtitle: {
                text: '',
                align: 'center',
            },
            tooltip: {
                theme: 'dark',
                x: {
                    show: false
                },
                y: {
                    title: {
                        formatter: function() {
                            return ''
                        }
                    }
                }
            }
        };
        var chart = new ApexCharts(document.querySelector("#graficoDolares"), options);
        chart.render();
    </script>
    {{-- FIN --}}


    <script>
        var bandModal = <?php echo json_encode($IdTipoPago); ?>;

        if (bandModal == '') {
            $("#mostrarmodal").modal("show");
        }
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [<?= implode(',', $grafCliente) ?>],
                datasets: [{
                    label: 'Ventas',
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
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
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
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
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
            $('#textoSoles').hide();
            $('#textoDolares').hide();
            $('#ventasContadoEfectivo').hide();
            $('#ventasContadoEfectivoDolares').hide();
            $('#ventasContadoVisa').hide();
            $('#ventasContadoMastercard').hide();
            $('#ventasContadoCuentasCorrientes').hide();
            $('#ventasContadoCuentasCorrientesDolares').hide();
            $('#ventasCredito').hide();
            $('#ventasCreditoDolares').hide();
            $('#descuentoContado').hide();
            $('#descuentoContadoDolares').hide();
            $('#descuentoCredito').hide();
            $('#descuentoCreditoDolares').hide();
            $('#amortSoles').hide();
            $('#amortDolares').hide();
            var idTipoPago = <?php echo json_encode($IdTipoPago); ?>;
            var fecha = <?php echo json_encode($fecha); ?>;
            var vendedor = <?php echo json_encode($inputvendedor); ?>;
            if (vendedor != 0 && vendedor != null) {
                var ventasContadoEfectivo = <?php echo json_encode($ventasContadoEfectivo); ?>;
                var ventasContadoEfectivoDolares = <?php echo json_encode($ventasContadoEfectivoDolares); ?>;
                var ventasContadoVisa = <?php echo json_encode($ventasContadoVisa); ?>;
                var ventasContadoMastercard = <?php echo json_encode($ventasContadoMastercard); ?>;
                var ventasContadoCuentasCorrientes = <?php echo json_encode($ventasContadoCuentasCorrientes); ?>;
                var ventasContadoCuentasCorrientesDolares = <?php echo json_encode($ventasContadoCuentasCorrientesDolares); ?>;
                var ventasCredito = <?php echo json_encode($ventasCredito); ?>;
                var ventasCreditoDolares = <?php echo json_encode($ventasCreditoDolares); ?>;
                var descuentoContado = <?php echo json_encode($descuentoContado); ?>;
                var descuentoContadoDolares = <?php echo json_encode($descuentoContadoDolares); ?>;
                var descuentoCredito = <?php echo json_encode($descuentoCredito); ?>;
                var descuentoCreditoDolares = <?php echo json_encode($descuentoCreditoDolares); ?>;
                var amortizacionSoles = <?php echo json_encode($amortizacionesSoles); ?>;
                var amortizacionDolares = <?php echo json_encode($amortizacionesDolares); ?>;
                $('#textoSoles').show();
                $('#textoDolares').show();
                $('#ventasContadoEfectivo').show();
                $('#ventasContadoEfectivoDolares').show();
                $('#ventasContadoVisa').show();
                $('#ventasContadoMastercard').show();
                $('#ventasContadoCuentasCorrientes').show();
                $('#ventasContadoCuentasCorrientesDolares').show();
                $('#ventasCredito').show();
                $('#ventasCreditoDolares').show();
                $('#descuentoContado').show();
                $('#descuentoContadoDolares').show();
                $('#descuentoCredito').show();
                $('#descuentoCreditoDolares').show();
                $('#amortSoles').show();
                $('#amortDolares').show();
                $('#vContadoEfectivo').val(redondeo(ventasContadoEfectivo));
                $('#vContadoEfectivoDolares').val(redondeo(ventasContadoEfectivoDolares));
                $('#vContadoVisa').val(redondeo(ventasContadoVisa));
                $('#vContadoMastercard').val(redondeo(ventasContadoMastercard));
                $('#vContadoCuentasCorrientes').val(redondeo(ventasContadoCuentasCorrientes));
                $('#vContadoCuentasCorrientesDolares').val(redondeo(ventasContadoCuentasCorrientesDolares));
                $('#vCredito').val(redondeo(ventasCredito));
                $('#vCreditoDolares').val(redondeo(ventasCreditoDolares));
                $('#dContado').val(redondeo(descuentoContado));
                $('#dContadoDolares').val(redondeo(descuentoContadoDolares));
                $('#dCredito').val(redondeo(descuentoCredito));
                $('#dCreditoDolares').val(redondeo(descuentoCreditoDolares));
                $('#amortizacionSoles').val(redondeo(amortizacionSoles));
                $('#amortizacionDolares').val(redondeo(amortizacionDolares));
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
