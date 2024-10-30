<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Http\Request;
use Session;

class GastosController extends Controller
{
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $fecha = date("d/m/Y");
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $idSucursal = Session::get('idSucursal');
        // Nuevo Codigo
        $ultimosTreintaDias = $this->ultimosTreintaDias();
        $listaGastosUltimosTreintaDias = $loadDatos->getObtenerGastos($idSucursal, $ultimosTreintaDias);
        // Fin
        $array = ['fecha' => $fecha, 'permisos' => $permisos, 'usuarioSelect' => $usuarioSelect, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'listaGastosUltimosTreintaDias' => $listaGastosUltimosTreintaDias];
        return view('administracion/gastos/gastos', $array);
    }

    public function listarGastos(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                if ($req->ajax()) {
                    $tipo = $req->tipo;
                    $loadDatos = new DatosController();
                    $idSucursal = Session::get('idSucursal');
                    $listaGastos = $loadDatos->getListaGastos($tipo, 0);
                    return Response($listaGastos);
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function crearGastos(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
        }

        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $sucursales = $loadDatos->getSucursales($usuarioSelect->CodigoCliente, $usuarioSelect->IdOperador);
        $tipoMonedas = $loadDatos->getTipoMoneda();

        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'usuarioSelect' => $usuarioSelect, 'sucursales' => $sucursales, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'tipoMonedas' => $tipoMonedas];
        return view('administracion/gastos/crearGastos', $array);

    }

    public function store(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                $this->validateGastos($req);
                $idUsuario = Session::get('idUsuario');
                $idSucursal = Session::get('idSucursal');
                if ($req->tipoGasto > 0) {
                    $loadDatos = new DatosController();
                    $tipoGasto = $req->tipoGasto;
                    $fecha = $req->fecha;
                    $date = DateTime::createFromFormat('Y-m-d', $fecha);
                    $fechaConvertida = $date->format("Y-m-d H:i:s");
                    $idListaGastos = $req->listaGastos;
                    $otros = $req->otros;
                    $monto = $req->monto;
                    $observacion = $req->observacion;
                    $idTipoMoneda = $req->tipoMoneda;

                    /*if($req->listaGastos == 0){
                    $gasto = $this->guardarListaGastos($tipoGasto, $idSucursal, $otros);
                    $idListaGastos = $gasto->IdListaGastos;
                    }*/
                    $array = ['TipoGasto' => $tipoGasto, 'IdListaGastos' => $idListaGastos, 'IdUsuario' => $idUsuario, 'IdSucursal' => $idSucursal, 'FechaCreacion' => $fechaConvertida, 'Monto' => $monto, 'Observacion' => $observacion, 'Estado' => 'E', 'IdTipoMoneda' => $idTipoMoneda];
                    DB::table('gastos')->insert($array);
                    return redirect('administracion/gastos')->with('status', 'Se registro gasto correctamente');
                } else {
                    return redirect('administracion/gastos')->with('error', 'Seleccionar Tipo de Gasto');
                }
            } else {
                Session::flush();
                return redirect('/')->with('out', 'Sesión de usuario Expirado');
            }

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    protected function guardarListaGastos($tipoGasto, $idSucursal, $otros)
    {
        $array = ['Tipo' => $tipoGasto, 'IdSucursal' => $idSucursal, 'Descripcion' => $otros, 'Estado' => 'E'];
        DB::table('lista_gastos')->insert($array);

        $gasto = DB::table('lista_gastos')
            ->orderBy('IdListaGastos', 'desc')
            ->first();

        return $gasto;
    }

    public function actualizarGasto(request $req)
    {
        try {
            if ($req->ajax()) {
                $monto = $req->monto;
                $observacion = $req->observacion;
                $id = $req->id;

                for ($i = 0; $i < count($req->id); $i++) {
                    $arrayDatos = [
                        'Observacion' => $req->observacion[$i],
                        'Monto' => $req->monto[$i],
                    ];

                    DB::table('gastos')
                        ->where("IdGastos", $id[$i])
                        ->update($arrayDatos);
                }
                return Response("Se Actualizo Correctamente");
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    // FIN

    protected function validateGastos(Request $request)
    {
        $this->validate($request, [
            'tipoGasto' => 'required',
            'listaGastos' => 'required',
            'monto' => 'required',
            'observacion' => 'required',
        ]);
    }

    public function ultimosTreintaDias()
    {
        $fechaInicio = Carbon::now()->subDays(30);
        return array($fechaInicio);
    }
}
