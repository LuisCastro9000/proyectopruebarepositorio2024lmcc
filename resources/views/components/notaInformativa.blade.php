@props(['color' => 'danger', 'icono' => 'bxs-error', 'titulo' => 'Importante:'])
<article class="media alert alert-{{ $color }} border-0">
    <i class='bx {{ $icono }} mr-4 fs-30 d-flex align-self-center mr-3'></i>
    <div class="media-body">
        <h5 class="fs-15 font-weight-bold m-0">{{ $titulo }}</h5>
        {{ $slot }}
    </div>
</article>
