<?php

namespace App\Http\Controllers\Reportes\Gerenciales;

use App\Exports\ExcelReporteFinesDia;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DateTime;
use DB;
use Excel;
use Illuminate\Http\Request;
use PDF;
use Session;

class ReportesFinesDeDiaController extends Controller
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
        $vendedores = $loadDatos->getVendedores($idSucursal);
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $fechaHoyInicio = Carbon::today();
        $inputvendedor = 0;
        $fecha = 5;
        $fechaIni = '';
        $fechaFin = '';
        $idTipoMoneda = 1;
        //$ventasCaja = $loadDatos->getVentasCajas($idSucursal, $fechaHoyInicio);
        //dd($ventasCaja);
        $fechas = $loadDatos->getFechaFiltro(5, null, null);
        // $ventasCaja =$loadDatos->getVentasCajasFiltrado($idSucursal, $vendedor, $fechas[0], $fechas[1]);
        $resultado = DB::select('call sp_getGerencialesVentasCajasFiltrado(?, ?, ?, ?)', array($idSucursal, $inputvendedor, $fechas[0], $fechas[1]));
        //dd($resultado);
        $ventasCaja = $loadDatos->getVentasCajasFiltrado($resultado, $idTipoMoneda);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['vendedores' => $vendedores, 'permisos' => $permisos, 'ventasCaja' => $ventasCaja, 'inputvendedor' => $inputvendedor, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'idTipoMoneda' => $idTipoMoneda, 'dateIni' => $fechaIni, 'dateFin' => $fechaFin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('reportes/gerenciales/reporteFinesDeDia', $array);
    }

    public function store(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $fecha = $req->fecha;
        $inputvendedor = $req->vendedor;
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        $idTipoMoneda = $req->tipoMoneda;

        if ($fecha == 9) {
            if ($fechaIni == null || $fechaFin == null) {
                return back()->with('error', 'Completar las fechas para filtrar');
            }
            if (Carbon::createFromFormat('d/m/Y', $fechaIni) > Carbon::createFromFormat('d/m/Y', $fechaFin)) {
                return back()->with('error', 'La fecha Inicial no puede ser mayor que la Final');
            }
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $vendedores = $loadDatos->getVendedores($idSucursal);
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $ini = str_replace('/', '-', $fechaIni);
        $fin = str_replace('/', '-', $fechaFin);

        //$fechaIni = DateTime::createFromFormat('d/m/Y',$fechaIni);
        //dd($fechaIni);
        /*$fechaConvertidaInicio = $fechaIni->format("Y-m-d");
        $fechaFin = strtotime('+1 day',strtotime($fechaConvertidaInicio));
        $fechaConvertidaFinal = date('Y-m-d',$fechaFin);*/

        //$ventasCaja = $loadDatos->getVentasCajasFiltrado($idSucursal, $vendedor, $fechaConvertidaInicio, $fechaConvertidaFinal);

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);

        // $ventasCaja = $loadDatos->getVentasCajasFiltrado($idSucursal, $vendedor, $fechas[0], $fechas[1]);
        // CODIGO DEL PROCEDIMIENTO ALMACENADO
        $resultado = DB::select('call sp_getGerencialesVentasCajasFiltrado(?, ?, ?, ?)', array($idSucursal, $inputvendedor, $fechas[0], $fechas[1]));

        $ventasCaja = $loadDatos->getVentasCajasFiltrado($resultado, $idTipoMoneda);
        //FIN

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['vendedores' => $vendedores, 'permisos' => $permisos, 'ventasCaja' => $ventasCaja, 'inputvendedor' => $inputvendedor, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'idTipoMoneda' => $idTipoMoneda, 'dateIni' => $ini, 'dateFin' => $fin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('reportes/gerenciales/reporteFinesDeDia', $array);
    }

    //  Se agrego la funcion exportExcel
    public function exportExcel($vendedor = null, $idTipoMoneda = null, $fecha = null, $ini = null, $fin = null)
    {
        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');
        $idSucursal = Session::get('idSucursal');
        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);

        if ($vendedor == null) {
            $vendedor = '0';

        }
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);

        // $reporteFinesDia  =$loadDatos->getVentasCajasFiltrado($idSucursal, $vendedor, $fechas[0], $fechas[1]);
        $resultado = DB::select('call sp_getGerencialesVentasCajasFiltrado(?, ?, ?, ?)', array($idSucursal, $vendedor, $fechas[0], $fechas[1]));

        $reporteFinesDia = $loadDatos->getVentasCajasFiltrado($resultado, $idTipoMoneda);
        // dd($reporteFinesDia);
        return Excel::download(new ExcelReporteFinesDia($reporteFinesDia, $idTipoMoneda), 'Reporte Gerencial - Fines-De-Dia.xlsx');

    }

    // =============== NUEVO CODIGO PARA IMPRIMIR DETALLE DE CAJA =============== \\

    public function imprimirDetalleCaja(Request $req)
    {
        $idSucursal = Session::get('idSucursal');
        $idCaja = $req->idCaja;
        $tipoImpresion = $req->selectImpre;
        $pdf = $this->generarPDF($tipoImpresion, $idCaja);
        return $pdf->stream('caja.pdf');
    }

    private function generarPDF($tipo, $idCaja)
    {
        $array = $this->detalleCaja($idCaja);
        view()->share($array);

        if ($tipo == 1) {
            $pdf = PDF::loadView('cajaPDF')->setPaper('a4', 'portrait');
        } else {
            $pdf = PDF::loadView('cajaTicket')->setPaper(array(0, 0, 107, 600));
        }

        return $pdf;
    }

    public function getCaja($idSucursal, $idCaja)
    {
        try {
            $resultado = DB::table('caja')
                ->join('usuario', 'caja.IdUsuario', '=', 'usuario.IdUsuario')
                ->select('caja.*', 'usuario.Nombre as NombreUsuario')
                ->where('caja.IdSucursal', $idSucursal)
                ->where('caja.IdCaja', $idCaja)
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function detalleCaja($idCaja)
    {
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');

        $caja = $this->getCaja($idSucursal, $idCaja);
        $idUsuario = $caja->IdUsuario;
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $turno = $caja->NombreUsuario;
        $ventasAperturaCierreCaja = $this->getVentasAperturaCierreCaja($idSucursal, $idUsuario, $caja->FechaApertura, $caja->FechaCierre);
        for ($i = 0; $i < count($ventasAperturaCierreCaja); $i++) {
            $_productos = $loadDatos->getItemsVentas($ventasAperturaCierreCaja[$i]->IdVentas);
            $ventasAperturaCierreCaja[$i]->Productos = $_productos;
        }
        $ventasContadoSoles = $this->getDetalleCaja($idSucursal, $idUsuario, $caja->FechaApertura, $caja->FechaCierre, 1, 1);
        $ventasContadoDolares = $this->getDetalleCaja($idSucursal, $idUsuario, $caja->FechaApertura, $caja->FechaCierre, 1, 2);
        $cobranzasSoles = $this->getDetalleCajaCobranzas($idSucursal, $idUsuario, $caja->FechaApertura, $caja->FechaCierre, 1);
        $cobranzasDolares = $this->getDetalleCajaCobranzas($idSucursal, $idUsuario, $caja->FechaApertura, $caja->FechaCierre, 2);
        $ventasContadoTotalSoles = $this->getDetalleCajaContado($idSucursal, $idUsuario, $caja->FechaApertura, $caja->FechaCierre, 1);
        $ventasContadoTotalDolares = $this->getDetalleCajaContado($idSucursal, $idUsuario, $caja->FechaApertura, $caja->FechaCierre, 2);

        $estado = $caja->Estado;
        $inicialSoles = $caja->Inicial;
        $inicialDolares = $caja->InicialDolares;
        $ingresosSol = $this->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, $caja->FechaCierre, 'I', 1);
        $ingresosDol = $this->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, $caja->FechaCierre, 'I', 2);

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
        $egresosSol = $this->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, $caja->FechaCierre, 'E', 1);
        $egresosDol = $this->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, $caja->FechaCierre, 'E', 2);

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
        $amortizacionSoles = $this->getAmortizacionesTotales($idSucursal, $idUsuario, $caja->FechaApertura, $caja->FechaCierre, 1);
        $amortizacionDolares = $this->getAmortizacionesTotales($idSucursal, $idUsuario, $caja->FechaApertura, $caja->FechaCierre, 2);

        $totalAmortizacionSoles = $amortizacionSoles->sum('Monto');
        $totalAmortizacionDolares = $amortizacionDolares->sum('Monto');
        $amortizacionEfectivoSoles = $amortizacionSoles->where('FormaPago', 1)->sum('Monto');
        $amortizacionEfectivoDolares = $amortizacionDolares->where('FormaPago', 1)->sum('Monto');
        $amortizacionTarjetaSoles = $amortizacionSoles->where('FormaPago', 2)->sum('Monto');
        $amortizacionTarjetaDolares = $amortizacionDolares->where('FormaPago', 2)->sum('Monto');
        $amortizacionCuentaBancariaSoles = $amortizacionSoles->where('FormaPago', 3)->sum('Monto');
        $amortizacionCuentaBancariaDolares = $amortizacionDolares->where('FormaPago', 3)->sum('Monto');
        $cajaTotalSoles = floatval($cobranzasEfectivoSoles) + floatval($ventasContadoEfectivoSoles) + floatval($amortizacionEfectivoSoles) + floatval($inicialSoles) + floatval($montoIngresosSoles) - floatval($montoEgresosSoles);
        $cajaTotalDolares = floatval($cobranzasEfectivoDolares) + floatval($ventasContadoEfectivoDolares) + floatval($totalAmortizacionDolares) + floatval($inicialDolares) + floatval($montoIngresosDolares) - floatval($montoEgresosDolares);

        $cajaTotalSoles = sprintf("%01.2f", $cajaTotalSoles);
        $cajaTotalDolares = sprintf("%01.2f", $cajaTotalDolares);
        $array = ['totalVentasContadoSoles' => $totalVentasContadoSoles, 'totalVentasContadoDolares' => $totalVentasContadoDolares, 'totalCobranzasSoles' => $totalCobranzasSoles, 'totalCobranzasDolares' => $totalCobranzasDolares, 'cobranzasEfectivoSoles' => $cobranzasEfectivoSoles, 'cobranzasTarjetaSoles' => $cobranzasTarjetaSoles, 'cobranzasEfectivoDolares' => $cobranzasEfectivoDolares, 'cobranzasTarjetaDolares' => $cobranzasTarjetaDolares, 'cobranzasCuentaBancariaSoles' => $cobranzasCuentaBancariaSoles, 'cobranzasCuentaBancariaDolares' => $cobranzasCuentaBancariaDolares, 'ventasContadoEfectivoSoles' => $ventasContadoEfectivoSoles,
            'ventasContadoCuentaBancariaSoles' => $ventasContadoCuentaBancariaSoles, 'ventasContadoCuentaBancariaDolares' => $ventasContadoCuentaBancariaDolares, 'totalAmortizacionSoles' => $totalAmortizacionSoles, 'totalAmortizacionDolares' => $totalAmortizacionDolares, 'amortizacionEfectivoSoles' => $amortizacionEfectivoSoles, 'amortizacionEfectivoDolares' => $amortizacionEfectivoDolares, 'amortizacionTarjetaSoles' => $amortizacionTarjetaSoles, 'amortizacionTarjetaDolares' => $amortizacionTarjetaDolares, 'amortizacionCuentaBancariaSoles' => $amortizacionCuentaBancariaSoles, 'amortizacionCuentaBancariaDolares' => $amortizacionCuentaBancariaDolares,
            'ventasContadoTarjetaSoles' => $ventasContadoTarjetaSoles, 'ventasContadoEfectivoDolares' => $ventasContadoEfectivoDolares, 'ventasContadoTarjetaDolares' => $ventasContadoTarjetaDolares, 'cajaTotalSoles' => $cajaTotalSoles, 'cajaTotalDolares' => $cajaTotalDolares, 'inicialSoles' => $inicialSoles, 'inicialDolares' => $inicialDolares, 'ultimoSesion' => $ultimoSesion, 'montoIngresosSoles' => $montoIngresosSoles, 'montoEgresosSoles' => $montoEgresosSoles, 'montoIngresosDolares' => $montoIngresosDolares, 'montoEgresosDolares' => $montoEgresosDolares, 'estado' => $estado, 'turno' => $turno, 'ventasAperturaCierreCaja' => $ventasAperturaCierreCaja, 'idCaja' => $idCaja, 'subniveles' => $subniveles];

        return $array;
    }

    public function getVentasAperturaCierreCaja($idSucursal, $idUsuario, $fechaApertura, $fechaCierre)
    {
        try {
            $ventas = DB::table('ventas')
                ->select('ventas.*')
                ->whereBetween('ventas.FechaCreacion', [$fechaApertura, $fechaCierre])
                ->where('ventas.IdSucursal', '=', $idSucursal)
                ->where('ventas.IdCreacion', '=', $idUsuario)
                ->get();

            return $ventas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getDetalleCaja($idSucursal, $idUsuario, $fechaApertura, $fechaCierre, $tipo, $tipoMoneda)
    {
        try {
            $resultado = DB::table('ventas')
                ->select(DB::raw("SUM(Total) as ImporteTotal"))
                ->whereBetween('ventas.FechaCreacion', [$fechaApertura, $fechaCierre])
                ->where('IdSucursal', $idSucursal)
                ->where('IdCreacion', $idUsuario)
                ->where('IdTipoPago', $tipo)
                ->where('IdTipoMoneda', $tipoMoneda)
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getDetalleCajaCobranzas($idSucursal, $idUsuario, $fechaApertura, $fechaCierre, $tipoMoneda)
    {
        try {
            $resultado = DB::table('ventas')
                ->join('fecha_pago', 'ventas.IdVentas', '=', 'fecha_pago.IdVenta')
                ->join('pagos_detalle', 'fecha_pago.IdFechaPago', '=', 'pagos_detalle.IdFechaPago')
                ->select(DB::raw("SUM(pagos_detalle.Efectivo) as Efectivo"), DB::raw("SUM(pagos_detalle.Tarjeta) as Tarjeta"), DB::raw("SUM(pagos_detalle.CuentaBancaria) as CuentaBancaria"), DB::raw("(SUM(pagos_detalle.Efectivo) + SUM(pagos_detalle.Tarjeta) + SUM(pagos_detalle.CuentaBancaria)) as TotalCobranza"))
                ->whereBetween('pagos_detalle.FechaPago', [$fechaApertura, $fechaCierre])
                ->where('IdSucursal', $idSucursal)
                ->where('pagos_detalle.IdUsuario', $idUsuario)
                ->where('IdTipoMoneda', $tipoMoneda)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getDetalleCajaContado($idSucursal, $idUsuario, $fechaApertura, $fechaCierre, $tipoMoneda)
    {
        try {
            $resultado = DB::table('ventas')
                ->select(DB::raw("SUM(MontoEfectivo) as Efectivo"), DB::raw("SUM(MontoTarjeta) as Tarjeta"), DB::raw("SUM(MontoCuentaBancaria) as CuentaBancaria"), DB::raw("(SUM(MontoEfectivo) + SUM(MontoTarjeta)) as CajaTotal"))
                ->whereBetween('FechaCreacion', [$fechaApertura, $fechaCierre])
                ->where('IdSucursal', $idSucursal)
                ->where('IdCreacion', $idUsuario)
                ->where('IdTipoMoneda', $tipoMoneda)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getTotalIngresosEgresos($idSucursal, $idUsuario, $fechaApertura, $fechaCierre, $tipo, $tipoMoneda)
    {
        try {
            $resultado = DB::table('ingresoegreso')
                ->join('caja', 'ingresoegreso.IdCaja', '=', 'caja.IdCaja')
                ->join('usuario', 'caja.IdUsuario', '=', 'usuario.IdUsuario')
                ->select(DB::raw("SUM(ingresoegreso.Monto) as Monto"))
                ->where('caja.IdSucursal', $idSucursal)
                ->where('caja.IdUsuario', $idUsuario)
                ->where('ingresoegreso.Tipo', $tipo)
                ->where('ingresoegreso.IdTipoMoneda', $tipoMoneda)
                ->whereBetween('caja.FechaApertura', [$fechaApertura, $fechaCierre])
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getAmortizacionesTotales($idSucursal, $idUsuario, $fechaApertura, $fechaCierre, $tipoMoneda)
    {
        try {
            $amortizaciones = DB::table('amortizacion')
                ->where('IdSucursal', $idSucursal)
                ->where('IdUsuario', $idUsuario)
                ->where('IdTipoMoneda', $tipoMoneda)
                ->whereBetween('FechaIngreso', [$fechaApertura, $fechaCierre])
                ->get();
            return $amortizaciones;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // =================================== FIN ===================================== \\

    // public function getVentasAperturaCierreCaja($idCaja)
    // {
    //     try {
    //         $ventas = DB::table('caja_ventas')
    //             ->select('IdCaja', 'IdVentas')
    //             ->where('IdCaja', $idCaja)
    //             ->whereIn('IdVentas', function ($query) {
    //                 $query->select('IdVentas')->from('ventas');})
    //             ->get();
    //         return $ventas;
    //     } catch (Exception $ex) {
    //         echo $ex->getMessage();
    //     }
    // }
}
