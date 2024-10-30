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
                        <img src="{{ config('variablesGlobales.urlDominioAmazonS3') . $empresa->Imagen }}"
                            alt="" width="110" height="60">
                    @endif
                </td>
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
            <tr>

                <td class="negrita" align="center">AMORTIZACIÓN</td>

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
                    Cotización
                </td>
            </tr>
            <tr>
                <td width="20%">Forma de Pago:</td>
                <td width="30%">
                    @if ($ventaSelect->FormaPago == 1)
                        Pago Efectivo
                    @elseif($ventaSelect->FormaPago = 2)
                        POS
                    @else
                        Transferencia Bancaria
                    @endif
                </td>
            </tr>
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
            <tbody>
                <tr>
                    <td class="borderTop" width="20%" align="">Total</td>
                    <td class="borderTop" width="20%" align="right">
                        {{ number_format($ventaSelect->Total, 2) }}
                    </td>
                </tr>
                <tr>
                    <td class="borderTop" width="20%" align="">Amortización Total</td>
                    <td class="borderTop" width="30%" align="right">
                        {{ number_format($ventaSelect->montoAmortizado, 2) }}
                    </td>
                </tr>
                <tr>
                    <td class="borderTop" width="50%" align="">Pendiente por Pagar</td>
                    <td class="borderTop" width="50%" align="right">
                        {{ number_format($ventaSelect->Total - $ventaSelect->montoAmortizado, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
        <table width="100%" class="tabla4">
            <tr>
                <td width="10%">Son:</td>
                <td width="45%" align="right">{{ $importeAmortizadoTicketLetras }}</td>
            </tr>
        </table>
        <br><br>
        <table width="100%" class="tabla1">
            <tr>
                <td align="center" class="negrita">-------------------------------------------------------</td>
            </tr>
        </table>
        <div>
            <table width="100%" class="tabla3">
                <tr>
                    <td align="center">
                        <div align="center">
                            Documento no contable para fines tributarios, <br> debe ser canjeado por una Factura o
                            Boleta.
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <table width="100%" class="tabla1">
            <tr>
                <td align="center" class="negrita">-------------------------------------------------------</td>
            </tr>
        </table>
    </div>
</body>

</html>
