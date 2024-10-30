<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th scope="col" align="center">Fecha de Baja</th>
            <th scope="col" align="center">Usuario</th>
            <th scope="col" align="center">Producto</th>
            <th scope="col" align="center">TipoMoneda</th>
            <th scope="col" align="center">Cantidad</th>
            <th scope="col" align="center">Costo</th>
            <th scope="col" align="center">Precio</th>
            <th scope="col" align="center">Motivo</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bajaProductos as $bajaProd)
            <tr>
                <td width="20" align="center">{{ $bajaProd->FechaBaja }}</td>
                <td width="40">{{ $bajaProd->Nombre }}</td>
                <td width="50">{{ $bajaProd->Descripcion }}</td>
                <td width="15" align="center">{{ $bajaProd->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                <td width="15" align="center">{{ $bajaProd->Cantidad }}</td>
                <td width="15" align="center">{{ $bajaProd->Costo }}</td>
                <td width="15" align="center">{{ $bajaProd->Precio }}</td>
                <td width="40">
                    @if ($bajaProd->IdMotivo == 1)
                        Consumo Interno
                    @elseif($bajaProd->IdMotivo == 2)
                        Producto Vencido
                    @elseif($bajaProd->IdMotivo == 3)
                        Perdida y/o Extravio
                    @else
                        {{ $bajaProd->DescripcionMotivo }}
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
