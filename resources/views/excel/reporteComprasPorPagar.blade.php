<table id="table" class="table table-responsive-sm" style="width:100%">
                <thead>
                           <tr class="bg-primary">
                               <th scope="col">Fecha Emitida</th>
 							   <th scope="col">Fecha Emitida</th>
                               <th scope="col">Cliente</th>
                               <th scope="col">Documento</th>
                               <th scope="col">Importe Inicial</th>
                               <th scope="col">Deuda Actual</th>
                               <th scope="col">Tiempo de Pago(Dias)</th>
                               <th scope="col">Dias transcurridos</th>
                               <th scope="col">Estado</th>
                           </tr>
                </thead>
                                       <tbody>
						@php $cont=0; @endphp
                                          @foreach($cobranzas as $cobranza)
								@foreach($fecha_pago as $abono)
                                                   @if($abono->Estado==1)
								    	@if($cobranza->IdVentas == $abono->IdVenta)
								    		@php $cont++ @endphp
								    		<tr>
								    			<td>{{$cobranza->FechaCreacion}}</td>
								    			<td>{{$cobranza->FechaCreacion}}</td>
                                                               <td>{{$cobranza->Cliente}}</td>
                                                               <td>{{$cobranza->Serie.'-'.$cobranza->Numero}}</td>
                                                               <td class="text-center">{{$cobranza->Total}}</td>
                                                               <td class="text-center">{{ number_format($cobranza->Total - $abono->ImportePagado, 2, '.', '') }}</td>
                                                               <td class="text-center">{{ $cobranza->Dias}}</td>
                                                               <td class="text-center">{{ abs($cobranza->Dias+$abono->DiasPasados) }}</td>
                                                               <td>{{$cobranza->Nota == 1 ? 'N. Credito': $cobranza->Estado}}</td>
								    		</tr>	
								    	@endif	
								    @endif	
                                           	@endforeach
					
								@php $cont=0 @endphp		
                                           @endforeach
                                       </tbody>
                               </table>