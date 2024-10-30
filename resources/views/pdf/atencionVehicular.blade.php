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

    .h3 {
        font-size: 14px;
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
        margin-bottom: 20px;
    }

    .tabla4,
    .tabla3 {
        margin-top: 15px;
    }

    .tabla3 td {
        font-size: 10px;
    }

    .tabla3 th {
        font-size: 10.5px;
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
                                    <div>
                                        <br>
                                        <span class="h3">DETALLE DE ATENCION VEHICULAR</span>
                                    </div>
                                    <br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            {{-- <tr>
                <td><span class="negrita">{{ $empresa->Nombre }}</span></td>
            </tr>
            <tr>
                <td>{{ $empresa->Direccion }} - {{ $empresa->Distrito }}</td>
            </tr>
            <tr>
                <td>TELÉFONO: {{ $empresa->Telefono }}</td>
            </tr> --}}
        </table>
        {{-- NUEVO CODIGO --}}
        <table width="100%" class="tabla1">
            <tr>
                <td><span class="negrita">{{ $empresa->Nombre }}</span></td>
            </tr>
            <tr>
                <td><span class="negrita fs-10">Principal:</span><span
                        class="fs-10">{{ $empresa->DirPrincipal }}</span></td>
            </tr>
            @if ($sucursal != null)
                <tr>
                    <td><span class="negrita fs-10">Sucursal:</span></span><span class="fs-10">
                            {{ $sucursal->Direccion }}</span></td>
                </tr>
            @endif
            <tr>
                <td><span class="negrita fs-10">TELÉFONO:</span> {{ $empresa->Telefono }}</td>
            </tr>
        </table>
        {{-- FIN --}}
        <br>
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
                            <span class="negrita">Raz. Social:</span>{{ $ventaSelect->RazonSocial }}<br>
                            <span class="negrita">RUC/DNI:</span>{{ $ventaSelect->NumeroDocumento }}<br>
                            <span class="negrita">Direccion:</span>{{ $ventaSelect->Direccion }}<br>

                            <span class="negrita">Placa: </span>{{ $ventaSelect->PlacaVehiculo }} /
                            <span class="negrita">Marca: </span>{{ $ventaSelect->NombreMarca }} /
                            <span class="negrita">Modelo </span>{{ $ventaSelect->NombreModelo }} <br>

                            <span class="negrita">Nro. Flota: </span>{{ $ventaSelect->numeroFlota }} /
                            <span class="negrita">Kilometraje: </span>{{ $ventaSelect->Kilometro }} /
                            <span class="negrita">Año: </span>{{ $ventaSelect->Anio }} <br>

                            <span class="negrita">Fec. Venc. Soat: </span>{{ $ventaSelect->FechaSoat }}
                            <span class="negrita">Fec. Ven. Rev Tec:
                            </span>{{ $ventaSelect->FechaRevTecnica }}
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
                            {{-- <span class="negrita">Dirección: </span> {{$ventaSelect->Local}} <br> --}}
                            {{-- <span class="negrita">Operador: </span> {{$ventaSelect->Usuario}} --}}
                        </div>
                    </td>
                </tr>
        </table>

        {{-- <table width="100%" class="tabla2">
            <tr>
                <td width="100%"><strong>TRABAJOS A REALIZAR : </strong>{{ $ventaSelect->Trabajos }}</td>
            </tr>
        </table> --}}

        <table width="100%" class="tabla3">
            @if (count($itemsProd) > 0)
                <thead>
                    <tr>
                        <th class="borderTabla fondo" align="center">Item</th>
                        <th class="borderTabla fondo" align="center">Código Producto</th>
                        <th class="borderTabla fondo" align="center">Cantidad</th>
                        <th class="borderTabla fondo" align="center">Descripción / Detalle</th>
                        <th class="borderTabla fondo" align="center">Precio</th>
                        <th class="borderTabla fondo" align="center">Descuento</th>
                        <th class="borderTabla fondo" align="center">Importe</th>
                    </tr>
                </thead>
                <tbody>
                    @php $a=1 @endphp
                    @foreach ($itemsProd as $item)
                        <tr>
                            <td class="borderTabla" align="center">{{ $a++ }}</td>
                            <td class="borderTabla" align="center">{{ $item->Codigo }}</td>
                            <td class="borderTabla" align="center">{{ $item->Cantidad }} <span>
                                </span>{{ $item->TextUnidad }} </td>
                            <td class="borderTabla" align="center">{{ $item->Descripcion }} /
                                {{ $item->Detalle }}</td>
                            <td class="borderTabla" align="center">
                                {{ number_format($item->PrecioUnidadReal, 2, '.', ',') }}</td>
                            <td class="borderTabla" align="center">{{ number_format($item->Descuento, 2, '.', ',') }}
                            </td>
                            <td class="borderTabla" align="center">{{ number_format($item->Importe, 2, '.', ',') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            @endif
            @if (count($itemsServ) > 0)
                <thead>
                    <tr>
                        <th class="borderTabla fondo" align="center">Item</th>
                        <th class="borderTabla fondo" align="center">Código Servicio</th>
                        <th class="borderTabla fondo" align="center">Cantidad</th>
                        <th class="borderTabla fondo" align="center">Descripción / Detalle</th>
                        <th class="borderTabla fondo" align="center">Precio</th>
                        <th class="borderTabla fondo" align="center">Descuento</th>
                        <th class="borderTabla fondo" align="center">Importe</th>
                    </tr>
                </thead>
                <tbody>
                    @php $a=1 @endphp
                    @foreach ($itemsServ as $item)
                        <tr>
                            <td class="borderTabla" align="center">{{ $a++ }}</td>
                            <td class="borderTabla" align="center">{{ $item->Codigo }}</td>
                            <td class="borderTabla" align="center">{{ $item->Cantidad }} <span>
                                </span>{{ $item->TextUnidad }} </td>
                            <td class="borderTabla" align="center">{{ $item->Descripcion }} / {{ $item->Detalle }}
                            </td>
                            <td class="borderTabla" align="center">
                                {{ number_format($item->PrecioUnidadReal, 2, '.', ',') }}</td>
                            <td class="borderTabla" align="center">{{ number_format($item->Descuento, 2, '.', ',') }}
                            </td>
                            <td class="borderTabla" align="center">{{ number_format($item->Importe, 2, '.', ',') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            @endif
            <tfoot>
                <tr>
                    <td colspan="7" class="borderTabla">
                        <div class="margen">
                            <span class="negrita">OBSERVACIONES: </span>
                            @if ($ventaSelect->Observacion != null || $ventaSelect->Observacion != '')
                                @php echo nl2br($ventaSelect->Observacion) @endphp
                            @else
                            @endif
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
        <br>
        <table width="100%">
            <tr>
                <td align="right"><span class="negrita">SON: </span> {{ $importeLetras }}</td>
            </tr>
        </table>

        <table width="100%" class="tabla4 borderTop borderBottom">
            <thead>
                <tr>
                    <td width="25%" align="center" class="fondo">
                        @if ($ventaSelect->TipoVenta == 1)
                            OP GRAVADAS
                        @else
                            OP EXONERADAS
                        @endif
                    </td>
                    <td width="25%" align="center" class="fondo">TOTAL DESCUENTO</td>
                    <td width="25%" align="center" class="fondo">
                        @if ($ventaSelect->TipoVenta == 1)
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
                    <td class="borderTop" width="25%" align="center">
                        {{ number_format($ventaSelect->SubTotal, 2, '.', ',') }}</td>
                    <td class="borderTop" width="25%" align="center">
                        {{ number_format($ventaSelect->Exonerada, 2, '.', ',') }}</td>
                    <td class="borderTop" width="25%" align="center">
                        {{ number_format($ventaSelect->Igv, 2, '.', ',') }}</td>
                    <td class="borderTop" width="25%" align="center">
                        {{ number_format($ventaSelect->Total, 2, '.', ',') }}</td>
                </tr>
            </tbody>
        </table>
        <table width="100%">
            <tr>
                <td align="center">Este documento no tiene ningún valor tributario, solo representa un detalle de la
                    atención vehicular</td>
            </tr>
        </table>
    </div>
</body>

</html>
