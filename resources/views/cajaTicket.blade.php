<!DOCTYPE html>
<html>
<style type="text/css">
    @page {
        margin-top: 1em;
        margin-left: 0.5em;
        margin-right: 0.5em;
    }

    body {
        font-size: 7px;
        font-family: "sans-serif";
    }

    table {
        border-collapse: collapse;
    }

    td {
        font-size: 7px;
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

    .h6 {
        font-size: 11px;
        font-weight: bold;
    }

    .h7 {
        font-size: 9px;
        font-weight: bold;
    }

    .h8 {
        font-size: 7px;
        font-weight: bold;
    }

    .h9 {
        font-size: 5px;
        font-weight: bold;
    }

    .h10 {
        font-size: 3.6px;
        font-weight: bold;
    }

    .tabla1 {
        margin-bottom: 0px;
    }

    .tabla2 td {
        font-size: 5px;
    }

    .tabla4,
    .tabla3 {
        margin-top: 0px;
    }

    .tabla4 td {
        font-size: 5px;
    }

    .tabla3 td {
        font-size: 3.6px;
    }

    .tabla3 th {
        font-size: 5px;
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
        border: 2px solid #000;
    }

    .borderTabla {
        border: 0.5px solid #000;
    }

    .borderTop {
        border-top: 0.5px solid #000;
    }

    .borderBottom {
        border-bottom: 0.5px solid #000;
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

    .text-center {
        align-items: center;
    }
</style>

<body>
    <div class="container">
        <table width="100%">
            <tr>
                <td align="center" class="h7"><span>DETALLE DE CAJA</span></td>
            </tr>
            <tr>
                <td align="center" class="h9"><span>Fecha. Cierre:</span>{{ $ultimoSesion }}</td>
            </tr>
            <tr>
                <td align="center" class="h9"><span>Turno:</span> {{ $turno }}</td>
            </tr>
        </table>
        <table width="100%" class="tabla3" style=" margin-top: 8px">
            <thead class="borderBottom">
                <tr align="left">
                    <th><span class="h8" colspan="1">DETALLES</span></th>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <th class="text-center"><span class="h8" colspan="2">TOTALES</span></th>
                    @else
                        <th><span class="h8">TOTAL (S/)</span></th>
                    @endif
                </tr>
                @if ($subniveles->contains('IdSubNivel', 46))
                    <tr align="left">
                        <th></th>
                        <th><span class="h8">(S/)</span></th>
                        <th><span class="h8">($)</span></th>
                    </tr>
                @endif
            </thead>
            <tbody>
                <tr>
                    <td><span class="h9">EFECTIVO INICIAL</span></td>
                    <td><span class="h9">{{ number_format($inicialSoles, 2, '.', ',') }}</span></td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td><span class="h9">{{ number_format($inicialDolares, 2, '.', ',') }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h9">CONTADO</span></td>
                    <td><span class="h9">{{ $totalVentasContadoSoles }}</span></td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td><span class="h9">{{ $totalVentasContadoDolares }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h9 ml">- EFECTIVO</span></td>
                    <td>{{ $ventasContadoEfectivoSoles }}</td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td>{{ $ventasContadoEfectivoDolares }}</td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h9 ml">- TARJETA</span></td>
                    <td>{{ $ventasContadoTarjetaSoles }}</td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td>{{ $ventasContadoTarjetaDolares }}</td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h9 ml">- CUENTA BANCARIA</span></td>
                    <td>{{ $ventasContadoCuentaBancariaSoles }}</td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td>{{ $ventasContadoCuentaBancariaDolares }}</td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h9">COBRANZAS</span></td>
                    <td><span class="h9">{{ $totalCobranzasSoles }}</span></td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td><span class="h9">{{ $totalCobranzasDolares }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h9 ml">- EFECTIVO</span></td>
                    <td>{{ $cobranzasEfectivoSoles }}</td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td>{{ $cobranzasEfectivoDolares }}</td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h9 ml">- TARJETA</span></td>
                    <td>{{ $cobranzasTarjetaSoles }}</td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td>{{ $cobranzasTarjetaDolares }}</td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h9 ml">- CUENTA BANCARIA</span></td>
                    <td>{{ $cobranzasCuentaBancariaSoles }}</td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td>{{ $cobranzasCuentaBancariaDolares }}</td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h9">AMORTIZACIONES</span></td>
                    <td><span class="h9">{{ number_format($totalAmortizacionSoles, 2, '.', ',') }}</span></td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td><span class="h9">{{ number_format($totalAmortizacionDolares, 2, '.', ',') }}</span>
                        </td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h9 ml">- EFECTIVO</span></td>
                    <td>{{ number_format($amortizacionEfectivoSoles, 2, '.', ',') }}</td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td>{{ number_format($amortizacionEfectivoDolares, 2, '.', ',') }}</td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h9 ml">- TARJETA</span></td>
                    <td>{{ number_format($amortizacionTarjetaSoles, 2, '.', ',') }}</td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td>{{ number_format($amortizacionTarjetaDolares, 2, '.', ',') }}</td>
                    @endif

                </tr>
                <tr>
                    <td><span class="h9 ml">- CUENTA BANCARIA</span></td>
                    <td>{{ number_format($amortizacionCuentaBancariaSoles, 2, '.', ',') }}</td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td>{{ number_format($amortizacionCuentaBancariaDolares, 2, '.', ',') }}</td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h9">INGRESOS</span></td>
                    <td><span class="h9">{{ $montoIngresosSoles }}</span></td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td><span class="h9">{{ $montoIngresosDolares }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h9">EGRESOS</span></td>
                    <td><span class="h9">{{ $montoEgresosSoles }}</span></td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td><span class="h9">{{ $montoEgresosDolares }}</span></td>
                    @endif
                </tr>
            </tbody>
            <tfoot class="borderTop">
                <tr align="left">
                    <th><span class="h9">CAJA TOTAL EFECTIVO</span></th>
                    <th><span class="h9">{{ $cajaTotalSoles }}</span></th>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <th><span class="h9">{{ $cajaTotalDolares }}</span></th>
                    @endif
                </tr>
            </tfoot>
        </table>
        <table width="100%" class="tabla1" style=" margin-top: 10px">
            <tr>
                <td align="center">==============================</td>
            </tr>
        </table>
        <table width="100%" style=" margin-top: 10px">
            <tr>
                <td align="center"><span class="h7">DETALLE DE VENTAS</span></td>
            </tr>
        </table>
        <table width="100%" class="tabla3">
            <thead class="borderBottom">
                <tr>
                    <th width="55%" class="borderTabla" align="center"><span class="h10">PRODUCTOS</span></th>
                    <th width="15%" class="borderTabla" align="center"><span class="h10">CANTIDAD</span></th>
                    <th width="15%" class="borderTabla" align="center"><span class="h10">PRECIO</span></th>
                    <th width="15%" class="borderTabla" align="center"><span class="h10">IMPORTE FINAL</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ventasAperturaCierreCaja as $ventas)
                    <tr>
                        <td class="borderTabla" align="left">
                            @foreach ($ventas->Productos as $producto)
                                {{ $producto->Descripcion }}&nbsp; - &nbsp;{{ $producto->Detalle }}<br>
                            @endforeach
                        </td>
                        <td class="borderTabla" align="center">
                            @foreach ($ventas->Productos as $producto)
                                {{ $producto->Cantidad }} <br>
                            @endforeach
                        </td>
                        <td class="borderTabla" align="center">
                            @foreach ($ventas->Productos as $producto)
                                {{ $producto->Precio }} <br>
                            @endforeach
                        </td>
                        <td class="borderTabla" align="center">{{ $ventas->Total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</body>

</html>
