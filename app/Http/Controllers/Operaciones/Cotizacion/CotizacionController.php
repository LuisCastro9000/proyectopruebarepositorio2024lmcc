<?php

namespace App\Http\Controllers\Operaciones\Cotizacion;

use App\Exports\ExcelCotizaciones;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use App\Http\Controllers\Operaciones\NumeroALetras;
use App\Http\Controllers\Servicios\config;
// use App\Models\Articulo as MasterArticulo;
// use App\Models\Cotizacion as DocCotizacion;
// use App\Models\Cotizacion_articulo as DetalleCotizacion_art;
// use App\Models\Estados_cotizacion as EstadosCotizacion;
// use App\Models\Sucursal as MasterSucursal;
use App\Models\Cotizacion as DocCotizacion;
use App\Models\Cotizacion_articulo as DetalleCotizacion_art;
use App\Models\Cotizacion_articulopaquetepromocional as DetalleCotizacion_artprom;
use App\Models\Estados_cotizacion as EstadosCotizacion;
use App\Models\Sucursal as MasterSucursal;
use App\Traits\ArchivosS3Trait;
use Carbon\Carbon;
use DateTime;
use DB;
#clases modelos LMCC 16-07-2023

#clases modelos LMCC 16-07-2023
use DOMDocument;
use Excel;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\Cuota;
use Greenter\Model\Sale\DetailAttribute;
use Greenter\Model\Sale\Detraction;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\FormaPagos\FormaPagoCredito;
#fin modelos add

use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Ws\Services\SunatEndpoints;
use Illuminate\Http\Request;
use PDF;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Session;

class CotizacionController extends Controller
{
    use ArchivosS3Trait;
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        /*$Listadetallecotizacionart= DetalleCotizacion_art::select('*')
        ->offset(0)
        ->limit(10)
        ->get();*/
        //$listacotizaciones= DocCotizacion::all();
        // $Listadetallecotizacionart= DetalleCotizacion_art::select('*')->limit(10)->get();
        //dd($Listadetallecotizacionart);

        $idSucursal = Session::get('idSucursal');

        $text = "";
        $clientes = $loadDatos->getClientes($idSucursal);
        $tipoMonedas = $loadDatos->getTipoMoneda();
        $listaGruposSoles = $this->getListaGrupos($idSucursal, 1);
        $listaGruposDolares = $this->getListaGrupos($idSucursal, 2);

        // PAQUETES MISCELANEOS
        $paquetesPromocionalesSoles = $this->getPaquetesPromocionales($idSucursal, 1);
        $paquetesPromocionalesDolares = $this->getPaquetesPromocionales($idSucursal, 2);
        // FIN

        $cod_cliente = DB::table('sucursal')
            ->select('CodigoCliente')
            ->where('IdSucursal', $idSucursal)
            ->first();

        $Datossucursal = MasterSucursal::select("CodigoCliente")->where("IdSucursal", $idSucursal)->first();
        //echo  $Datossucursal;
        //dd($Datossucursal);

        $sucPrincipal = DB::table('sucursal')
            ->select('IdSucursal')
            ->where('CodigoCliente', $cod_cliente->CodigoCliente)
            ->where('Principal', 1)
            ->first();
        $IdSucPrincipal = MasterSucursal::select("IdSucursal")->where("CodigoCliente", $Datossucursal->CodigoCliente)->where("Principal", 1)->first();
        /*var_dump($sucPrincipal);
        echo "- otro cod sucursal<br>";
        var_dump($IdSucPrincipal->IdSucursal);
        dd($IdSucPrincipal);*/

        //dd($idSucursal);
        if ($sucPrincipal->IdSucursal == $idSucursal) {
            $productos = $loadDatos->getProductosPagination($idSucursal, $text, 1, 0);
            $productosDolares = $loadDatos->getProductosPagination($idSucursal, $text, 2, 0);

        } else {
            $productos = $loadDatos->paginarAjaxProdSucursal($idSucursal, $text, 1, 0);
            $productosDolares = $loadDatos->paginarAjaxProdSucursal($idSucursal, $text, 2, 0);
        }
        //dd($productos);
        $servicios = $loadDatos->getServiciosPagination($idSucursal, 1, $text);
        $serviciosDolares = $loadDatos->getServiciosPagination($idSucursal, 2, $text);

        $permisos = $loadDatos->getPermisos($idUsuario);
        $totalVentas = $loadDatos->getTotalVentas($idSucursal, $idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $fecha = date("d/m/Y");

        $tipoComprobante = $loadDatos->getTipoComprobante();
        $tipoDoc = $loadDatos->TipoDocumento();

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $orden = $usuarioSelect->Orden;

        $editarPrecio = $usuarioSelect->EditarPrecio;
        $sucursal = $loadDatos->getSucursalSelect($idSucursal);

        $ordenSucursal = $sucursal->Orden;
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        // Nuevo codigo
        if ($modulosSelect->contains('IdModulo', 7)) {
            $moduloCronogramaActivo = 'activado';
        } else {
            $moduloCronogramaActivo = 'desactivado';
        }
        // Fin

        $operarios = $loadDatos->getOperarios($idSucursal);
        $sucExonerado = $sucursal->Exonerado;
        $categorias = $loadDatos->getCategorias($usuarioSelect->CodigoCliente);
        $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $bandVentaSolesDolares = $datosEmpresa->VentaSolesDolares;
        $exonerado = $datosEmpresa->Exonerado;
        $tiposAtenciones = $loadDatos->getTiposAtenciones();
        // dd($tiposAtenciones);
        /*var_dump($tiposAtenciones);
        die();*/

        $numeroDB = DB::table('cotizacion')
            ->select('Numero')
            ->where('IdCreacion', $idUsuario)
            ->where('IdSucursal', $idSucursal)
            ->orderBy('IdCotizacion', 'desc')
            ->first();

        $cotizacindet = DocCotizacion::select("*")->where("IdCreacion", $idUsuario)->where("IdSucursal", $idSucursal)->OrderBy("IdCotizacion", "desc")->first();

        if ($numeroDB) {
            $numero = str_pad($numeroDB->Numero + 1, 8, "0", STR_PAD_LEFT);
        } else {
            $numero = str_pad(1, 8, "0", STR_PAD_LEFT);
        }

        //echo $numero ."<br>";
        //var_dump($numeroDB);
        // var_dump($cotizacindet);
        // dd($cotizacindet);

        $serieCeros = str_pad($orden, 2, "0", STR_PAD_LEFT);
        $serie = 'C' . $ordenSucursal . '' . $serieCeros;

        $array = ['clientes' => $clientes, 'operarios' => $operarios, 'Serie' => $serie, 'Numero' => $numero, 'tipoMoneda' => $tipoMonedas, 'tipoComprobante' => $tipoComprobante, 'pagoEfectivo' => '', 'fecha' => $fecha, 'totalVentas' => $totalVentas, 'sucExonerado' => $sucExonerado, 'exonerado' => $exonerado, 'editarPrecio' => $editarPrecio, 'tiposAtenciones' => $tiposAtenciones, 'bandVentaSolesDolares' => $bandVentaSolesDolares,
            'categorias' => $categorias, 'productos' => $productos, 'productosDolares' => $productosDolares, 'servicios' => $servicios, 'serviciosDolares' => $serviciosDolares, 'tipoDoc' => $tipoDoc, 'permisos' => $permisos, 'idSucursal' => $idSucursal, 'modulosSelect' => $modulosSelect, 'orden' => $orden, 'ordenSucursal' => $ordenSucursal, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles,
            'listaGruposSoles' => $listaGruposSoles, 'listaGruposDolares' => $listaGruposDolares, 'moduloCronogramaActivo' => $moduloCronogramaActivo, 'paquetesPromocionalesSoles' => $paquetesPromocionalesSoles, 'paquetesPromocionalesDolares' => $paquetesPromocionalesDolares, 'usuarioSelect' => $usuarioSelect, 'deshabilidato' => ''];

        return view('operaciones/cotizacion/crearVenta', $array);
    }

    public function revertirEstadoConAjax(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        try {
            if ($req->ajax()) {
                $resultado = DB::table('cotizacion')->where('IdCotizacion', $req->idCotizacion)->update(['IdEstadoCotizacion' => 1]);
                if ($resultado > 0) {
                    return response()->json(['respuesta' => 'success']);
                } else {
                    return response()->json(['respuesta' => 'error']);
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }

    }

    // NUEVA FUNCION PAGINAR GRUPO

    public function searchGrupo(Request $req)
    {
        if ($req->ajax()) {
            $idSucursal = Session::get('idSucursal');
            $grupo = $this->getPaginarListaGrupos($idSucursal, $req->tipoMoneda, $req->textoBuscar);

            return Response($grupo);
        }
    }

    public function paginationGrupos(Request $req)
    {
        if ($req->ajax()) {
            $idSucursal = Session::get('idSucursal');
            $grupoSoles = $this->getPaginarListaGrupos($idSucursal, $req->tipoMoneda, $req->textoNombreGrupo);
            return Response($grupoSoles);
        }
    }

    private function getListaGrupos($idSucursal, $idTipoMoneda)
    {
        try {
            $listaGrupos = DB::table('grupos_productos_servicios')
                ->select('*')
                ->where('IdSucursal', $idSucursal)
                ->where('IdTipoMoneda', $idTipoMoneda)
                ->where('Estado', 'E')
                ->orderBy('FechaCreacion', 'desc')
                ->paginate(8);
            return $listaGrupos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getPaginarListaGrupos($idSucursal, $idTipoMoneda, $textoNombreGrupo)
    {
        try {
            $listaGrupos = DB::table('grupos_productos_servicios')
                ->select('*')
                ->where('IdSucursal', $idSucursal)
                ->where('IdTipoMoneda', $idTipoMoneda)
                ->where('Estado', 'E')
                ->Where('NombreGrupo', 'like', '%' . $textoNombreGrupo . '%')
                ->orderBy('FechaCreacion', 'desc')
                ->paginate(8);
            return $listaGrupos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function obtenerItemsGrupo(Request $req)
    {
        try {
            if ($req->ajax()) {

                $detalleGrupo = DB::table('detalle_grupo')
                    ->join('articulo', 'detalle_grupo.IdArticulo', '=', 'articulo.IdArticulo')
                    ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                    ->select('articulo.IdArticulo', 'articulo.Descripcion AS NombreArticulo', 'articulo.IdMarca', 'articulo.Precio', 'articulo.Stock', 'articulo.Codigo AS codigoBarra', 'articulo.IdTipoMoneda AS idTipoMonedaItems', 'articulo.IdCategoria', 'unidad_medida.Nombre as UM', 'unidad_medida.IdUnidadMedida', 'articulo.IdTipo AS idTipoItems', 'articulo.Costo', 'detalle_grupo.CantidadArticulo')
                    ->where('IdGrupo', $req->idGrupo)
                    ->where('articulo.Estado', 'E')
                    ->get();

                return Response($detalleGrupo);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    // FIN

    // ================= GET PAQUETES Promocionales ======================

    // paquetes para mostrar en la card del modal
    public function getPaquetesPromocionales($idSucursal, $idTipoMoneda)
    {
        try {
            $datos = DB::table('paquetes_promocionales AS pp')
                ->join('articulo_paquetePromocional AS app', 'pp.IdPaquetePromocional', '=', 'app.IdPaquetePromocional')
                ->join('articulo AS a', 'app.IdArticulo', '=', 'a.IdArticulo')
                ->select('pp.IdPaquetePromocional', 'pp.NombrePaquete', 'pp.IdTipoMoneda', 'pp.IdSucursal', 'pp.Etiqueta', DB::raw('ROUND(SUM(a.precio * app.Cantidad), 2) as Total'), DB::raw('SUM(a.costo) as Costo'), 'pp.FechaCreacion', 'pp.Estado')
                ->where('pp.IdSucursal', $idSucursal)
                ->where('pp.Estado', 'E')
                ->where('pp.IdTipoMoneda', $idTipoMoneda)
                ->orderBy('pp.FechaCreacion', 'desc')
                ->groupBy('pp.IdPaquetePromocional')
                ->paginate(8);
            return $datos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // items para mostrar en el modal del detalle del paquete agregado a la cotizacion
    public function getDetallePaquetePromocional(Request $req)
    {
        try {
            if ($req->ajax()) {
                $loadDatos = new DatosController();
                $datos = $loadDatos->getItemsPaquetePromocional($req->idPaquete);
                return Response($datos);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getItemsPaquetePromocional($idPaquete)
    {
        $datos = DB::table('articulo_paquetePromocional AS app')
            ->join('articulo', 'app.IdArticulo', '=', 'articulo.IdArticulo')
            ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
            ->select('articulo.IdArticulo', 'articulo.Descripcion AS NombreArticulo', 'articulo.IdMarca', 'articulo.Precio', 'articulo.Stock', 'articulo.Codigo AS codigoBarra', 'articulo.IdTipoMoneda AS idTipoMonedaItems', 'articulo.IdCategoria', 'unidad_medida.Nombre as UM', 'unidad_medida.IdUnidadMedida', 'articulo.IdTipo AS idTipoItems', 'articulo.Costo', 'app.IdPaquetePromocional', 'app.cantidad', 'app.CodigoArticulo')
            ->where('IdPaquetePromocional', $idPaquete)
            ->where('articulo.Estado', 'E')
            ->get();
        return $datos;
    }
    // fin----------------------

    // Funciones para la paginacion de los paquetes
    public function searchPaquetesPromocionales(Request $req)
    {
        if ($req->ajax()) {
            $idSucursal = Session::get('idSucursal');
            $paquetes = $this->getPaginarPaquetesPromocionales($idSucursal, $req->tipoMoneda, $req->textoBuscar);
            return Response($paquetes);
        }
    }
    public function getPaginarPaquetesPromocionales($idSucursal, $idTipoMoneda, $textoNombrePaquete)
    {
        try {
            // $datos = DB::table('paquetes_promocional')
            //     ->select('*')
            //     ->where('IdSucursal', $idSucursal)
            //     ->where('IdTipoMoneda', $idTipoMoneda)
            //     ->where('Estado', 'E')
            //     ->Where('NombrePaquete', 'like', '%' . $textoNombrePaquete . '%')
            //     ->orderBy('FechaCreacion', 'desc')
            //     ->paginate(8);
            $datos = DB::table('paquetes_promocionales AS pp')
                ->join('articulo_paquetePromocional AS app', 'pp.IdPaquetePromocional', '=', 'app.IdPaquetePromocional')
                ->join('articulo AS a', 'app.IdArticulo', '=', 'a.IdArticulo')
                ->select('pp.IdPaquetePromocional', 'pp.NombrePaquete', 'pp.IdTipoMoneda', 'pp.IdSucursal', 'pp.Etiqueta', DB::raw('ROUND(SUM(a.precio * app.Cantidad), 2) as Total'), DB::raw('SUM(a.costo) as Costo'), 'pp.FechaCreacion')
                ->where('pp.IdSucursal', $idSucursal)
                ->where('pp.IdTipoMoneda', $idTipoMoneda)
                ->Where('NombrePaquete', 'like', '%' . $textoNombrePaquete . '%')
                ->where('pp.Estado', 'E')
                ->orderBy('pp.FechaCreacion', 'desc')
                ->groupBy('pp.IdPaquetePromocional')
                ->paginate(8);
            return $datos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    public function paginationPaquetesPromocionales(Request $req)
    {
        if ($req->ajax()) {
            $idSucursal = Session::get('idSucursal');
            $paquetes = $this->getPaginarPaquetesPromocionales($idSucursal, $req->tipoMoneda, $req->textoNombrePaquete);
            return Response($paquetes);
        }
    }
    // ----------------------------

    // traer paquetes promocionales para almacenarlo en la tabla cotizacion_articulo desde la funcion store y actualizar_cotizacion
    private function getDatosPaquetesPromocionales($idPaquete, $idSucursal)
    {
        try {
            $datos = DB::table('paquetes_promocionales AS pp')
                ->join('articulo_paquetePromocional AS app', 'pp.IdPaquetePromocional', '=', 'app.IdPaquetePromocional')
                ->join('articulo AS a', 'app.IdArticulo', '=', 'a.IdArticulo')
                ->select('pp.IdPaquetePromocional', 'pp.NombrePaquete', 'pp.IdTipoMoneda', 'pp.IdSucursal', 'pp.Etiqueta', 'app.Cantidad', DB::raw('ROUND(SUM(a.precio * app.Cantidad), 2) as Total'), DB::raw('SUM(a.costo) as Costo'))
                ->where('pp.IdPaquetePromocional', $idPaquete)
                ->where('pp.IdSucursal', $idSucursal)
                ->where('pp.Estado', 'E')
                ->first();
            return $datos;

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // Funciona obtener los items de los paquete para almacenarlo
    private function getItemsPaquetePromocionalStore($idPaquete)
    {
        $datos = DB::table('articulo_paquetePromocional AS app')
            ->join('articulo', 'app.IdArticulo', '=', 'articulo.IdArticulo')
            ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
            ->select('app.*', 'articulo.*', 'unidad_medida.Nombre as TextUnidad')
            ->whereIn('IdPaquetePromocional', $idPaquete)
            ->get();
        return $datos;
    }
    // Fin

    //  Funcion para verificar si existe paquetes promocionales en la cotizacion
    private function verificarExistenciaPaquetePromocional($idPaquete, $idCotizacion)
    {
        $resultado = DB::table('cotizacion_articulo')
            ->where('IdPaquetePromocional', $idPaquete)
            ->where('IdCotizacion', $idCotizacion)
            ->first();
        return $resultado;
    }
    // Fin

    // Funcion para eliminar los items del cotizacion_articuloPaquetePromocional que han sido quitado al editar la cotizacion
    private function getArticulosPaquetePromoCotizacionGenerada($idCotizacion, $idPaquePromocional)
    {
        try {
            $datos = DB::table('cotizacion_articuloPaquetePromocional AS capp')
                ->where('capp.IdCotizacion', $idCotizacion)
                ->whereIn('capp.IdPaquetePromocional', $idPaquePromocional)
                ->get();
            return $datos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    // fin

    // ==================== FIN ======================= //

    public function store(Request $req)
    {

        try {
            if ($req->session()->has('idUsuario')) {
                if ($req->ajax()) {
                    $idUsuario = Session::get('idUsuario');
                    $idSucursal = Session::get('idSucursal');
                    $loadDatos = new DatosController();
                    $cotizacion = $req->cotizacion;

                    if ($cotizacion['IdCliente'] == 0) {
                        return Response()->json(['respuesta' => 'error', 'mensaje' => 'Por favor, elegir Cliente']);
                    }
                    if (empty($cotizacion['articulos'])) {
                        return Response()->json(['respuesta' => 'error', 'mensaje' => 'Por favor, agrege productos o servicios']);
                    }
                    if ($cotizacion['TipoCotizacion'] == 2) {
                        $placaVehic = $req->placaVehicular;
                        // Codigo para validarel tipo de atencion
                        if ($cotizacion['IdTipoAtencion'] == 0) {
                            return Response()->json(['respuesta' => 'error', 'mensaje' => 'Por favor, Seleccione el Tipo de Atención']);
                        }
                        if ($cotizacion['IdTipoAtencion'] == 1 || $cotizacion['IdTipoAtencion'] == 2 || $cotizacion['IdTipoAtencion'] == 6) {
                            if ($cotizacion['Campo1'] == '') {
                                return Response()->json(['respuesta' => 'error', 'mensaje' => 'Por favor, Ingrese el Kilometraje']);
                            }
                        }
                        // Fin
                    } else {
                        $placaVehic = '';
                    }

                    $proximoMantenimiento = null;
                    if ($req->moduloCronogramaActivo == "activado") {
                        if ($cotizacion['IdTipoAtencion'] == 1 || $cotizacion['IdTipoAtencion'] == 6) {
                            if ($cotizacion['PeriodoProximoMantenimiento'] == null) {
                                return Response()->json(['respuesta' => 'error', 'mensaje' => 'Ingrese el Período para el próximo Mantenimiento']);
                            }
                            if ($cotizacion['PeriodoProximoMantenimiento'] == 0) {
                                return Response()->json(['respuesta' => 'error', 'mensaje' => 'El Período para el próximo Mantenimiento debe ser mayor a CERO']);
                            }
                            if ($cotizacion['PeriodoProximoMantenimiento'] > 366) {
                                return Response()->json(['respuesta' => 'error', 'mensaje' => 'El Período no puede EXCEDER los 366 Días']);
                            }
                        }
                    }

                    $verificar = $this->verificarCodigoCoti($cotizacion['Serie'], $cotizacion['Numero'], $idSucursal);
                    if ($verificar->Cantidad > 0) {
                        $ultimoCorrelativo = $this->ultimoCorrelativoCoti($idUsuario, $idSucursal);
                        $sumarCorrelativo = intval($ultimoCorrelativo->Numero) + 1;
                        $numero = $this->completarCeros($sumarCorrelativo);
                    } else {
                        $numero = $cotizacion['Numero'];
                    }

                    $cotizacion = array_merge($cotizacion, ['IdSucursal' => $idSucursal, 'IdCreacion' => $idUsuario, 'Numero' => $numero]);

                    $valorCambioVentas = $req->valorCambioVentas;
                    $valorCambioCompras = $req->valorCambioCompras;
                    $banderaVentaSolesDolares = $req->banderaVentaSolesDolares;
                    if ($banderaVentaSolesDolares == 1) {
                        $ventaSolesDolares = 1;
                    } else {
                        $ventaSolesDolares = 0;
                    }
                    DB::beginTransaction();
                    try {
                        $nuevacotizacion = new DocCotizacion();
                        if ($cotizacion['TipoCotizacion'] == 1) {
                            $datos = collect($cotizacion)->only($nuevacotizacion->fillableComercial)->toArray();
                        } else {
                            $datos = collect($cotizacion)->only($nuevacotizacion->fillableVehicular)->toArray();
                        }
                        // $nuevacotizacion = new DocCotizacion(collect($cotizacion)->except('articulos')->toArray());
                        $nuevacotizacion->fill($datos);
                        $nuevacotizacion->save();

                        $idCotizacion = $nuevacotizacion->IdCotizacion;

                        if ($placaVehic != '') {
                            DB::table('vehiculo')
                                ->where('PlacaVehiculo', $req->placaVehicular)
                                ->update(["FechaSoat" => $req->vencSoat, "FechaRevTecnica" => $req->vencRevTecnica, "PeriodoMantenimientoKm" => $cotizacion['PeriodoProximoMantenimiento']]);
                        }
                        DB::table('registro_estados')->insert(['IdUsuario' => $idUsuario, 'Idsucursal' => $idSucursal, 'FechaRegistro' => now(), 'IdCotizacion' => $idCotizacion, 'IdEstadoCotizacion' => 1]);
                        $arrayIdsPaquetePromocional = [];
                        $articulos = $cotizacion['articulos'];
                        foreach ($articulos as $aticulolista) {
                            if (str_contains($aticulolista['Codigo'], 'PAQ')) {
                                array_push($arrayIdsPaquetePromocional, $aticulolista["IdPaquetePromocional"]);
                            }

                            $articulo = array_merge($aticulolista, ['IdCotizacion' => $idCotizacion]);
                            $detallecotizacionarticulo = new DetalleCotizacion_art($articulo);
                            $detallecotizacionarticulo->save();
                            usleep(200000);
                        }

                        if (count($arrayIdsPaquetePromocional) >= 1) {
                            $itemPaquete = $this->getItemsPaquetePromocionalStore($arrayIdsPaquetePromocional);

                            $igv = 1.18;
                            for ($i = 0; $i < count($itemPaquete); $i++) {
                                if ($itemPaquete[$i]->IdTipo == 2) {
                                    $textUnidad = 'ZZ';
                                    $tipo = 4;
                                } else {
                                    $textUnidad = $itemPaquete[$i]->TextUnidad;
                                    $tipo = 1;
                                }

                                // condicion si tipo de venta es 2 -> EXONERADO, quitamos el IGV al precio articulo
                                ($cotizacion['TipoVenta'] == 2) ? $precioArticulo = round(floatval($itemPaquete[$i]->Precio / $igv), 2) : $precioArticulo = round(floatval($itemPaquete[$i]->Precio), 2);

                                if ($req->checkCotizacionSolesConDolares == 1) {
                                    if ($cotizacion['IdTipoMoneda'] == 1 && $itemPaquete[$i]->IdTipoMoneda == 2) {
                                        $precioArticulo = floatval($precioArticulo) * floatval($valorCambioVentas);
                                    }
                                    if ($cotizacion['IdTipoMoneda'] == 2 && $itemPaquete[$i]->IdTipoMoneda == 1) {
                                        $precioArticulo = floatval($precioArticulo) / floatval($valorCambioCompras);
                                    }
                                }

                                $detallecotizacionarticulo = new DetalleCotizacion_artprom();
                                $detallecotizacionarticulo->IdCotizacion = $idCotizacion;
                                $detallecotizacionarticulo->IdCliente = $cotizacion['IdCliente'];
                                $detallecotizacionarticulo->IdArticulo = $itemPaquete[$i]->IdArticulo;
                                $detallecotizacionarticulo->Codigo = $itemPaquete[$i]->CodigoArticulo;
                                $detallecotizacionarticulo->VerificaTipo = $tipo;
                                $detallecotizacionarticulo->Cantidad = $itemPaquete[$i]->Cantidad;
                                $detallecotizacionarticulo->CantidadReal = intval($itemPaquete[$i]->Cantidad);
                                $detallecotizacionarticulo->PrecioUnidadReal = $precioArticulo;
                                $detallecotizacionarticulo->TextUnidad = $textUnidad;
                                $detallecotizacionarticulo->Importe = floatval($precioArticulo * $itemPaquete[$i]->Cantidad);
                                $detallecotizacionarticulo->IdPaquetePromocional = $itemPaquete[$i]->IdPaquetePromocional;
                                $detallecotizacionarticulo->save();
                                usleep(200000);
                            }
                        }
                        DB::commit();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        $idMaximo = DB::table('cotizacion_articulo')->SELECT(DB::RAW("MAX(IdCotizaArticulo) AS IdMaximo"))->first();
                        $idMaximo = $idMaximo->IdMaximo + 1;
                        DB::statement("ALTER TABLE cotizacion_articulo AUTO_INCREMENT=" . $idMaximo);

                        $idMaximoCoti = DB::table('cotizacion')->SELECT(DB::RAW("MAX(IdCotizacion) AS IdMaximo"))->first();
                        $idMaximoCoti = $idMaximoCoti->IdMaximo + 1;
                        DB::statement("ALTER TABLE cotizacion AUTO_INCREMENT=" . $idMaximoCoti);
                        return Response()->json(['respuesta' => 'error', 'mensaje' => 'Surgio un error, comunicarse con soporte']);
                    }
                    return Response()->json(['respuesta' => 'success', 'mensaje' => 'Se Genero Correctamente la Cotizacion', 'id' => $idCotizacion]);

                }
            } else {
                Session::flush();
                return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function verCotizacionGenerada(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $info = [];
        $idIconoWhatsapp = $id;
        if (strpos($id, "W-") === 0) {
            $id = substr($id, 2);
        }

        $cotizacionSelect = $this->getCotizacionselect($id);

        $tipoVenta = $cotizacionSelect->TipoVenta;

        if ($cotizacionSelect->IdOperario > 0) {
            $operarioSelect = $loadDatos->getOperarioSelect($cotizacionSelect->IdOperario);
            $operario = $operarioSelect->Nombres;
        } else {
            $operario = 'Genérico';

        }

        if ($cotizacionSelect->TipoCotizacion == 2) {
            $vehiculoSelect = $loadDatos->getVehiculoSelect($cotizacionSelect->Campo0);
            $color = $vehiculoSelect->Color;
            $anio = $vehiculoSelect->Anio;
            $fechaSoat = $vehiculoSelect->FechaSoat ? carbon::CreateFromFormat('Y-m-d', $vehiculoSelect->FechaSoat)->format('d-m-Y') : '';
            $fechaRevTec = $vehiculoSelect->FechaRevTecnica ? carbon::CreateFromFormat('Y-m-d', $vehiculoSelect->FechaRevTecnica)->format('d-m-Y') : '';

            $seguro = $vehiculoSelect->Seguro;
        } else {
            $color = '';
            $anio = '';
            $fechaSoat = '';
            $fechaRevTec = '';
            $seguro = '';
        }
        //if($cotizacionSelect->IdEstadoCotizacion == 4){
        $arrayComprobantes = DB::table('ventas')
            ->where('IdCotizacion', $cotizacionSelect->IdCotizacion)
            ->get();
        /*}else{
        $arrayComprobantes = [];
        }*/
        // dd($arrayComprobantes );
        $datosVe = DB::table('cotizacion as c')
            ->join('vehiculo as v', 'c.Campo0', '=', 'v.IdVehiculo')
            ->join('marca_general as ma', 'ma.IdMarcaGeneral', '=', 'v.IdMarcaVehiculo')
            ->join('modelo_general as mo', 'mo.IdModeloGeneral', '=', 'v.IdModeloVehiculo')
            ->join('seguros as s', 'v.IdSeguro', '=', 's.IdSeguro')
            ->where('IdCotizacion', $id)
            ->first();
        if ($datosVe) {
            $info = $datosVe;

        }

        $idUsuario = Session::get('idUsuario');
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $idSucursal = Session::get('idSucursal');

        $numeroCerosIzquierda = $this->completarCeros($cotizacionSelect->Numero);
        $fecha = date_create($cotizacionSelect->FechaCreacion);
        $formatoFecha = date_format($fecha, 'd-m-Y');
        $formatoHora = date_format($fecha, 'H:i A');
        $items = $this->getItemsCotizacion($id);
        $itemsPaquetePromocional = $this->getPaquetesPromoCotizacionGenerada($id);

        // dd($itemsPaquete);

        for ($i = 0; $i < count($items); $i++) {
            if ($items[$i]->IdMarca != null) {
                $_marca = DB::table('marca')
                    ->where('IdMarca', $items[$i]->IdMarca)
                    ->first();

                $items[$i]->Marca = $_marca->Nombre;
            } else {
                $items[$i]->Marca = null;
            }
        }
        // Nuevo codigo
        $numeroCelular = $cotizacionSelect->TelfCliente;
        if ($numeroCelular != null) {
            if (str_starts_with($numeroCelular, 9) === true) {
                $numeroCelular = $numeroCelular;
            }
        }
        // Fin
        $amortizaciones = $this->getAmortizacionCotizado($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        // dd($cotizacionSelect);
        $array = ['cotizacionSelect' => $cotizacionSelect, 'amortizaciones' => $amortizaciones, 'arrayComprobantes' => $arrayComprobantes, 'seguro' => $seguro, 'operario' => $operario, 'color' => $color, 'anio' => $anio, 'vehiculo' => $info, 'items' => $items, 'permisos' => $permisos, 'numeroCeroIzq' => $numeroCerosIzquierda, 'formatoFecha' => $formatoFecha, 'formatoHora' => $formatoHora, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'fechaSoat' => $fechaSoat, 'fechaRevTec' => $fechaRevTec, 'idIconoWhatsapp' => $idIconoWhatsapp, 'numeroCelular' => $numeroCelular, 'itemsPaquetePromocional' => $itemsPaquetePromocional, 'tipoVenta' => $tipoVenta, 'usuarioSelect' => $usuarioSelect];

        return view('operaciones/cotizacion/cotizacionGenerada', $array)->with('status', 'Se registro venta exitosamente');
    }

    // // NUEVA FUNCION DE PRUEBA ITEMS COTIZACION E PAQUETE PROMOCIONALES
    private function getPaquetesPromoCotizacionGenerada($idCotizacion)
    {
        try {
            $ventas = DB::table('cotizacion_articulo')
                ->join('paquetes_promocionales as pp', 'cotizacion_articulo.IdPaquetePromocional', '=', 'pp.IdPaquetePromocional')
                ->select('cotizacion_articulo.*', 'pp.IdPaquetePromocional', 'pp.NombrePaquete', 'pp.Etiqueta', 'pp.IdTipoMoneda', 'pp.Costo')
                ->where('cotizacion_articulo.IdCotizacion', $idCotizacion)
            // ->where(DB::raw('substr(cotizacion_articulo.Codigo, 1, 3)'), '=' , 'PAQ')
            // ->where('cotizacion_articulo.IdPaquetePromocional', '!=', 'Null')
                ->whereNotNull('cotizacion_articulo.IdPaquetePromocional')
                ->orderBy('pp.IdPaquetePromocional', 'asc')
                ->get();
            return $ventas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    // // FIn

    public function consultarCotizacion(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $cotizaciones = $this->getCotizacionAll($idSucursal);

            $countAbierto = $cotizaciones->where('IdEstadoCotizacion', 1)->count();
            $countEnProceso = $cotizaciones->where('IdEstadoCotizacion', 2)->count();
            $countFinalizado = $cotizaciones->where('IdEstadoCotizacion', 3)->count();
            $countCerrado = $cotizaciones->where('IdEstadoCotizacion', 4)->count();
            $countBaja = $cotizaciones->where('IdEstadoCotizacion', 6)->count();

            $idTipoAtencion = 5;
            $idEstadoCotizacion = 5;
            $tiposAtenciones = $loadDatos->getTiposAtenciones();
            if (count($cotizaciones) > 0) {
                foreach ($cotizaciones as $cotizacion) {
                    if ($cotizacion->TipoCotizacion == 2) {
                        $vehiculoSelect = $loadDatos->getVehiculoSelect($cotizacion->Campo0);
                        $cotizacion->PaquetePromocional = $this->getVerificarPaquetePromocional($cotizacion->IdCotizacion);
                        $cotizacion->Placa = $vehiculoSelect->PlacaVehiculo;
                        $cotizacion->Anio = $vehiculoSelect->Anio;
                        $cotizacion->Marca = $vehiculoSelect->NombreMarca;
                        $cotizacion->Modelo = $vehiculoSelect->NombreModelo;
                        $cotizacion->Seguro = $vehiculoSelect->Seguro;
                        $cotizacion->ChasisVehiculo = $vehiculoSelect->ChasisVehiculo;

                    } else {
                        $cotizacion->PaquetePromocional = $this->getVerificarPaquetePromocional($cotizacion->IdCotizacion);
                        $cotizacion->Placa = '';
                        $cotizacion->Anio = '';
                        $cotizacion->Marca = '';
                        $cotizacion->Modelo = '';
                        $cotizacion->Seguro = '';
                        $cotizacion->ChasisVehiculo = '';

                    }
                }
            }
            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $date = Carbon::today();
            $dateAtras = $date->subDays(7)->startOfDay()->format("Y-m-d H:i:s");
            $tipoPago = '';
            $fecha = '5';
            $fechaIni = '2000-12-12';
            $fechaFin = $loadDatos->getDateTime();
            $ini = '0';
            $fin = '0';
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $rol = $usuarioSelect->IdOperador;
            $estadosCotis = $loadDatos->getEstadosCotizacion();
            $cuentasSoles = $loadDatos->getCuentasCorrientes($usuarioSelect->CodigoCliente, 1);
            $cuentasDolares = $loadDatos->getCuentasCorrientes($usuarioSelect->CodigoCliente, 2);

            $array = ['cotizaciones' => $cotizaciones, 'estadosCotis' => $estadosCotis, 'idTipoAtencion' => $idTipoAtencion, 'idEstadoCotizacion' => $idEstadoCotizacion, 'permisos' => $permisos, 'IdTipoPago' => $tipoPago, 'rol' => $rol, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'dateAtras' => $dateAtras, 'modulosSelect' => $modulosSelect,
                'ini' => $ini, 'fin' => $fin, 'cuentasSoles' => $cuentasSoles, 'cuentasDolares' => $cuentasDolares, 'countAbierto' => $countAbierto, 'countEnProceso' => $countEnProceso, 'countFinalizado' => $countFinalizado, 'countCerrado' => $countCerrado, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'countBaja' => $countBaja, 'tiposAtenciones' => $tiposAtenciones];
            return view('operaciones/cotizacion/consultaCotizacion', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
        }
    }

    public function filtrarCotizacion(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idTipoAtencion = $req->tipoAtencion;
            $idEstadoCotizacion = $req->estadoCotizacion;

            if ($req->inputEstadoCotizacion != null) {
                $idEstadoCotizacion = $req->inputEstadoCotizacion;
            }

            $idSucursal = Session::get('idSucursal');
            $fecha = $req->fecha;
            $fechaIni = trim($req->fechaIni) == false ? '0' : $req->fechaIni;
            $fechaFin = trim($req->fechaFin) == false ? '0' : $req->fechaFin;
            if ($fecha == 9) {
                if ($fechaIni == null || $fechaFin == null) {
                    return back()->with('error', 'Completar las fechas para filtrar');
                }
                if (Carbon::createFromFormat('d/m/Y', $fechaIni) > Carbon::createFromFormat('d/m/Y', $fechaFin)) {
                    return back()->with('error', 'La fecha Inicial no puede ser mayor que la Final');
                }
            }
            //$this->filtradoCompletado($idTipoAtencion, $idSucursal, $idEstadoCotizacion, $fecha, $fechaIni, $fechaFin);
            $loadDatos = new DatosController();
            $idUsuario = Session::get('idUsuario');
            $tipoPago = "";
            $dateAtras = "";
            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);

            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $rol = $usuarioSelect->IdOperador;

            $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            $cotizaciones = $this->getCotizacionAllFiltrado($idTipoAtencion, $idSucursal, $idEstadoCotizacion, $fechas[0], $fechas[1]);

            if ($idEstadoCotizacion == 5) {
                $countAbierto = $cotizaciones->where('IdEstadoCotizacion', 1)->count();
                $countEnProceso = $cotizaciones->where('IdEstadoCotizacion', 2)->count();
                $countFinalizado = $cotizaciones->where('IdEstadoCotizacion', 3)->count();
                $countCerrado = $cotizaciones->where('IdEstadoCotizacion', 4)->count();
                $countBaja = $cotizaciones->where('IdEstadoCotizacion', 6)->count();
            } else {
                $countAbierto = 0;
                $countEnProceso = 0;
                $countFinalizado = 0;
                $countCerrado = 0;
                $countBaja = 0;
                $countEstados = $cotizaciones->where('IdEstadoCotizacion', $idEstadoCotizacion)->count();
                if ($idEstadoCotizacion == 1) {
                    $countAbierto = $countEstados;
                }
                if ($idEstadoCotizacion == 2) {
                    $countEnProceso = $countEstados;
                }
                if ($idEstadoCotizacion == 3) {
                    $countFinalizado = $countEstados;
                }
                if ($idEstadoCotizacion == 4) {
                    $countCerrado = $countEstados;
                }
                if ($idEstadoCotizacion == 6) {
                    $countBaja = $countEstados;
                }
            }

            $ini = str_replace('/', '-', $fechaIni);
            $fin = str_replace('/', '-', $fechaFin);

            $tiposAtenciones = $loadDatos->getTiposAtenciones();
            $estadosCotis = $loadDatos->getEstadosCotizacion();
            if (count($cotizaciones) > 0) {
                foreach ($cotizaciones as $cotizacion) {
                    if ($cotizacion->TipoCotizacion == 2) {
                        $vehiculoSelect = $loadDatos->getVehiculoSelect($cotizacion->Campo0);
                        $cotizacion->PaquetePromocional = $this->getVerificarPaquetePromocional($cotizacion->IdCotizacion);
                        $cotizacion->Placa = $vehiculoSelect->PlacaVehiculo;
                        $cotizacion->Anio = $vehiculoSelect->Anio;
                        $cotizacion->Marca = $vehiculoSelect->NombreMarca;
                        $cotizacion->Modelo = $vehiculoSelect->NombreModelo;
                        $cotizacion->Seguro = $vehiculoSelect->Seguro;
                        $cotizacion->ChasisVehiculo = $vehiculoSelect->ChasisVehiculo;
                    } else {
                        $cotizacion->PaquetePromocional = $this->getVerificarPaquetePromocional($cotizacion->IdCotizacion);
                        $cotizacion->Placa = '';
                        $cotizacion->Anio = '';
                        $cotizacion->Marca = '';
                        $cotizacion->Modelo = '';
                        $cotizacion->Seguro = '';
                        $cotizacion->ChasisVehiculo = '';

                    }
                }
            }

            $cuentasSoles = $loadDatos->getCuentasCorrientes($usuarioSelect->CodigoCliente, 1);
            $cuentasDolares = $loadDatos->getCuentasCorrientes($usuarioSelect->CodigoCliente, 2);

            $array = ['cotizaciones' => $cotizaciones, 'estadosCotis' => $estadosCotis, 'idTipoAtencion' => $idTipoAtencion, 'idEstadoCotizacion' => $idEstadoCotizacion, 'permisos' => $permisos, 'IdTipoPago' => $tipoPago, 'rol' => $rol, 'fecha' => $fecha, 'cuentasSoles' => $cuentasSoles, 'cuentasDolares' => $cuentasDolares, 'ini' => $ini, 'fin' => $fin,
                'countAbierto' => $countAbierto, 'countEnProceso' => $countEnProceso, 'countFinalizado' => $countFinalizado, 'countCerrado' => $countCerrado, 'countBaja' => $countBaja, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'dateAtras' => $dateAtras, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'tiposAtenciones' => $tiposAtenciones];
            return view('operaciones/cotizacion/consultaCotizacion', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function filtradoCompletado($idTipoAtencion, $idSucursal, $idEstadoCotizacion, $fecha, $fechaIni, $fechaFin)
    {
        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');
        $tipoPago = "";
        $dateAtras = "";
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $rol = $usuarioSelect->IdOperador;

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $cotizaciones = $this->getCotizacionAllFiltrado($idTipoAtencion, $idSucursal, $idEstadoCotizacion, $fechas[0], $fechas[1]);

        if ($idEstadoCotizacion == 5) {
            $countAbierto = $cotizaciones->where('IdEstadoCotizacion', 1)->count();
            $countEnProceso = $cotizaciones->where('IdEstadoCotizacion', 2)->count();
            $countFinalizado = $cotizaciones->where('IdEstadoCotizacion', 3)->count();
            $countCerrado = $cotizaciones->where('IdEstadoCotizacion', 4)->count();
        } else {
            $countAbierto = 0;
            $countEnProceso = 0;
            $countFinalizado = 0;
            $countCerrado = 0;
            $countEstados = $cotizaciones->where('IdEstadoCotizacion', $idEstadoCotizacion)->count();
            if ($idEstadoCotizacion == 1) {
                $countAbierto = $countEstados;
            }
            if ($idEstadoCotizacion == 2) {
                $countEnProceso = $countEstados;
            }
            if ($idEstadoCotizacion == 3) {
                $countFinalizado = $countEstados;
            }
            if ($idEstadoCotizacion == 4) {
                $countCerrado = $countEstados;
            }
        }

        $tiposAtenciones = $loadDatos->getTiposAtenciones();
        $estadosCotis = $loadDatos->getEstadosCotizacion();
        if (count($cotizaciones) > 0) {
            foreach ($cotizaciones as $cotizacion) {
                if ($cotizacion->TipoCotizacion == 2) {
                    $vehiculoSelect = $loadDatos->getVehiculoSelect($cotizacion->Campo0);
                    $cotizacion->Placa = $vehiculoSelect->PlacaVehiculo;
                    $cotizacion->Anio = $vehiculoSelect->Anio;
                    $cotizacion->Marca = $vehiculoSelect->NombreMarca;
                    $cotizacion->Modelo = $vehiculoSelect->NombreModelo;
                    $cotizacion->Seguro = $vehiculoSelect->Seguro;
                } else {
                    $cotizacion->Placa = '';
                    $cotizacion->Anio = '';
                    $cotizacion->Marca = '';
                    $cotizacion->Modelo = '';
                    $cotizacion->Seguro = '';
                }
            }
        }

        $cuentasSoles = $loadDatos->getCuentasCorrientes($usuarioSelect->CodigoCliente, 1);
        $cuentasDolares = $loadDatos->getCuentasCorrientes($usuarioSelect->CodigoCliente, 2);

        $array = ['cotizaciones' => $cotizaciones, 'estadosCotis' => $estadosCotis, 'idTipoAtencion' => $idTipoAtencion, 'idEstadoCotizacion' => $idEstadoCotizacion, 'permisos' => $permisos, 'IdTipoPago' => $tipoPago, 'rol' => $rol, 'fecha' => $fecha, 'cuentasSoles' => $cuentasSoles, 'cuentasDolares' => $cuentasDolares,
            'countAbierto' => $countAbierto, 'countEnProceso' => $countEnProceso, 'countFinalizado' => $countFinalizado, 'countCerrado' => $countCerrado, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin, 'dateAtras' => $dateAtras, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'tiposAtenciones' => $tiposAtenciones];
        return view('operaciones/cotizacion/consultaCotizacion', $array);
    }

    public function amortizar(Request $req)
    {
        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');
        $idSucursal = Session::get('idSucursal');
        $tipoPago = $req->tipoPago; // 1=>Pago efectivo; 2=>Deposito en tarjeta, 3=> Transferencia Bancaria
        $idCotizacion = $req->idCotizacion;
        $idTipoMoneda = $req->idTipoMoneda;
        $fecha = $loadDatos->getDateTime();
        $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
        if ($caja == null) {
            return back()->with('error', 'Abrir caja antes de realizar la Amortización');
        } else {

            $cotizacion = $this->getCotizacionselect($idCotizacion);
            $amortizaciones = $this->getAmortizaciones($idCotizacion);
            $maxAmortizacion = floatval($cotizacion->Total * 0.75);
            if ($tipoPago == 1) {
                $monto = $req->pagoEfectivo;
                $totalAmortizado = floatval($amortizaciones->AmortizacionTotal) + floatval($monto);
                if (floatval($totalAmortizado) <= floatval($maxAmortizacion)) {
                    if (floatval($monto) > 0) {
                        $array = ['IdSucursal' => $idSucursal, 'FechaIngreso' => $fecha, 'IdUsuario' => $idUsuario, 'FormaPago' => $tipoPago, 'IdTipoMoneda' => $idTipoMoneda, 'Monto' => $monto, 'IdCotizacion' => $idCotizacion, 'IdCaja' => $caja->IdCaja];
                        DB::table('amortizacion')->insert($array);

                        return redirect('/operaciones/cotizacion/consultar-cotizacion')->with('status', 'Se guardo amortización correctamente');
                    } else {
                        return redirect('/operaciones/cotizacion/consultar-cotizacion')->with('error', 'Por favor ingresar Monto');
                    }
                } else {
                    return redirect('/operaciones/cotizacion/consultar-cotizacion')->with('error', 'La amortización total debe ser como máximo el 75% del Importe Total');
                }
            }
            if ($tipoPago == 2) {
                $monto = $req->pagoTarjeta;
                $totalAmortizado = floatval($amortizaciones->AmortizacionTotal) + floatval($monto);
                if (floatval($totalAmortizado) <= floatval($maxAmortizacion)) {
                    if (floatval($monto) > 0) {
                        $numeroTarjeta = $req->numTarjeta;
                        $idTipoTarjeta = $req->tipoTarjeta;
                        $array = ['IdSucursal' => $idSucursal, 'FechaIngreso' => $fecha, 'IdUsuario' => $idUsuario, 'FormaPago' => $tipoPago, 'IdTipoMoneda' => $idTipoMoneda, 'IdTipoTarjeta' => $idTipoTarjeta, 'NumeroTarjeta' => $numeroTarjeta, 'Monto' => $monto, 'IdCotizacion' => $idCotizacion, 'IdCaja' => $caja->IdCaja];
                        DB::table('amortizacion')->insert($array);

                        return redirect('/operaciones/cotizacion/consultar-cotizacion')->with('status', 'Se guardo amortización correctamente');
                    } else {
                        return redirect('/operaciones/cotizacion/consultar-cotizacion')->with('error', 'Por favor ingresar Monto');
                    }
                } else {
                    return redirect('/operaciones/cotizacion/consultar-cotizacion')->with('error', 'La amortización total debe ser como máximo el 75% del Importe Total');
                }
            }
            if ($tipoPago == 3) {
                $cuentaBancaria = $req->cuentaBancaria;
                $monto = $req->montoCuenta;
                $totalAmortizado = floatval($amortizaciones->AmortizacionTotal) + floatval($monto);
                if (floatval($totalAmortizado) <= floatval($maxAmortizacion)) {
                    if ($cuentaBancaria > 0 && floatval($monto) > 0) {
                        $numeroOperacion = $req->nroOperacion;
                        $array = ['IdSucursal' => $idSucursal, 'FechaIngreso' => $fecha, 'IdUsuario' => $idUsuario, 'FormaPago' => $tipoPago, 'IdTipoMoneda' => $idTipoMoneda, 'CuentaBancaria' => $cuentaBancaria, 'Monto' => $monto, 'IdCotizacion' => $idCotizacion, 'IdCaja' => $caja->IdCaja];
                        DB::table('amortizacion')->insert($array);

                        $montoCuenta = $loadDatos->getCuentaCorrienteSelect($cuentaBancaria);
                        $montoActual = floatval($montoCuenta->MontoActual) + floatval($monto);
                        $arrayDatos = ['FechaPago' => $fecha, 'IdBanco' => $cuentaBancaria, 'NumeroOperacion' => $numeroOperacion, 'Detalle' => $req->detalleMovimientoCuenta, 'TipoMovimiento' => 'Cotización', 'Entrada' => $monto, 'Salida' => '0', 'MontoActual' => $montoActual, 'IdSucursal' => $idSucursal];
                        DB::table('banco_detalles')->insert($arrayDatos);

                        DB::table('banco')->where('IdBanco', $cuentaBancaria)->update(['MontoActual' => $montoActual]);

                        return redirect('/operaciones/cotizacion/consultar-cotizacion')->with('status', 'Se guardo amortización correctamente');
                    } else {
                        return redirect('/operaciones/cotizacion/consultar-cotizacion')->with('error', 'Seleccionar cuenta bancaria e ingresar Monto');
                    }
                } else {
                    return redirect('/operaciones/cotizacion/consultar-cotizacion')->with('error', 'La amortización total debe ser como máximo el 75% del Importe Total');
                }
            }
        }
    }

    public function editarCotizacion(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $permisos = $loadDatos->getPermisos($idUsuario);

            //LMCC   dd( listaproductostable);

            // $tipoMonedas = $loadDatos->getTipoMoneda();
            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            if ($modulosSelect->contains('IdModulo', 7)) {
                $moduloCronogramaActivo = 'activado';
            } else {
                $moduloCronogramaActivo = 'desactivado';
            }
            $tipoComprobante = $loadDatos->getTipoComprobante();
            $text = "";

            $cod_cliente = DB::table('sucursal')
                ->select('CodigoCliente')
                ->where('IdSucursal', $idSucursal)
                ->first();

            $sucPrincipal = DB::table('sucursal')
                ->select('IdSucursal')
                ->where('CodigoCliente', $cod_cliente->CodigoCliente)
                ->where('Principal', 1)
                ->first();

            /*echo $idSucursal."<br>";
            dd($cod_cliente);*/

            $cotizacionSelect = $this->getCotizacionselect($id);
            $editarPrecio = $usuarioSelect->EditarPrecio;
            $fecha = $cotizacionSelect->FechaCreacion;

            //dd($fecha);
            if ($sucPrincipal->IdSucursal == $idSucursal) {
                $productos = $loadDatos->getProductosPagination($idSucursal, $text, 1, 0);
                $productosDolares = $loadDatos->getProductosPagination($idSucursal, $text, 2, 0);
            } else {
                $productos = $loadDatos->paginarAjaxProdSucursal($idSucursal, $text, 1, 0);
                $productosDolares = $loadDatos->paginarAjaxProdSucursal($idSucursal, $text, 2, 0);
            }

            $servicios = $loadDatos->getServiciosPagination($idSucursal, 1, $text);
            $serviciosDolares = $loadDatos->getServiciosPagination($idSucursal, 2, $text);
            $items = $this->getItemsCotizacion($id);
            // nuevo codigo obtener items paquete promocional
            $listaPaquetePromo = $this->getPaquetesPromoCotizacionGenerada($id);
            $arrayIdsPaquetePromo = $listaPaquetePromo->pluck('IdPaquetePromocional');
            $listaItemsPaquetePromo = $this->getArticulosPaquetePromoCotizacionGenerada($id, $arrayIdsPaquetePromo);
            // fin
            $placa = '';

            $operarios = $loadDatos->getOperarios($idSucursal);
            if ($cotizacionSelect->TipoCotizacion == 1) {
                $clienteCotizacion = $cotizacionSelect->RazonSocial;
                $dataVehiculo = '';
                $fechaSoat = '';
                $fechaRevTec = '';
                $checkIn = null;
                $listaCheckList = '';

            } else {
                $dataVehiculo = $loadDatos->getVehiculoSelect($cotizacionSelect->Campo0);

                $clienteCotizacion = "$cotizacionSelect->RazonSocial --- Placa: $dataVehiculo->PlacaVehiculo";
                $checkIn = $this->getCheckInSelect($cotizacionSelect->IdCheckIn);
                $fechaSoat = $dataVehiculo->FechaSoat;
                $fechaRevTec = $dataVehiculo->FechaRevTecnica;
                $placa = $dataVehiculo->PlacaVehiculo;

                if ($cotizacionSelect->IdCheckIn == 0) {
                    $listaCheckList = $this->getCheckListPlacas($dataVehiculo->PlacaVehiculo, $cotizacionSelect->IdCliente);
                } else {
                    $listaCheckList = '';
                }
            }

            if ($cotizacionSelect->IdEstadoCotizacion == 2) {
                $deshabilidato = 'disabled';
            } else {
                $deshabilidato = '';
            }
            //dd($deshabilidato);
            $sucursal = $loadDatos->getSucursalSelect($idSucursal);
            $sucExonerado = $sucursal->Exonerado;
            $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
            $bandVentaSolesDolares = $datosEmpresa->VentaSolesDolares;
            $exonerado = $datosEmpresa->Exonerado;
            $tiposAtenciones = $loadDatos->getTiposAtenciones();
            //Nuevo codigo paquetes promocionales
            $paquetesPromocionalesSoles = $this->getPaquetesPromocionales($idSucursal, 1);
            $paquetesPromocionalesDolares = $this->getPaquetesPromocionales($idSucursal, 2);
            // FIN
            $categorias = $loadDatos->getCategorias($usuarioSelect->CodigoCliente);
            // $array = ['tipoComprobante' => $tipoComprobante, 'idSucursal' => $idSucursal, 'idCliente' => $idCliente, 'tipoMoneda' => $tipoMonedas, 'fecha' => $fecha, 'idTipoMoneda' => $cotizacionSelect->IdTipoMoneda, 'productos' => $productos, 'productosDolares' => $productosDolares, 'servicios' => $servicios, 'serviciosDolares' => $serviciosDolares, 'items' => $items, 'IdCotizacion' => $id, 'operarios' => $operarios, 'clientes' => $clientes, 'tipoVenta' => $cotizacionSelect->TipoVenta, 'fechaSoat' => $fechaSoat, 'fechaRevTec' => $fechaRevTec, 'editarPrecio' => $editarPrecio,
            //     'placa' => $placa, 'bandVentaSolesDolares' => $bandVentaSolesDolares, 'FechaFinal' => $cotizacionSelect->FechaFin, 'permisos' => $permisos, 'sucExonerado' => $sucExonerado, 'exonerado' => $exonerado, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'cotizacionSelect' => $cotizacionSelect, 'dataVehiculo' => $dataVehiculo, 'checkIn' => $checkIn, 'deshabilidato' => $deshabilidato, 'listaPaquetePromo' => $listaPaquetePromo, 'tiposAtenciones' => $tiposAtenciones, 'moduloCronogramaActivo' => $moduloCronogramaActivo, 'paquetesPromocionalesSoles' => $paquetesPromocionalesSoles, 'paquetesPromocionalesDolares' => $paquetesPromocionalesDolares, 'listaItemsPaquetePromo' => $listaItemsPaquetePromo, 'listaCheckList' => $listaCheckList, 'categorias' => $categorias, 'clienteCotizacion' => $clienteCotizacion];

            $array = ['usuarioSelect' => $usuarioSelect, 'tipoComprobante' => $tipoComprobante, 'idSucursal' => $idSucursal, 'fecha' => $fecha, 'idTipoMoneda' => $cotizacionSelect->IdTipoMoneda, 'productos' => $productos, 'productosDolares' => $productosDolares, 'servicios' => $servicios, 'serviciosDolares' => $serviciosDolares, 'items' => $items, 'IdCotizacion' => $id, 'operarios' => $operarios, 'tipoVenta' => $cotizacionSelect->TipoVenta, 'fechaSoat' => $fechaSoat, 'fechaRevTec' => $fechaRevTec, 'editarPrecio' => $editarPrecio, 'placa' => $placa, 'bandVentaSolesDolares' => $bandVentaSolesDolares, 'FechaFinal' => $cotizacionSelect->FechaFin, 'permisos' => $permisos, 'sucExonerado' => $sucExonerado, 'exonerado' => $exonerado, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'cotizacionSelect' => $cotizacionSelect, 'dataVehiculo' => $dataVehiculo, 'checkIn' => $checkIn, 'deshabilidato' => $deshabilidato, 'listaPaquetePromo' => $listaPaquetePromo, 'tiposAtenciones' => $tiposAtenciones, 'moduloCronogramaActivo' => $moduloCronogramaActivo, 'paquetesPromocionalesSoles' => $paquetesPromocionalesSoles, 'paquetesPromocionalesDolares' => $paquetesPromocionalesDolares, 'listaItemsPaquetePromo' => $listaItemsPaquetePromo, 'listaCheckList' => $listaCheckList, 'categorias' => $categorias, 'clienteCotizacion' => $clienteCotizacion];

            //dd($array);
            return view('operaciones/cotizacion/editarCotizacion', $array);

        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function duplicarCotizacion(Request $req, $id)
    {

        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $permisos = $loadDatos->getPermisos($idUsuario);

            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            if ($modulosSelect->contains('IdModulo', 7)) {
                $moduloCronogramaActivo = 'activado';
            } else {
                $moduloCronogramaActivo = 'desactivado';
            }
            $tipoComprobante = $loadDatos->getTipoComprobante();
            $text = "";

            $cod_cliente = DB::table('sucursal')
                ->select('CodigoCliente')
                ->where('IdSucursal', $idSucursal)
                ->first();

            $sucPrincipal = DB::table('sucursal')
                ->select('IdSucursal')
                ->where('CodigoCliente', $cod_cliente->CodigoCliente)
                ->where('Principal', 1)
                ->first();

            $cotizacionSelect = $this->getCotizacionselect($id);
            // obtener Tipo cambio Anterior
            $fechaAnteriorTipoCambio = Carbon::parse($cotizacionSelect->FechaCreacion)->format('Y-m-d');
            $fechaActualTipoCambio = Carbon::today()->toDateString();
            $tipoCambioAnterior = DB::table('tipo_cambio')->whereDate('FechaCreacion', $fechaAnteriorTipoCambio)->where('IdSucursal', $cotizacionSelect->IdSucursal)->first();
            $tipoCambioActual = DB::table('tipo_cambio')->whereDate('FechaCreacion', $fechaActualTipoCambio)->where('IdSucursal', $idSucursal)->first();

            $editarPrecio = $usuarioSelect->EditarPrecio;
            $fecha = date("d/m/Y");

            if ($sucPrincipal->IdSucursal == $idSucursal) {
                $productos = $loadDatos->getProductosPagination($idSucursal, $text, 1, 0);
                $productosDolares = $loadDatos->getProductosPagination($idSucursal, $text, 2, 0);
            } else {
                $productos = $loadDatos->paginarAjaxProdSucursal($idSucursal, $text, 1, 0);
                $productosDolares = $loadDatos->paginarAjaxProdSucursal($idSucursal, $text, 2, 0);
            }

            $servicios = $loadDatos->getServiciosPagination($idSucursal, 1, $text);
            $serviciosDolares = $loadDatos->getServiciosPagination($idSucursal, 2, $text);
            $items = $this->getItemsCotizacion($id);
            // nuevo codigo obtener items paquete promocional
            $listaPaquetePromo = $this->getPaquetesPromoCotizacionGenerada($id);
            $arrayIdsPaquetePromo = $listaPaquetePromo->pluck('IdPaquetePromocional');
            $listaItemsPaquetePromo = $this->getArticulosPaquetePromoCotizacionGenerada($id, $arrayIdsPaquetePromo);
            // fin

            $listaGruposSoles = $this->getListaGrupos($idSucursal, 1);
            $listaGruposDolares = $this->getListaGrupos($idSucursal, 2);
            // PAQUETES MISCELANEOS
            $paquetesPromocionalesSoles = $this->getPaquetesPromocionales($idSucursal, 1);
            $paquetesPromocionalesDolares = $this->getPaquetesPromocionales($idSucursal, 2);

            $placa = '';

            $operarios = $loadDatos->getOperarios($idSucursal);
            if ($cotizacionSelect->TipoCotizacion == 1) {
                $clienteCotizacion = $cotizacionSelect->RazonSocial;
                $dataVehiculo = '';
                $fechaSoat = '';
                $fechaRevTec = '';
                $checkIn = null;
                $listaCheckList = '';

            } else {
                $dataVehiculo = $loadDatos->getVehiculoSelect($cotizacionSelect->Campo0);

                $clienteCotizacion = "$cotizacionSelect->RazonSocial --- Placa: $dataVehiculo->PlacaVehiculo";
                $checkIn = $this->getCheckInSelect($cotizacionSelect->IdCheckIn);
                $fechaSoat = $dataVehiculo->FechaSoat;
                $fechaRevTec = $dataVehiculo->FechaRevTecnica;
                $placa = $dataVehiculo->PlacaVehiculo;

                if ($cotizacionSelect->IdCheckIn == 0) {
                    $listaCheckList = $this->getCheckListPlacas($dataVehiculo->PlacaVehiculo, $cotizacionSelect->IdCliente);
                } else {
                    $listaCheckList = '';
                }
                $listaCheckList = $this->getCheckListPlacas($dataVehiculo->PlacaVehiculo, $cotizacionSelect->IdCliente);
            }

            if ($cotizacionSelect->IdEstadoCotizacion == 2) {
                $deshabilidato = 'disabled';
            } else {
                $deshabilidato = '';
            }
            //dd($deshabilidato);
            $sucursal = $loadDatos->getSucursalSelect($idSucursal);
            $sucExonerado = $sucursal->Exonerado;
            $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
            $bandVentaSolesDolares = $datosEmpresa->VentaSolesDolares;
            $exonerado = $datosEmpresa->Exonerado;
            $tiposAtenciones = $loadDatos->getTiposAtenciones();
            //Nuevo codigo paquetes promocionales
            $paquetesPromocionalesSoles = $this->getPaquetesPromocionales($idSucursal, 1);
            $paquetesPromocionalesDolares = $this->getPaquetesPromocionales($idSucursal, 2);
            // FIN
            $categorias = $loadDatos->getCategorias($usuarioSelect->CodigoCliente);

            // generar serie y numero
            $numeroDB = DB::table('cotizacion')
                ->select('Numero')
                ->where('IdCreacion', $idUsuario)
                ->where('IdSucursal', $idSucursal)
                ->orderBy('IdCotizacion', 'desc')
                ->first();

            if ($numeroDB) {
                $numero = str_pad($numeroDB->Numero + 1, 8, "0", STR_PAD_LEFT);
            } else {
                $numero = str_pad(1, 8, "0", STR_PAD_LEFT);
            }

            $orden = $usuarioSelect->Orden;
            $ordenSucursal = $sucursal->Orden;
            $serieCeros = str_pad($orden, 2, "0", STR_PAD_LEFT);
            $serie = 'C' . $ordenSucursal . '' . $serieCeros;

            if ($cotizacionSelect->TipoCotizacion == 1) {
                $clientes = $loadDatos->getClientes($idSucursal);
                $idCliente = $cotizacionSelect->IdCliente;

            } else {
                $clientes = $this->getVehiculos($idSucursal);
                $idCliente = $cotizacionSelect->Campo0;
            }

            $array = ['usuarioSelect' => $usuarioSelect, 'tipoComprobante' => $tipoComprobante, 'idSucursal' => $idSucursal, 'fecha' => $fecha, 'idTipoMoneda' => $cotizacionSelect->IdTipoMoneda, 'productos' => $productos, 'productosDolares' => $productosDolares, 'servicios' => $servicios, 'serviciosDolares' => $serviciosDolares, 'items' => $items, 'IdCotizacion' => $id, 'operarios' => $operarios, 'tipoVenta' => $cotizacionSelect->TipoVenta, 'fechaSoat' => $fechaSoat, 'fechaRevTec' => $fechaRevTec, 'editarPrecio' => $editarPrecio, 'placa' => $placa, 'bandVentaSolesDolares' => $bandVentaSolesDolares, 'FechaFinal' => $cotizacionSelect->FechaFin, 'permisos' => $permisos, 'sucExonerado' => $sucExonerado, 'exonerado' => $exonerado, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'cotizacionSelect' => $cotizacionSelect, 'dataVehiculo' => $dataVehiculo, 'checkIn' => $checkIn, 'deshabilidato' => $deshabilidato, 'listaPaquetePromo' => $listaPaquetePromo, 'tiposAtenciones' => $tiposAtenciones, 'moduloCronogramaActivo' => $moduloCronogramaActivo, 'paquetesPromocionalesSoles' => $paquetesPromocionalesSoles, 'paquetesPromocionalesDolares' => $paquetesPromocionalesDolares, 'listaItemsPaquetePromo' => $listaItemsPaquetePromo, 'listaCheckList' => $listaCheckList, 'categorias' => $categorias, 'clienteCotizacion' => $clienteCotizacion, 'Numero' => $numero, 'Serie' => $serie, 'clientes' => $clientes, 'idCliente' => $idCliente,

                'listaGruposSoles' => $listaGruposSoles, 'listaGruposDolares' => $listaGruposDolares, 'paquetesPromocionalesSoles' => $paquetesPromocionalesSoles, 'paquetesPromocionalesDolares' => $paquetesPromocionalesDolares, 'tipoCambioAnterior' => $tipoCambioAnterior, 'tipoCambioActual' => $tipoCambioActual];

            //dd($array);
            return view('operaciones.cotizacion.duplicarCotizacion', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

    }

    // NUEVAS FUNCIONES DEL PROCESO DE ACTUALIZAR KARDEX

    private function agruparArticulosParaKardex($articulo, $idTipoMovimiento, $documentoMovimiento, $cantidadArticulo, $estadoMovimiento)
    {
        $loadDatos = new DatosController();

        $productoSelect = $loadDatos->getProductoSelect($articulo['IdArticulo']);
        if ($estadoMovimiento == 'Reposicion') {
            $stockFinal = floatval($productoSelect->Stock) + floatval($cantidadArticulo);
        } else {
            $stockFinal = floatval($productoSelect->Stock) - floatval($cantidadArticulo);
        }
        $kardex = array(
            'CodigoInterno' => $productoSelect->CodigoInterno,
            'fecha_movimiento' => now(),
            'tipo_movimiento' => $idTipoMovimiento,
            'usuario_movimiento' => Session::get('idUsuario'),
            'documento_movimiento' => $documentoMovimiento,
            'existencia' => $stockFinal,
            'costo' => $productoSelect->Precio,
            'IdArticulo' => $articulo['IdArticulo'],
            'IdSucursal' => Session::get('idSucursal'),
            'Cantidad' => $cantidadArticulo,
            'Descuento' => $articulo['Descuento'],
            'ImporteEntrada' => 0,
            'ImporteSalida' => $articulo['Importe'],
            'estado' => 1,
        );
        return $kardex;
    }

    private function incrementarStockProducto($articulo, $cantidad)
    {
        $loadDatos = new DatosController();
        // Actualizar Stock de la tabla Articulo
        DB::table('articulo')
            ->where('IdArticulo', $articulo['IdArticulo'])
            ->increment('Stock', $cantidad);

        // Actualizar Stock de la tabla Stock
        $stockSelect = $loadDatos->getProductoStockSelect($articulo['IdArticulo']);
        DB::table('stock')
            ->where('IdStock', $stockSelect[0]->IdStock)
            ->increment('Cantidad', $cantidad);
    }

    private function decrementarStockArticulo($articulo, $cantidad)
    {
        // Actualizar Stock de la tabla Stock
        $this->actualizarStock($articulo['IdArticulo'], substr($articulo['Codigo'], 0, 3), $cantidad);
        // Actualizar Stock de la tabla Articulo
        DB::table('articulo')
            ->where('IdArticulo', $articulo['IdArticulo'])
            ->decrement('Stock', $cantidad);
    }

    public function actualizarCotizacion(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                if ($req->ajax()) {
                    // Inicia la transaccion
                    DB::beginTransaction();
                    try {
                        $loadDatos = new DatosController();
                        $cotizacion = $req->cotizacion;
                        $articulos = $cotizacion['articulos'];
                        $articulosAnteriores = collect($req->articulosAnteriores);
                        $todosLosRegistrosKardex = [];

                        $articulosParaEliminar = $req->articulosParaEliminar;
                        $articulosParaInsertar = $req->articulosParaInsertar;
                        $articulosParaKardex = $req->articulosParaKardex;

                        // Actualizar datos de la cotizacion
                        if ($cotizacion['TipoCotizacion'] == 1) {
                            $camposEditables = ['Observacion' => $cotizacion['Observacion'], 'SubTotal' => $cotizacion['SubTotal'], 'Exonerada' => $cotizacion['Exonerada'], 'Igv' => $cotizacion['Igv'], 'Total' => $cotizacion['Total'], 'FechaActualizacion' => now()];

                        }
                        if ($cotizacion['TipoCotizacion'] == 2) {
                            $camposEditables = ['IdOperario' => $cotizacion['IdOperario'], 'Campo1' => $cotizacion['Campo1'], 'Campo2' => $cotizacion['Campo2'], 'Trabajos' => $cotizacion['Trabajos'], 'Observacion' => $cotizacion['Observacion'], 'SubTotal' => $cotizacion['SubTotal'], 'Exonerada' => $cotizacion['Exonerada'], 'Igv' => $cotizacion['Igv'], 'Total' => $cotizacion['Total'], 'IdCheckIn' => $cotizacion['IdCheckIn'], 'FechaActualizacion' => now()];
                        }

                        DocCotizacion::where('IdCotizacion', $req->idCotizacion)->update($camposEditables);

                        if ($cotizacion['IdEstadoCotizacion'] == 1) {
                            if ($articulosParaEliminar !== null) {
                                $codigoArticulos = array_column($articulosParaEliminar, 'Codigo');
                                DetalleCotizacion_art::where('IdCotizacion', $req->idCotizacion)->whereIn('Codigo', $codigoArticulos)->delete();
                            }

                            if ($articulosParaInsertar !== null) {
                                DetalleCotizacion_art::insert($articulosParaInsertar);
                            }
                        }

                        // ======================================================
                        if ($cotizacion['IdEstadoCotizacion'] == 2 || $cotizacion['IdEstadoCotizacion'] == 3) {

                            if ($articulosParaEliminar !== null) {
                                $codigoArticulos = array_column($articulosParaEliminar, 'Codigo');
                                DetalleCotizacion_art::where('IdCotizacion', $req->idCotizacion)->whereIn('Codigo', $codigoArticulos)->delete();
                            }

                            if ($articulosParaInsertar !== null) {
                                DetalleCotizacion_art::insert($articulosParaInsertar);
                            }

                            if ($articulosParaKardex !== null) {
                                foreach ($articulosParaKardex as $articulo) {
                                    if ($articulo['EstadoEditar'] === 'Modificado') {
                                        $articuloAnteriorEncontrado = $articulosAnteriores->where('IdArticulo', $articulo['IdArticulo'])->first();
                                        if ($articuloAnteriorEncontrado) {

                                            if ($articulo['VerificaTipo'] == 1) {
                                                // Descuento Stock
                                                if (floatval($articulo['Cantidad']) > floatval($articuloAnteriorEncontrado['Cantidad'])) {
                                                    $cantidadDescuento = $articulo['Cantidad'] - floatval($articuloAnteriorEncontrado['Cantidad']);
                                                    // Crear kardex
                                                    $registroKardex = $this->agruparArticulosParaKardex($articulo, $idTipoMovimiento = 12, $documentoMovimiento = "En Proceso - Descuento : {$cotizacion['Serie']} - {$cotizacion['Numero']}", $cantidadDescuento, $estadoMovimiento = 'Descuento');
                                                    array_push($todosLosRegistrosKardex, $registroKardex);

                                                    // Decrementar Stock
                                                    $this->decrementarStockArticulo($articulo, $cantidadDescuento);
                                                }

                                                // Reposicion de Stock
                                                if (floatval($articulo['Cantidad']) < floatval($articuloAnteriorEncontrado['Cantidad'])) {
                                                    $cantidadReposicion = floatval($articuloAnteriorEncontrado['Cantidad'] - $articulo['Cantidad']);
                                                    // Crear kardex
                                                    $registroKardex = $this->agruparArticulosParaKardex($articulo, $idTipoMovimiento = 13, $documentoMovimiento = "En Proceso - Reposicion : {$cotizacion['Serie']} - {$cotizacion['Numero']}", $cantidadReposicion, $estadoMovimiento = 'Reposicion');
                                                    array_push($todosLosRegistrosKardex, $registroKardex);

                                                    // Incrementar Stock
                                                    $this->incrementarStockProducto($articulo, $cantidadReposicion);
                                                }
                                            }
                                        }
                                    }

                                    if ($articulo['EstadoEditar'] === 'Nuevo') {
                                        if ($articulo['VerificaTipo'] == 1) {
                                            // Crear kardex
                                            $registroKardex = $this->agruparArticulosParaKardex($articulo, $idTipoMovimiento = 12, $documentoMovimiento = "En Proceso - Descuento : {$cotizacion['Serie']} - {$cotizacion['Numero']}", $articulo['Cantidad'], $estadoMovimiento = 'Descuento');
                                            array_push($todosLosRegistrosKardex, $registroKardex);

                                            // Decrementar Stock
                                            $this->decrementarStockArticulo($articulo, $articulo['Cantidad']);
                                        }

                                    }

                                    if ($articulo['EstadoEditar'] === 'Eliminado') {
                                        if ($articulo['VerificaTipo'] == 1) {
                                            // Crear kardex
                                            $registroKardex = $this->agruparArticulosParaKardex($articulo, $idTipoMovimiento = 13, $documentoMovimiento = "En Proceso - Reposicion : {$cotizacion['Serie']} - {$cotizacion['Numero']}", $articulo['Cantidad'], $estadoMovimiento = 'Reposicion');
                                            array_push($todosLosRegistrosKardex, $registroKardex);

                                            // Incrementar Stock
                                            $this->incrementarStockProducto($articulo, $articulo['Cantidad']);
                                        }
                                    }
                                }
                                // Insertar kardex
                                if (count($todosLosRegistrosKardex) >= 1) {
                                    DB::table('kardex')->insert($todosLosRegistrosKardex);
                                }
                            }

                        }

                        // ======================================================

                        if ($cotizacion['TipoCotizacion'] == 2) {
                            // Actualizar vehículo
                            DB::table('vehiculo')
                                ->where('PlacaVehiculo', $req->placa)
                                ->where('IdSucursal', Session::get('idSucursal'))
                                ->update(["NumeroFlota" => $req->flota, "FechaSoat" => $req->vencSoat, "FechaRevTecnica" => $req->vencRevTecnica, 'FechaActualizacion' => now()]);

                        }
                        // Fin de la transaction si todas las operaciones son exitosas
                        DB::commit();
                        // Se envia el mensaje de exito
                        return Response(['respuesta' => 'success', 'mensaje' => 'Actualizacion satisfactorio', 'id' => $req->idCotizacion]);
                    } catch (\Exception $e) {
                        // Si ocurre algún error en las consultas, revertir la transacción
                        DB::rollBack();
                        // Manejar la excepción
                        return response()->json(['respuesta' => 'errorTransaccion', 'mensaje' => 'Surgio un error, por favor comunicarse con el área de soporte']);
                    }
                }
            }
        } catch (\Exception $e) {
            echo $ex->getMessage();
        }
    }

    // public function actualizarCotizacion(Request $req)
    // {
    //     try {
    //         if ($req->session()->has('idUsuario')) {
    //             if ($req->ajax()) {
    //                 $idUsuario = Session::get('idUsuario');
    //                 $idOperario = '';
    //                 $trabajos = '';
    //                 $opc = 1;
    //                 if ($req->tipoCoti == 2) {
    //                     $opc = 2;
    //                     $cliVehiculo = DB::table('vehiculo')
    //                         ->where('IdVehiculo', $req->cliente)
    //                         ->first();
    //                     $idCliente = $cliVehiculo->IdCliente;
    //                     $campo0 = $req->cliente;
    //                     $idOperario = $req->operario;
    //                     $trabajos = $req->trabajos;
    //                     $placaVehiculo = $cliVehiculo->PlacaVehiculo;

    //                     // Codigo para validarel tipo de atencion
    //                     if ($req->atencion == 1 || $req->atencion == 2 || $req->atencion == 6) {
    //                         if ($req->kilometro == '') {
    //                             return Response(['error', 'Por favor, Ingrese el Kilometraje']);
    //                         }
    //                     }
    //                     // Fin

    //                 } else {
    //                     $idCliente = $req->cliente;
    //                     $campo0 = null;
    //                     $placaVehiculo = '';
    //                 }

    //                 //LMCC

    //                 // $listaitemscoizacion_act=$req->listaproductostable; listaproductostable
    //                 //   dd($listaitemscoizacion_act);

    //                 if ($req->moduloCronogramaActivo == "activado") {
    //                     $atencion = $req->atencion;
    //                     if ($atencion == 1 || $atencion == 6) {
    //                         if ($req->mantenimientoActual == null) {
    //                             return Response(['alert1', 'Ingrese el Mantenimiento Actual']);
    //                         }
    //                         if ($req->proximoMantenimiento == null) {
    //                             return Response(['alert2', 'Ingrese el proximo Mantenimiento']);
    //                         }
    //                         if ($req->periodoProximoMantenimiento == null) {
    //                             return Response(['alert3', 'Ingrese el Período para el próximo Mantenimiento']);
    //                         }
    //                         if ($req->periodoProximoMantenimiento == 0) {
    //                             return Response(['alert3', 'El Período para el próximo Mantenimiento debe ser mayor a CERO']);
    //                         }
    //                         if ($req->periodoProximoMantenimiento > 366) {
    //                             return Response(['alert1', 'El Período no puede EXCEDER los 366 Días']);
    //                         }
    //                         $RegExpInputProximoMantenimiento = '/[^0-9]/';
    //                         $proximoMantenimiento = preg_replace($RegExpInputProximoMantenimiento, "", $req->proximoMantenimiento);
    //                         $req->proximoMantenimiento = number_format($proximoMantenimiento, 0, ',', ' ') . " " . 'Km';
    //                     }
    //                 }

    //                 if ($idCliente == 0) {
    //                     return Response(['alert4', 'Por favor, elegir Cliente']);
    //                     //return back()->with('error','Por favor, elegir Cliente')->withInput($req->all());
    //                 }
    //                 if ($req->Id == null) {
    //                     return Response(['alert5', 'Por favor, agrege productos o servicios']);
    //                     //return back()->with('error','Por favor, agrege productos o servicios')->withInput($req->all());
    //                 }

    //                 $valorCambioVentas = $req->valorCambioVentas;
    //                 $valorCambioCompras = $req->valorCambioCompras;
    //                 $banderaVentaSolesDolares = $req->banderaVentaSolesDolares;
    //                 if ($banderaVentaSolesDolares == 1) {
    //                     $ventaSolesDolares = 1;
    //                 } else {
    //                     $ventaSolesDolares = 0;
    //                 }
    //                 $idTipoMoneda = $req->TipoMoneda;

    //                 $total = $req->total;
    //                 $idCotizacion = $req->idCotizacion;
    //                 $idSucursal = Session::get('idSucursal');
    //                 $subtotal = $req->subtotal;
    //                 $exonerada = $req->exonerada;
    //                 $observacion = $req->observacion;
    //                 if ($exonerada == '-') {
    //                     $exonerada = '0.00';
    //                 }
    //                 $tipoVenta = $req->tipoVenta;
    //                 if ($tipoVenta == 1) {
    //                     $subtotal = $req->subtotal;
    //                 } else {
    //                     $subtotal = $req->opExonerado;
    //                 }
    //                 $igv = $req->igv;
    //                 $idEstadoCotizacion = $req->idEstadoCotizacion;
    //                 $loadDatos = new DatosController();
    //                 $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
    //                 if ($caja == null) {
    //                     return Response(['alert9', 'Abrir Caja antes de realizar una venta']);
    //                 } else {
    //                     $stockSuficiente[0] = 1; //$this->verificarStockSuficiente($req);

    //                     if ($stockSuficiente[0] == 1) {
    //                         //$bandera = 1;
    //                         $exonerada = $req->exonerada; //esto se puso aqui, por  que descuento =  exoneracion , y se  nesecita  guardar el total del descuento
    //                         if ($exonerada == '-') {
    //                             $exonerada = '0.00';
    //                         }
    //                         $array = ['IdCliente' => $idCliente, 'IdOperario' => $idOperario, 'Campo0' => $campo0, 'Campo1' => $req->kilometro,
    //                             'Campo2' => $req->horometro, 'Trabajos' => $trabajos, 'Observacion' => $observacion, 'Subtotal' => $subtotal, 'Exonerada' => $exonerada, 'IGV' => $igv, 'Total' => $total, 'Estado' => 1, 'MantenimientoActual' => $req->mantenimientoActual, 'ProximoMantenimiento' => $req->proximoMantenimiento, 'PeriodoProximoMantenimiento' => $req->periodoProximoMantenimiento];

    //                         if ($placaVehiculo != '') {
    //                             /*DB::table('vehiculo')
    //                             ->where('PlacaVehiculo', $placaVehiculo)
    //                             ->where('IdSucursal', $idSucursal)
    //                             ->update(["NumeroFlota" => $req->flota, "FechaSoat" => $req->vencSoat, "FechaRevTecnica" => $req->vencRevTecnica, "PeriodoMantenimientoKm" => $req->periodoProximoMantenimiento]);*/
    //                             /*****************LMCC 200823  ******************/
    //                             $Datosvehiculo = MasterVehiculo::select("*")->where("PlacaVehiculo", $placaVehiculo)->where("IdSucursal", $idSucursal)->first();
    //                             if (!is_null($Datosvehiculo)) {
    //                                 $Datosvehiculo->NumeroFlota = $req->flota;
    //                                 $Datosvehiculo->FechaSoat = $req->vencSoat;
    //                                 $Datosvehiculo->FechaRevTecnica = $req->vencRevTecnica;
    //                                 $Datosvehiculo->PeriodoMantenimientoKm = $req->periodoProximoMantenimiento;
    //                                 $Datosvehiculo->save();
    //                             }
    //                             /*****************fin cambios  ******************/

    //                         }

    //                         /* DB::table('cotizacion')
    //                         ->where('IdCotizacion', $idCotizacion)
    //                         ->update($array);*/

    //                         /*****************LMCC 200823  ******************/
    //                         $DatosCotizacion = DocCotizacion::select("*")->where("IdCotizacion", $idCotizacion)->first();
    //                         if (!is_null($DatosCotizacion)) {
    //                             $DatosCotizacion->IdCliente = $idCliente;
    //                             $DatosCotizacion->IdOperario = $idOperario;
    //                             $DatosCotizacion->Campo0 = $campo0;
    //                             $DatosCotizacion->Campo1 = $req->kilometro;
    //                             $DatosCotizacion->Campo2 = $req->horometro;
    //                             $DatosCotizacion->Trabajos = $trabajos;
    //                             $DatosCotizacion->Observacion = $observacion;
    //                             $DatosCotizacion->Subtotal = $subtotal;
    //                             $DatosCotizacion->Exonerada = $exonerada;
    //                             $DatosCotizacion->IGV = $igv;
    //                             $DatosCotizacion->Total = $total;
    //                             $DatosCotizacion->Estado = 1;
    //                             $DatosCotizacion->MantenimientoActual = $req->mantenimientoActual;
    //                             $DatosCotizacion->ProximoMantenimiento = $req->proximoMantenimiento;
    //                             $DatosCotizacion->PeriodoProximoMantenimiento = $req->periodoProximoMantenimiento;
    //                             $DatosCotizacion->save();
    //                         }
    //                         //  'ProximoMantenimiento' => $req->proximoMantenimiento, 'PeriodoProximoMantenimiento' => $req->periodoProximoMantenimiento];
    //                         /***************fin cambios 200823***************/

    //                         // Nuevas variables Paquetes Promocionales, array de nuevos paquetes y paquetes quitados
    //                         $arrayIdsNuevosPaquetesPromo = $req->arrayIdsNuevosPaquetesPromo;
    //                         $arrayIdsEliminadosPaquetesPromo = $req->arrayIdsEliminadosPaquetesPromo;
    //                         $arrayIdsAntiguosPaquetesPromo = $req->arrayIdsAntiguosPaquetesPromo;

    //                         // Fin
    //                         if ($idEstadoCotizacion == 1 || $idEstadoCotizacion == 5) {
    //                             DB::beginTransaction();
    //                             try {
    //                                 /* DB::table('cotizacion_articulo')
    //                                 ->where('IdCotizacion', $idCotizacion)
    //                                 ->delete();*/

    //                                 /********************LMCC 110923*****************/
    //                                 //DetalleCotizacion_art::where("IdCotizacion",$idCotizacion)->delete();
    //                                 $cantidadVentaReal = 1;
    //                                 $bandTipo = 0;
    //                                 $bandGan = 0;
    //                                 $listaitemscoizacion_act = $req->listaproductostable;
    //                                 //dd($listaitemscoizacion_act);
    //                                 foreach ($listaitemscoizacion_act as $itemlista) {

    //                                     $producto = substr($itemlista["codigo"], 0, 3);
    //                                     $productoSelect = $loadDatos->getProductoSelect($itemlista["idarticulo"]);
    //                                     if ($producto == 'PRO') {
    //                                         if ($itemlista["tipo"] == 1) {
    //                                             $precio = floatval($itemlista["precio"]);
    //                                             $costo = floatval($productoSelect->Costo);
    //                                             if ($productoSelect->TipoOperacion == 2) {
    //                                                 $costo = floatval($costo / 1.18);
    //                                             }
    //                                             if ($ventaSolesDolares == 1) {
    //                                                 if ($idTipoMoneda == 1 && $productoSelect->IdTipoMoneda == 2) {
    //                                                     if (floatval($productoSelect->ValorTipoCambio) > 0) {
    //                                                         $costo = floatval($costo * $productoSelect->ValorTipoCambio);
    //                                                     } else {
    //                                                         $costo = floatval($costo * $valorCambioVentas);
    //                                                     }
    //                                                 }
    //                                                 if ($idTipoMoneda == 2 && $productoSelect->IdTipoMoneda == 1) {
    //                                                     $costo = floatval($costo / $valorCambioCompras);
    //                                                 }
    //                                             }
    //                                             $bandGan = $precio - $costo;
    //                                             $newGanancia = floatval($bandGan * $itemlista["cantidad"]) - floatval($itemlista["descuento"]);
    //                                             $bandTipo = 1;

    //                                         } else {
    //                                             if ($itemlista["tipo"] == 3) {
    //                                                 //$newCantidad=$req->Cantidad[$i];
    //                                                 //$cantidadRestada = (int) $productoSelect->Stock - ((int) $req->Cantidad[$i] * 1);
    //                                                 $cantidadVentaReal = $itemlista["cantidad"] * 1;
    //                                                 $newGanancia = $itemlista["importe"] - (($productoSelect->Costo * (1 * $itemlista["cantidad"])) - $itemlista["descuento"]);
    //                                                 $bandTipo = 3;
    //                                             } else if ($itemlista->tipo == 2) {
    //                                                 //$newCantidad=$req->Cantidad[$i]*$productoSelect->CantidadTipo;

    //                                                 //$cantidadRestada = (int) $productoSelect->Stock - ((int) $req->Cantidad[$i] * (int) $productoSelect->CantidadTipo);
    //                                                 $cantidadVentaReal = $itemlista["cantidad"] * $productoSelect->CantidadTipo;
    //                                                 $newGanancia = $itemlista["importe"] - (($productoSelect->Costo * ($productoSelect->CantidadTipo * $itemlista["cantidad"])) - $itemlista["descuento"]);
    //                                                 $bandTipo = 2;
    //                                             }
    //                                         }

    //                                     } else {
    //                                         // Nuevo codigo para traer los datos paquete promocional y guardarlo como un items de la cotizacion
    //                                         if ($producto == 'PAQ') {
    //                                             $productoSelect = $this->getDatosPaquetesPromocionales($itemlista["idarticulo"], $idSucursal);
    //                                         }
    //                                         // Fin

    //                                         $precio = floatval($itemlista["precio"]);
    //                                         $costo = floatval($productoSelect->Costo);
    //                                         if ($ventaSolesDolares == 1) {
    //                                             if ($idTipoMoneda == 1 && $productoSelect->IdTipoMoneda == 2) {
    //                                                 $costo = floatval($costo * $valorCambioVentas);
    //                                             }
    //                                             if ($idTipoMoneda == 2 && $productoSelect->IdTipoMoneda == 1) {
    //                                                 $costo = floatval($costo / $valorCambioCompras);
    //                                             }
    //                                         }
    //                                         $newGanancia = $itemlista["importe"] - $costo - $itemlista["descuento"]; //$ganancias[$i];

    //                                         $bandTipo = 4;
    //                                     }

    //                                     $detallecotizacion_nuevact = new DetalleCotizacion_art();
    //                                     $detallecotizacion_nuevact->IdCotizacion = $idCotizacion;
    //                                     $detallecotizacion_nuevact->IdCliente = $idCliente;
    //                                     $detallecotizacion_nuevact->Codigo = $itemlista["codigo"];
    //                                     $detallecotizacion_nuevact->IdArticulo = $itemlista["idarticulo"]; //($producto == 'PAQ')?0:$itemlista->idarticulo;
    //                                     $detallecotizacion_nuevact->IdPaquetePromocional = 0; //($producto == 'PAQ')?$itemlista->idarticulo:0;
    //                                     $detallecotizacion_nuevact->Detalle = $itemlista["detalle"];
    //                                     $detallecotizacion_nuevact->Descuento = $itemlista["descuento"];
    //                                     $detallecotizacion_nuevact->VerificaTipo = $bandTipo;
    //                                     $detallecotizacion_nuevact->Cantidad = $itemlista["cantidad"];
    //                                     $detallecotizacion_nuevact->CantidadReal = $cantidadVentaReal;
    //                                     $detallecotizacion_nuevact->PrecioUnidadReal = $precio;
    //                                     $detallecotizacion_nuevact->TextUnidad = $itemlista["textundm"];
    //                                     $detallecotizacion_nuevact->Ganancia = $newGanancia;
    //                                     $detallecotizacion_nuevact->Importe = $itemlista["importe"];
    //                                     $detallecotizacion_nuevact->save();

    //                                     $cantidadVentaReal = 1;
    //                                     $bandTipo = 0;
    //                                     $bandGan = 0;
    //                                     usleep(100000);
    //                                 }
    //                                 return Response(['alert4', 'VERIFICANDO ERROR']);

    //                                 if ($arrayIdsNuevosPaquetesPromo) {

    //                                     $itemPaquete = ArticuloPaquetePromo::select("articulo_paquetePromocional.*", "articulo.*", "unidad_medida.Nombre as TextUnidad")
    //                                         ->joim("articulo", "articulo.IdArticulo", "=", "articulo_paquetePromocional.IdArticulo")
    //                                         ->join("unidad_medida", "unidad_medida.IdUnidadMedida", "=", "articulo_paquetePromocional.IdUnidadMedida")
    //                                         ->whereIn("IdPaquetePromocional", $idPaquete)
    //                                         ->get();

    //                                     $igv = 1.18;

    //                                     foreach ($itemPaquete as $articulopaq) {
    //                                         if ($articulopaq->IdTipo == 2) {
    //                                             $textUnidad = 'ZZ';
    //                                             $tipo = 4;
    //                                         } else {
    //                                             $textUnidad = 'ZZ';
    //                                             $tipo = 4;
    //                                         }
    //                                         $precioArticulo = ($tipoVenta == 2) ? round(floatval($articulopaq->Precio / $igv), 2) : round(floatval($articulopaq->Precio), 2);

    //                                         if ($banderaVentaSolesDolares == 1) {
    //                                             if ($idTipoMoneda == 1 && $articulopaq->IdTipoMoneda == 2) {
    //                                                 $precioArticulo = floatval($precioArticulo) * floatval($valorCambioVentas);
    //                                             }
    //                                             if ($idTipoMoneda == 2 && $articulopaq->IdTipoMoneda == 1) {
    //                                                 $precioArticulo = floatval($precioArticulo) / floatval($valorCambioCompras);
    //                                             }
    //                                         }

    //                                         $articulos_paquetepromact = new DetalleCotizacion_artprom();
    //                                         $articulos_paquetepromact->IdCotizacion = $idCotizacion;
    //                                         $articulos_paquetepromact->IdCliente = $idCliente;
    //                                         $articulos_paquetepromact->IdArticulo = $articulopaq->IdArticulo;
    //                                         $articulos_paquetepromact->Codigo = $articulopaq->CodigoArticulo;
    //                                         $articulos_paquetepromact->VerificaTipo = $tipo;
    //                                         $articulos_paquetepromact->Cantidad = $articulopaq->Cantidad;
    //                                         $articulos_paquetepromact->CantidadReal = intval($articulopaq->Cantidad);
    //                                         $articulos_paquetepromact->PrecioUnidadReal = $precioArticulo;
    //                                         $articulos_paquetepromact->TextUnidad = $textUnidad;
    //                                         $articulos_paquetepromact->Importe = floatval($precioArticulo * $articulopaq->Cantidad);
    //                                         $articulos_paquetepromact->IdPaquetePromocional = $articulopaq->IdPaquetePromocional;
    //                                         $articulos_paquetepromact->save();
    //                                         usleep(200000);
    //                                     }
    //                                 }
    //                                 if ($arrayIdsEliminadosPaquetesPromo) {

    //                                     DetalleCotizacion_artprom::where("IdCotizacion", $idCotizacion)->whereIn("IdPaquetePromocional", $arrayIdsEliminadosPaquetesPromo)->delete();
    //                                 }

    //                                 /*************************fin cambios 110923*********************/

    //                                 /*$cantidadVentaReal = 1; // puse esto para contener si hay algun error
    //                                 $bandTipo = 0;
    //                                 $bandGan = 0; //esto es para controlar la ganancia

    //                                 for ($i = 0; $i < count($req->Id); $i++) {
    //                                 $producto = substr($req->Codigo[$i], 0, 3);
    //                                 $productoSelect = $loadDatos->getProductoSelect($req->Id[$i]);

    //                                 if ($producto == 'PRO') {
    //                                 if ($req->Tipo[$i] == 1) {
    //                                 $precio = floatval($req->Precio[$i]);
    //                                 $costo = floatval($productoSelect->Costo);
    //                                 if ($productoSelect->TipoOperacion == 2) {
    //                                 $costo = floatval($costo / 1.18);
    //                                 }
    //                                 if ($ventaSolesDolares == 1) {
    //                                 if ($idTipoMoneda == 1 && $productoSelect->IdTipoMoneda == 2) {
    //                                 if (floatval($productoSelect->ValorTipoCambio) > 0) {
    //                                 $costo = floatval($costo * $productoSelect->ValorTipoCambio);
    //                                 } else {
    //                                 $costo = floatval($costo * $valorCambioVentas);
    //                                 }
    //                                 }
    //                                 if ($idTipoMoneda == 2 && $productoSelect->IdTipoMoneda == 1) {
    //                                 $costo = floatval($costo / $valorCambioCompras);
    //                                 }
    //                                 }
    //                                 $bandGan = $precio - $costo;
    //                                 $newGanancia = floatval($bandGan * $req->Cantidad[$i]) - floatval($req->Descuento[$i]);
    //                                 $bandTipo = 1;
    //                                 } else {

    //                                 if ($req->Tipo[$i] == 3) {
    //                                 //$newCantidad=$req->Cantidad[$i];
    //                                 //$cantidadRestada = (int) $productoSelect->Stock - ((int) $req->Cantidad[$i] * 1);
    //                                 $cantidadVentaReal = $req->Cantidad[$i] * 1;
    //                                 $newGanancia = $req->Importe[$i] - (($productoSelect->Costo * (1 * $req->Cantidad[$i])) - $req->Descuento[$i]);
    //                                 $bandTipo = 3;
    //                                 } else if ($req->Tipo[$i] == 2) {
    //                                 //$newCantidad=$req->Cantidad[$i]*$productoSelect->CantidadTipo;

    //                                 //$cantidadRestada = (int) $productoSelect->Stock - ((int) $req->Cantidad[$i] * (int) $productoSelect->CantidadTipo);
    //                                 $cantidadVentaReal = $req->Cantidad[$i] * $productoSelect->CantidadTipo;
    //                                 $newGanancia = $req->Importe[$i] - (($productoSelect->Costo * ($productoSelect->CantidadTipo * $req->Cantidad[$i])) - $req->Descuento[$i]);
    //                                 $bandTipo = 2;
    //                                 }
    //                                 }
    //                                 } else {
    //                                 // Nuevo codigo para traer los datos paquete promocional y guardarlo como un items de la cotizacion
    //                                 if ($producto == 'PAQ') {
    //                                 $productoSelect = $this->getDatosPaquetesPromocionales($req->Id[$i], $idSucursal);
    //                                 }
    //                                 // Fin

    //                                 $precio = floatval($req->Precio[$i]);
    //                                 $costo = floatval($productoSelect->Costo);
    //                                 if ($ventaSolesDolares == 1) {
    //                                 if ($idTipoMoneda == 1 && $productoSelect->IdTipoMoneda == 2) {
    //                                 $costo = floatval($costo * $valorCambioVentas);
    //                                 }
    //                                 if ($idTipoMoneda == 2 && $productoSelect->IdTipoMoneda == 1) {
    //                                 $costo = floatval($costo / $valorCambioCompras);
    //                                 }
    //                                 }
    //                                 $newGanancia = $req->Importe[$i] - $costo - $req->Descuento[$i]; //$ganancias[$i];

    //                                 $bandTipo = 4;
    //                                 }
    //                                 // Nuevo codigo para agregar el ID si es paquete o articulo

    //                                 if ($producto == 'PAQ') {
    //                                 $datoId = 'IdPaquetePromocional';
    //                                 } else {
    //                                 $datoId = 'IdArticulo';
    //                                 }

    //                                 // Fin
    //                                 $arrayRelacion = ['IdCotizacion' => $idCotizacion,
    //                                 'IdCliente' => $idCliente,
    //                                 $datoId => $req->Id[$i],
    //                                 'Codigo' => $req->Codigo[$i],
    //                                 'Detalle' => $req->Detalle[$i],
    //                                 'Descuento' => $req->Descuento[$i],
    //                                 'VerificaTipo' => $bandTipo,
    //                                 'Cantidad' => $req->Cantidad[$i],
    //                                 'CantidadReal' => $cantidadVentaReal,
    //                                 'PrecioUnidadReal' => $precio,
    //                                 'TextUnidad' => $req->TextUnida[$i],
    //                                 'Ganancia' => $newGanancia,
    //                                 'Importe' => $req->Importe[$i]];

    //                                 DB::table('cotizacion_articulo')->insert($arrayRelacion);
    //                                 $cantidadVentaReal = 1;
    //                                 $bandTipo = 0;
    //                                 $bandGan = 0;
    //                                 usleep(100000);
    //                                 }

    //                                 // Nuevo codigo para agregar nuevos items y eliminar los item de los paquetes promocionales que han sido quitados de la cotizacion
    //                                 if ($arrayIdsNuevosPaquetesPromo) {
    //                                 $itemPaquete = $this->getItemsPaquetePromocionalStore($arrayIdsNuevosPaquetesPromo);
    //                                 $igv = 1.18;
    //                                 for ($i = 0; $i < count($itemPaquete); $i++) {
    //                                 if ($itemPaquete[$i]->IdTipo == 2) {
    //                                 $textUnidad = 'ZZ';
    //                                 $tipo = 4;
    //                                 } else {
    //                                 $textUnidad = $itemPaquete[$i]->TextUnidad;
    //                                 $tipo = 1;
    //                                 }
    //                                 // condicion si tipo de venta es 2 -> EXONERADO, quitamos el IGV al precio articulo
    //                                 ($tipoVenta == 2) ? $precioArticulo = round(floatval($itemPaquete[$i]->Precio / $igv), 2) : $precioArticulo = round(floatval($itemPaquete[$i]->Precio), 2);

    //                                 if ($banderaVentaSolesDolares == 1) {
    //                                 if ($idTipoMoneda == 1 && $itemPaquete[$i]->IdTipoMoneda == 2) {
    //                                 $precioArticulo = floatval($precioArticulo) * floatval($valorCambioVentas);
    //                                 }
    //                                 if ($idTipoMoneda == 2 && $itemPaquete[$i]->IdTipoMoneda == 1) {
    //                                 $precioArticulo = floatval($precioArticulo) / floatval($valorCambioCompras);
    //                                 }
    //                                 }

    //                                 $arrayDatos = [
    //                                 'IdCotizacion' => $idCotizacion,
    //                                 'IdCliente' => $idCliente,
    //                                 'IdArticulo' => $itemPaquete[$i]->IdArticulo,
    //                                 'Codigo' => $itemPaquete[$i]->CodigoArticulo,
    //                                 'VerificaTipo' => $tipo,
    //                                 'Cantidad' => $itemPaquete[$i]->Cantidad,
    //                                 'CantidadReal' => intval($itemPaquete[$i]->Cantidad),
    //                                 'PrecioUnidadReal' => $precioArticulo,
    //                                 'TextUnidad' => $textUnidad,
    //                                 'Importe' => floatval($precioArticulo * $itemPaquete[$i]->Cantidad),
    //                                 'IdPaquetePromocional' => $itemPaquete[$i]->IdPaquetePromocional,
    //                                 ];
    //                                 DB::table('cotizacion_articuloPaquetePromocional')->insert($arrayDatos);
    //                                 usleep(200000);
    //                                 }
    //                                 }
    //                                 if ($arrayIdsEliminadosPaquetesPromo) {
    //                                 DB::table('cotizacion_articuloPaquetePromocional')
    //                                 ->where('IdCotizacion', $idCotizacion)
    //                                 ->whereIn('IdPaquetePromocional', $arrayIdsEliminadosPaquetesPromo)
    //                                 ->delete();
    //                                 }*/
    //                                 // Fin
    //                                 DB::commit();
    //                             } catch (\Throwable $th) {
    //                                 DB::rollBack();
    //                                 $idMaximo = DB::table('cotizacion_articulo')->SELECT(DB::RAW("MAX(IdCotizaArticulo) AS IdMaximo"))->first();
    //                                 $idMaximo = $idMaximo->IdMaximo + 1;
    //                                 DB::statement("ALTER TABLE cotizacion_articulo AUTO_INCREMENT=" . $idMaximo);
    //                                 return Response(['error', 'Surgio un error, comunicarse con soporte']);
    //                             }
    //                             return Response(['succes', 'Se Actualizo Correctamente la Cotizacion ', $idCotizacion]);

    //                         } else {
    //                             if ($idEstadoCotizacion == 2 || $idEstadoCotizacion == 3) {
    //                                 if ($placaVehiculo != '') {
    //                                     DB::beginTransaction();
    //                                     try {
    //                                         $cantidadVentaReal = 1;
    //                                         $arrayProductosEliminar = [];
    //                                         $arrayItemsYpaquetesActualesNoEliminar = [];
    //                                         // return Response(['alert10', 'MENSAJE DE PRUEBA-<->' . collect($arrayIdsNuevosPaquetesPromo)->toJson()]);

    //                                         for ($i = 0; $i < count($req->Id); $i++) {
    //                                             $producto = substr($req->Codigo[$i], 0, 3);
    //                                             $productoSelect = $loadDatos->getProductoSelect($req->Id[$i]);
    //                                             array_push($arrayItemsYpaquetesActualesNoEliminar, $req->Codigo[$i]);
    //                                             array_push($arrayProductosEliminar, $req->Id[$i]);
    //                                             // return Response(['alert10', 'MENSAJE DE PRUEBA'. collect( $arrayIdsNuevosPaquetesPromo)->toJson()]);

    //                                             if ($producto === 'SER' || $producto === 'PRO') {
    //                                                 if ($producto == 'PRO') {
    //                                                     $precio = floatval($req->Precio[$i]);
    //                                                     $costo = floatval($productoSelect->Costo);
    //                                                     if ($productoSelect->TipoOperacion == 2) {
    //                                                         $costo = floatval($costo / 1.18);
    //                                                     }
    //                                                     if ($ventaSolesDolares == 1) {
    //                                                         if ($idTipoMoneda == 1 && $productoSelect->IdTipoMoneda == 2) {
    //                                                             if (floatval($productoSelect->ValorTipoCambio) > 0) {
    //                                                                 $costo = floatval($costo * $productoSelect->ValorTipoCambio);
    //                                                             } else {
    //                                                                 $costo = floatval($costo * $valorCambioVentas);
    //                                                             }
    //                                                         }
    //                                                         if ($idTipoMoneda == 2 && $productoSelect->IdTipoMoneda == 1) {
    //                                                             $costo = floatval($costo / $valorCambioCompras);
    //                                                         }
    //                                                     }
    //                                                     $bandGan = $precio - $costo;
    //                                                     $newGanancia = floatval($bandGan * $req->Cantidad[$i]) - floatval($req->Descuento[$i]);
    //                                                     $bandTipo = 1;
    //                                                 } else {
    //                                                     $precio = floatval($req->Precio[$i]);
    //                                                     $costo = floatval($productoSelect->Costo);
    //                                                     if ($ventaSolesDolares == 1) {
    //                                                         if ($idTipoMoneda == 1 && $productoSelect->IdTipoMoneda == 2) {
    //                                                             if (floatval($productoSelect->ValorTipoCambio) > 0) {
    //                                                                 $costo = floatval($costo * $productoSelect->ValorTipoCambio);
    //                                                             } else {
    //                                                                 $costo = floatval($costo * $valorCambioVentas);
    //                                                             }
    //                                                         }
    //                                                         if ($idTipoMoneda == 2 && $productoSelect->IdTipoMoneda == 1) {
    //                                                             $costo = floatval($costo / $valorCambioCompras);
    //                                                         }
    //                                                     }
    //                                                     $newGanancia = $req->Importe[$i] - $costo - floatval($req->Descuento[$i]); //$ganancias[$i];
    //                                                     $bandTipo = 4;
    //                                                 }

    //                                                 $verificar = $this->verificarProductoCotizacion($req->Id[$i], $idCotizacion);
    //                                                 if ($verificar) {
    //                                                     if ($productoSelect->IdTipo == 1) {
    //                                                         $resta = floatval($verificar->Cantidad) - floatval($req->Cantidad[$i]);
    //                                                         $date = $loadDatos->getDateTime();
    //                                                         $cantidadRestada = $productoSelect->Stock;
    //                                                         if ($resta < 0) {
    //                                                             $resta = floatval($resta * -1);
    //                                                             DB::table('articulo')
    //                                                                 ->where('IdArticulo', $req->Id[$i])
    //                                                                 ->decrement('Stock', $resta);
    //                                                             $cantidadRestada = floatval($productoSelect->Stock) - floatval($resta);
    //                                                             $this->actualizarStock($req->Id[$i], $producto, $resta);

    //                                                             $kardex = array(
    //                                                                 'CodigoInterno' => $productoSelect->CodigoInterno,
    //                                                                 'fecha_movimiento' => $date,
    //                                                                 'tipo_movimiento' => 12,
    //                                                                 'usuario_movimiento' => $idUsuario,
    //                                                                 'documento_movimiento' => 'En Proceso - Descuento',
    //                                                                 'existencia' => $cantidadRestada,
    //                                                                 'costo' => $productoSelect->Precio,
    //                                                                 'IdArticulo' => $req->Id[$i],
    //                                                                 'IdSucursal' => $idSucursal,
    //                                                                 'Cantidad' => $req->Cantidad[$i],
    //                                                                 'Descuento' => $req->Descuento[$i],
    //                                                                 'ImporteEntrada' => 0,
    //                                                                 'ImporteSalida' => $req->Importe[$i],
    //                                                                 'estado' => 1,
    //                                                             );
    //                                                             DB::table('kardex')->insert($kardex);
    //                                                         } else {
    //                                                             if ($resta > 0) {

    //                                                                 $stockSelect = $loadDatos->getProductoStockSelect($req->Id[$i]);

    //                                                                 DB::table('articulo')
    //                                                                     ->where('IdArticulo', $req->Id[$i])
    //                                                                     ->increment('Stock', $resta);

    //                                                                 DB::table('stock')
    //                                                                     ->where('IdStock', $stockSelect[0]->IdStock)
    //                                                                     ->increment('Cantidad', $resta);

    //                                                                 $cantidadRestada = floatval($productoSelect->Stock) + floatval($resta);

    //                                                                 $kardex = array(
    //                                                                     'CodigoInterno' => $productoSelect->CodigoInterno,
    //                                                                     'fecha_movimiento' => $date,
    //                                                                     'tipo_movimiento' => 13,
    //                                                                     'usuario_movimiento' => $idUsuario,
    //                                                                     'documento_movimiento' => 'En Proceso - Reposicion',
    //                                                                     'existencia' => $cantidadRestada,
    //                                                                     'costo' => $productoSelect->Precio,
    //                                                                     'IdArticulo' => $req->Id[$i],
    //                                                                     'IdSucursal' => $idSucursal,
    //                                                                     'Cantidad' => $req->Cantidad[$i],
    //                                                                     'Descuento' => $req->Descuento[$i],
    //                                                                     'ImporteEntrada' => 0,
    //                                                                     'ImporteSalida' => $req->Importe[$i],
    //                                                                     'estado' => 1,
    //                                                                 );
    //                                                                 DB::table('kardex')->insert($kardex);
    //                                                             }
    //                                                         }
    //                                                     }
    //                                                     $arrayRelacion = [
    //                                                         'Detalle' => $req->Detalle[$i],
    //                                                         'Descuento' => $req->Descuento[$i],
    //                                                         'Cantidad' => $req->Cantidad[$i],
    //                                                         'PrecioUnidadReal' => $precio,
    //                                                         'TextUnidad' => $req->TextUnida[$i],
    //                                                         'Ganancia' => $newGanancia,
    //                                                         'Importe' => $req->Importe[$i],
    //                                                     ];
    //                                                     DB::table('cotizacion_articulo')
    //                                                         ->where('IdArticulo', $req->Id[$i])
    //                                                         ->where('IdCotizacion', $idCotizacion)
    //                                                         ->update($arrayRelacion);
    //                                                 } else {
    //                                                     $arrayRelacion = ['IdCotizacion' => $idCotizacion,
    //                                                         'IdCliente' => $idCliente,
    //                                                         'IdArticulo' => $req->Id[$i],
    //                                                         'Codigo' => $req->Codigo[$i],
    //                                                         'Detalle' => $req->Detalle[$i],
    //                                                         'Descuento' => $req->Descuento[$i],
    //                                                         'VerificaTipo' => $bandTipo,
    //                                                         'Cantidad' => $req->Cantidad[$i],
    //                                                         'CantidadReal' => $cantidadVentaReal,
    //                                                         'PrecioUnidadReal' => $precio,
    //                                                         'TextUnidad' => $req->TextUnida[$i],
    //                                                         'Ganancia' => $newGanancia,
    //                                                         'Importe' => $req->Importe[$i],
    //                                                     ];
    //                                                     DB::table('cotizacion_articulo')->insert($arrayRelacion);

    //                                                     DB::table('articulo')
    //                                                         ->where('IdArticulo', $req->Id[$i])
    //                                                         ->decrement('Stock', $req->Cantidad[$i]);

    //                                                     $this->actualizarStock($req->Id[$i], $producto, $req->Cantidad[$i]);

    //                                                     $date = $loadDatos->getDateTime();
    //                                                     $cantidadRestada = floatval($productoSelect->Stock) - floatval($req->Cantidad[$i]);
    //                                                     $kardex = array(
    //                                                         'CodigoInterno' => $productoSelect->CodigoInterno,
    //                                                         'fecha_movimiento' => $date,
    //                                                         'tipo_movimiento' => 12,
    //                                                         'usuario_movimiento' => $idUsuario,
    //                                                         'documento_movimiento' => 'En Proceso - Descuento',
    //                                                         'existencia' => $cantidadRestada,
    //                                                         'costo' => $productoSelect->Precio,
    //                                                         'IdArticulo' => $req->Id[$i],
    //                                                         'IdSucursal' => $idSucursal,
    //                                                         'Cantidad' => $req->Cantidad[$i],
    //                                                         'Descuento' => $req->Descuento[$i],
    //                                                         'ImporteEntrada' => 0,
    //                                                         'ImporteSalida' => $req->Importe[$i],
    //                                                         'estado' => 1,
    //                                                     );
    //                                                     DB::table('kardex')->insert($kardex);
    //                                                 }
    //                                                 usleep(100000);
    //                                             } else {
    //                                                 // return Response(['alert10', 'MENSAJE DE PRUEBA PAQUETE ->']);
    //                                                 // CODIGO PARA GUARDAR AL PAQUETE PROMOCIONAL COMO UN ITMES DE LA COTIZACION
    //                                                 $productoSelect = $this->getDatosPaquetesPromocionales($req->Id[$i], $idSucursal);
    //                                                 $precio = floatval($req->Precio[$i]);
    //                                                 $costo = floatval($productoSelect->Costo);
    //                                                 if ($ventaSolesDolares == 1) {
    //                                                     if ($idTipoMoneda == 1 && $productoSelect->IdTipoMoneda == 2) {
    //                                                         $costo = floatval($costo * $valorCambioVentas);
    //                                                     }
    //                                                     if ($idTipoMoneda == 2 && $productoSelect->IdTipoMoneda == 1) {
    //                                                         $costo = floatval($costo / $valorCambioCompras);
    //                                                     }
    //                                                 }
    //                                                 $newGanancia = $req->Importe[$i] - $costo - floatval($req->Descuento[$i]);
    //                                                 $bandTipo = 4;

    //                                                 // $verificar = $this->verificarExistenciaPaquetePromocional($req->Id[$i], $idCotizacion);

    //                                                 if (in_array($req->Id[$i], $arrayIdsAntiguosPaquetesPromo)) {
    //                                                     $arrayRelacion = [
    //                                                         'Detalle' => $req->Detalle[$i],
    //                                                         'Descuento' => $req->Descuento[$i],
    //                                                         'PrecioUnidadReal' => $precio,
    //                                                         'Ganancia' => $newGanancia,
    //                                                         'Importe' => $req->Importe[$i],
    //                                                     ];
    //                                                     DB::table('cotizacion_articulo')
    //                                                         ->where('IdPaquetePromocional', $req->Id[$i])
    //                                                         ->where('IdCotizacion', $idCotizacion)
    //                                                         ->update($arrayRelacion);

    //                                                 } else {
    //                                                     $arrayRelacion = ['IdCotizacion' => $idCotizacion,
    //                                                         'IdCliente' => $idCliente,
    //                                                         'IdPaquetePromocional' => $req->Id[$i],
    //                                                         'Codigo' => $req->Codigo[$i],
    //                                                         'Detalle' => $req->Detalle[$i],
    //                                                         'Descuento' => $req->Descuento[$i],
    //                                                         'VerificaTipo' => $bandTipo,
    //                                                         'Cantidad' => $req->Cantidad[$i],
    //                                                         'CantidadReal' => $cantidadVentaReal,
    //                                                         'PrecioUnidadReal' => $precio,
    //                                                         'TextUnidad' => $req->TextUnida[$i],
    //                                                         'Ganancia' => $newGanancia,
    //                                                         'Importe' => $req->Importe[$i]];
    //                                                     DB::table('cotizacion_articulo')->insert($arrayRelacion);
    //                                                 }
    //                                                 usleep(100000);
    //                                             }
    //                                         }
    //                                         // return Response(['alert10', 'MENSAJE DE PRUEBAAAA->' . collect($arrayIdsNuevosPaquetesPromocionales)->toJson()]);
    //                                         // CODIGO PARA ALMACENAR LOS ITEMS DEL PAQUETE PROMOCIONAL A LA TABLA cotizacion_articuloPaquetePromocional
    //                                         if ($arrayIdsNuevosPaquetesPromo) {
    //                                             $itemPaquete = $this->getItemsPaquetePromocionalStore($arrayIdsNuevosPaquetesPromo);
    //                                             $igv = 1.18;

    //                                             for ($i = 0; $i < count($itemPaquete); $i++) {
    //                                                 if ($itemPaquete[$i]->IdTipo == 2) {
    //                                                     $textUnidad = 'ZZ';
    //                                                     $tipo = 4;
    //                                                 } else {
    //                                                     $textUnidad = $itemPaquete[$i]->TextUnidad;
    //                                                     $tipo = 1;
    //                                                 }
    //                                                 // condicion si tipo de venta es 2 -> EXONERADO, quitamos el IGV al precio articulo
    //                                                 ($tipoVenta == 2) ? $precioArticulo = round(floatval($itemPaquete[$i]->Precio / $igv), 2) : $precioArticulo = round(floatval($itemPaquete[$i]->Precio), 2);

    //                                                 if ($banderaVentaSolesDolares == 1) {
    //                                                     if ($idTipoMoneda == 1 && $itemPaquete[$i]->IdTipoMoneda == 2) {
    //                                                         $precioArticulo = floatval($precioArticulo) * floatval($valorCambioVentas);
    //                                                     }
    //                                                     if ($idTipoMoneda == 2 && $itemPaquete[$i]->IdTipoMoneda == 1) {
    //                                                         $precioArticulo = floatval($precioArticulo) / floatval($valorCambioCompras);
    //                                                     }
    //                                                 }
    //                                                 $arrayDatos = [
    //                                                     'IdCotizacion' => $idCotizacion,
    //                                                     'IdCliente' => $idCliente,
    //                                                     'IdArticulo' => $itemPaquete[$i]->IdArticulo,
    //                                                     'Codigo' => $itemPaquete[$i]->CodigoArticulo,
    //                                                     'VerificaTipo' => $tipo,
    //                                                     'Cantidad' => $itemPaquete[$i]->Cantidad,
    //                                                     'CantidadReal' => intval($itemPaquete[$i]->Cantidad),
    //                                                     'PrecioUnidadReal' => $precioArticulo,
    //                                                     'TextUnidad' => $textUnidad,
    //                                                     'Importe' => floatval($precioArticulo * $itemPaquete[$i]->Cantidad),
    //                                                     'IdPaquetePromocional' => $itemPaquete[$i]->IdPaquetePromocional,
    //                                                 ];
    //                                                 DB::table('cotizacion_articuloPaquetePromocional')->insert($arrayDatos);
    //                                                 usleep(200000);

    //                                                 // Codigo para descontar el stock
    //                                                 DB::table('articulo')
    //                                                     ->where('IdArticulo', $itemPaquete[$i]->IdArticulo)
    //                                                     ->decrement('Stock', $itemPaquete[$i]->Cantidad);
    //                                                 $cantidadRestada = floatval($itemPaquete[$i]->Stock) - floatval($itemPaquete[$i]->Cantidad);
    //                                                 $this->actualizarStock($itemPaquete[$i]->IdArticulo, $producto, floatval($itemPaquete[$i]->Cantidad));

    //                                                 // Codigo para crear kardex
    //                                                 $kardex = array(
    //                                                     'CodigoInterno' => $itemPaquete[$i]->CodigoInterno,
    //                                                     'fecha_movimiento' => $date,
    //                                                     'tipo_movimiento' => 12,
    //                                                     'usuario_movimiento' => $idUsuario,
    //                                                     'documento_movimiento' => 'En Proceso - Descuento',
    //                                                     'existencia' => $cantidadRestada,
    //                                                     'costo' => $precioArticulo,
    //                                                     'IdArticulo' => $itemPaquete[$i]->IdArticulo,
    //                                                     'IdSucursal' => $idSucursal,
    //                                                     'Cantidad' => $itemPaquete[$i]->Cantidad,
    //                                                     'Descuento' => 0,
    //                                                     'ImporteEntrada' => 0,
    //                                                     'ImporteSalida' => floatval($precioArticulo * $itemPaquete[$i]->Cantidad),
    //                                                     'estado' => 1,
    //                                                 );
    //                                                 DB::table('kardex')->insert($kardex);

    //                                             }
    //                                         }

    //                                         $productosDelete = DB::table('cotizacion_articulo')
    //                                             ->where('IdCotizacion', $idCotizacion)
    //                                             ->whereNotIn('IdArticulo', $arrayProductosEliminar)
    //                                             ->whereNull('IdPaquetePromocional')
    //                                             ->get();
    //                                         // return Response(['alert10', 'MENSAJE DE PRUEBAAAAssssss' . collect($productosDelete)->toJson()]);
    //                                         // return Response(['alert10', 'MENSAJE DE PRUEBA ELIMNADO ARTI' . collect($productosDelete)->toJson()]);

    //                                         // codigo para traer los items de los paquetes que fueron quitados de la cotizacion y reponer su stock
    //                                         if ($arrayIdsEliminadosPaquetesPromo) {
    //                                             $itemsReponerStock = $this->getArticulosPaquetePromoCotizacionGenerada($idCotizacion, $arrayIdsEliminadosPaquetesPromo);
    //                                             $productosDelete = $productosDelete->concat($itemsReponerStock);

    //                                             DB::table('cotizacion_articuloPaquetePromocional')
    //                                                 ->where('IdCotizacion', $idCotizacion)
    //                                                 ->whereIn('IdPaquetePromocional', $arrayIdsEliminadosPaquetesPromo)
    //                                                 ->delete();
    //                                         }
    //                                         //============= FIN =============
    //                                         // return Response(['alert10', 'MENSAJE DE PRUEBAAAAssssss' . collect($itemsEliminados)->toJson()]);
    //                                         // return Response(['alert10', 'MENSAJE DE PRUEBAAAAssssss' . collect($productosDelete)->toJson()]);
    //                                         if (count($productosDelete) >= 1) {
    //                                             $date = $loadDatos->getDateTime();
    //                                             for ($i = 0; $i < count($productosDelete); $i++) {
    //                                                 if ($productosDelete[$i]->VerificaTipo != 4) {
    //                                                     $stockSelectRep = $loadDatos->getProductoStockSelect($productosDelete[$i]->IdArticulo);

    //                                                     $productoSelect = $loadDatos->getProductoSelect($productosDelete[$i]->IdArticulo);
    //                                                     $existencia = floatval($productoSelect->Stock) + floatval($productosDelete[$i]->Cantidad);
    //                                                     DB::table('articulo')
    //                                                         ->where('IdArticulo', $productosDelete[$i]->IdArticulo)
    //                                                         ->increment('Stock', $productosDelete[$i]->Cantidad);

    //                                                     DB::table('stock')
    //                                                         ->where('IdStock', $stockSelectRep[0]->IdStock)
    //                                                         ->increment('Cantidad', $productosDelete[$i]->Cantidad);

    //                                                     $kardex = array(
    //                                                         'CodigoInterno' => $productoSelect->CodigoInterno,
    //                                                         'fecha_movimiento' => $date,
    //                                                         'tipo_movimiento' => 13,
    //                                                         'usuario_movimiento' => $idUsuario,
    //                                                         'documento_movimiento' => 'En Proceso - Reposicion',
    //                                                         'existencia' => $existencia,
    //                                                         'costo' => $productoSelect->Precio,
    //                                                         'IdArticulo' => $productosDelete[$i]->IdArticulo,
    //                                                         'IdSucursal' => $idSucursal,
    //                                                         'Cantidad' => $productosDelete[$i]->Cantidad,
    //                                                         'Descuento' => 0,
    //                                                         'ImporteEntrada' => $productosDelete[$i]->Importe,
    //                                                         'ImporteSalida' => 0,
    //                                                         'estado' => 1,
    //                                                     );
    //                                                     DB::table('kardex')->insert($kardex);
    //                                                 }
    //                                             }
    //                                             DB::table('cotizacion_articulo')
    //                                                 ->where('IdCotizacion', $idCotizacion)
    //                                                 ->whereNotIn('Codigo', $arrayItemsYpaquetesActualesNoEliminar)
    //                                                 ->delete();

    //                                             // DB::table('cotizacion_articulo')
    //                                             //     ->where('IdCotizacion', $idCotizacion)
    //                                             //     ->whereNotIn('IdArticulo', $arrayProductosEliminar)
    //                                             //     ->delete();

    //                                         }
    //                                         DB::commit();

    //                                     } catch (\Throwable $th) {
    //                                         DB::rollBack();
    //                                         $idMaximoCotiArt = DB::table('cotizacion_articulo')->SELECT(DB::RAW("MAX(IdCotizaArticulo) AS IdMaximo"))->first();
    //                                         $idMaximoCotiArt = $idMaximoCotiArt->IdMaximo + 1;
    //                                         DB::statement("ALTER TABLE cotizacion_articulo AUTO_INCREMENT=" . $idMaximoCotiArt);

    //                                         $idMaximoKardex = DB::table('kardex')->SELECT(DB::RAW("MAX(IdKardex) AS IdMaximo"))->first();
    //                                         $idMaximoKardex = $idMaximoKardex->IdMaximo + 1;
    //                                         DB::statement("ALTER TABLE kardex AUTO_INCREMENT=" . $idMaximoKardex);

    //                                         return Response(['error', 'error']);
    //                                     }

    //                                     return Response(['succes', 'Se actualizo cotización correctamente']);
    //                                 }
    //                             }

    //                         }
    //                     } else {
    //                         return Response(['alert10', 'Quedan ' . $stockSuficiente[2] . ' unidades en stock de : ' . $stockSuficiente[1]]);
    //                     }
    //                 }
    //             }
    //         } else {
    //             Session::flush();
    //             return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
    //         }
    //     } catch (Exception $ex) {
    //         echo $ex->getMessage();
    //     }
    // }

    private function verificarProductoCotizacion($idProducto, $idCotizacion)
    {
        $resultado = DB::table('cotizacion_articulo')
            ->where('IdArticulo', $idProducto)
            ->where('IdCotizacion', $idCotizacion)
            ->first();
        return $resultado;
    }

    public function getVerificarPaquetePromocional($idCotizacion)
    {
        $verificar = DB::table('cotizacion_articulo')
            ->select(DB::raw("count(*) as totalPaquetePromocional"))
            ->where('IdCotizacion', $idCotizacion)
            ->where('IdPaquetePromocional', '>', 0)
            ->first();

        if ($verificar->totalPaquetePromocional > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function convertirCotizacion(Request $req, $id)
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

        $tipoComprobante = $loadDatos->getTipoComprobante();
        $fecha = date("d/m/Y");
        $text = "";
        $tipoMonedas = $loadDatos->getTipoMoneda();
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        if ($modulosSelect->contains('IdModulo', 7)) {
            $moduloCronogramaActivo = 'activado';
        } else {
            $moduloCronogramaActivo = 'desactivado';
        }
        $cod_cliente = DB::table('sucursal')
            ->select('CodigoCliente')
            ->where('IdSucursal', $idSucursal)
            ->first();

        $sucPrincipal = DB::table('sucursal')
            ->select('IdSucursal')
            ->where('CodigoCliente', $cod_cliente->CodigoCliente)
            ->where('Principal', 1)
            ->first();

        $cotizacionSelect = $this->getCotizacionselect($id);

        if ($sucPrincipal->IdSucursal == $idSucursal) {
            $productos = $loadDatos->getProductosPagination($idSucursal, $text, $cotizacionSelect->IdTipoMoneda, 0);
        } else {
            $productos = $loadDatos->paginarAjaxProdSucursal($idSucursal, $text, $cotizacionSelect->IdTipoMoneda, 0);
        }

        $servicios = $loadDatos->getServiciosPagination($idSucursal, $cotizacionSelect->IdTipoMoneda, $text);
        $items = $this->getItemsCotizacion($id);
        $itemsPaquetePromocional = $this->getPaquetesPromoCotizacionGenerada($id);

        for ($i = 0; $i < count($itemsPaquetePromocional); $i++) {

            $paquetesProm = $loadDatos->getItemsPaquetePromocional($itemsPaquetePromocional[$i]->IdPaquetePromocional);

            $itemsPaquetePromocional[$i]->EstadoPaquete = 0;
            for ($j = 0; $j < count($paquetesProm); $j++) {
                if (($paquetesProm[$j]->idTipoItems == 1) && (floatval($paquetesProm[$j]->Stock) < floatval($paquetesProm[$j]->cantidad))) {
                    $itemsPaquetePromocional[$i]->EstadoPaquete = 1;
                }
            }
        }
        $date = date("Y-m-d", strtotime($cotizacionSelect->FechaCreacion));
        //$fechaConvertida = $date->format("Y-m-d");

        $valorCambio = DB::table('tipo_cambio')
            ->where('IdSucursal', $idSucursal)
            ->where('FechaCreacion', $date)
            ->first();

        $placa = '';

        $datosVe = DB::table('cotizacion as c')
            ->join('vehiculo as v', 'c.Campo0', '=', 'v.IdVehiculo')
            ->where('IdCotizacion', $id)
            ->first();

        if ($datosVe) {
            $placa = $datosVe->PlacaVehiculo;

        }

        if ($cotizacionSelect->TipoCotizacion == 2) {
            $seguro = DB::table('cotizacion as c')
                ->join('vehiculo as v', 'c.Campo0', '=', 'v.IdVehiculo')
                ->join('seguros as s', 'v.IdSeguro', '=', 's.IdSeguro')
                ->where('IdCotizacion', $id)
                ->first();
            $idSeguro = $seguro->IdSeguro;
            $seguro = $seguro->Descripcion;
        } else {
            $idSeguro = 0;
            $seguro = "";
        }

        $arrayVentas = DB::table('ventas')
            ->where('IdCotizacion', $id)
            ->get();

        $conversionCotiMoneda = 0;
        $tipoCambioGuardado = 0;

        /*if(count($arrayVentas) > 0){
        if($arrayVentas[0]->IdTipoMoneda != $cotizacionSelect->IdTipoMoneda){
        $conversionCotiMoneda = 1;
        $arrayVentas = DB::table('ventas')
        ->where('IdCotizacion', $id)
        ->get();

        $fechaAnt = date_create($arrayVentas[0]->FechaCreacion);
        $formatoFechaAnt = date_format($fechaAnt, 'Y-m-d');

        $tipoCambio = DB::table('tipo_cambio')
        ->where('IdSucursal', $idSucursal)
        ->where('FechaCreacion', $formatoFechaAnt)
        ->first();

        $tipoCambioGuardado = $tipoCambio->TipoCambioVentas;
        }
        }*/
        $amortizaciones = $this->getAmortizaciones($id);

        $operarios = $loadDatos->getOperarios($idSucursal);

        $cuentas = $loadDatos->getCuentasCorrientes($cod_cliente->CodigoCliente, $cotizacionSelect->IdTipoMoneda);

        $sucursal = $loadDatos->getSucursalSelect($idSucursal);
        $sucExonerado = $sucursal->Exonerado;
        $datosEmpresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $exonerado = $datosEmpresa->Exonerado;

        $bienesServicios = $loadDatos->getBienesServicios();
        $medioPagos = $loadDatos->getMedioPagos();
        $cuentaDetraccion = $loadDatos->getCuentaDetracciones($usuarioSelect->CodigoCliente, 9);

        $array = ['usuarioSelect' => $usuarioSelect, 'tipoComprobante' => $tipoComprobante, 'idSeguro' => $idSeguro, 'seguro' => $seguro, 'idSucursal' => $idSucursal, 'cuentas' => $cuentas, 'tipoMoneda' => $tipoMonedas, 'fecha' => $fecha, 'idEstadoCotizacion' => $cotizacionSelect->IdEstadoCotizacion, 'idTipoMoneda' => $cotizacionSelect->IdTipoMoneda, 'productos' => $productos, 'servicios' => $servicios, 'items' => $items, 'IdCotizacion' => $id, 'IdCliente' => $cotizacionSelect->IdCliente,
            'amortizaciones' => $amortizaciones->AmortizacionTotal, 'nombreCli' => $cotizacionSelect->RazonSocial, 'IdOperario' => $cotizacionSelect->IdOperario, 'operarios' => $operarios, 'tipoVenta' => $cotizacionSelect->TipoVenta, 'valorCambio' => $valorCambio, 'conversionCotiMoneda' => $conversionCotiMoneda, 'tipoCambioGuardado' => $tipoCambioGuardado, 'cuentaDetraccion' => $cuentaDetraccion, 'bienesServicios' => $bienesServicios, 'medioPagos' => $medioPagos,
            'Trabajos' => $cotizacionSelect->Trabajos, 'kilometro' => $cotizacionSelect->Campo1, 'horometro' => $cotizacionSelect->Campo2, 'placa' => $placa, 'tipo' => $cotizacionSelect->TipoCotizacion, 'FechaFinal' => $cotizacionSelect->FechaFin, 'Observacion' => $cotizacionSelect->Observacion, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'sucExonerado' => $sucExonerado, 'exonerado' => $exonerado, 'moduloCronogramaActivo' => $moduloCronogramaActivo, 'itemsPaquetePromocional' => $itemsPaquetePromocional];
        return view('operaciones/cotizacion/convertirCotizacion', $array);
    }

    // public function guardarTipoCambio(Request $req)
    // {
    //     if ($req->ajax()) {
    //         $idUsuario = Session::get('idUsuario');
    //         $idSucursal = Session::get('idSucursal');
    //         $fecha = Carbon::today();

    //         $tipoCambioCompras = $req->tipoCambioCompras;
    //         $tipoCambioVentas = $req->tipoCambioVentas;

    //         if ($tipoCambioCompras == null || $tipoCambioCompras == '') {
    //             return Response(['alert', 'Completar el campo de tipo de cambio de compras']);
    //         }

    //         if ($tipoCambioVentas == null || $tipoCambioVentas == '') {
    //             return Response(['alert', 'Completar el campo de tipo de cambio de ventas']);
    //         }

    //         $array = ['FechaCreacion' => $fecha, 'IdUsuario' => $idUsuario, 'IdSucursal' => $idSucursal, 'TipoCambioCompras' => $tipoCambioCompras, 'TipoCambioVentas' => $tipoCambioVentas, 'Estado' => 'E'];
    //         DB::table('tipo_cambio')->insert($array);

    //         /*$tipoCambio = DB::table('tipo_cambio')
    //         ->where('IdTipoCambio', 'desc')
    //         ->first();*/

    //         return Response(['success', 'Se guardó tipo de cambio correctamente']);
    //     }
    // }

    // public function guardarTipoCambioEdicion(Request $req)
    // {
    //     if ($req->ajax()) {
    //         $idUsuario = Session::get('idUsuario');
    //         $idSucursal = Session::get('idSucursal');
    //         $fecha = $req->fecha;

    //         $tipoCambioCompras = $req->tipoCambioCompras;
    //         $tipoCambioVentas = $req->tipoCambioVentas;

    //         if ($tipoCambioCompras == null || $tipoCambioCompras == '') {
    //             return Response(['alert', 'Completar el campo de tipo de cambio de compras']);
    //         }

    //         if ($tipoCambioVentas == null || $tipoCambioVentas == '') {
    //             return Response(['alert', 'Completar el campo de tipo de cambio de ventas']);
    //         }

    //         $array = ['FechaCreacion' => $fecha, 'IdUsuario' => $idUsuario, 'IdSucursal' => $idSucursal, 'TipoCambioCompras' => $tipoCambioCompras, 'TipoCambioVentas' => $tipoCambioVentas, 'Estado' => 'E'];
    //         DB::table('tipo_cambio')->insert($array);

    //         /*$tipoCambio = DB::table('tipo_cambio')
    //         ->where('IdTipoCambio', 'desc')
    //         ->first();*/

    //         return Response(['success', 'Se guardo tipo de cambio correctamente']);
    //     }
    // }

    public function estadosCotizacion(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            $cotizacionSelect = $this->getCotizacionselect($id);
            if ($cotizacionSelect->IdOperario > 0) {
                $operarioSelect = $loadDatos->getOperarioSelect($cotizacionSelect->IdOperario);
                $operario = $operarioSelect->Nombres;
            } else {
                $operario = 'Genérico';

            }
            $vehiculoSelect = $loadDatos->getVehiculoSelect($cotizacionSelect->Campo0);
            $ventasRealizadas = $this->getVentasRealizadas($id);
            $fecha = date_create($cotizacionSelect->FechaCreacion);
            $formatoFecha = date_format($fecha, 'd-m-Y');
            $formatoHora = date_format($fecha, 'H:i A');
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
            $permisos = $loadDatos->getPermisos($idUsuario);
            $subpermisos = $loadDatos->getSubPermisos($idUsuario);
            $subniveles = $loadDatos->getSubNiveles($idUsuario);
            $numeroCerosIzquierda = $this->completarCeros($cotizacionSelect->Numero);
            // $items = $this->getItemsCotizacion($id);
            $items = $this->getItemsCotizacionYpaquetePromocional($id);
            $usuarioAbierto = $this->getRegistroEstado($id, 1);
            $usuarioEnProceso = $this->getRegistroEstado($id, 2);
            $usuarioFinalizado = $this->getRegistroEstado($id, 3);
            $usuarioCerrado = $this->getRegistroEstado($id, 4);
            $array = ['cotizacionSelect' => $cotizacionSelect, 'ventasRealizadas' => $ventasRealizadas, 'numeroCeroIzq' => $numeroCerosIzquierda, 'items' => $items, 'vehiculo' => $vehiculoSelect, 'operario' => $operario, 'permisos' => $permisos, 'formatoFecha' => $formatoFecha, 'formatoHora' => $formatoHora,
                'IdEstadoCotizacion' => $cotizacionSelect->IdEstadoCotizacion, 'usuarioAbierto' => $usuarioAbierto, 'usuarioEnProceso' => $usuarioEnProceso, 'usuarioFinalizado' => $usuarioFinalizado, 'usuarioCerrado' => $usuarioCerrado, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles];
            return view('operaciones/cotizacion/estadosCotizacion', $array);
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    private function getRegistroEstado($idCotizacion, $idEstadoCotizacion)
    {
        $estadoCotizacion = DB::table('registro_estados')
            ->join('usuario', 'registro_estados.IdUsuario', '=', 'usuario.IdUsuario')
            ->select('registro_estados.*', 'usuario.Nombre')
            ->where('IdCotizacion', $idCotizacion)
            ->where('IdEstadoCotizacion', $idEstadoCotizacion)
            ->first();
        return $estadoCotizacion;
    }

    private function getVentasRealizadas($idCotizacion)
    {
        $ventas = DB::table('ventas')
            ->where('IdCotizacion', $idCotizacion)
            ->count();
        return $ventas;
    }

    public function actualizarEstadosCotizacion(Request $req)
    {
        DB::beginTransaction();
        try {

            $loadDatos = new DatosController();
            $idCotizacion = $req->idCotizacion;
            $idEstadoCotizacion = $req->estadoCotizacion;
            $idSucursal = Session::get('idSucursal');
            $idUsuario = Session::get('idUsuario');
            $fechaConvertida = $loadDatos->getDateTime();
            $fechaFinAtencion = $req->inputFechaFinAtencion ? Carbon::createFromFormat('d/m/Y', $req->inputFechaFinAtencion)->format('Y-m-d H:i:s') : null;
            $cotizacionSelect = $this->getCotizacionselect($idCotizacion);
            // Obtener los articulos incluido de los paquetes
            $items = $this->getItemsCotizacionYpaquetePromocional($idCotizacion);

            if ($idEstadoCotizacion == 1) {
                for ($i = 0; $i < count($items); $i++) {
                    $productoSelect = $loadDatos->getProductoSelect($items[$i]->IdArticulo);
                    if ($productoSelect->IdTipo == 1) {
                        $cantidadRestada = floatval($productoSelect->Stock) - floatval($items[$i]->Cantidad);

                        DB::table('articulo')
                            ->where('IdArticulo', $items[$i]->IdArticulo)
                            ->update(['Stock' => $cantidadRestada]);

                        $this->actualizarStock($items[$i]->IdArticulo, $items[$i]->Codigo, $items[$i]->Cantidad);

                        $kardex = array(
                            'CodigoInterno' => $productoSelect->CodigoInterno,
                            'fecha_movimiento' => $fechaConvertida,
                            'tipo_movimiento' => 12,
                            'usuario_movimiento' => $idUsuario,
                            'documento_movimiento' => 'En Proceso - Descuento : ' . $cotizacionSelect->Serie . '-' . $cotizacionSelect->Numero,
                            'existencia' => $cantidadRestada,
                            'costo' => $productoSelect->Precio,
                            'IdArticulo' => $items[$i]->IdArticulo,
                            'IdSucursal' => $idSucursal,
                            'Cantidad' => $items[$i]->Cantidad,
                            'Descuento' => $items[$i]->Descuento,
                            'ImporteEntrada' => 0,
                            'ImporteSalida' => $items[$i]->Importe,
                            'estado' => 1,
                        );
                        DB::table('kardex')->insert($kardex);
                    }
                }
                DB::table('registro_estados')->insert(['IdUsuario' => $idUsuario, 'Idsucursal' => $idSucursal, 'FechaRegistro' => $fechaConvertida, 'IdCotizacion' => $idCotizacion, 'IdEstadoCotizacion' => 2]);

                DB::table('cotizacion')
                    ->where('IdCotizacion', $idCotizacion)
                    ->update(['IdEstadoCotizacion' => 2, 'FechaFinAtencion' => $fechaFinAtencion]);
            }
            if ($idEstadoCotizacion == 2) {
                DB::table('registro_estados')->insert(['IdUsuario' => $idUsuario, 'Idsucursal' => $idSucursal, 'FechaRegistro' => $fechaConvertida, 'IdCotizacion' => $idCotizacion, 'IdEstadoCotizacion' => 3]);

                DB::table('cotizacion')
                    ->where('IdCotizacion', $idCotizacion)
                    ->update(['IdEstadoCotizacion' => 3]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('operaciones/cotizacion/estados-cotizacion/' . $idCotizacion)->with('error', 'Error al actualizar el estado de la cotización.');
        }
        return redirect('operaciones/cotizacion/estados-cotizacion/' . $idCotizacion)->with('status', 'Se actualizo estado correctamente');
    }

    // public function actualizarEstadosCotizacion(Request $req)
    // {
    //     $loadDatos = new DatosController();
    //     $idCotizacion = $req->idCotizacion;
    //     $idEstadoCotizacion = $req->estadoCotizacion;
    //     $idSucursal = Session::get('idSucursal');
    //     $idUsuario = Session::get('idUsuario');
    //     $fechaConvertida = $loadDatos->getDateTime();
    //     $items = $this->getItemsCotizacion($idCotizacion);

    //     if ($idEstadoCotizacion == 1) {
    //         for ($i = 0; $i < count($items); $i++) {
    //             $productoSelect = $loadDatos->getProductoSelect($items[$i]->IdArticulo);
    //             $cantidadRestada = floatval($productoSelect->Stock) - floatval($items[$i]->Cantidad);

    //             DB::table('articulo')
    //                 ->where('IdArticulo', $items[$i]->IdArticulo)
    //                 ->update(['Stock' => $cantidadRestada]);

    //             $this->actualizarStock($items[$i]->IdArticulo, $items[$i]->Codigo, $items[$i]->Cantidad);

    //             $kardex = array(
    //                 'CodigoInterno' => $productoSelect->CodigoInterno,
    //                 'fecha_movimiento' => $fechaConvertida,
    //                 'tipo_movimiento' => 12,
    //                 'usuario_movimiento' => $idUsuario,
    //                 'documento_movimiento' => 'En Proceso - Descuento',
    //                 'existencia' => $cantidadRestada,
    //                 'costo' => $productoSelect->Precio,
    //                 'IdArticulo' => $items[$i]->IdArticulo,
    //                 'IdSucursal' => $idSucursal,
    //                 'Cantidad' => $items[$i]->Cantidad,
    //                 'Descuento' => $items[$i]->Descuento,
    //                 'ImporteEntrada' => 0,
    //                 'ImporteSalida' => $items[$i]->Importe,
    //                 'estado' => 1,
    //             );
    //             DB::table('kardex')->insert($kardex);
    //         }
    //         DB::table('registro_estados')->insert(['IdUsuario' => $idUsuario, 'Idsucursal' => $idSucursal, 'FechaRegistro' => $fechaConvertida, 'IdCotizacion' => $idCotizacion, 'IdEstadoCotizacion' => 2]);

    //         DB::table('cotizacion')
    //             ->where('IdCotizacion', $idCotizacion)
    //             ->update(['IdEstadoCotizacion' => 2]);
    //     }
    //     if ($idEstadoCotizacion == 2) {
    //         DB::table('registro_estados')->insert(['IdUsuario' => $idUsuario, 'Idsucursal' => $idSucursal, 'FechaRegistro' => $fechaConvertida, 'IdCotizacion' => $idCotizacion, 'IdEstadoCotizacion' => 3]);

    //         DB::table('cotizacion')
    //             ->where('IdCotizacion', $idCotizacion)
    //             ->update(['IdEstadoCotizacion' => 3]);
    //     }
    //     return redirect('operaciones/cotizacion/estados-cotizacion/' . $idCotizacion)->with('status', 'Se actualizo estado correctamente');
    // }

    public function obtenerInformacion(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                if ($req->ajax()) {

                    $idUsuario = Session::get('idUsuario');
                    $loadDatos = new DatosController();
                    $idSucursal = Session::get('idSucursal');
                    $req->tipoDoc;

                    if ($req->tipoDoc < 0) {
                        if ($req->opcion == 1) {
                            $clientes = $loadDatos->getClientes($idSucursal);
                            return Response()->json([
                                'clientes' => $clientes,
                                'tipo' => $req->tipoDoc,
                            ]);
                        } else {
                            $clientes = $this->getVehiculos($idSucursal);

                            $clientes = $clientes;
                            return Response()->json([
                                'clientes' => $clientes,
                                'tipo' => $req->tipoDoc,
                            ]);
                        }
                    } else {
                        if ($req->tipoDoc == 0) {
                            //return Response(['error','Por favor, elegir Tipo de comprobante']);
                            return Response()->json([
                                'error' => true,
                            ]);
                        } else {
                            switch ($req->tipoDoc) {
                                case 1:$letra = 'B';
                                    break;
                                case 2:$letra = 'F';
                                    break;
                                case 3:$letra = 'T';
                                    break;
                                default:'';
                            }
                        }

                        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
                        $orden = $usuarioSelect->Orden;
                        $sucursal = $loadDatos->getSucursalSelect($idSucursal);
                        $ordenSucursal = $sucursal->Orden;
                        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

                        $numeroDB = $this->ultimoCorrelativo($idUsuario, $idSucursal, $req->tipoDoc);

                        if ($numeroDB) {
                            $numero = str_pad($numeroDB->Numero + 1, 8, "0", STR_PAD_LEFT);
                        } else {
                            $numero = str_pad(1, 8, "0", STR_PAD_LEFT);
                        }

                        $serieCeros = str_pad($orden, 2, "0", STR_PAD_LEFT);
                        $serie = $letra . '' . $ordenSucursal . '' . $serieCeros;

                        $clientes = $loadDatos->getTipoClientes($req->tipoDoc, $idSucursal);
                        return Response()->json([
                            'clientes' => $clientes,
                            'serie' => $serie,
                            'numero' => $numero,
                            'tipo' => $req->tipoDoc,
                        ]);
                    }
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function getAmortizaciones($idCotizacion)
    {
        $amortizaciones = DB::table('amortizacion')
            ->select(DB::raw('SUM(Monto) as AmortizacionTotal'))
            ->where('IdCotizacion', $idCotizacion)
            ->first();
        return $amortizaciones;
    }

    private function getAmortizacionCotizado($idCotizacion)
    {
        $amortizaciones = DB::table('amortizacion')
            ->where('IdCotizacion', $idCotizacion)
            ->get();
        return $amortizaciones;
    }

    // public function exportExcel($idTipoAtencion, $idEstadoCotizacion, $fecha, $fechaInicial, $fechaFinal)
    // {
    //     $idSucursal = Session::get('idSucursal');
    //     $idUsuario = Session::get('idUsuario');
    //     $loadDatos = new DatosController();
    //     $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
    //     $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
    //     $fechaIni = str_replace('-', '/', $fechaInicial);
    //     $fechaFin = str_replace('-', '/', $fechaFinal);

    //     $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
    //     $ventas = DB::table('ventas')
    //         ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
    //         ->get();

    //     $cotizaciones = $this->getCotizacionAllFiltrado($idTipoAtencion, $idSucursal, $idEstadoCotizacion, $fecha, $fechaIni, $fechaFin);
    //     if (count($cotizaciones) > 0) {
    //         foreach ($cotizaciones as $cotizacion) {
    //             if ($cotizacion->TipoCotizacion == 2) {
    //                 $vehiculoSelect = $loadDatos->getVehiculoSelect($cotizacion->Campo0);
    //                 $cotizacion->Placa = $vehiculoSelect->PlacaVehiculo;
    //                 $cotizacion->Anio = $vehiculoSelect->Anio;
    //                 $cotizacion->Marca = $vehiculoSelect->NombreMarca;
    //                 $cotizacion->Modelo = $vehiculoSelect->NombreModelo;
    //                 $cotizacion->Seguro = $vehiculoSelect->Seguro;
    //                 $cotizacion->ChasisVehiculo = $vehiculoSelect->ChasisVehiculo;
    //                 if ($cotizacion->IdEstadoCotizacion == 4) {
    //                     $cotizacion->Documentos = $ventas->where('IdCotizacion', $cotizacion->IdCotizacion);
    //                 } else {
    //                     $cotizacion->Documentos = [];
    //                 }

    //             } else {
    //                 $cotizacion->Placa = '';
    //                 $cotizacion->Anio = '';
    //                 $cotizacion->Marca = '';
    //                 $cotizacion->Modelo = '';
    //                 $cotizacion->Seguro = '';
    //                 $cotizacion->ChasisVehiculo = '';
    //                 if ($cotizacion->IdEstadoCotizacion == 4) {
    //                     $cotizacion->Documentos = $ventas->where('IdCotizacion', $cotizacion->IdCotizacion);
    //                 } else {
    //                     $cotizacion->Documentos = [];
    //                 }

    //             }
    //         }
    //     }
    //     return Excel::download(new ExcelCotizaciones($cotizaciones, $modulosSelect), 'Reporte Cotizaciones.xlsx');
    // }

    public function exportExcel($idTipoAtencion, $idEstadoCotizacion, $fecha, $fechaInicial, $fechaFinal)
    {
        $idSucursal = Session::get('idSucursal');
        $idUsuario = Session::get('idUsuario');
        $loadDatos = new DatosController();
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $fechaIni = str_replace('-', '/', $fechaInicial);
        $fechaFin = str_replace('-', '/', $fechaFinal);

        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $cotizaciones = $this->getCotizacionAllFiltrado($idTipoAtencion, $idSucursal, $idEstadoCotizacion, $fechas[0], $fechas[1]);

        if (count($cotizaciones) > 0) {
            foreach ($cotizaciones as $cotizacion) {
                if ($cotizacion->TipoCotizacion == 2) {
                    $vehiculoSelect = $loadDatos->getVehiculoSelect($cotizacion->Campo0);
                    $cotizacion->Placa = $vehiculoSelect->PlacaVehiculo;
                    $cotizacion->Anio = $vehiculoSelect->Anio;
                    $cotizacion->Marca = $vehiculoSelect->NombreMarca;
                    $cotizacion->Modelo = $vehiculoSelect->NombreModelo;
                    $cotizacion->Seguro = $vehiculoSelect->Seguro;
                    $cotizacion->ChasisVehiculo = $vehiculoSelect->ChasisVehiculo;
                    if ($cotizacion->IdEstadoCotizacion == 4) {
                        $cotizacion->Comprobantes = DB::table('ventas')
                            ->where('IdCotizacion', $cotizacion->IdCotizacion)
                            ->get();
                    } else {
                        $cotizacion->Comprobantes = [];
                    }

                } else {
                    $cotizacion->Placa = '';
                    $cotizacion->Anio = '';
                    $cotizacion->Marca = '';
                    $cotizacion->Modelo = '';
                    $cotizacion->Seguro = '';
                    $cotizacion->ChasisVehiculo = '';
                    $cotizacion->CodigoInventario = null;
                    if ($cotizacion->IdEstadoCotizacion == 4) {
                        $cotizacion->Comprobantes = DB::table('ventas')
                            ->where('IdCotizacion', $cotizacion->IdCotizacion)
                            ->get();
                    } else {
                        $cotizacion->Comprobantes = [];
                    }
                }
            }
        }
        // dd($cotizaciones);

        return Excel::download(new ExcelCotizaciones($cotizaciones, $modulosSelect), 'Reporte Cotizaciones.xlsx');
    }

    public function storePdfForWhatsapp(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $numeroCelular = $req->numeroCelular;
        $id = $req->idCotizacion;
        // dd($id);
        $pdf = $this->generarPDF($req, 1, $id);
        $loadDatos = new DatosController();
        // $ventaSelect = $loadDatos->getVentaselect($id);
        $ventaSelect = $this->getCotizacionselect($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $rucEmpresa = $empresa->Ruc;
        $numero = $ventaSelect->Numero;
        $serie = $ventaSelect->Serie;
        if ($ventaSelect->IdTipoDocumento == 1) {
            $idDoc = 03;
        }
        if ($ventaSelect->IdTipoDocumento == 2) {
            $idDoc = 01;
        }
        if ($ventaSelect->IdTipoDocumento == 3) {
            $idDoc = 12;
        }
        $fechaCreacionPdf = Carbon::now()->toDateTimeString();
        $nombrePdf = "$serie-$numero";
        $directorio = "/PdfWhatsApp/Cotizaciones/";
        $urlPdf = $this->storePdfWhatsAppS3($pdf, $nombrePdf, $directorio, $empresa->Ruc);
        $array = ['UrlPdf' => $urlPdf, 'FechaCreacionPdf' => $fechaCreacionPdf];
        DB::table('cotizacion')
            ->where('IdCotizacion', $id)
            ->update($array);
        // $mensajeUrl = '¡Hola%20Gracias%20por%20confiar%20en%20nuestra%20Empresa!%20🥳%0A%0A☝️%20Te%20enviamos%20la%20cotización%20solicitada%20de%20acuerdo%20a%20tu%20requerimiento,%20podrás%20descargarlo%20en%20el%20link%20de%20la%20parte%20inferior, %20este%20enlace%20solo%20estará%20disponible%20por%2030%20días.%20📄%20🙌%0A%0A 📞%20Si%20tienes%20alguna%20duda%20o%20consulta,%20no%20dudes%20en%20comunicarte%20con%20nuestro%20Centro%20de%20Servicio%20al%20Cliente,%20con%20tus%20asesores%20de%20siempre%20que%20estarán%20gustos%20en%20atenderte.%0A%0A' . $urlPdf;

        $fechaCotizacion = carbon::parse($ventaSelect->FechaCreacion)->isoFormat('D [de] MMMM [de] YYYY');
        $mensajeUrl = "¡Hola gracias por confiar en nuestra Empresa: *$empresa->NombreComercial* con RUC: *$empresa->Ruc*! 🥳%0A%0A ☝️Te enviamos la cotización, generada el dia: *$fechaCotizacion* de acuerdo a tu requerimiento, podrás descargarlo haciendo click en el link de la parte inferior, este enlace solo estará disponible por 30 días. 📄 🙌 %0A%0A 📞 Si tienes alguna duda o consulta, no dudes en comunicarte con nuestro Centro de Servicio al Cliente al teléfono: *$empresa->Telefono*, con tus asesores de siempre que estarán gustos en atenderte.%0A%0A" . config('variablesGlobales.urlDominioAmazonS3') . $urlPdf;

        if ($this->isMobileDevice()) {
            return redirect('https://api.whatsapp.com/send?phone=+51' . $numeroCelular . '&text=' . $mensajeUrl);
        } else {
            return redirect('https://web.whatsapp.com/send?phone=51' . $numeroCelular . '&text=' . $mensajeUrl);
        }
    }

    private function isMobileDevice()
    {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }

    public function imprimirPDF(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        // Nuevo codigo Imprimir Ticket Amortizacion

        if ($req->selectImpre == null) {
            $tipo = 1;
        } else {
            $tipo = $req->selectImpre;
        }
        // Fin

        $pdf = $this->generarPDF($req, $tipo, $id);
        $loadDatos = new DatosController();
        $ventaSelect = $loadDatos->getVentaselect($id);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $rucEmpresa = $empresa->Ruc;
        $numero = $ventaSelect->Numero;
        $serie = $ventaSelect->Serie;
        // dd($serie);
        if ($ventaSelect->IdTipoComprobante == 1) {
            $idDoc = 03;
        }
        if ($ventaSelect->IdTipoComprobante == 2) {
            $idDoc = 01;
        }
        if ($ventaSelect->IdTipoComprobante == 3) {
            $idDoc = 12;
        }
        return $pdf->stream($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numero . '.pdf');
    }

    public function descargarPDF(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
            $pdf = $this->generarPDF($req, 1, $id);
            $loadDatos = new DatosController();
            $info = [];

            $ventaSelect = $this->getCotizacionselect($id);
            $datosVe = DB::table('cotizacion as c')
                ->join('vehiculo as v', 'c.Campo0', '=', 'v.IdVehiculo')
                ->where('IdCotizacion', $id)
                ->first();

            if ($datosVe) {
                $info = $datosVe;

            }

            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
            $rucEmpresa = $empresa->Ruc;
            $numeroCerosIzquierda = $this->completarCeros($ventaSelect->Numero);
            $serie = $ventaSelect->Serie;
            $idDoc = 'CT';
            return $pdf->download($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numeroCerosIzquierda . '.pdf');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function descargarOrdenPDF(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');

            $pdf = $this->generarPDF($req, 4, $id);
            $loadDatos = new DatosController();
            $info = [];

            $ventaSelect = $this->getCotizacionselect($id);
            $datosVe = DB::table('cotizacion as c')
                ->join('vehiculo as v', 'c.Campo0', '=', 'v.IdVehiculo')
                ->where('IdCotizacion', $id)
                ->first();

            if ($datosVe) {
                $info = $datosVe;

            }

            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
            $rucEmpresa = $empresa->Ruc;
            $numeroCerosIzquierda = $this->completarCeros($ventaSelect->Numero);
            $serie = $ventaSelect->Serie;
            $idDoc = 'CT';
            return $pdf->download($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numeroCerosIzquierda . '.pdf');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function descargarNuevoPDF(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');

            $pdf = $this->generarPDF($req, 5, $id);
            $loadDatos = new DatosController();
            $info = [];

            $ventaSelect = $this->getCotizacionselect($id);
            //dd($ventaSelect);
            /*$datosVe  = DB::table('cotizacion as c')
            ->join('vehiculo as v', 'c.Campo0', '=','v.IdVehiculo' )
            ->where('IdCotizacion', $id)
            ->first();

            if($datosVe)
            {
            $info=$datosVe;

            }*/

            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
            $rucEmpresa = $empresa->Ruc;
            $numeroCerosIzquierda = $this->completarCeros($ventaSelect->Numero);
            $serie = $ventaSelect->Serie;
            $idDoc = 'CT';
            return $pdf->download($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numeroCerosIzquierda . '.pdf');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    public function enviarCorreo(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $info = [];
        /* $tipoCotizacion  = DB::table('cotizacion')
        ->select('IdCotizacion', 'TipoCotizacion')
        ->where('IdCotizacion', $id)
        ->first(); */

        $ventaSelect = $this->getCotizacionselect($id);
        $datosVe = DB::table('cotizacion as c')
            ->join('vehiculo as v', 'c.Campo0', '=', 'v.IdVehiculo')
            ->where('IdCotizacion', $id)
            ->first();

        if ($datosVe) {
            $info = $datosVe;

        }
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $nombreEmpresa = $empresa->Nombre;
        $rucEmpresa = $empresa->Ruc;
        $numero = $ventaSelect->Numero;
        $serie = $ventaSelect->Serie;
        $cod = $serie . '-' . $numero;
        $idDoc = 'CT';
        $pdf = $this->generarPDF($req, 1, $id);
        file_put_contents($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numero . '.pdf', $pdf->output());

        $mail = new PHPMailer();
        //$mail->isSMTP();                                     // Set mailer to use SMTP
        $mail->Host = 'mail.mifacturita.pe'; // Specify main and backup SMTP servers
        //$mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'facturacionelectronica@mifacturita.pe'; // SMTP username
        $mail->Debugoutput = 'html';
        $mail->Password = '@MiFacturita123'; // SMTP password
        $mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465; // TCP port to connect to
        $mail->From = 'facturacionelectronica@mifacturita.pe';
        $mail->FromName = 'TELEPROCESOS DIGITALES QUEFACILITO S.A.C. - Cotizacion Electrónica';
        $mail->addAddress($req->correo, 'Cotizacion'); // Add a recipient

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8'; // Set email format to HTML
        $mail->Subject = 'Envío de Cotizacion';
        $mail->addAttachment($rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numero . '.pdf');
        $tipo = "COTIZACION";
        //$numeroCerosIzquierda = $this->completarCeros($ventaSelect->Numero);
        $fecha = date_create($ventaSelect->FechaCreacion);
        $formatoFecha = date_format($fecha, 'd/m/Y');
        $mail->msgHTML('<table width="100%">'
            . '<tr>'
            . '<td style="border: 1px solid #000;">'
            . '<div align="center" style="background-color: #CCC">'
            . '<img width="150px" style="margin:15px" src="' . $empresa->Imagen . '">'
            . '<img width="150px" style="margin:15px" src="https://s3-us-west-2.amazonaws.com/2019mifacturita/1558678972.png">'
            . '</div>'
            . '<div style="margin-bottom:20px;margin-left:10px">'
            . '<p>Estimado(a),</p>'
            . '</div>'
            . '<div style="margin-bottom:20px;margin-left:10px">'
            . '<p>' . $req->cliente . '</p>'
            . '</div>'
            . '<div style="margin-bottom:10px;margin-left:10px">'
            . '<p>Por encargo del emisor <span style="font-weight: bold;">' . $nombreEmpresa . '</span>, nos es grato remitirle la cotización solicitada :</p>'
            . '</div>'
            . '<hr style="border: 0.5px solid #000;">'
            . '<div style="margin-bottom:20px;margin-left:30px">'
            . '<p><span style="font-weight: bold;">Tipo: ' . $tipo . '</span></p>'
            . '<p><span style="font-weight: bold;">Número: ' . $ventaSelect->Serie . '-' . $numero . '</span></p>'
            . '<p><span style="font-weight: bold;">RUC / DNI: ' . $rucEmpresa . '</span></p>'
            . '<p><span style="font-weight: bold;">Fecha Emisión: ' . $formatoFecha . '</span></p>'
            . '<p><span style="font-weight: bold;">Monto Total: ' . $ventaSelect->Total . '</span></p>'
            . '</div>'
            . '<hr style="border: 0.5px solid #000;">'
            . '<div style="margin-bottom:20px;margin-left:10px">'
            . '</div>'
            . '<hr style="border: 0.5px solid #000;">'
            . '<div style="margin-bottom:20px;margin-left:10px">'
            . '<p><span style="font-weight: bold;">Atentamente</span></p>'
            . '<p><span style="font-weight: bold;">AGRADECEREMOS NO RESPONDER ESTE CORREO</span></p>'
            . '<p><span style="font-weight: bold;">Si deseas ser Emisor Electrónico contáctanos o escríbenos al correo informes@mifacturita.pe</span></p>'
            . '</div>'
            . '</td>'
            . '</tr>'
            . '</table>');

        $enviado = $mail->send();
        if ($enviado) {
            return back()->with('status', 'Se envio correo con éxito');
        } else {
            return back()->with('error', 'No se pudo enviar correo');
        }
    }

    public function verificarTipoCambio(Request $req)
    {
        if ($req->ajax()) {
            $idSucursal = Session::get('idSucursal');
            $loadDatos = new DatosController();
            $fecha = Carbon::today();

            $data = DB::table('tipo_cambio')
                ->where('FechaCreacion', $fecha)
                ->where('IdSucursal', $idSucursal)
                ->get();

            return Response($data);
        }
    }

    public function saveVenta(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                if ($req->ajax()) {
                    $stockSuficiente = $this->verificarStockSuficiente($req);
                    if (count($stockSuficiente) > 0) {
                        return Response(['alert1', 'Quedan ' . $stockSuficiente[1] . ' unidades en stock de : ' . $stockSuficiente[0]]);
                    } else {
                        $idUsuario = Session::get('idUsuario');
                        $serie = $req->serie;
                        $idTipoSunat = 'NT';
                        if ($serie == null) {
                            return Response(['alert1', 'Por favor, completar serie y número correlativo']);
                        }
                        $numero = $req->numero;
                        $idTipoComp = $req->idTipoComp;
                        if ($idTipoComp == 0) {
                            return Response(['alert1', 'Por favor, elegir Tipo de comprobante']);
                            //return back()->with('error','Por favor, elegir Tipo de comprobante')->withInput($req->all());
                        }
                        if ($req->facturarCliente != null) {
                            if ($req->facturarCliente == 'on') {
                                $idCliente = $req->cliente;
                                $seguro = null;
                            } else {
                                $idCliente = $req->idC;
                                $seguro = $req->idSeguro;
                            }
                        } else {
                            $idCliente = $req->cliente;
                            $seguro = null;
                        }

                        if ($idCliente == 0) {
                            return Response(['alert1', 'Por favor, elegir Cliente']);
                            //return back()->with('error','Por favor, elegir Cliente')->withInput($req->all());
                        }
                        if ($req->Id == null) {
                            return Response(['alert1', 'Por favor, agrege productos o servicios']);
                            //return back()->with('error','Por favor, agrege productos o servicios')->withInput($req->all());
                        }
                        $total = $req->total;
                        $amortizacion = $req->amortizacionTotal;
                        $req->fechaEmitida = date('Y-m-d');
                        $fecha = $req->fechaEmitida;
                        if ($fecha == null) {
                            return Response(['alert1', 'Por favor, ingresar fecha de venta']);
                            //return back()->with('error','Por favor, ingresar fecha de venta');
                        }

                        $ctzn = DB::table('cotizacion')
                            ->select('IdCotizacion', 'IdCliente', 'Campo0', 'Total', 'IdTipoMoneda', 'ProximoMantenimiento', 'IdTipoAtencion', 'PeriodoProximoMantenimiento')
                            ->where('IdCotizacion', $req->cotizacion)
                            ->first();

                        $cotiTotal = $ctzn->Total;

                        $vnt = DB::table('ventas')
                            ->select(DB::raw('SUM(Exonerada) as DescuentoTotal'), DB::raw('SUM(Total) as SumaTotal'))
                            ->where('IdCotizacion', $req->cotizacion)
                            ->get();

                        if (count($vnt) > 0) {
                            $totalSuma = floatval($vnt[0]->SumaTotal) + floatval($vnt[0]->DescuentoTotal) + floatval($amortizacion);
                            if (floatval($cotiTotal) <= floatval($totalSuma + 0.1)) {
                                return Response(['alert1', 'La cotización ya se encuentra Finalizada']);
                            }
                        }

                        $numero = $this->completarCeros($numero);
                        $idSucursal = Session::get('idSucursal');
                        $verificar = $this->verificarCodigo($serie, $numero, $idSucursal);
                        if ($verificar->Cantidad > 0) {
                            $ultimoCorrelativo = $this->ultimoCorrelativo($idUsuario, $idSucursal, $idTipoComp);
                            $sumarCorrelativo = intval($ultimoCorrelativo->Numero) + 1;
                            $numero = $this->completarCeros($sumarCorrelativo);

                        }

                        $date = DateTime::createFromFormat('Y-m-d', $fecha);
                        $fechaConvertida = $date->format("Y-m-d H:i:s");

                        $idTipoMoneda = $req->TipoMoneda;
                        $tipoVenta = $req->tipoVenta;
                        if ($tipoVenta == 1) {
                            $subtotal = $req->subtotal;
                        } else {
                            $subtotal = $req->opExonerado;
                        }

                        $valorCambioVentas = $req->valorCambioVentas;
                        $valorCambioCompras = $req->valorCambioCompras;
                        $valorDetraccion = $req->valorDetraccion;
                        $detraccion = $req->detraccion;
                        $retencion = $req->retencion;
                        $ordenCompra = $req->ordenCompra;
                        $codBienServicio = null;
                        $codMedioPago = null;
                        $loadDatos = new DatosController();
                        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
                        $tipoPago = $req->tipoPago;

                        if ($detraccion == 1) {
                            $cuentaDetraccion = $loadDatos->getCuentaDetracciones($usuarioSelect->CodigoCliente, 9);
                            if ($cuentaDetraccion == null) {
                                return Response(['alert12', 'Por favor, para ventas con detracciones es obligaotorio crear una cuenta de Detracciones']);
                            }
                            $codBienServicio = $req->bienServicio;
                            $codMedioPago = $req->medioPago;
                        }
                        /*if($ordenCompra == null || $ordenCompra == ""){
                        $ordenCompra = "-";
                        }*/
                        //$valorCambio = $req->valorCambio;
                        /*$valorVentaSoles = $req->valorVentaSoles;
                        if($valorVentaSoles == 1 && floatval($valorCambio) > 0){
                        $cambioSoles = 1;
                        $idTipoMoneda = 1;
                        }else{
                        $cambioSoles = 0;
                        }*/
                        if ($idTipoMoneda == 1) {
                            $totalDetrac = floatval($total);
                        } else {
                            $totalDetrac = floatval($total * $valorCambioVentas);
                        }
                        $exonerada = $req->exonerada;
                        $observacion = $req->observacion;

                        if ($exonerada == '-') {
                            $exonerada = '0.00';
                        }
                        if ($req->tipoCotizacion == 2) {
                            $placa = $req->placa;
                        } else {
                            $placa = '';
                        }
                        $cliente = DB::table('cliente')
                            ->where('IdCliente', $idCliente)
                            ->where('Estado', 'E')
                            ->first();
                        if ($tipoPago == 1) {
                            $plazoCredito = '';
                            $pagoEfect = $req->pagoEfectivo;
                            $tipoTarjeta = $req->tipoTarjeta;
                            $numTarjeta = $req->numTarjeta;
                            $pagoTarjeta = $req->pagoTarjeta;
                            $vueltoEfectivo = $req->vueltoEfectivo;
                            $cuentaBancaria = $req->CuentaBancaria;
                            $montoCuenta = $req->MontoCuenta;
                            if (floatval($pagoTarjeta) > 0) {
                                if ($numTarjeta == '' || $numTarjeta == null) {
                                    return Response(['alert1', 'Completar Numero de Tarjeta']);
                                }
                            }
                            $pagoEfectivo = floatval($pagoEfect) - floatval($vueltoEfectivo);
                            $pagoTotal = floatval($pagoEfectivo) + floatval($pagoTarjeta) + floatval($montoCuenta);
                            $_total = floatval($total) - floatval($amortizacion);
                            $_pagoTotal = round($pagoTotal, 2);
                            $_total = round($_total, 2);
                            if ($_pagoTotal != $_total) {
                                return Response(['alert1', 'La suma de pago efectivo, con tarjeta y tranferencia bancaria debe ser igual al Importe Total']);
                            }
                        } else {
                            //aqui es  donde  debe  verse  el saldo
                            $contCredito = 0;
                            $_total = floatval($total);
                            $ventaCliente = DB::table('ventas')
                                ->where('IdCliente', $idCliente)
                                ->where('IdSucursal', $idSucursal)
                                ->where(function ($query) {
                                    $query->whereNull('ventas.MotivoAnulacion')
                                        ->orWhere('ventas.MotivoAnulacion', '=', '');
                                })
                                ->where('ventas.Nota', '!=', 1)
                                ->where('IdTipoPago', 2)
                                ->get();

                            if (count($ventaCliente) >= 1) {
                                foreach ($ventaCliente as $venta) {
                                    $deuda = DB::table('fecha_pago')
                                        ->where('IdVenta', $venta->IdVentas)
                                        ->first();

                                    if ($deuda) {
                                        $contCredito = $contCredito + ($deuda->Importe - $deuda->ImportePagado); //suma el credito dado al cliente
                                    }
                                }
                            }

                            if ($cliente) {
                                if ($cliente->BandSaldo == 1) {
                                    $saldoCredito = $cliente->SaldoCredito - ($contCredito + $req->total);
                                    if ($saldoCredito < 0) {
                                        //return Response(['alert8','El cliente '.$cliente->Nombre.'  con esta  venta sobrepasa su saldo para creditos']);
                                        return Response(['alert1', 'El cliente ' . $cliente->Nombre . '  con esta  venta sobrepasa su saldo para creditos, Su Linea de Credito Total es : ' . $cliente->SaldoCredito . ' Su monto usado hasta el momento es de : ' . $contCredito . '. Maximo de credito a  entregar en esta compra es de : ' . ($req->total - abs($saldoCredito))]);
                                    }
                                }
                            }

                            $plazoCredito = $req->plazoCredito;

                            if (!is_numeric($plazoCredito)) {
                                $plazoCredito = 1;
                            }

                            $pagoEfectivo = '';
                            $tipoTarjeta = '';
                            $numTarjeta = '';
                            $pagoTarjeta = '';
                            $montoCuenta = '';
                        }

                        $igv = $req->igv;
                        $estado = 'Sin Valor Tributario';
                        $idEstadoCotizacion = $req->idEstadoCotizacion;
                        $loadDatos = new DatosController();
                        $tipoMoneda = $loadDatos->getTipoMonedaSelect($idTipoMoneda);
                        $caja = $loadDatos->getCierreCaja($idSucursal, $idUsuario);
                        if ($caja == null) {
                            return Response(['alert9', 'Abrir caja antes de realizar una venta']);
                        } else {
                            $noReduceStock = 0;
                            $codigoAceptado = '';
                            $bandera = 1;
                            $resumen = '';
                            $hash = '';
                            $mensaje = 'Se genero Ticket con éxito';
                            if (intval($idTipoComp) < 3) {
                                $estado = 'Pendiente';
                                if (intval($idTipoComp) == 1) {
                                    $idTipoSunat = '03';
                                } else {
                                    $idTipoSunat = '01';
                                }
                            } else {
                                $estado = 'Sin Valor Tributario';
                            }

                            $array = ['IdCliente' => $idCliente, 'IdTipoMoneda' => $idTipoMoneda, 'Idsucursal' => $idSucursal, 'FechaCreacion' => $fechaConvertida, 'IdCreacion' => $idUsuario, 'IdTipoComprobante' => $idTipoComp, 'IdTipoSunat' => $idTipoSunat,
                                'IdTipoPago' => $tipoPago, 'PlazoCredito' => $plazoCredito, 'MontoEfectivo' => $pagoEfectivo, 'IdTipoTarjeta' => $tipoTarjeta, 'NumeroTarjeta' => $numTarjeta, 'MontoTarjeta' => $pagoTarjeta, 'MontoCuentaBancaria' => $montoCuenta, 'TipoVenta' => $tipoVenta, 'IdCotizacion' => $req->cotizacion, 'Seguro' => $seguro, 'Placa' => $placa,
                                'Serie' => $serie, 'Numero' => $numero, 'Observacion' => $observacion, 'Subtotal' => $subtotal, 'Exonerada' => $exonerada, 'IGV' => $igv, 'Total' => $_total, 'OrdenCompra' => $ordenCompra, 'Detraccion' => $detraccion, 'CodDetraccion' => $codBienServicio, 'CodMedioPago' => $codMedioPago, 'PorcentajeDetraccion' => $valorDetraccion, 'Retencion' => $retencion, 'Amortizacion' => floatval($amortizacion), 'Resumen' => $resumen, 'Hash' => $hash, 'Nota' => 0, 'Guia' => 0, 'CodigoDoc' => $codigoAceptado, 'Estado' => $estado];

                            DB::table('ventas')->insert($array);

                            $venta = DB::table('ventas')
                                ->orderBy('IdVentas', 'desc')
                                ->first();
                            $idVenta = $venta->IdVentas;

                            if ($tipoPago == 2) {
                                $interes = $req->interes;
                                $this->guardarFechasPago($fecha, $plazoCredito, $idVenta, $_total, $interes);
                            } else {
                                if (intval($cuentaBancaria) > 0) {
                                    $numeroOp = $req->nroOperacion;
                                    $montoCuenta = $req->MontoCuenta;
                                    $dateBanco = $req->DateBanco;
                                    if ($dateBanco == null || $dateBanco == "") {
                                        $fechaBanco = $fechaConvertida;
                                    } else {
                                        $fechaBanco = Carbon::createFromFormat('d/m/Y', $dateBanco)->format('Y-m-d');
                                    }
                                    if (floatval($montoCuenta) > 0) {
                                        $this->guardaDetallesCuentaBancaria($cuentaBancaria, $montoCuenta, $numeroOp, $fechaBanco, $serie, $numero, $cliente->RazonSocial, $idSucursal);
                                    }
                                }
                            }

                            //if($noReduceStock==0)
                            //{
                            $arrayCaja = ['IdCaja' => $caja->IdCaja, 'IdVentas' => $idVenta];
                            DB::table('caja_ventas')->insert($arrayCaja);
                            //}

                            $cantidadRestada = 0;
                            $cantidadVentaReal = 1; // puse esto para contener si hay algun error
                            $bandTipo = 0;
                            $bandGan = 0; //esto es para controlar la ganancia
                            $condicionDetrac = 0;
                            for ($i = 0; $i < count($req->Id); $i++) {
                                $producto = substr($req->Codigo[$i], 0, 3);
                                // NUEVO CODIGO PARA ALMACENAR LOS ARTICULOS QUE SE ENCUENTRAN DENTRO DEL PAQUETE PROMOCIONAL
                                if ($producto == 'PAQ') {
                                    //getItemsPaquetePromocional
                                    $productoSelect = DB::table('paquetes_promocionales AS pp')
                                        ->where('pp.IdPaquetePromocional', $req->Id[$i])
                                        ->first();

                                    $productoSelectDatos = $loadDatos->getItemsPaquetePromocional($req->Id[$i]);
                                    $idPaquetePromocional = $req->Id[$i];
                                    for ($j = 0; $j < count($productoSelectDatos); $j++) {
                                        $productoSelectItem = $loadDatos->getProductoSelect($productoSelectDatos[$j]->IdArticulo);
                                        if ($productoSelectItem->IdTipo == 1) {
                                            if ($idEstadoCotizacion == 1 || $idEstadoCotizacion == 5) {
                                                $cantidadRestada = $productoSelectItem->Stock - $productoSelectDatos[$j]->cantidad;
                                                DB::table('articulo')
                                                    ->where('IdArticulo', $productoSelectDatos[$j]->IdArticulo)
                                                    ->update(['Stock' => $cantidadRestada]);

                                                $kardex = array(
                                                    'CodigoInterno' => $productoSelectItem->CodigoInterno,
                                                    'fecha_movimiento' => $fechaConvertida,
                                                    'tipo_movimiento' => 1,
                                                    'usuario_movimiento' => $idUsuario,
                                                    'documento_movimiento' => $serie . '-' . $numero,
                                                    'existencia' => $cantidadRestada,
                                                    'costo' => $productoSelectItem->Precio,
                                                    'IdArticulo' => $productoSelectDatos[$j]->IdArticulo,
                                                    'IdSucursal' => $idSucursal,
                                                    'Cantidad' => $productoSelectDatos[$j]->cantidad,
                                                    'Descuento' => 0,
                                                    'ImporteEntrada' => 0,
                                                    'ImporteSalida' => $productoSelectDatos[$j]->Precio,
                                                    'estado' => 1,
                                                );
                                                DB::table('kardex')->insert($kardex);

                                                $this->actualizarStock($productoSelectDatos[$j]->IdArticulo, $producto, $productoSelectDatos[$j]->cantidad);
                                            }
                                        }
                                    }
                                } else {
                                    $productoSelect = $loadDatos->getProductoSelect($req->Id[$i]);
                                    $idPaquetePromocional = null;
                                }
                                // FIN
                                if ($productoSelect->IdTipo == 1 && $producto == 'PRO') {
                                    $cantidadVentaReal = 1;
                                    $cantidadRestada = $productoSelect->Stock - $req->Cantidad[$i];
                                    $bandTipo = 1;
                                    $precio = floatval($req->Precio[$i]);
                                    $costo = floatval($productoSelect->Costo);
                                    if ($idTipoMoneda == 1 && $productoSelect->IdTipoMoneda == 2) {
                                        if (floatval($productoSelect->ValorTipoCambio) > 0) {
                                            $costo = floatval($costo * $productoSelect->ValorTipoCambio);
                                        } else {
                                            $costo = floatval($costo * $valorCambioVentas);
                                        }
                                    }
                                    if ($idTipoMoneda == 2 && $productoSelect->IdTipoMoneda == 1) {
                                        $costo = floatval($costo / $valorCambioCompras);
                                    }

                                    if ($productoSelect->TipoOperacion == 2) {
                                        $costo = floatval($costo / 1.18);
                                    }
                                    $bandGan = $precio - $costo;
                                    $newGanancia = floatval($bandGan * $req->Cantidad[$i]) - floatval($req->Descuento[$i]);
                                    if ($idEstadoCotizacion == 1 || $idEstadoCotizacion == 5) {
                                        DB::table('articulo')
                                            ->where('IdArticulo', $req->Id[$i])
                                            ->update(['Stock' => $cantidadRestada]);

                                        $kardex = array(
                                            'CodigoInterno' => $productoSelect->CodigoInterno,
                                            'fecha_movimiento' => $fechaConvertida,
                                            'tipo_movimiento' => 1,
                                            'usuario_movimiento' => $idUsuario,
                                            'documento_movimiento' => $serie . '-' . $numero,
                                            'existencia' => $cantidadRestada,
                                            'costo' => $productoSelect->Precio,
                                            'IdArticulo' => $req->Id[$i],
                                            'IdSucursal' => $idSucursal,
                                            'Cantidad' => $req->Cantidad[$i],
                                            'Descuento' => $req->Descuento[$i],
                                            'ImporteEntrada' => 0,
                                            'ImporteSalida' => $req->Importe[$i],
                                            'estado' => 1,
                                        );
                                        DB::table('kardex')->insert($kardex);

                                        $this->actualizarStock($req->Id[$i], $producto, $req->Cantidad[$i]);
                                    }
                                } else {
                                    $condicionDetrac = 1;
                                    $costo = floatval($productoSelect->Costo);
                                    if ($idTipoMoneda == 1 && $productoSelect->IdTipoMoneda == 2) {
                                        $costo = floatval($costo * $valorCambioVentas);
                                    }
                                    if ($idTipoMoneda == 2 && $productoSelect->IdTipoMoneda == 1) {
                                        $costo = floatval($costo / $valorCambioCompras);
                                    }
                                    $newGanancia = floatval(($req->Precio[$i] - $costo) * $req->Cantidad[$i]) - $req->Descuento[$i];
                                }

                                $arrayRelacion = ['IdVentas' => $idVenta, 'IdArticulo' => $req->Id[$i], 'Codigo' => $req->Codigo[$i], 'Detalle' => $req->Detalle[$i], 'Descuento' => $req->Descuento[$i], 'Cantidad' => $req->Cantidad[$i], 'CantidadReal' => $cantidadVentaReal, 'VerificaTipo' => $bandTipo, 'Ganancia' => $newGanancia, 'Importe' => $req->Importe[$i], 'TextUnidad' => $req->TextUnida[$i], 'PrecioUnidadReal' => $req->Precio[$i], 'IdPaquetePromocional' => $idPaquetePromocional];
                                DB::table('ventas_articulo')->insert($arrayRelacion);
                                $cantidadVentaReal = 1;
                                $bandTipo = 0;
                                $bandGan = 0;
                                usleep(150000);
                            }

                            $vnt = DB::table('ventas')
                                ->select(DB::raw('SUM(Exonerada) as DescuentoTotal'), DB::raw('SUM(Total) as SumaTotal'))
                                ->where('IdCotizacion', $req->cotizacion)
                                ->get();

                            $totalSuma = floatval($vnt[0]->SumaTotal) + floatval($vnt[0]->DescuentoTotal) + floatval($amortizacion);

                            if ($req->tipoCotizacion == 2) {
                                $operario = $req->operario;
                                $trabajos = $req->trabajos;
                                if (floatval($cotiTotal) <= floatval($totalSuma + 0.1)) {
                                    DB::table('cotizacion')
                                        ->where('IdCotizacion', $req->cotizacion)
                                        ->update(['IdEstadoCotizacion' => 4]);

                                    DB::table('registro_estados')->insert(['IdUsuario' => $idUsuario, 'Idsucursal' => $idSucursal, 'FechaRegistro' => $fechaConvertida, 'IdCotizacion' => $req->cotizacion, 'IdEstadoCotizacion' => 4]);
                                }

                                $array = ['IdSucursal' => $idSucursal, 'IdVehiculo' => $ctzn->Campo0, 'IdCliente' => $idCliente, 'IdReferencia' => $ctzn->IdCliente, 'FechaAtencion' => $fechaConvertida, 'IdVentas' => $idVenta, 'Documento' => $serie . '-' . $numero, 'SubTotal' => $subtotal, 'Exonerada' => $exonerada,
                                    'Igv' => $igv, 'Total' => $total, 'IdOperario' => $operario, 'Trabajos' => $trabajos, 'Kilometro' => $req->kilometro, 'Horometro' => $req->horometro, 'Observacion' => $observacion];

                                DB::table('atencion_vehicular')->insert($array);

                                // Nuevo Codigo para Cronograma Mantenimiento
                                if ($req->moduloCronogramaActivo == "activado") {
                                    if ($ctzn->IdTipoAtencion == 1 || $ctzn->IdTipoAtencion == 6) {
                                        $validacionNotificacion = $this->validarNotificacionVehiculo($req->placa, $idSucursal);
                                        $fechaActual = carbon::now();
                                        $dias = $ctzn->PeriodoProximoMantenimiento;
                                        if ($dias != "" && $ctzn->ProximoMantenimiento != "") {
                                            $proximaFecha = $fechaActual->addDays($dias)->toDateTimeString();
                                            if ($validacionNotificacion) {
                                                if ($validacionNotificacion->IdCotizacion != $req->cotizacion) {
                                                    $arrayNotificacion = ['IdSucursal' => $idSucursal, 'IdVehiculo' => $ctzn->Campo0, 'PlacaVehiculo' => $req->placa, 'FechaSalida' => $fechaConvertida,
                                                        'IdCotizacion' => $req->cotizacion, 'ProximaFecha' => $proximaFecha, 'Periodo' => $dias, 'ProximoMantenimiento' => $ctzn->ProximoMantenimiento,
                                                        'DiasAvanzados' => 0, 'DiasRestantes' => $dias, 'Estado' => 'Km Inical', 'ColorEstado' => '#28A745'];
                                                    DB::table('notificacion_mantenimiento')
                                                        ->where('IdSucursal', $idSucursal)
                                                        ->where('PlacaVehiculo', $req->placa)
                                                        ->update($arrayNotificacion);
                                                }
                                            } else {
                                                $arrayNotificacion = ['IdSucursal' => $idSucursal, 'IdVehiculo' => $ctzn->Campo0, 'PlacaVehiculo' => $req->placa, 'FechaSalida' => $fechaConvertida, 'IdCotizacion' => $req->cotizacion, 'ProximaFecha' => $proximaFecha, 'Periodo' => $dias, 'ProximoMantenimiento' => $ctzn->ProximoMantenimiento, 'DiasAvanzados' => 0, 'DiasRestantes' => $dias];
                                                DB::table('notificacion_mantenimiento')->insert($arrayNotificacion);
                                            }
                                        }
                                    }
                                }
                                // Fin

                            } else {
                                if (floatval($cotiTotal) <= floatval($totalSuma + 0.1)) {
                                    DB::table('cotizacion')
                                        ->where('IdCotizacion', $req->cotizacion)
                                        ->update(['IdEstadoCotizacion' => 4]);

                                    DB::table('registro_estados')->insert(['IdUsuario' => $idUsuario, 'Idsucursal' => $idSucursal, 'FechaRegistro' => $fechaConvertida, 'IdCotizacion' => $req->cotizacion, 'IdEstadoCotizacion' => 4]);
                                }
                            }

                            if (intval($idTipoComp) < 3) {
                                $opcionFactura = DB::table('usuario')
                                    ->select('OpcionFactura')
                                    ->where('IdUsuario', $idUsuario)
                                    ->first();
                                $config = new config();
                                if ($opcionFactura->OpcionFactura > 0) {
                                    if ($opcionFactura->OpcionFactura == 1) { //sunat {
                                        $see = $config->configuracion(SunatEndpoints::FE_BETA);
                                    } else if ($opcionFactura->OpcionFactura == 2) { //ose {
                                        $see = $config->configuracion('https://demo-ose.nubefact.com/ol-ti-itcpe/billService?wsdl');
                                    } else {
                                        return Response(['error', 'No  tiene Elejida la  ruta de  envio.... comuniquese con  el administrador']);
                                    }
                                } else {
                                    return Response(['error', 'No  tiene Elejida  el tipo de  envio.... comuniquese con  el administrador']);
                                }

                                if ($idTipoComp == 1) {
                                    $idTipoSunat = '03';
                                    $fecha = $req->fechaEmitida;
                                    $date = DateTime::createFromFormat('Y-m-d', $fecha);

                                    //$see = $config->configuracion('https://demo-ose.nubefact.com/ol-ti-itcpe/billService?wsdl');
                                    $loadDatos = new DatosController();
                                    $cliente = $loadDatos->getClienteSelect($req->cliente);

                                    $client = new Client();
                                    $client->setTipoDoc(strval($cliente->CodigoSunat)) //agregado
                                        ->setNumDoc($cliente->NumeroDocumento)
                                        ->setRznSocial($cliente->RazonSocial);

                                    // Emisor
                                    $sucursal = $loadDatos->getSucursalSelect($idSucursal);

                                    $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
                                    $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);

                                    $address = new Address();
                                    $address->setUbigueo($empresa->Ubigeo)
                                        ->setDepartamento($empresa->Departamento)
                                        ->setProvincia($empresa->Provincia)
                                        ->setDistrito($empresa->Distrito)
                                        ->setUrbanizacion('NONE')
                                        ->setCodLocal($sucursal->CodFiscal)
                                        ->setDireccion($sucursal->DirPrin);
                                    // ->setDireccion($sucursal->Direccion);

                                    $company = new Company();
                                    $company->setRuc($empresa->Ruc)
                                        ->setRazonSocial($empresa->Nombre)
                                        ->setNombreComercial('NONE')
                                        ->setAddress($address);

                                    /* $exonerada = $req->exonerada;
                                    if($exonerada == '-'){
                                    $exonerada = '0.00';
                                    }  */

                                    $exonerada = 0;

                                    $total = floatval($req->total) - floatval($exonerada);

                                    $invoice = (new Invoice())
                                        ->setUblVersion('2.1')
                                        ->setTipoOperacion('0101') // Catalog. 51
                                        ->setTipoDoc('03')
                                        ->setSerie($req->serie)
                                        ->setCorrelativo($req->numero)
                                        ->setFechaEmision($date)
                                        ->setTipoMoneda($tipoMoneda->CodigoMoneda)
                                        ->setClient($client)
                                        ->setMtoOperGravadas(floatval($req->subtotal)) // Subtotal
                                        ->setMtoOperExoneradas(floatval($req->opExonerado))
                                        ->setMtoIGV(floatval($req->igv))
                                        ->setTotalImpuestos(floatval($req->igv))
                                        ->setValorVenta(floatval($subtotal)) //->setValorVenta(floatval($req->total))
                                        ->setSubTotal($total)
                                        ->setMtoImpVenta($total)
                                        ->setCompany($company);

                                    if ($ordenCompra != null && $ordenCompra != "") {
                                        $invoice->setCompra($ordenCompra);
                                    }
                                    $array = [];
                                    $res = [];

                                    for ($i = 0; $i < count($req->Id); $i++) {
                                        $producto = substr($req->Codigo[$i], 0, 3);
                                        if ($producto == 'PAQ') {
                                            $productoSelect = DB::table('paquetes_promocionales AS pp')
                                                ->where('pp.IdPaquetePromocional', $req->Id[$i])
                                                ->first();

                                            $medidaSunat = 'ZZ';
                                            $descripcion = $productoSelect->NombrePaquete;
                                        } else {
                                            $productoSelect = $loadDatos->getProductoSelect($req->Id[$i]);

                                            $medidaSunat = $productoSelect->MedidaSunat;
                                            $descripcion = $productoSelect->Descripcion;
                                        }
                                        if ($req->Tipo[$i] == 1 || $req->Tipo[$i] == 3 || $req->Tipo[$i] == 4) {
                                            $newCantidad = $req->Cantidad[$i];
                                        } else if ($req->Tipo[$i] == 2) {
                                            $newCantidad = $req->Cantidad[$i] * $productoSelect->CantidadTipo;
                                        }
                                        //$subTotalItem = floatval($productoSelect->Precio/1.18);
                                        //$newCantidad=

                                        $valorUniDescuento = floatval($req->Importe[$i] / $newCantidad);
                                        if ($tipoVenta == 1) {
                                            $subTotalItem = floatval($valorUniDescuento / 1.18);
                                            $afectIgv = '10';
                                            $porcentaje = 18;
                                        } else {
                                            $subTotalItem = floatval($valorUniDescuento);
                                            $afectIgv = '20';
                                            $porcentaje = 0;
                                        }

                                        $igvItem = $valorUniDescuento - $subTotalItem;
                                        $mtoValorVenta = floatval($newCantidad * $subTotalItem);
                                        $igvTotal = floatval($newCantidad * $igvItem);
                                        $item = (new SaleDetail())
                                            ->setCodProducto($req->Codigo[$i])
                                            ->setUnidad($medidaSunat)
                                            ->setCantidad($newCantidad)
                                            ->setDescripcion($descripcion)
                                            ->setMtoBaseIgv(round($mtoValorVenta, 5))
                                            ->setPorcentajeIgv($porcentaje) // 18%
                                            ->setIgv($igvTotal)
                                            ->setTipAfeIgv($afectIgv)
                                            ->setTotalImpuestos(round($igvTotal, 5))
                                            ->setMtoValorVenta(round($mtoValorVenta, 5))
                                            ->setMtoValorUnitario(round($subTotalItem, 5))
                                            ->setMtoPrecioUnitario(round($valorUniDescuento, 5));

                                        if ($req->tipoCotizacion == 2) {
                                            $item->setAtributos([(new DetailAttribute())
                                                    ->setName('Gastos Art. 37 Renta: Número de Placa')
                                                    ->setCode('7000')
                                                    ->setValue($placa)]);
                                        }
                                        array_push($array, $item);
                                        usleep(150000);
                                    }

                                    $convertirLetras = new NumeroALetras();
                                    if ($idTipoMoneda == 1) {
                                        $importeLetras = $convertirLetras->convertir($total, 'soles');
                                    } else {
                                        $importeLetras = $convertirLetras->convertir($total, 'dolares');
                                    }
                                    $legend = (new Legend())
                                        ->setCode('1000')
                                        ->setValue($importeLetras);

                                    $invoice->setDetails($array)
                                        ->setLegends([$legend]);

                                    $xml_string = $see->getXmlSigned($invoice);
                                    //dd($see->getFactory()->getLastXml());
                                    $now = Carbon::now();
                                    $anio = $now->year;
                                    $mes = $now->month;
                                    $_mes = $loadDatos->getMes($mes);
                                    $nombreArchivo = $empresa->Ruc . '-03-' . $req->serie . '-' . $req->numero;
                                    $rutaXml = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/FacturasBoletas/' . $nombreArchivo . '.xml';

                                    $config->writeXml($invoice, $see->getFactory()->getLastXml(), $empresa->Ruc, $anio, $_mes, 1);

                                    $_array = [];
                                    $respuesta = 2;
                                    $doc = new DOMDocument();
                                    $doc->loadXML($xml_string);
                                    $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                                    $date = new DateTime();
                                    $fecha = $date->format('Y-m-d');
                                    $resumen = $empresa->Ruc . '|03|' . $req->serie . '|' . $req->numero . '|' . round($req->igv, 2) . '|' . round($total, 2) . '|' . $fecha . '|' . $cliente->CodigoSunat . '|' . $cliente->NumeroDocumento;
                                    DB::table('ventas')
                                        ->where('IdVentas', $idVenta)
                                        ->update(['Resumen' => $resumen, 'Hash' => $hash, 'RutaXml' => $rutaXml]);
                                    array_push($_array, $hash);
                                    array_push($_array, $resumen);
                                    array_push($_array, $respuesta);

                                    $res = $_array;

                                }
                                if ($idTipoComp == 2) {

                                    $idTipoSunat = '01';
                                    $fecha = $req->fechaEmitida;
                                    $date = DateTime::createFromFormat('Y-m-d', $fecha);

                                    //$config = new config();
                                    //        $see = $config->configuracion('https://demo-ose.nubefact.com/ol-ti-itcpe/billService?wsdl');

                                    //$loadDatos = new DatosController();
                                    if ($venta->Seguro > 2) {
                                        $cliente = DB::table('seguros')
                                            ->join('tipo_documento', 'seguros.IdTipoDocumento', '=', 'tipo_documento.IdTipoDocumento')
                                            ->select('seguros.Descripcion as RazonSocial', 'seguros.NumeroDocumento', 'tipo_documento.CodigoSunat')
                                            ->where('IdSucursal', $idSucursal)
                                            ->where('IdSeguro', $req->idSeguro)
                                            ->first();
                                    } else {
                                        $cliente = $loadDatos->getClienteSelect($req->cliente);
                                    }

                                    $client = new Client();
                                    $client->setTipoDoc(strval($cliente->CodigoSunat)) //agregado
                                        ->setNumDoc($cliente->NumeroDocumento)
                                        ->setRznSocial($cliente->RazonSocial);

                                    // Emisor
                                    $sucursal = $loadDatos->getSucursalSelect($idSucursal);

                                    $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
                                    $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);

                                    $address = new Address();
                                    $address->setUbigueo($empresa->Ubigeo)
                                        ->setDepartamento($empresa->Departamento)
                                        ->setProvincia($empresa->Provincia)
                                        ->setDistrito($empresa->Distrito)
                                        ->setUrbanizacion('NONE')
                                        ->setCodLocal($sucursal->CodFiscal)
                                        ->setDireccion($sucursal->DirPrin);
                                    //   ->setDireccion($sucursal->Direccion);

                                    $company = new Company();
                                    $company->setRuc($empresa->Ruc)
                                        ->setRazonSocial($empresa->Nombre)
                                        ->setNombreComercial('NONE')
                                        ->setAddress($address);

                                    // Venta
                                    /*  $exonerada = $req->exonerada;
                                    if($exonerada == '-'){
                                    $exonerada = '0.00';
                                    }  */
                                    $exonerada = 0;

                                    $total = floatval($req->total) - floatval($exonerada);

                                    $invoice = (new Invoice())
                                        ->setUblVersion('2.1')
                                        //->setTipoOperacion('0101') // Catalog. 51
                                        ->setTipoDoc('01')
                                        ->setSerie($req->serie)
                                        ->setCorrelativo($req->numero)
                                        ->setFechaEmision($date)
                                        ->setTipoMoneda($tipoMoneda->CodigoMoneda)
                                        ->setClient($client)
                                        ->setMtoOperGravadas(floatval($req->subtotal)) // Subtotal
                                        ->setMtoOperExoneradas(floatval($req->opExonerado))
                                        ->setMtoIGV(floatval($req->igv))
                                        ->setTotalImpuestos(floatval($req->igv))
                                        ->setValorVenta(floatval($subtotal)) //->setValorVenta(floatval($req->total))
                                        ->setSubTotal($total)
                                        ->setMtoImpVenta($total)
                                        ->setCompany($company);

                                    if ($ordenCompra != null && $ordenCompra != "") {
                                        $invoice->setCompra($ordenCompra);
                                    }

                                    $array = [];
                                    $res = [];
                                    $legends = [];

                                    for ($i = 0; $i < count($req->Id); $i++) {
                                        $producto = substr($req->Codigo[$i], 0, 3);
                                        if ($producto == 'PAQ') {
                                            $productoSelect = DB::table('paquetes_promocionales AS pp')
                                                ->where('pp.IdPaquetePromocional', $req->Id[$i])
                                                ->first();

                                            $medidaSunat = 'ZZ';
                                            $descripcion = $productoSelect->NombrePaquete;
                                        } else {
                                            $productoSelect = $loadDatos->getProductoSelect($req->Id[$i]);

                                            $medidaSunat = $productoSelect->MedidaSunat;
                                            $descripcion = $productoSelect->Descripcion;
                                        }
                                        if ($req->Tipo[$i] == 1 || $req->Tipo[$i] == 3 || $req->Tipo[$i] == 4) {
                                            $newCantidad = $req->Cantidad[$i];
                                        } else if ($req->Tipo[$i] == 2) {
                                            $newCantidad = $req->Cantidad[$i] * $productoSelect->CantidadTipo;
                                        }
                                        //$subTotalItem = floatval($productoSelect->Precio/1.18);

                                        $valorUniDescuento = floatval(round($req->Importe[$i] / $newCantidad, 2));
                                        if ($tipoVenta == 1) {
                                            $subTotalItem = floatval($valorUniDescuento / 1.18);
                                            $afectIgv = '10';
                                            $porcentaje = 18;
                                        } else {
                                            $subTotalItem = floatval($valorUniDescuento);
                                            $afectIgv = '20';
                                            $porcentaje = 0;
                                        }

                                        $igvItem = $valorUniDescuento - $subTotalItem;
                                        $mtoValorVenta = floatval($newCantidad * floatval($subTotalItem));
                                        $igvTotal = floatval($newCantidad * $igvItem);
                                        $item = (new SaleDetail())
                                            ->setCodProducto($req->Codigo[$i])
                                            ->setUnidad($medidaSunat)
                                            ->setCantidad($newCantidad)
                                            ->setDescripcion($descripcion)
                                            ->setMtoBaseIgv(round($mtoValorVenta, 5))
                                            ->setPorcentajeIgv($porcentaje) // 18%
                                            ->setIgv(round($igvTotal, 5))
                                            ->setTipAfeIgv($afectIgv)
                                            ->setTotalImpuestos(round($igvTotal, 5))
                                            ->setMtoValorVenta(round($mtoValorVenta, 5))
                                            ->setMtoValorUnitario(round($subTotalItem, 5))
                                            ->setMtoPrecioUnitario(round($valorUniDescuento, 5));

                                        if ($req->tipoCotizacion == 2) {
                                            $item->setAtributos([(new DetailAttribute())
                                                    ->setName('Gastos Art. 37 Renta: Número de Placa')
                                                    ->setCode('7000')
                                                    ->setValue($placa)]);
                                        }
                                        array_push($array, $item);
                                        usleep(150000);
                                    }

                                    if ($detraccion == 1) {
                                        $codigoMedioPago = $loadDatos->getCodigoMedioPagoSelect($codMedioPago);
                                        $codigoBS = $loadDatos->getCodigoBienServicioSelect($codBienServicio);

                                        $montoDetraccion = floatval($total * $valorDetraccion / 100);
                                        $cuentaDetraccion = $loadDatos->getCuentaDetracciones($usuarioSelect->CodigoCliente, 9);

                                        $invoice->setDetraccion(
                                            (new Detraction())
                                                ->setCodBienDetraccion($codigoBS->CodigoSunat) // catalog. 54
                                                ->setCodMedioPago($codigoMedioPago->Codigo) // catalog. 59
                                                ->setCtaBanco($cuentaDetraccion->NumeroCuenta)
                                                ->setPercent($valorDetraccion)
                                                ->setMount($montoDetraccion))
                                            ->setTipoOperacion('1001');
                                    } else {
                                        $invoice->setTipoOperacion('0101');
                                    }

                                    if ($tipoPago == 1) {
                                        $invoice->setFormaPago(new FormaPagoContado());
                                        if ($retencion == 1) {
                                            $montoRetencion = floatval($total * 0.03);
                                            $invoice->setDescuentos([
                                                (new Charge())
                                                    ->setCodTipo('62') // Catalog. 53
                                                    ->setMontoBase($total)
                                                    ->setFactor(0.03) // 3%
                                                    ->setMonto(round($montoRetencion, 2)),
                                            ]);
                                        }
                                    } else {
                                        if ($detraccion == 1) {
                                            $totalCredito = floatval($total) - floatval($montoDetraccion);
                                        } else {
                                            if ($retencion == 1) {
                                                $montoRetencion = floatval($total * 0.03);
                                                $totalCredito = floatval($total) - floatval($montoRetencion);
                                                $invoice->setDescuentos([
                                                    (new Charge())
                                                        ->setCodTipo('62') // Catalog. 53
                                                        ->setMontoBase($total)
                                                        ->setFactor(0.03) // 3%
                                                        ->setMonto(round($montoRetencion, 2)),
                                                ]);
                                            } else {
                                                $totalCredito = floatval($total);
                                            }
                                        }
                                        $_date = Carbon::today();
                                        $fechaPago = $_date->addDays($plazoCredito);

                                        $invoice->setFormaPago(new FormaPagoCredito(round($totalCredito, 2)));
                                        $invoice->setCuotas([
                                            (new Cuota())
                                                ->setMonto(round($totalCredito, 2))
                                                ->setFechaPago(new DateTime($fechaPago)),
                                        ]);
                                    }

                                    $convertirLetras = new NumeroALetras();
                                    if ($idTipoMoneda == 1) {
                                        $importeLetras = $convertirLetras->convertir($total, 'soles');
                                    } else {
                                        $importeLetras = $convertirLetras->convertir($total, 'dolares');
                                    }
                                    $legend = (new Legend())
                                        ->setCode('1000')
                                        ->setValue($importeLetras);

                                    array_push($legends, $legend);

                                    if ($detraccion == 1) {
                                        $legend3 = (new Legend())
                                            ->setCode('2006')
                                            ->setValue('Operación sujeta a detracción');

                                        array_push($legends, $legend3);
                                    }

                                    $invoice->setDetails($array)
                                        ->setLegends($legends);

                                    //$see->getXmlSigned($invoice);
                                    //dd($see->getFactory()->getLastXml());
                                    $xml_string = $see->getXmlSigned($invoice);
                                    //dd($see->getFactory()->getLastXml());
                                    $now = Carbon::now();
                                    $anio = $now->year;
                                    $mes = $now->month;
                                    $_mes = $loadDatos->getMes($mes);
                                    $nombreArchivo = $empresa->Ruc . '-01-' . $req->serie . '-' . $req->numero;
                                    $rutaXml = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/FacturasBoletas/' . $nombreArchivo . '.xml';

                                    DB::table('ventas')
                                        ->where('IdVentas', $idVenta)
                                        ->update(['RutaXml' => $rutaXml]);

                                    $config->writeXml($invoice, $see->getFactory()->getLastXml(), $empresa->Ruc, $anio, $_mes, 1);
                                    $result = $see->send($invoice);

                                    if ($result->isSuccess()) {
                                        //$config->writeXml($invoice, $see->getFactory()->getLastXml());
                                        $rutaCdr = '/RespuestaSunat/' . $anio . '/' . $_mes . '/' . $empresa->Ruc . '/FacturasBoletas/R-' . $nombreArchivo . '.zip';
                                        $cdr = $result->getCdrResponse();
                                        $config->writeCdr($invoice, $result->getCdrZip(), $empresa->Ruc, $anio, $_mes, 1);
                                        $config->showResponse($invoice, $cdr);

                                        $_array = [];
                                        $respuesta = 1;

                                        $isAccetedCDR = $result->getCdrResponse()->isAccepted();
                                        $descripcionCDR = $result->getCdrResponse()->getDescription();
                                        $codeCDR = $result->getCdrResponse()->getCode();

                                        $ver = $codeCDR . '-' . $descripcionCDR . '-' . $isAccetedCDR; //getCdrResponse()->getDescription();  //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                                        //$xml_string = $see->getXmlSigned($invoice);
                                        $doc = new DOMDocument();
                                        $doc->loadXML($xml_string);
                                        $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                                        $date = new DateTime();
                                        $fecha = $date->format('Y-m-d');
                                        $resumen = $empresa->Ruc . '|01|' . $req->serie . '|' . $req->numero . '|' . round($req->igv, 2) . '|' . round($total, 2) . '|' . $fecha . '|' . $cliente->CodigoSunat . '|' . $cliente->NumeroDocumento;
                                        DB::table('ventas')
                                            ->where('IdVentas', $idVenta)
                                            ->update(['Resumen' => $resumen, 'Hash' => $hash, 'RutaCdr' => $rutaCdr]);
                                        array_push($_array, $hash);
                                        array_push($_array, $resumen);
                                        array_push($_array, $respuesta);
                                        array_push($_array, $codeCDR); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                                        array_push($_array, $descripcionCDR); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                                        array_push($_array, $isAccetedCDR); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                                        // return $_array;
                                    } else {
                                        //dd($result);
                                        $_array = [];
                                        if ($result->getError()->getCode() == 'HTTP') {
                                            //return Response(['alert4','Por favor, xxxx productos o servicios  '.$result->getError()->getCode()]);
                                            // echo "<script language='javascript'>alert('Servicio inestable, intentelo en otro momento');</script>";
                                            $respuesta = 2;

                                            $codeOp = -1;
                                            $descripOp = "";
                                            $accepOp = -1;

                                            $doc = new DOMDocument();
                                            $doc->loadXML($xml_string);
                                            $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                                            $date = new DateTime();
                                            $fecha = $date->format('Y-m-d');
                                            $resumen = $empresa->Ruc . '|01|' . $req->serie . '|' . $req->numero . '|' . round($req->igv, 2) . '|' . round($total, 2) . '|' . $fecha . '|' . $cliente->CodigoSunat . '|' . $cliente->NumeroDocumento;
                                            array_push($_array, $hash);
                                            array_push($_array, $resumen);
                                            array_push($_array, $respuesta);
                                            array_push($_array, $codeOp);
                                            array_push($_array, $descripOp);
                                            array_push($_array, $accepOp);

                                        } else {
                                            //echo '<script language="javascript">alert("'.$result->getError()->getMessage().'");</script>';
                                            $respuesta = 1;
                                            $doc = new DOMDocument();
                                            $doc->loadXML($xml_string);
                                            $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;

                                            $descripcionError = $result->getError()->getMessage();
                                            $codeError = $result->getError()->getCode();
                                            $isAccetedError = -1;

                                            //$ver=$descripcionError.'-'.$codeError;  $result->getError();//borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                                            $resumen = $empresa->Ruc . '|01|' . $req->serie . '|' . $req->numero . '|' . round($req->igv, 2) . '|' . round($total, 2) . '|' . $fecha . '|' . $cliente->CodigoSunat . '|' . $cliente->NumeroDocumento;
                                            //------------    return Response(['verificar','Se debe  en '.$result->getError()->getCode().' verificara la valides de este Documento', $TmpidVenta]);

                                            array_push($_array, $hash);
                                            array_push($_array, $resumen);
                                            array_push($_array, $respuesta);
                                            array_push($_array, $codeError); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                                            array_push($_array, $descripcionError); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                                            array_push($_array, $isAccetedError); //borrarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
                                        }
                                        // return $_array;
                                    }

                                    //$res = $this->obtenerXMLFactura($req);
                                    //return $res;

                                    $res = $_array;
                                }

                                if ($res[2] == 0) {
                                    $bandera = 0;
                                    $mensaje = $res[1];
                                } else {
                                    if ($res[2] == 1) { //es  enviado y recibido......

                                        if (intval($res[3]) == 0) {
                                            $codigoAceptado = $res[3];
                                            $estado = 'Aceptado';
                                            $mensaje = $res[4];
                                        } else if (intval($res[3]) >= 100 && intval($res[3]) <= 1999) {
                                            //$bandera = 0;
                                            $codigoAceptado = $res[3];
                                            $estado = 'Pendiente';
                                            $mensaje = $res[5] . '-' . $res[4] . '-' . $res[3];

                                        } else if (intval($res[3]) >= 2000 && intval($res[3]) <= 3999) {
                                            $noReduceStock = 1;
                                            $codigoAceptado = $res[3];
                                            $estado = 'Rechazo';
                                            $mensaje = $res[4];
                                        } else if (intval($res[3]) >= 4000) {
                                            $codigoAceptado = $res[3];
                                            $estado = 'Observado';
                                            $mensaje = $res[4]; //'La Factura '.$serie.'-'.$numero.', Ha sido Aceptado';
                                        } else {
                                            $codigoAceptado = $res[3];
                                            $estado = 'Pendiente';
                                            $mensaje = 'Se generó Factura pero no se pudo enviar a Sunat ';
                                        }

                                        //$mensaje = $res[3];  //'Se generó Factura y se envio a Sunat con éxito';
                                    }
                                    if ($res[2] == 2) {
                                        $estado = 'Pendiente';
                                        $codigoAceptado = '-';
                                        if (intval($idTipoComp) == 1) {
                                            $mensaje = 'Se generó Boleta y se guardo con éxito'; //$res[1];
                                        } else {
                                            $mensaje = 'Se generó Factura pero no se pudo enviar a Sunat';
                                        }
                                    }
                                    //$hash = $res[0];
                                    //$resumen = $res[1];
                                }
                            }

                            if ($bandera == 1) {

                                if (intval($idTipoComp) == 2) {
                                    DB::table('ventas')
                                        ->where('IdVentas', $idVenta)
                                        ->update(['CodigoDoc' => $codigoAceptado, 'Estado' => $estado]);

                                    if ($estado == 'Rechazo') {
                                        $itemasVentaSelect = $loadDatos->getItemsVentas($idVenta);
                                        for ($i = 0; $i < count($itemasVentaSelect); $i++) {
                                            $productoSelect = $loadDatos->getProductoSelect($itemasVentaSelect[$i]->IdArticulo);
                                            $stockSelect = $loadDatos->getProductoStockSelect($itemasVentaSelect[$i]->IdArticulo);
                                            if ($productoSelect->IdTipo == 1) {
                                                if ($itemasVentaSelect[$i]->VerificaTipo != 1) {
                                                    $newCantidad = intval($itemasVentaSelect[$i]->Cantidad * $itemasVentaSelect[$i]->CantidadReal);
                                                } else {
                                                    $newCantidad = intval($itemasVentaSelect[$i]->Cantidad);
                                                }

                                                DB::table('articulo')
                                                    ->where('IdArticulo', $productoSelect->IdArticulo)
                                                    ->increment('Stock', $newCantidad);

                                                DB::table('stock')
                                                    ->where('IdStock', $stockSelect[0]->IdStock)
                                                    ->increment('Cantidad', $newCantidad);

                                                $kardex = array(
                                                    'CodigoInterno' => $productoSelect->CodigoInterno,
                                                    'fecha_movimiento' => $fechaConvertida,
                                                    'tipo_movimiento' => 17,
                                                    'usuario_movimiento' => $idUsuario,
                                                    'documento_movimiento' => $serie . '-' . $numero,
                                                    'existencia' => $newCantidad,
                                                    'costo' => floatval($itemasVentaSelect[$i]->PrecioUnidadReal),
                                                    'IdArticulo' => $productoSelect->IdArticulo,
                                                    'IdSucursal' => $idSucursal,
                                                    'Cantidad' => floatval($itemasVentaSelect[$i]->Cantidad),
                                                    'Descuento' => floatval($itemasVentaSelect[$i]->Descuento),
                                                    'ImporteEntrada' => floatval($itemasVentaSelect[$i]->Importe),
                                                    'ImporteSalida' => 0,
                                                    'estado' => 1,
                                                );
                                                DB::table('kardex')->insert($kardex);
                                            }
                                        }
                                    }
                                }

                                return Response(['succes', $mensaje, $idVenta]);
                            } else {
                                return Response(['error', $mensaje]);
                                //return redirect()->to('operaciones/ventas/realizar-venta')->with('error', $mensaje);
                            }
                        }
                    }
                }

            } else {
                Session::flush();
                return redirect('/')->with('out', 'Sesión de usuario Expirado');
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function guardaDetallesCuentaBancaria($cuentaBancaria, $montoCuentaBanc, $numeroOp, $fechaBanco, $serie, $numero, $razonSocial, $idSucursal)
    {
        $loadDatos = new DatosController();
        $montoCuenta = $loadDatos->getCuentaCorrienteSelect($cuentaBancaria);
        $montoActual = floatval($montoCuenta->MontoActual) + floatval($montoCuentaBanc);
        //$fechaHoy = $loadDatos->getDateTime();
        $arrayDatos = ['FechaPago' => $fechaBanco, 'IdBanco' => $cuentaBancaria, 'NumeroOperacion' => $numeroOp, 'Detalle' => $serie . '-' . $numero . ' (' . $razonSocial . ')', 'TipoMovimiento' => 'Ventas', 'Entrada' => $montoCuentaBanc, 'Salida' => '0', 'MontoActual' => $montoActual, 'IdSucursal' => $idSucursal];
        DB::table('banco_detalles')->insert($arrayDatos);

        DB::table('banco')->where('IdBanco', $cuentaBancaria)->update(['MontoActual' => $montoActual]);
    }

    public function dataVehiculo(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                if ($req->ajax()) {

                    $idUsuario = Session::get('idUsuario');
                    $loadDatos = new DatosController();
                    $idSucursal = Session::get('idSucursal');
                    $idVehiculo = $req->IdVehiculo;

                    $dataVehicular = $loadDatos->getVehiculoSelect($idVehiculo);

                    $checkList = $this->getCheckListPlacas($dataVehicular->PlacaVehiculo, $dataVehicular->IdCliente);

                    return Response([$dataVehicular, $checkList]);

                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function dataCheckList(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                if ($req->ajax()) {
                    $loadDatos = new DatosController();
                    $idSucursal = Session::get('idSucursal');
                    $idCheckList = $req->IdCheckList;
                    $dataCheckList = $loadDatos->getInventarioSelect($idCheckList, $idSucursal);
                    return Response([$dataCheckList]);
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function getCheckInSelect($idCkeckIn)
    {
        $ckecklist = DB::table('check_in')
            ->where('IdCheckIn', $idCkeckIn)
            ->first();
        return $ckecklist;
    }

    private function getCheckListPlacas($placa, $idCliente)
    {
        try {
            $ckecklist = DB::table('check_in')
                ->where('IdCliente', $idCliente)
                ->where('Placa', $placa)
                ->get();
            return $ckecklist;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function darBaja(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idCotizacion = $req->idBajaCotizacion;
            $amortizaciones = $this->getAmortizaciones($idCotizacion);
            //dd($amortizaciones);
            if (floatval($amortizaciones->AmortizacionTotal) > 0) {
                return redirect('operaciones/cotizacion/consultar-cotizacion')->with('error', 'No se puede dar de baja. La cotización ya tienes amortizaciones');
            } else {

                DB::table('cotizacion')
                    ->where('IdCotizacion', $idCotizacion)
                    ->update(['IdEstadoCotizacion' => 6]);

                return redirect('operaciones/cotizacion/consultar-cotizacion')->with('status', 'Se dio de baja cotización correctamente');
            }
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    private function getVehiculos($idSucursal)
    {
        try {
            $venta = DB::table('vehiculo')
                ->join('cliente', 'vehiculo.IdCliente', '=', 'cliente.IdCliente')
                ->select(DB::raw('concat(cliente.RazonSocial, " ---  Placa : ", vehiculo.PlacaVehiculo) as RazonSocial'), 'vehiculo.IdVehiculo as IdCliente',
                    'vehiculo.IdVehiculo as IdVehiculo', 'vehiculo.IdCliente as IdClienteVehicular')
                ->where('vehiculo.IdSucursal', $idSucursal)
                ->where('vehiculo.Estado', 1)
                ->get();
            return $venta;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function getCotizacionselect($idCotizacion)
    {
        try {
            //$cotizacion = DB::table('cotizacion')->where('IdCotizacion',$idCotizacion)->first();
            //if($cotizacion->TipoCotizacion == 1){
            $venta = DB::table('cotizacion')
                ->join('cliente', 'cotizacion.IdCliente', '=', 'cliente.IdCliente')
                ->join('tipo_documento', 'cliente.IdTipoDocumento', '=', 'tipo_documento.IdTipoDocumento')
                ->join('sucursal', 'cotizacion.IdSucursal', '=', 'sucursal.IdSucursal')
                ->join('usuario', 'cotizacion.IdCreacion', '=', 'usuario.IdUsuario')
                ->join('tipo_atencion', 'cotizacion.IdTipoAtencion', '=', 'tipo_atencion.IdTipoAtencion')
                ->join('estados_cotizacion', 'estados_cotizacion.IdEstadoCotizacion', '=', 'cotizacion.IdEstadoCotizacion')
                ->leftJoin('amortizacion', 'cotizacion.IdCotizacion', '=', 'amortizacion.IdCotizacion')
            // Nuevo codigo para imprimir ticket amortizacion
                ->join('tipo_moneda', 'cotizacion.IdTipoMoneda', '=', 'tipo_moneda.IdTipoMoneda')
            // Fin
                ->select('cotizacion.*', 'estados_cotizacion.Descripcion as EstadoCoti', 'cliente.IdTipoDocumento', 'cliente.Nombre as Nombres', 'cliente.RazonSocial', 'cliente.Direccion as DirCliente', 'cliente.NumeroDocumento', 'cliente.Email', 'cliente.Telefono as TelfCliente',
                    'sucursal.Direccion as Local', 'sucursal.Principal', 'sucursal.Nombre as Sucursal', 'sucursal.Ciudad', 'sucursal.Telefono as TelfSucursal', 'usuario.Nombre as Usuario', 'tipo_atencion.Descripcion as Atencion', 'cliente.PersonaContacto',
                    // Nuevo codigo para imprimir ticket amortizacion
                    DB::RAW('SUM(amortizacion.Monto) AS montoAmortizado'), 'tipo_moneda.Nombre AS Moneda', 'tipo_documento.Descripcion as TipoDoc', 'amortizacion.FormaPago')
            // Fin
                ->where('cotizacion.IdCotizacion', $idCotizacion)
                ->first();
            /*}else{
            $venta = DB::table('cotizacion')
            ->join('vehiculo','cotizacion.IdCliente', '=', 'vehiculo.IdVehiculo')
            ->join('cliente','vehiculo.IdCliente', '=', 'cliente.IdCliente')
            ->join('tipo_documento','cliente.IdTipoDocumento', '=', 'tipo_documento.IdTipoDocumento')
            ->join('sucursal','cotizacion.IdSucursal', '=', 'sucursal.IdSucursal')
            ->join('usuario','cotizacion.IdCreacion', '=', 'usuario.IdUsuario')
            ->select('cotizacion.*', 'vehiculo.PlacaVehiculo', 'vehiculo.Color', 'vehiculo.Anio', 'cliente.IdTipoDocumento', 'cliente.Nombre as Nombres', 'cliente.RazonSocial', 'cliente.Direccion as DirCliente', 'cliente.NumeroDocumento', 'cliente.Email', 'cliente.Telefono as TelfCliente',
            'sucursal.Direccion as Local', 'sucursal.Nombre as Sucursal', 'sucursal.Ciudad', 'sucursal.Telefono as TelfSucursal', 'usuario.Nombre as Usuario')
            ->where('cotizacion.IdCotizacion', $idCotizacion)
            ->first();
            }*/

            /* if($tipo == 2)
            {
            $venta = DB::table('cotizacion')
            ->join('vehiculo','cotizacion.IdCliente', '=', 'vehiculo.IdVehiculo')
            ->join('cliente','vehiculo.IdCliente', '=', 'cliente.IdCliente')
            ->join('tipo_documento','cliente.IdTipoDocumento', '=', 'tipo_documento.IdTipoDocumento')
            ->join('sucursal','cotizacion.IdSucursal', '=', 'sucursal.IdSucursal')
            ->join('usuario','cotizacion.IdCreacion', '=', 'usuario.IdUsuario')
            ->select('cotizacion.*', 'vehiculo.PlacaVehiculo', 'cliente.IdTipoDocumento', 'cliente.Nombre as Nombres', 'cliente.RazonSocial', 'cliente.Direccion as DirCliente', 'cliente.NumeroDocumento', 'cliente.Email', 'cliente.Telefono as TelfCliente',
            'sucursal.Direccion as Local', 'sucursal.Nombre as Sucursal', 'sucursal.Ciudad', 'sucursal.Telefono as TelfSucursal', 'usuario.Nombre as Usuario')
            ->where('cotizacion.IdCotizacion', $idCotizacion)
            ->first();
            }
            else
            {
            $venta = DB::table('cotizacion')
            ->join('cliente','cotizacion.IdCliente', '=', 'cliente.IdCliente')
            ->join('tipo_documento','cliente.IdTipoDocumento', '=', 'tipo_documento.IdTipoDocumento')
            ->join('sucursal','cotizacion.IdSucursal', '=', 'sucursal.IdSucursal')
            ->join('usuario','cotizacion.IdCreacion', '=', 'usuario.IdUsuario')
            ->select('cotizacion.*', 'cliente.IdTipoDocumento', 'cliente.Nombre as Nombres', 'cliente.RazonSocial', 'cliente.Direccion as DirCliente', 'cliente.NumeroDocumento', 'cliente.Email', 'cliente.Telefono as TelfCliente',
            'sucursal.Direccion as Local', 'sucursal.Nombre as Sucursal', 'sucursal.Ciudad', 'sucursal.Telefono as TelfSucursal', 'usuario.Nombre as Usuario')
            ->where('cotizacion.IdCotizacion', $idCotizacion)
            ->first();
            } */
            return $venta;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // FUNCION MODIFICADA PARA UNIR ITEMS DE LOS PAQUETES A LA COTIZACIONNNNNNNNNNNN
    private function getItemsCotizacion($idCotizacion)
    {
        try {
            $ventas = DB::table('cotizacion_articulo')
                ->join('articulo', 'cotizacion_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->select('cotizacion_articulo.*', 'cotizacion_articulo.Codigo as Cod', 'articulo.Codigo as CodigoArticulo', 'articulo.Ubicacion', 'articulo.IdMarca', 'articulo.Descripcion', 'articulo.Stock', 'articulo.Costo', 'articulo.CantidadTipo', 'articulo.DescuentoTipo', 'cotizacion_articulo.PrecioUnidadReal as artPrecio', 'articulo.PrecioTipo', 'unidad_medida.Nombre as UniMedida', 'unidad_medida.Descripcion as CodSunatMedida', 'articulo.IdTipo', 'articulo.IdUnidadMedida', 'articulo.IdTipoMoneda')
                ->where('cotizacion_articulo.IdCotizacion', $idCotizacion)
                ->whereNull('cotizacion_articulo.IdPaquetePromocional')
                ->orderBy('cotizacion_articulo.IdCotizaArticulo', 'asc')
                ->get();
            return $ventas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function getItemsCotizacionYpaquetePromocional($idCotizacion)
    {
        try {
            $itemsCotizacion = DB::table('cotizacion_articulo')
                ->join('articulo', 'cotizacion_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->select('cotizacion_articulo.*', 'cotizacion_articulo.Codigo as Cod', 'articulo.Codigo as CodigoArticulo', 'articulo.Ubicacion', 'articulo.IdMarca', 'articulo.Descripcion', 'articulo.Stock', 'articulo.Costo', 'articulo.CantidadTipo', 'articulo.DescuentoTipo', 'cotizacion_articulo.PrecioUnidadReal as artPrecio', 'articulo.PrecioTipo', 'unidad_medida.Nombre as UniMedida', 'unidad_medida.Descripcion as CodSunatMedida', 'articulo.IdTipo', 'articulo.IdUnidadMedida')
                ->where('cotizacion_articulo.IdCotizacion', $idCotizacion)
                ->whereNull('cotizacion_articulo.IdPaquetePromocional')
                ->orderBy('cotizacion_articulo.IdCotizaArticulo', 'asc')
                ->get();

            $itemsPaquetePromocional = DB::table('cotizacion_articuloPaquetePromocional as capp')
                ->join('articulo', 'capp.IdArticulo', '=', 'articulo.IdArticulo')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->select('capp.*', 'capp.Codigo as Cod', 'articulo.Codigo as CodigoArticulo', 'articulo.Ubicacion', 'articulo.IdMarca', 'articulo.Descripcion', 'articulo.Stock', 'articulo.Costo', 'articulo.CantidadTipo', 'articulo.DescuentoTipo', 'capp.PrecioUnidadReal as artPrecio', 'articulo.PrecioTipo', 'unidad_medida.Nombre as UniMedida', 'unidad_medida.Descripcion as CodSunatMedida', 'articulo.IdTipo', 'articulo.IdUnidadMedida')
                ->where('capp.IdCotizacion', $idCotizacion)
                ->orderBy('capp.IdDetalle', 'asc')
                ->get();
            $resultado = $itemsCotizacion->concat($itemsPaquetePromocional);
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    // -------------------------FIN---------------------------

    private function getCotizacionAll($idSucursal)
    {
        try {
            $ventas = DB::table('cotizacion')
                ->join('cliente', 'cotizacion.IdCliente', '=', 'cliente.IdCliente')
                ->join('sucursal', 'cotizacion.IdSucursal', '=', 'sucursal.IdSucursal')
                ->join('usuario', 'cotizacion.IdCreacion', '=', 'usuario.IdUsuario')
                ->join('tipo_atencion', 'cotizacion.IdTipoAtencion', '=', 'tipo_atencion.IdTipoAtencion')
                ->join('estados_cotizacion', 'estados_cotizacion.IdEstadoCotizacion', '=', 'cotizacion.IdEstadoCotizacion')
                ->leftJoin('check_in', 'cotizacion.IdCheckIn', '=', 'check_in.IdCheckIn')
                ->select('cotizacion.*', 'estados_cotizacion.Descripcion as EstadoCoti', 'estados_cotizacion.Color', 'cliente.RazonSocial as RazonSocial', 'cliente.Nombre as Nombres', 'cliente.NumeroDocumento', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario', 'tipo_atencion.Descripcion as Atencion', DB::raw("CONCAT(check_in.Serie, '-', check_in.Correlativo) As CodigoInventario"))
                ->where('cotizacion.IdSucursal', $idSucursal)
                ->whereRaw('YEAR(cotizacion.FechaCreacion)=YEAR(NOW())')
                ->whereRaw('MONTH(cotizacion.FechaCreacion) = MONTH(NOW())')
                ->orderBy('cotizacion.FechaCreacion', 'desc')
                ->get();
            return $ventas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // protected function getCotizacionAllFiltrado($idTipoAtencion, $idSucursal, $idEstadoCotizacion, $fecha, $fechaIni, $fechaFin)
    // {
    //     $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);

    //     try {
    //         if ($idTipoAtencion == 5) {
    //             if ($idEstadoCotizacion == 5) {
    //                 $cotizaciones = DB::table('cotizacion')
    //                     ->join('cliente', 'cotizacion.IdCliente', '=', 'cliente.IdCliente')
    //                     ->join('sucursal', 'cotizacion.IdSucursal', '=', 'sucursal.IdSucursal')
    //                     ->join('usuario', 'cotizacion.IdCreacion', '=', 'usuario.IdUsuario')
    //                     ->join('tipo_atencion', 'cotizacion.IdTipoAtencion', '=', 'tipo_atencion.IdTipoAtencion')
    //                     ->join('estados_cotizacion', 'estados_cotizacion.IdEstadoCotizacion', '=', 'cotizacion.IdEstadoCotizacion')
    //                     ->select('cotizacion.*', 'estados_cotizacion.Descripcion as EstadoCoti', 'estados_cotizacion.Color', 'cliente.RazonSocial as RazonSocial', 'cliente.Nombre as Nombres', 'cliente.NumeroDocumento', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario', 'tipo_atencion.Descripcion as Atencion')
    //                     ->where('cotizacion.IdSucursal', $idSucursal)
    //                     ->whereBetween('cotizacion.FechaCreacion', [$fechas[0], $fechas[1]])
    //                     ->orderBy('cotizacion.FechaCreacion', 'desc')
    //                     ->get();
    //             } else {
    //                 $cotizaciones = DB::table('cotizacion')
    //                     ->join('cliente', 'cotizacion.IdCliente', '=', 'cliente.IdCliente')
    //                     ->join('sucursal', 'cotizacion.IdSucursal', '=', 'sucursal.IdSucursal')
    //                     ->join('usuario', 'cotizacion.IdCreacion', '=', 'usuario.IdUsuario')
    //                     ->join('tipo_atencion', 'cotizacion.IdTipoAtencion', '=', 'tipo_atencion.IdTipoAtencion')
    //                     ->join('estados_cotizacion', 'estados_cotizacion.IdEstadoCotizacion', '=', 'cotizacion.IdEstadoCotizacion')
    //                     ->select('cotizacion.*', 'estados_cotizacion.Descripcion as EstadoCoti', 'estados_cotizacion.Color', 'cliente.RazonSocial as RazonSocial', 'cliente.Nombre as Nombres', 'cliente.NumeroDocumento', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario', 'tipo_atencion.Descripcion as Atencion')
    //                     ->where('cotizacion.IdSucursal', $idSucursal)
    //                     ->where('cotizacion.IdEstadoCotizacion', $idEstadoCotizacion)
    //                     ->whereBetween('cotizacion.FechaCreacion', [$fechas[0], $fechas[1]])
    //                     ->orderBy('cotizacion.FechaCreacion', 'desc')
    //                     ->get();
    //             }
    //         } else {
    //             if ($idEstadoCotizacion == 5) {
    //                 $cotizaciones = DB::table('cotizacion')
    //                     ->join('cliente', 'cotizacion.IdCliente', '=', 'cliente.IdCliente')
    //                     ->join('sucursal', 'cotizacion.IdSucursal', '=', 'sucursal.IdSucursal')
    //                     ->join('usuario', 'cotizacion.IdCreacion', '=', 'usuario.IdUsuario')
    //                     ->join('tipo_atencion', 'cotizacion.IdTipoAtencion', '=', 'tipo_atencion.IdTipoAtencion')
    //                     ->join('estados_cotizacion', 'estados_cotizacion.IdEstadoCotizacion', '=', 'cotizacion.IdEstadoCotizacion')
    //                     ->select('cotizacion.*', 'estados_cotizacion.Descripcion as EstadoCoti', 'estados_cotizacion.Color', 'cliente.RazonSocial as RazonSocial', 'cliente.Nombre as Nombres', 'cliente.NumeroDocumento', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario', 'tipo_atencion.Descripcion as Atencion')
    //                     ->where('cotizacion.IdSucursal', $idSucursal)
    //                     ->where('cotizacion.IdTipoAtencion', $idTipoAtencion)
    //                     ->whereBetween('cotizacion.FechaCreacion', [$fechas[0], $fechas[1]])
    //                     ->orderBy('cotizacion.FechaCreacion', 'desc')
    //                     ->get();
    //             } else {
    //                 $cotizaciones = DB::table('cotizacion')
    //                     ->join('cliente', 'cotizacion.IdCliente', '=', 'cliente.IdCliente')
    //                     ->join('sucursal', 'cotizacion.IdSucursal', '=', 'sucursal.IdSucursal')
    //                     ->join('usuario', 'cotizacion.IdCreacion', '=', 'usuario.IdUsuario')
    //                     ->join('tipo_atencion', 'cotizacion.IdTipoAtencion', '=', 'tipo_atencion.IdTipoAtencion')
    //                     ->join('estados_cotizacion', 'estados_cotizacion.IdEstadoCotizacion', '=', 'cotizacion.IdEstadoCotizacion')
    //                     ->select('cotizacion.*', 'estados_cotizacion.Descripcion as EstadoCoti', 'estados_cotizacion.Color', 'cliente.RazonSocial as RazonSocial', 'cliente.Nombre as Nombres', 'cliente.NumeroDocumento', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario', 'tipo_atencion.Descripcion as Atencion')
    //                     ->where('cotizacion.IdSucursal', $idSucursal)
    //                     ->where('cotizacion.IdTipoAtencion', $idTipoAtencion)
    //                     ->where('cotizacion.IdEstadoCotizacion', $idEstadoCotizacion)
    //                     ->whereBetween('cotizacion.FechaCreacion', [$fechas[0], $fechas[1]])
    //                     ->orderBy('cotizacion.FechaCreacion', 'desc')
    //                     ->get();
    //             }

    //         }
    //         return $cotizaciones;
    //     } catch (Exception $ex) {
    //         echo $ex->getMessage();
    //     }
    // }

    private function getCotizacionAllFiltrado($idTipoAtencion, $idSucursal, $idEstadoCotizacion, $fechaIni, $fechaFin)
    {
        try {
            if ($idTipoAtencion == 5) {
                if ($idEstadoCotizacion == 5) {
                    $cotizaciones = DB::table('cotizacion')
                        ->join('cliente', 'cotizacion.IdCliente', '=', 'cliente.IdCliente')
                        ->join('sucursal', 'cotizacion.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario', 'cotizacion.IdCreacion', '=', 'usuario.IdUsuario')
                        ->join('tipo_atencion', 'cotizacion.IdTipoAtencion', '=', 'tipo_atencion.IdTipoAtencion')
                        ->join('estados_cotizacion', 'estados_cotizacion.IdEstadoCotizacion', '=', 'cotizacion.IdEstadoCotizacion')
                        ->leftJoin('check_in', 'cotizacion.IdCheckIn', '=', 'check_in.IdCheckIn')
                        ->select('cotizacion.*', 'estados_cotizacion.Descripcion as EstadoCoti', 'estados_cotizacion.Color', 'cliente.RazonSocial as RazonSocial', 'cliente.Nombre as Nombres', 'cliente.NumeroDocumento', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario', 'tipo_atencion.Descripcion as Atencion', 'cliente.Telefono as CelularCliente', DB::raw("CONCAT(check_in.Serie, '-', check_in.Correlativo) As CodigoInventario"))
                        ->where('cotizacion.IdSucursal', $idSucursal)
                        ->whereBetween('cotizacion.FechaCreacion', [$fechaIni, $fechaFin])
                        ->orderBy('cotizacion.FechaCreacion', 'desc')
                        ->get();
                } else {
                    $cotizaciones = DB::table('cotizacion')
                        ->join('cliente', 'cotizacion.IdCliente', '=', 'cliente.IdCliente')
                        ->join('sucursal', 'cotizacion.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario', 'cotizacion.IdCreacion', '=', 'usuario.IdUsuario')
                        ->join('tipo_atencion', 'cotizacion.IdTipoAtencion', '=', 'tipo_atencion.IdTipoAtencion')
                        ->join('estados_cotizacion', 'estados_cotizacion.IdEstadoCotizacion', '=', 'cotizacion.IdEstadoCotizacion')
                        ->leftJoin('check_in', 'cotizacion.IdCheckIn', '=', 'check_in.IdCheckIn')
                        ->select('cotizacion.*', 'estados_cotizacion.Descripcion as EstadoCoti', 'estados_cotizacion.Color', 'cliente.RazonSocial as RazonSocial', 'cliente.Nombre as Nombres', 'cliente.NumeroDocumento', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario', 'tipo_atencion.Descripcion as Atencion', 'cliente.Telefono as CelularCliente', DB::raw("CONCAT(check_in.Serie, '-', check_in.Correlativo) As CodigoInventario"))
                        ->where('cotizacion.IdSucursal', $idSucursal)
                        ->where('cotizacion.IdEstadoCotizacion', $idEstadoCotizacion)
                        ->whereBetween('cotizacion.FechaCreacion', [$fechaIni, $fechaFin])
                        ->orderBy('cotizacion.FechaCreacion', 'desc')
                        ->get();
                }
            } else {
                if ($idEstadoCotizacion == 5) {
                    $cotizaciones = DB::table('cotizacion')
                        ->join('cliente', 'cotizacion.IdCliente', '=', 'cliente.IdCliente')
                        ->join('sucursal', 'cotizacion.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario', 'cotizacion.IdCreacion', '=', 'usuario.IdUsuario')
                        ->join('tipo_atencion', 'cotizacion.IdTipoAtencion', '=', 'tipo_atencion.IdTipoAtencion')
                        ->join('estados_cotizacion', 'estados_cotizacion.IdEstadoCotizacion', '=', 'cotizacion.IdEstadoCotizacion')
                        ->leftJoin('check_in', 'cotizacion.IdCheckIn', '=', 'check_in.IdCheckIn')
                        ->select('cotizacion.*', 'estados_cotizacion.Descripcion as EstadoCoti', 'estados_cotizacion.Color', 'cliente.RazonSocial as RazonSocial', 'cliente.Nombre as Nombres', 'cliente.NumeroDocumento', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario', 'tipo_atencion.Descripcion as Atencion', 'cliente.Telefono as CelularCliente', DB::raw("CONCAT(check_in.Serie, '-', check_in.Correlativo) As CodigoInventario"))
                        ->where('cotizacion.IdSucursal', $idSucursal)
                        ->where('cotizacion.IdTipoAtencion', $idTipoAtencion)
                        ->whereBetween('cotizacion.FechaCreacion', [$fechaIni, $fechaFin])
                        ->orderBy('cotizacion.FechaCreacion', 'desc')
                        ->get();
                } else {
                    $cotizaciones = DB::table('cotizacion')
                        ->join('cliente', 'cotizacion.IdCliente', '=', 'cliente.IdCliente')
                        ->join('sucursal', 'cotizacion.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario', 'cotizacion.IdCreacion', '=', 'usuario.IdUsuario')
                        ->join('tipo_atencion', 'cotizacion.IdTipoAtencion', '=', 'tipo_atencion.IdTipoAtencion')
                        ->join('estados_cotizacion', 'estados_cotizacion.IdEstadoCotizacion', '=', 'cotizacion.IdEstadoCotizacion')
                        ->leftJoin('check_in', 'cotizacion.IdCheckIn', '=', 'check_in.IdCheckIn')
                        ->select('cotizacion.*', 'estados_cotizacion.Descripcion as EstadoCoti', 'estados_cotizacion.Color', 'cliente.RazonSocial as RazonSocial', 'cliente.Nombre as Nombres', 'cliente.NumeroDocumento', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario', 'tipo_atencion.Descripcion as Atencion', 'cliente.Telefono as CelularCliente', DB::raw("CONCAT(check_in.Serie, '-', check_in.Correlativo) As CodigoInventario"))
                        ->where('cotizacion.IdSucursal', $idSucursal)
                        ->where('cotizacion.IdTipoAtencion', $idTipoAtencion)
                        ->where('cotizacion.IdEstadoCotizacion', $idEstadoCotizacion)
                        ->whereBetween('cotizacion.FechaCreacion', [$fechaIni, $fechaFin])
                        ->orderBy('cotizacion.FechaCreacion', 'desc')
                        ->get();
                }

            }
            return $cotizaciones;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // public function paginationProductos(Request $req)
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
    //             $productos = $loadDatos->getProductosPagination($idSucursal, $req->textoBuscar, $req->tipoMoneda, $req->idCategoria);
    //         } else {
    //             $productos = $loadDatos->paginarAjaxProdSucursal($idSucursal, $req->textoBuscar, $req->tipoMoneda, $req->idCategoria);
    //         }

    //         return Response($productos);
    //     }
    // }

    // public function paginationServicios(Request $req)
    // {
    //     if ($req->ajax()) {
    //         $idSucursal = Session::get('idSucursal');
    //         $text2 = Session::get('text');
    //         $loadDatos = new DatosController();
    //         $servicios = $loadDatos->getServiciosPagination($idSucursal, $req->tipoMoneda, $text2);
    //         return Response($servicios);
    //     }
    // }

    // public function searchProducto(Request $req)
    // {
    //     if ($req->ajax()) {
    //         $loadDatos = new DatosController();
    //         $idSucursal = Session::get('idSucursal');

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
    //             $articulos = $loadDatos->getBuscarProductosVentas($req->textoBuscar, $req->tipoMoneda, $idSucursal, $req->idCategoria);
    //         } else {
    //             $articulos = $loadDatos->buscarAjaxProdSucursal($req->textoBuscar, $req->tipoMoneda, $idSucursal, $req->idCategoria);
    //         }
    //         return Response($articulos);
    //     }
    // }

    // public function searchServicio(Request $req)
    // {
    //     if ($req->ajax()) {
    //         $loadDatos = new DatosController();
    //         $idSucursal = Session::get('idSucursal');
    //         Session::put('text', $req->textoBuscar);
    //         $articulos = $loadDatos->getBuscarServiciosVentas($req->textoBuscar, $req->tipoMoneda, $idSucursal);
    //         return Response($articulos);
    //     }
    // }

    public function searchCodigoProducto(Request $req)
    {
        if ($req->ajax()) {
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $articulos = $loadDatos->getBuscarCodigoProductoVentas($req->codigoBusqueda, $req->tipoMoneda, $idSucursal);
            return Response($articulos);
        }
    }

    // public function selectProductos(Request $req)
    // {
    //     if ($req->ajax()) {
    //         $loadDatos = new DatosController();
    //         $idSucursal = Session::get('idSucursal');
    //         //$textoBuscar = "";

    //         $fecha = Carbon::today();

    //         $tipoCambio = DB::table('tipo_cambio')
    //             ->where('FechaCreacion', $fecha)
    //             ->where('IdSucursal', $idSucursal)
    //             ->get();

    //         //$productos = $loadDatos->getBuscarProductosVentas($textoBuscar, $req->tipoMoneda, $idSucursal, 0);

    //         //$servicios = $loadDatos->getBuscarServiciosVentas($textoBuscar, $req->tipoMoneda, $idSucursal);

    //         return Response([$tipoCambio]);
    //     }
    // }

    // public function selectProductosEdicion(Request $req)
    // {
    //     if ($req->ajax()) {
    //         $idSucursal = Session::get('idSucursal');
    //         $fecha = $req->fecha;
    //         $date = DateTime::createFromFormat('Y-m-d', $fecha);
    //         $fechaConvertida = $date->format("Y-m-d");

    //         $tipoCambio = DB::table('tipo_cambio')
    //             ->where('FechaCreacion', $fechaConvertida)
    //             ->where('IdSucursal', $idSucursal)
    //             ->get();

    //         return Response([$tipoCambio]);
    //     }
    // }

    public function porcentajeDescuento(Request $req)
    {
        if ($req->ajax()) {
            $loadDatos = new DatosController();
            $idProducto = $req->idProducto;
            $descuentos = $loadDatos->getProductoSelect($idProducto);
            return Response([$descuentos]);
        }
    }

    private function guardarFechasPago($fecha, $plazoCredito, $idVenta, $total, $interes)
    {
        $fechaInicio = DateTime::createFromFormat('Y-m-d', $fecha);
        $fechaConvertidaInicio = $fechaInicio->format("Y-m-d");

        $plazoInteresTotal = $total + (($total / 100) * $interes);

        $fechaConvertidaFinal = strtotime('+' . $plazoCredito . ' day', strtotime($fechaConvertidaInicio));
        $fechaConvertidaFinal = date('Y-m-d', $fechaConvertidaFinal);
        $array = ['IdVenta' => $idVenta, 'FechaInicio' => $fechaConvertidaInicio, 'FechaUltimo' => $fechaConvertidaFinal, 'Importe' => $plazoInteresTotal, 'ImportePagado' => 0.00, 'DiasPasados' => 0, 'Estado' => 1];
        DB::table('fecha_pago')->insert($array);
    }

    private function verificarStockSuficiente($req)
    {
        $loadDatos = new DatosController();
        $array = [];
        $idEstadoCotizacion = $req->idEstadoCotizacion;
        if ($idEstadoCotizacion == 1 || $idEstadoCotizacion == 5) {
            for ($i = 0; $i < count($req->Id); $i++) {
                $sumador = 0;
                $producto = substr($req->Codigo[$i], 0, 3);
                $productoSelect = $loadDatos->getProductoSelect($req->Id[$i]);
                if ($producto == 'PRO') {
                    $sumador = $req->Cantidad[$i];
                    /*for($k=0; $k<count($req->Id); $k++){

                    if($req->Id[$i] == $req->Id[$k]){
                    $sumador = $req->Cantidad[$k];
                    }
                    }*/
                    if ($sumador > $productoSelect->Stock) {
                        array_push($array, $productoSelect->Descripcion);
                        array_push($array, $productoSelect->Stock);
                        return $array;
                    }
                }
            }
        }

        //array_push($array, 1);
        return $array;

    }

    private function completarCeros($numero)
    {
        $numeroConCeros = str_pad($numero, 8, "0", STR_PAD_LEFT);
        return $numeroConCeros;
    }

    private function verificarCodigoCoti($serie, $numero, $idSucursal)
    {
        try {
            $resultado = DB::table('cotizacion')
                ->select(DB::raw("count(IdCotizacion) as Cantidad"))
                ->where('Serie', $serie)
                ->where('Numero', $numero)
                ->where('IdSucursal', $idSucursal)
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function ultimoCorrelativoCoti($idUsuario, $idSucursal)
    {
        try {
            $resultado = DB::table('cotizacion')
                ->where('IdCreacion', $idUsuario)
                ->where('IdSucursal', $idSucursal)
                ->orderBy('IdCotizacion', 'desc')
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function verificarCodigo($serie, $numero, $idSucursal)
    {
        try {
            $resultado = DB::table('ventas')
                ->select(DB::raw("count(IdVentas) as Cantidad"))
                ->where('Serie', $serie)
                ->where('Numero', $numero)
                ->where('IdSucursal', $idSucursal)
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function ultimoCorrelativo($idUsuario, $idSucursal, $tipoDoc)
    {
        try {
            $resultado = DB::table('ventas')
                ->where('IdCreacion', $idUsuario)
                ->where('IdSucursal', $idSucursal)
                ->where('IdTipoComprobante', $tipoDoc)
                ->orderBy('IdVentas', 'desc')
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function generarPDF($req, $tipo, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        $loadDatos = new DatosController();
        $info = [];

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $cotizacionSelect = $this->getCotizacionselect($id);
        // Nuevo codigo
        if ($modulosSelect->contains('IdModulo', 7)) {
            if ($cotizacionSelect->IdTipoAtencion == 1 || $cotizacionSelect->IdTipoAtencion == 6) {
                $datosNotificacionMantenimiento = $this->getNotificacionMantenimiento($id);
            } else {
                $datosNotificacionMantenimiento = null;
            }
        } else {
            $datosNotificacionMantenimiento = null;
        }
        // Fin
        if ($cotizacionSelect->IdOperario > 0) {
            $operarioSelect = $loadDatos->getOperarioSelect($cotizacionSelect->IdOperario);
            $operario = $operarioSelect->Nombres;
        } else {
            $operario = 'Genérico';

        }
        if ($cotizacionSelect->TipoCotizacion == 2) {
            $vehiculoSelect = $loadDatos->getVehiculoSelect($cotizacionSelect->Campo0);
            $color = $vehiculoSelect->Color;
            $anio = $vehiculoSelect->Anio;
            $marca = $vehiculoSelect->NombreMarca;
            $modelo = $vehiculoSelect->NombreModelo;
            $numeroFlota = $vehiculoSelect->NumeroFlota;
            $seguro = $vehiculoSelect->Seguro;
            $idSeguro = $vehiculoSelect->IdSeguro;
            $_fechaSoat = date_create($vehiculoSelect->FechaSoat);
            $fechaSoat = date_format($_fechaSoat, 'd-m-Y');
            $_fechaRevTec = date_create($vehiculoSelect->FechaRevTecnica);
            $fechaRevTec = date_format($_fechaRevTec, 'd-m-Y');
        } else {
            $color = '';
            $anio = '';
            $marca = '';
            $modelo = '';
            $fechaSoat = '';
            $fechaRevTec = '';
            $numeroFlota = '';
            $seguro = '';
            $idSeguro = '';
        }

        $datosVe = DB::table('cotizacion as c')
            ->join('vehiculo as v', 'c.Campo0', '=', 'v.IdVehiculo')
            ->where('IdCotizacion', $id)
            ->first();

        if ($datosVe) {
            $info = $datosVe;

        }
        // $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $fecha = date_create($cotizacionSelect->FechaCreacion);
        $fechaFin = date_create($cotizacionSelect->FechaFin);
        $formatoFecha = date_format($fecha, 'd/m/Y');
        $formatoHora = date_format($fecha, 'H:i A');
        $formatoFechaFin = date_format($fechaFin, 'd/m/Y');
        $convertirLetras = new NumeroALetras();
        if ($cotizacionSelect->IdTipoMoneda == 1) {
            //$totalDetrac = $cotizacionSelect->Total;
            $importeLetras = $convertirLetras->convertir($cotizacionSelect->Total, 'soles');
        } else {
            /*$fechaDetrac = Carbon::parse($cotizacionSelect->FechaCreacion);
            $fechaDetrac = date_format($fechaDetrac, 'Y-m-d');
            $valorCambio = DB::table('tipo_cambio')
            ->where('IdSucursal', $idSucursal)
            ->where('FechaCreacion', $fechaDetrac)
            ->first();

            if($valorCambio){
            $totalDetrac = $cotizacionSelect->Total * $valorCambio->TipoCambioVentas;
            }else{
            $totalDetrac = $cotizacionSelect->Total;
            }*/
            $importeLetras = $convertirLetras->convertir($cotizacionSelect->Total, 'dólares');
        }

        $numeroCerosIzquierda = $this->completarCeros($cotizacionSelect->Numero);
        $exp = explode("\n", $cotizacionSelect->Observacion);
        $lineas = count($exp);
        if ($lineas <= 5) {
            $lineas = $lineas * 8;
        } else if ($lineas > 5 && $lineas <= 10) {
            $lineas = $lineas * 10;
        } else {
            $lineas = $lineas * 12;
        }
        $cuentasCorrientes = $this->getCuentasCorrientes($usuarioSelect->CodigoCliente);
        // dd($cuentasCorrientes);
        $items = $this->getItemsCotizacion($id);
        for ($i = 0; $i < count($items); $i++) {
            $items[$i]->Disponibilidad = floatval($items[$i]->Stock) - floatval($items[$i]->Cantidad);
            if ($items[$i]->IdMarca != null) {
                $_marca = DB::table('marca')
                    ->where('IdMarca', $items[$i]->IdMarca)
                    ->first();

                $items[$i]->Marca = $_marca->Nombre;
            } else {
                $items[$i]->Marca = null;
            }
        }
        if ($cotizacionSelect->TipoVenta == 1) {
            $totalOperacionesProductos = $items->where('IdTipo', 1)->pluck('Importe')->sum() / config('variablesGlobales.Igv');
            $totalOperacionesServicios = $items->where('IdTipo', 2)->pluck('Importe')->sum() / config('variablesGlobales.Igv');
        } else {
            $totalOperacionesProductos = $items->where('IdTipo', 1)->pluck('Importe')->sum();
            $totalOperacionesServicios = $items->where('IdTipo', 2)->pluck('Importe')->sum();
        }
        // dd($totalOperacionesProductos);

        $itemsProd = $items->where('IdTipo', 1);
        $itemsServ = $items->where('IdTipo', 2);
        // $listaPaquetesPromocionales = $this->getDetalleCotizacionPaquetesPromocionales($id);
        $sucursal = $loadDatos->getSucursales($usuarioSelect->CodigoCliente, $usuarioSelect->IdOperador)->where("Principal", 0)->where('OcultarDireccion', 'E')->first();
        // dd($sucursal);
        // dd($paquetesPromocionales);

        $importeAmortizadoTicketLetras = $convertirLetras->convertir($cotizacionSelect->Total - $cotizacionSelect->montoAmortizado, 'Soles');
        $array = ['itemsProd' => $itemsProd, 'itemsServ' => $itemsServ, 'vehiculo' => $info, 'lineas' => $lineas, 'operario' => $operario, 'color' => $color, 'anio' => $anio, 'marca' => $marca, 'modelo' => $modelo, 'numeroCeroIzq' => $numeroCerosIzquierda, 'ventaSelect' => $cotizacionSelect, 'numeroFlota' => $numeroFlota, 'seguro' => $seguro, 'idSeguro' => $idSeguro,
            'formatoFecha' => $formatoFecha, 'formatoFechaFin' => $formatoFechaFin, 'formatoHora' => $formatoHora, 'importeLetras' => $importeLetras, 'empresa' => $empresa, 'cuentasCorrientes' => $cuentasCorrientes, 'tipo' => $tipo, 'fechaSoat' => $fechaSoat, 'fechaRevTec' => $fechaRevTec, 'modulosSelect' => $modulosSelect, 'sucursal' => $sucursal, 'datosNotificacionMantenimiento' => $datosNotificacionMantenimiento, 'importeAmortizadoTicketLetras' => $importeAmortizadoTicketLetras, 'totalOperacionesProductos' => $totalOperacionesProductos, 'totalOperacionesServicios' => $totalOperacionesServicios, 'usuarioSelect' => $usuarioSelect];
        view()->share($array);
        // dd($tipo);
        if ($tipo == 1) {
            $pdf = PDF::loadView('pdf/cotizacionPdf')->setPaper('a4', 'portrait');
            //$pdf = PDF::loadView('ventasPDF')->setPaper(array(0,0,595.28,841.89));
        }
        if ($tipo == 2) {
            $pdf = PDF::loadView('ventasPDFA5')->setPaper('a5', 'portrait');
        }
        if ($tipo == 3) {
            $pdf = PDF::loadView('ventasTicket')->setPaper(array(0, 0, 107, 600));
        }
        if ($tipo == 4) {
            $pdf = PDF::loadView('pdf/cotizacionOrdenPdf')->setPaper('a4', 'portrait');
        }
        if ($tipo == 5) {
            $pdf = PDF::loadView('pdf/nuevoPdf')->setPaper('a4', 'portrait');
        }
        if ($tipo == 6) {
            $pdf = PDF::loadView('amortizacionTicketA4')->setPaper('a4', 'portrait');
        }
        if ($tipo == 7) {
            $pdf = PDF::loadView('amortizacionTicket')->setPaper(array(0, 0, 107, 400));
        }
        return $pdf;
    }

    private function getCuentasCorrientes($codigoCliente)
    {
        $resultado = DB::table('banco')
            ->join('lista_banco', 'banco.IdListaBanco', '=', 'lista_banco.IdListaBanco')
            ->join('tipo_moneda', 'banco.IdTipoMoneda', '=', 'tipo_moneda.IdTipoMoneda')
            ->join('tipos_cuentas_bancarias as tcb', 'banco.IdCuentaBancaria', '=', 'tcb.IdCuentaBancaria')
            ->select('banco.NumeroCuenta', 'lista_banco.Nombre as Banco', 'tipo_moneda.Nombre as Moneda', 'banco.CCI', DB::Raw('UPPER(tcb.Nombre) as NombreCuenta'))
            ->where('CodigoCliente', $codigoCliente)
            ->where('banco.Estado', 'E')
            ->limit(3)
            ->get();
        return $resultado;
    }

    private function actualizarStock($Id, $producto, $Cantidad)
    {
        $loadDatos = new DatosController();
        //$arrayGanancias = [];
        //for($i=0; $i<count($req->Id); $i++){
        //$producto = substr($req->Codigo[$i],0,3);
        //$ganancia = 0;
        //if($producto == 'PRO'){
        $productoSelect = $loadDatos->getProductoStockSelect($Id);

        if (count($productoSelect) >= 1) { //evitar el no encontrar y el cero { {
            if ($Cantidad > $productoSelect[0]->Cantidad) {
                //$ganancia += (int) $productoSelect[0]->Cantidad * ( (float) $productoSelect[0]->Precio - (float) $productoSelect[0]->Costo);
                $resto = (float) $Cantidad - (float) $productoSelect[0]->Cantidad;
                DB::table('stock')
                    ->where('IdStock', $productoSelect[0]->IdStock)
                    ->update(['Cantidad' => 0]);
                if ($resto > $productoSelect[1]->Cantidad) {
                    //$ganancia += $productoSelect[1]->Cantidad * ( (float) $productoSelect[1]->Precio - (float) $productoSelect[1]->Costo);
                    $resto = $resto - (float) $productoSelect[1]->Cantidad;
                    DB::table('stock')
                        ->where('IdStock', $productoSelect[1]->IdStock)
                        ->update(['Cantidad' => 0]);
                    /*if($resto > $productoSelect[2]->Cantidad){
                //$ganancia += $productoSelect[2]->Cantidad * ( (float) $productoSelect[2]->Precio - (float) $productoSelect[2]->Costo);
                $dif = (float) $productoSelect[2]->Cantidad - (float) $Cantidad;
                DB::table('stock')
                ->where('IdStock', $productoSelect[0]->IdStock)
                ->update(['Cantidad' => $dif]);
                }else{
                //$ganancia += $resto * ( (float) $productoSelect[2]->Precio - (float) $productoSelect[2]->Costo);
                //$dif = (int) $productoSelect[2]->Cantidad - $resto;
                DB::table('stock')
                ->where('IdStock', $productoSelect[2]->IdStock)
                ->decrement('Cantidad', $resto);
                }*/
                } else {
                    //$ganancia += $resto * ( (float) $productoSelect[1]->Precio - (float) $productoSelect[1]->Costo);
                    //$dif = (int) $productoSelect[1]->Cantidad - $resto;
                    DB::table('stock')
                        ->where('IdStock', $productoSelect[1]->IdStock)
                        ->decrement('Cantidad', $resto);
                }
            } else {
                //$ganancia += $Cantidad * ( (float) $productoSelect[0]->Precio - (float) $productoSelect[0]->Costo);
                //$dif = (int) $productoSelect[0]->Cantidad - (float) $Cantidad;
                DB::table('stock')
                    ->where('IdStock', $productoSelect[0]->IdStock)
                    ->decrement('Cantidad', $Cantidad);
            }
        }
        //$arrayGanancias[$i] = $ganancia;
    }

    private function validarNotificacionVehiculo($placa, $idSucursal)
    {
        $resultado = DB::table('notificacion_mantenimiento')
            ->where('PlacaVehiculo', $placa)
            ->where('IdSucursal', $idSucursal)
            ->first();
        return $resultado;
    }

    private function getNotificacionMantenimiento($idCotizacion)
    {
        $resultado = DB::table('notificacion_mantenimiento')
            ->select(DB::raw('DATE_FORMAT(ProximaFecha, "%d/%m/%Y") AS ProximaFecha'), 'ProximoMantenimiento')
            ->where('IdCotizacion', $idCotizacion)
            ->first();
        return $resultado;
    }

    public function getItems($idSucursal)
    {
        $itemsCotizacionSinPaquetePromocional = DB::table('cotizacion_articulo')
            ->join('articulo', 'cotizacion_articulo.IdArticulo', '=', 'articulo.IdArticulo')
            ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
            ->select('cotizacion_articulo.*', 'articulo.Ubicacion', 'articulo.IdMarca', 'articulo.Descripcion', 'articulo.Stock', 'articulo.Costo', 'articulo.CantidadTipo', 'articulo.DescuentoTipo', 'cotizacion_articulo.PrecioUnidadReal as artPrecio', 'articulo.PrecioTipo', 'unidad_medida.Nombre as UniMedida', 'unidad_medida.Descripcion as CodSunatMedida', 'articulo.IdTipo', 'articulo.IdUnidadMedida')
            ->where('cotizacion_articulo.IdCotizacion', $idSucursal)
            ->whereNull('cotizacion_articulo.IdPaquetePromocional')
            ->orderBy('cotizacion_articulo.IdCotizaArticulo', 'asc')
            ->get();

        $itemsCotizacionConPaquetePromocional = DB::table('cotizacion_articulo')
            ->join('articulo_paquetePromocional as app', 'cotizacion_articulo.IdPaquetePromocional', '=', 'app.IdPaquetePromocional')
            ->join('articulo', 'app.IdArticulo', '=', 'articulo.IdArticulo')
            ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
            ->select('cotizacion_articulo.*', 'articulo.Ubicacion', 'articulo.IdMarca', 'articulo.Descripcion', 'articulo.Stock', 'articulo.Costo', 'articulo.CantidadTipo', 'articulo.DescuentoTipo', 'cotizacion_articulo.PrecioUnidadReal as artPrecio', 'articulo.PrecioTipo', 'unidad_medida.Nombre as UniMedida', 'unidad_medida.Descripcion as CodSunatMedida', 'articulo.IdTipo', 'articulo.IdUnidadMedida', 'app.CodigoArticulo as Codigo', 'app.IdArticulo', 'app.Cantidad')
            ->where('cotizacion_articulo.IdCotizacion', $idSucursal)
            ->whereNotNull('cotizacion_articulo.IdPaquetePromocional')
            ->orderBy('cotizacion_articulo.IdCotizaArticulo', 'asc')
            ->get();
        $resultado = $itemsCotizacionConPaquetePromocional->concat($itemsCotizacionSinPaquetePromocional);
        return $resultado;
    }

    private function getFechaFiltro($fecha, $fechaIni, $fechaFin)
    {
        if ($fecha == 0) {
            $fechaInicio = '1900-01-01';
            $fechaFinal = Carbon::now();
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 1) {
            $fechaInicio = Carbon::today();
            $fechaFinal = Carbon::now();
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 2) {
            $fechaInicio = Carbon::yesterday();
            $fechaFinal = Carbon::today();
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 3) {
            $datePrev = Carbon::today()->dayOfWeek;
            $date = Carbon::today();
            $fechaInicio = $date->subDays($datePrev - 1);
            $fechaFinal = Carbon::now();
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 4) {
            $datePrev = Carbon::today()->dayOfWeek;
            $date1 = Carbon::today();
            $date2 = Carbon::today();
            $fechaFinal = $date1->subDays($datePrev - 1);
            $fechaInicio = $date2->subDays($datePrev + 6);
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 5) {
            $datePrev = Carbon::today()->day;
            $date = Carbon::today();
            $fechaInicio = $date->subDays($datePrev - 1);
            $fechaFinal = Carbon::now();
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 6) {
            $datePrev = Carbon::today()->day;
            $mesPasado = Carbon::today()->subMonth(1)->firstOfMonth();
            $date1 = Carbon::today();
            $fechaFinal = $date1->subDays($datePrev - 1);
            $fechaInicio = $mesPasado;
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 7) {
            $datePrev = Carbon::today()->firstOfYear();
            $fechaInicio = $datePrev;
            $fechaFinal = Carbon::now();
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 8) {
            $fechaInicio = Carbon::today()->subYear(1)->firstOfYear();
            $fechaFinal = Carbon::today()->subYear(1)->endOfYear();
            return array($fechaInicio, $fechaFinal);
        }
        if ($fecha == 9) {
            $fechaInicio = DateTime::createFromFormat('d/m/Y', $fechaIni);
            $fechaFinal = DateTime::createFromFormat('d/m/Y', $fechaFin);
            $fechaConvertidaInicio = $fechaInicio->format("Y-m-d");
            $fechaConvertidaFinal = $fechaFinal->format("Y-m-d");
            $fechaConvertidaFinal = strtotime('+1 day', strtotime($fechaConvertidaFinal));
            $fechaConvertidaFinal = date('Y-m-d', $fechaConvertidaFinal);
            return array($fechaConvertidaInicio, $fechaConvertidaFinal);
        }
    }
}
