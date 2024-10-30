<?php

namespace App\Http\Controllers\Administracion\Almacen;

use App\Exports\ExcelReporteProductos;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use App\Imports\ExcelDatosImportacion;
use App\Traits\ArchivosS3Trait;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class ArticulosController extends Controller
{
    // use getFuncionesTrait;
    // use GestionarImagenesS3Trait;
    use ArchivosS3Trait;
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
        $texto = "";
        $cod_cliente = DB::table('sucursal')
            ->select('CodigoCliente')
            ->where('IdSucursal', $idSucursal)
            ->first();

        $sucPrincipal = DB::table('sucursal')
            ->select('IdSucursal')
            ->where('CodigoCliente', $cod_cliente->CodigoCliente)
            ->where('Principal', 1)
            ->first();

        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        if ($sucPrincipal->IdSucursal == $idSucursal) {
            $articulos = $loadDatos->getProductosPagination($idSucursal, $texto, 1, 0);
            $array = ['articulos' => $articulos, 'miStock' => 0, 'principal' => 1, 'textoBuscar' => '', 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'texto' => $texto, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
            return view('administracion/almacen/articulos/articulos', $array);
        } else {

            $articulos = DB::table('articulo')
                ->select('IdArticulo', 'CodigoInterno', 'Descripcion', 'Codigo', 'Costo', 'Precio', 'IdMarca', 'IdCategoria', 'IdTipo', 'IdUnidadMedida', 'Stock', 'IdTipoMoneda')
                ->where('IdSucursal', $sucPrincipal->IdSucursal)
                ->where('Estado', 'E')
                ->where('IdTipo', 1)
                ->orderBy('Descripcion', 'asc')
                ->get();

            foreach ($articulos as $arti) {
                $miarticulo = DB::table('articulo')
                    ->select('Codigo', 'Costo', 'Precio', 'Stock')
                    ->where('CodigoInterno', $arti->CodigoInterno)
                    ->where('IdSucursal', $idSucursal)
                    ->where('Estado', 'E')
                    ->first();

                if (!empty($miarticulo)) {
                    $arti->Estado = 1;
                    $arti->Codigo = $miarticulo->Codigo;
                    $arti->Costo = $miarticulo->Costo;
                    $arti->Precio = $miarticulo->Precio;
                    $arti->Stock = $miarticulo->Stock;

                } else {
                    $arti->Estado = 0;
                }

            }
            $array = ['articulos' => $articulos, 'textoBuscar' => '', 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'texto' => $texto, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
            return view('administracion/almacen/articulos/articulos_sucursal', $array);
        }
    }

    public function create(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $unidadMedidas = $loadDatos->getUnidadMedida();
        $undiadesPorMayor = $loadDatos->getUnidadesPorMayor(); // esto lo puse para la creacion
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $tipoMonedas = $loadDatos->getTipoMoneda();
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $categorias = $loadDatos->getCategorias($usuarioSelect->CodigoCliente);
        $marcas = $loadDatos->getMarcas($usuarioSelect->CodigoCliente);
        $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $exonerado = $datosEmpresa->Exonerado;
        $sucursal = $loadDatos->getSucursalSelect($idSucursal);
        $sucExonerado = $sucursal->Exonerado;
        $array = ['sucursal' => $sucursal, 'marcas' => $marcas, 'categorias' => $categorias, 'unidadMedidas' => $unidadMedidas, 'tipoMonedas' => $tipoMonedas, 'undiadesPorMayor' => $undiadesPorMayor, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'sucExonerado' => $sucExonerado, 'exonerado' => $exonerado];
        return view('administracion/almacen/articulos/crearArticulo', $array);
    }

    public function store(Request $req)
    {
        try {
            $this->validateArticulos($req);
            $loadDatos = new DatosController();
            $fecha = $loadDatos->getDateTime();
            $idUsuario = Session::get('idUsuario');
            $idMarca = $req->marca;
            $idCategoria = $req->categoria;
            $idTipo = 1;
            $idSucursal = Session::get('idSucursal');

            $codigo_interno = round(microtime(true) * 1000);

            $stock = $req->stock;
            $uniMedida = $req->uniMedida;
            $exonerado = 1;
            $tipoMoneda = $req->tipoMoneda;
            $tipoOp = $req->tipoOperacion;
            if ($tipoMoneda == null) {
                $tipoMoneda = 1;
            }
            $precio = $req->precio;
            $costo = $req->costo;

            $descripcion = $req->descripcion;
            $ubicacion = $req->ubicacion;
            $codigo = $req->codBarra;
            $ventasMayor1 = $req->ventaMayor1 > 0 ? $req->ventaMayor1 : null;
            $ventasMayor2 = $req->ventaMayor2 > 0 ? $req->ventaMayor2 : null;
            $ventasMayor3 = $req->ventaMayor3 > 0 ? $req->ventaMayor3 : null;
            $descuento1 = null; //$req->descuento1;
            $descuento2 = null; //$req->descuento2;
            $descuento3 = null; //$req->descuento3;
            $tipoUnidad = $req->uniMedidaMayor;
            $valorTipoCambio = $req->valorCambio;
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);

            if (floatval($costo) >= floatval($precio)) {
                return back()->with('error', 'El precio tiene que ser mayor que el costo')->withInput();
            }

            /////////////////////////////////martin
            $nombreTipoBand = DB::table('unidad_medida')
                ->where('IdUnidadMedida', $tipoUnidad)
                ->select('Nombre')
                ->first();
            if ($nombreTipoBand) {
                $nombreTipo = $nombreTipoBand->Nombre;
            } else {
                $nombreTipo = $req->nombreTipo;
            } //////////////////////martin

            $precioDescuento1 = $req->precioDescuento1;
            $precioDescuento2 = $req->precioDescuento2;
            $precioDescuento3 = $req->precioDescuento3;

            $cantidadTipo = $req->cantidadTipo;
            $descuentoTipo = null; //$req->descuentoTipo;
            $precioTipo = $req->precioTipo;
            $estado = 'E';
            if ($req->imagen != null) {
                // Almacenar la imganen en el S3 y obtener la URL
                $rucEmpresa = $loadDatos->getRucEmpresa($idUsuario)->Ruc;
                $nombreImagen = "producto-{$codigo_interno}-" . date('His');
                $directorio = $this->generarUbicacionArchivo('ImagenesArticulos/', "$rucEmpresa/");
                $imagen = $this->storeImagenFormatoFileS3($req->imagen, $imagenAnterior = null, $nombreImagen, $directorio, $accion = 'store');
                // $imagen = $loadDatos->setImage($req->imagen);
            } else {
                $imagen = 'https://easyfactperu2021.s3.us-west-2.amazonaws.com/1641830772.png';
            }

            /*********************mi modificacion tabla  productos*****************************/

            $table_prod = ['Nombre' => $descripcion, 'id_creador' => $idSucursal, 'CodigoInterno' => $codigo_interno, 'Imagen' => $imagen];
            DB::table('productos')->insert($table_prod);

            /******************************************************************/

            $array = ['IdMarca' => $idMarca, 'IdCategoria' => $idCategoria, 'IdTipo' => $idTipo, 'IdUnidadMedida' => $uniMedida, 'IdSucursal' => $idSucursal, 'IdTipoMoneda' => $tipoMoneda, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario, 'Descripcion' => $descripcion, 'Ubicacion' => $ubicacion,
                'Stock' => $stock, 'Precio' => $precio, 'Exonerado' => $exonerado, 'Costo' => $costo, 'ValorTipoCambio' => $valorTipoCambio, 'TipoOperacion' => $tipoOp, 'Imagen' => $imagen, 'Codigo' => $codigo, 'CodigoInterno' => $codigo_interno, 'ventaMayor1' => $ventasMayor1, 'Descuento1' => $descuento1, 'PrecioDescuento1' => $precioDescuento1, 'ventaMayor2' => $ventasMayor2, 'Descuento2' => $descuento2,
                'PrecioDescuento2' => $precioDescuento2, 'ventaMayor3' => $ventasMayor3, 'Descuento3' => $descuento3, 'PrecioDescuento3' => $precioDescuento3, 'IdTipoUnidad' => $tipoUnidad, 'NombreTipo' => $nombreTipo, 'CantidadTipo' => $cantidadTipo, 'DescuentoTipo' => $descuentoTipo, 'Estado' => $estado, 'PrecioTipo' => $precioTipo, 'Detalle' => $req->detalle];
            DB::table('articulo')->insert($array);

            $producto = $loadDatos->getProductoUltimoStock($idSucursal);
            if ($usuarioSelect->CodigoProducto == 1) {
                DB::table('articulo')
                    ->where('IdArticulo', $producto->IdArticulo)
                    ->update(['Codigo' => $producto->IdArticulo]);
            }

            $_array = ['IdArticulo' => $producto->IdArticulo, 'Costo' => $costo, 'Precio' => $precio, 'Cantidad' => $stock];
            DB::table('stock')->insert($_array);

            $kardex = array(
                'CodigoInterno' => $codigo_interno,
                'fecha_movimiento' => $fecha,
                'tipo_movimiento' => 8,
                'usuario_movimiento' => $idUsuario,
                'documento_movimiento' => "Inicial",
                'existencia' => $stock,
                'costo' => $precio,
                'IdArticulo' => $producto->IdArticulo,
                'IdSucursal' => $idSucursal,
                'Cantidad' => $stock,
                'Descuento' => 0,
                'ImporteEntrada' => (float) $costo * (float) $stock,
                'ImporteSalida' => 0,
                'estado' => 1,
            );
            DB::table('kardex')->insert($kardex);

            if ($usuarioSelect->CodigoProducto == 1) {
                return redirect('administracion/almacen/productos')->with('status', 'Se creo producto correctamente con Codigo : ' . $producto->IdArticulo);
            } else {
                return redirect('administracion/almacen/productos')->with('status', 'Se creo producto correctamente');
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function edit(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $producto = $loadDatos->getProductoSelect($id);
        $idSucursal = Session::get('idSucursal');
        $unidadMedidas = $loadDatos->getUnidadMedida();
        $undiadesPorMayor = $loadDatos->getUnidadesPorMayor(); //// esto se agrego por martin y se agrego en datos Controller
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $tipoMonedas = $loadDatos->getTipoMoneda();
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $categorias = $loadDatos->getCategorias($usuarioSelect->CodigoCliente);
        $marcas = $loadDatos->getMarcas($usuarioSelect->CodigoCliente);

        $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $exonerado = $datosEmpresa->Exonerado;
        $sucursal = $loadDatos->getSucursalSelect($idSucursal);
        $sucExonerado = $sucursal->Exonerado;

        $array = ['marcas' => $marcas, 'categorias' => $categorias, 'producto' => $producto, 'unidadMedidas' => $unidadMedidas, 'tipoMonedas' => $tipoMonedas, 'undiadesPorMayor' => $undiadesPorMayor, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'sucExonerado' => $sucExonerado, 'exonerado' => $exonerado];
        return view('administracion/almacen/articulos/editarArticulo', $array);
    }

    public function update(Request $req, $id)
    {
        try {
            $this->validateArticulos($req);
            $loadDatos = new DatosController();
            $fecha = $loadDatos->getDateTime();
            $idUsuario = Session::get('idUsuario');
            $idMarca = $req->marca;
            $idCategoria = $req->categoria;
            $tipoOp = $req->tipoOperacion;
            $precio = $req->precio;
            $stock = $req->stock;
            $uniMedida = $req->uniMedida;
            $exonerado = 1;
            $costo = $req->costo;
            $descripcion = $req->descripcion;
            $ubicacion = $req->ubicacion;
            $codigo = $req->codBarra;
            $ventasMayor1 = $req->ventaMayor1;
            $ventasMayor2 = $req->ventaMayor2;
            $ventasMayor3 = $req->ventaMayor3;
            $descuento1 = null; //$req->descuento1;
            $descuento2 = null; //$req->descuento2;
            $descuento3 = null; //$req->descuento3;
            $tipoUnidad = $req->uniMedidaMayor;

            if (floatval($costo) >= floatval($precio)) {
                return back()->with('error', 'El precio tiene que ser mayor que el costo')->withInput();
            }

            /////////////////////////////////martin
            $nombreTipoBand = DB::table('unidad_medida')
                ->where('IdUnidadMedida', $tipoUnidad)
                ->select('Nombre')
                ->first();
            if ($nombreTipoBand) {
                $nombreTipo = $nombreTipoBand->Nombre;
            } else {
                $nombreTipo = $req->nombreTipo;
            } //////////////////////martin

            $precioDescuento1 = $req->precioDescuento1;
            $precioDescuento2 = $req->precioDescuento2;
            $precioDescuento3 = $req->precioDescuento3;

            $cantidadTipo = $req->cantidadTipo;
            $descuentoTipo = null; //$req->descuentoTipo;
            $precioTipo = $req->precioTipo;

            if ($req->checkEliminarImagenAnterior === 'chekeado' && $req->imagen == null) {
                $this->deleteImagenArticulo($req->inputUrlImagenAnterior, $id);
            }

            if ($req->imagen != null) {
                // $this->deleteImagenActual($req->inputUrlImagenActual, $id, $req->imagen);
                // $imagen = $loadDatos->setImage($req->imagen);

                // Almacenar la imganen en el S3 y obtener la URL
                // $rucEmpresa = $loadDatos->getRucEmpresa($idUsuario)->Ruc;
                // $nombreImagen = "producto-{$req->inputCodigoInterno}-" . date('His');
                // $directorio = $this->generarUbicacionArchivo('ImagenesArticulos/', "$rucEmpresa/");
                // $imagen = $this->storeImagenFormatoFileS3($req->imagen, $imagenAnterior = $req->inputUrlImagenAnterior, $nombreImagen, $directorio, $accion = 'edit');

                $rucEmpresa = $loadDatos->getRucEmpresa($idUsuario)->Ruc;
                $nombreImagen = "producto-{$req->inputCodigoInterno}-" . date('His');
                $imagen = $this->storeImagenFormatoFileS3($req->imagen, $imagenAnterior = $req->inputUrlImagenAnterior, $nombreImagen, $directorio = '/ImagenesArticulos/', $rucEmpresa, $accion = 'edit');

                /***************************mi modificacion tabla productos******************************************/
                $table_prod = ['Nombre' => $descripcion, 'Imagen' => $imagen];
                /**********************************************************************************/

                $array = ['IdMarca' => $idMarca, 'IdCategoria' => $idCategoria, 'IdUnidadMedida' => $uniMedida, 'FechaModificacion' => $fecha, 'IdModificacion' => $idUsuario, 'Descripcion' => $descripcion, 'Ubicacion' => $ubicacion,
                    'Stock' => $stock, 'Precio' => $precio, 'Exonerado' => $exonerado, 'Costo' => $costo, 'TipoOperacion' => $tipoOp, 'Imagen' => $imagen, 'Codigo' => $codigo, 'ventaMayor1' => $ventasMayor1, 'Descuento1' => $descuento1, 'PrecioDescuento1' => $precioDescuento1, 'ventaMayor2' => $ventasMayor2, 'Descuento2' => $descuento2,
                    'PrecioDescuento2' => $precioDescuento2, 'ventaMayor3' => $ventasMayor3, 'Descuento3' => $descuento3, 'PrecioDescuento3' => $precioDescuento3, 'IdTipoUnidad' => $tipoUnidad, 'NombreTipo' => $nombreTipo, 'CantidadTipo' => $cantidadTipo, 'DescuentoTipo' => $descuentoTipo, 'PrecioTipo' => $precioTipo, 'Detalle' => $req->detalle];
            } else {
                /***************************mi modificacion tabla productos******************************************/
                $table_prod = ['Nombre' => $descripcion];
                /**********************************************************************************/

                $array = ['IdMarca' => $idMarca, 'IdCategoria' => $idCategoria, 'IdUnidadMedida' => $uniMedida, 'FechaModificacion' => $fecha, 'IdModificacion' => $idUsuario, 'Descripcion' => $descripcion, 'Ubicacion' => $ubicacion,
                    'Stock' => $stock, 'Precio' => $precio, 'Exonerado' => $exonerado, 'Costo' => $costo, 'TipoOperacion' => $tipoOp, 'Codigo' => $codigo, 'ventaMayor1' => $ventasMayor1, 'Descuento1' => $descuento1, 'PrecioDescuento1' => $precioDescuento1, 'ventaMayor2' => $ventasMayor2, 'Descuento2' => $descuento2,
                    'PrecioDescuento2' => $precioDescuento2, 'ventaMayor3' => $ventasMayor3, 'Descuento3' => $descuento3, 'PrecioDescuento3' => $precioDescuento3, 'IdTipoUnidad' => $tipoUnidad, 'NombreTipo' => $nombreTipo, 'CantidadTipo' => $cantidadTipo, 'DescuentoTipo' => $descuentoTipo, 'PrecioTipo' => $precioTipo, 'Detalle' => $req->detalle];
            }

            /***************************mi modificacion para actualizar la tabla productos******************************************/
            $cod_cliente = DB::table('articulo')
                ->select('CodigoInterno')
                ->where('IdArticulo', $id)
                ->first();

            DB::table('productos')
                ->where('CodigoInterno', $cod_cliente->CodigoInterno)
                ->update($table_prod);
            /*********************************************************************/

            DB::table('articulo')
                ->where('IdArticulo', $id)
                ->update($array);

            $_stock = $loadDatos->getUltimoStock($id);

            $_array = ['Costo' => $costo, 'Precio' => $precio];

            DB::table('stock')
                ->where('IdStock', $_stock->IdStock)
                ->update($_array);

            return redirect('administracion/almacen/productos')->with('status', 'Se actualizo producto correctamente');
            //return $nombreTipo;

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            $loadDatos = new DatosController();
            $fecha = $loadDatos->getDateTime();
            $idUsuario = Session::get('idUsuario');
            $array = ['FechaEliminacion' => $fecha, 'IdEliminacion' => $idUsuario, 'Estado' => 'D'];
            DB::table('articulo')
                ->where('IdArticulo', $id)
                ->update($array);

            return redirect('administracion/almacen/productos')->with('status', 'Se elimino producto correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function verificarProducto(Request $req, $id)
    {
        try {
            $loadDatos = new DatosController();
            $producto = $loadDatos->getProductoSelect($id);
            return Response(['succes', $producto]);

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function eliminacionPersonalizada(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $loadDatos = new DatosController();
            $idUsuario = Session::get('idUsuario');

            $idSucursal = Session::get('idSucursal');
            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

            $articulos = $loadDatos->getProductos($idSucursal);

            $array = ['articulos' => $articulos, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
            return view('administracion/almacen/articulos/eliminacionPersonalizada', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function eliminacionCompletada(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            if (!empty($req->id)) {
                for ($i = 0; $i < count($req->id); $i++) {
                    DB::table('articulo')
                        ->where('IdArticulo', $req->id[$i])
                        ->update(['Estado' => 'D', 'IdEliminacion' => $idUsuario, 'FechaEliminacion' => Carbon::now()->toDateTimeString()]);
                }
                return redirect('administracion/almacen/eliminacion-personalizada')->with('status', 'Se eliminaron productos seleccionados correctamente');
            } else {
                return redirect('administracion/almacen/eliminacion-personalizada')->with('error', 'No se selecciono ningún producto a eliminar');
            }
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    // public function search(Request $req)
    // {
    //     if ($req->ajax()) {
    //         $loadDatos = new DatosController();
    //         $idSucursal = Session::get('idSucursal');

    //         $cod_cliente = DB::table('sucursal')
    //             ->select('CodigoCliente')
    //             ->where('IdSucursal', $idSucursal)
    //             ->first();

    //         $sucPrincipal = DB::table('sucursal')
    //             ->select('IdSucursal')
    //             ->where('CodigoCliente', $cod_cliente->CodigoCliente)
    //             ->where('Principal', 1)
    //             ->first();

    //         if ($sucPrincipal->IdSucursal == $idSucursal) {
    //             Session::put('texto', $req->texto);
    //             $articulos = $loadDatos->getBuscarProductosVentas($req->texto, $req->tipoMoneda, $idSucursal, 0);
    //         } else {
    //             Session::put('texto', $req->texto);
    //             $articulos = $loadDatos->buscarAjaxProdSucursal($req->texto, $req->tipoMoneda, $idSucursal, 0);
    //         }
    //         return Response($articulos);
    //     }
    // }

    // public function paginationProductos(Request $req)
    // {
    //     if ($req->ajax()) {
    //         $idSucursal = Session::get('idSucursal');
    //         $loadDatos = new DatosController();

    //         $cod_cliente = DB::table('sucursal')
    //             ->select('CodigoCliente')
    //             ->where('IdSucursal', $idSucursal)
    //             ->first();

    //         $sucPrincipal = DB::table('sucursal')
    //             ->select('IdSucursal')
    //             ->where('CodigoCliente', $cod_cliente->CodigoCliente)
    //             ->where('Principal', 1)
    //             ->first();

    //         if ($sucPrincipal->IdSucursal == $idSucursal) {
    //             $text2 = Session::get('texto');
    //             $productos = $loadDatos->getProductosPagination($idSucursal, $req->textoBuscar, $req->tipoMoneda, 0);
    //         } else {
    //             $text2 = Session::get('texto');
    //             $productos = $loadDatos->paginarAjaxProdSucursal($idSucursal, $req->textoBuscar, $req->tipoMoneda, 0);
    //         }
    //         return Response($productos);
    //     }
    // }

    public function importar(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            try {
                if ($req->excel != null) {
                    $this->validarExcel($req);
                    //Excel::import(new ExcelDatosImportacion, $req->excel);
                    $collection = Excel::toCollection(new ExcelDatosImportacion, $req->excel);
                    //Excel::import($req->excel, function($reader){
                    //$importar = $reader->get();
                    $idUsuario = Session::get('idUsuario');
                    $loadDatos = new DatosController();
                    $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
                    $i = 0;
                    $arrayProductosNoGuardados = [];
                    $arrayProductosConCostoMayor = [];
                    $mensajeProductosNoGuardados = '';
                    $mensajeProductosConMayorCosto = '';
                    foreach ($collection[0] as $row) {
                        if ($i > 0) {
                            $time = round(microtime(true) * 1000);
                            $date = Carbon::now();
                            $idSucursal = Session::get('idSucursal');
                            $nombre = $row[0];
                            if ($nombre != null) {
                                $ubicacion = $row[1];
                                $nombreMarca = trim($row[10]);

                                if ($nombreMarca == null) {
                                    $nombreMarca = 'Varios';
                                }
                                $nombreCategoria = trim($row[9]);
                                if ($nombreCategoria == null) {
                                    $nombreCategoria = 'Varios';
                                }
                                $idMarca = $this->getMarca($nombreMarca, $date, $idSucursal, $idUsuario, $usuarioSelect->CodigoCliente);
                                $idCategoria = $this->getCategoria($nombreCategoria, $date, $idSucursal, $idUsuario, $usuarioSelect->CodigoCliente);
                                $idUnidadMedida = $this->getUnidadMedida($row[5]);
                                // dd($idUnidadMedida);
                                $stock = (float) $row[2];
                                $precio = (float) $row[3];
                                $costo = (float) $row[4];
                                $codigoBarra = trim($row[8]);
                                $precioMayor = $row[6];
                                $cantidadMayor = $row[7];
                                $tipoMoneda = trim($row[11]);
                                if ($tipoMoneda == 'Soles' || $tipoMoneda == 'SOLES' || $tipoMoneda == 'soles' || $tipoMoneda == null) {
                                    $tipoMoneda = 1;
                                } else {
                                    $tipoMoneda = 2;
                                }
                                $producto = $this->getBuscarProducto($nombre, $idCategoria, $idMarca, $idSucursal, $codigoBarra);

                                $codigoInterno = $time;

                                if ($stock == null) {
                                    $stock = 0;
                                }

                                if ($precio == null) {
                                    $precio = 0;
                                }

                                if ($costo == null) {
                                    $costo = 0;
                                }

                                if (count($producto) > 0) {
                                    $arrayProductosNoGuardados[$i - 1] = $nombre;
                                    $mensajeProductosNoGuardados = 'Se importarón los productos correctamente y se encontrarón Duplicados';
                                } else {
                                    // Nuevo codigo
                                    if (floatval($costo) >= floatval($precio)) {
                                        $arrayProductosConCostoMayor[$i - 1] = $nombre;
                                        $mensajeProductosConMayorCosto = 'Se importarón los productos correctamente y los Productos de las lista no se registrarón por  motivo que el COSTO es mayor que el PRECIO';
                                    }
                                    // Fin
                                    $array = ['IdMarca' => $idMarca, 'IdCategoria' => $idCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => $idUnidadMedida, 'IdSucursal' => $idSucursal, 'IdTipoMoneda' => $tipoMoneda, 'FechaCreacion' => $date, 'IdCreacion' => $idUsuario, 'Descripcion' => $nombre, 'Ubicacion' => $ubicacion,
                                        'Stock' => $stock, 'Precio' => $precio, 'Exonerado' => 1, 'Costo' => $costo, 'TipoOperacion' => 1, 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Codigo' => $codigoBarra, 'CodigoInterno' => $codigoInterno, 'VentaMayor1' => $cantidadMayor, 'PrecioDescuento1' => $precioMayor, 'Estado' => 'E', 'CantidadTipo' => 1];
                                    DB::table('articulo')->insert($array);

                                    $_producto = $loadDatos->getProductoUltimoStock($idSucursal);
                                    $_array = ['IdArticulo' => $_producto->IdArticulo, 'Costo' => $costo, 'Precio' => $precio, 'Cantidad' => $stock];
                                    DB::table('stock')->insert($_array);

                                    $table_prod = ['Nombre' => $nombre, 'id_creador' => $idSucursal, 'CodigoInterno' => $codigoInterno, 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg'];
                                    DB::table('productos')->insert($table_prod);

                                    $kardex = array(
                                        'CodigoInterno' => $codigoInterno,
                                        'fecha_movimiento' => $date,
                                        'tipo_movimiento' => 9,
                                        'usuario_movimiento' => $idUsuario,
                                        'documento_movimiento' => "Inicial",
                                        'existencia' => $stock,
                                        'costo' => $precio,
                                        'IdArticulo' => $_producto->IdArticulo,
                                        'IdSucursal' => $idSucursal,
                                        'Cantidad' => $stock,
                                        'Descuento' => 0,
                                        'ImporteEntrada' => $costo * $stock,
                                        'ImporteSalida' => 0,
                                        'estado' => 1,
                                    );
                                    DB::table('kardex')->insert($kardex);
                                }
                            }
                        }
                        $i++;
                        usleep(100000);
                    }
                    if (count($arrayProductosNoGuardados) >= 1 || count($arrayProductosConCostoMayor) >= 1) {
                        return redirect('administracion/almacen/productos')->with('arrayProductosNoGuardados', collect($arrayProductosNoGuardados))->with('errorProductosDuplicados', $mensajeProductosNoGuardados)->with('arrayProductoConMayorCosto', collect($arrayProductosConCostoMayor))->with('errorProductosCostoMayor', $mensajeProductosConMayorCosto);

                    } else {
                        return redirect('administracion/almacen/productos')->with('status', 'Se importaron datos correctamente');
                    }
                } else {
                    return redirect('administracion/almacen/productos')->with('error', 'No se cargaron datos para importar');
                }
            } catch (Exception $ex) {
                return redirect('administracion/almacen/productos')->with('error', 'El formato excel no coincide con el actual');
            }
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    protected function getBuscarProducto($nombre, $idCategoria, $idMarca, $idSucursal, $codigoBarra)
    {
        try {
            $producto = DB::table('articulo')
                ->where('IdSucursal', $idSucursal)
                ->where('IdCategoria', $idCategoria)
                ->where('IdMarca', $idMarca)
                ->where('Codigo', $codigoBarra)
                ->where('Estado', 'E')
                ->where('Descripcion', $nombre)
                ->get();
            return $producto;
        } catch (Exception $ex) {
            return redirect('administracion/almacen/productos')->with('error', 'Error al importar excel');
        }
    }

    protected function getMarca($nombreMarca, $date, $idSucursal, $idUsuario, $codCliente)
    {
        try {
            $marca = DB::table('marca')
                ->join('sucursal', 'marca.IdSucursal', '=', 'sucursal.IdSucursal')
                ->select('marca.*')
                ->where('sucursal.CodigoCliente', $codCliente)
                ->where('marca.Estado', 'E')
                ->where('marca.Nombre', $nombreMarca)
                ->get();
            if (count($marca) > 0) {
                return $marca[0]->IdMarca;
            } else {
                $arrayMarca = ['IdSucursal' => $idSucursal, 'FechaCreacion' => $date, 'IdCreacion' => $idUsuario, 'Nombre' => $nombreMarca, 'Descripcion' => '', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
                DB::table('marca')->insert($arrayMarca);

                $ultimo = DB::table('marca')
                    ->join('sucursal', 'marca.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->select('marca.*')
                    ->where('sucursal.CodigoCliente', $codCliente)
                    ->where('marca.Estado', 'E')
                    ->orderBy('marca.IdMarca', 'desc')
                    ->first();
                return $ultimo->IdMarca;
            }
        } catch (Exception $ex) {
            return redirect('administracion/almacen/productos')->with('error', 'Error al importar excel');
        }
    }

    protected function getCategoria($nombreCategoria, $date, $idSucursal, $idUsuario, $codCliente)
    {
        try {
            $categoria = DB::table('categoria')
                ->join('sucursal', 'categoria.IdSucursal', '=', 'sucursal.IdSucursal')
                ->select('categoria.*')
                ->where('sucursal.CodigoCliente', $codCliente)
                ->where('categoria.Estado', 'E')
                ->where('categoria.Nombre', $nombreCategoria)
                ->get();
            if (count($categoria) > 0) {
                return $categoria[0]->IdCategoria;
            } else {
                $arrayCategoria = ['IdSucursal' => $idSucursal, 'FechaCreacion' => $date, 'IdCreacion' => $idUsuario, 'Nombre' => $nombreCategoria, 'Descripcion' => '', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
                DB::table('categoria')->insert($arrayCategoria);

                $ultimo = DB::table('categoria')
                    ->join('sucursal', 'categoria.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->select('categoria.*')
                    ->where('sucursal.CodigoCliente', $codCliente)
                    ->where('categoria.Estado', 'E')
                    ->orderBy('categoria.IdCategoria', 'desc')
                    ->first();
                return $ultimo->IdCategoria;
            }
        } catch (Exception $ex) {
            return redirect('administracion/almacen/productos')->with('error', 'Error al importar excel');
        }
    }

    protected function getUnidadMedida($unidadMedida)
    {
        if ($unidadMedida == null) {
            $unidadMedida = 'Unidad';
        }
        $idUniMed = DB::table('unidad_medida')
            ->where('Nombre', $unidadMedida)
            ->first();
        return $idUniMed->IdUnidadMedida;

    }

    public function descargarFormato(Request $req)
    {
        //dd($req);
        //return response()->download(public_path().'\FormatoExcel\formato.xlsx');
        return response()->download(public_path() . '/FormatoExcel/formato.xlsx');

    }

    public function showSucursal(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $valorCheckSucursalFactExonerado = DB::table('sucursal')
            ->select('Exonerado')
            ->where('IdSucursal', $idSucursal)
            ->first();

        $texto = "";

        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $productos = $loadDatos->paginarAjaxProdSucursal($idSucursal, $texto, 1, 0);
        // dd($productos);
        $articulos = $productos; //$loadDatos->getProductosPagination($idSucursal, $texto);
        $array = ['articulos' => $articulos, 'textoBuscar' => '', 'principal' => 0, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'texto' => $texto, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'valorCheckSucursalFactExonerado' => $valorCheckSucursalFactExonerado];
        return view('administracion/almacen/articulos/articulos', $array);
    }

    public function createSucursal(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');

        $data = DB::table('articulo')
            ->where('IdSucursal', $idSucursal)
            ->where('CodigoInterno', $id)
            ->where('Estado', 'E')
            ->first();
        if (count($data) >= 1) {
            return 'No se puede';
        } else {
            $datos = DB::table('articulo')
                ->select('Descripcion', 'IdMarca', 'IdCategoria', 'Codigo', 'CodigoInterno')
                ->where('CodigoInterno', $id)
                ->where('Estado', 'E')
                ->first();

            $unidadMedidas = $loadDatos->getUnidadMedida();
            $undiadesPorMayor = $loadDatos->getUnidadesPorMayor(); // esto lo puse para la creacion
            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $categorias = $loadDatos->getCategorias($usuarioSelect->CodigoCliente);
            $marcas = $loadDatos->getMarcas($usuarioSelect->CodigoCliente);

            $array = ['datos' => $datos, 'marcas' => $marcas, 'categorias' => $categorias, 'unidadMedidas' => $unidadMedidas, 'undiadesPorMayor' => $undiadesPorMayor, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
            return view('administracion/almacen/articulos/crearArticuloSucursal', $array);
        }
    }

    public function storeSucursal(Request $req)
    {
        $corre = 0;
        try {
            $this->validateArticulos($req);
            $loadDatos = new DatosController();
            $fecha = $loadDatos->getDateTime();
            $idUsuario = Session::get('idUsuario');
            $idMarca = $req->marca;
            $idCategoria = $req->categoria;
            $idTipo = 1;
            $idSucursal = Session::get('idSucursal');

            $codigo_interno = $req->matriz;

            $precio = $req->precio;
            $stock = $req->stock;
            $uniMedida = $req->uniMedida;
            $exonerado = 1;
            $costo = $req->costo;
            $descripcion = $req->descripcion;
            $codigo = $req->codBarra;
            $ventasMayor1 = $req->ventaMayor1;
            $ventasMayor2 = $req->ventaMayor2;
            $ventasMayor3 = $req->ventaMayor3;
            $descuento1 = null; //$req->descuento1;
            $descuento2 = null; //$req->descuento2;
            $descuento3 = null; //$req->descuento3;
            $tipoUnidad = $req->uniMedidaMayor;

            /////////////////////////////////martin
            $nombreTipoBand = DB::table('unidad_medida')
                ->where('IdUnidadMedida', $tipoUnidad)
                ->select('Nombre')
                ->first();
            if ($nombreTipoBand) {
                $nombreTipo = $nombreTipoBand->Nombre;
            } else {
                $nombreTipo = $req->nombreTipo;
            } //////////////////////martin

            $precioDescuento1 = $req->precioDescuento1;
            $precioDescuento2 = $req->precioDescuento2;
            $precioDescuento3 = $req->precioDescuento3;

            $cantidadTipo = $req->cantidadTipo;
            $descuentoTipo = null; //$req->descuentoTipo;
            $precioTipo = $req->precioTipo;
            $estado = 'E';
            $tipoOp = 1;
            if ($req->imagen != null) {
                $imagen = $loadDatos->setImage($req->imagen);
            } else {
                $imagen = 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg';
            }

            /*********************mi modificacion tabla  productos*****************************/

            $table_prod = ['Nombre' => $descripcion, 'id_creador' => $idSucursal, 'CodigoInterno' => $codigo_interno, 'Imagen' => $imagen];
            DB::table('productos')->insert($table_prod);

            /******************************************************************/

            $array = ['IdMarca' => $idMarca, 'IdCategoria' => $idCategoria, 'IdTipo' => $idTipo, 'IdUnidadMedida' => $uniMedida, 'IdSucursal' => $idSucursal, 'IdTipoMoneda' => 1, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario, 'Descripcion' => $descripcion,
                'Stock' => $stock, 'Precio' => $precio, 'Exonerado' => $exonerado, 'Costo' => $costo, 'TipoOperacion' => $tipoOp, 'Imagen' => $imagen, 'Codigo' => $codigo, 'CodigoInterno' => $codigo_interno, 'ventaMayor1' => $ventasMayor1, 'Descuento1' => $descuento1, 'PrecioDescuento1' => $precioDescuento1, 'ventaMayor2' => $ventasMayor2, 'Descuento2' => $descuento2,
                'PrecioDescuento2' => $precioDescuento2, 'ventaMayor3' => $ventasMayor3, 'Descuento3' => $descuento3, 'PrecioDescuento3' => $precioDescuento3, 'IdTipoUnidad' => $tipoUnidad, 'NombreTipo' => $nombreTipo, 'CantidadTipo' => $cantidadTipo, 'DescuentoTipo' => $descuentoTipo, 'Estado' => $estado, 'PrecioTipo' => $precioTipo];
            DB::table('articulo')->insert($array);

            $producto = $loadDatos->getProductoUltimoStock($idSucursal);
            $_array = ['IdArticulo' => $producto->IdArticulo, 'Costo' => $costo, 'Precio' => $precio, 'Cantidad' => $stock];
            DB::table('stock')->insert($_array);

            return redirect('administracion/almacen/productos')->with('status', 'Se creo producto correctamente');
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function storeProdSucursal(Request $req)
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
            $exonerado = 1;
            $tipoOp = 1;
            $fecha = $loadDatos->getDateTime();
            for ($i = 0; $i < count($articulos); $i++) {
                $articulo = DB::table('articulo')
                    ->select('Descripcion', 'IdMarca', 'IdCategoria', 'IdTipo', 'IdUnidadMedida', 'Codigo', 'IdTipoMoneda')
                    ->where('CodigoInterno', $articulos[$i]['codigoInterno'])
                    ->first();

                $marca = $articulo->IdMarca;
                $categoria = $articulo->IdCategoria;
                $detalle = $articulo->Descripcion;
                $tipo = $articulo->IdTipo;
                $unidad = $articulo->IdUnidadMedida;
                $cod = $articulo->Codigo;
                $tipoMoneda = $articulo->IdTipoMoneda;

                $data = array('IdMarca' => $marca, 'IdCategoria' => $categoria, 'Descripcion' => $detalle, 'Costo' => $articulos[$i]['costo'], 'Precio' => $articulos[$i]['precio'], 'Exonerado' => $exonerado, 'TipoOperacion' => $tipoOp,
                    'Stock' => $articulos[$i]['stock'], 'IdSucursal' => $idSucursal, 'IdTipoMoneda' => $tipoMoneda, 'IdTipo' => $tipo, 'IdUnidadMedida' => $unidad, 'IdCreacion' => $idUsuario, 'FechaCreacion' => $fecha, 'Codigo' => $cod, 'CodigoInterno' => $articulos[$i]['codigoInterno'], 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E');

                DB::table('articulo')->insert($data);

                $producto = $loadDatos->getProductoUltimoStock($idSucursal);
                $_array = ['IdArticulo' => $producto->IdArticulo, 'Costo' => $articulos[$i]['costo'], 'Precio' => $articulos[$i]['precio'], 'Cantidad' => $articulos[$i]['stock']];
                DB::table('stock')->insert($_array);

                $kardex = array(
                    'CodigoInterno' => $articulos[$i]['codigoInterno'],
                    'fecha_movimiento' => $fecha,
                    'tipo_movimiento' => 10,
                    'usuario_movimiento' => $idUsuario,
                    'documento_movimiento' => "Inicial",
                    'existencia' => $articulos[$i]['stock'],
                    'costo' => $articulos[$i]['precio'],
                    'IdArticulo' => $producto->IdArticulo,
                    'IdSucursal' => $idSucursal,
                    'Cantidad' => $articulos[$i]['stock'],
                    'Descuento' => 0,
                    'ImporteEntrada' => (float) $articulos[$i]['costo'] * (float) $articulos[$i]['stock'],
                    'ImporteSalida' => 0,
                    'estado' => 1,
                );
                DB::table('kardex')->insert($kardex);
                usleep(100000);
            }

            return ('Se crearon productos correctamente');
        }

    }

    public function verificarTipoCambio(Request $req)
    {
        if ($req->ajax()) {
            $idSucursal = Session::get('idSucursal');
            $loadDatos = new DatosController();
            $fecha = Carbon::today();

            $data = DB::table('tipo_cambio')
                ->where('FechaCreacion', $fecha)
                ->where('IdSucursal', $idSucursal)
                ->get();

            return Response($data);
        }
    }

    protected function validateArticulos(Request $request)
    {
        $this->validate($request, [
            'descripcion' => 'required',
            'stock' => 'required|numeric',
            'precio' => 'required|numeric',
            'costo' => 'required|numeric',
            'categoria' => 'required',
            'marca' => 'required',
            'imagen' => 'max:500',
        ]);
    }

    protected function validarExcel(Request $request)
    {
        $this->validate($request, [
            'excel' => 'required|mimes:xlsx,xls|max:220',
        ]);
    }

    public function exportExcel()
    {
        $idSucursal = Session::get('idSucursal');
        $loadDatos = new DatosController();
        $productos = DB::table('articulo')
            ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
            ->join('categoria', 'articulo.IdCategoria', '=', 'categoria.IdCategoria')
            ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
            ->select('articulo.*', 'marca.Nombre as Marca', 'categoria.Nombre as Categoria', 'unidad_medida.Nombre as UM', DB::raw('IFNULL(articulo.Costo, 0.0) as Costo'), DB::raw('IFNULL(articulo.Precio, 0.0) as Precio'), DB::raw('IFNULL(articulo.Stock, 0) as Stock'))
            ->where('IdTipo', 1)
            ->where('articulo.IdSucursal', $idSucursal)
            ->where('articulo.Estado', 'E')
            ->get();
        return Excel::download(new ExcelReporteProductos($productos), 'ReporteProductos.xlsx');
    }
}
