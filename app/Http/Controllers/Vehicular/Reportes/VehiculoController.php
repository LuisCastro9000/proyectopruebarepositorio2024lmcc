<?php

namespace App\Http\Controllers\Vehicular\Reportes;

use App\Exports\ExcelReporteVehicularVehiculos;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class VehiculoController extends Controller
{
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {

            $idUsuario = Session::get('idUsuario');

            $loadDatos = new DatosController();
            $idSucursal = $idSucursal = Session::get('idSucursal');
            $fechas = $loadDatos->getFechaFiltro(5, null, null);
            $reporteVehiculos = DB::select('call sp_getVehicularVehiculos(?, ?, ?)', array($idSucursal, $fechas[0], $fechas[1]));
            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $tipo = '';
            $fechaIni = '';
            $fechaFin = '';
            $fecha = 5;
            $ini = $fechas[0];
            $fin = $fechas[1];

            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

            $array = ['reporteVehiculos' => $reporteVehiculos, 'tipo' => $tipo, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'modulosSelect' => $modulosSelect,
                'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
            return view('vehicular/reportes/vehiculo', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
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
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        $tipo = 1;
        if ($fecha == 9) {
            if ($fechaIni == null || $fechaFin == null) {
                return back()->with('error', 'Completar las fechas para filtrar');
            }
            if (Carbon::create($fechaIni)->gt(Carbon::create($fechaFin))) {
                return back()->with('error', 'La fecha Inicial no puede ser mayor que la Final');
            }
        }

        $idSucursal = $idSucursal = Session::get('idSucursal');

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);

        $reporteVehiculos = DB::select('call sp_getVehicularVehiculos(?, ?, ?)', array($idSucursal, $fechas[0], $fechas[1]));

        if ($fechaIni == null && $fechaFin == null) {
            $fechaIni = $fechas[0];
            $fechaFin = $fechas[1];
        }

        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $fechaIni = str_replace('-', '/', $fechaIni);
        $fechaFin = str_replace('-', '/', $fechaFin);

        $ini = str_replace('/', '-', $fechaIni);
        $fin = str_replace('/', '-', $fechaFin);

        $array = ['reporteVehiculos' => $reporteVehiculos, 'tipo' => $tipo, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'modulosSelect' => $modulosSelect,
            'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('vehicular/reportes/vehiculo', $array);
    }

    public function exportExcel($fecha, $ini, $fin)
    {
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');

        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $reporteVehiculos = DB::select('call sp_getVehicularVehiculos(?, ?, ?)', array($idSucursal, $fechas[0], $fechas[1]));

        return Excel::download(new ExcelReporteVehicularVehiculos($reporteVehiculos), 'Reporte Vehicular - Vehículos.xlsx');

        /*$array = ['reporteVehiculos' => $reporteVehiculos];

    Excel::create('Reporte Vehiculares Vehiculos', function ($excel) use($array){
    $excel->sheet('Reporte Vehiculares Vehiculos', function ($sheet) use($array) {
    $sheet->loadView('excel/excelVehicularesVehiculo', $array);
    });
    })->download('xlsx');*/
    }

}
