	 <table width="100%" class="tabla3">
                                        <thead>
                                            <tr class="bg-primary">
                                                <th class="borderTabla" align="center">Fecha</th>
                                                <th class="borderTabla" align="center">Tipo</th>
                                                <th class="borderTabla" align="center">Usuario</th>
                                                <th class="borderTabla" align="center">Documento</th>
												<th class="borderTabla" align="center">Costo</th>
												<th class="borderTabla" align="center">Entrada</th>
                                                <th class="borderTabla" align="center">Salida</th>
                                                <th class="borderTabla" align="center">Impor. Entrada</th>
                                                <th class="borderTabla" align="center">Impor. Salida</th>
                                            </tr>
                                        </thead>
                                            <tbody>
											   <?php $bandExis=0; 
											   		$entrada=0;
													$salida=0;
													$imp_salida=0;
													$imp_entrada=0;
                                               		foreach($reporteKardex as $kardex)
													{
														if($kardex->Tipo == 1)	
														{
															$salida=$salida + $kardex->Cantidad;
															$imp_salida=$imp_salida+ $kardex->Costo * $kardex->Cantidad;
															$existencia=$existencia-$kardex->Cantidad;
														}
														elseif($kardex->Tipo==2)
														{
															$entrada=$entrada + $kardex->Cantidad;
															$imp_entrada=$imp_entrada + $kardex->Costo * $kardex->Cantidad;
															$existencia=$existencia+$kardex->Cantidad;
														}
														elseif($kardex->Tipo==3)
														{
															$entrada=$entrada + $kardex->Cantidad;
															$existencia=$existencia+$kardex->Cantidad;
														}
														elseif($kardex->Tipo==4)
														{
															$existencia=$existencia-$kardex->Cantidad;
															$salida=$salida + $kardex->Cantidad;
														}
														elseif($kardex->Tipo==5)
														{
															$salida=$salida + $kardex->Cantidad;
															$existencia=$existencia-$kardex->Cantidad;
														}
														elseif($kardex->Tipo==7 || $kardex->Tipo==6)
														{
															$entrada=$entrada + $kardex->Cantidad;
															
															$existencia=$existencia+$kardex->Cantidad;
														}
														elseif($kardex->Tipo==8 || $kardex->Tipo==9 || $kardex->Tipo==10 || $kardex->Tipo==11)
														{
															$entrada=$entrada + $kardex->Cantidad;
                                                            $existencia=$existencia;
														}
												?>	
												<tr>
                                                    <td class="borderTabla" width="20" align="center">{{$kardex->Fecha}}</td>
                                                    <td class="borderTabla" width="25" align="center">
														@if($kardex->Tipo==8 || $kardex->Tipo==9 || $kardex->Tipo==10 || $kardex->Tipo==11)
																<span>Inicial</span>
														@elseif($kardex->Tipo==1)
																<span>Venta</span>
														@elseif($kardex->Tipo==2)
																<span>Compra</span>
														@elseif($kardex->Tipo==3 || $kardex->Tipo==5)
																<span>Traspaso</span>
														@elseif($kardex->Tipo==4)
																<span>Baja Producto</span>
														@elseif($kardex->Tipo==7)
																<span>N. Credito</span>
														@elseif($kardex->Tipo==6)
																<span>Baja Documento</span>
														@endif
													</td>
                                                    <td class="borderTabla" width="40" align="center">{{$kardex->Nombre}}</td>
                                                    <td class="borderTabla" width="25" align="center">
														@if($kardex->Tipo==8)
															Inventario Inicial
														@elseif($kardex->Tipo==9)
															Importación Excel
														@elseif($kardex->Tipo==10)
															Sucursal Inicial
														@elseif($kardex->Tipo==11)
															Traspaso Inicial
														@else
															{{$kardex->Serie.'-'.$kardex->Numero}}
														@endif
													</td>
                                                    <td class="borderTabla" width="15" align="center">{{$kardex->Costo}}</td>
													<td class="borderTabla" width="15" align="center">
														@if($kardex->Tipo==1 || $kardex->Tipo==4 || $kardex->Tipo==5)
																0
														@elseif($kardex->Tipo==2 || $kardex->Tipo==3 || $kardex->Tipo==6 || $kardex->Tipo==7)
															{{ $kardex->Cantidad }}
														@elseif($kardex->Tipo==8 || $kardex->Tipo==9 || $kardex->Tipo==10 || $kardex->Tipo==11)
															{{ $entrada }}
														@endif
													</td>
													<td class="borderTabla" width="15" align="center">
														@if($kardex->Tipo==1)
															{{ $kardex->Cantidad }}
														@elseif($kardex->Tipo==2)
															0
														@elseif($kardex->Tipo==3)
															0
														@elseif($kardex->Tipo==4 || $kardex->Tipo==5)
															{{ $kardex->Cantidad }}
														@elseif($kardex->Tipo==8 || $kardex->Tipo==9 || $kardex->Tipo==10 || $kardex->Tipo==11)
															{{$salida}}
														@else
															0
														@endif
													</td>
                                                    <td class="borderTabla" width="15" align="center">{{ $kardex->Tipo == 2 ? $kardex->Costo * $kardex->Cantidad : '0.00' }}</td>
                                                    <td class="borderTabla" width="15" align="center">{{ $kardex->Tipo == 1 ? $kardex->Total : '0.00'}}</td>
                                                </tr>
												<?php
												
													}
												?>
												<tr>
													 <td colspan="5"><span class="">Totales: </span></td>
													 <td align="center">{{ $entrada }}</td>
													 <td align="center">{{ $salida}}</td>
													 <td align="center">{{$imp_entrada}}</td>
													 <td align="center">{{$imp_salida}}</td>
												</tr> 
                                            </tbody>
                                    </table>