<?php

namespace App\Http\Controllers\Reportes\Almacen;

use App\Exports\ExcelReporteTraspasos;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class ReporteTraspasosController extends Controller
{
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $idSucursal = Session::get('idSucursal');
            $fechas = $loadDatos->getFechaFiltro(0, null, null);
            //$reporteTraspasos = DB::select('call sp_getTraspasos(?, ?, ?)',array($idSucursal, $fechas[0], $fechas[1]));
            $traspasos = [];
            $fecha = 5;
            $fechaIni = '0';
            $fechaFin = '0';
            $ini = '0';
            $fin = '0';

            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $almacenes = $loadDatos->getAlmacenes($usuarioSelect->CodigoCliente, $idSucursal);
            $_sucursal = $loadDatos->getSucursalSelect($idSucursal);
            $sucursal = '0';
            $arrayProducto = [];
            $arrayCantidad = [];
            $array = ['grafProductosTraspasos' => $arrayProducto, 'grafCantidadTraspasos' => $arrayCantidad, 'permisos' => $permisos, 'traspasos' => $traspasos, 'tipoTraspaso' => 0, 'sucursal' => $sucursal, '_sucursal' => $_sucursal, 'almacenes' => $almacenes, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
            return view('reportes/almacen/reporteTraspasos', $array);
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
            $fecha = $req->fecha;
            $tipoTraspaso = $req->tipoTraspaso;
            $sucursal = $req->sucursal;
            $iniSuc = substr($sucursal, 0, 1);
            $sucAlmacen = substr($sucursal, 1);

            if ($iniSuc == "s") {
                $tipoOrigen = 1;
            } else {
                $tipoOrigen = 2;
            }
            $fechaIni = trim($req->fechaIni) == false ? '0' : $req->fechaIni;
            $fechaFin = trim($req->fechaFin) == false ? '0' : $req->fechaFin;

            if ($fecha == 9) {
                if ($fechaIni == null || $fechaFin == null) {
                    return back()->with('error', 'Completar las fechas para filtrar');
                }
                if ($fechaIni > $fechaFin) {
                    return back()->with('error', 'La fecha Inicial no puede ser mayor que la Final');
                }
            }
            $idSucursal = Session::get('idSucursal');
            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);

            $traspasos = DB::select('call sp_getTraspasos(?, ?, ?, ?, ?, ?)', array($idSucursal, $sucAlmacen, $tipoOrigen, $tipoTraspaso, $fechas[0], $fechas[1]));

            $_sucursal = $loadDatos->getSucursalSelect($idSucursal);
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $almacenes = $loadDatos->getAlmacenes($usuarioSelect->CodigoCliente, $idSucursal);

            $arrayProducto = [];
            $arrayCantidad = [];
            if (count($traspasos) >= 1) {
                $i = 0;
                foreach ($traspasos as $traspaso) {
                    if ($i < 10) {
                        $arrayProducto[$i] = "'$traspaso->Producto'";
                        $arrayCantidad[$i] = $traspaso->Cantidad;
                    }
                    $i++;
                }
            }
            $ini = str_replace('/', '-', $fechaIni);
            $fin = str_replace('/', '-', $fechaFin);
            $array = ['grafProductosTraspasos' => $arrayProducto, 'grafCantidadTraspasos' => $arrayCantidad, 'traspasos' => $traspasos, 'sucursal' => $sucursal, '_sucursal' => $_sucursal, 'almacenes' => $almacenes, 'tipoTraspaso' => $tipoTraspaso, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
            return view('reportes/almacen/reporteTraspasos', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function exportExcel($sucursal, $tipoTraspaso, $fecha, $ini, $fin)
    {
        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');
        $idSucursal = Session::get('idSucursal');

        $fechaHoy = $loadDatos->getDateTime();
        //dd($fechas);

        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);
        /*     $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin); */
        /*if($fecha  == '0' )
        {
        $pagosParciales = $loadDatos->getPagosParciales($idSucursal);
        }
        else
        {
        $pagosParciales = $loadDatos->getPagosParcialesFiltrados($idSucursal, $fecha, $fechaIni, $fechaFin);
        }*/

        $iniSuc = substr($sucursal, 0, 1);
        $sucAlmacen = substr($sucursal, 1);
        if ($iniSuc == "s") {
            $tipoOrigen = 1;
        } else {
            $tipoOrigen = 2;
        }

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $traspasos = DB::select('call sp_getTraspasos(?, ?, ?, ?, ?, ?)', array($idSucursal, $sucAlmacen, $tipoOrigen, $tipoTraspaso, $fechas[0], $fechas[1]));
        //$bajaProductos = DB::select('call sp_getBajaProductos(?, ?, ?)',array($idSucursal, $fechas[0], $fechas[1]));

        return Excel::download(new ExcelReporteTraspasos($traspasos, $tipoTraspaso), 'Reporte Traspasos.xlsx');

        /*$array = ['bajaProductos' => $bajaProductos];

    Excel::create('Reporte Baja de Productos', function ($excel) use($array){
    $excel->sheet('Baja de Productos', function ($sheet) use($array) {
    $sheet->loadView('excel/excelBajaProductos', $array);
    });
    })->download('xlsx');*/
    }
}
