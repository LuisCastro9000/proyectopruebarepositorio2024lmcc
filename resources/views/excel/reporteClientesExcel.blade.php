<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th align="center" scope="col" style="font-weight:bold">Fecha Emitida</th>
            <th align="center" scope="col" style="font-weight:bold">Cliente</th>
            <th align="center" scope="col" style="font-weight:bold">Código</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo Venta</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo de Pago</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo Moneda</th>
            <th align="center" scope="col" style="font-weight:bold">Descuento</th>
            <th align="center" scope="col" style="font-weight:bold">Total Pago</th>
            <th align="center" scope="col" style="font-weight:bold">Estado</th>
        </tr>
    </thead>
        <tbody>
           @foreach($reporteClientes as $reportCliente)
            <tr>
                <td width="24" align="center">{{$reportCliente->FechaCreacion}}</td>
                <td width="60" align="center">{{$reportCliente->Nombres}}</td>
                <td width="18" align="center">{{$reportCliente->Serie}} - {{$reportCliente->Numero}}</td>
                @if($reportCliente->IdTipoPago == 1)
                    <td width="14" align="center">Contado</td>
                @else
                    <td width="14" align="center">Crédito</td>
                @endif
                <td width="16" align="center">{{$reportCliente->TipoVenta == 1 ? 'Gravada': 'Exonerada'}}</td>
                <td width="16" align="center">{{$reportCliente->IdTipoMoneda == 1 ? 'Soles': 'Dólares'}}</td>
                <td width="14" align="center">{{$reportCliente->Exonerada}}</td>
                <td width="14" align="center">{{$reportCliente->Total}}</td>
                <td width="17" align="center">{{$reportCliente->Estado}}</td>
            </tr>
            @endforeach
        </tbody>
</table>
