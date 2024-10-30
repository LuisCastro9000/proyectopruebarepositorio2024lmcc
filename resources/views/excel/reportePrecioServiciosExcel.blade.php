<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th align="center" scope="col" style="font-weight:bold">Descripcion</th>
            <th align="center" scope="col" style="font-weight:bold"> TipoMoneda</th>
            {{-- Agregue una columna con el nombre documento --}}
            <th align="center" scope="col" style="font-weight:bold">Precio</th>
            <th align="center" scope="col" style="font-weight:bold">CodigoBarra</th>
            <th align="center" scope="col" style="font-weight:bold">Sucursal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reportePreciosServicios as $reporteServicio)
            <tr>
                <td width="60" align="center">{{ $reporteServicio->Descripcion }}</td>
                @if ($reporteServicio->IdTipoMoneda == 1)
                    <td width="30" align="center">Soles</td>
                @else
                    <td width="30" align="center">Dólares</td>
                @endif
                <td width="20" align="center">{{ $reporteServicio->Precio }}</td>
                <td width="20" align="center">{{ $reporteServicio->Codigo }}</td>
                <td width="20" align="center">{{ $reporteServicio->Nombre }}</td>
                {{-- fin --}}

            </tr>
        @endforeach
    </tbody>
</table>
