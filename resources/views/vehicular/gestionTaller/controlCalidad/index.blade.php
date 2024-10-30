@extends('layouts.app')
@section('title', 'Lista Control de Calidad')
@section('content')
    <div class="container">
        @if (session('success'))
            <section class="mt-4">
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('success') }}
                </div>
            </section>
        @endif
        <section class="mt-4 d-flex justify-content-between align-items-center">
            <h6 class="font-weight-bolder">Control de calidad Vehicular</h6>
            <article>
                <a href="https://www.youtube.com/watch?v=d0Uev-oSbuM" target="_blank">
                    <span class="btn btn-autocontrol-naranja ripple text-white">
                        Video Instructivo <i class="list-icon material-icons fs-24 color-icon">videocam</i>
                    </span>
                </a>
                <a href="{{ route('controlCalidad.create') }}"><button class="btn btn-primary">Nuevo
                        Control</button></a>
            </article>
        </section>

        {{-- <section class="d-flex justify-content-center flex-row flex-wrap mt-4 flexGap--10">
            <article>
                <span class="badge badge-success fs-14" style="width: 80px">Bajo <br> 1-30</span>
            </article>
            <article>
                <span class="badge badge-warning fs-14" style="width: 80px">Medio</span>
            </article>
            <article>
                <span class="badge badge-danger fs-14" style="width: 80px">Urgente</span>
            </article>
        </section> --}}
        <div class="row mt-4">
            <div class=" col-12 widget-holder">
                <div class="widget-bg">
                    <div class="widget-body clearfix">
                        <table id="table" class="table table-responsive-sm" style="width:100%">
                            <thead>
                                <tr class="bg-primary">
                                    <th scope="col">Fecha Creación</th>
                                    <th scope="col">Usuario</th>
                                    <th scope="col">Cliente</th>
                                    <th scope="col">Placa</th>
                                    <th scope="col">Cotización</th>
                                    <th scope="col">Codigo</th>
                                    <th scope="col" class="text-center">Estado Vehículo</th>
                                    <th scope="col" class="text-center">Prioridad Atención</th>
                                    <th scope="col" class="text-center">Acciones</th>
                                    {{-- <th scope="col">Motivo</th>
                                    <th scope="col">Stock de Sistema</th>
                                    <th scope="col">Stock de Almacén</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($controlCalidad as $item)
                                    <tr>
                                        <td>{{ $item->FechaCreacion }}</td>
                                        <td>{{ $item->Nombre }}</td>
                                        <td>{{ $item->NombreCliente }}</td>
                                        <td>{{ $item->PlacaVehiculo }}</td>
                                        <td>{{ $item->CodigoCotizacion }}</td>
                                        <td>{{ $item->Serie }}-{{ $item->Numero }}</td>
                                        <td class="text-center">
                                            @if ($item->IdEstadoCotizacion == 2)
                                                <span class="font-weight-bold badge badge-warning fs-14">Vehículo <br> en
                                                    Reparación</span>
                                            @elseif ($item->IdEstadoCotizacion == 3)
                                                <span class="font-weight-bold badge badge-success fs-14">Vehículo <br>
                                                    Reparado</span>
                                            @elseif ($item->IdEstadoCotizacion == 4)
                                                <span class="font-weight-bold badge badge-success fs-14">Vehículo
                                                    Facturado</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($item->Prioridad == 'Bajo')
                                                <span class="badge badge-success fs-14">{{ $item->Prioridad }}</span>
                                            @endif
                                            @if ($item->Prioridad == 'Urgente')
                                                <span class="badge badge-danger fs-14">{{ $item->Prioridad }}</span>
                                            @endif
                                            @if ($item->Prioridad == 'Medio')
                                                <span class="badge badge-warning fs-14">{{ $item->Prioridad }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('controlCalidad.show', $item->IdControlCalidad) }}"><i
                                                    class="list-icon material-icons">visibility</i></a>
                                            <a href="#" title="Editar"><i
                                                    id="iconoEditar{{ $item->IdControlCalidad }}"
                                                    data-estado="{{ $item->IdEstadoCotizacion }}"
                                                    data-id="{{ $item->IdControlCalidad }}"
                                                    class="list-icon material-icons btnEditar">edit</i></a>
                                            @php
                                                if ($item->ImagenFirmaCliente != null) {
                                                    $imagenFirmaCliente = 'tengoFirma';
                                                } else {
                                                    $imagenFirmaCliente = '';
                                                }
                                            @endphp
                                            <a class="btnEnviarWhatsApp btnEnviarPdf{{ $item->IdControlCalidad }}"
                                                data-enlace="whatsApp" data-id="{{ $item->IdControlCalidad }}"
                                                data-celular="{{ $item->Telefono }}"
                                                data-idcliente="{{ $item->IdCliente }}"
                                                data-imagenfirma="{{ $imagenFirmaCliente }}" href=""><img
                                                    class="logo-expand" alt="" width="25"
                                                    src="{{ asset('assets/img/whatsapp.png') }}" data-toggle="modal"
                                                    data-target="#modalWhatsapp"></a>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal comprobar Permiso --}}
        @include('modal._modalValidaSupervisor')
        {{-- Fin --}}
        {{-- Modal enviar pdf x Whatsapp --}}
        <div class="modal fade" id="modalEnviarWhatsApp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    {!! Form::open([
                        // 'route' => ['imprimirControlCalidad', [1, 'whatsApp']],
                        'method' => 'Get',
                        'files' => true,
                        'target' => 'blank',
                        'class' => 'formularioEnviarWhatsApp',
                    ]) !!}
                    <div class="modal-body">
                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <div class="form-group">
                                    <section id="seccionClienteConCelular" class="d-none">
                                        <label class="fs-16" for="numeroCelular">Número de celular
                                            Registrado como teléfono de contacto del cliente</label>
                                    </section>
                                    <section id="seccionClienteSinCelular" class="d-none">
                                        <label class="fs-16" for="numeroCelular">Ingresar número de
                                            celular</label>
                                    </section>
                                    <input type="text" class="form-control text-center" name="numeroCelular"
                                        id="numeroCelular" value="">
                                </div>
                                <section id="seccionMensajeClienteConCelular" class="d-none">
                                    <p>Si aún es válido Continue caso contrario <br> vuelva a digitarlo
                                    </p>
                                </section>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        {{-- Fin --}}

        {{-- Modal Dibujar Firma --}}
        @include('modal._modalFirmaDigital')
        {{-- Fin --}}
    </div>
@stop

@section('scripts')
    <script src="{{ asset('assets/js/signature_pad.js') }}"></script>
    <script src="{{ asset('assets/js/canvas/recortarImagen.js') }}"></script>
    <script src="{{ asset('assets/js/controlCalidadVehicular/verificarFirma.js?v=' . time()) }}"></script>
    <script src="{{ asset('assets/js/respuestaAjax/respuestaSweetAlert2.js?v=' . time()) }}"></script>
    <script src="{{ asset('assets/js/firmaDigital/script.js?v=' . time()) }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Codigo para cargar poner el focus en input del modal
        $('body').on('shown.bs.modal', '.modalcomprobarPermiso', function() {
            $('input:visible:enabled:first', this).focus();
        });
        // Fin
        let idControl = '';
        document.addEventListener("click", function(event) {
            if (event.target.matches('.btnEditar')) {
                event.preventDefault();
                const estadoCotizacion = document.getElementById(event.target.id).dataset.estado;
                idControl = document.getElementById(event.target.id).dataset.id;
                if (estadoCotizacion != 4) {
                    redireccionarVistas(idControl);
                } else {
                    abriModal();
                }
            }
        });

        function abriModal() {
            $(".modalcomprobarPermiso").modal('show');
            $('#password').removeClass('border-danger');
            $("p").remove();
            $('.modalcomprobarPermiso').modal('show');
        }
    </script>
    <script>
        $('#btnComprobar').click(function() {
            var password = $('#password').val();
            if (password !== "") {
                $.ajax({
                    type: "get",
                    url: "comprobar-permiso",
                    data: {
                        'password': password
                    },
                    success: function(data) {
                        console.log(data);
                        $('p').remove();
                        if (data[0] == 'Success') {
                            // CerrarModal()
                            $(".modalcomprobarPermiso").modal('hide');
                            redireccionarVistas(idControl);
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

        function redireccionarVistas(id) {
            var url = "{{ route('controlCalidad.edit', ':id') }}";
            url = url.replace(':id', id);
            location.href = url;
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
        $(document).ready(function() {
            $('#formularioCrearFirmaDigital').submit(function(e) {
                e.preventDefault();

                let inputCodigoFirma = $('#inputCodigoFirma').val();
                let inputIdControlCalidad = $('#inputIdControl').val();
                let inputDescripcionEnlace = $('#inputDescripcionEnlace').val();
                let inputIdCliente = $('.btnEnviarWhatsApp').data('idcliente');
                $.ajax({
                    type: 'post',
                    url: 'control-calidad/ajax/guardar-firma-digital',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "inputCodigoFirma": inputCodigoFirma,
                        "inputIdCliente": inputIdCliente,
                        "inputIdControlCalidad": inputIdControlCalidad
                    },
                    success: function(data) {
                        if (data[0] == 'succes') {
                            $(".btnEnviarPdf" + inputIdControlCalidad).attr(
                                "data-contienefirma", 'si');
                            $('#modalDibujarFirma').modal('hide');
                            respuestaSuccesAjax(data[1]);
                            setTimeout(() => {
                                if (inputDescripcionEnlace == 'descargar' ||
                                    inputDescripcionEnlace == 'imprimir') {
                                    var url =
                                        "{{ route('imprimirControlCalidad', [':id', ':enlace']) }}";
                                    url = url.replace(':id', inputIdControlCalidad);
                                    url = url.replace(':enlace',
                                        inputDescripcionEnlace);
                                    return window.open(url, '_blank');
                                } else {
                                    return $('#modalEnviarWhatsApp').modal('show');
                                }
                            }, 500);
                        } else {
                            respuestaErrorAjax(data[1]);
                        }
                    }
                });
            });
        });
    </script>
@stop
