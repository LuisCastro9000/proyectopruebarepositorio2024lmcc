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
use Greenter\Model\Sale\Charge;
use Greenter\Model\Sale\Cuota;
use Greenter\Model\Sale\DetailAttribute;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\FormaPagos\FormaPagoCredito;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\Prepayment;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Ws\Services\SunatEndpoints;
use Illuminate\Http\Request;
use Session;

class AnticiposController extends Controller
{
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {

            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            //$facturasVentas = $loadDatos->getVentasAll($idSucursal);
            $fechas = $loadDatos->getFechaFiltro(5, null, null);
            $facturasVentas = DB::select('call sp_getFacturasAnticipos(?, ?, ?)', array($idSucursal, $fechas[0], $fechas[1]));
            $facturasVentas = collect($facturasVentas);

            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $tipoPago = '';
            $fecha = '';
            $fechaIni = '';
            $fechaFin = '';
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);

            $array = ['facturasVentas' => $facturasVentas, 'permisos' => $permisos, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'modulosSelect' => $modulosSelect, 'usuarioSelect' => $usuarioSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'rucEmpresa' => $empresa->Ruc];
            return view('operaciones/ventas/ventas/listaAnticipos', $array);

        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function store(Request $req)
    {
        $idUsuario = Session::get('idUsuario');
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');

        //$facturasVentas = $loadDatos->getVentasAll($idSucursal);

        $fecha = $req->fecha;
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;

        if ($fecha == 9) {
            if ($fechaIni == null || $fechaFin == null) {
                return back()->with('error', 'Completar las fechas para filtrar');
            }
            $dateIni = DateTime::createFromFormat('d/m/Y', $fechaIni);
            $dateFin = DateTime::createFromFormat('d/m/Y', $fechaFin);
            if (strtotime($dateIni->format('Y-m-d')) > strtotime($dateFin->format('Y-m-d'))) {
                return back()->with('error', 'La fecha Inicial no puede ser mayor que la Final');
            }
        }

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $facturasVentas = DB::select('call sp_getFacturasAnticipos(?, ?, ?)', array($idSucursal, $fechas[0], $fechas[1]));
        $facturasVentas = collect($facturasVentas);

        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $tipoPago = 1;
        $fecha = '';
        $fechaIni = '';
        $fechaFin = '';
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);

        $array = ['facturasVentas' => $facturasVentas, 'permisos' => $permisos, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'modulosSelect' => $modulosSelect, 'usuarioSelect' => $usuarioSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'rucEmpresa' => $empresa->Ruc];
        return view('operaciones/ventas/ventas/listaAnticipos', $array);
    }

    public function completarFacturaAnticipo(Request $req, $idAnticipo)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();

            $tipoMonedas = $loadDatos->getTipoMoneda();

            $idSucursal = Session::get('idSucursal');
            $text = "";
            $servicios = $loadDatos->getServiciosPagination($idSucursal, 1, $text);
            $serviciosDolares = $loadDatos->getServiciosPagination($idSucursal, 2, $text);
            $ventasFactura = $loadDatos->getVentas($idSucursal, $idUsuario, 2);
            $ventasBoleta = $loadDatos->getVentas($idSucursal, $idUsuario, 1);
            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $productos = $loadDatos->getProductosPagination($idSucursal, $text, 1, 0);
            $productosDolares = $loadDatos->getProductosPagination($idSucursal, $text, 2, 0);

            $fecha = date("d/m/Y");
            $tipoComprobante = $loadDatos->getTipoComprobante();
            $tipoComprobante = $tipoComprobante->whereNotIn('IdTipoComprobante', [3]);

            $tipoDoc = $loadDatos->TipoDocumento();
            $departamentos = $loadDatos->getDepartamentos();
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $orden = $usuarioSelect->Orden;
            $sucursal = $loadDatos->getSucursalSelect($idSucursal);
            $ordenSucursal = $sucursal->Orden;
            $sucExonerado = $sucursal->Exonerado;
            $editarPrecio = $usuarioSelect->EditarPrecio;

            $cuentasSoles = $loadDatos->getCuentasCorrientes($usuarioSelect->CodigoCliente, 1);
            $cuentasDolares = $loadDatos->getCuentasCorrientes($usuarioSelect->CodigoCliente, 2);

            $categorias = $loadDatos->getCategorias($usuarioSelect->CodigoCliente);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

            $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);

            $bandVentaSolesDolares = $datosEmpresa->VentaSolesDolares;

            $exonerado = $datosEmpresa->Exonerado;

            $ventaSelectAnticipo = $loadDatos->getVentaselect($idAnticipo);
            $ultimoComprobante = $this->correlativoActual($idUsuario, $idSucursal, $ventaSelectAnticipo->IdTipoComprobante);
            $numero = str_pad($ultimoComprobante->Numero + 1, 8, "0", STR_PAD_LEFT);
            $serie = $ultimoComprobante->Serie;

            $itemsAnticipo = $loadDatos->getItemsVentas($idAnticipo);

            $clientes = $loadDatos->getTipoClientes($ventaSelectAnticipo->IdTipoDocumento, $idSucursal);

            $date = date("Y-m-d", strtotime($ventaSelectAnticipo->FechaCreacion));

            $valorCambio = DB::table('tipo_cambio')
                ->where('IdSucursal', $idSucursal)
                ->where('FechaCreacion', $date)
                ->first();

            $array = ['ventaSelectAnticipo' => $ventaSelectAnticipo, 'tipoMoneda' => $tipoMonedas, 'cuentasSoles' => $cuentasSoles, 'cuentasDolares' => $cuentasDolares, 'sucExonerado' => $sucExonerado, 'tipoComprobante' => $tipoComprobante, 'fecha' => $fecha, 'exonerado' => $exonerado, 'editarPrecio' => $editarPrecio, 'bandVentaSolesDolares' => $bandVentaSolesDolares, 'numero' => $numero, 'serie' => $serie, 'itemsAnticipo' => $itemsAnticipo, 'idAnticipo' => $idAnticipo,
                'clientes' => $clientes, 'categorias' => $categorias, 'valorCambio' => $valorCambio, 'ventasFactura' => $ventasFactura, 'ventasBoleta' => $ventasBoleta, 'productos' => $productos, 'productosDolares' => $productosDolares, 'servicios' => $servicios, 'serviciosDolares' => $serviciosDolares, 'tipoDoc' => $tipoDoc, 'permisos' => $permisos, 'idSucursal' => $idSucursal, 'modulosSelect' => $modulosSelect, 'usuarioSelect' => $usuarioSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
            return view('operaciones/ventas/ventas/anticipos', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function finalizarAnticipo(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                if ($req->ajax()) {
                    $stockSuficiente = $this->verificarStockSuficiente($req);
                    if (!empty($stockSuficiente)) {
                        return Response(['alert', 'Quedan ' . $stockSuficiente[1] . ' unidades en stock de : ' . $stockSuficiente[0]]);
                    } else {
                        $idUsuario = Session::get('idUsuario');
                        $serie = $req->serie;
                        //$idTipoSunat = 'NT';
                        if ($serie == null) {
                            return Response(['alert', 'Por favor, completar serie y número correlativo']);
                        }
                        $numero = $req->numero;
                        $idTipoComp = $req->idTipoComp;
                        if ($idTipoComp == 0) {
                            return Response(['alert', 'Por favor, elegir Tipo de comprobante']);
                        }
                        $idCliente = $req->cliente;
                        if ($idCliente == 0) {
                            return Response(['alert', 'Por favor, elegir Cliente']);
                        }
                        if ($req->Id == null) {
                            return Response(['alert', 'Por favor, agrege productos o servicios']);
                        }
                        $total = $req->total;
                        $fecha = date('Y-m-d');
                        //$fecha = $req->fechaEmitida;
                        //$fecha = $loadDatos->getDateTime();
                        //dd($fecha);

                        if ($fecha == null) {
                            return Response(['alert', 'Por favor, ingresar fecha de venta']);
                        }

                        $numero = $this->completarCeros($numero);
                        $idSucursal = Session::get('idSucursal');
                        $verificar = $this->verificarCodigo($serie, $numero, $idSucursal);
                        if ($verificar->Cantidad > 0) {
                            return Response(['alert', 'La Serie y Número ya existen, por favor vuelva a generar nuevamente']);
                            //return back()->with('error','La Serie y Número ya existen');
                            /*$ultimoCorrelativo = $this->ultimoCorrelativo($idUsuario, $idSucursal, $idTipoComp);
                        $sumarCorrelativo = intval($ultimoCorrelativo->Numero) + 1;
                        $numero = $this->completarCeros($sumarCorrelativo);*/
                        }

                        $date = DateTime::createFromFormat('Y-m-d', $fecha);
                        $fechaConvertida = $date->format("Y-m-d H:i:s");
                        $idTipoMoneda = $req->TipoMoneda;
                        $valorCambioVentas = $req->valorCambioVentas;
                        $valorCambioCompras = $req->valorCambioCompras;
                        $valorDetraccion = $req->valorDetraccion;
                        $retencion = $req->retencion;
                        $idAnticipo = $req->idAnticipo;

                        $placa = null;
                        $idPlaca = 0;
                        if ($idPlaca == 0) {
                            $placa = null;
                        }
                        /*if($valorCambioVentas == 0 && $idTipoMoneda == 2){
                        return Response(['alert11','Antes de realizar una venta en Dólares, debe ingresar el tipo de cambio']);
                        }*/
                        $banderaVentaSolesDolares = $req->banderaVentaSolesDolares;
                        if ($banderaVentaSolesDolares == 1) {
                            $ventaSolesDolares = 1;
                            //$idTipoMoneda = 1;
                        } else {
                            $ventaSolesDolares = 0;
                        }
                        if ($idTipoMoneda == 1) {
                            $totalDetrac = floatval($total);
                        } else {
                            $totalDetrac = floatval($total * $valorCambioVentas);
                            //$totalDetrac = floatval($total * $valorCambio);
                        }
                        $exonerada = $req->exonerada;
                        $observacion = $req->observacion;
                        $tipoVenta = $req->tipoVenta;
                        if ($tipoVenta == 1) {
                            $subtotal = $req->subtotal;
                            $codTipo = '04'; // op Gravadas
                        } else {
                            $subtotal = $req->opExonerado;
                            $codTipo = '05'; // op Exoneradas
                        }

                        if ($exonerada == '-') {
                            $exonerada = '0.00';
                        }
                        $tipoPago = $req->tipoPago;

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

                        $cliente = DB::table('cliente')
                            ->where('IdCliente', $idCliente)
                            ->where('Estado', 'E')
                            ->first();
                        if ($tipoPago == 1) {
                            $plazoCredito = '';
                            $pagoEfect = $req->pagoEfectivo;
                            $tipoTarjeta = $req->tipoTarjeta;
                            $numTarjeta = $req->numTarjeta;
                            $pagoTarjeta = $req->pagoTarjeta;
                            $vueltoEfectivo = $req->vueltoEfectivo;
                            $cuentaBancaria = $req->CuentaBancaria;
                            $montoCuenta = $req->MontoCuenta;
                            if (floatval($pagoTarjeta) > 0) {
                                if ($numTarjeta == '' || $numTarjeta == null) {
                                    return Response(['alert', 'Completar Numero de Tarjeta']);
                                }
                            }
                            $pagoEfectivo = floatval($pagoEfect) - floatval($vueltoEfectivo);
                            $pagoTotal = floatval($pagoEfectivo) + floatval($pagoTarjeta) + floatval($montoCuenta);
                            $_pagoTotal = round($pagoTotal, 2);
                            $_total = round($total, 2);
                            if ($_pagoTotal != $_total) {
                                return Response(['alert', 'La suma de pago efectivo y pago con tarjeta debe ser igual al Importe Total']);
                            }
                        } else {
                            //aqui es  donde  debe  verse  el saldo
                            $contCredito = 0;

                            $ventaCliente = DB::table('ventas')
                                ->where('IdCliente', $idCliente)
                                ->where('IdSucursal', $idSucursal)
                                ->where(function ($query) {
                                    $query->whereNull('ventas.MotivoAnulacion')
                                        ->orWhere('ventas.MotivoAnulacion', '=', '');
                                })
                                ->where('ventas.Nota', '!=', 1)
                                ->where('IdTipoPago', 2)
                                ->get();

                            if (count($ventaCliente) >= 1) {
                                foreach ($ventaCliente as $venta) {
                                    $deuda = DB::table('fecha_pago')
                                        ->where('IdVenta', $venta->IdVentas)
                                        ->first();

                                    if ($deuda) {
                                        $contCredito = $contCredito + ($deuda->Importe - $deuda->ImportePagado); //suma el credito dado al cliente
                                    }
                                }
                            }

                            if ($cliente) {
                                if ($cliente->BandSaldo == 1) {
                                    $saldoCredito = $cliente->SaldoCredito - ($contCredito + $req->total);
                                    if ($saldoCredito < 0) {
                                        //return Response(['alert8','El cliente '.$cliente->Nombre.'  con esta  venta sobrepasa su saldo para creditos']);
                                        return Response(['alert', 'El cliente ' . $cliente->Nombre . '  con esta  venta sobrepasa su saldo para creditos, Su Linea de Credito Total es : ' . $cliente->SaldoCredito . ' Su monto usado hasta el momento es de : ' . $contCredito . '. Maximo de credito a  entregar en esta compra es de : ' . ($req->total - abs($saldoCredito))]);
                                    }
                                }
                            }

                            $plazoCredito = $req->plazoCredito;

                            if (!is_numeric($plazoCredito)) {
                                $plazoCredito = 1;
                            }

                            $pagoEfectivo = '';
                            $tipoTarjeta = '';
                            $numTarjeta = '';
                            $pagoTarjeta = '';
                            $montoCuenta = '';

                        }
                        $igv = $req->igv;
                        //$estado = 'Sin Valor Tributario';

                        $loadDatos = new DatosController();
                        $tipoMoneda = $loadDatos->getTipoMonedaSelect($idTipoMoneda);
                        $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
                        if ($caja == null) {
                            //return back()->with('caja','Abrir Caja antes de realizar una venta');
                            return Response(['alert1', 'Abrir Caja antes de realizar una venta']);
                            //echo "<script language='javascript'>alert('Abrir Caja antes de realizar una venta');window.location='../../caja/cierre-caja'</script>";
                        } else {

                            $noReduceStock = 0;
                            $codigoAceptado = '';
                            $bandera = 1;
                            $resumen = '';
                            $hash = '';
                            //$mensaje = 'Se genero Ticket con éxito';
                            //if (intval($idTipoComp) < 3) {
                            $estado = 'Pendiente';
                            if (intval($idTipoComp) == 1) {
                                $idTipoSunat = '03';
                            } else {
                                $idTipoSunat = '01';
                            }
                            /*} else {
                            $estado = 'Sin Valor Tributario';
                            }*/

                            $array = ['IdCliente' => $idCliente, 'IdTipoMoneda' => $idTipoMoneda, 'Idsucursal' => $idSucursal, 'FechaCreacion' => $fechaConvertida, 'IdCreacion' => $idUsuario, 'IdTipoComprobante' => $idTipoComp, 'IdTipoSunat' => $idTipoSunat, 'Placa' => $placa,
                                'IdTipoPago' => $tipoPago, 'PlazoCredito' => $plazoCredito, 'MontoEfectivo' => $pagoEfectivo, 'IdTipoTarjeta' => $tipoTarjeta, 'NumeroTarjeta' => $numTarjeta, 'MontoTarjeta' => $pagoTarjeta, 'MontoCuentaBancaria' => $montoCuenta, 'TipoVenta' => $tipoVenta,
                                'Serie' => $serie, 'Numero' => $numero, 'Observacion' => $observacion, 'Subtotal' => $subtotal, 'Gratuita' => $totalGratuita, 'Exonerada' => $exonerada, 'IGV' => $igv, 'Total' => $total, 'PorcentajeDetraccion' => $valorDetraccion, 'Retencion' => $retencion, 'Resumen' => $resumen, 'Hash' => $hash, 'Anticipo' => $idAnticipo, 'Nota' => 0, 'Guia' => 0, 'CodigoDoc' => $codigoAceptado, 'Estado' => $estado];

                            DB::table('ventas')->insert($array);

                            $venta = DB::table('ventas')
                                ->where('IdCreacion', $idUsuario)
                                ->orderBy('IdVentas', 'desc')
                                ->first();
                            $idVenta = $venta->IdVentas;

                            if ($idPlaca != 0) {
                                $array = ['IdSucursal' => $idSucursal, 'IdVehiculo' => $idPlaca, 'IdCliente' => $idCliente, 'IdReferencia' => $idCliente, 'FechaAtencion' => $fechaConvertida, 'IdVentas' => $idVenta, 'Documento' => $serie . '-' . $numero, 'SubTotal' => $subtotal, 'Exonerada' => $exonerada,
                                    'Igv' => $igv, 'Total' => $total, 'IdOperario' => 0];

                                DB::table('atencion_vehicular')->insert($array);
                            }

                            if ($tipoPago == 2) {
                                $interes = $req->interes;
                                $this->guardarFechasPago($fecha, $plazoCredito, $idVenta, $total, $interes);
                            } else {
                                if (intval($cuentaBancaria) > 0) {
                                    $numeroOp = $req->nroOperacion;
                                    $montoCuenta = $req->MontoCuenta;
                                    $dateBanco = $req->DateBanco;
                                    if ($dateBanco == null || $dateBanco == "") {
                                        $fechaBanco = $fechaConvertida;
                                    } else {
                                        $fechaBanco = Carbon::createFromFormat('d/m/Y', $dateBanco)->format('Y-m-d');
                                    }
                                    if (floatval($montoCuenta) > 0) {
                                        $this->guardaDetallesCuentaBancaria($cuentaBancaria, $montoCuenta, $numeroOp, $fechaBanco, $serie, $numero, $cliente->RazonSocial, $idSucursal);
                                    }
                                }
                            }

                            //if($noReduceStock==0)
                            //{
                            $arrayCaja = ['IdCaja' => $caja->IdCaja, 'IdVentas' => $idVenta];
                            DB::table('caja_ventas')->insert($arrayCaja);
                            //}

                            $cantidadRestada = 0;
                            $cantidadVentaReal = 1; // puse esto para contener si hay algun error
                            $bandTipo = 0;
                            $bandGan = 0; //esto es para controlar la ganancia
                            $condicionDetrac = 0;
                            for ($i = 0; $i < count($req->Id); $i++) {
                                $producto = substr($req->Codigo[$i], 0, 3);
                                $productoSelect = $loadDatos->getProductoSelect($req->Id[$i]);
                                if ($productoSelect->IdTipo == 1) {
                                    if ($req->Tipo[$i] == 1) {
                                        $cantidadVentaReal = 1;
                                        $cantidadKardex = $req->Cantidad[$i];
                                        $cantidadRestada = $productoSelect->Stock - $req->Cantidad[$i];
                                        $bandTipo = 1;
                                        $precio = floatval($req->Precio[$i]);
                                        $costo = floatval($productoSelect->Costo);

                                        if ($productoSelect->TipoOperacion == 2) {
                                            $costo = floatval($costo / 1.18);
                                        }
                                        if ($ventaSolesDolares == 1) {
                                            if ($idTipoMoneda == 1 && $productoSelect->IdTipoMoneda == 2) {
                                                if (floatval($productoSelect->ValorTipoCambio) > 0) {
                                                    $costo = floatval($costo * $productoSelect->ValorTipoCambio);
                                                } else {
                                                    $costo = floatval($costo * $valorCambioVentas);
                                                }
                                            }
                                            if ($idTipoMoneda == 2 && $productoSelect->IdTipoMoneda == 1) {
                                                /*if(floatval($productoSelect->ValorTipoCambio) > 0){
                                                $costo = floatval($costo * $productoSelect->ValorTipoCambio);
                                                }else{*/
                                                $costo = floatval($costo / $valorCambioCompras);
                                                //}
                                            }
                                        }
                                        $bandGan = $precio - $costo;
                                        $newGanancia = floatval($bandGan * $req->Cantidad[$i]) - floatval($req->Descuento[$i]);
                                    } else {

                                        if ($req->Tipo[$i] == 3) {

                                            $cantidadVentaReal = 1;
                                            $cantidadRestada = $productoSelect->Stock - ($req->Cantidad[$i] * 1);
                                            $cantidadKardex = $req->Cantidad[$i];
                                            $precio = floatval($req->Precio[$i]);
                                            $costo = floatval($productoSelect->Costo);

                                            if ($productoSelect->TipoOperacion == 2) {
                                                $costo = floatval($costo / 1.18);
                                            }
                                            if ($ventaSolesDolares == 1) {
                                                if ($idTipoMoneda == 1 && $productoSelect->IdTipoMoneda == 2) {
                                                    if (floatval($productoSelect->ValorTipoCambio) > 0) {
                                                        $costo = floatval($costo * $productoSelect->ValorTipoCambio);
                                                    } else {
                                                        $costo = floatval($costo * $valorCambioVentas);
                                                    }
                                                }
                                                if ($idTipoMoneda == 2 && $productoSelect->IdTipoMoneda == 1) {
                                                    /*if(floatval($productoSelect->ValorTipoCambio) > 0){
                                                    $costo = floatval($costo * $productoSelect->ValorTipoCambio);
                                                    }else{*/
                                                    $costo = floatval($costo / $valorCambioCompras);
                                                    //}
                                                }
                                            }
                                            $newGanancia = (($precio - $costo) * ($req->Cantidad[$i])) - $req->Descuento[$i];
                                            $bandTipo = 3;
                                        } else if ($req->Tipo[$i] == 2) {
                                            //$newCantidad=$req->Cantidad[$i]*$productoSelect->CantidadTipo;
                                            $cantidadKardex = $req->Cantidad[$i] * $productoSelect->CantidadTipo;
                                            $cantidadRestada = $productoSelect->Stock - ($req->Cantidad[$i] * $productoSelect->CantidadTipo);
                                            $cantidadVentaReal = $productoSelect->CantidadTipo;
                                            $importe = $req->Importe[$i];
                                            $costo = floatval($productoSelect->Costo);
                                            if ($productoSelect->TipoOperacion == 2) {
                                                $costo = floatval($costo / 1.18);
                                            }
                                            if ($ventaSolesDolares == 1) {
                                                if ($idTipoMoneda == 1 && $productoSelect->IdTipoMoneda == 2) {
                                                    if (floatval($productoSelect->ValorTipoCambio) > 0) {
                                                        $costo = floatval($costo * $productoSelect->ValorTipoCambio);
                                                    } else {
                                                        $costo = floatval($costo * $valorCambioVentas);
                                                    }
                                                }
                                                if ($idTipoMoneda == 2 && $productoSelect->IdTipoMoneda == 1) {
                                                    /*if(floatval($productoSelect->ValorTipoCambio) > 0){
                                                    $costo = floatval($costo * $productoSelect->ValorTipoCambio);
                                                    }else{*/
                                                    $costo = floatval($costo / $valorCambioCompras);
                                                    //}
                                                }
                                            }
                                            $newGanancia = $importe - ($costo * ($productoSelect->CantidadTipo * $req->Cantidad[$i])) - $req->Descuento[$i];

                                            $bandTipo = 2;
                                        }
                                    }
                                    if ($noReduceStock == 0) {
                                        DB::table('articulo')
                                            ->where('IdArticulo', $req->Id[$i])
                                            ->update(['Stock' => $cantidadRestada]);

                                        $kardex = array(
                                            'CodigoInterno' => $productoSelect->CodigoInterno,
                                            'fecha_movimiento' => $fechaConvertida,
                                            'tipo_movimiento' => 1,
                                            'usuario_movimiento' => $idUsuario,
                                            'documento_movimiento' => $serie . '-' . $numero,
                                            'existencia' => $cantidadRestada,
                                            'costo' => $req->Precio[$i],
                                            'IdArticulo' => $req->Id[$i],
                                            'IdSucursal' => $idSucursal,
                                            'Cantidad' => $cantidadKardex,
                                            'Descuento' => $req->Descuento[$i],
                                            'ImporteEntrada' => 0,
                                            'ImporteSalida' => $req->Importe[$i],
                                            'estado' => 1,
                                        );
                                        DB::table('kardex')->insert($kardex);

                                        $this->actualizarStock($req->Id[$i], $producto, $cantidadKardex);
                                    }
                                } else {
                                    $condicionDetrac = 1;
                                    $costo = floatval($productoSelect->Costo);
                                    if ($ventaSolesDolares == 1) {
                                        if ($idTipoMoneda == 1 && $productoSelect->IdTipoMoneda == 2) {
                                            $costo = floatval($costo * $valorCambioVentas);
                                        }
                                        if ($idTipoMoneda == 2 && $productoSelect->IdTipoMoneda == 1) {
                                            $costo = floatval($costo / $valorCambioCompras);
                                        }
                                    }
                                    //$costo = floatval($productoSelect->Costo);
                                    $newGanancia = ((floatval($req->Precio[$i]) - $costo) * $req->Cantidad[$i]) - $req->Descuento[$i];
                                }

                                $arrayRelacion = ['IdVentas' => $idVenta, 'IdArticulo' => $req->Id[$i], 'Codigo' => $req->Codigo[$i], 'Detalle' => $req->Detalle[$i], 'Descuento' => $req->Descuento[$i], 'Cantidad' => $req->Cantidad[$i], 'CantidadReal' => $cantidadVentaReal, 'VerificaTipo' => $bandTipo, 'Gratuito' => $req->gratuitos[$i], 'Ganancia' => $newGanancia, 'Importe' => $req->Importe[$i], 'TextUnidad' => $req->TextUnida[$i], 'PrecioUnidadReal' => $req->Precio[$i]];
                                DB::table('ventas_articulo')->insert($arrayRelacion);
                                $cantidadVentaReal = 1;
                                $bandTipo = 0;
                                $bandGan = 0;
                                usleep(100000);
                            }

                            if (intval($idTipoComp) < 3) {
                                $opcionFactura = DB::table('usuario')
                                    ->select('OpcionFactura')
                                    ->where('IdUsuario', $idUsuario)
                                    ->first();
                                $config = new config();
                                if ($opcionFactura->OpcionFactura > 0) {
                                    if ($opcionFactura->OpcionFactura == 1) { //sunat
                                        $see = $config->configuracion(SunatEndpoints::FE_BETA);
                                        //$see = $config->configuracion('https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl');
                                    } else if ($opcionFactura->OpcionFactura == 2) { //ose
                                        $see = $config->configuracion('https://demo-ose.nubefact.com/ol-ti-itcpe/billService?wsdl');
                                    } else {
                                        return Response(['error', 'No  tiene Elejida la  ruta de  envio.... comuniquese con  el administrador']);
                                    }
                                } else {
                                    return Response(['error', 'No  tiene Elejida  el tipo de  envio.... comuniquese con  el administrador']);
                                }

                                $anticipoSelect = DB::table('ventas')
                                    ->where('IdVentas', $idAnticipo)
                                    ->first();
                                $docRelacionado = $anticipoSelect->Serie . '-' . $anticipoSelect->Numero;

                                if ($idTipoComp == 1) { //// Boletas

                                    //$fecha = $req->fechaEmitida;
                                    $date = DateTime::createFromFormat('Y-m-d', $fecha);

                                    //$see = $config->configuracion('https://demo-ose.nubefact.com/ol-ti-itcpe/billService?wsdl');
                                    $loadDatos = new DatosController();
                                    $cliente = $loadDatos->getClienteSelect($req->cliente);

                                    $client = new Client();
                                    $client->setTipoDoc(strval($cliente->CodigoSunat)) //agregado
                                        ->setNumDoc($cliente->NumeroDocumento)
                                        ->setRznSocial($cliente->RazonSocial);

                                    // Emisor
                                    $idSucursal = Session::get('idSucursal');
                                    $idUsuario = Session::get('idUsuario');
                                    $sucursal = $loadDatos->getSucursalSelect($idSucursal);

                                    $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
                                    $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);

                                    $address = new Address();
                                    $address->setUbigueo($empresa->Ubigeo)
                                        ->setDepartamento($empresa->Departamento)
                                        ->setProvincia($empresa->Provincia)
                                        ->setDistrito($empresa->Distrito)
                                        ->setUrbanizacion('NONE')
                                        ->setCodLocal($sucursal->CodFiscal)
                                        ->setDireccion($sucursal->DirPrin);
                                    //   ->setDireccion($sucursal->Direccion);

                                    $company = new Company();
                                    $company->setRuc($empresa->Ruc)
                                        ->setRazonSocial($empresa->Nombre)
                                        ->setNombreComercial('NONE')
                                        ->setAddress($address);

                                    $exonerada = 0;

                                    $total = floatval($req->total) - floatval($exonerada);

                                    $mtoOperGravadas = floatval($req->subtotal);
                                    $mtoIGV = floatval($req->igv);
                                    $mtoSubTotal = floatval($total) + floatval($anticipoSelect->Total);
                                    $mtoValorVenta = floatval($subtotal) + floatval($anticipoSelect->Subtotal);

                                    $mtoOperExoneradas = floatval($req->opExonerado);

                                    $invoice = (new Invoice())
                                        ->setUblVersion('2.1')
                                        ->setTipoOperacion('0101') // Catalog. 51
                                        ->setTipoDoc('03')
                                        ->setSerie($req->serie)
                                        ->setCorrelativo($req->numero)
                                        ->setFechaEmision($date)
                                        ->setTipoMoneda($tipoMoneda->CodigoMoneda)
                                        ->setClient($client)
                                        ->setMtoOperGravadas(floatval($mtoOperGravadas)) // Subtotal
                                        ->setMtoOperExoneradas(floatval($mtoOperExoneradas))
                                        ->setMtoOperGratuitas(floatval($subTotalGratuita))
                                        ->setMtoIGVGratuitas(floatval($igvGratuita))
                                        ->setMtoIGV(floatval($mtoIGV))
                                        ->setTotalImpuestos(floatval($mtoIGV))
                                        ->setValorVenta($mtoValorVenta) //->setValorVenta(floatval($req->total))
                                        ->setSubTotal($mtoSubTotal)
                                        ->setMtoImpVenta($total)
                                        ->setCompany($company)
                                        ->setDescuentos([(
                                            new Charge())
                                                ->setCodTipo($codTipo)
                                                ->setMonto($anticipoSelect->Subtotal) // anticipo
                                                ->setMontoBase($anticipoSelect->Subtotal),
                                        ])
                                        ->setAnticipos([
                                            (new Prepayment())
                                                ->setTipoDocRel('03') // catalog. 12
                                                ->setNroDocRel($docRelacionado)
                                                ->setTotal($anticipoSelect->Total),
                                        ])
                                        ->setTotalAnticipos($anticipoSelect->Total); //PrepaidAmount

                                    $array = [];
                                    $res = [];
                                    $legends = [];
                                    $countGratuita = 0;

                                    for ($i = 0; $i < count($req->Id); $i++) {
                                        if ($req->anticipos[$i] == 0) {
                                            $productoSelect = $loadDatos->getProductoSelect($req->Id[$i]);
                                            if ($req->Tipo[$i] == 1 || $req->Tipo[$i] == 3 || $req->Tipo[$i] == 4) {
                                                $newCantidad = $req->Cantidad[$i];
                                            } else if ($req->Tipo[$i] == 2) {
                                                $newCantidad = $req->Cantidad[$i] * $productoSelect->CantidadTipo;
                                            }

                                            //$valorUniDescuento=floatval($req->Importe[$i]/$newCantidad);
                                            $valorUniDescuento = floatval($req->Precio[$i]);
                                            if ($tipoVenta == 1) {
                                                $subTotalItem = floatval($valorUniDescuento / 1.18);
                                                $afectIgv = '10';
                                                $porcentaje = 18;
                                            } else {
                                                $subTotalItem = floatval($valorUniDescuento);
                                                $afectIgv = '20';
                                                $porcentaje = 0;
                                            }

                                            $igvItem = $valorUniDescuento - $subTotalItem;
                                            $mtoValorVenta = floatval($newCantidad * $subTotalItem);
                                            $igvTotal = floatval($newCantidad * $igvItem);
                                            $totalImpuesto = floatval($igvTotal);
                                            $valorGratuito = 0;
                                            if ($req->gratuitos[$i] == 1) {
                                                $valorGratuito = floatval($subTotalItem);
                                                $valorUniDescuento = 0;
                                                $subTotalItem = 0;
                                                $totalImpuesto = 0;
                                                $countGratuita++;
                                                if ($tipoVenta == 1) {
                                                    $afectIgv = '11';
                                                } else {
                                                    $afectIgv = '21';
                                                }
                                            }

                                            $item = (new SaleDetail())
                                                ->setCodProducto($req->Codigo[$i])
                                                ->setUnidad($productoSelect->MedidaSunat)
                                                ->setCantidad($newCantidad)
                                                ->setDescripcion($productoSelect->Descripcion)
                                                ->setMtoBaseIgv(round($mtoValorVenta, 5))
                                                ->setPorcentajeIgv($porcentaje) // 18%
                                                ->setIgv(round($igvTotal, 5))
                                                ->setTipAfeIgv($afectIgv)
                                                ->setTotalImpuestos(round($totalImpuesto, 5))
                                                ->setMtoValorVenta(round($mtoValorVenta, 5))
                                                ->setMtoValorGratuito(round($valorGratuito, 5))
                                                ->setMtoValorUnitario(round($subTotalItem, 5))
                                                ->setMtoPrecioUnitario(round($valorUniDescuento, 5));

                                            if ($idPlaca != 0) {
                                                $item->setAtributos([(new DetailAttribute())
                                                        ->setName('Gastos Art. 37 Renta: Número de Placa')
                                                        ->setCode('7000')
                                                        ->setValue($placa)]);
                                            }

                                            array_push($array, $item);
                                            usleep(100000);
                                        }
                                    }

                                    $convertirLetras = new NumeroALetras();
                                    if ($idTipoMoneda == 1) {
                                        $importeLetras = $convertirLetras->convertir($total, 'soles');
                                    } else {
                                        $importeLetras = $convertirLetras->convertir($total, 'dolares');
                                    }
                                    $legend = (new Legend())
                                        ->setCode('1000')
                                        ->setValue($importeLetras);

                                    array_push($legends, $legend);

                                    if ($countGratuita > 0) {
                                        $legend2 = (new Legend())
                                            ->setCode('1002')
                                            ->setValue('TRANSFERENCIA GRATUITA DE UN BIEN Y/O SERVICIO PRESTADO GRATUITAMENTE');

                                        array_push($legends, $legend2);
                                    }

                                    $invoice->setDetails($array)
                                        ->setLegends($legends);

                                    $xml_string = $see->getXmlSigned($invoice);
                                    //dd($see->getFactory()->getLastXml());
                                    $now = Carbon::now();
                                    $anio = $now->year;
                                    $mes = $now->month;
                                    $_mes = $loadDatos->getMes($mes);
                                    $nombreArchivo = $empresa->Ruc . '-03-' . $req->serie . '-' . $req->numero;
                                    $rutaXml = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/FacturasBoletas/' . $nombreArchivo . '.xml';

                                    $config->writeXml($invoice, $see->getFactory()->getLastXml(), $empresa->Ruc, $anio, $_mes, 1);

                                    $_array = [];
                                    $respuesta = 2;
                                    $doc = new DOMDocument();
                                    $doc->loadXML($xml_string);
                                    $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                                    $date = new DateTime();
                                    $_fecha = $date->format('Y-m-d');
                                    $resumen = $empresa->Ruc . '|03|' . $req->serie . '|' . $req->numero . '|' . round($req->igv, 2) . '|' . round($total, 2) . '|' . $_fecha . '|' . $cliente->CodigoSunat . '|' . $cliente->NumeroDocumento;

                                    DB::table('ventas')
                                        ->where('IdVentas', $idVenta)
                                        ->update(['Resumen' => $resumen, 'Hash' => $hash, 'RutaXml' => $rutaXml]);

                                    array_push($_array, $hash);
                                    array_push($_array, $resumen);
                                    array_push($_array, $respuesta);

                                    $res = $_array;
                                    //----------------------------------------------------------------------------------------------------------------
                                    //return $_array;

                                    //$res = $this->obtenerXMLBoleta($req);
                                    //return $res;

                                }
                                if ($idTipoComp == 2) { //// Facturas

                                    //$fecha = $req->fechaEmitida;
                                    $date = DateTime::createFromFormat('Y-m-d', $fecha);

                                    //$config = new config();
                                    //$see = $config->configuracion('https://demo-ose.nubefact.com/ol-ti-itcpe/billService?wsdl');

                                    $loadDatos = new DatosController();
                                    $cliente = $loadDatos->getClienteSelect($req->cliente);

                                    $client = new Client();
                                    $client->setTipoDoc(strval($cliente->CodigoSunat)) //agregado
                                        ->setNumDoc($cliente->NumeroDocumento)
                                        ->setRznSocial($cliente->RazonSocial);

                                    // Emisor
                                    $idSucursal = Session::get('idSucursal');
                                    $idUsuario = Session::get('idUsuario');
                                    $sucursal = $loadDatos->getSucursalSelect($idSucursal);

                                    $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
                                    $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);

                                    $address = new Address();
                                    $address->setUbigueo($empresa->Ubigeo)
                                        ->setDepartamento($empresa->Departamento)
                                        ->setProvincia($empresa->Provincia)
                                        ->setDistrito($empresa->Distrito)
                                        ->setUrbanizacion('NONE')
                                        ->setCodLocal($sucursal->CodFiscal)
                                        ->setDireccion($sucursal->DirPrin);
                                    //   ->setDireccion($sucursal->Direccion);

                                    $company = new Company();
                                    $company->setRuc($empresa->Ruc)
                                        ->setRazonSocial($empresa->Nombre)
                                        ->setNombreComercial('NONE')
                                        ->setAddress($address);

                                    $exonerada = 0;

                                    $total = floatval($req->total) - floatval($exonerada);

                                    $mtoOperGravadas = floatval($req->subtotal);
                                    $mtoIGV = floatval($req->igv);
                                    $mtoSubTotal = floatval($total) + floatval($anticipoSelect->Total);
                                    $mtoValorVenta = floatval($subtotal) + floatval($anticipoSelect->Subtotal);

                                    $mtoOperExoneradas = floatval($req->opExonerado);
                                    //$totalDif = floatval($total) - floatval($anticipoSelect->Total);

                                    $invoice = (new Invoice())
                                        ->setUblVersion('2.1')
                                        ->setTipoOperacion('0101') // Catalog. 51
                                        ->setTipoDoc('01')
                                        ->setSerie($req->serie)
                                        ->setCorrelativo($req->numero)
                                        ->setFechaEmision($date)
                                        ->setTipoMoneda($tipoMoneda->CodigoMoneda)
                                        ->setClient($client)
                                        ->setMtoOperGravadas(floatval($mtoOperGravadas)) // Subtotal
                                        ->setMtoOperExoneradas(floatval($mtoOperExoneradas))
                                        ->setMtoOperGratuitas(floatval($subTotalGratuita))
                                        ->setMtoIGVGratuitas(floatval($igvGratuita))
                                        ->setMtoIGV(floatval($mtoIGV))
                                        ->setTotalImpuestos(floatval($mtoIGV))
                                        ->setValorVenta($mtoValorVenta) //->setValorVenta(floatval($req->total))
                                        ->setSubTotal($mtoSubTotal)
                                        ->setMtoImpVenta($total)
                                        ->setCompany($company)
                                        ->setDescuentos([(
                                            new Charge())
                                                ->setCodTipo($codTipo)
                                                ->setMonto($anticipoSelect->Subtotal) // anticipo
                                                ->setMontoBase($anticipoSelect->Subtotal),
                                        ])
                                        ->setAnticipos([
                                            (new Prepayment())
                                                ->setTipoDocRel('02') // catalog. 12
                                                ->setNroDocRel($docRelacionado)
                                                ->setTotal($anticipoSelect->Total),
                                        ])
                                        ->setTotalAnticipos($anticipoSelect->Total); //PrepaidAmount

                                    $array = [];
                                    $res = [];
                                    $legends = [];
                                    $countGratuita = 0;

                                    for ($i = 0; $i < count($req->Id); $i++) {
                                        if ($req->anticipos[$i] == 0) {
                                            $productoSelect = $loadDatos->getProductoSelect($req->Id[$i]);
                                            if ($req->Tipo[$i] == 1 || $req->Tipo[$i] == 3 || $req->Tipo[$i] == 4) {
                                                $newCantidad = $req->Cantidad[$i];
                                            } else if ($req->Tipo[$i] == 2) {
                                                $newCantidad = $req->Cantidad[$i] * $productoSelect->CantidadTipo;
                                            }

                                            $valorUniDescuento = floatval(round($req->Importe[$i] / $newCantidad, 2));
                                            //$precioUni = floatval($req->Precio[$i]);
                                            if ($tipoVenta == 1) {
                                                $subTotalItem = floatval($valorUniDescuento / 1.18);
                                                $afectIgv = '10';
                                                $porcentaje = 18;
                                            } else {
                                                $subTotalItem = floatval($valorUniDescuento);
                                                $afectIgv = '20';
                                                $porcentaje = 0;
                                            }

                                            $igvItem = $valorUniDescuento - $subTotalItem;
                                            $mtoValorVenta = floatval($newCantidad * $subTotalItem);
                                            $igvTotal = floatval($newCantidad * $igvItem);
                                            $totalImpuesto = floatval($igvTotal);
                                            $valorGratuito = 0;
                                            if ($req->gratuitos[$i] == 1) {
                                                $valorGratuito = floatval($subTotalItem);
                                                $valorUniDescuento = 0;
                                                $subTotalItem = 0;
                                                $totalImpuesto = 0;
                                                $countGratuita++;
                                                if ($tipoVenta == 1) {
                                                    $afectIgv = '11';
                                                } else {
                                                    $afectIgv = '21';
                                                }
                                            }

                                            $item = (new SaleDetail())
                                                ->setCodProducto($req->Codigo[$i])
                                                ->setUnidad($productoSelect->MedidaSunat)
                                                ->setCantidad($newCantidad)
                                                ->setDescripcion($productoSelect->Descripcion)
                                                ->setMtoBaseIgv(round($mtoValorVenta, 5))
                                                ->setPorcentajeIgv($porcentaje) // 18%
                                                ->setIgv(round($igvTotal, 5))
                                                ->setTipAfeIgv($afectIgv)
                                                ->setTotalImpuestos(round($totalImpuesto, 5))
                                                ->setMtoValorVenta(round($mtoValorVenta, 5))
                                                ->setMtoValorGratuito(round($valorGratuito, 5))
                                                ->setMtoValorUnitario(round($subTotalItem, 5))
                                                ->setMtoPrecioUnitario(round($valorUniDescuento, 5));

                                            if ($idPlaca != 0) {
                                                $item->setAtributos([(new DetailAttribute())
                                                        ->setName('Gastos Art. 37 Renta: Número de Placa')
                                                        ->setCode('7000')
                                                        ->setValue($placa)]);
                                            }

                                            array_push($array, $item);
                                            usleep(100000);
                                        }
                                    }

                                    if ($tipoPago == 1) {
                                        $invoice->setFormaPago(new FormaPagoContado());
                                        if ($retencion == 1) {
                                            $montoRetencion = floatval($total * 0.03);
                                            $invoice->setDescuentos([
                                                (new Charge())
                                                    ->setCodTipo('62') // Catalog. 53
                                                    ->setMontoBase($total)
                                                    ->setFactor(0.03) // 3%
                                                    ->setMonto(round($montoRetencion, 2)),
                                            ]);
                                        }
                                    } else {
                                        if ($condicionDetrac == 1 && floatval($totalDetrac) >= 700 && $tipoVenta == 1) {
                                            $totalCredito = floatval($total) - floatval($total * $valorDetraccion / 100);
                                        } else {
                                            if ($retencion == 1) {
                                                $montoRetencion = floatval($total * 0.03);
                                                $totalCredito = floatval($total) - floatval($montoRetencion);
                                                $invoice->setDescuentos([
                                                    (new Charge())
                                                        ->setCodTipo('62') // Catalog. 53
                                                        ->setMontoBase($total)
                                                        ->setFactor(0.03) // 3%
                                                        ->setMonto(round($montoRetencion, 2)),
                                                ]);
                                            } else {
                                                $totalCredito = floatval($total);
                                            }
                                        }
                                        $_date = Carbon::today();
                                        $fechaPago = $_date->addDays($plazoCredito);

                                        $invoice->setFormaPago(new FormaPagoCredito(round($totalCredito, 2)));
                                        $invoice->setCuotas([
                                            (new Cuota())
                                                ->setMonto(round($totalCredito, 2))
                                                ->setFechaPago(new DateTime($fechaPago)),
                                        ]);
                                    }

                                    $convertirLetras = new NumeroALetras();
                                    if ($idTipoMoneda == 1) {
                                        $importeLetras = $convertirLetras->convertir($total, 'soles');
                                    } else {
                                        $importeLetras = $convertirLetras->convertir($total, 'dolares');
                                    }
                                    $legend = (new Legend())
                                        ->setCode('1000')
                                        ->setValue($importeLetras);

                                    array_push($legends, $legend);

                                    if ($countGratuita > 0) {
                                        $legend2 = (new Legend())
                                            ->setCode('1002')
                                            ->setValue('TRANSFERENCIA GRATUITA DE UN BIEN Y/O SERVICIO PRESTADO GRATUITAMENTE');

                                        array_push($legends, $legend2);
                                    }

                                    $invoice->setDetails($array)
                                        ->setLegends($legends);

                                    //$see->getXmlSigned($invoice);
                                    //dd($see->getFactory()->getLastXml());
                                    $xml_string = $see->getXmlSigned($invoice);
                                    $doc = new DOMDocument();
                                    $doc->loadXML($xml_string);
                                    $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                                    $date = new DateTime();
                                    $_fecha = $date->format('Y-m-d');
                                    $resumen = $empresa->Ruc . '|01|' . $req->serie . '|' . $req->numero . '|' . round($req->igv, 2) . '|' . round($total, 2) . '|' . $_fecha . '|' . $cliente->CodigoSunat . '|' . $cliente->NumeroDocumento;
                                    $now = Carbon::now();
                                    $anio = $now->year;
                                    $mes = $now->month;
                                    $_mes = $loadDatos->getMes($mes);
                                    $nombreArchivo = $empresa->Ruc . '-01-' . $req->serie . '-' . $req->numero;
                                    $rutaXml = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/FacturasBoletas/' . $nombreArchivo . '.xml';
                                    DB::table('ventas')
                                        ->where('IdVentas', $idVenta)
                                        ->update(['Resumen' => $resumen, 'Hash' => $hash, 'RutaXml' => $rutaXml]);

                                    $config->writeXml($invoice, $see->getFactory()->getLastXml(), $empresa->Ruc, $anio, $_mes, 1);
                                    $result = $see->send($invoice);

                                    //---------------------------------------------------------------------------------------------------------------------------------------------------------------

                                    //-------------------

                                    if ($result->isSuccess()) {
                                        //$config->writeXml($invoice, $see->getFactory()->getLastXml());
                                        $rutaCdr = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/FacturasBoletas/R-' . $nombreArchivo . '.zip';
                                        DB::table('ventas')
                                            ->where('IdVentas', $idVenta)
                                            ->update(['RutaCdr' => $rutaCdr]);
                                        $cdr = $result->getCdrResponse();
                                        $config->writeCdr($invoice, $result->getCdrZip(), $empresa->Ruc, $anio, $_mes, 1);
                                        $config->showResponse($invoice, $cdr);

                                        $_array = [];
                                        $respuesta = 1;

                                        $isAccetedCDR = $result->getCdrResponse()->isAccepted();
                                        $descripcionCDR = $result->getCdrResponse()->getDescription();
                                        $codeCDR = $result->getCdrResponse()->getCode();

                                        $ver = $codeCDR . '-' . $descripcionCDR . '-' . $isAccetedCDR; //getCdrResponse()->getDescription();  //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                                        //$xml_string = $see->getXmlSigned($invoice);
                                        /*$doc = new DOMDocument();
                                        $doc->loadXML($xml_string);
                                        $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                                        $date = new DateTime();
                                        $fecha = $date->format('Y-m-d');
                                        $resumen = $empresa->Ruc.'|01|'.$req->serie.'|'.$req->numero.'|'.round($req->igv, 2).'|'.round($total, 2).'|'.$fecha.'|'.$cliente->CodigoSunat.'|'.$cliente->NumeroDocumento;*/
                                        array_push($_array, $hash);
                                        array_push($_array, $resumen);
                                        array_push($_array, $respuesta);
                                        array_push($_array, $codeCDR); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                                        array_push($_array, $descripcionCDR); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                                        array_push($_array, $isAccetedCDR); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                                        // return $_array;
                                    } else {
                                        //dd($result);
                                        $_array = [];
                                        if ($result->getError()->getCode() == 'HTTP' || $result->getError()->getCode() == 'HTTPS') {
                                            //echo "<script language='javascript'>alert('Servicio inestable, intentelo en otro momento');</script>";
                                            $respuesta = 2;

                                            $codeOp = -1;
                                            $descripOp = "";
                                            $accepOp = -1;

                                            /*$doc = new DOMDocument();
                                            $doc->loadXML($xml_string);
                                            $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                                            $date = new DateTime();
                                            $fecha = $date->format('Y-m-d');
                                            $resumen = $empresa->Ruc.'|01|'.$req->serie.'|'.$req->numero.'|'.round($req->igv, 2).'|'.round($total, 2).'|'.$fecha.'|'.$cliente->CodigoSunat.'|'.$cliente->NumeroDocumento;*/
                                            array_push($_array, $hash);
                                            array_push($_array, $resumen);
                                            array_push($_array, $respuesta);
                                            array_push($_array, $codeOp);
                                            array_push($_array, $descripOp);
                                            array_push($_array, $accepOp);
                                            //return Response(['alert3','Por favor, elegir ClienteJodertio']);
                                        } else {
                                            //echo '<script language="javascript">alert("'.$result->getError()->getMessage().'");</script>';
                                            $respuesta = 1;
                                            /*$doc = new DOMDocument();
                                            $doc->loadXML($xml_string);
                                            $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                                            $date = new DateTime();
                                            $fecha = $date->format('Y-m-d');*/

                                            $descripcionError = $result->getError()->getMessage();
                                            $codeError = -1;
                                            $isAccetedError = -1;

                                            //$ver=$descripcionError.'-'.$codeError;  $result->getError();//borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                                            //$resumen = $empresa->Ruc.'|01|'.$req->serie.'|'.$req->numero.'|'.round($req->igv, 2).'|'.round($total, 2).'|'.$fecha.'|'.$cliente->CodigoSunat.'|'.$cliente->NumeroDocumento;
                                            //-----  return Response(['verificar','error '.$result->getError()->getCode().' verificara la valides de este Documento', $TmpidVenta]);

                                            array_push($_array, $hash);
                                            array_push($_array, $resumen);
                                            array_push($_array, $respuesta);
                                            array_push($_array, $codeError); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                                            array_push($_array, $descripcionError); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                                            array_push($_array, $isAccetedError); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                                        }
                                    }

                                    $res = $_array;
                                }

                                /*$hash = $res[0];
                                $resumen = $res[1];
                                DB::table('ventas')
                                ->where('IdVentas',$idVenta)
                                ->update(['Resumen' => $resumen, 'Hash' => $hash]);*/

                                if ($res[2] == 0) {
                                    $bandera = 0;
                                    $estado = 'Pendiente';
                                    $mensaje = $res[1];
                                } else {
                                    if ($res[2] == 1) { //es  enviado y recibido......

                                        if (intval($res[3]) == 0) {
                                            $codigoAceptado = $res[3];
                                            $mensaje = $res[4];
                                            if (is_numeric($codigoAceptado)) {
                                                $estado = 'Aceptado';
                                            } else {
                                                $estado = 'Pendiente';
                                            }
                                        } else if (intval($res[3]) >= 100 && intval($res[3]) <= 1999) {
                                            $bandera = 0;
                                            $codigoAceptado = $res[3];
                                            $estado = 'Pendiente';
                                            $mensaje = $res[5] . '-' . $res[4] . '-' . $res[3];

                                        } else if (intval($res[3]) >= 2000 && intval($res[3]) <= 3999) {
                                            $noReduceStock = 1;
                                            $codigoAceptado = $res[3];
                                            $estado = 'Rechazo';
                                            $mensaje = $res[4];
                                        } else if (intval($res[3]) >= 4000) {
                                            $codigoAceptado = $res[3];
                                            $estado = 'Observado';
                                            $mensaje = $res[4]; //'La Factura '.$serie.'-'.$numero.', Ha sido Aceptado';
                                        } else {
                                            $codigoAceptado = $res[3];
                                            $estado = 'Pendiente';
                                            $mensaje = 'Se generó Factura pero no se pudo enviar a Sunat ';
                                        }

                                    }
                                    if ($res[2] == 2) {
                                        $estado = 'Pendiente';
                                        $codigoAceptado = '-';
                                        if (intval($idTipoComp) == 1) {
                                            $mensaje = 'Se generó Boleta y se guardo con éxito'; //$res[1];
                                        } else {
                                            $mensaje = 'Se generó Factura pero no se pudo enviar a Sunat';
                                        }
                                    }
                                }
                            }

                            if ($bandera == 1) {

                                DB::table('ventas')
                                    ->where('IdVentas', $idAnticipo)
                                    ->update(['Anticipo' => 2]);

                                if (intval($idTipoComp) == 2) {
                                    DB::table('ventas')
                                        ->where('IdVentas', $idVenta)
                                        ->update(['CodigoDoc' => $codigoAceptado, 'Estado' => $estado]);

                                    if ($estado == 'Rechazo') {
                                        DB::table('ventas')
                                            ->where('IdVentas', $idAnticipo)
                                            ->update(['Anticipo' => 1]);
                                        $itemasVentaSelect = $loadDatos->getItemsVentas($idVenta);
                                        for ($i = 0; $i < count($itemasVentaSelect); $i++) {
                                            $productoSelect = $loadDatos->getProductoSelect($itemasVentaSelect[$i]->IdArticulo);
                                            $stockSelect = $loadDatos->getProductoStockSelect($itemasVentaSelect[$i]->IdArticulo);
                                            if ($productoSelect->IdTipo == 1) {
                                                if ($itemasVentaSelect[$i]->VerificaTipo != 1) {
                                                    $newCantidad = floatval($itemasVentaSelect[$i]->Cantidad * $itemasVentaSelect[$i]->CantidadReal);
                                                } else {
                                                    $newCantidad = floatval($itemasVentaSelect[$i]->Cantidad);
                                                }

                                                DB::table('articulo')
                                                    ->where('IdArticulo', $productoSelect->IdArticulo)
                                                    ->increment('Stock', $newCantidad);

                                                DB::table('stock')
                                                    ->where('IdStock', $stockSelect[0]->IdStock)
                                                    ->increment('Cantidad', $newCantidad);

                                                $kardex = array(
                                                    'CodigoInterno' => $productoSelect->CodigoInterno,
                                                    'fecha_movimiento' => $fechaConvertida,
                                                    'tipo_movimiento' => 17,
                                                    'usuario_movimiento' => $idUsuario,
                                                    'documento_movimiento' => $serie . '-' . $numero,
                                                    'existencia' => $newCantidad,
                                                    'costo' => floatval($itemasVentaSelect[$i]->PrecioUnidadReal),
                                                    'IdArticulo' => $productoSelect->IdArticulo,
                                                    'IdSucursal' => $idSucursal,
                                                    'Cantidad' => floatval($itemasVentaSelect[$i]->Cantidad),
                                                    'Descuento' => floatval($itemasVentaSelect[$i]->Descuento),
                                                    'ImporteEntrada' => floatval($itemasVentaSelect[$i]->Importe),
                                                    'ImporteSalida' => 0,
                                                    'estado' => 1,
                                                );
                                                DB::table('kardex')->insert($kardex);
                                            }
                                        }
                                    }
                                }

                                return Response(['succes', $mensaje, $idVenta]);

                            } else {
                                DB::table('ventas')
                                    ->where('IdVentas', $idVenta)
                                    ->update(['CodigoDoc' => $codigoAceptado, 'Estado' => $estado]);
                                return Response(['success', $mensaje, $idVenta]);
                            }
                        }
                    }
                }
            } else {
                Session::flush();
                return redirect('/')->with('out', 'Sesión de usuario Expirado');
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function guardaDetallesCuentaBancaria($cuentaBancaria, $montoCuentaBanc, $numeroOp, $fechaBanco, $serie, $numero, $razonSocial, $idSucursal)
    {
        $loadDatos = new DatosController();
        $montoCuenta = $loadDatos->getCuentaCorrienteSelect($cuentaBancaria);
        $montoActual = floatval($montoCuenta->MontoActual) + floatval($montoCuentaBanc);
        //$fechaHoy = $loadDatos->getDateTime();
        $arrayDatos = ['FechaPago' => $fechaBanco, 'IdBanco' => $cuentaBancaria, 'NumeroOperacion' => $numeroOp, 'Detalle' => $serie . '-' . $numero . ' (' . $razonSocial . ')', 'TipoMovimiento' => 'Ventas', 'Entrada' => $montoCuentaBanc, 'Salida' => '0', 'MontoActual' => $montoActual, 'IdSucursal' => $idSucursal];
        DB::table('banco_detalles')->insert($arrayDatos);

        DB::table('banco')->where('IdBanco', $cuentaBancaria)->update(['MontoActual' => $montoActual]);
    }

    public function guardarFechasPago($fecha, $plazoCredito, $idVenta, $total, $interes)
    {
        $fechaInicio = DateTime::createFromFormat('Y-m-d', $fecha);
        $fechaConvertidaInicio = $fechaInicio->format("Y-m-d");

        $plazoInteresTotal = $total + (($total / 100) * $interes);

        $fechaConvertidaFinal = strtotime('+' . $plazoCredito . ' day', strtotime($fechaConvertidaInicio));
        $fechaConvertidaFinal = date('Y-m-d', $fechaConvertidaFinal);
        $array = ['IdVenta' => $idVenta, 'FechaInicio' => $fechaConvertidaInicio, 'FechaUltimo' => $fechaConvertidaFinal, 'Importe' => $plazoInteresTotal, 'ImportePagado' => 0.00, 'DiasPasados' => 0, 'Estado' => 1];
        DB::table('fecha_pago')->insert($array);
    }

    public function verificarCodigo($serie, $numero, $idSucursal)
    {
        try {
            $resultado = DB::table('ventas')
                ->select(DB::raw("count(IdVentas) as Cantidad"))
                ->where('Serie', $serie)
                ->where('Numero', $numero)
                ->where('IdSucursal', $idSucursal)
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function verificarStockSuficiente($req)
    {
        $loadDatos = new DatosController();
        $array = [];
        for ($i = 0; $i < count($req->Id); $i++) {
            $sumador = 0;
            $cantidadTotal = 0;
            $producto = substr($req->Codigo[$i], 0, 3);
            if ($producto == 'PRO') {
                $productoSelect = $loadDatos->getProductoSelect($req->Id[$i]);
                //for($k=0; $k<count($req->Id); $k++){
                //if($req->Id[$i] == $req->Id[$k]){
                if ($req->Tipo[$i] == 1 || $req->Tipo[$i] == 3) {
                    $cantidadTotal = $req->Cantidad[$i];
                } else if ($req->Tipo[$i] == 2) {
                    $cantidadTotal = $req->Cantidad[$i] * $productoSelect->CantidadTipo;
                }
                //}
                //}
                if ($cantidadTotal > $productoSelect->Stock) {
                    array_push($array, $productoSelect->Descripcion);
                    array_push($array, $productoSelect->Stock);
                    return $array;
                }
            }
        }
        return $array;
    }

    public function actualizarStock($Id, $producto, $Cantidad)
    {
        $loadDatos = new DatosController();
        //$arrayGanancias = [];
        //for($i=0; $i<count($req->Id); $i++){
        //$producto = substr($req->Codigo[$i],0,3);
        //$ganancia = 0;
        //if($producto == 'PRO'){
        $productoSelect = $loadDatos->getProductoStockSelect($Id);

        if (count($productoSelect) >= 1) { //evitar el no encontrar y el cero { { {
            if ($Cantidad > $productoSelect[0]->Cantidad) {
                //$ganancia += (int) $productoSelect[0]->Cantidad * ( (float) $productoSelect[0]->Precio - (float) $productoSelect[0]->Costo);
                $resto = (float) $Cantidad - (float) $productoSelect[0]->Cantidad;
                DB::table('stock')
                    ->where('IdStock', $productoSelect[0]->IdStock)
                    ->update(['Cantidad' => 0]);
                if ($resto > $productoSelect[1]->Cantidad) {
                    //$ganancia += $productoSelect[1]->Cantidad * ( (float) $productoSelect[1]->Precio - (float) $productoSelect[1]->Costo);
                    $resto = $resto - (float) $productoSelect[1]->Cantidad;
                    DB::table('stock')
                        ->where('IdStock', $productoSelect[1]->IdStock)
                        ->update(['Cantidad' => 0]);
                    if ($resto > $productoSelect[2]->Cantidad) {
                        //$ganancia += $productoSelect[2]->Cantidad * ( (float) $productoSelect[2]->Precio - (float) $productoSelect[2]->Costo);
                        $dif = (float) $productoSelect[2]->Cantidad - (float) $Cantidad;
                        DB::table('stock')
                            ->where('IdStock', $productoSelect[0]->IdStock)
                            ->update(['Cantidad' => $dif]);
                    } else {
                        //$ganancia += $resto * ( (float) $productoSelect[2]->Precio - (float) $productoSelect[2]->Costo);
                        //$dif = (int) $productoSelect[2]->Cantidad - $resto;
                        DB::table('stock')
                            ->where('IdStock', $productoSelect[2]->IdStock)
                            ->decrement('Cantidad', $resto);
                    }

                } else {
                    //$ganancia += $resto * ( (float) $productoSelect[1]->Precio - (float) $productoSelect[1]->Costo);
                    //$dif = (int) $productoSelect[1]->Cantidad - $resto;
                    DB::table('stock')
                        ->where('IdStock', $productoSelect[1]->IdStock)
                        ->decrement('Cantidad', $resto);
                }
            } else {
                //$ganancia += $Cantidad * ( (float) $productoSelect[0]->Precio - (float) $productoSelect[0]->Costo);
                //$dif = (int) $productoSelect[0]->Cantidad - (int) $Cantidad;
                DB::table('stock')
                    ->where('IdStock', $productoSelect[0]->IdStock)
                    ->decrement('Cantidad', $Cantidad);
            }
        }

    }

    public function completarCeros($numero)
    {
        $numeroConCeros = str_pad($numero, 8, "0", STR_PAD_LEFT);
        return $numeroConCeros;
    }

    public function correlativoActual($idUsuario, $idSucursal, $tipoDoc)
    {
        try {
            $resultado = DB::table('ventas')
                ->where('IdCreacion', $idUsuario)
                ->where('IdSucursal', $idSucursal)
                ->where('IdTipoComprobante', $tipoDoc)
                ->orderBy('IdVentas', 'desc')
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

}
