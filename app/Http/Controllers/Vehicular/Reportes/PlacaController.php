<?php

namespace App\Http\Controllers\Vehicular\Reportes;

use App\Exports\ExcelReporteVehicularPlaca;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class PlacaController extends Controller
{
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {

            $idUsuario = Session::get('idUsuario');
            $arrayVehicular = [];
            $arrayAtenciones = [];

            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $fechas = $loadDatos->getFechaFiltro(5, null, null);

            $reporteVehiculares = DB::select('call sp_getVehicularPlacas(?, ?, ?, ?)', array($idSucursal, null, $fechas[0], $fechas[1]));
            for ($i = 0; $i < count($reporteVehiculares); $i++) {
                $_productos = $loadDatos->getProductosVendidos($reporteVehiculares[$i]->IdVentas);
                $reporteVehiculares[$i]->Productos = $_productos;
            }

            $placas = $loadDatos->getPlacas($idSucursal);
            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $inputPlaca = '';
            $_inputPlaca = 0;
            $tipo = '';
            $fecha = 5;
            $fechaIni = $fechas[0];
            $fechaFin = $fechas[1];

            $reportePlacas = $this->reportePlacas(null, $idSucursal, $fechas);

            if (count($reportePlacas) >= 1) {
                $i = 0;
                foreach ($reportePlacas as $repPlaca) {
                    $arrayVehicular[$i] = "'$repPlaca->PlacaVehiculo'";
                    $arrayAtenciones[$i] = $repPlaca->total;
                    $i++;
                }
            }

            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

            $array = ['grafvehiculos' => $arrayVehicular, 'grafTotal' => $arrayAtenciones, 'placas' => $placas, 'reporteVehiculares' => $reporteVehiculares, 'inputPlaca' => $inputPlaca, '_inputPlaca' => $_inputPlaca, 'tipo' => $tipo, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'modulosSelect' => $modulosSelect,
                'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
            return view('vehicular/reportes/placa', $array);
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

        $arrayVehicular = [];
        $arrayAtenciones = [];

        $loadDatos = new DatosController();
        $inputPlaca = $req->placa;
        // if ($inputPlaca == null) {
        //     $_inputPlaca = 0;
        // } else {
        //     $_inputPlaca = $inputPlaca;
        // }
        if ($inputPlaca == 0) {
            $inputPlaca = null;
        } else {
            $inputPlaca = $inputPlaca;
        }

        $_inputPlaca = $req->placa;

        $fecha = $req->fecha;
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        $tipo = 1;
        if ($fecha == 9) {
            if ($fechaIni == null || $fechaFin == null) {
                return back()->with('error', 'Completar las fechas para filtrar');
            }
            $dateIni = DateTime::createFromFormat('d/m/Y', $fechaIni);
            $dateFin = DateTime::createFromFormat('d/m/Y', $fechaFin);
            if (strtotime($dateIni->format('Y-m-d')) > strtotime($dateFin->format('Y-m-d'))) {
                return back()->with('error', 'La fecha Inicial no puede ser mayor que la Final');
            }

        }

        $idSucursal = $idSucursal = Session::get('idSucursal');

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);

        $reporteVehiculares = DB::select('call sp_getVehicularPlacas(?, ?, ?, ?)', array($idSucursal, $inputPlaca, $fechas[0], $fechas[1]));
        for ($i = 0; $i < count($reporteVehiculares); $i++) {
            $_productos = $loadDatos->getProductosVendidos($reporteVehiculares[$i]->IdVentas);
            $reporteVehiculares[$i]->Productos = $_productos;
        }
        $placas = $loadDatos->getPlacas($idSucursal);
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        if ($fechaIni == null && $fechaFin == null) {
            $fechaIni = $fechas[0];
            $fechaFin = $fechas[1];
        }

        $fechaIni = str_replace('/', '-', $fechaIni);
        $fechaFin = str_replace('/', '-', $fechaFin);

        $reportePlacas = $this->reportePlacas($inputPlaca, $idSucursal, $fechas);

        if (count($reportePlacas) >= 1) {
            $i = 0;
            foreach ($reportePlacas as $repPlaca) {
                $arrayVehicular[$i] = "'$repPlaca->PlacaVehiculo'";
                $arrayAtenciones[$i] = $repPlaca->total;
                $i++;
            }
        }

        $array = ['grafvehiculos' => $arrayVehicular, 'grafTotal' => $arrayAtenciones, 'placas' => $placas, 'reporteVehiculares' => $reporteVehiculares, 'inputPlaca' => $inputPlaca, '_inputPlaca' => $_inputPlaca, 'tipo' => $tipo, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'modulosSelect' => $modulosSelect,
            'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('vehicular/reportes/placa', $array);
    }

    private function reportePlacas($placa, $idSucursal, $fechas)
    {
        if ($placa == null) {
            $reportePlacas = DB::table('atencion_vehicular')
                ->join('vehiculo', 'atencion_vehicular.IdVehiculo', '=', 'vehiculo.IdVehiculo')
                ->where('atencion_vehicular.IdSucursal', $idSucursal)
                ->select(DB::raw('count(*) as total, vehiculo.PlacaVehiculo'))
                ->whereBetween('atencion_vehicular.FechaAtencion', [$fechas[0], $fechas[1]])
                ->groupBy(DB::raw("vehiculo.PlacaVehiculo"))
                ->limit(10)
                ->get();
        } else {
            $reportePlacas = DB::table('atencion_vehicular')
                ->join('vehiculo', 'atencion_vehicular.IdVehiculo', '=', 'vehiculo.IdVehiculo')
                ->where('atencion_vehicular.IdSucursal', $idSucursal)
                ->select(DB::raw('count(*) as total, vehiculo.PlacaVehiculo'))
                ->where('vehiculo.PlacaVehiculo', $placa)
                ->whereBetween('atencion_vehicular.FechaAtencion', [$fechas[0], $fechas[1]])
                ->groupBy(DB::raw("vehiculo.PlacaVehiculo"))
                ->limit(10)
                ->get();
        }

        return $reportePlacas;
    }

    public function exportExcel($inputPlaca, $fecha, $ini, $fin)
    {
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');

        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);

        if ($inputPlaca == '0') {
            $inputPlaca = null;
        }
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $reporteVehiculares = DB::select('call sp_getPlacasVehicularesParaExcel(?, ?, ?, ?)', array($idSucursal, $inputPlaca, $fechas[0], $fechas[1]));
        return Excel::download(new ExcelReporteVehicularPlaca($reporteVehiculares), 'Reporte Vehicular - Placas.xlsx');
    }
}
