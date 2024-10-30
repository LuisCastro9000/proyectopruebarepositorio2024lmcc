<?php

namespace App\Http\Controllers\Reportes\Ventas;

use App\Exports\ExcelReporteVentasVendedor;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DateTime;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class ReporteVendedorController extends Controller
{
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            // dd($idUsuario);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        $arrayVendedores = [];
        $arrayVentas = [];
        $inputvendedor = 0;
        $tipoPago = 0;
        $fecha = 5;
        $fechaIni = '';
        $fechaFin = '';
        $ini = '';
        $fin = '';

        $loadDatos = new DatosController();
        //$fechasReportes = new AjusteFechasReportesController();
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $fechas = $loadDatos->getFechaFiltro(5, null, null);
        $reporteVendedores = DB::select('call sp_getVentasVendedores(?, ?, ?, ?, ?)', array($idSucursal, 0, 0, $fechas[0], $fechas[1]));
        // $vendedoresGrafico = $loadDatos->getVentasVendedoresFiltrados($idSucursal, $inputvendedor, $tipoPago, $fecha, $fechas[0], $fechas[1]);
        // dd( $reporteVendedores);

        $vendedores = $loadDatos->getVendedores($idSucursal);
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        // dd($usuarioSelect);

        // $_vendedores = DB::table('ventas as v')
        //     ->join('usuario','v.IdCreacion', '=', 'usuario.IdUsuario')
        //     ->where('v.IdSucursal',$idSucursal)
        //     ->select(DB::raw('count(*) as total, usuario.Nombre, usuario.IdUsuario'))
        //     ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
        //     ->groupBy(DB::raw("usuario.Nombre"))
        //     ->get();
        // dd($vendedores);
        $_vendedores = $loadDatos->grafgetVentasVendedoresFiltrados($idSucursal, $inputvendedor, $tipoPago, $fecha, $fechas[0], $fechas[1]);

        if (count($_vendedores) >= 1) {
            $i = 0;
            foreach ($_vendedores as $_vendedor) {
                $arrayVendedores[$i] = "'$_vendedor->Nombre'";
                $arrayVentas[$i] = $_vendedor->total;
                $i++;
            }
        }

        $reporteVendedorGraficoSoles = DB::select('call sp_getVentasVendedoresGrafico(?, ?, ?, ?, ?, ?)', array($idSucursal, 0, 0, $fechas[0], $fechas[1], 1));

        $reporteVendedorGraficoDolares = DB::select('call sp_getVentasVendedoresGrafico(?, ?, ?, ?, ?, ?)', array($idSucursal, 0, 0, $fechas[0], $fechas[1], 2));

        //  $reportecardVendedor = DB::select('call sp_VentasReporteVendedor(?, ?, ?)',array($idSucursal, $fechas[0], $fechas[1]));
        //  dd( $reporteVendedorGrafico );

        $ventasContadoEfectivo = '';
        $ventasContadoEfectivoDolares = '';
        $ventasContadoCuentasCorrientes = '';
        $ventasContadoCuentasCorrientesDolares = '';
        $ventasContadoVisa = '';
        $ventasContadoMastercard = '';
        $ventasCredito = '';
        $ventasCreditoDolares = '';
        $descuentoContado = '';
        $descuentoContadoDolares = '';
        $descuentoCredito = '';
        $descuentoCreditoDolares = '';
        //$usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $amortizacionesSoles = '';
        $amortizacionesDolares = '';

        $resultadoDataSoles = [$ventasContadoEfectivo, $ventasContadoVisa, $ventasContadoMastercard, $ventasContadoCuentasCorrientes, $ventasCredito, $descuentoContado, $descuentoCredito, $amortizacionesSoles];

        $resultadoDataDolares = [$ventasContadoEfectivoDolares, $ventasContadoCuentasCorrientesDolares, $ventasCreditoDolares, $descuentoContadoDolares, $descuentoCreditoDolares, $amortizacionesDolares];

        // Nuevo Codigo
        // $resultado = DB::table('ventas')
        // ->where('IdSucursal', 112)
        // ->get();

        // for ($i = 0; $i < count($resultado); $i++)  {
        //     if ($resultado[$i]->IdCotizacion != null) {
        //         $cotizacion = DB::table('cotizacion')
        //         ->select(DB::raw("CONCAT(Serie, '-', Numero) As codigo"))
        //                 ->where('IdCotizacion', $resultado[$i]->IdCotizacion)
        //                 ->first();
        //         $resultado[$i]->codigoCotizacion = $cotizacion->codigo;
        //     }else{
        //         $resultado[$i]->codigoCotizacion = "-";
        //     }
        // }
        // dd($resultado);
        // Fin

        $array = ['idUsuario' => $idUsuario, 'resultadoDataDolares' => $resultadoDataDolares, 'resultadoDataSoles' => $resultadoDataSoles
            , 'reporteVendedorGraficoDolares' => $reporteVendedorGraficoDolares, 'reporteVendedorGraficoSoles' => $reporteVendedorGraficoSoles, 'arrayVendedores' => $_vendedores, 'grafCliente' => $arrayVendedores, 'grafTotal' => $arrayVentas, 'reporteVendedores' => $reporteVendedores, 'vendedores' => $vendedores, 'inputvendedor' => $inputvendedor, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'modulosSelect' => $modulosSelect, 'amortizacionesSoles' => $amortizacionesSoles, 'amortizacionesDolares' => $amortizacionesDolares,
            'ventasContadoEfectivo' => $ventasContadoEfectivo, 'ventasContadoEfectivoDolares' => $ventasContadoEfectivoDolares, 'ventasContadoVisa' => $ventasContadoVisa, 'ventasContadoMastercard' => $ventasContadoMastercard, 'ventasContadoCuentasCorrientes' => $ventasContadoCuentasCorrientes, 'ventasContadoCuentasCorrientesDolares' => $ventasContadoCuentasCorrientesDolares,
            'ventasCredito' => $ventasCredito, 'ventasCreditoDolares' => $ventasCreditoDolares, 'descuentoContado' => $descuentoContado, 'descuentoContadoDolares' => $descuentoContadoDolares, 'descuentoCredito' => $descuentoCredito, 'descuentoCreditoDolares' => $descuentoCreditoDolares, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('reportes/ventas/reporteVendedores', $array);
    }

    public function store(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        $arrayVendedores = [];
        $arrayVentas = [];

        $loadDatos = new DatosController();
        //$fechasReportes = new AjusteFechasReportesController();
        $inputvendedor = $req->vendedor;
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

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $_reporteVendedores = DB::select('call sp_getVentasVendedores(?, ?, ?, ?, ?)', array($idSucursal, $inputvendedor, 0, $fechas[0], $fechas[1]));
        $vendedoresGraf = $loadDatos->grafgetVentasVendedoresFiltrados($idSucursal, $inputvendedor, $tipoPago, $fecha, $fechas[0], $fechas[1]);

        $reporteVendedores = collect($_reporteVendedores);

        // dd( $reporteVendedores );
        $ventasContadoEfectivo = $reporteVendedores->where('IdTipoPago', 1)->where('IdTipoMoneda', 1)->sum('MontoEfectivo');
        $ventasContadoEfectivoDolares = $reporteVendedores->where('IdTipoPago', 1)->where('IdTipoMoneda', 2)->sum('MontoEfectivo');
        $ventasContadoVisa = $reporteVendedores->where('IdTipoPago', 1)->where('IdTipoTarjeta', 1)->where('IdTipoMoneda', 1)->sum('MontoTarjeta');
        $ventasContadoMastercard = $reporteVendedores->where('IdTipoPago', 1)->where('IdTipoTarjeta', 2)->where('IdTipoMoneda', 1)->sum('MontoTarjeta');
        $ventasContadoCuentasCorrientes = $reporteVendedores->where('IdTipoPago', 1)->where('IdTipoMoneda', 1)->sum('MontoCuentaBancaria');
        $ventasContadoCuentasCorrientesDolares = $reporteVendedores->where('IdTipoPago', 1)->where('IdTipoMoneda', 2)->sum('MontoCuentaBancaria');
        $ventasCredito = $reporteVendedores->where('IdTipoPago', 2)->where('IdTipoMoneda', 1)->sum('Total');
        $ventasCreditoDolares = $reporteVendedores->where('IdTipoPago', 2)->where('IdTipoMoneda', 2)->sum('Total');
        $descuentoContado = $reporteVendedores->where('IdTipoPago', 1)->where('IdTipoMoneda', 1)->sum('Exonerada');
        $descuentoContadoDolares = $reporteVendedores->where('IdTipoPago', 1)->where('IdTipoMoneda', 2)->sum('Exonerada');
        $descuentoCredito = $reporteVendedores->where('IdTipoPago', 2)->where('IdTipoMoneda', 1)->sum('Exonerada');
        $descuentoCreditoDolares = $reporteVendedores->where('IdTipoPago', 2)->where('IdTipoMoneda', 2)->sum('Exonerada');
        $vendedores = $loadDatos->getVendedores($idSucursal);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $_amortizaciones = $loadDatos->getAmortizaciones($idSucursal, $inputvendedor, $fechas[0], $fechas[1]);
        $amortizaciones = collect($_amortizaciones);
        $amortizacionesSoles = $amortizaciones->where('FormaPago', 1)->where('IdTipoMoneda', 1)->sum('Monto');
        $amortizacionesDolares = $amortizaciones->where('FormaPago', 1)->where('IdTipoMoneda', 2)->sum('Monto');
        //$amortizacionDolares = $loadDatos->getAmortizaciones($idSucursal, $inputvendedor, $fechas[0], $fechas[1]));

        $ini = str_replace('/', '-', $fechaIni);
        $fin = str_replace('/', '-', $fechaFin);

        if (count($vendedoresGraf) >= 1) {
            $i = 0;
            foreach ($vendedoresGraf as $vendedor) {
                $arrayVendedores[$i] = "'$vendedor->Nombre'";
                $arrayVentas[$i] = $vendedor->total;
                $i++;
            }
        }
        // $reporteVendedorGrafico = $loadDatos-> grafgetVentasVendedoresFiltrados($idSucursal, $inputvendedor, $tipoPago, $fecha, $fechas[0], $fechas[1]);
        $reporteVendedorGraficoSoles = DB::select('call sp_getVentasVendedoresGrafico(?, ?, ?, ?, ?, ?)', array($idSucursal, $inputvendedor, $tipoPago, $fechas[0], $fechas[1], 1));

        $reporteVendedorGraficoDolares = DB::select('call sp_getVentasVendedoresGrafico(?, ?, ?, ?, ?, ?)', array($idSucursal, $inputvendedor, $tipoPago, $fechas[0], $fechas[1], 2));
        // $reportecardVendedor = DB::select('call sp_VentasReporteVendedor(?, ?, ?)',array($idSucursal, $fechas[0], $fechas[1]));
        // dd(  $reporteVendedorGraficoDolares );

        $resultadoDataSoles = [$ventasContadoEfectivo, $ventasContadoVisa, $ventasContadoMastercard, $ventasContadoCuentasCorrientes, $ventasCredito, $descuentoContado, $descuentoCredito, $amortizacionesSoles];

        $resultadoDataDolares = [$ventasContadoEfectivoDolares, $ventasContadoCuentasCorrientesDolares, $ventasCreditoDolares, $descuentoContadoDolares, $descuentoCreditoDolares, $amortizacionesDolares];
        // dd( $resultadoData);

        $array = ['resultadoDataDolares' => $resultadoDataDolares, 'resultadoDataSoles' => $resultadoDataSoles, 'reporteVendedorGraficoDolares' => $reporteVendedorGraficoDolares, 'reporteVendedorGraficoSoles' => $reporteVendedorGraficoSoles, 'grafCliente' => $arrayVendedores, 'grafTotal' => $arrayVentas, 'reporteVendedores' => $reporteVendedores, 'vendedores' => $vendedores, 'inputvendedor' => $inputvendedor, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'modulosSelect' => $modulosSelect, 'ini' => $ini, 'fin' => $fin, 'ventasContadoEfectivo' => $ventasContadoEfectivo, 'ventasContadoEfectivoDolares' => $ventasContadoEfectivoDolares, 'amortizacionesSoles' => $amortizacionesSoles, 'amortizacionesDolares' => $amortizacionesDolares,
            'ventasContadoVisa' => $ventasContadoVisa, 'ventasContadoMastercard' => $ventasContadoMastercard, 'ventasContadoCuentasCorrientes' => $ventasContadoCuentasCorrientes, 'ventasContadoCuentasCorrientesDolares' => $ventasContadoCuentasCorrientesDolares, 'ventasCredito' => $ventasCredito, 'ventasCreditoDolares' => $ventasCreditoDolares, 'descuentoContado' => $descuentoContado, 'descuentoContadoDolares' => $descuentoContadoDolares, 'descuentoCredito' => $descuentoCredito, 'descuentoCreditoDolares' => $descuentoCreditoDolares, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('reportes/ventas/reporteVendedores', $array);
    }

    public function exportExcel($inputvendedor = null, $tipoPago = null, $fecha = null, $ini = null, $fin = null)
    {
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');

        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);

        if ($inputvendedor == 0) {
            $inputvendedor = null;
        }

        $reporteVendedores = $loadDatos->getVentasVendedoresFiltrados($idSucursal, $inputvendedor, $tipoPago, $fecha, $fechaIni, $fechaFin);
        //$reporteVendedores = DB::select('call sp_getVentasVendedores(?, ?, ?, ?, ?)',array($idSucursal, $inputvendedor, $fecha, $fechas[0], $fechas[1]));

        // dd($reporteVendedores);

        return Excel::download(new ExcelReporteVentasVendedor($reporteVendedores), 'Reporte Ventas - Vendedores.xlsx');
        /*$array = ['reporteVendedores' => $reporteVendedores];

    Excel::create('Reporte Vendedores', function ($excel) use($array){
    $excel->sheet('Reporte Vendedores', function ($sheet) use($array) {
    $sheet->getStyle('A:I', $sheet->getHighestRow())->getAlignment()->setWrapText(false);
    $sheet->loadView('excel/reporteVendedoresExcel', $array);
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
