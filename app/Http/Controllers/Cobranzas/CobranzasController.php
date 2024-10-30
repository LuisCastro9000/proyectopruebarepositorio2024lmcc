<?php

namespace App\Http\Controllers\Cobranzas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Session;
use DB;
use Carbon\Carbon;

class CobranzasController extends Controller
{
    public function index(Request $req) {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $fechas = $loadDatos->getFechaFiltro(5, null, null);
        //dd($fechas);
        $fechaInicial = Carbon::today()->subMonth(2)->firstOfMonth();
        $fechaFinal = Carbon::now();
        $cobranzas = DB::select('call sp_getCobranzas(?, ?, ?, ?)',array($idSucursal, null, $fechaInicial , $fechaFinal));
        //dd($cobranzas);
        //$cobranzas = $loadDatos->getCobranzas($idSucursal);
        
        $clientes = $loadDatos->getClientes($idSucursal);
        $permisos = $loadDatos->getPermisos($idUsuario);
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
		
        $fechaHoy = $loadDatos->getDateTime();
        $cobranzasTotales = $loadDatos->getCobranzasTotales($idSucursal, $fechaHoy);
        //dd($cobranzasTotales);
        $this->actualizarFechasPasados($idSucursal, $cobranzasTotales);

        $totalVentasCobrar = $loadDatos->getVentasCobrar($idSucursal, 1);
        $ventasCobrarConvertido = number_format($totalVentasCobrar[0]->TotalCobrar, 2, '.', ',');
        $noVencidos = $loadDatos->getCobranzasNoVencidos($idSucursal, 1);
        $noVencidoConvertido = number_format($noVencidos[0]->TotalNoVencido, 2, '.', ',');
        $vencidos = $loadDatos->getCobranzasVencidos($idSucursal, 1);
        $vencidoConvertido = number_format($vencidos[0]->TotalVencido, 2, '.', ',');
        $cantidadTotal = $noVencidos[0]->Cantidad + $vencidos[0]->Cantidad;
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        if(floatval($totalVentasCobrar[0]->TotalCobrar) > 0)
        {
            if($cantidadTotal > 0){
                $_porcentajeNoVencido = $noVencidos[0]->TotalNoVencido * 100/$totalVentasCobrar[0]->TotalCobrar;
                $porcentajeNoVencido = number_format($_porcentajeNoVencido, 2, '.', ',');
                $_porcentajeVencido = $vencidos[0]->TotalVencido * 100/$totalVentasCobrar[0]->TotalCobrar;
                $porcentajeVencido = number_format($_porcentajeVencido, 2, '.', ',');
            }else{
                $porcentajeNoVencido = 0;
                $porcentajeVencido = 0;
            }
        }else{
            $porcentajeNoVencido = 0;
            $porcentajeVencido = 0;
        }
        $inputcliente = '';
        $tipoPago = '';
        $fecha = '';
        $fechaIni = '';
        $fechaFin = '';

        $array = ['permisos' => $permisos, 'clientes' => $clientes,  'cobranzas' => $cobranzas, 'inputcliente' => $inputcliente, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'modulosSelect' => $modulosSelect,
                  'ventasCobrarConvertido' => $ventasCobrarConvertido, 'noVencidoConvertido' => $noVencidoConvertido, 'vencidoConvertido' => $vencidoConvertido, 'porcentajeNoVencido' => $porcentajeNoVencido, 'porcentajeVencido' => $porcentajeVencido,'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
        return view('cobranzas/cobranzas', $array);
    }
    
    public function store(Request $req) {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $inputcliente = $req->cliente;
        $fecha = $req->fecha;
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        $tipoPago = 1;
        if($fecha == 9){
            if($fechaIni == null || $fechaFin == null){
                return back()->with('error','Completar las fechas para filtrar');
            }
            if($fechaIni > $fechaFin){
                return back()->with('error','La fecha Inicial no puede ser mayor que la Final');
            }
        }
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');
        $permisos = $loadDatos->getPermisos($idUsuario);
		$clientes = $loadDatos->getClientes($idSucursal);
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
		
        $totalVentasCobrar = $loadDatos->getVentasCobrar($idSucursal, 1);
        
        $ventasCobrarConvertido= number_format($totalVentasCobrar[0]->TotalCobrar, 2, '.', ',');
        $noVencidos = $loadDatos->getCobranzasNoVencidos($idSucursal, 1);
        $noVencidoConvertido = number_format($noVencidos[0]->TotalNoVencido, 2, '.', ',');
        $vencidos = $loadDatos->getCobranzasVencidos($idSucursal, 1);
        $vencidoConvertido = number_format($vencidos[0]->TotalVencido, 2, '.', ',');
        $cantidadTotal = $noVencidos[0]->Cantidad + $vencidos[0]->Cantidad;
        if($cantidadTotal >0 ){
            $_porcentajeNoVencido = $noVencidos[0]->TotalNoVencido * 100/$totalVentasCobrar[0]->TotalCobrar;
            $porcentajeNoVencido = number_format($_porcentajeNoVencido, 2, '.', ',');
            $_porcentajeVencido = $vencidos[0]->TotalVencido * 100/$totalVentasCobrar[0]->TotalCobrar;
            $porcentajeVencido = number_format($_porcentajeVencido, 2, '.', ',');
        }else{
            $porcentajeNoVencido = 0;
            $porcentajeVencido = 0;
        }
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $cobranzas = $cobranzas = DB::select('call sp_getCobranzas(?, ?, ?, ?)',array($idSucursal, $inputcliente, $fechas[0], $fechas[1]));
       // $cobranzas = $loadDatos->getCobranzasFiltrados($idSucursal, $inputcliente, $fecha, $fechaIni, $fechaFin);
		
		$cobranzasTotalesFil = $loadDatos->getCobranzasTotalesFiltrado($idSucursal, $inputcliente, $fecha, $fechaIni, $fechaFin);
		
        $array = ['permisos' => $permisos, 'clientes' => $clientes,  'cobranzas' => $cobranzas, 'inputcliente' => $inputcliente, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'modulosSelect' => $modulosSelect, 'estados'=>$cobranzasTotalesFil,
                  'ventasCobrarConvertido' => $ventasCobrarConvertido, 'noVencidoConvertido' => $noVencidoConvertido, 'vencidoConvertido' => $vencidoConvertido, 'porcentajeNoVencido' => $porcentajeNoVencido, 'porcentajeVencido' => $porcentajeVencido, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
        return view('cobranzas/cobranzas', $array);
    }

    public function show(Request $req){
        $loadDatos = new DatosController();
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $totalVentasCobrar = $loadDatos->getVentasCobrar($idSucursal, $req->idTipoMoneda);
        $ventasCobrarConvertido = number_format($totalVentasCobrar[0]->TotalCobrar, 2, '.', ',');
        $noVencidos = $loadDatos->getCobranzasNoVencidos($idSucursal, $req->idTipoMoneda);
        $noVencidoConvertido = number_format($noVencidos[0]->TotalNoVencido, 2, '.', ',');
        $vencidos = $loadDatos->getCobranzasVencidos($idSucursal, $req->idTipoMoneda);
        $vencidoConvertido = number_format($vencidos[0]->TotalVencido, 2, '.', ',');
        $cantidadTotal = $noVencidos[0]->Cantidad + $vencidos[0]->Cantidad;
        if(floatval($totalVentasCobrar[0]->TotalCobrar) > 0)
        {
            if($cantidadTotal > 0){
                $_porcentajeNoVencido = $noVencidos[0]->TotalNoVencido * 100/$totalVentasCobrar[0]->TotalCobrar;
                $porcentajeNoVencido = number_format($_porcentajeNoVencido, 2, '.', ',');
                $_porcentajeVencido = $vencidos[0]->TotalVencido * 100/$totalVentasCobrar[0]->TotalCobrar;
                $porcentajeVencido = number_format($_porcentajeVencido, 2, '.', ',');
            }else{
                $porcentajeNoVencido = 0;
                $porcentajeVencido = 0;
            }
        }else{
            $porcentajeNoVencido = 0;
            $porcentajeVencido = 0;
        }
        return Response([$ventasCobrarConvertido, $noVencidoConvertido, $vencidoConvertido, $porcentajeNoVencido, $porcentajeVencido]);
    }
    
    private function actualizarFechasPasados($idSucursal, $noVencidos) {
        for($i=0; $i<count($noVencidos); $i++){
            DB::table('fecha_pago')
            ->join('ventas','fecha_pago.IdVenta', '=', 'ventas.IdVentas')
            ->where('ventas.IdSucursal',$idSucursal)
            ->where('fecha_pago.Estado', '!=', 2)
            ->where('fecha_pago.IdFechaPago',$noVencidos[$i]->IdFechaPago)
            ->update(['DiasPasados' => $noVencidos[$i]->Dias]);
        }
    }
}
