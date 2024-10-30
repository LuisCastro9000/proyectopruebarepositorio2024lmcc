{{-- 
    mostrarBoton: Cambia a {false} si se utilizan otros filtros para obtener datos(oculta el BOTON que realiza la obtencion de datos en la seccion fechasPersonalizadas).
    tipoRangoFechas: Se cambia a {anual} para extender el tiempo para el rango de fechas personalizadas
--}}

@props([
    'colFechaInicio' => '5',
    'colFechaFin' => '5',
    'mostrarBoton' => 'true', // valor por defecto {true}, si solo se usa el select para obtener datos.
    'tipoRangoFechas' => 'mensual', // valor por defecto {mensual}, tiempo para el rango de fechas personalizadas
])

<section id="seccionFechasPersonalizadas" class="mt-1 mb-4 row">
    <div class="col-md-{{ $colFechaInicio }}">
        <div id="Inicio">
            <label class="form-control-label">Desde</label>
            <div class="input-group">
                <input id="datepickerIni" type="text" class="form-control datepicker" name="fechaIni"
                    data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}' autocomplete="off"
                    onkeydown="return false" data-date-end-date="0d">
            </div>
        </div>
    </div>
    <div class="col-md-{{ $colFechaFin }}">
        <div id="Final">
            <label class="form-control-label">Hasta</label>
            <div class="input-group">
                <input id="datepickerFin" type="text" class="form-control datepicker" name="fechaFin"
                    data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}' autocomplete="off"
                    onkeydown="return false" data-date-end-date="0d">
            </div>
        </div>
    </div>
    @if ($mostrarBoton === 'true')
        <div class="col-md-2 mt-2 mt-md-0 align-self-end">
            <button id="btnBuscarConFechasPersonalizadas" type="submit" class="btn btn-primary">Buscar</button>
        </div>
    @endif
    @if ($tipoRangoFechas === 'mensual')
        <article id="mensajeRangoFechas" class="text-danger col-12">"El rango máximo permitido para fechas
            personalizadas es de 6 meses."
        </article>
    @endif
</section>


{{-- <x-inputFecha :colFechaInicio="'6'" :colFechaFin="'6'" :mostrarBoton="'false'"></x-inputFecha> --}}
