<?php

namespace App\Http\Controllers\Vehicular\Administracion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Session;
use DB;


class ModeloController extends Controller
{
	public function index(Request $req) {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesi�n de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
		$titulo="Lista de Modelos";
		$url="administracion/modelo/crear";
		$controller="modelo";
		
        $permisos = $loadDatos->getPermisos($idUsuario);
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
		
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
		$modelos = $this->getModelos($idSucursal);
  		//dd($tipos);
		$array = ['items' => $modelos, 'titulo' =>$titulo, 'url'=>$url, 'controller'=>$controller, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'texto' => 1, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
		return view('vehicular/general/listar', $array);
    }
    
    public function create(Request $req) {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesi�n de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $permisos = $loadDatos->getPermisos($idUsuario);
		$titulo="Crear Modelo";
		$url="administracion/modelo/salvar";
		$controller="modelo";
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
		
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['titulo'=>$titulo, 'url'=>$url, 'controller'=>$controller, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
        return view('vehicular/general/crear', $array);
    }
    
    public function store(Request $req) {
        try{
            $this->validateMarca($req);
            $loadDatos = new DatosController();
            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
            $nombre = $req->nombre;
			$uso=1;  //esto es  para  que se  usa este  tipo ejem....1  ->  vehiculo
            $estado = 1;

            $array = ['IdSucursal' => $idSucursal, 'IdCreador' => $idUsuario, 'NombreModelo' => $nombre, 'UsoModelo' =>1, 'Estado' => $estado];
            DB::table('modelo_general')->insert($array);
            
            return redirect('vehicular/administracion/modelo')->with('status', 'Se creo Tipo correctamente');
        } catch (Exception $ex){
            echo $ex->getMessage();
        }
    }
    
    public function edit(Request $req, $id) {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesi�n de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $modelo = $this->getModeloSelect($id);
        $permisos = $loadDatos->getPermisos($idUsuario);
		$titulo="Editar Modelo";
		$controller="modelo";
		$url="administracion/modelo/";
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
		
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['tipo' => $modelo, 'titulo'=>$titulo, 'controller'=>$controller, 'url'=>$url, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
        return view('vehicular/general/editar', $array);
    }
    
    public function update(Request $req, $id) {
        try{
            $this->validateMarca($req);
            $loadDatos = new DatosController();
            $idUsuario = Session::get('idUsuario');
            $nombre = $req->nombre;

			$array = ['NombreModelo' => $nombre];
            DB::table('modelo_general')
                    ->where('IdModeloGeneral', $id)
                    ->update($array);
            
            return redirect('vehicular/administracion/modelo')->with('status', 'Se actualizo el Modelo correctamente');
                  
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    
    public function delete($id) {
        try{
            $array = ['Estado' => 0];
            DB::table('modelo_general')
                    ->where('IdModeloGeneral', $id)
                    ->update($array);
            
            return redirect('vehicular/administracion/modelo')->with('status', 'Se elimino el Modelo correctamente');
                  
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    
    protected function validateMarca(Request $request) {
        $this->validate($request, [
            'nombre' => 'required'
        ]);
    }
	
	protected function getModelos($sucursal){
        try{
            $tipos = DB::table('modelo_general')
                    ->select('IdModeloGeneral as id', 'FechaCreacion as fecha', 'NombreModelo as nombre', 'Estado as estado')
					->where('IdSucursal', $sucursal)
                    ->where('UsoModelo', 1)
                    ->where('Estado', 1)
					->orderBy('IdModeloGeneral','desc')
                    ->get();
            return $tipos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
	
	protected function getModeloSelect($id)
	{
		try{
            $tipos = DB::table('modelo_general')
                    ->select('IdModeloGeneral as id', 'FechaCreacion as fecha', 'NombreModelo as nombre', 'Estado as estado')
					->where('IdModeloGeneral', $id)
                    ->first();
            return $tipos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
	}
}