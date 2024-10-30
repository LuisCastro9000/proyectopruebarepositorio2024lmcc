<?php

namespace App\Http\Controllers\Reportes\Compras;

use App\Exports\ExcelReporteComprasProveedores;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class ReporteProveedorController extends Controller
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
        //$fechasReportes = new AjusteFechasReportesController();
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $fechas = $loadDatos->getFechaFiltro(5, null, null);
        //$reporteProveedores = $loadDatos->getComprasProveedores($idSucursal);
        //$reporteProveedores = $loadDatos->getComprasProveedorFiltrados($idSucursal, null, 0, 5, null, null);
        // dd($fechas);
        $proveedores = $loadDatos->getProveedores($idSucursal);
        $inputproveedor = '';
        $_inputproveedor = 0;
        $tipoPago = 0;
        $fecha = 5;
        $fechaIni = '';
        $fechaFin = '';
        $ini = '';
        $fin = '';
        $comprasContado = '';
        $comprasCredito = '';
        $comprasContadoDolares = '';
        $comprasCreditoDolares = '';
        $arrayUnicoProveedor = [];
        $arrayFechasFiltros = [];
        $arrayUnicoProveedorDolares = [];
        $arrayFechasFiltrosDolares = [];
        $nombreProveedoresSoles = "";
        $totalComprasProveedoresSoles = "";
        $nombreProveedoresDolares = "";
        $totalComprasProveedoresDolares = "";

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $reporteProveedores = DB::select('call sp_getComprasProveedores(?, ?, ?, ?, ?)', array($idSucursal, $inputproveedor, $tipoPago, $fechas[0], $fechas[1]));
        // dd($proveedores);
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        // Reporte grafico proveedores
        $reporteProveedores = collect($reporteProveedores)->where('Estado', 'Registrado');
        $gaficoProveedorSoles = $reporteProveedores->where('IdTipoMoneda', 1)->values();
        $gaficoProveedorDolares = $reporteProveedores->where('IdTipoMoneda', 2)->values();

        $nombreProveedoresSoles = $gaficoProveedorSoles->unique('Nombres')->pluck('Nombres');
        $totalComprasProveedoresSoles = $gaficoProveedorSoles->countBy('IdProveedor')->values();

        $nombreProveedoresDolares = $gaficoProveedorDolares->unique('Nombres')->pluck('Nombres');
        $totalComprasProveedoresDolares = $gaficoProveedorDolares->countBy('IdProveedor')->values();

        $comprasSumaTotal = $gaficoProveedorSoles->sum('Total');
        $comprasContado = $gaficoProveedorSoles->where('IdTipoPago', 1)->sum('Total');
        $comprasCredito = $gaficoProveedorSoles->where('IdTipoPago', 2)->sum('Total');
        $comprasSumaTotalDolares = $gaficoProveedorDolares->sum('Total');
        $comprasContadoDolares = $gaficoProveedorDolares->where('IdTipoPago', 1)->sum('Total');
        $comprasCreditoDolares = $gaficoProveedorDolares->where('IdTipoPago', 2)->sum('Total');
        $totalComprasRealizadasSoles = $gaficoProveedorSoles->countBy('IdProveedor')->sum();
        $totalComprasRealizadasDolares = $gaficoProveedorDolares->countBy('IdProveedor')->sum();
        // Fin
        $array = ['gaficoProveedorSoles' => $gaficoProveedorSoles, 'gaficoProveedorDolares' => $gaficoProveedorDolares, 'ini' => $ini, 'fin' => $fin, 'reporteProveedores' => $reporteProveedores, 'proveedores' => $proveedores, 'inputproveedor' => $inputproveedor, '_inputproveedor' => $_inputproveedor, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'comprasContadoDolares' => $comprasContadoDolares, 'comprasCreditoDolares' => $comprasCreditoDolares, 'comprasSumaTotalDolares' => $comprasSumaTotalDolares,
            'nombreProveedoresSoles' => $nombreProveedoresSoles, 'totalComprasProveedoresSoles' => $totalComprasProveedoresSoles, 'nombreProveedoresDolares' => $nombreProveedoresDolares, 'totalComprasProveedoresDolares' => $totalComprasProveedoresDolares,
            'comprasContado' => $comprasContado, 'comprasCredito' => $comprasCredito, 'comprasSumaTotal' => $comprasSumaTotal, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'arrayUnicoProveedor' => $arrayUnicoProveedor, 'arrayFechasFiltros' => $arrayFechasFiltros, 'arrayUnicoProveedorDolares' => $arrayUnicoProveedorDolares, 'arrayFechasFiltrosDolares' => $arrayFechasFiltrosDolares, 'totalComprasRealizadasDolares' => $totalComprasRealizadasDolares, 'totalComprasRealizadasSoles' => $totalComprasRealizadasSoles];
        return view('reportes/compras/reporteProveedores', $array);
    }

    public function store(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        // $fechaIni = $req->fechaIni;
        // $fechaIni =  str_replace("/","-",$fechaIni);
        // $fechaFin = $req->fechaFin;
        // $fechaFin =  str_replace("/","-",$fechaIni);
        // $fechaEntera = strtotime($fechaIni);
        // $anioFechaInicial = date("Y", $fechaEntera);
        // $mesFechaInicial = date("m", $fechaEntera);
        // $diaFechaInicial = date("d", $fechaEntera);

        // $fechaEnteraFin = strtotime($fechaFin);
        // $anioFechaFin = date("Y", $fechaEnteraFin);
        // $mesFechaFin = date("m", $fechaEnteraFin);
        // $diaFechaFin = date("d", $fechaEnteraFin);
        // dd(    $mes  );
        // $fecha1 =  str_replace("/","",$fechaIni);
        // $fecha1  = intval($fecha1);
        // $ini= str_replace('/','-', $fechaIni);
        // $fin= str_replace('/','-', $fechaFin);

        // .-------------------------------------------------------------
        $loadDatos = new DatosController();
        //$fechasReportes = new AjusteFechasReportesController();
        $inputproveedor = $req->proveedor;
        // if($inputproveedor == null){
        //     $_inputproveedor = 0;
        // }else{
        //     $_inputproveedor = $inputproveedor;
        // }
        $_inputproveedor = "";
        $diferencia = null;
        $tipoPago = $req->tipoPago;
        $fecha = $req->fecha;
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        $ini = str_replace('/', '-', $fechaIni);
        $fin = str_replace('/', '-', $fechaFin);
        if ($fecha == 9) {
            if ($fechaIni == null || $fechaFin == null) {
                return back()->with('error', 'Completar las fechas para filtrar');
            }

            $nuevaFechaIni = str_replace("/", "-", $fechaIni);
            $nuevaFechaFin = str_replace("/", "-", $fechaFin);

            $fechaEntera = strtotime($nuevaFechaIni);
            $anioFechaInicial = date("Y", $fechaEntera);
            $mesFechaInicial = date("m", $fechaEntera);
            $diaFechaInicial = date("d", $fechaEntera);

            $fechaEnteraFin = strtotime($nuevaFechaFin);
            $anioFechaFin = date("Y", $fechaEnteraFin);
            $mesFechaFin = date("m", $fechaEnteraFin);
            $diaFechaFin = date("d", $fechaEnteraFin);

            if ($anioFechaInicial > $anioFechaFin) {
                return back()->with('error', 'El Año inicial no puede ser mayor que el Año Final');
            }
            if ($mesFechaInicial > $mesFechaFin) {
                return back()->with('error', 'El Mes Inicial no puede ser mayor que el Mes Final');
            }
            if ($diaFechaInicial > $diaFechaFin && $mesFechaInicial >= $mesFechaFin) {
                return back()->with('error', 'El Día Inicial no puede ser mayor que el Día Final');
            }
        }
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $_reporteProveedores = DB::select('call sp_getComprasProveedores(?, ?, ?, ?, ?)', array($idSucursal, $inputproveedor, $tipoPago, $fechas[0], $fechas[1]));
        //$reporteProveedores = $loadDatos->getComprasProveedorFiltrados($idSucursal, $inputproveedor, $tipoPago, $fecha, $fechaIni, $fechaFin);
        $reporteProveedores = collect($_reporteProveedores)->where('Estado', 'Registrado');
        $gaficoProveedorSoles = $reporteProveedores->where('IdTipoMoneda', 1)->values();
        $gaficoProveedorDolares = $reporteProveedores->where('IdTipoMoneda', 2)->values();

        $proveedores = $loadDatos->getProveedores($idSucursal);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        // Reporte grafico todos los proveedores ->Rois
        // $totalComprasProveedores = $reporteProveedores->countBy('IdProveedor')->values();
        $nombresProveedores = $reporteProveedores->unique('Nombres')->pluck('Nombres')->first();
        $nombreProveedoresSoles = $gaficoProveedorSoles->unique('Nombres')->pluck('Nombres');
        $totalComprasProveedoresSoles = $gaficoProveedorSoles->countBy('IdProveedor')->values();

        $nombreProveedoresDolares = $gaficoProveedorDolares->unique('Nombres')->pluck('Nombres');
        $totalComprasProveedoresDolares = $gaficoProveedorDolares->countBy('IdProveedor')->values();

        $comprasSumaTotal = $gaficoProveedorSoles->sum('Total');
        $comprasContado = $gaficoProveedorSoles->where('IdTipoPago', 1)->sum('Total');
        $comprasCredito = $gaficoProveedorSoles->where('IdTipoPago', 2)->sum('Total');
        $comprasSumaTotalDolares = $gaficoProveedorDolares->sum('Total');
        $comprasContadoDolares = $gaficoProveedorDolares->where('IdTipoPago', 1)->sum('Total');
        $comprasCreditoDolares = $gaficoProveedorDolares->where('IdTipoPago', 2)->sum('Total');

        $totalComprasRealizadasSoles = $gaficoProveedorSoles->countBy('IdProveedor')->sum();
        $totalComprasRealizadasDolares = $gaficoProveedorDolares->countBy('IdProveedor')->sum();
        // dd($totalComprasProveedoresDolares);
        // Fin

        // Grafico unico proveedor soles getComprasProveedorFiltradosSS
        $gaficoProveedorFiltradoSoles = $loadDatos->getComprasUnicoProveedorFiltrados($idSucursal, $inputproveedor, $tipoPago, $fecha, $fechas[0], $fechas[1], $diferencia, 1);
        $gaficoProveedorFiltradoDolares = $loadDatos->getComprasUnicoProveedorFiltrados($idSucursal, $inputproveedor, $tipoPago, $fecha, $fechas[0], $fechas[1], $diferencia, 2);
        $arrayUnicoProveedor = [];
        $arrayFechasFiltros = [];
        $arrayMes = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];

        for ($i = 0; $i < count($gaficoProveedorFiltradoSoles); $i++) {
            if (($fecha >= 1 && $fecha <= 6) || ($fecha == 9 && $diferencia <= 31)) {
                array_push($arrayFechasFiltros, $gaficoProveedorFiltradoSoles[$i]->dia . ' ' . $arrayMes[$gaficoProveedorFiltradoSoles[$i]->mes - 1]);
            }
            if (($fecha == 7 || $fecha == 8 || $fecha == 0 || $fecha == 10) || ($fecha == 9 && $diferencia > 31)) {
                array_push($arrayFechasFiltros, $arrayMes[$gaficoProveedorFiltradoSoles[$i]->mes - 1] . ' ' . $gaficoProveedorFiltradoSoles[$i]->anio);
            }
            array_push($arrayUnicoProveedor, $gaficoProveedorFiltradoSoles[$i]->totalCompras);
        }

        // Grafico unico proveedor dolares
        $arrayUnicoProveedorDolares = [];
        $arrayFechasFiltrosDolares = [];

        for ($i = 0; $i < count($gaficoProveedorFiltradoDolares); $i++) {
            if (($fecha >= 1 && $fecha <= 6) || ($fecha == 9 && $diferencia <= 31)) {
                array_push($arrayFechasFiltrosDolares, $gaficoProveedorFiltradoDolares[$i]->dia . ' ' . $arrayMes[$gaficoProveedorFiltradoDolares[$i]->mes - 1]);
            }
            if (($fecha == 7 || $fecha == 8 || $fecha == 0 || $fecha == 10) || ($fecha == 9 && $diferencia > 31)) {
                array_push($arrayFechasFiltrosDolares, $arrayMes[$gaficoProveedorFiltradoDolares[$i]->mes - 1] . ' ' . $gaficoProveedorFiltradoDolares[$i]->anio);
            }
            array_push($arrayUnicoProveedorDolares, $gaficoProveedorFiltradoDolares[$i]->totalCompras);
        }
        //   dd($arrayFechasFiltrosDolares);
        // Fin
        $array = ['gaficoProveedorSoles' => $gaficoProveedorSoles, 'gaficoProveedorDolares' => $gaficoProveedorDolares, 'ini' => $ini, 'fin' => $fin, 'reporteProveedores' => $reporteProveedores, 'proveedores' => $proveedores, 'inputproveedor' => $inputproveedor, '_inputproveedor' => $_inputproveedor, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'comprasContadoDolares' => $comprasContadoDolares, 'comprasCreditoDolares' => $comprasCreditoDolares, 'nombreProveedores' => $nombresProveedores,
            'nombreProveedoresSoles' => $nombreProveedoresSoles, 'totalComprasProveedoresSoles' => $totalComprasProveedoresSoles, 'nombreProveedoresDolares' => $nombreProveedoresDolares, 'totalComprasProveedoresDolares' => $totalComprasProveedoresDolares,
            'comprasContado' => $comprasContado, 'comprasCredito' => $comprasCredito, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'nombresProveedores' => $nombresProveedores, 'arrayUnicoProveedor' => $arrayUnicoProveedor, 'arrayFechasFiltros' => $arrayFechasFiltros, 'arrayUnicoProveedorDolares' => $arrayUnicoProveedorDolares, 'arrayFechasFiltrosDolares' => $arrayFechasFiltrosDolares, 'totalComprasRealizadasDolares' => $totalComprasRealizadasDolares, 'totalComprasRealizadasSoles' => $totalComprasRealizadasSoles, 'comprasSumaTotal' => $comprasSumaTotal, 'comprasContadoDolares' => $comprasContadoDolares, 'comprasCreditoDolares' => $comprasCreditoDolares, 'comprasSumaTotalDolares' => $comprasSumaTotalDolares];
        return view('reportes/compras/reporteProveedores', $array);
    }

    public function exportExcel($inputproveedor = null, $tipoPago = null, $fecha = null, $ini = null, $fin = null)
    {

        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');
        $idSucursal = Session::get('idSucursal');

        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);

        //   if($inputproveedor == 0){
        //       $inputproveedor = null;
        //   }

        //$reporteVendedores = $loadDatos->getVentasVendedoresFiltrados($idSucursal, $inputvendedor, $tipoPago, $fecha, $fechaIni, $fechaFin);
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        //   dd($fechas);
        //   $reporteProveedores = DB::select('call sp_getComprasProveedores(?, ?, ?, ?, ?)',array($idSucursal, $inputproveedor, $tipoPago, $fecha,$fechas[0], $fechas[1]));
        $reporteProveedores = DB::select('call sp_getComprasProveedores(?, ?, ?, ?, ?)', array($idSucursal, $inputproveedor, $tipoPago, $fechas[0], $fechas[1]));
        //    dd($reporteProveedores);
        //$reporteProveedores = $loadDatos-> getComprasProveedorFiltrados($idSucursal, $inputproveedor, $tipoPago, $fecha, $fechaIni, $fechaFin);

        return Excel::download(new ExcelReporteComprasProveedores($reporteProveedores), 'Reporte Compras - Proveedores.xlsx');
        /*$array = ['reporteProveedores' => $reporteProveedores];

    Excel::create('Reporte de Compras - Proveedores', function ($excel) use($array){
    $excel->sheet('Reporte Compras - Proveedores', function ($sheet) use($array) {
    $sheet->getStyle('A:I', $sheet->getHighestRow())->getAlignment()->setWrapText(false);
    $sheet->loadView('excel/excelComprasProveedores', $array);
    });
    })->download('xlsx');*/
    }

}
