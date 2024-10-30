<section aria-label="breadcrumb">
    <div class="breadcrumb d-flex justify-content-center">
        <h5 class="breadcrumb-item ">SECCIÓN MÓDULOS</h5>
    </div>
</section>
<div class="jumbotron mt-3 bg-jumbotron bg-jumbotron--grisClaro">
    @foreach ($modulosDelSistema as $modulo)
        <section class="seccionModulos">
            <div class="custom-control custom-checkbox border-left--rojo">
                @if (in_array($modulo->IdModulo, $modulosChekeados))
                    <input id="modulo{{ $modulo->IdModulo }}" type="checkbox" checked
                        class="custom-control-input checkModulo" data-id-modulo="{{ $modulo->IdModulo }}"
                        name="modulosChekeados[]" value="{{ $modulo->IdModulo }}">
                @else
                    <input id="modulo{{ $modulo->IdModulo }}" type="checkbox" class="custom-control-input checkModulo"
                        data-id-modulo="{{ $modulo->IdModulo }}" name="modulosChekeados[]"
                        value="{{ $modulo->IdModulo }}">
                @endif
                <label class="custom-control-label check ml-3" for="modulo{{ $modulo->IdModulo }}">
                    {{ $modulo->Descripcion }}</label>
            </div>
            <hr>

        </section>
    @endforeach
</div>
