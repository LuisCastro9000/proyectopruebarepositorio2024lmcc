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
use App\Exports\ExcelReporteComprasProductos;

class ReporteProductoController extends Controller
{
    public function index(Request $req){
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $tipoMoneda = 0;
        $producto = 0;
        $_producto = 0;
        $tipoPago = 0;
        $fecha = 5;
        $fechaIni = '';
        $fechaFin = '';
        $ini='';
        $fin='';
        $datos = [];
        $precioCostoXProveedor = "";
        $fechas = $loadDatos->getFechaFiltro(5, null, null);
        $estadoProductos = 1;
        $reporteProductos = DB::select('call sp_getComprasProductos(?, ?, ?, ?, ?)',array($idSucursal, 0, 0, $fechas[0], $fechas[1]));
        $cantCompras = collect($reporteProductos)->where('Estado', 'Registrado')->count();
        for($i=0; $i<count($reporteProductos); $i++){
            $productos = $loadDatos->getProductosComprados($reporteProductos[$i]->IdCompras);
            $reporteProductos[$i]->Productos = $productos;
        }
        // $promedioCostoCompra = collect($reporteProductos)->avg('Total');

        // Datos para los graficos de los productos mas comprados en soles y dolares
        $reporteProductoMasComprado =collect( $loadDatos->getComprasProductosFiltrados($idSucursal, $producto, $tipoPago, $fecha, $fechas[0], $fechas[1], "compras_articulo.IdArticulo"));
        // dd($reporteProductoMasComprado );
        $reporteProductoMasComprado = $reporteProductoMasComprado->where('Estado', 'Registrado')->sortByDesc('totalProductos');
        $reporteProductoMasCompradoSoles = $reporteProductoMasComprado->where('IdTipoMoneda', 1);
        $reporteProductoMasCompradoEnDolares = $reporteProductoMasComprado->where('IdTipoMoneda', 2);
        $nombresDeProductosMasComprados = $reporteProductoMasCompradoSoles->pluck('Descripcion');
        $cantidadDeProductosMasComprados = $reporteProductoMasCompradoSoles->pluck('totalProductos');
        $nombresDeProductosMasCompradosEnDolares = $reporteProductoMasCompradoEnDolares->pluck('Descripcion');
        $cantidadDeProductosMasCompradosEnDolares = $reporteProductoMasCompradoEnDolares->pluck('totalProductos');
        //  Fin


        $permisos = $loadDatos->getPermisos($idUsuario);

		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);

        // $productos = $this->getProductosComprados($idSucursal);
        $productos = $loadDatos->getProductos($idSucursal);

        $productos  = collect($productos);
        $productosSoles = $productos->where("IdTipoMoneda", 1)->values();
        $productosDolares = $productos->where("IdTipoMoneda", 2)->values();

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['reporteProductos' => $reporteProductos, 'productos' => $productos, 'producto' => $producto, '_producto' => $_producto, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini'=>$ini, 'fin'=>$fin, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles,
         'precioCostoXproveedor'  =>  $precioCostoXProveedor, 'datos' =>$datos, 'tipoMoneda'=> $tipoMoneda,  'estadoProductos' => $estadoProductos,
        'nombresDeProductosMasComprados' => $nombresDeProductosMasComprados, 'cantidadDeProductosMasComprados' => $cantidadDeProductosMasComprados, 'productosDolares'=>$productosDolares, 'productosSoles'=>$productosSoles,
        'nombresDeProductosMasCompradosEnDolares' => $nombresDeProductosMasCompradosEnDolares, 'cantidadDeProductosMasCompradosEnDolares' => $cantidadDeProductosMasCompradosEnDolares, 'reporteProductoMasCompradoSoles' =>$reporteProductoMasCompradoSoles, 'reporteProductoMasCompradoEnDolares'=> $reporteProductoMasCompradoEnDolares, 'cantCompras' => $cantCompras ];
        // return view('reportes/compras/reporteProductos', $array);
        return view('reportes/compras/ReporteComprasProducto', $array);
    }

    private function getProductos($idSucursal)
    {
        try {
            $productos = DB::table('articulo')
                ->select('IdArticulo', 'Descripcion', 'IdTipoMoneda')
                ->where('IdTipo', 1)
                ->where('articulo.IdSucursal', $idSucursal)
                ->where('articulo.Estado', 'E')
                ->orderBy('articulo.Descripcion', 'asc')
                ->get();
            return $productos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function store(Request $req) {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $producto = $req->producto;
        if($producto == null){
            $_producto = 0;
        }else{
            $_producto = $producto;
        }

        $tipoPago = $req->tipoPago;
        $fecha = $req->fecha;
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        $tipoMoneda = trim ($req->get('customRadio'));
        $estadoProductos = $req->customRadio;

        if($fecha == 9){
            if($fechaIni == null || $fechaFin == null){
                return back()->with('error','Completar las fechas para filtrar');
            }
            // if($fechaIni > $fechaFin){
            //     return back()->with('error','La fecha Inicial no puede ser mayor que la Final');
            // }

            $nuevaFechaIni =  str_replace("/","-",$fechaIni);
            $nuevaFechaFin =  str_replace("/","-", $fechaFin);

            $fechaEntera = strtotime($nuevaFechaIni);
            $anioFechaInicial = date("Y", $fechaEntera);
            $mesFechaInicial = date("m", $fechaEntera);
            $diaFechaInicial = date("d", $fechaEntera);

            $fechaEnteraFin = strtotime($nuevaFechaFin);
            $anioFechaFin = date("Y", $fechaEnteraFin);
            $mesFechaFin = date("m", $fechaEnteraFin);
            $diaFechaFin = date("d", $fechaEnteraFin);

            if(  $anioFechaInicial > $anioFechaFin ){
                return back()->with('error','El Año inicial no puede ser mayor que el Año Final');
            }
            if( $mesFechaInicial > $mesFechaFin ){
                return back()->with('error','El Mes Inicial no puede ser mayor que el Mes Final');
            }
            if( $diaFechaInicial > $diaFechaFin && $mesFechaInicial >= $mesFechaFin){
                return back()->with('error','El Día Inicial no puede ser mayor que el Día Final');
            }
        }
        $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);

		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
		$fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $reporteProductos = $loadDatos->getComprasProductosFiltrados($idSucursal, $producto, $tipoPago, $fecha, $fechas[0], $fechas[1], "compras.IdCompras");
        $cantCompras = collect($reporteProductos)->where('Estado', 'Registrado')->count();
        for($i=0; $i<count($reporteProductos); $i++){
            $productos = $loadDatos->getProductosComprados($reporteProductos[$i]->IdCompras);
            $reporteProductos[$i]->Productos = $productos;
        }

        $ini= str_replace('/','-', $fechaIni);
        $fin= str_replace('/','-', $fechaFin);

        // Datos para la comparacion de precios
        $reporte = collect( $loadDatos->getComprasProductosFiltrados($idSucursal, $producto, $tipoPago, $fecha, $fechas[0], $fechas[1], "compras.IdCompras"))->where('Estado', 'Registrado');
        $promedioCostoCompra = collect($reporte)->avg('PrecioCosto');
        // dd( $promedioCostoCompra );
        $nombreDeProveedor = $reporte->pluck('Nombres');
        $precioCostoXproveedor = $reporte->pluck( 'PrecioCosto');
        $fechaDeComprasXproveedor = $reporte->pluck( 'fechaCompras');
        $nombreDelProductoComprado = $reporte->pluck('Descripcion')->first();
        $idTipoMonedaProducto = $reporte->pluck('IdTipoMoneda')->first();
        $datos = [];
        $datosDolares = [];
        for ($i=0; $i < count($nombreDeProveedor) ; $i++) {
            for ($i=0; $i < count($fechaDeComprasXproveedor) ; $i++) {
               $datos[] = $nombreDeProveedor[$i]  .' => F. Compra '.$fechaDeComprasXproveedor[$i] ;
            }
        }
        // ----------------------------------------------------------------------------------------------------------------------------------
        // Fin

        // Datos para los graficos de los productos mas comprados en soles y dolares
        $reporteProductoMasComprado =collect( $loadDatos->getComprasProductosFiltrados($idSucursal, $producto, $tipoPago, $fecha, $fechas[0], $fechas[1], "compras_articulo.IdArticulo"));
        $reporteProductoMasComprado = $reporteProductoMasComprado->where('Estado', 'Registrado')->sortByDesc('totalProductos');
        $reporteProductoMasCompradoSoles = $reporteProductoMasComprado->where('IdTipoMoneda', 1);
        $reporteProductoMasCompradoEnDolares = $reporteProductoMasComprado->where('IdTipoMoneda', 2);
        $nombresDeProductosMasComprados = $reporteProductoMasCompradoSoles->pluck('Descripcion');
        $cantidadDeProductosMasComprados = $reporteProductoMasCompradoSoles->pluck('totalProductos');
        $nombresDeProductosMasCompradosEnDolares = $reporteProductoMasCompradoEnDolares->pluck('Descripcion');
        $cantidadDeProductosMasCompradosEnDolares = $reporteProductoMasCompradoEnDolares->pluck('totalProductos');
         //  Fin

        //  dd($reporteProductoMasComprado);

        // Cargar Datos en select
        $productos = $loadDatos->getProductos($idSucursal);
        $productos  = collect($productos);
        $productosSoles = $productos->where("IdTipoMoneda", 1)->values();
        $productosDolares = $productos->where("IdTipoMoneda", 2)->values();
        // Fin
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['reporteProductos' => $reporteProductos, 'productos' => $productos, 'producto' => $producto, '_producto' => $_producto, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini'=>$ini, 'fin'=>$fin, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles,
        'nombreDeProveedor' =>$nombreDeProveedor,  'precioCostoXproveedor'  => $precioCostoXproveedor, 'datos' =>$datos, 'tipoMoneda'=> $tipoMoneda,  'IdTipoMonedaProducto'=>  $idTipoMonedaProducto, 'estadoProductos' => $estadoProductos,
        'nombresDeProductosMasComprados' => $nombresDeProductosMasComprados, 'cantidadDeProductosMasComprados' => $cantidadDeProductosMasComprados,
        'nombresDeProductosMasCompradosEnDolares' => $nombresDeProductosMasCompradosEnDolares, 'cantidadDeProductosMasCompradosEnDolares' => $cantidadDeProductosMasCompradosEnDolares,
        'nombreDelproductoComprado'=> $nombreDelProductoComprado, 'reporte'=> $reporte,
        'reporteProductoMasCompradoSoles' =>$reporteProductoMasCompradoSoles, 'reporteProductoMasCompradoEnDolares'=> $reporteProductoMasCompradoEnDolares, 'productosDolares'=>$productosDolares, 'productosSoles'=>$productosSoles, 'cantCompras' =>$cantCompras, 'promedioCostoCompra' => $promedioCostoCompra ];
        // return view('reportes/compras/reporteProductos', $array);
        return view('reportes/compras/ReporteComprasProducto', $array);
    }

    public function exportExcel( $producto=null, $tipoPago=null, $fecha=null, $ini=null, $fin=null)
    {

     $loadDatos = new DatosController();
      $idUsuario = Session::get('idUsuario');
      $idSucursal = Session::get('idSucursal');

      $fechaIni= str_replace('-','/', $ini);
      $fechaFin= str_replace('-','/', $fin);

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        //dd($fechas);

        $reporteProductos = $this->getComprasProductosExportarExcel($idSucursal, $producto, $tipoPago, $fecha, $fechas[0], $fechas[1]);
        // $reporteProductos = DB::select('call sp_getComprasProductos(?, ?, ?, ?, ?)',array($idSucursal, $producto, $tipoPago, $fechas[0], $fechas[1]));
        // for($i=0; $i<count($reporteProductos); $i++){
        //     $productos = $loadDatos->getProductosComprados($reporteProductos[$i]->IdCompras);
        //     $reporteProductos[$i]->Productos = $productos;
        // }
        // dd($reporteProductos);
      return Excel::download(new ExcelReporteComprasProductos($reporteProductos), 'Reporte Compras - Productos.xlsx');
    }


    public function cargarSelect(Request $req){
        if($req->ajax()){
			$idSucursal = Session::get('idSucursal');
            $idTipoMoneda = $req->idMoneda;
            $productos = DB::table('compras_articulo')
            ->join('articulo', 'compras_articulo.IdArticulo', '=', 'articulo.IdArticulo')
            ->join('compras', 'compras_articulo.IdCompras', '=', 'compras.IdCompras')
            ->select('articulo.IdArticulo', 'articulo.Descripcion')
            ->where('compras.IdSucursal', $idSucursal)
            ->where('articulo.Estado', 'E')
            ->where('compras.IdTipoMoneda', $idTipoMoneda)
            ->orderBy('articulo.Descripcion', 'asc')
            ->groupBy('articulo.IdArticulo')
            ->get();

            // $productos = $this->getProductosComprados($idSucursal, $idTipoMoneda);
            // $productos = collect($productos)->where('IdTipoMoneda',  $req->idMoneda);
            // $productos = $productos->all();

			return Response([$productos]);
		}
    }

    private function getProductosComprados($idSucursal )
    {
        try {
            $productos = DB::table('compras_articulo')
                ->join('articulo', 'compras_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                ->join('compras', 'compras_articulo.IdCompras', '=', 'compras.IdCompras')
                ->select('articulo.IdArticulo', 'articulo.Descripcion')
                ->where('compras.IdSucursal', $idSucursal)
                ->where('articulo.Estado', 'E')
                ->orderBy('articulo.Descripcion', 'asc')
                ->groupBy('articulo.IdArticulo')
                ->get();
            return $productos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getComprasProductosExportarExcel($idSucursal, $producto, $tipoPago, $fecha, $fechaIni, $fechaFin){
        try{
            $loadDatos = new DatosController();
            //$fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            if($producto == 0){
                if($tipoPago == 0){
                    $compras= DB::table('compras_articulo')
                        ->join('articulo','compras_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                        ->join('compras','compras_articulo.IdCompras', '=', 'compras.IdCompras')
                        ->join('usuario','compras.IdCreacion', '=', 'usuario.IdUsuario')
                        ->join('proveedor','compras.IdProveedor', '=', 'proveedor.IdProveedor')
                        // Agrege en el Select el atributo -->NumeroDocumento
                        ->select('compras.*', 'proveedor.Nombre as Nombres','proveedor.NumeroDocumento', 'compras_articulo.PrecioCosto', 'compras_articulo.Cantidad', 'articulo.Descripcion', 'usuario.Nombre as Usuario')
                        ->where('compras.IdSucursal', $idSucursal)
                        ->whereBetween('compras.FechaCreacion', [$fechaIni, $fechaFin])
                        ->get();
                    return $compras;
                }else{
                    $compras= DB::table('compras_articulo')
                        ->join('articulo','compras_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                        ->join('compras','compras_articulo.IdCompras', '=', 'compras.IdCompras')
                        ->join('usuario','compras.IdCreacion', '=', 'usuario.IdUsuario')
                        ->join('proveedor','compras.IdProveedor', '=', 'proveedor.IdProveedor')
                        // Agrege en el Select el atributo -->NumeroDocumento
                        ->select('compras.*', 'proveedor.Nombre as Nombres','proveedor.NumeroDocumento', 'compras_articulo.PrecioCosto', 'compras_articulo.Cantidad', 'articulo.Descripcion', 'usuario.Nombre as Usuario')
                        ->where('compras.IdSucursal', $idSucursal)
                        ->where('compras.IdTipoPago', $tipoPago)
                        ->whereBetween('compras.FechaCreacion', [$fechaIni, $fechaFin])
                        ->get();
                    return $compras;
                }
            }else{
                if($tipoPago == 0){
                    $compras= DB::table('compras_articulo')
                        ->join('articulo','compras_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                        ->join('compras','compras_articulo.IdCompras', '=', 'compras.IdCompras')
                        ->join('usuario','compras.IdCreacion', '=', 'usuario.IdUsuario')
                        ->join('proveedor','compras.IdProveedor', '=', 'proveedor.IdProveedor')
                        // Agrege en el Select el atributo -->NumeroDocumento
                        ->select('compras.*', 'proveedor.Nombre as Nombres','proveedor.NumeroDocumento', 'compras_articulo.PrecioCosto', 'compras_articulo.Cantidad', 'articulo.Descripcion', 'usuario.Nombre as Usuario')
                        ->where('compras.IdSucursal', $idSucursal)
                        ->where('articulo.IdArticulo', $producto)
                        ->whereBetween('compras.FechaCreacion', [$fechaIni, $fechaFin])
                        ->get();
                    return $compras;
                }else{
                    $compras= DB::table('compras_articulo')
                        ->join('articulo','compras_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                        ->join('compras','compras_articulo.IdCompras', '=', 'compras.IdCompras')
                        ->join('usuario','compras.IdCreacion', '=', 'usuario.IdUsuario')
                        ->join('proveedor','compras.IdProveedor', '=', 'proveedor.IdProveedor')
                        // Agrege en el Select el atributo -->NumeroDocumento
                        ->select('compras.*', 'proveedor.Nombre as Nombres','proveedor.NumeroDocumento', 'compras_articulo.PrecioCosto', 'compras_articulo.Cantidad', 'articulo.Descripcion', 'usuario.Nombre as Usuario')
                        ->where('compras.IdSucursal', $idSucursal)
                        ->where('articulo.Descripcion', $producto)
                        ->where('compras.IdTipoPago', $tipoPago)
                        ->whereBetween('compras.FechaCreacion', [$fechaIni, $fechaFin])
                        ->get();
                    return $compras;
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

}


