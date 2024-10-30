@extends('layouts.app')
@section('title', 'AsignarPermisos')
@section('content')
    <div class="container">
        <section>
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
        </section>

        {!! Form::open([
            'url' => '/administracion/permisos/datos/asignar-permisos-administradores',
            'method' => 'POST',
            'name' => 'formActualizarPermisos',
            'files' => true,
        ]) !!}
        {{ csrf_field() }}

        <section class="d-flex justify-content-center justify-content-sm-end mt-4">
            <button class="btn btn-primary" type="submit" onclick="return confirmarPermisos()">Guardar
                Permisos</button>
        </section>
        <section class=" seccionUsuaiosAdministradores mt-4">
            <div id="accordion" role="tablist">
                <div class="card">
                    <div class="card-header" role="tab" id="headingOne">
                        <section class="d-flex justify-content-between flex-wrap">
                            <a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <span class="fs-20 font-weight-bolder">Seleccione Usuarios</span>
                            </a>
                            <article class="d-flex align-items-center flex-wrap justify-content-center flexGap--10">
                                <div>
                                    <select id="selectRubro" class="form-control" name="rubro">
                                        <option value="Ninguno">Ninguno</option>
                                        <option value="Todos">Todos</option>
                                        @foreach ($rubros as $rubro)
                                            <option value="{{ $rubro->IdRubro }}">{{ $rubro->Descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="selectRubro" class="ml-2">Rubros</label>
                                </div>
                                {{-- <div class="badge-success p-1 rounded">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input fs-28" id="checkTodos">
                                        <label class="custom-control-label text-light" for="checkTodos">Todos</label>
                                    </div>
                                </div> --}}
                                <div>
                                    <select id="selectPlanesSuscripcion" class="form-control" name="rubro">
                                        <option value="Ninguno">Ninguno</option>
                                        @foreach ($planesSuscripcion as $plan)
                                            <option value="{{ $plan->IdPlanSuscripcion }}">{{ $plan->Nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="selectPlanesSuscripcion" class="ml-2">Planes Suscripción</label>
                                </div>
                            </article>
                        </section>
                    </div>
                    <div id="collapseOne" class="collapse" role="tabpanel" aria-labelledby="headingOne">
                        <div class="card-body">
                            <table id="table" width="100%" class="table table-responsive-xl">
                                <thead>
                                    <tr class="bg-success">
                                        <th>Nombre Cliente</th>
                                        <th class="text-center">Login</th>
                                        <th class="text-center">Codigo</th>
                                        <th class="text-center">Plan de Suscripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($usuariosAdministradores as $user)
                                        <tr>
                                            <th scope="row">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox"
                                                        class="custom-control-input checkUser{{ $user->IdRubro }} checkUserPlanSuscripcion{{ $user->IdPlanSuscripcion }} checkUserAll"
                                                        id="checkUser-{{ $user->IdUsuario }}" name="idUsuarios[]"
                                                        value="{{ $user->IdUsuario }}">
                                                    <label class="custom-control-label"
                                                        for="checkUser-{{ $user->IdUsuario }}">{{ $user->Nombre }}</label>
                                                </div>
                                            </th>
                                            <td class="text-center">{{ $user->Login }}</td>
                                            <td class="text-center">{{ $user->CodigoCliente }}</td>
                                            <td class="text-center">{{ $user->NombrePlan }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="seccionPermisosSistema">
            @foreach ($permisosDelSistema as $menu)
                <div class="jumbotron mt-3 bg-jumbotron bg-jumbotron--grisClaro">
                    <section class="seccionPermisosUsuarios">
                        <div class="custom-control custom-checkbox border-left--rojo">
                            <input type="checkbox"
                                class="custom-control-input checkedPermisosSistema permiso subNivelCheckedPermiso-{{ $menu->IdPermiso }}"
                                id="permiso-{{ $menu->IdPermiso }}" value="{{ $menu->IdPermiso }}" name="permisos[]"
                                onclick="activarYdesactivarCheckSubPermisoYsubNivel({{ $menu->IdPermiso }})">
                            <label class="custom-control-label check ml-3" for="permiso-{{ $menu->IdPermiso }}">Menu
                                {{ $menu->Descripcion }}</label>
                        </div>
                        <hr>
                        @foreach ($menu->SubPermisos as $permi)
                            <section class="seccionSubPermisosUsuarios">
                                <div class="custom-control custom-checkbox offset-2 offset-sm-1">
                                    <input type="checkbox"
                                        class="custom-control-input checkPermiso-{{ $menu->IdPermiso }} checkedPermisosSistema checkSubPermisos-{{ $menu->IdPermiso }} permisoCheckedSubPermisoYsubNivel-{{ $menu->IdPermiso }} subNivelCheckedSubPermiso-{{ $permi->IdSubPermiso }}"
                                        id="subPermiso-{{ $permi->IdSubPermiso }}" value="{{ $permi->IdSubPermiso }}"
                                        name="subPermisos[]"
                                        onclick="activarYdesactivarCheckPermisoYsubNivel({{ $permi->IdSubPermiso }}, {{ $menu->IdPermiso }})">
                                    <label class="custom-control-label"
                                        for="subPermiso-{{ $permi->IdSubPermiso }}">{{ $permi->Descripcion }}</label>
                                </div>
                                @foreach ($permi->SubNiveles as $subPermi)
                                    <section class="seccionSubNivelesUsuarios">
                                        <div class="custom-control custom-checkbox offset-4 offset-sm-2">
                                            <input type="checkbox"
                                                class="custom-control-input checkPermiso-{{ $menu->IdPermiso }} checkSubNivel-{{ $permi->IdSubPermiso }} checkedPermisosSistema subPermisoCheckedSubNivel-{{ $permi->IdSubPermiso }} permisoCheckedSubPermisoYsubNivel-{{ $menu->IdPermiso }}"
                                                onclick="activarYdesactivarCkeckSubPermisosYPermisos({{ $subPermi->IdSubNivel }}, {{ $permi->IdSubPermiso }}, {{ $menu->IdPermiso }})"
                                                id="subNivel-{{ $subPermi->IdSubNivel }}"
                                                value="{{ $subPermi->IdSubNivel }}" name="subNiveles[]">
                                            <label class="custom-control-label"
                                                for="subNivel-{{ $subPermi->IdSubNivel }}">{{ $subPermi->DetalleNivel }}</label>
                                        </div>
                                    </section>
                                @endforeach
                            </section>
                        @endforeach
                    </section>
                </div>
            @endforeach

            @include('partials._permisosBotonesAdministrativos', ['permisosBotones' => $permisosBotones])
        </section>
        {!! Form::close() !!}
    </div>
@stop
@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('#selectRubro').on('change', function() {
            const idRubro = $(this).val();
            if (idRubro == 'Ninguno') {
                $(`.checkUserAll`).prop("checked", false);
                return true;
            }
            if (idRubro == 'Todos') {
                $(`.checkUserAll`).prop("checked", true);
                return true;
            }
            $(`.checkUserAll`).prop("checked", false);
            $(`.checkUser${idRubro}`).prop("checked", true);
        })

        $('#selectPlanesSuscripcion').on('change', function() {
            const idPlan = $(this).val();
            if (idPlan == 'Ninguno') {
                $(`.checkUserAll`).prop("checked", false);
                return true;
            }
            $(`.checkUserAll`).prop("checked", false);
            $(`.checkUserPlanSuscripcion${idPlan}`).prop("checked", true);
        })
    </script>

    <script>
        function activarYdesactivarCheckPermisoYsubNivel(idsubPermiso, idPermiso) {
            if ($('#subPermiso-' + idsubPermiso).prop('checked')) {
                $('.subPermisoCheckedSubNivel-' + idsubPermiso).prop("checked", true);
                $('#permiso-' + idPermiso).prop("checked", true);
            } else {
                let arrayCheckSubPermiso = $(".checkSubPermisos-" + idPermiso).map(function() {
                    if ($(this).prop('checked')) {
                        return $(this).val();
                    }
                }).get();
                if (arrayCheckSubPermiso.length == '') {
                    $('#permiso-' + idPermiso).prop("checked", false);
                }
                $('.subPermisoCheckedSubNivel-' + idsubPermiso).prop("checked", false);

            }
        }

        function activarYdesactivarCheckSubPermisoYsubNivel(idPermiso) {
            if ($('#permiso-' + idPermiso).prop('checked')) {
                $('.permisoCheckedSubPermisoYsubNivel-' + idPermiso).prop("checked", true);
            } else {
                $('.permisoCheckedSubPermisoYsubNivel-' + idPermiso).prop("checked", false);
            }
        }

        function activarYdesactivarCkeckSubPermisosYPermisos(idSubNivel, idsubPermiso, idPermiso) {
            if ($('#subNivel-' + idSubNivel).prop('checked')) {
                $('#subPermiso-' + idsubPermiso).prop("checked", true);
                $('#permiso-' + idPermiso).prop("checked", true);
            } else {
                let arrayCheckSubNivel = $(".checkSubNivel-" + idsubPermiso).map(function() {
                    if ($(this).prop('checked')) {
                        return $(this).val();
                    }
                }).get();
                if (arrayCheckSubNivel.length == '') {
                    $('#subPermiso-' + idsubPermiso).prop("checked", false);
                }
                let arrayCheckTodos = $(".checkPermiso-" + idPermiso).map(function() {
                    if ($(this).prop('checked')) {
                        return $(this).val();
                    }
                }).get();
                if (arrayCheckTodos.length == '') {
                    $('#permiso-' + idPermiso).prop("checked", false);
                }
            }
        }

        function confirmarPermisos() {
            event.preventDefault();
            var checkPermisos = $(`.checkedPermisosSistema:checked`).length;

            var checkUser = $(`.checkUserAll:checked`).length;

            var checkSubBotones = $(`.checkBoton:checked`).length;

            if ((checkPermisos + checkSubBotones) == 0 || checkUser == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'error',
                    text: 'Revise le falto seleccionar usuarios o Permisos',
                })
            } else {
                alerta();
            }
        }

        function alerta() {
            Swal.fire({
                title: 'Estas seguro de otorgar los nuevos permisos?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    utilidades.showLoadingOverlay();
                    document.formActualizarPermisos.submit();
                }
            })
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
                    searching: true,
                    bPaginate: false,
                    language: {
                        search: "Buscar:",
                    }
                });
            });
        });
    </script>

    <script>
        $(document).on('click', function(e) {
            if (e.target.matches('.checkBoton')) {
                const idBoton = $(e.target).data('idBoton');
                if ($(`#boton${idBoton}`).prop('checked')) {
                    $(`.checkSubBoton${idBoton}`).prop("checked", true);
                    $(`.checkSubBoton${idBoton}`).removeAttr('disabled', 'disabled');
                } else {
                    $(`.checkSubBoton${idBoton}`).prop("checked", false);
                }
            }

            if (e.target.matches('.checkSubBoton')) {
                const idBoton = $(e.target).data('idBoton');
                const idSubBoton = $(e.target).data('idSubboton');
                if ($(`#subBoton${idSubBoton}`).prop('checked')) {
                    const cantidadChekeados = $(`.checkSubBoton${idBoton}:checked`).length;
                    if (cantidadChekeados == 1) {
                        $(`#boton${idBoton}`).prop('checked', true);
                    }
                } else {
                    const cantidadChekeados = $(`.checkSubBoton${idBoton}:checked`).length;
                    if (cantidadChekeados === 0) {
                        $(`#boton${idBoton}`).prop("checked", false);
                    }
                }
            }
        })
    </script>
@stop
