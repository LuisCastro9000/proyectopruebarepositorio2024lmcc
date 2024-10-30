@php
    $fecha = new DateTime();
    $anioActual = $fecha->format('Y');
@endphp
<footer class="footer bg-primary text-inverse text-center">
    <div class="container"><span class="fs-13 heading-font-family"> Copyright @ 2019-{{ $anioActual }}. Todos los
            derechos reservados
            EASYFACTPERU SAC</span>
    </div>
    <!-- /.container -->
</footer>
