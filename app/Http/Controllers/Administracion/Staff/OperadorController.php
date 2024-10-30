<?php

namespace App\Http\Controllers\Administracion\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Session;
use DB;

class OperadorController extends Controller
{
    public function index(Request $req) {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $operadores = $loadDatos->getRoles();
        $permisos = $loadDatos->getPermisos($idUsuario);
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
		
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['operadores' => $operadores, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
        return view('administracion/staff/operadores/operadores',$array);
    }
    
    public function create(Request $req) {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $permisos = $loadDatos->getPermisos($idUsuario);
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
		
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
        return view('administracion/staff/operadores/crearOperador', $array);
    }
    
    public function store(Request $req) {
        try{
            $this->validateOperador($req);
            $loadDatos = new DatosController();
            $fecha = $loadDatos->getDateTime();
            $operador = $req->operador;
            $descripcion = $req->descripcion;
            $estado = 'E';
            
            $array = ['Rol' => $operador, 'Descripcion' => $descripcion, 'Estado' => $estado];
            DB::table('operador')->insert($array);
            
            return redirect('administracion/staff/operadores')->with('status', 'Se creo operador correctamente');
        } catch (Exception $ex){
            echo $ex->getMessage();
        }
    }
    
    public function edit(Request $req, $id){
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $operador = $loadDatos->getOperadorSelect($id);
        $permisos = $loadDatos->getPermisos($idUsuario);
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
		
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['operador' => $operador, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
        return view('administracion/staff/operadores/editarOperador', $array);
    }
    
    public function update(Request $req, $id) {
        try{
            $this->validateOperador($req);
            $operador = $req->operador;
            $descripcion = $req->descripcion;
            $array = ['Rol' => $operador, 'Descripcion' => $descripcion];
            
            DB::table('operador')
                    ->where('IdOperador', $id)
                    ->update($array);
            
            return redirect('administracion/staff/operadores')->with('status', 'Se actualizo operador correctamente');
                  
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    
    public function delete($id) {
        try{
            $array = ['Estado' => 'D'];
            DB::table('operador')
                    ->where('IdOperador', $id)
                    ->update($array);
            
            return redirect('administracion/staff/operadores')->with('status', 'Se elimino operador correctamente');
                  
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    
    protected function validateOperador(Request $request) {
        $this->validate($request, [
            'operador' => 'required'
        ]);
    }
}
