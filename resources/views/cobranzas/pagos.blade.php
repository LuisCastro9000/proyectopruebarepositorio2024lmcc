@extends('layouts.app')
@section('title', 'Pagos')
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
        {!! Form::open(['url' => '/pagos', 'method' => 'POST', 'files' => true]) !!}
        {{ csrf_field() }}
        <div class="row clearfix">
            <div class="col-md-4 mt-4 order-md-0">
                <div class="form-group form-material">
                    <label>Cliente</label>
                    <input id="list" type="text" list="contenido" name="proveedor"
                        class="form-control AvenirMedium lista" style="font-size:14px;" value="{{ $inputproveedor }}" />
                    <datalist id="contenido">
                        @foreach ($proveedores as $proveedor)
                            <option value="{{ $proveedor->Nombre }}"></option>
                        @endforeach
                    </datalist>
                </div>
            </div>
            <div class="col-md-4 mt-4 order-md-2">
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
                <div class="form-group container ">
                    <br>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </div>
            <div class="col-md-4 mt-4 order-md-4">
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
                                        <th scope="col">Documento</th>
                                        <th scope="col">Tipo Moneda</th>
                                        <th scope="col">Importe Total</th>
                                        <th scope="col">Tiempo Pago (Días)</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pagos as $pago)
                                        @if ($pago->Estado === 'Registrado')
                                            <tr>
                                                <td>{{ $pago->FechaCreacion }}</td>
                                                <td>{{ $pago->Proveedor }}</td>
                                                <td>{{ $pago->Serie . '-' . $pago->Numero }}</td>
                                                <td class="text-right">{{ $pago->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}
                                                </td>
                                                <td class="text-right">{{ $pago->Total }}</td>
                                                <td class="text-center">{{ $pago->Dias }}</td>
                                                <td>
                                                    {{ $pago->TipoEstado == 1 ? 'Pendiente' : 'Cancelado' }}
                                                </td>
                                                <td class="text-center">
                                                    <a href="detalle-pago/{{ $pago->IdCompras }}"><i
                                                            class="list-icon material-icons">visibility</i></a>
                                                </td>
                                            </tr>
                                        @endif
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
    <script>
        $(function() {
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
