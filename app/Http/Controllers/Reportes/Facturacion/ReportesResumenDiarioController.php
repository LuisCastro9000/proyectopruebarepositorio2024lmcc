<?php

namespace App\Http\Controllers\Reportes\Facturacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Session;
use Carbon\Carbon;
use DateTime;
use Greenter\Model\Sale\Document;
use Greenter\Model\Summary\Summary;
use Greenter\Model\Summary\SummaryDetail;
use Greenter\Model\Summary\SummaryPerception;
use Greenter\Ws\Services\SunatEndpoints;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use App\Http\Controllers\Operaciones\NumeroALetras;
use App\Http\Controllers\Servicios\config;
use DB;
use Storage;

class ReportesResumenDiarioController extends Controller
{
	/* public function __construct(Request $req)
    { */
		/* if ($req->session()->has('idUsuario')) {
             $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
		echo  ':(';
		die(); */
		//dd($req);
		
	/* 	$this->middleware(function ($request, $next){
            
			$user_id = session('idUsuario');
			dd(Session::all());
			if(isset($user_id) && !empty($user_id))
			{
				print_r($user_id);
			}
            return $next($request);
        });
    } */
	
	/*  public function __invoke(Request $req) {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
			return ':)';
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        } 
	} */
	
	public function index(Request $req) : ?string{
        if ($req->session()->has('idUsuario')) {
             $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
		
        $loadDatos = new DatosController();
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
		$hoy = Carbon::today();
        $fechaAntes = $hoy->subDays(90);
		$date = Carbon::now();
		$horaActual = $date->toTimeString();
		//dd($horaActual);
		/*if($horaActual > "10:30:00"){
			dd("activar");
		}else{
			dd("desactivar");
		}*/
        $resumenDiario = $loadDatos->getReportesResumenDiario($idSucursal, $fechaAntes);
		
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
		$empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $array = ['usuarioSelect' => $usuarioSelect, 'permisos' => $permisos, 'resumenDiario' => $resumenDiario, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles, 'rucEmpresa' => $empresa->Ruc, 'horaActual' => $horaActual];
        return view('reportes/facturacion/reporteResumenDiario', $array);
    }
    
    public function descargarCDR(Request $req, $ruc, $id) {
		if ($req->session()->has('idUsuario')) {
			try{
				$idUsuario = Session::get('idUsuario');
				$loadDatos = new DatosController();
				$resumenDiarioSelect = $loadDatos->getResumenDiarioSelect($id);
				$file = 'R-'.$ruc.'-'.$resumenDiarioSelect->Numero.'.zip';
				
				if(Storage::disk('s3')->exists($resumenDiarioSelect->RutaCdr)){
					$rutaS3 = Storage::disk('s3')->get($resumenDiarioSelect->RutaCdr);

					$headers = [
						'Content-Type' => 'text/xml', 
						'Content-Description' => 'File Transfer',
						'Content-Disposition' => "attachment; filename=".$file."",
						'filename'=> ''.$file.''
					];

					return response($rutaS3, 200, $headers);
				}else{

					$rutaCDR = $this->generarCDR($resumenDiarioSelect);
					if($rutaCDR != null){
						$rutaS3 = Storage::disk('s3')->get($rutaCDR);
						$headers = [
							'Content-Type' => 'text/xml',
							'Content-Description' => 'File Transfer',
							'Content-Disposition' => "attachment; filename=" . $file . "",
							'filename' => '' . $file . '',
						];

						return response($rutaS3, 200, $headers);
					}else{
						return back()->with('error', 'No se encontró archivo Cdr');
					}
				}
				
			} catch (Exception $e){
				return redirect('reportes/facturacion/resumen-diario')->with('error', 'xml no encontrado');
			}
			
			//return response()->download(public_path().'/RespuestaSunat/ResumenDiario/'.$rucEmpresa.'/'.$file.'.xml');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }

        /*if ($req->session()->has('idUsuario')) {
             $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        
        $loadDatos = new DatosController();
        $resumenDiarioSelect = $loadDatos->getResumenDiarioSelect($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $rucEmpresa = $empresa->Ruc;
        $file = 'R-'.$rucEmpresa.'-'.$resumenDiarioSelect->Numero;
        return response()->download(public_path().'/RespuestaSunat/ResumenDiario/'.$rucEmpresa.'/'.$file.'.zip');*/
    }

	public function generarCDR($resumenDiarioSelect){
		$idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');
        
        $loadDatos = new DatosController();
        
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
		//dd($rucEmpresa);
        $res = $see->getStatus($resumenDiarioSelect->Ticket);
		if($res->getCdrResponse() == null){
            return redirect('/reportes/facturacion/resumen-diario')->with('error','Error, la respuesta de Sunat es: '.$res->getError()->getMessage().' , vuelva a intentar en un momento');
        }else{
			$now = Carbon::now();
			$anio = $now->year;
			$mes = $now->month;
			$_mes = $loadDatos->getMes($mes);
		
			if($resumenDiarioSelect->TipoResumen==1)
	    	{
				$bandExceccion=0;
				$bandRechazo=0;
	    		$cdr = $res->getCdrResponse();
                $name = $rucEmpresa.'-'.$cdr->getId();

				$isAccetedCDR=$res->getCdrResponse()->isAccepted();
				$descripcionCDR=$res->getCdrResponse()->getDescription();
				$codeCDR  =  $res->getCdrResponse()->getCode();	
					
				if(intval($codeCDR) == 0)
		   	    {
		   	    	$estado = 'Aceptado';
		   	    	$mensaje = $descripcionCDR;
		   			$tipoMensaje='Aceptado';
		   	    }
		   	    else if(intval($codeCDR) >=100 &&  intval($codeCDR) <=1999)
		   	    {
		   	    	$bandExceccion=1;
		   	    	$estado = 'Pendiente';
		   	    	$mensaje = $descripcionCDR;
		   			$tipoMensaje='Resumen Pendiente';
		   	    }
		   	    else if(intval($codeCDR) >=2000 && intval($codeCDR) <=3999)
		   	    {
		   			$bandRechazo=1;
		   	    	$estado = 'Pendiente';
		   	    	$mensaje = $descripcionCDR;
		   			$tipoMensaje='Resumen Rechazo';
		   	    }
		   	    else
		   	    {
		   	    	$estado = 'Aceptado';
		   	    	$mensaje = $descripcionCDR;
		   			$tipoMensaje='Aceptado';
				}

				$rutaCdr = '/RespuestaSunat/'.$anio.'/'.$_mes.'/'.$rucEmpresa.'/ResumenDiario/R-'.$name.'.zip';

				Storage::disk('s3')->put($rutaCdr, $res->getCdrZip(), 'public');

				DB::table('resumen_diario')
						->where('IdResumenDiario',$id)
						->update(['FechaEnviada' => Carbon::now(), 'Numero' => $cdr->getId(), 'CodResSunat' => $codeCDR, 'RutaCdr' => $rutaCdr, 'Estado' => $tipoMensaje]);

				if($bandExceccion==0)
				{
					

					DB::table('ventas')
							->where('IdResumenDiario', $id)
							->update(['Estado' => $estado]);
					
					if($bandRechazo == 1){
						return redirect('/reportes/facturacion/resumen-diario')->with('error', $mensaje);
					}else{
						return redirect('/reportes/facturacion/resumen-diario')->with('status','Se obtuvo CDR de Resumen Diario Correctamente');
					}
					
				}else{
					return redirect('/reportes/facturacion/resumen-diario')->with('error','La respuesta de Sunat es: '.$mensaje.' , vuelva a intentar en un momento');
				}
                
	    	}
	    	else if($resumenDiarioSelect->TipoResumen==2)
	    	{
	    		$bandBaja=0;
				$bandExceccion=0;

				$notas = $loadDatos->getResumenDiarioNotasFiltrado($idSucursal, $resumenDiarioSelect->IdTipoMoneda, $id, $resumenDiarioSelect->FechaEmitida, $resumenDiarioSelect->FechaEnviada); 
				
				/*$notas=DB::table('nota_credito_debito')
                    ->where('TicketResumen', $resumenDiarioSelect->Ticket)
                    ->get();*/

				$cdr = $res->getCdrResponse();
				$isAccetedCDR=$res->getCdrResponse()->isAccepted();
				$descripcionCDR=$res->getCdrResponse()->getDescription();
				$codeCDR  =  $res->getCdrResponse()->getCode();	
				$name = $rucEmpresa.'-'.$cdr->getId();
					
				if(intval($codeCDR) == 0)
		   	    {
		   			$codigoAceptado=$codeCDR;
		   	    	$estado = 'Aceptado';
		   	    	$mensaje = $descripcionCDR;
		   			$tipoMensaje='Aceptado';
		   	    }
		   	    else if(intval($codeCDR) >=100 &&  intval($codeCDR) <=1999)
		   	    {
		   	    	$bandExceccion=1;
		   			$bandBaja=1;
		   			$codigoAceptado=$codeCDR;
		   	    	$estado = 'Pendiente';
		   	    	$mensaje = $descripcionCDR;
		   			$tipoMensaje='Resumen Pendiente';
		   	    }
		   	    else if(intval($codeCDR) >=2000 && intval($codeCDR) <=3999)
		   	    {
		   			$bandBaja=1;
		   	    	$codigoAceptado=$codeCDR;
		   	    	$estado = 'Pendiente';
		   	    	$mensaje = $descripcionCDR;
		   			$tipoMensaje='Resumen Rechazo';
		   	    }
		   	    else
		   	    {
		   	    	$codigoAceptado=$codeCDR;
		   	    	$estado = 'Aceptado';
		   	    	$mensaje = $descripcionCDR;
		   			$tipoMensaje='Aceptado';
				}
				//dd($notas);

				$rutaCdr = '/RespuestaSunat/'.$anio.'/'.$_mes.'/'.$rucEmpresa.'/ResumenDiario/R-'.$name.'.zip';

				Storage::disk('s3')->put($rutaCdr, $res->getCdrZip(), 'public');

				DB::table('resumen_diario')
                            ->where('IdResumenDiario', $id)
                            ->update(['FechaEnviada' => Carbon::now(), 'Numero' => $cdr->getId(), 'CodResSunat' => $codeCDR, 'RutaCdr' => $rutaCdr, 'Estado' => $tipoMensaje]);
				
				if($bandExceccion==0)
				{
					if($bandBaja == 0)
					{
						for($j=0; $j<count($notas); $j++)
			    		{
							DB::table('nota_credito_debito')
                            ->where('IdCreditoDebito', $notas[$j]->IdCreditoDebito)
                            ->update(['Estado' => 'Aceptado']);
								
							if($notas[$j]->IdTipoNota==1)
							{
				        		$stock=DB::table('ventas_articulo')
				        				->where('IdVentas',$notas[$j]->IdVentas)
				        				->get();
				        				
				        		if(count($stock) >=1)
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
                                    
			            		    		$reponer = $_stock->Cantidad + ($stock[$k]->Cantidad * $stock[$k]->CantidadReal);
                                    
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
				        		               	'estado'=>1
				        		                    );
				        		         	DB::table('kardex')->insert($kardex);
				        				}
				        			}
				        		}
							}	
			    		}
						
						
			    	}
					else
					{
						DB::table('nota_credito_debito')
			    		->where('IdResumenDiario', $id)   //esto se agrego 21-01-2020
                        ->update(['Estado' => $estado]);
                	
						 
					}
				}
				return redirect('/reportes/facturacion/resumen-diario')->with('status','Se envio Resumen Diario Correctamente');
	    	}
	    	else if($resumenDiarioSelect->TipoResumen==3)
	    	{
	    		$bandBaja=0;
				$bandExceccion=0;
				
				/*$bajas=DB::table('baja_documentos')
                    ->where('TicketResumen',$resumenDiarioSelect->Ticket)
                    ->get();*/
				
					$bajas=DB::table('ventas')
				    ->whereBetween('FechaCreacion', [$resumenDiarioSelect->FechaEmitida, $resumenDiarioSelect->FechaEnviada])
                    ->where('Estado','Baja Pendiente')
                    ->get();
					
				$cdr = $res->getCdrResponse();
				
                /* $config->writeCdr($sum, $res->getCdrZip(), 4);
                $config->showResponse($sum, $cdr); */
					
				$isAccetedCDR=$res->getCdrResponse()->isAccepted();
				$descripcionCDR=$res->getCdrResponse()->getDescription();
				$codeCDR  =  $res->getCdrResponse()->getCode();	
				$name = $rucEmpresa.'-'.$cdr->getId();
					
				
				for($j=0; $j<count($bajas); $j++)
				{
					DB::table('ventas')
							->where('IdVentas', $bajas[$j]->IdVentas)
							->update(['Estado' => 'Baja Aceptado']);
						
					DB::table('baja_documentos')
					->where('TicketResumen', $resumenDiarioSelect->Ticket)
					->update(['Estado' => 'Baja Aceptado']);

					$stock=DB::table('ventas_articulo')
							->where('IdVentas',$bajas[$j]->IdVentas)
							->get();
					
					if(count($stock) >=1)
					{
						for($k=0; $k<count($stock); $k++)
						{
							
							$articulo=DB::table('articulo')
										->where('IdArticulo', $stock[$k]->IdArticulo)
										->first();
							
							if($articulo->IdTipo == 1){
								$cantidaSum = $articulo->Stock + ($stock[$k]->Cantidad * $stock[$k]->CantidadReal); 
								
								/* $arrayRelacion = ['IdBajaDocumento' => $idBaja, 'IdArticulo' => $stock[$k]->IdArticulo, 'Codigo' =>'PRO-'.$stock[$k]->IdArticulo , 'Descripcion' => $articulo->Descripcion, 'PrecioVenta' => $articulo->Precio, 'Cantidad' => ($stock[$k]->Cantidad * $stock[$k]->CantidadReal), 'Descuento' => 0.0, 'Total' => 0.0];
								DB::table('baja_detalle')->insert($arrayRelacion); */
								
								/*DB::table('articulo')
										->where('IdArticulo', $stock[$k]->IdArticulo)
										->update(['Stock' => $cantidaSum]);*/
							
								$_stock = $loadDatos->getUltimoStock($stock[$k]->IdArticulo);
								
								/*$reponer = $_stock->Cantidad + ($stock[$k]->Cantidad * $stock[$k]->CantidadReal);
								
									DB::table('stock')
										->where('IdStock', $_stock->IdStock)
										->update(['Cantidad'=>$reponer]);*/
									
								$kardex=array(	 
									'CodigoInterno'=>$articulo->CodigoInterno,
									'fecha_movimiento'=>Carbon::now(),
									'tipo_movimiento'=>6,  //doc. baja 
									'usuario_movimiento'=>$idUsuario,
									'documento_movimiento'=>$cdr->getId(),
									'existencia'=>$cantidaSum,
									'costo'=>1,
									'IdArticulo'=>$stock[$k]->IdArticulo,
									'IdSucursal'=>$idSucursal,
									'estado'=>1
										);
								DB::table('kardex')->insert($kardex);
							}
						}
					}
				}

				$rutaCdr = '/RespuestaSunat/'.$anio.'/'.$_mes.'/'.$rucEmpresa.'/ResumenDiario/R-'.$name.'.zip';

				Storage::disk('s3')->put($rutaCdr, $res->getCdrZip(), 'public');

				DB::table('resumen_diario')
					->where('Ticket', $resumenDiarioSelect->Ticket)
					->update(['FechaEnviada' => Carbon::now(), 'Numero' => $cdr->getId(), 'CodResSunat' => $codeCDR, 'RutaCdr' => $rutaCdr, 'Estado' => 'Aceptado']);
						


                return redirect('/reportes/facturacion/resumen-diario')->with('status','Se envio Resumen Diario Correctamente');
	    	}
	    	else
	    	{
	    		return redirect('/reportes/facturacion/resumen-diario')->with('error','No se  encontro el Tipo de Resumen');
	    	}
	    	
        }
	}

	public function descargarXML(Request $req, $ruc, $id){
        if ($req->session()->has('idUsuario')) {
			try{
				$idUsuario = Session::get('idUsuario');
				$loadDatos = new DatosController();
				$resumenDiarioSelect = $loadDatos->getResumenDiarioSelect($id);
				$file = $ruc.'-'.$resumenDiarioSelect->Numero.'.xml';
				
				if(Storage::disk('s3')->exists($resumenDiarioSelect->RutaXml)){
					$rutaS3 = Storage::disk('s3')->get($resumenDiarioSelect->RutaXml);

					$headers = [
						'Content-Type' => 'text/xml', 
						'Content-Description' => 'File Transfer',
						'Content-Disposition' => "attachment; filename=".$file."",
						'filename'=> ''.$file.''
					];
					
					return response($rutaS3, 200, $headers);
				}else{
					return back()->with('error','No se encontró archivo Xml');
				}
			} catch (Exception $e){
				return redirect('reportes/facturacion/resumen-diario')->with('error', 'xml no encontrado');
			}
			
			//return response()->download(public_path().'/RespuestaSunat/ResumenDiario/'.$rucEmpresa.'/'.$file.'.xml');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
    }
    
    public function enviarTicket(Request $req, $id){
        
		if ($req->session()->has('idUsuario')) {
             $idUsuario = Session::get('idUsuario');
			 $idSucursal = Session::get('idSucursal');
			 $mensaje = $this->procesarEnvio($id, $idUsuario, $idSucursal, 2);
			 //dd($mensaje);
			 if($mensaje[0] == 1){
				return redirect('/reportes/facturacion/resumen-diario')->with('status', $mensaje[1]);
			}else{
				return redirect('/reportes/facturacion/resumen-diario')->with('error', $mensaje[1]);
			}
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
    }

	public function enviarTicketAdmin(Request $req, $id, $idUsuario, $idSucursal){
		//dd($id.'-'.$idUsuario.'-'.$idSucursal);
		$mensaje = $this->procesarEnvio($id, $idUsuario, $idSucursal, 1);
		//dd($mensaje);
		if($mensaje[0] == 1){
			return redirect('/reportes/facturacion/ver-resumenes-diario-pendientes')->with('status', $mensaje[1]);
		}else{
			return redirect('/reportes/facturacion/ver-resumenes-diario-pendientes')->with('error', $mensaje[1]);
		}
	}

	public function procesarEnvio($id, $idUsuario, $idSucursal, $bandera){
		$loadDatos = new DatosController();
        $resumenDiarioSelect = $loadDatos->getResumenDiarioSelect($id);
        
		$opcionFactura = DB::table('usuario')
                             ->select('OpcionFactura')
                             ->where('IdUsuario', $idUsuario)
                             ->first();
		$usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
		$empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
			$config = new config();
			if($opcionFactura->OpcionFactura  > 0)
			{
					if($opcionFactura->OpcionFactura == 1) //sunat
					{
						if($bandera == 2){
							$see = $config->configuracion(SunatEndpoints::FE_BETA);
						}else{
							$see = new \Greenter\See();
        					$see->setService(SunatEndpoints::FE_BETA);
							$see->setCertificate(file_get_contents(__DIR__.'../../../Servicios/CertificadoDigital/10439528422.pem'));
							$see->setCredentials('20000000001MODDATOS', 'MODDATOS');
							//$see->setCertificate(file_get_contents(__DIR__.'/CertificadoDigital/'.$empresa->Ruc.'.pem'));
        					//$see->setCredentials($empresa->Ruc.''.$empresa->UsuarioSol, $empresa->ClaveSol);
						}
					}
					else if($opcionFactura->OpcionFactura == 2) //ose
					{
						if($bandera == 2){
							$see = $config->configuracion('https://demo-ose.nubefact.com/ol-ti-itcpe/billService?wsdl');
						}else{
							$see = new \Greenter\See();
        					$see->setService('https://demo-ose.nubefact.com/ol-ti-itcpe/billService?wsdl');
							$see->setCertificate(file_get_contents(__DIR__.'../../../Servicios/CertificadoDigital/10439528422.pem'));
							$see->setCredentials('20000000001MODDATOS', 'MODDATOS');
							//$see->setCertificate(file_get_contents(__DIR__.'/CertificadoDigital/'.$empresa->Ruc.'.pem'));
        					//$see->setCredentials($empresa->Ruc.''.$empresa->UsuarioSol, $empresa->ClaveSol);
						}
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
			//dd($see);
        
        $rucEmpresa = $empresa->Ruc;
		//dd($rucEmpresa);
        $res = $see->getStatus($resumenDiarioSelect->Ticket);
		
		$array = [];
        if($res->getCdrResponse() == null){
			//$mensaje ='Error '.$res->getError()->getCode().', la respuesta de Sunat es: '.$res->getError()->getMessage().' , vuelva a intentar en un momento';
			array_push($array, 0);
			array_push($array, 'Error '.$res->getError()->getCode().', la respuesta de Sunat es: '.$res->getError()->getMessage().' , vuelva a intentar en un momento');
			return $array;
            //return redirect($url)->with('error','Error, la respuesta de Sunat es: '.$res->getError()->getMessage().' , vuelva a intentar en un momento');
        }else{
			$now = Carbon::now();
			$anio = $now->year;
			$mes = $now->month;
			$_mes = $loadDatos->getMes($mes);
			//dd("llego");
			if($resumenDiarioSelect->TipoResumen==1)
	    	{
				$bandExceccion=0;
				$bandRechazo=0;
	    		$cdr = $res->getCdrResponse();
                $name = $rucEmpresa.'-'.$cdr->getId();

				$isAccetedCDR=$res->getCdrResponse()->isAccepted();
				$descripcionCDR=$res->getCdrResponse()->getDescription();
				$codeCDR  =  $res->getCdrResponse()->getCode();	
					
				if(intval($codeCDR) == 0)
		   	    {
		   	    	$estado = 'Aceptado';
		   	    	$mensaje = $descripcionCDR;
		   			$tipoMensaje='Aceptado';
		   	    }
		   	    else if(intval($codeCDR) >=100 &&  intval($codeCDR) <=1999)
		   	    {
		   	    	$bandExceccion=1;
		   	    	$estado = 'Pendiente';
		   	    	$mensaje = $descripcionCDR;
		   			$tipoMensaje='Resumen Pendiente';
		   	    }
		   	    else if(intval($codeCDR) >=2000 && intval($codeCDR) <=3999)
		   	    {
		   			$bandRechazo=1;
		   	    	$estado = 'Pendiente';
		   	    	$mensaje = $descripcionCDR;
		   			$tipoMensaje='Resumen Rechazo';
		   	    }
		   	    else
		   	    {
		   	    	$estado = 'Aceptado';
		   	    	$mensaje = $descripcionCDR;
		   			$tipoMensaje='Aceptado';
				}

				$rutaCdr = '/RespuestaSunat/'.$anio.'/'.$_mes.'/'.$rucEmpresa.'/ResumenDiario/R-'.$name.'.zip';

				Storage::disk('s3')->put($rutaCdr, $res->getCdrZip(), 'public');

				DB::table('resumen_diario')
						->where('IdResumenDiario',$id)
						->update(['FechaEnviada' => Carbon::now(), 'Numero' => $cdr->getId(), 'CodResSunat' => $codeCDR, 'RutaCdr' => $rutaCdr, 'Estado' => $tipoMensaje]);
				
				if($bandExceccion==0)
				{
					
					//$cdr = $res->getCdrResponse();	
                    //$config->writeCdr($sum, $res->getCdrZip(), $empresa->Ruc, $anio, $_mes, 4);

					//file_put_contents(public_path().'/RespuestaSunat/ResumenDiario/'.$ruc.'/'.$filename, $content);*/
            		//$ruta = 'RespuestaSunat/'.$anio.'/'.$mes.'/'.$ruc.'/ResumenDiario/'.$filename;
            		

					//file_put_contents(public_path().'/RespuestaSunat/ResumenDiario/'.$rucEmpresa.'/R-'.$name.'.zip', $res->getCdrZip());

					DB::table('ventas')
							->where('IdResumenDiario', $id)
							->update(['Estado' => $estado]);
					
					if($bandRechazo == 1){
						DB::table('ventas')
							->where('IdResumenDiario', $id)
							->update(['IdResumenDiario' => 0]);
						array_push($array, 0);
						array_push($array, $mensaje);
						return $array;
						//return redirect('/reportes/facturacion/resumen-diario')->with('error', $mensaje);
					}else{
						array_push($array, 1);
						array_push($array, 'Se obtuvo CDR de Resumen Diario Correctamente');
						return $array;
						//return redirect('/reportes/facturacion/resumen-diario')->with('status','Se obtuvo CDR de Resumen Diario Correctamente');
					}
					
				}else{
					array_push($array, 0);
					array_push($array, 'La respuesta de Sunat es: '.$mensaje.' , vuelva a intentar en un momento');
					return $array;
					//return redirect('/reportes/facturacion/resumen-diario')->with('error','La respuesta de Sunat es: '.$mensaje.' , vuelva a intentar en un momento');
				}
                
	    	}
	    	else if($resumenDiarioSelect->TipoResumen==2)
	    	{
	    		$bandBaja=0;
				$bandExceccion=0;
				$bandRechazo = 0;
				$notas = $loadDatos->getResumenDiarioNotasFiltrado($idSucursal, $resumenDiarioSelect->IdTipoMoneda, $id, $resumenDiarioSelect->FechaEmitida, $resumenDiarioSelect->FechaEnviada); 
				
				/*$notas=DB::table('nota_credito_debito')
                    ->where('TicketResumen', $resumenDiarioSelect->Ticket)
                    ->get();*/

				$cdr = $res->getCdrResponse();
				$isAccetedCDR=$res->getCdrResponse()->isAccepted();
				$descripcionCDR=$res->getCdrResponse()->getDescription();
				$codeCDR  =  $res->getCdrResponse()->getCode();	
				$name = $rucEmpresa.'-'.$cdr->getId();
					
				if(intval($codeCDR) == 0)
		   	    {
		   			$codigoAceptado=$codeCDR;
		   	    	$estado = 'Aceptado';
		   	    	$mensaje = $descripcionCDR;
		   			$tipoMensaje='Aceptado';
		   	    }
		   	    else if(intval($codeCDR) >=100 &&  intval($codeCDR) <=1999)
		   	    {
		   	    	$bandExceccion=1;
		   			$bandBaja=1;
		   			$codigoAceptado=$codeCDR;
		   	    	$estado = 'Pendiente';
		   	    	$mensaje = $descripcionCDR;
		   			$tipoMensaje='Resumen Pendiente';
		   	    }
		   	    else if(intval($codeCDR) >=2000 && intval($codeCDR) <=3999)
		   	    {
					$bandRechazo = 1;
		   			$bandBaja=1;
		   	    	$codigoAceptado=$codeCDR;
		   	    	$estado = 'Pendiente';
		   	    	$mensaje = $descripcionCDR;
		   			$tipoMensaje='Resumen Rechazo';
		   	    }
		   	    else
		   	    {
		   	    	$codigoAceptado=$codeCDR;
		   	    	$estado = 'Aceptado';
		   	    	$mensaje = $descripcionCDR;
		   			$tipoMensaje='Aceptado';
				}
				//dd($notas);

				$rutaCdr = '/RespuestaSunat/'.$anio.'/'.$_mes.'/'.$rucEmpresa.'/ResumenDiario/R-'.$name.'.zip';

				Storage::disk('s3')->put($rutaCdr, $res->getCdrZip(), 'public');

				DB::table('resumen_diario')
                            ->where('IdResumenDiario', $id)
                            ->update(['FechaEnviada' => Carbon::now(), 'Numero' => $cdr->getId(), 'CodResSunat' => $codeCDR, 'RutaCdr' => $rutaCdr, 'Estado' => $tipoMensaje]);
				
				if($bandExceccion==0)
				{
					if($bandBaja == 0)
					{
						for($j=0; $j<count($notas); $j++)
			    		{
							DB::table('nota_credito_debito')
                            ->where('IdCreditoDebito', $notas[$j]->IdCreditoDebito)
                            ->update(['Estado' => 'Aceptado']);
								
							if($notas[$j]->IdTipoNota==1)
							{
				        		$stock=DB::table('ventas_articulo')
				        				->where('IdVentas',$notas[$j]->IdVentas)
				        				->get();
				        				
				        		if(count($stock) >=1)
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
                                    
			            		    		$reponer = $_stock->Cantidad + ($stock[$k]->Cantidad * $stock[$k]->CantidadReal);
                                    
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
				        		               	'estado'=>1
				        		                    );
				        		         	DB::table('kardex')->insert($kardex);
				        				}
				        			}
				        		}
							}	
			    		}
						
						
			    	}
					else
					{
						DB::table('nota_credito_debito')
			    		->where('IdResumenDiario', $id)   //esto se agrego 21-01-2020
                        ->update(['Estado' => $estado]);
						
						if($bandRechazo == 1){
							DB::table('nota_credito_debito')
							->where('IdResumenDiario', $id)
							->update(['IdResumenDiario' => 0]);
						}
						 
					}
				}
				array_push($array, 1);
				array_push($array, 'Se envio Resumen Diario Correctamente');
				return $array;
				//return redirect('/reportes/facturacion/resumen-diario')->with('status','Se envio Resumen Diario Correctamente');
	    	}
	    	else if($resumenDiarioSelect->TipoResumen==3)
	    	{
	    		$bandBaja=0;
				$bandExceccion=0;
				
				/*$bajas=DB::table('baja_documentos')
                    ->where('TicketResumen',$resumenDiarioSelect->Ticket)
                    ->get();*/
				
					$bajas=DB::table('ventas')
				    ->whereBetween('FechaCreacion', [$resumenDiarioSelect->FechaEmitida, $resumenDiarioSelect->FechaEnviada])
                    ->where('Estado','Baja Pendiente')
                    ->get();
					
				$cdr = $res->getCdrResponse();
				
                /* $config->writeCdr($sum, $res->getCdrZip(), 4);
                $config->showResponse($sum, $cdr); */
					
				$isAccetedCDR=$res->getCdrResponse()->isAccepted();
				$descripcionCDR=$res->getCdrResponse()->getDescription();
				$codeCDR  =  $res->getCdrResponse()->getCode();	
				$name = $rucEmpresa.'-'.$cdr->getId();
					
				/*if(intval($codeCDR) == 0)
		   	    {
		   			$codigoAceptado=$codeCDR;
		   	    	$estado = 'Aceptado';
		   	    	$mensaje = $descripcionCDR;
		   			$tipoMensaje='Aceptado';
		   	    }
		   	    else if(intval($codeCDR) >=100 &&  intval($codeCDR) <=1999)
		   	    {
		   	    	$bandExceccion=1;
		   			$bandBaja=1;
		   			$codigoAceptado=$codeCDR;
		   	    	$estado = 'Pendiente';
		   	    	$mensaje = $descripcionCDR;
		   			$tipoMensaje='Resumen Pendiente';
		   	    }
		   	    else if(intval($codeCDR) >=2000 && intval($codeCDR) <=3999)
		   	    {
		   			$bandBaja=1;
		   	    	$codigoAceptado=$codeCDR;
		   	    	$estado = 'Pendiente';
		   	    	$mensaje = $descripcionCDR;
		   			$tipoMensaje='Resumen Pendiente';
		   	    }
		   	    else
		   	    {
		   	    	$codigoAceptado=$codeCDR;
		   	    	$estado = 'Aceptado';
		   	    	$mensaje = $descripcionCDR;
		   			$tipoMensaje='Aceptado';
				}*/
				/* dd($bajas);
				 */
				//if($bandExceccion==0)
				//{
					//if($bandBaja == 0)
					//{
						for($j=0; $j<count($bajas); $j++)
		         		{
			     			DB::table('ventas')
                                    ->where('IdVentas', $bajas[$j]->IdVentas)
                                    ->update(['Estado' => 'Baja Aceptado']);
			     				
							DB::table('baja_documentos')
                            ->where('TicketResumen', $resumenDiarioSelect->Ticket)
                            ->update(['Estado' => 'Baja Aceptado']);

							$stock=DB::table('ventas_articulo')
			           				->where('IdVentas',$bajas[$j]->IdVentas)
			           				->get();
							
			           		if(count($stock) >=1)
			           		{
			           			for($k=0; $k<count($stock); $k++)
			           			{
			           				
									$articulo=DB::table('articulo')
			           							->where('IdArticulo', $stock[$k]->IdArticulo)
			           							->first();
			           				
			           				if($articulo->IdTipo == 1){
    		           					$cantidaSum = $articulo->Stock + ($stock[$k]->Cantidad * $stock[$k]->CantidadReal); 
			           					
										/* $arrayRelacion = ['IdBajaDocumento' => $idBaja, 'IdArticulo' => $stock[$k]->IdArticulo, 'Codigo' =>'PRO-'.$stock[$k]->IdArticulo , 'Descripcion' => $articulo->Descripcion, 'PrecioVenta' => $articulo->Precio, 'Cantidad' => ($stock[$k]->Cantidad * $stock[$k]->CantidadReal), 'Descuento' => 0.0, 'Total' => 0.0];
                   						DB::table('baja_detalle')->insert($arrayRelacion); */
										
										/*DB::table('articulo')
                                      			->where('IdArticulo', $stock[$k]->IdArticulo)
                                      			->update(['Stock' => $cantidaSum]);*/
			           				
			           					$_stock = $loadDatos->getUltimoStock($stock[$k]->IdArticulo);
                                      
		               		    		/*$reponer = $_stock->Cantidad + ($stock[$k]->Cantidad * $stock[$k]->CantidadReal);
                                      
                          		    		DB::table('stock')
                                      			->where('IdStock', $_stock->IdStock)
                                      			->update(['Cantidad'=>$reponer]);*/
			           		    			
			           		    		$kardex=array(	 
			           		                'CodigoInterno'=>$articulo->CodigoInterno,
			           		         		'fecha_movimiento'=>Carbon::now(),
			           		               	'tipo_movimiento'=>6,  //doc. baja 
			           		               	'usuario_movimiento'=>$idUsuario,
			           		               	'documento_movimiento'=>$cdr->getId(),
			           		               	'existencia'=>$cantidaSum,
			           		               	'costo'=>1,
			           		               	'IdArticulo'=>$stock[$k]->IdArticulo,
			           		               	'IdSucursal'=>$idSucursal,
			           		               	'estado'=>1
			           		                    );
			           		         	DB::table('kardex')->insert($kardex);
			           				}
			           			}
			           		}
		         		}

						$rutaCdr = '/RespuestaSunat/'.$anio.'/'.$_mes.'/'.$rucEmpresa.'/ResumenDiario/R-'.$name.'.zip';

						Storage::disk('s3')->put($rutaCdr, $res->getCdrZip(), 'public');

		         		DB::table('resumen_diario')
                            ->where('Ticket', $resumenDiarioSelect->Ticket)
                            ->update(['FechaEnviada' => Carbon::now(), 'Numero' => $cdr->getId(), 'CodResSunat' => $codeCDR, 'RutaCdr' => $rutaCdr, 'Estado' => 'Aceptado']);
						
						
                		
					/*}
					else
					{
						DB::table('resumen_diario')
                            ->where('Ticket', $resumenDiarioSelect->Ticket)
                            ->update(['FechaEnviada' => Carbon::now(), 'Numero' => $cdr->getId(), 'Estado' => $tipoMensaje]);
					}*/
				//}

                array_push($array, 1);
				array_push($array, 'Se envio Resumen Diario Correctamente');
				return $array;
	    	}
	    	else
	    	{
				array_push($array, 0);
				array_push($array, 'No se  encontro el Tipo de Resumen');
				return $array;
	    	}
	    	
	    	/* dd($bajas);

            $cdr = $res->getCdrResponse();
            $name = $rucEmpresa.'-'.$cdr->getId();
            file_put_contents(public_path().'/RespuestaSunat/ResumenDiario/R-'.$name.'.zip', $res->getCdrZip());
            DB::table('resumen_diario')
                ->where('IdResumenDiario',$id)
                ->update(['Numero' => $cdr->getId(), 'Estado' => 'Aceptado']);
            return redirect('/reportes/facturacion/resumen-diario')->with('status','Se obtuvo CDR de Resumen Diario Correctamente'); */
        }
	}

    public function verResumenesDiario(Request $req){
        if ($req->session()->has('idUsuario')) {
			$idUsuario = Session::get('idUsuario');
			$idSucursal = Session::get('idSucursal');
			$loadDatos = new DatosController();
			$permisos = $loadDatos->getPermisos($idUsuario);
			
			$subpermisos=$loadDatos->getSubPermisos($idUsuario);
			$subniveles=$loadDatos->getSubNiveles($idUsuario);
			
			$resumenDiario = $this->resumenesDiario();
			
			$usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
			$modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
			$array = ['permisos' => $permisos, 'resumenDiario' => $resumenDiario, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
			
			return view('reportes/facturacion/verResumenesDiariosAdmin', $array);
	   }else{
		   Session::flush();
		   return redirect('/')->with('out','Sesión de usuario Expirado');
	   }
    }

	public function cambiarResumenesDiario(Request $req){
		$idUsuario = Session::get('idUsuario');
        $idSucursal = Session::get('idSucursal');
        $loadDatos = new DatosController();
		$idResumenDiario = $req->idResumendiario;
		$tipoResumen = $req->tipoResumen;
		$estado = $req->estado;

		if($estado == 1){
			$desEstado = "Aceptado";
		}else if($estado == 2){
			$desEstado = "Pendiente";
		}else{
			$desEstado = "Rechazo";
		}

		$resumen = DB::table('resumen_diario')
					->where('IdResumenDiario', $idResumenDiario)
					->first();

		if($resumen->Numero == null || $resumen->Numero == ''){
			$identificador = $req->identificador;
		}else{
			$identificador = $resumen->Numero;
		}

		DB::table('resumen_diario')
				->where('IdResumenDiario', $idResumenDiario)
				->update(['Estado' => $desEstado, 'Numero' => $identificador]);

		$fechaIni = Carbon::parse($resumen->FechaEmitida);
		$_fechaFin = Carbon::parse($resumen->FechaEmitida);
		$fechaFin = $_fechaFin->addDays(1);

		if($tipoResumen == 1){
			$documentos = DB::table('ventas')
					->whereBetween('FechaCreacion', [$fechaIni, $fechaFin])
					->where('IdSucursal', $resumen->IdSucursal)
					->where('IdTipoComprobante', 1)
					->where('IdResumendiario', $idResumenDiario)
					->where('Estado', 'Pendiente')
					->get();

			DB::table('ventas')
					->whereBetween('FechaCreacion', [$fechaIni, $fechaFin])
					->where('IdSucursal', $resumen->IdSucursal)
					->where('IdResumendiario', $idResumenDiario)
					->where('Estado' , 'Pendiente')
					->update(['Estado' => 'Aceptado']);
		}elseif($tipoResumen == 2){
			$documentos = DB::table('nota_credito_debito')
					->whereBetween('FechaCreacion', [$fechaIni, $fechaFin])
					->where('IdSucursal', $resumen->IdSucursal)
					->where('IdTipoNota', 1)
					->where('IdResumendiario', $idResumenDiario)
					->where('Estado', 'Pendiente')
					->get();

			DB::table('nota_credito_debito')
					->whereBetween('FechaCreacion', [$fechaIni, $fechaFin])
					->where('IdSucursal', $resumen->IdSucursal)
					->where('IdResumendiario', $idResumenDiario)
					->where('Estado' , 'Pendiente')
					->update(['Estado' => 'Aceptado']);
		}else{
			$documentos = DB::table('ventas')
					->whereBetween('FechaCreacion', [$fechaIni, $fechaFin])
					->where('IdSucursal', $resumen->IdSucursal)
					->where('IdTipoComprobante', 1)
					->where('IdResumendiario', $idResumenDiario)
					->where('Estado', 'Baja Pendiente')
					->get();

			DB::table('ventas')
					->whereBetween('FechaCreacion', [$fechaIni, $fechaFin])
					->where('IdSucursal', $resumen->IdSucursal)
					->where('IdResumendiario', $idResumenDiario)
					->where('Estado' , 'Baja Pendiente')
					->update(['Estado' => 'Baja Aceptado']);
		}
		
		

		Session::put('arrayBoletasPendientes', $documentos);
		
		return redirect('/reportes/facturacion/ver-resumenes-diario-pendientes')->with('status','Se cambio estado de resumen diario correctamente');
	}

    private function resumenesDiario(){
        try{
            $resumen = DB::table('resumen_diario')
                        ->join('sucursal','sucursal.IdSucursal', '=', 'resumen_diario.IdSucursal')
                        ->select('resumen_diario.*', 'sucursal.Nombre as Sucursal')
                        ->whereIn('resumen_diario.Estado', ['Resumen Pendiente','Nota Pendiente', 'Baja Pendiente'])
                        ->where('resumen_diario.Estado', '!=', 'Aceptado')
                        ->where('resumen_diario.FechaEmitida', '>', '2021-08-01')
                        ->orderBy('resumen_diario.FechaEnviada','desc')
                        ->get();
            return $resumen;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        
    }
}
