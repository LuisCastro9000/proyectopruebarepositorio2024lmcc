<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th align="center" scope="col" style="font-weight:bold">Fecha Emitida</th>
            <th align="center" scope="col" style="font-weight:bold">Cliente </th>
            <th align="center" scope="col" style="font-weight:bold">Documento Cliente </th>
            <th align="center" scope="col" style="font-weight:bold">Dirección </th>
            <th align="center" scope="col" style="font-weight:bold">Distrito </th>
            <th align="center" scope="col" style="font-weight:bold">Producto - Detalle</th>
            <th align="center" scope="col" style="font-weight:bold">Categoria</th>
            <th align="center" scope="col" style="font-weight:bold">Marca</th>
            <th align="center" scope="col" style="font-weight:bold">Codigo de Barra</th>
            <th align="center" scope="col" style="font-weight:bold">Código</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo Moneda Venta</th>
            <th align="center" scope="col" style="font-weight:bold">Precio Costo</th>
            <th align="center" scope="col" style="font-weight:bold">Precio Lista Unitario</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo Cambio</th>
            <th align="center" scope="col" style="font-weight:bold">Precio Venta Convertido a Soles</th>
            <th align="center" scope="col" style="font-weight:bold">Precio Venta Convertido a Dólares</th>
            <th align="center" scope="col" style="font-weight:bold">Cantidad </th>
            <th align="center" scope="col" style="font-weight:bold">Descuento </th>
            <th align="center" scope="col" style="font-weight:bold">Importe Item </th>
            <th align="center" scope="col" style="font-weight:bold">Tipo de Pago</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo Venta</th>
            <th align="center" scope="col" style="font-weight:bold">Total Pago</th>
            <th align="center" scope="col" style="font-weight:bold">Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reporteProductos as $reportProducto)
            <tr>
                <td width="20" align="center">{{ $reportProducto->FechaCreacion }}</td>
                <td width="40" align="center">{{ $reportProducto->Nombres }}</td>
                <td width="20" align="center">{{ $reportProducto->Documento }}</td>
                <td width="70" align="center">{{ $reportProducto->Direccion }}</td>
                <td width="20" align="center">{{ $reportProducto->nombreDistrito }}</td>
                <td width="50" align="center">{{ $reportProducto->Descripcion }} @if ($reportProducto->Detalle != null)
                        - {{ $reportProducto->Detalle }}
                    @endif
                </td>
                <td width="20" align="center">{{ $reportProducto->nombreCategoria }}</td>
                <td width="20" align="center">{{ $reportProducto->nombreMarca }}</td>
                <td width="20" align="center">{{ $reportProducto->codigo }}</td>
                <td width="20" align="center">{{ $reportProducto->Serie }}-{{ $reportProducto->Numero }}</td>
                <td width="20" align="center">{{ $reportProducto->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                <td width="15" align="center">{{ $reportProducto->PrecioCosto }}</td>
                <td width="25" align="center">{{ $reportProducto->PrecioArticulo }}</td>
                <td width="15" align="center">{{ $reportProducto->TipoCambio }}</td>
                <td width="35" align="center">{{ $reportProducto->PrecioVentaConvertidoSoles }}</td>
                <td width="35" align="center">{{ $reportProducto->PrecioVentaConvertidoDolares }}</td>
                <td width="15" align="center">{{ $reportProducto->Cantidad }}</td>
                <td width="15" align="center">{{ $reportProducto->Descuento }}</td>
                <td width="20" align="center">{{ $reportProducto->ImporteItem }}</td>
                @if ($reportProducto->IdTipoPago == 1)
                    <td width="12" align="center">Contado</td>
                @else
                    <td width="12" align="center">Crédito</td>
                @endif
                <td width="20" align="center">{{ $reportProducto->TipoVenta == 1 ? 'Gravada' : 'Exonerada' }}
                </td>
                <td width="20" align="center">{{ $reportProducto->Total }}</td>
                <td width="20" align="center">{{ $reportProducto->Estado }}</td>
            </tr>
        @endforeach
    </tbody>
</table>


{{-- <table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th>&nbsp;</th>
            <th align="center" scope="col" style="font-weight:bold">Fecha Emitida</th>
            <th align="center" scope="col" style="font-weight:bold">Cliente </th>
            <th align="center" scope="col" style="font-weight:bold">Documento Cliente </th>
            <th align="center" scope="col" style="font-weight:bold">Dirección </th>
            <th align="center" scope="col" style="font-weight:bold">Distrito </th>
            <th align="center" scope="col" style="font-weight:bold">Producto - Detalle</th>
            <th align="center" scope="col" style="font-weight:bold">Categoria</th>
            <th align="center" scope="col" style="font-weight:bold">Marca</th>
            <th align="center" scope="col" style="font-weight:bold">Codigo de Barra</th>
            <th align="center" scope="col" style="font-weight:bold">Código</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo Moneda Venta</th>
            <th align="center" scope="col" style="font-weight:bold">Precio Lista Unitario</th>
            <th align="center" scope="col" style="font-weight:bold">Precio Costo</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo Cambio</th>
            <th align="center" scope="col" style="font-weight:bold">Precio Costo Convertido a Soles</th>
            <th align="center" scope="col" style="font-weight:bold">Precio Costo Convertido a Dólares</th>
            <th align="center" scope="col" style="font-weight:bold">Cantidad </th>
            <th align="center" scope="col" style="font-weight:bold">Descuento </th>
            <th align="center" scope="col" style="font-weight:bold">Importe Item </th>
            <th align="center" scope="col" style="font-weight:bold">Tipo de Pago</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo Venta</th>
            <th align="center" scope="col" style="font-weight:bold">Total Pago</th>
            <th align="center" scope="col" style="font-weight:bold">Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reporteProductos as $reportProducto)
            <tr>
                <td>&nbsp;</td>
                <td width="20" align="center">{{ $reportProducto->FechaCreacion }}</td>
                <td width="40" align="center">{{ $reportProducto->Nombres }}</td>
                <td width="20" align="center">{{ $reportProducto->Documento }}</td>
                <td width="70" align="center">{{ $reportProducto->Direccion }}</td>
                <td width="20" align="center">{{ $reportProducto->nombreDistrito }}</td>
                <td width="50" align="center">{{ $reportProducto->Descripcion }} @if ($reportProducto->Detalle != null)
                        - {{ $reportProducto->Detalle }}
                    @endif
                </td>
                <td width="20" align="center">{{ $reportProducto->nombreCategoria }}</td>
                <td width="20" align="center">{{ $reportProducto->nombreMarca }}</td>
                <td width="20" align="center">{{ $reportProducto->codigo }}</td>
                <td width="20" align="center">{{ $reportProducto->Serie }}-{{ $reportProducto->Numero }}</td>
                <td width="20" align="center">{{ $reportProducto->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                <td width="25" align="center">{{ $reportProducto->PrecioUnidadRealVenta }}</td>
                <td width="15" align="center">{{ $reportProducto->PrecioCosto }}</td>
                <td width="15" align="center">{{ $reportProducto->TipoCambio }}</td>
                <td width="35" align="center">{{ $reportProducto->PrecioCostoConvertidoSoles }}</td>
                <td width="35" align="center">{{ $reportProducto->PrecioCostoConvertidoDolares }}</td>
                <td width="15" align="center">{{ $reportProducto->Cantidad }}</td>
                <td width="15" align="center">{{ $reportProducto->Descuento }}</td>
                <td width="20" align="center">{{ $reportProducto->ImporteItem }}</td>
                @if ($reportProducto->IdTipoPago == 1)
                    <td width="12" align="center">Contado</td>
                @else
                    <td width="12" align="center">Crédito</td>
                @endif
                <td width="20" align="center">{{ $reportProducto->TipoVenta == 1 ? 'Gravada' : 'Exonerada' }}
                </td>
                <td width="20" align="center">{{ $reportProducto->Total }}</td>
                <td width="20" align="center">{{ $reportProducto->Estado }}</td>
            </tr>
        @endforeach
    </tbody>
</table> --}}
