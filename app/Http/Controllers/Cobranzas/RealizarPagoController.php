<?php

namespace App\Http\Controllers\Cobranzas;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use App\Traits\CuentasBancariasTrait;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Session;

class RealizarPagoController extends Controller
{
    use CuentasBancariasTrait;

    public function __invoke(Request $req, $id, $tipoMoneda)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
            if ($caja == null) {
                echo "<script language='javascript'>alert('Abrir caja antes de realizar una cobranza');window.location='../../caja/cierre-caja'</script>";
            }
            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $selectCuota = $loadDatos->getCuotaPagarProveedor($id);
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $bancos = $loadDatos->getCuentasCorrientes($usuarioSelect->CodigoCliente, $tipoMoneda);
            $array = ['permisos' => $permisos, 'selectCuota' => $selectCuota, 'bancos' => $bancos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'tipoMoneda' => $tipoMoneda];
            return view('cobranzas/realizarPago', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function pagar(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            try {
                DB::beginTransaction();
                $idUsuario = Session::get('idUsuario');
                $loadDatos = new DatosController();
                $idFechaCompra = $req->idFechaCompra;
                $idCompra = $req->idCompra;
                $importePagado = $req->importePagado;
                $totalEfectivo = $req->totalEfectivo;
                $totalCuenta = $req->totalCuenta;
                $importe = $req->importe;
                $cuentaBancaria = $req->cuentaBancaria;
                $montoEfectivo = $req->pagoEfectivo;
                $montoCuenta = $req->montoCuenta;
                $nroOperacion = $req->nroOperacion;

                $modoPago = $req->modoPago;
                if ($modoPago == 1) {
                    $pago = $req->deudaTotal;
                    $estado = 2;
                    $_modoPago = 'Totalidad';
                } else {
                    $pago = $req->pagoParcial;
                    $estado = 1;
                    $_modoPago = 'Parcial';
                }
                if ($pago == null) {
                    return back()->with('error', 'Ingrese monto parcial')->withInput($req->all());
                }

                $totalPago = floatval($montoEfectivo) + floatval($montoCuenta);
                if ($pago < $totalPago) {
                    return back()->with('error', 'El monto a pagar no puede ser mayor que la deuda')->withInput($req->all());
                }
                if ($pago > $totalPago) {
                    return back()->with('error', 'El monto a pagar no puede ser menor que el monto Total o Parcial')->withInput($req->all());
                }

                if ($cuentaBancaria > 0) {
                    $banco = $loadDatos->getCuentaCorrienteSelect($cuentaBancaria);
                    if (floatval($banco->MontoActual) < floatval($montoCuenta)) {
                        return back()->with('error', 'La cuenta bancaria no se encuentra con saldo suficiente disponible')->withInput($req->all());
                    }
                } else {
                    $cuentaBancaria = 1;
                }

                $pagoTotal = floatval($importePagado) + floatval($pago);
                $pagoTotalEfectivo = floatval($totalEfectivo) + floatval($montoEfectivo);
                $pagoTotalCuenta = floatval($totalCuenta) + floatval($montoCuenta);
                $idSucursal = Session::get('idSucursal');
                $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
                $fechaHoy = $loadDatos->getDateTime();

                $array = ['IdCaja' => $caja->IdCaja, 'FechaPago' => $fechaHoy, 'ImportePagado' => $pagoTotal, 'MontoEfectivo' => $pagoTotalEfectivo, 'MontoBanco' => $pagoTotalCuenta, 'Estado' => $estado];
                DB::table('fecha_compras')
                    ->where('IdFechaCompras', $idFechaCompra)
                    ->update($array);

                $importePendiente = floatval($req->deudaTotal);
                $restaImporte = floatval($importePendiente) - floatval($pago);
                $_array = ['IdFechaCompras' => $idFechaCompra, 'FechaPago' => $fechaHoy, 'ImportePendiente' => $importePendiente, 'ImporteInicial' => $importe, 'MontoPagado' => $pago, 'ModoPago' => $_modoPago, 'Efectivo' => $montoEfectivo, 'IdBanco' => $cuentaBancaria, 'CuentaBancaria' => $montoCuenta, 'RestaImporte' => $restaImporte];
                DB::table('detalles_pagos_proveedor')->insert($_array);

                if (floatval($montoCuenta) > 0) {
                    $dateBanco = $req->fechaPagoCuenta;
                    if ($dateBanco == null || $dateBanco == "") {
                        $fechaBanco = $fechaHoy;
                    } else {
                        $fechaBanco = Carbon::createFromFormat('d/m/Y', $dateBanco)->toDateTimeString();
                    }
                    // Codigo para validar el si el monto actual de la fecha anterior es mayor al monto ingresado de la cuenta
                    // Se obtiene el primer registro del detalle banco
                    $fechaPrimerDetalle = DB::table('banco_detalles')->where('IdBanco', $cuentaBancaria)->first()->FechaPago ?? null;
                    if ($fechaPrimerDetalle != null && $fechaBanco < $fechaHoy && $fechaBanco > $fechaPrimerDetalle) {
                        $usuario = $loadDatos->getUsuarioSelect($idUsuario);
                        $resultadoCalculado = $this->calcularSaldoCuentaBancaria($cuentaBancaria, $usuario->CodigoCliente);
                        // dd($resultadoCalculado);
                        // Se obtiene el Monto Actual de la fecha anterior
                        $registroAnterior = $resultadoCalculado->where('FechaPago', '<', $fechaBanco)->last();
                        // Se evalua si el Monto actual de la fecha anterior es menor al monto de la salida
                        if ($registroAnterior->SaldoActualCalculado < $montoCuenta) {
                            return back()->with('error', " El último monto actual registrado para esa fecha es $registroAnterior->SaldoActualCalculado, por lo que no puede ser menor que el monto a pagar de la cuenta ($montoCuenta).");
                        }
                    }
                    // Se evalua la fecha del primer detalle Banco es menor a la fecha Anterior
                    if ($fechaPrimerDetalle != null && $fechaBanco < $fechaPrimerDetalle) {
                        return back()->with('error', "La fecha ($fechaBanco) del pago por cuenta bancaria no puede ser anterior a la fecha ($fechaPrimerDetalle) de creación de la cuenta");
                    }
                    // ====== Fin

                    $montoActual = floatval($banco->MontoActual) - floatval($montoCuenta);
                    $compra = DB::table('compras')
                        ->select('Serie', 'Numero')
                        ->where('compras.IdCompras', $idCompra)
                        ->first();

                    $arrayDatos = ['FechaPago' => $fechaBanco, 'IdBanco' => $cuentaBancaria, 'NumeroOperacion' => $nroOperacion, 'Detalle' => $compra->Serie . '-' . $compra->Numero, 'TipoMovimiento' => 'Pago a Proveedor', 'Entrada' => '0', 'Salida' => $montoCuenta, 'MontoActual' => $montoActual, 'IdSucursal' => $idSucursal];
                    DB::table('banco_detalles')->insert($arrayDatos);

                    DB::table('banco')->where('IdBanco', $cuentaBancaria)->update(['MontoActual' => $montoActual]);

                }
                if ($req->has('switchRegistrarGasto')) {
                    $array = ['TipoGasto' => $req->tipoGasto, 'IdListaGastos' => $req->idListaGasto, 'IdUsuario' => $idUsuario, 'IdSucursal' => $idSucursal, 'FechaCreacion' => now(), 'Monto' => $pago, 'Observacion' => $req->observacion, 'Estado' => 'E', 'IdTipoMoneda' => $req->tipoMoneda];

                    DB::table('gastos')->insert($array);
                }
                DB::commit();
                return redirect('detalle-pago/' . $idCompra)->with('status', 'Se realizo pago con éxito');
            } catch (\Throwable $th) {
                DB::rollback();
                return back()->with('error', 'Ocurrio un error, por favor comunicarse con el área de soporte');
            }
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function obtenerGastos(Request $req)
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

}
