<div class="table-responsive">
    <table id="tablaArticulos" class="table table-centered mb-0">
        <thead class="table-light">
            <tr>
                <th>Sucursal</th>
                <th>Articulo</th>
                <th>Stock-Actual</th>
                <th>Documento</th>
                <th>Stock-A-Reponer</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($articulosVendidos as $articulo)
                <tr>
                    <td>{{ $articulo->NombreSucursal }}</td>
                    <td>{{ $articulo->NombreArticulo }}</td>
                    <td><span
                            class="badge-warning-lighten font-14 spanExistencia-{{ $articulo->IdArticulo }}">{{ $articulo->CantidadActual }}</span>
                    </td>
                    <td>{{ $articulo->CorrelativoVenta }}</td>
                    <td><span class="badge-primary-lighten font-14">{{ $articulo->CantidadVendida }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
