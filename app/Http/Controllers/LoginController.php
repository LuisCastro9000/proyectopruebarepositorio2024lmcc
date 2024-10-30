<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Session;

class LoginController extends Controller
{
    public function __invoke()
    {
        return view('login');
    }

    public function loginUsuario(Request $req)
    {
        if ($req->session()->has('idUsuario')) {

            $idUsuario = Session::get('idUsuario');

            $this->validateLogin($req);

            $usuario = $req->usuario;
            $contrasena = $req->contraseña;

            $verificarLogin = DB::table('usuario')
                ->join('operador', 'usuario.IdOperador', '=', 'operador.IdOperador')
                ->select('usuario.*', 'operador.Descripcion')
                ->where('Login', $usuario)
            // ->where('usuario.Estado', 'E')
                ->get();
            if (count($verificarLogin) < 1) {
                return back()->with('error', 'La cuenta de usuario no esta Registrado');
            }
            // Nuevo codigo
            if ($verificarLogin[0]->Estado == 'D') {
                return back()->with('error', 'Cuenta de usuario Bloqueada');
            }

            if ($verificarLogin[0]->Estado == 'Suscripcion Caducada') {
                return back()->with('error', 'La suscripcion ha finalizado');
            }

            // Comprobamos si el usuario Principal tiene habilitada al menos una sucursal
            if ($verificarLogin[0]->Cliente != null) {
                if ($verificarLogin[0]->Cliente == 1) {
                    $datos = DB::table('usuario')
                        ->join('sucursal', 'usuario.CodigoCliente', '=', 'sucursal.CodigoCliente')
                        ->select('usuario.CodigoCliente', 'sucursal.IdSucursal', 'sucursal.Estado')
                        ->where('usuario.IdUsuario', $verificarLogin[0]->IdUsuario)
                        ->where('sucursal.Estado', 'E')
                        ->where('usuario.CodigoCliente', $verificarLogin[0]->CodigoCliente)
                        ->first();
                    if ($datos == null) {
                        return back()->with('error', 'La Suscripción ha Finalizado');
                    }
                }
            }
            // Fin

            if (count($verificarLogin) > 0) {
                if (password_verify($contrasena, $verificarLogin[0]->Password) || password_verify($contrasena, $verificarLogin[0]->Password2)) {

                    if ($verificarLogin[0]->IdUsuario == $idUsuario) {
                        $idUsuario = $verificarLogin[0]->IdUsuario;
                        $idSucursal = $verificarLogin[0]->IdSucursal;
                        $foto = $verificarLogin[0]->Foto;
                        Session::put('idUsuario', $idUsuario);
                        Session::put('idSucursal', $idSucursal);
                        Session::put('foto', $foto);
                        Session::put('contrasenaLogin', $contrasena);
                        // Esta session se agrego para mostar un mensaje en el modal de validarClaveSupervisor dependiendo si un Cliente->1 (Adminsitrador) o usuario
                        Session::put('Cliente', $verificarLogin[0]->Cliente);
                        return redirect()->to('panel');
                    } else {
                        return back()
                            ->with('error', 'Ya se encuentra un usuario activo, cerrar sesión para iniciar con uno nuevo');
                    }
                } else {
                    return back()
                        ->with('error', 'Usuario y/o contraseña incorrecta, por favor vuelva a intentar')
                        ->withInput(request(['usuario']));
                }
            }
            // else {
            //     return back()
            //         ->with('error', 'Usuario y/o contraseña incorrecta, por favor vuelva a intentar');
            // }

        } else {
            $this->validateLogin($req);

            $usuario = $req->usuario;
            $contrasena = $req->contraseña;

            $verificarLogin = DB::table('usuario')
                ->join('operador', 'usuario.IdOperador', '=', 'operador.IdOperador')
                ->select('usuario.*', 'operador.Descripcion')
                ->where('Login', $usuario)
            // ->where('usuario.Estado', 'E')
                ->get();

            if (count($verificarLogin) < 1) {
                return back()->with('error', 'La cuenta de usuario no esta Registrado');
            }
            // Nuevo codigo
            if ($verificarLogin[0]->Estado == 'D') {
                return back()->with('error', 'Cuenta de usuario Bloqueada');
            }

            if ($verificarLogin[0]->Estado == 'Suscripcion Caducada') {
                return back()->with('error', 'La suscripcion ha finalizado');
            }

            // Comprobamos si el usaurio Principal tiene habilitada al menos una sucursal
            if ($verificarLogin[0]->Cliente != null) {
                if ($verificarLogin[0]->Cliente == 1) {
                    $datos = DB::table('usuario')
                        ->join('sucursal', 'usuario.CodigoCliente', '=', 'sucursal.CodigoCliente')
                        ->select('usuario.CodigoCliente', 'sucursal.IdSucursal', 'sucursal.Estado')
                        ->where('usuario.IdUsuario', $verificarLogin[0]->IdUsuario)
                        ->where('sucursal.Estado', 'E')
                        ->where('usuario.CodigoCliente', $verificarLogin[0]->CodigoCliente)
                        ->first();
                    if ($datos == null) {
                        return back()->with('error', 'La Suscripción ha Finalizado');
                    }
                }
            }
            // Fin

            //$verificarLogin = DB::select("select usuario.*, operador.Descripcion from usuario inner join operador on usuario.IdOperador = operador.IdOperador where Login = 'demoautomotriz@mifacturita.pe' and usuario.Estado = 'E'");
            //dd($verificarLogin);
            if (count($verificarLogin) > 0) {
                if (password_verify($contrasena, $verificarLogin[0]->Password) || password_verify($contrasena, $verificarLogin[0]->Password2)) {
                    //dd($verificarLogin);
                    $idUsuario = $verificarLogin[0]->IdUsuario;
                    $idSucursal = $verificarLogin[0]->IdSucursal;
                    $foto = $verificarLogin[0]->Foto;
                    //$logo = $this->getLogoEmpresa($verificarLogin[0]->CodigoCliente);
                    Session::put('idUsuario', $idUsuario);
                    Session::put('idSucursal', $idSucursal);
                    Session::put('foto', $foto);
                    Session::put('contrasenaLogin', $contrasena);
                    // Esta session se agrego para mostar un mensaje en el modal de validarClaveSupervisor dependiendo si un Cliente->1 (Adminsitrador) o usuario
                    Session::put('Cliente', $verificarLogin[0]->Cliente);
                    return redirect()->to('panel');
                } else {
                    return back()
                        ->with('error', 'Usuario y/o contraseña incorrecta, por favor vuelva a intentar')
                        ->withInput(request(['usuario']));
                }
            }
            // else {
            //     return back()
            //         ->with('error', 'Usuario y/o contraseña incorrecta, por favor vuelva a intentar');
            // }
        }
    }

    protected function getLogoEmpresa($codigoCliente)
    {
        $logo = DB::table('empresa')
            ->select('Imagen')
            ->where('CodigoCliente', $codigoCliente)
            ->first();
        return $logo;
    }

    public function loginOut()
    {
        Session::flush();
        return redirect('/')
            ->with('out', 'Sesión de usuario finalizado');
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            'usuario' => 'required|max:50|min:5',
            'contraseña' => 'required|max:30|min:6',
        ]);
    }
}
