<?php
namespace App\Http\Controllers\Admin\Administracion;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentasController extends Controller
{

    public function index(Request $req)
    {
        $sucursales = $this->getSucursales();
        $articulosVendidos = [];
        $datosVenta = [];
        return view('admin/administracion/ventas.index', compact('sucursales', 'articulosVendidos', 'datosVenta'));
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

    public function buscarArticulos(Request $req)
    {
        if ($req->tipoOperacion === 'reponer') {
            $articulosVendidos = $this->getArticulosVendidos($req);
            $vista = view('admin.administracion.ventas._tabla', ['articulosVendidos' => $articulosVendidos])->render();
            return response()->json(['vista' => $vista, 'articulosVendidos' => $articulosVendidos]);
        }
        if ($req->tipoOperacion === 'completar') {
            $datosVenta = DB::table('ventas')
                ->select('ventas.*', DB::raw('concat(Serie, "-", Numero) as CorrelativoVenta'), 'Total', 'sucursal.Nombre as NombreSucursal')
                ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                ->where('ventas.IdSucursal', $req->idSucursal)
                ->where('Serie', $req->serie)
                ->where('Numero', $req->numero)
                ->get();
            $vista = view('admin.administracion.ventas._tablaDatosVenta', ['datosVenta' => $datosVenta])->render();
            return response()->json(['vista' => $vista, 'datosVenta' => $datosVenta]);
        }
    }

    public function reponerStock(Request $req)
    {
        try {
            DB::beginTransaction();
            // Reposición de stock
            $resultadoReposicion = $this->actualizarStock($req);
            if ($resultadoReposicion === 'sinDatos') {
                return response()->json(['respuesta' => 'error', 'mensaje' => "No se puede reponer stock: la venta solo incluye servicios."]);
            }

            if (!$resultadoReposicion) {
                return response()->json(['respuesta' => 'error', 'mensaje' => "Ocurrio un error en la reposición de stock"]);
            }

            DB::commit();
            return response()->json(['respuesta' => 'success', 'articulos' => $resultadoReposicion]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['respuesta' => 'error', 'mensaje' => 'No se pudo realizar la reposición de stock'], 500);
        }
    }

    private function actualizarStock($req)
    {
        try {
            $kardex = [];
            foreach ($req->articulosVendidos as $articulo) {
                if ($articulo['TipoArticulo'] == 1) {
                    // reponer stock Table Articulo
                    DB::table('articulo')
                        ->where('IdSucursal', $articulo['IdSucursal'])
                        ->where('IdArticulo', $articulo['IdArticulo'])
                        ->increment('Stock', $articulo['CantidadVendida']);

                    // Reponer stock Table Stock
                    $articuloTableStock = $this->getArticuloTableStock($articulo['IdArticulo']);
                    DB::table('stock')
                        ->where('IdStock', $articuloTableStock[0]->IdStock)
                        ->increment('Cantidad', $articulo['CantidadVendida']);

                    // Obtener Stock actual del articulo
                    $articuloActualizado = DB::table('articulo')->where('IdArticulo', $articulo['IdArticulo'])->first();

                    // Crear array Kardex
                    $nuevoRegistro = [
                        'CodigoInterno' => $articulo['CodigoInterno'],
                        'fecha_movimiento' => now(),
                        'tipo_movimiento' => 21,
                        'usuario_movimiento' => 1,
                        'documento_movimiento' => "Latencia Internet-Reposición" . ': ' . $articulo['CorrelativoVenta'],
                        'existencia' => $articuloActualizado->Stock,
                        'costo' => $articulo['Precio'],
                        'IdArticulo' => $articulo['IdArticulo'],
                        'IdSucursal' => $articulo['IdSucursal'],
                        'Cantidad' => $articulo['CantidadVendida'],
                        'Descuento' => $articulo['Descuento'],
                        'ImporteEntrada' => 0,
                        'ImporteSalida' => $articulo['PrecioVenta'],
                        'estado' => 1]
                    ;
                    array_push($kardex, $nuevoRegistro);
                }
            }
            if (count($kardex) >= 1) {
                DB::table('kardex')
                    ->insert($kardex);
                return $kardex;
            } else {
                return 'sinDatos';
            }
        } catch (\Exception $e) {
            // Retorna false ocurrio un error
            return false;
        }
    }

    private function getArticulosVendidos($req)
    {
        $articulos = DB::table('ventas as v')
            ->join('ventas_articulo as va', 'v.IdVentas', 'va.IdVentas')
            ->join('sucursal as s', 'v.IdSucursal', 's.IdSucursal')
            ->join('articulo as a', 'va.IdArticulo', 'a.IdArticulo')
            ->select('va.IdArticulo', 'va.Cantidad as CantidadVendida', 'va.Descuento', 'va.Importe as PrecioVenta', 'v.IdSucursal', 'a.CodigoInterno', 'a.Precio', 's.Nombre as NombreSucursal', 'a.Descripcion as NombreArticulo', 'a.Stock as CantidadActual', DB::raw('concat(v.Serie, "-", v.Numero) as CorrelativoVenta'), 'a.IdTipo as TipoArticulo')
            ->where('v.Serie', $req->serie)
            ->where('v.Numero', $req->numero)
            ->where('v.IdSucursal', $req->idSucursal)
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

    public function actualizarHashAndQr(Request $req)
    {
        if ($req->ajax()) {
            try {
                DB::beginTransaction();
                $datosVenta = $req->datosVenta;
                $fechaCreacion = Carbon::createFromFormat('Y-m-d H:i:s', $datosVenta[0]['FechaCreacion'])->format('Y-m-d');

                $tipoDocumento = [
                    '1' => '03', //Boleta
                    '2' => '01', //Factura
                ];

                $tipoDocumentoSeleccionado = $tipoDocumento[$datosVenta[0]['IdTipoComprobante']];

                $cliente = DB::table('cliente')
                    ->join('tipo_documento', 'cliente.IdTipoDocumento', '=', 'tipo_documento.IdTipoDocumento')
                    ->select('cliente.NumeroDocumento', 'tipo_documento.CodigoSunat')
                    ->where('IdCliente', $datosVenta[0]['IdCliente'])
                    ->first();

                $empresa = DB::table('empresa')
                    ->join('usuario', 'empresa.CodigoCliente', '=', 'usuario.CodigoCliente')
                    ->select('empresa.Ruc as RucEmpresa')
                    ->where('usuario.IdUsuario', $datosVenta[0]['IdCreacion'])
                    ->first();

                $resumen = "$empresa->RucEmpresa|$tipoDocumentoSeleccionado|{$datosVenta[0]['Serie']}|{$datosVenta[0]['Numero']}|{$datosVenta[0]['IGV']}|{$datosVenta[0]['Total']}|$fechaCreacion|{$cliente->CodigoSunat}|{$cliente->NumeroDocumento}";

                $hash = 'tDiwQMnpuPU3V0NKeefQw5uEuoM=';

                $nuevosDatos = (object) ['resumen' => $resumen, 'hash' => $hash];

                if ($req->switchActualizarHashResumen === 'true') {
                    $array = ['resumen' => $resumen, 'hash' => $hash];
                }
                if ($req->switchActualizarHash === 'true') {
                    $array = ['hash' => $hash];
                }
                if ($req->switchActualizarResumen === 'true') {
                    $array = ['resumen' => $resumen];
                }

                DB::table('ventas')->where('IdVentas', $datosVenta[0]['IdVentas'])->update($array);

                DB::commit();
                return response()->json(['respuesta' => 'success', 'nuevosDatos' => $nuevosDatos]);

            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['respuesta' => 'error', 'mensaje' => 'No se pudo realizar la reposición de stock'], 500);
            }
        }
    }
}
