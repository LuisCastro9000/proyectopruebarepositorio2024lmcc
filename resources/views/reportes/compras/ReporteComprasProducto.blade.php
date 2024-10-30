@extends('layouts.app')
@section('title', 'Reporte de productos')
@section('content')
    <style>
        .custom-control-label {
            padding-top: 5px !important;
        }

        .label-texto {
            padding-top: 4px !important;
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
        {!! Form::open(['url' => '/reportes/compras/productos', 'method' => 'POST', 'files' => true]) !!}
        {{ csrf_field() }}
        <div class="row clearfix">
            <div class="col-md-3 mt-4 order-md-0">
                <div class="form-group form-material h-100">
                    <section>
                        <label class="d-flex align-items-center justify-content-between">
                            <span class="label-texto">Producto</span>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="tipoMonedaUno" name="customRadio" value="1"
                                    class="custom-control-input" checked>
                                <label class="custom-control-label" for="tipoMonedaUno">Soles</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="tipoMonedaDos" name="customRadio" value="2"
                                    class="custom-control-input">
                                <label class="custom-control-label" for="tipoMonedaDos">Dolares</label>
                            </div>
                        </label>
                    </section>

                    <section>
                        <select class="m-b-10 form-control select2-hidden-accessible" id="listaProductos" name="producto"
                            data-toggle="select2" tabindex="-1" aria-hidden="true">
                        </select>
                    </section>
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
                <x-selectorFiltrosFechas obtenerDatos='false' clases='form-material' />
            </div>
            <div class="col-md-1 col-6 mt-4 order-md-3 col-4 text-center">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </div>

            <div class="col-md-1 col-6 mt-4 order-md-4 text-center">
                <a class="m-1" target="_blank"
                    href='{{ url("reportes/compras/descargar-excel-productos/$_producto/$IdTipoPago/$fecha/$ini/$fin") }}'>
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
            <div class="row my-4 justify-content-center">
                <section class="col-12 col-md-4">
                    <div class="card card-bg--celeste">
                        <div class="card-body text-center">
                            <span class="fs-16">Cantidad de Compras</span><br>
                            <span class="font-weight-bold fs-30">{{ $cantCompras }}</span>
                        </div>
                    </div>
                </section>
                @if ($producto != null)
                    <section class="col-12 col-md-4">
                        <div class="card card-bg--celeste">
                            <div class="card-body text-center">
                                <span class="fs-16">Promedio de Costo</span><br>
                                <span
                                    class="font-weight-bold fs-30">{{ number_format($promedioCostoCompra, 2, '.', ',') }}</span>
                            </div>
                        </div>
                    </section>
                @endif
            </div>
            {{-- Grafico de compras de productos --}}
            <section class="row mb-5">
                @if ($producto == 0)
                    {{-- Datos de productos mas comprados --}}
                    <div class="col-12">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-Soles"
                                    role="tab" aria-controls="nav-home" aria-selected="true">Reporte en Soles</a>
                                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile"
                                    role="tab" aria-controls="nav-profile" aria-selected="false">Reporte en
                                    Dólares</a>
                                <a class="ml-2" href="https://youtu.be/eiZsIDNi5Og" target="_blank">
                                    <span class="btn btn-autocontrol-naranja ripple text-white">
                                        Video Instructivo <i class="list-icon material-icons fs-24 color-icon">videocam</i>
                                    </span>
                                </a>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            {{-- Grafico en soles --}}
                            <div class="tab-pane fade show active" id="nav-Soles" role="tabpanel"
                                aria-labelledby="nav-home-tab">
                                <div class="text-center font-weight-bold">
                                    <h6>Reporte de producto más Comprados en soles</h6>
                                </div>
                                @if (count($reporteProductoMasCompradoSoles) >= 1)
                                    <div id="graficoComprasProductos">
                                    </div>
                                @else
                                    <div class="d-flex justify-content-center align-items-center">
                                        <p>No ha realizado compras en Soles</p>
                                    </div>
                                @endif
                            </div>
                            {{-- Grafico en dólares --}}
                            <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                aria-labelledby="nav-profile-tab">
                                <div class="text-center font-weight-bold">
                                    <h6>Reporte de producto más Comprados en Dólares</h6>
                                </div>
                                @if (count($reporteProductoMasCompradoEnDolares) >= 1)
                                    <div id="graficoComprasProductosEnDolares">
                                    </div>
                                @else
                                    <div class="d-flex justify-content-center align-items-center">
                                        <p>No ha realizado compras en Dólares</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- Fin --}}
                @else
                    {{-- Datos de comparacion de precios --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="text-center font-weight-bold">
                                <h6>Gráfico comparativo de precios de productos x proveedor en
                                    @if ($tipoMoneda == 1)
                                        Soles
                                </h6>
                            @else
                                Dólares</h6>
                @endif
                <p class="fs-14">{{ $nombreDelproductoComprado }}</p>
        </div>
        @if (count($reporte) >= 1)
            <div id="graficopreciosXproveedor">
            </div>
        @else
            <div class="d-flex justify-content-center align-items-center">
                <p>No ha realizado compras</p>
            </div>
        @endif
    </div>
    </div>
    </section>
    {{-- Fin --}}
    @endif

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
    {{-- SCRIPT DEL NUEVO GRAFICO  REPORTE VENTAS --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    {{-- FIN --}}
    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <script>
        $(function() {
            var bandModal = <?php echo json_encode($IdTipoPago); ?>;
            if (bandModal == '') {
                $("#mostrarmodal").modal("show");
            }
            var idTipoPago = <?php echo json_encode($IdTipoPago); ?>;
            var tipoMoneda = <?php echo json_encode($tipoMoneda); ?>;

            $('#tipoPago option[value=' + idTipoPago + ']').prop('selected', true);
            $('input[name=customRadio][value=' + tipoMoneda + ']').prop("checked", true);
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

    {{-- Grafico en soles --}}
    <script>
        var nombresDeProductosMasComprado = <?php echo json_encode($nombresDeProductosMasComprados); ?>;
        var cantidadDeProductosMasComprados = <?php echo json_encode($cantidadDeProductosMasComprados); ?>;


        var options = {
            series: [{
                name: 'Cantidad ',
                data: cantidadDeProductosMasComprados
            }],
            chart: {
                type: 'bar',
                height: 420
            },
            colors: ['#FEB019'],
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: false,
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: nombresDeProductosMasComprado,
                labels: {
                    show: false
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#graficoComprasProductos"), options);
        chart.render();
    </script>

    {{-- Grafico en Dolares --}}
    <script>
        var nombresDeProductosMasCompradoEnDolares = <?php echo json_encode($nombresDeProductosMasCompradosEnDolares); ?>;
        var cantidadDeProductosMasCompradosEnDolares = <?php echo json_encode($cantidadDeProductosMasCompradosEnDolares); ?>;


        var options = {
            series: [{
                name: 'Cantidad ',
                data: cantidadDeProductosMasCompradosEnDolares
            }],
            chart: {
                type: 'bar',
                height: 420
            },
            colors: ['#FEB019'],
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: false,
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: nombresDeProductosMasCompradoEnDolares,
                labels: {
                    show: false
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#graficoComprasProductosEnDolares"), options);
        chart.render();
    </script>

    {{-- Grafico compativo de Precios --}}
    <script>
        var nombreDeProveedor = <?php echo json_encode($datos); ?>;
        var precioCostoXproveedor = <?php echo json_encode($precioCostoXproveedor); ?>;
        var options = {
            series: [{
                name: 'Precio ',
                data: precioCostoXproveedor,
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
                categories: nombreDeProveedor,
                labels: {
                    show: false
                }
            },
            legend: {
                position: 'right',
                offsetY: 40,
            },
            fill: {
                opacity: 1
            }
        };

        var chart = new ApexCharts(document.querySelector("#graficopreciosXproveedor"), options);
        chart.render();
    </script>

    {{-- Traer datos de Productos con Ajax al select en soles y dolares --}}
    //
    <script>
        //     $('#tipoMonedaUno').click(function() {
        //         // var texto = 'input[name=customRadio][value=' + tipoMoneda + ']'
        //         var idMoneda = $('#tipoMonedaUno').val();
        //         cargarDatos(idMoneda);

        //     });

        //     function cargarDatos(idMoneda) {
        //         alert("hola soy soles" + idMoneda);

        //     }
        //
    </script>

    //
    <script>
        //     $("input[name=customRadio]").change(function() {
        //         // var idMoneda = $('input:radio[name=customRadio]').val());
        //         if ($(this).val() == 1) {
        //             cargarDatos(1);
        //         } else {
        //             cargarDatos(2);
        //         }
        //     });

        //     function cargarDatos(idMoneda) {
        //         if (idMoneda == 1) {
        //             alert("hola soy soles" + idMoneda);
        //         } else {
        //             alert("hola soy Dolares" + idMoneda);
        //         }

        //         if (idMoneda == 1) {
        //             $.ajax({
        //                 type: " get ";
        //                 url: 'verificar-producto/' + idMoneda,
        //                 data: {
        //                     'idMoneda': idMoneda
        //                 },
        //                 success: function(data) {
        //                     $('#producto option').remove();
        //                     // alert('se recogieron los datos correctamente');
        //                 };
        //             });
        //         }
        //     }
        //
    </script>

    <script>
        $(function() {
            //$('#contenidoProductos').hide();
            var productosDolares = <?php echo json_encode($productosDolares); ?>;
            var productosSoles = <?php echo json_encode($productosSoles); ?>;
            var estadoProductos = <?php echo json_encode($estadoProductos); ?>;
            if (estadoProductos == 1) {
                $('#listaProductos option').remove();
                //$('#contenidoProductos').show();
                if (productosSoles.length >= 1) {
                    $('#listaProductos').append('<option value="">Seleccione Producto</option>')
                    for (let index = 0; index <= productosSoles.length; index++) {
                        //console.log(productosDolares[index]);
                        $('#listaProductos').append('<option value="' + productosSoles[index]["IdArticulo"] +
                            '">' + productosSoles[index]["Descripcion"] + '</option>');
                    }
                }
            } else {
                $('#listaProductos option').remove();
                // $('#contenidoProductos').show();
                if (productosDolares.length >= 1) {
                    $('#listaProductos').append('<option value="">Seleccione Producto</option>')
                    for (let index = 0; index <= productosDolares.length; index++) {
                        //console.log(productosSoles[index]);
                        $('#listaProductos').append('<option value="' + productosDolares[index]["IdArticulo"] +
                            '">' +
                            productosDolares[index]["Descripcion"] + '</option>');
                    }
                }
            }
        })
    </script>

    <script>
        var productosSoles = <?php echo json_encode($productosSoles); ?>;
        var productosDolares = <?php echo json_encode($productosDolares); ?>;
        $('input[name=customRadio]').change(function() {
            //$('#contenidoSelect').remove();
            //$('#contenidoProductos').show();
            $.showLoading({
                name: 'circle-fade',
            });
            $('#listaProductos option').remove();
            $('#listaProductos').append('<option value="">Seleccione Producto</option>');
            var valor = $(this).val();
            if (valor == 1) {
                if (productosSoles.length >= 1) {
                    for (var index = 0; index < productosSoles.length; index++) {
                        //console.log(productosSoles[index]);

                        $('#listaProductos').append('<option value="' + productosSoles[index]["IdArticulo"] + '">' +
                            productosSoles[index]["Descripcion"] + '</option>');
                    }
                    $.hideLoading();
                }
            }
            if (valor == 2) {
                if (productosDolares.length >= 1) {
                    for (var index = 0; index < productosDolares.length; index++) {
                        //console.log(productosDolares[index]);

                        $('#listaProductos').append('<option value="' + productosDolares[index]["IdArticulo"] +
                            '">' +
                            productosDolares[index]["Descripcion"] + '</option>');
                    }
                    $.hideLoading();
                }
            }
        })
    </script>

    {{-- Cargar productos en el select con AJAX --}}

    {{-- <script>
        $("input[name=customRadio]").change(function() {
            $.showLoading({
                name: 'circle-fade',
            });
            if ($(this).is(':checked')) {
                var idMoneda = $(this).val();
                $.ajax({
                    type: 'get',
                    url: 'cargar-select',
                    data: {
                        'idMoneda': idMoneda
                    },
                    success: function(data) {
                        $('#producto option').remove();
                        $('#producto').append('<option value="">Seleccione Producto</option>');
                        // console.log(data[0].length);
                         console.log(data[0]);
                        if (data[0].length > 0) {
                            for (var i = 0; i < data[0].length; i++) {
                                $('#producto').append('<option value="' + data[0][i]["IdArticulo"] +
                                    '">' + data[0][
                                        i
                                    ]["Descripcion"] + '</option>');
                            }
                        }
                        $.hideLoading();
                    }
                });
            } else {
                $.hideLoading();
            }
        });


        // $("input[name=customRadio]").click(function() {
        //     var idMoneda = $(this).val();
        //     if ($(this).is(':checked')) {
        //         alert("Está activado" + idMoneda);
        //     }
        // });
    </script> --}}
@stop
