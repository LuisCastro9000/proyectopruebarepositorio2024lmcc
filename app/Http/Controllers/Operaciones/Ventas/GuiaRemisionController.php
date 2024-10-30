<?php

namespace App\Http\Controllers\Operaciones\Ventas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use App\Http\Controllers\Servicios\config;
use App\Http\Controllers\Operaciones\NumeroALetras;
use DateTime;
use Session;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\Document;
use Greenter\Model\Despatch\AdditionalDoc;
use Greenter\Model\Despatch\Transportist;
use Greenter\Model\Despatch\Shipment;
use Greenter\Model\Despatch\Despatch;
use Greenter\Model\Despatch\DespatchDetail;
use Greenter\Model\Despatch\Direction;
use Greenter\Ws\Services\SunatEndpoints;
use DOMDocument;
use DB;
use Carbon\Carbon;
use PhpZip\ZipFile;
use PhpZip\Constants\ZipCompressionMethod;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;

class GuiaRemisionController extends Controller
{
    public function index(Request $req) {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        $idSucursal = Session::get('idSucursal');
        $loadDatos = new DatosController();
        $permisos = $loadDatos->getPermisos($idUsuario);
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
				
        $motivosGuias = $loadDatos->getMotivos('gv', 0);
        $ventaSelect = [];
        $items = [];
        $codComprobante = '';
        $idTipoComprobante = '';
        $cliente = '';
        $idCliente = '';
        $idVentas = '';
		$dirCliente = '';
        $nroDocumento = '';
		$dirEmpresa = '';
        $date = Carbon::today();
        $dateAtras = $date->subMonth(3)->firstOfMonth();
        $reportesVentasAceptados= $loadDatos->getVentasAceptadasGuias($idSucursal, $dateAtras);
        $clientes = $loadDatos->getTipoClientes(3,$idSucursal);
        $sucursal = $loadDatos->getSucursalSelect($idSucursal);
        $guiasRemision = $loadDatos->getGuiasRemision($idSucursal);
        $tiposDoc = $loadDatos->TipoDocumento();
        $totalGuias = $loadDatos->getTotalGuias($idSucursal);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $departamentos = $loadDatos->getDepartamentos();
        $provincias = $loadDatos->getProvincias($sucursal->IdDepartamento);
        $distritos = $loadDatos->getDistritos($sucursal->IdProvincia);
        $provincias2 = [];
        $distritos2 = [];
        $selectOpcion = 1;
        $productos = $loadDatos->getAllProductosPagination($idSucursal, "");
        $sucursales = $loadDatos->getSucursalesRestantes($idSucursal, $usuarioSelect->CodigoCliente);
        $array = ['permisos' => $permisos, 'reportesVentasAceptados' => $reportesVentasAceptados, 'clientes' => $clientes, 'codComprobante' => $codComprobante, 'idTipoComprobante' => $idTipoComprobante, 'cliente' => $cliente, 'dirCliente' => $dirCliente, 'idCliente' => $idCliente, 'idSucursal' => $idSucursal, 'dirEmpresa' => $dirEmpresa, 'modulosSelect' => $modulosSelect, 'sucursal' => $sucursal, 'provincias' => $provincias, 'distritos' => $distritos, 'productos' => $productos,
            'sucursales' => $sucursales, 'ventaSelect' => $ventaSelect, 'provincias2' => $provincias2, 'distritos2'=> $distritos2, 'items' => $items, 'motivosGuias' => $motivosGuias, 'guiasRemision' => $guiasRemision, 'tiposDoc' => $tiposDoc, 'nroDocumento' => $nroDocumento, 'selectOpcion' => $selectOpcion, 'idVentas' => $idVentas, 'totalGuias' => $totalGuias, 'departamentos' => $departamentos, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
        return view('operaciones/ventas/guiaRemision/guiaRemision', $array);
    }
    
    public function selectVentaAceptada(Request $req){
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
            $loadDatos = new DatosController();
            $id = $req->id;
            $option = $req->option;
            
            $date = Carbon::today();
            $dateAtras = $date->subMonth(3)->firstOfMonth();
            $reportesVentasAceptados= $loadDatos->getVentasAceptadasGuias($idSucursal, $dateAtras);
            $clientes = $loadDatos->getTipoClientes(3,$idSucursal);
            $tiposDoc = $loadDatos->TipoDocumento();
            if($option == 1){
                $ventaSelect = $loadDatos->getVentaselect($id);
                $items = $loadDatos->getItemsVentas($id);
                $codComprobante = $ventaSelect->Serie.'-'.$ventaSelect->Numero;
                $idTipoComprobante = $ventaSelect->IdTipoComprobante;
                $cliente = $ventaSelect->Nombres;
                $idCliente = $ventaSelect->IdCliente;
                $dirCliente = $ventaSelect->DirCliente;
                $nroDocumento = $ventaSelect->NumeroDocumento;
                $idVentas = $ventaSelect->IdVentas;
                $idDepartamento = $ventaSelect->IdDepartamento;
                $idProvincia = $ventaSelect->IdProvincia;
                $idDistrito = $ventaSelect->IdDistrito;
                $provincias2 = $loadDatos->getProvincias($idDepartamento);
                $distritos2 = $loadDatos->getDistritos($idProvincia);
            }else{
                $_cliente = $loadDatos->getClienteSelect($id);
                $items = [];
                $codComprobante = '-';
                $idTipoComprobante = '';
                $cliente = $_cliente->Nombre;
                $idCliente = $_cliente->IdCliente;
                $dirCliente = $_cliente->Direccion;
                $nroDocumento = $_cliente->NumeroDocumento;
                $idDepartamento = $_cliente->IdDepartamento;
                $idProvincia = $_cliente->IdProvincia;
                $idDistrito = $_cliente->IdDistrito;
                $provincias2 = $loadDatos->getProvincias($idDepartamento);
                $distritos2 = $loadDatos->getDistritos($idProvincia);
                $idVentas = 0;
            }
            
            $array = ['reportesVentasAceptados' => $reportesVentasAceptados, 'clientes' => $clientes, 'codComprobante' => $codComprobante, 'idTipoComprobante' => $idTipoComprobante, 'cliente' => $cliente, 'idCliente' => $idCliente, 'dirCliente' => $dirCliente, 'nroDocumento' => $nroDocumento, 'idSucursal' => $idSucursal,
                'provincias2' => $provincias2, 'distritos2'=> $distritos2, 'idDepartamento' => $idDepartamento, 'idProvincia' => $idProvincia, 'idDistrito' => $idDistrito, 'items' => $items, 'tiposDoc' => $tiposDoc, 'selectOpcion' => $option, 'idVentas' => $idVentas];
            return Response($array);
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
    }

    public function store(Request $req) {
       //dd($req);
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }

        if($req->modoTraslado == '01'){
            $this->validateGuiaPublico($req);
        }else{
            $this->validateGuiaPrivado($req);
        }
        
        $idDep = $req->departamento;
        $idPro = $req->provincia;
        $idDis = $req->distrito;
        $idDep2 = $req->departamento2;
        $idPro2 = $req->provincia2;
        $idDis2 = $req->distrito2;
        if($idDep == 0){
            return back()->with('error', 'Selecciona un departamento para Origen')->withInput();
        }
        if($idPro == 0){
            return back()->with('error', 'Selecciona una provincia para Origen')->withInput();
        }
        if($idDis == 0){
            return back()->with('error', 'Selecciona un distrito para Origen')->withInput();
        }
        if($idDep2 == 0){
            return back()->with('error', 'Selecciona un departamento para Llegada')->withInput();
        }
        if($idPro2 == 0){
            return back()->with('error', 'Selecciona una provincia para Llegada')->withInput();
        }
        if($idDis2 == 0){
            return back()->with('error', 'Selecciona un distrito para Llegada')->withInput();
        }
        if($req->option == 2){
            if($req->Codigo == null){
                return back()->with('error', 'Por favor agrege productos')->withInput();
            }
        }
        $razonSocialEmpresa = $req->razonSocialEmpresa;
        $idSucursal = Session::get('idSucursal');
        $idCliente = $req->idCliente;
        $idMotivo = $req->motivo;
        $idVenta= $req->idVentas;
        $codComprobante = $req->codComprobante;
        $fecha1 = $req->fechaEmitida;
        //dd($fecha1);
        $date1 = Carbon::createFromFormat('d/m/Y', $fecha1);
        //$date1 = DateTime::createFromFormat('Y-m-d',$fecha1);
        
        $fechaEmitidaConvertida = $date1->format("Y-m-d H:i:s");
        
        $loadDatos = new DatosController();
        $fecha2 = $req->fechaTraslado;
        $date2 = Carbon::createFromFormat('d/m/Y', $fecha2);
        $fechaTrasladoConvertida = $date2->format("Y-m-d H:i:s");
        $serie = $req->serie;
        $numero = $req->numero;
        $numero = $this->completarCeros($numero);
        $origen = $req->origen;
        $destino = $req->destino;
        $modoTraslado = $req->modoTraslado;
        $bultos = $req->bultos;
        $peso = $req->peso;

        $verificar = $this->verificarCodigo($serie, $numero, $idSucursal);
        //dd($verificar);
        if ($verificar != null) {
            //$ultimoCorrelativo = $this->ultimoCorrelativo($idUsuario, $idSucursal, $idTipoComp);
            //dd($verificar);
            $sumarCorrelativo = intval($verificar->Numero) + 1;
            $numero = $this->completarCeros($sumarCorrelativo);
            
        }

        if($idMotivo == 16){
            $idCliente = 2681;
            $idVenta = 0;
            $codComprobante = '-';
        }

        //$razonSocialTransp = $req->razonSocialTransp;
        
		$tipDocEmpresa = $req->tipDocEmpresa;
        $rucEmpresa = $req->rucEmpresa;
		
		//transportista ------------
        $transportista = $req->transportista;
		$tipDocTransp = $req->tipDocTransp;
		$docTransp = $req->docTransp;
		//---------------------------
        $placa = $req->placa;
        
        $observacion = $req->observacion;
        $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
		
        
        $now = new DateTime();
		$fechaConvertida = $now->format("Y-m-d H:i:s");
		//dd($req);
        if($caja == null){
            echo "<script language='javascript'>alert('Abrir Caja antes de realizar una venta');window.location='../../caja/cierre-caja'</script>";
        }else{
			$bandera = 1;
			$opcSave = 1;
            
            $res = $this->obtenerXMLGuiaRemision($req);
			
            if(is_numeric($res[0])){
                return back()->with("error", "La respuesta de Sunat: ".$res[1]." - ".$res[2])->withInput();
            }else{
                $hash = $res[0];
                $resumen = $res[1];
                $rutaXml = $res[5];
                $rutaCdr = $res[6];
                $numTicket = $res[7];
                if($res[2] == 0){
                    $bandera = 0;
                    $mensaje = 'Error : '.$res[3].'  -  '.$res[4];
                    if(intval($res[3])>=2000 &&  intval($res[3])<=3999)
                    {
                        $estado = "Rechazo";
                        $codigoAceptado = $res[3];
                        $array = ['IdUsuario' => $idUsuario, 'IdCliente' => $idCliente, 'IdSucursal' => $idSucursal, 'IdMotivo' => $idMotivo, 'IdVentas'=>$idVenta, 'DocumentoVenta'=>$codComprobante, 'FechaCreacion' => $fechaConvertida, 'FechaEmision' => $fechaEmitidaConvertida, 'FechaTraslado' => $fechaTrasladoConvertida, 'Serie' => $serie, 'Numero' => $numero, 
                            'Origen' => $origen, 'Destino' => $destino, 'ModoTraslado' => $modoTraslado, 'Peso' => $peso, 'Bultos' => $bultos, 'DistritoOrigen' => $idDis, 'DistritoDestino' =>  $idDis2, 'Transportista' => $transportista, 'RazonSocialTransp' => $razonSocialEmpresa, 'tipoDocEmpresa'=>$tipDocEmpresa, 'RucTransp' => $rucEmpresa, 'PlacaVehicular' => $placa, 
                            'IdTipoDocumento' => $tipDocTransp, 'NumeroDocumento' => $docTransp, 'Observacion' => $observacion, 'NumTicket' => $numTicket, 'Resumen' => $resumen, 'Hash' => $hash, 'codigoError'=>$codigoAceptado, 'RutaXml' => $rutaXml, 'RutaCdr' => $rutaCdr, 'Estado' => $estado];
                    
                        DB::table('guia_remision')->insert($array);
                        
                        $guia = DB::table('guia_remision')
                                            ->orderBy('IdGuiaRemision','desc')
                                            ->first();
                        $idGuiaRemision = $guia->IdGuiaRemision;
                        
                        if($req->option == 1){
                            
                            $items = $loadDatos->getItemsVentas($req->idVentas);
                            
                            for($i=0; $i<count($items); $i++){
                                if($items[$i]->VerificaTipo  ==  2 )
                                {
                                    $unidad=DB::table('articulo as a')
                                        ->join('unidad_medida as um','a.IdTipoUnidad', '=', 'um.IdUnidadMedida')
                                        ->select('um.*')
                                        ->where('a.IdTipoUnidad', $items[$i]->IdTipoUnidad)
                                        ->first();
                                    
                                    $codUnidad= $unidad->Descripcion;
                                }
                                else
                                {
                                    $codUnidad= $items[$i]->CodSunatMedida;
                                }
                                
                                $arrayRelacion = ['IdGuiaRemision' => $idGuiaRemision, 'IdArticulo' => $items[$i]->IdArticulo, 'Codigo' => $items[$i]->Cod, 'Cantidad' => $items[$i]->Cantidad, 'codUnidad'=>$codUnidad, 'TextUnidad'=>$items[$i]->TextUnidad];
                                DB::table('guia_detalle')->insert($arrayRelacion);
                            }
                            
                            if($opcSave == 1){
                                DB::table('ventas')
                                        ->where('IdVentas',$req->idVentas)
                                        ->update(['Guia' => 1]); 
                            }
                        }else{
                            
                            
                            for($i=0; $i<count($req->Codigo); $i++){
                                $id = substr($req->Codigo[$i], 4);
                                $producto = $loadDatos->getProductoSelect($id);
                                if($producto->IdTipo == 1){
                                    $textUnidad = 'Unidad';
                                }else{
                                    $textUnidad = 'ZZ';
                                }
                                $arrayRelacion = ['IdGuiaRemision' => $idGuiaRemision, 'IdArticulo' => $id, 'Codigo' => $req->Codigo[$i], 'Cantidad' => $req->Cantidad[$i], 'CodUnidad'=>$producto->MedidaSunat, 'TextUnidad'=>$textUnidad];
                                DB::table('guia_detalle')->insert($arrayRelacion);
                            }
                        }
                    }
                }else{
                
                    if($res[2] == 1){  //es  enviado y recibido......
                                            
                        if(intval($res[3]) == 0)
                        {
                            $codigoAceptado=$res[3];
                            $estado = 'Aceptado';
                            $mensaje = $res[4];
                        }
                        else if(intval($res[3])>=100 &&  intval($res[3])<=1999)
                        {
                            $bandera = 0;
                            $codigoAceptado=$res[3];
                            $estado = 'Pendiente';
                            $mensaje = $res[3].' - '.$res[4];
                        }
                        else if(intval($res[3])>=2000 &&  intval($res[3])<=3999)
                        {
                            $bandera = 0;
                            $opcSave = 0;
                            $codigoAceptado=$res[3];
                            $estado = 'Rechazo';
                            $mensaje = 'La GRE fue Rechazado, '.$res[4];
                        }
                        else if(intval($res[3])>=4000)
                        {
                            $codigoAceptado=$res[3];
                            $estado = 'Observado';
                            $mensaje = $res[4];//'La Factura '.$serie.'-'.$numero.', Ha sido Aceptado';
                        }else{
                            $codigoAceptado=$res[3];
                            $estado = 'Pendiente';
                            $mensaje = 'Se generó Guía de Remisión pero no se pudo enviar a Sunat ';
                        }
                    }
                    if($res[2] == 2){
                        $estado = 'Pendiente';
                        $codigoAceptado=$res[3];
                        $mensaje = 'Se genero Guía de Remisión pero no se pudo enviar a Sunat';
                    }
                    
                    //if($bandera == 1){
                        $array = ['IdUsuario' => $idUsuario, 'IdCliente' => $idCliente, 'IdSucursal' => $idSucursal, 'IdMotivo' => $idMotivo, 'IdVentas'=>$idVenta, 'DocumentoVenta'=>$codComprobante, 'FechaCreacion' => $fechaConvertida, 'FechaEmision' => $fechaEmitidaConvertida, 'FechaTraslado' => $fechaTrasladoConvertida, 'Serie' => $serie, 'Numero' => $numero, 
                            'Origen' => $origen, 'Destino' => $destino, 'ModoTraslado' => $modoTraslado, 'Peso' => $peso, 'Bultos' => $bultos, 'DistritoOrigen' => $req->distrito, 'DistritoDestino' => $req->distrito2, 'Transportista' => $transportista, 'RazonSocialTransp' => $razonSocialEmpresa, 'tipoDocEmpresa'=>$tipDocEmpresa, 'RucTransp' => $rucEmpresa, 'PlacaVehicular' => $placa, 
                            'IdTipoDocumento' => $tipDocTransp, 'NumeroDocumento' => $docTransp, 'Observacion' => $observacion, 'NumTicket' => $numTicket, 'Resumen' => $resumen, 'Hash' => $hash, 'codigoError'=>$codigoAceptado, 'RutaXml' => $rutaXml, 'RutaCdr' => $rutaCdr, 'Estado' => $estado];
                    
                        DB::table('guia_remision')->insert($array);
                        
                        $guia = DB::table('guia_remision')
                                            ->orderBy('IdGuiaRemision','desc')
                                            ->first();
                        $idGuiaRemision = $guia->IdGuiaRemision;
                        
                        if($req->option == 1){
                            $items = $loadDatos->getItemsVentas($req->idVentas);
                            
                            for($i=0; $i<count($items); $i++){
                                if($items[$i]->VerificaTipo  ==  2 )
                                {
                                    $unidad=DB::table('articulo as a')
                                        ->join('unidad_medida as um','a.IdTipoUnidad', '=', 'um.IdUnidadMedida')
                                        ->select('um.*')
                                        ->where('a.IdTipoUnidad', $items[$i]->IdTipoUnidad)
                                        ->first();
                                    
                                    $codUnidad= $unidad->Descripcion;
                                }
                                else
                                {
                                    $codUnidad= $items[$i]->CodSunatMedida;
                                }
                                
                                $arrayRelacion = ['IdGuiaRemision' => $idGuiaRemision, 'IdArticulo' => $items[$i]->IdArticulo, 'Codigo' => $items[$i]->Cod, 'Cantidad' => $items[$i]->Cantidad, 'codUnidad'=>$codUnidad, 'TextUnidad'=>$items[$i]->TextUnidad];
                                DB::table('guia_detalle')->insert($arrayRelacion);
                            }
                            
                            if($opcSave == 1){
                                DB::table('ventas')
                                        ->where('IdVentas',$req->idVentas)
                                        ->update(['Guia' => 1]); 
                            }
                        }else{
                            for($i=0; $i<count($req->Codigo); $i++){
                                $id = substr($req->Codigo[$i], 4);
                                $producto = $loadDatos->getProductoSelect($id);
                                if($producto->IdTipo == 1){
                                    $textUnidad = 'Unidad';
                                }else{
                                    $textUnidad = 'ZZ';
                                }
                                $arrayRelacion = ['IdGuiaRemision' => $idGuiaRemision, 'IdArticulo' => $id, 'Codigo' => $req->Codigo[$i], 'Cantidad' => floatval($req->Cantidad[$i]), 'CodUnidad'=>$producto->MedidaSunat, 'TextUnidad'=>$textUnidad];
                                DB::table('guia_detalle')->insert($arrayRelacion);
                            }
                        }
                    //}
                }
                
                return redirect('consultas/guias-remision/detalles/'.$idGuiaRemision)
                            ->with('status', $mensaje);
            }
        }
    }
    
    private function completarCeros($numero){
        $numeroConCeros = str_pad($numero, 8, "0", STR_PAD_LEFT);
        return $numeroConCeros;
    }
    
    public function consultarProvincias(Request $req) {
        if($req->ajax()){
            $idDep = $req->departamento;
            $loadDatos = new DatosController();
            $provincias = $loadDatos->getProvincias($idDep);
            return Response($provincias);
        }
    }
    
    public function consultarDistritos(Request $req) {
        if($req->ajax()){
            $idPro = $req->provincia;
            $loadDatos = new DatosController();
            $distritos = $loadDatos->getDistritos($idPro);
            return Response($distritos);
        }
    }
    
    public function obtenerXMLGuiaRemision($req) {
    
		$idUsuario = Session::get('idUsuario');
		$idSucursal = Session::get('idSucursal');

		$opcionFactura = DB::table('usuario')
                        ->select('OpcionFactura')
                        ->where('IdUsuario', $idUsuario)
                        ->first();
		
		$config = new config();
		
		if($opcionFactura->OpcionFactura  > 0)
		{
			if($opcionFactura->OpcionFactura == 1) //sunat
			{
				//$see = $config->configuracion(SunatEndpoints::FE_BETA);
				$see = $config->configuracion(SunatEndpoints::GUIA_BETA);
			}
			else if($opcionFactura->OpcionFactura == 2) //ose
			{
				$see = $config->configuracion('https://demo-ose.nubefact.com/ol-ti-itcpe/billService?wsdl');
				//$see = $config->configuracion(SunatEndpoints::GUIA_BETA);
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


        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');
        $sucursal = $loadDatos->getSucursalSelect($idSucursal);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $_array = [];

        if($req->modoTraslado == '01'){
            if($empresa->Ruc == $req->rucEmpresa){
                $error = 0;
                array_push($_array, $error);
                array_push($_array, 2560);
                array_push($_array, 'El Ruc de Empresa Transportista debe ser diferente al Ruc del Remitente');
                return $_array;
            }

            $longitud = strlen($req->rucEmpresa);

            if ($longitud == 11) {
                $url = 'https://dniruc.apisperu.com/api/v1/ruc/' . $req->rucEmpresa . '?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImZhY3R1cmFjaW9uQGVhc3lmYWN0cGVydS5wZSJ9.45m3851f7G3SqNhT4MZ20YTZt6h4HD3YIf8pfwZgGJI';
                
                $curl = curl_init();

                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HEADER, false);

                $_result = curl_exec($curl);
                $result = json_decode($_result, true);
                curl_close($curl);
                
                if(!empty($result["message"])){
                    $error = 0;
                    array_push($_array, $error);
                    array_push($_array, 3348);
                    array_push($_array, 'El Ruc de Empresa Transportista no existe');
                    return $_array;
                }else{
                    if($result["estado"] != 'ACTIVO' || $result["condicion"] != 'HABIDO'){
                        $error = 0;
                        array_push($_array, $error);
                        array_push($_array, 3349);
                        array_push($_array, 'El Ruc de Empresa Transportista no esta Activo o no Habido');
                        return $_array;
                    }
                }
            }else{
                $error = 0;
                array_push($_array, $error);
                array_push($_array, 3348);
                array_push($_array, 'El Ruc de Empresa Transportista no existe');
                return $_array;
            }
        }
        
        //$codSunatComprobante = $loadDatos->getSelectTipoDocumento($req->tipDocTransp);
        $selectMotivo = $loadDatos->getSelectMotivo($req->motivo, 'g');
        
        $cliente = $loadDatos->getClienteSelect($req->idCliente);
        
        //$fecha1 = new DateTime();
        
        $fecha1 = $req->fechaEmitida;
        $fecha1Convert = Carbon::createFromFormat('d/m/Y', $fecha1)->format("Y-m-d H:i:s");
        $fechaEmitida = new DateTime($fecha1Convert);
        
        $fecha2 = $req->fechaTraslado;
        $fecha2Convert = Carbon::createFromFormat('d/m/Y', $fecha2)->format("Y-m-d H:i:s");
        $fechaTraslado = new DateTime($fecha2Convert);
        /////************ GRE Antigua  ***************/
        /*$address = new Address();
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
        $transp->setTipoDoc($req->tipDocEmpresa)
            ->setNumDoc($req->rucEmpresa)
            ->setRznSocial($req->razonSocialEmpresa)
            ->setPlaca($req->placa)
            ->setChoferTipoDoc($req->tipDocTransp)
            ->setChoferDoc($req->docTransp);
        
        if($req->motivo == 16){
            $codigoSunat = '6';
            $nroDocumneto = $empresa->Ruc;
            $destinatario = (new Client())
                ->setTipoDoc(6)
                ->setNumDoc($empresa->Ruc)
                ->setRznSocial($empresa->Nombre);
        }else{
            $codigoSunat = $cliente->CodigoSunat;
            $nroDocumneto = $cliente->NumeroDocumento;
            $destinatario = (new Client())
                ->setTipoDoc($cliente->CodigoSunat)
                ->setNumDoc($cliente->NumeroDocumento)
                ->setRznSocial($cliente->Nombre);
        }

        $envio = new Shipment();
        $envio->setModTraslado($req->modoTraslado)
            ->setCodTraslado($selectMotivo->CodigoSunat)
            ->setDesTraslado($selectMotivo->Descripcion)
            ->setFecTraslado(new DateTime($req->fechaEmitida))
            //->setCodPuerto('123')
            //->setIndTransbordo(false)
            ->setPesoTotal($req->peso)
            ->setUndPesoTotal('KGM')
            ->setNumBultos($req->bultos)
            //->setNumContenedor('XD-2232')
            ->setLlegada(new Direction($req->distrito2,$req->destino))
            ->setPartida(new Direction($req->distrito,$req->origen))
            ->setTransportista($transp);
        
        $despatch = new Despatch();
        $despatch->setTipoDoc('09')
            ->setSerie($req->serie)
            ->setCorrelativo($req->numero)
            ->setFechaEmision(new DateTime($req->fechaTraslado))
            ->setCompany($company)
            ->setDestinatario($destinatario)
            ->setObservacion($req->observacion)
            ->setEnvio($envio);
        
        $array = [];
        if($req->option == 1){
            $items = $loadDatos->getItemsVentas($req->idVentas);
            for($i=0; $i<count($items); $i++){
                $detail = new DespatchDetail();
                $detail->setCantidad($items[$i]->Cantidad)
                    ->setUnidad($items[$i]->CodSunatMedida)
                    ->setDescripcion($items[$i]->Descripcion)
                    ->setCodigo($items[$i]->Cod);
                array_push($array, $detail);
            }
        }else{
            for($i=0; $i<count($req->Codigo); $i++){
                $id = substr($req->Codigo[$i], 4);
                $producto = $loadDatos->getProductoSelect($id);
                $detail = new DespatchDetail();
                $detail->setCantidad($req->Cantidad[$i])
                    ->setUnidad($producto->MedidaSunat)
                    ->setDescripcion($producto->Descripcion)
                    ->setCodigo($req->Codigo[$i]);
                array_push($array, $detail);
            }
        }*/

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

        $envio = new Shipment();
        $envio->setModTraslado($req->modoTraslado)
            ->setCodTraslado($selectMotivo->CodigoSunat)
            //->setDesTraslado($selectMotivo->Descripcion)
            ->setFecTraslado($fechaTraslado)
            ->setPesoTotal($req->peso)
            ->setUndPesoTotal('KGM');
            //->setLlegada(new Direction($req->distrito2,$req->destino))
            //->setPartida(new Direction($req->distrito,$req->origen))
            

        if($req->motivo == 16){

            $codigoSunat = '6';
            $nroDocumneto = $empresa->Ruc;
            $destinatario = (new Client())
                ->setTipoDoc(6)
                ->setNumDoc($empresa->Ruc)
                ->setRznSocial($empresa->Nombre);

            $envio->setIndicador(['SUNAT_Envio_IndicadorTrasladoVehiculoM1L'])
                ->setLlegada((new Direction($req->distrito2,$req->destino))
                    ->setRuc($empresa->Ruc)
                    ->setCodLocal($req->codigoSucDestino)) // Código de establecimiento anexo
                ->setPartida((new Direction($req->distrito,$req->origen))
                    ->setRuc($empresa->Ruc)
                    ->setCodLocal($empresa->CodigoFiscal));
            
        }else{
            $codigoSunat = $cliente->CodigoSunat;
            $nroDocumneto = $cliente->NumeroDocumento;
            $destinatario = (new Client())
                ->setTipoDoc($cliente->CodigoSunat)
                ->setNumDoc($cliente->NumeroDocumento)
                ->setRznSocial($cliente->Nombre);

            if($req->modoTraslado == '01'){
                $transp = new Transportist();
                $transp->setTipoDoc($req->tipDocEmpresa)
                    ->setNumDoc($req->rucEmpresa)
                    ->setRznSocial($req->razonSocialEmpresa)
                    ->setPlaca($req->placa)
                    ->setChoferTipoDoc($req->tipDocTransp)
                    ->setChoferDoc($req->docTransp);

                $envio->setTransportista($transp)
                    ->setLlegada(new Direction($req->distrito2,$req->destino))
                    ->setPartida(new Direction($req->distrito,$req->origen));
            }else{
                $envio->setIndicador(['SUNAT_Envio_IndicadorTrasladoVehiculoM1L'])
                    ->setLlegada(new Direction($req->distrito2,$req->destino))
                    ->setPartida(new Direction($req->distrito,$req->origen));
            }   
        }

        $despatch = new Despatch();
        $despatch->setVersion('2022')
            ->setTipoDoc('09')
            ->setSerie($req->serie)
            ->setCorrelativo($req->numero)
            ->setFechaEmision($fechaEmitida)
            ->setCompany($company)
            ->setDestinatario($destinatario)
            ->setObservacion($req->observacion)
            ->setEnvio($envio);

        if($req->option == 1){
            $ventaSelect = $loadDatos->getVentaselect($req->idVentas);
            if($ventaSelect->IdTipoComprobante == 1){
                $descripcionDoc = 'Boleta';
            }else{
                $descripcionDoc = 'factura';
            }
    
            $documento = $ventaSelect->Serie.'-'.$ventaSelect->Numero;
            $rel = new AdditionalDoc();
                $rel->setTipoDesc($descripcionDoc)
                ->setTipo($ventaSelect->IdTipoSunat) // Cat. 61 - Factura
                ->setNro($documento)
                ->setEmisor($empresa->Ruc);
            $despatch->setAddDocs([$rel]);
        }


        $array = [];
        if($req->option == 1){
            $items = $loadDatos->getItemsVentas($req->idVentas);
            for($i=0; $i<count($items); $i++){
                $detail = new DespatchDetail();
                $detail->setCantidad($items[$i]->Cantidad)
                    ->setUnidad($items[$i]->CodSunatMedida)
                    ->setDescripcion($items[$i]->Descripcion)
                    ->setCodigo($items[$i]->Cod);
                array_push($array, $detail);
            }
        }else{
            for($i=0; $i<count($req->Codigo); $i++){
                $id = substr($req->Codigo[$i], 4);
                $producto = $loadDatos->getProductoSelect($id);
                $detail = new DespatchDetail();
                $detail->setCantidad($req->Cantidad[$i])
                    ->setUnidad($producto->MedidaSunat)
                    ->setDescripcion($producto->Descripcion)
                    ->setCodigo($req->Codigo[$i]);
                array_push($array, $detail);
            }
        }

        $despatch->setDetails($array);
        
        $xml_string = $see->getXmlSigned($despatch);
        
        $now = Carbon::now();
        $anio = $now->year;
        $mes = $now->month;
        $_mes = $loadDatos->getMes($mes);
        $nombreArchivo = $empresa->Ruc.'-09-'.$req->serie.'-'.$req->numero;
        $rutaXml = '/RespuestaSunat/'.$anio.'/'.$_mes.'/'.$empresa->Ruc.'/GuiasRemision/'.$nombreArchivo.'.zip';
        $rutaCdr = null;

        $zipFile = new ZipFile();
        $zipFile->addFromString($despatch->getName().'.xml', $see->getFactory()->getLastXml(), ZipCompressionMethod::DEFLATED);
        $resultZip = $zipFile->outputAsString();
        $zipFile->close();

        $config->writeXml($despatch, $resultZip, $empresa->Ruc, $anio, $_mes, 3);

        $access_token = $config->getTokenGRE($empresa);
        //dd($access_token);

        if(!empty($access_token["access_token"])){
            $resEnvio = $config->envioGRE($despatch, $access_token["access_token"], $resultZip);
            usleep(2000000);
            if(!empty($resEnvio["numTicket"])){
                
                $resCdr = $config->consultaCDR($resEnvio["numTicket"], $access_token["access_token"]);

                if($resCdr["codRespuesta"] == 0){
                    $now = Carbon::now();
                    $anio = $now->year;
                    $mes = $now->month;
                    $_mes = $loadDatos->getMes($mes);

                    $rutaCdr = '/RespuestaSunat/'.$anio.'/'.$_mes.'/'.$empresa->Ruc.'/GuiasRemision/R-'.$despatch->getName().'.zip';
                    $cdrGRE = base64_decode($resCdr["arcCdr"]);
                    $config->writeCdr($despatch, $cdrGRE, $empresa->Ruc, $anio, $_mes, 3);
                    //Storage::disk('s3')->put($rutaCdr, $cdrGRE, 'public');

                    $_array = [];
                    $respuesta = 1;

                    $doc = new DOMDocument();
                    $doc->loadXML($xml_string);
                    $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                    $date = new DateTime();
                    $fecha = $date->format('Y-m-d');
                    $resumen = $empresa->Ruc.'|09|'.$req->serie.'|'.$req->numero.'|'.$fecha.'|'.$codigoSunat.'|'.$nroDocumneto;
                    $descripcionCDR = 'La GRE fue aceptada con éxito';
                    array_push($_array, $hash);
                    array_push($_array, $resumen);
                    array_push($_array, $respuesta);
                    array_push($_array, $resCdr["codRespuesta"]); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                    array_push($_array, $descripcionCDR); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                    array_push($_array, $rutaXml);
                    array_push($_array, $rutaCdr);
                    array_push($_array, $resEnvio['numTicket']);

                    return $_array;

                    /*DB::table('guia_remision')
                                ->where('IdGuiaRemision',$req->idDocEnvio)
                                ->update(['RutaCdr'=>$rutaCdr, 'Estado' => 'Aceptado']);*/
                }else{
                    if($resCdr["codRespuesta"] == 99){
                        $_array = [];
                        $respuesta = 1;
                        $doc = new DOMDocument();
                        $doc->loadXML($xml_string);
                        $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                        $date = new DateTime();
                        $fecha = $date->format('Y-m-d');
                        $resumen = $empresa->Ruc.'|09|'.$req->serie.'|'.$req->numero.'|'.$fecha.'|'.$codigoSunat.'|'.$nroDocumneto;
                        $resultadoCdr = $resCdr["error"];

                        if($resCdr["indCdrGenerado"] == 1){
                            $now = Carbon::now();
                            $anio = $now->year;
                            $mes = $now->month;
                            $_mes = $loadDatos->getMes($mes);

                            $rutaCdr = '/RespuestaSunat/'.$anio.'/'.$_mes.'/'.$empresa->Ruc.'/GuiasRemision/R-'.$despatch->getName().'.zip';
                            $cdrGRE = base64_decode($resCdr["arcCdr"]);
                            $config->writeCdr($despatch, $cdrGRE, $empresa->Ruc, $anio, $_mes, 3);
                        }

                        array_push($_array, $hash);
                        array_push($_array, $resumen);
                        array_push($_array, $respuesta);
                        array_push($_array, $resultadoCdr["numError"]); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                        array_push($_array, $resultadoCdr["desError"]); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                        array_push($_array, $rutaXml);
                        array_push($_array, $rutaCdr);
                        array_push($_array, $resEnvio['numTicket']);
                    
                        return $_array;
                    }else{
                        $_array = [];
                        $respuesta = 2;
                        $doc = new DOMDocument();
                        $doc->loadXML($xml_string);
                        $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                        $date = new DateTime();
                        $fecha = $date->format('Y-m-d');
                        $resumen = $empresa->Ruc.'|09|'.$req->serie.'|'.$req->numero.'|'.$fecha.'|'.$codigoSunat.'|'.$nroDocumneto;
                        $descripcionCDR = "No se puedo enviar a Sunat";

                        array_push($_array, $hash);
                        array_push($_array, $resumen);
                        array_push($_array, $respuesta);
                        array_push($_array, $resCdr["codRespuesta"]); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                        array_push($_array, $descripcionCDR); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                        array_push($_array, $rutaXml);
                        array_push($_array, $rutaCdr);
                        array_push($_array, $resEnvio['numTicket']);
                    
                        return $_array;
                    }
                    
                }
            }else{
                $_array = [];
                $error = 0;
                array_push($_array, $error);
                array_push($_array, $access_token["cod"]);
                array_push($_array, $access_token["msg"]);
                return $_array;
            }
        }else{
            $_array = [];
            $error = 0;
            array_push($_array, $error);
            array_push($_array, $access_token["cod"]);
            array_push($_array, $access_token["msg"]);
            return $_array;
        }
        
        //$res = $see->send($despatch);
        /*if ($res->isSuccess()){
            $rutaCdr = '/RespuestaSunat/'.$anio.'/'.$_mes.'/'.$empresa->Ruc.'/GuiasRemision/R-'.$nombreArchivo.'.zip';
            $cdr = $res->getCdrResponse();
            $config->writeCdr($despatch, $res->getCdrZip(), $empresa->Ruc, $anio, $_mes, 3);
            $config->showResponse($despatch, $cdr);
            
            $_array = [];
            $respuesta = 1;
			
			$isAccetedCDR=$res->getCdrResponse()->isAccepted();
			$descripcionCDR=$res->getCdrResponse()->getDescription();
			$codeCDR=  $res->getCdrResponse()->getCode();
			
            $doc = new DOMDocument();
            $doc->loadXML($xml_string);
            $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
            $date = new DateTime();
            $fecha = $date->format('Y-m-d');
            $resumen = $empresa->Ruc.'|09|'.$req->serie.'|'.$req->numero.'|'.$fecha.'|'.$codigoSunat.'|'.$nroDocumneto;
            array_push($_array, $hash);
            array_push($_array, $resumen);
            array_push($_array, $respuesta);
			array_push($_array, $codeCDR); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
            array_push($_array, $descripcionCDR); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
            array_push($_array, $rutaXml);
            array_push($_array, $rutaCdr);
			
            return $_array;
        } else {
            //echo $config->getErrorResponse($res->getError());
           	// echo "<script language='javascript'>alert('Servicio inestable, intentelo en otro momento');</script>";
			//dd($result);
            $_array = [];
            if($res->getError()->getCode() == 'HTTP' || $res->getError()->getCode() == 'HTTPS'){
                echo "<script language='javascript'>alert('Servicio inestable, intentelo en otro momento');</script>";
                $respuesta = 2;

                $codeOp=-1;
                $descripOp="";
                $accepOp=-1;
									
				$doc = new DOMDocument();
                $doc->loadXML($xml_string);
                $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                $date = new DateTime();
                $fecha = $date->format('Y-m-d');
                $resumen = $empresa->Ruc.'|09|'.$req->serie.'|'.$req->numero.'|'.$fecha.'|'.$codigoSunat.'|'.$nroDocumneto;
                array_push($_array, $hash);
                array_push($_array, $resumen);
                array_push($_array, $respuesta);
                array_push($_array, $codeOp);
                array_push($_array, $descripOp);
                array_push($_array, $rutaXml);
                array_push($_array, $rutaCdr);
                                         
            }else{
                //echo '<script language="javascript">alert("'.$result->getError()->getMessage().'");</script>';
                $respuesta = 1;
                $hash = '';

				$descripcionError=$res->getError()->getMessage();
				$codeError = $res->getError()->getCode();
				$isAccetedError=-1;
                $date = new DateTime();
                $fecha = $date->format('Y-m-d');
									
				//$ver=$descripcionError.'-'.$codeError;  $result->getError();//borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                $resumen = $empresa->Ruc.'|09|'.$req->serie.'|'.$req->numero.'|'.$fecha.'|'.$codigoSunat.'|'.$nroDocumneto;
				//----  return Response(['verificar','error '.$result->getError()->getCode().' verificara la valides de este Documento', $TmpidVenta]);
 
                array_push($_array, $hash);
                array_push($_array, $resumen);
                array_push($_array, $respuesta);
				array_push($_array, $codeError);//borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
				array_push($_array, $descripcionError);//borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
				array_push($_array, $rutaXml);
                array_push($_array, $rutaCdr);
            }
			return $_array;
        }*/
    }

    public function searchProducto(Request $req) {
        if($req->ajax()){
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
			$textoBuscar = $req->textoBuscar;

			$cod_cliente = DB::table('sucursal')
					   ->select('CodigoCliente')
					   ->where('IdSucursal', $idSucursal)
					   ->first();

			$sucPrincipal = DB::table('sucursal')
					   ->select('IdSucursal')
					   ->where('CodigoCliente',$cod_cliente->CodigoCliente)
					   ->where('Principal', 1)
					   ->first();

            if($sucPrincipal->IdSucursal == $idSucursal)
            {
                $articulos = $loadDatos->getAllProductosPagination($idSucursal, $textoBuscar);
            }
            else
            {
                $articulos=$loadDatos->getAllProductosPagination($idSucursal, $textoBuscar);
            }
            

            return Response($articulos);
        }
    }

    public function paginationProductos(Request $req){
        if($req->ajax()){
            $idSucursal = Session::get('idSucursal');
            $loadDatos = new DatosController();

                    $cod_cliente = DB::table('sucursal')
                    ->select('CodigoCliente')
                    ->where('IdSucursal', $idSucursal)
                    ->first();

                        $sucPrincipal = DB::table('sucursal')
                    ->select('IdSucursal')
                    ->where('CodigoCliente',$cod_cliente->CodigoCliente)
                    ->where('Principal', 1)
                    ->first();


                    if($sucPrincipal->IdSucursal == $idSucursal)
                    {
                    $productos = $loadDatos->getAllProductosPagination($idSucursal, $req->textoBuscar);
                    }
                    else
                    {
                    $productos = $loadDatos->getAllProductosPagination($idSucursal, $req->textoBuscar);
                    }
            return Response($productos);
        }
    }
	
	public function obtenerInformacion(Request $req)
	{
		try{
            if($req->session()->has('idUsuario')){
                if($req->ajax()){

					$idUsuario = Session::get('idUsuario');
					$loadDatos = new DatosController();
        			$idSucursal = Session::get('idSucursal');
					$req->tipoDoc;

					if($req->tipoDoc==0)
				   	{
				   		//return Response(['error','Por favor, elegir Tipo de comprobante']);
				   		return Response()->json([
				   			'error'=>true
				   		]);
				   	}
				   	else
				   	{
				   		$letra='T';
						
						$usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
                        $orden = $usuarioSelect->Orden;
                        $sucursal = $loadDatos->getSucursalSelect($idSucursal);
                        $ordenSucursal = $sucursal->Orden;

				   	    $numeroDB=$this->ultimoCorrelativo($idUsuario, $idSucursal);

                      	if($numeroDB)
                      	{
                      		$numero = str_pad($numeroDB->Numero+1, 8, "0", STR_PAD_LEFT);
                      	}
                      	else {
                      		$numero = str_pad(1, 8, "0", STR_PAD_LEFT);
                      	}

                      	$serieCeros = str_pad($orden, 2, "0", STR_PAD_LEFT);
                      	$serie=$letra.''.$ordenSucursal.''.$serieCeros; 
					
						$clientes=array();
					/* 	$serie=1;
						$numero='0005'; */

				     	//$clientes = $loadDatos->getTipoClientes($req->tipoDoc, $idSucursal);
				     	return Response()->json([
				     		'clientes'=>$clientes,
				     		'serie'=>$serie,
				     		'numero'=>$numero,
				     		'tipo'=>$req->tipoDoc
				     	]);
				   	}
				}
			}
		}catch (Exception $ex){
            echo $ex->getMessage();
        }
	}

    public function mostrarDocumentos(Request $req){
        $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');
        $loadDatos = new DatosController();
        $option = $req->option;
        $array = [];
        if($option == 1){
            $date = Carbon::today();
            $dateAtras = $date->subMonth(3)->firstOfMonth();
            //$array= $loadDatos->getVentasAceptadasGuias($idSucursal, $dateAtras);
            $motivosGuias = $loadDatos->getMotivos('gv', 0);
        }else{
            //$array = $loadDatos->getTipoClientes(3,$idSucursal);
            //$motivosGuias = $loadDatos->getMotivos('gt');
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $sucursales = $loadDatos->getSucursalesRestantes($idSucursal, $usuarioSelect->CodigoCliente);
            if(count($sucursales) > 0){
                $motivosGuias = $loadDatos->getMotivos('gt', 0);
            }else{
                $motivosGuias = $loadDatos->getMotivos('gt', 16);
            }
        }
        
        return Response()->json(['array'=>$array, 'motivos' => $motivosGuias]);
    }

    private function verificarCodigo($serie, $numero, $idSucursal)
    {
        try {
            $resultado = DB::table('guia_remision')
                ->select('guia_remision.*')
                ->where('Serie', $serie)
                ->where('Numero', $numero)
                ->where('IdSucursal', $idSucursal)
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function mostrarSucursales(Request $req){
        $idSucursal = $req->idSucursal;
        $loadDatos = new DatosController();
        $sucursal = $loadDatos->getSucursalSelect($idSucursal);
        $provincias2 = $loadDatos->getProvincias($sucursal->IdDepartamento);
        $distritos2 = $loadDatos->getDistritos($sucursal->IdProvincia);
        return Response()->json(['sucursal'=>$sucursal, 'provincias' => $provincias2, 'distritos' => $distritos2]);
    }
	
	private function ultimoCorrelativo($idUsuario, $idSucursal) {
        try{
            $resultado = DB::table('guia_remision')
                        ->where('IdUsuario', $idUsuario)
                        ->where('IdSucursal', $idSucursal)
                        ->orderBy('IdGuiaRemision', 'desc')
                        ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    
    protected function validateGuiaPublico(Request $request) {
        $this->validate($request, [
            'serie' => 'required',
            'numero' => 'required',
            'cliente' => 'required',
			'motivo'=>'required',
            'origen' => 'required',
            'destino' => 'required',
            'modoTraslado' => 'required',
            'peso' => 'required',
            'fechaTraslado' => 'required',
            'razonSocialEmpresa' => 'required',
            'rucEmpresa' => 'required'
        ]);
    }

    protected function validateGuiaPrivado(Request $request) {
        $this->validate($request, [
            'serie' => 'required',
            'numero' => 'required',
            'cliente' => 'required',
			'motivo'=>'required',
            'origen' => 'required',
            'destino' => 'required',
            'modoTraslado' => 'required',
            'peso' => 'required',
            'fechaTraslado' => 'required'
        ]);
    }
}
