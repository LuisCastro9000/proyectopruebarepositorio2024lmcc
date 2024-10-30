<?php

namespace App\Http\Controllers\Administracion\Staff;

use App\Exports\ExcelProveedores;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class ProveedoresController extends Controller
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
        $proveedores = $loadDatos->getProveedores($idSucursal);
        // dd($proveedores);
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['proveedores' => $proveedores, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('administracion/staff/proveedores/proveedores', $array);
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
        $tipoDoc = $loadDatos->TipoDocumento();
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        // Nuevo codigo
        $listaBancos = $loadDatos->getListaBancos();
        // Fin

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $departamentos = $loadDatos->getDepartamentos();
        $array = ['tipoDoc' => $tipoDoc, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'departamentos' => $departamentos, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'listaBancos' => $listaBancos];
        return view('administracion/staff/proveedores/crearProveedor', $array);
    }

    public function store(Request $req)
    {
        try {
            $this->validateProveedor($req);
            $tipoDoc = $req->tipoDocumento;
            $numDoc = $req->nroDocumento;
            $idDep = $req->departamento;
            $idPro = $req->provincia;
            $idDis = $req->distrito;
            if ($tipoDoc == 1) {
                if (strlen($numDoc) != 8) {
                    return back()->with('error', 'El DNI tiene que tener 8 dígitos')->withInput();
                }
            }
            if ($tipoDoc == 2) {
                if (strlen($numDoc) != 11) {
                    return back()->with('error', 'El RUC tiene que tener 11 dígitos')->withInput();
                }
            }
            if ($tipoDoc == 3) {
                if (strlen($numDoc) != 12) {
                    return back()->with('error', 'El PASAPORTE tiene que tener 12 dígitos')->withInput();
                }
            }
            if ($idDep == 0) {
                return back()->with('error', 'Selecciona un departamento para el proveedor')->withInput();
            }
            if ($idPro == 0) {
                return back()->with('error', 'Selecciona una provincia para el proveedor')->withInput();
            }
            if ($idDis == 0) {
                return back()->with('error', 'Selecciona un distrito para el proveedor')->withInput();
            }

            if ($this->consultarDocBase($tipoDoc, $numDoc)) {
                return back()->with('error', 'El Numero de  Documento ya existe en nuestros registros')->withInput();
            }

            $loadDatos = new DatosController();
            $fecha = $loadDatos->getDateTime();
            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
            $nombre = $req->nombreComercial;
            $razonSocial = $req->razonSocial;
            $direccion = $req->direccion;
            $telefono = $req->telefono;
            $email = $req->email;
            $cuentaCorriente = $req->cuentaCorriente;
            $idListaBanco = $req->idListaBanco;
            $personaContacto = $req->personaContacto;
            $estado = 'E';
            $array = ['IdTipoDocumento' => $tipoDoc, 'IdSucursal' => $idSucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario, 'Nombre' => $nombre, 'RazonSocial' => $razonSocial, 'NumeroDocumento' => $numDoc, 'Direccion' => $direccion, 'Ubigeo' => $idDis, 'Telefono' => $telefono, 'Email' => $email, 'Estado' => $estado, 'CuentaCorriente' => $cuentaCorriente, 'IdListaBanco' => $idListaBanco, 'PersonaContacto' => $personaContacto];
            DB::table('proveedor')->insert($array);
            return redirect('administracion/staff/proveedores')->with('status', 'Se creo proveedor correctamente');
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
        $proveedor = $loadDatos->getProveedorSelect($id);
        $tipoDoc = $loadDatos->TipoDocumento();
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        // Nuevo codigo
        $listaBancos = $loadDatos->getListaBancos();
        // dd($listaBancos);
        // Fin

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $departamentos = $loadDatos->getDepartamentos();
        $provincias = $loadDatos->getProvincias($proveedor->IdDepartamento);
        $distritos = $loadDatos->getDistritos($proveedor->IdProvincia);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['proveedor' => $proveedor, 'tipoDoc' => $tipoDoc, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'departamentos' => $departamentos, 'provincias' => $provincias, 'distritos' => $distritos, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'listaBancos' => $listaBancos];
        return view('administracion/staff/proveedores/editarProveedor', $array);
    }

    public function update(Request $req, $id)
    {
        try {
            $this->validateProveedor($req);
            $tipoDoc = $req->tipoDocumento;
            $numDoc = $req->nroDocumento;
            $idDep = $req->departamento;
            $idPro = $req->provincia;
            $idDis = $req->distrito;
            if ($tipoDoc == 1) {
                if (strlen($numDoc) != 8) {
                    return back()->with('error', 'El DNI tiene que tener 8 dígitos');
                }
            }
            if ($tipoDoc == 2) {
                if (strlen($numDoc) != 11) {
                    return back()->with('error', 'El RUC tiene que tener 11 dígitos');
                }
            }
            if ($tipoDoc == 3) {
                if (strlen($numDoc) != 12) {
                    return back()->with('error', 'El PASAPORTE tiene que tener 12 dígitos');
                }
            }
            if ($idDep == 0) {
                return back()->with('error', 'Selecciona un departamento para el proveedor')->withInput();
            }
            if ($idPro == 0) {
                return back()->with('error', 'Selecciona una provincia para el proveedor')->withInput();
            }
            if ($idDis == 0) {
                return back()->with('error', 'Selecciona un distrito para el proveedor')->withInput();
            }

            if ($this->updateDocBase($tipoDoc, $numDoc, $id)) {
                return back()->with('error', 'El Numero de  Documento ya existe en nuestros registros')->withInput();
            }

            $loadDatos = new DatosController();
            $fecha = $loadDatos->getDateTime();
            $idUsuario = Session::get('idUsuario');
            $nombre = $req->nombre;
            $razonSocial = $req->razonSocial;
            $direccion = $req->direccion;
            $telefono = $req->telefono;
            $email = $req->email;
            $cuentaCorriente = $req->cuentaCorriente;
            $idListaBanco = $req->idListaBanco;
            $personaContacto = $req->personaContacto;
            $array = ['IdTipoDocumento' => $tipoDoc, 'FechaModificacion' => $fecha, 'IdModificacion' => $idUsuario, 'Nombre' => $nombre, 'RazonSocial' => $razonSocial, 'NumeroDocumento' => $numDoc, 'Direccion' => $direccion, 'Ubigeo' => $idDis, 'Telefono' => $telefono, 'Email' => $email, 'CuentaCorriente' => $cuentaCorriente, 'IdListaBanco' => $idListaBanco, 'PersonaContacto' => $personaContacto];

            DB::table('proveedor')
                ->where('IdProveedor', $id)
                ->update($array);

            return redirect('administracion/staff/proveedores')->with('status', 'Se actualizo proveedor correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function delete($id)
    {
        //dd($id);
        try {
            $array = ['Estado' => 'D'];
            DB::table('proveedor')
                ->where('IdProveedor', $id)
                ->update($array);

            return redirect('administracion/staff/proveedores')->with('status', 'Se elimino proveedor correctamente');

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

    public function exportExcel()
    {
        $idSucursal = Session::get('idSucursal');
        $loadDatos = new DatosController();
        $proveedores = $loadDatos->getProveedores($idSucursal);
        return Excel::download(new ExcelProveedores($proveedores), 'Reporte Proveedores.xlsx');
    }

    protected function validateProveedor(Request $request)
    {
        $this->validate($request, [
            'razonSocial' => 'required',
            'nroDocumento' => 'required|numeric',
            'direccion' => 'required',
            'departamento' => 'required',
            'provincia' => 'required',
            'distrito' => 'required',
            // 'cuentaCorriente' => 'numeric',
            // 'cuentaCorriente' => 'required_if:idListaBanco,==,1',
            // 'cuentaCorriente' => 'sometimes|required|cuentaCorriente',
        ]);
    }

    public function consultarDoc(Request $req)
    {
        if ($req->ajax()) {
            $idDoc = $req->idDoc;
            $numDoc = $req->numDoc;
            $loadDatos = new DatosController();

            if ($idDoc == 1) {
                $url = 'https://dniruc.apisperu.com/api/v1/dni/' . $numDoc . '?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImZhY3R1cmFjaW9uQGVhc3lmYWN0cGVydS5wZSJ9.45m3851f7G3SqNhT4MZ20YTZt6h4HD3YIf8pfwZgGJI';
            }

            if ($idDoc == 2) {
                $url = 'https://dniruc.apisperu.com/api/v1/ruc/' . $numDoc . '?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImZhY3R1cmFjaW9uQGVhc3lmYWN0cGVydS5wZSJ9.45m3851f7G3SqNhT4MZ20YTZt6h4HD3YIf8pfwZgGJI';
            }

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);

            $_result = curl_exec($curl);
            $result = json_decode($_result, true);

            curl_close($curl);

            if (!empty($result)) {
                $mensaje = 1;
                if ($idDoc == 1) {
                    $data = array(
                        0 => $mensaje,
                        1 => $idDoc,
                        2 => $numDoc,
                        3 => $result["apellidoPaterno"] . ' ' . $result["apellidoMaterno"] . ' ' . $result["nombres"],
                    );
                }
                if ($idDoc == 2) {
                    $arrayProvincias = [];
                    $arrayDistritos = [];
                    if ($result["departamento"] != null) {
                        $dep = DB::table('departamento')
                            ->where('Nombre', $result["departamento"])
                            ->first();
                        $departamento = $dep->IdDepartamento;

                        $arrayProvincias = $loadDatos->getProvincias($departamento);
                        $pro = DB::table('provincia')
                            ->where('Nombre', $result["provincia"])
                            ->first();
                        $provincia = $pro->IdProvincia;

                        $arrayDistritos = $loadDatos->getDistritos($provincia);
                        $dis = DB::table('distrito')
                            ->where('Nombre', $result["distrito"])
                            ->first();
                        $distrito = $dis->IdDistrito;

                    } else {
                        $departamento = null;
                        $provincia = null;
                        $distrito = null;
                    }
                    $data = array(
                        0 => $mensaje,
                        1 => $idDoc,
                        2 => $numDoc,
                        3 => $result["razonSocial"],
                        4 => $result["nombreComercial"],
                        5 => $result["direccion"],
                        6 => $departamento,
                        7 => array(0 => $provincia, 1 => $arrayProvincias),
                        8 => array(0 => $distrito, 1 => $arrayDistritos),
                    );
                }
                return Response($data);
            } else {
                $mensaje = 0;
                $data = array(
                    0 => $mensaje,
                );
                return Response($data);
            }
        }
    }

    /* private function consultarDocBase($idTipo, $doc)
    {

    $documento = DB::table('cliente')
    ->where('IdTipoDocumento', $idTipo)
    ->where('NumeroDocumento', $doc)
    ->where('Estado', 'E')
    ->first();

    if(count($documento) >= 1)
    {
    return TRUE;
    }
    else
    {
    return False;
    }
    } */

    private function consultarDocBase($idTipo, $doc)
    {
        $idSucursal = Session::get('idSucursal');
        $documento = DB::table('proveedor')
            ->where('IdTipoDocumento', $idTipo)
            ->where('NumeroDocumento', $doc)
            ->where('IdSucursal', $idSucursal)
            ->where('Estado', 'E')
            ->first();

        if (!empty($documento)) {
            return true;
        } else {
            return false;
        }
    }

    private function updateDocBase($idTipo, $doc, $update)
    {
        $idSucursal = Session::get('idSucursal');
        $documento = DB::table('proveedor')
            ->where('IdTipoDocumento', $idTipo)
            ->where('NumeroDocumento', $doc)
            ->where('IdProveedor', '!=', $update)
            ->where('IdSucursal', $idSucursal)
            ->where('Estado', 'E')
            ->first();

        if (!empty($documento)) {
            return true;
        } else {
            return false;
        }
    }
}
