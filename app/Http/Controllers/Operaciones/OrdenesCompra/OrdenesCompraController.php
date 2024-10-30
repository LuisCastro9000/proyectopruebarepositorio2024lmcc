<?php

namespace App\Http\Controllers\Operaciones\OrdenesCompra;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use App\Http\Controllers\Operaciones\NumeroALetras;
use App\Traits\getFuncionesTrait;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use PDF;
use PHPMailer\PHPMailer\PHPMailer;
use Session;

class OrdenesCompraController extends Controller
{
    use getFuncionesTrait;
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
        $ordenesCompra = $this->getOrdenesCompras($idSucursal);
        
        $array = ['permisos' => $permisos, 'idSucursal' => $idSucursal, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'ordenesCompra' => $ordenesCompra];

        return view('operaciones/ordenesCompra/index', $array);
    }

    public function create(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                $idUsuario = Session::get('idUsuario');
            } else {
                Session::flush();
                return redirect('/')->with('out', 'Sesión de usuario Expirado');
            }
            // dd('crearCompra');
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $permisos = $loadDatos->getPermisos($idUsuario);
            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $sucursal = $loadDatos->getSucursalSelect($idSucursal);
            $sucExonerado = $sucursal->Exonerado;
            $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
            $exonerado = $datosEmpresa->Exonerado;
            $proveedores = $loadDatos->getproveedores($idSucursal);
            $tipoMonedas = $loadDatos->getTipoMoneda();
            $text = '';
            $productos = $loadDatos->getProductosPagination($idSucursal, $text, 1, 0);
            $categorias = $loadDatos->getCategorias($usuarioSelect->CodigoCliente);
            $fecha = date('d/m/Y');

            $correlativoActual = $this->getCorrelativoActual($idUsuario, $idSucursal);
            $nuevoCorrelativo = $this->generarCorrelativo('OC', $correlativoActual, $sucursal->Orden);

            $array = ['permisos' => $permisos, 'idSucursal' => $idSucursal, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'serie' => $nuevoCorrelativo->Serie, 'numero' => $nuevoCorrelativo->Numero, 'proveedores' => $proveedores,
                'tipoMonedas' => $tipoMonedas, 'productos' => $productos, 'categorias' => $categorias, 'textoBuscar' => '', 'sucExonerado' => $sucExonerado, 'exonerado' => $exonerado, 'fecha' => $fecha];
            return view('operaciones/ordenesCompra/create', $array);

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function store(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                if ($req->ajax()) {
                    try {
                        DB::beginTransaction();
                        $idUsuario = Session::get('idUsuario');
                        $idSucursal = Session::get('idSucursal');
                        $loadDatos = new DatosController();
                        $textoMensaje = '';
                        $idRegistro = '';

                        // CODIGO PARA GUARDAR ORDEN COMPRA
                        if ($req->valueBotonOrdenCompra == 'Pendiente') {
                            $ordenCompra = $req->ordenCompra;
                            $datosOrdenCompra = json_decode($ordenCompra, true);
                            if ($datosOrdenCompra['IdProveedor'] == 0) {
                                return response()->json(['respuesta' => 'error', 'mensaje' => 'Por favor, elegir Proveedor.']);
                            }
                            if ($datosOrdenCompra['Articulos'] == null) {
                                return response()->json(['respuesta' => 'error', 'mensaje' => 'Por favor, agrege productos.']);
                            }
                            if ($datosOrdenCompra['FechaRecepcion'] == '') {
                                return response()->json(['respuesta' => 'error', 'mensaje' => 'Por favor, ingresar fecha de recepción.']);
                            }

                            if ($datosOrdenCompra['IdTipoPago'] == 2 && $datosOrdenCompra['DiasPlazoCredito'] == '') {
                                return response()->json(['respuesta' => 'error', 'mensaje' => 'Por favor, ingresar los días de plazo para realizar el pago.']);
                            }
                            $numeroOrdenCompra = $datosOrdenCompra['Numero'];
                            $resultado = $this->existeCorrelativo($datosOrdenCompra['Serie'], $numeroOrdenCompra, $idSucursal);
                            if ($resultado) {
                                $resultado = DB::table('orden_compra')
                                    ->select(DB::raw('MAX(Numero) as Numero'))
                                    ->where('IdSucursal', $idSucursal)
                                    ->where('IdUsuarioCreacion', $idUsuario)
                                    ->first();
                                $numeroOrdenCompra = str_pad($resultado->Numero + 1, 8, '0', STR_PAD_LEFT);
                            }

                            $observacion = $datosOrdenCompra['Observacion'] == '' ? null : $datosOrdenCompra['Observacion'];
                            $fechaEmision = $this->formatearFechaRecibidaConSlash($datosOrdenCompra['FechaEmision']);
                            $fechaRecepcion = $this->formatearFechaRecibidaConSlash($datosOrdenCompra['FechaRecepcion']);

                            $datosOrdenCompra = array_merge($datosOrdenCompra, ['IdSucursal' => $idSucursal, 'IdUsuarioCreacion' => $idUsuario, 'FechaEmision' => $fechaEmision, 'FechaRecepcion' => $fechaRecepcion, 'Observacion' => $observacion, 'DiasPlazoCredito' => $datosOrdenCompra['DiasPlazoCredito'], 'Numero' => $numeroOrdenCompra]);
                            // eliminamos la propiedad articulos del objeto datosOrdenCompra e insertamos el resto de propiedades que requiere la tabla OrdenCompras
                            $datosOrdenCompraInsertar = collect($datosOrdenCompra)->except(['Articulos'])->toArray();
                            $idOrdenCompra = DB::table('orden_compra')->insertGetId($datosOrdenCompraInsertar);
                            // Fin

                            // Recorriendo los articulos de la compra
                            $detalleOrdenCompra = $datosOrdenCompra['Articulos'];
                            foreach ($detalleOrdenCompra as $key => $item) {
                                $precioCosto = floatval($item['PrecioCosto']);
                                if ($datosOrdenCompra['TipoCompra'] == 2) {
                                    $precioCosto = floatval($precioCosto * 1.18);
                                }
                                $detalleOrdenCompra[$key]['IdOrdenCompra'] = $idOrdenCompra;
                                $detalleOrdenCompra[$key]['PrecioCosto'] = $precioCosto;
                                usleep(200000);
                            }

                            // CODIGO PARA LA INSERCCION DE 5 ITEMS POR LOTE
                            $lotes = array_chunk($detalleOrdenCompra, 20);
                            foreach ($lotes as $lote) {
                                DB::table('detalle_orden_compra')->insert($lote);
                            }
                            // FIN
                            $textoMensaje = 'La Orden de Compra se registro correctamente';
                            $idRegistro = $idOrdenCompra;
                        }

                        // CODIGO PARA CONVERTIR ORDEN EN COMPRA ASOCIADO CON SU COMPROBANTE DE PAGO
                        if ($req->valueBotonOrdenCompra == 'Finalizado') {
                            $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
                            if ($caja == null) {
                                return response()->json(['respuesta' => 'errorCajaCerrada', 'mensaje' => 'Abrir Caja antes de realizar una compra.']);

                            } else {
                                $compra = $req->compra;
                                $valorTipoCambio = $req->valorCambio;
                                $datosCompra = json_decode($compra, true);
                                $observacion = $datosCompra['Observacion'] == '' ? null : $datosCompra['Observacion'];
                                $fechaConvertida = $this->formatearFechaRecibidaConGuion($datosCompra['FechaCreacion']);
                                $datosCompra = array_merge($datosCompra, ['Idsucursal' => $idSucursal, 'IdCreacion' => $idUsuario, 'FechaCreacion' => $fechaConvertida, 'Observacion' => $observacion]);

                                if ($datosCompra['IdTipoComprobante'] == 0) {
                                    return response()->json(['respuesta' => 'error', 'mensaje' => 'Por favor, seleccionar Tipo de comprobante.']);
                                }
                                if ($datosCompra['Serie'] == '') {
                                    return response()->json(['respuesta' => 'error', 'mensaje' => 'Por favor, agregar serie.']);

                                }
                                if ($datosCompra['Numero'] == '') {
                                    return response()->json(['respuesta' => 'error', 'mensaje' => 'Por favor, agregar número.']);
                                }

                                // Validar serie y numero
                                $respuesta = $this->existeCorrelativoCompra($datosCompra['Serie'], $datosCompra['Numero'], $idSucursal, $datosCompra['IdProveedor']);
                                if ($respuesta) {
                                    // return Response(['error', 'La Serie y Número ya existen, Por favor vuelva a ingresarlo']);
                                    return response()->json(['respuesta' => 'error', 'mensaje' => 'La Serie y Número ya existen, Por favor vuelva a ingresarlo.']);

                                }
                                // Fin

                                if ($datosCompra['IdTipoPago'] == 1) {
                                    $montoEfect = $req->MontoEfect;
                                    $montoCuenta = $req->MontoCuenta;
                                    $cuentaBancaria = $req->CuentaBancaria;
                                    if (intval($cuentaBancaria) > 0) {
                                        $montoTotal = floatval($montoEfect) + floatval($montoCuenta);
                                        $banco = $loadDatos->getCuentaCorrienteSelect($cuentaBancaria);
                                        if (floatval($banco->MontoActual) < floatval($montoCuenta)) {
                                            return response()->json(['respuesta' => 'error', 'mensaje' => 'La cuenta bancaria no se encuentra con saldo suficiente disponible.']);
                                        }
                                    } else {
                                        $montoTotal = floatval($montoEfect);
                                    }
                                    $_montoTotal = round($montoTotal, 2);
                                    $_total = round($datosCompra['Total'], 2);
                                    if ($_montoTotal != $_total) {
                                        return response()->json(['respuesta' => 'error', 'mensaje' => 'La suma de pago efectivo y pago de cuenta bancaria debe ser igual al Importe Total.']);
                                    }
                                }

                                $datosComprasInsertar = collect($datosCompra)->except(['articulos'])->toArray();
                                $idCompra = DB::table('compras')->insertGetId($datosComprasInsertar);

                                if ($datosCompra['IdTipoPago'] == 2) {
                                    $interes = $req->Interes;
                                    if ($interes == null) {
                                        $interes = 0;
                                    }
                                    $this->guardarFechasPagosCompras($fechaConvertida, $datosCompra['PlazoCredito'], $idCompra, $datosCompra['Total'], $interes);
                                } else {
                                    if (intval($cuentaBancaria) > 0) {
                                        $numeroOp = $req->nroOperacion;
                                        $this->guardaDetallesCuentaBancaria($cuentaBancaria, $montoEfect, $montoCuenta, $numeroOp, $datosCompra['Serie'], $datosCompra['Numero'], $idSucursal);
                                    }
                                }
                                // Obtenemos los articulos de la Compra
                                $detalleCompra = $datosCompra['articulos'];

                                // Recorriendo los articulos de la compra
                                foreach ($detalleCompra as $key => $item) {
                                    $productoSelect = $loadDatos->getProductoSelect($item['IdArticulo']);
                                    $nuevoStock = (float) $productoSelect->Stock + (float) $item['Cantidad'];
                                    $precioCosto = floatval($item['PrecioCosto']);

                                    if ($datosCompra['TipoCompra'] == 2) {
                                        $precioCosto = floatval($precioCosto * 1.18);
                                    }

                                    if ($datosCompra['IdTipoMoneda'] == 2) {
                                        $valorTipoCambioPror = ($productoSelect->ValorTipoCambio + $valorTipoCambio) / 2;
                                    } else {
                                        $valorTipoCambioPror = 0;
                                    }

                                    DB::table('articulo')
                                        ->where('IdArticulo', $item['IdArticulo'])
                                        ->update(['Stock' => $nuevoStock, 'Costo' => $precioCosto, 'ValorTipoCambio' => $valorTipoCambioPror]);

                                    $itemsKardex[] = [
                                        'CodigoInterno' => $productoSelect->CodigoInterno,
                                        'fecha_movimiento' => $fechaConvertida,
                                        'tipo_movimiento' => 2,
                                        'usuario_movimiento' => $idUsuario,
                                        'documento_movimiento' => $datosCompra['Serie'] . '-' . $datosCompra['Numero'],
                                        'existencia' => $nuevoStock,
                                        'costo' => $precioCosto,
                                        'IdArticulo' => $item['IdArticulo'],
                                        'IdSucursal' => $idSucursal,
                                        'Cantidad' => $item['Cantidad'],
                                        'Descuento' => 0,
                                        'ImporteEntrada' => $item['Importe'],
                                        'ImporteSalida' => 0,
                                        'estado' => 1,
                                    ];

                                    $this->completarStockNuevo($item['IdArticulo'], $item['PrecioCosto'], $productoSelect->Precio, $item['Cantidad']);
                                    $detalleCompra[$key]['IdCompras'] = $idCompra;
                                    $detalleCompra[$key]['PrecioCosto'] = $precioCosto;
                                }

                                // Codigo de insercion por lotes
                                $lotesDetalleCompra = array_chunk($detalleCompra, 20);
                                foreach ($lotesDetalleCompra as $lote) {
                                    DB::table('compras_articulo')->insert($lote);
                                }

                                $lotesKardex = array_chunk($itemsKardex, 20);
                                foreach ($lotesKardex as $lote) {
                                    DB::table('kardex')->insert($lote);
                                }
                                $textoMensaje = 'La compra se registro correctamente';
                                $idRegistro = $idCompra;
                            }

                            // Codigo para cambiar el estado de la Orden de Compra  a Facturado
                            DB::table('orden_compra')
                                ->where('IdOrdenCompra', $req->idOrdenCompra)
                                ->update(['Estado' => 'Facturada']);
                            // Fin
                        }
                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollback();
                        return response()->json(['respuesta' => 'errorTransaccion', 'mensaje' => 'Ocurrio un error, por favor No INSISTA EN LA CREACIÓN, proceda a comunicarse con el Área de Soporte.']);
                    }
                    return response()->json(['respuesta' => 'success', 'mensaje' => $textoMensaje, 'id' => $idRegistro, 'accion' => 'store']);
                }
            } else {
                Session::flush();
                return redirect('/')->with('out', 'Sesión de usuario Expirado');
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function edit(Request $req, $id)
    {
        try {
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
            $sucursal = $loadDatos->getSucursalSelect($idSucursal);
            $sucExonerado = $sucursal->Exonerado;

            // Datos de orden de compra
            $ordenCompra = $this->getOrdenesComprasSelect($id);
            $itemsOrdenCompra = $this->getItemsOrdenCompra($id);
            $tipoOrdenCompra = $ordenCompra->TipoCompra;
            $text = '';
            $productos = $loadDatos->getProductosPagination($idSucursal, $text, 1, 0);
            $categorias = $loadDatos->getCategorias($usuarioSelect->CodigoCliente);

            $array = ['permisos' => $permisos, 'idSucursal' => $idSucursal, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'idOrdenComrpra' => $id, 'ordenCompra' => $ordenCompra, 'itemsOrdenCompra' => $itemsOrdenCompra, 'tipoOrdenCompra' => $tipoOrdenCompra, 'productos' => $productos, 'categorias' => $categorias, 'sucExonerado' => $sucExonerado];
            return view('operaciones/ordenesCompra/edit', $array);

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function update(Request $req, $id)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                if ($req->ajax()) {
                    try {
                        DB::beginTransaction();
                        $datosOrdenCompra = json_decode($req->ordenCompra, true);

                        $observacion = $datosOrdenCompra['Observacion'] == '' ? null : $datosOrdenCompra['Observacion'];
                        $datosOrdenCompraUpdate = ['FechaModificacion' => date("Y-m-d H:i:s"), 'Observacion' => $observacion, 'SubTotal' => $datosOrdenCompra['SubTotal'], 'Igv' => $datosOrdenCompra['Igv'], 'Total' => $datosOrdenCompra['Total']];
                        DB::table('orden_compra')->where('IdOrdenCompra', $id)->update($datosOrdenCompraUpdate);
                        // Obtener articlos anteriores
                        $arrayArticulosAnteriores = DB::table('detalle_orden_compra')->where('IdOrdenCompra', $id)->select('IdArticulo')->get()->pluck('IdArticulo');

                        // Recorriendo los articulos de la compra
                        $detalleOrdenCompra = $datosOrdenCompra['Articulos'];
                        $arrayItemsNoEliminar = [];
                        $articulosInsertar = [];

                        foreach ($detalleOrdenCompra as $key => $item) {
                            array_push($arrayItemsNoEliminar, $item['IdArticulo']);
                            $precioCosto = floatval($item['PrecioCosto']);
                            if ($datosOrdenCompra['TipoCompra'] == 2) {
                                $precioCosto = floatval($precioCosto * config('variablesGlobales.Igv'));
                            }
                            if ($arrayArticulosAnteriores->search($item['IdArticulo']) !== false) {
                                DB::table('detalle_orden_compra')
                                    ->where('IdOrdenCompra', $id)
                                    ->where('IdArticulo', $item['IdArticulo'])
                                    ->update(['PrecioCosto' => $precioCosto, 'Cantidad' => $item['Cantidad'], 'Importe' => $item['Importe']]);
                            } else {
                                array_push($articulosInsertar, array_merge($item, ['IdOrdenCompra' => $id, 'PrecioCosto' => $precioCosto]));
                            }
                        }
                        // CODIGO PARA LA INSERCCION DE 5 ITEMS POR LOTE
                        $lotes = array_chunk($articulosInsertar, 20);
                        foreach ($lotes as $lote) {
                            DB::table('detalle_orden_compra')->insert($lote);
                        }
                        // FIN
                        DB::table('detalle_orden_compra')
                            ->where('IdOrdenCompra', $id)
                            ->whereNotIn('IdArticulo', $arrayItemsNoEliminar)
                            ->delete();

                        DB::commit();
                        return response()->json(['respuesta' => 'success', 'mensaje' => 'La Orden de Compra se actualizó correctamente', 'id' => $id, 'accion' => 'update']);

                    } catch (\Exception $e) {
                        DB::rollback();
                        $this->restablecerSecuenciaId();
                        return Response()->json(['respuesta' => 'errorTransaccion', 'mensaje' => 'Ocurrio un error, por favor No INSISTA EN LA CREACIÓN, proceda a comunicarse con el Área de Soporte. ']);
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

    // private function restablecerSecuenciaId()
    // {
    //     $idMaximoOrdenCompra = DB::table('orden_compra')->max('IdOrdenCompra');
    //     DB::statement("ALTER TABLE orden_compra AUTO_INCREMENT=" . ($idMaximoOrdenCompra + 1));

    //     $idMaximoDetalleOrden = DB::table('detalle_orden_compra')->max('IdDetalleOrdenCompra');
    //     DB::statement("ALTER TABLE detalle_orden_compra AUTO_INCREMENT=" . ($idMaximoDetalleOrden + 1));
    // }

    public function show(Request $req, $id)
    {
        if (strpos(url()->previous(), 'consultas')) {
            $mostrarBotonConvertirOrden = 'false';
        } else {
            $mostrarBotonConvertirOrden = 'activo';
        }

        $loadDatos = new DatosController();
        $ordenCompraSelect = $this->getOrdenesComprasSelect($id);
        $idUsuario = Session::get('idUsuario');
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $fecha = date_create($ordenCompraSelect->FechaEmision);
        $formatoFecha = date_format($fecha, 'd-m-Y');
        $formatoHora = date_format($fecha, 'H:i A');
        $fechaRecepcion = Carbon::parse($ordenCompraSelect->FechaRecepcion)->format('Y-m-d');
        $itemsOrdenCompra = $this->getItemsOrdenCompra($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $array = ['ordenCompraSelect' => $ordenCompraSelect, 'formatoFecha' => $formatoFecha, 'permisos' => $permisos, 'formatoHora' => $formatoHora, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'fechaRecepcion' => $fechaRecepcion, 'itemsOrdenCompra' => $itemsOrdenCompra, 'mostrarBotonConvertirOrden' => $mostrarBotonConvertirOrden];
        return view('operaciones/ordenesCompra/show', $array)->with('status', 'Se registro compra exitosamente');
    }

    public function verVistaConvertirOrden(Request $req, $id)
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
        $sucursal = $loadDatos->getSucursalSelect($idSucursal);
        $sucExonerado = $sucursal->Exonerado;
        $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $exonerado = $datosEmpresa->Exonerado;

        $arrayItemsOrdenCompra = $this->getItemsOrdenCompra($id);
        $datosOrdenCompra = $this->getOrdenesComprasSelect($id);
        $tipoComprobante = $loadDatos->getTipoComprobante();
        // Nuevo Codigo
        if ($datosOrdenCompra->IdTipoDocumento == 1) {
            $tipoComprobante = $tipoComprobante->whereIn("IdTipoComprobante", [1, 3])->values();
        }
        if ($datosOrdenCompra->IdTipoDocumento == 2) {
            $tipoComprobante = $tipoComprobante->whereIn("IdTipoComprobante", [2, 3])->values();
        }
        // Fin
        $tipoMonedas = $loadDatos->getTipoMoneda();
        $cuentasSoles = $loadDatos->getCuentasCorrientes($usuarioSelect->CodigoCliente, 1);
        $cuentasDolares = $loadDatos->getCuentasCorrientes($usuarioSelect->CodigoCliente, 2);
        $idOrdenCompra = $id;

        $fecha = Carbon::today();
        $datosTipoCambio = DB::table('tipo_cambio')
            ->where('FechaCreacion', $fecha)
            ->where('IdSucursal', $idSucursal)
            ->get();

        $tipoCompra = $datosOrdenCompra->TipoCompra;

        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'sucExonerado' => $sucExonerado, 'exonerado' => $exonerado, 'arrayItemsOrdenCompra' => $arrayItemsOrdenCompra, 'datosOrdenCompra' => $datosOrdenCompra, 'tipoComprobante' => $tipoComprobante, 'tipoMoneda' => $tipoMonedas, 'cuentasSoles' => $cuentasSoles, 'cuentasDolares' => $cuentasDolares, 'idOrdenCompra' => $idOrdenCompra, 'datosTipoCambio' => $datosTipoCambio, 'tipoCompra' => $tipoCompra];
        return view('operaciones/ordenesCompra/convertirOrden', $array);
    }

    public function getDocumentoPdf(Request $req, $id, $accion)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        return $this->generarPdf($id, $accion, $req);
    }

    private function generarPdf($id, $accion, $req)
    {
        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');
        $ordenCompraSelect = $this->getOrdenesComprasSelect($id);
        $fecha = date_create($ordenCompraSelect->FechaEmision);
        $formatoFecha = date_format($fecha, 'd-m-Y');
        $formatoHora = date_format($fecha, 'H:i A');
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $inicialRuc = substr($empresa->Ruc, 0, 2);
        if ($inicialRuc == '10') {
            $nombreEmpresa = $empresa->NombreComercial;
        } else {
            $nombreEmpresa = $empresa->Nombre;
        }
        $convertirLetras = new NumeroALetras();
        if ($ordenCompraSelect->IdTipoMoneda == 1) {
            $importeLetras = $convertirLetras->convertir($ordenCompraSelect->Total, 'soles');
        } else {
            $importeLetras = $convertirLetras->convertir($ordenCompraSelect->Total, 'dólares');
        }
        $cuentasCorrientes = $this->getCuentasCorrientesParaPdf($usuarioSelect->CodigoCliente, null);
        $items = $this->getItemsOrdenCompra($id);
        $array = ['items' => $items, 'ordenCompraSelect' => $ordenCompraSelect, 'empresa' => $empresa, 'nombreEmpresa' => $nombreEmpresa, 'formatoFecha' => $formatoFecha, 'formatoHora' => $formatoHora, 'importeLetras' => $importeLetras, 'cuentasCorrientes' => $cuentasCorrientes];
        view()->share($array);
        $pdf = PDF::loadView('ordenCompraPDF');
        // return $pdf;

        if ($accion == 'imprimir') {
            return $pdf->stream();
        }

        if ($accion == 'descargar') {
            return $pdf->download('OrdenCompra-' . $id . '.pdf');
        }

        if ($accion == 'enviarCorreo') {

            $rucEmpresa = $empresa->Ruc;
            $numero = $ordenCompraSelect->Numero;
            $serie = $ordenCompraSelect->Serie;
            $fechaRecepcion = Carbon::createFromFormat('d-m-Y', $ordenCompraSelect->FechaRecepcion)->format('d/m/Y');

            file_put_contents($rucEmpresa . '-' . $serie . '-' . $numero . '.pdf', $pdf->output());
            if ($ordenCompraSelect->IdTipoPago == 1) {
                $tipoPago = '<p><span style="font-weight: bold;">Tipo Compra: Contado</span></p>';
            } else {
                $tipoPago = '<p><span style="font-weight: bold;">Tipo Compra: Crédito</span></p><p><span style="font-weight: bold;">Condición de Pago: ' . $ordenCompraSelect->DiasPlazoCredito . ' Días - (Después de la Fecha de Recepción)</span></p>';
            }

            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'mail.easyfactperu.pe';
            $mail->SMTPAuth = true;
            $mail->Username = 'facturacion@easyfactperu.pe';
            $mail->Debugoutput = 'html';
            $mail->Password = 'gV.S=o=Q,bl2';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->From = 'facturacion@easyfactperu.pe';
            $mail->FromName = 'EASYFACT PERÚ S.A.C.  - Facturación Electrónica';
            $mail->addAddress($req->correo, 'Comprobante');
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Envío de Orden de Compra';
            $mail->addAttachment($rucEmpresa . '-' . $serie . '-' . $numero . '.pdf');

            $mail->msgHTML('<table width="100%">'
                . '<tr>'
                . '<td style="border: 1px solid #000;">'
                . '<div align="center" style="background-color: #CCC">'
                . '<img width="150px" style="margin:15px" src="' . $empresa->Imagen . '">'
                . '</div>'
                . '<div style="margin-bottom:20px;margin-left:10px">'
                . '<p>Estimado(a),</p>'
                . '</div>'
                . '<div style="margin-bottom:20px;margin-left:10px">'
                . '<p>' . $ordenCompraSelect->Nombres . '</p>'
                . '</div>'
                . '<div style="margin-bottom:10px;margin-left:10px">'
                . '<p>Por encargo del emisor <span style="font-weight: bold;">' . $nombreEmpresa . '</span>, nos es grato informar que se le está detallando la lista de productos requeridos en la siguiente Orden de Compra:</p>'
                . '</div>'
                . '<hr style="border: 0.5px solid #000;">'
                . '<div style="margin-bottom:20px;margin-left:30px">'
                . '<p><span style="font-weight: bold;">Tipo: ORDEN DE COMPRA</span></p>'
                . '<p><span style="font-weight: bold;">Número: ' . $serie . '-' . $numero . '</span></p>'
                . '<p><span style="font-weight: bold;">RUC / DNI: ' . $rucEmpresa . '</span></p>'
                . '<p><span style="font-weight: bold;">Fecha Emisión: ' . $formatoFecha . '</span></p>'
                . '<p><span style="font-weight: bold;">Fecha Entrega: ' . $fechaRecepcion . '</span></p>'
                . '<p><span style="font-weight: bold;">Monto Total: ' . $ordenCompraSelect->Total . '</span></p>'
                . $tipoPago
                . '</div>'
                . '</td>'
                . '</tr>'
                . '</table>');
            $enviado = $mail->send();
            if ($enviado) {
                return back()->with('status', 'Se envio correo con éxito');
            } else {
                return back()->with('error', 'No se pudo enviar correo');
            }
        }
    }

    // FUNCIONES PARA OBTENER DATOS
    private function getCorrelativoActual($idUsuario, $idSucursal)
    {
        try {
            $resultado = DB::table('orden_compra')
                ->select('Numero')
                ->where('IdUsuarioCreacion', $idUsuario)
                ->where('IdSucursal', $idSucursal)
                ->orderBy('IdOrdenCompra', 'desc')
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getOrdenesCompras($idSucursal)
    {
        try {
            $ordencompras = DB::table('orden_compra')
                ->join('proveedor', 'orden_compra.IdProveedor', '=', 'proveedor.IdProveedor')
                ->select('orden_compra.*', 'proveedor.Nombre as Nombres', 'proveedor.NumeroDocumento')
                ->where('orden_compra.IdSucursal', $idSucursal)
                ->where('orden_compra.Estado', 'Pendiente')
                ->orderBy('IdOrdenCompra', 'desc')
                ->get();
            return $ordencompras;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getOrdenesComprasSelect($idOrdenCompra)
    {
        try {
            $ordencompras = DB::table('orden_compra')
                ->join('proveedor', 'orden_compra.IdProveedor', '=', 'proveedor.IdProveedor')
                ->join('tipo_documento', 'proveedor.IdTipoDocumento', '=', 'tipo_documento.IdTipoDocumento')
                ->join('sucursal', 'orden_compra.IdSucursal', '=', 'sucursal.IdSucursal')
                ->join('usuario', 'orden_compra.IdUsuarioCreacion', '=', 'usuario.IdUsuario')
                ->select('orden_compra.*', 'proveedor.Nombre as Nombres', 'proveedor.NumeroDocumento', 'proveedor.RazonSocial', 'proveedor.Direccion as DireccionProveedor', 'proveedor.Telefono as TelefonoProveedor', 'proveedor.Email as EmailProveedor', 'sucursal.Direccion as Local', 'sucursal.Ciudad as CiudadSucursal', 'sucursal.Nombre as Sucursal', 'sucursal.Ciudad', 'sucursal.Telefono as TelfSucursal', 'usuario.Nombre as Usuario', 'usuario.DNI as DNI', 'tipo_documento.Descripcion as TipoDocumento', 'tipo_documento.IdTipoDocumento', DB::raw("DATE_FORMAT(orden_compra.FechaEmision, '%d-%m-%Y' )AS FechaOrdenCompra"), DB::raw("DATE_FORMAT(orden_compra.FechaRecepcion, '%d-%m-%Y' )AS FechaRecepcion"))
                ->where('orden_compra.IdOrdenCompra', $idOrdenCompra)
                ->orderBy('IdOrdenCompra', 'desc')
                ->first();
            return $ordencompras;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getItemsOrdenCompra($idOrdenCompra)
    {
        try {
            $ordenCompra = DB::table('detalle_orden_compra')
                ->join('articulo', 'detalle_orden_compra.IdArticulo', '=', 'articulo.IdArticulo')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                ->select('detalle_orden_compra.*', 'detalle_orden_compra.CodigoArticulo', 'articulo.*', 'unidad_medida.Nombre as UniMedida', 'marca.Nombre as nombreMarca')
                ->where('detalle_orden_compra.IdOrdenCompra', $idOrdenCompra)
                ->get();
            return $ordenCompra;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function getCuentasCorrientesParaPdf($codigoCliente)
    {
        $resultado = DB::table('banco')
            ->join('lista_banco', 'banco.IdListaBanco', '=', 'lista_banco.IdListaBanco')
            ->join('tipo_moneda', 'banco.IdTipoMoneda', '=', 'tipo_moneda.IdTipoMoneda')
            ->select('banco.NumeroCuenta', 'lista_banco.Nombre as Banco', 'tipo_moneda.Nombre as Moneda', 'banco.CCI')
            ->where('CodigoCliente', $codigoCliente)
            ->where('banco.Estado', 'E')
            ->limit(5)
            ->get();
        return $resultado;
    }

    public function storeTipoCambio(Request $req)
    {
        if ($req->ajax()) {
            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
            $fecha = Carbon::today();

            $tipoCambioCompras = $req->tipoCambioCompras;
            $tipoCambioVentas = $req->tipoCambioVentas;

            if ($tipoCambioCompras == null || $tipoCambioCompras == '') {
                return Response(['alert', 'Completar el campo de tipo de cambio de compras']);
            }

            if ($tipoCambioVentas == null || $tipoCambioVentas == '') {
                return Response(['alert', 'Completar el campo de tipo de cambio de ventas']);
            }

            $array = ['FechaCreacion' => $fecha, 'IdUsuario' => $idUsuario, 'IdSucursal' => $idSucursal, 'TipoCambioCompras' => $tipoCambioCompras, 'TipoCambioVentas' => $tipoCambioVentas, 'Estado' => 'E'];
            DB::table('tipo_cambio')->insert($array);

            return Response(['success', 'Se guardo tipo de cambio correctamente']);
        }
    }

    // FUNCIONES PARA OBTENER DATOS CON AJAX
    // public function getProductosAjaxXpaginacion(Request $req)
    // {
    //     if ($req->ajax()) {
    //         $idSucursal = Session::get('idSucursal');
    //         $textCompra = Session::get('textCompra');
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
    //             $productos = $loadDatos->getProductosPagination($idSucursal, $textCompra, $req->tipoMoneda, $req->idCategoria);
    //         } else {
    //             $productos = $loadDatos->paginarAjaxProdSucursal($idSucursal, $textCompra, $req->tipoMoneda, $req->idCategoria);
    //         }

    //         return Response($productos);
    //     }
    // }
    // public function getProductosAjaxXbusqueda(Request $req)
    // {
    //     if ($req->ajax()) {
    //         $loadDatos = new DatosController();
    //         $idSucursal = Session::get('idSucursal');
    //         Session::put('textCompra', $req->textoBuscar);

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
    //             $articulos = $loadDatos->getBuscarProductosVentas($req->textoBuscar, $req->tipoMoneda, $idSucursal, $req->idCategoria);
    //         } else {
    //             $articulos = $loadDatos->buscarAjaxProdSucursal($req->textoBuscar, $req->tipoMoneda, $idSucursal, $req->idCategoria);
    //         }
    //         return Response($articulos);
    //     }
    // }

    public function getProductosAjaxXtipoMoneda(Request $req)
    {
        if ($req->ajax()) {
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $textoBuscar = '';

            $articulos = $loadDatos->getBuscarProductosVentas($textoBuscar, $req->tipoMoneda, $idSucursal, 0);
            return Response($articulos);
        }
    }

    // FUNCION VALIDACION SERIE Y NUMERO
    private function existeCorrelativo($serie, $numero, $idSucursal)
    {
        try {
            $resultado = DB::table('orden_compra')
                ->where('Serie', $serie)
                ->where('Numero', $numero)
                ->where('IdSucursal', $idSucursal)
                ->exists();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function existeCorrelativoCompra($serie, $numero, $idSucursal, $idProveedor)
    {
        try {
            $resultado = DB::table('compras')
                ->where('Serie', $serie)
                ->where('Numero', $numero)
                ->where('IdSucursal', $idSucursal)
                ->where('IdProveedor', $idProveedor)
                ->whereIn('Estado', ['Registrado', 'Pendiente'])
                ->exists();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // FUNCIONES USADAS EN CONVERTIR COMPRA
    public function guardarFechasPagosCompras($fechaConvertida, $plazoCredito, $idCompra, $total, $interes)
    {
        $plazoInteresTotal = $total + (($total / 100) * $interes);

        $fechaConvertidaFinal = strtotime('+' . $plazoCredito . ' day', strtotime($fechaConvertida));
        $fechaConvertidaFinal = date('Y-m-d', $fechaConvertidaFinal);

        $array = ['IdCompras' => $idCompra, 'FechaInicio' => $fechaConvertida, 'FechaUltimo' => $fechaConvertidaFinal, 'Importe' => $plazoInteresTotal, 'ImportePagado' => 0.00, 'DiasPasados' => 0, 'Estado' => 1];
        DB::table('fecha_compras')->insert($array);
    }
    public function guardaDetallesCuentaBancaria($cuentaBancaria, $montoEfect, $montoCuentaBanc, $numeroOp, $serie, $numero, $idSucursal)
    {
        $loadDatos = new DatosController();
        $montoCuenta = $loadDatos->getCuentaCorrienteSelect($cuentaBancaria);
        $montoActual = floatval($montoCuenta->MontoActual) - floatval($montoCuentaBanc);
        $fechaHoy = $loadDatos->getDateTime();
        $arrayDatos = ['FechaPago' => $fechaHoy, 'IdBanco' => $cuentaBancaria, 'NumeroOperacion' => $numeroOp, 'Detalle' => $serie . '-' . $numero, 'TipoMovimiento' => 'Compras', 'Entrada' => '0', 'Salida' => $montoCuentaBanc, 'MontoActual' => $montoActual, 'IdSucursal' => $idSucursal];
        DB::table('banco_detalles')->insert($arrayDatos);

        DB::table('banco')->where('IdBanco', $cuentaBancaria)->update(['MontoActual' => $montoActual]);
    }
    public function completarStockNuevo($idArticulo, $costo, $precio, $cantidad)
    {
        $articulo = DB::table('stock')
            ->where('IdArticulo', $idArticulo)
            ->orderBy('IdStock', 'desc')
            ->get();

        if ($articulo[0]->Cantidad == 0) {
            DB::table('stock')
                ->where('IdStock', $articulo[0]->IdStock)
                ->update(['Costo' => $costo, 'Precio' => $precio, 'Cantidad' => $cantidad]);
        } else {
            if (count($articulo) == 1) {
                $_array = ['IdArticulo' => $idArticulo, 'Costo' => $costo, 'Precio' => $precio, 'Cantidad' => $cantidad];
                DB::table('stock')->insert($_array);
            } else {
                $total = $articulo[0]->Cantidad + $articulo[1]->Cantidad;
                DB::table('stock')
                    ->where('IdStock', $articulo[1]->IdStock)
                    ->update(['Costo' => $articulo[0]->Costo, 'Precio' => $articulo[0]->Precio, 'Cantidad' => $total]);

                DB::table('stock')
                    ->where('IdStock', $articulo[0]->IdStock)
                    ->update(['Costo' => $costo, 'Precio' => $precio, 'Cantidad' => $cantidad]);
            }
        }

    }

}
