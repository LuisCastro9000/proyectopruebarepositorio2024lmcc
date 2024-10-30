<?php

namespace App\Http\Controllers\Cobranzas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Session;
use DB;

class PagosController extends Controller
{
    public function index(Request $req) {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $fechas = $loadDatos->getFechaFiltro(0, null, null);
        $pagos = DB::select('call sp_getPagos(?, ?, ?, ?)',array($idSucursal, null, $fechas[0], $fechas[1]));
        

        //$cobranzas = $loadDatos->getCobranzas($idSucursal);
        $proveedores = $loadDatos->getProveedores($idSucursal);

        $permisos = $loadDatos->getPermisos($idUsuario);
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
        
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $fechaHoy = $loadDatos->getDateTime();
        $pagosTotales = $loadDatos->getPagosProveedoresTotales($idSucursal, $fechaHoy);
        $this->actualizarFechasPasados($idSucursal, $pagosTotales);
        
        $inputproveedor = '';
        $fecha = '';
        $fechaIni = '';
        $fechaFin = '';

        $array = ['inputproveedor' => $inputproveedor, 'pagos' => $pagos, 'proveedores' => $proveedores, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect,'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin];
        return view('cobranzas/pagos', $array);
    }
    
    public function store(Request $req) {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $inputproveedor = $req->proveedor;
        $fecha = $req->fecha;
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        if($fecha == 9){
            if($fechaIni == null || $fechaFin == null){
                return back()->with('error','Completar las fechas para filtrar');
            }
            if($fechaIni > $fechaFin){
                return back()->with('error','La fecha Inicial no puede ser mayor que la Final');
            }
        }
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');
        $permisos = $loadDatos->getPermisos($idUsuario);
		$proveedores = $loadDatos->getProveedores($idSucursal);
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
        
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $pagos = DB::select('call sp_getPagos(?, ?, ?, ?)',array($idSucursal, $inputproveedor, $fechas[0], $fechas[1]));
       // $cobranzas = $loadDatos->getCobranzasFiltrados($idSucursal, $inputcliente, $fecha, $fechaIni, $fechaFin);
		
        $array = ['permisos' => $permisos, 'inputproveedor' => $inputproveedor, 'pagos' => $pagos, 'proveedores' => $proveedores, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
        return view('cobranzas/pagos', $array);
    }
    
    private function actualizarFechasPasados($idSucursal, $noVencidos) {
        for($i=0; $i<count($noVencidos); $i++){
            DB::table('fecha_compras')
            ->join('compras','fecha_compras.IdCompras', '=', 'compras.IdCompras')
            ->where('compras.IdSucursal',$idSucursal)
            ->where('fecha_compras.Estado', '!=', 2)
            ->where('fecha_compras.IdFechaCompras',$noVencidos[$i]->IdFechaCompras)
            ->update(['DiasPasados' => $noVencidos[$i]->Dias]);
        }
    }
}