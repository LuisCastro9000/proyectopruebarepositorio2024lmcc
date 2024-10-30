<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    //
    protected $table = 'articulo';
    protected $primaryKey = 'IdTarea';

    public $timestamps = false;




    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'IdTarea',
        'IdMarca',
        'IdCategoria',
        'IdTipo',
        'IdUnidadMedida',
        'IdSucursal ',
        'IdTipoMoneda',
        'FechaCreacion',
        'FechaModificacion',
        'FechaEliminacion ',
        'IdCreacion',
        'IdModificacion ',
        'IdEliminacion',
        'Descripcion',
        'Ubicacion',
        'Stock',
        'Precio',
        'Exonerado ',
        'Costo',
        'ValorTipoCambio',
        'TipoOperacion',
        'Imagen',
        'Codigo',
        'CodigoInterno',
        'VentaMayor1 ',
        'Descuento1 ',
        'PrecioDescuento1 ',
        'VentaMayor2 ',
        'Descuento2',
        'PrecioDescuento2',
        'VentaMayor3 ',
        'Descuento3',
        'PrecioDescuento3',
        'IdTipoUnidad ',
        'NombreTipo ',
        'CantidadTipo',
        'DescuentoTipo ',
        'PrecioTipo',
        'Estado ',
    ];
}
