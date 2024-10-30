<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permisos_Botones extends Model
{
    // use HasFactory;

    protected $fillable = [
        'Id', 'Nombre', 'Descripcion',
    ];

    protected $table = 'permisos_botones';
    protected $primaryKey = 'Id';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
}
