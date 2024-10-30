<?php

namespace App\Http\Controllers\Reportes\Gerenciales;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use App\Http\Controllers\Reportes\AjusteFechasReportesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class ReportesGananciasController extends Controller
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
        $fechasReportes = new AjusteFechasReportesController();
        $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $fecha = 5;
        $fechaIni = '';
        $fechaFin = '';

        $datosGanancias = $this->getDatosGanancias($loadDatos, $idSucursal, $fecha, $fechaIni, $fechaFin, $fechasReportes);
        $array = ['modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'permisos' => $permisos, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin];
        $array = array_merge($array, $datosGanancias);
        return view('reportes/gerenciales/reporteGanancias', $array);
    }

    public function store(Request $req)
    {
        $loadDatos = new DatosController();
        $fechasReportes = new AjusteFechasReportesController();
        $fecha = $req->fecha;
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        if ($fecha == 9) {
            if ($fechaIni == null || $fechaFin == null) {
                return back()->with('error', 'Completar las fechas para filtrar');
            }
            // if ($fechaIni > $fechaFin) {
            //     return back()->with('error', 'La fecha Inicial no puede ser mayor que la Final');
            // }
        }
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $datosGanancias = $this->getDatosGanancias($loadDatos, $idSucursal, $fecha, $fechaIni, $fechaFin, $fechasReportes);
        $array = ['modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'permisos' => $permisos, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin];
        $array = array_merge($array, $datosGanancias);

        return view('reportes/gerenciales/reporteGanancias', $array);
    }

    public function getDatosGanancias($loadDatos, $idSucursal, $fecha, $fechaIni, $fechaFin, $fechasReportes)
    {
        // Reporte de ganancias
        $reporteGanancias = $loadDatos->getGananciasFiltradoss($idSucursal, $fecha, $fechaIni, $fechaFin);
        $reporteGanancias = $fechasReportes->ajustarFechas($reporteGanancias, $fecha, $fechaIni, $fechaFin);
        $reporteGanancias = collect($reporteGanancias);
        // soles
        $gananciasSoles = $reporteGanancias->where('tipoMoneda', 1)->values();
        $gananciasSolesContado = $gananciasSoles->where('IdTipoPago', 1);
        $gananciaSolesContado = $gananciasSolesContado->pluck('Ganancia')->first();
        $precioSolesContado = $gananciasSolesContado->pluck('Precio')->first();
        $costoSolesContado = $gananciasSolesContado->pluck('Costo')->first();
        $gananciasSolesCredito = $gananciasSoles->where('IdTipoPago', 2);
        $gananciaSolesCredito = $gananciasSolesCredito->pluck('Ganancia')->first();
        $precioSolesCredito = $gananciasSolesCredito->pluck('Precio')->first();
        $costoSolesCredito = $gananciasSolesCredito->pluck('Costo')->first();
        // Dolares
        $gananciasDolares = $reporteGanancias->where('tipoMoneda', 2)->values();
        $gananciasDolaresContado = $gananciasDolares->where('IdTipoPago', 1);
        $gananciaDolaresContado = $gananciasDolaresContado->pluck('Ganancia')->first();
        $precioDolaresContado = $gananciasDolaresContado->pluck('Precio')->first();
        $costoDolaresContado = $gananciasDolaresContado->pluck('Costo')->first();
        $gananciasDolaresCredito = $gananciasDolares->where('IdTipoPago', 2);
        $gananciaDolaresCredito = $gananciasDolaresCredito->pluck('Ganancia')->first();
        $precioDolaresCredito = $gananciasDolaresCredito->pluck('Precio')->first();
        $costoDolaresCredito = $gananciasDolaresCredito->pluck('Costo')->first();

        // CODIGO OBNETER EL MONTO GASTO
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $montoGasto = DB::table('gastos')->select('monto as MontoGasto', 'IdTipoMoneda')
            ->where('IdSucursal', $idSucursal)
            ->whereBetween('FechaCreacion', [$fechas[0], $fechas[1]])
            ->get();
        $montoGastoSoles = $montoGasto->whereIn('IdTipoMoneda', [1, null])->sum('MontoGasto');
        $gananciaNetaContadoMasCredito = floatval($gananciaSolesContado) + floatval($gananciaSolesCredito) - floatval($montoGastoSoles);
        $gananciaNetaContado = floatval($gananciaSolesContado) - floatval($montoGastoSoles);
        $gananciaTotalNetoMasContado = [$gananciaNetaContado, $montoGastoSoles];
        $gananciaTotalNetoMasContadoYcredito = [$gananciaNetaContadoMasCredito, $montoGastoSoles];

        $montoGastoDolares = $montoGasto->where('IdTipoMoneda', 2)->sum('MontoGasto');
        $gananciaNetaContadoMasCreditoDolares = floatval($gananciaDolaresContado) + floatval($gananciaDolaresCredito) - floatval($montoGastoDolares);
        $gananciaNetaContadoDolares = floatval($gananciaDolaresContado) - floatval($montoGastoDolares);
        $gananciaTotalNetoMasContadoDolares = [$gananciaNetaContadoDolares, $montoGastoDolares];
        $gananciaTotalNetoMasContadoYcreditoDolares = [$gananciaNetaContadoMasCreditoDolares, $montoGastoDolares];
        // FIN

        $arrayGananciasSolesContado = [$precioSolesContado, $costoSolesContado, $gananciaSolesContado];
        $arrayGananciasSolesCredito = [$precioSolesCredito, $costoSolesCredito, $gananciaSolesCredito];

        $arrayGananciasDolaresContado = [$gananciaDolaresContado, $precioDolaresContado, $costoDolaresContado];
        $arrayGananciasDolaresCredito = [$gananciaDolaresCredito, $precioDolaresCredito, $costoDolaresCredito];

        return ['reporteGanancias' => $reporteGanancias, 'gananciaNetaContadoMasCredito' => $gananciaNetaContadoMasCredito, 'gananciaNetaContado' => $gananciaNetaContado, 'montoGastoSoles' => $montoGastoSoles, 'gananciaTotalNetoMasContado' => $gananciaTotalNetoMasContado, 'gananciaTotalNetoMasContadoYcredito' => $gananciaTotalNetoMasContadoYcredito, 'gananciaNetaContadoMasCreditoDolares' => $gananciaNetaContadoMasCreditoDolares, 'gananciaNetaContadoDolares' => $gananciaNetaContadoDolares, 'montoGastoDolares' => $montoGastoDolares, 'gananciaTotalNetoMasContadoDolares' => $gananciaTotalNetoMasContadoDolares, 'gananciaTotalNetoMasContadoYcreditoDolares' => $gananciaTotalNetoMasContadoYcreditoDolares, 'gananciaSolesContado' => $gananciaSolesContado, 'precioSolesContado' => $precioSolesContado, 'costoSolesContado' => $costoSolesContado, 'gananciaSolesCredito' => $gananciaSolesCredito, 'precioSolesCredito' => $precioSolesCredito, 'costoSolesCredito' => $costoSolesCredito, 'gananciaDolaresContado' => $gananciaDolaresContado, 'precioDolaresContado' => $precioDolaresContado, 'costoDolaresContado' => $costoDolaresContado, 'gananciaDolaresCredito' => $gananciaDolaresCredito, 'precioDolaresCredito' => $precioDolaresCredito, 'costoDolaresCredito' => $costoDolaresCredito, 'arrayGananciasSolesContado' => $arrayGananciasSolesContado, 'arrayGananciasSolesCredito' => $arrayGananciasSolesCredito, 'arrayGananciasDolaresContado' => $arrayGananciasDolaresContado, 'arrayGananciasDolaresCredito' => $arrayGananciasDolaresCredito];
    }
}
