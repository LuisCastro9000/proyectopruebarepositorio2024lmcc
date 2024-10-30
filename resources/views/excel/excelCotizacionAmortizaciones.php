<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th align="center" scope="col">Fecha Registro</th>
            <th align="center" scope="col">Usuario Vendedor</th>
            <th align="center" scope="col">Docum. Cotizado</th>
            <th align="center" scope="col">Forma de Pago</th>
            <th align="center" scope="col">Tipo Moneda</th>
            <th align="center" scope="col">Monto</th>
        </tr>
    </thead>
        <tbody>
        @foreach($reporteAmortizaciones as $reporteAmortizacion)
            <tr>
                <td width="30" align="center">{{$reporteAmortizacion->FechaIngreso}}</td>
                <td width="60" align="center">{{$reporteAmortizacion->Usuario}}</td>
                <td width="15" align="center">{{$reporteAmortizacion->Serie}}-{{$reporteAmortizacion->Numero}}</td>
                <td width="20" align="center">
                    @if($reporteAmortizacion->FormaPago == 1)
                        'Efectivo'
                    @elseif($reporteAmortizacion->FormaPago == 2)
                        'POS'
                    @else
                        'Transferencia Bancaria'
                    @endif
                </td>
                <td width="20" align="center">{{$reporteAmortizacion->IdTipoMoneda == 1 ? 'Soles': 'Dólares'}}</td>
                <td width="15" align="center">{{$reporteAmortizacion->Monto}}</td>
            </tr>
        @endforeach
        </tbody>
</table>