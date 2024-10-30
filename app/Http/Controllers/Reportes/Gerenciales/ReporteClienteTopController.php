<?php

namespace App\Http\Controllers\Reportes\Gerenciales;

use App\Exports\ExcelReporteClientesTop;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class ReporteClienteTopController extends Controller
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
        $idSucursal = $idSucursal = Session::get('idSucursal');
        // $reporteClientesTop = $loadDatos->getClientesTop($idSucursal);
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $tipoPago = 0;
        $fecha = 5;
        $fechaIni = '';
        $fechaFin = '';
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        // nuevas lineas agregadas
        $fechas = $loadDatos->getFechaFiltro(5, null, null);
        // $reporteClientesTop  = $loadDatos->getClientesTopFiltrados($idSucursal, $tipoPago, $fecha, $fechaIni, $fechaFin);

        // SE AGREGO EL PA
        $reporteClientesTop = DB::select('call sp_getClientesTop(?, ?, ?, ?)', array($idSucursal, 0, $fechas[0], $fechas[1]));
        // Fin
        $array = ['reporteClientesTop' => $reporteClientesTop, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => '0', 'fin' => '0', 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('reportes/gerenciales/reporteClientesTop', $array);
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
        $tipoPago = $req->tipoPago;
        $fecha = $req->fecha;
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        if ($fecha == 9) {
            if ($fechaIni == null || $fechaFin == null) {
                return back()->with('error', 'Completar las fechas para filtrar');
            }
            if (Carbon::createFromFormat('d/m/Y', $fechaIni) > Carbon::createFromFormat('d/m/Y', $fechaFin)) {
                return back()->with('error', 'La fecha Inicial no puede ser mayor que la Final');
            }
        }
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        // $reporteClientesTop = $loadDatos->getClientesTopFiltrados($idSucursal, $tipoPago, $fecha, $fechaIni, $fechaFin);
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        // SE AGREGO EL PA
        $reporteClientesTop = DB::select('call sp_getClientesTop(?, ?, ?, ?)', array($idSucursal, $tipoPago, $fechas[0], $fechas[1]));

        $ini = str_replace('/', '-', $fechaIni);
        $fin = str_replace('/', '-', $fechaFin);

        $array = ['reporteClientesTop' => $reporteClientesTop, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('reportes/gerenciales/reporteClientesTop', $array);
    }

    //  Se agrego la funcion exportExcel
    public function exportExcel($tipoPago = null, $fecha = null, $ini = null, $fin = null)
    {

        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');
        $idSucursal = Session::get('idSucursal');

        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $reporteClientesTop = $loadDatos->getClientesTopFiltrados($idSucursal, $tipoPago, $fecha, $fechaIni, $fechaFin);

        //    dd($reporteClientesTop);

        return Excel::download(new ExcelReporteClientesTop($reporteClientesTop), 'Reporte Gerencial - Clientes-Top.xlsx');

    }
}
