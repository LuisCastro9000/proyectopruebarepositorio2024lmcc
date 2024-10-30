<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion_articulo extends Model
{
    //

    protected $table = 'cotizacion_articulo';
    protected $primaryKey = 'IdCotizaArticulo';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'IdCotizaArticulo',
        'IdCotizacion',
        'IdCliente',
        'IdArticulo',
        'Codigo',
        'Detalle',
        'Descuento',
        'VerificaTipo',
        'Cantidad',
        'CantidadReal',
        'PrecioUnidadReal',
        'TextUnidad',
        'Ganancia',
        'Importe',
        'IdPaquetePromocional',
        'Fecha_actualizacion',
    ];
}
