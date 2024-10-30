@extends('layouts.app')
@section('title', 'Reportes ventas por cobrar')
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
            'url' => '/reportes/pagos/compras-por-pagar',
            'id' => 'formObtenerDatos',
            'method' => 'POST',
            'files' => true,
        ]) !!}
        {{ csrf_field() }}
        <div class="row clearfix">
            <div class="col-md-4 mt-4">
                <div class="form-group form-material">
                    <label>Proveedor</label>
                    <input id="list" type="text" list="contenido" name="proveedor"
                        class="form-control AvenirMedium lista" style="font-size:14px;"
                        value="{{ $inputproveedor != 0 ? $inputproveedor : '' }}" />
                    <datalist id="contenido">
                        @foreach ($proveedores as $proveedor)
                            <option value="{{ $proveedor->Nombre }}"></option>
                        @endforeach
                    </datalist>
                </div>
            </div>
            <div class="col-md-4 mt-4">
                <x-selectorFiltrosFechas metodoObtenerDatos='submit' class='form-material' />
            </div>
            <div class="col-md-4 mt-4 form-group align-self-end">
                <button type="submit" class="btn btn-primary">Buscar</button>
                <a class="mr-3" target="_blank"
                    href='{{ url("reportes/pagos/excel-por-pagar/$inputproveedor/$fecha/$ini/$fin") }}'>
                    <span class="btn bg-excel ripple">
                        <i class="list-icon material-icons fs-20">explicit</i>Excel
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
                                        <th scope="col">Importe Inicial</th>
                                        <th scope="col">Deuda Actual</th>
                                        <th scope="col">Tiempo de Pago (Días)</th>
                                        <th scope="col">Dias transcurridos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pagos as $pago)
                                        @if ($pago->TipoEstado == 1)
                                            <tr>
                                                <td>{{ $pago->FechaCreacion }}</td>
                                                <td>{{ $pago->Proveedor }}</td>
                                                <td>{{ $pago->Serie . '-' . $pago->Numero }}</td>
                                                <td>{{ $pago->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                                                <td class="text-center">{{ $pago->Total }}</td>
                                                <td class="text-center">
                                                    {{ number_format($pago->Total - $pago->ImportePagado, 2, '.', '') }}
                                                </td>
                                                <td class="text-center">{{ $pago->Dias }}</td>
                                                <td class="text-center">{{ $pago->DiasPasados }}</td>
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
