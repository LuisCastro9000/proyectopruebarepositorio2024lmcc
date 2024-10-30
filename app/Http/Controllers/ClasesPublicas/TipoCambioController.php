<?php

namespace App\Http\Controllers\ClasesPublicas;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class TipoCambioController extends Controller
{

    public function obtenerTipoCambio(Request $req)
    {
        if ($req->ajax()) {
            $idSucursal = Session::get('idSucursal');
            $fechaTipoCambio = $req->fecha ? Carbon::createFromFormat('Y-m-d', $req->fecha)->format("Y-m-d") : Carbon::today()->format("Y-m-d");

            $tipoCambio = DB::table('tipo_cambio')
                ->whereDate('FechaCreacion', $fechaTipoCambio)
                ->where('IdSucursal', $idSucursal)
                ->first();

            return Response()->json($tipoCambio);
        }
    }

    public function guardarTipoCambio(Request $req)
    {
        if ($req->ajax()) {
            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
            $fecha = $req->fecha ? $req->fecha : Carbon::today();

            $tipoCambioCompras = $req->tipoCambioCompras;
            $tipoCambioVentas = $req->tipoCambioVentas;

            if ($tipoCambioCompras == null || $tipoCambioCompras == '') {
                return Response(['respuesta' => 'alert', 'mensaje' => 'Completar el campo de tipo de cambio de compras']);
            }

            if ($tipoCambioVentas == null || $tipoCambioVentas == '') {
                return Response(['respuesta' => 'alert', 'mensaje' => 'Completar el campo de tipo de cambio de ventas']);
            }

            $array = ['FechaCreacion' => $fecha, 'IdUsuario' => $idUsuario, 'IdSucursal' => $idSucursal, 'TipoCambioCompras' => $tipoCambioCompras, 'TipoCambioVentas' => $tipoCambioVentas, 'Estado' => 'E'];
            DB::table('tipo_cambio')->insert($array);

            return Response(['respuesta' => 'success', 'mensaje' => 'Se guardó tipo de cambio correctamente']);
        }
    }
}
