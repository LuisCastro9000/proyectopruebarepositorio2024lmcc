<?php

namespace App\Http\Controllers\Administracion\Banco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DateTime;
use Carbon\Carbon;
use Session;
use DB;

class TipoCambioController extends Controller
{
    public function index(Request $req) {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
            $loadDatos = new DatosController();
            
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $permisos = $loadDatos->getPermisos($idUsuario);
            
            $subpermisos=$loadDatos->getSubPermisos($idUsuario);
            $subniveles=$loadDatos->getSubNiveles($idUsuario);
            //dd('hola');
            $tiposCambios = $loadDatos->getTiposCambios($idSucursal);
            $fecha = Carbon::today();
            $validarTipoCambio = $loadDatos->getTipoCambioHoy($idSucursal, $fecha);
    
            $array = ['tiposCambios' => $tiposCambios, 'permisos' => $permisos, 'validarTipoCambio' => $validarTipoCambio, 'usuarioSelect' => $usuarioSelect, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
            return view('administracion/bancos/tipoCambio/tipoCambio', $array);
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
    }

    public function store(Request $req){
        if($req->session()->has('idUsuario')) {
                $idUsuario = Session::get('idUsuario');
                $idSucursal = Session::get('idSucursal');
                $this->validateTipoCambio($req);
                //$loadDatos = new DatosController();
                $fecha = Carbon::today();
                //$validarTipoCambio = $loadDatos->getTipoCambioHoy($idUsuario,$idSucursal, $fecha);
                $tipoCambioCompras = $req->TipoCambioCompras;
                $tipoCambioVentas = $req->TipoCambioVentas;

                $tipoCambioComprasSunat = $req->tipoCambioComprasSunat;
                $tipoCambioVentasSunat = $req->tipoCambioVentasSunat;

                $array = ['FechaCreacion' => $fecha, 'IdUsuario' => $idUsuario, 'IdSucursal' => $idSucursal, 'TipoCambioCompras' => $tipoCambioCompras, 'TipoCambioVentas' => $tipoCambioVentas, 'Estado' => 'E'];
                DB::table('tipo_cambio')->insert($array);

                $_array = ['FechaCreacion' => $fecha, 'IdUsuario' => $idUsuario, 'IdSucursal' => $idSucursal, 'ComprasSunat' => $tipoCambioComprasSunat, 'VentasSunat' => $tipoCambioVentasSunat, 'Estado' => 'E'];
                DB::table('tipo_cambio_sunat')->insert($_array);

                return redirect('administracion/bancos/tipo-cambio')->with('status', 'Se configuro tipo de cambio correctamente');
            
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
    }

    public function validateTipoCambio(Request $req){
        $this->validate($req, [
            'TipoCambioCompras' => 'required',
            'TipoCambioVentas' => 'required'
        ]);
    }
}