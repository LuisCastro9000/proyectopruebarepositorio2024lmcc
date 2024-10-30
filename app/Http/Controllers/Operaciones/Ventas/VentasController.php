<?php

namespace App\Http\Controllers\Operaciones\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use App\Http\Controllers\Operaciones\NumeroALetras;
use App\Http\Controllers\Servicios\config;
use App\Traits\ArchivosS3Trait;
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
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Detraction;
use Greenter\Ws\Services\ConsultCdrService;
use Greenter\Ws\Services\SoapClient;
use Greenter\Ws\Services\SunatEndpoints;
use Illuminate\Http\Request;
use PDF;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Session;
use Storage;
use Sunat\Sunat;

class VentasController extends Controller
{
    use ArchivosS3Trait;
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        $loadDatos = new DatosController();

        $idSucursal = Session::get('idSucursal');
        $text = "";
        $totalVentas = $loadDatos->getTotalVentas($idSucursal, $idUsuario);
        //$clientes = $loadDatos->getClientes($idSucursal);
        $tipoMonedas = $loadDatos->getTipoMoneda();

        $cod_cliente = DB::table('sucursal')
            ->select('CodigoCliente')
            ->where('IdSucursal', $idSucursal)
            ->first();

        $sucPrincipal = DB::table('sucursal')
            ->select('IdSucursal')
            ->where('CodigoCliente', $cod_cliente->CodigoCliente)
            ->where('Principal', 1)
            ->first();

        if ($sucPrincipal->IdSucursal == $idSucursal) {
            $productos = $loadDatos->getProductosPagination($idSucursal, $text, 1, 0);
            $productosDolares = $loadDatos->getProductosPagination($idSucursal, $text, 2, 0);
        } else {
            $productos = $loadDatos->paginarAjaxProdSucursal($idSucursal, $text, 1, 0);
            $productosDolares = $loadDatos->paginarAjaxProdSucursal($idSucursal, $text, 2, 0);
        }

        $bienesServicios = $loadDatos->getBienesServicios();
        $medioPagos = $loadDatos->getMedioPagos();
        $servicios = $loadDatos->getServiciosPagination($idSucursal, 1, $text);
        $serviciosDolares = $loadDatos->getServiciosPagination($idSucursal, 2, $text);
        $ventasTicket = $loadDatos->getVentas($idSucursal, $idUsuario, 3);
        $ventasFactura = $loadDatos->getVentas($idSucursal, $idUsuario, 2);
        $ventasBoleta = $loadDatos->getVentas($idSucursal, $idUsuario, 1);
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $cuentasSoles = $loadDatos->getCuentasCorrientes($cod_cliente->CodigoCliente, 1)->whereNotIn('IdListaBanco', ['9']);;
        $cuentasDolares = $loadDatos->getCuentasCorrientes($cod_cliente->CodigoCliente, 2)->whereNotIn('IdListaBanco', ['9']);;

        $fecha = date("d/m/Y");
        $tipoComprobante = $loadDatos->getTipoComprobante();
        $tipoDoc = $loadDatos->TipoDocumento();
        $departamentos = $loadDatos->getDepartamentos();
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $orden = $usuarioSelect->Orden;
        $sucursal = $loadDatos->getSucursalSelect($idSucursal);
        $ordenSucursal = $sucursal->Orden;
        $sucExonerado = $sucursal->Exonerado;
        $editarPrecio = $usuarioSelect->EditarPrecio;

        $categorias = $loadDatos->getCategorias($usuarioSelect->CodigoCliente);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $ventaRapida = $datosEmpresa->VentaRapida;
        $opcionAnticipo = $datosEmpresa->Anticipos;

        $exonerado = $datosEmpresa->Exonerado;

        $bandVentaSolesDolares = $datosEmpresa->VentaSolesDolares;

        $idRubro = $datosEmpresa->IdRubro;
        $arrayAnio = [];
        $marcaVehiculo = [];
        $modeloVehiculo = [];
        $tipoVehiculo = [];
        if ($idRubro == 11) {
            $marcaVehiculo = $this->Marca($idSucursal);
            $modeloVehiculo = $this->Modelo($idSucursal);
            $tipoVehiculo = $this->Tipo($idSucursal);

            $date = new DateTime();
            $anio = intval($date->format("Y"));
            for ($i = 0; $i < 50; $i++) {
                array_push($arrayAnio, $anio - $i);
            }
        }
        //dd($arrayAnio);
        //$clientesFact = [];
        $clientesTick = [];
        $clientesBol = [];
        $serie = '';
        $numero = '';
        if ($ventaRapida == 1) {
            $clientesTick = $loadDatos->getTipoClientes(3, $idSucursal);

            $numeroDB = $this->correlativoActual($idUsuario, $idSucursal, 3);

            if ($numeroDB) {
                $numero = str_pad($numeroDB->Numero + 1, 8, "0", STR_PAD_LEFT);
            } else {
                $numero = str_pad(1, 8, "0", STR_PAD_LEFT);
            }
            $serieCeros = str_pad($orden, 2, "0", STR_PAD_LEFT);
            $serie = 'T' . $ordenSucursal . '' . $serieCeros;
        }
        if ($ventaRapida == 2) {
            $clientesBol = $loadDatos->getTipoClientes(1, $idSucursal);
            $numeroDB = $this->correlativoActual($idUsuario, $idSucursal, 1);

            if ($numeroDB) {
                $numero = str_pad($numeroDB->Numero + 1, 8, "0", STR_PAD_LEFT);
            } else {
                $numero = str_pad(1, 8, "0", STR_PAD_LEFT);
            }
            $serieCeros = str_pad($orden, 2, "0", STR_PAD_LEFT);
            $serie = 'B' . $ordenSucursal . '' . $serieCeros;
        }

        // ========
        $cuentaDetraccion = $loadDatos->getCuentaDetracciones($usuarioSelect->CodigoCliente, 9);
        // ========

        $array = ['tipoMoneda' => $tipoMonedas, 'cuentasSoles' => $cuentasSoles, 'cuentasDolares' => $cuentasDolares, 'clientesTickets' => $clientesTick, 'clientesBoletas' => $clientesBol, 'sucExonerado' => $sucExonerado, 'tipoComprobante' => $tipoComprobante, 'pagoEfectivo' => '', 'totalVentas' => $totalVentas, 'departamentos' => $departamentos, 'fecha' => $fecha, 'ventaRapida' => $ventaRapida, 'exonerado' => $exonerado, 'editarPrecio' => $editarPrecio, 'serie' => $serie, 'numero' => $numero, 'bandVentaSolesDolares' => $bandVentaSolesDolares, 'idRubro' => $idRubro, 'opcionAnticipo' => $opcionAnticipo, 'cuentaDetraccion' => $cuentaDetraccion,
            'categorias' => $categorias, 'arrayAnio' => $arrayAnio, 'marcas' => $marcaVehiculo, 'modelos' => $modeloVehiculo, 'tipos' => $tipoVehiculo, 'bienesServicios' => $bienesServicios, 'medioPagos' => $medioPagos, 'ventasTicket' => $ventasTicket, 'ventasFactura' => $ventasFactura, 'ventasBoleta' => $ventasBoleta, 'productos' => $productos, 'productosDolares' => $productosDolares, 'servicios' => $servicios, 'serviciosDolares' => $serviciosDolares, 'tipoDoc' => $tipoDoc, 'permisos' => $permisos, 'idSucursal' => $idSucursal, 'modulosSelect' => $modulosSelect, 'orden' => $orden, 'ordenSucursal' => $ordenSucursal, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'usuarioSelect' => $usuarioSelect];
        return view('operaciones/ventas/ventas/crearVenta', $array);
    }

    public function show()
    {
        if ($req->ajax()) {
            $idDoc = $req->idDoc;
            $numDoc = $req->numDoc;
            $datos = new Sunat();
            if ($idDoc == 3) {
                return Response(array(0 => $numDoc));
            }
            $search = $datos->search($numDoc);
            if ($search->success == true) {
                if ($idDoc == 1) {
                    $data = array(
                        0 => $search->result->razon_social,
                    );
                }
                if ($idDoc == 2) {
                    $data = array(
                        0 => $search->result->razon_social,
                        1 => $search->result->nombre_comercial,
                        2 => $search->result->direccion,
                    );
                }

                return Response($data);
            } else {
                return Response(array());
            }

        }
    }

    public function create()
    {
        /*$loadDatos = new DatosController();
    $idSucursal = Session::get('idSucursal');
    $idUsuario = Session::get('idUsuario');
    $clientes = $loadDatos->getClientes($idSucursal);
    $tipoMonedas = $loadDatos->getTipoMoneda();
    $clientesBol = $loadDatos->getTipoClientes(1,$idSucursal);
    $clientesFact = $loadDatos->getTipoClientes(2,$idSucursal);
    $clientesTick = $loadDatos->getTipoClientes(3,$idSucursal);
    $productos = $loadDatos->getProductosPagination($idSucursal);
    $servicios = $loadDatos->getServiciosPagination($idSucursal);
    $ventasTicket = $loadDatos->getVentas($idSucursal, 3);
    $ventasFactura = $loadDatos->getVentas($idSucursal, 2);
    $ventasBoleta = $loadDatos->getVentas($idSucursal, 1);
    $permisos = $loadDatos->getPermisos($idUsuario);
    $tipoComprobante = $loadDatos->getTipoComprobante();
    $tipoDoc= $loadDatos->TipoDocumento();
    $array = ['clientes' => $clientes, 'tipoMoneda' => $tipoMonedas, 'clientesTickets' => $clientesTick, 'clientesBoletas' => $clientesBol, 'clientesFacturas' => $clientesFact, 'tipoComprobante' => $tipoComprobante,
    'ventasTicket' => $ventasTicket, 'ventasFactura' => $ventasFactura, 'ventasBoleta' => $ventasBoleta, 'productos' => $productos, 'servicios' => $servicios, 'tipoDoc' => $tipoDoc, 'permisos' => $permisos];
    return view('operaciones/ventas/ventas/crearVenta', $array);*/
    }

    public function store(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                if ($req->ajax()) {

                    $stockSuficiente = $this->verificarStockSuficiente($req);
                    if (!empty($stockSuficiente)) {
                        return Response(['alert1', 'Quedan ' . $stockSuficiente[1] . ' unidades en stock de : ' . $stockSuficiente[0]]);
                    } else {
                        $idUsuario = Session::get('idUsuario');
                        $serie = $req->serie;
                        $idTipoSunat = 'NT';
                        if ($serie == null) {
                            return Response(['alert1', 'Por favor, completar serie y número correlativo']);
                        }
                        $numero = $req->numero;
                        $idTipoComp = $req->idTipoComp;
                        if ($idTipoComp == 0) {
                            return Response(['alert1', 'Por favor, elegir Tipo de comprobante']);
                            //return back()->with('error','Por favor, elegir Tipo de comprobante')->withInput($req->all());
                        }
                        $idCliente = $req->cliente;
                        if ($idCliente == 0) {
                            return Response(['alert1', 'Por favor, elegir Cliente']);
                            //return back()->with('error','Por favor, elegir Cliente')->withInput($req->all());
                        }
                        if ($req->Id == null) {
                            return Response(['alert1', 'Por favor, agrege productos o servicios']);
                            //return back()->with('error','Por favor, agrege productos o servicios')->withInput($req->all());
                        }
                        $total = $req->total;
                        $fecha = date('Y-m-d');
                        //$fecha = $req->fechaEmitida;
                        //$fecha = $loadDatos->getDateTime();
                        //dd($fecha);

                        if ($fecha == null) {
                            return Response(['alert1', 'Por favor, ingresar fecha de venta']);
                            //return back()->with('error','Por favor, ingresar fecha de venta');
                        }

                        $numero = $this->completarCeros($numero);
                        $idSucursal = Session::get('idSucursal');
                        $verificar = $this->verificarCodigo($serie, $numero, $idSucursal);
                        if ($verificar->Cantidad > 0) {
                            return Response(['alert1', 'La Serie y Número ya existen, por favor vuelva a generar nuevamente']);
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
                        $detraccion = $req->detraccion;
                        $retencion = $req->retencion;
                        $anticipo = $req->anticipo;
                        $ordenCompra = $req->ordenCompra;
                        $codBienServicio = null;
                        $codMedioPago = null;
                        $loadDatos = new DatosController();
                        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
                        $tipoPago = $req->tipoPago;

                        if($detraccion == 1){
                            $cuentaDetraccion = $loadDatos->getCuentaDetracciones($usuarioSelect->CodigoCliente, 9);
                            if($cuentaDetraccion == null){
                                return Response(['alert12', 'Por favor, para ventas con detracciones es obligaotorio crear una cuenta de Detracciones']);
                            }
                            $codBienServicio = $req->bienServicio;
                            $codMedioPago = $req->medioPago;
                        }
                        /*if($ordenCompra == null || $ordenCompra == ""){
                            $ordenCompra = "-";
                        }*/

                        $placa = $req->placa;
                        $idPlaca = $req->idPlaca;
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
                        } else {
                            $subtotal = $req->opExonerado;
                        }

                        if ($exonerada == '-') {
                            $exonerada = '0.00';
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
                                    return Response(['alert1', 'Completar Numero de Tarjeta']);
                                }
                            }
                            $pagoEfectivo = floatval($pagoEfect) - floatval($vueltoEfectivo);
                            $pagoTotal = floatval($pagoEfectivo) + floatval($pagoTarjeta) + floatval($montoCuenta);
                            $_pagoTotal = round($pagoTotal, 2);
                            $_total = round($total, 2);
                            if ($_pagoTotal != $_total) {
                                return Response(['alert1', 'La suma de pago efectivo y pago con tarjeta debe ser igual al Importe Total']);
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
                                        return Response(['alert1', 'El cliente ' . $cliente->Nombre . '  con esta  venta sobrepasa su saldo para creditos, Su Linea de Credito Total es : ' . $cliente->SaldoCredito . ' Su monto usado hasta el momento es de : ' . $contCredito . '. Maximo de credito a  entregar en esta compra es de : ' . ($req->total - abs($saldoCredito))]);
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
                        $estado = 'Sin Valor Tributario';

                        
                        $tipoMoneda = $loadDatos->getTipoMonedaSelect($idTipoMoneda);
                        $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
                        if ($caja == null) {
                            //return back()->with('caja','Abrir Caja antes de realizar una venta');
                            return Response(['alert9', 'Abrir Caja antes de realizar una venta']);
                            //echo "<script language='javascript'>alert('Abrir Caja antes de realizar una venta');window.location='../../caja/cierre-caja'</script>";
                        } else {

                            $noReduceStock = 0;
                            $codigoAceptado = '';
                            $bandera = 1;
                            $resumen = '';
                            $hash = '';
                            $mensaje = 'Se genero Ticket con éxito';
                            if (intval($idTipoComp) < 3) {
                                $estado = 'Pendiente';
                                if (intval($idTipoComp) == 1) {
                                    $idTipoSunat = '03';
                                } else {
                                    $idTipoSunat = '01';
                                }
                            } else {
                                $estado = 'Sin Valor Tributario';
                            }

                            $array = ['IdCliente' => $idCliente, 'IdTipoMoneda' => $idTipoMoneda, 'Idsucursal' => $idSucursal, 'FechaCreacion' => $fechaConvertida, 'IdCreacion' => $idUsuario, 'IdTipoComprobante' => $idTipoComp, 'IdTipoSunat' => $idTipoSunat, 'Placa' => $placa,
                                'IdTipoPago' => $tipoPago, 'PlazoCredito' => $plazoCredito, 'MontoEfectivo' => $pagoEfectivo, 'IdTipoTarjeta' => $tipoTarjeta, 'NumeroTarjeta' => $numTarjeta, 'MontoTarjeta' => $pagoTarjeta, 'MontoCuentaBancaria' => $montoCuenta, 'TipoVenta' => $tipoVenta,
                                'Serie' => $serie, 'Numero' => $numero, 'Observacion' => $observacion, 'Subtotal' => $subtotal, 'Gratuita' => $totalGratuita, 'Exonerada' => $exonerada, 'IGV' => $igv, 'Total' => $total, 'OrdenCompra' => $ordenCompra, 'Detraccion' => $detraccion, 'CodDetraccion' => $codBienServicio, 'CodMedioPago' => $codMedioPago, 'PorcentajeDetraccion' => $valorDetraccion, 'Retencion' => $retencion, 'Resumen' => $resumen, 'Hash' => $hash, 'Anticipo' => $anticipo, 'Nota' => 0, 'Guia' => 0, 'CodigoDoc' => $codigoAceptado, 'Estado' => $estado];

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
                                $interes = 0;
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

                                    $invoice = (new Invoice())
                                        ->setUblVersion('2.1')
                                        ->setTipoOperacion('0101') // Catalog. 51
                                        ->setTipoDoc('03')
                                        ->setSerie($req->serie)
                                        ->setCorrelativo($req->numero)
                                        ->setFechaEmision($date)
                                        ->setTipoMoneda($tipoMoneda->CodigoMoneda)
                                        ->setClient($client)
                                        ->setMtoOperGravadas(floatval($req->subtotal)) // Subtotal
                                        ->setMtoOperExoneradas(floatval($req->opExonerado))
                                        ->setMtoOperGratuitas(floatval($subTotalGratuita))
                                        ->setMtoIGVGratuitas(floatval($igvGratuita))
                                        ->setMtoIGV(floatval($req->igv))
                                        ->setTotalImpuestos(floatval($req->igv))
                                        ->setValorVenta(floatval($subtotal)) //->setValorVenta(floatval($req->total))
                                        ->setSubTotal($total)
                                        ->setMtoImpVenta($total)
                                        ->setCompany($company);

                                    if($ordenCompra != null && $ordenCompra != ""){
                                        $invoice->setCompra($ordenCompra);
                                    }

                                    $array = [];
                                    $res = [];
                                    $legends = [];
                                    $countGratuita = 0;

                                    for ($i = 0; $i < count($req->Id); $i++) {
                                        $productoSelect = $loadDatos->getProductoSelect($req->Id[$i]);
                                        if ($req->Tipo[$i] == 1 || $req->Tipo[$i] == 3 || $req->Tipo[$i] == 4) {
                                            $newCantidad = $req->Cantidad[$i];
                                        } else if ($req->Tipo[$i] == 2) {
                                            $newCantidad = $req->Cantidad[$i] * $productoSelect->CantidadTipo;
                                        }

                                        //$valorUniDescuento=floatval($req->Importe[$i]/$newCantidad);
                                        $valorUniDescuento = floatval($req->Importe[$i] / $newCantidad);
                                        if ($tipoVenta == 1) {
                                            $subTotalItem = floatval(round($valorUniDescuento / 1.18, 5));
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

                                    //$loadDatos = new DatosController();
                                    $cliente = $loadDatos->getClienteSelect($req->cliente);

                                    $client = new Client();
                                    $client->setTipoDoc(strval($cliente->CodigoSunat)) //agregado
                                        ->setNumDoc($cliente->NumeroDocumento)
                                        ->setRznSocial($cliente->RazonSocial);

                                    // Emisor
                                    $idSucursal = Session::get('idSucursal');
                                    $idUsuario = Session::get('idUsuario');
                                    $sucursal = $loadDatos->getSucursalSelect($idSucursal);

                                    //$usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
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

                                    $invoice = (new Invoice())
                                        ->setUblVersion('2.1')
                                        //->setTipoOperacion('1001') 
                                        //->setTipoOperacion('0101') // Catalog. 51
                                        ->setTipoDoc('01')
                                        ->setSerie($req->serie)
                                        ->setCorrelativo($req->numero)
                                        ->setFechaEmision($date)
                                        ->setTipoMoneda($tipoMoneda->CodigoMoneda)
                                        ->setClient($client)
                                        ->setMtoOperGravadas(floatval($req->subtotal)) // Subtotal
                                        ->setMtoOperExoneradas(floatval($req->opExonerado))
                                        ->setMtoOperGratuitas(floatval($subTotalGratuita))
                                        ->setMtoIGVGratuitas(floatval($igvGratuita))
                                        ->setMtoIGV(floatval($req->igv))
                                        ->setTotalImpuestos(floatval($req->igv))
                                        ->setValorVenta(floatval($subtotal)) //->setValorVenta(floatval($req->total))
                                        ->setSubTotal($total)
                                        ->setMtoImpVenta($total)
                                        ->setCompany($company);
                                    //->setObservacion('Placa:T23-232');

                                    if($ordenCompra != null && $ordenCompra != ""){
                                        $invoice->setCompra($ordenCompra);
                                    }

                                    $array = [];
                                    $res = [];
                                    $legends = [];
                                    $countGratuita = 0;

                                    for ($i = 0; $i < count($req->Id); $i++) {
                                        $productoSelect = $loadDatos->getProductoSelect($req->Id[$i]);
                                        if ($req->Tipo[$i] == 1 || $req->Tipo[$i] == 3 || $req->Tipo[$i] == 4) {
                                            $newCantidad = $req->Cantidad[$i];
                                        } else if ($req->Tipo[$i] == 2) {
                                            $newCantidad = $req->Cantidad[$i] * $productoSelect->CantidadTipo;
                                        }

                                        $valorUniDescuento = floatval($req->Importe[$i] / $newCantidad);
                                        //$precioUni = floatval($req->Precio[$i]);
                                        if ($tipoVenta == 1) {
                                            $subTotalItem = floatval(round($valorUniDescuento / 1.18, 5));
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
                                            ->setMtoValorUnitario(round($subTotalItem, 5)) // PriceAmount cac:Price
                                            ->setMtoPrecioUnitario(round($valorUniDescuento, 5));//PriceAmount 

                                        if ($idPlaca != 0) {
                                            $item->setAtributos([(new DetailAttribute())
                                                    ->setName('Gastos Art. 37 Renta: Número de Placa')
                                                    ->setCode('7000')
                                                    ->setValue($placa)]);
                                        }

                                        array_push($array, $item);
                                        usleep(100000);
                                    }

                                    if($detraccion == 1){
                                        $codigoMedioPago = $loadDatos->getCodigoMedioPagoSelect($codMedioPago);
                                        $codigoBS = $loadDatos->getCodigoBienServicioSelect($codBienServicio);
                                        
                                        $montoDetraccion = floatval($total * $valorDetraccion / 100);
                                        $cuentaDetraccion = $loadDatos->getCuentaDetracciones($usuarioSelect->CodigoCliente, 9);

                                        $invoice->setDetraccion(
                                            (new Detraction())
                                                ->setCodBienDetraccion($codigoBS->CodigoSunat) // catalog. 54
                                                ->setCodMedioPago($codigoMedioPago->Codigo) // catalog. 59
                                                ->setCtaBanco($cuentaDetraccion->NumeroCuenta)
                                                ->setPercent($valorDetraccion)
                                                ->setMount($montoDetraccion))
                                        ->setTipoOperacion('1001');
                                    }else{
                                        $invoice->setTipoOperacion('0101');
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
                                        if ($detraccion == 1) {
                                            $totalCredito = floatval($total) - floatval($montoDetraccion);
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

                                    if($detraccion == 1){
                                        $legend3 = (new Legend())
                                            ->setCode('2006')
                                            ->setValue('Operación sujeta a detracción');

                                        array_push($legends, $legend3);
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

                            // Codigo para soporte - consultar facturas
                            $datosEmpresa = $loadDatos->getRucEmpresa($idUsuario);
                            if ($datosEmpresa->Ruc == '206081156021') {
                                if ($estado == 'Pendiente' || $estado == 'Aceptado') {
                                    DB::table('facturas_clientes_erp')->insert(['IdVentas' => $idVenta, 'CorrelativoFactura' => $req->serie . '-' . $req->numero, 'FechaCreacion' => $fechaConvertida, 'RucCliente' => $cliente->NumeroDocumento, 'TotalFactura' => $total, 'CodigoClienteFacturador' => $datosEmpresa->CodigoCliente, 'Estado' => 'Aceptado']);
                                }
                            }
                            // Fin

                            if ($bandera == 1) {

                                if (intval($idTipoComp) == 2) {
                                    DB::table('ventas')
                                        ->where('IdVentas', $idVenta)
                                        ->update(['CodigoDoc' => $codigoAceptado, 'Estado' => $estado]);

                                    if ($estado == 'Rechazo') {
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

    public function store_backup(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                if ($req->ajax()) {
                    $idUsuario = Session::get('idUsuario');
                    $serie = $req->serie;

                    if ($serie == null) {
                        return Response(['alert1', 'Por favor, completar serie y número correlativo']);
                    }
                    $numero = $req->numero;
                    $idTipoComp = $req->idTipoComp;
                    if ($idTipoComp == 0) {
                        return Response(['alert1', 'Por favor, elegir Tipo de comprobante']);
                        //return back()->with('error','Por favor, elegir Tipo de comprobante')->withInput($req->all());
                    }
                    $idCliente = $req->cliente;
                    if ($idCliente == 0) {
                        return Response(['alert1', 'Por favor, elegir Cliente']);
                        //return back()->with('error','Por favor, elegir Cliente')->withInput($req->all());
                    }
                    if ($req->Id == null) {
                        return Response(['alert1', 'Por favor, agrege productos o servicios']);
                        //return back()->with('error','Por favor, agrege productos o servicios')->withInput($req->all());
                    }
                    $total = $req->total;
                    $fecha = $req->fechaEmitida;
                    if ($fecha == null) {
                        return Response(['alert1', 'Por favor, ingresar fecha de venta']);
                        //return back()->with('error','Por favor, ingresar fecha de venta');
                    }

                    $numero = $this->completarCeros($numero);
                    $idSucursal = Session::get('idSucursal');
                    $verificar = $this->verificarCodigo($serie, $numero, $idSucursal);
                    if ($verificar->Cantidad > 0) {
                        //return Response(['alert6','La Serie y Número ya existen']);
                        //return back()->with('error','La Serie y Número ya existen');
                        $ultimoCorrelativo = $this->ultimoCorrelativo($idUsuario, $idSucursal, $idTipoComp);
                        $sumarCorrelativo = intval($ultimoCorrelativo->Numero) + 1;
                        $numero = $this->completarCeros($sumarCorrelativo);
                    }
                    $date = DateTime::createFromFormat('Y-m-d', $fecha);
                    $fechaConvertida = $date->format("Y-m-d H:i:s");
                    $idTipoMoneda = 1;
                    $subtotal = $req->subtotal;
                    $exonerada = $req->exonerada;
                    $observacion = $req->observacion;
                    if ($exonerada == '-') {
                        $exonerada = '0.00';
                    }
                    $tipoPago = $req->tipoPago;
                    if ($tipoPago == 1) {
                        $plazoCredito = '';
                        $pagoEfect = $req->pagoEfectivo;
                        $tipoTarjeta = $req->tipoTarjeta;
                        $numTarjeta = $req->numTarjeta;
                        $pagoTarjeta = $req->pagoTarjeta;
                        $vueltoEfectivo = $req->vueltoEfectivo;
                        if (floatval($pagoTarjeta) > 0) {
                            if ($numTarjeta == '' || $numTarjeta == null) {
                                return Response(['alert1', 'Completar Numero de Tarjeta']);
                                /*return back()->with('error','Completar Numero de Tarjeta')
                            ->withInput($req->all());*/
                            }
                        }
                        $pagoEfectivo = floatval($pagoEfect) - floatval($vueltoEfectivo);
                        $pagoTotal = floatval($pagoEfectivo) + floatval($pagoTarjeta);
                        $_pagoTotal = round($pagoTotal, 2);
                        $_total = round($total, 2);
                        if ($_pagoTotal != $_total) {
                            return Response(['alert1', 'La suma de pago efectivo y pago con tarjeta debe ser igual al Importe Total']);
                            /*return back()->with('error','La suma de pago efectivo y pago con tarjeta debe ser igual al Importe Total')
                        ->withInput($req->all());*/
                        }
                    } else {
                        $plazoCredito = $req->plazoCredito;
                        $pagoEfectivo = '';
                        $tipoTarjeta = '';
                        $numTarjeta = '';
                        $pagoTarjeta = '';
                    }
                    $igv = $req->igv;
                    $estado = 'Sin Valor Tributario';

                    $loadDatos = new DatosController();
                    $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
                    if ($caja == null) {
                        //return back()->with('caja','Abrir Caja antes de realizar una venta');
                        return Response(['alert9', 'Abrir Caja antes de realizar una venta']);
                        //echo "<script language='javascript'>alert('Abrir Caja antes de realizar una venta');window.location='../../caja/cierre-caja'</script>";
                    } else {
                        $stockSuficiente = $this->verificarStockSuficiente($req);

                        if ($stockSuficiente[0] == 1) {
                            $bandera = 1;
                            $resumen = '';
                            $hash = '';
                            $mensaje = 'Se genero Ticket con éxito';
                            if (intval($idTipoComp) < 3) {
                                $res = $this->envioSunat($req);
                                if ($res[2] == 0) {
                                    $bandera = 0;
                                    $mensaje = $res[1];
                                } else {
                                    if ($res[2] == 1) {
                                        $estado = 'Aceptado';
                                        $mensaje = 'Se generó Factura y se envio a Sunat con éxito';
                                    }
                                    if ($res[2] == 2) {
                                        $estado = 'Pendiente';
                                        if (intval($idTipoComp) == 1) {
                                            $mensaje = 'Se generó Boleta y se guardo con éxito';
                                        } else {
                                            $mensaje = 'Se generó Factura pero no se pudo enviar a Sunat';
                                        }
                                    }
                                    $hash = $res[0];
                                    $resumen = $res[1];
                                }
                            }
                            //dd($req);
                            if ($bandera == 1) {
                                $array = ['IdCliente' => $idCliente, 'IdTipoMoneda' => $idTipoMoneda, 'Idsucursal' => $idSucursal, 'FechaCreacion' => $fechaConvertida, 'IdCreacion' => $idUsuario, 'IdTipoComprobante' => $idTipoComp,
                                    'IdTipoPago' => $tipoPago, 'PlazoCredito' => $plazoCredito, 'MontoEfectivo' => $pagoEfectivo, 'IdTipoTarjeta' => $tipoTarjeta, 'NumeroTarjeta' => $numTarjeta, 'MontoTarjeta' => $pagoTarjeta,
                                    'Serie' => $serie, 'Numero' => $numero, 'Observacion' => $observacion, 'Subtotal' => $subtotal, 'Exonerada' => $exonerada, 'IGV' => $igv, 'Total' => $total, 'Resumen' => $resumen, 'Hash' => $hash, 'Nota' => 0, 'Guia' => 0, 'Estado' => $estado];

                                DB::table('ventas')->insert($array);

                                $venta = DB::table('ventas')
                                    ->orderBy('IdVentas', 'desc')
                                    ->first();
                                $idVenta = $venta->IdVentas;

                                if ($tipoPago == 2) {
                                    $interes = 0;
                                    $this->guardarFechasPago($fecha, $plazoCredito, $idVenta, $total, $interes);
                                }

                                $arrayCaja = ['IdCaja' => $caja->IdCaja, 'IdVentas' => $idVenta];
                                DB::table('caja_ventas')->insert($arrayCaja);

                                //$ganancias = $this->actualizarStock($req);

                                for ($i = 0; $i < count($req->Id); $i++) {
                                    $producto = substr($req->Codigo[$i], 0, 3);
                                    $ganancias = $this->actualizarStock($req->Id[$i], $producto, $req->Cantidad[$i]);
                                    if ($producto == 'PRO') {
                                        $productoSelect = $loadDatos->getProductoSelect($req->Id[$i]);
                                        $cantidadRestada = (int) $productoSelect->Stock - (int) $req->Cantidad[$i];
                                        DB::table('articulo')
                                            ->where('IdArticulo', $req->Id[$i])
                                            ->update(['Stock' => $cantidadRestada]);
                                    }
                                    $arrayRelacion = ['IdVentas' => $idVenta, 'IdArticulo' => $req->Id[$i], 'Codigo' => $req->Codigo[$i], 'Detalle' => $req->Detalle[$i], 'Descuento' => $req->Descuento[$i], 'Cantidad' => $req->Cantidad[$i], 'Ganancia' => $ganancias[$i], 'Importe' => $req->Importe[$i]];
                                    DB::table('ventas_articulo')->insert($arrayRelacion);
                                    usleep(150000);
                                }

                                return Response(['succes', $mensaje, $idVenta]);
                            } else {
                                return Response(['error', $mensaje]);
                                //return redirect()->to('operaciones/ventas/realizar-venta')->with('error', $mensaje);
                            }
                        } else {
                            return Response(['alert1', 'Quedan ' . $stockSuficiente[2] . ' unidades en stock de : ' . $stockSuficiente[1]]);
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

    public function verificarStockSuficiente($req)
    {
        $loadDatos = new DatosController();
        $array = [];
        for ($i = 0; $i < count($req->Id); $i++) {
            $sumador = 0;
            $cantidadTotal = 0;
            $producto = substr($req->Codigo[$i], 0, 3);
            $productoSelect = $loadDatos->getProductoSelect($req->Id[$i]);
            if ($producto == 'PRO') {
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

//$arrayGanancias[$i] = $ganancia;

//}

/*else{
$ganancia = ((int) $Cantidad * (float) $req->Ganancia[$i]) - (float)$req->Descuento[$i];
}*/
//usleep(100000);
//}
//return($ganancia);

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

    public function guardarTipoCambio(Request $req)
    {
        if ($req->ajax()) {
            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
            $fecha = Carbon::today();

            $tipoCambioCompras = $req->tipoCambioCompras;
            $tipoCambioVentas = $req->tipoCambioVentas;

            $tipoCambioComprasSunat = $req->tipoCambioComprasSunat;
            $tipoCambioVentasSunat = $req->tipoCambioVentasSunat;

            if ($tipoCambioCompras == null || $tipoCambioCompras == '') {
                return Response(['alert', 'Completar el campo de tipo de cambio de compras']);
            }

            if ($tipoCambioVentas == null || $tipoCambioVentas == '') {
                return Response(['alert', 'Completar el campo de tipo de cambio de ventas']);
            }

            if ($tipoCambioComprasSunat == null || $tipoCambioComprasSunat == '') {
                return Response(['alert', 'Completar el campo de tipo de cambio de compras Sunat']);
            }

            if ($tipoCambioVentasSunat == null || $tipoCambioVentasSunat == '') {
                return Response(['alert', 'Completar el campo de tipo de cambio de ventas Sunat']);
            }

            $array = ['FechaCreacion' => $fecha, 'IdUsuario' => $idUsuario, 'IdSucursal' => $idSucursal, 'TipoCambioCompras' => $tipoCambioCompras, 'TipoCambioVentas' => $tipoCambioVentas, 'Estado' => 'E'];
            DB::table('tipo_cambio')->insert($array);

            $_array = ['FechaCreacion' => $fecha, 'IdUsuario' => $idUsuario, 'IdSucursal' => $idSucursal, 'ComprasSunat' => $tipoCambioComprasSunat, 'VentasSunat' => $tipoCambioVentasSunat, 'Estado' => 'E'];
            DB::table('tipo_cambio_sunat')->insert($_array);

            /*$tipoCambio = DB::table('tipo_cambio')
            ->where('IdTipoCambio', 'desc')
            ->first();*/

            return Response(['success', 'Se guardo tipo de cambio correctamente']);
        }
    }

    public function selectAnticipo(Request $req)
    {
        if ($req->ajax()) {
            $idSucursal = Session::get('idSucursal');
            $idUsuario = Session::get('idUsuario');
            if ($req->tipoMoneda == 1) {
                $nombreServicio = 'Anticipo Soles';
            } else {
                $nombreServicio = 'Anticipo Dolares';
            }

            $selectAnticipo = DB::table('articulo')
                ->where('Descripcion', $nombreServicio)
                ->where('IdTipo', 2)
                ->where('IdTipoMoneda', $req->tipoMoneda)
                ->where('IdSucursal', $idSucursal)
                ->get();

            if (count($selectAnticipo) == 0) {
                $date = Carbon::now();
                $array = ['IdTipo' => 2, 'IdUnidadMedida' => 11, 'IdSucursal' => $idSucursal, 'IdTipoMoneda' => $req->tipoMoneda, 'FechaCreacion' => $date, 'IdCreacion' => $idUsuario, 'Descripcion' => $nombreServicio, 'Precio' => 0, 'Exonerado' => 1, 'Costo' => 0, 'Codigo' => null, 'CodigoInterno' => null, 'TipoOperacion' => 1, 'Imagen' => 'https://easyfactperu2021.s3.us-west-2.amazonaws.com/1641830772.png', 'Estado' => 'D'];
                DB::table('articulo')->insert($array);

                $selectAnticipo = DB::table('articulo')
                    ->where('Descripcion', $nombreServicio)
                    ->where('IdTipo', 2)
                    ->where('IdTipoMoneda', $req->tipoMoneda)
                    ->where('IdSucursal', $idSucursal)
                    ->get();
            }

            return Response([$selectAnticipo]);
        }
    }

    public function selectProductos(Request $req)
    {
        if ($req->ajax()) {
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            //$textoBuscar = "";

            $fecha = Carbon::today();

            $tipoCambio = DB::table('tipo_cambio')
                ->where('FechaCreacion', $fecha)
                ->where('IdSucursal', $idSucursal)
                ->get();

            //$productos = $loadDatos->getBuscarProductosVentas($textoBuscar, $req->tipoMoneda, $idSucursal, 0);

            //$servicios = $loadDatos->getBuscarServiciosVentas($textoBuscar, $req->tipoMoneda, $idSucursal);

            return Response([$tipoCambio]);
        }
    }

    // public function paginationProductos(Request $req)
    // {
    //     if ($req->ajax()) {
    //         $idSucursal = Session::get('idSucursal');
    //         $optMarca = Session::get('optMarca');
    //         $loadDatos = new DatosController();

    //         $cod_cliente = DB::table('sucursal')
    //             ->select('CodigoCliente')
    //             ->where('IdSucursal', $idSucursal)
    //             ->first();

    //         $sucPrincipal = DB::table('sucursal')
    //             ->select('IdSucursal')
    //             ->where('CodigoCliente', $cod_cliente->CodigoCliente)
    //             ->where('Principal', 1)
    //             ->first();

    //         if ($optMarca == 1) {
    //             if ($sucPrincipal->IdSucursal == $idSucursal) {
    //                 $productos = $loadDatos->getProductosPaginationNoMarca($idSucursal, $req->textoBuscar, $req->tipoMoneda, $req->idCategoria);
    //             } else {
    //                 $productos = $loadDatos->paginarAjaxProdSucursalNoMarca($idSucursal, $req->textoBuscar, $req->tipoMoneda, $req->idCategoria);
    //             }
    //         } else {
    //             if ($sucPrincipal->IdSucursal == $idSucursal) {
    //                 $productos = $loadDatos->getProductosPagination($idSucursal, $req->textoBuscar, $req->tipoMoneda, $req->idCategoria);
    //             } else {
    //                 $productos = $loadDatos->paginarAjaxProdSucursal($idSucursal, $req->textoBuscar, $req->tipoMoneda, $req->idCategoria);
    //             }
    //         }
    //         return Response($productos);
    //     }
    // }

    // public function paginationServicios(Request $req)
    // {
    //     if ($req->ajax()) {
    //         $idSucursal = Session::get('idSucursal');
    //         $text2 = Session::get('text');
    //         $loadDatos = new DatosController();
    //         $servicios = $loadDatos->getServiciosPagination($idSucursal, $req->tipoMoneda, $text2);
    //         return Response($servicios);
    //     }
    // }

    // public function searchProducto(Request $req)
    // {
    //     if ($req->ajax()) {
    //         $loadDatos = new DatosController();
    //         $idSucursal = Session::get('idSucursal');
    //         $optMarca = $req->sinMarca;
    //         Session::put('text', $req->textoBuscar);
    //         Session::put('optMarca', $req->sinMarca);

    //         $cod_cliente = DB::table('sucursal')
    //             ->select('CodigoCliente')
    //             ->where('IdSucursal', $idSucursal)
    //             ->first();

    //         $sucPrincipal = DB::table('sucursal')
    //             ->select('IdSucursal')
    //             ->where('CodigoCliente', $cod_cliente->CodigoCliente)
    //             ->where('Principal', 1)
    //             ->first();

    //         if ($optMarca == 1) {
    //             if ($sucPrincipal->IdSucursal == $idSucursal) {
    //                 $articulos = $loadDatos->getBuscarProdNoMarcas($req->textoBuscar, $req->tipoMoneda, $idSucursal, $req->idCategoria);
    //             } else {
    //                 $articulos = $loadDatos->buscarAjaxProdNoMarcaSucursal($req->textoBuscar, $req->tipoMoneda, $idSucursal, $req->idCategoria);
    //             }
    //         } else {
    //             if ($sucPrincipal->IdSucursal == $idSucursal) {
    //                 $articulos = $loadDatos->getBuscarProductosVentas($req->textoBuscar, $req->tipoMoneda, $idSucursal, $req->idCategoria);
    //             } else {
    //                 $articulos = $loadDatos->buscarAjaxProdSucursal($req->textoBuscar, $req->tipoMoneda, $idSucursal, $req->idCategoria);
    //             }
    //         }

    //         return Response($articulos);
    //     }
    // }

    // public function searchServicio(Request $req)
    // {
    //     if ($req->ajax()) {
    //         $loadDatos = new DatosController();
    //         $idSucursal = Session::get('idSucursal');
    //         Session::put('text', $req->textoBuscar);
    //         $articulos = $loadDatos->getBuscarServiciosVentas($req->textoBuscar, $req->tipoMoneda, $idSucursal);
    //         return Response($articulos);
    //     }
    // }

    public function searchCodigoProducto(Request $req)
    {
        if ($req->ajax()) {
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');

            $articulos = $loadDatos->getBuscarCodigoProductoVentas($req->codigoBusqueda, $req->tipoMoneda, $idSucursal);
            return Response($articulos);
        }
    }

    public function porcentajeDescuento(Request $req)
    {
        if ($req->ajax()) {
            $loadDatos = new DatosController();
            $idProducto = $req->idProducto;
            $descuentos = $loadDatos->getProductoSelect($idProducto);
            return Response([$descuentos]);
        }
    }

    public function verFacturaGenerada(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $idIconoWhatsapp = $id;
        if (strpos($id, "W-") === 0) {
            $id = substr($id, 2);
        }

        $loadDatos = new DatosController();
        $ventaSelect = $loadDatos->getVentaselect($id);

        $idUsuario = Session::get('idUsuario');
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $idSucursal = Session::get('idSucursal');

        if ($ventaSelect->IdTipoPago == 1) {
            $fechaPago = '';
        } else {
            $fechaPagoConvert = Carbon::parse($ventaSelect->FechaCreacion);
            $fechaPagoDias = $fechaPagoConvert->addDays($ventaSelect->PlazoCredito);
            $fechaPagoDate = new DateTime($fechaPagoDias);
            $fechaPago = date_format($fechaPagoDate, 'd-m-Y');

            $fechaHoy = $loadDatos->getDateTime();
            $cobranzasTotales = $loadDatos->getCobranzasTotales($idSucursal, $fechaHoy);
            //dd($cobranzasTotales);
            $this->actualizarFechasPasados($idSucursal, $cobranzasTotales);
        }
        if ($ventaSelect->Seguro != null && $ventaSelect->Seguro > 2) {
            $datosSeguro = $this->getDatosSeguro($ventaSelect->IdVentas);
            $seguroNombre = $datosSeguro->Descripcion;
            $idSeguro = $datosSeguro->IdSeguro;
        } else {
            $seguroNombre = "";
            $idSeguro = 1;
        }

        if ($ventaSelect->IdTipoMoneda == 1) {
            $totalDetrac = $ventaSelect->Total + $ventaSelect->Amortizacion;
        } else {

            if ($ventaSelect->IdCotizacion) {
                $cotiSelect = DB::table('cotizacion')
                    ->where('IdCotizacion', $ventaSelect->IdCotizacion)
                    ->first();
                $fechaDetrac = Carbon::parse($cotiSelect->FechaCreacion);
                $fechaDetrac = date_format($fechaDetrac, 'Y-m-d');
            } else {
                $fechaDetrac = Carbon::parse($ventaSelect->FechaCreacion);
                $fechaDetrac = date_format($fechaDetrac, 'Y-m-d');
            }

            $valorCambio = DB::table('tipo_cambio')
                ->where('IdSucursal', $idSucursal)
                ->where('FechaCreacion', $fechaDetrac)
                ->first();

            if ($valorCambio) {
                $totalDetrac = ($ventaSelect->Total + $ventaSelect->Amortizacion) * $valorCambio->TipoCambioVentas;
            } else {
                $totalDetrac = $ventaSelect->Total + $ventaSelect->Amortizacion;
            }
        }

        $numeroCerosIzquierda = $this->completarCeros($ventaSelect->Numero);
        $fecha = date_create($ventaSelect->FechaCreacion);
        $formatoFecha = date_format($fecha, 'd-m-Y');
        $formatoHora = date_format($fecha, 'H:i A');

        //$items = $loadDatos->getItemsVentas($id);
        $items = $loadDatos->getItemsYpaquetePromocional($id);

        $itemsServ = $items->where('IdTipo', 2);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $numeroCelular = $ventaSelect->TelfCliente;
        if ($numeroCelular != null) {
            if (str_starts_with($numeroCelular, 9) === true) {
                $numeroCelular = $numeroCelular;
            } else {
                $numeroCelular = '';
            }
        }

        $cuentaDetracciones = $loadDatos->getCuentaDetracciones($usuarioSelect->CodigoCliente, 9);

        if ($ventaSelect->Anticipo > 2) {
            $anticipoSelect = $loadDatos->getVentaselect($ventaSelect->Anticipo);
            $fechaAnticipo = date_create($ventaSelect->FechaCreacion);
            $formatoFechaAnticipo = date_format($fecha, 'd-m-Y');
        } else {
            $anticipoSelect = [];
            $formatoFechaAnticipo = '';
        }

        $codMedioPago = null;
        $codDetraccion = null;

        if($ventaSelect->Detraccion == 1){
            $codMedioPago = $loadDatos->getCodigoMedioPagoSelect($ventaSelect->CodMedioPago);
            $codDetraccion = $loadDatos->getCodigoBienServicioSelect($ventaSelect->CodDetraccion);
        }

        // Verifica si la venta se realizado pago con deposito y agregar el numero de cuenta y numero de operacion
        if ($this->isPagoConDeposito($ventaSelect)) {
            $this->agregarDetallesBanco($ventaSelect, $idSucursal);
        }
        $array = ['ventaSelect' => $ventaSelect, 'cuentaDetracciones' => $cuentaDetracciones, 'anticipoSelect' => $anticipoSelect, 'totalDetrac' => $totalDetrac, 'items' => $items, 'itemsServ' => $itemsServ, 'seguroNombre' => $seguroNombre, 'idSeguro' => $idSeguro, 'permisos' => $permisos, 'numeroCeroIzq' => $numeroCerosIzquierda, 'formatoFecha' => $formatoFecha, 'formatoHora' => $formatoHora, 'fechaPago' => $fechaPago, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'rucEmpresa' => $empresa->Ruc, 'formatoFechaAnticipo' => $formatoFechaAnticipo, 'numeroCelular' => $numeroCelular, 'idIconoWhatsapp' => $idIconoWhatsapp, 'empresa' => $empresa, 'codMedioPago' => $codMedioPago, 'codDetraccion' => $codDetraccion];
        return view('operaciones/ventas/ventas/facturaGenerada', $array)->with('status', 'Se registro venta exitosamente');
    }

    private function isPagoConDeposito($ventaSelect)
    {
        return $ventaSelect->IdTipoPago == 1 && $ventaSelect->MontoCuentaBancaria !== null && $ventaSelect->MontoCuentaBancaria !== "0.00";
    }
    private function agregarDetallesBanco($ventaSelect, $idSucursal)
    {
        $datosBanco = DB::table('banco_detalles')
            ->join('banco', 'banco_detalles.IdBanco', '=', 'banco.IdBanco')
            ->select('NumeroCuenta', 'NumeroOperacion')
            ->where('Detalle', 'like', "%$ventaSelect->Serie-$ventaSelect->Numero%")
            ->where('IdSucursal', $idSucursal)
            ->first();
        if ($datosBanco) {
            $ventaSelect->NumeroCuentaBancaria = $datosBanco->NumeroCuenta;
            $ventaSelect->NumeroOperacionBancaria = $datosBanco->NumeroOperacion;
        }
        return $ventaSelect;
    }

    public function getDetallePaquetePromocional(Request $req)
    {
        try {
            if ($req->ajax()) {
                $loadDatos = new DatosController();
                $datos = $loadDatos->getItemsPaquetePromocional($req->idPaquete);
                return Response($datos);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function getItemsYpaquetePromocional($idVentas)
    {
        try {
            /*$ventas = DB::table('ventas_articulo')
            ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
            ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
            ->select('ventas_articulo.*', 'ventas_articulo.Codigo as Cod', 'articulo.*', 'unidad_medida.Nombre as UniMedida', 'unidad_medida.Descripcion as CodSunatMedida')
            ->where('ventas_articulo.IdVentas', $idVenta)
            ->get();*/

            $items = DB::table('ventas_articulo')
                ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->select('ventas_articulo.*', 'ventas_articulo.Codigo as Cod', 'articulo.*', 'articulo.Descripcion as Descripcion')
                ->where('ventas_articulo.IdVentas', $idVentas)
                ->whereNull('ventas_articulo.IdPaquetePromocional')
                ->get();

            $itemsPaquetePromocional = DB::table('ventas_articulo')
                ->join('paquetes_promocionales', 'ventas_articulo.IdPaquetePromocional', '=', 'paquetes_promocionales.IdPaquetePromocional')
                ->select('ventas_articulo.*', 'ventas_articulo.Codigo as Cod', 'paquetes_promocionales.*', 'paquetes_promocionales.NombrePaquete as Descripcion')
                ->where('ventas_articulo.IdVentas', $idVentas)
                ->where('ventas_articulo.IdPaquetePromocional', '>', 0)
                ->get();
            $resultado = $items->concat($itemsPaquetePromocional);

            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function obtenerFacturaGenerada(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        $loadDatos = new DatosController();
        $ventaSelect = $loadDatos->getVentaselect($id);
        $idUsuario = Session::get('idUsuario');
        $idSucursal = Session::get('idSucursal');

        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $numeroCerosIzquierda = $this->completarCeros($ventaSelect->Numero);
        $fecha = date_create($ventaSelect->FechaCreacion);
        $formatoFecha = date_format($fecha, 'd-m-Y');
        $formatoHora = date_format($fecha, 'H:i A');
        $items = $loadDatos->getItemsVentas($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);

        /*********************************mio*************************************/
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        /**********************************************************************/
        /*
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['ventaSelect' => $ventaSelect, 'items' => $items, 'permisos' => $permisos, 'numeroCeroIzq' => $numeroCerosIzquierda, 'formatoFecha' => $formatoFecha, 'formatoHora' => $formatoHora,'modulosSelect' => $modulosSelect];
        return view('operaciones/ventas/ventas/obtenerFacturaGenerada', $array)->with('status','Se registro venta exitosamente');
         */
        $convertirLetras = new NumeroALetras();
        $importeLetras = $convertirLetras->convertir($ventaSelect->Total, 'soles');
        $numeroCerosIzquierda = $this->completarCeros($ventaSelect->Numero);
        $resumen = $ventaSelect->Resumen;
        $hash = $ventaSelect->Hash;

        $array = ['items' => $items, 'numeroCeroIzq' => $numeroCerosIzquierda, 'ventaSelect' => $ventaSelect, 'resumen' => $resumen, 'hash' => $hash,
            'formatoFecha' => $formatoFecha, 'formatoHora' => $formatoHora, 'importeLetras' => $importeLetras, 'empresa' => $empresa, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];

        return view('operaciones/ventas/ventas/obtenerFacturaGenerada', $array)->with('status', 'Se registro venta exitosamente');
    }

    public function imprimirPDF(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $pdf = $this->generarPDF($req, $req->selectImpre, $id);
        $loadDatos = new DatosController();
        $ventaSelect = $loadDatos->getVentaselect($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $rucEmpresa = $empresa->Ruc;
        $numero = $ventaSelect->Numero;
        $serie = $ventaSelect->Serie;
        if ($ventaSelect->IdTipoComprobante == 1) {
            $idDoc = 03;
        }
        if ($ventaSelect->IdTipoComprobante == 2) {
            $idDoc = 01;
        }
        if ($ventaSelect->IdTipoComprobante == 3) {
            $idDoc = 12;
        }
        return $pdf->stream($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numero . '.pdf');
    }

    public function getCuentasCorrientes($codigoCliente)
    {

        $resultado = DB::table('banco')
            ->join('lista_banco', 'banco.IdListaBanco', '=', 'lista_banco.IdListaBanco')
            ->join('tipo_moneda', 'banco.IdTipoMoneda', '=', 'tipo_moneda.IdTipoMoneda')
            ->join('tipos_cuentas_bancarias as tcb', 'banco.IdCuentaBancaria', '=', 'tcb.IdCuentaBancaria')
            ->select('banco.NumeroCuenta', 'lista_banco.Nombre as Banco', 'tipo_moneda.Nombre as Moneda', 'banco.CCI', DB::Raw('UPPER(tcb.Nombre) as NombreCuenta'))
            ->where('CodigoCliente', $codigoCliente)
            ->where('lista_banco.IdListaBanco', '!=', 9)
            ->where('banco.Estado', 'E')
            ->limit(5)
            ->get();
        return $resultado;
    }

    public function storePdfForWhatsapp(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $numeroCelular = $req->numeroCelular;
        $id = $req->idVenta;
        $pdf = $this->generarPDF($req, 1, $id);
        $loadDatos = new DatosController();
        $ventaSelect = $loadDatos->getVentaselect($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $rucEmpresa = $empresa->Ruc;
        $numeroCerosIzquierda = $this->completarCeros($ventaSelect->Numero);
        $serie = $ventaSelect->Serie;
        if ($ventaSelect->IdTipoComprobante == 1) {
            $idDoc = '03';
            $nombreDocumento = 'Boleta';
        }
        if ($ventaSelect->IdTipoComprobante == 2) {
            $idDoc = '01';
            $nombreDocumento = 'Factura';
        }
        if ($ventaSelect->IdTipoComprobante == 3) {
            $idDoc = '12';
            $nombreDocumento = 'Ticket';
        }
        $fechaCreacionPdf = Carbon::now()->toDateTimeString();
        $nombrePdf = "$serie-$numeroCerosIzquierda";
        $directorio = "/PdfWhatsApp/Ventas/";
        $urlPdf = $this->storePdfWhatsAppS3($pdf, $nombrePdf, $directorio, $empresa->Ruc);

        $array = ['UrlPdf' => $urlPdf, 'FechaCreacionPdf' => $fechaCreacionPdf];
        DB::table('ventas')
            ->where('IdVentas', $id)
            ->update($array);

        $fechaVenta = carbon::parse($ventaSelect->FechaCreacion)->isoFormat('D [de] MMMM [de] YYYY');
        $mensajeUrl = "¡Hola estamos muy Felices en: *$empresa->NombreComercial* con RUC: *$empresa->Ruc*, por tu compra realizado el dia: *$fechaVenta*! 🥳%0A%0A ☝️ Hemos recibido tu pago, podrás descargar tu *$nombreDocumento* haciendo click en el link de la parte inferior, este enlace solo estará disponible por 30 días. 📄 🙌 %0A%0A 📞 Si tienes alguna duda o consulta, no dudes en comunicarte con nuestro Centro de Servicio al Cliente al teléfono: *$empresa->Telefono*, con tus asesores de siempre que estarán gustos en atenderte.%0A%0A" . config('variablesGlobales.urlDominioAmazonS3') . $urlPdf;

        if ($loadDatos->isMobileDevice()) {
            return redirect()->away('https://api.whatsapp.com/send?phone=+51' . $numeroCelular . '&text=' . $mensajeUrl);
        } else {
            return redirect()->away('https://web.whatsapp.com/send?phone=51' . $numeroCelular . '&text=' . $mensajeUrl);
        }
    }

    public function descargarPDF(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        // dd($id);
        $pdf = $this->generarPDF($req, 1, $id);
        $loadDatos = new DatosController();
        $ventaSelect = $loadDatos->getVentaselect($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $rucEmpresa = $empresa->Ruc;
        $numeroCerosIzquierda = $this->completarCeros($ventaSelect->Numero);
        $serie = $ventaSelect->Serie;
        if ($ventaSelect->IdTipoComprobante == 1) {
            $idDoc = '03';
        }
        if ($ventaSelect->IdTipoComprobante == 2) {
            $idDoc = '01';
        }
        if ($ventaSelect->IdTipoComprobante == 3) {
            $idDoc = '12';
        }
        return $pdf->download($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numeroCerosIzquierda . '.pdf');
    }

    public function descargarValeAlmacenPDF(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        $pdf = $this->generarPDF($req, 4, $id);
        $loadDatos = new DatosController();
        $ventaSelect = $loadDatos->getVentaselect($id);

        $serieVale = substr($ventaSelect->Serie, 1);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $rucEmpresa = $empresa->Ruc;
        $numeroCerosIzquierda = $this->completarCeros($ventaSelect->Numero);
        //$serie = $ventaSelect->serieVale;

        if ($ventaSelect->IdTipoComprobante == 1) {
            $idDoc = '03';
        }
        if ($ventaSelect->IdTipoComprobante == 2) {
            $idDoc = '01';
        }
        if ($ventaSelect->IdTipoComprobante == 3) {
            $idDoc = '12';
        }
        // return $pdf->download($rucEmpresa.'-'.$idDoc.'-'.$serie.'-'.$numeroCerosIzquierda.'.pdf');
        return $pdf->download('VA' . $serieVale . '-' . $numeroCerosIzquierda . '.pdf');
    }

    public function descargarXML(Request $req, $ruc, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $ventaSelect = $loadDatos->getVentaselect($id);
            $serie = $ventaSelect->Serie;
            $numero = $ventaSelect->Numero;
            $idTipoComprobante = $ventaSelect->IdTipoComprobante;
            $cod = $serie . '-' . $numero;
            if ($idTipoComprobante == 1) {
                $file = $ruc . '-03-' . $cod . '.xml';
            } else {
                $file = $ruc . '-01-' . $cod . '.xml';
            }

            if (Storage::disk('s3')->exists($ventaSelect->RutaXml)) {

                $rutaS3 = Storage::disk('s3')->get($ventaSelect->RutaXml);
                $headers = [
                    'Content-Type' => 'text/xml',
                    'Content-Description' => 'File Transfer',
                    'Content-Disposition' => "attachment; filename=" . $file . "",
                    'filename' => '' . $file . '',
                ];
                
                return response($rutaS3, 200, $headers);
            } else {

                /*$rutaXml = $this->generarXML($ruc, $ventaSelect);

                $rutaS3 = Storage::disk('s3')->get($rutaXml);
                $headers = [
                'Content-Type' => 'text/xml',
                'Content-Description' => 'File Transfer',
                'Content-Disposition' => "attachment; filename=" . $file . "",
                'filename' => '' . $file . '',
                ];

                return response($rutaS3, 200, $headers);*/
                return back()->with('error', 'No se encontró archivo Xml');
            }
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

    }

    public function generarXML($ruc, $ventaSelect)
    {
        $idUsuario = Session::get('idUsuario');
        $idSucursal = Session::get('idSucursal');
        //$idDoc = $req->idDocEnvio;
        $loadDatos = new DatosController();
        //$ventaSelect = $loadDatos->getVentaselect($idDoc);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $fecha = $ventaSelect->FechaCreacion;
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $fecha);

        $opcionFactura = DB::table('usuario')
            ->select('OpcionFactura')
            ->where('IdUsuario', $idUsuario)
            ->first();
        $config = new config();
        if ($opcionFactura->OpcionFactura > 0) {
            if ($opcionFactura->OpcionFactura == 1) { //sunat
                $see = $config->configuracion(SunatEndpoints::FE_BETA);
            } else if ($opcionFactura->OpcionFactura == 2) { //ose
                $see = $config->configuracion('https://demo-ose.nubefact.com/ol-ti-itcpe/billService?wsdl');
            } else {
                return Response(['error', 'No  tiene Elejida la  ruta de  envio.... comuniquese con  el administrador']);
            }
        } else {
            return Response(['error', 'No  tiene Elejida  el tipo de  envio.... comuniquese con  el administrador']);
        }
//$config = new config();
//$see = $config->configuracion(SunatEndpoints::FE_BETA);

        $cliente = $loadDatos->getClienteSelect($ventaSelect->IdCliente);

        $tipoMoneda = $loadDatos->getTipoMonedaSelect($ventaSelect->IdTipoMoneda);

        $client = new Client();
        $client->setTipoDoc($cliente->CodigoSunat) //agregado
            ->setNumDoc($cliente->NumeroDocumento)
            ->setRznSocial($cliente->RazonSocial);

// Emisor
        $idSucursal = Session::get('idSucursal');
        $sucursal = $loadDatos->getSucursalSelect($idSucursal);

        $address = new Address();
        $address->setUbigueo($empresa->Ubigeo)
            ->setDepartamento($empresa->Departamento)
            ->setProvincia($empresa->Provincia)
            ->setDistrito($empresa->Distrito)
            ->setUrbanizacion('NONE')
            ->setCodLocal($sucursal->CodFiscal)
            ->setDireccion($sucursal->DirPrin);

        $company = new Company();
        $company->setRuc($empresa->Ruc)
            ->setRazonSocial($empresa->Nombre)
            ->setNombreComercial('NONE')
            ->setAddress($address);

// Venta
        $exonerada = $ventaSelect->Exonerada;
        if ($exonerada == '-') {
            $exonerada = '0.00';
        }

        if ($ventaSelect->TipoVenta == 1) {
            $opGravada = $ventaSelect->Subtotal;
            $opExonerada = 0;
            $codTipo = '04'; // op Gravadas
        } else {
            $opGravada = 0;
            $opExonerada = $ventaSelect->Subtotal;
            $codTipo = '05'; // op Exoneradas
        }

        $totalGratuita = 0;
        if ($ventaSelect->Gratuita > 0) {
            $totalGratuita = floatval($ventaSelect->Gratuita);
            if ($ventaSelect->TipoVenta == 1) {
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

        $idAnticipo = $ventaSelect->Anticipo;
        if ($idAnticipo > 2) {
            $anticipoSelect = DB::table('ventas')
                ->where('IdVentas', $idAnticipo)
                ->first();
            $docRelacionado = $anticipoSelect->Serie . '-' . $anticipoSelect->Numero;

            $total = floatval($ventaSelect->Total) + floatval($ventaSelect->Amortizacion);

            $mtoOperGravadas = floatval($opGravada);
            $mtoIGV = floatval($ventaSelect->IGV);
            $mtoSubTotal = floatval($total) + floatval($anticipoSelect->Total);
            $mtoValorVenta = floatval($ventaSelect->Subtotal) + floatval($anticipoSelect->Subtotal);

            $mtoOperExoneradas = floatval($opExonerada);
            //$totalDif = floatval($total) - floatval($anticipoSelect->Total);

            $invoice = (new Invoice())
                ->setUblVersion('2.1')
                ->setTipoOperacion('0101') // Catalog. 51
                ->setTipoDoc('01')
                ->setSerie($ventaSelect->Serie)
                ->setCorrelativo($ventaSelect->Numero)
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
        } else {
            //$total = floatval($ventaSelect->Total) - floatval($exonerada);
            $total = floatval($ventaSelect->Total) + floatval($ventaSelect->Amortizacion);

            $invoice = (new Invoice())
                ->setUblVersion('2.1')
                ->setTipoOperacion('0101') // Catalog. 51
                ->setTipoDoc('01')
                ->setSerie($ventaSelect->Serie)
                ->setCorrelativo($ventaSelect->Numero)
                ->setFechaEmision($date)
                ->setTipoMoneda($tipoMoneda->CodigoMoneda)
                ->setClient($client)
                ->setMtoOperGravadas(floatval($opGravada)) // Subtotal
                ->setMtoOperExoneradas(floatval($opExonerada))
                ->setMtoOperGratuitas(floatval($subTotalGratuita))
                ->setMtoIGVGratuitas(floatval($igvGratuita))
                ->setMtoIGV(floatval($ventaSelect->IGV))
                ->setTotalImpuestos(floatval($ventaSelect->IGV))
                ->setValorVenta(floatval($ventaSelect->Subtotal))
                ->setSubTotal($total)
                ->setMtoImpVenta($total)
                ->setCompany($company);
        }

        $array = [];
        $legends = [];
        $countGratuita = 0;
        $itemasVentaSelect = $loadDatos->getItemsVentas($ventaSelect->IdVentas);
        $condicionDetrac = 0;
        for ($i = 0; $i < count($itemasVentaSelect); $i++) {
            if ($itemasVentaSelect[$i]->IdPaquetePromocional > 0) {
                $productoSelect = DB::table('paquetes_promocionales')
                    ->where('IdPaquetePromocional', $itemasVentaSelect[$i]->IdPaquetePromocional)
                    ->first();
                $condicionDetrac = 1;
                $medidaSunat = 'ZZ';
                $descripcion = $productoSelect->NombrePaquete;
            } else {
                $productoSelect = $loadDatos->getProductoSelect($itemasVentaSelect[$i]->IdArticulo);
                if ($productoSelect->IdTipo == 2) {
                    $condicionDetrac = 1;
                }
                $medidaSunat = $productoSelect->MedidaSunat;
                $descripcion = $productoSelect->Descripcion;
            }

            if ($itemasVentaSelect[$i]->VerificaTipo == 1 || $itemasVentaSelect[$i]->VerificaTipo == 3 || $itemasVentaSelect[$i]->VerificaTipo == 4) {
                $newCantidad = $itemasVentaSelect[$i]->Cantidad;
            } else {
                $newCantidad = $itemasVentaSelect[$i]->Cantidad * $itemasVentaSelect[$i]->CantidadReal;
            }

            $valorUniDescuento = floatval(round($itemasVentaSelect[$i]->Importe / $newCantidad, 2));
            if ($ventaSelect->TipoVenta == 1) {
                $subTotalItem = floatval(round($valorUniDescuento / 1.18, 5));
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
            if ($itemasVentaSelect[$i]->Gratuito == 1) {
                $valorGratuito = floatval($subTotalItem);
                $valorUniDescuento = 0;
                $subTotalItem = 0;
                $totalImpuesto = 0;
                $countGratuita++;
                if ($ventaSelect->TipoVenta == 1) {
                    $afectIgv = '11';
                } else {
                    $afectIgv = '21';
                }
            }

            $item = (new SaleDetail())
                ->setCodProducto($itemasVentaSelect[$i]->Codigo)
                ->setUnidad($medidaSunat)
                ->setCantidad($newCantidad)
                ->setDescripcion($descripcion)
                ->setMtoBaseIgv(round($mtoValorVenta, 5))
                ->setPorcentajeIgv($porcentaje) // 18%
                ->setIgv(round($igvTotal, 5))
                ->setTipAfeIgv($afectIgv)
                ->setTotalImpuestos(round($totalImpuesto, 5))
                ->setMtoValorVenta(round($mtoValorVenta, 5))
                ->setMtoValorGratuito(round($valorGratuito, 5))
                ->setMtoValorUnitario(round($subTotalItem, 5))
                ->setMtoPrecioUnitario(round($valorUniDescuento, 5));

            if ($ventaSelect->Placa != null || $ventaSelect->Placa != '') {
                $item->setAtributos([(new DetailAttribute())
                        ->setName('Gastos Art. 37 Renta: Número de Placa')
                        ->setCode('7000')
                        ->setValue($ventaSelect->Placa)]);
            }

            array_push($array, $item);
            usleep(100000);
        }

        if ($ventaSelect->IdTipoPago == 1) {
            $invoice->setFormaPago(new FormaPagoContado());
            if ($ventaSelect->Retencion == 1) {
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
            if ($condicionDetrac == 1 && floatval($total) >= 700 && $ventaSelect->TipoVenta == 1) {
                $totalCredito = floatval($total) - floatval($total * $ventaSelect->PorcentajeDetraccion / 100);
            } else {
                if ($ventaSelect->Retencion == 1) {
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
            $_date = Carbon::parse($ventaSelect->FechaCreacion);
            $fechaPago = $_date->addDays($ventaSelect->PlazoCredito);

            $invoice->setFormaPago(new FormaPagoCredito(round($totalCredito, 2)));
            $invoice->setCuotas([
                (new Cuota())
                    ->setMonto(round($totalCredito, 2))
                    ->setFechaPago(new DateTime($fechaPago)),
            ]);
        }

        $convertirLetras = new NumeroALetras();
        if ($ventaSelect->IdTipoMoneda == 1) {
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

        $rucEmpresa = $empresa->Ruc;
        $serie = $ventaSelect->Serie;
        $numero = $ventaSelect->Numero;
        $idTipoComprobante = $ventaSelect->IdTipoComprobante;
        $cod = $serie . '-' . $numero;
        if ($idTipoComprobante == 1) {
            $file = $rucEmpresa . '-03-' . $cod;
        } else {
            $file = $rucEmpresa . '-01-' . $cod;
        }
//$ruta = public_path().'/RespuestaSunat/FacturasBoletas/'.$rucEmpresa.'/'.$file.'.xml';
        $now = Carbon::now();
        $anio = $now->year;
        $mes = $now->month;
        $_mes = $loadDatos->getMes($mes);
        $nombreArchivo = $file;
        $rutaXml = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $rucEmpresa . '/FacturasBoletas/' . $nombreArchivo . '.xml';

        $xml_string = $see->getXmlSigned($invoice);
        $doc = new DOMDocument();
        $doc->loadXML($xml_string);
        $config->writeXml($invoice, $see->getFactory()->getLastXml(), $rucEmpresa, $anio, $_mes, 1);

        $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
        $date = new DateTime();
        $_fecha = $date->format('Y-m-d');
        $resumen = $rucEmpresa . '|01|' . $ventaSelect->Serie . '|' . $ventaSelect->Numero . '|' . round($ventaSelect->IGV, 2) . '|' . round($ventaSelect->Total, 2) . '|' . $_fecha . '|' . $cliente->CodigoSunat . '|' . $cliente->NumeroDocumento;

        DB::table('ventas')
            ->where('IdVentas', $ventaSelect->IdVentas)
            ->update(['Resumen' => $resumen, 'Hash' => $hash, 'RutaXml' => $rutaXml]);

        usleep(1000000);

        return $rutaXml;
    }

    public function descargarCDR(Request $req, $ruc, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $ventaSelect = $loadDatos->getVentaselect($id);
            $serie = $ventaSelect->Serie;
            $numero = $ventaSelect->Numero;
            $idTipoComprobante = $ventaSelect->IdTipoComprobante;
            $cod = $serie . '-' . $numero;
            if ($idTipoComprobante == 1) {
                $file = 'R-' . $ruc . '-03-' . $cod . '.zip';
            } else {
                $file = 'R-' . $ruc . '-01-' . $cod . '.zip';
            }

            if (Storage::disk('s3')->exists($ventaSelect->RutaCdr)) {

                $rutaS3 = Storage::disk('s3')->get($ventaSelect->RutaCdr);
                $headers = [
                    'Content-Type' => 'text/xml',
                    'Content-Description' => 'File Transfer',
                    'Content-Disposition' => "attachment; filename=" . $file . "",
                    'filename' => '' . $file . '',
                ];

                return response($rutaS3, 200, $headers);
            } else {

                $rutaCDR = $this->generarCDR($ventaSelect);
                if ($rutaCDR != null) {
                    $rutaS3 = Storage::disk('s3')->get($rutaCDR);
                    $headers = [
                        'Content-Type' => 'text/xml',
                        'Content-Description' => 'File Transfer',
                        'Content-Disposition' => "attachment; filename=" . $file . "",
                        'filename' => '' . $file . '',
                    ];

                    return response($rutaS3, 200, $headers);
                } else {
                    return back()->with('error', 'No se encontró archivo Cdr');
                }
            }
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        //return response()->download(public_path().'/RespuestaSunat/FacturasBoletas/'.$rucEmpresa.'/'.$file.'.zip');
    }

    public function generarCDR($ventaSelect)
    {
        $idUsuario = Session::get('idUsuario');
        $loadDatos = new DatosController();
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $service = $this->getCdrStatusService($empresa->Ruc . '' . $empresa->UsuarioSol, $empresa->ClaveSol);
        //$service = $this->getCdrStatusService('20000000001MODDATOS', 'MODDATOS');

        $arguments = [
            $fields['ruc'] = $empresa->Ruc,
            $fields['tipo'] = '01',
            $fields['serie'] = $ventaSelect->Serie,
            intval($fields['numero'] = $ventaSelect->Numero),
        ];

        $res = $service->getStatusCdr(...$arguments);
        //dd($res);
        if ($res->getCdrZip()) {
            $name = 'R-' . $empresa->Ruc . '-01-' . $ventaSelect->Serie . '-' . $ventaSelect->Numero . '.zip';

            $now = Carbon::now();
            $anio = $now->year;
            $mes = $now->month;
            $_mes = $loadDatos->getMes($mes);

            $rutaCdr = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/FacturasBoletas/' . $name;
            Storage::disk('s3')->put($rutaCdr, $res->getCdrZip(), 'public');

            DB::table('ventas')
                ->where('IdVentas', $ventaSelect->IdVentas)
                ->update(['RutaCdr' => $rutaCdr]);
            usleep(1000000);
            return $rutaCdr;
        } else {
            return null;
        }
    }

    public function enviarCorreo(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $ventaSelect = $loadDatos->getVentaselect($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $nombreEmpresa = $empresa->Nombre;
        $rucEmpresa = $empresa->Ruc;
        $numero = $ventaSelect->Numero;
        $serie = $ventaSelect->Serie;
        $cod = $serie . '-' . $numero;
        if ($ventaSelect->IdTipoComprobante == 1) {
            $idDoc = '03';
            $file = $rucEmpresa . '-03-' . $cod;
        }
        if ($ventaSelect->IdTipoComprobante == 2) {
            $idDoc = '01';
            $file = $rucEmpresa . '-01-' . $cod;
        }
        if ($ventaSelect->IdTipoComprobante == 3) {
            $idDoc = '12';
            $file = $rucEmpresa . '-12-' . $cod;
        }
        //$mail->addAttachment(public_path().'/RespuestaSunat/FacturasBoletas/'.$rucEmpresa.'/'.$file.'.xml');
        $pdf = $this->generarPDF($req, 1, $id);
        file_put_contents($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numero . '.pdf', $pdf->output());

        $mail = new PHPMailer();
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'mail.easyfactperu.pe'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'facturacion@easyfactperu.pe'; // SMTP username
        $mail->Debugoutput = 'html';
        $mail->Password = 'gV.S=o=Q,bl2'; // SMTP password
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; // TCP port to connect to
        $mail->From = 'facturacion@easyfactperu.pe';
        $mail->FromName = 'EASYFACT PERÚ S.A.C.  - Facturación Electrónica';
        $mail->addAddress($req->correo, 'Comprobante'); // Add a recipient

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8'; // Set email format to HTML
        $mail->Subject = 'Envío de comprobante';
        $mail->addAttachment($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numero . '.pdf');

        //$mail->msgHTML('Hola: '.$req->cliente.', Te estamos enviando adjunto el comprobante ('.$req->comprobante.'.pdf) de la compra que hiciste en BroadCast Perú');
        if ($ventaSelect->IdTipoComprobante == 1) {
            $tipo = 'BOLETA ELECTRÓNICA';
            if (Storage::disk('s3')->exists($ventaSelect->RutaXml)) {
                $rutaXmlS3 = Storage::disk('s3')->get($ventaSelect->RutaXml);
                file_put_contents($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numero . '.xml', $rutaXmlS3);
                $mail->addAttachment($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numero . '.xml');
            }
        }
        if ($ventaSelect->IdTipoComprobante == 2) {
            $tipo = 'FACTURA ELECTRÓNICA';
            if (Storage::disk('s3')->exists($ventaSelect->RutaXml)) {
                $rutaXmlS3 = Storage::disk('s3')->get($ventaSelect->RutaXml);
                file_put_contents($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numero . '.xml', $rutaXmlS3);
                $mail->addAttachment($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numero . '.xml');
            }

            if (Storage::disk('s3')->exists($ventaSelect->RutaCdr)) {
                $rutaCdrS3 = Storage::disk('s3')->get($ventaSelect->RutaCdr);
                file_put_contents($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numero . '.zip', $rutaCdrS3);
                $mail->addAttachment($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numero . '.zip');
            }
        }
        if ($ventaSelect->IdTipoComprobante == 3) {
            $tipo = 'TICKET PRE-VENTA';
        }
        //$numeroCerosIzquierda = $this->completarCeros($ventaSelect->Numero);
        $fecha = date_create($ventaSelect->FechaCreacion);
        $formatoFecha = date_format($fecha, 'd/m/Y');
        $total = floatval($ventaSelect->Total) + floatval($ventaSelect->Amortizacion);
        $mail->msgHTML('<table width="100%">'
            . '<tr>'
            . '<td style="border: 1px solid #000;">'
            . '<div align="center" style="background-color: #CCC">'
            . '<img width="150px" style="margin:15px" src="' . $empresa->Imagen . '">'
            . '<img width="150px" style="margin:15px" src="https://2019mifacturita.s3.us-west-2.amazonaws.com/1624941410.png">'
            . '</div>'
            . '<div style="margin-bottom:20px;margin-left:10px">'
            . '<p>Estimado(a),</p>'
            . '</div>'
            . '<div style="margin-bottom:20px;margin-left:10px">'
            . '<p>' . $req->cliente . '</p>'
            . '</div>'
            . '<div style="margin-bottom:10px;margin-left:10px">'
            . '<p>Por encargo del emisor <span style="font-weight: bold;">' . $nombreEmpresa . '</span>, nos es grato informar que el documento electrónico ya se encuentra disponible con los siguientes datos:</p>'
            . '</div>'
            . '<hr style="border: 0.5px solid #000;">'
            . '<div style="margin-bottom:20px;margin-left:30px">'
            . '<p><span style="font-weight: bold;">Tipo: ' . $tipo . '</span></p>'
            . '<p><span style="font-weight: bold;">Número: ' . $ventaSelect->Serie . '-' . $numero . '</span></p>'
            . '<p><span style="font-weight: bold;">RUC / DNI: ' . $rucEmpresa . '</span></p>'
            . '<p><span style="font-weight: bold;">Fecha Emisión: ' . $formatoFecha . '</span></p>'
            . '<p><span style="font-weight: bold;">Monto Total: ' . $ventaSelect->Total . '</span></p>'
            . '</div>'
            . '<hr style="border: 0.5px solid #000;">'
            . '<div style="margin-bottom:20px;margin-left:10px">'
            . '<p>Los comprobantes también podrán ser consultados en el enlace: <a href="http://easyfactperu.pe/facturacion/">www.easyfactperu.pe</a>, ingresando mediante su usuario o utilizando nuestro acceso anónimo.</p>'
            . '</div>'
            . '<hr style="border: 0.5px solid #000;">'
            . '<div style="margin-bottom:20px;margin-left:10px">'
            . '<p><span style="font-weight: bold;">Atentamente</span></p>'
            . '<p><span style="font-weight: bold;">AGRADECEREMOS NO RESPONDER ESTE CORREO</span></p>'
            . '<p><span style="font-weight: bold;">Si deseas ser Emisor Electrónico contáctanos o escríbenos al correo informes@easyfactperu.pe</span></p>'
            . '</div>'
            . '</td>'
            . '</tr>'
            . '</table>');

        $enviado = $mail->send();
        if ($enviado) {
            if (unlink($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numero . '.pdf')) {
                //dd("eliminado");
            }
            if (unlink($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numero . '.xml')) {
                //dd("eliminado");
            }
            if (unlink($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numero . '.zip')) {
                //dd("eliminado");
            }
            return back()->with('status', 'Se envio correo con éxito');
        } else {
            return back()->with('error', 'No se pudo enviar correo');
        }
    }

    public function generarPDF($req, $tipo, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        $idSucursal = Session::get('idSucursal');
        $loadDatos = new DatosController();
        $ventaSelect = $loadDatos->getVentaselect($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $fecha = date_create($ventaSelect->FechaCreacion);
        $formatoFecha = date_format($fecha, 'd-m-Y');
        $formatoHora = date_format($fecha, 'H:i A');
        if ($ventaSelect->IdTipoPago == 1) {
            $fechaPago = '';
        } else {
            $fechaPagoConvert = Carbon::parse($ventaSelect->FechaCreacion);
            $fechaPagoDias = $fechaPagoConvert->addDays($ventaSelect->PlazoCredito);
            $fechaPagoDate = new DateTime($fechaPagoDias);
            $fechaPago = date_format($fechaPagoDate, 'd-m-Y');
        }
        $convertirLetras = new NumeroALetras();
        if ($ventaSelect->IdTipoMoneda == 1) {
            $totalDetrac = $ventaSelect->Total + $ventaSelect->Amortizacion;
            $importeLetras = $convertirLetras->convertir(floatval($ventaSelect->Total) + floatval($ventaSelect->Amortizacion), 'soles');
        } else {
            $fechaDetrac = Carbon::parse($ventaSelect->FechaCreacion);
            $fechaDetrac = date_format($fechaDetrac, 'Y-m-d');
            $valorCambio = DB::table('tipo_cambio')
                ->where('IdSucursal', $idSucursal)
                ->where('FechaCreacion', $fechaDetrac)
                ->first();

            if ($valorCambio) {
                $totalDetrac = ($ventaSelect->Total + $ventaSelect->Amortizacion) * $valorCambio->TipoCambioVentas;
            } else {
                $totalDetrac = $ventaSelect->Total + $ventaSelect->Amortizacion;
            }

            $importeLetras = $convertirLetras->convertir(floatval($ventaSelect->Total) + floatval($ventaSelect->Amortizacion), 'dólares');
        }

        $inicialRuc = substr($empresa->Ruc, 0, 2);
        if ($inicialRuc == '10') {
            $nombreEmpresa = $empresa->NombreComercial;
        } else {
            $nombreEmpresa = $empresa->Nombre;
        }
        if ($ventaSelect->Seguro != null && $ventaSelect->Seguro > 2) {
            $datosSeguro = $this->getDatosSeguro($ventaSelect->IdVentas);
            $seguroNombre = $datosSeguro->Descripcion;
            $idSeguro = $datosSeguro->IdSeguro;
        } else {
            $seguroNombre = "";
            $idSeguro = 1;
        }

        $numeroCerosIzquierda = $this->completarCeros($ventaSelect->Numero);
        $resumen = $ventaSelect->Resumen;
        $hash = $ventaSelect->Hash;

        $items = $loadDatos->getItemsYpaquetePromocional($id);
        //$items = $this->getItemsYpaquetePromocional($id);

        $itemsProd = $items->where('IdTipo', 1);

        $itemsServ = $items->where('IdTipo', 2);
        $cuentasCorrientes = $this->getCuentasCorrientes($usuarioSelect->CodigoCliente);
        $cuentaDetracciones = $loadDatos->getCuentaDetracciones($usuarioSelect->CodigoCliente, 9);
        $exp = explode("\n", $ventaSelect->Observacion);
        $lineas = count($exp);
        if ($lineas <= 5) {
            $lineas = $lineas * 8;
        } else if ($lineas > 5 && $lineas <= 10) {
            $lineas = $lineas * 10;
        } else {
            $lineas = $lineas * 12;
        }

        if ($ventaSelect->Anticipo > 2) {
            $anticipoSelect = $loadDatos->getVentaselect($ventaSelect->Anticipo);
            $fechaAnticipo = date_create($ventaSelect->FechaCreacion);
            $formatoFechaAnticipo = date_format($fecha, 'd-m-Y');
        } else {
            $anticipoSelect = [];
            $formatoFechaAnticipo = '';
        }

        $codMedioPago = null;
        $codDetraccion = null;

        if($ventaSelect->Detraccion == 1){
            $codMedioPago = $loadDatos->getCodigoMedioPagoSelect($ventaSelect->CodMedioPago);
            $codDetraccion = $loadDatos->getCodigoBienServicioSelect($ventaSelect->CodDetraccion);
        }

        // Verifica si la venta se realizado pago con deposito y agregar el numero de cuenta y numero de operacion
        if ($this->isPagoConDeposito($ventaSelect)) {
            $this->agregarDetallesBanco($ventaSelect, $idSucursal);
        }

        $sucursal = $loadDatos->getSucursales($usuarioSelect->CodigoCliente, $usuarioSelect->IdOperador)->where("Principal", 0)->first();
        $array = ['itemsProd' => $itemsProd, 'itemsServ' => $itemsServ, 'seguroNombre' => $seguroNombre, 'idSeguro' => $idSeguro, 'numeroCeroIzq' => $numeroCerosIzquierda, 'lineas' => $lineas, 'ventaSelect' => $ventaSelect, 'resumen' => $resumen, 'hash' => $hash, 'cuentasCorrientes' => $cuentasCorrientes, 'cuentaDetracciones' => $cuentaDetracciones, 'anticipoSelect' => $anticipoSelect, 'formatoFechaAnticipo' => $formatoFechaAnticipo,
            'totalDetrac' => $totalDetrac, 'fechaPago' => $fechaPago, 'formatoFecha' => $formatoFecha, 'formatoHora' => $formatoHora, 'importeLetras' => $importeLetras, 'empresa' => $empresa, 'nombreEmpresa' => $nombreEmpresa, 'sucursal' => $sucursal, 'codMedioPago' => $codMedioPago, 'codDetraccion' => $codDetraccion];
        //dd($array);
        view()->share($array);

        if ($tipo == 1) {
            $pdf = PDF::loadView('ventasPDF')->setPaper('a4', 'portrait');
            //$pdf = PDF::loadView('ventasPDF')->setPaper(array(0,0,595.28,841.89));
        }
        if ($tipo == 2) {
            $pdf = PDF::loadView('ventasPDFA5')->setPaper('a5', 'portrait');
        }
        if ($tipo == 3) {
            $pdf = PDF::loadView('ventasTicket')->setPaper(array(0, 0, 107, 600));
        }
        if ($tipo == 4) {
            $pdf = PDF::loadView('valePdf')->setPaper('a4', 'portrait');

        }
        if ($tipo == 5) {
            // $pdf = PDF::loadView('valePdf')->save( public_path('facturanueva00.pdf') );
            $pdf = PDF::loadView('ventasPDF');
            //     $content = PDF::loadView('valePdf')->output();
            //     Storage::disk('public')->put('mi-archivo.pdf', $content);
            //    $url = Storage::disk('public')->url($content );

            //     $url = $this->setPdf($pdf);
            //    $array = ['Pdf' =>$url];
            //     DB::table('ventas')
            //                 ->where('IdVentas', $id)
            //                 ->update($array);
        }
        return $pdf;
    }

// public function setPdf($pdf, $rucEmpresa, $idDoc, $serie, $numeroCerosIzquierda)
// {
//     $getPdf = 'pdfWhatsApp/'. $rucEmpresa.'-'.$idDoc.'-'.$serie.'-'.$numeroCerosIzquierda . '.pdf';
//     Storage::disk('s3')->put( $getPdf, $pdf->output() , 'public');
//     return Storage::disk('s3')->url($getPdf );
// }

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

    public function ultimoCorrelativo($idUsuario, $idSucursal, $idTipoComp)
    {
        try {
            $resultado = DB::table('ventas')
                ->where('IdCreacion', $idUsuario)
                ->where('IdSucursal', $idSucursal)
                ->where('IdTipoComprobante', $idTipoComp)
                ->orderBy('IdVentas', 'desc')
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function completarCeros($numero)
    {
        $numeroConCeros = str_pad($numero, 8, "0", STR_PAD_LEFT);
        return $numeroConCeros;
    }

    public function consultarProvincias(Request $req)
    {
        if ($req->ajax()) {
            $idDep = $req->departamento;
            $loadDatos = new DatosController();
            $provincias = $loadDatos->getProvincias($idDep);
            return Response($provincias);
        }
    }

    public function consultarDistritos(Request $req)
    {
        if ($req->ajax()) {
            $idPro = $req->provincia;
            $loadDatos = new DatosController();
            $distritos = $loadDatos->getDistritos($idPro);
            return Response($distritos);
        }
    }

    public function crearCliente(Request $req)
    {
        if ($req->ajax()) {
            $idSucursal = Session::get('idSucursal');
            if ($req->numDoc == '') {
                return Response('Número de Documento es obligatorio');
            }
            if ($req->razonSocial == '') {
                return Response('La Razón Social es obligatorio');
            }
            if ($req->departamento == 0) {
                return Response('Asignar departamento para el cliente');
            }
            if ($req->provincia == 0) {
                return Response('Asignar provincia para el cliente');
            }
            if ($req->distrito == 0) {
                return Response('Asignar distrito para el cliente');
            }
            $tipoDoc = $req->tipoDoc;
            $numDoc = $req->numDoc;
            if ($tipoDoc == 1) {
                if (strlen($numDoc) != 8) {
                    return Response('El DNI tiene que tener 8 dígitos');
                }
            }
            if ($tipoDoc == 2) {
                if (strlen($numDoc) != 11) {
                    return Response('El RUC tiene que tener 11 dígitos');
                }
            }
            if ($tipoDoc == 3 || $tipoDoc == 4) {
                if (strlen($numDoc) != 12) {
                    return Response('error', 'El CARNET DE EXTRANJERÍA o PASAPORTE tiene que tener 12 dígitos');
                }
            }

            if ($tipoDoc < 3) {
                $documento = DB::table('cliente')
                    ->where('IdTipoDocumento', $tipoDoc)
                    ->where('NumeroDocumento', $numDoc)
                    ->where('IdSucursal', $idSucursal)
                    ->where('Estado', 'E')
                    ->first();

                if (!empty($documento)) {
                    return Response('El Numero de  Documento ya existe en nuestros registros');
                }
            }

            $loadDatos = new DatosController();
            $fecha = $loadDatos->getDateTime();
            $idUsuario = Session::get('idUsuario');
            $nombre = $req->nombreComercial;
            if ($nombre == "" || $nombre == null) {
                $nombre = $req->razonSocial;
            }
            $razonSocial = $req->razonSocial;
            $direccion = $req->direccion;
            $telefono = $req->telefono;
            $email = $req->email;
            $estado = 'E';

            $array = ['IdTipoDocumento' => $tipoDoc, 'IdSucursal' => $idSucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario, 'Nombre' => $nombre, 'RazonSocial' => $razonSocial, 'NumeroDocumento' => $numDoc,
                'Ubigeo' => $req->distrito, 'Direccion' => $direccion, 'Telefono' => $telefono, 'Email' => $email, 'Estado' => $estado];
            DB::table('cliente')->insert($array);

            $cliente = DB::table('cliente')
                ->where('Estado', 'E')
                ->orderBy('IdCliente', 'desc')
                ->get();

            return Response($cliente);
        }
    }

    public function crearVehiculo(Request $req)
    {
        if ($req->ajax()) {
            $idSucursal = Session::get('idSucursal');
            $idUsuario = Session::get('idUsuario');
            if ($req->cliente == 0) {
                return Response(['alert', 'Seleccione cliente']);
            }
            if ($req->color == null || $req->color == '') {
                return Response(['alert', 'Seleccione un color']);
            }
            if ($req->marca == 0) {
                return Response(['alert', 'Seleccione una marca']);
            }
            if ($req->modelo == 0) {
                return Response(['alert', 'Seleccione una modelo']);
            }
            if ($req->tipo == 0) {
                return Response(['alert', 'Seleccione un tipo']);
            }
            $anio = $req->anio;
            $tipoVehiculo = $req->tipoVehiculo;
            if ($tipoVehiculo == 1) {
                $placa = $req->placa;
            } else {
                $placa = $req->placaMoto;
            }

            $color = $req->color;
            $marca = $req->marca;
            $modelo = $req->modelo;
            $tipo = $req->tipo;
            $cliente = $req->cliente;

            $vehiculoPlacas = DB::table('vehiculo')
                ->where('IdSucursal', $idSucursal)
                ->where('PlacaVehiculo', $placa)
                ->first();

            if (!empty($vehiculoPlacas)) {
                return Response(['alert', 'La placa ya existe, registre otra distinta']);
            }

            $array = ['IdSucursal' => $idSucursal, 'IdSeguro' => 1, 'TipoVehicular' => $tipoVehiculo, 'PlacaVehiculo' => $placa, 'Color' => $color, 'Anio' => $anio,
                'IdMarcaVehiculo' => $marca, 'IdModeloVehiculo' => $modelo, 'IdTipoVehiculo' => $tipo, 'IdCreacion' => $idUsuario, 'IdCliente' => $cliente, 'Estado' => 1];
            DB::table('vehiculo')->insert($array);

            $vehiculo = DB::table('vehiculo')
                ->where('IdSucursal', $idSucursal)
                ->orderBy('IdVehiculo', 'desc')
                ->first();

            return Response(['success', $vehiculo]);
        }
    }

    public function envioSunat($req)
    {

        if ($req->tipoComprobante == 1) {
            $res = $this->obtenerXMLBoleta($req);
            return $res;
        }
        if ($req->tipoComprobante == 2) {
            $res = $this->obtenerXMLFactura($req);
            return $res;
        }

    }

    public function consultarDoc(Request $req)
    {
        if ($req->ajax()) {
            $idDoc = $req->idDoc;
            $numDoc = $req->numDoc;
            $loadDatos = new DatosController();
            $longitud = strlen($numDoc);

            if ($idDoc == 3 || $idDoc == 4) {
                $mensaje = 0;
                $data = array(
                    0 => $mensaje,
                    1 => $idDoc,
                    2 => $numDoc,
                    3 => 'Servicio disponbile solo para DNI y RUC',
                );
                return Response($data);
            }

            if ($idDoc == 1) {
                if ($longitud == 8) {
                    $url = 'https://dniruc.apisperu.com/api/v1/dni/' . $numDoc . '?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImZhY3R1cmFjaW9uQGVhc3lmYWN0cGVydS5wZSJ9.45m3851f7G3SqNhT4MZ20YTZt6h4HD3YIf8pfwZgGJI';
                    //$url = 'https://dniruc.apisperu.com/api/v1/dni/45272540?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Im1hcmNvLm1hbGxtYTIwMUBnbWFpbC5jb20ifQ.tnR51gvdQl1DO4ovYJFjGlu9EFzwx1wSOA3Nd_BIxrg';
                } else {
                    $mensaje = 0;
                    $data = array(
                        0 => $mensaje,
                        1 => $idDoc,
                        2 => $numDoc,
                        3 => 'Error en la busqueda, el DNI tiene que ser de 8 dígitos',
                    );
                    return Response($data);
                }
            }

            if ($idDoc == 2) {
                if ($longitud == 11) {
                    $url = 'https://dniruc.apisperu.com/api/v1/ruc/' . $numDoc . '?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImZhY3R1cmFjaW9uQGVhc3lmYWN0cGVydS5wZSJ9.45m3851f7G3SqNhT4MZ20YTZt6h4HD3YIf8pfwZgGJI';
                    //$url = 'https://dniruc.apisperu.com/api/v1/ruc/'.$numDoc.'?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Im1hcmNvLm1hbGxtYTIwMUBnbWFpbC5jb20ifQ.tnR51gvdQl1DO4ovYJFjGlu9EFzwx1wSOA3Nd_BIxrg';
                } else {
                    $mensaje = 0;
                    $data = array(
                        0 => $mensaje,
                        1 => $idDoc,
                        2 => $numDoc,
                        3 => 'Error en la busqueda, el RUC tiene que ser de 11 dígitos',
                    );
                    return Response($data);
                }
            }

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);

            $_result = curl_exec($curl);
            $result = json_decode($_result, true);

            curl_close($curl);
            $elementCount = count($result);

            if ($elementCount == 5 || $elementCount == 26) {
                $mensaje = 1;
                if ($idDoc == 1) {
                    $data = array(
                        0 => $mensaje,
                        1 => $idDoc,
                        2 => $numDoc,
                        3 => $result["apellidoPaterno"] . ' ' . $result["apellidoMaterno"] . ' ' . $result["nombres"],
                    );
                }
                if ($idDoc == 2) {
                    $arrayProvincias = [];
                    $arrayDistritos = [];
                    if ($result["departamento"] != null) {
                        $dep = DB::table('departamento')
                            ->where('Nombre', $result["departamento"])
                            ->first();
                        $departamento = $dep->IdDepartamento;

                        $arrayProvincias = $loadDatos->getProvincias($departamento);
                        $pro = DB::table('provincia')
                            ->where('Nombre', $result["provincia"])
                            ->first();
                        $provincia = $pro->IdProvincia;

                        $arrayDistritos = $loadDatos->getDistritos($provincia);
                        $dis = DB::table('distrito')
                            ->where('Nombre', $result["distrito"])
                            ->first();
                        $distrito = $dis->IdDistrito;

                    } else {
                        $departamento = null;
                        $provincia = null;
                        $distrito = null;
                    }
                    $data = array(
                        0 => $mensaje,
                        1 => $idDoc,
                        2 => $numDoc,
                        3 => $result["razonSocial"],
                        4 => $result["nombreComercial"],
                        5 => $result["direccion"],
                        6 => $departamento,
                        7 => array(0 => $provincia, 1 => $arrayProvincias),
                        8 => array(0 => $distrito, 1 => $arrayDistritos),
                        9 => $result["condicion"],
                        10 => $result["estado"],
                    );
                }
                return Response($data);
            } else {
                $mensaje = 0;
                $data = array(
                    0 => $mensaje,
                    1 => $idDoc,
                    2 => $numDoc,
                    3 => $result["message"],
                );
                return Response($data);
            }
        }
    }

    public function obtenerXMLFactura($req)
    {
        $fecha = $req->fechaEmitida;
        $date = DateTime::createFromFormat('Y-m-d', $fecha);

        $config = new config();
        $see = $config->configuracion(SunatEndpoints::FE_PRODUCCION);

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
            ->setDireccion($sucursal->Direccion);

        $company = new Company();
        $company->setRuc($empresa->Ruc)
            ->setRazonSocial($empresa->Nombre)
            ->setNombreComercial('NONE')
            ->setAddress($address);

        // Venta
        $exonerada = $req->exonerada;
        if ($exonerada == '-') {
            $exonerada = '0.00';
        }
        $total = floatval($req->total) - floatval($exonerada);

        $invoice = (new Invoice())
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101') // Catalog. 51
            ->setTipoDoc('01')
            ->setSerie($req->serie)
            ->setCorrelativo($req->numero)
            ->setFechaEmision($date)
            ->setTipoMoneda('PEN')
            ->setClient($client)
            ->setMtoOperGravadas(floatval($req->subtotal)) // Subtotal
            ->setMtoIGV(floatval($req->igv))
            ->setTotalImpuestos(floatval($req->igv))
            ->setValorVenta(floatval($req->total))
            ->setMtoImpVenta($total)
            ->setCompany($company);

        $array = [];

        for ($i = 0; $i < count($req->Id); $i++) {
            //$producto = substr($req->Codigo[$i],0,3);
            $productoSelect = $loadDatos->getProductoSelect($req->Id[$i]);
            $subTotalItem = floatval($productoSelect->Precio / 1.18);
            $igvItem = floatval($productoSelect->Precio) - floatval($subTotalItem);
            $mtoValorVenta = floatval(intval($req->Cantidad[$i]) * $subTotalItem);
            $igvTotal = floatval(intval($req->Cantidad[$i]) * $igvItem);
            $item = (new SaleDetail())
                ->setCodProducto($req->Codigo[$i])
                ->setUnidad($productoSelect->MedidaSunat)
                ->setCantidad(intval($req->Cantidad[$i]))
                ->setDescripcion($productoSelect->Descripcion)
                ->setMtoBaseIgv($mtoValorVenta)
                ->setPorcentajeIgv(18.00) // 18%
                ->setIgv($igvTotal)
                ->setTipAfeIgv('10')
                ->setTotalImpuestos($igvTotal)
                ->setMtoValorVenta($mtoValorVenta)
                ->setMtoValorUnitario($subTotalItem)
                ->setMtoPrecioUnitario($productoSelect->Precio);
            array_push($array, $item);
        }

        $convertirLetras = new NumeroALetras();
        $importeLetras = $convertirLetras->convertir($total, 'soles');
        $legend = (new Legend())
            ->setCode('1000')
            ->setValue($importeLetras);

        $invoice->setDetails($array)
            ->setLegends([$legend]);

        //$see->getXmlSigned($invoice);
        //dd($see->getFactory()->getLastXml());
        $xml_string = $see->getXmlSigned($invoice);
        //dd($see->getFactory()->getLastXml());
        $config->writeXml($invoice, $see->getFactory()->getLastXml(), 1);
        $result = $see->send($invoice);
        if ($result->isSuccess()) {
            //$config->writeXml($invoice, $see->getFactory()->getLastXml());
            $cdr = $result->getCdrResponse();
            $config->writeCdr($invoice, $result->getCdrZip(), 1);
            $config->showResponse($invoice, $cdr);

            $_array = [];
            $respuesta = 1;
            //$xml_string = $see->getXmlSigned($invoice);
            $doc = new DOMDocument();
            $doc->loadXML($xml_string);
            $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
            $date = new DateTime();
            $fecha = $date->format('Y-m-d');
            $resumen = $empresa->Ruc . '|01|' . $req->serie . '|' . $req->numero . '|' . round($req->igv, 2) . '|' . round($total, 2) . '|' . $fecha . '|' . $cliente->CodigoSunat . '|' . $cliente->NumeroDocumento;
            array_push($_array, $hash);
            array_push($_array, $resumen);
            array_push($_array, $respuesta);
            return $_array;
        } else {
            //dd($result);
            $_array = [];
            if ($result->getError()->getCode() == 'HTTP') {
                echo "<script language='javascript'>alert('Servicio inestable, intentelo en otro momento');</script>";
                $respuesta = 2;
                $doc = new DOMDocument();
                $doc->loadXML($xml_string);
                $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                $date = new DateTime();
                $fecha = $date->format('Y-m-d');
                $resumen = $empresa->Ruc . '|01|' . $req->serie . '|' . $req->numero . '|' . round($req->igv, 2) . '|' . round($total, 2) . '|' . $fecha . '|' . $cliente->CodigoSunat . '|' . $cliente->NumeroDocumento;
                array_push($_array, $hash);
                array_push($_array, $resumen);
                array_push($_array, $respuesta);
            } else {
                //echo '<script language="javascript">alert("'.$result->getError()->getMessage().'");</script>';
                $respuesta = 0;
                $hash = '';
                $resumen = 'Error ' . $result->getError()->getCode() . ': ' . $result->getError()->getMessage();
                array_push($_array, $hash);
                array_push($_array, $resumen);
                array_push($_array, $respuesta);
            }
            return $_array;
        }
    }

    public function obtenerXMLBoleta($req)
    {

        $fecha = $req->fechaEmitida;
        $date = DateTime::createFromFormat('Y-m-d', $fecha);

        $config = new config();
        $see = $config->configuracion(SunatEndpoints::FE_PRODUCCION);
        $loadDatos = new DatosController();
        $cliente = $loadDatos->getClienteSelect($req->cliente);

        $client = new Client();
        $client->setTipoDoc(strval($cliente->CodigoSunat)) //agregado
            ->setNumDoc($cliente->NumeroDocumento)
            ->setRznSocial($cliente->Nombre);

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
            ->setDireccion($sucursal->Direccion);

        $company = new Company();
        $company->setRuc($empresa->Ruc)
            ->setRazonSocial($empresa->Nombre)
            ->setNombreComercial('NONE')
            ->setAddress($address);

        $exonerada = $req->exonerada;
        if ($exonerada == '-') {
            $exonerada = '0.00';
        }
        $total = floatval($req->total) - floatval($exonerada);

        $invoice = (new Invoice())
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101') // Catalog. 51
            ->setTipoDoc('03')
            ->setSerie($req->serie)
            ->setCorrelativo($req->numero)
            ->setFechaEmision($date)
            ->setTipoMoneda('PEN')
            ->setClient($client)
            ->setMtoOperGravadas(floatval($req->subtotal)) // Subtotal
            ->setMtoIGV(floatval($req->igv))
            ->setTotalImpuestos(floatval($req->igv))
            ->setValorVenta(floatval($req->total))
            ->setMtoImpVenta($total)
            ->setCompany($company);

        $array = [];

        for ($i = 0; $i < count($req->Id); $i++) {
            $productoSelect = $loadDatos->getProductoSelect($req->Id[$i]);
            $subTotalItem = floatval($productoSelect->Precio / 1.18);
            $igvItem = floatval($productoSelect->Precio) - floatval($subTotalItem);
            $mtoValorVenta = floatval(intval($req->Cantidad[$i]) * $subTotalItem);
            $igvTotal = floatval(intval($req->Cantidad[$i]) * $igvItem);
            $item = (new SaleDetail())
                ->setCodProducto($req->Codigo[$i])
                ->setUnidad($productoSelect->MedidaSunat)
                ->setCantidad(intval($req->Cantidad[$i]))
                ->setDescripcion($productoSelect->Descripcion)
                ->setMtoBaseIgv($mtoValorVenta)
                ->setPorcentajeIgv(18.00) // 18%
                ->setIgv($igvTotal)
                ->setTipAfeIgv('10')
                ->setTotalImpuestos($igvTotal)
                ->setMtoValorVenta($mtoValorVenta)
                ->setMtoValorUnitario($subTotalItem)
                ->setMtoPrecioUnitario($productoSelect->Precio);
            array_push($array, $item);
        }

        $convertirLetras = new NumeroALetras();
        $importeLetras = $convertirLetras->convertir($total, 'soles');
        $legend = (new Legend())
            ->setCode('1000')
            ->setValue($importeLetras);

        $invoice->setDetails($array)
            ->setLegends([$legend]);

        $xml_string = $see->getXmlSigned($invoice);
        //dd($see->getFactory()->getLastXml());
        $config->writeXml($invoice, $see->getFactory()->getLastXml(), 1);

        $_array = [];
        $respuesta = 2;
        $doc = new DOMDocument();
        $doc->loadXML($xml_string);
        $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
        $date = new DateTime();
        $fecha = $date->format('Y-m-d');
        $resumen = $empresa->Ruc . '|03|' . $req->serie . '|' . $req->numero . '|' . round($req->igv, 2) . '|' . round($total, 2) . '|' . $fecha . '|' . $cliente->CodigoSunat . '|' . $cliente->NumeroDocumento;
        array_push($_array, $hash);
        array_push($_array, $resumen);
        array_push($_array, $respuesta);
        return $_array;

        //dd($config);
        /*$result = $see->send($invoice);
    $config->writeXml($invoice, $see->getFactory()->getLastXml());

    if ($result->isSuccess()) {
    $cdr = $result->getCdrResponse();
    $config->writeCdr($invoice, $result->getCdrZip());
    $config->showResponse($invoice, $cdr);

    $_array = [];
    $xml_string = $see->getXmlSigned($invoice);
    $doc = new DOMDocument();
    $doc->loadXML($xml_string);
    $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
    $date = new DateTime();
    $fecha = $date->format('Y-m-d');
    $resumen = '20000000001|03|'.$req->serie.'|'.$req->numero.'|'.round($req->igv, 2).'|'.round($total, 2).'|'.$fecha.'|'.$cliente->CodigoSunat.'|20000000001';
    array_push($_array, $hash);
    array_push($_array, $resumen);
    return $_array;

    } else {
    echo "<script language='javascript'>alert('error al procesar solicitud, intentelo más tarde');window.location='realizar-venta'</script>";
    //return back()->with('error',$result->getError())->withInput($req->all());
    }*/
    }

    public function verificarTipoCambio(Request $req)
    {
        if ($req->ajax()) {
            $idSucursal = Session::get('idSucursal');
            $loadDatos = new DatosController();
            $fecha = Carbon::today();

            $data = DB::table('tipo_cambio')
                ->where('FechaCreacion', $fecha)
                ->where('IdSucursal', $idSucursal)
                ->get();

            return Response($data);
        }
    }

    public function validateVenta(Request $request)
    {
        $this->validate($request, [
            'serie' => 'required',
            'numero' => 'required',
        ]);
    }

    public function actualizarFechasPasados($idSucursal, $noVencidos)
    {
        for ($i = 0; $i < count($noVencidos); $i++) {
            DB::table('fecha_pago')
                ->join('ventas', 'fecha_pago.IdVenta', '=', 'ventas.IdVentas')
                ->where('ventas.IdSucursal', $idSucursal)
                ->where('fecha_pago.Estado', '!=', 2)
                ->where('fecha_pago.IdFechaPago', $noVencidos[$i]->IdFechaPago)
                ->update(['DiasPasados' => $noVencidos[$i]->Dias]);
        }
        //dd($noVencidos);
        /*DB::table('fecha_pago')
    ->join('ventas','fecha_pago.IdVenta', '=', 'ventas.IdVentas')
    ->where('ventas.IdSucursal',$idSucursal)
    ->update(['Stock' => $cantidadRestada]);*/
    }

    public function obtenerInformacion(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                if ($req->ajax()) {

                    $idUsuario = Session::get('idUsuario');
                    $loadDatos = new DatosController();
                    $idSucursal = Session::get('idSucursal');
                    $req->tipoDoc;

                    if ($req->tipoDoc > 0) {
                        switch ($req->tipoDoc) {
                            case 1:$letra = 'B';
                                break;
                            case 2:$letra = 'F';
                                break;
                            case 3:$letra = 'T';
                                break;
                            default:'';
                        }

                        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
                        $orden = $usuarioSelect->Orden;
                        $sucursal = $loadDatos->getSucursalSelect($idSucursal);
                        $ordenSucursal = $sucursal->Orden;
                        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

                        $numeroDB = $this->correlativoActual($idUsuario, $idSucursal, $req->tipoDoc);

                        if ($numeroDB) {
                            $numero = str_pad($numeroDB->Numero + 1, 8, "0", STR_PAD_LEFT);
                        } else {
                            $numero = str_pad(1, 8, "0", STR_PAD_LEFT);
                        }

                        $serieCeros = str_pad($orden, 2, "0", STR_PAD_LEFT);
                        $serie = $letra . '' . $ordenSucursal . '' . $serieCeros;

                        $clientes = $loadDatos->getTipoClientes($req->tipoDoc, $idSucursal);
                        return Response()->json([
                            'clientes' => $clientes,
                            'serie' => $serie,
                            'numero' => $numero,
                            'tipo' => $req->tipoDoc,
                        ]);
                    } else {
                        return Response()->json([
                            'error' => true,
                        ]);
                    }
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
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

    public function Marca($idSucursal)
    {
        $marca = DB::table('marca_general')
            ->where('IdSucursal', $idSucursal)
            ->where('UsoMarca', 1)
            ->where('Estado', 1)
            ->get();
        return $marca;
    }

    public function Modelo($idSucursal)
    {
        $modelo = DB::table('modelo_general')
            ->where('IdSucursal', $idSucursal)
            ->where('Estado', 1)
            ->where('UsoModelo', 1)
            ->get();
        return $modelo;
    }

    public function Tipo($idSucursal)
    {
        $tipo = DB::table('tipo_general')
            ->where('IdSucursal', $idSucursal)
            ->where('Estado', 1)
            ->where('UsoTipo', 1)
            ->get();
        return $tipo;
    }

    public function placasClientes(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                if ($req->ajax()) {

                    $idUsuario = Session::get('idUsuario');
                    $loadDatos = new DatosController();
                    $idSucursal = Session::get('idSucursal');
                    $idCliente = $req->idCliente;
                    $placasVehiculos = DB::table('vehiculo')
                        ->select('IdVehiculo', 'PlacaVehiculo')
                        ->where('IdCliente', $idCliente)
                        ->get();

                    return Response($placasVehiculos);
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function validarDocumento(Request $req, $id)
    {
        //$fecha = $req->fechaEmitida;
        //$date = DateTime::createFromFormat('Y-m-d',$fecha);

        //$config = new config();
        // $see = $config->configuracion(SunatEndpoints::FE_BETA);
        //dd($id);
        $loadDatos = new DatosController();

        // Emisor
        $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');
        $sucursal = $loadDatos->getSucursalSelect($idSucursal);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);

        $documento = DB::table('Tmp_ventas')
            ->where('IdVentas', $id)
            ->first();

        //$service = $this->getCdrStatusService($empresa->Ruc.$empresa->UsuarioSol, $empresa->ClaveSol);
        $service = $this->getCdrStatusService('20000000001MODDATOS', 'MODDATOS');
        $tipo = "$documento->IdTipoSunat";
        $serie = "$documento->Serie";

        $arguments = [
            $fields['ruc'] = $empresa->Ruc,
            $fields['tipo'] = $tipo,
            $fields['serie'] = $serie,
            intval($fields['numero'] = $documento->Numero),
        ];

        $result = $service->getStatusCdr(...$arguments);

        if ($result->getCdrZip()) {

            $isAccetedCDR = $result->getCdrResponse()->isAccepted();
            $descripcionCDR = $result->getCdrResponse()->getDescription();
            $codeCDR = $result->getCdrResponse()->getCode();

            if (intval($codeCDR) < 100) {
                $codigoAceptado = $codeCDR;
                $estado = 'Aceptado';
                $mensaje = $descripcionCDR;
                $tipoMensaje = 'Factura Aceptado';
                //$filename = 'R-'.implode('-', $arguments).'.zip';
                //savedFile($filename, $result->getCdrZip());
                //aqui
                $this->saveValidateDocument($id, $estado);
            } else if (intval($codeCDR) >= 100 && intval($codeCDR) <= 1999) {
                $bandExceccion = 1;
                $bandBaja = 1;
                $codigoAceptado = $codeCDR;
                $estado = 'Excepcion';
                //$mensaje = $descripcionCDR;
                $tipoMensaje = 'Baja Pendiente';
                $mensaje = "El Documento Tiene el error " . $codigoAceptado . "  -  " . $descripcionCDR . " - Este Numero de Documento puede usarse";
            } else if (intval($codeCDR) >= 2000 && intval($codeCDR) <= 3999) {
                $bandBaja = 1;
                $codigoAceptado = $codeCDR;
                $estado = 'Baja Rechazo';
                //$mensaje = $descripcionCDR;
                $tipoMensaje = 'Baja Pendiente';
                $mensaje = "El Documento Tiene el error " . $codigoAceptado . "  -  " . $descripcionCDR . " - No debe usarse  este  Numero de Documento";
            } else {
                $codigoAceptado = $codeCDR;
                $estado = 'Aceptada (Observada)';
                $mensaje = $descripcionCDR;
                $tipoMensaje = 'Aceptado';

                $this->saveValidateDocument($id, $estado);
            }
        } else {
            $mensaje = "El Documento no esta  registrado en  Sunat  ......Comuniquese con la  Administracion";
            return $mensaje;
        }
    }

    public function getCdrStatusService($user, $password)
    {
        $ws = new SoapClient(SunatEndpoints::FE_CONSULTA_CDR . '?wsdl');
        $ws->setCredentials($user, $password);

        $service = new ConsultCdrService();
        $service->setClient($ws);

        return $service;
    }

    public function saveValidateDocument($id, $_estado)
    {
        $loadDatos = new DatosController();

        $documento = DB::table('Tmp_ventas')
            ->where('IdVentas', $id)
            ->first();

        $idCliente = $documento->IdCliente;
        $idTipoMoneda = $documento->IdTipoMoneda;
        $idSucursal = $documento->Idsucursal;
        $fechaConvertida = $documento->FechaCreacion;
        $idUsuario = $documento->IdCreacion;
        $idTipoComp = $documento->IdTipoComprobante;
        $idTipoSunat = $documento->IdTipoSunat;
        $tipoPago = $documento->IdTipoPago;
        $plazoCredito = $documento->PlazoCredito;
        $pagoEfectivo = $documento->MontoEfectivo;
        $tipoTarjeta = $documento->IdTipoTarjeta;
        $numTarjeta = $documento->NumeroTarjeta;
        $pagoTarjeta = $documento->MontoTarjeta;
        $serie = $documento->Serie;
        $numero = $documento->Numero;
        $observacion = $documento->Observacion;
        $subtotal = $documento->Subtotal;
        $exonerada = $documento->Exonerada;
        $igv = $documento->IGV;
        $total = $documento->Total;
        $resumen = $documento->Resumen;
        $hash = $documento->Hash;
        $nota = $documento->Nota;
        $guia = $documento->Guia;
        $codigoAceptado = $documento->CodigoDoc;
        $estado = $_estado;
        $interes = $documento->Interes;

        $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);

        $array = ['IdCliente' => $idCliente, 'IdTipoMoneda' => $idTipoMoneda, 'Idsucursal' => $idSucursal, 'FechaCreacion' => $fechaConvertida, 'IdCreacion' => $idUsuario, 'IdTipoComprobante' => $idTipoComp, 'IdTipoSunat' => $idTipoSunat,
            'IdTipoPago' => $tipoPago, 'PlazoCredito' => $plazoCredito, 'MontoEfectivo' => $pagoEfectivo, 'IdTipoTarjeta' => $tipoTarjeta, 'NumeroTarjeta' => $numTarjeta, 'MontoTarjeta' => $pagoTarjeta,
            'Serie' => $serie, 'Numero' => $numero, 'Observacion' => $observacion, 'Subtotal' => $subtotal, 'Exonerada' => $exonerada, 'IGV' => $igv, 'Total' => $total, 'Resumen' => $resumen, 'Hash' => $hash, 'Nota' => $nota, 'Guia' => $guia, 'CodigoDoc' => $codigoAceptado, 'Estado' => $estado];

        DB::table('ventas')->insert($array);

        $venta = DB::table('ventas')
            ->orderBy('IdVentas', 'desc')
            ->first();
        $TmpidVenta = $venta->IdVentas;

        if ($tipoPago == 2) {
            //$interes = $req->interes;
            $this->guardarFechasPago($fechaConvertida, $plazoCredito, $idVenta, $total, $interes);
        }

        $arrayCaja = ['IdCaja' => $caja->IdCaja, 'IdVentas' => $idVenta];
        DB::table('caja_ventas')->insert($arrayCaja);

        $venta_articulo = DB::table('Tmp_ventas_articulo')
            ->where('IdVentas', $id)
            ->get();

        if (count($venta_articulo) >= 1) {
            $cantidadRestada = 0;
            $cantidadVentaReal = 1; // puse esto para contener si hay algun error
            $bandTipo = 0;
            $bandGan = 0; //esto es para controlar la ganancia
            foreach ($venta_articulo as $articulo) {
                $producto = substr($articulo->Codigo, 0, 3);
                if ($producto == 'PRO') {
                    $productoSelect = $loadDatos->getProductoSelect($articulo->IdArticulo);
                    $cantidadRestada = (int) $productoSelect->Stock - $articulo->Cantidad;

                    DB::table('articulo')
                        ->where('IdArticulo', $articulo->IdArticulo)
                        ->update(['Stock' => $cantidadRestada]);

                    $kardex = array(
                        'CodigoInterno' => $productoSelect->CodigoInterno,
                        'fecha_movimiento' => $fechaConvertida,
                        'tipo_movimiento' => 1,
                        'usuario_movimiento' => $idUsuario,
                        'documento_movimiento' => $serie . '-' . $numero,
                        'existencia' => $cantidadRestada,
                        'costo' => 1,
                        'IdArticulo' => $articulo->IdArticulo,
                        'IdSucursal' => $idSucursal,
                        'Cantidad' => $articulo->Cantidad,
                        'Descuento' => $articulo->Descuento,
                        'ImporteEntrada' => 0,
                        'ImporteSalida' => $articulo->Importe,
                        'estado' => 1,
                    );
                    DB::table('kardex')->insert($kardex);

                }

                $idVenta = $TmpidVenta;
                $idArticulo = $articulo->IdArticulo;
                $codigo = $articulo->Codigo;
                $detalle = $articulo->Detalle;
                $descuento = $articulo->Descuento;
                $cantidad = $articulo->Cantidad;
                $cantidadVentaReal = $articulo->CantidadReal;
                $bandTipo = $articulo->VerificaTipo;
                $newGanancia = $articulo->Ganancia;
                $importe = $articulo->Importe;
                $textUnida = $articulo->TextUnidad;
                $precio = $articulo->PrecioUnidadReal;

                $arrayRelacion = ['IdVentas' => $idVenta, 'IdArticulo' => $idArticulo, 'Codigo' => $codigo, 'Detalle' => $detalle, 'Descuento' => $descuento, 'Cantidad' => $cantidad, 'CantidadReal' => $cantidadVentaReal, 'VerificaTipo' => $bandTipo, 'Ganancia' => $newGanancia, 'Importe' => $importe, 'TextUnidad' => $textUnida, 'PrecioUnidadReal' => $precio];
                DB::table('ventas_articulo')->insert($arrayRelacion);
            }

            $verificar_venta = DB::table('Tmp_ventas')
                ->where('Idsucursal', $idSucursal)
                ->where('IdCreacion', $idUsuario)
                ->where('Serie', $serie)
                ->where('Numero', $numero)
                ->first();

            if ($verificar_venta) {
                $borrar_articulo = $verificar_venta->IdVentas;
                DB::table('Tmp_ventas')
                    ->where('IdVentas', $borrar_articulo)
                    ->delete();

                DB::table('Tmp_ventas_articulo')
                    ->where('IdVentas', $borrar_articulo)
                    ->delete();
            }

            return Response(['succes', $mensaje, $idVenta]);

        }
    }

    public function getDatosSeguro($idVentas)
    {
        return DB::table('ventas')
            ->join('seguros', 'ventas.Seguro', '=', 'seguros.IdSeguro')
            ->where('IdVentas', $idVentas)
            ->first();
    }
}
