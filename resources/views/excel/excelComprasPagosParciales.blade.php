<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th scope="col">Proveedor</th>    
            <th scope="col">Documento</th>    
            <th scope="col">Últ. Fecha Pagada</th>
            <th scope="col">Tipo Moneda</th>
            <th scope="col">Importe Inicial</th>
            <th scope="col">Importe Parcial Pagado</th>
            <th scope="col">Monto Efectivo</th>
            <th scope="col">Monto Cuenta Bancaria</th>
            <th scope="col">Días Atrasados</th>
        </tr>
    </thead>
        <tbody>
            @foreach($pagosParciales as $pagoParcial)
            <tr>
                <td width="40">{{$pagoParcial->Proveedor}}</td>
                <td width="20" align="center">{{$pagoParcial->Serie.'-'.$pagoParcial->Numero}}</td>
                <td width="20" align="center">{{$pagoParcial->FechaPago}}</td>
                <td width="20">{{$pagoParcial->IdTipoMoneda == 1 ? 'Soles' : 'Dólares'}}</td>
                <td width="20" align="center">{{$pagoParcial->Importe}}</td>
                <td width="25" align="center">{{$pagoParcial->ImportePagado}}</td>
                <td width="20" align="center">{{$pagoParcial->MontoEfectivo}}</td>
                <td width="25" align="center">{{$pagoParcial->MontoBanco}}</td>
                <td width="20" align="center">{{$pagoParcial->DiasAtrasados}}</td>
            </tr>
            @endforeach
        </tbody>
</table>