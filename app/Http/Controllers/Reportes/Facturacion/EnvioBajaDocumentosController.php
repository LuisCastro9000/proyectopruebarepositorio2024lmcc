<?php

namespace App\Http\Controllers\Reportes\Facturacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Session;
use Carbon\Carbon;
use DateTime;
use DB;
use Greenter\Model\Voided\Voided;
use Greenter\Model\Voided\VoidedDetail;
use Greenter\Ws\Services\SunatEndpoints;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;


use Greenter\Ws\Services\ConsultCdrService;
use Greenter\Ws\Services\SoapClient;

use App\Http\Controllers\Servicios\config;
use DOMDocument;
use Storage;

class EnvioBajaDocumentosController extends Controller
{
    public function index(Request $req){
        if ($req->session()->has('idUsuario')) {
             $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $fecha = '';
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
		
        $fechaHoyInicio = Carbon::today();
        $documentos = $loadDatos->getFacturaBajaActual($idSucursal, $fechaHoyInicio);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['permisos' => $permisos, 'fecha' => $fecha, 'documentos' => $documentos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
        return view('reportes/facturacion/envioBajaDocumentos', $array);
    }
    
    public function store(Request $req) {
        if ($req->session()->has('idUsuario')) {
             $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        $fecha = $req->fecha;
        if($fecha == null){
            return back()->with('error','Completar fecha para filtrar');
        }
        $loadDatos = new DatosController();
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
		
        $fechaConvert = Carbon::createFromFormat('d/m/Y',$fecha);
        if($fechaConvert->diffInDays()>7){
            return back()->with('error','Los documentos de baja no puede ser mayor a 7 días');
        }else{
            $fechaConvertidaInicial = $fechaConvert->startOfDay()->format("Y-m-d H:i:s");
            $fechaConvertidaFinal = $fechaConvert->endOfDay()->format("Y-m-d H:i:s");
            $documentos = $loadDatos->obtenerBajaDocumentosEnviarFactura($idSucursal, $fechaConvertidaInicial, $fechaConvertidaFinal);
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $array = ['permisos' => $permisos, 'fecha' => $fecha, 'documentos' => $documentos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
            return view('reportes/facturacion/envioBajaDocumentos', $array);
        }
    }
    
    public function enviarDocumentos(Request $req) {
        if ($req->session()->has('idUsuario')) {
             $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
		$tipoMensaje=0;
        $fecha = $req->fechaDocumentos;
        $fechaConvert = Carbon::createFromFormat('d/m/Y',$fecha);
        $fechaGuardar = $fechaConvert->format("Y-m-d");
        $fechaConvertidaInicial = $fechaConvert->startOfDay()->format("Y-m-d H:i:s");
        $fechaConvertidaFinal = $fechaConvert->endOfDay()->format("Y-m-d H:i:s");
 
		$loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $documentos = $loadDatos->obtenerBajaDocumentosEnviarFactura($idSucursal,$fechaConvertidaInicial,$fechaConvertidaFinal);

		if(count($documentos)>0){
            //$datos = $this->obtenerBajaDocumentoXML($req, $documentos, $fechaConvertidaInicial);
            
			$opcionFactura = DB::table('usuario')
                             ->select('OpcionFactura')
                             ->where('IdUsuario', $idUsuario)
                             ->first();
			$config = new config();
			if($opcionFactura->OpcionFactura  > 0)
			{
					if($opcionFactura->OpcionFactura == 1) //sunat
					{
						$see = $config->configuracion(SunatEndpoints::FE_BETA);
					}
					else if($opcionFactura->OpcionFactura == 2) //ose
					{
						$see = $config->configuracion('https://demo-ose.nubefact.com/ol-ti-itcpe/billService?wsdl');
					}
					else
					{
						return Response(['error','No  tiene Elejida la  ruta de  envio.... comuniquese con  el administrador']);
					}
			}
			else
			{
				return Response(['error','No  tiene Elejida  el tipo de  envio.... comuniquese con  el administrador']);
			}

			//$config = new config();
            //$see = $config->configuracion(SunatEndpoints::FE_BETA);

            $loadDatos = new DatosController();
            $idSucursal = $idSucursal = Session::get('idSucursal');
            $idUsuario = Session::get('idUsuario');
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

            $array = [];
            $denegado = [];
            for($i=0; $i<count($documentos); $i++){
                if($documentos[$i]->Tipo ==  "Factura"){
                    $tipoComprob = '01';
                }else{
                    $tipoComprob = '07';
                }
                $detail = new VoidedDetail();
                $detail->setTipoDoc($tipoComprob)
                ->setSerie($documentos[$i]->Serie)
                ->setCorrelativo($documentos[$i]->Numero)
                ->setDesMotivoBaja($documentos[$i]->Motivo);
                array_push($array, $detail);
				array_push($denegado, $documentos[$i]->IdDoc);
            }
			
            $cantidad = intval($correlativo->Cantidad);
            $voided = new Voided();
            $voided->setCorrelativo($cantidad+1)
                ->setFecGeneracion(new DateTime($fechaConvertidaInicial))
                ->setFecComunicacion(new DateTime())
                ->setCompany($company)
                ->setDetails($array);
            // Envio a SUNAT.
            //$see = $util->getSee();
            $res1 = $see->send($voided);
            //dd($voided);
            if (!$res1->isSuccess()) {
                if($res1->getError()->getCode() == 'HTTP' || $res1->getError()->getCode() == 'HTTPS'){
                    echo "<script language='javascript'>alert('Servicio inestable, intentelo en otro momento');</script>";
                    $resumen = 'Servicio inestable, intentelo en otro momento';
                }else{
					$resumen = 'Error '.$res1->getError()->getCode().': '.$res1->getError()->getMessage();
					$array = ['IdSucursal'=>$idSucursal, 'IdUsuario'=>$idUsuario, 'Hash' => '', 'FechaEmitida' => $fechaGuardar, 'FechaEnviada' => Carbon::now(), 'Identificador' => '', 'Ticket' => '', 'CodigoDoc' => $res1->getError()->getCode(), 'Estado' => 'Baja Rechazo'];
                    DB::table('baja_documentos')->insert($array);
                }
                return back()->with('error',$res1->getError()->getMessage().'-'.$res1->getError()->getCode());
            }else{
                /**@var $res \Greenter\Model\Response\SummaryResult*/
				$now = Carbon::now();
				$anio = $now->year;
				$mes = $now->month;
				$_mes = $loadDatos->getMes($mes);
				//$nombreArchivo = $sum->getName();
				//$rutaXml = '/RespuestaSunat/'.$anio.'/'.$_mes.'/'.$empresa->Ruc.'/BajaDocumentos/'.$nombreArchivo.'.xml';
                //$config->writeXml($sum, $see->getFactory()->getLastXml(), $empresa->Ruc, $anio, $_mes, 5);

                $ticket = $res1->getTicket();
                sleep(2);
                $res2 = $see->getStatus($ticket);
                if($res2->getCdrResponse() == null){
                    $_array = ['IdSucursal'=>$idSucursal, 'IdUsuario'=>$idUsuario, 'Hash' => '', 'FechaEmitida' => $fechaGuardar, 'FechaEnviada' => Carbon::now(), 'Identificador' => '', 'Ticket' => $ticket, 'Estado' => 'Baja Pendiente'];
                    DB::table('baja_documentos')->insert($_array);
					
					/*for($i=0; $i<count($documentos); $i++){

						DB::table('ventas')
                            ->where('IdVentas',$documentos[$i]->IdVentas)
                            ->update(['Estado' => 'Baja Pendiente']);				
                    }*/

					for($k=0; $k<count($denegado); $k++)
					{
						$dene=['IdVentas'=>$denegado[$k], 'Ticket' => $ticket];
						DB::table('denegado_baja')->insert($dene);
					} 

                    return redirect('/reportes/facturacion/baja-documentos')->with('error','No se pudo obtener CDR de Baja de Documentos');
                }else{
                    
					$bandBaja=0;
					$bandExceccion=0;

					$cdr = $res2->getCdrResponse();
					$name = $empresa->Ruc.'-'.$cdr->getId();
					$rutaCdr = '/RespuestaSunat/'.$anio.'/'.$_mes.'/'.$empresa->Ruc.'/ResumenDiario/R-'.$name.'.zip';
					$config->writeCdr($voided, $res2->getCdrZip(), $empresa->Ruc, $anio, $_mes, 4);
                    //$config->writeCdr($voided, $res2->getCdrZip(), $empresa->Ruc, 5);
                    $config->showResponse($voided, $cdr);
                    
                    $xml_string = $see->getXmlSigned($voided);
                    $doc = new DOMDocument();
                    $doc->loadXML($xml_string);
                    $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;

                    $isAccetedCDR = $res2->getCdrResponse()->isAccepted();
					$descripcionCDR = $res2->getCdrResponse()->getDescription();
					$codeCDR = $res2->getCdrResponse()->getCode();
       
					    if(intval($codeCDR) == 0)
					    {
							$codigoAceptado=$codeCDR;
					    	$estado = 'Aceptado';
					    	$mensaje = $descripcionCDR;
							$tipoMensaje='Baja Aceptado';
					    }
					    else if(intval($codeCDR) >=100 &&  intval($codeCDR) <=1999)
					    {
					    	$bandExceccion=1;
							$bandBaja=1;
							$codigoAceptado=$codeCDR;
					    	$estado = 'Excepcion';
					    	$mensaje = $descripcionCDR;
							$tipoMensaje='Baja Pendiente';
					    }
					    else if(intval($codeCDR) >=2000 && intval($codeCDR) <=3999)
					    {
							$bandBaja=1;
					    	$codigoAceptado=$codeCDR;
					    	$estado = 'Baja Rechazo';
					    	$mensaje = $descripcionCDR;
							$tipoMensaje='Baja Pendiente';
					    }
					    else
					    {
					    	$codigoAceptado=$codeCDR;
					    	$estado = 'Baja Observada';
					    	$mensaje = $descripcionCDR;
							$tipoMensaje='Baja Aceptado';
					    }
                    //dd($estado);
					if($bandExceccion==0)
					{
						$array = ['IdSucursal'=>$idSucursal, 'IdUsuario'=>$idUsuario, 'Hash' => $hash, 'FechaEmitida' => $fechaGuardar, 'FechaEnviada' => Carbon::now(), 'Identificador' => $cdr->getId(), 'Ticket' => $ticket, 'RutaCdr' => $rutaCdr, 'CodigoDoc'=>$codigoAceptado, 'Estado' => $estado];
                    	DB::table('baja_documentos')->insert($array);
						
						 $baja = DB::table('baja_documentos')
                                    ->orderBy('IdBajaDoc','desc')
                                    ->first();
                		$idBaja = $baja->IdBajaDoc;
					}

                    if($bandBaja==0)
					{
						for($i=0; $i<count($documentos); $i++){
							if($documentos[$i]->Tipo == "Factura"){
								DB::table('ventas')
									->where('IdVentas',$documentos[$i]->IdDoc)
									->update(['Estado' => $tipoMensaje]);
								
								$stock=DB::table('ventas_articulo')
										->where('IdVentas',$documentos[$i]->IdDoc)
										->get();
										
								if(count($stock) >=1)
								{
									for($j=0; $j<count($stock); $j++)
									{
										$articulo=DB::table('articulo')
													->where('IdArticulo', $stock[$j]->IdArticulo)
													->first();
								
										if($articulo->IdTipo == 1)
										{
											$cantidaSum = $articulo->Stock + ($stock[$j]->Cantidad * $stock[$j]->CantidadReal); 
											DB::table('articulo')
												->where('IdArticulo', $stock[$j]->IdArticulo)
												->update(['Stock' => $cantidaSum]);
										
											$_stock = $loadDatos->getUltimoStock($stock[$j]->IdArticulo);
										
											$reponer = $_stock->Cantidad + ($stock[$j]->Cantidad * $stock[$j]->CantidadReal);
										
											DB::table('stock')
												->where('IdStock', $_stock->IdStock)
												->update(['Cantidad'=>$reponer]);
												
											$kardex=array(	 
												'CodigoInterno'=>$articulo->CodigoInterno,
												'fecha_movimiento'=>Carbon::now(),
												'tipo_movimiento'=>6,  //baja de documento 
												'usuario_movimiento'=>$idUsuario,
												'documento_movimiento'=>$cdr->getId(),
												'existencia'=>$cantidaSum,
												'costo'=>1,
												'IdArticulo'=>$stock[$j]->IdArticulo,
												'IdSucursal'=>$idSucursal,
												'Cantidad'=>$stock[$j]->Cantidad * $stock[$j]->CantidadReal,
												'Descuento'=>0,
												'ImporteEntrada'=>0,
												'ImporteSalida'=>0,
												'estado'=>1
													);
											DB::table('kardex')->insert($kardex);
										
											$arrayRelacion = ['IdBajaDocumento' => $idBaja, 'IdArticulo' => $stock[$j]->IdArticulo, 'Codigo' =>'PRO-'.$stock[$j]->IdArticulo , 'Descripcion' => $articulo->Descripcion, 'PrecioVenta' => $articulo->Precio, 'Cantidad' => ($stock[$j]->Cantidad * $stock[$j]->CantidadReal), 'Descuento' => 0.0, 'Total' => 0.0];
											DB::table('baja_detalle')->insert($arrayRelacion);
										}
										
									}
								}		
							}else{
								DB::table('nota_credito_debito')
									->where('IdCreditoDebito',$documentos[$i]->IdDoc)
									->update(['Estado' => $tipoMensaje]);
							}				
                        }
					}
					
                    return redirect('reportes/facturacion/baja-documentos')
                            ->with('status', 'Se enviaron correctamente documentos con fecha: '.$fecha);
                }
            }
			
        }else{
            return back()->with('error','No se encontraron dcoumentos para enviar con fecha: '.$fecha);
        } 
    }
    
	public function enviarTicket(Request $req, $id, $tipoDocumento) {
        if ($req->session()->has('idUsuario')){
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        
		$loadDatos = new DatosController();
		$idSucursal = Session::get('idSucursal');
        $bajaDocumento = $loadDatos->getBajaDocumentoSelect($id);
        
		$opcionFactura = DB::table('usuario')
                             ->select('OpcionFactura')
                             ->where('IdUsuario', $idUsuario)
                             ->first();
			$config = new config();
			if($opcionFactura->OpcionFactura  > 0)
			{
					if($opcionFactura->OpcionFactura == 1) //sunat
					{
						$see = $config->configuracion(SunatEndpoints::FE_BETA);
					}
					else if($opcionFactura->OpcionFactura == 2) //ose
					{
						$see = $config->configuracion('https://demo-ose.nubefact.com/ol-ti-itcpe/billService?wsdl');
					}
					else
					{
						return Response(['error','No  tiene Elejida la  ruta de  envio.... comuniquese con  el administrador']);
					}
			}
			else
			{
				return Response(['error','No  tiene Elejida  el tipo de  envio.... comuniquese con  el administrador']);
			}
        
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $rucEmpresa = $empresa->Ruc;
        
	/* 	$service = $this->getCdrStatusService('20000000001MODDATOS', 'MODDATOS');
		
		$arguments = [
            $fields['ruc']='20000000001',
            $fields['tipo']='01',
            $fields['serie']='F101',
            intval($fields['numero']=134)
    	];
		
    
		$result = $service->getStatusCdr(...$arguments);
        dd($result);
	
	if (isset($fields['cdr'])) {
        $result = $service->getStatusCdr(...$arguments);
        dd($result);
		 if ($result->getCdrZip()) {
            $filename = 'R-'.implode('-', $arguments).'.zip';
            savedFile($filename, $result->getCdrZip());
        }

        return $result; 
    } */
		
        $res = $see->getStatus($bajaDocumento->Ticket);
		//dd($see->getStatus($resumenDiarioSelect->Ticket)->getCode());
		if($res->getCdrResponse() == null){
			DB::table('baja_documentos')
                ->where('Ticket', $bajaDocumento->Ticket)
                ->update(['CodigoDoc'=>$res->getError()->getCode()]);
            return redirect('/reportes/facturacion/baja-documentos')->with('error','No se pudo obtener CDR de La Baja');
        }else{
			
			$bandExceccion=0;
			$bandBaja=0;

			$now = Carbon::now();
			$anio = $now->year;
			$mes = $now->month;
			$_mes = $loadDatos->getMes($mes);

			$cdr = $res->getCdrResponse();
            $name = $rucEmpresa.'-'.$cdr->getId();
            /*if (!is_dir(public_path().'/RespuestaSunat/BajaDocumentos/'.$rucEmpresa)) {
                mkdir(public_path().'/RespuestaSunat/BajaDocumentos/'.$rucEmpresa);
            }
            file_put_contents(public_path().'/RespuestaSunat/BajaDocumentos/'.$rucEmpresa.'/R-'.$name.'.zip', $res->getCdrZip());*/

			$rutaCdr = '/RespuestaSunat/'.$anio.'/'.$_mes.'/'.$rucEmpresa.'/BajaDocumentos/R-'.$name.'.zip';

			Storage::disk('s3')->put($rutaCdr, $res->getCdrZip(), 'public');
										
			$isAccetedCDR=$res->getCdrResponse()->isAccepted();
			$descripcionCDR=$res->getCdrResponse()->getDescription();
			$codeCDR = $res->getCdrResponse()->getCode();
			
			    if(intval($codeCDR) == 0)
			    {
					$codigoAceptado=$codeCDR;
			    	$estado = 'Aceptado';
			    	$mensaje = $descripcionCDR;
					$tipoMensaje='Baja Aceptado';
			    }
			    else if(intval($codeCDR) >=100 &&  intval($codeCDR) <=1999)
			    {
			    	$bandExceccion=1;
					$bandBaja=1;
					$codigoAceptado=$codeCDR;
			    	$estado = 'Excepcion';
			    	$mensaje = $descripcionCDR;
					$tipoMensaje='Baja Pendiente';
			    }
			    else if(intval($codeCDR) >=2000 && intval($codeCDR) <=3999)
			    {
					$bandBaja=1;
			    	$codigoAceptado=$codeCDR;
			    	$estado = 'Baja Rechazo';
			    	$mensaje = $descripcionCDR;
					$tipoMensaje='Baja Pendiente';
			    }
			    else
			    {
			    	$codigoAceptado=$codeCDR;
			    	$estado = 'Baja Observada';
			    	$mensaje = $descripcionCDR;
					$tipoMensaje='Baja Aceptado';
			    }

			if($bandExceccion==0)
			{
				DB::table('baja_documentos')
                ->where('Ticket', $bajaDocumento->Ticket)
                ->update(['Identificador' => $cdr->getId(), 'CodigoDoc'=>$codigoAceptado, 'RutaCdr' => $rutaCdr, 'Estado' => $tipoMensaje]);
			
				/*$documentos=DB::table('baja_documentos')
                			->where('Ticket', $bajaDocumento->Ticket)
							->first();*/
			
			}else{
			    DB::table('baja_documentos')
                ->where('Ticket', $bajaDocumento->Ticket)
                ->update(['Identificador' => $cdr->getId(), 'CodigoDoc'=>$codigoAceptado, 'RutaCdr' => $rutaCdr]);
			}
			
            if($bandBaja==0)
			{
		     	if($tipoDocumento == 1){
					DB::table('ventas')
						->where('IdVentas',$bajaDocumento->IdVentas)
						->update(['Estado' => $tipoMensaje]);
	         		
	         		$stock=DB::table('ventas_articulo')
	         				->where('IdVentas',$bajaDocumento->IdVentas)
	         				->get();
	         				
	         		if(count($stock) >=1)
	         		{
	         			for($j=0; $j<count($stock); $j++)
	         			{
	         				$articulo=DB::table('articulo')
	         							->where('IdArticulo', $stock[$j]->IdArticulo)
	         							->first();
	         				
	         				if($articulo){
								$cantidaSum = floatval($articulo->Stock) + floatval($stock[$j]->Cantidad * $stock[$j]->CantidadReal); 
	         					DB::table('articulo')
                               			->where('IdArticulo', $stock[$j]->IdArticulo)
                               			->update(['Stock' => $cantidaSum]);
	         				
		     					$_stock = $loadDatos->getProductoStockSelect($stock[$j]->IdArticulo);
                               
								 $reponer = floatval($stock[$j]->Cantidad * $stock[$j]->CantidadReal);
                                    
								 DB::table('stock')
									 ->where('IdStock', $_stock[0]->IdStock)
									 ->increment('Cantidad', $reponer);
		     		    			
		     		    		$kardex=array(	 
		     		                'CodigoInterno'=>$articulo->CodigoInterno,
		     		         		'fecha_movimiento'=>Carbon::now(),
		     		               	'tipo_movimiento'=>6,  //baja de documento 
		     		               	'usuario_movimiento'=>$idUsuario,
		     		               	'documento_movimiento'=>$cdr->getId(),
		     		               	'existencia'=>$cantidaSum,
		     		               	'costo'=>1,
		     		               	'IdArticulo'=>$stock[$j]->IdArticulo,
									'IdSucursal'=>$idSucursal,
									'Cantidad'=>$stock[$j]->Cantidad * $stock[$j]->CantidadReal,
									'Descuento'=>0,
									'ImporteEntrada'=>0,
									'ImporteSalida'=>0,
		     		               	'estado'=>1
		     		                    );
		     		         	DB::table('kardex')->insert($kardex);
		     				
		     					$arrayRelacion = ['IdBajaDocumento' => $id, 'IdArticulo' => $stock[$j]->IdArticulo, 'Codigo' =>'PRO-'.$stock[$j]->IdArticulo , 'Descripcion' => $articulo->Descripcion, 'PrecioVenta' => $articulo->Precio, 'Cantidad' => $reponer, 'Descuento' => 0.0, 'Total' => 0.0];
                   					DB::table('baja_detalle')->insert($arrayRelacion);
		     				}
	         			}
	    			}
				}else{
					DB::table('nota_credito_debito')
						->where('IdCreditoDebito',$bajaDocumento->IdVentas)
						->update(['Estado' => $tipoMensaje]);

					$stock=DB::table('nota_detalle')
							->where('IdCreditoDebito',$bajaDocumento->IdVentas)
							->get();		
							
					$notaSelect = $loadDatos->getNotaSelect($bajaDocumento->IdVentas);
							if(count($stock) >=1)
							{
								for($j=0; $j<count($stock); $j++)
								{
									$articulo=DB::table('articulo')
												->where('IdArticulo', $stock[$j]->IdArticulo)
												->first();
							
									if($articulo->IdTipo == 1)
									{
										$quitar = floatval($stock[$j]->Cantidad);
										if($notaSelect->IdMotivo != 23){
											$cantidadRes = floatval($articulo->Stock) - $quitar; 
											DB::table('articulo')
												->where('IdArticulo', $stock[$j]->IdArticulo)
												->update(['Stock' => $cantidadRes]);
										
											$_stock = $loadDatos->getProductoStockSelect($stock[$j]->IdArticulo);
										
											
										
											DB::table('stock')
												->where('IdStock', $_stock[0]->IdStock)
												->decrement('Cantidad', $quitar);
												
											$kardex=array(	 
												'CodigoInterno'=>$articulo->CodigoInterno,
												'fecha_movimiento'=>Carbon::now(),
												'tipo_movimiento'=>6,  //baja de documento 
												'usuario_movimiento'=>$idUsuario,
												'documento_movimiento'=>$cdr->getId(),
												'existencia'=>$cantidadRes,
												'costo'=>1,
												'IdArticulo'=>$stock[$j]->IdArticulo,
												'IdSucursal'=>$idSucursal,
												'Cantidad'=>$quitar,
												'Descuento'=>0,
												'ImporteEntrada'=>0,
												'ImporteSalida'=>0,
												'estado'=>1
													);
											DB::table('kardex')->insert($kardex);
										}
									
										$arrayRelacion = ['IdBajaDocumento' => $id, 'IdArticulo' => $stock[$j]->IdArticulo, 'Codigo' =>'PRO-'.$stock[$j]->IdArticulo , 'Descripcion' => $articulo->Descripcion, 'PrecioVenta' => $articulo->Precio, 'Cantidad' => $quitar, 'Descuento' => 0.0, 'Total' => 0.0];
										DB::table('baja_detalle')->insert($arrayRelacion);
									}
									
								}
							}
				}

				return redirect('/reportes/facturacion/baja-documentos')->with('status','Se obtuvo CDR de la  baja Correctamente'); 
			}else{
			    return redirect('/reportes/facturacion/baja-documentos')->with('error','La respuesta de Sunat fue: '.$mensaje); 
			}		
        }
    }
	
    private function getCdrStatusService($user, $password)
	{
        $ws = new SoapClient(SunatEndpoints::FE_CONSULTA_CDR.'?wsdl');
        $ws->setCredentials($user, $password);
       
        $service = new ConsultCdrService();
        $service->setClient($ws);
       
        return $service;
	}
	
	
	protected function obtenerBajaDocumentoXML($req, $documentos, $fecha) {
        
    }
}
