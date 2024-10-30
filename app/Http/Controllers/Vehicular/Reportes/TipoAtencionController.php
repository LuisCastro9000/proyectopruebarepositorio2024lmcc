<?php

namespace App\Http\Controllers\Vehicular\Reportes;

use App\Exports\ExcelReporteTipoAtencion;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use App\Traits\getFuncionesTrait;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class TipoAtencionController extends Controller
{
    use getFuncionesTrait;
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
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $fecha = 5;
        $fechaIni = '';
        $fechaFin = '';
        $ini = '';
        $fin = '';
        $idTipoAtencion = 0;
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);

        $listaAtenciones = DB::table('tipo_atencion')->where('Estado', 'E')->get();
        $atenciones = $this->getVehiculosAtendidos($fechas, $idSucursal, $idTipoAtencion);

        // codigo para los cuadros de estados
        $datos = $atenciones->pluck('IdEstadoCotizacion');
        $datos = $datos->countBy();
        // Fin

        // $datosGrafico = $atenciones->pluck('Descripcion');
        // $datosGrafico = $datosGrafico->countBy();
        // $nombresAtenciones = array_keys($datosGrafico->toArray());
        // $cantidadesAtenciones = array_values($datosGrafico->toArray());

        if ($fecha == 5 || $fecha == 6 || $fecha == 7 || $fecha == 8) {
            $datosGrafico = $this->getVehiculosAtendidosGrafico($fecha, $idSucursal, $idTipoAtencion);
            $datosGrafico = $datosGrafico->map(function ($item) {
                return (object) array_merge((array) $item, ['NombreMes' => $this->getNombreMesAbreviado($item->Mes) . '-' . $item->Anio]);
            });
            $nombresAtenciones = $datosGrafico->pluck('NombreMes');
            $cantidadesAtenciones = $datosGrafico->pluck('TotalAtenciones');
        } else {
            $nombresAtenciones = '';
            $cantidadesAtenciones = '';
        }

        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'listaAtenciones' => $listaAtenciones, 'fecha' => $fecha, 'fechaIni' => $fechaIni, 'fechaFin' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'atenciones' => $atenciones, 'nombresAtenciones' => $nombresAtenciones, 'cantidadesAtenciones' => $cantidadesAtenciones, 'datos' => $datos, 'idTipoAtencion' => $idTipoAtencion];
        return view('vehicular/reportes/tipoAtencion', $array);
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
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $fecha = $req->fecha;
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        $idTipoAtencion = $req->idTipoAtencion;

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $listaAtenciones = DB::table('tipo_atencion')->where('Estado', 'E')->get();
        $atenciones = $this->getVehiculosAtendidos($fechas, $idSucursal, $idTipoAtencion);

        // codigo para los cuadros de estados
        $datos = $atenciones->pluck('IdEstadoCotizacion');
        $datos = $datos->countBy();
        // Fin

        // $datosGrafico = $atenciones->pluck('Descripcion');
        // $datosGrafico = $datosGrafico->countBy();
        // $nombresAtenciones = array_keys($datosGrafico->toArray());
        // $cantidadesAtenciones = array_values($datosGrafico->toArray());

        if ($req->fechaIni != '' && $req->fechaFin != '') {
            $ini = Carbon::createFromFormat('d/m/Y', $req->fechaIni)->format('Y-m-d');
            $fin = Carbon::createFromFormat('d/m/Y', $req->fechaFin)->format('Y-m-d');
        } else {
            $ini = '';
            $fin = '';
        }

        if ($fecha == 5 || $fecha == 6 || $fecha == 7 || $fecha == 8) {
            $datosGrafico = $this->getVehiculosAtendidosGrafico($fecha, $idSucursal, $idTipoAtencion);
            $datosGrafico = $datosGrafico->map(function ($item) {
                return (object) array_merge((array) $item, ['NombreMes' => $this->getNombreMesAbreviado($item->Mes) . '-' . $item->Anio]);
            });
            $nombresAtenciones = $datosGrafico->pluck('NombreMes');
            $cantidadesAtenciones = $datosGrafico->pluck('TotalAtenciones');
        } else {
            $nombresAtenciones = '';
            $cantidadesAtenciones = '';
        }
        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'listaAtenciones' => $listaAtenciones, 'fecha' => $fecha, 'fechaIni' => $fechaIni, 'fechaFin' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'atenciones' => $atenciones, 'nombresAtenciones' => $nombresAtenciones, 'cantidadesAtenciones' => $cantidadesAtenciones, 'datos' => $datos, 'idTipoAtencion' => $idTipoAtencion];
        return view('vehicular/reportes/tipoAtencion', $array);

    }

    public function exportExcel($fecha, $fechaIni = null, $fechaFin = null)
    {
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $fechaIni = Carbon::parse($fechaIni)->format('d/m/Y');
        $fechaFin = Carbon::parse($fechaFin)->format('d/m/Y');

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $atencionesVehiculares = $this->getVehiculosAtendidos($fechas, $idSucursal, $idTipoAtencion = 0);
        return Excel::download(new ExcelReporteTipoAtencion($atencionesVehiculares), 'ReporteAtencionesVehiculares.xlsx');
    }

    public function getVehiculosAtendidos($fechas, $idSucursal, $idTipoAtencion)
    {
        if ($idTipoAtencion == 0) {
            $atenciones = DB::table('cotizacion')
                ->select('cotizacion.FechaCreacion', 'vehiculo.PlacaVehiculo', 'tipo_atencion.Descripcion', 'operario.Nombres as NombreOperario', 'cliente.RazonSocial', 'cotizacion.IdEstadoCotizacion')
                ->join('vehiculo', 'cotizacion.campo0', '=', 'vehiculo.IdVehiculo')
                ->join('tipo_atencion', 'cotizacion.IdTipoAtencion', '=', 'tipo_atencion.IdTipoAtencion')
                ->join('operario', 'cotizacion.IdOperario', '=', 'operario.IdOperario')
                ->join('cliente', 'vehiculo.IdCliente', '=', 'cliente.IdCliente')
                ->where('cotizacion.IdSucursal', $idSucursal)
                ->whereNotIn('cotizacion.IdEstadoCotizacion', [5, 6])
                ->where('TipoCotizacion', 2)
                ->whereBetween('cotizacion.FechaCreacion', [$fechas[0], $fechas[1]])
                ->get();
            return $atenciones;
        } else {
            $atenciones = DB::table('cotizacion')
                ->select('cotizacion.FechaCreacion', 'vehiculo.PlacaVehiculo', 'tipo_atencion.Descripcion', 'operario.Nombres as NombreOperario', 'cliente.RazonSocial', 'cotizacion.IdEstadoCotizacion')
                ->join('vehiculo', 'cotizacion.campo0', '=', 'vehiculo.IdVehiculo')
                ->join('tipo_atencion', 'cotizacion.IdTipoAtencion', '=', 'tipo_atencion.IdTipoAtencion')
                ->join('operario', 'cotizacion.IdOperario', '=', 'operario.IdOperario')
                ->join('cliente', 'vehiculo.IdCliente', '=', 'cliente.IdCliente')
                ->where('cotizacion.IdSucursal', $idSucursal)
                ->whereNotIn('cotizacion.IdEstadoCotizacion', [5, 6])
                ->where('cotizacion.IdTipoAtencion', $idTipoAtencion)
                ->whereBetween('cotizacion.FechaCreacion', [$fechas[0], $fechas[1]])
                ->get();
            return $atenciones;
        }
    }

    private function getVehiculosAtendidosGrafico($fecha, $idSucursal, $idTipoAtencion)
    {
        $fechas = $this->getFiltrosFecha($fecha);
        if ($idTipoAtencion == 0) {
            $atenciones = DB::table('cotizacion')
                ->select('cotizacion.FechaCreacion', 'vehiculo.PlacaVehiculo', 'tipo_atencion.Descripcion', 'operario.Nombres as NombreOperario', 'cliente.RazonSocial', 'cotizacion.IdEstadoCotizacion', DB::raw('count(*) as TotalAtenciones, MONTH(cotizacion.FechaCreacion) as Mes, YEAR(cotizacion.FechaCreacion) as Anio'))
                ->join('vehiculo', 'cotizacion.campo0', '=', 'vehiculo.IdVehiculo')
                ->join('tipo_atencion', 'cotizacion.IdTipoAtencion', '=', 'tipo_atencion.IdTipoAtencion')
                ->join('operario', 'cotizacion.IdOperario', '=', 'operario.IdOperario')
                ->join('cliente', 'vehiculo.IdCliente', '=', 'cliente.IdCliente')
                ->where('cotizacion.IdSucursal', $idSucursal)
                ->whereNotIn('cotizacion.IdEstadoCotizacion', [5, 6])
                ->where('TipoCotizacion', 2)
                ->whereBetween('cotizacion.FechaCreacion', [$fechas[0], $fechas[1]])
                ->groupBy(DB::raw('YEAR(cotizacion.FechaCreacion), MONTH(cotizacion.FechaCreacion)'))
                ->orderBy(DB::raw('YEAR(cotizacion.FechaCreacion), MONTH(cotizacion.FechaCreacion)', 'desc'))
                ->get();
        } else {
            $atenciones = DB::table('cotizacion')
                ->select('cotizacion.FechaCreacion', 'vehiculo.PlacaVehiculo', 'tipo_atencion.Descripcion', 'operario.Nombres as NombreOperario', 'cliente.RazonSocial', 'cotizacion.IdEstadoCotizacion', DB::raw('count(*) as TotalAtenciones, MONTH(cotizacion.FechaCreacion) as Mes, YEAR(cotizacion.FechaCreacion) as Anio'))
                ->join('vehiculo', 'cotizacion.campo0', '=', 'vehiculo.IdVehiculo')
                ->join('tipo_atencion', 'cotizacion.IdTipoAtencion', '=', 'tipo_atencion.IdTipoAtencion')
                ->join('operario', 'cotizacion.IdOperario', '=', 'operario.IdOperario')
                ->join('cliente', 'vehiculo.IdCliente', '=', 'cliente.IdCliente')
                ->where('cotizacion.IdSucursal', $idSucursal)
                ->whereNotIn('cotizacion.IdEstadoCotizacion', [5, 6])
                ->where('cotizacion.IdTipoAtencion', $idTipoAtencion)
                ->whereBetween('cotizacion.FechaCreacion', [$fechas[0], $fechas[1]])
                ->groupBy(DB::raw('YEAR(cotizacion.FechaCreacion), MONTH(cotizacion.FechaCreacion)'))
                ->orderBy(DB::raw('YEAR(cotizacion.FechaCreacion), MONTH(cotizacion.FechaCreacion)', 'desc'))
                ->get();
        }
        return $atenciones;
    }

    public function ultimosSeisMesesConMesActual()
    {
        $fechaInicial = Carbon::now()->startOfMonth()->subMonths(5);
        $fechaFinal = Carbon::now();
        return [$fechaInicial, $fechaFinal];
    }

    public function ultimosDoceMesesConMesActual()
    {
        $fechaInicial = Carbon::now()->startOfMonth()->subMonths(11);
        $fechaFinal = Carbon::now();
        return [$fechaInicial, $fechaFinal];
    }

    public function getFiltrosFecha($fecha)
    {
        if ($fecha == 5) {
            $fechaInicial = Carbon::now()->startOfMonth()->subMonths(5);
            $fechaFinal = Carbon::now();
            return [$fechaInicial, $fechaFinal];
        }
        if ($fecha == 6) {
            $fechaInicial = Carbon::now()->startOfMonth()->subMonths(6);
            $fechaFinal = Carbon::now()->startOfMonth()->subSeconds(1);
            return [$fechaInicial, $fechaFinal];
        }
        if ($fecha == 7) {
            $fechaInicialAñoActual = Carbon::now()->startOfMonth()->subMonth(12);
            $fechaFinalAñoActual = Carbon::now();
            return [$fechaInicialAñoActual, $fechaFinalAñoActual];
        }

        if ($fecha == 8) {
            $fechaInicialAñoAnterior = Carbon::today()->subYear(1)->startOfYear();
            $fechaFinalAñoAnterior = Carbon::today()->subYear(1)->endOfYear();

            return [$fechaInicialAñoAnterior, $fechaFinalAñoAnterior];
        }
    }
}
