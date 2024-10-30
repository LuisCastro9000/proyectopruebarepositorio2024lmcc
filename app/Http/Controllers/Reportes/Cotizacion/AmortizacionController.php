<?php

namespace App\Http\Controllers\Reportes\Cotizacion;

use App\Exports\ExcelReporteAmortizaciones;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class AmortizacionController extends Controller
{
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {

            $idUsuario = Session::get('idUsuario');

            $loadDatos = new DatosController();
            $idSucursal = $idSucursal = Session::get('idSucursal');
            $fechas = $loadDatos->getFechaFiltro(5, null, null);

            $reporteAmortizaciones = DB::select('call sp_getAmortizaciones(?, ?, ?, ?)', array($idSucursal, 0, $fechas[0], $fechas[1]));

            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $tipo = '';
            $fecha = 5;
            $fechaIni = $fechas[0];
            $fechaFin = $fechas[1];
            $vendedor = 0;

            $usuarios = $this->getUsuarios($idSucursal);
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

            $arrayUsuarios = [];
            $arrayCantidad = [];
            $arrayTotal = [];

            $arrayAmortizacionesUsuario = $this->getAmortizacionUsuarios($idSucursal, 0, 1, $fechaIni, $fechaFin);
            if (count($arrayAmortizacionesUsuario) > 0) {
                $i = 0;
                foreach ($arrayAmortizacionesUsuario as $amortizacion) {
                    $arrayUsuarios[$i] = $amortizacion->Nombre;
                    $arrayCantidad[$i] = $amortizacion->Cantidad;
                    $arrayTotal[$i] = $amortizacion->Total;
                    $i++;
                }
            }

            $array = ['arrayUsuarios' => $arrayUsuarios, 'arrayCantidad' => $arrayCantidad, 'arrayTotal' => $arrayTotal, 'reporteAmortizaciones' => $reporteAmortizaciones, 'vendedor' => $vendedor, 'usuarios' => $usuarios, 'tipo' => $tipo, 'fecha' => $fecha, 'ini' => '0', 'fin' => '0', 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'modulosSelect' => $modulosSelect,
                'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
            return view('reportes/cotizacion/amortizacion', $array);
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
        $vendedor = $req->vendedor;
        if ($fecha == 9) {
            if ($fechaIni == null || $fechaFin == null) {
                return back()->with('error', 'Completar las fechas para filtrar');
            }
            if (Carbon::createFromFormat('d/m/Y', $fechaIni) > Carbon::createFromFormat('d/m/Y', $fechaFin)) {
                return back()->with('error', 'La fecha Inicial no puede ser mayor que la Final');
            }
        }

        $idSucursal = $idSucursal = Session::get('idSucursal');

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);

        $reporteAmortizaciones = DB::select('call sp_getAmortizaciones(?, ?, ?, ?)', array($idSucursal, $vendedor, $fechas[0], $fechas[1]));
        //$reporteVehiculos = DB::select('call sp_getVehicularVehiculos(?, ?, ?)',array($idSucursal, $fechas[0], $fechas[1]));

        if ($fechaIni == null && $fechaFin == null) {
            $fechaIni = $fechas[0];
            $fechaFin = $fechas[1];
        }

        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $usuarios = $this->getUsuarios($idSucursal);

        $arrayUsuarios = [];
        $arrayCantidad = [];
        $arrayTotal = [];

        $arrayAmortizacionesUsuario = $this->getAmortizacionUsuarios($idSucursal, $vendedor, 1, $fechaIni, $fechaFin);

        if (count($arrayAmortizacionesUsuario) > 0) {
            $i = 0;
            foreach ($arrayAmortizacionesUsuario as $amortizacion) {
                $arrayUsuarios[$i] = $amortizacion->Nombre;
                $arrayCantidad[$i] = $amortizacion->Cantidad;
                $arrayTotal[$i] = $amortizacion->Total;
                $i++;
            }
        }

        $ini = str_replace('/', '-', $fechaIni);
        $fin = str_replace('/', '-', $fechaFin);
        $tipo = 1;

        $array = ['arrayUsuarios' => $arrayUsuarios, 'arrayCantidad' => $arrayCantidad, 'arrayTotal' => $arrayTotal, 'reporteAmortizaciones' => $reporteAmortizaciones, 'vendedor' => $vendedor, 'usuarios' => $usuarios, 'tipo' => $tipo, 'fecha' => $fecha, 'ini' => $ini, 'fin' => $fin, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'modulosSelect' => $modulosSelect,
            'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('reportes/cotizacion/amortizacion', $array);
    }

    public function exportExcel($vendedor, $fecha, $ini, $fin)
    {
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');

        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        //$vendedor = $vendedor;
        $reporteAmortizaciones = DB::select('call sp_getAmortizaciones(?, ?, ?, ?)', array($idSucursal, $vendedor, $fechas[0], $fechas[1]));

        return Excel::download(new ExcelReporteAmortizaciones($reporteAmortizaciones), 'Reporte Cotización - Amortizaciones.xlsx');

        /*$array = ['reporteVehiculos' => $reporteVehiculos];

    Excel::create('Reporte Vehiculares Vehiculos', function ($excel) use($array){
    $excel->sheet('Reporte Vehiculares Vehiculos', function ($sheet) use($array) {
    $sheet->loadView('excel/excelVehicularesVehiculo', $array);
    });
    })->download('xlsx');*/
    }

    private function getUsuarios($idSucursal)
    {
        $usuarios = DB::table('usuario')
            ->where('IdSucursal', $idSucursal)
            ->where('Estado', 'E')
            ->get();
        return $usuarios;
    }

    private function getAmortizacionUsuarios($idSucursal, $idUsuario, $idTipoMoneda, $fechaIni, $fechaFin)
    {
        if ($idUsuario == 0) {
            $amortizaciones = DB::table('amortizacion')
                ->join('usuario', 'usuario.IdUsuario', '=', 'amortizacion.IdUsuario')
                ->select('usuario.Nombre', 'usuario.IdUsuario', DB::raw('SUM(amortizacion.Monto) as Total'), DB::raw("count(*) as Cantidad"))
                ->where('amortizacion.IdSucursal', $idSucursal)
                ->where('amortizacion.IdTipoMoneda', $idTipoMoneda)
                ->whereBetween('amortizacion.FechaIngreso', [$fechaIni, $fechaFin])
                ->groupBy(DB::raw("amortizacion.IdUsuario"), DB::raw("amortizacion.IdTipoMoneda"))
                ->get();
        } else {
            $amortizaciones = DB::table('amortizacion')
                ->join('usuario', 'usuario.IdUsuario', '=', 'amortizacion.IdUsuario')
                ->select('usuario.Nombre', 'usuario.IdUsuario', DB::raw('SUM(amortizacion.Monto) as Total'), DB::raw("count(*) as Cantidad"))
                ->where('usuario.IdUsuario', $idUsuario)
                ->where('amortizacion.IdSucursal', $idSucursal)
                ->where('amortizacion.IdTipoMoneda', $idTipoMoneda)
                ->whereBetween('amortizacion.FechaIngreso', [$fechaIni, $fechaFin])
                ->groupBy(DB::raw("amortizacion.IdUsuario"), DB::raw("amortizacion.IdTipoMoneda"))
                ->get();
        }
        return $amortizaciones;
    }

}
