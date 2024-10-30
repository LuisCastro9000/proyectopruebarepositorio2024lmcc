<?php

namespace App\Http\Controllers\Reportes\Almacen;

use App\Exports\ExcelReporteKardex;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DateTime;
use DB;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;

class ReporteKardexController extends Controller
{
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $band = 0;
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');

        $almacenes = DB::table('almacen')
            ->where('IdSucursal', $idSucursal)
            ->where('Estado', 'E')
            ->get();

        $sucursal = DB::table('sucursal')
            ->where('IdSucursal', $idSucursal)
            ->first();

        $reporteKardex = array(); //$loadDatos->getKardexProductos($idSucursal);

        $productos = $loadDatos->getProductos($idSucursal);
        $permisos = $loadDatos->getPermisos($idUsuario);

        //Nuevo codigo Obbtener Lista Marcas
        $listaMarca = $productos->unique('Marca')->values()->all();
        // Fin
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $fecha = '5';
        $fechaIni = '';
        $fechaFin = '';
        $ini = 0;
        $fin = 0;
        $prod = 0;
        $marca = 0;
        $checkCodigoBarra = 0;
        $stockActual = 0;
        $cantidadMovimientos = 0;
        $inputCodigoBarra = 0;
        $valorCkeckCodigo = 0;
        $tipo = 1;
        $idSucursalAlmacen = $idSucursal;

        $fechas = $this->getFechaFiltro($fecha, $ini, $fin);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $array = ['usuario' => $idUsuario, 'reporteKardex' => $reporteKardex, 'productos' => $productos, 'almacenes' => $almacenes, 'sucursal' => $sucursal, 'verificarCodigo' => 0, 'codigo' => 0, 'tipo' => $tipo, 'idSucursalAlmacen' => $idSucursalAlmacen,
            'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'band' => $band, 'prod' => $prod, 'fecha' => $fecha, 'ini' => $ini, 'fin' => $fin, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'listaMarca' => $listaMarca, 'marca' => $marca, 'stockActual' => $stockActual, 'cantidadMovimientos' => $cantidadMovimientos, 'inputCodigoBarra' => $inputCodigoBarra, 'valorCkeckCodigo' => $valorCkeckCodigo, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin];
        return view('reportes/almacen/reporteKardex', $array);
    }

    public function store(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi��n de usuario Expirado');
        }

        $loadDatos = new DatosController();

        $this->validateReporteKardex($req);
        $idSucursal = Session::get('idSucursal');
        $almacenes = DB::table('almacen')
            ->where('IdSucursal', $idSucursal)
            ->where('Estado', 'E')
            ->get();

        $sucursal = DB::table('sucursal')
            ->where('IdSucursal', $idSucursal)
            ->first();

        $band = 1;
        $id_prod = $req->input("producto");
        $id_marca = $req->marca;

        $fecha = $req->fecha;
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        // $ini = 0;
        // $fin = 0;
        $ini = str_replace('/', '-', $fechaIni);
        $fin = str_replace('/', '-', $fechaFin);

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

        $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        /****ver  tipo  almacen/sucursal  ********/
        $idAlmacen = $req->input("local");

        $identificador = explode("*", $idAlmacen);
        //dd($identificador);
        if ($req->codigoProducto != null) {
            $codigo = $req->inputCodigo;
        } else {
            $codigo = 0;
            $req->codigoProducto = 0;
        }

        if (is_numeric($identificador[0])) {
            //almacen
            //Session::set('tipoReporte', 2);
            //Session::put('tipoReporte', 2);
            //Session::put('almacen', $idAlmacen);
            $tipo = 2;
            $existencia = DB::table('kardex')
                ->where('IdSucursal', $idAlmacen)
                ->where('CodigoInterno', $id_prod) //prod es  codigo  ineterno
                ->where('fecha_movimiento', '>=', $fechas[0])
                ->limit(1)
                ->get();
            if (count($existencia) >= 1) {
                $existe = $existencia[0]->existencia;
            } else {
                $existe = 0;
            }

            // Nuevo codigo
            $valorCkeckCodigo = $req->customcheck;
            $inputCodigoBarra = $req->codigoBarra;
            if ($inputCodigoBarra != null) {
                $id_prod = 0;
            } else {
                $inputCodigoBarra = 'vacio';
            }
            $idSucursalAlmacen = $idAlmacen;
            //dd($id_prod);
            $reporteKardex = $loadDatos->getKardexListaProductos($idAlmacen, $id_prod, $id_marca, $inputCodigoBarra, 2, $fechas[0], $fechas[1]);
            $stockActual = empty($reporteKardex) ? 0 : round($reporteKardex->pluck("Stock")->first(), 2);
            $cantidadMovimientos = empty($reporteKardex) ? 0 : $reporteKardex->count("IdKardex");
            // Fin
        } else {
            //sucursal principal
            //Session::put('tipoReporte', 1);
            $tipo = 1;
            $existencia = DB::table('kardex')
                ->where('IdSucursal', $idSucursal)
                ->where('IdArticulo', $id_prod)
                ->where('fecha_movimiento', '>=', $fechas[0])
                ->limit(1)
                ->get();
            if (count($existencia) >= 1) {
                $existe = $existencia[0]->existencia;
            } else {
                $existe = 0;
            }
            // NUEVO CODIGO
            $valorCkeckCodigo = $req->customcheck;
            $inputCodigoBarra = $req->codigoBarra;
            if ($inputCodigoBarra != null) {
                $id_prod = 0;
            } else {
                $inputCodigoBarra = 'vacio';
            }
            $idSucursalAlmacen = $idSucursal;
            $reporteKardex = $loadDatos->getKardexListaProductos($idSucursal, $id_prod, $id_marca, $inputCodigoBarra, 1, $fechas[0], $fechas[1]);
            $stockActual = round($reporteKardex->pluck("Stock")->first(), 2);
            $cantidadMovimientos = $reporteKardex->count("IdKardex");
            // FIN
        }

        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $productos = $loadDatos->getProductos($idSucursal);

        //  NUEVO CODIGO OBTENER LISTA DE MARCAS
        $listaMarca = $productos->unique('Marca')->values()->all();
        // Fin

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['usuario' => $idUsuario, 'reporteKardex' => $reporteKardex, 'productos' => $productos, 'almacenes' => $almacenes, 'sucursal' => $sucursal, 'verificarCodigo' => $req->codigoProducto, 'codigo' => $codigo, 'tipo' => $tipo, 'idSucursalAlmacen' => $idSucursalAlmacen,
            'existencia' => $existe, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'band' => $band, 'prod' => $id_prod, 'fecha' => $fecha, 'ini' => $ini, 'fin' => $fin, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'listaMarca' => $listaMarca, 'marca' => $id_marca, 'stockActual' => $stockActual, 'cantidadMovimientos' => $cantidadMovimientos, 'inputCodigoBarra' => $inputCodigoBarra, 'valorCkeckCodigo' => $valorCkeckCodigo, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin];

        return view('reportes/almacen/reporteKardex', $array);
    }

    public function kardexAntiguo(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $band = 0;
            $loadDatos = new DatosController();
            $idSucursal = $idSucursal = Session::get('idSucursal');

            $almacenes = DB::table('almacen')
                ->where('IdSucursal', $idSucursal)
                ->where('Estado', 'E')
                ->get();

            $sucursal = DB::table('sucursal')
                ->where('IdSucursal', $idSucursal)
                ->first();

            $reporteKardex = array(); //$loadDatos->getKardexProductos($idSucursal);

            $productos = $loadDatos->getProductos($idSucursal);
            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $producto = '';
            $categoria = '';
            $marca = '';

            $fecha = '';
            $ini = 0;
            $fin = 0;
            $prod = 0;

            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $categorias = $loadDatos->getCategorias($usuarioSelect->CodigoCliente);
            $marcas = $loadDatos->getMarcas($usuarioSelect->CodigoCliente);
            $array = ['usuario' => $idUsuario, 'reporteKardex' => $reporteKardex, 'productos' => $productos, 'categorias' => $categorias, 'marcas' => $marcas, 'producto' => $producto, 'categoria' => $categoria, 'almacenes' => $almacenes, 'sucursal' => $sucursal,
                'marca' => $marca, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'band' => $band, 'prod' => $prod, 'fecha' => $fecha, 'ini' => $ini, 'fin' => $fin, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
            return view('reportes/almacen/reporteKardexOld', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi��n de usuario Expirado');
        }
    }

    public function filtrarKardexAntiguo(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $idSucursal = $idSucursal = Session::get('idSucursal');
            $almacenes = DB::table('almacen')
                ->where('IdSucursal', $idSucursal)
                ->where('Estado', 'E')
                ->get();

            $sucursal = DB::table('sucursal')
                ->where('IdSucursal', $idSucursal)
                ->first();

            $idSucursal = Session::get('idSucursal');

            $validator = Validator::make($req->all(), [
                'local' => 'required',
                'producto' => 'required',
                'fecha' => 'required',
            ]);

            if ($validator->fails()) {
                $band = 0;
                return redirect('reportes/almacen/kardex-antiguo')
                    ->withErrors($validator)
                    ->withInput();
            }

            $band = 1;
            $id_prod = $req->input("producto");
            $fecha = $req->fecha;
            $fechaIni = $req->fechaIni;
            $fechaFin = $req->fechaFin;
            $ini = 0;
            $fin = 0;

            if ($fecha == 9) {
                if ($fechaIni == null || $fechaFin == null) {
                    return back()->with('error', 'Completar las fechas para filtrar');
                }
                if ($fechaIni > $fechaFin) {
                    return back()->with('error', 'La fecha Inicial no puede ser mayor que la Final');
                }
                $ini = str_replace('/', '-', $req->fechaIni);
                $fin = str_replace('/', '-', $req->fechaFin);
            }

            $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            /****ver  tipo  almacen/sucursal  ********/
            $idAlmacen = $req->input("local");
            $identificador = explode("*", $idAlmacen);
            if (is_numeric($identificador[0])) {
                //almacen
                //Session::set('tipoReporte', 2);
                Session::put('tipoReporte', 2);
                Session::put('almacen', $idAlmacen);
                $existencia = DB::table('kardex')
                    ->where('IdSucursal', $idAlmacen)
                    ->where('CodigoInterno', $id_prod) //prod es  codigo  ineterno
                    ->where('fecha_movimiento', '>=', $fechas[0])
                    ->limit(1)
                    ->get();

                if (count($existencia) >= 1) {
                    $existe = $existencia[0]->existencia;
                } else {
                    $existe = 0;
                }
                $reporteKardex = $this->consultaKardexAlmacen($idAlmacen, $id_prod, $fechas[0], $fechas[1], $id_prod); //id_prod  es  el codigo interno ojo
            } else {
                //sucursal principal
                Session::put('tipoReporte', 1);

                $existencia = DB::table('kardex')
                    ->where('IdSucursal', $idSucursal)
                    ->where('CodigoInterno', $id_prod) //prod es  codigo  ineterno
                    ->where('fecha_movimiento', '>=', $fechas[0])
                    ->limit(1)
                    ->get();
                if (count($existencia) >= 1) {
                    $existe = $existencia[0]->existencia;
                } else {
                    $existe = 0;
                }
                $codInterno = DB::table('articulo')
                    ->select('CodigoInterno')
                    ->where('CodigoInterno', $id_prod)
                    ->first();
                $reporteKardex = $this->consultaKardex($idSucursal, $id_prod, $fechas[0], $fechas[1], $codInterno->CodigoInterno);

            }

            $loadDatos = new DatosController();
            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $producto = '';
            $categoria = '';
            $marca = '';
            // $reporteKardex = array(); //$loadDatos->getKardexProductosFiltrados($idSucursal, $producto, $categoria, $marca);
            $productos = $loadDatos->getProductos($idSucursal);
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $categorias = $loadDatos->getCategorias($usuarioSelect->CodigoCliente);
            $marcas = $loadDatos->getMarcas($usuarioSelect->CodigoCliente);
            $array = ['usuario' => $idUsuario, 'reporteKardex' => $reporteKardex, 'productos' => $productos, 'categorias' => $categorias, 'marcas' => $marcas, 'producto' => $producto, 'categoria' => $categoria, 'almacenes' => $almacenes, 'sucursal' => $sucursal,
                'existencia' => $existe, 'marca' => $marca, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'band' => $band, 'prod' => $id_prod, 'fecha' => $fecha, 'ini' => $ini, 'fin' => $fin, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];

            return view('reportes/almacen/reporteKardexOld', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi��n de usuario Expirado');
        }

    }

    public function emparejarKardex(Request $req)
    {

        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi��n de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idSucursal = $idSucursal = Session::get('idSucursal');

        $tipoMovimientos = $this->getTipoMovimientos();
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['tipoMovimientos' => $tipoMovimientos, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('reportes/almacen/emparejarKardex', $array);
    }

    public function getTipoMovimientos()
    {
        $tipos = DB::table('tipo_movimiento')
            ->get();

        return $tipos;

    }

    public function emparejando(Request $req)
    {
        $idTipoMovimiento = $req->tipoMovimiento;

        //dd($idTipoMovimiento);
        /*$reporteKardex = DB::table('kardex')
        ->select('IdKardex', 'IdArticulo')
        ->where('tipo_movimiento', $idTipoMovimiento)
        ->where('IdSucursal', 60)
        ->orderBy('IdKardex','desc')
        ->get();*/

        if ($idTipoMovimiento == "1") {
            $reporteKardex = DB::table('ventas')
                ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                ->join('ventas', 'ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
                ->select('ventas_articulo.IdArticulo as IdArticulo')
                ->where('articulo.IdTipo', 1)
                ->where('articulo.Estado', 'E')
                ->where('ventas.FechaCreacion', '>', '2020-01-14 01:30')
                ->orderBy('ventas_articulo.IdVentasArticulo', 'desc')
                ->limit(500)
                ->get();
        }
        /*$reporteKardex = DB::table('ventas')
        ->join('ventas_articulo','ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
        ->select('ventas_articulo.Descuento', 'ventas_articulo.Cantidad', 'ventas_articulo.PrecioUnidadReal', 'ventas_articulo.Importe', 'ventas_articulo.IdArticulo', 'ventas_articulo.IdVentas')
        ->orderBy('ventas_articulo.IdVentasArticulo','desc')
        ->skip(2000)
        ->take(2000)
        ->get();*/

        return $reporteKardex;
    }

    // public function exportExcel($prod, $verificarCodigo, $codigo, $fecha, $ini, $fin)
    public function exportExcel($prod, $marca, $inputCodigoBarra, $idSucursalAlmacen, $tipo, $fecha, $ini = null, $fin = null)
    {
        //$tipo=Session::get('tipoReporte');
        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');

        $idSucursal = Session::get('idSucursal');
        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);

        $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        if ($tipo == 1) {
            $existencia = DB::table('kardex')
                ->where('IdSucursal', $idSucursal)
                ->where('IdArticulo', $prod)
                ->where('fecha_movimiento', '<=', $fechas[1])
                ->limit(1)
                ->get();
            if (count($existencia) >= 1) {
                $existe = $existencia[0]->existencia;
            } else {
                $existe = 0;
            }

            // NUEVO CODIGO
            if ($inputCodigoBarra != 0) {
                $prod = 0;
            }
            $reporteKardex = $loadDatos->getKardexListaProductos($idSucursalAlmacen, $prod, $marca, $inputCodigoBarra, 1, $fechas[0], $fechas[1]);
            // FIN
            return Excel::download(new ExcelReporteKardex($reporteKardex, $existe), 'Reporte de Kardex.xlsx');

        } else if ($tipo == 2) {
            $idAlmacen = Session::get('almacen');
            $existencia = DB::table('kardex')
                ->where('IdSucursal', $idAlmacen)
                ->where('CodigoInterno', $prod) //prod es  codigo  ineterno
                ->where('fecha_movimiento', '<=', $fechas[1])
                ->limit(1)
                ->get();
            if (count($existencia) >= 1) {
                $existe = $existencia[0]->existencia;
            } else {
                $existe = 0;
            }

            // NUEVO CODIGO

            if ($inputCodigoBarra != 0) {
                $prod = 0;
            }
            $reporteKardex = $loadDatos->getKardexListaProductos($idSucursalAlmacen, $prod, $marca, $inputCodigoBarra, 2, $fechas[0], $fechas[1]);
            // FIN

            return Excel::download(new ExcelReporteKardex($reporteKardex, $existe), 'Reporte de Kardex.xlsx');
        }
    }

    private function consultaKardex($idSucursal, $id_prod, $fechas1, $fechas2, $codInterno)
    {

        $idArticulo = DB::table('articulo')
            ->select('IdArticulo')
            ->where('CodigoInterno', $id_prod)
            ->where('IdSucursal', $idSucursal)
            ->first();

        $id_prod = $idArticulo->IdArticulo;

        $reporteKardex = DB::select("(SELECT if(1 > 1, '-', '1') as 'Tipo', u.Nombre, DATE_FORMAT(v.FechaCreacion, '%d/%m/%Y')  as  Fecha, va.IdVentas, v.Serie, v.Numero, sum(va.CantidadReal * va.Cantidad) as Cantidad, va.PrecioUnidadReal as 'Costo', sum(va.Importe) as Total, v.FechaCreacion as fecha2
									FROM ventas v inner join ventas_articulo va on v.IdVentas = va.IdVentas INNER JOIN usuario u ON u.IdUsuario = v.IdCreacion
									WHERE v.IdSucursal = ? AND va.IdArticulo = ? AND v.FechaCreacion BETWEEN ? AND ? group by va.IdVentas)
								    UNION ALL
                                   (SELECT 2 as 'Tipo', u.Nombre, DATE_FORMAT(c.FechaCreacion, '%d/%m/%Y') as Fecha, ca.IdCompras, c.Serie, c.Numero, sum(ca.Cantidad), ca.PrecioCosto, (sum(ca.Cantidad) * ca.PrecioCosto), c.FechaCreacion as fecha2
                                    FROM compras c inner join compras_articulo ca on c.IdCompras = ca.IdCompras INNER JOIN usuario u ON u.IdUsuario = c.IdCreacion
                                	WHERE c.IdSucursal = ? AND ca.IdArticulo = ? AND FechaCreacion BETWEEN ? AND ? group by ca.IdCompras)
									UNION ALL
                                   (SELECT 4 as 'Tipo', u.Nombre, DATE_FORMAT(b.FechaBaja, '%d/%m/%Y') as Fecha, b.IdBajaProducto, 'Baja', 'Prod', sum(b.Cantidad), 0, 0, b.FechaBaja as fecha2 from baja_producto b INNER JOIN usuario u ON u.IdUsuario = b.IdUsuario
									WHERE b.IdSucursal = ? AND IdProducto = ? AND FechaBaja BETWEEN ? AND ? group by b.IdProducto)
									UNION ALL
								   (SELECT 3 as 'Tipo', NombreOrigen, DATE_FORMAT(fechaTraspaso, '%d/%m/%Y') as Fecha, id, 'Transpaso', 'Entrada', sum(Cantidad),0,0, fechaTraspaso as fecha2 from historico_traspaso
									WHERE Destino=? AND TipoDestino = 1 AND CodigoInterno=? AND fechaTraspaso BETWEEN ? AND ? group by Destino)
									UNION ALL
								   (SELECT 5 as 'Tipo', NombreDestino, DATE_FORMAT(fechaTraspaso, '%d/%m/%Y') as Fecha, id, 'Transpaso', 'Salida', sum(Cantidad),0,0, fechaTraspaso as fecha2 from historico_traspaso
									WHERE origen=? AND TipoOrigen = 1 AND CodigoInterno=? AND fechaTraspaso BETWEEN ? AND ? group by Origen)
									UNION ALL
									(SELECT 6 as 'Tipo', u.nombre, DATE_FORMAT(bd.FechaEnviada, '%d/%m/%Y') as Fecha, bd.IdBajaDoc, 'Baja', 'Documento', sum(bdd.Cantidad), bdd.PrecioVenta, 0, bd.FechaEnviada as fecha2
									FROM baja_documentos bd inner join baja_detalle bdd on bd.IdBajaDoc = bdd.IdBajaDocumento inner join usuario u on u.IdUsuario = bd.IdUsuario
									WHERE bd.IdSucursal = ? AND bdd.IdArticulo = ? AND bd.FechaEnviada BETWEEN ? AND ? group by bd.IdBajaDoc)
									UNION ALL
									(SELECT 7 as 'Tipo', u.nombre, DATE_FORMAT(ncd.FechaCreacion, '%d/%m/%Y') as Fecha, nd.IdCreditoDebito, ncd.Serie, ncd.Numero, sum(nd.Cantidad), 0, 0, ncd.FechaCreacion as fecha2
									FROM nota_credito_debito ncd inner join nota_detalle nd on ncd.IdCreditoDebito = nd.IdCreditoDebito Inner join usuario u ON u.IdUsuario = ncd.IdUsuarioCreacion
									WHERE ncd.IdTipoNota=1 AND ncd.IdSucursal = ? AND nd.IdArticulo = ? AND ncd.FechaCreacion BETWEEN ? AND ? group by nd.IdCreditoDebito)
									UNION ALL
									(SELECT k.tipo_movimiento as 'Tipo', u.Nombre, DATE_FORMAT(k.fecha_movimiento, '%d/%m/%Y')  as  Fecha, k.IdKardex, null, null, k.existencia as Cantidad, k.Costo as 'Costo', 0, k.fecha_movimiento as fecha2
									FROM kardex k inner join articulo a on k.IdArticulo = a.IdArticulo INNER JOIN usuario u ON u.IdUsuario = a.IdCreacion
									WHERE k.IdSucursal = ? AND k.IdArticulo = ? AND k.fecha_movimiento > '2020-08-25' group by k.IdArticulo) order by fecha2 desc

								", [$idSucursal, $id_prod, $fechas1, $fechas2, $idSucursal, $id_prod, $fechas1, $fechas2, $idSucursal, $id_prod, $fechas1, $fechas2, $idSucursal, $codInterno, $fechas1, $fechas2, $idSucursal, $codInterno, $fechas1, $fechas2, $idSucursal, $id_prod, $fechas1, $fechas2, $idSucursal, $id_prod, $fechas1, $fechas2, $idSucursal, $id_prod]);
        return $reporteKardex;
    }

    private function consultaKardexAlmacen($idSucursal, $id_prod, $fechas1, $fechas2, $codInterno)
    {
        /*$reporteKardex = DB::select("(SELECT 3 as Tipo, NombreOrigen as Nombre, DATE_FORMAT(fechaTraspaso, '%d/%m/%Y') as Fecha, id, 'Transpaso' as Serie, 'Entrada' as Numero, sum(Cantidad) as Cantidad,0 as Costo, 0 as Total from historico_traspaso
        WHERE Destino=? AND TipoDestino = 2 AND CodigoInterno=? AND fechaTraspaso BETWEEN ? AND ? group by Destino)
        UNION ALL (SELECT 5, NombreDestino, DATE_FORMAT(fechaTraspaso, '%d/%m/%Y') as Fecha, id, 'Transpaso', 'Salida', sum(Cantidad),0,0 from historico_traspaso
        WHERE origen=? AND TipoOrigen = 2 AND CodigoInterno=? AND fechaTraspaso BETWEEN ? AND ? group by Origen) order by Fecha asc
        ", [$idSucursal, $id_prod, $fechas1, $fechas2,  $idSucursal, $id_prod, $fechas1, $fechas2, $idSucursal, $id_prod, $fechas1, $fechas2, $idSucursal, $codInterno, $fechas1, $fechas2, $idSucursal, $codInterno, $fechas1, $fechas2]);
        return  $reporteKardex;*/
        /*$reporteKardex =

    return  $reporteKardex;*/

    }

    private function productosAlmacen($idSucursal, $idAlmacen)
    {
        $productos = DB::table('almacen_producto')
            ->where('IdSucursal', $idSucursal)
            ->where('IdAlmacen', $idAlmacen)
            ->get();
        return $productos;
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

    public function selectLocal(Request $req)
    {
        if ($req->ajax()) {
            $idLocal = $req->local;
            $idSucursal = Session::get('idSucursal');
            $identificador = explode("*", $idLocal);
            if (is_numeric($identificador[0])) {
                $productos = $this->productosAlmacen($idSucursal, $idLocal);
            } else {
                $loadDatos = new DatosController();
                $productos = $loadDatos->getProductos($idSucursal);
            }
            return Response([$productos]);
        }
    }

    protected function validateReporteKardex(Request $request)
    {
        $this->validate($request, [
            'local' => 'required',
            'fecha' => 'required',
        ]);
    }
}
