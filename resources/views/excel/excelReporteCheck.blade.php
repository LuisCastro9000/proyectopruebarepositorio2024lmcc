<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr>
            <th align="center" scope="col" style="font-weight:bold">FechaEmision</th>
            <th align="center" scope="col" style="font-weight:bold">Asesor Comercial</th>
            <th align="center" scope="col" style="font-weight:bold">Cliente</th>
            <th align="center" scope="col" style="font-weight:bold">Celular</th>
            <th align="center" scope="col" style="font-weight:bold">Ruc</th>
            <th align="center" scope="col" style="font-weight:bold">Codigo</th>
            <th align="center" scope="col" style="font-weight:bold">Placa</th>
            <th align="center" scope="col" style="font-weight:bold">TipoVehículo</th>
            <th align="center" scope="col" style="font-weight:bold">Kilometraje</th>
            <th align="center" scope="col" style="font-weight:bold">Nivel de Gasolina</th>
            <th align="center" scope="col" style="font-weight:bold">Año</th>
            <th align="center" scope="col" style="font-weight:bold">Marca</th>
            <th align="center" scope="col" style="font-weight:bold">Modelo</th>
            <th align="center" scope="col" style="font-weight:bold">Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reporteCheck as $inventario)
            <tr>
                <td width="22" align="center">{{ $inventario->FechaEmision }}</td>
                <td width="50" align="center">{{ $inventario->Nombre }}</td>
                <td width="65" align="center">{{ $inventario->RazonSocial }}</td>
                <td width="20" align="center">{{ $inventario->Telefono }}</td>
                <td width="20" align="center">{{ $inventario->NumeroDocumento }}</td>
                <td width="20" align="center">{{ $inventario->Serie }}-{{ $inventario->Correlativo }}</td>
                <td width="15" align="center">{{ $inventario->Placa }}</td>
                <td width="15" align="center">{{ $inventario->TipoVehiculo == 1 ? 'Vehículo' : 'Moto' }}</td>
                <td width="20" align="center">{{ $inventario->Kilometraje }}</td>
                <td width="20" align="center">{{ $inventario->NivelGasolina }}%</td>
                <td width="20" align="center">{{ $inventario->AñoVehiculo }}</td>
                <td width="20" align="center">{{ $inventario->NombreMarca }}</td>
                <td width="20" align="center">{{ $inventario->NombreModelo }}</td>
                <td width="15" align="center"
                    style="background-color: {{ $inventario->Estado == 'Baja' ? '#FF3333;color: #ffffff' : '#06D6A0;color: #ffffff' }}">
                    {{ $inventario->Estado }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
