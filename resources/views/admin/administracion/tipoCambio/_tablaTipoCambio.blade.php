<div class="table-responsive">
    <table id="tablaDatos" class="table table-centered mb-0">
        <thead class="table-light">
            <tr>
                <th>FechaCreación</th>
                <th>Sucursal</th>
                <th>TipoCambioCompras</th>
                <th>TipoCambioVentas</th>
            </tr>
        </thead>
        <tbody>
            @if ($tipoCambio != null)
                <tr>
                    <td>{{ $tipoCambio->FechaCreacion }}</td>
                    <td>{{ $tipoCambio->NombreSucursal }}</td>
                    <td>
                        <div id="tipoCompra">
                            {{ $tipoCambio->TipoCambioCompras }}
                            <div class="mb-3">
                                <input type="text" min="1" class="form-control" id="inputTipoCambioCompras">
                            </div>
                        </div>
                    </td>
                    <td>
                        <div id="tipoVenta">
                            {{ $tipoCambio->TipoCambioVentas }}
                            <div class="mb-3">
                                <input type="text" min="1" class="form-control" id="inputTipoCambioVentas">
                            </div>
                        </div>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
