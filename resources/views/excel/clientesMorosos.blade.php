<table id="table" class="table table-responsive-sm" style="width:100%">
                                        <thead>
                                            <tr class="bg-primary">
                                                <th scope="col" align="center">Cliente</th>
                                                <th scope="col" align="center">Documento</th>
                                                <th scope="col" align="center">Última Fecha Pagada</th>
                                                <th scope="col" align="center">Tipo Moneda</th>
                                                <th scope="col" align="center">Importe Inicial</th>
                                                <th scope="col" align="center">Deuda Actual</th>
                                                <th scope="col" align="center">Días Atrasados</th>
                                                <th scope="col" align="center">Estado</th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                                @foreach($clientesMorosos as $clienteMoroso)
                                                <tr>
                                                    <td width="50" align="center">{{$clienteMoroso->Cliente}}</td>
													<td width="25" align="center">{{$clienteMoroso->Serie.'-'.$clienteMoroso->Numero}} {{$clienteMoroso->Nota == 1 ? ' : N. Credito'  :  ''}} </td>
                                                    <td width="18" align="center">{{$clienteMoroso->FechaPago}}</td>
                                                    <td width="18" align="center">{{$clienteMoroso->IdTipoMoneda == 1 ? 'Soles': 'Dólares'}}</td>
                                                    <td width="18" align="center">{{$clienteMoroso->Importe}}</td>
                                                    <td width="20" align="center">{{$clienteMoroso->Deuda}}</td>
                                                    <td width="20" align="center">{{$clienteMoroso->DiasPasados}}</td>
                                                    <td width="20" align="center"><span class="badge fs-11 text-white" style="background-color: {{$clienteMoroso->Color}}">{{$clienteMoroso->NombreEstado}}</span></td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                    </table>