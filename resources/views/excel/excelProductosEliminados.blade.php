<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr>
            <th align="center" scope="col" style="font-weight:bold">FechaEliminación</th>
            <th align="center" scope="col" style="font-weight:bold">Usuario</th>
            <th align="center" scope="col" style="font-weight:bold">Descripción</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo</th>
            <th align="center" scope="col" style="font-weight:bold">Cantidad</th>
            <th align="center" scope="col" style="font-weight:bold">Código</th>
            <th align="center" scope="col" style="font-weight:bold">Moneda</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reporte as $item)
            <tr>
                <td width="22" align="center">{{ $item->FechaEliminacion }}</td>
                <td width="40" align="center">{{ $item->Nombre }}</td>
                <td width="60" align="center">{{ $item->Descripcion }}</td>
                <td width="20" align="center">{{ $item->IdTipo == 1 ? 'Producto' : 'Servicio' }}</td>
                <td width="15" align="center">{{ $item->Stock }}</td>
                <td width="25" align="center">{{ $item->Codigo }}</td>
                <td width="20" align="center">{{ $item->IdTipoMoneda == 1 ? 'Soles' : 'Dolares' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
