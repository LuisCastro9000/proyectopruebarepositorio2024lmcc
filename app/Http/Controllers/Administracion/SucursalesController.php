<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DB;
use Illuminate\Http\Request;
use Session;

class SucursalesController extends Controller
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
        $tipo = 1;
        if ($usuarioSelect->IdOperador == 1) {
            $sucursales = $loadDatos->getSucursalesFiltrado(1);
        } else {
            $sucursales = $loadDatos->getSucursales($usuarioSelect->CodigoCliente, $usuarioSelect->IdOperador);
        }
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        // dd($sucursales);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['sucursales' => $sucursales, 'permisos' => $permisos, 'usuarioSelect' => $usuarioSelect, 'modulosSelect' => $modulosSelect, 'tipo' => $tipo, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('administracion/sucursales/sucursales', $array);
    }

    public function filtrar(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $tipo = $req->tipoSucursal;
        $loadDatos = new DatosController();
        $sucursales = $loadDatos->getSucursalesFiltrado($tipo);
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['sucursales' => $sucursales, 'permisos' => $permisos, 'usuarioSelect' => $usuarioSelect, 'modulosSelect' => $modulosSelect, 'tipo' => $tipo, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('administracion/sucursales/sucursales', $array);
        //dd($tipo);
    }

    public function create(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $departamentos = $loadDatos->getDepartamentos();

        $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $array = ['permisos' => $permisos, 'datosEmpresa' => $datosEmpresa, 'modulosSelect' => $modulosSelect, 'departamentos' => $departamentos, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'idUsuario' => $idUsuario];
        return view('administracion/sucursales/crearSucursal', $array);
    }

    public function store(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                $idUsuario = Session::get('idUsuario');
            } else {
                Session::flush();
                return redirect('/')->with('out', 'Sesión de usuario Expirado');
            }

            // Nuevo codigo
            if ($req->ocultarDireccion == 'on') {
                $ocultarDireccion = 'E';
            } else {
                $ocultarDireccion = 'D';
            }
            // Fin
            $loadDatos = new DatosController();
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $this->validateSucursal($req);
            $idDep = $req->departamento;
            $idPro = $req->provincia;
            $idDis = $req->distrito;
            if ($idDep == 0) {
                return back()->with('error', 'Selecciona un departamento para la sucursal')->withInput();
            }
            if ($idPro == 0) {
                return back()->with('error', 'Selecciona una provincia para la sucursal')->withInput();
            }
            if ($idDis == 0) {
                return back()->with('error', 'Selecciona un distrito para la sucursal')->withInput();
            }

            if ($idUsuario == 1) {
                if ($req->exonerar == 'on') {
                    $exonerado = 1; //Activado
                } else {
                    $exonerado = 2; //Desactivado
                }
            } else {
                $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
                if ($datosEmpresa->Exonerado > 0) {
                    if ($req->exonerar == 'on') {
                        $exonerado = 1; //Activado
                    } else {
                        $exonerado = 2; //Desactivado
                    }
                } else {
                    $exonerado = 0;
                }
            }

            if ($usuarioSelect->IdOperador != 1) {
                $loadDatos = new DatosController();
                $totalSucursales = $loadDatos->getTotalSucursales($usuarioSelect->CodigoCliente);
                if ($usuarioSelect->TotalSucursales <= $totalSucursales->Total) {
                    return redirect('administracion/sucursales')->with('error', 'Ya no se pueden crear más sucursales, consulte con Soporte');
                } else {
                    $nombre = $req->nombre;
                    $codFiscal = $req->codigoFiscal;
                    $direccion = $req->direccion;
                    $telefono = $req->telefono;
                    $ciudad = $req->ciudad;
                    $estado = 'E';
                    $orden = $totalSucursales->Total + 1;
                    $array = ['Nombre' => $nombre, 'CodFiscal' => $codFiscal, 'Exonerado' => $exonerado, 'Ubigeo' => $idDis, 'Direccion' => $direccion, 'Ciudad' => $ciudad, 'Telefono' => $telefono, 'CodigoCliente' => $usuarioSelect->CodigoCliente, 'Principal' => 0, 'Orden' => $orden, 'Estado' => $estado, 'OcultarDireccion' => $ocultarDireccion];
                    DB::table('sucursal')->insert($array);

                    $sucursal = DB::table('sucursal')
                        ->where('CodigoCliente', $usuarioSelect->CodigoCliente)
                        ->orderBy('IdSucursal', 'desc')
                        ->first();
                    $idSucursal = $sucursal->IdSucursal;
                    $fecha = $loadDatos->getDateTime();

                    $array = ['IdTipoDocumento' => 1, 'IdSucursal' => $idSucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario, 'Nombre' => 'Varios', 'RazonSocial' => 'Varios', 'NumeroDocumento' => '99999999',
                        'Ubigeo' => '130101', 'Direccion' => '---', 'Telefono' => '999999999', 'Email' => 'correo@gmail.com', 'BandSaldo' => 0, 'SaldoCredito' => 0, 'Estado' => 'E'];
                    DB::table('cliente')->insert($array);

                    return redirect('administracion/sucursales')->with('status', 'Se creo sucursal correctamente');
                }
            } else {

                $nombre = $req->nombre;
                $codFiscal = $req->codigoFiscal;
                $direccion = $req->direccion;
                $telefono = $req->telefono;
                $ciudad = $req->ciudad;
                $estado = 'E';
                $array = ['Nombre' => $nombre, 'CodFiscal' => $codFiscal, 'Exonerado' => $exonerado, 'Ubigeo' => $idDis, 'Direccion' => $direccion, 'Ciudad' => $ciudad, 'Telefono' => $telefono, 'CodigoCliente' => '', 'Principal' => 1, 'Orden' => 1, 'Estado' => $estado, 'OcultarDireccion' => $ocultarDireccion];
                DB::table('sucursal')->insert($array);

                $sucursal = DB::table('sucursal')
                    ->where('CodigoCliente', $usuarioSelect->CodigoCliente)
                    ->orderBy('IdSucursal', 'desc')
                    ->first();
                $idSucursal = $sucursal->IdSucursal;
                $fecha = $loadDatos->getDateTime();

                $array = ['IdTipoDocumento' => 1, 'IdSucursal' => $idSucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario, 'Nombre' => 'Varios', 'RazonSocial' => 'Varios', 'NumeroDocumento' => '99999999',
                    'Ubigeo' => '130101', 'Direccion' => '---', 'Telefono' => '999999999', 'Email' => 'correo@gmail.com', 'BandSaldo' => 0, 'SaldoCredito' => 0, 'Estado' => 'E'];
                DB::table('cliente')->insert($array);

                return redirect('administracion/sucursales')->with('status', 'Se creo sucursal correctamente');
            }

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
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $sucursal = $this->getSucursalSelectEdit($id);
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);

        $departamentos = $loadDatos->getDepartamentos();
        $provincias = $loadDatos->getProvincias($sucursal->IdDepartamento);
        $distritos = $loadDatos->getDistritos($sucursal->IdProvincia);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $datosEmpresa = $loadDatos->getDatosEmpresa($sucursal->CodigoCliente);
        $inicioComprobante = $loadDatos->getInicioComprobante($id);
        $array = ['sucursal' => $sucursal, 'permisos' => $permisos, 'datosEmpresa' => $datosEmpresa, 'inicioComprobante' => $inicioComprobante, 'modulosSelect' => $modulosSelect, 'departamentos' => $departamentos, 'provincias' => $provincias, 'distritos' => $distritos, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'usuarioSelect' => $usuarioSelect];
        return view('administracion/sucursales/editarSucursal', $array);
    }

    public function update(Request $req, $id)
    {
        try {
            $this->validateSucursal($req);
            $nombre = $req->nombre;
            $codFiscal = $req->codigoFiscal;
            $direccion = $req->direccion;
            $telefono = $req->telefono;
            $ciudad = $req->ciudad;
            $idDep = $req->departamento;
            $idPro = $req->provincia;
            $idDis = $req->distrito;
            // Nuevo codigo
            if ($req->ocultarDireccion == 'on') {
                $ocultarDireccion = 'E';
            } else {
                $ocultarDireccion = 'D';
            }
            // Fin

            if ($idDep == 0) {
                return back()->with('error', 'Selecciona un departamento para la sucursal')->withInput();
            }
            if ($idPro == 0) {
                return back()->with('error', 'Selecciona una provincia para la sucursal')->withInput();
            }
            if ($idDis == 0) {
                return back()->with('error', 'Selecciona un distrito para la sucursal')->withInput();
            }
            if ($req->exonerar == 'on') {
                $exonerado = 1; //Activado
            } else {
                $exonerado = 0; //Desactivado
            }
            $array = ['Nombre' => $nombre, 'CodFiscal' => $codFiscal, 'Exonerado' => $exonerado, 'Ubigeo' => $idDis, 'Direccion' => $direccion, 'Ciudad' => $ciudad, 'Telefono' => $telefono, 'OcultarDireccion' => $ocultarDireccion];

            DB::table('sucursal')
                ->where('IdSucursal', $id)
                ->update($array);

            if ($req->IdOperadorUsuario == 1) {
                for ($i = 0; $i < count($req->serie); $i++) {
                    $arrayIC = ['Correlativo' => $req->correlativo[$i]];

                    DB::table('inicio_comprobantes')
                        ->where('IdSucursal', $id)
                        ->where('TipoComprobante', $req->tipoComprobante[$i])
                        ->update($arrayIC);
                }
            }

            return redirect('administracion/sucursales')->with('status', 'Se actualizo sucursal correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            $array = ['Estado' => 'D'];
            DB::table('sucursal')
                ->where('IdSucursal', $id)
                ->update($array);

            return redirect('administracion/sucursales')->with('status', 'Se elimino sucursal correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function consultarProvincias(Request $req)
    {
        if ($req->ajax()) {
            $idDep = $req->departamento;
            $loadDatos = new DatosController();
            $provincias = $loadDatos->getProvincias($idDep);
            return Response($provincias);
        }
    }

    public function consultarDistritos(Request $req)
    {
        if ($req->ajax()) {
            $idPro = $req->provincia;
            $loadDatos = new DatosController();
            $distritos = $loadDatos->getDistritos($idPro);
            return Response($distritos);
        }
    }

    protected function getSucursalSelectEdit($id)
    {
        try {
            $sucursal = DB::table('sucursal')
                ->join('distrito', 'sucursal.Ubigeo', '=', 'distrito.IdDistrito')
                ->join('provincia', 'distrito.IdProvincia', '=', 'provincia.IdProvincia')
                ->join('departamento', 'provincia.IdDepartamento', '=', 'departamento.IdDepartamento')
                ->select('sucursal.*', 'distrito.Nombre as Distrito', 'provincia.Nombre as Provincia', 'provincia.IdProvincia', 'departamento.Nombre as  Departamento', 'departamento.IdDepartamento')
                ->where('sucursal.IdSucursal', $id)
                ->first();
            return $sucursal;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    protected function validateSucursal(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required',
            'codigoFiscal' => 'required|min:4|max:4|regex:/[0-9]/',
            'direccion' => 'required',
            'departamento' => 'required',
            'telefono' => 'nullable|regex:/[0-9]/',
        ]);
    }
}
