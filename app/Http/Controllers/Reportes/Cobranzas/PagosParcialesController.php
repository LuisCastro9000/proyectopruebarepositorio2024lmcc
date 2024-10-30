<?php

namespace App\Http\Controllers\Reportes\Cobranzas;

use App\Exports\ExcelReporteCobrosParciales;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class PagosParcialesController extends Controller
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

        $idSucursal = Session::get('idSucursal');
        $fechas = $loadDatos->getFechaFiltro(5, null, null);

        $pagosParciales = DB::select('call sp_getPagosParciales(?, ?, ?)', array($idSucursal, $fechas[0], $fechas[1]));

        //$pagosParciales = $loadDatos->getPagosParciales($idSucursal);
        $fecha = 5;
        $fechaIni = '0';
        $fechaFin = '0';
        $ini = '0';
        $fin = '0';
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['permisos' => $permisos, 'pagosParciales' => $pagosParciales, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('reportes/cobranzas/reportesPagosParciales', $array);
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
        if ($fecha == 9) {
            if ($fechaIni == null || $fechaFin == null) {
                return back()->with('error', 'Completar las fechas para filtrar');
            }
            if (Carbon::createFromFormat('d/m/Y', $fechaIni) > Carbon::createFromFormat('d/m/Y', $fechaFin)) {
                return back()->with('error', 'La fecha Inicial no puede ser mayor que la Final');
            }
        }
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $pagosParciales = DB::select('call sp_getPagosParciales(?, ?, ?)', array($idSucursal, $fechas[0], $fechas[1]));
        //$pagosParciales = $loadDatos->getPagosParcialesFiltrados($idSucursal, $fecha, $fechaIni, $fechaFin);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $ini = str_replace('/', '-', $fechaIni);
        $fin = str_replace('/', '-', $fechaFin);
        $array = ['pagosParciales' => $pagosParciales, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('reportes/cobranzas/reportesPagosParciales', $array);
    }

    public function exportExcel($fecha, $ini, $fin)
    {
        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');
        $idSucursal = Session::get('idSucursal');

        $fechaHoy = $loadDatos->getDateTime();
        //dd($fechas);

        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);
        /*     $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin); */
        if ($fecha == '0') {
            $pagosParciales = $loadDatos->getPagosParciales($idSucursal);
        } else {
            $pagosParciales = $loadDatos->getPagosParcialesFiltrados($idSucursal, $fecha, $fechaIni, $fechaFin);
        }

        return Excel::download(new ExcelReporteCobrosParciales($pagosParciales), 'Reporte Cobranzas - Cobros Parciales.xlsx');
        /*$array = ['pagosParciales' => $pagosParciales];

    Excel::create('Reporte Pagos Parciales', function ($excel) use($array){
    $excel->sheet('Pagos Parciales', function ($sheet) use($array) {
    $sheet->loadView('pagosParciales', $array);
    });
    })->download('xlsx');*/
    }
}
