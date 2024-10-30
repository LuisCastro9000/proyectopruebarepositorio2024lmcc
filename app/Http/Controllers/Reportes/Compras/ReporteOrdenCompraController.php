<?php

namespace App\Http\Controllers\Reportes\Compras;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use App\Http\Controllers\Reportes\AjusteFechasReportesController;
use Session;
use DB;
use Excel;
use DateTime;
use Carbon\Carbon;
use App\Exports\ExcelReporteOrdenCompra;

class ReporteOrdenCompraController extends Controller {
    public function index( Request $req ) {
        if ( $req->session()->has( 'idUsuario' ) ) {
            $idUsuario = Session::get( 'idUsuario' );
        } else {
            Session::flush();
            return redirect( '/' )->with( 'out', 'Sesión de usuario Expirado' );
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get( 'idSucursal' );
        $permisos = $loadDatos->getPermisos( $idUsuario );
        $subpermisos = $loadDatos->getSubPermisos( $idUsuario );
        $subniveles = $loadDatos->getSubNiveles( $idUsuario );
        $usuarioSelect = $loadDatos->getUsuarioSelect( $idUsuario );
        $modulosSelect = $loadDatos->getModulosSelect( $usuarioSelect->CodigoCliente );
        $proveedores = $loadDatos->getProveedores( $idSucursal );

        $fecha = 5;
        $fechaIni = '';
        $fechaFin = '';
        $ini = '';
        $fin = '';
        $idProveedor = 0;
        $fechas = $loadDatos->getFechaFiltro( $fecha, $fechaIni, $fechaFin );
        $listaOrdenCompra = $this->getOrdenesCompras( $idProveedor, $fechas[ 0 ], $fechas[ 1 ], $idSucursal, );
        $listaOrdenCompraSoles = $listaOrdenCompra->where( 'IdTipoMoneda', 1 );
        $cantOrdenCompraPendientesSoles = $listaOrdenCompraSoles->where( 'Estado', 'Pendiente' )->count();
        $cantOrdenCompraFacturadosSoles = $listaOrdenCompraSoles->where( 'Estado', 'Facturada' )->count();

        $listaOrdenCompraDolares = $listaOrdenCompra->where( 'IdTipoMoneda', 2 );
        $cantOrdenCompraPendientesDolares = $listaOrdenCompraDolares->where( 'Estado', 'Pendiente' )->count();
        $cantOrdenCompraFacturadosDolares = $listaOrdenCompraDolares->where( 'Estado', 'Facturada' )->count();
        // dd( $listaOrdenCompra );

        $array = [ 'permisos' => $permisos, 'modulosSelect' => $modulosSelect,  'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'listaOrdenCompraSoles'=> $listaOrdenCompraSoles,  'listaOrdenCompraDolares'=> $listaOrdenCompraDolares,  'proveedores' =>$proveedores, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' =>$ini, 'fin' =>$fin,  'idProveedor' =>  $idProveedor, 'cantOrdenCompraPendientesSoles' => $cantOrdenCompraPendientesSoles, 'cantOrdenCompraFacturadosSoles' =>$cantOrdenCompraFacturadosSoles, 'cantOrdenCompraPendientesDolares' => $cantOrdenCompraPendientesDolares, 'cantOrdenCompraFacturadosDolares' =>$cantOrdenCompraFacturadosDolares ];

        return view( 'reportes/compras/ReporteOrdenesCompras', $array );
    }

    public function filtraOrdenCompra( Request $req ) {
        if ( $req->session()->has( 'idUsuario' ) ) {
            $idUsuario = Session::get( 'idUsuario' );
        } else {
            Session::flush();
            return redirect( '/' )->with( 'out', 'Sesión de usuario Expirado' );
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get( 'idSucursal' );
        $permisos = $loadDatos->getPermisos( $idUsuario );
        $subpermisos = $loadDatos->getSubPermisos( $idUsuario );
        $subniveles = $loadDatos->getSubNiveles( $idUsuario );
        $usuarioSelect = $loadDatos->getUsuarioSelect( $idUsuario );
        $modulosSelect = $loadDatos->getModulosSelect( $usuarioSelect->CodigoCliente );
        $proveedores = $loadDatos->getProveedores( $idSucursal );

        $idProveedor = $req->proveedor;
        $fecha = $req->fecha;
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        $fechas = $loadDatos->getFechaFiltro( $fecha, $fechaIni, $fechaFin );
        $listaOrdenCompra = $this->getOrdenesCompras( $idProveedor, $fechas[ 0 ], $fechas[ 1 ], $idSucursal, );
        $listaOrdenCompraSoles = $listaOrdenCompra->where( 'IdTipoMoneda', 1 );
        $cantOrdenCompraPendientesSoles = $listaOrdenCompraSoles->where( 'Estado', 'Pendiente' )->count();
        $cantOrdenCompraFacturadosSoles = $listaOrdenCompraSoles->where( 'Estado', 'Facturada' )->count();

        $listaOrdenCompraDolares = $listaOrdenCompra->where( 'IdTipoMoneda', 2 );
        $cantOrdenCompraPendientesDolares = $listaOrdenCompraDolares->where( 'Estado', 'Pendiente' )->count();
        $cantOrdenCompraFacturadosDolares = $listaOrdenCompraDolares->where( 'Estado', 'Facturada' )->count();

        $montoOrdenComprasFacturadasSoles = $listaOrdenCompraSoles->where( 'Estado', 'Facturada' )->sum( 'Total' );
        $montoOrdenComprasPendientesSoles = $listaOrdenCompraSoles->where( 'Estado', 'Pendiente' )->sum( 'Total' );

        $montoOrdenComprasFacturadasDolares = $listaOrdenCompraDolares->where( 'Estado', 'Facturada' )->sum( 'Total' );
        $montoOrdenComprasPendientesDolares = $listaOrdenCompraDolares->where( 'Estado', 'Pendiente' )->sum( 'Total' );
        // dd( $cantOrdenCompraPendientes );
        $ini = str_replace( '/', '-', $fechaIni );
        $fin = str_replace( '/', '-', $fechaFin );
        $array = [ 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'listaOrdenCompraSoles'=> $listaOrdenCompraSoles,  'listaOrdenCompraDolares'=> $listaOrdenCompraDolares, 'proveedores' =>$proveedores,  'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' =>$ini, 'fin' =>$fin,  'idProveedor' =>  $idProveedor, 'montoOrdenComprasPendientesSoles' =>$montoOrdenComprasPendientesSoles, 'montoOrdenComprasFacturadasSoles' => $montoOrdenComprasFacturadasSoles, 'montoOrdenComprasPendientesDolares' =>$montoOrdenComprasPendientesDolares, 'montoOrdenComprasFacturadasDolares' => $montoOrdenComprasFacturadasDolares,
        'cantOrdenCompraPendientesSoles' => $cantOrdenCompraPendientesSoles, 'cantOrdenCompraFacturadosSoles' =>$cantOrdenCompraFacturadosSoles, 'cantOrdenCompraPendientesDolares' => $cantOrdenCompraPendientesDolares, 'cantOrdenCompraFacturadosDolares' =>$cantOrdenCompraFacturadosDolares ];
        return view( 'reportes/compras/ReporteOrdenesCompras', $array );
    }

    public function getOrdenesCompras( $idProveedor, $fechaInicial, $fechaFinal, $idSucursal ) {
        if ( $idProveedor == 0 ) {
            $resultado = DB::table( 'orden_compra' )
            ->join( 'proveedor', 'orden_compra.IdProveedor', '=', 'proveedor.IdProveedor' )
            ->select( 'orden_compra.*',  'proveedor.Nombre as NombreProveedor', 'proveedor.NumeroDocumento', DB::raw( "DATE_FORMAT(orden_compra.FechaRecepcion, '%Y-%m-%d' )AS FechaRecepcion" ) )
            ->where( 'orden_compra.IdSucursal', $idSucursal )
            ->whereBetween( 'orden_compra.FechaEmision', [ $fechaInicial, $fechaFinal ] )
            ->orderBy( 'orden_compra.IdOrdenCompra', 'desc' )
            ->get();
            return $resultado;
        } else {
            $resultado = DB::table( 'orden_compra' )
            ->join( 'proveedor', 'orden_compra.IdProveedor', '=', 'proveedor.IdProveedor' )
            ->select( 'orden_compra.*',  'proveedor.Nombre as NombreProveedor', 'proveedor.NumeroDocumento', DB::raw( "DATE_FORMAT(orden_compra.FechaRecepcion, '%Y-%m-%d' )AS FechaRecepcion" ) )
            ->where( 'orden_compra.IdProveedor', $idProveedor )
            ->where( 'orden_compra.IdSucursal', $idSucursal )
            ->whereBetween( 'orden_compra.FechaEmision', [ $fechaInicial, $fechaFinal ] )
            ->orderBy( 'orden_compra.IdOrdenCompra', 'desc' )
            ->get();
            return $resultado;
        }
    }

    public function exportarExcelOrdenCompra( $idProveedor,  $fecha = null, $ini = null, $fin = null ) {
        $loadDatos = new DatosController();
        $idSucursal = Session::get( 'idSucursal' );

        $fechaIni = str_replace( '-', '/', $ini );
        $fechaFin = str_replace( '-', '/', $fin );
        $fechas = $loadDatos->getFechaFiltro( $fecha, $fechaIni, $fechaFin );
        $reporteOrdenesCompras = $this->getItemsOrdenCompra( $idProveedor, $fecha,  $fechas[ 0 ], $fechas[ 1 ] );
        return Excel::download( new ExcelReporteOrdenCompra( $reporteOrdenesCompras ), 'ReporteOrdenCompras.xlsx' );
    }

    public function getItemsOrdenCompra( $idProveedor, $fecha, $fechaInicial, $fechaFinal ) {
        try {
            if ( $idProveedor == 0 ) {
                $ordenCompra = DB::table( 'detalle_orden_compra' )
                ->join( 'orden_compra', 'detalle_orden_compra.IdOrdenCompra', '=', 'orden_compra.IdOrdenCompra' )
                ->join( 'articulo', 'detalle_orden_compra.IdArticulo', '=', 'articulo.IdArticulo' )
                ->join( 'unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida' )
                ->join( 'proveedor', 'orden_compra.IdProveedor', '=', 'proveedor.IdProveedor' )
                ->select( 'orden_compra.*', 'detalle_orden_compra.CodigoArticulo', 'articulo.Descripcion', 'unidad_medida.Nombre as UniMedida', 'proveedor.Nombre as NombreProveedor', 'proveedor.NumeroDocumento', 'detalle_orden_compra.Cantidad', 'detalle_orden_compra.PrecioCosto' )
                ->whereBetween( 'orden_compra.FechaEmision', [ $fechaInicial, $fechaFinal ] )
                ->get();

            } else {
                $ordenCompra = DB::table( 'detalle_orden_compra' )
                ->join( 'orden_compra', 'detalle_orden_compra.IdOrdenCompra', '=', 'orden_compra.IdOrdenCompra' )
                ->join( 'articulo', 'detalle_orden_compra.IdArticulo', '=', 'articulo.IdArticulo' )
                ->join( 'unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida' )
                ->join( 'proveedor', 'orden_compra.IdProveedor', '=', 'proveedor.IdProveedor' )
                ->select( 'orden_compra.*', 'detalle_orden_compra.CodigoArticulo', 'articulo.Descripcion', 'unidad_medida.Nombre as UniMedida', 'proveedor.Nombre as NombreProveedor', 'proveedor.NumeroDocumento', 'detalle_orden_compra.Cantidad', 'detalle_orden_compra.PrecioCosto' )
                ->where( 'orden_compra.IdProveedor', $idProveedor )
                ->whereBetween( 'orden_compra.FechaEmision', [ $fechaInicial, $fechaFinal ] )
                ->get();
            }
            return $ordenCompra;
        } catch ( Exception $ex ) {
            echo $ex->getMessage();
        }
    }
}

