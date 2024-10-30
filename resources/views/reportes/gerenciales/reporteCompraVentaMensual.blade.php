@extends('layouts.app')
@section('title', 'Reporte de compras y ventas')
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
        {!! Form::open(['url' => '/reportes/gerenciales/compras-ventas', 'method' => 'POST', 'files' => true]) !!}
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
            {{-- Se agrego el boton de exportar a EXCEL  y clases a los botones text-center text-md-left --}}
            <div class="col-md-1 col-6 mt-4 order-md-3">
                <div class="form-group text-center text-md-left">
                    <br>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </div>

            <div class="col-md-2 col-6  mt-4 order-md-4">
                <div class="form-group text-center text-md-left">
                    <br>
                    <a class="" target="_blank"
                        href='{{ url("reportes/comprasVentas/excel-CompraVentas/$IdTipoPago/$fecha/$ini/$fin") }}'>
                        <span class="btn bg-excel ripple">
                            <i class="list-icon material-icons fs-20">explicit</i>XCEL
                        </span>
                    </a>
                </div>
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
            <div class="row">
                <div class="col-md-12 widget-holder">
                    <div class="widget-bg">
                        <!-- /.widget-heading -->
                        <div class="widget-body clearfix">
                            <!--<p>Listado de ventas</p>-->
                            <table id="table" class="table table-responsive-sm" style="width:100%">
                                <thead>
                                    <tr class="bg-primary">
                                        <th scope="col">Comprobante</th>
                                        <th scope="col">Fecha Emitida</th>
                                        <th scope="col">Tipo Moneda</th>
                                        <th scope="col">Tipo de Pago</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($comprasVentas as $compraVenta)
                                        <tr>
                                            <td>{{ $compraVenta->Comprobante }}</td>
                                            <td>{{ $compraVenta->FechaCreacion }}</td>
                                            <td>{{ $compraVenta->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                                            <td>{{ $compraVenta->IdTipoPago == 1 ? 'Contado' : 'Crédito' }}</td>
                                            <td>{{ $compraVenta->Total }}</td>
                                            <td>{{ $compraVenta->Estado }}</td>
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
                    <h6 class="text-success">Reportes Gerencial - Compras y Ventas</h6>
                </div>
                <div class="modal-body form-material">
                    <div>
                        <label class="fs-14 negrita">Reporte del Mes</label>
                        <p class="fs-15negrita">Se mostraran solo las compras y ventas de este mes....... Si desea ver
                            compras y ventas anteriores utilize los filtros</p>
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
        var bandModal = <?php echo json_encode($IdTipoPago); ?>;

        if (bandModal == '') {
            $("#mostrarmodal").modal("show");
        }
    </script>
    {{-- FIN --}}
    <script>
        $(function() {
            var idTipoPago = <?php echo json_encode($IdTipoPago); ?>;
            $('#tipoPago option[value=' + idTipoPago + ']').prop('selected', true);
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
