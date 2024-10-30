<?php

namespace App\Http\Controllers\Administracion\Banco;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use App\Traits\CuentasBancariasTrait;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Session;

class BancosController extends Controller
{
    use CuentasBancariasTrait;
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();

            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $cuentasCorrientes = $loadDatos->getCuentasCorrientes($usuarioSelect->CodigoCliente, null);
            //
            $planSuscripcionContratado = $this->getPlanSuscripcion($usuarioSelect->CodigoCliente);
            //
            $array = ['cuentasCorrientes' => $cuentasCorrientes, 'permisos' => $permisos, 'usuarioSelect' => $usuarioSelect, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'planSuscripcionContratado' => $planSuscripcionContratado];
            return view('administracion/bancos/cuentaBancaria/bancos', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    private function getPlanSuscripcion($codigoCliente)
    {
        $planSuscripcion = DB::table('empresa')
            ->join('planes_suscripcion as ps', 'empresa.IdPlanSuscripcion', '=', 'ps.IdPlanSuscripcion')
            ->select('ps.Nombre as NombrePlan')
            ->where('CodigoCliente', $codigoCliente)->value('NombrePlan');
        return $planSuscripcion;
    }

    public function create(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        // -----
        // Este id se obtiene desde la vista operaciones/crearVenta
        $idCuentraDetracciones = $req->idCuentraDetracciones;
        // ----
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $permisos = $loadDatos->getAllPermisos();

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $listaBancos = $loadDatos->getListaBancos();
        $tipoMonedas = $loadDatos->getTipoMoneda();
        // Nuevo codigo
        $tiposCuentasBancarias = DB::table('tipos_cuentas_bancarias')->get();
        // Fin
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $planSuscripcionContratado = $this->getPlanSuscripcion($usuarioSelect->CodigoCliente);
        $array = ['listaBancos' => $listaBancos, 'tipoMonedas' => $tipoMonedas, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'tiposCuentasBancarias' => $tiposCuentasBancarias, 'idCuentraDetracciones' => $idCuentraDetracciones, 'planSuscripcionContratado' => $planSuscripcionContratado];
        return view('administracion/bancos/cuentaBancaria/crearBanco', $array);
    }

    public function store(Request $req)
    {
        try {
            $loadDatos = new DatosController();
            $idUsuario = Session::get('idUsuario');
            $this->validateBanco($req);
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $idBanco = $req->banco;
            $nroCuenta = $req->cuenta;
            $cci = $req->cci;
            $tipoMoneda = $req->tipoMoneda;
            $inicial = $req->inicial;
            $actual = $req->inicial;
            $tipoCuenta = $req->tipoCuenta;
            $array = ['CodigoCliente' => $usuarioSelect->CodigoCliente, 'IdUsuario' => $idUsuario, 'IdListaBanco' => $idBanco, 'NumeroCuenta' => $nroCuenta, 'CCI' => $cci, 'IdTipoMoneda' => $tipoMoneda, 'MontoInicial' => $inicial, 'MontoActual' => $actual, 'Estado' => 'E', 'IdCuentaBancaria' => $tipoCuenta, 'FechaCreacion' => now()];
            DB::table('banco')->insert($array);
            return redirect('administracion/bancos/cuentas-bancarias')->with('status', 'Se creo cuenta corriente correctamente');
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function edit(Request $req, $idBanco)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');

            $loadDatos = new DatosController();
            $usuario = $loadDatos->getUsuarioSelect($idUsuario);
            $permisos = $loadDatos->getPermisos($idUsuario);
            $cuentaCorriente = $loadDatos->getCuentaCorrienteSelect($idBanco);

            $listaBancos = $loadDatos->getListaBancos();
            $tipoMonedas = $loadDatos->getTipoMoneda();

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuario->CodigoCliente);
            // Nuevo codigo
            $tiposCuentasBancarias = DB::table('tipos_cuentas_bancarias')->get();
            // Fin

            $array = ['listaBancos' => $listaBancos, 'tipoMonedas' => $tipoMonedas, 'cuentaCorriente' => $cuentaCorriente, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'tiposCuentasBancarias' => $tiposCuentasBancarias];
            return view('administracion/bancos/cuentaBancaria/editarBanco', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function updateIngresoSalida(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        try {
            DB::BeginTransaction();
            $array = ['Entrada' => $req->montoNuevo, 'Detalle' => $req->detalle];
            if ($req->tipoMovimiento === 'Registro Salida') {
                $array = ['Salida' => $req->montoNuevo, 'Detalle' => $req->detalle];
            }
            $loadDatos = new DatosController();
            $fechas = $loadDatos->getFechaFiltro($req->fecha, $req->fechaIni, $req->fechaFin);
            $codigoCliente = $loadDatos->getUsuarioSelect($idUsuario)->CodigoCliente;
            $resultado = $this->verificarSaldoDisponible($req->idBanco, $codigoCliente, $req->fechaPago, $req->tipoMovimiento, $req->montoParaActualizar);
            if ($resultado) {
                return response()->json(['respuesta' => 'error', 'mensaje' => $resultado]);
            }
            // actualizar entrada o salida de detalle banco
            DB::table('banco_detalles')->where('IdBancoDetalles', $req->idRegistro)->update($array);

            // actualizar el saldo actual de cuenta
            $montoCuenta = $loadDatos->getCuentaCorrienteSelect($req->idBanco);
            if ($req->tipoMovimiento === 'Registro Salida') {
                if (floatval($req->montoNuevo) < floatval($req->montoActual)) {
                    $montoActual = (float) $montoCuenta->MontoActual + (float) $req->montoParaActualizar;
                } else {
                    $montoActual = (float) $montoCuenta->MontoActual - (float) $req->montoParaActualizar;
                }
            } else {
                if (floatval($req->montoNuevo) < floatval($req->montoActual)) {
                    $montoActual = (float) $montoCuenta->MontoActual - (float) $req->montoParaActualizar;
                } else {
                    $montoActual = (float) $montoCuenta->MontoActual + (float) $req->montoParaActualizar;
                }
            }

            DB::table('banco')
                ->where('IdBanco', $req->idBanco)
                ->update(["MontoActual" => $montoActual]);

            // calcular nuevos saldos
            $resultadoCalculo = $this->calcularSaldoCuentaBancaria($req->idBanco, $codigoCliente);
            $detallesCuentaCorriente = $resultadoCalculo->whereBetween('FechaPago', [$fechas[0], $fechas[1]])->sortKeysDesc();

            DB::commit();
            $renderView = view('administracion.bancos.cuentaBancaria.tablaDetalleBanco', ['detallesCuentaCorriente' => $detallesCuentaCorriente])->render();
            return response()->json(['respuesta' => 'success', 'renderView' => $renderView, 'detallesCuentaCorriente' => $detallesCuentaCorriente]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['respuesta' => 'error', 'mensaje' => 'Ocurrio un error, por favor comunicarse con el área de soporte']);
        }
        return redirect('/')->with('out', 'Sesión de usuario Expirado');

    }

    public function update(Request $req, $id)
    {
        try {
            $idUsuario = Session::get('idUsuario');
            $this->validateBanco($req);

            $idBanco = $req->banco;
            $nroCuenta = $req->cuenta;
            $cci = $req->cci;
            $tipoMoneda = $req->tipoMoneda;
            $tipoCuenta = $req->tipoCuenta;

            $array = ['IdListaBanco' => $idBanco, 'NumeroCuenta' => $nroCuenta, 'CCI' => $cci, 'IdTipoMoneda' => $tipoMoneda, 'IdCuentaBancaria' => $tipoCuenta, 'FechaActualizacion' => now()];

            DB::table('banco')
                ->where('IdBanco', $id)
                ->update($array);

            return redirect('administracion/bancos/cuentas-bancarias')->with('status', 'Se actualizo banco correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // NUEVA FUNCION
    // public function calcularSaldoBanco($idBanco, $fechaInicial, $fechaFinal)
    // {
    //     $getDetalleCuentaBanco = DB::table('banco_detalles as BD')
    //         ->join('banco', 'BD.IdBanco', '=', 'banco.IdBanco')
    //         ->select('BD.*', 'MontoInicial', 'banco.IdTipoMoneda as tipoMoneda')
    //         ->where('BD.IdBanco', $idBanco)
    //         ->orderBy('FechaPago', 'desc')
    //         ->get();

    //     // $getDetalleCuentaBanco = DB::select('call sp_getDetalleCuentaBanco(?)', array($idBanco));

    //     $detallesDesc = $getDetalleCuentaBanco->sortBy('FechaPago')->values();
    //     $bancoMontoInicial = $getDetalleCuentaBanco->pluck('MontoInicial')->first();
    //     $montoActual = 0;

    //     foreach ($detallesDesc as $key => $item) {
    //         if ($item->Entrada > 0) {
    //             $montoActual = $bancoMontoInicial + $item->Entrada;
    //             $bancoMontoInicial = $montoActual;
    //             $detallesDesc[$key]->Monto = $montoActual;
    //         }
    //         if ($item->Salida > 0) {
    //             $montoActual = $bancoMontoInicial - $item->Salida;
    //             $bancoMontoInicial = $montoActual;
    //             $detallesDesc[$key]->Monto = $montoActual;
    //         }

    //         if ($item->Salida == 0 && $item->Entrada == 0) {
    //             $detallesDesc[$key]->Monto = $montoActual;
    //         }

    //     }
    //     return $detallesDesc->whereBetween('FechaPago', [$fechaInicial, $fechaFinal])->sortKeysDesc();
    // }
    // FIN

    public function storeTransferencia(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        try {
            DB::BeginTransaction();
            $idSucursal = Session::get('idSucursal');
            $loadDatos = new DatosController();

            if ($req->selectBancoDestino == 0) {
                return response()->json(['respuesta' => 'error', 'mensaje' => 'Se olvido de seleccionar el número de cuenta']);
            }

            if ($req->inputMontoTranferencia == '') {
                return response()->json(['respuesta' => 'error', 'mensaje' => 'Se olvido de ingresar el monto a transferir']);
            }

            $numeroOperacion = $req->inputNumeroOperacion ?? '000000';

            $montoCuentaOrigen = $loadDatos->getCuentaCorrienteSelect($req->inputBancoOrigen);
            if ($req->inputMontoTranferencia > $montoCuentaOrigen->MontoActual) {
                return response()->json(['respuesta' => 'error', 'mensaje' => 'El monto a descontar es mayor que el Monto Actual']);
            }
            $montoActualOrigen = floatval($montoCuentaOrigen->MontoActual) - $req->inputMontoTranferencia;
            $montoCuentaDestino = $loadDatos->getCuentaCorrienteSelect($req->selectBancoDestino);
            $montoActualDestino = floatval($montoCuentaDestino->MontoActual) + $req->inputMontoTranferencia;

            $array = [
                ['FechaPago' => now(), 'IdBanco' => $req->inputBancoOrigen, 'NumeroOperacion' => $numeroOperacion, 'Detalle' => "Cuenta corriente Destino ($req->numeroCuentaDestino)", 'TipoMovimiento' => 'Transferencia Saliente', 'Entrada' => 0, 'Salida' => $req->inputMontoTranferencia, 'MontoActual' => $montoActualOrigen, 'IdSucursal' => $idSucursal],
                ['FechaPago' => now(), 'IdBanco' => $req->selectBancoDestino, 'NumeroOperacion' => $numeroOperacion, 'Detalle' => "Cuenta corriente Origen ($req->inputNumeroCuentaOrigen)", 'TipoMovimiento' => 'Transferencia Entrante', 'Entrada' => $req->inputMontoTranferencia, 'Salida' => 0, 'MontoActual' => $montoActualDestino, 'IdSucursal' => $idSucursal],
            ];

            DB::table('banco_detalles')->insert($array);

            DB::table('banco')
                ->where('IdBanco', $req->inputBancoOrigen)
                ->update(["MontoActual" => $montoActualOrigen]);

            DB::table('banco')
                ->where('IdBanco', $req->selectBancoDestino)
                ->update(["MontoActual" => $montoActualDestino]);

            DB::commit();
            return response()->json(['respuesta' => 'success', 'mensaje' => 'La transferencia fue un éxito', 'montoActual' => $montoActualOrigen]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['respuesta' => 'error', 'mensaje' => 'Ocurrio un error, por favor comunicarse con el área de soporte']);
        }
    }

    public function show(Request $req, $id)
    {
        try {
            $idUsuario = Session::get('idUsuario');
            if ($req->session()->has('idUsuario')) {
                $idUsuario = Session::get('idUsuario');
            } else {
                Session::flush();
                return redirect('/')->with('out', 'Sesión de usuario Expirado');
            }
            $loadDatos = new DatosController();
            $fecha = $req->fecha ?? 5;
            // dd($fecha);
            $fechaIni = $req->fechaIni ?? null;
            $fechaFin = $req->fechaFin ?? null;
            $idtipo = $req->fecha ?? '';
            $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            $usuario = $loadDatos->getUsuarioSelect($idUsuario);
            $resultadoCalculo = $this->calcularSaldoCuentaBancaria($id, $usuario->CodigoCliente);
            $detallesCuentaCorriente = $resultadoCalculo->whereBetween('FechaPago', [$fechas[0], $fechas[1]])->sortKeysDesc();

            if ($req->ajax()) {
                $renderView = view('administracion.bancos.cuentaBancaria.tablaDetalleBanco', ['detallesCuentaCorriente' => $detallesCuentaCorriente])->render();
                return response()->json(['renderView' => $renderView, 'detallesCuentaCorriente' => $detallesCuentaCorriente]);
            } else {
                $permisos = $loadDatos->getPermisos($idUsuario);
                $cuentaCorriente = $loadDatos->getCuentaCorrienteSelect($id);
                $allCuentasCorrientes = $loadDatos->getCuentasCorrientes($usuario->CodigoCliente, $cuentaCorriente->IdTipoMoneda)->whereNotIn('IdBanco', $id);
                $subpermisos = $loadDatos->getSubPermisos($idUsuario);
                $subniveles = $loadDatos->getSubNiveles($idUsuario);
                $modulosSelect = $loadDatos->getModulosSelect($usuario->CodigoCliente);
                $array = ['cuentaCorriente' => $cuentaCorriente, 'detallesCuentaCorriente' => $detallesCuentaCorriente, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles,
                    'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'idTipo' => $idtipo, 'id' => $id, 'allCuentasCorrientes' => $allCuentasCorrientes];
                return view('administracion/bancos/cuentaBancaria/detallesBanco', $array);
            }

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function filtrar(Request $req, $id)
    {
        try {

            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $usuario = $loadDatos->getUsuarioSelect($idUsuario);
            $permisos = $loadDatos->getPermisos($idUsuario);

            $fecha = $req->fecha;
            $fechaIni = $req->fechaIni;
            $fechaFin = $req->fechaFin;
            $idtipo = 1;

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

            $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);

            // $detallesCuentaCorriente = DB::select('call sp_getDetalleCuentaCorriente(?, ?, ?)', array($id, $fechas[0], $fechas[1]));
            // $detallesCuentaCorriente = collect($detallesCuentaCorriente);

            // // NUEVO MONTO DETALLES CUENTA
            // $detalles = DB::select('call sp_getDetalleCuentaBanco(?)', array($id));
            // $detalles = collect($detalles);
            // $detallesDesc = $detalles->sortBy('FechaPago')->values();
            // $montoActual = 0;
            // $nuevoMontoActual = 0;

            // $bancoMontoInicial = $detalles->pluck('MontoInicial')->first();

            // foreach ($detallesDesc as $key => $item) {
            //     if ($item->Entrada > 0) {
            //         $montoActual = $bancoMontoInicial + $item->Entrada;
            //         $bancoMontoInicial = abs($montoActual);
            //         $monto[] = abs($montoActual);
            //     }
            //     if ($item->Salida > 0) {
            //         $montoActual = $bancoMontoInicial - $item->Salida;
            //         $bancoMontoInicial = abs($montoActual);
            //         $monto[] = abs($montoActual);
            //     }

            //     if ($item->Salida == 0 && $item->Entrada == 0) {
            //         $monto[] = abs($montoActual);
            //     }
            // }
            // for ($i = 0; $i < count($detalles); $i++) {
            //     $detallesDesc[$i]->Monto = $monto[$i];
            // }
            // $detallesCuentaCorrienteAsc = $detallesCuentaCorriente->sortBy('FechaPago')->values();
            // $minimaFechaDetalle = $detallesCuentaCorrienteAsc->min('FechaPago');
            // $resultadoBanco = $detallesDesc->where('FechaPago', $minimaFechaDetalle);
            // $bancoInicial = $resultadoBanco->pluck("Monto")->values()->first();
            // $entradaAnterior = $resultadoBanco->pluck("Entrada")->values()->first();
            // $salidaAnterior = $resultadoBanco->pluck("Salida")->values()->first();

            // if ($entradaAnterior > 0) {
            //     $bancoMontoInicial = $bancoInicial - $entradaAnterior;
            // } else {
            //     $bancoMontoInicial = $bancoInicial + $salidaAnterior;
            // }

            // foreach ($detallesCuentaCorrienteAsc as $key => $item) {
            //     if ($item->Entrada > 0) {
            //         $nuevoMontoActual = $bancoMontoInicial + $item->Entrada;
            //         $bancoMontoInicial = abs($nuevoMontoActual);
            //         $nuevoMonto[] = abs($nuevoMontoActual);
            //     }
            //     if ($item->Salida > 0) {
            //         $nuevoMontoActual = $bancoMontoInicial - $item->Salida;
            //         $bancoMontoInicial = abs($nuevoMontoActual);
            //         $nuevoMonto[] = abs($nuevoMontoActual);
            //     }

            //     if ($item->Salida == 0 && $item->Entrada == 0) {
            //         $nuevoMonto[] = abs($nuevoMontoActual);
            //     }
            // }
            // for ($i = 0; $i < count($detallesCuentaCorriente); $i++) {
            //     $detallesCuentaCorrienteAsc[$i]->Monto = $nuevoMonto[$i];
            // }
            // $detallesCuentaCorriente = $detallesCuentaCorrienteAsc->sortKeysDesc();
            // FIN

            $cuentaCorriente = $loadDatos->getCuentaCorrienteSelect($id);
            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuario->CodigoCliente);

            $resultadoCalculo = $this->calcularSaldoCuentaBancaria($id, $usuario->CodigoCliente);
            $detallesCuentaCorriente = $resultadoCalculo->whereBetween('FechaPago', [$fechas[0], $fechas[1]])->sortKeysDesc();

            $array = ['cuentaCorriente' => $cuentaCorriente, 'detallesCuentaCorriente' => $detallesCuentaCorriente, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles,
                'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'idTipo' => $idtipo, 'id' => $id];
            return view('administracion/bancos/cuentaBancaria/detallesBanco', $array);

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function registrar(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                $idUsuario = Session::get('idUsuario');
                $idSucursal = Session::get('idSucursal');
                $loadDatos = new DatosController();
                $this->validateDetalleBanco($req);
                $idBanco = $req->idBanco;
                $montoCuenta = $loadDatos->getCuentaCorrienteSelect($idBanco);
                $tipoMovimiento = $req->tipoMovimiento;
                $numeroOperacion = $req->numeroOperacion;
                $detalle = $req->detalle;
                $monto = $req->monto;
                $fechaAnterior = $req->fechaAnterior;
                $montoActual = floatval($montoCuenta->MontoActual);
                $montoActual = abs($montoActual);
                // $fechaHoy = $loadDatos->getDateTime();
                if ($tipoMovimiento == 1) {
                    $movimiento = "Registro Ingreso";
                    $montoActual = (float) $montoActual + (float) $monto;
                    $entrada = $monto;
                    $salida = 0;
                } else {
                    if ($montoActual < floatval($monto)) {
                        return back()->with('error', 'El monto Actual no puede ser menor que la Salida, Vuelva intentarlo');
                    }
                    $movimiento = "Registro Salida";
                    $montoActual = (float) $montoActual - (float) $monto;
                    $entrada = 0;
                    $salida = $monto;
                }
                if ($fechaAnterior == "") {
                    $fechaHoy = $loadDatos->getDateTime();
                } else {
                    $fechaHoy = Carbon::createFromFormat('d/m/Y', $req->fechaAnterior)->format('Y-m-d h:i:s');
                    // =======================
                    if ($tipoMovimiento != 1) {
                        // Se obtiene el primer registro del detalle banco
                        $fechaPrimerDetalle = DB::table('banco_detalles')->where('IdBanco', $idBanco)->first()->FechaPago ?? null;
                        // Se evalua si la fecha Anterior es mayor a la decha del primer registro
                        if ($fechaPrimerDetalle != null && $fechaHoy > $fechaPrimerDetalle) {
                            $usuario = $loadDatos->getUsuarioSelect($idUsuario);
                            $resultadoCalculado = $this->calcularSaldoCuentaBancaria($idBanco, $usuario->CodigoCliente);
                            // Se obtiene el Monto Actual de la fecha anterior
                            $registroAnterior = $resultadoCalculado->where('FechaPago', '<', $fechaHoy)->last();
                            // Se evalua si el Monto actual de la fecha anterior es menor al monto de la salida
                            if ($registroAnterior->SaldoActualCalculado < $monto) {
                                return back()->with('error', "El último monto Actual de esa fecha es ($registroAnterior->SaldoActualCalculado) , no puede ser menor que la Salida");
                            }
                        }
                        // Si la fecha del primer detalle Banco es menor a la fecha Anterior, se evalua si el MONTO INICIAL es menor al monto de la salida
                        if ($fechaPrimerDetalle != null && $fechaHoy < $fechaPrimerDetalle) {
                            if ($montoCuenta->MontoInicial < $monto) {
                                return back()->with('error', "El último monto Actual de esa fecha es ($montoCuenta->MontoInicial) , no puede ser menor que la Salida");
                            }
                        }
                        // ========================
                    }
                }

                // Nuevo Codigo
                $checkGasto = $req->checkGasto;
                if ($checkGasto == 1) {
                    $tipoGasto = $req->tipoGasto;
                    $observacion = $req->observacion;
                    $idGasto = $req->listaGastos;

                    $array = ['TipoGasto' => $tipoGasto, 'IdListaGastos' => $idGasto, 'IdUsuario' => $idUsuario, 'IdSucursal' => $idSucursal, 'FechaCreacion' => $fechaHoy, 'Monto' => $monto, 'Observacion' => $observacion, 'Estado' => 'E'];
                    DB::table('gastos')->insert($array);
                }
                // Fin

                $array = ['FechaPago' => $fechaHoy, 'IdBanco' => $idBanco, 'NumeroOperacion' => $numeroOperacion, 'Detalle' => $detalle, 'TipoMovimiento' => $movimiento, 'Entrada' => $entrada, 'Salida' => $salida, 'MontoActual' => $montoActual, 'IdSucursal' => $idSucursal];

                DB::table('banco_detalles')->insert($array);

                DB::table('banco')
                    ->where('IdBanco', $idBanco)
                    ->update(["MontoActual" => $montoActual]);

                return redirect()->route('cuentas-bancarias.show', [$idBanco])->with('status', 'Se ' . $movimiento . ' correctamente');
            } else {
                Session::flush();
                return redirect('/')->with('out', 'Sesión de usuario Expirado');
            }

        } catch (Exception $ex) {
            echo $ex->getMessage();
            return Response(['error', 'Por favor, completar serie y número correlativo']);
        }
    }

    public function delete($id)
    {
        try {
            $array = ['Estado' => 'D'];
            DB::table('banco')
                ->where('IdBanco', $id)
                ->update($array);

            return redirect('administracion/bancos/cuentas-bancarias')->with('status', 'Se elimino producto correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // Nueva Funcion Traer gastos
    public function listarGastos(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                if ($req->ajax()) {
                    $tipo = $req->tipo;
                    $loadDatos = new DatosController();
                    $idSucursal = Session::get('idSucursal');
                    $listaGastos = $loadDatos->getListaGastos($tipo, 0);
                    return Response($listaGastos);
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    // Fin

    public function validateBanco(Request $req)
    {
        $this->validate($req, [
            'banco' => 'required',
            // 'cuenta' => 'required|numeric',
            'cuenta' => 'required|regex:/^[A-Za-z0-9\-]+$/',
            // 'cci' => 'required|numeric',
            'cci' => $req->input('banco') != 9 ? 'required|numeric' : '', // Validar cci solo si el banco no es 9
            'tipoMoneda' => 'required',
            'inicial' => 'required',
        ]);
    }

    public function validateDetalleBanco(Request $req)
    {
        $this->validate($req, [
            'numeroOperacion' => 'required',
            'detalle' => 'required',
            'monto' => 'required|numeric',
        ]);
    }
}
