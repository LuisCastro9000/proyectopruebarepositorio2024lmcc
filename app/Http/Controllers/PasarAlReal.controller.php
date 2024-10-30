<?php
// pruebasoporte@autocontrol.pe
// AMORTIGUADOR
namespace App\Http\Controllers\Reportes\Ventas;

use App\Exports\ExcelReporteVentasProducto;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DateTime;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class ReporteProductoController extends Controller
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
        $fechas = $loadDatos->getFechaFiltro(1, null, null);

        // Poner todas las variables ($producto, $tipoPago, $fecha) con un valor para que las vistas carguen con la opcion elegida
        $producto = 0;
        $tipoPago = 0;
        $fecha = 5;
        $fechaIni = '';
        $fechaFin = '';
        $otherProd = '';
        $ini = '';
        $fin = '';
        $arrayProductos = [];
        $arrayVentas = [];
        $arrayCantidad = [];
        $arrayUnicoProducto = [];
        $arrayfecha = [];
        $arrayArticulo = [];
        // $idCategoria = 0;
        $correo = "0";
        $loadDatos = new DatosController();
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        // dd($usuarioSelect );
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        //$fechasReportes = new AjusteFechasReportesController();
        $idSucursal = Session::get('idSucursal');

        $fechas = $loadDatos->getFechaFiltro(5, null, null);
        // SE HA AGREGADO IDCATEGORIA EN EL PA
        $reporteProductos = DB::select('call sp_getVentasProductos(?, ?, ?, ?, ?)', array($idSucursal, $producto, $tipoPago, $fechas[0], $fechas[1]));

        // $productosGrafico = $loadDatos->grafgetVentasProductosFiltrados($idSucursal, $producto, $tipoPago, 5, $fechas[0], $fechas[1]);
        $productosGrafico = DB::select('call  sp_getVentasProductosFiltrados(?, ?, ?, ?, ?)', array($idSucursal, $producto, $tipoPago, $fechas[0], $fechas[1]));
        $productos = $loadDatos->getProductos($idSucursal);
        $servicios = $loadDatos->getServicios($idSucursal);
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        for ($i = 0; $i < count($reporteProductos); $i++) {
            $_productos = $loadDatos->getProductosVendidos($reporteProductos[$i]->IdVentas);
            $reporteProductos[$i]->Productos = $_productos;
        }

        if (count($productosGrafico) >= 1) {
            $i = 0;
            foreach ($productosGrafico as $articulo) {
                $arrayProductos[$i] = "'$articulo->Descripcion'";
                $arrayVentas[$i] = $articulo->total;
                $arraycantidad[$i] = $articulo->totalProductos;
                $i++;
            }
        }
        $arrayFechasFiltros = [];

        //  CODIGO PARA MOSTRAR LOS DATOS EN LOS INPUT EN LA SECCION ENVIO DE CORREO
        $resultadoFiltro = DB::table('filtros_correo')->select('IdCategoria', 'NombreCorreoExcel', 'NombreCorreoXml', 'NombreCategoria', 'Estado')->get();
        $nombreCorreoExcel = $resultadoFiltro->pluck('NombreCorreoExcel')->first();
        $nombreCorreoXml = $resultadoFiltro->pluck('NombreCorreoXml')->first();
        $nombreCategoria = $resultadoFiltro->pluck('NombreCategoria')->first();
        $idCategoria = $resultadoFiltro->pluck('IdCategoria')->first();
        $checkEstado = $resultadoFiltro->pluck('Estado')->first();
        // FIN
        // CODIGO PARA HABILITAR LA TAREA
        $estadoTarea = DB::table("detalle_tarea")->select('*')->where("idUsuario", $idUsuario)->get();
        $estadoTarea = $estadoTarea->pluck("EstadoTarea")->first();
        if ($estadoTarea == null) {
            $estadoTarea = "Desactivado";
        }
        // FIN

        // NueVo codigo obtener lista de categorias
        $listaCategoria = $loadDatos->getCategoriasSucursal($idSucursal);
        // Fin

        $array = ['grafArticulo' => $arrayArticulo, 'grafFecha' => $arrayfecha, 'grafUnicoProduc' => $arrayUnicoProducto, 'productosGrafico' => $productosGrafico, 'grafCliente' => $arrayProductos, 'grafTotal' => $arrayVentas, 'arrayCantidad' => $arrayCantidad, 'reporteProductos' => $reporteProductos,
            'arrayFechasFiltros' => $arrayFechasFiltros, 'productos' => $productos, 'producto' => $producto, 'otherProd' => $otherProd, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'listaCategoria' => $listaCategoria, 'idCategoria' => $idCategoria, 'correo' => $correo, 'nombreCorreoExcel' => $nombreCorreoExcel, 'nombreCorreoXml' => $nombreCorreoXml, 'checkEstado' => $checkEstado, 'nombreCategoria' => $nombreCategoria, 'estadoTarea' => $estadoTarea, 'resultadoFiltro' => $resultadoFiltro, 'servicios' => $servicios];
        return view('reportes/ventas/reporteProductos', $array);
    }

    // Ya esta Copiado
    public function guardarDatosCorreoExcel(Request $req)
    {
        try {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $idSucursal = $idSucursal = Session::get('idSucursal');
            $idCategoria = $req->idCategoria;
            $nombreCorreoExcel = $req->nombreCorreoExcel;
            $nombreCorreoXml = $req->nombreCorreoXml;
            $fechaCreacion = date('Y-m-d');
            $fechaCreacion = DateTime::createFromFormat('Y-m-d', $fechaCreacion);
            $fechaCreacion = $fechaCreacion->format("Y-m-d H:i:s");
            $checkActivarEnvio = $req->checkActivarEnvio;
            $listaCategoria = $loadDatos->getCategoriasSucursal($idSucursal);
            $nombreCategoria = $listaCategoria->where('IdCategoria', $idCategoria)->pluck("Nombre")->first();
            if ($checkActivarEnvio != null) {
                $checkActivarEnvio = "Activado";
            } else {
                $checkActivarEnvio = "Desactivado";
            }

            $resultado = DB::table('filtros_correo')->select('filtros_correo.*')->get();
            $arrayDatos = ['IdCategoria' => $idCategoria, 'NombreCategoria' => $nombreCategoria, 'NombreCorreoExcel' => $nombreCorreoExcel, 'NombreCorreoXml' => $nombreCorreoXml, 'Estado' => $checkActivarEnvio, 'FechaCreacion' => $fechaCreacion, 'IdSucursal' => $idSucursal, 'IdUsuario' => $idUsuario];

            if (count($resultado) >= 1) {
                DB::table('filtros_correo')->update($arrayDatos);
            } else {
                DB::table('filtros_correo')->insert($arrayDatos);
            }
            return redirect('/reportes/ventas/productos')->with('status', 'Se creo el filtro correctamente');
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function actualizarFiltros(Request $req)
    {
        if ($req->ajax()) {
            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
            $checkActivarEnvio = $req->checkActivarEnvio;
            if ($req->checkActivarEnvio == "Activado") {
                $array = ['Estado' => $checkActivarEnvio];
                DB::table('filtros_correo')
                    ->update($array);
                return Response(['succes', 'El envio de correo esta habilitado']);
            } else {
                $array = ['Estado' => $checkActivarEnvio];
                DB::table('filtros_correo')
                    ->update($array);
                return Response(['succes', 'El envio de correo esta Desabilitado']);
            }
        }
    }

    // Fin

    public function store(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $producto = $req->producto;
        $tipoPago = $req->tipoPago;
        $fecha = $req->fecha;
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        $correo = $req->correo;
        if ($correo == null) {
            $correo = 0;
        }
        $diferencia = null;
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
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $reporteProductos = DB::select('call sp_getVentasProductos(?, ?, ?, ?, ?)', array($idSucursal, $producto, $tipoPago, $fechas[0], $fechas[1]));
        $productosGraf = DB::select('call  sp_getVentasProductosFiltrados(?, ?, ?, ?, ?)', array($idSucursal, $producto, $tipoPago, $fechas[0], $fechas[1]));

        $grafProductosFiltrado = $this->grafgetVentasProductosFiltros($idSucursal, $producto, $tipoPago, $fecha, $fechas[0], $fechas[1], $diferencia);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $otherProd = $req->producto;
        $ini = str_replace('/', '-', $fechaIni);
        $fin = str_replace('/', '-', $fechaFin);
        $arrayProductos = [];
        $arrayVentas = [];
        $arrayCantidad = [];
        $arrayUnicoProducto = [];
        $arrayfecha = [];
        $arrayArticulo = [];
        $arrayFechasFiltros = [];
        if (count($productosGraf) >= 1) {
            $i = 0;
            foreach ($productosGraf as $articulo) {
                $arrayProductos[$i] = "'$articulo->Descripcion'";
                $arrayCantidad[$i] = $articulo->totalProductos;
                $arrayVentas[$i] = $articulo->total;
                $i++;
            }
        }
        for ($i = 0; $i < count($reporteProductos); $i++) {
            $productos = $loadDatos->getProductosVendidos($reporteProductos[$i]->IdVentas);
            $reporteProductos[$i]->Productos = $productos;
        }
        $productos = $loadDatos->getProductos($idSucursal);
        $servicios = $loadDatos->getServicios($idSucursal);

        $arrayMes = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];

        for ($i = 0; $i < count($grafProductosFiltrado); $i++) {
            if (($fecha >= 1 && $fecha <= 6) || ($fecha == 9 && $diferencia <= 31)) {
                array_push($arrayFechasFiltros, $grafProductosFiltrado[$i]->dia . ' ' . $arrayMes[$grafProductosFiltrado[$i]->mes - 1]);
            }
            if (($fecha == 7 || $fecha == 8 || $fecha == 0 || $fecha == 10) || ($fecha == 9 && $diferencia > 31)) {
                array_push($arrayFechasFiltros, $arrayMes[$grafProductosFiltrado[$i]->mes - 1] . ' ' . $grafProductosFiltrado[$i]->anio);
            }
            array_push($arrayUnicoProducto, $grafProductosFiltrado[$i]->total);
        }

        // Nuevo codigo
        $listaCategoria = $loadDatos->getCategoriasSucursal($idSucursal);
        // Fin
        //  CODIGO MOSTRAR LOS CORREOS EN LOS INPUT
        $resultadoFiltro = DB::table('filtros_correo')->select('IdCategoria', 'NombreCorreoExcel', 'NombreCorreoXml', 'NombreCategoria', 'Estado')->get();
        $resultadoFiltro = collect($resultadoFiltro);
        $nombreCorreoExcel = $resultadoFiltro->pluck('NombreCorreoExcel')->first();
        $nombreCorreoXml = $resultadoFiltro->pluck('NombreCorreoXml')->first();
        $nombreCategoria = $resultadoFiltro->pluck('NombreCategoria')->first();
        $idCategoria = $resultadoFiltro->pluck('IdCategoria')->first();
        $checkEstado = $resultadoFiltro->pluck('Estado')->first();
        // FIN
        // CODIGO PARA HABILITAR LA TAREA
        $estadoTarea = DB::table("detalle_tarea")->select('detalle_tarea.*')->where("idUsuario", $idUsuario)->get();
        $estadoTarea = $estadoTarea->pluck("EstadoTarea")->first();
        if ($estadoTarea == null) {
            $estadoTarea = "Desactivado";
        }
        // FIN
        $array = ['grafProductosFiltrado' => $grafProductosFiltrado, 'grafArticulo' => $arrayArticulo, 'grafFecha' => $arrayfecha, 'grafUnicoProduc' => $arrayUnicoProducto, 'productosGrafico' => $productosGraf, 'grafCliente' => $arrayProductos, 'grafTotal' => $arrayVentas, 'grafCantidad' => $arrayCantidad,
            'arrayFechasFiltros' => $arrayFechasFiltros, 'reporteProductos' => $reporteProductos, 'productos' => $productos, 'producto' => $producto, 'otherProd' => $otherProd, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'listaCategoria' => $listaCategoria, 'idCategoria' => $idCategoria, 'correo' => $correo, 'nombreCorreoExcel' => $nombreCorreoExcel, 'nombreCorreoXml' => $nombreCorreoXml, 'checkEstado' => $checkEstado, 'nombreCategoria' => $nombreCategoria, 'estadoTarea' => $estadoTarea, 'resultadoFiltro' => $resultadoFiltro, 'servicios' => $servicios];
        return view('reportes/ventas/reporteProductos', $array);
    }

    private function grafgetVentasProductosFiltros($idSucursal, $producto, $tipoPago, $fecha, $fechaIni, $fechaFin, $diferencia)
    {

        /*$resultado = DB::select('SELECT MONTH(FechaCreacion) AS mes, YEAR(FechaCreacion) AS anio, count(*) AS total FROM ventas where IdSucursal ='.$idSucursal.'
        GROUP BY YEAR(FechaCreacion), MONTH(FechaCreacion)
        ORDER BY YEAR(FechaCreacion) DESC, MONTH(FechaCreacion) DESC
        LIMIT 25');*/

        if (($fecha >= 1 && $fecha <= 6) || ($fecha == 9 && $diferencia <= 31)) {
            $condicion = 'MONTH(v.FechaCreacion), DAY(FechaCreacion)';
        }
        if (($fecha == 7 || $fecha == 8 || $fecha == 0 || $fecha == 10) || ($fecha == 9 && $diferencia > 31)) {
            $condicion = 'YEAR(v.FechaCreacion), MONTH(FechaCreacion)';
        }
        if ($producto == 0) {
            if ($tipoPago == 0) {
                $resultado = DB::table('ventas as v')
                    ->join('ventas_articulo as va', 'v.IdVentas', '=', 'va.IdVentas')
                    ->join('articulo as a', 'va.IdArticulo', '=', 'a.IdArticulo')
                    ->select(DB::raw('count(va.Cantidad) as total, a.Descripcion, DAY(v.FechaCreacion) as dia, MONTH(v.FechaCreacion) as mes, YEAR(v.FechaCreacion) AS anio'))
                    ->where('v.IdSucursal', $idSucursal)
                    ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
                    ->groupBy(DB::raw($condicion))
                    ->orderBy(DB::raw($condicion), 'asc')
                    ->get();
            } else {
                $resultado = DB::table('ventas as v')
                    ->join('ventas_articulo as va', 'v.IdVentas', '=', 'va.IdVentas')
                    ->join('articulo as a', 'va.IdArticulo', '=', 'a.IdArticulo')
                    ->select(DB::raw('count(va.Cantidad) as total, a.Descripcion, DAY(v.FechaCreacion) as dia, MONTH(v.FechaCreacion) as mes, YEAR(v.FechaCreacion) AS anio'))
                    ->where('v.IdSucursal', $idSucursal)
                    ->where('v.IdTipoPago', $tipoPago)
                    ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
                    ->groupBy(DB::raw($condicion))
                    ->orderBy(DB::raw($condicion), 'asc')
                    ->get();
            }
        } else {
            if ($tipoPago == 0) {
                $resultado = DB::table('ventas as v')
                    ->join('ventas_articulo as va', 'v.IdVentas', '=', 'va.IdVentas')
                    ->join('articulo as a', 'va.IdArticulo', '=', 'a.IdArticulo')
                    ->select(DB::raw('count(va.Cantidad) as total, a.Descripcion, DAY(v.FechaCreacion) as dia, MONTH(v.FechaCreacion) as mes, YEAR(v.FechaCreacion) AS anio'))
                    ->where('a.IdArticulo', $producto)
                    ->where('v.IdSucursal', $idSucursal)
                    ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
                    ->groupBy(DB::raw($condicion))
                    ->orderBy(DB::raw($condicion), 'asc')
                    ->get();
            } else {
                $resultado = DB::table('ventas as v')
                    ->join('ventas_articulo as va', 'v.IdVentas', '=', 'va.IdVentas')
                    ->join('articulo as a', 'va.IdArticulo', '=', 'a.IdArticulo')
                    ->select(DB::raw('count(va.Cantidad) as total, a.Descripcion, DAY(v.FechaCreacion) as dia, MONTH(v.FechaCreacion) as mes, YEAR(v.FechaCreacion) AS anio'))
                    ->where('a.IdArticulo', $producto)
                    ->where('v.IdTipoPago', $tipoPago)
                    ->where('v.IdSucursal', $idSucursal)
                    ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
                    ->groupBy(DB::raw($condicion))
                    ->orderBy(DB::raw($condicion), 'asc')
                    ->get();
            }
        }

        return $resultado;
    }

    public function setTipoCambio()
    {

    }

    public function exportExcel($producto = null, $tipoPago = null, $fecha = null, $ini = null, $fin = null)
    {
        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');
        $idSucursal = Session::get('idSucursal');
        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);
        $reporteProductos = $this->getVentasProductosExportar($idSucursal, $producto, $tipoPago, $fecha, $fechaIni, $fechaFin);
        foreach ($reporteProductos as $key => $item) {
            $reporteProductos[$key]->TipoCambio = '-';
            $reporteProductos[$key]->PrecioVentaConvertidoSoles = '-';
            $reporteProductos[$key]->PrecioVentaConvertidoDolares = '-';
            if ($item->IdTipoMoneda == 1 && $item->IdTipoMonedaArticulo == 2) {
                if ($item->IdCotizacion == null) {
                    $fecha = Carbon::parse($item->FechaCreacion)->format('Y-m-d');
                }
                if ($item->IdCotizacion != null) {
                    $cotizacion = DB::table('cotizacion')->where('IdCotizacion', $item->IdCotizacion)->first();
                    $fecha = Carbon::parse($cotizacion->FechaCreacion)->format('Y-m-d');
                }
                $tipoCambio = DB::table('tipo_cambio')
                    ->select('TipoCambioVentas', 'TipoCambioCompras')
                    ->where('FechaCreacion', $fecha)
                    ->where('IdSucursal', $item->IdSucursal)
                    ->first();
                $reporteProductos[$key]->TipoCambio = $tipoCambio->TipoCambioVentas;
                $reporteProductos[$key]->PrecioVentaConvertidoSoles = $item->PrecioUnidadRealVenta;
            }
            if ($item->IdTipoMoneda == 2 && $item->IdTipoMonedaArticulo == 1) {
                if ($item->IdCotizacion == null) {
                    $fecha = Carbon::parse($item->FechaCreacion)->format('Y-m-d');
                }
                if ($item->IdCotizacion != null) {
                    $cotizacion = DB::table('cotizacion')->where('IdCotizacion', $item->IdCotizacion)->first();
                    $fecha = Carbon::parse($cotizacion->FechaCreacion)->format('Y-m-d');
                }
                $tipoCambio = DB::table('tipo_cambio')
                    ->select('TipoCambioVentas', 'TipoCambioCompras')
                    ->where('FechaCreacion', $fecha)
                    ->where('IdSucursal', $item->IdSucursal)
                    ->first();
                $reporteProductos[$key]->TipoCambio = $tipoCambio->TipoCambioCompras;
                $reporteProductos[$key]->PrecioVentaConvertidoDolares = $item->PrecioUnidadRealVenta;
            }

            if ($item->IdCategoria != null) {
                $listaCategoria = DB::table('categoria')
                    ->where('IdCategoria', $item->IdCategoria)
                    ->first();
                $reporteProductos[$key]->nombreCategoria = $listaCategoria->Nombre;
            } else {
                $reporteProductos[$key]->nombreCategoria = '-';
            }
            if ($item->IdMarca != null) {
                $listaMarca = DB::table('marca')
                    ->where('IdMarca', $item->IdMarca)
                    ->first();
                $reporteProductos[$key]->nombreMarca = $listaMarca->Nombre;
            } else {
                $reporteProductos[$key]->nombreMarca = '-';
            }
        }
        $reporteProductos = $reporteProductos->sortDesc();
        return Excel::download(new ExcelReporteVentasProducto($reporteProductos), 'Reporte Ventas - Productos.xlsx');
    }

    private function getVentasProductosExportar($idSucursal, $producto, $tipoPago, $fecha, $fechaIni, $fechaFin)
    {
        try {
            $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            if ($producto == 0) {
                if ($tipoPago == 0) {
                    $resultado = DB::table('ventas_articulo')
                        ->join('ventas', 'ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
                        ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                        ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                        ->join('distrito', 'cliente.Ubigeo', '=', 'distrito.IdDistrito')
                        ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                        ->select('ventas.IdVentas', 'ventas.IdCotizacion', 'ventas.FechaCreacion', 'ventas.IdTipoMoneda', 'ventas.Serie', 'ventas.Numero', 'ventas.IdSucursal', 'ventas.IdTipoPago', 'ventas.TipoVenta', 'ventas.Total', 'ventas.Estado', 'ventas_articulo.IdArticulo as IdArticulo', 'articulo.Descripcion', 'ventas_articulo.Detalle', 'articulo.Precio as PrecioArticulo', 'ventas_articulo.PrecioUnidadReal as PrecioUnidadRealVenta', 'ventas_articulo.Cantidad', 'ventas_articulo.Descuento', 'ventas_articulo.Importe as ImporteItem', 'usuario.Nombre as Usuario', 'cliente.Nombre as Nombres', 'cliente.NumeroDocumento as Documento', 'articulo.Costo as PrecioCosto', 'articulo.IdCategoria', 'articulo.IdMarca', 'articulo.codigo', 'articulo.IdTipoMoneda as IdTipoMonedaArticulo', 'distrito.Nombre AS nombreDistrito', 'cliente.Direccion')
                        ->where('articulo.IdSucursal', $idSucursal)
                        ->whereNotIn('ventas.Estado', ['Baja Aceptado', 'Baja Pendiente', 'Baja Ticket'])
                        ->whereNotIn('ventas.Nota', [1])
                        ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                        ->get();
                    return $resultado;
                } else {
                    $resultado = DB::table('ventas_articulo')
                        ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                        ->join('ventas', 'ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
                        ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                        ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                        ->join('distrito', 'cliente.Ubigeo', '=', 'distrito.IdDistrito')
                        ->select('ventas.IdVentas', 'ventas.IdCotizacion', 'ventas.FechaCreacion', 'ventas.IdTipoMoneda', 'ventas.Serie', 'ventas.Numero', 'ventas.IdSucursal', 'ventas.IdTipoPago', 'ventas.TipoVenta', 'ventas.Total', 'ventas.Estado', 'ventas_articulo.IdArticulo as IdArticulo', 'articulo.Descripcion', 'ventas_articulo.Detalle', 'articulo.Precio as PrecioArticulo', 'ventas_articulo.PrecioUnidadReal as PrecioUnidadRealVenta', 'ventas_articulo.Cantidad', 'ventas_articulo.Descuento', 'ventas_articulo.Importe as ImporteItem', 'usuario.Nombre as Usuario', 'cliente.Nombre as Nombres', 'cliente.NumeroDocumento as Documento', 'articulo.Costo as PrecioCosto', 'articulo.IdCategoria', 'articulo.IdMarca', 'articulo.codigo', 'articulo.IdTipoMoneda as IdTipoMonedaArticulo', 'distrito.Nombre AS nombreDistrito', 'cliente.Direccion')
                        ->where('articulo.IdSucursal', $idSucursal)
                        ->whereNotIn('ventas.Estado', ['Baja Aceptado', 'Baja Pendiente', 'Baja Ticket'])
                        ->whereNotIn('ventas.Nota', [1])
                        ->where('ventas.IdTipoPago', $tipoPago)
                        ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                        ->get();
                    return $resultado;
                }
            } else {
                if ($tipoPago == 0) {
                    $resultado = DB::table('ventas_articulo')
                        ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                        ->join('ventas', 'ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
                        ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                        ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                        ->join('distrito', 'cliente.Ubigeo', '=', 'distrito.IdDistrito')
                        ->select('ventas.IdVentas', 'ventas.IdCotizacion', 'ventas.FechaCreacion', 'ventas.IdTipoMoneda', 'ventas.Serie', 'ventas.Numero', 'ventas.IdSucursal', 'ventas.IdTipoPago', 'ventas.TipoVenta', 'ventas.Total', 'ventas.Estado', 'ventas_articulo.IdArticulo as IdArticulo', 'articulo.Descripcion', 'ventas_articulo.Detalle', 'articulo.Precio as PrecioArticulo', 'ventas_articulo.PrecioUnidadReal as PrecioUnidadRealVenta', 'ventas_articulo.Cantidad', 'ventas_articulo.Descuento', 'ventas_articulo.Importe as ImporteItem', 'usuario.Nombre as Usuario', 'cliente.Nombre as Nombres', 'cliente.NumeroDocumento as Documento', 'articulo.Costo as PrecioCosto', 'articulo.IdCategoria', 'articulo.IdMarca', 'articulo.codigo', 'articulo.IdTipoMoneda as IdTipoMonedaArticulo', 'distrito.Nombre AS nombreDistrito', 'cliente.Direccion')
                        ->where('articulo.IdSucursal', $idSucursal)
                        ->whereNotIn('ventas.Estado', ['Baja Aceptado', 'Baja Pendiente', 'Baja Ticket'])
                        ->whereNotIn('ventas.Nota', [1])->whereNotIn('ventas.Estado', ['Baja Aceptado', 'Baja Pendiente', 'Baja Ticket'])
                        ->where('articulo.IdArticulo', $producto)
                        ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                        ->get();
                    return $resultado;
                } else {
                    $resultado = DB::table('ventas_articulo')
                        ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                        ->join('ventas', 'ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
                        ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                        ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                        ->join('distrito', 'cliente.Ubigeo', '=', 'distrito.IdDistrito')
                        ->select('ventas.IdVentas', 'ventas.IdCotizacion', 'ventas.FechaCreacion', 'ventas.IdTipoMoneda', 'ventas.Serie', 'ventas.Numero', 'ventas.IdSucursal', 'ventas.IdTipoPago', 'ventas.TipoVenta', 'ventas.Total', 'ventas.Estado', 'ventas_articulo.IdArticulo as IdArticulo', 'articulo.Descripcion', 'ventas_articulo.Detalle', 'articulo.Precio as PrecioArticulo', 'ventas_articulo.PrecioUnidadReal as PrecioUnidadRealVenta', 'ventas_articulo.Cantidad', 'ventas_articulo.Descuento', 'ventas_articulo.Importe as ImporteItem', 'usuario.Nombre as Usuario', 'cliente.Nombre as Nombres', 'cliente.NumeroDocumento as Documento', 'articulo.Costo as PrecioCosto', 'articulo.IdCategoria', 'articulo.IdMarca', 'articulo.codigo', 'articulo.IdTipoMoneda as IdTipoMonedaArticulo', 'distrito.Nombre AS nombreDistrito', 'cliente.Direccion')
                        ->where('articulo.IdSucursal', $idSucursal)
                        ->whereNotIn('ventas.Estado', ['Baja Aceptado', 'Baja Pendiente', 'Baja Ticket'])
                        ->whereNotIn('ventas.Nota', [1])
                        ->where('articulo.IdArticulo', $producto)
                        ->where('ventas.IdTipoPago', $tipoPago)
                        ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                        ->get();
                    return $resultado;
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function getFechaFiltro($fecha, $fechaIni, $fechaFin)
    {
        if ($fecha == 0) {
            $fechaInicio = '1900-01-01';
            $fechaFinal = Carbon::now();
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 1) {
            $fechaInicio = Carbon::today();
            $fechaFinal = Carbon::now();
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 2) {
            $fechaInicio = Carbon::yesterday();
            $fechaFinal = Carbon::today();
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 3) {
            $datePrev = Carbon::today()->dayOfWeek;
            $date = Carbon::today();
            $fechaInicio = $date->subDays($datePrev - 1);
            $fechaFinal = Carbon::now();
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 4) {
            $datePrev = Carbon::today()->dayOfWeek;
            $date1 = Carbon::today();
            $date2 = Carbon::today();
            $fechaFinal = $date1->subDays($datePrev - 1);
            $fechaInicio = $date2->subDays($datePrev + 6);
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 5) {
            $datePrev = Carbon::today()->day;
            $date = Carbon::today();
            $fechaInicio = $date->subDays($datePrev - 1);
            $fechaFinal = Carbon::now();
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 6) {
            $datePrev = Carbon::today()->day;
            $mesPasado = Carbon::today()->subMonth(1)->firstOfMonth();
            $date1 = Carbon::today();
            $fechaFinal = $date1->subDays($datePrev - 1);
            $fechaInicio = $mesPasado;
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 7) {
            $datePrev = Carbon::today()->firstOfYear();
            $fechaInicio = $datePrev;
            $fechaFinal = Carbon::now();
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 8) {
            $fechaInicio = Carbon::today()->subYear(1)->firstOfYear();
            $fechaFinal = Carbon::today()->subYear(1)->endOfYear();
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 9) {
            $fechaInicio = DateTime::createFromFormat('d/m/Y', $fechaIni);
            $fechaFinal = DateTime::createFromFormat('d/m/Y', $fechaFin);
            $fechaConvertidaInicio = $fechaInicio->format("Y-m-d");
            $fechaConvertidaFinal = $fechaFinal->format("Y-m-d");
            $fechaConvertidaFinal = strtotime('+1 day', strtotime($fechaConvertidaFinal));
            $fechaConvertidaFinal = date('Y-m-d', $fechaConvertidaFinal);
            return array($fechaConvertidaInicio, $fechaConvertidaFinal);
        }
    }

    private function verificarLogin($req)
    {
        if ($req->session()->has('idUsuario')) {

        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }
}

// Consulta para el envio del correo XML
// $loadDatos = new DatosController();
// $fechas = $loadDatos->getFechaFiltro(1, null, null);
// $resultadoFiltro = DB::table('filtros_correo')->select('IdCategoria', 'NombreCorreoExcel', 'IdSucursal', 'Estado')->get();
// $resultadoFiltro = collect($resultadoFiltro);
// $IdSucursal = $resultadoFiltro->pluck('IdSucursal')->first();
// $IdCategoria = $resultadoFiltro->pluck('IdCategoria')->first();
// // CODIGO PARA CARGAR LOS DATOS EN EL XML
// $resultado = DB::table('ventas_articulo')
// ->join('ventas','ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
// ->join('articulo','ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
// ->select( 'ventas.IdTipoComprobante', 'Serie', 'Numero')
// ->where('ventas.IdSucursal',$IdSucursal)
// ->where('articulo.IdCategoria',$IdCategoria)
// ->whereIn('ventas.IdTipoComprobante', [1,2])
// ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
// ->get();

// // $resultado = $resultado->get('Serie');
// dd($resultado);
