<?php

namespace App\Http\Controllers\Vehicular\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DateTime;
use DB;
use Illuminate\Http\Request;
use Session;

class GruposController extends Controller
{

    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        // try {
        //     DB::transaction(function () {
        //         DB::table('notificacion_mantenimiento')->insert(['IdVehiculo' => 1]);

        //         DB::table('grupos_productos_servicios')->insert(['NombreGru' => "MouseLenovo"]);

        //     });
        // } catch (\Throwable$th) {
        //     dd('Error');
        // }
        if ($req->ajax()) {
            try {
                DB::beginTransaction();

                DB::table('notificacion_mantenimiento')->insert(['IdVehiculo' => 1]);
                // DB::table('notificacion_mantenimiento')->where('IdNotificacionMantenimiento', 2)->delete();

                DB::table('grupos_productos_servicios')->insert(['NombreGrupooo' => "MouseLenovo"]);
                // DB::afterCommit(function () {
                //     DB::table('notificacion_mantenimiento')->insert(['IdVehiculo' => 1]);

                //     // DB::table('grupos_productos_servicios')->insert(['NombreGrupo' => "MouseLenovo"]);
                // });
                return view('vehicular/grupos/grupos');

                DB::commit();
                dd("correcto");

            } catch (\Throwable$th) {
                DB::rollBack();
                $idMaximo = DB::table('notificacion_mantenimiento')->SELECT(DB::RAW("MAX(IdNotificacionMantenimiento) AS IdMaximo"))->first();
                $idMaximo = $idMaximo->IdMaximo + 1;
                DB::statement("ALTER TABLE notificacion_mantenimiento AUTO_INCREMENT=" . $idMaximo);
                dd('Error');
            }
        }

        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $almacenes = $loadDatos->getAlmacenes($usuarioSelect->CodigoCliente, $idSucursal);

        $reporteGrupos = DB::select('call sp_getGruposProductoServicio(?)', array($idSucursal));
        $reporteGrupos = collect($reporteGrupos);
        $grupoDolares = $reporteGrupos->where('IdTipoMoneda', 2);
        $grupoSoles = $reporteGrupos->where('IdTipoMoneda', 1);

        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'almacenes' => $almacenes, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'grupoDolares' => $grupoDolares, 'grupoSoles' => $grupoSoles];

        return view('vehicular/grupos/grupos', $array);
    }

    public function create(Request $req)
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
        return view('vehicular/grupos/crearGrupo', $array);

    }

    public function editarGrupo(Request $req, $id)
    {

        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
        }

        $idSucursal = Session::get('idSucursal');
        // $cotizacion = grupos_productos_servicios::where('IdGrupo', $id)->first();
        // $cotizacion = grupos_productos_servicios::where(auth()->id())->get();
        // dd( $cotizacion);
        // $this->authorize('update', $idSucursal, 112);
        // dd($post);
        // $id = Auth::id();
        $a = $this->getGrupo($id);
        if ($a->IdSucursal == $idSucursal) {

            $loadDatos = new DatosController();
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

            $itemsGrupo = $this->itemsDetalleGrupo($id);
            for ($i = 0; $i < count($itemsGrupo);
                $i++) {
                if ($itemsGrupo[$i]->IdMarca != null) {
                    $marca = DB::table('marca')
                        ->where('IdMarca', $itemsGrupo[$i]->IdMarca)
                        ->first();
                    $itemsGrupo[$i]->nombreMarca = $marca->Nombre;
                } else {
                    $itemsGrupo[$i]->nombreMarca = '-';
                }

                if ($itemsGrupo[$i]->idTipoArticulo == 2) {
                    $itemsGrupo[$i]->Stock = '-';
                }

                if ($itemsGrupo[$i]->codigoBarra == null) {
                    $itemsGrupo[$i]->codigoBarra = '';
                }

                if ($itemsGrupo[$i]->IdCategoria != null) {
                    $categoria = DB::table('categoria')
                        ->where('IdCategoria', $itemsGrupo[$i]->IdCategoria)
                        ->first();
                    $itemsGrupo[$i]->nombreCategoria = $categoria->Nombre;
                } else {
                    $itemsGrupo[$i]->nombreCategoria = '-';
                }
            }
            $itemsGrupo = collect($itemsGrupo);
            // dd($itemsGrupo);
            $nombreGrupo = $itemsGrupo->pluck('NombreGrupo')->first();
            $tipoMoneda = $itemsGrupo->pluck('IdTipoMoneda')->first();
            $idGrupo = $itemsGrupo->pluck('IdGrupo')->first();

            $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'sucursales' => $sucursales, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'productos' => $productos, 'productosDolares' => $productosDolares, 'servicios' => $servicios, 'serviciosDolares' => $serviciosDolares, 'categorias' => $categorias, 'itemsGrupo' => $itemsGrupo, 'nombreGrupo' => $nombreGrupo, 'tipoMoneda' => $tipoMoneda, 'idGrupo' => $idGrupo];
            return view('vehicular/grupos/editarGrupo', $array);
        } else {
            // dd("error");
            return redirect('vehicular/administracion/paquetes');
        }

    }

    public function store(Request $req)
    {
        if ($req->ajax()) {
            $req->fechaEmitida = date('Y-m-d');
            $fecha = $req->fechaEmitida;
            $date = DateTime::createFromFormat('Y-m-d', $fecha);

            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
            $nombreGrupo = ucwords($req->nombreGrupo);
            $tipoMoneda = $req->tipoMoneda;
            $fechaConvertida = $date->format('Y-m-d H:i:s');
            if ($nombreGrupo == null) {
                return Response(['alert1', 'Por favor, agrege el nombre del Grupo']);
            }

            if ($req->Id == null) {
                return Response(['alert2', 'Por favor, agrege productos o servicios']);
            }

            $respuesta = DB::table('grupos_productos_servicios')
                ->where('IdSucursal', $idSucursal)
                ->where('NombreGrupo', $nombreGrupo)
                ->exists();
            if ($respuesta) {
                return Response(['alert3', 'El nombre Ingresado Ya existe']);
            }

            $array = ['FechaCreacion' => $fechaConvertida, 'NombreGrupo' => $nombreGrupo, 'IdTipoMoneda' => $tipoMoneda, 'FechaModificacion' => $fechaConvertida, 'Idsucursal' => $idSucursal, 'IdUsuarioCreacion' => $idUsuario, 'Estado' => 'E'];
            DB::table('grupos_productos_servicios')->insert($array);

            $grupo = DB::table('grupos_productos_servicios')
                ->orderBy('IdGrupo', 'desc')
                ->first();

            $idGrupo = $grupo->IdGrupo;
            for ($i = 0; $i < count($req->Id);
                $i++) {
                $arrayRelacion = [
                    'IdGrupo' => $idGrupo,
                    'IdArticulo' => $req->Id[$i],
                    'Codigo' => $req->Codigo[$i],
                    // Nuevo codigo
                    'CantidadArticulo' => $req->cantidad[$i],
                    // fin
                ];
                DB::table('detalle_grupo')->insert($arrayRelacion);
                usleep(200000);
            }
            return Response(['succes', 'Se Creo Correctamente el Grupo', $idGrupo]);
        }
    }

    public function actualizar(Request $req)
    {
        if ($req->ajax()) {

            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
            $nombreGrupo = ucwords($req->nombreGrupo);
            // $tipoMoneda = $req->tipoMoneda;
            $idGrupo = $req->idGrupo;
            if ($nombreGrupo == null) {
                return Response(['alert1', 'Por favor, agrege el nombre del Grupo']);
            }

            if ($idGrupo == null) {
                return Response(['alert2', 'Por favor, agrege productos o servicios']);
            }

            $array = ['NombreGrupo' => $nombreGrupo, 'Idsucursal' => $idSucursal, 'IdUsuarioCreacion' => $idUsuario, 'Estado' => 'E'];
            DB::table('grupos_productos_servicios')->where('IdGrupo', $idGrupo)
                ->update($array);

            $arrayItemsNoEliminados = [];
            for ($i = 0; $i < count($req->Id);
                $i++) {
                array_push($arrayItemsNoEliminados, $req->Id[$i]);
                $arrayRelacion = [
                    'IdGrupo' => $idGrupo,
                    'IdArticulo' => $req->Id[$i],
                    'Codigo' => $req->Codigo[$i],
                    // Nuevo codigo
                    'CantidadArticulo' => $req->cantidad[$i],
                    // fin
                ];

                $verificarExistencia = $this->verificarItemsGrupo($req->Id[$i], $idGrupo);
                if ($verificarExistencia) {
                    DB::table('detalle_grupo')
                        ->where('IdArticulo', $req->Id[$i])
                        ->where('IdGrupo', $idGrupo)
                        ->update($arrayRelacion);
                    usleep(200000);
                } else {
                    DB::table('detalle_grupo')
                        ->insert($arrayRelacion);
                    usleep(200000);
                }
            }

            DB::table('detalle_grupo')
                ->where('IdGrupo', $idGrupo)
                ->whereNotIn('IdArticulo', $arrayItemsNoEliminados)
                ->delete();
            return Response(['succes', 'Se Actualizo Correctamente el Grupo', $idGrupo]);
        }
    }

    private function verificarItemsGrupo($id, $idGrupo)
    {
        $resultado = DB::table('detalle_grupo')
            ->where('IdArticulo', $id)
            ->where('IdGrupo', $idGrupo)
            ->first();
        return $resultado;
    }

    public function verDetalleGrupo(Request $req, $id)
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

        $reporteGrupos = DB::select('call sp_getGruposProductoServicio(?)', array($idSucursal));
        $reporteGrupos = collect($reporteGrupos);
        $nombreGrupo = $reporteGrupos->where('IdGrupo', $id)->pluck('NombreGrupo')->first();

        $itemsGrupo = $this->itemsDetalleGrupo($id);
        for ($i = 0; $i < count($itemsGrupo);
            $i++) {
            if ($itemsGrupo[$i]->IdMarca != null) {
                $marca = DB::table('marca')
                    ->where('IdMarca', $itemsGrupo[$i]->IdMarca)
                    ->first();
                $itemsGrupo[$i]->nombreMarca = $marca->Nombre;
            } else {
                $itemsGrupo[$i]->nombreMarca = '-';
            }

            if ($itemsGrupo[$i]->IdCategoria != null) {
                $categoria = DB::table('categoria')
                    ->where('IdCategoria', $itemsGrupo[$i]->IdCategoria)
                    ->first();
                $itemsGrupo[$i]->nombreCategoria = $categoria->Nombre;
            } else {
                $itemsGrupo[$i]->nombreCategoria = '-';
            }

            if ($itemsGrupo[$i]->idTipoArticulo == 2) {
                $itemsGrupo[$i]->Stock = '-';
            }
        }
        $itemsEliminado = $itemsGrupo->where("Estado", "D");
        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'almacenes' => $almacenes, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'itemsGrupo' => $itemsGrupo, 'nombreGrupo' => $nombreGrupo, 'itemsEliminado' => $itemsEliminado];
        return view('vehicular/grupos/detalleGrupo', $array);
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

    protected function itemsDetalleGrupo($idGrupo)
    {
        try {
            $listaGrupo = DB::table('detalle_grupo')
                ->join('grupos_productos_servicios', 'detalle_grupo.IdGrupo', '=', 'grupos_productos_servicios.IdGrupo')
                ->join('articulo', 'detalle_grupo.IdArticulo', '=', 'articulo.IdArticulo')
                ->select('detalle_grupo.Codigo', 'articulo.Descripcion', 'articulo.IdMarca', 'articulo.Precio', 'articulo.Stock', 'articulo.Codigo AS codigoBarra', 'grupos_productos_servicios.NombreGrupo', 'grupos_productos_servicios.IdTipoMoneda', 'detalle_grupo.IdGrupo', 'articulo.IdTipo AS idTipoArticulo', 'articulo.IdArticulo', 'articulo.IdCategoria', 'articulo.Estado', 'detalle_grupo.CantidadArticulo', 'articulo.IdUnidadMedida')
                ->where('detalle_grupo.IdGrupo', $idGrupo)
                ->orderBy('detalle_grupo.IdDetalleGrupo', 'asc')
                ->get();
            return $listaGrupo;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function eliminarGrupo(Request $req, $idGrupo)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $array = ['Estado' => 'D'];
        DB::table('grupos_productos_servicios')
            ->where('IdGrupo', $idGrupo)
            ->where('IdSucursal', $idSucursal)
            ->update($array);

        return redirect('vehicular/administracion/paquetes')->with('status', 'El grupo se eliminó Correctamente');
    }

    public function getGrupo($idGrupo)
    {
        try {
            $grupo = DB::table('grupos_productos_servicios')
                ->where('grupos_productos_servicios.IdGrupo', $idGrupo)
                ->first();
            return $grupo;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
