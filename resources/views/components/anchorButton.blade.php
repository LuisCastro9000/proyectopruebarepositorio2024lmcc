@props(['href' => null, 'textoIcono' => null, 'title' => null, 'size' => null])
<a href="{{ $href }}" title="{{ $title }}" {{ $attributes->merge([]) }}>
    @if (isset($textoIcono))
        <i class="list-icon material-icons {{ $size }}">{{ $textoIcono }}</i>
    @endif
    {{ $slot }}
</a>
