@extends('layouts.app')
@section('title', 'Guía Remisión Pendientes')
@section('content')
    <div class="container">
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Listado Guías de Remisión Pendientes</h6>
            </div>
            {{-- Nuevo codigo --}}
            <div class="page-title-right">
                <div class="row mr-b-50 mt-2">
                    <div class="col-12 mr-b-20 d-none d-flex">
                        @if ($usuarioSelect->IdOperador == 1)
                            <a class="m-1" href="ver-guias-remision-pendientes"><button
                                    class="btn btn-block btn-primary ripple"><i
                                        class="list-icon material-icons fs-24">visibility</i> </button></a>
                        @endif
                    </div>
                </div>
            </div>
            {{-- fin --}}
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
        <!--{!! Form::open(['url' => '/reportes/facturacion/facturas-pendientes', 'method' => 'POST', 'files' => true]) !!}
                                {{ csrf_field() }}
                                <div class="row clearfix">
                                    <div class="col-md-4 mt-4 order-md-2">
                                        <div class="form-group form-material">
                                            <label class="form-control-label">Fecha</label>
                                            <div class="input-group">
                                                <input id="datepicker" type="text" data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}' class="form-control datepicker" name="fecha" value="{{ $fecha }}">
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><i class="list-icon material-icons">date_range</i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-4 order-md-3 order-last">
                                        <div class="form-group">
                                            <br>
                                            <button type="submit" class="btn btn-primary">Buscar</button>
                                        </div>
                                    </div>
                                </div>
                            {!! Form::close() !!}-->
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
                                    <tr class="bg-primary">
                                        <th scope="col">Fecha Emisión</th>
                                        <th scope="col">Fecha Traslado</th>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Serie - Nro Correlativo</th>
                                        <th scope="col">Documento</th>
                                        <th scope="col">Código de Error</th>
                                        <th scope="col">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($guiasRemision as $guiaRemision)
                                        <tr>
                                            <td>{{ $guiaRemision->FechaEmision }}</td>
                                            <td>{{ $guiaRemision->FechaTraslado }}</td>
                                            <td>{{ $guiaRemision->Nombres }}</td>
                                            <td>{{ $guiaRemision->Serie }} - {{ $guiaRemision->Numero }}</td>
                                            <td>{{ $guiaRemision->DocumentoVenta }}</td>
                                            <td>{{ $guiaRemision->codigoError }}</td>
                                            <td class="text-center"><a href="#" data-toggle="modal"
                                                    data-target=".bs-modal-sm-enviar" title="Enviar Sunat"
                                                    onclick="enviarSunat({{ $guiaRemision->IdGuiaRemision }})"><i
                                                        class="list-icon material-icons">send</i></a></td>
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

    <div class="modal modal-primary fade bs-modal-sm-enviar" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
        <div class="modal-dialog modal-md">
            {!! Form::open([
                'url' => '/reportes/facturacion/enviando-guias-sunat',
                'method' => 'POST',
                'files' => true,
                'class' => 'form-material',
            ]) !!}
            <div class="modal-content">
                <div class="modal-header text-inverse">
                    <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                    <h6 class="modal-title" id="mySmallModalLabel2">Enviar Comprobante</h6>
                </div>
                <div class="modal-body">
                    <label>Desea enviar guía de remisión a Sunat ?</label>
                    <input id="idDocEnvio" hidden class="form-control" name="idDocEnvio" />
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <!-- /.container -->
@stop

@section('scripts')
    <script>
        $(function() {
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
            $("#datepicker2").val(today);

        });

        function redondeo(num) {
            /*var flotante = parseFloat(numero);
            var resultado = Math.round(flotante * Math.pow(10, decimales)) / Math.pow(10, decimales);
            return resultado;*/

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

        function enviarSunat(id) {
            $('#idDocEnvio').val(id);
        }
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
