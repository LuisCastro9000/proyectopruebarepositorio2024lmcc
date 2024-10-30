<table id="table" class="table table-responsive-sm" style="width:100%">
     <thead>
         <tr class="bg-primary">
              <th align="center" scope="col" style="font-weight:bold">Cliente</th>
              <th align="center" scope="col" style="font-weight:bold">Documento</th>
              <th align="center" scope="col" style="font-weight:bold">Telefono</th>
              <th align="center" scope="col" style="font-weight:bold">Correo</th>
             <th align="center" scope="col" style="font-weight:bold">Tipo de Pago</th>
             <th align="center" scope="col" style="font-weight:bold">Total de Ventas</th>
         </tr>
     </thead>

         <tbody>
             @foreach( $reporteClientesTop as $reporte)
             <tr>
                 <td width="50" align="center">{{$reporte->Nombre}}</td>
                 <td width="20" align="center">{{$reporte->NumeroDocumento}}</td>
                 <td width="20" align="center">{{$reporte->Telefono}}</td>
                 <td width="20" align="center">{{$reporte->Email}}</td>
                 @if($reporte->IdTipoPago == 1)
                     <td  width="20" align="center">Contado</td>
                 @else
                     <td width="20" align="center">Crédito</td>
                 @endif
                 <td width="20" align="center">{{$reporte->Cantidad}}</td>
             </tr>
             @endforeach
         </tbody>       
 </table>