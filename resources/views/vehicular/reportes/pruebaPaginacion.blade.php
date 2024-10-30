<div class="datos">
    <p>datoss</p>
    @foreach ($datos as $dato)
        {{ $dato->PlacaVehiculo }}
    @endforeach

    {{ $datos->onEachSide(3)->links() }}

</div>
