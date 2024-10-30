<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class AdminMiddleware extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        if (session()->has('idAdmin')) {
            // El usuario tiene una sesión válida, permite el acceso.
            return $next($request);
        }

        // Si no tiene una sesión válida, se redirige a la página de inicio de sesión admin o realiza otra acción.
        return redirect('/admin/inicia-sesion')->with('out', 'Sesión de usuario Expirada');
    }
}
