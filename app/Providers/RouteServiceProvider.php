<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // *** RUTAS PARA EL ADMINISTRADOR
            Route::middleware('web')
                ->group(base_path('routes/web/admin/dashboards.php'));

            Route::middleware('web')
                ->group(base_path('routes/web/admin/login.php'));

            Route::middleware('web')
                ->group(base_path('routes/web/admin/pagosPlataforma.php'));

            Route::middleware('web')
                ->group(base_path('routes/web/admin/administracion.php'));

// *** RUTAS PARA USUARIOS
            Route::middleware('web')
                ->group(base_path('routes/web/usuarios/login.php'));

            Route::middleware('web')
                ->group(base_path('routes/web/usuarios/perfil.php'));

            Route::middleware('web')
                ->group(base_path('routes/web/usuarios/soporte.php'));

            Route::middleware('web')
                ->group(base_path('routes/web/usuarios/rutas_globales.php'));

            Route::middleware('web')
                ->group(base_path('routes/web/usuarios/panel.php'));

            Route::middleware('web')
                ->group(base_path('routes/web/usuarios/administracion.php'));

            Route::middleware('web')
                ->group(base_path('routes/web/usuarios/vehicular.php'));

            Route::middleware('web')
                ->group(base_path('routes/web/usuarios/operaciones.php'));

            Route::middleware('web')
                ->group(base_path('routes/web/usuarios/caja.php'));

            Route::middleware('web')
                ->group(base_path('routes/web/usuarios/pagosCobranzas.php'));

            Route::middleware('web')
                ->group(base_path('routes/web/usuarios/consultas.php'));

            Route::middleware('web')
                ->group(base_path('routes/web/usuarios/reportes.php'));

        });
    }
}
