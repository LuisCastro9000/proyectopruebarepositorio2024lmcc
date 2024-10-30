<?php

namespace App\Http\Controllers\Consultas;

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
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\Note;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Voided\Voided;
use Greenter\Model\Voided\VoidedDetail;
use Greenter\Ws\Services\ConsultCdrService;
use Greenter\Ws\Services\SoapClient;
use Greenter\Ws\Services\SunatEndpoints;
use Illuminate\Http\Request;
use PDF;
use PHPMailer\PHPMailer\PHPMailer;
use Session;
use Storage;

class ConsultaNotasCreditoDebitoController extends Controller
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
        $notasAceptadas = $loadDatos->getNotasAll($idSucursal);

        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $date = Carbon::today();
        $dateAtras = $date->subDays(7)->startOfDay()->format("Y-m-d H:i:s");

        $tipoNota = '';
        $fecha = '';
        $fechaIni = '';
        $fechaFin = '';
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        //$filename = 'not-found.png';
        //$path = storage_path('public/' . $filename);
        //dd($path);
        $array = ['notasAceptadas' => $notasAceptadas, 'permisos' => $permisos, 'IdTipoNota' => $tipoNota, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'dateAtras' => $dateAtras, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'rucEmpresa' => $empresa->Ruc];
        return view('consultas/consultaNotasCreditoDebito', $array);
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
        $tipoNota = $req->tipoNota;
        $fecha = $req->fecha;
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        $date = Carbon::today();
        $dateAtras = $date->subDays(3)->startOfDay()->format("Y-m-d H:i:s");
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
        $idUsuario = Session::get('idUsuario');
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $notasAceptadas = $loadDatos->getNotasAllFiltrado($idSucursal, $tipoNota, $fecha, $fechaIni, $fechaFin);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $array = ['notasAceptadas' => $notasAceptadas, 'IdTipoNota' => $tipoNota, 'fecha' => $fecha, 'permisos' => $permisos, 'dateAtras' => $dateAtras, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'rucEmpresa' => $empresa->Ruc];
        return view('consultas/consultaNotasCreditoDebito', $array);
    }

    public function anular(Request $req)
    {
        $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');
        $loadDatos = new DatosController();
        $idNota = $req->idNota;
        $descripcion = $req->descripcion;
        if ($descripcion == null || $descripcion == '') {
            $descripcion = "Error";
        }
        $date = Carbon::now();
        $fechaConvertida = $date->format("Y-m-d H:i:s");
        $notaSelect = $loadDatos->getNotaSelect($idNota);
        if ($notaSelect->IdDocModificado == 2) {
            $res = $this->darBajaDocumento($notaSelect, $idUsuario, $fechaConvertida, $descripcion);

            if (is_numeric($res)) {
                if ($res == 1) {
                    return redirect('/consultas/notas-credito-debito')->with('status', 'Se envio baja documento a Sunat correctamente');
                } else {
                    return redirect('/consultas/notas-credito-debito')->with('error', 'No se pudo obtener CDR de Baja de Documentos');
                }
            } else {
                return redirect('/consultas/notas-credito-debito')->with('error', $res);
            }
        } else {
            DB::table('nota_credito_debito')
                ->where('IdCreditoDebito', $idNota)
                ->where('IdSucursal', $idSucursal)
                ->update(['FechaModificacion' => $fechaConvertida, 'MotivoBaja' => $descripcion, 'Estado' => 'Baja Pendiente']);

            return redirect('/consultas/notas-credito-debito')->with('status', 'Se agrego a la lista de Baja de Documentos con éxito');
        }
    }

    private function darBajaDocumento($notaSelect, $idUsuario, $fechaConvertida, $descripcion)
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
        $idSucursal = $idSucursal = Session::get('idSucursal');
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
        $detail->setTipoDoc('07')
            ->setSerie($notaSelect->Serie)
            ->setCorrelativo($notaSelect->Numero)
            ->setDesMotivoBaja($descripcion);
        //array_push($array, $detail);
        //array_push($denegado, $documentos[$i]->IdDoc);
        //}

        $cantidad = intval($correlativo->Cantidad);
        $voided = new Voided();
        $voided->setCorrelativo($cantidad + 1)
            ->setFecGeneracion(new DateTime($notaSelect->FechaCreacion))
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
                $_array = ['IdSucursal' => $idSucursal, 'IdUsuario' => $idUsuario, 'Hash' => '', 'FechaEmitida' => $notaSelect->FechaCreacion, 'FechaEnviada' => Carbon::now(), 'Identificador' => '', 'Ticket' => '', 'IdVentas' => $notaSelect->IdCreditoDebito, 'TipoDocumento' => 2, 'CodigoDoc' => $res1->getError()->getCode(), 'Estado' => 'Baja Rechazo'];
                DB::table('baja_documentos')->insert($_array);
            }
            DB::table('nota_credito_debito')
                ->where('IdCreditoDebito', $notaSelect->IdCreditoDebito)
                ->update(['FechaModificacion' => $fechaConvertida, 'MotivoBaja' => $descripcion, 'Estado' => 'Baja Pendiente']);
            //return back()->with('error',$res1->getError()->getMessage().'-'.$res1->getError()->getCode());
            return $res1->getError()->getMessage() . '-' . $res1->getError()->getCode();
        } else {
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
                $_array = ['IdSucursal' => $idSucursal, 'IdUsuario' => $idUsuario, 'Hash' => '', 'FechaEmitida' => $notaSelect->FechaCreacion, 'FechaEnviada' => Carbon::now(), 'Identificador' => '', 'Ticket' => $ticket, 'IdVentas' => $notaSelect->IdCreditoDebito, 'TipoDocumento' => 2, 'RutaXml' => $rutaXml, 'Estado' => 'Baja Pendiente'];
                DB::table('baja_documentos')->insert($_array);

                /*for($i=0; $i<count($documentos); $i++){

                DB::table('ventas')
                ->where('IdVentas',$documentos[$i]->IdVentas)
                ->update(['Estado' => 'Baja Pendiente']);
                }*/

                //for($k=0; $k<count($denegado); $k++)
                //{
                $dene = ['IdVentas' => $notaSelect->IdCreditoDebito, 'Ticket' => $ticket];
                DB::table('denegado_baja')->insert($dene);
                //}

                DB::table('nota_credito_debito')
                    ->where('IdCreditoDebito', $notaSelect->IdCreditoDebito)
                    ->update(['FechaModificacion' => $fechaConvertida, 'MotivoBaja' => $descripcion, 'Estado' => 'Baja Pendiente']);

                return 0;
                //return redirect('/reportes/facturacion/baja-documentos')->with('error','No se pudo obtener CDR de Baja de Documentos');
            } else {

                $bandBaja = 0;
                $bandExceccion = 0;

                $cdr = $res2->getCdrResponse();
                $rutaCdr = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/BajaDocumentos/R-' . $nombreArchivo . '.zip';
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
                    $array = ['IdSucursal' => $idSucursal, 'IdUsuario' => $idUsuario, 'Hash' => $hash, 'FechaEmitida' => $notaSelect->FechaCreacion, 'FechaEnviada' => Carbon::now(), 'Identificador' => $cdr->getId(), 'Ticket' => $ticket, 'CodigoDoc' => $codigoAceptado, 'IdVentas' => $notaSelect->IdCreditoDebito, 'TipoDocumento' => 2, 'RutaXml' => $rutaXml, 'RutaCdr' => $rutaCdr, 'Estado' => $estado];
                    DB::table('baja_documentos')->insert($array);

                    $baja = DB::table('baja_documentos')
                        ->orderBy('IdBajaDoc', 'desc')
                        ->first();
                    $idBaja = $baja->IdBajaDoc;
                }

                if ($bandBaja == 0) {
                    DB::table('nota_credito_debito')
                        ->where('IdCreditoDebito', $notaSelect->IdCreditoDebito)
                        ->update(['MotivoBaja' => $descripcion, 'Estado' => $tipoMensaje]);

                    $stock = DB::table('nota_detalle')
                        ->where('IdCreditoDebito', $notaSelect->IdCreditoDebito)
                        ->get();

                    DB::table('ventas')
                        ->where('IdVentas', $notaSelect->IdVentas)
                        ->update(['Nota' => 0, 'TipoNota' => null]);

                    if (count($stock) >= 1) {
                        for ($j = 0; $j < count($stock); $j++) {
                            $articulo = DB::table('articulo')
                                ->where('IdArticulo', $stock[$j]->IdArticulo)
                                ->first();

                            if ($articulo->IdTipo == 1) {
                                $cantidadRes = floatval($articulo->Stock) - floatval($stock[$j]->Cantidad);
                                DB::table('articulo')
                                    ->where('IdArticulo', $stock[$j]->IdArticulo)
                                    ->update(['Stock' => $cantidadRes]);

                                $_stock = $loadDatos->getProductoStockSelect($stock[$j]->IdArticulo);

                                $quitar = floatval($stock[$j]->Cantidad);

                                DB::table('stock')
                                    ->where('IdStock', $_stock[0]->IdStock)
                                    ->decrement('Cantidad', $quitar);

                                $kardex = array(
                                    'CodigoInterno' => $articulo->CodigoInterno,
                                    'fecha_movimiento' => Carbon::now(),
                                    'tipo_movimiento' => 15, //baja de documento
                                    'usuario_movimiento' => $idUsuario,
                                    'documento_movimiento' => $cdr->getId(),
                                    'existencia' => $cantidadRes,
                                    'costo' => 1,
                                    'IdArticulo' => $stock[$j]->IdArticulo,
                                    'IdSucursal' => $idSucursal,
                                    'Cantidad' => $quitar,
                                    'Descuento' => 0,
                                    'ImporteEntrada' => 0,
                                    'ImporteSalida' => 0,
                                    'estado' => 1,
                                );
                                DB::table('kardex')->insert($kardex);

                                $arrayRelacion = ['IdBajaDocumento' => $idBaja, 'IdArticulo' => $stock[$j]->IdArticulo, 'Codigo' => 'PRO-' . $stock[$j]->IdArticulo, 'Descripcion' => $articulo->Descripcion, 'PrecioVenta' => $articulo->Precio, 'Cantidad' => $quitar, 'Descuento' => 0.0, 'Total' => 0.0];
                                DB::table('baja_detalle')->insert($arrayRelacion);
                            }

                        }

                    }
                }
            }

            return 1;
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
        $pdf = $this->generarPDF($req, 1, $id);
        $loadDatos = new DatosController();
        $notaSelect = $loadDatos->getNotaSelect($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $rucEmpresa = $empresa->Ruc;
        $numeroCerosIzquierda = $this->completarCeros($notaSelect->Numero);
        $serie = $notaSelect->Serie;
        if ($notaSelect->IdTipoNota == 1) {
            $idDoc = '07';
        }
        if ($notaSelect->IdTipoNota == 2) {
            $idDoc = '08';
        }
        return $pdf->download($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numeroCerosIzquierda . '.pdf');
    }

    public function descargarXML(request $req, $ruc, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $notaSelect = $loadDatos->getNotaselect($id);
            $serie = $notaSelect->Serie;
            $numero = $notaSelect->Numero;
            $idTipoNota = $notaSelect->IdTipoNota;
            $cod = $serie . '-' . $numero;
            if ($idTipoNota == 1) {
                $file = $ruc . '-07-' . $cod . '.xml';
            }
            if ($idTipoNota == 2) {
                $file = $ruc . '-08-' . $cod . '.xml';
            }

            if (Storage::disk('s3')->exists($notaSelect->RutaXml)) {

                $rutaS3 = Storage::disk('s3')->get($notaSelect->RutaXml);
                $headers = [
                    'Content-Type' => 'text/xml',
                    'Content-Description' => 'File Transfer',
                    'Content-Disposition' => "attachment; filename=" . $file . "",
                    'filename' => '' . $file . '',
                ];

                return response($rutaS3, 200, $headers);
            } else {
                /*$rutaXml = $this->generarXML($ruc, $notaSelect);

                $rutaS3 = Storage::disk('s3')->get($rutaXml);
                $headers = [
                'Content-Type' => 'text/xml',
                'Content-Description' => 'File Transfer',
                'Content-Disposition' => "attachment; filename=".$file."",
                'filename'=> ''.$file.''
                ];

                return response($rutaS3, 200, $headers);*/
                return back()->with('error', 'No se encontró archivo Xml');
            }
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        /*$loadDatos = new DatosController();
    $notaSelect = $loadDatos->getNotaselect($id);
    $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
    $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
    $rucEmpresa = $empresa->Ruc;
    $serie = $notaSelect->Serie;
    $numero= $notaSelect->Numero;
    $idTipoNota = $notaSelect->IdTipoNota;
    $cod = $serie.'-'.$numero;
    if($idTipoNota == 1){
    $file = $rucEmpresa.'-07-'.$cod;
    }
    if($idTipoNota == 2){
    $file = $rucEmpresa.'-08-'.$cod;
    }

    return response()->download(public_path().'/RespuestaSunat/NotasCreditoDebito/'.$rucEmpresa.'/'.$file.'.xml');*/
    }

    public function generarXML($ruc, $nota)
    {
        $config = new config();
        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $fecha = $nota->FechaCreacion;
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $fecha);
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
                return Response(['error', 'No  tiene Elejida la  ruta de  envio.... comuniquese con  el administrador']);
            }
        } else {
            return Response(['error', 'No  tiene Elejida  el tipo de  envio.... comuniquese con  el administrador']);
        }

        $cliente = $loadDatos->getClienteSelect($nota->IdCliente);

        if ($nota->IdDocModificado == 2) {
            $tipoDoc = '01';
        }
        if ($nota->IdDocModificado == 1) {
            $tipoDoc = '03';
        }

        if ($nota->TipoVenta == 1) {
            $opGravada = $nota->Subtotal;
            $opExonerada = '0.00';
        } else {
            $opGravada = '0.00';
            $opExonerada = $nota->Subtotal;
        }

        if ($nota->IdTipoMoneda == 1) {
            $totalDetrac = floatval($nota->Total);
        } else {
            $totalDetrac = floatval($nota->Total);
        }

        $totalGratuita = 0;
        if (floatval($nota->Gratuita) > 0) {
            $totalGratuita = floatval($nota->Gratuita);
            if ($nota->TipoVenta == 1) {
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

        $idMotivo = $nota->IdMotivo;
        $selectMotivo = $loadDatos->getSelectMotivo($idMotivo, 'c');
        $total = floatval($nota->Total) - floatval(0);
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

        $company = new Company();
        $company->setRuc($empresa->Ruc)
            ->setRazonSocial($empresa->Nombre)
            ->setNombreComercial('NONE')
            ->setAddress($address);

        $cliente = $loadDatos->getClienteSelect($nota->IdCliente);

        $client = new Client();
        $client->setTipoDoc(strval($cliente->CodigoSunat)) //agregado
            ->setNumDoc($cliente->NumeroDocumento)
            ->setRznSocial($cliente->Nombre);

        $note = new Note();
        $note->setUblVersion('2.1')
            ->setTipDocAfectado($tipoDoc)
            ->setNumDocfectado($nota->DocModificado)
            ->setCodMotivo($selectMotivo->CodigoSunat)
            ->setDesMotivo($selectMotivo->Descripcion)
            ->setTipoDoc('07')
            ->setSerie($nota->Serie)
            ->setFechaEmision($date)
            ->setCorrelativo($nota->Numero)
            ->setTipoMoneda($nota->CodMoneda)
            ->setCompany($company)
            ->setClient($client)
            ->setMtoOperGravadas(floatval($opGravada))
            ->setMtoOperExoneradas(floatval($opExonerada))
            ->setMtoOperGratuitas(floatval($subTotalGratuita))
            ->setMtoIGVGratuitas(floatval($igvGratuita))
            ->setMtoIGV(floatval($nota->IGV))
            ->setTotalImpuestos(floatval($nota->IGV))
            ->setMtoImpVenta($total);

        $items = $loadDatos->getItemsNotas($nota->IdCreditoDebito);

        $array = [];
        $legends = [];
        $countGratuita = 0;
        $condicionDetrac = 0;
        for ($i = 0; $i < count($items); $i++) {
            $idProducto = substr($items[$i]->Cod, 4);

            if ($items[$i]->IdPaquetePromocional > 0) {
                $productoSelect = DB::table('paquetes_promocionales')
                    ->where('IdPaquetePromocional', $items[$i]->IdPaquetePromocional)
                    ->first();
                $condicionDetrac = 1;
                $medidaSunat = 'ZZ';
                $descripcion = $productoSelect->NombrePaquete;
            } else {
                $productoSelect = $loadDatos->getProductoSelect($items[$i]->IdArticulo);
                if ($productoSelect->IdTipo == 2) {
                    $condicionDetrac = 1;
                }
                $medidaSunat = $productoSelect->MedidaSunat;
                $descripcion = $productoSelect->Descripcion;
            }

            $precioUnitario = floatval($items[$i]->Total / $items[$i]->Cantidad);
            if ($nota->TipoVenta == 1) {
                $subTotalPrecioUnitario = floatval($precioUnitario) / 1.18;
                $afectIgv = '10';
                $porcentaje = 18;
            } else {
                $subTotalPrecioUnitario = floatval($precioUnitario);
                $afectIgv = '20';
                $porcentaje = 0;
            }

            $igvItem = floatval($precioUnitario) - floatval($subTotalPrecioUnitario);
            $mtoValorVenta = floatval(intval($items[$i]->Cantidad) * $subTotalPrecioUnitario); // - floatval($items[$i]->Descuento);
            $igvTotal = floatval(intval($items[$i]->Cantidad) * $igvItem);
            $totalImpuesto = floatval($igvTotal);
            $valorGratuito = 0;
            if ($items[$i]->Gratuito == 1) {
                $valorGratuito = floatval($subTotalPrecioUnitario);
                $subTotalPrecioUnitario = 0;
                $totalImpuesto = 0;
                $countGratuita++;
                if ($nota->TipoVenta == 1) {
                    $afectIgv = '11';
                } else {
                    $afectIgv = '21';
                }
            }

            $detail = new SaleDetail();
            $detail->setCodProducto($items[$i]->Cod)
                ->setUnidad($medidaSunat)
                ->setCantidad($items[$i]->Cantidad)
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
            usleep(100000);
        }

        $convertirLetras = new NumeroALetras();
        if ($nota->IdTipoMoneda == 1) {
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
        $rucEmpresa = $empresa->Ruc;
        $serie = $nota->Serie;
        $numero = $nota->Numero;
        $cod = $serie . '-' . $numero;
        $file = $rucEmpresa . '-07-' . $cod;

        $now = Carbon::now();
        $anio = $now->year;
        $mes = $now->month;
        $_mes = $loadDatos->getMes($mes);
        $nombreArchivo = $file;
        $rutaXml = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $rucEmpresa . '/NotasCreditoDebito/' . $nombreArchivo . '.xml';

        $xml_string = $see->getXmlSigned($note);
        $doc = new DOMDocument();
        $doc->loadXML($xml_string);

        $config->writeXml($note, $see->getFactory()->getLastXml(), $rucEmpresa, $anio, $_mes, 2);

        $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
        $date = new DateTime();
        $fecha = $date->format('Y-m-d');
        $resumen = $rucEmpresa . '|07|' . $serie . '|' . $numero . '|' . round($nota->IGV, 2) . '|' . round($nota->Total, 2) . '|' . $fecha . '|' . $cliente->CodigoSunat . '|' . $cliente->NumeroDocumento;

        DB::table('nota_credito_debito')
            ->where('IdCreditoDebito', $nota->IdCreditoDebito)
            ->update(['Resumen' => $resumen, 'Hash' => $hash, 'RutaXml' => $rutaXml]);

        usleep(1000000);

        return $rutaXml;

    }

    public function descargarCDR(request $req, $ruc, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $loadDatos = new DatosController();
            $idUsuario = Session::get('idUsuario');
            $notaSelect = $loadDatos->getNotaselect($id);
            $serie = $notaSelect->Serie;
            $numero = $notaSelect->Numero;
            $idTipoNota = $notaSelect->IdTipoNota;
            $cod = $serie . '-' . $numero;
            if ($idTipoNota == 1) {
                $file = 'R-' . $ruc . '-07-' . $cod . '.zip';
            } else {
                $file = 'R-' . $ruc . '-08-' . $cod . '.zip';
            }

            if (Storage::disk('s3')->exists($notaSelect->RutaCdr)) {

                $rutaS3 = Storage::disk('s3')->get($notaSelect->RutaCdr);
                $headers = [
                    'Content-Type' => 'text/xml',
                    'Content-Description' => 'File Transfer',
                    'Content-Disposition' => "attachment; filename=" . $file . "",
                    'filename' => '' . $file . '',
                ];

                return response($rutaS3, 200, $headers);
            } else {
                $rutaCDR = $this->generarCDR($notaSelect);
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
        /*$loadDatos = new DatosController();
    $notaSelect = $loadDatos->getNotaselect($id);
    $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
    $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
    $rucEmpresa = $empresa->Ruc;
    $serie = $notaSelect->Serie;
    $numero= $notaSelect->Numero;
    $idTipoNota = $notaSelect->IdTipoNota;
    $cod = $serie.'-'.$numero;
    if($idTipoNota == 1){
    $file = 'R-'.$rucEmpresa.'-07-'.$cod;
    }
    if($idTipoNota == 2){
    $file = 'R-'.$rucEmpresa.'-08-'.$cod;
    }
    return response()->download(public_path().'/RespuestaSunat/NotasCreditoDebito/'.$rucEmpresa.'/'.$file.'.zip');*/
    }

    public function generarCdr($nota)
    {
        $idUsuario = Session::get('idUsuario');
        $loadDatos = new DatosController();
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $service = $this->getCdrStatusService($empresa->Ruc . '' . $empresa->UsuarioSol, $empresa->ClaveSol);

        $arguments = [
            $fields['ruc'] = $empresa->Ruc,
            $fields['tipo'] = '07',
            $fields['serie'] = $nota->Serie,
            intval($fields['numero'] = $nota->Numero),
        ];

        $res = $service->getStatusCdr(...$arguments);
        //dd($res);
        if ($res->getCdrZip()) {
            $name = 'R-' . $empresa->Ruc . '-07-' . $nota->Serie . '-' . $nota->Numero . '.zip';

            $now = Carbon::now();
            $anio = $now->year;
            $mes = $now->month;
            $_mes = $loadDatos->getMes($mes);

            $rutaCdr = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/NotasCreditoDebito/' . $name;
            Storage::disk('s3')->put($rutaCdr, $res->getCdrZip(), 'public');

            DB::table('nota_credito_debito')
                ->where('IdCreditoDebito', $nota->IdCreditoDebito)
                ->update(['RutaCdr' => $rutaCdr]);
            usleep(1000000);
            return $rutaCdr;
        } else {
            return null;
        }
    }

    public function detallesNotaCreditoDebito(Request $req, $id, $tipo)
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
        $notaSelect = $loadDatos->getNotaSelect($id);
        $ventaSelect = $loadDatos->getVentaselect($notaSelect->IdVentas);
        $fechaPago = '';
        if ($ventaSelect->IdTipoPago == 1) {
            if ($tipo == 1) {
                $caja = $loadDatos->getCierreCajaUltimo($idSucursal, $idUsuario);
                $cobranzas = $loadDatos->getDetalleCajaCobranzas($idSucursal, $idUsuario, $caja->FechaApertura, $notaSelect->IdTipoMoneda);
                $ventasContadoTotal = $loadDatos->getDetalleCajaContado($idSucursal, $idUsuario, $caja->FechaApertura, $notaSelect->IdTipoMoneda);
                $ventasContadoEfectivo = $ventasContadoTotal[0]->Efectivo;
                $cobranzasEfectivo = $cobranzas[0]->Efectivo;
                $ingresos = $loadDatos->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, 'I', $notaSelect->IdTipoMoneda);

                if ($ingresos[0]->Monto == null) {
                    $montoIngresos = '0.00';
                } else {
                    $montoIngresos = $ingresos[0]->Monto;
                }
                $egresos = $loadDatos->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, 'E', $notaSelect->IdTipoMoneda);

                if ($egresos[0]->Monto == null) {
                    $montoEgresos = '0.00';
                } else {
                    $montoEgresos = $egresos[0]->Monto;
                }
                if ($notaSelect->IdTipoMoneda == 1) {
                    $inicial = $caja->Inicial;
                } else {
                    $inicial = $caja->InicialDolares;
                }

                $cajaTotal = floatval($cobranzasEfectivo) + floatval($ventasContadoEfectivo) + floatval($inicial) + floatval($montoIngresos) - floatval($montoEgresos);
            } else {
                $cajaTotal = 0.0;
            }
        } else {
            $cajaTotal = 0.0;
        }
        if ($notaSelect->IdMotivo == 23) {
            $fechaPagoConvert = Carbon::parse($ventaSelect->FechaCreacion);
            $fechaPagoDias = $fechaPagoConvert->addDays($ventaSelect->PlazoCredito);
            $fechaPagoDate = new DateTime($fechaPagoDias);
            $fechaPago = date_format($fechaPagoDate, 'd-m-Y');
        }
        $numeroCerosIzquierda = $this->completarCeros($notaSelect->Numero);
        $fecha = date_create($notaSelect->FechaCreacion);
        $formatoFecha = date_format($fecha, 'd-m-Y');
        $formatoHora = date_format($fecha, 'H:i A');
        $items = $loadDatos->getItemsNotas($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $listaBancos = $loadDatos->getCuentasCorrientes($usuarioSelect->CodigoCliente, $notaSelect->IdTipoMoneda)->whereNotIn('IdListaBanco', ['9']);
        $array = ['notaSelect' => $notaSelect, 'ventaSelect' => $ventaSelect, 'fechaPago' => $fechaPago, 'permisos' => $permisos, 'tipo' => $tipo, 'cajaTotal' => $cajaTotal, 'numeroCeroIzq' => $numeroCerosIzquierda, 'formatoFecha' => $formatoFecha, 'formatoHora' => $formatoHora, 'items' => $items, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'listaBancos' => $listaBancos];
        return view('consultas/detallesNotasCreditoDebito', $array);
    }

    public function getDatosCuenta(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $loadDatos = new DatosController();
            $datosCuenta = $loadDatos->getCuentaCorrienteSelect($req->idBanco);
            return response()->json(['respuesta' => 'success', 'datosCuenta' => $datosCuenta]);

        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function descontarMonto(Request $req)
    {

        // $loadDatos = new DatosController();
        // $idSucursal = Session::get('idSucursal');
        // $idUsuario = Session::get('idUsuario');
        // $totalDescontar = $req->totalDescontar;
        // $totalCaja = $req->totalCaja;
        // $idNotaCredito = $req->idNotaCredito;
        // $notaSelect = $loadDatos->getNotaSelect($idNotaCredito);
        // $descEgreso = "Descuento por NC " . $notaSelect->Serie . " - " . $notaSelect->Numero;
        // $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
        // if (floatval($totalDescontar) > floatval($totalCaja)) {
        //     return redirect('/consultas/notas-credito-debito/detalles/' . $idNotaCredito . '/2')->with('error', 'No hay suficiente dinero en caja');
        // } else {
        //     $date = new DateTime();
        //     $fecha = $date->format("Y-m-d H:i:s");
        //     $tipo = 'E';
        //     $array = ['IdCaja' => $caja->IdCaja, 'Fecha' => $fecha, 'Tipo' => $tipo, 'IdTipoMoneda' => $notaSelect->IdTipoMoneda, 'Monto' => $totalDescontar, 'Descripcion' => $descEgreso];
        //     DB::table('ingresoegreso')->insert($array);

        //     return redirect('/consultas/notas-credito-debito/detalles/' . $idNotaCredito . '/2')->with('status', 'Se desconto monto de la caja con éxito');
        // }
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');
        $totalDescontar = $req->totalDescontar;
        $totalCaja = $req->totalCaja;
        $idNotaCredito = $req->idNotaCredito;
        $notaSelect = $loadDatos->getNotaSelect($idNotaCredito);
        $descEgreso = "Descuento por NC " . $notaSelect->Serie . " - " . $notaSelect->Numero;
        $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
        $date = new DateTime();
        $fecha = $date->format("Y-m-d H:i:s");

        if ($req->has('switchDescontarCaja')) {
            $mensaje = 'Se desconto monto de la caja con éxito';
            if (floatval($totalDescontar) > floatval($totalCaja)) {
                return redirect('/consultas/notas-credito-debito/detalles/' . $idNotaCredito . '/2')->with('error', 'No hay suficiente dinero en caja');
            }
            $tipo = 'E';
            $array = ['IdCaja' => $caja->IdCaja, 'Fecha' => $fecha, 'Tipo' => $tipo, 'IdTipoMoneda' => $notaSelect->IdTipoMoneda, 'Monto' => $totalDescontar, 'Descripcion' => $descEgreso];
            DB::table('ingresoegreso')->insert($array);
        }

        if ($req->has('switchDescontarCuenta')) {
            $mensaje = 'Se desconto monto de la cuenta con éxito';
            $cuenta = $loadDatos->getCuentaCorrienteSelect($req->selectBanco);

            if (floatval($totalDescontar) > floatval($cuenta->MontoActual)) {
                return redirect('/consultas/notas-credito-debito/detalles/' . $idNotaCredito . '/2')->with('error', 'No hay suficiente dinero en la cuenta');
            }
            $montoActual = floatval($cuenta->MontoActual) - floatval($totalDescontar);
            $numeroOperacion = $req->numeroOperacion ? $req->numeroOperacion : 0;

            $array = ['FechaPago' => $fecha, 'IdBanco' => $req->selectBanco, 'NumeroOperacion' => $numeroOperacion, 'Detalle' => $descEgreso, 'TipoMovimiento' => "Nota Crédito", 'Entrada' => 0, 'Salida' => $totalDescontar, 'MontoActual' => $montoActual, 'IdSucursal' => $idSucursal];
            DB::table('banco_detalles')->insert($array);

            DB::table('banco')
                ->where('IdBanco', $req->selectBanco)
                ->update(["MontoActual" => $montoActual]);
        }
        return redirect('/consultas/notas-credito-debito/detalles/' . $idNotaCredito . '/2')->with('status', $mensaje);
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
        $notaSelect = $loadDatos->getNotaSelect($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $nombreEmpresa = $empresa->Nombre;
        $rucEmpresa = $empresa->Ruc;
        $numero = $notaSelect->Numero;
        $serie = $notaSelect->Serie;
        $cod = $serie . '-' . $numero;
        if ($notaSelect->IdTipoNota == 1) {
            $idDoc = '07';
            $file = $rucEmpresa . '-07-' . $cod;
        }
        if ($notaSelect->IdTipoNota == 2) {
            $idDoc = '08';
            $file = $rucEmpresa . '-08-' . $cod;
        }
        $pdf = $this->generarPDF($req, 1, $id);
        file_put_contents($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numero . '.pdf', $pdf->output());
        //dd($pdf);
        $mail = new PHPMailer();
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'mail.easyfactperu.pe'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'facturacion@easyfactperu.pe'; // SMTP username
        $mail->Debugoutput = 'html';
        $mail->Password = 'gV.S=o=Q,bl2'; // SMTP password
        $mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465; // TCP port to connect to
        $mail->From = 'facturacion@easyfactperu.pe';
        $mail->FromName = 'EASYFACT PERÚ S.A.C  - Notas Crédito / Débito';
        $mail->addAddress($req->correo, 'Comprobante'); // Add a recipient

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8'; // Set email format to HTML
        $mail->Subject = 'Envío de comprobante';
        $mail->addAttachment($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numero . '.pdf');
        //$mail->addAttachment(public_path().'/RespuestaSunat/NotasCreditoDebito/'.$file.'.xml');
        //$mail->msgHTML('Hola: '.$req->cliente.', Te estamos enviando adjunto el comprobante ('.$req->comprobante.'.pdf) de la compra que hiciste en BroadCast Perú');
        if ($notaSelect->IdTipoNota == 1) {
            if (Storage::disk('s3')->exists($notaSelect->RutaXml)) {
                $rutaXmlS3 = Storage::disk('s3')->get($notaSelect->RutaXml);
                file_put_contents($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numero . '.xml', $rutaXmlS3);
                $mail->addAttachment($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numero . '.xml');
            }

            if (Storage::disk('s3')->exists($notaSelect->RutaCdr)) {
                $rutaCdrS3 = Storage::disk('s3')->get($notaSelect->RutaCdr);
                file_put_contents($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numero . '.zip', $rutaCdrS3);
                $mail->addAttachment($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numero . '.zip');
            }
            $tipo = 'NOTA DE CRÉDITO';
        }
        if ($notaSelect->IdTipoNota == 2) {
            $tipo = 'NOTA DE DÉBITO';
        }
        //$numeroCerosIzquierda = $this->completarCeros($ventaSelect->Numero);
        $fecha = date_create($notaSelect->FechaCreacion);
        $formatoFecha = date_format($fecha, 'd/m/Y');
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
            . '<p>Por encargo del emisor <span style="font-weight: bold;">' . $nombreEmpresa . '/span>, nos es grato informar que el documento electrónico ya se encuentra disponible con los siguientes datos:</p>'
            . '</div>'
            . '<hr style="border: 0.5px solid #000;">'
            . '<div style="margin-bottom:20px;margin-left:30px">'
            . '<p><span style="font-weight: bold;">Tipo: ' . $tipo . '</span></p>'
            . '<p><span style="font-weight: bold;">Número: ' . $notaSelect->Serie . '-' . $numero . '</span></p>'
            . '<p><span style="font-weight: bold;">RUC / DNI: ' . $rucEmpresa . '</span></p>'
            . '<p><span style="font-weight: bold;">Fecha Emisión: ' . $formatoFecha . '</span></p>'
            . '<p><span style="font-weight: bold;">Monto Total: ' . $notaSelect->Total . '</span></p>'
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
        $notaSelect = $loadDatos->getNotaSelect($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $rucEmpresa = $empresa->Ruc;
        $numero = $notaSelect->Numero;
        $serie = $notaSelect->Serie;
        if ($notaSelect->IdTipoNota == 1) {
            $idDoc = '07';
        }
        if ($notaSelect->IdTipoNota == 2) {
            $idDoc = '08';
        }
        return $pdf->stream($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numero . '.pdf');
    }

    private function generarPDF($req, $tipo, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $notaSelect = $loadDatos->getNotaSelect($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $fecha = date_create($notaSelect->FechaCreacion);
        $formatoFecha = date_format($fecha, 'd/m/Y');
        $formatoHora = date_format($fecha, 'H:i A');
        $convertirLetras = new NumeroALetras();
        $fechaPago = '';
        if ($notaSelect->IdMotivo == 23) {
            $ventaSelect = $loadDatos->getVentaselect($notaSelect->IdVentas);
            $fechaPagoConvert = Carbon::parse($ventaSelect->FechaCreacion);
            $fechaPagoDias = $fechaPagoConvert->addDays($ventaSelect->PlazoCredito);
            $fechaPagoDate = new DateTime($fechaPagoDias);
            $fechaPago = date_format($fechaPagoDate, 'd-m-Y');
        }
        if ($notaSelect->IdTipoMoneda == 1) {
            $importeLetras = $convertirLetras->convertir($notaSelect->Total, 'soles');
        } else {
            $importeLetras = $convertirLetras->convertir($notaSelect->Total, 'dolares');
        }
        //dd($fechaPago);
        $numeroCerosIzquierda = $this->completarCeros($notaSelect->Numero);
        $resumen = $notaSelect->Resumen;
        $hash = $notaSelect->Hash;
        $items = $loadDatos->getItemsNotas($id);
        $array = ['items' => $items, 'numeroCeroIzq' => $numeroCerosIzquierda, 'notaSelect' => $notaSelect, 'resumen' => $resumen, 'hash' => $hash,
            'fechaPago' => $fechaPago, 'formatoFecha' => $formatoFecha, 'formatoHora' => $formatoHora, 'importeLetras' => $importeLetras, 'empresa' => $empresa];
        view()->share($array);
        if ($tipo == 1) {
            $pdf = PDF::loadView('notaPDF')->setPaper('a4', 'portrait');
        }
        if ($tipo == 2) {
            $pdf = PDF::loadView('notaPDFA5')->setPaper('a5', 'portrait');
        }

        return $pdf;
    }

    private function getCdrStatusService($user, $password)
    {
        $ws = new SoapClient(SunatEndpoints::FE_CONSULTA_CDR . '?wsdl');

        $ws->setCredentials($user, $password);

        $service = new ConsultCdrService();
        $service->setClient($ws);

        return $service;
    }

    private function completarCeros($numero)
    {
        $numeroConCeros = str_pad($numero, 8, "0", STR_PAD_LEFT);
        return $numeroConCeros;
    }
}
