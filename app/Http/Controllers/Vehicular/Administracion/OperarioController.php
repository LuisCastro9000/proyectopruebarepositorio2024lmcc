<?php

namespace App\Http\Controllers\Vehicular\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use App\Traits\GestionarImagenesS3Trait;
use App\Traits\getFuncionesTrait;
use DB;
use Illuminate\Http\Request;
use Session;

class OperarioController extends Controller
{
    use getFuncionesTrait;
    use GestionarImagenesS3Trait;

    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

            $operarios = $loadDatos->getOperarios($idSucursal);

            $array = ['operarios' => $operarios, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
            return view('vehicular/operario/index', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
        }
    }

    public function create(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $roles = $loadDatos->getRolesOperarios();
            $array = ['roles' => $roles, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
            return view('vehicular/operario/crear', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
        }
    }

    public function store(Request $req)
    {
        try {
            $this->validateOperario($req);
            $loadDatos = new DatosController();
            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
            $fecha = $loadDatos->getDateTime();
            $nombres = $req->nombres;
            $rol = $req->rol;
            $array = ['FechaCreacion' => $fecha, 'IdUsuario' => $idUsuario, 'IdSucursal' => $idSucursal, 'IdRolOperario' => $rol, 'Nombres' => $nombres, 'Estado' => 'E'];
            DB::table('operario')->insert($array);

            // codigo para guardar Firma
            $rucEmpresa = $loadDatos->getRucEmpresa($idUsuario)->Ruc;
            $idOperarioNuevo = DB::table('operario')
                ->select('IdOperario')
                ->orderBy('IdOperario', 'desc')
                ->first();

            $imagenFirma = $req->inputImagenFirma;
            if ($imagenFirma != null) {
                $directorio = $this->generarUbicacionArchivo('FirmasDigitales/FirmasMecanicos/', "$rucEmpresa/");
                $nombreFirma = "firma-$idOperarioNuevo->IdOperario";
                $imagenFirma = $this->storeImagenFormatoBase64($imagenFirma, $imagenFirmaAnterior = null, $nombreFirma, $directorio, $accion = 'store');

                DB::table('operario')->where('IdOperario', $idOperarioNuevo->IdOperario)->update(['ImagenFirma' => $imagenFirma]);
            }
            // Fin

            return redirect('vehicular/administracion/operario')->with('status', 'Se creo operario correctamente');
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function edit(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $operario = $loadDatos->getOperarioSelect($id);
        $roles = $loadDatos->getRolesOperarios();
        $array = ['roles' => $roles, 'operario' => $operario, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('vehicular/operario/edit', $array);
    }

    public function update(Request $req, $id)
    {
        try {
            $this->validateOperario($req);
            $loadDatos = new DatosController();
            $idUsuario = Session::get('idUsuario');
            $nombres = $req->nombres;
            $rol = $req->rol;
            $fecha = $loadDatos->getDateTime();

            // codigo para guardar Firma
            $rucEmpresa = $loadDatos->getRucEmpresa($idUsuario)->Ruc;
            $imagenFirma = $req->inputImagenFirma;
            if ($imagenFirma != null) {
                $directorio = $this->generarUbicacionArchivo('FirmasDigitales/FirmasMecanicos/', "$rucEmpresa/");
                $nombreFirma = "firma-$id";
                $imagenFirma = $this->storeImagenFormatoBase64($imagenFirma, $req->inputImagenFirmaAnterior, $nombreFirma, $directorio, $accion = 'edit');

            } else {
                $imagenFirma = $req->inputImagenFirmaAnterior;
            }
            // Fin

            $array = ['FechaModificacion' => $fecha, 'IdUsuarioModificacion' => $idUsuario, 'IdRolOperario' => $rol, 'Nombres' => $nombres, 'ImagenFirma' => $imagenFirma];
            DB::table('operario')
                ->where('IdOperario', $id)
                ->update($array);

            return redirect('vehicular/administracion/operario')->with('status', 'Se actualizo operario correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            $array = ['Estado' => 'D'];
            DB::table('operario')
                ->where('IdOperario', $id)
                ->update($array);

            return redirect('vehicular/administracion/operario')->with('status', 'Se elimino operario correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    protected function validateOperario(Request $request)
    {
        $this->validate($request, [
            'nombres' => 'required',
            'rol' => 'required',
        ]);
    }
}
