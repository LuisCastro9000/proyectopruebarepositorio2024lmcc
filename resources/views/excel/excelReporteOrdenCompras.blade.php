<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr>
            <th align="center" scope="col" style="font-weight:bold">Codigo</th>
            <th align="center" scope="col" style="font-weight:bold">Fecha Emitida</th>
            <th align="center" scope="col" style="font-weight:bold">Fecha Recepción</th>
            <th align="center" scope="col" style="font-weight:bold">Proveedor</th>
            <th align="center" scope="col" style="font-weight:bold">Ruc</th>
            <th align="center" scope="col" style="font-weight:bold">Producto</th>
            <th align="center" scope="col" style="font-weight:bold">Cantidad</th>
            <th align="center" scope="col" style="font-weight:bold">Precio</th>
            <th align="center" scope="col" style="font-weight:bold">Total</th>
            <th align="center" scope="col" style="font-weight:bold">TipoMoneda</th>
            <th align="center" scope="col" style="font-weight:bold">TipoPago</th>
            <th align="center" scope="col" style="font-weight:bold">TipoCompra</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reporteOrdenesCompras as $item)
            <tr>
                <td width="20" align="center">{{ $item->Serie }}-{{ $item->Numero }}</td>
                <td width="25" align="center">{{ $item->FechaEmision }}</td>
                <td width="25" align="center">{{ $item->FechaRecepcion }}</td>
                <td width="70" align="center">{{ $item->NombreProveedor }}</td>
                <td width="20" align="center">{{ $item->NumeroDocumento }}</td>
                <td width="50" align="center">{{ $item->Descripcion }}</td>
                <td width="20" align="center">{{ $item->Cantidad }}</td>
                @if ($item->TipoCompra == 1)
                    <td width="20" align="center">{{ $item->PrecioCosto }}</td>
                @else
                    <td width="20" align="center">{{ round($item->PrecioCosto / 1.18, 2) }}</td>
                @endif
                <td width="15" align="center">{{ $item->Total }}</td>
                <td width="15" align="center">{{ $item->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                <td width="15" align="center">{{ $item->IdTipoPago == 1 ? 'Contado' : 'Crédito' }}</td>
                <td width="15" align="center">{{ $item->TipoCompra == 1 ? 'Gravada' : 'Exonerado' }}</td>

            </tr>
        @endforeach
    </tbody>
</table>
