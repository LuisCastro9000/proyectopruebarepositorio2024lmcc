<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion_articulopaquetepromocional extends Model
{
    //

    protected $table = 'cotizacion_articuloPaquetePromocional';
    protected $primaryKey = 'IdDetalle';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'IdDetalle',
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
    ];
}
