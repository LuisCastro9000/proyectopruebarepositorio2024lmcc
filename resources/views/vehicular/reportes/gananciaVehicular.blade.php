@extends('layouts.app')
@section('title', 'Ganancia Vehicular')
@section('content')

    <div class="container mt-4">
        <div class="jumbotron bg-white pt-3 pb-2 border-radius--10">
            {!! Form::open(['url' => '/vehicular/reportes/filtrar-ganancias', 'method' => 'POST', 'files' => true]) !!}
            {{ csrf_field() }}
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="form-group form-material">
                        <label>Mecánico / Operador</label>
                        <select class="m-b-10 form-control select2-hidden-accessible" id="mecanico" name="mecanico"
                            data-placeholder="Seleccione Mecanico" data-toggle="select2" tabindex="-1" aria-hidden="true">
                            <option value="0">Seleccione Mecanico</option>
                            {{-- @if ($inputMecanico == 'Generico')
                                <option value="Generico" selected>Generico</option>
                            @else
                                <option value="Generico">Generico</option>
                            @endif --}}
                            <option value="Genérico">Genérico</option>
                            @foreach ($mecanicos as $mecanico)
                                {{-- @if ($inputMecanico == $mecanico->IdOperario)
                                    <option value="{{ $mecanico->IdOperario }}" selected>{{ $mecanico->Nombres }}
                                    </option>
                                @else
                                    <option value="{{ $mecanico->IdOperario }}">{{ $mecanico->Nombres }}</option>
                                @endif --}}
                                <option value="{{ $mecanico->IdOperario }}">{{ $mecanico->Nombres }}</option>
                            @endforeach
                        </select>
                        <input id="inputNombreMecanico" type="hidden" name="inputNombreMecanico">
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group form-material">
                        <label>Placa</label>
                        <select class="m-b-10 form-control select2-hidden-accessible" id="placa" name="placa"
                            data-placeholder="Seleccione Placa" data-toggle="select2" tabindex="-1" aria-hidden="true">
                            <option value="0">Seleccione Placa</option>
                            @foreach ($placas as $placa)
                                {{-- @if ($placa->PlacaVehiculo == $inputPlaca)
                                    <option value="{{ $placa->PlacaVehiculo }}" selected>{{ $placa->PlacaVehiculo }}
                                    </option>
                                @else
                                    <option value="{{ $placa->PlacaVehiculo }}">{{ $placa->PlacaVehiculo }}</option>
                                @endif --}}
                                <option value="{{ $placa->PlacaVehiculo }}">{{ $placa->PlacaVehiculo }}</option>
                            @endforeach
                        </select>
                        <input id="inputNombrePlaca" type="hidden" name="inputNombrePlaca">
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <x-selectorFiltrosFechas :obtenerDatos="'false'" :tipoRangoFechas="'anual'"></x-selectorFiltrosFechas>
                </div>
                <div class="col-12 mt-3">
                    <section class="d-flex justify-content-center flex-wrap">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </div>
                        <div class="form-group mx-0 mx-md-3">
                            <a class="p-0" target="_blank"
                                href='{{ url("vehicular/reportes/excel-ganancia/$_inputPlaca/$_inputMecanico/$fecha/$ini/$fin") }}'>
                                <span class="btn bg-excel ripple">
                                    <i class="list-icon material-icons fs-20">explicit</i>XCEL
                                </span>
                            </a>
                        </div>
                        <div class="form-group">
                            <a class="py-1" href="https://www.youtube.com/watch?v=FNUHWu3rEN0" target="_blank">
                                <span class="btn btn-autocontrol-naranja ripple text-white">
                                    Video Instructivo <i class="list-icon material-icons fs-22 color-icon">videocam</i>
                                </span>
                            </a>
                        </div>

                    </section>
                </div>
            </div>
            <x-inputFechasPersonalizadas :mostrarBoton="'false'" :tipoRangoFechas="'anual'"></x-inputFechasPersonalizadas>
            {!! Form::close() !!}
        </div>

        @if (count($ganancia) == 0)
            <section class="row">
                <article class="col-12 text-center font-weight-bold">
                    <span class="fs-28">NO HAY DATOS REGISTRADOS</span>
                </article>
            </section>
        @else
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                @if ($subniveles->contains('IdSubNivel', 46))
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home" type="button"
                            role="tab" aria-controls="home" aria-selected="true">Ganacias en
                            Soles</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile" type="button"
                            role="tab" aria-controls="profile" aria-selected="false">Ganacias en
                            Dólares</button>
                    </li>
                @endIf
            </ul>
            <div class="tab-content" id="myTabContent">
                {{-- Pesataña Soles --}}
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row mt-4 justify-content-center">
                        <section class="col-12 col-md-12  text-center">
                            @if ($inputMecanico != 0)
                                <Span>MECÁNICO: </Span>
                                <p class="fs-26">{{ $inputNombreMecanico }}</p>
                            @endif
                            @if ($inputPlaca != 0)
                                <Span>PLACA: </Span>
                                <p class="fs-26">{{ $inputNombrePlaca }}</p>
                            @endif
                        </section>
                        <section class="col-12 col-md-4 mb-2 mb-md-0">
                            <div class="card card-bg--verde ">
                                <div class="card-body px-4 pt-2">
                                    <section class="text-center mb-2">
                                        <span class="fs-26 font-weight-bold">PRODUCTOS</span>
                                        <hr>
                                    </section>
                                    <section class="">
                                        <article class="d-flex justify-content-between">
                                            <span class="fs-16">Ganancia:</span>
                                            <span class="font-weight-bold fs-16">S/
                                                {{ number_format($gananciaTotalProductosSoles, 2, '.', ',') }}</span>
                                        </article>
                                        <article class="d-flex justify-content-between">
                                            <span class="fs-16">Costo:</span>
                                            <span class="font-weight-bold fs-16">S/
                                                {{ number_format($costoTotalProductosSoles, 2, '.', ',') }}</span>
                                        </article>
                                        <article class="d-flex justify-content-between">
                                            <span class="fs-16">Ingreso:</span>
                                            <span class="font-weight-bold fs-16">S/
                                                {{ number_format($totalImporteProductoSoles, 2, '.', ',') }}</span>
                                        </article>

                                    </section>
                                </div>
                            </div>
                        </section>
                        <section class="col-12 col-md-4 mb-2 mb-md-0">
                            <div class="card card-bg--verde">
                                <div class="card-body px-4 pt-2 text-center">
                                    <span class="fs-26 font-weight-bold">ATENCIONES REALIZADAS</span>
                                    <hr><br>
                                    <span class="font-weight-bold fs-34">{{ $cantVehiculosAtendidosSoles }}</span>
                                </div>
                            </div>
                        </section>
                        <section class="col-12 col-md-4 mb-2 mb-md-0">
                            <div class="card card-bg--verde ">
                                <div class="card-body px-4 pt-2">
                                    <section class="text-center mb-2">
                                        <span class="fs-26 font-weight-bold">SERVICIOS</span>
                                        <hr>
                                    </section>
                                    <section class="">
                                        <article class="d-flex justify-content-between">
                                            <span class="fs-16">Ganancia:</span>
                                            <span class="font-weight-bold fs-16">S/
                                                {{ number_format($gananciaTotalServiciosSoles, 2, '.', ',') }}</span>
                                        </article>
                                        <article class="d-flex justify-content-between">
                                            <span class="fs-16">Costo:</span>
                                            <span class="font-weight-bold fs-16">S/
                                                {{ number_format($costoTotalServiciosSoles, 2, '.', ',') }}</span>
                                        </article>
                                        <article class="d-flex justify-content-between">
                                            <span class="fs-16">Ingreso:</span>
                                            <span class="font-weight-bold fs-16">S/
                                                {{ number_format($totalImporteServicioSoles, 2, '.', ',') }}</span>
                                        </article>
                                    </section>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12 col-lg-6">
                            <div class="card">
                                <div id="graficoProductos">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="card">
                                <div id="graficoServicios">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Fin --}}
                {{-- Pestaña Dolares --}}
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="row mt-4 justify-content-center">
                        <section class="col-12 col-md-12  text-center">
                            @if ($inputMecanico != 0)
                                <Span>MECÁNICO: </Span>
                                <p class="fs-26">{{ $inputNombreMecanico }}</p>
                            @endif
                            @if ($inputPlaca != 0)
                                <Span>PLACA: </Span>
                                <p class="fs-26">{{ $inputNombrePlaca }}</p>
                            @endif
                        </section>
                        <section class="col-12 col-md-4 mb-2 mb-md-0">
                            <div class="card card-bg--verde ">
                                <div class="card-body px-4 pt-2">
                                    <section class="text-center mb-2">
                                        <span class="fs-26 font-weight-bold">PRODUCTOS</span>
                                        <hr>
                                    </section>
                                    <section class="">
                                        <article class="d-flex justify-content-between">
                                            <span class="fs-16">Ganancia:</span>
                                            <span class="font-weight-bold fs-16">$
                                                {{ number_format($gananciaTotalProductosDolares, 2, '.', ',') }}</span>
                                        </article>
                                        <article class="d-flex justify-content-between">
                                            <span class="fs-16">Costo:</span>
                                            <span class="font-weight-bold fs-16">$
                                                {{ number_format($costoTotalProductosDolares, 2, '.', ',') }}</span>
                                        </article>
                                        <article class="d-flex justify-content-between">
                                            <span class="fs-16">Ingreso:</span>
                                            <span class="font-weight-bold fs-16">$
                                                {{ number_format($totalImporteProductoDolares, 2, '.', ',') }}</span>
                                        </article>

                                    </section>
                                </div>
                            </div>
                        </section>
                        <section class="col-12 col-md-4 mb-2 mb-md-0">
                            <div class="card card-bg--verde">
                                <div class="card-body px-4 pt-2 text-center">
                                    <span class="fs-26 font-weight-bold">ATENCIONES REALIZADAS</span>
                                    <hr><br>
                                    <span class="font-weight-bold fs-34">{{ $cantVehiculosAtendidosDolares }}</span>
                                </div>
                            </div>
                        </section>
                        <section class="col-12 col-md-4 mb-2 mb-md-0">
                            <div class="card card-bg--verde ">
                                <div class="card-body px-4 pt-2">
                                    <section class="text-center mb-2">
                                        <span class="fs-26 font-weight-bold">SERVICIOS</span>
                                        <hr>
                                    </section>
                                    <section class="">
                                        <article class="d-flex justify-content-between">
                                            <span class="fs-16">Ganancia:</span>
                                            <span class="font-weight-bold fs-16">$
                                                {{ number_format($gananciaTotalServiciosDolares, 2, '.', ',') }}</span>
                                        </article>
                                        <article class="d-flex justify-content-between">
                                            <span class="fs-16">Costo:</span>
                                            <span class="font-weight-bold fs-16">$
                                                {{ number_format($costoTotalServiciosDolares, 2, '.', ',') }}</span>
                                        </article>
                                        <article class="d-flex justify-content-between">
                                            <span class="fs-16">Ingreso:</span>
                                            <span class="font-weight-bold fs-16">$
                                                {{ number_format($totalImporteServicioDolares, 2, '.', ',') }}</span>
                                        </article>
                                    </section>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12 col-lg-6">
                            <div class="card">
                                <div id="graficoProductosDolares">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="card">
                                <div id="graficoServiciosDolares">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- FIn --}}
            </div>
            <br><br>
            <div class="row mt-4">
                <div class="col-12">
                    <table id="table" class="table table-responsive-lg" style="width:100%">
                        <thead>
                            <tr class="bg-primary">
                                <th scope="col">FechaCreacion</th>
                                <th scope="col">Placa</th>
                                <th scope="col">Razón Social</th>
                                <th scope="col">Número Documento</th>
                                <th scope="col">Número Cotización</th>
                                <th scope="col">Costo Total Producto</th>
                                <th scope="col">Ganancia Total Productos</th>
                                <th scope="col">Costo Total Servicio</th>
                                <th scope="col">Ganancia Total Servicios</th>
                                <th scope="col">Tipo Moneda</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ganancia as $datos)
                                <tr>
                                    <td> {{ $datos->FechaCreacion }}</td>
                                    <td> {{ $datos->Placa }}</td>
                                    <td> {{ $datos->RazonSocial }}</td>
                                    <td> {{ $datos->NumeroDocumento }}</td>
                                    <td> {{ $datos->Cotizacion }}</td>
                                    <td> {{ $datos->CostoProducto }}</td>
                                    <td> {{ $datos->GananciaProducto }}</td>
                                    <td> {{ $datos->CostoServicio }}</td>
                                    <td> {{ $datos->GananciaServicio }}</td>
                                    <td> {{ $datos->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
@endsection

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
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
    <script>
        // $(function() {
        //     if ($('#mecanico option:selected').val() != 0) {
        //         $('#placa').attr('disabled', 'disabled')
        //     } else {
        //         $('#placa').removeAttr('disabled')
        //     }

        //     if ($('#placa option:selected').val() != 0) {
        //         $('#mecanico').attr('disabled', 'disabled')
        //     } else {
        //         $('#mecanico').removeAttr('disabled')
        //     }
        // })
        // let a = @json($inputPlaca);
        // console.log(a);

        $('#mecanico').change(function() {
            if ($('#mecanico option:selected').val() != 0) {
                $('#placa').attr('disabled', 'disabled')
                $nombreMecanico = $('#mecanico option:selected').text();
                $('#inputNombreMecanico').val($nombreMecanico);
            } else {
                $('#placa').removeAttr('disabled')
            }
        });

        $('#placa').change(function() {
            if ($('#placa option:selected').val() != 0) {
                $('#mecanico').attr('disabled', 'disabled')
                $nombrePlaca = $('#placa option:selected').text();
                $('#inputNombrePlaca').val($nombrePlaca);
            } else {
                $('#mecanico').removeAttr('disabled')
            }
        });
    </script>

    <script>
        let $arrayTotalesProductos = @json($arrayTotalesProductosSoles);
        var options = {
            series: $arrayTotalesProductos,
            chart: {
                type: 'polarArea',
                height: 460
            },
            labels: ["Costo total", "Ganancia Total"],
            fill: {
                opacity: 1
            },
            title: {
                text: 'Productos',
                margin: 50,
                align: 'center'
            },
            colors: ['#1B1464', '#FECC6C', '#12CBC4', '#6794DC', '#5CEDBC', '#A897E1', '#FF8899', '#5CB7FC'],
            stroke: {
                width: 1,
                colors: undefined
            },
            yaxis: {
                show: false
            },
            legend: {
                position: 'bottom'
            },
            plotOptions: {
                polarArea: {
                    rings: {
                        strokeWidth: 0
                    },
                    spokes: {
                        strokeWidth: 0
                    },
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#graficoProductos"), options);
        chart.render();


        let $arrayTotalesServicios = @json($arrayTotalesServiciosSoles);
        var options = {
            series: $arrayTotalesServicios,
            chart: {
                type: 'polarArea',
                height: 460
            },
            labels: ["Costo total", "Ganancia Total"],
            fill: {
                opacity: 1
            },
            title: {
                text: 'Servicios',
                margin: 50,
                align: 'center'
            },
            colors: ['#A897E1', '#FF8899', '#5CB7FC'],
            stroke: {
                width: 1,
                colors: undefined
            },
            yaxis: {
                show: false
            },
            legend: {
                position: 'bottom'
            },
            plotOptions: {
                polarArea: {
                    rings: {
                        strokeWidth: 0
                    },
                    spokes: {
                        strokeWidth: 0
                    },
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#graficoServicios"), options);
        chart.render();
    </script>

    <script>
        let $arrayTotalesProductosDolares = @json($arrayTotalesProductosDolares);
        var options = {
            series: $arrayTotalesProductosDolares,
            chart: {
                type: 'polarArea',
                height: 460
            },
            labels: ["Costo total", "Ganancia Total"],
            fill: {
                opacity: 1
            },
            title: {
                text: 'Productos',
                margin: 50,
                align: 'center'
            },
            colors: ['#6794DC', '#5CEDBC', '#A897E1', '#FF8899', '#5CB7FC'],
            stroke: {
                width: 1,
                colors: undefined
            },
            yaxis: {
                show: false
            },
            legend: {
                position: 'bottom'
            },
            plotOptions: {
                polarArea: {
                    rings: {
                        strokeWidth: 0
                    },
                    spokes: {
                        strokeWidth: 0
                    },
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#graficoProductosDolares"), options);
        chart.render();


        let $arrayTotalesServiciosDolares = @json($arrayTotalesServiciosDolares);
        var options = {
            series: $arrayTotalesServiciosDolares,
            chart: {
                type: 'polarArea',
                height: 460
            },
            labels: ["Costo total", "Ganancia Total"],
            fill: {
                opacity: 1
            },
            title: {
                text: 'Servicios',
                margin: 50,
                align: 'center'
            },
            colors: ['#FF8899', '#5CB7FC'],
            stroke: {
                width: 1,
                colors: undefined
            },
            yaxis: {
                show: false
            },
            legend: {
                position: 'bottom'
            },
            plotOptions: {
                polarArea: {
                    rings: {
                        strokeWidth: 0
                    },
                    spokes: {
                        strokeWidth: 0
                    },
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#graficoServiciosDolares"), options);
        chart.render();
    </script>
@endsection
