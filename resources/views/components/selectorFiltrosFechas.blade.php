{{-- obtenerDatos, cambia a {false} si se usan otros filtros para obtener datos --}}
{{-- metodoObtenerDatos, por defecto esta en {submit} al aplicar el evento change se envia el formulario y se cambia al valor a {ajax} si se obtiene los datos al ejecutar una peticion ajax --}}


@props([
    'clases' => '',
    'obtenerDatos' => 'true', // valor por defecto {true}, si solo se usa el select para obtener los datos
    'metodoObtenerDatos' => 'submit', // valor por defecto {submit}, para el envio del formulario
    'tipoRangoFechas' => 'mensual', // valor por defecto {mensual}, tiempo para el rango de fechas personalizadas
])
<div class="{{ $attributes->get('class', $clases) }} form-group">
    <label>Fecha</label>
    <select id="idFecha" class="form-control selectorFiltroFecha" data-obtener-datos="{{ $obtenerDatos }}"
        data-metodo-obtener-datos="{{ $metodoObtenerDatos }}" data-tipo-rango-fechas="{{ $tipoRangoFechas }}"
        name="fecha">
        <option value="1">Hoy</option>
        <option value="2">Ayer</option>
        <option value="3">Semana Actual</option>
        <option value="4">Semana Anterior</option>
        <option value="5">Mes Actual</option>
        <option value="6">Mes Anterior</option>
        @if ($tipoRangoFechas === 'mensual')
            <option value="10">Últimos 6 meses</option>
        @else
            <option value="7">Año Actual</option>
            <option value="8">Año Anterior</option>
            <option value="11">Últimos 3 años</option>
        @endif
        <option value="9">Personalizar</option>
    </select>
</div>

{{-- <x-selectorFiltrosFechas :clases="'form-material'" :metodoObtenerDatos= "'ajax'"></x-selectorFiltrosFechas> --}}
