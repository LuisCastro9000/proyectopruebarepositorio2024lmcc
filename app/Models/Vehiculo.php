<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    //
    protected $table = 'vehiculo';
    protected $primaryKey = 'IdVehiculo';
    
    public $timestamps = false;
    
    /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [

        'IdVehiculo'  ,
        'IdSucursal'  ,
        'IdSeguro'  ,
        'TipoVehicular' ,
        'PlacaVehiculo' ,
        'ChasisVehiculo'  ,
        'HorometroInicial' ,
        'KilometroInicial' ,
        'Color' ,
        'Anio' ,
        'Motor' ,
        'NumeroFlota' ,
        'FechaSoat' ,
        'FechaRevTecnica'  ,
        'CertificacionAnual'  ,
        'PruebaQuinquenal'  ,
        'IdMarcaVehiculo'  ,
        'IdModeloVehiculo'  ,
        'IdTipoVehiculo'  ,
        'IdCreacion'  ,
        'IdCliente'  ,
        'NotaVehiculo' ,
        'FechaIngreso' ,
        'Estado' ,
        'PeriodoMantenimientoKm' 
        
  
];   
}
