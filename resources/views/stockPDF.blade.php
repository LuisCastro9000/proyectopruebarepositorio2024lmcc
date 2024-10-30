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
        <div class="col-md-12 widget-holder">
            <div class="widget-bg">

                <div class="widget-body clearfix">
                    <!--<p>Listado de ventas</p>-->

                    <table width="100%" class="tabla1">
                        <tr>
                            <td width="70%">
                                @if ($empresa->Imagen !== null && str_contains($empresa->Imagen, config('variablesGlobales.urlDominioAmazonS3')))
                                    <img src="{{ $empresa->Imagen }}" alt="" width="190" height="115">
                                @elseif($empresa->Imagen == null)
                                    <img src="" alt="" width="190" height="115">
                                @else
                                    <img src="{{ config('variablesGlobales.urlDominioAmazonS3') . $empresa->Imagen }}"
                                        alt=""width="190" height="115">
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
                    <label>Sotck Total de Productos : </label>
                    <span class="negrita">{{ $totalStock }}</span>
                    <br />

                    <table width="100%" class="tabla3">
                        <thead>
                            <tr class="bg-primary">
                                <th class="borderTabla" align="center">Descripción</th>
                                <th class="borderTabla" align="center">Cód. Barras</th>
                                <th class="borderTabla" align="center">UM</th>
                                <th class="borderTabla" align="center">Stock</th>
                                <th class="borderTabla" align="center">Costo</th>
                                <th class="borderTabla" align="center">Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reporteStock as $stock)
                                <tr>
                                    <td class="borderTabla" align="center">{{ $stock->Descripcion }}</td>
                                    <td class="borderTabla" align="center">{{ $stock->Codigo }}</td>
                                    <td class="borderTabla" align="center">{{ $stock->Nombre }}</td>
                                    <td class="borderTabla" align="center">{{ $stock->Stock }}</td>
                                    <td class="borderTabla" align="center">{{ $stock->Costo }}</td>
                                    <td class="borderTabla" align="center">{{ $stock->Precio }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.widget-body -->
            </div>
            <!-- /.widget-bg -->
        </div>
        <!-- /.widget-holder -->



    </div>
    <!-- /.container -->



</body>

</html>
