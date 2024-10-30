{{-- para para clases <x-fieldset :legend="'Nombre'" :legendClass="'px-3'" :fieldsetClass="'border border-danger'"> --}}

{{-- <x-fieldset :legend="$cotizacionSelect->Serie . '-' . $numeroCeroIzq" :legendClass="'px-2 btn-success'" :legendAttributes="[
    'data-ejempplo' => 'RoisChavez','data-color' => 'Aczul','disabled' => 'true', 'style' => 'font-size: 52px', ]"> --}}


{{-- <fieldset class="{{ $attributes->get('class', $fieldsetClass) }} fieldset fieldset--bordeCeleste">
    <legend class="{{ $attributes->get('class', $legendClass) }} legend--colorNegro w-auto" $legendAttributes>
        {{ $legend }}
    </legend>
    {{ $slot }}
</fieldset> --}}

@props([
    'legend' => '',
    'legendClass' => '',
    'fieldsetClass' => 'fieldset--bordeCeleste',
    'legendAttributes' => [],
    'fieldsetAttributes' => [],
])

<fieldset @foreach ($fieldsetAttributes as $key => $value) {{ $key }}="{{ $value }}" @endforeach
    class="{{ $attributes->get('class', $fieldsetClass) }} fieldset">
    <legend @foreach ($legendAttributes as $key => $value) {{ $key }}="{{ $value }}" @endforeach
        class="{{ $attributes->get('class', $legendClass) }} legend--colorNegro w-auto">
        {{ $legend }}
    </legend>
    {{ $slot }}
</fieldset>

{{-- EJEMPLO DE COMO USARLO EN EL BLADE --}}
{{-- <x-fieldset :legend="$cotizacionSelect->Serie . '-' . $numeroCeroIzq" :legendClass="'px-2'">
</x-fieldset> --}}
