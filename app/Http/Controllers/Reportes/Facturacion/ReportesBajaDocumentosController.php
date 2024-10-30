<?php

namespace App\Http\Controllers\Reportes\Facturacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Session;
use Carbon\Carbon;
use DateTime;
use Greenter\Model\Voided\Voided;
use Greenter\Model\Voided\VoidedDetail;
use Greenter\Ws\Services\SunatEndpoints;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use App\Http\Controllers\Operaciones\NumeroALetras;
use App\Http\Controllers\Servicios\config;
use Greenter\Model\Response\Error;
use DOMDocument;
use DB;
use Storage;

class ReportesBajaDocumentosController extends Controller
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
		
        $fechaInicio = Carbon::today()->subDay(90);
        //$documentos = $loadDatos->getDocumentos($idSucursal, $fechaInicio);
        $bajaDocumentos = $loadDatos->getBajaDocumentos($idSucursal, null);
       
        $bajaDocumentoPendientes = $loadDatos->getBajaDocumentos($idSucursal, 'Baja Pendiente');
        
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $array = ['usuarioSelect' => $usuarioSelect, 'permisos' => $permisos, 'bajaDocumentos' => $bajaDocumentos, 'bajaDocumentoPendientes' => $bajaDocumentoPendientes, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles, 'rucEmpresa' => $empresa->Ruc];
        return view('reportes/facturacion/reportesBajaDocumentos', $array);
    }

    public function descargarXML(Request $req, $ruc, $id){
        if ($req->session()->has('idUsuario')) {

            try{
				$idUsuario = Session::get('idUsuario');
				$loadDatos = new DatosController();
				$bajaDocumento = $loadDatos->getBajaDocumentoSelect($id);
				$file = $ruc.'-'.$bajaDocumento->Identificador.'.xml';
				
				if(Storage::disk('s3')->exists($bajaDocumento->RutaXml)){
					$rutaS3 = Storage::disk('s3')->get($bajaDocumento->RutaXml);

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


            /*$idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $bajaDocumento = $loadDatos->getBajaDocumentoSelect($id);
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
            $rucEmpresa = $empresa->Ruc;
            $file = $rucEmpresa.'-'.$bajaDocumento->Identificador;
            return response()->download(public_path().'/RespuestaSunat/BajaDocumentos/'.$rucEmpresa.'/'.$file.'.xml');*/
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
    }
    
    public function descargarCDR(Request $req, $ruc, $id) {
        if ($req->session()->has('idUsuario')) {
             $idUsuario = Session::get('idUsuario');

            try{
				$idUsuario = Session::get('idUsuario');
				$loadDatos = new DatosController();
				$bajaDocumento = $loadDatos->getBajaDocumentoSelect($id);
                $file = 'R-'.$ruc.'-'.$bajaDocumento->Identificador.'.zip';
				
				if(Storage::disk('s3')->exists($bajaDocumento->RutaCdr)){
					$rutaS3 = Storage::disk('s3')->get($bajaDocumento->RutaCdr);

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
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        
        /*$loadDatos = new DatosController();
        $resumenDiarioSelect = $loadDatos->getBajaDocumentoSelect($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $rucEmpresa = $empresa->Ruc;
        $file = 'R-'.$rucEmpresa.'-'.$resumenDiarioSelect->Identificador;
        return response()->download(public_path().'/RespuestaSunat/BajaDocumentos/'.$rucEmpresa.'/'.$file.'.zip');*/
    }
    
    public function enviarTicket(Request $req, $id) {
        if ($req->session()->has('idUsuario')) {
             $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        
        $loadDatos = new DatosController();
        $bajaDocumento = $loadDatos->getBajaDocSelect($id);
        
        $config = new config();
        $see = $config->configuracion(SunatEndpoints::FE_BETA);
        
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $rucEmpresa = $empresa->Ruc;
        
        $res = $see->getStatus($bajaDocumento->Ticket);
        
        if($res->getCdrResponse() == null){
            return redirect('/reportes/facturacion/baja-documentos')->with('error','No se pudo obtener CDR de Documento');
        }else{
            $cdr = $res->getCdrResponse();
            $name = $rucEmpresa.'-'.$cdr->getId();
            //$config->writeCdr($voided, $res->getCdrZip(), $rucEmpresa, 5);
            //$config->writeCdr($name, $res->getCdrZip(), 4);
            file_put_contents(public_path().'/RespuestaSunat/BajaDocumentos/'.$rucEmpresa.'/'.$name, $cdr);
            DB::table('baja_documentos')
                ->where('IdBajaDoc',$id)
                ->update(['Identificador' => $cdr->getId(), 'Estado' => 'Aceptado']);
            return redirect('/reportes/facturacion/baja-documentos')->with('status','Se obtuvo CDR de Documento Correctamente');
        }
    }

    public function verBajaDocumentos(Request $req){
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            
            $loadDatos = new DatosController();
			$permisos = $loadDatos->getPermisos($idUsuario);
			
			$subpermisos=$loadDatos->getSubPermisos($idUsuario);
			$subniveles=$loadDatos->getSubNiveles($idUsuario);
			
			$bajaDocumentos = $this->bajaDocumentos();
			$usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
			$modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $array = ['permisos' => $permisos, 'bajaDocumentos' => $bajaDocumentos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
			
			return view('reportes/facturacion/verBajaDocumentosAdmin', $array);
       }else{
           Session::flush();
           return redirect('/')->with('out','Sesión de usuario Expirado');
       }
    }

    private function bajaDocumentos(){
        try{
            $bajas = DB::table('baja_documentos')
                    ->join('sucursal','sucursal.IdSucursal', '=', 'baja_documentos.IdSucursal')
                    ->join('usuario','sucursal.CodigoCliente', '=', 'usuario.CodigoCliente')
                    ->select('baja_documentos.*', 'sucursal.Nombre as Sucursal', 'usuario.Login')
                    ->where('usuario.Cliente', 1)
                    ->whereNotIn('baja_documentos.Estado', ['Aceptado','Baja Aceptado', 'Baja Rechazo'])
                    ->orderBy('FechaEnviada','desc')
                    ->get();
            return $bajas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
