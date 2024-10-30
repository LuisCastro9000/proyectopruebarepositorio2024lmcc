<?php

namespace App\Http\Controllers\Reportes\Gerenciales;

use App\Exports\ExcelReporteGerencialesIngresosEgresos;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class ReportesIngresosEgresosController extends Controller
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
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        // $fechas = $loadDatos->getFechaFiltro(0, null, null);
        // $ingresosEgresos = DB::select('call sp_getIngresosEgresos(?, ?, ?)',array($idSucursal, $fechas[0], $fechas[1]));
        $idSucursal = Session::get('idSucursal');

        $fecha = 5;
        $fechaIni = '0';
        $fechaFin = '0';
        $fechas = $loadDatos->getFechaFiltro(5, null, null);
        $ini = str_replace('/', '-', $fechaIni);
        $fin = str_replace('/', '-', $fechaFin);

        $ingresosEgresos = DB::select('call sp_getIngresosEgresos(?, ?, ?)', array($idSucursal, $fechas[0], $fechas[1]));
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['permisos' => $permisos, 'ingresosEgresos' => $ingresosEgresos, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'fechaIni' => $ini, 'fechaFin' => $fin];
        return view('reportes/gerenciales/reporteIngresosEgresos', $array);
    }

    public function store(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $fecha = $req->fecha;
        $fechaIni = trim($req->fechaIni) == false ? '0' : $req->fechaIni;
        $fechaFin = trim($req->fechaFin) == false ? '0' : $req->fechaFin;
        $ini = str_replace('/', '-', $fechaIni);
        $fin = str_replace('/', '-', $fechaFin);

        if ($fecha == 9) {
            if ($fechaIni == null || $fechaFin == null) {
                return back()->with('error', 'Completar las fechas para filtrar');
            }
            if ($fechaIni > $fechaFin) {
                return back()->with('error', 'La fecha Inicial no puede ser mayor que la Final');
            }
        }
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $ingresosEgresos = DB::select('call sp_getIngresosEgresos(?, ?, ?)', array($idSucursal, $fechas[0], $fechas[1]));

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['ingresosEgresos' => $ingresosEgresos, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'fechaIni' => $ini, 'fechaFin' => $fin];
        return view('reportes/gerenciales/reporteIngresosEgresos', $array);
    }

    //  Se agrego la funcion exportExcel
    public function exportExcel($fecha, $ini, $fin)
    {
        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');
        $idSucursal = Session::get('idSucursal');

        $fechaHoy = $loadDatos->getDateTime();
        //dd($fechas);

        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $ingresosEgresos = DB::select('call sp_getIngresosEgresos(?, ?, ?)', array($idSucursal, $fechas[0], $fechas[1]));

        // dd($ingresosEgresos);
        return Excel::download(new ExcelReporteGerencialesIngresosEgresos($ingresosEgresos), 'Reporte Gerencial - Ingresos-Egresos.xlsx');
    }
}
