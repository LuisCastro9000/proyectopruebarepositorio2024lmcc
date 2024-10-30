<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th scope="col">Fecha de Emisión</th>
            <th scope="col">Tipo de Comprobante de Pago o Documento</th>
            <th scope="col">Serie de Compobante de Pago o Documento</th>
            <th scope="col">Numero de Comprobante de pago o Documento</th>
            <th scope="col">Apellido Y Nombre Denominacion o Razón Social</th>
            <th scope="col">Documento Receptor</th>
            <th scope="col">Estado</th>
            <th scope="col">Cod. Error</th>
            <th scope="col">Tipo Pago</th>
            <th scope="col">Tipo Operación</th>
            <th scope="col">Fecha Vencimiento</th>
            <th scope="col">Base Imponible</th>
            <th scope="col">IGV</th>
            <th scope="col">Importe Total</th>
            <th scope="col">Codigo Moneda</th>
            <th scope="col">Serie de ref del Comprobante de Pago o Documento Original que se Modifica</th>
            <th scope="col">Numero de ref del  Comprobante de Pago o Documento Original que se modifica</th>
        </tr>
    </thead>     
    <tbody>
        @foreach($facturasVentas as $factura)
        <tr>
            <td width="20" align="center">{{$factura->FechaCreacion}}</td>
            <td width="50" align="center">{{$factura->Documento}}</td>
            <td width="50" align="center">{{$factura->Serie}}</td>
            <td width="50" align="center">{{$factura->Numero}}</td>
            <td width="55">{{$factura->RazonSocial}}</td>
            <td width="30" align="center">{{$factura->DocumentoReceptor}}</td>
            <td width="25" align="center">{{$factura->Estado}}</td>
            <td width="20" align="center">{{$factura->CodError}}</td>
            <td width="20">{{$factura->TipoPago}}</td>
            <td width="20">{{$factura->TipoOperacion}}</td>
            <td width="20" align="center">{{$factura->FechaVencimiento}}</td>
            <td width="20" align="center">{{$factura->BaseImponible}}</td>
            <td width="15" align="center">{{$factura->IGV}}</td>
            <td width="20" align="center">{{$factura->ImporteTotal}}</td>
            <td width="20" align="center">{{$factura->CodigoMoneda}}</td>
            <td width="80">{{$factura->SerieRef}}</td>
            <td width="80">{{$factura->NumeroRef}}</td>
        </tr>
        @endforeach
    </tbody>       
</table>