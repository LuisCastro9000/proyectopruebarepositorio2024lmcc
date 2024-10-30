<!DOCTYPE html>
<html>
<style type="text/css">
    body {
        font-size: 8px;
        font-family: "sans-serif";
    }

    table {
        border-collapse: collapse;
    }

    td {
        padding: 0.5px;
        font-size: 8px;
    }

    .h1 {
        font-size: 18px;
        font-weight: bold;
    }

    .h2 {
        font-size: 16px;
        font-weight: bold;
    }

    .h4 {
        font-size: 12px;
        font-weight: bold;
    }

    .h5 {
        font-size: 10px;
        font-weight: bold;
    }

    .tabla1 {
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .tabla2 {
        margin-bottom: 18px;
    }

    .tabla4,
    .tabla3 {
        margin-top: 13px;
    }

    .tabla3 td {
        font-size: 8px;
    }

    .tabla3 th {
        font-size: 8px;
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
        border-radius: 10px;
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
        bottom: 140px;
    }

    .margen {
        margin: 2px;
    }

    .cursiva {
        font-style: oblique;
    }

    .abajo {
        width: 100%;
        position: absolute;
        bottom: 0;
    }

    .page-break {
        page-break-after: always;
    }
</style>

<body>
    <div class="container">
        <table width="100%" class="tabla1">
            <tr>
                <td width="60%">
                    @if ($empresa->Imagen !== null && str_contains($empresa->Imagen, config('variablesGlobales.urlDominioAmazonS3')))
                        <img src="{{ $empresa->Imagen }}" alt=""width="190" height="105">
                    @elseif($empresa->Imagen == null)
                        <img src="" alt="" width="190" height="105">
                    @else
                        <img src="{{ config('variablesGlobales.urlDominioAmazonS3') . $empresa->Imagen }}" alt=""
                            width="190" height="105">
                    @endif
                </td>
                <td width="40%">
                    <table width="100%">
                        <tr>
                            <td align="center" style="border: 1px solid #000;">
                                <div style="margin-top: 8px;">
                                    <span class="h2">RUC: {{ $empresa->Ruc }}</span>
                                </div>
                                <div style="margin-left: 3px; margin-right: 3px;">
                                    @if ($ventaSelect->IdTipoComprobante == 1)
                                        <span class="h2">BOLETA ELECTRÓNICA</span>
                                    @elseif($ventaSelect->IdTipoComprobante == 2)
                                        <span class="h2">FACTURA ELECTRÓNICA</span>
                                    @else
                                        <span class="h2">TICKET PRE-VENTA</span>
                                    @endif
                                </div>
                                <div style="margin-bottom: 8px;">
                                    <span class="h2">{{ $ventaSelect->Serie }}-{{ $numeroCeroIzq }}</span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td><span class="negrita">{{ $nombreEmpresa }}</span></td>
            </tr>
            <tr>
                <td>{{ $empresa->DirPrincipal }} - {{ $empresa->Distrito }}</td>
            </tr>
            <tr>
                <td>TELÉFONO: {{ $empresa->Telefono }}</td>
            </tr>
        </table>
        <table width="100%" class="tabla2">
            @if ($ventaSelect->Principal != 1)
                <tr>
                    <td width="15%">VENDEDOR:</td>
                    <td width="18%">{{ $ventaSelect->Usuario }}</td>
                    <td width="10%">&nbsp;</td>
                    <td width="20%">&nbsp;</td>
                    <td width="25%">&nbsp;</td>

                </tr>
                <tr>
                    <td width="15%">SUCURSAL:</td>
                    <td width="40%">{{ $ventaSelect->Sucursal }}</td>
                    <td width="15%">&nbsp;</td>
                    <td width="15%">&nbsp;</td>
                    <td width="15%">&nbsp;</td>

                </tr>
                <tr>
                    <td width="15%">DIRECCION:</td>
                    <td width="40%">{{ $ventaSelect->Local }}</td>
                    <td width="15%">&nbsp;</td>
                    <td width="15%">&nbsp;</td>
                    <td width="15%">&nbsp;</td>

                </tr>
                <tr>
                    <td width="15%">CIUDAD:</td>
                    <td width="40%">{{ $ventaSelect->Ciudad }}</td>
                    <td width="15%">&nbsp;</td>
                    <td width="15%">&nbsp;</td>
                    <td width="15%">&nbsp;</td>

                </tr>
                <tr>
                    <td width="20%">&nbsp;</td>
                    <td width="25%">&nbsp;</td>
                    <td width="10%">&nbsp;</td>
                    <td width="15%">&nbsp;</td>
                    <td width="18%">&nbsp;</td>
                </tr>
                <tr>
                    <td class="borderTop" width="20%">&nbsp;</td>
                    <td class="borderTop" width="25%">&nbsp;</td>
                    <td class="borderTop" width="10%">&nbsp;</td>
                    <td class="borderTop" width="15%">&nbsp;:</td>
                    <td class="borderTop" width="18%">&nbsp;</td>
                </tr>
            @endif;
            <tr>
                <td width="20%">NOMBRE/RAZÓN SOCIAL:</td>
                <td width="25%">{{ $ventaSelect->RazonSocial }}</td>
                <td width="10%">&nbsp;</td>
                <td width="15%">FECHA EMISIÓN:</td>
                <td width="18%">{{ $formatoFecha }}</td>
            </tr>
            <tr>
                <td width="20%">RUC/DNI:</td>
                <td width="25%">{{ $ventaSelect->NumeroDocumento }}</td>
                <td width="10%">&nbsp;</td>
                <td width="15%">HORA EMISIÓN:</td>
                <td width="18%">{{ $formatoHora }}</td>
            </tr>
            <tr>
                <td width="20%">DIRECCIÓN:</td>
                <td width="25%">{{ $ventaSelect->DirCliente }}</td>
                <td width="10%">&nbsp;</td>
                <td width="15%">FORMA DE PAGO:</td>
                @if ($ventaSelect->IdTipoPago == 1)
                    <td width="15%">Contado</td>
                @else
                    <td width="18%">Crédito</td>
                @endif
            </tr>
            <tr>
                <td width="20%">&nbsp;</td>
                <td width="25%">&nbsp;</td>
                <td width="10%">&nbsp;</td>
                <td width="15%">MONEDA:</td>
                <td width="18%">{{ $ventaSelect->Moneda }}</td>
            </tr>
            <tr>
                <td width="20%">&nbsp;</td>
                <td width="25%">&nbsp;</td>
                <td width="10%">&nbsp;</td>
                <td width="15%">TIPO OPERACIÓN:</td>
                <td width="18%">
                    @if ($ventaSelect->TipoVenta == 1)
                        Gravada
                    @else
                        Exonerada
                    @endif
                </td>
            </tr>

        </table>
        <table width="100%" class="tabla3">
            <thead>
                <tr>
                    <th class="borderTabla" align="center">Item</th>
                    <th class="borderTabla" align="center">Código</th>
                    <th class="borderTabla" align="center">Cantidad</th>
                    <th class="borderTabla" align="center">Descripción</th>
                    <th class="borderTabla" align="center">Precio</th>
                    <th class="borderTabla" align="center">Descuento</th>
                    <th class="borderTabla" align="center">Importe</th>
                </tr>
            </thead>
            <tbody>
                @php $a=1 @endphp
                @foreach ($items as $item)
                    @if ($a == 21)
                        <div class="page-break"></div>
                    @endif
                    <tr>
                        <td class="borderTabla" align="center">{{ $a++ }}</td>
                        <td class="borderTabla" align="center">{{ $item->Cod }}</td>
                        <td class="borderTabla" align="center">{{ $item->Cantidad }} <span> </span>
                            {{ $item->TextUnidad }} </td>
                        <td class="borderTabla" align="center">{{ $item->Descripcion }} {{ $item->Detalle }}</td>
                        <td class="borderTabla" align="center">{{ $item->PrecioUnidadReal }}</td>
                        <td class="borderTabla" align="center">{{ $item->Descuento }}</td>
                        <td class="borderTabla" align="center">{{ $item->Importe }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table width="100%">
            <tr>
                <td width="12%">OBSERVACIONES:</td>
                <td width="25%">{{ $ventaSelect->Observacion }}</td>
                <td width="10%">&nbsp;</td>
            </tr>
            <tr>
                <td width="12%">SON:</td>
                <td width="25%">{{ $importeLetras }}</td>
                <td width="10%">&nbsp;</td>
            </tr>
        </table>
        <table width="100%" class="tabla4 borderTop borderBottom">
            <thead>
                <tr>
                    <td width="25%" align="center">
                        @if ($ventaSelect->TipoVenta == 1)
                            OP GRAVADAS
                        @else
                            OP EXONERADAS
                        @endif
                    </td>
                    <td width="25%" align="center">TOTAL DESCUENTO</td>
                    <td width="25%" align="center">
                        @if ($ventaSelect->TipoVenta == 1)
                            IGV(18%)
                        @else
                            IGV(0%)
                        @endif
                    </td>
                    <td width="25%" align="center">TOTALES</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="borderTop" width="25%" align="center">{{ $ventaSelect->Subtotal }}</td>
                    <td class="borderTop" width="25%" align="center">{{ $ventaSelect->Exonerada }}</td>
                    <td class="borderTop" width="25%" align="center">{{ $ventaSelect->IGV }}</td>
                    <td class="borderTop" width="25%" align="center">{{ $ventaSelect->Total }}</td>
                </tr>
            </tbody>
        </table>
        @if ($ventaSelect->IdTipoComprobante == 3)
            <table width="100%">
                <tr>
                    <td align="center">Este documento no tiene ningún valor tributario, solo representa un ticket de
                        Pre-Venta</td>
                </tr>
            </table>
        @endif
        @if ($ventaSelect->IdTipoComprobante != 3)
            <br>
            <br>
            <!--<table width="100%" class="borderPlomo">
            <tr class="margen">
                <td class="negrita">Observaciones de Sunat:</td>
            </tr>
            <tr class="margen">
                @if ($ventaSelect->IdTipoComprobante == 1)
@if ($ventaSelect->Estado == 'Aceptado')
<td>La Boleta número {{ $ventaSelect->Serie }}-{{ $ventaSelect->Numero }}, ha sido aceptada</td>
@else
<td><br></td>
@endif
@else
<td>La Factura número {{ $ventaSelect->Serie }}-{{ $ventaSelect->Numero }}, ha sido aceptada</td>
@endif
            </tr>
        </table>
        <br>
        <table width="100%">
            <tr>
                <td class="cursiva">Autorizado a ser emisor electrónico mediante R.I. SUNAT Nº</td>
            </tr>
        </table>-->
            <div class="abajo">
                <table width="100%">
                    <tr>
                        <td width="70%">
                            @if (count($cuentasCorrientes) > 0)
                                <div style="border:1px solid black;padding: 2px;margin-bottom:2px;">
                                    <span class="negrita">Cuentas Corrientes</span><br>
                                    @foreach ($cuentasCorrientes as $cuentaCorriente)
                                        <span>{{ $cuentaCorriente->Banco }} en {{ $cuentaCorriente->Moneda }} :
                                            {{ $cuentaCorriente->NumeroCuenta }} - CCI:
                                            {{ $cuentaCorriente->CCI }}</span><br>
                                    @endforeach
                                </div>
                            @endif
                            <div>
                                <span>Consulte en <a href="" target="_blank">www.mifacturita.pe</a></span>
                            </div>
                            <div>
                                Resumen: <span class="negrita">{{ $hash }}</span>
                            </div>
                            <div>
                                @if ($ventaSelect->IdTipoComprobante == 1)
                                    <span>Representación Impresa de la BOLETA ELECTRÓNICA</span>
                                @else
                                    <span>Representación Impresa de la FACTURA ELECTRÓNICA</span>
                                @endif
                            </div>
                        </td>
                        <td align="right" width="30%">
                            <img src="data:image/png;base64, {!! base64_encode(
                                QrCode::format('png')->size(160)->generate($resumen),
                            ) !!} ">
                        </td>
                    </tr>
                </table>
            </div>
        @endif
    </div>
</body>

</html>
