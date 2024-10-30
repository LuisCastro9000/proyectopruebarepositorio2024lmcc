<?php

namespace App\Http\Controllers\Reportes\Gerenciales;

use App\Exports\ExcelReporteMasVendido;
use App\Exports\ExcelReporteProductosNoVendidos;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class ReportesMasVendidosController extends Controller
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
        // $masVendidos = $loadDatos->getMasVendidos($idSucursal);
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $tipoPago = 0;
        $fecha = 5;
        $cantRegistros = 1000;
        $ini = '';
        $fin = '';
        $fechaIni = '';
        $fechaFin = '';
        $arrayTotalVendidos = [];
        $arrayDescripcion = [];
        $arrayTotalProductos = [];
        $arrayMenosVendidos = [];
        $arrayDescripcionMenos = [];
        $arrayFechas = [];
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        //Se agrego el PA
        $fechas = $loadDatos->getFechaFiltro(5, null, null);
        $reporteVendidos = collect(DB::select('call sp_getMasVendidos(?, ?, ?, ?, ? )', array($idSucursal, $tipoPago, $fechas[0], $fechas[1], $cantRegistros)));
        $menosVendidos = $reporteVendidos->sortBy('Total');
        $masVendidos = $reporteVendidos->sortByDesc('Total');
        $reporteMenosVendidos = $menosVendidos->take(15);
        $reporteMasVendidos = $masVendidos->take(15);
        //   Fin
//  dd($reporteMenosVendidos);
        if (count($reporteMasVendidos) >= 1) {
            $i = 0;
            foreach ($reporteMasVendidos as $articulo) {
                $arrayfecha[$i] = $articulo->FechaCreacion;
                $arrayDescripcion[$i] = $articulo->Descripcion;
                $arrayTotalVendidos[$i] = $articulo->Total;
                $i++;
            }
        }
        if (count($reporteMenosVendidos) >= 1) {
            $i = 0;
            foreach ($reporteMenosVendidos as $articulo) {
                $arrayMenosVendidos[$i] = $articulo->Total;
                $arrayDescripcionMenos[$i] = $articulo->Descripcion;
                $i++;
            }
        }
        // dd( $arrayMenosVendidos);
        // $masVendidos  = $loadDatos->getMasVendidosFiltrados($idSucursal, $tipoPago, $fecha, $cantRegistros, $fechaIni, $fechaFin);

        $array = ['arrayFechas' => $arrayFechas, 'arrayTotalProductos' => $arrayTotalProductos, 'arrayDescripcion' => $arrayDescripcion, 'arrayTotalVendidos' => $arrayTotalVendidos, 'arrayDescripcionMenos' => $arrayDescripcionMenos, 'arrayMenosVendidos' => $arrayMenosVendidos, 'cantRegistros' => $cantRegistros, 'masVendidos' => $masVendidos, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('reportes/gerenciales/reporteMasVendidos', $array);
    }

    public function store(Request $req)
    {
        $loadDatos = new DatosController();
        $cantRegistros = $req->cantRegistros;
        $tipoPago = $req->tipoPago;
        $fecha = $req->fecha;
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        $arrayTotalVendidos = [];
        $arrayMenosVendidos = [];
        $arrayDescripcion = [];
        $arrayDescripcionMenos = [];
        $arrayFecha = [];
        $arrayTotalProductos = [];
        $arrayFechas = [];
        if ($fecha == 9) {
            if ($fechaIni == null || $fechaFin == null) {
                return back()->with('error', 'Completar las fechas para filtrar');
            }
            if ($fechaIni > $fechaFin) {
                return back()->with('error', 'La fecha Inicial no puede ser mayor que la Final');
            }
        }
        $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        //$masVendidos = $loadDatos->getMasVendidosFiltrados($idSucursal, $tipoPago, $fecha, $fechaIni, $fechaFin);
        //$masVendidos = $loadDatos->getMasVendidosFiltradosPrueba($idSucursal, $tipoPago, $cantidad, $fecha, $fechaIni, $fechaFin);
        //dd($masVendidos);
        //Se agrego el PA
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $reporteVendidos = DB::select('call sp_getMasVendidos(?, ?, ?, ?, ? )', array($idSucursal, $tipoPago, $fechas[0], $fechas[1], $cantRegistros));
        $collectionReporteVendidos = collect($reporteVendidos);
        $menosVendidos = $collectionReporteVendidos->sortBy('Total');
        $masVendidos = $collectionReporteVendidos->sortByDesc('Total');
        $reporteMenosVendidos = $menosVendidos->take(15);
        $reporteMasVendidos = $masVendidos->take(15);

        //FIN
        //   dd(  $reporteMasVendidos);

        if (count($reporteMasVendidos) >= 1) {
            $i = 0;
            foreach ($reporteMasVendidos as $articulo) {
                $arrayfecha[$i] = $articulo->FechaCreacion;
                $arrayDescripcion[$i] = $articulo->Descripcion;
                $arrayTotalVendidos[$i] = $articulo->Total;
                $i++;
            }
        }
        if (count($reporteMenosVendidos) >= 1) {
            $i = 0;
            foreach ($reporteMenosVendidos as $articulo) {
                $arrayMenosVendidos[$i] = $articulo->Total;
                $arrayDescripcionMenos[$i] = $articulo->Descripcion;
                $i++;
            }
        }
        $ini = str_replace('/', '-', $fechaIni);
        $fin = str_replace('/', '-', $fechaFin);

        // $masVendidos  = $loadDatos->getMasVendidosFiltrados($idSucursal, $tipoPago, $fecha, $cantRegistros, $fechaIni, $fechaFin);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['arrayFechas' => $arrayFechas, 'arrayFecha' => $arrayFecha, 'arrayDescripcion' => $arrayDescripcion, 'arrayTotalVendidos' => $arrayTotalVendidos, 'arrayDescripcionMenos' => $arrayDescripcionMenos, 'arrayMenosVendidos' => $arrayMenosVendidos, 'cantRegistros' => $cantRegistros, 'masVendidos' => $masVendidos, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('reportes/gerenciales/reporteMasVendidos', $array);
    }

    //  Se agrego la funcion exportExcel
    public function exportExcel($tipoPago = null, $fecha = null, $cantRegistros = null, $ini = null, $fin = null)
    {
        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');
        $idSucursal = Session::get('idSucursal');

        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $reporteVendidos = DB::select('call sp_getMasVendidos(?, ?, ?, ?, ? )', array($idSucursal, $tipoPago, $fechas[0], $fechas[1], $cantRegistros));
        $collectionReporteVendidos = collect($reporteVendidos);
        $reporteComprasVentas = $collectionReporteVendidos->sortByDesc('Total');
        //   $reporteComprasVentas  = $loadDatos->getMasVendidosFiltrados($idSucursal, $tipoPago, $fecha, $cantRegistros, $fechaIni, $fechaFin);
        //    dd( $reporteComprasVentas);
        return Excel::download(new ExcelReporteMasVendido($reporteComprasVentas), 'Reporte Gerencial - Mas Vendidos.xlsx');

    }

    public function exportExcelProductosNoVendidos($fecha = null, $ini = null, $fin = null)
    {
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $reporteProductosNoVendidos = DB::select('call sp_getProductosNoVendidos(?,?,? )', array($idSucursal, $fechas[0], $fechas[1]));
        // dd($reporteProductosNoVendidos);
        return Excel::download(new ExcelReporteProductosNoVendidos($reporteProductosNoVendidos), 'Reporte Gerencial - ProductosNoVendidos.xlsx');

    }
}
