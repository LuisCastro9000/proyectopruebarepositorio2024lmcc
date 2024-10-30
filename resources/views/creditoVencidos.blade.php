<table id="table" class="table table-responsive-sm" style="width:100%">
                                        <thead>
                                            <tr class="bg-primary">
                                                <th scope="col">Cliente</th>
                                                <th scope="col">Documento</th>
                                                <th scope="col">Fecha Emision</th>
                                                <th scope="col">Fecha Vencimiento</th>
                                                <th scope="col">Ultima Fecha Pagada</th>
                                                <th scope="col">Tipo Moneda</th>
                                                <th scope="col">Importe</th>
                                                <th scope="col">Deuda</th>
                                                <th scope="col">Dias Atrasados</th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                                @foreach($creditosVencidos as $creditoVencido)
                                                <tr>
                                                    <td>{{$creditoVencido->Cliente}}</td>
													<td>{{$creditoVencido->Serie.'-'.$creditoVencido->Numero}}
														@if($creditoVencido->Nota == 1)
															{{'N. Credito'}}
														@elseif(!is_null($creditoVencido->MotivoAnulacion))
															{{'Doc. Baja'}}
														@else
															{{ '' }}
														@endif
													</td>
                                                    <td>{{$creditoVencido->FechaInicio}}</td>
                                                    <td>{{$creditoVencido->FechaUltimo}}</td>
                                                    <td>{{$creditoVencido->FechaPago}}</td>
                                                    <td>{{$creditoVencido->IdTipoMoneda == 1 ? 'Soles': 'Dólares'}}</td>
                                                    <td>{{$creditoVencido->Importe}}</td>
                                                    <td>{{$creditoVencido->Deuda}}</td>
                                                    <td>{{$creditoVencido->DiasPasados}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                    </table