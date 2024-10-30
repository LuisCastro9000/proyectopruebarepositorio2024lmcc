<?php

namespace App\Http\Controllers\Reportes\Facturacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use App\Http\Controllers\Reportes\AjusteFechasReportesController;
use Session;
use DB;
use DateTime;
use Excel;
use Carbon\Carbon;
use App\Exports\ExcelReporteGuia;

class ReporteGuiaController extends Controller
{
    public function index(Request $req){
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
		
		$arrayClientes =  [];
		$arrayVentas = [];
		
        $loadDatos = new DatosController();
        //$fechasReportes = new AjusteFechasReportesController();
        $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);
        
    	$subpermisos=$loadDatos->getSubPermisos($idUsuario);
    	$subniveles=$loadDatos->getSubNiveles($idUsuario);
        
        $inputcliente = 0;
        $tipoPago = 0;
        $fecha = 5;
        $fechaIni = '';
        $fechaFin = '';
        $ini='';
    	$fin='';
        $reporteClientes = $this->getGuiasClientes($idSucursal, $fecha, $fechaIni, $fechaFin);
        $ventasContado = '';
        $ventasCredito = '';
        $descuentoContado = '';
        $descuentoCredito = '';
	
        $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
		$clientes = DB::table('guia_remision as gr')
			->join('cliente','gr.IdCliente', '=', 'cliente.IdCliente')
			->where('gr.IdSucursal',$idSucursal)
            ->whereBetween('gr.FechaEmision', [$fechas[0], $fechas[1]])
			->select(DB::raw('count(*) as total, cliente.Nombre '), 'cliente.RazonSocial')
			->groupBy(DB::raw("cliente.Nombre"))
			->get();
	
        // $arrayClientes = $clientes->pluck('RazonSocial')->toArray();
        // $arrayVentas = $clientes->pluck('total')->toArray();
         if(count($clientes) >= 1)
            {
                $i = 0;
                foreach($clientes as $cliente )
                {
                    $arrayClientes[$i] = "'$cliente->Nombre'";
                    $arrayVentas[$i] = $cliente->total;
                    $i++;
                }
            }
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['grafCliente'=>$arrayClientes, 'grafTotal'=>$arrayVentas, 'reporteClientes' => $reporteClientes, 'clientes' => $clientes, 'inputcliente' => $inputcliente, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini'=>$ini, 'fin'=>$fin,
                'ventasContado' => $ventasContado, 'ventasCredito' => $ventasCredito, 'descuentoContado' => $descuentoContado, 'descuentoCredito' => $descuentoCredito, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
        return view('reportes/facturacion/reporteGuias', $array);
    }

    public function store(Request $req) {
        $loadDatos = new DatosController();
        if($req->fecha == "0"){
            //dd("entro");
            $fecha = 0;
        }else{
            //dd("no entro");
            $fecha = $req->fecha;
        }
    
        if($req->cliente == null){
            $inputcliente = 0;
        }else{
            $inputcliente = $req->cliente;
        }
        $tipoPago = 0;
        //$fecha = $req->fecha;
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        if($fecha == 9){
            if($fechaIni == null || $fechaFin == null){
                return back()->with('error','Completar las fechas para filtrar');
            }
            if($fechaIni > $fechaFin){
                return back()->with('error','La fecha Inicial no puede ser mayor que la Final');
            }
        }
        $ini= str_replace('/','-', $fechaIni);
        $fin= str_replace('/','-', $fechaFin);
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');
        $permisos = $loadDatos->getPermisos($idUsuario);

    	$subpermisos=$loadDatos->getSubPermisos($idUsuario);
    	$subniveles=$loadDatos->getSubNiveles($idUsuario);

        $reporteClientes = $this->getGuiasClientesFiltrados($idSucursal, $inputcliente, $tipoPago, $fecha, $fechaIni, $fechaFin);
        $clientesGraf = $this->grafgetGuiasClientesFiltrados($idSucursal, $inputcliente, $tipoPago, $fecha, $fechaIni, $fechaFin);
		
		$ventasContado = $reporteClientes->where('IdTipoPago',1)->sum('Total');
        $ventasCredito = $reporteClientes->where('IdTipoPago',2)->sum('Total');
        $descuentoContado = $reporteClientes->where('IdTipoPago',1)->sum('Exonerada');
        $descuentoCredito = $reporteClientes->where('IdTipoPago',2)->sum('Exonerada');
        // $clientes = $loadDatos->getClientes($idSucursal);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        //dd($inputcliente."- ".$tipoPago."- ".$fecha."- ".$ini."- ".$fin);
        //dd($ini." - ".$fin);
        //dd($clientesGraf);
        $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
		$clientes = DB::table('guia_remision as gr')
			->join('cliente','gr.IdCliente', '=', 'cliente.IdCliente')
			->where('gr.IdSucursal',$idSucursal)
            ->whereBetween('gr.FechaEmision', [$fechas[0], $fechas[1]])
			->select(DB::raw('count(*) as total, cliente.Nombre '), 'cliente.RazonSocial')
			->groupBy(DB::raw("cliente.RazonSocial"))
			->get();
       
		$arrayClientes =[];
		$arrayVentas= [];
		
		if(count($clientesGraf) >= 1)
        {
			$i = 0;
			foreach($clientesGraf as $clieGraf )
			{
				$arrayClientes[$i] = "'$clieGraf->Nombre'";
				$arrayVentas[$i] = $clieGraf->total;
				$i++;
			}
	    }	
		
		$array = ['grafCliente'=>$arrayClientes, 'grafTotal'=>$arrayVentas, 'reporteClientes' => $reporteClientes, 'clientes' => $clientes, 'inputcliente' => $inputcliente, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini'=>$ini, 'fin'=>$fin,
            'ventasContado' => $ventasContado, 'ventasCredito' => $ventasCredito, 'descuentoContado' => $descuentoContado, 'descuentoCredito' => $descuentoCredito, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
        //dd($array);
        return view('reportes/facturacion/reporteGuias', $array);
    }

    public function exportExcel( $inputcliente=null, $tipoPago=0, $fecha=null, $ini=null, $fin=null)
    {
      $tipoPago=0;
	  $loadDatos = new DatosController();
      $idUsuario = Session::get('idUsuario');
      $idSucursal = Session::get('idSucursal');

      $fechaIni= str_replace('-','/', $ini);
      $fechaFin= str_replace('-','/', $fin);
      $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
      //dd($inputcliente."- ".$tipoPago."- ".$fecha."- ".$ini."- ".$fin);
      //$reporteVendedores = $loadDatos->getVentasVendedoresFiltrados($idSucursal, $inputvendedor, $tipoPago, $fecha, $fechaIni, $fechaFin);

      /*$reporteClientes = $this->getGuiasClientesFiltrados($idSucursal, $inputcliente, 0, $fecha, $fechaIni, $fechaFin);
      
      $array = ['reporteClientes' => $reporteClientes];

      Excel::create('Reporte Clientes', function ($excel) use($array){
                $excel->sheet('Reporte Clientes', function ($sheet) use($array) {
                $sheet->getStyle('A:I', $sheet->getHighestRow())->getAlignment()->setWrapText(false);
                $sheet->loadView('excel/reporteGuiasExcel', $array);
              });
          })->download('xlsx');*/
          $reporteClientes = $this->getGuiasClientesFiltrados($idSucursal, $inputcliente, 0, $fecha, $fechaIni, $fechaFin);
      
          return Excel::download(new ExcelReporteGuia($reporteClientes), 'Reporte Guias.xlsx');
        // return Excel::download(new ExcelReporteGuia($idSucursal, $inputcliente, 0, $fechas[0], $fechas[1]), 'Reporte Guias.xlsx');
    }

    public function getGuiasClientes($idSucursal, $fecha, $fechaIni, $fechaFin) {
        try{
            $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            $ventas= DB::table('guia_remision as gr')
                        ->join('cliente','gr.IdCliente', '=', 'cliente.IdCliente')
                        ->join('sucursal','gr.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario','gr.IdUsuario', '=', 'usuario.IdUsuario')
                        ->select('gr.*', 'cliente.Nombre as Nombres', 'usuario.Nombre as Usuario', 'cliente.RazonSocial')
                        ->where('gr.IdSucursal', $idSucursal)
                        ->whereBetween('gr.FechaEmision', [$fechas[0], $fechas[1]])
                        ->orderBy('gr.FechaEmision','desc')
                        ->get();
            return $ventas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getGuiasClientesFiltrados($idSucursal, $cliente, $tipoPago, $fecha, $fechaIni, $fechaFin) {
        try{
            $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            if($cliente == 0){
                 if($tipoPago == 0){
                    $ventas= DB::table('guia_remision as gr')
                        ->join('cliente','gr.IdCliente', '=', 'cliente.IdCliente')
                        ->join('sucursal','gr.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario','gr.IdUsuario', '=', 'usuario.IdUsuario')
                        ->select('gr.*', 'cliente.Nombre as Nombres', 'usuario.Nombre as Usuario', 'cliente.RazonSocial')
                        ->where('gr.IdSucursal', $idSucursal)
                        ->whereBetween('gr.FechaEmision', [$fechas[0], $fechas[1]])
                        ->orderBy('IdGuiaRemision','desc')
                        ->get();
                    return $ventas;
                }
            }else{
                if($tipoPago == 0){
                    $ventas= DB::table('guia_remision as gr')
                        ->join('cliente','gr.IdCliente', '=', 'cliente.IdCliente')
                        ->join('sucursal','gr.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario','gr.IdUsuario', '=', 'usuario.IdUsuario')
                        ->select('gr.*', 'cliente.Nombre as Nombres',  'usuario.Nombre as Usuario', 'cliente.RazonSocial')
                        ->where('gr.IdSucursal', $idSucursal)
                        ->where('cliente.RazonSocial', $cliente)
                        ->whereBetween('gr.FechaEmision', [$fechas[0], $fechas[1]])
                        ->orderBy('IdGuiaRemision','desc')
                        ->get();
                    return $ventas;

                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

	public function grafgetGuiasClientesFiltrados($idSucursal, $cliente, $tipoPago, $fecha, $fechaIni, $fechaFin){
		try{
            $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            if($cliente == 0){
                 if($tipoPago == 0){

				 	$clientes = DB::table('guia_remision as gr')
					->join('cliente','gr.IdCliente', '=', 'cliente.IdCliente')
					->where('gr.IdSucursal',$idSucursal)
			        ->select(DB::raw('count(*) as total, cliente.Nombre '), 'cliente.RazonSocial')
					->whereBetween('gr.FechaEmision', [$fechas[0], $fechas[1]])
			        ->groupBy(DB::raw("cliente.Nombre"))
			        ->get();

                    return $clientes;
                }
            }else{
                if($tipoPago == 0){

					$clientes = DB::table('guia_remision as gr')
		            	->join('cliente','gr.IdCliente', '=', 'cliente.IdCliente')
		            	->select(DB::raw('count(*) as total, cliente.Nombre '), 'cliente.RazonSocial')
						->where('gr.IdSucursal',$idSucursal)
						->where('cliente.RazonSocial', $cliente)
						->whereBetween('gr.FechaEmision', [$fechas[0], $fechas[1]])
		            	->groupBy(DB::raw("cliente.Nombre"))
		            	->get();

                    return $clientes;

                }
            }
        }catch(Exception $ex) {
            echo $ex->getMessage();
        }
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
