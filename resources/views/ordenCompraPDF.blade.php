<!DOCTYPE html>
<html>
<style type="text/css">
    body {
        font-size: 11px;
        font-family: "sans-serif";
    }

    table {
        border-collapse: collapse;
    }

    td {
        padding: 1px;
        font-size: 11px;
    }

    .h1 {
        font-size: 21px;
        font-weight: bold;
    }

    .h2 {
        font-size: 19px;
        font-weight: bold;
    }

    .h4 {
        font-size: 15px;
        font-weight: bold;
    }

    .h5 {
        font-size: 13px;
        font-weight: bold;
    }

    .h6 {
        font-size: 12px;
        font-weight: bold;
    }

    .tabla1 {
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .tabla2 {
        margin-bottom: 20px;
    }

    .tabla4,
    .tabla3 {
        margin-top: 15px;
    }

    .tabla3 td {
        font-size: 11px;
    }

    .tabla3 th {
        font-size: 11px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .tabla3 .cancelado {
        border-left: 0;
        border-right: 0;
        border-bottom: 0;
        border-top: 1px dotted #000;
        width: 200px;
    }

    .negrita {
        font-weight: bold;
    }

    .linea {
        border-bottom: 1px dotted #000;
    }

    .border {
        border: 1px solid #000;
        border-radius: 12px;
    }

    .borderTabla {
        border: 1px solid #000;
    }

    .borderTop {
        border-top: 1px solid #000;
    }

    .borderBottom {
        border-bottom: 1px solid #000;
    }

    .borderPlomo {
        border: 2px solid #818182;
    }

    .fondo {
        background-color: #dfdfdf;
    }

    .container .tabla4 {
        /*position: absolute;*/
        bottom: 160px;
    }

    .margen {
        margin: 3px;
    }

    .cursiva {
        font-style: oblique;
    }

    .abajo {
        width: 100%;
        position: absolute;
        bottom: 0;
    }

    .p-x-y {
        padding: 8px;
    }

    .p-y {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .text-red {
        color: #ff0000;
    }

    .text-right {
        text-align: right;
    }
</style>

<body>
    <div class="container">
        <table width="100%" class="tabla1">
            <tr>
                <td width="40%">
                    @if ($empresa->Imagen !== null && str_contains($empresa->Imagen, config('variablesGlobales.urlDominioAmazonS3')))
                        <img src="{{ $empresa->Imagen }}" alt="" width="250" height="110">
                    @elseif($empresa->Imagen == null)
                        <img src="" alt="" width="250" height="110">
                    @else
                        <img src="{{ config('variablesGlobales.urlDominioAmazonS3') . $empresa->Imagen }}" alt=""
                            width="250" height="110">
                    @endif
                </td>
                <td width="30%">
                    <div style="margin-top: 0px;text-align: center;">
                        {{ $empresa->Descripcion }}
                    </div>
                </td>
                <td width="30%">
                    <table width="100%">
                        <tr>
                            <td align="center">
                                <section class="border">
                                    <div style="margin-top: 10px;">
                                        <span class="h2">RUC: {{ $empresa->Ruc }}</span>
                                    </div>
                                    <div style="margin-left: 5px; margin-right: 5px;">
                                        <span class="h2">ORDEN COMPRA</span>
                                    </div>
                                    <div style="margin-bottom: 10px;">
                                        <span
                                            class="h5">{{ $ordenCompraSelect->Serie }}-{{ $ordenCompraSelect->Numero }}</span><br>
                                        {{-- <span class="h6">F. Emisión  {{ $ordenCompraSelect->FechaOrdenCompra }}</span><br> --}}
                                    </div>
                                </section>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <section style="margin-bottom:15px">
            <span>{{ $empresa->PaginaWeb }}</span>
            <span style="display: block">{{ $empresa->CorreoEmpresa }}</span>
        </section>

        {{-- Nuevo codigo --}}
        {{-- <table width="100%" class="tabla1">
            <tr>
                <td width="40%" >
                    <span class="negrita">{{ $nombreEmpresa }}</span> <br>
                    <span>{{ $ordenCompraSelect->Local }} - {{ $ordenCompraSelect->Ciudad }}</span> <br>
                    SUCURSAL: {{ $ordenCompraSelect->Sucursal }} <br>
                    TELÉFONO: {{ $ordenCompraSelect->TelfSucursal }} <br>
                </td>
                <td class="text-right" width="60%">
                    @if ($ordenCompraSelect->Estado == 'Pendiente')
                        <span class="text-red">Estado: Pendiente de actualizacion de stock</span>
                    @endif
                </td>
            </tr>
        </table> --}}
        <br>
        {{-- Fin --}}
        <table width="100%" class="tabla2">
            <thead>
                <tr>
                    <th width="50%" class="borderTabla fondo" align="center">PROVEEDOR</th>
                    <th width="50%" class="borderTabla fondo" align="center">DATOS GENERALES</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="borderTabla">
                        <div class="margen">
                            <span class="negrita">Raz. Social:</span> {{ $ordenCompraSelect->Nombres }} <br>
                            <span class="negrita">RUC/DNI:</span> {{ $ordenCompraSelect->NumeroDocumento }} <br>
                            <span class="negrita">Dirección:</span>
                            {{ substr($ordenCompraSelect->DireccionProveedor, 0, 68) }}
                            <br>
                            <span class="negrita">Celular:</span> {{ $ordenCompraSelect->TelefonoProveedor }} <br>
                            <span class="negrita">Email:</span> {{ $ordenCompraSelect->EmailProveedor }}
                            {{-- <span class="negrita">Moneda:</span> {{ $ordenCompraSelect->Moneda }} <br> --}}
                        </div>
                    </td>
                    <td class="borderTabla">
                        <div class="margen">
                            <span class="negrita">Empresa:</span> {{ $nombreEmpresa }} <br>
                            <span class="negrita">Dirección:</span> {{ $ordenCompraSelect->Local }} <br>
                            <span class="negrita">Celular:</span> {{ $ordenCompraSelect->TelfSucursal }} <br>
                            <span class="negrita">Fecha Emisión:</span> {{ $ordenCompraSelect->FechaOrdenCompra }} <br>
                            <span class="negrita">Fecha Recepción:</span> {{ $ordenCompraSelect->FechaRecepcion }} <br>
                            <span class="negrita">Tipo Pago :</span>
                            {{ $ordenCompraSelect->IdTipoPago == 1 ? 'Contado' : 'Crédito' }} <br>
                            @if ($ordenCompraSelect->IdTipoPago == 2)
                                <span class="negrita">Condición de pago :</span>
                                {{ $ordenCompraSelect->DiasPlazoCredito }} Días - (Después de la Fecha de
                                Recepción)<br>
                            @endif
                            {{-- <span class="negrita">Tipo de pago:</span> --}}
                            {{-- @if ($compraSelect->IdTipoPago == 1)
                                Contado
                            @else
                                Crédito
                            @endif <br>
                            <span class="negrita">Tipo Operación:</span>
                            @if ($compraSelect->TipoCompra == 1)
                                Gravada
                            @else
                                Exonerada
                            @endif <br> --}}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <table width="100%" class="tabla3">
            <thead>
                <tr>
                    <th class="borderTabla fondo" align="center">Item</th>
                    <th class="borderTabla fondo" align="center">CódigoBarra</th>
                    <th class="borderTabla fondo" align="center">Cantidad</th>
                    <th class="borderTabla fondo" align="center">Descripción</th>
                    <th class="borderTabla fondo" align="center">P. Unitario</th>
                    <th class="borderTabla fondo" align="center">Importe</th>
                </tr>
            </thead>
            <tbody>
                @php $a=1 @endphp
                @foreach ($items as $item)
                    <tr>
                        <td class="borderTabla" align="center">{{ $a++ }}</td>
                        <td class="borderTabla" align="center">{{ $item->Codigo }}</td>
                        <td class="borderTabla" align="center">{{ $item->Cantidad }}</td>
                        <td class="borderTabla" align="center">{{ $item->Descripcion }}</td>
                        <td class="borderTabla" align="center">{{ round($item->PrecioCosto, 2) }}</td>
                        <td class="borderTabla" align="center">{{ $item->Importe }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="borderTabla">
                        <div class="margen p-y">
                            <span class="negrita">OBSERVACIONES: </span> {{ $ordenCompraSelect->Observacion }}
                            {{-- <? @phpecho nl2br($ordenCompraSelect->Observacion);@endphp ?> --}}
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
        <table width="100%">
            <tr>
                <td align="right"><br><span class="negrita">SON: </span> {{ $importeLetras }}</td>
            </tr>
        </table>
        <table width="100%" class="tabla4 borderTop borderBottom">
            <thead>
                <tr>
                    <td width="25%" align="center" class="fondo">
                        @if ($ordenCompraSelect->TipoCompra == 1)
                            OP GRAVADAS
                        @else
                            OP EXONERADAS
                        @endif
                    </td>
                    <td width="25%" align="center" class="fondo">
                        @if ($ordenCompraSelect->TipoCompra == 1)
                            IGV(18%)
                        @else
                            IGV(0%)
                        @endif
                    </td>
                    <td width="25%" align="center" class="fondo">TOTALES</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="borderTop" width="25%" align="center">{{ $ordenCompraSelect->SubTotal }}</td>
                    <td class="borderTop" width="25%" align="center">{{ $ordenCompraSelect->Igv }}</td>
                    <td class="borderTop" width="25%" align="center">{{ $ordenCompraSelect->Total }}</td>
                </tr>
            </tbody>
        </table>
        <div class="abajo">
            <table width="100%">
                <tr>
                    <td width="80%">
                        {{-- @if (count($cuentasCorrientes) > 0) --}}
                        <table width="100%">
                            <thead>
                                <tr>
                                    <th class="fondo fs-9" width="30%" align="center">ENTIDAD FINANCIERA</th>
                                    <th class="fondo fs-9" width="40%" align="center">CÓDIGO CUENTA</th>
                                    <th class="fondo fs-9" width="30%" align="center">CÓDIGO INTERBANCARIA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td align="center">
                                        <div class="margen fs-8">
                                            @foreach ($cuentasCorrientes as $cuentaCorriente)
                                                {{ $cuentaCorriente->Banco }} <br>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td align="center">
                                        <div class="margen fs-8">
                                            @foreach ($cuentasCorrientes as $cuentaCorriente)
                                                CTA. CTE {{ $cuentaCorriente->Moneda }} :
                                                {{ $cuentaCorriente->NumeroCuenta }} <br>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td align="center">
                                        <div class="margen fs-8">
                                            @foreach ($cuentasCorrientes as $cuentaCorriente)
                                                {{ $cuentaCorriente->CCI }} <br>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        {{-- @endif --}}
                        {{-- <table width="100%" class="tabla2">
                            <tr>
                                <td width="50%">
                                    <div class="fs-9">
                                        <span>Consulte en <a href="" target="_blank">www.easyfactperu.pe</a></span>
                                    </div>
                                </td>
                                <td width="50%" align="right">
                                    <div class="fs-9">
                                        <span>Sujeta a Detracción según Resolución 063/2012</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td width="50%">
                                    <div class="fs-9">
                                        Resumen: <span class="negrita">{{$hash}}</span>
                                    </div>
                                </td>
                                <td width="50%" align="right">
                                    <div class="fs-9">
                                        <span>La resolución surtira efecto si esta factura supera los S/700</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td width="40%">
                                    <div class="fs-9">
                                        <span>Representación Impresa de la ORDEN DE COMPRA</span>
                                    </div>
                                </td>
                            </tr>
                        </table> --}}
                    </td>
                </tr>
            </table>
        </div>

    </div>
</body>

</html>
