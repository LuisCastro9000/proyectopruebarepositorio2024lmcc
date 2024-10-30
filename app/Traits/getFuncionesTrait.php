<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Str;

trait getFuncionesTrait
{
    public function getNombreMesAbreviado($numeroMes)
    {
        $meses = ['1' => 'ENE', '2' => 'FEB', '3' => 'MAR', '4' => 'ABR', '5' => 'MAY', '6' => 'JUN', '7' => 'JUL', '8' => 'AGOS', '9' => 'SEP', '10' => 'OCT', '11' => 'NOV', '12' => 'DIC'];
        return $meses[$numeroMes];
    }

    public function getNombreMes($numeroMes)
    {
        $meses = ['1' => 'ENERO', '2' => 'FEBRERO', '3' => 'MARZO', '4' => 'ABRIL', '5' => 'MAYO', '6' => 'JUNIO', '7' => 'JULIO', '8' => 'AGOSTO', '9' => 'SETIEMBRE', '10' => 'OCTUBRE', '11' => 'NOVIEMBRE', '12' => 'DICIEMBRE'];
        return $meses[$numeroMes];
    }

    public function getNombreDia($fecha)
    {
        return CARBON::parse($fecha)->locale('es')->dayName;
    }

    public function generarCorrelativo($textoSerie, $correlativoActual, $numeroOrden)
    {
        if ($correlativoActual) {
            $numero = str_pad($correlativoActual->Numero + 1, 8, '0', STR_PAD_LEFT);
        } else {
            $numero = str_pad(1, 8, '0', STR_PAD_LEFT);
        }

        $serieCeros = str_pad($numeroOrden, 2, '0', STR_PAD_LEFT);
        $serie = $textoSerie . $numeroOrden . '' . $serieCeros;

        return (object) ['Serie' => $serie, 'Numero' => $numero];
    }

    public function formatearFechaRecibidaConSlash($fecha)
    {
        return $fecha ? Carbon::createFromFormat('d/m/Y', $fecha)->format('Y-m-d H:i:s') : '';
    }

    public function formatearFechaRecibidaConGuion($fecha)
    {
        return $fecha ? Carbon::createFromFormat('d-m-Y', $fecha)->format('Y-m-d H:i:s') : '';
    }

    public function isValidoFormatoCorreoElectronico($correo)
    {
        $patron = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/';
        if (preg_match($patron, $correo)) {
            return true;
        } else {
            return false;
        }
    }

    public function isValidoFormatoLogin($loginUsuarioCliente, $login)
    {
        // Expresion regular para obtener la cadena antes del primer punto correo.correo@correo.correo
        $patron = '/^[^.]+\./';
        // Reemplazamos el patron por vacio, siendo el resultado correo@correo.correo
        $formatoLoginUsuarioCliente = preg_replace($patron, '', $loginUsuarioCliente);
        $patronLoginUsuarioCliente = "/^[a-zA-Z0-9]+\.($formatoLoginUsuarioCliente)$/";
        if (preg_match($patronLoginUsuarioCliente, $login)) {
            return true;
        } else {
            return false;
        }
    }

    public function generarUbicacionArchivo($cadena, $rucEmpresa = '')
    {
        $fecha = Carbon::now();
        $anio = $fecha->year;
        $mes = Str::ucfirst($fecha->locale('es')->monthName);
        return $cadena . "$anio/$mes/$rucEmpresa";
    }
}
