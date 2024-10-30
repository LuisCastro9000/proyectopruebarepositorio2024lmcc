<?php

namespace App\Http\Controllers;

use App\Http\Controllers\DatosController;
use App\Traits\GestionarImagenesS3Trait;
use App\Traits\getFuncionesTrait;
use DB;
use Greenter\XMLSecLibs\Certificate\X509Certificate;
use Greenter\XMLSecLibs\Certificate\X509ContentType;
use Illuminate\Http\Request;
use Session;

class PerfilController extends Controller
{
    use getFuncionesTrait;
    use GestionarImagenesS3Trait;
    public function crearFirmaDigital(Request $req)
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
        // dd($usuarioSelect);
        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'usuarioSelect' => $usuarioSelect];
        return view('crearFirmaDigital', $array);
    }

    public function actualizarFirmaDigital(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        // codigo para guardar Firma
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $rucEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente)->Ruc;
        $imagenFirma = $req->inputImagenFirma;
        $imagenFirmaAnterior = $req->inputImagenFirmaAnterior;
        if ($imagenFirma != null) {
            $directorio = $this->generarUbicacionArchivo('FirmasDigitales/FirmasUsuarios/', "$rucEmpresa/");
            $nombreFirma = "firma-{$idUsuario}";
            $imagenFirma = $this->storeImagenFormatoBase64($imagenFirma, $imagenFirmaAnterior, $nombreFirma, $directorio, $accion = 'edit');
            DB::table('usuario')->where('IdUsuario', $idUsuario)->update(['ImagenFirma' => $imagenFirma]);

        }
        // Fin
        return redirect('/panel')->with('status', 'Se guardo la firma digital correctamente');
    }

    public function cambiarContrasena(Request $req)
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

        // NUEVO CODIGO VERIFICAR ACTUALIZACION DE CONTRASEÑA
        $contrasena = '*easyfactperu*';
        if (password_verify($contrasena, $usuarioSelect->Password)) {
            $contrasenaActualizada = 'true';
        } else {
            $contrasenaActualizada = 'false';
        }
        // FIN

        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'usuarioSelect' => $usuarioSelect, 'contrasenaActualizada' => $contrasenaActualizada];
        return view('cambiarContrasena', $array);
    }

    public function actualizarContrasena(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $radioPassword = trim($req->get('radioOption'));
        $this->validateContrasena($req, $radioPassword);
        if ($radioPassword == 'Password') {
            $ncontra = $req->ncontrasena;
            $rncontra = $req->rncontrasena;
        } else {
            $ncontra = $req->claveSupervisor;
            $rncontra = $req->claveSupervisorDuplicado;
        }
        if ($ncontra == $rncontra) {
            $passwordEncry = password_hash($ncontra, PASSWORD_DEFAULT);
            $array = [$radioPassword => $passwordEncry];
            DB::table('usuario')
                ->where('IdUsuario', $idUsuario)
                ->update($array);

            return redirect('/panel')->with('status', 'Se cambio contraseña correctamente');
        } else {
            return back()
                ->with('error', 'Las contraseñas deben ser iguales');
        }
    }

    public function cambiarSucursal(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $idSucursal = $req->sucursal;
        Session::put('idSucursal', $idSucursal);
        $idUsuario = Session::get('idUsuario');
        DB::table('usuario')
            ->where('IdUsuario', $idUsuario)
            ->update(['IdSucursal' => $idSucursal]);
        return redirect('/panel');
    }

    public function configurarEmpresa(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);

        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $departamentos = $loadDatos->getDepartamentos();
        $provincias = $loadDatos->getProvincias($datosEmpresa->IdDepartamento);
        $distritos = $loadDatos->getDistritos($datosEmpresa->IdProvincia);
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $array = ['usuarioSelect' => $usuarioSelect, 'permisos' => $permisos, 'datosEmpresa' => $datosEmpresa, 'modulosSelect' => $modulosSelect, 'departamentos' => $departamentos, 'provincias' => $provincias, 'distritos' => $distritos, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('administrarLogo', $array);
    }

    public function actualizarEmpresa(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $direccionEmpresa = '';
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $empreseSelect = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);

        $nombreEmpresa = $req->nombreEmpresa;
        $rucEmpresa = $req->rucEmpresa;
        $ciudadEmpresa = $req->ciudadEmpresa;
        $ventRapida = $req->selectVentaRapida;
        $clientId = $req->clientId;
        $clientSecret = $req->clientSecret;
        if ($ventRapida == 'on') {
            if ($req->radioOption != null) {
                $selecVentaRapida = $req->radioOption;
            } else {
                $selecVentaRapida = 1;
            }
        } else {
            $selecVentaRapida = 0;
        }

        $direcSucursal = DB::table('sucursal')
            ->select('Direccion')
            ->where('CodigoCliente', $usuarioSelect->CodigoCliente)
            ->first();

        if ($direcSucursal) {
            $direccionEmpresa = $direcSucursal->Direccion;
        }

        $descripcion = $req->descripcion;
        $telefonoEmpresa = $req->telefonoEmpresa;
        // $nombreComercial = htmlentities(htmlspecialchars($req->comercialEmpresa));
        $nombreComercial = $req->comercialEmpresa;
        $idDep = $req->departamento;
        $idPro = $req->provincia;
        $idDis = $req->distrito;

        if ($idDep == 0) {
            return back()->with('error', 'Selecciona un departamento para la empresa')->withInput();
        }
        if ($idPro == 0) {
            return back()->with('error', 'Selecciona una provincia para la empresa')->withInput();
        }
        if ($idDis == 0) {
            return back()->with('error', 'Selecciona un distrito para la empresa')->withInput();
        }
        $usuarioSol = $req->usuarioSol;
        $claveSol = $req->claveSol;

        if ($empreseSelect->Exonerado > 0) {
            if ($req->selectExonerar == 'on') {
                $exonerado = 1; //Activado
            } else {
                $exonerado = 2; //Desactivado
            }
        } else {
            $exonerado = 0;
        }

        if ($req->selectImagen) {
            $this->validateDatos($req, 2);
            $imagen = null;
            $array = ['Nombre' => $nombreEmpresa, 'Ruc' => $rucEmpresa, 'NombreComercial' => $nombreComercial, 'UsuarioSol' => $usuarioSol, 'ClaveSol' => $claveSol, 'Client_Id' => $clientId, 'Client_Secret' => $clientSecret, 'Ciudad' => $ciudadEmpresa, 'Ubigeo' => $idDis, 'Direccion' => $direccionEmpresa, 'Descripcion' => $descripcion, 'Telefono' => $telefonoEmpresa, 'Exonerado' => $exonerado, 'VentaRapida' => $selecVentaRapida, 'Imagen' => $imagen, 'PaginaWeb' => $req->paginaWeb, 'CorreoEmpresa' => $req->correoEmpresa, 'TipoImpresion' => $req->radioTipoImpresion];
        } else {
            if ($req->imagen != null) {
                $this->validateDatos($req, 1);
                // Almacenar la imganen en el S3 y obtener la URL
                $nombreImagen = "logo-{$rucEmpresa}";
                $directorio = $this->generarUbicacionArchivo('LogosEmpresas/');
                $imagen = $this->storeImagenFormatoFileS3($req->imagen, $imagenAnterior = $req->inputLogoAnterior, $nombreImagen, $directorio, $accion = 'edit');
                // Fin
                $array = ['Nombre' => $nombreEmpresa, 'Ruc' => $rucEmpresa, 'NombreComercial' => $nombreComercial, 'UsuarioSol' => $usuarioSol, 'ClaveSol' => $claveSol, 'Client_Id' => $clientId, 'Client_Secret' => $clientSecret, 'Ciudad' => $ciudadEmpresa, 'Ubigeo' => $idDis, 'Direccion' => $direccionEmpresa, 'Descripcion' => $descripcion, 'Telefono' => $telefonoEmpresa, 'Exonerado' => $exonerado, 'VentaRapida' => $selecVentaRapida, 'Imagen' => $imagen, 'PaginaWeb' => $req->paginaWeb, 'CorreoEmpresa' => $req->correoEmpresa, 'TipoImpresion' => $req->radioTipoImpresion];
            } else {
                $this->validateDatos($req, 2);
                $array = ['Nombre' => $nombreEmpresa, 'Ruc' => $rucEmpresa, 'NombreComercial' => $nombreComercial, 'UsuarioSol' => $usuarioSol, 'ClaveSol' => $claveSol, 'Client_Id' => $clientId, 'Client_Secret' => $clientSecret, 'Ciudad' => $ciudadEmpresa, 'Ubigeo' => $idDis, 'Direccion' => $direccionEmpresa, 'Descripcion' => $descripcion, 'Telefono' => $telefonoEmpresa, 'Exonerado' => $exonerado, 'VentaRapida' => $selecVentaRapida, 'PaginaWeb' => $req->paginaWeb, 'CorreoEmpresa' => $req->correoEmpresa, 'TipoImpresion' => $req->radioTipoImpresion];
            }
        }

        DB::table('empresa')
            ->where('CodigoCliente', $usuarioSelect->CodigoCliente)
            ->update($array);

        return redirect('/panel')->with('status', 'Se actualizaron datos de Empresa correctamente');
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

    public function importarCertificado(Request $req)
    {
        $bandera = 0;
        try {
            if ($req->session()->has('idUsuario')) {
                $idUsuario = Session::get('idUsuario');
            } else {
                Session::flush();
                return redirect('/')->with('out', 'Sesión de usuario Expirado');
            }
            $loadDatos = new DatosController();
            $idUsuario = Session::get('idUsuario');
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
            $archivo = $req->file;
            $password = $req->password;
            $pfx = file_get_contents($archivo);
            $certificate = new X509Certificate($pfx, $password);
            $pem = $certificate->export(X509ContentType::PEM);
            file_put_contents(__DIR__ . '/Servicios/CertificadoDigital/' . $empresa->Ruc . '.pem', $pem);
            $bandera = 1;
            /*$ok = openssl_pkcs12_read(file_get_contents($archivo), $resultado, $clave);
            if($ok === false) {
            die(openssl_error_string());
            }
            dd($resultado);
            $ok = openssl_pkey_export($resultado['pkey'], $resultado, $clave);
            if($ok === false) {
            die(openssl_error_string());
            }
            dd($ok);*/
            //$pfx = file_get_contents(__DIR__.'/CertificadoDigital/'.$empresa->Ruc.'.pfx');
            //Storage::put('/CertificadoDigital/'.$empresa->Ruc.'.pfx', $req->file);
            //Storage::disk('local')->put('/CertificadoDigital/'.$empresa->Ruc.'.pfx', $req->file());
            //file_put_contents(__DIR__.'/Servicios/CertificadoDigital/'.$empresa->Ruc.'.pfx', $req->file());
            //return redirect('/configurar-empresa')->with('status','Se subió certificado digital correctamente');
        } catch (Exception $ex) {
            echo $ex->getMessage();
        } finally {
            if ($bandera == 1) {
                return redirect('/configurar-empresa')->with('status', 'Se subió certificado digital correctamente');
            } else {
                return redirect('/configurar-empresa')->with('error', 'Error al importar certificado digital, verificar si su password es correcto');
            }
        }
    }

    protected function validateContrasena(Request $request, $descripcionRadio)
    {
        if ($descripcionRadio == 'Password') {
            $this->validate($request, [
                'ncontrasena' => 'required|max:30|min:6',
                'rncontrasena' => 'required|max:30|min:6',
            ]);
        } else {
            $this->validate($request, [
                'claveSupervisor' => 'required|max:30|min:6',
                'claveSupervisorDuplicado' => 'required|max:30|min:6',
            ]);
        }
    }

    protected function validateDatos($request, $tipo)
    {

        if ($tipo == 1) {
            $this->validate($request, [
                'nombreEmpresa' => 'required',
                'rucEmpresa' => 'required',
                'ciudadEmpresa' => 'required',
                'telefonoEmpresa' => 'nullable|regex:/[0-9]/',
                'imagen' => 'max:400',
            ]);
        } else {
            $this->validate($request, [
                'nombreEmpresa' => 'required',
                'rucEmpresa' => 'required',
                'ciudadEmpresa' => 'required',
                'telefonoEmpresa' => 'nullable|regex:/[0-9]/',

            ]);
        }
    }
}
