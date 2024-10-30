<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Session;

class PermisosController extends Controller
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
        $usuariosPermisos = $loadDatos->getUsuariosPermisos($usuarioSelect, 'E');
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        // for ($i = 0; $i < count($usuariosPermisos);
        //     $i++) {
        //     $_permisos = $loadDatos->getPermisos($usuariosPermisos[$i]->IdUsuario);
        //     $usuariosPermisos[$i]->Permisos = $_permisos;
        // }
        // dd($usuarioSelect);
        $array = ['usuariosPermisos' => $usuariosPermisos, 'permisos' => $permisos, 'usuarioSelect' => $usuarioSelect, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'idUsuario' => $idUsuario];
        return view('administracion/permisos/permisos', $array);
    }

    public function create(Request $req)
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
        $usuarios = $loadDatos->getUsuariosPermisos($usuarioSelect, 'D');
        $permisos = $loadDatos->getAllPermisos();

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['usuarios' => $usuarios, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('administracion/permisos/agregarPermisos', $array);
    }

    public function store(Request $req)
    {
        try {
            $idUsuario = $req->usuario;
            if ($idUsuario == 0) {
                return back()->with('error', 'No se selecciono usuario');
            }
            if ($req->permisos !== null) {
                /*for ( $i = 0; $i<count( $req->permisos );
                $i++ ) {
                $array = [ 'IdUsuario' => $idUsuario, 'IdPermiso' => $req->permisos[ $i ] ];
                DB::table( 'usuario_permisos' )->insert( $array );
                }
                 */
                DB::table('usuario')
                    ->where('IdUsuario', $idUsuario)
                    ->update(['Estado' => 'E']);
            } else {
                return back()->with('error', 'Por favor, agregue permisos antes de guardar');
            }
            return redirect('administracion/permisos')->with('status', 'Se agregaron permisos correctamente');
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
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $usuario = $loadDatos->getUsuarioSelect($id);
        $permisos = $loadDatos->getAllPermisos();
        $usuarioPermisos = $loadDatos->getPermisos($id);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $modulosSelect = $loadDatos->getModulosSelect($usuario->CodigoCliente);
        $arrayPermisos = [];
        for ($i = 0; $i < count($usuarioPermisos);
            $i++) {
            array_push($arrayPermisos, $usuarioPermisos[$i]->IdPermiso);
        }
        // dd($permisos);
        $array = ['usuario' => $usuario, 'permisos' => $permisos, 'usuarioPermisos' => $arrayPermisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('administracion/permisos/editarPermisos', $array);
    }

    // Nuevas Funciones Para insertar Permisos
    private function getAllPermisosUsuarioSeleccionado($id)
    {
        try {
            $permisos = DB::table('usuario_permisos')
                ->where('usuario_permisos.IdUsuario', $id)
                ->get();
            return $permisos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    private function getAllSubPermisosUsuarioSeleccionado($id)
    {
        try {
            $subPermisos = DB::table('usuario_sub_permisos')
                ->where('usuario_sub_permisos.IdUsuario', $id)
                ->get();
            return $subPermisos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    private function getAllSubNivelesUsuarioSeleccionado($id)
    {
        try {
            $subNiveles = DB::table('usuario_sub_nivel')
                ->where('usuario_sub_nivel.IdUsuario', $id)
                ->get();
            return $subNiveles;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function subPermisosSistema($idSubPermiso)
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

    private function subNivelesSistema($idSubNivel)
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

    public function update(Request $req, $id)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                $idUsuario = Session::get('idUsuario');
            } else {
                Session::flush();
                return redirect('/')->with('out', 'Sesión de usuario Expirado');
            }
            try {
                DB::beginTransaction();
                $loadDatos = new DatosController();
                $idAdministrador = $loadDatos->getUsuarioSelect($idUsuario)->IdOperador;
                $usuarioSelect = $loadDatos->getUsuarioSelect($id)->CodigoCliente;

                // Codigo para Insertar Permiso si no esta registrado en la BD
                // Con las Funciones $this->subPermisosSistema | $this->subNivelesSistema: Obtenemos los datos
                $allIdPermisos = $this->getAllPermisosUsuarioSeleccionado($id)->pluck('IdPermiso');
                $allIdSubPermisos = $this->getAllSubPermisosUsuarioSeleccionado($id)->pluck('IdSubPermisos');
                $allIdSubNiveles = $this->getAllSubNivelesUsuarioSeleccionado($id)->pluck('IdSubNivel');

                if ($req->subPermisos != null) {
                    $subPermisosActualizados = $this->subPermisosSistema($req->subPermisos);
                    foreach ($subPermisosActualizados as $subPermiso) {
                        if (!is_numeric($allIdSubPermisos->search($subPermiso->IdSubPermiso))) {
                            DB::table('usuario_sub_permisos')
                                ->insert(['IdUsuario' => $id, 'Permiso' => $subPermiso->IdPermiso, 'IdSubPermisos' => $subPermiso->IdSubPermiso, 'Estado' => 'E']);
                        }
                    }
                }
                if ($req->subNiveles != null) {
                    $subNivelesActualizados = $this->subNivelesSistema($req->subNiveles);
                    foreach ($subNivelesActualizados as $subNiveles) {
                        if (!is_numeric($allIdSubNiveles->search($subNiveles->IdSubNivel))) {
                            DB::table('usuario_sub_nivel')
                                ->insert(['IdUsuario' => $id, 'IdSubPermiso' => $subNiveles->IdSubPermiso, 'IdSubNivel' => $subNiveles->IdSubNivel, 'Estado' => 'E']);
                        }
                    }
                }
                if ($req->permisos != null) {
                    $permisosActualizados = $req->permisos;
                    foreach ($permisosActualizados as $idPermiso) {
                        if (!is_numeric($allIdPermisos->search($idPermiso))) {
                            DB::table('usuario_permisos')
                                ->insert(['IdUsuario' => $id, 'IdPermiso' => $idPermiso, 'Estado' => 'E']);
                        }
                    }
                }
                // Fin

                if ($idAdministrador == 1) {
                    $idUsuariosSecundarios = DB::table('usuario')
                        ->select('IdUsuario')
                        ->where('CodigoCliente', $usuarioSelect)
                        ->get();
                } else {
                    $idUsuariosSecundarios = DB::table('usuario')
                        ->select('IdUsuario')
                        ->where('CodigoCliente', $usuarioSelect)
                        ->whereNotIn('IdUsuario', [$idUsuario])
                        ->where('IdUsuario', $id)
                        ->get();
                }
                $idUsuariosSecundarios = $idUsuariosSecundarios->pluck('IdUsuario')->toArray();

                $permisos = $this->getPermisosDelUsuarioPrincipal($id)->pluck('IdPermiso')->toArray();
                $subPermisos = $this->getSubPermisosDelUsuarioPrincipal($id)->pluck('IdSubPermiso')->toArray();
                $subNiveles = $this->getSubNivelesDelUsuarioPrincipal($id)->pluck('IdSubNivel')->toArray();

                $permisosActualizados = $req->permisos;
                $subPermisosActualizados = $req->subPermisos;
                $subNivelesActualizados = $req->subNiveles;

                $permisosEliminados = collect($permisos)->diff($permisosActualizados);
                $permisosAgregados = collect($permisosActualizados)->diff($permisos);

                $subPermisosEliminados = collect($subPermisos)->diff($subPermisosActualizados);
                $subPermisosAgregados = collect($subPermisosActualizados)->diff($subPermisos);

                $subNivelesEliminados = collect($subNiveles)->diff($subNivelesActualizados);
                $subNivelesAgregados = collect($subNivelesActualizados)->diff($subNiveles);

                if (!empty($permisosEliminados)) {
                    DB::table('usuario_permisos')
                        ->whereIn('IdUsuario', $idUsuariosSecundarios)
                        ->whereIn('IdPermiso', $permisosEliminados)
                        ->update(['estado' => 'D']);
                }

                if (!empty($permisosAgregados)) {
                    DB::table('usuario_permisos')
                        ->where('IdUsuario', $id)
                        ->whereIn('IdPermiso', $permisosAgregados)
                        ->update(['estado' => 'E']);
                }

                if (!empty($subPermisosEliminados)) {
                    DB::table('usuario_sub_permisos')
                        ->whereIn('IdUsuario', $idUsuariosSecundarios)
                        ->whereIn('IdSubPermisos', $subPermisosEliminados)
                        ->update(['estado' => 'D']);
                }

                if (!empty($subPermisosAgregados)) {
                    DB::table('usuario_sub_permisos')
                        ->where('IdUsuario', $id)
                        ->whereIn('IdSubPermisos', $subPermisosAgregados)
                        ->update(['estado' => 'E']);
                }

                if (!empty($subNivelesEliminados)) {
                    DB::table('usuario_sub_nivel')
                        ->whereIn('IdUsuario', $idUsuariosSecundarios)
                        ->whereIn('IdSubNivel', $subNivelesEliminados)
                        ->update(['estado' => 'D']);
                }

                if (!empty($subNivelesAgregados)) {
                    DB::table('usuario_sub_nivel')
                        ->where('IdUsuario', $id)
                        ->whereIn('IdSubNivel', $subNivelesAgregados)
                        ->update(['estado' => 'E']);
                }

                // ACTUALIZAR PERMISOS BOTONES ADMINISTRATIVOS
                $fecha = now()->format('Y-m-d H:i:s');
                // ACTUALIZANDO E INSERTANDO PERMISOS BOTONES
                if ($req->permisosBotonesChekeados != '') {
                    $allPermisosBotonesUsuario = $loadDatos->getAllPermisosBotonesUsuario($id)->pluck('IdPermisoBoton');
                    $nuevosPermisosBotones = $req->permisosBotonesChekeados;
                    $anterioresPermisosBotones = $loadDatos->getAllPermisosBotonesUsuario($id)->where('Estado', 'E')->pluck('IdPermisoBoton')->toArray();
                    $permisosBotones = collect($nuevosPermisosBotones)->diff($anterioresPermisosBotones);

                    $permisosBotonesInsertar = [];
                    foreach ($permisosBotones as $key => $item) {
                        if ($allPermisosBotonesUsuario->search($item) === false) {
                            array_push($permisosBotonesInsertar, [
                                'IdPermisoBoton' => $item,
                                'IdUsuario' => $id,
                                'FechaAsignacion' => $fecha,
                            ]);
                        } else {
                            DB::table('permisos_botones_usuarios')
                                ->select('IdPermisoBoton')
                                ->where('IdUsuario', $id)
                                ->where('IdPermisoBoton', $item)
                                ->update(['FechaActualizacion' => $fecha, 'Estado' => 'E']);
                        }
                    }
                    DB::table('permisos_botones_usuarios')
                        ->insert($permisosBotonesInsertar);

                    DB::table('permisos_botones_usuarios')
                        ->where('IdUsuario', $id)
                        ->whereNotIn('IdPermisoBoton', $nuevosPermisosBotones)
                        ->update(['FechaActualizacion' => $fecha, 'Estado' => 'D']);
                } else {
                    DB::table('permisos_botones_usuarios')
                        ->where('IdUsuario', $id)
                        ->update(['FechaActualizacion' => $fecha, 'Estado' => 'D']);

                }

                // ACTUALIZANDO E INSERTANDO PERMISOS SUBBOTONES
                if ($req->permisosSubBotonesChekeados != '') {
                    $allPermisosSubBotonesUsuario = $loadDatos->getAllPermisosSubBotonesUsuario($id)->pluck('IdPermisoSubBoton');
                    $nuevosPermisosSubtones = $req->permisosSubBotonesChekeados;

                    $anterioresPermisosBotones = $loadDatos->getAllPermisosSubBotonesUsuario($id)->where('Estado', 'E')->pluck('IdPermisoSubBoton')->toArray();
                    $permisosSubBotones = collect($nuevosPermisosSubtones)->diff($anterioresPermisosBotones);
                    $permisosSubBotones = $loadDatos->getDatosPermisosSubBotonesDelSistema($permisosSubBotones);

                    $permisosSubBotonesInsertar = [];
                    foreach ($permisosSubBotones as $key => $item) {
                        if ($allPermisosSubBotonesUsuario->search($item->IdPermisoSubBoton) === false) {
                            array_push($permisosSubBotonesInsertar, array_merge((array) $item, ['IdUsuario' => $id, 'FechaAsignacion' => $fecha]));
                        } else {
                            DB::table('permisos_subbotones_usuarios')
                                ->select('IdPermisoSubBoton')
                                ->where('IdUsuario', $id)
                                ->where('IdPermisoSubBoton', $item->IdPermisoSubBoton)
                                ->update(['FechaActualizacion' => $fecha, 'Estado' => 'E']);
                        }
                    }
                    DB::table('permisos_subbotones_usuarios')
                        ->insert($permisosSubBotonesInsertar);

                    DB::table('permisos_subbotones_usuarios')
                        ->where('IdUsuario', $id)
                        ->whereNotIn('IdPermisoSubBoton', $nuevosPermisosSubtones)
                        ->update(['FechaActualizacion' => $fecha, 'Estado' => 'D']);
                } else {
                    DB::table('permisos_subbotones_usuarios')
                        ->where('IdUsuario', $id)
                        ->update(['FechaActualizacion' => $fecha, 'Estado' => 'D']);
                }

                DB::commit();
                return redirect('administracion/permisos')->with('status', 'Se actualizaron los permisos correctamente');
            } catch (\Exception $e) {
                DB::rollback();
                return redirect('administracion/permisos')->with('error', 'Ocurrio un error, comunicarse con el area de soporte');
            }

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // public function update(Request $req, $id)
    // {
    //     try {
    //         if ($req->permisos !== null) {
    //             DB::table('usuario_permisos')
    //                 ->where('IdUsuario', $id)
    //                 ->delete();
    //             for ($i = 0; $i < count($req->permisos);
    //                 $i++) {
    //                 $array = ['IdUsuario' => $id, 'IdPermiso' => $req->permisos[$i]];
    //                 DB::table('usuario_permisos')->insert($array);
    //             }
    //         } else {
    //             return back()->with('error', 'Por favor, agregue permisos antes de actualizar');
    //         }
    //         dd($id);

    //         return redirect('administracion/permisos')->with('status', 'Se actualizaron permisos correctamente');

    //     } catch (Exception $ex) {
    //         echo $ex->getMessage();
    //     }
    // }

    public function seleccionarModulos(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $usuarioSelect = $loadDatos->getUsuarioSelect($id);
        $usuarioModulos = $loadDatos->getUsuarioModulos($id);
        $modulos = $loadDatos->getModulos();
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $arrayModulos = [];
        if (count($usuarioModulos) > 0) {
            for ($i = 0; $i < count($usuarioModulos);
                $i++) {
                array_push($arrayModulos, $usuarioModulos[$i]->IdModulo);
            }
        }
        $array = ['modulos' => $modulos, 'permisos' => $permisos, 'usuarioModulos' => $arrayModulos, 'usuarioSelect' => $usuarioSelect, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('administracion/permisos/modulos', $array);
    }

    public function guardarModulos(Request $req)
    {
        try {
            if ($req->modulos !== null) {
                DB::table('usuario_modulo')
                    ->where('IdUsuario', $req->idUsuario)
                    ->delete();
                for ($i = 0; $i < count($req->modulos);
                    $i++) {
                    $array = ['IdUsuario' => $req->idUsuario, 'IdModulo' => $req->modulos[$i]];
                    DB::table('usuario_modulo')->insert($array);
                }
            } else {
                return back()->with('error', 'Por favor, agregue módulos antes de guardar');
            }

            return redirect('administracion/permisos')->with('status', 'Se guardaron módulos correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function completarModulos(Request $req, $id)
    {
        try {
            $loadDatos = new DatosController();
            $usuarioSelect = $loadDatos->getUsuarioSelect($id);

            $usuariosCompletar = DB::table('usuario')
                ->where('CodigoCliente', $usuarioSelect->CodigoCliente)
                ->where('Estado', 'E')
                ->get();

            if (count($usuariosCompletar) > 0) {
                for ($i = 0; $i < count($usuariosCompletar);
                    $i++) {
                    $permisos = $this->setPermisos($usuariosCompletar[$i]->IdUsuario);
                    if ($usuariosCompletar[$i]->Orden == 1) {
                        $this->setSubPermisos($usuariosCompletar[$i]->IdUsuario, $permisos, 'E');
                        $this->setSubNivel($usuariosCompletar[$i]->IdUsuario, 'E');
                    } else {
                        $this->setSubPermisos($usuariosCompletar[$i]->IdUsuario, $permisos, 'D');
                        $this->setSubNivel($usuariosCompletar[$i]->IdUsuario, 'D');
                    }
                }
            }

            return redirect('administracion/permisos')->with('status', 'Se completaron módulos correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function setSubNivel($idUsuario, $estado)
    {
        $subPermisos = $this->getAllSubPermisos();
        for ($i = 0; $i < count($subPermisos);
            $i++) {
            DB::table('usuario_sub_nivel')
                ->where('IdUsuario', $idUsuario)
                ->where('IdSubPermiso', $subPermisos[$i]->IdSubPermiso)
                ->delete();
            $subNivel = $this->getSubNivel($subPermisos[$i]->IdSubPermiso);
            for ($j = 0; $j < count($subNivel);
                $j++) {
                $array = ['IdUsuario' => $idUsuario, 'IdSubPermiso' => $subPermisos[$i]->IdSubPermiso, 'IdSubNivel' => $subNivel[$j]->IdSubNivel, 'estado' => $estado];
                DB::table('usuario_sub_nivel')->insert($array);
            }
        }
    }

    private function setSubPermisos($idUsuario, $permisos, $estado)
    {
        for ($i = 0; $i < count($permisos);
            $i++) {
            DB::table('usuario_sub_permisos')
                ->where('IdUsuario', $idUsuario)
                ->where('Permiso', $permisos[$i]->IdPermiso)
                ->delete();
            $subPermisos = $this->getSubPermisos($permisos[$i]->IdPermiso);
            for ($j = 0; $j < count($subPermisos);
                $j++) {
                $array = ['IdUsuario' => $idUsuario, 'Permiso' => $permisos[$i]->IdPermiso, 'IdSubPermisos' => $subPermisos[$j]->IdSubPermiso, 'estado' => $estado];
                DB::table('usuario_sub_permisos')->insert($array);
            }
        }
    }

    private function setPermisos($idUsuario)
    {
        $loadDatos = new DatosController();
        DB::table('usuario_permisos')
            ->where('IdUsuario', $idUsuario)
            ->delete();
        $permisos = $loadDatos->getAllPermisos();
        for ($i = 0; $i < count($permisos);
            $i++) {
            $array = ['IdUsuario' => $idUsuario, 'IdPermiso' => $permisos[$i]->IdPermiso];
            DB::table('usuario_permisos')->insert($array);
        }
        return $permisos;
    }

    private function getSubNivel($idSubPermiso)
    {
        try {
            $subNivel = DB::table('sub_nivel')
                ->where('IdSubPermiso', $idSubPermiso)
                ->where('Estado', 'E')
                ->get();
            return $subNivel;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function getSubPermisos($idPermiso)
    {
        try {
            $subPermisos = DB::table('sub_permisos')
                ->where('IdPermiso', $idPermiso)
                ->where('Estado', 'E')
                ->get();
            return $subPermisos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function getAllSubPermisos()
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

    // Nuevas funciones para Actualizar Permisos
    public function guardarPermisosAdministradores(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        try {
            DB::beginTransaction();
            $idUsuariosAdmin = $req->idUsuarios;
            $permisosActualizados = $req->permisos;
            $subPermisosActualizados = $req->subPermisos;
            $subNivelesActualizados = $req->subNiveles;
            $fechaHoy = carbon::now()->toDateTimeString();
            $loadDatos = new DatosController();

            foreach ($idUsuariosAdmin as $id) {
                $allIdPermisosUsuario = $this->getAllPermisosUsuarioSeleccionado($id)->pluck('IdPermiso');
                if ($permisosActualizados) {
                    foreach ($permisosActualizados as $idPermiso) {
                        if ($allIdPermisosUsuario->search($idPermiso) === false) {
                            DB::table('usuario_permisos')
                                ->insert(['IdUsuario' => $id, 'IdPermiso' => $idPermiso, 'Estado' => 'E', 'FechaAsignacion' => $fechaHoy]);
                        } else {
                            DB::table('usuario_permisos')
                                ->where('IdUsuario', $id)
                                ->where('IdPermiso', $idPermiso)
                                ->update(['Estado' => 'E', 'FechaActualizacion' => $fechaHoy]);
                        }
                    }
                }

                if ($subPermisosActualizados) {
                    $allIdSubPermisosUsuario = $this->getAllSubPermisosUsuarioSeleccionado($id)->pluck('IdSubPermisos');
                    $nuevosSubPermisos = $this->subPermisosSistema($subPermisosActualizados);
                    foreach ($nuevosSubPermisos as $subPermiso) {
                        if ($allIdSubPermisosUsuario->search($subPermiso->IdSubPermiso) === false) {
                            DB::table('usuario_sub_permisos')
                                ->insert(['IdUsuario' => $id, 'Permiso' => $subPermiso->IdPermiso, 'IdSubPermisos' => $subPermiso->IdSubPermiso, 'Estado' => 'E', 'FechaAsignacion' => $fechaHoy]);
                        } else {
                            DB::table('usuario_sub_permisos')
                                ->where('IdUsuario', $id)
                                ->where('IdSubPermisos', $subPermiso->IdSubPermiso)
                                ->update(['Estado' => 'E', 'FechaActualizacion' => $fechaHoy]);
                        }
                    }
                }

                if ($subNivelesActualizados) {
                    $allIdSubNivelesUsuario = $this->getAllSubNivelesUsuarioSeleccionado($id)->pluck('IdSubNivel');
                    $nuevosSubNiveles = $this->subNivelesSistema($subNivelesActualizados);
                    foreach ($nuevosSubNiveles as $subNiveles) {
                        if ($allIdSubNivelesUsuario->search($subNiveles->IdSubNivel) === false) {
                            DB::table('usuario_sub_nivel')
                                ->insert(['IdUsuario' => $id, 'IdSubPermiso' => $subNiveles->IdSubPermiso, 'IdSubNivel' => $subNiveles->IdSubNivel, 'Estado' => 'E', 'FechaAsignacion' => $fechaHoy]);
                        } else {
                            DB::table('usuario_sub_nivel')
                                ->where('IdUsuario', $id)
                                ->where('IdSubNivel', $subNiveles->IdSubNivel)
                                ->update(['Estado' => 'E', 'FechaActualizacion' => $fechaHoy]);
                        }
                    }
                }

                // ACTUALIZANDO E INSERTANDO PERMISOS BOTONES
                if ($req->permisosBotonesChekeados) {
                    $allIdPermisosBotonesUsuario = $loadDatos->getAllPermisosBotonesUsuario($id)->pluck('IdPermisoBoton');
                    $permisosBotonesInsertar = [];
                    foreach ($req->permisosBotonesChekeados as $key => $item) {
                        if ($allIdPermisosBotonesUsuario->search($item) === false) {
                            array_push($permisosBotonesInsertar, [
                                'IdPermisoBoton' => $item,
                                'IdUsuario' => $id,
                                'FechaAsignacion' => $fechaHoy,
                            ]);
                        } else {
                            DB::table('permisos_botones_usuarios')
                                ->select('IdPermisoBoton')
                                ->where('IdUsuario', $id)
                                ->where('IdPermisoBoton', $item)
                                ->update(['FechaActualizacion' => $fechaHoy, 'Estado' => 'E']);
                        }
                    }
                    DB::table('permisos_botones_usuarios')
                        ->insert($permisosBotonesInsertar);
                }

                // ACTUALIZANDO E INSERTANDO PERMISOS SUBBOTONES
                if ($req->permisosSubBotonesChekeados) {
                    $allIdPermisosSubBotonesUsuario = $loadDatos->getAllPermisosSubBotonesUsuario($id)->pluck('IdPermisoSubBoton');
                    $nuevosPermisosSubtones = $loadDatos->getDatosPermisosSubBotonesDelSistema($req->permisosSubBotonesChekeados);
                    $permisosSubBotonesInsertar = [];
                    foreach ($nuevosPermisosSubtones as $key => $item) {
                        if ($allIdPermisosSubBotonesUsuario->search($item->IdPermisoSubBoton) === false) {
                            array_push($permisosSubBotonesInsertar, array_merge((array) $item, ['IdUsuario' => $id, 'FechaAsignacion' => $fechaHoy]));
                        } else {
                            DB::table('permisos_subbotones_usuarios')
                                ->select('IdPermisoSubBoton')
                                ->where('IdUsuario', $id)
                                ->where('IdPermisoSubBoton', $item->IdPermisoSubBoton)
                                ->update(['FechaActualizacion' => $fechaHoy, 'Estado' => 'E']);
                        }
                    }
                    DB::table('permisos_subbotones_usuarios')
                        ->insert($permisosSubBotonesInsertar);
                }
            }

            DB::commit();
            return redirect('administracion/permisos')->with('status', 'Se asignaron los permisos correctamente');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect('administracion/permisos')->with('error', 'Ocurrio un error, comunicarse con el area de soporte');
        }
    }

    public function verVistaPermisosDelSistema(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        $loadDatos = new DatosController();
        $planesSuscripcion = $loadDatos->getPaquetesSuscripcion();
        $idSucursal = Session::get('idSucursal');
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $usuariosAdministradores = $loadDatos->getUsuariosPermisos($usuarioSelect, 'E');

        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $permisos = $loadDatos->getAllPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $permisosDelSistema = $this->getPermisosDelSuperAdmin();
        $subPermisosDelSistema = $this->getSubPermisosDelSuperAdmin();
        $nivelesDelSistema = $this->getNivelesDelSuperAdmin();
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
        $rubros = $loadDatos->getRubros();
        $permisosBotonesDelSistema = $loadDatos->getAllPermisosBotonesDelSistema();
        $permisosSubBotonesDelSistema = $loadDatos->getAllPermisosSubBotonesDelSistema();

        $permisosBotones = $permisosBotonesDelSistema->map(function ($item) use ($permisosSubBotonesDelSistema) {
            $resultado = $permisosSubBotonesDelSistema->where('IdPermisoBoton', $item->Id)->values();
            $item->SubBotones = $resultado;
            return $item;
        });

        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'permisosDelSistema' => $permisosDelSistema, 'usuariosAdministradores' => $usuariosAdministradores, 'rubros' => $rubros, 'permisosBotones' => $permisosBotones, 'planesSuscripcion' => $planesSuscripcion];

        return view('administracion/permisos/asignacionMasiva', $array);
    }
    public function verVistaListaPermisos(Request $req, $id)
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
        $idUsuarioPermiso = $id;

        $usuariosecundario = $loadDatos->getUsuarioSelect($id);
        if ($idUsuario == 1) {

            $permisosDelSuperAdmin = $this->getPermisosDelSuperAdmin();
            $subPermisosDelSuperAdmin = $this->getSubPermisosDelSuperAdmin();
            $nivelesDelSuperAdmin = $this->getNivelesDelSuperAdmin();

            $subPermisosDelSuperAdmin = $subPermisosDelSuperAdmin->map(function ($item) use ($nivelesDelSuperAdmin) {
                $resultado = $nivelesDelSuperAdmin->where('IdSubPermiso', $item->IdSubPermiso)->values();
                $item->SubNiveles = $resultado;
                return $item;
            });

            $permisosDelSuperAdmin = $permisosDelSuperAdmin->map(function ($item) use ($subPermisosDelSuperAdmin) {
                $resultado = $subPermisosDelSuperAdmin->where('IdPermiso', $item->IdPermiso)->values();
                $item->SubPermisos = $resultado;
                return $item;
            });

            $permisosDelUsuarioPrincipal = $permisosDelSuperAdmin;

        } else {
            $usuarioPrincipal = DB::table('usuario')
                ->where('CodigoCliente', $usuariosecundario->CodigoCliente)
                ->where('Estado', 'E')
                ->where('Orden', '1')
                ->first();

            $permisosDelUsuarioPrincipal = $this->getPermisosDelUsuarioPrincipal($usuarioPrincipal->IdUsuario);
            $subPermisosDelUsuarioPrincipal = $this->getSubPermisosDelUsuarioPrincipal($usuarioPrincipal->IdUsuario);
            $nivelesDelUsuarioPrincipal = $this->getSubNivelesDelUsuarioPrincipal($usuarioPrincipal->IdUsuario);

            $subPermisosDelUsuarioPrincipal = $subPermisosDelUsuarioPrincipal->map(function ($item) use ($nivelesDelUsuarioPrincipal) {
                $resultado = $nivelesDelUsuarioPrincipal->where('IdSubPermiso', $item->IdSubPermiso)->values();
                $item->SubNiveles = $resultado;
                return $item;
            });

            $permisosDelUsuarioPrincipal = $permisosDelUsuarioPrincipal->map(function ($item) use ($subPermisosDelUsuarioPrincipal) {
                $resultado = $subPermisosDelUsuarioPrincipal->where('IdPermiso', $item->IdPermiso)->values();
                $item->SubPermisos = $resultado;
                return $item;
            });
        }

        $usuarioPermisos = $this->getPermisosUsuarioSecundario($id);
        $arrayPermisosDeUsuario = $usuarioPermisos->pluck('IdPermiso')->values()->toArray();

        $usuarioSubPermisos = $this->getSubPermisosUsuarioSecundario($id);
        $arraySubPermisosDeUsuario = $usuarioSubPermisos->pluck('IdSubPermiso')->values()->toArray();

        $usuarioSubNiveles = $this->getSubNivelesUsuarioSecundario($id);
        $arraySubNivelesDeUsuario = $usuarioSubNiveles->pluck('IdSubNivel')->values()->toArray();
        // PERMISOS BOTONES ADMINISTRATIVOS
        $permisosBotones = $loadDatos->getAllPermisosBotonesDelSistema();
        $permisosSubBotones = $loadDatos->getAllPermisosSubBotonesDelSistema();
        $permisosBotonesDelSistema = $permisosBotones->map(function ($item) use ($permisosSubBotones) {
            $resultado = $permisosSubBotones->where('IdPermisoBoton', $item->Id)->values();
            $item->SubBotones = $resultado;
            return $item;
        });

        $arrayPermisosBotonesHabilitados = $loadDatos->getAllPermisosBotonesUsuario($id)->where('Estado', 'E')->pluck('IdPermisoBoton')->toArray();
        $arrayPermisosSubBotonesHabilitados = $loadDatos->getAllPermisosSubBotonesUsuario($id)->where('Estado', 'E')->pluck('IdPermisoSubBoton')->toArray();

        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'permisosDelSistema' => $permisosDelUsuarioPrincipal, 'arrayPermisosHabilitados' => $arrayPermisosDeUsuario, 'arraySubPermisosHabilitados' => $arraySubPermisosDeUsuario, 'arraySubNivelesHabilitados' => $arraySubNivelesDeUsuario, 'idUsuarioPermiso' => $idUsuarioPermiso, 'permisosBotonesDelSistema' => $permisosBotonesDelSistema, 'arrayPermisosBotonesHabilitados' => $arrayPermisosBotonesHabilitados, 'arrayPermisosSubBotonesHabilitados' => $arrayPermisosSubBotonesHabilitados];

        return view('administracion/permisos/listaPermisos', $array);
    }

    public function getPermisosDelSuperAdmin()
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

    public function getSubPermisosDelSuperAdmin()
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

    public function getNivelesDelSuperAdmin()
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

    public function getPermisosDelUsuarioPrincipal($idUsuario)
    {
        try {
            $permisos = DB::table('usuario_permisos')
                ->join('permiso', 'usuario_permisos.IdPermiso', '=', 'permiso.IdPermiso')
                ->select('permiso.*')
                ->where('usuario_permisos.Estado', 'E')
                ->where('permiso.Estado', 'E')
                ->where('usuario_permisos.IdUsuario', $idUsuario)
                ->orderBy('permiso.IdPermiso', 'asc')
                ->get();

            return $permisos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    public function getSubPermisosDelUsuarioPrincipal($idUsuario)
    {
        try {
            $subPermisos = DB::table('usuario_sub_permisos')
                ->join('sub_permisos', 'usuario_sub_permisos.IdSubPermisos', '=', 'sub_permisos.IdSubPermiso')
                ->select('sub_permisos.*')
                ->where('usuario_sub_permisos.estado', 'E')
                ->where('sub_permisos.Estado', 'E')
                ->where('usuario_sub_permisos.IdUsuario', $idUsuario)
                ->orderBy('sub_permisos.IdSubPermiso', 'asc')
                ->get();

            return $subPermisos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getSubNivelesDelUsuarioPrincipal($idUsuario)
    {
        try {
            $subNiveles = DB::table('usuario_sub_nivel')
                ->join('sub_nivel', 'usuario_sub_nivel.IdSubNivel', '=', 'sub_nivel.IdSubNivel')
                ->select('sub_nivel.*')
                ->where('usuario_sub_nivel.estado', 'E')
                ->where('sub_nivel.estado', 'E')
                ->where('usuario_sub_nivel.IdUsuario', $idUsuario)
                ->get();

            return $subNiveles;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getPermisosUsuarioSecundario($id)
    {
        $usuarioSubPermisos = DB::table('usuario_permisos')
            ->join('permiso', 'usuario_permisos.IdPermiso', '=', 'permiso.IdPermiso')
            ->select('permiso.*', 'usuario_permisos.*')
            ->where('usuario_permisos.Estado', 'E')
            ->where('usuario_permisos.IdUsuario', $id)
            ->orderBy('permiso.IdPermiso', 'asc')
            ->get();
        return $usuarioSubPermisos;
    }

    public function getSubPermisosUsuarioSecundario($id)
    {
        $usuarioSubPermisos = DB::table('usuario_sub_permisos')
            ->join('sub_permisos', 'usuario_sub_permisos.IdSubPermisos', '=', 'sub_permisos.IdSubPermiso')
            ->select('sub_permisos.*', 'usuario_sub_permisos.*')
            ->where('usuario_sub_permisos.estado', 'E')
            ->where('usuario_sub_permisos.IdUsuario', $id)
            ->orderBy('sub_permisos.IdSubPermiso', 'asc')
            ->get();
        return $usuarioSubPermisos;
    }

    public function getSubNivelesUsuarioSecundario($id)
    {
        $usuarioSubNiveles = DB::table('usuario_sub_nivel')
            ->join('sub_nivel', 'usuario_sub_nivel.IdSubNivel', '=', 'sub_nivel.IdSubNivel')
            ->select('sub_nivel.*', 'usuario_sub_nivel.*')
            ->where('usuario_sub_nivel.estado', 'E')
            ->where('usuario_sub_nivel.IdUsuario', $id)
            ->orderBy('sub_nivel.IdSubNivel', 'asc')
            ->get();
        return $usuarioSubNiveles;
    }
}
