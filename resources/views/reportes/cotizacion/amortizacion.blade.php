@extends('layouts.app')
@section('title', 'Reporte Cotización - Amortizaciones')
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
        {!! Form::open(['url' => '/reportes/cotizacion/amortizaciones', 'method' => 'POST', 'files' => true]) !!}
        {{ csrf_field() }}
        <div class="row clearfix">
            <div class="col-md-4 mt-3">
                <div class="form-group form-material">
                    <label>Usuario Vendedor</label>
                    <!--<select class="form-control" id="clientes" name="cliente">-->
                    <select class="m-b-10 form-control select2-hidden-accessible" id="vendedor" name="vendedor"
                        data-placeholder="Seleccione vendedor" data-toggle="select2" tabindex="-1" aria-hidden="true">
                        <option value="0">Seleccione Vendedor</option>
                        @foreach ($usuarios as $usuario)
                            @if ($vendedor == $usuario->IdUsuario)
                                <option value="{{ $usuario->IdUsuario }}" selected>{{ $usuario->Nombre }}</option>
                            @else
                                <option value="{{ $usuario->IdUsuario }}">{{ $usuario->Nombre }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

            </div>
            <div class="col-md-4 mt-3 order-md-2">
                <x-selectorFiltrosFechas obtenerDatos='false' class='form-material' />
            </div>
            <div class="col-md-4 mt-3 order-md-3 order-last">
                <div class="form-group">
                    <br>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    <a class="p-5" target="_blank"
                        href='{{ url("reportes/cotizacion/excel-amortizacion/$vendedor/$fecha/$ini/$fin") }}'>
                        <span class="btn bg-excel ripple">
                            <i class="list-icon material-icons fs-20">explicit</i>Excel
                        </span>
                    </a>
                </div>
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
            <div class="row">
                <div class="col-12 widget-holder widget-full-content widget-full-height justify-content-center">
                    <div class="widget-bg">
                        <div class="widget-heading">
                            <h5 class="widget-title">Reporte Amortizaciones</h5>
                        </div>
                        <div class="widget-body">
                            <div id="chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 widget-holder">
                    <div class="widget-bg">
                        <!-- /.widget-heading -->
                        <div class="widget-body clearfix">
                            <!--<p>Listado de ventas</p>-->
                            <table id="table" class="table table-responsive-sm" style="width:100%">
                                <thead>
                                    <tr class="bg-primary">
                                        <th scope="col">Fecha Registro</th>
                                        <th scope="col">Usuario Vendedor</th>
                                        <th scope="col">Docum. Cotizado</th>
                                        <th scope="col">Forma de Pago</th>
                                        <th scope="col">Tipo Moneda</th>
                                        <th scope="col">Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reporteAmortizaciones as $reporteAmortizacion)
                                        <tr>
                                            <td>{{ $reporteAmortizacion->FechaIngreso }}</td>
                                            <td>{{ $reporteAmortizacion->Usuario }}</td>
                                            <td>{{ $reporteAmortizacion->Serie }}-{{ $reporteAmortizacion->Numero }}</td>
                                            <td>
                                                @if ($reporteAmortizacion->FormaPago == 1)
                                                    Efectivo
                                                @elseif($reporteAmortizacion->FormaPago == 2)
                                                    POS
                                                @else
                                                    Transferencia Bancaria
                                                @endif
                                            </td>
                                            <td>{{ $reporteAmortizacion->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                                            <td>{{ $reporteAmortizacion->Monto }}</td>
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

    <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="text-success">Reporte Vehicular - Placas</h6>
                </div>
                <div class="modal-body form-material">
                    <div>
                        <label class="fs-14 negrita">Reporte del Mes</label>
                        <p class="fs-15negrita">Se mostraran solo las atenciones vehiculares de este mes....... Si desea ver
                            atenciones vehiculares anteriores utilize los filtros</p>
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
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        $(function() {
            var arrayUsuarios = <?php echo json_encode($arrayUsuarios); ?>;
            var arrayCantidad = <?php echo json_encode($arrayCantidad); ?>;
            var arrayTotal = <?php echo json_encode($arrayTotal); ?>;
            var options = {
                series: [{
                        name: 'Total Amortizado (S/)',
                        data: arrayTotal
                    },
                    {
                        name: 'Número de Amortizaciones',
                        data: arrayCantidad
                    }
                ],
                chart: {
                    type: 'bar',
                    height: 300
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: true,
                        dataLabels: {
                            position: 'top',
                        },
                    }
                },
                dataLabels: {
                    enabled: true,
                    offsetX: -6,
                    style: {
                        fontSize: '12px',
                        colors: ['#fff']
                    }
                },
                xaxis: {
                    categories: arrayUsuarios,
                    labels: {
                        formatter: function(val) {
                            return val;
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        });
    </script>

    <script>
        $(function() {
            var bandModal = <?php echo json_encode($tipo); ?>;
            if (bandModal == '') {
                $("#mostrarmodal").modal("show");
            }
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
