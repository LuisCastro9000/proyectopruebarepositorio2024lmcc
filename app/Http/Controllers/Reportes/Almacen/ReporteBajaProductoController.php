<?php

namespace App\Http\Controllers\Reportes\Almacen;

use App\Exports\ExcelReporteBajaProductos;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DateTime;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class ReporteBajaProductoController extends Controller
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
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $fecha = 5;
        $fechaIni = '0';
        $fechaFin = '0';
        $ini = 0;
        $fin = 0;
        $fechas = $loadDatos->getFechaFiltro($fecha, null, null);
        $bajaProductos = DB::select('call sp_getBajaProductos(?, ?, ?)', array($idSucursal, $fechas[0], $fechas[1]));
        $bajaProductos = collect($bajaProductos);
        $bajaProductos = $bajaProductos->map(function ($item, $key) {
            if ($item->IdCategoria != null) {
                $listaCategoria = DB::table('categoria')
                    ->where('IdCategoria', $item->IdCategoria)
                    ->first();
                $item->nombreCategoria = $listaCategoria->Nombre;
            } else {
                $item->nombreCategoria = '-';
            }
            if ($item->IdMarca != null) {
                $listaMarca = DB::table('marca')
                    ->where('IdMarca', $item->IdMarca)
                    ->first();
                $item->nombreMarca = $listaMarca->Nombre;
            } else {
                $item->nombreMarca = '-';
            }
            return $item;
        });

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['permisos' => $permisos, 'bajaProductos' => $bajaProductos, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'ini' => $ini, 'fin' => $fin];
        return view('reportes/almacen/reporteBajaProducto', $array);
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
        $fechaIni = trim($req->fechaIni) == false ? '0' : $req->fechaIni;
        $fechaFin = trim($req->fechaFin) == false ? '0' : $req->fechaFin;
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
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $bajaProductos = DB::select('call sp_getBajaProductos(?, ?, ?)', array($idSucursal, $fechas[0], $fechas[1]));
        $bajaProductos = collect($bajaProductos);
        $bajaProductos = $bajaProductos->map(function ($item, $key) {
            if ($item->IdCategoria != null) {
                $listaCategoria = DB::table('categoria')
                    ->where('IdCategoria', $item->IdCategoria)
                    ->first();
                $item->nombreCategoria = $listaCategoria->Nombre;
            } else {
                $item->nombreCategoria = '-';
            }
            if ($item->IdMarca != null) {
                $listaMarca = DB::table('marca')
                    ->where('IdMarca', $item->IdMarca)
                    ->first();
                $item->nombreMarca = $listaMarca->Nombre;
            } else {
                $item->nombreMarca = '-';
            }
            return $item;
        });
        $ini = str_replace('/', '-', $fechaIni);
        $fin = str_replace('/', '-', $fechaFin);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['bajaProductos' => $bajaProductos, 'fecha' => $fecha, 'ini' => $ini, 'fin' => $fin, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('reportes/almacen/reporteBajaProducto', $array);
    }

    public function exportExcel($fecha, $ini = null, $fin = null)
    {
        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');
        $idSucursal = Session::get('idSucursal');

        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $bajaProductos = DB::select('call sp_getBajaProductos(?, ?, ?)', array($idSucursal, $fechas[0], $fechas[1]));
        $bajaProductos = collect($bajaProductos);
        $bajaProductos = $bajaProductos->map(function ($item, $key) {
            if ($item->IdCategoria != null) {
                $listaCategoria = DB::table('categoria')
                    ->where('IdCategoria', $item->IdCategoria)
                    ->first();
                $item->nombreCategoria = $listaCategoria->Nombre;
            } else {
                $item->nombreCategoria = '-';
            }
            if ($item->IdMarca != null) {
                $listaMarca = DB::table('marca')
                    ->where('IdMarca', $item->IdMarca)
                    ->first();
                $item->nombreMarca = $listaMarca->Nombre;
            } else {
                $item->nombreMarca = '-';
            }
            return $item;
        });
        return Excel::download(new ExcelReporteBajaProductos($bajaProductos), 'Reporte Baja Producto.xlsx');

    }
}
