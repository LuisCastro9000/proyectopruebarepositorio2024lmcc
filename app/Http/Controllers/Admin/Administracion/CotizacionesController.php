<?php
namespace App\Http\Controllers\Admin\Administracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CotizacionesController extends Controller
{

    public $datosEstados;
    public function __construct()
    {
        $this->datosEstados = [
            '1' => ['claseCss' => 'badge badge-danger-lighten', 'nombre' => 'Abierto'],
            '2' => ['claseCss' => 'badge badge-info-lighten', 'nombre' => 'Proceso'],
            '3' => ['claseCss' => 'badge badge-warning-lighten', 'nombre' => 'Finalizado'],
            '4' => ['claseCss' => 'badge badge-success-lighten', 'nombre' => 'Cerrado'],
            '6' => ['claseCss' => 'badge badge-secondary-lighten', 'nombre' => 'Baja'],
        ];
    }
    public function index(Request $req)
    {
        $sucursales = $this->getSucursales();
        $cotizaciones = [];
        return view('admin/administracion/cotizaciones/index', compact('sucursales', 'cotizaciones'));
    }

    public function updateEstado(Request $req)
    {
        try {
            DB::BeginTransaction();
            // Cambiar estado de la cotizacion
            $resultadoCambioEstado = $this->actualizarEstado($req);
            if (!$resultadoCambioEstado) {
                return response()->json(['respuesta' => 'error', 'mensaje' => "Ocurrio un error en la actualización de estado"]);
            }

            // Reposición de stock
            if ($req->switchReponerStock === "true") {
                if ($req->estadoAnterior == 2 || $req->estadoAnterior == 3 || $req->estadoAnterior == 4) {
                    if ($req->nuevoEstado == 1 || $req->nuevoEstado == 6) {
                        $resultadoReposicion = $this->reponerStock($req);
                        if ($resultadoReposicion === 'sinDatos') {
                            return response()->json(['respuesta' => 'error', 'mensaje' => 'No se puede reponer stock: la cotización solo incluye servicios. Vuelva a cambiar el estado sin tener habilitada la opción de reponer stock.']);
                        }
                        if (!$resultadoReposicion) {
                            return response()->json(['respuesta' => 'error', 'mensaje' => "Ocurrio un error en la reposición de stock"]);
                        }
                    }
                }
            }

            $estado = $this->datosEstados[$req->nuevoEstado];
            DB::commit();
            return response()->json(['respuesta' => 'success', 'html' => "<span class='$estado[claseCss] font-16 spanEstado'>$estado[nombre]</span>"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['respuesta' => 'error', 'mensaje' => 'Ocurrió un error inesperado. Por favor, comuníquese con el área de soporte.']);
        }
    }

    public function getSucursales()
    {
        $sucursales = DB::table('sucursal')
            ->join('empresa', 'sucursal.CodigoCliente', '=', 'empresa.CodigoCliente')
            ->select('IdSucursal', 'sucursal.Nombre as NombreSucursal', 'sucursal.Direccion', 'sucursal.Ciudad', 'empresa.Nombre as NombreEmpresa', 'empresa.Ruc as RucEmpresa')
            ->whereNotIn('Estado', ['D'])
            ->get();
        return $sucursales;

    }

    public function getCotizacionesAjax(Request $req)
    {
        $cotizaciones = DB::table('cotizacion as c')
            ->select('c.*', 'sucursal.Nombre as NombreSucursal')
            ->join('sucursal', 'c.IdSucursal', '=', 'sucursal.IdSucursal')
            ->where('c.IdSucursal', $req->idSucursal)
            ->where('Serie', $req->serie)
            ->where('Numero', $req->numero)->get();

        $vista = view('admin.administracion.cotizaciones._tabla', ['cotizaciones' => $cotizaciones, 'datosEstados' => $this->datosEstados])->render();
        return response()->json(['vista' => $vista, 'cotizaciones' => $cotizaciones]);
    }

    // Nuevas funciones
    private function actualizarEstado($req)
    {
        try {
            DB::table('cotizacion')
                ->where('IdCotizacion', $req->idCotizacion)
                ->update(['IdEstadoCotizacion' => $req->nuevoEstado]);

            $registroEstado = DB::table('registro_estados')
                ->where('IdCotizacion', $req->idCotizacion)
                ->where('IdEstadoCotizacion', $req->nuevoEstado)->first();

            if ($registroEstado) {
                DB::table('registro_estados')
                    ->where('IdRegistroEstados', $registroEstado->IdRegistroEstados)
                    ->update(['FechaRegistro' => now()]);
            } else {
                DB::table('registro_estados')
                    ->insert(['IdUsuario' => 1, 'IdSucursal' => $req->idSucursal, 'IdCotizacion' => $req->idCotizacion, 'IdEstadoCotizacion' => $req->nuevoEstado, 'FechaRegistro' => now()]);
            }

            // La reposición de stock fue exitosa
            return true;
        } catch (\Exception $e) {
            // Retorna false ocurrio un error
            return false;
        }

    }

    private function reponerStock($req)
    {
        try {
            $articulosVendidos = $this->getArticulosVendidos($req->idCotizacion);
            $kardex = [];
            foreach ($articulosVendidos as $articulo) {
                if ($articulo->TipoArticulo === 1) {
                    // reponer stock Table Articulo
                    DB::table('articulo')
                        ->where('IdSucursal', $articulo->IdSucursal)
                        ->where('IdArticulo', $articulo->IdArticulo)
                        ->increment('Stock', $articulo->CantidadRestada);

                    // Reponer stock Table Stock
                    $articuloTableStock = $this->getArticuloTableStock($articulo->IdArticulo);
                    DB::table('stock')
                        ->where('IdStock', $articuloTableStock[0]->IdStock)
                        ->increment('Cantidad', $articulo->CantidadRestada);

                    // Obtener Stock actual del articulo
                    $articuloActualizado = DB::table('articulo')->where('IdArticulo', $articulo->IdArticulo)->first();

                    // Crear array Kardex
                    $nuevoRegistro = [
                        'CodigoInterno' => $articulo->CodigoInterno,
                        'fecha_movimiento' => now(),
                        'tipo_movimiento' => 21,
                        'usuario_movimiento' => 1,
                        'documento_movimiento' => "Latencia Internet-Reposición" . ': ' . "$req->correlativo",
                        'existencia' => $articuloActualizado->Stock,
                        'costo' => $articulo->Precio,
                        'IdArticulo' => $articulo->IdArticulo,
                        'IdSucursal' => $articulo->IdSucursal,
                        'Cantidad' => $articulo->CantidadRestada,
                        'Descuento' => $articulo->Descuento,
                        'ImporteEntrada' => 0,
                        'ImporteSalida' => $articulo->PrecioVenta,
                        'estado' => 1]
                    ;
                    array_push($kardex, $nuevoRegistro);
                }
            }
            if (count($kardex) >= 1) {
                DB::table('kardex')
                    ->insert($kardex);
                // La reposición de stock fue exitosa
                return true;
            } else {
                return 'sinDatos';
            }

        } catch (\Exception $e) {
            // Retorna false ocurrio un error
            return false;
        }
    }

    private function getArticulosVendidos($idCotizacion)
    {
        $articulos = DB::table('cotizacion_articulo as ca')
            ->join('articulo as a', 'ca.IdArticulo', 'a.IdArticulo')
            ->join('cotizacion as c', 'ca.IdCotizacion', 'c.IdCotizacion')
            ->select('ca.IdArticulo', 'ca.Cantidad as CantidadRestada', 'ca.Descuento', 'ca.Importe as PrecioVenta', 'a.IdSucursal', 'a.CodigoInterno', 'a.Precio', 'a.IdTipo as TipoArticulo', 'c.IdEstadoCotizacion')
            ->where('ca.IdCotizacion', $idCotizacion)
            ->get();

        return $articulos;
    }

    private function getArticuloTableStock($id)
    {
        $stock = DB::table('stock')
            ->where('IdArticulo', $id)
            ->where('Cantidad', '>=', 0)
            ->orderBy('IdStock', 'desc')
            ->limit(2)
            ->get();
        return $stock;
    }
}
