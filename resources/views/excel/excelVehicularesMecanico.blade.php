<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th align="center" scope="col" style="font-weight:bold">Fecha Atención</th>
            <th align="center" scope="col">Mecánico</th>
            <th align="center" scope="col">Documento</th>
            {{-- Agregados Nuevas columnas --}}
            <th align="center" scope="col" style="font-weight:bold">Descripción</th>
            <th align="center" scope="col" style="font-weight:bold">Codigo</th>
            <th align="center" scope="col" style="font-weight:bold">PrecioProducto</th>
            <th align="center" scope="col" style="font-weight:bold">PrecioServicio</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo de Moneda</th>
            {{-- Fin --}}
            <th align="center" scope="col" style="font-weight:bold">Importe Total</th>
            <th align="center" scope="col" style="font-weight:bold">Placa</th>
            <th align="center" scope="col" style="font-weight:bold">Cliente</th>
            <th align="center" scope="col" style="font-weight:bold">RUC</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reporteVehiculares as $reporteVehicular)
            <tr>
                <td width="20" align="center">{{ $reporteVehicular->FechaAtencion }}</td>
                <td width="40">
                    @if ($inputMecanico == 'Generico')
                        {{ $inputMecanico }}
                    @else
                        {{ $reporteVehicular->Operario }}
                    @endif
                </td>
                <td width="20" align="center">{{ $reporteVehicular->Documento }}</td>
                <td width="50" align="center">{{ $reporteVehicular->articulo }}</td>
                <td width="20" align="center">{{ $reporteVehicular->Codigo }}</td>
                {{-- <td width="20">{{ $reporteVehicular->Importe }}</td> --}}
                @if ($reporteVehicular->IdTipo === 1)
                <td width="20" align="center">{{ $reporteVehicular->Importe }}</td>
                @else
                    <td width="20" align="center">0</td>
                @endif
                @if ($reporteVehicular->IdTipo === 2)
                <td width="20" align="center">{{ $reporteVehicular->Importe }}</td>
                @else
                    <td width="20" align="center">0</td>
                @endif
                @if ($reporteVehicular->tipoMoneda == 1)
                    <td width="30" align="center">Soles</td>
                @else
                    <td width="30" align="center">Dolares</td>
                @endif
                <td width="20">{{ $reporteVehicular->Total }}</td>
                <td width="20" align="center">{{ $reporteVehicular->PlacaVehiculo }}</td>
                <td width="70" align="center">{{ $reporteVehicular->Cliente }}</td>
                <td width="20" align="center">{{ $reporteVehicular->NumeroDocumento }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
