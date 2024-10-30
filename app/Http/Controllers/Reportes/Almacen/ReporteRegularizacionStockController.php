<?php

namespace App\Http\Controllers\Reportes\Almacen;

use App\Exports\ExcelReporteRegularizacionStock;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class ReporteRegularizacionStockController extends Controller
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
        $fechaIni = '';
        $fechaFin = '';
        $ini = '0';
        $fin = '0';
        $idArticulo = 0;
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $listaArticulos = $this->getProductosEmparejados($idSucursal, $idArticulo, $fechas[0], $fechas[1]);
        $nombresArticulos = $this->getListaArticulo($idSucursal);
        $nombresArticulos = $nombresArticulos->unique('IdArticulo');

        $motivoLatenciaInternet = $listaArticulos->where('Motivo', 'Latencia de Internet')->count();
        $motivoInventario = $listaArticulos->where('Motivo', 'Inventario')->count();

        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'listaArticulos' => $listaArticulos, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'idArticulo' => $idArticulo, 'nombresArticulos' => $nombresArticulos, 'motivoInventario' => $motivoInventario, 'motivoLatenciaInternet' => $motivoLatenciaInternet];

        return view('reportes/almacen/reporteRegularizacionStock', $array);
    }

    public function filtrarArticulo(Request $req)
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
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        $idArticulo = $req->producto;
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $listaArticulos = $this->getProductosEmparejados($idSucursal, $idArticulo, $fechas[0], $fechas[1]);
        $ini = str_replace('/', '-', $fechaIni);
        $fin = str_replace('/', '-', $fechaFin);
        $nombresArticulos = $this->getListaArticulo($idSucursal);
        $nombresArticulos = $nombresArticulos->unique('IdArticulo');

        $motivoLatenciaInternet = $listaArticulos->where('Motivo', 'Latencia de Internet')->count();
        $motivoInventario = $listaArticulos->where('Motivo', 'Inventario')->count();

        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'listaArticulos' => $listaArticulos, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'idArticulo' => $idArticulo, 'nombresArticulos' => $nombresArticulos, 'motivoInventario' => $motivoInventario, 'motivoLatenciaInternet' => $motivoLatenciaInternet];
        return view('reportes/almacen/reporteRegularizacionStock', $array);

    }

    public function exportarExcel($idArticulo, $fecha = null, $ini = null, $fin = null)
    {
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');

        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $reporteArticulos = $this->getProductosEmparejados($idSucursal, $idArticulo, $fechas[0], $fechas[1])->sortByDesc('FechaCreacion');
        return Excel::download(new ExcelReporteRegularizacionStock($reporteArticulos), 'ReporteRegularizacionStock.xlsx');
    }

    private function getProductosEmparejados($idSucursal, $idArticulo, $fechaInicial, $fechaFinal)
    {
        if ($idArticulo == 0) {
            $resultado = DB::table('detalle_regularizar_stock AS d')
                ->join('articulo', 'd.IdArticulo', '=', 'articulo.IdArticulo')
                ->join('usuario', 'd.IdUsuarioRegularizacion', '=', 'usuario.IdUsuario')
                ->select('d.*', 'articulo.Descripcion', 'usuario.Nombre as NombreUsuario')
                ->where('d.IdSucursal', $idSucursal)
                ->whereBetween('d.FechaCreacion', [$fechaInicial, $fechaFinal])
                ->get();
            return $resultado;
        } else {
            $resultado = DB::table('detalle_regularizar_stock AS d')
                ->join('articulo', 'd.IdArticulo', '=', 'articulo.IdArticulo')
                ->join('usuario', 'd.IdUsuarioRegularizacion', '=', 'usuario.IdUsuario')
                ->select('d.*', 'articulo.Descripcion', 'usuario.Nombre as NombreUsuario')
                ->where('d.IdSucursal', $idSucursal)
                ->where('d.IdArticulo', $idArticulo)
                ->whereBetween('d.FechaCreacion', [$fechaInicial, $fechaFinal])
                ->get();
            return $resultado;
        }
    }

    private function getListaArticulo($idSucursal)
    {
        $resultado = DB::table('detalle_regularizar_stock AS d')
            ->join('articulo', 'd.IdArticulo', '=', 'articulo.IdArticulo')
            ->select('d.IdArticulo', 'articulo.Descripcion')
            ->where('d.IdSucursal', $idSucursal)
            ->get();
        return $resultado;
    }
}
