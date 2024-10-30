<?php

namespace App\Http\Controllers\Administracion\Almacen;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use PDF;
use Session;

class BajaProductosController extends Controller
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

        $fecha = Carbon::now()->subDays(30)->toDateString();
        $bajaProductos = DB::table('baja_producto as BP')
            ->join('articulo', 'BP.IdProducto', '=', 'articulo.IdArticulo')
            ->join('usuario', 'BP.IdUsuario', '=', 'usuario.IdUsuario')
            ->where('BP.IdSucursal', $idSucursal)
            ->where('BP.FechaBaja', '>', $fecha)
            ->groupBy('BP.FechaBaja')
            ->selectRaw('BP.IdBajaProducto, usuario.Nombre as NombreUsuario, articulo.Codigo as CodigoBarra, articulo.Descripcion as NombreArticulo, BP.FechaBaja, BP.IdMotivo, SUM(BP.Cantidad) as TotalCantidad')
            ->get();
        $array = ['permisos' => $permisos, 'idSucursal' => $idSucursal, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'bajaProductos' => $bajaProductos];
        return view('administracion/almacen/bajaProductos/index', $array);
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
        $text = "";

        /****para  traer el principal sucursal *********/
        $cod_cliente = DB::table('sucursal')
            ->select('CodigoCliente')
            ->where('IdSucursal', $idSucursal)
            ->first();

        $sucPrincipal = DB::table('sucursal')
            ->select('IdSucursal')
            ->where('CodigoCliente', $cod_cliente->CodigoCliente)
            ->where('Principal', 1)
            ->first();

        /* $correlativo = DB::table('articulo')->select('Correlativo')
        ->where('Principal', $sucPrincipal->IdSucursal)
        ->orderBy('IdArticulo', 'desc')
        ->first();  */
        /***********************************end traer principal***********************************/

        $tipoMonedas = $loadDatos->getTipoMoneda();

        if ($sucPrincipal->IdSucursal == $idSucursal) {
            $productos = $loadDatos->getProductosPagination($idSucursal, $text, 1, 0);
        } else {
            $productos = $loadDatos->paginarAjaxProdSucursal($idSucursal, $text, 1, 0);
        }

        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['tipoMoneda' => $tipoMonedas, 'productos' => $productos, 'permisos' => $permisos, 'idSucursal' => $idSucursal, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('administracion/almacen/bajaProductos/create', $array);
    }

    public function show(Request $req, $id)
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
        $fechaBaja = DB::table('baja_producto as BP')->where('IdBajaProducto', $id)->first()->FechaBaja;
        $listaUsuarios = DB::table('usuario')->select('IdUsuario', 'Nombre', 'Telefono')->where('CodigoCliente', $usuarioSelect->CodigoCliente)->where('Estado', 'E')->get();

        $detalleBaja = $this->getDetalleBajaProductos($idSucursal, $fechaBaja);

        $array = ['permisos' => $permisos, 'idSucursal' => $idSucursal, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'detalleBaja' => $detalleBaja, 'listaUsuarios' => $listaUsuarios, 'idDetalleBaja' => $id];
        return view('administracion/almacen/bajaProductos/detalleBaja', $array);

    }

    public function store(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                if ($req->ajax()) {
                    try {
                        DB::beginTransaction();
                        $stockSuficiente = $this->verificarStockSuficiente($req);
                        if (empty($req->Id)) {
                            return Response()->json(['respuesta' => 'error', 'mensaje' => 'No ha seleccionado ningún articulo']);

                        }
                        if (!empty($stockSuficiente)) {
                            return Response()->json(['respuesta' => 'error', 'mensaje' => 'Quedan ' . $stockSuficiente[1] . ' unidades en stock de : ' . $stockSuficiente[0]]);

                        } else {
                            $idUsuario = Session::get('idUsuario');
                            $idSucursal = Session::get('idSucursal');
                            $loadDatos = new DatosController();
                            $fecha = $loadDatos->getDateTime();
                            $idDetalleBaja = '';

                            $idMotivo = $req->idMotivo;
                            $otros = $req->otros;
                            if ($idMotivo == 4) {
                                if ($otros == null) {
                                    return Response()->json(['respuesta' => 'error', 'mensaje' => 'Por favor, ingresar descripción de otros motivos']);
                                }
                            }
                            if ($req->Id == null) {
                                return Response()->json(['respuesta' => 'error', 'mensaje' => 'Por favor, agrege productos que se dara de baja']);
                            }
                            for ($i = 0; $i < count($req->Id); $i++) {
                                $cantidadRestada = (float) $req->Stock[$i] - (float) $req->Cantidad[$i];
                                DB::table('articulo')
                                    ->where('IdArticulo', $req->Id[$i])
                                    ->update(['Stock' => $cantidadRestada]);

                                $kardex = array(
                                    'fecha_movimiento' => $fecha,
                                    'tipo_movimiento' => 4,
                                    'usuario_movimiento' => $idUsuario,
                                    'documento_movimiento' => 'Baja Producto',
                                    'existencia' => $cantidadRestada,
                                    'costo' => 1,
                                    'IdArticulo' => $req->Id[$i],
                                    'IdSucursal' => $idSucursal,
                                    'Cantidad' => $req->Cantidad[$i],
                                    'Descuento' => 0,
                                    'ImporteEntrada' => 0,
                                    'ImporteSalida' => 0,
                                    'estado' => 1,
                                );
                                DB::table('kardex')->insert($kardex);

                                $this->actualizarStock($req->Id[$i], $req->Cantidad[$i]);

                                $arrayRelacion = ['IdUsuario' => $idUsuario, 'IdSucursal' => $idSucursal, 'IdProducto' => $req->Id[$i], 'FechaBaja' => $fecha, 'Cantidad' => $req->Cantidad[$i], 'IdMotivo' => $idMotivo, 'DescripcionMotivo' => $otros];
                                $idDetalleBaja = DB::table('baja_producto')->insertGetId($arrayRelacion);
                                usleep(200000);
                            }
                        }
                        DB::commit();
                        return Response()->json(['respuesta' => 'success', 'mensaje' => 'Se guardarón los datos correctamente', 'idDetalleBaja' => $idDetalleBaja]);
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        $idMaximoKardex = DB::table('kardex')->SELECT(DB::RAW("MAX(IdKardex) AS IdMaximo"))->first();
                        $idMaximoKardex = $idMaximoKardex->IdMaximo + 1;
                        DB::statement("ALTER TABLE kardex AUTO_INCREMENT=" . $idMaximoKardex);

                        $idMaximoBaja = DB::table('baja_producto')->SELECT(DB::RAW("MAX(IdBajaProducto) AS IdMaximo"))->first();
                        $idMaximoBaja = $idMaximoBaja->IdMaximo + 1;
                        DB::statement("ALTER TABLE baja_producto AUTO_INCREMENT=" . $idMaximoBaja);
                        return Response()->json(['respuesta' => 'error', 'mensaje' => 'Se produjo un error, por favor comuniquese con el área de soporte']);
                    }
                }
            } else {
                Session::flush();
                return redirect('/')->with('out', 'Sesión de usuario Expirado');
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getPdf(Request $req, $id, $accion)
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
        // ============================
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $rucEmpresa = $empresa->Ruc;
        $sucursal = $loadDatos->getSucursales($usuarioSelect->CodigoCliente, $usuarioSelect->IdOperador)->where("Principal", 0)->first();
        $fechaBaja = DB::table('baja_producto as BP')->where('IdBajaProducto', $id)->first()->FechaBaja;
        $detalleBaja = $this->getDetalleBajaProductos($idSucursal, $fechaBaja);

        $arrayDatos = ['empresa' => $empresa, 'detalleBaja' => $detalleBaja, 'sucursal' => $sucursal, 'firmaUsuario' => $usuarioSelect->ImagenFirma];
        view()->share($arrayDatos);
        $pdf = PDF::loadView('pdf/bajaProductosPdf')->setPaper('a4', 'portrait');
        if ($accion == 'Descargar') {
            return $pdf->download('BajaProductos.pdf');
        }
        if ($accion == 'EnviarPorWhatsApp') {
            $numeroCelular = $req->inputTelefono;
            $fecha = carbon::parse($fechaBaja)->isoFormat('D [de] MMMM [de] YYYY [a las] HH:mm:ss');
            $resultadoBusqueda = $detalleBaja->where('FechaBaja', $fechaBaja)->where('IdSucursal', $idSucursal)->whereNotNull('UrlPdf')->first();
            // verifica si el detalle de baja ta tiene asignado una url de Pdf
            if (empty($resultadoBusqueda)) {
                $fechaCreacionPdf = Carbon::now()->toDateTimeString();
                $urlPdf = $loadDatos->storePdfWhatsAppS3($pdf, $carpeta = 'BajaProductos', $rucEmpresa, $idUsuario, date('His'));
                DB::table('baja_producto')
                    ->where('FechaBaja', $fechaBaja)
                    ->where('IdBajaProducto', $id)
                    ->update(['UrlPdf' => $urlPdf, 'FechaCreacionPdf' => $fechaCreacionPdf]);
            } else {
                $urlPdf = $resultadoBusqueda->UrlPdf;
            }
            if ($req->inputNombreUsuario == '') {
                $nombreUsuario = 'Agradeceré';
            } else {
                $nombreUsuario = "¡Hola *$req->inputNombreUsuario*, agradeceré";
            }
            $mensaje = "$nombreUsuario que puedas atender la solicitud y realizar la entrega de los productos descritos en el documento adjunto según la cantidad indicada en este. %0AEsta entrega esta autorizada por *$usuarioSelect->Nombre*, en la fecha: *$fecha*%0A%0A*Nunca olvides*: La única manera de hacer un trabajo genial es amar lo que haces 🥳🥳.%0A%0A" . config('variablesGlobales.urlDominioAmazonS3') . $urlPdf;

            if ($loadDatos->isMobileDevice()) {

                return redirect('https://api.whatsapp.com/send?phone=+51' . $numeroCelular . '&text=' . $mensaje);
            } else {
                return redirect('https://web.whatsapp.com/send?phone=51' . $numeroCelular . '&text=' . $mensaje);
            }
        }
        // ============================
    }

    private function getDetalleBajaProductos($idSucursal, $fechaBaja)
    {
        $datosBaja = DB::table('baja_producto as BP')
            ->join('articulo', 'BP.IdProducto', '=', 'articulo.IdArticulo')
            ->join('unidad_medida as UM', 'articulo.IdUnidadMedida', '=', 'UM.IdUnidadMedida')
            ->where('BP.IdSucursal', $idSucursal)
            ->where('BP.FechaBaja', $fechaBaja)
            ->select('BP.Idproducto', 'BP.Cantidad as CantidadBajas', 'articulo.Stock as NuevoStock', 'articulo.Codigo as CodigoBarra', 'articulo.Descripcion as NombreArticulo', 'UM.Nombre as NombreUnidadMedida', 'BP.FechaBaja', 'BP.IdMotivo', 'BP.DescripcionMotivo', 'BP.UrlPdf', 'BP.IdSucursal')
            ->get();
        return $datosBaja;
    }

    private function verificarStockSuficiente($req)
    {
        $loadDatos = new DatosController();
        $array = [];
        for ($i = 0; $i < count($req->Id); $i++) {
            $productoSelect = $loadDatos->getProductoSelect($req->Id[$i]);
            if (floatval($req->Cantidad[$i]) > floatval($req->Stock[$i])) {
                array_push($array, $productoSelect->Descripcion);
                array_push($array, $productoSelect->Stock);
                return $array;
            }
        }
        return $array;
    }

    private function actualizarStock($Id, $Cantidad)
    {
        $loadDatos = new DatosController();
        $productoSelect = $loadDatos->getProductoStockSelect($Id);
        if ($Cantidad > $productoSelect[0]->Cantidad) {
            $resto = (float) $Cantidad - (float) $productoSelect[0]->Cantidad;
            DB::table('stock')
                ->where('IdStock', $productoSelect[0]->IdStock)
                ->update(['Cantidad' => 0]);
            if ($resto > $productoSelect[1]->Cantidad) {
                $resto = $resto - (float) $productoSelect[1]->Cantidad;
                DB::table('stock')
                    ->where('IdStock', $productoSelect[1]->IdStock)
                    ->update(['Cantidad' => 0]);
            } else {
                $dif = (float) $productoSelect[1]->Cantidad - $resto;
                DB::table('stock')
                    ->where('IdStock', $productoSelect[1]->IdStock)
                    ->update(['Cantidad' => $dif]);
            }
        } else {
            $dif = (float) $productoSelect[0]->Cantidad - (float) $Cantidad;
            DB::table('stock')
                ->where('IdStock', $productoSelect[0]->IdStock)
                ->update(['Cantidad' => $dif]);
        }
    }

    public function searchProducto(Request $req)
    {
        if ($req->ajax()) {
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            Session::put('bajaText1', $req->textoBuscar);
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
                $articulos = $loadDatos->getBuscarProductosVentas($req->textoBuscar, $req->tipoMoneda, $idSucursal, 0);
            } else {
                $articulos = $loadDatos->buscarAjaxProdSucursal($req->textoBuscar, $req->tipoMoneda, $idSucursal, 0);
            }

            return Response($articulos);
        }
    }

    public function searchCodigoProducto(Request $req)
    {
        if ($req->ajax()) {
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $articulos = $loadDatos->getBuscarCodigoProductoVentas($req->codigoBusqueda, $idSucursal);
            return Response($articulos);
        }
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

                $productos = $loadDatos->getProductosPagination($idSucursal, $req->textoBuscar, $req->tipoMoneda, 0);
            } else {

                $productos = $loadDatos->paginarAjaxProdSucursal($idSucursal, $req->textoBuscar, $req->tipoMoneda, 0);
            }
            return Response($productos);
        }
    }
}
