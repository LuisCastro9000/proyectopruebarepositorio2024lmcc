<?php

namespace App\Http\Controllers\Administracion\PlanesSuscripcion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Session;

class PlanesSuscripcionController extends Controller
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
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $planesSuscripcion = DB::table('planes_suscripcion')->get();

        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['permisos' => $permisos, 'usuarioSelect' => $usuarioSelect, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'planesSuscripcion' => $planesSuscripcion];

        return view('administracion/planesSuscripcion/index', $array);

    }

    public function edit(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        $loadDatos = new DatosController();
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $permisosDelSistema = $this->getPermisosDelSistema();
        $subPermisosDelSistema = $this->getSubPermisosDelSistema();
        $nivelesDelSistema = $this->getNivelesDelSistema();
        $subPermisosDelSistema = $subPermisosDelSistema->map(function ($item) use ($nivelesDelSistema) {
            $resultado = $nivelesDelSistema->where('IdSubPermiso', $item->IdSubPermiso)->values();
            $item->SubNiveles = $resultado;
            return $item;
        });
        $permisosDelSistema = $permisosDelSistema->map(function ($item) use ($subPermisosDelSistema) {
            $resultado = $subPermisosDelSistema->where('IdPermiso', $item->IdPermiso)->values();
            $item->SubPermisos = $resultado;
            return $item;
        });

        // MODULOS ACTIVOS DEL PLAN DE SUSCRIPCION
        $arrayPermisosActivosDelPlan = $this->getPermisosActivadosPlanSuscripcion($id)->pluck('IdPermiso')->toArray();
        $arraySubPermisosActivosDelPlan = $this->getSubPermisosActivadosPlanSuscripcion($id)->pluck('IdSubPermiso')->toArray();
        $arraySubNivelesActivosDelPlan = $this->getSubNivelesActivadosPlanSuscripcion($id)->pluck('IdSubNivel')->toArray();

        // PERMISOS BOTONES ADMINISTRATIVOS DEL  SISTEMA
        $permisosBotones = $loadDatos->getAllPermisosBotonesDelSistema();
        $permisosSubBotones = $loadDatos->getAllPermisosSubBotonesDelSistema();

        $permisosBotonesDelSistema = $permisosBotones->map(function ($item) use ($permisosSubBotones) {
            $resultado = $permisosSubBotones->where('IdPermisoBoton', $item->Id)->values();
            $item->SubBotones = $resultado;
            return $item;
        });

        // PERMISOS BOTONES ACTIVOS DEL PLAN DE SUSCRIPCION
        $arrayPermisosBotonesHabilitados = $loadDatos->getAllPermisosBotonesPlanSucripcion($id)->where('Estado', 'E')->pluck('IdPermisoBoton')->toArray();
        $arrayPermisosSubBotonesHabilitados = $loadDatos->getAllPermisosSubBotonesPlanSucripcion($id)->where('Estado', 'E')->pluck('IdPermisoSubBoton')->toArray();
        // dd($permisosSubBotonesHabilitados);

        // Nuevo codigo Modulos
        $modulosDelSistema = $loadDatos->getModulos();
        $modulosPlanSuscripcion = DB::table('modulo_planSuscripcion')->where('IdPlanSuscripcion', $id)->where('Estado', 'E')->pluck('IdModulo')->toArray();
        // fin

        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'permisosDelSistema' => $permisosDelSistema, 'idPlanSuscripcion' => $id, 'arrayPermisosHabilitados' => $arrayPermisosActivosDelPlan, 'arraySubPermisosHabilitados' => $arraySubPermisosActivosDelPlan, 'arraySubNivelesHabilitados' => $arraySubNivelesActivosDelPlan, 'arrayPermisosBotonesHabilitados' => $arrayPermisosBotonesHabilitados, 'arrayPermisosSubBotonesHabilitados' => $arrayPermisosSubBotonesHabilitados, 'permisosBotonesDelSistema' => $permisosBotonesDelSistema, 'modulosDelSistema' => $modulosDelSistema, 'modulosChekeados' => $modulosPlanSuscripcion];

        return view('administracion/planesSuscripcion/editar', $array);
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
            $loadDatos = new DatosController();
            $fecha = Carbon::now()->toDateTimeString();
            $arrayPermisosActualizados = $req->permisos;
            $arraySubPermisosActualizados = $req->subPermisos;
            $arraySubNivelesActualizados = $req->subNiveles;

            // CODIGO PARA AGREGAR NUEVOS MODULOS AL PLAN
            if ($arrayPermisosActualizados != null) {
                $allPermisosPlanSuscripcion = $this->getPermisosActivadosYdesactivadosPlanSuscripcion($id)->pluck('IdPermiso');
                foreach ($arrayPermisosActualizados as $idPermiso) {
                    if (!is_numeric($allPermisosPlanSuscripcion->search($idPermiso))) {
                        DB::table('permiso_planSuscripcion')
                            ->insert(['IdPermiso' => $idPermiso, 'IdPlanSuscripcion' => $id, 'Estado' => 'E', 'FechaAsignacion' => $fecha]);
                    }
                }
            }

            if ($arraySubPermisosActualizados != null) {
                $allSubPermisosPlanSuscripcion = $this->getSubPermisosActivadosYdesactivadosPlanSuscripcion($id)->pluck('IdSubPermiso');
                $arrayDatosSubPermisosActualizados = $this->getDatosSubPermisosDelSistema($arraySubPermisosActualizados);
                foreach ($arrayDatosSubPermisosActualizados as $subPermiso) {
                    if (!is_numeric($allSubPermisosPlanSuscripcion->search($subPermiso->IdSubPermiso))) {
                        DB::table('subPermiso_planSuscripcion')
                            ->insert(['IdPermiso' => $subPermiso->IdPermiso, 'IdSubPermiso' => $subPermiso->IdSubPermiso, 'IdPlanSuscripcion' => $id, 'Estado' => 'E', 'FechaAsignacion' => $fecha]);
                    }
                }
            }

            if ($arraySubNivelesActualizados != null) {
                $subNivelesActualizados = $req->subNiveles;
                $allSubNivelesPlanSuscripcion = $this->getSubNivelesActivadosYdesactivadosPlanSuscripcion($id)->pluck('IdSubNivel');
                $arrayDatosSubNivelesActualizados = $this->getDatosSubNivelesDelSistema($arraySubNivelesActualizados);
                foreach ($arrayDatosSubNivelesActualizados as $subNivel) {
                    if (!is_numeric($allSubNivelesPlanSuscripcion->search($subNivel->IdSubNivel))) {
                        DB::table('subNivel_planSuscripcion')
                            ->insert(['IdSubPermiso' => $subNivel->IdSubPermiso, 'IdSubNivel' => $subNivel->IdSubNivel, 'IdPlanSuscripcion' => $id, 'Estado' => 'E', 'FechaAsignacion' => $fecha]);
                    }
                }
            }

            // CODIGO PARA CAMBIAR ESTADO DE LOS MODULOS QUE HAN SIDO ELIMINADOS O NUEVAMENTE AGREGADOS
            $allPermisosActivadosPlanSuscripcion = $this->getPermisosActivadosPlanSuscripcion($id)->pluck('IdPermiso')->toArray();
            $permisosEliminados = collect($allPermisosActivadosPlanSuscripcion)->diff($arrayPermisosActualizados);
            $permisosAgregados = collect($arrayPermisosActualizados)->diff($allPermisosActivadosPlanSuscripcion);
            if (!empty($permisosEliminados)) {
                DB::table('permiso_planSuscripcion')
                    ->where('IdPlanSuscripcion', $id)
                    ->whereIn('IdPermiso', $permisosEliminados)
                    ->update(['estado' => 'D']);
            }

            if (!empty($permisosAgregados)) {
                DB::table('permiso_planSuscripcion')
                    ->where('IdPlanSuscripcion', $id)
                    ->whereIn('IdPermiso', $permisosAgregados)
                    ->update(['estado' => 'E']);
            }

            $allSubPermisosActivadosPlanSuscripcion = $this->getSubPermisosActivadosPlanSuscripcion($id)->pluck('IdSubPermiso')->toArray();
            $subPermisosEliminados = collect($allSubPermisosActivadosPlanSuscripcion)->diff($arraySubPermisosActualizados);
            $subPermisosAgregados = collect($arraySubPermisosActualizados)->diff($allSubPermisosActivadosPlanSuscripcion);
            if (!empty($subPermisosEliminados)) {
                DB::table('subPermiso_planSuscripcion')
                    ->where('IdPlanSuscripcion', $id)
                    ->whereIn('IdSubPermiso', $subPermisosEliminados)
                    ->update(['estado' => 'D']);
            }

            if (!empty($subPermisosAgregados)) {
                DB::table('subPermiso_planSuscripcion')
                    ->where('IdPlanSuscripcion', $id)
                    ->whereIn('IdSubPermiso', $subPermisosAgregados)
                    ->update(['estado' => 'E']);
            }

            $allSubNivelesActivadosPlanSuscripcion = $this->getSubNivelesActivadosPlanSuscripcion($id)->pluck('IdSubNivel')->toArray();
            $subNivelesEliminados = collect($allSubNivelesActivadosPlanSuscripcion)->diff($arraySubNivelesActualizados);
            $subNivelesAgregados = collect($arraySubNivelesActualizados)->diff($allSubNivelesActivadosPlanSuscripcion);
            if (!empty($subNivelesEliminados)) {
                DB::table('subNivel_planSuscripcion')
                    ->where('IdPlanSuscripcion', $id)
                    ->whereIn('IdSubNivel', $subNivelesEliminados)
                    ->update(['estado' => 'D']);
            }

            if (!empty($subNivelesAgregados)) {
                DB::table('subNivel_planSuscripcion')
                    ->where('IdPlanSuscripcion', $id)
                    ->whereIn('IdSubNivel', $subNivelesAgregados)
                    ->update(['estado' => 'E']);
            }

            // ACTUALIZAR PERMISOS BOTONES ADMINISTRATIVO
            // ACTUALIZANDO E INSERTANDO PERMISOS BOTONES
            if ($req->permisosBotonesChekeados != '') {
                $allPermisosBotonesPlanSucripcion = $loadDatos->getAllPermisosBotonesPlanSucripcion($id);
                $arrayIdsPermisosPlanSucripcion = $allPermisosBotonesPlanSucripcion->pluck('IdPermisoBoton');
                $anterioresPermisosBotones = $allPermisosBotonesPlanSucripcion->where('Estado', 'E')->pluck('IdPermisoBoton')->toArray();
                $nuevosPermisosBotones = $req->permisosBotonesChekeados;
                $permisosBotones = collect($nuevosPermisosBotones)->diff($anterioresPermisosBotones);

                $permisosBotonesInsertar = [];
                foreach ($permisosBotones as $key => $item) {
                    if ($arrayIdsPermisosPlanSucripcion->search($item) === false) {
                        array_push($permisosBotonesInsertar, [
                            'IdPermisoBoton' => $item,
                            'IdPlanSuscripcion' => $id,
                            'FechaAsignacion' => $fecha,
                        ]);
                    } else {
                        DB::table('permisos_botones_plan_suscripciones')
                            ->select('IdPermisoBoton')
                            ->where('IdPlanSuscripcion', $id)
                            ->where('IdPermisoBoton', $item)
                            ->update(['FechaActualizacion' => $fecha, 'Estado' => 'E']);
                    }
                }
                DB::table('permisos_botones_plan_suscripciones')
                    ->insert($permisosBotonesInsertar);

                DB::table('permisos_botones_plan_suscripciones')
                    ->where('IdPlanSuscripcion', $id)
                    ->whereNotIn('IdPermisoBoton', $nuevosPermisosBotones)
                    ->update(['FechaActualizacion' => $fecha, 'Estado' => 'D']);
            } else {
                DB::table('permisos_botones_plan_suscripciones')
                    ->where('IdPlanSuscripcion', $id)
                    ->update(['FechaActualizacion' => $fecha, 'Estado' => 'D']);
            }

            // ACTUALIZANDO E INSERTANDO PERMISOS SUBBOTONES
            if ($req->permisosSubBotonesChekeados != '') {
                $allSubPermisosBotonesPlanSucripcion = $loadDatos->getAllPermisosSubBotonesPlanSucripcion($id);
                $arrayIdsSubPermisosPlanSucripcion = $allSubPermisosBotonesPlanSucripcion->pluck('IdPermisoSubBoton');
                $anterioresPermisoSubBotones = $allSubPermisosBotonesPlanSucripcion->where('Estado', 'E')->pluck('IdPermisoSubBoton')->toArray();
                $nuevosPermisosSubBotones = $req->permisosSubBotonesChekeados;
                $permisosSubBotones = collect($nuevosPermisosSubBotones)->diff($anterioresPermisoSubBotones);
                $permisosSubBotones = $loadDatos->getDatosPermisosSubBotonesDelSistema($permisosSubBotones);
                $permisosSubBotonesInsertar = [];
                foreach ($permisosSubBotones as $key => $item) {
                    if ($arrayIdsSubPermisosPlanSucripcion->search($item->IdPermisoSubBoton) === false) {
                        array_push($permisosSubBotonesInsertar, array_merge((array) $item, ['IdPlanSuscripcion' => $id, 'FechaAsignacion' => $fecha]));
                    } else {
                        DB::table('permisos_subbotones_plan_suscripciones')
                            ->select('IdPermisoSubBoton')
                            ->where('IdPlanSuscripcion', $id)
                            ->where('IdPermisoSubBoton', $item->IdPermisoSubBoton)
                            ->update(['FechaActualizacion' => $fecha, 'Estado' => 'E']);
                    }
                }
                DB::table('permisos_subbotones_plan_suscripciones')
                    ->insert($permisosSubBotonesInsertar);

                DB::table('permisos_subbotones_plan_suscripciones')
                    ->where('IdPlanSuscripcion', $id)
                    ->whereNotIn('IdPermisoSubBoton', $nuevosPermisosSubBotones)
                    ->update(['FechaActualizacion' => $fecha, 'Estado' => 'D']);
            } else {
                DB::table('permisos_subbotones_plan_suscripciones')
                    ->where('IdPlanSuscripcion', $id)
                    ->update(['FechaActualizacion' => $fecha, 'Estado' => 'D']);
            }

            // ACTUALIZANDO PERMISOS MODULOS
            $modulosPlanSuscripcion = DB::table('modulo_planSuscripcion')->where('IdPlanSuscripcion', $id)->get();
            $modulosChekeados = $req->modulosChekeados;
            if (collect($modulosChekeados)->isNotEmpty()) {
                foreach ($modulosChekeados as $idModulo) {
                    $moduloEncontrado = $modulosPlanSuscripcion->firstWhere('IdModulo', $idModulo);
                    if ($moduloEncontrado) {
                        if ($moduloEncontrado->Estado === 'D') {
                            DB::table('modulo_planSuscripcion')
                                ->where('IdPlanSuscripcion', $id)
                                ->where('IdModulo', $idModulo)
                                ->update(['FechaActualizacion' => Carbon::now()->toDateTimeString(), 'Estado' => 'E']);
                        }
                    } else {
                        DB::table('modulo_planSuscripcion')
                            ->insert(['IdModulo' => $idModulo, 'IdPlanSuscripcion' => $id, 'Estado' => 'E', 'FechaAsignacion' => Carbon::now()->toDateTimeString()]);
                    }
                }
                DB::table('modulo_planSuscripcion')
                    ->where('IdPlanSuscripcion', $id)
                    ->whereNotIn('IdModulo', $modulosChekeados)
                    ->where('Estado', 'E')
                    ->update(['FechaActualizacion' => Carbon::now()->toDateTimeString(), 'Estado' => 'D']);
            } else {
                DB::table('modulo_planSuscripcion')
                    ->where('IdPlanSuscripcion', $id)
                    ->update(['FechaActualizacion' => Carbon::now()->toDateTimeString(), 'Estado' => 'D']);
            }

            DB::commit();
            return redirect()->route('planesSuscripcion.index')->with('success', 'El plan de suscripción se actualizó Correctamente');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Ocurrio un error, comunicarse con el area de soporte');
        }
    }

    public function getPermisosActivadosPlanSuscripcion($id)
    {
        try {
            $datos = DB::table('permiso_planSuscripcion')
                ->where('Estado', 'E')
                ->where('IdPlanSuscripcion', $id)
                ->get();
            return $datos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getSubPermisosActivadosPlanSuscripcion($id)
    {
        try {
            $datos = DB::table('subPermiso_planSuscripcion')
                ->where('Estado', 'E')
                ->where('IdPlanSuscripcion', $id)
                ->get();
            return $datos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getSubNivelesActivadosPlanSuscripcion($id)
    {
        try {
            $datos = DB::table('subNivel_planSuscripcion')
                ->where('Estado', 'E')
                ->where('IdPlanSuscripcion', $id)
                ->get();
            return $datos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // MODULOS ACTIVOS Y DESACTIVADOS DEL PLAN SUSCRIPCION
    public function getPermisosActivadosYdesactivadosPlanSuscripcion($id)
    {
        try {
            $datos = DB::table('permiso_planSuscripcion')
                ->where('IdPlanSuscripcion', $id)
                ->get();
            return $datos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getSubPermisosActivadosYdesactivadosPlanSuscripcion($id)
    {
        try {
            $datos = DB::table('subPermiso_planSuscripcion')
                ->where('IdPlanSuscripcion', $id)
                ->get();
            return $datos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getSubNivelesActivadosYdesactivadosPlanSuscripcion($id)
    {
        try {
            $datos = DB::table('subNivel_planSuscripcion')
                ->where('IdPlanSuscripcion', $id)
                ->get();
            return $datos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // MODULOS DEL SISTEMA
    public function getPermisosDelSistema()
    {
        try {
            $permisos = DB::table('permiso')
                ->where('Estado', 'E')
                ->get();
            return $permisos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    public function getSubPermisosDelSistema()
    {
        try {
            $subPermisos = DB::table('sub_permisos')
                ->where('Estado', 'E')
                ->get();
            return $subPermisos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    public function getNivelesDelSistema()
    {
        try {
            $subPermisos = DB::table('sub_nivel')
                ->where('estado', 'E')
                ->get();
            return $subPermisos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // OBTENER DATOS DE LOS MODULOS
    public function getDatosSubPermisosDelSistema($idSubPermiso)
    {
        try {
            $subPermisos = DB::table('sub_permisos')
                ->where('Estado', 'E')
                ->whereIn('IdSubPermiso', $idSubPermiso)
                ->get();
            return $subPermisos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }

    }
    public function getDatosSubNivelesDelSistema($idSubNivel)
    {
        try {
            $subNiveles = DB::table('sub_nivel')
                ->where('Estado', 'E')
                ->whereIn('IdSubNivel', $idSubNivel)
                ->get();
            return $subNiveles;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }

    }
}
