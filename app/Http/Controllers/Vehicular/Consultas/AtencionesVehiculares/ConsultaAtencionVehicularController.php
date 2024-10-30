<?php

namespace App\Http\Controllers\Vehicular\Consultas\AtencionesVehiculares;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use App\Http\Controllers\Operaciones\NumeroALetras;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Http\Request;
use PDF;
use PHPMailer\PHPMailer\Exception;
use Session;

class ConsultaAtencionVehicularController extends Controller
{
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');

        $marcaVehiculo = $this->Marca($idSucursal);
        $modeloVehiculo = $this->Modelo($idSucursal);
        $tipoVehiculo = $this->Tipo($idSucursal);
        $fecha = 5;
        $fechaIni = '';
        $fechaFin = '';
        $fechas = $loadDatos->getFechaFiltro(5, null, null);
        // $vehiculo = $this->atenciones_vehiculares($idSucursal);
        // $vehiculo = $this->atenciones_vehicularesFiltrado($idSucursal, $fecha, $fechaIni, $fechaFin);
        $tipo = '';

        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        // NUEVO CODIGO
        $listaAtenciones = $this->getListaAtenciones($idSucursal, $fechas);
        $listaDocumentosVentas = $listaAtenciones->map(function ($item, $key) {
            return ((object) collect($item)->only(['IdCotizacion', 'IdVentas', 'Documento'])->toArray());
        });

        $listaAtencionesSinCotizacion = $listaAtenciones->whereNull('IdCotizacion')->values();
        $listaAtencionesConCotizacion = $listaAtenciones->whereNotNull('IdCotizacion')->values();
        $vehiculo = $listaAtencionesConCotizacion->unique('IdCotizacion')->values();
        $vehiculo = $vehiculo->concat($listaAtencionesSinCotizacion)->sortDesc()->values();

        $vehiculo = $vehiculo->map(function ($items) use ($listaDocumentosVentas) {
            if ($items->IdCotizacion == "") {
                $items->DocumentosVentas = array();
            } else {
                $documento = $listaDocumentosVentas->where('IdCotizacion', $items->IdCotizacion)->values();
                $items->DocumentosVentas = $documento;
            }
            return $items;
        });
        // FIN

        $array = ['vehiculos' => $vehiculo, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'tipo' => $tipo, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin];
        return view('vehicular/consultas/atencionesVehiculares/consultaAtencionesVehiculares', $array);
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
        $fecha = $req->fecha;
        $fechaIni = trim($req->fechaIni) == false ? '0' : $req->fechaIni;
        $fechaFin = trim($req->fechaFin) == false ? '0' : $req->fechaFin;
        if ($fecha == 9) {
            if ($fechaIni == null || $fechaFin == null) {
                return back()->with('error', 'Completar las fechas para filtrar');
            }
            $dateIni = DateTime::createFromFormat('d/m/Y', $fechaIni);
            $dateFin = DateTime::createFromFormat('d/m/Y', $fechaFin);
            if (strtotime($dateIni->format('Y-m-d')) > strtotime($dateFin->format('Y-m-d'))) {
                return back()->with('error', 'La fecha Inicial no puede ser mayor que la Final');
            }
            $fechaIniConvert = Carbon::createFromFormat('d/m/Y', $fechaIni);
            $fechaFinConvert = Carbon::createFromFormat('d/m/Y', $fechaFin);
            $diferencia = $fechaIniConvert->diffInDays($fechaFinConvert);
        }
        $idSucursal = $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $tipo = 1;
        // $creditosVencidos = $loadDatos->getCreditosVencidosFiltrados($idSucursal, $fecha, $fechaIni, $fechaFin);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        // $vehiculo = $this->atenciones_vehicularesFiltrado($idSucursal, $fecha, $fechaIni, $fechaFin);

        // NUEVO CODIGO
        // $listaDocumentosVentas = $this->getDocumentosVentasAtenciones($idSucursal, $fecha, $fechaIni, $fechaFin);
        $fechas = $loadDatos->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        $listaAtenciones = $this->getListaAtenciones($idSucursal, $fechas);
        $listaDocumentosVentas = $listaAtenciones->map(function ($item, $key) {
            return ((object) collect($item)->only(['IdCotizacion', 'IdVentas', 'Documento'])->toArray());
        });

        $listaAtencionesSinCotizacion = $listaAtenciones->whereNull('IdCotizacion')->values();
        $listaAtencionesConCotizacion = $listaAtenciones->whereNotNull('IdCotizacion')->values();
        $vehiculo = $listaAtencionesConCotizacion->unique('IdCotizacion')->values();
        $vehiculo = $vehiculo->concat($listaAtencionesSinCotizacion)->sortDesc()->values();

        $vehiculo = $vehiculo->map(function ($items) use ($listaDocumentosVentas) {
            if ($items->IdCotizacion == "") {
                $items->DocumentosVentas = array();
            } else {
                $documento = $listaDocumentosVentas->where('IdCotizacion', $items->IdCotizacion)->values();
                $items->DocumentosVentas = $documento;
            }
            return $items;
        });
        // FIN
        $array = ['vehiculos' => $vehiculo, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'tipo' => $tipo, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'fecha' => $fecha, 'fechaInicial' => $fechaIni, 'fechaFinal' => $fechaFin];
        return view('vehicular/consultas/atencionesVehiculares/consultaAtencionesVehiculares', $array);
    }

    public function verBitacora(Request $req, $idAtencion)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi n de usuario Expirado');
        }
        // dd($req->idCotizacion);
        $loadDatos = new DatosController();
        $info = [];

        $bitacoraSelect = $this->atencion_vehicular($idAtencion);
        $venta = $this->verificarCotizacion($bitacoraSelect->IdVentas);
        if ($venta->IdCotizacion != null) {
            $coti = DB::table('cotizacion')
                ->where('IdCotizacion', $venta->IdCotizacion)
                ->first();

            $bitacoraSelect->IdCotizacion = $venta->IdCotizacion;
            $bitacoraSelect->serie = $coti->Serie;
            $bitacoraSelect->Numero = $coti->Numero;
        } else {
            $bitacoraSelect->IdCotizacion = $venta->IdCotizacion;
        }
        if ($bitacoraSelect->IdOperario > 0) {
            $operarioSelect = $loadDatos->getOperarioSelect($bitacoraSelect->IdOperario);
            $operario = $operarioSelect->Nombres;
        } else {
            $operario = 'Genérico';

        }
        $serieNumeroCotizacion = $bitacoraSelect;

        $idUsuario = Session::get('idUsuario');
        $permisos = $loadDatos->getPermisos($idUsuario);

        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $idSucursal = Session::get('idSucursal');
        $fecha = date_create($bitacoraSelect->FechaAtencion);
        $formatoFecha = date_format($fecha, 'd-m-Y');
        $formatoHora = date_format($fecha, 'H:i A');

        // Nuevo Codigo
        $atencionesVehiculares = $this->getAtencionVehicular($bitacoraSelect->IdCotizacion, $idSucursal, $idAtencion);
        for ($i = 0; $i < count($atencionesVehiculares); $i++) {
            $_productos = $this->getItemsAtencion($atencionesVehiculares[$i]->IdVentas);
            $atencionesVehiculares[$i]->Productos = $_productos;
            // if ($atencionesVehiculares[$i]->IdCotizacion != null) {
            // $coti = DB::table('cotizacion')
            // ->where('IdCotizacion', $atencionesVehiculares[$i]->IdCotizacion)
            // ->first();
            // $atencionesVehiculares[$i]->Serie = $coti->Serie;
            // $atencionesVehiculares[$i]->Numero = $coti->Numero;
            // }
        }

        // Fin

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $array = ['bitacoraSelect' => $bitacoraSelect, 'operario' => $operario, 'vehiculo' => $info, 'permisos' => $permisos, 'formatoFecha' => $formatoFecha, 'formatoHora' => $formatoHora, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'atencionesVehiculares' => $atencionesVehiculares, 'serieNumeroCotizacion' => $serieNumeroCotizacion];
        return view('vehicular/consultas/atencionesVehiculares/consultaBitacoraVehicular', $array)->with('status', 'Se registro venta exitosamente');
    }

    // nueva funcion
    public function getAtencionVehicular($idCotizacion, $idSucursal, $idAtencion)
    {
        if ($idCotizacion != null) {
            $vehiculo = DB::table('atencion_vehicular as av')
                ->join('vehiculo as v', 'av.IdVehiculo', '=', 'v.IdVehiculo')
                ->join('cliente as c', 'av.IdCliente', '=', 'c.IdCliente')
                ->join('marca_general as mg', 'mg.IdMarcaGeneral', '=', 'v.IdMarcaVehiculo')
                ->join('modelo_general as mog', 'mog.IdModeloGeneral', '=', 'v.IdModeloVehiculo')
                ->join('ventas', 'av.IdVentas', '=', 'ventas.IdVentas')
                ->select('av.IdAtencion', 'av.FechaAtencion', 'av.Documento', 'av.IdOperario', 'av.Trabajos', 'av.Kilometro', 'av.Horometro', 'av.Observacion', 'av.Total', 'av.SubTotal', 'av.Igv', 'av.Exonerada', 'av.IdVentas', 'v.PlacaVehiculo', 'v.ChasisVehiculo', 'c.RazonSocial', 'c.NumeroDocumento', 'c.Direccion', 'mg.NombreMarca', 'mog.NombreModelo', 'v.Color', 'v.Anio', 'v.FechaSoat', 'v.FechaRevTecnica', 'v.numeroFlota', 'ventas.IdCotizacion', 'ventas.IdTipoMoneda', 'ventas.TipoVenta', DB::RAW('DATE_FORMAT(av.FechaAtencion, "%d-%m-%y") AS FechaAtencion'), DB::RAW('DATE_FORMAT(av.FechaAtencion, "%H:%i %p") AS HoraAtencion'))
                ->where('ventas.IdCotizacion', $idCotizacion)
                ->where('ventas.IdSucursal', $idSucursal)
                ->get();
            return $vehiculo;

        } else {
            $vehiculo = DB::table('atencion_vehicular as av')
                ->join('vehiculo as v', 'av.IdVehiculo', '=', 'v.IdVehiculo')
                ->join('cliente as c', 'av.IdCliente', '=', 'c.IdCliente')
                ->join('marca_general as mg', 'mg.IdMarcaGeneral', '=', 'v.IdMarcaVehiculo')
                ->join('modelo_general as mog', 'mog.IdModeloGeneral', '=', 'v.IdModeloVehiculo')
                ->join('ventas', 'av.IdVentas', '=', 'ventas.IdVentas')
                ->select('av.IdAtencion', 'av.FechaAtencion', 'av.Documento', 'av.IdOperario', 'av.Trabajos', 'av.Kilometro', 'av.Horometro', 'av.Observacion', 'av.Total', 'av.SubTotal', 'av.Igv', 'av.Exonerada', 'av.IdVentas', 'v.PlacaVehiculo', 'v.ChasisVehiculo', 'c.RazonSocial', 'c.NumeroDocumento', 'c.Direccion', 'mg.NombreMarca', 'mog.NombreModelo', 'v.Color', 'v.Anio', 'v.FechaSoat', 'v.FechaRevTecnica', 'v.numeroFlota', 'ventas.IdCotizacion', 'ventas.IdTipoMoneda', 'ventas.TipoVenta', DB::RAW('DATE_FORMAT(av.FechaAtencion, "%d-%m-%y") AS FechaAtencion'), DB::RAW('DATE_FORMAT(av.FechaAtencion, "%H:%i %p") AS HoraAtencion'))
                ->where('av.IdAtencion', $idAtencion)
                ->where('ventas.IdSucursal', $idSucursal)
                ->get();
            return $vehiculo;
        }
    }

    protected function getListaAtenciones($idSucursal, $fechas)
    {
        // $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        try {
            $resultado = DB::table('atencion_vehicular as av')
                ->join('vehiculo as v', 'av.IdVehiculo', '=', 'v.IdVehiculo')
                ->join('cliente as c', 'av.IdCliente', '=', 'c.IdCliente')
                ->join('marca_general as mg', 'mg.IdMarcaGeneral', '=', 'v.IdMarcaVehiculo')
                ->join('tipo_general as tg', 'tg.IdTipoGeneral', '=', 'v.IdTipoVehiculo')
                ->join('ventas', 'av.IdVentas', '=', 'ventas.IdVentas')
                ->join('modelo_general as mog', 'mog.IdModeloGeneral', '=', 'v.IdModeloVehiculo')
                ->select('av.IdAtencion', 'av.FechaAtencion', 'av.Documento', 'av.IdVentas', 'v.PlacaVehiculo', 'c.RazonSocial', 'mg.NombreMarca', 'mog.NombreModelo', 'tg.NombreTipo', 'ventas.IdCotizacion')
                ->where('av.IdSucursal', $idSucursal)
                ->whereBetween('av.FechaAtencion', [$fechas[0], $fechas[1]])
                ->orderBy('av.FechaAtencion', 'desc')
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    protected function getDocumentosVentasAtenciones($idSucursal, $fecha, $fechaIni, $fechaFin)
    {
        $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        try {
            $resultado = DB::table('ventas as v')
                ->select('Idventas', 'IdCotizacion', DB::RAW('CONCAT(Serie, "-" ,Numero) AS DocumentoVenta'))
                ->where('v.IdSucursal', $idSucursal)
                ->whereBetween('v.FechaCreacion', [$fechas[0], $fechas[1]])
                ->whereIn('IdVentas', function ($query) {
                    $query->select('IdVentas')->from('atencion_vehicular');})
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // Fin

    public function descargarPDF(Request $req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        $pdf = $this->generarPDF($req, $id);
        $loadDatos = new DatosController();
        $info = [];

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $rucEmpresa = $empresa->Ruc;

        // $serie = $ventaSelect->Documento;
        $idDoc = 'CT';
        // return $pdf->download($rucEmpresa . '.pdf');
        return $pdf->stream($rucEmpresa . '.pdf');
    }

    protected function verificarCotizacion($idVenta)
    {
        $res = DB::table('ventas')
            ->select('IdCotizacion')
            ->where('IdVentas', $idVenta)
            ->first();
        return $res;
    }

    protected function atenciones_vehiculares($idSucursal)
    {
        $vehiculos = DB::table('atencion_vehicular as av')
            ->join('vehiculo as v', 'av.IdVehiculo', '=', 'v.IdVehiculo')
            ->join('cliente as c', 'av.IdCliente', '=', 'c.IdCliente')
            ->join('marca_general as mg', 'mg.IdMarcaGeneral', '=', 'v.IdMarcaVehiculo')
            ->join('tipo_general as tg', 'tg.IdTipoGeneral', '=', 'v.IdTipoVehiculo')
            ->join('modelo_general as mog', 'mog.IdModeloGeneral', '=', 'v.IdModeloVehiculo')
            ->select('av.IdAtencion', 'av.FechaAtencion', 'av.Documento', 'av.IdVentas', 'v.PlacaVehiculo', 'c.RazonSocial', 'mg.NombreMarca', 'mog.NombreModelo', 'tg.NombreTipo', 'v.Color', 'v.Anio')
            ->where('v.IdSucursal', $idSucursal)
            ->whereRaw('YEAR(av.FechaAtencion)=YEAR(NOW())')
            ->whereRaw('MONTH(av.FechaAtencion) = MONTH(NOW())')
            ->orderBy('av.FechaAtencion', 'desc')
            ->get();

        return $vehiculos;
    }

    protected function atencion_vehicular($id)
    {
        $vehiculo = DB::table('atencion_vehicular as av')
            ->join('vehiculo as v', 'av.IdVehiculo', '=', 'v.IdVehiculo')
            ->join('cliente as c', 'av.IdCliente', '=', 'c.IdCliente')
        //AGREGUE  TRES JOIN Y CAMPOS DE CONSULTA MARCA Y MODELO
            ->join('marca_general as mg', 'mg.IdMarcaGeneral', '=', 'v.IdMarcaVehiculo')
            ->join('modelo_general as mog', 'mog.IdModeloGeneral', '=', 'v.IdModeloVehiculo')
            ->join('ventas', 'av.IdVentas', '=', 'ventas.IdVentas')
            ->select('av.IdAtencion', 'av.FechaAtencion', 'av.Documento', 'av.IdOperario', 'av.Trabajos', 'av.Kilometro', 'av.Horometro', 'av.Observacion', 'av.Total', 'av.SubTotal', 'av.Igv', 'av.Exonerada', 'av.IdVentas', 'v.PlacaVehiculo', 'v.ChasisVehiculo', 'c.RazonSocial', 'c.NumeroDocumento', 'c.Direccion', 'mg.NombreMarca', 'mog.NombreModelo', 'v.Color', 'v.Anio', 'v.FechaSoat', 'v.FechaRevTecnica', 'v.numeroFlota', 'ventas.IdCotizacion', 'ventas.IdTipoMoneda', 'ventas.TipoVenta')
            ->where('av.IdAtencion', $id)
            ->first();
        // FIN 'cotizacion.serie', 'cotizacion.Numero', 'cotizacion.IdCotizacion'
        return $vehiculo;
    }

    protected function atenciones_vehicularesFiltrado($idSucursal, $fecha, $fechaIni, $fechaFin)
    {
        $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        try {
            $resultado = DB::table('atencion_vehicular as av')
                ->join('vehiculo as v', 'av.IdVehiculo', '=', 'v.IdVehiculo')
                ->join('cliente as c', 'av.IdCliente', '=', 'c.IdCliente')
                ->join('marca_general as mg', 'mg.IdMarcaGeneral', '=', 'v.IdMarcaVehiculo')
                ->join('tipo_general as tg', 'tg.IdTipoGeneral', '=', 'v.IdTipoVehiculo')
                ->join('modelo_general as mog', 'mog.IdModeloGeneral', '=', 'v.IdModeloVehiculo')
                ->select('av.IdAtencion', 'av.FechaAtencion', 'av.Documento', 'av.IdVentas', 'v.PlacaVehiculo', 'c.RazonSocial', 'mg.NombreMarca', 'mog.NombreModelo', 'tg.NombreTipo')
                ->where('av.IdSucursal', $idSucursal)
                ->whereBetween('av.FechaAtencion', [$fechas[0], $fechas[1]])
                ->orderBy('av.FechaAtencion', 'desc')
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCreditosVencidosFiltrados($idSucursal, $fecha, $fechaIni, $fechaFin)
    {
        $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        try {
            $resultado = DB::table('fecha_pago')
                ->join('ventas', 'fecha_pago.IdVenta', '=', 'ventas.IdVentas')
                ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                ->select('cliente.Nombre as Cliente', 'fecha_pago.*', 'ventas.Serie', 'ventas.Numero', 'ventas.Nota', 'ventas.MotivoAnulacion', DB::raw("(fecha_pago.Importe - fecha_pago.ImportePagado) as Deuda"))
                ->where('ventas.IdSucursal', $idSucursal)
                ->whereNotIn('fecha_pago.Estado', [2])
                ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                ->where('fecha_pago.DiasPasados', '>', 0)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    protected function getItemsAtencion($idVenta)
    {
        $vehiculo = DB::table('ventas_articulo as va')
            ->join('articulo as a', 'va.IdArticulo', '=', 'a.IdArticulo')
            ->select('va.*', 'a.Codigo as CodigoBarra', 'a.Descripcion', 'a.IdTipo')
            ->where('va.IdVentas', $idVenta)
            ->get();
        return $vehiculo;
    }

    // cONSULTAR COTIZACION
    // public function consultarCotizacion($idVenta){
    //     $detalleCotizacion = DB::table('ventas')
    //     ->join('cotizacion','ventas.IdCotizacion', '=', 'cotizacion.IdCotizacion')
    //    ->select('cotizacion.serie', 'cotizacion.Numero')
    //    ->where('ventas.IdVentas', $idVenta)
    //   ->get();
    // }

    protected function Marca($idSucursal)
    {
        $marca = DB::table('marca_general')
            ->where('IdSucursal', $idSucursal)
            ->where('UsoMarca', 1)
            ->get();
        return $marca;
    }

    protected function Modelo($idSucursal)
    {
        $modelo = DB::table('modelo_general')
            ->where('IdSucursal', $idSucursal)
            ->where('UsoModelo', 1)
            ->get();
        return $modelo;
    }

    protected function Tipo($idSucursal)
    {
        $tipo = DB::table('tipo_general')
            ->where('UsoTipo', 1)
            ->get();
        return $tipo;
    }

    private function completarCeros($numero)
    {
        $numeroConCeros = str_pad($numero, 8, "0", STR_PAD_LEFT);
        return $numeroConCeros;
    }

    private function generarPDF($req, $id)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        $loadDatos = new DatosController();
        $info = [];

        $atencionSelect = $this->atencion_vehicular($id);
        // dd($atencionSelect);
        if ($atencionSelect->IdOperario > 0) {
            $operarioSelect = $loadDatos->getOperarioSelect($atencionSelect->IdOperario);
            $operario = $operarioSelect->Nombres;
        } else {
            $operario = 'Genérico';

        }
        // dd($atencionSelect);

        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $fecha = date_create($atencionSelect->FechaAtencion);
        $formatoFecha = date_format($fecha, 'd/m/Y');
        $formatoHora = date_format($fecha, 'H:i A');
        $convertirLetras = new NumeroALetras();
        $importeLetras = $convertirLetras->convertir($atencionSelect->Total, 'soles');
        $exp = explode("\n", $atencionSelect->Observacion);
        $lineas = count($exp);
        if ($lineas <= 5) {
            $lineas = $lineas * 8;
        } else if ($lineas > 5 && $lineas <= 10) {
            $lineas = $lineas * 10;
        } else {
            $lineas = $lineas * 12;
        }

        $items = $this->getItemsAtencion($atencionSelect->IdVentas);

        // dd($items);
        // SE AGREGO LOS ITEMPRO Y ITEMSERV
        $itemsProd = $items->where('IdTipo', 1);
        // dd($itemsProd);
        $itemsServ = $items->where('IdTipo', 2);
        // FIN
        $sucursal = $loadDatos->getSucursales($usuarioSelect->CodigoCliente, $usuarioSelect->IdOperador)->where("Principal", 0)->first();
        $array = ['atencionSelect' => $atencionSelect, 'itemsProd' => $itemsProd, 'itemsServ' => $itemsServ, 'items' => $items, 'numeroCeroIzq' => '', 'ventaSelect' => $atencionSelect, 'operario' => $operario, 'lineas' => $lineas,
            'formatoFecha' => $formatoFecha, 'formatoHora' => $formatoHora, 'importeLetras' => $importeLetras, 'empresa' => $empresa, 'sucursal' => $sucursal];
        view()->share($array);

        $pdf = PDF::loadView('pdf/atencionVehicular')->setPaper('a4', 'portrait');

        return $pdf;
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

// $a=$this->getAtencionVehicular();
// // dd($a);
// $datos = [22784, 22785, 22786];
// for ($i=0; $i < count($datos); $i++) {
//     $items = $this->getItemsAtencion($datos[$i]);
// }
//     $a = [];
//     foreach ($items as $key => $value) {
//         foreach ($items as $key => $value) {
//             $a[$key] = $value;
//         }
//     }
// dd($a);
// NUevo codigo
// public function getAtencionVehicular(){
//     $vehiculo = DB::table('atencion_vehicular as av')
//         ->join('vehiculo as v', 'av.IdVehiculo', '=', 'v.IdVehiculo')
//         ->join('cliente as c', 'av.IdCliente', '=', 'c.IdCliente')
//         ->join('marca_general as mg', 'mg.IdMarcaGeneral', '=', 'v.IdMarcaVehiculo')
//         ->join('modelo_general as mog', 'mog.IdModeloGeneral', '=', 'v.IdModeloVehiculo')
//         ->join('ventas', 'av.IdVentas', '=', 'ventas.IdVentas')
//         ->select('av.IdAtencion', 'av.FechaAtencion', 'av.Documento', 'av.IdOperario', 'av.Trabajos', 'av.Kilometro', 'av.Horometro', 'av.Observacion', 'av.Total', 'av.SubTotal', 'av.Igv', 'av.Exonerada', 'av.IdVentas', 'v.PlacaVehiculo', 'v.ChasisVehiculo', 'c.RazonSocial', 'c.NumeroDocumento', 'c.Direccion', 'mg.NombreMarca', 'mog.NombreModelo', 'v.Color', 'v.Anio', 'v.FechaSoat', 'v.FechaRevTecnica', 'v.numeroFlota', 'ventas.IdCotizacion', 'ventas.IdTipoMoneda', 'ventas.TipoVenta')
//         ->where('ventas.IdCotizacion', 1457)
//         ->where('ventas.IdSucursal', 112)
//         ->get();
//     return $vehiculo;
// }
// Fin

// protected function getVentasAtenciones($idSucursal)
// {
//     try {
//         $resultado = DB::table('ventas as v')
//             ->select('Idventas', DB::RAW('CONCAT(Serie, "-" ,Numero) AS DocumentoVenta'))
//             ->where('v.IdSucursal', $idSucursal)
//             ->whereIn('IdVentas', function ($query) {
//                 $query->select('IdVentas')->from('atencion_vehicular');})
//             ->get();
//         return $resultado;
//     } catch (Exception $ex) {
//         echo $ex->getMessage();
//     }
// }

// $listaVentas = $this->getVentasAtenciones($idSucursal);
// $listaVehiculo = $this->getListaAtenciones($idSucursal, $fecha, $fechaIni, $fechaFin);
// $vehiculo = $listaVehiculo->unique('IdCotizacion')->values();
// $vehiculo = $vehiculo->map(function ($items) use ($listaVentas) {
//     $documento = $listaVentas->where('IdCotizacion', $items->IdCotizacion)->values();
//     $items->DocumentosVentas = $documento;
//     return $items;
// });
// dd($vehiculo);
