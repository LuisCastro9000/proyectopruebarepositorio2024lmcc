<?php

namespace App\Http\Controllers\Consultas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Session;
use DB;

class ConsultaArticuloStockController extends Controller
{
    public function index(Request $req, $idUser){
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        
        $permisos = $loadDatos->getPermisos($idUsuario);
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
		
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $userSelect = $loadDatos->getUsuarioSelect($idUser);
        $sucursales = $loadDatos->getSucursales($userSelect->CodigoCliente, $userSelect->IdOperador);
        $sucursal = 0;
        //$cantidadArticulos = $loadDatos->getArticulosCantidad($userSelect->CodigoCliente, 1, $sucursales[0]->IdSucursal);
        //$stockArticulos = $loadDatos->verificarStock($userSelect->CodigoCliente);
        
        $cantidadArticulos = [];
        $arrayValores = [];
        $arrayError = [];
        $arrayDifieren = [];
        /*for($i=0; $i<count($cantidadArticulos); $i++){
            if($cantidadArticulos[$i]->Stock != $cantidadArticulos[$i]->SumaTotal){
                $arrayValores[$i] = [$cantidadArticulos[$i]->IdArticulo, $cantidadArticulos[$i]->Descripcion, $cantidadArticulos[$i]->Stock, $cantidadArticulos[$i]->SumaTotal, "si", "si"];
                $arrayDifieren[$i] = [$cantidadArticulos[$i]->IdArticulo, "ok"];
            }else{
                $arrayValores[$i] = [$cantidadArticulos[$i]->IdArticulo, $cantidadArticulos[$i]->Descripcion, $cantidadArticulos[$i]->Stock, $cantidadArticulos[$i]->SumaTotal, "si", "no"]; 
            }
            
        }*/
        $array = ['sucursales' => $sucursales, 'sucursal' => $sucursal, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles, 'arrayValores' => $arrayValores, 'arrayError' => $arrayError, 'arrayDifieren' => $arrayDifieren, 'idUser' => $idUser];
        return view('consultas/consultaArticuloStock', $array);
    }

    public function store(Request $req, $idUser){
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        
        $permisos = $loadDatos->getPermisos($idUsuario);
		
		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
		$subniveles=$loadDatos->getSubNiveles($idUsuario);
		
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $sucursal = $req->sucursal;
        //dd($sucursal);
        $userSelect = $loadDatos->getUsuarioSelect($idUser);
        $sucursales = $loadDatos->getSucursales($userSelect->CodigoCliente, $userSelect->IdOperador);
        $arrayValores = [];
        $arrayError = [];
        $arrayDifieren = [];
        $cantidadArticulos = $loadDatos->getArticulosCantidad($userSelect->CodigoCliente, 1, $sucursal);
        //dd($cantidadArticulos);
        //$stockArticulos = $loadDatos->verificarStock($userSelect->CodigoCliente);
        $productos = DB::table('articulo')
                            ->where('IdSucursal', $sucursal)
                            ->where('IdTipo', 1)
                            ->where('Estado', 'E')
                            ->get();
        //dd($productos);
        $stock = DB::table('stock')
                            ->select('stock.*')
                            ->join('articulo','articulo.IdArticulo', '=', 'stock.IdArticulo')
                            ->where('articulo.IdSucursal', $sucursal)
                            ->where('articulo.IdTipo', 1)
                            ->where('articulo.Estado', 'E')
                            ->groupBy('stock.IdArticulo')
                            ->get();
        //dd($stock);
        $_array = [];
        for($j=0; $j<count($productos); $j++){
            //$filtered = $productos->whereNotIn('IdArticulo', $stock[$j]->IdArticulo);
            $band = $stock->contains('IdArticulo', $productos[$j]->IdArticulo);
            if(!$band){
                $arrayValores[$j] = [$productos[$j]->IdArticulo, $productos[$j]->Descripcion, $productos[$j]->Stock, 0, "no", "no"];
                $arrayError[$j] = [$productos[$j]->IdArticulo, "ok"];
            }
        }                    
        
        //dd($_array);
        
        for($i=0; $i<count($cantidadArticulos); $i++){
            if($cantidadArticulos[$i]->Stock != $cantidadArticulos[$i]->SumaTotal){
                $arrayValores[$i] = [$cantidadArticulos[$i]->IdArticulo, $cantidadArticulos[$i]->Descripcion, $cantidadArticulos[$i]->Stock, $cantidadArticulos[$i]->SumaTotal, "si", "si"];
                $arrayDifieren[$i] = [$cantidadArticulos[$i]->IdArticulo, "ok"];
            }else{
                $arrayValores[$i] = [$cantidadArticulos[$i]->IdArticulo, $cantidadArticulos[$i]->Descripcion, $cantidadArticulos[$i]->Stock, $cantidadArticulos[$i]->SumaTotal, "si", "no"]; 
            }
            
        }
        $array = ['sucursales' => $sucursales, 'sucursal' => $sucursal, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles, 'arrayValores' => $arrayValores, 'arrayError' => $arrayError, 'arrayDifieren' => $arrayDifieren, 'idUser' => $idUser];
        return view('consultas/consultaArticuloStock', $array);
    }

    public function completarTabla(Request $req){
        if ($req->session()->has('idUsuario')) {
            
            $idUser = $req->idUsuario;
            $sucursal = $req->idSucursal;
            //$idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $userSelect = $loadDatos->getUsuarioSelect($idUser);
            /*$cantidadArticulos = $loadDatos->getArticulosCantidad($userSelect->CodigoCliente, 1, $sucursal);
            for($i=0; $i<count($cantidadArticulos); $i++){
                $stockArticulos = $loadDatos->verificarStock($cantidadArticulos[$i]->IdArticulo);
                if(count($stockArticulos) > 0){
                }else{
                    $array = ['IdArticulo' => $cantidadArticulos[$i]->IdArticulo, 'Costo' => $cantidadArticulos[$i]->Costo, 'Precio' => $cantidadArticulos[$i]->Precio, 'Cantidad' => $cantidadArticulos[$i]->Stock];
                    DB::table('stock')->insert($array);
                }  
            }*/

            $productos = DB::table('articulo')
                            ->where('IdSucursal', $sucursal)
                            ->where('IdTipo', 1)
                            ->where('Estado', 'E')
                            ->get();
            //dd($productos);
            $stock = DB::table('stock')
                                ->select('stock.*')
                                ->join('articulo','articulo.IdArticulo', '=', 'stock.IdArticulo')
                                ->where('articulo.IdSucursal', $sucursal)
                                ->where('articulo.IdTipo', 1)
                                ->where('articulo.Estado', 'E')
                                ->groupBy('stock.IdArticulo')
                                ->get();
            //dd($stock);
            $_array = [];
            for($j=0; $j<count($productos); $j++){
                //$filtered = $productos->whereNotIn('IdArticulo', $stock[$j]->IdArticulo);
                $band = $stock->contains('IdArticulo', $productos[$j]->IdArticulo);
                if(!$band){
                    $array = ['IdArticulo' => $productos[$j]->IdArticulo, 'Costo' => $productos[$j]->Costo, 'Precio' => $productos[$j]->Precio, 'Cantidad' => $productos[$j]->Stock];
                    DB::table('stock')->insert($array);
                }
            }    

            return redirect('consultas/articulos-stock/'.$idUser)->with('status', 'Se completaron registros en la tabla Stock');
         

        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
    }

    public function emparejarCantidad(Request $req){
        if ($req->session()->has('idUsuario')) {
            //$idUsuario = Session::get('idUsuario');
           
            $idUser = $req->idUsuario;
            $sucursal = $req->idSucursal;
            $loadDatos = new DatosController();
            $userSelect = $loadDatos->getUsuarioSelect($idUser);
            $cantidadArticulos = $loadDatos->getArticulosCantidad($userSelect->CodigoCliente, 1, $sucursal);
            $count = 0;
            for($i=0; $i<count($cantidadArticulos); $i++){
                if($cantidadArticulos[$i]->Stock > $cantidadArticulos[$i]->SumaTotal){
                    $diferencia = $cantidadArticulos[$i]->Stock - $cantidadArticulos[$i]->SumaTotal;
                    $stock = $loadDatos->getUltimoStock($cantidadArticulos[$i]->IdArticulo);
                    $emparejar = floatval($diferencia) + floatval($stock->Cantidad);
                    DB::table('stock')
                            ->where('IdStock', $stock->IdStock)
                            ->update(['Cantidad' => $emparejar]);
                    $count++;
                }
                if($cantidadArticulos[$i]->Stock < $cantidadArticulos[$i]->SumaTotal){
                    DB::table('stock')
                        ->where('IdArticulo', $cantidadArticulos[$i]->IdArticulo)
                        ->update(['Cantidad' => 0]);

                    $stock = $loadDatos->getUltimoStock($cantidadArticulos[$i]->IdArticulo);

                    DB::table('stock')
                            ->where('IdStock', $stock->IdStock)
                            ->update(['Cantidad' => $cantidadArticulos[$i]->Stock]);
                    
                    $count++;
                }
                
                /*$stockArticulos = $loadDatos->verificarStock($cantidadArticulos[$i]->IdArticulo);
                if($cantidadArticulos[$i]->Stock > $stockArticulos[0]->SumaTotal){
                    $diferencia = $cantidadArticulos[$i]->Stock - $stockArticulos[0]->SumaTotal;
                    $stock = $loadDatos->getUltimoStock($cantidadArticulos[$i]->IdArticulo);
                    $emparejar = intval($diferencia) + intval($stock->Cantidad);
                    DB::table('stock')
                            ->where('IdStock', $stock->IdStock)
                            ->update(['Cantidad' => $emparejar]);
                    $count++;
                }
                if($cantidadArticulos[$i]->Stock < $stockArticulos[0]->SumaTotal){
                    DB::table('stock')
                        ->where('IdArticulo', $cantidadArticulos[$i]->IdArticulo)
                        ->update(['Cantidad' => 0]);

                    $stock = $loadDatos->getUltimoStock($cantidadArticulos[$i]->IdArticulo);

                    DB::table('stock')
                            ->where('IdStock', $stock->IdStock)
                            ->update(['Cantidad' => $cantidadArticulos[$i]->Stock]);
                    
                    $count++;
                }*/
            }
            return redirect('consultas/articulos-stock/'.$idUser)->with('status', 'Se emparejaron '.$count.' articulos');
         

        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
    }  

    public function consultarStockKardex(Request $req, $idUser){
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            
            $permisos = $loadDatos->getPermisos($idUsuario);
            
            $subpermisos=$loadDatos->getSubPermisos($idUsuario);
            $subniveles=$loadDatos->getSubNiveles($idUsuario);
            
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

            $userSelect = $loadDatos->getUsuarioSelect($idUser);
            //dd($userSelect->CodigoCliente);
            $articulosKardex = $this->getArticulosKardexCantidad($userSelect->CodigoCliente);
            //dd($articulosKardex);
            $arrayValores = [];
            $arrayDifieren = [];
            for($i=0; $i<count($articulosKardex); $i++){
                //$kardex = $this->kardexTotalProducto($articulosKardex[$i]->IdArticulo);

                /*$kardexEntrada = $kardex->where('EstadoStock','E')->sum('Cantidad');
                $kardexSalida = $kardex->where('EstadoStock','S')->sum('Cantidad');
                $totalKardex = $kardexEntrada - $kardexSalida;*/
                //dd($kardexEntrada);
                $articulosKardex[$i]->TotalKardex = DB::table('kardex')
                                                    ->select('existencia')
                                                    ->where('kardex.estado', 1)
                                                    ->where('kardex.IdArticulo', $articulosKardex[$i]->IdArticulo)
                                                    ->orderBy('IdKardex', 'desc')
                                                    ->first();
                                                    
                if($articulosKardex[$i]->Stock != $articulosKardex[$i]->TotalKardex->existencia){
                    $arrayValores[$i] = [$articulosKardex[$i]->IdArticulo, $articulosKardex[$i]->Descripcion, $articulosKardex[$i]->Stock, $articulosKardex[$i]->TotalKardex->existencia, "si"];
                    $arrayDifieren[$i] = [$articulosKardex[$i]->IdArticulo, "ok"];
                }else{
                    $arrayValores[$i] = [$articulosKardex[$i]->IdArticulo, $articulosKardex[$i]->Descripcion, $articulosKardex[$i]->Stock, $articulosKardex[$i]->TotalKardex->existencia, "no"]; 
                }
                
            }

            $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles, 'arrayValores' => $arrayValores, 'arrayDifieren' => $arrayDifieren, 'idUser' => $idUser];
            return view('consultas/consultaStockKardex', $array);
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        
    }

    public function emparejarStockKardex(Request $req){
        if($req->session()->has('idUsuario')) {
            $idUser = $req->idUser;
            $loadDatos = new DatosController();
            if(!empty($req->idArticulo)){
                for($i=0; $i<count($req->idArticulo); $i++){
                    $totalEmparejar = $this->getTotalKardexArticulo($req->idArticulo[$i]);
                    $totalKardex = $totalEmparejar->TotalKardex;
                    $producto = $loadDatos->getProductoSelect($req->idArticulo[$i]);
                    $diferencia = floatval($producto->Stock) - floatval($totalKardex);
                    DB::table('articulo')
                        ->where('IdArticulo', $req->idArticulo[$i])
                        ->update(['Stock' => $totalKardex]);

                    //$_stock = $loadDatos->getProductoStockSelect($req->idArticulo[$i]);
                    $this->actualizarStock($req->idArticulo[$i], $producto, $diferencia);	
                    /*if($diferencia > 0){
                        if($_stock[0]->Cantidad > 0){
                            DB::table('stock')
                                ->where('IdArticulo', $req->idArticulo[$i])
                                ->where('IdStock', $_stock[0]->IdStock)
                                ->decrement('Cantidad', $diferencia);
                        }else{
                            DB::table('stock')
                                ->where('IdArticulo', $req->idArticulo[$i])
                                ->where('IdStock', $_stock[1]->IdStock)
                                ->decrement('Cantidad', $diferencia);
                        }
                    }*/
                }
                return redirect('/consultas/articulos-kardex/'.$idUser)->with('status', 'Se emparejaron los productos seleccionados correctamente');
            }else{
                return redirect('/consultas/articulos-kardex/'.$idUser)->with('error', 'No se selecciono ningún producto a emparejar');
            }
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
    }

    private function actualizarStock($Id , $producto, $Cantidad) {
        $loadDatos = new DatosController();
        $productoSelect = $loadDatos->getProductoStockSelect($Id);
        
        if(count($productoSelect) >=1)  //evitar el no encontrar y el cero
        {
            if($Cantidad > $productoSelect[0]->Cantidad){
                //$ganancia += (int) $productoSelect[0]->Cantidad * ( (float) $productoSelect[0]->Precio - (float) $productoSelect[0]->Costo);
                $resto = (float) $Cantidad - (float) $productoSelect[0]->Cantidad;
                DB::table('stock')
                    ->where('IdStock', $productoSelect[0]->IdStock)
                    ->update(['Cantidad' => 0]);
                if($resto > $productoSelect[1]->Cantidad){
                    //$ganancia += $productoSelect[1]->Cantidad * ( (float) $productoSelect[1]->Precio - (float) $productoSelect[1]->Costo);
                    $resto = $resto - (float)$productoSelect[1]->Cantidad;
                    DB::table('stock')
                        ->where('IdStock', $productoSelect[1]->IdStock)
                        ->update(['Cantidad' => 0]);
                    if($resto > $productoSelect[2]->Cantidad){
                        //$ganancia += $productoSelect[2]->Cantidad * ( (float) $productoSelect[2]->Precio - (float) $productoSelect[2]->Costo);
                        $dif = (float)$productoSelect[2]->Cantidad - (float)$Cantidad;
                        DB::table('stock')
                            ->where('IdStock', $productoSelect[0]->IdStock)
                            ->update(['Cantidad' => $dif]);
                    }else{
                        //$ganancia += $resto * ( (float) $productoSelect[2]->Precio - (float) $productoSelect[2]->Costo);
                        //$dif = (int) $productoSelect[2]->Cantidad - $resto;
                        DB::table('stock')
                            ->where('IdStock', $productoSelect[2]->IdStock)
                            ->decrement('Cantidad', $resto);
                        }

                }else{
                    //$ganancia += $resto * ( (float) $productoSelect[1]->Precio - (float) $productoSelect[1]->Costo);
                    //$dif = (int) $productoSelect[1]->Cantidad - $resto;
                    DB::table('stock')
                    ->where('IdStock', $productoSelect[1]->IdStock)
                    ->decrement('Cantidad', $resto);
                }
            }else{
                //$ganancia += $Cantidad * ( (float) $productoSelect[0]->Precio - (float) $productoSelect[0]->Costo);
                //$dif = (int) $productoSelect[0]->Cantidad - (int) $Cantidad;
                DB::table('stock')
                    ->where('IdStock', $productoSelect[0]->IdStock)
                    ->decrement('Cantidad', $Cantidad);
            }
            //$arrayGanancias[$i] = $ganancia;
        }
    }

    public function getTotalKardexArticulo($idArticulo){
        $articulo = DB::table('kardex')
            ->select(DB::raw('SUM(kardex.Cantidad) as TotalKardex'))
            ->where('kardex.IdArticulo', $idArticulo)
            ->first();

        return $articulo;
    }

    public function getArticulosKardexCantidad($codCliente){
        /*$articulos = DB::table('articulo')
                ->join('usuario', 'usuario.IdUsuario', '=', 'articulo.IdCreacion')
                ->selectRaw('articulo.IdArticulo, articulo.Descripcion, articulo.Stock, articulo.Costo, articulo.Precio, (select existencia from kardex where kardex.IdArticulo = articulo.IdArticulo order by IdKardex desc limit 1) as TotalKardex')
                ->where('usuario.CodigoCliente', $codCliente)
                ->where('articulo.IdTipo', 1)
                ->where('articulo.Estado', 'E')
                ->orderBy('IdArticulo', 'desc')
                ->get();*/

            $articulos = DB::table('articulo')
                ->join('usuario', 'usuario.IdUsuario', '=', 'articulo.IdCreacion')
                ->select('articulo.IdArticulo', 'articulo.Descripcion', 'articulo.Stock', 'articulo.Costo', 'articulo.Precio')
                ->where('usuario.CodigoCliente', $codCliente)
                ->where('articulo.IdTipo', 1)
                ->where('articulo.Estado', 'E')
                ->get();
            
                

            /*for($i = 0; $i < count($articulos); $i++) {
                $articulos[$i]->TotalKardex = DB::table('kardex')
                                                    ->select('existencia')
                                                    ->where('kardex.estado', 1)
                                                    ->where('kardex.IdArticulo', $articulos[$i]->IdArticulo)
                                                    ->orderBy('IdKardex', 'desc')
                                                    ->first();
            }*/
            //dd($articulos);
        return $articulos;
    }

    /*public function getArticulosKardexCantidad($codCliente){
        $articulos = DB::table('articulo')
                ->join('usuario', 'usuario.IdUsuario', '=', 'articulo.IdCreacion')
                ->join('kardex', 'articulo.IdArticulo', '=', 'kardex.IdArticulo')
                ->select('articulo.IdArticulo', 'articulo.Descripcion', 'articulo.Stock', 'articulo.Costo', 'articulo.Precio')
                ->where('usuario.CodigoCliente', $codCliente)
                ->where('articulo.IdTipo', 1)
                ->where('articulo.Estado', 'E')
                ->groupBy(DB::raw("kardex.IdArticulo"))
                ->get();

        return $articulos;
    }*/

    public function kardexTotalProducto($idArticulo){
        $articulos = DB::table('kardex')
                ->join('movimiento_kardex', 'kardex.tipo_movimiento', '=', 'movimiento_kardex.IdMovimientoKardex')
                ->select('kardex.IdArticulo', 'kardex.CodigoInterno', 'kardex.existencia', 'kardex.Cantidad', 'movimiento_kardex.EstadoStock')
                ->where('kardex.IdArticulo', $idArticulo)
                ->get();

        return $articulos;
    }
}
