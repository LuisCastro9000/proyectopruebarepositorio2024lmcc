@extends('layouts.app')
@section('title', 'AsignarPermisos')
@section('content')
    <div class="container">

        {!! Form::open([
            'url' => '/administracion/permisos/' . $idUsuarioPermiso,
            'method' => 'PUT',
            'name' => 'formActualizarPermisos',
        ]) !!}
        {{ csrf_field() }}

        @if (session::has('usuarioCreado'))
            <section class="d-flex justify-content-between align-items-center mt-4 flex-wrap">
                <article>
                    <h6>Asignar permisos al Nuevo Usuario</h6>
                </article>
                <article>
                    <button class="btn btn-primary" type="submit" onclick="return confirmarActulizarPermisos()">Asignar
                        Permisos</button>
                </article>
            </section>
        @else
            <section class="d-flex justify-content-center justify-content-sm-end mt-4">
                <button class="btn btn-primary" type="submit" onclick="return confirmarPermisos()">Actualizar
                    Permisos</button>
            </section>
        @endif
        {{-- @foreach ($permisosDelSistema as $menu)
            <div class="jumbotron mt-3 bg-jumbotron bg-jumbotron--grisClaro">
                <section class="seccionPermisosUsuarios">
                    <div class="custom-control custom-checkbox border-left--rojo">
                        @if (in_array($menu->IdPermiso, $arrayPermisosDeUsuario))
                            <input type="checkbox" class="custom-control-input permiso" id="menu-{{ $menu->IdPermiso }}"
                                checked value="{{ $menu->IdPermiso }}" name="permisos[]"
                                onclick="activarYdesactivarCheckPermisoMenu({{ $menu->IdPermiso }})">
                        @else
                            <input type="checkbox" class="custom-control-input permiso" id="menu-{{ $menu->IdPermiso }}"
                                value="{{ $menu->IdPermiso }}" name="permisos[]"
                                onclick="activarYdesactivarCheckPermisoMenu({{ $menu->IdPermiso }})">
                        @endif
                        <label class="custom-control-label check ml-3" for="menu-{{ $menu->IdPermiso }}">Menu
                            {{ $menu->Descripcion }}</label>
                    </div>
                    <hr>
                    @foreach ($menu->Permisos as $permi)
                        <section class="seccionSubPermisosUsuarios">
                            <div class="custom-control custom-checkbox offset-2 offset-sm-1">
                                @if (in_array($permi->IdSubPermiso, $arraySubPermisosDeUsuario))
                                    <input type="checkbox"
                                        class="custom-control-input checkSubPermiso quitarDisabledSubPermiso-{{ $menu->IdPermiso }} subPermiso-{{ $menu->IdPermiso }} subPermisoYnivelNoCheked-{{ $menu->IdPermiso }}"
                                        data-idsubpermiso="{{ $menu->IdPermiso }}" id="permiso-{{ $permi->IdSubPermiso }}"
                                        checked value="{{ $permi->IdSubPermiso }}" name="subPermisos[]"
                                        onclick="activarYdesactivarCheckSubPermiso({{ $permi->IdSubPermiso }})" disabled>
                                @else
                                    <input type="checkbox"
                                        class="custom-control-input checkSubPermiso quitarDisabledSubPermiso-{{ $menu->IdPermiso }} subPermiso-{{ $menu->IdPermiso }} subPermisoYnivelNoCheked-{{ $menu->IdPermiso }}"
                                        data-idsubpermiso="{{ $menu->IdPermiso }}" id="permiso-{{ $permi->IdSubPermiso }}"
                                        value="{{ $permi->IdSubPermiso }}" name="subPermisos[]"
                                        onclick="activarYdesactivarCheckSubPermiso({{ $permi->IdSubPermiso }})" disabled>
                                @endif
                                <label class="custom-control-label"
                                    for="permiso-{{ $permi->IdSubPermiso }}">{{ $permi->Descripcion }}</label>
                            </div>
                            @foreach ($permi->SubPermisos as $subPermi)
                                <section class="seccionSubNivelesUsuarios">
                                    <div class="custom-control custom-checkbox offset-4 offset-sm-2">
                                        @if (in_array($subPermi->IdSubNivel, $arraySubNivelesDeUsuario))
                                            <input type="checkbox" data-idsubnivel="{{ $permi->IdSubPermiso }}"
                                                class="custom-control-input checkSubNivel quitarDisabledSubNivel-{{ $permi->IdSubPermiso }} subNivel-{{ $permi->IdSubPermiso }}
                                                 nivel-{{ $menu->IdPermiso }} subPermisoYnivelNoCheked-{{ $menu->IdPermiso }}"
                                                id="subPermiso-{{ $subPermi->IdSubNivel }}" checked
                                                value="{{ $subPermi->IdSubNivel }}" name="subNiveles[]" disabled>
                                        @else
                                            <input type="checkbox" data-idsubnivel="{{ $permi->IdSubPermiso }}"
                                                class="custom-control-input checkSubNivel quitarDisabledSubNivel-{{ $permi->IdSubPermiso }} subNivel-{{ $permi->IdSubPermiso }}
                                                 nivel-{{ $menu->IdPermiso }} subPermisoYnivelNoCheked-{{ $menu->IdPermiso }}"
                                                id="subPermiso-{{ $subPermi->IdSubNivel }}"
                                                value="{{ $subPermi->IdSubNivel }}" name="subNiveles[]" disabled>
                                        @endif
                                        <label class="custom-control-label"
                                            for="subPermiso-{{ $subPermi->IdSubNivel }}">{{ $subPermi->DetalleNivel }}</label>
                                    </div>
                                </section>
                            @endforeach
                        </section>
                    @endforeach
                </section>
            </div>
        @endforeach
        <section aria-label="breadcrumb">
            <div class="breadcrumb d-flex justify-content-center">
                <h5 class="breadcrumb-item ">SECCIÓN PERMISOS BOTONES ADMINISTRATIVOS</h5>
            </div>
        </section>
        @foreach ($permisosBotonesDelSistema as $boton)
            <div class="jumbotron mt-3 bg-jumbotron bg-jumbotron--grisClaro">
                <section class="seccionPermisosUsuarios">
                    <div class="custom-control custom-checkbox border-left--rojo">
                        @if (in_array($boton->Id, $arrayPermisosSubBotonesHabilitados))
                            <input id="boton{{ $boton->Id }}" type="checkbox" checked
                                class="custom-control-input checkBoton" data-id-boton="{{ $boton->Id }}"
                                name="permisosBotonesChekeados[]" value="{{ $boton->Id }}">
                        @else
                            <input id="boton{{ $boton->Id }}" type="checkbox" class="custom-control-input checkBoton"
                                data-id-boton="{{ $boton->Id }}" name="permisosBotonesChekeados[]"
                                value="{{ $boton->Id }}">
                        @endif
                        <label class="custom-control-label check ml-3" for="boton{{ $boton->Id }}">BOTÓN
                            {{ $boton->Nombre }}</label>
                    </div>
                    <hr>
                    @foreach ($boton->SubBotones as $subBoton)
                        <section class="seccionSubPermisosUsuarios">
                            <div class="custom-control custom-checkbox offset-2 offset-sm-1">
                                @if (in_array($subBoton->Id, $arrayPermisosBotonesHabilitados))
                                    <input id="subBoton{{ $subBoton->Id }}" type="checkbox" checked
                                        class="custom-control-input checkSubBoton checkSubBoton{{ $boton->Id }}"
                                        data-id-subboton="{{ $subBoton->Id }}" data-id-boton="{{ $boton->Id }}"
                                        name="permisosSubBotonesChekeados[]" value="{{ $subBoton->Id }}">
                                @else
                                    <input id="subBoton{{ $subBoton->Id }}" type="checkbox"
                                        class="custom-control-input checkSubBoton checkSubBoton{{ $boton->Id }}"
                                        data-id-subboton="{{ $subBoton->Id }}" data-id-boton="{{ $boton->Id }}"
                                        name="permisosSubBotonesChekeados[]" value="{{ $subBoton->Id }}">
                                @endif
                                <label class="custom-control-label"
                                    for="subBoton{{ $subBoton->Id }}">{{ $subBoton->Nombre }}</label>
                            </div>
                        </section>
                    @endforeach
                </section>
            </div>
        @endforeach --}}
        @include('partials._permisosDelSistema')
        {!! Form::close() !!}
    </div>


@stop

@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <script>
        $('#checkTodos').click(function() {
            if ($(this).is(':checked')) {
                $('.checkUser').prop("checked", true);
            } else {
                $('.checkUser').prop("checked", false);
            }
        })

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
            var checkPermisos = $('.checkedPermisosSistema').map(function() {
                if ($(this).prop('checked')) {
                    return $(this).val();
                }
            }).get();

            if (checkPermisos.length == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'error',
                    text: 'Revise le falto seleccionar Permisos',
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
@stop
{{-- @section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmarActulizarPermisos() {
            event.preventDefault();
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
                    document.formActualizarPermisos.submit();
                }
            })
        }

        $(function() {
            $('[name="permisos[]"]').each(function() {
                var id = $(this).val();
                if (this.checked) {
                    $('.quitarDisabledSubPermiso-' + id).removeAttr('disabled', 'disabled');
                } else {
                    $('.subPermisoYnivelNoCheked-' + id).prop("checked", false);
                }
            });

            $('[name="subPermisos[]"]').each(function() {
                var id = $(this).val();
                if (this.checked) {
                    $('.quitarDisabledSubNivel-' + id).removeAttr('disabled', 'disabled');
                } else {
                    $('.quitarDisabledSubNivel-' + id).prop("checked", false);
                }
            });
        })


        function activarYdesactivarCheckPermisoMenu(id) {
            if ($('#menu-' + id).prop('checked')) {
                $('.subPermiso-' + id).removeAttr('disabled', 'disabled');
                $('.subPermiso-' + id).prop("checked", true);
                $('.nivel-' + id).removeAttr('disabled', 'disabled');
            } else {
                $('.subPermiso-' + id).prop("checked", false);
                $('.subPermiso-' + id).attr('disabled', 'disabled');

                $('.nivel-' + id).prop("checked", false);
                $('.nivel-' + id).attr('disabled', 'disabled');
            }
        }

        function activarYdesactivarCheckSubPermiso(id) {
            if ($('#permiso-' + id).prop('checked')) {
                $('.subNivel-' + id).removeAttr('disabled', 'disabled');
                $('.subNivel-' + id).prop("checked", true);
            } else {
                $('.subNivel-' + id).prop("checked", false);
                $('.subNivel-' + id).attr('disabled', 'disabled');
            }

        }

        document.addEventListener("click", function(event) {
            if (event.target.matches('.checkSubNivel')) {
                const idSubNivel = event.target.dataset.idsubnivel;
                let numero = 0;
                $('.subNivel-' + idSubNivel).each(function() {
                    if (this.checked) {
                        numero++;
                    }
                });
                if (numero == 0) {
                    $('#permiso-' + idSubNivel).prop("checked", false);
                }
            }

            if (event.target.matches('.checkSubPermiso')) {
                const idSubPermiso = event.target.dataset.idsubpermiso;
                let numeroUno = 0;
                $('.subPermiso-' + idSubPermiso).each(function() {
                    if (this.checked) {
                        numeroUno++;
                    }
                });
                if (numeroUno == 0) {
                    $('#menu-' + idSubPermiso).prop("checked", false);
                }
            }
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
@stop --}}
