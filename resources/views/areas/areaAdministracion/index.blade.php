@extends('layouts.app')
@section('title', 'Panel Administrativo')
@section('content')
    <style>
        .box-1 {
            background-image: linear-gradient(135deg, #5eade6 10%, #0061A6 100%);
            border-radius: 5px;
        }

        .box-numero {
            font-size: 28px;
            font-weight: bold;
            color: #ffff;
        }

        .card-datos {
            border: none !important;
            width: 100%;
            height: 90px;
        }

        .box-texto {
            color: #ffff;
            font-size: 14px
        }

        .btn-facturacion {
            background-color: #0095E8;
            color: #ffff;
            cursor: pointer;
        }

        .btn-vehicular {
            background-color: #f8973d;
            color: #ffff;
        }

        .btn-vehicular:hover {
            background-color: #F98518;
        }

        .nav-item {
            background-color: #EFF2F5;
            border-radius: 5px;
        }

        .nav-item .active {
            background-color: #10ac84 !important;
        }

        .nav-link {
            color: #A1A5B7;
        }

        .color-icono {
            color: #80BBDB !important;
        }

        .icono {
            background-color: #0095E8;
        }

        .box-datos {
            border-radius: 10px;
            font-size: 25px;
            font-weight: 800;
            padding: 2px 15px 2px 15px;
        }

        .box-datos--soles {
            background-color: #A9DFFD;
            color: #009EF7;
        }

        .box-datos--dolares {
            background-color: #a8f7ce;
            color: #10ac84;
        }

        .card-bg-color {
            background-color: #EFF2F5;
        }

        .box-titulo {
            color: #009EF7;
            font-weight: bold;
        }

        .box-titulo-dolares {
            color: #10ac84;
            font-weight: bold;
        }

        /* FLUJO CAJA */
        .card-texto {
            font-weight: bold;
        }

        .card-texto--datos {
            border-radius: 10px;
            font-size: 35px;
            font-weight: 800;
            color: #3F4254;
        }

        .card-flujo-caja--fondo {
            color: #714DCA;
            border-radius: 10px;
            font-weight: 800;
            font-size: 35px;
            padding: 2px 15px 2px 15px;
            background-color: #F1E6FE;
        }

        .my-6 {
            margin-top: 60px;
        }

        .mt-6 {
            margin-top: 60px;
        }

        .cajas-abiertas {
            background: #3F4254;
            border-radius: 10px;
            color: #ffff;
            font-size: 25px;
            padding: 3px 10px;
            margin: auto;
        }

        /* PRODUCTOS EN SCTOCK */
        .card-bg-color--sctock {
            background-color: #EFF2F5;
            font-weight: 800;
            padding: 10px;
        }

        .card_datos {
            font-size: 25px;
        }

        .card-bg-color--numeroCajas {
            background-color: #009EF7;
        }

        .fs-18px {
            font-size: 22px;
        }

        .fs-35px {
            font-size: 35px;
        }

        .card-bg-color--azul {
            background-color: #6874AC;
            color: #ffff;
        }

        .card-bg-color--gris {
            background-color: #7BB6E8;
            color: #ffff;
        }

        .card-bg-color--amarillo {
            background-color: #FFB000;
            color: #ffff;
        }

        .badge-primary {
            background-color: #fff !important;
            color: #FFB000;
        }

        .card-bg-color--verde1 {
            /* background-color: #00b894; */
            background-image: linear-gradient(135deg, #9877a1 10%, #6B327C 100%);
            color: #ffff;
            font-weight: 800;
            padding: 10px;
        }

        .card-bg-color--verde2 {
            /* background-color: #00b894; */
            background-image: linear-gradient(135deg, #f0b074 10%, #F6851A 100%);
            color: #ffff;
        }

        .card-bg-color--verde3 {
            /* background-color: #00b894; */
            background-image: linear-gradient(135deg, #e78685 10%, #eb4d4b 100%);
            color: #ffff;
            font-weight: 800;
            padding: 10px;
        }

        .card-bg-color--verde4 {
            /* background-color: #00b894; */
            background-image: linear-gradient(135deg, #75ddc3 10%, #10ac84 100%);
            color: #ffff;
            font-weight: 800;
            padding: 10px;
        }

        .card-bg-color--grisClaro {
            background-color: #F5F8FA;
            font-weight: 800;
            padding: 10px;
        }

        .card-bg-color--morado {
            background-color: #7d5fff;
            color: #ffff;
            font-weight: 800;
            padding: 10px;
        }

        .card-bg-color--celeste {
            background-color: #0095E8;
            color: #ffff;
            font-weight: 800;
            padding: 10px;
        }

        .card {
            border-radius: 10px !important;
        }

        .card--border {
            border: 2px solid #E9ECEF !important;
        }

        /* Estilos Cotizaciones */
        .icon-bg--morado {
            color: #FFC110;
        }

        .icon-bg--verde {
            color: #1BC5BD;
        }

        .icon-bg--rojo {
            color: #F64E60;
        }
    </style>
    <style>
        HTML CSS JSResult Skip Results Iframe EDIT ON .loader-section {
            width: 100vw;
            height: 100vh;
            max-width: 100%;
            position: fixed;
            top: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #ffffff;
            z-index: 999;
            transition: all 1s 1s ease-out;
            opacity: 1;
        }

        .loaded {
            opacity: 0;
            z-index: -1;
        }

        .loader {
            width: 30px;
            height: 30px;
            border: 5px solid #623ddb;
            border-bottom-color: transparent;
            border-radius: 50%;
            display: inline-block;
            box-sizing: border-box;
            animation: rotation 1s linear infinite;
        }

        @keyframes rotation {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <div class="container">
        <section
            class="jumbotron jumbotron-fluid p-4 d-flex justify-content-center flex-wrap align-items-center justify-content-sm-between flex-column flex-md-row my-4 border">
            <h6 class="font-weight-bold text-center">Área Administrativa
            </h6>
            <article>
                <div class="icon d-flex  justify-content-center flex-wrap">
                    <a href=" {{ url('panel') }}">
                        <button type="button" class="btn btn-secondary btn-sm"><i
                                class='bx bx-share fs-20 icono-vehicular mr-1'></i> Ir Panel
                            Principal</button>
                    </a>

                    @if ($permisosBotones->contains('Nombre', 'Area Vehicular'))
                        <a href=" {{ url('area-vehicular') }}">
                            <button type="button" class="btn btn-vehicular btn-sm ml-2"><i
                                    class='bx bxs-car-mechanic fs-20 mr-1'></i> Area Vehicular</button>
                        </a>
                    @endif
                    @if ($permisosBotones->contains('Nombre', 'Area Facturacion'))
                        <a href="{{ url('area-facturacion') }}">
                            <button type="button" class="btn btn-facturacion btn-sm ml-2"><i
                                    class='bx bx-file fs-20 mr-1'></i> Area Facturación</button>
                        </a>
                    @endif
                </div>
            </article>
        </section>
        <section class="row mb-4">
            <div class="col-12">
                {!! Form::open(['url' => 'area-administrativa/reportes', 'method' => 'POST']) !!}
                {{ csrf_field() }}
                <input id="inputValorBoton" type="hidden" name="inputValorBoton">
                <table id="table1" class="table-responsive" width="100%">
                    <tbody class="d-flex justify-content-between">
                        <tr style="width:100%" class="d-flex justify-content-between flexGap--10 mb-3">
                            @if ($permisosSubBotones->contains('Nombre', 'Flujo Ventas'))
                                <td>
                                    <button id="btnFlujoVentas" class="btn btnAdministracion font-weight-bold py-1 px-1"
                                        value="flujoVentas" name="flujoVentas">Flujo de Ventas</button>
                                </td>
                            @endif
                            @if ($permisosSubBotones->contains('Nombre', 'Flujo Caja'))
                                <td>
                                    <button id="btnFlujoCaja" class="btn btnAdministracion font-weight-bold py-1 px-1"
                                        value="flujoCaja" name="flujoCaja">Flujo de Caja</button>
                                </td>
                            @endif
                            @if ($permisosSubBotones->contains('Nombre', 'Almacen/Stock'))
                                <td>
                                    <button id="btnAlmacen" class="btn btnAdministracion font-weight-bold py-1 px-1"
                                        value="almacenStock" name="almacenStock">Almacen/Stock</button>
                                </td>
                            @endif
                            @if ($permisosSubBotones->contains('Nombre', 'Cotizaciones'))
                                <td>
                                    <button id="btnCotizacion" class="btn btnAdministracion font-weight-bold py-1 px-1"
                                        value="cotizacion" name="cotizacion">Cotizaciones</button>
                                </td>
                            @endif
                            @if ($permisosSubBotones->contains('Nombre', 'Ganancias'))
                                <td>
                                    <button id="btnGanancias" class="btn btnAdministracion font-weight-bold py-1 px-1"
                                        value="ganancias" name="ganancias">Ganancias</button>
                                </td>
                            @endif
                            @if ($permisosSubBotones->contains('Nombre', 'Bancos'))
                                {{-- @if ($usuarioSelect->IdOperador == 2) --}}
                                <td>
                                    <button id="btnBancos" class="btn btnAdministracion font-weight-bold py-1 px-1"
                                        value="bancos" name="bancos">Bancos</button>
                                </td>
                                {{-- @endif --}}
                            @endif
                            @if ($permisosSubBotones->contains('Nombre', 'Compras'))
                                <td>
                                    <button id="btnCompras" class="btn btnAdministracion font-weight-bold py-1 px-1"
                                        value="compras" name="compras">Compras</button>
                                </td>
                            @endif
                            @if ($permisosSubBotones->contains('Nombre', 'Creditos/Clientes'))
                                <td>
                                    <button id="btnCreditos" class="btn btnAdministracion font-weight-bold py-1 px-1"
                                        value="creditos" name="creditos">Créditos/Clientes</button>
                                </td>
                            @endif
                            @if ($permisosSubBotones->contains('Nombre', 'Pagos/Proveedores'))
                                <td>
                                    <button id="btnPagos" class="btn btnAdministracion font-weight-bold py-1 px-1"
                                        value="pagos" name="pagos">Pagos/Proveedores</button>
                                </td>
                            @endif
                            @if ($permisosSubBotones->contains('Nombre', 'Gastos'))
                                <td>
                                    <button id="btnGastos" class="btn btnAdministracion font-weight-bold py-1 px-1"
                                        value="gastos" name="gastos">Gastos</button>
                                </td>
                            @endif
                        </tr>
                    </tbody>
                </table>
                {!! Form::close() !!}
            </div>
        </section>

        <section class="row">
            <article class="col-12">
                @switch($inputValorBoton)
                    @case('flujoVentas')
                        {{-- Flujo de ventas --}}
                        <div class="tab-pane fade show active" id="flujoDeVentas" role="tabpanel" aria-labelledby="pills-home-tab">
                            {{-- Datos en soles --}}
                            {{-- Cuadros informativos ventas diarias Soles --}}
                            <div
                                class="d-flex  justify-content-center justify-content-lg-start align-items-center flex-wrap  text-center">
                                <h6 class="font-weight-bold">Gráfico Reporte Diarios en Soles</h6>
                                <div class="ml-0 ml-sm-4">
                                    <a class="" href="https://www.youtube.com/watch?v=PAL8PQeWB9s" target="_blank">
                                        <span class="btn btn-autocontrol-naranja ripple text-white">
                                            Video Instructivo <i class="list-icon material-icons fs-24 color-icon">videocam</i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                            <div class="row mt-4 mb-5">
                                <div class="col-12 col-lg-4">
                                    <div class="card p-3 card-bg-color">
                                        <div class="d-flex d-flex justify-content-between align-items-center">
                                            <section class="texto">
                                                <div class="mt-1">
                                                    <span class="box-datos box-datos--soles">{{ $totalVentasMes }}</span>
                                                </div>
                                                <span class="fs-2 box-titulo">Cant. de Ventas del Mes de
                                                    {{ $mesAnterior }}</span>
                                            </section>
                                            <section class="mr-1">
                                                <span class=""><i class="fa fa-pie-chart fs-40 color-icono"
                                                        aria-hidden="true"></i>
                                                </span>
                                            </section>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="card p-3 card-bg-color">
                                        <div class="d-flex d-flex justify-content-between align-items-center">
                                            <section class="texto">
                                                <div class="mt-1">
                                                    <span
                                                        class="box-datos box-datos--soles">{{ $promedioVentasMesAnterior }}</span>
                                                </div>
                                                <span class="fs-3 box-titulo">Promedio de Venta X DÍa en
                                                    {{ $mesAnterior }}</span>
                                            </section>
                                            <section class="mr-1">
                                                <span class=""><i class="fa fa-pie-chart fs-40 color-icono"
                                                        aria-hidden="true"></i>
                                                </span>
                                            </section>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="card p-3 card-bg-color">
                                        <div class="d-flex d-flex justify-content-between align-items-center">
                                            <section class="texto">
                                                <div class="mt-1">
                                                    <span class="box-datos box-datos--soles">{{ $sumaTotalVentasXdia }}</span>
                                                </div>
                                                <span class="fs-2 box-titulo">Total Ventas de Hoy</span>
                                            </section>
                                            <section class="mr-1">
                                                <span class=""><i class="fa fa-pie-chart fs-40 color-icono"
                                                        aria-hidden="true"></i>
                                                </span>
                                            </section>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Fin --}}

                            {{-- Graficos ventas X dia Soles --}}
                            <div class="row">
                                <div class="col-12 col-lg-9">
                                    <div id="graficoFlujoVenta"></div>
                                </div>
                                <div class="col-12 col-lg-3">
                                    <div class="row d-flex justify-content-center align-items-start">
                                        <div id="graficoFlujoCajaCircular"></div>
                                    </div>
                                    {{-- Mensaje Meta completada --}}
                                    <div class="row">
                                        @if ($totalVentas >= 100)
                                            <div class="alert alert-warning alert-dismissible fade  w-100 show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                                <strong>Felicitaciones:</strong> Llego a la meta en cantidad
                                                de ventas diarias.
                                            </div>
                                        @elseif($totalVentas == 0)
                                            <div class="alert alert-warning alert-dismissible fade  w-100 show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                                <strong>Buenos Dias:</strong> Complete la meta de cantidad
                                                de ventas
                                                diarias.
                                            </div>
                                        @else
                                            <div class="alert alert-warning alert-dismissible fade  w-100 show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                                <strong>Llegando a la meta:</strong> Aún le falta por
                                                completar la cantidad de ventas del Día.
                                            </div>
                                        @endif
                                    </div>
                                    {{-- Fin --}}
                                    @if ($usuarioSelect->IdOperador == 2)
                                        <div class="row">
                                            <div class="card card-datos box-1">
                                                <div class="card-body">
                                                    <h3 class="card-title box-numero">
                                                        S/.
                                                        {{ number_format($totalDineroVentasXdia, 2, '.', ',') }}
                                                    </h3>
                                                    <h6 class="card-subtitle mb-2 box-texto">Total Ventas
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            {{-- Fin --}}

                            {{-- Cuadros informativos de ventas mensual Soles --}}
                            <h6 class="font-weight-bold">Gráfico Reporte Mensual en Soles</h6>
                            <div class="row mt-4 mb-5">
                                <div class="col-12 col-lg-4">
                                    <div class="card p-3 card-bg-color">
                                        <div class="d-flex d-flex justify-content-between align-items-center">
                                            <section class="texto">
                                                <div class="mt-1">
                                                    <span
                                                        class="box-datos box-datos--soles">{{ $totalVentasTresMesesAtras }}</span>
                                                </div>
                                                <span class="fs-2 box-titulo pt-4">Cant. de Ventas de 3
                                                    meses
                                                    Atrás</span>
                                            </section>
                                            <section class="mr-1">
                                                <span class=""><i class="fa fa-pie-chart fs-40 color-icono"
                                                        aria-hidden="true"></i>
                                                </span>
                                            </section>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="card p-3 card-bg-color">
                                        <div class="d-flex d-flex justify-content-between align-items-center">
                                            <section class="texto">
                                                <div class="mt-1">
                                                    <span class="box-datos box-datos--soles">{{ $promedioVentasTresMeses }}</span>
                                                </div>
                                                <span class="fs-3 box-titulo">Promedio de Venta X
                                                    Mes</span>
                                            </section>
                                            <section class="mr-1">
                                                <span class=""><i class="fa fa-pie-chart fs-40 color-icono"
                                                        aria-hidden="true"></i>
                                                </span>
                                            </section>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="card p-3 card-bg-color">
                                        <div class="d-flex d-flex justify-content-between align-items-center">
                                            <section class="texto">
                                                <div class="mt-1">
                                                    <span class="box-datos box-datos--soles">{{ $totalVentasDelMesActual }}</span>
                                                </div>
                                                <span class="fs-2 box-titulo">Total Ventas del Mes
                                                    Actual</span>
                                            </section>
                                            <section class="mr-1">
                                                <span class=""><i class="fa fa-pie-chart fs-40 color-icono"
                                                        aria-hidden="true"></i>
                                                </span>
                                            </section>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Fin --}}

                            {{-- Gafico Ventas Mensual Soles --}}
                            <div class="row">
                                <div class="col-12 col-lg-9">
                                    <div id="graficoVentasMensual"></div>
                                </div>
                                <div class="col col-lg-3">
                                    <div class="row d-flex justify-content-center ">
                                        <div class="" id="graficoCircularVentasMensual">
                                        </div>
                                    </div>
                                    <div class="row">
                                        @if ($pocentajeMeta >= 100)
                                            <div class="alert alert-warning alert-dismissible  w-100 fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                                <strong>Felicitaciones:</strong> Llego a la meta en cantidad
                                                de ventas Mensual.
                                            </div>
                                        @elseif($pocentajeMeta == 0)
                                            <div class="alert alert-warning alert-dismissible fade  w-100 show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                                <strong>Buenos Dias:</strong> Complete la meta de cantidad
                                                de ventas
                                                diarias.
                                            </div>
                                        @else
                                            <div class="alert alert-warning alert-dismissible fade  w-100 show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                                <strong>Llegando a la meta:</strong> Aún le falta por
                                                completar la cantidad de ventas del Mes.
                                            </div>
                                        @endif
                                    </div>
                                    @if ($usuarioSelect->IdOperador == 2)
                                        <div class="row">
                                            <div class="card w-100 box-1 card-datos">
                                                <div class="card-body">
                                                    <h3 class="card-title box-numero">
                                                        S/.
                                                        {{ number_format($totalDineroVentasMesActual, 2, '.', ',') }}
                                                    </h3>
                                                    <h6 class="card-subtitle mb-2 box-texto">Total Ventas
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            {{-- Fin --}}
                            {{-- Fin --}}

                            {{-- Datos en Dolares --}}
                            {{-- Cuadros informativos ventas diarias  Dolares --}}
                            @if ($subniveles->contains('IdSubNivel', 46))
                                <h6 class="font-weight-bold">Gráfico Reporte diario en Dólares</h6>
                                <div class="row mt-4 mb-5">
                                    <div class="col-12 col-lg-4">
                                        <div class="card p-3 card-bg-color">
                                            <div class="d-flex d-flex justify-content-between align-items-center">
                                                <section class="texto">
                                                    <div class="mt-1">
                                                        <span
                                                            class="box-datos box-datos--dolares">{{ $totalVentasMesDolares }}</span>
                                                    </div>
                                                    <span class="fs-2 box-titulo-dolares">Cant. de Ventas del
                                                        Mes de
                                                        {{ $mesAnterior }}</span>
                                                </section>
                                                <section class="mr-1">
                                                    <span class=""><i class="fa fa-pie-chart fs-40 color-icono"
                                                            aria-hidden="true"></i>
                                                    </span>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <div class="card p-3 card-bg-color">
                                            <div class="d-flex d-flex justify-content-between align-items-center">
                                                <section class="texto">
                                                    <div class="mt-1">
                                                        <span
                                                            class="box-datos box-datos--dolares">{{ $promedioVentasMesAnteriorDolares }}</span>
                                                    </div>
                                                    <span class="fs-3 box-titulo-dolares">Promedio de Venta X
                                                        DÍa en
                                                        {{ $mesAnterior }}</span>
                                                </section>
                                                <section class="mr-1">
                                                    <span class=""><i class="fa fa-pie-chart fs-40 color-icono"
                                                            aria-hidden="true"></i>
                                                    </span>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <div class="card p-3 card-bg-color">
                                            <div class="d-flex d-flex justify-content-between align-items-center">
                                                <section class="texto">
                                                    <div class="mt-1">
                                                        <span
                                                            class="box-datos box-datos--dolares">{{ $sumaTotalVentasXdiaDolares }}</span>
                                                    </div>
                                                    <span class="fs-2 box-titulo-dolares">Total Ventas de Hoy
                                                        en
                                                        Dólares</span>
                                                </section>
                                                <section class="mr-1">
                                                    <span class=""><i class="fa fa-pie-chart fs-40 color-icono"
                                                            aria-hidden="true"></i>
                                                    </span>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Fin --}}

                                {{-- Graficos ventas X dia Dolares --}}
                                <div class="row">
                                    <div class="col-12 col-lg-9">
                                        <div id="graficoVentasXdiaDolares"></div>
                                    </div>
                                    <div class="col-12 col-lg-3">
                                        <div class="row d-flex justify-content-center align-items-start">
                                            <div id="graficoCircularXdiaDolares"></div>
                                        </div>
                                        {{-- Mensaje Meta completada --}}
                                        <div class="row">
                                            @if ($totalVentasDolares >= 100)
                                                <div class="alert alert-warning alert-dismissible  w-100 fade show"
                                                    role="alert">
                                                    <button type="button" class="close" data-dismiss="alert"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                    <strong>Felicitaciones:</strong> Llego a la meta en cantidad
                                                    de ventas diarias.
                                                </div>
                                            @elseif($totalVentasDolares == 0)
                                                <div class="alert alert-warning alert-dismissible  w-100 fade show"
                                                    role="alert">
                                                    <button type="button" class="close" data-dismiss="alert"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                    <strong>Buenos Dias:</strong> Complete la meta de cantidad
                                                    de ventas
                                                    diarias.
                                                </div>
                                            @else
                                                <div class="alert alert-warning alert-dismissible  w-100 fade show"
                                                    role="alert">
                                                    <button type="button" class="close" data-dismiss="alert"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                    <strong>Llegando a la meta:</strong> Aún le falta por
                                                    completar la cantidad de ventas del Día.
                                                </div>
                                            @endif
                                        </div>
                                        {{-- Fin --}}
                                        @if ($usuarioSelect->IdOperador == 2)
                                            <div class="row">
                                                <div class="card card-datos box-1">
                                                    <div class="card-body">
                                                        <h3 class="card-title box-numero">
                                                            $
                                                            {{ number_format($totalDineroVentasXdiaDolares, 2, '.', ',') }}
                                                        </h3>
                                                        <h6 class="card-subtitle mb-2 box-texto">Total Ventas
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                {{-- Fin --}}

                                {{-- Cuadros informativos de ventas mensual Dolares --}}
                                <h6 class="font-weight-bold">Gráfico Reporte Mensual en Dólares</h6>
                                <div class="row mt-4  mb-5">
                                    <div class="col-12 col-lg-4">
                                        <div class="card p-3 card-bg-color">
                                            <div class="d-flex d-flex justify-content-between align-items-center">
                                                <section class="texto">
                                                    <div class="mt-1">
                                                        <span
                                                            class="box-datos box-datos--dolares">{{ $totalVentasTresMesesAtrasDolares }}</span>
                                                    </div>
                                                    <span class="fs-2 box-titulo-dolares pt-4">Cant. de Ventas
                                                        de 3
                                                        meses Atrás</span>
                                                </section>
                                                <section class="mr-1">
                                                    <span class=""><i class="fa fa-pie-chart fs-40 color-icono"
                                                            aria-hidden="true"></i>
                                                    </span>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <div class="card p-3 card-bg-color">
                                            <div class="d-flex d-flex justify-content-between align-items-center">
                                                <section class="texto">
                                                    <div class="mt-1">
                                                        <span
                                                            class="box-datos box-datos--dolares">{{ $promedioVentasTresMesesDolares }}</span>
                                                    </div>
                                                    <span class="fs-3 box-titulo-dolares">Promedio de Venta X
                                                        Mes</span>
                                                </section>
                                                <section class="mr-1">
                                                    <span class=""><i class="fa fa-pie-chart fs-40 color-icono"
                                                            aria-hidden="true"></i>
                                                    </span>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <div class="card p-3 card-bg-color">
                                            <div class="d-flex d-flex justify-content-between align-items-center">
                                                <section class="texto">
                                                    <div class="mt-1">
                                                        <span
                                                            class="box-datos box-datos--dolares">{{ $totalVentasDelMesActualDolares }}</span>
                                                    </div>
                                                    <span class="fs-2 box-titulo-dolares">Total Ventas del Mes
                                                        Actual</span>
                                                </section>
                                                <section class="mr-1">
                                                    <span class=""><i class="fa fa-pie-chart fs-40 color-icono"
                                                            aria-hidden="true"></i>
                                                    </span>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-12">
                                            <div id="graficoVentasMensual"></div>
                                            </div> --}}
                                </div>
                                {{-- Fin --}}

                                {{-- Gafico Ventas Mensual Dolares --}}
                                <div class="row">
                                    <div class="col col-lg-9">
                                        <div id="graficoVentasMensualDolares"></div>
                                    </div>
                                    <div class="col col-lg-3">
                                        <div class="row d-flex justify-content-center ">
                                            <div class="" id="graficoCircularVentasMensualDolates">
                                            </div>
                                        </div>
                                        <div class="row">
                                            @if ($pocentajeMetaDolares >= 100)
                                                <div class="alert alert-warning alert-dismissible  w-100 fade show"
                                                    role="alert">
                                                    <button type="button" class="close" data-dismiss="alert"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                    <strong>Felicitaciones:</strong> Llego a la meta en cantidad
                                                    de ventas Mensual.
                                                </div>
                                            @elseif($pocentajeMetaDolares == 0)
                                                <div class="alert alert-warning alert-dismissible  w-100 fade show"
                                                    role="alert">
                                                    <button type="button" class="close" data-dismiss="alert"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                    <strong>Buenos Dias:</strong> Complete la meta de cantidad
                                                    de ventas
                                                    Mensual.
                                                </div>
                                            @else
                                                <div class="alert alert-warning alert-dismissible  w-100 fade show"
                                                    role="alert">
                                                    <button type="button" class="close" data-dismiss="alert"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                    <strong>Llegando a la meta:</strong>
                                                    Aún le falta por completar la cantidad de ventas del Mes.
                                                </div>
                                            @endif
                                        </div>
                                        @if ($usuarioSelect->IdOperador == 2)
                                            <div class="row">
                                                <div class="card w-100 box-1 card-datos">
                                                    <div class="card-body">
                                                        <h3 class="card-title box-numero">
                                                            $
                                                            {{ number_format($totalDineroVentasMesActualDolares, 2, '.', ',') }}
                                                        </h3>
                                                        <h6 class="card-subtitle mb-2 box-texto">Total Ventas
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            {{-- Fin --}}
                            {{-- Fin --}}

                        </div>
                        {{-- Fin --}}
                    @break

                    @case('flujoCaja')
                        {{-- Flujo de Caja --}}
                        <div class="d-none" id="flujoDeCaja" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <section
                                class="d-flex  justify-content-center justify-content-lg-start align-items-center flex-wrap  text-center">
                                <h6>Reporte del mes actual</h6>
                                <div class="ml-0 ml-sm-4">
                                    <a class="" href="https://www.youtube.com/watch?v=18FBshWyhoI" target="_blank">
                                        <span class="btn btn-autocontrol-naranja ripple text-white">
                                            Video Instructivo <i class="list-icon material-icons fs-24 color-icon">videocam</i>
                                        </span>
                                    </a>
                                </div>
                            </section>
                            <hr>
                            <section class="Soles">
                                <div class="col-12  text-center">
                                    <section class="w-50 w-md-25 px-4 m-auto card-bg-color--amarillo rounded pt-3 pb-1">
                                        <span class="badge badge-primary fs-30">
                                            {{ $totalcajasAbiertas }} </span>
                                        <p class="font-weight-bold card-titulo ">(Cajas Abiertas)</p>
                                    </section>
                                    <h6 class="font-weight-bold  mt-4">Reporte de Cajas en Soles</h6>
                                    <p class="font-weight-bold text-center">(Todas las Cajas Abiertas)</p>
                                </div>

                                {{-- Cuadros de Datos Soles --}}
                                <div class="row my-4">
                                    <div class="col-12 col-md-6">
                                        <div class="card  px-4 pt-4 pb-2 card_flujocaja card-bg-color--azul">
                                            <section class="d-flex d-flex justify-content-between align-items-center">
                                                <div class="">
                                                    <span class="fs-18px">S/.</span>
                                                    <span class="fs-35px">
                                                        {{ number_format($totalCajaSoles, 2, '.', ',') }}</span>
                                                </div>
                                                <span><i class='bx bx-trending-up fs-40 mr-0 mr-md-4'></i></span>
                                            </section>
                                            <section>
                                                <span class="card-texto">Caja Total de Efectivo (Incluye
                                                    Ingresos, Egresos y Efectivo Inicial)</span>
                                            </section>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card px-4 pt-4 pb-2 card_flujocaja  card-bg-color--azul">
                                            <section class="d-flex d-flex justify-content-between align-items-center">
                                                <div class="">
                                                    <span class="fs-18px">S/.</span>
                                                    <span class="fs-35px">
                                                        {{ number_format($totaEfectivoContadoSoles, 2, '.', ',') }}</span>
                                                </div>
                                                <span><i class='bx bx-trending-up fs-40 mr-0 mr-md-4'></i></span>
                                            </section>
                                            <section>
                                                <span class="card-texto">Total de efectivos
                                                    Contado</span>
                                            </section>
                                        </div>
                                    </div>
                                </div>
                                <div class="row my-4">
                                    <div class="col-12 col-md-6">
                                        <div class="card px-4 pt-4 pb-2 card_flujocaja card-bg-color--azul">
                                            <section class="d-flex d-flex justify-content-between align-items-center">
                                                <div class="">
                                                    <span class="fs-18px">S/.</span>
                                                    <span class="fs-35px">
                                                        {{ number_format($totaEfectivoCobranzasSoles, 2, '.', ',') }}</span>
                                                </div>
                                                <span><i class='bx bx-trending-up fs-40 mr-0 mr-md-4'></i></span>
                                            </section>
                                            <section>
                                                <span class="card-texto">Total de efectivos de
                                                    Cobranzas</span>
                                            </section>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card px-4 pt-4 pb-2 card_flujocaja card-bg-color--azul">
                                            <section class="d-flex d-flex justify-content-between align-items-center">
                                                <div class="">
                                                    <span class="fs-18px">S/.</span>
                                                    <span class="fs-35px">
                                                        {{ number_format($totaEfectivoAmortizacionSoles, 2, '.', ',') }}</span>
                                                </div>
                                                <span><i class='bx bx-trending-up fs-40 mr-0 mr-md-4'></i></span>
                                            </section>
                                            <section>
                                                <span class="card-texto">T. de efectivo de
                                                    Amortizaciones</span>
                                            </section>
                                            {{-- <hr>
                                                        <span class="text-center card-texto">Todas las Cajas</span> --}}
                                        </div>
                                    </div>
                                </div>
                                {{-- Fin --}}
                                <h6 class="font-weight-bold text-center mt-6">Reporte de Cierre de caja
                                    Diario
                                </h6>
                                {{-- Grafico Cierre de caja --}}
                                <p class="font-weight-bold text-center">(Todos los
                                    Usuarios)</p>
                                <div class="row">
                                    <div class="col">
                                        <div id="graficoCierreCaja"></div>
                                    </div>
                                </div>
                                {{-- Fin --}}
                            </section>
                            @if ($subniveles->contains('IdSubNivel', 46))
                                <section class="Dolares">
                                    <h6 class="font-weight-bold text-center mt-6">Reporte de caja en
                                        Dólares
                                    </h6>
                                    <p class="font-weight-bold text-center">(Todas las Cajas Abiertas)</p>
                                    {{-- Cuadros Datos Dolares --}}
                                    <div class="row my-4">
                                        <div class="col-12 col-md-6">
                                            <div class="card  px-4 pt-4 pb-2 card_flujocaja card-bg-color--gris">
                                                <section class="d-flex d-flex justify-content-between align-items-center">
                                                    <div class="">
                                                        <span class="fs-18px">$</span>
                                                        <span class="fs-35px">
                                                            {{ number_format($totalCajaDolares, 2, '.', ',') }}</span>
                                                    </div>
                                                    <span><i class='bx bx-trending-up fs-40 mr-0 mr-md-4'></i></span>
                                                </section>
                                                <section>
                                                    <span class="card-texto">Suma Total de efectivo de
                                                        caja</span>
                                                </section>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="card  px-4 pt-4 pb-2 card_flujocaja card-bg-color--gris">
                                                <section class="d-flex d-flex justify-content-between align-items-center">
                                                    <div class="">
                                                        <span class="fs-18px">$</span>
                                                        <span class="fs-35px">
                                                            {{ number_format($totaEfectivoContadoDolares, 2, '.', ',') }}</span>
                                                    </div>
                                                    <span><i class='bx bx-trending-up fs-40 mr-0 mr-md-4'></i></span>
                                                </section>
                                                <section>
                                                    <span class="card-texto"> Total de efectivos
                                                        Contado</span>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row my-4">
                                        <div class="col-12 col-md-6">
                                            <div class="card  px-4 pt-4 pb-2 card_flujocaja card-bg-color--gris">
                                                <section class="d-flex justify-content-between">
                                                    <div class="">
                                                        <span class="fs-18px">$</span>
                                                        <span class="fs-35px">
                                                            {{ number_format($totaEfectivoCobranzasDolares, 2, '.', ',') }}</span>
                                                    </div>
                                                    <span><i class='bx bx-trending-up fs-40 mr-0 mr-md-4'></i></span>
                                                </section>
                                                <section>
                                                    <span class="card-texto">Total de efectivos de
                                                        Cobranzas</span>
                                                </section>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="card  px-4 pt-4 pb-2 card_flujocaja card-bg-color--gris">
                                                <section class="d-flex d-flex justify-content-between align-items-center">
                                                    <div class="">
                                                        <span class="fs-18px">$</span>
                                                        <span class="fs-35px">
                                                            {{ number_format($totaEfectivoAmortizacionDolares, 2, '.', ',') }}</span>
                                                    </div>
                                                    <span><i class='bx bx-trending-up fs-40 mr-0 mr-md-4'></i></span>
                                                </section>
                                                <section>
                                                    <span class="card-texto">T. de efectivo de
                                                        Amortizaciones</span>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Fin --}}
                                    {{-- Grafico de cierre de caja Dolares --}}
                                    <h6 class="font-weight-bold text-center mt-6">Reporte de Cierre de caja
                                        Diario
                                    </h6>
                                    <p class="font-weight-bold text-center">(Todos los
                                        Usuarios)</p>
                                    <div class="col-12 col-lg-12 mb-4">
                                        <div id="graficoCierreCajaDolares"></div>
                                    </div>
                                    {{-- Fin --}}
                                </section>
                            @endif
                            {{-- Grafico de Egresos --}}
                            <h6 class="font-weight-bold text-center mt-6">Reporte Egresos Diarios</h6>
                            <p class="font-weight-bold text-center">(Todos los
                                Usuarios)</p>
                            <div class="col-12 col-lg-12 mb-4">
                                <div id="graficoFlujoCaja"></div>
                            </div>
                            {{-- Fin --}}
                        </div>
                        {{-- Fin --}}
                    @break

                    @case('almacenStock')
                        {{-- Almacen Stock --}}
                        <div class="d-none" id="almacenStock" role="tabpanel" aria-labelledby="pills-contact-tab">
                            {{-- Cuadros informativos ventas diarias Soles --}}
                            <div class="row mt-3">
                                <div class="col-12 col-lg-4">
                                    <div class="card card-bg-color--morado text-center">
                                        <span class="card_datos">{{ $totalProductosStock }}</span>
                                        <span>Productos Con Stock (0)</span>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="card card-bg-color--morado text-center">
                                        <span class="card_datos">{{ $totalBajasProductos }}</span>
                                        <span>Bajas Totales</span>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="card card-bg-color--morado text-center">
                                        <span class="card_datos">{{ $totalTraspasosProductos }}</span>
                                        <span>Traspasos Totales</span>
                                    </div>
                                </div>
                            </div>
                            {{-- Fin --}}

                            {{-- Grafico Stock --}}
                            <div class="row align-items-start mt-6 card-bg-color--grisClaro">
                                <div class="col-12">
                                    <h6 class="font-weight-bold">Reporte de bajas del Mes</h6>
                                </div>
                                <div class="col-12 col-lg-9">
                                    <div id="graficoAlmacenStock"></div>
                                </div>
                                <div class="col-12 col-lg-3 ">
                                    <div class="d-flex flex-wrap">
                                        <div class="w-100  rounded">
                                            <div class="card card-bg-color--verde1 text-center p-2">
                                                <span class="card_datos">{{ $totalProductosCreados }}</span>
                                                <span>Total de productos Creados</span>
                                            </div>
                                        </div>
                                        <div class="w-100  rounded my-0 my-lg-3">
                                            <div class="card card-bg-color--verde2  text-center p-2">
                                                <span class="card_datos">{{ $totalProductosCreadosSoles }}</span>
                                                <span>Total de productos Creados en Soles</span>
                                            </div>
                                        </div>
                                        <div class="w-100  rounded">
                                            <div class="card card-bg-color--verde3 text-center p-2">
                                                <span class="card_datos">{{ $totalProductosCreadosDolares }}</span>
                                                <span>Total de productos Creados en Dólares</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Grafico menos y mas vendidos --}}
                            <div class="graficoMasYmenosVendidos">
                                <div class="row card-bg-color mt-6">
                                    <div class="col-12 ">
                                        <h6>Productos Menos vendidos del Mes</h6>
                                        <div id="graficoAlmacenMenosVendido"></div>
                                    </div>
                                </div>

                                <div class="row card-bg-color mt-6">
                                    <div class="col-12 ">
                                        <h6>Productos Más vendidos del Mes</h6>
                                        <<div id="graficoAlmacenMasVendido">
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Fin --}}
                    @break

                    @case('cotizacion')
                        {{-- Cotizaciones --}}
                        <div class="d-none" id="cotizaciones" role="tabpanel" aria-labelledby="pills-contact-tab">
                            {{-- Cuadros de datos cotiizaciones --}}
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="mb-4 d-flex  justify-content-center justify-content-lg-start align-items-center">
                                        <a class="" href="https://www.youtube.com/watch?v=ffITboW8844" target="_blank">
                                            <span class="btn btn-autocontrol-naranja ripple text-white">
                                                Video Instructivo <i class="list-icon material-icons fs-24 color-icon">videocam</i>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="card border border-top-0 border-right-0 border-left-0 p-2">
                                        <section class=" d-flex justify-content-between align-items-center">
                                            <article>
                                                <span class="card-texto--datos">{{ $cotizacionAbierto }}</span>
                                                <p class="card-texto">Total de Cotizaciones Abierta</p>
                                            </article>
                                            <article>
                                                <span class="fs-30">
                                                    <i class='bx bxs-calendar-event icon-bg--morado'></i>
                                                </span>
                                            </article>
                                        </section>
                                    </div>
                                    <div class="card border border-top-0 border-right-0 border-left-0 p-2">
                                        <section class=" d-flex justify-content-between align-items-center">
                                            <article>
                                                <span class="card-texto--datos">{{ $cotizacionCerrado }}</span>
                                                <p class="card-texto">Total de Cotizaciones Cerradas</p>
                                            </article>
                                            <article>
                                                <span class="fs-30">
                                                    <i class='bx bxs-calendar-check icon-bg--verde'></i>
                                                </span>
                                            </article>
                                        </section>
                                    </div>
                                    <div class="card border border-top-0 border-right-0 border-left-0 p-2">
                                        <section class=" d-flex justify-content-between align-items-center">
                                            <article>
                                                <span class="card-texto--datos">{{ $cotizacionBaja }}</span>
                                                <p class="card-texto">Total de Cotizaciones de Baja</p>
                                            </article>
                                            <article>
                                                <span class="fs-30">
                                                    <i class='bx bxs-calendar-x icon-bg--rojo'></i>
                                                </span>
                                            </article>
                                        </section>
                                    </div>
                                </div>
                                {{-- Comparacion de Cotizaciones Cerradas --}}

                                <div class="col-12 col-lg-8">
                                    <div class="card text-center card--border">
                                        {{-- <h6>Total cotizaciones cerradas del mes de {{ $mesAnterior }}</h6> --}}
                                        <h6>Total cotizaciones cerradas del mes Anterior</h6>
                                        <span class="card-texto--datos ">{{ $cotizacionCerradoDelAnterior }}</span>
                                        <i class='bx bxs-chevron-up fs-20'></i>
                                        <span>(Meta del Mes actual)</span>
                                        <div id="graficoCotizaciones"></div>
                                        @if ($pocentajeMetaCotizacionEsteMes >= 100)
                                            <div class="alert alert-warning alert-dismissible fade show mx-2" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <strong>Felicitaciones!</strong> Cumplio la meta de cantidad de
                                                cotizaciones Cerradas.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Fin --}}
                    @break

                    @case('ganancias')
                        {{-- Ganancias --}}
                        <div class="d-none" id="ganancias" role="tabpanel" aria-labelledby="pills-contact-tab">
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <h6 class="font-weight-bold text-center">Reporte de Ganancias Netas en
                                        Soles <br> (Brutas -Gasto)
                                    </h6>
                                    <div id="graficoGanancias"></div>
                                </div>
                                @if ($usuarioSelect->IdOperador == 2 && $subniveles->contains('IdSubNivel', 46))
                                    <div class="col-12">
                                        <h6 class="font-weight-bold text-center">Reporte de Ganacias en
                                            Dolares</h6>
                                        <div id="graficoGananciasDolares"></div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        {{-- Fin --}}
                    @break

                    @case('bancos')
                        <div>
                            <a class="" href="https://www.youtube.com/watch?v=q3h6ESXFxpQ" target="_blank">
                                <span class="btn btn-autocontrol-naranja ripple text-white">
                                    Video Instructivo <i class="list-icon material-icons fs-24 color-icon">videocam</i>
                                </span>
                            </a>
                        </div>
                        <br>
                        <div class="form-group">
                            <select class="custom-select" id="selectBanco" name="selectBanco">
                                @foreach ($listaBancos as $banco)
                                    <option value="{{ $banco->IdBanco }}">
                                        {{ $banco->Banco }} - {{ $banco->NumeroCuenta }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="loader-section text-center mb-4" id="loader">
                            <span class="loader"></span>
                        </div>
                        <section id="seccionMensaje" class="text-center">
                            <br><br>
                            <h6 class="text-uppercase text-danger font-weight-bold">No se encontrarón datos</h6>
                        </section>
                        <section id="datosReporteBanco" class="row justify-content-md-center">
                            <div class="col-12 col-lg-4">
                                <div class="card p-3 card-bg-color">
                                    <div class="d-flex d-flex justify-content-between align-items-center">
                                        <section class="texto">
                                            <div class="mt-1">
                                                <span id="totalIngresoBanco" class="box-datos box-datos--soles"></span>
                                            </div>
                                            <span class="fs-3 box-titulo">Total Ingreso al Banco del Mes
                                                Actual</span>
                                        </section>
                                        <section class="mr-1">
                                            <span class=""><i class="fa fa-pie-chart fs-40 color-icono"
                                                    aria-hidden="true"></i>
                                            </span>
                                        </section>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="card p-3 card-bg-color">
                                    <div class="d-flex d-flex justify-content-between align-items-center">
                                        <section class="texto">
                                            <div class="mt-1">
                                                <span id="totalSalidaBanco" class="box-datos box-datos--soles"></span>
                                            </div>
                                            <span class="fs-3 box-titulo">Total Salida al Banco del Mes
                                                Actual</span>
                                        </section>
                                        <section class="mr-1">
                                            <span class=""><i class="fa fa-pie-chart fs-40 color-icono"
                                                    aria-hidden="true"></i>
                                            </span>
                                        </section>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="card p-3 card-bg-color">
                                    <div class="d-flex d-flex justify-content-between align-items-center">
                                        <section class="texto">
                                            <div class="mt-1">
                                                <span id="saldoMesActual" class="box-datos box-datos--soles"></span>
                                            </div>
                                            <span class="fs-3 box-titulo">Saldo del Mes Actual</span>
                                        </section>
                                        <section class="mr-1">
                                            <span class=""><i class="fa fa-pie-chart fs-40 color-icono"
                                                    aria-hidden="true"></i>
                                            </span>
                                        </section>
                                    </div>
                                </div>
                            </div>
                            <article id="seccionGrafico" class="jumbotron bg-jumbotron--white col-12">
                                <h6 class="font-weight-bold text-center text-uppercase">Reporte de Saldo de la
                                    cuenta al último
                                    día del Mes
                                </h6>
                                <br><br><br><br>
                                <div id="graficoBancos"></div>
                            </article>
                        </section>
                    @break

                @endswitch
        </section>
    </div>
@stop

@section('scripts')


    <script>
        $(".btnAdministracion").click(function() {
            var $a = $(this).val();
            $("#inputValorBoton").val($a)

        })

        $(function() {
            $('#table1').DataTable({
                "scrollX": true,
                "searching": false,
                "bPaginate": false,
                "ordering": false,
                "bInfo": false,
            });
        });
    </script>

    @if ($inputValorBoton == 'flujoVentas')
        <script>
            $('#flujoDeVentas').removeClass('d-none');
            $('#btnFlujoVentas').addClass('btn--verde');
            // FLUJO DE VENNTAS
            // Grafico de ventas diarias
            var totalVentasXdia = @json($totalVentasXdia);
            var horas = @json($horas);
            var options = {
                series: [{
                    name: 'Total Ventas X Hora',
                    data: totalVentasXdia,
                }],
                chart: {
                    height: 400,
                    type: 'area'
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {
                    // type: 'datetime',
                    categories: horas,
                },
                tooltip: {
                    x: {
                        format: 'dd/MM/yy HH:mm'
                    },
                },
            };

            var chart = new ApexCharts(document.querySelector("#graficoFlujoVenta"), options);
            chart.render()
            // fin

            // Grafico circular Ventas diarias
            var totalVentasXdia = @json($totalVentas);
            var options = {
                series: [totalVentasXdia],
                chart: {
                    type: 'radialBar',
                    width: 360,
                    height: 380,
                    offsetY: -20,
                    sparkline: {
                        enabled: true
                    }
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -90,
                        endAngle: 90,
                        track: {
                            background: "#e7e7e7",
                            strokeWidth: '97%',
                            margin: 5, // margin is in pixels
                            dropShadow: {
                                enabled: true,
                                top: 2,
                                left: 0,
                                color: '#999',
                                opacity: 1,
                                blur: 2
                            }
                        },
                        dataLabels: {
                            name: {
                                show: false
                            },
                            value: {
                                offsetY: -2,
                                fontSize: '22px'
                            }
                        }
                    }
                },
                grid: {
                    padding: {
                        top: -10
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        shadeIntensity: 0.4,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 50, 53, 91]
                    },
                },
                labels: ['Average Results'],
            };
            var chart = new ApexCharts(document.querySelector("#graficoFlujoCajaCircular"), options);
            chart.render();
            // Fin

            // Grafico de ventas mensuales
            var totalVentasDelMesActualXdia = @json($totalVentasDelMesActualXdia);
            var dia = @json($dia);
            var options = {
                series: [{
                    name: 'Total Ventas del Día',
                    data: totalVentasDelMesActualXdia,
                }],
                chart: {
                    height: 400,
                    type: 'area'
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {
                    // type: 'datetime',
                    categories: dia,
                },
                tooltip: {
                    x: {
                        format: 'dd/MM/yy HH:mm'
                    },
                },
            };
            var chart = new ApexCharts(document.querySelector("#graficoVentasMensual"), options);
            chart.render()
            // Fin

            // Gafico circular ventas mensual
            var porcentajeMeta = @json($pocentajeMeta);
            var options = {
                series: [porcentajeMeta],
                chart: {
                    type: 'radialBar',
                    width: 360,
                    height: 380,
                    offsetY: -20,
                    sparkline: {
                        enabled: true
                    }
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -90,
                        endAngle: 90,
                        track: {
                            background: "#e7e7e7",
                            strokeWidth: '97%',
                            margin: 5, // margin is in pixels
                            dropShadow: {
                                enabled: true,
                                top: 2,
                                left: 0,
                                color: '#999',
                                opacity: 1,
                                blur: 2
                            }
                        },
                        dataLabels: {
                            name: {
                                show: false
                            },
                            value: {
                                offsetY: -2,
                                fontSize: '22px'
                            }
                        }
                    }
                },
                grid: {
                    padding: {
                        top: -10
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        shadeIntensity: 0.4,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 50, 53, 91]
                    },
                },
                labels: ['Average Results'],
            };
            var chart = new ApexCharts(document.querySelector("#graficoCircularVentasMensual"), options);
            chart.render();
            // Fin

            // DOLARES
            // Grafico de ventas diarias
            var totalVentasXdiaDolares = @json($totalVentasXdiaDolares);
            var horasDolares = @json($horasDolares);
            var options = {
                series: [{
                    name: 'Total Ventas X Hora',
                    data: totalVentasXdiaDolares,
                }],
                chart: {
                    height: 400,
                    type: 'area'
                },
                colors: ['#00E396'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {
                    // type: 'datetime',
                    categories: horasDolares,
                },
                tooltip: {
                    x: {
                        format: 'dd/MM/yy HH:mm'
                    },
                },
            };
            var chart = new ApexCharts(document.querySelector("#graficoVentasXdiaDolares"), options);
            chart.render()
            // fin

            // Grafico circular Ventas diarias
            var totalVentasXdiaDolares = @json($totalVentasDolares);
            var options = {
                series: [totalVentasXdiaDolares],
                chart: {
                    type: 'radialBar',
                    width: 360,
                    height: 380,
                    offsetY: -20,
                    sparkline: {
                        enabled: true
                    }
                },
                colors: ['#10ac84'],
                plotOptions: {
                    radialBar: {
                        startAngle: -90,
                        endAngle: 90,
                        track: {
                            background: "#e7e7e7",
                            strokeWidth: '97%',
                            margin: 5, // margin is in pixels
                            dropShadow: {
                                enabled: true,
                                top: 2,
                                left: 0,
                                color: '#999',
                                opacity: 1,
                                blur: 2
                            }
                        },
                        dataLabels: {
                            name: {
                                show: false
                            },
                            value: {
                                offsetY: -2,
                                fontSize: '22px'
                            }
                        }
                    }
                },
                grid: {
                    padding: {
                        top: -10
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        shadeIntensity: 0.4,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 50, 53, 91]
                    },
                },
                labels: ['Average Results'],
            };
            var chart = new ApexCharts(document.querySelector("#graficoCircularXdiaDolares"), options);
            chart.render();
            // Fin

            // Grafico de ventas mensuales
            var totalVentasDelMesActualXdiaDolares = @json($totalVentasDelMesActualXdiaDolares);
            var diaDolares = @json($diaDolares);
            var options = {
                series: [{
                    name: 'Total Ventas del Día',
                    data: totalVentasDelMesActualXdiaDolares,
                }],
                chart: {
                    height: 400,
                    type: 'area'
                },
                colors: ['#00E396'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {
                    // type: 'datetime',
                    categories: diaDolares,
                },
                tooltip: {
                    x: {
                        format: 'dd/MM/yy HH:mm'
                    },
                },
            };
            var chart = new ApexCharts(document.querySelector("#graficoVentasMensualDolares"), options);
            chart.render()
            // Fin

            // Gafico circular ventas mensual
            var porcentajeMetaDolares = @json($pocentajeMetaDolares);
            var options = {
                series: [porcentajeMetaDolares],
                chart: {
                    type: 'radialBar',
                    width: 360,
                    height: 400,
                    offsetY: -20,
                    sparkline: {
                        enabled: true
                    }
                },
                colors: ['#10ac84'],
                plotOptions: {
                    radialBar: {
                        startAngle: -90,
                        endAngle: 90,
                        track: {
                            background: "#e7e7e7",
                            strokeWidth: '97%',
                            margin: 5, // margin is in pixels
                            dropShadow: {
                                enabled: true,
                                top: 2,
                                left: 0,
                                color: '#999',
                                opacity: 1,
                                blur: 2
                            }
                        },
                        dataLabels: {
                            name: {
                                show: false
                            },
                            value: {
                                offsetY: -2,
                                fontSize: '22px'
                            }
                        }
                    }
                },
                grid: {
                    padding: {
                        top: -10
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        shadeIntensity: 0.4,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 50, 53, 91]
                    },
                },
                labels: ['Average Results'],
            };
            var chart = new ApexCharts(document.querySelector("#graficoCircularVentasMensualDolates"), options);
            chart.render();
            // Fin
        </script>
    @endif
    @if ($inputValorBoton == 'flujoCaja')
        <script>
            $('#flujoDeCaja').removeClass('d-none');
            $('#btnFlujoCaja').addClass('btn--verde');

            var cantidadEgreso = @json($cantidadEgreso);
            var fechaEgreso = @json($fechaEgreso);
            var montoTotalEgreso = @json($montoTotalEgreso);
            var options = {
                series: [{
                    name: 'Total Egreso',
                    type: 'column',
                    data: montoTotalEgreso
                }, {
                    name: 'Cantidad Egreso',
                    type: 'line',
                    data: cantidadEgreso
                }],
                chart: {
                    height: 350,
                    type: 'line',
                },
                stroke: {
                    width: [0, 4]
                },
                dataLabels: {
                    enabled: true,
                    enabledOnSeries: [1]
                },
                labels: fechaEgreso,
                xaxis: {
                    //   type: 'datetime'
                },
                yaxis: [{
                    title: {
                        text: 'Total Egreso',
                    },

                }, {
                    opposite: true,
                    title: {
                        text: 'Cantidad Egreso'
                    }
                }]
            };
            var chart = new ApexCharts(document.querySelector("#graficoFlujoCaja"), options);
            chart.render();
            // Cierre de caja Diarios Soles
            var montoTotalCierreCaja = @json($montoTotalCierreCajaSoles);
            var fechaCierreCaja = @json($fechaCierreCaja);
            var options = {
                series: [{
                    name: 'Total Cierre Caja S/.',
                    data: montoTotalCierreCaja
                }],

                chart: {
                    type: 'bar',
                    height: 350,
                    stacked: true,
                    toolbar: {
                        show: true
                    },
                    zoom: {
                        enabled: true
                    }
                },
                colors: ['#21BF73'],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        legend: {
                            position: 'bottom',
                            offsetX: -10,
                            offsetY: 0
                        }
                    }
                }],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        borderRadius: 10
                    },
                },
                xaxis: {
                    //   type: 'datetime',
                    categories: fechaCierreCaja
                },
                legend: {
                    position: 'right',
                    offsetY: 40
                },
                fill: {
                    opacity: 1
                }
            };

            var chart = new ApexCharts(document.querySelector("#graficoCierreCaja"), options);
            chart.render();

            // Cierre de caja Diarios Dolares
            var montoTotalCierreCajaDolares = @json($montoTotalCierreCajaDolares);
            var fechaCierreCajaDolares = @json($fechaCierreCajaDolares);
            var options = {
                series: [{
                    name: 'Total Cierre Caja $',
                    data: montoTotalCierreCajaDolares
                }],

                chart: {
                    type: 'bar',
                    height: 350,
                    stacked: true,
                    toolbar: {
                        show: true
                    },
                    zoom: {
                        enabled: true
                    }
                },
                colors: ['#5555FF'],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        legend: {
                            position: 'bottom',
                            offsetX: -10,
                            offsetY: 0
                        }
                    }
                }],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        borderRadius: 10
                    },
                },
                xaxis: {
                    //   type: 'datetime',
                    categories: fechaCierreCajaDolares
                },
                legend: {
                    position: 'right',
                    offsetY: 40
                },
                fill: {
                    opacity: 1
                }
            };

            var chart = new ApexCharts(document.querySelector("#graficoCierreCajaDolares"), options);
            chart.render();
        </script>
    @endif
    @if ($inputValorBoton == 'almacenStock')
        <script>
            $('#almacenStock').removeAttr('class', 'd-none')
            $('#btnAlmacen').addClass('btn--verde');

            //  REPORTES STOCK PRODUCTOS
            var totalBaja = @json($totalBaja);
            var fechaBaja = @json($fechaBaja);
            var options = {
                series: [{
                    name: 'Total de Bajas',
                    data: totalBaja
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    stacked: true,
                    toolbar: {
                        show: true
                    },
                    zoom: {
                        enabled: true
                    }
                },
                colors: ['#009EF7'],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        legend: {
                            position: 'bottom',
                            offsetX: -10,
                            offsetY: 0
                        }
                    }
                }],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        borderRadius: 10
                    },
                },
                xaxis: {
                    //   type: 'datetime',
                    categories: fechaBaja
                },
                legend: {
                    position: 'right',
                    offsetY: 40
                },
                fill: {
                    opacity: 1
                }
            };

            var chart = new ApexCharts(document.querySelector("#graficoAlmacenStock"), options);
            chart.render();

            // Reporte Productos Menos vendidos
            var menosVendidosDescripcion = @json($menosVendidosDescripcion);
            var menosVendidosTotal = @json($menosVendidosTotal);
            var options = {
                series: [{
                    name: 'Cantidad',
                    data: menosVendidosTotal
                }],
                annotations: {
                    points: [{
                        x: 'Bananas',
                        seriesIndex: 0,
                        label: {
                            borderColor: '#775DD0',
                            offsetY: 0,
                            style: {
                                color: '#fff',
                                background: '#775DD0',
                            },
                            text: 'Bananas are good',
                        }
                    }]
                },
                chart: {
                    height: 350,
                    type: 'bar',
                },

                plotOptions: {
                    bar: {
                        columnWidth: '45%',
                        distributed: true,
                    }
                },
                dataLabels: {
                    enabled: false
                },
                legend: {
                    show: false
                },
                stroke: {
                    width: 2
                },

                grid: {
                    row: {
                        // colors: ['#fff', '#f2f2f2']
                    }
                },
                xaxis: {
                    labels: {
                        rotate: -60,
                        show: false,
                    },
                    categories: menosVendidosDescripcion,
                    tickPlacement: 'on'
                },
                yaxis: {
                    title: {
                        text: 'Cantidad',
                    },
                },
            };

            var chart = new ApexCharts(document.querySelector("#graficoAlmacenMenosVendido"), options);
            chart.render();

            //  Reporte Productos Más vendidos del mes
            var masVendidosDescripcion = @json($masVendidosDescripcion);
            var masVendidosTotal = @json($masVendidosTotal);
            var options = {
                series: [{
                    name: 'Cantidad',
                    data: masVendidosTotal
                }],
                annotations: {
                    points: [{
                        x: 'Bananas',
                        seriesIndex: 0,
                        label: {
                            borderColor: '#775DD0',
                            offsetY: 0,
                            style: {
                                color: '#fff',
                                background: '#775DD0',
                            },
                            text: 'Bananas are good',
                        }
                    }]
                },
                chart: {
                    height: 350,
                    type: 'bar',
                },

                plotOptions: {
                    bar: {
                        columnWidth: '45%',
                        distributed: true,
                    }
                },
                dataLabels: {
                    enabled: false
                },
                legend: {
                    show: false
                },
                stroke: {
                    width: 2
                },

                grid: {
                    row: {
                        // colors: ['#fff', '#f2f2f2']
                    }
                },
                xaxis: {
                    labels: {
                        rotate: -60,
                        show: false,
                    },
                    categories: masVendidosDescripcion,
                    tickPlacement: 'on'
                },
                yaxis: {
                    title: {
                        text: 'Cantidad',
                    },
                },
            };

            var chart = new ApexCharts(document.querySelector("#graficoAlmacenMasVendido"), options);
            chart.render();
        </script>
    @endif
    @if ($inputValorBoton == 'cotizacion')
        <script>
            $("#cotizaciones").removeClass('d-none');
            $('#btnCotizacion').addClass('btn--verde');

            var pocentajeMetaCotizacionEsteMes = @json($pocentajeMetaCotizacionEsteMes);
            var options = {
                chart: {
                    height: 220,
                    type: "radialBar"
                },

                series: [pocentajeMetaCotizacionEsteMes],

                plotOptions: {
                    radialBar: {
                        hollow: {
                            margin: 15,
                            size: "70%"
                        },

                        dataLabels: {
                            showOn: "always",
                            name: {
                                offsetY: -10,
                                show: true,
                                color: "#888",
                                fontSize: "13px"
                            },
                            value: {
                                color: "#111",
                                fontSize: "30px",
                                show: true,
                                fontWeight: 600,
                            }
                        }
                    }
                },
                stroke: {
                    lineCap: "round",
                },
                labels: ["Progreso "]
            };

            var chart = new ApexCharts(document.querySelector("#graficoCotizaciones"), options);
            chart.render();
        </script>
    @endif
    @if ($inputValorBoton == 'ganancias')
        <script>
            $("#ganancias").removeClass('d-none');
            $('#btnGanancias').addClass('btn--verde');

            var totalGananciasXmes = @json($totalGananciasXmes);
            var arrayFechasGanancias = @json($arrayFechasGanancias);
            var options = {
                series: [{
                    name: 'Monto Total de Ganancias',
                    type: 'column',
                    data: totalGananciasXmes
                }],
                chart: {
                    height: 350,
                    type: 'line',
                },
                stroke: {
                    width: [0, 4]
                },
                dataLabels: {
                    enabled: true,
                    enabledOnSeries: [1]
                },
                labels: arrayFechasGanancias,
            };

            var chart = new ApexCharts(document.querySelector("#graficoGanancias"), options);
            chart.render();

            var totalGananciasXmesDolares = @json($totalGananciasXmesDolares);
            var arrayFechasGananciasDolares = @json($arrayFechasGananciasDolares);
            var options = {
                series: [{
                    name: 'Monto Total de Ganancias',
                    type: 'column',
                    data: totalGananciasXmesDolares
                }],
                chart: {
                    height: 350,
                    type: 'line',
                },
                stroke: {
                    width: [0, 4]
                },
                colors: ['#00E396', '#008FFB'],
                dataLabels: {
                    enabled: true,
                    enabledOnSeries: [1]
                },
                labels: arrayFechasGananciasDolares,
            };

            var chart = new ApexCharts(document.querySelector("#graficoGananciasDolares"), options);
            chart.render();
        </script>
    @endif
    @if ($inputValorBoton == 'bancos')
        <script>
            $("#ganancias").removeClass('d-none');
            $('#btnBancos').addClass('btn--verde');

            $(() => {
                $('#datosReporteBanco').hide();
                $('#seccionMensaje').hide();
                $('#loader').hide();
            })

            $(document).ready(function() {
                const idBanco = $('#selectBanco').val();
                ejecutarAjax(idBanco);
            })

            $("#selectBanco").change(function() {
                $('#graficoBancos').remove();
                $('#seccionGrafico').append('<div id="graficoBancos"></div>');
                $('#datosReporteBanco').hide();
                $('#loader').show();
                $('#seccionMensaje').hide();
                const idBanco = $(this).val();
                ejecutarAjax(idBanco);
            })

            function ejecutarAjax(idBanco) {
                $.ajax({
                    type: 'GET',
                    url: 'reportes/ajax',
                    data: {
                        'idBanco': idBanco,
                    },

                    success: function($data) {
                        $('#loader').hide();
                        console.log($data);
                        if ($data[0].length == 0) {
                            return $('#seccionMensaje').show();
                        } else {
                            $('#datosReporteBanco').show();
                            $('#totalIngresoBanco').text($data[2].toFixed(2));
                            $('#totalSalidaBanco').text($data[3].toFixed(2))
                            $('#saldoMesActual').text($data[4].toFixed(2))
                            var options = {
                                series: [{
                                    name: 'Cantidad:',
                                    data: $data[1]
                                }],
                                chart: {
                                    height: 350,
                                    type: 'bar',
                                },
                                plotOptions: {
                                    bar: {
                                        columnWidth: '45%',
                                        distributed: true,
                                    }
                                },
                                dataLabels: {
                                    enabled: true
                                },
                                legend: {
                                    show: false
                                },
                                xaxis: {
                                    categories: $data[0],
                                    labels: {
                                        style: {
                                            fontSize: '12px'
                                        }
                                    }
                                }
                            };
                            var chart = new ApexCharts(document.querySelector("#graficoBancos"), options);
                            chart.render();
                        }
                    }
                })
            }

            // $(() => {
            //     alert('respuesta');
            // })

            // $("#selectBanco").click(function() {
            //     console.log('respuesta');
            // })

            // const selectBanco = document.querySelector('#selectBanco');
            // selectBanco.addEventListener('click', function() {
            //     console.log('datos');
            // })
            // $.ajax({
            //     type: 'GET',
            //     url: 'area-administrativa/ajax',
            //     data: {},
            //     success: function(params) {}
            // })





            // var options = {
            //     series: [{
            //         name: 'Series 1',
            //         data: [20, 100, 40, 30, 50, 80, 33],
            //     }],
            //     chart: {
            //         height: 350,
            //         type: 'radar',
            //     },
            //     dataLabels: {
            //         enabled: true
            //     },
            //     plotOptions: {
            //         radar: {
            //             size: 140,
            //             polygons: {
            //                 strokeColors: '#e9e9e9',
            //                 fill: {
            //                     colors: ['#f8f8f8', '#fff']
            //                 }
            //             }
            //         }
            //     },
            //     title: {
            //         text: ''
            //     },
            //     colors: ['#FF4560'],
            //     markers: {
            //         size: 4,
            //         colors: ['#fff'],
            //         strokeColor: '#FF4560',
            //         strokeWidth: 2,
            //     },
            //     tooltip: {
            //         y: {
            //             formatter: function(val) {
            //                 return val
            //             }
            //         }
            //     },
            //     xaxis: {
            //         categories: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
            //     },
            //     yaxis: {
            //         tickAmount: 7,
            //         labels: {
            //             formatter: function(val, i) {
            //                 if (i % 2 === 0) {
            //                     return val
            //                 } else {
            //                     return ''
            //                 }
            //             }
            //         }
            //     }
            // };

            // var chart = new ApexCharts(document.querySelector("#graficoBancos"), options);
            // chart.render();

            // var options = {
            //     series: [{
            //         name: 'Cantidad:',
            //         data: ,
            //     }],
            //     chart: {
            //         height: 350,
            //         type: 'bar',
            //     },
            //     plotOptions: {
            //         bar: {
            //             columnWidth: '45%',
            //             distributed: true,
            //         }
            //     },
            //     dataLabels: {
            //         enabled: true
            //     },
            //     legend: {
            //         show: false
            //     },
            //     xaxis: {
            //         categories: ,
            //         labels: {
            //             style: {
            //                 fontSize: '12px'
            //             }
            //         }
            //     }
            // };
            // var chart = new ApexCharts(document.querySelector("#graficoBancos"), options);
            // chart.render();
        </script>
    @endif
@stop
