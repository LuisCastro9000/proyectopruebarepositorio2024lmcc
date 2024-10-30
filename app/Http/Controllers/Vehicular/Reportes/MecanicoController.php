<?php

namespace App\Http\Controllers\Vehicular\Reportes;

use App\Exports\ExcelReporteVehicularMecanico;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DateTime;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class MecanicoController extends Controller
{
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {

            $idUsuario = Session::get('idUsuario');
            $arrayVehicular = [];
            $arrayAtenciones = [];
            $datosConsumo = [];

            $loadDatos = new DatosController();
            $idSucursal = $idSucursal = Session::get('idSucursal');
            $fechas = $loadDatos->getFechaFiltro(5, null, null);
            $inputMecanico = null;

            $reporteVehiculares = DB::select('call sp_getVehicularMecanicos(?, ?, ?, ?)', array($idSucursal, $inputMecanico, $fechas[0], $fechas[1]));
            for ($i = 0; $i < count($reporteVehiculares); $i++) {
                $_reporteVehiculares = $this->getProductosVendidos($reporteVehiculares[$i]->IdVentas);
                $reporteVehiculares[$i]->Consumo = $_reporteVehiculares;
            }
            $reporteVehiculares = collect($reporteVehiculares);
            $montoTotalConsumo = $reporteVehiculares->pluck('Total')->sum();
            foreach ($reporteVehiculares as $key => $value) {
                foreach ($value->Consumo as $key => $item) {
                    $datosConsumo[] = $item;
                }
            }
            $datosConsumo = collect($datosConsumo);
            $totalProductos = $datosConsumo->where('IdTipo', 1)->sum('Importe');
            $totalServicios = $datosConsumo->where('IdTipo', 2)->sum('Importe');

            $sumaTotalProductosServicios = $totalProductos + $totalServicios;

            // lista de precios Servicios
            $reporte = DB::select('call sp_getVehicularNuevoMecanico(?, ?, ?, ?)', array($idSucursal, $inputMecanico, $fechas[0], $fechas[1]));

            $reporte = collect($reporte);
            $totalProducto = $reporte->where('VerificaTipo', 1);
            $totalServicio = $reporte->where('VerificaTipo', 0);

            $totalMontoProducto = [];
            foreach ($totalProducto as $key => $value) {
                if (array_key_exists($value->IdVentas, $totalMontoProducto)) {
                    $totalMontoProducto[$value->IdVentas] += $value->Importe;
                } else {
                    $totalMontoProducto[$value->IdVentas] = $value->Importe;
                }
            }

            $totalMontoServicio = [];
            foreach ($totalServicio as $key => $value) {
                if (array_key_exists($value->IdVentas, $totalMontoServicio)) {
                    $totalMontoServicio[$value->IdVentas] += $value->Importe;
                } else {
                    $totalMontoServicio[$value->IdVentas] = $value->Importe;
                }
            }

            $totalMontoServicio = collect($totalMontoServicio);
            $diferenciaProductos = $totalMontoServicio->diffKeys($totalMontoProducto);
            $preciosProductos = $diferenciaProductos->map(function ($item, $key) {
                return $item * 0;
            });
            $preciosProductos = $preciosProductos->toArray();
            $listaDePreciosProductos = $preciosProductos + $totalMontoProducto;
            $listaDePreciosProductos = collect($listaDePreciosProductos)->sortKeysDesc()->values();

            // lista de precios Servicios
            $totalMontoProductos = collect($totalMontoProducto);
            $totalMontoServicio = collect($totalMontoServicio)->toArray();
            $diferenciaServicios = $totalMontoProductos->diffKeys($totalMontoServicio);
            $preciosServicios = $diferenciaServicios->map(function ($value, $key) {
                return $value * 0;
            });
            $preciosServicios = $preciosServicios->toArray();
            $listaDePrecios = $preciosServicios + $totalMontoServicio;
            $listaDePrecios = collect($listaDePrecios)->sortKeysDesc()->values();

            for ($i = 0; $i < count($reporteVehiculares); $i++) {
                $reporteVehiculares[$i]->MontoProducto = $listaDePreciosProductos[$i];
                $reporteVehiculares[$i]->MontoServicio = $listaDePrecios[$i];
            }
            // Fin

            if (count($reporteVehiculares) > 0) {
                $i = 0;
                foreach ($reporteVehiculares as $repVehicular) {
                    if ($repVehicular->IdOperario > 0) {
                        $operarioSelect = $loadDatos->getOperarioSelect($repVehicular->IdOperario);
                        $reporteVehiculares[$i]->Operario = $operarioSelect->Nombres;
                    } else {
                        $reporteVehiculares[$i]->Operario = 'Genérico';
                    }
                    $i++;
                }
            }
            $mecanicos = $loadDatos->getOperarios($idSucursal);
            $cantidadMecanicos = collect($mecanicos)->count();

            $permisos = $loadDatos->getPermisos($idUsuario);
            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $inputMecanico = 0;
            $_inputMecanico = 0;
            $tipo = '';
            $fecha = 5;
            $fechaIni = '';
            $fechaFin = '';
            $ini = 0;
            $fin = 0;

            $reporteMecanicos = $this->reporteMecanicos(null, $idSucursal, $fechas);

            if (count($reporteMecanicos) >= 1) {
                $i = 0;
                foreach ($reporteMecanicos as $repMecanico) {
                    if ($repMecanico->IdOperario > 0) {
                        $operarioSelect = $loadDatos->getOperarioSelect($repMecanico->IdOperario);
                        $arrayVehicular[$i] = "'$operarioSelect->Nombres'";
                    } else {
                        $arrayVehicular[$i] = "'Genérico'";

                    }
                    $arrayAtenciones[$i] = $repMecanico->total;
                    $i++;
                }
            }

            $totalAtencionVehicular = collect($arrayAtenciones)->sum();

            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

            $array = ['grafvehiculos' => $arrayVehicular, 'grafTotal' => $arrayAtenciones, 'mecanicos' => $mecanicos, 'reporteVehiculares' => $reporteVehiculares, 'inputMecanico' => $inputMecanico, '_inputMecanico' => $_inputMecanico, 'tipo' => $tipo, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'totalAtencionVehicular' => $totalAtencionVehicular, 'totalServicios' => $totalServicios, 'totalProductos' => $totalProductos, 'sumaTotalProductosServicios' => $sumaTotalProductosServicios];
            return view('vehicular/reportes/mecanico', $array);
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

        $arrayVehicular = [];
        $arrayAtenciones = [];

        $loadDatos = new DatosController();

        $inputMecanico = $req->mecanico;
        if ($inputMecanico == "0") {
            $_inputMecanico = null;
        } else {
            $_inputMecanico = $req->mecanico;
        }
        // dd($inputMecanico);
        // if($inputMecanico == null){
        //     $_inputMecanico = "";
        // }else{
        //     $_inputMecanico = $inputMecanico;
        // }
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

        $idSucursal = Session::get('idSucursal');

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);

        $reporteVehiculares = DB::select('call sp_getVehicularMecanicos(?, ?, ?, ?)', array($idSucursal, $_inputMecanico, $fechas[0], $fechas[1]));

        for ($i = 0; $i < count($reporteVehiculares); $i++) {
            $_productos = $this->getProductosVendidos($reporteVehiculares[$i]->IdVentas);
            $reporteVehiculares[$i]->Consumo = $_productos;
        }
        $reporteVehiculares = collect($reporteVehiculares);
        $montoTotalConsumo = $reporteVehiculares->pluck('Total')->sum();
        $datosConsumo = [];
        foreach ($reporteVehiculares as $key => $value) {
            foreach ($value->Consumo as $key => $item) {
                $datosConsumo[] = $item;
            }
        }
        $datosConsumo = collect($datosConsumo);
        $totalProductos = round($datosConsumo->where('IdTipo', 1)->sum('Importe'));
        $totalServicios = round($datosConsumo->where('IdTipo', 2)->sum('Importe'));

        $sumaTotalProductosServicios = $totalProductos + $totalServicios;

        // lista de precios Servicios
        $reporte = DB::select('call sp_getVehicularNuevoMecanico(?, ?, ?, ?)', array($idSucursal, $_inputMecanico, $fechas[0], $fechas[1]));

        $reporte = collect($reporte);
        $totalProducto = $reporte->where('VerificaTipo', 1);
        $totalServicio = $reporte->where('VerificaTipo', 0);

        $totalMontoProducto = [];
        foreach ($totalProducto as $key => $value) {
            if (array_key_exists($value->IdVentas, $totalMontoProducto)) {
                $totalMontoProducto[$value->IdVentas] += $value->Importe;
            } else {
                $totalMontoProducto[$value->IdVentas] = $value->Importe;
            }
        }

        $totalMontoServicio = [];
        foreach ($totalServicio as $key => $value) {
            if (array_key_exists($value->IdVentas, $totalMontoServicio)) {
                $totalMontoServicio[$value->IdVentas] += $value->Importe;
            } else {
                $totalMontoServicio[$value->IdVentas] = $value->Importe;
            }
        }

        $totalMontoServicio = collect($totalMontoServicio);
        $diferenciaProductos = $totalMontoServicio->diffKeys($totalMontoProducto);
        //   dd($diferenciaProductos );
        $preciosProductos = $diferenciaProductos->map(function ($item, $key) {
            return $item * 0;
        });
        $preciosProductos = $preciosProductos->toArray();
        $listaDePreciosProductos = $preciosProductos + $totalMontoProducto;
        $listaDePreciosProductos = collect($listaDePreciosProductos)->sortKeysDesc()->values();

        // lista de precios Servicios
        $totalMontoProductos = collect($totalMontoProducto);
        $totalMontoServicio = collect($totalMontoServicio)->toArray();
        $diferenciaServicios = $totalMontoProductos->diffKeys($totalMontoServicio);
        $preciosServicios = $diferenciaServicios->map(function ($value, $key) {
            return $value * 0;
        });
        $preciosServicios = $preciosServicios->toArray();
        $listaDePrecios = $preciosServicios + $totalMontoServicio;
        $listaDePrecios = collect($listaDePrecios)->sortKeysDesc()->values();

        for ($i = 0; $i < count($reporteVehiculares); $i++) {
            $reporteVehiculares[$i]->MontoProducto = $listaDePreciosProductos[$i];
            $reporteVehiculares[$i]->MontoServicio = $listaDePrecios[$i];
        }

        if (count($reporteVehiculares) > 0) {
            $i = 0;
            foreach ($reporteVehiculares as $repVehicular) {
                if ($repVehicular->IdOperario > 0) {
                    $operarioSelect = $loadDatos->getOperarioSelect($repVehicular->IdOperario);
                    $reporteVehiculares[$i]->Operario = $operarioSelect->Nombres;
                } else {
                    $reporteVehiculares[$i]->Operario = 'Genérico';

                }
                $i++;
            }
        }

        if ($fechaIni == null && $fechaFin == null) {
            $fechaIni = 0;
            $fechaFin = 0;
        }
        // dd($fechaIni);
        $mecanicos = $loadDatos->getOperarios($idSucursal);
        $cantidadMecanicos = collect($mecanicos)->count();
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        // $fechaIni= str_replace('/','-', $fechaIni);
        // $fechaFin= str_replace('/','-', $fechaFin);
        $ini = str_replace('/', '-', $fechaIni);
        $fin = str_replace('/', '-', $fechaFin);

        $reporteMecanicos = $this->reporteMecanicos($_inputMecanico, $idSucursal, $fechas);

        if (count($reporteMecanicos) >= 1) {
            $i = 0;
            foreach ($reporteMecanicos as $repMecanico) {
                if ($repMecanico->IdOperario > 0) {
                    $operarioSelect = $loadDatos->getOperarioSelect($repMecanico->IdOperario);
                    $arrayVehicular[$i] = "'$operarioSelect->Nombres'";
                } else {
                    $arrayVehicular[$i] = "'Genérico'";

                }
                $arrayAtenciones[$i] = $repMecanico->total;
                $i++;
            }
        }

        $totalAtencionVehicular = collect($arrayAtenciones)->sum();

        $array = ['grafvehiculos' => $arrayVehicular, 'grafTotal' => $arrayAtenciones, 'mecanicos' => $mecanicos, 'reporteVehiculares' => $reporteVehiculares, 'inputMecanico' => $inputMecanico, 'tipo' => $tipo, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'modulosSelect' => $modulosSelect,
            'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'totalAtencionVehicular' => $totalAtencionVehicular, 'totalServicios' => $totalServicios, 'totalProductos' => $totalProductos, 'sumaTotalProductosServicios' => $sumaTotalProductosServicios];
        return view('vehicular/reportes/mecanico', $array);
    }

    private function reporteMecanicos($operario, $idSucursal, $fechas)
    {
        if ($operario == null) {
            $reporteMecanicos = DB::table('atencion_vehicular')
                ->where('atencion_vehicular.IdSucursal', $idSucursal)
                ->select(DB::raw('count(*) as total, atencion_vehicular.IdOperario'))
                ->whereBetween('atencion_vehicular.FechaAtencion', [$fechas[0], $fechas[1]])
                ->groupBy(DB::raw("atencion_vehicular.IdOperario"))
                ->limit(10)
                ->get();
        } elseif ($operario == 'Generico') {
            $reporteMecanicos = DB::table('atencion_vehicular')
                ->where('atencion_vehicular.IdSucursal', $idSucursal)
                ->select(DB::raw('count(*) as total, atencion_vehicular.IdOperario'))
                ->where('atencion_vehicular.IdOperario', 0)
                ->whereBetween('atencion_vehicular.FechaAtencion', [$fechas[0], $fechas[1]])
                ->groupBy(DB::raw("atencion_vehicular.IdOperario"))
                ->limit(10)
                ->get();
        } else {
            $reporteMecanicos = DB::table('atencion_vehicular')
                ->join('operario', 'atencion_vehicular.IdOperario', '=', 'operario.IdOperario')
                ->where('atencion_vehicular.IdSucursal', $idSucursal)
                ->select(DB::raw('count(*) as total, atencion_vehicular.IdOperario'))
                ->where('operario.IdOperario', $operario)
                ->whereBetween('atencion_vehicular.FechaAtencion', [$fechas[0], $fechas[1]])
                ->groupBy(DB::raw("atencion_vehicular.IdOperario"))
                ->limit(10)
                ->get();
        }

        return $reporteMecanicos;
    }

    public function exportExcel($inputMecanico, $fecha = null, $ini = null, $fin = null)
    {
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');

        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);

        if ($inputMecanico == '0') {
            $inputMecanico = null;
        }

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        // $reporteVehiculares = DB::select('call sp_getVehicularMecanicos(?, ?, ?, ?)',array($idSucursal, $inputMecanico, $fechas[0], $fechas[1]));

        $reporteVehiculares = DB::select('call sp_getVehicularNuevoMecanico(?, ?, ?, ?)', array($idSucursal, $inputMecanico, $fechas[0], $fechas[1]));

        if (count($reporteVehiculares) > 0) {
            $i = 0;
            foreach ($reporteVehiculares as $repVehicular) {
                if ($repVehicular->IdOperario > 0) {
                    $operarioSelect = $loadDatos->getOperarioSelect($repVehicular->IdOperario);
                    $reporteVehiculares[$i]->Operario = $operarioSelect->Nombres;
                } else {
                    $reporteVehiculares[$i]->Operario = 'Genérico';
                }
                $i++;
            }
        }
// dd($reporteVehiculares);
        return Excel::download(new ExcelReporteVehicularMecanico($reporteVehiculares, $inputMecanico), 'Reporte Vehicular - Mecánico.xlsx');

        /*$array = ['reporteVehiculares' => $reporteVehiculares, 'inputMecanico' => $inputMecanico];

    Excel::create('Reporte Vehiculares Mecánico', function ($excel) use($array){
    $excel->sheet('Reporte Vehiculares Mecánico', function ($sheet) use($array) {
    $sheet->loadView('excel/excelVehicularesMecanico', $array);
    });
    })->download('xlsx');*/
    }

    public function getProductosVendidos($idVentas)
    {
        try {
            $productos = DB::table('ventas_articulo')
                ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                ->select('articulo.Descripcion as Articulo', 'ventas_articulo.Cantidad', 'ventas_articulo.Importe', 'articulo.IdTipo')
                ->where('ventas_articulo.IdVentas', $idVentas)
                ->get();
            return $productos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

}
