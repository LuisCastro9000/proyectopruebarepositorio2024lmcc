<?php

namespace App\Http\Controllers\Vehicular\GestionTaller;

use App\Exports\TemplateExcelExportar;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Session;

class MonitoreoAtencionController extends Controller
{
    public $loadDatos;
    public $datosEstado;
    public function __construct(DatosController $datosController)
    {
        $this->loadDatos = $datosController;
        $this->datosEstado = [
            '2' => ['claseCss' => 'badge badge-success', 'color' => '#00e396', 'estado' => 'En Taller'],
            '3' => ['claseCss' => 'badge badge-warning', 'color' => '#feb019', 'estado' => 'Reparado X Entregar'],
            '4' => ['claseCss' => 'badge badge-primary', 'color' => '#008ffb', 'estado' => 'Facturado/Entregado'],
        ];
    }

    public function __invoke(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            if ($req->session()->has('idUsuario')) {
                $idUsuario = Session::get('idUsuario');
            } else {
                Session::flush();
                return redirect('/')->with('out', 'Sesión de usuario Expirado');
            }

            $idSucursal = Session::get('idSucursal');
            $permisos = $this->loadDatos->getPermisos($idUsuario);
            $subpermisos = $this->loadDatos->getSubPermisos($idUsuario);
            $subniveles = $this->loadDatos->getSubNiveles($idUsuario);
            $usuarioSelect = $this->loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $this->loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $fecha = $req->fecha ?? '5';
            $fechaIni = $req->fechaIni ?? '';
            $fechaFin = $req->fechaFin ?? '';
            if ($fecha == 9) {
                if ($fechaIni == null || $fechaFin == null) {
                    return back()->with('error', 'Completar las fechas para Filtrar');
                }
            }
            $fechas = $this->loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            $fechaI = $fechas[0]->toDateTimeString();
            $fechaF = $fechas[1]->toDateTimeString();

            $datosVehiculos = $this->getVehiculos($idSucursal, $fechas[0], $fechas[1]);
            $datosGrafico = $this->getDatosGrafico($datosVehiculos);
            $atencionesRetrasadas = $this->getAtencionesConRetraso();
            $atencionesRetrasadasGrafico = [
                'Placas' => $atencionesRetrasadas->pluck('PlacaVehiculo'),
                'TiemposRetraso' => $atencionesRetrasadas->pluck('TiempoRetraso'),
            ];

            $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect,
                'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'datosVehiculos' => $datosVehiculos, 'datosGrafico' => $datosGrafico, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'datosEstado' => $this->datosEstado, 'fechaI' => $fechaI, 'fechaF' => $fechaF, 'atencionesRetrasadasTabla' => $atencionesRetrasadas, 'atencionesRetrasadasGrafico' => $atencionesRetrasadasGrafico];
            return view('vehicular/gestionTaller/monitoreoAtencion/index', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function exportarExcel($opcion = null, $fechaIni = null, $fechaFin = null)
    {
        $idSucursal = Session::get('idSucursal');
        if ($opcion === 'excelEstadosAtenciones') {
            $vehiculos = $this->getVehiculos($idSucursal, $fechaIni, $fechaFin);
            return Excel::download(new TemplateExcelExportar($vehiculos, 'excel.excelReporteMonitoreoAtencionEstados', $this->datosEstado), 'Reporte-Monitoreo.xlsx');
        } else {
            $atencionesRetrasadas = $this->getAtencionesConRetraso();
            return Excel::download(new TemplateExcelExportar($atencionesRetrasadas, 'excel.excelReporteMonitoreoAtencionRetrasos'), 'Reporte.xlsx');
        }
    }

    /**
     * Undocumented function
     *
     * @param int $idSucursal
     * @param string $fechaInicial
     * @param string $fechaFinal
     * @return  \Illuminate\Support\Collection
     */

    private function getVehiculos($idSucursal, $fechaInicial, $fechaFinal): Collection
    {
        $datos = DB::table('vehiculo')
            ->select('vehiculo.PlacaVehiculo', 'cotizacion.FechaCreacion', 'cotizacion.IdEstadoCotizacion', 'cliente.RazonSocial', 'cliente.NumeroDocumento', 'ta.Descripcion as TipoAtencion', 'operario.Nombres as NombreOperario', 'cliente.Telefono as NumeroCelular', 'cotizacion.Trabajos',
                DB::Raw("CASE
                    WHEN cotizacion.IdEstadoCotizacion = 2 THEN DATEDIFF(cotizacion.FechaFinAtencion, CURDATE())
                    WHEN cotizacion.IdEstadoCotizacion = 2 AND DATEDIFF(cotizacion.FechaFinAtencion,CURDATE()) = 0 THEN 1
                    WHEN cotizacion.IdEstadoCotizacion <> 2 THEN '-'
                    ELSE 0
                END as TiempoRestanteAtencion"))
            ->join('cotizacion', 'vehiculo.IdVehiculo', '=', 'cotizacion.campo0')
            ->join('cliente', 'vehiculo.IdCliente', '=', 'cliente.IdCliente')
            ->join('tipo_atencion as ta', 'cotizacion.IdTipoAtencion', '=', 'ta.IdTipoAtencion')
            ->leftjoin('operario', 'cotizacion.IdOperario', '=', 'operario.IdOperario')
            ->where('vehiculo.IdSucursal', $idSucursal)
            ->whereIn('cotizacion.IdEstadoCotizacion', [2, 3, 4])
            ->whereBetween('cotizacion.FechaCreacion', [$fechaInicial, $fechaFinal])
            ->orderBy('cotizacion.FechaCreacion', 'DESC')
            ->get()
            ->unique('PlacaVehiculo');

        return $datos;
    }

    /**
     * Obtiene datos para generar gráficos basados en el estado de las cotizaciones tipo vehicular.
     *
     * @param \Illuminate\Support\Collection $datosVehiculos
     * @return array
     */
    private function getDatosGrafico($datosVehiculos): array
    {
        // Proceso = 2 , Cerrado = 3 , Finalizado = 4
        $estados = [2, 3, 4];
        $datosGrafico = [];

        foreach ($estados as $estado) {
            $resultado = $datosVehiculos->where('IdEstadoCotizacion', $estado)->count();
            if ($resultado > 0) {
                $datosGrafico[] = [
                    'cantidad' => $resultado,
                    'color' => $this->datosEstado[$estado]['color'],
                    'estado' => $this->datosEstado[$estado]['estado'],
                ];
            }
        }
        return $datosGrafico;
    }

    public function getEstadosAtencion(Request $req)
    {
        $idSucursal = Session::get('idSucursal');
        $fecha = $req->fecha;
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        $datosEstado = $this->datosEstado;

        $fechas = $this->loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $fechaI = $fechas[0];
        $fechaF = $fechas[1];

        $datosVehiculos = $this->getVehiculos($idSucursal, $fechas[0], $fechas[1]);
        $datosGrafico = $this->getDatosGrafico($datosVehiculos);

        $vista = view('vehicular.gestionTaller.monitoreoAtencion._tablaEstados', [
            'datosVehiculos' => $datosVehiculos,
            'datosGrafico' => $datosGrafico,
            'fecha' => $fecha,
            'fechaInicial' => $fechaIni,
            'fechaFinal' => $fechaFin,
            'fechaI' => $fechaI,
            'fechaF' => $fechaF,
            'datosEstado' => $datosEstado,
        ])->render();

        return response()->json(['vista' => $vista, 'fechaInicio' => $fechaI, 'fechaFinal' => $fechaF, 'datosGrafico' => $datosGrafico]);
    }

    private function getAtencionesConRetraso()
    {
        $idSucursal = Session::get('idSucursal');
        $datos = DB::table('vehiculo')
            ->join('cotizacion', 'vehiculo.IdVehiculo', '=', 'cotizacion.campo0')
            ->join('cliente', 'vehiculo.IdCliente', '=', 'cliente.IdCliente')
            ->join('tipo_atencion as ta', 'cotizacion.IdTipoAtencion', '=', 'ta.IdTipoAtencion')
            ->leftjoin('operario', 'cotizacion.IdOperario', '=', 'operario.IdOperario')
            ->join('registro_estados as Re', function ($join) {
                $join->on('cotizacion.IdCotizacion', '=', 'Re.IdCotizacion')
                    ->where('Re.IdEstadoCotizacion', '=', 2)
                    ->where('cotizacion.IdEstadoCotizacion', 2);
            })
            ->select('cotizacion.IdCotizacion', 'cotizacion.FechaFinAtencion', 'cotizacion.IdEstadoCotizacion', 'cotizacion.IdTipoAtencion', 'Re.FechaRegistro', 'vehiculo.PlacaVehiculo', 'cliente.RazonSocial', 'ta.Descripcion as TipoAtencion', 'operario.Nombres as NombreOperario', 'cliente.Telefono as NumeroCelular', 'cotizacion.Trabajos', DB::Raw('DATEDIFF(CURDATE(), cotizacion.FechaFinAtencion) as  TiempoRetraso'))
            ->whereDate('cotizacion.FechaFinAtencion', '<', Carbon::now()->format('Y-m-d'))
            ->where('cotizacion.IdSucursal', $idSucursal)
            ->get();
        return $datos;
    }
}

// $datos = DB::table('vehiculo')
//     ->join('cotizacion', 'vehiculo.IdVehiculo', '=', 'cotizacion.campo0')
//     ->join('cliente', 'vehiculo.IdCliente', '=', 'cliente.IdCliente')
//     ->join('tipo_atencion as ta', 'cotizacion.IdTipoAtencion', '=', 'ta.IdTipoAtencion')
//     ->leftjoin('operario', 'cotizacion.IdOperario', '=', 'operario.IdOperario')
//     ->join('registro_estados as Re', function ($join) {
//         $join->on('cotizacion.IdCotizacion', '=', 'Re.IdCotizacion')
//             ->where('Re.IdEstadoCotizacion', '=', 2);
//     })
//     ->select('cotizacion.IdCotizacion', 'cotizacion.FechaFinAtencion', 'cotizacion.IdEstadoCotizacion', 'cotizacion.IdTipoAtencion', 'Re.FechaRegistro', 'vehiculo.PlacaVehiculo', 'cliente.RazonSocial', 'ta.Descripcion as TipoAtencion', 'operario.Nombres as NombreOperario', 'cliente.Telefono as NumeroCelular', 'cotizacion.Trabajos',
//         DB::raw('
//             @horas := HOUR(TIMEDIFF(CONVERT_TZ(NOW(), \'UTC\', \'America/Lima\'), cotizacion.FechaFinAtencion)),
//             @dias := DATEDIFF(CURDATE(), cotizacion.FechaFinAtencion),
//             IF(@horas < 24,  CONCAT(@horas, " HORAS"), CONCAT(@dias, " DIAS")) as TiempoDos
//         '))

//     ->where('cotizacion.FechaFinAtencion', '<', Carbon::now())
//     ->where('cotizacion.IdSucursal', $idSucursal)
//     ->get();
