<?php

namespace App\Http\Controllers\Operaciones;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use App\Http\Controllers\Operaciones\NumeroALetras;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use PDF;
use PHPMailer\PHPMailer\PHPMailer;
use Session;

class ComprasController extends Controller
{

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

        // NUEVO CODIGO
        $ultimosTreintaDias = $this->ultimosTreintaDias();
        $reporteCompras = $this->getCompras($idSucursal, $ultimosTreintaDias);
        $reporteComprasPendientes = $reporteCompras->where('Estado', 'Pendiente');
        $reporteComprasRegistradas = $reporteCompras->whereIn('Estado', ['Registrado', 'Anulado']);
        // FIN
        $reporteOrdenesCompra = $this->getOrdenesCompras($idSucursal, $ultimosTreintaDias);
        // dd($reporteOrdenesCompra);
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $tipoComprobante = $loadDatos->getTipoComprobante();
        $listaProveedores = $loadDatos->getTipoProveedores(3, $idSucursal);

        $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
        $array = ['permisos' => $permisos, 'idSucursal' => $idSucursal, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'reporteComprasPendientes' => $reporteComprasPendientes, 'reporteComprasRegistradas' => $reporteComprasRegistradas, 'reporteOrdenesCompra' => $reporteOrdenesCompra, 'tipoComprobante' => $tipoComprobante, 'listaProveedores' => $listaProveedores, 'caja' => $caja];

        return view('operaciones/compras/listaCompras', $array);
    }

    // Nueva funcion OBTENER MONTO CAJA
    public function getMontoCajaAbierta($idUsuario, $caja)
    {
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');

        $ventasAperturaCierreCaja = $loadDatos->getVentasAperturaCierreCaja($idSucursal, $idUsuario, $caja->FechaApertura);
        for ($i = 0; $i < count($ventasAperturaCierreCaja); $i++) {
            $_productos = $loadDatos->getItemsVentas($ventasAperturaCierreCaja[$i]->IdVentas);
            $ventasAperturaCierreCaja[$i]->Productos = $_productos;
        }

        $ventasContadoSoles = $loadDatos->getDetalleCaja($idSucursal, $idUsuario, $caja->FechaApertura, 1, 1);
        $ventasContadoDolares = $loadDatos->getDetalleCaja($idSucursal, $idUsuario, $caja->FechaApertura, 1, 2);
        $cobranzasSoles = $loadDatos->getDetalleCajaCobranzas($idSucursal, $idUsuario, $caja->FechaApertura, 1);
        $cobranzasDolares = $loadDatos->getDetalleCajaCobranzas($idSucursal, $idUsuario, $caja->FechaApertura, 2);
        $ventasContadoTotalSoles = $loadDatos->getDetalleCajaContado($idSucursal, $idUsuario, $caja->FechaApertura, 1);
        $ventasContadoTotalDolares = $loadDatos->getDetalleCajaContado($idSucursal, $idUsuario, $caja->FechaApertura, 2);
        $estado = $caja->Estado;
        $inicialSoles = $caja->Inicial;
        $inicialDolares = $caja->InicialDolares;
        $ingresosSol = $loadDatos->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, 'I', 1);
        $ingresosDol = $loadDatos->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, 'I', 2);

        if ($ingresosSol[0]->Monto == null) {
            $montoIngresosSoles = '0.00';
        } else {
            $montoIngresosSoles = $ingresosSol[0]->Monto;
        }
        if ($ingresosDol[0]->Monto == null) {
            $montoIngresosDolares = '0.00';
        } else {
            $montoIngresosDolares = $ingresosDol[0]->Monto;
        }
        $egresosSol = $loadDatos->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, 'E', 1);
        $egresosDol = $loadDatos->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, 'E', 2);
        if ($egresosSol[0]->Monto == null) {
            $montoEgresosSoles = '0.00';
        } else {
            $montoEgresosSoles = $egresosSol[0]->Monto;
        }

        if ($egresosDol[0]->Monto == null) {
            $montoEgresosDolares = '0.00';
        } else {
            $montoEgresosDolares = $egresosDol[0]->Monto;
        }

        $ventasContadoEfectivoSoles = $ventasContadoTotalSoles[0]->Efectivo;
        $ventasContadoTarjetaSoles = $ventasContadoTotalSoles[0]->Tarjeta;
        $ventasContadoCuentaBancariaSoles = $ventasContadoTotalSoles[0]->CuentaBancaria;
        $ventasContadoEfectivoDolares = $ventasContadoTotalDolares[0]->Efectivo;
        $ventasContadoTarjetaDolares = $ventasContadoTotalDolares[0]->Tarjeta;
        $ventasContadoCuentaBancariaDolares = $ventasContadoTotalDolares[0]->CuentaBancaria;
        $totalVentasContadoSoles = $ventasContadoSoles->ImporteTotal;
        $totalVentasContadoDolares = $ventasContadoDolares->ImporteTotal;
        $totalCobranzasSoles = $cobranzasSoles[0]->TotalCobranza;
        $totalCobranzasDolares = $cobranzasDolares[0]->TotalCobranza;
        $cobranzasEfectivoSoles = $cobranzasSoles[0]->Efectivo;
        $cobranzasEfectivoDolares = $cobranzasDolares[0]->Efectivo;
        $cobranzasTarjetaSoles = $cobranzasSoles[0]->Tarjeta;
        $cobranzasTarjetaDolares = $cobranzasDolares[0]->Tarjeta;
        $cobranzasCuentaBancariaSoles = $cobranzasSoles[0]->CuentaBancaria;
        $cobranzasCuentaBancariaDolares = $cobranzasDolares[0]->CuentaBancaria;
        $amortizacionSoles = $loadDatos->getAmortizacionesTotales($idSucursal, $idUsuario, $caja->FechaApertura, 1);
        $amortizacionDolares = $loadDatos->getAmortizacionesTotales($idSucursal, $idUsuario, $caja->FechaApertura, 2);
        $totalAmortizacionSoles = $amortizacionSoles->sum('Monto');
        $totalAmortizacionDolares = $amortizacionDolares->sum('Monto');
        $amortizacionEfectivoSoles = $amortizacionSoles->where('FormaPago', 1)->sum('Monto');
        $amortizacionEfectivoDolares = $amortizacionDolares->where('FormaPago', 1)->sum('Monto');
        $amortizacionTarjetaSoles = $amortizacionSoles->where('FormaPago', 2)->sum('Monto');
        $amortizacionTarjetaDolares = $amortizacionDolares->where('FormaPago', 2)->sum('Monto');
        $amortizacionCuentaBancariaSoles = $amortizacionSoles->where('FormaPago', 3)->sum('Monto');
        $amortizacionCuentaBancariaDolares = $amortizacionDolares->where('FormaPago', 3)->sum('Monto');
        $cajaTotalSoles = floatval($cobranzasEfectivoSoles) + floatval($ventasContadoEfectivoSoles) + floatval($amortizacionEfectivoSoles) + floatval($inicialSoles) + floatval($montoIngresosSoles) - floatval($montoEgresosSoles);
        $cajaTotalDolares = floatval($cobranzasEfectivoDolares) + floatval($ventasContadoEfectivoDolares) + floatval($totalAmortizacionDolares) + floatval($inicialDolares) + floatval($montoIngresosDolares) - floatval($montoEgresosDolares);
        return (object) ['CajaTotalSoles' => round($cajaTotalSoles, 2), 'CajaTotalDolares' => round($cajaTotalDolares, 2)];
    }

    public function storeEgresoCompra(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                if ($req->ajax()) {
                    $idUsuario = Session::get('idUsuario');
                    $idSucursal = Session::get('idSucursal');
                    $loadDatos = new DatosController();
                    $fecha = Carbon::now()->toDateTimeString();
                    // $datosCaja = $this->getMontoCajaAbierta($idUsuario);
                    $tipoMoneda = $req->selectTipoMoneda;
                    $descripcion = $req->inputDescripcion;
                    $montoCompra = $req->inputMontoEgreso;
                    $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
                    if ($caja == null) {
                        return Response(['error', 'Abrir Caja antes de realizar una venta']);
                    }
                    $datosCaja = $this->getMontoCajaAbierta($idUsuario, $caja);

                    if ($tipoMoneda == 1) {
                        if ($montoCompra >= $datosCaja->CajaTotalSoles) {
                            return Response(['error', 'No se puedo guardar el Egreso, porque el monto sobrepaso el total de caja Soles']);
                        }
                    }
                    if ($tipoMoneda == 2) {
                        if ($montoCompra >= $datosCaja->CajaTotalDolares) {
                            return Response(['error', 'No se puedo guardar el Egreso, porque el monto sobrepaso el total de caja Dolares']);
                        }
                    }

                    // $array = ['IdCaja' => $caja->IdCaja, 'Fecha' => $fecha, 'Tipo' => 'E', 'IdTipoMoneda' => $tipoMoneda, 'Monto' => $montoCompra, 'Descripcion' => $descripcion];
                    // DB::table('ingresoegreso')->insert($array);

                    return Response(['success', 'Egreso registrado correctamente']);
                }

            } else {
                Session::flush();
                return redirect('/')->with('out', 'Sesión de usuario Expirado');
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }

    }
    // FIn

    public function editarCompraPendiente(Request $req, $idCompraPendiente)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $text = '';
        $idSucursal = Session::get('idSucursal');
        $proveedores = $loadDatos->getproveedores($idSucursal);
        // NUEVO CODIGO
        $ultimosTreintaDias = $this->ultimosTreintaDias();
        $reporteCompras = $this->getCompras($idSucursal, $ultimosTreintaDias);
        $datosComprasPendientes = $reporteCompras->where('IdCompras', $idCompraPendiente)->first();
        $tipoMonedaCompraPendiente = $datosComprasPendientes->IdTipoMoneda;
        $arrayItemsDetalleCompra = $this->itemsDetalleCompra($idCompraPendiente);
        //  FIN
        $proveedoresTick = $loadDatos->getTipoProveedores(3, $idSucursal);
        $tipoMonedas = $loadDatos->getTipoMoneda();
        $productos = $loadDatos->getProductosPagination($idSucursal, $text, $tipoMonedaCompraPendiente, 0);
        $permisos = $loadDatos->getPermisos($idUsuario);

        $sucursal = $loadDatos->getSucursalSelect($idSucursal);
        $sucExonerado = $sucursal->Exonerado;

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $comprasTicket = $loadDatos->getCompras($idSucursal, 3);
        $comprasFactura = $loadDatos->getCompras($idSucursal, 2);
        $comprasBoleta = $loadDatos->getCompras($idSucursal, 1);
        $tipoComprobante = $loadDatos->getTipoComprobante();
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $fecha = date('d/m/Y');

        $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $exonerado = $datosEmpresa->Exonerado;
        $categorias = $loadDatos->getCategorias($usuarioSelect->CodigoCliente);

        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $cuentasSoles = $loadDatos->getCuentasCorrientes($usuarioSelect->CodigoCliente, 1);
        $cuentasDolares = $loadDatos->getCuentasCorrientes($usuarioSelect->CodigoCliente, 2);
        // Nuevo codigo Egreso
        $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
        $datosCaja = $datosCaja = $this->getMontoCajaAbierta($idUsuario, $caja) ?? '';
        // FIN
        $array = ['proveedores' => $proveedores, 'cuentasSoles' => $cuentasSoles, 'cuentasDolares' => $cuentasDolares, 'tipoMoneda' => $tipoMonedas, 'tipoComprobante' => $tipoComprobante, 'permisos' => $permisos, 'fecha' => $fecha, 'proveedoresTickets' => $proveedoresTick, 'categorias' => $categorias, 'comprasTicket' => $comprasTicket, 'comprasFactura' => $comprasFactura, 'comprasBoleta' => $comprasBoleta, 'productos' => $productos, 'textoBuscar' => '', 'idSucursal' => $idSucursal, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'sucExonerado' => $sucExonerado, 'exonerado' => $exonerado, 'datosComprasPendientes' => $datosComprasPendientes, 'arrayItemsDetalleCompra' => $arrayItemsDetalleCompra, 'idCompraPendiente' => $idCompraPendiente, 'tipoMonedaCompraPendiente' => $tipoMonedaCompraPendiente, 'datosCaja' => $datosCaja, 'caja' => $caja];
        return view('operaciones/compras/editarCompra', $array);
    }

    public function crearCompra(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        $loadDatos = new DatosController();
        $text = '';
        $idSucursal = Session::get('idSucursal');
        $proveedores = $loadDatos->getproveedores($idSucursal);
        $proveedoresTick = $loadDatos->getTipoProveedores(3, $idSucursal);
        $tipoMonedas = $loadDatos->getTipoMoneda();
        $productos = $loadDatos->getProductosPagination($idSucursal, $text, 1, 0);
        $permisos = $loadDatos->getPermisos($idUsuario);

        $sucursal = $loadDatos->getSucursalSelect($idSucursal);
        $sucExonerado = $sucursal->Exonerado;

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $comprasTicket = $loadDatos->getCompras($idSucursal, 3);
        $comprasFactura = $loadDatos->getCompras($idSucursal, 2);
        $comprasBoleta = $loadDatos->getCompras($idSucursal, 1);
        $tipoComprobante = $loadDatos->getTipoComprobante();
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $fecha = date('d/m/Y');

        $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $exonerado = $datosEmpresa->Exonerado;
        $categorias = $loadDatos->getCategorias($usuarioSelect->CodigoCliente);

        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $cuentasSoles = $loadDatos->getCuentasCorrientes($usuarioSelect->CodigoCliente, 1);
        $cuentasDolares = $loadDatos->getCuentasCorrientes($usuarioSelect->CodigoCliente, 2);

        // Nuevo codigo Egreso
        $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
        $datosCaja = $datosCaja = $this->getMontoCajaAbierta($idUsuario, $caja) ?? '';
        // FIN

        $array = ['proveedores' => $proveedores, 'cuentasSoles' => $cuentasSoles, 'cuentasDolares' => $cuentasDolares, 'tipoMoneda' => $tipoMonedas, 'tipoComprobante' => $tipoComprobante, 'permisos' => $permisos, 'fecha' => $fecha, 'proveedoresTickets' => $proveedoresTick, 'categorias' => $categorias, 'comprasTicket' => $comprasTicket, 'comprasFactura' => $comprasFactura, 'comprasBoleta' => $comprasBoleta, 'productos' => $productos, 'textoBuscar' => '', 'idSucursal' => $idSucursal, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'sucExonerado' => $sucExonerado, 'exonerado' => $exonerado, 'datosCaja' => $datosCaja, 'caja' => $caja];
        return view('operaciones/compras/crearCompra', $array);
    }

    public function store(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                if ($req->ajax()) {
                    try {
                        DB::beginTransaction();
                        $loadDatos = new DatosController();
                        $idSucursal = Session::get('idSucursal');
                        $idUsuario = Session::get('idUsuario');
                        $textoMensaje = '';
                        $compra = $req->compra;
                        $datosCompra = json_decode($compra, true);

                        if ($datosCompra['Serie'] == null) {
                            return Response(['error', 'Por favor, completar serie']);
                        }
                        if ($datosCompra['Numero'] == null) {
                            return Response(['error', 'Por favor, completar número correlativo']);
                        }
                        if ($datosCompra['IdTipoComprobante'] == 0) {
                            return Response(['error', 'Por favor, elegir Tipo de comprobante']);
                        }
                        if ($datosCompra['IdProveedor'] == 0) {
                            return Response(['error', 'Por favor, elegir Proveedor']);
                        }
                        if ($datosCompra['articulos'] == null) {
                            return Response(['error', 'Por favor, agrege productos']);
                        }

                        $valorTipoCambio = $req->valorCambio;

                        if ($datosCompra['IdTipoPago'] == 1) {
                            $plazoCredito = '';
                            $montoEfect = $req->MontoEfect;
                            $montoCuenta = $req->MontoCuenta;
                            $cuentaBancaria = $req->CuentaBancaria;
                            if (intval($cuentaBancaria) > 0) {
                                $montoTotal = floatval($montoEfect) + floatval($montoCuenta);
                                $banco = $loadDatos->getCuentaCorrienteSelect($cuentaBancaria);
                                if (floatval($banco->MontoActual) < floatval($montoCuenta)) {
                                    return Response(['error', 'La cuenta bancaria no se encuentra con saldo suficiente disponible']);
                                }
                            } else {
                                $montoTotal = floatval($montoEfect);
                            }
                            $_montoTotal = round($montoTotal, 2);
                            $_total = round($datosCompra['Total'], 2);
                            // Nuevo codigo el IF si es compra pendiente que no me pregunte el total $req->estadoCompra != 'Pendiente'
                            if ($req->valueBotonCompra != 'Pendiente' && $req->valueBotonCompra != 'actualizarCompraPendiente') {
                                if ($_montoTotal != $_total) {
                                    return Response(['error', 'La suma de pago efectivo y pago de cuenta bancaria debe ser igual al Importe Total']);
                                }
                            }
                        } else {
                            $plazoCredito = $datosCompra['PlazoCredito'];
                        }
                        $observacion = $datosCompra['Observacion'] == '' ? null : $datosCompra['Observacion'];
                        $fechaConvertida = Carbon::createFromFormat('d-m-Y', $datosCompra['FechaCreacion'])->format('Y-m-d H:i:s');
                        $datosCompra = array_merge($datosCompra, ['Idsucursal' => $idSucursal, 'IdCreacion' => $idUsuario, 'FechaCreacion' => $fechaConvertida, 'Observacion' => $observacion, 'PlazoCredito' => $plazoCredito]);

                        // NUEVO CODIGO
                        if ($req->valueBotonCompra == 'Pendiente') {

                            $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
                            if ($caja == null) {
                                return Response(['errorCajaCerrada', 'Abrir Caja antes de realizar una venta']);
                            } else {
                                // Validar serie y numero
                                $verificar = $this->verificarCodigo($datosCompra['Serie'], $datosCompra['Numero'], $idSucursal, $datosCompra['IdProveedor']);
                                if ($verificar->Cantidad > 0) {
                                    return Response(['error', 'La Serie y Número ya existen, Por favor vuelva a ingresarlo']);
                                }
                                // Fin

                                // eliminamos la propiedad articulos del objeto datosCompra e insertamos el resto de propiedades que requiere la tabla Compras
                                $datosComprasInsertar = collect($datosCompra)->except(['articulos'])->toArray();
                                $idCompra = DB::table('compras')->insertGetId($datosComprasInsertar);
                                // Fin

                                // Recorriendo los articulos de la compra
                                $detalleCompra = $datosCompra['articulos'];

                                foreach ($detalleCompra as $key => $item) {
                                    $precioCosto = floatval($item['PrecioCosto']);
                                    if ($datosCompra['TipoCompra'] == 2) {
                                        $precioCosto = floatval($precioCosto * 1.18);
                                    }
                                    $detalleCompra[$key]['IdCompras'] = $idCompra;
                                    $detalleCompra[$key]['PrecioCosto'] = $precioCosto;
                                    usleep(200000);
                                }
                                // Codigo de insercion por lotes
                                $lotesDetalleCompra = array_chunk($detalleCompra, 20);
                                foreach ($lotesDetalleCompra as $lote) {
                                    DB::table('compras_articulo')->insert($lote);
                                }
                                $textoMensaje = "La compra se registro como Pendiente";
                            }
                        }
                        if ($req->valueBotonCompra == 'Finalizado') {
                            $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
                            if ($caja == null) {
                                return Response(['errorCajaCerrada', 'Abrir Caja antes de realizar una venta']);
                            } else {
                                // Validar serie y numero
                                $verificar = $this->verificarCodigo($datosCompra['Serie'], $datosCompra['Numero'], $idSucursal, $datosCompra['IdProveedor']);
                                if ($verificar->Cantidad > 0) {
                                    return Response(['error', 'La Serie y Número ya existen, Por favor vuelva a ingresarlo']);
                                }
                                // Fin

                                $datosComprasInsertar = collect($datosCompra)->except(['articulos'])->toArray();
                                $idCompra = DB::table('compras')->insertGetId($datosComprasInsertar);

                                if ($datosCompra['IdTipoPago'] == 2) {
                                    $interes = $req->Interes;
                                    if ($interes == null) {
                                        $interes = 0;
                                    }

                                    $this->guardarFechasPagosCompras($fechaConvertida, $datosCompra['PlazoCredito'], $idCompra, $datosCompra['Total'], $interes);
                                } else {
                                    if (intval($cuentaBancaria) > 0) {
                                        $numeroOp = $req->nroOperacion;
                                        $fechaDepositoCompra = $req->fechaDepositoCompra ? Carbon::createFromFormat('d/m/Y', $req->fechaDepositoCompra)->format('Y-m-d H:i:s') : Carbon::now()->format('Y-m-d H:i:s');
                                        $this->guardaDetallesCuentaBancaria($cuentaBancaria, $montoEfect, $montoCuenta, $numeroOp, $datosCompra['Serie'], $datosCompra['Numero'], $fechaDepositoCompra, $idSucursal);
                                    }
                                }
                                // Recorriendo los articulos de la compra
                                $detalleCompra = $datosCompra['articulos'];

                                foreach ($detalleCompra as $key => $item) {
                                    $productoSelect = $loadDatos->getProductoSelect($item['IdArticulo']);
                                    $nuevoStock = (float) $productoSelect->Stock + (float) $item['Cantidad'];
                                    $precioCosto = floatval($item['PrecioCosto']);

                                    if ($datosCompra['TipoCompra'] == 2) {
                                        $precioCosto = floatval($precioCosto * 1.18);
                                    }

                                    if ($datosCompra['IdTipoMoneda'] == 2) {
                                        $valorTipoCambioPror = ($productoSelect->ValorTipoCambio + $valorTipoCambio) / 2;
                                    } else {
                                        $valorTipoCambioPror = 0;
                                    }

                                    DB::table('articulo')
                                        ->where('IdArticulo', $item['IdArticulo'])
                                        ->update(['Stock' => $nuevoStock, 'Costo' => $precioCosto, 'ValorTipoCambio' => $valorTipoCambioPror]);

                                    $itemsKardex[] = [
                                        'CodigoInterno' => $productoSelect->CodigoInterno,
                                        'fecha_movimiento' => $fechaConvertida,
                                        'tipo_movimiento' => 2,
                                        'usuario_movimiento' => $idUsuario,
                                        'documento_movimiento' => $datosCompra['Serie'] . '-' . $datosCompra['Numero'],
                                        'existencia' => $nuevoStock,
                                        'costo' => $precioCosto,
                                        'IdArticulo' => $item['IdArticulo'],
                                        'IdSucursal' => $idSucursal,
                                        'Cantidad' => $item['Cantidad'],
                                        'Descuento' => 0,
                                        'ImporteEntrada' => $item['Importe'],
                                        'ImporteSalida' => 0,
                                        'estado' => 1,
                                    ];

                                    $this->completarStockNuevo($item['IdArticulo'], $item['PrecioCosto'], $productoSelect->Precio, $item['Cantidad']);
                                    $detalleCompra[$key]['IdCompras'] = $idCompra;
                                    $detalleCompra[$key]['PrecioCosto'] = $precioCosto;
                                }

                                // Codigo de insercion por lotes
                                $lotesDetalleCompra = array_chunk($detalleCompra, 20);
                                foreach ($lotesDetalleCompra as $lote) {
                                    DB::table('compras_articulo')->insert($lote);
                                }

                                $lotesKardex = array_chunk($itemsKardex, 20);
                                foreach ($lotesKardex as $lote) {
                                    DB::table('kardex')->insert($lote);
                                }
                                $textoMensaje = 'La compra se registro correctamente';
                                $textomensaje2 = 'Compra 2 correcto';

                                if ($req->checkGuardarEgreso == 1) {
                                    $array = ['IdCaja' => $req->inputIdCaja, 'Fecha' => $fechaConvertida, 'Tipo' => 'E', 'IdTipoMoneda' => $datosCompra['IdTipoMoneda'], 'Monto' => $datosCompra['Total'], 'Descripcion' => $req->inputDescripcionEgreso];
                                    DB::table('ingresoegreso')->insert($array);
                                }
                            }
                        }
                        if ($req->valueBotonCompra == 'actualizarCompraPendiente') {

                            $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
                            if ($caja == null) {
                                return Response(['errorCajaCerrada', 'Abrir Caja antes de realizar una venta']);
                            } else {

                                $idCompra = $req->idCompraPendiente;
                                $datosComprasInsertar = collect($datosCompra)->except(['articulos'])->toArray();
                                // Actualizar los datos de la compra
                                DB::table('compras')->where('IdCompras', $idCompra)->where('IdSucursal', $idSucursal)->update($datosComprasInsertar);

                                // Obteniendo los articulo almacenados anteriormente
                                $articulosAnteriores = DB::table('compras_articulo')->where('IdCompras', $idCompra)->get();
                                $arrayIdArticulosAnteriores = $articulosAnteriores->pluck('IdArticulo')->toArray();
                                // Obteniendo los nuevos articulos del detalle de compra
                                $articulosNuevos = collect($datosCompra['articulos']);
                                $idArticulosNuevos = $articulosNuevos->pluck('IdArticulo')->toArray();

                                $articulosInsertar = [];
                                foreach ($articulosNuevos as $key => $item) {
                                    $precioCosto = floatval($item['PrecioCosto']);
                                    if ($datosCompra['TipoCompra'] == 2) {
                                        $precioCosto = floatval($precioCosto * 1.18);
                                    }

                                    if (in_array($item['IdArticulo'], $arrayIdArticulosAnteriores)) {
                                        DB::table('compras_articulo')
                                            ->where('IdArticulo', $item['IdArticulo'])
                                            ->where('IdCompras', $idCompra)
                                            ->update(['PrecioCosto' => $precioCosto, 'Cantidad' => $item['Cantidad'], 'Importe' => $item['Importe']]);
                                    } else {
                                        array_push($articulosInsertar, array_merge($item, ['IdCompras' => $idCompra, 'PrecioCosto' => $precioCosto]));
                                    }
                                }
                                // Eliminando los articulo que han sido quitados de la compra
                                DB::table('compras_articulo')
                                    ->where('IdCompras', $idCompra)
                                    ->whereNotIn('IdArticulo', $idArticulosNuevos)
                                    ->delete();

                                // Codigo de insercion por lotes
                                if (!empty($articulosInsertar)) {
                                    $lotesDetalleCompra = array_chunk($articulosInsertar, 20);
                                    foreach ($lotesDetalleCompra as $lote) {
                                        DB::table('compras_articulo')->insert($lote);
                                    }
                                }
                                $textoMensaje = 'La compra se registro como Pendiente Nuevamente';
                            }
                        }
                        if ($req->valueBotonCompra == 'finalizarCompraPendiente') {

                            $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
                            if ($caja == null) {
                                return Response(['errorCajaCerrada', 'Abrir Caja antes de realizar una venta']);
                            } else {
                                $idCompra = $req->idCompraPendiente;
                                $datosComprasInsertar = collect($datosCompra)->except(['articulos'])->toArray();
                                DB::table('compras')->where('IdCompras', $idCompra)->where('IdSucursal', $idSucursal)->update($datosComprasInsertar);

                                if ($datosCompra['IdTipoPago'] == 2) {
                                    $interes = $req->Interes;
                                    if ($interes == null) {
                                        $interes = 0;
                                    }
                                    $this->guardarFechasPagosCompras($fechaConvertida, $datosCompra['PlazoCredito'], $idCompra, $datosCompra['Total'], $interes);
                                } else {
                                    if (intval($cuentaBancaria) > 0) {
                                        $numeroOp = $req->nroOperacion;
                                        $fechaDepositoCompra = $req->fechaDepositoCompra ? Carbon::createFromFormat('d/m/Y', $req->fechaDepositoCompra)->format('Y-m-d H:i:s') : Carbon::now()->format('Y-m-d H:i:s');
                                        $this->guardaDetallesCuentaBancaria($cuentaBancaria, $montoEfect, $montoCuenta, $numeroOp, $datosCompra['Serie'], $datosCompra['Numero'], $fechaDepositoCompra, $idSucursal);
                                    }
                                }

                                // Obteniendo los articulo almacenados anteriormente
                                $articulosAnteriores = DB::table('compras_articulo')->where('IdCompras', $idCompra)->get();
                                $arrayIdArticulosAnteriores = $articulosAnteriores->pluck('IdArticulo')->toArray();
                                // Obteniendo los nuevos articulos del detalle de compra
                                $articulosNuevos = collect($datosCompra['articulos']);
                                $idArticulosNuevos = $articulosNuevos->pluck('IdArticulo')->toArray();

                                $articulosInsertar = [];
                                foreach ($articulosNuevos as $key => $item) {
                                    $productoSelect = $loadDatos->getProductoSelect($item['IdArticulo']);
                                    $nuevoStock = (float) $productoSelect->Stock + (float) $item['Cantidad'];

                                    $precioCosto = floatval($item['PrecioCosto']);
                                    if ($datosCompra['TipoCompra'] == 2) {
                                        $precioCosto = floatval($precioCosto * 1.18);
                                    }

                                    if ($datosCompra['IdTipoMoneda'] == 2) {
                                        $valorTipoCambioPror = ($productoSelect->ValorTipoCambio + $valorTipoCambio) / 2;
                                    } else {
                                        $valorTipoCambioPror = 0;
                                    }

                                    DB::table('articulo')
                                        ->where('IdArticulo', $item['IdArticulo'])
                                        ->update(['Stock' => $nuevoStock, 'Costo' => $precioCosto, 'ValorTipoCambio' => $valorTipoCambioPror]);

                                    $this->completarStockNuevo($item['IdArticulo'], $item['PrecioCosto'], $productoSelect->Precio, $item['Cantidad']);

                                    $itemsKardex[] = [
                                        'CodigoInterno' => $productoSelect->CodigoInterno,
                                        'fecha_movimiento' => $fechaConvertida,
                                        'tipo_movimiento' => 2,
                                        'usuario_movimiento' => $idUsuario,
                                        'documento_movimiento' => $datosCompra['Serie'] . '-' . $datosCompra['Numero'],
                                        'existencia' => $nuevoStock,
                                        'costo' => $precioCosto,
                                        'IdArticulo' => $item['IdArticulo'],
                                        'IdSucursal' => $idSucursal,
                                        'Cantidad' => $item['Cantidad'],
                                        'Descuento' => 0,
                                        'ImporteEntrada' => $item['Importe'],
                                        'ImporteSalida' => 0,
                                        'estado' => 1,
                                    ];

                                    if (in_array($item['IdArticulo'], $arrayIdArticulosAnteriores)) {
                                        DB::table('compras_articulo')
                                            ->where('IdArticulo', $item['IdArticulo'])
                                            ->where('IdCompras', $idCompra)
                                            ->update(['PrecioCosto' => $precioCosto, 'Cantidad' => $item['Cantidad'], 'Importe' => $item['Importe']]);
                                    } else {
                                        array_push($articulosInsertar, array_merge($item, ['IdCompras' => $idCompra, 'PrecioCosto' => $precioCosto]));
                                    }
                                }

                                // Eliminando los articulo que han sido quitados de la compra
                                DB::table('compras_articulo')
                                    ->where('IdCompras', $idCompra)
                                    ->whereNotIn('IdArticulo', $idArticulosNuevos)
                                    ->delete();

                                // Codigo de insercion por lotes detallee Compra
                                $lotesDetalleCompra = array_chunk($articulosInsertar, 20);
                                foreach ($lotesDetalleCompra as $lote) {
                                    DB::table('compras_articulo')->insert($lote);
                                }

                                // Codigo de insercion por lotes Articulos Kardex
                                $lotesKardex = array_chunk($itemsKardex, 20);
                                foreach ($lotesKardex as $lote) {
                                    DB::table('kardex')->insert($lote);
                                }

                                if ($req->checkGuardarEgreso == 1) {
                                    $array = ['IdCaja' => $req->inputIdCaja, 'Fecha' => $fechaConvertida, 'Tipo' => 'E', 'IdTipoMoneda' => $datosCompra['IdTipoMoneda'], 'Monto' => $datosCompra['Total'], 'Descripcion' => $req->inputDescripcionEgreso];
                                    DB::table('ingresoegreso')->insert($array);

                                }
                                $textoMensaje = 'La compra pendiente se Finalizo Correctamente';
                            }
                        }
                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollback();
                        return Response(['errorTransaccion', 'Ocurrio un error, por favor No INSISTA EN LA CREACIÓN, proceda a comunicarse con el Área de Soporte. ']);
                    }
                    return Response(['success', $textoMensaje, $idCompra]);
                }
            } else {
                Session::flush();
                return redirect('/')->with('out', 'Sesión de usuario Expirado');
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // Nueva funcion Orden de compra

    public function verComprobanteOrdenCompra($id)
    {
        $idBtn = $id;
        if (strpos($id, 'C-') === 0) {
            $id = substr($id, 2);
        }
        $loadDatos = new DatosController();
        $ordenCompraSelect = $this->getOrdenesComprasSelect($id);
        $idUsuario = Session::get('idUsuario');
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $fecha = date_create($ordenCompraSelect->FechaEmision);
        $formatoFecha = date_format($fecha, 'd-m-Y');
        $formatoHora = date_format($fecha, 'H:i A');
        $fechaRecepcion = Carbon::parse($ordenCompraSelect->FechaRecepcion)->format('Y-m-d');
        $itemsOrdenCompra = $this->getItemsOrdenCompra($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $array = ['ordenCompraSelect' => $ordenCompraSelect, 'formatoFecha' => $formatoFecha, 'permisos' => $permisos, 'formatoHora' => $formatoHora, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'fechaRecepcion' => $fechaRecepcion, 'itemsOrdenCompra' => $itemsOrdenCompra, 'idBtn' => $idBtn];
        return view('operaciones/compras/comprobanteOrdenCompra', $array)->with('status', 'Se registro compra exitosamente');
    }

    public function mostrarVistaGenerarOrdenCompra(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                $idUsuario = Session::get('idUsuario');
            } else {
                Session::flush();
                return redirect('/')->with('out', 'Sesión de usuario Expirado');
            }
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $permisos = $loadDatos->getPermisos($idUsuario);
            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $sucursal = $loadDatos->getSucursalSelect($idSucursal);
            $sucExonerado = $sucursal->Exonerado;
            $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
            $exonerado = $datosEmpresa->Exonerado;
            $proveedores = $loadDatos->getproveedores($idSucursal);
            $tipoMonedas = $loadDatos->getTipoMoneda();
            $text = '';
            $productos = $loadDatos->getProductosPagination($idSucursal, $text, 1, 0);
            $categorias = $loadDatos->getCategorias($usuarioSelect->CodigoCliente);
            $fecha = date('d/m/Y');

            $numeroDB = $this->correlativoActual($idUsuario, $idSucursal);
            if ($numeroDB) {
                $numero = str_pad($numeroDB->Numero + 1, 8, '0', STR_PAD_LEFT);
            } else {
                $numero = str_pad(1, 8, '0', STR_PAD_LEFT);
            }
            $numeroOrden = $sucursal->Orden;
            $serieCeros = str_pad($numeroOrden, 2, '0', STR_PAD_LEFT);
            $serie = 'OC' . $numeroOrden . '' . $serieCeros;

            $array = ['permisos' => $permisos, 'idSucursal' => $idSucursal, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'serie' => $serie, 'numero' => $numero, 'proveedores' => $proveedores,
                'tipoMonedas' => $tipoMonedas, 'productos' => $productos, 'categorias' => $categorias, 'textoBuscar' => '', 'sucExonerado' => $sucExonerado, 'exonerado' => $exonerado, 'fecha' => $fecha];
            return view('operaciones/compras/generarOrdenCompra', $array);

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function storeOrdenCompra(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                if ($req->ajax()) {
                    $idUsuario = Session::get('idUsuario');
                    $idSucursal = Session::get('idSucursal');
                    if ($req->proveedor == 0) {
                        return Response(['alert1', 'Por favor, elegir Proveedor']);
                    }
                    $fechaRecepcion = $req->fechaRecepcion;
                    if ($fechaRecepcion == null) {
                        return Response(['alert2', 'Por favor, ingresar fecha de recepción']);
                    }
                    if ($req->Id == null) {
                        return Response(['alert3', 'Por favor, agrege productos']);
                    }

                    $fechaRecepcion = Carbon::createFromFormat('d/m/Y', $fechaRecepcion)->format('Y-m-d');
                    $fechaEmision = Carbon::createFromFormat('d/m/Y', $req->fechaEmision)->format('Y-m-d H:i:s');
                    $observacion = $req->observacion;
                    $idTipoMoneda = $req->TipoMoneda;
                    $tipoCompra = $req->tipoCompra;
                    $subtotal = $req->subtotal;
                    $tipoPago = $req->tipoPago;
                    $igv = $req->igv;
                    $total = $req->total;
                    $serie = $req->serie;
                    $numero = $req->numero;
                    $idProveedor = $req->proveedor;
                    $plazoCredito = $req->plazoCredito;

                    $resultado = $this->verificarSerieNumero($serie, $numero, $idSucursal);
                    if ($resultado->Cantidad > 0) {
                        $resultado = DB::table('orden_compra')
                            ->select(DB::raw('MAX(Numero) as Numero'))
                            ->where('IdSucursal', $idSucursal)
                            ->first();
                        $numero = str_pad($resultado->Numero + 1, 8, '0', STR_PAD_LEFT);
                    }

                    // NUEVO CODIGO
                    $estado = 'Pendiente';
                    if ($tipoCompra == 1) {
                        $subtotal = $req->subtotal;
                    } else {
                        $subtotal = $req->opExonerado;
                    }
                    $array = ['IdProveedor' => $idProveedor, 'IdTipoMoneda' => $idTipoMoneda, 'Idsucursal' => $idSucursal, 'FechaEmision' => $fechaEmision, 'FechaRecepcion' => $fechaRecepcion, 'IdUsuarioCreacion' => $idUsuario, 'IdTipoPago' => $tipoPago, 'DiasPlazoCredito' => $plazoCredito,
                        'TipoCompra' => $tipoCompra, 'Serie' => $serie, 'Numero' => $numero, 'Observacion' => $observacion, 'Subtotal' => $subtotal, 'Igv' => $igv, 'Total' => $total, 'Estado' => $estado];
                    DB::table('orden_compra')->insert($array);

                    $ordenCompra = DB::table('orden_compra')
                        ->where('IdUsuarioCreacion', $idUsuario)
                        ->orderBy('IdOrdenCompra', 'desc')
                        ->first();
                    $idOrdenCompra = $ordenCompra->IdOrdenCompra;

                    for ($i = 0; $i < count($req->Id);
                        $i++) {
                        $loadDatos = new DatosController();
                        $productoSelect = $loadDatos->getProductoSelect($req->Id[$i]);
                        $precioCosto = floatval($req->PrecioCosto[$i]);

                        if ($tipoCompra == 2) {
                            $precioCosto = floatval($precioCosto * 1.18);
                        }
                        $arrayRelacion = ['IdOrdenCompra' => $idOrdenCompra, 'IdArticulo' => $req->Id[$i], 'CodigoArticulo' => $req->Codigo[$i], 'PrecioCosto' => $precioCosto, 'Cantidad' => $req->Cantidad[$i], 'Importe' => $req->Importe[$i]];
                        DB::table('detalle_orden_compra')->insert($arrayRelacion);
                    }
                    return Response(['succes', 'La compra se registro correctamente', $idOrdenCompra]);

                } else {
                    Session::flush();
                    return redirect('/')->with('out', 'Sesión de usuario Expirado');
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
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
        $ordenCompraSelect = $this->getOrdenesComprasSelect($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $nombreEmpresa = $empresa->Nombre;
        $rucEmpresa = $empresa->Ruc;
        $numero = $ordenCompraSelect->Numero;
        $serie = $ordenCompraSelect->Serie;
        $fecha = date_create($ordenCompraSelect->FechaEmision);
        $formatoFecha = date_format($fecha, 'd/m/Y');
        $fechaRecepcion = Carbon::createFromFormat('d-m-Y', $ordenCompraSelect->FechaRecepcion)->format('d/m/Y');
        $pdf = $this->generarPdfOrdenCompra($id);
        file_put_contents($rucEmpresa . '-' . $serie . '-' . $numero . '.pdf', $pdf->output());

        if ($ordenCompraSelect->IdTipoPago == 1) {
            $tipoPago = '<p><span style="font-weight: bold;">Tipo Compra: Contado</span></p>';
        } else {
            $tipoPago = '<p><span style="font-weight: bold;">Tipo Compra: Crédito</span></p><p><span style="font-weight: bold;">Condición de Pago: ' . $ordenCompraSelect->DiasPlazoCredito . ' Días - (Después de la Fecha de Recepción)</span></p>';
        }

        // dd( $ordenCompraSelect->Nombres );
        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host = 'mail.easyfactperu.pe';

        $mail->SMTPAuth = true;

        $mail->Username = 'facturacion@easyfactperu.pe';
        $mail->Debugoutput = 'html';
        $mail->Password = 'gV.S=o=Q,bl2';

        $mail->SMTPSecure = 'tls';

        $mail->Port = 587;

        $mail->From = 'facturacion@easyfactperu.pe';
        $mail->FromName = 'EASYFACT PERÚ S.A.C.  - Facturación Electrónica';
        $mail->addAddress($req->correo, 'Comprobante');
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Envío de Orden de Compra';
        $mail->addAttachment($rucEmpresa . '-' . $serie . '-' . $numero . '.pdf');

        $mail->msgHTML('<table width="100%">'
            . '<tr>'
            . '<td style="border: 1px solid #000;">'
            . '<div align="center" style="background-color: #CCC">'
            . '<img width="150px" style="margin:15px" src="' . $empresa->Imagen . '">'
            . '</div>'
            . '<div style="margin-bottom:20px;margin-left:10px">'
            . '<p>Estimado(a),</p>'
            . '</div>'
            . '<div style="margin-bottom:20px;margin-left:10px">'
            . '<p>' . $ordenCompraSelect->Nombres . '</p>'
            . '</div>'
            . '<div style="margin-bottom:10px;margin-left:10px">'
            . '<p>Por encargo del emisor <span style="font-weight: bold;">' . $nombreEmpresa . '</span>, nos es grato informar que se le está detallando la lista de productos requeridos en la siguiente Orden de Compra:</p>'
            . '</div>'
            . '<hr style="border: 0.5px solid #000;">'
            . '<div style="margin-bottom:20px;margin-left:30px">'
            . '<p><span style="font-weight: bold;">Tipo: ORDEN DE COMPRA</span></p>'
            . '<p><span style="font-weight: bold;">Número: ' . $serie . '-' . $numero . '</span></p>'
            . '<p><span style="font-weight: bold;">RUC / DNI: ' . $rucEmpresa . '</span></p>'
            . '<p><span style="font-weight: bold;">Fecha Emisión: ' . $formatoFecha . '</span></p>'
            . '<p><span style="font-weight: bold;">Fecha Entrega: ' . $fechaRecepcion . '</span></p>'
            . '<p><span style="font-weight: bold;">Monto Total: ' . $ordenCompraSelect->Total . '</span></p>'
            . $tipoPago
            . '</div>'
            // . '<hr style="border: 0.5px solid #000;">'
            // . '<div style="margin-bottom:20px;margin-left:10px">'
            // . '<p>Los comprobantes también podrán ser consultados en el enlace: <a href="http://easyfactperu.pe/facturacion/">www.easyfactperu.pe</a>, ingresando mediante su usuario o utilizando nuestro acceso anónimo.</p>'
            // . '</div>'
            // . '<hr style="border: 0.5px solid #000;">'
            // . '<div style="margin-bottom:20px;margin-left:10px">'
            // . '<p><span style="font-weight: bold;">Atentamente</span></p>'
            // . '<p><span style="font-weight: bold;">AGRADECEREMOS NO RESPONDER ESTE CORREO</span></p>'
            // . '<p><span style="font-weight: bold;">Si deseas ser Emisor Electrónico contáctanos o escríbenos al correo informes@easyfactperu.pe</span></p>'
            // . '</div>'
            . '</td>'
            . '</tr>'
            . '</table>');
        $enviado = $mail->send();
        if ($enviado) {
            if ($enviado) {
            }
            return back()->with('status', 'Se envio correo con éxito');
        } else {
            return back()->with('error', 'No se pudo enviar correo');
        }
    }

    private function generarPdfOrdenCompra($id)
    {
        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');
        $ordenCompraSelect = $this->getOrdenesComprasSelect($id);
        $fecha = date_create($ordenCompraSelect->FechaEmision);
        $formatoFecha = date_format($fecha, 'd-m-Y');
        $formatoHora = date_format($fecha, 'H:i A');
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $inicialRuc = substr($empresa->Ruc, 0, 2);
        if ($inicialRuc == '10') {
            $nombreEmpresa = $empresa->NombreComercial;
        } else {
            $nombreEmpresa = $empresa->Nombre;
        }
        $convertirLetras = new NumeroALetras();
        if ($ordenCompraSelect->IdTipoMoneda == 1) {
            $importeLetras = $convertirLetras->convertir($ordenCompraSelect->Total, 'soles');
        } else {
            $importeLetras = $convertirLetras->convertir($ordenCompraSelect->Total, 'dólares');
        }

        $numeroCerosIzquierda = $this->completarCeros($ordenCompraSelect->Numero);
        $cuentasCorrientes = $this->getCuentasCorrientes($usuarioSelect->CodigoCliente);

        $items = $this->getItemsOrdenCompra($id);
        $array = ['items' => $items, 'ordenCompraSelect' => $ordenCompraSelect, 'numeroCeroIzq' => $numeroCerosIzquierda, 'empresa' => $empresa, 'nombreEmpresa' => $nombreEmpresa, 'formatoFecha' => $formatoFecha, 'formatoHora' => $formatoHora, 'importeLetras' => $importeLetras, 'cuentasCorrientes' => $cuentasCorrientes];
        view()->share($array);
        $pdf = PDF::loadView('ordenCompraPDF');

        return $pdf;
    }

    public function imprimirPdfOrdenCompra($id)
    {
        $pdf = $this->generarPdfOrdenCompra($id);
        return $pdf->stream();
    }

    public function descargarPdfOrdenCompra($id)
    {
        $pdf = $this->generarPdfOrdenCompra($id);
        return $pdf->download('OrdenCompra-' . $id . '.pdf');
    }

    private function correlativoActual($idUsuario, $idSucursal)
    {
        try {
            $resultado = DB::table('orden_compra')
                ->where('IdUsuarioCreacion', $idUsuario)
                ->where('IdSucursal', $idSucursal)
                ->orderBy('IdOrdenCompra', 'desc')
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function mostrarVistaConvertirOrdenCompra(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $sucursal = $loadDatos->getSucursalSelect($idSucursal);
        $sucExonerado = $sucursal->Exonerado;
        $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $exonerado = $datosEmpresa->Exonerado;

        $arrayItemsOrdenCompra = $this->getItemsOrdenCompra($id);
        $datosOrdenCompra = $this->getOrdenesComprasSelect($id);
        $tipoComprobante = $loadDatos->getTipoComprobante();
        // Nuevo Codigo
        if ($datosOrdenCompra->IdTipoDocumento == 1) {
            $tipoComprobante = $tipoComprobante->whereIn("IdTipoComprobante", [1, 3])->values();
        }
        if ($datosOrdenCompra->IdTipoDocumento == 2) {
            $tipoComprobante = $tipoComprobante->whereIn("IdTipoComprobante", [2, 3])->values();
        }
        // Fin
        $tipoMonedas = $loadDatos->getTipoMoneda();
        $cuentasSoles = $loadDatos->getCuentasCorrientes($usuarioSelect->CodigoCliente, 1);
        $cuentasDolares = $loadDatos->getCuentasCorrientes($usuarioSelect->CodigoCliente, 2);
        $idOrdenCompra = $id;

        $fecha = Carbon::today();
        $datosTipoCambio = DB::table('tipo_cambio')
            ->where('FechaCreacion', $fecha)
            ->where('IdSucursal', $idSucursal)
            ->get();

        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'sucExonerado' => $sucExonerado, 'exonerado' => $exonerado, 'arrayItemsOrdenCompra' => $arrayItemsOrdenCompra, 'datosOrdenCompra' => $datosOrdenCompra, 'tipoComprobante' => $tipoComprobante, 'tipoMoneda' => $tipoMonedas, 'cuentasSoles' => $cuentasSoles, 'cuentasDolares' => $cuentasDolares, 'idOrdenCompra' => $idOrdenCompra, 'datosTipoCambio' => $datosTipoCambio];
        return view('operaciones/compras/convertirOrden', $array);
    }

    private function verificarSerieNumero($serie, $numero, $idSucursal)
    {
        try {
            $resultado = DB::table('orden_compra')
                ->select(DB::raw('count(IdOrdenCompra) as Cantidad'))
                ->where('Serie', $serie)
                ->where('Numero', $numero)
                ->where('IdSucursal', $idSucursal)
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    // FIN

    public function comprobarExistencia(Request $req)
    {
        if ($req->ajax()) {
            $idSucursal = Session::get('idSucursal');
            $serie = $req->serie;
            $numero = $req->numero;
            $idProveedor = $req->idProveedor;

            if ($serie == null) {
                return Response(['error', 'Por favor, completar serie']);
            }
            if ($numero == null) {
                return Response(['error', 'Por favor, completar número']);
            }
            $respuesta = DB::table('compras')
                ->select('compras.IdProveedor', 'compras.Serie', 'compras.Numero')
                ->where('compras.IdSucursal', $idSucursal)
                ->where('compras.IdProveedor', $idProveedor)
                ->where('compras.Serie', $serie)
                ->where('compras.Numero', $numero)
                ->whereIn('compras.Estado', ['Registrado', 'Pendiente'])
                ->get();
            if (count($respuesta) >= 1) {
                return Response(['errorDuplicado', 'Por favor, Ingrese otra serie y número']);
            } else {
                return response(['success', 'Serie y Número Correctos no existen en los registros']);
            }
        }
    }

    public function guardaDetallesCuentaBancaria($cuentaBancaria, $montoEfect, $montoCuentaBanc, $numeroOp, $serie, $numero, $fechaDepositoCompra, $idSucursal)
    {
        $loadDatos = new DatosController();
        $montoCuenta = $loadDatos->getCuentaCorrienteSelect($cuentaBancaria);
        $montoActual = floatval($montoCuenta->MontoActual) - floatval($montoCuentaBanc);
        $arrayDatos = ['FechaPago' => $fechaDepositoCompra, 'IdBanco' => $cuentaBancaria, 'NumeroOperacion' => $numeroOp, 'Detalle' => $serie . '-' . $numero, 'TipoMovimiento' => 'Compras', 'Entrada' => '0', 'Salida' => $montoCuentaBanc, 'MontoActual' => $montoActual, 'IdSucursal' => $idSucursal];
        DB::table('banco_detalles')->insert($arrayDatos);

        DB::table('banco')->where('IdBanco', $cuentaBancaria)->update(['MontoActual' => $montoActual]);
    }

    public function guardarFechasPagosCompras($fechaConvertida, $plazoCredito, $idCompra, $total, $interes)
    {
        //$fechaInicio = DateTime::createFromFormat( 'd-m-Y', $fecha );
        //$fechaConvertida = $fechaInicio->format( 'Y-m-d' );

        $plazoInteresTotal = $total + (($total / 100) * $interes);

        $fechaConvertidaFinal = strtotime('+' . $plazoCredito . ' day', strtotime($fechaConvertida));
        $fechaConvertidaFinal = date('Y-m-d', $fechaConvertidaFinal);

        $array = ['IdCompras' => $idCompra, 'FechaInicio' => $fechaConvertida, 'FechaUltimo' => $fechaConvertidaFinal, 'Importe' => $plazoInteresTotal, 'ImportePagado' => 0.00, 'DiasPasados' => 0, 'Estado' => 1];
        DB::table('fecha_compras')->insert($array);
    }

    public function completarStockNuevo($idArticulo, $costo, $precio, $cantidad)
    {
        $articulo = DB::table('stock')
            ->where('IdArticulo', $idArticulo)
            ->orderBy('IdStock', 'desc')
            ->get();

        if ($articulo[0]->Cantidad == 0) {
            DB::table('stock')
                ->where('IdStock', $articulo[0]->IdStock)
                ->update(['Costo' => $costo, 'Precio' => $precio, 'Cantidad' => $cantidad]);
        } else {
            if (count($articulo) == 1) {
                $_array = ['IdArticulo' => $idArticulo, 'Costo' => $costo, 'Precio' => $precio, 'Cantidad' => $cantidad];
                DB::table('stock')->insert($_array);
            } else {
                $total = $articulo[0]->Cantidad + $articulo[1]->Cantidad;
                DB::table('stock')
                    ->where('IdStock', $articulo[1]->IdStock)
                    ->update(['Costo' => $articulo[0]->Costo, 'Precio' => $articulo[0]->Precio, 'Cantidad' => $total]);

                DB::table('stock')
                    ->where('IdStock', $articulo[0]->IdStock)
                    ->update(['Costo' => $costo, 'Precio' => $precio, 'Cantidad' => $cantidad]);
            }
        }

    }

    public function verComprobanteGenerado($id)
    {
        $loadDatos = new DatosController();
        $compraSelect = $loadDatos->getCompraselect($id);
        // dd( $compraSelect );
        $idUsuario = Session::get('idUsuario');
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $fecha = date_create($compraSelect->FechaCreacion);
        $formatoFecha = date_format($fecha, 'd-m-Y');
        $formatoHora = date_format($fecha, 'H:i A');
        $items = $loadDatos->getItemsCompras($id);
        //$collectItems = collect($items);
        $arrayItems = [];
        foreach ($items as $item) {
            if ($item->IdTipo == 1) {
                $precio = floatval($item->Precio);
                $costo = floatval($item->Costo);
                if ($costo >= $precio) {
                    array_push($arrayItems, $item->Descripcion);
                }
            }
            usleep(100000);
        }
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        //dd($arrayItems);
        $array = ['compraSelect' => $compraSelect, 'arrayItems' => $arrayItems, 'items' => $items, 'formatoFecha' => $formatoFecha, 'permisos' => $permisos, 'formatoHora' => $formatoHora, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('operaciones/compras/comprobanteGenerado', $array)->with('status', 'Se registro compra exitosamente');
    }

    public function descargarValeComprasPDF($id)
    {
        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');
        $compraSelect = $loadDatos->getCompraselect($id);
        $serieCompra = substr($compraSelect->Serie, 1);
        $fecha = date_create($compraSelect->FechaCreacion);
        $formatoFecha = date_format($fecha, 'd-m-Y');
        $formatoHora = date_format($fecha, 'H:i A');
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $inicialRuc = substr($empresa->Ruc, 0, 2);
        if ($inicialRuc == '10') {
            $nombreEmpresa = $empresa->NombreComercial;
        } else {
            $nombreEmpresa = $empresa->Nombre;
        }
        $convertirLetras = new NumeroALetras();
        if ($compraSelect->IdTipoMoneda == 1) {
            $importeLetras = $convertirLetras->convertir($compraSelect->Total, 'soles');
        } else {
            $importeLetras = $convertirLetras->convertir($compraSelect->Total, 'dólares');
        }
        $numeroCerosIzquierda = $this->completarCeros($compraSelect->Numero);
        $items = $loadDatos->getItemsCompras($id);
        $array = ['items' => $items, 'compraSelect' => $compraSelect, 'numeroCeroIzq' => $numeroCerosIzquierda, 'empresa' => $empresa, 'nombreEmpresa' => $nombreEmpresa,
            'formatoFecha' => $formatoFecha, 'formatoHora' => $formatoHora, 'importeLetras' => $importeLetras];
        view()->share($array);
        $pdf = PDF::loadView('valeComprasPDF');
        return $pdf->download('VAC' . $serieCompra . '-' . $numeroCerosIzquierda . '.pdf');
    }

    public function imprimirPDF($id)
    {
        $pdf = $this->generarPDF($id);
        /*$loadDatos = new DatosController();
        $ventaSelect = $loadDatos->getVentaselect( $id );
        $numeroCerosIzquierda = $this->completarCeros( $ventaSelect->Numero );
        $serie = $ventaSelect->Serie;
         */
        return $pdf->stream();
    }

    public function descargarPDF($id)
    {
        $pdf = $this->generarPDF($id);
        return $pdf->download('pdfCompras' . $id . '.pdf');
    }

    private function generarPDF($id)
    {
        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');
        $compraSelect = $loadDatos->getCompraselect($id);
        $fecha = date_create($compraSelect->FechaCreacion);
        $formatoFecha = date_format($fecha, 'd-m-Y');
        $formatoHora = date_format($fecha, 'H:i A');
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $inicialRuc = substr($empresa->Ruc, 0, 2);
        if ($inicialRuc == '10') {
            $nombreEmpresa = $empresa->NombreComercial;
        } else {
            $nombreEmpresa = $empresa->Nombre;
        }
        $convertirLetras = new NumeroALetras();
        if ($compraSelect->IdTipoMoneda == 1) {
            $importeLetras = $convertirLetras->convertir($compraSelect->Total, 'soles');
        } else {
            $importeLetras = $convertirLetras->convertir($compraSelect->Total, 'dólares');
        }

        $numeroCerosIzquierda = $this->completarCeros($compraSelect->Numero);

        $items = $loadDatos->getItemsCompras($id);
        $array = ['items' => $items, 'compraSelect' => $compraSelect, 'numeroCeroIzq' => $numeroCerosIzquierda, 'empresa' => $empresa, 'nombreEmpresa' => $nombreEmpresa,
            'formatoFecha' => $formatoFecha, 'formatoHora' => $formatoHora, 'importeLetras' => $importeLetras];
        view()->share($array);
        $pdf = PDF::loadView('comprasPDF');
        return $pdf;
    }

    // public function paginationProductos(Request $req)
    // {
    //     if ($req->ajax()) {
    //         $idSucursal = Session::get('idSucursal');
    //         $textCompra = Session::get('textCompra');
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

    //         if ($sucPrincipal->IdSucursal == $idSucursal) {
    //             $productos = $loadDatos->getProductosPagination($idSucursal, $textCompra, $req->tipoMoneda, $req->idCategoria);
    //         } else {
    //             $productos = $loadDatos->paginarAjaxProdSucursal($idSucursal, $textCompra, $req->tipoMoneda, $req->idCategoria);
    //         }

    //         return Response($productos);
    //     }
    // }

    // public function searchProducto(Request $req)
    // {
    //     if ($req->ajax()) {
    //         $loadDatos = new DatosController();
    //         $idSucursal = Session::get('idSucursal');
    //         Session::put('textCompra', $req->textoBuscar);

    //         $cod_cliente = DB::table('sucursal')
    //             ->select('CodigoCliente')
    //             ->where('IdSucursal', $idSucursal)
    //             ->first();

    //         $sucPrincipal = DB::table('sucursal')
    //             ->select('IdSucursal')
    //             ->where('CodigoCliente', $cod_cliente->CodigoCliente)
    //             ->where('Principal', 1)
    //             ->first();

    //         if ($sucPrincipal->IdSucursal == $idSucursal) {
    //             $articulos = $loadDatos->getBuscarProductosVentas($req->textoBuscar, $req->tipoMoneda, $idSucursal, $req->idCategoria);
    //         } else {
    //             $articulos = $loadDatos->buscarAjaxProdSucursal($req->textoBuscar, $req->tipoMoneda, $idSucursal, $req->idCategoria);
    //         }
    //         return Response($articulos);
    //     }
    // }

    public function selectProductos(Request $req)
    {
        if ($req->ajax()) {
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $textoBuscar = '';
            $fecha = Carbon::today();
            $articulos = [];
            $valorTipoCambio = '0.00';

            $data = DB::table('tipo_cambio')
                ->where('FechaCreacion', $fecha)
                ->where('IdSucursal', $idSucursal)
                ->get();

            if (count($data) > 0) {
                $articulos = $loadDatos->getBuscarProductosVentas($textoBuscar, $req->tipoMoneda, $idSucursal, 0);
                $valorTipoCambio = $data[0]->TipoCambioVentas;
                return Response([$articulos, 1, $valorTipoCambio]);
            } else {
                return Response([$articulos, 2, $valorTipoCambio]);
            }
        }
    }

    // Nueva funcion Para traer productos sin tipo de cambio

    public function selectProductosSinTipoCambio(Request $req)
    {
        if ($req->ajax()) {
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $textoBuscar = '';

            $articulos = $loadDatos->getBuscarProductosVentas($textoBuscar, $req->tipoMoneda, $idSucursal, 0);
            return Response($articulos);
        }
    }

    public function verificarCompra(Request $req)
    {
        if ($req->ajax()) {
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $serie = $req->serie;
            $numero = $req->numero;
            $fechaCompra = $req->fechaCompra;
            $documento = $serie . '-' . $numero;
            $fechaBanco = Carbon::parse($fechaCompra);
            $fechaBanco = date_format($fechaBanco, 'Y-m-d');
            $tipo = 'Compras';
            //$fechaBanco  =  Carbon::createFromFormat('Y-m-d', $fechaCompra)->format('Y-m-d');
            $verificar = DB::table('banco_detalles')
                ->where('FechaPago', $fechaBanco)
                ->where('Detalle', $documento)
                ->where('TipoMovimiento', $tipo)
                ->get();

            return Response($verificar);
        }
    }

    public function anularCompra(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                $loadDatos = new DatosController();
                $idSucursal = Session::get('idSucursal');
                $idUsuario = Session::get('idUsuario');
                $idCompras = $req->idCompras;
                $idBanco = $req->idBanco;
                $compraSelect = $loadDatos->getCompraselect($idCompras);
                $itemsProductos = $loadDatos->getItemsCompras($idCompras);
                $reponer = $req->reponer;
                $stockSuficiente = $this->verificarStockSuficiente($itemsProductos);
                $fecha = $loadDatos->getDateTime();
                //dd($stockSuficiente);
                if (!empty($stockSuficiente)) {
                    return redirect('/operaciones/compras/lista-compras')->with('error', 'No hay stock suficiente en algunos de los productos');
                    //return Response(['alert10','Quedan '.$stockSuficiente[1].' unidades en stock de : '.$stockSuficiente[0]]);
                } else {
                    for ($i = 0; $i < count($itemsProductos); $i++) {
                        $productoSelect = $loadDatos->getProductoSelect($itemsProductos[$i]->IdArticulo);
                        $stockSelect = $loadDatos->getProductoStockSelect($itemsProductos[$i]->IdArticulo);
                        if ($productoSelect->IdTipo == 1) {
                            $cantidad = floatval($itemsProductos[$i]->Cantidad);

                            $cantidadActualizada = floatval($productoSelect->Stock - $cantidad);

                            DB::table('articulo')
                                ->where('IdArticulo', $productoSelect->IdArticulo)
                                ->decrement('Stock', $cantidad);

                            DB::table('stock')
                                ->where('IdStock', $stockSelect[0]->IdStock)
                                ->decrement('Cantidad', $cantidad);

                            $kardex = array(
                                'CodigoInterno' => $productoSelect->CodigoInterno,
                                'fecha_movimiento' => $fecha,
                                'tipo_movimiento' => 20,
                                'usuario_movimiento' => $idUsuario,
                                'documento_movimiento' => $compraSelect->Serie . '-' . $compraSelect->Numero,
                                'existencia' => $cantidadActualizada,
                                'costo' => floatval($itemsProductos[$i]->PrecioCosto),
                                'IdArticulo' => $productoSelect->IdArticulo,
                                'IdSucursal' => $idSucursal,
                                'Cantidad' => floatval($cantidad),
                                'Descuento' => 0,
                                'ImporteEntrada' => floatval($itemsProductos[$i]->Importe),
                                'ImporteSalida' => 0,
                                'estado' => 1,
                            );
                            DB::table('kardex')->insert($kardex);
                        }
                    }

                    DB::table('compras')
                        ->where('IdCompras', $idCompras)
                        ->update(['Estado' => 'Anulado']);

                    if ($reponer != null && $idBanco != 0) {
                        $documento = $compraSelect->Serie . '-' . $compraSelect->Numero;
                        $banco = DB::table('banco_detalles')
                            ->where('TipoMovimiento', 'Compras')
                            ->where('Detalle', $documento)
                            ->where('IdBanco', $idBanco)
                            ->first();

                        $montoCuenta = $loadDatos->getCuentaCorrienteSelect($idBanco);
                        $montoActual = floatval($montoCuenta->MontoActual) + floatval($banco->Salida);
                        $fechaRepos = $loadDatos->getDateTime();
                        $arrayDatos = ['FechaPago' => $fechaRepos, 'IdBanco' => $idBanco, 'NumeroOperacion' => null, 'Detalle' => $documento, 'TipoMovimiento' => 'Anulación de Compra', 'Entrada' => $banco->Salida, 'Salida' => '0', 'MontoActual' => $montoActual, 'IdSucursal' => $idSucursal];
                        DB::table('banco_detalles')->insert($arrayDatos);

                        DB::table('banco')->where('IdBanco', $idBanco)->update(['MontoActual' => $montoActual]);
                    }
                }

                return redirect('/operaciones/compras/lista-compras')->with('status', 'Se realizo la anulación de compra correctamente');

            } else {
                Session::flush();
                return redirect('/')->with('out', 'Sesión de usuario Expirado');
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function verificarStockSuficiente($itemsProductos)
    {
        $loadDatos = new DatosController();
        $array = [];
        for ($i = 0; $i < count($itemsProductos); $i++) {
            $sumador = 0;
            $cantidadTotal = 0;
            $producto = substr($itemsProductos[$i]->Cod, 0, 3);
            $productoSelect = $loadDatos->getProductoSelect($itemsProductos[$i]->IdArticulo);
            if ($producto == 'PRO') {
                $cantidadTotal = $itemsProductos[$i]->Cantidad;
                if ($cantidadTotal > $productoSelect->Stock) {
                    array_push($array, $productoSelect->Descripcion);
                    //array_push($array, $productoSelect->Stock);
                    //return $array;
                }
            }
        }
        return $array;
    }

    public function guardarTipoCambio(Request $req)
    {
        if ($req->ajax()) {
            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
            $fecha = Carbon::today();

            $tipoCambioCompras = $req->tipoCambioCompras;
            $tipoCambioVentas = $req->tipoCambioVentas;

            if ($tipoCambioCompras == null || $tipoCambioCompras == '') {
                return Response(['alert', 'Completar el campo de tipo de cambio de compras']);
            }

            if ($tipoCambioVentas == null || $tipoCambioVentas == '') {
                return Response(['alert', 'Completar el campo de tipo de cambio de ventas']);
            }

            $array = ['FechaCreacion' => $fecha, 'IdUsuario' => $idUsuario, 'IdSucursal' => $idSucursal, 'TipoCambioCompras' => $tipoCambioCompras, 'TipoCambioVentas' => $tipoCambioVentas, 'Estado' => 'E'];
            DB::table('tipo_cambio')->insert($array);

            return Response(['success', 'Se guardo tipo de cambio correctamente']);
        }
    }
    // -----------------------------------

    private function verificarCodigo($serie, $numero, $idSucursal, $idProveedor)
    {
        try {
            $resultado = DB::table('compras')
                ->select(DB::raw('count(IdCompras) as Cantidad'))
                ->where('Serie', $serie)
                ->where('Numero', $numero)
                ->where('IdSucursal', $idSucursal)
                ->where('IdProveedor', $idProveedor)
                ->whereIn('Estado', ['Registrado', 'Pendiente'])
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // private function verificarCodigo($serie, $numero, $idSucursal)
    // {
    //     try {
    //         $resultado = DB::table('ventas')
    //             ->select(DB::raw('count(IdVentas) as Cantidad'))
    //             ->where('Serie', $serie)
    //             ->where('Numero', $numero)
    //             ->where('Numero', $idSucursal)
    //             ->first();
    //         return $resultado;
    //     } catch (Exception $ex) {
    //         echo $ex->getMessage();
    //     }
    // }

    private function completarCeros($numero)
    {
        $numeroConCeros = str_pad($numero, 8, '0', STR_PAD_LEFT);
        return $numeroConCeros;
    }

    protected function validateCompra(Request $request)
    {
        $this->validate($request, [
            'serie' => 'required',
            'numero' => 'required',
        ]);
    }

    // Nuevas Funciones

    public function ultimosTreintaDias()
    {
        $fechaInicio = Carbon::now()->subDays(90)->format('Y-m-d');
        // return array($fechaInicio);
        return $fechaInicio;

    }

    public function getCompras($idSucursal, $fecha)
    {
        try {
            $compras = DB::table('compras')
                ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
                ->join('sucursal', 'compras.IdSucursal', '=', 'sucursal.IdSucursal')
                ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
                ->join('tipo_comprobante', 'compras.IdTipoComprobante', '=', 'tipo_comprobante.IdTipoComprobante')
                ->select('compras.*', 'proveedor.Nombre as Nombres', 'proveedor.NumeroDocumento', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario', 'tipo_comprobante.Descripcion as NombreComprobante', DB::raw("DATE_FORMAT(compras.FechaCreacion, '%d-%m-%Y' )AS fechaCompras"))
                ->where('compras.IdSucursal', $idSucursal)
                ->where('compras.FechaCreacion', '>=', $fecha)
                ->orderBy('IdCompras', 'desc')
                ->get();
            return $compras;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // public function getCompras($idSucursal, $fecha)
    // {
    //     try {
    //         $compras = DB::table('compras')
    //             ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
    //             ->join('sucursal', 'compras.IdSucursal', '=', 'sucursal.IdSucursal')
    //             ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
    //             ->join('tipo_comprobante', 'compras.IdTipoComprobante', '=', 'tipo_comprobante.IdTipoComprobante')
    //             ->select('compras.*', 'proveedor.Nombre as Nombres', 'proveedor.NumeroDocumento', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario', 'tipo_comprobante.Descripcion as NombreComprobante', DB::raw("DATE_FORMAT(compras.FechaCreacion, '%d-%m-%Y' )AS fechaCompras"))
    //             ->where('compras.IdSucursal', $idSucursal)
    //             ->where('compras.FechaCreacion', '>=', $fecha)
    //             ->orderBy('IdCompras', 'desc')
    //             ->get();
    //         return $compras;
    //     } catch (Exception $ex) {
    //         echo $ex->getMessage();
    //     }
    // }

    private function verificarItemsCompraPendiente($idArticulo, $idCompraPendiente)
    {
        $resultado = DB::table('compras_articulo')
            ->where('IdArticulo', $idArticulo)
            ->where('IdCompras', $idCompraPendiente)
            ->first();
        return $resultado;
    }

    private function itemsDetalleCompra($idCompraPendiente)
    {
        try {
            $listaCompras = DB::table('compras_articulo')
                ->join('compras', 'compras_articulo.IdCompras', '=', 'compras.IdCompras')
                ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
                ->join('articulo', 'compras_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->select('compras_articulo.*', 'compras.*', 'articulo.Descripcion', 'articulo.Precio', 'proveedor.Nombre as Nombres', 'unidad_medida.Nombre AS UM')
                ->where('compras_articulo.IdCompras', $idCompraPendiente)
                ->orderBy('compras_articulo.IdComprasArticulo', 'asc')
                ->get();
            return $listaCompras;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    // Fin

    // Nuevas Funciones Orden de compra

    public function getOrdenesCompras($idSucursal, $fecha)
    {
        try {
            $ordencompras = DB::table('orden_compra')
                ->join('proveedor', 'orden_compra.IdProveedor', '=', 'proveedor.IdProveedor')
                ->join('sucursal', 'orden_compra.IdSucursal', '=', 'sucursal.IdSucursal')
                ->join('usuario', 'orden_compra.IdUsuarioCreacion', '=', 'usuario.IdUsuario')
                ->select('orden_compra.*', 'proveedor.Nombre as Nombres', 'proveedor.NumeroDocumento', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario', DB::raw("DATE_FORMAT(orden_compra.FechaEmision, '%d-%m-%Y' )AS fechaCompras"))
                ->where('orden_compra.IdSucursal', $idSucursal)
                ->where('orden_compra.FechaEmision', '>=', $fecha)
                ->where('orden_compra.Estado', 'Pendiente')
                ->orderBy('IdOrdenCompra', 'desc')
                ->get();
            return $ordencompras;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getOrdenesComprasSelect($idOrdenCompra)
    {
        try {
            $ordencompras = DB::table('orden_compra')
                ->join('proveedor', 'orden_compra.IdProveedor', '=', 'proveedor.IdProveedor')
                ->join('tipo_documento', 'proveedor.IdTipoDocumento', '=', 'tipo_documento.IdTipoDocumento')
                ->join('sucursal', 'orden_compra.IdSucursal', '=', 'sucursal.IdSucursal')
                ->join('usuario', 'orden_compra.IdUsuarioCreacion', '=', 'usuario.IdUsuario')
                ->select('orden_compra.*', 'proveedor.Nombre as Nombres', 'proveedor.NumeroDocumento', 'proveedor.RazonSocial', 'proveedor.Direccion as DireccionProveedor', 'proveedor.Telefono as TelefonoProveedor', 'proveedor.Email as EmailProveedor', 'sucursal.Direccion as Local', 'sucursal.Ciudad as CiudadSucursal', 'sucursal.Nombre as Sucursal', 'sucursal.Ciudad', 'sucursal.Telefono as TelfSucursal', 'usuario.Nombre as Usuario', 'usuario.DNI as DNI', 'tipo_documento.Descripcion as TipoDocumento', 'tipo_documento.IdTipoDocumento', DB::raw("DATE_FORMAT(orden_compra.FechaEmision, '%d-%m-%Y' )AS FechaOrdenCompra"), DB::raw("DATE_FORMAT(orden_compra.FechaRecepcion, '%d-%m-%Y' )AS FechaRecepcion"))
                ->where('orden_compra.IdOrdenCompra', $idOrdenCompra)
                ->orderBy('IdOrdenCompra', 'desc')
                ->first();
            return $ordencompras;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getItemsOrdenCompra($idOrdenCompra)
    {
        try {
            $ordenCompra = DB::table('detalle_orden_compra')
                ->join('articulo', 'detalle_orden_compra.IdArticulo', '=', 'articulo.IdArticulo')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->select('detalle_orden_compra.*', 'detalle_orden_compra.CodigoArticulo', 'articulo.*', 'unidad_medida.Nombre as UniMedida')
                ->where('detalle_orden_compra.IdOrdenCompra', $idOrdenCompra)
                ->get();

            for ($i = 0; $i < count($ordenCompra);
                $i++) {
                if ($ordenCompra[$i]->IdTipo == 1) {
                    $ordenCompra[$i]->NombreMarca = DB::table('marca')
                        ->join('articulo', 'marca.IdMarca', '=', 'articulo.IdMarca')
                        ->select('marca.Nombre as nombreMarca')
                        ->where('IdArticulo', $ordenCompra[$i]->IdArticulo)
                        ->first();
                } else {
                    $ordenCompra[$i]->NombreMarca = '';
                }
            }
            return $ordenCompra;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function getCuentasCorrientes($codigoCliente)
    {
        $resultado = DB::table('banco')
            ->join('lista_banco', 'banco.IdListaBanco', '=', 'lista_banco.IdListaBanco')
            ->join('tipo_moneda', 'banco.IdTipoMoneda', '=', 'tipo_moneda.IdTipoMoneda')
            ->select('banco.NumeroCuenta', 'lista_banco.Nombre as Banco', 'tipo_moneda.Nombre as Moneda', 'banco.CCI')
            ->where('CodigoCliente', $codigoCliente)
            ->where('banco.Estado', 'E')
            ->limit(5)
            ->get();
        return $resultado;
    }

    public function validarPasswordSupervisor(Request $req)
    {
        if ($req->ajax()) {
            $idUsuario = Session::get('idUsuario');

            $idSucursal = Session::get('idSucursal');
            $loadDatos = new DatosController();
            $password = $req->password;
            $usuarioCodigo = $loadDatos->getUsuarioSelect($idUsuario);
            $codigoCliente = $usuarioCodigo->CodigoCliente;
            $respuesta = DB::table('usuario')
                ->select('usuario.ClaveDeComprobacion', 'usuario.Nombre', 'usuario.IdSucursal')
                ->where('usuario.CodigoCliente', $codigoCliente)
                ->where('usuario.Cliente', 1)
                ->get();

            if (count($respuesta) > 0) {
                if ((password_verify($password, $respuesta[0]->ClaveDeComprobacion))) {
                    $password = (password_verify($password, $respuesta[0]->ClaveDeComprobacion));
                    return Response(['Success', 'La clave si coincide']);
                }
            }
        }
    }

    public function actualizarDocumento(Request $req)
    {
        if ($req->ajax()) {
            $idSucursal = Session::get('idSucursal');
            $serie = $req->serie;
            $numero = $req->numero;
            $serieAnterior = $req->serieAnterior;
            $numeroAnterior = $req->numeroAnterior;
            $idProveedor = $req->idProveedor;
            $idCompra = $req->idCompra;
            $idTipoDocumento = $req->idTipoDocumento;
            $fechaCreacion = $req->fechaCreacion;
            $idUsuarioCompra = $req->idUsuarioCompra;
            $respuesta = $this->verificarExistenciaDocumento($idTipoDocumento, $serie, $numero, $idSucursal, $idProveedor);
            if ($serie == null) {
                return Response(['alert01', 'Ingrese la serie']);
            }
            if ($numero == null) {
                return Response(['alert02', 'Ingrese el número']);
            }

            $array = ['idTipoComprobante' => $idTipoDocumento, 'Serie' => $serie, 'Numero' => $numero, 'IdProveedor' => $idProveedor];

            if ($respuesta) {
                return Response(['documentoDuplicado', 'La serie y numero ya estan registrados, vuelva ingresarlo']);

            } else {
                DB::table('compras')
                    ->where('IdCompras', $idCompra)
                    ->update($array);

                DB::table('kardex')
                    ->where('fecha_movimiento', $fechaCreacion)
                    ->where('tipo_movimiento', 2)
                    ->where('documento_movimiento', $serieAnterior . '-' . $numeroAnterior)
                    ->where('usuario_movimiento', $idUsuarioCompra)
                    ->update(['documento_movimiento' => $serie . '-' . $numero]);
                return Response(['Success', 'El documento fue actualizado correctamente']);
            }

        }
    }

    public function verificarExistenciaDocumento($idTipoDocumento, $serie, $numero, $idSucursal, $idProveedor)
    {
        try {
            $resultado = DB::table('compras')
                ->where('Serie', $serie)
                ->where('Numero', $numero)
                ->where('IdSucursal', $idSucursal)
                ->where('IdProveedor', $idProveedor)
                ->where('IdTipoComprobante', $idTipoDocumento)
                ->exists();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

}
