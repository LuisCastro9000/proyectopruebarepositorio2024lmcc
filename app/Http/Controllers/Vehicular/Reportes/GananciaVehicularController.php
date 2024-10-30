<?php

namespace App\Http\Controllers\Vehicular\Reportes;

use App\Exports\ExcelReporteGananciasXmecanico;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class GananciaVehicularController extends Controller
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
        $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect(
            $usuarioSelect->CodigoCliente
        );
        $fecha = 5;
        $fechaIni = '';
        $fechaFin = '';
        $ini = '';
        $fin = '';
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $placas = $loadDatos->getPlacas($idSucursal)->unique('PlacaVehiculo');
        $mecanicos = $loadDatos->getOperarios($idSucursal);

        $inputPlaca = 0;
        $inputMecanico = 0;
        $_inputPlaca = 0;
        $_inputMecanico = 0;

        // nuevo codigo
        $gananciaVehicular = $this->getGanaciasXmecanico($idSucursal, $inputPlaca, $_inputMecanico, $fechas[0], $fechas[1]
        );
        //  dd($gananciaVehicular[0]);
        // $a = [2,3,4,5,6,7,8,9,2,3,4,5,2,4,5,6,7,8,];
        // foreach ($gananciaVehicular  as $key=> $value) {
        //     // $a[] = [$key=> $value->FechaCreacion, 'b'=> $value->codigo] ;
        //     $value->nuevo = $a[$key];
        // }
        // dd($gananciaVehicular);
        $gananciaVehicular = collect($gananciaVehicular);
        $gananciaVehicularSoles = $gananciaVehicular->where('IdTipoMoneda', 1);
        $gananciaVehicularDolares = $gananciaVehicular->where('IdTipoMoneda', 2);

        $totalProducto = $gananciaVehicular->where('IdTipo', 1)->toArray();
        $totalServicio = $gananciaVehicular->where('IdTipo', 2)->toArray();
        $ganancia = $gananciaVehicular->unique('IdVentas')->values();
        $arrayCostosProductos = [];
        $arrayGananciasProductos = [];
        $arrayCostosServicio = [];
        $arrayGananciasServicio = [];
        foreach ($totalProducto as $key => $value) {
            if (array_key_exists($value->IdVentas, $arrayCostosProductos)) {
                $arrayCostosProductos[$value->IdVentas] += $value->Costo;
            } else {
                $arrayCostosProductos[$value->IdVentas] = $value->Costo;
            }
        }
        foreach ($totalServicio as $key => $value) {
            if (array_key_exists($value->IdVentas, $arrayCostosServicio)) {
                $arrayCostosServicio[$value->IdVentas] += $value->Costo;
            } else {
                $arrayCostosServicio[$value->IdVentas] = $value->Costo;
            }
        }
        // Costos
        $arrayCostosServicio = collect($arrayCostosServicio);
        $diferenciaProductos = $arrayCostosServicio->diffKeys(
            $arrayCostosProductos
        );
        $costosProductos = $diferenciaProductos->map(function ($item, $key) {
            return $item * 0;
        });
        $costosProductos = $costosProductos->toArray();
        $listaDeCostosProductos = $costosProductos + $arrayCostosProductos;
        $listaDeCostosProductos = collect($listaDeCostosProductos)
            ->sortKeys()
            ->values();

        $arrayCostosProductos = collect($arrayCostosProductos);
        $arrayCostosServicio = collect($arrayCostosServicio)->toArray();
        $diferenciaServicios = $arrayCostosProductos->diffKeys(
            $arrayCostosServicio
        );
        $costosServicios = $diferenciaServicios->map(function ($value, $key) {
            return $value * 0;
        });
        $costosServicios = $costosServicios->toArray();
        $listaDeCostosServicios = $costosServicios + $arrayCostosServicio;
        $listaDeCostosServicios = collect($listaDeCostosServicios)
            ->sortKeys()
            ->values();

        // Ganancias
        foreach ($totalProducto as $key => $value) {
            if (array_key_exists($value->IdVentas, $arrayGananciasProductos)) {
                $arrayGananciasProductos[$value->IdVentas] += $value->Ganancia;
            } else {
                $arrayGananciasProductos[$value->IdVentas] = $value->Ganancia;
            }
        }
        foreach ($totalServicio as $key => $value) {
            if (array_key_exists($value->IdVentas, $arrayGananciasServicio)) {
                $arrayGananciasServicio[$value->IdVentas] += $value->Ganancia;
            } else {
                $arrayGananciasServicio[$value->IdVentas] = $value->Ganancia;
            }
        }
        $arrayGananciasServicio = collect($arrayGananciasServicio);
        $diferenciaProductos = $arrayGananciasServicio->diffKeys(
            $arrayGananciasProductos
        );
        $gananciasProductos = $diferenciaProductos->map(function ($item, $key) {
            return $item * 0;
        });
        $gananciasProductos = $gananciasProductos->toArray();
        $listaDeGananciasProductos =
            $gananciasProductos + $arrayGananciasProductos;
        $listaDeGananciasProductos = collect($listaDeGananciasProductos)
            ->sortKeys()
            ->values();

        $arrayGananciasProductos = collect($arrayGananciasProductos);
        $arrayGananciasServicio = collect($arrayGananciasServicio)->toArray();
        $diferenciaServicios = $arrayGananciasProductos->diffKeys(
            $arrayGananciasServicio
        );
        $gananciaServicios = $diferenciaServicios->map(function ($value, $key) {
            return $value * 0;
        });
        $gananciaServicios = $gananciaServicios->toArray();
        $listaDeGananciasServicios =
            $gananciaServicios + $arrayGananciasServicio;
        $listaDeGananciasServicios = collect($listaDeGananciasServicios)
            ->sortKeys()
            ->values();

        for ($i = 0; $i < count($ganancia); $i++) {
            $ganancia[$i]->CostoProducto = $listaDeCostosProductos[$i];
            $ganancia[$i]->CostoServicio = $listaDeCostosServicios[$i];
            $ganancia[$i]->GananciaProducto = $listaDeGananciasProductos[$i];
            $ganancia[$i]->GananciaServicio = $listaDeGananciasServicios[$i];
        }

        $gananciasSoles = $ganancia->where('IdTipoMoneda', 1);
        // dd($gananciasSoles);
        $cantVehiculosAtendidosSoles = $gananciasSoles->count();
        $gananciaTotalProductosSoles = $gananciasSoles->pluck('GananciaProducto')->sum();
        $gananciaTotalServiciosSoles = $gananciasSoles->pluck('GananciaServicio')->sum();
        $costoTotalProductosSoles = $gananciasSoles->pluck('CostoProducto')->sum();
        $costoTotalServiciosSoles = $gananciasSoles->pluck('CostoServicio')->sum();
        // $totalImporteProductoSoles = collect($totalProducto)->pluck('Importe')->sum();
        // $totalImporteServicioSoles = collect($totalServicio)->pluck('Importe')->sum();
        $totalImporteProductoSoles = $gananciaVehicularSoles->where('IdTipo', 1)->pluck('Importe')->sum();
        $totalImporteServicioSoles = $gananciaVehicularSoles->where('IdTipo', 2)->pluck('Importe')->sum();
        $arrayTotalesProductosSoles = [$costoTotalProductosSoles, $gananciaTotalProductosSoles];
        $arrayTotalesServiciosSoles = [$costoTotalServiciosSoles, $gananciaTotalServiciosSoles];
        $arrayTotalesProductosSoles = collect($arrayTotalesProductosSoles)->map(function ($valor) {
            return round($valor, 2);
        });
        $arrayTotalesServiciosSoles = collect($arrayTotalesServiciosSoles)->map(function ($valor) {
            return round($valor, 2);
        });

        $gananciasDolares = $ganancia->where('IdTipoMoneda', 2);
        $cantVehiculosAtendidosDolares = $gananciasDolares->count();
        $gananciaTotalProductosDolares = $gananciasDolares->pluck('GananciaProducto')->sum();
        $gananciaTotalServiciosDolares = $gananciasDolares->pluck('GananciaServicio')->sum();
        $costoTotalProductosDolares = $gananciasDolares->pluck('CostoProducto')->sum();
        $costoTotalServiciosDolares = $gananciasDolares->pluck('CostoServicio')->sum();
        // $totalImporteProductoDolares = collect($totalProducto)->pluck('Importe')->sum();
        // $totalImporteServicioDolares = collect($totalServicio)->pluck('Importe')->sum();
        $totalImporteProductoDolares = $gananciaVehicularDolares->where('IdTipo', 1)->pluck('Importe')->sum();
        $totalImporteServicioDolares = $gananciaVehicularDolares->where('IdTipo', 2)->pluck('Importe')->sum();
        $arrayTotalesProductosDolares = [$costoTotalProductosDolares, $gananciaTotalProductosDolares];
        $arrayTotalesServiciosDolares = [$costoTotalServiciosDolares, $gananciaTotalServiciosDolares];
        $arrayTotalesProductosDolares = collect($arrayTotalesProductosDolares)->map(function ($valor) {
            return round($valor, 2);
        });
        $arrayTotalesServiciosDolares = collect($arrayTotalesServiciosDolares)->map(function ($valor) {
            return round($valor, 2);
        });

        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'placas' => $placas, 'mecanicos' => $mecanicos, 'ganancia' => $ganancia, 'inputPlaca' => $inputPlaca, '_inputPlaca' => $_inputPlaca, 'inputMecanico' => $inputMecanico, '_inputMecanico' => $_inputMecanico, 'gananciaTotalProductosSoles' => $gananciaTotalProductosSoles, 'gananciaTotalServiciosSoles' => $gananciaTotalServiciosSoles, 'costoTotalProductosSoles' => $costoTotalProductosSoles, 'costoTotalServiciosSoles' => $costoTotalServiciosSoles, 'totalImporteProductoSoles' => $totalImporteProductoSoles, 'totalImporteServicioSoles' => $totalImporteServicioSoles, 'arrayTotalesProductosSoles' => $arrayTotalesProductosSoles, 'arrayTotalesServiciosSoles' => $arrayTotalesServiciosSoles, 'cantVehiculosAtendidosSoles' => $cantVehiculosAtendidosSoles, 'gananciaTotalProductosDolares' => $gananciaTotalProductosDolares, 'gananciaTotalServiciosDolares' => $gananciaTotalServiciosDolares, 'costoTotalProductosDolares' => $costoTotalProductosDolares, 'costoTotalServiciosDolares' => $costoTotalServiciosDolares, 'totalImporteProductoDolares' => $totalImporteProductoDolares, 'totalImporteServicioDolares' => $totalImporteServicioDolares, 'arrayTotalesProductosDolares' => $arrayTotalesProductosDolares, 'arrayTotalesServiciosDolares' => $arrayTotalesServiciosDolares, 'cantVehiculosAtendidosDolares' => $cantVehiculosAtendidosDolares,
        ];

        return view('vehicular/reportes/gananciaVehicular', $array);
    }

    public function filtrarGanancias(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect(
            $usuarioSelect->CodigoCliente
        );
        $placas = $loadDatos->getPlacas($idSucursal)->unique('PlacaVehiculo');
        $mecanicos = $loadDatos->getOperarios($idSucursal);
        $fecha = $req->fecha;
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        $inputMecanico = $req->mecanico;
        $inputPlaca = $req->placa;
        $inputNombreMecanico = $req->inputNombreMecanico;
        $inputNombrePlaca = $req->inputNombrePlaca;
        // dd($inputPlaca);

        if ($inputPlaca == null) {
            $_inputPlaca = 0;
        } else {
            $_inputPlaca = $inputPlaca;
        }

        if ($inputMecanico == null) {
            $_inputMecanico = 0;
        } else {
            $_inputMecanico = $inputMecanico;

        }

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $ini = str_replace('/', '-', $fechaIni);
        $fin = str_replace('/', '-', $fechaFin);

        // nuevo codigo
        $gananciaVehicular = $this->getGanaciasXmecanico($idSucursal, $_inputPlaca, $_inputMecanico, $fechas[0], $fechas[1]);
        $gananciaVehicular = collect($gananciaVehicular);
        $gananciaVehicularSoles = $gananciaVehicular->where('IdTipoMoneda', 1);
        $gananciaVehicularDolares = $gananciaVehicular->where('IdTipoMoneda', 2);

        $totalProducto = $gananciaVehicular->where('IdTipo', 1)->toArray();
        $totalServicio = $gananciaVehicular->where('IdTipo', 2)->toArray();
        $ganancia = $gananciaVehicular->unique('IdVentas')->values();
        $arrayCostosProductos = [];
        $arrayGananciasProductos = [];
        $arrayCostosServicio = [];
        $arrayGananciasServicio = [];
        foreach ($totalProducto as $key => $value) {
            if (array_key_exists($value->IdVentas, $arrayCostosProductos)) {
                $arrayCostosProductos[$value->IdVentas] += $value->Costo;
            } else {
                $arrayCostosProductos[$value->IdVentas] = $value->Costo;
            }
        }
        foreach ($totalServicio as $key => $value) {
            if (array_key_exists($value->IdVentas, $arrayCostosServicio)) {
                $arrayCostosServicio[$value->IdVentas] += $value->Costo;
            } else {
                $arrayCostosServicio[$value->IdVentas] = $value->Costo;
            }
        }
// Costos
        $arrayCostosServicio = collect($arrayCostosServicio);
        $diferenciaProductos = $arrayCostosServicio->diffKeys(
            $arrayCostosProductos
        );
        $costosProductos = $diferenciaProductos->map(function ($item, $key) {
            return $item * 0;
        });
        $costosProductos = $costosProductos->toArray();
        $listaDeCostosProductos = $costosProductos + $arrayCostosProductos;
        $listaDeCostosProductos = collect($listaDeCostosProductos)
            ->sortKeys()
            ->values();

        $arrayCostosProductos = collect($arrayCostosProductos);
        $arrayCostosServicio = collect($arrayCostosServicio)->toArray();
        $diferenciaServicios = $arrayCostosProductos->diffKeys(
            $arrayCostosServicio
        );
        $costosServicios = $diferenciaServicios->map(function ($value, $key) {
            return $value * 0;
        });
        $costosServicios = $costosServicios->toArray();
        $listaDeCostosServicios = $costosServicios + $arrayCostosServicio;
        $listaDeCostosServicios = collect($listaDeCostosServicios)
            ->sortKeys()
            ->values();

// Ganancias
        foreach ($totalProducto as $key => $value) {
            if (array_key_exists($value->IdVentas, $arrayGananciasProductos)) {
                $arrayGananciasProductos[$value->IdVentas] += $value->Ganancia;
            } else {
                $arrayGananciasProductos[$value->IdVentas] = $value->Ganancia;
            }
        }
        foreach ($totalServicio as $key => $value) {
            if (array_key_exists($value->IdVentas, $arrayGananciasServicio)) {
                $arrayGananciasServicio[$value->IdVentas] += $value->Ganancia;
            } else {
                $arrayGananciasServicio[$value->IdVentas] = $value->Ganancia;
            }
        }
        $arrayGananciasServicio = collect($arrayGananciasServicio);
        $diferenciaProductos = $arrayGananciasServicio->diffKeys(
            $arrayGananciasProductos
        );
        $gananciasProductos = $diferenciaProductos->map(function ($item, $key) {
            return $item * 0;
        });
        $gananciasProductos = $gananciasProductos->toArray();
        $listaDeGananciasProductos =
            $gananciasProductos + $arrayGananciasProductos;
        $listaDeGananciasProductos = collect($listaDeGananciasProductos)
            ->sortKeys()
            ->values();

        $arrayGananciasProductos = collect($arrayGananciasProductos);
        $arrayGananciasServicio = collect($arrayGananciasServicio)->toArray();
        $diferenciaServicios = $arrayGananciasProductos->diffKeys(
            $arrayGananciasServicio
        );
        $gananciaServicios = $diferenciaServicios->map(function ($value, $key) {
            return $value * 0;
        });
        $gananciaServicios = $gananciaServicios->toArray();
        $listaDeGananciasServicios =
            $gananciaServicios + $arrayGananciasServicio;
        $listaDeGananciasServicios = collect($listaDeGananciasServicios)
            ->sortKeys()
            ->values();

        for ($i = 0; $i < count($ganancia); $i++) {
            $ganancia[$i]->CostoProducto = $listaDeCostosProductos[$i];
            $ganancia[$i]->CostoServicio = $listaDeCostosServicios[$i];
            $ganancia[$i]->GananciaProducto = $listaDeGananciasProductos[$i];
            $ganancia[$i]->GananciaServicio = $listaDeGananciasServicios[$i];
        }

        $gananciasSoles = $ganancia->where('IdTipoMoneda', 1);
// dd($gananciasSoles);
        $cantVehiculosAtendidosSoles = $gananciasSoles->count();
        $gananciaTotalProductosSoles = $gananciasSoles->pluck('GananciaProducto')->sum();
        $gananciaTotalServiciosSoles = $gananciasSoles->pluck('GananciaServicio')->sum();
        $costoTotalProductosSoles = $gananciasSoles->pluck('CostoProducto')->sum();
        $costoTotalServiciosSoles = $gananciasSoles->pluck('CostoServicio')->sum();
// $totalImporteProductoSoles = collect($totalProducto)->pluck('Importe')->sum();
// $totalImporteServicioSoles = collect($totalServicio)->pluck('Importe')->sum();
        $totalImporteProductoSoles = $gananciaVehicularSoles->where('IdTipo', 1)->pluck('Importe')->sum();
        $totalImporteServicioSoles = $gananciaVehicularSoles->where('IdTipo', 2)->pluck('Importe')->sum();
        $arrayTotalesProductosSoles = [$costoTotalProductosSoles, $gananciaTotalProductosSoles];
        $arrayTotalesServiciosSoles = [$costoTotalServiciosSoles, $gananciaTotalServiciosSoles];
        $arrayTotalesProductosSoles = collect($arrayTotalesProductosSoles)->map(function ($valor) {
            return round($valor, 2);
        });
        $arrayTotalesServiciosSoles = collect($arrayTotalesServiciosSoles)->map(function ($valor) {
            return round($valor, 2);
        });

        $gananciasDolares = $ganancia->where('IdTipoMoneda', 2);
        $cantVehiculosAtendidosDolares = $gananciasDolares->count();
        $gananciaTotalProductosDolares = $gananciasDolares->pluck('GananciaProducto')->sum();
        $gananciaTotalServiciosDolares = $gananciasDolares->pluck('GananciaServicio')->sum();
        $costoTotalProductosDolares = $gananciasDolares->pluck('CostoProducto')->sum();
        $costoTotalServiciosDolares = $gananciasDolares->pluck('CostoServicio')->sum();
// $totalImporteProductoDolares = collect($totalProducto)->pluck('Importe')->sum();
// $totalImporteServicioDolares = collect($totalServicio)->pluck('Importe')->sum();
        $totalImporteProductoDolares = $gananciaVehicularDolares->where('IdTipo', 1)->pluck('Importe')->sum();
        $totalImporteServicioDolares = $gananciaVehicularDolares->where('IdTipo', 2)->pluck('Importe')->sum();
        $arrayTotalesProductosDolares = [$costoTotalProductosDolares, $gananciaTotalProductosDolares];
        $arrayTotalesServiciosDolares = [$costoTotalServiciosDolares, $gananciaTotalServiciosDolares];
        $arrayTotalesProductosDolares = collect($arrayTotalesProductosDolares)->map(function ($valor) {
            return round($valor, 2);
        });
        $arrayTotalesServiciosDolares = collect($arrayTotalesServiciosDolares)->map(function ($valor) {
            return round($valor, 2);
        });

        $array = [
            'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'placas' => $placas, 'mecanicos' => $mecanicos, 'ganancia' => $ganancia, 'inputPlaca' => $inputPlaca, '_inputPlaca' => $_inputPlaca, 'inputMecanico' => $inputMecanico, '_inputMecanico' => $_inputMecanico, 'inputNombreMecanico' => $inputNombreMecanico, 'inputNombrePlaca' => $inputNombrePlaca, 'gananciaTotalProductosSoles' => $gananciaTotalProductosSoles, 'gananciaTotalServiciosSoles' => $gananciaTotalServiciosSoles, 'costoTotalProductosSoles' => $costoTotalProductosSoles, 'costoTotalServiciosSoles' => $costoTotalServiciosSoles, 'totalImporteProductoSoles' => $totalImporteProductoSoles, 'totalImporteServicioSoles' => $totalImporteServicioSoles, 'arrayTotalesProductosSoles' => $arrayTotalesProductosSoles, 'arrayTotalesServiciosSoles' => $arrayTotalesServiciosSoles, 'cantVehiculosAtendidosSoles' => $cantVehiculosAtendidosSoles, 'gananciaTotalProductosDolares' => $gananciaTotalProductosDolares, 'gananciaTotalServiciosDolares' => $gananciaTotalServiciosDolares, 'costoTotalProductosDolares' => $costoTotalProductosDolares, 'costoTotalServiciosDolares' => $costoTotalServiciosDolares, 'totalImporteProductoDolares' => $totalImporteProductoDolares, 'totalImporteServicioDolares' => $totalImporteServicioDolares, 'arrayTotalesProductosDolares' => $arrayTotalesProductosDolares, 'arrayTotalesServiciosDolares' => $arrayTotalesServiciosDolares, 'cantVehiculosAtendidosDolares' => $cantVehiculosAtendidosDolares,
        ];
        return view('vehicular/reportes/gananciaVehicular', $array);
    }

    public function ExportExcel($inputPlaca, $inputMecanico, $fecha = null, $ini = null, $fin = null)
    {
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');

        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $reporteGanancias = $this->getGanaciasXmecanico(
            $idSucursal,
            $inputPlaca,
            $inputMecanico,
            $fechas[0],
            $fechas[1]
        );
        return Excel::download(new ExcelReporteGananciasXmecanico($reporteGanancias), 'GANANCIA POR PLACA.xlsx');
    }

    public function getGanaciasXmecanico($idSucursal, $inputPlaca, $inputMecanico, $fecha1, $fecha2)
    {
        try {
            if ($inputMecanico == 0) {
                if ($inputPlaca == 0) {
                    $resultado = DB::table('ventas')
                        ->join('atencion_vehicular as av', 'ventas.IdVentas', '=', 'av.IdVentas')
                        ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                        ->join('cotizacion', 'ventas.IdCotizacion', '=', 'cotizacion.IdCotizacion')
                        ->join('ventas_articulo as va', 'ventas.IdVentas', '=', 'va.IdVentas')
                        ->join('articulo', 'va.IdArticulo', '=', 'articulo.IdArticulo')
                        ->select('ventas.IdVentas', 'ventas.IdTipoMoneda', 'av.IdOperario', 'va.codigo', 'ventas.FechaCreacion', 'ventas.Placa', 'articulo.IdTipo', 'cliente.RazonSocial', 'cliente.NumeroDocumento', 'ventas.Total', DB::raw('concat(cotizacion.Serie ,"-", cotizacion.Numero) as Cotizacion'), DB::raw('concat(ventas.Serie, "-", ventas.Numero) as ComprobanteVenta'), 'va.Ganancia', 'va.Importe', DB::raw('(va.Importe - va.Ganancia) as Costo'), 'va.Descuento', 'va.Cantidad', 'articulo.Descripcion')
                        ->where('ventas.IdSucursal', $idSucursal)
                        ->whereBetween('ventas.FechaCreacion', [$fecha1, $fecha2])
                        ->get();
                } else {
                    $resultado = DB::table('ventas')
                        ->join('atencion_vehicular as av', 'ventas.IdVentas', '=', 'av.IdVentas')
                        ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                        ->join('cotizacion', 'ventas.IdCotizacion', '=', 'cotizacion.IdCotizacion')
                        ->join('ventas_articulo as va', 'ventas.IdVentas', '=', 'va.IdVentas')
                        ->join('articulo', 'va.IdArticulo', '=', 'articulo.IdArticulo')
                        ->select('ventas.IdVentas', 'ventas.IdTipoMoneda', 'av.IdOperario', 'va.codigo', 'ventas.FechaCreacion', 'ventas.Placa', 'articulo.IdTipo', 'cliente.RazonSocial', 'cliente.NumeroDocumento', 'ventas.Total', DB::raw('concat(cotizacion.Serie ,"-", cotizacion.Numero) as Cotizacion'), DB::raw('concat(ventas.Serie, "-", ventas.Numero) as ComprobanteVenta'), 'va.Ganancia', 'va.Importe', DB::raw('(va.Importe - va.Ganancia) as Costo'), 'va.Descuento', 'va.Cantidad', 'articulo.Descripcion')
                        ->where('ventas.IdSucursal', $idSucursal)
                        ->where('ventas.Placa', $inputPlaca)
                        ->whereBetween('ventas.FechaCreacion', [$fecha1, $fecha2])
                        ->get();

                }
            } elseif ($inputMecanico != 0) {
                $resultado = DB::table('ventas')
                    ->join('atencion_vehicular as av', 'ventas.IdVentas', '=', 'av.IdVentas')
                    ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                    ->join('cotizacion', 'ventas.IdCotizacion', '=', 'cotizacion.IdCotizacion')
                    ->join('ventas_articulo as va', 'ventas.IdVentas', '=', 'va.IdVentas')
                    ->join('articulo', 'va.IdArticulo', '=', 'articulo.IdArticulo')
                    ->select('ventas.IdVentas', 'ventas.IdTipoMoneda', 'av.IdOperario', 'va.codigo', 'ventas.FechaCreacion', 'ventas.Placa', 'articulo.IdTipo', 'cliente.RazonSocial', 'cliente.NumeroDocumento', 'ventas.Total', DB::raw('concat(cotizacion.Serie ,"-", cotizacion.Numero) as Cotizacion'), DB::raw('concat(ventas.Serie, "-", ventas.Numero) as ComprobanteVenta'), 'va.Ganancia', 'va.Importe', DB::raw('(va.Importe - va.Ganancia) as Costo'), 'va.Descuento', 'va.Cantidad', 'articulo.Descripcion')
                    ->where('ventas.IdSucursal', $idSucursal)
                    ->where('av.IdOperario', $inputMecanico)
                    ->whereBetween('ventas.FechaCreacion', [$fecha1, $fecha2])
                    ->get();

            } elseif ($inputMecanico == 'Generico') {
                $resultado = DB::table('ventas')
                    ->join('atencion_vehicular as av', 'ventas.IdVentas', '=', 'av.IdVentas')
                    ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                    ->join('cotizacion', 'ventas.IdCotizacion', '=', 'cotizacion.IdCotizacion')
                    ->join('ventas_articulo as va', 'ventas.IdVentas', '=', 'va.IdVentas')
                    ->join('articulo', 'va.IdArticulo', '=', 'articulo.IdArticulo')
                    ->select('ventas.IdVentas', 'ventas.IdTipoMoneda', 'av.IdOperario', 'va.codigo', 'ventas.FechaCreacion', 'ventas.Placa', 'articulo.IdTipo', 'cliente.RazonSocial', 'cliente.NumeroDocumento', 'ventas.Total', DB::raw('concat(cotizacion.Serie ,"-", cotizacion.Numero) as Cotizacion'), DB::raw('concat(ventas.Serie, "-", ventas.Numero) as ComprobanteVenta'), 'va.Ganancia', 'va.Importe', DB::raw('(va.Importe - va.Ganancia) as Costo'), 'va.Descuento', 'va.Cantidad', 'articulo.Descripcion')
                    ->where('ventas.IdSucursal', $idSucursal)
                    ->where('av.IdOperario', 0)
                    ->whereBetween('ventas.FechaCreacion', [$fecha1, $fecha2])
                    ->get();
            }
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
