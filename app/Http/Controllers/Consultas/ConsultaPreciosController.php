<?php

namespace App\Http\Controllers\Consultas;

use App\Exports\ExcelReportePreciosServicios;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DB;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Session;

class ConsultaPreciosController extends Controller
{
    public function consulta(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi贸n de usuario Expirado');
        }
        $idSucursal = Session::get('idSucursal');
        $loadDatos = new DatosController();
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $sucursal = $loadDatos->getSucursalSelect($idSucursal);
        $sucExonerado = $sucursal->Exonerado;
        $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $exonerado = $datosEmpresa->Exonerado;
        // ---------

        $idAlmacen = $req->input("almacenes");
        if ($idAlmacen === null) {
            $idSucursal = Session::get('idSucursal');
            $idAlmacen = '*' . $idSucursal;
        }
        $id = explode("*", $idAlmacen);
        $idAlmacen = Str::contains($idAlmacen, '*') ? substr($idAlmacen, 1) : $idAlmacen;
        // dd($idAlmacen);
        if (count($id) == 1) {
            $tipo = $req->radioOption;
            $igv = $req->radioOption2;
            $precios = DB::table('almacen_producto')
                ->join('articulo', 'almacen_producto.CodigoInterno', '=', 'articulo.CodigoInterno')
                ->join('marca', 'marca.IdMarca', '=', 'articulo.IdMarca')
                ->join('tipo', 'articulo.IdTipo', '=', 'tipo.IdTipo')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->join('sucursal', 'almacen_producto.IdSucursal', '=', 'sucursal.IdSucursal')
                ->select('almacen_producto.*', 'articulo.CodigoInterno', 'articulo.Ubicacion', 'articulo.Precio', 'articulo.Detalle', 'articulo.IdArticulo', 'tipo.Descripcion as Tipo', 'marca.Nombre as Marca', 'sucursal.Nombre', 'articulo.PrecioDescuento1', 'articulo.VentaMayor1', 'unidad_medida.Nombre as NombreUnidadMedida')
                ->where('articulo.Estado', 'E')
                ->where('articulo.IdSucursal', $idSucursal)
                ->where('articulo.IdTipo', $tipo)
                ->where('almacen_producto.IdAlmacen', $id[0])
                ->get();

            $almacenes = DB::table('almacen')
                ->where('IdSucursal', $idSucursal)
                ->where('Estado', 'E')
                ->get();

            $sucursal = DB::table('sucursal')
                ->where('IdSucursal', $idSucursal)
                ->first();
            // dd($precios);
            $array = ['btn_ojo' => 0, 'sucursal' => $sucursal, 'precios' => $precios, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'almacenes' => $almacenes, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'sucExonerado' => $sucExonerado, 'exonerado' => $exonerado, 'tipo' => $tipo, 'igv' => $igv, 'idAlmacen' => $idAlmacen, 'idSucursal' => $idSucursal];
        } else {

            $tipo = $req->radioOption === null ? 1 : $req->radioOption;
            $igv = $req->radioOption2;

            $precios = $loadDatos->getPrecios($idSucursal, $tipo);

            $almacenes = DB::table('almacen')
                ->where('IdSucursal', $idSucursal)
                ->where('Estado', 'E')
                ->get();

            $sucursal = DB::table('sucursal')
                ->where('IdSucursal', $idSucursal)
                ->first();
            // dd($precios);
            $array = ['btn_ojo' => 1, 'sucursal' => $sucursal, 'precios' => $precios, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'almacenes' => $almacenes, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'sucExonerado' => $sucExonerado, 'exonerado' => $exonerado, 'tipo' => $tipo, 'igv' => $igv, 'idAlmacen' => $idAlmacen, 'idSucursal' => $idSucursal];
        }
        return view('consultas/consultaPrecios', $array);

        // if ($req->isMethod('post')) {
        //     $idAlmacen = $req->input("almacenes");
        //     $id = explode("*", $idAlmacen);
        //     $idAlmacen = substr($idAlmacen, 1);

        //     $valorIdSucursal = $req->input("almacenes");
        //     if (is_numeric($id[0])) {
        //         $idSucursal = Session::get('idSucursal');
        //         $tipo = $req->radioOption;
        //         $igv = $req->radioOption2;
        //         $idAlmacen = 0;
        //         $precios = DB::table('almacen_producto')
        //             ->join('articulo', 'almacen_producto.CodigoInterno', '=', 'articulo.CodigoInterno')
        //             ->join('marca', 'marca.IdMarca', '=', 'articulo.IdMarca')
        //             ->join('tipo', 'articulo.IdTipo', '=', 'tipo.IdTipo')
        //             ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
        //             ->join('sucursal', 'almacen_producto.IdSucursal', '=', 'sucursal.IdSucursal')
        //             ->select('almacen_producto.*', 'articulo.CodigoInterno', 'articulo.Ubicacion', 'articulo.Precio', 'articulo.Detalle', 'articulo.IdArticulo', 'tipo.Descripcion as Tipo', 'marca.Nombre as Marca', 'sucursal.Nombre', 'articulo.PrecioDescuento1', 'articulo.VentaMayor1', 'unidad_medida.Nombre as NombreUnidadMedida')
        //             ->where('articulo.Estado', 'E')
        //             ->where('articulo.IdSucursal', $idSucursal)
        //             ->where('articulo.IdTipo', $tipo)
        //             ->where('almacen_producto.IdAlmacen', $id[0])
        //             ->get();

        //         $loadDatos = new DatosController();
        //         //$precios = $loadDatos->getPrecios($idSucursal);
        //         $permisos = $loadDatos->getPermisos($idUsuario);

        //         $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        //         $subniveles = $loadDatos->getSubNiveles($idUsuario);

        //         $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        //         $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        //         $sucursal = $loadDatos->getSucursalSelect($idSucursal);
        //         $sucExonerado = $sucursal->Exonerado;

        //         $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        //         $exonerado = $datosEmpresa->Exonerado;

        //         $almacenes = DB::table('almacen')
        //             ->where('IdSucursal', $idSucursal)
        //             ->where('Estado', 'E')
        //             ->get();

        //         $sucursal = DB::table('sucursal')
        //             ->where('IdSucursal', $idSucursal)
        //             ->first();
        //         // dd($precios);
        //         $array = ['btn_ojo' => 0, 'sucursal' => $sucursal, 'precios' => $precios, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'almacenes' => $almacenes, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'sucExonerado' => $sucExonerado, 'exonerado' => $exonerado, 'tipo' => $tipo, 'igv' => $igv, 'idAlmacen' => $idAlmacen, 'idSucursal' => $idSucursal];
        //         return view('consultas/consultaPrecios', $array);

        //     } else {
        //         $loadDatos = new DatosController();
        //         $idSucursal = Session::get('idSucursal');
        //         $tipo = $req->radioOption;
        //         $igv = $req->radioOption2;

        //         $precios = $loadDatos->getPrecios($idSucursal, $tipo);

        //         $permisos = $loadDatos->getPermisos($idUsuario);

        //         $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        //         $subniveles = $loadDatos->getSubNiveles($idUsuario);

        //         $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        //         $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        //         $sucursal = $loadDatos->getSucursalSelect($idSucursal);
        //         $sucExonerado = $sucursal->Exonerado;

        //         $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        //         $exonerado = $datosEmpresa->Exonerado;

        //         $almacenes = DB::table('almacen')
        //             ->where('IdSucursal', $idSucursal)
        //             ->where('Estado', 'E')
        //             ->get();

        //         $sucursal = DB::table('sucursal')
        //             ->where('IdSucursal', $idSucursal)
        //             ->first();
        //         // dd($precios);
        //         $array = ['btn_ojo' => 1, 'sucursal' => $sucursal, 'precios' => $precios, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'almacenes' => $almacenes, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'sucExonerado' => $sucExonerado, 'exonerado' => $exonerado, 'tipo' => $tipo, 'igv' => $igv, 'idAlmacen' => $idAlmacen, 'idSucursal' => $idSucursal];
        //         return view('consultas/consultaPrecios', $array);
        //     }
        // } else {
        //     $idSucursal = Session::get('idSucursal');
        //     $idAlmacen = $req->input("almacenes");
        //     $idAlmacen = substr($idAlmacen, 1);

        //     $almacenes = DB::table('almacen')
        //         ->where('IdSucursal', $idSucursal)
        //         ->where('Estado', 'E')
        //         ->get();

        //     if ($almacenes->isEmpty()) {

        //         $loadDatos = new DatosController();
        //         $idSucursal = Session::get('idSucursal');
        //         $precios = $loadDatos->getPrecios($idSucursal, 1);
        //         $permisos = $loadDatos->getPermisos($idUsuario);

        //         $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        //         $subniveles = $loadDatos->getSubNiveles($idUsuario);

        //         $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        //         $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        //         $sucursal = $loadDatos->getSucursalSelect($idSucursal);
        //         $sucExonerado = $sucursal->Exonerado;

        //         $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        //         $exonerado = $datosEmpresa->Exonerado;

        //         $almacenes = DB::table('almacen')
        //             ->where('IdSucursal', $idSucursal)
        //             ->where('Estado', 'E')
        //             ->get();

        //         $sucursal = DB::table('sucursal')
        //             ->where('IdSucursal', $idSucursal)
        //             ->first();
        //         // dd($precios);
        //         $array = ['btn_ojo' => 1, 'sucursal' => $sucursal, 'precios' => $precios, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'almacenes' => $almacenes, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'sucExonerado' => $sucExonerado, 'exonerado' => $exonerado, 'tipo' => 1, 'igv' => 1, 'idAlmacen' => $idAlmacen, 'idSucursal' => $idSucursal];
        //         return view('consultas/consultaPrecios', $array);
        //     } else {
        //         $loadDatos = new DatosController();
        //         $idSucursal = Session::get('idSucursal');
        //         $precios = array();
        //         $permisos = $loadDatos->getPermisos($idUsuario);

        //         $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        //         $subniveles = $loadDatos->getSubNiveles($idUsuario);

        //         $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        //         $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        //         $sucursal = $loadDatos->getSucursalSelect($idSucursal);
        //         $sucExonerado = $sucursal->Exonerado;

        //         $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        //         $exonerado = $datosEmpresa->Exonerado;

        //         $almacenes = DB::table('almacen')
        //             ->where('IdSucursal', $idSucursal)
        //             ->where('Estado', 'E')
        //             ->get();
        //         $sucursal = DB::table('sucursal')
        //             ->where('IdSucursal', $idSucursal)
        //             ->first();
        //         // dd($precios);
        //         $array = ['btn_ojo' => 0, 'sucursal' => $sucursal, 'precios' => $precios, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'almacenes' => $almacenes, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'sucExonerado' => $sucExonerado, 'exonerado' => $exonerado, 'tipo' => 1, 'igv' => 1, 'idAlmacen' => $idAlmacen, 'idSucursal' => $idSucursal];
        //         return view('consultas/consultaPrecios', $array);
        //     }
        // }
    }

    // public function consulta(Request $req)
    // {
    //     if ($req->session()->has('idUsuario')) {
    //         $idUsuario = Session::get('idUsuario');
    //     } else {
    //         Session::flush();
    //         return redirect('/')->with('out', 'Sesi贸n de usuario Expirado');
    //     }

    //     if ($req->isMethod('post')) {
    //         $idAlmacen = $req->input("almacenes");
    //         $id = explode("*", $idAlmacen);
    //         $idAlmacen = substr($idAlmacen, 1);

    //         $valorIdSucursal = $req->input("almacenes");
    //         if (is_numeric($id[0])) {
    //             $idSucursal = Session::get('idSucursal');
    //             $tipo = $req->radioOption;
    //             $igv = $req->radioOption2;
    //             $idAlmacen = 0;
    //             $precios = DB::table('almacen_producto')
    //                 ->join('articulo', 'almacen_producto.CodigoInterno', '=', 'articulo.CodigoInterno')
    //                 ->join('marca', 'marca.IdMarca', '=', 'articulo.IdMarca')
    //                 ->join('tipo', 'articulo.IdTipo', '=', 'tipo.IdTipo')
    //                 ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
    //                 ->join('sucursal', 'almacen_producto.IdSucursal', '=', 'sucursal.IdSucursal')
    //                 ->select('almacen_producto.*', 'articulo.CodigoInterno', 'articulo.Ubicacion', 'articulo.Precio', 'articulo.Detalle', 'articulo.IdArticulo', 'tipo.Descripcion as Tipo', 'marca.Nombre as Marca', 'sucursal.Nombre', 'articulo.PrecioDescuento1', 'articulo.VentaMayor1', 'unidad_medida.Nombre as NombreUnidadMedida')
    //                 ->where('articulo.Estado', 'E')
    //                 ->where('articulo.IdSucursal', $idSucursal)
    //                 ->where('articulo.IdTipo', $tipo)
    //                 ->where('almacen_producto.IdAlmacen', $id[0])
    //                 ->get();

    //             $loadDatos = new DatosController();
    //             //$precios = $loadDatos->getPrecios($idSucursal);
    //             $permisos = $loadDatos->getPermisos($idUsuario);

    //             $subpermisos = $loadDatos->getSubPermisos($idUsuario);
    //             $subniveles = $loadDatos->getSubNiveles($idUsuario);

    //             $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
    //             $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

    //             $sucursal = $loadDatos->getSucursalSelect($idSucursal);
    //             $sucExonerado = $sucursal->Exonerado;

    //             $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
    //             $exonerado = $datosEmpresa->Exonerado;

    //             $almacenes = DB::table('almacen')
    //                 ->where('IdSucursal', $idSucursal)
    //                 ->where('Estado', 'E')
    //                 ->get();

    //             $sucursal = DB::table('sucursal')
    //                 ->where('IdSucursal', $idSucursal)
    //                 ->first();
    //             // dd($precios);
    //             $array = ['btn_ojo' => 0, 'sucursal' => $sucursal, 'precios' => $precios, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'almacenes' => $almacenes, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'sucExonerado' => $sucExonerado, 'exonerado' => $exonerado, 'tipo' => $tipo, 'igv' => $igv, 'idAlmacen' => $idAlmacen, 'idSucursal' => $idSucursal];
    //             return view('consultas/consultaPrecios', $array);

    //         } else {
    //             $loadDatos = new DatosController();
    //             $idSucursal = Session::get('idSucursal');
    //             $tipo = $req->radioOption;
    //             $igv = $req->radioOption2;

    //             $precios = $loadDatos->getPrecios($idSucursal, $tipo);

    //             $permisos = $loadDatos->getPermisos($idUsuario);

    //             $subpermisos = $loadDatos->getSubPermisos($idUsuario);
    //             $subniveles = $loadDatos->getSubNiveles($idUsuario);

    //             $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
    //             $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

    //             $sucursal = $loadDatos->getSucursalSelect($idSucursal);
    //             $sucExonerado = $sucursal->Exonerado;

    //             $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
    //             $exonerado = $datosEmpresa->Exonerado;

    //             $almacenes = DB::table('almacen')
    //                 ->where('IdSucursal', $idSucursal)
    //                 ->where('Estado', 'E')
    //                 ->get();

    //             $sucursal = DB::table('sucursal')
    //                 ->where('IdSucursal', $idSucursal)
    //                 ->first();
    //             // dd($precios);
    //             $array = ['btn_ojo' => 1, 'sucursal' => $sucursal, 'precios' => $precios, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'almacenes' => $almacenes, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'sucExonerado' => $sucExonerado, 'exonerado' => $exonerado, 'tipo' => $tipo, 'igv' => $igv, 'idAlmacen' => $idAlmacen, 'idSucursal' => $idSucursal];
    //             return view('consultas/consultaPrecios', $array);
    //         }
    //     } else {
    //         $idSucursal = Session::get('idSucursal');
    //         $idAlmacen = $req->input("almacenes");
    //         $idAlmacen = substr($idAlmacen, 1);

    //         $almacenes = DB::table('almacen')
    //             ->where('IdSucursal', $idSucursal)
    //             ->where('Estado', 'E')
    //             ->get();

    //         if ($almacenes->isEmpty()) {

    //             $loadDatos = new DatosController();
    //             $idSucursal = Session::get('idSucursal');
    //             $precios = $loadDatos->getPrecios($idSucursal, 1);
    //             $permisos = $loadDatos->getPermisos($idUsuario);

    //             $subpermisos = $loadDatos->getSubPermisos($idUsuario);
    //             $subniveles = $loadDatos->getSubNiveles($idUsuario);

    //             $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
    //             $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

    //             $sucursal = $loadDatos->getSucursalSelect($idSucursal);
    //             $sucExonerado = $sucursal->Exonerado;

    //             $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
    //             $exonerado = $datosEmpresa->Exonerado;

    //             $almacenes = DB::table('almacen')
    //                 ->where('IdSucursal', $idSucursal)
    //                 ->where('Estado', 'E')
    //                 ->get();

    //             $sucursal = DB::table('sucursal')
    //                 ->where('IdSucursal', $idSucursal)
    //                 ->first();
    //             // dd($precios);
    //             $array = ['btn_ojo' => 1, 'sucursal' => $sucursal, 'precios' => $precios, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'almacenes' => $almacenes, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'sucExonerado' => $sucExonerado, 'exonerado' => $exonerado, 'tipo' => 1, 'igv' => 1, 'idAlmacen' => $idAlmacen, 'idSucursal' => $idSucursal];
    //             return view('consultas/consultaPrecios', $array);
    //         } else {
    //             $loadDatos = new DatosController();
    //             $idSucursal = Session::get('idSucursal');
    //             $precios = array();
    //             $permisos = $loadDatos->getPermisos($idUsuario);

    //             $subpermisos = $loadDatos->getSubPermisos($idUsuario);
    //             $subniveles = $loadDatos->getSubNiveles($idUsuario);

    //             $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
    //             $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

    //             $sucursal = $loadDatos->getSucursalSelect($idSucursal);
    //             $sucExonerado = $sucursal->Exonerado;

    //             $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
    //             $exonerado = $datosEmpresa->Exonerado;

    //             $almacenes = DB::table('almacen')
    //                 ->where('IdSucursal', $idSucursal)
    //                 ->where('Estado', 'E')
    //                 ->get();
    //             $sucursal = DB::table('sucursal')
    //                 ->where('IdSucursal', $idSucursal)
    //                 ->first();
    //             // dd($precios);
    //             $array = ['btn_ojo' => 0, 'sucursal' => $sucursal, 'precios' => $precios, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'almacenes' => $almacenes, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'sucExonerado' => $sucExonerado, 'exonerado' => $exonerado, 'tipo' => 1, 'igv' => 1, 'idAlmacen' => $idAlmacen, 'idSucursal' => $idSucursal];
    //             return view('consultas/consultaPrecios', $array);
    //         }
    //     }
    // }

    public function almacen()
    {
        return view('hola');
    }

    public function porcentajeDescuento(Request $req)
    {
        /* if($req->ajax()){
        $loadDatos = new DatosController();
        $idProducto = $req->idProducto;
        $descuentos = $loadDatos->getProductoSelect($idProducto);
        return Response([$descuentos]);
        }  */
        if ($req->ajax()) {
            $cadena = '';
            $cad_sucursal = array();

            $cod_interno = $req->idProducto; //esto es el codigo interno ojo ::::::

            $sucursal = DB::table('articulo')
                ->select('IdSucursal')
                ->where('CodigoInterno', $cod_interno)
                ->first();

            $codigoCliente = DB::table('sucursal')
                ->select('CodigoCliente')
                ->where('IdSucursal', $sucursal->IdSucursal)
                ->first();

            $sucursales = DB::table('sucursal')
                ->where('CodigoCliente', $codigoCliente->CodigoCliente)
                ->select('IdSucursal', 'Nombre')
                ->get();

            $sucursal_producto = DB::table('sucursal')
                ->leftJoin('articulo', 'sucursal.IdSucursal', '=', 'articulo.IdSucursal')
                ->select('articulo.Descripcion', 'articulo.Precio', 'articulo.Stock', 'sucursal.Nombre', 'sucursal.IdSucursal')
                ->where('sucursal.CodigoCliente', $codigoCliente->CodigoCliente)
                ->where('articulo.CodigoInterno', $cod_interno)
                ->get();

            $alm_prod = DB::table('almacen_producto')
                ->join('almacen', 'almacen_producto.IdAlmacen', '=', 'almacen.IdAlmacen')
                ->select('almacen_producto.Descripcion', 'almacen_producto.Stock', 'almacen.Nombre', 'almacen_producto.IdSucursal')
                ->where('almacen_producto.CodigoInterno', $cod_interno)
                ->get();

            $datos = DB::table('articulo')->first();
            $indice = 0;
            foreach ($sucursales as $sucu) {

                $cad_sucursal[$indice] = array('nombre' => $sucu->Nombre, 'descripcion' => '', 'precio' => 0, 'stock' => 0);

                foreach ($sucursal_producto as $suc_pro) {
                    if ($sucu->IdSucursal == $suc_pro->IdSucursal) {
                        $cad_sucursal[$indice] = array('nombre' => $sucu->Nombre, 'descripcion' => $suc_pro->Descripcion, 'precio' => $suc_pro->Precio, 'stock' => $suc_pro->Stock, 'id_sucursal' => $suc_pro->IdSucursal);
                        //$cadena=$cadena.'  '.$sucu->Nombre.' '.$suc_pro->Precio.' '.$suc_pro->Stock;
                    }
                }
                $indice++;
            }

            return Response()->json(['datos' => $sucursal_producto, 'sucursal' => $cad_sucursal, 'alm_prod' => $alm_prod]);
        }
    }

    public function exportExcel($tipo, $idAlmacen)
    {

        $loadDatos = new DatosController();
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');
        $precios = $loadDatos->getPrecios($idAlmacen, $tipo);
// dd($precios);
        return Excel::download(new ExcelReportePreciosServicios($precios), 'Reporte Precios - Servicios.xlsx');

    }
}
