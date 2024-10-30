<?php

namespace App\Http\Controllers\Administracion\Almacen;

use App\Exports\ExcelReporteServicios;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use App\Imports\ExcelServiciosImportacion;
use App\Traits\GestionarImagenesS3Trait;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use Session;

class ServiciosController extends Controller
{
    use GestionarImagenesS3Trait;
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $texto2 = "";
        $idSucursal = Session::get('idSucursal');
        $servicios = $loadDatos->getServiciosPagination($idSucursal, 1, $texto2);
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        // dd($servicios);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['servicios' => $servicios, 'textoBuscar' => '', 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'texto2' => $texto2, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
        return view('administracion/almacen/servicios/servicios', $array);
    }

    // NUEVA FUNCION ELIMINACION MASIVA
    public function getVistaEliminacionMasiva(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $servicios = $loadDatos->getServicios($idSucursal);

        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'servicios' => $servicios];
        return view('administracion/almacen/servicios/eliminacionMasiva', $array);
    }

    public function eliminacionMasiva(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');

            if (!empty($req->id)) {
                for ($i = 0; $i < count($req->id); $i++) {
                    DB::table('articulo')
                        ->where('IdArticulo', $req->id[$i])
                        ->update(['Estado' => 'D', 'IdEliminacion' => $idUsuario, 'FechaEliminacion' => Carbon::now()->toDateTimeString()]);
                }
                return redirect()->route('vistaServiciosEliminacionMasiva')->with('status', 'Se eliminaron los productos seleccionados correctamente');
            } else {
                return redirect()->route('vistaServiciosEliminacionMasiva')->with('error', 'No se selecciono ningún servicio a eliminar');
            }
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }
    // FIN

    public function importarExcelServicios(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            DB::beginTransaction();
            try {
                $idUsuario = Session::get('idUsuario');
                if ($req->hasFile('datosExcelServicios')) {
                    $idSucursal = Session::get('idSucursal');
                    $datosExcelServicio = Excel::toCollection(new ExcelServiciosImportacion, $req->datosExcelServicios);
                    // Accede a la segunda posicion para obtener los nombres de los encabezados del excel
                    $nombresHeaderExcel = $datosExcelServicio->first()->first();
                    // verifica si dentro del array de los nombres, existe NULL
                    if ($nombresHeaderExcel->contains(null)) {
                        return redirect('administracion/almacen/servicios')->with('error', 'El encabezado del archivo no debe tener columnas vacias');
                    }
                    $i = 0;
                    $arrayServiciosNoGuardados = [];
                    $arrayServiciosConCostoMayor = [];
                    $mensajeServiciosConMayorCosto = '';
                    $mensajeServiciosNoGuardados = '';
                    foreach ($datosExcelServicio->first() as $row) {
                        if ($i > 0) {
                            $nombreServicio = $row[0];
                            if ($nombreServicio != null) {
                                $precio = (float) $row[1];
                                $costo = (float) $row[2];
                                $tipoMoneda = $row[3];
                                $idUnidadMedida = 11;
                                $date = Carbon::now();
                                $codigoBarra = $row[4];
                                $codigoInterno = round(microtime(true) * 1000);

                                if ($tipoMoneda == 'Soles' || $tipoMoneda == 'SOLES' || $tipoMoneda == 'soles' || $tipoMoneda == null) {
                                    $tipoMoneda = 1;
                                } else {
                                    $tipoMoneda = 2;
                                }

                                $serviciosDupicados = $this->getBuscarProducto($nombreServicio, $idSucursal);
                                if (count($serviciosDupicados) >= 1) {
                                    $arrayServiciosNoGuardados[$i - 1] = $nombreServicio;
                                    $mensajeServiciosNoGuardados = 'Se importarón los servicios correctamente y se encontrarón Duplicados';
                                } else {
                                    // Nuevo codigo
                                    if (floatval($costo) >= floatval($precio)) {
                                        $arrayServiciosConCostoMayor[$i - 1] = $nombreServicio;
                                        $mensajeServiciosConMayorCosto = 'Se importarón los servicios correctamente y los Servicios de las lista no se registrarón por  motivo que el COSTO es mayor que el PRECIO';
                                    }
                                    // Fin
                                    $array = ['IdTipo' => 2, 'IdUnidadMedida' => $idUnidadMedida, 'IdSucursal' => $idSucursal, 'IdTipoMoneda' => $tipoMoneda, 'FechaCreacion' => $date, 'IdCreacion' => $idUsuario, 'Descripcion' => $nombreServicio, 'Precio' => $precio, 'Exonerado' => 1, 'Costo' => $costo, 'Codigo' => $codigoBarra, 'CodigoInterno' => $codigoInterno, 'TipoOperacion' => 1, 'Imagen' => 'https://easyfactperu2021.s3.us-west-2.amazonaws.com/1641830772.png', 'Estado' => 'E'];
                                    DB::table('articulo')->insert($array);
                                }
                            }
                        }
                        $i++;
                        usleep(100000);
                    }

                } else {
                    return redirect('administracion/almacen/servicios')->with('error', 'No se ha seleccionado un archivo');
                }
                DB::commit();
                if (count($arrayServiciosNoGuardados) >= 1 || count($arrayServiciosConCostoMayor) >= 1) {
                    return redirect('administracion/almacen/servicios')->with('arrayServiciosNoGuardados', collect($arrayServiciosNoGuardados))->with('errorServiciosDuplicados', $mensajeServiciosNoGuardados)->with('arrayServiciosConMayorCosto', collect($arrayServiciosConCostoMayor))->with('errorServiciosCostoMayor', $mensajeServiciosConMayorCosto);

                } else {
                    return redirect('administracion/almacen/servicios')->with('status', 'Se importaron datos correctamente');
                }

            } catch (\Exception $e) {
                DB::rollback();
                return redirect('administracion/almacen/servicios')->with('error', 'Error al importar servicios desde Excel.');
            }
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    // public function importarExcelServicios(Request $req)
    // {
    //     if ($req->session()->has('idUsuario')) {
    //         $idUsuario = Session::get('idUsuario');
    //         if ($req->hasFile('datosExcelServicios')) {
    //             $idSucursal = Session::get('idSucursal');
    //             $datosExcelServicio = Excel::toCollection(new ExcelServiciosImportacion, $req->datosExcelServicios);
    //             //dd($datosExcelServicio[0][0]);
    //             $i = 0;
    //             $arrayServiciosNoGuardados = [];
    //             foreach ($datosExcelServicio[0] as $row) {
    //                 if ($i > 0) {
    //                     $nombreServicio = $row[0];
    //                     if ($nombreServicio != null) {
    //                         $precio = (float) $row[1];
    //                         $costo = (float) $row[2];
    //                         $tipoMoneda = $row[3];
    //                         $idUnidadMedida = 11;
    //                         $date = Carbon::now();
    //                         $codigoBarra = $row[4];
    //                         $codigoInterno = round(microtime(true) * 1000);

    //                         if ($tipoMoneda == 'Soles' || $tipoMoneda == 'SOLES' || $tipoMoneda == 'soles' || $tipoMoneda == null) {
    //                             $tipoMoneda = 1;
    //                         } else {
    //                             $tipoMoneda = 2;
    //                         }

    //                         $serviciosDupicados = $this->getBuscarProducto($nombreServicio, $idSucursal);
    //                         if (count($serviciosDupicados) >= 1) {
    //                             $arrayServiciosNoGuardados[$i - 1] = $nombreServicio;
    //                         } else {
    //                             $array = ['IdTipo' => 2, 'IdUnidadMedida' => $idUnidadMedida, 'IdSucursal' => $idSucursal, 'IdTipoMoneda' => $tipoMoneda, 'FechaCreacion' => $date, 'IdCreacion' => $idUsuario, 'Descripcion' => $nombreServicio, 'Precio' => $precio, 'Exonerado' => 1, 'Costo' => $costo, 'Codigo' => $codigoBarra, 'CodigoInterno' => $codigoInterno, 'TipoOperacion' => 1, 'Imagen' => 'https://easyfactperu2021.s3.us-west-2.amazonaws.com/1641830772.png', 'Estado' => 'E'];
    //                             DB::table('articulo')->insert($array);
    //                         }
    //                     }
    //                 }
    //                 $i++;
    //                 usleep(100000);
    //             }
    //             if (count($serviciosDupicados) >= 1) {
    //                 Session::put('arrayServicios', $arrayServiciosNoGuardados);
    //                 return redirect('administracion/almacen/servicios')->with('status', 'Se importarón los servicios correctamente y se encontrarón Duplicados');
    //             } else {
    //                 return redirect('administracion/almacen/servicios')->with('status', 'Se importarón los servicios correctamente');

    //             }
    //         } else {
    //             return redirect('administracion/almacen/servicios')->with('error', 'No se ha seleccionado un archivo');
    //         }
    //     } else {
    //         Session::flush();
    //         return redirect('/')->with('out', 'Sesión de usuario Expirado');
    //     }
    // }

    protected function getBuscarProducto($nombreServicio, $idSucursal)
    {
        try {
            $producto = DB::table('articulo')
                ->where('IdSucursal', $idSucursal)
                ->where('Estado', 'E')
                ->where('Descripcion', $nombreServicio)
                ->get();
            return $producto;
        } catch (Exception $ex) {
            return redirect('administracion/almacen/servicios')->with('error', 'Error al importar excel');
        }
    }

    public function descargarFormatoExcel(Request $req)
    {
        return response()->download(public_path() . '/FormatoExcel/FormatoExcelServicios.xlsx');

    }
// Fin

    public function create(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $permisos = $loadDatos->getPermisos($idUsuario);
        $idSucursal = Session::get('idSucursal');

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $exonerado = $datosEmpresa->Exonerado;
        $sucursal = $loadDatos->getSucursalSelect($idSucursal);
        $sucExonerado = $sucursal->Exonerado;
        $tipoMonedas = $loadDatos->getTipoMoneda();
        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'tipoMonedas' => $tipoMonedas, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'sucExonerado' => $sucExonerado, 'exonerado' => $exonerado];
        return view('administracion/almacen/servicios/crearServicio', $array);
    }

    public function store(Request $req)
    {
        try {
            $this->validateServicio($req);
            $loadDatos = new DatosController();
            $fecha = $loadDatos->getDateTime();
            $idUsuario = Session::get('idUsuario');
            $idTipo = 2;
            $idUnidadMedida = 11;
            $idSucursal = Session::get('idSucursal');

            $codigo_interno = round(microtime(true) * 1000);

            $exonerado = 1;
            $descripcion = $req->descripcion;
            $estado = 'E';
            $tipoOp = 1;
            $precio = $req->precio;
            $costo = $req->costo;
            $tipoMoneda = $req->tipoMoneda;
            $codigo = $req->codBarra;

            if ($req->imagen != null) {
                // Almacenar la imganen en el S3 y obtener la URL
                $rucEmpresa = $loadDatos->getRucEmpresa($idUsuario)->Ruc;
                $nombreImagen = "servicio-{$codigo_interno}-" . date('His');
                $directorio = "ImagenesArticulos/{$rucEmpresa}/";
                $imagen = $this->storeImagenFormatoFileS3($req->imagen, $imagenAnterior = null, $nombreImagen, $directorio, $accion = 'store');

                // $imagen = $loadDatos->setImage($req->imagen);
            } else {
                $imagen = 'https://easyfactperu2021.s3.us-west-2.amazonaws.com/1641830772.png';
            }
            $array = ['IdTipo' => $idTipo, 'IdUnidadMedida' => $idUnidadMedida, 'IdSucursal' => $idSucursal, 'IdTipoMoneda' => $tipoMoneda, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario, 'Descripcion' => $descripcion,
                'Precio' => $precio, 'Exonerado' => $exonerado, 'Costo' => $costo, 'TipoOperacion' => $tipoOp, 'Imagen' => $imagen, 'Codigo' => $codigo, 'CodigoInterno' => $codigo_interno, 'Estado' => $estado];
            DB::table('articulo')->insert($array);

            return redirect('administracion/almacen/servicios')->with('status', 'Se creo servicio correctamente');
        } catch (Exception $ex) {
            echo $ex->getMessage();
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
        $servicio = $loadDatos->getProductoSelect($id);
        $permisos = $loadDatos->getPermisos($idUsuario);
        $idSucursal = Session::get('idSucursal');

        if ($servicio->TipoOperacion == 1) {
            $costo = $servicio->Costo;
            $costoSinIgv = number_format($servicio->Costo / 1.18, 2);
        } else {
            $costo = number_format($servicio->Costo * 1.18, 2);
            $costoSinIgv = $servicio->Costo;
        }

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $tipoMonedas = $loadDatos->getTipoMoneda();
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $exonerado = $datosEmpresa->Exonerado;
        $sucursal = $loadDatos->getSucursalSelect($idSucursal);
        $sucExonerado = $sucursal->Exonerado;
        $array = ['servicio' => $servicio, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'tipoMonedas' => $tipoMonedas, 'costo' => $costo, 'costoSinIgv' => $costoSinIgv, 'sucExonerado' => $sucExonerado, 'exonerado' => $exonerado];
        return view('administracion/almacen/servicios/editarServicio', $array);
    }

    public function update(Request $req, $id)
    {
        try {
            $this->validateServicio($req);
            $loadDatos = new DatosController();
            $fecha = $loadDatos->getDateTime();
            $idUsuario = Session::get('idUsuario');
            $descripcion = $req->descripcion;
            $precio = $req->precio;
            $costo = $req->costo;
            $tipoMoneda = $req->tipoMoneda;
            $codigo = $req->codBarra;

            if ($req->imagen != null) {
                // Almacenar la imganen en el S3 y obtener la URL
                $rucEmpresa = $loadDatos->getRucEmpresa($idUsuario)->Ruc;
                $nombreImagen = "servicio-{$req->inputCodigoInterno}-" . date('His');
                $directorio = "ImagenesArticulos/{$rucEmpresa}/";
                $imagen = $this->storeImagenFormatoFileS3($req->imagen, $imagenAnterior = $req->inputUrlImagenAnterior, $nombreImagen, $directorio, $accion = 'edit');

                // $imagen = $loadDatos->setImage($req->imagen);
                $array = ['FechaModificacion' => $fecha, 'IdModificacion' => $idUsuario, 'Descripcion' => $descripcion,
                    'Precio' => $precio, 'Costo' => $costo, 'IdTipoMoneda' => $tipoMoneda, 'Imagen' => $imagen, 'Codigo' => $codigo];
            } else {
                $array = ['FechaModificacion' => $fecha, 'IdModificacion' => $idUsuario, 'Descripcion' => $descripcion,
                    'Precio' => $precio, 'Costo' => $costo, 'IdTipoMoneda' => $tipoMoneda, 'Codigo' => $codigo];
            }

            DB::table('articulo')
                ->where('IdArticulo', $id)
                ->update($array);

            return redirect('administracion/almacen/servicios')->with('status', 'Se actualizo servicio correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            $idUsuario = Session::get('idUsuario');
            $array = ['Estado' => 'D', 'IdEliminacion' => $idUsuario, 'FechaEliminacion' => Carbon::now()->toDateTimeString()];
            DB::table('articulo')
                ->where('IdArticulo', $id)
                ->update($array);
            return redirect('administracion/almacen/servicios')->with('status', 'Se elimino servicio correctamente');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // public function search(Request $req)
    // {
    //     if ($req->ajax()) {
    //         $loadDatos = new DatosController();
    //         $idSucursal = Session::get('idSucursal');
    //         Session::put('textoServ', $req->textoBuscar);
    //         $servicios = $loadDatos->getBuscarServiciosPagination($req->textoBuscar, $req->tipoMoneda, $idSucursal);
    //         return Response($servicios);
    //         /*$textoBuscar = $req->textoBuscar;
    //     $array = ['servicios' => $servicios, 'textoBuscar' => $textoBuscar];
    //     return view('administracion/almacen/servicios/servicios', $array);*/
    //     }
    // }

    // public function paginationServicios(Request $req)
    // {
    //     if ($req->ajax()) {
    //         $idSucursal = Session::get('idSucursal');
    //         $loadDatos = new DatosController();

    //         $cod_cliente = DB::table('sucursal')
    //             ->select('CodigoCliente')
    //             ->where('IdSucursal', $idSucursal)
    //             ->first();

    //         $sucPrincipal = DB::table('sucursal')
    //             ->select('IdSucursal')
    //             ->where('CodigoCliente', $cod_cliente->CodigoCliente)
    //             ->where('Principal', 1)
    //             ->first();

    //         if ($sucPrincipal->IdSucursal == $idSucursal) {
    //             $servicios = $loadDatos->getServiciosPagination($idSucursal, $req->tipoMoneda, $req->textoBuscar);
    //         } else {
    //             $servicios = $loadDatos->paginarAjaxServSucursal($idSucursal, $req->tipoMoneda, $req->textoBuscar);
    //         }
    //         return Response($servicios);
    //     }
    // }

    protected function validateServicio(Request $request)
    {
        $this->validate($request, [
            'descripcion' => 'required',
            'precio' => 'required|numeric',
            'costo' => 'required|numeric',
            'imagen' => 'max:300',
        ]);
    }
    public function exportExcel()
    {
        $idSucursal = Session::get('idSucursal');
        $loadDatos = new DatosController();
        $servicios = DB::table('articulo')
            ->select('IdArticulo', 'Descripcion', 'Precio', 'Costo', 'Codigo', 'IdTipoMoneda')
            ->where('articulo.IdTipo', 2)
            ->where('articulo.IdSucursal', $idSucursal)
            ->where('articulo.Estado', 'E')
            ->get();
        return Excel::download(new ExcelReporteServicios($servicios), 'ReporteServicios.xlsx');
    }
}
