<?php

namespace App\Http\Controllers\Caja;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Http\Request;
use PDF;
use PHPMailer\PHPMailer\PHPMailer;
use Session;

class CierreCajaController extends Controller
{
    public function __invoke(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $array = $this->detallesCaja($idUsuario);
        return view('caja/cierreCaja', $array);
    }

    private function detallesCaja($idUsuario)
    {
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $hoy = Carbon::today();
        $fechaAntes = $hoy->subDays(7);
        $date = new DateTime();
        $hoyDia = date_format($date, 'd-m-Y');
        $comprobantesPendientes = $loadDatos->getFacturasPendientes($idSucursal, $fechaAntes);
        $resumenPendientes = $loadDatos->getResumenDiarioPendientes($idSucursal, $fechaAntes);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $turno = $usuarioSelect->Nombre;
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $ventasAperturaCierreCaja = [];

        if ($caja == null) {
            $caja = $loadDatos->getCierreCajaUltimo($idSucursal, $idUsuario);
            if ($caja == null) {
                $idCaja = 0;
                $estado = 'D';
                $inicialSoles = '0.00';
                $inicialDolares = '0.00';
                $ventasContadoEfectivoSoles = '0.00';
                $ventasContadoTarjetaSoles = '0.00';
                $ventasContadoEfectivoDolares = '0.00';
                $ventasContadoTarjetaDolares = '0.00';
                $ventasContadoCuentaBancariaSoles = '0.00';
                $ventasContadoCuentaBancariaDolares = '0.00';
                $totalVentasContadoSoles = '0.00';
                $totalVentasContadoDolares = '0.00';
                $montoIngresosSoles = '0.00';
                $montoEgresosSoles = '0.00';
                $montoIngresosDolares = '0.00';
                $montoEgresosDolares = '0.00';
                $cajaTotalSoles = '0.00';
                $cajaTotalDolares = '0.00';
                $ultimoSesion = '';
                $totalCobranzasSoles = '0.00';
                $totalCobranzasDolares = '0.00';
                $cobranzasEfectivoSoles = '0.00';
                $cobranzasEfectivoDolares = '0.00';
                $cobranzasTarjetaSoles = '0.00';
                $cobranzasTarjetaDolares = '0.00';
                $cobranzasCuentaBancariaSoles = '0.00';
                $cobranzasCuentaBancariaDolares = '0.00';
                $totalAmortizacionSoles = '0.00';
                $totalAmortizacionDolares = '0.00';
                $amortizacionEfectivoSoles = '0.00';
                $amortizacionEfectivoDolares = '0.00';
                $amortizacionTarjetaSoles = '0.00';
                $amortizacionTarjetaDolares = '0.00';
                $amortizacionCuentaBancariaSoles = '0.00';
                $amortizacionCuentaBancariaDolares = '0.00';
            } else {
                $idCaja = $caja->IdCaja;
                $ventasAperturaCierreCaja = $loadDatos->getVentasAperturaCierreCaja($idSucursal, $idUsuario, $caja->FechaApertura);
                for ($i = 0; $i < count($ventasAperturaCierreCaja); $i++) {
                    $_productos = $loadDatos->getItemsVentas($ventasAperturaCierreCaja[$i]->IdVentas);
                    $ventasAperturaCierreCaja[$i]->Productos = $_productos;
                }
                //dd($ventasAperturaCierreCaja);
                $ventasContadoSoles = $loadDatos->getDetalleCaja($idSucursal, $idUsuario, $caja->FechaApertura, 1, 1);
                $ventasContadoDolares = $loadDatos->getDetalleCaja($idSucursal, $idUsuario, $caja->FechaApertura, 1, 2);
                $cobranzasSoles = $loadDatos->getDetalleCajaCobranzas($idSucursal, $idUsuario, $caja->FechaApertura, 1);
                $cobranzasDolares = $loadDatos->getDetalleCajaCobranzas($idSucursal, $idUsuario, $caja->FechaApertura, 2);
                $ventasContadoTotalSoles = $loadDatos->getDetalleCajaContado($idSucursal, $idUsuario, $caja->FechaApertura, 1);
                $ventasContadoTotalDolares = $loadDatos->getDetalleCajaContado($idSucursal, $idUsuario, $caja->FechaApertura, 2);
                $estado = $caja->Estado;
                $inicialSoles = $caja->Inicial;
                $inicialDolares = $caja->InicialDolares;
                $ingresosSol = $loadDatos->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, 'I', 1);
                $ingresosDol = $loadDatos->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, 'I', 2);

                if ($ingresosSol[0]->Monto == null) {
                    $montoIngresosSoles = '0.00';
                } else {
                    $montoIngresosSoles = $ingresosSol[0]->Monto;
                }
                if ($ingresosDol[0]->Monto == null) {
                    $montoIngresosDolares = '0.00';
                } else {
                    $montoIngresosDolares = $ingresosDol[0]->Monto;
                }
                $egresosSol = $loadDatos->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, 'E', 1);
                $egresosDol = $loadDatos->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, 'E', 2);
                if ($egresosSol[0]->Monto == null) {
                    $montoEgresosSoles = '0.00';
                } else {
                    $montoEgresosSoles = $egresosSol[0]->Monto;
                }

                if ($egresosDol[0]->Monto == null) {
                    $montoEgresosDolares = '0.00';
                } else {
                    $montoEgresosDolares = $egresosDol[0]->Monto;
                }
                // $ultimoSesion = Carbon::parse($caja->FechaCierre);
                $date = new DateTime($caja->FechaCierre);
                $ultimoSesion = $date->format("d-m-Y / H:i:s a");
                $ventasContadoEfectivoSoles = $ventasContadoTotalSoles[0]->Efectivo;
                if ($ventasContadoEfectivoSoles == null) {
                    $ventasContadoEfectivoSoles = '0.00';
                }
                $ventasContadoTarjetaSoles = $ventasContadoTotalSoles[0]->Tarjeta;
                if ($ventasContadoTarjetaSoles == null) {
                    $ventasContadoTarjetaSoles = '0.00';
                }
                $ventasContadoCuentaBancariaSoles = $ventasContadoTotalSoles[0]->CuentaBancaria;
                if ($ventasContadoCuentaBancariaSoles == null) {
                    $ventasContadoCuentaBancariaSoles = '0.00';
                }
                $ventasContadoEfectivoDolares = $ventasContadoTotalDolares[0]->Efectivo;
                if ($ventasContadoEfectivoDolares == null) {
                    $ventasContadoEfectivoDolares = '0.00';
                }
                $ventasContadoTarjetaDolares = $ventasContadoTotalDolares[0]->Tarjeta;
                if ($ventasContadoTarjetaDolares == null) {
                    $ventasContadoTarjetaDolares = '0.00';
                }
                $ventasContadoCuentaBancariaDolares = $ventasContadoTotalDolares[0]->CuentaBancaria;
                if ($ventasContadoCuentaBancariaDolares == null) {
                    $ventasContadoCuentaBancariaDolares = '0.00';
                }
                $totalVentasContadoSoles = $ventasContadoSoles->ImporteTotal;
                $totalVentasContadoDolares = $ventasContadoDolares->ImporteTotal;
                $totalCobranzasSoles = $cobranzasSoles[0]->TotalCobranza;
                if ($totalCobranzasSoles == null) {
                    $totalCobranzasSoles = '0.00';
                }
                $totalCobranzasDolares = $cobranzasDolares[0]->TotalCobranza;
                if ($totalCobranzasDolares == null) {
                    $totalCobranzasDolares = '0.00';
                }
                $cobranzasEfectivoSoles = $cobranzasSoles[0]->Efectivo;
                if ($cobranzasEfectivoSoles == null) {
                    $cobranzasEfectivoSoles = '0.00';
                }
                $cobranzasEfectivoDolares = $cobranzasDolares[0]->Efectivo;
                if ($cobranzasEfectivoDolares == null) {
                    $cobranzasEfectivoDolares = '0.00';
                }
                $cobranzasTarjetaSoles = $cobranzasSoles[0]->Tarjeta;
                if ($cobranzasTarjetaSoles == null) {
                    $cobranzasTarjetaSoles = '0.00';
                }
                $cobranzasTarjetaDolares = $cobranzasDolares[0]->Tarjeta;
                if ($cobranzasTarjetaDolares == null) {
                    $cobranzasTarjetaDolares = '0.00';
                }
                $cobranzasCuentaBancariaSoles = $cobranzasSoles[0]->CuentaBancaria;
                if ($cobranzasCuentaBancariaSoles == null) {
                    $cobranzasCuentaBancariaSoles = '0.00';
                }
                $cobranzasCuentaBancariaDolares = $cobranzasDolares[0]->CuentaBancaria;
                if ($cobranzasCuentaBancariaDolares == null) {
                    $cobranzasCuentaBancariaDolares = '0.00';
                }
                if (floatval($totalVentasContadoSoles) == 0) {
                    $totalVentasContadoSoles = '0.00';
                }
                if (floatval($totalVentasContadoSoles) == 0) {
                    $totalVentasContadoSoles = '0.00';
                }
                if (floatval($totalVentasContadoDolares) == 0) {
                    $totalVentasContadoDolares = '0.00';
                }
                $amortizacionSoles = $loadDatos->getAmortizacionesTotales($idSucursal, $idUsuario, $caja->FechaApertura, 1);
                $amortizacionDolares = $loadDatos->getAmortizacionesTotales($idSucursal, $idUsuario, $caja->FechaApertura, 2);
                $totalAmortizacionSoles = $amortizacionSoles->sum('Monto');
                $totalAmortizacionDolares = $amortizacionDolares->sum('Monto');
                $amortizacionEfectivoSoles = $amortizacionSoles->where('FormaPago', 1)->sum('Monto');
                $amortizacionEfectivoDolares = $amortizacionDolares->where('FormaPago', 1)->sum('Monto');
                $amortizacionTarjetaSoles = $amortizacionSoles->where('FormaPago', 2)->sum('Monto');
                $amortizacionTarjetaDolares = $amortizacionDolares->where('FormaPago', 2)->sum('Monto');
                $amortizacionCuentaBancariaSoles = $amortizacionSoles->where('FormaPago', 3)->sum('Monto');
                $amortizacionCuentaBancariaDolares = $amortizacionDolares->where('FormaPago', 3)->sum('Monto');
                $cajaTotalSoles = floatval($cobranzasEfectivoSoles) + floatval($ventasContadoEfectivoSoles) + floatval($amortizacionEfectivoSoles) + floatval($inicialSoles) + floatval($montoIngresosSoles) - floatval($montoEgresosSoles);
                $cajaTotalDolares = floatval($cobranzasEfectivoDolares) + floatval($ventasContadoEfectivoDolares) + floatval($amortizacionEfectivoDolares) + floatval($inicialDolares) + floatval($montoIngresosDolares) - floatval($montoEgresosDolares);
            }
        } else {
            $idCaja = $caja->IdCaja;
            $ventasContadoSoles = $loadDatos->getDetalleCaja($idSucursal, $idUsuario, $caja->FechaApertura, 1, 1);
            $ventasContadoDolares = $loadDatos->getDetalleCaja($idSucursal, $idUsuario, $caja->FechaApertura, 1, 2);
            $cobranzasSoles = $loadDatos->getDetalleCajaCobranzas($idSucursal, $idUsuario, $caja->FechaApertura, 1);
            $cobranzasDolares = $loadDatos->getDetalleCajaCobranzas($idSucursal, $idUsuario, $caja->FechaApertura, 2);
            $ventasContadoTotalSoles = $loadDatos->getDetalleCajaContado($idSucursal, $idUsuario, $caja->FechaApertura, 1);
            $ventasContadoTotalDolares = $loadDatos->getDetalleCajaContado($idSucursal, $idUsuario, $caja->FechaApertura, 2);
            $ingresosSol = $loadDatos->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, 'I', 1);
            $ingresosDol = $loadDatos->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, 'I', 2);

            if ($ingresosSol[0]->Monto == null) {
                $montoIngresosSoles = '0.00';
            } else {
                $montoIngresosSoles = $ingresosSol[0]->Monto;
            }
            if ($ingresosDol[0]->Monto == null) {
                $montoIngresosDolares = '0.00';
            } else {
                $montoIngresosDolares = $ingresosDol[0]->Monto;
            }
            $egresosSol = $loadDatos->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, 'E', 1);
            $egresosDol = $loadDatos->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, 'E', 2);

            if ($egresosSol[0]->Monto == null) {
                $montoEgresosSoles = '0.00';
            } else {
                $montoEgresosSoles = $egresosSol[0]->Monto;
            }
            if ($egresosDol[0]->Monto == null) {
                $montoEgresosDolares = '0.00';
            } else {
                $montoEgresosDolares = $egresosDol[0]->Monto;
            }

            $estado = $caja->Estado;
            $inicialSoles = $caja->Inicial;
            $inicialDolares = $caja->InicialDolares;
            $ultimoSesion = $this->convertirFecha($caja->FechaApertura);
            $ventasContadoEfectivoSoles = $ventasContadoTotalSoles[0]->Efectivo;
            if ($ventasContadoEfectivoSoles == null) {
                $ventasContadoEfectivoSoles = '0.00';
            }
            $ventasContadoTarjetaSoles = $ventasContadoTotalSoles[0]->Tarjeta;
            if ($ventasContadoTarjetaSoles == null) {
                $ventasContadoTarjetaSoles = '0.00';
            }
            $ventasContadoCuentaBancariaSoles = $ventasContadoTotalSoles[0]->CuentaBancaria;
            if ($ventasContadoCuentaBancariaSoles == null) {
                $ventasContadoCuentaBancariaSoles = '0.00';
            }
            $ventasContadoEfectivoDolares = $ventasContadoTotalDolares[0]->Efectivo;
            if ($ventasContadoEfectivoDolares == null) {
                $ventasContadoEfectivoDolares = '0.00';
            }
            $ventasContadoTarjetaDolares = $ventasContadoTotalDolares[0]->Tarjeta;
            if ($ventasContadoTarjetaDolares == null) {
                $ventasContadoTarjetaDolares = '0.00';
            }
            $ventasContadoCuentaBancariaDolares = $ventasContadoTotalDolares[0]->CuentaBancaria;
            if ($ventasContadoCuentaBancariaDolares == null) {
                $ventasContadoCuentaBancariaDolares = '0.00';
            }
            $totalVentasContadoSoles = $ventasContadoSoles->ImporteTotal;
            $totalVentasContadoDolares = $ventasContadoDolares->ImporteTotal;
            $totalCobranzasSoles = $cobranzasSoles[0]->TotalCobranza;
            if ($totalCobranzasSoles == null) {
                $totalCobranzasSoles = '0.00';
            }
            $totalCobranzasDolares = $cobranzasDolares[0]->TotalCobranza;
            if ($totalCobranzasDolares == null) {
                $totalCobranzasDolares = '0.00';
            }
            $cobranzasEfectivoSoles = $cobranzasSoles[0]->Efectivo;
            if ($cobranzasEfectivoSoles == null) {
                $cobranzasEfectivoSoles = '0.00';
            }
            $cobranzasEfectivoDolares = $cobranzasDolares[0]->Efectivo;
            if ($cobranzasEfectivoDolares == null) {
                $cobranzasEfectivoDolares = '0.00';
            }
            $cobranzasTarjetaSoles = $cobranzasSoles[0]->Tarjeta;
            if ($cobranzasTarjetaSoles == null) {
                $cobranzasTarjetaSoles = '0.00';
            }
            $cobranzasTarjetaDolares = $cobranzasDolares[0]->Tarjeta;
            if ($cobranzasTarjetaDolares == null) {
                $cobranzasTarjetaDolares = '0.00';
            }
            $cobranzasCuentaBancariaSoles = $cobranzasSoles[0]->CuentaBancaria;
            if ($cobranzasCuentaBancariaSoles == null) {
                $cobranzasCuentaBancariaSoles = '0.00';
            }
            $cobranzasCuentaBancariaDolares = $cobranzasDolares[0]->CuentaBancaria;
            if ($cobranzasCuentaBancariaDolares == null) {
                $cobranzasCuentaBancariaDolares = '0.00';
            }
            if (floatval($totalVentasContadoSoles) == 0) {
                $totalVentasContadoSoles = '0.00';
            }
            if (floatval($totalVentasContadoDolares) == 0) {
                $totalVentasContadoDolares = '0.00';
            }
            $amortizacionSoles = $loadDatos->getAmortizacionesTotales($idSucursal, $idUsuario, $caja->FechaApertura, 1);
            $amortizacionDolares = $loadDatos->getAmortizacionesTotales($idSucursal, $idUsuario, $caja->FechaApertura, 2);
            $totalAmortizacionSoles = $amortizacionSoles->sum('Monto');
            $totalAmortizacionDolares = $amortizacionDolares->sum('Monto');
            $amortizacionEfectivoSoles = $amortizacionSoles->where('FormaPago', 1)->sum('Monto');
            $amortizacionEfectivoDolares = $amortizacionDolares->where('FormaPago', 1)->sum('Monto');
            $amortizacionTarjetaSoles = $amortizacionSoles->where('FormaPago', 2)->sum('Monto');
            $amortizacionTarjetaDolares = $amortizacionDolares->where('FormaPago', 2)->sum('Monto');
            $amortizacionCuentaBancariaSoles = $amortizacionSoles->where('FormaPago', 3)->sum('Monto');
            $amortizacionCuentaBancariaDolares = $amortizacionDolares->where('FormaPago', 3)->sum('Monto');
            $cajaTotalSoles = floatval($cobranzasEfectivoSoles) + floatval($ventasContadoEfectivoSoles) + floatval($amortizacionEfectivoSoles) + floatval($inicialSoles) + floatval($montoIngresosSoles) - floatval($montoEgresosSoles);
            $cajaTotalDolares = floatval($cobranzasEfectivoDolares) + floatval($ventasContadoEfectivoDolares) + floatval($amortizacionEfectivoDolares) + floatval($inicialDolares) + floatval($montoIngresosDolares) - floatval($montoEgresosDolares);
        }
        $cajaTotalSoles = sprintf("%01.2f", $cajaTotalSoles);
        $cajaTotalDolares = sprintf("%01.2f", $cajaTotalDolares);
        $array = ['permisos' => $permisos, 'totalVentasContadoSoles' => $totalVentasContadoSoles, 'totalVentasContadoDolares' => $totalVentasContadoDolares, 'totalCobranzasSoles' => $totalCobranzasSoles, 'totalCobranzasDolares' => $totalCobranzasDolares, 'cobranzasEfectivoSoles' => $cobranzasEfectivoSoles, 'cobranzasTarjetaSoles' => $cobranzasTarjetaSoles, 'cobranzasEfectivoDolares' => $cobranzasEfectivoDolares, 'cobranzasTarjetaDolares' => $cobranzasTarjetaDolares, 'cobranzasCuentaBancariaSoles' => $cobranzasCuentaBancariaSoles, 'cobranzasCuentaBancariaDolares' => $cobranzasCuentaBancariaDolares, 'ventasContadoEfectivoSoles' => $ventasContadoEfectivoSoles,
            'ventasContadoCuentaBancariaSoles' => $ventasContadoCuentaBancariaSoles, 'ventasContadoCuentaBancariaDolares' => $ventasContadoCuentaBancariaDolares, 'totalAmortizacionSoles' => $totalAmortizacionSoles, 'totalAmortizacionDolares' => $totalAmortizacionDolares, 'amortizacionEfectivoSoles' => $amortizacionEfectivoSoles, 'amortizacionEfectivoDolares' => $amortizacionEfectivoDolares, 'amortizacionTarjetaSoles' => $amortizacionTarjetaSoles, 'amortizacionTarjetaDolares' => $amortizacionTarjetaDolares, 'amortizacionCuentaBancariaSoles' => $amortizacionCuentaBancariaSoles, 'amortizacionCuentaBancariaDolares' => $amortizacionCuentaBancariaDolares,
            'ventasContadoTarjetaSoles' => $ventasContadoTarjetaSoles, 'ventasContadoEfectivoDolares' => $ventasContadoEfectivoDolares, 'ventasContadoTarjetaDolares' => $ventasContadoTarjetaDolares, 'cajaTotalSoles' => $cajaTotalSoles, 'cajaTotalDolares' => $cajaTotalDolares, 'inicialSoles' => $inicialSoles, 'inicialDolares' => $inicialDolares, 'ultimoSesion' => $ultimoSesion, 'montoIngresosSoles' => $montoIngresosSoles, 'montoEgresosSoles' => $montoEgresosSoles, 'montoIngresosDolares' => $montoIngresosDolares, 'montoEgresosDolares' => $montoEgresosDolares, 'modulosSelect' => $modulosSelect, 'estado' => $estado, 'comprobantesPendientes' => $comprobantesPendientes, 'resumenPendientes' => $resumenPendientes, 'hoyDia' => $hoyDia, 'turno' => $turno, 'ventasAperturaCierreCaja' => $ventasAperturaCierreCaja, 'idCaja' => $idCaja, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];

        return $array;
    }

    public function abrirCaja(Request $req)
    {
        try {
            if ($req->ajax()) {
                $inicialSoles = $req->inicialSoles;
                $inicialDolares = $req->inicialDolares;
                if ($inicialSoles == null || $inicialSoles == '') {
                    $inicialSoles = '0.00';
                }
                if ($inicialDolares == null || $inicialDolares == '') {
                    $inicialDolares = '0.00';
                }
                $loadDatos = new DatosController();
                $idSucursal = Session::get('idSucursal');
                $idUsuario = Session::get('idUsuario');
                $date = new DateTime();
                $fecha = $date->format("Y-m-d H:i:s");
                $estado = 'E';
                $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
                if ($caja != null) {
                    return Response('La caja ya se encuentra abierta');
                } else {
                    $array = ['IdUsuario' => $idUsuario, 'IdSucursal' => $idSucursal, 'Inicial' => $inicialSoles, 'InicialDolares' => $inicialDolares, 'FechaApertura' => $fecha, 'Estado' => $estado];
                    DB::table('caja')->insert($array);
                    return Response('Se abrió la caja corrrectamente');
                }

            }

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function cerrarCaja(Request $req)
    {
        try {
            if ($req->ajax()) {
                $date = new DateTime();
                $fecha = $date->format("Y-m-d H:i:s");
                $estado = 'D';
                $loadDatos = new DatosController();
                $idSucursal = Session::get('idSucursal');
                $idUsuario = Session::get('idUsuario');
                $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
                if ($caja != null) {
                    $array = ['FechaCierre' => $fecha, 'Estado' => $estado];
                    DB::table('caja')
                        ->where('IdCaja', $caja->IdCaja)
                        ->where('IdUsuario', $idUsuario)
                        ->update($array);
                    return Response('Se cerro caja correctamente');
                } else {
                    return Response('La caja ya se encuentra cerrada');
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function imprimir(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $tipoImpresion = $req->selectImpre;
        $pdf = $this->generarPDF($idUsuario, $tipoImpresion);
        return $pdf->stream('caja.pdf');
    }

    private function generarPDF($idUsuario, $tipo)
    {
        $array = $this->detallesCaja($idUsuario);
        //dd($array);
        view()->share($array);

        if ($tipo == 1) {
            $pdf = PDF::loadView('cajaPDF')->setPaper('a4', 'portrait');
        } else {
            $pdf = PDF::loadView('cajaTicket')->setPaper(array(0, 0, 107, 600));
        }

        return $pdf;
    }

    public function enviarCorreo(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $caja = $loadDatos->getCajaSelect($id);

        $pdf = $this->generarPDF($idUsuario, 1);
        file_put_contents('reporte-caja.pdf', $pdf->output());

        $mail = new PHPMailer();
        //$mail->isSMTP();                                     // Set mailer to use SMTP
        $mail->Host = 'mail.easyfactperu.pe'; // Specify main and backup SMTP servers
        //$mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'facturacion@easyfactperu.pe'; // SMTP username
        $mail->Debugoutput = 'html';
        $mail->Password = 'gV.S=o=Q,bl2'; // SMTP password
        $mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465; // TCP port to connect to
        $mail->From = 'facturacion@easyfactperu.pe';
        $mail->FromName = 'EASYFACT PERÚ S.A.C.  - Reporte Caja';
        $mail->addAddress($req->correo, 'Reporte Caja'); // Add a recipient

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8'; // Set email format to HTML
        $mail->Subject = 'Reporte Detallado de Caja';
        $mail->addAttachment('reporte-caja.pdf');

        $mail->msgHTML('<div>Fecha Apertura: ' . $caja->FechaApertura . '</div><br><div>Fecha Cierre: ' . $caja->FechaCierre . '</div>');
        $enviado = $mail->send();
        if ($enviado) {
            return back()->with('status', 'Se envio correo con éxito');
        } else {
            return back()->with('error', 'No se pudo enviar correo');
        }
    }

    private function convertirFecha($fecha)
    {

        $date = date_create($fecha);
        $hora = date_format($date, 'h:i A');
        $_fecha = date_format($date, 'd/m/y');
        $carbon = Carbon::parse($fecha);
        if ($carbon->isToday()) {
            $sesion = 'Hoy';
        } else {
            if ($carbon->isYesterday()) {
                $sesion = 'Ayer';
            } else {
                if ($carbon->isMonday()) {
                    $sesion = 'Lunes';
                } else {
                    if ($carbon->isTuesday()) {
                        $sesion = 'Martes';
                    } else {
                        if ($carbon->isWednesday()) {
                            $sesion = 'Miércoles';
                        } else {
                            if ($carbon->isThursday()) {
                                $sesion = 'Jueves';
                            } else {
                                if ($carbon->isFriday()) {
                                    $sesion = 'Viernes';
                                } else {
                                    if ($carbon->isSaturday()) {
                                        $sesion = 'Sábado';
                                    } else {
                                        if ($carbon->isSunday()) {
                                            $sesion = 'Domingo';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $fechaConvertida = $hora . ', ' . $sesion . ', ' . $_fecha;

        return $fechaConvertida;
    }

}
