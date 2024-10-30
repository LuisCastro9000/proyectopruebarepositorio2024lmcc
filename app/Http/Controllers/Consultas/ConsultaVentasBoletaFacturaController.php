<?php

namespace App\Http\Controllers\Consultas;

use App\Http\Controllers\ClasesPublicas\CajaController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use App\Http\Controllers\Servicios\config;
use Carbon\Carbon;
use DateTime;
use DB;
use DOMDocument;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Voided\Voided;
use Greenter\Model\Voided\VoidedDetail;
use Greenter\Ws\Services\SunatEndpoints;
use Illuminate\Http\Request;
use PDF;
use PHPMailer\PHPMailer\PHPMailer;
use Session;

class ConsultaVentasBoletaFacturaController extends Controller
{
    protected $claseCaja;

    public function __construct(CajaController $claseCaja)
    {
        $this->claseCaja = $claseCaja;
    }

    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');

            $facturasVentas = $loadDatos->getVentasAll($idSucursal);

            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $date = Carbon::today();
            $dateAtras = $date->subDays(3)->startOfDay()->format("Y-m-d H:i:s");
            $date1 = Carbon::today();
            $dateAtrasTicket = $date1->subMonths(4)->startOfDay()->format("Y-m-d H:i:s");
            $tipoPago = '';
            $fecha = '';
            $fechaIni = '';
            $fechaFin = '';
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);

            //////// Para descuento de caja ////////
            // $caja = $loadDatos->getCierreCajaUltimo($idSucursal, $idUsuario);
            // $cobranzasSoles = $loadDatos->getDetalleCajaCobranzas($idSucursal, $idUsuario, $caja->FechaApertura, 1);
            // $ventasContadoTotalSoles = $loadDatos->getDetalleCajaContado($idSucursal, $idUsuario, $caja->FechaApertura, 1);
            // $ventasContadoEfectivoSoles = $ventasContadoTotalSoles[0]->Efectivo;
            // $cobranzasEfectivoSoles = $cobranzasSoles[0]->Efectivo;
            // $ingresosSoles = $loadDatos->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, 'I', 1);
            // if ($ingresosSoles[0]->Monto == null) {
            //     $montoIngresosSoles = '0.00';
            // } else {
            //     $montoIngresosSoles = $ingresosSoles[0]->Monto;
            // }
            // $egresosSoles = $loadDatos->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, 'E', 1);
            // if ($egresosSoles[0]->Monto == null) {
            //     $montoEgresosSoles = '0.00';
            // } else {
            //     $montoEgresosSoles = $egresosSoles[0]->Monto;
            // }
            // $inicial = $caja->Inicial;
            // $cajaTotalSoles = floatval($cobranzasEfectivoSoles) + floatval($ventasContadoEfectivoSoles) + floatval($inicial) + floatval($montoIngresosSoles) - floatval($montoEgresosSoles);
            // Agregado totalCajaSoles
            $cajaTotalSoles = $this->claseCaja->obtenerTotalCajaAbierta(1);
            // Fin

            // $cobranzasDolares = $loadDatos->getDetalleCajaCobranzas($idSucursal, $idUsuario, $caja->FechaApertura, 2);
            // $ventasContadoTotalDolares = $loadDatos->getDetalleCajaContado($idSucursal, $idUsuario, $caja->FechaApertura, 2);
            // $ventasContadoEfectivoDolares = $ventasContadoTotalDolares[0]->Efectivo;
            // $cobranzasEfectivoDolares = $cobranzasDolares[0]->Efectivo;
            // $ingresosDolares = $loadDatos->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, 'I', 2);
            // if ($ingresosDolares[0]->Monto == null) {
            //     $montoIngresosDolares = '0.00';
            // } else {
            //     $montoIngresosDolares = $ingresosDolares[0]->Monto;
            // }
            // $egresosDolares = $loadDatos->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, 'E', 2);
            // if ($egresosDolares[0]->Monto == null) {
            //     $montoEgresosDolares = '0.00';
            // } else {
            //     $montoEgresosDolares = $egresosDolares[0]->Monto;
            // }
            // $inicialDolares = $caja->InicialDolares;
            // $cajaTotalDolares = floatval($cobranzasEfectivoDolares) + floatval($ventasContadoEfectivoDolares) + floatval($inicialDolares) + floatval($montoIngresosDolares) - floatval($montoEgresosDolares);
            // Agregado totalCajaSoles
            $cajaTotalDolares = $this->claseCaja->obtenerTotalCajaAbierta(2);
            // Fin

            $array = ['facturasVentas' => $facturasVentas, 'cajaTotalSoles' => $cajaTotalSoles, 'cajaTotalDolares' => $cajaTotalDolares, 'permisos' => $permisos, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'dateAtras' => $dateAtras, 'dateAtrasTicket' => $dateAtrasTicket, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'rucEmpresa' => $empresa->Ruc];
            return view('consultas/consultaVentasBoletaFactura', $array);
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
        $loadDatos = new DatosController();
        $tipoPago = $req->tipoPago;
        $fecha = $req->fecha;
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        $date = Carbon::today();
        $dateAtras = $date->subDays(3)->startOfDay()->format("Y-m-d H:i:s");
        $date1 = Carbon::today();
        $dateAtrasTicket = $date1->subMonths(4)->startOfDay()->format("Y-m-d H:i:s");
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

        $idSucursal = $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $facturasVentas = $loadDatos->getVentasAllFiltrado($idSucursal, $tipoPago, $fecha, $fechaIni, $fechaFin);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);

        //////// Para descuento de caja ////////
        $caja = $loadDatos->getCierreCajaUltimo($idSucursal, $idUsuario);
        if ($caja != null) {
            $cobranzasSoles = $loadDatos->getDetalleCajaCobranzas($idSucursal, $idUsuario, $caja->FechaApertura, 1);
            $ventasContadoTotalSoles = $loadDatos->getDetalleCajaContado($idSucursal, $idUsuario, $caja->FechaApertura, 1);
            $ventasContadoEfectivoSoles = $ventasContadoTotalSoles[0]->Efectivo;
            $cobranzasEfectivoSoles = $cobranzasSoles[0]->Efectivo;
            $ingresosSoles = $loadDatos->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, 'I', 1);
            if ($ingresosSoles[0]->Monto == null) {
                $montoIngresosSoles = '0.00';
            } else {
                $montoIngresosSoles = $ingresosSoles[0]->Monto;
            }
            $egresosSoles = $loadDatos->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, 'E', 1);
            if ($egresosSoles[0]->Monto == null) {
                $montoEgresosSoles = '0.00';
            } else {
                $montoEgresosSoles = $egresosSoles[0]->Monto;
            }
            $inicial = $caja->Inicial;
            $cajaTotalSoles = floatval($cobranzasEfectivoSoles) + floatval($ventasContadoEfectivoSoles) + floatval($inicial) + floatval($montoIngresosSoles) - floatval($montoEgresosSoles);

            $cobranzasDolares = $loadDatos->getDetalleCajaCobranzas($idSucursal, $idUsuario, $caja->FechaApertura, 2);
            $ventasContadoTotalDolares = $loadDatos->getDetalleCajaContado($idSucursal, $idUsuario, $caja->FechaApertura, 2);
            $ventasContadoEfectivoDolares = $ventasContadoTotalDolares[0]->Efectivo;
            $cobranzasEfectivoDolares = $cobranzasDolares[0]->Efectivo;
            $ingresosDolares = $loadDatos->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, 'I', 2);
            if ($ingresosDolares[0]->Monto == null) {
                $montoIngresosDolares = '0.00';
            } else {
                $montoIngresosDolares = $ingresosDolares[0]->Monto;
            }
            $egresosDolares = $loadDatos->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, 'E', 2);
            if ($egresosDolares[0]->Monto == null) {
                $montoEgresosDolares = '0.00';
            } else {
                $montoEgresosDolares = $egresosDolares[0]->Monto;
            }
            $inicialDolares = $caja->InicialDolares;
            $cajaTotalDolares = floatval($cobranzasEfectivoDolares) + floatval($ventasContadoEfectivoDolares) + floatval($inicialDolares) + floatval($montoIngresosDolares) - floatval($montoEgresosDolares);
            /////////////////////////
        } else {
            $cajaTotalSoles = 0;
            $cajaTotalDolares = 0;
        }

        $array = ['facturasVentas' => $facturasVentas, 'cajaTotalSoles' => $cajaTotalSoles, 'cajaTotalDolares' => $cajaTotalDolares, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'dateAtras' => $dateAtras, 'dateAtrasTicket' => $dateAtrasTicket, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'rucEmpresa' => $empresa->Ruc];
        return view('consultas/consultaVentasBoletaFactura', $array);
    }

    public function anular(Request $req)
    {
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');
        $idVenta = $req->idVenta;
        $descripcion = $req->descripcion;
        if ($descripcion == null || $descripcion == '') {
            $descripcion = "Error";
        }
        $date = Carbon::now();
        $fechaConvertida = $date->format("Y-m-d H:i:s");
        $totalDescontar = $req->totalDescontar;
        $ventaSelect = $loadDatos->getVentaselect($idVenta);
        // $totalCaja = $req->totalCaja;
        // Agregado totalCaja
        $totalCaja = $this->claseCaja->obtenerTotalCajaAbierta($ventaSelect->IdTipoMoneda);
        // Fin
        $descEgreso = "Descuento Baja Documento " . $ventaSelect->Serie . " - " . $ventaSelect->Numero;
        $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
        $descontar = $req->descontar;
        $tipo = 'E';
        if ($descontar == null) {
            if ($ventaSelect->IdTipoComprobante == 2) {
                $res = $this->darBajaDocumento($ventaSelect, $idUsuario, $fechaConvertida, $descripcion);
                if (is_numeric($res)) {
                    if ($res == 1) {
                        return redirect('/consultas/ventas-boletas-facturas')->with('status', 'Se envio baja documento correctamente');
                    } else {
                        return redirect('/consultas/ventas-boletas-facturas')->with('error', 'No se pudo obtener CDR de Baja de Documentos');
                    }
                } else {
                    return redirect('/consultas/ventas-boletas-facturas')->with('error', $res);
                }
            } elseif ($ventaSelect->IdTipoComprobante == 1) {
                DB::table('ventas')
                    ->where('IdVentas', $idVenta)
                    ->update(['FechaModificacion' => $fechaConvertida, 'MotivoAnulacion' => $descripcion, 'Estado' => 'Baja Pendiente']);

                return redirect('/consultas/ventas-boletas-facturas')->with('status', 'Se agrego a la lista de Baja de Documentos con éxito');
            } else {
                DB::table('ventas')
                    ->where('IdVentas', $idVenta)
                    ->update(['MotivoAnulacion' => $descripcion, 'Estado' => 'Baja Ticket']);
                $this->descontarStockProductos($ventaSelect, $idUsuario);
                return redirect('/consultas/ventas-boletas-facturas')->with('status', 'Se dio de baja al Ticket correctamente');
            }
        } else {
            if ($caja == null) {
                return redirect('/consultas/ventas-boletas-facturas')->with('error', 'No se pudo realizar la anulación, abrir la caja antes de realizar un descuento');
            } else {
                if ($ventaSelect->IdTipoPago == 1) {
                    if (floatval($totalDescontar) > floatval($totalCaja)) {
                        return redirect('/consultas/ventas-boletas-facturas')->with('error', 'No hay suficiente dinero en caja');
                    } else {

                        $array = ['IdCaja' => $caja->IdCaja, 'Fecha' => $fechaConvertida, 'Tipo' => $tipo, 'IdTipoMoneda' => $ventaSelect->IdTipoMoneda, 'Monto' => $totalDescontar, 'Descripcion' => $descEgreso];
                        DB::table('ingresoegreso')->insert($array);

                        if ($ventaSelect->IdTipoComprobante == 2) {
                            $res = $this->darBajaDocumento($ventaSelect, $idUsuario, $fechaConvertida, $descripcion);

                            if (is_numeric($res)) {
                                if ($res == 1) {
                                    return redirect('/consultas/ventas-boletas-facturas')->with('status', 'Se envio baja documento correctamente');
                                } else {
                                    return redirect('/consultas/ventas-boletas-facturas')->with('error', 'No se pudo obtener CDR de Baja de Documentos');
                                }
                            } else {
                                return redirect('/consultas/ventas-boletas-facturas')->with('error', $res);
                            }
                        } elseif ($ventaSelect->IdTipoComprobante == 1) {
                            DB::table('ventas')
                                ->where('IdVentas', $idVenta)
                                ->update(['FechaModificacion' => $fechaConvertida, 'MotivoAnulacion' => $descripcion, 'Estado' => 'Baja Pendiente']);

                            return redirect('/consultas/ventas-boletas-facturas')->with('status', 'Se agrego a la lista de Baja de Documentos con éxito');
                        } else {
                            DB::table('ventas')
                                ->where('IdVentas', $idVenta)
                                ->update(['MotivoAnulacion' => $descripcion, 'Estado' => 'Baja Ticket']);

                            $this->descontarStockProductos($ventaSelect, $idUsuario);
                            return redirect('/consultas/ventas-boletas-facturas')->with('status', 'Se dio de baja al Ticket correctamente');
                        }
                    }
                } else {
                    if ($ventaSelect->IdTipoComprobante == 2) {
                        $res = $this->darBajaDocumento($ventaSelect, $idUsuario, $fechaConvertida, $descripcion);
                        if (is_numeric($res)) {
                            if ($res == 1) {
                                return redirect('/consultas/ventas-boletas-facturas')->with('status', 'Se envio baja documento correctamente');
                            } else {
                                return redirect('/consultas/ventas-boletas-facturas')->with('error', 'No se pudo obtener CDR de Baja de Documentos');
                            }
                        } else {
                            return redirect('/consultas/ventas-boletas-facturas')->with('error', $res);
                        }
                    } elseif ($ventaSelect->IdTipoComprobante == 1) {
                        DB::table('ventas')
                            ->where('IdVentas', $idVenta)
                            ->update(['FechaModificacion' => $fechaConvertida, 'MotivoAnulacion' => $descripcion, 'Estado' => 'Baja Pendiente']);

                        return redirect('/consultas/ventas-boletas-facturas')->with('status', 'Se agrego a la lista de Baja de Documentos con éxito');
                    } else {
                        DB::table('ventas')
                            ->where('IdVentas', $idVenta)
                            ->update(['MotivoAnulacion' => $descripcion, 'Estado' => 'Baja Ticket']);
                        $this->descontarStockProductos($ventaSelect, $idUsuario);
                        return redirect('/consultas/ventas-boletas-facturas')->with('status', 'Se dio de baja al Ticket correctamente');
                    }
                }
            }
        }
    }

    private function darBajaDocumento($ventaSelect, $idUsuario, $fechaConvertida, $descripcion)
    {
        $opcionFactura = DB::table('usuario')
            ->select('OpcionFactura')
            ->where('IdUsuario', $idUsuario)
            ->first();
        $config = new config();
        if ($opcionFactura->OpcionFactura > 0) {
            if ($opcionFactura->OpcionFactura == 1) //sunat
            {
                $see = $config->configuracion(SunatEndpoints::FE_BETA);
            } else if ($opcionFactura->OpcionFactura == 2) //ose
            {
                $see = $config->configuracion('https://demo-ose.nubefact.com/ol-ti-itcpe/billService?wsdl');
            } else {
                return Response(['error', 'No  tiene Elejida la  ruta de  envio.... comuniquese con  el administrador']);
            }
        } else {
            return Response(['error', 'No  tiene Elejida  el tipo de  envio.... comuniquese con  el administrador']);
        }

        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        //$idUsuario = Session::get('idUsuario');
        $sucursal = $loadDatos->getSucursalSelect($idSucursal);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $correlativo = $loadDatos->getCorrelativoBajaDocumento($idSucursal, Carbon::today());
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

        //$array = [];
        //$denegado = [];
        //for($i=0; $i<count($documentos); $i++){
        //if($documentos[$i]->Tipo ==  "Factura"){
        //$tipoComprob = '01';
        /*}else{
        $tipoComprob = '07';
        }*/
        $detail = new VoidedDetail();
        $detail->setTipoDoc('01')
            ->setSerie($ventaSelect->Serie)
            ->setCorrelativo($ventaSelect->Numero)
            ->setDesMotivoBaja($descripcion);
        //array_push($array, $detail);
        //array_push($denegado, $documentos[$i]->IdDoc);
        //}

        $cantidad = intval($correlativo->Cantidad);
        $voided = new Voided();
        $voided->setCorrelativo($cantidad + 1)
            ->setFecGeneracion(new DateTime($ventaSelect->FechaCreacion))
            ->setFecComunicacion(new DateTime())
            ->setCompany($company)
            ->setDetails([$detail]);
        // Envio a SUNAT.
        //$see = $util->getSee();
        $res1 = $see->send($voided);
        //dd($res1);
        if (!$res1->isSuccess()) {
            if ($res1->getError()->getCode() == 'HTTP' || $res1->getError()->getCode() == 'HTTPS') {
                echo "<script language='javascript'>alert('Servicio inestable, intentelo en otro momento');</script>";
                $resumen = 'Servicio inestable, intentelo en otro momento';
            } else {
                $resumen = 'Error ' . $res1->getError()->getCode() . ': ' . $res1->getError()->getMessage();
                $_array = ['IdSucursal' => $idSucursal, 'IdUsuario' => $idUsuario, 'Hash' => '', 'FechaEmitida' => $ventaSelect->FechaCreacion, 'FechaEnviada' => Carbon::now(), 'Identificador' => '', 'Ticket' => '', 'IdVentas' => $ventaSelect->IdVentas, 'TipoDocumento' => 1, 'CodigoDoc' => $res1->getError()->getCode(), 'Estado' => 'Baja Rechazo'];
                DB::table('baja_documentos')->insert($_array);
            }
            DB::table('ventas')
                ->where('IdVentas', $ventaSelect->IdVentas)
                ->update(['FechaModificacion' => $fechaConvertida, 'MotivoAnulacion' => $descripcion, 'Estado' => 'Baja Pendiente']);
            //return back()->with('error',$res1->getError()->getMessage().'-'.$res1->getError()->getCode());
            return $res1->getError()->getMessage() . '-' . $res1->getError()->getCode();
        } else {
            /**@var $res \Greenter\Model\Response\SummaryResult*/
            $now = Carbon::now();
            $anio = $now->year;
            $mes = $now->month;
            $_mes = $loadDatos->getMes($mes);
            $nombreArchivo = $voided->getName();
            $rutaXml = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/BajaDocumentos/' . $nombreArchivo . '.xml';

            $config->writeXml($voided, $see->getFactory()->getLastXml(), $empresa->Ruc, $anio, $_mes, 5);
            $ticket = $res1->getTicket();
            sleep(2);
            $res2 = $see->getStatus($ticket);
            if ($res2->getCdrResponse() == null) {
                $_array = ['IdSucursal' => $idSucursal, 'IdUsuario' => $idUsuario, 'Hash' => '', 'FechaEmitida' => $ventaSelect->FechaCreacion, 'FechaEnviada' => Carbon::now(), 'Identificador' => '', 'Ticket' => $ticket, 'IdVentas' => $ventaSelect->IdVentas, 'TipoDocumento' => 1, 'RutaXml' => $rutaXml, 'Estado' => 'Baja Pendiente'];
                DB::table('baja_documentos')->insert($_array);

                /*for($i=0; $i<count($documentos); $i++){

                DB::table('ventas')
                ->where('IdVentas',$documentos[$i]->IdVentas)
                ->update(['Estado' => 'Baja Pendiente']);
                }*/

                //for($k=0; $k<count($denegado); $k++)
                //{
                $dene = ['IdVentas' => $ventaSelect->IdVentas, 'Ticket' => $ticket];
                DB::table('denegado_baja')->insert($dene);
                //}

                DB::table('ventas')
                    ->where('IdVentas', $ventaSelect->IdVentas)
                    ->update(['FechaModificacion' => $fechaConvertida, 'MotivoAnulacion' => $descripcion, 'Estado' => 'Baja Pendiente']);

                return 0;
                //return redirect('/reportes/facturacion/baja-documentos')->with('error','No se pudo obtener CDR de Baja de Documentos');
            } else {

                $bandBaja = 0;
                $bandExceccion = 0;
                $rutaCdr = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/BajaDocumentos/R-' . $nombreArchivo . '.zip';
                $cdr = $res2->getCdrResponse();
                //dd($voided);
                $config->writeCdr($voided, $res2->getCdrZip(), $empresa->Ruc, $anio, $_mes, 5);
                $config->showResponse($voided, $cdr);

                $xml_string = $see->getXmlSigned($voided);
                $doc = new DOMDocument();
                $doc->loadXML($xml_string);
                $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;

                $isAccetedCDR = $res2->getCdrResponse()->isAccepted();
                $descripcionCDR = $res2->getCdrResponse()->getDescription();
                $codeCDR = $res2->getCdrResponse()->getCode();

                if (intval($codeCDR) == 0) {
                    $codigoAceptado = $codeCDR;
                    $estado = 'Baja Aceptado';
                    $mensaje = $descripcionCDR;
                    $tipoMensaje = 'Baja Aceptado';
                } else if (intval($codeCDR) >= 100 && intval($codeCDR) <= 1999) {
                    $bandExceccion = 1;
                    $bandBaja = 1;
                    $codigoAceptado = $codeCDR;
                    $estado = 'Excepcion';
                    $mensaje = $descripcionCDR;
                    $tipoMensaje = 'Baja Pendiente';
                } else if (intval($codeCDR) >= 2000 && intval($codeCDR) <= 3999) {
                    $bandBaja = 1;
                    $codigoAceptado = $codeCDR;
                    $estado = 'Baja Rechazo';
                    $mensaje = $descripcionCDR;
                    $tipoMensaje = 'Baja Pendiente';
                } else {
                    $codigoAceptado = $codeCDR;
                    $estado = 'Baja Observada';
                    $mensaje = $descripcionCDR;
                    $tipoMensaje = 'Baja Aceptado';
                }
                //dd($estado);
                if ($bandExceccion == 0) {
                    $array = ['IdSucursal' => $idSucursal, 'IdUsuario' => $idUsuario, 'Hash' => $hash, 'FechaEmitida' => $ventaSelect->FechaCreacion, 'FechaEnviada' => Carbon::now(), 'Identificador' => $cdr->getId(), 'Ticket' => $ticket, 'CodigoDoc' => $codigoAceptado, 'IdVentas' => $ventaSelect->IdVentas, 'TipoDocumento' => 1, 'RutaXml' => $rutaXml, 'RutaCdr' => $rutaCdr, 'Estado' => $estado];
                    DB::table('baja_documentos')->insert($array);

                    $baja = DB::table('baja_documentos')
                        ->orderBy('IdBajaDoc', 'desc')
                        ->first();
                    $idBaja = $baja->IdBajaDoc;
                }

                if ($bandBaja == 0) {
                    //for($i=0; $i<count($documentos); $i++){
                    //if($documentos[$i]->Tipo == "Factura"){
                    DB::table('ventas')
                        ->where('IdVentas', $ventaSelect->IdVentas)
                        ->update(['MotivoAnulacion' => $descripcion, 'Estado' => $tipoMensaje]);

                    $stock = DB::table('ventas_articulo')
                        ->where('IdVentas', $ventaSelect->IdVentas)
                        ->get();

                    if (count($stock) >= 1) {
                        for ($j = 0; $j < count($stock); $j++) {
                            $articulo = DB::table('articulo')
                                ->where('IdArticulo', $stock[$j]->IdArticulo)
                                ->first();

                            if ($articulo->IdTipo == 1) {
                                $cantidaSum = floatval($articulo->Stock) + floatval($stock[$j]->Cantidad * $stock[$j]->CantidadReal);
                                DB::table('articulo')
                                    ->where('IdArticulo', $stock[$j]->IdArticulo)
                                    ->update(['Stock' => $cantidaSum]);

                                $_stock = $loadDatos->getProductoStockSelect($stock[$j]->IdArticulo);

                                $reponer = floatval($stock[$j]->Cantidad * $stock[$j]->CantidadReal);

                                DB::table('stock')
                                    ->where('IdStock', $_stock[0]->IdStock)
                                    ->increment('Cantidad', $reponer);

                                $kardex = array(
                                    'CodigoInterno' => $articulo->CodigoInterno,
                                    'fecha_movimiento' => Carbon::now(),
                                    'tipo_movimiento' => 6, //baja de documento
                                    'usuario_movimiento' => $idUsuario,
                                    'documento_movimiento' => $ventaSelect->Serie . '-' . $ventaSelect->Numero,
                                    'existencia' => $cantidaSum,
                                    'costo' => $articulo->Precio,
                                    'IdArticulo' => $stock[$j]->IdArticulo,
                                    'IdSucursal' => $idSucursal,
                                    'Cantidad' => $reponer,
                                    'Descuento' => 0,
                                    'ImporteEntrada' => 0,
                                    'ImporteSalida' => 0,
                                    'estado' => 1,
                                );
                                DB::table('kardex')->insert($kardex);

                                $arrayRelacion = ['IdBajaDocumento' => $idBaja, 'IdArticulo' => $stock[$j]->IdArticulo, 'Codigo' => 'PRO-' . $stock[$j]->IdArticulo, 'Descripcion' => $articulo->Descripcion, 'PrecioVenta' => $articulo->Precio, 'Cantidad' => $reponer, 'Descuento' => 0.0, 'Total' => 0.0];
                                DB::table('baja_detalle')->insert($arrayRelacion);
                            }

                        }
                    }
                    /*}else{
                    DB::table('nota_credito_debito')
                    ->where('IdCreditoDebito',$documentos[$i]->IdDoc)
                    ->update(['Estado' => $tipoMensaje]);
                    }*/
                    //}
                }

                return 1;
                /*return redirect('reportes/facturacion/baja-documentos')
            ->with('status', 'Se enviaron correctamente documentos con fecha: '.$fecha);*/
            }
        }
    }

    private function descontarStockProductos($ventaSelect, $idUsuario)
    {
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $stock = DB::table('ventas_articulo')
            ->where('IdVentas', $ventaSelect->IdVentas)
            ->get();

        if (count($stock) >= 1) {
            for ($j = 0; $j < count($stock); $j++) {
                $articulo = DB::table('articulo')
                    ->where('IdArticulo', $stock[$j]->IdArticulo)
                    ->first();

                if ($articulo->IdTipo == 1) {
                    $cantidaSum = floatval($articulo->Stock) + floatval($stock[$j]->Cantidad * $stock[$j]->CantidadReal);
                    DB::table('articulo')
                        ->where('IdArticulo', $stock[$j]->IdArticulo)
                        ->update(['Stock' => $cantidaSum]);

                    $_stock = $loadDatos->getProductoStockSelect($stock[$j]->IdArticulo);

                    $reponer = floatval($stock[$j]->Cantidad * $stock[$j]->CantidadReal);

                    DB::table('stock')
                        ->where('IdStock', $_stock[0]->IdStock)
                        ->increment('Cantidad', $reponer);

                    $kardex = array(
                        'CodigoInterno' => $articulo->CodigoInterno,
                        'fecha_movimiento' => Carbon::now(),
                        'tipo_movimiento' => 14, //baja de tickets
                        'usuario_movimiento' => $idUsuario,
                        'documento_movimiento' => $ventaSelect->Serie . " - " . $ventaSelect->Numero,
                        'existencia' => $cantidaSum,
                        'costo' => 1,
                        'IdArticulo' => $stock[$j]->IdArticulo,
                        'IdSucursal' => $idSucursal,
                        'Cantidad' => $reponer,
                        'Descuento' => 0,
                        'ImporteEntrada' => 0,
                        'ImporteSalida' => 0,
                        'estado' => 1,
                    );
                    DB::table('kardex')->insert($kardex);
                }

            }
        }
    }

    public function obtenerDatos(Request $req)
    {
        if ($req->ajax()) {
            $loadDatos = new DatosController();
            $ventaSelect = $loadDatos->getVentaselect($req->idVentas);
            return Response([$ventaSelect]);
        }
    }

    public function descargarPDF($id)
    {
        $pdf = $this->generarPDF($id);
        $loadDatos = new DatosController();
        $ventaSelect = $loadDatos->getVentaselect($id);
        $numeroCerosIzquierda = $this->completarCeros($ventaSelect->Numero);
        $serie = $ventaSelect->Serie;
        return $pdf->download('V-' . $serie . '-' . $numeroCerosIzquierda . '.pdf');
    }

    public function enviarCorreo(Request $req, $id)
    {
        //$mail = new PHPMailer();
        //dd($req->correo);
        $pdf = $this->generarPDF($id);
        file_put_contents($req->comprobante . '.pdf', $pdf->output());
        //dd($pdf);
        $mail = new PHPMailer();
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'mail.sbg.com.pe'; // Specify main and backup SMTP servers
        //$mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'facturacionelectronica@sbg.com.pe'; // SMTP username
        $mail->Debugoutput = 'html';
        $mail->Password = '@SmartGrupo123'; // SMTP password
        $mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465; // TCP port to connect to
        $mail->From = 'facturacionelectronica@sbg.com.pe';
        $mail->FromName = 'SBG - Facturación Electrónica';
        $mail->addAddress($req->correo, 'Comprobante'); // Add a recipient

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8'; // Set email format to HTML
        $mail->Subject = 'Envío de comprobante';
        $mail->AddAttachment($req->comprobante . '.pdf');
        //$mail->addAttachment($pdf->output(),'Factura');
        $mail->msgHTML('Hola: ' . $req->cliente . ', Te estamos enviando adjunto el comprobante (' . $req->comprobante . '.pdf) de la compra que hiciste en BroadCast Perú');
        $enviado = $mail->send();
        if ($enviado) {
            return back()->with('status', 'Se envio correo con éxito');
        } else {
            return back()->with('error', 'No se pudo enviar correo');
        }
    }

    private function generarPDF($id)
    {
        $loadDatos = new DatosController();
        $ventaSelect = $loadDatos->getVentaselect($id);
        $fecha = date_create($ventaSelect->FechaCreacion);
        $dia = date_format($fecha, 'd');
        $mes = date_format($fecha, 'm');
        $año = date_format($fecha, 'Y');
        $numeroCerosIzquierda = $this->completarCeros($ventaSelect->Numero);
        $items = $loadDatos->getItemsVentas($id);
        $array = ['items' => $items, 'numeroCeroIzq' => $numeroCerosIzquierda, 'ventaSelect' => $ventaSelect,
            'dia' => $dia, 'mes' => $mes, 'año' => $año];
        view()->share($array);
        $pdf = PDF::loadView('ventasPDF');
        return $pdf;
    }

    private function completarCeros($numero)
    {
        $numeroConCeros = str_pad($numero, 8, "0", STR_PAD_LEFT);
        return $numeroConCeros;
    }
}
