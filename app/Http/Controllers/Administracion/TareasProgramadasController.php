<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Session;
use Storage;
use Illuminate\Support\Facades\Config;

class TareasProgramadasController extends Controller
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
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $listaTareas = DB::table("lista_tareas")->select('*')->get();

        $array = ['permisos' => $permisos, 'usuarioSelect' => $usuarioSelect, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'listaTareas' => $listaTareas];
        return view('administracion/tareasProgramadas/tareas', $array);
    }

    public function AsignarTareas(request $req, $id)
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
        $usuarios = $this->getUsuarios($usuarioSelect->IdOperador, $usuarioSelect->CodigoCliente);
        $usuarios = $usuarios->filter(function ($items) {
            return $items->Estado == "E";
        });

        $idTarea = intval($id);
        $tareasXgrupoDeUsuarios = DB::table("detalle_tarea")
            ->select('*')
            ->where("IdTarea", $idTarea)
            ->get();
        $listaIds = $tareasXgrupoDeUsuarios->pluck("IdUsuario")->toArray();
        $listaIdUsuarios = $tareasXgrupoDeUsuarios->pluck("IdUsuario")->toArray();
        $usuarios = $usuarios->values();
        $cantidadUsuarios = $usuarios->count();
        $usuariosKey = $usuarios->keyBy('idUsuario');
        $listaIds = collect($listaIds)->pad($cantidadUsuarios, 12)->toArray();
        foreach ($usuarios as $key => $data) {
            if (in_array($data->idUsuario, $listaIds)) {
                $usuarios[$key]->checkValor = "1";
            } else {
                $usuarios[$key]->checkValor = "0";
            }
        };
        $array = ['permisos' => $permisos, 'usuarioSelect' => $usuarioSelect, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'usuarios' => $usuarios, 'idTarea' => $idTarea, 'tareasXgrupoDeUsuarios' => $tareasXgrupoDeUsuarios, 'listaIdUsuarios' => $listaIdUsuarios];
        return view('administracion/tareasProgramadas/asignarTareas', $array);
    }

    public function store(Request $req)
    {
        if ($req->ajax()) {
            $arrayIdUsuarios = $req->arrayIdUsuario;
            $idTarea = $req->idTarea;
            $estadoTarea = $req->estadoTarea;

            if ($arrayIdUsuarios == null) {
                return Response(['error', 'No ha seleccionado a ningun usuario']);
            }

            $arrayItemsNoEliminados = [];
            for ($i = 0; $i < count($arrayIdUsuarios); $i++) {
                array_push($arrayItemsNoEliminados, $arrayIdUsuarios[$i]);
                $arrayId = [
                    'IdTarea' => $idTarea,
                    'IdUsuario' => $arrayIdUsuarios[$i],
                    'EstadoTarea' => $estadoTarea,
                ];

                $verificarExistenciaIds = $this->listaDetalleTarea($arrayIdUsuarios[$i], $idTarea);

                if ($verificarExistenciaIds) {
                    DB::table('detalle_tarea')
                        ->where("IdTarea", $idTarea)
                        ->where("IdUsuario", $arrayIdUsuarios[$i])
                        ->update($arrayId);
                } else {
                    DB::table('detalle_tarea')->insert($arrayId);
                    usleep(200000);
                }
            }

            DB::table('detalle_tarea')
                ->where('IdTarea', $idTarea)
                ->whereNotIn('IdUsuario', $arrayItemsNoEliminados)
                ->delete();
            return response(["succes", "Se asigno la tarea correctamente", $arrayItemsNoEliminados]);
        }
    }

    private function listaDetalleTarea($idUsuarios, $idTarea)
    {
        $resultado = DB::table('detalle_tarea')
            ->where('IdUsuario', $idUsuarios)
            ->where('IdTarea', $idTarea)
            ->first();
        return $resultado;
    }

    // Nuevas Funciones
    public function listarPdf(request $req)
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
        $ultimosTreintaDias = $this->ultimosTreintaDias();
        $listaPdf = $this->getUrlPdf($ultimosTreintaDias);

        $array = ['permisos' => $permisos, 'usuarioSelect' => $usuarioSelect, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'listaPdf' => $listaPdf];
        return view('administracion/tareasProgramadas/listaPdf', $array);
    }
    public function deletePdf()
    {
        $ultimosTreintaDias = $this->ultimosTreintaDias();
        $listaPdf = $this->getUrlPdf($ultimosTreintaDias);
        
        foreach($listaPdf as $pdf){
            Storage::disk('s3')->delete($pdf->UrlPdf);
            $array = ['UrlPdf' => null, 'FechaCreacionPdf'=> null];
            if($pdf->TipoDocumento === 'Ventas'){
                DB::table('ventas')
                    ->where('IdVentas', $pdf->Id)
                    ->where('IdSucursal', $pdf->IdSucursal)
                    ->update($array);
            }

            if($pdf->TipoDocumento === 'Cotizacion'){
                DB::table('cotizacion')
                    ->where('IdCotizacion', $pdf->Id)
                    ->where('IdSucursal', $pdf->IdSucursal)
                    ->update($array);
            }

            if($pdf->TipoDocumento === 'Check_in'){
                DB::table('check_in')
                    ->where('IdCheckIn', $pdf->Id)
                    ->where('IdSucursal', $pdf->IdSucursal)
                    ->update($array);
            }
        }
        return redirect('administracion/tareasProgramadas/lista-pdf');
    }

    public function getUsuarios($idOperador, $codigoCliente)
    {
        try {
            if ($idOperador == 1) {
                $usuarios = DB::table('usuario')
                    ->join('operador', 'usuario.IdOperador', '=', 'operador.IdOperador')
                    ->join('sucursal', 'usuario.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->select('usuario.*', 'operador.Rol as Rol', 'sucursal.Nombre as Sucursal', 'usuario.IdUsuario as idUsuario')
                    ->whereIn('usuario.Estado', ['E', 'D'])
                    ->where('usuario.Cliente', 1)
                    ->orderBy('usuario.IdUsuario', 'desc')
                    ->get();
            } else {
                $usuarios = DB::table('usuario')
                    ->join('operador', 'usuario.IdOperador', '=', 'operador.IdOperador')
                    ->join('sucursal', 'usuario.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->select('usuario.*', 'operador.Rol as Rol', 'sucursal.Nombre as Sucursal', 'usuario.IdUsuario as idUsuario')
                    ->whereIn('usuario.Estado', ['E', 'D'])
                    ->where('usuario.Cliente', 0)
                    ->where('usuario.CodigoCliente', $codigoCliente)
                    ->orderBy('usuario.IdUsuario', 'desc')
                    ->get();
            }
            return $usuarios;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // public function getUrlPdf($fecha){
    //     $resultado = DB::table('ventas')
    //     ->select('IdSucursal', 'IdCreacion', 'FechaCreacion', 'UrlPdf')
    //     ->where('UrlPdf', '!=', 'null')
    //     ->where('ventas.FechaCreacionPdf','<=', $fecha)
    //     ->get();
    //     return $resultado;
    // }
    public function getUrlPdf($fecha)
    {
        try {
            $resultado = DB::select('(select IdVentas as Id, IdSucursal,FechaCreacionPdf,UrlPdf, IdCreacion, "Ventas" as TipoDocumento
                                    from ventas
									  where UrlPdf is not null and  FechaCreacionPdf < "' . $fecha . '")
                                      UNION
                                      (select IdCotizacion as Id, IdSucursal,FechaCreacionPdf,UrlPdf, IdCreacion, "Cotizacion" as TipoDocumento
							          from cotizacion
									  where UrlPdf is not null and  FechaCreacionPdf < "' . $fecha . '")
                                      UNION
                                      (select IdCheckIn As Id, IdSucursal,FechaCreacionPdf,UrlPdf, IdUsuario as IdCreacion, "Check_in" as TipoDocumento
							          from check_in
									  where UrlPdf is not null and  FechaCreacionPdf < "' . $fecha . '")
                                    ');
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function ultimosTreintaDias()
    {
        $fechaInicio = Carbon::now()->subDays(30);
        return $fechaInicio;
    }

    // public function ultimosTreintaDias(){
    //     $fechaInicio = Carbon::now()->subDays(30);
    //     return array($fechaInicio);
    // }
}
