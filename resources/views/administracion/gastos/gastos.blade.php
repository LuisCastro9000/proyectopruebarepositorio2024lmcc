@extends('layouts.app')
@section('title', 'Crear Gastos')
@section('content')
    <style>
        .jumbotron {
            padding-top: 20px !important;
            padding-bottom: 5px !important;
        }

        .bg-verde {
            background: #2AB994;
            color: #FFF;
        }
    </style>
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <div class="container">
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Lista de Gastos</h6>
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
    </div>

    <div class="container">
        <div class="widget-list">
            {{-- Nuevo Codigo --}}
            <div class="jumbotron">
                <div class="row d-flex align-items-end ">
                    <section class="col-12 col-md-3  text-center">
                        <a href="{!! url('/reportes/financieros/gastos') !!}">
                            <img width="100px" src="{{ asset('/assets/img/graficoReporte.png') }}" alt=""><br>
                            <button class="btn  bg-verde ripple">Ver
                                Reporte</button></a>
                    </section>
                    <div class="col-12 col-md-9 d-flex justify-content-md-end justify-content-center flex-wrap">
                        <button type="button" class="btn btn-info btnEditarConClaveSupervisor">
                            Ingresar Clave
                        </button>
                        <a href="../administracion/crear-gastos" class="ml-3"><button
                                class="btn btn-block btn-primary ripple"><i
                                    class="list-icon material-icons fs-22">add</i>Crear
                                Gasto</button></a>

                        <button id="btnActualizar" type="button" class="btn btn-primary ml-3 d-none"
                            onclick="actualizarDatos()">
                            Guardar Cambios
                        </button>
                    </div>
                </div>
                <section class="d-flex justify-content-md-end justify-content-center mt-4">
                    <span>Antes de Editar un Gasto debe Ingresar la CLAVE SUPERVISOR</span>
                </section>

            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="table" width="100%" class="table table-responsive-xl">
                                <thead>
                                    <tr class="bg-primary">
                                        <th scope="col">FechaCreacion</th>
                                        <th scope="col">Tipo de Gasto</th>
                                        <th scope="col">Motivo</th>
                                        <th scope="col">Observacion</th>
                                        <th scope="col">Monto</th>
                                        <th scope="col">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($listaGastosUltimosTreintaDias as $gastos)
                                        <tr>
                                            <input hidden type="text" class="form-control"
                                                id="id-{{ $gastos->IdGastos }}" value="{{ $gastos->IdGastos }}">

                                            <td>{{ $gastos->FechaCreacion }}</td>
                                            <td>
                                                @if ($gastos->TipoGasto == 1)
                                                    Fijo
                                                @else
                                                    Variable
                                                @endif
                                            </td>
                                            <td>
                                                <span>{{ $gastos->Descripcion }}</span>
                                            </td>
                                            <td class="ajustar-texto">
                                                <span
                                                    id="spanObservacion-{{ $gastos->IdGastos }}">{{ $gastos->Observacion }}
                                                </span>
                                                <input hidden type="text" class="w-100"
                                                    id="inputObservacion-{{ $gastos->IdGastos }}"
                                                    value="{{ $gastos->Observacion }}">
                                            </td>
                                            <td>
                                                <span id="spanMonto-{{ $gastos->IdGastos }}">{{ $gastos->Monto }}</span>
                                                <input hidden type="text" id="inputMonto-{{ $gastos->IdGastos }}"
                                                    value="{{ $gastos->Monto }}">
                                            </td>
                                            <td align="center">
                                                <a class="fs-12  editarGasto disabled-elemento" title="Editar"
                                                    onclick="editarEgresoIngreso({{ $gastos->IdGastos }})"
                                                    href="javascript:void(0);">
                                                    <i class="list-icon material-icons" id="btnEditar">edit</i>
                                                </a>
                                                <a class="fs-12 d-none" id="cancelar-{{ $gastos->IdGastos }}"
                                                    title="Cancelar Editar"
                                                    onclick=" cancelarEdicion({{ $gastos->IdGastos }})"
                                                    href="javascript:void(0);">
                                                    <i class="list-icon material-icons" id="btnEditar">cancel</i>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <section class="ModalCrearGastos">
                <div class="modal modal-primary fade bs-modal-gasto" tabindex="-1" role="dialog"
                    aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content form-material">
                            <div class="modal-header text-inverse">
                                <h6 class="modal-title" id="mySmallModalLabel2">Crear Gastos</h6>
                            </div>
                            <div class="modal-body">
                                {!! Form::open([
                                    'url' => '/administracion/gastos',
                                    'method' => 'POST',
                                    'files' => true,
                                    'class' => 'form-material',
                                    'id' => 'myform',
                                ]) !!}
                                {{ csrf_field() }}
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <select id="tipoGasto" class="form-control" name="tipoGasto">
                                                <option value="0">-</option>
                                                <option value="1">Fijo</option>
                                                <option value="2">Variable</option>
                                            </select>
                                            <label for="sucursal">Seleccionar Tipo de Gasto</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-text"><label>Fecha</label></div>
                                                <input id="datepicker" type="date"
                                                    data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                    class="form-control" name="fecha">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <small class="text-muted"><strong>SELECCIONE ITEM</strong></small>
                                            <select id="listaGastos" class="m-b-10 form-control select2-hidden-accessible"
                                                name="listaGastos" data-placeholder="Seleccionar Opción"
                                                data-toggle="select2" tabindex="-1" aria-hidden="true">
                                            </select>
                                            <span class="text-danger font-size">{{ $errors->first('listaGastos') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <small class="text-muted"><strong>&nbsp;</strong></small>
                                            <input class="form-control" type="number" step=".01" name="monto">
                                            <label for="direccion">Monto Gasto</label>
                                            <span class="text-danger font-size">{{ $errors->first('monto') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <textarea id="observacion" class="form-control" rows="4" name="observacion"></textarea>
                                            <label>Observación</label>
                                            <span class="text-danger font-size">{{ $errors->first('observacion') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <input class="form-control" type="text" hidden name="IdOperadorUsuario"
                                    value="{{ $usuarioSelect->IdOperador }}">
                                <div class="form-actions btn-list mt-3">
                                    <button id="btnCrear" class="btn btn-primary" type="submit">Crear</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                </div>
                                {!! Form::close() !!}
                            </div>
                            {{-- <button id="btnEgreso" type="button" onclick="generarEgreso();" class="btn btn-primary"
                            data-dismiss="modal">Aceptar</button> --}}
                        </div>
                    </div>
                </div>
            </section>
            {{-- Fin --}}
            {{-- Modal comprobar Permiso --}}
            @include('modal._modalValidaSupervisor')
            {{-- Fin --}}
        </div>
        <!-- /.widget-list -->
    </div>
    <!-- /.container -->
@stop

@section('scripts')
    <script src="{{ asset('assets/js/respuestaAjax/libreriaSweetAlert1/libreriaSweetalert1.js') }}"></script>
    <script src="{{ asset('assets/js/scriptGlobal/script.js?v=' . time()) }}"></script>
    <script>
        const isValidacionClaveSupervisorSuccess = () => {
            ocultarLoader('#btnValidarClave');
            $("#modalValidarClaveSupervisor").modal('hide');
            swal("Permiso Concedido", {
                icon: "success",
                buttons: false,
                timer: 1500
            });
            $('#password').val("");
            $('.editarGasto').removeClass('disabled-elemento');
            $('#btnActualizar').removeClass('d-none');
        };
    </script>

    <script>
        function editarEgresoIngreso(id) {
            $('#spanObservacion-' + id).addClass('d-none');
            $('#spanMonto-' + id).addClass('d-none');
            $("#inputObservacion-" + id).removeAttr('hidden');
            $("#inputMonto-" + id).removeAttr('hidden');

            $("#inputObservacion-" + id).attr('name', 'Observacion[]');
            $('#inputMonto-' + id).attr('name', 'Monto[]');
            $('#id-' + id).attr('name', 'id[]');
            $('#cancelar-' + id).removeClass('d-none')
        }

        function cancelarEdicion(id) {
            $('#inputDescripcion-' + id).removeAttr('name', 'Descripcion[]');
            $('#inputMonto-' + id).removeAttr('name', 'Monto[]');
            $('#id-' + id).removeAttr('name', 'id[]');

            $('#spanObservacion-' + id).removeClass('d-none');
            $('#spanMonto-' + id).removeClass('d-none');
            $("#inputObservacion-" + id).attr('hidden', 'hidden');
            $("#inputMonto-" + id).attr('hidden', 'hidden');
            $('#cancelar-' + id).addClass('d-none')
        }

        function actualizarDatos() {

            var observaciones = $("input[name='Observacion[]']").map(function() {
                return $(this).val();
            }).get();
            var montos = $("input[name='Monto[]']").map(function() {
                return $(this).val();
            }).get();
            var Ids = $("input[name='id[]']").map(function() {
                return $(this).val();
            }).get();
            if (Ids == 0) {
                swal({
                    title: "No hay cambios?",
                    icon: "error",
                });
            } else {
                swal({
                        title: "Estas seguro de Actualizar?",
                        text: "Una vez actualizado, no podrá recuperar los datos Anteriores!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willActualizar) => {
                        if (willActualizar) {
                            $.ajax({
                                type: 'post',
                                url: 'actualizar-gasto',
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "observacion": observaciones,
                                    "monto": montos,
                                    "id": Ids
                                },
                                success: function(data) {
                                    swal({
                                            title: "Se actualizo Correctamente!",
                                            icon: "success",
                                            button: "Entendido",
                                        })
                                        .then((Entendido) => {
                                            if (Entendido) {
                                                window.location = 'gastos';
                                            }
                                        });
                                }
                            })
                        }
                    });
            }
        }
    </script>

    <script>
        $(function() {
            $(document).ready(function() {
                $('#table').DataTable({
                    responsive: true,
                    "order": [
                        [0, "desc"]
                    ],
                    searching: false,
                    bPaginate: false,
                });
            });
        });
    </script>

    {{-- <script type="text/javascript">
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
    </script> --}}
@stop
