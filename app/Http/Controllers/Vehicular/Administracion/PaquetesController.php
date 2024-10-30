<?php

namespace App\Http\Controllers\Vehicular\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Http\Request;
use Session;

class PaquetesController extends Controller
{

    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $almacenes = $loadDatos->getAlmacenes($usuarioSelect->CodigoCliente, $idSucursal);

        $reportePaquete = $this->getPaquetesPromocionales($idSucursal);
        $reportePaquete = collect($reportePaquete);
        $paquetesDolares = $reportePaquete->where('IdTipoMoneda', 2);
        $paquetesSoles = $reportePaquete->where('IdTipoMoneda', 1);

        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'almacenes' => $almacenes, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'paquetesDolares' => $paquetesDolares, 'paquetesSoles' => $paquetesSoles];

        return view('vehicular/administracion/paquetesPromocionales/paquetes', $array);
    }

    public function verVistaCrearPaquete(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
        }

        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $sucursales = $loadDatos->getSucursales($usuarioSelect->CodigoCliente, $usuarioSelect->IdOperador);
        $text = '';
        $cod_cliente = DB::table('sucursal')
            ->select('CodigoCliente')
            ->where('IdSucursal', $idSucursal)
            ->first();

        $sucPrincipal = DB::table('sucursal')
            ->select('IdSucursal')
            ->where('CodigoCliente', $cod_cliente->CodigoCliente)
            ->where('Principal', 1)
            ->first();

        // traer productos, servicios y categorias
        if ($sucPrincipal->IdSucursal == $idSucursal) {
            $productos = $loadDatos->getProductosPagination($idSucursal, $text, 1, 0);
            $productosDolares = $loadDatos->getProductosPagination($idSucursal, $text, 2, 0);
        } else {
            $productos = $loadDatos->paginarAjaxProdSucursal($idSucursal, $text, 1, 0);
            $productosDolares = $loadDatos->paginarAjaxProdSucursal($idSucursal, $text, 2, 0);
        }

        $servicios = $loadDatos->getServiciosPagination($idSucursal, 1, $text);
        $serviciosDolares = $loadDatos->getServiciosPagination($idSucursal, 2, $text);
        $categorias = $loadDatos->getCategorias($usuarioSelect->CodigoCliente);

        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'sucursales' => $sucursales, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'productos' => $productos, 'productosDolares' => $productosDolares, 'servicios' => $servicios, 'serviciosDolares' => $serviciosDolares, 'categorias' => $categorias];
        return view('vehicular/administracion/paquetesPromocionales/crearPaquete', $array);

    }

    public function verVistaEditarPaquete(Request $req, $idPaquete)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $sucursales = $loadDatos->getSucursales($usuarioSelect->CodigoCliente, $usuarioSelect->IdOperador);
        $text = '';
        $cod_cliente = DB::table('sucursal')
            ->select('CodigoCliente')
            ->where('IdSucursal', $idSucursal)
            ->first();

        $sucPrincipal = DB::table('sucursal')
            ->select('IdSucursal')
            ->where('CodigoCliente', $cod_cliente->CodigoCliente)
            ->where('Principal', 1)
            ->first();

        // traer productos, servicios y categorias
        if ($sucPrincipal->IdSucursal == $idSucursal) {
            $productos = $loadDatos->getProductosPagination($idSucursal, $text, 1, 0);
            $productosDolares = $loadDatos->getProductosPagination($idSucursal, $text, 2, 0);
        } else {
            $productos = $loadDatos->paginarAjaxProdSucursal($idSucursal, $text, 1, 0);
            $productosDolares = $loadDatos->paginarAjaxProdSucursal($idSucursal, $text, 2, 0);
        }

        $servicios = $loadDatos->getServiciosPagination($idSucursal, 1, $text);
        $serviciosDolares = $loadDatos->getServiciosPagination($idSucursal, 2, $text);
        $categorias = $loadDatos->getCategorias($usuarioSelect->CodigoCliente);

        $itemsPaquete = $this->getItemsDetallePaquete($idPaquete);
        // dd($itemsPaquete);
        $nombrePaquete = $itemsPaquete->pluck('NombrePaquete')->first();
        $totalPaquete = $itemsPaquete->pluck('TotalPaquete')->first();
        $costoPaquete = $itemsPaquete->pluck('CostoPaquete')->first();
        $tipoMoneda = $itemsPaquete->pluck('IdTipoMoneda')->first();
        $idPaquete = $idPaquete;

        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'sucursales' => $sucursales, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'productos' => $productos, 'productosDolares' => $productosDolares, 'servicios' => $servicios, 'serviciosDolares' => $serviciosDolares, 'categorias' => $categorias, 'itemsPaquete' => $itemsPaquete, 'nombrePaquete' => $nombrePaquete, 'tipoMoneda' => $tipoMoneda, 'idPaquete' => $idPaquete, 'totalPaquete' => $totalPaquete, 'costoPaquete' => $costoPaquete];
        return view('vehicular/administracion/paquetesPromocionales/editarPaquete', $array);
    }

    public function store(Request $req)
    {
        if ($req->ajax()) {
            $req->fechaEmitida = date('Y-m-d');
            $fecha = $req->fechaEmitida;
            $date = DateTime::createFromFormat('Y-m-d', $fecha);

            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
            $nombrePaquete = ucwords($req->nombrePaquete);
            $tipoMoneda = $req->tipoMoneda;
            $fechaConvertida = $date->format('Y-m-d H:i:s');
            $precioPaquete = $req->totalPaquete;
            $costoPaquete = $req->costoPaquete;
            if ($nombrePaquete == null) {
                return Response(['alert1', 'Por favor, agrege el nombre del Paquete Miscelaneo']);
            }

            if ($req->Id == null) {
                return Response(['alert2', 'Por favor, agrege productos o servicios']);
            }

            $respuesta = DB::table('paquetes_promocionales')
                ->where('IdSucursal', $idSucursal)
                ->where('NombrePaquete', $nombrePaquete)
                ->exists();
            if ($respuesta) {
                return Response(['alert3', 'El nombre Ingresado Ya existe']);
            }
            $array = ['NombrePaquete' => $nombrePaquete, 'IdTipoMoneda' => $tipoMoneda, 'Idsucursal' => $idSucursal, 'FechaCreacion' => $fechaConvertida, 'FechaModificacion' => $fechaConvertida, 'IdUsuarioCreacion' => $idUsuario, 'IdUsuarioModificacion' => $idUsuario, 'Estado' => 'E', 'Etiqueta' => 'PaquetePromocional', 'Precio' => $precioPaquete, 'Costo' => $costoPaquete];
            DB::table('paquetes_promocionales')->insert($array);

            $paquete = DB::table('paquetes_promocionales')
                ->orderBy('IdPaquetePromocional', 'desc')
                ->first();

            $idPaquete = $paquete->IdPaquetePromocional;
            for ($i = 0; $i < count($req->Id);
                $i++) {
                $arrayRelacion = [
                    'IdPaquetePromocional' => $idPaquete,
                    'IdArticulo' => $req->Id[$i],
                    'CodigoArticulo' => $req->Codigo[$i],
                    'Cantidad' => $req->Cantidad[$i],
                ];
                DB::table('articulo_paquetePromocional')->insert($arrayRelacion);
                usleep(200000);
            }
            return Response(['succes', 'Se Creo Correctamente el Paquete Promocional', $idPaquete]);
        }
    }

    // public function actualizar(Request $req)
    // {
    //     if ($req->ajax()) {
    //         try {
    //             DB::beginTransaction();

    //             // $arrayRelacion = ['IdCliente' => 'PERU'];
    //             // DB::table('cotizacion')->where('IdCotizacion', 1571)->delete();

    //             // $arrayRelacion = ['IdCliente' => 'PERU'];
    //             // DB::table('paquetes_promocionales')->where('IdPaquetePromocional', 10)->delete();

    //             // $arrayRelacion = ['IdCliente' => 'PERU'];
    //             // DB::table('cotizacion')->insert($arrayRelacion);

    //             // $array = ['NombrePaquete' => 'PERU-12'];
    //             // DB::table('paquetes_promocionales')->insert($array);
    //             $datos = [1, 2, 3];
    //             if (count($datos) >= 1) {
    //                 DB::table('detalle_transaccion')->where('IdDetalle', 1)->update(['Nombre' => 'detalle']);
    //                 $numero = 5;
    //                 $rsul = $nume + 7;
    //                 DB::table('prueba_transaccion')->insert(['Nombre' => 'peru']);

    //             }

    //             DB::commit();

    //         } catch (\Throwable$th) {
    //             DB::rollBack();
    //             return Response(['error', 'error']);

    //         }
    //         return Response(['succes', 'Se Actualizo Correctamente las pruebas']);
    //     }
    // }

    public function actualizar(Request $req)
    {
        if ($req->ajax()) {
            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
            $nombrePaquete = ucwords($req->nombrePaquete);
            $idPaquete = $req->idPaquete;
            $precioPaquete = $req->totalPaquete;
            $costoPaquete = $req->costoPaquete;
            $fecha = Carbon::now()->toDateTimeString();
            if ($nombrePaquete == null) {
                return Response(['alert1', 'Por favor, agrege el nombre del Paquete']);
            }

            if ($req->idsItems == null) {
                return Response(['alert2', 'Por favor, agrege productos o servicios']);
            }

            $array = ['NombrePaquete' => $nombrePaquete, 'Idsucursal' => $idSucursal, 'IdUsuarioModificacion' => $idUsuario, 'FechaModificacion' => $fecha, 'Precio' => $precioPaquete, 'Costo' => $costoPaquete];
            DB::table('paquetes_promocionales')->where('IdPaquetePromocional', $idPaquete)
                ->update($array);

            $arrayItemsNoEliminados = [];
            for ($i = 0; $i < count($req->idsItems);
                $i++) {
                array_push($arrayItemsNoEliminados, $req->idsItems[$i]);
                $arrayRelacion = [
                    'IdPaquetePromocional' => $idPaquete,
                    'IdArticulo' => $req->idsItems[$i],
                    'CodigoArticulo' => $req->Codigo[$i],
                    'Cantidad' => $req->cantidad[$i],
                ];

                $verificarExistencia = $this->verificarItemsPaquete($req->idsItems[$i], $idPaquete);
                if ($verificarExistencia) {
                    DB::table('articulo_paquetePromocional')
                        ->where('IdArticulo', $req->idsItems[$i])
                        ->where('IdPaquetePromocional', $idPaquete)
                        ->update($arrayRelacion);
                    usleep(200000);
                } else {
                    DB::table('articulo_paquetePromocional')
                        ->insert($arrayRelacion);
                    usleep(200000);
                }
            }

            DB::table('articulo_paquetePromocional')
                ->where('IdPaquetePromocional', $idPaquete)
                ->whereNotIn('IdArticulo', $arrayItemsNoEliminados)
                ->delete();
            return Response(['succes', 'Se Actualizo Correctamente el Paquete Promocional', $idPaquete]);
        }
    }

    private function verificarItemsPaquete($idArticulo, $idPaquete)
    {
        $resultado = DB::table('articulo_paquetePromocional')
            ->where('IdArticulo', $idArticulo)
            ->where('IdPaquetepromocional', $idPaquete)
            ->first();
        return $resultado;
    }

    public function verVistaDetallePaquete(Request $req, $idPaquete)
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

        $itemsPaquete = $this->getItemsDetallePaquete($idPaquete);
        $nombrePaquete = $itemsPaquete->pluck('NombrePaquete')->first();
        $totalPaquete = $itemsPaquete->pluck('Total')->first();
        $costoPaquete = $itemsPaquete->pluck('Costo')->first();
        $itemsEliminado = $itemsPaquete->where("Estado", "D");
        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'itemsPaquete' => $itemsPaquete, 'nombrePaquete' => $nombrePaquete, 'itemsEliminado' => $itemsEliminado, 'totalPaquete' => $totalPaquete, 'costoPaquete' => $costoPaquete];
        return view('vehicular/administracion/paquetesPromocionales/detallePaquete', $array);
    }

    public function paginationProductos(Request $req)
    {
        if ($req->ajax()) {
            $idSucursal = Session::get('idSucursal');
            $loadDatos = new DatosController();

            $cod_cliente = DB::table('sucursal')
                ->select('CodigoCliente')
                ->where('IdSucursal', $idSucursal)
                ->first();

            $sucPrincipal = DB::table('sucursal')
                ->select('IdSucursal')
                ->where('CodigoCliente', $cod_cliente->CodigoCliente)
                ->where('Principal', 1)
                ->first();

            if ($sucPrincipal->IdSucursal == $idSucursal) {
                $productos = $loadDatos->getProductosPagination($idSucursal, $req->textoBuscar, $req->tipoMoneda, $req->idCategoria);
            } else {
                $productos = $loadDatos->paginarAjaxProdSucursal($idSucursal, $req->textoBuscar, $req->tipoMoneda, $req->idCategoria);
            }

            return Response($productos);
        }
    }

    public function paginationServicios(Request $req)
    {
        if ($req->ajax()) {
            $idSucursal = Session::get('idSucursal');
            $text2 = Session::get('text');
            $loadDatos = new DatosController();
            $servicios = $loadDatos->getServiciosPagination($idSucursal, $req->tipoMoneda, $text2);
            return Response($servicios);
        }
    }

    public function searchServicio(Request $req)
    {
        if ($req->ajax()) {
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            Session::put('text', $req->textoBuscar);
            $articulos = $loadDatos->getBuscarServiciosVentas($req->textoBuscar, $req->tipoMoneda, $idSucursal);
            return Response($articulos);
        }
    }

    public function searchProducto(Request $req)
    {
        if ($req->ajax()) {
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');

            $cod_cliente = DB::table('sucursal')
                ->select('CodigoCliente')
                ->where('IdSucursal', $idSucursal)
                ->first();

            $sucPrincipal = DB::table('sucursal')
                ->select('IdSucursal')
                ->where('CodigoCliente', $cod_cliente->CodigoCliente)
                ->where('Principal', 1)
                ->first();

            if ($sucPrincipal->IdSucursal == $idSucursal) {
                $articulos = $loadDatos->getBuscarProductosVentas($req->textoBuscar, $req->tipoMoneda, $idSucursal, $req->idCategoria);
            } else {
                $articulos = $loadDatos->buscarAjaxProdSucursal($req->textoBuscar, $req->tipoMoneda, $idSucursal, $req->idCategoria);
            }

            return Response($articulos);
        }
    }

    protected function getItemsDetallePaquete($idPaquete)
    {
        try {
            $itemsDetalle = DB::table('articulo_paquetePromocional AS app')
                ->join('paquetes_promocionales AS pp', 'app.IdPaquetePromocional', '=', 'pp.IdPaquetePromocional')
                ->join('articulo', 'app.IdArticulo', '=', 'articulo.IdArticulo')
                ->select('app.CodigoArticulo', 'articulo.Descripcion', 'articulo.IdMarca', 'articulo.Precio', 'articulo.Costo', 'articulo.Stock', 'articulo.Codigo AS codigoBarra', 'pp.NombrePaquete', 'pp.IdTipoMoneda', 'app.IdPaquetePromocional', 'articulo.IdTipo AS idTipoArticulo', 'articulo.IdArticulo', 'articulo.IdCategoria', 'articulo.Estado', 'app.Cantidad', 'articulo.IdUnidadMedida')
                ->where('app.IdPaquetePromocional', $idPaquete)
                ->orderBy('app.IdDetallePaquetePromo', 'asc')
                ->get();
            for ($i = 0; $i < count($itemsDetalle);
                $i++) {
                if ($itemsDetalle[$i]->IdMarca != null) {
                    $marca = DB::table('marca')
                        ->where('IdMarca', $itemsDetalle[$i]->IdMarca)
                        ->first();
                    $itemsDetalle[$i]->nombreMarca = $marca->Nombre;
                } else {
                    $itemsDetalle[$i]->nombreMarca = '-';
                }

                if ($itemsDetalle[$i]->idTipoArticulo == 2) {
                    $itemsDetalle[$i]->Stock = '-';
                }

                if ($itemsDetalle[$i]->codigoBarra == null) {
                    $itemsDetalle[$i]->codigoBarra = '';
                }

                if ($itemsDetalle[$i]->IdCategoria != null) {
                    $categoria = DB::table('categoria')
                        ->where('IdCategoria', $itemsDetalle[$i]->IdCategoria)
                        ->first();
                    $itemsDetalle[$i]->nombreCategoria = $categoria->Nombre;
                } else {
                    $itemsDetalle[$i]->nombreCategoria = '-';
                }
            }

            return $itemsDetalle;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function eliminarPaquete(Request $req, $idPaquete)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $fecha = Carbon::now()->toDateTimeString();
        $array = ['Estado' => 'D', 'FechaEliminacion' => $fecha, 'IdUsuarioEliminacion' => $idUsuario];
        DB::table('paquetes_promocionales')
            ->where('IdPaquetePromocional', $idPaquete)
            ->where('IdSucursal', $idSucursal)
            ->update($array);

        return redirect('vehicular/administracion/paquetes-promocionales')->with('status', 'El paquete promocional se eliminó Correctamente');
    }

    protected function getPaquetesPromocionales($idSucursal)
    {
        try {
            $paquetes = DB::table('paquetes_promocionales')
                ->where('IdSucursal', $idSucursal)
                ->where('Estado', 'E')
                ->orderBy('IdPaquetePromocional', 'asc')
                ->get();
            return $paquetes;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // public function actualizarTransaccion(Request $req)
// {
//     if ($req->ajax()) {
//         try {
//             DB::beginTransaction();
//             $idUsuario = Session::get('idUsuario');
//             $idSucursal = Session::get('idSucursal');
//             $nombrePaquete = ucwords($req->nombrePaquete);
//             $idPaquete = $req->idPaquete;
//             if ($nombrePaquete == null) {
//                 return Response(['alert1', 'Por favor, agrege el nombre del Paquete']);
//             }

//             if ($req->idsItems == null) {
//                 return Response(['alert2', 'Por favor, agrege productos o servicios']);
//             }

//             $array = ['NombrePaquete' => $nombrePaquete, 'Idsucursal' => $idSucursal, 'IdUsuarioCreacion' => $idUsuario, 'Estado' => 'E'];
//             DB::table('paquetes_miscelaneos')->where('IdPaqueteMiscelaneo', $idPaquete)
//                 ->update($array);

//             $arrayItemsNoEliminados = [];
//             for ($i = 0; $i < count($req->idsItems);
//                 $i++) {
//                 array_push($arrayItemsNoEliminados, $req->idsItems[$i]);
//                 $arrayRelacion = [
//                     'IdPaqueteMiscelaneo' => $idPaquete,
//                     'IdArticulo' => $req->idsItems[$i],
//                     'CodigoArticulo' => $req->Codigo[$i],
//                 ];

//                 $verificarExistencia = $this->verificarItemsPaquete($req->idsItems[$i], $idPaquete);
//                 if ($verificarExistencia) {
//                     DB::table('detalle_articulo_paqueteMiscelaneo')
//                         ->where('IdArticulo', $req->idsItems[$i])
//                         ->where('IdPaqueteMiscelaneo', $idPaquete)
//                         ->update($arrayRelacion);
//                     usleep(200000);
//                 } else {
//                     DB::table('detalle_articulo_paqueteMiscelaneo')
//                         ->insert($arrayRelacion);
//                     usleep(200000);
//                 }
//             }

//             DB::table('detalle_articulo_paqueteMiscelaneo')
//                 ->where('IdPaqueteMiscelaneo', $idPaquete)
//                 ->whereNotIn('IdArticulo', $arrayItemsNoEliminados)
//                 ->delete();

//             DB::commit();

//         } catch (\Throwable$th) {
//             DB::rollBack();
//             return Response(['error', 'error']);

//         }
//         return Response(['succes', 'Se Actualizo Correctamente el Paquete Miscelaneo', $idPaquete]);
//     }
// }

//  public function actualizar(Request $req)
//     {
//         if ($req->ajax()) {
//             try {
//                 DB::beginTransaction();

//                 // DB::table('prueba_transaccion')->where('IdTransaccion', 2)->delete();
//                 DB::table('prueba_transaccion')->insert(['Nombre' => 'transaccion']);

//                 DB::table('detalle_transaccion')->insert(['Nombre' => 'detalle']);

//                 $idGrupo = 'hola';
//                 if ($idGrupo == null) {
//                     return Response(['succes', 'Por favor, agrege productos o servicios']);
//                 }

//                 DB::commit();

//             } catch (\Throwable$th) {
//                 DB::rollBack();
//                 $idMaximo = DB::table('prueba_transaccion')->SELECT(DB::RAW("MAX(IdTransaccion) AS IdMaximo"))->first();
//                 $idMaximo = $idMaximo->IdMaximo + 1;
//                 DB::statement("ALTER TABLE prueba_transaccion AUTO_INCREMENT=" . $idMaximo);
//                 return Response(['alert2', 'error']);

//                 // return view('vehicular/grupos/grupos');

//             }
//             return Response(['alert3', 'error']);
//         }
//     }

}

// try {
//     DB::transaction(function () {
//         DB::table('notificacion_mantenimiento')->insert(['IdVehiculo' => 1]);

//         DB::table('grupos_productos_servicios')->insert(['NombreGru' => "MouseLenovo"]);

//     });
// } catch (\Throwable$th) {
//     dd('Error');
// }

// try {
//     DB::beginTransaction();

//     DB::table('notificacion_mantenimiento')->insert(['IdVehiculo' => 1]);
//     // DB::table('notificacion_mantenimiento')->where('IdNotificacionMantenimiento', 2)->delete();

//     DB::table('grupos_productos_servicios')->insert(['NombreGrupooo' => "MouseLenovo"]);
//     // DB::afterCommit(function () {
//     //     DB::table('notificacion_mantenimiento')->insert(['IdVehiculo' => 1]);

//     //     // DB::table('grupos_productos_servicios')->insert(['NombreGrupo' => "MouseLenovo"]);
//     // });
//     DB::commit();
//     dd("correcto");

// } catch (\Throwable$th) {
//     DB::rollBack();
//     $idMaximo = DB::table('notificacion_mantenimiento')->SELECT(DB::RAW("MAX(IdNotificacionMantenimiento) AS IdMaximo"))->first();
//     $idMaximo = $idMaximo->IdMaximo + 1;
//     DB::statement("ALTER TABLE notificacion_mantenimiento AUTO_INCREMENT=" . $idMaximo);
//     dd('Error');
// }

// public function store(Request $req)
// {
//     try {
//         if ($req->ajax()) {
//             DB::beginTransaction();
//             $req->fechaEmitida = date('Y-m-d');
//             $fecha = $req->fechaEmitida;
//             $date = DateTime::createFromFormat('Y-m-d', $fecha);

//             $idUsuario = Session::get('idUsuario');
//             $idSucursal = Session::get('idSucursal');
//             $nombreGrupo = ucwords($req->nombreGrupo);
//             $tipoMoneda = $req->tipoMoneda;
//             $fechaConvertida = $date->format('Y-m-d H:i:s');
//             if ($nombreGrupo == null) {
//                 return Response(['alert1', 'Por favor, agrege el nombre del Paquete Miscelaneo']);
//             }

//             if ($req->Id == null) {
//                 return Response(['alert2', 'Por favor, agrege productos o servicios']);
//             }

//             $respuesta = DB::table('paquetes_miscelaneos')
//                 ->where('IdSucursal', $idSucursal)
//                 ->where('NombrePaquete', $nombreGrupo)
//                 ->exists();
//             if ($respuesta) {
//                 return Response(['alert3', 'El nombre Ingresado Ya existe']);
//             }

//             $array = ['FechaCreacion' => $fechaConvertida, 'NombrePaquete' => $nombreGrupo, 'IdTipoMoneda' => $tipoMoneda, 'FechaModificacion' => $fechaConvertida, 'Idsucursal' => $idSucursal, 'IdUsuarioCreacion' => $idUsuario, 'IdUsuarioModificacion' => $idUsuario, 'Estado' => 'E'];
//             DB::table('paquetes_miscelaneos')->insert($array);

//             $paquete = DB::table('paquetes_miscelaneos')
//                 ->orderBy('IdPaqueteMiscelaneo', 'desc')
//                 ->first();

//             $idPaquete = $paquete->IdPaqueteMiscelaneo;
//             for ($i = 0; $i < count($req->Id);
//                 $i++) {
//                 $arrayRelacion = [
//                     'IdDetalle' => $idPaquete,
//                     'IdArticulo' => $req->Id[$i],
//                     'CodigoArticulo' => $req->Codigo[$i],
//                 ];
//                 DB::table('detalle_articulo_paqueteMiscelan')->insert($arrayRelacion);
//                 usleep(200000);
//             }
//             DB::commit();
//             return Response(['succes', 'Se Creo Correctamente el Grupo', $idPaquete]);
//         }
//     } catch (\Throwable$th) {
//         DB::rollBack();
//     }
// }

//  PROBAR ESTA FUNCION
// public function actualizar(Request $req)
//     {
//         if ($req->ajax()) {
//             try {
//                 DB::beginTransaction();
//                 $idUsuario = Session::get('idUsuario');
//                 $idSucursal = Session::get('idSucursal');
//                 $nombrePaquete = ucwords($req->nombrePaquete);
//                 $idPaquete = $req->idPaquete;
//                 $totalPaquete = $req->totalPaquete;
//                 $costoPaquete = $req->costoPaquete;
//                 $fecha = Carbon::now()->toDateTimeString();
//                 if ($nombrePaquete == null) {
//                     return Response(['alert1', 'Por favor, agrege el nombre del Paquete']);
//                 }

//                 if ($req->idsItems == null) {
//                     return Response(['alert2', 'Por favor, agrege productos o servicios']);
//                 }

//                 $array = ['NombrePaquete' => $nombrePaquete, 'Idsucursal' => $idSucursal, 'IdUsuarioModificacion' => $idUsuario, 'Total' => $totalPaquete, 'Costo' => $costoPaquete, 'FechaModificacion' => $fecha];
//                 DB::table('paquetes_promocionales')->where('IdPaquetePromocional', $idPaquete)
//                     ->update($array);

//                 $arrayItemsNoEliminados = [];
//                 for ($i = 0; $i < count($req->idsItems);
//                     $i++) {
//                     array_push($arrayItemsNoEliminados, $req->idsItems[$i]);
//                     $arrayRelacion = [
//                         'IdPaquetePromocional' => $idPaquete,
//                         'IdArticulo' => $req->idsItems[$i],
//                         'CodigoArticulo' => $req->Codigo[$i],
//                         'Cantidad' => $req->cantidad[$i],
//                     ];

//                     $verificarExistencia = $this->verificarItemsPaquete($req->idsItems[$i], $idPaquete);
//                     if ($verificarExistencia) {
//                         DB::table('articulo_paquetePromocional')
//                             ->where('IdArticulo', $req->idsItems[$i])
//                             ->where('IdPaquetePromocional', $idPaquete)
//                             ->update($arrayRelacion);
//                         usleep(200000);
//                     } else {
//                         DB::table('articulo_paquetePromocional')
//                             ->insert($arrayRelacion);
//                         usleep(200000);
//                     }
//                 }

//                 DB::table('articulo_paquetePromocional')
//                     ->where('IdPaquetePromocional', $idPaquete)
//                     ->whereNotIn('IdArticulo', $arrayItemsNoEliminados)
//                     ->delete();

//                 DB::commit();

//             } catch (\Throwable$th) {
//                 DB::rollBack();
//                 return Response(['error', 'error']);

//             }
//             return Response(['succes', 'Se Actualizo Correctamente el Paquete Miscelaneo', $idPaquete]);
//         }
//     }

// $dato = [1, 2, 3];
// if (count($dato) > 1) {
//     $array = ['Nombre' => 'facturador'];
//     DB::table('prueba_transaccion')->insert($array);
// }
// if (count($dat) > 1) {
//     $array = ['Nombre' => 'Detalle'];
//     DB::table('prueba_transaccion')->where('IdTransaccion', 1)->update($array);
// }

// public function actualizar(Request $req)
//     {
//         if ($req->ajax()) {
//             DB::beginTransaction();
//             try {

//                 $arrayRelacion = ['IdCliente' => 'PERU'];
//                 DB::table('cotizacion')->where('IdCotizacion', 1571)->delete();

//                 $arrayRelacion = ['IdCliente' => 'PERU'];
//                 DB::table('paquetes_promocionales')->where('IdPaquetePromocional', 10)->delete();

//                 $arrayRelacion = ['IdCliente' => 'PERU'];
//                 DB::table('cotizacion')->insert($arrayRelacion);

//                 $array = ['NombrePaquete' => 'PERU-12'];
//                 DB::table('paquetes_promocionales')->insert($array);

//                 DB::commit();

//             } catch (\Throwable$th) {
//                 DB::rollBack();
//                 return Response(['error', 'error']);

//             }
//             return Response(['succes', 'Se Actualizo Correctamente el Paquete Miscelaneo']);
//         }
//     }
