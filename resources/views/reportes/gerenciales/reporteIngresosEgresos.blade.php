@extends('layouts.app')
@section('title', 'Reportes Ingresos - Egresos')
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
            'url' => '/reportes/gerenciales/ingresos-egresos',
            'id' => 'formObtenerDatos',
            'method' => 'POST',
            'files' => true,
        ]) !!}
        {{ csrf_field() }}
        <div class="row clearfix">
            <div class="col-md-10 mt-4 order-md-1">
                <x-selectorFiltrosFechas metodoObtenerDatos='submit' />
            </div>
            <div class="col-md-2 mt-4 order-md-3 form-group align-self-end">
                <a class="" target="_blank"
                    href='{{ url("reportes/gerenciales/excel-ingresos-egresos/$fecha/$fechaIni/$fechaFin") }}'>
                    <span class="btn bg-excel ripple">
                        <i class="list-icon material-icons fs-20">explicit</i>XCEL
                    </span>
                </a>
            </div>
        </div>
        <x-inputFechasPersonalizadas />
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
                                        <th scope="col">Fecha Registro</th>
                                        <th scope="col">Usuario</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Tipo Moneda</th>
                                        <th scope="col">Monto</th>
                                        <th scope="col">Descripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ingresosEgresos as $ingresoegreso)
                                        <tr>
                                            <td>{{ $ingresoegreso->Fecha }}</td>
                                            <td>{{ $ingresoegreso->Nombre }}</td>
                                            <td>
                                                @if ($ingresoegreso->Tipo == 'I')
                                                    Ingreso
                                                @else
                                                    Egreso
                                                @endif
                                            </td>
                                            <td>
                                                @if ($ingresoegreso->IdTipoMoneda == 1)
                                                    Soles
                                                @else
                                                    Dólares
                                                @endif
                                            </td>
                                            <td>{{ $ingresoegreso->Monto }}</td>
                                            <td>{{ $ingresoegreso->Descripcion }}</td>
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
                    <h6 class="text-success">Reportes Gerencial - Ingresos y Egresos</h6>
                </div>
                <div class="modal-body form-material">
                    <div>
                        <label class="fs-14 negrita">Reporte del Mes</label>
                        <p class="fs-15negrita">Se mostraran solo los ingresos y egresos de este mes....... Si desea ver
                            ingresos y egresos anteriores utilize los filtros</p>
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
    {{-- AGREGUE EL SCRIPT PARA CARGAR EL MODAL --}}
    <script>
        var bandModal = <?php echo $fecha; ?>;

        if (bandModal == 5) {
            $("#mostrarmodal").modal("show");
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
