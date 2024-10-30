<?php

namespace App\Http\Controllers\Administracion\Almacen;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DB;
use Illuminate\Http\Request;
use Session;

class EmparejarStockController extends Controller
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
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $almacenes = $loadDatos->getAlmacenes($usuarioSelect->CodigoCliente, $idSucursal);

        $cantidadArticulos = $this->getArticulosCantidad($idSucursal);
        $cantidadArticulos = $cantidadArticulos->filter(function ($value, $key) {
            return $value->Stock != $value->SumaTotal;
        });
        $cantidadArticulos = $cantidadArticulos->sortBy('Stock')->values();

        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'almacenes' => $almacenes, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'cantidadArticulos' => $cantidadArticulos];

        return view('administracion/almacen/stock/stock', $array);
    }

    // Nueva funcion 19/01/2023
    public function verVistaStockArticulos(Request $req)
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

        $stockArticulos = $this->getProductos($idSucursal);
        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'stockArticulos' => $stockArticulos];

        return view('administracion/almacen/stock/stockArticulos', $array);
    }

    public function getProductos($idSucursal)
    {
        try {
            $articulos = DB::table('articulo')
                ->join('stock', 'articulo.IdArticulo', '=', 'stock.IdArticulo')
                ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                ->select('articulo.IdArticulo', 'articulo.IdTipoMoneda', 'articulo.CodigoInterno', 'articulo.Codigo as CodigoBarra', 'articulo.Descripcion', 'articulo.Stock', 'articulo.Costo', 'articulo.Precio', 'marca.Nombre AS Marca', 'articulo.IdUnidadMedida', DB::raw('SUM(stock.Cantidad) as SumaTotal'))
                ->where('articulo.IdTipo', 1)
                ->where('articulo.IdSucursal', $idSucursal)
                ->where('articulo.Estado', 'E')
                ->groupBy(DB::raw("stock.IdArticulo"))
                ->get();

            return $articulos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    // Fin

    public function actualizarStock(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        if ($req->ajax()) {

            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $articulos = $req->articulos;
            if ($req->btnRegularizar == 'inventario') {
                $motivo = 'Inventario';
            } else {
                $motivo = 'Latencia de Internet';
            }

            for ($i = 0; $i < count($articulos); $i++) {

                $array = ['stock' => $articulos[$i]['stockActualizado']];
                DB::table('articulo')
                    ->where('IdArticulo', $articulos[$i]['idArticulo'])
                    ->update($array);

                // insertar cambios de stock en kardex
                if ($articulos[$i]['stockReal'] < $articulos[$i]['stockActualizado']) {
                    if ($articulos[$i]['stockReal'] < 0) {
                        $kardex = array(
                            'CodigoInterno' => $articulos[$i]['codigoInterno'],
                            'fecha_movimiento' => date("Y-m-d H:i:s"),
                            'tipo_movimiento' => 18, //18  Entrada para  kardex
                            'usuario_movimiento' => $idUsuario,
                            'documento_movimiento' => 'Regularización',
                            'existencia' => $articulos[$i]['stockActualizado'],
                            'costo' => $articulos[$i]['precio'],
                            'IdArticulo' => $articulos[$i]['idArticulo'],
                            'IdSucursal' => $idSucursal,
                            'Cantidad' => $articulos[$i]['stockActualizado'],
                            'Descuento' => 0,
                            'ImporteEntrada' => 0,
                            'ImporteSalida' => 0,
                            'estado' => 1,
                        );
                        DB::table('kardex')
                            ->insert($kardex);
                    } else {
                        $kardex = array(
                            'CodigoInterno' => $articulos[$i]['codigoInterno'],
                            'fecha_movimiento' => date("Y-m-d H:i:s"),
                            'tipo_movimiento' => 18, //18  Entrada para  kardex
                            'usuario_movimiento' => $idUsuario,
                            'documento_movimiento' => 'Regularización',
                            'existencia' => $articulos[$i]['stockActualizado'],
                            'costo' => $articulos[$i]['precio'],
                            'IdArticulo' => $articulos[$i]['idArticulo'],
                            'IdSucursal' => $idSucursal,
                            'Cantidad' => $articulos[$i]['stockActualizado'] - $articulos[$i]['stockReal'],
                            'Descuento' => 0,
                            'ImporteEntrada' => 0,
                            'ImporteSalida' => 0,
                            'estado' => 1,
                        );
                        DB::table('kardex')
                            ->insert($kardex);
                    }

                    // Emparejar stock
                    DB::table('stock')
                        ->where('IdArticulo', $articulos[$i]['idArticulo'])
                        ->update(['Cantidad' => 0]);
                    $stock = $loadDatos->getUltimoStock($articulos[$i]['idArticulo']);
                    DB::table('stock')
                        ->where('IdStock', $stock->IdStock)
                        ->update(['Cantidad' => $articulos[$i]['stockActualizado']]);
                    // Fin
                }
                if ($articulos[$i]['stockReal'] > $articulos[$i]['stockActualizado']) {

                    $kardex = array(
                        'CodigoInterno' => $articulos[$i]['codigoInterno'],
                        'fecha_movimiento' => date("Y-m-d H:i:s"),
                        'tipo_movimiento' => 19, //19  salida para  kardex
                        'usuario_movimiento' => $idUsuario,
                        'documento_movimiento' => 'Regularización ',
                        'existencia' => $articulos[$i]['stockActualizado'],
                        'costo' => $articulos[$i]['precio'],
                        'IdArticulo' => $articulos[$i]['idArticulo'],
                        'IdSucursal' => $idSucursal,
                        'Cantidad' => $articulos[$i]['stockReal'] - $articulos[$i]['stockActualizado'],
                        'Descuento' => 0,
                        'ImporteEntrada' => 0,
                        'ImporteSalida' => 0,
                        'estado' => 1,
                    );
                    DB::table('kardex')
                        ->insert($kardex);

                    // Emparejar stock
                    $diferencia = $articulos[$i]['stockActualizado'] - $articulos[$i]['sumaTotalStock'];
                    $stock = $loadDatos->getUltimoStock($articulos[$i]['idArticulo']);
                    $emparejar = floatval($diferencia) + floatval($stock->Cantidad);
                    DB::table('stock')
                        ->where('IdStock', $stock->IdStock)
                        ->update(['Cantidad' => $emparejar]);
                    // Fin
                }

                if ($articulos[$i]['stockReal'] == $articulos[$i]['stockActualizado']) {
                    $diferencia = $articulos[$i]['stockActualizado'] - $articulos[$i]['sumaTotalStock'];
                    $stock = $loadDatos->getUltimoStock($articulos[$i]['idArticulo']);
                    $emparejar = floatval($diferencia) + floatval($stock->Cantidad);
                    DB::table('stock')
                        ->where('IdStock', $stock->IdStock)
                        ->update(['Cantidad' => $emparejar]);
                }
                // Fin
                $regularizacionStock = array(
                    'IdArticulo' => $articulos[$i]['idArticulo'],
                    'Precio' => $articulos[$i]['precio'],
                    'Costo' => $articulos[$i]['costo'],
                    'StockSistema' => $articulos[$i]['stockReal'],
                    'StockAlmacen' => $articulos[$i]['stockActualizado'],
                    'FechaCreacion' => date("Y-m-d H:i:s"),
                    'IdUsuarioRegularizacion' => $idUsuario,
                    'IdSucursal' => $idSucursal,
                    'Motivo' => $motivo,
                );
                DB::table('detalle_regularizar_stock')
                    ->insert($regularizacionStock);
            }
            return Response(['Success', 'La regularizacion fue un éxito']);
        }
    }

    // public function actualizarStock(Request $req)
    // {
    //     if ($req->session()->has('idUsuario')) {
    //         $idUsuario = Session::get('idUsuario');
    //     } else {
    //         Session::flush();
    //         return redirect('/')->with('out', 'Sesión de usuario Expirado');
    //     }
    //     if ($req->ajax()) {

    //         $loadDatos = new DatosController();
    //         $idSucursal = Session::get('idSucursal');

    //         $idArticulo = $req->idArticulo;
    //         $precio = $req->precio;
    //         $costo = $req->costo;
    //         $stockReal = $req->stockReal;
    //         $stockActualizado = $req->stockActualizado;
    //         $sumaTotalStock = $req->sumaTotalStock;
    //         $arrayCodigoInterno = $req->codigoInterno;

    //         for ($i = 0; $i < count($idArticulo); $i++) {

    //             $array = ['stock' => $stockActualizado[$i]];
    //             DB::table('articulo')
    //                 ->where('IdArticulo', $idArticulo[$i])
    //                 ->update($array);

    //             // insertar cambios de stock en kardex
    //             if ($stockReal[$i] < $stockActualizado[$i]) {
    //                 if ($stockReal[$i] < 0) {
    //                     $kardex = array(
    //                         'CodigoInterno' => $arrayCodigoInterno[$i],
    //                         'fecha_movimiento' => date("Y-m-d H:i:s"),
    //                         'tipo_movimiento' => 18, //18  Entrada para  kardex
    //                         'usuario_movimiento' => $idUsuario,
    //                         'documento_movimiento' => 'Regularización',
    //                         'existencia' => $stockActualizado[$i],
    //                         'costo' => $precio[$i],
    //                         'IdArticulo' => $idArticulo[$i],
    //                         'IdSucursal' => $idSucursal,
    //                         'Cantidad' => $stockActualizado[$i],
    //                         'Descuento' => 0,
    //                         'ImporteEntrada' => 0,
    //                         'ImporteSalida' => 0,
    //                         'estado' => 1,
    //                     );
    //                     DB::table('kardex')
    //                         ->insert($kardex);
    //                 } else {
    //                     $kardex = array(
    //                         'CodigoInterno' => $arrayCodigoInterno[$i],
    //                         'fecha_movimiento' => date("Y-m-d H:i:s"),
    //                         'tipo_movimiento' => 18, //18  Entrada para  kardex
    //                         'usuario_movimiento' => $idUsuario,
    //                         'documento_movimiento' => 'Regularización',
    //                         'existencia' => $stockActualizado[$i],
    //                         'costo' => $precio[$i],
    //                         'IdArticulo' => $idArticulo[$i],
    //                         'IdSucursal' => $idSucursal,
    //                         'Cantidad' => $stockActualizado[$i] - $stockReal[$i],
    //                         'Descuento' => 0,
    //                         'ImporteEntrada' => 0,
    //                         'ImporteSalida' => 0,
    //                         'estado' => 1,
    //                     );
    //                     DB::table('kardex')
    //                         ->insert($kardex);
    //                 }

    //                 // Emparejar stock
    //                 DB::table('stock')
    //                     ->where('IdArticulo', $idArticulo[$i])
    //                     ->update(['Cantidad' => 0]);
    //                 $stock = $loadDatos->getUltimoStock($idArticulo[$i]);
    //                 DB::table('stock')
    //                     ->where('IdStock', $stock->IdStock)
    //                     ->update(['Cantidad' => $stockActualizado[$i]]);
    //                 // Fin
    //             }
    //             if ($stockReal[$i] > $stockActualizado[$i]) {

    //                 $kardex = array(
    //                     'CodigoInterno' => $arrayCodigoInterno[$i],
    //                     'fecha_movimiento' => date("Y-m-d H:i:s"),
    //                     'tipo_movimiento' => 19, //19  salida para  kardex
    //                     'usuario_movimiento' => $idUsuario,
    //                     'documento_movimiento' => 'Regularización ',
    //                     'existencia' => $stockActualizado[$i],
    //                     'costo' => $precio[$i],
    //                     'IdArticulo' => $idArticulo[$i],
    //                     'IdSucursal' => $idSucursal,
    //                     'Cantidad' => $stockReal[$i] - $stockActualizado[$i],
    //                     'Descuento' => 0,
    //                     'ImporteEntrada' => 0,
    //                     'ImporteSalida' => 0,
    //                     'estado' => 1,
    //                 );
    //                 DB::table('kardex')
    //                     ->insert($kardex);

    //                 // Emparejar stock
    //                 $diferencia = $stockActualizado[$i] - $sumaTotalStock[$i];
    //                 $stock = $loadDatos->getUltimoStock($idArticulo[$i]);
    //                 $emparejar = floatval($diferencia) + floatval($stock->Cantidad);
    //                 DB::table('stock')
    //                     ->where('IdStock', $stock->IdStock)
    //                     ->update(['Cantidad' => $emparejar]);
    //                 // Fin
    //             }

    //             if ($stockReal[$i] == $stockActualizado[$i]) {
    //                 $diferencia = $stockActualizado[$i] - $sumaTotalStock[$i];
    //                 $stock = $loadDatos->getUltimoStock($idArticulo[$i]);
    //                 $emparejar = floatval($diferencia) + floatval($stock->Cantidad);
    //                 DB::table('stock')
    //                     ->where('IdStock', $stock->IdStock)
    //                     ->update(['Cantidad' => $emparejar]);
    //             }
    //             // Fin
    //             $regularizacionStock = array(
    //                 'IdArticulo' => $idArticulo[$i],
    //                 'Precio' => $precio[$i],
    //                 'Costo' => $costo[$i],
    //                 'StockSistema' => $stockReal[$i],
    //                 'StockAlmacen' => $stockActualizado[$i],
    //                 'FechaCreacion' => date("Y-m-d H:i:s"),
    //                 'IdUsuarioRegularizacion' => $idUsuario,
    //                 'IdSucursal' => $idSucursal,
    //             );
    //             DB::table('detalle_regularizar_stock')
    //                 ->insert($regularizacionStock);
    //         }
    //         return Response(['Success', 'La regularizacion fue un éxito']);
    //     }
    // }

    public function validarClave(Request $req)
    {
        if ($req->ajax()) {
            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
            $loadDatos = new DatosController();
            $password = $req->password;

            $usuarioCodigo = $loadDatos->getUsuarioSelect($idUsuario);
            $codigoCliente = $usuarioCodigo->CodigoCliente;
            $respuesta = DB::table('usuario')
                ->select('usuario.ClaveDeComprobacion', 'usuario.Nombre', 'usuario.IdSucursal')
                ->where('usuario.CodigoCliente', $codigoCliente)
                ->where('usuario.Cliente', 1)
                ->get();

            if ($respuesta) {
                if ((password_verify($password, $respuesta[0]->ClaveDeComprobacion))) {
                    $password = (password_verify($password, $respuesta[0]->ClaveDeComprobacion));
                    return Response(['Success', 'La clave si coincide']);
                }
            }
        }
    }

    public function getArticulosCantidad($idSucursal)
    {
        try {
            $articulos = DB::table('articulo')
                ->join('stock', 'articulo.IdArticulo', '=', 'stock.IdArticulo')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                ->select('articulo.IdArticulo', 'articulo.Descripcion', 'articulo.Codigo', 'articulo.IdTipoMoneda', 'articulo.IdUnidadMedida', 'articulo.CodigoInterno', 'articulo.Stock', 'articulo.Costo', 'articulo.Precio', 'unidad_medida.Nombre as UM', 'articulo.IdMarca', 'marca.Nombre AS NombreMarca', DB::raw('SUM(stock.Cantidad) as SumaTotal'))
                ->where('articulo.IdTipo', 1)
                ->where('articulo.IdSucursal', $idSucursal)
                ->where('articulo.Estado', 'E')
                ->groupBy(DB::raw('stock.IdArticulo'))
                ->get();
            return $articulos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

}
