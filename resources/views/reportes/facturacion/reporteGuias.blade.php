@extends('layouts.app')
@section('title', 'Reporte de clientes')
@section('content')
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
        {!! Form::open(['url' => '/reportes/facturacion/guias', 'method' => 'POST', 'files' => true]) !!}
        {{ csrf_field() }}
        <div class="row clearfix">
            <div class="col-md-6 mt-4 order-md-0">
                {{-- <div class="form-group form-material">
                    <label>Cliente</label>
                    <input id="list" type="text" list="contenido" name="cliente"
                        class="form-control AvenirMedium lista" style="font-size:14px;" value="{{ $inputcliente }}" />
                    <datalist id="contenido">
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->Nombre }}"></option>
                        @endforeach
                    </datalist>
                </div> --}}
                <label>Cliente</label>
                <select class="m-b-10 form-control select2-hidden-accessible" id="producto" name="cliente"
                    data-placeholder="Seleccione Cliente" data-toggle="select2" tabindex="-1" aria-hidden="true">
                    <option value="0">Seleccione Cliente</option>
                    @foreach ($clientes as $cliente)
                        @if ($inputcliente == $cliente->RazonSocial)
                            <option value="{{ $cliente->RazonSocial }}" selected>{{ $cliente->RazonSocial }}</option>
                        @else
                            <option value="{{ $cliente->RazonSocial }}">{{ $cliente->RazonSocial }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 order-md-2">
                <br>
                <div class="form-group form-material">
                    <label>Fecha</label>
                    <select id="idFecha" class="form-control" name="fecha">
                        <option value="1">Hoy</option>
                        <option value="2">Ayer</option>
                        <option value="3">Semana Actual</option>
                        <option value="4">Semana Anterior</option>
                        <option value="5">Mes Actual</option>
                        <option value="6">Mes Anterior</option>
                        <option value="7">Año Actual</option>
                        <option value="8">Año Anterior</option>
                        <option value="9">Personalizar</option>
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-3 mt-4 order-md-3 order-last d-flex justify-content-center">
                <section>
                    <br>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </section>
                <section>
                    <br>
                    <a class="p-3" target="_blank"
                        href='{{ url("reportes/facturacion/guias/excel-guias/$inputcliente/$IdTipoPago/$fecha/$ini/$fin") }}'>
                        <span class="btn bg-excel  ripple">
                            <i class="list-icon material-icons fs-20">explicit</i>XCEL
                        </span>
                    </a>
                </section>
                {{-- <a class="p-5" target="_blank"
                        href='{{ url("reportes/facturacion/guias/excel-guias/$inputcliente/$IdTipoPago/$fecha/$ini/$fin") }}'>
                        <span class="btn btn-primary ripple">
                            <i class="list-icon material-icons fs-20">explicit</i>
                        </span>
                    </a> --}}
            </div>
            <div class="col-md-3 mt-4 order-md-4">
                <div id="Inicio" class="form-group">
                    <label class="form-control-label">Desde</label>
                    <div class="input-group">
                        <input id="datepickerIni" type="text" class="form-control datepicker" name="fechaIni"
                            data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}' autocomplete="off"
                            onkeydown="return false" data-date-end-date="0d">
                    </div>
                </div>
            </div>
            <div class="col-md-3 mt-4 order-md-5">
                <div id="Final" class="form-group">
                    <label class="form-control-label">Hasta</label>
                    <div class="input-group">
                        <input id="datepickerFin" type="text" class="form-control datepicker" name="fechaFin"
                            data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}' autocomplete="off"
                            onkeydown="return false" data-date-end-date="0d">
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
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-12 col-md-12 widget-holder widget-full-content widget-full-height">
                    <div class="widget-bg">
                        <div class="widget-heading text-center">
                            <b class="widget-title fs-18">REPORTE DE CLIENTES</b>
                        </div>
                        <div class="widget-body">
                            {{-- <div class="" style="height: 400px">
                                <!--<canvas id="_chartJsPie"></canvas>-->
                                <canvas id="myChart" height="200"></canvas>
                            </div> --}}
                            <div class="card">
                                <br>
                                <div id="graficoProductos">
                                </div>
                            </div>
                        </div>
                        <!-- /.widget-body -->
                    </div>
                </div>
                <div class="col-md-3 ">
                </div>
            </div>

            <div class="row">

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
                                        <th scope="col">Documento Relacionado.</th>
                                        <th scope="col">Nro. Guia</th>
                                        <th scope="col">Cód. Error</th>
                                        <th scope="col">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reporteClientes as $reportCliente)
                                        <tr>
                                            <td>{{ $reportCliente->FechaEmision }}</td>
                                            <td>{{ $reportCliente->RazonSocial }}</td>
                                            <td>{{ $reportCliente->DocumentoVenta }}</td>
                                            <td>{{ $reportCliente->Serie }} - {{ $reportCliente->Numero }}</td>
                                            <td>{{ $reportCliente->codigoError == 0 ? '' : $reportCliente->codigoError }}
                                            </td>
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
@stop

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

    <script>
        var options = {
            series: [{
                data: [<?= implode(',', $grafTotal) ?>]
            }],
            chart: {
                height: 350,
                type: 'bar',
                events: {
                    click: function(chart, w, e) {}
                }
            },
            plotOptions: {
                bar: {
                    columnWidth: '80%',
                    distributed: true,
                }
            },
            dataLabels: {
                enabled: false,
                dropShadow: {
                    enabled: true
                }
            },
            legend: {
                show: false
            },

            xaxis: {
                categories: [
                    <?= implode(',', $grafCliente) ?>
                ],
                labels: {
                    show: false
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val
                    },
                    title: {
                        formatter: function(seriesName) {
                            return 'Total Guias: '
                        }
                    }
                }
            }
        };
        var chart = new ApexCharts(document.querySelector("#graficoProductos"), options);
        chart.render();
    </script>

    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [<?= implode(',', $grafCliente) ?>],
                datasets: [{
                    label: 'Guias',
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
            $('#Inicio').hide();
            $('#Final').hide();
            $('#ventasContado').hide();
            $('#ventasCredito').hide();
            $('#descuentoContado').hide();
            $('#descuentoCredito').hide();
            var idTipoPago = <?php echo json_encode($IdTipoPago); ?>;
            var fecha = <?php echo json_encode($fecha); ?>;
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
