<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th align="center" scope="col" style="font-weight:bold">Nombre</th>
            <th align="center" scope="col" style="font-weight:bold">Razóm Social</th>
            <th align="center" scope="col" style="font-weight:bold">Banco</th>
            <th align="center" scope="col" style="font-weight:bold">Cuenta Corriente</th>
            <th align="center" scope="col" style="font-weight:bold">Documento</th>
            <th align="center" scope="col" style="font-weight:bold">Teléfono</th>
            <th align="center" scope="col" style="font-weight:bold">Email</th>
            <th align="center" scope="col" style="font-weight:bold">Contacto</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($proveedores as $proveedor)
            <tr>
                <td width="60" align="center">{{ $proveedor->Nombre }}</td>
                <td width="60" align="center">{{ $proveedor->RazonSocial }}</td>
                <td width="40" align="center">{{ $proveedor->NombreBanco }}</td>
                <td width="30" align="center">{{ $proveedor->CuentaCorriente }}</td>
                <td width="20" align="center">{{ $proveedor->Descripcion }}: {{ $proveedor->NumeroDocumento }}</td>
                <td width="15" align="center">{{ $proveedor->Telefono }}</td>
                <td width="40" align="center">{{ $proveedor->Email }}</td>
                <td width="60" align="center">{{ $proveedor->PersonaContacto }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
