<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articulo_paquetepromocional extends Model
{
    //
    //

    protected $table = 'articulo_paquetepromocional';
    protected $primaryKey = 'IdDetallePaquetePromo';
    
    public $timestamps = false;

    /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

    protected $fillable = [

        'IdDetallePaquetePromo',
        'IdPaquetePromocional',
        'IdArticulo',
        'CodigoArticulo',
        'Cantidad',
        'Importe'
      
    ];   

   
}
