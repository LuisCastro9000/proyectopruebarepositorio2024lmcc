<?php

namespace App\Http\Controllers\Cobranzas;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Session;

class RealizarCobroController extends Controller
{
    public function __invoke(Request $req, $id, $tipoMoneda)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
        if ($caja == null) {
            echo "<script language='javascript'>alert('Abrir caja antes de realizar una cobranza');window.location='../../caja/cierre-caja'</script>";
        }
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $selectCuota = $loadDatos->getCuotaPagar($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $bancos = $loadDatos->getCuentasCorrientes($usuarioSelect->CodigoCliente, $tipoMoneda);
        $array = ['permisos' => $permisos, 'bancos' => $bancos, 'IdFechaPago' => $id, 'selectCuota' => $selectCuota, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('cobranzas/realizarCobro', $array);
    }

    public function cobrar(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idFechaPago = $req->idFechaPago;
        $idVenta = $req->idVenta;

        $importePagado = $req->importePagado;
        $totalEfectivo = $req->totalEfectivo;
        $totalTarjeta = $req->totalTarjeta;
        $importe = $req->importe;

        $modoPago = $req->modoPago;
        if ($modoPago == 1) {
            $pago = $req->deudaTotal;
            $estado = 2;
            $_modoPago = 'Totalidad';
        } else {
            $pago = $req->deudaTotal;
            $pagoParcial = $req->pagoParcial;
            if ($pago < $pagoParcial) {
                return back()->with('error', 'El monto parcial a cobrar no puede ser mayor que la deuda total')->withInput($req->all());
            }
            if ($pagoParcial == $pago) {
                $estado = 2;
            } else {
                $estado = 1;
            }
            $_modoPago = 'Parcial';
            $pago = $pagoParcial;
        }

        if ($pago == null) {
            return back()->with('error', 'Ingrese monto parcial')->withInput($req->all());
        }
        $numTarjeta = $req->numTarjeta;
        $montoEfectivo = $req->pagoEfectivo;
        $idTipoTarjeta = $req->tipoTarjeta;
        $montoTarjeta = $req->pagoTarjeta;

        $cuentaBancaria = $req->cuentaBancaria;
        $montoCuenta = floatval($req->montoCuenta);

        $totalPago = floatval($montoEfectivo) + floatval($montoTarjeta) + floatval($montoCuenta);

        if ($pago < $totalPago) {
            return back()->with('error', 'El monto a cobrar no puede ser mayor que la deuda total o Parcial')->withInput($req->all());
        }
        if ($pago > $totalPago) {
            return back()->with('error', 'El monto a cobrar no puede ser menor que el monto Total o Parcial')->withInput($req->all());
        }

        if ($cuentaBancaria == 0) {
            $cuentaBancaria = 1;
        }
        //dd($totalPago. '---'.$pago);
        $pagoTotal = floatval($importePagado) + floatval($pago);
        $pagoTotalEfectivo = floatval($totalEfectivo) + floatval($montoEfectivo);
        $pagoTotalTarjeta = floatval($totalTarjeta) + floatval($montoTarjeta);
        $idSucursal = Session::get('idSucursal');
        $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
        $fechaHoy = $loadDatos->getDateTime();

        $array = ['IdCaja' => $caja->IdCaja, 'FechaPago' => $fechaHoy, 'ImportePagado' => $pagoTotal, 'MontoEfectivo' => $pagoTotalEfectivo, 'IdTipoTarjeta' => $idTipoTarjeta, 'NumeroTarjeta' => $numTarjeta, 'MontoTarjeta' => $pagoTotalTarjeta, 'Estado' => $estado];
        DB::table('fecha_pago')
            ->where('IdFechaPago', $idFechaPago)
            ->update($array);

        $importePendiente = floatval($req->deudaTotal);
        $restaImporte = floatval($importePendiente) - floatval($pago);
        $_array = ['IdFechaPago' => $idFechaPago, 'IdUsuario' => $idUsuario, 'FechaPago' => $fechaHoy, 'ImporteInicial' => $importe, 'ImportePendiente' => $importePendiente, 'MontoPagado' => $pago, 'ModoPago' => $_modoPago, 'Efectivo' => $montoEfectivo, 'Tarjeta' => $montoTarjeta, 'IdBanco' => $cuentaBancaria, 'CuentaBancaria' => $montoCuenta, 'RestaImporte' => $restaImporte];
        DB::table('pagos_detalle')->insert($_array);

        if (floatval($montoCuenta) > 0) {
            $nroOperacion = $req->nroOperacion;
            $dateBanco = $req->fechaCobroCuenta;
            if ($dateBanco == null || $dateBanco == "") {
                $fechaBanco = $fechaHoy;
            } else {
                $fechaBanco = Carbon::createFromFormat('d/m/Y', $dateBanco)->format('Y-m-d');
            }
            $banco = $loadDatos->getCuentaCorrienteSelect($cuentaBancaria);
            $montoActual = floatval($banco->MontoActual) + floatval($montoCuenta);
            $venta = DB::table('ventas')
                ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                ->select('Serie', 'Numero')
                ->where('ventas.IdVentas', $idVenta)
                ->first();

            $arrayDatos = ['FechaPago' => $fechaBanco, 'IdBanco' => $cuentaBancaria, 'NumeroOperacion' => $nroOperacion, 'Detalle' => $venta->Serie . '-' . $venta->Numero, 'TipoMovimiento' => 'Cobro a Cliente', 'Entrada' => $montoCuenta, 'Salida' => '0', 'MontoActual' => $montoActual, 'IdSucursal' => $idSucursal];
            DB::table('banco_detalles')->insert($arrayDatos);

            DB::table('banco')->where('IdBanco', $cuentaBancaria)->update(['MontoActual' => $montoActual]);
        }

        return redirect('detalle-cobranza/' . $idVenta)->with('status', 'Se realizo cobro con éxito');
    }

}
