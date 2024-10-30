<?php

namespace App\Http\Controllers\Vehicular\GestionTaller;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use App\Traits\ArchivosS3Trait;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use PDF;
use Session;
use Storage;

class ControlCalidadController extends Controller
{
    use ArchivosS3Trait;
    public function index(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);

        $controlCalidad = $this->getControlesCalidad($idSucursal);
        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'controlCalidad' => $controlCalidad];

        return view('vehicular/gestionTaller/controlCalidad/index', $array);
    }

    private function getControlesCalidad($idSucursal)
    {
        $datos = DB::table('controles_calidad')
            ->join('vehiculo', 'controles_calidad.IdVehiculo', '=', 'vehiculo.IdVehiculo')
            ->join('cliente', 'vehiculo.IdCliente', '=', 'cliente.Idcliente')
            ->join('usuario', 'controles_calidad.IdUsuarioCreacion', '=', 'usuario.IdUsuario')
            ->join('cotizacion', 'controles_calidad.IdCotizacion', '=', 'cotizacion.IdCotizacion')
            ->where('controles_calidad.IdSucursal', $idSucursal)
            ->where('vehiculo.IdSucursal', $idSucursal)
            ->select('controles_calidad.*', 'vehiculo.PlacaVehiculo', 'usuario.Nombre', 'cliente.IdCliente', 'cliente.RazonSocial as NombreCliente', 'cotizacion.IdEstadoCotizacion', DB::raw('concat(cotizacion.Serie, "-", cotizacion.Numero) as CodigoCotizacion'), 'cliente.Telefono')
            ->get();
        return $datos;
    }

    public function create(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        // $cotizaciones = $this->getVehiculosConCotizacionConCotizacion($idSucursal)->unique('Placa');
        $cotizaciones = $this->getVehiculosConCotizacion($idSucursal);
        // dd($cotizaciones);

        if (Cache::has('partesVehiculo')) {
            $partesVehiculo = Cache::get('partesVehiculo');
        } else {
            $partesVehiculo = $this->getPartesVehiculo();
            Cache::put('partesVehiculo', $partesVehiculo);
        }

        $debajoVehiculo = $partesVehiculo->where('Grupo', 'DebajoVehiculo')->sortBy('Nombre')->Values();
        $partesBajoCapo = $partesVehiculo->where('Grupo', 'BajoCapo')->sortBy('Nombre')->Values();
        $nivelesLiquido = $partesVehiculo->where('Grupo', 'Liquidos')->sortBy('Nombre')->Values();
        $filtrosVehiculo = $partesVehiculo->where('Grupo', 'Filtros')->sortBy('Nombre')->Values();
        $frenosVehiculo = $partesVehiculo->where('Grupo', 'Frenos')->sortBy('Posicion')->Values();
        $limpiaParabrisas = $partesVehiculo->where('Grupo', 'LimpiaParabrisas')->sortBy('Posicion')->Values();
        $llantas = $partesVehiculo->where('Grupo', 'Llantas')->sortBy('Posicion')->Values();
        $luces = $partesVehiculo->where('Grupo', 'Luces')->sortBy('IdParteVehiculo')->Values();
        // dd($luces);
        $presionNeumatico = $partesVehiculo->where('Grupo', 'PresionNeumaticos')->values();
        $dentroVehiculo = $partesVehiculo->where('Grupo', 'DentroVehiculo')->values();

        $correlativoControlCalidad = $this->generarCorrelativoControlCalidad('CC', $idUsuario, $idSucursal);

        $array = ['usuarioSelect' => $usuarioSelect, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'cotizaciones' => $cotizaciones, 'debajoVehiculo' => $debajoVehiculo, 'partesBajoCapo' => $partesBajoCapo, 'nivelesLiquido' => $nivelesLiquido, 'filtrosVehiculo' => $filtrosVehiculo, 'frenosVehiculo' => $frenosVehiculo, 'limpiaParabrisas' => $limpiaParabrisas, 'llantas' => $llantas, 'luces' => $luces, 'presionNeumatico' => $presionNeumatico, 'dentroVehiculo' => $dentroVehiculo, 'serie' => $correlativoControlCalidad->Serie, 'numero' => $correlativoControlCalidad->Numero];

        return view('vehicular/gestionTaller/controlCalidad/crear', $array);
    }

    public function generarCorrelativoControlCalidad($texto, $idUsuario, $idSucursal)
    {
        $numeroDB = $this->getCorrelativoActual($idUsuario, $idSucursal);
        if ($numeroDB) {
            $numero = str_pad($numeroDB->Numero + 1, 8, '0', STR_PAD_LEFT);
        } else {
            $numero = str_pad(1, 8, '0', STR_PAD_LEFT);
        }
        $loadDatos = new DatosController();

        $sucursal = $loadDatos->getSucursalSelect($idSucursal);
        $numeroOrden = $sucursal->Orden;
        $serieCeros = str_pad($numeroOrden, 2, '0', STR_PAD_LEFT);
        $serie = $texto . $numeroOrden . '' . $serieCeros;

        return (object) ['Serie' => $serie, 'Numero' => $numero];
    }

    private function getCorrelativoActual($idUsuario, $idSucursal)
    {
        try {
            $resultado = DB::table('controles_calidad')
                ->where('IdUsuarioCreacion', $idUsuario)
                ->where('IdSucursal', $idSucursal)
                ->orderBy('IdControlCalidad', 'desc')
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function store(Request $req)
    {
        try {
            DB::beginTransaction();
            $loadDatos = new DatosController();
            $idSucursal = Session::get('idSucursal');
            $radioEstadoPrioridad = $req->radioEstadoPrioridad;
            $diagnostico = $req->diagnostico;
            $recomendaciones = $req->recomendaciones;
            $fechaCreacion = Carbon::now()->toDateTimeString();
            $idVehiculo = $req->selectVehiculo;
            $serie = $req->serie;
            $numero = $req->numero;
            $idUsuario = Session::get('idUsuario');
            $idCotizacion = $req->idCotizacion;
            if ($idVehiculo == 0) {
                return back()->with('error', 'No ha seleccionado la Placa')->withInput();
            }
            $respuestaExistenciaCorrelativo = $this->existeCorrelativo($idSucursal, $serie, $numero);
            if ($respuestaExistenciaCorrelativo === true) {
                $resultado = DB::table('controles_calidad')
                    ->select(DB::raw('MAX(Numero) as Numero'))
                    ->where('IdSucursal', $idSucursal)
                    ->where('IdUsuarioCreacion', $idUsuario)
                    ->first();
                $numero = str_pad($resultado->Numero + 1, 8, '0', STR_PAD_LEFT);
            }

            $arrayDatos = ['IdVehiculo' => $idVehiculo, 'IdSucursal' => $idSucursal, 'IdUsuarioCreacion' => $idUsuario, 'FechaCreacion' => $fechaCreacion, 'Serie' => $serie, 'Numero' => $numero, 'Prioridad' => $radioEstadoPrioridad, 'Diagnostico' => $diagnostico, 'Recomendacion' => $recomendaciones, 'IdCotizacion' => $idCotizacion, 'IdOperario' => $req->inputIdMecanico, 'OpcionFirmaAsesor' => $req->checkFirmaAsesorComercial, 'OpcionFirmaMecanico' => $req->checkFirmaMecanico];
            DB::table('controles_calidad')
                ->insert($arrayDatos);

            $controlCalidad = DB::table('controles_calidad')
                ->select('IdControlCalidad')
                ->orderBy('IdControlCalidad', 'desc')
                ->first();

            foreach ($req->idPartesBajoVehiculo as $item) {
                $radioEstado = 'radioEstadoPartesInferiores' . $item;
                DB::table('controlCalidad_debajoVehiculo')
                    ->insert(['IdControlCalidad' => $controlCalidad->IdControlCalidad, 'IdParteVehiculo' => $item, 'Estado' => $req->get($radioEstado)]);
            }

            foreach ($req->idPartesBajoCapo as $item) {
                $radioEstado = 'radioEstadoPartesBajoCapo' . $item;
                DB::table('controlCalidad_bajoCapo')
                    ->insert(['IdControlCalidad' => $controlCalidad->IdControlCalidad, 'IdParteVehiculo' => $item, 'Estado' => $req->get($radioEstado)]);
            }

            foreach ($req->idNivelesLiquido as $item) {
                $radioEstado = 'radioNivelLiquido' . $item;
                DB::table('controlCalidad_fluidos')
                    ->insert(['IdControlCalidad' => $controlCalidad->IdControlCalidad, 'IdParteVehiculo' => $item, 'Estado' => $req->get($radioEstado)]);
            }
            foreach ($req->idFiltros as $item) {
                $radioEstado = 'radioEstadoFiltro' . $item;
                DB::table('controlCalidad_filtros')
                    ->insert(['IdControlCalidad' => $controlCalidad->IdControlCalidad, 'IdParteVehiculo' => $item, 'Estado' => $req->get($radioEstado)]);
            }

            foreach ($req->idFrenos as $item) {
                $radioEstado = 'radioEstadoFreno' . $item;
                $radioMedida = 'radioMedida' . $item;

                DB::table('controlCalidad_frenos')
                    ->insert(['IdControlCalidad' => $controlCalidad->IdControlCalidad, 'IdParteVehiculo' => $item, 'Estado' => $req->get($radioEstado), 'Medida' => $req->get($radioMedida)]);
            }
            foreach ($req->idlimpiaparabrisas as $item) {
                $radioEstado = 'radioEstadoLimpiaparabrisas' . $item;
                DB::table('controlCalidad_limpiaparabrisas')
                    ->insert(['IdControlCalidad' => $controlCalidad->IdControlCalidad, 'IdParteVehiculo' => $item, 'Estado' => $req->get($radioEstado)]);

            }
            foreach ($req->idLlantas as $item) {
                $radioEstado = 'radioEstadoLlanta' . $item;
                DB::table('controlCalidad_neumaticos')
                    ->insert(['IdControlCalidad' => $controlCalidad->IdControlCalidad, 'IdParteVehiculo' => $item, 'Estado' => $req->get($radioEstado)]);
            }
            foreach ($req->idLuces as $item) {
                $radioEstado = 'radioEstadoLuces' . $item;
                DB::table('controlCalidad_luces')
                    ->insert(['IdControlCalidad' => $controlCalidad->IdControlCalidad, 'IdParteVehiculo' => $item, 'Estado' => $req->get($radioEstado)]);
            }
            foreach ($req->idPresionNeumatico as $item) {
                $radioPresion = 'radioPresionNeumatico' . $item;
                DB::table('controlCalidad_presionNeumatico')
                    ->insert(['IdControlCalidad' => $controlCalidad->IdControlCalidad, 'IdParteVehiculo' => $item, 'Presion' => $req->get($radioPresion)]);
            }

            foreach ($req->idPartesDentroVehiculo as $item) {
                $radioEstado = 'radioEstadoPartesInterior' . $item;
                DB::table('controlCalidad_dentroVehiculo')
                    ->insert(['IdControlCalidad' => $controlCalidad->IdControlCalidad, 'IdParteVehiculo' => $item, 'Estado' => $req->get($radioEstado)]);
            }
            DB::commit();
            DB::table('cotizacion')->where('IdCotizacion', $idCotizacion)->update(['AplicaControlCalidad' => 'Si']);

            return redirect()->route('controlCalidad.index')->with('success', 'El control de Calidad se creo Correctamente');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Ocurrio un problema al generar el contol de calidad, comuniquese con soporte')->withInput();
        }
    }

    public function edit(Request $req, $idControl)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
        }
        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $controlCalidad = $this->getDetalleControlCalidad($idControl);
        // dd($controlCalidad);
        $array = ['usuarioSelect' => $usuarioSelect, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'controlCalidad' => $controlCalidad];
        return view('vehicular/gestionTaller/controlCalidad/editar', $array);
    }

    public function update(Request $req, $idControl)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
        }
        try {
            DB::beginTransaction();
            $fechaModificacion = Carbon::now()->toDateTimeString();
            $diagnostico = $req->diagnostico;
            $recomendaciones = $req->recomendaciones;

            $arrayDatos = ['IdUsuarioModificacion' => $idUsuario, 'FechaModificacion' => $fechaModificacion, 'Diagnostico' => $diagnostico, 'Recomendacion' => $recomendaciones, 'Prioridad' => $req->radioEstadoPrioridad, 'OpcionFirmaAsesor' => $req->checkFirmaAsesorComercial, 'OpcionFirmaMecanico' => $req->checkFirmaMecanico];
            DB::table('controles_calidad')
                ->where('IdControlCalidad', $idControl)
                ->update($arrayDatos);

            foreach ($req->idPartesBajoVehiculo as $item) {
                $radioEstado = 'radioEstadoPartesInferiores' . $item;
                DB::table('controlCalidad_debajoVehiculo')
                    ->where('IdControlCalidad', $idControl)
                    ->where('IdParteVehiculo', $item)
                    ->update(['Estado' => $req->get($radioEstado)]);
            }

            foreach ($req->idPartesBajoCapo as $item) {
                $radioEstado = 'radioEstadoPartesBajoCapo' . $item;
                DB::table('controlCalidad_bajoCapo')
                    ->where('IdControlCalidad', $idControl)
                    ->where('IdParteVehiculo', $item)
                    ->update(['Estado' => $req->get($radioEstado)]);
            }

            foreach ($req->idNivelesLiquido as $item) {
                $radioEstado = 'radioNivelLiquido' . $item;
                DB::table('controlCalidad_fluidos')
                    ->where('IdControlCalidad', $idControl)
                    ->where('IdParteVehiculo', $item)
                    ->update(['Estado' => $req->get($radioEstado)]);
            }
            foreach ($req->idFiltros as $item) {
                $radioEstado = 'radioEstadoFiltro' . $item;
                DB::table('controlCalidad_filtros')
                    ->where('IdControlCalidad', $idControl)
                    ->where('IdParteVehiculo', $item)
                    ->update(['Estado' => $req->get($radioEstado)]);
            }

            foreach ($req->idFrenos as $item) {
                $radioEstado = 'radioEstadoFreno' . $item;
                $radioMedida = 'radioMedida' . $item;
                DB::table('controlCalidad_frenos')
                    ->where('IdControlCalidad', $idControl)
                    ->where('IdParteVehiculo', $item)
                    ->update(['Estado' => $req->get($radioEstado), 'Medida' => $req->get($radioMedida)]);
            }
            foreach ($req->idlimpiaparabrisas as $item) {
                $radioEstado = 'radioEstadoLimpiaparabrisas' . $item;
                DB::table('controlCalidad_limpiaparabrisas')
                    ->where('IdControlCalidad', $idControl)
                    ->where('IdParteVehiculo', $item)
                    ->update(['Estado' => $req->get($radioEstado)]);

            }
            foreach ($req->idLlantas as $item) {
                $radioEstado = 'radioEstadoLlanta' . $item;
                DB::table('controlCalidad_neumaticos')
                    ->where('IdControlCalidad', $idControl)
                    ->where('IdParteVehiculo', $item)
                    ->update(['Estado' => $req->get($radioEstado)]);
            }
            foreach ($req->idLuces as $item) {
                $radioEstado = 'radioEstadoLuces' . $item;
                DB::table('controlCalidad_luces')
                    ->where('IdControlCalidad', $idControl)
                    ->where('IdParteVehiculo', $item)
                    ->update(['Estado' => $req->get($radioEstado)]);
            }
            foreach ($req->idPresionNeumatico as $item) {
                $radioPresion = 'radioPresionNeumatico' . $item;
                DB::table('controlCalidad_presionNeumatico')
                    ->where('IdControlCalidad', $idControl)
                    ->where('IdParteVehiculo', $item)
                    ->update(['Presion' => $req->get($radioPresion)]);
            }

            foreach ($req->idPartesDentroVehiculo as $item) {
                $radioEstado = 'radioEstadoPartesInterior' . $item;
                DB::table('controlCalidad_dentroVehiculo')
                    ->where('IdControlCalidad', $idControl)
                    ->where('IdParteVehiculo', $item)
                    ->update(['Estado' => $req->get($radioEstado)]);
            }

            DB::commit();
            return redirect()->route('controlCalidad.show', $idControl)->with('success', 'El control de Calidad se actualizo Correctamente');

        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Ocurrio un problema al generar el contol de calidad, comoniquese con soporte');
        }
    }

    public function show(Request $req, $idControl)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesi�n de usuario Expirado');
        }

        $loadDatos = new DatosController();
        $idSucursal = Session::get('idSucursal');
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $controlCalidad = $this->getDetalleControlCalidad($idControl);
        // dd($controlCalidad);
        $numeroCelular = $controlCalidad->Telefono;
        if ($numeroCelular != null) {
            if (str_starts_with($numeroCelular, 9) === true) {
                $numeroCelular = $numeroCelular;
            } else {
                $numeroCelular = '';
            }
        }

        $array = ['permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'controlCalidad' => $controlCalidad, 'numeroCelular' => $numeroCelular, 'idDocumento' => $idControl];

        return view('vehicular/gestionTaller/controlCalidad/documentoControlCalidad', $array);

    }

    public function existeCorrelativo($idSucursal, $serie, $numero)
    {
        $datos = DB::table('controles_calidad')
            ->where('IdSucursal', $idSucursal)
            ->where('Serie', $serie)
            ->where('Numero', $numero)
            ->exists();
        return $datos;
    }

    protected function getVehiculosConCotizacion($idSucursal)
    {
        try {
            // $vehiculos = DB::table('vehiculo')
            //     ->join('cliente', 'vehiculo.IdCliente', '=', 'cliente.IdCliente')
            //     ->select(DB::raw('concat(cliente.RazonSocial, " -  Placa : ", vehiculo.PlacaVehiculo) as RazonSocial'), 'vehiculo.IdVehiculo')
            //     ->where('vehiculo.IdSucursal', $idSucursal)
            //     ->where('vehiculo.Estado', 1)
            //     ->get();
            $vehiculos = DB::table('cotizacion')
                ->join('vehiculo', 'cotizacion.campo0', '=', 'vehiculo.IdVehiculo')
                ->join('cliente', 'vehiculo.IdCliente', '=', 'cliente.IdCliente')
                ->select(DB::raw('concat(cliente.RazonSocial, " -  Placa : ", vehiculo.PlacaVehiculo) as RazonSocial'), 'vehiculo.IdVehiculo', 'cotizacion.IdCotizacion', 'cotizacion.TipoCotizacion')
                ->where('cotizacion.IdSucursal', $idSucursal)
                ->where('TipoCotizacion', 2)
                ->where('cotizacion.IdOperario', '!=', 0)
                ->whereIn('IdEstadoCotizacion', [2, 3])
                ->groupBy('IdVehiculo')
                ->get();
            return $vehiculos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getVehiculo(Request $req)
    {
        if ($req->ajax()) {
            $loadDatos = new DatosController();
            $placa = $req->placa;
            $datos = DB::table('vehiculo')
                ->join('cotizacion', 'vehiculo.Idvehiculo', '=', 'cotizacion.campo0')
                ->join('tipo_atencion', 'cotizacion.IdtipoAtencion', '=', 'tipo_atencion.IdTipoAtencion')
                ->select('vehiculo.PlacaVehiculo', 'vehiculo.Color', 'vehiculo.Anio', 'tipo_atencion.Descripcion as TipoAtencion', 'cotizacion.Serie', 'cotizacion.Numero', 'cotizacion.IdCotizacion', 'cotizacion.IdEstadoCotizacion', 'cotizacion.Campo1 as Kilometraje', 'cotizacion.FechaCreacion', 'IdOperario', 'cotizacion.AplicaControlCalidad')
                ->where('IdVehiculo', $placa)
                ->where('cotizacion.IdOperario', '!=', 0)
                ->where('vehiculo.IdSucursal', 112)
                ->whereIn('cotizacion.IdEstadoCotizacion', [2, 3])
                ->get();
            $datos->map(function ($item, $key) use ($datos) {
                if ($item->IdOperario != 0) {
                    $datosOperario = DB::table('operario')->where('IdOperario', $item->IdOperario)->select('IdOperario', 'Nombres', 'ImagenFirma')->first();
                    $datos[$key]->NombreOperario = $datosOperario->Nombres;
                    $datos[$key]->ImagenFirma = $datosOperario->ImagenFirma;
                    $datos[$key]->IdOperario = $datosOperario->IdOperario;

                } else {
                    $datos[$key]->NombreOperario = '';
                    $datos[$key]->ImagenFirma = '';
                    $datos[$key]->IdOperario = '';
                }
            });

            return Response($datos);
        }
    }

    private function getCotizacion($idSucursal, $placa)
    {
        $cotizacion = DB::table('cotizacion')
            ->join('vehiculo', 'vehiculo.IdCliente', '=', 'cotizacion.IdCliente')
            ->join('estados_cotizacion', 'cotizacion.IdEstadoCotizacion', '=', 'estados_cotizacion.IdEstadoCotizacion')
            ->select('*', 'estados_cotizacion.Descripcion', 'vehiculo.IdVehiculo')
            ->where('cotizacion.IdSucursal', $idSucursal)
            ->where('vehiculo.PlacaVehiculo', $placa)
            ->whereIn('cotizacion.IdEstadoCotizacion', [1, 2, 3])
            ->where('TipoCotizacion', 2)
            ->get();
        return $cotizacion;
    }

    public function getPartesVehiculo()
    {
        $datos = DB::table('partes_vehiculo')
            ->where('Estado', 'E')
            ->orderBy('Descripcion', 'asc')
            ->get();
        return $datos;
    }

    public function getDetalleControlCalidad($id)
    {
        $datos = DB::table('controles_calidad')
            ->join('usuario', 'controles_calidad.IdUsuarioCreacion', '=', 'usuario.IdUsuario')
            ->join('vehiculo', 'controles_calidad.IdVehiculo', '=', 'vehiculo.IdVehiculo')
            ->join('marca_general', 'vehiculo.IdMarcaVehiculo', '=', 'marca_general.IdMarcaGeneral')
            ->join('modelo_general', 'vehiculo.IdModeloVehiculo', '=', 'modelo_general.IdModeloGeneral')
            ->join('cliente', 'vehiculo.IdCliente', '=', 'cliente.IdCliente')
            ->join('cotizacion', 'controles_calidad.IdCotizacion', '=', 'cotizacion.IdCotizacion')
            ->join('tipo_atencion', 'cotizacion.IdTipoAtencion', '=', 'tipo_atencion.IdTipoAtencion')
            ->join('operario', 'controles_calidad.IdOperario', '=', 'operario.IdOperario')
            ->select('controles_calidad.*', 'vehiculo.ChasisVehiculo', 'vehiculo.FechaSoat', 'vehiculo.FechaRevTecnica', 'vehiculo.Color', 'vehiculo.PlacaVehiculo', 'vehiculo.Anio', 'cliente.IdCliente', 'cliente.RazonSocial', 'cliente.NumeroDocumento', 'cliente.Direccion', 'cliente.Telefono', 'usuario.Nombre as NombreAsesorComercial', 'usuario.ImagenFirma as FirmaAsesorComercial', 'modelo_general.NombreModelo', 'marca_general.NombreMarca', 'cotizacion.IdEstadoCotizacion', 'cotizacion.FechaCreacion as FechaCotizacion', 'tipo_atencion.Descripcion as TipoAtencion', 'cotizacion.Campo1 as Kilometraje', 'operario.ImagenFirma as FirmaMecanico', 'operario.Nombres as NombreOperario')
            ->where('IdControlCalidad', $id)
            ->first();

        // if ($datos->IdOperario != 0) {
        //     $datosOperario = DB::table('operario')->where('IdOperario', $datos->IdOperario)->select('IdOperario', 'ImagenFirma', 'Nombres')->first();
        //     $datos->FimaMecanico = $datosOperario->ImagenFirma;
        //     $datos->NombreOperario = $datosOperario->Nombres;
        // } else {
        //     $datos->FimaMecanico = '';
        //     $datos->NombreOperario = '';
        // }

        // $datos->DetallePartes = DB::table('controlCalidad_partesVehiculo as cp')
        //     ->join('partes_vehiculo as pv', 'cp.IdParteVehiculo', '=', 'pv.IdParteVehiculo')
        //     ->select('cp.Estado', 'pv.IdParteVehiculo', 'pv.Descripcion', 'pv.Grupo', 'pv.Posicion', 'cp.Medida')
        //     ->where('IdControlCalidad', $datos->IdControlCalidad)
        //     ->get();
        $datos->datosBajoCapo = $this->getDatosBajoCapo($datos->IdControlCalidad);
        $datos->datosDebajoVehiculo = $this->getDatosDebajoVehiculo($datos->IdControlCalidad);
        $datos->datosDentroVehiculo = $this->getDatosDentroVehiculo($datos->IdControlCalidad);
        $datos->datosFiltros = $this->getDatosFiltros($datos->IdControlCalidad);
        $datos->datosFluidos = $this->getDatosFluidos($datos->IdControlCalidad);
        $datos->datosFrenos = $this->getDatosFrenos($datos->IdControlCalidad);
        $datos->datosLimpiaParabrisas = $this->getDatosLimpiaParabrisas($datos->IdControlCalidad);
        $datos->datosLuces = $this->getDatosLuces($datos->IdControlCalidad);
        $datos->datosNeumaticos = $this->getDatosNeumaticos($datos->IdControlCalidad);
        $datos->datosPresionNeumatico = $this->getDatosPresionNeumaticos($datos->IdControlCalidad);

        return $datos;
    }
    public function generarPdfControlCalidad(Request $req, $id, $descripcion)
    {
        if ($req->session()->has('idUsuario')) {
            $loadDatos = new DatosController();
            $idUsuario = Session::get('idUsuario');
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
            $rucEmpresa = $empresa->Ruc;
            $sucursal = $loadDatos->getSucursales($usuarioSelect->CodigoCliente, $usuarioSelect->IdOperador)->where("Principal", 0)->first();

            $datosControlCalidad = $this->getDetalleControlCalidad($id);
            // dd($datosControlCalidad);
            $arrayDatos = ['empresa' => $empresa, 'controlCalidad' => $datosControlCalidad, 'sucursal' => $sucursal];
            view()->share($arrayDatos);

            $pdf = PDF::loadView('pdf/controlCalidadPdf')->setPaper('a4', 'portrait');

            if ($descripcion == 'imprimir') {
                return $pdf->stream('documento.pdf');
            }

            if ($descripcion == 'descargar') {
                return $pdf->download('documento.pdf');
            }

            if ($descripcion == 'whatsApp') {
                if (Storage::disk('s3')->exists("pdfWhatsApp/ControlCalidad/$rucEmpresa/" . $datosControlCalidad->Serie . '-' . $datosControlCalidad->Numero . '.pdf')) {
                    Storage::disk('s3')->delete("pdfWhatsApp/ControlCalidad/$rucEmpresa/" . $datosControlCalidad->Serie . '-' . $datosControlCalidad->Numero . '.pdf');
                }
                $fechaCreacionPdf = Carbon::now()->toDateTimeString();
                $nombrePdf = "$datosControlCalidad->Serie-$datosControlCalidad->Numero";
                $directorio = "/PdfWhatsApp/ControlCalidad/";
                $urlPdf = $this->storePdfWhatsAppS3($pdf, $nombrePdf, $directorio, $rucEmpresa);

                DB::table('controles_calidad')->where('IdControlCalidad', $id)->update(['UrlPdf' => $urlPdf, 'FechaCreacionPdf' => $fechaCreacionPdf]);

                $numeroCelular = $req->numeroCelular;
                // $mensajeUrl = '¡Hola Gracias por confiar tu vehículo en nuestro Taller! 🥳 %0A%0A ☝️ Te enviamos el Informes de Control de Calidad (Diagnóstico y Recomendaciones) de tu vehículo que ingresastes a nuestras instalaciones recientemente, podrás descargarlo en el %0A  link de la parte inferior,  este enlace solo estará disponible por 30 días. 📄 🙌 %0A%0A 📞 Si tienes alguna duda o consulta, no dudes en comunicarte con nuestro Centro de Servicio al Cliente, con tus asesores de siempre que estarán gustos en atenderte.%0A%0A';

                $fechaControlCalidad = carbon::parse($datosControlCalidad->FechaCreacion)->isoFormat('D [de] MMMM [de] YYYY');
                $mensajeUrl = "¡Hola gracias por confiar en nuestra Empresa: *$empresa->NombreComercial* con RUC: *$empresa->Ruc*! 🥳%0A%0A ☝️Te enviamos el Informe de Control de Calidad (Diagnóstico y Recomendaciones) de tu vehículo, generada el dia: *$fechaControlCalidad* de acuerdo a tu requerimiento, podrás descargarlo haciendo click en el link de la parte inferior, este enlace solo estará disponible por 30 días. 📄 🙌 %0A%0A 📞 Si tienes alguna duda o consulta, no dudes en comunicarte con nuestro Centro de Servicio al Cliente al teléfono: *$empresa->Telefono*, con tus asesores de siempre que estarán gustos en atenderte.%0A%0A" . config('variablesGlobales.urlDominioAmazonS3') . $urlPdf;

                if ($loadDatos->isMobileDevice()) {
                    return redirect('https://api.whatsapp.com/send?phone=+51' . $numeroCelular . '&text=' . $mensajeUrl);
                } else {
                    return redirect('https://web.whatsapp.com/send?phone=51' . $numeroCelular . '&text=' . $mensajeUrl);
                }
            }
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
    }

    // public function guardarFirmaDigital(Request $req)
    // {
    //     $loadDatos = new DatosController();
    //     $imagenFirma = $req->imagenCodigoFirma;
    //     if ($req->imagenCodigoFirma != null) {
    //         $ruta = 'FirmasDigitales/FirmasClientes-ControlCalidad/';
    //         $imagenFirma = $loadDatos->storeFirmaDigital($imagenFirma, $ruta);
    //     }
    //     $arrayDatos = ['ImagenFirma' => $imagenFirma];
    //     DB::table('controles_calidad')
    //         ->where('IdControlCalidad', $req->idControl)
    //         ->update($arrayDatos);
    //     if ($req->descripcionEnlace == 'whatsApp') {
    //         return redirect()->route('controlCalidad.show', [$req->idControl])->with('success', 'Se guardo correctamente la Firma, Proceda a ' . $req->descripcionEnlace . ' el PDF');
    //     } else {
    //         return redirect()->route('imprimirControlCalidad', [$req->idControl, $req->descripcionEnlace]);
    //     }
    // }

    public function guardarFirmaDigitalConAjax(Request $req)
    {
        try {
            if ($req->session()->has('idUsuario')) {
                if ($req->ajax()) {
                    $loadDatos = new DatosController();
                    $idUsuario = Session::get('idUsuario');
                    $rucEmpresa = $loadDatos->getRucEmpresa($idUsuario)->Ruc;

                    $imagenFirma = $req->inputCodigoFirma;
                    $idCliente = $req->inputIdCliente;

                    if ($imagenFirma == null) {
                        return Response(['error', 'No Existe Firma Digital']);
                    }
                    $directorio = '/FirmasDigitales/FirmasClientes-ControlCalidad/';
                    $nombreImagen = "firma-{$idCliente}";
                    $imagenFirma = $this->storeImagenFormatoBase64($imagenFirma, $imagenAnterior = null, $nombreImagen, $directorio, $rucEmpresa, $accion = 'store');

                    $arrayDatos = ['ImagenFirmaCliente' => $imagenFirma];
                    DB::table('controles_calidad')
                        ->where('IdControlCalidad', $req->inputIdControlCalidad)
                        ->update($arrayDatos);

                    return Response(['succes', 'La Firma Digital se registro correctamente']);
                }
            } else {
                Session::flush();
                return redirect('/')->with('out', 'Sesion de usuario Expirado');
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }

    }

    // Funciones para traer datos de los detalles del control de calidad
    private function getDatosBajoCapo($id)
    {
        $datos = DB::table('controlCalidad_bajoCapo as cc')
            ->join('partes_vehiculo as pv', 'cc.IdParteVehiculo', '=', 'pv.IdParteVehiculo')
            ->select('pv.IdParteVehiculo', 'cc.Estado as EstadoParte', 'Descripcion')
            ->where('IdControlCalidad', $id)
            ->get();
        return $datos;
    }
    private function getDatosDebajoVehiculo($id)
    {
        $datos = DB::table('controlCalidad_debajoVehiculo as cc')
            ->join('partes_vehiculo as pv', 'cc.IdParteVehiculo', '=', 'pv.IdParteVehiculo')
            ->select('pv.IdParteVehiculo', 'cc.Estado as EstadoParte', 'Descripcion')
            ->where('IdControlCalidad', $id)
            ->get();
        return $datos;
    }

    private function getDatosDentroVehiculo($id)
    {
        $datos = DB::table('controlCalidad_dentroVehiculo as cc')
            ->join('partes_vehiculo as pv', 'cc.IdParteVehiculo', '=', 'pv.IdParteVehiculo')
            ->select('pv.IdParteVehiculo', 'cc.Estado as EstadoParte', 'Descripcion')
            ->where('IdControlCalidad', $id)
            ->get();
        return $datos;
    }
    private function getDatosFiltros($id)
    {
        $datos = DB::table('controlCalidad_filtros as cc')
            ->join('partes_vehiculo as pv', 'cc.IdParteVehiculo', '=', 'pv.IdParteVehiculo')
            ->select('pv.IdParteVehiculo', 'cc.Estado as EstadoParte', 'Descripcion')
            ->where('IdControlCalidad', $id)
            ->get();
        return $datos;
    }
    private function getDatosFluidos($id)
    {
        $datos = DB::table('controlCalidad_fluidos as cc')
            ->join('partes_vehiculo as pv', 'cc.IdParteVehiculo', '=', 'pv.IdParteVehiculo')
            ->select('pv.IdParteVehiculo', 'cc.Estado as EstadoParte', 'Descripcion')
            ->where('IdControlCalidad', $id)
            ->get();
        return $datos;
    }
    private function getDatosFrenos($id)
    {
        $datos = DB::table('controlCalidad_frenos as cc')
            ->join('partes_vehiculo as pv', 'cc.IdParteVehiculo', '=', 'pv.IdParteVehiculo')
            ->select('pv.IdParteVehiculo', 'Descripcion', 'pv.Posicion', 'cc.Estado as EstadoParte', 'cc.Medida')
            ->where('IdControlCalidad', $id)
            ->get();
        return $datos;
    }
    private function getDatosLimpiaParabrisas($id)
    {
        $datos = DB::table('controlCalidad_limpiaparabrisas as cc')
            ->join('partes_vehiculo as pv', 'cc.IdParteVehiculo', '=', 'pv.IdParteVehiculo')
            ->select('pv.IdParteVehiculo', 'Descripcion', 'pv.Posicion', 'cc.Estado as EstadoParte')
            ->where('IdControlCalidad', $id)
            ->get();
        return $datos;
    }
    private function getDatosLuces($id)
    {
        $datos = DB::table('controlCalidad_luces as cc')
            ->join('partes_vehiculo as pv', 'cc.IdParteVehiculo', '=', 'pv.IdParteVehiculo')
            ->select('pv.IdParteVehiculo', 'Descripcion', 'pv.Posicion', 'cc.Estado as EstadoParte')
            ->where('IdControlCalidad', $id)
            ->get();
        return $datos;
    }
    private function getDatosNeumaticos($id)
    {
        $datos = DB::table('controlCalidad_neumaticos as cc')
            ->join('partes_vehiculo as pv', 'cc.IdParteVehiculo', '=', 'pv.IdParteVehiculo')
            ->select('pv.IdParteVehiculo', 'Descripcion', 'pv.Posicion', 'cc.Estado as EstadoParte')
            ->where('IdControlCalidad', $id)
            ->get();
        return $datos;
    }
    private function getDatosPresionNeumaticos($id)
    {
        $datos = DB::table('controlCalidad_presionNeumatico as cc')
            ->join('partes_vehiculo as pv', 'cc.IdParteVehiculo', '=', 'pv.IdParteVehiculo')
            ->select('pv.IdParteVehiculo', 'Descripcion', 'pv.Posicion', 'cc.Presion')
            ->where('IdControlCalidad', $id)
            ->get();
        return $datos;
    }
}

// private function getVehiculosConCotizacionConCotizacion($idSucursal)
// {
//     $loadDatos = new DatosController();

//     $cotizacion = DB::table('cotizacion')
//         ->join('cliente', 'cotizacion.IdCliente', '=', 'cliente.IdCliente')
//         ->whereIn('cotizacion.IdEstadoCotizacion', [1, 2, 3])
//         ->where('cotizacion.IdSucursal', $idSucursal)
//         ->where('TipoCotizacion', 2)
//         ->get();
//     $cotizacion->map(function ($item, $key) use ($cotizacion, $loadDatos) {
//         $datosVehiculo = $loadDatos->getVehiculoSelect($item->Campo0);
//         $cotizacion[$key]->Placa = $datosVehiculo->PlacaVehiculo;
//         $cotizacion[$key]->Anio = $datosVehiculo->Anio;
//         $cotizacion[$key]->Marca = $datosVehiculo->NombreMarca;
//         $cotizacion[$key]->Modelo = $datosVehiculo->NombreModelo;
//         $cotizacion[$key]->Seguro = $datosVehiculo->Seguro;
//     });

//     return $cotizacion;
// }
