<?php

namespace App\Http\Controllers\Administracion\Banco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Session;
use DB;


class BancosController extends Controller
{
    public function index(Request $req) {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $permisos = $loadDatos->getPermisos($idUsuario);
            
            $subpermisos=$loadDatos->getSubPermisos($idUsuario);
            $subniveles=$loadDatos->getSubNiveles($idUsuario);
            
            $cuentasCorrientes = $loadDatos->getCuentasCorrientes($usuarioSelect->CodigoCliente, null);
    
            $array = ['cuentasCorrientes' => $cuentasCorrientes, 'permisos' => $permisos, 'usuarioSelect' => $usuarioSelect, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
            return view('administracion/bancos/cuentaBancaria/bancos', $array);
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
    }
    
    public function create(Request $req) {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $permisos = $loadDatos->getAllPermisos();
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
        $subniveles=$loadDatos->getSubNiveles($idUsuario);
        
        $listaBancos = $loadDatos->getListaBancos();
        $tipoMonedas = $loadDatos->getTipoMoneda();
		
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['listaBancos' => $listaBancos, 'tipoMonedas' => $tipoMonedas, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
        return view('administracion/bancos/cuentaBancaria/crearBanco', $array);
    }
    
    public function store(Request $req) {
        try{
            $loadDatos = new DatosController();
            $idUsuario = Session::get('idUsuario');
            $this->validateBanco($req);
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $idBanco = $req->banco;
            $nroCuenta = $req->cuenta;
            $cci = $req->cci;
            $tipoMoneda = $req->tipoMoneda;
            $inicial = $req->inicial;
            $actual = $req->inicial;
            $array = ['CodigoCliente' => $usuarioSelect->CodigoCliente, 'IdUsuario' => $idUsuario, 'IdListaBanco' => $idBanco, 'NumeroCuenta' => $nroCuenta, 'CCI' => $cci, 'IdTipoMoneda' => $tipoMoneda, 'MontoInicial' => $inicial, 'MontoActual' => $actual, 'Estado' => 'E'];
            DB::table('banco')->insert($array);
            return redirect('administracion/bancos/cuentas-bancarias')->with('status', 'Se creo cuenta corriente correctamente');
        } catch (Exception $ex){
            echo $ex->getMessage();
        }
    }
    
    public function edit(Request $req, $idBanco){
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');

            $loadDatos = new DatosController();
            $usuario = $loadDatos->getUsuarioSelect($idUsuario);
            $permisos = $loadDatos->getPermisos($idUsuario);
            $cuentaCorriente = $loadDatos->getCuentaCorrienteSelect($idBanco);

            $listaBancos = $loadDatos->getListaBancos();
            $tipoMonedas = $loadDatos->getTipoMoneda();
            
            $subpermisos=$loadDatos->getSubPermisos($idUsuario);
            $subniveles=$loadDatos->getSubNiveles($idUsuario);
            
            $modulosSelect = $loadDatos->getModulosSelect($usuario->CodigoCliente);

            $array = ['listaBancos' => $listaBancos, 'tipoMonedas' => $tipoMonedas, 'cuentaCorriente' => $cuentaCorriente, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
            return view('administracion/bancos/cuentaBancaria/editarBanco', $array);
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
       
    }
    
    public function update(Request $req, $id) {
        try{
            $idUsuario = Session::get('idUsuario');
            $this->validateBanco($req);

            $idBanco = $req->banco;
            $nroCuenta = $req->cuenta;
            $cci = $req->cci;
            $tipoMoneda = $req->tipoMoneda;
            
            $array = ['IdListaBanco' => $idBanco, 'NumeroCuenta' => $nroCuenta, 'CCI' => $cci, 'IdTipoMoneda' => $tipoMoneda];
            
            DB::table('banco')
                    ->where('IdBanco', $id)
                    ->update($array);

            return redirect('administracion/bancos/cuentas-bancarias')->with('status', 'Se actualizo banco correctamente');
                  
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function detallesBanco(Request $req, $id){
        try{
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $usuario = $loadDatos->getUsuarioSelect($idUsuario);
            $permisos = $loadDatos->getPermisos($idUsuario);

            $fechas = $loadDatos->getFechaFiltro(5, null, null);
            $detallesCuentaCorriente = DB::select('call sp_getDetalleCuentaCorriente(?, ?, ?)',array($id, $fechas[0], $fechas[1]));
            //$detallesCuentaCorriente = $loadDatos->getDetallesCuentaCorriente($id);
            $cuentaCorriente = $loadDatos->getCuentaCorrienteSelect($id);

            $fecha=5;
            $fechaIni = '';
            $fechaFin = '';
            $idtipo = '';
            
            $subpermisos=$loadDatos->getSubPermisos($idUsuario);
            $subniveles=$loadDatos->getSubNiveles($idUsuario);
            
            $modulosSelect = $loadDatos->getModulosSelect($usuario->CodigoCliente);

            $array = ['cuentaCorriente' => $cuentaCorriente, 'detallesCuentaCorriente' => $detallesCuentaCorriente, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles,
                    'fecha'=>$fecha, 'fechaInicial'=>$fechaIni, 'fechaFinal'=>$fechaFin, 'idTipo'=>$idtipo, 'id'=>$id];
            return view('administracion/bancos/cuentaBancaria/detallesBanco', $array);
                  
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function filtrar(Request $req, $id){
        try{
            
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $usuario = $loadDatos->getUsuarioSelect($idUsuario);
            $permisos = $loadDatos->getPermisos($idUsuario);

            $fecha = $req->fecha;
            $fechaIni = $req->fechaIni;
            $fechaFin = $req->fechaFin;
            $idtipo = 1;

            if($fecha == 9){
                if($fechaIni == null || $fechaFin == null){
                    return back()->with('error','Completar las fechas para filtrar');
                }
                if($fechaIni > $fechaFin){
                    return back()->with('error','La fecha Inicial no puede ser mayor que la Final');
                }
            }
            $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);

            $detallesCuentaCorriente = DB::select('call sp_getDetalleCuentaCorriente(?, ?, ?)',array($id, $fechas[0], $fechas[1]));
            dd($detallesCuentaCorriente);
            $cuentaCorriente = $loadDatos->getCuentaCorrienteSelect($id);

            $subpermisos=$loadDatos->getSubPermisos($idUsuario);
            $subniveles=$loadDatos->getSubNiveles($idUsuario);
            
            $modulosSelect = $loadDatos->getModulosSelect($usuario->CodigoCliente);
            
            $array = ['cuentaCorriente' => $cuentaCorriente, 'detallesCuentaCorriente' => $detallesCuentaCorriente, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles,
                    'fecha'=>$fecha, 'fechaInicial'=>$fechaIni, 'fechaFinal'=>$fechaFin, 'idTipo'=>$idtipo, 'id'=>$id];
            return view('administracion/bancos/cuentaBancaria/detallesBanco', $array);
                  
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function registrar(Request $req){
        try{
            if($req->ajax()){
                $idBanco = $req->idBanco;
                $tipoMovimiento = $req->tipoMovimiento;
                $numeroOperacion = $req->numeroOperacion;
                $detalle = $req->detalle;
                $monto = $req->monto;
                $montoActual = $req->montoActual;
                $loadDatos = new DatosController();
                $fechaHoy = $loadDatos->getDateTime();
                if($tipoMovimiento == 1){
                    $movimiento = "Registro Ingreso";
                    $montoActual = (float)$montoActual + (float)$monto;
                    $entrada = $monto;
                    $salida = 0;
                }else{
                    $movimiento = "Registro Salida";
                    $montoActual = (float)$montoActual - (float)$monto;
                    $entrada = 0;
                    $salida = $monto;
                }

                $array = ['FechaPago' => $fechaHoy, 'IdBanco' => $idBanco, 'NumeroOperacion' => $numeroOperacion, 'Detalle' => $detalle, 'TipoMovimiento' => $movimiento, 'Entrada' => $entrada, 'Salida' => $salida, 'MontoActual' => $montoActual];

                DB::table('banco_detalles')->insert($array);

                DB::table('banco')
                    ->where('IdBanco', $idBanco)
                    ->update(["MontoActual" => $montoActual]);

                return Response(['success','Se registro '.$movimiento.' correctamente']);
            }
        } catch (Exception $ex){
            echo $ex->getMessage();
            return Response(['error','Por favor, completar serie y número correlativo']);
        }
    }

    public function delete($id) {
        try{
            $array = ['Estado' => 'D'];
            DB::table('banco')
                    ->where('IdBanco', $id)
                    ->update($array);
            
            return redirect('administracion/bancos/cuentas-bancarias')->with('status', 'Se elimino producto correctamente');
                  
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function validateBanco(Request $req){
        $this->validate($req, [
            'banco' => 'required',
            'cuenta' => 'required|numeric',
            'cci' => 'required|numeric',
            'tipoMoneda' => 'required',
            'inicial' => 'required'
        ]);
    }
}
