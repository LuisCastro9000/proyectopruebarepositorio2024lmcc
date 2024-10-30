<?php

namespace App\Http\Controllers\Areas;

use App\Http\Controllers\Controller;

use App\Http\Controllers\DatosController;
use App\Traits\CuentasBancariasTrait;
use App\Traits\getFuncionesTrait;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Http\Request;
use Session;

class AreaAdministrativaController extends Controller
{
    use getFuncionesTrait;
    use CuentasBancariasTrait;
    public function index(Request $req)
    {

        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }

        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');
        $idSucursal = Session::get('idSucursal');
        $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
        $permisos = $loadDatos->getPermisos($idUsuario);
        $subpermisos = $loadDatos->getSubPermisos($idUsuario);
        $subniveles = $loadDatos->getSubNiveles($idUsuario);
        $sucursales = $loadDatos->getSucursales($usuarioSelect->CodigoCliente, $usuarioSelect->IdOperador);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $permisosBotones = $loadDatos->getPermisosBotones($idUsuario);
        $permisosSubBotones = $loadDatos->getPermisosSubBotones($idUsuario);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        $logo = $empresa->Imagen;
        $mensaje = $loadDatos->getMensajeAdmin();
        $mensaje = collect($mensaje);
        $mensajeActualizacion = $mensaje->where("IdMensaje", 1)->first();
        $mensajeSunat = $mensaje->where("IdMensaje", 2)->first();
        $mesActual = $this->mesActual();

        $inputValorBoton = $req->inputValorBoton;
        if ($inputValorBoton == null) {
            $inputValorBoton = "flujoVentas";
        }

        // Construir un array común con las variables compartidas
        $arrayVariblesGenerales = ['empresa' => $empresa, 'usuarioSelect' => $usuarioSelect, 'sucursales' => $sucursales, 'logo' => $logo, 'permisos' => $permisos, 'modulosSelect' => $modulosSelect, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'permisosBotones' => $permisosBotones, 'permisosSubBotones' => $permisosSubBotones,
        ];

        switch ($inputValorBoton) {
            case ('flujoVentas'):
                // DATOS DEL MES ANTERIOR EN SOLES
                $fechas = $this->getFechaFiltro(6, null, null);
                $reporteMesAnterior = $loadDatos->ventasMesAnterior($fechas[0], $fechas[1], 1, $idSucursal);
                $reporteMesAnterior = collect($reporteMesAnterior);
                // obtener el mes anterior
                $mesAnterior = Carbon::now()->startOfMonth()->subSeconds(1)->toDateTimeString();
                // obtener el total de dias del mes anterior
                $totalDiasMesAnterior = date('t', strtotime($mesAnterior));
                // convertir de string a numerico los dias
                $totalDiasMesAnterior = intval($totalDiasMesAnterior - 4);
                $totalVentasMes = $reporteMesAnterior->pluck('totalVentasDelMes')->first();
                // obtener el promedio de las ventas del mes anterior
                $promedioVentasMesAnterior = $totalVentasMes / $totalDiasMesAnterior;
                $promedioVentasMesAnterior = round($promedioVentasMesAnterior, 2);
                //  FIN

                // DATOS DEL MES ANTERIOR EN DOLARES

                $fechas = $loadDatos->getFechaFiltro(6, null, null);
                $reporteMesAnteriorDolares = $loadDatos->ventasMesAnterior($fechas[0], $fechas[1], 2, $idSucursal);
                $reporteMesAnteriorDolares = collect($reporteMesAnteriorDolares);
                // obtener el mes anterior
                $mesAnteriorDolares = Carbon::now()->startOfMonth()->subSeconds(1)->toDateTimeString();
                // obtener el total de dias del mes anterior
                $totalDiasMesAnteriorDolares = date('t', strtotime($mesAnteriorDolares));
                // convertir de string a numerico los dias
                $totalDiasMesAnteriorDolares = intval($totalDiasMesAnteriorDolares - 4);
                $totalVentasMesDolares = $reporteMesAnteriorDolares->pluck('totalVentasDelMes')->first();
                // obtener el promedio de las ventas del mes anterior
                $promedioVentasMesAnteriorDolares = $totalVentasMesDolares / $totalDiasMesAnteriorDolares;
                $promedioVentasMesAnteriorDolares = round($promedioVentasMesAnteriorDolares, 2);
                //  FIN

                // GRAFICO  PRODUCTOS/SERVICIOS MAS COMERCIALIZADO DEL DIA   EN SOLES
                $fechaHoyInicio = Carbon::today();
                $num = 100;
                $porcentajeXventa = 0;
                $horas = [];
                $reporteVentasDiarias = $loadDatos->ventasDiarias($fechaHoyInicio, "horaCreacion", 1, $idSucursal);
                $totalVentasXdia = $reporteVentasDiarias->pluck('totalVentasDiarias');
                $fechaXdia = $reporteVentasDiarias->pluck('horaCreacion')->sort();
                $sumaTotalVentasXdia = $reporteVentasDiarias->sum('totalVentasDiarias');
                $totalDineroVentasXdia = $reporteVentasDiarias->sum('TotalVentasDinero');
                // obtener el porcentaje equivalente a una venta diaria
                if ($promedioVentasMesAnterior > 0.01) {
                    $porcentajeXventa = $num / $promedioVentasMesAnterior;
                }
                // Mostrar que las ventas diarias llegen solamente al 100%
                $totalVentas = round($sumaTotalVentasXdia * $porcentajeXventa, 2);
                if ($totalVentas >= 100) {
                    $totalVentas = $num;
                }
                if (count($fechaXdia) >= 1) {
                    $i = 0;
                    foreach ($fechaXdia as $item) {
                        $horas[$i] = $item . ':' . '00';
                        $i++;
                    }
                }

                $horasDolares = [];
                $porcentajeXventaDolares = 0;
                $reporteVentasDiariasDolares = $loadDatos->ventasDiarias($fechaHoyInicio, "horaCreacion", 2, $idSucursal);
                $totalVentasXdiaDolares = $reporteVentasDiariasDolares->pluck('totalVentasDiarias');
                $fechaXdiaDolares = $reporteVentasDiariasDolares->pluck('horaCreacion');
                $sumaTotalVentasXdiaDolares = $reporteVentasDiariasDolares->sum('totalVentasDiarias');
                $totalDineroVentasXdiaDolares = $reporteVentasDiariasDolares->sum('TotalVentasDinero');
                // obtener el porcentaje equivalente a una venta diaria
                if ($promedioVentasMesAnteriorDolares > 0.01) {
                    $porcentajeXventaDolares = $num / $promedioVentasMesAnteriorDolares;
                }
                // Mostrar que las ventas diarias llegen solamente al 100%
                $totalVentasDolares = round($sumaTotalVentasXdiaDolares * $porcentajeXventaDolares, 2);
                if ($totalVentasDolares >= 100) {
                    $totalVentasDolares = $num;
                }
                if (count($fechaXdiaDolares) >= 1) {
                    $i = 0;
                    foreach ($fechaXdiaDolares as $item) {
                        $horasDolares[$i] = $item . ':' . '00';
                        $i++;
                    }
                }
                $horasDolares = collect($horasDolares);

                //  OBTENER EL NOMBRE DEL MES ANTERIOR
                // obtener el nombre el mes anterior
                $mesAnterior = Carbon::now();
                // $mesAnterior->subMonth(1)->startOfMonth();  este ya estava agregado
                // // $mesAnterior->startOfMonth()->subSeconds(1)->toDateTimeString();
                $mesAnterior->startOfMonth()->subSeconds(1);
                // extraer el numero del mes
                $mesAnterior = $mesAnterior->month;
                // convertir el numero del mes a letras en español
                setlocale(LC_TIME, 'es_ES');
                $mesAnterior = DateTime::createFromFormat('!m', $mesAnterior);
                $mesAnterior = strftime("%B", $mesAnterior->getTimestamp());
                // Converitr la primera en mayuscula
                $mesAnterior = ucfirst($mesAnterior);
                // FIN

                // DATOS DE TRES MESES ANTERIORES EN SOLES
                $fechas = $this->getFechaFiltro(10, null, null);
                $pocentajeVentasMesActual = 0;
                $reporteTresMesesAtras = $loadDatos->ventasMesAnterior($fechas[0], $fechas[1], 1, $idSucursal);
                // $reportetres =collect($reportetres );
                $totalVentasTresMesesAtras = $reporteTresMesesAtras->pluck('totalVentasDelMes')->first();
                // obtener el nombre el mes anterior
                $mesActual = Carbon::today();
                $primerDiaDelMesActual = $mesActual->startOfMonth();
                $mesAnteriorTres = Carbon::today();
                $mesAnteriorTres->subMonth(3)->startOfMonth();
                // obtener el total de dias de los tres meses anteriores
                $totalDiasMesAnteriorTres = $primerDiaDelMesActual->diffInDays($mesAnteriorTres);
                // Obtener el promedio de ventas mensual
                $promedioVentasTresMeses = round($totalVentasTresMesesAtras / 3, 2);
                // obtener las ventas del mes
                $mesActual = $this->mesActual();
                $reporteVentasDelMes = $loadDatos->ventasDiarias($mesActual, "dia", 1, $idSucursal);
                $dia = $reporteVentasDelMes->pluck('FechaCreacion');
                $totalVentasDelMesActualXdia = $reporteVentasDelMes->pluck('totalVentasDiarias');
                $totalVentasDelMesActual = $reporteVentasDelMes->pluck('totalVentasDiarias')->sum();
                $totalDineroVentasMesActual = $reporteVentasDelMes->pluck('TotalVentasDinero')->sum();
                $totalDineroVentasMesActual = round($totalDineroVentasMesActual, 2);
                if ($promedioVentasTresMeses > 0.01) {
                    $pocentajeVentasMesActual = $num / $promedioVentasTresMeses;
                }
                $pocentajeMeta = round($totalVentasDelMesActual * $pocentajeVentasMesActual, 2);
                if ($pocentajeMeta >= $num) {
                    $pocentajeMeta = $num;
                }

                // /DATOS DE TRES MESES ANTERIORES EN DOLARES
                $reporteTresMesesAtrasDolares = $loadDatos->ventasMesAnterior($fechas[0], $fechas[1], 2, $idSucursal);
                $totalVentasTresMesesAtrasDolares = $reporteTresMesesAtrasDolares->pluck('totalVentasDelMes')->first();
                // obtener el nombre el mes anterior
                $mesActualDolares = Carbon::today();
                $primerDiaDelMesActualDolares = $mesActualDolares->startOfMonth();
                $mesAnteriorTresDolares = Carbon::today();
                $mesAnteriorTresDolares->subMonth(3)->startOfMonth();
                // obtener el total de dias de los tres meses anteriores
                $totalDiasMesAnteriorTresDolares = $primerDiaDelMesActualDolares->diffInDays($mesAnteriorTresDolares);
                // Obtener el promedio de ventas mensual
                $promedioVentasTresMesesDolares = round($totalVentasTresMesesAtrasDolares / 3, 2);
                // obtener las ventas del mes
                $mesActual = $this->mesActual();
                $pocentajeVentasMesActualDolares = 0;
                $reporteVentasDelMesDolares = $loadDatos->ventasDiarias($mesActual, "dia", 2, $idSucursal);
                $diaDolares = $reporteVentasDelMesDolares->pluck('FechaCreacion');
                $totalVentasDelMesActualXdiaDolares = $reporteVentasDelMesDolares->pluck('totalVentasDiarias');
                $totalVentasDelMesActualDolares = $reporteVentasDelMesDolares->pluck('totalVentasDiarias')->sum();
                $totalDineroVentasMesActualDolares = $reporteVentasDelMesDolares->pluck('TotalVentasDinero')->sum();
                $totalDineroVentasMesActualDolares = round($totalDineroVentasMesActualDolares, 2);
                if ($promedioVentasTresMesesDolares > 0.01) {
                    $pocentajeVentasMesActualDolares = $num / $promedioVentasTresMesesDolares;
                }
                $pocentajeMetaDolares = round($totalVentasDelMesActualDolares * $pocentajeVentasMesActualDolares, 2);
                if ($pocentajeMetaDolares >= $num) {
                    $pocentajeMetaDolares = $num;
                }

                $array = array_merge($arrayVariblesGenerales, ['inputValorBoton' => $inputValorBoton, 'totalVentas' => $totalVentas, 'promedioVentasMesAnterior' => $promedioVentasMesAnterior, 'totalVentasMes' => $totalVentasMes, 'totalDineroVentasXdia' => $totalDineroVentasXdia, 'sumaTotalVentasXdia' => $sumaTotalVentasXdia, 'mensajeActualizacion' => $mensajeActualizacion, 'mensajeSunat' => $mensajeSunat, 'sumaTotalVentasXdiaDolares' => $sumaTotalVentasXdiaDolares, 'totalDineroVentasXdiaDolares' => $totalDineroVentasXdiaDolares, 'totalVentasDolares' => $totalVentasDolares, 'mesAnterior' => $mesAnterior, 'totalVentas' => $totalVentas, 'totalVentasTresMesesAtras' => $totalVentasTresMesesAtras, 'promedioVentasTresMeses' => $promedioVentasTresMeses, 'totalVentasDelMesActual' => $totalVentasDelMesActual, 'totalDineroVentasMesActual' => $totalDineroVentasMesActual, 'totalVentasMesDolares' => $totalVentasMesDolares, 'promedioVentasMesAnteriorDolares' => $promedioVentasMesAnteriorDolares, 'totalVentasDolares' => $totalVentasDolares, 'totalVentasTresMesesAtrasDolares' => $totalVentasTresMesesAtrasDolares, 'promedioVentasTresMesesDolares' => $promedioVentasTresMesesDolares, 'totalVentasDelMesActualDolares' => $totalVentasDelMesActualDolares, 'totalDineroVentasMesActualDolares' => $totalDineroVentasMesActualDolares, 'totalVentasXdia' => $totalVentasXdia,
                    // Variables Grafico Flujo Ventas
                    'horas' => $horas, 'totalVentasXdia' => $totalVentasXdia, 'totalVentas' => $totalVentas, 'totalVentasDelMesActualXdia' => $totalVentasDelMesActualXdia, 'dia' => $dia, 'pocentajeMeta' => $pocentajeMeta, 'totalVentasXdiaDolares' => $totalVentasXdiaDolares, 'horasDolares' => $horasDolares, 'totalVentasDolares' => $totalVentasDolares, 'totalVentasDelMesActualXdiaDolares' => $totalVentasDelMesActualXdiaDolares, 'diaDolares' => $diaDolares, 'pocentajeMetaDolares' => $pocentajeMetaDolares,
                ]);
                return view('areas/areaAdministracion/index', $array);
                break;
            case ('flujoCaja'):
                $fechas = $loadDatos->getFechaFiltro(5, null, null);
                $reporteEgreso = $loadDatos->getReporteEgresos($idSucursal, $mesActual);
                $cantidadEgreso = $reporteEgreso->pluck('totalIngresoEgreso');
                $fechaEgreso = $reporteEgreso->pluck('fechaCreacion');
                $montoTotalEgreso = $reporteEgreso->pluck('totalMonto');
                // Soles
                $cajasAbiertasSoles = $loadDatos->getCajaAperturas($idSucursal, 1);
                $cajasAbiertasSoles = collect($cajasAbiertasSoles);
                $totalcajasAbiertas = $cajasAbiertasSoles->count();
                $totalCajaSoles = $cajasAbiertasSoles->pluck('TotalEfectivoXcajeroSoles')->sum();
                $totaEfectivoContadoSoles = $cajasAbiertasSoles->pluck('TotalEfectivoContado')->sum();
                $totaEfectivoCobranzasSoles = $cajasAbiertasSoles->pluck('TotalEfectivoCobranzas')->sum();
                $totaEfectivoAmortizacionSoles = $cajasAbiertasSoles->pluck('TotalEfectivoAmortizacion')->sum();
                // Dolares
                $cajasAbiertasDolares = $loadDatos->getCajaAperturas($idSucursal, 2);
                $cajasAbiertasDolares = collect($cajasAbiertasDolares);
                $totalCajaDolares = $cajasAbiertasDolares->pluck('TotalEfectivoXcajeroDolares')->sum();
                $totaEfectivoContadoDolares = $cajasAbiertasDolares->pluck('TotalEfectivoContado')->sum();
                $totaEfectivoCobranzasDolares = $cajasAbiertasDolares->pluck('TotalEfectivoCobranzas')->sum();
                $totaEfectivoAmortizacionDolares = $cajasAbiertasDolares->pluck('TotalEfectivoAmortizacion')->sum();

                $cierreCajaSoles = $loadDatos->getCierreDeCajaXMesActual($idSucursal, $mesActual, 1);
                $cierreCajaSoles = collect($cierreCajaSoles);
                $totalCierreCajaSoles = [];
                $fechaCierreCaja = [];
                foreach ($cierreCajaSoles as $key => $data) {
                    if (!in_array($data->fechaCierre, $fechaCierreCaja)) {
                        $totalCierreCajaSoles[$data->fechaCierre] = $data->TotalEfectivo;
                        $fechaCierreCaja[] = $data->fechaCierre;
                    } else {
                        $totalCierreCajaSoles[$data->fechaCierre] += $data->TotalEfectivo;
                    }
                }
                $totalCierreCajaSoles = collect($totalCierreCajaSoles)->flatten();
                $montoTotalCierreCajaSoles = $totalCierreCajaSoles->map(function ($valor) {
                    return round($valor, 2);
                });
                $cierreCajaDolares = $loadDatos->getCierreDeCajaXMesActual($idSucursal, $mesActual, 2);
                $cierreCajaDolares = collect($cierreCajaDolares);
                $totalCierreCajaDolares = [];
                $fechaCierreCajaDolares = [];
                foreach ($cierreCajaDolares as $key => $data) {
                    if (!in_array($data->fechaCierre, $fechaCierreCajaDolares)) {
                        $totalCierreCajaDolares[$data->fechaCierre] = $data->TotalEfectivoXcajeroDolares;
                        $fechaCierreCajaDolares[] = $data->fechaCierre;
                    } else {
                        $totalCierreCajaDolares[$data->fechaCierre] += $data->TotalEfectivoXcajeroDolares;
                    }
                }
                $totalCierreCajaDolares = collect($totalCierreCajaDolares)->flatten();
                $montoTotalCierreCajaDolares = $totalCierreCajaDolares->map(function ($valor) {
                    return round($valor, 2);
                });

                $array = array_merge($arrayVariblesGenerales, ['mensajeActualizacion' => $mensajeActualizacion, 'mensajeSunat' => $mensajeSunat, 'inputValorBoton' => $inputValorBoton, 'totalcajasAbiertas' => $totalcajasAbiertas, 'totaEfectivoContadoSoles' => $totaEfectivoContadoSoles, 'totalCajaSoles' => $totalCajaSoles, 'totalCajaDolares' => $totalCajaDolares, 'totaEfectivoAmortizacionDolares' => $totaEfectivoAmortizacionDolares, 'totaEfectivoCobranzasDolares' => $totaEfectivoCobranzasDolares, 'totaEfectivoContadoDolares' => $totaEfectivoContadoDolares, 'totaEfectivoCobranzasSoles' => $totaEfectivoCobranzasSoles, 'totaEfectivoAmortizacionSoles' => $totaEfectivoAmortizacionSoles,
                    // Variables Grafico Flujo Caja
                    'cantidadEgreso' => $cantidadEgreso, 'fechaEgreso' => $fechaEgreso, 'montoTotalEgreso' => $montoTotalEgreso, 'montoTotalCierreCajaSoles' => $montoTotalCierreCajaSoles, 'fechaCierreCaja' => $fechaCierreCaja, 'montoTotalCierreCajaDolares' => $montoTotalCierreCajaDolares, 'fechaCierreCajaDolares' => $fechaCierreCajaDolares,
                ]);
                return view('areas/areaAdministracion/index', $array);

                break;
            case ('almacenStock'):
                $bajaProductos = DB::select('call sp_getReporteBajaProductos(?)', array($idSucursal));
                $bajaProductos = collect($bajaProductos);
                $totalBajasProductos = $bajaProductos->pluck('totalBajas')->sum();
                $totalBaja = $bajaProductos->pluck('totalBajas');
                $fechaBaja = [];

                $arrayMes = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];

                for ($i = 0; $i < count($bajaProductos); $i++) {
                    array_push($fechaBaja, $arrayMes[$bajaProductos[$i]->Mes - 1] . ' ' . $bajaProductos[$i]->anio);
                }

                $traspasos = DB::select('call sp_getTotalDeTraspaso(?)', array($idSucursal));
                $traspasos = collect($traspasos);
                $totalTraspasosProductos = $traspasos->pluck('cantidad')->sum();

                $totalProductosCreadosAlmacen = $loadDatos->getProductos($idSucursal);
                $totalProductosCreadosAlmacen = collect($totalProductosCreadosAlmacen);
                $totalProductosCreados = $totalProductosCreadosAlmacen->count();
                $totalProductosStock = $totalProductosCreadosAlmacen->where("Stock", 0)->count();
                $totalProductosCreadosSoles = $totalProductosCreadosAlmacen->where("IdTipoMoneda", 1)->count();
                $totalProductosCreadosDolares = $totalProductosCreadosAlmacen->where("IdTipoMoneda", 2)->count();

                $totalProductosConStockSoles = $totalProductosCreadosAlmacen->filter(function ($item, $key) {
                    if ($item->Stock >= 1 && $item->IdTipoMoneda == 1) {
                        return $item;
                    }
                });

                $totalProductosConStockDolares = $totalProductosCreadosAlmacen->filter(function ($item, $key) {
                    if ($item->Stock >= 1 && $item->IdTipoMoneda == 2) {
                        return $item;
                    }
                });

                $totalProductosConStockSoles = $totalProductosConStockSoles->map(function ($item, $key) {
                    $total = $item->Stock * $item->Precio;
                    return $total;
                });
                $totalProductosConStockDolares = $totalProductosConStockDolares->map(function ($item, $key) {
                    $total = $item->Stock * $item->Precio;
                    return $total;
                });
                $totalProductosConStockSoles = $totalProductosConStockSoles->sum();
                $totalProductosConStockDolares = $totalProductosConStockDolares->sum();

                $fechas = $loadDatos->getFechaFiltro(5, null, null);
                $reporteVendidos = collect(DB::select('call sp_getMasVendidosDelMesActual(?, ?, ? )', array($idSucursal, $fechas[0], $fechas[1])));
                $menosVendidos = $reporteVendidos->sortBy('Total');
                $masVendidos = $reporteVendidos->sortByDesc('Total');
                $reporteMenosVendidos = $menosVendidos->take(15);
                $reporteMasVendidos = $masVendidos->take(15);
                $menosVendidosDescripcion = $reporteMenosVendidos->pluck('Descripcion');
                $menosVendidosTotal = $reporteMenosVendidos->pluck('Total');
                $masVendidosDescripcion = $reporteMasVendidos->pluck('Descripcion');
                $masVendidosTotal = $reporteMasVendidos->pluck('Total');

                $array = array_merge($arrayVariblesGenerales, ['mensajeActualizacion' => $mensajeActualizacion, 'mensajeSunat' => $mensajeSunat, 'inputValorBoton' => $inputValorBoton, 'totalProductosStock' => $totalProductosStock, 'totalBajasProductos' => $totalBajasProductos, 'totalTraspasosProductos' => $totalTraspasosProductos, 'totalProductosCreados' => $totalProductosCreados, 'totalProductosCreadosSoles' => $totalProductosCreadosSoles, 'totalProductosCreadosDolares' => $totalProductosCreadosDolares,
                    // Variables Grafico Almacen
                    'totalBaja' => $totalBaja, 'fechaBaja' => $fechaBaja, 'menosVendidosDescripcion' => $menosVendidosDescripcion, 'menosVendidosTotal' => $menosVendidosTotal, 'masVendidosDescripcion' => $masVendidosDescripcion, 'masVendidosTotal' => $masVendidosTotal,
                ]);
                return view('areas/areaAdministracion/index', $array);

                break;

            case ('cotizacion'):
                // COTIZACIONES
                $num = 100;
                $fechas = $this->getFechaFiltro(5, null, null);
                $cotizaciones = $this->getCotizacionAll($idSucursal, $fechas[0], $fechas[1]);
                $porcentajeXCotizacion = 0;
                $cotizacionAbierto = $cotizaciones->where('IdEstadoCotizacion', 1)->count();
                $cotizacionCerrado = $cotizaciones->where('IdEstadoCotizacion', 4)->count();
                $cotizacionBaja = $cotizaciones->where('IdEstadoCotizacion', 6)->count();

                // Cotizaciones del mes anterior
                $fechas = $this->getFechaFiltro(6, null, null);
                $cotizaciones = $this->getCotizacionAll($idSucursal, $fechas[0], $fechas[1]);
                $cotizacionCerradoDelAnterior = $cotizaciones->where('IdEstadoCotizacion', 4)->count();
                // obtener el promedio de las ventas del mes anterior
                if ($cotizacionCerradoDelAnterior > 0.1) {
                    $porcentajeXCotizacion = $num / $cotizacionCerradoDelAnterior;
                }
                $pocentajeMetaCotizacionEsteMes = round($cotizacionCerrado * $porcentajeXCotizacion, 2);

                if ($pocentajeMetaCotizacionEsteMes >= 100) {
                    $pocentajeMetaCotizacionEsteMes = 100;
                }
                // FIN

                $array = array_merge($arrayVariblesGenerales, ['mensajeActualizacion' => $mensajeActualizacion, 'mensajeSunat' => $mensajeSunat, 'inputValorBoton' => $inputValorBoton, 'cotizacionAbierto' => $cotizacionAbierto, 'cotizacionCerradoDelAnterior' => $cotizacionCerradoDelAnterior, 'cotizacionAbierto' => $cotizacionAbierto, 'cotizacionCerrado' => $cotizacionCerrado, 'cotizacionBaja' => $cotizacionBaja,
                    // Variables Grafico Cotizacion
                    'pocentajeMetaCotizacionEsteMes' => $pocentajeMetaCotizacionEsteMes,
                ]);
                return view('areas/areaAdministracion/index', $array);

                break;
            case ('ganancias'):
                $reporteGananciasSoles = $this->getObtenerGananciasXmes($idSucursal, 1);
                $gastosUltimosSeisMeses = $this->getObtenerGastos($idSucursal);
                $reporteGananciasSoles = collect($reporteGananciasSoles);
                $gastosUltimosSeisMeses = collect($gastosUltimosSeisMeses);

                $mesesGanancia = $reporteGananciasSoles->pluck('fechaCreacion');
                $gastosUltimosSeisMeses = $gastosUltimosSeisMeses->whereIn('fechaCreacion', $mesesGanancia)->sortByDesc('Mes')->values();
                $mesesGasto = $gastosUltimosSeisMeses->pluck('fechaCreacion');

                $cantidadMesesGanancia = count($mesesGanancia);
                $cantidadMesesConGastos = count($gastosUltimosSeisMeses);
                $mesesGasto = $mesesGasto->pad($cantidadMesesGanancia, 0)->toArray();
                $diferenciaMes = $mesesGanancia->diff($mesesGasto)->values();

                if (count($reporteGananciasSoles) != count($gastosUltimosSeisMeses)) {
                    $cantidadMesesConGastos = ($cantidadMesesGanancia - 1) - $cantidadMesesConGastos;
                    for ($i = 0; $i <= $cantidadMesesConGastos; $i++) {
                        $gastosUltimosSeisMeses->push(['totalGasto' => 0, 'fechaCreacion' => $diferenciaMes[$i]]);
                    }
                }

                $gastosUltimosSeisMeses = $gastosUltimosSeisMeses->sortBy('fechaCreacion')->values();
                $montoGasto = $gastosUltimosSeisMeses->pluck('totalGasto');
                $listaMesGasto = $gastosUltimosSeisMeses->pluck('fechaCreacion')->sort()->values()->toArray();

                $reporteGananciasSoles = $reporteGananciasSoles->sortBy('mes')->values();

                foreach ($reporteGananciasSoles as $key => $data) {
                    if (in_array($data->fechaCreacion, $listaMesGasto)) {
                        $reporteGananciasSoles[$key]->montoGastos = $montoGasto[$key];
                    } else {
                        $reporteGananciasSoles[$key]->montoGastos = 0;
                    }
                };

                $arrayFechasGanancias = [];
                $arrayMes = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
                for ($i = 0; $i < count($reporteGananciasSoles); $i++) {
                    array_push($arrayFechasGanancias, $arrayMes[$reporteGananciasSoles[$i]->mes - 1] . ' ' . $reporteGananciasSoles[$i]->anio);
                }

                $totalGananciasXmes = $reporteGananciasSoles->map(function ($item) {
                    return round($item->Ganancia - $item->montoGastos, 2);
                });

                $reporteGananciasDolares = $this->getObtenerGananciasXmes($idSucursal, 2);
                $reporteGananciasDolares = collect($reporteGananciasDolares)->sortBy("FechaCreacion")->values();
                $arrayFechasGananciasDolares = [];
                for ($i = 0; $i < count($reporteGananciasDolares); $i++) {
                    array_push($arrayFechasGananciasDolares, $arrayMes[$reporteGananciasDolares[$i]->mes - 1] . ' ' . $reporteGananciasDolares[$i]->anio);
                }
                $totalGananciasXmesDolares = $reporteGananciasDolares->pluck('Ganancia');

                $array = array_merge($arrayVariblesGenerales, ['mensajeActualizacion' => $mensajeActualizacion, 'mensajeSunat' => $mensajeSunat, 'inputValorBoton' => $inputValorBoton,
                    // Variables Grafico Ganancias
                    'totalGananciasXmes' => $totalGananciasXmes, 'arrayFechasGanancias' => $arrayFechasGanancias, 'totalGananciasXmesDolares' => $totalGananciasXmesDolares, 'arrayFechasGananciasDolares' => $arrayFechasGananciasDolares,
                ]);
                return view('areas/areaAdministracion/index', $array);

                break;
            case ('bancos'):
                $listaBancos = $loadDatos->getCuentasCorrientes($usuarioSelect->CodigoCliente, null);
                $array = array_merge($arrayVariblesGenerales, ['mensajeActualizacion' => $mensajeActualizacion, 'mensajeSunat' => $mensajeSunat, 'inputValorBoton' => $inputValorBoton,
                    // Variables Grafico Bancos
                    'listaBancos' => $listaBancos,
                ]);
                return view('areas/areaAdministracion/index', $array);
                break;

            case ('compras'):
                dd("compras");
                break;
            case ('creditos'):
                dd("creditos");
                break;
            case ('pagos'):
                dd("pagos");
                break;
            case ('gastos'):
                dd("gastos");
                break;
        }
    }

    private function getFechaFiltro($fecha, $fechaIni, $fechaFin)
    {
        if ($fecha == 6) {
            $fechaInicio = Carbon::now()->startOfMonth()->subSeconds(1)->startOfMonth();
            $fechaFinal = Carbon::now()->startOfMonth()->subSeconds(1);
            return array($fechaInicio, $fechaFinal);
        }

        if ($fecha == 10) {
            $datePrev = Carbon::today()->day;
            $mesPasado = Carbon::today()->subMonth(3)->firstOfMonth();
            $date1 = Carbon::today();
            $fechaFinal = $date1->startOfMonth();
            $fechaInicio = $mesPasado;
            return array($fechaInicio, $fechaFinal);
        }

        // ESTE MES
        if ($fecha == 5) {
            $fechaInicio = Carbon::now()->startOfMonth();
            $fechaFinal = Carbon::now()->startOfMonth()->endOfMonth();
            return array($fechaInicio, $fechaFinal);
        }
    }

    protected function getCotizacionAll($idSucursal, $fechaInicial, $fechaFinal)
    {
        try {
            $ventas = DB::table('cotizacion')
                ->join('cliente', 'cotizacion.IdCliente', '=', 'cliente.IdCliente')
                ->join('sucursal', 'cotizacion.IdSucursal', '=', 'sucursal.IdSucursal')
                ->join('usuario', 'cotizacion.IdCreacion', '=', 'usuario.IdUsuario')
                ->join('tipo_atencion', 'cotizacion.IdTipoAtencion', '=', 'tipo_atencion.IdTipoAtencion')
                ->join('estados_cotizacion', 'estados_cotizacion.IdEstadoCotizacion', '=', 'cotizacion.IdEstadoCotizacion')
                ->select('cotizacion.*', 'estados_cotizacion.Descripcion as EstadoCoti', 'estados_cotizacion.Color', 'cliente.RazonSocial as RazonSocial', 'cliente.Nombre as Nombres', 'cliente.NumeroDocumento', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario', 'tipo_atencion.Descripcion as Atencion')
                ->where('cotizacion.IdSucursal', $idSucursal)
                ->whereBetween('cotizacion.FechaCreacion', [$fechaInicial, $fechaFinal])
                ->orderBy('cotizacion.FechaCreacion', 'desc')
                ->get();
            return $ventas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getObtenerGastos($idSucursal)
    {
        try {
            $resultado = DB::select(
                'select MONTH(FechaCreacion) as Mes, sum(gastos.Monto) AS totalGasto, YEAR(FechaCreacion) AS anio, DATE_FORMAT(FechaCreacion,"%Y-%m") as fechaCreacion, FechaCreacion
            FROM gastos
            INNER JOIN lista_gastos ON gastos.IdListaGastos = lista_gastos.IdListaGastos
            WHERE gastos.IdSucursal = ' . $idSucursal . ' and gastos.Estado="E"
            GROUP BY YEAR(gastos.FechaCreacion), MONTH(gastos.FechaCreacion)
            ORDER BY YEAR(gastos.FechaCreacion) DESC, MONTH(gastos.FechaCreacion) DESC LIMIT 6');
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getObtenerGananciasXmes($idSucursal, $tipoMoneda)
    {

        try {
            $resultado = DB::select('select  SUM(Ganancia) as Ganancia, SUM(Descuento) as Descuento, SUM(Importe - Ganancia) as Costo, FechaCreacion, IdTipoPago, IdTipoMoneda as tipoMoneda, MONTH(FechaCreacion) AS mes, YEAR(FechaCreacion) AS anio,  ventas.FechaCreacion, DATE_FORMAT(FechaCreacion,"%Y-%m") as fechaCreacion
         from ventas_articulo
         inner join ventas on ventas_articulo.IdVentas = ventas.IdVentas
         where (ventas.MotivoAnulacion = "" OR ventas.MotivoAnulacion IS NULL) AND ventas.IdTipoMoneda = ' . $tipoMoneda . ' AND ventas.Nota!=1 AND  IdSucursal = ' . $idSucursal . ' group by YEAR(FechaCreacion), MONTH(FechaCreacion) order by YEAR(FechaCreacion) DESC, MONTH(FechaCreacion) DESC LIMIT 6');
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // NUEVAS FUNCION BANCOS 2023-04-27
    public function mesActual()
    {
        $mesActual = Carbon::now()->startOfMonth();
        return $mesActual;
    }

    public function ultimosSeisMesesConMesActual()
    {
        $fechaInicial = Carbon::now()->startOfMonth()->subMonths(5);
        $fechaFinal = Carbon::now();
        return [$fechaInicial, $fechaFinal];

    }

    public function ultimosSeisMesesSinMesActual()
    {
        $fechaInicial = Carbon::now()->startOfMonth()->subMonths(6);
        $fechaFinal = Carbon::now()->startOfMonth()->subSeconds(1);
        return [$fechaInicial, $fechaFinal];

    }

    // public function getNombreMesConCarbon($fecha)
    // {
    //     $nombreMes = substr(CARBON::parse($fecha)->locale('es')->monthName, 0, 3);
    //     $nombreEnMayuscula = strtoupper($nombreMes);
    //     return $nombreEnMayuscula;
    // }

    // public function calcularSaldoBanco($idBanco, $fechaInicial, $fechaFinal, $mesActual, $valorSucursal)
    // {
    //     $idSucursal = Session::get('idSucursal');
    //     // if ($valorSucursal == 1) {
    //     //     $getDetalleCuentaBanco = DB::table('banco_detalles as BD')
    //     //         ->join('banco', 'BD.IdBanco', '=', 'banco.IdBanco')
    //     //         ->select('IdBancoDetalles', 'FechaPago', 'BD.IdBanco', 'Entrada', 'Salida', 'BD.MontoActual', 'banco.MontoInicial', DB::raw('MONTH(BD.FechaPago) as Mes, YEAR(BD.FechaPago) as Anio'))
    //     //         ->where('BD.IdBanco', $idBanco)
    //     //         ->where(function ($query) use ($idSucursal) {
    //     //             $query->where('IdSucursal', $idSucursal)
    //     //                 ->orWhereNull('IdSucursal');
    //     //         })
    //     //         ->orderBy('FechaPago', 'desc')
    //     //         ->get();
    //     // } else {
    //     //     $getDetalleCuentaBanco = DB::table('banco_detalles as BD')
    //     //         ->join('banco', 'BD.IdBanco', '=', 'banco.IdBanco')
    //     //         ->select('IdBancoDetalles', 'FechaPago', 'BD.IdBanco', 'Entrada', 'Salida', 'BD.MontoActual', 'banco.MontoInicial', DB::raw('MONTH(BD.FechaPago) as Mes, YEAR(BD.FechaPago) as Anio'))
    //     //         ->where('BD.IdBanco', $idBanco)
    //     //         ->where('IdSucursal', $idSucursal)
    //     //         ->orderBy('FechaPago', 'desc')
    //     //         ->get();

    //     // }
    //     $getDetalleCuentaBanco = DB::table('banco_detalles as BD')
    //         ->join('banco', 'BD.IdBanco', '=', 'banco.IdBanco')
    //         ->select('IdBancoDetalles', 'FechaPago', 'BD.IdBanco', 'Entrada', 'Salida', 'BD.MontoActual', 'banco.MontoInicial', DB::raw('MONTH(BD.FechaPago) as Mes, YEAR(BD.FechaPago) as Anio'))
    //         ->where('BD.IdBanco', $idBanco)
    //         ->when($valorSucursal == 1, function ($query) use ($idSucursal) {
    //             $query->where(function ($subQuery) use ($idSucursal) {
    //                 $subQuery->where('IdSucursal', $idSucursal)
    //                     ->orWhereNull('IdSucursal');
    //             });
    //         }, function ($query) use ($idSucursal) {
    //             $query->where('IdSucursal', $idSucursal);
    //         })
    //         ->orderBy('FechaPago', 'desc')
    //         ->get();

    //     // NUEVO MONTO DETALLES CUENTA
    //     $detallesDesc = $getDetalleCuentaBanco->sortBy('FechaPago')->values();
    //     $bancoMontoInicial = $getDetalleCuentaBanco->pluck('MontoInicial')->first();
    //     $montoActual = 0;

    //     foreach ($detallesDesc as $key => $item) {
    //         if ($item->Entrada > 0) {
    //             $montoActual = $bancoMontoInicial + $item->Entrada;
    //             $bancoMontoInicial = abs($montoActual);
    //             $detallesDesc[$key]->SaldoActualCalculado = $montoActual;
    //             $detallesDesc[$key]->NombreMes = $this->getNombreMesAbreviado($item->Mes) . ' ' . $item->Anio;

    //         }
    //         if ($item->Salida > 0) {
    //             $montoActual = $bancoMontoInicial - $item->Salida;
    //             $bancoMontoInicial = abs($montoActual);
    //             $detallesDesc[$key]->SaldoActualCalculado = $montoActual;
    //             $detallesDesc[$key]->NombreMes = $this->getNombreMesAbreviado($item->Mes) . ' ' . $item->Anio;
    //         }

    //         if ($item->Salida == 0 && $item->Entrada == 0) {
    //             $detallesDesc[$key]->SaldoActualCalculado = $montoActual;
    //             $detallesDesc[$key]->NombreMes = $this->getNombreMesAbreviado($item->Mes) . ' ' . $item->Anio;
    //         }

    //     }
    //     $detallesCuentaCorriente = $detallesDesc->whereBetween('FechaPago', [$fechaInicial, $fechaFinal]);

    //     $mesesBanco = $detallesCuentaCorriente->pluck('Mes')->unique()->values()->toArray();
    //     $datosBanco = [];
    //     for ($i = 0; $i <= count($mesesBanco) - 1; $i++) {
    //         $a = $detallesCuentaCorriente->where('Mes', $mesesBanco[$i])->last();
    //         array_push($datosBanco, $a);
    //     }

    //     $datosBanco = collect($datosBanco);
    //     $nombreMes = $datosBanco->pluck('NombreMes');
    //     $saldoFinMes = $datosBanco->pluck('SaldoActualCalculado');
    //     $saldoFinMes = $saldoFinMes->map(function ($valor) {
    //         return round($valor, 2);
    //     });

    //     $getDetalleBancoMesActual = $detallesCuentaCorriente->where('FechaPago', '>=', $mesActual);
    //     $totalIngresoMesActual = $getDetalleBancoMesActual->sum('Entrada');
    //     $totalSalidaMesActual = $getDetalleBancoMesActual->sum('Salida');
    //     // $saldoMesActual = $datosBanco->where('FechaPago', '>=', $mesActual)->first()->SaldoActualCalculado ?? 0;
    //     $saldoMesActual = $getDetalleBancoMesActual->last()->SaldoActualCalculado ?? 0;

    //     return (object) ['NombreMes' => $nombreMes, 'SaldoFinMes' => $saldoFinMes, 'TotalIngresoMesActual' => $totalIngresoMesActual, 'TotalSalidaMesActual' => $totalSalidaMesActual, 'SaldoMesActual' => $saldoMesActual];
    // }

    public function getDatosBancos(Request $req)
    {
        if ($req->Ajax()) {
            $loadDatos = new DatosController();
            $fechas = $this->ultimosSeisMesesConMesActual();
            $mesActual = $this->mesActual();

            // $saldoBanco = $this->calcularSaldoBanco($req->idBanco, $fechas[0], $fechas[1], $mesActual, $req->valorSucursal);

            $idUsuario = Session::get('idUsuario');
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);

            $resultadoCalculo = $this->calcularSaldoCuentaBancaria($req->idBanco, $usuarioSelect->CodigoCliente);
            $detallesCuentaCorriente = $resultadoCalculo->whereBetween('FechaPago', [$fechas[0], $fechas[1]]);
            $mesesBanco = $detallesCuentaCorriente->pluck('Mes')->unique()->values()->toArray();
            $datosBanco = [];
            for ($i = 0; $i <= count($mesesBanco) - 1; $i++) {
                $a = $detallesCuentaCorriente->where('Mes', $mesesBanco[$i])->last();
                array_push($datosBanco, $a);
            }

            $datosBanco = collect($datosBanco);
            $nombreMes = $datosBanco->pluck('NombreMes');
            $saldoFinMes = $datosBanco->pluck('SaldoActualCalculado');
            $saldoFinMes = $saldoFinMes->map(function ($valor) {
                return round($valor, 2);
            });

            $getDetalleBancoMesActual = $detallesCuentaCorriente->where('FechaPago', '>=', $mesActual);
            $totalIngresoMesActual = $getDetalleBancoMesActual->sum('Entrada');
            $totalSalidaMesActual = $getDetalleBancoMesActual->sum('Salida');
            // $saldoMesActual = $datosBanco->where('FechaPago', '>=', $mesActual)->first()->SaldoActualCalculado ?? 0;
            $saldoMesActual = $getDetalleBancoMesActual->last()->SaldoActualCalculado ?? 0;

            return response([$nombreMes, $saldoFinMes, $totalIngresoMesActual, $totalSalidaMesActual, $saldoMesActual]);
        }
    }
}
