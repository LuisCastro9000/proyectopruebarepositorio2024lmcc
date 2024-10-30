<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th scope="col" align="center" style="font-weight:bold">Descripción</th>
            <th scope="col" align="center" style="font-weight:bold">Stock</th>
            <th scope="col" align="center" style="font-weight:bold">Marca</th>
            <th scope="col" align="center" style="font-weight:bold">Categoría</th>
            <th scope="col" align="center" style="font-weight:bold">Código</th>
            <th scope="col" align="center" style="font-weight:bold">Ubicación</th>
            <th scope="col" align="center" style="font-weight:bold">FechaCreacionProducto</th>
            <th scope="col" align="center" style="font-weight:bold">UltimaFechaMovimiento</th>
            <th scope="col" align="center" style="font-weight:bold">Unidad de Medida</th>
            <th scope="col" align="center" style="font-weight:bold">Tipo Moneda</th>
            <th scope="col" align="center" style="font-weight:bold">Costo</th>
            <th scope="col" align="center" style="font-weight:bold">Precio</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datosStock as $item)
            <tr>
                <td width="60" align="center">{{ $item->Descripcion }}</td>
                <td width="20" align="center">{{ $item->Existencia }}</td>
                <td width="20" align="center">{{ $item->NombreMarca }}</td>
                <td width="20" align="center">{{ $item->NombreCategoria }}</td>
                <td width="20" align="center">{{ $item->CodigoBarra }}</td>
                <td width="30" align="center">{{ $item->Ubicacion }}</td>
                <td width="25" align="center">{{ $item->FechaCreacionArticulo }}</td>
                <td width="30" align="center">{{ $item->FechaMovimiento }}</td>
                <td width="20" align="center">{{ $item->NombreUnidadMedida }}</td>
                <td width="15" align="center">{{ $item->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                <td width="20" align="center">{{ $item->Costo }}</td>
                <td width="20" align="center">{{ $item->Precio }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
