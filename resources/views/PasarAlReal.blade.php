@extends('layouts.app')
@section('title', 'Reporte Vehicular - Placas')
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

        {!! Form::open([
            'url' => '/vehicular/reportes/placa',
            'id' => 'formObtenerDatos',
            'method' => 'POST',
            'files' => true,
        ]) !!}
        {{ csrf_field() }}
        <div class="row ">
            <div class="col-md-4 mt-4 order-md-0">
                <div class="form-group form-material">
                    <label>Seleccione Placa</label>
                    <select class="m-b-10 form-control select2-hidden-accessible" id="placa" name="placa"
                        data-placeholder="Seleccione placa" data-toggle="select2" tabindex="-1" aria-hidden="true">
                        <option value="0" class="text-danger">Seleccione Placa</option>
                        @foreach ($placas as $placa)
                            @if ($inputPlaca == $placa->PlacaVehiculo)
                                <option value="{{ $placa->PlacaVehiculo }}" selected>{{ $placa->PlacaVehiculo }}</option>
                            @else
                                <option value="{{ $placa->PlacaVehiculo }}">{{ $placa->PlacaVehiculo }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4 mt-4">
                <x-selectorFiltrosFechas :soloFiltroFecha="'false'"></x-selectorFiltrosFechas>
            </div>
            <div class="col-md-4 mt-4 order-md-3 order-last">
                <div class="form-group">
                    <br>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    <a class="p-2" target="_blank"
                        href='{{ url("vehicular/reportes/excel-placa/$_inputPlaca/$fecha/$fechaInicial/$fechaFinal") }}'>
                        <span class="btn bg-excel ripple">
                            <i class="list-icon material-icons fs-20">explicit</i>XCEL
                        </span>
                    </a>
                </div>
            </div>
        </div>
        <x-inputFechasPersonalizadas :mostrarBoton="'false'"></x-inputFechasPersonalizadas>
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
                                        <th scope="col">Fecha Atención</th>
                                        <th scope="col">Producto y/o Servicios</th>
                                        <th scope="col">Documento</th>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Placa</th>
                                        <th scope="col">Marca</th>
                                        <th scope="col">Kilometraje de Ingreso</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reporteVehiculares as $reporteVehicular)
                                        <tr>
                                            <td>{{ $reporteVehicular->FechaAtencion }}</td>
                                            <td>
                                                @foreach ($reporteVehicular->Productos as $producto)
                                                    * {{ $producto->Articulo }} {{ $producto->Detalle }}
                                                    <br>
                                                @endforeach
                                            </td>
                                            <td>{{ $reporteVehicular->Documento }}</td>
                                            <td>{{ $reporteVehicular->Cliente }}</td>
                                            <td>{{ $reporteVehicular->PlacaVehiculo }}</td>
                                            <td>{{ $reporteVehicular->NombreMarca }}</td>
                                            <td>{{ $reporteVehicular->Kilometro }}</td>
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
                        <p class="fs-15negrita">Se mostraran solo las atenciones vehiculares de este mes....... Si desea
                            ver atenciones vehiculares anteriores utilize los filtros</p>
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

<!-- Etas variables son usadas en el archivo assets/js/utilidades/utilidades.js-->
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
