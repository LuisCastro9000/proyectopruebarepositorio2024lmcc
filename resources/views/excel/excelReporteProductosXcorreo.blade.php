<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th align="center" scope="col" style="font-weight:bold">Operación</th>
            <th align="center" scope="col" style="font-weight:bold">Fecha Emitida</th>
            <th align="center" scope="col" style="font-weight:bold">Producto Detalle</th>
            <th align="center" scope="col" style="font-weight:bold">Categoria</th>
            <th align="center" scope="col" style="font-weight:bold">Cliente </th>
            <th align="center" scope="col" style="font-weight:bold">Documento Cliente </th>
            <th align="center" scope="col" style="font-weight:bold">Código</th>
            <th align="center" scope="col" style="font-weight:bold">Documento-Afectado</th>
            <th align="center" scope="col" style="font-weight:bold">Precio Venta</th>
            <th align="center" scope="col" style="font-weight:bold">Cantidad </th>
            <th align="center" scope="col" style="font-weight:bold">Tipo de Pago</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo Venta</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo Moneda</th>
            <th align="center" scope="col" style="font-weight:bold">Total Pago</th>
            <th align="center" scope="col" style="font-weight:bold">Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reporteProductos as $reportProducto)
            <tr>
                <td width="20" align="center">{{ $reportProducto->Operacion }}</td>
                <td width="20" align="center">{{ $reportProducto->FechaCreacion }}</td>
                <td width="50" align="center">{{ $reportProducto->Descripcion }}</td>
                <td width="30" align="center">{{ $reportProducto->nombreCategoria }}</td>
                <td width="60" align="center">{{ $reportProducto->NombresCliente }}</td>
                <td width="20" align="center">{{ $reportProducto->Documento }}</td>
                <td width="20" align="center">{{ $reportProducto->Correlativo }}</td>
                <td width="20" align="center">{{ $reportProducto->DocumentoAfectado }} </td>
                <td width="15" align="center">{{ $reportProducto->PrecioUnidadReal }}</td>
                <td width="15" align="center">{{ $reportProducto->Cantidad }}</td>
                <td width="20" align="center"> {{ $reportProducto->IdTipoPago == 1 ? 'Contado' : 'Crédito' }}</td>
                <td width="20" align="center">{{ $reportProducto->TipoVenta == 1 ? 'Gravada' : 'Exonerada' }}</td>
                <td width="20" align="center">{{ $reportProducto->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                <td width="20" align="center">{{ $reportProducto->Total }}</td>
                <td width="20" align="center">{{ $reportProducto->Estado }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
