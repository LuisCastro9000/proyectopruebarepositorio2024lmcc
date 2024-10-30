@extends('layouts.app')
@section('title', 'Guias Pendientes')
@section('content')
    <div class="container">
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Listado de Guias de Remisión Pendientes</h6>
            </div>
            <!-- /.page-title-left -->
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
        @if (session::has('succes'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{ session('succes') }}
            </div>
        @endif
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
                        {!! Form::open([
                            'url' => '/reportes/facturacion/cambiar-estado-guia-remision',
                            'method' => 'POST',
                            'class' => 'form-material',
                        ]) !!}
                        {{ csrf_field() }}

                        <div class="widget-body clearfix">
                            <section class="d-flex justify-content-end mb-4">
                                <button class="btn btn-primary" type="submit">Actualizar Estado</button>
                            </section>
                            <hr>
                            <table id="table" class="table table-responsive-sm" style="width:100%">
                                <thead>
                                    <tr class="bg-primary">
                                        <th scope="col">Fecha</th>
                                        {{-- <th scope="col">Tipo Documento</th> --}}
                                        <th scope="col">Sucursal</th>
                                        <th scope="col">Usuario</th>
                                        <th scope="col">Documento</th>
                                        <th scope="col">Codigo Sunat</th>
                                        <th scope="col" class="text-center">Estado Actual</th>
                                        <th scope="col"></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($guiasRemision as $guia)
                                        <tr id="{{ $guia->IdGuiaRemision }}" class="check">
                                            <td>{{ $guia->FechaCreacion }}</td>
                                            {{-- <td>{{ $guia->Comprobante }}</td> --}}
                                            <td>{{ $guia->Sucursal }}</td>
                                            <td>{{ $guia->Usuario }}</td>
                                            <td>{{ $guia->Serie }}-{{ $guia->Numero }}</td>
                                            <td>{{ $guia->codigoError }}</td>
                                            {{-- Nuevo codigo --}}
                                            <td>
                                                <div class="form-group form-material" style="width: 150px">
                                                    <input hidden type="text"
                                                        id="inputCodigo-{{ $guia->IdGuiaRemision }}" name="codigo[]"
                                                        value="{{ $guia->IdGuiaRemision }}" disabled />
                                                    <select class="m-b-10 form-control select2-hidden-accessible"
                                                        id="selectEstado-{{ $guia->IdGuiaRemision }}" name="estado[]"
                                                        data-placeholder="Seleccione estado" data-toggle="select2"
                                                        tabindex="-1" aria-hidden="true" disabled>
                                                        @if ($guia->Estado == 'Pendiente')
                                                            <option value="Pendiente" selected>Pendiente</option>
                                                        @else
                                                            <option value="Pendiente">Pendiente</option>
                                                        @endif

                                                        @if ($guia->Estado == 'Aceptado')
                                                            <option value="Aceptado" selected>Aceptado</option>
                                                        @else
                                                            <option value="Aceptado">Aceptado</option>
                                                        @endif
                                                        @if ($guia->Estado == 'Rechazo')
                                                            <option value="Rechazo" selected>Rechazo</option>
                                                        @else
                                                            <option value="Rechazo">Rechazo</option>
                                                        @endif

                                                    </select>
                                                </div>

                                            </td>
                                            <td>
                                                <section class="d-flex">
                                                    <input class="checkBox-{{ $guia->IdGuiaRemision }} mt-3"
                                                        type="checkbox" value="{{ $guia->IdGuiaRemision }}" />
                                                </section>
                                            </td>
                                            {{-- Fin --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {!! Form::close() !!}
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

    <script type="text/javascript">
        $(".check").click(function() {
            var id = $(this).attr("id");
            if ($('.checkBox-' + id).is(':checked')) {
                $("#selectEstado-" + id).prop('disabled', false);
                $("#inputCodigo-" + id).prop('disabled', false);
                $("#inputTipoDocumento-" + id).prop('disabled', false);
            } else {
                $("#selectEstado-" + id).prop('disabled', true);
                $("#inputCodigo-" + id).prop('disabled', true);
                $("#inputTipoDocumento-" + id).prop('disabled', true);
            }
        });
    </script>


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
