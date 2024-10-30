<?php

namespace App\Http\Controllers\Servicios;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Greenter\Ws\Services\SunatEndpoints;
use Greenter\Model\DocumentInterface;
use Greenter\Model\Response\CdrResponse;
use Greenter\XMLSecLibs\Certificate\X509Certificate;
use Greenter\XMLSecLibs\Certificate\X509ContentType;
use DB;
use Session;
use App\Http\Controllers\DatosController;
use Storage;
use PhpZip\ZipFile;
use PhpZip\Constants\ZipCompressionMethod;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;

class config extends Controller
{
    public function configuracion($endpoint) {
        //$pfx = file_get_contents(__DIR__.'/CertificadoDigital/DEMO-20482121072.pfx');
        /*$password = '123456';
        $certificate = new X509Certificate($pfx, $password);
        $pem = $certificate->export(X509ContentType::PEM);
        file_put_contents(__DIR__.'/CertificadoDigital/certificate.pem', $pem);
        
        $see = new \Greenter\See();
        $see->setService($endpoint);
        $see->setCertificate(file_get_contents(__DIR__.'/CertificadoDigital/certificate.pem'));
        $see->setCredentials('20482121072', '123456');*/
        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        
        /*$pfx = file_get_contents(__DIR__.'/CertificadoDigital/'.$empresa->Ruc.'.pfx');
        $password = $empresa->ClaveCertificado;
        $certificate = new X509Certificate($pfx, $password);
        $pem = $certificate->export(X509ContentType::PEM);
        file_put_contents(__DIR__.'/CertificadoDigital/firma'.$empresa->Ruc.'.pem', $pem);*/
        
        $see = new \Greenter\See();
        $see->setService($endpoint);
		
		/***********************************************merfi **************************************/
        //$see->setCertificate(file_get_contents(__DIR__.'/CertificadoDigital/'.$empresa->Ruc.'.pem'));
        //$see->setCredentials($empresa->Ruc.''.$empresa->UsuarioSol, $empresa->ClaveSol);
		/******************************************************************************************/
	
        $see->setCertificate(file_get_contents(__DIR__.'/CertificadoDigital/10439528422.pem'));
		//$see->setCertificate(file_get_contents(__DIR__.'/CertificadoDigital/20604636478.pem'));
        $see->setCredentials('20000000001MODDATOS', 'MODDATOS');

        /*$see = new \Greenter\See();
        $see->setService(SunatEndpoints::FE_BETA);
        $see->setCertificate(file_get_contents(__DIR__.'/CertificadoDigital/20604636478.pem'));
        $see->setCredentials('20604636478DCSFACIL', '@Dc*facil19@');*/

        return $see;
    }

    public function getTokenGRE($empresa){
        //https://api-seguridad.sunat.gob.pe/v1/clientessol/%3cclient_id%3e/oauth2/token/

        $client_id = $empresa->Client_Id;
        $client_secret = $empresa->Client_Secret;
        $ruc = $empresa->Ruc;
        $username = 'MODDATOS';
        $password = 'MODDATOS';
       
        /*client_id:test-85e5b0ae-255c-4891-a595-0b98c65c9854
        client_secret:test-Hty/M6QshYvPgItX2P0+Kw==
        username:20602957935MODDATOS
        password:MODDATOS*/

        /*$postData = array('grant_type' => 'password', 'scope' => 'https://api-cpe.sunat.gob.pe', 'client_id' => $client_id, 'client_secret' => $client_secret, 'username' => $empresa->UsuarioSol.'MODDATOS', 'password' => 'MODDATOS');
        $encodedData = json_encode($postData);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://gre-test.nubefact.com/v1/clientessol/'.$client_id.'/oauth2/token');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $encodedData);
        
        $response = curl_exec($curl);
        curl_close($curl);
        dd($response);*/

        $params = array('grant_type' => 'password', 'scope' => 'https://api-cpe.sunat.gob.pe', 'client_id' => $client_id, 'client_secret' => $client_secret, 'username' => $ruc.''.$username, 'password' => $password);
        $url = 'https://gre-test.nubefact.com/v1/clientessol/'.$client_id.'/oauth2/token';
        try {
			$client = new GuzzleClient();
			$res = $client->request('POST', $url, [
				'form_params' => $params
			]);

			$response = json_decode($res->getBody()->getContents(), true);
            //dd($response);
            return $response;

		} catch (ClientException $e) {
			$response = json_decode($e->getResponse()->getBody()->getContents(), true);

            return false;
		}
        
    }

    public function envioGRE($despatch, $access_token, $resultZip){

        $arcGreZip = base64_encode($resultZip);
        $hashZip = hash('sha256', $resultZip);

        $postData = array('archivo' => array('nomArchivo' => $despatch->getName().'.zip', 'arcGreZip' => $arcGreZip, 'hashZip' => $hashZip));
        $encodedData = json_encode($postData, true);

        $urlEnvio = 'https://gre-test.nubefact.com/v1/contribuyente/gem/comprobantes/'.$despatch->getName();

        $client = new GuzzleClient();

        $res = $client->request('POST', $urlEnvio, [
            'headers' => [
                'User-Agent' => 'GyOManager/1.0',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer ".$access_token,
            ],
            'body' => $encodedData,
        ]);

        $response = json_decode($res->getBody(), true);

        return $response;
    }

    public function consultaCDR($numTicket, $access_token){
        //$numTicket = 'test-c8b2fe92-8364-4add-b9bc-e5eb6e2f4f25';
        //$numTicket = $resEnvio['numTicket'];
        //dd($numTicket);
        $client = new GuzzleClient();

        $urlConsulta = 'https://gre-test.nubefact.com/v1/contribuyente/gem/comprobantes/envios/'.$numTicket;

        $res = $client->request('GET', $urlConsulta, [
            'headers' => [
                'Authorization' => "Bearer ".$access_token,
            ]
        ]);

        $response = json_decode($res->getBody(), true);
        
        return $response;
        //dd($response);

            
        /*$curl2 = curl_init();
        curl_setopt($curl2, CURLOPT_URL, 'https://gre-test.nubefact.com/v1/contribuyente/gem/comprobantes/envios/'.$numTicket); 
        curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($curl2, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token)); 
        
        $response2 = curl_exec($curl2); 
        curl_close($curl2);
        
        $arrayResponse2 = json_decode($response2, true);
        
        //$cdrGRE = base64_decode($arrayResponse2["arcCdr"]);
        //dd($arrayResponse2);*/
    }
    
    public function writeXml(DocumentInterface $document, $xml, $ruc, $anio, $mes, $tipoDoc)
    {
        $this->writeFile($document->getName(), $xml, $ruc, $anio, $mes, $tipoDoc, 1);
    }
    
    public function writeCdr(DocumentInterface $document, $zip, $ruc, $anio, $mes, $tipoDoc)
    {
        $this->writeFile('R-'.$document->getName().'.zip', $zip, $ruc, $anio, $mes, $tipoDoc, 2);
    }
    
    public function showResponse(DocumentInterface $document, CdrResponse $cdr)
    {
        $filename = $document->getName();
    }
    
    public function writeFile($filename, $content, $ruc, $anio, $mes, $tipoDoc, $tipoFile)
    {
        if (getenv('GREENTER_NO_FILES')) {
            return;
        }
        if($tipoDoc == 1){
            /*if (!is_dir(public_path().'/RespuestaSunat/FacturasBoletas/'.$ruc)) {
                mkdir(public_path().'/RespuestaSunat/FacturasBoletas/'.$ruc);
            }
            file_put_contents(public_path().'/RespuestaSunat/FacturasBoletas/'.$ruc.'/'.$filename, $content);*/
            if($tipoFile == 1){
                $ruta = 'RespuestaSunat/'.$anio.'/'.$mes.'/'.$ruc.'/FacturasBoletas/'.$filename.'.xml';
            }else{
                $ruta = 'RespuestaSunat/'.$anio.'/'.$mes.'/'.$ruc.'/FacturasBoletas/'.$filename;
            }
            
            Storage::disk('s3')->put($ruta, $content, 'public');
            //return Storage::disk('s3')->url($ruta);
            }
        if($tipoDoc == 2){
            /*if (!is_dir(public_path().'/RespuestaSunat/NotasCreditoDebito/'.$ruc)) {
                mkdir(public_path().'/RespuestaSunat/NotasCreditoDebito/'.$ruc);
            }
            file_put_contents(public_path().'/RespuestaSunat/NotasCreditoDebito/'.$ruc.'/'.$filename, $content);*/
            if($tipoFile == 1){
                $ruta = 'RespuestaSunat/'.$anio.'/'.$mes.'/'.$ruc.'/NotasCreditoDebito/'.$filename.'.xml';
            }else{
                $ruta = 'RespuestaSunat/'.$anio.'/'.$mes.'/'.$ruc.'/NotasCreditoDebito/'.$filename;
            }
            Storage::disk('s3')->put($ruta, $content, 'public');
        }
        if($tipoDoc == 3){
            /*if (!is_dir(public_path().'/RespuestaSunat/GuiasRemision/'.$ruc)) {
                mkdir(public_path().'/RespuestaSunat/GuiasRemision/'.$ruc);
            }
            file_put_contents(public_path().'/RespuestaSunat/GuiasRemision/'.$ruc.'/'.$filename, $content);*/
            /*$zipFile = new ZipFile();
            $zipFile->addFromString($filename.'.xml', $content, ZipCompressionMethod::DEFLATED);
            $zip = $zipFile->outputAsString();
            $zipFile->close();*/
            if($tipoFile == 1){
                $ruta = 'RespuestaSunat/'.$anio.'/'.$mes.'/'.$ruc.'/GuiasRemision/'.$filename.'.zip';
            }else{
                $ruta = 'RespuestaSunat/'.$anio.'/'.$mes.'/'.$ruc.'/GuiasRemision/'.$filename;
            }
            Storage::disk('s3')->put($ruta, $content, 'public');
        }
        if($tipoDoc == 4){
            /*if (!is_dir(public_path().'/RespuestaSunat/ResumenDiario/'.$ruc)) {
                mkdir(public_path().'/RespuestaSunat/ResumenDiario/'.$ruc);
            }
            file_put_contents(public_path().'/RespuestaSunat/ResumenDiario/'.$ruc.'/'.$filename, $content);*/
            if($tipoFile == 1){
                $ruta = 'RespuestaSunat/'.$anio.'/'.$mes.'/'.$ruc.'/ResumenDiario/'.$filename.'.xml';
            }else{
                $ruta = 'RespuestaSunat/'.$anio.'/'.$mes.'/'.$ruc.'/ResumenDiario/'.$filename;
            }
            Storage::disk('s3')->put($ruta, $content, 'public');
        }
        if($tipoDoc == 5){
            /*if (!is_dir(public_path().'/RespuestaSunat/BajaDocumentos/'.$ruc)) {
                mkdir(public_path().'/RespuestaSunat/BajaDocumentos/'.$ruc);
            }
            file_put_contents(public_path().'/RespuestaSunat/BajaDocumentos/'.$ruc.'/'.$filename, $content);*/
            if($tipoFile == 1){
                $ruta = 'RespuestaSunat/'.$anio.'/'.$mes.'/'.$ruc.'/BajaDocumentos/'.$filename.'.xml';
            }else{
                $ruta = 'RespuestaSunat/'.$anio.'/'.$mes.'/'.$ruc.'/BajaDocumentos/'.$filename;
            }
            Storage::disk('s3')->put($ruta, $content, 'public');
        }
    }
    
    public function getErrorResponse(\Greenter\Model\Response\Error $error)
    {
        $result = <<<HTML
        <b>Código:</b>{$error->getCode()}<br>
        <b>Descripción:</b>{$error->getMessage()}<br><br>
        <a href="/FacturasERP/public/panel"><button>Volver</button></a>
HTML;
        return $result;
    }
}
