<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th scope="col">Fecha Emitida</th>
            <th scope="col">Proveedor</th>
            <th scope="col">Documento</th>
            <th scope="col">Tipo Moneda</th>
            <th scope="col">Importe Inicial</th>
            <th scope="col">Deuda Actual</th>
            <th scope="col">Tiempo de Pago (Días)</th>
            <th scope="col">Dias transcurridos</th>
        </tr>
    </thead>

    <tbody>
        @foreach($pagos as $pago)
                @if($pago->TipoEstado==1)
                        <tr>
                            <td width="20" align="center">{{$pago->FechaCreacion}}</td>
                            <td width="40">{{$pago->Proveedor}}</td>
                            <td width="20" align="center">{{$pago->Serie.'-'.$pago->Numero}}</td>
                            <td width="20">{{$pago->IdTipoMoneda == 1 ? 'Soles' : 'Dólares'}}</td>
                            <td width="20" align="center">{{$pago->Total}}</td>
                            <td width="20" align="center">{{ number_format($pago->Total - $pago->ImportePagado, 2, '.', '') }}</td>
                            <td width="25" align="center">{{ $pago->Dias}}</td>
                            <td width="20" align="center">{{ $pago->DiasPasados }}</td>
                        </tr>
                @endif
        @endforeach
    </tbody>
</table>