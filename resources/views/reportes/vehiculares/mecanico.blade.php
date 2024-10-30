@extends('layouts.app')
@section('title', 'Reporte Vehicular - Mecánicos')
@section('content')
    <style>
        .card-bg {
            color: #1266F1;
            border-radius: 10px !important;
        }

        .card-bg--color {
            background-color: #e4e6e7;
        }

        .card_datos {
            font-size: 28px;
        }

        .z-index_Grafico {
            opacity: .99;
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
        {!! Form::open(['url' => '/reportes/vehiculares/mecanico', 'method' => 'POST', 'files' => true]) !!}
        {{ csrf_field() }}
        <div class="row clearfix">
            {{-- <div class="col-md-4 mt-4 order-md-0">
                <div class="form-group form-material">
                    <label>Mecánico / Operador</label>
                    <input id="list" type="text" list="contenido" name="mecanico" class="form-control AvenirMedium lista"
                        style="font-size:14px;" value="{{ $inputMecanico }}" />
                    <datalist id="contenido">
                        <option value="Generico"></option>
                        @foreach ($mecanicos as $mecanico)
                            <option value="{{ $mecanico->IdOperario }}"></option>
                        @endforeach
                    </datalist>
                </div>
            </div> --}}

            {{-- select --}}
            <div class="col-lg-4 mt-4 ">
                <div class="form-group form-material">
                    <label>Mecánico / Operador</label>
                    <select class="m-b-10 form-control select2-hidden-accessible" id="mecanico" name="mecanico"
                        data-placeholder="Seleccione Mecanico" data-toggle="select2" tabindex="-1" aria-hidden="true">
                        <option value="0">Seleccione Mecanico</option>
                        @if ($inputMecanico > 1 || $inputMecanico == 'Generico')
                            <option value="0">Seleccione Mecanico</option>
                        @endif
                        <option value="Generico">Generico</option>
                        @foreach ($mecanicos as $listaMecanico)
                            @if ($inputMecanico == $listaMecanico->IdOperario)
                                <option value="{{ $listaMecanico->IdOperario }}" selected>{{ $listaMecanico->Nombres }}
                                </option>
                            @else
                                <option value="{{ $listaMecanico->IdOperario }}">{{ $listaMecanico->Nombres }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            {{-- Fin --}}
            <div class="col-lg-3 mt-4 ">
                <div class="form-group form-material">
                    <label>Fecha</label>
                    <select id="idFecha" class="form-control" name="fecha">
                        <option value="0">Todo</option>
                        <option value="1">Hoy</option>
                        <option value="2">Ayer</option>
                        <option value="3">Esta semana</option>
                        <option value="4">Última semana</option>
                        <option value="5">Este mes</option>
                        <option value="6">Último mes</option>
                        <option value="7">Este año</option>
                        <option value="8">Último año</option>
                        <option value="9">Personalizar</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-2 col-sm-4 col-12 mt-0 mt-md-4 text-center">
                <br>
                <a class="" href="https://www.youtube.com/watch?v=oAkyybGwdL8" target="_blank">
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
                href='{{ url("reportes/vehiculares/excel-mecanico/$inputMecanico/$fecha/$ini/$fin") }}'>
                    <span class="btn bg-excel ripple">
                        <i class="list-icon material-icons fs-20">explicit</i>XCEL
                    </span>
                </a>
            </div>



            {{-- <div class="col-md-4 mt-2 order-md-3">
                <div class="form-group">
                    <br>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    <a class="p-5" target="_blank"
                        href='{{ url("reportes/vehiculares/excel-mecanico/$inputMecanico/$fecha/$fechaInicial/$fechaFinal") }}'>
                        <span class="btn bg-excel ripple">
                            <i class="list-icon material-icons fs-20">explicit</i>XCEL
                        </span>
                    </a>
                </div>
            </div> --}}
            <div class="col-md-4 mt-4 order-md-4">
                <div id="Inicio" class="form-group">
                    <label class="form-control-label">Desde</label>
                    <div class="input-group">
                        <input id="datepickerIni" type="text" class="form-control datepicker" name="fechaIni"
                            autocomplete="off" onkeydown="return false"
                            data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}' data-date-end-date="0d">
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-4 order-md-5">
                <div id="Final" class="form-group">
                    <label class="form-control-label">Hasta</label>
                    <div class="input-group">
                        <input id="datepickerFin" type="text" class="form-control datepicker" name="fechaFin"
                           autocomplete="off" onkeydown="return false"
                            data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}' data-date-end-date="0d">
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
    <!-- /.container -->
    <!-- =================================== -->
    <!-- Different data widgets ============ -->
    <!-- =================================== -->

    <div class="container">
        <div class="widget-list">


            {{-- NUEVOS REPORTES DE MECANICO --}}
            <div class="row mb-4">
                @if ($inputMecanico == 0)
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class=" card card-body text-center card-bg card-bg--color">
                            <span class="card_datos font-weight-bold">{{ $totalAtencionVehicular }}</span>
                            <span class=" text-dark font-weight-bold">Total de Atención Vehiculares</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class=" card card-body  text-center card-bg card-bg--color">
                            <span class="card_datos font-weight-bold">S/
                                {{ number_format($totalProductos, 2, '.', ',') }}</span>
                            <span class=" text-dark font-weight-bold">Monto total de Productos</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class=" card card-body  text-center card-bg card-bg--color">
                            <span class="card_datos font-weight-bold">S/
                                {{ number_format($totalServicios, 2, '.', ',') }}</span>
                            <span class=" text-dark font-weight-bold">Monto total de Servicios</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class=" card card-body  text-center card-bg card-bg--color">
                            <span class="card_datos font-weight-bold">S/
                                {{ number_format($sumaTotalProductosServicios, 2, '.', ',') }}</span>
                            <span class=" text-dark font-weight-bold">T. de Productos mas Servicios</span>
                        </div>
                    </div>
                    @if (count($reporteVehiculares) >= 1)
                        <div class="col-12 mt-4">
                            <div class="card card-body  text-center z-index_Grafico">
                                <h6 class="font-weight-bold">Gráfico de Atención Vehícular</h6>
                                <div id="graficoCantidadAtencionVehicular">
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="col-12 col-lg-4">
                        <div class=" card card-body  text-center card-bg card-bg--color">
                            <span class="card_datos font-weight-bold">S/
                                {{ number_format($totalProductos, 2, '.', ',') }}</span>
                            <span class=" text-dark font-weight-bold">Monto total de Productos</span>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class=" card card-body text-center card-bg card-bg--color">
                            <span class="card_datos font-weight-bold">{{ $totalAtencionVehicular }}</span>
                            <span class=" text-dark font-weight-bold">Cantidad de Atención Vehiculares</span>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class=" card card-body  text-center card-bg card-bg--color">
                            <span class="card_datos font-weight-bold">S/
                                {{ number_format($totalServicios, 2, '.', ',') }}</span>
                            <span class=" text-dark font-weight-bold">Monto total de Servicios</span>
                        </div>
                    </div>
                    @if (count($reporteVehiculares) >= 1)
                        <div class="col-12 mt-4">
                            <div class="card z-index_Grafico">
                                <div id="graficoAtencionVehicular">
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
            {{-- FIN --}}

            {{-- <div class="row">
                <div class="col-md-3"></div>
                <div class="col-12 col-md-6 widget-holder widget-full-content widget-full-height">
                    <div class="widget-bg">
                        <div class="widget-heading">
                            <h5 class="widget-title">Reporte Vehiculares</h5>
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
                                        <th scope="col">Fecha Atención</th>
                                        <th scope="col">Mecánico</th>
                                        <th scope="col">Documento</th>
                                        <th scope="col">Monto de Productos</th>
                                        <th scope="col">Monto de Servicios</th>
                                        <th scope="col">Importe Total</th>
                                        <th scope="col">Placa</th>
                                        <th scope="col">Cliente</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reporteVehiculares as $reporteVehicular)
                                        <tr>
                                            <td>{{ $reporteVehicular->FechaAtencion }}</td>
                                            <td>
                                                @if ($inputMecanico == 'Generico')
                                                    Genérico
                                                @else
                                                    {{ $reporteVehicular->Operario }}
                                                @endif
                                            </td>
                                            <td>{{ $reporteVehicular->Documento }}</td>
                                            <td>{{ number_format($reporteVehicular->MontoProducto, 2, '.', ',') }}</td>
                                            <td>{{ number_format($reporteVehicular->MontoServicio, 2, '.', ',') }}</td>
                                            <td>{{ $reporteVehicular->Total }}</td>
                                            <td>{{ $reporteVehicular->PlacaVehiculo }}</td>
                                            <td>{{ $reporteVehicular->Cliente }}</td>
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

            <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="text-success">Reporte Vehicular - Mecánicos</h6>
                        </div>
                        <div class="modal-body form-material">
                            <div>
                                <label class="fs-14 negrita">Reporte del Mes</label>
                                <p class="fs-15negrita">Se mostraran solo las atenciones vehiculares de este mes....... Si
                                    desea ver atenciones vehiculares anteriores utilize los filtros</p>
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

        @section('scripts')

            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/1.9.2/countUp.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/jquery.vmap.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/jquery.vmap.usa.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-circle-progress/1.2.2/circle-progress.min.js"></script>

            {{-- Grafico de total de productos y servicios x Mecánico --}}
            <script>
                var totalProductos = <?php echo json_encode($totalProductos); ?>;
                var totalServicios = <?php echo json_encode($totalServicios); ?>;
                var options = {
                    series: [{
                        name: 'Monto Total de Productos',
                        data: [totalProductos]
                    }, {
                        name: 'Monto Total de Servicios',
                        data: [totalServicios]
                    }],
                    chart: {
                        type: 'bar',
                        height: 350,
                        stacked: true,
                        stackType: '100%',
                    },
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
                    xaxis: {
                        categories: [<?= implode(',', $grafvehiculos) ?>],
                    },
                    fill: {
                        opacity: 1
                    },
                    legend: {
                        position: 'bottom',
                        offsetX: 0,
                        // offsetY: 50
                    },
                };

                var chart = new ApexCharts(document.querySelector("#graficoAtencionVehicular"), options);
                chart.render();
            </script>

            {{-- Grafico de Total de cantidad de atenciones vehiculares --}}
            <script>
                var options = {
                    series: [{
                        name: 'Total de Atencion Vehicular',
                        data: [<?= implode(',', $grafTotal) ?>]
                    }],
                    chart: {
                        type: 'bar',
                        height: 350,
                        //   stacked: true,
                        stackType: '100%'
                    },
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
                    xaxis: {
                        categories: [<?= implode(',', $grafvehiculos) ?>],
                    },
                    fill: {
                        opacity: 1
                    },
                    legend: {
                        position: 'right',
                        offsetX: 0,
                        offsetY: 50
                    },
                };
                var chart = new ApexCharts(document.querySelector("#graficoCantidadAtencionVehicular"), options);
                chart.render();
            </script>

            <script>
                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [<?= implode(',', $grafvehiculos) ?>],
                        datasets: [{
                            label: 'Atenciones Vehiculares',
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
                    $('#Inicio').hide();
                    $('#Final').hide();
                    var fecha = <?php echo json_encode($fecha); ?>;
                    if (fecha == '9') {
                        var fechaIni = <?php echo json_encode($fechaInicial); ?>;
                        var fechaFin = <?php echo json_encode($fechaFinal); ?>;
                        $('#Inicio').show();
                        $('#Final').show();
                        $('#datepickerIni').val(fechaIni);
                        $('#datepickerFin').val(fechaFin);
                    }
                    $('#idFecha option[value=' + fecha + ']').prop('selected', true);
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
            <script>
                $(function() {
                    $("#idFecha").on('change', function() {
                        var valor = $("#idFecha").val();
                        if (valor == "9") {
                            $('#Inicio').show();
                            $('#Final').show();
                        } else {
                            $('#Inicio').hide();
                            $('#Final').hide();
                            $('#datepickerIni').val('');
                            $('#datepickerFin').val('');
                        }
                    });
                });
            </script>
        @stop
