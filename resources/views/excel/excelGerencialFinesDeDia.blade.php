<table id="table" class="table table-responsive-sm" style="width:100%">
     <thead>
         <tr class="bg-primary">
              <th align="center" scope="col" style="font-weight:bold">Vendedor</th>
             <th align="center" scope="col" style="font-weight:bold">Apertura de caja</th>
             <th align="center" scope="col" style="font-weight:bold">Cierre de caja</th>
             <th align="center" scope="col" style="font-weight:bold">Efectivo Inicial</th>
             <th align="center" scope="col" style="font-weight:bold">Efectivo Contado</th>
             <th align="center" scope="col" style="font-weight:bold">Efectivo Cobranzas</th>
             <th align="center" scope="col" style="font-weight:bold">Ingresos</th>
             <th align="center" scope="col" style="font-weight:bold">Egresos</th>
             <th align="center" scope="col" style="font-weight:bold">Total Efectivo</th>
             <th align="center" scope="col" style="font-weight:bold">Total TC</th>
             <th align="center" scope="col" style="font-weight:bold">Total Transferencia Bancaria</th>
         </tr>
     </thead>
         <tbody>
             @foreach( $reporteFinesDia as $reporte)
             <tr>
                  <td width="30" align="center">{{$reporte->Usuario}}</td>
                  <td width="22" align="center">{{$reporte->FechaApertura}}</td>
                  <td width="22" align="center">{{$reporte->FechaCierre}}</td>
                  <td width="20" align="center">@if($idTipoMoneda == 1)
                                                        {{$reporte->Inicial}}
                                                        @else
                                                        {{$reporte->InicialDolares}}
                                                        @endif</td>
                  <td width="20" align="center">{{$reporte[0]->Efectivo}}</td>
                  <td width="20" align="center">{{$reporte->EfectivoCobranzas}}</td>
                  <td width="20" align="center">{{$reporte->MontoIngreso}}</td>
                  <td width="20" align="center">{{$reporte->MontoEgreso}}</td>
                  <td width="20" align="center">{{$reporte->TotalEfectivo}}</td>
                  <td width="20" align="center">{{$reporte->Totaltarjeta}}</td>
                  <td width="40" align="center">{{$reporte[0]->CuentaBancaria}}</td>
             </tr>
             @endforeach
         </tbody>       
 </table>