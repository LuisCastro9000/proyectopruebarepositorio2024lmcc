@extends('layouts.app')
@section('title', 'Asignar Permisos')
@section('content')
    <div class="container">

        {!! Form::open([
            'route' => ['permisos.botones.administrativos.update', $idUsuario],
            'method' => 'PUT',
            'name' => 'formActualizarPermisos',
        ]) !!}
        {{ csrf_field() }}

        <section class="d-flex justify-content-center justify-content-sm-end mt-4">
            <button id="otorgarPermisosBotonesAdministrativos" class="btn btn-primary" type="submit">Actualizar
                Permisos Botones Adminsitrativos</button>
        </section>
        @foreach ($permisosBotones as $boton)
            <div class="jumbotron mt-3 bg-jumbotron bg-jumbotron--grisClaro">
                <section class="seccionPermisosUsuarios">
                    <div class="custom-control custom-checkbox border-left--rojo">
                        @if (in_array($boton->Id, $permisosBotonesDeUsuario))
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
                                @if (in_array($subBoton->Id, $permisosSubBotonesDeUsuario))
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
        @endforeach
        {!! Form::close() !!}
    </div>


@stop

@section('scripts')
    <script>
        let datosPermisosBotones = [];
        let datosPermisosSubBotones = [];
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
                    console.log(datosPermisosSubBotones);
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
