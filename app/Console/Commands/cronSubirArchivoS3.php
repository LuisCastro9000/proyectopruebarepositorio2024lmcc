<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
// Uses
use App\Http\Controllers\DatosController;
use DB;
use Illuminate\Console\Command;
use PHPMailer\PHPMailer\PHPMailer;
use Storage;

// Fin

class cronSubirArchivoS3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subirArchivoS3:archivoXmlCdr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $loadDatos = new DatosController();
        $fechas = $loadDatos->getFechaFiltro(1, null, null);
        $resultadoFiltro = DB::table('filtros_correo')->select('IdCategoria', 'NombreCorreoXml', 'IdSucursal', 'IdUsuario', 'Estado')->get();
        $resultadoFiltro = collect($resultadoFiltro);
        $estadoCheckEnvio = $resultadoFiltro->pluck('Estado')->first();
        if ($estadoCheckEnvio == "Activado") {

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
            $mail->addAddress('pruebasoporte@autocontrol.pe', 'Comprobante Automatico');
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Motoquad XML Desde el Entorno de Desarrollo';
            $mail->msgHTML('<div>Envio de correo automatico XML</div>');
            $enviado = $mail->send();

            $texto = "Enviando xml por tarea programada nuevamente 2023";
            // Storage::disk('local')->put("archivo.txt", $texto);
            Storage::append("archivo.txt", $texto);
        }
        /*$loadDatos = new DatosController();
        $idUsuario = 239;
        $idSucursal = 112;
        $anio = 2022;
        $mes = 6;
        $tipoComprobante = 1;

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $_mes = $loadDatos->getMes($mes);
        $archivosXML = $loadDatos->getArchivosXML($idSucursal, $tipoComprobante, $anio, $mes);
        if($tipoComprobante == 1){
        for ($i=0; $i<count($archivosXML); $i++) {
        $nombreArchivo = $empresa->Ruc.'-'.$archivosXML[$i]->IdTipoSunat.'-'.$archivosXML[$i]->Serie.'-'.$archivosXML[$i]->Numero;
        $archivoXml = public_path().'/RespuestaSunat/FacturasBoletas/'.$empresa->Ruc.'/'.$nombreArchivo.'.xml';
        $archivoCdr = public_path().'/RespuestaSunat/FacturasBoletas/'.$empresa->Ruc.'/R-'.$nombreArchivo.'.zip';
        //dd($archivo);
        if (file_exists($archivoXml)){
        $archivoXmlRead = file_get_contents($archivoXml);
        $rutaXml = '/RespuestaSunat/'.$anio.'/'.$_mes.'/'.$empresa->Ruc.'/FacturasBoletas/'.$nombreArchivo.'.xml';
        DB::table('ventas')
        ->where('IdVentas', $archivosXML[$i]->IdVentas)
        ->update(["RutaXml"=>$rutaXml]);
        Storage::disk('s3')->put($rutaXml, $archivoXmlRead, 'public');
        //dd($xmlObject);
        }
        if (file_exists($archivoCdr)){
        $archivoCdrRead = file_get_contents($archivoCdr);
        $rutaCDR = '/RespuestaSunat/'.$anio.'/'.$_mes.'/'.$empresa->Ruc.'/FacturasBoletas/R-'.$nombreArchivo.'.zip';
        DB::table('ventas')
        ->where('IdVentas', $archivosXML[$i]->IdVentas)
        ->update(['RutaCdr'=>$rutaCDR]);
        Storage::disk('s3')->put($rutaCDR, $archivoCdrRead, 'public');
        }
        usleep(25000);
        }
        }*/

        /*$loadDatos = new DatosController();
    $fechas = $loadDatos->getFechaFiltro(1, null, null);
    $resultadoFiltro = DB::table('filtros_correo')->select('IdCategoria','NombreCorreoXml', 'IdSucursal', 'IdUsuario','Estado')->get();
    $resultadoFiltro = collect($resultadoFiltro);
    $estadoCheckEnvio = $resultadoFiltro->pluck('Estado')->first();

    if ($estadoCheckEnvio == "Activado") {
    $nombreCorreoXml = $resultadoFiltro->pluck('NombreCorreoXml')->first();
    $IdSucursal = $resultadoFiltro->pluck('IdSucursal')->first();
    $idUsuario = $resultadoFiltro->pluck('IdUsuario')->first();
    $IdCategoria = $resultadoFiltro->pluck('IdCategoria')->first();

    //  Datos del RUC
    $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
    $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
    $rucEmpresa = $empresa->Ruc;
    // Fin

    // CODIGO PARA CARGAR LOS DATOS EN EL XML
    $resultado = DB::table('ventas_articulo')
    ->join('ventas','ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
    ->join('articulo','ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
    ->select( 'ventas.IdTipoComprobante', 'Serie', 'Numero')
    ->where('ventas.IdSucursal',$IdSucursal)
    ->where('articulo.IdCategoria',$IdCategoria)
    ->whereIn('ventas.IdTipoComprobante', [1,2])
    ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
    ->get();

    if (count($resultado) >= 1) {

    // CODIGO ENVIAR EL EMAIL
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
    $mail->addAddress($nombreCorreoXml, 'Comprobante');
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'Motoquad XML';
    for ($i=0; $i < count( $resultado); $i++) {
    if($resultado[$i]->IdTipoComprobante == 1){
    $file = $rucEmpresa.'-03-'.$resultado[$i]->Serie.'-'.$resultado[$i]->Numero;
    }
    if($resultado[$i]->IdTipoComprobante == 2){
    $file = $rucEmpresa.'-01-'.$resultado[$i]->Serie.'-'.$resultado[$i]->Numero;
    }
    $mail->addAttachment( public_path().'/RespuestaSunat/FacturasBoletas/'.$rucEmpresa.'/'.$file.'.xml');
    }
    $mail->msgHTML('<div>Envio de correo automatico XML</div>');
    $enviado =$mail->send();
    }
    }*/
    }
}
