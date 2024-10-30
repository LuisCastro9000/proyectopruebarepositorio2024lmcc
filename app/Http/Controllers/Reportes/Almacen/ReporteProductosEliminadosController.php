<?php

namespace App\Http\Controllers\Reportes\Almacen;

use App\Exports\ExcelReporteProductosEliminados;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class ReporteProductosEliminadosController extends Controller
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
        $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $fecha = 5;
        $idTipo = 0;
        $fechaIni = '';
        $fechaFin = '';
        $ini = '0';
        $fin = '0';
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $productosEliminados = $this->getProductosEliminados($idSucursal, $idTipo, $fechas[0], $fechas[1]);
        $cantProductosEliminados = $productosEliminados->count();
        $nameProductos = $productosEliminados->pluck('Descripcion');
        $cantEliminadosXProducto = $productosEliminados->pluck('Stock');

        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'productosEliminados' => $productosEliminados, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'idTipo' => $idTipo, 'cantProductosEliminados' => $cantProductosEliminados, 'nameProductos' => $nameProductos, 'cantEliminadosXProducto' => $cantEliminadosXProducto];

        return view('reportes/almacen/reporteProductosEliminados', $array);
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
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $fecha = $req->fecha;
        $idTipo = $req->idTipo;
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $productosEliminados = $this->getProductosEliminados($idSucursal, $idTipo, $fechas[0], $fechas[1]);
        $cantProductosEliminados = $productosEliminados->count();
        $nameProductos = $productosEliminados->pluck('Descripcion');
        $cantEliminadosXProducto = $productosEliminados->pluck('Stock');

        $ini = str_replace('/', '-', $fechaIni);
        $fin = str_replace('/', '-', $fechaFin);

        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'productosEliminados' => $productosEliminados, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'idTipo' => $idTipo, 'cantProductosEliminados' => $cantProductosEliminados, 'nameProductos' => $nameProductos, 'cantEliminadosXProducto' => $cantEliminadosXProducto];

        return view('reportes/almacen/reporteProductosEliminados', $array);
    }

    public function exportExcel($fecha = null, $idTipo = null, $ini = null, $fin = null)
    {
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $productosEliminados = $this->getProductosEliminados($idSucursal, $idTipo, $fechas[0], $fechas[1]);
        return Excel::download(new ExcelReporteProductosEliminados($productosEliminados), 'ReporteProductosEliminados.xlsx');
    }

    public function getProductosEliminados($idSucursal, $idTipo, $fechaInicial, $fechaFinal)
    {

        if ($idTipo == 0) {
            $datos = DB::table('articulo')
                ->select('articulo.*', 'usuario.Nombre')
                ->join('usuario', 'articulo.IdEliminacion', '=', 'usuario.IdUsuario')
                ->where('articulo.IdSucursal', $idSucursal)
                ->where('articulo.Estado', 'D')
                ->whereBetween('articulo.FechaEliminacion', [$fechaInicial, $fechaFinal])
                ->get();
            return $datos;

        } else {
            $datos = DB::table('articulo')
                ->select('articulo.*', 'usuario.Nombre')
                ->join('usuario', 'articulo.IdEliminacion', '=', 'usuario.IdUsuario')
                ->where('articulo.IdSucursal', $idSucursal)
                ->where('articulo.Estado', 'D')
                ->where('articulo.IdTipo', $idTipo)
                ->whereBetween('articulo.FechaEliminacion', [$fechaInicial, $fechaFinal])
                ->get();
            return $datos;

        }

    }

}