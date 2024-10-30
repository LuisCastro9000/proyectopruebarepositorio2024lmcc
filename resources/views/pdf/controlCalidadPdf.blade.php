<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Control Calidad</title>
    <style type="text/css">
        @page {
            margin: 0cm 0cm;
            /* margin: 5px 30px; */
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 1cm;
            right: 1cm;
            height: 4cm;

            /** Estilos extra personales **/
            /* color: white;
            text-align: center;
            line-height: 5cm; */
        }

        .contenedorImagenFirma {
            margin: auto;
            width: 100px;
            height: 40px;
        }

        #imgFirma {
            width: 100%;
            height: 100%;
        }

        body {
            font-size: 10px;
            font-family: DejaVu Sans, "sans-serif";
            /* line-height: 10.5px; */
            margin: 0.5cm 1cm;
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

        .text-center {
            text-align: center;
        }

        .w-100 {
            width: 100%
        }

        .flex {
            display: flex;
            justify-content: center;
        }
    </style>
</head>

<body>
    <main class="container">
        <table width="100%" class="tabla3">
            <tr>
                <td width="40%">
                    @if ($empresa->Imagen !== null)
                        @if (str_contains($empresa->Imagen, config('variablesGlobales.urlDominioAmazonS3')))
                            <img src="{{ $empresa->Imagen }}" alt="" width="250" height="110">
                        @else
                            <img src="{{ config('variablesGlobales.urlDominioAmazonS3') . $empresa->Imagen }}"
                                alt="" width="250" height="110">
                        @endif
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
                                        <span class="h5">CONTROL DE CALIDAD</span>
                                    </div>
                                    <div style="margin-bottom: 15px;">
                                        <span class="h5">{{ $controlCalidad->Serie }} -
                                            {{ $controlCalidad->Numero }}</span>
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
                            <span class="negrita">Fecha Informe Control Calidad:</span>
                            {{ $controlCalidad->FechaCreacion }} <br>
                            <span class="negrita">Raz. Social:</span> {{ $controlCalidad->RazonSocial }} <br>
                            <span class="negrita">Dirección:</span> {{ substr($controlCalidad->Direccion, 0, 55) }}<br>
                            <span class="negrita">Celular:</span> {{ $controlCalidad->Telefono }} /
                            <span class="negrita">Tipo Atención:</span> {{ $controlCalidad->TipoAtencion }}
                            {{-- <span class="negrita">Asesor Comercial:</span> {{ $controlCalidad->Nombre }} --}}
                        </div>
                    </td>
                    <td class="borderTabla">
                        <div class="margen">
                            <span class="negrita">Placa:</span> {{ $controlCalidad->PlacaVehiculo }} /
                            <span class="negrita">Marca:</span> {{ $controlCalidad->NombreMarca }} /
                            <span class="negrita">Modelo:</span> {{ $controlCalidad->NombreModelo }}/
                            <span class="negrita">Año:</span> {{ $controlCalidad->Anio }} <br>
                            <span class="negrita">Color:</span> {{ $controlCalidad->Color }} / <span
                                class="negrita">Kilometraje:</span> {{ $controlCalidad->Kilometraje }} <br>
                            <span class="negrita">Chasis / VIN:</span> {{ $controlCalidad->ChasisVehiculo }}
                            <br>
                            <span class="negrita">Venc. Soat:</span> {{ $controlCalidad->FechaSoat }} |
                            <span class="negrita">Venc Rev. Tec.:</span> {{ $controlCalidad->FechaRevTecnica }}
                            <br>
                            {{-- <span class="negrita">Fecha:</span> {{ $formatoFecha }} / <span
                                class="negrita">Hora:</span> {{ $formatoHora }} <br> --}}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <table width="100%">
            <tr align="center" style="width:100%">
                <th colspan="4" class="borderTabla fondo"><span class="negrita">ESTADOS</span></th>
            </tr>
            <tr style="width:100%">
                <th width="25%" class="borderTabla"><span class="negrita">SATISFACTORIO</span></th>
                <th width="25%" class="borderTabla"><span class="negrita">ATENCIÓN FUTURA</span></th>
                <th width="25%" class="borderTabla"><span class="negrita">ATENCIÓN INMEDIATA</span></th>
                <th width="25%" class="borderTabla"><span class="negrita">SIN INSPECCION</span></th>
            </tr>
            <tr style="width:100%">
                <th class="borderTabla">
                    <img src="{{ asset('/assets/img/Control-de-calidad/satisfactorio.png') }}" alt="Imagen de Frenos"
                        style="width: 18px; height:18px">
                </th>
                <th class="borderTabla">
                    <img src="{{ asset('/assets/img/Control-de-calidad/futura.png') }}" alt="Imagen de Frenos"
                        style="width: 18px; height:18px">
                </th>
                <th class="borderTabla">
                    <img src="{{ asset('/assets/img/Control-de-calidad/inmediata.png') }}" alt="Imagen de Frenos"
                        style="width: 18px; height:18px">
                </th>
                <th class="borderTabla">
                    <span>X</span>
                </th>
            </tr>
        </table>
        <table width="100%">
            <tr align="center" style="width:100%">
                <th colspan="4" class="borderTabla fondo"><span class="negrita">POSICIONES</span></th>
            </tr>
            <tr class="fondo">
                <th class="borderTabla">FRONTAL DERECHA</th>
                <th class="borderTabla">FRONTAL IZQUIERDA</th>
                <th class="borderTabla">TRASERA DERECHA</th>
                <th class="borderTabla">TRASERA IZQUIERDA</th>
            </tr>
            <tr style="width:100%">
                <th class="borderTabla">
                    <div class="text-center">FD</div>
                </th>
                <th class="borderTabla">
                    <div class="text-center">FI</div>
                </th>
                <th class="borderTabla">
                    <div class="text-center">TD</div>
                </th>
                <th class="borderTabla">
                    <div class="text-center">TI</div>
                </th>
            </tr>
        </table>

        {{-- TABLA DE EJEMPLOS --}}
        <table width="100%" class="tabla2">
            <tr>

                <td style="vertical-align:top;width:360px">
                    {{-- Bajo del vehiculo --}}
                    <table width="100%" class="tabla2" style="margin-right: 10px">
                        <thead>
                            <tr>
                                <th colspan="5" class="borderTabla fondo" align="center">BAJO DEL VEHÍCULO</th>
                            </tr>
                            <tr class="fondo borderTabla">
                                <th class="borderTabla">Descripción</th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/satisfactorio.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/futura.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/inmediata.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <span>X</span>
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($controlCalidad->datosDebajoVehiculo as $item)
                                <tr>
                                    <td class="borderTabla">{{ $item->Descripcion }}</td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->EstadoParte == 'Satisfactorio')
                                            <span>✔</span>
                                        @endif
                                    </td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->EstadoParte == 'Atencion Futura')
                                            <span>✔</span>
                                        @endif
                                    </td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->EstadoParte == 'Atencion Inmediata')
                                            <span>✔</span>
                                        @endif
                                    </td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->EstadoParte == 'Sin Inspeccion')
                                            <span>✔</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- Dentro del vehiculo --}}
                    <table width="100%" class="tabla2" style="margin-right: 10px">
                        <thead>
                            <tr>
                                <th colspan="5" class="borderTabla fondo" align="center">DENTRO DEL VEHÍCULO</th>
                            </tr>
                            <tr class="fondo borderTabla">
                                <th class="borderTabla">Descripción</th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/satisfactorio.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/futura.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/inmediata.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <span>X</span>
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($controlCalidad->datosDentroVehiculo as $item)
                                <tr>
                                    <td class="borderTabla">{{ $item->Descripcion }}</td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->EstadoParte == 'Satisfactorio')
                                            <span>✔</span>
                                        @endif
                                    </td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->EstadoParte == 'Atencion Futura')
                                            <span>✔</span>
                                        @endif
                                    </td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->EstadoParte == 'Atencion Inmediata')
                                            <span>✔</span>
                                        @endif
                                    </td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->EstadoParte == 'Sin Inspeccion')
                                            <span>✔</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- Niveles de liquido --}}
                    <table width="100%" class="tabla2" style="margin-right: 10px">
                        <thead>
                            <tr>
                                <th colspan="5" class="borderTabla fondo" align="center">NIVELES DE LIQUIDO</th>
                            </tr>
                            <tr class="fondo">
                                <th class="borderTabla">Descripción</th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/satisfactorio.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/futura.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/inmediata.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <span>X</span>
                                </th>
                        </thead>
                        <tbody>
                            @foreach ($controlCalidad->datosFluidos as $item)
                                <tr>
                                    <td class="borderTabla">{{ $item->Descripcion }}</td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->EstadoParte == 'Satisfactorio')
                                            <span>✔</span>
                                        @endif
                                    </td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->EstadoParte == 'Atencion Futura')
                                            <span>✔</span>
                                        @endif
                                    </td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->EstadoParte == 'Atencion Inmediata')
                                            <span>✔</span>
                                        @endif
                                    </td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->EstadoParte == 'Sin Inspeccion')
                                            <span>✔</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
                <td style="vertical-align:top;width:360px">
                    {{-- Bajo del capo --}}
                    <table width="100%" class="tabla2">
                        <thead>
                            <tr>
                                <th colspan="5" class="borderTabla fondo" align="center">BAJO DEL CAPO</th>
                            </tr>
                            <tr class="fondo">
                                <th class="borderTabla">Descripción</th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/satisfactorio.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/futura.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/inmediata.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <span>X</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($controlCalidad->datosBajoCapo as $item)
                                <tr>
                                    <td class="borderTabla">{{ $item->Descripcion }}</td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->EstadoParte == 'Satisfactorio')
                                            <span>✔</span>
                                        @endif
                                    </td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->EstadoParte == 'Atencion Futura')
                                            <span>✔</span>
                                        @endif
                                    </td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->EstadoParte == 'Atencion Inmediata')
                                            <span>✔</span>
                                        @endif
                                    </td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->EstadoParte == 'Sin Inspeccion')
                                            <span>✔</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- Luces --}}
                    <table width="100%" class="tabla2">
                        <thead>
                            <tr>
                                <th colspan="6" class="borderTabla fondo" align="center">LUCES</th>
                            </tr>
                            <tr class="fondo">
                                <th class="borderTabla">Posición</th>
                                <th class="borderTabla">Descripción</th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/satisfactorio.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/futura.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/inmediata.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <span>X</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($controlCalidad->datosLuces as $item)
                                @if ($item->Posicion == 'FrontalDerecha')
                                    <tr align="center">
                                        @if ($item->Descripcion == 'Intermitente/Direccionales')
                                            <th class="borderTabla" rowspan="2" class="align-middle">FD</th>
                                        @endif
                                        <td class="borderTabla">{{ $item->Descripcion }}</td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Satisfactorio')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Futura')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Inmediata')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Sin Inspeccion')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'FrontalIzquierda')
                                    <tr align="center">
                                        @if ($item->Descripcion == 'Intermitente/Direccionales')
                                            <th class="borderTabla" class="borderTabla" rowspan="2"
                                                class="align-middle">
                                                FI</th>
                                        @endif
                                        <td class="borderTabla">{{ $item->Descripcion }}</td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Satisfactorio')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Futura')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Inmediata')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Sin Inspeccion')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'TraseraDerecha')
                                    <tr align="center">
                                        @if ($item->Descripcion == 'Retroceso')
                                            <th class="borderTabla" class="borderTabla" rowspan="3"
                                                class="align-middle">
                                                TD</th>
                                        @endif
                                        <td class="borderTabla">{{ $item->Descripcion }}</td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Satisfactorio')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Futura')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Inmediata')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Sin Inspeccion')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'TraseraIzquierda')
                                    <tr align="center">
                                        @if ($item->Descripcion == 'Retroceso')
                                            <th class="borderTabla" rowspan="3" class="align-middle">TI</th>
                                        @endif
                                        <td class="borderTabla">{{ $item->Descripcion }}</td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Satisfactorio')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Futura')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Inmediata')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Sin Inspeccion')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    {{-- LimpiaParabrisas --}}
                    <table width="100%" class="tabla2">
                        <thead>
                            <tr>
                                <th colspan="6" class="borderTabla fondo" align="center">LIMPIAPARABRISAS</th>
                            </tr>
                            <tr class="fondo">
                                <th class="borderTabla">Posición</th>
                                <th class="borderTabla">Descripción</th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/satisfactorio.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/futura.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/inmediata.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <span>X</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($controlCalidad->datosLimpiaParabrisas as $item)
                                @if ($item->Posicion == 'FrontalDerecha')
                                    <tr align="center">
                                        @if ($item->Descripcion == 'Escobilla/Goma')
                                            <th class="borderTabla" rowspan="3">FD</th>
                                        @endif
                                        <td class="borderTabla">{{ $item->Descripcion }}</td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Satisfactorio')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Futura')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Inmediata')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Sin Inspeccion')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'FrontalIzquierda')
                                    <tr align="center">
                                        @if ($item->Descripcion == 'Escobilla/Goma')
                                            <th class="borderTabla" rowspan="3">FI</th>
                                        @endif
                                        <td class="borderTabla">{{ $item->Descripcion }}</td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Satisfactorio')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Futura')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Inmediata')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Sin Inspeccion')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'Trasera')
                                    <tr align="center">
                                        @if ($item->Descripcion == 'Escobilla/Goma')
                                            <th class="borderTabla" rowspan="3">T</th>
                                        @endif
                                        <td class="borderTabla">{{ $item->Descripcion }}</td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Satisfactorio')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Futura')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Inmediata')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Sin Inspeccion')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <div style="page-break-after:always;"></div>
        <table width="100%" class="tabla2">
            <tr>
                <td style="vertical-align:top;width:360px">
                    {{-- Llantas --}}
                    <table width="100%" class="tabla2" style="margin-right: 10px">
                        <thead>
                            <tr>
                                <th colspan="6" class="borderTabla fondo" align="center">LLANTAS</th>
                            </tr>
                            <tr class="fondo">
                                <th class="borderTabla">Posición</th>
                                <th class="borderTabla">Descripción</th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/satisfactorio.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/futura.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/inmediata.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <span>X</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($controlCalidad->datosNeumaticos as $item)
                                @if ($item->Posicion == 'FrontalDerecha')
                                    <tr align="center">
                                        @if ($item->Descripcion == 'Alineación')
                                            <th class="borderTabla" rowspan="2" class="align-middle">FD</th>
                                        @endif
                                        <td class="borderTabla">{{ $item->Descripcion }}</td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Satisfactorio')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Futura')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Inmediata')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Sin Inspeccion')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'FrontalIzquierda')
                                    <tr align="center">
                                        @if ($item->Descripcion == 'Alineación')
                                            <th class="borderTabla" rowspan="2" class="align-middle">FI</th>
                                        @endif
                                        <td class="borderTabla">{{ $item->Descripcion }}</td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Satisfactorio')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Futura')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Inmediata')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Sin Inspeccion')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'TraseraDerecha')
                                    <tr align="center">
                                        @if ($item->Descripcion == 'Alineación')
                                            <th class="borderTabla" rowspan="2" class="align-middle">TD</th>
                                        @endif
                                        <td class="borderTabla">{{ $item->Descripcion }}</td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Satisfactorio')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Futura')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Inmediata')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Sin Inspeccion')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'TraseraIzquierda')
                                    <tr align="center">
                                        @if ($item->Descripcion == 'Alineación')
                                            <th class="borderTabla" rowspan="2" class="align-middle">TI</th>
                                        @endif
                                        <td class="borderTabla">{{ $item->Descripcion }}</td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Satisfactorio')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Futura')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Inmediata')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Sin Inspeccion')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                            <tr class="borderTabla" width="100%">
                                <td colspan="6">
                                    <table width="100%">
                                        <tr align="center" style="width:100%">
                                            <th colspan="4" class="borderTabla"><span
                                                    class="negrita">PRESIÓN</span></th>
                                        </tr>
                                        <tr>
                                            <th class="borderTabla">FD</th>
                                            <th class="borderTabla">FI</th>
                                            <th class="borderTabla">TD</th>
                                            <th class="borderTabla">TI</th>
                                        </tr>

                                        <tr style="width:100%">
                                            <td class="borderTabla" align="center">
                                                @if ($controlCalidad->datosPresionNeumatico[0]->Presion != null)
                                                    <span>{{ $controlCalidad->datosPresionNeumatico[0]->Presion }}</span>
                                                @else
                                                    <span>-</span>
                                                @endif
                                            </td>
                                            <td class="borderTabla" align="center">
                                                @if ($controlCalidad->datosPresionNeumatico[1]->Presion != null)
                                                    <span>{{ $controlCalidad->datosPresionNeumatico[1]->Presion }}</span>
                                                @else
                                                    <span>-</span>
                                                @endif
                                            </td>
                                            <td class="borderTabla" align="center">
                                                @if ($controlCalidad->datosPresionNeumatico[2]->Presion != null)
                                                    <span>{{ $controlCalidad->datosPresionNeumatico[2]->Presion }}</span>
                                                @else
                                                    <span>-</span>
                                                @endif
                                            </td>
                                            <td class="borderTabla" align="center">
                                                @if ($controlCalidad->datosPresionNeumatico[3]->Presion != null)
                                                    <span>{{ $controlCalidad->datosPresionNeumatico[3]->Presion }}</span>
                                                @else
                                                    <span>-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </td>
                <td style="vertical-align:top;width:360px">
                    {{-- Frenos --}}
                    <table width="100%" class="tabla2">
                        <thead>
                            <tr>
                                <th colspan="7" class="borderTabla fondo" align="center">FRENOS</th>
                            </tr>
                            <tr class="fondo">
                                <th class="borderTabla">Posición</th>
                                <th class="borderTabla">Descripción</th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/satisfactorio.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/futura.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/inmediata.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <span>X</span>
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <span>Medida</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($controlCalidad->datosFrenos as $item)
                                @if ($item->Posicion == 'FrontalDerecha')
                                    <tr align="center">
                                        @if ($item->Descripcion == 'Discos')
                                            <th class="borderTabla" rowspan="2" class="align-middle">FD</th>
                                        @endif
                                        <td class="borderTabla">{{ $item->Descripcion }}</td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Satisfactorio')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Futura')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Inmediata')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Sin Inspeccion')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            <span>{{ $item->Medida }}</span>

                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'FrontalIzquierda')
                                    <tr align="center">
                                        @if ($item->Descripcion == 'Discos')
                                            <th class="borderTabla" rowspan="2" class="align-middle">FI</th>
                                        @endif
                                        <td class="borderTabla">{{ $item->Descripcion }}</td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Satisfactorio')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Futura')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Inmediata')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Sin Inspeccion')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            <span>{{ $item->Medida }}</span>

                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'TraseraDerecha')
                                    <tr align="center">
                                        @if ($item->Descripcion == 'Discos')
                                            <th class="borderTabla" rowspan="4" class="align-middle">TD</th>
                                        @endif
                                        <td class="borderTabla">{{ $item->Descripcion }}</td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Satisfactorio')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Futura')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Inmediata')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Sin Inspeccion')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            <span>{{ $item->Medida }}</span>
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'TraseraIzquierda')
                                    <tr align="center">
                                        @if ($item->Descripcion == 'Discos')
                                            <th class="borderTabla" rowspan="4" class="align-middle">TI</th>
                                        @endif
                                        <td class="borderTabla">{{ $item->Descripcion }}</td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Satisfactorio')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Futura')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Atencion Inmediata')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            @if ($item->EstadoParte == 'Sin Inspeccion')
                                                <span>✔</span>
                                            @endif
                                        </td>
                                        <td class="borderTabla" align="center">
                                            <span>{{ $item->Medida }}</span>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    {{-- Filtros --}}
                    <table width="100%" class="tabla2">
                        <thead>
                            <tr>
                                <th colspan="5" class="borderTabla fondo" align="center">FILTROS</th>
                            </tr>
                            <tr class="fondo">
                                <th class="borderTabla">Descripción</th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/satisfactorio.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/futura.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <img src="{{ asset('/assets/img/Control-de-calidad/inmediata.png') }}"
                                        alt="Imagen de Frenos" style="width: 18px; height:18px">
                                </th>
                                <th style="width:9%" class="borderTabla">
                                    <span>X</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($controlCalidad->datosFiltros as $item)
                                <tr>
                                    <td class="borderTabla">{{ $item->Descripcion }}</td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->EstadoParte == 'Satisfactorio')
                                            <span>✔</span>
                                        @endif
                                    </td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->EstadoParte == 'Atencion Futura')
                                            <span>✔</span>
                                        @endif
                                    </td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->EstadoParte == 'Atencion Inmediata')
                                            <span>✔</span>
                                        @endif
                                    </td>
                                    <td class="borderTabla" align="center">
                                        @if ($item->EstadoParte == 'Sin Inspeccion')
                                            <span>✔</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <br><br>
        {{-- Recomendaciones --}}
        @if ($controlCalidad->Recomendacion != null)
            <table width="100%" class="tabla2">
                <thead>
                    <tr class="borderTabla fondo" align="center">
                        <th class="borderTabla">Recomendaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="borderTabla">
                            <span>✔ {{ $controlCalidad->Recomendacion }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif
        {{-- Diagnostico --}}
        @if ($controlCalidad->Diagnostico != null)
            <table width="100%" class="tabla2">
                <thead>
                    <tr class="borderTabla fondo" align="center">
                        <th class="borderTabla">Diagnóstico</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="borderTabla">
                            <span>✔ {{ $controlCalidad->Diagnostico }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif
        {{-- Fin --}}
    </main>
    <footer>
        <table width="100%">
            <tr>
                <td style="width: 32%">
                    <section style="text-align: center">
                        <article class="contenedorImagenFirma">
                            @if (
                                $controlCalidad->OpcionFirmaAsesor == 'Activado' &&
                                    !empty($controlCalidad->FirmaAsesorComercial) &&
                                    !str_contains($controlCalidad->FirmaAsesorComercial, config('variablesGlobales.urlDominioAmazonS3Anterior')))
                                <img id="imgFirma"
                                    src="{{ str_contains($controlCalidad->FirmaAsesorComercial, config('variablesGlobales.urlDominioAmazonS3'))
                                        ? $controlCalidad->FirmaAsesorComercial
                                        : config('variablesGlobales.urlDominioAmazonS3') . $controlCalidad->FirmaAsesorComercial }}">
                            @endif
                        </article>
                        <article style="height:60px">
                            ---------------------------------------------------------------<br>
                            Firma Asesor Comercial<br>
                            {{ $controlCalidad->NombreAsesorComercial }}
                        </article>
                    </section>
                </td>
                <td style="width: 32%">
                    <section style="text-align: center">
                        <article class="contenedorImagenFirma">
                            @if (
                                $controlCalidad->OpcionFirmaMecanico == 'Activado' &&
                                    !empty($controlCalidad->FirmaMecanico) &&
                                    !str_contains($controlCalidad->FirmaMecanico, config('variablesGlobales.urlDominioAmazonS3Anterior')))
                                <img id="imgFirma"
                                    src="{{ str_contains($controlCalidad->FirmaMecanico, config('variablesGlobales.urlDominioAmazonS3'))
                                        ? $controlCalidad->FirmaMecanico
                                        : config('variablesGlobales.urlDominioAmazonS3') . $controlCalidad->FirmaMecanico }}">
                            @endif
                        </article>
                        <article style="height:60px">
                            ---------------------------------------------------------------<br>
                            Firma Mecánico <br>
                            {{ $controlCalidad->NombreOperario }}
                        </article>
                    </section>
                </td>
                <td style="width: 32%">
                    <section style="text-align: center">
                        <article class="contenedorImagenFirma">
                            @if (
                                $controlCalidad->ImagenFirmaCliente !== null &&
                                    !str_contains($controlCalidad->ImagenFirmaCliente, config('variablesGlobales.urlDominioAmazonS3Anterior')))
                                <img id="imgFirma"
                                    src="{{ str_contains($controlCalidad->ImagenFirmaCliente, config('variablesGlobales.urlDominioAmazonS3'))
                                        ? $controlCalidad->ImagenFirmaCliente
                                        : config('variablesGlobales.urlDominioAmazonS3') . $controlCalidad->ImagenFirmaCliente }}">
                            @endif
                        </article>
                        <article style="height:60px">
                            ---------------------------------------------------------------<br>
                            Firma Cliente <br>
                            Declaro haber recibido la asesoria de la atención y acepto las implicancias al no realizar
                            los cambios sugeridos por la empresa de acuerdo a lo manisfestado
                        </article>
                    </section>
                </td>
            </tr>
        </table>
    </footer>
</body>

</html>
