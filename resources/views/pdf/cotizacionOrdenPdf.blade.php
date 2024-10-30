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
        margin-bottom: 5px;
    }

    .tabla2 {
        margin-bottom: 5px;
    }

    .tabla4,
    .tabla3 {
        margin-top: 5px;
    }

    .tabla3 td {
        font-size: 9px;
        height: 22px;
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

    .fs-12 {
        font-size: 12px;
    }

    .fs-11 {
        font-size: 11px;
    }

    .fs-10 {
        font-size: 10px;
    }

    .fs-9 {
        font-size: 9px;
    }

    .fs-8 {
        font-size: 8px;
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
                        <img src="{{ config('variablesGlobales.urlDominioAmazonS3') . $empresa->Imagen }}"
                            alt="" width="250" height="110">
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
                                        <span class="h2">RUC: {{ $empresa->Ruc }}</span>
                                    </div>
                                    <div>
                                        <span class="h2">ORDEN DE SERVICIO</span>
                                    </div>
                                    <div style="margin-bottom: 15px;">
                                        <span class="h2">{{ $ventaSelect->Serie }} - {{ $numeroCeroIzq }}</span>
                                        <span class="h5">ESTADO : {{ strtoupper($ventaSelect->EstadoCoti) }}</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        {{-- Nueva Tabla --}}
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
        {{-- Fin Tabla --}}
        {{-- Nuevo codigo --}}
        @if ($datosNotificacionMantenimiento != null)
            <br>
            <table width="100%" class="tabla1">
                <tr>
                    <td width="50%">
                        <span class="negrita fs-10">Próximo Mantenimiento: </span><span
                            class="fs-12">{{ $datosNotificacionMantenimiento->ProximoMantenimiento }}</span>
                    </td>

                    <td width="50%" align="right">
                        <span class="negrita fs-10">Fecha Apróximada: </span><span
                            class="fs-12">{{ $datosNotificacionMantenimiento->ProximaFecha }}</span>
                    </td>
                </tr>
            </table>
            <br>
        @endif
        {{-- Fin --}}
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
                            @if ($ventaSelect->TipoCotizacion == 2)
                                @if ($modulosSelect->contains('IdModulo', 5) && $idSeguro > 2)
                                    <span class="negrita">Raz. Social:</span> {{ $seguro }} <br>
                                    <span class="negrita">Responsable Asegurado:</span> {{ $ventaSelect->RazonSocial }}
                                    <br>
                                @else
                                    <span class="negrita">Raz. Social:</span> {{ $ventaSelect->RazonSocial }} <br>
                                @endif
                            @else
                                <span class="negrita">Raz. Social:</span> {{ $ventaSelect->RazonSocial }} <br>
                            @endif
                            <span class="negrita">RUC/DNI:</span> {{ $ventaSelect->NumeroDocumento }} / <span
                                class="negrita">celular:</span> {{ $ventaSelect->TelfCliente }}<br>
                            <span class="negrita">Dirección:</span> {{ substr($ventaSelect->DirCliente, 0, 68) }} <br>
                            <span class="negrita">Fecha Vencimiento:</span> {{ $formatoFechaFin }} <br>
                            @if ($ventaSelect->TipoCotizacion == 2)
                                <span class="negrita">Placa: </span> {{ $vehiculo->PlacaVehiculo }} / <span
                                    class="negrita">Marca: </span> {{ $marca }} / <span class="negrita">Modelo:
                                </span> {{ $modelo }} / <span class="negrita">Nro Flota: </span>
                                {{ $numeroFlota }}<br>
                                <span class="negrita">Kilometraje: </span> {{ $ventaSelect->Campo1 }} / <span
                                    class="negrita">Año:</span> {{ $anio }} / <span class="negrita">Tipo
                                    Aten.:</span> {{ $ventaSelect->Atencion }}
                            @endif
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
                            <span class="negrita">Operador: </span> {{ $ventaSelect->Usuario }}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>



        <table width="100%" class="tabla3">
            @if (count($itemsProd) > 0)
                <thead>
                    <tr>
                        <th class="borderTabla fondo" colspan="6" scope="col"
                            style="font-size: 13px;padding:4px 0px 4px 0px">Productos
                        </th>
                    </tr>
                    <tr>
                        <th class="borderTabla fondo" align="center">Item</th>
                        <th class="borderTabla fondo" align="center">Cantidad</th>
                        <th class="borderTabla fondo" style="width:340px" align="center">Descripción / Detalle</th>
                        <th class="borderTabla fondo" align="center">Ubicación</th>
                        <th class="borderTabla fondo" align="center">Marca</th>
                        <th class="borderTabla fondo" align="center">CodigoBarra</th>
                    </tr>
                </thead>
                <tbody>
                    @php $a=1 @endphp
                    @foreach ($itemsProd as $item)
                        <tr>
                            <td class="borderTabla" align="center" style="width:40px">{{ $a++ }}</td>
                            <td class="borderTabla" align="center" style="width:100px">{{ $item->Cantidad }} <span>
                                </span>{{ $item->TextUnidad }} </td>
                            <td class="borderTabla" align="center">{{ $item->Descripcion }} / {{ $item->Detalle }}
                            </td>
                            <td class="borderTabla" align="center">{{ $item->Ubicacion }}</td>
                            <td class="borderTabla" align="center">{{ $item->Marca }}</td>
                            <td class="borderTabla" align="center">{{ $item->CodigoArticulo }}</td>
                        </tr>
                    @endforeach
                </tbody>
            @endif
        </table>
        <table width="100%" class="tabla3">
            @if (count($itemsServ) > 0)
                <thead>
                    <tr>
                        <th class="borderTabla fondo fs-20" colspan="4" scope="col"
                            style="font-size: 13px;padding:4px 0px 4px 0px">
                            SERVICIOS</th>
                    </tr>
                    <tr>
                        <th class="borderTabla fondo" align="center">Item</th>
                        <th class="borderTabla fondo" align="center">Cantidad</th>
                        <th class="borderTabla fondo" style="width:340px" align="center">Descripción / Detalle</th>
                        <th class="borderTabla fondo" align="center">CodigoBarra</th>
                    </tr>
                </thead>
                <tbody>
                    @php $a=1 @endphp
                    @foreach ($itemsServ as $item)
                        <tr>
                            <td class="borderTabla" align="center" style="width:40px">{{ $a++ }}</td>
                            <td class="borderTabla" align="center" style="width:100px">{{ $item->Cantidad }} <span>
                                </span>{{ $item->TextUnidad }} </td>
                            <td class="borderTabla" align="center">{{ $item->Descripcion }} / {{ $item->Detalle }}
                            </td>
                            <td class="borderTabla" align="center">{{ $item->CodigoArticulo }}</td>

                        </tr>
                    @endforeach
                </tbody>
            @endif
        </table>
        <table width="100%" class="tabla3">
            <tfoot>
                <tr>
                    <td colspan="6" class="borderTabla">
                        <div class="margen">
                            <span class="negrita">OBSERVACIONES: </span> @php echo nl2br($ventaSelect->Observacion) @endphp
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" class="borderTabla">
                        <div class="margen">
                            <span class="negrita">Operario: </span> {{ $operario }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" class="borderTabla">
                        <div class="margen">
                            <span class="negrita">Trabajo a realizar: </span> <br> @php echo nl2br($ventaSelect->Trabajos) @endphp
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>
