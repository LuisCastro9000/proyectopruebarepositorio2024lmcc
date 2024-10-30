<?php

namespace App\Http\Controllers\Consultas;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Greenter\Zip\ZipFly;
use Illuminate\Http\Request;
use PDF;
use PHPMailer\PHPMailer\PHPMailer;
use Session;
use Storage;

class ConsultaGuiasRemisionController extends Controller
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
        //dd($guiasAceptados);
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $tipoPago = '';
        $fecha = 5;
        $fechaIni = '';
        $fechaFin = '';
        $guiasAceptados = $loadDatos->getGuiasRemisionFiltrado($idSucursal, $fecha, $fechaIni, $fechaFin);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $array = ['guiasAceptados' => $guiasAceptados, 'permisos' => $permisos, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'rucEmpresa' => $empresa->Ruc];
        return view('consultas/consultaGuiaRemision', $array);
    }

    public function store(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $fecha = $req->fecha;
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        $tipoPago = 1;
        if ($fecha == 9) {
            if ($fechaIni == null || $fechaFin == null) {
                return back()->with('error', 'Completar las fechas para filtrar');
            }
            $dateIni = DateTime::createFromFormat('d/m/Y', $fechaIni);
            $dateFin = DateTime::createFromFormat('d/m/Y', $fechaFin);
            if (strtotime($dateIni->format('Y-m-d')) > strtotime($dateFin->format('Y-m-d'))) {
                return back()->with('error', 'La fecha Inicial no puede ser mayor que la Final');
            }
        }

        $idSucursal = $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $guiasAceptados = $loadDatos->getGuiasRemisionFiltrado($idSucursal, $fecha, $fechaIni, $fechaFin);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $array = ['guiasAceptados' => $guiasAceptados, 'fecha' => $fecha, 'permisos' => $permisos, 'IdTipoPago' => $tipoPago, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'rucEmpresa' => $empresa->Ruc];
        return view('consultas/consultaGuiaRemision', $array);
    }

    public function descargarPDF(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $pdf = $this->generarPDF($req, 1, $id);
        $loadDatos = new DatosController();
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $guiaSelect = $loadDatos->getGuiaRemisionSelect($id);
        $rucEmpresa = $empresa->Ruc;
        $numeroCerosIzquierda = $this->completarCeros($guiaSelect->Numero);
        $serie = $guiaSelect->Serie;
        $idDoc = '09';
        return $pdf->download($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numeroCerosIzquierda . '.pdf');
    }

    public function descargarXML(Request $req, $ruc, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $guiaSelect = $loadDatos->getGuiaRemisionSelect($id);
            $serie = $guiaSelect->Serie;
            $numero = $guiaSelect->Numero;
            $cod = $serie . '-' . $numero;
            $file = $ruc . '-09-' . $cod . '.xml';

            if (Storage::disk('s3')->exists($guiaSelect->RutaXml)) {

                $rutaS3 = Storage::disk('s3')->get($guiaSelect->RutaXml);

                $zipFile = new ZipFly();
                $decompress = $zipFile->decompress($rutaS3);
                $fileXml = $decompress[0]["content"];

                $headers = [
                    'Content-Type' => 'text/xml',
                    'Content-Description' => 'File Transfer',
                    'Content-Disposition' => "attachment; filename=" . $file . "",
                    'filename' => '' . $file . '',
                ];

                return response($fileXml, 200, $headers);
            } else {
                return back()->with('error', 'No se encontró archivo Xml');
            }
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        /*$loadDatos = new DatosController();
    $guiaSelect = $loadDatos->getGuiaRemisionSelect($id);
    $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
    $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
    $rucEmpresa = $empresa->Ruc;
    $serie = $guiaSelect->Serie;
    $numero= $guiaSelect->Numero;
    $cod = $serie.'-'.$numero;
    $file = $rucEmpresa.'-09-'.$cod;

    return response()->download(public_path().'/RespuestaSunat/GuiasRemision/'.$ruc.'/'.$file.'.xml');*/
    }

    public function descargarCDR(Request $req, $ruc, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $guiaSelect = $loadDatos->getGuiaRemisionSelect($id);
            $serie = $guiaSelect->Serie;
            $numero = $guiaSelect->Numero;
            $cod = $serie . '-' . $numero;
            $file = 'R-' . $ruc . '-09-' . $cod . '.zip';

            if (Storage::disk('s3')->exists($guiaSelect->RutaCdr)) {

                $rutaS3 = Storage::disk('s3')->get($guiaSelect->RutaCdr);
                $headers = [
                    'Content-Type' => 'text/xml',
                    'Content-Description' => 'File Transfer',
                    'Content-Disposition' => "attachment; filename=" . $file . "",
                    'filename' => '' . $file . '',
                ];

                return response($rutaS3, 200, $headers);
            } else {
                return back()->with('error', 'No se encontró archivo Xml');
            }

        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        /*$loadDatos = new DatosController();
    $guiaSelect = $loadDatos->getGuiaRemisionSelect($id);
    $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
    $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
    $rucEmpresa = $empresa->Ruc;
    $serie = $guiaSelect->Serie;
    $numero= $guiaSelect->Numero;
    $cod = $serie.'-'.$numero;
    $file = 'R-'.$rucEmpresa.'-09-'.$cod;

    return response()->download(public_path().'/RespuestaSunat/GuiasRemision/'.$ruc.'/'.$file.'.zip');*/
    }

    public function detallesGuiaRemision(Request $req, $id)
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

        $guiaSelect = $loadDatos->getGuiaRemisionSelect($id);

        $numero = $guiaSelect->Numero;
        $fecha = date_create($guiaSelect->FechaEmision);
        $formatoFecha = date_format($fecha, 'd-m-Y');
        $formatoHora = date_format($fecha, 'H:i A');
        $fecha2 = date_create($guiaSelect->FechaTraslado);
        $formatoFecha2 = date_format($fecha2, 'd-m-Y');
        $items = $loadDatos->getItemsGuias($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['guiaSelect' => $guiaSelect, 'permisos' => $permisos, 'numero' => $numero, 'formatoFecha' => $formatoFecha, 'formatoHora' => $formatoHora, 'formatoFecha2' => $formatoFecha2, 'items' => $items, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('consultas/detallesGuiaRemision', $array);
    }

    public function enviarCorreo(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $guiaSelect = $loadDatos->getGuiaRemisionSelect($id);

        $numero = $guiaSelect->Numero;
        $serie = $guiaSelect->Serie;
        $cod = $serie . '-' . $numero;
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $nombreEmpresa = $empresa->Nombre;
        $rucEmpresa = $empresa->Ruc;

        $file = $rucEmpresa . '-09-' . $cod;

        $pdf = $this->generarPDF($req, 1, $id);

        file_put_contents($rucEmpresa . '-09-' . $serie . '-' . $numero . '.pdf', $pdf->output());

        $mail = new PHPMailer();
        // comente  $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'mail.easyfactperu.pe'; // Specify main and backup SMTP servers
        //$mail->SMTPAuth = true;
        $mail->Username = 'facturacion@easyfactperu.pe'; // SMTP username
        $mail->Debugoutput = 'html';
        $mail->Password = 'gV.S=o=Q,bl2'; // SMTP password
        $mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465; // TCP port to connect to
        $mail->From = 'facturacion@easyfactperu.pe';
        $mail->FromName = 'EASYFACT PERÚ S.A.C - Guía Remisión';
        $mail->addAddress($req->correo, 'Comprobante'); // Add a recipient

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8'; // Set email format to HTML
        $mail->Subject = 'Envío de comprobante';
        $mail->addAttachment($rucEmpresa . '-09-' . $serie . '-' . $numero . '.pdf');
        $mail->addAttachment(public_path() . '/RespuestaSunat/GuiasRemision/' . $file . '.xml');
        //$mail->msgHTML('Hola: '.$req->cliente.', Te estamos enviando adjunto el comprobante ('.$req->comprobante.'.pdf) de la compra que hiciste en BroadCast Perú');

        if (Storage::disk('s3')->exists($guiaSelect->RutaXml)) {
            $rutaXmlS3 = Storage::disk('s3')->get($guiaSelect->RutaXml);
            file_put_contents($rucEmpresa . '-09-' . $serie . '-' . $numero . '.xml', $rutaXmlS3);
            $mail->addAttachment($rucEmpresa . '-09-' . $serie . '-' . $numero . '.xml');
        }

        if (Storage::disk('s3')->exists($guiaSelect->RutaCdr)) {
            $rutaCdrS3 = Storage::disk('s3')->get($guiaSelect->RutaCdr);
            file_put_contents($rucEmpresa . '-09-' . $serie . '-' . $numero . '.zip', $rutaCdrS3);
            $mail->addAttachment($rucEmpresa . '-09-' . $serie . '-' . $numero . '.zip');
        }

        $tipo = 'GUÍA DE REMISIÓN';

        //$numeroCerosIzquierda = $this->completarCeros($ventaSelect->Numero);
        $fecha = date_create($guiaSelect->FechaEmision);
        $formatoFecha = date_format($fecha, 'd/m/Y');
        $fecha2 = date_create($guiaSelect->FechaTraslado);
        $formatoFecha2 = date_format($fecha2, 'd/m/Y');
        $mail->msgHTML('<table width="100%">'
            . '<tr>'
            . '<td style="border: 1px solid #000;">'
            . '<div align="center" style="background-color: #CCC">'
            . '<img width="150px" style="margin:15px" src="' . $empresa->Imagen . '">'
            . '<img width="150px" style="margin:15px" src="https://2019mifacturita.s3.us-west-2.amazonaws.com/1624941410.png">'
            . '</div>'
            . '<div style="margin-bottom:20px;margin-left:10px">'
            . '<p>Estimado(a),</p>'
            . '</div>'
            . '<div style="margin-bottom:20px;margin-left:10px">'
            . '<p>' . $req->cliente . '</p>'
            . '</div>'
            . '<div style="margin-bottom:10px;margin-left:10px">'
            . '<p>Por encargo del emisor <span style="font-weight: bold;">' . $nombreEmpresa . '</span>, nos es grato informar que el documento electrónico ya se encuentra disponible con los siguientes datos:</p>'
            . '</div>'
            . '<hr style="border: 0.5px solid #000;">'
            . '<div style="margin-bottom:20px;margin-left:30px">'
            . '<p><span style="font-weight: bold;">Tipo: ' . $tipo . '</span></p>'
            . '<p><span style="font-weight: bold;">Número: ' . $guiaSelect->Serie . '-' . $numero . '</span></p>'
            . '<p><span style="font-weight: bold;">RUC / DNI: ' . $rucEmpresa . '</span></p>'
            . '<p><span style="font-weight: bold;">Fecha Emisión: ' . $formatoFecha . '</span></p>'
            . '<p><span style="font-weight: bold;">Fecha Traslado: ' . $formatoFecha2 . '</span></p>'
            . '</div>'
            . '<hr style="border: 0.5px solid #000;">'
            . '<div style="margin-bottom:20px;margin-left:10px">'
            . '<p>Los comprobantes también podrán ser consultados en el enlace: <a href="http://easyfactperu.pe/facturacion/">www.easyfactperu.pe</a>, ingresando mediante su usuario o utilizando nuestro acceso anónimo.</p>'
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
            if (unlink($rucEmpresa . '-09-' . $serie . '-' . $numero . '.pdf')) {
                //dd("eliminado");
            }
            if (unlink($rucEmpresa . '-09-' . $serie . '-' . $numero . '.xml')) {
                //dd("eliminado");
            }
            if (unlink($rucEmpresa . '-09-' . $serie . '-' . $numero . '.zip')) {
                //dd("eliminado");
            }
            return back()->with('status', 'Se envio correo con éxito');
        } else {
            return back()->with('error', 'No se pudo enviar correo');
        }
    }

    public function imprimirPDF(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $pdf = $this->generarPDF($req, $req->selectImpre, $id);
        $loadDatos = new DatosController();
        $guiaSelect = $loadDatos->getGuiaRemisionSelect($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $rucEmpresa = $empresa->Ruc;
        $numero = $guiaSelect->Numero;
        $serie = $guiaSelect->Serie;
        $idDoc = '09';

        return $pdf->stream($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numero . '.pdf');
    }

    private function generarPDF($req, $tipo, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $guiaSelect = $loadDatos->getGuiaRemisionSelect($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $fecha = date_create($guiaSelect->FechaEmision);
        $formatoFecha = date_format($fecha, 'd/m/Y');
        $fecha2 = date_create($guiaSelect->FechaTraslado);
        $formatoFecha2 = date_format($fecha2, 'd/m/Y');
        $resumen = $guiaSelect->Resumen;
        $numeroCerosIzquierda = $this->completarCeros($guiaSelect->Numero);
        $items = $loadDatos->getItemsGuias($id);
        $array = ['items' => $items, 'numeroCeroIzq' => $numeroCerosIzquierda, 'guiaSelect' => $guiaSelect, 'resumen' => $resumen,
            'formatoFecha' => $formatoFecha, 'formatoFecha2' => $formatoFecha2, 'empresa' => $empresa];
        view()->share($array);
        if ($tipo == 1) {
            $pdf = PDF::loadView('guiaRemisionPDF')->setPaper('a4', 'portrait');
        }
        if ($tipo == 2) {
            $pdf = PDF::loadView('guiaRemisionPDFA5')->setPaper('a5', 'portrait');
        }

        return $pdf;
    }

    private function completarCeros($numero)
    {
        $numeroConCeros = str_pad($numero, 8, "0", STR_PAD_LEFT);
        return $numeroConCeros;
    }
}
