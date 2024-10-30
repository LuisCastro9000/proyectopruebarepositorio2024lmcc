<?php

namespace App\Http\Controllers\Administracion\Vehicular;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Session;
use DB;
use DateTime;
use Carbon\Carbon;

class VehicularController extends Controller
{
    public function index(Request $req){
		if ($req->session()->has('idUsuario')){
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $texto = "";
        $fecha = '';
        $fechaIni = '';
        $fechaFin = '';
        $ini='';
    	$fin='';
        $band='';

        //  // Nuevo codigo
        //     $placaVehiculo = DB::table('vehiculo')
        //     ->where('IdSucursal', $idSucursal)
        //     ->where('PlacaVehiculo', $req->placa)
        //     ->exists();
        // // Fin

  		//$marcaVehiculo = $this->Marca($idSucursal);
  		//$modeloVehiculo = $this->Modelo($idSucursal);
  		//$tipoVehiculo = $this->Tipo($idSucursal);
  		//$vehiculo = $this->vehiculo($idSucursal);
        $fechas = $loadDatos->getFechaFiltro(5, null, null);
        $vehiculo = DB::select('call sp_getVehiculos(?, ?, ?)',array($idSucursal, $fechas[0], $fechas[1]));
        //dd($vehiculo);
        $permisos = $loadDatos->getPermisos($idUsuario);

  		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
  		$subniveles=$loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

  		$array = ['vehiculos' => $vehiculo, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini'=>$ini, 'fin'=>$fin, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'band' => $band, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
  		return view('administracion/vehicular/vehiculos', $array);
	}

    public function filtrar(Request $req){
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $fecha = $req->fecha;
            $fechaIni = $req->fechaIni;
            $fechaFin = $req->fechaFin;
            $ini='';
            $fin='';
            $band=1;

            if($fecha == 9){
                if($fechaIni == null || $fechaFin == null){
                    return back()->with('error','Completar las fechas para filtrar');
                }
                if(strtotime($fechaIni) > strtotime($fechaFin)){
                    return back()->with('error','La fecha Inicial no puede ser mayor que la Final');
                }
            }

            $ini= str_replace('/','-', $fechaIni);
      	    $fin= str_replace('/','-', $fechaFin);

            //$marcaVehiculo = $this->Marca($idSucursal);
            $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            $vehiculo = DB::select('call sp_getVehiculos(?, ?, ?)',array($idSucursal, $fechas[0], $fechas[1]));
            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos=$loadDatos->getSubPermisos($idUsuario);
            $subniveles=$loadDatos->getSubNiveles($idUsuario);

            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

            $array = ['vehiculos' => $vehiculo, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'ini'=>$ini, 'fin'=>$fin, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'band' => $band, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
            return view('administracion/vehicular/vehiculos', $array);

        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
    }

	public function create(Request $req) {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');

        $marcaVehiculo = $this->Marca($idSucursal);
        $modeloVehiculo = $this->Modelo($idSucursal);
        $tipoVehiculo = $this->Tipo($idSucursal);
        $clientes = $loadDatos->getClientes($idSucursal);

        /*$fecha=date("d/m/Y");
        dd($fecha);*/
        $fecha = Carbon::today();
        $fechaFuturo = $fecha->startOfMonth()->startOfYear()->addYears(28)->format("Y-m-d");

        $arrayAnio = [];
        $date = new DateTime();
        $anio = intval($date->format("Y"));
        for($i=0; $i<50; $i++)
        {
            array_push($arrayAnio, $anio-$i);
        }

        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos=$loadDatos->getSubPermisos($idUsuario);
        $subniveles=$loadDatos->getSubNiveles($idUsuario);
        $seguros = $loadDatos->getSeguros($idSucursal);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $array = ['clientes'=>$clientes, 'seguros' => $seguros, 'marcas'=>$marcaVehiculo, 'modelos'=>$modeloVehiculo, 'tipos'=>$tipoVehiculo, 'fechaFuturo' => $fechaFuturo, 'arrayAnio' => $arrayAnio, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
        return view('administracion/vehicular/crear', $array);
    }

    public function store(Request $req) {
        try{
            if ($req->session()->has('idUsuario')) {
                $idUsuario = Session::get('idUsuario');
            }else{
                Session::flush();
                return redirect('/')->with('out','Sesión de usuario Expirado');
            }
			$idSucursal = Session::get('idSucursal');
            $loadDatos = new DatosController();
			$this->validateVehiculo($req);

			$tipoVehiculo = $req->tipoVehiculo;
            if($tipoVehiculo == 1){
                if($req->placa == ''){
                    return back()->with('error', 'Ingrese el número de placa')->withInput();
                }else{
                    $placa = strtoupper(trim($req->placa));
                }
            }else{
                if($req->placaMoto == ''){
                    return back()->with('error', 'Ingrese el número de placa')->withInput();
                }else{
                    $placa = strtoupper(trim($req->placaMoto));
                }
            }
			$vowels = array("/", "@", ".", "_", "$", "&", "<", ">", "#", "?", "%", "!","[","]", "{", "}", "\"", "(", ")", "=");
			$placa = str_replace($vowels, "", $placa);

			if($this->validatePlaca($placa, $idSucursal))
			{
				return back()->with('error', 'El Formato de Placa no es el correcto ó La Placa ya existe en nuestros registros')->withInput();
			}

            $cliente = $req->cliente;
            $anio = $req->anio;
            $chasis = $req->chasis;
            $kilometraje = $req->kilometraje;
            $horometro = $req->horometro;
            $color = $req->color;
            $marca = $req->marca;
            $modelo = $req->modelo;
            $tipo = $req->tipo;
            $nota = $req->nota;
            $estado = $req->radioOpcion;
            $motor = $req->motor;
            $fechaSoat = $req->fechaSoat;
            $fechaRevTecnica = $req->fechaRevTecnica;
            $nroFlota = $req->flota;
            $periodo = $req->periodoMantenimientoKm;
            $fechaIngreso = $loadDatos->getDateTime();

            if($req->seguro != null){
                $seguro = $req->seguro;
            }else{
                $seguro = 1;
            }

            if($req->fechaCertAnual != null){
                $fechaCertAnual = $req->fechaCertAnual;
            }else{
                $fechaCertAnual = null;
            }

            if($req->fechaPrueQuin != null){
                $fechaPrueQuin = $req->fechaPrueQuin;
            }else{
                $fechaPrueQuin = null;
            }

            $array = ['IdSucursal' => $idSucursal, 'IdSeguro' => $seguro, 'TipoVehicular' => $tipoVehiculo, 'PlacaVehiculo' => $placa, 'ChasisVehiculo' => $chasis, 'HorometroInicial' => $horometro, 'KilometroInicial' => $kilometraje, 'Color' => $color, 'Anio' => $anio, 'Motor' => $motor, 'NumeroFlota' => $nroFlota,
			          'FechaSoat' => $fechaSoat, 'FechaRevTecnica' => $fechaRevTecnica, 'CertificacionAnual' => $fechaCertAnual, 'PruebaQuinquenal' => $fechaPrueQuin, 'IdMarcaVehiculo' =>$marca, 'IdModeloVehiculo'=>$modelo, 'IdTipoVehiculo'=>$tipo, 'NotaVehiculo' => $nota, 'IdCreacion' => $idUsuario, 'IdCliente' =>$cliente, 'FechaIngreso' => $fechaIngreso, 'Estado' => $estado, 'PeriodoMantenimientoKm' => $periodo];
                    DB::table('vehiculo')->insert($array);

                    return redirect('administracion/vehicular/registrar')->with('status', 'Se registro el vehiculo correctamente');

        }catch(Exception $ex){
            echo $ex->getMessage();
        }
    }

    public function edit(Request $req, $id) {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        }else{
            Session::flush();
            return redirect('/')->with('out','Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $categoria = $loadDatos->getCategoriaSelect($id);
        $permisos = $loadDatos->getPermisos($idUsuario);
        $marcaVehiculo = $this->Marca($idSucursal);
    	$modeloVehiculo = $this->Modelo($idSucursal);
    	$tipoVehiculo = $this->Tipo($idSucursal);
        $clientes = $loadDatos->getClientes($idSucursal);

        $arrayAnio = [];
        $date = new DateTime();
        $anio = intval($date->format("Y"));
        for($i=0; $i<50; $i++)
        {
            array_push($arrayAnio, $anio-$i);
        }

    	$subpermisos=$loadDatos->getSubPermisos($idUsuario);
    	$subniveles=$loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $vehiculo = $this->vehiculoSelect($id);
        $fechaSoat = $vehiculo->FechaSoat;
        $fechaRevTecnica = $vehiculo->FechaRevTecnica;
        $fechaCertAnual = $vehiculo->CertificacionAnual;
        $fechaPrueQuin = $vehiculo->PruebaQuinquenal;
        $seguros = $loadDatos->getSeguros($idSucursal);

        $array = ['vehiculo'=>$vehiculo, 'seguros' => $seguros, 'fechaCertAnual' => $fechaCertAnual, 'fechaPrueQuin' => $fechaPrueQuin, 'fechaSoat' => $fechaSoat, 'fechaRevTecnica' => $fechaRevTecnica, 'clientes'=>$clientes, 'marcas'=>$marcaVehiculo, 'modelos'=>$modeloVehiculo, 'tipos'=>$tipoVehiculo, 'arrayAnio' => $arrayAnio, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
        return view('administracion/vehicular/editar', $array);

    }

    public function update(Request $req, $id) {
        try{
            $this->validateVehiculo($req);
            $loadDatos = new DatosController();
            $fecha = $loadDatos->getDateTime();
            $idUsuario = Session::get('idUsuario');

			$placa = strtoupper(trim($req->placa));
			$vowels = array("/", "@", ".", "_", "$", "&", "<", ">", "#", "?", "%", "!","[","]", "{", "}", "\"", "(", ")", "=");
			$placa = str_replace($vowels, "", $placa);

			if($this->updatePlaca($placa, $id))
			{
				return back()->with('error', 'El Formato de Placa no es el correcto ó La Placa ya existe en nuestros registros')->withInput();
			}

            $cliente = $req->cliente;
            $anio = $req->anio;
            $chasis = $req->chasis;
            $kilometraje = $req->kilometraje;
            $horometro = $req->horometro;
            $color = $req->color;
            $marca = $req->marca;
            $modelo = $req->modelo;
            $tipo = $req->tipo;
            $nota = $req->nota;
            $estado = $req->radioOpcion;
            $motor = $req->motor;
            $fechaSoat = $req->fechaSoat;
            $fechaRevTecnica = $req->fechaRevTecnica;
            $nroFlota = $req->flota;
            $periodo = $req->periodoMantenimientoKm;

            if($req->seguro != null){
                $seguro = $req->seguro;
            }else{
                $seguro = 1;
            }

            if($req->fechaCertAnual != null){
                $fechaCertAnual = $req->fechaCertAnual;
            }else{
                $fechaCertAnual = null;
            }

            if($req->fechaPrueQuin != null){
                $fechaPrueQuin = $req->fechaPrueQuin;
            }else{
                $fechaPrueQuin = null;
            }

            $array = ['IdSeguro' => $seguro, 'PlacaVehiculo' => $placa, 'ChasisVehiculo' => $chasis, 'HorometroInicial' => $horometro, 'KilometroInicial' => $kilometraje, 'Color' => $color, 'Anio' => $anio, 'Motor' => $motor, 'NumeroFlota' => $nroFlota, 'FechaSoat' => $fechaSoat, 'FechaRevTecnica' => $fechaRevTecnica,
                        'CertificacionAnual' => $fechaCertAnual, 'PruebaQuinquenal' => $fechaPrueQuin, 'IdMarcaVehiculo' =>$marca, 'IdModeloVehiculo'=>$modelo, 'IdTipoVehiculo'=>$tipo, 'NotaVehiculo' => $nota, 'IdCliente' =>$cliente, 'Estado' => $estado, 'PeriodoMantenimientoKm' => $periodo];

            DB::table('vehiculo')
                    ->where('IdVehiculo', $id)
                    ->update($array);

            return redirect('administracion/vehicular/registrar')->with('status', 'Se actualizo Vehiculo correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function delete($id) {
        try{
            $array = ['Estado' => 0];
            DB::table('vehiculo')
                    ->where('IdVehiculo', $id)
                    ->update($array);

            return redirect('administracion/vehicular/registrar')->with('status', 'Se elimino vehiculo correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

	protected function vehiculo($idSucursal)
	{
		/* $vehiculo = DB::table('vehiculo')
					   ->where('IdSucursal', $idSucursal)
					   ->get(); */

		$vehiculo = DB::table('vehiculo as v')
						->join('cliente as c','v.IdCliente', '=', 'c.IdCliente')
						->join('marca_general as mg','mg.IdMarcaGeneral', '=', 'v.IdMarcaVehiculo')
						->join('tipo_general as tg','tg.IdTipoGeneral', '=', 'v.IdTipoVehiculo')
						->join('modelo_general as mog','mog.IdModeloGeneral', '=', 'v.IdModeloVehiculo')
                        ->join('seguros as s','s.IdSeguro', '=', 'v.IdSeguro')
						->select('v.IdVehiculo', 'v.PlacaVehiculo', 'v.Estado', 'c.RazonSocial', 'mg.NombreMarca', 'mog.NombreModelo', 'tg.NombreTipo', 's.Descripcion as Seguro')
					   ->where('v.IdSucursal', $idSucursal)
					   ->orderBy('v.FechaIngreso', 'desc')
					   ->get();
		return $vehiculo;
	}

    protected function vehiculoSelect($id)
	{
		$vehiculo = DB::table('vehiculo')
             ->where('IdVehiculo', $id)
					   ->first();
		return $vehiculo;
	}

	protected function Marca($idSucursal)
	{
		$marca = DB::table('marca_general')
					   ->where('IdSucursal', $idSucursal)
					   ->where('UsoMarca', 1)
					   ->where('Estado', 1)
					   ->get();
		return $marca;
	}

	protected function Modelo($idSucursal)
	{
		$modelo = DB::table('modelo_general')
					   ->where('IdSucursal', $idSucursal)
					   ->where('Estado', 1)
					   ->where('UsoModelo', 1)
					   ->get();
		return $modelo;
	}

	protected function Tipo($idSucursal)
	{
		$tipo = DB::table('tipo_general')
					   ->where('IdSucursal', $idSucursal)
					   ->where('Estado', 1)
					   ->where('UsoTipo', 1)
					   ->get();
		return $tipo;
	}

	protected function validateVehiculo(Request $request) {
        $this->validate($request, [
            'cliente' => 'required|numeric',
            'anio' => 'required',
            'color' => 'required',
            'marca' => 'required|numeric',
            'modelo' => 'required|numeric',
            'tipo' => 'required|numeric',
        ]);
    }

	protected function validatePlaca($clPlaca, $sucursal)
	{
		$placa = str_replace("-", "", $clPlaca);

		$placa = DB::table('vehiculo')
			 			->whereRaw("replace(PlacaVehiculo,'-','')= ?", [$placa])
						->where('IdSucursal', $sucursal)
			 			->where('Estado', 1)
                        ->first();

        if($placa)
		{
			return TRUE;
		}
		else
		{
			return False;
		}
	}

	private function updatePlaca($clPlaca, $id)
	{
		$idSucursal = Session::get('idSucursal');
		$placa = str_replace("-", "", $clPlaca);

		$documento = DB::table('vehiculo')
			 				->whereRaw("replace(PlacaVehiculo,'-','')= ?", [$placa])
			 				->where('IdVehiculo', '!=', $id)
			 				->where('IdSucursal', $idSucursal)
			 				->where('Estado', 1)
                            ->first();

        if($documento)
		{
			return TRUE;
		}
		else
		{
			return False;
		}
	}
}
