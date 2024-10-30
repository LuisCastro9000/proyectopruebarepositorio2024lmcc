<?php

namespace App\Http\Controllers\Reportes\Almacen;

use App\Exports\ExcelReporteConsolidadoMovimientosInventario;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Session;

class ReporteConsolidadoMovimientosInventarioController extends Controller
{
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
            $loadDatos = new DatosController();
            $permisos = $loadDatos->getPermisos($idUsuario);
            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $mes = Carbon::now()->month;
            $anio = Carbon::now()->year;

            $inventario = $this->getInventario($mes, $anio);
            $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'inventario' => $inventario, 'anio' => $anio, 'mes' => $mes];
            return view('reportes/almacen/reporteConsolidadoMovimientosInventario', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function consultarInventario(Request $req)
    {
        $idUsuario = Session::get('idUsuario');
        $idSucursal = Session::get('idSucursal');
        $loadDatos = new DatosController();
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $mes = $req->mes;
        $anio = $req->anio;

        $inventario = $this->getInventario($mes, $anio);
        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'inventario' => $inventario, 'anio' => $anio, 'mes' => $mes];
        return view('reportes/almacen/reporteConsolidadoMovimientosInventario', $array);
    }

    public function exportarInventario($mes, $anio)
    {
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $inventario = $this->getInventario($mes, $anio);
        $nombreMes = ucfirst(Carbon::create()->month($mes)->isoFormat('MMMM'));
        return Excel::download(new ExcelReporteConsolidadoMovimientosInventario($inventario), "ReporteInventario-$nombreMes-$anio.xlsx");

    }

    public function getInventario($mes, $anio)
    {
        $idSucursal = Session::get('idSucursal');
        $firstDayOfMonth = Carbon::create($anio, $mes, 1)->toDaTeTimeString();
        $inventario = DB::table('articulo as A')
            ->select('A.IdArticulo', 'A.IdArticulo', 'A.Codigo as CodigoBarra', 'A.Descripcion', DB::raw('COALESCE(ES.Entradas, 0) AS Entradas'),
                DB::raw('COALESCE(ES.Salidas, 0) AS Salidas'), DB::raw('COALESCE(InventarioInicial, 0) AS InventarioInicial'), DB::raw('COALESCE((II.InventarioInicial + COALESCE(ES.Entradas, 0)) - COALESCE(ES.Salidas, 0),0) AS InventarioFinal'), 'UM.Nombre as UnidadMedida', 'marca.nombre as Marca', )
            ->join('unidad_medida as UM', 'A.IdUnidadMedida', '=', 'UM.IdUnidadMedida')
            ->join('marca', 'A.IdMarca', '=', 'marca.IdMarca')
            ->leftJoinSub(function ($join) use ($mes, $anio) {
                $join->from('kardex as K')
                    ->join('movimiento_kardex as MK', 'K.tipo_movimiento', '=', 'MK.IdMovimientoKardex')
                    ->whereMonth('K.fecha_movimiento', $mes)
                    ->whereYear('K.fecha_movimiento', $anio)
                    ->select('IdArticulo', 'MK.EstadoStock', DB::raw('SUM(CASE WHEN MK.EstadoStock = "S" THEN K.Cantidad ELSE 0 END) AS Salidas'), DB::raw('SUM(CASE WHEN MK.EstadoStock = "E" THEN K.Cantidad ELSE 0 END) AS Entradas'))
                    ->groupBy('IdArticulo');
            }, 'ES', 'A.IdArticulo', '=', 'ES.IdArticulo')
            ->leftJoinSub(function ($join) use ($firstDayOfMonth) {
                $join->from('kardex as K')
                    ->select('*', DB::raw('MAX(K.fecha_movimiento) AS min_fecha_movimiento'), 'existencia as InventarioInicial')
                    ->where('fecha_movimiento', '<', $firstDayOfMonth)
                    ->orderByDesc('fecha_movimiento')
                    ->groupBy('IdArticulo', 'fecha_movimiento');
            }, 'II', 'A.IdArticulo', '=', 'II.IdArticulo')
            ->where('A.IdSucursal', $idSucursal)
            ->where('A.Estado', 'E')
            ->where('IdTipo', 1)
            ->groupBy('A.IdArticulo')
            ->get();

        return $inventario;
    }
}

// public function getInventarioo($mes, $anio)
//     {
//         $idSucursal = Session::get('idSucursal');
//         $firstDayOfMonth = Carbon::create($anio, $mes, 1)->toDaTeTimeString();
//         $inventario = DB::table('articulo as A')
//             ->select('A.IdArticulo', 'A.IdArticulo as Codigo', 'A.Descripcion', DB::raw('COALESCE(E.Entradas, 0) AS Entradas'),
//                 DB::raw('COALESCE(S.Salidas, 0) AS Salidas'), DB::raw('COALESCE(InventarioInicial, 0) AS InventarioInicial'), DB::raw('COALESCE((F.InventarioInicial + COALESCE(E.Entradas, 0)) - COALESCE(S.Salidas, 0),0) AS InventarioFinal'), 'UM.Nombre as UnidadMedida', 'marca.nombre as Marca', )
//             ->join('unidad_medida as UM', 'A.IdUnidadMedida', '=', 'UM.IdUnidadMedida')
//             ->join('marca', 'A.IdMarca', '=', 'marca.IdMarca')
//             ->leftJoinSub(function ($join) use ($mes, $anio) {
//                 $join->from('kardex as K')
//                     ->join('movimiento_kardex as MK', 'K.tipo_movimiento', '=', 'MK.IdMovimientoKardex')
//                     ->whereMonth('K.fecha_movimiento', $mes)
//                     ->whereYear('K.fecha_movimiento', $anio)
//                     ->select('IdArticulo', 'MK.EstadoStock', DB::raw('SUM(K.Cantidad) AS Cantidad'))
//                     ->groupBy('IdArticulo', 'MK.EstadoStock');
//             }, 'K', 'A.IdArticulo', '=', 'K.IdArticulo')
//             ->leftJoinSub(function ($join) use ($mes, $anio) {
//                 $join->from('kardex as K')
//                     ->join('movimiento_kardex as MK', 'K.tipo_movimiento', '=', 'MK.IdMovimientoKardex')
//                     ->whereMonth('K.fecha_movimiento', $mes)
//                     ->whereYear('K.fecha_movimiento', $anio)
//                     ->select('IdArticulo', 'MK.EstadoStock', DB::raw('SUM(CASE WHEN MK.EstadoStock = "E" THEN K.Cantidad ELSE 0 END) AS Entradas'))
//                     ->groupBy('IdArticulo', 'MK.EstadoStock');
//             }, 'E', 'A.IdArticulo', '=', 'E.IdArticulo')
//             ->leftJoinSub(function ($join) use ($mes, $anio) {
//                 $join->from('kardex as K')
//                     ->join('movimiento_kardex as MK', 'K.tipo_movimiento', '=', 'MK.IdMovimientoKardex')
//                     ->whereMonth('K.fecha_movimiento', $mes)
//                     ->whereYear('K.fecha_movimiento', $anio)
//                     ->select('IdArticulo', 'MK.EstadoStock', DB::raw('SUM(CASE WHEN MK.EstadoStock = "S" THEN K.Cantidad ELSE 0 END) AS Salidas'))
//                     ->groupBy('IdArticulo', 'MK.EstadoStock');
//             }, 'S', 'A.IdArticulo', '=', 'S.IdArticulo')
//         // ->leftJoinSub(function ($join) use ($mes, $anio) {
//         //     $join->from('kardex as K')
//         //         ->select('IdArticulo', DB::raw('MIN(K.fecha_movimiento) AS min_fecha_movimiento'), 'existencia as InventarioInicial')
//         //         ->whereMonth('K.fecha_movimiento', $mes)
//         //         ->whereYear('K.fecha_movimiento', $anio)
//         //         ->groupBy('IdArticulo');
//         // }, 'F', 'A.IdArticulo', '=', 'F.IdArticulo')
//             ->leftJoinSub(function ($join) use ($firstDayOfMonth) {
//                 $join->from('kardex as K')
//                     ->select('*', DB::raw('MAX(K.fecha_movimiento) AS min_fecha_movimiento'), 'existencia as InventarioInicial')
//                     ->where('fecha_movimiento', '<', $firstDayOfMonth)
//                     ->orderByDesc('fecha_movimiento')
//                     ->groupBy('IdArticulo', 'fecha_movimiento');
//             }, 'F', 'A.IdArticulo', '=', 'F.IdArticulo')
//             ->where('A.IdSucursal', $idSucursal)
//             ->where('A.Estado', 'E')
//             ->where('IdTipo', 1)
//             ->groupBy('A.IdArticulo')
//             ->get();

//         return $inventario;
//     }

// $productos = DB::table('articulo as A')
//     ->select('A.IdArticulo', 'A.Descripcion', 'A.Stock', DB::raw('SUM(CASE WHEN MK.EstadoStock = "E" THEN K.Cantidad ELSE 0 END) AS Entradas'))
// // , DB::raw('SUM(CASE WHEN MK.EstadoStock = "E" THEN K.Cantidad ELSE 0 END) AS Entradas'), DB::raw('SUM(CASE WHEN MK.EstadoStock = "S" THEN K.Cantidad ELSE 0 END) AS Salidas')
// // ->leftJoin('kardex as K', 'A.IdArticulo', '=', 'K.IdArticulo')
//     ->leftJoin('kardex as K', function ($join) use ($mes) {
//         $join->on('A.IdArticulo', '=', 'K.IdArticulo')
//             ->whereMonth('K.fecha_movimiento', '=', $mes);
//     })
//     ->join('movimiento_kardex as MK', 'K.tipo_movimiento', '=', 'MK.IdMovimientoKardex')
//     ->where('A.IdSucursal', $idSucursal)
//     ->where('A.Estado', 'E')
//     ->whereIn('A.IdArticulo', function ($query) {
//         $query->select('A.IdArticulo')
//             ->from('kardex')
//             ->distinct()
//             ->limit(100);
//     })
// // ->whereBetween('K.fecha_movimiento', ['01-08-2023', '18-08-2023'])
//     ->distinct()
//     ->groupBy('A.IdArticulo', 'A.Descripcion')
//     ->get();

// $listaProductos = $productos = DB::table('articulo as A')->select('IdArticulo')->where('IdSucursal', $idSucursal)->where('Estado', 'E')->get();
// foreach ($listaProductos as $key => $item) {
//     $resultado = DB::table('kardex as K')
//         ->select('IdArticulo', 'tipo_movimiento', 'fecha_movimiento', DB::raw('SUM(CASE WHEN MK.EstadoStock = "E" THEN K.Cantidad ELSE 0 END) AS Entradas'), DB::raw('SUM(CASE WHEN MK.EstadoStock = "S" THEN K.Cantidad ELSE 0 END) AS Salidas'))
//         ->join('movimiento_kardex as MK', 'K.tipo_movimiento', '=', 'MK.IdMovimientoKardex')
//         ->where('IdArticulo', $item->IdArticulo)
//         ->whereBetween('K.fecha_movimiento', ['2023-07-31 23:59:59', '2023-08-19 23:59:59'])
//         ->first();

//     if ($resultado->Salidas != null && $resultado->Entradas != null) {
//         $listaProductos[$key]->Salidas = $resultado->Salidas;
//         $listaProductos[$key]->Entradas = $resultado->Entradas;
//     } else {
//         $listaProductos[$key]->Salidas = 0;
//         $listaProductos[$key]->Entradas = 0;
//     }
// }

// $listaProductos = DB::table('articulo as A')
//     ->select('A.IdArticulo', 'Entradas', 'Salidas', 'InventarioInicial', DB::raw('(F.InventarioInicial + COALESCE(E.Entradas, 0) - COALESCE(S.Salidas, 0)) AS InventarioFinal'))
//     ->leftJoinSub(function ($join) {
//         $join->from('kardex as K')
//             ->join('movimiento_kardex as MK', 'K.tipo_movimiento', '=', 'MK.IdMovimientoKardex')
//             ->whereBetween('K.fecha_movimiento', ['2023-07-31 23:59:59', '2023-08-19 23:59:59'])
//             ->select('IdArticulo', 'MK.EstadoStock', DB::raw('SUM(K.Cantidad) AS Cantidad'))
//             ->groupBy('IdArticulo', 'MK.EstadoStock');
//     }, 'K', 'A.IdArticulo', '=', 'K.IdArticulo')
//     ->leftJoinSub(function ($join) {
//         $join->from('kardex as K')
//             ->join('movimiento_kardex as MK', 'K.tipo_movimiento', '=', 'MK.IdMovimientoKardex')
//             ->whereBetween('K.fecha_movimiento', ['2023-07-31 23:59:59', '2023-08-19 23:59:59'])
//             ->select('IdArticulo', 'MK.EstadoStock', DB::raw('SUM(CASE WHEN MK.EstadoStock = "E" THEN K.Cantidad ELSE 0 END) AS Entradas'))
//             ->groupBy('IdArticulo', 'MK.EstadoStock');
//     }, 'E', 'A.IdArticulo', '=', 'E.IdArticulo')
//     ->leftJoinSub(function ($join) {
//         $join->from('kardex as K')
//             ->join('movimiento_kardex as MK', 'K.tipo_movimiento', '=', 'MK.IdMovimientoKardex')
//             ->whereBetween('K.fecha_movimiento', ['2023-07-31 23:59:59', '2023-08-19 23:59:59'])
//             ->select('IdArticulo', 'MK.EstadoStock', DB::raw('SUM(CASE WHEN MK.EstadoStock = "S" THEN K.Cantidad ELSE 0 END) AS Salidas'))
//             ->groupBy('IdArticulo', 'MK.EstadoStock');
//     }, 'S', 'A.IdArticulo', '=', 'S.IdArticulo')
//     ->leftJoinSub(function ($join) {
//         $join->from('kardex as K')
//             ->select('IdArticulo', DB::raw('MIN(K.fecha_movimiento) AS min_fecha_movimiento'), 'existencia as InventarioInicial')
//             ->whereBetween('K.fecha_movimiento', ['2023-07-31 23:59:59', '2023-08-19 23:59:59'])
//             ->groupBy('IdArticulo');
//     }, 'F', 'A.IdArticulo', '=', 'F.IdArticulo')
//     ->where('A.IdSucursal', $idSucursal)
//     ->where('A.Estado', 'E')
//     ->groupBy('A.IdArticulo')
//     ->get();
