<?php

namespace App\Http\Controllers\Reportes\Facturacion;;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Session;
use DB;
use DateTime;
use PDF;
use Excel;
use Carbon\Carbon;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use App\Exports\ExcelReporteVentas;
use App\Exports\ExcelReporteVentasFormato;
/* use Illuminate\Database\Events\StatementPrepared;
use Illuminate\Support\Facades\Event;
use PDO; */ 

class ReporteVentasElectronicas extends Controller
{
    public function index(Request $req){
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
			session(['idUserExcel' => $idUsuario]);			
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesi�n de usuario Expirado');
        }

		$valor=5;
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');	
        //$facturasVentas = $loadDatos->getVentasAll($idSucursal);
        
        $permisos = $loadDatos->getPermisos($idUsuario);
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
		
        $date = Carbon::today();
        $dateAtras = $date->subDays(7)->startOfDay()->format("Y-m-d H:i:s");
        $tipoPago = '';
        $fecha = '';
        $fechaIni = '';
        $fechaFin = '';
		$ini = 0;
		$fin = 0;
        $facturasVentas = $loadDatos->getVentasElectronicas($idSucursal, 0, 5, $fechaIni, $fechaFin);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $array = ['datosEmpresa' => $datosEmpresa, 'ini'=>$ini,  'fin'=>$fin, 'defecto'=>$valor, 'facturasVentas' => $facturasVentas, 'permisos' => $permisos, 'IdTipoPago' => 0, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'dateAtras' => $dateAtras, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
        return view('reportes/facturacion/reporteVentasElectronicas', $array);
    }
	
	public function store(Request $req) {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesi�n de usuario Expirado');
        }
        $loadDatos = new DatosController();
        //$tipoPago = $req->tipoPago;
        $fecha = $req->fecha;
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        $date = Carbon::today();
        $dateAtras = $date->subDays(7)->startOfDay()->format("Y-m-d H:i:s");
        if($fecha == 9){
            if($fechaIni == null || $fechaFin == null){
                return back()->with('error','Completar las fechas para filtrar');
            }
            if($fechaIni > $fechaFin){
                return back()->with('error','La fecha Inicial no puede ser mayor que la Final');
            }
			$ini = str_replace('/','-',$req->fechaIni);
			$fin = str_replace('/','-',$req->fechaFin);
        }
		else
		{
			$ini = 0;
			$fin = 0;
		}
        
        $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
		
        $facturasVentas = $loadDatos->getVentasElectronicas($idSucursal, 0, $fecha, $fechaIni, $fechaFin);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $array = ['datosEmpresa' => $datosEmpresa, 'ini'=>$ini,  'fin'=>$fin, 'defecto'=>$fecha,'facturasVentas' => $facturasVentas, 'IdTipoPago' => 0, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'dateAtras' => $dateAtras, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
		return view('reportes/facturacion/reporteVentasElectronicas', $array);
    }

    public function exportTextoPlano($fecha=null, $fechaIni=null, $fechaFin=null){
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $fechaIni= str_replace('-','/', $fechaIni); 
		$fechaFin= str_replace('-','/', $fechaFin);
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $facturasVentas = $this->reporteVentasTextoPlano($idSucursal, $idUsuario, $fechas[0], $fechas[1]);
        //dd($facturasVentas);
        $content = null;
        $docResumen = [];
        $countVentas = count($facturasVentas);
        $i = 1;
        foreach ($facturasVentas as $doc){
            $content .= $empresa->Ruc.'|'.$doc->Documento.'|'.$doc->Serie.'|'.$doc->Numero.'|'.$doc->FechaCreacion.'|'.$doc->ImporteTotal;
            if($i < $countVentas ){
                $content .= "\n";
            }
            $i++;
        }
        //$string_encoded = iconv( mb_convert_encoding($content, 'ANSI'), 'Windows-1252//TRANSLIT', $content);
        $string_encoded = iconv("UTF-8", "Windows-1252//TRANSLIT", $content);
        $file = "Texto_Plano.txt";
        $txt = fopen($file, "w") or die("Unable to open file!");
        fwrite($txt, $string_encoded);
        fclose($txt);

        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename='.basename($file));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        header("Content-Type: text/plain");
        readfile($file);

        /*$file_name = 'archivo.txt';
        $f = file_get_contents($file_name);
        $f = iconv("UTF-8", "WINDOWS-1252", $f);
        file_put_contents($file_name, $f);*/
    }
	
	public function exportExcel($fecha=null, $fechaIni=null, $fechaFin=null)
	{		
		//$usuario = session('idUserExcel');
        $loadDatos = new DatosController();
		$idSucursal = Session::get('idSucursal');	
		$fechaIni= str_replace('-','/', $fechaIni); 
		$fechaFin= str_replace('-','/', $fechaFin);
		 
		$fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);	

        return Excel::download(new ExcelReporteVentas($idSucursal, $fechas[0], $fechas[1]), 'Registro de Ventas.xlsx');
	}

    public function exportExcelPLE($fecha=null, $fechaIni=null, $fechaFin=null)
	{		
		//$usuario = session('idUserExcel');
        $loadDatos = new DatosController();
		$idSucursal = Session::get('idSucursal');	
		$fechaIni= str_replace('-','/', $fechaIni); 
		$fechaFin= str_replace('-','/', $fechaFin);
		 
		$fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);	

        return Excel::download(new ExcelReporteVentasFormato($idSucursal, $fechas[0], $fechas[1]), 'Registro de Ventas.xlsx');
	}

    private function reporteVentasTextoPlano($idSucursal, $idUsuario, $fecha1, $fecha2){
    	$facturasVentas = DB::select("(SELECT DATE_FORMAT(v.FechaCreacion, '%d/%m/%Y') as 'FechaCreacion', v.IdTipoSunat as 'Documento', v.Serie, v.Numero , c.RazonSocial, c.NumeroDocumento as 'DocumentoReceptor', v.Estado, IF(v.TipoVenta=1, 'Gravada', 'Exonerada') as 'TipoOperacion', IF(v.CodigoDoc=0, '-' ,v.CodigoDoc) as 'CodError', IF(v.IdTipoPago=1, 'Contado','Credito') as 'TipoPago', IF(v.IdTipoPago=2,  DATE_FORMAT(DATE_ADD(v.FechaCreacion, interval v.PlazoCredito DAY), '%d-%m-%Y'), '-') as 'FechaVencimiento', v.Subtotal as 'BaseImponible', v.IGV, (v.Total + v.Amortizacion) as 'ImporteTotal', tm.CodigoMoneda, if(1 > 1, '-', '-') as 'SerieRef', if(1 > 1, '-', '-') as 'NumeroRef' 
							FROM 
							ventas v inner join cliente c ON v.IdCliente = c.IdCliente inner join tipo_moneda tm ON v.IdTipoMoneda = tm.IdTipoMoneda WHERE v.IdTipoComprobante != 3 AND v.IdSucursal= ? AND v.FechaCreacion BETWEEN ? AND ?)
							union ALL 
							(SELECT DATE_FORMAT(ncd.FechaCreacion, '%d/%m/%Y') as fecha, ncd.IdTipoSunat, ncd.Serie, ncd.Numero, c.RazonSocial, c.NumeroDocumento, ncd.Estado, IF(ncd.TipoVenta=1, 'Gravada', 'Exonerada') as 'TipoOperacion', IF(ncd.CodigoDoc=0, '-' ,ncd.CodigoDoc), '-', '-', ncd.Subtotal, ncd.IGV, ncd.Total, tm.CodigoMoneda, SUBSTRING_INDEX(ncd.DocModificado, '-', 1),  SUBSTRING_INDEX(ncd.DocModificado, '-', -1) 
							FROM 
							nota_credito_debito ncd inner join cliente c ON ncd.IdCliente = c.IdCliente inner join tipo_nota tn ON ncd.IdTipoNota = tn.IdTipoNota inner join tipo_moneda tm ON ncd.IdTipoMoneda = tm.IdTipoMoneda WHERE ncd.IdSucursal = ? AND ncd.FechaCreacion BETWEEN ? AND ?) order by FechaCreacion desc",
    		[$idSucursal, $fecha1, $fecha2, $idSucursal, $fecha1, $fecha2]);
    	//$facturasVentas = collect($_facturasVentas);
        return $facturasVentas;
    }
	
	private function getFechaFiltro($fecha, $fechaIni, $fechaFin) {
        if($fecha == 0){
            $fechaInicio = '1900-01-01';
            $fechaFinal = Carbon::now();
            return Array($fechaInicio,$fechaFinal);
        }
        if($fecha == 1){
            $fechaInicio = Carbon::today();
            $fechaFinal = Carbon::now();
            return Array($fechaInicio,$fechaFinal);
        }
        if($fecha == 2){
            $fechaInicio = Carbon::yesterday();
            $fechaFinal = Carbon::today();
            return Array($fechaInicio,$fechaFinal); 
        }
        if($fecha == 3){
            $datePrev = Carbon::today()->dayOfWeek; 
            $date = Carbon::today();
            $fechaInicio = $date->subDays($datePrev-1);
            $fechaFinal = Carbon::now();
            return Array($fechaInicio,$fechaFinal); 
        }
        if($fecha == 4){
            $datePrev = Carbon::today()->dayOfWeek; 
            $date1 = Carbon::today();
            $date2 = Carbon::today();
            $fechaFinal = $date1->subDays($datePrev-1);
            $fechaInicio = $date2->subDays($datePrev+6);
            return Array($fechaInicio,$fechaFinal);
        }
        if($fecha == 5){
            $datePrev = Carbon::today()->day;
            $date = Carbon::today();
            $fechaInicio = $date->subDays($datePrev-1);
            $fechaFinal = Carbon::now();
            return Array($fechaInicio,$fechaFinal); 
        }
        if($fecha == 6){
            $datePrev = Carbon::today()->day;
            $mesPasado = Carbon::today()->subMonth(1)->firstOfMonth();
            $date1 = Carbon::today();
            $fechaFinal = $date1->subDays($datePrev-1);
            $fechaInicio = $mesPasado;
            return Array($fechaInicio,$fechaFinal);
        }
        if($fecha == 7){
            $datePrev = Carbon::today()->firstOfYear();
            $fechaInicio = $datePrev;
            $fechaFinal = Carbon::now();
            return Array($fechaInicio,$fechaFinal); 
        }
        if($fecha == 8){
            $fechaInicio = Carbon::today()->subYear(1)->firstOfYear();
            $fechaFinal = Carbon::today()->subYear(1)->endOfYear();
            return Array($fechaInicio,$fechaFinal); 
        }
        if($fecha == 9){
            $fechaInicio = DateTime::createFromFormat('d/m/Y',$fechaIni);
            $fechaFinal = DateTime::createFromFormat('d/m/Y',$fechaFin);
            $fechaConvertidaInicio = $fechaInicio->format("Y-m-d");
            $fechaConvertidaFinal = $fechaFinal->format("Y-m-d");
            $fechaConvertidaFinal = strtotime('+1 day',strtotime($fechaConvertidaFinal));
            $fechaConvertidaFinal = date('Y-m-d',$fechaConvertidaFinal);
            return Array($fechaConvertidaInicio,$fechaConvertidaFinal);
        }
    }
}