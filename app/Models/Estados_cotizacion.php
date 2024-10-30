<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estados_cotizacion extends Model
{
    //

    
    protected $table = 'estados_cotizacion';
    protected $primaryKey = 'IdEstadoCotizacion';
    
    public $timestamps = false;

    /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [

    'IdEstadoCotizacion' ,
    'Descripcion',
    'Color' ,
    'Estado'
  
];   
    

}
