<?php

namespace App\Http\Controllers\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class AjusteFechasReportesController extends Controller
{
    public function ajustarFechas($array, $idFecha, $fechaIni, $fechaFin) {
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        if($idFecha == 0 || $idFecha == ''){
            for($i=0; $i<count($array); $i++){
               $array[$i]->FechaCreacion = 'Todas las fechas';
            }
        }
        if($idFecha == 1){
            for($i=0; $i<count($array); $i++){
               $array[$i]->FechaCreacion = 'Hoy';
            }
        }
        if($idFecha == 2){
            for($i=0; $i<count($array); $i++){
               $array[$i]->FechaCreacion = 'Ayer';
            }
        }
        if($idFecha == 3){
            for($i=0; $i<count($array); $i++){
               $array[$i]->FechaCreacion = 'Esta Semana';
            }
        }
        if($idFecha == 4){
            for($i=0; $i<count($array); $i++){
               $array[$i]->FechaCreacion = 'Última Semana';
            }
        }
        if($idFecha == 5){
            for($i=0; $i<count($array); $i++){
               $date = Carbon::parse($array[$i]->FechaCreacion);
               $array[$i]->FechaCreacion = $meses[$date->month - 1];
            }
        }
        if($idFecha == 6){
            for($i=0; $i<count($array); $i++){
               $date = Carbon::parse($array[$i]->FechaCreacion);
               $array[$i]->FechaCreacion = $meses[$date->month - 1];
            }
        }
        if($idFecha == 7){
            for($i=0; $i<count($array); $i++){
               $date = Carbon::parse($array[$i]->FechaCreacion);
               $array[$i]->FechaCreacion = $date->year;
            }
        }
        if($idFecha == 8){
            for($i=0; $i<count($array); $i++){
               $date = Carbon::parse($array[$i]->FechaCreacion);
               $array[$i]->FechaCreacion = $date->year;
            }
        }
        if($idFecha == 9){
            for($i=0; $i<count($array); $i++){
               $date = Carbon::parse($array[$i]->FechaCreacion);
               $array[$i]->FechaCreacion = $fechaIni.' - '.$fechaFin;
            }
        }
        return $array;
    }
}
