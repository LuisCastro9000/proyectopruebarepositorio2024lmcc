<?php

namespace App\Http\Controllers\ClasesPublicas;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DB;
use Illuminate\Http\Request;
use Session;

class ArticulosController extends Controller
{

    public function paginationProductos(Request $req)
    {
        if ($req->ajax()) {
            $idSucursal = Session::get('idSucursal');
            $optMarca = Session::get('optMarca');
            $loadDatos = new DatosController();

            $cod_cliente = DB::table('sucursal')
                ->select('CodigoCliente')
                ->where('IdSucursal', $idSucursal)
                ->first();

            $sucPrincipal = DB::table('sucursal')
                ->select('IdSucursal')
                ->where('CodigoCliente', $cod_cliente->CodigoCliente)
                ->where('Principal', 1)
                ->first();

            if ($optMarca == 1) {
                if ($sucPrincipal->IdSucursal == $idSucursal) {
                    $productos = $loadDatos->getProductosPaginationNoMarca($idSucursal, $req->textoBuscar, $req->tipoMoneda, $req->idCategoria);
                } else {
                    $productos = $loadDatos->paginarAjaxProdSucursalNoMarca($idSucursal, $req->textoBuscar, $req->tipoMoneda, $req->idCategoria);
                }
            } else {
                if ($sucPrincipal->IdSucursal == $idSucursal) {
                    // $productos = $loadDatos->getProductosPagination($idSucursal, $req->textoBuscar, $req->tipoMoneda, $req->idCategoria);
                    $productosPaginacion = $this->getProductosConAjax($req->textoBuscar, $req->tipoMoneda, $idSucursal, $req->idCategoria);
                } else {
                    // $productos = $loadDatos->paginarAjaxProdSucursal($idSucursal, $req->textoBuscar, $req->tipoMoneda, $req->idCategoria);
                    $productosPaginacion = $this->getProductosConAjax($req->textoBuscar, $req->tipoMoneda, $idSucursal, $req->idCategoria);
                }
            }
            return Response($productosPaginacion);
        }
    }

    public function paginationServicios(Request $req)
    {
        if ($req->ajax()) {
            $idSucursal = Session::get('idSucursal');
            $text2 = Session::get('text');
            $loadDatos = new DatosController();
            // $servicios = $loadDatos->getServiciosPagination($idSucursal, $req->tipoMoneda, $text2);
            $servicios = $this->getServiciosConAjax($text2, $req->tipoMoneda, $idSucursal);
            return Response($servicios);
        }
    }

    public function searchProducto(Request $req)
    {
        if ($req->ajax()) {
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $optMarca = $req->sinMarca;
            Session::put('text', $req->textoBuscar);
            Session::put('optMarca', $req->sinMarca);

            $cod_cliente = DB::table('sucursal')
                ->select('CodigoCliente')
                ->where('IdSucursal', $idSucursal)
                ->first();

            $sucPrincipal = DB::table('sucursal')
                ->select('IdSucursal')
                ->where('CodigoCliente', $cod_cliente->CodigoCliente)
                ->where('Principal', 1)
                ->first();

            if ($optMarca == 1) {
                if ($sucPrincipal->IdSucursal == $idSucursal) {
                    $articulos = $loadDatos->getBuscarProdNoMarcas($req->textoBuscar, $req->tipoMoneda, $idSucursal, $req->idCategoria);
                } else {
                    $articulos = $loadDatos->buscarAjaxProdNoMarcaSucursal($req->textoBuscar, $req->tipoMoneda, $idSucursal, $req->idCategoria);
                }
            } else {
                if ($sucPrincipal->IdSucursal == $idSucursal) {
                    // $articulos = $loadDatos->getBuscarProductosVentas($req->textoBuscar, $req->tipoMoneda, $idSucursal, $req->idCategoria);
                    $articulos = $this->getProductosConAjax($req->textoBuscar, $req->tipoMoneda, $idSucursal, $req->idCategoria);

                } else {
                    // $articulos = $loadDatos->buscarAjaxProdSucursal($req->textoBuscar, $req->tipoMoneda, $idSucursal, $req->idCategoria);
                    $articulos = $this->getProductosConAjax($req->textoBuscar, $req->tipoMoneda, $idSucursal, $req->idCategoria);
                }
            }
            return Response($articulos);
        }
    }

    public function searchServicio(Request $req)
    {
        if ($req->ajax()) {
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            Session::put('text', $req->textoBuscar);
            // $articulos = $loadDatos->getBuscarServiciosVentas($req->textoBuscar, $req->tipoMoneda, $idSucursal);
            $articulos = $this->getServiciosConAjax($req->textoBuscar, $req->tipoMoneda, $idSucursal);
            return Response($articulos);
        }
    }

    public function getProductosConAjax($texto, $tipoMoneda, $idSucursal, $idCategoria)
    {
        try {
            // Convertimos el texto a un array de palabras
            $palabras = explode(' ', $texto);

            $productos = DB::table('articulo')
                ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->join('categoria', 'articulo.IdCategoria', '=', 'categoria.IdCategoria')
                ->join('sucursal', 'articulo.IdSucursal', '=', 'sucursal.IdSucursal')
                ->select('articulo.*', 'sucursal.Exonerado as valorCheckSucursalFactExonerado', 'marca.Nombre as Marca', 'categoria.Nombre as Categoria', 'unidad_medida.Nombre as UM', DB::raw('IFNULL(articulo.Costo, 0.0) as Costo'), DB::raw('IFNULL(articulo.Precio, 0.0) as Precio'), DB::raw('IFNULL(articulo.Stock, 0) as Stock'))
                ->where('IdTipo', 1)
                ->where('IdTipoMoneda', $tipoMoneda)
                ->where('articulo.IdSucursal', $idSucursal)
                ->where('articulo.Estado', 'E')
                ->where(function ($query) use ($texto, $palabras) {
                    $query->where('articulo.Codigo', 'like', '%' . $texto . '%')
                        ->orWhere('articulo.Precio', 'like', '%' . $texto . '%')
                        ->orWhere('marca.Nombre', 'like', '%' . $texto . '%')
                        ->orWhere(function ($query) use ($palabras) {
                            foreach ($palabras as $palabra) {
                                $query->where('articulo.Descripcion', 'like', '%' . $palabra . '%');
                            }
                        });
                })
                ->when($idCategoria != 0 && $idCategoria !== '' && $idCategoria !== null, function ($query) use ($idCategoria) {
                    $query->where('categoria.IdCategoria', $idCategoria);
                })
                ->orderBy('articulo.Descripcion', 'asc')
                ->paginate(12);

            return $productos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function getServiciosConAjax($textoBuscar, $tipoMoneda, $idSucursal)
    {
        try {
            // Convertimos el texto a un array de palabras
            $palabras = explode(' ', $textoBuscar);
            try {
                $servicios = DB::table('articulo')
                    ->where('IdTipo', 2)
                    ->where('Estado', 'E')
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('IdSucursal', $idSucursal)
                    ->where(function ($query) use ($textoBuscar, $palabras) {
                        $query->where('Precio', 'like', '%' . $textoBuscar . '%')
                            ->orWhere('Codigo', 'like', '%' . $textoBuscar . '%')
                            ->orWhere(function ($query) use ($palabras) {
                                foreach ($palabras as $palabra) {
                                    $query->where('Descripcion', 'like', '%' . $palabra . '%');
                                }
                            });
                    })
                    ->orderBy('Descripcion', 'asc')
                    ->paginate(12);
                return $servicios;
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
