<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th align="center" scope="col">Fecha y Hora de Ingreso</th>
            <th align="center" scope="col">Cliente</th>
            <th align="center" scope="col">Placa</th>
            <th align="center" scope="col">Marca</th>
            <th align="center" scope="col">Modelo</th>
            <th align="center" scope="col">Color</th>
            <th align="center" scope="col">Año</th>
            <th align="center" scope="col">Venc. SOAT</th>
            <th align="center" scope="col">Venc. Rev. Téc.</th>
            <th align="center" scope="col">Certif. Anual</th>
            <th align="center" scope="col">Prueba Quinquenal</th>
            <th align="center" scope="col">Numero Flota</th>
            <th align="center" scope="col">Motor</th>
            <th align="center" scope="col">Chasis / VIN</th>
        </tr>
    </thead>
        <tbody>
            @foreach($reporteVehiculos as $reporteVehicular)
            <tr>
                <td width="30" align="center">{{$reporteVehicular->FechaIngreso}}</td>
                <td width="60" align="center">{{$reporteVehicular->Nombre}}</td>
                <td width="15" align="center">{{$reporteVehicular->PlacaVehiculo}}</td>
                <td width="20" align="center">{{$reporteVehicular->NombreMarca}}</td>
                <td width="20" align="center">{{$reporteVehicular->NombreModelo}}</td>
                <td width="15" align="center">{{$reporteVehicular->Color}}</td>
                <td width="15" align="center">{{$reporteVehicular->Anio}}</td>
                <td width="20" align="center">{{$reporteVehicular->FechaSoat}}</td>
                <td width="20" align="center">{{$reporteVehicular->FechaRevTecnica}}</td>
                <td width="20" align="center">{{$reporteVehicular->CertificacionAnual}}</td>
                <td width="20" align="center">{{$reporteVehicular->PruebaQuinquenal}}</td>
                <td width="20" align="center">{{$reporteVehicular->NumeroFlota}}</td>
                <td width="25" align="center">{{$reporteVehicular->Motor}}</td>
                <td width="30" align="center">{{$reporteVehicular->ChasisVehiculo}}</td>
            </tr>
            @endforeach
        </tbody>
</table>