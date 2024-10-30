<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\DatosController;
use Session;
use DateTime;
use Carbon\Carbon;
use DB;
class panelVehicularController extends Controller
{
    public function index(Request $req) {
        
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');
        $idSucursal = Session::get('idSucursal');
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        //phpinfo();
        // dd($usuarioSelect);
        $permisos = $loadDatos->getPermisos($idUsuario);
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
        // dd($subniveles);
		
        $sucursales = $loadDatos->getSucursales($usuarioSelect->CodigoCliente, $usuarioSelect->IdOperador);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $empresa= $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $logo = $empresa->Imagen;
     
        $mensajeMostrar = $loadDatos->getMensajeAdmin();
        $array = [ 'usuarioSelect' => $usuarioSelect, 'sucursales' => $sucursales, 'logo' => $logo,
                  'permisos' => $permisos, 'modulosSelect' => $modulosSelect  ,'subpermisos'=>$subpermisos,  'subniveles'=>$subniveles   , 'mensajeMostrar' => $mensajeMostrar   
                ];
        return view('panelVehicular' , $array);
    }
}
    