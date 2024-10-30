<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th align="center" scope="col" style="font-weight:bold">FECHA INGRESO</th>
            <th align="center" scope="col" style="font-weight:bold">CLIENTE</th>
            <th align="center" scope="col" style="font-weight:bold">PLACA</th>
            <th align="center" scope="col" style="font-weight:bold">TIPO ATENCION</th>
            <th align="center" scope="col" style="font-weight:bold">ESTADO</th>
            <th align="center" scope="col" style="font-weight:bold">CELULAR</th>
            <th align="center" scope="col" style="font-weight:bold">MECÁNICO</th>
            <th align="center" scope="col" style="font-weight:bold">TRABAJOS</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datosExportar as $dato)
            <tr>
                <td width="30" align="center">{{ $dato->FechaCreacion }}</td>
                <td width="60" align="center">{{ $dato->RazonSocial }}</td>
                <td width="20" align="center">{{ $dato->PlacaVehiculo }}</td>
                <td width="40" align="center">{{ $dato->TipoAtencion }}</td>
                <td width="40" align="center"
                    style="background-color: {{ $datosOpcionales[$dato->IdEstadoCotizacion]['color'] }}">
                    {{ $datosOpcionales[$dato->IdEstadoCotizacion]['estado'] }}
                </td>
                <td width="20" align="center">{{ $dato->NumeroCelular }}</td>
                <td width="40" align="center">{{ $dato->NombreOperario }}</td>
                <td width="60" align="center">{{ $dato->Trabajos }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
