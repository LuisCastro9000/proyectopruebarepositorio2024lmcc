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

    .fondo {
        background-color: #dfdfdf;
    }

    .container .tabla4 {
        /*position: absolute;*/
        bottom: 300px;
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

    .fs-9 {
        font-size: 9px;
    }

    .fs-8 {
        font-size: 8px;
    }

    .fs-7 {
        font-size: 7.5px;
        font-weight: bold;
    }

    .textoTruncado {
        -webkit-line-clamp: 2;
        -moz-line-clamp: 2;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-box-orient: vertical;
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
                                        <span class="h4">RUC: {{ $empresa->Ruc }}</span>
                                    </div>
                                    <div>
                                        @if ($ventaSelect->IdTipoComprobante == 1)
                                            <span class="h4">BOLETA ELECTRÓNICA</span>
                                        @elseif($ventaSelect->IdTipoComprobante == 2)
                                            <span class="h4">FACTURA ELECTRÓNICA</span>
                                        @else
                                            <span class="h4">TICKET PRE-VENTA</span>
                                        @endif
                                    </div>
                                    <div style="margin-bottom: 15px;">
                                        <span class="h4">{{ $ventaSelect->Serie }}-{{ $numeroCeroIzq }}</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <section style="margin-bottom:10px">
            <span>{{ $empresa->PaginaWeb }}</span>
            <span style="display: block">{{ $empresa->CorreoEmpresa }}</span>
        </section>
        <table width="100%" class="tabla1">
            <tr>
                <td><span class="negrita">{{ $nombreEmpresa }}</span></td>
            </tr>
            <tr>
                <td><span class="negrita fs-10">Principal: </span><span class="fs-10">{{ $empresa->DirPrincipal }} -
                        {{ $empresa->Distrito }}</span></td>
            </tr>
            @if ($sucursal != null && $sucursal->OcultarDireccion == 'E')
                <tr>
                    <td><span class="negrita fs-10">Sucursal: </span></span><span
                            class="fs-10">{{ $sucursal->Direccion }}</span></td>
                </tr>
            @endif
            <tr>
                <td><span class="negrita fs-10">TELÉFONO: </span>{{ $empresa->Telefono }}</td>
            </tr>
        </table>
        <table width="100%" class="tabla2">
            <thead>
                <tr>
                    <th width="60%" class="borderTabla fondo" align="center">DATOS DEL RECEPTOR</th>
                    <th width="40%" class="borderTabla fondo" align="center">DATOS GENERALES</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="borderTabla">
                        <div class="margen">
                            @if ($idSeguro > 2)
                                <span class="negrita">Raz. Social:</span> {{ $seguroNombre }} <br>
                                <span class="negrita">Responsable Asegurado:</span> {{ $ventaSelect->RazonSocial }}
                                <br>
                            @else
                                <span class="negrita">Raz. Social:</span> {{ $ventaSelect->RazonSocial }} <br>
                            @endif
                            <span class="negrita">{{ $ventaSelect->TipoDoc }}:</span>
                            {{ $ventaSelect->NumeroDocumento }} <br>
                            <span class="negrita">Dirección:</span> {{ substr($ventaSelect->DirCliente, 0, 120) }} <br>
                            @if ($ventaSelect->Placa != '')
                                <span class="negrita">Placa:</span> {{ $ventaSelect->Placa }} <br>
                            @endif
                            <span class="negrita">Forma de Pago: </span>
                            @if ($ventaSelect->IdTipoPago == 1)
                                Contado
                            @else
                                Crédito /
                            @endif
                            @if ($ventaSelect->IdTipoPago == 2)
                                <span class="negrita">Cuota:</span>1 / <span class="negrita"> Monto:</span>
                                @if ($ventaSelect->Detraccion == 1)
                                    {{ number_format($ventaSelect->Total - $ventaSelect->Total * $ventaSelect->PorcentajeDetraccion / 100, 2, '.', ',') }}
                                @elseif($ventaSelect->Retencion == 1)
                                    {{ number_format($ventaSelect->Total - ($ventaSelect->Total + $ventaSelect->Amortizacion) * 0.03, 2) }}
                                @else
                                    {{ number_format($ventaSelect->Total, 2) }}
                                @endif /
                                <span class="negrita"> Fecha Pago:</span>{{ $fechaPago }}
                            @endif
                            @if ($ventaSelect->Retencion == 1)
                            <br><span class="negrita">Base Imp. de
                                    Retención:</span>{{ number_format($ventaSelect->Total + $ventaSelect->Amortizacion, 2) }}
                                / <span class="negrita"> Porcentaje de Retención:</span>3% / <span class="negrita">
                                    Monto de
                                    Retención:</span>{{ number_format(($ventaSelect->Total + $ventaSelect->Amortizacion) * 0.03, 2) }}
                            @endif
                            @if($ventaSelect->OrdenCompra != null && $ventaSelect->OrdenCompra != "")
                                <br><span class="negrita">Orden de Compra:</span>{{$ventaSelect->OrdenCompra}}</span>
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
                            @endif
                            @if ($ventaSelect->MontoEfectivo !== null && $ventaSelect->MontoEfectivo !== '0.00')
                                <br><span class="negrita">Pago. Efectivo:</span> <span
                                    class="text-muted">{{ $ventaSelect->MontoEfectivo }}</span>
                            @endif
                            @if ($ventaSelect->MontoTarjeta !== null && $ventaSelect->MontoTarjeta !== '0.00')
                                <br><span class="negrita">Pago. Tarjeta:</span> <span
                                    class="text-muted">{{ $ventaSelect->MontoTarjeta }}</span>
                            @endif
                            @if ($ventaSelect->MontoCuentaBancaria !== null && $ventaSelect->MontoCuentaBancaria !== '0.00')
                                <br><span class="negrita">Pago. Depósito:</span> <span
                                    class="text-muted">{{ $ventaSelect->MontoCuentaBancaria }}</span>
                                @if (isset($ventaSelect->NumeroOperacionBancaria))
                                    -
                                    <span class="negrita">N° Operación:</span>
                                    <span class="text-muted">{{ $ventaSelect->NumeroOperacionBancaria }}</span>
                                @endif
                            @endif
                            <!--<span class="negrita">Usuario: </span> {{ $ventaSelect->Sucursal }}-->
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <table width="100%" class="tabla2">
            @if ($ventaSelect->Principal != 1)
                <!--<tr>
    <td class="negrita" width="15%">VENDEDOR:</td>
                <td width="18%">{{ $ventaSelect->Usuario }}</td>
                <td width="10%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
                <td width="25%">&nbsp;</td>

            </tr>
            <tr>
    <td class="negrita"  width="15%">SUCURSAL:</td>
                <td width="40%">{{ $ventaSelect->Sucursal }}</td>
                <td width="15%">&nbsp;</td>
                <td width="15%">&nbsp;</td>
                <td width="15%">&nbsp;</td>

            </tr>
   <tr>
                <td class="negrita"  width="15%">DIRECCION:</td>
                <td width="40%">{{ $ventaSelect->Local }}</td>
    <td width="15%">&nbsp;</td>
                <td width="15%">&nbsp;</td>
                <td width="15%">&nbsp;</td>

            </tr>
   <tr>
    <td class="negrita"  width="15%">CIUDAD:</td>
                <td width="40%">{{ $ventaSelect->Ciudad }}</td>
                <td width="15%">&nbsp;</td>
                <td width="15%">&nbsp;</td>
                <td width="15%">&nbsp;</td>
            </tr>-->
            @endif
        </table>
        @php
            $a = 1;
            $b = 1;
        @endphp
        <table width="100%" class="tabla3">
            @if (count($itemsProd) > 0)
                <thead>
                    <tr>
                        <th class="borderTabla fondo" align="center">Item</th>
                        <th class="borderTabla fondo" style="width:70px" align="center">Código Producto</th>
                        <th class="borderTabla fondo" align="center">Cantidad</th>
                        <th class="borderTabla fondo" style="width:340px" align="center">Descripción de Producto</th>
                        <th class="borderTabla fondo" align="center">Precio</th>
                        <th class="borderTabla fondo" align="center">Descuento</th>
                        <th class="borderTabla fondo" align="center">Importe</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($itemsProd as $item)
                        <tr>
                            <td class="borderTabla" align="center">{{ $a++ }}</td>
                            <td class="borderTabla" align="center">{{ $item->Cod }}</td>
                            <td class="borderTabla" align="center">{{ $item->Cantidad }} {{ $item->TextUnidad }}
                            </td>
                            <td class="borderTabla" align="center">{{ $item->Descripcion }} {{ $item->Detalle }} </td>
                            <td class="borderTabla" align="center">{{ $item->PrecioUnidadReal }}</td>
                            <td class="borderTabla" align="center">{{ $item->Descuento }}</td>
                            <td class="borderTabla" align="center">{{ $item->Importe }}</td>
                        </tr>
                    @endforeach
                </tbody>
            @endif
        </table>
        <table width="100%" class="tabla3">
            @if (count($itemsServ) > 0)
                <thead>
                    <tr>
                        <th class="borderTabla fondo" align="center">Item</th>
                        <th class="borderTabla fondo" style="width:70px" align="center">Código Servicio</th>
                        <th class="borderTabla fondo" align="center">Cantidad</th>
                        <th class="borderTabla fondo" style="width:340px" align="center">Descripción de Servicio</th>
                        <th class="borderTabla fondo" align="center">Precio</th>
                        <th class="borderTabla fondo" align="center">Descuento</th>
                        <th class="borderTabla fondo" align="center">Importe</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($itemsServ as $item)
                        <tr>
                            <td class="borderTabla" align="center">{{ $b++ }}</td>
                            <td class="borderTabla" align="center">{{ $item->Cod }}</td>
                            <td class="borderTabla" align="center">{{ $item->Cantidad }} {{ $item->TextUnidad }}
                            </td>
                            <td class="borderTabla" align="center">{{ $item->Descripcion }} {{ $item->Detalle }}
                            </td>
                            <td class="borderTabla" align="center">{{ $item->PrecioUnidadReal }}</td>
                            <td class="borderTabla" align="center">{{ $item->Descuento }}</td>
                            <td class="borderTabla" align="center">{{ $item->Importe }}</td>
                        </tr>
                    @endforeach
                </tbody>
            @endif
        </table>
        @php $c=$a + $b @endphp
        @if ($c > 18 && $c < 25)
            <div class="page-break"></div>
        @endif
        <table width="100%" class="tabla3">
            <tfoot>
                <tr>
                    <td colspan="7" class="borderTabla">
                        <div class="margen">
                            <span class="negrita">OBSERVACIONES: </span> @php echo nl2br($ventaSelect->Observacion) @endphp
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
        <br>
        <table width="100%">
            <tr>
                @if ($ventaSelect->Anticipo > 2)
                    <td align="left"><span class="negrita">Comprob. Anticipo
                            :</span>{{ $anticipoSelect->Serie }}-{{ $anticipoSelect->Numero }} / <span
                            class="negrita">Monto :</span>{{ $anticipoSelect->Total }} / <span class="negrita">Fecha
                            :</span>{{ $formatoFechaAnticipo }}</td>
                @endif
                <td align="right"><span class="negrita">SON: </span> {{ $importeLetras }}</td>
            </tr>
            @if ($ventaSelect->Gratuita > 0)
                <tr>
                    <td align="right"><span class="negrita">TRANSFERENCIA GRATUITA DE UN BIEN Y/O SERVICIO PRESTADO
                            GRATUITAMENTE</span></td>
                </tr>
            @endif
        </table>
        <table width="100%" class="tabla4 borderTop borderBottom">
            <thead>
                <tr>
                    <td width="20%" align="center" class="fondo">
                        @if ($ventaSelect->TipoVenta == 1)
                            OP GRAVADAS
                        @else
                            OP EXONERADAS
                        @endif
                    </td>
                    <td width="20%" align="center" class="fondo">OP GRATUITAS</td>
                    <td width="20%" align="center" class="fondo">TOTAL DESCUENTO</td>
                    <td width="20%" align="center" class="fondo">
                        @if ($ventaSelect->TipoVenta == 1)
                            IGV(18%)
                        @else
                            IGV(0%)
                        @endif
                    </td>
                    <td width="20%" align="center" class="fondo">TOTALES</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="borderTop" width="20%" align="center">
                        {{ number_format($ventaSelect->Subtotal, 2) }}</td>
                    <td class="borderTop" width="20%" align="center">
                        {{ number_format($ventaSelect->Gratuita, 2) }}</td>
                    <td class="borderTop" width="20%" align="center">
                        {{ number_format($ventaSelect->Exonerada, 2) }}</td>
                    <td class="borderTop" width="20%" align="center">{{ number_format($ventaSelect->IGV, 2) }}
                    </td>
                    <td class="borderTop" width="20%" align="center">
                        {{ number_format($ventaSelect->Total + $ventaSelect->Amortizacion, 2) }}</td>
                </tr>
            </tbody>
        </table>
        @if ($ventaSelect->IdTipoComprobante == 3)
            <table width="100%">
                <tr>
                    <td align="center">Este documento no tiene ningún valor tributario, solo representa un ticket de
                        Pre-Venta</td>
                </tr>
            </table>
        @endif
        @if ($ventaSelect->IdTipoComprobante != 3)
            <br>
            <div class="abajo">
                @if ($ventaSelect->Detraccion == 1)
                    <table width="100%">
                        <thead>
                            <tr>
                            <th class="fondo fs-9" colspan="5" width="30%" align="center">
                                        INFORMACIÓN DE LA DETRACCIÓN</th>
                                <!--@if (floatval($totalDetrac) >= floatval(2000))
                                    <th class="fondo fs-9" colspan="4" width="30%" align="center">
                                        INFORMACIÓN DE LA DETRACCIÓN</th>
                                @else
                                    <th class="fondo fs-9" colspan="3" width="30%" align="center">
                                        INFORMACIÓN DE LA DETRACCIÓN</th>
                                @endif-->
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td align="center">
                                    <div class="margen fs-8">
                                        <span class="negrita">Medio de Pago:</span> {{$codMedioPago->Codigo}} - {{$codMedioPago->Descripcion}}
                                    </div>
                                </td>
                                <td align="center">
                                    <div class="margen fs-8">
                                        <span class="negrita">Cód. Bien y Servicio:</span> {{$codDetraccion->CodigoSunat}} - {{$codDetraccion->Descripcion}}
                                    </div>
                                </td>
                                <td align="center">
                                    <div class="margen fs-8">
                                    <span class="negrita">Cta. de Detrac.:</span>
                                        @if ($cuentaDetracciones != null)
                                            {{ $cuentaDetracciones->NumeroCuenta }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                </td>
                                <td align="center">
                                    <div class="margen fs-8">
                                        <span class="negrita">Porcentaje Detrac. (%):</span> {{ $ventaSelect->PorcentajeDetraccion }}
                                    </div>
                                </td>
                                <td align="center">
                                    <div class="margen fs-8">
                                        <span class="negrita">Monto Detrac.:</span>
                                        {{ number_format(number_format((($ventaSelect->Total + $ventaSelect->Amortizacion) * $ventaSelect->PorcentajeDetraccion) / 100, 0), 2, '.', ',') }}
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endif

                <table width="100%">
                    <tr>
                        <td width="80%">
                            @if (count($cuentasCorrientes) > 0)
                                <table width="100%">
                                    <thead>
                                        <tr>
                                            <th class="fondo fs-9" width="30%" align="center">ENTIDAD FINANCIERA
                                            </th>
                                            <th class="fondo fs-9" width="40%" align="center">CÓDIGO CUENTA</th>
                                            <th class="fondo fs-9" width="30%" align="center">CÓDIGO INTERBANCARIA
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td align="center">
                                                <div class="margen fs-8">
                                                    @foreach ($cuentasCorrientes as $cuentaCorriente)
                                                        {{ $cuentaCorriente->Banco }} <br>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td align="center">
                                                <div class="margen fs-8">
                                                    @foreach ($cuentasCorrientes as $cuentaCorriente)
                                                        {{-- CTA. CTE {{ $cuentaCorriente->Moneda }} :
                                                        {{ $cuentaCorriente->NumeroCuenta }} <br> --}}
                                                        {{ $cuentaCorriente->NombreCuenta }}
                                                        {{ $cuentaCorriente->Moneda }} :
                                                        {{ $cuentaCorriente->NumeroCuenta }} <br>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td align="center">
                                                <div class="margen fs-8">
                                                    @foreach ($cuentasCorrientes as $cuentaCorriente)
                                                        {{ $cuentaCorriente->CCI }} <br>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            @endif
                            <table width="100%" class="tabla2">
                                <tr>
                                    {{-- <td width="50%">
                                        <div class="fs-9">
                                            <span>Consulte en <a href=""
                                                    target="_blank">www.easyfactperu.pe</a></span>
                                        </div>
                                    </td> --}}
                                    <td width="50%">
                                        <div class="fs-9">
                                            <span>Sujeta a Detracción según Resolución 063/2012</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%">
                                        <div class="fs-9">
                                            Resumen: <span class="negrita">{{ $hash }}</span>
                                        </div>
                                    </td>
                                    <td width="50%" align="right">
                                        <div class="fs-9">
                                            <span>La resolución surtira efecto si esta factura supera los S/700</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="40%">
                                        <div class="fs-9">
                                            @if ($ventaSelect->IdTipoComprobante == 1)
                                                <span>Representación Impresa de la BOLETA ELECTRÓNICA</span>
                                            @else
                                                <span>Representación Impresa de la FACTURA ELECTRÓNICA</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td width="60%" align="right">
                                        @if ($ventaSelect->TipoVenta == 2)
                                            <div class="fs-7">
                                                <span>BIENES TRANSFERIDOS EN LA AMAZONIA PARA SER CONSUMIDOS EN LA
                                                    MISMA</span>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                        
                        <td align="right" width="20%">
                            <img src="data:image/png;base64, {!! base64_encode(
                                QrCode::format('png')->size(110)->generate($resumen),
                            ) !!} ">
                        </td>
                    </tr>
                </table>
            </div>
        @endif
    </div>
</body>

</html>
