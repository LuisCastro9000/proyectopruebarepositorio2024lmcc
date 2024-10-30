@extends('layouts.app')
@section('title', 'Inventarios de Vehículos')
@section('content')
    <div class="container">
        {{-- <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Listado de Inventario Vehicular</h6>
            </div>
            <div class="page-title-right">
                <div class="row mr-b-50 mt-2">
                    <div class="col-12 mr-b-20 d-sm-block d-none">
                        <a href="check-in/create"><button class="btn btn-block btn-primary ripple"><i
                                    class="list-icon material-icons fs-26">add</i> Crear Inventario</button></a>
                    </div>
                    <div class="col-12 mr-b-20 d-sm-none d-block">
                        <a href="check-in/create"><button class="btn btn-block btn-primary ripple"><i
                                    class="list-icon material-icons fs-26">add</i></button></a>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="row d-flex justify-content-center justify-content-md-between my-4 flex-wrap align-items-center">
            <section>
                <h6 class="page-title-heading mr-0 mr-r-5">Listado de Inventario Vehicular</h6>
            </section>
            <section>
                <a href="check-in/create"><button class="btn btn-primary ripple"><i
                            class="list-icon material-icons fs-26">add</i> Crear Inventario</button></a>
                <a class="mr-0 mr-md-3" href="https://www.youtube.com/watch?v=DQdO7BCNq_Y&ab_channel=AutocontrolPeru"
                    target="_blank">
                    <span class="btn btn-autocontrol-naranja ripple text-white">
                        Video Instructivo <i class="list-icon material-icons fs-24 color-icon">videocam</i>
                    </span>
                </a>
            </section>
        </div>
        @if (session('status'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('status') }}
            </div>
        @endif
        <!--    /operaciones/cotizacion/--->
        {!! Form::open(['url' => '/operaciones/vehiculares/check-in-filtrar', 'method' => 'POST']) !!}
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
            <div class="col-md-1 mt-4 order-md-2">
                <div class="form-group">
                    <br>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
                <!--   <div class="form-group form-material">
                                                                                                                                                                                                    <label>Tipo Pago</label>
                                                                                                                                                                                                    <select id="tipoPago" class="form-control" name="tipoPago">
                                                                                                                                                                                                        <option value="0">Todo</option>
                                                                                                                                                                                                        <option value="1">Contado</option>
                                                                                                                                                                                                        <option value="2">Crédito</option>
                                                                                                                                                                                                    </select>
                                                                                                                                                                                                </div>-->
            </div>

            <div class="col-md-4 mt-4 order-md-2">
                <div class="form-group">
                    <br>
                    <a class="p-0" target="_blank"
                        href='{{ url("/operaciones/vehiculares/exportar-excel/$fecha/$ini/$fin") }}'>
                        <span class="btn bg-excel ripple">
                            <i class="list-icon material-icons fs-20">explicit</i>XCEL
                        </span>
                    </a>
                </div>
            </div>
            <div class="col-md-3 mt-4 order-md-3 order-last">

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
                <!--<div class="col-md-12 widget-holder">-->
                <div class="widget-bg">
                    <!--<div class="widget-heading clearfix">
                                                                                                                                                                                                        <h5>TableSaw</h5>
                                                                                                                                                                                                    </div>-->
                    <!-- /.widget-heading -->
                    <div class="widget-body clearfix">
                        <!--<p>Listado de ventas</p>-->
                        <table id="table" class="table table-responsive-sm" style="width:100%">
                            <thead>
                                <tr class="bg-primary-dark text-white">
                                    <th scope="col">Fecha Emitida</th>
                                    <th scope="col">Asesor Comercial</th>
                                    <th scope="col">Cliente</th>
                                    <th scope="col">Placa</th>
                                    <th scope="col">RUC</th>
                                    <th scope="col">Código</th>
                                    <th scope="col">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inventarios as $inventario)
                                    <tr>
                                        <td>{{ $inventario->FechaEmision }}</td>
                                        <td>{{ $inventario->Nombre }}</td>
                                        <td>{{ $inventario->RazonSocial }}</td>
                                        <td>{{ $inventario->Placa }}</td>
                                        <td>{{ $inventario->NumeroDocumento }}</td>
                                        <td>{{ $inventario->Serie }} - {{ $inventario->Correlativo }}</td>
                                        <td>
                                            <a target="_blank"
                                                href="../../operaciones/vehiculares/documento-generado/{{ $inventario->IdCheckIn }}"><i
                                                    class="list-icon material-icons">visibility</i></a>

                                            {{-- href="../../operaciones/vehiculares/editar-inventario/{{ $inventario->IdCheckIn }}" --}}
                                            <a href="javascript:void(0);" title="Editar"
                                                onclick="abrirModal({{ $inventario->IdCheckIn }})"><i
                                                    class="list-icon material-icons">edit</i></a>
                                            <a target="_blank" class="p-1"
                                                href="../../operaciones/vehiculares/documento-generado/W-{{ $inventario->IdCheckIn }}"><img
                                                    class="logo-expand" alt="" width="25"
                                                    src="{{ asset('assets/img/whatsapp.png') }}" data-toggle="modal"
                                                    data-target="#modalWhatsapp"></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.widget-body -->
                </div>
                <!-- /.widget-bg -->
                <!--</div>-->
                <!-- /.widget-holder -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.widget-list -->

    </div>
    <!-- /.container -->

    <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="text-success">Consultas de Inventarios</h6>
                </div>
                <div class="modal-body form-material">
                    <div>
                        <label class="fs-14 negrita">Inventario del Mes</label>
                        <p class="fs-15negrita">Se mostraran solo los inventarios de este mes....... Si desea ver
                            inventarios anteriores utilize los filtros</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-actions btn-list mt-3">
                        <button class="btn btn-success" type="button" data-dismiss="modal">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal comprobar Permiso --}}
    <div class="modal fade modalcomprobarPermiso" id="exampleModalCentered" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenteredLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="form-group text-center mt-3">
                    <input id="id" class="d-none" type="text" value="">
                    <label for="formGroupExampleInput">Ingrese clave de Supervisor para Proceder Editar</label>
                    <input id="password" value="" type="text" class="form-control text-center"
                        placeholder="********" style="-webkit-text-security: disc;" autocomplete="off">
                    <div id="mensaje" class="py-1">
                    </div>
                    <button id="btnComprobar" type="button" class="btn btn-primary">Comprobar Permiso</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Fin --}}
@stop

@section('scripts')
    <script>
        $(function() {
            var bandModal = <?php echo json_encode($modal); ?>;

            if (bandModal == 0) {
                $("#mostrarmodal").modal("show");
            }

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

    <script>
        function CerrarModal() {
            $("#exampleModalCentered").modal('hide');
        }

        function abrirModal($id) {
            $(".modalcomprobarPermiso").modal('show');
            $('#password').removeClass('border-danger');
            $("p").remove();
            $('#btnComprobar').val($id);
        }

        $('#btnComprobar').click(function() {
            var password = $('#password').val();
            var idCheckList = $(this).val();
            if (password !== "") {
                $.ajax({
                    type: "get",
                    url: "comprobar-permiso",
                    data: {
                        'password': password
                    },
                    success: function(data) {
                        $('p').remove();
                        if (data[0] == 'Success') {
                            CerrarModal()
                            window.location =
                                'editar-inventario/' +
                                idCheckList;
                            $('#password').val("");
                            $('#btnActualizar').removeClass('d-none');

                        } else {
                            $('#mensaje').append(
                                '<p class="text-center text-danger font-weight-bold">Error la clave no coincide</p>'
                            )
                            $('#password').val("");
                            $('#password').focus();
                            $('#password').addClass('border-danger');
                        }
                    }
                })
            } else {
                $('p').remove();
                $('#mensaje').append(
                    '<p class="text-center text-danger font-weight-bold">Por favor ingrese la clave</p>');
                $('#password').addClass('border-danger');
                $('#password').focus();
            }
        })
    </script>
@stop
