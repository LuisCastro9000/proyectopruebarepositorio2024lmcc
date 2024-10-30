<!DOCTYPE html>
<html>
<style type="text/css">
    @page {
        margin: 5px 30px;
    }

    body {
        font-size: 10px;
        font-family: DejaVu Sans, "sans-serif";
        line-height: 10.5px;
    }

    table {
        border-collapse: collapse;
    }

    td {
        padding: 0.5px;
        /* font-size: 10px; */
        font-size: 9px;
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
        line-height: 10px;
        margin-bottom: 3px;
    }

    .tabla2 td {
        font-size: 10px;
    }

    .tabla3 {
        text-transform: uppercase;
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
        width: auto;
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

    .margen {
        margin: 3px;
    }

    .cursiva {
        font-style: oblique;
    }

    .pd-4 {
        padding: 10px;
    }

    .cuadradoBueno {
        width: 28px;
        height: 18px;
        background: #fff;
        border: 1px solid #000;
    }

    .fs-18 {
        font-size: 18px;
    }

    .fs-14 {
        font-size: 14px;
    }

    .fs-9 {
        font-size: 9px;
        line-height: 8px;
    }

    .cuadradoRegular {
        width: 28px;
        height: 18px;
        background: #8f8d8d;
        border: 1px solid #000;
    }

    .cuadradoMalo {
        width: 28px;
        height: 18px;
        background: #ff0000;
        border: 1px solid #000;
    }

    .contenedorImagenFirma {
        margin: auto;
        width: 30%;
        height: 40px;
    }

    #imgFirma {
        width: 100%;
        height: 100%;
    }
</style>

<body>
    <div class="container">
        <table width="100%" class="tabla3">
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
                    <div style="margin: 0px 2px 0px 2px;text-align: center; ">
                        {{ $empresa->Descripcion }}
                    </div>
                </td>
                <td width="30%">
                    <table width="100%">
                        <tr>
                            <td align="center">
                                <div class="border">
                                    <div style="margin-top: 15px;">
                                        <span class="h5">RUC: {{ $empresa->Ruc }}</span>
                                    </div>
                                    <div>
                                        <span class="h5">INVENTARIO VEHICULAR </span>
                                    </div>
                                    <div style="margin-bottom: 15px;">
                                        <span class="h5">{{ $inventario->Serie }} -
                                            {{ $inventario->Correlativo }}</span>
                                    </div>
                                </div>
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
        <table width="100%" class="tabla1">
            <tr>
                <td><span class="negrita">{{ $empresa->Nombre }}</span></td>
            </tr>
            <tr>
                <td><span class="negrita fs-10">Principal:</span><span class="fs-10">{{ $empresa->DirPrincipal }} -
                        {{ $empresa->Distrito }}</span></td>
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
        <table width="100%" class="tabla2">
            <thead>
                <tr>
                    <th width="50%" class="borderTabla fondo" align="center">DATOS GENERALES</th>
                    <th width="50%" class="borderTabla fondo" align="center">DATOS DEL VEHÍCULO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="borderTabla">
                        <div class="margen">
                            <span class="negrita">Raz. Social:</span> {{ $inventario->RazonSocial }} <br>
                            <span class="negrita">Dirección:</span>
                            {{ substr($inventario->Direccion, 0, 55) }}
                            <br>
                            <span class="negrita">Placa:</span> {{ $inventario->Placa }} /
                            <span class="negrita">Marca:</span> {{ $inventario->NombreMarca }} /
                            <span class="negrita">Modelo:</span> {{ $inventario->NombreModelo }} <br>
                            <span class="negrita">Año:</span> {{ $inventario->Anio }} /
                            {{-- <span class="negrita">Nro Flota:</span>
                            {{ $datosVehiculo->NumeroFlota != '' ? '$datosVehiculo->NumeroFlota' : '-' }}  --}}
                            <span class="negrita">Kilometraje:</span> {{ $inventario->Kilometraje }} /
                            <span class="negrita">Celular:</span>
                            {{ $inventario->Telefono }}<br>
                            <span class="negrita">Asesor Comercial:</span> {{ $inventario->Nombre }}
                        </div>
                    </td>
                    <td class="borderTabla">
                        <div class="margen">
                            <span class="negrita">Color:</span> {{ $datosVehiculo->Color }} / <span
                                class="negrita">Año:</span> {{ $datosVehiculo->Anio }} <br>
                            <span class="negrita">Chasis / VIN:</span> {{ $datosVehiculo->ChasisVehiculo }}
                            <br>
                            <span class="negrita">Venc. Soat:</span> {{ $datosVehiculo->FechaSoat }} <br>
                            <span class="negrita">Venc Rev. Tec.:</span> {{ $datosVehiculo->FechaRevTecnica }}
                            <br>
                            <span class="negrita">Fecha:</span> {{ $formatoFecha }} / <span
                                class="negrita">Hora:</span> {{ $formatoHora }} <br>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <table width="100%">
            <tr>
                <td style="vertical-align:top;width:360px">
                    <table width="95%">
                        <tr align="center" style="width:100%">
                            <th colspan="3"><span class="negrita">ESTADOS</span></th>
                        </tr>
                        <tr style="width:100%">
                            <th width="33%" class="borderTabla"><span class="negrita">Bueno</span></th>
                            <th width="33%" class="borderTabla"><span class="negrita">Regular</span></th>
                            <th width="33%" class="borderTabla"><span class="negrita">Malo</span></th>
                        </tr>
                        <tr style="width:100%">
                            <th class="borderTabla">
                                <div class="cuadradoBueno"></div>
                            </th>
                            <th class="borderTabla">
                                <div class="cuadradoRegular"></div>
                            </th>
                            <th class="borderTabla">
                                <div class="cuadradoMalo"></div>
                            </th>
                        </tr>
                    </table>
                    <br>
                    <table width="95%">
                        <tr align="center" style="width:100%">
                            <th colspan="5"><span class="negrita">ACCESORIOS EXTERNOS</span></th>
                        </tr>
                        <tr align="center" style="width:100%">
                            <th class="borderTabla" style="width:56%"><span class="negrita">Descripción</span>
                            </th>
                            <th class="borderTabla" style="width:10%"><span class="negrita">Cant.</span></th>
                            <th style="width:9%" class="borderTabla">
                                <div class="cuadradoBueno"></div>
                            </th>
                            <th style="width:9%" class="borderTabla">
                                <div class="cuadradoRegular"></div>
                            </th>
                            <th style="width:9%" class="borderTabla">
                                <div class="cuadradoMalo"></div>
                        </tr>
                        @foreach ($accesoriosExternos as $item)
                            <tr>
                                <td class="borderTabla">
                                    <span>{{ $item->Descripcion }}</span>
                                </td>
                                @if ($item->Estado != 'E')
                                    <td class="borderTabla" align="center">
                                        <span>{{ $item->Cantidad }}</span>
                                    </td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->Estado == 1)
                                            <span>✔</span>
                                        @else
                                            <span></span>
                                        @endif
                                    </td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->Estado == 2)
                                            <span>✔</span>
                                        @else
                                            <span></span>
                                        @endif
                                    </td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->Estado == 3)
                                            <span>✔</span>
                                        @else
                                            <span></span>
                                        @endif
                                    </td>
                                @else
                                    <td class="borderTabla">
                                    </td>
                                    <td class="borderTabla" align="center">
                                    </td>
                                    <td class="borderTabla">
                                    </td>
                                    <td class="borderTabla">
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </table>
                    <br>
                    <table width="95%">
                        <tr align="center" style="width:100%">
                            <th colspan="2" class="borderTabla"><span class="negrita">Autorizaciones</span>
                            </th>
                        </tr>
                        <tr>
                            <td class="borderTabla" style="padding:2px 1px">
                                <span class="fs-9">Autorizo conducir mi vehículo para pruebas en exteriores
                                    del Taller</span>
                            </td>
                            <td class="borderTabla" align="center" style="width:30px">
                                @if ($autorizaciones[0])
                                    <span>✔</span>
                                @else
                                    <span></span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="borderTabla" style="padding:2px 1px">
                                <span class="fs-9">Autorizo enviar mi vehículo para trabajos de terceros en
                                    Talleres de su elección</span>
                            </td>
                            <td class="borderTabla" align="center" style="width:30px">
                                @if ($autorizaciones[1])
                                    <span>✔</span>
                                @else
                                    <span></span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="borderTabla" style="padding:2px 1px">
                                <span class="fs-9">Declaro que no existen elementos de valor dentro del
                                    vehículo</span>
                            </td>
                            <td class="borderTabla" align="center" style="width:30px">
                                @if ($autorizaciones[2])
                                    <span>✔</span>
                                @else
                                    <span></span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="borderTabla" style="padding:2px 1px">
                                <span class="fs-9">Acepto retirar mi vehículo en un máximo de
                                    @if ($autorizaciones[3])
                                        {{ $autorizaciones[3]->Dias }}
                                    @else
                                        XX
                                    @endif días, luego de finalizado el servicio; caso contrario
                                    asumiré un costo de
                                    S/ @if ($autorizaciones[3])
                                        {{ $autorizaciones[3]->Monto }}
                                    @else
                                        XX
                                    @endif diarios por cochera (interna y/o externa)
                                </span>
                            </td>
                            <td class="borderTabla" align="center" style="width:30px">
                                @if ($autorizaciones[3])
                                    <span>✔</span>
                                @else
                                    <span></span>
                                @endif
                            </td>
                        </tr>
                    </table>
                    <br>
                    <table width="95%">
                        <tr align="center" style="width:100%">
                            <th class="borderTabla"><span class="negrita">Observaciones</span></th>
                        </tr>
                        <tr style="width:100%">
                            @if ($inventario->Observacion)
                                <td class="borderTabla">
                                    {{ $inventario->Observacion }}
                                </td>
                            @else
                                <td class="borderTabla">
                                    <br>
                                </td>
                            @endif
                        </tr>
                    </table>
                </td>
                <td style="vertical-align:top;width:360px">
                    @if ($tipoVehiculo == 'vehiculo')
                        <table width="100%">
                            <tr align="center" style="width:100%">
                                <th colspan="5"><span class="negrita">ACCESORIOS INTERNOS</span></th>
                            </tr>
                            <tr align="center" style="width:100%">
                                <th class="borderTabla" style="width:56%"><span class="negrita">Descripción</span>
                                </th>
                                <th class="borderTabla" style="width:10%"><span class="negrita">Cant.</span>
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <div class="cuadradoBueno"></div>
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <div class="cuadradoRegular"></div>
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <div class="cuadradoMalo"></div>
                                </th>
                            </tr>
                            @foreach ($accesoriosInternos as $item)
                                <tr>
                                    <td class="borderTabla">
                                        <span>{{ $item->Descripcion }}</span>
                                    </td>
                                    @if ($item->Estado != 'E')
                                        <td class="borderTabla" align="center">
                                            <span>{{ $item->Cantidad }}</span>
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->Estado == 1)
                                                <span>✔</span>
                                            @else
                                                <span></span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->Estado == 2)
                                                <span>✔</span>
                                            @else
                                                <span></span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->Estado == 3)
                                                <span>✔</span>
                                            @else
                                                <span></span>
                                            @endif
                                        </td>
                                    @else
                                        <td class="borderTabla">
                                        </td>
                                        <td class="borderTabla" align="center">
                                        </td>
                                        <td class="borderTabla">
                                        </td>
                                        <td class="borderTabla">
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </table>
                        <br>
                    @endif
                    <table width="100%">
                        <tr align="center">
                            <th colspan="5"><span class="negrita">HERRAMIENTAS</span></th>
                        </tr>
                        <tr align="center">
                            <th class="borderTabla" style="width:56%"><span class="negrita">Descripción</span></th>
                            <th class="borderTabla" style="width:10%"><span class="negrita">Cant.</span>
                            </th>
                            <th style="width:9%" class="borderTabla">
                                <div class="cuadradoBueno"></div>
                            </th>
                            <th style="width:9%" class="borderTabla">
                                <div class="cuadradoRegular"></div>
                            </th>
                            <th style="width:9%" class="borderTabla">
                                <div class="cuadradoMalo"></div>
                            </th>
                        </tr>
                        @foreach ($herramientas as $item)
                            <tr>
                                <td class="borderTabla">
                                    <span>{{ $item->Descripcion }}</span>
                                </td>
                                @if ($item->Estado != 'E')
                                    <td class="borderTabla" align="center">
                                        <span>{{ $item->Cantidad }}</span>
                                    </td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->Estado == 1)
                                            <span>✔</span>
                                        @else
                                            <span></span>
                                        @endif
                                    </td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->Estado == 2)
                                            <span>✔</span>
                                        @else
                                            <span></span>
                                        @endif
                                    </td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->Estado == 3)
                                            <span>✔</span>
                                        @else
                                            <span></span>
                                        @endif
                                    </td>
                                @else
                                    <td class="borderTabla">
                                    </td>
                                    <td class="borderTabla" align="center">
                                    </td>
                                    <td class="borderTabla">
                                    </td>
                                    <td class="borderTabla">
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </table>
                    <br>
                    <table width="100%">
                        <tr align="center">
                            <th colspan="5"><span class="negrita">DOCUMENTOS DE VEHÍCULO</span></th>
                        </tr>
                        <tr align="center">
                            <th class="borderTabla" style="width:56%"><span class="negrita">Descripción</span></th>
                            <th class="borderTabla" style="width:10%"><span class="negrita">Cant.</span>
                            </th>
                            <th style="width:9%" class="borderTabla">
                                <div class="cuadradoBueno"></div>
                            </th>
                            <th style="width:9%" class="borderTabla">
                                <div class="cuadradoRegular"></div>
                            </th>
                            <th style="width:9%" class="borderTabla">
                                <div class="cuadradoMalo"></div>
                            </th>
                        </tr>
                        @foreach ($documentosVehiculo as $item)
                            <tr>
                                <td class="borderTabla">
                                    <span>{{ $item->Descripcion }}</span>
                                </td>
                                @if ($item->Estado != 'E')
                                    <td class="borderTabla" align="center">
                                        <span>{{ $item->Cantidad }}</span>
                                    </td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->Estado == 1)
                                            <span>✔</span>
                                        @else
                                            <span></span>
                                        @endif
                                    </td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->Estado == 2)
                                            <span>✔</span>
                                        @else
                                            <span></span>
                                        @endif
                                    </td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->Estado == 3)
                                            <span>✔</span>
                                        @else
                                            <span></span>
                                        @endif
                                    </td>
                                @else
                                    <td class="borderTabla">
                                    </td>
                                    <td class="borderTabla" align="center">
                                    </td>
                                    <td class="borderTabla">
                                    </td>
                                    <td class="borderTabla">
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </table>
                    <br>
                    <table width="100%">
                        <tr align="center">
                            <td><span class="negrita">NIVEL DE GASOLINA</span></td>
                            <td>
                                @if ($inventario->NivelGasolina == 25)
                                    <img src="{{ asset('assets/img/Gasolina_25.jpg') }}" width="130px"
                                        height="85px">
                                @elseif($inventario->NivelGasolina == 50)
                                    <img src="{{ asset('assets/img/Gasolina_50.jpg') }}" width="130px"
                                        height="85px">
                                @elseif($inventario->NivelGasolina == 75)
                                    <img src="{{ asset('assets/img/Gasolina_75.jpg') }}" width="130px"
                                        height="85px">
                                @else
                                    <img src="{{ asset('assets/img/Gasolina_100.jpg') }}" width="130px"
                                        height="85px">
                                @endif
                            </td>
                        </tr>
                        <tr align="center">
                            <td colspan="2">
                                @if ($empresa->PaginaWeb != null || $empresa->CorreoEmpresa != null)
                                    @if (
                                        $inventario->ImagenCarro !== null &&
                                            !str_contains($inventario->ImagenCarro, config('variablesGlobales.urlDominioAmazonS3Anterior')))
                                        <img src="{{ str_contains($inventario->ImagenCarro, config('variablesGlobales.urlDominioAmazonS3'))
                                            ? $inventario->ImagenCarro
                                            : config('variablesGlobales.urlDominioAmazonS3') . $inventario->ImagenCarro }}"
                                            width="280px" height="260px">
                                    @else
                                        <img src="{{ $inventario->TipoVehicular == 1 ? asset('assets/img/img-vehiculo.jpeg') : asset('assets/img/img-motocicleta.jpg') }}"
                                            width="280px" height="260px">
                                    @endif
                                @else
                                    @if (
                                        $inventario->ImagenCarro !== null &&
                                            !str_contains($inventario->ImagenCarro, config('variablesGlobales.urlDominioAmazonS3Anterior')))
                                        <img src="{{ str_contains($inventario->ImagenCarro, config('variablesGlobales.urlDominioAmazonS3'))
                                            ? $inventario->ImagenCarro
                                            : config('variablesGlobales.urlDominioAmazonS3') . $inventario->ImagenCarro }}"
                                            width="320px" height="295px">
                                    @else
                                        <img src="{{ $inventario->TipoVehicular == 1 ? asset('assets/img/img-vehiculo.jpeg') : asset('assets/img/img-motocicleta.jpg') }}"
                                            width="320px" height="295px">
                                    @endif
                                @endif
                            </td>
                        </tr>
                    </table>

                    <table width="100%">
                        <tr>
                            <td align="center">
                                <section class="contenedorImagenFirma">
                                    @if (
                                        $inventario->Imagen !== null &&
                                            !str_contains($inventario->Imagen, config('variablesGlobales.urlDominioAmazonS3Anterior')))
                                        <img id="imgFirma"
                                            src="{{ str_contains($inventario->Imagen, config('variablesGlobales.urlDominioAmazonS3'))
                                                ? $inventario->Imagen
                                                : config('variablesGlobales.urlDominioAmazonS3') . $inventario->Imagen }}">
                                    @endif
                                </section>
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                -----------------------------------------------------------------------------------------<br>
                                Declaro haber dejado mi vehículo según este Check-List, <br> aceptando y autorizando lo
                                detallado</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div class="abajo">

        </div>
    </div>
</body>

</html>
