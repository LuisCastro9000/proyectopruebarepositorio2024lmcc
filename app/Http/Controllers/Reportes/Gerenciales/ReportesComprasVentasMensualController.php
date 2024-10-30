<?php

namespace App\Http\Controllers\Reportes\Gerenciales;

use App\Exports\ExcelReporteComprasVentas;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use App\Http\Controllers\Reportes\AjusteFechasReportesController;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class ReportesComprasVentasMensualController extends Controller
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
        $fechasReportes = new AjusteFechasReportesController();
        $idSucursal = $idSucursal = Session::get('idSucursal');
        // $arrayComprasVentas = $loadDatos->getComprasVentas($idSucursal);
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
        // $arrayComprasVentas  = $loadDatos->getComprasVentasFiltrados($idSucursal, $tipoPago, $fecha, $fechaIni, $fechaFin);
        $arrayComprasVentas = DB::select('call sp_getComprasVentasFiltrado(?, ?, ?, ?)', array($idSucursal, $tipoPago, $fechas[0], $fechas[1]));
        // Fin
        $comprasVentas = $fechasReportes->ajustarFechas($arrayComprasVentas, $fecha, $fechaIni, $fechaFin);
        $array = ['comprasVentas' => $comprasVentas, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => '0', 'fin' => '0', 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('reportes/gerenciales/reporteCompraVentaMensual', $array);
    }

    public function store(Request $req)
    {
        $loadDatos = new DatosController();
        $fechasReportes = new AjusteFechasReportesController();
        $tipoPago = $req->tipoPago;
        $fecha = $req->fecha;
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        if ($fecha == 9) {
            if ($fechaIni == null || $fechaFin == null) {
                return back()->with('error', 'Completar las fechas para filtrar');
            }
            if ($fechaIni > $fechaFin) {
                return back()->with('error', 'La fecha Inicial no puede ser mayor que la Final');
            }
        }
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        // $arrayComprasVentas = $loadDatos->getComprasVentasFiltrados($idSucursal, $tipoPago, $fecha, $fechaIni, $fechaFin);
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $arrayComprasVentas = DB::select('call sp_getComprasVentasFiltrado(?, ?, ?, ?)', array($idSucursal, $tipoPago, $fechas[0], $fechas[1]));
        //dd($arrayComprasVentas);
        $comprasVentas = $fechasReportes->ajustarFechas($arrayComprasVentas, $fecha, $fechaIni, $fechaFin);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $ini = str_replace('/', '-', $fechaIni);
        $fin = str_replace('/', '-', $fechaFin);

        $array = ['comprasVentas' => $comprasVentas, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('reportes/gerenciales/reporteCompraVentaMensual', $array);
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
        //   $reporteComprasVentas  = $loadDatos->getComprasVentasFiltrados($idSucursal, $tipoPago, $fecha, $fechaIni, $fechaFin);
        $reporteComprasVentas = DB::select('call sp_getComprasVentasFiltrado(?, ?, ?, ?)', array($idSucursal, $tipoPago, $fechas[0], $fechas[1]));

        //    dd($reporteComprasVentas);

        return Excel::download(new ExcelReporteComprasVentas($reporteComprasVentas), 'Reporte Gerencial - Compras-ventas.xlsx');

    }
}
