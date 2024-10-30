<?php

namespace App\Http\Controllers\Vehicular\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Http\Request;
use Session;

class VehicularController extends Controller
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
        $fechas = $loadDatos->getFechaFiltro(5, null, null);
        $vehiculo = DB::select('call sp_getVehiculos(?)', array($idSucursal));

        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $array = ['vehiculos' => $vehiculo, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('vehicular/vehiculos', $array);
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
        $idSucursal = Session::get('idSucursal');

        $marcaVehiculo = $this->Marca($idSucursal);
        $modeloVehiculo = $this->Modelo($idSucursal);
        $tipoVehiculo = $this->Tipo($idSucursal);
        $clientes = $loadDatos->getClientes($idSucursal);

        $fecha = Carbon::today();
        $fechaFuturo = $fecha->startOfMonth()->startOfYear()->addYears(28)->format("Y-m-d");

        $arrayAnio = [];
        $date = new DateTime();
        $anio = intval($date->format("Y"));
        for ($i = 0; $i < 50; $i++) {
            array_push($arrayAnio, $anio - $i);
        }

        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $seguros = $loadDatos->getSeguros($idSucursal);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        // nuevo codigo
        $tipoDoc = $loadDatos->TipoDocumento();
        $departamentos = $loadDatos->getDepartamentos();
        // Fin

        $array = ['clientes' => $clientes, 'seguros' => $seguros, 'marcas' => $marcaVehiculo, 'modelos' => $modeloVehiculo, 'tipos' => $tipoVehiculo, 'fechaFuturo' => $fechaFuturo, 'arrayAnio' => $arrayAnio, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'tipoDoc' => $tipoDoc, 'departamentos' => $departamentos];
        return view('vehicular/crear', $array);
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
            $idSucursal = Session::get('idSucursal');
            $loadDatos = new DatosController();
            // dd($idSucursal);
            $this->validateVehiculo($req);
            $tipoVehiculo = $req->tipoVehiculo;
            if ($tipoVehiculo == 1) {
                if ($req->placa == '') {
                    return back()->with('error', 'Ingrese el número de placa')->withInput();
                } else {
                    $placa = strtoupper(trim($req->placa));
                }
            } else {
                if ($req->placaMoto == '') {
                    return back()->with('error', 'Ingrese el número de placa')->withInput();
                } else {
                    $placa = strtoupper(trim($req->placaMoto));
                }
            }
            $vowels = array("/", "@", ".", "_", "$", "&", "<", ">", "#", "?", "%", "!", "[", "]", "{", "}", "\"", "(", ")", "=");
            $placa = str_replace($vowels, "", $placa);

            if ($this->validatePlaca($placa, $idSucursal)) {
                return back()->with('error', 'El Formato de Placa no es el correcto ó La Placa ya existe en nuestros registros')->withInput();
            }

            $cliente = $req->cliente;
            $anio = $req->anio;
            $chasis = $req->chasis;
            $kilometraje = $req->kilometraje;
            $horometro = $req->horometro;
            $color = $req->color;
            $marca = $req->marca;
            $modelo = $req->modelo;
            $tipo = $req->tipo;
            $nota = $req->nota;
            $estado = $req->radioOpcion;
            $motor = $req->motor;
            $fechaSoat = $req->fechaSoat;
            $fechaRevTecnica = $req->fechaRevTecnica;
            $nroFlota = $req->flota;
            $fechaIngreso = $loadDatos->getDateTime();

            if ($req->seguro != null) {
                $seguro = $req->seguro;
            } else {
                $seguro = 1;
            }

            if ($req->fechaCertAnual != null) {
                $fechaCertAnual = $req->fechaCertAnual;
            } else {
                $fechaCertAnual = null;
            }

            if ($req->fechaPrueQuin != null) {
                $fechaPrueQuin = $req->fechaPrueQuin;
            } else {
                $fechaPrueQuin = null;
            }

            $array = ['IdSucursal' => $idSucursal, 'IdSeguro' => $seguro, 'TipoVehicular' => $tipoVehiculo, 'PlacaVehiculo' => $placa, 'ChasisVehiculo' => $chasis, 'HorometroInicial' => $horometro, 'KilometroInicial' => $kilometraje, 'Color' => $color, 'Anio' => $anio, 'Motor' => $motor, 'NumeroFlota' => $nroFlota,
                'FechaSoat' => $fechaSoat, 'FechaRevTecnica' => $fechaRevTecnica, 'CertificacionAnual' => $fechaCertAnual, 'PruebaQuinquenal' => $fechaPrueQuin, 'IdMarcaVehiculo' => $marca, 'IdModeloVehiculo' => $modelo, 'IdTipoVehiculo' => $tipo, 'NotaVehiculo' => $nota, 'IdCreacion' => $idUsuario, 'IdCliente' => $cliente, 'FechaIngreso' => $fechaIngreso, 'Estado' => $estado];
            DB::table('vehiculo')->insert($array);

            return redirect('vehicular/administracion/lista-vehiculos')->with('status', 'Se registro el vehiculo correctamente');

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
        $idSucursal = Session::get('idSucursal');
        $categoria = $loadDatos->getCategoriaSelect($id);
        $permisos = $loadDatos->getPermisos($idUsuario);
        $marcaVehiculo = $this->Marca($idSucursal);
        $modeloVehiculo = $this->Modelo($idSucursal);
        $tipoVehiculo = $this->Tipo($idSucursal);
        $clientes = $loadDatos->getClientes($idSucursal);

        $arrayAnio = [];
        $date = new DateTime();
        $anio = intval($date->format("Y"));
        for ($i = 0; $i < 50; $i++) {
            array_push($arrayAnio, $anio - $i);
        }

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $vehiculo = $this->vehiculoSelect($id);
        $fechaSoat = $vehiculo->FechaSoat;
        $fechaRevTecnica = $vehiculo->FechaRevTecnica;
        $fechaCertAnual = $vehiculo->CertificacionAnual;
        $fechaPrueQuin = $vehiculo->PruebaQuinquenal;
        $seguros = $loadDatos->getSeguros($idSucursal);

        $array = ['vehiculo' => $vehiculo, 'seguros' => $seguros, 'fechaCertAnual' => $fechaCertAnual, 'fechaPrueQuin' => $fechaPrueQuin, 'fechaSoat' => $fechaSoat, 'fechaRevTecnica' => $fechaRevTecnica, 'clientes' => $clientes, 'marcas' => $marcaVehiculo, 'modelos' => $modeloVehiculo, 'tipos' => $tipoVehiculo, 'arrayAnio' => $arrayAnio, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('vehicular/editar', $array);

    }

    public function update(Request $req, $id)
    {
        try {
            $this->validateVehiculo($req);
            $loadDatos = new DatosController();
            $fecha = $loadDatos->getDateTime();
            $idUsuario = Session::get('idUsuario');
            if ($req->placa == '' || $req->placa == null) {
                return back()->with('error', 'Se olvido de ingresar la nueva Placa.')->withInput();
            }
            $placa = strtoupper(trim($req->placa));
            $vowels = array("/", "@", ".", "_", "$", "&", "<", ">", "#", "?", "%", "!", "[", "]", "{", "}", "\"", "(", ")", "=");
            $placa = str_replace($vowels, "", $placa);

            if ($this->updatePlaca($placa, $id)) {
                return back()->with('error', 'El Formato de Placa no es el correcto ó La Placa ya existe en nuestros registros')->withInput();
            }

            $cliente = $req->cliente;
            $anio = $req->anio;
            $chasis = $req->chasis;
            $kilometraje = $req->kilometraje;
            $horometro = $req->horometro;
            $color = $req->color;
            $marca = $req->marca;
            $modelo = $req->modelo;
            $tipo = $req->tipo;
            $nota = $req->nota;
            $estado = $req->radioOpcion;
            $motor = $req->motor;
            $fechaSoat = $req->fechaSoat;
            $fechaRevTecnica = $req->fechaRevTecnica;
            $nroFlota = $req->flota;

            if ($req->seguro != null) {
                $seguro = $req->seguro;
            } else {
                $seguro = 1;
            }

            if ($req->fechaCertAnual != null) {
                $fechaCertAnual = $req->fechaCertAnual;
            } else {
                $fechaCertAnual = null;
            }

            if ($req->fechaPrueQuin != null) {
                $fechaPrueQuin = $req->fechaPrueQuin;
            } else {
                $fechaPrueQuin = null;
            }

            $array = ['IdSeguro' => $seguro, 'PlacaVehiculo' => $placa, 'ChasisVehiculo' => $chasis, 'HorometroInicial' => $horometro, 'KilometroInicial' => $kilometraje, 'Color' => $color, 'Anio' => $anio, 'Motor' => $motor, 'NumeroFlota' => $nroFlota, 'FechaSoat' => $fechaSoat, 'FechaRevTecnica' => $fechaRevTecnica, 'CertificacionAnual' => $fechaCertAnual, 'PruebaQuinquenal' => $fechaPrueQuin, 'IdMarcaVehiculo' => $marca, 'IdModeloVehiculo' => $modelo, 'IdTipoVehiculo' => $tipo, 'NotaVehiculo' => $nota, 'IdCliente' => $cliente, 'Estado' => $estado];

            DB::table('vehiculo')
                ->where('IdVehiculo', $id)
                ->update($array);

            return redirect('vehicular/administracion/lista-vehiculos')->with('status', 'Se actualizo Vehiculo correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            $array = ['Estado' => 0];
            DB::table('vehiculo')
                ->where('IdVehiculo', $id)
                ->update($array);

            return redirect('vehicular/administracion/lista-vehiculos')->with('status', 'Se elimino vehiculo correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    protected function vehiculo($idSucursal)
    {
        /* $vehiculo = DB::table('vehiculo')
        ->where('IdSucursal', $idSucursal)
        ->get(); */

        $vehiculo = DB::table('vehiculo as v')
            ->join('cliente as c', 'v.IdCliente', '=', 'c.IdCliente')
            ->join('marca_general as mg', 'mg.IdMarcaGeneral', '=', 'v.IdMarcaVehiculo')
            ->join('tipo_general as tg', 'tg.IdTipoGeneral', '=', 'v.IdTipoVehiculo')
            ->join('modelo_general as mog', 'mog.IdModeloGeneral', '=', 'v.IdModeloVehiculo')
            ->join('seguros as s', 's.IdSeguro', '=', 'v.IdSeguro')
            ->select('v.IdVehiculo', 'v.PlacaVehiculo', 'v.Estado', 'c.RazonSocial', 'mg.NombreMarca', 'mog.NombreModelo', 'tg.NombreTipo', 's.Descripcion as Seguro')
            ->where('v.IdSucursal', $idSucursal)
            ->orderBy('v.FechaIngreso', 'desc')
            ->get();
        return $vehiculo;
    }

    protected function vehiculoSelect($id)
    {
        $vehiculo = DB::table('vehiculo')
            ->where('IdVehiculo', $id)
            ->first();
        return $vehiculo;
    }

    protected function Marca($idSucursal)
    {
        $marca = DB::table('marca_general')
            ->where('IdSucursal', $idSucursal)
            ->where('UsoMarca', 1)
            ->where('Estado', 1)
            ->get();
        return $marca;
    }

    protected function Modelo($idSucursal)
    {
        $modelo = DB::table('modelo_general')
            ->where('IdSucursal', $idSucursal)
            ->where('Estado', 1)
            ->where('UsoModelo', 1)
            ->get();
        return $modelo;
    }

    protected function Tipo($idSucursal)
    {
        $tipo = DB::table('tipo_general')
            ->where('IdSucursal', $idSucursal)
            ->where('Estado', 1)
            ->where('UsoTipo', 1)
            ->get();
        return $tipo;
    }

    protected function validateVehiculo(Request $request)
    {
        $this->validate($request, [
            'cliente' => 'required|numeric',
            'anio' => 'required',
            'color' => 'required',
            'marca' => 'required|numeric',
            'modelo' => 'required|numeric',
            'tipo' => 'required|numeric',
        ]);
    }

    protected function validatePlaca($clPlaca, $sucursal)
    {
        $placa = str_replace("-", "", $clPlaca);

        $placa = DB::table('vehiculo')
            ->whereRaw("replace(PlacaVehiculo,'-','')= ?", [$placa])
            ->where('IdSucursal', $sucursal)
            ->where('Estado', 1)
            ->first();

        if ($placa) {
            return true;
        } else {
            return false;
        }
    }

    private function updatePlaca($clPlaca, $id)
    {
        $idSucursal = Session::get('idSucursal');
        $placa = str_replace("-", "", $clPlaca);

        $documento = DB::table('vehiculo')
            ->whereRaw("replace(PlacaVehiculo,'-','')= ?", [$placa])
            ->where('IdVehiculo', '!=', $id)
            ->where('IdSucursal', $idSucursal)
            ->where('Estado', 1)
            ->first();

        if ($documento) {
            return true;
        } else {
            return false;
        }
    }

    // Nuevas funciones
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

    public function consultarDoc(Request $req)
    {
        if ($req->ajax()) {
            $idDoc = $req->idDoc;
            $numDoc = $req->numDoc;
            $loadDatos = new DatosController();
            $longitud = strlen($numDoc);

            if ($idDoc == 3 || $idDoc == 4) {
                $mensaje = 0;
                $data = array(
                    0 => $mensaje,
                    1 => $idDoc,
                    2 => $numDoc,
                    3 => 'Servicio disponbile solo para DNI y RUC',
                );
                return Response($data);
            }

            if ($idDoc == 1) {
                if ($longitud == 8) {
                    $url = 'https://dniruc.apisperu.com/api/v1/dni/' . $numDoc . '?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImZhY3R1cmFjaW9uQGVhc3lmYWN0cGVydS5wZSJ9.45m3851f7G3SqNhT4MZ20YTZt6h4HD3YIf8pfwZgGJI';
                } else {
                    $mensaje = 0;
                    $data = array(
                        0 => $mensaje,
                        1 => $idDoc,
                        2 => $numDoc,
                        3 => 'Error en la busqueda, el DNI tiene que ser de 8 dígitos',
                    );
                    return Response($data);
                }
            }

            if ($idDoc == 2) {
                if ($longitud == 11) {
                    $url = 'https://dniruc.apisperu.com/api/v1/ruc/' . $numDoc . '?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImZhY3R1cmFjaW9uQGVhc3lmYWN0cGVydS5wZSJ9.45m3851f7G3SqNhT4MZ20YTZt6h4HD3YIf8pfwZgGJI';
                } else {
                    $mensaje = 0;
                    $data = array(
                        0 => $mensaje,
                        1 => $idDoc,
                        2 => $numDoc,
                        3 => 'Error en la busqueda, el RUC tiene que ser de 11 dígitos',
                    );
                    return Response($data);
                }
            }

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);

            $_result = curl_exec($curl);
            $result = json_decode($_result, true);

            curl_close($curl);
            $elementCount = count($result);

            if ($elementCount == 6 || $elementCount == 26) {
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
                        9 => $result["condicion"],
                        10 => $result["estado"],
                    );
                }
                return Response($data);
            } else {
                $mensaje = 0;
                $data = array(
                    0 => $mensaje,
                    1 => $idDoc,
                    2 => $numDoc,
                    3 => $result["message"],
                );
                return Response($data);
            }
        }
    }

    public function crearCliente(Request $req)
    {
        if ($req->ajax()) {
            $idSucursal = Session::get('idSucursal');
            if ($req->numDoc == '') {
                return Response('Número de Documento es obligatorio');
            }
            if ($req->razonSocial == '') {
                return Response('La Razón Social es obligatorio');
            }
            if ($req->departamento == 0) {
                return Response('Asignar departamento para el cliente');
            }
            if ($req->provincia == 0) {
                return Response('Asignar provincia para el cliente');
            }
            if ($req->distrito == 0) {
                return Response('Asignar distrito para el cliente');
            }
            $tipoDoc = $req->tipoDoc;
            $numDoc = $req->numDoc;
            if ($tipoDoc == 1) {
                if (strlen($numDoc) != 8) {
                    return Response('El DNI tiene que tener 8 dígitos');
                }
            }
            if ($tipoDoc == 2) {
                if (strlen($numDoc) != 11) {
                    return Response('El RUC tiene que tener 11 dígitos');
                }
            }
            if ($tipoDoc == 3 || $tipoDoc == 4) {
                if (strlen($numDoc) != 12) {
                    return Response('error', 'El CARNET DE EXTRANJERÍA o PASAPORTE tiene que tener 12 dígitos');
                }
            }

            if ($tipoDoc < 3) {
                $documento = DB::table('cliente')
                    ->where('IdTipoDocumento', $tipoDoc)
                    ->where('NumeroDocumento', $numDoc)
                    ->where('IdSucursal', $idSucursal)
                    ->where('Estado', 'E')
                    ->first();

                if (!empty($documento)) {
                    return Response('El Numero de  Documento ya existe en nuestros registros');
                }
            }

            $loadDatos = new DatosController();
            $fecha = $loadDatos->getDateTime();
            $idUsuario = Session::get('idUsuario');
            $nombre = $req->nombreComercial;
            if ($nombre == "" || $nombre == null) {
                $nombre = $req->razonSocial;
            }
            $razonSocial = $req->razonSocial;
            $direccion = $req->direccion;
            $telefono = $req->telefono;
            $email = $req->email;
            $estado = 'E';

            $array = ['IdTipoDocumento' => $tipoDoc, 'IdSucursal' => $idSucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario, 'Nombre' => $nombre, 'RazonSocial' => $razonSocial, 'NumeroDocumento' => $numDoc,
                'Ubigeo' => $req->distrito, 'Direccion' => $direccion, 'Telefono' => $telefono, 'Email' => $email, 'Estado' => $estado];
            DB::table('cliente')->insert($array);

            // $cliente = DB::table('cliente')
            //     ->where('Estado', 'E')
            //     ->orderBy('IdCliente', 'desc')
            //     ->get();

            $cliente = DB::table('cliente')
                ->where('Estado', 'E')
                ->where('NumeroDocumento', $numDoc)
                ->where('IdSucursal', $idSucursal)
                ->get();

            return Response($cliente);
        }
    }
    // fin
}
