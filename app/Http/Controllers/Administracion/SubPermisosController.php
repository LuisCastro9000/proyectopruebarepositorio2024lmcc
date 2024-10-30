<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DB;
use Illuminate\Http\Request;
use Session;

class SubPermisosController extends Controller
{
    public function consultar(Request $req, $id, $id2)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
        }

        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $usuariosPermisos = $loadDatos->getUsuariosPermisos($usuarioSelect, 'E');
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $operador = DB::table('usuario')
            ->join('operador', 'usuario.IdOperador', '=', 'operador.IdOperador')
            ->select('usuario.IdUsuario', 'usuario.Nombre', 'operador.IdOperador', 'operador.Rol')
            ->where('IdUsuario', $id2)
            ->first();

        $sub_permisos = DB::table('sub_permisos')
            ->where('IdPermiso', $id)
            ->get();

        $usuarioSubPermisos = DB::table('usuario_sub_permisos') // $usuarioPermisos = $loadDatos->getPermisos( $id );
            ->join('sub_permisos', 'usuario_sub_permisos.IdSubPermisos', '=', 'sub_permisos.IdSubPermiso')
            ->select('sub_permisos.*', 'usuario_sub_permisos.*')
            ->where('usuario_sub_permisos.estado', 'E')
            ->where('usuario_sub_permisos.Permiso', $id)
            ->where('usuario_sub_permisos.IdUsuario', $id2)
            ->orderBy('sub_permisos.IdSubPermiso', 'asc')
            ->get();

        /*$array_subPermisos = [];
        for ( $i = 0; $i<count( $usuarioSubPermisos );
        $i++ ) {
        array_push( $array_subPermisos, $usuarioSubPermisos[ $i ]->IdSubPermiso );
        }
         */
        $array = ['permiso_general' => $id, 'operador' => $operador, 'sub_permisos' => $sub_permisos, 'usuariosPermisos' => $usuariosPermisos, 'usuarioSubPermisos' => $usuarioSubPermisos, 'permisos' => $permisos, 'usuarioSelect' => $usuarioSelect, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('administracion/permisos/mostrarPermisos', $array);

    }

    public function agregar(Request $req, $id, $id2)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $usuario = $loadDatos->getUsuarioSelect($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $permisos = $loadDatos->getAllPermisos();
        if ($idUsuario == 1) {
            $sub_permisos = DB::table('sub_permisos') //$permisos = $loadDatos->getAllPermisos();
                ->where('IdPermiso', $id2)
                ->where('Estado', 'E')
                ->get();
        } else {
            $usuarioPrinc = DB::table('usuario')
                ->where('CodigoCliente', $usuario->CodigoCliente)
                ->where('Estado', 'E')
                ->where('Orden', '1')
                ->first();
            $sub_permisos = DB::table('usuario_sub_permisos') //$permisos = $loadDatos->getAllPermisos();
                ->join('sub_permisos', 'usuario_sub_permisos.IdSubPermisos', '=', 'sub_permisos.IdSubPermiso')
                ->select('sub_permisos.*')
                ->where('usuario_sub_permisos.estado', 'E')
                ->where('usuario_sub_permisos.Permiso', $id2)
                ->where('usuario_sub_permisos.IdUsuario', $usuarioPrinc->IdUsuario)
                ->orderBy('sub_permisos.IdSubPermiso', 'asc')
                ->get();
        }

        $usuarioSubPermisos = DB::table('usuario_sub_permisos') // $usuarioPermisos = $loadDatos->getPermisos( $id );
            ->join('sub_permisos', 'usuario_sub_permisos.IdSubPermisos', '=', 'sub_permisos.IdSubPermiso')
            ->select('sub_permisos.*', 'usuario_sub_permisos.*')
            ->where('usuario_sub_permisos.estado', 'E')
            ->where('usuario_sub_permisos.Permiso', $id2)
            ->where('usuario_sub_permisos.IdUsuario', $id)
            ->get();

        $modulosSelect = $loadDatos->getModulosSelect($usuario->CodigoCliente);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $array_subPermisos = [];
        for ($i = 0; $i < count($usuarioSubPermisos);
            $i++) {
            array_push($array_subPermisos, $usuarioSubPermisos[$i]->IdSubPermisos);
        }
        $array = ['usuario' => $usuario, 'usuarioSelect' => $usuarioSelect, 'IdPermiso' => $id2, 'permisos' => $sub_permisos, 'usuarioPermisos' => $array_subPermisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('administracion/permisos/editarSubPermisos', $array);
    }

    public function agregar_update(Request $req, $id, $id2)
    {
        try {
            if ($req->sub_permisos !== null) {
                $idUsuario = Session::get('idUsuario');
                DB::table('usuario_sub_permisos')
                    ->where('IdUsuario', $id)
                    ->where('Permiso', $id2)
                    ->update(['estado' => 'E']);

                dd($id2);
                if ($idUsuario == 1) {
                    $usuarioPrinc = DB::table('usuario')
                        ->where('IdUsuario', $id)
                        ->first();

                    $usuariosSec = DB::table('usuario')
                        ->where('CodigoCliente', $usuarioPrinc->CodigoCliente)
                        ->where('Estado', 'E')
                        ->where('Cliente', 0)
                        ->get();

                    for ($i = 0; $i < count($usuariosSec);
                        $i++) {
                        DB::table('usuario_sub_permisos')
                            ->where('IdUsuario', $usuariosSec[$i]->IdUsuario)
                            ->where('Permiso', $id2)
                            ->whereNotIn('IdSubPermisos', $req->sub_permisos)
                            ->update(['estado' => 'D']);
                    }
                }
                DB::table('usuario_sub_permisos')
                    ->where('IdUsuario', $id)
                    ->where('Permiso', $id2)
                    ->whereNotIn('IdSubPermisos', $req->sub_permisos)
                    ->update(['estado' => 'D']);
                /*DB::table( 'usuario_sub_permisos' )
            ->where( 'IdUsuario', $id )
            ->where( 'Permiso', $id2 )
            ->delete();
            for ( $i = 0; $i<count( $req->sub_permisos );
            $i++ ) {
            $array = [ 'IdUsuario' => $id, 'Permiso'=>$id2, 'IdSubPermisos' => $req->sub_permisos[ $i ] ];
            DB::table( 'usuario_sub_permisos' )->insert( $array );
            }
             */
            } else {
                return back()->with('error', 'Por favor, agregue sub permisos antes de actualizar');
            }

            return redirect('administracion/permisos')->with('status', 'Se actualizaron permisos correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }

    }

    public function consultar_nivel(Request $req, $id, $id2)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $usuariosPermisos = $loadDatos->getUsuariosPermisos($usuarioSelect, 'E');
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $operador = DB::table('usuario')
            ->join('operador', 'usuario.IdOperador', '=', 'operador.IdOperador')
            ->select('usuario.IdUsuario', 'usuario.Nombre', 'operador.IdOperador', 'operador.Rol')
            ->where('IdUsuario', $id)
            ->first();

        $sub_permisos = DB::table('sub_nivel')
            ->where('IdSubPermiso', $id2)
            ->get();

        $usuarioSubPermisos = DB::table('usuario_sub_nivel') // $usuarioPermisos = $loadDatos->getPermisos( $id );
            ->join('sub_nivel', 'usuario_sub_nivel.IdSubNivel', '=', 'sub_nivel.IdSubNivel')
            ->select('sub_nivel.*', 'usuario_sub_nivel.*')
            ->where('usuario_sub_nivel.estado', 'E')
            ->where('usuario_sub_nivel.IdSubPermiso', $id2)
            ->where('usuario_sub_nivel.IdUsuario', $id)
            ->get();

        /*$array_subPermisos = [];
        for ( $i = 0; $i<count( $usuarioSubPermisos );
        $i++ ) {
        array_push( $array_subPermisos, $usuarioSubPermisos[ $i ]->IdSubNivel );
        }
         */

        $array = ['permiso_general' => $id2, 'operador' => $operador, 'sub_permisos' => $sub_permisos, 'usuariosPermisos' => $usuariosPermisos, 'usuarioSubPermisos' => $usuarioSubPermisos, 'permisos' => $permisos, 'usuarioSelect' => $usuarioSelect, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('administracion/permisos/mostrarNiveles', $array);

    }

    public function agregar_nivel(Request $req, $id, $id2)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $usuario = $loadDatos->getUsuarioSelect($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);

        if ($idUsuario == 1) {
            $sub_permisos = DB::table('sub_nivel') //$permisos = $loadDatos->getAllPermisos();
                ->where('IdSubPermiso', $id2)
                ->where('Estado', 'E')
                ->get();
        } else {
            $usuarioPrinc = DB::table('usuario')
                ->where('CodigoCliente', $usuario->CodigoCliente)
                ->where('Estado', 'E')
                ->where('Orden', '1')
                ->first();

            $sub_permisos = DB::table('usuario_sub_nivel') // $usuarioPermisos = $loadDatos->getPermisos( $id );
                ->join('sub_nivel', 'usuario_sub_nivel.IdSubNivel', '=', 'sub_nivel.IdSubNivel')
                ->select('sub_nivel.*')
                ->where('usuario_sub_nivel.Estado', 'E')
                ->where('usuario_sub_nivel.IdSubPermiso', $id2)
                ->where('usuario_sub_nivel.IdUsuario', $usuarioPrinc->IdUsuario)
                ->get();
        }

        $usuarioSubPermisos = DB::table('usuario_sub_nivel') // $usuarioPermisos = $loadDatos->getPermisos( $id );
            ->join('sub_nivel', 'usuario_sub_nivel.IdSubNivel', '=', 'sub_nivel.IdSubNivel')
            ->select('sub_nivel.*', 'usuario_sub_nivel.*')
            ->where('usuario_sub_nivel.Estado', 'E')
            ->where('usuario_sub_nivel.IdSubPermiso', $id2)
            ->where('usuario_sub_nivel.IdUsuario', $id)
            ->get();

        $modulosSelect = $loadDatos->getModulosSelect($usuario->CodigoCliente);
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $array_subPermisos = [];
        for ($i = 0; $i < count($usuarioSubPermisos);
            $i++) {
            array_push($array_subPermisos, $usuarioSubPermisos[$i]->IdSubNivel);
        }
        /* dd( $array_subPermisos );
        die;
         */
        $array = ['usuario' => $usuario, 'usuarioSelect' => $usuarioSelect, 'IdPermiso' => $id2, 'permisos' => $sub_permisos, 'usuarioPermisos' => $array_subPermisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('administracion/permisos/editarSubNivel', $array);
    }

    public function nivel_update(Request $req, $id, $id2)
    {
        try {

            if ($req->sub_niveles !== null) {
                $idUsuario = Session::get('idUsuario');
                DB::table('usuario_sub_nivel')
                    ->where('IdUsuario', $id)
                    ->where('IdSubPermiso', $id2)
                    ->update(['estado' => 'E']);

                if ($idUsuario == 1) {
                    $usuarioPrinc = DB::table('usuario')
                        ->where('IdUsuario', $id)
                        ->first();

                    $usuariosSec = DB::table('usuario')
                        ->where('CodigoCliente', $usuarioPrinc->CodigoCliente)
                        ->where('Estado', 'E')
                        ->where('Cliente', 0)
                        ->get();

                    for ($i = 0; $i < count($usuariosSec);
                        $i++) {
                        DB::table('usuario_sub_nivel')
                            ->where('IdUsuario', $usuariosSec[$i]->IdUsuario)
                            ->where('IdSubPermiso', $id2)
                            ->whereNotIn('IdSubNivel', $req->sub_niveles)
                            ->update(['estado' => 'D']);
                    }
                }
                DB::table('usuario_sub_nivel')
                    ->where('IdUsuario', $id)
                    ->where('IdSubPermiso', $id2)
                    ->whereNotIn('IdSubNivel', $req->sub_niveles)
                    ->update(['estado' => 'D']);
                /*DB::table( 'usuario_sub_nivel' )
            ->where( 'IdUsuario', $id )
            ->where( 'IdSubPermiso', $id2 )
            ->delete();
            for ( $i = 0; $i<count( $req->sub_niveles );
            $i++ ) {
            $array = [ 'IdUsuario' => $id, 'IdSubPermiso'=>$id2, 'IdSubNivel' => $req->sub_niveles[ $i ] ];
            DB::table( 'usuario_sub_nivel' )->insert( $array );
            }
             */
            } else {
                //se modifico esto para poder eliminar
                /*DB::table( 'usuario_sub_nivel' )
                ->where( 'IdUsuario', $id )
                ->where( 'IdSubPermiso', $id2 )
                ->delete();
                 */
                return back()->with('error', 'Por favor, agregue permisos antes de actualizar');
            }

            return redirect('administracion/permisos')->with('status', 'Se actualizaron permisos correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function buscarSubPermisos(Request $req)
    {
        $idSubPermiso = $req->subPermiso;
        $_users = [];
        $usuarios = DB::table('usuario_sub_permisos')
            ->join('usuario', 'usuario_sub_permisos.IdUsuario', '=', 'usuario.IdUsuario')
            ->select('usuario.*')
            ->where('usuario.Orden', 1)
            ->where('usuario.Estado', 'E')
            ->whereNotIn('usuario.IdUsuario', [1])
            ->where('usuario_sub_permisos.IdSubPermisos', $idSubPermiso)
            ->get();

        $usuariosSelect = DB::table('usuario')
            ->where('Orden', 1)
            ->where('Estado', 'E')
            ->whereNotIn('IdUsuario', [1])
            ->get();
        foreach ($usuarios as $usuario) {
            array_push($_users, $usuario->IdUsuario);
        }
        $filtered = $usuariosSelect->whereNotIn('IdUsuario', $_users);
        $values = $filtered->values();
        return Response($values);
    }

    public function agregar_update_completar(Request $req, $idPermiso)
    {
        $idSubPermiso = $req->subPermisos;
        $idUsuarios = $req->usuariosSinSubPermisos;
        $loadDatos = new DatosController();
        for ($i = 0; $i < count($idUsuarios);
            $i++) {
            $usuario = $loadDatos->getUsuarioSelect($idUsuarios[$i]);

            $usuarios = DB::table('usuario')
                ->where('CodigoCliente', $usuario->CodigoCliente)
                ->where('Estado', 'E')
                ->get();

            for ($j = 0; $j < count($usuarios);
                $j++) {
                $array = ['IdUsuario' => $usuarios[$j]->IdUsuario, 'Permiso' => $idPermiso, 'IdSubPermisos' => $idSubPermiso, 'Estado' => 'E'];
                DB::table('usuario_sub_permisos')->insert($array);
            }

        }

        return redirect('administracion/permisos')->with('status', 'Se completaron módulos correctamente');
    }

    public function buscarSubNiveles(Request $req)
    {
        $idSubNivel = $req->subNiveles;
        $_users = [];
        $usuarios = DB::table('usuario_sub_nivel')
            ->join('usuario', 'usuario_sub_nivel.IdUsuario', '=', 'usuario.IdUsuario')
            ->select('usuario.*')
            ->where('usuario.Orden', 1)
            ->where('usuario.Estado', 'E')
            ->whereNotIn('usuario.IdUsuario', [1])
            ->where('usuario_sub_nivel.IdSubNivel', $idSubNivel)
            ->get();

        // $usuariosSelect = DB::table( 'usuario' )
        // ->where( 'Orden', 1 )
        // ->where( 'Estado', 'E' )
        // ->whereNotIn( 'IdUsuario', [ 1 ] )
        // ->get();

        $usuariosSelect = DB::table('usuario')
            ->join('sucursal', 'usuario.IdSucursal', '=', 'sucursal.IdSucursal')
            ->select('usuario.*', 'sucursal.Nombre as NombreSucursal')
            ->where('usuario.Orden', 1)
            ->where('usuario.Estado', 'E')
            ->whereNotIn('usuario.IdUsuario', [1])
            ->get();
        foreach ($usuarios as $usuario) {
            array_push($_users, $usuario->IdUsuario);
        }
        $filtered = $usuariosSelect->whereNotIn('IdUsuario', $_users);
        $values = $filtered->values();
        return Response($values);
    }

    public function nivel_update_completar(Request $req, $idSubPermiso)
    {
        $idSubNivel = $req->subNiveles;
        $idUsuarios = $req->usuariosSinNiveles;
        $loadDatos = new DatosController();

        for ($i = 0; $i < count($idUsuarios);
            $i++) {
            $usuario = $loadDatos->getUsuarioSelect($idUsuarios[$i]);

            $usuarios = DB::table('usuario')
                ->where('CodigoCliente', $usuario->CodigoCliente)
                ->where('Estado', 'E')
                ->get();

            for ($j = 0; $j < count($usuarios);
                $j++) {
                $array = ['IdUsuario' => $usuarios[$j]->IdUsuario, 'IdSubPermiso' => $idSubPermiso, 'IdSubNivel' => $idSubNivel, 'Estado' => 'E'];
                DB::table('usuario_sub_nivel')->insert($array);
            }

        }

        return redirect('administracion/permisos')->with('status', 'Se completaron módulos correctamente');
    }
}
