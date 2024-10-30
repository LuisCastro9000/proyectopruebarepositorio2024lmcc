@extends('layouts.app')
@section('title', 'Editar-Plan')
@section('content')
    <div class="container">
        @if (session('error'))
            <div class="alert alert-info mt-4">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('error') }}
            </div>
        @endif
        <section>
            {!! Form::open([
                'url' => '/administracion/planes-suscripcion/' . $idPlanSuscripcion,
                'method' => 'post',
                'name' => 'formPlanSuscripcion',
                'files' => true,
            ]) !!}
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            <section class="d-flex justify-content-between align-items-center mt-4">
                <h6 class="page-title-heading mr-0 mr-r-5">Editar Plan de Suscripción</h6>
                <button class="btn btn-primary" type="submit" onclick="return confirmarPermisos()">Actualizar Plan</button>
            </section>
            @include('partials._permisosDelSistema')
            @include('partials._permisosModulos')
            {!! Form::close() !!}
        </section>
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
                    document.formPlanSuscripcion.submit();
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
