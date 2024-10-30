{{-- @props(['color' => 'primary', 'type' => 'submit', 'size' => 'fs-22'])
<button type="{{ $type }}" {{ $attributes->merge(['class' => "btn btn-$color btnLoader"]) }}>
    <span id="btnTexto">{{ $textoBoton }}</span>
    <div id="seccionLoader" class="d-none">
        <span class="d-flex align-items-center">
            <i class='bx bx-loader-alt bx-spin {{ $size }} mr-2'></i>{{ $textoLoader }}
        </span>
    </div>
</button> --}}

@props(['color' => 'primary', 'type' => 'submit', 'size' => 'fs-22', 'accion' => 'Store', 'nombreClase' => 'btnLoader'])
<button type="{{ $type }}" {{ $attributes->merge(['class' => "btn btn-$color $nombreClase"]) }}
    data-accion='{{ $accion }}'>
    <span id="btnTexto{{ $accion }}">{{ $textoBoton }}</span>
    <div id="seccionLoader{{ $accion }}" class="d-none">
        <span class="d-flex justify-content-center align-items-center">
            @if (isset($textoLoader))
                <i class='bx bx-loader-alt bx-spin {{ $size }} mr-2'></i>{{ $textoLoader }}
            @else
                <i class='bx bx-loader-alt bx-spin mx-4 {{ $size }}'></i>
            @endif
        </span>
    </div>
</button>
