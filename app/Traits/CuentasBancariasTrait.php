<?php
namespace App\Traits;

use App\Traits\getFuncionesTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait CuentasBancariasTrait
{
    use getFuncionesTrait;
    public function calcularSaldoCuentaBancaria($idBanco, $codigoCliente)
    {
        $nombreSucursalPrincipal = DB::table('sucursal')->where('CodigoCliente', '=', $codigoCliente)->where('Principal', 1)->value('Nombre') ?? '';

        $detalleCuentaCorriente = DB::table('banco_detalles as BD')
            ->join('banco', 'BD.IdBanco', '=', 'banco.IdBanco')
            ->leftJoin('sucursal', 'sucursal.IdSucursal', '=', 'BD.IdSucursal')
            ->select('BD.*', DB::raw("COALESCE(sucursal.Nombre, '$nombreSucursalPrincipal') as NombreSucursal"), 'IdBancoDetalles', 'FechaPago', 'BD.IdBanco', 'Entrada', 'Salida', 'BD.MontoActual', 'banco.MontoInicial', DB::raw('MONTH(BD.FechaPago) as Mes, YEAR(BD.FechaPago) as Anio'))
            ->where('BD.IdBanco', $idBanco)
            ->orderBy('FechaPago', 'asc')
            ->orderBy('IdBancoDetalles', 'asc')
            ->get();

        // NUEVO MONTO DETALLES CUENTA
        $bancoMontoInicial = $detalleCuentaCorriente->pluck('MontoInicial')->first();
        $montoActual = 0;

        foreach ($detalleCuentaCorriente as $key => $item) {
            if ($item->Entrada > 0) {
                $montoActual = $bancoMontoInicial + $item->Entrada;
                $bancoMontoInicial = abs($montoActual);
                $detalleCuentaCorriente[$key]->SaldoActualCalculado = $montoActual;
                $detalleCuentaCorriente[$key]->NombreMes = $this->getNombreMesAbreviado($item->Mes) . ' ' . $item->Anio;

            }
            if ($item->Salida > 0) {
                $montoActual = $bancoMontoInicial - $item->Salida;
                $bancoMontoInicial = abs($montoActual);
                $detalleCuentaCorriente[$key]->SaldoActualCalculado = $montoActual;
                $detalleCuentaCorriente[$key]->NombreMes = $this->getNombreMesAbreviado($item->Mes) . ' ' . $item->Anio;
            }

            if ($item->Salida == 0 && $item->Entrada == 0) {
                $detalleCuentaCorriente[$key]->SaldoActualCalculado = $montoActual;
                $detalleCuentaCorriente[$key]->NombreMes = $this->getNombreMesAbreviado($item->Mes) . ' ' . $item->Anio;
            }
        }
        return $detalleCuentaCorriente;
    }
    public function verificarSaldoDisponible($idBanco, $codigoCliente, $fecha, $tipoMovimiento, $monto)
    {
        $fechaRegistro = Str::contains($fecha, '-') ? $fecha : Carbon::createFromFormat('d/m/Y', $fecha)->format('Y-m-d h:i:s');
        if ($tipoMovimiento === 'Registro Salida') {
            // Se obtiene el primer registro del detalle banco
            $fechaPrimerDetalle = DB::table('banco_detalles')->where('IdBanco', $idBanco)->first()->FechaPago ?? null;
            // Se evalua si la fecha Anterior es mayor a la decha del primer registro
            if ($fechaPrimerDetalle != null && $fechaRegistro > $fechaPrimerDetalle) {
                $resultadoCalculado = $this->calcularSaldoCuentaBancaria($idBanco, $codigoCliente);
                // Se obtiene el Monto Actual de la fecha anterior
                $registroAnterior = $resultadoCalculado->where('FechaPago', '<', $fechaRegistro)->last();
                // Se evalua si el Monto actual de la fecha anterior es menor al monto de la salida
                if ($registroAnterior->SaldoActualCalculado < $monto) {
                    return "El último monto Actual de esa fecha es ($registroAnterior->SaldoActualCalculado) , no puede ser menor que la Salida";
                }
            }
            // Si la fecha del primer detalle Banco es menor a la fecha Anterior, se evalua si el MONTO INICIAL es menor al monto de la salida
            if ($fechaPrimerDetalle != null && $fechaRegistro < $fechaPrimerDetalle) {
                if ($montoCuenta->MontoInicial < $monto) {
                    return "El último monto Actual de esa fecha es ($montoCuenta->MontoInicial) , no puede ser menor que la Salida";
                }
            }
        }
        return null;
    }
}
