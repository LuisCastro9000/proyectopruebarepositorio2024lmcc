<?php

namespace App\Http\Controllers\Reportes\Financieros;

use App\Exports\ExcelReporteFinancieroGastos;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DateTime;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class ReporteGastosController extends Controller
{
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {

            $idUsuario = Session::get('idUsuario');
            $arrayTipo = [];
            $arrayGastos = [];

            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $fechas = $loadDatos->getFechaFiltro(5, null, null);

            $reporteFinancieroGastos = DB::select('call sp_getFinancierosGastos(?, ?, ?, ?, ?)', array($idSucursal, 0, $tipoMoneda = 1, $fechas[0], $fechas[1]));
            $reporteFinancieroGastos = collect($reporteFinancieroGastos);

            $sumatoriaTotalGastosFijos = $reporteFinancieroGastos->where('TipoGasto', 1)->pluck('Monto')->sum();
            $sumatoriaTotalGastosVariables = $reporteFinancieroGastos->where('TipoGasto', 2)->pluck('Monto')->sum();
            $sumatoriaGastosFijosVariables = $sumatoriaTotalGastosFijos + $sumatoriaTotalGastosVariables;

            // Codigo para obtener montos de los gastos fijos y variables
            $gastosFijos = $reporteFinancieroGastos->filter(function ($value, $key) {
                return $value->TipoGasto == 1 && ($value->IdListaGastos <= 13 || $value->IdListaGastos >= 20) && $value->IdSucursal == 0;
            });
            $otrosGastosFijos = $reporteFinancieroGastos->filter(function ($value, $key) {
                return $value->TipoGasto == 1 && $value->IdListaGastos >= 14 && $value->IdListaGastos < 20 && $value->IdSucursal >= 0;
            });
            $gastosVariables = $reporteFinancieroGastos->filter(function ($value, $key) {
                return $value->TipoGasto == 2 && ($value->IdListaGastos <= 13 || $value->IdListaGastos >= 22) && $value->IdSucursal == 0;
            });
            $otrosGastosVariables = $reporteFinancieroGastos->filter(function ($value, $key) {
                return $value->TipoGasto == 2 && $value->IdListaGastos >= 14 && $value->IdListaGastos < 22 && $value->IdSucursal >= 0;
            });

            $gastoAlquilerFijo = $gastosFijos->where('IdListaGastos', 1)->pluck('Descripcion')->unique();
            $gastoLuzFijo = $gastosFijos->where('IdListaGastos', 2)->pluck('Descripcion')->unique();
            $gastoAguaFijo = $gastosFijos->where('IdListaGastos', 3)->pluck('Descripcion')->unique();
            $gastoInternetFijo = $gastosFijos->where('IdListaGastos', 4)->pluck('Descripcion')->unique();
            $gastoCelular = $gastosFijos->where('IdListaGastos', 5)->pluck('Descripcion')->unique();
            $gastoMaterialesDeOficina = $gastosFijos->where('IdListaGastos', 6)->pluck('Descripcion')->unique();
            $gastoContabilidadFijo = $gastosFijos->where('IdListaGastos', 7)->pluck('Descripcion')->unique();
            $gastoSalarioFijo = $gastosFijos->where('IdListaGastos', 8)->pluck('Descripcion')->unique();
            $gastoBancosFijo = $gastosFijos->where('IdListaGastos', 20)->pluck('Descripcion')->unique();
            $gastoMarketingFijo = $gastosFijos->where('IdListaGastos', 21)->pluck('Descripcion')->unique();

            $totalGastoAlquilerFijo = $gastosFijos->where('IdListaGastos', 1)->pluck('Monto')->sum();
            $totalGastoLuzFijo = $gastosFijos->where('IdListaGastos', 2)->pluck('Monto')->sum();
            $totalGastoAguaFijo = $gastosFijos->where('IdListaGastos', 3)->pluck('Monto')->sum();
            $totalGastoInternetFijo = $gastosFijos->where('IdListaGastos', 4)->pluck('Monto')->sum();
            $totalGastoCelular = $gastosFijos->where('IdListaGastos', 5)->pluck('Monto')->sum();
            $totalGastoMaterialesDeOficina = $gastosFijos->where('IdListaGastos', 6)->pluck('Monto')->sum();
            $totalGastoContabilidadFijo = $gastosFijos->where('IdListaGastos', 7)->pluck('Monto')->sum();
            $totalGastoSalarioFijo = $gastosFijos->where('IdListaGastos', 8)->pluck('Monto')->sum();
            $totalGastoBancosFijo = $gastosFijos->where('IdListaGastos', 20)->pluck('Monto')->sum();
            $totalGastoMarketingFijo = $gastosFijos->where('IdListaGastos', 21)->pluck('Monto')->sum();
            $totalGastosOtrosFijos = $otrosGastosFijos->pluck('Monto')->sum();
            if ($totalGastosOtrosFijos != "") {
                $nombreOtrosGastosFijos = "Otros Gastos";
            } else {
                $nombreOtrosGastosFijos = "";
            }

            $gastoComisionesVariables = $gastosVariables->where('IdListaGastos', 9)->pluck('Descripcion')->unique();
            $gastoImpuestoVariable = $gastosVariables->where('IdListaGastos', 10)->pluck('Descripcion')->unique();
            $gastoCombustibleVariable = $gastosVariables->where('IdListaGastos', 11)->pluck('Descripcion')->unique();
            $gastoMovilidadVariable = $gastosVariables->where('IdListaGastos', 12)->pluck('Descripcion')->unique();
            $gastoProveedoresVariable = $gastosVariables->where('IdListaGastos', 13)->pluck('Descripcion')->unique();
            $gastoViaticoVariable = $gastosVariables->where('IdListaGastos', 22)->pluck('Descripcion')->unique();
            $gastoMiscelaneoVariable = $gastosVariables->where('IdListaGastos', 23)->pluck('Descripcion')->unique();

            $totalGastoComisionesVariables = $gastosVariables->where('IdListaGastos', 9)->pluck('Monto')->sum();
            $totalGastoImpuestoVariable = $gastosVariables->where('IdListaGastos', 10)->pluck('Monto')->sum();
            $totalGastoCombustibleVariable = $gastosVariables->where('IdListaGastos', 11)->pluck('Monto')->sum();
            $totalGastoMovilidadVariable = $gastosVariables->where('IdListaGastos', 12)->pluck('Monto')->sum();
            $totalGastoProveedoresVariable = $gastosVariables->where('IdListaGastos', 13)->pluck('Monto')->sum();
            $totalGastoViaticoVariable = $gastosVariables->where('IdListaGastos', 22)->pluck('Monto')->sum();
            $totalGastoMiscelaneoVariable = $gastosVariables->where('IdListaGastos', 23)->pluck('Monto')->sum();
            $totalGastosOtrosVariable = $otrosGastosVariables->pluck('Monto')->sum();
            $nombreOtrosGastosVariables = "";
            if ($totalGastosOtrosFijos != "") {
                $nombreOtrosGastosVariables = "Otros Gastos";
            } else {
                $nombreOtrosGastosVariables = "";
            }
            // Fin

            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $tipoGasto = 0;
            $tipo = '';
            $fecha = 5;
            $fechaIni = $fechas[0];
            $fechaFin = $fechas[1];

            $reporteGastos = $this->reporteGastos($tipoGasto, $idSucursal, $fechas);
            if (count($reporteGastos) > 0) {
                $i = 0;
                foreach ($reporteGastos as $repGastos) {
                    if ($repGastos->TipoGasto == 1) {
                        $arrayTipo[$i] = "'Fijo'";
                    } else {
                        $arrayTipo[$i] = "'Variable'";
                    }
                    $arrayGastos[$i] = $repGastos->total;
                    $i++;
                }
            }

            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

            $array = ['graftipo' => $arrayTipo, 'grafTotal' => $arrayGastos, 'tipoGasto' => $tipoGasto, 'reporteFinancieroGastos' => $reporteFinancieroGastos, 'tipo' => $tipo, 'fecha' => $fecha, 'ini' => '0', 'fin' => '0', 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'sumatoriaGastosFijosVariables' => $sumatoriaGastosFijosVariables, 'sumatoriaTotalGastosFijos' => $sumatoriaTotalGastosFijos, 'sumatoriaTotalGastosVariables' => $sumatoriaTotalGastosVariables,
                'totalGastoAlquilerFijo' => $totalGastoAlquilerFijo, 'totalGastoLuzFijo' => $totalGastoLuzFijo, 'totalGastoAguaFijo' => $totalGastoAguaFijo, 'totalGastoInternetFijo' => $totalGastoInternetFijo, 'totalGastoCelular' => $totalGastoCelular, 'totalGastoMaterialesDeOficina' => $totalGastoMaterialesDeOficina, 'totalGastoContabilidadFijo' => $totalGastoContabilidadFijo, 'totalGastoSalarioFijo' => $totalGastoSalarioFijo, 'totalGastoBancosFijo' => $totalGastoBancosFijo, 'totalGastoMarketingFijo' => $totalGastoMarketingFijo, 'totalGastosOtrosFijos' => $totalGastosOtrosFijos,
                'totalGastoComisionesVariables' => $totalGastoComisionesVariables, 'totalGastoImpuestoVariable' => $totalGastoImpuestoVariable,
                'totalGastoCombustibleVariable' => $totalGastoCombustibleVariable, 'totalGastoMovilidadVariable' => $totalGastoMovilidadVariable,
                'totalGastoProveedoresVariable' => $totalGastoProveedoresVariable, 'totalGastoViaticoVariable' => $totalGastoViaticoVariable, 'totalGastoMiscelaneoVariable' => $totalGastoMiscelaneoVariable, 'totalGastosOtrosVariable' => $totalGastosOtrosVariable,
                'gastoAlquilerFijo' => $gastoAlquilerFijo, 'gastoLuzFijo' => $gastoLuzFijo, 'gastoAguaFijo' => $gastoAguaFijo, 'gastoInternetFijo' => $gastoInternetFijo, 'gastoCelular' => $gastoCelular, 'gastoMaterialesDeOficina' => $gastoMaterialesDeOficina, 'gastoContabilidadFijo' => $gastoContabilidadFijo, 'gastoSalarioFijo' => $gastoSalarioFijo, 'gastoBancosFijo' => $gastoBancosFijo, 'gastoMarketingFijo' => $gastoMarketingFijo, 'nombreOtrosGastosFijos' => $nombreOtrosGastosFijos,
                'gastoComisionesVariables' => $gastoComisionesVariables, 'gastoImpuestoVariable' => $gastoImpuestoVariable, 'gastoCombustibleVariable' => $gastoCombustibleVariable, 'gastoMovilidadVariable' => $gastoMovilidadVariable,
                'gastoProveedoresVariable' => $gastoProveedoresVariable, 'gastoViaticoVariable' => $gastoViaticoVariable, 'gastoMiscelaneoVariable' => $gastoMiscelaneoVariable, 'nombreOtrosGastosVariables' => $nombreOtrosGastosVariables, 'gastosVariables' => $gastosVariables, 'gastosFijos' => $gastosFijos, 'tipoMoneda' => 1];
            return view('reportes/financieros/reporteGastos', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function store(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        $arrayTipo = [];
        $arrayGastos = [];

        $loadDatos = new DatosController();
        $tipoGasto = $req->tipoGasto;

        $fecha = $req->fecha;
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        $tipo = 1;

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

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);

        $reporteFinancieroGastos = DB::select('call sp_getFinancierosGastos(?, ?, ?, ?, ?)', array($idSucursal, $tipoGasto, $req->tipoMoneda, $fechas[0], $fechas[1]));
        $reporteFinancieroGastos = collect($reporteFinancieroGastos);
        $sumatoriaTotalGastosFijos = $reporteFinancieroGastos->where('TipoGasto', 1)->pluck('Monto')->sum();
        $sumatoriaTotalGastosVariables = $reporteFinancieroGastos->where('TipoGasto', 2)->pluck('Monto')->sum();
        $sumatoriaGastosFijosVariables = $sumatoriaTotalGastosFijos + $sumatoriaTotalGastosVariables;

        // Codigo para obtener montos de los gastos fijos y variables
        $gastosFijos = $reporteFinancieroGastos->filter(function ($value, $key) {
            return $value->TipoGasto == 1 && ($value->IdListaGastos <= 13 || $value->IdListaGastos >= 20) && $value->IdSucursal >= 0;
        });
        $otrosGastosFijos = $reporteFinancieroGastos->filter(function ($value, $key) {
            return $value->TipoGasto == 1 && $value->IdListaGastos >= 14 && $value->IdListaGastos < 20 && $value->IdSucursal >= 0;
        });
        $gastosVariables = $reporteFinancieroGastos->filter(function ($value, $key) {
            return $value->TipoGasto == 2 && ($value->IdListaGastos <= 13 || $value->IdListaGastos >= 22) && $value->IdSucursal >= 0;
        });
        $otrosGastosVariables = $reporteFinancieroGastos->filter(function ($value, $key) {
            return $value->TipoGasto == 2 && $value->IdListaGastos >= 14 && $value->IdListaGastos < 22 && $value->IdSucursal >= 0;
        });

        //dd($otrosGastosVariables);
        $gastoAlquilerFijo = $gastosFijos->where('IdListaGastos', 1)->pluck('Descripcion')->unique();
        $gastoLuzFijo = $gastosFijos->where('IdListaGastos', 2)->pluck('Descripcion')->unique();
        $gastoAguaFijo = $gastosFijos->where('IdListaGastos', 3)->pluck('Descripcion')->unique();
        $gastoInternetFijo = $gastosFijos->where('IdListaGastos', 4)->pluck('Descripcion')->unique();
        $gastoCelular = $gastosFijos->where('IdListaGastos', 5)->pluck('Descripcion')->unique();
        $gastoMaterialesDeOficina = $gastosFijos->where('IdListaGastos', 6)->pluck('Descripcion')->unique();
        $gastoContabilidadFijo = $gastosFijos->where('IdListaGastos', 7)->pluck('Descripcion')->unique();
        $gastoSalarioFijo = $gastosFijos->where('IdListaGastos', 8)->pluck('Descripcion')->unique();
        $gastoBancosFijo = $gastosFijos->where('IdListaGastos', 20)->pluck('Descripcion')->unique();
        $gastoMarketingFijo = $gastosFijos->where('IdListaGastos', 21)->pluck('Descripcion')->unique();

        $totalGastoAlquilerFijo = $gastosFijos->where('IdListaGastos', 1)->pluck('Monto')->sum();
        $totalGastoLuzFijo = $gastosFijos->where('IdListaGastos', 2)->pluck('Monto')->sum();
        $totalGastoAguaFijo = $gastosFijos->where('IdListaGastos', 3)->pluck('Monto')->sum();
        $totalGastoInternetFijo = $gastosFijos->where('IdListaGastos', 4)->pluck('Monto')->sum();
        $totalGastoCelular = $gastosFijos->where('IdListaGastos', 5)->pluck('Monto')->sum();
        $totalGastoMaterialesDeOficina = $gastosFijos->where('IdListaGastos', 6)->pluck('Monto')->sum();
        $totalGastoContabilidadFijo = $gastosFijos->where('IdListaGastos', 7)->pluck('Monto')->sum();
        $totalGastoSalarioFijo = $gastosFijos->where('IdListaGastos', 8)->pluck('Monto')->sum();
        $totalGastoBancosFijo = $gastosFijos->where('IdListaGastos', 20)->pluck('Monto')->sum();
        $totalGastoMarketingFijo = $gastosFijos->where('IdListaGastos', 21)->pluck('Monto')->sum();
        $totalGastosOtrosFijos = $otrosGastosFijos->pluck('Monto')->sum();
        $nombreOtrosGastosFijos = "";
        if ($totalGastosOtrosFijos != "") {
            $nombreOtrosGastosFijos = "Otros Gastos";
        } else {
            $nombreOtrosGastosFijos = "";
        }

        $gastoComisionesVariables = $gastosVariables->where('IdListaGastos', 9)->pluck('Descripcion')->unique();
        $gastoImpuestoVariable = $gastosVariables->where('IdListaGastos', 10)->pluck('Descripcion')->unique();
        $gastoCombustibleVariable = $gastosVariables->where('IdListaGastos', 11)->pluck('Descripcion')->unique();
        $gastoMovilidadVariable = $gastosVariables->where('IdListaGastos', 12)->pluck('Descripcion')->unique();
        $gastoProveedoresVariable = $gastosVariables->where('IdListaGastos', 13)->pluck('Descripcion')->unique();
        $gastoViaticoVariable = $gastosVariables->where('IdListaGastos', 22)->pluck('Descripcion')->unique();
        $gastoMiscelaneoVariable = $gastosVariables->where('IdListaGastos', 23)->pluck('Descripcion')->unique();

        $totalGastoComisionesVariables = $gastosVariables->where('IdListaGastos', 9)->pluck('Monto')->sum();
        $totalGastoImpuestoVariable = $gastosVariables->where('IdListaGastos', 10)->pluck('Monto')->sum();
        $totalGastoCombustibleVariable = $gastosVariables->where('IdListaGastos', 11)->pluck('Monto')->sum();
        $totalGastoMovilidadVariable = $gastosVariables->where('IdListaGastos', 12)->pluck('Monto')->sum();
        $totalGastoProveedoresVariable = $gastosVariables->where('IdListaGastos', 13)->pluck('Monto')->sum();
        $totalGastoViaticoVariable = $gastosVariables->where('IdListaGastos', 22)->pluck('Monto')->sum();
        $totalGastoMiscelaneoVariable = $gastosVariables->where('IdListaGastos', 23)->pluck('Monto')->sum();
        $totalGastosOtrosVariable = $otrosGastosVariables->pluck('Monto')->sum();
        $nombreOtrosGastosVariables = "";
        if ($totalGastosOtrosVariable != "") {
            $nombreOtrosGastosVariables = "Otros Gastos";
        } else {
            $nombreOtrosGastosVariables = "";
        }
        // Fin

        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        if ($fechaIni == null && $fechaFin == null) {
            $fechaIni = $fechas[0];
            $fechaFin = $fechas[1];
        }

        $ini = str_replace('/', '-', $fechaIni);
        $fin = str_replace('/', '-', $fechaFin);

        $reporteGastos = $this->reporteGastos($tipoGasto, $idSucursal, $fechas);

        if (count($reporteGastos) >= 1) {
            $i = 0;
            foreach ($reporteGastos as $repGastos) {
                if ($repGastos->TipoGasto == 1) {
                    $arrayTipo[$i] = "'Fijo'";
                } else {
                    $arrayTipo[$i] = "'Variable'";
                }
                $arrayGastos[$i] = $repGastos->total;
                $i++;
            }
        }

        $array = ['graftipo' => $arrayTipo, 'grafTotal' => $arrayGastos, 'reporteFinancieroGastos' => $reporteFinancieroGastos, 'tipoGasto' => $tipoGasto, 'tipo' => $tipo, 'fecha' => $fecha, 'ini' => $ini, 'fin' => $fin, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'sumatoriaGastosFijosVariables' => $sumatoriaGastosFijosVariables, 'sumatoriaTotalGastosFijos' => $sumatoriaTotalGastosFijos, 'sumatoriaTotalGastosVariables' => $sumatoriaTotalGastosVariables,
            'totalGastoAlquilerFijo' => $totalGastoAlquilerFijo, 'totalGastoLuzFijo' => $totalGastoLuzFijo, 'totalGastoAguaFijo' => $totalGastoAguaFijo, 'totalGastoInternetFijo' => $totalGastoInternetFijo, 'totalGastoCelular' => $totalGastoCelular, 'totalGastoMaterialesDeOficina' => $totalGastoMaterialesDeOficina, 'totalGastoContabilidadFijo' => $totalGastoContabilidadFijo, 'totalGastoSalarioFijo' => $totalGastoSalarioFijo, 'totalGastoBancosFijo' => $totalGastoBancosFijo, 'totalGastoMarketingFijo' => $totalGastoMarketingFijo, 'totalGastosOtrosFijos' => $totalGastosOtrosFijos,
            'totalGastoComisionesVariables' => $totalGastoComisionesVariables, 'totalGastoImpuestoVariable' => $totalGastoImpuestoVariable,
            'totalGastoCombustibleVariable' => $totalGastoCombustibleVariable, 'totalGastoMovilidadVariable' => $totalGastoMovilidadVariable,
            'totalGastoProveedoresVariable' => $totalGastoProveedoresVariable, 'totalGastoViaticoVariable' => $totalGastoViaticoVariable, 'totalGastoMiscelaneoVariable' => $totalGastoMiscelaneoVariable, 'totalGastosOtrosVariable' => $totalGastosOtrosVariable, 'gastoAlquilerFijo' => $gastoAlquilerFijo, 'gastoLuzFijo' => $gastoLuzFijo, 'gastoAguaFijo' => $gastoAguaFijo, 'gastoInternetFijo' => $gastoInternetFijo, 'gastoCelular' => $gastoCelular, 'gastoMaterialesDeOficina' => $gastoMaterialesDeOficina, 'gastoContabilidadFijo' => $gastoContabilidadFijo, 'gastoSalarioFijo' => $gastoSalarioFijo, 'gastoBancosFijo' => $gastoBancosFijo, 'gastoMarketingFijo' => $gastoMarketingFijo, 'nombreOtrosGastosFijos' => $nombreOtrosGastosFijos,
            'gastoComisionesVariables' => $gastoComisionesVariables, 'gastoImpuestoVariable' => $gastoImpuestoVariable, 'gastoCombustibleVariable' => $gastoCombustibleVariable, 'gastoMovilidadVariable' => $gastoMovilidadVariable,
            'gastoProveedoresVariable' => $gastoProveedoresVariable, 'gastoViaticoVariable' => $gastoViaticoVariable, 'gastoMiscelaneoVariable' => $gastoMiscelaneoVariable, 'nombreOtrosGastosVariables' => $nombreOtrosGastosVariables, 'gastosVariables' => $gastosVariables, 'gastosFijos' => $gastosFijos, 'tipoMoneda' => $req->tipoMoneda];
        return view('reportes/financieros/reporteGastos', $array);
    }

    private function reporteGastos($tipo, $idSucursal, $fechas)
    {
        if ($tipo == 0) {
            $reporteGastos = DB::table('gastos')
                ->select(DB::raw('count(*) as total, gastos.TipoGasto'))
                ->where('gastos.IdSucursal', $idSucursal)
                ->whereBetween('gastos.FechaCreacion', [$fechas[0], $fechas[1]])
                ->groupBy(DB::raw("gastos.TipoGasto"))
                ->get();
        } else {
            $reporteGastos = DB::table('gastos')
                ->select(DB::raw('count(*) as total, gastos.TipoGasto'))
                ->where('gastos.IdSucursal', $idSucursal)
                ->where('gastos.TipoGasto', $tipo)
                ->whereBetween('gastos.FechaCreacion', [$fechas[0], $fechas[1]])
                ->groupBy(DB::raw("gastos.TipoGasto"))
                ->get();
        }

        return $reporteGastos;
    }

    public function exportExcel($tipoGasto, $tipoMoneda, $fecha, $ini, $fin)
    {
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');

        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $reporteFinancieroGastos = DB::select('call sp_getFinancierosGastos(?, ?, ?, ?, ?)', array($idSucursal, $tipoGasto, $tipoMoneda, $fechas[0], $fechas[1]));

        return Excel::download(new ExcelReporteFinancieroGastos($reporteFinancieroGastos), 'Reporte Financieros - Gastos.xlsx');
    }

}
