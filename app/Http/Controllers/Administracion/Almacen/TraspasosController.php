<?php

namespace App\Http\Controllers\Administracion\Almacen;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DB;
use Illuminate\Http\Request;
use Session;

class TraspasosController extends Controller
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
        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'almacenes' => $almacenes, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('administracion/almacen/traspasos/almacenes', $array);
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
        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'sucursales' => $sucursales, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('administracion/almacen/traspasos/crearAlmacen', $array);
    }

    public function store(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $this->validateAlmacen($req);
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $nombre = $req->nombre;
            $sucursal = $req->idSucursal;
            $telefono = $req->telefono;
            $direccion = $req->direccion;
            $codigoCliente = $usuarioSelect->CodigoCliente;
            $estado = 'E';
            $array = ['IdSucursal' => $sucursal, 'Nombre' => $nombre, 'Direccion' => $direccion, 'Telefono' => $telefono, 'Codigocliente' => $codigoCliente, 'Estado' => $estado];
            DB::table('almacen')->insert($array);

            return redirect('administracion/almacen/traspasos')->with('status', 'Se creo almac�n correctamente');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
        }
    }

    public function edit(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $almacen = $loadDatos->getAlmacenSelect($id);
        $sucursales = $loadDatos->getSucursales($usuarioSelect->CodigoCliente, $usuarioSelect->IdOperador);
        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'almacen' => $almacen, 'sucursales' => $sucursales, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('administracion/almacen/traspasos/editarAlmacen', $array);
    }

    public function update(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $this->validateAlmacen($req);
            $nombre = $req->nombre;
            $sucursal = $req->idSucursal;
            $telefono = $req->telefono;
            $direccion = $req->direccion;
            $array = ['IdSucursal' => $sucursal, 'Nombre' => $nombre, 'Direccion' => $direccion, 'Telefono' => $telefono];

            DB::table('almacen')
                ->where('IdAlmacen', $id)
                ->update($array);

            return redirect('administracion/almacen/traspasos')->with('status', 'Se actualizo almac�n correctamente');

        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
        }
    }

    public function delete($id)
    {
        try {
            $array = ['Estado' => 'D'];
            DB::table('almacen')
                ->where('IdAlmacen', $id)
                ->update($array);

            return redirect('administracion/almacen/traspasos')->with('status', 'Se elimino almac�n correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function realizarTraspaso(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
        }

        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');

        $productos = $loadDatos->getProductos($idSucursal);
        $tipo = 's';

        $array = $this->datosTraspasos($productos, $idUsuario, $idSucursal, $idSucursal, $tipo);

        return view('administracion/almacen/traspasos/realizarTraspaso', $array);
    }

    private function datosTraspasos($productos, $idUsuario, $idSucursal, $idAlmacen, $tipo)
    {
        $loadDatos = new DatosController();
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $almacenes = $loadDatos->getAlmacenes($usuarioSelect->CodigoCliente, $idSucursal);

        /*$sucPrincipal = DB::table('sucursal')
        ->select('IdSucursal')
        ->where('CodigoCliente',$usuarioSelect->CodigoCliente)
        ->where('Principal', 1)
        ->first();*/

        $sucursalSelect = $loadDatos->getSucursalSelect($idSucursal);
        $sucursales = $loadDatos->getSucursalesRestantes($idSucursal, $usuarioSelect->CodigoCliente);
        $array = ['permisos' => $permisos, 'sucursalSelect' => $sucursalSelect, 'sucursales' => $sucursales, 'modulosSelect' => $modulosSelect, 'almacenes' => $almacenes, 'productos' => $productos, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'idAlmacen' => $idAlmacen, 'tipo' => $tipo];
        return $array;
    }

    public function mostrarProductos(Request $req)
    {
        $idUsuario = Session::get('idUsuario');
        $idSucursal = Session::get('idSucursal');
        $origen = $req->almacenOrig;
        $tipo = substr($origen, 0, 1);
        $idAlmacen = substr($origen, 1);
        $loadDatos = new DatosController();
        if ($tipo == 's') {
            $datosProductos = $loadDatos->getProductos($idAlmacen);
        } else {
            $datosProductos = $loadDatos->getProductosAlmacen($idAlmacen);
        }
        $array = $this->datosTraspasos($datosProductos, $idUsuario, $idSucursal, $idAlmacen, $tipo);
        return view('administracion/almacen/traspasos/realizarTraspaso', $array);
    }

    public function buscarAlmacen(Request $req)
    {
        if ($req->ajax()) {
            $idAlmacen = $req->idAlmacen;
            $tipo = $req->tipo;
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            if ($tipo == 's') {
                $datosProductos = $loadDatos->getProductos($idSucursal);
            } else {
                $datosProductos = $loadDatos->getProductosAlmacen($idAlmacen);
            }

            return Response($datosProductos);
        }
    }

    public function traspasoProducto(Request $req)
    {
        if ($req->ajax()) {
            $idAlmacen = $req->idAlmacen; //sucursal o almacen de destino
            $tipo = $req->tipo;
            $codInterno = $req->codInterno;
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            if ($tipo == "s") {
                $productosEncontrados = DB::table('articulo as a')
                    ->join('marca as m', 'a.IdMarca', '=', 'm.IdMarca')
                    ->select('a.*', 'm.Nombre as Marca')
                    ->where('a.IdSucursal', $idAlmacen)
                    ->where('a.CodigoInterno', $codInterno)
                    ->where('a.IdTipo', 1)
                    ->where('a.Estado', 'E')
                    ->orderBy('a.IdArticulo', 'desc')
                    ->get();
            } else {
                $productosEncontrados = DB::table('almacen_producto')
                    ->join('marca', 'almacen_producto.IdMarca', '=', 'marca.IdMarca')
                    ->select('almacen_producto.IdAlmacenProducto as IdArticulo', 'almacen_producto.Descripcion', 'almacen_producto.Codigo', 'almacen_producto.Stock', 'marca.Nombre as Marca', 'almacen_producto.CodigoInterno', 'almacen_producto.IdTipoMoneda')
                    ->where('almacen_producto.IdAlmacen', $idAlmacen)
                    ->where('almacen_producto.CodigoInterno', $codInterno)
                    ->orderBy('IdAlmacenProducto', 'desc')
                    ->get();
            }
            $array = [];
            foreach ($productosEncontrados as $producto) {
                array_push($array, $producto);

            }

            return Response($array);
        }
    }

    public function guardarTraspaso(Request $req)
    {
        if ($req->ajax()) {
            try {
                DB::beginTransaction();
                //$corre=0;
                $loadDatos = new DatosController();
                $tipoOrigen = $req->tipoOrigen;
                $tipoDestino = $req->tipoDestino;
                $idOrigen = $req->idAlmacenOrigen;
                $idDestino = $req->idAlmacenDestino;
                $idUsuario = Session::get('idUsuario');
                $idSucursal = Session::get('idSucursal');
                $arrayProductosCreados = [];
                /***************************buscar nombre almacen/sucursal**********************/

                if ($tipoOrigen == 's') {
                    $nomOrigen = DB::table('sucursal')
                        ->select('Nombre')
                        ->where('IdSucursal', $idOrigen)
                        ->first();
                    $nombreOrigen = $nomOrigen->Nombre;
                } else {
                    $nomOrigen = DB::table('almacen')
                        ->select('Nombre')
                        ->where('IdAlmacen', $idOrigen)
                        ->first();
                    $nombreOrigen = $nomOrigen->Nombre;
                }

                if ($tipoDestino == 's') {
                    $nomDestino = DB::table('sucursal')
                        ->select('Nombre')
                        ->where('IdSucursal', $idDestino)
                        ->first();
                    $nombreDestino = $nomDestino->Nombre;
                } else {
                    $nomDestino = DB::table('almacen')
                        ->select('Nombre')
                        ->where('IdAlmacen', $idDestino)
                        ->first();
                    $nombreDestino = $nomDestino->Nombre;
                }

                /*******************************************************************************/

                /****para  traer el principal sucursal *********/
                $cod_cliente = DB::table('sucursal')
                    ->select('CodigoCliente')
                    ->where('IdSucursal', $idSucursal)
                    ->first();

                /*$sucPrincipal = DB::table('sucursal')
                ->select('IdSucursal')
                ->where('CodigoCliente',$cod_cliente->CodigoCliente)
                ->where('Principal', 1)
                ->first();*/

                /***********************************end traer principal***********************************/
                //$bandPrincipal=0;

                if ($tipoOrigen == "s") {
                    for ($i = 0; $i < count($req->idArticulo); $i++) {
                        $productoOrigen = $loadDatos->getProductoSelect($req->idArticulo[$i]);
                        DB::table('articulo')
                            ->where('IdArticulo', $req->idArticulo[$i])
                            ->decrement('Stock', $req->cantTrasp[$i]);

                        $this->actualizarStock($req->idArticulo[$i], $req->cantTrasp[$i], 2);

                        $kardex = array(
                            'CodigoInterno' => $productoOrigen->CodigoInterno,
                            'fecha_movimiento' => date("Y-m-d H:i:s"),
                            'tipo_movimiento' => 5, //5  salida para  kardex
                            'usuario_movimiento' => $idUsuario,
                            'documento_movimiento' => 'Traspaso',
                            'existencia' => $productoOrigen->Stock - (float) $req->cantTrasp[$i],
                            'costo' => $productoOrigen->Precio,
                            'IdArticulo' => $req->idArticulo[$i],
                            'IdSucursal' => $idOrigen,
                            'Cantidad' => $req->cantTrasp[$i],
                            'Descuento' => 0,
                            'ImporteEntrada' => 0,
                            'ImporteSalida' => 0,
                            'estado' => 1,
                        );
                        DB::table('kardex')->insert($kardex);

                        if ($tipoDestino == 's') {

                            $productoDestino = $loadDatos->getProductoCodigoInterno($productoOrigen->CodigoInterno, $idDestino);
                            if (count($productoDestino) > 0) {
                                DB::table('articulo')
                                    ->where('IdArticulo', $productoDestino[0]->IdArticulo)
                                    ->increment('Stock', $req->cantTrasp[$i]);

                                $this->actualizarStock($productoDestino[0]->IdArticulo, $req->cantTrasp[$i], 1);

                                $kardex = array(
                                    'CodigoInterno' => $productoDestino[0]->CodigoInterno,
                                    'fecha_movimiento' => date("Y-m-d H:i:s"),
                                    'tipo_movimiento' => 3, //3  entrada para  kardex
                                    'usuario_movimiento' => $idUsuario,
                                    'documento_movimiento' => 'Traspaso',
                                    'existencia' => $productoDestino[0]->Stock + (float) $req->cantTrasp[$i],
                                    'costo' => $productoDestino[0]->Precio,
                                    'IdArticulo' => $productoDestino[0]->IdArticulo,
                                    'IdSucursal' => $idDestino,
                                    'Cantidad' => $req->cantTrasp[$i],
                                    'Descuento' => 0,
                                    'ImporteEntrada' => 0,
                                    'ImporteSalida' => 0,
                                    'estado' => 1,
                                );
                                DB::table('kardex')->insert($kardex);
                            } else {
                                $fecha = $loadDatos->getDateTime();
                                $imagen = 'https://easyfactperu2021.s3.us-west-2.amazonaws.com/1641830772.png';
                                $array = ['IdMarca' => $productoOrigen->IdMarca,
                                    'IdCategoria' => $productoOrigen->IdCategoria,
                                    'IdTipo' => 1,
                                    'IdUnidadMedida' => $productoOrigen->IdUnidadMedida,
                                    'IdSucursal' => $idDestino,
                                    'IdTipoMoneda' => $productoOrigen->IdTipoMoneda,
                                    'FechaCreacion' => $fecha,
                                    'IdCreacion' => $idUsuario,
                                    'Descripcion' => $productoOrigen->Descripcion,
                                    'Stock' => $req->cantTrasp[$i],
                                    'Precio' => $productoOrigen->Precio,
                                    'Exonerado' => $productoOrigen->Exonerado,
                                    'Costo' => $productoOrigen->Costo,
                                    'TipoOperacion' => 1,
                                    'Imagen' => $imagen,
                                    'Codigo' => $productoOrigen->Codigo,
                                    'CodigoInterno' => $productoOrigen->CodigoInterno,
                                    'IdTipoUnidad' => $productoOrigen->IdTipoUnidad,
                                    'NombreTipo' => $productoOrigen->NombreTipo,
                                    'CantidadTipo' => $productoOrigen->CantidadTipo,
                                    'Estado' => 'E',
                                ];
                                DB::table('articulo')->insert($array);

                                $iden = DB::table('articulo')
                                    ->select('IdArticulo')
                                    ->orderBy('IdArticulo', 'desc')
                                    ->first();

                                $_array = ['IdArticulo' => $iden->IdArticulo, 'Costo' => $productoOrigen->Costo, 'Precio' => $productoOrigen->Precio, 'Cantidad' => $req->cantTrasp[$i]];
                                DB::table('stock')->insert($_array);

                                $kardex = array(
                                    'CodigoInterno' => $productoOrigen->CodigoInterno,
                                    'fecha_movimiento' => date("Y-m-d H:i:s"),
                                    'tipo_movimiento' => 11, //11  creacion de entrada para  kardex
                                    'usuario_movimiento' => $idUsuario,
                                    'documento_movimiento' => 'Traspaso',
                                    'existencia' => (float) $req->cantTrasp[$i],
                                    'costo' => $productoOrigen->Precio,
                                    'IdArticulo' => $iden->IdArticulo,
                                    'IdSucursal' => $idDestino,
                                    'Cantidad' => $req->cantTrasp[$i],
                                    'Descuento' => 0,
                                    'ImporteEntrada' => 0,
                                    'ImporteSalida' => 0,
                                    'estado' => 1,
                                );
                                DB::table('kardex')->insert($kardex);
                                $arrayProductosCreados[$i] = $productoOrigen->Descripcion;
                            }
                            $this->guardarHistorial($productoOrigen->CodigoInterno, $idSucursal, $idOrigen, $nombreOrigen, 1, $idDestino, $nombreDestino, 1, $req->cantTrasp[$i]);
                        } else {
                            $productoDestino = $loadDatos->getBuscarProductoAlmacenNew($productoOrigen->CodigoInterno, $idDestino);
                            if (count($productoDestino) > 0) {
                                DB::table('almacen_producto')
                                    ->where('IdAlmacen', $idDestino)
                                    ->where('CodigoInterno', $productoOrigen->CodigoInterno)
                                    ->increment('Stock', $req->cantTrasp[$i]);

                                $kardex = array(
                                    'CodigoInterno' => $productoDestino[0]->CodigoInterno,
                                    'fecha_movimiento' => date("Y-m-d H:i:s"),
                                    'tipo_movimiento' => 3, //3  entrada para  kardex
                                    'usuario_movimiento' => $idUsuario,
                                    'documento_movimiento' => 'Traspaso',
                                    'existencia' => $productoDestino[0]->Stock + (float) $req->cantTrasp[$i],
                                    'costo' => $productoOrigen->Precio,
                                    'IdArticulo' => $productoDestino[0]->IdArticulo,
                                    'IdSucursal' => $idDestino,
                                    'Cantidad' => $req->cantTrasp[$i],
                                    'Descuento' => 0,
                                    'ImporteEntrada' => 0,
                                    'ImporteSalida' => 0,
                                    'estado' => 2,
                                );
                                DB::table('kardex')->insert($kardex);

                            } else {
                                $array = ['CodigoCliente' => $cod_cliente->CodigoCliente, 'IdSucursal' => $idOrigen, 'IdArticulo' => $req->idArticulo[$i], 'IdAlmacen' => $idDestino, 'Descripcion' => $productoOrigen->Descripcion, 'IdMarca' => $productoOrigen->IdMarca, 'IdCategoria' => $productoOrigen->IdCategoria, 'IdTipoMoneda' => $productoOrigen->IdTipoMoneda, 'Codigo' => $productoOrigen->Codigo, 'CodigoInterno' => $productoOrigen->CodigoInterno, 'Stock' => $req->cantTrasp[$i]];
                                DB::table('almacen_producto')->insert($array);

                                /*$iden = DB::table('almacen')
                                ->orderBy('IdAlmacen','desc')
                                ->first();*/

                                $kardex = array(
                                    'CodigoInterno' => $productoOrigen->CodigoInterno,
                                    'fecha_movimiento' => date("Y-m-d H:i:s"),
                                    'tipo_movimiento' => 3, //3  entrada para  kardex
                                    'usuario_movimiento' => $idUsuario,
                                    'documento_movimiento' => 'Traspaso',
                                    'existencia' => (float) $req->cantTrasp[$i],
                                    'costo' => $productoOrigen->Precio,
                                    'IdArticulo' => $req->idArticulo[$i],
                                    'IdSucursal' => $idDestino,
                                    'Cantidad' => $req->cantTrasp[$i],
                                    'Descuento' => 0,
                                    'ImporteEntrada' => 0,
                                    'ImporteSalida' => 0,
                                    'estado' => 2,
                                );
                                DB::table('kardex')->insert($kardex);

                                $arrayProductosCreados[$i] = $productoOrigen->Descripcion;
                            }
                            $this->guardarHistorial($productoOrigen->CodigoInterno, $idSucursal, $idOrigen, $nombreOrigen, 1, $idDestino, $nombreDestino, 2, $req->cantTrasp[$i]);
                        }
                        usleep(20000);
                    }
                } else {
                    for ($i = 0; $i < count($req->idArticulo); $i++) {
                        $productoAlmacen = DB::table('almacen_producto')
                            ->where('IdAlmacen', $idOrigen)
                            ->where('IdAlmacenProducto', $req->idArticulo[$i])
                            ->first();

                        $productoOrigen = $loadDatos->getProductoSelect($productoAlmacen->IdArticulo);

                        DB::table('almacen_producto')
                            ->where('IdAlmacen', $idOrigen)
                            ->where('IdAlmacenProducto', $req->idArticulo[$i])
                            ->decrement('Stock', $req->cantTrasp[$i]);

                        $kardex = array(
                            'CodigoInterno' => $productoAlmacen->CodigoInterno,
                            'fecha_movimiento' => date("Y-m-d H:i:s"),
                            'tipo_movimiento' => 5, //5  salida para  kardex
                            'usuario_movimiento' => $idUsuario,
                            'documento_movimiento' => 'Traspaso',
                            'existencia' => $productoAlmacen->Stock - (float) $req->cantTrasp[$i],
                            'costo' => $productoOrigen->Precio,
                            'IdArticulo' => $productoOrigen->IdArticulo,
                            'IdSucursal' => $idOrigen,
                            'Cantidad' => $req->cantTrasp[$i],
                            'Descuento' => 0,
                            'ImporteEntrada' => 0,
                            'ImporteSalida' => 0,
                            'estado' => 2,
                        );
                        DB::table('kardex')->insert($kardex);

                        if ($tipoDestino == 's') {
                            $productoDestino = $loadDatos->getProductoCodigoInterno($productoOrigen->CodigoInterno, $idDestino);
                            if (count($productoDestino) > 0) {
                                DB::table('articulo')
                                    ->where('IdArticulo', $productoDestino[0]->IdArticulo)
                                    ->increment('Stock', $req->cantTrasp[$i]);

                                $this->actualizarStock($productoDestino[0]->IdArticulo, $req->cantTrasp[$i], 1);

                                $kardex = array(
                                    'CodigoInterno' => $productoDestino[0]->CodigoInterno,
                                    'fecha_movimiento' => date("Y-m-d H:i:s"),
                                    'tipo_movimiento' => 3, //3  entrada para  kardex
                                    'usuario_movimiento' => $idUsuario,
                                    'documento_movimiento' => 'Traspaso',
                                    'existencia' => $productoDestino[0]->Stock + (float) $req->cantTrasp[$i],
                                    'costo' => $productoDestino[0]->Precio,
                                    'IdArticulo' => $productoDestino[0]->IdArticulo,
                                    'IdSucursal' => $idDestino,
                                    'Cantidad' => $req->cantTrasp[$i],
                                    'Descuento' => 0,
                                    'ImporteEntrada' => 0,
                                    'ImporteSalida' => 0,
                                    'estado' => 1,
                                );
                                DB::table('kardex')->insert($kardex);
                            } else {
                                $fecha = $loadDatos->getDateTime();
                                $imagen = 'https://easyfactperu2021.s3.us-west-2.amazonaws.com/1641830772.png';
                                $array = ['IdMarca' => $productoOrigen->IdMarca,
                                    'IdCategoria' => $productoOrigen->IdCategoria,
                                    'IdTipo' => 1,
                                    'IdUnidadMedida' => $productoOrigen->IdUnidadMedida,
                                    'IdSucursal' => $idDestino,
                                    'IdTipoMoneda' => $productoOrigen->IdTipoMoneda,
                                    'FechaCreacion' => $fecha,
                                    'IdCreacion' => $idUsuario,
                                    'Descripcion' => $productoOrigen->Descripcion,
                                    'Stock' => $req->cantTrasp[$i],
                                    'Precio' => $productoOrigen->Precio,
                                    'Exonerado' => $productoOrigen->Exonerado,
                                    'Costo' => $productoOrigen->Costo,
                                    'TipoOperacion' => 1,
                                    'Imagen' => $imagen,
                                    'Codigo' => $productoOrigen->Codigo,
                                    'CodigoInterno' => $productoOrigen->CodigoInterno,
                                    'IdTipoUnidad' => $productoOrigen->IdTipoUnidad,
                                    'NombreTipo' => $productoOrigen->NombreTipo,
                                    'CantidadTipo' => $productoOrigen->CantidadTipo,
                                    'Estado' => 'E',
                                ];
                                DB::table('articulo')->insert($array);

                                $iden = DB::table('articulo')
                                    ->select('IdArticulo')
                                    ->orderBy('IdArticulo', 'desc')
                                    ->first();

                                $_array = ['IdArticulo' => $iden->IdArticulo, 'Costo' => $productoOrigen->Costo, 'Precio' => $productoOrigen->Precio, 'Cantidad' => $req->cantTrasp[$i]];
                                DB::table('stock')->insert($_array);

                                $kardex = array(
                                    'CodigoInterno' => $productoOrigen->CodigoInterno,
                                    'fecha_movimiento' => date("Y-m-d H:i:s"),
                                    'tipo_movimiento' => 11, //3  creacion de entrada para  kardex
                                    'usuario_movimiento' => $idUsuario,
                                    'documento_movimiento' => 'Traspaso',
                                    'existencia' => (float) $req->cantTrasp[$i],
                                    'costo' => $productoOrigen->Precio,
                                    'IdArticulo' => $iden->IdArticulo,
                                    'IdSucursal' => $idDestino,
                                    'Cantidad' => $req->cantTrasp[$i],
                                    'Descuento' => 0,
                                    'ImporteEntrada' => 0,
                                    'ImporteSalida' => 0,
                                    'estado' => 1,
                                );
                                DB::table('kardex')->insert($kardex);

                                $arrayProductosCreados[$i] = $productoOrigen->Descripcion;

                            }
                            $this->guardarHistorial($productoOrigen->CodigoInterno, $idSucursal, $idOrigen, $nombreOrigen, 2, $idDestino, $nombreDestino, 1, $req->cantTrasp[$i]);
                        } else {
                            $productoDestino = $loadDatos->getBuscarProductoAlmacenNew($productoOrigen->CodigoInterno, $idDestino);
                            if (count($productoDestino) > 0) {
                                DB::table('almacen_producto')
                                    ->where('IdAlmacen', $idDestino)
                                    ->where('CodigoInterno', $productoOrigen->CodigoInterno)
                                    ->increment('Stock', $req->cantTrasp[$i]);

                                $kardex = array(
                                    'CodigoInterno' => $productoDestino[0]->CodigoInterno,
                                    'fecha_movimiento' => date("Y-m-d H:i:s"),
                                    'tipo_movimiento' => 3, //3  entrada para  kardex
                                    'usuario_movimiento' => $idUsuario,
                                    'documento_movimiento' => 'Traspaso',
                                    'existencia' => $productoDestino[0]->Stock + (float) $req->cantTrasp[$i],
                                    'costo' => $productoOrigen->Precio,
                                    'IdArticulo' => $productoOrigen->IdArticulo,
                                    'IdSucursal' => $idDestino,
                                    'Cantidad' => $req->cantTrasp[$i],
                                    'Descuento' => 0,
                                    'ImporteEntrada' => 0,
                                    'ImporteSalida' => 0,
                                    'estado' => 2,
                                );
                                DB::table('kardex')->insert($kardex);

                            } else {
                                $array = ['CodigoCliente' => $cod_cliente->CodigoCliente, 'IdSucursal' => $productoOrigen->IdSucursal, 'IdArticulo' => $productoOrigen->IdArticulo, 'IdAlmacen' => $idDestino, 'Descripcion' => $productoOrigen->Descripcion, 'IdMarca' => $productoOrigen->IdMarca, 'IdCategoria' => $productoOrigen->IdCategoria, 'IdTipoMoneda' => $productoOrigen->IdTipoMoneda, 'Codigo' => $productoOrigen->Codigo, 'CodigoInterno' => $productoOrigen->CodigoInterno, 'Stock' => $req->cantTrasp[$i]];
                                DB::table('almacen_producto')->insert($array);

                                $kardex = array(
                                    'CodigoInterno' => $productoOrigen->CodigoInterno,
                                    'fecha_movimiento' => date("Y-m-d H:i:s"),
                                    'tipo_movimiento' => 3, //3  entrada para  kardex
                                    'usuario_movimiento' => $idUsuario,
                                    'documento_movimiento' => 'Traspaso',
                                    'existencia' => (float) $req->cantTrasp[$i],
                                    'costo' => $productoOrigen->Precio,
                                    'IdArticulo' => $productoOrigen->IdArticulo,
                                    'IdSucursal' => $idDestino,
                                    'Cantidad' => $req->cantTrasp[$i],
                                    'Descuento' => 0,
                                    'ImporteEntrada' => 0,
                                    'ImporteSalida' => 0,
                                    'estado' => 2,
                                );
                                DB::table('kardex')->insert($kardex);

                                $arrayProductosCreados[$i] = $productoOrigen->Descripcion;
                            }
                            $this->guardarHistorial($productoOrigen->CodigoInterno, $idSucursal, $idOrigen, $nombreOrigen, 2, $idDestino, $nombreDestino, 2, $req->cantTrasp[$i]);
                        }
                        usleep(20000);
                    }
                }

                Session::put('arrayProductos', $arrayProductosCreados);

                DB::commit();
                return Response("Se guardaron datos correctamente");
            } catch (\Exception $e) {
                DB::rollback();
                return Response(['respuesta' => 'error', 'mensaje' => 'Ocurrio un error en la transacion, por favor comunicarse con el área de soporte']);
            }

        }
    }

    private function guardarHistorial($codInterno, $idSucursal, $origen, $nombreOrigen, $tipoOri, $destino, $nombreDestino, $tipoDes, $cantidad)
    {
        $idUsuario = Session::get('idUsuario');
        $historico = array(
            'IdUsuario' => $idUsuario,
            'fechaTraspaso' => date("Y-m-d H:i:s"),
            'Descripcion' => 'Traspaso',
            'CodigoInterno' => $codInterno,
            'IdSucursal' => $idSucursal,
            'Origen' => $origen,
            'NombreOrigen' => $nombreOrigen,
            'TipoOrigen' => $tipoOri,
            'Destino' => $destino,
            'NombreDestino' => $nombreDestino,
            'TipoDestino' => $tipoDes,
            'Cantidad' => $cantidad,
            'Tipo' => 1,
        );

        DB::table('historico_traspaso')->insert($historico);
    }

    private function actualizarStock($idArticulo, $stock, $estado)
    {
        if ($estado == 1) {
            $loadDatos = new DatosController();
            $productoSelect = DB::table('stock')
                ->where('IdArticulo', $idArticulo)
                ->orderBy('IdStock', 'desc')
                ->get();
            if (count($productoSelect) > 1) {
                DB::table('stock')
                    ->where('IdStock', $productoSelect[1]->IdStock)
                    ->increment('Cantidad', $stock);
            } else {
                DB::table('stock')
                    ->where('IdStock', $productoSelect[0]->IdStock)
                    ->increment('Cantidad', $stock);
            }
        } else {
            $loadDatos = new DatosController();

            $productoSelect = $loadDatos->getProductoStockSelect($idArticulo);
            if ($stock > $productoSelect[0]->Cantidad) {
                DB::table('stock')
                    ->where('IdStock', $productoSelect[0]->IdStock)
                    ->decrement('Cantidad', 0);

                $resto = (float) $stock - (float) $productoSelect[0]->Cantidad;

                DB::table('stock')
                    ->where('IdStock', $productoSelect[1]->IdStock)
                    ->decrement('Cantidad', $resto);
            } else {
                DB::table('stock')
                    ->where('IdStock', $productoSelect[0]->IdStock)
                    ->decrement('Cantidad', $stock);
            }
        }
    }

    protected function validateAlmacen(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required',
        ]);
    }

    public function llenar()
    {
/*         $all = DB::table('articulo')
//->where('IdArticulo','>',16352)
->select('CodigoInterno','IdSucursal')
->whereNull('CodigoInterno')
->get();
 */

        $loadDatos = new DatosController();
        $productoSelect = $loadDatos->getProductoStockSelect(22105);

        if (count($productoSelect) >= 1) {
            if (5 > $productoSelect[0]->Cantidad) {
                echo "Hola";
            } else {
                echo "negativo";
            }
        } else {
            echo "negativo afuera";
        }

        /* $all  =  DB::table('kardex')
        ->join('articulo', 'articulo.IdArticulo', '=', 'kardex.IdArticulo')
        ->select('articulo.CodigoInterno','articulo.IdArticulo')
        ->whereNull('kardex.CodigoInterno')
        ->groupBy('kardex.IdArticulo')
        ->get();

        foreach($all as $joder)
        { */
        /* DB::table('kardex')
        ->where('IdArticulo', $joder->IdArticulo)
        ->whereNull('CodigoInterno')
        ->update(['CodigoInterno' => $joder->CodigoInterno]); */
        /*
        }
         */

        /* $all=DB::select('SELECT IdArticulo FROM `kardex` WHERE `CodigoInterno` is null');

        foreach($all as $joder)
        {
        echo  $joder->IdArticulo.'<br>';
        } */

        //dd($all);
        /* $principales=DB::table('sucursal')
    ->where('Principal',1)
    ->get();

    foreach($all as $joder)
    {
    $registro = DB::table('articulo')
    ->select('CodigoInterno','IdSucursal')
    ->where('IdArticulo', $joder->IdArticulo)
    ->first();

    //$codigo_interno = date("YmdHis");
    $codigo_interno = round(microtime(true) * 1000);

    foreach($principales as $prin)
    {
    if($prin->IdSucursal == $registro->IdSucursal)
    {
    DB::table('articulo')
    ->where('IdArticulo', $joder->IdArticulo)
    ->whereNull('CodigoInterno')
    ->update(['CodigoInterno' => $codigo_interno]);
    }
    }
    usleep(55555);
    }          */
    }

    public function allcli()
    {
        $all = DB::table('cliente')
            ->get();
        foreach ($all as $joder) {
            DB::table('cliente')
                ->where('IdCliente', $joder->IdCliente)
                ->update(['RazonSocial' => $joder->Nombre]);
        }
    }
}
