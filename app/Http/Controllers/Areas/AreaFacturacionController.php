<?php
namespace App\Http\Controllers\Areas;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Session;

class AreaFacturacionController extends Controller
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
        $idUsuario = Session::get('idUsuario');
        $idSucursal = Session::get('idSucursal');
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $sucursales = $loadDatos->getSucursales($usuarioSelect->CodigoCliente, $usuarioSelect->IdOperador);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $permisosBotones = $loadDatos->getPermisosBotones($idUsuario);
        $idDocumento = 2;
        $fechaInicial = Carbon::now()->startOfMonth();
        $fechaFinal = Carbon::now()->endOfMonth();
        $datosDocumento = $this->getDatosDocumentos($idDocumento, $fechaInicial, $fechaFinal, $idSucursal);
        $totalDocumentoPendientesSoles = $datosDocumento->where('IdTipoMoneda', 1)->countBy('TipoDocumento');
        $totalDocumentoPendientesDolares = $datosDocumento->where('IdTipoMoneda', 2)->countBy('TipoDocumento');

        $meses = $this->getUltimosTresMeses();
        $nombreMesActual = ucfirst(Carbon::now()->monthName);

        $array = ['usuarioSelect' => $usuarioSelect, 'sucursales' => $sucursales, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'datosDocumento' => $datosDocumento, 'meses' => $meses, 'nombreMesActual' => $nombreMesActual, 'totalDocumentoPendientesSoles' => $totalDocumentoPendientesSoles, 'totalDocumentoPendientesDolares' => $totalDocumentoPendientesDolares, 'totalDocumentoPendientesGuias' => [], 'idDocumento' => $idDocumento, 'permisosBotones' => $permisosBotones,
        ];
        return view('areas/areaFacturacion/index', $array);
    }

    public function getUltimosTresMeses()
    {
        // CREANDO UN OBJETO DE LOS ULTIMOS TRES MESES
        $meses = collect([2, 1, 0])->map(function ($item) {
            return (object) [
                'NombreMes' => ucfirst(carbon::now()->startOfMonth()->subMonth($item)->monthName),
                'FechaInicial' => carbon::now()->startOfMonth()->subMonth($item)->startOfMonth()->toDateString(),
                'FechaFinal' => carbon::now()->startOfMonth()->subMonth($item)->endOfMonth()->toDateString(),
            ];
        });
        return $meses;
    }

    public function getDatosDocumentosAjax(Request $req)
    {
        if ($req->ajax()) {
            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
            $idDocumento = $req->idDocumento;

            $datosDocumento = $this->getDatosDocumentos($idDocumento, $req->fechaInicial, $req->fechaFinal, $idSucursal);
            $totalDocumentoPendientesSoles = [];
            $totalDocumentoPendientesDolares = [];
            $totalDocumentoPendientesGuias = [];
            $fechaParaObtenerBoletasPendientes = '';
            if ($idDocumento == 3) {
                $totalDocumentoPendientesGuias = $datosDocumento->countBy('TipoDocumento');
            } else {
                $fechaBoletaPendiente = $datosDocumento->min('FechaCreacion');
                if ($fechaBoletaPendiente != '') {
                    $fechaParaObtenerBoletasPendientes = Carbon::createFromFormat('Y-m-d H:i:s', $datosDocumento->min('FechaCreacion'))->format('d/m/Y');
                }
                $totalDocumentoPendientesSoles = $datosDocumento->where('IdTipoMoneda', 1)->countBy('TipoDocumento');
                $totalDocumentoPendientesDolares = $datosDocumento->where('IdTipoMoneda', 2)->countBy('TipoDocumento');
            }

            return view('areas.areaFacturacion._tablaRespuestaAjax', compact('datosDocumento', 'totalDocumentoPendientesSoles', 'totalDocumentoPendientesDolares', 'totalDocumentoPendientesGuias', 'idDocumento', 'fechaParaObtenerBoletasPendientes'));
        }
    }

    public function getDatosDocumentos($idDocumento, $fechaInicial, $fechaFinal, $idSucursal)
    {
        // AGREGUE DOS COLUMNAS FICTICIAS CON DB:RAM->tipoDocumento(sirve para hacer un conteo)->Documento(sirve para mostrar el nombre del tipo de documento, en la vista )
        if ($idDocumento == 1) {
            $resumenDiarioBoletasPendientes = DB::table('resumen_diario')
                ->select('FechaEmitida as FechaCreacion', 'IdTipoMoneda', 'Numero as CorrelativoDocumento', 'Estado', DB::RAW("'Resumen Boleta' Documento, 'Pendiente' TipoDocumento"))
                ->where('Estado', 'Resumen Pendiente')
                ->where('IdSucursal', $idSucursal)
                ->where('TipoResumen', 1)
                ->whereBetween('FechaEmitida', [$fechaInicial, $fechaFinal])
                ->get();

            $resumenDiarioNotaCredito = DB::table('resumen_diario')
                ->select('FechaEmitida as FechaCreacion', 'IdTipoMoneda', 'Numero as CorrelativoDocumento', 'Estado', DB::RAW("'Resumen Nota Credito' Documento, 'NotaCredito' TipoDocumento"))
                ->where('Estado', 'Resumen Pendiente')
                ->where('IdSucursal', $idSucursal)
                ->where('TipoResumen', 2)
                ->whereBetween('FechaEmitida', [$fechaInicial, $fechaFinal])
                ->get();

            $resumenDiarioBajaPendiente = DB::table('resumen_diario')
                ->select('FechaEmitida as FechaCreacion', 'IdTipoMoneda', 'Numero as CorrelativoDocumento', 'Estado', DB::RAW("'Resumen Baja de boleta' Documento, 'Baja' TipoDocumento"))
                ->where('Estado', 'Resumen Pendiente')
                ->where('IdSucursal', $idSucursal)
                ->where('TipoResumen', 3)
                ->whereBetween('FechaEmitida', [$fechaInicial, $fechaFinal])
                ->get();

            return $datos = $resumenDiarioBoletasPendientes->concat($resumenDiarioNotaCredito)->concat($resumenDiarioBajaPendiente);
        }

        if ($idDocumento == 2) {
            $facturasPendiente = DB::table('ventas')
                ->select('FechaCreacion', 'IdTipoMoneda', 'Estado', DB::RAW("'Factura' Documento, 'Pendiente' TipoDocumento, concat_ws('-', ventas.Serie, ventas.Numero) as CorrelativoDocumento"))
                ->where('IdSucursal', $idSucursal)
                ->where('Estado', 'Pendiente')
                ->where('IdTipoComprobante', $idDocumento)
                ->whereBetween('FechaCreacion', [$fechaInicial, $fechaFinal])
                ->get();

            $notasCreditoPendiente = DB::table('nota_credito_debito')
                ->select('FechaCreacion', 'IdTipoMoneda', 'Estado', DB::RAW("'Nota de Credito' Documento, 'NotaCredito' TipoDocumento , concat_ws('-', Serie, Numero) as CorrelativoDocumento"))
                ->where('IdSucursal', $idSucursal)
                ->where('Estado', 'Pendiente')
                ->where('IdDocModificado', $idDocumento)
                ->whereBetween('FechaCreacion', [$fechaInicial, $fechaFinal])
                ->get();

            $bajaFacturasPendiente = DB::table('baja_documentos')
                ->select('FechaEnviada as FechaCreacion', 'IdTipoMoneda', 'baja_documentos.Estado', DB::RAW("'Baja de Factura' Documento, 'Baja' TipoDocumento , concat_ws('-', ventas.Serie, ventas.Numero) as CorrelativoDocumento"))
                ->join('ventas', 'baja_documentos.IdVentas', '=', 'ventas.IdVentas')
                ->where('baja_documentos.IdSucursal', $idSucursal)
                ->where('baja_documentos.Estado', 'Baja Pendiente')
                ->where('baja_documentos.TipoDocumento', $idDocumento)
                ->whereBetween('FechaEnviada', [$fechaInicial, $fechaFinal])
                ->get();
            return $datos = $facturasPendiente->concat($notasCreditoPendiente)->concat($bajaFacturasPendiente);
        }

        if ($idDocumento == 3) {
            $datos = DB::table('guia_remision')
                ->select('FechaCreacion', 'Estado', DB::RAW("'Baja de Guia' Documento, 'Baja' TipoDocumento , concat_ws('-', Serie, Numero) as CorrelativoDocumento"))
                ->where('Estado', 'Pendiente')
                ->where('IdSucursal', $idSucursal)
                ->whereBetween('FechaCreacion', [$fechaInicial, $fechaFinal])
                ->get();
            return $datos;
        }

        if ($idDocumento == 4) {
            $datosBoletasPendientes = DB::table('ventas')
                ->select('FechaCreacion', 'IdTipoMoneda', 'Estado', DB::RAW("'Boleta' Documento, 'Pendiente' TipoDocumento , concat_ws('-', Serie, Numero) as CorrelativoDocumento"))
                ->where('Estado', 'Pendiente')
                ->where('IdTipoComprobante', 1)
                ->where('IdSucursal', $idSucursal)
                ->whereBetween('FechaCreacion', [$fechaInicial, $fechaFinal])
                ->get();
            return $datos = $datosBoletasPendientes;
        }

    }
}
