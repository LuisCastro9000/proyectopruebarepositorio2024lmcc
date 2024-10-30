<?php

namespace App\Http\Controllers\Operaciones\Vehiculares;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Session;
use DB;
use DateTime;
use PDF;
use Excel;
use Carbon\Carbon;
use App\Exports\ExcelReporteCheck;

class CheckInController extends Controller {
    public function index( Request $req ) {
        if ( $req->session()->has( 'idUsuario' ) ) {
            $idUsuario = Session::get( 'idUsuario' );

            $loadDatos = new DatosController();
            $idSucursal = Session::get( 'idSucursal' );

            $permisos = $loadDatos->getPermisos( $idUsuario );

            $subpermisos = $loadDatos->getSubPermisos( $idUsuario );
            $subniveles = $loadDatos->getSubNiveles( $idUsuario );

            $modal = 0;
            $fecha = 5;
            $fechaIni = '';
            $fechaFin = '';
            // NUEVAS VARIABLES
            $ini = '';
            $fin = '';
            // FIN
            $usuarioSelect = $loadDatos->getUsuarioSelect( $idUsuario );

            $modulosSelect = $loadDatos->getModulosSelect( $usuarioSelect->CodigoCliente );

            $fechas = $loadDatos->getFechaFiltro( 5, null, null );
            $inventarios = DB::select( 'call sp_getInventarios(?, ?, ?)', array( $idSucursal, $fechas[ 0 ], $fechas[ 1 ] ) );
            $inventarios = collect( $inventarios )->unique( 'IdCheckIn' );
            // dd( $inventarios );
            //$inventarios = $loadDatos->getInventarios( $idSucursal );
            $array = [ 'inventarios'=>$inventarios, 'fecha'=>$fecha, 'modal' => $modal, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin ];
            return view( 'operaciones/vehiculares/checkIn/listar', $array );
        } else {
            Session::flush();
            return redirect( '/' )->with( 'out', 'Sesión de usuario Expirado' );
        }
    }

    // Nueva funcion

    public function mostarVistaEditarInventario( Request $req, $id ) {
        if ( $req->session()->has( 'idUsuario' ) ) {
            $idUsuario = Session::get( 'idUsuario' );
            $loadDatos = new DatosController();
            $idSucursal = Session::get( 'idSucursal' );

            $clientes = $this->getVehiculos( $idSucursal );

            $permisos = $loadDatos->getPermisos( $idUsuario );

            $subpermisos = $loadDatos->getSubPermisos( $idUsuario );
            $subniveles = $loadDatos->getSubNiveles( $idUsuario );

            $usuarioSelect = $loadDatos->getUsuarioSelect( $idUsuario );
            $modulosSelect = $loadDatos->getModulosSelect( $usuarioSelect->CodigoCliente );
            $sucursal = $loadDatos->getSucursalSelect( $idSucursal );
            $orden = $usuarioSelect->Orden;
            $ordenSucursal = $sucursal->Orden;

            // ----
            $datosInventario = $loadDatos->getInventarioSelect( $id );
            $datosAccesoriosExterno = collect( $loadDatos->accesoriosExternos( $id ) )->keyBy( 'IdDescripcionCheckIn' );
            $accesoriosExt = $loadDatos->getDescripcionCheckIn( 1 )->keyBy( 'IdDescripcionCheckIn' );
            $accesoriosExt = $accesoriosExt ->replace( $datosAccesoriosExterno );

            $datosAccesoriosInternos = $loadDatos->accesoriosInternos( $id )->keyBy( 'IdDescripcionCheckIn' );
            $accesoriosInt = $loadDatos->getDescripcionCheckIn( 2 )->keyBy( 'IdDescripcionCheckIn' );
            $accesoriosInt = $accesoriosInt ->replace( $datosAccesoriosInternos );

            $datosDeHerramientas = $loadDatos->herramientas( $id )->keyBy( 'IdDescripcionCheckIn' );
            $herramientas = $loadDatos->getDescripcionCheckIn( 3 )->keyBy( 'IdDescripcionCheckIn' );
            $herramientas = $herramientas ->replace( $datosDeHerramientas );

            $datosDocumentosVehiculo = $loadDatos->documentosVehiculo( $id )->keyBy( 'IdDescripcionCheckIn' );
            $docVehiculos = $loadDatos->getDescripcionCheckIn( 4 )->keyBy( 'IdDescripcionCheckIn' );
            $docVehiculos = $docVehiculos ->replace( $datosDocumentosVehiculo );
            $datosAutorizacion = DB::table( 'autorizacion_checkin' )
            ->where( 'IdCheckIn', $datosInventario->IdCheckIn )
            ->get();
            $autorizacionesUno = $datosAutorizacion->where( 'IdDescripcionAutorizacion', 1 )->first();
            $autorizacionesDos = $datosAutorizacion->where( 'IdDescripcionAutorizacion', 2 )->first();
            $autorizacionesTres = $datosAutorizacion->where( 'IdDescripcionAutorizacion', 3 )->first();
            $autorizacionesCuatro = $datosAutorizacion->where( 'IdDescripcionAutorizacion', 4 )->first();

            $array = [ 'clientes'=>$clientes, 'accesoriosExt'=>$accesoriosExt, 'accesoriosInt'=>$accesoriosInt, 'herramientas'=>$herramientas, 'docVehiculos'=>$docVehiculos, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles, 'datosInventario' =>$datosInventario, 'datosAccesoriosExterno' =>$datosAccesoriosExterno, 'autorizacionesUno' =>$autorizacionesUno,  'autorizacionesDos' => $autorizacionesDos, 'autorizacionesTres' =>$autorizacionesTres, 'autorizacionesCuatro' => $autorizacionesCuatro ];
            return view( 'operaciones/vehiculares/checkIn/editarCheckList', $array );
        } else {
            Session::flush();
            return redirect( '/' )->with( 'out', 'Sesión de usuario Expirado' );
        }
    }

    public function updateCheckList( Request $req ) {
        if ( $req->session()->has( 'idUsuario' ) ) {
            $loadDatos = new DatosController();
            $idUsuario = Session::get( 'idUsuario' );
            $idSucursal = Session::get( 'idSucursal' );
            $kilometraje = $req->kilometraje;
            $nivelGasolina = $req->radioNivelGasolina;
            $idInventario = $req->idCheckIn;

            // $array = [ 'IdCliente'=>$idCliente, 'Placa'=>$placa, 'FechaEmision'=>$fecha, 'IdUsuario'=>$idUsuario, 'IdSucursal'=>$idSucursal, 'Serie'=>$req->serie, 'Correlativo'=>$req->correlativo, 'Observacion'=>$req->observacion, 'Kilometraje' => $kilometraje, 'NivelGasolina' => $nivelGasolina, 'Cotizado' => 0 ];
            $array = [ 'NivelGasolina' => $nivelGasolina, 'Observacion'=>$req->observacion, 'Kilometraje' => $kilometraje ];
            DB::table( 'check_in' )
            ->where('check_in.IdSucursal', $idSucursal)
            ->where('check_in.IdCheckIn', $idInventario)
            ->Update( $array );

            // $checkIn = DB::table( 'check_in' )
            // ->where( 'IdUsuario', $idUsuario )
            // ->orderBy( 'IdCheckIn', 'desc' )
            // ->first();
            // dd($req->idCheckIn);
            $this->updateAutorizacionCheckIn( $req, $idInventario );
            $this->updateAccesoriosExternos( $req, $idInventario );
            $this->updateAccesoriosInternos( $req, $idInventario );
            $this->updateHerramientas( $req, $idInventario );
            $this->updateDocumentosVehiculo( $req, $idInventario );

            // $this->updateAutorizacionCheckIn( $req, $checkIn->IdCheckIn );
            // $this->updateAccesoriosExternos( $req, $checkIn->IdCheckIn );
            // $this->updateAccesoriosInternos( $req, $checkIn->IdCheckIn );
            // $this->updateHerramientas( $req, $checkIn->IdCheckIn );
            // $this->updateDocumentosVehiculo( $req, $checkIn->IdCheckIn );

            return redirect( 'operaciones/vehiculares/documento-generado/'.$idInventario )->with( 'status', 'El inventario se actualizo correctamente' );
        } else {
            Session::flush();
            return redirect( '/' )->with( 'out', 'Sesión de usuario Expirado' );
        }
    }

    protected function updateAutorizacionCheckIn( Request $req, $idcheckin ) {
        try {

            $arrayIdDescripcion = [];
            if ( $req->chboxAuto1 == 'on' ) {
                array_push( $arrayIdDescripcion, 1 );
            }
            if ( $req->chboxAuto2 == 'on' ) {
                array_push( $arrayIdDescripcion, 2 );
            }
            if ( $req->chboxAuto3 == 'on' ) {
                array_push( $arrayIdDescripcion, 3 );
            }
            if ( $req->chboxAuto4 == 'on' ) {
                array_push( $arrayIdDescripcion, 4 );
            }

            if ( count( $arrayIdDescripcion ) >= 1 ) {
                $cantidad = count( $arrayIdDescripcion );
                for ( $i = 0; $i <= $cantidad-1; $i++ ) {
                    $estado = 1;
                    $idDescripcionAuto = $arrayIdDescripcion[ $i ];
                    if ( $arrayIdDescripcion[ $i ]  == 4 ) {
                        $dias = $req->Dias;
                        $monto = $req->Monto;
                        $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionAutorizacion' =>$idDescripcionAuto, 'Dias' => $dias, 'Monto' => $monto, 'Estado'=>$estado ];
                    } else {
                        $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionAutorizacion' =>$idDescripcionAuto, 'Estado'=>$estado ];

                    }
                    $existenciaAutorizacion = $this->verificarAutorizacion( $idDescripcionAuto, $idcheckin );
                    if ( $existenciaAutorizacion ) {
                        DB::table( 'autorizacion_checkin' )->where( 'IdCheckIn', $idcheckin ) ->where( 'IdDescripcionAutorizacion', $idDescripcionAuto )->update( $array );
                    } else {
                        DB::table( 'autorizacion_checkin' )->insert( $array );
                    }
                }
                DB::table( 'autorizacion_checkin' )->where( 'IdCheckIn', $idcheckin )->whereNotIn( 'IdDescripcionAutorizacion', $arrayIdDescripcion )->delete();
            }
        } catch( Exception $ex ) {
            echo $ex->getMessage();
        }
    }

    protected function updateAccesoriosExternos( Request $req, $idcheckin ) {
        try {
            $arrayIdDescripcion = [];
            $arrayEstado = [];
            $arrayCantidad = [];
            if ( $req->chbox1 == 'on' ) {
                array_push( $arrayIdDescripcion, 1 );
                array_push( $arrayEstado, $req->radioOption1 );
                array_push( $arrayCantidad, $req->input1 );
            }
            if ( $req->chbox2 == 'on' ) {
                array_push( $arrayIdDescripcion, 2 );
                array_push( $arrayEstado, $req->radioOption2 );
                array_push( $arrayCantidad, $req->input2 );
            }
            if ( $req->chbox3 == 'on' ) {
                array_push( $arrayIdDescripcion, 3 );
                array_push( $arrayEstado, $req->radioOption3 );
                array_push( $arrayCantidad, $req->input3 );
            }
            if ( $req->chbox4 == 'on' ) {
                array_push( $arrayIdDescripcion, 4 );
                array_push( $arrayEstado, $req->radioOption4 );
                array_push( $arrayCantidad, $req->input4 );
            }
            if ( $req->chbox5 == 'on' ) {
                array_push( $arrayIdDescripcion, 5 );
                array_push( $arrayEstado, $req->radioOption5 );
                array_push( $arrayCantidad, $req->input5 );
            }
            if ( $req->chbox6 == 'on' ) {
                array_push( $arrayIdDescripcion, 6 );
                array_push( $arrayEstado, $req->radioOption6 );
                array_push( $arrayCantidad, $req->input6 );
            }
            if ( $req->chbox7 == 'on' ) {
                array_push( $arrayIdDescripcion, 7 );
                array_push( $arrayEstado, $req->radioOption7 );
                array_push( $arrayCantidad, $req->input7 );
            }
            if ( $req->chbox8 == 'on' ) {
                array_push( $arrayIdDescripcion, 8 );
                array_push( $arrayEstado, $req->radioOption8 );
                array_push( $arrayCantidad, $req->input8 );
            }
            if ( $req->chbox9 == 'on' ) {
                array_push( $arrayIdDescripcion, 9 );
                array_push( $arrayEstado, $req->radioOption9 );
                array_push( $arrayCantidad, $req->input9 );
            }
            if ( $req->chbox10 == 'on' ) {
                array_push( $arrayIdDescripcion, 10 );
                array_push( $arrayEstado, $req->radioOption10 );
                array_push( $arrayCantidad, $req->input10 );
            }
            if ( $req->chbox11 == 'on' ) {
                array_push( $arrayIdDescripcion, 11 );
                array_push( $arrayEstado, $req->radioOption11 );
                array_push( $arrayCantidad, $req->input11 );
            }
            if ( $req->chbox12 == 'on' ) {
                array_push( $arrayIdDescripcion, 12 );
                array_push( $arrayEstado, $req->radioOption12 );
                array_push( $arrayCantidad, $req->input12 );
            }
            if ( $req->chbox13 == 'on' ) {
                array_push( $arrayIdDescripcion, 13 );
                array_push( $arrayEstado, $req->radioOption13 );
                array_push( $arrayCantidad, $req->input13 );
            }
            if ( $req->chbox14 == 'on' ) {
                array_push( $arrayIdDescripcion, 14 );
                array_push( $arrayEstado, $req->radioOption14 );
                array_push( $arrayCantidad, $req->input14 );
            }
            $cantidad = count( $arrayIdDescripcion );
            if ( count( $arrayIdDescripcion ) >= 1 ) {
                $cantidad = count( $arrayIdDescripcion );
                for ( $i = 0; $i <= $cantidad-1; $i++ ) {
                    $idDescripcionCheckIn = $arrayIdDescripcion[ $i ];
                    $cantidadAccesorios = $arrayCantidad[ $i ];
                    $estado = $arrayEstado[ $i ];
                    $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidadAccesorios, 'Estado'=>$estado ];
                    $existenciaAccesoriosExternos = $this->verificarAccesoriosExternos( $idDescripcionCheckIn, $idcheckin );
                    if ( $existenciaAccesoriosExternos ) {
                        DB::table( 'accesorios_externos' )->where( 'IdCheckIn', $idcheckin ) ->where( 'IdDescripcionCheckIn', $idDescripcionCheckIn )->update( $array );
                    } else {
                        DB::table( 'accesorios_externos' )->insert( $array );
                    }
                }
                // dd( $arrayIdDescripcion );
                DB::table( 'accesorios_externos' )->where( 'IdCheckIn', $idcheckin )->whereNotIn( 'IdDescripcionCheckIn', $arrayIdDescripcion )->delete();
            }
        } catch( Exception $ex ) {
            echo $ex->getMessage();
        }
    }

    protected function updateAccesoriosInternos( Request $req, $idcheckin ) {
        try {
            $arrayIdDescripcion = [];
            $arrayEstado = [];
            $arrayCantidad = [];

            if ( $req->chbox15 == 'on' ) {
                array_push( $arrayIdDescripcion, 15 );
                array_push( $arrayEstado, $req->radioOption15 );
                array_push( $arrayCantidad, $req->input15 );
            }
            if ( $req->chbox16 == 'on' ) {
                array_push( $arrayIdDescripcion, 16 );
                array_push( $arrayEstado, $req->radioOption16 );
                array_push( $arrayCantidad, $req->input16 );
            }
            if ( $req->chbox17 == 'on' ) {
                array_push( $arrayIdDescripcion, 17 );
                array_push( $arrayEstado, $req->radioOption17 );
                array_push( $arrayCantidad, $req->input17 );
            }
            if ( $req->chbox18 == 'on' ) {
                array_push( $arrayIdDescripcion, 18 );
                array_push( $arrayEstado, $req->radioOption18 );
                array_push( $arrayCantidad, $req->input18 );
            }
            if ( $req->chbox19 == 'on' ) {
                array_push( $arrayIdDescripcion, 19 );
                array_push( $arrayEstado, $req->radioOption19 );
                array_push( $arrayCantidad, $req->input19 );
            }
            if ( $req->chbox20 == 'on' ) {
                array_push( $arrayIdDescripcion, 20 );
                array_push( $arrayEstado, $req->radioOption20 );
                array_push( $arrayCantidad, $req->input20 );
            }
            if ( $req->chbox21 == 'on' ) {
                array_push( $arrayIdDescripcion, 21 );
                array_push( $arrayEstado, $req->radioOption21 );
                array_push( $arrayCantidad, $req->input21 );
            }

            $cantidad = count( $arrayIdDescripcion );
            if ( count( $arrayIdDescripcion ) >= 1 ) {
                $cantidad = count( $arrayIdDescripcion );
                for ( $i = 0; $i <= $cantidad-1; $i++ ) {
                    $idDescripcionCheckIn = $arrayIdDescripcion[ $i ];
                    $cantidadAccesorios = $arrayCantidad[ $i ];
                    $estado = $arrayEstado[ $i ];
                    $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidadAccesorios, 'Estado'=>$estado ];
                    $existenciaAccesoriosInternos = $this->verificarAccesoriosInternos( $idDescripcionCheckIn, $idcheckin );
                    if ( $existenciaAccesoriosInternos ) {
                        DB::table( 'accesorios_internos' )->where( 'IdCheckIn', $idcheckin ) ->where( 'IdDescripcionCheckIn', $idDescripcionCheckIn )->update( $array );
                    } else {
                        DB::table( 'accesorios_internos' )->insert( $array );
                    }
                }
                DB::table( 'accesorios_internos' )->where( 'IdCheckIn', $idcheckin )->whereNotIn( 'IdDescripcionCheckIn', $arrayIdDescripcion )->delete();
            }
        } catch( Exception $ex ) {
            echo $ex->getMessage();
        }
    }

    protected function updateHerramientas( Request $req, $idcheckin ) {
        try {
            $arrayIdDescripcion = [];
            $arrayEstado = [];
            $arrayCantidad = [];

            if ( $req->chbox22 == 'on' ) {
                array_push( $arrayIdDescripcion, 22 );
                array_push( $arrayEstado, $req->radioOption22 );
                array_push( $arrayCantidad, $req->input22 );
            }
            if ( $req->chbox23 == 'on' ) {
                array_push( $arrayIdDescripcion, 23 );
                array_push( $arrayEstado, $req->radioOption23 );
                array_push( $arrayCantidad, $req->input23 );
            }
            if ( $req->chbox24 == 'on' ) {
                array_push( $arrayIdDescripcion, 24 );
                array_push( $arrayEstado, $req->radioOption24 );
                array_push( $arrayCantidad, $req->input24 );
            }

            $cantidad = count( $arrayIdDescripcion );
            if ( count( $arrayIdDescripcion ) >= 1 ) {
                $cantidad = count( $arrayIdDescripcion );
                for ( $i = 0; $i <= $cantidad-1; $i++ ) {
                    $idDescripcionCheckIn = $arrayIdDescripcion[ $i ];
                    $cantidadHerramientas = $arrayCantidad[ $i ];
                    $estado = $arrayEstado[ $i ];
                    $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidadHerramientas, 'Estado'=>$estado ];
                    $existenciaAccesoriosInternos = $this->verificarHerramientas( $idDescripcionCheckIn, $idcheckin );
                    if ( $existenciaAccesoriosInternos ) {
                        DB::table( 'herramientas' )->where( 'IdCheckIn', $idcheckin ) ->where( 'IdDescripcionCheckIn', $idDescripcionCheckIn )->update( $array );
                    } else {
                        DB::table( 'herramientas' )->insert( $array );
                    }
                }
                DB::table( 'herramientas' )->where( 'IdCheckIn', $idcheckin )->whereNotIn( 'IdDescripcionCheckIn', $arrayIdDescripcion )->delete();
            }
        } catch ( Exception $ex ) {
            echo $ex->getMessage();
        }
    }

    protected function updateDocumentosVehiculo( Request $req, $idcheckin ) {
        try {
            $arrayIdDescripcion = [];
            $arrayEstado = [];
            $arrayCantidad = [];

            if ( $req->chbox25 == 'on' ) {
                array_push( $arrayIdDescripcion, 25 );
                array_push( $arrayEstado, $req->radioOption25 );
                array_push( $arrayCantidad, $req->input25 );
            }
            if ( $req->chbox26 == 'on' ) {
                array_push( $arrayIdDescripcion, 26 );
                array_push( $arrayEstado, $req->radioOption26 );
                array_push( $arrayCantidad, $req->input26 );
            }
            if ( $req->chbox27 == 'on' ) {
                array_push( $arrayIdDescripcion, 27 );
                array_push( $arrayEstado, $req->radioOption27 );
                array_push( $arrayCantidad, $req->input27 );
            }
            if ( $req->chbox28 == 'on' ) {
                array_push( $arrayIdDescripcion, 28 );
                array_push( $arrayEstado, $req->radioOption28 );
                array_push( $arrayCantidad, $req->input28 );
            }

            $cantidad = count( $arrayIdDescripcion );
            if ( count( $arrayIdDescripcion ) >= 1 ) {
                $cantidad = count( $arrayIdDescripcion );
                for ( $i = 0; $i <= $cantidad-1; $i++ ) {
                    $idDescripcionCheckIn = $arrayIdDescripcion[ $i ];
                    $cantidadDocumentos = $arrayCantidad[ $i ];
                    $estado = $arrayEstado[ $i ];
                    $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidadDocumentos, 'Estado'=>$estado ];
                    $existenciaAccesoriosInternos = $this->verificarDocumentos( $idDescripcionCheckIn, $idcheckin );
                    if ( $existenciaAccesoriosInternos ) {
                        DB::table( 'documento_vehiculo' )->where( 'IdCheckIn', $idcheckin ) ->where( 'IdDescripcionCheckIn', $idDescripcionCheckIn )->update( $array );
                    } else {
                        DB::table( 'documento_vehiculo' )->insert( $array );
                    }
                }
                DB::table( 'documento_vehiculo' )->where( 'IdCheckIn', $idcheckin )->whereNotIn( 'IdDescripcionCheckIn', $arrayIdDescripcion )->delete();
            }
        } catch ( Exception $ex ) {
            echo $ex->getMessage();
        }
    }

    private function verificarAutorizacion( $idDescripcionAuto, $idcheckin ) {
        $resultado = DB::table( 'autorizacion_checkin' )
        ->where( 'IdDescripcionAutorizacion', $idDescripcionAuto )
        ->where( 'IdCheckIn', $idcheckin )
        ->first();
        return $resultado;
    }

    private function verificarAccesoriosExternos( $idDescripcionCheckIn, $idcheckin ) {
        $resultado = DB::table( 'accesorios_externos' )
        ->where( 'IdDescripcionCheckIn', $idDescripcionCheckIn )
        ->where( 'IdCheckIn', $idcheckin )
        ->first();
        return $resultado;
    }

    private function verificarAccesoriosInternos( $idDescripcionCheckIn, $idcheckin ) {
        $resultado = DB::table( 'accesorios_internos' )
        ->where( 'IdDescripcionCheckIn', $idDescripcionCheckIn )
        ->where( 'IdCheckIn', $idcheckin )
        ->first();
        return $resultado;
    }

    private function verificarHerramientas( $idDescripcionCheckIn, $idcheckin ) {
        $resultado = DB::table( 'herramientas' )
        ->where( 'IdDescripcionCheckIn', $idDescripcionCheckIn )
        ->where( 'IdCheckIn', $idcheckin )
        ->first();
        return $resultado;
    }

    private function verificarDocumentos( $idDescripcionCheckIn, $idcheckin ) {
        $resultado = DB::table( 'documento_vehiculo' )
        ->where( 'IdDescripcionCheckIn', $idDescripcionCheckIn )
        ->where( 'IdCheckIn', $idcheckin )
        ->first();
        return $resultado;
    }
    // Fin

    public function create( Request $req ) {
        if ( $req->session()->has( 'idUsuario' ) ) {
            $idUsuario = Session::get( 'idUsuario' );
            $loadDatos = new DatosController();
            $idSucursal = Session::get( 'idSucursal' );

            //$clientes = $loadDatos->getClientes( $idSucursal );
            $clientes = $this->getVehiculos( $idSucursal );

            $permisos = $loadDatos->getPermisos( $idUsuario );

            $subpermisos = $loadDatos->getSubPermisos( $idUsuario );
            $subniveles = $loadDatos->getSubNiveles( $idUsuario );

            $usuarioSelect = $loadDatos->getUsuarioSelect( $idUsuario );
            $modulosSelect = $loadDatos->getModulosSelect( $usuarioSelect->CodigoCliente );
            $sucursal = $loadDatos->getSucursalSelect( $idSucursal );
            $orden = $usuarioSelect->Orden;
            $ordenSucursal = $sucursal->Orden;

            $correlativoSelect = DB::table( 'check_in' )
            ->select( 'Correlativo' )
            ->where( 'IdUsuario', $idUsuario )
            ->where( 'IdSucursal', $idSucursal )
            ->orderBy( 'IdCheckIn', 'desc' )
            ->first();

            if ( $correlativoSelect ) {
                $correlativo = str_pad( $correlativoSelect->Correlativo+1, 8, '0', STR_PAD_LEFT );
            } else {
                $correlativo = str_pad( 1, 8, '0', STR_PAD_LEFT );
            }

            $serieCeros = str_pad( $orden, 2, '0', STR_PAD_LEFT );
            $serie = 'V'.$ordenSucursal.''.$serieCeros;
            $accesoriosExt = $loadDatos->getDescripcionCheckIn( 1 );
            $accesoriosInt = $loadDatos->getDescripcionCheckIn( 2 );
            $herramientas = $loadDatos->getDescripcionCheckIn( 3 );
            $docVehiculos = $loadDatos->getDescripcionCheckIn( 4 );

            $array = [ 'clientes'=>$clientes, 'serie' => $serie, 'correlativo' => $correlativo, 'accesoriosExt'=>$accesoriosExt, 'accesoriosInt'=>$accesoriosInt, 'herramientas'=>$herramientas, 'docVehiculos'=>$docVehiculos, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles ];
            return view( 'operaciones/vehiculares/checkIn/crear', $array );
        } else {
            Session::flush();
            return redirect( '/' )->with( 'out', 'Sesión de usuario Expirado' );
        }
    }

    public function store( Request $req ) {
        if ( $req->session()->has( 'idUsuario' ) ) {
            $loadDatos = new DatosController();
            if ( $req->cliente > 0 ) {
                $vehiculo = $loadDatos->getVehiculoSelect( $req->cliente );
                $idCliente = $vehiculo->IdCliente;
                $placa = $vehiculo->PlacaVehiculo;
                $fecha = $loadDatos->getDateTime();
                $idUsuario = Session::get( 'idUsuario' );
                $idSucursal = Session::get( 'idSucursal' );
                $kilometraje = $req->kilometraje;
                $nivelGasolina = $req->radioNivelGasolina;

                // dd( $req->imagenAuto );
                // $_data_uri = $req->imagenAuto;
                // $_encoded_image = explode( ',', $_data_uri )[ 1 ];
                // $_decoded_image = base64_decode( $_encoded_image );
                // $_imagenPng = explode( '/', explode( ':', substr( $_data_uri, 0, strpos( $_data_uri, ';' ) ) )[ 1 ] )[ 1 ];
                // $imagenCarro = $loadDatos->setImageCheckList( $_decoded_image, $_imagenPng );

                if ( $req->imagenCodigoCarro != null ) {
                    $_data_uri = $req->imagenCodigoCarro;
                    $_encoded_image = explode( ',', $_data_uri )[ 1 ];
                    $_decoded_image = base64_decode( $_encoded_image );
                    $_imagenPng = explode( '/', explode( ':', substr( $_data_uri, 0, strpos( $_data_uri, ';' ) ) )[ 1 ] )[ 1 ];
                    $imagenCarro = $loadDatos->setImageCheckListCarro( $_decoded_image, $_imagenPng );
                } else {
                    $imagenCarro = null;
                }

                // dd( $imagenCarro );
                // $data_uri = $req->imagen;
                // $encoded_image = explode( ',', $data_uri )[ 1 ];
                // $decoded_image = base64_decode( $encoded_image );
                // $imagenPng = explode( '/', explode( ':', substr( $data_uri, 0, strpos( $data_uri, ';' ) ) )[ 1 ] )[ 1 ];
                // $imagen = $loadDatos->setImageCheckList( $decoded_image, $imagenPng );

                // dd( $req->imagenCodigoFirma );
                if ( $req->imagenCodigoFirma != null ) {
                    $data_uri = $req->imagenCodigoFirma;
                    $encoded_image = explode( ',', $data_uri )[ 1 ];
                    $decoded_image = base64_decode( $encoded_image );
                    $imagenPng = explode( '/', explode( ':', substr( $data_uri, 0, strpos( $data_uri, ';' ) ) )[ 1 ] )[ 1 ];
                    $imagenFirma = $loadDatos->setImageCheckListFirma( $decoded_image, $imagenPng );
                } else {
                    $imagenFirma = null;
                }

                $array = [ 'IdCliente'=>$idCliente, 'Placa'=>$placa, 'FechaEmision'=>$fecha, 'IdUsuario'=>$idUsuario, 'IdSucursal'=>$idSucursal, 'Serie'=>$req->serie, 'Correlativo'=>$req->correlativo, 'Observacion'=>$req->observacion, 'Kilometraje' => $kilometraje, 'NivelGasolina' => $nivelGasolina, 'Cotizado' => 0,  'Imagen'=>$imagenFirma, 'ImagenCarro'=> $imagenCarro ];
                DB::table( 'check_in' )->insert( $array );

                $checkIn = DB::table( 'check_in' )
                ->where( 'IdUsuario', $idUsuario )
                ->orderBy( 'IdCheckIn', 'desc' )
                ->first();

                $this->autorizacionCheckIn( $req, $checkIn->IdCheckIn );

                $this->accesoriosExternos( $req, $checkIn->IdCheckIn );
                $this->accesoriosInternos( $req, $checkIn->IdCheckIn );
                $this->herramientas( $req, $checkIn->IdCheckIn );
                $this->documentosVehiculo( $req, $checkIn->IdCheckIn );

                return redirect( 'operaciones/vehiculares/documento-generado/'.$checkIn->IdCheckIn )->with( 'status', 'Se creo inventario correctamente' );
            } else {
                return back()->with( 'error', 'Seleccionar Cliente' )->withInput();
            }

        } else {
            Session::flush();
            return redirect( '/' )->with( 'out', 'Sesión de usuario Expirado' );
        }
    }

    public function show( Request $req ) {
        try {
            if ( $req->session()->has( 'idUsuario' ) ) {
                if ( $req->ajax() ) {

                    $idUsuario = Session::get( 'idUsuario' );
                    $loadDatos = new DatosController();
                    $idSucursal = Session::get( 'idSucursal' );
                    $idVehiculo = $req->IdVehiculo;

                    $dataVehicular = $loadDatos->getVehiculoSelect( $idVehiculo );

                    return Response( [ $dataVehicular ] );

                }
            }
        } catch ( Exception $ex ) {
            echo $ex->getMessage();
        }
    }

    public function filtrar( Request $req ) {
        try {
            if ( $req->session()->has( 'idUsuario' ) ) {
                $idUsuario = Session::get( 'idUsuario' );

                $loadDatos = new DatosController();
                $idSucursal = Session::get( 'idSucursal' );

                $permisos = $loadDatos->getPermisos( $idUsuario );

                $subpermisos = $loadDatos->getSubPermisos( $idUsuario );
                $subniveles = $loadDatos->getSubNiveles( $idUsuario );

                $modal = 1;
                $fecha = $req->fecha;
                $fechaIni = $req->fechaIni;
                $fechaFin = $req->fechaFin;
                $usuarioSelect = $loadDatos->getUsuarioSelect( $idUsuario );

                $modulosSelect = $loadDatos->getModulosSelect( $usuarioSelect->CodigoCliente );

                $fechas = $loadDatos->getFechaFiltro( $fecha, $fechaIni, $fechaFin );
                $inventarios = DB::select( 'call sp_getInventarios(?, ?, ?)', array( $idSucursal, $fechas[ 0 ], $fechas[ 1 ] ) );
                $inventarios = collect( $inventarios )->unique( 'IdCheckIn' );
                // NUEVO VARIABLES
                $ini = str_replace( '/', '-', $fechaIni );
                $fin = str_replace( '/', '-', $fechaFin );
                // FIN

                $array = [ 'inventarios'=>$inventarios, 'fecha'=>$fecha, 'modal' => $modal, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' =>$ini, 'fin' =>$fin ];
                return view( 'operaciones/vehiculares/checkIn/listar', $array );

            }
        } catch ( Exception $ex ) {
            echo $ex->getMessage();
        }
    }

    public function inventarioGenerado( Request $req, $id ) {
        if ( $req->session()->has( 'idUsuario' ) ) {
            $loadDatos = new DatosController();
            $idUsuario = Session::get( 'idUsuario' );
            $idSucursal = Session::get( 'idSucursal' );
            $idIconoWhatsapp = $id;
            if ( strpos( $id, 'W-' ) === 0 ) {
                $id = substr( $id, 2 );
            }
            $permisos = $loadDatos->getPermisos( $idUsuario );

            $subpermisos = $loadDatos->getSubPermisos( $idUsuario );
            $subniveles = $loadDatos->getSubNiveles( $idUsuario );

            $usuarioSelect = $loadDatos->getUsuarioSelect( $idUsuario );
            $modulosSelect = $loadDatos->getModulosSelect( $usuarioSelect->CodigoCliente );

            $inventario = $loadDatos->getInventarioSelect( $id );
            $accesoriosExternos = $loadDatos->accesoriosExternos( $id );
            $accesoriosInternos = $loadDatos->accesoriosInternos( $id );
            $herramientas = $loadDatos->herramientas( $id );
            $documentosVehiculo = $loadDatos->documentosVehiculo( $id );

            $autorizaciones = [];
            for ( $i = 1; $i <= 4; $i++ ) {
                $datos = $this->autorizacionesSelect( $id, $i );
                array_push( $autorizaciones, $datos );
            }

            $array = [ 'inventario' => $inventario, 'autorizaciones' => $autorizaciones, 'accesoriosExternos' => $accesoriosExternos, 'accesoriosInternos' => $accesoriosInternos, 'herramientas' => $herramientas, 'documentosVehiculo' => $documentosVehiculo, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles, 'idIconoWhatsapp' =>$idIconoWhatsapp ];
            return view( 'operaciones/vehiculares/checkIn/documentoGenerado', $array );
        } else {
            Session::flush();
            return redirect( '/' )->with( 'out', 'Sesión de usuario Expirado' );
        }
    }

    public function imprimirInventario( Request $req, $id ) {
        if ( $req->session()->has( 'idUsuario' ) ) {
            $loadDatos = new DatosController();
            $idUsuario = Session::get( 'idUsuario' );

            $usuarioSelect = $loadDatos->getUsuarioSelect( $idUsuario );
            $empresa = $loadDatos->getDatosEmpresa( $usuarioSelect->CodigoCliente );

            $inventario = $loadDatos->getInventarioSelect( $id );
            // dd( $inventario );
            $datosVehiculo = $this->getDatosVehiculo( $inventario->Placa );
            $fecha = date_create( $inventario->FechaEmision );
            $formatoFecha = date_format( $fecha, 'd-m-Y' );
            $formatoHora = date_format( $fecha, 'H:i A' );

            $autorizaciones = [];
            $accesoriosExternos = [];
            $accesoriosInternos = [];
            $herramientas = [];
            $documentosVehiculo = [];

            for ( $i = 1; $i <= 4; $i++ ) {
                $datos = $this->autorizacionesSelect( $id, $i );
                array_push( $autorizaciones, $datos );
            }
            // dd( $autorizaciones );
            for ( $i = 1; $i <= 14; $i++ ) {
                $datos1 = $this->accesoriosExternosSelect( $id, $i );
                array_push( $accesoriosExternos, $datos1 );
            }
            for ( $i = 15; $i <= 21; $i++ ) {
                $datos2 = $this->accesoriosInternosSelect( $id, $i );
                array_push( $accesoriosInternos, $datos2 );
            }
            for ( $i = 22; $i <= 24; $i++ ) {
                $datos3 = $this->herramientasSelect( $id, $i );
                array_push( $herramientas, $datos3 );
            }
            for ( $i = 25; $i <= 28; $i++ ) {
                $datos4 = $this->documentosVehiculoSelect( $id, $i );
                array_push( $documentosVehiculo, $datos4 );
            }
            $sucursal = $loadDatos->getSucursales($usuarioSelect->CodigoCliente, $usuarioSelect->IdOperador)->where("Principal", 0)->first();
            $array = [ 'inventario' => $inventario, 'autorizaciones' => $autorizaciones, 'accesoriosExternos' => $accesoriosExternos, 'accesoriosInternos' => $accesoriosInternos, 'herramientas' => $herramientas, 'documentosVehiculo' => $documentosVehiculo,
            'formatoFecha' => $formatoFecha, 'formatoHora' => $formatoHora, 'empresa' => $empresa, 'datosVehiculo' => $datosVehiculo ,'sucursal' => $sucursal];
            view()->share( $array );
            $pdf = PDF::loadView( 'pdf/inventarioPdf' )->setPaper( 'a4', 'portrait' );
            return $pdf->stream( 'inventario-'.$inventario->Serie.'-'.$inventario->Correlativo.'.pdf' );
        } else {
            Session::flush();
            return redirect( '/' )->with( 'out', 'Sesión de usuario Expirado' );
        }
    }

    public function storePdfForWhatsapp( Request $req ) {
        if ( $req->session()->has( 'idUsuario' ) ) {
            $loadDatos = new DatosController();
            $idUsuario = Session::get( 'idUsuario' );
            $idSucursal = Session::get( 'idSucursal' );
            $numeroCelular = $req->numeroCelular;
            $id = $req->idCheck;

            $usuarioSelect = $loadDatos->getUsuarioSelect( $idUsuario );
            $empresa = $loadDatos->getDatosEmpresa( $usuarioSelect->CodigoCliente );
            $inventario = $loadDatos->getInventarioSelect( $id );
            $datosVehiculo = $this->getDatosVehiculo( $inventario->Placa );
            $fecha = date_create( $inventario->FechaEmision );
            $formatoFecha = date_format( $fecha, 'd-m-Y' );
            $formatoHora = date_format( $fecha, 'H:i A' );

            $autorizaciones = [];
            $accesoriosExternos = [];
            $accesoriosInternos = [];
            $herramientas = [];
            $documentosVehiculo = [];

            for ( $i = 1; $i <= 4; $i++ ) {
                $datos = $this->autorizacionesSelect( $id, $i );
                array_push( $autorizaciones, $datos );
            }
            for ( $i = 1; $i <= 14; $i++ ) {
                $datos1 = $this->accesoriosExternosSelect( $id, $i );
                array_push( $accesoriosExternos, $datos1 );
            }
            for ( $i = 15; $i <= 21; $i++ ) {
                $datos2 = $this->accesoriosInternosSelect( $id, $i );
                array_push( $accesoriosInternos, $datos2 );
            }
            for ( $i = 22; $i <= 24; $i++ ) {
                $datos3 = $this->herramientasSelect( $id, $i );
                array_push( $herramientas, $datos3 );
            }
            for ( $i = 25; $i <= 28; $i++ ) {
                $datos4 = $this->documentosVehiculoSelect( $id, $i );
                array_push( $documentosVehiculo, $datos4 );
            }

            $array = [ 'inventario' => $inventario, 'autorizaciones' => $autorizaciones, 'accesoriosExternos' => $accesoriosExternos, 'accesoriosInternos' => $accesoriosInternos, 'herramientas' => $herramientas, 'documentosVehiculo' => $documentosVehiculo,
            'formatoFecha' => $formatoFecha, 'formatoHora' => $formatoHora, 'empresa' => $empresa, 'datosVehiculo' => $datosVehiculo ];
            view()->share( $array );
            $pdf = PDF::loadView( 'pdf/inventarioPdf' )->setPaper( 'a4', 'portrait' );
            // $pdf = PDF::loadView( 'pdf/cotizacionPdf' )->setPaper( 'a4', 'portrait' );
            if ( $inventario->UrlPdf == Null ) {
                $fechaCreacionPdf = Carbon::now()->toDateTimeString();
                $url = $loadDatos->storePdfWhatsAppCheckList( $pdf, $inventario->Serie, $inventario->Correlativo );
                $array = [ 'UrlPdf' =>$url ,'FechaCreacionPdf' => $fechaCreacionPdf];
                DB::table( 'check_in' )
                ->where( 'IdCheckIn', $id )
                ->where('IdSucursal' , $idSucursal)
                ->update( $array );
                $inventario = $loadDatos->getInventarioSelect( $id );
            }
            $mensajeUrl = '¡Hola%20Gracias%20por%20confiar%20tu%20vehículo%20en%20nuestro%20Taller!%20🥳%0A%0A☝️%20Te%20enviamos%20el%20CheckList%20(Inventario%20de%20tu%20vehículo)%20que%20ingresastes%20a%20nuestras%20instalaciones%20recientemente,%20podrás%20descargarlo%20en%20el%20link%20de%20la%20parte%20inferior, %20este%20enlace%20solo%20estará%20disponible%20por%2030%20días.%20📄%20🙌%0A%0A 📞%20Si%20tienes%20alguna%20duda%20o%20consulta,%20no%20dudes%20en%20comunicarte%20con%20nuestro%20Centro%20de%20Servicio%20al%20Cliente,%20con%20tus%20asesores%20de%20siempre%20que%20estarán%20gustos%20en%20atenderte.%0A%0A'.$inventario->UrlPdf;

            // return redirect( 'https://web.whatsapp.com/send?phone=51' .$numeroCelular. '&text='.$mensajeUrl );
            // return $pdf->download( 'inventario-'.$inventario->Serie.'-'.$inventario->Correlativo.'.pdf' );
            
            // return redirect()->away( 'https://web.whatsapp.com/send?phone=51' .$numeroCelular. '&text='.$mensajeUrl );
            if($this->isMobileDevice()){
            return redirect()->away('https://api.whatsapp.com/send?phone=+51'.$numeroCelular. '&text='.$mensajeUrl);
            } else {
                return redirect()->away('https://web.whatsapp.com/send?phone=51' .$numeroCelular. '&text='.$mensajeUrl);
            }
        } else {
            Session::flush();
            return redirect( '/' )->with( 'out', 'Sesión de usuario Expirado' );
        }
    }

    private function isMobileDevice() {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }

    public function descargarInventario( Request $req, $id ) {
        if ( $req->session()->has( 'idUsuario' ) ) {
            $loadDatos = new DatosController();
            $idUsuario = Session::get( 'idUsuario' );

            $usuarioSelect = $loadDatos->getUsuarioSelect( $idUsuario );
            $empresa = $loadDatos->getDatosEmpresa( $usuarioSelect->CodigoCliente );

            $inventario = $loadDatos->getInventarioSelect( $id );
            $datosVehiculo = $this->getDatosVehiculo( $inventario->Placa );
            $fecha = date_create( $inventario->FechaEmision );
            $formatoFecha = date_format( $fecha, 'd-m-Y' );
            $formatoHora = date_format( $fecha, 'H:i A' );

            $autorizaciones = [];
            $accesoriosExternos = [];
            $accesoriosInternos = [];
            $herramientas = [];
            $documentosVehiculo = [];

            for ( $i = 1; $i <= 4; $i++ ) {
                $datos = $this->autorizacionesSelect( $id, $i );
                array_push( $autorizaciones, $datos );
            }
            for ( $i = 1; $i <= 14; $i++ ) {
                $datos1 = $this->accesoriosExternosSelect( $id, $i );
                array_push( $accesoriosExternos, $datos1 );
            }
            for ( $i = 15; $i <= 21; $i++ ) {
                $datos2 = $this->accesoriosInternosSelect( $id, $i );
                array_push( $accesoriosInternos, $datos2 );
            }
            for ( $i = 22; $i <= 24; $i++ ) {
                $datos3 = $this->herramientasSelect( $id, $i );
                array_push( $herramientas, $datos3 );
            }
            for ( $i = 25; $i <= 28; $i++ ) {
                $datos4 = $this->documentosVehiculoSelect( $id, $i );
                array_push( $documentosVehiculo, $datos4 );
            }

            $array = [ 'inventario' => $inventario, 'autorizaciones' => $autorizaciones, 'accesoriosExternos' => $accesoriosExternos, 'accesoriosInternos' => $accesoriosInternos, 'herramientas' => $herramientas, 'documentosVehiculo' => $documentosVehiculo,
            'formatoFecha' => $formatoFecha, 'formatoHora' => $formatoHora, 'empresa' => $empresa, 'datosVehiculo' => $datosVehiculo ];
            view()->share( $array );
            $pdf = PDF::loadView( 'pdf/inventarioPdf' )->setPaper( 'a4', 'portrait' );
            return $pdf->download( 'inventario-'.$inventario->Serie.'-'.$inventario->Correlativo.'.pdf' );
        } else {
            Session::flush();
            return redirect( '/' )->with( 'out', 'Sesión de usuario Expirado' );
        }
    }

    protected function autorizacionesSelect( $idInventario, $idDescripcion ) {
        try {
            $inventario = DB::table( 'autorizacion_checkin' )
            ->where( 'IdCheckIn', $idInventario )
            ->where( 'IdDescripcionAutorizacion', $idDescripcion )
            ->first();
            return $inventario;
        } catch ( Exception $ex ) {
            echo $ex->getMessage();
        }
    }

    protected function accesoriosExternosSelect( $idInventario, $idDescripcion ) {
        try {
            $inventario = DB::table( 'accesorios_externos' )
            ->join( 'descripcion_checkin', 'accesorios_externos.IdDescripcionCheckIn', '=', 'descripcion_checkin.IdDescripcionCheckIn' )
            ->select( 'accesorios_externos.*', 'descripcion_checkin.Descripcion' )
            ->where( 'IdCheckIn', $idInventario )
            ->where( 'descripcion_checkin.IdDescripcionCheckIn', $idDescripcion )
            ->first();
            return $inventario;
        } catch ( Exception $ex ) {
            echo $ex->getMessage();
        }
    }

    protected function accesoriosInternosSelect( $idInventario, $idDescripcion ) {
        try {
            $inventario = DB::table( 'accesorios_internos' )
            ->join( 'descripcion_checkin', 'accesorios_internos.IdDescripcionCheckIn', '=', 'descripcion_checkin.IdDescripcionCheckIn' )
            ->select( 'accesorios_internos.*', 'descripcion_checkin.Descripcion' )
            ->where( 'IdCheckIn', $idInventario )
            ->where( 'descripcion_checkin.IdDescripcionCheckIn', $idDescripcion )
            ->first();
            return $inventario;
        } catch ( Exception $ex ) {
            echo $ex->getMessage();
        }
    }

    protected function herramientasSelect( $idInventario, $idDescripcion ) {
        try {
            $inventario = DB::table( 'herramientas' )
            ->join( 'descripcion_checkin', 'herramientas.IdDescripcionCheckIn', '=', 'descripcion_checkin.IdDescripcionCheckIn' )
            ->select( 'herramientas.*', 'descripcion_checkin.Descripcion' )
            ->where( 'IdCheckIn', $idInventario )
            ->where( 'descripcion_checkin.IdDescripcionCheckIn', $idDescripcion )
            ->first();
            return $inventario;
        } catch ( Exception $ex ) {
            echo $ex->getMessage();
        }
    }

    protected function documentosVehiculoSelect( $idInventario, $idDescripcion ) {
        try {
            $inventario = DB::table( 'documento_vehiculo' )
            ->join( 'descripcion_checkin', 'documento_vehiculo.IdDescripcionCheckIn', '=', 'descripcion_checkin.IdDescripcionCheckIn' )
            ->select( 'documento_vehiculo.*', 'descripcion_checkin.Descripcion' )
            ->where( 'IdCheckIn', $idInventario )
            ->where( 'descripcion_checkin.IdDescripcionCheckIn', $idDescripcion )
            ->first();
            return $inventario;
        } catch ( Exception $ex ) {
            echo $ex->getMessage();
        }
    }

    protected function autorizacionCheckIn( Request $req, $idcheckin ) {
        try {
            if ( $req->chboxAuto1 ) {
                $idDescripcionAuto = 1;
                $estado = 1;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionAutorizacion' =>$idDescripcionAuto, 'Estado'=>$estado ];
                DB::table( 'autorizacion_checkin' )->insert( $array );
            }
            if ( $req->chboxAuto2 ) {
                $idDescripcionAuto = 2;
                $estado = 1;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionAutorizacion' =>$idDescripcionAuto, 'Estado'=>$estado ];
                DB::table( 'autorizacion_checkin' )->insert( $array );
            }
            if ( $req->chboxAuto3 ) {
                $idDescripcionAuto = 3;
                $estado = 1;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionAutorizacion' =>$idDescripcionAuto, 'Estado'=>$estado ];
                DB::table( 'autorizacion_checkin' )->insert( $array );
            }
            if ( $req->chboxAuto4 ) {
                $idDescripcionAuto = 4;
                $estado = 1;
                $dias = $req->Dias;
                $monto = $req->Monto;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionAutorizacion' =>$idDescripcionAuto, 'Dias' => $dias, 'Monto' => $monto, 'Estado'=>$estado ];
                DB::table( 'autorizacion_checkin' )->insert( $array );
            }
        } catch( Exception $ex ) {
            echo $ex->getMessage();
        }
    }

    protected function accesoriosExternos( Request $req, $idcheckin ) {
        try {
            if ( $req->chbox1 ) {
                $idDescripcionCheckIn = 1;
                $cantidad = $req->input1;
                $estado = $req->radioOption1;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'accesorios_externos' )->insert( $array );
            }
            if ( $req->chbox2 ) {
                $idDescripcionCheckIn = 2;
                $cantidad = $req->input2;
                $estado = $req->radioOption2;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'accesorios_externos' )->insert( $array );
            }
            if ( $req->chbox3 ) {
                $idDescripcionCheckIn = 3;
                $cantidad = $req->input3;
                $estado = $req->radioOption3;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'accesorios_externos' )->insert( $array );
            }
            if ( $req->chbox4 ) {
                $idDescripcionCheckIn = 4;
                $cantidad = $req->input4;
                $estado = $req->radioOption4;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'accesorios_externos' )->insert( $array );
            }
            if ( $req->chbox5 ) {
                $idDescripcionCheckIn = 5;
                $cantidad = $req->input5;
                $estado = $req->radioOption5;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'accesorios_externos' )->insert( $array );
            }
            if ( $req->chbox6 ) {
                $idDescripcionCheckIn = 6;
                $cantidad = $req->input6;
                $estado = $req->radioOption6;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'accesorios_externos' )->insert( $array );
            }
            if ( $req->chbox7 ) {
                $idDescripcionCheckIn = 7;
                $cantidad = $req->input7;
                $estado = $req->radioOption7;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'accesorios_externos' )->insert( $array );
            }
            if ( $req->chbox8 ) {
                $idDescripcionCheckIn = 8;
                $cantidad = $req->input8;
                $estado = $req->radioOption8;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'accesorios_externos' )->insert( $array );
            }
            if ( $req->chbox9 ) {
                $idDescripcionCheckIn = 9;
                $cantidad = $req->input9;
                $estado = $req->radioOption9;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'accesorios_externos' )->insert( $array );
            }
            if ( $req->chbox10 ) {
                $idDescripcionCheckIn = 10;
                $cantidad = $req->input10;
                $estado = $req->radioOption10;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'accesorios_externos' )->insert( $array );
            }
            if ( $req->chbox11 ) {
                $idDescripcionCheckIn = 11;
                $cantidad = $req->input11;
                $estado = $req->radioOption11;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'accesorios_externos' )->insert( $array );
            }
            if ( $req->chbox12 ) {
                $idDescripcionCheckIn = 12;
                $cantidad = $req->input12;
                $estado = $req->radioOption12;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'accesorios_externos' )->insert( $array );
            }
            if ( $req->chbox13 ) {
                $idDescripcionCheckIn = 13;
                $cantidad = $req->input13;
                $estado = $req->radioOption13;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'accesorios_externos' )->insert( $array );
            }
            if ( $req->chbox14 ) {
                $idDescripcionCheckIn = 14;
                $cantidad = $req->input14;
                $estado = $req->radioOption14;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'accesorios_externos' )->insert( $array );
            }
        } catch( Exception $ex ) {
            echo $ex->getMessage();
        }
    }

    protected function accesoriosInternos( Request $req, $idcheckin ) {
        try {
            if ( $req->chbox15 ) {
                $idDescripcionCheckIn = 15;
                $cantidad = $req->input15;
                $estado = $req->radioOption15;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'accesorios_internos' )->insert( $array );
            }
            if ( $req->chbox16 ) {
                $idDescripcionCheckIn = 16;
                $cantidad = $req->input16;
                $estado = $req->radioOption16;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'accesorios_internos' )->insert( $array );
            }
            if ( $req->chbox17 ) {
                $idDescripcionCheckIn = 17;
                $cantidad = $req->input17;
                $estado = $req->radioOption17;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'accesorios_internos' )->insert( $array );
            }
            if ( $req->chbox18 ) {
                $idDescripcionCheckIn = 18;
                $cantidad = $req->input18;
                $estado = $req->radioOption18;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'accesorios_internos' )->insert( $array );
            }
            if ( $req->chbox19 ) {
                $idDescripcionCheckIn = 19;
                $cantidad = $req->input19;
                $estado = $req->radioOption19;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'accesorios_internos' )->insert( $array );
            }
            if ( $req->chbox20 ) {
                $idDescripcionCheckIn = 20;
                $cantidad = $req->input20;
                $estado = $req->radioOption20;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'accesorios_internos' )->insert( $array );
            }
            if ( $req->chbox21 ) {
                $idDescripcionCheckIn = 21;
                $cantidad = $req->input21;
                $estado = $req->radioOption21;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'accesorios_internos' )->insert( $array );
            }
        } catch( Exception $ex ) {
            echo $ex->getMessage();
        }
    }

    protected function herramientas( Request $req, $idcheckin ) {
        try {
            if ( $req->chbox22 ) {
                $idDescripcionCheckIn = 22;
                $cantidad = $req->input22;
                $estado = $req->radioOption22;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'herramientas' )->insert( $array );
            }
            if ( $req->chbox23 ) {
                $idDescripcionCheckIn = 23;
                $cantidad = $req->input23;
                $estado = $req->radioOption23;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'herramientas' )->insert( $array );
            }
            if ( $req->chbox24 ) {
                $idDescripcionCheckIn = 24;
                $cantidad = $req->input24;
                $estado = $req->radioOption24;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'herramientas' )->insert( $array );
            }
        } catch ( Exception $ex ) {
            echo $ex->getMessage();
        }
    }

    protected function documentosVehiculo( Request $req, $idcheckin ) {
        try {
            if ( $req->chbox25 ) {
                $idDescripcionCheckIn = 25;
                $cantidad = $req->input25;
                $estado = $req->radioOption25;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'documento_vehiculo' )->insert( $array );
            }
            if ( $req->chbox26 ) {
                $idDescripcionCheckIn = 26;
                $cantidad = $req->input26;
                $estado = $req->radioOption26;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'documento_vehiculo' )->insert( $array );
            }
            if ( $req->chbox27 ) {
                $idDescripcionCheckIn = 27;
                $cantidad = $req->input27;
                $estado = $req->radioOption27;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'documento_vehiculo' )->insert( $array );
            }
            if ( $req->chbox28 ) {
                $idDescripcionCheckIn = 28;
                $cantidad = $req->input28;
                $estado = $req->radioOption28;
                $array = [ 'IdCheckIn'=>$idcheckin, 'IdDescripcionCheckIn' =>$idDescripcionCheckIn, 'Cantidad'=>$cantidad, 'Estado'=>$estado ];
                DB::table( 'documento_vehiculo' )->insert( $array );
            }
        } catch ( Exception $ex ) {
            echo $ex->getMessage();
        }
    }

    protected function getVehiculos( $idSucursal ) {
        try {
            $vehiculos = DB::table( 'vehiculo' )
            ->join( 'cliente', 'vehiculo.IdCliente', '=', 'cliente.IdCliente' )
            ->select( DB::raw( 'concat(cliente.RazonSocial, " -  Placa : ", vehiculo.PlacaVehiculo) as RazonSocial' ), 'vehiculo.IdVehiculo as IdCliente' )
            ->where( 'vehiculo.IdSucursal', $idSucursal )
            ->where( 'vehiculo.Estado', 1 )
            ->get();
            return $vehiculos;
        } catch ( Exception $ex ) {
            echo $ex->getMessage();
        }
    }

    protected function getDatosVehiculo( $placa ) {
        $datos = DB::table( 'vehiculo' )
        ->where( 'PlacaVehiculo', $placa )
        ->first();

        return $datos;
    }

    // NUEVA FUNCION

    public function exportarExcel( $fecha = null, $ini = null, $fin = null ) {
        $loadDatos = new DatosController();
        $idSucursal = Session::get( 'idSucursal' );

        $fechaIni = str_replace( '-', '/', $ini );
        $fechaFin = str_replace( '-', '/', $fin );
        $fechas = $loadDatos->getFechaFiltro( $fecha, $fechaIni, $fechaFin );
        $reporteCheck = DB::select( 'call sp_getInventarios(?, ?, ?)', array( $idSucursal, $fechas[ 0 ], $fechas[ 1 ] ) );
        // dd( $reporteCheck );
        return Excel::download( new ExcelReporteCheck( $reporteCheck ), 'ReporteInventario.xlsx' );
    }
    // FIN

    public function comprobarPermiso( Request $req ) {
        if ( $req->ajax() ) {
            $idUsuario = Session::get( 'idUsuario' );

            $idSucursal = Session::get( 'idSucursal' );
            $loadDatos = new DatosController();
            $password = $req->password;

            $usuarioCodigo = $loadDatos->getUsuarioSelect( $idUsuario );
            $codigoCliente = $usuarioCodigo->CodigoCliente;
            $respuesta = DB::table( 'usuario' )
            ->select( 'usuario.ClaveDeComprobacion', 'usuario.Nombre', 'usuario.IdSucursal' )
            ->where( 'usuario.CodigoCliente', $codigoCliente )
            ->where( 'usuario.Cliente', 1 )
            ->get();

            if ( count( $respuesta )>0 ) {
                if ( ( password_verify( $password, $respuesta[ 0 ]->ClaveDeComprobacion ) ) ) {
                    $password = ( password_verify( $password, $respuesta[ 0 ]->ClaveDeComprobacion ) );
                    return Response( [ 'Success', 'La clave si coincide' ] );
                }
            }
        }
    }

}
