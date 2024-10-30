<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th align="center" scope="col" style="font-weight:bold">Fecha Emitida</th>
            <th align="center" scope="col" style="font-weight:bold">Cliente</th>
            <th align="center" scope="col" style="font-weight:bold">Placa Vehículo</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo Atención</th>
            <th align="center" scope="col" style="font-weight:bold">Mecánico</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($atencionesVehiculares as $atencion)
            <tr>
                <td width="20" align="center">{{ $atencion->FechaCreacion }}</td>
                <td width="70" align="center">{{ $atencion->RazonSocial }}</td>
                <td width="20" align="center">{{ $atencion->PlacaVehiculo }}</td>
                <td width="25" align="center">{{ $atencion->Descripcion }}</td>
                <td width="50" align="center">{{ $atencion->NombreOperario }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
