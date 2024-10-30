<div class="table-responsive">
    <table id="tablaDatosVenta" class="table table-centered mb-0">
        <thead class="table-light">
            <tr>
                <th>FechaCreación</th>
                <th>Sucursal</th>
                <th>Venta</th>
                <th>Total</th>
                <th>Hash</th>
                <th>Resumen</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datosVenta as $venta)
                <tr>
                    <td>{{ $venta->FechaCreacion }}</td>
                    <td>{{ $venta->NombreSucursal }}</td>
                    <td>{{ $venta->CorrelativoVenta }} </td>
                    <td>{{ $venta->Total }}</td>
                    <td><span class="spanHash">{{ $venta->Hash }}</span></td>
                    <td><span class="spanResumen">{{ $venta->Resumen }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
