<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr>
            <th align="center" scope="col" style="font-weight:bold">PlacaVehiculo</th>
            <th align="center" scope="col" style="font-weight:bold">Cliente</th>
            <th align="center" scope="col" style="font-weight:bold">Fecha Próximo Mantenimiento</th>
            <th align="center" scope="col" style="font-weight:bold">Siguiente Mantenimiento Km</th>
            <th align="center" scope="col" style="font-weight:bold">Días Restantes</th>
            <th align="center" scope="col" style="font-weight:bold">Estado Km</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reporte as $vehiculo)
            <tr>
                <td width="22" align="center">{{ $vehiculo->PlacaVehiculo }}</td>
                <td width="60" align="center">{{ $vehiculo->Nombre }}</td>
                <td width="35" align="center">{{ $vehiculo->ProximaFecha }}</td>
                <td width="30" align="center">{{ $vehiculo->ProximoMantenimiento }}</td>
                <td width="15" align="center">{{ $vehiculo->DiasRestantes }} Días</td>
                <td width="20" align="center">{{ $vehiculo->Estado }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
