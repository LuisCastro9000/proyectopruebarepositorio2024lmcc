<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr>
            <th align="center" scope="col" style="font-weight:bold">Descripción</th>
            <th align="center" scope="col" style="font-weight:bold">Código (SKU)</th>
            <th align="center" scope="col" style="font-weight:bold">Unidad de Medida</th>
            <th align="center" scope="col" style="font-weight:bold">Marca</th>
            <th align="center" scope="col" style="font-weight:bold">Inventario Inicial</th>
            <th align="center" scope="col" style="font-weight:bold">Entradas</th>
            <th align="center" scope="col" style="font-weight:bold">Salidas</th>
            <th align="center" scope="col" style="font-weight:bold">Inventario Final</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($productos as $item)
            <tr>
                <td width="70" align="center">{{ $item->Descripcion }}</td>
                <td width="20" align="center">{{ $item->CodigoBarra }}</td>
                <td width="25" align="center">{{ $item->UnidadMedida }}</td>
                <td width="25" align="center">{{ $item->Marca }}</td>
                <td width="20" align="center">{{ $item->InventarioInicial }}</td>
                <td width="15" align="center">{{ $item->Entradas }}</td>
                <td width="15" align="center">{{ $item->Salidas }}</td>
                <td width="15" align="center">{{ $item->InventarioFinal }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
