<?php

namespace App\Http\Controllers\Reportes\Ventas;

use App\Exports\ExcelReporteVentasClientes;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DateTime;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class ReporteClienteController extends Controller
{
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $inputcliente = "";
        $tipoPago = '';
        $fecha = 5;
        $fechaIni = '';
        $fechaFin = '';
        $ini = '';
        $fin = '';
        $ventasContado = '';
        $ventasCredito = '';
        $descuentoContado = '';
        $descuentoCredito = '';
        $arrayClientes = [];
        $arrayVentas = [];
        $arrayUnicoCliente = [];
        $arrayFechasFiltros = [];
        $arrayUnicoClienteDolares = [];
        $arrayFechasDolares = [];
        $diferencia = null;
        $totalVentasSoles = "";
        $nombreVentasSoles = "";
        $totalVentasDolares = "";
        $nombreVentasDolares = "";

        $loadDatos = new DatosController();
        //$fechasReportes = new AjusteFechasReportesController();
        $idSucursal = Session::get('idSucursal');
        $fechas = $loadDatos->getFechaFiltro(5, null, null);
        $reporteClientes = DB::select('call sp_getVentasClientes(?, ?, ?, ?, ?)', array($idSucursal, 0, $inputcliente, $fechas[0], $fechas[1]));
        $reporteClientes = collect($reporteClientes);
        //$reporteClientes = $loadDatos->getVentasClientes($idSucursal);
        $clientes = $loadDatos->getClientes($idSucursal);
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $clientesGraf = $loadDatos->grafgetVentasClientesFiltrados($idSucursal, $inputcliente, $tipoPago, 5, $fechas[0], $fechas[1]);

        // $clientes = DB::table('ventas as v')
        //     ->join('cliente','v.IdCliente', '=', 'cliente.IdCliente')
        //     ->where('v.IdSucursal',$idSucursal)
        //     ->select(DB::raw('count(*) as total, cliente.Nombre , cliente.IdCliente'))
        //     ->groupBy(DB::raw("cliente.Nombre"))
        //     ->get();
        //     // dd($clientes);

        if (count($clientesGraf) >= 1) {
            $i = 0;
            foreach ($clientesGraf as $cliente) {
                $arrayClientes[$i] = "'$cliente->Nombre'";
                $arrayVentas[$i] = $cliente->total;
                $i++;
            }
        }
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $array = ['totalVentasSoles' => $totalVentasSoles, 'nombreVentasSoles' => $nombreVentasSoles,
            'totalVentasDolares' => $totalVentasDolares, 'nombreVentasDolares' => $nombreVentasDolares, 'grafUnicoClienteDolares' => $arrayUnicoClienteDolares, 'arrayFechasDolares' => $arrayFechasDolares, 'grafUnicoCliente' => $arrayUnicoCliente, 'arrayFechasFiltros' => $arrayFechasFiltros, 'graficoCliente' => $clientesGraf, 'grafCliente' => $arrayClientes, 'grafTotal' => $arrayVentas, 'reporteClientes' => $reporteClientes, 'clientes' => $clientes, 'inputcliente' => $inputcliente, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin,
            'ventasContado' => $ventasContado, 'ventasCredito' => $ventasCredito, 'descuentoContado' => $descuentoContado, 'descuentoCredito' => $descuentoCredito, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('reportes/ventas/reporteClientes', $array);
    }

    public function store(Request $req)
    {
        $loadDatos = new DatosController();
        $inputcliente = $req->cliente;
        $tipoPago = $req->tipoPago;
        $fecha = $req->fecha;
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        $arrayUnicoCliente = [];
        $arrayFechasFiltros = [];
        $diferencia = null;
        // if($fecha == 9){
        //     if($fechaIni == null || $fechaFin == null){
        //         return back()->with('error','Completar las fechas para filtrar');
        //     }
        //     if($fechaIni > $fechaFin){
        //         return back()->with('error','La fecha Inicial no puede ser mayor que la Final');
        //     }
        // }

        // SE HA MODIFICADO LA EVALUACION DE LA FECHA
        if ($fecha == 9) {
            if ($fechaIni == null || $fechaFin == null) {
                return back()->with('error', 'Completar las fechas para filtrar');
            }
            $dateIni = DateTime::createFromFormat('d/m/Y', $fechaIni);
            $dateFin = DateTime::createFromFormat('d/m/Y', $fechaFin);
            if (strtotime($dateIni->format('Y-m-d')) > strtotime($dateFin->format('Y-m-d'))) {
                return back()->with('error', 'La fecha Inicial no puede ser mayor que la Final');
            }
            $fechaIniConvert = Carbon::createFromFormat('d/m/Y', $fechaIni);
            $fechaFinConvert = Carbon::createFromFormat('d/m/Y', $fechaFin);
            $diferencia = $fechaIniConvert->diffInDays($fechaFinConvert);
        }
        //  FIN
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        //$reporteClientes = $loadDatos->getVentasClientesFiltrados($idSucursal, $inputcliente, $tipoPago, $fecha, $fechaIni, $fechaFin);
        // REPORTE DE SOLES
        $_reporteClientes = $reporteClientes = DB::select('call sp_getVentasClientes(?, ?, ?, ?, ?)', array($idSucursal, $inputcliente, $tipoPago, $fechas[0], $fechas[1]));
        $reporteClientes = collect($_reporteClientes);
        $reporteClientesSoles = $reporteClientes->whereIn('IdTipoMoneda', 1);

        $ventasContado = $reporteClientesSoles->where('IdTipoPago', 1)->sum('Total');
        $ventasCredito = $reporteClientesSoles->where('IdTipoPago', 2)->sum('Total');
        $descuentoContado = $reporteClientesSoles->where('IdTipoPago', 1)->sum('Exonerada');
        $descuentoCredito = $reporteClientesSoles->where('IdTipoPago', 2)->sum('Exonerada');
        // FIN

// REPORTE DE DOLARES
        $reporteClientesDolares = $reporteClientes->whereIn('IdTipoMoneda', 2);

        $ventasContadoDolares = $reporteClientesDolares->where('IdTipoPago', 1)->sum('Total');
        $ventasCreditoDolares = $reporteClientesDolares->where('IdTipoPago', 2)->sum('Total');
        $descuentoContadoDolares = $reporteClientesDolares->where('IdTipoPago', 1)->sum('Exonerada');
        $descuentoCreditoDolares = $reporteClientesDolares->where('IdTipoPago', 2)->sum('Exonerada');
// FIN

        $clientesGraf = $loadDatos->grafgetVentasClientesFiltrados($idSucursal, $inputcliente, $tipoPago, $fecha, $fechas[0], $fechas[1]);
        // dd($clientesGraf);
        $clientes = $loadDatos->getClientes($idSucursal);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $ini = str_replace('/', '-', $fechaIni);
        $fin = str_replace('/', '-', $fechaFin);

        //dd($clientesGraf);
        $arrayClientes = [];
        $arrayVentas = [];

        if (count($clientesGraf) >= 1) {
            $i = 0;
            foreach ($clientesGraf as $clieGraf) {
                $arrayClientes[$i] = "'$clieGraf->Nombre'";
                $arrayVentas[$i] = $clieGraf->total;
                $i++;
            }
        }

// PARA MOSTRAR GRAFICO EN FECHAS EN SOLES

        $graficoClientesFechas = $loadDatos->grafgetVentasClientesFiltradosFechas($idSucursal, $inputcliente, $tipoPago, $fecha, $fechas[0], $fechas[1], $diferencia, 1);
        $graficoClientesFechas = collect($graficoClientesFechas);
        $nombreVentasSoles = $graficoClientesFechas->pluck('Nombre')->first();
        $totalVentasSoles = $graficoClientesFechas->pluck('total')->sum();

        // $graficoClientesFechas = collect($graficoClientesFechas);
        // $graficoClientesFechas =  $graficoClientesFechas->where('IdTipoMoneda', 1);
        // dd($graficoClientesFechas);
        $arrayMes = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];

        for ($i = 0; $i < count($graficoClientesFechas); $i++) {
            if (($fecha >= 1 && $fecha <= 6) || ($fecha == 9 && $diferencia <= 31)) {
                array_push($arrayFechasFiltros, $graficoClientesFechas[$i]->dia . ' ' . $arrayMes[$graficoClientesFechas[$i]->mes - 1]);
            }
            if (($fecha == 7 || $fecha == 8 || $fecha == 0) || ($fecha == 9 && $diferencia > 31)) {
                array_push($arrayFechasFiltros, $arrayMes[$graficoClientesFechas[$i]->mes - 1] . ' ' . $graficoClientesFechas[$i]->anio);
            }
            array_push($arrayUnicoCliente, $graficoClientesFechas[$i]->total);
        }

// PARA MOSTRAR GRAFICO EN FECHAS EN DOLARES
        $arrayFechasDolares = [];
        $arrayUnicoClienteDolares = [];
        $graficoClientesFechasDolares = $loadDatos->grafgetVentasClientesFiltradosFechas($idSucursal, $inputcliente, $tipoPago, $fecha, $fechas[0], $fechas[1], $diferencia, 2);
        $nombreVentasDolares = $graficoClientesFechasDolares->pluck('Nombre')->first();
        $totalVentasDolares = $graficoClientesFechasDolares->pluck('total')->sum();
        // dd($totalVentasDolares);
        // $graficoClientesFechas = collect($graficoClientesFechas);
        // $graficoClientesFechasDolares =  $graficoClientesFechas->whereIn('IdTipoMoneda', 2);
        for ($i = 0; $i < count($graficoClientesFechasDolares); $i++) {
            if (($fecha >= 1 && $fecha <= 6) || ($fecha == 9 && $diferencia <= 31)) {
                array_push($arrayFechasFiltros, $graficoClientesFechasDolares[$i]->dia . ' ' . $arrayMes[$graficoClientesFechasDolares[$i]->mes - 1]);
            }
            if (($fecha == 7 || $fecha == 8 || $fecha == 0) || ($fecha == 9 && $diferencia > 31)) {
                array_push($arrayFechasDolares, $arrayMes[$graficoClientesFechasDolares[$i]->mes - 1] . ' ' . $graficoClientesFechasDolares[$i]->anio);
            }
            array_push($arrayUnicoClienteDolares, $graficoClientesFechasDolares[$i]->total);
        }

        // dd($arrayFechasDolares);
        // dd($arrayFechasFiltros);
        $array = ['totalVentasDolares' => $totalVentasDolares, 'nombreVentasDolares' => $nombreVentasDolares, 'totalVentasSoles' => $totalVentasSoles, 'nombreVentasSoles' => $nombreVentasSoles, 'grafUnicoClienteDolares' => $arrayUnicoClienteDolares, 'arrayFechasDolares' => $arrayFechasDolares, 'grafUnicoCliente' => $arrayUnicoCliente, 'arrayFechasFiltros' => $arrayFechasFiltros, 'graficoCliente' => $clientesGraf, 'grafCliente' => $arrayClientes, 'grafTotal' => $arrayVentas, 'reporteClientes' => $reporteClientes, 'clientes' => $clientes, 'inputcliente' => $inputcliente, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin,
            'ventasContado' => $ventasContado, 'ventasCredito' => $ventasCredito, 'descuentoContado' => $descuentoContado, 'descuentoCredito' => $descuentoCredito, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'ventasContadoDolares' => $ventasContadoDolares, 'ventasCreditoDolares' => $ventasCreditoDolares, 'descuentoContadoDolares' => $descuentoContadoDolares, 'descuentoCreditoDolares' => $descuentoCreditoDolares,
        ];
        return view('reportes/ventas/reporteClientes', $array);
    }

    public function exportExcel($inputcliente = 0, $tipoPago = null, $fecha = null, $ini = null, $fin = null)
    {

        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');
        $idSucursal = Session::get('idSucursal');

        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);

        //   if($inputcliente == null){
        //       $inputcliente = 0;
        //   }

        //$reporteVendedores = $loadDatos->getVentasVendedoresFiltrados($idSucursal, $inputvendedor, $tipoPago, $fecha, $fechaIni, $fechaFin);

        //   CODIGO CORREGIDO
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        //   $reporteClientes = $loadDatos->getVentasClientesFiltrados($idSucursal,$inputcliente, $tipoPago, $fecha, $fechas[0], $fechas[1]);
        $reporteClientes = $reporteClientes = DB::select('call sp_getVentasClientes(?, ?, ?, ?, ?)', array($idSucursal, $inputcliente, $tipoPago, $fechas[0], $fechas[1]));
        //   FIN
        return Excel::download(new ExcelReporteVentasClientes($reporteClientes), 'Reporte Ventas - Clientes.xlsx');
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
