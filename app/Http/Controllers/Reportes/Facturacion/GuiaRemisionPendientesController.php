<?php

namespace App\Http\Controllers\Reportes\Facturacion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use App\Http\Controllers\Servicios\config;
use Carbon\Carbon;
use DateTime;
use DB;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Despatch\Despatch;
use Greenter\Model\Despatch\DespatchDetail;
use Greenter\Model\Despatch\Direction;
use Greenter\Model\Despatch\Shipment;
use Greenter\Model\Despatch\Transportist;
use Greenter\Ws\Services\ConsultCdrService;
use Greenter\Ws\Services\SoapClient;
use Greenter\Ws\Services\SunatEndpoints;
use Illuminate\Http\Request;
use Session;
use Storage;

class GuiaRemisionPendientesController extends Controller
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
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $hoy = Carbon::today();
        $fechaAntes = $hoy->subDays(90);
        $fecha = '';
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $guiasRemision = $loadDatos->getGuiaRemisionPendientes($idSucursal, $fechaAntes);
        $array = ['permisos' => $permisos, 'fecha' => $fecha, 'guiasRemision' => $guiasRemision, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'usuarioSelect' => $usuarioSelect];
        return view('reportes/facturacion/guiaRemisionPendientes', $array);
    }

    // NUEVA FUNCION VER GUIAS PENDIENTES
    public function verGuiasRemisionPendientes(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $permisos = $loadDatos->getPermisos($idUsuario);
            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);
            $guiasRemision = $this->guiasRemisionPendientes();
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $array = ['permisos' => $permisos, 'guiasRemision' => $guiasRemision, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
            return view('reportes/facturacion/verGuiasRemisionPendientesAdmin', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    private function guiasRemisionPendientes()
    {
        try {
            $guiasPendientes = DB::select('(select guia_remision.IdGuiaRemision as IdGuiaRemision, guia_remision.FechaCreacion, usuario.Nombre as Usuario, sucursal.Nombre as Sucursal, cliente.NumeroDocumento as NroDoc, codigoError, guia_remision.Serie, guia_remision.Numero, guia_remision.Estado
							          from guia_remision
                                      inner join cliente on guia_remision.IdCliente = cliente.IdCliente
                                      inner join usuario on guia_remision.IdUsuario = usuario.IdUsuario
                                      inner join sucursal on guia_remision.IdSucursal = sucursal.IdSucursal
									  inner join tipo_documento on tipo_documento.IdTipoDocumento = cliente.IdTipoDocumento
									  where usuario.Estado = "E" and guia_remision.FechaCreacion > "2023-01-12" or guia_remision.FechaCreacion > "2023-01-12" and (codigoError like "%env%" or codigoError like "%CDR%"))');
            return $guiasPendientes;

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function updateEstadoGuiasRemisionPendiente(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $estado = $req->estado;
        $codigo = $req->codigo;
        if (!empty($codigo)) {
            for ($i = 0; $i < count($codigo); $i++) {
                $datos = [
                    'Estado' => $estado[$i],
                ];
                DB::table('guia_remision')
                    ->where('IdGuiaRemision', $codigo[$i])
                    ->update($datos);
            }
        }
        return redirect('/reportes/facturacion/ver-guias-remision-pendientes')->with('succes', ' El cambio de estado fue satisfactorio..');
    }
    // FIN

    public function enviarSunat(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
            $guiaSelect = $loadDatos->getGuiaRemisionSelect($req->idDocEnvio);
            $rucEmpresa = $empresa->Ruc;
            $serie = $guiaSelect->Serie;
            $numero = $guiaSelect->Numero;
            $cod = $serie . '-' . $numero;
            $file = $rucEmpresa . '-09-' . $cod;

            //$resultZip = Storage::disk('s3')->get($guiaSelect->RutaXml);
            //dd($resultZip);
            //$fileXml = file_get_contents($file.'.xml');

            /*$fileXml = Storage::disk('s3')->get($guiaSelect->RutaXml);

            $zipFile = new ZipFile();
            $zipFile->addFromString($file.'.xml', $fileXml, ZipCompressionMethod::DEFLATED);
            $zip = $zipFile->outputAsString();
            $zipFile->close();

            $carpetaUsuariosRetirados = 'RespuestaSunat/UsuariosRetirados/'.$file.'.zip';
            Storage::disk('s3')->put($carpetaUsuariosRetirados, $zip, 'public');
            dd($zip);*/

            $config = new config();

            $rutaCdr = null;

            $access_token = $config->getTokenGRE($empresa);

            if (!empty($access_token["access_token"])) {
                $resCdr = $config->consultaCDR($guiaSelect->NumTicket, $access_token["access_token"]);
                //dd($resCdr);
                $resEstado = 0;
                $mensaje = null;
                $codRespuesta = 0;
                if ($resCdr["codRespuesta"] == 0) {
                    $resEstado = 1;
                    $codRespuesta = $resCdr["codRespuesta"];
                    $mensaje = "Se envio GRE a Sunat correctamente";
                    $now = Carbon::now();
                    $anio = $now->year;
                    $mes = $now->month;
                    $_mes = $loadDatos->getMes($mes);

                    $cdrGRE = base64_decode($resCdr["arcCdr"]);

                    $ruta = 'RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/GuiasRemision/R-' . $file . '.zip';

                    $rutaCdr = '/' . $ruta;

                    Storage::disk('s3')->put($ruta, $cdrGRE, 'public');

                } else {
                    if ($resCdr["codRespuesta"] == 99) {
                        $resEstado = 1;
                        $resultadoCdr = $resCdr["error"];
                        $codRespuesta = $resultadoCdr["numError"];
                        $mensaje = 'La Respuesta de Sunat es: ' . $codRespuesta . ' - ' . $resultadoCdr["desError"];
                        if ($resCdr["indCdrGenerado"] == 1) {
                            $now = Carbon::now();
                            $anio = $now->year;
                            $mes = $now->month;
                            $_mes = $loadDatos->getMes($mes);

                            $ruta = 'RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/GuiasRemision/R-' . $file . '.zip';

                            $rutaCdr = '/' . $ruta;

                            $cdrGRE = base64_decode($resCdr["arcCdr"]);

                            Storage::disk('s3')->put($ruta, $cdrGRE, 'public');
                        }

                    } else {
                        $codRespuesta = $resCdr["codRespuesta"];
                        $mensaje = $resCdr["codRespuesta"] . ' - La GRE se encuentra en Proceso';

                    }
                }

                if (intval($codRespuesta) == 0) {
                    $estado = 'Aceptado';
                    $status = 'status';
                } else if (intval($codRespuesta) >= 100 && intval($codRespuesta) <= 1999) {
                    $estado = 'Pendiente';
                    $status = 'error';
                } else if (intval($codRespuesta) >= 2000 && intval($codRespuesta) <= 3999) {
                    $estado = 'Rechazo';
                    $status = 'error';
                } else if (intval($codRespuesta) >= 4000) {
                    $estado = 'Observado';
                    $status = 'status';
                } else {
                    $estado = 'Pendiente';
                    $status = 'error';
                }

                DB::table('guia_remision')
                    ->where('IdGuiaRemision', $req->idDocEnvio)
                    ->update(['codigoError' => $codRespuesta, 'RutaCdr' => $rutaCdr, 'Estado' => $estado]);

                return redirect()->to('/reportes/facturacion/guias-remision-pendientes')->with($status, $mensaje);

            } else {
                return redirect()->to('/reportes/facturacion/guias-remision-pendientes')->with('error', 'La respuesta de Sunat: ' . $access_token["cod"] . ' - ' . $access_token["msg"]);
            }

            //$resultZip = file_get_contents($file.'.zip');
            /*$arcGreZip = base64_encode($resultZip);
            $hashZip = hash('sha256', $resultZip);

            $postData = array('archivo' => array('nomArchivo' => $file.'.zip', 'arcGreZip' => $arcGreZip, 'hashZip' => $hashZip));
            $encodedData = json_encode($postData);*/
            /*$postData = [
            'archivo' => [
            'nomArchivo' => $resultZip,
            'arcGreZip' => $arcGreZip,
            'hashZip' => $hashZip
            ]
            ];*/

            //$access_token = 'test-eyJhbGciOiJIUzUxMiJ9.ImRiNmYwZjFkLWM4NGYtNDBkMi1hNThlLWIyYTA0ZWI3ZGUyZCI.BqIGfJz_fAj8qX08EHJ-96JVvKSSJOBXnrx_Msex6bj53GXpUJHOvddv6_7Fs3un5Xmr0WrcCgBS2imWRfoqDg';
            //$access_token = 'test-eyJhbGciOiJIUzUxMiJ9.ImUxNWQzNDNkLTAzMzctNGNmMy1iZmMzLWViOTFkMDYzYzYwNyI.qY96Ra6XtoEHxzdOJumNcJvyg93ZxnPZyPDnYCznUdMeSApC3NasPmrJeCiJYQqzwHZUR4M-FhgsM7KwQ60FQA';
            /*$curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://gre-test.nubefact.com/v1/contribuyente/gem/comprobantes/'.$file);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token, 'Content-Type: application/json'));
            curl_setopt($curl, CURLOPT_POSTFIELDS, $encodedData);

            $response = curl_exec($curl);
            curl_close($curl);
            dd(json_decode($response));*/

            /*$numTicket = 'test-c8b2fe92-8364-4add-b9bc-e5eb6e2f4f25';

            $curl2 = curl_init();
            curl_setopt($curl2, CURLOPT_URL, 'https://gre-test.nubefact.com/v1/contribuyente/gem/comprobantes/envios/'.$numTicket);
            curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl2, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));

            $response2 = curl_exec($curl2);
            curl_close($curl2);

            $arrayResponse2 = json_decode($response2, true);

            $cdrGRE = base64_decode($arrayResponse2["arcCdr"]);*/

            /*$now = Carbon::now();
            $anio = $now->year;
            $mes = $now->month;
            $_mes = $loadDatos->getMes($mes);

            $rutaCdr = '/RespuestaSunat/'.$anio.'/'.$_mes.'/'.$rucEmpresa.'/GuiasRemision/R-'.$file.'.zip';

            Storage::disk('s3')->put($rutaCdr, $cdrGRE, 'public');

            DB::table('guia_remision')
            ->where('IdGuiaRemision',$req->idDocEnvio)
            ->update(['RutaCdr'=>$rutaCdr, 'Estado' => 'Aceptado']);*/

            //dd($cdrGRE);

            /*$now = Carbon::now();
            $anio = $now->year;
            $mes = $now->month;
            $_mes = $loadDatos->getMes($mes);
            $nombreArchivo = $file;
            $rutaXml = '/RespuestaSunat/'.$anio.'/'.$_mes.'/'.$rucEmpresa.'/GuiasRemision/'.$nombreArchivo.'.xml';
            if(Storage::disk('s3')->exists($guiaSelect->RutaXml)){
            $ruta = Storage::disk('s3')->get($guiaSelect->RutaXml);
            file_put_contents($nombreArchivo.'.xml', $ruta);
            $result = file_get_contents($nombreArchivo.'.xml');
            dd($result);
            //$result = $see->sendXml(get_class($invoice), $invoice->getName(), file_get_contents($nombreArchivo.'.xml'));
            if(unlink($nombreArchivo.'.xml')) {
            }
            }else{
            dd("no encontrado");
            }*/

            /*$usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
            $guiaSelect = $loadDatos->getGuiaRemisionSelect($req->idDocEnvio);
            $rucEmpresa = $empresa->Ruc;
            $serie = $guiaSelect->Serie;
            $numero= $guiaSelect->Numero;
            $cod = $serie.'-'.$numero;
            $file = $rucEmpresa.'-09-'.$cod.'.xml';

            if(Storage::disk('s3')->exists($guiaSelect->RutaXml)){

            $rutaS3 = Storage::disk('s3')->get($guiaSelect->RutaXml);
            $headers = [
            'Content-Type' => 'text/xml',
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => "attachment; filename=".$file."",
            'filename'=> ''.$file.''
            ];

            return response($rutaS3, 200, $headers);
            }else{
            return back()->with('error','No se encontró archivo Xml');
            }*/

            /*$respuesta = $this->guiasRemisiones($req);
        if(is_numeric($respuesta)){
        return redirect()->to('/reportes/facturacion/guias-remision-pendientes')->with('status','Se envio Factura Electrónica a Sunat');
        }
        else
        {
        return redirect('/reportes/facturacion/guias-remision-pendientes')->with('error',$respuesta);
        }*/

        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function guiasRemisiones(Request $req)
    {
        $idUsuario = Session::get('idUsuario');
        $idDoc = $req->idDocEnvio;
        $loadDatos = new DatosController();
        $guiaRemisionSelect = $loadDatos->getGuiaRemisionSelect($idDoc);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $rucEmpresa = $empresa->Ruc;
        $fecha = $guiaRemisionSelect->FechaCreacion;
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

        $service = $this->getCdrStatusService($empresa->Ruc . '' . $empresa->UsuarioSol, $empresa->ClaveSol);
        $arguments = [
            $fields['ruc'] = $empresa->Ruc,
            $fields['tipo'] = '09',
            $fields['serie'] = $guiaRemisionSelect->Serie,
            intval($fields['numero'] = $guiaRemisionSelect->Numero),
        ];
        $res = $service->getStatusCdr(...$arguments);
/*$service = $this->getCdrStatusService('20604616515MS3KUMPA', 'MulKumpa125');
$arguments = [
$fields['ruc']='20604616515',
$fields['tipo']='09',
$fields['serie']='T202',
intval($fields['numero']='00000092')
];
$res = $service->getStatusCdr(...$arguments);*/

        if ($res->getCdrZip()) {

            $cdr = $res->getCdrResponse();
            $resumen = 1;
            $banderaRechazo = 0;
            $isAccetedCDR = $res->getCdrResponse()->isAccepted();
            $descripcionCDR = $res->getCdrResponse()->getDescription();
            $codeCDR = $res->getCdrResponse()->getCode();
            if (intval($codeCDR) == 0) {
                $codigoAceptado = $codeCDR;
                $estado = 'Aceptado';
                $mensaje = $descripcionCDR;
                $resumen = 1;

            } else if (intval($codeCDR) >= 100 && intval($codeCDR) <= 1999) {
                $banderaRechazo = 1;
                $codigoAceptado = $codeCDR;
                $estado = 'Pendiente';
                $mensaje = $descripcionCDR;
                $resumen = $descripcionCDR;
            } else if (intval($codeCDR) >= 2000 && intval($codeCDR) <= 3999) {

                $codigoAceptado = $codeCDR;
                $estado = 'Rechazo';
                $mensaje = $descripcionCDR;
                $resumen = $descripcionCDR;
            } else if (intval($codeCDR) >= 4000) {
                $codigoAceptado = $codeCDR;
                $estado = 'Observado';
                $mensaje = $descripcionCDR;
                $resumen = 1;
            } else {
                $banderaRechazo = 1;
                $codigoAceptado = $codeCDR;
                $estado = 'Pendiente';
                $resumen = "Error en el Sistema, vuelva a intentar";
            }

            if ($banderaRechazo == 0) {
                DB::table('guia_remision')
                    ->where('IdGuiaRemision', $idDoc)
                    ->update(['CodigoError' => $codigoAceptado, 'Estado' => $estado]);

                $name = 'R-' . $empresa->Ruc . '-09-' . $guiaRemisionSelect->Serie . '-' . $guiaRemisionSelect->Numero . '.zip';

                file_put_contents(public_path() . '/RespuestaSunat/FacturasBoletas/' . $empresa->Ruc . '/' . $name, $res->getCdrZip());

                DB::table('ventas')
                    ->where('IdVentas', $guiaRemisionSelect->IdVentas)
                    ->update(['Guia' => 1]);

            } else {
                DB::table('guia_remision')
                    ->where('IdGuiaRemision', $idDoc)
                    ->update(['CodigoError' => $codigoAceptado]);
            }

            return $resumen;

        } else {

            $cliente = $loadDatos->getClienteSelect($guiaRemisionSelect->IdCliente);
            $idSucursal = Session::get('idSucursal');
            $sucursal = $loadDatos->getSucursalSelect($idSucursal);

            //$codSunatComprobante = $loadDatos->getSelectTipoDocumento($req->tipDocTransp);
            $selectMotivo = $loadDatos->getSelectMotivo($guiaRemisionSelect->IdMotivo, 'g');
            //dd($selectMotivo);
            $items = $loadDatos->getItemsVentas($guiaRemisionSelect->IdVentas);

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

            $transp = new Transportist();
            $transp->setTipoDoc($guiaRemisionSelect->tipoDocEmpresa)
                ->setNumDoc($guiaRemisionSelect->RucTransp)
                ->setRznSocial($guiaRemisionSelect->RazonSocialTransp)
                ->setPlaca($guiaRemisionSelect->PlacaVehicular)
                ->setChoferTipoDoc($guiaRemisionSelect->IdTipoDocumento)
                ->setChoferDoc($guiaRemisionSelect->NumeroDocumento);

            $envio = new Shipment();
            $envio->setModTraslado($guiaRemisionSelect->ModoTraslado)
                ->setCodTraslado($selectMotivo->CodigoSunat)
                ->setDesTraslado($selectMotivo->Descripcion)
                ->setFecTraslado(new DateTime($guiaRemisionSelect->FechaEmision))
            //->setCodPuerto('123')
            //->setIndTransbordo(false)
                ->setPesoTotal($guiaRemisionSelect->Peso)
                ->setUndPesoTotal('KGM')
                ->setNumBultos($guiaRemisionSelect->Bultos)
            //->setNumContenedor('XD-2232')
                ->setLlegada(new Direction($guiaRemisionSelect->DistritoDestino, $guiaRemisionSelect->Destino))
                ->setPartida(new Direction($guiaRemisionSelect->DistritoOrigen, $guiaRemisionSelect->Origen))
                ->setTransportista($transp);

            $despatch = new Despatch();
            $despatch->setTipoDoc('09')
                ->setSerie($guiaRemisionSelect->Serie)
                ->setCorrelativo($guiaRemisionSelect->Numero)
                ->setFechaEmision(new DateTime($guiaRemisionSelect->FechaTraslado))
                ->setCompany($company)
                ->setDestinatario((new Client())
                        ->setTipoDoc($cliente->CodigoSunat)
                        ->setNumDoc($cliente->NumeroDocumento)
                        ->setRznSocial($cliente->Nombre))
                ->setObservacion($guiaRemisionSelect->Observacion)
                ->setEnvio($envio);

            $array = [];
            for ($i = 0; $i < count($items); $i++) {
                $detail = new DespatchDetail();
                $detail->setCantidad($items[$i]->Cantidad)
                    ->setUnidad($items[$i]->CodSunatMedida)
                    ->setDescripcion($items[$i]->Descripcion)
                    ->setCodigo($items[$i]->Cod);
                array_push($array, $detail);
            }
            $despatch->setDetails($array);
            //dd("espera");
            $xml_string = $see->getXmlSigned($despatch);
            $config->writeXml($despatch, $see->getFactory()->getLastXml(), $empresa->Ruc, 3);

            $res = $see->send($despatch);

            if ($res->isSuccess()) {
                $cdr = $res->getCdrResponse();
                $config->writeCdr($despatch, $res->getCdrZip(), $empresa->Ruc, 3);
                $config->showResponse($despatch, $cdr);

                DB::table('guia_remision')
                    ->where('IdVentas', $idDoc)
                    ->update(['Estado' => 'Aceptado']);

                DB::table('ventas')
                    ->where('IdVentas', $guiaRemisionSelect->IdVentas)
                    ->update(['Guia' => 1]);

                return 1;

            } else {

                if ($res->getError()->getCode() == 'HTTP' || $res->getError()->getCode() == 'HTTPS') {
                    $resumen = 'Servicio inestable, intentelo en otro momento';
                } else {
                    $resumen = 'Error ' . $res->getError()->getCode() . ': ' . $res->getError()->getMessage();
                }
                return $resumen;
            }
        }

    }

    public function getCdrStatusService($user, $password)
    {
        $ws = new SoapClient(SunatEndpoints::FE_CONSULTA_CDR . '?wsdl');
        //$ws = new SoapClient('https://e-factura.sunat.gob.pe/ol-it-wsconscpegem/billConsultService?wsdl');
        //$ws = new SoapClient('https://e-factura.sunat.gob.pe/ol-it-wsconsvalidcpe/billValidService?wsdl');

        $ws->setCredentials($user, $password);

        $service = new ConsultCdrService();
        $service->setClient($ws);

        return $service;
    }
}
