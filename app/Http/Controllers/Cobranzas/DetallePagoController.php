<?php

namespace App\Http\Controllers\Cobranzas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Session;
use Carbon\Carbon;

class DetallePagoController extends Controller
{
    public function __invoke(Request $req, $id) {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $detallePagos = $loadDatos->getDetallePagos($id);
        $deudasTotales = $loadDatos->getDeudasTotalesCompras($id);
        //dd($deudasTotales);
        $compraSelect = $loadDatos->getCompraselect($id);
        $plazos = $compraSelect->PlazoCredito;
		
		$plazos=1;//ceil($plazos/30); //ojo con esto
        
    
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
    
        $permisos = $loadDatos->getPermisos($idUsuario);
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
        
        $array = ['permisos' => $permisos, 'detallePagos' => $detallePagos, 'compraSelect' => $compraSelect, 'deudasTotales' => $deudasTotales, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
        return view('cobranzas/detallePago', $array);
    }
    
    private function tiempoPago($plazo) {
        if($plazo == 1){
            return 30;
        }
        if($plazo == 2){
            return 60;
        }
        if($plazo == 3){
            return 90;
        }
		return $plazo;
    }

    public function pagosProveedoresDetalle(Request $req, $id){
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $_pagosDetalles = $loadDatos->pagosProveedoresDetalle($id);
            if($_pagosDetalles == null){
                $pagosDetalles = [];
            }else{
                $pagosDetalles = $_pagosDetalles;
            }
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $permisos = $loadDatos->getPermisos($idUsuario);
            
            $subpermisos=$loadDatos->getSubPermisos($idUsuario);
            $subniveles=$loadDatos->getSubNiveles($idUsuario);

            $array = ['permisos' => $permisos, 'pagosDetalles' => $pagosDetalles, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
            
            return view('cobranzas/pagoProveedoresDetalles', $array);

        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
    }
}
