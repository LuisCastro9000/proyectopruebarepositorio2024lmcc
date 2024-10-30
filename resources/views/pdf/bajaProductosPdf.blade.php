<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Control Calidad</title>
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

        .contenedorImagenFirma {
            margin: auto;
            width: 100px;
            height: 40px;
        }

        #imgFirma {
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body>
    <main class="container">
        <table width="100%" class="tabla1">
            <tr>
                <td width="30%">
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
                    <div style="margin-top: 0px;text-align: center">
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
                                    <div style="margin-bottom: 15px;">
                                        <span class="h4">VALE DE BAJA DE PRODUCTOS</span>
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
                <td>{{ $empresa->DirPrincipal }} - {{ $empresa->Distrito }}</td>
            </tr>
            <tr>
                <td>TELÉFONO: {{ $empresa->Telefono }}</td>
            </tr>
        </table>
        <br>
        @php
            $a = 1;
        @endphp
        <table width="100%" class="tabla3">
            <thead>
                <tr>
                    <th class="borderTabla fondo" align="center">Items</th>
                    <th class="borderTabla fondo" align="center">FECHA BAJA</th>
                    <th class="borderTabla fondo" align="center">CODIGO BARRA</th>
                    <th class="borderTabla fondo" align="center">PRODUCTO</th>
                    <th class="borderTabla fondo" align="center">CANTIDAD</th>
                    <th class="borderTabla fondo" align="center">NUEVO STOCK</th>
                    <th class="borderTabla fondo" align="center">MOTIVO BAJA</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detalleBaja as $baja)
                    <tr>
                        <td class="borderTabla" align="center">{{ $a++ }}</td>
                        <td class="borderTabla" align="center">{{ $baja->FechaBaja }}</td>
                        <td class="borderTabla" align="center">{{ $baja->CodigoBarra }}</td>
                        <td class="borderTabla" align="center">{{ $baja->NombreArticulo }}</td>
                        <td class="borderTabla" align="center">{{ $baja->CantidadBajas }}
                            {{ $baja->NombreUnidadMedida }}</td>
                        <td class="borderTabla" align="center">{{ $baja->NuevoStock }}</td>
                        <td class="borderTabla" align="center">
                            @if ($baja->IdMotivo == 1)
                                Consumo Interno
                            @elseif($baja->IdMotivo == 2)
                                Producto Vencido
                            @elseif($baja->IdMotivo == 3)
                                Perdida y/o Extravio
                            @else
                                {{ $baja->DescripcionMotivo }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
    <footer class="abajo">
        <table width="100%">
            <tr>
                <td style="width: 32%">
                </td>
                <td style="width: 32%">
                </td>
                <td style="width: 32%">
                    <section style="text-align: center">
                        <article class="contenedorImagenFirma">
                            @if ($firmaUsuario !== null && !str_contains($firmaUsuario, config('variablesGlobales.urlDominioAmazonS3Anterior')))
                                <img id="imgFirma" width="100" height="60px"
                                    src="{{ str_contains($firmaUsuario, config('variablesGlobales.urlDominioAmazonS3'))
                                        ? $firmaUsuario
                                        : config('variablesGlobales.urlDominioAmazonS3') . $firmaUsuario }}">
                            @endif
                        </article>
                        <article style="height:60px">
                            ---------------------------------------------------------------<br>
                            Firma Responsable <br>
                        </article>
                    </section>
                </td>
            </tr>
        </table>
    </footer>
</body>

</html>
