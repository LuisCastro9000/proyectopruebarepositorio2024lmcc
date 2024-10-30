<?php

namespace App\Http\Controllers\Administracion\Staff;

use App\Exports\ExcelClientes;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use App\Imports\ExcelClientesImportacion;
use Carbon\Carbon;
use DB;
use DateTime;
use Excel;
use Illuminate\Http\Request;
use Session;

class ClientesController extends Controller
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
        //$clientes = $loadDatos->getClientes($idSucursal);
        $permisos = $loadDatos->getPermisos($idUsuario);
        $fecha = '';
        $fechaIni = '';
        $fechaFin = '';
        $ini = '';
        $fin = '';
        $band = '';

        $fechas = $loadDatos->getFechaFiltro(5, null, null);
        $clientes = DB::select('call sp_getClientes(?, ?, ?)', array($idSucursal, $fechas[0], $fechas[1]));

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['clientes' => $clientes, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'band' => $band, 'usuarioSelect' => $usuarioSelect];
        return view('administracion/staff/clientes/clientes', $array);
    }

    public function store(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $fecha = $req->fecha;
            $fechaIni = $req->fechaIni;
            $fechaFin = $req->fechaFin;
            $ini = '';
            $fin = '';
            $band = 1;

            if ($fecha == 9) {
                if ($fechaIni == null || $fechaFin == null) {
                    return back()->with('error', 'Completar las fechas para filtrar');
                }
                $dateIni = DateTime::createFromFormat('d/m/Y', $fechaIni);
                $dateFin = DateTime::createFromFormat('d/m/Y', $fechaFin);
                
                if(strtotime($dateIni->format('Y-m-d')) > strtotime($dateFin->format('Y-m-d'))){
                    return back()->with('error','La fecha Inicial no puede ser mayor que la Final');
                }
                /*if (strtotime($fechaIni) > strtotime($fechaFin)) {
                    return back()->with('error', 'La fecha Inicial no puede ser mayor que la Final');
                }*/
            }

            $ini = str_replace('/', '-', $fechaIni);
            $fin = str_replace('/', '-', $fechaFin);

            $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            $clientes = DB::select('call sp_getClientes(?, ?, ?)', array($idSucursal, $fechas[0], $fechas[1]));
            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

            // dd('datos');
            $array = ['clientes' => $clientes, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini' => $ini, 'fin' => $fin, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'band' => $band, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'usuarioSelect' => $usuarioSelect];
            return view('administracion/staff/clientes/clientes', $array);

        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
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

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $departamentos = $loadDatos->getDepartamentos();
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['tipoDoc' => $tipoDoc, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'departamentos' => $departamentos, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('administracion/staff/clientes/crearCliente', $array);
    }

    public function guardar(Request $req)
    {
        try {
            $this->validateCliente($req);
            $tipoDoc = $req->tipoDocumento;
            $numDoc = $req->nroDocumento;
            $idDep = $req->departamento;
            $idPro = $req->provincia;
            $idDis = $req->distrito;
            $radioCredito = $req->radioOpcion;

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
            if ($tipoDoc == 3 || $tipoDoc == 4) {
                if (strlen($numDoc) != 12) {
                    return back()->with('error', 'El CARNET DE EXTRANJERÍA o PASAPORTE tiene que tener 12 dígitos')->withInput();
                }
            }
            if ($idDep == 0) {
                return back()->with('error', 'Selecciona un departamento para el cliente')->withInput();
            }
            if ($idPro == 0) {
                return back()->with('error', 'Selecciona una provincia para el cliente')->withInput();
            }
            if ($idDis == 0) {
                return back()->with('error', 'Selecciona un distrito para el cliente')->withInput();
            }

            if ($this->consultarDocBase($tipoDoc, $numDoc)) {
                return back()->with('error', 'El Numero de  Documento ya existe en nuestros registros')->withInput();
            }

            if ($radioCredito == 1) {
                $saldoCredito = $req->saldoCredito;

                if ($saldoCredito < 1) {
                    return back()->with('error', 'Debe escribir un saldo o que este sea mayor a cero')->withInput();
                }
            } else {
                $saldoCredito = 0;
            }

            $loadDatos = new DatosController();
            $fecha = $loadDatos->getDateTime();
            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
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
                'Ubigeo' => $idDis, 'Direccion' => $direccion, 'Telefono' => $telefono, 'Email' => $email, 'BandSaldo' => $radioCredito, 'SaldoCredito' => $saldoCredito, 'Estado' => $estado, 'PersonaContacto' => $req->personaContacto];
            DB::table('cliente')->insert($array);

            return redirect('administracion/staff/clientes')->with('status', 'Se creo cliente correctamente');
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
        $cliente = $loadDatos->getClienteSelect($id);
        $tipoDoc = $loadDatos->TipoDocumento();
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $departamentos = $loadDatos->getDepartamentos();
        $provincias = $loadDatos->getProvincias($cliente->IdDepartamento);
        $distritos = $loadDatos->getDistritos($cliente->IdProvincia);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['cliente' => $cliente, 'tipoDoc' => $tipoDoc, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'departamentos' => $departamentos, 'provincias' => $provincias, 'distritos' => $distritos, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('administracion/staff/clientes/editarCliente', $array);
    }

    public function update(Request $req, $id)
    {
        try {
            $this->validateCliente($req);
            $tipoDoc = $req->tipoDocumento;
            $numDoc = $req->nroDocumento;
            $idDep = $req->departamento;
            $idPro = $req->provincia;
            $idDis = $req->distrito;
            $radioCredito = $req->radioOpcion;
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
            if ($tipoDoc == 3 || $tipoDoc == 4) {
                if (strlen($numDoc) != 12) {
                    return back()->with('error', 'El CARNET DE EXTRANJERÍA o PASAPORTE tiene que tener 12 dígitos');
                }
            }
            if ($idDep == 0) {
                return back()->with('error', 'Selecciona un departamento para el cliente')->withInput();
            }
            if ($idPro == 0) {
                return back()->with('error', 'Selecciona una provincia para el cliente')->withInput();
            }
            if ($idDis == 0) {
                return back()->with('error', 'Selecciona un distrito para el cliente')->withInput();
            }

            /*if($this->updateDocBase($tipoDoc, $numDoc, $id))
            {
            return back()->with('error', 'El Numero de  Documento ya existe en nuestros registros')->withInput();
            }*/

            if ($radioCredito == 1) {
                $saldoCredito = $req->saldoCredito;

                if ($saldoCredito < 1) {
                    return back()->with('error', 'Debe escribir un saldo o que este sea mayor a cero')->withInput();
                }
            } else {
                $saldoCredito = 0;
            }

            $loadDatos = new DatosController();
            $fecha = $loadDatos->getDateTime();
            $idUsuario = Session::get('idUsuario');
            $nombre = $req->nombreComercial;
            $razonSocial = $req->razonSocial;
            $direccion = $req->direccion;
            $telefono = $req->telefono;
            $email = $req->email;

            $array = ['IdTipoDocumento' => $tipoDoc, 'FechaModificacion' => $fecha, 'IdModificacion' => $idUsuario, 'Nombre' => $nombre, 'RazonSocial' => $razonSocial, 'NumeroDocumento' => $numDoc,
                'Ubigeo' => $idDis, 'Direccion' => $direccion, 'Telefono' => $telefono, 'BandSaldo' => $radioCredito, 'SaldoCredito' => $saldoCredito, 'Email' => $email, 'PersonaContacto' => $req->personaContacto];

            DB::table('cliente')
                ->where('IdCliente', $id)
                ->update($array);

            return redirect('administracion/staff/clientes')->with('status', 'Se actualizo cliente correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            $array = ['Estado' => 'D'];
            DB::table('cliente')
                ->where('IdCliente', $id)
                ->update($array);

            return redirect('administracion/staff/clientes')->with('status', 'Se elimino cliente correctamente');

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
                    //$url = 'https://dniruc.apisperu.com/api/v1/dni/45272540?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Im1hcmNvLm1hbGxtYTIwMUBnbWFpbC5jb20ifQ.tnR51gvdQl1DO4ovYJFjGlu9EFzwx1wSOA3Nd_BIxrg';
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
                    //$url = 'https://dniruc.apisperu.com/api/v1/ruc/'.$numDoc.'?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Im1hcmNvLm1hbGxtYTIwMUBnbWFpbC5jb20ifQ.tnR51gvdQl1DO4ovYJFjGlu9EFzwx1wSOA3Nd_BIxrg';
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
            //return Response([$elementCount]);
            //$result = 'http://ruc.aqpfact.pe/sunat/'.$numDoc;

            if ($elementCount == 5 || $elementCount == 26) {
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

    protected function validateCliente(Request $request)
    {
        $this->validate($request, [
            'tipoDocumento' => 'required',
            'nroDocumento' => 'required',
            'razonSocial' => 'required',
            'direccion' => 'required',
        ]);
    }

    public function exportExcel()
    {
        $idSucursal = Session::get('idSucursal');
        $loadDatos = new DatosController();
        $clientes = $loadDatos->getClientes($idSucursal);
        // dd($clientes[0]);
        return Excel::download(new ExcelClientes($clientes), 'Reporte Clientes.xlsx');
    }

    private function consultarDocBase($idTipo, $doc)
    {
        $idSucursal = Session::get('idSucursal');
        $documento = DB::table('cliente')
            ->where('IdTipoDocumento', $idTipo)
            ->where('NumeroDocumento', $doc)
            ->where('IdSucursal', $idSucursal)
            ->where('Estado', 'E')
            ->get();

        if (count($documento) >= 1) {
            return true;
        } else {
            return false;
        }
    }

    private function updateDocBase($idTipo, $doc, $update)
    {
        $idSucursal = Session::get('idSucursal');
        $documento = DB::table('cliente')
            ->where('IdTipoDocumento', $idTipo)
            ->where('NumeroDocumento', $doc)
            ->where('IdCliente', '!=', $update)
            ->where('IdSucursal', $idSucursal)
            ->where('Estado', 'E')
            ->get();

        if (!empty($documento)) {
            return true;
        } else {
            return false;
        }
    }

    // Nuevas funciones
    public function importarExcelClientes(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            if ($req->hasFile('datosExcelClientes')) {
                $idSucursal = Session::get('idSucursal');
                $datosExcelClientes = Excel::toCollection(new ExcelClientesImportacion, $req->datosExcelClientes);
                $i = 0;
                $arrayClientesNoGuardados = [];
                $arrayClientesDatosIncompletos = [];
                $mensajeClientesDatosIncompletos = '';
                $mensajeClientesNoGuardados = '';

                $arrayEncabezadoExcelFormato = ['NOMBRE', 'RAZÓN SOCIAL', 'TIPO DOCUMENTO', 'NÚMERO DE DOCUMENTO', 'UBIGEO', 'CELULAR / TELÉFONO', 'DIRECCIÓN', 'E-MAIL'];
                $arrayEncabezadoExcelRecibido = $datosExcelClientes[0][0];
                if (collect($arrayEncabezadoExcelFormato)->diff($arrayEncabezadoExcelRecibido)->isNotEmpty()) {
                    return back()->with('error', 'La importación de datos no Coincide con el Formato Establecido');
                }
                foreach ($datosExcelClientes[0] as $row) {
                    if ($i > 0) {
                        $nombreCliente = $row[0];
                        $razonSocial = $row[1];
                        $tipoDocumento = $row[2];
                        $numeroDocumento = $row[3];
                        $ubigeo = $row[4];
                        $celular = $row[5];
                        $direccion = $row[6];
                        $correo = $row[7];

                        if ($nombreCliente != null && $razonSocial != null && $tipoDocumento != null && $numeroDocumento != null && $ubigeo != null && $direccion != null) {
                            // Convertimos a minusculas palabras sin o con tildes
                            $tipoDocumento = str_replace(" ", "", mb_strtolower($tipoDocumento, 'UTF-8'));
                            // En caso tenga Tilde
                            $tipoDocumento = str_replace("í", "i", $tipoDocumento);
                            $date = Carbon::now();

                            $documentos = ['dni' => 1, 'ruc' => 2, 'carnetdeextranjeria' => 3, 'carnetextranjeria' => 3, 'pasaporte' => 4];
                            $idTipoDocumento = $documentos[$tipoDocumento];

                            $clientesDupicados = $this->getExistingCustomerData($numeroDocumento, $idSucursal);
                            if (count($clientesDupicados) >= 1) {
                                $arrayClientesNoGuardados[$i - 1] = $nombreCliente;
                                $mensajeClientesNoGuardados = 'Se importarón los clientes correctamente y se encontrarón Duplicados';
                            } else {
                                $array = ['Nombre' => $nombreCliente, 'RazonSocial' => $razonSocial, 'IdSucursal' => $idSucursal, 'NumeroDocumento' => $numeroDocumento, 'IdTipoDocumento' => $idTipoDocumento, 'Telefono' => $celular, 'FechaCreacion' => $date, 'IdCreacion' => $idUsuario, 'Ubigeo' => $ubigeo, 'Direccion' => $direccion, 'Email' => $correo, 'Estado' => 'E'];
                                DB::table('cliente')->insert($array);
                            }
                        } else {
                            $arrayClientesDatosIncompletos[$i - 1] = $nombreCliente;
                            $mensajeClientesDatosIncompletos = 'Se importarón los clientes correctamente y los clientes de las lista no se registrarón por falta de datos, revise el FORMATO';
                        }
                    }
                    $i++;
                    usleep(100000);
                }
                if (count($arrayClientesNoGuardados) >= 1 || count($arrayClientesDatosIncompletos) >= 1) {
                    return redirect('administracion/staff/clientes')->with('arrayClientesNoGuardados', collect($arrayClientesNoGuardados))->with('status', $mensajeClientesNoGuardados)->with('arrayDatosIncompletos', collect($arrayClientesDatosIncompletos))->with('errorDatosIncompletos', $mensajeClientesDatosIncompletos);

                } else {
                    return redirect('administracion/staff/clientes')->with('status', 'Se importarón los clientes correctamente');
                }
            } else {
                return redirect('administracion/staff/clientes')->with('error', 'No se ha seleccionado un archivo');
            }

        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    protected function getExistingCustomerData($numeroDocumento, $idSucursal)
    {
        $cliente = DB::table('cliente')
            ->where('IdSucursal', $idSucursal)
            ->where('Estado', 'E')
            ->where('NumeroDocumento', $numeroDocumento)
            ->get();
        return $cliente;
    }

    public function descargarFormatoExcel(Request $req)
    {
        return response()->download(public_path() . '/FormatoExcel/FormatoExcelClientes.xlsx');

    }
// Fin
}
