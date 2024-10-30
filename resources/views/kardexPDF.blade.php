<!DOCTYPE HTML>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <title></title>
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
</head>

<body>
    <div class="container">
        <div class="widget-list">
            <div class="row">
                <div class="col-md-12 widget-holder">
                    <div class="widget-bg">
                        <!-- /.widget-heading -->
                        <div class="widget-body clearfix">

                            <table width="100%" class="tabla1">
                                <tr>
                                    <td width="70%">
                                        @if ($empresa->Imagen !== null && str_contains($empresa->Imagen, config('variablesGlobales.urlDominioAmazonS3')))
                                            <img src="{{ $empresa->Imagen }}" alt="" width="190"
                                                height="115">
                                        @elseif($empresa->Imagen == null)
                                            <img src="" alt="" width="190" height="115">
                                        @else
                                            <img src="{{ config('variablesGlobales.urlDominioAmazonS3') . $empresa->Imagen }}"
                                                alt="" width="190" height="115">
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="negrita">{{ $empresa->Nombre }}</span></td>
                                </tr>
                                <tr>
                                    <td>{{ $empresa->Direccion }} - {{ $empresa->Ciudad }} </td>
                                </tr>
                                <tr>
                                    <td>TELÉFONO: {{ $empresa->Telefono }}</td>
                                </tr>
                            </table>

                            <br />

                            <!--<p>Listado de ventas</p>-->
                            <table width="100%" class="tabla3">
                                <thead>
                                    <tr class="bg-primary">
                                        <th class="borderTabla" align="center">Fecha</th>
                                        <th class="borderTabla" align="center">Tipo</th>
                                        <th class="borderTabla" align="center">Usuario</th>
                                        <th class="borderTabla" align="center">Documento</th>
                                        <th class="borderTabla" align="center">Costo/Precio</th>
                                        <th class="borderTabla" align="center">Entrada</th>
                                        <th class="borderTabla" align="center">Salida</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalEntrada = 0;
                                        $totalSalida = 0;
                                    @endphp
                                    @foreach ($reporteKardex as $kardex)
                                        @if ($kardex->EstadoStock == 'E')
                                            @php $totalEntrada = $totalEntrada + $kardex->Cantidad; @endphp
                                        @else
                                            @php $totalSalida = $totalSalida + $kardex->Cantidad; @endphp
                                        @endif
                                        <tr>
                                            <td class="borderTabla" align="center">{{ $kardex->fecha_movimiento }}</td>
                                            <td class="borderTabla" align="center">{{ $kardex->Descripcion }}</td>
                                            <td class="borderTabla" align="center">{{ $kardex->Nombre }}</td>
                                            <td class="borderTabla" align="center">{{ $kardex->documento_movimiento }}
                                            </td>
                                            <td class="borderTabla" align="center">
                                                {{ number_format($kardex->costo, 2, '.', ',') }}</td>
                                            @if ($kardex->EstadoStock == 'E')
                                                <td class="borderTabla" align="center">
                                                    {{ number_format($kardex->Cantidad, 2, '.', ',') }}</td>
                                                <td class="borderTabla" align="center">
                                                    {{ number_format(0, 2, '.', ',') }}</td>
                                            @else
                                                <td class="borderTabla" align="center">
                                                    {{ number_format(0, 2, '.', ',') }}</td>
                                                <td class="borderTabla" align="center">
                                                    {{ number_format($kardex->Cantidad, 2, '.', ',') }}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="5"><span class="">Totales: </span></td>
                                        <td align="center">{{ number_format($totalEntrada, 2, '.', ',') }}</td>
                                        <td align="center">{{ number_format($totalSalida, 2, '.', ',') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.widget-body -->
                    </div>
                    <!-- /.widget-bg -->
                </div>
                <!-- /.widget-holder -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.widget-list -->
    </div>
    <!-- /.container -->
</body>

</html>
