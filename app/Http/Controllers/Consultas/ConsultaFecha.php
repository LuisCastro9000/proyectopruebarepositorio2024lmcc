<?php

namespace App\Http\Controllers\Consultas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Session;
use DB;
use DateTime;
use PDF;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class ConsultaFecha extends Controller
{
	
	public function fechaVencimiento(Request $req, $id)
	{
		 if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
		
		$loadDatos = new DatosController();
        $compraSelect = $loadDatos->getCompraselect($id);
        $idUsuario = Session::get('idUsuario');
        $permisos = $loadDatos->getPermisos($idUsuario);
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
		
        $fecha = date_create($compraSelect->FechaCreacion);
        $formatoFecha = date_format($fecha, 'd-m-Y');
        $formatoHora = date_format($fecha, 'H:i A');
        $items = $loadDatos->getItemsCompras($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['compraSelect' => $compraSelect, 'items' => $items, 'formatoFecha' => $formatoFecha, 'permisos' => $permisos, 'formatoHora' => $formatoHora, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
        return view('consultas/consultaFechaVencimiento', $array)->with('status','Se busco la compra exitosamente');
	}
	
	public function fechaAction(Request $req)
	{
		try{
            if($req->session()->has('idUsuario')) {
                if($req->ajax()){
                    $idUsuario = Session::get('idUsuario');
                    $identificador = $req->idem;
					if($identificador == null){
                        return Response(['alert1','No existe identificador']);
                    }
					$fecha = $req->fechaVenc;
                    if($fecha == 0){
                        return Response(['alert2','Por favor, elegir Fecha valida']);
                    }
					
					DB::table('compras_articulo')
                        ->where('IdComprasArticulo', $identificador)
                        ->update(['FechaVencimiento' => $fecha]);
					
					return Response(['succes','Guardado']);
				}
			}
			else
			{
                Session::flush();
                return redirect('/')->with('out','Sesión de usuario Expirado');
			}
		}
		catch (Exception $ex){
            echo $ex->getMessage();
        }
	}
}