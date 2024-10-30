@extends('layouts.app')
@section('title', 'Reporte Financiero de Gastos')
@section('content')
    <style>
        .z-index_Grafico {
            opacity: .99;
        }

        .card_datos {
            font-size: 28px;
        }

        .border-radius-10 {
            border-radius: 10px !important;
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
        {!! Form::open(['url' => '/reportes/financieros/gastos', 'method' => 'POST', 'files' => true]) !!}
        {{ csrf_field() }}
        <div class="row clearfix">
            @php
                if ($subniveles->contains('IdSubNivel', 46)) {
                    $colTipoGasto = 'col-lg-2';
                    $colTipoMoneda = 'col-lg-3';
                    $colFecha = 'col-lg-3';
                } else {
                    $colTipoGasto = 'col-lg-4';
                    $colFecha = 'col-lg-4';
                }
            @endphp
            <div class="{{ $colTipoGasto }} mt-4">
                <div class="form-group form-material">
                    <label>Tipo de Gasto</label>
                    <select id="tipoGasto" class="form-control" name="tipoGasto">
                        <option value="0">Todo</option>
                        <option value="1">Fijo</option>
                        <option value="2">Variable</option>
                    </select>
                </div>
            </div>
            @if ($subniveles->contains('IdSubNivel', 46))
                <div class="{{ $colTipoMoneda }} mt-4">
                    <div class="form-group form-material">
                        <label>Tipo Moneda</label>
                        <select id="tipoMoneda" class="form-control" name="tipoMoneda">
                            <option value="1">Soles</option>
                            <option value="2">Dolares</option>
                        </select>
                    </div>
                </div>
            @else
                <input type="hidden" name="tipoMoneda" value="1">
            @endif
            <div class="{{ $colFecha }} mt-4">
                <x-selectorFiltrosFechas obtenerDatos='false' class='form-material' />
            </div>
            <div class="col-lg-2 col-sm-4 col-12 mt-0 mt-md-4 text-center">
                <br>
                <a class="" href="https://www.youtube.com/watch?v=bXF6rr2CeD8" target="_blank">
                    <span class="btn btn-autocontrol-naranja ripple text-white">
                        Video Instructivo <i class="list-icon material-icons fs-24 color-icon">videocam</i>
                    </span>
                </a>
            </div>
            <div class="col-lg-1 col-sm-2 col-6 mt-0 mt-md-4  text-center">
                <div class="form-group">
                    <br>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </div>

            <div class="col-lg-1 col-sm-2 col-6 mt-0 mt-md-4  text-center ">
                <br>
                <a class="" target="_blank"
                    href='{{ url("reportes/financieros/excel-gastos/$tipoGasto/$tipoMoneda/$fecha/$ini/$fin") }}'>
                    <span class="btn bg-excel ripple">
                        <i class="list-icon material-icons fs-20">explicit</i>XCEL
                    </span>
                </a>
            </div>
        </div>
        <x-inputFechasPersonalizadas mostrarBoton='false' />
        {!! Form::close() !!}
    </div>
    <!-- /.container -->
    <!-- =================================== -->
    <!-- Different data widgets ============ -->
    <!-- =================================== -->
    <div class="container">
        <div class="widget-list">
            {{-- Nuevos Graficos --}}
            @php
                $simboloMoneda = $tipoMoneda == 1 ? 'S/.' : '$';
            @endphp
            <div class="row my-3">
                <div class="col-12 col-lg-4">
                    <div class=" card card-body  text-center bg-info border-radius-10">
                        <span class="card_datos font-weight-bold ">{{ $simboloMoneda }}
                            {{ number_format($sumatoriaTotalGastosFijos, 2, '.', ',') }}</span>
                        <span class=" text-dark font-weight-bold">Monto total de Gastos Fijos</span>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class=" card card-body  text-center bg-warning border-radius-10">
                        <span class="card_datos font-weight-bold">{{ $simboloMoneda }}
                            {{ number_format($sumatoriaGastosFijosVariables, 2, '.', ',') }}</span>
                        <span class=" text-dark font-weight-bold">T. de Gastos Fijos más Variables</span>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class=" card card-body  text-center bg-success border-radius-10">
                        <span class="card_datos font-weight-bold">{{ $simboloMoneda }}
                            {{ number_format($sumatoriaTotalGastosVariables, 2, '.', ',') }}</span>
                        <span class=" text-dark font-weight-bold">Monto total de Gastos Variables</span>
                    </div>
                </div>
            </div>
            <div class="row">

                @if ($tipoGasto == 0)
                    @if (count($gastosFijos) >= 1)
                        <div class="col-12 col-lg-12 my-4 z-index_Grafico">
                            <div class="card w-100">
                                <section class="text-center mt-3">
                                    <span class="fs-16 font-weight-bold">Reporte de Detalle Total x Item</span><br>
                                    <span class="fs-16 font-weight-bold badge badge-info my-2">Gastos Fijos</span>
                                </section>
                                <div class="px-4" id="graficoGastosFijos">
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (count($gastosVariables) >= 1)
                        <div class="col-12 col-lg-12 my-4 z-index_Grafico">
                            <div class="card w-100">
                                <section class="text-center mt-3">
                                    <span class="fs-16 font-weight-bold">Reporte de Detalle Total x Item</span><br>
                                    <span class="fs-16 font-weight-bold badge badge-success my-2">Gastos Variables</span>
                                </section>
                                <div class="px-4" id="graficoGastosVariables">
                                </div>
                            </div>
                        </div>
                    @endif
                @elseif($tipoGasto == 1)
                    <div class="col-12 my-3 z-index_Grafico">
                        <div class="card w-100">
                            <section class="text-center mt-3">
                                <span class="fs-16 font-weight-bold">Reporte de Detalle Total x Item</span><br>
                                <span class="fs-16 font-weight-bold badge badge-info my-2">Gastos Fijos</span>
                            </section>
                            <div class="px-4" id="graficoGastosFijos">
                            </div>
                        </div>
                    </div>
                @elseif($tipoGasto == 2)
                    <div class="col-12 my-3 z-index_Grafico">
                        <div class="card w-100">
                            <section class="text-center mt-3">
                                <span class="fs-16 font-weight-bold">Reporte de Detalle Total x Item</span><br>
                                <span class="fs-16 font-weight-bold badge badge-success my-2">Gastos Variables</span>
                            </section>
                            <div class="px-4" id="graficoGastosVariables">
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            {{-- Fin --}}
            {{-- <div class="row">
                <div class="col-md-3"></div>
                <div class="col-12 col-md-6 widget-holder widget-full-content widget-full-height">
                    <div class="widget-bg">
                        <div class="widget-heading">
                            <h5 class="widget-title">Reporte de Gastos</h5>
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

            <div class="row">
                <div class="col-md-12 widget-holder">
                    <div class="widget-bg">
                        <!-- /.widget-heading -->
                        <div class="widget-body clearfix">
                            <!--<p>Listado de ventas</p>-->
                            <table id="table" class="table table-responsive-sm" style="width:100%">
                                <thead>
                                    <tr class="bg-primary">
                                        <th scope="col">Fecha Creación</th>
                                        <th scope="col">Tipo Gasto</th>
                                        <th scope="col">Motivo</th>
                                        <th scope="col">Monto</th>
                                        <th scope="col">Observación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reporteFinancieroGastos as $reporteFinancieroGasto)
                                        <tr>
                                            <td>{{ $reporteFinancieroGasto->FechaCreacion }}</td>
                                            <td>
                                                @if ($reporteFinancieroGasto->TipoGasto == 1)
                                                    Fijo
                                                @else
                                                    Variable
                                                @endif
                                            </td>
                                            <td>{{ $reporteFinancieroGasto->Descripcion }}</td>
                                            <td>{{ $reporteFinancieroGasto->Monto }}</td>
                                            <td>{{ $reporteFinancieroGasto->Observacion }}</td>
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
        </div>
    </div>

    <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="text-success">Reporte Financieros de Gastos</h6>
                </div>
                <div class="modal-body form-material">
                    <div>
                        <label class="fs-14 negrita">Reporte del Mes</label>
                        <p class="fs-15negrita">Se mostraran solo los gastos de este mes....... Si desea ver gastos
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
    {{-- FIN --}}

    <script>
        var gastoAlquilerFijo = <?php echo json_encode($gastoAlquilerFijo); ?>;
        var gastoLuzFijo = <?php echo json_encode($gastoLuzFijo); ?>;
        var gastoAguaFijo = <?php echo json_encode($gastoAguaFijo); ?>;
        var gastoInternetFijo = <?php echo json_encode($gastoInternetFijo); ?>;
        var gastoCelular = <?php echo json_encode($gastoCelular); ?>;
        var gastoMaterialesDeOficina = <?php echo json_encode($gastoMaterialesDeOficina); ?>;
        var gastoContabilidadFijo = <?php echo json_encode($gastoContabilidadFijo); ?>;
        var gastoSalarioFijo = <?php echo json_encode($gastoSalarioFijo); ?>;
        var gastoBancosFijo = <?php echo json_encode($gastoBancosFijo); ?>;
        var gastoMarketingFijo = <?php echo json_encode($gastoMarketingFijo); ?>;
        var nombreOtrosGastosFijos = <?php echo json_encode($nombreOtrosGastosFijos); ?>;

        var totalGastoAlquilerFijo = <?php echo json_encode($totalGastoAlquilerFijo); ?>;
        var totalGastoLuzFijo = <?php echo json_encode($totalGastoLuzFijo); ?>;
        var totalGastoAguaFijo = <?php echo json_encode($totalGastoAguaFijo); ?>;
        var totalGastoInternetFijo = <?php echo json_encode($totalGastoInternetFijo); ?>;
        var totalGastoCelular = <?php echo json_encode($totalGastoCelular); ?>;
        var totalGastoMaterialesDeOficina = <?php echo json_encode($totalGastoMaterialesDeOficina); ?>;
        var totalGastoContabilidadFijo = <?php echo json_encode($totalGastoContabilidadFijo); ?>;
        var totalGastoSalarioFijo = <?php echo json_encode($totalGastoSalarioFijo); ?>;
        var totalGastoBancosFijo = <?php echo json_encode($totalGastoBancosFijo); ?>;
        var totalGastoMarketingFijo = <?php echo json_encode($totalGastoMarketingFijo); ?>;
        var totalGastosOtrosFijos = <?php echo json_encode($totalGastosOtrosFijos); ?>;
        var options = {
            series: [{
                data: [{
                        x: gastoAlquilerFijo,
                        y: totalGastoAlquilerFijo
                    },
                    {
                        x: gastoLuzFijo,
                        y: totalGastoLuzFijo
                    },
                    {
                        x: gastoAguaFijo,
                        y: totalGastoAguaFijo
                    },
                    {
                        x: gastoInternetFijo,
                        y: totalGastoInternetFijo
                    },
                    {
                        x: gastoCelular,
                        y: totalGastoCelular
                    },
                    {
                        x: gastoMaterialesDeOficina,
                        y: totalGastoMaterialesDeOficina
                    },
                    {
                        x: gastoContabilidadFijo,
                        y: totalGastoContabilidadFijo
                    },
                    {
                        x: gastoSalarioFijo,
                        y: totalGastoSalarioFijo
                    },
                    {
                        x: gastoBancosFijo,
                        y: totalGastoBancosFijo
                    },
                    {
                        x: gastoMarketingFijo,
                        y: totalGastoMarketingFijo
                    },
                    {
                        x: nombreOtrosGastosFijos,
                        y: totalGastosOtrosFijos
                    }
                ]
            }],
            colors: [
                "#17BFF0"
            ],
            legend: {
                show: false
            },
            dataLabels: {
                style: {
                    colors: ['#333']
                }
            },
            chart: {
                height: 350,
                type: 'treemap',
                foreColor: '#3333'
            }
        };
        var chart = new ApexCharts(document.querySelector("#graficoGastosFijos"), options);
        chart.render();
    </script>

    <script>
        var gastoComisionesVariable = <?php echo json_encode($gastoComisionesVariables); ?>;
        var gastoImpuestoVariable = <?php echo json_encode($gastoImpuestoVariable); ?>;
        var gastoCombustibleVariable = <?php echo json_encode($gastoCombustibleVariable); ?>;
        var gastoMovilidadVariable = <?php echo json_encode($gastoMovilidadVariable); ?>;
        var gastoProveedoresVariable = <?php echo json_encode($gastoProveedoresVariable); ?>;
        var gastoViaticoVariable = <?php echo json_encode($gastoViaticoVariable); ?>;
        var gastoMiscelaneoVariable = <?php echo json_encode($gastoMiscelaneoVariable); ?>;
        var nombreOtrosGastosVariables = <?php echo json_encode($nombreOtrosGastosVariables); ?>;

        var totalGastoComisionesVariable = <?php echo json_encode($totalGastoComisionesVariables); ?>;
        var totalGastoImpuestoVariable = <?php echo json_encode($totalGastoImpuestoVariable); ?>;
        var totalGastoCombustibleVariable = <?php echo json_encode($totalGastoCombustibleVariable); ?>;
        var totalGastoMovilidadVariable = <?php echo json_encode($totalGastoMovilidadVariable); ?>;
        var totalGastoProveedoresVariable = <?php echo json_encode($totalGastoProveedoresVariable); ?>;
        var totalGastoViaticoVariable = <?php echo json_encode($totalGastoViaticoVariable); ?>;
        var totalGastoMiscelaneoVariable = <?php echo json_encode($totalGastoMiscelaneoVariable); ?>;
        var totalGastosOtrosVariable = <?php echo json_encode($totalGastosOtrosVariable); ?>;
        var options = {
            series: [{
                data: [{
                        x: gastoComisionesVariable,
                        y: totalGastoComisionesVariable
                    },
                    {
                        x: gastoImpuestoVariable,
                        y: totalGastoImpuestoVariable
                    },
                    {
                        x: gastoCombustibleVariable,
                        y: totalGastoCombustibleVariable
                    },
                    {
                        x: gastoMovilidadVariable,
                        y: totalGastoMovilidadVariable
                    },
                    {
                        x: gastoProveedoresVariable,
                        y: totalGastoProveedoresVariable
                    },
                    {
                        x: gastoViaticoVariable,
                        y: totalGastoViaticoVariable
                    },
                    {
                        x: gastoMiscelaneoVariable,
                        y: totalGastoMiscelaneoVariable
                    },
                    {
                        x: nombreOtrosGastosVariables,
                        y: totalGastosOtrosVariable
                    }
                ]
            }],
            colors: [
                "#00E396"
            ],
            legend: {
                show: false
            },
            dataLabels: {
                style: {
                    colors: ['#333']
                }
            },
            chart: {
                height: 350,
                type: 'treemap'
            }
        };
        var chart = new ApexCharts(document.querySelector("#graficoGastosVariables"), options);
        chart.render();
    </script>

    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [<?= implode(',', $graftipo) ?>],
                datasets: [{
                    label: 'Gastos',
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
            var bandModal = <?php echo json_encode($tipo); ?>;

            if (bandModal == '') {
                $("#mostrarmodal").modal("show");
            }
            var tipoGasto = <?php echo json_encode($tipoGasto); ?>;
            $('#tipoGasto option[value=' + tipoGasto + ']').prop('selected', true);
            $('#tipoMoneda option[value=' + @json($tipoMoneda) + ']').prop('selected', true);
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
