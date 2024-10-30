<?php

namespace App\Http\Controllers\Reportes\Cobranzas;

use App\Exports\ExcelReporteCobranzasCreditosVencidos;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class CreditosVencidosController extends Controller
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

        $idSucursal = $idSucursal = Session::get('idSucursal');
        $fechas = $loadDatos->getFechaFiltro(5, null, null);
        $creditosVencidos = DB::select('call sp_getCreditosVencidos(?, ?, ?)', array($idSucursal, $fechas[0], $fechas[1]));

        //$creditosVencidos = $loadDatos->getCreditosVencidos($idSucursal);
        $fecha = 5;
        $fechaIni = '0';
        $fechaFin = '0';
        $ini = '0';
        $fin = '0';
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['permisos' => $permisos, 'creditosVencidos' => $creditosVencidos, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('reportes/cobranzas/reportesCreditosVencidos', $array);
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
        $creditosVencidos = DB::select('call sp_getCreditosVencidos(?, ?, ?)', array($idSucursal, $fechas[0], $fechas[1]));

        //$creditosVencidos = $loadDatos->getCreditosVencidosFiltrados($idSucursal, $fecha, $fechaIni, $fechaFin);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $ini = str_replace('/', '-', $fechaIni);
        $fin = str_replace('/', '-', $fechaFin);
        $array = ['creditosVencidos' => $creditosVencidos, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('reportes/cobranzas/reportesCreditosVencidos', $array);
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
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        if ($fecha == '0') {
            $creditosVencidos = $loadDatos->getCreditosVencidos($idSucursal);
        } else {
            $creditosVencidos = $loadDatos->getCreditosVencidosFiltrados($idSucursal, $fecha, $fechaIni, $fechaFin);
        }

        return Excel::download(new ExcelReporteCobranzasCreditosVencidos($creditosVencidos), 'Reporte Creditos Vencidos.xlsx');
        /*
    $array = ['creditosVencidos' => $creditosVencidos];

    Excel::create('Reporte Creditos Vencidos', function ($excel) use($array){
    $excel->sheet('Creditos Vencidos', function ($sheet) use($array) {
    $sheet->getStyle('A:I', $sheet->getHighestRow())->getAlignment()->setWrapText(false);
    $sheet->loadView('creditoVencidos', $array);
    });
    })->download('xlsx');*/
    }
}
