<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'IdUsuario';

    protected $fillable = [
        'Nombre', 'Login', 'Password', // Asegúrate de incluir 'clave' en $fillable
    ];

    protected $hidden = [
        'Password', 'remember_token',
    ];

    // Nombre de la columna de contraseña diferente
    public $password = 'Password';
}
