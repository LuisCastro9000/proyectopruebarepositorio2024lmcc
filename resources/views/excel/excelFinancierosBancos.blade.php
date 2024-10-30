<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th align="center" scope="col" style="font-weight:bold">Fecha Transferencia</th>
            <th align="center" scope="col" style="font-weight:bold">Nro Operación</th>
            <th align="center" scope="col" style="font-weight:bold">Detalle</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo Movimiento</th>
            <th align="center" scope="col" style="font-weight:bold">Ingreso</th>
            <th align="center" scope="col" style="font-weight:bold">Salida</th>
            <th align="center" scope="col" style="font-weight:bold">Monto Actual</th>
            <th align="center" scope="col" style="font-weight:bold">Sucursal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($detallesCuenta as $detalleCuenta)
            <tr>
                <td width="20" align="center">{{ $detalleCuenta->FechaPago }}</td>
                <td width="20" align="center">{{ $detalleCuenta->NumeroOperacion }}</td>
                <td width="60" align="center">{{ $detalleCuenta->Detalle }}</td>
                <td width="20" align="center">{{ $detalleCuenta->TipoMovimiento }}</td>
                <td width="15" align="center">{{ $detalleCuenta->Entrada }}</td>
                <td width="15" align="center">{{ $detalleCuenta->Salida }}</td>
                <td width="15" align="center">{{ $detalleCuenta->MontoActual }}</td>
                <td width="60" align="center">{{ $detalleCuenta->NombreSucursal }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
