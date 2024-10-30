<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Attribute;

class Cotizacion extends Model
{
    //

    //use HasFactory;
    protected $table = 'cotizacion';
    protected $primaryKey = 'IdCotizacion';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $attributes = [
        'IdOperario' => 0,
    ];

    public $fillable = [
        'IdCotizacion',
        'IdCliente',
        'IdTipoMoneda',
        'IdSucursal',
        'IdCheckIn',
        'IdCreacion',
        'FechaCreacion',
        'FechaActualizacion',
        'FechaFin',
        'IdOperario',
        'Serie',
        'Numero',
        'Seguro',
        'IdEstadoCotizacion',
        'IdTipoAtencion',
        'TipoCotizacion',
        'Campo0',
        'Campo1',
        'Campo2',
        'TipoVenta',
        'SubTotal',
        'Exonerada',
        'Igv',
        'Total',
        'Trabajos',
        'Observacion',
        'Estado',
        'UrlPdf',
        'FechaCreacionPdf',
        'MantenimientoActual',
        'ProximoMantenimiento',
        'PeriodoProximoMantenimiento',
        'AplicaControlCalidad',
        'OpcionVentaSolesDolares',
    ];

    public $fillableComercial = [
        'IdCotizacion',
        'IdCliente',
        'IdTipoMoneda',
        'IdSucursal',
        'IdCreacion',
        'FechaCreacion',
        'FechaFin',
        'Serie',
        'Numero',
        'IdEstadoCotizacion',
        'IdTipoAtencion',
        'TipoCotizacion',
        'TipoVenta',
        'SubTotal',
        'Exonerada',
        'Igv',
        'Total',
        'Observacion',
    ];

    protected $fillableVehicular = [
        'IdCotizacion',
        'IdCliente',
        'IdTipoMoneda',
        'IdSucursal',
        'IdCheckIn',
        'IdCreacion',
        'FechaCreacion',
        'FechaFin',
        'IdOperario',
        'Serie',
        'Numero',
        'Seguro',
        'IdEstadoCotizacion',
        'IdTipoAtencion',
        'TipoCotizacion',
        'Campo0',
        'Campo1',
        'Campo2',
        'TipoVenta',
        'SubTotal',
        'Exonerada',
        'Igv',
        'Total',
        'Trabajos',
        'Observacion',
    ];

    // Mutators
    public function setFechaFinAttribute($value)
    {
        $this->attributes['FechaFin'] = Carbon::createFromFormat('Y-m-d', $value)->format('Y-m-d H:i:s');
    }

    public function setFechaCreacionAttribute($value)
    {
        $this->attributes['FechaCreacion'] = now();
    }

    public function setProximoMantenimientoAttribute($value)
    {
        if ($value != null) {
            $RegExpInputProximoMantenimiento = '/[^0-9]/';
            $proximoMantenimiento = preg_replace($RegExpInputProximoMantenimiento, "", $value);
            $proximoMantenimiento = number_format($proximoMantenimiento, 0, ',', ' ') . " " . 'Km';
        } else {
            $proximoMantenimiento = null;
        }
        $this->attributes['ProximoMantenimiento'] = $proximoMantenimiento;
    }

}
