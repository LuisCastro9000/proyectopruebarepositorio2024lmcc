@extends('layouts.app')
@section('title', 'Reporte de productos')
@section('content')
    <style>
        .disabled-elemento {
            cursor: not-allowed;
            pointer-events: none;
            background-color: #EFF2F5;
            color: #9c9fa1;
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
        <section class="d-flex justify-content-between align-items-end my-4">
            <h6>Reporte Productos/Servicios</h6>
            <article>
                <a class="" target="_blank"
                    href='{{ url("reportes/ventas/excel-productos/$producto/$IdTipoPago/$fecha/$ini/$fin") }}'>
                    <span class="btn bg-excel ripple">
                        <i class="list-icon material-icons fs-20">explicit</i>XCEL
                    </span>
                </a>
            </article>
        </section>
        <hr>
        {!! Form::open(['url' => '/reportes/ventas/productos', 'method' => 'POST', 'files' => true]) !!}
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
                                <option value="{{ $_producto->IdArticulo }}" selected>{{ $_producto->Descripcion }}
                                </option>
                            @else
                                <option value="{{ $_producto->IdArticulo }}">{{ $_producto->Descripcion }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3 mt-4 order-md-0">
                <div class="form-group form-material">
                    <label>Servicios</label>
                    <select class="m-b-10 form-control select2-hidden-accessible" id="servicio" name="producto"
                        data-placeholder="Seleccione producto" data-toggle="select2" tabindex="-1" aria-hidden="true">
                        <option value="0">Seleccione Servicio</option>
                        @foreach ($servicios as $_servicio)
                            @if ($producto == $_servicio->IdArticulo)
                                <option value="{{ $_servicio->IdArticulo }}" selected>{{ $_servicio->Descripcion }}
                                </option>
                            @else
                                <option value="{{ $_servicio->IdArticulo }}">{{ $_servicio->Descripcion }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2 mt-4 order-md-1">
                <div class="form-group form-material">
                    <label>Tipo Pago</label>
                    <select id="tipoPago" class="form-control" name="tipoPago">
                        <option value="0">Todo</option>
                        <option value="1">Contado</option>
                        <option value="2">Crédito</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2 mt-4 order-md-2">
                <x-selectorFiltrosFechas obtenerDatos="false" />
            </div>
            <div class="col-md-1 col-4 mt-4 order-md-2">
                <div class="form-group container">
                    <br>
                    <?php $otherProd = $otherProd == '' ? 0 : $otherProd; ?>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </div>
        </div>
        <x-inputFechasPersonalizadas mostrarBoton="false" />
        {!! Form::close() !!}

        @if ($estadoTarea == 'Activado')
            {!! Form::open(['url' => 'reportes/ventas/productos/datos-correo', 'method' => 'POST', 'files' => true]) !!}
            {{ csrf_field() }}
            <div class="jumbotron">
                <div class="row">
                    <section class=" col-12 d-flex justify-content-between align-items-center flex-wrap">
                        <article>
                            <span class="fs-18">Seccion Generar Envio Automático de Correo</span>
                        </article>
                        <article>
                            @if ($checkEstado == 'Activado')
                                <div class="text-center">
                                    <label for="check">Activar envio</label><br>
                                    <label class="switch ">
                                        <input id="checkActivarEnvio" type="checkbox" name="checkActivarEnvio" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            @else
                                <div class="text-center">
                                    <label for="check">Activar envio</label><br>
                                    <label class="switch ">
                                        <input id="checkActivarEnvio" type="checkbox" name="checkActivarEnvio">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            @endif
                        </article>
                    </section>
                </div>
                <hr class="mt-2 mb-4">
                <div class="row mb-4">
                    <div class="col-12 col-md-4">
                        <div class="form-group form-material">
                            <label>Categoria</label>
                            <select class="m-b-10 form-control select2-hidden-accessible" id="categoria" name="idCategoria"
                                data-placeholder="Seleccione la Categoria" data-toggle="select2" tabindex="-1"
                                aria-hidden="true">
                                @if ($nombreCategoria != null)
                                    <option value="0">{{ $nombreCategoria }}</option>
                                @else
                                    <option value="0">Seleccione Categoria</option>
                                @endif
                                @foreach ($listaCategoria as $itemsMarca)
                                    @if ($idCategoria == $itemsMarca->IdCategoria)
                                        <option selected value="{{ $itemsMarca->IdCategoria }}">
                                            {{ $itemsMarca->Nombre }}
                                        </option>
                                    @else
                                        <option value="{{ $itemsMarca->IdCategoria }}">{{ $itemsMarca->Nombre }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if (count($resultadoFiltro) >= 1)
                        <div class="col-12  col-md-4 pt-1">
                            <div class="form-group">
                                <label for="formGroupExampleInput">Correo EXCEL</label>
                                <input value='{{ $nombreCorreoExcel }}' id="inputCorreoExcel"
                                    class="form-control disabled-elemento" name="nombreCorreoExcel" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-12  col-md-4 pt-1">
                            <div class="form-group">
                                <label for="formGroupExampleInput">Correo XML</label>
                                <input value='{{ $nombreCorreoXml }}' id="inputCorreoXml"
                                    class="form-control disabled-elemento" name="nombreCorreoXml" autocomplete="off" />
                            </div>
                        </div>
                    @else
                        <div class="col-12  col-md-4 pt-1">
                            <div class="form-group">
                                <label for="formGroupExampleInput">Correo EXCEL</label>
                                <input value='{{ $nombreCorreoExcel }}' id="inputCorreoExcel" class="form-control"
                                    name="nombreCorreoExcel" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-12  col-md-4 pt-1">
                            <div class="form-group">
                                <label for="formGroupExampleInput">Correo XML</label>
                                <input value='{{ $nombreCorreoXml }}' id="inputCorreoXml" class="form-control"
                                    name="nombreCorreoXml" autocomplete="off" />
                            </div>
                        </div>
                    @endif
                    <div class="col-12  col-md-12  mt-4 pt-1">
                        <div class="form-group d-flex justify-content-center justify-content-md-end">
                            <button id="guardarFiltros" type="submit" class="btn btn-primary mr-3"><i
                                    class="list-icon material-icons fs-20 mr-1">mail</i>Guardar Filtros</button>
                            <button id="btnActualizarFiltros" type="submit" class="btn btn-primary d-none mr-3"><i
                                    class="list-icon material-icons fs-20 mr-1">mail</i>Actualizar Filtros</button>
                            <a href="javascript:void(0);" id="btnEditarCorreo" class="btn btn-primary"><i
                                    class="list-icon material-icons mr-1">edit</i>Editar </a>
                        </div>
                    </div>
                    {{-- <div class="col-12  col-md-2  mt-4 pt-1">
                        <div class="form-group">
                            <a href="javascript:void(0);" id="btnEditarCorreo" class="btn btn-primary"><i
                                    class="list-icon material-icons mr-1">edit</i>Editar </a>
                        </div>
                    </div> --}}
                    <div class="col-12">
                        <section class="d-flex justify-content-center justify-content-md-end">
                            <span>Los envios de correos se enviaran a partir de las 10:00 PM</span>
                        </section>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        @endif
    </div>
    <!-- /.container -->
    <!-- =================================== -->
    <!-- Different data widgets ============ -->
    <!-- =================================== -->
    <div class="container">
        <div class="widget-list">
            <div class="row">
                {{-- GRAFICO PRODUCTOS --}}
                @if (count($productosGrafico) >= 1)
                    @if ($producto > 1)
                        @if ($fecha == '1' || $fecha == '2' || $fecha == '3')
                            <div class="col-md-12 col-12 ">
                                <a href="https://www.youtube.com/watch?v=WcPY5cJ2YIs" target="_blank">
                                    <span class="btn btn-autocontrol-naranja ripple text-white">
                                        Video Instructivo <i class="list-icon material-icons fs-24 color-icon">videocam</i>
                                    </span>
                                </a>
                                <br><br>
                                <div class="card">
                                    <br>
                                    <div id="graficoProductos">
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-12 col-12">
                                <a href="https://www.youtube.com/watch?v=WcPY5cJ2YIs" target="_blank">
                                    <span class="btn btn-autocontrol-naranja ripple text-white">
                                        Video Instructivo <i class="list-icon material-icons fs-24 color-icon">videocam</i>
                                    </span>
                                </a>
                                <br><br>
                                <div class="card">
                                    <br>
                                    <div id="graficoUnicoProducto">
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="col-md-12 col-12 ">
                            <a href="https://www.youtube.com/watch?v=WcPY5cJ2YIs" target="_blank">
                                <span class="btn btn-autocontrol-naranja ripple text-white">
                                    Video Instructivo <i class="list-icon material-icons fs-24 color-icon">videocam</i>
                                </span>
                            </a>
                            <br><br>
                            <div class="card">
                                <br>
                                <div id="graficoProductos">
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="col-md-12 col-12  m-auto">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>No se encontraron Datos!</strong> Por Favor aplique los filtros para realizar su
                            consulta.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
            {{-- FIN --}}

            <div class="row mt-4">
                <div class="col-md-12 widget-holder">
                    <div class="widget-bg">
                        <!-- /.widget-heading -->
                        <div class="widget-body clearfix">
                            <!--<p>Listado de ventas</p>-->
                            <table id="table" class="table table-responsive-sm" style="width:100%">
                                <thead>
                                    <tr class="bg-primary">
                                        <th scope="col">Fecha Emitida</th>
                                        <th scope="col">Producto Detalle - Precio - Cantidad</th>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Código</th>
                                        <th scope="col">Documento</th>
                                        <th scope="col">Tipo de Pago</th>
                                        <th scope="col">Tipo Venta</th>
                                        <th scope="col">Tipo Moneda</th>
                                        <th scope="col">Total Pago</th>
                                        <th scope="col">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reporteProductos as $reportProducto)
                                        <tr>
                                            <td>{{ $reportProducto->FechaCreacion }}</td>
                                            <td>
                                                @foreach ($reportProducto->Productos as $producto)
                                                    * {{ $producto->Articulo }} {{ $producto->Detalle }} -
                                                    {{ $producto->Precio }} - {{ $producto->Cantidad }}<br>
                                                @endforeach
                                            </td>
                                            <td>{{ $reportProducto->Nombres }}</td>
                                            <td>{{ $reportProducto->Serie }}-{{ $reportProducto->Numero }}
                                            </td>
                                            <td>{{ $reportProducto->Documento }}</td>
                                            @if ($reportProducto->IdTipoPago == 1)
                                                <td>Contado</td>
                                            @else
                                                <td>Crédito</td>
                                            @endif
                                            <td>{{ $reportProducto->TipoVenta == 1 ? 'Gravada' : 'Exonerada' }}
                                            </td>
                                            <td>{{ $reportProducto->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}
                                            </td>
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
    <!-- /.container -->
    <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="text-success">Reportes de Ventas - Productos</h6>
                </div>
                <div class="modal-body form-material">
                    <div>
                        <label class="fs-14 negrita">Reporte del Mes</label>
                        <p class="fs-15 negrita">Se mostraran solo las ventas de este mes....... Si desea ver
                            ventas
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/1.9.2/countUp.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/jquery.vmap.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/jquery.vmap.usa.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-circle-progress/1.2.2/circle-progress.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>
        $('#servicio').on('change', () => {
            $('#producto').attr('disabled', true);
        })
        $('#producto').on('change', () => {
            $('#servicio').prop('disabled', true);
        });
    </script>

    <script>
        $("#btnEditarCorreo").click(function() {
            $("#inputCorreoExcel").removeClass("disabled-elemento");
            $("#inputCorreoXml").removeClass("disabled-elemento");
            $("#btnActualizarFiltros").removeClass("d-none");
            $("#guardarFiltros").addClass("d-none");
        })

        $("#checkActivarEnvio").click(function() {
            if ($("#checkActivarEnvio").is(':checked')) {
                var checkActivarEnvio = "Activado";
                $.ajax({
                    type: "post",
                    url: "productos/actualizar-filtros",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'checkActivarEnvio': checkActivarEnvio,
                    },
                    success: function(data) {
                        if (data[0] == "succes") {
                            swal("Habilitado", "Se activo el envio de correo a las 10:00 PM",
                                "success");
                        }
                    }
                })
            } else {
                var checkActivarEnvio = "Desactivado";
                $.ajax({
                    type: "post",
                    url: "productos/actualizar-filtros",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'checkActivarEnvio': checkActivarEnvio,
                    },
                    success: function(data) {
                        if (data[0] == "succes") {
                            swal("Desabilitado", "No se enviaran los correos Programados", "success");
                        }
                    }
                })
            }
        })
    </script>

    <script>
        // Grafico de Un Unico Porducto
        var ArrayMes = [];
        var grafUnicoProduc = <?php echo json_encode($grafUnicoProduc); ?>;
        var arrayFechasFiltros = <?php echo json_encode($arrayFechasFiltros); ?>;

        /*var Mes = new Array("Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic");
        for(var i=0; i<countgrafProductosFiltrado; i++){
            ArrayMes.unshift(Mes[grafProductosFiltrado[i]["mes"]-1]+' '+grafProductosFiltrado[i]["anio"]);
        }*/

        var options = {
            series: [{
                name: 'Total',
                data: grafUnicoProduc,
            }],
            chart: {
                type: 'area',
                height: 350,
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: true
            },
            stroke: {
                curve: 'straight'
            },

            title: {
                text: 'REPORTE UNICO PRODUCTO',
                align: 'left'
            },
            subtitle: {
                text: 'Price Movements',
                align: 'left'
            },

            xaxis: {
                // type: 'category',
                categories: arrayFechasFiltros,
                // labels: {
                //     formatter: function(value, timestamp) {
                //         return new Date(timestamp) // The formatter function overrides format property
                //     },
                // }
            },

            yaxis: {
                opposite: false,
            },
            legend: {
                horizontalAlign: 'right',
            }


        };

        var chart = new ApexCharts(document.querySelector("#graficoUnicoProducto"), options);
        chart.render();


        var options = {
            series: [{
                data: [<?= implode(',', $grafTotal) ?>]
            }],
            chart: {
                height: 350,
                type: 'bar',
                events: {
                    click: function(chart, w, e) {
                        // console.log(chart, w, e)
                    }
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
            title: {
                text: 'REPORTE DE PRODUCTOS / SERVICIOS VENDIDOS',
                align: 'center',
                floating: true
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
                            return 'Total: '
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
                    label: 'Compras',
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
            var bandModal = <?php echo json_encode($IdTipoPago); ?>;

            if (bandModal == '') {
                $("#mostrarmodal").modal("show");
            }
            var idTipoPago = <?php echo json_encode($IdTipoPago); ?>;
            $('#tipoPago option[value=' + idTipoPago + ']').prop('selected', true);
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
@stop
