<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th align="center" scope="col" style="font-weight:bold">Fecha Registro</th>
            <th align="center" scope="col" style="font-weight:bold">Usuario Vendedor</th>
            <th align="center" scope="col" style="font-weight:bold">Docum. Cotizado</th>
            <th align="center" scope="col" style="font-weight:bold">Forma de Pago</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo Moneda</th>
            <th align="center" scope="col" style="font-weight:bold">Monto</th>
        </tr>
    </thead>
        <tbody>
        @foreach($reporteAmortizaciones as $reporteAmortizacion)
            <tr>
                <td width="20" align="center">{{$reporteAmortizacion->FechaIngreso}}</td>
                <td width="40" align="center">{{$reporteAmortizacion->Usuario}}</td>
                <td width="20" align="center">{{$reporteAmortizacion->Serie}}-{{$reporteAmortizacion->Numero}}</td>
                <td width="30" align="center">
                    @if($reporteAmortizacion->FormaPago == 1)
                        Efectivo
                    @elseif($reporteAmortizacion->FormaPago == 2)
                        POS
                    @else
                        Transferencia Bancaria
                    @endif
                </td>
                <td width="15" align="center">{{$reporteAmortizacion->IdTipoMoneda == 1 ? 'Soles': 'Dólares'}}</td>
                <td width="15" align="center">{{$reporteAmortizacion->Monto}}</td>
            </tr>
        @endforeach
        </tbody>
</table>