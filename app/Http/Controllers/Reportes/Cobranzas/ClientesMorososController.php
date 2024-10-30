<?php

namespace App\Http\Controllers\Reportes\Cobranzas;

use App\Exports\ExcelReporteClientesMorosos;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class ClientesMorososController extends Controller
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
        $fechas = $loadDatos->getFechaFiltro(5, null, null);
        $_clientesMorosos = DB::select('call sp_getClientesMorosos(?, ?, ?)', array($idSucursal, $fechas[0], $fechas[1]));

        //$_clientesMorosos = $loadDatos->getClientesMorosos($idSucursal);
        $clientesMorosos = $this->estadosCobranzas($_clientesMorosos);
        $fecha = 5;
        $fechaIni = '0';
        $fechaFin = '0';
        $ini = '0';
        $fin = '0';
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['permisos' => $permisos, 'clientesMorosos' => $clientesMorosos, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('reportes/cobranzas/reportesClientesMorosos', $array);
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
            if (Carbon::createFromFormat('d/m/Y', $fechaIni) > Carbon::createFromFormat('d/m/Y', $fechaFin)) {
                return back()->with('error', 'La fecha Inicial no puede ser mayor que la Final');
            }
        }
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        //$_clientesMorosos = $loadDatos->getClientesMorososFiltrados($idSucursal, $fecha, $fechaIni, $fechaFin);
        $_clientesMorosos = DB::select('call sp_getClientesMorosos(?, ?, ?)', array($idSucursal, $fechas[0], $fechas[1]));
        $clientesMorosos = $this->estadosCobranzas($_clientesMorosos);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $ini = str_replace('/', '-', $fechaIni);
        $fin = str_replace('/', '-', $fechaFin);

        $array = ['clientesMorosos' => $clientesMorosos, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('reportes/cobranzas/reportesClientesMorosos', $array);
    }

    public function exportExcel($fecha, $ini, $fin)
    {
        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');
        $idSucursal = Session::get('idSucursal');

        $fechaHoy = $loadDatos->getDateTime();
        //dd($fechas);

        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);
        /*     $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin); */
        if ($fecha == '0') {
            $_clientesMorosos = $loadDatos->getClientesMorosos($idSucursal);
            $clientesMorosos = $this->estadosCobranzas($_clientesMorosos);
        } else {
            $_clientesMorosos = $loadDatos->getClientesMorososFiltrados($idSucursal, $fecha, $fechaIni, $fechaFin);
            $clientesMorosos = $this->estadosCobranzas($_clientesMorosos);
        }

        return Excel::download(new ExcelReporteClientesMorosos($clientesMorosos), 'Reporte Cobranzas - Clientes Morosos.xlsx');
        /*$array = ['clientesMorosos' => $clientesMorosos];

    Excel::create('Reporte Clientes Morosos', function ($excel) use($array){
    $excel->sheet('Clientes Morosos', function ($sheet) use($array) {
    $sheet->getStyle('A:I', $sheet->getHighestRow())->getAlignment()->setWrapText(false);
    $sheet->loadView('clientesMorosos', $array);
    });
    })->download('xlsx');*/
    }

    private function estadosCobranzas($_clientesMorosos)
    {
        if (count($_clientesMorosos) > 0) {
            for ($i = 0; $i < count($_clientesMorosos); $i++) {
                $diasAtrasados = $_clientesMorosos[$i]->DiasPasados;
                if ($diasAtrasados <= 0) {
                    $diasAtrasados = 0;
                    $_clientesMorosos[$i]->Color = '#008000';
                }
                if ($diasAtrasados > 0 && $diasAtrasados <= 15) {
                    $_clientesMorosos[$i]->NombreEstado = 'Problema Potencial';
                    $_clientesMorosos[$i]->Color = '#77b300';
                }
                if ($diasAtrasados > 15 && $diasAtrasados <= 30) {
                    $_clientesMorosos[$i]->NombreEstado = 'Deficiente';
                    $_clientesMorosos[$i]->Color = '#ffff00';
                }
                if ($diasAtrasados > 30 && $diasAtrasados <= 60) {
                    $_clientesMorosos[$i]->NombreEstado = 'Dudoso';
                    $_clientesMorosos[$i]->Color = '#ff9900';
                }
                if ($diasAtrasados > 60) {
                    $_clientesMorosos[$i]->NombreEstado = 'Pérdida';
                    $_clientesMorosos[$i]->Color = '#ff0000';
                }
            }
            return $_clientesMorosos;
        } else {
            return $_clientesMorosos;
        }
    }
}
