<?php

namespace App\Http\Controllers\Reportes\Pagos;

use App\Exports\ExcelReporteComprasPorPagar;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class ComprasPorPagarController extends Controller
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

        $fechas = $loadDatos->getFechaFiltro(5, null, null);
        $pagos = DB::select('call sp_getPagos(?, ?, ?, ?)', array($idSucursal, null, $fechas[0], $fechas[1]));
        //dd($pagos);
        $proveedores = $loadDatos->getProveedores($idSucursal);
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $fechaHoy = $loadDatos->getDateTime();
        $pagosTotales = $loadDatos->getPagosProveedoresTotales($idSucursal, $fechaHoy);
        $this->actualizarFechasPasados($idSucursal, $pagosTotales);

        /*********************fecha  pago**********************/
        //$fecha_pago=DB::table('fecha_pago')->get();
        /******************************************************/

        $inputproveedor = 0;
        $fecha = 5;
        $fechaIni = 0;
        $fechaFin = 0;
        $ini = '0';
        $fin = '0';
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['permisos' => $permisos, 'proveedores' => $proveedores, 'pagos' => $pagos, 'inputproveedor' => $inputproveedor, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('reportes/pagos/reportesComprasPorPagar', $array);
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

        //$fechaHoy = $loadDatos->getDateTime();
        //$cobranzasTotales = $loadDatos->getCobranzasTotales($idSucursal, $fechaHoy);
        //$this->actualizarFechasPasados($idSucursal, $cobranzasTotales);

        $_inputproveedor = $req->proveedor;
        if ($_inputproveedor == null) {
            $inputproveedor = 0;
        } else {
            $inputproveedor = $_inputproveedor;
        }
        $fecha = $req->fecha;
        $fechaIni = trim($req->fechaIni) == false ? '0' : $req->fechaIni;
        $fechaFin = trim($req->fechaFin) == false ? '0' : $req->fechaFin;
        if ($fecha == 9) {
            if ($fechaIni == null || $fechaFin == null) {
                return back()->with('error', 'Completar las fechas para filtrar');
            }
            if (Carbon::createFromFormat('d/m/Y', $fechaIni) > Carbon::createFromFormat('d/m/Y', $fechaFin)) {
                return back()->with('error', 'La fecha Inicial no puede ser mayor que la Final');
            }
        }

        $idUsuario = Session::get('idUsuario');
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        /*********************fecha  pago**********************/
        //$fecha_pago=DB::table('fecha_pago')->get();
        /******************************************************/

        $proveedores = $loadDatos->getProveedores($idSucursal);
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        //$cobranzas = $loadDatos->getCobranzasFiltrados($idSucursal, $inputcliente, $fechas[0], $fechas[1]);
        $pagos = DB::select('call sp_getPagos(?, ?, ?, ?)', array($idSucursal, $_inputproveedor, $fechas[0], $fechas[1]));
        //dd($cobranzas);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $ini = str_replace('/', '-', $fechaIni);
        $fin = str_replace('/', '-', $fechaFin);
        $array = ['permisos' => $permisos, 'proveedores' => $proveedores, 'pagos' => $pagos, 'inputproveedor' => $inputproveedor, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('reportes/pagos/reportesComprasPorPagar', $array);
    }

    public function exportExcel($proveedor, $fecha, $ini, $fin)
    {
        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');
        $idSucursal = Session::get('idSucursal');

        $fechaHoy = $loadDatos->getDateTime();

        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);
        if ($proveedor == '0') {
            $proveedor = null;
        }
        /*if($cliente=='0' && $fecha  == '0' )
        {
        $fecha_pago=DB::table('fecha_pago')->get();
        $cobranzas = $loadDatos->getCobranzas($idSucursal);
        }
        else
        {
        $fecha_pago=DB::table('fecha_pago')->get();
        $cobranzas = $loadDatos->getCobranzasFiltrados($idSucursal, $cliente, $fecha, $fechas[0], $fechas[1]);

        }*/
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);

        $pagos = DB::select('call sp_getPagos(?, ?, ?, ?)', array($idSucursal, $proveedor, $fechas[0], $fechas[1]));

        return Excel::download(new ExcelReporteComprasPorPagar($pagos), 'Reporte Pagos - Compras por Pagar.xlsx');

        /*$array = ['pagos' => $pagos];

    Excel::create('Reporte Compras por Pagar', function ($excel) use($array){
    $excel->sheet('Compras por Pagar', function ($sheet) use($array) {
    $sheet->loadView('excel/excelComprasPorPagar', $array);
    });
    })->download('xlsx');*/
    }

    private function actualizarFechasPasados($idSucursal, $noVencidos)
    {
        for ($i = 0; $i < count($noVencidos); $i++) {
            DB::table('fecha_compras')
                ->join('compras', 'fecha_compras.IdCompras', '=', 'compras.IdCompras')
                ->where('compras.IdSucursal', $idSucursal)
                ->where('fecha_compras.Estado', '!=', 2)
                ->where('fecha_compras.IdFechaCompras', $noVencidos[$i]->IdFechaCompras)
                ->update(['DiasPasados' => $noVencidos[$i]->Dias]);
        }
    }
}
