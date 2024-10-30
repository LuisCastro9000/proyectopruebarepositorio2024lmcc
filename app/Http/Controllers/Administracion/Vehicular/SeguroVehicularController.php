<?php

namespace App\Http\Controllers\Administracion\Vehicular;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Session;
use DB;
use DateTime;

class SeguroVehicularController extends Controller
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
        
        $seguros = $loadDatos->getSegurosVehiculares($idSucursal);

        $permisos = $loadDatos->getPermisos($idUsuario);

  		$subpermisos=$loadDatos->getSubPermisos($idUsuario);
  		$subniveles=$loadDatos->getSubNiveles($idUsuario);
 
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

  		$array = ['seguros' => $seguros, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
  		return view('administracion/vehicular/seguro/seguros', $array);
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

        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos=$loadDatos->getSubPermisos($idUsuario);
        $subniveles=$loadDatos->getSubNiveles($idUsuario);
        $seguros = $loadDatos->getSeguros($idSucursal);
        $tipoDoc= $loadDatos->TipoDocumento();
        $departamentos = $loadDatos->getDepartamentos();

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        
        $array = ['tipoDoc' => $tipoDoc, 'permisos' => $permisos, 'departamentos' => $departamentos, 'modulosSelect' => $modulosSelect, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
        return view('administracion/vehicular/seguro/crear', $array);
    }

    public function store(Request $req) {
        try{
            $this->validateSeguro($req);            
            $tipoDoc = $req->tipoDocumento;
            $numDoc = $req->nroDocumento;
            $idDep = $req->departamento;
            $idPro = $req->provincia;
            $idDis = $req->distrito;
			
            
            if(strlen($numDoc) != 11){
                return back()->with('error', 'El RUC tiene que tener 11 dígitos')->withInput();
            }
            
            if($idDep == 0){
                return back()->with('error', 'Selecciona un departamento para el cliente')->withInput();
            }
            if($idPro == 0){
                return back()->with('error', 'Selecciona una provincia para el cliente')->withInput();
            }
            if($idDis == 0){
                return back()->with('error', 'Selecciona un distrito para el cliente')->withInput();
            }
			
			if($this->consultarDocBase($tipoDoc, $numDoc)) 
			{
				 return back()->with('error', 'El Numero de  Documento ya existe en nuestros registros')->withInput();
			}
            
            $loadDatos = new DatosController();
            $fecha = $loadDatos->getDateTime();
            $idUsuario = Session::get('idUsuario');
            $idSucursal = Session::get('idSucursal');
            $razonSocial = $req->razonSocial;
            $direccion = $req->direccion;
            $estado = 'E';
            
            $array = ['IdSucursal' => $idSucursal, 'Descripcion' => $razonSocial, 'NumeroDocumento' => $numDoc,
                      'Ubigeo' => $idDis, 'Direccion' => $direccion,'Estado' => $estado];
            DB::table('seguros')->insert($array);
            
            return redirect('administracion/vehicular/seguros-vehiculares')->with('status', 'Se creo seguro vehicular correctamente');
        } catch (Exception $ex){
            echo $ex->getMessage();
        }
    }

    public function edit(Request $req, $id){
        if ($req->session()->has('idUsuario')) {
           $idUsuario = Session::get('idUsuario');
       }else{
           Session::flush();
           return redirect('/')->with('out','Sesión de usuario Expirado');
       }
       $loadDatos = new DatosController();
       $seguroSelect = $loadDatos->getSeguroVehicularSelect($id);
       $tipoDoc= $loadDatos->TipoDocumento();
       $permisos = $loadDatos->getPermisos($idUsuario);
       
       $subpermisos=$loadDatos->getSubPermisos($idUsuario);
       $subniveles=$loadDatos->getSubNiveles($idUsuario);
       
       $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
       $departamentos = $loadDatos->getDepartamentos();
       $provincias = $loadDatos->getProvincias($seguroSelect->IdDepartamento);
       $distritos = $loadDatos->getDistritos($seguroSelect->IdProvincia);
       $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
       $array = ['seguroSelect' => $seguroSelect, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'departamentos' => $departamentos, 'provincias' => $provincias, 'distritos' => $distritos, 'subpermisos'=>$subpermisos, 'subniveles'=>$subniveles];
       return view('administracion/vehicular/seguro/editar', $array);
   }
   
   public function update(Request $req, $id) {
        try{
            $this->validateSeguro($req);
            $tipoDoc = $req->tipoDocumento;
            $numDoc = $req->nroDocumento;
            $idDep = $req->departamento;
            $idPro = $req->provincia;
            $idDis = $req->distrito;
            if($tipoDoc == 2){
                if(strlen($numDoc) != 11){
                    return back()->with('error', 'El RUC tiene que tener 11 dígitos');
                }
            }
            if($idDep == 0){
                return back()->with('error', 'Selecciona un departamento para el cliente')->withInput();
            }
            if($idPro == 0){
                return back()->with('error', 'Selecciona una provincia para el cliente')->withInput();
            }
            if($idDis == 0){
                return back()->with('error', 'Selecciona un distrito para el cliente')->withInput();
            }
            
            /*if($this->updateDocBase($tipoDoc, $numDoc, $id)) 
            {
                return back()->with('error', 'El Numero de  Documento ya existe en nuestros registros')->withInput();
            }*/
            

            $loadDatos = new DatosController();
            $fecha = $loadDatos->getDateTime();
            $idUsuario = Session::get('idUsuario');
            $razonSocial = $req->razonSocial;
            $direccion = $req->direccion;

            $array = ['Descripcion' => $razonSocial, 'NumeroDocumento' => $numDoc, 'Ubigeo' => $idDis, 'Direccion' => $direccion];
            
            DB::table('seguros')
                    ->where('IdSeguro', $id)
                    ->update($array);
            
            return redirect('administracion/vehicular/seguros-vehiculares')->with('status', 'Se actualizo cliente correctamente');
                
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function delete($id) {
        try{
            $array = ['Estado' => 'D'];
            DB::table('seguros')
                    ->where('IdSeguro', $id)
                    ->update($array);
            
            return redirect('administracion/vehicular/seguros-vehiculares')->with('status', 'Se elimino seguro vehicular correctamente');
                  
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function consultarDoc(Request $req) {
        if($req->ajax()){
            $idDoc = $req->idDoc;
            $numDoc = $req->numDoc;
            $loadDatos = new DatosController();

            if($idDoc == 1){
                $url = 'https://dniruc.apisperu.com/api/v1/dni/'.$numDoc.'?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImZhY3R1cmFjaW9uQGVhc3lmYWN0cGVydS5wZSJ9.45m3851f7G3SqNhT4MZ20YTZt6h4HD3YIf8pfwZgGJI';
                //$url = 'https://dniruc.apisperu.com/api/v1/dni/'.$numDoc.'?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Im1hcmNvLm1hbGxtYTIwMUBnbWFpbC5jb20ifQ.tnR51gvdQl1DO4ovYJFjGlu9EFzwx1wSOA3Nd_BIxrg';
            }
            
            if($idDoc == 2){
                $url = 'https://dniruc.apisperu.com/api/v1/ruc/'.$numDoc.'?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImZhY3R1cmFjaW9uQGVhc3lmYWN0cGVydS5wZSJ9.45m3851f7G3SqNhT4MZ20YTZt6h4HD3YIf8pfwZgGJI';
                //$url = 'https://dniruc.apisperu.com/api/v1/ruc/'.$numDoc.'?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Im1hcmNvLm1hbGxtYTIwMUBnbWFpbC5jb20ifQ.tnR51gvdQl1DO4ovYJFjGlu9EFzwx1wSOA3Nd_BIxrg';
            }

            $curl = curl_init();
            
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            
            $_result = curl_exec($curl);
            $result = json_decode($_result, true);
            curl_close($curl);

            //return Response([$result]);
            //$result = 'http://ruc.aqpfact.pe/sunat/'.$numDoc;

            
            if(!empty($result)){
                $mensaje = 1;
                if($idDoc == 1){
                    $data = array(
                        0 => $mensaje,
                        1 => $idDoc,
                        2 => $numDoc,
                        3 => $result["apellidoPaterno"].' '.$result["apellidoMaterno"].' '.$result["nombres"]
                    );
                }
                if($idDoc == 2){
                    $arrayProvincias = [];
                    $arrayDistritos = [];
                    if($result["departamento"] != null){
                        $dep = DB::table('departamento')
                                        ->where('Nombre',$result["departamento"])
                                        ->first();
                        $departamento = $dep->IdDepartamento;

                        $arrayProvincias = $loadDatos->getProvincias($departamento);
                        $pro = DB::table('provincia')
                                ->where('Nombre',$result["provincia"])
                                ->first();
                        $provincia = $pro->IdProvincia;

                        $arrayDistritos = $loadDatos->getDistritos($provincia);
                        $dis = DB::table('distrito')
                                ->where('Nombre',$result["distrito"])
                                ->first();
                        $distrito = $dis->IdDistrito;

                    }else{
                        $departamento = null;
                        $provincia = null;
                        $distrito = null;
                    }
                    $data = array( 
                        0 => $mensaje,
                        1 => $idDoc,
                        2 => $numDoc,
                        3 => $result["razonSocial"],
                        4 => $result["nombreComercial"],
                        5 => $result["direccion"],
                        6 => $departamento,
                        7 => array(0 => $provincia, 1 => $arrayProvincias),
                        8 => array(0 => $distrito, 1 => $arrayDistritos),
                        9 => $result["condicion"],
                        10 => $result["estado"]
                    );
                }
                return Response($data);
            }else{
                $mensaje = 0;
                $data = array(
                    0 => $mensaje,
                );
                return Response($data);
            }
        }
    }


	protected function validateSeguro(Request $request) {
        $this->validate($request, [
            'tipoDocumento' => 'required',
            'nroDocumento' => 'required',
            'razonSocial' => 'required',
            'direccion' => 'required'
        ]);
    }

    private function consultarDocBase($idTipo, $doc)
	{
		$idSucursal = Session::get('idSucursal');
		$documento = DB::table('seguros')
			 				->where('NumeroDocumento', $doc)
							->where('IdSucursal', $idSucursal)
			 				->where('Estado', 'E')
                            ->get();
                            		
        if(count($documento) >= 1)
		{
			return TRUE;
		}
		else
		{
			return False;
		}
	}
}
