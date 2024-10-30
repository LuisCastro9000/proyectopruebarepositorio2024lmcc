<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-success">
            <th align="center" scope="col" style="font-weight:bold">Fecha</th>
            <th align="center" scope="col" style="font-weight:bold">Código</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo Cotización</th>
            <th align="center" scope="col" style="font-weight:bold">Código Inventario</th>
            <th align="center" scope="col" style="font-weight:bold">Cliente</th>
            @if ($modulosSelect->contains('IdModulo', 5))
                <th align="center" scope="col" style="font-weight:bold">Seguro</th>
            @endif
            <th align="center" scope="col" style="font-weight:bold">Celular</th>
            <th align="center" scope="col" style="font-weight:bold">RUC/DNI</th>
            <th align="center" scope="col" style="font-weight:bold">Placa</th>
            <th align="center" scope="col" style="font-weight:bold">Kilom.</th>
            <th align="center" scope="col" style="font-weight:bold">Chasis/Vin</th>
            <th align="center" scope="col" style="font-weight:bold">Marca</th>
            <th align="center" scope="col" style="font-weight:bold">Modelo</th>
            <th align="center" scope="col" style="font-weight:bold">Año</th>
            <th align="center" scope="col" style="font-weight:bold">Importe</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo Moneda</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo Atención</th>
            <th align="center" scope="col" style="font-weight:bold">Estado</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo Operación</th>
            <th align="center" scope="col" style="font-weight:bold">Tipo Comprobante</th>
            <th align="center" scope="col" style="font-weight:bold">Documento Comprobante</th>
            <th align="center" scope="col" style="font-weight:bold">Trabajos a realizar</th>
            <th align="center" scope="col" style="font-weight:bold">Observación</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cotizaciones as $cotizacion)
            <tr>
                <td width="20" align="center">{{ $cotizacion->FechaCreacion }}</td>
                <td width="15" align="center">{{ $cotizacion->Serie }}-{{ $cotizacion->Numero }}</td>
                <td width="20" align="center">{{ $cotizacion->TipoCotizacion == 1 ? 'Comercial' : 'Vehicular' }}
                </td>
                <td width="25" align="center">{{ $cotizacion->CodigoInventario }}</td>
                <td width="50" align="center">{{ $cotizacion->RazonSocial }}</td>
                @if ($modulosSelect->contains('IdModulo', 5))
                    <td width="40" align="center">{{ $cotizacion->Seguro }}</td>
                @endif
                <td width="20" align="center">{{ $cotizacion->CelularCliente }}</td>
                <td width="20" align="center">{{ $cotizacion->NumeroDocumento }}</td>
                <td width="15" align="center">{{ $cotizacion->Placa }}</td>
                <td width="15" align="center">{{ $cotizacion->Campo1 }}</td>
                <td width="25" align="center">{{ $cotizacion->ChasisVehiculo }}</td>
                <td width="20" align="center">{{ $cotizacion->Marca }}</td>
                <td width="20" align="center">{{ $cotizacion->Modelo }}</td>
                <td width="15" align="center">{{ $cotizacion->Anio }}</td>
                <td width="15" align="center">{{ $cotizacion->Total }}</td>
                <td width="15" align="center">{{ $cotizacion->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                <td width="20" align="center">{{ $cotizacion->Atencion }}</td>
                <td width="20" align="center">{{ $cotizacion->EstadoCoti }}</td>
                <td width="15" align="center">{{ $cotizacion->TipoVenta == 1 ? 'Gravada' : 'Exonerada' }}</td>
                <td width="25" align="center">
                    @foreach ($cotizacion->Documentos as $arrayComprobante)
                        @if ($arrayComprobante->IdTipoComprobante == 1)
                            <br> Boleta
                        @elseif($arrayComprobante->IdTipoComprobante == 2)
                            <br> Factura
                        @else
                            <br> Ticket
                        @endif
                    @endforeach
                </td>
                <td width="25" align="center">
                    @foreach ($cotizacion->Documentos as $arrayComprobante)
                        <br> {{ $arrayComprobante->Serie }}-{{ $arrayComprobante->Numero }}
                    @endforeach
                </td>
                <td width="80" align="left">{{ $cotizacion->Trabajos }}</td>
                <td width="80" align="left">{{ $cotizacion->Observacion }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
