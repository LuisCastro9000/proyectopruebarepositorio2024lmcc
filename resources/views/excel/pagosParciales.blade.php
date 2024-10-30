<table id="table" class="table table-responsive-sm" style="width:100%">
                                        <thead>
                                            <tr class="bg-primary"> 
                                                <th scope="col" align="center">Cliente</th>    
                                                <th scope="col" align="center">Documento</th>    
                                                <th scope="col" align="center">Ult. Fecha Pagada</th>
                                                <th scope="col" align="center">Tipo Moneda</th>
                                                <th scope="col" align="center">Importe Inicial</th>
                                                <th scope="col" align="center">Importe Parcial Pagado</th>
                                                <th scope="col" align="center">Monto Efectivo</th>
                                                <th scope="col" align="center">Monto con Tarjeta</th>
                                                <th scope="col" align="center">Monto Cuenta Banc.</th>
                                                <th scope="col" align="center">Días Atrasados</th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                                @foreach($pagosParciales as $pagoParcial)
                                                <tr>
                                                    <td width="50" align="center">{{$pagoParcial->Cliente}}</td>
													<td width="20" align="center">{{$pagoParcial->Serie.'-'.$pagoParcial->Numero}} {{$pagoParcial->Nota == 1 ? ' : N. Credito'  :  ''}} </td>
                                                    <td width="15" align="center">{{$pagoParcial->FechaPago}}</td>
                                                    <td width="15" align="center">{{$pagoParcial->IdTipoMoneda == 1 ? 'Soles': 'Dólares'}}</td>
                                                    <td width="20" align="center">{{$pagoParcial->Importe}}</td>
                                                    <td width="20" align="center">{{$pagoParcial->ImportePagado}}</td>
                                                    <td width="20" align="center">{{$pagoParcial->MontoEfectivo}}</td>
                                                    <td width="18" align="center">{{$pagoParcial->MontoTarjeta}}</td>
                                                    <td width="20" align="center">{{$pagoParcial->CuentaBancaria == null ? '0.00': $pagoParcial->CuentaBancaria}}</td>
                                                    <td width="18" align="center">{{$pagoParcial->DiasAtrasados}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                    </table>