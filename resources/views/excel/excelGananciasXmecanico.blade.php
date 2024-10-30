<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr>
            <th align="center" scope="col" style="font-weight:bold">FechaCreación</th>
            <th align="center" scope="col" style="font-weight:bold">Placa</th>
            <th align="center" scope="col" style="font-weight:bold">Razon Social</th>
            <th align="center" scope="col" style="font-weight:bold">Número Documento</th>
            <th align="center" scope="col" style="font-weight:bold">Número Cotización</th>
            <th align="center" scope="col" style="font-weight:bold">Código Comprobante</th>
            <th align="center" scope="col" style="font-weight:bold">Descripción Producto</th>
            <th align="center" scope="col" style="font-weight:bold">Cantidad Vendida</th>
            <th align="center" scope="col" style="font-weight:bold">Costo de Productos</th>
            <th align="center" scope="col" style="font-weight:bold">Costo de Servicios</th>
            <th align="center" scope="col" style="font-weight:bold">Descuento</th>
            <th align="center" scope="col" style="font-weight:bold">Ganancia de Productos</th>
            <th align="center" scope="col" style="font-weight:bold">Ganancia de Servicios</th>
            <th align="center" scope="col" style="font-weight:bold">Importe x Item</th>
            <th align="center" scope="col" style="font-weight:bold">Importe Total x Documento</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo Modeda</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reporteGanancias as $item)
            <tr>
                <td width="20" align="center">{{ $item->FechaCreacion }}</td>
                <td width="15" align="center">{{ $item->Placa }}</td>
                <td width="70" align="center">{{ $item->RazonSocial }}</td>
                <td width="25" align="center">{{ $item->NumeroDocumento }}</td>
                <td width="25" align="center">{{ $item->Cotizacion }}</td>
                <td width="25" align="center">{{ $item->ComprobanteVenta }}</td>
                <td width="50" align="center">{{ $item->Descripcion }}</td>
                <td width="20" align="center">{{ $item->Cantidad }}</td>
                @if ($item->IdTipo == 1)
                    <td width="25" align="center">{{ $item->Costo }}</td>
                @else
                    <td width="25" align="center">0</td>
                @endif
                @if ($item->IdTipo == 2)
                    <td width="25" align="center">{{ $item->Costo }}</td>
                @else
                    <td width="25" align="center">0</td>
                @endif
                <td width="20" align="center">{{ $item->Descuento }}</td>
                @if ($item->IdTipo == 1)
                    <td width="25" align="center">{{ $item->Ganancia }}</td>
                @else
                    <td width="25" align="center">0</td>
                @endif
                @if ($item->IdTipo == 2)
                    <td width="25" align="center">{{ $item->Ganancia }}</td>
                @else
                    <td width="25" align="center">0</td>
                @endif
                <td width="20" align="center">{{ $item->Importe }}</td>
                <td width="35" align="center">{{ $item->Total }}</td>
                <td width="20" align="center">{{ $item->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
