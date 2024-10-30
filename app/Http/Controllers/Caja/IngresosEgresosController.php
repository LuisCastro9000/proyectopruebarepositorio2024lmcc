<?php

namespace App\Http\Controllers\Caja;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DateTime;
use DB;
use Illuminate\Http\Request;
use Session;

class IngresosEgresosController extends Controller
{
    public function __invoke(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');

        $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
        if ($caja == null) {
            $fecha = '';
        } else {
            $fecha = $caja->FechaApertura;
        }
        $ingresosEgresos = $loadDatos->getIngresosEgresos($idSucursal, $idUsuario, $fecha);
        $totalIngresoSoles = $ingresosEgresos->where('Tipo', 'I')->where('IdTipoMoneda', 1)->sum('Monto');
        $totalIngresoDolares = $ingresosEgresos->where('Tipo', 'I')->where('IdTipoMoneda', 2)->sum('Monto');

        $totalEgresoSoles = $ingresosEgresos->where('Tipo', 'E')->where('IdTipoMoneda', 1)->sum('Monto');
        $totalEgresoDolares = $ingresosEgresos->where('Tipo', 'E')->where('IdTipoMoneda', 2)->sum('Monto');

        // dd($totalEgresoDolares);
        $permisos = $loadDatos->getPermisos($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $array = ['permisos' => $permisos, 'ingresosEgresos' => $ingresosEgresos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'totalIngresoSoles' => $totalIngresoSoles, 'totalIngresoDolares' => $totalIngresoDolares, 'totalEgresoSoles' => $totalEgresoSoles, 'totalEgresoDolares' => $totalEgresoDolares];
        return view('caja/ingresosEgresos', $array);
    }

    public function actualizarIngresoEgreso(request $req)
    {
        try {
            if ($req->ajax()) {
                $monto = $req->monto;
                $descripcion = $req->descripcion;
                $id = $req->id;

                $actualizarGasto = $req->actualizarGasto;
                $idGasto = $req->idGasto;

                for ($i = 0; $i < count($req->id); $i++) {
                    $arrayDatos = [
                        'Descripcion' => $req->descripcion[$i],
                        'Monto' => $req->monto[$i],
                    ];
                    if ($actualizarGasto[$i] == 'activo') {
                        DB::table('gastos')
                            ->where("IdGastos", $idGasto[$i])
                            ->update(['Monto' => $req->monto[$i]]);
                    }

                    DB::table('ingresoegreso')
                        ->where("IdIngresEgreso", $id[$i])
                        ->update($arrayDatos);

                }
                // $array = ['Monto' => $monto, 'Descripcion' => $descripcion];
                // DB::table('ingresoegreso')
                // ->where("IdIngresEgreso", $id)
                // ->update($array);

                return Response("Se Actualizo Correctamente");
                // return Response ("['Se Actualizo Correctamente', $id]");
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function generarIngreso(Request $req)
    {
        try {
            if ($req->ajax()) {
                $montoIngreso = $req->montoIngreso;
                if ($montoIngreso == null || $montoIngreso == '') {
                    return Response(['error', 'Ingresar monto para registrar ingreso']);
                }
                $descIngreso = $req->descIngreso;
                $tipoMoneda = $req->tipoMoneda;
                $loadDatos = new DatosController();
                $idSucursal = Session::get('idSucursal');
                $idUsuario = Session::get('idUsuario');
                $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
                if ($caja == null) {
                    return Response(['error', 'Abrir caja antes de registrar un ingreso']);
                }
                //$date = new DateTime();
                $fecha = $loadDatos->getDateTime();
                $tipo = 'I';
                $array = ['IdCaja' => $caja->IdCaja, 'Fecha' => $fecha, 'Tipo' => $tipo, 'IdTipoMoneda' => $tipoMoneda, 'Monto' => $montoIngreso, 'Descripcion' => $descIngreso];
                DB::table('ingresoegreso')->insert($array);
                return Response(['success', 'Se registro ingreso con éxito']);
            }

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function generarEgreso(Request $req)
    {
        try {
            if ($req->ajax()) {
                $montoEgreso = $req->montoEgreso;
                if ($montoEgreso == null || $montoEgreso == '') {
                    return Response(['error', 'Ingresar monto para registrar egreso']);
                }
                $descEgreso = $req->descEgreso;
                $tipoMoneda = $req->tipoMoneda;
                $loadDatos = new DatosController();
                $idSucursal = Session::get('idSucursal');
                $idUsuario = Session::get('idUsuario');
                $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
                if ($caja == null) {
                    return Response(['error', 'Abrir caja antes de registrar un egreso']);
                }
                $date = new DateTime();
                $fecha = $date->format("Y-m-d H:i:s");
                $tipo = 'E';

                // Nuevo Codigo
                if ($req->checkActivarGasto == 1) {
                    $tipoGasto = $req->tipoGasto;
                    $observacion = $req->observacion;
                    $idListaGastos = $req->idGasto;
                    if ($tipoGasto != 0) {
                        $array = ['TipoGasto' => $tipoGasto, 'IdListaGastos' => $idListaGastos, 'IdUsuario' => $idUsuario, 'IdSucursal' => $idSucursal, 'FechaCreacion' => $fecha, 'Monto' => $montoEgreso, 'Observacion' => $observacion, 'Estado' => 'E', 'IdTipoMoneda' => $tipoMoneda];
                        DB::table('gastos')->insert($array);

                        $idGasto = DB::table('gastos')
                            ->select('IdGastos')
                            ->orderBy('IdGastos', 'desc')
                            ->first();
                        $idGasto = $idGasto->IdGastos;
                    } else {
                        return Response(['error', 'Se Olvido de seleccionar el Tipo de Gasto']);
                    }
                } else {
                    $idGasto = null;
                }
                // Fin

                $array = ['IdCaja' => $caja->IdCaja, 'Fecha' => $fecha, 'Tipo' => $tipo, 'IdTipoMoneda' => $tipoMoneda, 'Monto' => $montoEgreso, 'Descripcion' => $descEgreso, 'IdGastos' => $idGasto];
                DB::table('ingresoegreso')->insert($array);

                return Response(['success', 'Se registro egreso con éxito']);
            }

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // Nueva Funcion Traer gastos
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
    // Fin
}
