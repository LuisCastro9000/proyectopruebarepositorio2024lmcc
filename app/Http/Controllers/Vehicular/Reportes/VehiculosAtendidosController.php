<?php

namespace App\Http\Controllers\Vehicular\Reportes;

use App\Exports\ExcelReporteAtencionesVehiculares;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class VehiculosAtendidosController extends Controller
{
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
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

            $filtroFecha = $this->filtroFechaSeisMesesAnteriores();
            $mesActual = $this->mesActual();
            $meses = ['1' => 'ENE', '2' => 'FEB', '3' => 'MAR', '4' => 'ABR', '5' => 'MAY', '6' => 'JUN', '7' => 'JUL', '8' => 'AGOS', '9' => 'SEP', '10' => 'OCT', '11' => 'NOV', '12' => 'DIC'];

            $mecanicos = $loadDatos->getOperarios($idSucursal);
            $datosVehiculos = $this->getVehiculos($idSucursal, $idMecanico = null, $filtroFecha[0], $filtroFecha[1])->where('FechaCreacion', '<', $mesActual);
            $datosAtencionesGrafico = $this->getAtencionesGrafico($idSucursal, $idMecanico = null, $filtroFecha[0], $filtroFecha[1]);
            $atencionesGraficoSeisMesesAnteriores = $datosAtencionesGrafico->where('FechaCreacion', '<', $mesActual);
            $atencionesGraficoMesActual = $datosAtencionesGrafico->where('FechaCreacion', '>=', $mesActual)->first();

            $totalAtenciones = $atencionesGraficoSeisMesesAnteriores->pluck('TotalAtenciones');
            $mesesAtencionesGrafico = $atencionesGraficoSeisMesesAnteriores->map(function ($item) use ($meses) {
                return $meses[$item->Mes] . ' ' . $item->Anio;
            });
            // $datos = DB::table('vehiculo')
            //     ->join('cotizacion', 'vehiculo.IdVehiculo', '=', 'cotizacion.campo0')
            //     ->groupBy(['PlacaVehiculo', 'IdEstadoCotizacion'])
            //     ->paginate(12)->onEachSide(5);
            // dd($datos);

            $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'datosVehiculos' => $datosVehiculos, 'mecanicos' => $mecanicos, 'totalAtenciones' => $totalAtenciones, 'mesesAtencionesGrafico' => $mesesAtencionesGrafico, 'atencionesGraficoMesActual' => $atencionesGraficoMesActual, 'mecanico' => ''];
            return view('vehicular/reportes/vehiculosAtendidos', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function store(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
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

            $filtroFecha = $this->filtroFechaSeisMesesAnteriores();
            $mesActual = $this->mesActual();
            $mecanicos = $loadDatos->getOperarios($idSucursal);

            $meses = ['1' => 'ENE', '2' => 'FEB', '3' => 'MAR', '4' => 'ABR', '5' => 'MAY', '6' => 'JUN', '7' => 'JUL', '8' => 'AGOS', '9' => 'SEP', '10' => 'OCT', '11' => 'NOV', '12' => 'DIC'];

            $mecanicos = $loadDatos->getOperarios($idSucursal);
            $datosVehiculos = $this->getVehiculos($idSucursal, $req->mecanico, $filtroFecha[0], $filtroFecha[1])->where('FechaCreacion', '<', $mesActual);
            $datosAtencionesGrafico = $this->getAtencionesGrafico($idSucursal, $req->mecanico, $filtroFecha[0], $filtroFecha[1]);
            $atencionesGraficoSeisMesesAnteriores = $datosAtencionesGrafico->where('FechaCreacion', '<', $mesActual);
            $atencionesGraficoMesActual = $datosAtencionesGrafico->where('FechaCreacion', '>=', $mesActual)->first();

            $totalAtenciones = $atencionesGraficoSeisMesesAnteriores->pluck('TotalAtenciones');
            $mesesAtencionesGrafico = $atencionesGraficoSeisMesesAnteriores->map(function ($item) use ($meses) {
                return $meses[$item->Mes] . ' ' . $item->Anio;
            });

            $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'datosVehiculos' => $datosVehiculos, 'mecanicos' => $mecanicos, 'totalAtenciones' => $totalAtenciones, 'mesesAtencionesGrafico' => $mesesAtencionesGrafico, 'atencionesGraficoMesActual' => $atencionesGraficoMesActual, 'mecanico' => $req->mecanico];

            return view('vehicular/reportes/vehiculosAtendidos', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

    }

    private function getVehiculos($idSucursal, $idMecanico, $fechaInicial, $fechaFinal)
    {
        if ($idMecanico) {
            $datos = DB::table('vehiculo')
                ->select('vehiculo.PlacaVehiculo', 'cotizacion.FechaCreacion', 'cotizacion.IdEstadoCotizacion', 'cliente.RazonSocial', 'cliente.NumeroDocumento', 'ta.Descripcion as TipoAtencion', 'operario.Nombres as NombreOperario', 'operario.IdOperario')
                ->join('cotizacion', 'vehiculo.IdVehiculo', '=', 'cotizacion.campo0')
                ->join('cliente', 'vehiculo.IdCliente', '=', 'cliente.IdCliente')
                ->join('tipo_atencion as ta', 'cotizacion.IdTipoAtencion', '=', 'ta.IdTipoAtencion')
                ->leftjoin('operario', 'cotizacion.IdOperario', '=', 'operario.IdOperario')
                ->where('vehiculo.IdSucursal', $idSucursal)
                ->where('IdEstadoCotizacion', 4)
                ->where('cotizacion.IdOperario', $idMecanico)
                ->whereBetween('cotizacion.FechaCreacion', [$fechaInicial, $fechaFinal])
                ->get();
        } else {
            $datos = DB::table('vehiculo')
                ->select('vehiculo.PlacaVehiculo', 'cotizacion.FechaCreacion', 'cotizacion.IdEstadoCotizacion', 'cliente.RazonSocial', 'cliente.NumeroDocumento', 'ta.Descripcion as TipoAtencion', 'operario.Nombres as NombreOperario', 'operario.IdOperario')
                ->join('cotizacion', 'vehiculo.IdVehiculo', '=', 'cotizacion.campo0')
                ->join('cliente', 'vehiculo.IdCliente', '=', 'cliente.IdCliente')
                ->join('tipo_atencion as ta', 'cotizacion.IdTipoAtencion', '=', 'ta.IdTipoAtencion')
                ->leftjoin('operario', 'cotizacion.IdOperario', '=', 'operario.IdOperario')
                ->where('vehiculo.IdSucursal', $idSucursal)
                ->where('TipoCotizacion', 2)
                ->where('IdEstadoCotizacion', 4)
                ->whereBetween('cotizacion.FechaCreacion', [$fechaInicial, $fechaFinal])
                ->get();
        }
        return $datos;
    }

    private function getAtencionesGrafico($idSucursal, $idMecanico, $fechaInicial, $fechaFinal)
    {
        if ($idMecanico) {
            $datos = DB::table('vehiculo')
                ->select('vehiculo.PlacaVehiculo', 'cotizacion.FechaCreacion', 'cotizacion.IdEstadoCotizacion', 'cliente.RazonSocial', 'cliente.NumeroDocumento', 'ta.Descripcion as TipoAtencion', 'operario.Nombres as NombreOperario', DB::raw('count(*) as TotalAtenciones, MONTH(cotizacion.FechaCreacion) as Mes, YEAR(cotizacion.FechaCreacion) as Anio'))
                ->join('cotizacion', 'vehiculo.IdVehiculo', '=', 'cotizacion.campo0')
                ->join('cliente', 'vehiculo.IdCliente', '=', 'cliente.IdCliente')
                ->join('tipo_atencion as ta', 'cotizacion.IdTipoAtencion', '=', 'ta.IdTipoAtencion')
                ->leftjoin('operario', 'cotizacion.IdOperario', '=', 'operario.IdOperario')
                ->where('vehiculo.IdSucursal', $idSucursal)
                ->where('cotizacion.IdOperario', $idMecanico)
                ->where('TipoCotizacion', 2)
                ->where('IdEstadoCotizacion', 4)
                ->whereBetween('cotizacion.FechaCreacion', [$fechaInicial, $fechaFinal])
                ->groupBy(DB::raw('YEAR(cotizacion.FechaCreacion), MONTH(cotizacion.FechaCreacion)'))
                ->orderBy(DB::raw('YEAR(cotizacion.FechaCreacion), MONTH(cotizacion.FechaCreacion)', 'desc'))
                ->get();
        } else {
            $datos = DB::table('vehiculo')
                ->select('vehiculo.PlacaVehiculo', 'cotizacion.FechaCreacion', 'cotizacion.IdEstadoCotizacion', 'cliente.RazonSocial', 'cliente.NumeroDocumento', 'ta.Descripcion as TipoAtencion', 'operario.Nombres as NombreOperario', DB::raw('count(*) as TotalAtenciones, MONTH(cotizacion.FechaCreacion) as Mes, YEAR(cotizacion.FechaCreacion) as Anio'))
                ->join('cotizacion', 'vehiculo.IdVehiculo', '=', 'cotizacion.campo0')
                ->join('cliente', 'vehiculo.IdCliente', '=', 'cliente.IdCliente')
                ->join('tipo_atencion as ta', 'cotizacion.IdTipoAtencion', '=', 'ta.IdTipoAtencion')
                ->leftjoin('operario', 'cotizacion.IdOperario', '=', 'operario.IdOperario')
                ->where('vehiculo.IdSucursal', $idSucursal)
                ->where('TipoCotizacion', 2)
                ->where('IdEstadoCotizacion', 4)
                ->whereBetween('cotizacion.FechaCreacion', [$fechaInicial, $fechaFinal])
                ->groupBy(DB::raw('YEAR(cotizacion.FechaCreacion), MONTH(cotizacion.FechaCreacion)'))
                ->orderBy(DB::raw('YEAR(cotizacion.FechaCreacion), MONTH(cotizacion.FechaCreacion)', 'desc'))
                ->get();
        }
        return $datos;
    }

    public function exportarExcel($idMecanico)
    {
        $idSucursal = Session::get('idSucursal');
        $filtroFecha = $this->filtroFechaSeisMesesAnteriores();
        $mesActual = $this->mesActual();
        $datosAtencionesVehiculares = $this->getVehiculos($idSucursal, $idMecanico, $filtroFecha[0], $filtroFecha[1])->where('FechaCreacion', '<', $mesActual);
        return Excel::download(new ExcelReporteAtencionesVehiculares($datosAtencionesVehiculares), 'Reporte-Atenciones.xlsx');
    }

    public function filtroFechaSeisMesesAnteriores()
    {
        $fechaInicial = Carbon::now()->subMonths(6)->startOfMonth();
        $fechaFinal = Carbon::now();
        return [$fechaInicial, $fechaFinal];
    }

    public function mesActual()
    {
        $mesActual = Carbon::now()->startOfMonth();
        return $mesActual;
    }

    // CODIGO OBTENER DATOS POR AJAX
    public function getVehiculosAjaxXEstado(Request $req)
    {
        if ($req->ajax()) {
            $idSucursal = Session::get('idSucursal');
            // $datos = $this->getVehiculos($idSucursal, $req->idEstado);

            $datos = DB::table('vehiculo')
                ->join('cotizacion', 'vehiculo.IdVehiculo', '=', 'cotizacion.campo0')
                ->groupBy(['PlacaVehiculo', 'IdEstadoCotizacion'])
                ->paginate(8);
            // return Response($datos);
            return view('vehicular.reportes.pruebaPaginacion', compact('datos'))->render();

            // return response()->json(view('vehicular.reportes.pruebaPaginacion'), compact('datos'))->render();
        }
        // return view('vehicular.reportes.pruebaPaginacion', compact('datos'));

    }
}
