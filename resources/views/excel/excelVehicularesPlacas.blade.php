<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th align="center" scope="col">Fecha Atención</th>
            <th align="center" scope="col">Producto y/o Servicios</th>
            <th align="center" scope="col">Detalle</th>
            <th align="center" scope="col">CodigoBarra</th>
            <th align="center" scope="col">Documento</th>
            <th align="center" scope="col">Cliente</th>
            <th align="center" scope="col">Placa</th>
            <th align="center" scope="col">Marca</th>
            <th align="center" scope="col">Kilometraje de Ingreso</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reporteVehiculares as $reporteVehicular)
            <tr>
                <td width="20" align="center">{{ $reporteVehicular->FechaAtencion }}</td>
                <td width="80" align="center">{{ $reporteVehicular->Articulo }}</td>
                <td width="30" align="center">{{ $reporteVehicular->Detalle }}</td>
                <td width="20" align="center">{{ $reporteVehicular->CodigoBarra }}</td>
                <td width="20" align="center">{{ $reporteVehicular->Documento }}</td>
                <td width="40" align="center">{{ $reporteVehicular->Cliente }}</td>
                <td width="20" align="center">{{ $reporteVehicular->PlacaVehiculo }}</td>
                <td width="30" align="center">{{ $reporteVehicular->NombreMarca }}</td>
                <td width="30" align="center">{{ $reporteVehicular->Kilometro }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
