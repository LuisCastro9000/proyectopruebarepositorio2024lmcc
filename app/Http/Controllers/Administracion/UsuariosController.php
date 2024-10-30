<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use App\Jobs\SendEmailDocumentosDigitalesJob;
use App\Traits\GestionarImagenesS3Trait;
use App\Traits\getFuncionesTrait;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use PHPMailer\PHPMailer\PHPMailer;
use Session;
use Storage;
use ZipArchive;

class UsuariosController extends Controller
{
    use getFuncionesTrait;
    use GestionarImagenesS3Trait;
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $usuarios = $loadDatos->getUsuarios($usuarioSelect->IdOperador, $usuarioSelect->CodigoCliente);

            for ($i = 0; $i < count($usuarios);
                $i++) {
                $empresa = $loadDatos->getDatosEmpresa($usuarios[$i]->CodigoCliente);
                $usuarios[$i]->Empresa = $empresa->Nombre;
                $usuarios[$i]->RucEmpresa = $empresa->Ruc;
                $usuarios[$i]->NombrePlanSucripcion = $empresa->NombrePlanSucripcion;
            }
            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);
            // $mensajeMostrar = $loadDatos->getMensajeAdmin();
            // Nuevo Codigo
            if (Cache::has('mensaje')) {
                $mensaje = Cache::get('mensaje');
            } else {
                $mensaje = $loadDatos->getMensajeAdmin();
                Cache::put('mensaje', $mensaje);
            }
            // $mensaje = $this->getMensaje();
            $mensaje = collect($mensaje);
            $mensajeActualizacion = $mensaje->where('IdMensaje', 1)->first();
            $mensajeSunat = $mensaje->where('IdMensaje', 2)->first();
            // Fin
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $rubros = $loadDatos->getRubros();

            $suscripcionesSinIdSucursal = DB::table('suscripcion as sus')
                ->select('sus.FechaFinalContrato', 'sus.FechaFinalCDT', 'sus.IdSucursal', 'usuario.Nombre')
                ->join('usuario', 'sus.IdUsuario', 'usuario.IdUsuario')
                ->whereNull('sus.IdSucursal')
                ->limit(20)
                ->get();

            $array = ['usuarios' => $usuarios, 'permisos' => $permisos, 'usuarioSelect' => $usuarioSelect, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'mensajeActualizacion' => $mensajeActualizacion, 'mensajeSunat' => $mensajeSunat, 'rubros' => $rubros, 'suscripcionesSinIdSucursal' => $suscripcionesSinIdSucursal];
            return view('administracion/usuarios/usuarios', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function actualizarSuscripcionConIdSucursal()
    {
        $loadDatos = new DatosController();
        $usuariosClientes = DB::table('usuario')
            ->select('Email', 'IdUsuario', 'IdSucursal')
            ->where('usuario.Cliente', 1)
            ->orderBy('usuario.IdUsuario', 'desc')
            ->get();

        $suscripciones = DB::table('suscripcion')->whereNull('IdSucursal')->limit(20)->get();
        foreach ($suscripciones as $suscripcion) {
            $resultado = $usuariosClientes->where('IdUsuario', $suscripcion->IdUsuario)->first();
            DB::table('suscripcion')
                ->where('IdUsuario', $resultado->IdUsuario)
                ->whereNull('IdSucursal')
                ->update(['IdSucursal' => $resultado->IdSucursal]);
        }
        return Redirect::to('/administracion/usuarios');
    }

    // ------------------------------------
    public function enviarCorreoUsuario(Request $req, $idUsuario)
    {

        // dd('datos');

        $loadDatos = new DatosController();
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $rucEmpresa = $empresa->Ruc;
        $nombreEmpresa = $empresa->Nombre;

        $fechaHoy = Carbon::today()->toDateTimeString();
        $ventaSelect = $this->getDatosVentas($usuarioSelect, '2023-07-19');
        // dd($ventaSelect);

        $usuariosClientes = $loadDatos->getUsuariosClientes();
        $tamanoLote = 3;

        // foreach ($usuariosClientes->chunk(10) as $user) {
        //     if (!empty($ventaSelect)) {
        //         foreach ($user as $value) {
        //             SendEmailJob::dispatch($empresa)
        //                 ->delay(now()->addSeconds(5)); //
        //         }
        //     }
        // }
        SendEmailDocumentosDigitalesJob::dispatch($empresa)->delay(now()->addSeconds(5)); //
        dd('datos');

        $enviados = [];
        $arrayIdsPendientes = [];
        $arrayIdsEnviados = [];

        foreach ($usuariosClientes->chunk(10) as $user) {
            if (!empty($ventaSelect)) {
                foreach ($user as $value) {
                    $this->generarCorreo($user, $ventaSelect, $empresa, $nombreEmpresa);
                    sleep(5);
                }
                sleep(5);
            }

            $this->actualizarEstadoCompraEnviado($arrayIdsEnviados);
            $this->actualizarEstadoCompraPendiente($arrayIdsPendientes);
        }
        dd('datos');
    }

    public function getDatosVentas($usuario, $fechaHoy)
    {
        try {
            $ventasDeHoy = DB::table('ventas')
                ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                ->select('ventas.IdVentas', 'ventas.Serie', 'ventas.Numero', 'ventas.FechaCreacion', 'ventas.Estado', 'ventas.RutaCdr', 'ventas.RutaXml', 'ventas.IdTipoComprobante', 'sucursal.Direccion as Local', 'sucursal.IdSucursal', 'sucursal.Nombre as Sucursal', 'sucursal.Ciudad', 'sucursal.Telefono as TelfSucursal', 'sucursal.Principal', 'usuario.Nombre as Usuario')
                ->whereDate('ventas.FechaCreacion', $fechaHoy)
                ->where('usuario.CodigoCliente', $usuario->CodigoCliente)
                ->where('ventas.IdTipoComprobante', 2);

            $ventaAnterioresPendientes = DB::table('ventas')
                ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                ->select('ventas.IdVentas', 'ventas.Serie', 'ventas.Numero', 'ventas.FechaCreacion', 'ventas.Estado', 'ventas.RutaCdr', 'ventas.RutaXml', 'ventas.IdTipoComprobante', 'sucursal.Direccion as Local', 'sucursal.IdSucursal', 'sucursal.Nombre as Sucursal', 'sucursal.Ciudad', 'sucursal.Telefono as TelfSucursal', 'sucursal.Principal', 'usuario.Nombre as Usuario')
                ->whereDate('ventas.FechaCreacion', '<', '2023-07-20')
                ->where('usuario.CodigoCliente', $usuario->CodigoCliente)
                ->where('ventas.IdTipoComprobante', 2)
                ->where('ventas.Estado', 'Aceptada')
                ->where('ventas.EstadoEnvioDocumento', 'Pendiente');

            $ventasAceptadasXsunat = $ventasDeHoy->union($ventaAnterioresPendientes)->get();
            return $ventasAceptadasXsunat;

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function generarCorreo($usuario, $ventaSelect, $empresa, $nombreEmpresa)
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'mail.easyfactperu.pe';
        $mail->SMTPAuth = true;
        $mail->Username = 'facturacion@easyfactperu.pe';
        $mail->Debugoutput = 'html';
        $mail->Password = 'gV.S=o=Q,bl2';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->From = 'facturacion@easyfactperu.pe';
        $mail->FromName = 'EASYFACT PERÚ S.A.C.  - Facturación Electrónica';
        $mail->addAddress('pruebasoporte@autocontrol.pe', 'Documentos');
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Documentos Electrónicos(XML-CDR)';

        foreach ($ventaSelect as $venta) {
            if ($venta->IdTipoComprobante == 2) {
                // if ($this->adjuntarCrdXml($venta, $mail)) {
                //     array_push($arrayIdsEnviados, $venta->IdVentas);
                // } else {
                //     array_push($arrayIdsPendientes, $venta->IdVentas);
                // }
                $this->adjuntarCrdXml($venta, $mail);
            }
        }
        $mail->msgHTML('<table width="100%">'
            . '<tr>'
            . '<td style="border: 1px solid #000;">'
            . '<div align="center" style="background-color: #CCC">'
            . '<img width="150px" style="margin:15px" src="' . $empresa->Imagen . '">'
            . '</div>'
            . '<div style="margin-bottom:20px;margin-left:10px">'
            . '<p>Estimado(a),</p>'
            . '</div>'
            . '<div style="margin-bottom:20px;margin-left:10px">'
            . '<p>' . $empresa->Nombre . '</p>'
            . '</div>'
            . '<div style="margin-bottom:10px;margin-left:10px">'
            . '<p>Por encargo del emisor <span style="font-weight: bold;">' . $nombreEmpresa . '</span>, nos es grato informar que los documentos electrónicos ya se encuentran disponibles</p>'
            . '</div>'
            . '<hr style="border: 0.5px solid #000;">'
            . '<div style="margin-bottom:20px;margin-left:10px">'
            . '<p><span style="font-weight: bold;">Atentamente</span></p>'
            . '<p><span style="font-weight: bold;">AGRADECEREMOS NO RESPONDER ESTE CORREO</span></p>'
            . '<p><span style="font-weight: bold;">Si deseas ser Emisor Electrónico contáctanos o escríbenos al correo informes@easyfactperu.pe</span></p>'
            . '</div>'
            . '</td>'
            . '</tr>'
            . '</table>');
        $enviado = $mail->send();
        if ($enviado) {
            if (unlink($venta->Serie . '-' . $venta->Numero . '.xml')) {
                //dd("eliminado");
            }
            if (unlink($venta->Serie . '-' . $venta->Numero . '.zip')) {
                //dd("eliminado");
            }
            // array_push($enviados, $venta->IdVentas);

        } else {

        }

    }

    private function adjuntarCrdXml($venta, $mail)
    {
        if (Storage::disk('s3')->exists($venta->RutaCdr) && Storage::disk('s3')->exists($venta->RutaXml)) {
            $rutaCdrS3 = Storage::disk('s3')->get($venta->RutaCdr);
            file_put_contents($venta->Serie . '-' . $venta->Numero . '.zip', $rutaCdrS3);
            $mail->addAttachment($venta->Serie . '-' . $venta->Numero . '.zip');

            $rutaXmlS3 = Storage::disk('s3')->get($venta->RutaXml);
            file_put_contents($venta->Serie . '-' . $venta->Numero . '.xml', $rutaXmlS3);
            $mail->addAttachment($venta->Serie . '-' . $venta->Numero . '.xml');

            return true;
        }
        return false;
    }

    public function actualizarEstadoCompraPendiente($ids)
    {
        if (!empty($ids)) {
            DB::table('ventas')->whereIn('IdVentas', $ids)->update(['EstadoEnvioDocumento' => 'Pendiente']);
        }
    }

    public function actualizarEstadoCompraEnviado($ids)
    {
        DB::table('ventas')->whereIn('IdVentas', $ids)->update(['EstadoEnvioDocumento' => 'Enviado']);
    }

    // public function getDatosVentas($usuario, $fechaHoy)
// {
//     try {
//         $ventaDeHoy = DB::table('ventas')
//             ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
//             ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
//             ->select('ventas.IdVentas', 'ventas.Serie', 'ventas.Numero', 'ventas.FechaCreacion', 'ventas.Estado', 'ventas.RutaCdr', 'ventas.RutaXml', 'ventas.IdTipoComprobante', 'sucursal.Direccion as Local', 'sucursal.Nombre as Sucursal', 'sucursal.Ciudad', 'sucursal.Telefono as TelfSucursal', 'sucursal.Principal', 'usuario.Nombre as Usuario')
//             ->whereDate('ventas.FechaCreacion', $fechaHoy)
//             ->where('usuario.CodigoCliente', $usuario->CodigoCliente)
//             ->where('ventas.IdTipoComprobante', 2);
//         // ->get();

//         $ventaAnterioresPendientes = DB::table('ventas')
//             ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
//             ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
//             ->select('ventas.IdVentas', 'ventas.Serie', 'ventas.Numero', 'ventas.FechaCreacion', 'ventas.Estado', 'ventas.RutaCdr', 'ventas.RutaXml', 'ventas.IdTipoComprobante', 'sucursal.Direccion as Local', 'sucursal.Nombre as Sucursal', 'sucursal.Ciudad', 'sucursal.Telefono as TelfSucursal', 'sucursal.Principal', 'usuario.Nombre as Usuario')
//             ->whereDate('ventas.FechaCreacion', '<', $fechaHoy)
//             ->where('usuario.CodigoCliente', $usuario->CodigoCliente)
//             ->where('ventas.IdTipoComprobante', 2)
//             ->where('ventas.EstadoEnvioDocumento', 'Pendiente')
//             ->where([['ventas.RutaCdr', '<>', ''], ['ventas.RutaXml', '<>', '']])
//             ->get();
//         return $ventaAnterioresPendientes;
//         // return $ventaDeHoy->union($ventaAnterioresPendientes)->get();

//     } catch (Exception $ex) {
//         echo $ex->getMessage();
//     }

    // ------------------------------------

    // Nuevo funcion enviar archivos a usuarios retirados
    public function mostrarVistaListaDocumentos(Request $req, $idUsuarioRetirado)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $idSucursal = Session::get('idSucursal');
        $loadDatos = new DatosController();
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        // Datos del Usuario Retirado
        $usuarioRetirado = $loadDatos->getUsuarioSelect($idUsuarioRetirado);
        $correoUsuarioRetirado = $usuarioRetirado->Email;
        $rucEmpresa = $loadDatos->getDatosEmpresa($usuarioRetirado->CodigoCliente)->Ruc;

        // mostarndo los directorios

        $directoriosXanio = Storage::disk('s3')->directories('RespuestaSunat');
        $directoriosXmes = collect($directoriosXanio)->map(function ($item) {
            return Storage::disk('s3')->directories($item);
        });

        $directoriosXmes = $directoriosXmes->flatten();
        $directoriosXmes = collect($directoriosXmes)->map(function ($item) {
            $numeroMes = $this->enumerarMes($item);
            return (object) ['Dato' => substr(substr($item, 15), 0, 4) . $numeroMes, 'PathMes' => $item, 'Anio' => substr(substr($item, 15), 0, 4), 'NumeroMes' => $numeroMes, 'Mes' => substr($item, 20),
            ];
        });
        $directoriosXmes = $directoriosXmes->sortBy('Dato')->values();

        // dd($directoriosXmes->sortBy('Dato'));

        // $directoriosXmes = $directoriosXmes->sortBy('NumeroMes')->sortBy('Anio')->values();

        foreach ($directoriosXmes as $item) {
            $item->CantidadArchivos = collect(Storage::disk('s3')->allFiles($item->PathMes . '/' . $rucEmpresa))->count();
        }

        // dd($directoriosXmes);
        $directoriosXdocumentos = $directoriosXmes->map(function ($item) use ($rucEmpresa) {
            return Storage::disk('s3')->directories($item->PathMes . '/' . $rucEmpresa);
        });
        $directoriosXdocumentos = $directoriosXdocumentos->flatten();

        $directoriosXdocumentos = collect($directoriosXdocumentos)->map(function ($item) {
            return (object) ['PathDocumento' => $item];
        });
        foreach ($directoriosXdocumentos as $item) {
            $item->CantidadArchivos = collect(Storage::disk('s3')->allFiles($item->PathDocumento))->count();
        }

        if (Storage::disk('s3')->exists("DocumentosUsuarios/$rucEmpresa")) {
            $documentosZip = Storage::disk('s3')->files("DocumentosUsuarios/$rucEmpresa");
            $UrlDocumentosZip = collect($documentosZip)->map(function ($item) {
                return storage::disk('s3')->url($item);
            });
        } else {
            $UrlDocumentosZip = [];
        }

        if (Storage::disk('s3')->exists("DocumentosUsuarios/$rucEmpresa/Enviados")) {
            $documentosZip = Storage::disk('s3')->files("DocumentosUsuarios/$rucEmpresa/Enviados");
            $UrlEnviados = collect($documentosZip)->map(function ($item) {
                return storage::disk('s3')->url($item);
            });
        } else {
            $UrlEnviados = [];
        }

        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'directorioXanio' => $directoriosXanio, 'directoriosXmes' => $directoriosXmes, 'directoriosXdocumentos' => $directoriosXdocumentos, 'idUsuarioRetirado' => $idUsuarioRetirado, 'UrlDocumentosZip' => $UrlDocumentosZip, 'correoUsuarioRetirado' => $correoUsuarioRetirado, 'UrlEnviados' => $UrlEnviados];
        return view('administracion/usuarios/listaDocumentos', $array);

    }

    public function enumerarMes($item)
    {
        if (substr($item, 20) == 'Enero') {
            $numeroMes = 'A';
        } elseif (substr($item, 20) == 'Febrero') {
            $numeroMes = 'B';
        } elseif (substr($item, 20) == 'Marzo') {
            $numeroMes = 'C';
        } elseif (substr($item, 20) == 'Abril') {
            $numeroMes = 'D';
        } elseif (substr($item, 20) == 'Mayo') {
            $numeroMes = 'E';
        } elseif (substr($item, 20) == 'Junio') {
            $numeroMes = 'F';
        } elseif (substr($item, 20) == 'Julio') {
            $numeroMes = 'G';
        } elseif (substr($item, 20) == 'Agosto') {
            $numeroMes = 'H';
        } elseif (substr($item, 20) == 'Septiembre') {
            $numeroMes = 'I';
        } elseif (substr($item, 20) == 'Octubre') {
            $numeroMes = 'J';
        } elseif (substr($item, 20) == 'Noviembre') {
            $numeroMes = 'K';
        } else {
            $numeroMes = 'L';
        }
        return $numeroMes;
    }

    public function comprimirDocumentos(Request $req, $idUsuarioRetirado)
    {
        $loadDatos = new DatosController();
        $usuarioRetirado = $loadDatos->getUsuarioSelect($idUsuarioRetirado);
        $rucEmpresa = $loadDatos->getDatosEmpresa($usuarioRetirado->CodigoCliente)->Ruc;

        $path = "RespuestaSunat/2021/Julio/$rucEmpresa/FacturasBoletas/";
        $fileXml = Storage::disk('s3')->allFiles($path);
        $mes = $req->mes;
        $inputNombreArchivo = $req->nombreZip;
        if ($mes == null) {
            return redirect('/administracion/usuarios/lista-documentos/' . $idUsuarioRetirado)->with('error', 'No ha seleccionado ningún Documento');

        }
        if ($inputNombreArchivo == "") {
            return redirect('/administracion/usuarios/lista-documentos/' . $idUsuarioRetirado)->with('error', 'Debe ingresar el nombre del archivo Zip para proceder a comprimir');

        } else {
            $nombreZip = str_replace(' ', '', ucwords($inputNombreArchivo));
            if (!empty($req->anio)) {
                $documentosXmes = collect($mes)->map(function ($mes) use ($rucEmpresa) {
                    return Storage::disk('s3')->allFiles($mes . '/' . $rucEmpresa);
                });
            }
            $documentosXmes = $documentosXmes->flatten();
            $zipFile = new ZipArchive();
            $nameFile = $nombreZip . '.zip';

            if (!Storage::disk('s3')->exists("DocumentosUsuarios/$rucEmpresa")) {
                $carpetaZipUsuario = Storage::disk('s3')->makeDirectory('DocumentosUsuarios/' . $rucEmpresa, 'public');
            }

            $carpetaUsuariosRetirados = "DocumentosUsuarios/$rucEmpresa/" . $nameFile;
            if ($zipFile->open(storage_path($nameFile), ZIPARCHIVE::CREATE) == true) {

                foreach ($documentosXmes as $file) {
                    $nameXml = basename($file);
                    $zipFile->addFromString($nameXml, Storage::disk('s3')->get($file));
                }
                $zipFile->close();
            }
            Storage::disk('s3')->put($carpetaUsuariosRetirados, file_get_contents(storage_path($nameFile)), 'public');
            unlink(storage_path($nameFile));
            return redirect('/administracion/usuarios/lista-documentos/' . $idUsuarioRetirado)->with('succes', 'Se comprimio los archivos correctamente');
        }
    }

    public function enviarDocumentosZip(Request $req, $idUsuarioRetirado)
    {

        $loadDatos = new DatosController();

        $usuarioRetirado = $loadDatos->getUsuarioSelect($idUsuarioRetirado);
        $rucEmpresa = $loadDatos->getDatosEmpresa($usuarioRetirado->CodigoCliente)->Ruc;

        $urlDocumentos = $req->urlDocumentos;
        $correoUsuarioRetirado = $req->correoUsuarioRetirado;

        if (!empty($urlDocumentos)) {
            if (!Storage::disk('s3')->exists("DocumentosUsuarios/$rucEmpresa/Enviados")) {
                $carpetaZipUsuario = Storage::disk('s3')->makeDirectory('DocumentosUsuarios/' . $rucEmpresa . '/Enviados', 'public');
            }
            $urlParaEnviar = [];
            foreach ($urlDocumentos as $url) {
                $nameFile = basename($url);
                if (strpos($url, 'Enviados') != true && Storage::disk('s3')->exists(substr($url, 52))) {
                    Storage::disk('s3')->move(substr($url, 52), "DocumentosUsuarios/$rucEmpresa/Enviados/$nameFile");
                }
                array_push($urlParaEnviar, storage::disk('s3')->url("DocumentosUsuarios/$rucEmpresa/Enviados/$nameFile"));
            }
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'mail.easyfactperu.pe';
            $mail->SMTPAuth = true;
            $mail->Username = 'facturacion@easyfactperu.pe';
            $mail->Debugoutput = 'html';
            $mail->Password = 'gV.S=o=Q,bl2';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->From = 'facturacion@easyfactperu.pe';
            $mail->FromName = 'EASYFACT PERÚ S.A.C.  - Facturación Electrónica';
            $mail->addAddress($correoUsuarioRetirado, $correoUsuarioRetirado);
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Documentos Electronicos';

            $nombreEmpresa = 'EASYFACT PERÚ S.A.C. - Facturación Electrónica';

            $mensaje = '<table width="100%">'
                . '<tr>'
                . '<td style="border: 1px solid #000;">'
                . '<div style="margin-bottom:20px;margin-left:10px">'
                . '<p>Estimado(a),</p>'
                . '</div>'
                . '<div style="margin-bottom:10px;margin-left:10px">'
                . '<p>Por encargo del emisor <span style="font-weight: bold;">' . $nombreEmpresa . '</span>, nos es grato informar que los documentos electrónicos ya se encuentra disponible con los siguientes enlaces.</p>'
                . '<p>Recuerde que los enlaces estarán disponibles como máximo 30 días.</p>'
                . '</div>'
                . '<hr style="border: 0.5px solid #000;">'
                . '<div style="margin-bottom:20px;margin-left:30px"><br><br>';

            for ($i = 0; $i < count($urlParaEnviar); $i++) {
                $mensaje .= '<a style="font-weight: bold;" href=' . $urlParaEnviar[$i] . '>' . $urlParaEnviar[$i] . '</a><br><br>';
            }
            $mensaje .= '</div>'
                . '<hr style="border: 0.5px solid #000;">'
                . '<div style="margin-bottom:20px;margin-left:10px">'
                . '<p><span style="font-weight: bold;">Atentamente</span></p>'
                . '<p><span style="font-weight: bold;">AGRADECEREMOS NO RESPONDER ESTE CORREO</span></p>'
                . '<p><span style="font-weight: bold;">Si deseas ser Emisor Electrónico contáctanos o escríbenos al correo informes@easyfactperu.pe</span></p>'
                . '</div>'
                . '</td>'
                . '</tr>'
                . '</table>';
            $mail->msgHTML($mensaje);

            $enviado = $mail->send();
            if ($enviado) {
                return redirect("administracion/usuarios/lista-documentos/$idUsuarioRetirado")->with('succes', 'Correo enviado correctamente');
            } else {
                return redirect("administracion/usuarios/lista-documentos/$idUsuarioRetirado")->with('error', 'Correo no enviado, intentelo nuevamente');
            }
        }
    }
    // Fin

    private function extraerFormatoLogin($login)
    {
        $patron = "/\.[a-z.@]+$/";
        preg_match($patron, $login, $match);
        $loginUser = $match[0];
        return $loginUser;
    }

    public function create(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $idSucursal = Session::get('idSucursal');
        $loadDatos = new DatosController();
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        // Extraer el correo del administrador
        $loginUser = $this->extraerFormatoLogin($usuarioSelect->Login);
        $login = substr($loginUser, 1);
        // $expreReg = "[a-z]\.$login$";
        $expreReg = "^[a-zA-Z0-9]+\.$login$";

        // Fin

        $sucursales = $loadDatos->getSucursales($usuarioSelect->CodigoCliente, $usuarioSelect->IdOperador);
        $rubros = $loadDatos->getRubros();
        $operadores = $loadDatos->getRoles();
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $planesSuscripcion = DB::table('planes_suscripcion')->get();

        // Nuevo codigo Modulos
        $modulosDelSistema = $loadDatos->getModulos();
        // Fin

        $array = ['sucursales' => $sucursales, 'operadores' => $operadores, 'permisos' => $permisos, 'usuarioSelect' => $usuarioSelect, 'rubros' => $rubros, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'loginUser' => $loginUser, 'expreReg' => $expreReg, 'planesSuscripcion' => $planesSuscripcion, 'modulosDelSistema' => $modulosDelSistema];
        return view('administracion/usuarios/crearUsuario', $array);
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
            $nombre = $req->nombre;
            $operador = $req->operador;
            $sucursal = $req->sucursal;
            $dni = $req->dni;
            $email = $req->email;
            if ($sucursal == null) {
                return back()->with('error', 'Por favor elegir sucursal para este usuario');
            }

            $existe = $loadDatos->getVerificarUsuario($req->login);
            if ($existe->usuarioTotal > 0) {
                return back()->with('error', 'Login de usuario ya existe, por favor probar con otro.');
            }
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);

            $usuarios = $loadDatos->getUsuarios($usuarioSelect->IdOperador, $usuarioSelect->CodigoCliente);
            $direccion = '';
            $opcionFactura = ($idUsuario == 1 ? $req->radioOpcion : 0);
            $password = '*easyfactperu*';
            $password2 = '*xsecret23*';
            $passwordEncry = password_hash($password, PASSWORD_DEFAULT);
            $password2Encry = password_hash($password2, PASSWORD_DEFAULT);
            // $ClaveDeComprobacion = 'xsecret23';
            // $ClaveDeComprobacionEncry = password_hash( $ClaveDeComprobacion, PASSWORD_DEFAULT );
            $telefono = $req->telefono;
            $direccion = $req->direccion;
            $login = $req->login;
            //$rubro = $req->rubro;
            $estado = 'E';
            $foto = 'https://autocontrol1xz.s3.us-west-2.amazonaws.com/1686665216.jpg';

            if ($req->IdOperadorUsuario == 1) {
                $codigoCliente = time();
                $cliente = 1;
                $orden = 1;
                $totalUsuarios = $req->totalUsuarios;
                $totalsucursales = $req->totalSucursales;
            } else {
                $codigoCliente = $usuarioSelect->CodigoCliente;
                $cliente = 0;
                $totalUsuarios = $usuarioSelect->TotalUsuarios;
                $totalsucursales = $usuarioSelect->TotalSucursales;
                $orden = $loadDatos->getTotalUsuarios($codigoCliente);
                $orden = count($orden) + 1;
            }

            if ($req->editPrecio != null) {
                if ($req->editPrecio == 'on') {
                    $editarPrecio = 1;
                } else {
                    $editarPrecio = 0;
                }
            } else {
                $editarPrecio = 0;
            }

            // Nuevo codigo para activar paquete promocional y importarExcelClientes
            if ($req->activarPaquetePromo != null) {
                if ($req->activarPaquetePromo == 'on') {
                    $activarPaquetePromo = 1;
                } else {
                    $activarPaquetePromo = 0;
                }
            } else {
                $activarPaquetePromo = 0;
            }

            if ($req->activarImportacionExcelClientes != null) {
                if ($req->activarImportacionExcelClientes == 'on') {
                    $opcionImportarExcel = 1;
                } else {
                    $opcionImportarExcel = 0;
                }
            } else {
                $opcionImportarExcel = 0;
            }
            // Fin
            if ($usuarioSelect->IdOperador != 1) {
                try {
                    DB::beginTransaction();
                    if ($usuarioSelect->TotalUsuarios <= count($usuarios) + 1) {
                        return redirect('administracion/usuarios')->with('error', 'Ya no se pueden crear más usuarios, consulte con Soporte');
                    } else {

                        // Validar login
                        // $login = substr($req->formatoLogin, 1);
                        // $expreReg = "/[a-z]\.$login/";
                        // if (!preg_match($expreReg, $req->login)) {
                        //     return redirect('administracion/usuarios/create')->with('error', 'Login de usuario no cumple con el Formato NOMBREUSUARIO' . $req->formatoLogin);
                        // }
                        $isValidoFormatoLogin = $this->isValidoFormatoLogin($usuarioSelect->Login, $login);
                        if (!$isValidoFormatoLogin) {
                            return redirect('administracion/usuarios/create')->with('error', 'Login de usuario no cumple con el Formato ==> AQUI NOMBRE USUARIO' . $req->formatoLogin);
                        }
                        // Fin

                        if ($idSucursal != 1) {
                            $cod_cliente = DB::table('sucursal')
                                ->select('CodigoCliente')
                                ->where('IdSucursal', $idSucursal)
                                ->first();

                            $usuarioPrin = DB::table('usuario')
                                ->select('Opcionfactura', 'CodigoCliente', 'IdUSuario')
                                ->where('CodigoCliente', $cod_cliente->CodigoCliente)
                                ->first();

                            if ($usuarioPrin->Opcionfactura >= 1) {
                                $opcionFactura = $usuarioPrin->Opcionfactura;
                            }
                        }
                        $ClaveDeComprobacion = '';
                        $operacionGratuita = $req->has('operacionGratuita') ? 1 : 0;

                        $array = ['IdOperador' => $operador, 'IdSucursal' => $sucursal, 'Nombre' => $nombre, 'DNI' => $dni, 'Direccion' => $direccion,
                            'Telefono' => $telefono, 'Email' => $email, 'Login' => $login, 'OpcionFactura' => $opcionFactura, 'EditarPrecio' => $editarPrecio, 'CodigoProducto' => 0, 'Password' => $passwordEncry, 'Password2' => $password2Encry, 'Foto' => $foto, 'CodigoCliente' => $codigoCliente, 'Cliente' => $cliente, 'Orden' => $orden, 'TotalUsuarios' => $totalUsuarios, 'TotalSucursales' => $totalsucursales, 'Estado' => $estado, 'ClaveDeComprobacion' => $ClaveDeComprobacion, 'FechaCreacion' => date("Y-m-d H:i:s"), 'DescuentoMaximoSoles' => $req->descuentoMaximoSoles, 'DescuentoMaximoDolares' => $req->descuentoMaximoDolares, 'OpcionOperacionGratuita' => $operacionGratuita];
                        $idUsuarioNuevo = DB::table('usuario')->insertGetId($array);

                        // codigo para guardar Firma
                        $arrayDatosActualizar = [];
                        $imagenFirma = $req->inputImagenFirma;
                        if ($imagenFirma != null) {
                            // Almacenar la imganen en el S3 y obtener la URL
                            $rucEmpresa = $loadDatos->getRucEmpresa($idUsuario)->Ruc;
                            $directorio = "FirmasDigitales/FirmasUsuarios/{$rucEmpresa}/";
                            $nombreImagen = "firma-{$idUsuarioNuevo}-" . date('His');
                            $urlImagenFirma = $this->storeImagenFormatoBase64($imagenFirma, $imagenAnterior = null, $nombreImagen, $directorio, $accion = 'store');
                            $arrayDatosActualizar['ImagenFirma'] = $urlImagenFirma;
                        }
                        if ($req->foto != null) {
                            // Almacenar la imganen en el S3 y obtener la URL
                            $rucEmpresa = $loadDatos->getRucEmpresa($idUsuario)->Ruc;
                            $nombreImagen = "perfil-{$idUsuarioNuevo}-" . date('His');
                            $directorio = "ImagenesPerfiles/{$rucEmpresa}/";
                            $foto = $this->storeImagenFormatoFileS3($req->foto, $imagenAnterior = null, $nombreImagen, $directorio, $accion = 'store');
                            $arrayDatosActualizar['Foto'] = $foto;
                        }
                        // Verificar si hay datos para actualizar antes de realizar la consulta
                        if (!empty($arrayDatosActualizar)) {
                            DB::table('usuario')->where('IdUsuario', $idUsuarioNuevo)->update($arrayDatosActualizar);
                        }
                        // Fin
                    }
                    DB::commit();
                    return redirect('administracion/permisos/lista-permisos/' . $idUsuarioNuevo)->with('usuarioCreado', 'correcto');
                } catch (\Exception $e) {
                    DB::rollback();
                    return back()->with('error', 'Ocurrio un error, por favor No INSISTA EN LA CREACIÓN, proceda a comunicarse con el Área de Soporte.');
                }

            } else {
                try {
                    DB::beginTransaction();
                    if ($req->codigoProducto == 'on') {
                        $selectCodigo = 1;
                    } else {
                        $selectCodigo = 0;
                    }
                    $ClaveDeComprobacion = 'super*admin';
                    $ClaveDeComprobacionEncry = password_hash($ClaveDeComprobacion, PASSWORD_DEFAULT);
                    $array = ['IdOperador' => $operador, 'IdSucursal' => $sucursal, 'Nombre' => $nombre, 'DNI' => $dni, 'Direccion' => $direccion,
                        'Telefono' => $telefono, 'Email' => $email, 'Login' => $login, 'OpcionFactura' => $opcionFactura, 'EditarPrecio' => $editarPrecio, 'CodigoProducto' => $selectCodigo, 'Password' => $passwordEncry, 'Password2' => $password2Encry, 'Foto' => $foto, 'CodigoCliente' => $codigoCliente, 'Cliente' => $cliente, 'Orden' => $orden, 'TotalUsuarios' => $totalUsuarios, 'TotalSucursales' => $totalsucursales, 'Estado' => $estado, 'ClaveDeComprobacion' => $ClaveDeComprobacionEncry, 'ActivarPaquetePromo' => $activarPaquetePromo, 'OpcionImportarExcel' => $opcionImportarExcel, 'FechaCreacion' => date("Y-m-d H:i:s")];
                    DB::table('usuario')->insert($array);

                    DB::table('sucursal')
                        ->where('IdSucursal', $sucursal)
                        ->update(['CodigoCliente' => $codigoCliente]);

                    if ($req->exonerar == 'on') {
                        $exonerado = 1;
                    } else {
                        $exonerado = 0;
                    }

                    DB::table('empresa')->insert(['Nombre' => 'Nombre Empresa', 'Ruc' => '20000000001', 'UsuarioSol' => 'Usuario Sol', 'ClaveSol' => 'Clave Sol', 'Ciudad' => 'Ciudad', 'Ubigeo' => '130101', 'Direccion' => 'Mi dirección #1234', 'Telefono' => '999999999', 'Exonerado' => $exonerado, 'CodigoCliente' => $codigoCliente, 'IdRubro' => $req->rubro, 'IdPlanSuscripcion' => $req->selectPlanSuscripcion]);

                    $idUsuarioNuevo = DB::table('usuario')
                        ->orderBy('IdUsuario', 'desc')
                        ->first();

                    $fecha = Carbon::now()->toDateTimeString();
                    $this->setPermisosAdministrador($idUsuarioNuevo->IdUsuario, 'E', $req->selectPlanSuscripcion, $fecha);
                    $this->setSubPermisosAdministrador($idUsuarioNuevo->IdUsuario, 'E', $req->selectPlanSuscripcion, $fecha);
                    $this->setSubNivelAdministrador($idUsuarioNuevo->IdUsuario, 'E', $req->selectPlanSuscripcion, $fecha);

                    // OTORGANGO PERMISOS BOTONES ADMINISRATIVOS
                    $this->setPermisosBotonesAdministrador($idUsuarioNuevo->IdUsuario, $req->selectPlanSuscripcion, $fecha);
                    $this->setPermisosSubBotonesAdministrador($idUsuarioNuevo->IdUsuario, $req->selectPlanSuscripcion, $fecha);

                    // Codigo para asignar Modulos
                    if ($req->modulos != null) {
                        foreach ($req->modulos as $modulo) {
                            $array = ['IdUsuario' => $idUsuarioNuevo->IdUsuario, 'IdModulo' => $modulo];
                            DB::table('usuario_modulo')->insert($array);
                        }
                    }
                    // fin
                    // Codigo para Guardar suscripcion
                    $fechaContrato = $req->fechaContrato ? Carbon::createFromFormat('d/m/Y', $req->fechaContrato)->format('Y-m-d H:i:s') : '';
                    $fechaCDT = $req->fechaCDT ? Carbon::createFromFormat('d/m/Y', $req->fechaCDT)->format('Y-m-d H:i:s') : '';

                    $array = ['IdUsuario' => $idUsuarioNuevo->IdUsuario, 'Plan' => $req->plan, 'FechaFinalContrato' => $fechaContrato, 'FechaFinalCDT' => $fechaCDT, 'MontoPago' => $req->montoPago, 'Bloqueo' => $req->bloqueo, 'Estado' => 'E', 'IdSucursal' => $req->sucursal, 'FechaCreacion' => date("Y-m-d H:i:s")];
                    DB::table('suscripcion')->insert($array);

                    for ($i = 0; $i < count($req->serie); $i++) {
                        if ($req->tipoComprobante[$i] == 1) {
                            $descripcionComprobante = 'Nota de Credito Factura';
                        }
                        if ($req->tipoComprobante[$i] == 2) {
                            $descripcionComprobante = 'Nota de Credito Boleta';
                        }
                        $arrayInicio = ['IdSucursal' => $sucursal, 'TipoComprobante' => $req->tipoComprobante[$i], 'DescripcionComprobante' => $descripcionComprobante, 'Serie' => $req->serie[$i], 'Correlativo' => $req->correlativo[$i], 'Estado' => 'E'];
                        DB::table('inicio_comprobantes')->insert($arrayInicio);
                    }

                    // Fin
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    return back()->with('error', 'Ocurrio un error, por favor No INSISTA EN LA CREACIÓN, proceda a comunicarse con el Área de Soporte.');
                }
                return redirect('administracion/usuarios')->with('status', 'Se creo usuario correctamente');
            }

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // NUEVAS FUNCIONES ASIGNAR PLAN
    private function setPermisosBotonesAdministrador($idUsuario, $idPlan, $fecha)
    {
        $botones = DB::table('permisos_botones_plan_suscripciones')
            ->select('Id as IdPermisoBoton')
            ->where('IdPlanSuscripcion', $idPlan)
            ->where('Estado', 'E')
            ->get()
            ->map(function ($item) use ($idUsuario, $fecha) {
                return [
                    'IdUsuario' => $idUsuario,
                    'IdPermisoBoton' => $item->IdPermisoBoton,
                    'FechaAsignacion' => $fecha,
                ];
            })
            ->toArray();
        DB::table('permisos_botones_usuarios')
            ->insert($botones);

    }

    private function setPermisosSubBotonesAdministrador($idUsuario, $idPlan, $fecha)
    {
        $subBotones = DB::table('permisos_subbotones_plan_suscripciones')
            ->select('IdPermisoSubBoton', 'IdPermisoBoton')
            ->where('IdPlanSuscripcion', $idPlan)
            ->where('Estado', 'E')
            ->get()
            ->map(function ($item) use ($idUsuario, $fecha) {
                return [
                    'IdUsuario' => $idUsuario,
                    'FechaAsignacion' => $fecha,
                    'IdPermisoSubBoton' => $item->IdPermisoSubBoton,
                    'IdPermisoBoton' => $item->IdPermisoBoton,
                ];
            })
            ->toArray();

        DB::table('permisos_subbotones_usuarios')
            ->insert($subBotones);
    }

    private function setPermisosAdministrador($idUsuario, $estado, $idPlan, $fecha)
    {
        $loadDatos = new DatosController();
        $permisos = $loadDatos->getPermisosActivadosPlanSuscripcion($idPlan);
        foreach ($permisos as $item) {
            $array = ['IdUsuario' => $idUsuario, 'IdPermiso' => $item->IdPermiso, 'Estado' => $estado, 'FechaAsignacion' => $fecha];
            DB::table('usuario_permisos')->insert($array);
        }
    }
    private function setSubPermisosAdministrador($idUsuario, $estado, $idPlan, $fecha)
    {
        $loadDatos = new DatosController();
        $subPermisos = $loadDatos->getSubPermisosActivadosPlanSuscripcion($idPlan);
        foreach ($subPermisos as $item) {
            $array = ['IdUsuario' => $idUsuario, 'Permiso' => $item->IdPermiso, 'IdSubPermisos' => $item->IdSubPermiso, 'estado' => $estado, 'FechaAsignacion' => $fecha];
            DB::table('usuario_sub_permisos')->insert($array);
        }
    }
    private function setSubNivelAdministrador($idUsuario, $estado, $idPlan, $fecha)
    {
        $loadDatos = new DatosController();
        $subNiveles = $loadDatos->getSubNivelesActivadosPlanSuscripcion($idPlan);
        foreach ($subNiveles as $item) {
            $array = ['IdUsuario' => $idUsuario, 'IdSubPermiso' => $item->IdSubPermiso, 'IdSubNivel' => $item->IdSubNivel, 'estado' => $estado, 'FechaAsignacion' => $fecha];
            DB::table('usuario_sub_nivel')->insert($array);
        }
    }
    // FIN

    // private function setSubNivel($idUsuario, $estado)
    // {
    //     $subPermisos = $this->getAllSubPermisos();
    //     for ($i = 0; $i < count($subPermisos);
    //         $i++) {
    //         $subNivel = $this->getSubNivel($subPermisos[$i]->IdSubPermiso);
    //         for ($j = 0; $j < count($subNivel);
    //             $j++) {
    //             $array = ['IdUsuario' => $idUsuario, 'IdSubPermiso' => $subPermisos[$i]->IdSubPermiso, 'IdSubNivel' => $subNivel[$j]->IdSubNivel, 'estado' => $estado];
    //             DB::table('usuario_sub_nivel')->insert($array);
    //         }
    //     }
    // }

    // private function setSubPermisos($idUsuario, $permisos, $estado)
    // {
    //     for ($i = 0; $i < count($permisos);
    //         $i++) {
    //         $subPermisos = $this->getSubPermisos($permisos[$i]->IdPermiso);
    //         for ($j = 0; $j < count($subPermisos);
    //             $j++) {
    //             // Nuevo codigo solo la condicion If
    //             if ($subPermisos[$j]->IdSubPermiso != 34) {
    //                 $array = ['IdUsuario' => $idUsuario, 'Permiso' => $permisos[$i]->IdPermiso, 'IdSubPermisos' => $subPermisos[$j]->IdSubPermiso, 'estado' => $estado];
    //                 DB::table('usuario_sub_permisos')->insert($array);
    //             }
    //             // Fin
    //         }
    //     }
    // }

    // private function setPermisos($idUsuario, $estado)
    // {
    //     $loadDatos = new DatosController();
    //     $permisos = $loadDatos->getAllPermisos();
    //     for ($i = 0; $i < count($permisos);
    //         $i++) {
    //         $array = ['IdUsuario' => $idUsuario, 'IdPermiso' => $permisos[$i]->IdPermiso, 'Estado' => $estado];
    //         DB::table('usuario_permisos')->insert($array);
    //     }
    //     return $permisos;
    // }

    // private function getSubNivel($idSubPermiso)
    // {
    //     try {
    //         $subNivel = DB::table('sub_nivel')
    //             ->where('IdSubPermiso', $idSubPermiso)
    //             ->where('Estado', 'E')
    //             ->get();
    //         return $subNivel;
    //     } catch (Exception $ex) {
    //         echo $ex->getMessage();
    //     }
    // }

    // private function getSubPermisos($idPermiso)
    // {
    //     try {
    //         $subPermisos = DB::table('sub_permisos')
    //             ->where('IdPermiso', $idPermiso)
    //             ->where('Estado', 'E')
    //             ->get();
    //         return $subPermisos;
    //     } catch (Exception $ex) {
    //         echo $ex->getMessage();
    //     }
    // }

    // private function getAllSubPermisos()
    // {
    //     try {
    //         $subPermisos = DB::table('sub_permisos')
    //             ->where('Estado', 'E')
    //             ->get();
    //         return $subPermisos;
    //     } catch (Exception $ex) {
    //         echo $ex->getMessage();
    //     }
    // }

    private function verificarUsuarios($usuarioSelect, $usuarios)
    {
        if ($usuarioSelect->IdOperador != 1) {
            if ($usuarioSelect->TotalUsuarios <= count($usuarios)) {
                return redirect('administracion/usuarios')->with('error', 'Ya no se pueden crear más usuarios');
            }
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
        $usuario = $loadDatos->getUsuarioSelect($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        // Extraer el correo del administrador
        $loginUser = $this->extraerFormatoLogin($usuarioSelect->Login);
        $login = substr($loginUser, 1);
        // $expreReg = "[a-z]\.$login$";
        $expreReg = "^[a-zA-Z0-9]+\.$login$";
        // Fin

        $sucursales = $loadDatos->getSucursales($usuarioSelect->CodigoCliente, $usuarioSelect->IdOperador);
        $operadores = $loadDatos->getRoles();
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $datosEmpresa = $loadDatos->getDatosEmpresa($usuario->CodigoCliente);
        $rubros = $loadDatos->getRubros();
        $planesSuscripcion = DB::table('planes_suscripcion')->get();
        // Nuevo codigo Modulos
        $modulosDelSistema = $loadDatos->getModulos();
        $modulosDeUsuario = $loadDatos->getUsuarioModulos($id)->pluck('IdModulo')->toArray();
        // fin
        $datosSuscripcion = $loadDatos->getDatosUsuarioSuscripcion($id)->first();

        $suscripcionesSucursales = $this->getAllSuscripcionesSucursales($usuario->CodigoCliente);

        $array = ['usuario' => $usuario, 'datosEmpresa' => $datosEmpresa, 'sucursales' => $sucursales, 'operadores' => $operadores, 'usuarioSelect' => $usuarioSelect, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'rubros' => $rubros, 'loginUser' => $loginUser, 'expreReg' => $expreReg, 'planesSuscripcion' => $planesSuscripcion, 'modulosDelSistema' => $modulosDelSistema, 'modulosDeUsuario' => $modulosDeUsuario, 'datosSuscripcion' => $datosSuscripcion, 'suscripcionesSucursales' => $suscripcionesSucursales];
        return view('administracion/usuarios/editarUsuario', $array);
    }

    public function update(Request $req, $id)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                $idUsuario = Session::get('idUsuario');
            } else {
                Session::flush();
                return redirect('/')->with('out', 'Sesión de usuario Expirado');
            }
            $loadDatos = new DatosController();
            $login = $req->login;
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $usuario = $loadDatos->getUsuarioSelect($id);

            // validar si la sucursal a la que pretenece el usuario esta Activado
            if ($usuarioSelect->IdOperador == 2) {
                if ($usuario->EstadoSucursal != 'E') {
                    return back()->with('error', 'No se puede habilitar el usuario, la sucursal se encuenta desactivada por superar la fecha límite de Suscripción. Por favor comunicarse con el área Comercial de ventas');
                }
            }
            // Validar login
            if ($usuarioSelect->FechaCreacion >= '2023-01-01') {
                $formatolLogin = substr($req->formatoLogin, 1);
                $expreReg = "/[a-zA-Z0-9]+\.$formatolLogin/";

                if (!preg_match($expreReg, $login)) {
                    return back()->with('error', 'Login de usuario no cumple con el Formato NOMBREUSUARIO' . $req->formatoLogin);
                }
            }
            // Fin

            $this->validateUsuario($req);
            if ($req->login != $req->loginHide) {
                $existe = $loadDatos->getVerificarUsuario($req->login);
                if ($existe->usuarioTotal > 0) {
                    return redirect('administracion/usuarios')->with('error', 'Login de usuario ya existe, por favor probar con otro.');
                }
            }
            $nombre = $req->nombre;
            $sucursal = $req->sucursal;
            $operador = $req->operador;
            $dni = $req->dni;
            $email = $req->email;
            $telefono = $req->telefono;
            $direccion = $req->direccion;
            $opcionFactura = ($idUsuario == 1 ? $req->radioOpcion : 0);
            $selectEstado = $req->selectEstado;
            if ($selectEstado == 1) {
                $estado = 'E';
            } else {
                $estado = 'D';
            }
            if ($req->reestablecerContra == null) {
                $contrasena = $req->contrasena;
            } else {
                $password = '*easyfactperu*';
                $contrasena = password_hash($password, PASSWORD_DEFAULT);
                $ClaveDeComprobacion = 'xsecret23';
                $ClaveDeComprobacion = password_hash($ClaveDeComprobacion, PASSWORD_DEFAULT);
            }
            if ($req->IdOperadorUsuario == 1) {
                $totalUsuarios = $req->totalUsuarios;
                $totalsucursales = $req->totalSucursales;
            } else {
                $totalUsuarios = $usuarioSelect->TotalUsuarios;
                $totalsucursales = $usuarioSelect->TotalSucursales;
                $editarPrecio = $usuarioSelect->EditarPrecio;
            }
            if ($req->editPrecio != null) {
                if ($req->editPrecio == 'on') {
                    $editarPrecio = 1;
                } else {
                    $editarPrecio = 0;
                }
            } else {
                $editarPrecio = 0;
            }

            // Nuevo codigo para activar paquete promocional
            if ($req->activarPaquetePromo != null) {
                if ($req->activarPaquetePromo == 'on') {
                    $activarPaquetePromo = 1;
                } else {
                    $activarPaquetePromo = 0;
                }
            } else {
                $activarPaquetePromo = 0;
            }

            if ($req->activarImportacionExcelClientes != null) {
                if ($req->activarImportacionExcelClientes == 'on') {
                    $opcionImportarExcel = 1;
                } else {
                    $opcionImportarExcel = 0;
                }
            } else {
                $opcionImportarExcel = 0;
            }

            //dd($opcionAnticipos);

            // Fin

            /**********************conseguir cod Cliente***************************/
            if ($idUsuario == 1) {
                DB::table('usuario')
                    ->where('CodigoCliente', $req->codigoCliente)
                    ->update(['OpcionFactura' => $opcionFactura]);
            }

            $usuarioPrin = DB::table('usuario')
                ->select('OpcionFactura', 'CodigoCliente', 'IdUSuario')
                ->where('CodigoCliente', $usuario->CodigoCliente)
                ->first();

            if ($usuarioPrin->OpcionFactura >= 1) {
                $opcionFactura = $usuarioPrin->OpcionFactura;
            }

            // codigo para guardar Firma
            $rucEmpresa = $loadDatos->getRucEmpresa($idUsuario)->Ruc;
            $imagenFirma = $req->inputImagenFirma;
            if ($imagenFirma != null) {
                $imagenFirma = $loadDatos->storeFirmaDigital($rucEmpresa, $id, $carpeta = 'FirmasUsuarios', $imagenFirma, $req->inputImagenFirmaAnterior, $accion = 'editar');
            } else {
                $imagenFirma = $req->inputImagenFirmaAnterior;
            }
            // Fin
            $operacionGratuita = $req->has('operacionGratuita') ? 1 : 0;
            if ($req->foto != null) {
                $foto = $loadDatos->setImage($req->foto);
                $array = ['IdOperador' => $operador, 'IdSucursal' => $sucursal, 'Nombre' => $nombre, 'DNI' => $dni, 'Direccion' => $direccion,
                    'Telefono' => $telefono, 'Email' => $email, 'Login' => $login, 'OpcionFactura' => $opcionFactura, 'EditarPrecio' => $editarPrecio, 'Password' => $contrasena, 'Foto' => $foto, 'TotalUsuarios' => $totalUsuarios, 'TotalSucursales' => $totalsucursales, 'Estado' => $estado, 'ActivarPaquetePromo' => $activarPaquetePromo, 'OpcionImportarExcel' => $opcionImportarExcel, 'ImagenFirma' => $imagenFirma, 'FechaModificacion' => date("Y-m-d H:i:s"), 'DescuentoMaximoSoles' => $req->descuentoMaximoSoles, 'DescuentoMaximoDolares' => $req->descuentoMaximoDolares, 'OpcionOperacionGratuita' => $operacionGratuita];
            } else {
                $array = ['IdOperador' => $operador, 'IdSucursal' => $sucursal, 'Nombre' => $nombre, 'DNI' => $dni, 'Direccion' => $direccion,
                    'Telefono' => $telefono, 'Email' => $email, 'Login' => $login, 'OpcionFactura' => $opcionFactura, 'EditarPrecio' => $editarPrecio, 'Password' => $contrasena, 'TotalUsuarios' => $totalUsuarios, 'TotalSucursales' => $totalsucursales, 'Estado' => $estado, 'ActivarPaquetePromo' => $activarPaquetePromo, 'OpcionImportarExcel' => $opcionImportarExcel, 'ImagenFirma' => $imagenFirma, 'FechaModificacion' => date("Y-m-d H:i:s"), 'DescuentoMaximoSoles' => $req->descuentoMaximoSoles, 'DescuentoMaximoDolares' => $req->descuentoMaximoDolares, 'OpcionOperacionGratuita' => $operacionGratuita];
            }
            if ($req->IdOperadorUsuario == 1) {
                if ($req->codigoProducto == 'on') {
                    $selectCodigo = 1;
                } else {
                    $selectCodigo = 0;
                }

                if ($req->checkActivarPrecioSinIgv === 'chekeado') {
                    $opcionActivarPrecioIgv = 1;
                } else {
                    $opcionActivarPrecioIgv = 0;
                }

                DB::table('usuario')
                    ->where('CodigoCliente', $req->codigoCliente)
                    ->update(['CodigoProducto' => $selectCodigo, 'TotalUsuarios' => $totalUsuarios, 'TotalSucursales' => $totalsucursales, 'OpcionPrecioSinIgv' => $opcionActivarPrecioIgv]);

                if ($req->exonerar == 'on') {
                    $exonerado = 1;
                } else {
                    $exonerado = 0;
                }

                if ($req->archivoPLE == 'on') {
                    $archivoPle = 1;
                } else {
                    $archivoPle = 0;
                }

                if ($req->ventaSolesDolares == 'on') {
                    $ventaSolesDolares = 1;
                } else {
                    $ventaSolesDolares = 0;
                }

                if ($req->anticipos != null) {
                    if ($req->anticipos == 'on') {
                        $opcionAnticipos = 1;
                    } else {
                        $opcionAnticipos = 0;
                    }
                } else {
                    $opcionAnticipos = 0;
                }

                DB::table('empresa')
                    ->where('CodigoCliente', $req->codigoCliente)
                    ->update(['Exonerado' => $exonerado, 'ArchivoPLE' => $archivoPle, 'VentaSolesDolares' => $ventaSolesDolares, 'IdRubro' => $req->rubro, 'Anticipos' => $opcionAnticipos, 'IdPlanSuscripcion' => $req->selectPlanSuscripcionActual]);

                // Codigo para Actualizar PLAN SUSCRIPCION
                if ($req->inputPlanSuscripcionAnterior != $req->selectPlanSuscripcionActual) {
                    $fecha = Carbon::now()->toDateTimeString();
                    $this->actualizarPlanSuscripcionUsuario($id, $fecha, $req->selectPlanSuscripcionActual);
                }
                // FIN
            }

            DB::table('usuario')
                ->where('IdUsuario', $id)
                ->update($array);

            DB::table('sucursal')
                ->where('IdSucursal', $sucursal)
                ->update(['CodigoCliente' => $usuario->CodigoCliente]);

            // Codigo Actualizar Modulos
            if ($req->modulos !== null) {
                $modulosDeUsuario = $loadDatos->getUsuarioModulos($id)->pluck('IdModulo')->toArray();
                $this->actualizarModulos($id, $modulosDeUsuario, $req->modulos);
            }
            // Fin

            // Activar sucursal con usuarios desactivados por motivo de fecha de caducidad de la suscripcion
            $this->activarSucursalConUsuarios($req, $usuario);
            $this->actualizarSuscripcionDeSucursal($req, $usuario, $loadDatos);

            // --------------------------------------

            return redirect('administracion/usuarios')->with('status', 'Se actualizo usuario correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function actualizarModulos($idUsuario, $modulosAnteriores, $modulosNuevos)
    {
        foreach ($modulosNuevos as $modulo) {
            if (!in_array($modulo, $modulosAnteriores)) {
                $array = ['IdUsuario' => $idUsuario, 'IdModulo' => $modulo];
                DB::table('usuario_modulo')->insert($array);
            }
        }
        DB::table('usuario_modulo')
            ->where('IdUsuario', $idUsuario)
            ->whereNotIn('IdModulo', $modulosNuevos)
            ->delete();
    }

    private function actualizarSuscripcionDeSucursal($req, $usuario, $loadDatos)
    {
        if ($req->checkSuscripcion != null) {
            $arrayIdSucursalesConSuscripcion = $loadDatos->getDatosUsuarioSuscripcion($usuario->IdUsuario)->pluck('IdSucursal')->toArray();
            foreach ($req->checkSuscripcion as $item) {
                $plan = $req->get('plan-' . $item);
                $fechaContrato = $this->formatearFechaRecibidaConSlash($req->get('fechaContrato-' . $item));
                $fechaCDT = $this->formatearFechaRecibidaConSlash($req->get('fechaCDT-' . $item));
                $montoPago = $req->get('montoPago-' . $item);
                $bloqueo = $req->get('bloqueo-' . $item);

                if (in_array($item, $arrayIdSucursalesConSuscripcion)) {
                    DB::table('suscripcion')
                        ->where('IdUsuario', $usuario->IdUsuario)
                        ->where('IdSucursal', $item)
                        ->update(['Plan' => $plan, 'FechaFinalContrato' => $fechaContrato, 'FechaFinalCDT' => $fechaCDT, 'MontoPago' => $montoPago, 'Bloqueo' => $bloqueo, 'FechaActualizacion' => date("Y-m-d H:i:s")]);
                } else {
                    DB::table('suscripcion')
                        ->insert(['IdUsuario' => $usuario->IdUsuario, 'Plan' => $plan, 'FechaFinalContrato' => $fechaContrato, 'FechaFinalCDT' => $fechaCDT, 'MontoPago' => $montoPago, 'Bloqueo' => $bloqueo, 'Estado' => 'E', 'IdSucursal' => $item, 'FechaCreacion' => date("Y-m-d H:i:s")]);
                }
            }
        }
    }

    private function activarSucursalConUsuarios($req, $usuario)
    {
        if ($req->IdOperadorUsuario == 1) {
            if ($req->selectActivarSucursal != null) {
                DB::table('usuario')
                    ->where('Estado', 'Suscripcion Caducada')
                    ->whereIn('IdSucursal', $req->selectActivarSucursal)
                    ->update(['Estado' => 'E']);
                DB::table('sucursal')
                    ->where('Estado', 'Suscripcion Caducada')
                    ->whereIn('IdSucursal', $req->selectActivarSucursal)
                    ->update(['Estado' => 'E']);

                DB::table('usuario')
                    ->where('Estado', 'E')
                    ->whereNotIn('IdSucursal', $req->selectActivarSucursal)
                    ->where('CodigoCliente', $usuario->CodigoCliente)
                    ->update(['Estado' => 'Suscripcion Caducada']);
                DB::table('sucursal')
                    ->where('Estado', 'E')
                    ->whereNotIn('IdSucursal', $req->selectActivarSucursal)
                    ->where('CodigoCliente', $usuario->CodigoCliente)
                    ->update(['Estado' => 'Suscripcion Caducada']);
                // se comprueba si la sucursal que pertenece el usuario principal esta Activada caso contrario se actualiza a una sucursal que esta activado
                $resultado = DB::table('sucursal')->where('IdSucursal', $usuario->IdSucursal)->where('Estado', 'E')->first();
                if ($resultado == null) {
                    DB::table('usuario')
                        ->where('IdUsuario', $usuario->IdUsuario)
                        ->update(['IdSucursal' => $req->selectActivarSucursal[0], 'Estado' => 'E']);
                }
            } else {
                // Desactivamos todos los usuarios y sucursales
                DB::table('usuario')
                    ->where('Estado', 'E')
                    ->where('CodigoCliente', $usuario->CodigoCliente)
                    ->update(['Estado' => 'Suscripcion Caducada']);
                DB::table('sucursal')
                    ->where('Estado', 'E')
                    ->where('CodigoCliente', $usuario->CodigoCliente)
                    ->update(['Estado' => 'Suscripcion Caducada']);
            }
        }
    }

    // NUEVA FUNCION ACTUALIZAR EL PLAN DE SUSCRIPCION DEL USUARIO
    public function actualizarPlanSuscripcionUsuario($id, $fecha, $idPlan)
    {
        $loadDatos = new DatosController();
        $codigoCliente = $loadDatos->getUsuarioSelect($id)->CodigoCliente;
        $idsUsuariosClientesAndSubUsuarios = DB::table('usuario')
            ->select('IdUsuario')
            ->where('CodigoCliente', $codigoCliente)
            ->get()
            ->pluck('IdUsuario')
            ->toArray();

        $permisosPlanSuscripcion = $loadDatos->getPermisosActivadosPlanSuscripcion($idPlan);
        $subPermisosPlanSuscripcion = $loadDatos->getSubPermisosActivadosPlanSuscripcion($idPlan);
        $subNivelesPlanSuscripcion = $loadDatos->getSubNivelesActivadosPlanSuscripcion($idPlan);

        // CODIGO ACTUALIZAR PERMISOS
        $permisosUsuarioAdmin = $this->getPermisosUsuarioAdmin($id)->pluck('IdPermiso');

        $permisosParaNoBloquear = $this->getValoresDuplicados($permisosUsuarioAdmin, $permisosPlanSuscripcion->pluck('IdPermiso'));
        if (!empty($permisosParaNoBloquear)) {
            DB::table('usuario_permisos')
                ->whereIn('IdUsuario', $idsUsuariosClientesAndSubUsuarios)
                ->whereNotIn('IdPermiso', $permisosParaNoBloquear)
                ->update(['Estado' => 'D', 'FechaActualizacion' => $fecha]);
        }
        foreach ($permisosPlanSuscripcion as $permiso) {
            if ($permisosUsuarioAdmin->search($permiso->IdPermiso) === false) {
                DB::table('usuario_permisos')
                    ->insert(['IdUsuario' => $id, 'IdPermiso' => $permiso->IdPermiso, 'Estado' => 'E', 'FechaAsignacion' => $fecha]);
            } else {
                DB::table('usuario_permisos')
                    ->where('IdUsuario', $id)
                    ->where('IdPermiso', $permiso->IdPermiso)
                    ->update(['estado' => 'E', 'FechaActualizacion' => $fecha]);
            }
        }

        // CODIGO ACTUALIZAR SUB-PERMISOS
        $subPermisosUsuarioAdmin = $this->getSubPermisosUsuarioAdmin($id)->pluck('IdSubPermisos');

        $subPermisosParaNoBloquear = $this->getValoresDuplicados($subPermisosUsuarioAdmin, $subPermisosPlanSuscripcion->pluck('IdSubPermiso'));
        if (!empty($subPermisosParaNoBloquear)) {
            DB::table('usuario_sub_permisos')
                ->whereIn('IdUsuario', $idsUsuariosClientesAndSubUsuarios)
                ->whereNotIn('IdSubPermisos', $subPermisosParaNoBloquear)
                ->update(['Estado' => 'D', 'FechaActualizacion' => $fecha]);
        }
        foreach ($subPermisosPlanSuscripcion as $subPermiso) {
            if ($subPermisosUsuarioAdmin->search($subPermiso->IdSubPermiso) === false) {
                DB::table('usuario_sub_permisos')
                    ->insert(['IdUsuario' => $id, 'Permiso' => $subPermiso->IdPermiso, 'IdSubPermisos' => $subPermiso->IdSubPermiso, 'Estado' => 'E', 'FechaAsignacion' => $fecha]);
            } else {
                DB::table('usuario_sub_permisos')
                    ->where('IdUsuario', $id)
                    ->where('IdSubPermisos', $subPermiso->IdSubPermiso)
                    ->update(['estado' => 'E', 'FechaActualizacion' => $fecha]);
            }
        }

        // CODIGO ACTUALIZAR SUB-NIVELES
        $subNivelesUsuarioAdmin = $this->getSubNivelesUsuarioAdmin($id)->pluck('IdSubNivel');

        $subNivelesParaNoBloquear = $this->getValoresDuplicados($subNivelesUsuarioAdmin, $subNivelesPlanSuscripcion->pluck('IdSubNivel'));
        if (!empty($subNivelesParaNoBloquear)) {
            DB::table('usuario_sub_nivel')
                ->whereIn('IdUsuario', $idsUsuariosClientesAndSubUsuarios)
                ->whereNotIn('IdSubNivel', $subNivelesParaNoBloquear)
                ->update(['Estado' => 'D', 'FechaActualizacion' => $fecha]);
        }
        if (!empty($subNivelesUsuarioAdmin)) {
            foreach ($subNivelesPlanSuscripcion as $subNiveles) {
                if ($subNivelesUsuarioAdmin->search($subNiveles->IdSubNivel) === false) {
                    DB::table('usuario_sub_nivel')
                        ->insert(['IdUsuario' => $id, 'IdSubPermiso' => $subNiveles->IdSubPermiso, 'IdSubNivel' => $subNiveles->IdSubNivel, 'Estado' => 'E', 'FechaAsignacion' => $fecha]);
                } else {
                    DB::table('usuario_sub_nivel')
                        ->where('IdUsuario', $id)
                        ->where('IdSubNivel', $subNiveles->IdSubNivel)
                        ->update(['estado' => 'E', 'FechaActualizacion' => $fecha]);
                }
            }
        }
        // Actualizar modulos
        $modulosUsuario = $loadDatos->getUsuarioModulos($id)->pluck('IdModulo')->toArray();
        $modulosPlanSuscripcion = DB::table('modulo_planSuscripcion')->where('Estado', 'E')->where('IdPlanSuscripcion', $idPlan)->get()->pluck('IdModulo')->toArray();
        if (!empty($modulosPlanSuscripcion)) {
            $this->actualizarModulos($id, $modulosUsuario, $modulosPlanSuscripcion);
        }

    }

    private function getValoresDuplicados($planSuscripcionAnterior, $nuevoPlanSucripcion)
    {
        return $planSuscripcionAnterior->intersect($nuevoPlanSucripcion)->toArray();
    }

    private function getPermisosUsuarioAdmin($id)
    {
        $permisos = DB::table('usuario_permisos')
            ->where('usuario_permisos.IdUsuario', $id)
            ->get();
        return $permisos;
    }
    private function getSubPermisosUsuarioAdmin($id)
    {
        $subPermisos = DB::table('usuario_sub_permisos')
            ->where('usuario_sub_permisos.IdUsuario', $id)
            ->get();
        return $subPermisos;
    }
    private function getSubNivelesUsuarioAdmin($id)
    {
        $subNiveles = DB::table('usuario_sub_nivel')
            ->where('usuario_sub_nivel.IdUsuario', $id)
            ->get();
        return $subNiveles;
    }
    ///////// FIN

    public function delete($id)
    {
        try {
            $array = ['Estado' => 'R'];
            DB::table('usuario')
                ->where('IdUsuario', $id)
                ->update($array);

            return redirect('administracion/usuarios')->with('status', 'Se elimino usuario correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function actualizarMensaje(Request $req)
    {
        try {
            if ($req->checkMensaje == null) {
                $estado = 0;
            } else {
                $estado = 1;
            }
            $mensaje = $req->mensaje;
            $tipo = $req->mostrarMensaje;

            DB::table('mensaje')
                ->where('IdMensaje', $req->idMensaje)
                ->update(['Descripcion' => $mensaje, 'Estado' => $estado, 'UrlVideo' => $req->urlVideo, 'IdRubro' => $req->rubro]);

            Cache::flush();
            return redirect('administracion/usuarios')->with('status', 'Se actualizo mensaje de Administrador');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function configurarSuscripcion(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $usuario = $loadDatos->getUsuarioSelect($id);
            $empresa = $loadDatos->getDatosEmpresa($usuario->CodigoCliente);
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $permisos = $loadDatos->getPermisos($idUsuario);
            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            //$sucursales = $loadDatos->getSucursales( $usuarioSelect->CodigoCliente, $usuarioSelect->IdOperador );
            //$operadores = $loadDatos->getRoles();

            // $selectUsuarioSuscripcion = $loadDatos->getDatosUsuarioSuscripcion($id);
            // if (count($selectUsuarioSuscripcion) > 0) {
            //     $fechaFinalContrato = $selectUsuarioSuscripcion[0]->FechaFinalContrato;
            //     $fechaFinalCDT = $selectUsuarioSuscripcion[0]->FechaFinalCDT;
            //     $bloqueo = $selectUsuarioSuscripcion[0]->Bloqueo;
            //     $montoPago = $selectUsuarioSuscripcion[0]->MontoPago;
            //     $plan = $selectUsuarioSuscripcion[0]->Plan;
            // } else {
            //     $fechaFinalContrato = '';
            //     $fechaFinalCDT = '';
            //     $bloqueo = '';
            //     $montoPago = '';
            //     $plan = '';
            // }
            $suscripcionesSucursales = $this->getAllSuscripcionesSucursales($usuario->CodigoCliente);
            $array = ['usuarioSelect' => $usuarioSelect, 'idenUsuario' => $id, 'usuario' => $usuario->Nombre, 'empresa' => $empresa->Nombre, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'suscripcionesSucursales' => $suscripcionesSucursales];
            return view('administracion/usuarios/suscripcion', $array);
            //return redirect( 'administracion/usuarios' )->with( 'status', 'Se actualizo mensaje de Administrador' );

        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    private function getAllSuscripcionesSucursales($CodigoCliente)
    {
        $suscripcionesSucursales = DB::table('suscripcion')
            ->select('sucursal.IdSucursal', 'sucursal.Nombre as NombreSucursal', 'Plan', 'FechaFinalContrato', 'FechaFinalCDT', 'MontoPago', 'Bloqueo')
            ->rightjoin('sucursal', 'suscripcion.IdSucursal', '=', 'sucursal.IdSucursal')
            ->whereIn('sucursal.Estado', ['E', 'Suscripcion Caducada'])
            ->where('sucursal.CodigoCliente', $CodigoCliente)
            ->orderBy('sucursal.IdSucursal', 'desc')
            ->get();
        return $suscripcionesSucursales;
    }

    public function finalizarSuscripcion(Request $req)
    {
        try {
            // $this->validateSuscripcion($req);
            $loadDatos = new DatosController();
            $idenUsuario = $req->idenUsuario;
            // $plan = $req->plan;
            // $fecha = DateTime::createFromFormat('Y-m-d', $req->fechaContrato);
            // $fechaContrato = $fecha->format('Y-m-d H:i:s');
            // $fecha2 = DateTime::createFromFormat('Y-m-d', $req->fechaCDT);
            // $fechaCDT = $fecha2->format('Y-m-d H:i:s');
            // $montoPago = $req->montoPago;
            // $bloqueo = $req->bloqueo;
            // $selectUsuarioSuscripcion = $loadDatos->getDatosUsuarioSuscripcion($idenUsuario);
            // if (count($selectUsuarioSuscripcion) > 0) {
            //     $array = ['Plan' => $plan, 'FechaFinalContrato' => $fechaContrato, 'FechaFinalCDT' => $fechaCDT, 'MontoPago' => $montoPago, 'Bloqueo' => $bloqueo];
            //     DB::table('suscripcion')
            //         ->where('IdUsuario', $idenUsuario)
            //         ->update($array);
            // } else {
            //     $array = ['IdUsuario' => $idenUsuario, 'Plan' => $plan, 'FechaFinalContrato' => $fechaContrato, 'FechaFinalCDT' => $fechaCDT, 'MontoPago' => $montoPago, 'Bloqueo' => $bloqueo, 'Estado' => 'E'];
            //     DB::table('suscripcion')->insert($array);
            // }
            $usuario = $loadDatos->getUsuarioSelect($idenUsuario);
            $this->actualizarSuscripcionDeSucursal($req, $usuario, $loadDatos);
            return redirect('administracion/usuarios')->with('status', 'Se actualizaron datos de suscripcion correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function mostrarSuscripciones(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $now = new DateTime();
            $fecha = $now->format('Y-m-d H:i:s');
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $usuariosSuscripciones = $loadDatos->getSuscripciones();
            for ($i = 0; $i < count($usuariosSuscripciones);
                $i++) {
                $empresa = $loadDatos->getDatosEmpresa($usuariosSuscripciones[$i]->CodigoCliente);
                $fecha_actual = strtotime($fecha);
                $fecha_final = strtotime($usuariosSuscripciones[$i]->FechaFinalContrato);
                if ($fecha_actual < $fecha_final) {
                    /*$date1 = new DateTime( $usuariosSuscripciones[ $i ]->FechaFinalContrato );
                    $date2 = new DateTime();
                    $diff = $date1->diff( $date2 )->d;
                     */
                    $date1 = date_create($usuariosSuscripciones[$i]->FechaFinalContrato);
                    $date2 = new DateTime();
                    $interval = date_diff($date1, $date2);
                    $diff = $interval->format('%a');
                    if ($usuariosSuscripciones[$i]->Plan == 1 && $diff <= 4) {
                        $usuariosSuscripciones[$i]->Mostrar = 1;
                    } else {
                        if ($usuariosSuscripciones[$i]->Plan == 2 && $diff <= 7) {
                            $usuariosSuscripciones[$i]->Mostrar = 1;
                        } else {
                            if ($usuariosSuscripciones[$i]->Plan == 3 && $diff <= 15) {
                                $usuariosSuscripciones[$i]->Mostrar = 1;
                            } else {
                                $usuariosSuscripciones[$i]->Mostrar = 0;
                            }
                        }
                    }
                } else {
                    /*$date1 = new DateTime();
                    $date2 = new DateTime( $usuariosSuscripciones[ $i ]->FechaFinalContrato );
                    $diff = $date1->diff( $date2 )->d;
                     */
                    $date1 = new DateTime();
                    $date2 = date_create($usuariosSuscripciones[$i]->FechaFinalContrato);
                    $interval = date_diff($date1, $date2);
                    $diff = $interval->format('%a');

                    if ($diff <= $usuariosSuscripciones[$i]->Bloqueo) {
                        $usuariosSuscripciones[$i]->Mostrar = 1;
                    } else {
                        $usuariosSuscripciones[$i]->Mostrar = 0;
                    }
                }
                $usuariosSuscripciones[$i]->Empresa = $empresa->Nombre;
                $usuariosSuscripciones[$i]->RucEmpresa = $empresa->Ruc;
            }
            $array = ['usuarioSelect' => $usuarioSelect, 'usuariosSuscripciones' => $usuariosSuscripciones, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
            return view('administracion/usuarios/usuariosSuscripciones', $array);
            //return redirect( 'administracion/usuarios' )->with( 'status', 'Se actualizo mensaje de Administrador' );

        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function guardarCambiosSuscripciones(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $loadDatos = new DatosController();
            if (!empty($req->id)) {
                for ($i = 0; $i < count($req->id);
                    $i++) {
                    $usuarioSuscrip = $loadDatos->getSuscripcionSelect($req->id[$i]);
                    $_date = Carbon::parse($usuarioSuscrip->FechaFinalContrato);
                    if ($usuarioSuscrip->Plan == 1) {
                        $fechaProxima = $_date->addMonth();
                    } else {
                        if ($usuarioSuscrip->Plan == 2) {
                            $fechaProxima = $_date->addMonths(6);
                        } else {
                            $fechaProxima = $_date->addYear();
                        }
                    }
                    DB::table('suscripcion')
                        ->where('IdSuscripcion', $req->id[$i])
                        ->update(['FechaFinalContrato' => $fechaProxima]);
                }
                return redirect('administracion/usuarios-suscripciones')->with('status', 'Se guardaron cambios correctamente');
            } else {
                return redirect('administracion/usuarios-suscripciones')->with('error', 'No se selecciono ningún usuario');
            }
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function listadoXml(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
            $loadDatos = new DatosController();
            $usuarioSelect = $loadDatos->getUsuarioSelect($id);
            $sucursales = $loadDatos->getSucursales($usuarioSelect->CodigoCliente, $usuarioSelect->IdOperador);
            $_usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $permisos = $loadDatos->getPermisos($idUsuario);
            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($_usuarioSelect->CodigoCliente);
            $_idSucursal = $sucursales[0]->IdSucursal;
            $tipoComprobante = 1;
            $anio = '2022';
            $mes = '10';
            //$archivosXML = [];

            $archivosXML = $loadDatos->getArchivosXML($_idSucursal, $tipoComprobante, $anio, $mes);
            //dd($archivosXML);
            $array = ['sucursales' => $sucursales, 'id' => $id, 'tipoComprobante' => $tipoComprobante, 'anio' => $anio, 'mes' => $mes, '_idSucursal' => $_idSucursal, 'archivosXML' => $archivosXML, 'permisos' => $permisos, 'usuarioSelect' => $usuarioSelect, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
            return view('administracion/usuarios/listadoXml', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function buscarArchivosXML(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
            $loadDatos = new DatosController();
            $usuarioSelect = $loadDatos->getUsuarioSelect($id);
            $sucursales = $loadDatos->getSucursales($usuarioSelect->CodigoCliente, $usuarioSelect->IdOperador);
            $_usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $permisos = $loadDatos->getPermisos($idUsuario);
            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($_usuarioSelect->CodigoCliente);
            $_idSucursal = $req->sucursal;
            $tipoComprobante = $req->tipoComprobante;
            $anio = $req->anio;
            $mes = $req->mes;

            $archivosXML = $loadDatos->getArchivosXML($_idSucursal, $tipoComprobante, $anio, $mes);

            $array = ['sucursales' => $sucursales, 'id' => $id, 'tipoComprobante' => $tipoComprobante, 'anio' => $anio, 'mes' => $mes, '_idSucursal' => $_idSucursal, 'archivosXML' => $archivosXML, 'permisos' => $permisos, 'usuarioSelect' => $usuarioSelect, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
            return view('administracion/usuarios/listadoXml', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function guardarXML(Request $req)
    {
        $loadDatos = new DatosController();
        $idUsuario = $req->idUsuario;
        $idSucursal = $req->idSucursal;
        $anio = $req->anioDoc;
        $mes = $req->mesDoc;
        $tipoComprobante = $req->tipoComp;
        /*$now = Carbon::now();
        $anio = $now->year;
        $mes = $now->month;*/
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);

        //dd($anio.'---'.$mes);
        $archivosXML = $loadDatos->getArchivosXML($idSucursal, $tipoComprobante, $anio, $mes);
        if ($tipoComprobante == 1) {
            for ($i = 0; $i < count($archivosXML); $i++) {
                $nombreArchivo = $empresa->Ruc . '-' . $archivosXML[$i]->IdTipoSunat . '-' . $archivosXML[$i]->Serie . '-' . $archivosXML[$i]->Numero;
                $archivoXml = public_path() . '/RespuestaSunat/FacturasBoletas/' . $empresa->Ruc . '/' . $nombreArchivo . '.xml';
                $archivoCdr = public_path() . '/RespuestaSunat/FacturasBoletas/' . $empresa->Ruc . '/R-' . $nombreArchivo . '.zip';
                $date = new Carbon($archivosXML[$i]->FechaCreacion);
                $anio = $date->year;
                $mes = $date->month;
                $_mes = $loadDatos->getMes($mes);
                if (file_exists($archivoXml)) {
                    $archivoXmlRead = file_get_contents($archivoXml);
                    $rutaXml = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/FacturasBoletas/' . $nombreArchivo . '.xml';
                    DB::table('ventas')
                        ->where('IdVentas', $archivosXML[$i]->IdVentas)
                        ->update(["RutaXml" => $rutaXml]);
                    Storage::disk('s3')->put($rutaXml, $archivoXmlRead, 'public');
                    //dd($xmlObject);
                }
                if (file_exists($archivoCdr)) {
                    $archivoCdrRead = file_get_contents($archivoCdr);
                    $rutaCDR = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/FacturasBoletas/R-' . $nombreArchivo . '.zip';
                    DB::table('ventas')
                        ->where('IdVentas', $archivosXML[$i]->IdVentas)
                        ->update(['RutaCdr' => $rutaCDR]);
                    Storage::disk('s3')->put($rutaCDR, $archivoCdrRead, 'public');
                }
                //usleep(50000);
            }
        }

        if ($tipoComprobante == 2) {
            for ($i = 0; $i < count($archivosXML); $i++) {
                $nombreArchivo = $empresa->Ruc . '-' . $archivosXML[$i]->IdTipoSunat . '-' . $archivosXML[$i]->Serie . '-' . $archivosXML[$i]->Numero;
                $archivoXml = public_path() . '/RespuestaSunat/NotasCreditoDebito/' . $empresa->Ruc . '/' . $nombreArchivo . '.xml';
                $archivoCdr = public_path() . '/RespuestaSunat/NotasCreditoDebito/' . $empresa->Ruc . '/R-' . $nombreArchivo . '.zip';
                //dd($archivo);
                if (file_exists($archivoXml)) {
                    $archivoXmlRead = file_get_contents($archivoXml);
                    $rutaXml = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/NotasCreditoDebito/' . $nombreArchivo . '.xml';
                    DB::table('nota_credito_debito')
                        ->where('IdCreditoDebito', $archivosXML[$i]->IdCreditoDebito)
                        ->update(["RutaXml" => $rutaXml]);
                    Storage::disk('s3')->put($rutaXml, $archivoXmlRead, 'public');
                    //dd($xmlObject);
                }
                if (file_exists($archivoCdr)) {
                    $archivoCdrRead = file_get_contents($archivoCdr);
                    $rutaCDR = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/NotasCreditoDebito/R-' . $nombreArchivo . '.zip';
                    DB::table('nota_credito_debito')
                        ->where('IdCreditoDebito', $archivosXML[$i]->IdCreditoDebito)
                        ->update(['RutaCdr' => $rutaCDR]);
                    Storage::disk('s3')->put($rutaCDR, $archivoCdrRead, 'public');
                }
                usleep(50000);
            }
        }

        if ($tipoComprobante == 3) {
            for ($i = 0; $i < count($archivosXML); $i++) {
                $nombreArchivo = $empresa->Ruc . '-09-' . $archivosXML[$i]->Serie . '-' . $archivosXML[$i]->Numero;
                $archivoXml = public_path() . '/RespuestaSunat/GuiasRemision/' . $empresa->Ruc . '/' . $nombreArchivo . '.xml';
                $archivoCdr = public_path() . '/RespuestaSunat/GuiasRemision/' . $empresa->Ruc . '/R-' . $nombreArchivo . '.zip';
                //dd($archivo);
                if (file_exists($archivoXml)) {
                    $archivoXmlRead = file_get_contents($archivoXml);
                    $rutaXml = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/GuiasRemision/' . $nombreArchivo . '.xml';
                    DB::table('guia_remision')
                        ->where('IdGuiaRemision', $archivosXML[$i]->IdGuiaRemision)
                        ->update(["RutaXml" => $rutaXml]);
                    Storage::disk('s3')->put($rutaXml, $archivoXmlRead, 'public');
                    //dd($xmlObject);
                }
                if (file_exists($archivoCdr)) {
                    $archivoCdrRead = file_get_contents($archivoCdr);
                    $rutaCDR = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/GuiasRemision/R-' . $nombreArchivo . '.zip';
                    DB::table('guia_remision')
                        ->where('IdGuiaRemision', $archivosXML[$i]->IdGuiaRemision)
                        ->update(['RutaCdr' => $rutaCDR]);
                    Storage::disk('s3')->put($rutaCDR, $archivoCdrRead, 'public');
                }
                usleep(50000);
            }
        }

        if ($tipoComprobante == 4) {
            for ($i = 0; $i < count($archivosXML); $i++) {
                $nombreArchivo = $empresa->Ruc . '-' . $archivosXML[$i]->Numero;
                $archivoXml = public_path() . '/RespuestaSunat/ResumenDiario/' . $empresa->Ruc . '/' . $nombreArchivo . '.xml';
                $archivoCdr = public_path() . '/RespuestaSunat/ResumenDiario/' . $empresa->Ruc . '/R-' . $nombreArchivo . '.zip';
                //dd($archivo);
                if (file_exists($archivoXml)) {
                    $archivoXmlRead = file_get_contents($archivoXml);
                    $rutaXml = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/ResumenDiario/' . $nombreArchivo . '.xml';
                    DB::table('resumen_diario')
                        ->where('IdResumenDiario', $archivosXML[$i]->IdResumenDiario)
                        ->update(["RutaXml" => $rutaXml]);
                    Storage::disk('s3')->put($rutaXml, $archivoXmlRead, 'public');
                    //dd($xmlObject);
                }
                if (file_exists($archivoCdr)) {
                    $archivoCdrRead = file_get_contents($archivoCdr);
                    $rutaCDR = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/ResumenDiario/R-' . $nombreArchivo . '.zip';
                    DB::table('resumen_diario')
                        ->where('IdResumenDiario', $archivosXML[$i]->IdResumenDiario)
                        ->update(['RutaCdr' => $rutaCDR]);
                    Storage::disk('s3')->put($rutaCDR, $archivoCdrRead, 'public');
                }
                usleep(50000);
            }
        }

        if ($tipoComprobante == 5) {
            for ($i = 0; $i < count($archivosXML); $i++) {
                $nombreArchivo = $empresa->Ruc . '-' . $archivosXML[$i]->Identificador;
                $archivoXml = public_path() . '/RespuestaSunat/BajaDocumentos/' . $empresa->Ruc . '/' . $nombreArchivo . '.xml';
                $archivoCdr = public_path() . '/RespuestaSunat/BajaDocumentos/' . $empresa->Ruc . '/R-' . $nombreArchivo . '.zip';
                //dd($archivo);
                if (file_exists($archivoXml)) {
                    $archivoXmlRead = file_get_contents($archivoXml);
                    $rutaXml = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/BajaDocumentos/' . $nombreArchivo . '.xml';
                    DB::table('baja_documentos')
                        ->where('IdBajaDoc', $archivosXML[$i]->IdBajaDoc)
                        ->update(["RutaXml" => $rutaXml]);
                    Storage::disk('s3')->put($rutaXml, $archivoXmlRead, 'public');
                    //dd($xmlObject);
                }
                if (file_exists($archivoCdr)) {
                    $archivoCdrRead = file_get_contents($archivoCdr);
                    $rutaCDR = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/BajaDocumentos/R-' . $nombreArchivo . '.zip';
                    DB::table('baja_documentos')
                        ->where('IdBajaDoc', $archivosXML[$i]->IdBajaDoc)
                        ->update(['RutaCdr' => $rutaCDR]);
                    Storage::disk('s3')->put($rutaCDR, $archivoCdrRead, 'public');
                }
                usleep(25000);
            }
        }

        return redirect('administracion/usuarios/lista-xml/' . $idUsuario)->with('status', 'Se subieron archivos XML y CDR al S3 correctamente');
        //dd("llego");
    }

    protected function validateUsuario(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required',
            'dni' => 'required|numeric',
            'telefono' => 'required|numeric',
            'login' => 'required',
        ]);
    }

    protected function validateSuscripcion(Request $request)
    {
        $this->validate($request, [
            'fechaContrato' => 'required',
            'fechaCDT' => 'required',
            'montoPago' => 'required',
            'bloqueo' => 'required',
        ]);
    }
}
