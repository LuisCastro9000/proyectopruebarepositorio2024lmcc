<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class grupos_productos_servicios extends Model
{

     
    protected $table = 'grupos_productos_servicios';
    protected $primaryKey = 'IdGrupo';
    
    public $timestamps = false;

    /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [

    'IdGrupo',
  'NombreGrupo' ,
  'IdTipoMoneda',
  'FechaCreacion',
  'FechaModificacion',
  'IdSucursal',
  'IdUsuarioCreacion',
  'Estado'
  
];   
    
}