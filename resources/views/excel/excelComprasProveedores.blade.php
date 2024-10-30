<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th align="center" scope="col">Fecha Emitida</th>
            <th align="center" scope="col">Proveedor</th>
            <th align="center" scope="col">Documento</th>
            <th align="center" scope="col">Tipo Comprobante</th>
            <th align="center" scope="col">Codigo</th>
            <th align="center" scope="col">Tipo de Pago</th>
            <th align="center" scope="col">Tipo de Compra</th>
            <th align="center" scope="col">Tipo Moneda</th>
            <th align="center" scope="col">Total Costo</th>
            <th align="center" scope="col">Estado</th>
        </tr>
    </thead>
        <tbody>
            @foreach($reporteProveedores as $reportProveedor)
            <tr>
                <td width="20" align="center">{{$reportProveedor->FechaCreacion}}</td>
                <td width="60"  align="center">{{$reportProveedor->Nombres}}</td>
                <td width="20"  align="center">{{$reportProveedor->Documento}}</td>
                <td width="20" align="center">{{$reportProveedor->Descripcion}}</td>
                <td width="20" align="center">{{$reportProveedor->Serie}} - {{$reportProveedor->Numero}}</td>
                @if($reportProveedor->IdTipoPago == 1)
                    <td  width="20" align="center">Contado</td>
                @else
                    <td width="20" align="center">Crédito</td>
                @endif
                <td width="20" align="center">{{$reportProveedor->TipoCompra == 1 ? 'Gravada': 'Exonerada'}}</td>
                <td width="20" align="center">{{$reportProveedor->IdTipoMoneda == 1 ? 'Soles': 'Dólares'}}</td>
                <td width="20" align="center">{{$reportProveedor->Total}}</td>
                <td width="20" align="center">{{$reportProveedor->Estado}}</td>
            </tr>
            @endforeach
        </tbody>
</table>