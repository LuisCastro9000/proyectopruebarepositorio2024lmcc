<?php

namespace App\Http\Controllers\Reportes\Cobranzas;

use App\Exports\ExcelReporteCobranzasVentasPorCobrar;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DateTime;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class VentasPorCobrarController extends Controller
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

        //$cobranzas = $loadDatos->getCobranzas($idSucursal);
        $fechas = $loadDatos->getFechaFiltro(5, null, null);
        $cobranzas = DB::select('call sp_getCobranzas(?, ?, ?, ?)', array($idSucursal, null, $fechas[0], $fechas[1]));
        $clientes = $loadDatos->getClientes($idSucursal);
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $fechaHoy = $loadDatos->getDateTime();
        $cobranzasTotales = $loadDatos->getCobranzasTotales($idSucursal, $fechaHoy);
        $this->actualizarFechasPasados($idSucursal, $cobranzasTotales);

        /*********************fecha  pago**********************/
        //$fecha_pago=DB::table('fecha_pago')->get();
        /******************************************************/

        $inputcliente = 0;
        $fecha = 5;
        $fechaIni = 0;
        $fechaFin = 0;
        $ini = '0';
        $fin = '0';
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['permisos' => $permisos, 'clientes' => $clientes, 'cobranzas' => $cobranzas, 'inputcliente' => $inputcliente, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('reportes/cobranzas/reportesVentasPorCobrar', $array);
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
        $idSucursal = Session::get('idSucursal');

        $fechaHoy = $loadDatos->getDateTime();
        $cobranzasTotales = $loadDatos->getCobranzasTotales($idSucursal, $fechaHoy);
        $this->actualizarFechasPasados($idSucursal, $cobranzasTotales);

        $_inputcliente = $req->cliente;
        if ($_inputcliente == null) {
            $inputcliente = 0;
        } else {
            $inputcliente = $_inputcliente;
        }
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

        $idUsuario = Session::get('idUsuario');
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        /*********************fecha  pago**********************/
        //$fecha_pago=DB::table('fecha_pago')->get();
        /******************************************************/

        $clientes = $loadDatos->getClientes($idSucursal);
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        //$cobranzas = $loadDatos->getCobranzasFiltrados($idSucursal, $inputcliente, $fechas[0], $fechas[1]);
        $cobranzas = DB::select('call sp_getCobranzas(?, ?, ?, ?)', array($idSucursal, $_inputcliente, $fechas[0], $fechas[1]));

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $ini = str_replace('/', '-', $fechaIni);
        $fin = str_replace('/', '-', $fechaFin);
        $array = ['permisos' => $permisos, 'clientes' => $clientes, 'cobranzas' => $cobranzas, 'inputcliente' => $inputcliente, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('reportes/cobranzas/reportesVentasPorCobrar', $array);
    }

    private function actualizarFechasPasados($idSucursal, $noVencidos)
    {
        for ($i = 0; $i < count($noVencidos); $i++) {
            DB::table('fecha_pago')
                ->join('ventas', 'fecha_pago.IdVenta', '=', 'ventas.IdVentas')
                ->where('ventas.IdSucursal', $idSucursal)
                ->where('fecha_pago.Estado', '!=', 2)
                ->where('fecha_pago.IdFechaPago', $noVencidos[$i]->IdFechaPago)
                ->update(['DiasPasados' => $noVencidos[$i]->Dias]);
        }
    }

    public function exportExcel($cliente, $fecha, $ini, $fin)
    {
        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');
        $idSucursal = Session::get('idSucursal');

        $fechaHoy = $loadDatos->getDateTime();
        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);
        if ($cliente == '0') {
            $cliente = null;
        }
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        /*if($cliente=='0' && $fecha  == '0' )
        {
        $fecha_pago=DB::table('fecha_pago')->get();
        $cobranzas = $loadDatos->getCobranzas($idSucursal);
        }
        else
        {
        $fecha_pago=DB::table('fecha_pago')->get();
        $cobranzas = $loadDatos->getCobranzasFiltrados($idSucursal, $cliente, $fecha, $fechas[0], $fechas[1]);

        }*/

        $cobranzas = DB::select('call sp_getCobranzas(?, ?, ?, ?)', array($idSucursal, $cliente, $fechas[0], $fechas[1]));

        return Excel::download(new ExcelReporteCobranzasVentasPorCobrar($cobranzas), 'Reporte Ventas Por Cobrar.xlsx');

        /*$array = ['cobranzas' => $cobranzas];

    Excel::create('Reporte Ventas por Cobrar', function ($excel) use($array){
    $excel->sheet('Ventas por Cobrar', function ($sheet) use($array) {
    $sheet->loadView('excel/excelVentasPorCobrar', $array);
    });
    })->download('xlsx');*/
    }

    private function getFechaFiltro($fecha, $fechaIni, $fechaFin)
    {
        if ($fecha == 0) {
            $fechaInicio = '1900-01-01';
            $fechaFinal = Carbon::now();
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 1) {
            $fechaInicio = Carbon::today();
            $fechaFinal = Carbon::now();
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 2) {
            $fechaInicio = Carbon::yesterday();
            $fechaFinal = Carbon::today();
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 3) {
            $datePrev = Carbon::today()->dayOfWeek;
            $date = Carbon::today();
            $fechaInicio = $date->subDays($datePrev - 1);
            $fechaFinal = Carbon::now();
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 4) {
            $datePrev = Carbon::today()->dayOfWeek;
            $date1 = Carbon::today();
            $date2 = Carbon::today();
            $fechaFinal = $date1->subDays($datePrev - 1);
            $fechaInicio = $date2->subDays($datePrev + 6);
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 5) {
            $datePrev = Carbon::today()->day;
            $date = Carbon::today();
            $fechaInicio = $date->subDays($datePrev - 1);
            $fechaFinal = Carbon::now();
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 6) {
            $datePrev = Carbon::today()->day;
            $mesPasado = Carbon::today()->subMonth(1)->firstOfMonth();
            $date1 = Carbon::today();
            $fechaFinal = $date1->subDays($datePrev - 1);
            $fechaInicio = $mesPasado;
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 7) {
            $datePrev = Carbon::today()->firstOfYear();
            $fechaInicio = $datePrev;
            $fechaFinal = Carbon::now();
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 8) {
            $fechaInicio = Carbon::today()->subYear(1)->firstOfYear();
            $fechaFinal = Carbon::today()->subYear(1)->endOfYear();
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 9) {
            $fechaInicio = DateTime::createFromFormat('d/m/Y', $fechaIni);
            $fechaFinal = DateTime::createFromFormat('d/m/Y', $fechaFin);
            $fechaConvertidaInicio = $fechaInicio->format("Y-m-d");
            $fechaConvertidaFinal = $fechaFinal->format("Y-m-d");
            $fechaConvertidaFinal = strtotime('+1 day', strtotime($fechaConvertidaFinal));
            $fechaConvertidaFinal = date('Y-m-d', $fechaConvertidaFinal);
            return array($fechaConvertidaInicio, $fechaConvertidaFinal);
        }
    }
}
