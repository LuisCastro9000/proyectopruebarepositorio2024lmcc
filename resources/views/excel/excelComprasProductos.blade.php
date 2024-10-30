<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th align="center" scope="col" style="font-weight:bold">Fecha Emitida</th>
            <th align="center" scope="col" style="font-weight:bold">Proveedor</th>
            {{-- Agregue una columna con el nombre documento --}}
            <th align="center" scope="col" style="font-weight:bold">Documento</th>
            {{-- fin --}}
            <th align="center" scope="col" style="font-weight:bold">Producto</th>
            <th align="center" scope="col" style="font-weight:bold">Costo</th>
            <th align="center" scope="col" style="font-weight:bold">Cantidad</th>
            <th align="center" scope="col" style="font-weight:bold">Código</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo de Pago</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo de Compra</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo Moneda</th>
            <th align="center" scope="col" style="font-weight:bold">Total Costo</th>
            <th align="center" scope="col" style="font-weight:bold">Estado</th>
        </tr>
    </thead>
        <tbody>
            @foreach($reporteProductos as $reporteProducto)
            <tr>
                <td width="20" align="center">{{$reporteProducto->FechaCreacion}}</td>
                <td width="60" align="center">{{$reporteProducto->Nombres}}</td>
                {{-- Agregue una columna con el nombre documento --}}
                <td width="20" align="center">{{$reporteProducto->NumeroDocumento}}</td>
                {{-- fin --}}
                <td width="60" align="center">{{$reporteProducto->Descripcion}}</td>
                {{-- <td width="60" align="center">{{$reporteProducto->Productos[0]->Articulo}}</td> --}}
                <td width="15" align="center">{{$reporteProducto->PrecioCosto}}</td>
                <td width="15" align="center">{{$reporteProducto->Cantidad}}</td>
                <td width="30" align="center">{{$reporteProducto->Serie}} - {{$reporteProducto->Numero}}</td>
                @if($reporteProducto->IdTipoPago == 1)
                    <td width="30" align="center">Contado</td>
                @else
                    <td width="30" align="center">Crédito</td>
                @endif
                <td width="20" align="center">{{$reporteProducto->TipoCompra == 1 ? 'Gravada': 'Exonerada'}}</td>
                <td width="20" align="center">{{$reporteProducto->IdTipoMoneda == 1 ? 'Soles': 'Dólares'}}</td>
                <td width="20" align="center">{{$reporteProducto->Total}}</td>
                <td width="20" align="center">{{$reporteProducto->Estado}}</td>
            </tr>
            @endforeach
        </tbody>
</table>