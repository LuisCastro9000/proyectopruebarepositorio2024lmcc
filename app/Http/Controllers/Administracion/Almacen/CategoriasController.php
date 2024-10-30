<?php

namespace App\Http\Controllers\Administracion\Almacen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Session;
use DB;

class CategoriasController extends Controller
{
    public function index(Request $req) {
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
        // ----------------
        // $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        // $codigoCliente =  $usuarioSelect->CodigoCliente;
        // $respuesta = DB::table('categoria')
        // ->select('sucursal.IdSucursal', 'categoria.Nombre') 
        // ->join('sucursal', 'categoria.IdSucursal', '=', 'sucursal.IdSucursal')
        // ->where('codigoCliente',$codigoCliente)->where('categoria.Nombre',$nombre)->exists();
        // if ($respuesta) {
        //     dd("existo");
        // } else {
        //     dd("No existo");
        // }    
        // dd($usuarioSelect);
        // ----------------------
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $categorias = $loadDatos->getCategoriasPagination($usuarioSelect->CodigoCliente);
        $array = ['categorias' => $categorias, 'textoBuscar' => '', 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
        return view('administracion/almacen/categorias/categorias', $array);
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
        return view('administracion/almacen/categorias/crearCategoria', $array);
    }
    
     public function store(Request $req) {
        try{
            $this->validateCategoria($req);
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

            // $respuesta = DB::table('categoria')->select('IdSucursal', 'Nombre') ->where('IdSucursal',$idSucursal)->where('categoria.Nombre',$nombre)->exists();
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $codigoCliente =  $usuarioSelect->CodigoCliente;
            $respuesta = DB::table('categoria')
                            ->where('IdSucursal',$idSucursal)
                            ->where('Nombre',$nombre)
                            ->exists();
            if ($respuesta) {
                return back()->with('message', 'El nombre de la Categoria ya se encuentra registrado');          
            }else{
                $array = ['IdSucursal' => $idSucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario, 'Nombre' => $nombre, 'Descripcion' => $descripcion, 'Imagen' => $imagen, 'Estado' => $estado];
                DB::table('categoria')->insert($array);
            }
            
            return redirect('administracion/almacen/categorias')->with('status', 'Se creo categoría correctamente');
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
        $categoria = $loadDatos->getCategoriaSelect($id);
        $permisos = $loadDatos->getPermisos($idUsuario);
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
		
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['categoria' => $categoria, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
        return view('administracion/almacen/categorias/editarCategoria', $array);
    }
    
    public function update(Request $req, $id) {
        try{
            $this->validateCategoria($req);
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
            
            DB::table('categoria')
                    ->where('IdCategoria', $id)
                    ->update($array);
            
            return redirect('administracion/almacen/categorias')->with('status', 'Se actualizo categoría correctamente');
                  
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    
    public function delete($id) {
        try{
            $array = ['Estado' => 'D'];
            DB::table('categoria')
                    ->where('IdCategoria', $id)
                    ->update($array);
            
            return redirect('administracion/almacen/categorias')->with('status', 'Se elimino categoría correctamente');
                  
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    
    public function search(Request $req) {
        if($req->ajax()){
            $loadDatos = new DatosController();
            $idUsuario = Session::get('idUsuario');
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $categorias = $loadDatos->getBuscarCategoriasPagination($usuarioSelect->CodigoCliente, $req->textoBuscar);
            return Response($categorias);
        }
    }

    public function paginationCategorias(Request $req){
        if($req->ajax()){
            $idUsuario = Session::get('idUsuario');
			$loadDatos = new DatosController();
			$usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);	
            $categorias = $loadDatos->getBuscarCategoriasPagination($usuarioSelect->CodigoCliente, $req->textoBuscar);

            return Response($categorias);
        }
    }
    
    protected function validateCategoria(Request $request) {
        $this->validate($request, [
            // 'nombre' => 'required|unique:categoria',
            'nombre' => 'required',
            'imagen' => 'max:300'
        ]);
    }
}
