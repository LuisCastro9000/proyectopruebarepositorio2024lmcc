<table id="table" class="table table-responsive-sm" style="width:100%">
                                        <thead>
                                            <tr class="bg-primary">  
                                                <th scope="col" align="center">Cliente</th>    
                                                <th scope="col" align="center">Documento</th>    
                                                <th scope="col" align="center">Fecha Cancelado</th>
                                                <th scope="col" align="center">Tipo Moneda</th>
                                                <th scope="col" align="center">Importe Cancelado</th>
												<th scope="col" align="center">Monto Efectivo</th>
												<th scope="col" align="center">Tarjeta</th>
                                                <th scope="col" align="center">Monto con Tarjeta</th>
                                                <th scope="col" align="center">Monto Cuenta Banc.</th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                                @foreach($pagosTotales as $pagoParcial)
                                                <tr>
                                                    <td width="50" align="center">{{$pagoParcial->Cliente}}</td>
                                                    <td width="20" align="center">{{$pagoParcial->Serie.'-'.$pagoParcial->Numero}} {{$pagoParcial->Nota == 1 ? ' : N. Credito'  :  ''}}</td>
                                                    <td width="20" align="center">{{$pagoParcial->FechaPago}}</td>
                                                    <td width="18" align="center">{{$pagoParcial->IdTipoMoneda == 1 ? 'Soles': 'Dólares'}}</td>
                                                    <td width="20" align="center">{{$pagoParcial->ImportePagado}}</td>
													<td width="20" align="center">{{$pagoParcial->MontoEfectivo}}</td>
													<td width="18" align="center">
														@if($pagoParcial->NumeroTarjeta  > 0)
                                                            @if($pagoParcial->IdTipoTarjeta  == 1)
													    		Visa
                                                            @elseif ($pagoParcial->IdTipoTarjeta  == 2)
                                                                MasterCard
     												    	@else
													    		-
                                                            @endif
														@else
															-
														@endif	

													</td>
                                                    <td width="18" align="center">{{$pagoParcial->MontoTarjeta}}</td>
                                                    <td width="18" align="center">{{$pagoParcial->CuentaBancaria == null ? '0.00': $pagoParcial->CuentaBancaria}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                    </table>