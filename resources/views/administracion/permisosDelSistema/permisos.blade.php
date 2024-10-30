@extends('layouts.app')
@section('title', 'Permisos del Sistema')
@section('content')

    <div class="container">
        @if (Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{ session('success') }}
            </div>
        @endif
        @if (Session::has('creado'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{ session('creado') }}
            </div>
        @endif


        {!! Form::open([
            'url' => '/administracion/permisos-del-sistema/eliminar-permiso',
            'method' => 'post',
            'name' => 'formEliminarPermisos',
            'files' => true,
        ]) !!}
        {{ csrf_field() }}

        <section class="d-flex justify-content-center justify-content-sm-end mt-4">
            <a class="btn btn-primary text-light mr-2" type="submit" data-toggle="modal"
                data-target="#modalCrearPermiso">Crear Permisos</a>
            <button class="btn btn-primary" type="submit" onclick="return confirmarEliminarPermisos()">Eliminar
                Permisos</button>
        </section>

        @foreach ($permisosDelSistema as $menu)
            <div class="jumbotron mt-3 bg-jumbotron bg-jumbotron--grisClaro">
                <section class="seccionPermisosUsuarios">
                    <div class="custom-control custom-checkbox border-left--rojo">
                        <input type="checkbox" class=" disabledLienzo custom-control-input permiso"
                            id="menu-{{ $menu->IdPermiso }}" value="{{ $menu->IdPermiso }}" name="permisos[]"
                            onclick="activarYdesactivarCheckPermisoMenu({{ $menu->IdPermiso }})">
                        <label class="custom-control-label check ml-3" for="menu-{{ $menu->IdPermiso }}">Menu
                            {{ $menu->Descripcion }}</label>
                    </div>
                    <hr>
                    @foreach ($menu->Permisos as $permi)
                        <section class="seccionSubPermisosUsuarios">
                            <div class="custom-control custom-checkbox offset-2 offset-sm-1">
                                <input type="checkbox"
                                    class="custom-control-input quitarDisabledSubPermiso-{{ $menu->IdPermiso }} subPermiso-{{ $menu->IdPermiso }} subPermisoYnivelNoCheked-{{ $menu->IdPermiso }}"
                                    id="permiso-{{ $permi->IdSubPermiso }}" value="{{ $permi->IdSubPermiso }}"
                                    name="subPermisos[]"
                                    onclick="activarYdesactivarCheckSubPermiso({{ $permi->IdSubPermiso }})">
                                <label class="custom-control-label"
                                    for="permiso-{{ $permi->IdSubPermiso }}">{{ $permi->Descripcion }}</label>
                            </div>
                            @foreach ($permi->SubPermisos as $subPermi)
                                <section class="seccionSubNivelesUsuarios">
                                    <div class="custom-control custom-checkbox offset-4 offset-sm-2">
                                        <input type="checkbox" name="subNiveles[]"
                                            class="custom-control-input quitarDisabledSubNivel-{{ $permi->IdSubPermiso }} subNivel-{{ $permi->IdSubPermiso }}
                                                 nivel-{{ $menu->IdPermiso }} subPermisoYnivelNoCheked-{{ $menu->IdPermiso }}"
                                            id="subPermiso-{{ $subPermi->IdSubNivel }}"
                                            value="{{ $subPermi->IdSubNivel }}">
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
        {!! Form::close() !!}

        <section>
            <div class="modal fade bd-example-modal-lg" id="modalCrearPermiso" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            {!! Form::open([
                                'url' => '/administracion/permisos-del-sistema',
                                'method' => 'post',
                                'name' => 'formEliminarPermisos',
                                'files' => true,
                            ]) !!}
                            {{ csrf_field() }}
                            <section class="text-center mb-4"><b class="fs-16 text-uppercase">Crear Permiso</b>
                            </section>
                            <div class="form-group">
                                <label for="departamento">Seleccione Tipo</label>
                                <select id="tipoPermiso" class="form-control" name="tipoPermiso">
                                    <option value="permiso">Permiso</option>
                                    <option value="subPermiso">SubPermiso</option>
                                    <option value="subNivel">SubNivel</option>
                                </select>
                            </div>

                            <div id="selectPermiso" class="form-group d-none">
                                <label for="permiso">Seleccione Permiso</label>
                                <select id="permiso" class="form-control selectPermiso" name="permiso">
                                </select>
                            </div>

                            <div id="selectSubPermiso" class="form-group d-none">
                                <label for="subPermiso">Seleccione Sub-Permiso</label>
                                <select id="subPermiso" class="form-control selectSubPermiso" name="subPermiso">
                                </select>
                            </div>

                            <div class="form-group">
                                <input id="descripcion" class="form-control" type="text" name="descripcion"
                                    value="{{ old('descripcion') }}" placeholder="Escribir Descripcion">
                                <span class="text-danger font-size">{{ $errors->first('descripcion') }}</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Crear Permisos</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </div>


@stop

@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmarEliminarPermisos() {
            event.preventDefault();
            Swal.fire({
                title: 'Estas seguro de eliminar los permisos?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.formEliminarPermisos.submit();
                }
            })
        }

        $(function() {
            $('[name="permisos[]"]').each(function() {
                var id = $(this).val();
                if (this.checked) {
                    // $('.quitarDisabledSubPermiso-' + id).removeAttr('disabled', 'disabled');
                } else {
                    // $('.subPermisoYnivelNoCheked-' + id).prop("checked", false);
                }
            });

            $('[name="subPermisos[]"]').each(function() {
                var id = $(this).val();
                if (this.checked) {
                    // $('.quitarDisabledSubNivel-' + id).removeAttr('disabled', 'disabled');
                } else {
                    // $('.quitarDisabledSubNivel-' + id).prop("checked", false);
                }
            });
        })


        function activarYdesactivarCheckPermisoMenu(id) {
            if ($('#menu-' + id).prop('checked')) {
                // $('.subPermiso-' + id).removeAttr('disabled', 'disabled');
                // $('.subPermiso-' + id).attr('onClick', 'return false');
                $('.subPermiso-' + id).prop("checked", true);
                $('.nivel-' + id).prop("checked", true);
                // $('.nivel-' + id).removeAttr('disabled', 'disabled');
                $('.nivel-' + id).attr('onClick', 'return false');
            } else {
                $('.subPermiso-' + id).prop("checked", false);
                // $('.subPermiso-' + id).attr('disabled', 'disabled');
                $('.nivel-' + id).prop("checked", false);
                // $('.nivel-' + id).attr('disabled', 'disabled');
                $('.nivel-' + id).removeAttr('onClick');
            }
        }

        function activarYdesactivarCheckSubPermiso(id) {
            if ($('#permiso-' + id).prop('checked')) {
                // $('.subNivel-' + id).removeAttr('disabled', 'disabled');
                $('.subNivel-' + id).prop("checked", true);
                $('.subNivel-' + id).attr('onClick', 'return false;');
            } else {
                $('.subNivel-' + id).prop("checked", false);
                // $('.subNivel-' + id).attr('disabled', 'disabled');
                $('.subNivel-' + id).removeAttr('onClick');
            }
        }
    </script>
    <script>
        $("#tipoPermiso").on('change', function() {

            var tipoPermiso = $(this).val();
            $('.selectPermiso option').each(function() {
                $(this).remove();
            });
            $('.selectSubPermiso option').each(function() {
                $(this).remove();
            });
            if (tipoPermiso != 'permiso') {
                $.showLoading({
                    name: 'circle-fade',
                });

                $.ajax({
                    type: 'get',
                    url: 'permisos-del-sistema/permisos/obtener-permisos',
                    data: {
                        'tipoPermiso': tipoPermiso,
                    },
                    success: function(data) {
                        if (data[0] == 'subPermiso') {
                            $('#selectPermiso').removeClass('d-none');
                            $('#selectSubPermiso').addClass('d-none');
                            for (var i = 0; i < data[1].length; i++) {
                                $('#permiso').append('<option value="' + data[1][i].IdPermiso + '">' +
                                    data[1][i].Descripcion + '</option>')
                            }
                        }

                        if (data[0] == 'subNivel') {
                            $('#selectPermiso').removeClass('d-none');
                            $('#selectSubPermiso').removeClass('d-none');
                            for (var i = 0; i < data[1].length; i++) {
                                $('#permiso').append('<option value="' + data[1][i].IdPermiso + '">' +
                                    data[1][i].Descripcion + '</option>')
                            }

                            for (var i = 0; i < data[2].length; i++) {
                                $('#subPermiso').append('<option value="' + data[2][i].IdSubPermiso +
                                    '">' +
                                    data[2][i].Descripcion + '</option>')
                            }
                        }
                        $.hideLoading();
                    }
                });
            } else {
                $('#selectPermiso').addClass('d-none');
                $('#selectSubPermiso').addClass('d-none');
            }
        });

        $('#permiso').change(function() {
            var tipoPermiso = 'permiso';
            var idPermiso = $(this).val();

            $('.selectSubPermiso option').each(function() {
                $(this).remove();
            });

            if ($('#tipoPermiso').val() == 'subNivel') {
                $.showLoading({
                    name: 'circle-fade',
                });
                $.ajax({
                    type: 'get',
                    url: 'permisos-del-sistema/permisos/obtener-permisos',
                    data: {
                        'tipoPermiso': tipoPermiso,
                        'idPermiso': idPermiso,
                    },
                    success: function(data) {
                        for (var i = 0; i < data.length; i++) {
                            $('#subPermiso').append('<option value="' + data[i].IdSubPermiso +
                                '">' +
                                data[i].Descripcion + '</option>')
                        }
                        $.hideLoading();
                    }
                });
            }

        })
    </script>
@stop
