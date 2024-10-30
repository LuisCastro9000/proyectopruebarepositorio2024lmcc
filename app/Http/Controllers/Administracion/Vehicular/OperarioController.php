<?php

namespace App\Http\Controllers\Administracion\Vehicular;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DB;
use Illuminate\Http\Request;
use Session;

class OperarioController extends Controller
{
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

            $operarios = $loadDatos->getOperarios($idSucursal);

            $array = ['operarios' => $operarios, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
            return view('administracion/vehicular/operario/index', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
        }
    }

    public function create(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $roles = $loadDatos->getRolesOperarios();
            $array = ['roles' => $roles, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
            return view('administracion/vehicular/operario/crear', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
        }
    }

    public function store(Request $req)
    {
        try {
            $this->validateOperario($req);
            $loadDatos = new DatosController();
            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
            $fecha = $loadDatos->getDateTime();
            $nombres = $req->nombres;
            $rol = $req->rol;

            $array = ['FechaCreacion' => $fecha, 'IdUsuario' => $idUsuario, 'IdSucursal' => $idSucursal, 'IdRolOperario' => $rol, 'Nombres' => $nombres, 'Estado' => 'E'];
            DB::table('operario')->insert($array);

            return redirect('administracion/vehicular/operario')->with('status', 'Se creo operario correctamente');
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
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $operario = $loadDatos->getOperarioSelect($id);
        $roles = $loadDatos->getRolesOperarios();
        $array = ['roles' => $roles, 'operario' => $operario, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('administracion/vehicular/operario/edit', $array);
    }

    public function update(Request $req, $id)
    {
        try {
            $this->validateOperario($req);
            $loadDatos = new DatosController();
            $idUsuario = Session::get('idUsuario');
            $nombres = $req->nombres;
            $rol = $req->rol;

            $fecha = $loadDatos->getDateTime();

            $array = ['FechaModificacion' => $fecha, 'IdUsuarioModificacion' => $idUsuario, 'IdRolOperario' => $rol, 'Nombres' => $nombres];
            DB::table('operario')
                ->where('IdOperario', $id)
                ->update($array);

            return redirect('administracion/vehicular/operario')->with('status', 'Se actualizo operario correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            $array = ['Estado' => 'D'];
            DB::table('operario')
                ->where('IdOperario', $id)
                ->update($array);

            return redirect('administracion/vehicular/operario')->with('status', 'Se elimino operario correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    protected function validateOperario(Request $request)
    {
        $this->validate($request, [
            'nombres' => 'required',
            'rol' => 'required',
        ]);
    }
}
