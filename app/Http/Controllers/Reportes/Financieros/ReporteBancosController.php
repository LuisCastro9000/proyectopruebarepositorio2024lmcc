<?php

namespace App\Http\Controllers\Reportes\Financieros;

use App\Exports\ExcelReporteFinancieroBancos;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use App\Traits\CuentasBancariasTrait;
use app\Traits\getFuncionesTrait;
use Carbon\Carbon;
use DateTime;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class ReporteBancosController extends Controller
{
    use getFuncionesTrait;
    use CuentasBancariasTrait;

    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {

            $idUsuario = Session::get('idUsuario');

            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $fechas = $loadDatos->getFechaFiltro(5, null, null);

            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $idBanco = 0;
            $fecha = 5;
            $fechaIni = $fechas[0];
            $fechaFin = $fechas[1];
            $detallesCuenta = [];
            $totalDetalleCuentaIngreso = 0;
            $totalDetalleCuentaSalida = 0;
            $nombreTipoMovimientoEntrada = "";
            $nombreTipoMovimientoSalida = "";
            $totalTipoMovimientoEntrada = "";
            $totalTipoMovimientoSalida = "";
            $montoActualCuenta = 0;

            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

            $cuentas = $loadDatos->getCuentasCorrientes($usuarioSelect->CodigoCliente, null);

            $array = ['idBanco' => $idBanco, 'fecha' => $fecha, 'permisos' => $permisos, 'cuentas' => $cuentas, 'detallesCuenta' => $detallesCuenta, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => '0', 'fin' => '0', 'modulosSelect' => $modulosSelect,
                'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'totalDetalleCuentaIngreso' => $totalDetalleCuentaIngreso, 'totalDetalleCuentaSalida' => $totalDetalleCuentaSalida, 'nombreTipoMovimientoSalida' => $nombreTipoMovimientoSalida, 'nombreTipoMovimientoEntrada' => $nombreTipoMovimientoEntrada, 'totalTipoMovimientoEntrada' => $totalTipoMovimientoEntrada, 'totalTipoMovimientoSalida' => $totalTipoMovimientoSalida, 'montoActualCuenta' => $montoActualCuenta];
            return view('reportes/financieros/reporteBancos', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi贸n de usuario Expirado');
        }
    }

    private function isSucursalPrincipal($idSucursal)
    {
        $resultado = DB::table('sucursal')->where('IdSucursal', $idSucursal)->value('Principal');
        return $resultado == 1;
    }

    public function store(Request $req)
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
            $fecha = $req->fecha;
            $fechaIni = $req->fechaIni;
            $fechaFin = $req->fechaFin;
            $idBanco = $req->cuentaBancaria;
            if ($idBanco === "0") {
                return back()->with('error', 'Seleccione Cuenta de banco');
            }
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

            $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);

            if ($fechaIni == null && $fechaFin == null) {
                $fechaIni = $fechas[0];
                $fechaFin = $fechas[1];
            }

            $ini = str_replace('/', '-', $fechaIni);
            $fin = str_replace('/', '-', $fechaFin);

            $cuentas = $loadDatos->getCuentasCorrientes($usuarioSelect->CodigoCliente, null);
            $idTipoMoneda = $cuentas->where('IdBanco', $idBanco)->pluck('IdTipoMoneda')->first();
            $resultadoCalculo = $this->calcularSaldoCuentaBancaria($idBanco, $usuarioSelect->CodigoCliente);
            $detallesCuenta = $resultadoCalculo->whereBetween('FechaPago', [$fechas[0], $fechas[1]])->sortKeysDesc();

            $detallesCuentaIngreso = $detallesCuenta->filter(function ($value, $key) {
                return $value->Entrada > 0;
            });
            $detallesCuentaSalida = $detallesCuenta->filter(function ($value, $key) {
                return $value->Salida > 0;
            });

            $totalTipoMovimientoEntrada = [];
            $total = [];
            foreach ($detallesCuentaIngreso as $key => $data) {
                if (!in_array($data->TipoMovimiento, $total)) {
                    $totalTipoMovimientoEntrada[$data->TipoMovimiento] = $data->Entrada;
                    $total[] = $data->TipoMovimiento;
                } else {
                    $totalTipoMovimientoEntrada[$data->TipoMovimiento] += $data->Entrada;
                }
            }
            $totalTipoMovimientoEntrada = collect($totalTipoMovimientoEntrada)->values();

            $totalTipoMovimientoSalida = [];
            $totalSalida = [];
            foreach ($detallesCuentaSalida as $key => $data) {
                if (!in_array($data->TipoMovimiento, $totalSalida)) {
                    $totalTipoMovimientoSalida[$data->TipoMovimiento] = $data->Salida;
                    $totalSalida[] = $data->TipoMovimiento;
                } else {
                    $totalTipoMovimientoSalida[$data->TipoMovimiento] += $data->Salida;
                }
            }
            $totalTipoMovimientoSalida = collect($totalTipoMovimientoSalida)->values();

            $totalDetalleCuentaIngreso = $detallesCuentaIngreso->pluck('Entrada')->sum();
            $totalDetalleCuentaSalida = $detallesCuentaSalida->pluck('Salida')->sum();

            $nombreTipoMovimientoEntrada = $detallesCuentaIngreso->unique('TipoMovimiento')->pluck('TipoMovimiento');
            $nombreTipoMovimientoSalida = $detallesCuentaSalida->unique('TipoMovimiento')->pluck('TipoMovimiento');
            $montoActualCuenta = $detallesCuenta->first()->SaldoActualCalculado ?? 0;
            // ================================================================================

            $array = ['idBanco' => $idBanco, 'fecha' => $fecha, 'cuentas' => $cuentas, 'detallesCuenta' => $detallesCuenta, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'modulosSelect' => $modulosSelect,
                'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'totalDetalleCuentaIngreso' => $totalDetalleCuentaIngreso, 'totalDetalleCuentaSalida' => $totalDetalleCuentaSalida, 'nombreTipoMovimientoSalida' => $nombreTipoMovimientoSalida, 'nombreTipoMovimientoEntrada' => $nombreTipoMovimientoEntrada, 'totalTipoMovimientoEntrada' => $totalTipoMovimientoEntrada, 'totalTipoMovimientoSalida' => $totalTipoMovimientoSalida, 'montoActualCuenta' => $montoActualCuenta, 'idTipoMoneda' => $idTipoMoneda, 'detallesCuentaIngreso' => $detallesCuentaIngreso, 'detallesCuentaSalida' => $detallesCuentaSalida];

            return view('reportes/financieros/reporteBancos', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi贸n de usuario Expirado');
        }

    }

    public function exportExcel($idBanco, $fecha, $ini, $fin)
    {
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);

        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $resultadoCalculo = $this->calcularSaldoCuentaBancaria($idBanco, $usuarioSelect->CodigoCliente);
        $detallesCuentaCorriente = $resultadoCalculo->whereBetween('FechaPago', [$fechas[0], $fechas[1]])->sortKeysDesc();

        return Excel::download(new ExcelReporteFinancieroBancos($detallesCuentaCorriente), 'Reporte Financieros-Bancos.xlsx');
    }

    public function getDetallesCuentaCorriente($codigoCliente)
    {
        try {
            $resultado = DB::table('banco_detalles')
                ->join('banco', 'banco_detalles.IdBanco', '=', 'banco.IdBanco')
                ->where('banco.CodigoCliente', $codigoCliente)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // public function calcularSaldoBanco($idBanco, $idSucursal)
    // {
    //     $getDetalleCuentaBanco = DB::table('banco_detalles as BD')
    //         ->join('banco', 'BD.IdBanco', '=', 'banco.IdBanco')
    //         ->select('BD.*', 'IdBancoDetalles', 'FechaPago', 'BD.IdBanco', 'Entrada', 'Salida', 'BD.MontoActual', 'banco.MontoInicial', DB::raw('MONTH(BD.FechaPago) as Mes, YEAR(BD.FechaPago) as Anio'))
    //         ->where('BD.IdBanco', $idBanco)
    //         ->when($this->isSucursalPrincipal($idSucursal), function ($query) use ($idSucursal) {
    //             $query->where(function ($subQuery) use ($idSucursal) {
    //                 $subQuery->where('IdSucursal', $idSucursal)
    //                     ->orWhereNull('IdSucursal');
    //             });
    //         }, function ($query) use ($idSucursal) {
    //             $query->where('IdSucursal', $idSucursal);
    //         })
    //         ->orderBy('FechaPago', 'desc')
    //         ->get();

    //     // NUEVO MONTO DETALLES CUENTA
    //     $detallesDesc = $getDetalleCuentaBanco->sortBy('FechaPago')->values();
    //     $bancoMontoInicial = $getDetalleCuentaBanco->pluck('MontoInicial')->first();
    //     $montoActual = 0;

    //     foreach ($detallesDesc as $key => $item) {
    //         if ($item->Entrada > 0) {
    //             $montoActual = $bancoMontoInicial + $item->Entrada;
    //             $bancoMontoInicial = abs($montoActual);
    //             $detallesDesc[$key]->SaldoActualCalculado = $montoActual;
    //             $detallesDesc[$key]->NombreMes = $this->getNombreMesAbreviado($item->Mes) . ' ' . $item->Anio;

    //         }
    //         if ($item->Salida > 0) {
    //             $montoActual = $bancoMontoInicial - $item->Salida;
    //             $bancoMontoInicial = abs($montoActual);
    //             $detallesDesc[$key]->SaldoActualCalculado = $montoActual;
    //             $detallesDesc[$key]->NombreMes = $this->getNombreMesAbreviado($item->Mes) . ' ' . $item->Anio;
    //         }

    //         if ($item->Salida == 0 && $item->Entrada == 0) {
    //             $detallesDesc[$key]->SaldoActualCalculado = $montoActual;
    //             $detallesDesc[$key]->NombreMes = $this->getNombreMesAbreviado($item->Mes) . ' ' . $item->Anio;
    //         }
    //     }
    //     return $detallesDesc;
    // }

}
