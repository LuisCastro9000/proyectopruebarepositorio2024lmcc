<?php

namespace App\Http\Controllers\Vehicular\GestionTaller;

use App\Exports\ExcelReporteCheck;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use App\Traits\ArchivosS3Trait;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use PDF;
use Session;

class CheckInController extends Controller
{
    use ArchivosS3Trait;
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $permisos = $loadDatos->getPermisos($idUsuario);
            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);
            $modal = 0;
            $fecha = 5;
            $fechaIni = '';
            $fechaFin = '';
            // NUEVAS VARIABLES
            $ini = '';
            $fin = '';
            // FIN
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);

            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

            $fechas = $loadDatos->getFechaFiltro(5, null, null);
            $inventarios = DB::select('call sp_getInventarios(?, ?, ?)', array($idSucursal, $fechas[0], $fechas[1]));
            $inventarios = collect($inventarios)->unique('IdCheckIn');
            $cantInvertariosBaja = $inventarios->where('Estado', 'Baja')->count();
            $cantInvertariosAceptado = $inventarios->whereIn('Estado', [null, 'Aceptado'])->count();

            //$inventarios = $loadDatos->getInventarios( $idSucursal );
            $array = ['inventarios' => $inventarios, 'fecha' => $fecha, 'modal' => $modal, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'cantInvertariosBaja' => $cantInvertariosBaja, 'cantInvertariosAceptado' => $cantInvertariosAceptado];
            return view('vehicular/checkIn/listar', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    // public function mostarVistaEditarInventario(Request $req, $id)
    // {
    //     if ($req->session()->has('idUsuario')) {
    //         $idUsuario = Session::get('idUsuario');
    //         $loadDatos = new DatosController();
    //         $idSucursal = Session::get('idSucursal');
    //         $clientes = $this->getVehiculos($idSucursal);

    //         $permisos = $loadDatos->getPermisos($idUsuario);

    //         $subpermisos = $loadDatos->getSubPermisos($idUsuario);
    //         $subniveles = $loadDatos->getSubNiveles($idUsuario);

    //         $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
    //         $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
    //         $sucursal = $loadDatos->getSucursalSelect($idSucursal);
    //         $orden = $usuarioSelect->Orden;
    //         $ordenSucursal = $sucursal->Orden;

    //         // ----
    //         $datosInventario = $loadDatos->getInventarioSelect($id);
    //         if ($datosInventario->TipoVehicular == 1) {
    //             $tipoVehiculo = 'vehiculo';
    //         } else {
    //             $tipoVehiculo = 'moto';
    //         }
    //         $datosAccesoriosExterno = collect($loadDatos->accesoriosExternos($id))->keyBy('IdDescripcionCheckIn');
    //         $accesoriosExt = $loadDatos->getDescripcionCheckIn(1, $tipoVehiculo)->keyBy('IdDescripcionCheckIn');
    //         $accesoriosExt = $accesoriosExt->replace($datosAccesoriosExterno);
    //         Session::put('arrayIdsAccesoriosExternos', $datosAccesoriosExterno->pluck('IdDescripcionCheckIn'));

    //         $datosAccesoriosInternos = $loadDatos->accesoriosInternos($id)->keyBy('IdDescripcionCheckIn');
    //         $accesoriosInt = $loadDatos->getDescripcionCheckIn(2, $tipoVehiculo)->keyBy('IdDescripcionCheckIn');
    //         $accesoriosInt = $accesoriosInt->replace($datosAccesoriosInternos);
    //         Session::put('arrayIdsAccesoriosInternos', $datosAccesoriosInternos->pluck('IdDescripcionCheckIn'));

    //         $datosDeHerramientas = $loadDatos->herramientas($id)->keyBy('IdDescripcionCheckIn');
    //         $herramientas = $loadDatos->getDescripcionCheckIn(3, $tipoVehiculo)->keyBy('IdDescripcionCheckIn');
    //         $herramientas = $herramientas->replace($datosDeHerramientas);
    //         Session::put('arrayIdsHerramientas', $datosDeHerramientas->pluck('IdDescripcionCheckIn'));

    //         $datosDocumentosVehiculo = $loadDatos->documentosVehiculo($id)->keyBy('IdDescripcionCheckIn');
    //         $docVehiculos = $loadDatos->getDescripcionCheckIn(4, $tipoVehiculo)->keyBy('IdDescripcionCheckIn');
    //         $docVehiculos = $docVehiculos->replace($datosDocumentosVehiculo);
    //         Session::put('arrayIdsDocumentos', $datosDocumentosVehiculo->pluck('IdDescripcionCheckIn'));

    //         $datosAutorizacion = DB::table('autorizacion_checkin')
    //             ->where('IdCheckIn', $datosInventario->IdCheckIn)
    //             ->get();
    //         $autorizacionesUno = $datosAutorizacion->where('IdDescripcionAutorizacion', 1)->first();
    //         $autorizacionesDos = $datosAutorizacion->where('IdDescripcionAutorizacion', 2)->first();
    //         $autorizacionesTres = $datosAutorizacion->where('IdDescripcionAutorizacion', 3)->first();
    //         $autorizacionesCuatro = $datosAutorizacion->where('IdDescripcionAutorizacion', 4)->first();

    //         $array = ['clientes' => $clientes, 'accesoriosExt' => $accesoriosExt, 'accesoriosInt' => $accesoriosInt, 'herramientas' => $herramientas, 'docVehiculos' => $docVehiculos, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'datosInventario' => $datosInventario, 'datosAccesoriosExterno' => $datosAccesoriosExterno, 'autorizacionesUno' => $autorizacionesUno, 'autorizacionesDos' => $autorizacionesDos, 'autorizacionesTres' => $autorizacionesTres, 'autorizacionesCuatro' => $autorizacionesCuatro, 'tipoVehiculo' => $tipoVehiculo];
    //         return view('vehicular/checkIn/editarCheckList', $array);
    //     } else {
    //         Session::flush();
    //         return redirect('/')->with('out', 'Sesión de usuario Expirado');
    //     }
    // }
    public function mostarVistaEditarInventario(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $clientes = $this->getVehiculos($idSucursal);

            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $sucursal = $loadDatos->getSucursalSelect($idSucursal);
            $orden = $usuarioSelect->Orden;
            $ordenSucursal = $sucursal->Orden;

            // ----
            $datosInventario = $loadDatos->getInventarioSelect($id, $idSucursal);
            if ($datosInventario->TipoVehicular == 1) {
                $tipoVehiculo = 'Vehiculo';
            } else {
                $tipoVehiculo = 'Moto';
            }
            $datosCheckList = $this->getDatosCheckListSelect($loadDatos, $tipoVehiculo, $id);

            $datosAutorizacion = DB::table('autorizacion_checkin')
                ->where('IdCheckIn', $datosInventario->IdCheckIn)
                ->get();
            $autorizacionesUno = $datosAutorizacion->where('IdDescripcionAutorizacion', 1)->first();
            $autorizacionesDos = $datosAutorizacion->where('IdDescripcionAutorizacion', 2)->first();
            $autorizacionesTres = $datosAutorizacion->where('IdDescripcionAutorizacion', 3)->first();
            $autorizacionesCuatro = $datosAutorizacion->where('IdDescripcionAutorizacion', 4)->first();

            $array = ['clientes' => $clientes, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles,
                'accesoriosExt' => $datosCheckList->accesoriosExternos, 'accesoriosInt' => $datosCheckList->accesoriosInternos, 'herramientas' => $datosCheckList->herramientas, 'docVehiculos' => $datosCheckList->documentosVehiculo, 'datosInventario' => $datosInventario, 'autorizacionesUno' => $autorizacionesUno, 'autorizacionesDos' => $autorizacionesDos, 'autorizacionesTres' => $autorizacionesTres, 'autorizacionesCuatro' => $autorizacionesCuatro, 'tipoVehiculo' => $tipoVehiculo];
            return view('vehicular/checkIn/editarCheckList', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function updateCheckList(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $loadDatos = new DatosController();
            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
            $kilometraje = $req->kilometraje;
            $nivelGasolina = $req->radioNivelGasolina;
            $idInventario = $req->idCheckIn;

            $array = ['NivelGasolina' => $nivelGasolina, 'Observacion' => $req->observacion, 'Kilometraje' => $kilometraje];
            DB::table('check_in')
                ->where('check_in.IdSucursal', $idSucursal)
                ->where('check_in.IdCheckIn', $idInventario)
                ->Update($array);

            // codigo para traer las accesorios que se registraron anteriormente
            $datosCheckList = $this->getDatosCheckListSelect($loadDatos, $req->inputTipoVehiculo, $idInventario);
            $arrayIdsAccesoriosExternos = $datosCheckList->accesoriosExternos->where('Estado', '!=', 'E')->pluck('IdDescripcionCheckIn');
            $arrayIdsAccesoriosInternos = $datosCheckList->accesoriosInternos->where('Estado', '!=', 'E')->pluck('IdDescripcionCheckIn');
            $arrayIdsHerramientas = $datosCheckList->herramientas->where('Estado', '!=', 'E')->pluck('IdDescripcionCheckIn');
            $arrayIdsDocumentos = $datosCheckList->documentosVehiculo->where('Estado', '!=', 'E')->pluck('IdDescripcionCheckIn');
            // Fin
            $this->updateAutorizacionCheckIn($req, $idInventario);
            $this->updateAccesoriosExternos($req, $idInventario, $arrayIdsAccesoriosExternos);
            $this->updateAccesoriosInternos($req, $idInventario, $arrayIdsAccesoriosInternos);
            $this->updateHerramientas($req, $idInventario, $arrayIdsHerramientas);
            $this->updateDocumentosVehiculo($req, $idInventario, $arrayIdsDocumentos);

            return redirect('vehicular/CheckIn/documento-generado/' . $idInventario)->with('status', 'El inventario se actualizo correctamente');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    protected function updateAutorizacionCheckIn(Request $req, $idcheckin)
    {
        try {

            $arrayIdDescripcion = [];
            if ($req->chboxAuto1 == 'on') {
                array_push($arrayIdDescripcion, 1);
            }
            if ($req->chboxAuto2 == 'on') {
                array_push($arrayIdDescripcion, 2);
            }
            if ($req->chboxAuto3 == 'on') {
                array_push($arrayIdDescripcion, 3);
            }
            if ($req->chboxAuto4 == 'on') {
                array_push($arrayIdDescripcion, 4);
            }

            if (count($arrayIdDescripcion) >= 1) {
                $cantidad = count($arrayIdDescripcion);
                for ($i = 0; $i <= $cantidad - 1; $i++) {
                    $estado = 1;
                    $idDescripcionAuto = $arrayIdDescripcion[$i];
                    if ($arrayIdDescripcion[$i] == 4) {
                        $dias = $req->Dias;
                        $monto = $req->Monto;
                        $array = ['IdCheckIn' => $idcheckin, 'IdDescripcionAutorizacion' => $idDescripcionAuto, 'Dias' => $dias, 'Monto' => $monto, 'Estado' => $estado];
                    } else {
                        $array = ['IdCheckIn' => $idcheckin, 'IdDescripcionAutorizacion' => $idDescripcionAuto, 'Estado' => $estado];

                    }
                    $existenciaAutorizacion = $this->verificarAutorizacion($idDescripcionAuto, $idcheckin);
                    if ($existenciaAutorizacion) {
                        DB::table('autorizacion_checkin')->where('IdCheckIn', $idcheckin)->where('IdDescripcionAutorizacion', $idDescripcionAuto)->update($array);
                    } else {
                        DB::table('autorizacion_checkin')->insert($array);
                    }
                }
                DB::table('autorizacion_checkin')->where('IdCheckIn', $idcheckin)->whereNotIn('IdDescripcionAutorizacion', $arrayIdDescripcion)->delete();
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    protected function updateAccesoriosExternos(Request $req, $idcheckin, $datos)
    {
        try {
            // Actualizar accesorios externos
            $arrayIdsAccesoriosExternosAntiguos = $datos;
            if (collect($req->checkAccesoriosExternos)->isNotEmpty()) {
                foreach ($req->checkAccesoriosExternos as $item) {
                    $inputCantidad = $req->get('input' . $item);
                    $radioEstado = $req->get('radioOption' . $item);
                    if ($arrayIdsAccesoriosExternosAntiguos->search($item) !== false) {
                        DB::table('accesorios_externos')
                            ->where('IdCheckIn', $req->idCheckIn)
                            ->where('IdDescripcionCheckIn', $item)
                            ->update(['Cantidad' => $inputCantidad, 'Estado' => $radioEstado]);
                    } else {
                        DB::table('accesorios_externos')
                            ->insert(['IdCheckIn' => $req->idCheckIn, 'IdDescripcionCheckIn' => $item, 'Cantidad' => $inputCantidad, 'Estado' => $radioEstado]);
                    }
                }
                DB::table('accesorios_externos')->where('IdCheckIn', $req->idCheckIn)->whereNotIn('IdDescripcionCheckIn', $req->checkAccesoriosExternos)->delete();
            } else {
                DB::table('accesorios_externos')->where('IdCheckIn', $req->idCheckIn)->whereIn('IdDescripcionCheckIn', $arrayIdsAccesoriosExternosAntiguos)->delete();
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    protected function updateAccesoriosInternos(Request $req, $idcheckin, $datos)
    {
        try {
            // Actualizar accesoriosInternos
            $arrayIdsAccesoriosInternos = $datos;
            if (collect($req->checkAccesoriosInternos)->isNotEmpty()) {
                foreach ($req->checkAccesoriosInternos as $item) {
                    $inputCantidad = $req->get('input' . $item);
                    $radioEstado = $req->get('radioOption' . $item);
                    if ($arrayIdsAccesoriosInternos->search($item) !== false) {
                        DB::table('accesorios_internos')
                            ->where('IdCheckIn', $req->idCheckIn)
                            ->where('IdDescripcionCheckIn', $item)
                            ->update(['Cantidad' => $inputCantidad, 'Estado' => $radioEstado]);
                    } else {
                        DB::table('accesorios_internos')
                            ->insert(['IdCheckIn' => $req->idCheckIn, 'IdDescripcionCheckIn' => $item, 'Cantidad' => $inputCantidad, 'Estado' => $radioEstado]);
                    }
                }
                DB::table('accesorios_internos')->where('IdCheckIn', $req->idCheckIn)->whereNotIn('IdDescripcionCheckIn', $req->checkAccesoriosInternos)->delete();
            } else {
                DB::table('accesorios_internos')->where('IdCheckIn', $req->idCheckIn)->whereIn('IdDescripcionCheckIn', $arrayIdsAccesoriosInternos)->delete();
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    protected function updateHerramientas(Request $req, $idcheckin, $datos)
    {
        try {
            // Actualizar Herramientas
            $arrayIdsHerramientasAntiguos = $datos;
            if (collect($req->checkHerramientas)->isNotEmpty()) {
                foreach ($req->checkHerramientas as $item) {
                    $inputCantidad = $req->get('input' . $item);
                    $radioEstado = $req->get('radioOption' . $item);
                    if ($arrayIdsHerramientasAntiguos->search($item) !== false) {
                        DB::table('herramientas')
                            ->where('IdCheckIn', $req->idCheckIn)
                            ->where('IdDescripcionCheckIn', $item)
                            ->update(['Cantidad' => $inputCantidad, 'Estado' => $radioEstado]);
                    } else {
                        DB::table('herramientas')
                            ->insert(['IdCheckIn' => $req->idCheckIn, 'IdDescripcionCheckIn' => $item, 'Cantidad' => $inputCantidad, 'Estado' => $radioEstado]);
                    }
                }
                DB::table('herramientas')->where('IdCheckIn', $req->idCheckIn)->whereNotIn('IdDescripcionCheckIn', $req->checkHerramientas)->delete();
            } else {
                DB::table('herramientas')->where('IdCheckIn', $req->idCheckIn)->whereIn('IdDescripcionCheckIn', $arrayIdsHerramientasAntiguos)->delete();
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    protected function updateDocumentosVehiculo(Request $req, $idcheckin, $datos)
    {
        try {
            // Actualizar documentos vehiculos
            $arrayIdsDocumentos = $datos;
            if (collect($req->checkDocumentos)->isNotEmpty()) {
                foreach ($req->checkDocumentos as $item) {
                    $inputCantidad = $req->get('input' . $item);
                    $radioEstado = $req->get('radioOption' . $item);
                    if ($arrayIdsDocumentos->search($item) !== false) {
                        DB::table('documento_vehiculo')
                            ->where('IdCheckIn', $req->idCheckIn)
                            ->where('IdDescripcionCheckIn', $item)
                            ->update(['Cantidad' => $inputCantidad, 'Estado' => $radioEstado]);
                    } else {
                        DB::table('documento_vehiculo')
                            ->insert(['IdCheckIn' => $req->idCheckIn, 'IdDescripcionCheckIn' => $item, 'Cantidad' => $inputCantidad, 'Estado' => $radioEstado]);
                    }
                }
                DB::table('documento_vehiculo')->where('IdCheckIn', $req->idCheckIn)->whereNotIn('IdDescripcionCheckIn', $req->checkDocumentos)->delete();
            } else {
                DB::table('documento_vehiculo')->where('IdCheckIn', $req->idCheckIn)->whereIn('IdDescripcionCheckIn', $arrayIdsDocumentos)->delete();
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function verificarAutorizacion($idDescripcionAuto, $idcheckin)
    {
        $resultado = DB::table('autorizacion_checkin')
            ->where('IdDescripcionAutorizacion', $idDescripcionAuto)
            ->where('IdCheckIn', $idcheckin)
            ->first();
        return $resultado;
    }
    // Fin

    public function create(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $tipoVehiculo = 'vehiculo';

            //$clientes = $loadDatos->getClientes( $idSucursal );
            $clientes = $this->getVehiculos($idSucursal);

            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $sucursal = $loadDatos->getSucursalSelect($idSucursal);
            $orden = $usuarioSelect->Orden;
            $ordenSucursal = $sucursal->Orden;

            $correlativoSelect = DB::table('check_in')
                ->select('Correlativo')
                ->where('IdUsuario', $idUsuario)
                ->where('IdSucursal', $idSucursal)
                ->orderBy('IdCheckIn', 'desc')
                ->first();

            if ($correlativoSelect) {
                $correlativo = str_pad($correlativoSelect->Correlativo + 1, 8, '0', STR_PAD_LEFT);
            } else {
                $correlativo = str_pad(1, 8, '0', STR_PAD_LEFT);
            }

            $serieCeros = str_pad($orden, 2, '0', STR_PAD_LEFT);
            $serie = 'V' . $ordenSucursal . '' . $serieCeros;
            $accesoriosExt = $loadDatos->getDescripcionCheckIn(1, 'Vehiculo');
            $accesoriosInt = $loadDatos->getDescripcionCheckIn(2, 'Vehiculo');
            $herramientas = $loadDatos->getDescripcionCheckIn(3, 'Vehiculo');
            $docVehiculos = $loadDatos->getDescripcionCheckIn(4, 'Vehiculo');

            $array = ['clientes' => $clientes, 'serie' => $serie, 'correlativo' => $correlativo, 'accesoriosExt' => $accesoriosExt, 'accesoriosInt' => $accesoriosInt, 'herramientas' => $herramientas, 'docVehiculos' => $docVehiculos, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'tipoVehiculo' => $tipoVehiculo];
            return view('vehicular/checkIn/crear', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    private function existeCorrrelativo($idSucursal, $serie, $numero)
    {
        try {
            $resultado = DB::table('check_in')
                ->where('Serie', $serie)
                ->where('Correlativo', $numero)
                ->where('IdSucursal', $idSucursal)
                ->exists();

            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function obtenerUltimoCorrelativo($idSucursal, $idUsuario)
    {
        try {
            $resultado = DB::table('check_in')
                ->select(DB::raw('MAX(Correlativo) as Correlativo'))
                ->where('IdSucursal', $idSucursal)
                ->where('IdUsuario', $idUsuario)
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function store(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $loadDatos = new DatosController();
            if ($req->cliente > 0) {
                DB::beginTransaction();
                try {
                    $idUsuario = Session::get('idUsuario');
                    $idSucursal = Session::get('idSucursal');

                    $correlativo = $req->correlativo;
                    $serie = $req->serie;
                    if ($this->existeCorrrelativo($idSucursal, $serie, $correlativo)) {
                        $resultado = $this->obtenerUltimoCorrelativo($idSucursal, $idUsuario);
                        $correlativo = str_pad($resultado->Correlativo + 1, 8, '0', STR_PAD_LEFT);
                    }

                    $vehiculo = $loadDatos->getVehiculoSelect($req->cliente);
                    $idCliente = $vehiculo->IdCliente;
                    $placa = $vehiculo->PlacaVehiculo;
                    $fecha = $loadDatos->getDateTime();
                    $kilometraje = $req->kilometraje;
                    $nivelGasolina = $req->radioNivelGasolina;

                    $imagenFirma = $req->imagenCodigoFirma;
                    $imagenCarro = $req->imagenCodigoCarro;
                    if ($imagenCarro != null) {
                        $rucEmpresa = $loadDatos->getRucEmpresa($idUsuario)->Ruc;
                        $directorio = '/AnomaliasVehiculares/CheckList/';
                        $nombreImagen = "{$req->tipoVehiculo}-{$idCliente}-{$serie}-{$correlativo}";
                        $imagenCarro = $this->storeImagenFormatoBase64($imagenCarro, $imagenAnterior = null, $nombreImagen, $directorio, $rucEmpresa, $accion = 'store');
                    }
                    if ($imagenFirma != null) {
                        $rucEmpresa = $loadDatos->getRucEmpresa($idUsuario)->Ruc;
                        $directorio = '/FirmasDigitales/FirmasClientes-CheckList/';
                        $nombreImagen = "firma-{$idCliente}-{$serie}-{$correlativo}";
                        $imagenFirma = $this->storeImagenFormatoBase64($imagenFirma, $imagenAnterior = null, $nombreImagen, $directorio, $rucEmpresa, $accion = 'store');
                    }

                    $array = ['IdCliente' => $idCliente, 'Placa' => $placa, 'FechaEmision' => $fecha, 'IdUsuario' => $idUsuario, 'IdSucursal' => $idSucursal, 'Serie' => $serie, 'Correlativo' => $correlativo, 'Observacion' => $req->observacion, 'Kilometraje' => $kilometraje, 'NivelGasolina' => $nivelGasolina, 'Cotizado' => 0, 'Imagen' => $imagenFirma, 'ImagenCarro' => $imagenCarro];
                    $idCheckIn = DB::table('check_in')->insertGetId($array);

                    $this->storeAutorizacionCheckIn($req, $idCheckIn);
                    $this->storeAccesoriosExternos($req, $idCheckIn);
                    $this->storeAccesoriosInternos($req, $idCheckIn);
                    $this->storeHerramientas($req, $idCheckIn);
                    $this->storeDocumentosVehiculo($req, $idCheckIn);

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    $idMaximoCheckList = DB::table('check_in')->max('IdCheckIn');
                    DB::statement("ALTER TABLE check_in AUTO_INCREMENT=" . ($idMaximoCheckList + 1));

                    $idMaximoAccesorioExterno = DB::table('accesorios_externos')->max('IdAccesorioExterno');
                    DB::statement("ALTER TABLE accesorios_externos AUTO_INCREMENT=" . ($idMaximoAccesorioExterno + 1));

                    $idMaximoAccesorioInterno = DB::table('accesorios_internos')->max('IdAccesorioInterno');
                    DB::statement("ALTER TABLE accesorios_internos AUTO_INCREMENT=" . ($idMaximoAccesorioInterno + 1));

                    $idMaximoDocumento = DB::table('documento_vehiculo')->max('IdDocVehiculo');
                    DB::statement("ALTER TABLE documento_vehiculo AUTO_INCREMENT=" . ($idMaximoDocumento + 1));

                    $idMaximoHerramienta = DB::table('herramientas')->max('IdHerramientas');
                    DB::statement("ALTER TABLE herramientas AUTO_INCREMENT=" . ($idMaximoHerramienta + 1));

                    $idMaximoAutorizacion = DB::table('autorizacion_checkin')->max('IdAutorizacion');
                    DB::statement("ALTER TABLE autorizacion_checkin AUTO_INCREMENT=" . ($idMaximoAutorizacion + 1));

                    return back()->with('error', 'Ocurrio un error, por favor No INSISTA EN LA CREACIÓN DEL CHECKLIST, proceda a comunicarse con el Área de Soporte. ')->withInput();

                }
                return redirect('vehicular/CheckIn/documento-generado/' . $idCheckIn)->with('status', 'Se creo inventario correctamente');
            } else {
                return back()->with('error', 'Seleccionar Cliente')->withInput();
            }

        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function destroy(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            DB::table('check_in')->where('IdCheckIn', $id)->update(['Estado' => 'Baja']);
            return redirect()->route('checkList.index')->with('status', 'El inventario fue dado de baja correctamente');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    protected function storeAutorizacionCheckIn(Request $req, $idcheckin)
    {
        try {
            if (collect($req->checkAutorizaciones)->isNotEmpty()) {
                $datosAutorizaciones = collect($req->checkAutorizaciones)->map(function ($item) use ($req, $idcheckin) {
                    $inputDias = '';
                    $inputMonto = '';
                    if ($item == 4) {
                        $inputDias = $req->Dias;
                        $inputMonto = $req->Monto;
                    }
                    return (array) ['IdCheckIn' => $idcheckin, 'IdDescripcionAutorizacion' => $item, 'Dias' => $inputDias, 'Monto' => $inputMonto, 'Estado' => 1];
                });

                $datosAutorizaciones = $datosAutorizaciones->toArray();
                DB::table('autorizacion_checkin')->insert($datosAutorizaciones);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    protected function storeAccesoriosExternos(Request $req, $idcheckin)
    {
        try {
            if (collect($req->checkAccesoriosExternos)->isNotEmpty()) {
                $datosAccesoriosExternos = collect($req->checkAccesoriosExternos)->map(function ($item) use ($req, $idcheckin) {
                    $inputCantidad = $req->get('input' . $item);
                    $radioEstado = $req->get('radioOption' . $item);
                    return (array) ['IdCheckIn' => $idcheckin, 'IdDescripcionCheckIn' => $item, 'Cantidad' => $inputCantidad, 'Estado' => $radioEstado];
                });
                $datosAccesoriosExternos = $datosAccesoriosExternos->toArray();
                DB::table('accesorios_externos')->insert($datosAccesoriosExternos);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    protected function storeAccesoriosInternos(Request $req, $idcheckin)
    {
        try {
            if (collect($req->checkAccesoriosInternos)->isNotEmpty()) {
                $datosAccesoriosInternos = collect($req->checkAccesoriosInternos)->map(function ($item) use ($req, $idcheckin) {
                    $inputCantidad = $req->get('input' . $item);
                    $radioEstado = $req->get('radioOption' . $item);
                    return (array) ['IdCheckIn' => $idcheckin, 'IdDescripcionCheckIn' => $item, 'Cantidad' => $inputCantidad, 'Estado' => $radioEstado];
                });
                $datosAccesoriosInternos = $datosAccesoriosInternos->toArray();
                DB::table('accesorios_internos')->insert($datosAccesoriosInternos);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    protected function storeHerramientas(Request $req, $idcheckin)
    {
        try {
            if (collect($req->checkHerramientas)->isNotEmpty()) {
                $datosHerramientas = collect($req->checkHerramientas)->map(function ($item) use ($req, $idcheckin) {
                    $inputCantidad = $req->get('input' . $item);
                    $radioEstado = $req->get('radioOption' . $item);
                    return (array) ['IdCheckIn' => $idcheckin, 'IdDescripcionCheckIn' => $item, 'Cantidad' => $inputCantidad, 'Estado' => $radioEstado];
                });
                $datosHerramientas = $datosHerramientas->toArray();
                DB::table('herramientas')->insert($datosHerramientas);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    protected function storeDocumentosVehiculo(Request $req, $idcheckin)
    {
        try {
            if (collect($req->checkDocumentos)->isNotEmpty()) {
                $datosDocumentos = collect($req->checkDocumentos)->map(function ($item) use ($req, $idcheckin) {
                    $inputCantidad = $req->get('input' . $item);
                    $radioEstado = $req->get('radioOption' . $item);
                    return (array) ['IdCheckIn' => $idcheckin, 'IdDescripcionCheckIn' => $item, 'Cantidad' => $inputCantidad, 'Estado' => $radioEstado];
                });
                $datosDocumentos = $datosDocumentos->toArray();
                DB::table('documento_vehiculo')->insert($datosDocumentos);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function show(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                if ($req->ajax()) {

                    $idUsuario = Session::get('idUsuario');
                    $loadDatos = new DatosController();
                    $idSucursal = Session::get('idSucursal');
                    $idVehiculo = $req->IdVehiculo;

                    $dataVehicular = $loadDatos->getVehiculoSelect($idVehiculo);

                    return Response([$dataVehicular]);

                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function filtrar(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                $idUsuario = Session::get('idUsuario');

                $loadDatos = new DatosController();
                $idSucursal = Session::get('idSucursal');

                $permisos = $loadDatos->getPermisos($idUsuario);

                $subpermisos = $loadDatos->getSubPermisos($idUsuario);
                $subniveles = $loadDatos->getSubNiveles($idUsuario);

                $modal = 1;
                $fecha = $req->fecha;
                $fechaIni = $req->fechaIni;
                $fechaFin = $req->fechaFin;
                $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);

                $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

                $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
                $inventarios = DB::select('call sp_getInventarios(?, ?, ?)', array($idSucursal, $fechas[0], $fechas[1]));
                $inventarios = collect($inventarios)->unique('IdCheckIn');
                $cantInvertariosBaja = $inventarios->where('Estado', 'Baja')->count();
                $cantInvertariosAceptado = $inventarios->whereIn('Estado', [null, 'Aceptado'])->count();

                // NUEVO VARIABLES
                $ini = str_replace('/', '-', $fechaIni);
                $fin = str_replace('/', '-', $fechaFin);
                // FIN

                $array = ['inventarios' => $inventarios, 'fecha' => $fecha, 'modal' => $modal, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'cantInvertariosBaja' => $cantInvertariosBaja, 'cantInvertariosAceptado' => $cantInvertariosAceptado];
                return view('vehicular/checkIn/listar', $array);

            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function inventarioGenerado(Request $req, $id)
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

            $inventario = $loadDatos->getInventarioSelect($id, $idSucursal);
            $accesoriosExternos = $loadDatos->accesoriosExternos($id);
            $accesoriosInternos = $loadDatos->accesoriosInternos($id);
            $herramientas = $loadDatos->herramientas($id);
            $documentosVehiculo = $loadDatos->documentosVehiculo($id);

            $autorizaciones = [];
            for ($i = 1; $i <= 4; $i++) {
                $datos = $this->autorizacionesSelect($id, $i);
                array_push($autorizaciones, $datos);
            }
            // Nuevo codigo
            $numeroCelular = $inventario->Telefono;
            if ($numeroCelular != null) {
                if (str_starts_with($numeroCelular, 9) === true) {
                    $numeroCelular = $numeroCelular;
                } else {
                    $numeroCelular = '';
                }
            }
            $array = ['inventario' => $inventario, 'autorizaciones' => $autorizaciones, 'accesoriosExternos' => $accesoriosExternos, 'accesoriosInternos' => $accesoriosInternos, 'herramientas' => $herramientas, 'documentosVehiculo' => $documentosVehiculo, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'numeroCelular' => $numeroCelular];
            return view('vehicular/checkIn/documentoGenerado', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    // Nueva Funcion 2023/05/31
    public function generarPdf(Request $req, $id, $dato)
    {
        if ($req->session()->has('idUsuario')) {
            $loadDatos = new DatosController();
            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');

            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);

            $inventario = $loadDatos->getInventarioSelect($id, $idSucursal);
            if ($inventario->TipoVehicular == 1) {
                $tipoVehiculo = 'vehiculo';
            } else {
                $tipoVehiculo = 'moto';
            }
            $datosVehiculo = $this->getDatosVehiculo($inventario->Placa, $idSucursal);
            $fecha = date_create($inventario->FechaEmision);
            $formatoFecha = date_format($fecha, 'd-m-Y');
            $formatoHora = date_format($fecha, 'H:i A');

            $autorizaciones = [];
            for ($i = 1; $i <= 4; $i++) {
                $datos = $this->autorizacionesSelect($id, $i);
                array_push($autorizaciones, $datos);
            }
            $datosCheckListSelect = $this->getDatosCheckListSelect($loadDatos, $tipoVehiculo, $id);

            $sucursal = $loadDatos->getSucursales($usuarioSelect->CodigoCliente, $usuarioSelect->IdOperador)->where("Principal", 0)->first();
            $array = ['inventario' => $inventario, 'autorizaciones' => $autorizaciones, 'accesoriosExternos' => $datosCheckListSelect->accesoriosExternos, 'accesoriosInternos' => $datosCheckListSelect->accesoriosInternos, 'herramientas' => $datosCheckListSelect->herramientas, 'documentosVehiculo' => $datosCheckListSelect->documentosVehiculo, 'formatoFecha' => $formatoFecha, 'formatoHora' => $formatoHora, 'empresa' => $empresa, 'datosVehiculo' => $datosVehiculo, 'sucursal' => $sucursal, 'tipoVehiculo' => $tipoVehiculo];
            view()->share($array);
            $pdf = PDF::loadView('pdf/inventarioPdf')->setPaper('a4', 'portrait');

            if ($dato == 'imprimir') {
                return $pdf->stream('inventario-' . $inventario->Serie . '-' . $inventario->Correlativo . '.pdf');
            }
            if ($dato == 'descargar') {
                return $pdf->download('inventario-' . $inventario->Serie . '-' . $inventario->Correlativo . '.pdf');
            }
            if ($dato == 'whatsapp') {
                $fechaCreacionPdf = Carbon::now()->toDateTimeString();
                $nombrePdf = "$inventario->Serie-$inventario->Correlativo";
                $directorio = "/PdfWhatsApp/CheckList/";
                $urlPdf = $this->storePdfWhatsAppS3($pdf, $nombrePdf, $directorio, $empresa->Ruc);
                $array = ['UrlPdf' => $urlPdf, 'FechaCreacionPdf' => $fechaCreacionPdf];
                DB::table('check_in')
                    ->where('IdCheckIn', $id)
                    ->where('IdSucursal', $idSucursal)
                    ->update($array);
                $numeroCelular = $req->numeroCelular;
                // $mensajeUrl = '¡Hola%20Gracias%20por%20confiar%20tu%20vehículo%20en%20nuestro%20Taller!%20🥳%0A%0A☝️%20Te%20enviamos%20el%20CheckList%20(Inventario%20de%20tu%20vehículo)%20que%20ingresastes%20a%20nuestras%20instalaciones%20recientemente,%20podrás%20descargarlo%20en%20el%20link%20de%20la%20parte%20inferior, %20este%20enlace%20solo%20estará%20disponible%20por%2030%20días.%20📄%20🙌%0A%0A 📞%20Si%20tienes%20alguna%20duda%20o%20consulta,%20no%20dudes%20en%20comunicarte%20con%20nuestro%20Centro%20de%20Servicio%20al%20Cliente,%20con%20tus%20asesores%20de%20siempre%20que%20estarán%20gustos%20en%20atenderte.%0A%0A' . $urlPdf;

                $fechaInventario = carbon::parse($inventario->FechaEmision)->isoFormat('D [de] MMMM [de] YYYY');
                $mensajeUrl = "¡Hola gracias por confiar tu vehículo en nuestro Taller: *$empresa->NombreComercial* con RUC: *$empresa->Ruc*! 🥳%0A%0A ☝️Te enviamos el checkList (Inventario de tu vehículo), generada el dia: *$fechaInventario* de acuerdo a tu requerimiento, podrás descargarlo haciendo click en el link de la parte inferior, este enlace solo estará disponible por 30 días. 📄 🙌 %0A%0A 📞 Si tienes alguna duda o consulta, no dudes en comunicarte con nuestro Centro de Servicio al Cliente al teléfono: *$empresa->Telefono*, con tus asesores de siempre que estarán gustos en atenderte.%0A%0A" . config('variablesGlobales.urlDominioAmazonS3') . $urlPdf;

                if ($loadDatos->isMobileDevice()) {
                    return redirect()->away('https://api.whatsapp.com/send?phone=+51' . $numeroCelular . '&text=' . $mensajeUrl);
                } else {
                    return redirect()->away('https://web.whatsapp.com/send?phone=51' . $numeroCelular . '&text=' . $mensajeUrl);
                }
            }
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

    }
    private function getDatosCheckListSelect($loadDatos, $tipoVehiculo, $id)
    {
        $datosCheckListAccesoriosExterno = $loadDatos->accesoriosExternos($id)->keyBy('IdDescripcionCheckIn');
        $accesoriosExt = $loadDatos->getDescripcionCheckIn(1, $tipoVehiculo)->keyBy('IdDescripcionCheckIn');
        $accesoriosExternos = $accesoriosExt->replace($datosCheckListAccesoriosExterno);

        $datosCheckListAccesoriosInterno = $loadDatos->accesoriosInternos($id)->keyBy('IdDescripcionCheckIn');
        $accesoriosInter = $loadDatos->getDescripcionCheckIn(2, $tipoVehiculo)->keyBy('IdDescripcionCheckIn');
        $accesoriosInternos = $accesoriosInter->replace($datosCheckListAccesoriosInterno);

        $datosCheckListHerramientas = $loadDatos->herramientas($id)->keyBy('IdDescripcionCheckIn');
        $herramientas = $loadDatos->getDescripcionCheckIn(3, $tipoVehiculo)->keyBy('IdDescripcionCheckIn');
        $herramientas = $herramientas->replace($datosCheckListHerramientas);

        $datosCheckListDocumentosVehiculo = $loadDatos->documentosVehiculo($id)->keyBy('IdDescripcionCheckIn');
        $docVehiculos = $loadDatos->getDescripcionCheckIn(4, $tipoVehiculo)->keyBy('IdDescripcionCheckIn');
        $documentosVehiculo = $docVehiculos->replace($datosCheckListDocumentosVehiculo);

        return (object) array('accesoriosExternos' => $accesoriosExternos, 'accesoriosInternos' => $accesoriosInternos, 'herramientas' => $herramientas, 'documentosVehiculo' => $documentosVehiculo);
    }

    public function consultarDatosTipoVehiculo(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                if ($req->ajax()) {
                    $idUsuario = Session::get('idUsuario');
                    $loadDatos = new DatosController();
                    $idSucursal = Session::get('idSucursal');
                    $tipoVehiculo = $req->tipoVehiculo;

                    $accesoriosExt = $loadDatos->getDescripcionCheckIn(1, $tipoVehiculo);
                    $accesoriosInt = $loadDatos->getDescripcionCheckIn(2, $tipoVehiculo);
                    $herramientas = $loadDatos->getDescripcionCheckIn(3, $tipoVehiculo);
                    $docVehiculos = $loadDatos->getDescripcionCheckIn(4, $tipoVehiculo);

                    return view('vehicular.checkIn._tableDatosVehiculo', compact('tipoVehiculo', 'accesoriosExt', 'accesoriosInt', 'herramientas', 'docVehiculos'));
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    // Fin

    protected function autorizacionesSelect($idInventario, $idDescripcion)
    {
        try {
            $inventario = DB::table('autorizacion_checkin')
                ->where('IdCheckIn', $idInventario)
                ->where('IdDescripcionAutorizacion', $idDescripcion)
                ->first();
            return $inventario;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    protected function getVehiculos($idSucursal)
    {
        try {
            $vehiculos = DB::table('vehiculo')
                ->join('cliente', 'vehiculo.IdCliente', '=', 'cliente.IdCliente')
                ->select(DB::raw('concat(cliente.RazonSocial, " -  Placa : ", vehiculo.PlacaVehiculo) as RazonSocial'), 'vehiculo.IdVehiculo as IdCliente', 'vehiculo.TipoVehicular')
                ->where('vehiculo.IdSucursal', $idSucursal)
                ->where('vehiculo.Estado', 1)
                ->get();
            return $vehiculos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    protected function getDatosVehiculo($placa, $idSucursal)
    {
        $datos = DB::table('vehiculo')
            ->where('PlacaVehiculo', $placa)
            ->where('IdSucursal', $idSucursal)
            ->first();

        return $datos;
    }

    // NUEVA FUNCION

    public function exportarExcel($fecha = null, $ini = null, $fin = null)
    {
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');

        $fechaIni = str_replace('-', '/', $ini);
        $fechaFin = str_replace('-', '/', $fin);
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $reporteCheck = DB::select('call sp_getInventarios(?, ?, ?)', array($idSucursal, $fechas[0], $fechas[1]));
        $reporteCheck = collect($reporteCheck)->unique('IdCheckIn')->sortDesc();
        return Excel::download(new ExcelReporteCheck($reporteCheck), 'ReporteInventario.xlsx');
    }
    // FIN
}
