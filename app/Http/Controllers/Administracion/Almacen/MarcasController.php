<?php

namespace App\Http\Controllers\Administracion\Almacen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Session;
use DB;

class MarcasController extends Controller
{
    public function index(Request $req) {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        //$idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
		
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $marcas = $loadDatos->getMarcasPagination($usuarioSelect->CodigoCliente);
        $array = ['marcas' => $marcas, 'textoBuscar' => '', 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
        return view('administracion/almacen/marcas/marcas', $array);
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
        return view('administracion/almacen/marcas/crearMarca', $array);
    }
    
    public function store(Request $req) {

        try{
            $this->validateMarca($req);
            $loadDatos = new DatosController();
            $fecha = $loadDatos->getDateTime();
            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
            $nombre = $req->nombre;
            $descripcion = '';
            $estado = 'E';
            if($req->imagen != null){
                $imagen = $loadDatos->setImage($req->imagen);
            }else{
                $imagen = 'https://easyfactperu2021.s3.us-west-2.amazonaws.com/1641830772.png';
            }

            // $respuesta = DB::table('marca')->select('IdSucursal', 'Nombre') ->where('IdSucursal',$idSucursal)->where('marca.Nombre',$nombre)->exists();
            //$usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            //$codigoCliente =  $usuarioSelect->CodigoCliente;
            $respuesta = DB::table('marca')
                        ->where('IdSucursal',$idSucursal)
                        ->where('Nombre',$nombre)
                        ->exists();
            if ($respuesta) {
                return back()->with('message', 'El nombre de la Marca ya se encuentra registrado');          
            }else{

                $array = ['IdSucursal' => $idSucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario, 'Nombre' => $nombre, 'Descripcion' => $descripcion, 'Imagen' => $imagen, 'Estado' => $estado];
                DB::table('marca')->insert($array);
            }
            
            return redirect('administracion/almacen/marcas')->with('status', 'Se creo marca correctamente');
        } catch (Exception $ex){
            echo $ex->getMessage();
        }
    }
    
    public function edit(Request $req, $id) {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $marca = $loadDatos->getMarcaSelect($id);
        $permisos = $loadDatos->getPermisos($idUsuario);
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
		
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['marca' => $marca, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
        return view('administracion/almacen/marcas/editarMarca', $array);
    }
    
    public function update(Request $req, $id) {
        try{
            $this->validateMarca($req);
            $loadDatos = new DatosController();
            $fecha = $loadDatos->getDateTime();
            $idUsuario = Session::get('idUsuario');
            $nombre = $req->nombre;
            $descripcion = '';
            if($req->imagen != null){
                $imagen = $loadDatos->setImage($req->imagen);
                $array = ['FechaModificacion' => $fecha, 'IdModificacion' => $idUsuario, 'Nombre' => $nombre, 'Descripcion' => $descripcion, 'Imagen' => $imagen];
            }else{
                 $array = ['FechaModificacion' => $fecha, 'IdModificacion' => $idUsuario, 'Nombre' => $nombre, 'Descripcion' => $descripcion];
            }
            DB::table('marca')
                    ->where('IdMarca', $id)
                    ->update($array);
            
            return redirect('administracion/almacen/marcas')->with('status', 'Se actualizo marca correctamente');
                  
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    
    public function delete($id) {
        try{
            $array = ['Estado' => 'D'];
            DB::table('marca')
                    ->where('IdMarca', $id)
                    ->update($array);
            
            return redirect('administracion/almacen/marcas')->with('status', 'Se elimino marca correctamente');
                  
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    
    public function search(Request $req) {
        if($req->ajax()){
            $loadDatos = new DatosController();
            $idUsuario = Session::get('idUsuario');
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $marcas= $loadDatos->getBuscarMarcasPagination($usuarioSelect->CodigoCliente, $req->textoBuscar);
            return Response($marcas);
        }
    }

    public function paginationMarcas(Request $req){
        if($req->ajax()){
            $idUsuario = Session::get('idUsuario');
			$loadDatos = new DatosController();
			$usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);	
            $marcas = $loadDatos->getBuscarMarcasPagination($usuarioSelect->CodigoCliente, $req->textoBuscar);
            return Response($marcas);
        }
    }
    
    protected function validateMarca(Request $request) {
        $this->validate($request, [
            // 'nombre' => 'required|unique:marca',
            'nombre' => 'required',
            'imagen' => 'max:300'
        ]);
    }
}
