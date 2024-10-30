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
                            <td align="center" style="border: 1px solid #000;margin:10px;">
                                <div>
                                    <span class="h4">GUÍA DE REMISIÓN </span><br />
                                    <span class="h4">ELECTRÓNICA - REMITENTE</span>
                                </div>
                                <div style="margin-top: 5px;">
                                    <span class="h4">RUC: {{ $empresa->Ruc }}</span>
                                </div>
                                <div style="margin-bottom: 15px;">
                                    <span class="h4">{{ $guiaSelect->Serie }}-{{ $numeroCeroIzq }}</span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td><span class="negrita">{{ $empresa->Nombre }}</span></td>
                @if ($guiaSelect->Principal != 1)
                    <td align="right">SUCURSAL: {{ $guiaSelect->Sucursal }}</span></td>
                @endif

            </tr>
            <tr>
                <td>{{ $empresa->Direccion }} - {{ $empresa->Ciudad }}</td>
                @if ($guiaSelect->Principal != 1)
                    <td align="right">DIRECCION: {{ $guiaSelect->Local }} {{ $guiaSelect->Ciudad }}</td>
                @endif
            </tr>
            <tr>
                <td>TELÉFONO: {{ $empresa->Telefono }}</td>
                @if ($guiaSelect->Principal != 1)
                    <td align="right">TELÉFONO: {{ $guiaSelect->TelfSucursal }}</td>
                @endif
            </tr>
        </table>
        <table width="100%" class="tabla2 mt-2">
            <tr class="margen">
                <td class="borderBottom negrita" colspan="5">DESTINO</td>
            </tr>
            <tr>
                <td width="20%">NOMBRE/RAZÓN SOCIAL:</td>
                <td width="25%">{{ $guiaSelect->RazonSocial }}</td>
                <td width="15%">&nbsp;</td>
                <td width="15%">RUC/DNI:</td>
                <td width="18%">{{ $guiaSelect->NumDocumento }}</td>
            </tr>
            <tr>
                <td width="20%">DIRECCIÓN:</td>
                <td width="25%">{{ $guiaSelect->DirCliente }}</td>
                <td width="15%">&nbsp;</td>
                <td width="15%">TELÉFONO:</td>
                <td width="18%">{{ $guiaSelect->TelfCliente }}</td>
            </tr>
        </table>
        <table width="100%" class="tabla2">
            <tr>
                <td class="borderBottom negrita" colspan="5">ENVIO</td>
            </tr>
            <tr>
                <td width="20%">FECHA EMISIÓN:</td>
                <td width="25%">{{ $formatoFecha }}</td>
                <td width="15%">&nbsp;</td>
                <td width="15%">FECHA TRASL.:</td>
                <td width="18%">{{ $formatoFecha2 }}</td>
            </tr>
            <tr>
                <td width="20%">MOTIVO DE TRANSLADO:</td>
                <td width="25%">{{ $guiaSelect->Motivo }}</td>
                <td width="15%">&nbsp;</td>
                <td width="15%">PESO(Kg):</td>
                <td width="18%">{{ $guiaSelect->Peso }}</td>
            </tr>
            <tr>
                <td width="20%">BULTOS:</td>
                <td width="25%">{{ $guiaSelect->Bultos }}</td>
            </tr>
        </table>

        <table width="100%" class="tabla2">
            <tr>
                <td class="borderBottom negrita" colspan="5">DATOS DEL PUNTO DE ENVIO Y PUNTO DE LLEGADA</td>
            </tr>
            <tr>
                <td width="30%">DIRECCION DEL PUNTO DE PARTIDA:</td>
                <td width="40%">{{ $guiaSelect->Origen }}</td>
                <td width="10%">&nbsp;</td>
                <td width="20%">&nbsp;</td>
            </tr>
            <tr>
                <td width="30%">DIRECCION DEL PUNTO DE LLEGADA:</td>
                <td width="40%">{{ $guiaSelect->Destino }}</td>
                <td width="10%">&nbsp;</td>
                <td width="20%">&nbsp;</td>

            </tr>
        </table>

        <table width="100%" class="tabla2">
            <tr>
                <td class="borderBottom negrita" colspan="5">TRANSPORTE</td>
            </tr>
            <tr>
                <td width="20%">TRANSPORTISTA:</td>
                <td width="25%">{{ $guiaSelect->Transportista }}</td>
                <td width="15%">&nbsp;</td>
                <td width="15%">DNI/RUC:</td>
                <td width="18%">{{ $guiaSelect->NumeroDocumento }}</td>
            </tr>
            <tr>
                <td width="20%">RAZON SOCIAL:</td>
                <td width="25%">{{ $guiaSelect->RazonSocialTransp }}</td>
                <td width="15%">&nbsp;</td>
                <td width="15%">RUC:</td>
                <td width="18%">{{ $guiaSelect->RucTransp }}</td>
            </tr>
            <tr>
                <td width="20%">PLACA:</td>
                <td width="25%">{{ $guiaSelect->PlacaVehicular }}</td>
            </tr>
        </table>
        <table width="100%" class="tabla3">
            <thead>
                <tr>
                    <th class="borderTabla" align="center">Item</th>
                    <th class="borderTabla" align="center">Código</th>
                    <th class="borderTabla" align="center">Descripción</th>
                    <th class="borderTabla" align="center">Und. Medida</th>
                    <th class="borderTabla" align="center">Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @php $a=1 @endphp
                @foreach ($items as $item)
                    <tr>
                        <td class="borderTabla" align="center">{{ $a++ }}</td>
                        <td class="borderTabla" align="center">{{ $item->Cod }}</td>
                        <td class="borderTabla" align="center">{{ $item->Descripcion }} {{ $item->TextUnidad }}</td>
                        <td class="borderTabla" align="center">{{ $item->CodUnidad }}</td>
                        <td class="borderTabla" align="center">{{ $item->Cantidad }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table width="100%" class="mt-2">
            <tr>
                <td width="12%">OBSERVACIONES:</td>
                <td width="25%">{{ $guiaSelect->Observacion }}</td>
                <td width="15%">&nbsp;</td>
            </tr>
        </table>
        <!--<table width="100%" class="borderPlomo mt-2">
            <tr class="margen">
                <td class="negrita">Observaciones de Sunat:</td>
            </tr>
            <tr class="margen">
                <td>La Guía de Remisión {{ $guiaSelect->Serie }}-{{ $guiaSelect->Numero }}, ha sido aceptada</td>
            </tr>
        </table>
        <table class="mt-2" width="100%">
            <tr>
                <td class="cursiva">Autorizado a ser emisor electrónico mediante R.I. SUNAT Nº</td>
            </tr>
        </table>-->
        <div class="abajo">
            <table width="100%">
                <tr>
                    <td align="left">
                        <div>
                            <span>Consulte en <a href="" target="_blank">www.mifacturita.pe</a></span>
                        </div>
                        <div>
                            Resumen: <span class="negrita">{{ $guiaSelect->Hash }}</span>
                        </div>
                        <div>
                            <span>Representación Impresa de la GUÍA DE REMISIÓN</span>
                        </div>
                    </td>
                    <td align="right">
                        <img src="data:image/png;base64, {!! base64_encode(
                            QrCode::format('png')->size(120)->generate($resumen),
                        ) !!} ">
                    </td>
                </tr>
            </table>
        </div>

    </div>
</body>

</html>
