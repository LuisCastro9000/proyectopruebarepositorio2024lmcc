<?php

namespace App\Http\Controllers\Reportes\Facturacion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use App\Http\Controllers\Servicios\config;
use Carbon\Carbon;
use DateTime;
use DB;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\Document;
use Greenter\Model\Summary\Summary;
use Greenter\Model\Summary\SummaryDetail;
use Greenter\Ws\Services\SunatEndpoints;
use Illuminate\Http\Request;
use Session;

class EmisionResumenDiarioController extends Controller
{
    // req->fechaBoletaPendientes es una varible que viene desde el boton IR AL MODULO DE REENVIO de la vista Area Facturacion( opcion Selector BOLETAS PENDIENTES)
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {

            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $idSucursal = $idSucursal = Session::get('idSucursal');
            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $fechaHoyInicio = Carbon::today();
            $fecha = date("d/m/Y");

            // Esta sesion se crea en el area de Facturacion en la opcion de Boletas Pendientes
            $fechaBoletasPendienteOpcional = $req->fechaBoletaPendientes;
            if ($fechaBoletasPendienteOpcional != null) {
                $fecha = $fechaBoletasPendienteOpcional;
                $fechaHoyInicio = Carbon::createFromFormat('d/m/Y', $fechaBoletasPendienteOpcional)->startOfDay();
            }

            $boletasSoles = $loadDatos->getResumenDiarioBoletas($idSucursal, 1, 0, $fechaHoyInicio);
            $boletasDolares = $loadDatos->getResumenDiarioBoletas($idSucursal, 2, 0, $fechaHoyInicio);

            $notasSoles = $loadDatos->getResumenDiarioNotas($idSucursal, 1, 0, $fechaHoyInicio);
            $notasDolares = $loadDatos->getResumenDiarioNotas($idSucursal, 2, 0, $fechaHoyInicio);

            $bajasSoles = $loadDatos->getResumenDiarioBajas($idSucursal, 1, $fechaHoyInicio);
            $bajasDolares = $loadDatos->getResumenDiarioBajas($idSucursal, 2, $fechaHoyInicio);

            //$resumenDiario = $loadDatos->getResumenDiario($idSucursal, $fechaHoyInicio);
            $resumenDiario = $this->getResumenDiarioPendiente($idSucursal, $fechaHoyInicio);
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $array = ['boletasSoles' => $boletasSoles, 'boletasDolares' => $boletasDolares, 'notasSoles' => $notasSoles, 'notasDolares' => $notasDolares, 'bajasSoles' => $bajasSoles, 'bajasDolares' => $bajasDolares, 'permisos' => $permisos, 'fecha' => $fecha, 'resumenDiario' => $resumenDiario, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
            return view('reportes/facturacion/emisionResumenDiario', $array);
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
        $fecha = $req->fecha;
        if ($fecha == null) {
            return back()->with('error', 'Completar fecha para filtrar');
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $fechaIni = DateTime::createFromFormat('d/m/Y', $fecha);
        $fechaConvertidaInicio = $fechaIni->format("Y-m-d");
        $fechaFin = strtotime('+1 day', strtotime($fechaConvertidaInicio));
        $fechaConvertidaFinal = date('Y-m-d', $fechaFin);

        $boletasSoles = $loadDatos->getResumenDiarioBoletasFiltrado($idSucursal, 1, 0, $fechaConvertidaInicio, $fechaConvertidaFinal);
        $boletasDolares = $loadDatos->getResumenDiarioBoletasFiltrado($idSucursal, 2, 0, $fechaConvertidaInicio, $fechaConvertidaFinal);
        $notasSoles = $loadDatos->getResumenDiarioNotasFiltrado($idSucursal, 1, 0, $fechaConvertidaInicio, $fechaConvertidaFinal);
        $notasDolares = $loadDatos->getResumenDiarioNotasFiltrado($idSucursal, 2, 0, $fechaConvertidaInicio, $fechaConvertidaFinal);
        $bajasSoles = $loadDatos->getResumenDiarioBajasFiltrado($idSucursal, 1, $fechaConvertidaInicio, $fechaConvertidaFinal);
        $bajasDolares = $loadDatos->getResumenDiarioBajasFiltrado($idSucursal, 2, $fechaConvertidaInicio, $fechaConvertidaFinal);

        $resumenDiario = $this->getResumenDiarioPendiente($idSucursal, $fechaConvertidaInicio);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['boletasSoles' => $boletasSoles, 'boletasDolares' => $boletasDolares, 'notasSoles' => $notasSoles, 'notasDolares' => $notasDolares, 'bajasSoles' => $bajasSoles, 'bajasDolares' => $bajasDolares, 'permisos' => $permisos, 'fecha' => $fecha, 'resumenDiario' => $resumenDiario, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('reportes/facturacion/emisionResumenDiario', $array);
    }

    public function getResumenDiarioPendiente($idSucursal, $fecha)
    {
        $resumen = DB::table('resumen_diario')
            ->where('IdSucursal', $idSucursal)
            ->where('FechaEmitida', $fecha)
            ->where('Estado', 'Resumen Pendiente')
            ->get();
        return $resumen;
    }

    public function mostrarDocumentos(Request $req)
    {
        if ($req->ajax()) {
            $cadena = '';
            $cad_sucursal = array();
            $tipoDoc = $req->tipoDocumento;
            $fecha = $req->fecha;
            $idTipoMoneda = $req->idTipoMoneda;
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');

            $fechaIni = DateTime::createFromFormat('d/m/Y', $fecha);
            $fechaConvertidaInicio = $fechaIni->format("Y-m-d");
            $fechaFin = strtotime('+1 day', strtotime($fechaConvertidaInicio));
            $fechaConvertidaFinal = date('Y-m-d', $fechaFin);

            if ($tipoDoc == 1) {
                $documentos = $loadDatos->getResumenDiarioBoletasFiltrado($idSucursal, $idTipoMoneda, 0, $fechaConvertidaInicio, $fechaConvertidaFinal);
            } else if ($tipoDoc == 2) {
                $documentos = $loadDatos->getResumenDiarioNotasFiltrado($idSucursal, $idTipoMoneda, 0, $fechaConvertidaInicio, $fechaConvertidaFinal);
            } else if ($tipoDoc == 3) {
                $documentos = $loadDatos->getResumenDiarioBajasFiltrado($idSucursal, $idTipoMoneda, $fechaConvertidaInicio, $fechaConvertidaFinal);
            }

            return Response()->json(['datos' => $documentos]);
        }
    }

    public function enviarDocumentos(Request $req, $fechaBoletaPendiente = null)
    {
        $loadDatos = new DatosController();
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $fecha = $req->newFecha;
        $tipo = $req->tipo;
        $idTipoMoneda = $req->tipoMoneda;
        //dd($req);
        if ($tipo == 1) {
            $pregunta = $this->boletas($req, $idTipoMoneda, $fecha);
            //$documentos = $loadDatos->getResumenDiarioBoletasFiltrado($idSucursal, $fechaConvertidaInicio, $fechaConvertidaFinal);
        } else if ($tipo == 2) {
            $pregunta = $this->notas($req, $idTipoMoneda, $fecha);
            //$documentos = $loadDatos->getResumenDiarioNotasFiltrado($idSucursal, $fechaConvertidaInicio, $fechaConvertidaFinal);
        } else if ($tipo == 3) {
            $pregunta = $this->bajas($req, $idTipoMoneda, $fecha);
            //$documentos = $loadDatos->getResumenDiarioBajasFiltrado($idSucursal, $fechaConvertidaInicio, $fechaConvertidaFinal);
        }
        //dd('datos');
        return redirect('reportes/facturacion/emitir-resumen-diario');
    }

    public function boletas($req, $idTipoMoneda, $fecha)
    {
        $loadDatos = new DatosController();
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');

        $fechaIni = DateTime::createFromFormat('d/m/Y', $fecha);
        $fechaConvertidaInicio = $fechaIni->format("Y-m-d");
        $fechaFin = strtotime('+1 day', strtotime($fechaConvertidaInicio));
        $fechaConvertidaFinal = date('Y-m-d', $fechaFin);
        $resumenDiario = $loadDatos->getResumenDiarioBoletasFiltrado($idSucursal, $idTipoMoneda, 0, $fechaConvertidaInicio, $fechaConvertidaFinal);
        $ultimoResumen = $loadDatos->getUltimoResumenDiario($idSucursal);
        //dd($resumenDiario);
        if (count($resumenDiario) == 0) {
            return redirect('/reportes/facturacion/emitir-resumen-diario')->with('error', 'No hay documentos electrónicos por enviar');
        } else {
            //dd($resumenDiario);

            $opcionFactura = DB::table('usuario')
                ->select('OpcionFactura')
                ->where('IdUsuario', $idUsuario)
                ->first();

            $config = new config();
            if ($opcionFactura->OpcionFactura > 0) {
                if ($opcionFactura->OpcionFactura == 1) { //sunat {
                    $see = $config->configuracion(SunatEndpoints::FE_BETA);
                } else if ($opcionFactura->OpcionFactura == 2) { //ose {
                    $see = $config->configuracion('https://demo-ose.nubefact.com/ol-ti-itcpe/billService?wsdl');
                } else {
                    return Response(['error', 'No  tiene Elejida la  ruta de  envio.... comuniquese con  el administrador']);
                }
            } else {
                return Response(['error', 'No  tiene Elejida  el tipo de  envio.... comuniquese con  el administrador']);
            }

            /* $config = new config();
            $see = $config->configuracion(SunatEndpoints::FE_BETA); */

            $array = [];
            $baja = [];
            $resumen = [];
            for ($i = 0; $i < count($resumenDiario); $i++) {
                $detail = new SummaryDetail();
                if ($resumenDiario[$i]->Comprobante == 'Boleta') {
                    //$igv = floatval((18/100) + 1);
                    $total = floatval($resumenDiario[$i]->Total + $resumenDiario[$i]->Amortizacion);
                    if ($resumenDiario[$i]->TipoVenta == 1) {
                        $opGravadas = $resumenDiario[$i]->Subtotal;
                        $opExoneradas = '0.00';
                    } else {
                        $opExoneradas = $resumenDiario[$i]->Subtotal;
                        $opGravadas = '0.00';
                    }
                    if (floatval($resumenDiario[$i]->Gratuita) > 0) {
                        $totalGratuita = floatval($resumenDiario[$i]->Gratuita);
                        if ($resumenDiario[$i]->TipoVenta == 1) {
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
                    $detail->setTipoDoc('03')
                        ->setSerieNro($resumenDiario[$i]->Serie . '-' . $resumenDiario[$i]->Numero)
                        ->setEstado(1)
                        ->setClienteTipo($resumenDiario[$i]->CodigoDoc)
                        ->setClienteNro($resumenDiario[$i]->NroDoc)
                        ->setTotal($total)
                        ->setMtoOperGravadas(floatval($opGravadas))
                        ->setMtoOperExoneradas(floatval($opExoneradas))
                        ->setMtoOperGratuitas(floatval($subTotalGratuita))
                        ->setMtoOtrosCargos(floatval($resumenDiario[$i]->Descuento))
                        ->setMtoIGV($resumenDiario[$i]->IGV);
                    array_push($array, $detail);
                }
            }
            $sucursal = $loadDatos->getSucursalSelect($idSucursal);
            $idUsuario = Session::get('idUsuario');
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

            $correlativo = '';
            if (count($ultimoResumen) == 0) {
                $correlativo = '00001';
            } else {
                $correlativo = $this->completarCeros(intval($ultimoResumen[0]->IdResumenDiario) + 1);
            }

            $sum = new Summary();
            if ($idTipoMoneda == 1) {
                $sum->setFecGeneracion($fechaIni)
                    ->setFecResumen(new DateTime())
                    ->setCorrelativo($correlativo)
                    ->setCompany($company)
                    ->setDetails($array);
            } else {
                $sum->setFecGeneracion($fechaIni)
                    ->setFecResumen(new DateTime())
                    ->setCorrelativo($correlativo)
                    ->setMoneda('USD')
                    ->setCompany($company)
                    ->setDetails($array);
            }

            $res = $see->send($sum);

            if (!$res->isSuccess()) {
                //$config->writeXml($sum, $see->getFactory()->getLastXml(), $empresa->Ruc, 4);
                if ($res->getError()->getCode() == 'HTTP' || $res->getError()->getCode() == 'HTTPS') {
                    $resumen = 'Servicio inestable, intentelo en otro momento';
                } else {
                    $resumen = 'Error ' . $res->getError()->getCode() . ': ' . $res->getError()->getMessage();
                }
                return redirect('/reportes/facturacion/resumen-diario')->with('error', $resumen);

            } else {
                $now = Carbon::now();
                $anio = $now->year;
                $mes = $now->month;
                $_mes = $loadDatos->getMes($mes);
                $nombreArchivo = $sum->getName();
                $rutaXml = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/ResumenDiario/' . $nombreArchivo . '.xml';
                $config->writeXml($sum, $see->getFactory()->getLastXml(), $empresa->Ruc, $anio, $_mes, 4);
                $ticket = $res->getTicket();
                sleep(2);
                $res = $see->getStatus($ticket);
                if ($res->getCdrResponse() == null) {

                    /*for($j=0; $j<count($resumenDiario); $j++)
                    {
                    DB::table('ventas')
                    ->whereBetween('ventas.FechaCreacion', [$fechaConvertidaInicio, $fechaConvertidaFinal])
                    ->where('IdVentas', $resumenDiario[$j]->IdVentas) //esto se agrego 21-01-2020
                    ->update(['Estado' => 'Aceptado']);
                    }*/

                    $array = ['IdUsuario' => $idUsuario, 'IdSucursal' => $idSucursal, 'IdTipoMoneda' => $idTipoMoneda, 'TipoResumen' => 1, 'FechaEmitida' => $fechaConvertidaInicio, 'FechaEnviada' => Carbon::now(), 'Numero' => '', 'Ticket' => $ticket, 'RutaXml' => $rutaXml, 'Estado' => 'Resumen Pendiente'];
                    DB::table('resumen_diario')->insert($array);

                    $resumen = DB::table('resumen_diario')
                        ->orderBy('IdResumenDiario', 'desc')
                        ->first();

                    for ($j = 0; $j < count($resumenDiario); $j++) {
                        DB::table('ventas')
                            ->whereBetween('ventas.FechaCreacion', [$fechaConvertidaInicio, $fechaConvertidaFinal])
                            ->where('IdVentas', $resumenDiario[$j]->IdVentas)
                            ->update(['IdResumenDiario' => $resumen->IdResumenDiario]);
                    }

                    return redirect('/reportes/facturacion/resumen-diario')->with('error', 'No se pudo obtener CDR de Resumen Diario');
                } else {

                    $bandBoleta = 0;
                    $bandExceccion = 0;

                    $rutaCdr = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/ResumenDiario/R-' . $nombreArchivo . '.zip';
                    $cdr = $res->getCdrResponse();
                    $config->writeCdr($sum, $res->getCdrZip(), $empresa->Ruc, $anio, $_mes, 4);
                    $config->showResponse($sum, $cdr);

                    $isAccetedCDR = $res->getCdrResponse()->isAccepted();
                    $descripcionCDR = $res->getCdrResponse()->getDescription();
                    $codeCDR = $res->getCdrResponse()->getCode();

                    if (intval($codeCDR) == 0) {
                        $codigoAceptado = $codeCDR;
                        $estado = 'Aceptado';
                        $mensaje = $descripcionCDR;
                        $tipoMensaje = 'Aceptado'; //Resumen Aceptado
                    } else if (intval($codeCDR) >= 100 && intval($codeCDR) <= 1999) {
                        $bandExceccion = 1;
                        $codigoAceptado = $codeCDR;
                        $estado = 'Pendiente';
                        $mensaje = $descripcionCDR;
                        $tipoMensaje = 'Resumen Pendiente'; // Resumen Pendiente
                    } else if (intval($codeCDR) >= 2000 && intval($codeCDR) <= 3999) {
                        $bandBoleta = 1;
                        $codigoAceptado = $codeCDR;
                        $estado = 'Pendiente';
                        $mensaje = $descripcionCDR;
                        $tipoMensaje = 'Resumen Rechazo'; // Resumen Rechazo
                    } else {
                        $codigoAceptado = $codeCDR;
                        $estado = 'Observada';
                        $mensaje = $descripcionCDR;
                        $tipoMensaje = 'Aceptado';
                    }

                    $array = ['IdUsuario' => $idUsuario, 'IdSucursal' => $idSucursal, 'IdTipoMoneda' => $idTipoMoneda, 'TipoResumen' => 1, 'FechaEmitida' => $fechaConvertidaInicio, 'FechaEnviada' => Carbon::now(), 'Numero' => $cdr->getId(), 'Ticket' => $ticket, 'CodResSunat' => $codeCDR, 'RutaXml' => $rutaXml, 'RutaCdr' => $rutaCdr, 'Estado' => $tipoMensaje];
                    DB::table('resumen_diario')->insert($array);

                    if ($bandExceccion == 0) {
                        if ($bandBoleta == 1) {
                            $estado = "Resumen Pendiente";
                        }

                        $resumen = DB::table('resumen_diario')
                            ->orderBy('IdResumenDiario', 'desc')
                            ->first();

                        for ($j = 0; $j < count($resumenDiario); $j++) {
                            DB::table('ventas')
                                ->whereBetween('ventas.FechaCreacion', [$fechaConvertidaInicio, $fechaConvertidaFinal])
                                ->where('IdVentas', $resumenDiario[$j]->IdVentas) //esto se agrego 21-01-2020
                                ->update(['IdResumenDiario' => $resumen->IdResumenDiario, 'Estado' => $estado]);
                        }

                        return redirect('/reportes/facturacion/resumen-diario')->with('status', 'Se envio Resumen Diario Correctamente');
                    } else {
                        return redirect('/reportes/facturacion/resumen-diario')->with('error', 'La respuesta de Sunat es: ' . $mensaje . ' , vuelva a intentar en un momento');
                    }
                }
            }
        }
    }

    public function notas($req, $idTipoMoneda, $fecha)
    {
        $loadDatos = new DatosController();
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');

        $opcionFactura = DB::table('usuario')
            ->select('OpcionFactura')
            ->where('IdUsuario', $idUsuario)
            ->first();
        $config = new config();
        if ($opcionFactura->OpcionFactura > 0) {
            if ($opcionFactura->OpcionFactura == 1) { //sunat {
                $see = $config->configuracion(SunatEndpoints::FE_BETA);
            } else if ($opcionFactura->OpcionFactura == 2) { //ose {
                $see = $config->configuracion('https://demo-ose.nubefact.com/ol-ti-itcpe/billService?wsdl');
            } else {
                return Response(['error', 'No  tiene Elejida la  ruta de  envio.... comuniquese con  el administrador']);
            }
        } else {
            return Response(['error', 'No  tiene Elejida  el tipo de  envio.... comuniquese con  el administrador']);
        }

        /* $config = new config();
        $see = $config->configuracion(SunatEndpoints::FE_BETA); */

        $array = [];
        $fechaIni = DateTime::createFromFormat('d/m/Y', $fecha);
        $fechaConvertidaInicio = $fechaIni->format("Y-m-d");
        $fechaFin = strtotime('+1 day', strtotime($fechaConvertidaInicio));
        $fechaConvertidaFinal = date('Y-m-d', $fechaFin);

        $resumenDiario = $loadDatos->getResumenDiarioNotasFiltrado($idSucursal, $idTipoMoneda, 0, $fechaConvertidaInicio, $fechaConvertidaFinal);
        $ultimoResumen = $loadDatos->getUltimoResumenDiario($idSucursal);

        for ($i = 0; $i < count($resumenDiario); $i++) {
            $detail = new SummaryDetail();
            if ($resumenDiario[$i]->Comprobante == 'Nota Crédito') {
                if ($resumenDiario[$i]->TipoVenta == 1) {
                    $opGravadas = $resumenDiario[$i]->Subtotal;
                    $opExoneradas = '0.00';
                } else {
                    $opExoneradas = $resumenDiario[$i]->Subtotal;
                    $opGravadas = '0.00';
                }

                $detail->setTipoDoc('07')
                    ->setSerieNro($resumenDiario[$i]->Serie . '-' . $resumenDiario[$i]->Numero)
                    ->setDocReferencia((new Document())
                            ->setTipoDoc('03')
                            ->setNroDoc($resumenDiario[$i]->DocModificado))
                    ->setEstado(1)
                    ->setClienteTipo($resumenDiario[$i]->CodigoDoc)
                    ->setClienteNro($resumenDiario[$i]->NroDoc)
                    ->setTotal($resumenDiario[$i]->Total)
                    ->setMtoOperGravadas(floatval($opGravadas))
                    ->setMtoOperExoneradas(floatval($opExoneradas))
                    ->setMtoOtrosCargos(floatval($resumenDiario[$i]->Descuento))
                    ->setMtoIGV(floatval($resumenDiario[$i]->IGV));
                array_push($array, $detail);
                /*$detail->setTipoDoc('07')
            ->setSerieNro($resumenDiario[$i]->Serie.'-'.$resumenDiario[$i]->Numero)
            ->setEstado(2)
            ->setClienteTipo($resumenDiario[$i]->CodigoDoc)
            ->setClienteNro($resumenDiario[$i]->NroDoc)
            ->setTotal($resumenDiario[$i]->Total)
            ->setMtoOperGravadas($opGravadas)
            ->setMtoOperExoneradas($opExoneradas)
            ->setMtoOtrosCargos(floatval($resumenDiario[$i]->Descuento))
            ->setMtoIGV($resumenDiario[$i]->IGV);
            array_push($array, $detail);*/
            }
            if ($resumenDiario[$i]->Comprobante == 'Nota Débito') {
                $detail->setTipoDoc('08')
                    ->setSerieNro($resumenDiario[$i]->Serie . '-' . $resumenDiario[$i]->Numero)
                    ->setDocReferencia((new Document())
                            ->setTipoDoc('03')
                            ->setNroDoc($resumenDiario[$i]->DocModificado))
                    ->setEstado('2')
                    ->setClienteTipo($resumenDiario[$i]->CodigoDoc)
                    ->setClienteNro($resumenDiario[$i]->NroDoc)
                    ->setTotal($resumenDiario[$i]->Total)
                    ->setMtoOperGravadas($resumenDiario[$i]->Subtotal)
                    ->setMtoOtrosCargos(floatval($resumenDiario[$i]->Descuento))
                    ->setMtoIGV($resumenDiario[$i]->IGV);
                array_push($array, $detail);
            }
        }

        $sucursal = $loadDatos->getSucursalSelect($idSucursal);
        $idUsuario = Session::get('idUsuario');
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
        //->setDireccion($sucursal->Direccion);

        $company = new Company();
        $company->setRuc($empresa->Ruc)
            ->setRazonSocial($empresa->Nombre)
            ->setNombreComercial('NONE')
            ->setAddress($address);

        $correlativo = '';
        if (count($ultimoResumen) == 0) {
            $correlativo = '00001';
        } else {
            $correlativo = $this->completarCeros(intval($ultimoResumen[0]->IdResumenDiario) + 1);
        }

        $sum = new Summary();
        if ($idTipoMoneda == 1) {
            $sum->setFecGeneracion($fechaIni)
                ->setFecResumen(new DateTime())
                ->setCorrelativo($correlativo)
                ->setCompany($company)
                ->setDetails($array);
        } else {
            $sum->setFecGeneracion($fechaIni)
                ->setFecResumen(new DateTime())
                ->setCorrelativo($correlativo)
                ->setMoneda('USD')
                ->setCompany($company)
                ->setDetails($array);
        }

        $res = $see->send($sum);
        //dd($res);
        if (!$res->isSuccess()) {
            if ($res->getError()->getCode() == 'HTTP' || $res->getError()->getCode() == 'HTTPS') {
                $resumen = 'Servicio inestable, intentelo en otro momento';
            } else {
                $resumen = 'Error ' . $res->getError()->getCode() . ': ' . $res->getError()->getMessage();
            }
            return redirect('/reportes/facturacion/resumen-diario')->with('error', $resumen);

        } else {
            $now = Carbon::now();
            $anio = $now->year;
            $mes = $now->month;
            $_mes = $loadDatos->getMes($mes);
            $nombreArchivo = $sum->getName();
            $rutaXml = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/ResumenDiario/' . $nombreArchivo . '.xml';
            $config->writeXml($sum, $see->getFactory()->getLastXml(), $empresa->Ruc, $anio, $_mes, 4);
            $ticket = $res->getTicket();
            sleep(2);
            $res = $see->getStatus($ticket);
            if ($res->getCdrResponse() == null) {

                /*for($j=0; $j<count($resumenDiario); $j++)
                {
                DB::table('ventas')
                ->where('IdVentas', $resumenDiario[$j]->IdVentas) //esto se agrego 21-01-2020
                ->update(['Estado' => 'Aceptado Nota']);
                }*/

                /*DB::table('nota_credito_debito')
                ->where('IdSucursal',$idSucursal)
                ->where('IdDocModificado',1)   //esto se agrego 21-01-2020
                ->whereBetween('FechaCreacion', [$fechaConvertidaInicio, $fechaConvertidaFinal])
                ->update(['Estado' => 'Aceptado Ticket', 'TicketResumen'=>$ticket,]);*/

                $array = ['IdUsuario' => $idUsuario, 'IdSucursal' => $idSucursal, 'IdTipoMoneda' => $idTipoMoneda, 'TipoResumen' => 2, 'FechaEmitida' => $fechaConvertidaInicio, 'FechaEnviada' => Carbon::now(), 'Numero' => '', 'Ticket' => $ticket, 'RutaXml' => $rutaXml, 'Estado' => 'Resumen Pendiente'];
                DB::table('resumen_diario')->insert($array);

                $resumen = DB::table('resumen_diario')
                    ->orderBy('IdResumenDiario', 'desc')
                    ->first();

                for ($j = 0; $j < count($resumenDiario); $j++) {
                    DB::table('nota_credito_debito')
                        ->whereBetween('FechaCreacion', [$fechaConvertidaInicio, $fechaConvertidaFinal])
                        ->where('IdCreditoDebito', $resumenDiario[$j]->IdCreditoDebito)
                        ->update(['IdResumenDiario' => $resumen->IdResumenDiario, 'TicketResumen' => $ticket]);
                }

                return redirect('/reportes/facturacion/resumen-diario')->with('error', 'No se pudo obtener CDR de Resumen Diario');
            } else {

                $bandBoleta = 0;
                $bandExceccion = 0;

                $rutaCdr = '/RespuestaSunatProd/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/ResumenDiario/R-' . $nombreArchivo . '.zip';
                $cdr = $res->getCdrResponse();
                $config->writeCdr($sum, $res->getCdrZip(), $empresa->Ruc, $anio, $_mes, 4);
                $config->showResponse($sum, $cdr);

                $isAccetedCDR = $res->getCdrResponse()->isAccepted();
                $descripcionCDR = $res->getCdrResponse()->getDescription();
                $codeCDR = $res->getCdrResponse()->getCode();

                if (intval($codeCDR) == 0) {
                    $codigoAceptado = $codeCDR;
                    $estado = 'Aceptado';
                    $mensaje = $descripcionCDR;
                    $tipoMensaje = 'Aceptado';
                } else if (intval($codeCDR) >= 100 && intval($codeCDR) <= 1999) {
                    $bandExceccion = 1;
                    $bandBoleta = 1;
                    $codigoAceptado = $codeCDR;
                    $estado = 'Resumen Pendiente';
                    $mensaje = $descripcionCDR;
                    $tipoMensaje = 'Pendiente';
                } else if (intval($codeCDR) >= 2000 && intval($codeCDR) <= 3999) {
                    $bandBoleta = 1;
                    $codigoAceptado = $codeCDR;
                    $estado = 'Resumen Pendiente';
                    $mensaje = $descripcionCDR;
                    $tipoMensaje = 'Pendiente';
                } else {
                    $codigoAceptado = $codeCDR;
                    $estado = 'Aceptado';
                    $mensaje = $descripcionCDR;
                    $tipoMensaje = 'Aceptado';
                }

                if ($bandExceccion == 0) {
                    if ($bandBoleta == 0) {
                        for ($j = 0; $j < count($resumenDiario); $j++) {
                            /*DB::table('ventas')
                            ->where('IdVentas',$resumenDiario[$j]->IdVentas)
                            ->update(['Estado' => $tipoMensaje]);*/

                            if ($resumenDiario[$j]->IdTipoNota == 1) {
                                $stock = DB::table('ventas_articulo')
                                    ->where('IdVentas', $resumenDiario[$j]->IdVentas)
                                    ->get();

                                /*if(count($stock) >=1)
                            {
                            for($k=0; $k<count($stock); $k++)
                            {
                            $articulo=DB::table('articulo')
                            ->where('IdArticulo', $stock[$k]->IdArticulo)
                            ->first();
                            if($articulo->IdTipo == 1){
                            $cantidaSum = $articulo->Stock + ($stock[$k]->Cantidad * $stock[$k]->CantidadReal);

                            DB::table('articulo')
                            ->where('IdArticulo', $stock[$k]->IdArticulo)
                            ->update(['Stock' => $cantidaSum]);

                            $_stock = $loadDatos->getUltimoStock($stock[$k]->IdArticulo);

                            $reponer = intval($_stock->Cantidad,10) + (intval($stock[$k]->Cantidad, 10) * intval($stock[$k]->CantidadReal,10));

                            DB::table('stock')
                            ->where('IdStock', $_stock->IdStock)
                            ->update(['Cantidad'=>$reponer]);

                            $kardex=array(
                            'CodigoInterno'=>$articulo->CodigoInterno,
                            'fecha_movimiento'=>Carbon::now(),
                            'tipo_movimiento'=>7,  //nota credito
                            'usuario_movimiento'=>$idUsuario,
                            'documento_movimiento'=>$cdr->getId(),
                            'existencia'=>$cantidaSum,
                            'costo'=>1,
                            'IdArticulo'=>$stock[$k]->IdArticulo,
                            'IdSucursal'=>$idSucursal,
                            'estado'=>1);
                            DB::table('kardex')->insert($kardex);
                            }
                            }
                            }*/
                            }
                        }

                        /*DB::table('nota_credito_debito')
                        ->where('IdDocModificado',1)   //esto se agrego 21-01-2020
                        ->whereBetween('FechaCreacion', [$fechaConvertidaInicio, $fechaConvertidaFinal])
                        ->update(['Estado' => $tipoMensaje]);*/

                        $array = ['IdUsuario' => $idUsuario, 'IdSucursal' => $idSucursal, 'IdTipoMoneda' => $idTipoMoneda, 'TipoResumen' => 2, 'FechaEmitida' => $fechaConvertidaInicio, 'FechaEnviada' => Carbon::now(), 'Numero' => $cdr->getId(), 'Ticket' => $ticket, 'CodResSunat' => $codeCDR, 'RutaXml' => $rutaXml, 'RutaCdr' => $rutaCdr, 'Estado' => $estado];
                        DB::table('resumen_diario')->insert($array);

                        $resumen = DB::table('resumen_diario')
                            ->orderBy('IdResumenDiario', 'desc')
                            ->first();

                        for ($j = 0; $j < count($resumenDiario); $j++) {
                            DB::table('nota_credito_debito')
                                ->whereBetween('FechaCreacion', [$fechaConvertidaInicio, $fechaConvertidaFinal])
                                ->where('IdCreditoDebito', $resumenDiario[$j]->IdCreditoDebito)
                                ->update(['IdResumenDiario' => $resumen->IdResumenDiario, 'TicketResumen' => $ticket, 'Estado' => 'Aceptado']);
                        }
                        return redirect('/reportes/facturacion/resumen-diario')->with('status', 'Se envio Resumen Diario Correctamente');
                    } else {
                        /*DB::table('nota_credito_debito')
                        ->where('IdDocModificado',1)   //esto se agrego 21-01-2020
                        ->whereBetween('FechaCreacion', [$fechaConvertidaInicio, $fechaConvertidaFinal])
                        ->update(['Estado' => $tipoMensaje]);*/

                        $array = ['IdUsuario' => $idUsuario, 'IdSucursal' => $idSucursal, 'IdTipoMoneda' => $idTipoMoneda, 'TipoResumen' => 2, 'FechaEmitida' => $fechaConvertidaInicio, 'FechaEnviada' => Carbon::now(), 'Numero' => $cdr->getId(), 'Ticket' => $ticket, 'CodResSunat' => $codeCDR, 'RutaXml' => $rutaXml, 'RutaCdr' => $rutaCdr, 'Estado' => $estado];
                        DB::table('resumen_diario')->insert($array);

                        $resumen = DB::table('resumen_diario')
                            ->orderBy('IdResumenDiario', 'desc')
                            ->first();

                        for ($j = 0; $j < count($resumenDiario); $j++) {
                            DB::table('nota_credito_debito')
                                ->whereBetween('FechaCreacion', [$fechaConvertidaInicio, $fechaConvertidaFinal])
                                ->where('IdCreditoDebito', $resumenDiario[$j]->IdCreditoDebito)
                                ->update(['IdResumenDiario' => $resumen->IdResumenDiario, 'TicketResumen' => $ticket, 'Estado' => 'Pendiente']);
                        }
                        return redirect('/reportes/facturacion/resumen-diario')->with('error', 'La respuesta de Sunat es: ' . $mensaje . ' , vuelva a intentar en un momento');
                    }
                } else {
                    return redirect('/reportes/facturacion/resumen-diario')->with('error', 'La respuesta de Sunat es: ' . $mensaje . ' , vuelva a intentar en un momento');
                }
            }
        }
    }

    public function bajas($req, $idTipoMoneda, $fecha)
    {
        $loadDatos = new DatosController();
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');

        $fechaIni = DateTime::createFromFormat('d/m/Y', $fecha);
        $fechaConvertidaInicio = $fechaIni->format("Y-m-d");
        $fechaFin = strtotime('+1 day', strtotime($fechaConvertidaInicio));
        $fechaConvertidaFinal = date('Y-m-d', $fechaFin);
        $resumenDiario = $loadDatos->getResumenDiarioBajasFiltrado($idSucursal, $idTipoMoneda, $fechaConvertidaInicio, $fechaConvertidaFinal);
        $ultimoResumen = $loadDatos->getUltimoResumenDiario($idSucursal);

        if (count($resumenDiario) == 0) {
            return redirect('/reportes/facturacion/emitir-resumen-diario')->with('error', 'No hay documentos electrónicos por enviar');
        } else {

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

            /* $config = new config();
            $see = $config->configuracion(SunatEndpoints::FE_BETA); */
            // $see = $config->configuracion(SunatEndpoints::FE_BETA);

            $array = [];
            $baja = [];

            for ($i = 0; $i < count($resumenDiario); $i++) {
                $detail = new SummaryDetail();
                if ($resumenDiario[$i]->Comprobante == 'Boleta') {
                    //$igv = floatval((18/100) + 1);
                    $total = floatval($resumenDiario[$i]->Total + $resumenDiario[$i]->Amortizacion);
                    if ($resumenDiario[$i]->Estado == "Baja Pendiente") {
                        $estado = 3;
                        array_push($baja, $resumenDiario[$i]->IdVentas);
                    }
                    if ($resumenDiario[$i]->TipoVenta == 1) {
                        //$opGravadas = $total / $igv;
                        $opGravadas = $resumenDiario[$i]->Subtotal;
                        $opExoneradas = '0.00';
                    } else {
                        //$opExoneradas = $total;
                        $opExoneradas = $resumenDiario[$i]->Subtotal;
                        $opGravadas = '0.00';
                    }
                    $detail->setTipoDoc('03')
                        ->setSerieNro($resumenDiario[$i]->Serie . '-' . $resumenDiario[$i]->Numero)
                        ->setEstado(3)
                        ->setClienteTipo($resumenDiario[$i]->CodigoDoc)
                        ->setClienteNro($resumenDiario[$i]->NroDoc)
                        ->setTotal($total)
                        ->setMtoOperGravadas($opGravadas)
                        ->setMtoOperExoneradas($opExoneradas)
                        ->setMtoOtrosCargos(floatval($resumenDiario[$i]->Descuento))
                        ->setMtoIGV($resumenDiario[$i]->IGV);
                    array_push($array, $detail);
                }
            }
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
            // ->setDireccion($sucursal->Direccion);

            $company = new Company();
            $company->setRuc($empresa->Ruc)
                ->setRazonSocial($empresa->Nombre)
                ->setNombreComercial('NONE')
                ->setAddress($address);

            $correlativo = '';
            if (count($ultimoResumen) == 0) {
                $correlativo = '00001';
            } else {
                $correlativo = $this->completarCeros(intval($ultimoResumen[0]->IdResumenDiario) + 1);
            }
            //dd($correlativo);
            $sum = new Summary();
            if ($idTipoMoneda == 1) {
                $sum->setFecGeneracion($fechaIni)
                    ->setFecResumen(new DateTime())
                    ->setCorrelativo($correlativo)
                    ->setCompany($company)
                    ->setDetails($array);
            } else {
                $sum->setFecGeneracion($fechaIni)
                    ->setFecResumen(new DateTime())
                    ->setCorrelativo($correlativo)
                    ->setMoneda('USD')
                    ->setCompany($company)
                    ->setDetails($array);
            }

            $res = $see->send($sum);
            if (!$res->isSuccess()) {
                if ($res->getError()->getCode() == 'HTTP') {
                    $resumen = 'Servicio inestable, intentelo en otro momento';
                } else {
                    $resumen = 'Error ' . $res->getError()->getCode() . ': ' . $res->getError()->getMessage();
                }
                return redirect('/reportes/facturacion/resumen-diario')->with('error', $resumen);

            } else {

                $array_baja = [];
                $now = Carbon::now();
                $anio = $now->year;
                $mes = $now->month;
                $_mes = $loadDatos->getMes($mes);
                $nombreArchivo = $sum->getName();
                $rutaXml = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/ResumenDiario/' . $nombreArchivo . '.xml';
                $config->writeXml($sum, $see->getFactory()->getLastXml(), $empresa->Ruc, $anio, $_mes, 4);
                $ticket = $res->getTicket();
                sleep(2);
                $res = $see->getStatus($ticket);
                if ($res->getCdrResponse() == null) {

                    $array = ['IdUsuario' => $idUsuario, 'IdSucursal' => $idSucursal, 'IdTipoMoneda' => $idTipoMoneda, 'TipoResumen' => 3, 'FechaEmitida' => $fechaConvertidaInicio, 'FechaEnviada' => Carbon::now(), 'Numero' => '', 'Ticket' => $ticket, 'RutaXml' => $rutaXml, 'Estado' => 'Resumen Pendiente'];
                    DB::table('resumen_diario')->insert($array);

                    /*for($j=0; $j<count($baja); $j++)
                    {
                    DB::table('ventas')
                    ->whereBetween('ventas.FechaCreacion', [$fechaConvertidaInicio, $fechaConvertidaFinal])
                    ->where('IdVentas', $baja[$j]) //esto se agrego 21-01-2020
                    ->update(['Estado' => 'Aceptado Ticket']);

                    $array = ['IdSucursal'=>$idSucursal, 'IdUsuario'=>$idUsuario, 'Hash' => '', 'FechaEmitida' => $fechaConvertidaInicio, 'FechaEnviada' => Carbon::now(), 'Identificador' => '', 'Ticket' => '', 'CodigoDoc'=>'-', 'TicketResumen'=>$ticket, 'Estado' => 'Aceptado Baja'];
                    DB::table('baja_documentos')->insert($array);

                    $baja_alt = DB::table('baja_documentos')
                    ->orderBy('IdBajaDoc','desc')
                    ->first();

                    array_push($array_baja, $baja_alt->IdBajaDoc);
                    }*/

                    return redirect('/reportes/facturacion/resumen-diario')->with('error', 'No se pudo obtener CDR de Resumen Diario');
                } else {
                    $bandBaja = 0;
                    $bandExceccion = 0;

                    $rutaCdr = '/RespuestaSunatProd/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/ResumenDiario/R-' . $nombreArchivo . '.zip';
                    $cdr = $res->getCdrResponse();
                    $config->writeCdr($sum, $res->getCdrZip(), $empresa->Ruc, $anio, $_mes, 4);
                    $config->showResponse($sum, $cdr);

                    $isAccetedCDR = $res->getCdrResponse()->isAccepted();
                    $descripcionCDR = $res->getCdrResponse()->getDescription();
                    $codeCDR = $res->getCdrResponse()->getCode();

                    if (intval($codeCDR) == 0) {
                        $codigoAceptado = $codeCDR;
                        $estado = 'Aceptado';
                        $mensaje = $descripcionCDR;
                        $tipoMensaje = 'Baja Aceptado';
                    } else if (intval($codeCDR) >= 100 && intval($codeCDR) <= 1999) {
                        $bandExceccion = 1;
                        $bandBaja = 1;
                        $codigoAceptado = $codeCDR;
                        $estado = 'Resumen Pendiente';
                        $mensaje = $descripcionCDR;
                        $tipoMensaje = 'Baja Pendiente';
                    } else if (intval($codeCDR) >= 2000 && intval($codeCDR) <= 3999) {
                        $bandBaja = 1;
                        $codigoAceptado = $codeCDR;
                        $estado = 'Resumen Pendiente';
                        $mensaje = $descripcionCDR;
                        $tipoMensaje = 'Baja Pendiente';
                    } else {
                        $codigoAceptado = $codeCDR;
                        $estado = 'Aceptado';
                        $mensaje = $descripcionCDR;
                        $tipoMensaje = 'Baja Aceptado';
                    }

                    if ($bandExceccion == 0) {
                        if ($bandBaja == 0) {
                            for ($j = 0; $j < count($baja); $j++) {
                                DB::table('ventas')
                                    ->where('IdVentas', $baja[$j])
                                    ->update(['Estado' => $tipoMensaje]);

                                /*$array = ['IdSucursal'=>$idSucursal, 'IdUsuario'=>$idUsuario, 'Hash' => '', 'FechaEmitida' => $fechaConvertidaInicio, 'FechaEnviada' => Carbon::now(), 'Identificador' => '', 'Ticket' => '', 'CodigoDoc'=>'-', 'Estado' => $tipoMensaje];
                                DB::table('baja_documentos')->insert($array);*/

                                /*$baja_alt = DB::table('baja_documentos')
                                ->orderBy('IdBajaDoc','desc')
                                ->first();*/
                                //$idBaja=$baja_alt->IdBajaDoc;

                                $stock = DB::table('ventas_articulo')
                                    ->where('IdVentas', $baja[$j])
                                    ->get();

                                if (count($stock) >= 1) {
                                    for ($k = 0; $k < count($stock); $k++) {

                                        $articulo = DB::table('articulo')
                                            ->where('IdArticulo', $stock[$k]->IdArticulo)
                                            ->first();

                                        /*$arrayRelacion = ['IdBajaDocumento' => $idBaja, 'IdArticulo' => $stock[$k]->IdArticulo, 'Codigo' =>$stock[$k]->Codigo , 'Descripcion' => $articulo->Descripcion, 'PrecioVenta' => $articulo->Precio, 'Cantidad' => ($stock[$k]->Cantidad * $stock[$k]->CantidadReal), 'Descuento' => 0.0, 'Total' => 0.0];
                                        DB::table('baja_detalle')->insert($arrayRelacion);*/

                                        if ($articulo->IdTipo == 1) {
                                            $cantidaSum = $articulo->Stock + ($stock[$k]->Cantidad * $stock[$k]->CantidadReal);
                                            DB::table('articulo')
                                                ->where('IdArticulo', $stock[$k]->IdArticulo)
                                                ->update(['Stock' => $cantidaSum]);

                                            $_stock = $loadDatos->getUltimoStock($stock[$k]->IdArticulo);

                                            $reponer = $_stock->Cantidad + ($stock[$k]->Cantidad * $stock[$k]->CantidadReal);

                                            DB::table('stock')
                                                ->where('IdStock', $_stock->IdStock)
                                                ->update(['Cantidad' => $reponer]);

                                            $kardex = array(
                                                'CodigoInterno' => $articulo->CodigoInterno,
                                                'fecha_movimiento' => Carbon::now(),
                                                'tipo_movimiento' => 6, //doc. baja
                                                'usuario_movimiento' => $idUsuario,
                                                'documento_movimiento' => $cdr->getId(),
                                                'existencia' => $cantidaSum,
                                                'costo' => $articulo->Precio,
                                                'IdArticulo' => $stock[$k]->IdArticulo,
                                                'IdSucursal' => $idSucursal,
                                                'estado' => 1,
                                            );
                                            DB::table('kardex')->insert($kardex);
                                        }
                                    }
                                }
                            }

                            if ($estado == 'Baja Aceptado') {
                                $estado = "Aceptado";
                            }

                            $array = ['IdUsuario' => $idUsuario, 'IdSucursal' => $idSucursal, 'IdTipoMoneda' => $idTipoMoneda, 'TipoResumen' => 3, 'FechaEmitida' => $fechaConvertidaInicio, 'FechaEnviada' => Carbon::now(), 'Numero' => $cdr->getId(), 'Ticket' => $ticket, 'CodResSunat' => $codeCDR, 'RutaXml' => $rutaXml, 'RutaCdr' => $rutaCdr, 'Estado' => $estado];
                            DB::table('resumen_diario')->insert($array);

                            return redirect('/reportes/facturacion/resumen-diario')->with('status', 'Se envio Resumen Diario Correctamente');
                        } else {
                            $array = ['IdUsuario' => $idUsuario, 'IdSucursal' => $idSucursal, 'IdTipoMoneda' => $idTipoMoneda, 'TipoResumen' => 3, 'FechaEmitida' => $fechaConvertidaInicio, 'FechaEnviada' => Carbon::now(), 'Numero' => $cdr->getId(), 'Ticket' => $ticket, 'CodResSunat' => $codeCDR, 'RutaXml' => $rutaXml, 'RutaCdr' => $rutaCdr, 'Estado' => $estado];
                            DB::table('resumen_diario')->insert($array);

                            return redirect('/reportes/facturacion/resumen-diario')->with('error', 'La respuesta de Sunat es: ' . $mensaje . ' , vuelva a intentar en un momento');
                        }
                    } else {
                        return redirect('/reportes/facturacion/resumen-diario')->with('error', 'La respuesta de Sunat es: ' . $mensaje . ' , vuelva a intentar en un momento');
                    }
                }
            }
        }
    }
    public function completarCeros($numero)
    {
        $numeroConCeros = str_pad($numero, 5, "0", STR_PAD_LEFT);
        return $numeroConCeros;
    }
}
