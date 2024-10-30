<?php
namespace App\Http\Controllers\Reportes\Almacen;

use App\Exports\ExcelReporteStockPorFecha;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class ReporteStockPorFechaController extends Controller
{
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $permisos = $loadDatos->getPermisos($idUsuario);
            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $idSucursal = Session::get('idSucursal');

            $datosStock = [];
            $array = ['modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'permisos' => $permisos, 'datosStock' => $datosStock, 'fechaView' => '', 'fecha' => ''];
            return view('reportes/almacen/reporteStockPorFecha', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function store(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $permisos = $loadDatos->getPermisos($idUsuario);
            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

            $idSucursal = Session::get('idSucursal');
            $fechaView = $req->fecha;
            $fecha = Carbon::createFromFormat('d/m/Y', $req->fecha)->Format('Y-m-d H:i:s');
            $datosStock = $this->getDatosStock($idSucursal, $fecha);

            $fecha = Carbon::createFromFormat('d/m/Y', $req->fecha)->Format('Y-m-d');

            $array = ['modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'permisos' => $permisos, 'datosStock' => $datosStock, 'fechaView' => $fechaView, 'fecha' => $fecha];
            return view('reportes/almacen/reporteStockPorFecha', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function exportarExcel($fecha)
    {
        $idSucursal = Session::get('idSucursal');
        $fecha = Carbon::createFromFormat('Y-m-d', $fecha)->Format('Y-m-d H:i:s');
        $datosStock = $this->getDatosStock($idSucursal, $fecha);

        return Excel::download(new ExcelReporteStockPorFecha($datosStock), 'Reporte-Stock.xlsx');
    }

    private function getDatosStock($idSucursal, $fecha)
    {
        $stock = collect(DB::select('call sp_getDatosStockPorFecha(?)', array($idSucursal)));
        $resultadoFiltro = $stock->where('FechaMovimiento', '<=', $fecha);
        $idArticulosFiltrados = $resultadoFiltro->pluck('IdArticulo')->unique()->toArray();
        $datosStock = [];
        $arrayIdArticulo = [];

        for ($i = 0; $i <= count($stock) - 1; $i++) {
            if (in_array($stock[$i]->IdArticulo, $arrayIdArticulo) === false) {
                if (in_array($stock[$i]->IdArticulo, $idArticulosFiltrados)) {
                    $a = $stock->where('IdArticulo', $stock[$i]->IdArticulo)->max();
                    array_push($arrayIdArticulo, $a->IdArticulo);
                    array_push($datosStock, $a);
                } else {
                    $a = $stock->where('IdArticulo', $stock[$i]->IdArticulo)->max();
                    array_push($arrayIdArticulo, $a->IdArticulo);
                    $a->Existencia = '0.00';
                    array_push($datosStock, $a);
                }
            }
        }
        return $datosStock;
    }

}
