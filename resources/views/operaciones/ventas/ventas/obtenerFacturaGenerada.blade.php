<!DOCTYPE html>
<html>
    <style type="text/css">
    @page{
        margin-top: 1em;
        margin-left: 0.5em;
        margin-right: 0.5em;
    }
    body{
        font-size: 7px;
        font-family: "sans-serif";
    }
    table{
        border-collapse: collapse;
    }
    td{
        font-size: 7px;
    }
    .h1{
        font-size: 21px;
        font-weight: bold;
    }
    .h2{
        font-size: 19px;
        font-weight: bold;
    }
    .h4{
        font-size: 15px;
        font-weight: bold;
    }
    .h5{
        font-size: 13px;
        font-weight: bold;
    }
    .tabla1{
        margin-bottom: 0px;
    }
    .tabla2 td{
        font-size: 5px;
    }
    .tabla4,
    .tabla3{
        margin-top: 0px;
    }
    .tabla4 td{
        font-size: 5px;
    }
    .tabla3 td{
        font-size: 5px;
    }
    .tabla3 th{
        font-size: 5px;
    }
    .tabla3 .cancelado{
        border-left: 0;
        border-right: 0;
        border-bottom: 0;
        border-top: 1px dotted #000;
        width: 200px;
    }
    .negrita{
        font-weight: bold;
    }
    .linea{
        border-bottom: 1px dotted #000;
    }
    .border{
        border: 2px solid #000;
    }
    .borderTabla{
        border: 1px solid #000;
    }
    .borderTop{
        border-top: 0.5px solid #000;
    }
    .borderBottom{
        border-bottom: 0.5px solid #000;
    }
    .borderPlomo{
        border: 2px solid #818182;
    }
    .fondo{
        background-color: #dfdfdf;
    }
    .container .tabla4{
        /*position: absolute;*/
        bottom: 160px;
    }
    .margen{
        margin: 3px;
    }
    .cursiva{
        font-style: oblique;
    }
</style>

<body>
    <div class="container">
        <table width="12%" class="tabla1">
            <tr>
                <td align="center"><img src="{{$empresa->Imagen}}" alt="" width="110" height="40"></td>
            </tr>
            <tr>
                <td align="center">{{$empresa->Nombre}}</td>
            </tr>
            <tr>
                <td align="center">RUC: {{$empresa->Ruc}}</td>
            </tr>
            <tr>
                <td align="center">{{$empresa->Direccion}} - {{$empresa->Ciudad}}</td>
            </tr>
            <tr>
                <td align="center">==============================</td>
            </tr>
            <tr>
                <td align="center">Teléfono: {{$empresa->Telefono}}</td>
            </tr>
            <tr>
                <td align="center">==============================</td>
            </tr>
            <tr>
                @if($ventaSelect->IdTipoComprobante == 1)
                    <td class="negrita" align="center">BOLETA ELECTRÓNICA</td>
                @elseif($ventaSelect->IdTipoComprobante == 2)
                    <td class="negrita" align="center">FACTURA ELECTRÓNICA</td>
                @else
                    <td class="negrita" align="center">TICKET PRE-VENTA</td>
                @endif
            </tr>
            <tr>
                <td class="negrita" align="center">{{$ventaSelect->Serie}}-{{$numeroCeroIzq}}</td>
            </tr>
            <tr>
                <td align="center">==============================</td>
            </tr>
        </table>
        <table width="12%" class="tabla2">
            <tr>
                <td width="20%">Forma de Pago:</td>
                @if($ventaSelect->IdTipoPago == 1)
                <td width="30%">Contado</td>   
                @else
                <td width="30%">Crédito</td>
                @endif
            </tr>
            <tr>
                <td width="30%">Moneda:</td>
                <td width="69%">{{$ventaSelect->Moneda}}</td>
            </tr>
            <tr>
                <td width="30%">Fecha / Hora:</td>
                <td width="69%">{{$formatoFecha}} {{$formatoHora}}</td>
            </tr>
            <tr>
                <td width="30%">Ruc/Dni:</td>
                <td width="69%">{{$ventaSelect->NumeroDocumento}}</td>
            </tr>
            <tr>
                <td width="30%">Nombre/Razón:</td>
                <td width="69%">{{$ventaSelect->Nombres}}</td>
            </tr>
            <tr>
                <td width="30%">Dirección:</td>
                <td width="69%">{{$ventaSelect->DirCliente}}</td>
            </tr>
            
            <!--<tr>
                <td width="15%">Teléfono:</td>
                <td width="25%">{{$ventaSelect->TelfCliente}}</td>
                <td width="10%">&nbsp;</td>
                
            </tr>
            <tr>
                <td width="15%">SUCURSAL:</td>
                <td width="25%">{{$ventaSelect->Sucursal}}</td>
                <td width="10%">&nbsp;</td>
            </tr>
            <tr>
                <td width="15%">FECHA EMISIÓN:</td>
                <td width="25%">{{$formatoFecha}}</td>
                <td width="10%">&nbsp;</td>
            </tr>
            <tr>
                <td width="15%">Código:</td>
                <td width="25%"></td>
                <td width="10%">&nbsp;</td>
            </tr>-->
            
        </table>
        <table width="12%" class="tabla1">
            <tr>
                <td align="center">==============================</td>
            </tr>
        </table>
        <table width="12%" class="tabla3">
            <thead>
                <tr>
                    <th align="center">Descripción</th>
                    <th align="center">Cantidad</th>
                    <th align="center">Precio</th>
                    <th align="center">Descuento</th>
                    <th align="center">Importe</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td align="center">{{$item->Descripcion}} {{$item->Detalle}}</td>
                    <td align="center">{{$item->Cantidad}} <span> </span> {{$item->TextUnidad}} <span>x</span> {{$item->CantidadTipo}} </td>
                    <td align="center">{{$item->PrecioUnidadReal}}</td>
                    <td align="center">{{$item->Descuento}}</td>
                    <td align="center">{{$item->Importe}}</td>
                </tr>
                @endforeach
            </tbody>
        </table> 
        <table width="100%" class="tabla4 borderTop borderBottom">
            <thead>
                <tr>
                    <td width="25%" align="center">IMP. BRUTO</td>
                    <td width="25%" align="center">TOTAL DESCUENTO</td>
                    <td width="25%" align="center">IGV(18%)</td>
                    <td width="25%" align="center">TOTALES</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="borderTop" width="25%" align="center">{{$ventaSelect->Subtotal}}</td>
                    <td class="borderTop" width="25%" align="center">{{$ventaSelect->Exonerada}}</td>
                    <td class="borderTop" width="25%" align="center">{{$ventaSelect->IGV}}</td>
                    <td class="borderTop" width="25%" align="center">{{$ventaSelect->Total}}</td>
                </tr>
            </tbody>
        </table>
        <table width="100%" class="tabla4">
            <tr>
                <td width="25%">Son:</td>
                <td width="70%">{{$importeLetras}}</td>
            </tr>
        </table>
        <table width="113%" class="tabla1">
            <tr>
                <td align="center" class="negrita">-------------------------------------------------------</td>
            </tr>
        </table>
        <table width="100%" class="tabla4">
            <tr>
                <td width="25%">Vendedor:</td>
                <td width="70%">{{$ventaSelect->Usuario}}</td>
            </tr>
            <tr>
                <td width="25%">Observaciones:</td>
                <td width="70%">{{$ventaSelect->Observacion}}</td>
            </tr>
        </table>
        @if($ventaSelect->IdTipoComprobante == 3)
            <table width="100%" class="tabla1">
                <tr>
                    <td align="center">Este documento no tiene ningún valor tributario, solo representa un ticket de Pre-Venta</td>
                </tr>
            </table>
        @endif
        @if($ventaSelect->IdTipoComprobante != 3)
        <table width="100%" class="tabla1">
            <tr>
                <td align="center" class="negrita">-------------------------------------------------------</td>
            </tr>
        </table>
        <table width="12%" class="tabla3">
            <tr>
                <td align="center"><span class="negrita">{{$hash}}</span></td>
            </tr>
        </table>
        <table width="100%" class="tabla1">
            <tr>
                <td align="center" class="negrita">-------------------------------------------------------</td>
            </tr>
        </table>
        <table width="12%" class="tabla3">
            <tr>
                <td align="center">
                    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate($resumen)) !!} ">
                </td>
            </tr>
            <tr>
                <td align="center">
                    <!--<div>
                        Autorizado mediante Resolución
                    </div>-->
                    <div>
                        @if($ventaSelect->IdTipoComprobante == 1)
                            Representación Impresa de la BOLETA ELECTRÓNICA
                        @else
                            Representación Impresa de la FACTURA ELECTRÓNICA
                        @endif
                    </div>
                    <div>
                        Consulte Documento en www.mifacturita.pe
                    </div>
                </td>
            </tr>
        </table>
        @endif
    </div>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  	<script type="text/javascript">
    
     $(document).ready(function () {
        window.print();
        setTimeout("closePrintView()", 500);
    });
	
	function closePrintView() {
        //document.location.href = 'http://mifacturita.pe/test4/operaciones/ventas/realizar-venta';
		//window.location.replace("../operaciones/ventas/realizar-venta");
		window.location.href="../realizar-venta";	
    }

  </script>
	
	
	
</body>
</html>
