<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th align="center" scope="col" style="font-weight:bold">Comprobante</th>
            <th align="center" scope="col" style="font-weight:bold">Fecha Emitida</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo de Pago</th>
            <th align="center" scope="col" style="font-weight:bold">Total</th>
            <th align="center" scope="col" style="font-weight:bold">Estado</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($reporteComprasVentas as $reporte)
            <tr>
                <td width="20" align="center">{{ $reporte->Comprobante }}</td>
                <td width="20" align="center">{{ $reporte->FechaCreacion }}</td>
                @if ($reporte->IdTipoPago == 1)
                    <td width="20" align="center">Contado</td>
                @else
                    <td width="20" align="center">Crédito</td>
                @endif
                <td width="20" align="center">{{ $reporte->Total }}</td>
                <td width="20" align="center">{{ $reporte->Estado }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
