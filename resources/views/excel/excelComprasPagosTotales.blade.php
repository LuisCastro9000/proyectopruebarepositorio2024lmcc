<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th scope="col" style="font-weight:bold" align="center">Proveedor</th>
            <th scope="col" style="font-weight:bold" align="center">Documento</th>
            <th scope="col" style="font-weight:bold" align="center">Fecha Cancelado</th>
            <th scope="col" style="font-weight:bold" align="center">Tipo Moneda</th>
            <th scope="col" style="font-weight:bold" align="center">Importe Cancelado</th>
            <th scope="col" style="font-weight:bold" align="center">Monto Efectivo</th>
            <th scope="col" style="font-weight:bold" align="center">Monto Cuenta Bancaria</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pagosTotales as $pagoTotal)
            <tr>
                <td width="60" align="center">{{ $pagoTotal->Proveedor }}</td>
                <td width="20" align="center">{{ $pagoTotal->Serie . '-' . $pagoTotal->Numero }}</td>
                <td width="20" align="center">{{ $pagoTotal->FechaPago }}</td>
                <td width="20" align="center">{{ $pagoTotal->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                <td width="20" align="center">{{ $pagoTotal->ImportePagado }}</td>
                <td width="20" align="center">{{ $pagoTotal->MontoEfectivo }}</td>
                <td width="25" align="center">{{ $pagoTotal->MontoBanco }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
