<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    //

    protected $table = 'sucursal';
    protected $primaryKey = 'IdSucursal';
    
    public $timestamps = false;

    /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [

    'IdSucursal' ,
    'Nombre' ,
    'CodFiscal',
    'Exonerado',
    'Ubigeo',
    'Direccion' ,
    'Ciudad',
    'Telefono' ,
    'CodigoCliente' ,
    'Principal',
    'Orden' ,
    'Estado',
    'OcultarDireccion' 
  
];   
    


}
