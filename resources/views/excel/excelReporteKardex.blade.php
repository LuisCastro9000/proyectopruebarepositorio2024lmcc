<table width="100%" class="tabla3">
    <thead>
        <tr class="bg-primary">
            <th class="borderTabla" align="center" style="font-weight:bold">Fecha</th>
            <th class="borderTabla" align="center" style="font-weight:bold">Tipo</th>
            <th class="borderTabla" align="center" style="font-weight:bold">Usuario</th>
            <th class="borderTabla" align="center" style="font-weight:bold">Documento</th>
            <th class="borderTabla" align="center" style="font-weight:bold">Costo/Precio</th>
            <th class="borderTabla" align="center" style="font-weight:bold">Entrada</th>
            <th class="borderTabla" align="center" style="font-weight:bold">Salida</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalEntrada = 0;
            $totalSalida = 0;
        @endphp
        @foreach ($reporteKardex as $kardex)
            @if ($kardex->EstadoStock == 'E')
                @php $totalEntrada = $totalEntrada + $kardex->Cantidad; @endphp
            @else
                @php $totalSalida = $totalSalida + $kardex->Cantidad; @endphp
            @endif
            <tr>
                <td class="borderTabla" width="20" align="center">{{ $kardex->fecha_movimiento }}</td>
                <td class="borderTabla" width="50" align="center">{{ $kardex->Descripcion }}</td>
                <td class="borderTabla" width="40" align="center">{{ $kardex->Nombre }}</td>
                <td class="borderTabla" width="30" align="center">{{ $kardex->documento_movimiento }}</td>
                <td class="borderTabla" width="15" align="center">{{ number_format($kardex->costo, 2, '.', ',') }}
                </td>
                @if ($kardex->EstadoStock == 'E')
                    <td class="borderTabla" width="15" align="center">
                        {{ number_format($kardex->Cantidad, 2, '.', ',') }}</td>
                    <td class="borderTabla" width="15" align="center">{{ number_format(0, 2, '.', ',') }}</td>
                @else
                    <td class="borderTabla" width="15" align="center">{{ number_format(0, 2, '.', ',') }}</td>
                    <td class="borderTabla" width="15" align="center">
                        {{ number_format($kardex->Cantidad, 2, '.', ',') }}</td>
                @endif
            </tr>
        @endforeach
        <tr>
            <td colspan="5"><span class="">Totales: </span></td>
            <td align="center" style="background-color: slategray; color:#FFFF">{{ number_format($totalEntrada, 2, '.', ',') }}</td>
            <td align="center" style="background-color: slategray; color:#FFFF">{{ number_format($totalSalida, 2, '.', ',') }}</td>
        </tr>
    </tbody>
</table>
