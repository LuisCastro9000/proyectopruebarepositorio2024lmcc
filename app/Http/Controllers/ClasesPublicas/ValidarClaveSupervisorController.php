<?php

namespace App\Http\Controllers\ClasesPublicas;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DB;
use Illuminate\Http\Request;
use Session;

class ValidarClaveSupervisorController extends Controller
{

    public function __invoke(Request $req)
    {
        if ($req->ajax()) {
            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
            $loadDatos = new DatosController();
            $password = $req->password;

            $datosUsuario = $loadDatos->getUsuarioSelect($idUsuario);

            if ($datosUsuario->Cliente != 1) {
                $codigoCliente = $datosUsuario->CodigoCliente;
                $datosUsuario = DB::table('usuario')
                    ->select('usuario.ClaveDeComprobacion', 'usuario.Nombre', 'usuario.IdSucursal')
                    ->where('usuario.CodigoCliente', $codigoCliente)
                    ->where('usuario.Cliente', 1)
                    ->first();
            }

            if ((password_verify($password, $datosUsuario->ClaveDeComprobacion))) {
                $password = (password_verify($password, $datosUsuario->ClaveDeComprobacion));
                return Response(['Success', 'La clave si coincide']);
            }
        }

    }
}
