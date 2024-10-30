<?php

namespace App\Http\Controllers\Reportes\Facturacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Session;
use DateTime;
use App\Http\Controllers\Operaciones\NumeroALetras;
use App\Http\Controllers\Servicios\config;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Detraction;
use Greenter\Model\Sale\Legend;
use Greenter\Ws\Services\SunatEndpoints;
use Greenter\Model\Sale\Note;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\FormaPagos\FormaPagoCredito;
use Greenter\Model\Sale\Cuota;
use Greenter\Model\Sale\DetailAttribute;

use Greenter\Ws\Services\ConsultCdrService;
use Greenter\Ws\Services\SoapClient;
use Greenter\Model\Response\StatusCdrResult;

use DOMDocument;
use Sunat\Sunat;
use DB;
use Carbon\Carbon;
use Storage;

class FacturasPendientesController extends Controller
{
    public function index(Request $req){
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
        $fecha = '';
        $facturas = $loadDatos->getFacturasPendientes($idSucursal, $fechaAntes);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['usuarioSelect' => $usuarioSelect, 'permisos' => $permisos, 'fecha' => $fecha, 'modulosSelect' => $modulosSelect, 'facturas' => $facturas, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
        return view('reportes/facturacion/facturasPendientes', $array);
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
        $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
		
        $fechaIni = DateTime::createFromFormat('d/m/Y',$fecha);
        $fechaConvertidaInicio = $fechaIni->format("Y-m-d");
        $fechaFin = strtotime('+1 day',strtotime($fechaConvertidaInicio));
        $fechaConvertidaFinal = date('Y-m-d',$fechaFin);
        $facturas = $loadDatos->getFacturasPendientesFiltrados($idSucursal, $fechaConvertidaInicio, $fechaConvertidaFinal);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['permisos' => $permisos, 'fecha' => $fecha, 'facturas' => $facturas, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
        return view('reportes/facturacion/facturasPendientes', $array);
    }
    
    public function enviarSunat(Request $req) {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        
        if($req->comprobante == 1){
            $pregunta=$this->facturas($req);
			if(is_numeric($pregunta)){
				return redirect()->to('/reportes/facturacion/facturas-pendientes')->with('status','Se envio Factura Electrónica a Sunat');
			}
			else
			{
				return redirect()->to('/reportes/facturacion/facturas-pendientes')->with('error',$pregunta);
			}
        }
        if($req->comprobante == 2){
            $resp=$this->notas($req);
			if(is_numeric($resp))
			{
				return redirect()->to('/reportes/facturacion/facturas-pendientes')->with('status','Se envio Factura Electrónica a Sunat');
			}
			else
			{
				return redirect('/reportes/facturacion/facturas-pendientes')->with('error',$resp);
			}
        }
    }
    
    private function facturas($req) {
        $idUsuario = Session::get('idUsuario');
        $idSucursal = Session::get('idSucursal');
        $idDoc = $req->idDocEnvio;
        $loadDatos = new DatosController();
        $ventaSelect = $loadDatos->getVentaselect($idDoc);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $fecha = $ventaSelect->FechaCreacion;
        $date = DateTime::createFromFormat('Y-m-d H:i:s',$fecha);
		
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
		
        $cliente = $loadDatos->getClienteSelect($ventaSelect->IdCliente);
        
        $service = $this->getCdrStatusService($empresa->Ruc.''.$empresa->UsuarioSol, $empresa->ClaveSol);
        //$service = $this->getCdrStatusService('20000000001MODDATOS', 'MODDATOS');
		//dd($service);
		$arguments = [
            $fields['ruc']=$empresa->Ruc,
            $fields['tipo']='01',
            $fields['serie']=$ventaSelect->Serie,
            intval($fields['numero']=$ventaSelect->Numero)
    	];

		$res = $service->getStatusCdr(...$arguments);
        //dd($res);
		if($res->getCdrZip())
		{
			
			$name='R-'.$empresa->Ruc.'-01-'.$ventaSelect->Serie.'-'.$ventaSelect->Numero.'.zip';
			$resumen=1;
			$banderaRechazo=0;
			$cdr = $res->getCdrResponse();
			
			$isAccetedCDR=$res->getCdrResponse()->isAccepted();
			$descripcionCDR=$res->getCdrResponse()->getDescription();
			$codeCDR=  $res->getCdrResponse()->getCode();
            
			
			    if(intval($codeCDR) == 0)
			    {
					$codigoAceptado=$codeCDR;
			    	$estado = 'Aceptado';
			    	$mensaje = $descripcionCDR;
					$resumen=1;
					
			    }
			    else if(intval($codeCDR) >=100 &&  intval($codeCDR) <=1999)
			    {
					$banderaRechazo=1;
					$codigoAceptado=$codeCDR;
			    	$estado = 'Pendiente';
			    	$mensaje = $descripcionCDR;
					$resumen=$descripcionCDR;
			    }
			    else if(intval($codeCDR) >=2000 && intval($codeCDR) <=3999)
			    {
			    	
					$codigoAceptado=$codeCDR;
			    	$estado = 'Rechazo';
			    	$mensaje = $descripcionCDR;
					$resumen=$descripcionCDR;
			    }
			    else if(intval($codeCDR) >= 4000)
			    {
			    	$codigoAceptado=$codeCDR;
			    	$estado = 'Observado';
			    	$mensaje = $descripcionCDR;
					$resumen=1;
			    }else{
                    $banderaRechazo=1;
			        $codigoAceptado=$codeCDR;
			        $estado = 'Pendiente';
			        $resumen="Error en el Sistema, vuelva a intentar";
			    }
 			
			if($banderaRechazo==0)
			{
                $now = Carbon::now();
                $anio = $now->year;
                $mes = $now->month;
                $_mes = $loadDatos->getMes($mes);

                $rutaCdr = '/RespuestaSunat/'.$anio.'/'.$_mes.'/'.$$empresa->Ruc.'/FacturasBoletas/'.$name;
                Storage::disk('s3')->put($rutaCdr, $res->getCdrZip(), 'public');

				DB::table('ventas')
                        ->where('IdVentas',$idDoc)
                        ->update(['CodigoDoc'=>$codigoAceptado, 'RutaCdr' => $rutaCdr, 'Estado' => $estado]);

				//$name = $rucEmpresa.'-'.$cdr->getId();
            	//file_put_contents(public_path().'/RespuestaSunat/BajaDocumentos/R-'.$name.'.zip', $res->getCdrZip());
				
                //file_put_contents(public_path().'/RespuestaSunat/FacturasBoletas/'.$empresa->Ruc.'/'.$name, $res->getCdrZip());
                
                if($estado == 'Rechazo'){
                    $itemasVentaSelect = $loadDatos->getItemsVentas($idDoc);
                    for($i=0; $i<count($itemasVentaSelect); $i++){
                        if($itemasVentaSelect[$i]->IdPaquetePromocional > 0){
                            $productoSelect = DB::table('paquetes_promocionales')
                                                        ->where('IdPaquetePromocional', $itemasVentaSelect[$i]->IdPaquetePromocional)
                                                        ->first();
                            $productoSelectDatos = $this->getItemsPaquetePromocional($req->Id[$i]);
                            for ($j = 0; $j < count($productoSelectDatos); $j++) {
                                $productoSelectItem = $loadDatos->getProductoSelect($productoSelectDatos[$j]->IdArticulo);
                                $stockSelect = $loadDatos->getProductoStockSelect($productoSelectDatos[$i]->IdArticulo);
                                if($productoSelectItem->IdTipo == 1){
                                    $cantidadSumada = $productoSelectDatos[$j]->cantidad;
                                    DB::table('articulo')
                                        ->where('IdArticulo', $productoSelectDatos[$j]->IdArticulo)
                                        ->increment('Stock', $cantidadSumada);

                                    DB::table('stock')
                                        ->where('IdStock', $stockSelect[0]->IdStock)
                                        ->increment('Cantidad', $cantidadSumada);
                                    
                                    $fechaKardex = $loadDatos->getDateTime();

                                    $kardex = array(
                                        'CodigoInterno' => $productoSelectItem->CodigoInterno,
                                        'fecha_movimiento' => $fechaKardex,
                                        'tipo_movimiento' => 17,
                                        'usuario_movimiento' => $idUsuario,
                                        'documento_movimiento' => $ventaSelect->Serie.'-'.$ventaSelect->Numero,
                                        'existencia' => $productoSelectItem->Stock + $cantidadSumada,
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
                        }else{
                            $productoSelect = $loadDatos->getProductoSelect($itemasVentaSelect[$i]->IdArticulo);
                            $stockSelect = $loadDatos->getProductoStockSelect($itemasVentaSelect[$i]->IdArticulo);
                            if($productoSelect->IdTipo == 1){
                                if($itemasVentaSelect[$i]->VerificaTipo != 1)
                                {
                                    $newCantidad=$itemasVentaSelect[$i]->Cantidad*$itemasVentaSelect[$i]->CantidadReal;
                                }
                                else
                                {
                                    $newCantidad=$itemasVentaSelect[$i]->Cantidad;
                                }

                                DB::table('articulo')
                                    ->where('IdArticulo',$productoSelect->IdArticulo)
                                    ->increment('Stock' , $newCantidad);

                                DB::table('stock')
                                    ->where('IdStock', $stockSelect[0]->IdStock)
                                    ->increment('Cantidad', $newCantidad);

                                $fechaKardex = $loadDatos->getDateTime();

                                $kardex=array(	 
                                    'CodigoInterno'=>$productoSelect->CodigoInterno,
                                    'fecha_movimiento'=>$fechaKardex,
                                    'tipo_movimiento'=>17,
                                    'usuario_movimiento'=>$idUsuario,
                                    'documento_movimiento'=>$ventaSelect->Serie.'-'.$ventaSelect->Numero,
                                    'existencia'=>$productoSelect->Stock + $newCantidad,
                                    'costo'=>floatval($itemasVentaSelect[$i]->PrecioUnidadReal),
                                    'IdArticulo'=>$productoSelect->IdArticulo,
                                    'IdSucursal'=>$idSucursal,
                                    'Cantidad'=>floatval($itemasVentaSelect[$i]->Cantidad),
                                    'Descuento'=>floatval($itemasVentaSelect[$i]->Descuento),
                                    'ImporteEntrada'=>floatval($itemasVentaSelect[$i]->Importe),
                                    'ImporteSalida'=>0,
                                    'estado'=>1
                                );
                                DB::table('kardex')->insert($kardex);
                            }   
                        }
                    }
                }
			}else{
                DB::table('ventas')
                        ->where('IdVentas',$idDoc)
                        ->update(['CodigoDoc'=>$codigoAceptado]);
            }
			
			return $resumen;
		}
		else
		{
            
            

            $tipoMoneda = $loadDatos->getTipoMonedaSelect($ventaSelect->IdTipoMoneda);

			$client = new Client();
            $client->setTipoDoc($cliente->CodigoSunat)//agregado
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
            if($exonerada == '-'){
                $exonerada = '0.00';
            }

            if($ventaSelect->TipoVenta == 1){
                $opGravada = $ventaSelect->Subtotal;
                $opExonerada = 0;
                $codTipo = '04'; // op Gravadas
            }else{
                $opGravada = 0;
                $opExonerada = $ventaSelect->Subtotal;
                $codTipo = '05'; // op Exoneradas
            }

            $totalGratuita = 0;
            if($ventaSelect->Gratuita > 0){
                $totalGratuita = floatval($ventaSelect->Gratuita);
                if($ventaSelect->TipoVenta == 1){
                    $subTotalGratuita = floatval($totalGratuita / 1.18);
                    $igvGratuita = floatval($totalGratuita - $subTotalGratuita);
                }else{
                    $subTotalGratuita = floatval($totalGratuita);
                    $igvGratuita = '0.00';
                }
            }else{
                $subTotalGratuita = '0.00';
                $igvGratuita = '0.00';
            }


            $idAnticipo = $ventaSelect->Anticipo;
            if($idAnticipo > 2){
                $anticipoSelect = DB::table('ventas')
                            ->where('IdVentas', $idAnticipo)
                            ->first();
                $docRelacionado = $anticipoSelect->Serie.'-'.$anticipoSelect->Numero;

                $total = floatval($ventaSelect->Total) + floatval($ventaSelect->Amortizacion);

                $mtoOperGravadas = floatval($opGravada);
                $mtoIGV = floatval($ventaSelect->IGV);
                $mtoSubTotal = floatval($total) + floatval($anticipoSelect->Total);
                $mtoValorVenta = floatval($ventaSelect->Subtotal) +  floatval($anticipoSelect->Subtotal);

                $mtoOperExoneradas = floatval($opExonerada);
                //$totalDif = floatval($total) - floatval($anticipoSelect->Total);

                $invoice = (new Invoice())
                    ->setUblVersion('2.1')
                    //->setTipoOperacion('0101') // Catalog. 51
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
                        ->setMontoBase($anticipoSelect->Subtotal)
                    ])
                    ->setAnticipos([
                        (new Prepayment())
                            ->setTipoDocRel('02') // catalog. 12
                            ->setNroDocRel($docRelacionado)
                            ->setTotal($anticipoSelect->Total)
                    ])
                    ->setTotalAnticipos($anticipoSelect->Total); //PrepaidAmount 
            }else{
                 //$total = floatval($ventaSelect->Total) - floatval($exonerada);
                $total = floatval($ventaSelect->Total) + floatval($ventaSelect->Amortizacion);
                
                $invoice = (new Invoice())
                    ->setUblVersion('2.1')
                    //->setTipoOperacion('0101') // Catalog. 51
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

            if($ventaSelect->OrdenCompra != null && $ventaSelect->OrdenCompra != ""){
                $invoice->setCompra($ventaSelect->OrdenCompra);
            }

            $array = [];
            $legends = [];
            $countGratuita = 0;
	    	$itemasVentaSelect = $loadDatos->getItemsVentas($idDoc);
            $condicionDetrac = 0;
            for($i=0; $i<count($itemasVentaSelect); $i++){
                if($itemasVentaSelect[$i]->IdPaquetePromocional > 0){
                    $productoSelect = DB::table('paquetes_promocionales')
                                                ->where('IdPaquetePromocional', $itemasVentaSelect[$i]->IdPaquetePromocional)
                                                ->first();
                    $condicionDetrac = 1;
                    $medidaSunat = 'ZZ';
                    $descripcion = $productoSelect->NombrePaquete;
                }else{
                    $productoSelect = $loadDatos->getProductoSelect($itemasVentaSelect[$i]->IdArticulo);
                    if($productoSelect->IdTipo == 2){
                        $condicionDetrac = 1;
                    }
                    $medidaSunat = $productoSelect->MedidaSunat;
                    $descripcion = $productoSelect->Descripcion;
                }
                
	    		
	    		if($itemasVentaSelect[$i]->VerificaTipo == 1 || $itemasVentaSelect[$i]->VerificaTipo == 3 || $itemasVentaSelect[$i]->VerificaTipo == 4)
	    		{
	    			$newCantidad=$itemasVentaSelect[$i]->Cantidad;
	    		}
	    		else
	    		{
                    $newCantidad=$itemasVentaSelect[$i]->Cantidad*$itemasVentaSelect[$i]->CantidadReal;
	    		}
	    		
	    		$valorUniDescuento=floatval(round($itemasVentaSelect[$i]->Importe/$newCantidad,2));
                if($ventaSelect->TipoVenta == 1){
                    $subTotalItem=floatval($valorUniDescuento/1.18);
                    $afectIgv = '10';
                    $porcentaje = 18;
                }else{
                    $subTotalItem=floatval($valorUniDescuento);
                    $afectIgv = '20';
                    $porcentaje = 0;
                }
	    		
                
                $igvItem = $valorUniDescuento - $subTotalItem;
                $mtoValorVenta = floatval($newCantidad * $subTotalItem);
                $igvTotal = floatval($newCantidad * $igvItem);
                $totalImpuesto = floatval($igvTotal);
                $valorGratuito = 0;
                if($itemasVentaSelect[$i]->Gratuito == 1){
                    $valorGratuito = floatval($subTotalItem);
                    $valorUniDescuento = 0;
                    $subTotalItem = 0;
                    $totalImpuesto = 0;
                    $countGratuita++;
                    if($ventaSelect->TipoVenta == 1){
                        $afectIgv = '11';
                    }else{
                        $afectIgv = '21';
                    }
                }

                $item = (new SaleDetail())
                ->setCodProducto($itemasVentaSelect[$i]->Codigo)
                ->setUnidad($medidaSunat)
                ->setCantidad($newCantidad)
                ->setDescripcion($descripcion)
                ->setMtoBaseIgv(round($mtoValorVenta,5))
                ->setPorcentajeIgv($porcentaje) // 18%
                ->setIgv(round($igvTotal, 5))
                ->setTipAfeIgv($afectIgv)
                ->setTotalImpuestos(round($totalImpuesto, 5))
                ->setMtoValorVenta(round($mtoValorVenta, 5))
                ->setMtoValorGratuito(round($valorGratuito, 5))
                ->setMtoValorUnitario(round($subTotalItem, 5))
                ->setMtoPrecioUnitario(round($valorUniDescuento, 5));

                if($ventaSelect->Placa != null || $ventaSelect->Placa != '') {
                    $item->setAtributos([(new DetailAttribute())
                            ->setName('Gastos Art. 37 Renta: Número de Placa')
                            ->setCode('7000')
                            ->setValue($ventaSelect->Placa)]);
                }

                array_push($array, $item);
                usleep(100000);
            }


            if($ventaSelect->Detraccion == 1){
                $codigoBS = $loadDatos->getCodigoBienServicioSelect($ventaSelect->CodDetraccion);
                $codigoMedioPago = $loadDatos->getCodigoMedioPagoSelect($ventaSelect->CodMedioPago);
                $montoDetraccion = floatval($total * $ventaSelect->PorcentajeDetraccion / 100);
                $cuentaDetraccion = $loadDatos->getCuentaDetracciones($usuarioSelect->CodigoCliente, 9);

                $invoice->setDetraccion(
                    (new Detraction())
                        ->setCodBienDetraccion($codigoBS->CodigoSunat) // catalog. 54
                        ->setCodMedioPago($codigoMedioPago->Codigo) // catalog. 59
                        ->setCtaBanco($cuentaDetraccion->NumeroCuenta)
                        ->setPercent($ventaSelect->PorcentajeDetraccion)
                        ->setMount($montoDetraccion))
                ->setTipoOperacion('1001');
            }else{
                $invoice->setTipoOperacion('0101');
            }

            if($ventaSelect->IdTipoPago == 1){
                $invoice->setFormaPago(new FormaPagoContado());
                if($ventaSelect->Retencion == 1){
                    $montoRetencion = floatval($total * 0.03);
                    $invoice->setDescuentos([
                        (new Charge())
                            ->setCodTipo('62') // Catalog. 53
                            ->setMontoBase($total)
                            ->setFactor(0.03) // 3%
                            ->setMonto(round($montoRetencion,2))
                    ]);
                }
            }else{
                if($ventaSelect->Detraccion == 1){
                    $totalCredito = floatval($total) - floatval($total * $ventaSelect->PorcentajeDetraccion/100);
                }else{
                    if($ventaSelect->Retencion == 1){
                        $montoRetencion = floatval($total * 0.03);
                        $totalCredito = floatval($total) - floatval($montoRetencion);
                        $invoice->setDescuentos([
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
                $_date = Carbon::parse($ventaSelect->FechaCreacion);
                $fechaPago = $_date->addDays($ventaSelect->PlazoCredito);
                
                $invoice->setFormaPago(new FormaPagoCredito(round($totalCredito,2)));
                $invoice->setCuotas([
                    (new Cuota())
                        ->setMonto(round($totalCredito,2))
                        ->setFechaPago(new DateTime($fechaPago))
                ]);
            }
            
            $convertirLetras = new NumeroALetras();
            if($ventaSelect->IdTipoMoneda == 1){
                $importeLetras = $convertirLetras->convertir($total , 'soles');
            }
            else{
                $importeLetras = $convertirLetras->convertir($total , 'dolares');
            }
            $legend = (new Legend())
                ->setCode('1000')
                ->setValue($importeLetras);

            array_push($legends, $legend);

            if($countGratuita > 0){
                $legend2 = (new Legend())
                ->setCode('1002')
                ->setValue('TRANSFERENCIA GRATUITA DE UN BIEN Y/O SERVICIO PRESTADO GRATUITAMENTE');

                array_push($legends, $legend2);
            }

            if($ventaSelect->Detraccion == 1){
                $legend3 = (new Legend())
                    ->setCode('2006')
                    ->setValue('Operación sujeta a detracción');

                array_push($legends, $legend3);
            }
        
            $invoice->setDetails($array)
                    ->setLegends($legends);
            
            
            $rucEmpresa = $empresa->Ruc;
            $serie = $ventaSelect->Serie;
            $numero= $ventaSelect->Numero;
            $idTipoComprobante = $ventaSelect->IdTipoComprobante;
            $cod = $serie.'-'.$numero;
            if($idTipoComprobante == 1){
                $file = $rucEmpresa.'-03-'.$cod;
            }else{
                $file = $rucEmpresa.'-01-'.$cod;
            }
            //$ruta = public_path().'/RespuestaSunat/FacturasBoletas/'.$rucEmpresa.'/'.$file.'.xml';
            $now = Carbon::now();
            $anio = $now->year;
            $mes = $now->month;
            $_mes = $loadDatos->getMes($mes);
            $nombreArchivo = $file;
            $rutaXml = '/RespuestaSunat/'.$anio.'/'.$_mes.'/'.$rucEmpresa.'/FacturasBoletas/'.$nombreArchivo.'.xml';
            if(Storage::disk('s3')->exists($ventaSelect->RutaXml)){
                $ruta = Storage::disk('s3')->get($ventaSelect->RutaXml);
                file_put_contents($nombreArchivo.'.xml', $ruta);
                $result = $see->sendXml(get_class($invoice), $invoice->getName(), file_get_contents($nombreArchivo.'.xml'));
                if(unlink($nombreArchivo.'.xml')) {
                }
            }else{
                $xml_string = $see->getXmlSigned($invoice);
                $doc = new DOMDocument();
                $doc->loadXML($xml_string);
                $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                $config->writeXml($invoice, $see->getFactory()->getLastXml(), $rucEmpresa, $anio, $_mes, 1);
                $result = $see->send($invoice);
            }
            //dd($result);
            if ($result->isSuccess()) {
                //$config->writeXml($invoice, $see->getFactory()->getLastXml(), $rucEmpresa, 1);
                $cdr = $result->getCdrResponse();
                $rutaCdr = '/RespuestaSunat/'.$anio.'/'.$_mes.'/'.$rucEmpresa.'/FacturasBoletas/R-'.$nombreArchivo.'.zip';
                $config->writeCdr($invoice, $result->getCdrZip(), $rucEmpresa, $anio, $_mes, 1);
                //$config->writeCdr($invoice, $result->getCdrZip(), $rucEmpresa, 1);
                $config->showResponse($invoice, $cdr);

                $isAccetedCDR=$result->getCdrResponse()->isAccepted();
                $descripcionCDR=$result->getCdrResponse()->getDescription();
                $codeCDR=  $result->getCdrResponse()->getCode();
                if(intval($codeCDR)==0)
                {
                    $codigoAceptado=$codeCDR;
                    $estado = 'Aceptado';
                    $mensaje = 1;
                }
                else if(intval($codeCDR)>=100 &&  intval($codeCDR)<=1999)
                {
                    //$bandera = 0;
                    $codigoAceptado=$codeCDR;
                    $estado = 'Pendiente';
                    $mensaje = $descripcionCDR;
                }
                else if(intval($codeCDR)>=2000 &&  intval($codeCDR)<=3999)
                {
                    $codigoAceptado=$codeCDR;
                    $estado = 'Rechazo';
                    $mensaje = $descripcionCDR;
                }
                else if(intval($codeCDR) >= 4000)
                {
                    $codigoAceptado=$codeCDR;
                    $estado = 'Observado';
                    $mensaje = 1;
                }else{
                    $codigoAceptado=$codeCDR;
                    $estado = 'Pendiente';
                    $mensaje = "Error en el Sistema, vuelva a intentar";
                }

                if($estado == 'Rechazo'){
                    for($i=0; $i<count($itemasVentaSelect); $i++){
                        $productoSelect = $loadDatos->getProductoSelect($itemasVentaSelect[$i]->IdArticulo);
                        $stockSelect = $loadDatos->getProductoStockSelect($itemasVentaSelect[$i]->IdArticulo);
                        if($productoSelect->IdTipo == 1){
                            if($itemasVentaSelect[$i]->VerificaTipo != 1)
                            {
                                $newCantidad=$itemasVentaSelect[$i]->Cantidad*$itemasVentaSelect[$i]->CantidadReal;
                            }
                            else
                            {
                                $newCantidad=$itemasVentaSelect[$i]->Cantidad;
                            }

                            DB::table('articulo')
                                ->where('IdArticulo',$productoSelect->IdArticulo)
                                ->increment('Stock' , $newCantidad);

                            DB::table('stock')
                                ->where('IdStock', $stockSelect[0]->IdStock)
                                ->increment('Cantidad', $newCantidad);
                                
                            $fechaKardex = $loadDatos->getDateTime();
                                
                            $kardex=array(	 
                                'CodigoInterno'=>$productoSelect->CodigoInterno,
                                'fecha_movimiento'=>$fechaKardex,
                                'tipo_movimiento'=>17,
                                'usuario_movimiento'=>$idUsuario,
                                'documento_movimiento'=>$ventaSelect->Serie.'-'.$ventaSelect->Numero,
                                'existencia'=>$newCantidad,
                                'costo'=>floatval($itemasVentaSelect[$i]->PrecioUnidadReal),
                                'IdArticulo'=>$productoSelect->IdArticulo,
                                'IdSucursal'=>$idSucursal,
                                'Cantidad'=>floatval($itemasVentaSelect[$i]->Cantidad),
                                'Descuento'=>floatval($itemasVentaSelect[$i]->Descuento),
                                'ImporteEntrada'=>floatval($itemasVentaSelect[$i]->Importe),
                                'ImporteSalida'=>0,
                                'estado'=>1
                            );
                            DB::table('kardex')->insert($kardex);
                        }   
                    }
                }
                
                DB::table('ventas')
                        ->where('IdVentas', $idDoc)
                        ->update(['CodigoDoc' =>$codigoAceptado, 'Estado' => $estado, 'RutaXml' => $rutaXml, 'RutaCdr' => $rutaCdr]);
        
	    		return $mensaje;
	    		//return redirect()->to('/reportes/facturacion/facturas-pendientes')->with('status','Se envio Factura Electrónica a Sunat');
        
            } else {
                
                if($result->getError()->getCode() == 'HTTP' || $result->getError()->getCode() == 'HTTPS'){
                    $resumen = 'Servicio inestable, intentelo en otro momento';
                }else{
                    $resumen = 'Error '.$result->getError()->getCode().': '.$result->getError()->getMessage();
                    $codeError=  $result->getError()->getCode();
                    
                    if(intval($codeError) == 0)
    			    {
    			    	$estado = 'Aceptado';
    					
    			    }
    			    else if(intval($codeError) >=100 &&  intval($codeError) <=1999)
    			    {
    			    	$estado = 'Pendiente';
    			    }
    			    else if(intval($codeError) >=2000 && intval($codeError) <=3999)
    			    {
    			    	$estado = 'Rechazo';
    			    }
    			    else if(intval($codeError) >= 4000)
    			    {
    			    	$estado = 'Observado';
    			    }else{
    			        $estado = 'Pendiente';
    			    }
    			    
                    DB::table('ventas')
                        ->where('IdVentas', $idDoc)
                        ->update(['CodigoDoc' =>$codeError, 'Estado' => $estado, 'RutaXml' => $rutaXml]);
                }
                return $resumen;
                //return redirect('/reportes/facturacion/facturas-pendientes')->with('error',$resumen);
            }
		}
    }
    
    private function notas($req){
        $idDoc = $req->idDocEnvio;
        $loadDatos = new DatosController();
        $nota = $loadDatos->getNotaSelect($idDoc);
		$respuesta=array();
        if($nota->IdTipoNota == 1){
           $resp=$this->notaCredito($nota, $idDoc);
		}  
        return $resp;
        /*if($nota->IdTipoNota == 2){
            $resp=$this->notaDebito($nota, $idDoc);
        }*/
		/*if(is_numeric($resp))
        {
                /*if($resp==2)
             {
                 $respuesta=['status'=>2, 'mensaje'=>'Se envio Nota de Crédito a Sunat'];
             }
             else if($resp==3)
             {
                 $respuesta=['status'=>3, 'mensaje'=>'Se envio Nota de Débito a Sunat'];
             }
             return $resp;  //nota de credito
        }
        else
        {
            
                //return $respuesta=['status'=>0, 'mensaje'=>$resp];
        }*/
    }
    
    private function notaCredito($nota, $idDoc) {
        $config = new config();
        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $fecha = $nota->FechaCreacion;
        $date = DateTime::createFromFormat('Y-m-d H:i:s',$fecha);
        $opcionFactura = DB::table('usuario')
                              ->select('OpcionFactura')
                              ->where('IdUsuario', $idUsuario)
                              ->first();
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

		$cliente = $loadDatos->getClienteSelect($nota->IdCliente);
        
        $service = $this->getCdrStatusService($empresa->Ruc.''.$empresa->UsuarioSol, $empresa->ClaveSol);
        
		$arguments = [
            $fields['ruc']=$empresa->Ruc,
            $fields['tipo']='07',
            $fields['serie']=$nota->Serie,
            intval($fields['numero']=$nota->Numero)
    	];

		$res = $service->getStatusCdr(...$arguments); 
        //dd($res);
        if($res->getCdrZip())
		{
            $name='R-'.$empresa->Ruc.'-07-'.$nota->Serie.'-'.$nota->Numero.'.zip';
			$resumen=1;
			$banderaRechazo=0;
            $bandFactura=0;
			$cdr = $res->getCdrResponse();
			
			$descripcionCDR=$res->getCdrResponse()->getDescription();
			$codeCDR=  $res->getCdrResponse()->getCode();
            
			
			    if(intval($codeCDR) == 0)
			    {
					$codigoAceptado=$codeCDR;
			    	$estado = 'Aceptado';
			    	$mensaje = $descripcionCDR;
					$resumen=1;
					
			    }
			    else if(intval($codeCDR) >=100 &&  intval($codeCDR) <=1999)
			    {
					$banderaRechazo=1;
					$codigoAceptado=$codeCDR;
			    	$estado = 'Pendiente';
			    	$mensaje = $descripcionCDR;
					$resumen=$descripcionCDR;
			    }
			    else if(intval($codeCDR) >=2000 && intval($codeCDR) <=3999)
			    {
			    	$bandFactura = 1;
					$codigoAceptado=$codeCDR;
			    	$estado = 'Rechazo';
			    	$mensaje = $descripcionCDR;
					$resumen=$descripcionCDR;
			    }
			    else if(intval($codeCDR) >= 4000)
			    {
			    	$codigoAceptado=$codeCDR;
			    	$estado = 'Observado';
			    	$mensaje = $descripcionCDR;
					$resumen=1;
			    }else{
                    $banderaRechazo=1;
			        $codigoAceptado=$codeCDR;
			        $estado = 'Pendiente';
			        $resumen="Error en el Sistema, vuelva a intentar";
			    }
 			
			if($banderaRechazo==0)
			{
                $now = Carbon::now();
                $anio = $now->year;
                $mes = $now->month;
                $_mes = $loadDatos->getMes($mes);

                $rutaCdr = '/RespuestaSunat/'.$anio.'/'.$_mes.'/'.$rucEmpresa.'/NotasCreditoDebito/'.$name;
                Storage::disk('s3')->put($rutaCdr, $res->getCdrZip(), 'public');

				DB::table('nota_credito_debito')
                        ->where('IdCreditoDebito',$idDoc)
                        ->update(['CodigoDoc'=>$codigoAceptado, 'RutaCdr' => $rutaCdr, 'Estado' => $estado]);
				
                if($bandFactura == 1){
                    DB::table('ventas')
                        ->where('IdVentas',$nota->IdVentas)
                        ->update(['TipoNota' => null, 'Nota' => 0]);
                }
                //file_put_contents(public_path().'/RespuestaSunat/NotasCreditoDebito/'.$name, $res->getCdrZip());
                
			}else{
                DB::table('nota_credito_debito')
                        ->where('IdCreditoDebito',$idDoc)
                        ->update(['CodigoDoc'=>$codigoAceptado]);
            }
			
			return $resumen;
        }else{
            $loadDatos = new DatosController();
            if($nota->IdDocModificado == 2){
                $tipoDoc = '01';
            }
            if($nota->IdDocModificado == 1){
                $tipoDoc = '03';
            }

            if($nota->TipoVenta == 1){
                $opGravada = $nota->Subtotal;
                $opExonerada = '0.00';
            }else{
                $opGravada = '0.00';
                $opExonerada = $nota->Subtotal;
            }

            if($nota->IdTipoMoneda == 1){
                $totalDetrac = floatval($nota->Total);
            }else{
                $totalDetrac = floatval($nota->Total);
            }

            $totalGratuita = 0;
            if(floatval($nota->Gratuita) > 0){
                $totalGratuita = floatval($nota->Gratuita);
                if($nota->TipoVenta == 1){
                    $subTotalGratuita = floatval($totalGratuita / 1.18);
                    $igvGratuita = floatval($totalGratuita - $subTotalGratuita);
                }else{
                    $subTotalGratuita = floatval($totalGratuita);
                    $igvGratuita = '0.00';
                }
            }else{
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
            $client->setTipoDoc(strval($cliente->CodigoSunat))//agregado
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
            for($i=0; $i<count($items); $i++){
                $idProducto = substr($items[$i]->Cod,4);

                if($items[$i]->IdPaquetePromocional > 0){
                    $productoSelect = DB::table('paquetes_promocionales')
                                                ->where('IdPaquetePromocional', $items[$i]->IdPaquetePromocional)
                                                ->first();
                    $condicionDetrac = 1;
                    $medidaSunat = 'ZZ';
                    $descripcion = $productoSelect->NombrePaquete;
                }else{
                    $productoSelect = $loadDatos->getProductoSelect($items[$i]->IdArticulo);
                    if($productoSelect->IdTipo == 2){
                        $condicionDetrac = 1;
                    }
                    $medidaSunat = $productoSelect->MedidaSunat;
                    $descripcion = $productoSelect->Descripcion;
                }

                $precioUnitario = floatval($items[$i]->Total/$items[$i]->Cantidad);
                if($nota->TipoVenta == 1){
                    $subTotalPrecioUnitario = floatval($precioUnitario)/1.18;
                    $afectIgv = '10';
                    $porcentaje = 18;
                }else{
                    $subTotalPrecioUnitario=floatval($precioUnitario);
                    $afectIgv = '20';
                    $porcentaje = 0;
                }
                
                $igvItem = floatval($precioUnitario) - floatval($subTotalPrecioUnitario);
                $mtoValorVenta = floatval(intval($items[$i]->Cantidad) * $subTotalPrecioUnitario);// - floatval($items[$i]->Descuento);
                $igvTotal = floatval(intval($items[$i]->Cantidad) * $igvItem);
                $totalImpuesto = floatval($igvTotal);
                $valorGratuito = 0;
                if($items[$i]->Gratuito == 1){
                    $valorGratuito = floatval($subTotalPrecioUnitario);
                    $subTotalPrecioUnitario = 0;
                    $totalImpuesto = 0;
                    $countGratuita++;
                    if($nota->TipoVenta == 1){
                        $afectIgv = '11';
                    }else{
                        $afectIgv = '21';
                    }
                }

                if($idMotivo == 23){
                    $detail = new SaleDetail();
                    $detail->setCodProducto($items[$i]->Cod)
                    ->setUnidad($medidaSunat)
                    ->setCantidad($items[$i]->Cantidad)
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
                }else{
                
                    $detail = new SaleDetail();
                    $detail->setCodProducto($items[$i]->Cod)
                    ->setUnidad($medidaSunat)
                    ->setCantidad($items[$i]->Cantidad)
                    ->setDescripcion($descripcion)
                    ->setMtoBaseIgv(round($mtoValorVenta,5))
                    ->setPorcentajeIgv($porcentaje)
                    ->setIgv(round($igvTotal,5))
                    ->setTipAfeIgv($afectIgv)
                    ->setTotalImpuestos(round($totalImpuesto,5))
                    ->setMtoValorVenta(round($mtoValorVenta,5))
                    ->setMtoValorGratuito(round($valorGratuito, 5))
                    ->setMtoValorUnitario(round($subTotalPrecioUnitario,5))
                    ->setMtoPrecioUnitario(round($precioUnitario,5));
                    array_push($array, $detail);
                }
                usleep(100000);
            }

            if($idMotivo == 23){
                $idVenta = $nota->IdVentas;
                $ventaSelect = $loadDatos->getVentaselect($idVenta);
                $detallePagoCredito = DB::table('fecha_pago')
                                        ->where('IdVenta', $idVenta)
                                        ->first();

                $totalCredito = floatval($detallePagoCredito->Importe);
                $plazoNC = $ventaSelect->PlazoCredito;
    
                $fechaVenta = strtotime('+' . $plazoNC . ' day', strtotime($ventaSelect->FechaCreacion));
                $fechaVentaFinal = date('Y-m-d', $fechaVenta);
    
                $note->setFormaPago(new FormaPagoCredito(round($totalCredito,2)));
                $note->setCuotas([
                    (new Cuota())
                        ->setMonto(round($totalCredito,2))
                        ->setFechaPago(new DateTime($fechaVentaFinal))
                ]);
    
                /*DB::table('ventas')
                    ->where('IdVentas', $idVenta)
                    ->update(['PlazoCredito' => $plazoNC]);
    
                DB::table('fecha_pago')
                    ->where('IdVenta', $idVenta)
                    ->update(['FechaUltimo' => $fechaVentaFinal, 'Importe' => $totalCredito]);*/
            }
            
            /*if($nota->TipoComprobante == 2){
                if($nota->TipoPago == 1){
                    //$note->setFormaPago(new FormaPagoContado());
                }else{
                    if($condicionDetrac == 1 && floatval($totalDetrac) >= 700 && $nota->TipoVenta == 1){
                        $totalCredito = floatval($total) - floatval($total * $nota->PorcentajeDetraccion/100);
                    }else{
                        $totalCredito = floatval($total);
                        if($nota->Retencion == 1){
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
                    $_date = Carbon::parse($nota->FechaCreacion);
                    $fechaPago = $_date->addDays($nota->PlazoCredito);
    
                    $note->setFormaPago(new FormaPagoCredito(round($totalCredito,2)));
                    $note->setCuotas([
                        (new Cuota())
                            ->setMonto(round($totalCredito,2))
                            ->setFechaPago(new DateTime($fechaPago))
                    ]);
                }
            }*/
            
            $convertirLetras = new NumeroALetras();
            if($nota->IdTipoMoneda == 1){
                $importeLetras = $convertirLetras->convertir($total , 'soles');
            }
            else{
                $importeLetras = $convertirLetras->convertir($total , 'dolares');
            }
            
            $legend = new Legend();
            $legend->setCode('1000')
                ->setValue($importeLetras);

            array_push($legends, $legend);

            if($countGratuita > 0){
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
            $numero= $nota->Numero;
            $cod = $serie.'-'.$numero;
            $file = $rucEmpresa.'-07-'.$cod;

            $now = Carbon::now();
            $anio = $now->year;
            $mes = $now->month;
            $_mes = $loadDatos->getMes($mes);
            $nombreArchivo = $file;
            $rutaXml = '/RespuestaSunat/'.$anio.'/'.$_mes.'/'.$rucEmpresa.'/NotasCreditoDebito/'.$nombreArchivo.'.xml';

            //$ruta = public_path().'/RespuestaSunat/NotasCreditoDebito/'.$rucEmpresa.'/'.$file.'.xml';
            if(Storage::disk('s3')->exists($nota->RutaXml)){
                $ruta = Storage::disk('s3')->get($nota->RutaXml);
                file_put_contents($nombreArchivo.'.xml', $ruta);
                $res = $see->sendXml(get_class($note), $note->getName(), file_get_contents($nombreArchivo.'.xml'));
                if(unlink($nombreArchivo.'.xml')) {
                }
            }else{
                $xml_string = $see->getXmlSigned($note);
                $doc = new DOMDocument();
                $doc->loadXML($xml_string);
                $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                $config->writeXml($note, $see->getFactory()->getLastXml(), $rucEmpresa, $anio, $_mes, 2);
                //$config->writeXml($note, $see->getFactory()->getLastXml(), $empresa->Ruc, 2);
                $res = $see->send($note);
            }
            
    
            if ($res->isSuccess()) {
                $cdr = $res->getCdrResponse();
                $rutaCdr = '/RespuestaSunat/'.$anio.'/'.$_mes.'/'.$rucEmpresa.'/NotasCreditoDebito/R-'.$nombreArchivo.'.zip';
                $config->writeCdr($note, $res->getCdrZip(), $rucEmpresa, $anio, $_mes, 2);
                //$config->writeCdr($note, $res->getCdrZip(), $rucEmpresa, 2);
                $config->showResponse($note, $cdr);
                
                $descripcionCDR=$res->getCdrResponse()->getDescription();
                $codeCDR=  $res->getCdrResponse()->getCode();

                if(intval($codeCDR)==0)
                {
                    $codigoAceptado=$codeCDR;
                    $estado = 'Aceptado';
                    $mensaje = 1;
                }
                else if(intval($codeCDR)>=100 &&  intval($codeCDR)<=1999)
                {
                    $codigoAceptado=$codeCDR;
                    $estado = 'Pendiente';
                    $mensaje = $descripcionCDR;
                }
                else if(intval($codeCDR)>=2000 &&  intval($codeCDR)<=3999)
                {
                    $codigoAceptado=$codeCDR;
                    $estado = 'Rechazo';
                    $mensaje = $descripcionCDR;
                }
                else if(intval($codeCDR) >= 4000)
                {
                    $codigoAceptado=$codeCDR;
                    $estado = 'Observado';
                    $mensaje = 1;
                }else{
                    $codigoAceptado=$codeCDR;
                    $estado = 'Pendiente';
                    $mensaje = "Error en el Sistema, vuelva a intentar";
                }

                DB::table('nota_credito_debito')
                    ->where('IdCreditoDebito', $nota->IdCreditoDebito)
                    ->update(['CodigoDoc' =>$codigoAceptado, 'RutaXml' => $rutaXml, 'RutaCdr' => $rutaCdr, 'Estado' => $estado]);

        
	    		return $mensaje;

            } else {
                if($res->getError()->getCode() == 'HTTP' || $res->getError()->getCode() == 'HTTPS'){
                    $resumen = 'Servicio inestable, intentelo en otro momento';
                }else{
                    $resumen = 'Error '.$res->getError()->getCode().': '.$res->getError()->getMessage();
                    $codeError=  $res->getError()->getCode();
                    
                    if(intval($codeError) == 0)
    			    {
    			    	$estado = 'Aceptado';
    					
    			    }
    			    else if(intval($codeError) >=100 &&  intval($codeError) <=1999)
    			    {
    			    	$estado = 'Pendiente';
    			    }
    			    else if(intval($codeError) >=2000 && intval($codeError) <=3999)
    			    {
    			    	$estado = 'Rechazo';
    			    }
    			    else if(intval($codeError) >= 4000)
    			    {
    			    	$estado = 'Observado';
    			    }else{
    			        $estado = 'Pendiente';
    			    }
    			    
                    /*DB::table('ventas')
                        ->where('IdVentas', $idDoc)
                        ->update(['CodigoDoc' =>$codeError, 'RutaXml' => $rutaXml, 'Estado' => $estado]);*/
                }
                
                return $resumen;
            }
        }
    }

    public function getItemsPaquetePromocional($idPaquete)
    {
        $datos = DB::table('articulo_paquetePromocional AS dap')
            ->join('articulo', 'dap.IdArticulo', '=', 'articulo.IdArticulo')
            ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
            ->select('articulo.IdArticulo', 'articulo.Descripcion AS NombreArticulo', 'articulo.IdMarca', 'articulo.Precio', 'articulo.Stock', 'articulo.Codigo AS codigoBarra', 'articulo.IdTipoMoneda AS idTipoMonedaItems', 'articulo.IdCategoria', 'unidad_medida.Nombre as UM', 'unidad_medida.IdUnidadMedida', 'articulo.IdTipo AS idTipoItems', 'articulo.Costo', 'dap.IdPaquetePromocional', 'dap.cantidad', 'dap.CodigoArticulo')
            ->where('IdPaquetePromocional', $idPaquete)
            ->where('articulo.Estado', 'E')
            ->get();
        return $datos;
    }
    
    private function notaDebito($nota, $idDoc) {
       
        $config = new config();
        $idUsuario = Session::get('idUsuario');
        $opcionFactura = DB::table('usuario')
                              ->select('OpcionFactura')
                              ->where('IdUsuario', $idUsuario)
                              ->first();
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
		/* $config = new config();
        $see = $config->configuracion(SunatEndpoints::FE_BETA); */
        //$see = $config->configuracion(SunatEndpoints::FE_PRODUCCION);
		//dd($nota);
        $loadDatos = new DatosController();
        if($nota->IdDocModificado == 2){
            $tipoDoc = '01';
        }
        if($nota->IdDocModificado == 1){
            $tipoDoc = '03';
        }
        $idMotivo = $nota->IdMotivo;
        $selectMotivo = $loadDatos->getSelectMotivo($idMotivo, 'd');
       // $total = floatval($nota->Total) - floatval($nota->Descuento);
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
            //    ->setDireccion($sucursal->Direccion);

        $company = new Company();
        $company->setRuc($empresa->Ruc)
            ->setRazonSocial($empresa->Nombre)
            ->setNombreComercial('NONE')
            ->setAddress($address);
        
        $cliente = $loadDatos->getClienteSelect($nota->IdCliente);
    
        $client = new Client();
        $client->setTipoDoc(strval($cliente->CodigoSunat))//agregado
            ->setNumDoc($cliente->NumeroDocumento)
            ->setRznSocial($cliente->Nombre);
        
        $note = new Note();
        $note->setUblVersion('2.1')
            ->setTipDocAfectado($tipoDoc)
            ->setNumDocfectado($nota->DocModificado)
            ->setCodMotivo($selectMotivo->CodigoSunat)
            ->setDesMotivo($selectMotivo->Descripcion)
            ->setTipoDoc('08')
            ->setSerie($nota->Serie)
            ->setFechaEmision(new DateTime())
            ->setCorrelativo($nota->Numero)
            ->setTipoMoneda('PEN')
            ->setCompany($company)
            ->setClient($client)
            ->setMtoOperGravadas(floatval($nota->Subtotal))
            ->setMtoIGV(floatval($nota->IGV))
            ->setTotalImpuestos(floatval($nota->IGV))
            ->setMtoImpVenta($total);
        
        $items = $loadDatos->getItemsNotas($nota->IdCreditoDebito);
        
        $array = [];
        for($i=0; $i<count($items); $i++){
            $idProducto = substr($items[$i]->Cod,4);
            $productoSelect = $loadDatos->getProductoSelect($idProducto);

			$precioUnitario = floatval($items[$i]->Total/$items[$i]->Cantidad);
            $subTotalPrecioUnitario = floatval($precioUnitario)/1.18;
            $subTotal = floatval($items[$i]->Total/1.18);
            $igvItem = floatval((floatval($items[$i]->Total) - floatval($subTotal))/ intval($items[$i]->Cantidad));
            $mtoValorVenta = floatval(intval($items[$i]->Cantidad) * $subTotalPrecioUnitario);// - floatval($items[$i]->Descuento);
            $igvTotal = floatval(intval($items[$i]->Cantidad) * $igvItem);
            
            $detail = new SaleDetail();
            $detail->setCodProducto($items[$i]->Cod)
            ->setUnidad($productoSelect->MedidaSunat)
            ->setCantidad($items[$i]->Cantidad)
            ->setDescripcion($productoSelect->Descripcion)
            ->setMtoBaseIgv($mtoValorVenta)
            ->setDescuentos($items[$i]->Descuento)
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
        $importeLetras = $convertirLetras->convertir($total , 'soles');
        
        $legend = new Legend();
        $legend->setCode('1000')
            ->setValue($importeLetras);
        $note->setDetails($array)
            ->setLegends([$legend]);
        
        $rucEmpresa = $empresa->Ruc;
        $serie = $nota->Serie;
        $numero= $nota->Numero;
        $cod = $serie.'-'.$numero;
        $file = $rucEmpresa.'-08-'.$cod;
        
       $ruta = public_path().'/RespuestaSunat/NotasCreditoDebito/'.$rucEmpresa.'/'.$file.'.xml';
        $res = $see->sendXml(get_class($note), $note->getName(), file_get_contents($ruta));
        // Envio a SUNAT.
        //$see = $util->getSee(SunatEndpoints::FE_BETA);
        //$xml_string = $see->getXmlSigned($note);
        //$config->writeXml($note, $see->getFactory()->getLastXml(), 2);
        if ($res->isSuccess()) {
            $cdr = $res->getCdrResponse();
            $config->writeCdr($note, $res->getCdrZip(), $rucEmpresa, 2);
            $config->showResponse($note, $cdr);
            
            DB::table('nota_credito_debito')
                    ->where('IdCreditoDebito', $nota->IdCreditoDebito)
                    ->update(['Estado' => 'Aceptado']);
            
            return 3;
			//return redirect('/reportes/facturacion/facturas-pendientes')->with('status','Se envio Nota de Débito a Sunat');

        } else {
            if($res->getError()->getCode() == 'HTTP'){
                $resumen = 'Servicio inestable, intentelo en otro momento';
            }else{
                $resumen = 'Error '.$res->getError()->getCode().': '.$res->getError()->getMessage();
            }
            
            return $resumen; //redirect('/reportes/facturacion/facturas-pendientes')->with('error',$resumen);
        }
    }
	
	private function getCdrStatusService($user, $password)
	{
    	$ws = new SoapClient(SunatEndpoints::FE_CONSULTA_CDR.'?wsdl');
    	//$ws = new SoapClient('https://e-factura.sunat.gob.pe/ol-it-wsconscpegem/billConsultService?wsdl');
    	//$ws = new SoapClient('https://e-factura.sunat.gob.pe/ol-it-wsconsvalidcpe/billValidService?wsdl');

    	$ws->setCredentials($user, $password);

    	$service = new ConsultCdrService();
    	$service->setClient($ws);

    	return $service;
	}

    public function verFacturasPendientes(Request $req){
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
			$permisos = $loadDatos->getPermisos($idUsuario);
			
			$subpermisos=$loadDatos->getSubPermisos($idUsuario);
			$subniveles=$loadDatos->getSubNiveles($idUsuario);
			
			$facturas = $this->facturasPendientes();
            // dd($facturas);
			$usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
			$modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
			$array = ['permisos' => $permisos, 'facturas' => $facturas, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
			return view('reportes/facturacion/verFacturasPendientesAdmin', $array);
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        
    }

    // private function facturasPendientes(){
    //     try{
    //         $facturas = DB::select('(select ventas.IdVentas as IdDoc, 1 as tipo, ventas.FechaCreacion, tipo_comprobante.Descripcion as Comprobante, usuario.Nombre as Usuario, sucursal.Nombre as Sucursal, cliente.NumeroDocumento as NroDoc, Serie, Numero, Total, CodigoDoc, ventas.Estado
	// 						          from ventas inner join cliente on ventas.IdCliente = cliente.IdCliente
    //                                   inner join usuario on ventas.IdCreacion = usuario.IdUsuario
    //                                   inner join sucursal on ventas.IdSucursal = sucursal.IdSucursal
	// 								  inner join tipo_comprobante on ventas.IdTipoComprobante = tipo_comprobante.IdTipoComprobante
	// 								  inner join tipo_documento on tipo_documento.IdTipoDocumento = cliente.IdTipoDocumento
	// 								  where ventas.IdTipoComprobante = 2 and ventas.Estado = "Pendiente" and usuario.Estado = "E" and ventas.FechaCreacion > "2021-08-01" or ventas.Estado = "Aceptado" and ventas.FechaCreacion > "2021-08-01" and  CodigoDoc like "%env%") union
    //                     			(select nota_credito_debito.IdCreditoDebito as IdDoc, 2 as tipo, nota_credito_debito.FechaCreacion, tipo_nota.Descripcion as Comprobante, usuario.Nombre as Usuario, sucursal.Nombre as Sucursal, cliente.NumeroDocumento as NroDoc, Serie, Numero, Total, CodigoDoc, nota_credito_debito.Estado
	// 								  from nota_credito_debito inner join cliente on nota_credito_debito.IdCliente = cliente.IdCliente
    //                                   inner join usuario on nota_credito_debito.IdUsuarioCreacion = usuario.IdUsuario
    //                                   inner join sucursal on nota_credito_debito.IdSucursal = sucursal.IdSucursal
	// 								  inner join tipo_nota on nota_credito_debito.IdTipoNota = tipo_nota.IdTipoNota
	// 								  inner join tipo_documento on tipo_documento.IdTipoDocumento = cliente.IdTipoDocumento
	// 								  where nota_credito_debito.IdDocModificado = 2 and nota_credito_debito.Estado = "Pendiente" and usuario.Estado = "E" and nota_credito_debito.FechaCreacion > "2021-08-01") order by FechaCreacion desc');
    //         return $facturas;
    //     } catch (Exception $ex) {
    //         echo $ex->getMessage();
    //     }
    // }

    private function facturasPendientes(){
        try{
            $facturas = DB::select('(select ventas.IdVentas as IdDoc, 1 as tipo, ventas.FechaCreacion, tipo_comprobante.Descripcion as Comprobante, usuario.Nombre as Usuario, sucursal.Nombre as Sucursal, cliente.NumeroDocumento as NroDoc, Serie, Numero, Total, CodigoDoc, ventas.Estado
							          from ventas inner join cliente on ventas.IdCliente = cliente.IdCliente
                                      inner join usuario on ventas.IdCreacion = usuario.IdUsuario
                                      inner join sucursal on ventas.IdSucursal = sucursal.IdSucursal
									  inner join tipo_comprobante on ventas.IdTipoComprobante = tipo_comprobante.IdTipoComprobante
									  inner join tipo_documento on tipo_documento.IdTipoDocumento = cliente.IdTipoDocumento
									  where ventas.IdTipoComprobante = 2 and ventas.Estado = "Pendiente" and usuario.Estado = "E" and ventas.FechaCreacion > "2022-11-01" or ventas.FechaCreacion > "2022-11-01" and (CodigoDoc like "%env%" or CodigoDoc like "%CDR%")) union
                        			(select nota_credito_debito.IdCreditoDebito as IdDoc, 2 as tipo, nota_credito_debito.FechaCreacion, tipo_nota.Descripcion as Comprobante, usuario.Nombre as Usuario, sucursal.Nombre as Sucursal, cliente.NumeroDocumento as NroDoc, Serie, Numero, Total, CodigoDoc, nota_credito_debito.Estado
									  from nota_credito_debito inner join cliente on nota_credito_debito.IdCliente = cliente.IdCliente
                                      inner join usuario on nota_credito_debito.IdUsuarioCreacion = usuario.IdUsuario
                                      inner join sucursal on nota_credito_debito.IdSucursal = sucursal.IdSucursal
									  inner join tipo_nota on nota_credito_debito.IdTipoNota = tipo_nota.IdTipoNota
									  inner join tipo_documento on tipo_documento.IdTipoDocumento = cliente.IdTipoDocumento
									  where nota_credito_debito.IdDocModificado = 2 and nota_credito_debito.Estado = "Pendiente" and usuario.Estado = "E" and nota_credito_debito.FechaCreacion > "2022-11-01" or nota_credito_debito.FechaCreacion > "2022-11-01" and (CodigoDoc like "%env%" or CodigoDoc like "%CDR%")) order by FechaCreacion desc');
            return $facturas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }


    // Nueva Funcion
    public function updateEstadoDocumento(Request $req){
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario'); 
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        $estado = $req->estado;
        $codigo = $req->codigo;
        $tipoDocumento = $req->tipoDocumento;
        if (!empty($codigo)) {
            for ($i=0; $i < count($codigo); $i++) { 
                $datos = [
                    'Estado' => $estado[$i]
                ];
                if ($tipoDocumento[$i] == 1) {
                    DB::table('ventas')
                    ->where('IdVentas', $codigo[$i])
                    ->update($datos);
                }
                if($tipoDocumento[$i] == 2){
                    DB::table('nota_credito_debito')
                    ->where('IdCreditoDebito', $codigo[$i])
                    ->update($datos);
                }
            }            
        }
        return redirect('/reportes/facturacion/ver-facturas-pendientes')->with('succes', ' El cambio de estado fue satisfactorio..');
    }
}
