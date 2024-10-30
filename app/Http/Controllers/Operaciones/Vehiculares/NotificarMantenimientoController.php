<?php

namespace App\Http\Controllers\Operaciones\Vehiculares;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Session;

class NotificarMantenimientoController extends Controller
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
        $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $atencion = DB::select('call sp_getUltimaAtencionVehicular(?)', array($idSucursal));

        // $resultado = $this->validarNotificacionVehiculo('POG-294', $idSucursal);
        // if ($resultado) {
        //    dd("existo");
        // }else {
        //     dd("No existo");
        // }

        $fechaHoy = carbon::now()->toDateTimeString();
        $listaSalidaVehicular = DB::table('notificacion_mantenimiento as nm')
            ->join('vehiculo', 'nm.IdVehiculo', '=', 'vehiculo.IdVehiculo')
            ->join('cliente', 'vehiculo.IdCliente', '=', 'cliente.IdCliente')
            ->select('vehiculo.*', 'cliente.*', 'nm.*', DB::raw("DATEDIFF('" . $fechaHoy . "',nm.FechaSalida) as DiasRestantes"))
            ->where('nm.IdSucursal', $idSucursal)
            ->get();
// dd($listaSalidaVehicular[0]->Periodo);

        // $a = 18 * 100 / $listaSalidaVehicular[0]->Periodo;
        // if (($listaSalidaVehicular[0]->DiasRestantes * 100 / $listaSalidaVehicular[0]->Periodo) >= 60 && ($listaSalidaVehicular[0]->DiasRestantes * 100 / $listaSalidaVehicular[0]->Periodo) <=90) {
        //     dd("Kilometros iniciales");
        // }
        // dd($a);

        $fechaHoy = CARBON::now();
        $fechaTaxi = $fechaHoy->addMonths(3)->toDateTimeString();
        // dd($fechaTaxi);

        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'listaSalidaVehicular' => $listaSalidaVehicular];

        return view('operaciones/vehiculares/notificar/notificarMantenimiento', $array);
    }

    // private function validarNotificacionVehiculo($placa, $idSucursal){
    //     $resultado = DB::table('notificacion_mantenimiento')
    //     ->where('PlacaVehiculo', $placa)
    //     ->where('IdSucursal', $idSucursal)
    //     ->exists();
    //     return $resultado;
    // }
}
