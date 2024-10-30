<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Session;

class LoginAdminController extends Controller
{
    public function __invoke()
    {
        return view('admin/login');
    }

    public function loguin(Request $req)
    {
        $usuario = $req->usuario;
        $contrasena = $req->password;
        $verificarLogin = DB::table('usuario')
            ->join('operador', 'usuario.IdOperador', '=', 'operador.IdOperador')
            ->select('usuario.*', 'operador.Descripcion')
            ->where('Login', $usuario)
            ->whereNotNull('Login')
            ->first();
        if ($verificarLogin === null) {
            return back()->with('error', 'La cuenta de usuario no esta Registrado');
        }
        if ($verificarLogin->Cliente !== null) {
            return back()->with('error', 'No eres el administrador');
        }

        if ($verificarLogin->Cliente === null) {
            if (password_verify($contrasena, $verificarLogin->Password) || password_verify($contrasena, $verificarLogin->Password2)) {
                Session::put('idAdmin', $verificarLogin->IdUsuario);
                Session::put('nombreAdmin', $verificarLogin->Nombre);

                return Redirect::route('admin.dashboards');
            } else {
                return back()->with('errorpassword', 'password incorrecto, por favor vuelva a ingresarlo');
            }
        }
    }
    public function logout()
    {
        Session::flush();
        return Redirect::route('admin.login');
    }
}

// -------------
// use App\Models\Usuario;
// use Illuminate\Support\Facades\Auth;
// $credentials = $req->only('usuario', 'password');
// $verificarLogin = Usuario::where('Login', $credentials['usuario'])->first();
// // Despues de if true
// Auth::login($verificarLogin);
// -------
// En el archivo config/auth pegar
// 'model' => App\Models\Usuario::class,
// 'providers' => [
//         'users' => [
//             'driver' => 'eloquent',
//             // 'model' => App\User::class,
//             'model' => App\Models\Usuario::class,

//         ],
// dd(Auth::user());
