 <table id="table" class="table table-responsive-sm" style="width:100%">
     <thead>
         <tr class="bg-primary">
             <th align="center" scope="col" style="font-weight:bold">Fecha Emitida</th>
             <th align="center" scope="col" style="font-weight:bold">Vendedor</th>
             <th align="center" scope="col" style="font-weight:bold">Cliente</th>
             <th align="center" scope="col" style="font-weight:bold">Código</th>
             <th align="center" scope="col" style="font-weight:bold">Código Cotización</th>
             <th align="center" scope="col" style="font-weight:bold">DocumentoCliente</th>
             <th align="center" scope="col" style="font-weight:bold">Tipo Pago</th>
             <th align="center" scope="col" style="font-weight:bold">Tipo Venta</th>
             <th align="center" scope="col" style="font-weight:bold">Tipo Moneda</th>
             <th align="center" scope="col" style="font-weight:bold">Total Efectivo</th>
             <th align="center" scope="col" style="font-weight:bold">Total Tarjeta</th>
             <th align="center" scope="col" style="font-weight:bold">Cuentas Corrientes</th>
             <th align="center" scope="col" style="font-weight:bold">Total Pago</th>
             <th align="center" scope="col" style="font-weight:bold">Amortizacion</th>
             <th align="center" scope="col" style="font-weight:bold">Descuento</th>
             <th align="center" scope="col" style="font-weight:bold">Estado</th>
         </tr>
     </thead>
     <tbody>
         @foreach ($reporteVendedores as $reportVendedor)
             <tr>
                 <td width="20" align="center">{{ $reportVendedor->FechaCreacion }}</td>
                 <td width="30" align="center">{{ $reportVendedor->Usuario }}</td>
                 <td width="60" align="center">{{ $reportVendedor->Nombres }}</td>
                 <td width="20" align="center">{{ $reportVendedor->Serie }} - {{ $reportVendedor->Numero }}</td>
                 <td width="25" align="center">{{ $reportVendedor->codigoCotizacion }}</td>
                 <td width="25" align="center">{{ $reportVendedor->Documento }}</td>
                 @if ($reportVendedor->IdTipoPago == 1)
                     <td width="15" align="center">Contado</td>
                 @else
                     <td width="15" align="center">Crédito</td>
                 @endif
                 <td width="15" align="center">{{ $reportVendedor->TipoVenta == 1 ? 'Gravada' : 'Exonerada' }}</td>
                 <td width="15" align="center">{{ $reportVendedor->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                 <td width="20" align="center">{{ $reportVendedor->MontoEfectivo }}</td>
                 <td width="20" align="center">{{ $reportVendedor->MontoTarjeta }}</td>
                 <td width="20" align="center">{{ $reportVendedor->MontoCuentaBancaria }}</td>
                 <td width="20" align="center">{{ $reportVendedor->Total }}</td>
                 <td width="20" align="center">{{ $reportVendedor->Amortizacion }}</td>
                 <td width="15" align="center">{{ $reportVendedor->Exonerada }}</td>
                 <td width="20" align="center">{{ $reportVendedor->Estado }}</td>
             </tr>
         @endforeach
     </tbody>
 </table>
