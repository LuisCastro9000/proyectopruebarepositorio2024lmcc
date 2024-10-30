@extends('layouts.app')
@section('title', 'Reportes Traspasos')
@section('content')
    <div class="container">
        {!! Form::open(['url' => '/reportes/almacen/traspasos', 'method' => 'POST', 'files' => true]) !!}
        {{ csrf_field() }}
        <div class="row clearfix">
            <div class="col-md-3 mt-4">
                <div class="form-group form-material">
                    <label>Seleccionar Sucursal o Almacén</label>
                    <select id="contenido" class="form-control" name="sucursal">
                        <option value="s{{ $_sucursal->IdSucursal }}">{{ $_sucursal->Nombre }}</option>
                        @foreach ($almacenes as $almacen)
                            <option value="a{{ $almacen->IdAlmacen }}">{{ $almacen->Nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3 mt-4">
                <div class="form-group form-material">
                    <label>Seleccionar Tipo</label>
                    <select id="idTipoTraspaso" class="form-control" name="tipoTraspaso">
                        @if ($tipoTraspaso == 0)
                            <option selected value="0">Todos</option>
                            <option value="1">Origen</option>
                            <option value="2">Destino</option>
                        @elseif($tipoTraspaso == 1)
                            <option value="0">Todos</option>
                            <option selected value="1">Origen</option>
                            <option value="2">Destino</option>
                        @else
                            <option value="0">Todos</option>
                            <option value="1">Origen</option>
                            <option selected value="2">Destino</option>
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-md-3 mt-4">
                <x-selectorFiltrosFechas obtenerDatos='false' class="form-material" />
            </div>
            <div class="col-md-3 mt-4 form-group align-self-end">
                <button type="submit" class="btn btn-primary">Buscar</button>
                <a class="pr-1" target="_blank"
                    href='{{ url("reportes/almacen/excel-traspasos/$sucursal/$tipoTraspaso/$fecha/$ini/$fin") }}'>
                    <span class="btn bg-excel ripple">
                        <i class="list-icon material-icons fs-20">explicit</i>xcel
                    </span>
                </a>
            </div>


        </div>
        <x-inputFechasPersonalizadas mostrarBoton='false' />
        {!! Form::close() !!}
    </div>

    <div class="container">
        <div class="widget-list">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-12 col-md-8 widget-holder widget-full-content widget-full-height">
                    <div class="widget-bg">
                        <div class="widget-heading">
                            <h5 class="widget-title">Reporte Traspasos</h5>
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
                <div class="col-md-2 ">
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
                                        <th scope="col">Fecha Traspaso</th>
                                        <th scope="col">Usuario</th>
                                        <th scope="col">Producto</th>
                                        <th scope="col">Origen</th>
                                        <th scope="col">Destino</th>
                                        <th scope="col">Cantidad Traspasada</th>
                                        <!--<th scope="col">Cantidad Entrada</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($traspasos as $traspaso)
                                        <tr>
                                            <td>{{ $traspaso->fechaTraspaso }}</td>
                                            <td>{{ $traspaso->Nombre }}</td>
                                            <td>{{ $traspaso->Producto }}</td>
                                            <td>{{ $traspaso->NombreOrigen }}</td>
                                            <td>{{ $traspaso->NombreDestino }}</td>
                                            <td>{{ $traspaso->Cantidad }}</td>
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
                labels: [<?= implode(',', $grafProductosTraspasos) ?>],
                datasets: [{
                    label: 'Traspasos',
                    data: [<?= implode(',', $grafCantidadTraspasos) ?>],
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
@stop
