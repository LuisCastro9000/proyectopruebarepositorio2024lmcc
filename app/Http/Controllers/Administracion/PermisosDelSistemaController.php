<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DB;
use Illuminate\Http\Request;
use Session;

class PermisosDelSistemaController extends Controller
{
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $permisos = $loadDatos->getAllPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $permisosDelSuperAdmin = $this->getPermisosDelSuperAdmin();
        $subPermisosDelSuperAdmin = $this->getSubPermisosDelSuperAdmin();
        $nivelesDelSuperAdmin = $this->getNivelesDelSuperAdmin();
        
        $subPermisosDelSuperAdmin = $subPermisosDelSuperAdmin->map(function ($item) use ($nivelesDelSuperAdmin) {
            $resultado = $nivelesDelSuperAdmin->where('IdSubPermiso', $item->IdSubPermiso)->values();
            $item->SubPermisos = $resultado;
            return $item;
        });
        $permisosDelSuperAdmin = $permisosDelSuperAdmin->map(function ($item) use ($subPermisosDelSuperAdmin) {
            $resultado = $subPermisosDelSuperAdmin->where('IdPermiso', $item->IdPermiso)->values();
            $item->Permisos = $resultado;
            return $item;
        });
        // dd($idUsuario);
        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'permisosDelSistema' => $permisosDelSuperAdmin];
        return view('administracion/permisosDelSistema/permisos', $array);

    }

    public function store(Request $req)
    {
        $idPermiso = $req->permiso;
        $idSubPermiso = $req->subPermiso;
        $descripcion = $req->descripcion;
        $tipoPermiso = $req->tipoPermiso;

        if ($tipoPermiso == 'permiso' && $descripcion != null) {
            $datos = ['Descripcion' => $descripcion, 'Estado' => 'E'];
            DB::table('permiso')->insert($datos);
        }

        if ($tipoPermiso == 'subPermiso' && $descripcion != null) {
            $datos = ['Descripcion' => $descripcion, 'IdPermiso' => $idPermiso, 'estado' => 'E'];
            DB::table('sub_permisos')->insert($datos);
        }

        if ($tipoPermiso == 'subNivel' && $descripcion != null) {
            $datos = ['DetalleNivel' => $descripcion, 'IdSubPermiso' => $idSubPermiso, 'estado' => 'E'];
            DB::table('sub_nivel')->insert($datos);
        }
        return redirect('administracion/permisos-del-sistema')->with('creado', "El $tipoPermiso se creo correctamente");
    }

    public function eliminarPermisos(Request $req)
    {
        $permisos = $req->permisos;
        $subPermisos = $req->subPermisos;
        $subNiveles = $req->subNiveles;

        if ($permisos !== null) {
            DB::table('permiso')
                ->whereIn('Idpermiso', $permisos)
                ->update(['Estado' => 'E']);
            DB::table('usuario_permisos')
                ->whereIn('Idpermiso', $permisos)
                ->update(['Estado' => 'E']);
        }

        if ($subPermisos !== null) {
            DB::table('sub_permisos')
                ->whereIn('IdSubPermiso', $subPermisos)
                ->update(['Estado' => 'D']);

            DB::table('usuario_sub_permisos')
                ->whereIn('IdSubPermisos', $subPermisos)
                ->update(['estado' => 'D']);
        }

        if ($subNiveles !== null) {
            DB::table('sub_nivel')
                ->whereIn('IdSubNivel', $subNiveles)
                ->update(['Estado' => 'D']);

            DB::table('usuario_sub_nivel')
                ->whereIn('IdSubNivel', $subNiveles)
                ->update(['Estado' => 'D']);
        }
        return redirect('administracion/permisos-del-sistema')->with('success', 'Se eliminaron los permisos correctamente');

    }

    public function getPermisos(Request $req)
    {
        if ($req->ajax()) {
            $tipoPermiso = $req->tipoPermiso;
            $idPermiso = $req->idPermiso;

            if ($tipoPermiso == 'permiso') {
                $subPermisos = DB::table('sub_permisos')
                    ->where('IdPermiso', $idPermiso)
                    ->where('Estado', 'E')
                    ->get();
                return ($subPermisos);
            }

            if ($tipoPermiso == 'subPermiso') {
                $permisos = DB::table('permiso')
                    ->where('Estado', 'E')
                    ->get();
                return Response([$tipoPermiso, $permisos]);
            }

            if ($tipoPermiso == 'subNivel') {
                $permisos = DB::table('permiso')
                    ->where('Estado', 'E')
                    ->get();

                $subPermisos = DB::table('sub_permisos')
                    ->where('IdPermiso', 1)
                    ->where('Estado', 'E')
                    ->get();
                return Response([$tipoPermiso, $permisos, $subPermisos]);
            }

        }
    }

    public function getPermisosDelSuperAdmin()
    {
        try {
            $permisos = DB::table('permiso')
            // ->where('Estado', 'E')
                ->get();

            return $permisos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getSubPermisosDelSuperAdmin()
    {
        try {
            $subPermisos = DB::table('sub_permisos')
            // ->where('Estado', 'E')
                ->get();
            return $subPermisos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getNivelesDelSuperAdmin()
    {
        try {
            $subPermisos = DB::table('sub_nivel')
            // ->where('estado', 'E')
                ->get();

            return $subPermisos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
