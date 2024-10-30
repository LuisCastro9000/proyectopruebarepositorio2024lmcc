@extends('layouts.app')
@section('title', 'Reporte Kardex')
@section('content')
    <style>
        .card-bg {
            background-color: #d6e9f3;
            color: #009EF7;
            border-radius: 10px;
        }

        .disabled-elemento {
            cursor: not-allowed;
            pointer-events: none;
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

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row page-title clearfix pb-4 pb-sm-0">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Kardex - movimientos</h6>
            </div>
            <!-- /.page-title-left -->
            <div class="page-title-right">
                <div class="row mr-b-50 mt-2">
                    <div class="col-12 mr-b-20 d-flex ">
                        <a class="p-1"
                            href='{{ url("reportes/almacen/excel-kardex/$prod/$marca/$inputCodigoBarra/$idSucursalAlmacen/$tipo/$fecha/$ini/$fin") }}'
                            target="_blank">
                            <button class="btn btn-block bg-excel ripple">
                                <i class="list-icon material-icons fs-20">explicit</i>XCEL
                            </button>
                        </a>

                        <a class="p-1 d-block d-sm-none"
                            href='{{ url("reportes/almacen/excel-kardex/$prod/$marca/$inputCodigoBarra/$idSucursalAlmacen/$tipo/$fecha/$ini/$fin") }}'
                            target="_blank">
                            <button class="btn btn-block bg-excel ripple">
                                <i class="list-icon material-icons fs-20">explicit</i>
                            </button>
                        </a>
                    </div>
                    {{-- @endif --}}
                </div>
            </div>
        </div>
        {{-- SE HA MODIFICADO EN ESTE CDIGO --}}
        <div class="row mb-4 d-block d-sm-none">
        </div>
        {!! Form::open(['url' => '/reportes/almacen/kardex', 'method' => 'POST', 'files' => true]) !!}
        {{ csrf_field() }}
        <div class="row clearfix">
            <div class="col-md-4 mt-4 order-md-1">
                <div class="form-group form-material">
                    <label>Local</label>
                    <select id="local" class="form-control" name="local">
                        <option selected value="*{{ $sucursal->IdSucursal }}">{{ $sucursal->Nombre }}</option>
                        @foreach ($almacenes as $almacen)
                            <option value="{{ $almacen->IdAlmacen }}">{{ $almacen->Nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4 mt-4 order-md-2">
                <div id="listaProducto" class="form-group form-material">
                    <label>Producto</label>
                    <select class="m-b-10 form-control select2-hidden-accessible" id="producto" name="producto"
                        data-placeholder="Seleccione el Producto" data-toggle="select2" tabindex="-1" aria-hidden="true">
                        <option value="0">Seleccione el Producto</option>
                        @foreach ($productos as $producto)
                            @if ($prod == $producto->IdArticulo)
                                <option selected value="{{ $producto->IdArticulo }}">{{ $producto->Descripcion }}
                                </option>
                            @else
                                <option value="{{ $producto->IdArticulo }}">{{ $producto->Descripcion }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4 mt-4 order-md-2">
                <div class="form-group form-material">
                    <label>Marca</label>
                    <select class="m-b-10 form-control select2-hidden-accessible" id="marca" name="marca"
                        data-placeholder="Seleccione la Marca" data-toggle="select2" tabindex="-1" aria-hidden="true">
                        <option value="0">Seleccione Marca</option>
                        @foreach ($listaMarca as $itemsMarca)
                            @if ($marca == $itemsMarca->IdMarca)
                                <option selected value="{{ $itemsMarca->IdMarca }}">{{ $itemsMarca->Marca }}</option>
                            @else
                                <option value="{{ $itemsMarca->IdMarca }}">{{ $itemsMarca->Marca }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            {{-- <div class="col-md-2 mt-4 order-md-4">
                <div class="form-group">
                    <br>
                    <a href="kardex-antiguo" class="btn btn-primary">kardex Antiguo</a>
                </div>
            </div>  --}}

            {{-- <div class="col-md-1 mt-4 order-md-3">
                <div class="form-group">
                    <br>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </div>
            <div class="col-md-2 mt-4 order-md-4">
                <div class="form-group">
                    <br>
                    <a href="kardex-antiguo" class="btn btn-primary">kardex Antiguo</a>
                </div>
            </div> --}}
            {{-- <div class="col-md-4 mt-4 order-md-4">
                <div id="Inicio" class="form-group">
                    <label class="form-control-label">Desde</label>
                    <div class="input-group">
                        <input id="datepickerIni" type="text" class="form-control datepicker" name="fechaIni"
                            data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-4 order-md-5">
                <div id="Final" class="form-group">
                    <label class="form-control-label">Hasta</label>
                    <div class="input-group">
                        <input id="datepickerFin" type="text" class="form-control datepicker" name="fechaFin"
                            data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'>
                    </div>
                </div>
            </div> --}}
        </div>

        <div class="row">
            <section class="col-md-4">
                <div class="custom-control custom-checkbox">
                    <input value="1" type="checkbox" class="custom-control-input" id="checkCodiBarra"
                        name="customcheck">
                    <label class="custom-control-label" for="checkCodiBarra">Código Producto</label>
                </div>
                <div class="form-group mt-1 inputCodigoBarra">
                    <input class="form-control disabled-elemento" id="codigoBarra" type="text" name="codigoBarra">
                </div>
            </section>

            <section class="col-md-4">
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
            </section>

            <section class="col-md-4">
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </section>
        </div>
        <div class="row">
            <section class="col-md-4 mt-4 order-md-4">
                <div id="Inicio" class="form-group">
                    <label class="form-control-label">Desde</label>
                    <div class="input-group">
                        <input id="datepickerIni" type="text" class="form-control datepicker" name="fechaIni"
                            data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}' autocomplete="off"
                            onkeydown="return false">
                    </div>
                </div>
            </section>
            <section class="col-md-4 mt-4 order-md-5">
                <div id="Final" class="form-group">
                    <label class="form-control-label">Hasta</label>
                    <div class="input-group">
                        <input id="datepickerFin" type="text" class="form-control datepicker" name="fechaFin"
                            data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}' autocomplete="off"
                            onkeydown="return false">
                    </div>
                </div>
            </section>
        </div>
        {!! Form::close() !!}
    </div>
    {{-- FIN --}}

    <!-- /.container -->
    <!-- =================================== -->
    <!-- Different data widgets ============ -->
    <!-- =================================== -->

    <div class="container">
        <div class="widget-list">
            <div class="row my-3">
                @if ($prod != 0 || $inputCodigoBarra != null)
                    <section class="col-12 col-lg-6">
                        <div class="card card-bg">
                            <div class="card-body text-center">
                                <span class="fs-16">Stock Actual del Producto</span><br>
                                <span class="font-weight-bold fs-30">{{ $stockActual }}</span>
                            </div>
                        </div>
                    </section>
                    <section class="col-12 col-lg-6">
                        <div class="card card-bg">
                            <div class="card-body text-center">
                                <span class="fs-16">Cantidad Movimientos</span><br>
                                <span class="font-weight-bold fs-30">{{ $cantidadMovimientos }}</span>
                            </div>
                        </div>
                    </section>
                @endif
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
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Usuario</th>
                                        <th scope="col">Documento</th>
                                        <th scope="col">Costo/Precio</th>
                                        <th scope="col">Entrada</th>
                                        <th scope="col">Salida</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalEntrada = 0;
                                        $totalSalida = 0;
                                    @endphp
                                    @foreach ($reporteKardex as $kardex)
                                        @if ($kardex->EstadoStock == 'E')
                                            @php $totalEntrada = $totalEntrada + $kardex->Cantidad; @endphp
                                        @else
                                            @php $totalSalida = $totalSalida + $kardex->Cantidad; @endphp
                                        @endif
                                        <tr>
                                            <td>{{ $kardex->fecha_movimiento }}</td>
                                            <td>{{ $kardex->Descripcion }}</td>
                                            <td>{{ $kardex->Nombre }}</td>
                                            <td>{{ $kardex->documento_movimiento }}</td>
                                            <td>{{ number_format($kardex->costo, 2, '.', ',') }}</td>
                                            @if ($kardex->EstadoStock == 'E')
                                                <td>{{ number_format($kardex->Cantidad, 2, '.', ',') }}</td>
                                                <td>{{ number_format(0, 2, '.', ',') }}</td>
                                            @else
                                                <td>{{ number_format(0, 2, '.', ',') }}</td>
                                                <td>{{ number_format($kardex->Cantidad, 2, '.', ',') }}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5"><strong>Totales: </strong></td>
                                        <td><strong>{{ number_format($totalEntrada, 2, '.', ',') }}</strong></td>
                                        <td><strong>{{ number_format($totalSalida, 2, '.', ',') }}</strong></td>
                                    </tr>
                                </tfoot>
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

        <div class="modal modal-primary fade bs-modal-sm-primary" tabindex="-1" role="dialog"
            aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
            <div class="modal-dialog modal-sm">
                {!! Form::open([
                    'url' => "reportes/almacen/correo-kardex/$prod/$verificarCodigo/$codigo/$fecha/$ini/$fin",
                    'method' => 'POST',
                    'files' => true,
                    'class' => 'form-material',
                ]) !!}
                <div class="modal-content">
                    <div class="modal-header text-inverse">
                        <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                        <h6 class="modal-title" id="mySmallModalLabel2">Enviar por correo</h6>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <label>Ingrese correo:</label>
                            <input id="inpCorreo" class="form-control" name="correo" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Enviar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
                {!! Form::close() !!}
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.widget-list -->
    </div>
    <!-- /.container -->
@stop

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
    <script>
        var valorCkeckCodigo = <?php echo json_encode($valorCkeckCodigo); ?>;
        $("#checkCodiBarra").click(function() {
            if ($("#checkCodiBarra").is(':checked')) {
                $("#codigoBarra").attr("autocomplete", "false");
                $("#codigoBarra").removeClass("disabled-elemento");
                $("#listaProducto").addClass("disabled-elemento");
            } else {
                $("#codigoBarra").addClass("disabled-elemento");
                $("#listaProducto").removeClass("disabled-elemento");
                $("#codigoBarra").val("");
            }

        });
        $(function() {
            if (valorCkeckCodigo == 1) {
                $("#codigoBarra").removeClass("disabled-elemento");
                $("#checkCodiBarra:not(:checked)").prop("checked", true);
                $("#listaProducto").addClass("disabled-elemento");
            }
        });
    </script>
    <script>
        $(function() {
            $('#verCodigo').hide();
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
                        emptyTable: "Ning�n dato disponible en esta tabla",
                        paginate: {
                            first: "Primero",
                            previous: "Anterior",
                            next: "Siguiente",
                            last: "�ltimo"
                        },
                        aria: {
                            sortAscending: ": Activar para ordenar la columna de manera ascendente",
                            sortDescending: ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                });
            });
        });

        function redondeo(num) {
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
    <script>
        $("#codigoProducto").click(function() {
            if ($(this).is(':checked')) {
                $('#verCodigo').show();
                $('#producto').prop('disabled', true);
            } else {
                $('#verCodigo').hide();
                $('#producto').prop('disabled', false);
            }
        });

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
