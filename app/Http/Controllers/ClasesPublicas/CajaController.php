<?php

namespace App\Http\Controllers\ClasesPublicas;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Illuminate\Support\Facades\Session;

class CajaController extends Controller
{

    public function obtenerTotalCajaAbierta($idTipoMoneda)
    {
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');

        $caja = $loadDatos->getCierreCajaUltimo($idSucursal, $idUsuario);
        $cobranzas = $loadDatos->getDetalleCajaCobranzas($idSucursal, $idUsuario, $caja->FechaApertura, $idTipoMoneda);
        $ventasContadoTotal = $loadDatos->getDetalleCajaContado($idSucursal, $idUsuario, $caja->FechaApertura, $idTipoMoneda);
        $ventasContadoEfectivo = $ventasContadoTotal[0]->Efectivo;
        $cobranzasEfectivo = $cobranzas[0]->Efectivo;
        $ingresosSoles = $loadDatos->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, 'I', $idTipoMoneda);
        if ($ingresosSoles[0]->Monto == null) {
            $montoIngresos = '0.00';
        } else {
            $montoIngresos = $ingresosSoles[0]->Monto;
        }
        $egresos = $loadDatos->getTotalIngresosEgresos($idSucursal, $idUsuario, $caja->FechaApertura, 'E', $idTipoMoneda);
        if ($egresos[0]->Monto == null) {
            $montoEgresos = '0.00';
        } else {
            $montoEgresos = $egresos[0]->Monto;
        }

        $inicial = $idTipoMoneda === 1 ? $caja->Inicial : $caja->InicialDolares;

        $totalCaja = floatval($cobranzasEfectivo) + floatval($ventasContadoEfectivo) + floatval($inicial) + floatval($montoIngresos) - floatval($montoEgresos);
        return $totalCaja;
    }
}
