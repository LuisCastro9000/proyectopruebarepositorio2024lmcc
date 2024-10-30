<?php

namespace App\Http\Controllers\Operaciones\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use App\Http\Controllers\Operaciones\NumeroALetras;
use App\Http\Controllers\Servicios\config;
use Carbon\Carbon;
use DateTime;
use DB;
use DOMDocument;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\Cuota;
use Greenter\Model\Sale\FormaPagos\FormaPagoCredito;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\Note;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Ws\Services\SunatEndpoints;
use Illuminate\Http\Request;
use Session;

class NotaCreditoDebitoController extends Controller
{
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        $idSucursal = Session::get('idSucursal');
        $loadDatos = new DatosController();
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $fecha = date("d/m/Y");
        $motivosCredito = $loadDatos->getMotivos('c', 0);
        $motivosDebito = $loadDatos->getMotivos('d', 0);
        $ventaSelect = [];
        $items = [];
        $codComprobante = '';
        $idTipoComprobante = '';
        $cliente = '';
        $idCliente = '';
        $subTotal = '0';
        $descuento = '0';
        $igv = '0';
        $importeTotal = '0';
        $idVenta = '';
        $tipoMoneda = '';
        $tipoVenta = '';
        $opGravada = '0';
        $opExonerada = '0';
        $opGratuita = '0';
        $date = Carbon::today();
        $deshabilitado = 1;
        $idTipoPago = '';
        $plazoCredito = '';
        $retencion = '';
        $porcentajeDetraccion = '';
        $fechaEmisionFac = '';
        $dateAtras = $date->subMonth(6)->firstOfMonth();

        // ===========
        // $req->idVentas se obtiene desde el modulo de consultas/ventas-boletas-facturas
        $idVentasConsultas = $req->idVentas ? $req->idVentas : '';
        // ===========
        $reportesVentasAceptados = $loadDatos->getVentasAceptadas($idSucursal, $dateAtras, $idVentasConsultas);

        $totalNotas = $loadDatos->getTotalNotas($idSucursal);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $totalSucursales = $loadDatos->getTotalSucursales($usuarioSelect->CodigoCliente);
        $notasCreditoB = $loadDatos->getNotasCreditoDebito($usuarioSelect->CodigoCliente, 1, 1);
        $notasCreditoF = $loadDatos->getNotasCreditoDebito($usuarioSelect->CodigoCliente, 2, 1);
        $notasDebito = $loadDatos->getNotasCreditoDebito($usuarioSelect->CodigoCliente, 1, 2);
        $inicioComprobante = $loadDatos->getInicioComprobante($idSucursal);

        $array = ['permisos' => $permisos, 'reportesVentasAceptados' => $reportesVentasAceptados, 'items' => $items, 'codComprobante' => $codComprobante, 'idSucursal' => $idSucursal, 'idTipoComprobante' => $idTipoComprobante, 'idVenta' => $idVenta, 'totalNotas' => $totalNotas, 'totalSucursales' => $totalSucursales, 'tipoMoneda' => $tipoMoneda, 'tipoVenta' => $tipoVenta, 'inicioComprobante' => $inicioComprobante,
            'fecha' => $fecha, 'cliente' => $cliente, 'idCliente' => $idCliente, 'motivosCredito' => $motivosCredito, 'motivosDebito' => $motivosDebito, 'notasCreditoB' => $notasCreditoB, 'notasCreditoF' => $notasCreditoF, 'notasDebito' => $notasDebito, 'subTotal' => $subTotal, 'descuento' => $descuento, 'igv' => $igv, 'importeTotal' => $importeTotal, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles,
            'idTipoPago' => $idTipoPago, 'plazoCredito' => $plazoCredito, 'deshabilitado' => $deshabilitado, 'opGravada' => $opGravada, 'opExonerada' => $opExonerada, 'opGratuita' => $opGratuita, 'retencion' => $retencion, 'porcentajeDetraccion' => $porcentajeDetraccion, 'fechaEmisionFac' => $fechaEmisionFac, 'idVentasConsultas' => $idVentasConsultas];
        return view('operaciones/ventas/notaCreditoDebito/notaCreditoDebito', $array);
    }

    public function selectVentaAceptada(Request $req, $tipo, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $idSucursal = Session::get('idSucursal');
        $loadDatos = new DatosController();
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $ventaSelect = $loadDatos->getVentaselect($id);

        $idTipoComprobante = $ventaSelect->IdTipoComprobante;
        $idTipoPago = $ventaSelect->IdTipoPago;
        //$fechaEmisionFac = date('Y-m-d', strtotime($ventaSelect->FechaCreacion));
        $fechaEmisionFac = $ventaSelect->FechaCreacion;
        //$fechaConvertida = $fechaEmisionFac->format("Y-m-d");
        //$fechaBanco = Carbon::createFromFormat('d/m/Y', $fechaEmisionFac)->format('Y-m-d');
        //dd();
        $motivosCredito = $loadDatos->getMotivos('c', 0);
        $motivosDebito = $loadDatos->getMotivos('d', 0);

        if ($idTipoPago == 1 || $idTipoComprobante == 1) {
            $motivosCredito = $motivosCredito->whereNotIn('IdMotivo', [23]);
        }

        $items = $loadDatos->getItemsYpaquetePromocional($id);

        $idVenta = $ventaSelect->IdVentas;
        $codComprobante = $ventaSelect->Serie . '-' . $ventaSelect->Numero;
        $cliente = $ventaSelect->Nombres;
        $idCliente = $ventaSelect->IdCliente;
        $subTotal = $ventaSelect->Subtotal;
        $descuento = $ventaSelect->Exonerada;
        $igv = $ventaSelect->IGV;
        $importeTotal = floatval($ventaSelect->Total) + $ventaSelect->Amortizacion;
        $tipoMoneda = $ventaSelect->IdTipoMoneda;
        $tipoVenta = $ventaSelect->TipoVenta;
        $plazoCredito = $ventaSelect->PlazoCredito;
        $retencion = $ventaSelect->Retencion;
        $porcentajeDetraccion = $ventaSelect->PorcentajeDetraccion;
        if ($tipoVenta == 1) {
            $opGravada = $subTotal;
            $opExonerada = '0.00';
        } else {
            $opGravada = '0.00';
            $opExonerada = $subTotal;
        }
        $opGratuita = $ventaSelect->Gratuita;
        $date = Carbon::today();
        $dateAtras = $date->subMonth(6)->firstOfMonth();
        $reportesVentasAceptados = $loadDatos->getVentasAceptadas($idSucursal, $dateAtras);
        if ($tipo == 'F') {
            $tipo = 2;
        } else {
            $tipo = 1;
        }
        $fecha = date("d/m/Y");
        $deshabilitado = 0;
        $totalNotas = $loadDatos->getTotalNotas($idSucursal);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $totalSucursales = $loadDatos->getTotalSucursales($usuarioSelect->CodigoCliente);
        $notasCredito = $loadDatos->getNotasCreditoDebito($usuarioSelect->CodigoCliente, $tipo, 1);
        $notasDebito = $loadDatos->getNotasCreditoDebito($usuarioSelect->CodigoCliente, $tipo, 2);
        $array = ['permisos' => $permisos, 'reportesVentasAceptados' => $reportesVentasAceptados, 'items' => $items, 'codComprobante' => $codComprobante, 'idSucursal' => $idSucursal, 'idTipoComprobante' => $idTipoComprobante, 'idVenta' => $idVenta, 'totalNotas' => $totalNotas, 'totalSucursales' => $totalSucursales, 'tipoMoneda' => $tipoMoneda, 'tipoVenta' => $tipoVenta,
            'fecha' => $fecha, 'cliente' => $cliente, 'idCliente' => $idCliente, 'motivosCredito' => $motivosCredito, 'motivosDebito' => $motivosDebito, 'notasCredito' => $notasCredito, 'notasDebito' => $notasDebito, 'subTotal' => $subTotal, 'descuento' => $descuento, 'igv' => $igv, 'importeTotal' => $importeTotal, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles,
            'idTipoPago' => $idTipoPago, 'plazoCredito' => $plazoCredito, 'deshabilitado' => $deshabilitado, 'opGravada' => $opGravada, 'opExonerada' => $opExonerada, 'opGratuita' => $opGratuita, 'retencion' => $retencion, 'porcentajeDetraccion' => $porcentajeDetraccion, 'fechaEmisionFac' => $fechaEmisionFac];
        return view('operaciones/ventas/notaCreditoDebito/notaCreditoDebito', $array);
    }

    public function getDatosNotaCredito(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
            $loadDatos = new DatosController();
            $id = $req->id;
            $tipo = $req->option;

            $ventaSelect = $loadDatos->getVentaselect($id);

            $idTipoComprobante = $ventaSelect->IdTipoComprobante;
            $idTipoPago = $ventaSelect->IdTipoPago;
            //$fechaEmisionFac = date('Y-m-d', strtotime($ventaSelect->FechaCreacion));
            $fechaEmisionFac = $ventaSelect->FechaCreacion;
            //$fechaConvertida = $fechaEmisionFac->format("Y-m-d");
            //$fechaBanco = Carbon::createFromFormat('d/m/Y', $fechaEmisionFac)->format('Y-m-d');
            //dd();
            // $motivosCredito = $loadDatos->getMotivos('c', 0);
            //$motivosDebito = $loadDatos->getMotivos('d', 0);

            /*if($idTipoPago == 1 || $idTipoComprobante == 1){
            $motivosCredito = $motivosCredito->whereNotIn('IdMotivo', [23]);
            }*/

            $items = $loadDatos->getItemsYpaquetePromocional($id);

            $idVenta = $ventaSelect->IdVentas;
            $codComprobante = $ventaSelect->Serie . '-' . $ventaSelect->Numero;
            $cliente = $ventaSelect->Nombres;
            $idCliente = $ventaSelect->IdCliente;
            $subTotal = $ventaSelect->Subtotal;
            $descuento = $ventaSelect->Exonerada;
            $igv = $ventaSelect->IGV;
            $importeTotal = floatval($ventaSelect->Total) + $ventaSelect->Amortizacion;
            $tipoMoneda = $ventaSelect->IdTipoMoneda;
            $tipoVenta = $ventaSelect->TipoVenta;
            $plazoCredito = $ventaSelect->PlazoCredito;
            $retencion = $ventaSelect->Retencion;
            $porcentajeDetraccion = $ventaSelect->PorcentajeDetraccion;
            $anticipo = $ventaSelect->Anticipo;
            if ($tipoVenta == 1) {
                $opGravada = $subTotal;
                $opExonerada = '0.00';
            } else {
                $opGravada = '0.00';
                $opExonerada = $subTotal;
            }
            $opGratuita = $ventaSelect->Gratuita;
            //$date = Carbon::today();
            //$dateAtras = $date->subMonth(6)->firstOfMonth();
            //$reportesVentasAceptados= $loadDatos->getVentasAceptadas($idSucursal, $dateAtras);
            if ($tipo == 'F') {
                $tipo = 2;
            } else {
                $tipo = 1;
            }
            //$fecha=date("d/m/Y");
            //$deshabilitado = 0;
            //$totalNotas = $loadDatos->getTotalNotas($idSucursal);
            //$usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            //$modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            //$totalSucursales = $loadDatos->getTotalSucursales($usuarioSelect->CodigoCliente);
            //$notasCredito = $loadDatos->getNotasCreditoDebito($usuarioSelect->CodigoCliente, $tipo, 1);
            //$notasDebito = $loadDatos->getNotasCreditoDebito($usuarioSelect->CodigoCliente, $tipo, 2);
            //$notasDebito = [];
            $array = ['items' => $items, 'codComprobante' => $codComprobante, 'idSucursal' => $idSucursal, 'idTipoComprobante' => $idTipoComprobante, 'idVenta' => $idVenta, 'tipoMoneda' => $tipoMoneda, 'tipoVenta' => $tipoVenta, 'anticipo' => $anticipo,
                'cliente' => $cliente, 'idCliente' => $idCliente, 'subTotal' => $subTotal, 'descuento' => $descuento, 'igv' => $igv, 'importeTotal' => $importeTotal,
                'idTipoPago' => $idTipoPago, 'plazoCredito' => $plazoCredito, 'opGravada' => $opGravada, 'opExonerada' => $opExonerada, 'opGratuita' => $opGratuita, 'retencion' => $retencion, 'porcentajeDetraccion' => $porcentajeDetraccion, 'fechaEmisionFac' => $fechaEmisionFac];
            //return view('operaciones/ventas/notaCreditoDebito/notaCreditoDebito', $array);
            return Response($array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function getItemsNotaCredito(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $id = $req->idVenta;
            $loadDatos = new DatosController();
            $items = $loadDatos->getItemsYpaquetePromocional($id);
            //$array = ['items' => $items];
            return Response($items);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function store(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        //dd($req);
        $this->validateNota($req);
        $loadDatos = new DatosController();
        $idVenta = $req->idVenta;
        $ventaSelect = $loadDatos->getVentaselect($idVenta);
        if ($ventaSelect->IdTipoPago == 2) {
            $nuevoMonto = floatval($req->montoNC);
            $pagos = DB::table('fecha_pago')
                ->where('IdVenta', $idVenta)
                ->first();
            $pagosRealiados = floatval($pagos->ImportePagado);
            $importeTotal = floatval($pagos->Importe);
            if ($nuevoMonto > $importeTotal) {
                return back()->with('error', 'No se puede generar Nota de Crédito, el monto a modificar no puede ser mayor al importe total de la factura');
            }
            if ($nuevoMonto < $pagosRealiados) {
                return back()->with('error', 'No se puede generar Nota de Crédito, los pagos realizados exceden al monto a a modificar');
            }
        }

        $idTipoSunat = '00';
        $idTipoNota = $req->tipoNota;
        $idSucursal = Session::get('idSucursal');
        $idCliente = $req->idCliente;
        //$fecha = $req->fechaEmitida;
        $fecha = date('Y-m-d');
        if ($fecha == null) {
            return back()->with('error', 'Por favor, ingresar fecha de venta');
        }
        $serie = $req->serie;
        $numero = $req->numero;
        $numero = $this->completarCeros($numero);
        ////// verificar Codigo ////////
        $verificar = $this->verificarCodigo($serie, $numero, $idSucursal);
        if ($verificar->Cantidad > 0) {
            $ultimoCorrelativo = $this->ultimoCorrelativo($idUsuario, $idSucursal, 1);
            $sumarCorrelativo = intval($ultimoCorrelativo->Numero) + 1;
            $numero = $this->completarCeros($sumarCorrelativo);
        }
        $motivo = $req->motivo;
        $date = DateTime::createFromFormat('Y-m-d', $fecha);
        $fechaConvertida = $date->format("Y-m-d H:i:s");
        $observacion = $req->observacion;
        $tipoVenta = $req->tipoVenta;
        $idTipoMoneda = $req->tipoMoneda;
        if ($tipoVenta == 1) {
            $subtotal = $req->subtotal;
        } else {
            $subtotal = $req->opExonerado;
        }
        //dd($subtotal);
        $gratuita = $req->opGratuita;
        $descuento = $req->exonerada;
        $igv = $req->igv;
        $total = $req->total;
        $docModificar = $req->codComprobante;
        $idDocModificar = $req->idTipoComprobante;
        if ($descuento == '-') {
            $descuento = '0.00';
        }
        $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
        if ($caja == null) {
            //return back()->with('caja','Abrir Caja antes de realizar una venta');
            echo "<script language='javascript'>alert('Abrir Caja antes de realizar una venta');window.location='../../caja/cierre-caja'</script>";
        } else {
            $bandera = 1;
            $codigoAceptado = '';
            $noReduceStock = 0;
            $estado = "";
            $resumen = '';
            $hash = '';

            if ($idTipoNota == 1) {
                $notaCD = 'N. Crédito';
                $idTipoSunat = '07';
            }
            if ($idTipoNota == 2) {
                $notaCD = 'N. Débito';
                $idTipoSunat = '08';
            }

            if ($motivo == 23) {
                $subtotal = 0;
                $gratuita = 0;
                $descuento = 0;
                $igv = 0;
                $total = 0;
            }

            $array = ['IdVentas' => $req->idVenta, 'IdTipoMoneda' => $idTipoMoneda, 'IdTipoNota' => $idTipoNota, 'IdUsuarioCreacion' => $idUsuario, 'IdCliente' => $idCliente, 'IdSucursal' => $idSucursal, 'IdMotivo' => $motivo, 'FechaCreacion' => $fechaConvertida, 'IdTipoSunat' => $idTipoSunat, 'IdDocModificado' => $idDocModificar, 'DocModificado' => $docModificar,
                'Serie' => $serie, 'Numero' => $numero, 'Observacion' => $observacion, 'Subtotal' => $subtotal, 'Gratuita' => $gratuita, 'Descuento' => $descuento, 'IGV' => $igv, 'Total' => $total, 'TipoVenta' => $tipoVenta, 'Resumen' => $resumen, 'Hash' => $hash, 'CodigoDoc' => $codigoAceptado, 'Nota' => 0, 'Estado' => $estado];

            DB::table('nota_credito_debito')->insert($array);

            $nota = DB::table('nota_credito_debito')
                ->orderBy('IdCreditoDebito', 'desc')
                ->first();
            $idNota = $nota->IdCreditoDebito;

            if ($idTipoNota == 1) {
                //if($noReduceStock==0)
                //{
                if ($motivo == 1 || $motivo == 2 || $motivo == 3 || $motivo == 6 || $motivo == 7 || $motivo = 23) {
                    for ($i = 0; $i < count($req->codigo); $i++) {
                        $producto = substr($req->codigo[$i], 0, 3);
                        $idProducto = substr($req->codigo[$i], 4);

                        if ($producto == 'PAQ') {
                            $productoSelect = DB::table('paquetes_promocionales AS pp')
                                ->where('pp.IdPaquetePromocional', $idProducto)
                                ->first();

                            $productoSelectDatos = $this->getItemsPaquetePromocional($idProducto);
                            $idPaquetePromocional = $idProducto;
                            for ($j = 0; $j < count($productoSelectDatos); $j++) {
                                $productoSelectItem = $loadDatos->getProductoSelect($productoSelectDatos[$j]->IdArticulo);
                                if ($productoSelectItem->IdTipo == 1) {
                                    $cantidadSumada = $productoSelectItem->Stock + $productoSelectDatos[$j]->cantidad;
                                    DB::table('articulo')
                                        ->where('IdArticulo', $productoSelectDatos[$j]->IdArticulo)
                                        ->update(['Stock' => $cantidadSumada]);

                                    $_stock = $loadDatos->getUltimoStock($productoSelectDatos[$j]->IdArticulo);

                                    $reponer = $_stock->Cantidad + $productoSelectDatos[$j]->cantidad;
                                    DB::table('stock')
                                        ->where('IdStock', $_stock->IdStock)
                                        ->update(['Cantidad' => $reponer]);

                                    $kardex = array(
                                        'CodigoInterno' => $productoSelectItem->CodigoInterno,
                                        'fecha_movimiento' => $fechaConvertida,
                                        'tipo_movimiento' => 7,
                                        'usuario_movimiento' => $idUsuario,
                                        'documento_movimiento' => $serie . '-' . $numero,
                                        'existencia' => $cantidadSumada,
                                        'costo' => $productoSelectItem->Precio,
                                        'IdArticulo' => $productoSelectDatos[$j]->IdArticulo,
                                        'IdSucursal' => $idSucursal,
                                        'Cantidad' => $productoSelectDatos[$j]->cantidad,
                                        'Descuento' => 0,
                                        'ImporteEntrada' => $productoSelectDatos[$j]->Precio,
                                        'ImporteSalida' => 0,
                                        'estado' => 1,
                                    );
                                    DB::table('kardex')->insert($kardex);

                                }
                            }
                        } else {
                            $productoSelect = $loadDatos->getProductoSelect($idProducto);
                            $idPaquetePromocional = null;
                        }

                        if ($producto == 'PRO' && $motivo != 23) {
                            $productoSelect = $loadDatos->getProductoSelect($idProducto);
                            $cantidadSumada = (float) $productoSelect->Stock + (float) $req->cantidad[$i];
                            $_cantidad = (float) $req->cantidad[$i];
                            DB::table('articulo')
                                ->where('IdArticulo', $idProducto)
                                ->update(['Stock' => $cantidadSumada]);

                            $_stock = $loadDatos->getUltimoStock($idProducto);
                            if ($_stock) {
                                $reponer = $_stock->Cantidad + $req->cantidad[$i];
                                DB::table('stock')
                                    ->where('IdStock', $_stock->IdStock)
                                    ->update(['Cantidad' => $reponer]);
                            }

                            $kardex = array(
                                'CodigoInterno' => $productoSelect->CodigoInterno,
                                'fecha_movimiento' => $fechaConvertida,
                                'tipo_movimiento' => 7, //siete  es  nota  de  credito
                                'usuario_movimiento' => $idUsuario,
                                'documento_movimiento' => $serie . '-' . $numero,
                                'existencia' => $cantidadSumada,
                                'costo' => $productoSelect->Precio,
                                'IdArticulo' => $idProducto,
                                'IdSucursal' => $idSucursal,
                                'Cantidad' => $_cantidad,
                                'Descuento' => 0,
                                'ImporteEntrada' => 0,
                                'ImporteSalida' => 0,
                                'estado' => 1);
                            DB::table('kardex')->insert($kardex);
                        }

                        if ($motivo == 23) {
                            $arrayRelacion = ['IdCreditoDebito' => $idNota, 'IdArticulo' => $idProducto, 'Codigo' => $req->codigo[$i], 'Descripcion' => $req->descripcion[$i], 'Gratuito' => $req->gratuitos[$i], 'PrecioVenta' => 0, 'Cantidad' => $req->cantidad[$i], 'Descuento' => 0, 'Total' => 0, 'IdPaquetePromocional' => $idPaquetePromocional];
                            DB::table('nota_detalle')->insert($arrayRelacion);
                        } else {
                            $arrayRelacion = ['IdCreditoDebito' => $idNota, 'IdArticulo' => $idProducto, 'Codigo' => $req->codigo[$i], 'Descripcion' => $req->descripcion[$i], 'Gratuito' => $req->gratuitos[$i], 'PrecioVenta' => $req->precio[$i], 'Cantidad' => $req->cantidad[$i], 'Descuento' => $req->descuento[$i], 'Total' => $req->importe[$i], 'IdPaquetePromocional' => $idPaquetePromocional];
                            DB::table('nota_detalle')->insert($arrayRelacion);
                        }
                    }
                }
                //}
            }

            $res = $this->envioSunat($req, $idNota);
            $hash = $res[0];
            $resumen = $res[1];
            $rutaXml = $res[5];
            $rutaCdr = $res[6];
            if ($res[2] == 0) {
                $bandera = 0;
                $mensaje = $res[1];
                $estado = 'Pendiente';
            } else {
                if ($res[2] == 1) {

                    if ($res[3] == 0) {
                        $codigoAceptado = $res[3];
                        $mensaje = $res[4];
                        if (is_numeric($codigoAceptado)) {
                            $estado = 'Aceptado';
                        } else {
                            $estado = 'Pendiente';
                        }
                    } else if ($res[3] >= 100 && $res[3] <= 1999) {
                        //$bandera = 0;
                        $codigoAceptado = $res[3];
                        $estado = 'Pendiente';
                        $mensaje = $res[5] . '-' . $res[4] . '-' . $res[3];
                    } else if ($res[3] >= 2000 && $res[3] <= 3999) {
                        $noReduceStock = 1;
                        $codigoAceptado = $res[3];
                        $estado = 'Rechazo';
                        $mensaje = $res[4];
                    } else if ($res[3] >= 4000) {
                        $codigoAceptado = $res[3]; //$res[3];
                        $estado = 'Observado';
                        $mensaje = $res[4];
                    } else {
                        $codigoAceptado = $res[3];
                        $estado = 'Pendiente';
                        $mensaje = 'Se genero Nota de Crédito pero no se envio a Sunat';
                    }
                }
                if ($res[2] == 2) {
                    $estado = 'Pendiente';
                    $codigoAceptado = '-';
                    if ($idTipoNota == 1) {
                        $mensaje = 'Se genero Nota de Crédito pero no se envio a Sunat';
                    }
                    if ($idTipoNota == 2) {
                        $mensaje = 'Se genero Nota de Débito pero no se envio a Sunat';
                    }
                }
            }

            DB::table('nota_credito_debito')
                ->where('IdCreditoDebito', $idNota)
                ->update(['Resumen' => $resumen, 'Hash' => $hash, 'CodigoDoc' => $codigoAceptado, 'RutaXml' => $rutaXml, 'RutaCdr' => $rutaCdr, 'Estado' => $estado]);

            if ($noReduceStock == 0) {
                if ($motivo == 7) {
                    $nota = 2;
                } else {
                    $nota = $req->tipoNota;
                }
                DB::table('ventas')
                    ->where('IdVentas', $req->idVenta)
                    ->update(['TipoNota' => $notaCD, 'Nota' => $nota]);
            }

            return redirect('/consultas/notas-credito-debito/detalles/' . $idNota . '/1')->with('status', $mensaje);

            /*if($bandera == 1){

        if($req->idTipoComprobante==1 && $estado != "Rechazo")
        {
        DB::table('ventas')
        ->where('IdVentas',$req->idVenta)
        ->update(['TipoNota' => $notaCD, 'Nota' => $req->tipoNota, 'Estado'=>"N. Pendiente"]);
        }
        else if($req->idTipoComprobante==2 && $estado != "Rechazo")
        {
        DB::table('ventas')
        ->where('IdVentas',$req->idVenta)
        ->update(['TipoNota' => $notaCD, 'Nota' => $req->tipoNota]);
        }

        return redirect('/consultas/notas-credito-debito/detalles/'.$idNota.'/1')->with('status', $mensaje);
        }else{
        return redirect()->to('operaciones/ventas/nota-credito-debito')->with('error', $mensaje);
        }*/

        }
    }

    private function ultimoCorrelativo($idUsuario, $idSucursal, $idTipoNota)
    {
        try {
            $resultado = DB::table('nota_credito_debito')
                ->where('IdUsuarioCreacion', $idUsuario)
                ->where('IdSucursal', $idSucursal)
                ->where('IdTipoNota', $idTipoNota)
                ->orderBy('IdCreditoDebito', 'desc')
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function verificarCodigo($serie, $numero, $idSucursal)
    {
        try {
            $resultado = DB::table('nota_credito_debito')
                ->select(DB::raw("count(IdCreditoDebito) as Cantidad"))
                ->where('Serie', $serie)
                ->where('Numero', $numero)
                ->where('IdSucursal', $idSucursal)
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function completarCeros($numero)
    {
        $numeroConCeros = str_pad($numero, 8, "0", STR_PAD_LEFT);
        return $numeroConCeros;
    }

    private function getItemsPaquetePromocional($idPaquete)
    {
        $datos = DB::table('detalle_articulo_paquetePromocional AS dap')
            ->join('articulo', 'dap.IdArticulo', '=', 'articulo.IdArticulo')
            ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
            ->select('articulo.IdArticulo', 'articulo.Descripcion AS NombreArticulo', 'articulo.IdMarca', 'articulo.Precio', 'articulo.Stock', 'articulo.Codigo AS codigoBarra', 'articulo.IdTipoMoneda AS idTipoMonedaItems', 'articulo.IdCategoria', 'unidad_medida.Nombre as UM', 'unidad_medida.IdUnidadMedida', 'articulo.IdTipo AS idTipoItems', 'articulo.Costo', 'dap.IdPaquetePromocional', 'dap.cantidad', 'dap.CodigoArticulo')
            ->where('IdPaquetePromocional', $idPaquete)
            ->where('articulo.Estado', 'E')
            ->get();
        return $datos;
    }

    protected function envioSunat($req, $idNota)
    {
        if ($req->tipoNota == 1) {
            $res = $this->obtenerXMLNotaCredito($req, $idNota);
            return $res;
        }
        if ($req->tipoNota == 2) {
            $res = $this->obtenerXMLNotaDebito($req);
            return $res;
        }
    }

    protected function obtenerXMLNotaCredito($req, $idNota)
    {

        $idUsuario = Session::get('idUsuario');
        $_arrayOthers = [];

        $config = new config();

        $opcionFactura = DB::table('usuario')
            ->select('OpcionFactura')
            ->where('IdUsuario', $idUsuario)
            ->first();

        if ($opcionFactura->OpcionFactura > 0) {
            if ($opcionFactura->OpcionFactura == 1) //sunat
            {
                $see = $config->configuracion(SunatEndpoints::FE_BETA);
            } else if ($opcionFactura->OpcionFactura == 2) //ose
            {
                $see = $config->configuracion('https://demo-ose.nubefact.com/ol-ti-itcpe/billService?wsdl');
            } else {
                $respuesta = 0;
                $hash = '';
                $resumen = 'Error No  tiene Elejida  el tipo de  envio.... comuniquese con  el administrador';
                $code = '';
                $descripcion = '';
                $isAcceted = '';

                array_push($_arrayOthers, $hash);
                array_push($_arrayOthers, $resumen);
                array_push($_arrayOthers, $respuesta);
                array_push($_array, $code); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                array_push($_array, $descripcion); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                array_push($_array, $isAcceted); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr

                return $_arrayOthers;
            }
        } else {
            $respuesta = 0;
            $hash = '';
            $resumen = 'Error No  tiene Elejida  el tipo de  envio.... comuniquese con  el administrador';
            $code = '';
            $descripcion = '';
            $isAcceted = '';
            array_push($_arrayOthers, $hash);
            array_push($_arrayOthers, $resumen);
            array_push($_arrayOthers, $respuesta);
            array_push($_array, $code); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
            array_push($_array, $descripcion); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
            array_push($_array, $isAcceted); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr

            return $_arrayOthers;
        }

        // $see = $config->configuracion(SunatEndpoints::FE_PRODUCCION);
        //  $see = $config->configuracion(SunatEndpoints::FE_BETA);
        // dd($see);
        $loadDatos = new DatosController();
        if ($req->idTipoComprobante == 2) {
            $tipoDoc = '01';
        }
        if ($req->idTipoComprobante == 1) {
            $tipoDoc = '03';
        }
        $idMotivo = $req->motivo;
        $selectMotivo = $loadDatos->getSelectMotivo($idMotivo, 'c');
        //$total = floatval($req->total) - floatval($req->exonerada);
        $total = floatval($req->total) - floatval(0);
        //$fecha = $req->fechaEmitida;
        $fecha = date('Y-m-d');
        $date = DateTime::createFromFormat('Y-m-d', $fecha);
        $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');
        $sucursal = $loadDatos->getSucursalSelect($idSucursal);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $idTipoMoneda = $req->tipoMoneda;
        $tipoMoneda = $loadDatos->getTipoMonedaSelect($idTipoMoneda);
        $tipoVenta = $req->tipoVenta;
        $tipoPago = $req->tipoPago;
        $plazoCredito = $req->plazoCredito;

        if ($tipoVenta == 1) {
            $opGravada = $req->subtotal;
            $opExonerada = '0.00';
        } else {
            $opGravada = '0.00';
            $opExonerada = $req->opExonerado;
        }

        if ($req->tipoMoneda == 1) {
            $totalDetrac = floatval($total);
        } else {
            $totalDetrac = floatval($total);
            //$totalDetrac = floatval($total * $valorCambioVentas);
        }

        $totalGratuita = 0;
        if (floatval($req->opGratuita) > 0) {
            $totalGratuita = floatval($req->opGratuita);
            if ($tipoVenta == 1) {
                $subTotalGratuita = floatval($totalGratuita / 1.18);
                $igvGratuita = floatval($totalGratuita - $subTotalGratuita);
            } else {
                $subTotalGratuita = floatval($totalGratuita);
                $igvGratuita = '0.00';
            }
        } else {
            $subTotalGratuita = '0.00';
            $igvGratuita = '0.00';
        }

        $address = new Address();
        $address->setUbigueo($empresa->Ubigeo)
            ->setDepartamento($empresa->Departamento)
            ->setProvincia($empresa->Provincia)
            ->setDistrito($empresa->Distrito)
            ->setUrbanizacion('NONE')
            ->setCodLocal($sucursal->CodFiscal)
            ->setDireccion($sucursal->DirPrin);
        //->setDireccion($sucursal->Direccion);

        $company = new Company();
        $company->setRuc($empresa->Ruc)
            ->setRazonSocial($empresa->Nombre)
            ->setNombreComercial('NONE')
            ->setAddress($address);

        $cliente = $loadDatos->getClienteSelect($req->idCliente);

        $client = new Client();
        $client->setTipoDoc(strval($cliente->CodigoSunat)) //agregado
            ->setNumDoc($cliente->NumeroDocumento)
            ->setRznSocial($cliente->RazonSocial);

        if ($idMotivo == 23) {
            $note = new Note();
            $note->setUblVersion('2.1')
                ->setTipDocAfectado($tipoDoc)
                ->setNumDocfectado($req->codComprobante)
                ->setCodMotivo($selectMotivo->CodigoSunat)
                ->setDesMotivo($selectMotivo->Descripcion)
                ->setTipoDoc('07')
                ->setSerie($req->serie)
                ->setFechaEmision($date)
                ->setCorrelativo($req->numero)
                ->setTipoMoneda($tipoMoneda->CodigoMoneda)
                ->setCompany($company)
                ->setClient($client)
                ->setMtoOperGravadas(0.00)
                ->setMtoOperExoneradas(0.00)
                ->setMtoOperGratuitas(0.00)
                ->setMtoIGVGratuitas(0.00)
                ->setMtoIGV(0.00)
                ->setTotalImpuestos(0.00)
                ->setMtoImpVenta(0.00);
        } else {
            $note = new Note();
            $note->setUblVersion('2.1')
                ->setTipDocAfectado($tipoDoc)
                ->setNumDocfectado($req->codComprobante)
                ->setCodMotivo($selectMotivo->CodigoSunat)
                ->setDesMotivo($selectMotivo->Descripcion)
                ->setTipoDoc('07')
                ->setSerie($req->serie)
                ->setFechaEmision($date)
                ->setCorrelativo($req->numero)
                ->setTipoMoneda($tipoMoneda->CodigoMoneda)
                ->setCompany($company)
                ->setClient($client)
                ->setMtoOperGravadas(floatval($opGravada))
                ->setMtoOperExoneradas(floatval($opExonerada))
                ->setMtoOperGratuitas(floatval($subTotalGratuita))
                ->setMtoIGVGratuitas(floatval($igvGratuita))
                ->setMtoIGV(floatval($req->igv))
                ->setTotalImpuestos(floatval($req->igv))
                ->setMtoImpVenta(floatval($total));
        }
        $array = [];
        $legends = [];
        $countGratuita = 0;
        $condicionDetrac = 0;

        $idVenta = $req->idVenta;
        $ventaSelect = $loadDatos->getVentaselect($idVenta);

        if($ventaSelect->Anticipo > 2){
            $precioUnitario = floatval($total);
            $subTotalPrecioUnitario = floatval($precioUnitario) / 1.18;
            $afectIgv = '10';
            $porcentaje = 18;
            
            $igvItem = floatval($precioUnitario) - floatval($subTotalPrecioUnitario);
            $mtoValorVenta = floatval($subTotalPrecioUnitario);
            $igvTotal = floatval($igvItem);
            $totalImpuesto = floatval($igvTotal);
            $valorGratuito = 0;
            $detail = new SaleDetail();
            $detail->setCodProducto('SER-001')
                ->setUnidad('ZZ')
                ->setCantidad(1)
                ->setDescripcion('Servicio Anticipo - NC')
                ->setMtoBaseIgv(round($mtoValorVenta, 5))
                ->setPorcentajeIgv($porcentaje)
                ->setIgv(round($igvTotal, 5))
                ->setTipAfeIgv($afectIgv)
                ->setTotalImpuestos(round($totalImpuesto, 5))
                ->setMtoValorVenta(round($mtoValorVenta, 5))
                ->setMtoValorGratuito(round($valorGratuito, 5))
                ->setMtoValorUnitario(round($subTotalPrecioUnitario, 5))
                ->setMtoPrecioUnitario(round($precioUnitario, 5));
            array_push($array, $detail);
        }else{
            for ($i = 0; $i < count($req->codigo); $i++) {
                $idProducto = substr($req->codigo[$i], 4);
                $producto = substr($req->codigo[$i], 0, 3);
                if ($producto == 'PAQ') {
                    $productoSelect = DB::table('paquetes_promocionales')
                        ->where('IdPaquetePromocional', $idProducto)
                        ->first();

                    $medidaSunat = 'ZZ';
                    $descripcion = $productoSelect->NombrePaquete;
                } else {
                    $productoSelect = $loadDatos->getProductoSelect($idProducto);

                    $medidaSunat = $productoSelect->MedidaSunat;
                    $descripcion = $productoSelect->Descripcion;
                }

                if ($productoSelect->IdTipo == 2) {
                    $condicionDetrac = 1;
                }
                /*$item=DB::table('ventas_articulo')
                ->where('IdArticulo', $idProducto)
                ->where('IdVentas', $req->idVenta)
                ->first();*/

                $item = DB::table('nota_detalle')
                    ->where('IdArticulo', $idProducto)
                    ->where('IdCreditoDebito', $idNota)
                    ->first();

                $precioUnitario = floatval($item->Total / $item->Cantidad);
                if ($tipoVenta == 1) {
                    $subTotalPrecioUnitario = floatval($precioUnitario) / 1.18;
                    $afectIgv = '10';
                    $porcentaje = 18;
                } else {
                    $subTotalPrecioUnitario = floatval($precioUnitario);
                    $afectIgv = '20';
                    $porcentaje = 0;
                }
                

                //$subTotal = floatval($req->importe[$i]/1.18);  //esto no se
                //$igvItem = floatval((floatval($req->importe[$i]) - floatval($subTotal))/intval($req->cantidad[$i]));
                $igvItem = floatval($precioUnitario) - floatval($subTotalPrecioUnitario);
                $mtoValorVenta = floatval(($item->Cantidad) * $subTotalPrecioUnitario);
                $igvTotal = floatval(($item->Cantidad) * $igvItem);
                $totalImpuesto = floatval($igvTotal);
                $valorGratuito = 0;
                if ($item->Gratuito == 1) {
                    $valorGratuito = floatval($subTotalPrecioUnitario);
                    $subTotalPrecioUnitario = 0;
                    $totalImpuesto = 0;
                    $countGratuita++;
                    if ($tipoVenta == 1) {
                        $afectIgv = '11';
                    } else {
                        $afectIgv = '21';
                    }
                }

                if ($idMotivo == 23) {
                    $detail = new SaleDetail();
                    $detail->setCodProducto($req->codigo[$i])
                        ->setUnidad($medidaSunat)
                        ->setCantidad($req->cantidad[$i])
                        ->setDescripcion($descripcion)
                        ->setMtoBaseIgv(0.00)
                        ->setPorcentajeIgv($porcentaje)
                        ->setIgv(0.00)
                        ->setTipAfeIgv($afectIgv)
                        ->setTotalImpuestos(0.00)
                        ->setMtoValorVenta(0.00)
                        ->setMtoValorGratuito(0.00)
                        ->setMtoValorUnitario(0.00)
                        ->setMtoPrecioUnitario(0.00);
                    array_push($array, $detail);
                } else {
                    $detail = new SaleDetail();
                    $detail->setCodProducto($req->codigo[$i])
                        ->setUnidad($medidaSunat)
                        ->setCantidad($req->cantidad[$i])
                        ->setDescripcion($descripcion)
                        ->setMtoBaseIgv(round($mtoValorVenta, 5))
                        ->setPorcentajeIgv($porcentaje)
                        ->setIgv(round($igvTotal, 5))
                        ->setTipAfeIgv($afectIgv)
                        ->setTotalImpuestos(round($totalImpuesto, 5))
                        ->setMtoValorVenta(round($mtoValorVenta, 5))
                        ->setMtoValorGratuito(round($valorGratuito, 5))
                        ->setMtoValorUnitario(round($subTotalPrecioUnitario, 5))
                        ->setMtoPrecioUnitario(round($precioUnitario, 5));
                    array_push($array, $detail);
                }
                usleep(100000);
            }
        }

        if ($idMotivo == 23) {
            $totalCredito = floatval($req->montoNC);
            $plazoNC = $req->diasNC;

            $fechaVenta = strtotime('+' . $plazoNC . ' day', strtotime($ventaSelect->FechaCreacion));
            $fechaVentaFinal = date('Y-m-d', $fechaVenta);

            $note->setFormaPago(new FormaPagoCredito(round($totalCredito, 2)));
            $note->setCuotas([
                (new Cuota())
                    ->setMonto(round($totalCredito, 2))
                    ->setFechaPago(new DateTime($fechaVentaFinal)),
            ]);

            DB::table('ventas')
                ->where('IdVentas', $idVenta)
                ->update(['PlazoCredito' => $plazoNC]);

            DB::table('fecha_pago')
                ->where('IdVenta', $idVenta)
                ->update(['FechaUltimo' => $fechaVentaFinal, 'Importe' => $totalCredito]);
        }

        if ($idMotivo == 7) {
            DB::table('fecha_pago')
                ->where('IdVenta', $req->idVenta)
                ->decrement('Importe', $total);
        }
        /*if($req->idTipoComprobante == 2){
        if($tipoPago == 1){
        //$note->setFormaPago(new FormaPagoContado());
        }else{
        if($condicionDetrac == 1 && floatval($totalDetrac) >= 700 && $tipoVenta == 1){
        $totalCredito = floatval($total) - floatval($total * $req->porcentajeDetraccion/100);
        }else{
        $totalCredito = floatval($total);
        if($req->retencion == 1){
        $montoRetencion = floatval($total * 0.03);
        $totalCredito = floatval($total) - floatval($montoRetencion);
        $note->setDescuentos([
        (new Charge())
        ->setCodTipo('62') // Catalog. 53
        ->setMontoBase($total)
        ->setFactor(0.03) // 3%
        ->setMonto(round($montoRetencion,2))
        ]);
        }else{
        $totalCredito = floatval($total);
        }
        }
        $_date = Carbon::today();
        $fechaPago = $_date->addDays($plazoCredito);

        $note->setFormaPago(new FormaPagoCredito(round($totalCredito,2)));
        $note->setCuotas([
        (new Cuota())
        ->setMonto(round($totalCredito,2))
        ->setFechaPago(new DateTime($fechaPago))
        ]);
        }
        }*/

        $convertirLetras = new NumeroALetras();
        if ($idTipoMoneda == 1) {
            $importeLetras = $convertirLetras->convertir($total, 'soles');
        } else {
            $importeLetras = $convertirLetras->convertir($total, 'dolares');
        }

        $legend = new Legend();
        $legend->setCode('1000')
            ->setValue($importeLetras);

        array_push($legends, $legend);

        if ($countGratuita > 0) {
            $legend2 = (new Legend())
                ->setCode('1002')
                ->setValue('TRANSFERENCIA GRATUITA DE UN BIEN Y/O SERVICIO PRESTADO GRATUITAMENTE');

            array_push($legends, $legend2);
        }

        $note->setDetails($array)
            ->setLegends($legends);
        // Envio a SUNAT.
        //$see = $util->getSee(SunatEndpoints::FE_BETA);
        $xml_string = $see->getXmlSigned($note);
        $now = Carbon::now();
        $anio = $now->year;
        $mes = $now->month;
        $_mes = $loadDatos->getMes($mes);
        $nombreArchivo = $empresa->Ruc . '-07-' . $req->serie . '-' . $req->numero;
        $rutaXml = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/NotasCreditoDebito/' . $nombreArchivo . '.xml';
        $rutaCdr = null;
        /*DB::table('ventas')
        ->where('IdVentas',$idVenta)
        ->update(['Resumen' => $resumen, 'Hash' => $hash, 'RutaXml' => $rutaXml]);*/

        $config->writeXml($note, $see->getFactory()->getLastXml(), $empresa->Ruc, $anio, $_mes, 2);

        if ($req->idTipoComprobante == 2) {
            $res = $see->send($note);
            //dd($res);
            if ($res->isSuccess()) {
                $rutaCdr = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/NotasCreditoDebito/R-' . $nombreArchivo . '.zip';
                $cdr = $res->getCdrResponse();
                $config->writeCdr($note, $res->getCdrZip(), $empresa->Ruc, $anio, $_mes, 2);
                $config->showResponse($note, $cdr);

                $_array = [];
                $respuesta = 1;

                $isAccetedCDR = $res->getCdrResponse()->isAccepted();
                $descripcionCDR = $res->getCdrResponse()->getDescription();
                $codeCDR = $res->getCdrResponse()->getCode();

                $doc = new DOMDocument();
                $doc->loadXML($xml_string);
                $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                $date = new DateTime();
                $fecha = $date->format('Y-m-d');
                $resumen = $empresa->Ruc . '|07|' . $req->serie . '|' . $req->numero . '|' . round($req->igv, 2) . '|' . round($total, 2) . '|' . $fecha . '|' . $cliente->CodigoSunat . '|' . $cliente->NumeroDocumento;
                array_push($_array, $hash);
                array_push($_array, $resumen);
                array_push($_array, $respuesta);
                array_push($_array, $codeCDR); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                array_push($_array, $descripcionCDR); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                array_push($_array, $rutaXml);
                array_push($_array, $rutaCdr);
                return $_array;
            } else {

                $_array = [];
                if ($res->getError()->getCode() == 'HTTP') {
                    echo "<script language='javascript'>alert('Servicio inestable, intentelo en otro momento');</script>";
                    $respuesta = 2;

                    $codeOp = -1;
                    $descripOp = "";
                    $accepOp = -1;

                    $doc = new DOMDocument();
                    $doc->loadXML($xml_string);
                    $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                    $date = new DateTime();
                    $fecha = $date->format('Y-m-d');
                    $resumen = $empresa->Ruc . '|07|' . $req->serie . '|' . $req->numero . '|' . round($req->igv, 2) . '|' . round($total, 2) . '|' . $fecha . '|' . $cliente->CodigoSunat . '|' . $cliente->NumeroDocumento;
                    array_push($_array, $hash);
                    array_push($_array, $resumen);
                    array_push($_array, $respuesta);
                    array_push($_array, $codeOp);
                    array_push($_array, $descripOp);
                    array_push($_array, $rutaXml);
                    array_push($_array, $rutaCdr);

                } else {
                    //echo '<script language="javascript">alert("'.$result->getError()->getMessage().'");</script>';
                    $respuesta = 1;
                    $hash = '';

                    $descripcionError = $res->getError()->getMessage();
                    $codeError = -1;
                    $isAccetedError = -1;

                    $doc = new DOMDocument();
                    $doc->loadXML($xml_string);
                    $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                    $date = new DateTime();
                    $fecha = $date->format('Y-m-d');
                    $resumen = $empresa->Ruc . '|07|' . $req->serie . '|' . $req->numero . '|' . round($req->igv, 2) . '|' . round($total, 2) . '|' . $fecha . '|' . $cliente->CodigoSunat . '|' . $cliente->NumeroDocumento;
                    array_push($_array, $hash);
                    array_push($_array, $resumen);
                    array_push($_array, $respuesta);
                    array_push($_array, $codeError); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                    array_push($_array, $descripcionError); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                    array_push($_array, $rutaXml);
                    array_push($_array, $rutaCdr);
                }
                return $_array;
            }
        } else {
            $_array = [];
            $respuesta = 2;
            $doc = new DOMDocument();
            $doc->loadXML($xml_string);
            $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
            $date = new DateTime();
            $fecha = $date->format('Y-m-d');
            $resumen = $empresa->Ruc . '|07|' . $req->serie . '|' . $req->numero . '|' . round($req->igv, 2) . '|' . round($total, 2) . '|' . $fecha . '|' . $cliente->CodigoSunat . '|' . $cliente->NumeroDocumento;
            array_push($_array, $hash);
            array_push($_array, $resumen);
            array_push($_array, $respuesta);
            array_push($_array, null); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
            array_push($_array, null); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
            array_push($_array, $rutaXml);
            array_push($_array, $rutaCdr);
            return $_array;
        }
    }

    protected function obtenerXMLNotaDebito($req)
    {

        $idUsuario = Session::get('idUsuario');
        $_arrayOthers = [];

        $config = new config();

        $opcionFactura = DB::table('usuario')
            ->select('OpcionFactura')
            ->where('IdUsuario', $idUsuario)
            ->first();

        if ($opcionFactura->OpcionFactura > 0) {
            if ($opcionFactura->OpcionFactura == 1) //sunat
            {
                $see = $config->configuracion(SunatEndpoints::FE_BETA);
            } else if ($opcionFactura->OpcionFactura == 2) //ose
            {
                $see = $config->configuracion('https://demo-ose.nubefact.com/ol-ti-itcpe/billService?wsdl');
            } else {
                $respuesta = 0;
                $hash = '';
                $resumen = 'Error No  tiene Elejida  el tipo de  envio.... comuniquese con  el administrador';
                $code = '';
                $descripcion = '';
                $isAcceted = '';
                array_push($_arrayOthers, $hash);
                array_push($_arrayOthers, $resumen);
                array_push($_arrayOthers, $respuesta);
                array_push($_array, $code); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                array_push($_array, $descripcion); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                array_push($_array, $isAcceted); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr

                return $_arrayOthers;
            }
        } else {
            $respuesta = 0;
            $hash = '';
            $resumen = 'Error No  tiene Elejida  el tipo de  envio.... comuniquese con  el administrador';
            $code = '';
            $descripcion = '';
            $isAcceted = '';
            array_push($_arrayOthers, $hash);
            array_push($_arrayOthers, $resumen);
            array_push($_arrayOthers, $respuesta);
            array_push($_array, $code); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
            array_push($_array, $descripcion); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
            array_push($_array, $isAcceted); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr

            return $_arrayOthers;
        }

        //$config = new config();
        //$see = $config->configuracion(SunatEndpoints::FE_BETA);

        $loadDatos = new DatosController();
        if ($req->idTipoComprobante == 2) {
            $tipoDoc = '01';
        }
        if ($req->idTipoComprobante == 1) {
            $tipoDoc = '03';
        }
        $idMotivo = $req->motivo;
        $selectMotivo = $loadDatos->getSelectMotivo($idMotivo, 'd');
        $fecha = $req->fechaEmitida;
        $date = DateTime::createFromFormat('Y-m-d', $fecha);

        $total = floatval($req->total) - floatval(0);

        $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');
        $sucursal = $loadDatos->getSucursalSelect($idSucursal);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $idTipoMoneda = $req->tipoMoneda;
        $tipoMoneda = $loadDatos->getTipoMonedaSelect($idTipoMoneda);

        $address = new Address();
        $address->setUbigueo($empresa->Ubigeo)
            ->setDepartamento($empresa->Departamento)
            ->setProvincia($empresa->Provincia)
            ->setDistrito($empresa->Distrito)
            ->setUrbanizacion('NONE')
            ->setCodLocal($sucursal->CodFiscal)
            ->setDireccion($sucursal->DirPrin);
        //->setDireccion($sucursal->Direccion);

        $company = new Company();
        $company->setRuc($empresa->Ruc)
            ->setRazonSocial($empresa->Nombre)
            ->setNombreComercial('NONE')
            ->setAddress($address);

        $cliente = $loadDatos->getClienteSelect($req->idCliente);

        $client = new Client();
        $client->setTipoDoc(strval($cliente->CodigoSunat)) //agregado
            ->setNumDoc($cliente->NumeroDocumento)
            ->setRznSocial($cliente->RazonSocial);

        $note = new Note();
        $note->setUblVersion('2.1')
            ->setTipDocAfectado($tipoDoc)
            ->setNumDocfectado($req->codComprobante)
            ->setCodMotivo($selectMotivo->CodigoSunat)
            ->setDesMotivo($selectMotivo->Descripcion)
            ->setTipoDoc('08')
            ->setSerie($req->serie)
            ->setFechaEmision($date)
            ->setCorrelativo($req->numero)
            ->setTipoMoneda($tipoMoneda->CodigoMoneda)
            ->setCompany($company)
            ->setClient($client)
            ->setMtoOperGravadas(floatval($req->subtotal))
            ->setMtoIGV(floatval($req->igv))
            ->setTotalImpuestos(floatval($req->igv))
            ->setMtoImpVenta($total);

        $array = [];
        for ($i = 0; $i < count($req->codigo); $i++) {
            $idProducto = substr($req->codigo[$i], 4);
            $productoSelect = $loadDatos->getProductoSelect($idProducto);
            /*  $precioUnitario = floatval($productoSelect->Precio);
            $subTotalPrecioUnitario = floatval($productoSelect->Precio)/1.18;
            $subTotal = floatval($req->importe[$i]/1.18);
            $igvItem = floatval((floatval($req->importe[$i]) - floatval($subTotal))/intval($req->cantidad[$i]));
            $mtoValorVenta = floatval(intval($req->cantidad[$i]) * $subTotalPrecioUnitario) - floatval($req->descuento[$i]);
            $igvTotal = floatval(intval($req->cantidad[$i]) * $igvItem); */

            $item = DB::table('ventas_articulo')
                ->where('IdArticulo', $idProducto)
                ->where('IdVentas', $req->idVenta)
                ->first();

            $precioUnitario = floatval($item->Importe / ($item->Cantidad * $item->CantidadReal));
            $subTotalPrecioUnitario = floatval($precioUnitario) / 1.18;

            $subTotal = floatval($req->importe[$i] / 1.18); //esto no se
            $igvItem = floatval((floatval($req->importe[$i]) - floatval($subTotal)) / intval($req->cantidad[$i]));
            $mtoValorVenta = floatval(intval($item->Cantidad * $item->CantidadReal) * $subTotalPrecioUnitario);
            $igvTotal = floatval(intval($item->Cantidad * $item->CantidadReal) * $igvItem);

            $detail = new SaleDetail();
            $detail->setCodProducto($req->codigo[$i])
                ->setUnidad($productoSelect->MedidaSunat)
                ->setCantidad($req->cantidad[$i])
                ->setDescripcion($productoSelect->Descripcion)
                ->setMtoBaseIgv($mtoValorVenta)
                ->setDescuentos($req->descuento[$i])
                ->setPorcentajeIgv(18.00)
                ->setIgv($igvTotal)
                ->setTipAfeIgv('10')
                ->setTotalImpuestos($igvTotal)
                ->setMtoValorVenta($mtoValorVenta)
                ->setMtoValorUnitario($subTotalPrecioUnitario)
                ->setMtoPrecioUnitario($precioUnitario);
            array_push($array, $detail);
        }

        $convertirLetras = new NumeroALetras();
        $importeLetras = $convertirLetras->convertir($total, 'soles');

        $legend = new Legend();
        $legend->setCode('1000')
            ->setValue($importeLetras);
        $note->setDetails($array)
            ->setLegends([$legend]);

        // Envio a SUNAT.
        //$see = $util->getSee(SunatEndpoints::FE_BETA);
        $xml_string = $see->getXmlSigned($note);
        $config->writeXml($note, $see->getFactory()->getLastXml(), $empresa->Ruc, 2);
        if ($req->idTipoComprobante == 2) {
            $res = $see->send($note);
            if ($res->isSuccess()) {
                $cdr = $res->getCdrResponse();
                $config->writeCdr($note, $res->getCdrZip(), $empresa->Ruc, 2);
                $config->showResponse($note, $cdr);

                $_array = [];
                $respuesta = 1;

                $isAccetedCDR = $res->getCdrResponse()->isAccepted();
                $descripcionCDR = $res->getCdrResponse()->getDescription();
                $codeCDR = $res->getCdrResponse()->getCode();

                $doc = new DOMDocument();
                $doc->loadXML($xml_string);
                $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                $date = new DateTime();
                $fecha = $date->format('Y-m-d');
                $resumen = $empresa->Ruc . '|08|' . $req->serie . '|' . $req->numero . '|' . round($req->igv, 2) . '|' . round($total, 2) . '|' . $fecha . '|' . $cliente->CodigoSunat . '|' . $cliente->NumeroDocumento;
                array_push($_array, $hash);
                array_push($_array, $resumen);
                array_push($_array, $respuesta);
                array_push($_array, $codeCDR); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                array_push($_array, $descripcionCDR); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                array_push($_array, $isAccetedCDR); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                return $_array;
            } else {
                $_array = [];
                if ($res->getError()->getCode() == 'HTTP') {
                    echo "<script language='javascript'>alert('Servicio inestable, intentelo en otro momento');</script>";
                    $respuesta = 2;

                    $codeOp = -1;
                    $descripOp = "";
                    $accepOp = -1;

                    $doc = new DOMDocument();
                    $doc->loadXML($xml_string);
                    $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                    $date = new DateTime();
                    $fecha = $date->format('Y-m-d');
                    $resumen = $empresa->Ruc . '|08|' . $req->serie . '|' . $req->numero . '|' . round($req->igv, 2) . '|' . round($total, 2) . '|' . $fecha . '|' . $cliente->CodigoSunat . '|' . $cliente->NumeroDocumento;
                    array_push($_array, $hash);
                    array_push($_array, $resumen);
                    array_push($_array, $respuesta);
                    array_push($_array, $codeOp);
                    array_push($_array, $descripOp);
                    array_push($_array, $accepOp);

                } else {
                    //echo '<script language="javascript">alert("'.$result->getError()->getMessage().'");</script>';
                    $respuesta = 0;
                    $hash = '';

                    $descripcionError = $res->getError()->getMessage();
                    $codeError = $res->getError()->getCode();
                    $isAccetedError = -1;

                    $resumen = 'Error ' . $res->getError()->getCode() . ': ' . $res->getError()->getMessage();
                    array_push($_array, $hash);
                    array_push($_array, $resumen);
                    array_push($_array, $respuesta);
                    array_push($_array, $codeError); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                    array_push($_array, $descripcionError); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                    array_push($_array, $isAccetedError); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                }
                return $_array;
            }
        } else {
            $_array = [];
            $respuesta = 2;
            $doc = new DOMDocument();
            $doc->loadXML($xml_string);
            $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
            $date = new DateTime();
            $fecha = $date->format('Y-m-d');
            $resumen = $empresa->Ruc . '|08|' . $req->serie . '|' . $req->numero . '|' . round($req->igv, 2) . '|' . round($total, 2) . '|' . $fecha . '|' . $cliente->CodigoSunat . '|' . $cliente->NumeroDocumento;
            array_push($_array, $hash);
            array_push($_array, $resumen);
            array_push($_array, $respuesta);
            return $_array;
        }
    }

    protected function validateNota(Request $request)
    {
        $this->validate($request, [
            'serie' => 'required',
            'numero' => 'required',
            'codComprobante' => 'required',
        ]);
    }
}
