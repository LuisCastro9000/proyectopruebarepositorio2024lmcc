<?php

namespace App\Http\Controllers\Vehicular\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DB;
use Illuminate\Http\Request;
use Session;

class MarcaController extends Controller
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
        $titulo = "Lista de Marcas";
        $url = "administracion/marca/crear";
        $controller = "marca";

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
        $titulo = "Crear Marca";
        //   dd($titulo);
        $url = "administracion/marca/salvar";
        $controller = "marca";
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

            $array = ['IdSucursal' => $idSucursal, 'IdCreador' => $idUsuario, 'NombreMarca' => $nombre, 'UsoMarca' => 1, 'Estado' => $estado];
            DB::table('marca_general')->insert($array);

            return redirect('vehicular/administracion/marca')->with('status', 'Se creo la Marca correctamente');
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
        $marca = $this->getMarcaSelect($id);
        $permisos = $loadDatos->getPermisos($idUsuario);
        $titulo = "Editar Marca";
        $controller = "marca";
        $url = "administracion/marca/";

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['tipo' => $marca, 'titulo' => $titulo, 'controller' => $controller, 'url' => $url, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('vehicular/general/editar', $array);
    }

    public function update(Request $req, $id)
    {
        try {
            $this->validateMarca($req);
            $loadDatos = new DatosController();
            $idUsuario = Session::get('idUsuario');
            $nombre = $req->nombre;

            $array = ['NombreMarca' => $nombre];
            DB::table('marca_general')
                ->where('IdMarcaGeneral', $id)
                ->update($array);
            return redirect('vehicular/administracion/marca')->with('status', 'Se actualizo la Marca correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            $array = ['Estado' => 0];
            DB::table('marca_general')
                ->where('IdMarcaGeneral', $id)
                ->update($array);

            return redirect('vehicular/administracion/marca')->with('status', 'Se elimino la Marca correctamente');

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
            $tipos = DB::table('marca_general')
                ->select('IdMarcaGeneral as id', 'FechaCreacion as fecha', 'NombreMarca as nombre', 'Estado as estado')
                ->where('IdSucursal', $sucursal)
                ->where('UsoMarca', 1)
                ->where('Estado', 1)
                ->orderBy('IdMarcaGeneral', 'desc')
                ->get();
            return $tipos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    protected function getMarcaSelect($id)
    {
        try {
            $tipos = DB::table('marca_general')
                ->select('IdMarcaGeneral as id', 'FechaCreacion as fecha', 'NombreMarca as nombre', 'Estado as estado')
                ->where('IdMarcaGeneral', $id)
                ->first();
            return $tipos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
