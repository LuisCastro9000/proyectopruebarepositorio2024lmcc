@extends('layouts.app')
@section('title', 'Listar')
@section('content')
    <div class="container">
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Listado de Atenciones</h6>
            </div>
        </div>
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

        {!! Form::open(['url' => '/consultas/atenciones-vehiculares', 'method' => 'POST']) !!}
        {{ csrf_field() }}
        <div class="row clearfix">

            <div class="col-md-4 mt-4 order-md-1">
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
            <div class="col-md-3 mt-4 order-md-2 order-last">
                <div class="form-group">
                    <br>
                    <button type="submit" class="btn btn-info">Buscar</button>
                </div>
            </div>
            <div class="col-md-4 mt-4 order-md-3">

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
        <!-- /.page-title -->
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
                        <!--<div class="widget-heading clearfix">
                                                <h5>TableSaw</h5>
                                            </div>-->
                        <!-- /.widget-heading -->
                        <div class="widget-body clearfix">
                            <!--<p>Listado de ventas</p>-->
                            <table id="table" class="table table-responsive-sm" style="width:100%">
                                <thead>
                                    <tr class="bg-info">
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Placa</th>
                                        <th scope="col">Tip. Documento</th>
                                        <th scope="col">Marca</th>
                                        <th scope="col">Modelo</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vehiculos as $vehiculo)
                                        <tr>
                                            <td>{{ $vehiculo->FechaAtencion }}</td>
                                            <td>{{ $vehiculo->RazonSocial }}</td>
                                            <td>{{ $vehiculo->PlacaVehiculo }}</td>
                                            <td><a
                                                    href="../operaciones/ventas/comprobante-generado/{{ $vehiculo->IdVentas }}"><i
                                                        class="material-icons">directions</i>
                                                    {{ $vehiculo->Documento }}</a>
                                            </td>
                                            <td>{{ $vehiculo->NombreMarca }}</td>
                                            <td>{{ $vehiculo->NombreModelo }}</td>
                                            <td>{{ $vehiculo->NombreTipo }}</td>
                                            <td class="text-center">
                                                <a
                                                    href="../consultas/atenciones-vehiculares/ver-bitacora/{{ $vehiculo->IdAtencion }}"><button
                                                        class="btn btn-info"><i
                                                            class="list-icon material-icons">visibility</i></button></a>

                                            </td>
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

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!--<div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                </div>-->
                <div class="modal-body">
                    <h6 class="modal-title">Desea Eliminar Usuario?</h6>
                    <input id="idUsuario" hidden />
                </div>
                <div class="modal-footer">
                    <button id="btnEliminar" type="button" class="btn btn-primary btnEliminar">Eliminar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="text-success">Consultas de Atenciones Vehiculares</h6>
                </div>
                <div class="modal-body form-material">
                    <div>
                        <label class="fs-14 negrita">Atenciones Vehiculares del Mes</label>
                        <p class="fs-15negrita">Se mostraran solo las Atenciones Vehicularess de este mes....... Si desea
                            ver Atenciones Vehiculares anteriores utilize los filtros</p>
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
    <script type="text/javascript">
        $(function() {
            $(document).ready(function() {
                $('#table').DataTable({
                    responsive: true,
                    ordering: false,
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
                        emptyTable: "Ning?n dato disponible en esta tabla",
                        paginate: {
                            first: "Primero",
                            previous: "Anterior",
                            next: "Siguiente",
                            last: "?ltimo"
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
        function modalEliminar(id) {
            $("#idUsuario").val(id);
        }
        $(function() {
            $("#exampleModal button.btnEliminar").on("click", function(e) {
                var id = $("#idUsuario").val();
                window.location = +id + '/delete';
            });
        });
    </script>
    <script>
        $(function() {
            var bandModal = <?php echo json_encode($tipo); ?>;

            if (bandModal === '') {
                $("#mostrarmodal").modal("show");
            }
            $('#Inicio').hide();
            $('#Final').hide();
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
@stop
