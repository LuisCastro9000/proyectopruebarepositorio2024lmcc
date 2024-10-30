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
</style>

<body>
    <div class="container">
        <table width="100%" class="tabla1">
            <tr>
                <td width="60%">
                    @if ($empresa->Imagen !== null && str_contains($empresa->Imagen, config('variablesGlobales.urlDominioAmazonS3')))
                        <img src="{{ $empresa->Imagen }}" alt="" width="190" height="115">
                    @elseif($empresa->Imagen == null)
                        <img src="" alt="" width="190" height="115">
                    @else
                        <img src="{{ config('variablesGlobales.urlDominioAmazonS3') . $empresa->Imagen }}" alt=""
                            width="190" height="115">
                    @endif
                </td>
                <td width="40%">
                    <table width="100%">
                        <tr>
                            <td align="center" style="border: 1px solid #000;">
                                <div style="margin-top: 10px;">
                                    <span class="h2">RUC: {{ $empresa->Ruc }}</span>
                                </div>
                                <div style="margin-left: 5px; margin-right: 5px;">
                                    @if ($notaSelect->IdTipoNota == 1)
                                        <span class="h4">NOTA DE CRÉDITO ELECTRÓNICO</span>
                                    @else
                                        <span class="h4">NOTA DE DÉBITO ELECTRÓNICO</span>
                                    @endif
                                </div>
                                <div style="margin-bottom: 10px;">
                                    <span class="h2">{{ $notaSelect->Serie }}-{{ $numeroCeroIzq }}</span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td><span class="negrita">{{ $empresa->Nombre }}</span></td>
            </tr>
            <tr>
                <td>{{ $empresa->Direccion }} - {{ $empresa->Ciudad }}</td>
            </tr>
            <tr>
                <td>TELÉFONO: {{ $empresa->Telefono }}</td>
            </tr>
        </table>
        <table width="100%" class="tabla2 mt-2">
            <tr>
                <td width="20%">NOMBRE/RAZÓN SOCIAL:</td>
                <td width="25%">{{ $notaSelect->RazonSocial }}</td>
                <td width="10%">&nbsp;</td>
                <td width="15%">RUC/DNI:</td>
                <td width="18%">{{ $notaSelect->NumeroDocumento }}</td>
            </tr>
            <tr>
                <td width="20%">DIRECCIÓN:</td>
                <td width="25%">{{ $notaSelect->DirCliente }}</td>
                <td width="10%">&nbsp;</td>
                <td width="15%">TELÉFONO:</td>
                <td width="18%">{{ $notaSelect->TelfCliente }}</td>
            </tr>
            <tr>
                <td width="20%">MONEDA:</td>
                <td width="25%">
                    @if ($notaSelect->IdTipoMoneda == 1)
                        Soles
                    @else
                        Dólares
                    @endif
                </td>
                <td width="10%">&nbsp;</td>
                <td width="15%">TIPO OPERACIÓN:</td>
                <td width="18%">
                    @if ($notaSelect->TipoVenta == 1)
                        Gravada
                    @else
                        Exonerada
                    @endif
                </td>
            </tr>
        </table>
        <table width="100%" class="tabla2 mt-2">
            <tr>
                <td class="borderBottom negrita" colspan="5">DOCUMENTO MODIFICADO</td>
            </tr>
            <tr>
                <td width="20%">DOCUMENTO:</td>
                <td width="25%">{{ $notaSelect->DocModificado }}</td>
                <td width="10%">&nbsp;</td>
                <td width="15%">FECHA EMISIÓN:</td>
                <td width="18%">{{ $formatoFecha }}</td>
            </tr>
            <tr>
                <td width="20%">TIPO DOC.:</td>
                @if ($notaSelect->IdDocModificado == 1)
                    <td width="25%">BOLETA</td>
                @else
                    <td width="25%">FACTURA</td>
                @endif
                <td width="10%">&nbsp;</td>
                <td width="15%">HORA EMISIÓN:</td>
                <td width="18%">{{ $formatoHora }}</td>
            </tr>
            <tr>
                <td width="20%">MOTIVO:</td>
                <td width="25%">{{ $notaSelect->Motivo }}</td>
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
                    <tr>
                        <td class="borderTabla" align="center">{{ $a++ }}</td>
                        <td class="borderTabla" align="center">{{ $item->Cod }}</td>
                        <td class="borderTabla" align="center">{{ $item->Cantidad }}</td>
                        <td class="borderTabla" align="center">{{ $item->Descripcion }}</td>
                        <td class="borderTabla" align="center">{{ $item->Precio }}</td>
                        <td class="borderTabla" align="center">{{ $item->Descuento }}</td>
                        <td class="borderTabla" align="center">{{ $item->Total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table width="100%">
            <tr>
                <td width="12%">OBSERVACIONES:</td>
                <td width="25%">{{ $notaSelect->Observacion }}</td>
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
                        @if ($notaSelect->TipoVenta == 1)
                            OP GRAVADAS
                        @else
                            OP EXONERADAS
                        @endif
                    </td>
                    <td width="25%" align="center">TOTAL DESCUENTO</td>
                    <td width="25%" align="center">
                        @if ($notaSelect->TipoVenta == 1)
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
                    <td class="borderTop" width="25%" align="center">{{ $notaSelect->Subtotal }}</td>
                    <td class="borderTop" width="25%" align="center">{{ $notaSelect->Descuento }}</td>
                    <td class="borderTop" width="25%" align="center">{{ $notaSelect->IGV }}</td>
                    <td class="borderTop" width="25%" align="center">{{ $notaSelect->Total }}</td>
                </tr>
            </tbody>
        </table>
        <!--<table width="100%" class="borderPlomo">
            <tr class="margen">
                <td class="negrita">Observaciones de Sunat:</td>
            </tr>
            <tr class="margen">
                @if ($notaSelect->IdTipoNota == 1)
<td>La Nota de Crédito número {{ $notaSelect->Serie }}-{{ $notaSelect->Numero }}, ha sido aceptada</td>
@else
<td>La Nota de Débito número {{ $notaSelect->Serie }}-{{ $notaSelect->Numero }}, ha sido aceptada</td>
@endif
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td class="cursiva">Autorizado a ser emisor electrónico mediante R.I. SUNAT Nº</td>
            </tr>
        </table>-->
        <div class="abajo">
            <table width="100%">
                <tr>
                    <td align="left">
                        <div>
                            <span>Consulte en <a href="" target="_blank">www.easyfactperu.pe</a></span>
                        </div>
                        <div>
                            Resumen: <span class="negrita">{{ $hash }}</span>
                        </div>
                        <div>
                            @if ($notaSelect->IdTipoNota == 1)
                                <span>Representación Impresa de la NOTA DE CRÉDITO ELECTRÓNICO</span>
                            @else
                                <span>Representación Impresa de la NOTA DE DÉBITO ELECTRÓNICO</span>
                            @endif
                        </div>
                    </td>
                    <td align="right">
                        <img src="data:image/png;base64, {!! base64_encode(
                            QrCode::format('png')->size(160)->generate($resumen),
                        ) !!} ">
                    </td>
                </tr>
            </table>
        </div>

    </div>
</body>

</html>
