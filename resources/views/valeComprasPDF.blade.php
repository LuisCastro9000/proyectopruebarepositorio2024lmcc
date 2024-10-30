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
</style>

<body>
    <div class="container">
        <table width="100%" class="tabla1">
            <tr>
                <td width="50%">
                    <table width="100%">
                        <tr>
                            <td align="center">
                                <div style="margin: 10px;" class="border p-x-y">
                                    <span class="h4">Este documento no tiene ningún valor tributario, es solo una
                                        representación impresa generado por el sistema para fines Administrativos</span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="20%">&nbsp;</td>
                <td width="30%">
                    <table width="100%">
                        <tr>
                            <td align="center">
                                <section class="border">
                                    <div style="margin-top: 10px;">
                                        <span class="h2">RUC: {{ $empresa->Ruc }}</span>
                                    </div>
                                    <div style="margin-left: 5px; margin-right: 5px;">
                                        <span class="h4">VALE ALMACEN</span>
                                    </div>
                                    <div style="margin-bottom: 10px;">
                                        <span
                                        class="h4">VAC{{ substr($compraSelect->Serie, 1) }}-{{ $numeroCeroIzq }}</span>
                                    </div>
                                </section>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td><span class="negrita">{{ $nombreEmpresa }}</span></td>
            </tr>
            <tr>
                <td>{{ $compraSelect->Local }} - {{ $compraSelect->Ciudad }}</td>
            </tr>
            <tr>
                <td>SUCURSAL: {{ $compraSelect->Sucursal }}</td>
            </tr>
            <tr>
                <td>TELÉFONO: {{ $compraSelect->TelfSucursal }}</td>
            </tr>
        </table>
        <table width="100%" class="tabla2">
            <thead>
                <tr>
                    <th width="65%" class="borderTabla fondo" align="center">DATOS DEL EMISOR</th>
                    <th width="35%" class="borderTabla fondo" align="center">DATOS GENERALES</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="borderTabla">
                        <div class="margen">
                            <span class="negrita">Raz. Social:</span> {{ $compraSelect->Nombres }} <br>
                            <span class="negrita">RUC/DNI:</span> {{ $compraSelect->NumeroDocumento }} <br>
                            <span class="negrita">Dirección:</span> {{ substr($compraSelect->DirProveedor, 0, 68) }}
                            <br>
                            <span class="negrita">Telefono:</span> {{ $compraSelect->TelfProveedor }} <br>
                            <span class="negrita">Moneda:</span> {{ $compraSelect->Moneda }} <br>
                        </div>
                    </td>
                    <td class="borderTabla">
                        <div class="margen">
                            <span class="negrita">Fecha Emisión:</span> {{ $formatoFecha }} <br>
                            <span class="negrita">Hora Emisión:</span> {{ $formatoHora }} <br>
                            <span class="negrita">Tipo de pago:</span>
                            @if ($compraSelect->IdTipoPago == 1)
                                Contado
                            @else
                                Crédito
                            @endif <br>
                            <span class="negrita">Tipo Operación:</span>
                            @if ($compraSelect->TipoCompra == 1)
                                Gravada
                            @else
                                Exonerada
                            @endif <br>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <table width="100%" class="tabla3">
            <thead>
                <tr>
                    <th class="borderTabla fondo" align="center">Item</th>
                    <th class="borderTabla fondo" align="center">Código Barra</th>
                    <th class="borderTabla fondo" align="center">Cantidad</th>
                    <th class="borderTabla fondo" align="center">Descripción Producto</th>
                    <th class="borderTabla fondo" align="center">Marca</th>
                    <th class="borderTabla fondo" align="center">Ubicacion</th>
                </tr>
            </thead>
            <tbody>
                @php $a=1 @endphp
                @foreach ($items as $item)
                    <tr>
                        <td class="borderTabla" align="center">{{ $a++ }}</td>
                        <td class="borderTabla" align="center">{{ $item->Codigo }}</td>
                        <td class="borderTabla" align="center">{{ $item->Cantidad }} {{ $item->UniMedida }}</td>
                        <td class="borderTabla" align="center">{{ $item->Descripcion }}</td>
                        <td class="borderTabla" align="center">{{ $item->NombreMarca->nombreMarca }}</td>
                        <td class="borderTabla" align="center">{{ $item->Ubicacion }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="borderTabla">
                        <div class="margen">
                            <span class="negrita">OBSERVACIONES: </span> @php echo nl2br( $compraSelect->Observacion ) @endphp
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
        {{-- <table width="100%" class="tabla4">
            <tr>
                <td width="20%">OBSERVACIÓN:</td>
                <td width="10%">&nbsp;</td>
            </tr>
            <tr>
                <td width="50%" style="border:0.5px solid #000;">
                    <div style="margin:8px 2px 8px 2px;">
                        {{ $compraSelect->Observacion }}
                    </div>
                </td>
                <td width="40%">&nbsp;</td>
            </tr>
        </table> --}}
        <table width="100%">
            <tr>
                <td align="right"><br><span class="negrita">SON: </span> {{$importeLetras}}</td>
            </tr>
        </table>
        {{-- <table width="100%" class="tabla4">
            <tr>
                <td width="15%">SON:</td>
                <td width="20%">{{ $importeLetras }}</td>
                <td width="10%">&nbsp;</td>
            </tr>
        </table> --}}
        <table width="100%" class="tabla4 borderTop borderBottom">
            <thead>
                <tr>
                    <td width="25%" align="center" class="fondo">
                        @if ($compraSelect->TipoCompra == 1)
                            OP GRAVADAS
                        @else
                            OP EXONERADAS
                        @endif
                    </td>
                    <td width="25%" align="center" class="fondo">
                        @if ($compraSelect->TipoCompra == 1)
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
                    <td class="borderTop" width="25%" align="center">{{ $compraSelect->Subtotal }}</td>
                    <td class="borderTop" width="25%" align="center">{{ $compraSelect->IGV }}</td>
                    <td class="borderTop" width="25%" align="center">{{ $compraSelect->Total }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
