@extends('layouts.app')
@section('title', 'Reporte de los más vendidos')
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
        {!! Form::open(['url' => '/reportes/gerenciales/mas-vendidos', 'method' => 'POST', 'files' => true]) !!}
        {{ csrf_field() }}
        <div class="row clearfix">
            <div class="col-md-4 mt-4 order-md-1">
                <div class="form-group form-material">
                    <label>Tipo Pago</label>
                    <select id="tipoPago" class="form-control" name="tipoPago">
                        <option value="0">Todo</option>
                        <option value="1">Contado</option>
                        <option value="2">Crédito</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4 mt-4 order-md-2">
                <x-selectorFiltrosFechas obtenerDatos='false' class="form-material" />
            </div>
            <div class="col-md-4 mt-4 order-md-2">
                <div class="form-group form-material">
                    <label>MOSTRAR REGISTROS</label>
                    <select id="cantRegistros" class="form-control" name="cantRegistros">
                        <option value="1000">Todo</option>
                        <option value="25">25</option>
                        <option value="100">100</option>
                        <option value="300">300</option>
                        <option value="500">500</option>
                    </select>
                </div>
            </div>
            {{-- Agregue clases a estos botones --}}
            <div
                class="col-md-12 mt-2 order-md-2 d-flex justify-content-md-end justify-content-center mt-0 mt-sm-3 flex-wrap">
                <div class="p-1">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
                <div class="p-1 ">
                    <a class="" target="_blank"
                        href='{{ url("reportes/gerenciales/mas-vendidos/excel-MasVendidos/$IdTipoPago/$fecha/$cantRegistros/$ini/$fin") }}'>
                        <span class="btn bg-excel ripple">
                            <i class="list-icon material-icons fs-20">explicit</i>XCEL
                        </span>
                    </a>
                </div>

                <div class="p-1 ">
                    <a class="btn btn-primary" target="_blank"
                        href='{{ url("reportes/gerenciales/mas-vendidos/excel-productos-no-vendidos/$fecha/$ini/$fin") }}'>
                        <span class="">
                            <i class="list-icon material-icons fs-20">explicit</i>XCEL Productos No vendidos
                        </span>
                    </a>
                </div>
            </div>
            {{-- Fin --}}
        </div>
        <x-inputFechasPersonalizadas mostrarBoton='false' />
        {!! Form::close() !!}

        @if (count($masVendidos) >= 1)
            <div class="row">
                <div class="col-md-12 col-12">
                    <div class="card">
                        <br>
                        <div id="graficoMasVendidos">
                        </div>
                    </div>
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-12 col-12">
                    <div class="card">
                        <br>
                        <div id="graficoMenosVendidos">
                        </div>
                    </div>
                </div>
            </div><br>
        @else
            <div class="col-md-10 col-12  m-auto">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>No se encontraron Datos!</strong> Por Favor aplique los filtros para realizar su
                    consulta.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div><br>
        @endif

    </div>
    <!-- /.container -->
    <!-- =================================== -->
    <!-- Different data widgets ============ -->
    <!-- =================================== -->
    <div class="container">
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
                                        <th scope="col">Producto</th>
                                        <th scope="col">Precio</th>
                                        <th scope="col">Stock</th>
                                        <th scope="col">Codigo Barra</th>
                                        <th scope="col">Tipo de Pago</th>
                                        <th scope="col">Total Ventas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($masVendidos as $masVendido)
                                        <tr>
                                            <td>{{ $masVendido->Descripcion }}</td>
                                            <td>{{ $masVendido->Precio }}</td>
                                            <td>{{ $masVendido->Stock }}</td>
                                            <td>{{ $masVendido->Codigo }}</td>
                                            @if ($masVendido->IdTipoPago == 1)
                                                <td>Contado</td>
                                            @else
                                                <td>Crédito</td>
                                            @endif
                                            <td>{{ $masVendido->Total }}</td>
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

    {{-- Agregue un modal --}}
    <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="text-success">Reportes Gerencial - Productos más vendidos</h6>
                </div>
                <div class="modal-body form-material">
                    <div>
                        <label class="fs-14 negrita">Reporte del Mes</label>
                        <p class="fs-15negrita">Se mostraran solo los productos más vendidos de este mes....... Si desea
                            ver productos más vendidos anteriores utilize los filtros</p>
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
    {{-- Fin --}}
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
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        var bandModal = <?php echo json_encode($IdTipoPago); ?>;

        if (bandModal == '') {
            $("#mostrarmodal").modal("show");
        }
    </script>
    <script>
        var $arrayDescripcion = <?php echo json_encode($arrayDescripcion); ?>;
        var arrayTotalVendidos = <?php echo json_encode($arrayTotalVendidos); ?>;
        var arrayMenosVendidos = <?php echo json_encode($arrayMenosVendidos); ?>;
        var $arrayDescripcionMenos = <?php echo json_encode($arrayDescripcionMenos); ?>;
        var options = {
            series: [{
                name: 'Total mas vendidos: ',
                data: arrayTotalVendidos,
            }],
            chart: {
                type: 'bar',
                height: 500
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                }
            },
            dataLabels: {
                enabled: true
            },
            title: {
                text: 'PRODUCTOS MAS VENDIDOS',
                align: 'center',
            },
            xaxis: {
                categories: $arrayDescripcion,
            }
        };
        var chart = new ApexCharts(document.querySelector("#graficoMasVendidos"), options);
        chart.render();

        // GRAFICO PRODUCTOS MENOS VENDIDOS
        var options = {
            series: [{
                name: 'Total menos vendidos: ',
                data: arrayMenosVendidos,
            }],
            chart: {
                type: 'bar',
                height: 500
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                }
            },
            colors: ['#30BC98'],
            dataLabels: {
                enabled: true
            },
            title: {
                text: 'PRODUCTOS MENOS VENDIDOS',
                align: 'center',
            },
            xaxis: {
                categories: $arrayDescripcionMenos,
            },
            yaxis: {
                reversed: true,
            }

        };
        var chart = new ApexCharts(document.querySelector("#graficoMenosVendidos"), options);
        chart.render();
    </script>

    <script>
        $(function() {
            var idTipoPago = <?php echo json_encode($IdTipoPago); ?>;
            var $cantRegistros = <?php echo json_encode($cantRegistros); ?>;
            $('#tipoPago option[value=' + idTipoPago + ']').prop('selected', true);
            $('#cantRegistros option[value=' + $cantRegistros + ']').prop('selected', true);
        });
    </script>
    <script type="text/javascript">
        $(function() {
            $(document).ready(function() {
                $('#table').DataTable({
                    responsive: true,
                    "order": [
                        [4, "desc"]
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
