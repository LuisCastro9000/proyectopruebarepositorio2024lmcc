<?php

namespace App\Http\Controllers\Consultas;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DateTime;
use Illuminate\Http\Request;
use Session;

class ConsultaComprasBoletaFacturaController extends Controller
{
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $tipoPago = 0;
        $fecha = 5;
        $fechaIni = '';
        $fechaFin = '';
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $comprobanteCompras = $loadDatos->getComprasAll($idSucursal);
        // Nuevo codigo
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $comprobanteOrdenesCompras = $loadDatos->getConsultaOrdenesCompras($idSucursal, $tipoPago, $fechas[0], $fechas[1]);
        // Fin
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['comprobanteCompras' => $comprobanteCompras, 'permisos' => $permisos, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'comprobanteOrdenesCompras' => $comprobanteOrdenesCompras];
        return view('consultas/consultaComprasBoletaFactura', $array);
    }

    public function store(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $tipoPago = $req->tipoPago;
        $fecha = $req->fecha;
        $fechaIni = $req->fechaIni;
        $fechaFin = $req->fechaFin;
        if ($fecha == 9) {
            if ($fechaIni == null || $fechaFin == null) {
                return back()->with('error', 'Completar las fechas para filtrar');
            }
            $dateIni = DateTime::createFromFormat('d/m/Y', $fechaIni);
            $dateFin = DateTime::createFromFormat('d/m/Y', $fechaFin);
            if (strtotime($dateIni->format('Y-m-d')) > strtotime($dateFin->format('Y-m-d'))) {
                return back()->with('error', 'La fecha Inicial no puede ser mayor que la Final');
            }
        }
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $comprobanteCompras = $loadDatos->getComprasAllFiltrado($idSucursal, $tipoPago, $fecha, $fechaIni, $fechaFin);
        // Nuevo codigo
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $comprobanteOrdenesCompras = $loadDatos->getConsultaOrdenesCompras($idSucursal, $tipoPago, $fechas[0], $fechas[1]);
        // Fin
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['comprobanteCompras' => $comprobanteCompras, 'IdTipoPago' => $tipoPago, 'fecha' => $fecha, 'permisos' => $permisos, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'comprobanteOrdenesCompras' => $comprobanteOrdenesCompras];
        return view('consultas/consultaComprasBoletaFactura', $array);
    }

    public function descargarPDF($id)
    {
        $pdf = $this->generarPDF($id);
        $loadDatos = new DatosController();
        $ventaSelect = $loadDatos->getVentaselect($id);
        $numeroCerosIzquierda = $this->completarCeros($ventaSelect->Numero);
        $serie = $ventaSelect->Serie;
        return $pdf->download('V-' . $serie . '-' . $numeroCerosIzquierda . '.pdf');
    }

    /* public function fechaVencimiento( Request $req, $id )
{
if ( $req->session()->has( 'idUsuario' ) ) {
$idUsuario = Session::get( 'idUsuario' );
} else {
Session::flush();
return redirect( '/' )->with( 'out', 'Sesión de usuario Expirado' );
}

$loadDatos = new DatosController();
$compraSelect = $loadDatos->getCompraselect( $id );
$idUsuario = Session::get( 'idUsuario' );
$permisos = $loadDatos->getPermisos( $idUsuario );
$fecha = date_create( $compraSelect->FechaCreacion );
$formatoFecha = date_format( $fecha, 'd-m-Y' );
$formatoHora = date_format( $fecha, 'H:i A' );
$items = $loadDatos->getItemsCompras( $id );
$usuarioSelect = $loadDatos->getUsuarioSelect( $idUsuario );
$modulosSelect = $loadDatos->getModulosSelect( $usuarioSelect->CodigoCliente );
$array = [ 'compraSelect' => $compraSelect, 'items' => $items, 'formatoFecha' => $formatoFecha, 'permisos' => $permisos, 'formatoHora' => $formatoHora, 'modulosSelect' => $modulosSelect ];
return view( 'consultas/consultaFechaVencimiento', $array )->with( 'status', 'Se busco la compra exitosamente' );
}

public function fechaAction()
{
try {
if ( $req->session()->has( 'idUsuario' ) ) {
if ( $req->ajax() ) {
$idUsuario = Session::get( 'idUsuario' );
$identificador = $req->idem;
if ( $identificador == null ) {
return Response( [ 'alert1', 'No existe identificador' ] );
}
$fecha = $req->fechaVenc;
if ( $fecha == 0 ) {
return Response( [ 'alert2', 'Por favor, elegir Fecha valida' ] );
}

return Response( [ 'succes', 'Guardado' ] );
}
} else {
Session::flush();
return redirect( '/' )->with( 'out', 'Sesión de usuario Expirado' );
}
} catch ( Exception $ex ) {
echo $ex->getMessage();
}
}
 */
}
