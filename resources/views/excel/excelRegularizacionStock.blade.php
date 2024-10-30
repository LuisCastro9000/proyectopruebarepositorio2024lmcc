<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr>
            <th align="center" scope="col" style="font-weight:bold">FechaRegularizacion</th>
            <th align="center" scope="col" style="font-weight:bold">Usuario Responsable</th>
            <th align="center" scope="col" style="font-weight:bold">Descripcion</th>
            <th align="center" scope="col" style="font-weight:bold">Motivo</th>
            <th align="center" scope="col" style="font-weight:bold">Precio</th>
            <th align="center" scope="col" style="font-weight:bold">Costo</th>
            <th align="center" scope="col" style="font-weight:bold">Stock en Sistema</th>
            <th align="center" scope="col" style="font-weight:bold">Stock en Almacen</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reporteArticulos as $item)
            <tr>
                <td width="22" align="center">{{ $item->FechaCreacion }}</td>
                <td width="50" align="center">{{ $item->NombreUsuario }}</td>
                <td width="50" align="center">{{ $item->Descripcion }}</td>
                <td width="40" align="center">{{ $item->Motivo }}</td>
                <td width="15" align="center">{{ $item->Precio }}</td>
                <td width="15" align="center">{{ $item->Costo }}</td>
                <td width="20" align="center">{{ $item->StockSistema }}</td>
                <td width="20" align="center">{{ $item->StockAlmacen }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
