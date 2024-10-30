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
        padding: 5px;
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
        margin-left: 20px;
        margin-right: 20px;
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
        border-top: 1px solid #adad85;
    }

    .borderBottom {
        border-bottom: 1px solid #adad85;
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

    .ml {
        margin-left: 8px;
    }

    .cursiva {
        font-style: oblique;
    }
</style>

<body>
    <div class="container">
        <table width="100%">
            <tr>
                <td align="center"><span class="h2">DETALLE DE CAJA</span></td>

            </tr>
            <tr>
                <td align="center"><span class="negrita">Fecha Cierre:</span> {{ $ultimoSesion }}</td>
            </tr>
            <tr>
                <td align="center"><span class="negrita">Turno:</span> {{ $turno }}</td>
            </tr>
        </table>
        <table width="100%" class="tabla3">
            <thead class="borderBottom">
                <tr align="left">
                    <th><span class="h4">DETALLES</span></th>
                    <th align="center"><span class="h4">SOLES (S/)</span></th>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <th align="center"><span class="h4">DÓLARES ($)</span></th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="h5">EFECTIVO INICIAL</span></td>
                    <td align="center"><span class="h5">{{ number_format($inicialSoles, 2, '.', ',') }}</span></td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td align="center"><span class="h5">{{ number_format($inicialDolares, 2, '.', ',') }}</span>
                        </td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h5">CONTADO</span></td>
                    <td align="center"><span class="h5">{{ $totalVentasContadoSoles }}</span></td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td align="center"><span class="h5">{{ $totalVentasContadoDolares }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h6 ml">EFECTIVO</span></td>
                    <td align="center">{{ $ventasContadoEfectivoSoles }}</td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td align="center">{{ $ventasContadoEfectivoDolares }}</td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h6 ml">TARJETA</span></td>
                    <td align="center">{{ $ventasContadoTarjetaSoles }}</td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td align="center">{{ $ventasContadoTarjetaDolares }}</td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h6 ml">CUENTA BANCARIA</span></td>
                    <td align="center">{{ $ventasContadoCuentaBancariaSoles }}</td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td align="center">
                            {{ $ventasContadoCuentaBancariaDolares }}</td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h5">COBRANZAS</span></td>
                    <td align="center"><span class="h5">{{ $totalCobranzasSoles }}</span></td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td align="center"><span class="h5">{{ $totalCobranzasDolares }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h6 ml">EFECTIVO</span></td>
                    <td align="center">{{ $cobranzasEfectivoSoles }}</td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td align="center">{{ $cobranzasEfectivoDolares }}</td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h6 ml">TARJETA</span></td>
                    <td align="center">{{ $cobranzasTarjetaSoles }}</td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td align="center">{{ $cobranzasTarjetaDolares }}</td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h6 ml">CUENTA BANCARIA</span></td>
                    <td align="center">{{ $cobranzasCuentaBancariaSoles }}</td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td align="center">{{ $cobranzasCuentaBancariaDolares }}</td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h5">AMORTIZACIONES</span></td>
                    <td align="center"><span
                            class="h5">{{ number_format($totalAmortizacionSoles, 2, '.', ',') }}</span></td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td align="center"><span
                                class="h5">{{ number_format($totalAmortizacionDolares, 2, '.', ',') }}</span>
                        </td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h6 ml">EFECTIVO</span></td>
                    <td align="center">{{ number_format($amortizacionEfectivoSoles, 2, '.', ',') }}</td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td align="center">{{ number_format($amortizacionEfectivoDolares, 2, '.', ',') }}</td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h6 ml">TARJETA</span></td>
                    <td align="center">{{ number_format($amortizacionTarjetaSoles, 2, '.', ',') }}</td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td align="center">{{ number_format($amortizacionTarjetaDolares, 2, '.', ',') }}</td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h6 ml">CUENTA BANCARIA</span></td>
                    <td align="center">{{ number_format($amortizacionCuentaBancariaSoles, 2, '.', ',') }}</td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td align="center">{{ number_format($amortizacionCuentaBancariaDolares, 2, '.', ',') }}</td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h5">INGRESOS</span></td>
                    <td align="center"><span class="h5">{{ $montoIngresosSoles }}</span></td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td align="center"><span class="h5">{{ $montoIngresosDolares }}</span></td>
                    @endif
                </tr>
                <tr>
                    <td><span class="h5">EGRESOS</span></td>
                    <td align="center"><span class="h5">{{ $montoEgresosSoles }}</span></td>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <td align="center"><span class="h5">{{ $montoEgresosDolares }}</span></td>
                    @endif
                </tr>
            </tbody>
            <tfoot class="borderTop">
                <tr align="left">
                    <th><span class="h5">CAJA TOTAL EFECTIVO</span></th>
                    <th align="center"><span class="h5">{{ $cajaTotalSoles }}</span></th>
                    @if ($subniveles->contains('IdSubNivel', 46))
                        <th align="center"><span class="h5">{{ $cajaTotalDolares }}</span></th>
                    @endif
                </tr>
            </tfoot>
        </table>

        <table width="100%" style=" margin-top: 20px">
            <tr>
                <td><span class="h2">DETALLE DE VENTAS</span></td>
            </tr>
        </table>
        <table width="100%" class="tabla3">
            <thead class="borderBottom">
                <tr>
                    <th class="borderTabla" align="center"><span class="h5">CÓDIGO</span></th>
                    <th class="borderTabla" align="center"><span class="h5">PRODUCTOS</span></th>
                    <th class="borderTabla" align="center"><span class="h5">DETALLE</span></th>
                    <th class="borderTabla" align="center"><span class="h5">CANTIDAD</span></th>
                    <th class="borderTabla" align="center"><span class="h5">PRECIO</span></th>
                    <th class="borderTabla" align="center"><span class="h5">TOTAL</span></th>
                    <th class="borderTabla" align="center"><span class="h5">IMPORTE FINAL</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ventasAperturaCierreCaja as $ventas)
                    <tr>
                        <td class="borderTabla" align="center">{{ $ventas->Serie }}-{{ $ventas->Numero }}</td>
                        <td class="borderTabla" align="left" style="word-wrap: break-word;">
                            @foreach ($ventas->Productos as $producto)
                                {{ $producto->Descripcion }}<br>
                            @endforeach
                        </td>
                        <td class="borderTabla" align="left">
                            @foreach ($ventas->Productos as $producto)
                                -{{ $producto->Detalle }} <br>
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
                        <td class="borderTabla" align="center">
                            @foreach ($ventas->Productos as $producto)
                                {{ $producto->Importe }} <br>
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
