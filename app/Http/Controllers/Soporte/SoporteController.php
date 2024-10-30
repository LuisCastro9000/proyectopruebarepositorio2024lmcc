<?php

namespace App\Http\Controllers\Soporte;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use App\Http\Controllers\Operaciones\NumeroALetras;
use App\Traits\getFuncionesTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Session;
use Storage;

class SoporteController extends Controller
{
    use getFuncionesTrait;
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $permisos = $loadDatos->getPermisos($idUsuario);
        $datosEmpresa = $loadDatos->getRucEmpresa($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $isMobileDevice = $loadDatos->isMobileDevice();
        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'datosEmpresa' => $datosEmpresa, 'isMobileDevice' => $isMobileDevice];
        return view('soporte/soporte', $array);
    }

    public function consultarFacturas(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $datosEmpresa = $loadDatos->getRucEmpresa($idUsuario);
        $añoActual = Carbon::now()->year;

        $facturas = $this->getFacturasClientesErp($datosEmpresa->Ruc, $añoActual);
        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'facturas' => $facturas];
        return view('soporte/consultarFacturas', $array);
    }

    private function getFacturasClientesErp($rucEmpresa, $anio)
    {
        $facturas = DB::table('facturas_clientes_erp')
            ->where('RucCliente', $rucEmpresa)
            ->where('Estado', 'Aceptado')
            ->where(DB::raw('YEAR(FechaCreacion)'), $anio)
            ->get();
        $facturasActualizadas = [];
        foreach ($facturas as $factura) {
            $resultado = DB::table('ventas')
                ->select('IdVentas', 'Estado')
                ->where('IdVentas', $factura->IdVentas)
                ->first();
            if ($resultado->Estado == 'Baja Pendiente' || $resultado->Estado == 'Baja Aceptado') {
                DB::table('facturas_clientes_erp')
                    ->where('IdVentas', $factura->IdVentas)
                    ->update(['Estado' => 'Anulado', 'FechaActualizacion' => Carbon::now()->toDateTimeString()]);
                array_push($facturasActualizadas, $factura->IdVentas);
            }
        }
        $facturas = $facturas->whereNotIn('IdVentas', $facturasActualizadas);
        return $facturas;
    }

    public function getFacturasClientesErpConAjax(Request $req)
    {
        if ($req->ajax()) {
            if ($req->session()->has('idUsuario')) {
                $idUsuario = Session::get('idUsuario');
            } else {
                Session::flush();
                return redirect('/')->with('out', 'Sesión de usuario Expirado');
            }
            $loadDatos = new DatosController();
            $datosEmpresa = $loadDatos->getRucEmpresa($idUsuario);
            $facturas = $this->getFacturasClientesErp($datosEmpresa->Ruc, $req->anio);

            return view('soporte._tabla', compact('facturas'));
        }
    }

    // GENERAR PDF
    public function descargarFactura(Request $req, $id, $codigoClienteFacturador, $tipoArchivo)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        if ($tipoArchivo === 'Pdf') {
            $loadDatos = new DatosController();
            $ventaSelect = $loadDatos->getVentaselect($id);
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $empresaFacturadora = $loadDatos->getDatosEmpresa($codigoClienteFacturador);

            $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
            $pdf = $this->generarPDF($req, $id, $codigoClienteFacturador, $empresa, $empresaFacturadora);
            $numeroCerosIzquierda = $this->completarCeros($ventaSelect->Numero);
            $serie = $ventaSelect->Serie;
            return $pdf->download($empresaFacturadora->Ruc . '-02-' . $serie . '-' . $numeroCerosIzquierda . '.pdf');

        }

        if ($tipoArchivo === 'Xml' || $tipoArchivo === 'Cdr') {
            $tipoCampo = ($tipoArchivo === 'Xml') ? 'RutaXml' : 'RutaCdr';
            $ventaEncontrada = DB::table('ventas')->where('IdVentas', $id)->first();
            if (Storage::disk('s3')->exists($ventaEncontrada->$tipoCampo)) {
                $rutaS3 = Storage::disk('s3')->get($ventaEncontrada->$tipoCampo);
                $file = basename($ventaEncontrada->$tipoCampo);
                $headers = [
                    'Content-Type' => 'text/xml',
                    'Content-Description' => 'File Transfer',
                    'Content-Disposition' => "attachment; filename={$file}",
                    'filename' => $file,
                ];
                return response($rutaS3, 200, $headers);
            } else {
                return back()->with('error', "No se encontró el archivo {$tipoArchivo}");
            }
        }

    }

    public function generarPDF($req, $id, $codigoClienteFacturador, $empresa, $empresaFacturadora)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        $idSucursal = Session::get('idSucursal');
        $loadDatos = new DatosController();
        $ventaSelect = $loadDatos->getVentaselect($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $fecha = date_create($ventaSelect->FechaCreacion);
        $formatoFecha = date_format($fecha, 'd-m-Y');
        $formatoHora = date_format($fecha, 'H:i A');
        if ($ventaSelect->IdTipoPago == 1) {
            $fechaPago = '';
        } else {
            $fechaPagoConvert = Carbon::parse($ventaSelect->FechaCreacion);
            $fechaPagoDias = $fechaPagoConvert->addDays($ventaSelect->PlazoCredito);
            $fechaPagoDate = new DateTime($fechaPagoDias);
            $fechaPago = date_format($fechaPagoDate, 'd-m-Y');
        }
        $convertirLetras = new NumeroALetras();
        if ($ventaSelect->IdTipoMoneda == 1) {
            $totalDetrac = $ventaSelect->Total;
            $importeLetras = $convertirLetras->convertir(floatval($ventaSelect->Total) + floatval($ventaSelect->Amortizacion), 'soles');
        } else {
            $fechaDetrac = Carbon::parse($ventaSelect->FechaCreacion);
            $fechaDetrac = date_format($fechaDetrac, 'Y-m-d');
            $valorCambio = DB::table('tipo_cambio')
                ->where('IdSucursal', $idSucursal)
                ->where('FechaCreacion', $fechaDetrac)
                ->first();

            if ($valorCambio) {
                $totalDetrac = $ventaSelect->Total * $valorCambio->TipoCambioVentas;
            } else {
                $totalDetrac = $ventaSelect->Total;
            }

            $importeLetras = $convertirLetras->convertir(floatval($ventaSelect->Total) + floatval($ventaSelect->Amortizacion), 'dólares');
        }

        $nombreEmpresa = $empresaFacturadora->Nombre;
        if ($ventaSelect->Seguro != null && $ventaSelect->Seguro > 2) {
            $datosSeguro = $this->getDatosSeguro($ventaSelect->IdVentas);
            $seguroNombre = $datosSeguro->Descripcion;
            $idSeguro = $datosSeguro->IdSeguro;
        } else {
            $seguroNombre = "";
            $idSeguro = 1;
        }

        $numeroCerosIzquierda = $this->completarCeros($ventaSelect->Numero);
        $resumen = $ventaSelect->Resumen;
        $hash = $ventaSelect->Hash;

        $items = $loadDatos->getItemsVentasNuevo($id);

        $itemsServ = $items->where('IdTipo', 2);
        $cuentasCorrientes = $this->getCuentasCorrientes($codigoClienteFacturador);
        $exp = explode("\n", $ventaSelect->Observacion);
        $lineas = count($exp);
        if ($lineas <= 5) {
            $lineas = $lineas * 8;
        } else if ($lineas > 5 && $lineas <= 10) {
            $lineas = $lineas * 10;
        } else {
            $lineas = $lineas * 12;
        }

        $sucursal = $loadDatos->getSucursales($usuarioSelect->CodigoCliente, $usuarioSelect->IdOperador)->where("Principal", 0)->first();
        $array = ['itemsServ' => $itemsServ, 'seguroNombre' => $seguroNombre, 'idSeguro' => $idSeguro, 'numeroCeroIzq' => $numeroCerosIzquierda, 'lineas' => $lineas, 'ventaSelect' => $ventaSelect, 'resumen' => $resumen, 'hash' => $hash, 'cuentasCorrientes' => $cuentasCorrientes,
            'totalDetrac' => $totalDetrac, 'fechaPago' => $fechaPago, 'formatoFecha' => $formatoFecha, 'formatoHora' => $formatoHora, 'importeLetras' => $importeLetras, 'empresa' => $empresa, 'nombreEmpresa' => $nombreEmpresa, 'sucursal' => $sucursal, 'empresaFacturadora' => $empresaFacturadora];
        view()->share($array);

        $pdf = PDF::loadView('pdf/facturaClienteErp')->setPaper('a4', 'portrait');

        return $pdf;
    }

    public function completarCeros($numero)
    {
        $numeroConCeros = str_pad($numero, 8, "0", STR_PAD_LEFT);
        return $numeroConCeros;
    }

    public function getCuentasCorrientes($codigoCliente)
    {

        $resultado = DB::table('banco')
            ->join('lista_banco', 'banco.IdListaBanco', '=', 'lista_banco.IdListaBanco')
            ->join('tipo_moneda', 'banco.IdTipoMoneda', '=', 'tipo_moneda.IdTipoMoneda')
            ->join('tipos_cuentas_bancarias as tcb', 'banco.IdCuentaBancaria', '=', 'tcb.IdCuentaBancaria')
            ->select('banco.NumeroCuenta', 'lista_banco.Nombre as Banco', 'tipo_moneda.Nombre as Moneda', 'banco.CCI', DB::Raw('UPPER(tcb.Nombre) as NombreCuenta'))
            ->where('CodigoCliente', $codigoCliente)
            ->where('banco.Estado', 'E')
            ->limit(5)
            ->get();
        return $resultado;
    }
}
