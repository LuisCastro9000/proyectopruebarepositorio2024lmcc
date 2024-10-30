<!DOCTYPE html>
<html>
<style type="text/css">
    @page {
        margin-top: 0em;
        margin-left: 0.5em;
        margin-right: 0.5em;
    }

    body {
        font-size: 7px;
        font-family: "sans-serif";
    }

    table {
        border-collapse: collapse;
    }

    td {
        font-size: 7px;
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

    .tabla1 td {
        font-size: 6px;
    }

    .tabla1 {
        margin-bottom: 0px;
    }

    .tabla2 td {
        font-size: 5px;
    }

    .tabla4,
    .tabla3 {
        margin-top: 0px;
    }

    .tabla4 td {
        font-size: 4px;
    }

    .tabla3 td {
        font-size: 5px;
    }

    .tabla3 th {
        font-size: 5px;
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
        border: 2px solid #000;
    }

    .borderTabla {
        border: 1px solid #000;
    }

    .borderTop {
        border-top: 0.5px solid #000;
    }

    .borderBottom {
        border-bottom: 0.5px solid #000;
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
</style>

<body>
    <div class="container">
        <table width="100%" class="tabla1">
            <tr>
                <td align="center">
                    @if ($empresa->Imagen !== null && str_contains($empresa->Imagen, config('variablesGlobales.urlDominioAmazonS3')))
                        <img src="{{ $empresa->Imagen }}" alt="" width="110" height="60">
                    @elseif($empresa->Imagen == null)
                        <img src="" alt="" width="110" height="60">
                    @else
                        <img src="{{ config('variablesGlobales.urlDominioAmazonS3') . $empresa->Imagen }}" alt=""
                            width="110" height="60">
                    @endif
                </td>
            </tr>
            <tr>
                <td align="center">{{ $nombreEmpresa }}</td>
            </tr>
            <tr>
                <td align="center">RUC: {{ $empresa->Ruc }}</td>
            </tr>
            <tr>
                <td align="center">{{ $empresa->DirPrincipal }}</td>
            </tr>
            <tr>
                <td align="center">Teléfono: {{ $empresa->Telefono }}</td>
            </tr>
            <tr>
                <td align="center">==============================</td>
            </tr>
            @if ($ventaSelect->Principal != 1)
                <tr>
                    <td style="font-size: 5px;" align="center"><strong>Sucursal:</strong> {{ $ventaSelect->Sucursal }}
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 5px;" align="center"><strong>Direccion:</strong> {{ $ventaSelect->Local }}
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 5px;" align="center"><strong>Ciudad:</strong> {{ $ventaSelect->Distrito }}
                    </td>
                </tr>
                <tr>
                    <td align="center">==============================</td>
                </tr>
            @endif
            <tr>
                @if ($ventaSelect->IdTipoComprobante == 1)
                    <td class="negrita" align="center">BOLETA ELECTRÓNICA</td>
                @elseif($ventaSelect->IdTipoComprobante == 2)
                    <td class="negrita" align="center">FACTURA ELECTRÓNICA</td>
                @else
                    <td class="negrita" align="center">TICKET PRE-VENTA</td>
                @endif
            </tr>
            <tr>
                <td class="negrita" align="center">{{ $ventaSelect->Serie }}-{{ $numeroCeroIzq }}</td>
            </tr>
            <tr>
                <td align="center">==============================</td>
            </tr>
        </table>
        <table width="100%" class="tabla2">
            @if ($idSeguro > 2)
                <tr>
                    <td width="20%">Nombre/Razón:</td>
                    <td width="30%">{{ $seguroNombre }}</td>
                </tr>
                <tr>
                    <td width="20%">Responsable Asegurado:</td>
                    <td width="30%">{{ $ventaSelect->RazonSocial }}</td>
                </tr>
            @else
                <tr>
                    <td width="20%">Nombre/Razón:</td>
                    <td width="30%">{{ $ventaSelect->RazonSocial }}</td>
                </tr>
            @endif
            <tr>
                <td width="20%">{{ $ventaSelect->TipoDoc }}:</td>
                <td width="30%">{{ $ventaSelect->NumeroDocumento }}</td>
            </tr>
            <tr>
                <td width="20%">Dirección:</td>
                <td width="30%">{{ $ventaSelect->DirCliente }}</td>
            </tr>
            <tr>
                <td width="20%">Moneda:</td>
                <td width="30%">{{ $ventaSelect->Moneda }}</td>
            </tr>
            <tr>
                <td width="20%">Fecha / Hora:</td>
                <td width="30%">{{ $formatoFecha }} {{ $formatoHora }}</td>
            </tr>
            <tr>
                <td width="20%">Tipo Oper.:</td>
                <td width="30%">
                    @if ($ventaSelect->TipoVenta == 1)
                        Gravada
                    @else
                        Exonerada
                    @endif
                </td>
            </tr>
            @if ($ventaSelect->Placa != '')
                <tr>
                    <td width="20%">Placa:</td>
                    <td width="30%">{{ $ventaSelect->Placa }}</td>
                </tr>
            @endif
            <tr>
                <td width="20%">Forma de Pago:</td>
                @if ($ventaSelect->IdTipoPago == 1)
                    <td width="30%">Contado</td>
                @else
                    <td width="30%">Crédito</td>
                @endif
            </tr>
            @if ($ventaSelect->IdTipoPago == 2)
                <tr>
                    <td width="25%" colspan="2"><span>Cuota:</span>1 / <span> Monto:</span>
                        @if (floatval($ventaSelect->Total) >= floatval(700) && count($itemsServ) > 0 && $ventaSelect->TipoVenta == 1)
                            {{ number_format($ventaSelect->Total - ($ventaSelect->Total * $ventaSelect->PorcentajeDetraccion) / 100, 2) }}
                        @elseif($ventaSelect->Retencion == 1)
                            {{ number_format($ventaSelect->Total - ($ventaSelect->Total + $ventaSelect->Amortizacion) * 0.03, 2) }}
                        @else
                            {{ round($ventaSelect->Total, 2) }}
                        @endif /
                        <span> Fecha Pago:</span>{{ $fechaPago }}
                    </td>
                </tr>
            @endif
            <!--<tr>
                <td width="15%">SUCURSAL:</td>
                <td width="25%">{{ $ventaSelect->Sucursal }}</td>
                <td width="10%">&nbsp;</td>
            </tr>
            <tr>
                <td width="15%">FECHA EMISIÓN:</td>
                <td width="25%">{{ $formatoFecha }}</td>
                <td width="10%">&nbsp;</td>
            </tr>
            <tr>
                <td width="15%">Código:</td>
                <td width="25%"></td>
                <td width="10%">&nbsp;</td>
            </tr>-->

        </table>
        <table width="100%" class="tabla1">
            <tr>
                <td align="center">==============================</td>
            </tr>
        </table>
        <table width="100%" class="tabla3">
            <thead>
                <tr>
                    <th align="center">Descripción</th>
                    <th align="center">Cantidad</th>
                    <th align="center">Precio</th>
                    <th align="center">Desc.</th>
                    <th align="center">Importe</th>
                </tr>
            </thead>
            <tbody>
                @if (count($itemsProd) > 0)
                    @foreach ($itemsProd as $item)
                        <tr>
                            <td align="center">{{ $item->Descripcion }} {{ $item->Detalle }}</td>
                            <td align="center">{{ $item->Cantidad }} <span> </span>{{ $item->TextUnidad }}</td>
                            <td align="center">{{ $item->PrecioUnidadReal }}</td>
                            <td align="center">{{ $item->Descuento }}</td>
                            <td align="center">{{ $item->Importe }}</td>
                        </tr>
                    @endforeach
                @endif
                @if (count($itemsServ) > 0)
                    @foreach ($itemsServ as $item)
                        <tr>
                            <td align="center">{{ $item->Descripcion }} {{ $item->Detalle }}</td>
                            <td align="center">{{ $item->Cantidad }} <span> </span>{{ $item->TextUnidad }}</td>
                            <td align="center">{{ $item->PrecioUnidadReal }}</td>
                            <td align="center">{{ $item->Descuento }}</td>
                            <td align="center">{{ $item->Importe }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <table width="100%" class="tabla4 borderTop borderBottom">
            <thead>
                <tr>
                    <td width="20%" align="center">
                        @if ($ventaSelect->TipoVenta == 1)
                            OP GRAVADAS
                        @else
                            OP EXONERADAS
                        @endif
                    </td>
                    <td width="20%" align="center">OP GRATUITAS</td>
                    <td width="20%" align="center">TOTAL DESCUENTO</td>
                    <td width="20%" align="center">
                        @if ($ventaSelect->TipoVenta == 1)
                            IGV(18%)
                        @else
                            IGV(0%)
                        @endif
                    </td>
                    <td width="20%" align="center">TOTALES</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="borderTop" width="20%" align="center">
                        {{ number_format($ventaSelect->Subtotal, 2) }}
                    </td>
                    <td class="borderTop" width="20%" align="center">
                        {{ number_format($ventaSelect->Gratuita, 2) }}
                    </td>
                    <td class="borderTop" width="20%" align="center">
                        {{ number_format($ventaSelect->Exonerada, 2) }}</td>
                    <td class="borderTop" width="20%" align="center">{{ number_format($ventaSelect->IGV, 2) }}
                    </td>
                    <td class="borderTop" width="20%" align="center">
                        {{ number_format($ventaSelect->Total + $ventaSelect->Amortizacion, 2) }}</td>
                </tr>
            </tbody>
        </table>
        <table width="100%" class="tabla4">
            <tr>
                <td width="15%">Son:</td>
                <td width="45%">{{ $importeLetras }}</td>
            </tr>
            @if ($ventaSelect->Gratuita > 0)
                <tr>
                    <td width="45%" colspan="2" align="center">TRANSFERENCIA GRATUITA DE UN BIEN Y/O SERVICIO
                        PRESTADO GRATUITAMENTE</td>
                </tr>
            @endif
        </table>
        <table width="100%" class="tabla1">
            <tr>
                <td align="center" class="negrita">-------------------------------------------------------</td>
            </tr>
        </table>
        <table width="100%" class="tabla3">
            <tr>
                <td width="15%">Vendedor:</td>
                <td width="25%">{{ $ventaSelect->Usuario }}</td>
            </tr>
            <tr>
                <td width="15%">Observaciones:</td>
                <td width="25%">{{ $ventaSelect->Observacion }}</td>
            </tr>
        </table>
        @if ($ventaSelect->IdTipoComprobante == 3)
            <table width="100%" class="tabla1">
                <tr>
                    <td align="center">Este documento no tiene ningún valor tributario, solo representa un ticket de
                        Pre-Venta</td>
                </tr>
            </table>
        @endif
        @if ($ventaSelect->IdTipoComprobante != 3)
            <table width="100%" class="tabla1">
                <tr>
                    <td align="center" class="negrita">-------------------------------------------------------</td>
                </tr>
            </table>
            <table width="100%" class="tabla3">
                <tr>
                    <td align="center"><span class="negrita">{{ $hash }}</span></td>
                </tr>
            </table>
            <table width="100%" class="tabla1">
                <tr>
                    <td align="center" class="negrita">-------------------------------------------------------</td>
                </tr>
            </table>
            <table width="100%" class="tabla3">
                <tr>
                    <td align="center">
                        <img src="data:image/png;base64, {!! base64_encode(
                            QrCode::format('png')->size(70)->generate($resumen),
                        ) !!} ">
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <!--<div>
                        Autorizado mediante Resolución
                    </div>-->
                        <div>
                            @if ($ventaSelect->IdTipoComprobante == 1)
                                Representación Impresa de la BOLETA ELECTRÓNICA
                            @else
                                Representación Impresa de la FACTURA ELECTRÓNICA
                            @endif
                        </div>
                        <div>
                            Sujeta a Detracción según Resolución 063/2012
                        </div>
                        <div>
                            La presente resolución sufrira efecto si esta factura supera los 700 soles
                        </div>
                        {{-- <div>
                            Consulte Documento en www.easyfactperu.pe
                        </div> --}}
                    </td>
                </tr>
            </table>
        @endif
    </div>
</body>

</html>
