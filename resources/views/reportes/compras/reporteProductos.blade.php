@extends('layouts.app')
@section('title', 'Reporte de productos')
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
        {!! Form::open(['url' => '/reportes/compras/productos', 'method' => 'POST', 'files' => true]) !!}
        {{ csrf_field() }}
        <div class="row clearfix">
            <div class="col-md-3 mt-4 order-md-0">
                <div class="form-group form-material">
                    <label>Producto</label>
                    <select class="m-b-10 form-control select2-hidden-accessible" id="producto" name="producto"
                        data-placeholder="Seleccione producto" data-toggle="select2" tabindex="-1" aria-hidden="true">
                        <option value="0">Seleccione Producto</option>
                        @foreach ($productos as $_producto)
                            @if ($producto == $_producto->IdArticulo)
                                <option value="{{ $_producto->IdArticulo }}" selected>{{ $_producto->Descripcion }}</option>
                            @else
                                <option value="{{ $_producto->IdArticulo }}">{{ $_producto->Descripcion }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3 mt-4 order-md-1">
                <div class="form-group form-material">
                    <label>Tipo Pago</label>
                    <select id="tipoPago" class="form-control" name="tipoPago">
                        <option value="0">Todo</option>
                        <option value="1">Contado</option>
                        <option value="2">Crédito</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 mt-4 order-md-2">
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

            <div class="col-md-3 mt-4 order-md-3 order-last">
                <div class="form-group">
                    <br>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    <a class="p-5" target="_blank"
                        href='{{ url("reportes/compras/excel-productos/$producto/$IdTipoPago/$fecha/$ini/$fin") }}'>
                        <span class="btn btn-primary ripple">
                            <i class="list-icon material-icons fs-20">explicit</i>
                        </span>
                    </a>
                </div>
            </div>
            <div class="col-md-3 mt-4 order-md-4">
                <div id="Inicio" class="form-group">
                    <label class="form-control-label">Desde</label>
                    <div class="input-group">
                        <input id="datepickerIni" type="text" class="form-control datepicker" name="fechaIni"
                            data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mt-4 order-md-5">
                <div id="Final" class="form-group">
                    <label class="form-control-label">Hasta</label>
                    <div class="input-group">
                        <input id="datepickerFin" type="text" class="form-control datepicker" name="fechaFin"
                            data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'>
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
            {{-- Grafico de compras de productos --}}
            <section class="row mb-3">
                <div class="col">
                    <div class="card">
                        <div class="d-flex justify-content-center align-items-center">
                            <div id="graficoComprasProductos">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            {{-- Fin --}}
            {{-- Grafico de reporte de precios de productos X proveedor --}}
            <section class="row mb-3">
                <div class="col">
                    <div class="card">

                        <div id="graficopreciosXproveedor">
                        </div>

                    </div>
                </div>
            </section>
            {{-- Fin --}}
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
                                        <th scope="col">Proveedor</th>
                                        {{-- Agregue una columna con el nombre documento --}}
                                        <th scope="col">Documento</th>
                                        {{-- Fin --}}
                                        <th scope="col">Producto</th>
                                        <th scope="col">Código</th>
                                        <th scope="col">Tipo de Pago</th>
                                        <th scope="col">Tipo de Compra</th>
                                        <th scope="col">Tipo Moneda</th>
                                        <th scope="col">Total Costo</th>
                                        <th scope="col">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reporteProductos as $reportProducto)
                                        <tr>
                                            <td>{{ $reportProducto->FechaCreacion }}</td>
                                            <td>{{ $reportProducto->Nombres }}</td>
                                            {{-- Agregue una columna con el nombre documento --}}
                                            <td>{{ $reportProducto->NumeroDocumento }}</td>
                                            {{-- Fin --}}
                                            <td>
                                                @foreach ($reportProducto->Productos as $producto)
                                                    * {{ $producto->Articulo }}
                                                    <br>
                                                @endforeach
                                            </td>
                                            <td>{{ $reportProducto->Serie }}-{{ $reportProducto->Numero }}</td>
                                            @if ($reportProducto->IdTipoPago == 1)
                                                <td>Contado</td>
                                            @else
                                                <td>Crédito</td>
                                            @endif
                                            <td>{{ $reportProducto->TipoCompra == 1 ? 'Gravada' : 'Exonerada' }}</td>
                                            <td>{{ $reportProducto->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                                            <td>{{ $reportProducto->Total }}</td>
                                            <td>{{ $reportProducto->Estado }}</td>
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
    <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="text-success">Reportes de Compras - Productos</h6>
                </div>
                <div class="modal-body form-material">
                    <div>
                        <label class="fs-14 negrita">Reporte del Mes</label>
                        <p class="fs-15negrita">Se mostraran solo las compras de este mes....... Si desea ver compras
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

@section('scripts')
    {{-- SCRIPT DEL NUEVO GRAFICO  REPORTE VENTAS --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    {{-- FIN --}}
    <script>
        $(function() {
            var bandModal = <?php echo json_encode($IdTipoPago); ?>;
            if (bandModal == '') {
                $("#mostrarmodal").modal("show");
            }
            $('#Inicio').hide();
            $('#Final').hide();
            var idTipoPago = <?php echo json_encode($IdTipoPago); ?>;
            var fecha = <?php echo json_encode($fecha); ?>;
            if (fecha == '9') {
                var fechaIni = <?php echo json_encode($fechaInicial); ?>;
                var fechaFin = <?php echo json_encode($fechaFinal); ?>;
                $('#Inicio').show();
                $('#Final').show();
                $('#datepickerIni').val(fechaIni);
                $('#datepickerFin').val(fechaFin);
            }
            $('#tipoPago option[value=' + idTipoPago + ']').prop('selected', true);
            $('#idFecha option[value=' + fecha + ']').prop('selected', true);

        });
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

    <script>
        var totalComprasXProveedor = <?php echo json_encode($totalComprasXProveedor); ?>;
        var nombresDePoveedoresXcompra = <?php echo json_encode($nombresDePoveedoresXcompra); ?>;
        var options = {
            series: totalComprasXProveedor,
            chart: {
                type: 'donut',
                width: 900,
                height: 250,
            },
            labels: nombresDePoveedoresXcompra,
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

        var chart = new ApexCharts(document.querySelector("#graficoComprasProductos"), options);
        chart.render();
    </script>

    <script>
        var options = {
            series: [{
                name: 'Total Cierre Caja S/.',
                data: [400, 430, 448, 470, 540, 580, 690]
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
                categories: ['South Korea', 'Canada', 'United Kingdom', 'Netherlands', 'Italy', 'France', 'Japan']
            },
            legend: {
                position: 'right',
                offsetY: 40
            },
            fill: {
                opacity: 1
            }
        };

        var chart = new ApexCharts(document.querySelector("#graficopreciosXproveedor"), options);
        chart.render();
    </script>
@stop
