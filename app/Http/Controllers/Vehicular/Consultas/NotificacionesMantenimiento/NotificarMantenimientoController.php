<?php

namespace App\Http\Controllers\Vehicular\Consultas\NotificacionesMantenimiento;

use App\Exports\ExcelReporteVehiculosParaMantenimientoPorKm;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class NotificarMantenimientoController extends Controller
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
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $fecha = 5;
        $fechaIni = '';
        $fechaFin = '';
        $ini = '';
        $fin = '';

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $this->actualizarDatos($idSucursal, $fechas[0], $fechas[1]);
        $listaSalidaVehicular = $this->getVehiculosParaNotificar($idSucursal, $fechas[0], $fechas[1]);

        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'listaSalidaVehicular' => $listaSalidaVehicular, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin];

        return view('vehicular/consultas/notificacionesMantenimiento/notificarMantenimiento', $array);
    }

    public function consultarVehiculo(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            if ($req->session()->has('idUsuario')) {
                $idUsuario = Session::get('idUsuario');
            } else {
                Session::flush();
                return redirect('/')->with('out', 'Sesión de usuario Expirado');
            }
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $permisos = $loadDatos->getPermisos($idUsuario);
            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $fecha = $req->fecha;
            $fechaIni = $req->fechaIni;
            $fechaFin = $req->fechaFin;
            if ($fecha == 9) {
                if ($fechaIni == null || $fechaFin == null) {
                    return back()->with('error', 'Completar las fechas para Filtrar');
                }
            }
            $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            $this->actualizarDatos($idSucursal, $fechas[0], $fechas[1]);
            $listaSalidaVehicular = $this->getVehiculosParaNotificar($idSucursal, $fechas[0], $fechas[1]);

            $ini = str_replace('/', '-', $fechaIni);
            $fin = str_replace('/', '-', $fechaFin);

            $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'listaSalidaVehicular' => $listaSalidaVehicular, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin];

            return view('vehicular/consultas/notificacionesMantenimiento/notificarMantenimiento', $array);

        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function exportarExcel($fecha = null, $ini = null, $fin = null)
    {
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);

        $reporte = $this->getVehiculosParaNotificar($idSucursal, $fechas[0], $fechas[1]);
        return Excel::download(new ExcelReporteVehiculosParaMantenimientoPorKm($reporte), 'ReporteVehiculosParaMantemientoPorKm.xlsx');
    }

    private function actualizarDatos($idSucursal, $fechaIni, $fechaFin)
    {
        $listaSalidaVehicular = $this->getVehiculosParaNotificar($idSucursal, $fechaIni, $fechaFin);
        $fechaHoy = carbon::now()->startOfDay();

        for ($i = 0; $i < count($listaSalidaVehicular); $i++) {
            $fechaSalida = Carbon::parse($listaSalidaVehicular[$i]->FechaSalida)->startOfDay();
            $fechaProxima = Carbon::parse($listaSalidaVehicular[$i]->ProximaFecha)->startOfDay();
            $diasAvanzados = $fechaHoy->diffInDays($fechaSalida);
            $diasRestantes = $fechaHoy->diffInDays($fechaProxima);
            if ($diasAvanzados <= $listaSalidaVehicular[$i]->Periodo) {
                if (($diasAvanzados * 100 / $listaSalidaVehicular[$i]->Periodo) <= 40) {
                    $estado = 'Km Inical';
                    $color = '#28A745';
                } elseif (($diasAvanzados * 100 / $listaSalidaVehicular[$i]->Periodo) >= 41 && ($diasAvanzados * 100 / $listaSalidaVehicular[$i]->Periodo) <= 70) {
                    $estado = 'Km Intermedio';
                    $color = '#148CBA';
                } elseif (($diasAvanzados * 100 / $listaSalidaVehicular[$i]->Periodo) >= 71 && ($diasAvanzados * 100 / $listaSalidaVehicular[$i]->Periodo) <= 90) {
                    $estado = 'Km AProximado';
                    $color = '#FFC107';
                } elseif (($diasAvanzados * 100 / $listaSalidaVehicular[$i]->Periodo) >= 91) {
                    $estado = 'Km Alcanzado';
                    $color = '#ff0000';
                }
                DB::table('notificacion_mantenimiento')
                    ->where('IdSucursal', $idSucursal)
                    ->where('PlacaVehiculo', $listaSalidaVehicular[$i]->PlacaVehiculo)
                    ->update(['DiasAvanzados' => $diasAvanzados, 'DiasRestantes' => $diasRestantes, 'Estado' => $estado, 'ColorEstado' => $color]);
            }
            // else {
            //     DB::table('notificacion_mantenimiento')
            //         ->where('IdSucursal', $idSucursal)
            //         ->where('PlacaVehiculo', $listaSalidaVehicular[$i]->PlacaVehiculo)
            //         ->update(['DiasAvanzados' => $listaSalidaVehicular[$i]->Periodo, 'DiasRestantes' => 0, 'Estado' => 'Km Alcanzado', 'ColorEstado' => '#ff0000']);

            // }
        }
    }

    public function getVehiculosParaNotificar($idSucursal, $fechaIni, $fechaFin)
    {
        $datos = DB::table('notificacion_mantenimiento as nm')
            ->join('vehiculo', 'nm.IdVehiculo', '=', 'vehiculo.IdVehiculo')
            ->join('cliente', 'vehiculo.IdCliente', '=', 'cliente.IdCliente')
            ->select('nm.*', 'cliente.Nombre')
            ->where('nm.IdSucursal', $idSucursal)
            ->whereNotNull('nm.Periodo')
            ->whereBetween('nm.FechaSalida', [$fechaIni, $fechaFin])
            ->get();
        return ($datos);
    }

}
