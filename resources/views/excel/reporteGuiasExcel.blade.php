<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th align="center" scope="col" style="font-weight:bold">Fecha Emitida</th>
            <th align="center" scope="col" style="font-weight:bold">Cliente</th>
            <th align="center" scope="col" style="font-weight:bold">Documento Relacionado</th>
            <th align="center" scope="col" style="font-weight:bold">Nro. Guia</th>
            <th align="center" scope="col" style="font-weight:bold">Estado</th>
            <th align="center" scope="col" style="font-weight:bold">Cód. Error</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reporteClientes as $reportCliente)
            <tr>
                <td width="20" align="center">{{ $reportCliente->FechaEmision }}</td>
                <td width="60" align="center">{{ $reportCliente->RazonSocial }}</td>
                <td width="30" align="center">{{ $reportCliente->DocumentoVenta }}</td>
                <td width="30" align="center">{{ $reportCliente->Serie }} - {{ $reportCliente->Numero }}</td>
                <td width="15" align="center">{{ $reportCliente->Estado }}</td>
                <td width="15" align="center">
                    {{ $reportCliente->codigoError == 0 ? '' : $reportCliente->codigoError }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
