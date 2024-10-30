<?php

namespace App\Models\Administrador;

use Illuminate\Database\Eloquent\Model;

class PagoPlataforma extends Model
{
    //use HasFactory;
    protected $table = 'pagos_plataforma';
    protected $primaryKey = 'Id';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Id',
        'IdEmpresa',
        'NombreEmpresa',
        'Ruc',
        'NumeroOperacion',
        'MontoPago',
        'Imagen',
        'Estado',
        'FechaRegistro',
        'Celular',
    ];
}
