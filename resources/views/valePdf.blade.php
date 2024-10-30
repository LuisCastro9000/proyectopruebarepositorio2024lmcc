<!DOCTYPE html>
<html>
<style type="text/css">
    body {
        font-size: 10px;
        font-family: "sans-serif";
    }

    table {
        border-collapse: collapse;
    }

    td {
        padding: 1px;
        font-size: 10px;
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

    .tabla1 {
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .tabla2 {
        margin-bottom: 5px;
    }

    .tabla4,
    .tabla3 {
        margin-top: 10px;
    }

    .tabla3 td {
        font-size: 9px;
    }

    .tabla3 th {
        font-size: 10px;
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
        border-radius: 15px;
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

    .page-break {
        page-break-after: always;
    }

    .fs-10 {
        font-size: 10px;
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
                                <div class="border">
                                    <div style="margin-top: 15px;">
                                        <span class="h4">RUC: {{ $empresa->Ruc }}</span>
                                    </div>
                                    <div>

                                        <span class="h4">VALE ALMACEN</span>

                                    </div>
                                    <div style="margin-bottom: 15px;">
                                        <span
                                            class="h4">VA{{ substr($ventaSelect->Serie, 1) }}-{{ $numeroCeroIzq }}</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" class="tabla1">
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
            <thead>
                <tr>
                    <th width="65%" class="borderTabla fondo" align="center">DATOS DEL RECEPTOR</th>
                    <th width="35%" class="borderTabla fondo" align="center">DATOS GENERALES</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="borderTabla">
                        <div class="margen">
                            @if ($idSeguro > 2)
                                <span class="negrita">Raz. Social:</span> {{ $seguroNombre }} <br>
                                <span class="negrita">Responsable Asegurado:</span>
                                {{ $ventaSelect->RazonSocial }} <br>
                            @else
                                <span class="negrita">Raz. Social:</span> {{ $ventaSelect->RazonSocial }} <br>
                            @endif
                            <span class="negrita">{{ $ventaSelect->TipoDoc }}:</span>
                            {{ $ventaSelect->NumeroDocumento }} <br>
                            <span class="negrita">Dirección:</span>
                            {{ substr($ventaSelect->DirCliente, 0, 62) }} <br>
                        </div>
                    </td>
                    <td class="borderTabla">
                        <div class="margen">
                            <span class="negrita">Fecha Emisión:</span> {{ $formatoFecha }} <br>
                            <span class="negrita">Hora Emisión:</span> {{ $formatoHora }} <br>
                            <span class="negrita">Tipo Operación:</span>
                            @if ($ventaSelect->TipoVenta == 1)
                                Gravada
                            @else
                                Exonerada
                            @endif <br>
                            <span class="negrita">Moneda: </span>
                            @if ($ventaSelect->IdTipoMoneda == 1)
                                Soles
                            @else
                                Dólares
                            @endif <br>
                            <span class="negrita">Usuario: </span> {{ $ventaSelect->Sucursal }}<br>
                            <span class="negrita">Sucursal:</span> {{ $ventaSelect->Local }}

                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        @php
            $a = 1;
            $b = 1;
        @endphp
        <table width="100%" class="tabla3">
            @if (count($itemsProd) > 0)
                <thead>
                    <tr>
                        <th class="borderTabla fondo" align="center">ITEM</th>
                        <th class="borderTabla fondo" align="center">CODIGO BARRA</th>
                        <th class="borderTabla fondo" align="center">CANTIDAD</th>
                        <th class="borderTabla fondo" align="center">DESCRIPCION PRODUCTO</th>
                        <th class="borderTabla fondo" align="center">MARCA</th>
                        <th class="borderTabla fondo" align="center">UBICACION</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($itemsProd as $item)
                        <tr>
                            <td class="borderTabla" align="center">{{ $a++ }}</td>
                            <td class="borderTabla" align="center">{{ $item->Cod }}</td>
                            <td class="borderTabla" align="center">{{ $item->Cantidad }} <span>
                                </span>{{ $item->TextUnidad }} </td>
                            <td class="borderTabla" align="center">{{ $item->Descripcion }}
                                {{ $item->Detalle }}
                            </td>
                            <td class="borderTabla" align="center">{{ $item->NombreMarca->nombreMarca }}</td>
                            <td class="borderTabla" align="center">{{ $item->Ubicacion }}</td>

                        </tr>
                    @endforeach
                </tbody>
            @endif
        </table>
        @if ($a > 28)
            <div class="page-break"></div>
        @endif
        <table width="100%" class="tabla3">
            @if (count($itemsServ) > 0)
                <thead>
                    <tr>
                        <th class="borderTabla fondo" align="center">Item</th>
                        <th class="borderTabla fondo" align="center">Código Servicio</th>
                        <th class="borderTabla fondo" align="center">Cantidad</th>
                        <th class="borderTabla fondo" align="center">Descripción de Servicio</th>
                        <th class="borderTabla fondo" align="center">Precio</th>
                        <th class="borderTabla fondo" align="center">Descuento</th>
                        <th class="borderTabla fondo" align="center">Importe</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($itemsServ as $item)
                        <tr>
                            <td class="borderTabla" align="center">{{ $b++ }}</td>
                            <td class="borderTabla" align="center">{{ $item->Cod }}</td>
                            <td class="borderTabla" align="center">{{ $item->Cantidad }} <span>
                                </span>{{ $item->TextUnidad }} </td>
                            <td class="borderTabla" align="center">{{ $item->Descripcion }}
                                {{ $item->Detalle }}
                            </td>
                            <td class="borderTabla" align="center">{{ $item->PrecioUnidadReal }}</td>
                            <td class="borderTabla" align="center">{{ $item->Descuento }}</td>
                            <td class="borderTabla" align="center">{{ $item->Importe }}</td>
                        </tr>
                    @endforeach
                </tbody>
            @endif
        </table>
        @php $c=$a + $b @endphp
        @if ($c > 28 && $c < 33)
            <div class="page-break"></div>
        @endif
        <table width="100%" class="tabla3">
            <tfoot>
                <tr>
                    <td colspan="7" class="borderTabla">
                        <div class="margen">
                            <span class="negrita">OBSERVACIONES: </span> @php echo nl2br($ventaSelect->Observacion) @endphp
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
        <br>
        @if ($ventaSelect->IdTipoComprobante == 3)
            <table width="100%">
                <tr>
                    <td align="center">Este documento no tiene ningún valor tributario, solo representa un ticket de
                        Pre-Venta</td>
                </tr>
            </table>
        @endif
    </div>
</body>

</html>
