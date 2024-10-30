@extends('layouts.app')
@section('title', 'Reporte de ganacias')
@section('content')
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

    <style>
        .card-border--10 {
            border-radius: 15px;
        }

        .fs-25px {
            font-size: 35px;
        }

        .card-btn {
            background-color: #0095E8;
            color: #FFF;
            padding: 3px;
            font-size: 14px;
        }

        .card-btn:hover {
            background-color: #1aa3ec;
        }

        .card-title {
            font-size: 16px;
        }

        .card-title--verde {
            color: #222831;
            font-weight: bold;
            font-size: 25px;
        }

        .card-datos--number {
            color: #444444;
            font-size: 35px;
            font-weight: bold;
        }

        .card-datos {
            font-weight: 600;
        }

        .card-datos--title {
            color: #0095E8;
            font-size: 20px;
        }

        .card-datos--title-gananciaBruta {
            color: #9DA0B1;
            font-size: 20px;
        }

        .card-datos--sub-title {
            color: #4E5BA9;
            font-size: 13px;
        }

        .card-header--soles {
            background-color: #0095E8 !important;
            color: #FFF !important;
        }

        .card-header--dolares {
            background-color: #5f27cd !important;
            color: #FFF !important;
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

        <br />
        <br />


        {{-- CAMBIOS DE GRAFICOS --}}
        {!! Form::open([
            'url' => '/reportes/gerenciales/ganancias',
            'method' => 'POST',
            'id' => 'formObtenerDatos',
            'files' => true,
        ]) !!}
        {{ csrf_field() }}
        <div class="row mb-4">
            <div class="col-md-3 mt-4 order-md-1 d-none">
                <div class="form-group form-material">
                    <label>Tipo Pago</label>
                    <select id="tipoPago" class="form-control" name="tipoPago">
                        <option value="0">Todo</option>
                        <option value="1">Contado</option>
                        <option value="2">Crédito</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 mt-4 order-md-1 d-none">
                <div class="form-group form-material">
                    <label>Tipo Pago</label>
                    <select id="tipoMoneda" class="form-control" name="tipoMoneda">
                        <option value="1">Soles</option>
                        <option value="2">Dólares</option>
                    </select>
                </div>
            </div>

            <div class="col-md-9 ">
                <x-selectorFiltrosFechas metodoObtenerDatos='submit' />
            </div>
            <section class="col-md-3 mt-4 form-group align-self-end">
                <a href="https://www.youtube.com/watch?v=Zc9iC9SwAq8&feature=youtu.be" target="_blank">
                    <span class="btn btn-autocontrol-naranja ripple text-white w-100">
                        Video Instructivo <i class="list-icon material-icons fs-23 color-icon">videocam</i>
                    </span>
                </a>
            </section>
        </div>
        <x-inputFechasPersonalizadas />
        {!! Form::close() !!}

        <section class="reporte-Ganancias">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#gananciaBruta" role="tab"
                        aria-controls="pills-home" aria-selected="true">Ganancia Bruta</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#gananciaNeto" role="tab"
                        aria-controls="pills-profile" aria-selected="false">Ganancia Neta</a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                {{-- Ganancia Bruta --}}
                <div class="tab-pane fade show active" id="gananciaBruta" role="tabpanel" aria-labelledby="pills-home-tab">
                    <div class="row mb-2">
                        <div class="col">
                            <h5 class="card-title--verde">Ganancias en Soles</h5>
                        </div>
                    </div>
                    <section class="Ganancias-soles">
                        <div class="row">
                            {{-- Ganacias en Soles al Contado --}}
                            <div class="col-12 col-lg-12 ganancias-Soles-Contado">
                                <div class="card card-border--10">
                                    <div class="card-header card-header--soles text-center">
                                        <p class="card-title font-weight-bold">Reporte de Ganancias al Contado</p>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- Lista de Datos de ganancias --}}
                                            <div class="col-12 col-lg-6">
                                                <div
                                                    class="card border border-top-0 border-right-0 border-left-0 py-2 mb-3">
                                                    <section
                                                        class="d-flex justify-content-between align-items-center flex-column flex-sm-row">
                                                        <article class="card-datos card-datos--title-gananciaBruta ">Ventas
                                                        </article>
                                                        <article>
                                                            <span class="fs-20 ">S/.</span>
                                                            <span class="card-datos--number">
                                                                {{ number_format($precioSolesContado, 2, '.', ',') }}</span>
                                                        </article>
                                                    </section>
                                                </div>
                                                <div
                                                    class="card border border-top-0 border-right-0 border-left-0 py-2 mb-3">
                                                    <section
                                                        class="d-flex justify-content-between align-items-center flex-column flex-sm-row">
                                                        <article class="card-datos card-datos--title-gananciaBruta ">Costo
                                                        </article>
                                                        <article>
                                                            <span class="fs-20 ">S/.</span>
                                                            <span
                                                                class="card-datos--number">{{ number_format($costoSolesContado, 2, '.', ',') }}</span>
                                                        </article>
                                                    </section>
                                                </div>
                                                <div class="card border border-0 py-2">
                                                    <section
                                                        class="d-flex justify-content-between align-items-center flex-column flex-sm-row">
                                                        <article class="card-datos card-datos--title-gananciaBruta ">
                                                            Ganancias
                                                        </article>
                                                        <article>
                                                            <span class="fs-20 ">S/.</span>
                                                            <span class="card-datos--number">
                                                                {{ number_format($gananciaSolesContado, 2, '.', ',') }}
                                                            </span>
                                                        </article>
                                                    </section>
                                                </div>
                                            </div>
                                            {{-- Fin --}}
                                            {{-- Grafico de ganancias al contado en Soles --}}
                                            <div class="col-12 col-lg-6">
                                                <div class="card h-100">
                                                    <div class="d-flex justify-content-center">
                                                        <section id="graficoGananciasContadoSoles"></section>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- Fin --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Fin --}}
                            {{-- Ganacias en Soles al Credito --}}
                            <div class="col-12 col-lg-12 ganancias-Soles-Credito mt-4">
                                <div class="card card-border--10">
                                    <div class="card-header card-header--soles text-center">
                                        <p class="card-title font-weight-bold">Reporte de Ganancias al Crédito </p>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- Lista de Datos de ganancias --}}
                                            <div class="col-12 col-lg-6">
                                                <div
                                                    class="card border border-top-0 border-right-0 border-left-0 py-2 mb-3">
                                                    <section
                                                        class="d-flex justify-content-between align-items-center flex-column flex-sm-row">
                                                        <article class="card-datos card-datos--title-gananciaBruta ">Ventas
                                                        </article>
                                                        <article>
                                                            <span class="fs-20 ">S/.</span>
                                                            <span
                                                                class="card-datos--number">{{ number_format($precioSolesCredito, 2, '.', ',') }}</span>
                                                        </article>
                                                    </section>
                                                </div>
                                                <div
                                                    class="card border border-top-0 border-right-0 border-left-0 py-2 mb-3">
                                                    <section
                                                        class="d-flex justify-content-between align-items-center flex-column flex-sm-row">
                                                        <article class="card-datos card-datos--title-gananciaBruta ">Costo
                                                        </article>
                                                        <article>
                                                            <span class="fs-20 ">S/.</span>
                                                            <span
                                                                class="card-datos--number">{{ number_format($costoSolesCredito, 2, '.', ',') }}</span>
                                                        </article>
                                                    </section>
                                                </div>
                                                <div class="card border border-0 py-2">
                                                    <section
                                                        class="d-flex justify-content-between align-items-center flex-column flex-sm-row">
                                                        <article class="card-datos card-datos--title-gananciaBruta ">
                                                            Ganancias
                                                        </article>
                                                        <article class="">
                                                            <span class="fs-20 ">S/.</span>
                                                            <span
                                                                class="card-datos--number">{{ number_format($gananciaSolesCredito, 2, '.', ',') }}</span>
                                                        </article>
                                                    </section>
                                                </div>
                                            </div>
                                            {{-- Fin --}}
                                            {{-- Grafico de ganancias al contado en Soles --}}
                                            <div class="col-12 col-lg-6">
                                                <div class="card h-100">
                                                    <div class="d-flex justify-content-center">
                                                        <section id="graficoGananciasCreditoSoles"></section>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- Fin --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Fin --}}
                        </div>
                    </section>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <div class="row mt-4">
                            <div class="col">
                                <h5 class="card-title--verde">Ganancias en Dólares</h5>
                            </div>
                        </div>
                        <section class="Ganancias-Dolares">
                            <div class="row mb-4">
                                {{-- Ganacias en Dolares al Contado --}}
                                <div class="col-12 col-lg-12 ganancias-Dolares-Contado">
                                    <div class="card card-border--10 ">
                                        <div class="card-header card-header--dolares text-center">
                                            <p class="card-title font-weight-bold">Reporte de Ganancias al Contado</p>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                {{-- Lista de Datos de ganancias --}}
                                                <div class="col-12 col-lg-6">
                                                    <div
                                                        class="card border border-top-0 border-right-0 border-left-0 py-2 mb-3">
                                                        <section
                                                            class="d-flex justify-content-between align-items-center flex-column flex-sm-row">
                                                            <article class="card-datos card-datos--title-gananciaBruta ">
                                                                Ventas
                                                            </article>
                                                            <article>
                                                                <span class="fs-20 ">$</span>
                                                                <span
                                                                    class="card-datos--number">{{ number_format($precioDolaresContado, 2, '.', ',') }}</span>
                                                            </article>
                                                        </section>
                                                    </div>
                                                    <div
                                                        class="card border border-top-0 border-right-0 border-left-0 py-2 mb-3">
                                                        <section
                                                            class="d-flex justify-content-between align-items-center flex-column flex-sm-row">
                                                            <article class="card-datos card-datos--title-gananciaBruta ">
                                                                Costo
                                                            </article>
                                                            <article>
                                                                <span class="fs-20 ">$</span>
                                                                <span
                                                                    class="card-datos--number">{{ number_format($costoDolaresContado, 2, '.', ',') }}</span>
                                                            </article>
                                                        </section>
                                                    </div>
                                                    <div class="card border border-0 py-2">
                                                        <section
                                                            class="d-flex justify-content-between align-items-center flex-column flex-sm-row">
                                                            <article class="card-datos card-datos--title-gananciaBruta ">
                                                                Gananacia
                                                            </article>
                                                            <article>
                                                                <span class="fs-20 ">$</span>
                                                                <span
                                                                    class="card-datos--number">{{ number_format($gananciaDolaresContado, 2, '.', ',') }}</span>
                                                            </article>
                                                        </section>
                                                    </div>
                                                </div>
                                                {{-- Fin --}}
                                                {{-- Grafico de ganancias al contado en Dolares --}}
                                                <div class="col-12 col-lg-6">
                                                    <div class="card h-100">
                                                        <div class="d-flex justify-content-center">
                                                            <section id="graficoGananciasContadoDolares"></section>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- Fin --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Fin --}}

                                {{-- Ganacias en Dolares al Credito --}}
                                <div class="col-12 col-lg-12 ganancias-Dolares-Credito mt-4">
                                    <div class="card card-border--10">
                                        <div class="card-header card-header--dolares text-center">
                                            <p class="card-title font-weight-bold">Reporte de Ganancias al Crédito </p>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                {{-- Lista de Datos de ganancias --}}
                                                <div class="col-12 col-lg-6">
                                                    <div
                                                        class="card border border-top-0 border-right-0 border-left-0 py-2 mb-3">
                                                        <section
                                                            class="d-flex justify-content-between align-items-center flex-column flex-sm-row">
                                                            <article class="card-datos card-datos--title-gananciaBruta ">
                                                                Ventas
                                                            </article>
                                                            <article>
                                                                <span class="fs-20 ">$</span>
                                                                <span
                                                                    class="card-datos--number">{{ number_format($precioDolaresCredito, 2, '.', ',') }}</span>
                                                            </article>
                                                        </section>
                                                    </div>
                                                    <div
                                                        class="card border border-top-0 border-right-0 border-left-0 py-2 mb-3">
                                                        <section
                                                            class="d-flex justify-content-between align-items-center flex-column flex-sm-row">
                                                            <article class="card-datos card-datos--title-gananciaBruta ">
                                                                Costo
                                                            </article>
                                                            <article>
                                                                <span class="fs-20 ">$</span>
                                                                <span
                                                                    class="card-datos--number">{{ number_format($costoDolaresCredito, 2, '.', ',') }}</span>
                                                            </article>
                                                        </section>
                                                    </div>
                                                    <div class="card border border-0 py-2">
                                                        <section
                                                            class="d-flex justify-content-between align-items-center flex-column flex-sm-row">
                                                            <article class="card-datos card-datos--title-gananciaBruta ">
                                                                Ganancias
                                                            </article>
                                                            <article>
                                                                <span class="fs-20 ">$</span>
                                                                <span
                                                                    class="card-datos--number">{{ number_format($gananciaDolaresCredito, 2, '.', ',') }}</span>
                                                            </article>
                                                        </section>
                                                    </div>
                                                </div>
                                                {{-- Fin --}}

                                                {{-- Grafico de ganancias al credito en Dolares --}}
                                                <div class="col-12 col-lg-6">
                                                    <div class="card h-100">
                                                        <div class="d-flex justify-content-center">
                                                            <section id="graficoGananciasCreditoDolares"></section>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- Fin --}}
                                            </div>
                                            {{-- Fin --}}
                                        </div>
                                    </div>
                                </div>
                                {{-- Fin --}}
                            </div>
                        </section>
                    @endif
                </div>
                {{-- Fin --}}
                {{-- Ganancia Neta --}}
                <div class="tab-pane fade" id="gananciaNeto" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <h6 class="text-center card-header card-header--soles">Soles</h6>
                        </div>
                        <div class="col-12 col-lg-4">
                            <section class="d-flex justify-content-center align-items-center flex-column h-100">
                                <div class="text-center">
                                    <span class=" card-datos card-datos--title">Ganancia Neta Contado</span>
                                    <p class=" card-datos card-datos--sub-title">(Ganancia Bruta - Gastos)</p>
                                </div>
                                <div>
                                    <span class="card-datos card-datos--sub-title">S/. </span>
                                    <span
                                        class="card-datos--number">{{ number_format($gananciaNetaContado, 2, '.', ',') }}
                                    </span>
                                </div>
                                <div class="card">
                                    <div id="graficoGananciaNeta"></div>
                                </div>
                            </section>
                        </div>
                        <div class="col-12 col-lg-4">
                            <section class=" h-100 d-flex justify-content-center align-items-center">
                                <div class="text-center">
                                    <h6 class="card-datos">Total de Gastos</h6>
                                    <span class="card-datos card-datos--sub-title">S/. </span>
                                    <span
                                        class="card-datos--number">{{ number_format($montoGastoSoles, 2, '.', ',') }}</span>
                                </div>
                            </section>
                        </div>
                        <div class="col-12 col-lg-4">
                            <section class="d-flex justify-content-center align-items-center flex-column h-100">
                                <div class="text-center">
                                    <span class="card-datos card-datos--title">Ganancia Neta Contado y Crédito</span>
                                    <p class="card-datos card-datos--sub-title">(Suma de Ganancia Bruta Contado y Crédito -
                                        Gastos)</p>
                                </div>
                                <div>
                                    <span class="card-datos card-datos--sub-title">S/. </span>
                                    <span
                                        class="card-datos--number">{{ number_format($gananciaNetaContadoMasCredito, 2, '.', ',') }}
                                    </span>
                                </div>
                                <div class="card">
                                    <div id="graficoGananciaNetaContadoMasCredito"></div>
                                </div>
                            </section>
                        </div>
                    </div>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <br><br>
                        <div class="row">
                            <div class="col-12 mb-4">
                                <h6 class="text-center card-header card-header--dolares">Dólares</h6>
                            </div>
                            <div class="col-12 col-lg-4">
                                <section class="d-flex justify-content-center align-items-center flex-column h-100">
                                    <div class="text-center">
                                        <span class=" card-datos card-datos--title">Ganancia Neta Contado</span>
                                        <p class=" card-datos card-datos--sub-title">(Ganancia Bruta - Gastos)</p>
                                    </div>
                                    <div>
                                        <span class="card-datos card-datos--sub-title">$ </span>
                                        <span
                                            class="card-datos--number">{{ number_format($gananciaNetaContadoDolares, 2, '.', ',') }}
                                        </span>
                                    </div>
                                    <div class="card">
                                        <div id="graficoGananciaNetaDolares"></div>
                                    </div>
                                </section>
                            </div>
                            <div class="col-12 col-lg-4">
                                <section class=" h-100 d-flex justify-content-center align-items-center">
                                    <div class="text-center">
                                        <h6 class="card-datos">Total de Gastos</h6>
                                        <span class="card-datos card-datos--sub-title">$ </span>
                                        <span
                                            class="card-datos--number">{{ number_format($montoGastoDolares, 2, '.', ',') }}</span>
                                    </div>
                                </section>
                            </div>
                            <div class="col-12 col-lg-4">
                                <section class="d-flex justify-content-center align-items-center flex-column h-100">
                                    <div class="text-center">
                                        <span class="card-datos card-datos--title">Ganancia Neta Contado y Crédito</span>
                                        <p class="card-datos card-datos--sub-title">(Suma de Ganancia Bruta Contado y
                                            Crédito -
                                            Gastos)</p>
                                    </div>
                                    <div>
                                        <span class="card-datos card-datos--sub-title">$ </span>
                                        <span
                                            class="card-datos--number">{{ number_format($gananciaNetaContadoMasCreditoDolares, 2, '.', ',') }}
                                        </span>
                                    </div>
                                    <div class="card">
                                        <div id="graficoGananciaNetaContadoMasCreditoDolares"></div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    @endif
                </div>
                {{-- Fin --}}
            </div>
        </section>
        {{-- FIN --}}
    </div>
    <!-- /.container -->
    <!-- =================================== -->
    <!-- Different data widgets ============ -->
    <!-- =================================== -->
    <div class="container mt-5">
        <div class="widget-list">
            <div class="row">
                <div class="col-md-12 widget-holder">
                    <div class="widget-bg">
                        <!-- /.widget-heading -->
                        <div class="widget-body clearfix">
                            <!--<p>Listado de ventas</p>-->
                            <table id="table" class="table table-responsive-sm" style="width:100%">
                                <thead>
                                    <tr class="bg-primary">
                                        <th scope="col">Comprobante</th>
                                        <th scope="col">Fecha Emitida</th>
                                        <th scope="col">Tipo de Pago</th>
                                        <th scope="col">Tipo de Moneda</th>
                                        <th scope="col">Total Costo</th>
                                        <th scope="col">Total Venta</th>
                                        <th scope="col">Ganancia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reporteGanancias as $ganancia)
                                        <tr>
                                            <td>Venta</td>
                                            <td>{{ $ganancia->FechaCreacion }}</td>
                                            @if ($ganancia->IdTipoPago == 1)
                                                <td>Contado</td>
                                            @else
                                                <td>Crédito</td>
                                            @endif
                                            @if ($ganancia->tipoMoneda == 1)
                                                <td>Soles</td>
                                            @else
                                                <td>Dólares</td>
                                            @endif
                                            <td>{{ $ganancia->Costo }}</td>
                                            <td>{{ $ganancia->Precio }}</td>
                                            <td>{{ $ganancia->Ganancia }}</td>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" type="text/javascript"></script>
    {{-- SCRIPT DEL NUEVO GRAFICO  REPORTE VENTAS --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    {{-- FIN --}}
    <script type="text/javascript">
        $(function() {
            $(document).ready(function() {
                $('#table').DataTable({
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
        /* Ganancias al contado en soles*/
        var options = {
            series: @json($arrayGananciasSolesContado),
            chart: {
                width: 400,
                type: 'polarArea',
            },
            labels: ['Ventas', 'Costo', 'Ganancia'],
            colors: ['#F1416C', '#7239EA', '#6794DC'],
            legend: {
                position: 'bottom'
            },
            stroke: {
                colors: ['#fff']
            },
            fill: {
                opacity: 0.8
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 400
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };
        var chart = new ApexCharts(document.querySelector("#graficoGananciasContadoSoles"), options);
        chart.render();
    </script>

    {{-- Ganancias al credito en soles --}}
    <script>
        var options = {
            series: @json($arrayGananciasSolesCredito),
            chart: {
                width: 400,
                type: 'polarArea',
            },
            labels: ['Ventas', 'Costo', 'Ganancia'],
            legend: {
                position: 'bottom'
            },
            stroke: {
                colors: ['#fff']
            },
            fill: {
                opacity: 0.8
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 400
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };
        var chart = new ApexCharts(document.querySelector("#graficoGananciasCreditoSoles"), options);
        chart.render();
    </script>

    {{-- Ganancias al contado en Dolares --}}
    <script>
        var options = {
            series: @json($arrayGananciasDolaresContado),
            chart: {
                width: 400,
                type: 'polarArea',
            },
            labels: ['Ventas', 'Costo', 'Ganancia'],
            colors: ['#ee5253', '#1e3799', '#ff9f43'],
            legend: {
                position: 'bottom'
            },
            stroke: {
                colors: ['#fff']
            },
            fill: {
                opacity: 0.8
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 400
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };
        var chart = new ApexCharts(document.querySelector("#graficoGananciasContadoDolares"), options);
        chart.render();
    </script>

    {{-- Ganancias al contado en Dolares --}}
    <script>
        var options = {
            series: @json($arrayGananciasDolaresCredito),
            chart: {
                width: 400,
                type: 'polarArea',
            },
            labels: ['Ventas', 'Costo', 'Ganancia'],
            colors: ['#1B1464', '#12CBC4', '#6794DC'],
            legend: {
                position: 'bottom'
            },
            stroke: {
                colors: ['#fff']
            },
            fill: {
                opacity: 0.8
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 400
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };
        var chart = new ApexCharts(document.querySelector("#graficoGananciasCreditoDolares"), options);
        chart.render();
    </script>

    {{-- Ganacia Neta Soles --}}
    <script>
        var options = {
            series: @json($gananciaTotalNetoMasContado),
            chart: {
                type: 'donut',
                height: 200,
            },
            labels: ['G. Neto Contado', 'Gastos'],
            colors: ['#4786ff', '#fa607e'],
            legend: {
                position: 'bottom'
            },
            dataLabels: {
                enabled: false
            },
            plotOptions: {
                pie: {
                    startAngle: -90,
                    endAngle: 90,
                    offsetY: 10
                }
            },
            grid: {
                padding: {
                    bottom: -80
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#graficoGananciaNeta"), options);
        chart.render();
    </script>
    <script>
        var options = {
            series: @json($gananciaTotalNetoMasContadoYcredito),
            chart: {
                type: 'donut',
                height: 200,
            },
            labels: ['G. Neto Contado', 'Gastos'],
            colors: ['#4786ff', '#fa607e'],
            legend: {
                position: 'bottom'
            },
            dataLabels: {
                enabled: false
            },
            plotOptions: {
                pie: {
                    startAngle: -90,
                    endAngle: 90,
                    offsetY: 10
                }
            },
            grid: {
                padding: {
                    bottom: -80
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#graficoGananciaNetaContadoMasCredito"), options);
        chart.render();
    </script>

    {{-- Ganacia Neta Dolares --}}
    <script>
        var options = {
            series: @json($gananciaTotalNetoMasContadoDolares),
            chart: {
                type: 'donut',
                height: 200,
            },
            labels: ['G. Neto Contado', 'Gastos'],
            colors: ['#4786ff', '#fa607e'],
            legend: {
                position: 'bottom'
            },
            dataLabels: {
                enabled: false
            },
            plotOptions: {
                pie: {
                    startAngle: -90,
                    endAngle: 90,
                    offsetY: 10
                }
            },
            grid: {
                padding: {
                    bottom: -80
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#graficoGananciaNetaDolares"), options);
        chart.render();
    </script>
    <script>
        var options = {
            series: @json($gananciaTotalNetoMasContadoYcreditoDolares),
            chart: {
                type: 'donut',
                height: 200,
            },
            labels: ['G. Neto Contado', 'Gastos'],
            colors: ['#4786ff', '#fa607e'],
            legend: {
                position: 'bottom'
            },
            dataLabels: {
                enabled: false
            },
            plotOptions: {
                pie: {
                    startAngle: -90,
                    endAngle: 90,
                    offsetY: 10
                }
            },
            grid: {
                padding: {
                    bottom: -80
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#graficoGananciaNetaContadoMasCreditoDolares"), options);
        chart.render();
    </script>

@stop
