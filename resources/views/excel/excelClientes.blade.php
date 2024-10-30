{{-- <table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th align="center" scope="col" style="font-weight:bold">Nombre</th>
            <th align="center" scope="col" style="font-weight:bold">Razón Social</th>
            <th align="center" scope="col" style="font-weight:bold">Documento</th>
            <th align="center" scope="col" style="font-weight:bold">Teléfono</th>
            <th align="center" scope="col" style="font-weight:bold">Email</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($clientes as $cliente)
            <tr>
                <td width="60" align="center">{{ $cliente->Nombre }}</td>
                <td width="60" align="center">{{ $cliente->RazonSocial }}</td>
                <td width="20" align="center">{{ $cliente->Descripcion }}: {{ $cliente->NumeroDocumento }}</td>
                <td width="15" align="center">{{ $cliente->Telefono }}</td>
                <td width="20" align="center">{{ $cliente->Email }}</td>
            </tr>
        @endforeach
    </tbody>
</table> --}}

{{-- <table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th align="center" scope="col" style="font-weight:bold">Nombre</th>
            <th align="center" scope="col" style="font-weight:bold">Razón Social</th>
            <th align="center" scope="col" style="font-weight:bold">Documento</th>
            <th align="center" scope="col" style="font-weight:bold">Ubigeo</th>
            <th align="center" scope="col" style="font-weight:bold">Dirección</th>
            <th align="center" scope="col" style="font-weight:bold">Teléfono</th>
            <th align="center" scope="col" style="font-weight:bold">Email</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($clientes as $cliente)
            <tr>
                <td width="60" align="center">{{ $cliente->Nombre }}</td>
                <td width="60" align="center">{{ $cliente->RazonSocial }}</td>
                <td width="40" align="center">{{ $cliente->Descripcion }}: {{ $cliente->NumeroDocumento }}</td>
                <td width="20" align="center">{{ $cliente->Ubigeo }}</td>
                <td width="70" align="center">{{ $cliente->Direccion }}</td>
                <td width="15" align="center">{{ $cliente->Telefono }}</td>
                <td width="30" align="center">{{ $cliente->Email }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
 --}}

<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th align="center" scope="col" style="font-weight:bold">Nombre</th>
            <th align="center" scope="col" style="font-weight:bold">Razón Social</th>
            <th align="center" scope="col" style="font-weight:bold">Número de Documento</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo Documento</th>
            <th align="center" scope="col" style="font-weight:bold">Ubigeo</th>
            <th align="center" scope="col" style="font-weight:bold">Distrito</th>
            <th align="center" scope="col" style="font-weight:bold">Dirección</th>
            <th align="center" scope="col" style="font-weight:bold">Teléfono</th>
            <th align="center" scope="col" style="font-weight:bold">Email</th>
            <th align="center" scope="col" style="font-weight:bold">Contacto</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($clientes as $cliente)
            <tr>
                <td width="60" align="center">{{ $cliente->Nombre }}</td>
                <td width="60" align="center">{{ $cliente->RazonSocial }}</td>
                <td width="25" align="center">{{ $cliente->NumeroDocumento }}</td>
                <td width="25" align="center">{{ $cliente->Descripcion }}</td>
                <td width="20" align="center">{{ $cliente->Ubigeo }}</td>
                <td width="20" align="center">{{ $cliente->Distrito }}</td>
                <td width="70" align="center">{{ $cliente->Direccion }}</td>
                <td width="15" align="center">{{ $cliente->Telefono }}</td>
                <td width="30" align="center">{{ $cliente->Email }}</td>
                <td width="60" align="center">{{ $cliente->PersonaContacto }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
