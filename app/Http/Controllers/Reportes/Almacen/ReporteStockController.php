<?php

namespace App\Http\Controllers\Reportes\Almacen;

use App\Exports\ExcelReporteStock;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DB;
use Excel;
use Illuminate\Http\Request;
use PDF;
use PHPMailer\PHPMailer\PHPMailer;
use Session;

class ReporteStockController extends Controller
{
    public function index(Request $req)
    {

        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        $idSucursal = $idSucursal = Session::get('idSucursal');

        $almacenes = DB::table('almacen')
            ->where('IdSucursal', $idSucursal)
            ->where('Estado', 'E')
            ->get();

        if ($almacenes->isEmpty()) {
            $almacenes = DB::table('almacen')
                ->where('IdSucursal', $idSucursal)
                ->where('Estado', 'E')
                ->get();
            $sucursal = DB::table('sucursal')
                ->where('IdSucursal', $idSucursal)
                ->first();

            $loadDatos = new DatosController();
            $idSucursal = $idSucursal = Session::get('idSucursal');
            $reporteStock = $loadDatos->getStockProductos($idSucursal);
            $totalStock = $reporteStock->sum('Stock');
            $totalArticulos = 0;
            // Nuevo codigo
            $totalProductosConStockSoles = 0;
            $totalProductosConStockDolares = 0;
            // Fin
            $productos = $loadDatos->getProductos($idSucursal);
            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $producto = '';
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $array = ['nombre' => $sucursal->Nombre, 'tipo' => 1, 'alojado' => $idSucursal, 'reporteStock' => $reporteStock, 'band' => 1, 'almacenes' => $almacenes, 'sucursal' => $sucursal, 'productos' => $productos, 'producto' => $producto, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'totalStock' => $totalStock, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'totalArticulos' => $totalArticulos, 'totalProductosConStockDolares' => $totalProductosConStockDolares, 'totalProductosConStockSoles' => $totalProductosConStockSoles];
            return view('reportes/almacen/reporteStock', $array);
        } else {
            $almacenes = DB::table('almacen')
                ->where('IdSucursal', $idSucursal)
                ->where('Estado', 'E')
                ->get();

            $sucursal = DB::table('sucursal')
                ->where('IdSucursal', $idSucursal)
                ->first();

            $loadDatos = new DatosController();
            $idSucursal = $idSucursal = Session::get('idSucursal');
            $reporteStock = array(); //$loadDatos->getStockProductos($idSucursal);
            $totalStock = 0; //$reporteStock->sum('Stock');
            $totalArticulos = 0;
            // Nuevo codigo
            $totalProductosConStockSoles = 0;
            $totalProductosConStockDolares = 0;
            // Fin
            $productos = $loadDatos->getProductos($idSucursal);
            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $producto = '';
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $array = ['nombre' => '', 'tipo' => 1, 'alojado' => $idSucursal, 'reporteStock' => $reporteStock, 'band' => 0, 'almacenes' => $almacenes, 'sucursal' => $sucursal, 'productos' => $productos, 'producto' => $producto, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'totalStock' => $totalStock, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'totalArticulos' => $totalArticulos, 'totalProductosConStockDolares' => $totalProductosConStockDolares, 'totalProductosConStockSoles' => $totalProductosConStockSoles];
            return view('reportes/almacen/reporteStock', $array);
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

        if ($req->isMethod('post')) {
            $idAlmacen = $req->input("almacenes");
            $identificador = explode("*", $idAlmacen);

            if (is_numeric($identificador[0])) {
                $band = 1;
                $idSucursal = Session::get('idSucursal');

                $almacenes = DB::table('almacen')
                    ->where('IdSucursal', $idSucursal)
                    ->where('Estado', 'E')
                    ->get();

                $almacen = DB::table('almacen') // se obtiene el nombre
                    ->where('IdAlmacen', $identificador[0])
                    ->first();

                $sucursal = DB::table('sucursal')
                    ->where('IdSucursal', $idSucursal)
                    ->first();

                $loadDatos = new DatosController();

                $idSucursal = Session::get('idSucursal');

                $reporteStock = DB::table('almacen_producto')
                    ->join('articulo', 'almacen_producto.CodigoInterno', '=', 'articulo.CodigoInterno')
                    ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                    ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                    ->join('categoria', 'categoria.IdCategoria', '=', 'articulo.IdCategoria')
                    ->select('almacen_producto.*', 'articulo.CodigoInterno', 'articulo.Ubicacion', 'articulo.Costo', 'articulo.Codigo', 'unidad_medida.Nombre', 'almacen_producto.Stock', 'articulo.Precio', 'articulo.IdArticulo', 'marca.nombre as NombreMar', 'categoria.Nombre as NombreCat', 'articulo.PrecioDescuento1', 'articulo.PrecioTipo', 'articulo.VentaMayor1', 'articulo.NombreTipo', 'articulo.CantidadTipo')
                    ->where('articulo.Estado', 'E')
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('almacen_producto.IdAlmacen', $identificador[0])
                    ->get();

                // NUEVO CODIGO totalStock Y totalArticulos
                $totalStock = $reporteStock->sum('Stock');
                $totalArticulos = $reporteStock->count('IdArticulos');
                // Fin
                // Nuevo codigo Mostrar total de dinero en alamacen productos con stock
                $totalProductosConStockSoles = $reporteStock->filter(function ($item, $key) {
                    if ($item->Stock >= 1 && $item->IdTipoMoneda == 1) {
                        return $item;
                    }
                });
                $totalProductosConStockDolares = $reporteStock->filter(function ($item, $key) {
                    if ($item->Stock >= 1 && $item->IdTipoMoneda == 2) {
                        return $item;
                    }
                });
                $totalProductosConStockSoles = $totalProductosConStockSoles->map(function ($item, $key) {
                    return $total = $item->Stock * $item->Precio;
                });
                $totalProductosConStockDolares = $totalProductosConStockDolares->map(function ($item, $key) {
                    return $total = $item->Stock * $item->Precio;
                });
                $totalProductosConStockSoles = $totalProductosConStockSoles->sum();
                $totalProductosConStockDolares = $totalProductosConStockDolares->sum();
                // Fin

                $productos = $loadDatos->getProductos($idSucursal);
                $permisos = $loadDatos->getPermisos($idUsuario);
                $subpermisos = $loadDatos->getSubPermisos($idUsuario);
                $subniveles = $loadDatos->getSubNiveles($idUsuario);

                $producto = '';
                $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
                $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
                $array = ['nombre' => $almacen->Nombre, 'tipo' => 2, 'alojado' => $identificador[0], 'reporteStock' => $reporteStock, 'band' => $band, 'almacenes' => $almacenes, 'sucursal' => $sucursal, 'productos' => $productos, 'producto' => $producto, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'totalStock' => $totalStock, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'totalArticulos' => $totalArticulos, 'totalProductosConStockDolares' => $totalProductosConStockDolares, 'totalProductosConStockSoles' => $totalProductosConStockSoles];
                return view('reportes/almacen/reporteStock', $array);
            } else if ($identificador[0] == '') {
                $band = 1;
                $idSucursal = $idSucursal = Session::get('idSucursal');

                $almacenes = DB::table('almacen')
                    ->where('IdSucursal', $idSucursal)
                    ->where('Estado', 'E')
                    ->get();

                $sucursal = DB::table('sucursal')
                    ->where('IdSucursal', $idSucursal)
                    ->first();

                $loadDatos = new DatosController();
                $idSucursal = $idSucursal = Session::get('idSucursal');
                $reporteStock = $loadDatos->getStockProductos($idSucursal);
                // NUEVO CODIGO totalStock Y totalArticulos
                $totalStock = $reporteStock->sum('Stock');
                $totalArticulos = $reporteStock->count('IdArticulos');
                // FIN
                $productos = $loadDatos->getProductos($idSucursal);
                // Nuevo codigo Mostrar total de dinero en alamacen productos con stock
                $totalProductosConStockSoles = $productos->filter(function ($item, $key) {
                    if ($item->Stock >= 1 && $item->IdTipoMoneda == 1) {
                        return $item;
                    }
                });
                $totalProductosConStockDolares = $productos->filter(function ($item, $key) {
                    if ($item->Stock >= 1 && $item->IdTipoMoneda == 2) {
                        return $item;
                    }
                });
                $totalProductosConStockSoles = $totalProductosConStockSoles->map(function ($item, $key) {
                    return $total = $item->Stock * $item->Precio;
                });
                $totalProductosConStockDolares = $totalProductosConStockDolares->map(function ($item, $key) {
                    return $total = $item->Stock * $item->Precio;
                });
                $totalProductosConStockSoles = $totalProductosConStockSoles->sum();
                $totalProductosConStockDolares = $totalProductosConStockDolares->sum();
                // dd( $totalProductosConStockDolares);
                // Fin
                $permisos = $loadDatos->getPermisos($idUsuario);
                $subpermisos = $loadDatos->getSubPermisos($idUsuario);
                $subniveles = $loadDatos->getSubNiveles($idUsuario);

                $producto = '';
                $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
                $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
                $array = ['nombre' => $sucursal->Nombre, 'tipo' => 1, 'alojado' => $idSucursal, 'reporteStock' => $reporteStock, 'band' => $band, 'almacenes' => $almacenes, 'sucursal' => $sucursal, 'productos' => $productos, 'producto' => $producto, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'totalStock' => $totalStock, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'totalArticulos' => $totalArticulos, 'totalProductosConStockDolares' => $totalProductosConStockDolares, 'totalProductosConStockSoles' => $totalProductosConStockSoles];
                return view('reportes/almacen/reporteStock', $array);
            } else {
                $idSucursal = $idSucursal = Session::get('idSucursal');

                $almacenes = DB::table('almacen')
                    ->where('IdSucursal', $idSucursal)
                    ->where('Estado', 'E')
                    ->get();

                $sucursal = DB::table('sucursal')
                    ->where('IdSucursal', $idSucursal)
                    ->first();

                $loadDatos = new DatosController();
                $idSucursal = $idSucursal = Session::get('idSucursal');
                $reporteStock = array(); //$loadDatos->getStockProductos($idSucursal);
                $totalStock = 0; //$reporteStock->sum('Stock');
                $productos = $loadDatos->getProductos($idSucursal);
                $permisos = $loadDatos->getPermisos($idUsuario);

                $subpermisos = $loadDatos->getSubPermisos($idUsuario);
                $subniveles = $loadDatos->getSubNiveles($idUsuario);

                $producto = '';
                $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
                $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
                $array = ['nombre' => '', 'tipo' => 1, 'alojado' => $idSucursal, 'reporteStock' => $reporteStock, 'band' => 0, 'almacenes' => $almacenes, 'sucursal' => $sucursal, 'productos' => $productos, 'producto' => $producto, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'totalStock' => $totalStock, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
                return view('reportes/almacen/reporteStock', $array);
            }
        }
    }

    public function imprimirStock()
    {
        $band = 1;
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function imprimir(Request $req, $id, $id2)
    { //alojado es  sucursal  ojo
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        if ($id == 1) {
            $idUsuario = Session::get('idUsuario');

            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $reporteStock = $loadDatos->getStockProductos($idSucursal);
            $totalStock = $reporteStock->sum('Stock');
            $productos = $loadDatos->getProductos($idSucursal);
            $permisos = $loadDatos->getPermisos($idUsuario);
            $producto = '';
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);

            /**************************************************/
            $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
            /*******************************************************/

            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $array = ['reporteStock' => $reporteStock, 'productos' => $productos, 'producto' => $producto, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'totalStock' => $totalStock, 'empresa' => $empresa];

            $pdf = PDF::loadView('stockPDF', $array);

            return $pdf->download('stock.pdf');
        } else if ($id == 2) {
            $idSucursal = Session::get('idSucursal');

            $loadDatos = new DatosController();

            $reporteStock = DB::table('almacen_producto')
                ->join('articulo', 'almacen_producto.CodigoInterno', '=', 'articulo.CodigoInterno')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->select('almacen_producto.*', 'articulo.CodigoInterno', 'articulo.Costo', 'articulo.Codigo', 'unidad_medida.Nombre', 'almacen_producto.Stock', 'articulo.Precio', 'articulo.IdArticulo')
                ->where('articulo.Estado', 'E')
                ->where('articulo.IdSucursal', $idSucursal)
                ->where('almacen_producto.IdAlmacen', $id2)
                ->get();

            $totalStock = $reporteStock->sum('Stock');
            $productos = $loadDatos->getProductos($idSucursal);
            $permisos = $loadDatos->getPermisos($idUsuario);
            $producto = '';
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);

            /**************************************************/
            $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
            /*******************************************************/

            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $array = ['reporteStock' => $reporteStock, 'productos' => $productos, 'producto' => $producto, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'totalStock' => $totalStock, 'empresa' => $empresa];

            $pdf = PDF::loadView('stockPDF', $array);

            return $pdf->download('stock.pdf');
        }
    }

    public function exportExcel(Request $req, $id, $id2)
    { //alojado es  sucursal  ojo
        if ($id == 1) {
            $idUsuario = Session::get('idUsuario');

            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');

            // NUEVO CODIGO ULTIMA FECHA COMPRA Y VENTA
            $arrayArticulo = DB::table('articulo')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                ->join('categoria', 'categoria.IdCategoria', '=', 'articulo.IdCategoria')
                ->join('tipo_moneda', 'articulo.IdTipoMoneda', '=', 'tipo_moneda.IdTipoMoneda')
                ->select('articulo.IdArticulo', 'articulo.Descripcion as Descripcion', 'marca.nombre as Marca', 'categoria.Nombre as Categoria', 'articulo.Codigo', 'articulo.Ubicacion as Ubicacion', 'unidad_medida.Nombre as UnidadMedida', 'articulo.Stock', 'tipo_moneda.Nombre as TipoMoneda', 'articulo.Costo', 'articulo.Precio')
                ->where('IdTipo', 1)
                ->where('articulo.Estado', 'E')
                ->where('articulo.IdSucursal', $idSucursal)
                ->get();

            $resultArray = $arrayArticulo->map(function ($items) use ($idSucursal) {
                $reporteDetalle = DB::select('call sp_detalleFechasArticulo(?,?)', array($idSucursal, $items->IdArticulo));
                $ultimaFechaCompra = "-";
                $ultimaFechaVenta = "-";
                $fechaCreacionArticulo = $reporteDetalle[0]->fechaCreacionArticulo;
                if ($reporteDetalle[0]->ultimaFechaCompra != null) {
                    $ultimaFechaCompra = $reporteDetalle[0]->ultimaFechaCompra;
                }
                if ($reporteDetalle[0]->ultimaFechaVenta != null) {
                    $ultimaFechaVenta = $reporteDetalle[0]->ultimaFechaVenta;
                }

                return (object) array_merge((array) $items, ['ultimaFechaCompra' => $ultimaFechaCompra, 'ultimaFechaVenta' => $ultimaFechaVenta, 'fechaCreacionArticulo' => $fechaCreacionArticulo]);
            });
            // FIN
            $resultArray = $resultArray->sortBy('Categoria')->values();
            return Excel::download(new ExcelReporteStock($resultArray), 'Reporte de Stock.xlsx');

        } else if ($id == 2) {
            $idSucursal = Session::get('idSucursal');
            $loadDatos = new DatosController();

            $arrayIdArticulo = DB::table('almacen_producto')
                ->join('articulo', 'almacen_producto.CodigoInterno', '=', 'articulo.CodigoInterno')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                ->join('categoria', 'categoria.IdCategoria', '=', 'articulo.IdCategoria')
                ->join('tipo_moneda', 'almacen_producto.IdTipoMoneda', '=', 'tipo_moneda.IdTipoMoneda')
                ->select('articulo.IdArticulo', 'almacen_producto.Descripcion', 'marca.nombre as Marca', 'categoria.Nombre as Categoria', 'articulo.Codigo', 'articulo.Ubicacion as Ubicacion', 'unidad_medida.Nombre as UnidadMedida', 'almacen_producto.Stock', 'tipo_moneda.Nombre as TipoMoneda', 'articulo.Costo', 'articulo.Precio')
                ->where('articulo.Estado', 'E')
                ->where('articulo.IdSucursal', $idSucursal)
                ->where('almacen_producto.IdAlmacen', $id2)
                ->get();

            $resultArray = $arrayIdArticulo->map(function ($items) use ($idSucursal) {
                $reporteDetalle = DB::select('call sp_detalleFechasArticulo(?,?)', array($idSucursal, $items->IdArticulo));
                $ultimaFechaCompra = "-";
                $ultimaFechaVenta = "-";
                $fechaCreacionArticulo = $reporteDetalle[0]->fechaCreacionArticulo;
                if ($reporteDetalle[0]->ultimaFechaCompra != null) {
                    $ultimaFechaCompra = $reporteDetalle[0]->ultimaFechaCompra;
                }
                if ($reporteDetalle[0]->ultimaFechaVenta != null) {
                    $ultimaFechaVenta = $reporteDetalle[0]->ultimaFechaVenta;
                }

                return (object) array_merge((array) $items, ['ultimaFechaCompra' => $ultimaFechaCompra, 'ultimaFechaVenta' => $ultimaFechaVenta, 'fechaCreacionArticulo' => $fechaCreacionArticulo]);
            });
            $resultArray = $resultArray->sortBy('Categoria')->values();

            return Excel::download(new ExcelReporteStock($resultArray), 'Reporte de Stock.xlsx');
        }
    }

    public function generarPDF($idUsuario, $tipo, $id2)
    {

        if ($tipo == 1) {
            $idUsuario = Session::get('idUsuario');

            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $reporteStock = $loadDatos->getStockProductos($idSucursal);
            $totalStock = $reporteStock->sum('Stock');
            $productos = $loadDatos->getProductos($idSucursal);
            $permisos = $loadDatos->getPermisos($idUsuario);
            $producto = '';
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);

            /**************************************************/
            $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
            /*******************************************************/

            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $array = ['reporteStock' => $reporteStock, 'productos' => $productos, 'producto' => $producto, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'totalStock' => $totalStock, 'empresa' => $empresa];

            $pdf = PDF::loadView('stockPDF', $array);

            return $pdf;
        } else if ($tipo == 2) {
            $idSucursal = Session::get('idSucursal');

            $loadDatos = new DatosController();

            $reporteStock = DB::table('almacen_producto')
                ->join('articulo', 'almacen_producto.CodigoInterno', '=', 'articulo.CodigoInterno')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->select('almacen_producto.*', 'articulo.CodigoInterno', 'articulo.Costo', 'articulo.Codigo', 'unidad_medida.Nombre', 'almacen_producto.Stock', 'articulo.Precio', 'articulo.IdArticulo')
                ->where('articulo.Estado', 'E')
                ->where('articulo.IdSucursal', $idSucursal)
                ->where('almacen_producto.IdAlmacen', $id2)
                ->get();

            $totalStock = $reporteStock->sum('Stock');
            $productos = $loadDatos->getProductos($idSucursal);
            $permisos = $loadDatos->getPermisos($idUsuario);
            $producto = '';
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);

            /**************************************************/
            $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
            /*******************************************************/

            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $array = ['reporteStock' => $reporteStock, 'productos' => $productos, 'producto' => $producto, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'totalStock' => $totalStock, 'empresa' => $empresa];

            $pdf = PDF::loadView('stockPDF', $array);

            return $pdf;
        }

        /* $idUsuario = Session::get('idUsuario');

    $loadDatos = new DatosController();
    $idSucursal = Session::get('idSucursal');
    $reporteStock = $loadDatos->getStockProductos($idSucursal);
    $totalStock = $reporteStock->sum('Stock');
    $productos = $loadDatos->getProductos($idSucursal);
    $permisos = $loadDatos->getPermisos($idUsuario);
    $producto = '';
    $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
    $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
    $array = ['reporteStock' => $reporteStock, 'productos' => $productos, 'producto' => $producto, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'totalStock' => $totalStock];

    $pdf = PDF::loadView('stockPDF', $array);

    return $pdf; */
    }

    public function enviarCorreo(Request $req, $id, $id2)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        //$caja = $loadDatos->getCajaSelect($id);

        $pdf = $this->generarPDF($idUsuario, $id, $id2);
        file_put_contents('reporte-stock.pdf', $pdf->output());

        $mail = new PHPMailer();
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'mail.easyfactperu.pe'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'facturacion@easyfactperu.pe'; // SMTP username
        $mail->Debugoutput = 'html';
        $mail->Password = 'gV.S=o=Q,bl2'; // SMTP password
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; // TCP port to connect to
        $mail->From = 'facturacion@easyfactperu.pe';
        $mail->FromName = 'EASYFACT PERÚ S.A.C.  - Facturación Electrónica';
        $mail->addAddress($req->correo, 'Comprobante'); // Add a recipient

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8'; // Set email format to HTML
        $mail->Subject = 'Reporte Detallado de Stock';
        $mail->addAttachment('reporte-stock.pdf');

        $mail->msgHTML('<div>Estado del stock actual</div><br><div>A la fecha de Hoy</div>');
        $enviado = $mail->send();
        if ($enviado) {
            return back()->with('status', 'Se envio correo con éxito');
        } else {
            return back()->with('error', 'No se pudo enviar correo');
        }
    }

}
