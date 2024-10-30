<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th align="center" scope="col" style="font-weight:bold">FechaCreción</th>
            <th align="center" scope="col" style="font-weight:bold">Cliente</th>
            <th align="center" scope="col" style="font-weight:bold">Placa</th>
            <th align="center" scope="col" style="font-weight:bold">RUC/DNI</th>
            <th align="center" scope="col" style="font-weight:bold">Mecánico</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo Atención</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($atencionesVehiculares as $vehiculo)
            <tr>
                <td width="25" align="center">{{ $vehiculo->FechaCreacion }}</td>
                <td width="60" align="center">{{ $vehiculo->RazonSocial }}</td>
                <td width="20" align="center">{{ $vehiculo->PlacaVehiculo }}</td>
                <td width="20" align="center">{{ $vehiculo->NumeroDocumento }}</td>
                <td width="40" align="center">{{ $vehiculo->NombreOperario }}</td>
                <td width="25" align="center">{{ $vehiculo->TipoAtencion }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
