@extends('layouts.app')
@section('title', 'Paquetes')
@section('content')
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <div class="container">

        @if (session::has('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        {{-- Seccion Grupos --}}
        <div class="row mt-4">
            <div class="col">
                <div
                    class="d-flex justify-content-center flex-wrap align-items-center justify-content-sm-between flex-column flex-sm-row">
                    <section>
                        <div class="mb-3 mb-sm-0">
                            <h6 class="page-title-heading mr-0 mr-r-5">Listado de Paquetes Mantemiento</h6>
                        </div>
                    </section>
                    <section class="d-flex align-items-center flex-wrap justify-content-center">
                        <div class="d-md-block d-none mx-2">
                            <a href="https://www.youtube.com/watch?v=_hnim2Y-KRw&ab_channel=AutocontrolPeru"
                                target="_blank">
                                <span class="btn btn-autocontrol-naranja ripple text-white">
                                    Video Instructivo <i class="list-icon material-icons fs-24 color-icon">videocam</i>
                                </span>
                            </a>
                        </div>
                        <div class="d-md-none d-block mx-2">
                            <a href="https://www.youtube.com/watch?v=_hnim2Y-KRw&ab_channel=AutocontrolPeru"
                                target="_blank">
                                <span class="btn btn-autocontrol-naranja ripple text-white"> <i
                                        class="list-icon material-icons fs-24 color-icon">videocam</i>
                                </span>
                            </a>
                        </div>
                        <div class="d-md-block d-none mx-2">
                            <a href="../administracion/create"><button class="btn btn-block btn-primary ripple"><i
                                        class="list-icon material-icons fs-24" value="1" name='btnSoles'
                                        id="btnSoles">add</i>Crear Paquete</button></a>
                        </div>
                        <div class="d-md-none d-block mx-2">
                            <a href="../administracion/create"><button class="btn btn-block btn-primary ripple"><i
                                        class="list-icon material-icons fs-24">add</i></button></a>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#grupoSoles"
                            role="tab" aria-controls="nav-home" aria-selected="true">Paquetes en soles</a>
                        @if ($subniveles->contains('IdSubNivel', 46))
                            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#grupoDolares"
                                role="tab" aria-controls="nav-profile" aria-selected="false">Paquetes en Dolares</a>
                        @endif
                    </div>
                </nav>

                {{-- Contenedor Grupos soles --}}
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="grupoSoles" role="tabpanel" aria-labelledby="nav-home-tab">
                        <table id="tableSoles" class="table table-responsive-sm" style="width:100%">
                            <thead>
                                <tr class="bg-primary">
                                    <th scope="col">FechaCreacion</th>
                                    <th scope="col">Nombre de Paquete</th>
                                    <th scope="col" class="text-center">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($grupoSoles as $grupo)
                                    <tr>
                                        <td>{{ $grupo->FechaCreacion }}</td>
                                        <td scope="row">{{ $grupo->NombreGrupo }}</td>
                                        <td align="center">
                                            <a href="../administracion/paquetes/detalle-paquete/{{ $grupo->IdGrupo }}"
                                                class="btnDetalleGrupo" title="Ver Detalle"><i
                                                    class="list-icon material-icons">visibility</i></a>
                                            <a href="../administracion/paquetes/editar/{{ $grupo->IdGrupo }}"
                                                title="Editar"><i class="list-icon material-icons">edit</i></a>

                                            {{-- Nuevo codigo --}}
                                            {{-- href="eliminar-grupo/{{ $grupo->IdGrupo }}" --}}
                                            <a class="fs-12 " title="Eliminar" href="javascript:void(0);"
                                                id="btnGrupo-{{ $grupo->IdGrupo }}"
                                                onclick="eliminarGrupo({{ $grupo->IdGrupo }})">
                                                <i class="list-icon material-icons">cancel</i></a>
                                            {{-- Fin --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- Contenedor Grupos Dolares --}}
                    <div class="tab-pane fade" id="grupoDolares" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <table id="tableDolares" class="table table-responsive-sm" style="width:100%">
                            <thead>
                                <tr class="bg-primary">
                                    <th scope="col">FechaCreacion</th>
                                    <th scope="col">NombreGrupo</th>
                                    <th scope="col" class="text-center">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($grupoDolares as $grupo)
                                    <tr>
                                        <td>{{ $grupo->FechaCreacion }}</td>
                                        <td scope="row">{{ $grupo->NombreGrupo }}</td>
                                        <td align="center">
                                            <a href="../administracion/paquetes/detalle-paquete/{{ $grupo->IdGrupo }}"
                                                class="btnDetalleGrupo" title="Ver Detalle"><i
                                                    class="list-icon material-icons">visibility</i></a>
                                            <a href="../administracion/paquetes/editar/{{ $grupo->IdGrupo }}"
                                                title="Editar"><i class="list-icon material-icons">edit</i></a>
                                            {{-- Nuevo codigo --}}
                                            <a class="fs-12 " title="Eliminar" href="javascript:void(0);"
                                                id="btnGrupo-{{ $grupo->IdGrupo }}"
                                                onclick="eliminarGrupo({{ $grupo->IdGrupo }})">
                                                <i class="list-icon material-icons">cancel</i></a>
                                            {{-- Fin --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal --}}
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function eliminarGrupo(id) {
            Swal.fire({
                title: 'Estas seguro?',
                text: "Una vez eliminado el grupo no podrás recuperalo!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Entendido',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = 'paquetes/eliminar-grupo/' + id;
                }
            })
        }
    </script>
    <script type="text/javascript">
        $(function() {
            $(document).ready(function() {
                $('#tableSoles').DataTable({
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

        $(function() {
            $(document).ready(function() {
                $('#tableDolares').DataTable({
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
