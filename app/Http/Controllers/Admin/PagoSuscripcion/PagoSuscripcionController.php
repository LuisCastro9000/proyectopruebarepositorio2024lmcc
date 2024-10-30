<?php
namespace App\Http\Controllers\Admin\PagoSuscripcion;

use App\Http\Controllers\Controller;
use App\Traits\GestionarImagenesS3Trait;
use App\Traits\getFuncionesTrait;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PagoSuscripcionController extends Controller
{
    use getFuncionesTrait;
    use GestionarImagenesS3Trait;
    public function index(Request $req)
    {
        $ultimaSemana = Carbon::today()->subWeek();

        $pagosClientes = DB::table('pagos_plataforma')
            ->when($req->inputEstadoPago !== null, function ($query) use ($req) {
                // Si $req->inputEstadoPago no es null, busca solo por el estado especificados
                return $query->where('Estado', $req->inputEstadoPago);
            })
            ->where('FechaRegistro', '>=', $ultimaSemana)
            ->get();

        $array = ['pagosClientes' => $pagosClientes];
        return view('admin/pagoSuscripcion/ver-pagos', $array);
    }

    public function create(Request $req)
    {
        return view('admin/pagoSuscripcion/registrar-pago');
    }

    public function store(Request $req)
    {
        $registroPago = $this->existeRegistroPago($req);
        if ($registroPago) {
            return back()->with('error', "El número de Operación $req->inputNumeroOperacion ingresado, ya se encuentra registrado");
        }
        $imagen = $req->inputImagen;
        // if ($imagen != null) {
        //     $nombreImagen = "$req->inputNumeroOperacion";
        //     $directorio = 'ComprobantesPagoPlataforma/';
        //     $imagen = $this->storeImagenFormatoFileS3($imagen, $imagenAnterior = null, $nombreImagen, $directorio, $req->inputNumeroRuc, $accion = 'store');
        // }
        if ($imagen != null) {
            $directorio = $this->generarUbicacionArchivo('ComprobantesPagoPlataforma/', "$req->inputNumeroRuc/");
            $nombreImagen = "$req->inputNumeroOperacion";
            $imagen = $this->storeImagenFormatoFileS3($imagen, $imagenAnterior = null, $nombreImagen, $directorio, $accion = 'store');
        }

        $fechaDeposito = $this->formatearFechaRecibidaConSlash($req->inputFechaDeposito);
        $array = ['Ruc' => $req->inputNumeroRuc, 'IdEmpresa' => $req->inputIdEmpresa, 'NombreEmpresa' => $req->inputNombreEmpresa, 'NumeroOperacion' => $req->inputNumeroOperacion, 'MontoPago' => $req->inputMontoPago, 'Imagen' => $req->inputImagen, 'Estado' => 'Sin Verificar', 'Imagen' => $imagen, 'FechaRegistro' => now(), 'Celular' => $req->inputNumeroCelular, 'CodigoCliente' => $req->inputCodigoCliente, 'FechaDeposito' => $fechaDeposito];
        DB::table('pagos_plataforma')->insert($array);
        return redirect()->route('registro-pago.create')->with('success', 'Su registro de pago fue recibido exitosamente, una vez verificado se actualizará y/o reactivará su plan en un promedio de 4 horas dentro de los horarios regulares de atención de la Empresa; luego podrá descargar su factura después de 24 horas en la opción: Consultar Pagos dentro del área de Soporte.');
    }

    public function update(Request $req, $id)
    {
        if ($req->ajax()) {
            try {
                DB::table('pagos_plataforma')->where('Id', $id)->update(['Estado' => 'Verificado']);
                return response()->json(['respuesta' => 'success']);

            } catch (\Exception $e) {
                // Captura la excepción y devuelve un mensaje de error
                return response()->json(['respuesta' => 'error', 'detalleError' => $e->getMessage()]);
                // return response()->json(['respuesta' => 'error', 'detalleError' => $e->getMessage()], 500);

            }
        }
    }

    public function renovarSuscripcion(Request $req)
    {

        if ($req->checkRenovarSuscripcion != null) {
            DB::beginTransaction();
            try {
                foreach ($req->checkRenovarSuscripcion as $item) {
                    $periodo = $req->get('selectPeriodoSuscripcion' . $item);
                    $fechaContrato = $this->formatearFechaRecibidaConSlash($req->get('inputFechaSuscripcion' . $item));
                    $fechaCDT = $this->formatearFechaRecibidaConSlash($req->get('inputFechaCdt' . $item));
                    $montoPago = $req->get('inputPrecio' . $item);
                    $bloqueo = $req->get('inputDiasBloqueo' . $item);
                    DB::table('suscripcion')
                        ->where('IdSucursal', $item)
                        ->update(['Plan' => $periodo, 'FechaFinalContrato' => $fechaContrato, 'FechaFinalCDT' => $fechaCDT, 'MontoPago' => $montoPago, 'Bloqueo' => $bloqueo, 'FechaActualizacion' => date("Y-m-d H:i:s")]);
                }
                DB::table('usuario')
                    ->where('Estado', 'Suscripcion Caducada')
                    ->whereIn('IdSucursal', $req->checkRenovarSuscripcion)
                    ->update(['Estado' => 'E']);
                DB::table('sucursal')
                    ->where('Estado', 'Suscripcion Caducada')
                    ->whereIn('IdSucursal', $req->checkRenovarSuscripcion)
                    ->update(['Estado' => 'E']);

                DB::table('pagos_plataforma')->where('Id', $req->inputIdPagoSucripcion)->update(['Estado' => 'Renovado']);
                DB::commit();

            } catch (\Exception $e) {
                DB::rollback();
                Redirect::route('pagos-plan-sucripcion.index')->with('error', '`Hubo un error en la solicitud. Por favor, póngase en contacto con el área de soporte técnico');
            }
        }
        return Redirect::route('pagos-plan-sucripcion.index');
    }

    public function getSuscripcionesAjax(Request $req)
    {
        if ($req->ajax()) {
            $codigoCliente = $req->codigoCliente;
            $suscripciones = DB::table('suscripcion')
                ->rightjoin('sucursal', 'suscripcion.IdSucursal', '=', 'sucursal.IdSucursal')
                ->select('sucursal.CodigoCliente', 'sucursal.Estado', 'sucursal.Nombre', 'sucursal.IdSucursal', 'Plan', 'FechaFinalContrato', 'FechaFinalCDT', 'MontoPago', 'Bloqueo', DB::raw('CASE WHEN sucursal.Estado = "E" THEN "Activada" ELSE "Caducada" END AS EstadoSuscripcion'))
                ->whereIn('sucursal.Estado', ['E', 'Suscripcion Caducada'])
                ->where('sucursal.CodigoCliente', $codigoCliente)
                ->orderBy('sucursal.IdSucursal', 'desc')
                ->get();
            return view('admin.pagoSuscripcion.renovar-suscripcion', compact('suscripciones'))->render();
        }
    }

    private function existeRegistroPago($req)
    {
        $fechaDeposito = Carbon::createFromFormat('d/m/Y', $req->inputFechaDeposito)->format('Y-m-d');
        $resultado = DB::table('pagos_plataforma')
            ->where('Ruc', $req->inputNumeroRuc)
            ->where('NumeroOperacion', $req->inputNumeroOperacion)
            ->whereDate('FechaDeposito', $fechaDeposito)
            ->exists();
        return $resultado;

    }

    public function consultarRuc(Request $req)
    {
        if ($req->ajax()) {
            $numeroRuc = $req->numDoc;
            //Obtener Empresa
            $empresa = $this->getEmpresa($numeroRuc);
            if ($empresa !== null) {
                return Response()->json(['respuesta' => 'success', 'empresa' => $empresa]);
            } else {
                return Response()->json(['respuesta' => 'error', 'mensaje' => 'No se encontro ninguna coincidencia con el Ruc Ingresado']);
            }
        }
    }

    public function getEmpresa($ruc)
    {
        $empresa = DB::table('empresa')
            ->select('empresa.CodigoCliente', 'empresa.Nombre as NombreEmpresa', 'empresa.IdEmpresa')
            ->where('Ruc', $ruc)->first();
        return $empresa;
    }
}
