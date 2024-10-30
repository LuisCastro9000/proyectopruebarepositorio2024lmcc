<?php

namespace App\Http\Controllers\Vehicular\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DB;
use Illuminate\Http\Request;
use Session;

class TipoController extends Controller
{
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');

        $titulo = "Lista de Tipos";
        $url = "administracion/tipo/crear";
        $controller = "tipo";

        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $tipos = $this->getTipos($idSucursal);
        //dd($tipos);
        $array = ['items' => $tipos, 'titulo' => $titulo, 'url' => $url, 'controller' => $controller, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'texto' => 1, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('vehicular/general/listar', $array);
    }

    public function create(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $permisos = $loadDatos->getPermisos($idUsuario);
        $titulo = "Crear Tipo";
        //   dd($titulo);
        $url = "administracion/tipo/salvar";
        $controller = "tipo";

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['titulo' => $titulo, 'url' => $url, 'controller' => $controller, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('vehicular/general/crear', $array);
    }

    public function store(Request $req)
    {
        try {
            $this->validateMarca($req);
            $loadDatos = new DatosController();
            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
            $nombre = $req->nombre;
            $uso = 1; //esto es  para  que se  usa este  tipo ejem....1  ->  vehiculo
            $estado = 1;

            $array = ['IdSucursal' => $idSucursal, 'IdCreador' => $idUsuario, 'NombreTipo' => $nombre, 'UsoTipo' => 1, 'Estado' => $estado];
            DB::table('tipo_general')->insert($array);

            return redirect('vehicular/administracion/tipo')->with('status', 'Se creo Tipo correctamente');
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function edit(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $tipo = $this->getTipoSelect($id);
        $permisos = $loadDatos->getPermisos($idUsuario);
        $titulo = "Editar Tipo";
        $controller = "tipo";
        $url = "administracion/tipo/";

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['tipo' => $tipo, 'titulo' => $titulo, 'controller' => $controller, 'url' => $url, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('vehicular/general/editar', $array);
    }

    public function update(Request $req, $id)
    {
        try {
            $this->validateMarca($req);
            $loadDatos = new DatosController();
            $idUsuario = Session::get('idUsuario');
            $nombre = $req->nombre;

            $array = ['NombreTipo' => $nombre];
            DB::table('tipo_general')
                ->where('IdTipoGeneral', $id)
                ->update($array);
            return redirect('vehicular/administracion/tipo')->with('status', 'Se actualizo Tipo correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            $array = ['Estado' => 0];
            DB::table('tipo_general')
                ->where('IdTipoGeneral', $id)
                ->update($array);

            return redirect('vehicular/administracion/tipo')->with('status', 'Se elimino Tipo correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    protected function validateMarca(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required',
        ]);
    }

    protected function getTipos($sucursal)
    {
        try {
            $tipos = DB::table('tipo_general')
                ->select('IdTipoGeneral as id', 'FechaCreacion as fecha', 'NombreTipo as nombre', 'Estado as estado')
                ->where('IdSucursal', $sucursal)
                ->where('UsoTipo', 1)
                ->where('Estado', 1)
                ->orderBy('IdTipoGeneral', 'desc')
                ->get();
            return $tipos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    protected function getTipoSelect($id)
    {
        try {
            $tipos = DB::table('tipo_general')
                ->select('IdTipoGeneral as id', 'FechaCreacion as fecha', 'NombreTipo as nombre', 'Estado as estado')
                ->where('IdTipoGeneral', $id)
                ->first();
            return $tipos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
