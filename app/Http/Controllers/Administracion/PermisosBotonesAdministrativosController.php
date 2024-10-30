<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PermisosBotonesAdministrativosController extends Controller
{
    public function show(Request $req, $id)
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
        $usuariosPermisos = $loadDatos->getUsuariosPermisos($usuarioSelect, 'E');
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        // Botones Administrativos
        $botones = DB::table('permisos_botones')
            ->where('Estado', '1')
            ->get();
        $subBotones = DB::table('permisos_subbotones')
            ->where('Estado', '1')
            ->get();

        $permisosBotones = $botones->map(function ($item) use ($subBotones) {
            $resultado = $subBotones->where('IdPermiso_boton', $item->Id)->values();
            $item->SubBotones = $resultado;
            return $item;
        });

        $permisosBotonesDeUsuario = $this->getAllPermisosBotonesUsuario($id)->where('Estado', 'E')->pluck('IdPermiso_boton')->toArray();
        $permisosSubBotonesDeUsuario = $this->getAllPermisosSubBotonesUsuario($id)->where('Estado', 'E')->pluck('IdPermiso_subboton')->toArray();

        $array = ['permisos' => $permisos, 'usuarioSelect' => $usuarioSelect, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'permisosBotones' => $permisosBotones, 'permisosBotonesDeUsuario' => $permisosBotonesDeUsuario, 'permisosSubBotonesDeUsuario' => $permisosSubBotonesDeUsuario, 'idUsuario' => $id];
        return view('administracion/permisos/botonesAdministrativos', $array);
    }

    public function update(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        try {
            DB::beginTransaction();
            $fecha = now()->format('Y-m-d H:i:s');

            // ACTUALIZANDO E INSERTANDO PERMISOS BOTONES
            if ($req->permisosBotonesChekeados != '') {
                $allPermisosBotonesUsuario = $this->getAllPermisosBotonesUsuario($id)->pluck('IdPermiso_boton');
                $nuevosPermisosBotones = $req->permisosBotonesChekeados;
                $anterioresPermisosBotones = $this->getAllPermisosBotonesUsuario($id)->where('Estado', 'E')->pluck('IdPermiso_boton')->toArray();
                $permisosBotones = collect($nuevosPermisosBotones)->diff($anterioresPermisosBotones);

                $permisosBotonesInsertar = [];
                foreach ($permisosBotones as $key => $item) {
                    if ($allPermisosBotonesUsuario->search($item) === false) {
                        array_push($permisosBotonesInsertar, [
                            'IdPermiso_boton' => $item,
                            'IdUsuario' => $id,
                            'FechaAsignacion' => $fecha,
                        ]);
                    } else {
                        DB::table('permisos_botones_usuarios')
                            ->select('IdPermiso_boton')
                            ->where('IdUsuario', $id)
                            ->where('IdPermiso_boton', $item)
                            ->update(['FechaActualizacion' => $fecha, 'Estado' => 'E']);
                    }
                }
                DB::table('permisos_botones_usuarios')
                    ->insert($permisosBotonesInsertar);

                DB::table('permisos_botones_usuarios')
                    ->where('IdUsuario', $id)
                    ->whereNotIn('IdPermiso_boton', $nuevosPermisosBotones)
                    ->update(['FechaActualizacion' => $fecha, 'Estado' => 'D']);
            } else {
                DB::table('permisos_botones_usuarios')
                    ->where('IdUsuario', $id)
                    ->update(['FechaActualizacion' => $fecha, 'Estado' => 'D']);

            }

            // ACTUALIZANDO E INSERTANDO PERMISOS SUBBOTONES
            if ($req->permisosSubBotonesChekeados != '') {
                $allPermisosSubBotonesUsuario = $this->getAllPermisosSubBotonesUsuario($id)->pluck('IdPermiso_subboton');
                $nuevosPermisosSubtones = $req->permisosSubBotonesChekeados;

                $anterioresPermisosBotones = $this->getAllPermisosSubBotonesUsuario($id)->where('Estado', 'E')->pluck('IdPermiso_subboton')->toArray();
                $permisosSubBotones = collect($nuevosPermisosSubtones)->diff($anterioresPermisosBotones);
                $permisosSubBotones = $this->getDatosPermisosSubBotonesDelSistema($permisosSubBotones);

                $permisosSubBotonesInsertar = [];
                foreach ($permisosSubBotones as $key => $item) {
                    if ($allPermisosSubBotonesUsuario->search($item->IdPermiso_subboton) === false) {
                        array_push($permisosSubBotonesInsertar, array_merge((array) $item, ['IdUsuario' => $id, 'FechaAsignacion' => $fecha]));
                    } else {
                        DB::table('permisos_subbotones_usuarios')
                            ->select('IdPermiso_subboton')
                            ->where('IdUsuario', $id)
                            ->where('IdPermiso_subboton', $item->IdPermiso_subboton)
                            ->update(['FechaActualizacion' => $fecha, 'Estado' => 'E']);
                    }
                }
                DB::table('permisos_subbotones_usuarios')
                    ->insert($permisosSubBotonesInsertar);

                DB::table('permisos_subbotones_usuarios')
                    ->where('IdUsuario', $id)
                    ->whereNotIn('IdPermiso_subboton', $nuevosPermisosSubtones)
                    ->update(['FechaActualizacion' => $fecha, 'Estado' => 'D']);
            } else {
                DB::table('permisos_subbotones_usuarios')
                    ->where('IdUsuario', $id)
                    ->update(['FechaActualizacion' => $fecha, 'Estado' => 'D']);
            }
            DB::commit();
            return redirect()->route('permisos.botones.administrativos.show', $id);
        } catch (\Exception $e) {
            DB::rollback();
            dd('Fallo la transaccion');
        }
    }

    private function getAllPermisosBotonesUsuario($id)
    {
        $botones = DB::table('permisos_botones_usuarios')
            ->select('IdPermiso_boton', 'Estado')
            ->where('IdUsuario', $id)
            ->get();
        return $botones;
    }
    private function getAllPermisosSubBotonesUsuario($id)
    {
        $subBotones = DB::table('permisos_subbotones_usuarios')
            ->select('IdPermiso_subboton', 'Estado')
            ->where('IdUsuario', $id)
            ->get();
        return $subBotones;
    }

    private function getDatosPermisosBotonesDelSistema($idSubBtones)
    {
        try {
            $subBotones = DB::table('permisos_botones')
                ->select('Id as IdPermiso_boton')
                ->where('Estado', 1)
                ->whereIn('Id', $idSubBtones)
                ->get();
            return $subBotones;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function getDatosPermisosSubBotonesDelSistema($idSubBtones)
    {
        try {
            $subBotones = DB::table('permisos_subbotones')
                ->select('Id as IdPermiso_subboton', 'IdPermiso_boton')
                ->where('Estado', 1)
                ->whereIn('Id', $idSubBtones)
                ->get();
            return $subBotones;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
