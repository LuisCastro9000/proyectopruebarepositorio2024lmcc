<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th align="center" scope="col" style="font-weight:bold">Fecha Creación</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo Gasto</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo Moneda</th>
            <th align="center" scope="col" style="font-weight:bold">Motivo</th>
            <th align="center" scope="col" style="font-weight:bold">Monto</th>
            <th align="center" scope="col" style="font-weight:bold">Observación</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reporteFinancieroGastos as $reporteFinancieroGasto)
            <tr>
                <td width="20" align="center">{{ $reporteFinancieroGasto->FechaCreacion }}</td>
                <td width="20" align="center"> {{ $reporteFinancieroGasto->TipoGasto == 1 ? 'Fijo' : 'Variable' }}
                </td>
                <td width="20" align="center">
                    {{ $reporteFinancieroGasto->IdTipoMoneda == 1 || $reporteFinancieroGasto->IdTipoMoneda == null ? 'Soles' : ' Dólares' }}
                </td>
                <td width="30" align="center">{{ $reporteFinancieroGasto->Descripcion }}</td>
                <td width="15" align="center">{{ $reporteFinancieroGasto->Monto }}</td>
                <td width="70" align="center">{{ $reporteFinancieroGasto->Observacion }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
