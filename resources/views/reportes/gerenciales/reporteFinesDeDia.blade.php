@extends('layouts.app')
@section('title', 'Reporte fines de día')
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
        {!! Form::open(['url' => '/reportes/gerenciales/fines-de-dia', 'method' => 'POST', 'files' => true]) !!}
        {{ csrf_field() }}
        <div class="row clearfix">
            <div class="col-md-3 mt-4 order-md-1">
                <div class="form-group form-material">
                    <label>Vendedor</label>
                    <select class="m-b-10 form-control select2-hidden-accessible" id="vendedor" name="vendedor"
                        data-placeholder="Seleccione producto" data-toggle="select2" tabindex="-1" aria-hidden="true">
                        <option value="0">Seleccione Vendedor</option>
                        @foreach ($vendedores as $vendedor)
                            @if ($inputvendedor == $vendedor->IdUsuario)
                                <option value="{{ $vendedor->IdUsuario }}" selected>{{ $vendedor->Nombre }}</option>
                            @else
                                <option value="{{ $vendedor->IdUsuario }}">{{ $vendedor->Nombre }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3 mt-4 order-md-2">
                <div class="form-group form-material">
                    <label>Tipo de Moneda</label>
                    <select class="form-control" name="tipoMoneda">
                        @if ($subniveles->contains('IdSubNivel', 46))
                            @if ($idTipoMoneda == 1)
                                <option selected value="1">Soles</option>
                                <option value="2">Dólares</option>
                            @else
                                <option value="1">Soles</option>
                                <option selected value="2">Dólares</option>
                            @endif
                        @else
                            <option selected value="1">Soles</option>
                        @endif
                    </select>
                </div>
            </div>

            <div class="col-md-3 mt-4 order-md-3">
                <x-selectorFiltrosFechas obtenerDatos='false' class="form-material" />
            </div>

            {{-- Se agrego el boton de exportar a EXCEL  y clases a los botones text-center text-md-left --}}
            <div class="col-md-1 col-6 mt-4 order-md-3">
                <div class="form-group">
                    <br>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </div>
            <div class="col-md-2 col-6 mt-4 order-md-4">
                <div class="form-group text-center text-md-left">
                    <br>
                    <a class="" target="_blank"
                        href='{{ url("reportes/gerenciales/excel-FinesDia/$inputvendedor/$idTipoMoneda/$fecha/$dateIni/$dateFin") }}'>
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
                                        <th scope="col">Vendedor</th>
                                        <th scope="col">Apert. Caja</th>
                                        <th scope="col">Cierre Caja</th>
                                        <th scope="col">Efec. Inicial</th>
                                        <th scope="col">Efec. Contado</th>
                                        <th scope="col">Efec. Cobranzas</th>
                                        <th scope="col">Efec. Amortización</th>
                                        <th scope="col">Cta. Bancaria Contado</th>
                                        <th scope="col">Cta. Bancaria Cobranzas</th>
                                        <th scope="col">Cta. Bancaria Amortización</th>
                                        <th scope="col">Ingresos</th>
                                        <th scope="col">Egresos</th>
                                        <th scope="col">Total Efec.</th>
                                        <th scope="col">Total TC</th>
                                        <th scope="col">Imprimir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ventasCaja as $ventaCaja)
                                        <tr>
                                            <td>{{ $ventaCaja->Usuario }}</td>
                                            <td>{{ $ventaCaja->FechaApertura }}</td>
                                            <td>{{ $ventaCaja->FechaCierre }}</td>
                                            <td>
                                                @if ($idTipoMoneda == 1)
                                                    {{ $ventaCaja->Inicial }}
                                                @else
                                                    {{ $ventaCaja->InicialDolares }}
                                                @endif
                                            </td>
                                            <td>{{ number_format($ventaCaja[0]->Efectivo, 2, '.', ',') }}</td>
                                            <td>{{ $ventaCaja->EfectivoCobranzas }}</td>
                                            <td>{{ $ventaCaja->MontoAmortizacion }}</td>
                                            <td>{{ number_format($ventaCaja[0]->CuentaBancaria, 2, '.', ',') }}</td>
                                            <td>{{ $ventaCaja->CuentaBancariaCobranzas }}</td>
                                            <td>{{ $ventaCaja->CuentaBancariaAmortizacion }}</td>
                                            <td>{{ $ventaCaja->MontoIngreso }}</td>
                                            <td>{{ $ventaCaja->MontoEgreso }}</td>
                                            <td>{{ $ventaCaja->TotalEfectivo }}</td>
                                            <td>{{ $ventaCaja->Totaltarjeta }}</td>
                                            <td>
                                                @if ($ventaCaja->FechaCierre)
                                                    <a class="p-1" href="#"><i
                                                            class="list-icon material-icons fs-28 icono--naranja"
                                                            onclick="abrirModalImprimir({{ $ventaCaja->IdCaja }})">print</i></a>
                                                @endif
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
    <!-- /.container -->
    <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="text-success">Reportes Gerencial - Fines de Día</h6>
                </div>
                <div class="modal-body form-material">
                    <div>
                        <label class="fs-14 negrita">Reporte del Mes</label>
                        <p class="fs-15negrita">Se mostraran solo las ventas fInes de día de este mes....... Si desea ver
                            ventas fInes de día anteriores utilize los filtros</p>
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


    {{-- Nuevo modal imprime detalle caja --}}
    <div class="modal modal-primary fade bs-modal-sm-print" id="modalImprimirDetalleCaja" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
        <div class="modal-dialog modal-sm">
            {!! Form::open([
                'url' => 'reportes/gerenciales/imprimir-detalle-caja',
                'method' => 'POST',
                'files' => true,
                'class' => 'form-material',
                'target' => '_blank',
            ]) !!}
            <div class="modal-content">
                <div class="modal-header text-inverse">
                    <h6 class="modal-title" id="mySmallModalLabel2">Imprimir comprobante</h6>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <label>Seleccionar tipo de impresión:</label>
                        <select id="selectImpre" class="form-control" name="selectImpre">
                            <option value="1">A4</option>
                            <option value="2">Ticket</option>
                        </select>
                        <input type="text" id="inputIdCaja" name="idCaja" hidden>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Imprimir</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    {{-- fin --}}

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

    <script>
        function abrirModalImprimir(idCaja) {
            $('#modalImprimirDetalleCaja').modal('show');
            $('#inputIdCaja').val(idCaja);
        }
    </script>

    <script>
        var bandModal = @json($fecha);

        if (bandModal == 5) {
            $("#mostrarmodal").modal("show");
        }
    </script>
    <script>
        $(function() {
            var idTipoPago = "";
            $('#tipoPago option[value=' + idTipoPago + ']').prop('selected', true);
        });
    </script>

    <script>
        $(function() {
            var fecha = <?php echo json_encode($fecha); ?>;
            if (fecha == null || fecha == '') {
                var today = new Date();
                var dd = today.getDate();
                var mm = today.getMonth() + 1;

                var yyyy = today.getFullYear();
                if (dd < 10) {
                    dd = '0' + dd;
                }
                if (mm < 10) {
                    mm = '0' + mm;
                }
                var today = dd + '/' + mm + '/' + yyyy;
                $("#datepicker").val(today);
            }
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
    <script type="text/javascript">
        $(function() {
            $(document).ready(function() {
                $('#table').DataTable({
                    responsive: true,
                    "order": [
                        [1, "desc"]
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
