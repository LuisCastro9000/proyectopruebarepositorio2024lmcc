<?php
namespace App\Http\Controllers\Admin\Administracion;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoCambioController extends Controller
{
    public function index(Request $req)
    {
        $sucursales = $this->getSucursales();
        $tipoCambio = null;
        return view('admin.administracion.tipoCambio.index', compact('sucursales', 'tipoCambio'));
    }

    public function getSucursales()
    {
        $sucursales = DB::table('sucursal')
            ->join('empresa', 'sucursal.CodigoCliente', '=', 'empresa.CodigoCliente')
            ->select('IdSucursal', 'sucursal.Nombre as NombreSucursal', 'sucursal.Direccion', 'sucursal.Ciudad', 'empresa.Nombre as NombreEmpresa', 'empresa.Ruc as RucEmpresa')
            ->whereNotIn('Estado', ['D'])
            ->get();
        return $sucursales;
    }

    public function obtenerTipoCambioDelDia(Request $req)
    {
        if ($req->ajax()) {
            $idSucursal = $req->idSucursal;
            $fechaTipoCambio = Carbon::today()->format("Y-m-d");

            $tipoCambio = DB::table('tipo_cambio as tc')
                ->join('sucursal', 'tc.IdSucursal', 'sucursal.IdSucursal')
                ->select('tc.*', 'sucursal.Nombre as NombreSucursal')
                ->whereDate('tc.FechaCreacion', $fechaTipoCambio)
                ->where('tc.IdSucursal', $idSucursal)
                ->first();

            $view = view('admin.administracion.tipoCambio._tablaTipoCambio', compact('tipoCambio'))->render();
            return response()->json(['respuesta' => 'success', 'tipoCambio' => $tipoCambio, 'view' => $view]);
        }
    }

    public function actualizartipoCambio(Request $req)
    {
        $valueCompras = $req->valueTipoCambioCompras;
        $valueVentas = $req->valueTipoCambioVentas;
        $fechaTipoCambio = Carbon::today()->format("Y-m-d");

        $array = [];

        if ($valueCompras != null) {
            $array['TipoCambioCompras'] = $valueCompras;
        }
        if ($valueVentas != null) {
            $array['TipoCambioVentas'] = $valueVentas;
        }

        DB::table('tipo_cambio')->where('IdSucursal', $req->idSucursal)->whereDate('FechaCreacion', $fechaTipoCambio)->update($array);

        return response()->json(['respuesta' => 'success']);

    }
}
