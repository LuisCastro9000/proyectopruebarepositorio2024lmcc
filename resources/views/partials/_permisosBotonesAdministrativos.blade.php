@foreach ($permisosBotones as $boton)
    <div class="jumbotron mt-3 bg-jumbotron bg-jumbotron--grisClaro">
        <section class="seccionPermisosUsuarios">
            <div class="custom-control custom-checkbox border-left--rojo">
                <input id="boton{{ $boton->Id }}" type="checkbox" class="custom-control-input checkBoton"
                    data-id-boton="{{ $boton->Id }}" name="permisosBotonesChekeados[]" value="{{ $boton->Id }}">
                <label class="custom-control-label check ml-3" for="boton{{ $boton->Id }}">BOTÓN
                    {{ $boton->Nombre }}</label>
            </div>
            <hr>
            @foreach ($boton->SubBotones as $subBoton)
                <section class="seccionSubPermisosUsuarios">
                    <div class="custom-control custom-checkbox offset-2 offset-sm-1">
                        <input id="subBoton{{ $subBoton->Id }}" type="checkbox"
                            class="custom-control-input checkSubBoton checkSubBoton{{ $boton->Id }}"
                            data-id-subboton="{{ $subBoton->Id }}" data-id-boton="{{ $boton->Id }}"
                            name="permisosSubBotonesChekeados[]" value="{{ $subBoton->Id }}">
                        <label class="custom-control-label"
                            for="subBoton{{ $subBoton->Id }}">{{ $subBoton->Nombre }}</label>
                    </div>
                </section>
            @endforeach
        </section>
    </div>
@endforeach
