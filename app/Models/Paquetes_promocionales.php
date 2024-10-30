<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paquetes_promocionales extends Model
{
    //
     //

     protected $table = 'paquetes_promocionales';
     protected $primaryKey = 'IdPaquetePromocional';
     
     public $timestamps = false;
 
     /**
    * The attributes that are mass assignable.
    *
    * @var array
    */

    protected $fillable = [
        'IdPaquetePromocional',
        'NombrePaquete',
        'IdTipoMoneda',
        'IdSucursal',
        'IdTipo',
        'FechaCreacion',
        'FechaModificacion',
        'FechaEliminacion',
        'IdUsuarioCreacion',
        'IdUsuarioModificacion',
        'IdUsuarioEliminacion',
        'Estado',
        'Precio',
        'Costo',
        'Etiqueta'      
    ];  
}
