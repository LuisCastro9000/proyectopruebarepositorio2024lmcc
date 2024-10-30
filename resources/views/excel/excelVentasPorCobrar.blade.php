<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th align="center">Fecha Emitida</th>
            <th align="center">Cliente</th>
            <th align="center">Documento</th>
            <th align="center">Tipo Venta</th>
            <th align="center">Tipo Moneda</th>
            <th align="center">Importe Inicial</th>
            <th align="center">Deuda Actual</th>
            <th align="center">Tiempo de Pago (Días)</th>
            <th align="center">Dias transcurridos</th>
            <th align="center">Estado</th>
        </tr>
    </thead>
        <tbody>
            @foreach($cobranzas as $cobranza)
                    @if($cobranza->TipoEstado==1)
                            <tr>
                                <td width="20" align="center">{{$cobranza->FechaCreacion}}</td>
                                <td width="40">{{$cobranza->Cliente}}</td>
                                <td width="20" align="center">{{$cobranza->Serie.'-'.$cobranza->Numero}}</td>
                                <td width="20" align="center">{{$cobranza->TipoVenta == 1 ? 'Gravada': 'Exonerada'}}</td>
                                <td width="20" align="center">{{$cobranza->IdTipoMoneda == 1 ? 'Soles': 'Dólares'}}</td>
                                <td width="20" align="center">{{$cobranza->Total}}</td>
                                <td width="20" align="center">{{ number_format($cobranza->Total - $cobranza->ImportePagado, 2, '.', '') }}</td>
                                <td width="25" align="center">{{ $cobranza->Dias}}</td>
                                <td width="20" align="center">{{ abs($cobranza->Dias+$cobranza->DiasPasados) }}</td>
                                <td width="20">{{$cobranza->Nota == 1 ? 'N. Credito': $cobranza->Estado}}</td>
                            </tr>
                    @endif
            @endforeach
        </tbody>
</table>