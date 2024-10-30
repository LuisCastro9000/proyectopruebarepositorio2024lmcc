<?php

namespace App\Http\Controllers\Cobranzas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Session;
use Carbon\Carbon;

class DetalleCobranzaController extends Controller
{
    public function __invoke(Request $req, $id) {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        
        $loadDatos = new DatosController();
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $_detalleCobranzas = $loadDatos->getDetalleCobranzas($id);
        $deudasTotales = $loadDatos->getDeudasTotales($id);
        $ventaSelect = $loadDatos->getVentaselect($id);
        
        $plazos = $ventaSelect->PlazoCredito;
		
		$plazos=1;//ceil($plazos/30); //ojo con esto
		
        $tiempoPagar = $this->tiempoPago($plazos);
        $detalleCobranzas = $_detalleCobranzas;
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        if(count($_detalleCobranzas)> 0){
            for($i=0; $i<$plazos; $i++){
                $detalleCobranzas[$i]->FechaInicio = $_detalleCobranzas[$i]->FechaInicio;
                $detalleCobranzas[$i]->FechaUltimo = $_detalleCobranzas[$i]->FechaUltimo;
                $detalleCobranzas[$i]->Importe = number_format($_detalleCobranzas[$i]->Importe, 2, ".", ",");
                $diasAtrasados = $_detalleCobranzas[$i]->DiasPasados;
                if($diasAtrasados <= 0){
                    $diasAtrasados = 0;
                }
                if($diasAtrasados > 0 && $diasAtrasados <= 15){
                    $detalleCobranzas[$i]->NombreEstado = 'Problema Potencial';
                }
                if($diasAtrasados > 15 && $diasAtrasados <= 30){
                    $detalleCobranzas[$i]->NombreEstado = 'Deficiente';
                }
                if($diasAtrasados > 30 && $diasAtrasados <= 60){
                    $detalleCobranzas[$i]->NombreEstado = 'Dudoso';
                }
                if($diasAtrasados > 60){
                    $detalleCobranzas[$i]->NombreEstado = 'Perdida';
                }
                $detalleCobranzas[$i]->DiasPasados = $diasAtrasados;
            }
        }
        $permisos = $loadDatos->getPermisos($idUsuario);
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
        
        $array = ['permisos' => $permisos, 'detalleCobranzas' => $detalleCobranzas, 'ventaSelect' => $ventaSelect, 'tiempoPagar' => $tiempoPagar, 'deudasTotales' => $deudasTotales, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
        return view('cobranzas/detalleCobranza', $array);
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

    public function pagosDetalle(Request $req, $id){
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $_pagosDetalles = $loadDatos->pagosDetalle($id);
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
            
            return view('cobranzas/pagosDetalles', $array);

        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
    }
}
