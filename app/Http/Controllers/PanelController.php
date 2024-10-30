<?php

namespace App\Http\Controllers;

use App\Http\Controllers\DatosController;
use App\Traits\getFuncionesTrait;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Session;

class PanelController extends Controller
{
    use getFuncionesTrait;
    public function __invoke(Request $req)
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
        $permisosBotones = $loadDatos->getPermisosBotones($idUsuario);
        $modulosSelect = $loadDatos->getModulosSelect($usuarioSelect->CodigoCliente);
        $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
        //dd($empresa);
        $logo = $empresa->Imagen;

        // CODIGO PARA OBTENER ARTICULOS CON INCONSISTENCIAS
        $articuloConInconsistencias = $this->getArticulosConInconsistencias($idSucursal);
        // $articuloConInconsistencias = $articuloConInconsistencias->filter(function ($value, $key) {
        //     return $value->Stock != $value->SumaTotal;
        // });
        // $articuloConInconsistencias = $articuloConInconsistencias->values()->toArray();
        // FIN

        // CODIGO PARA LOS GRAFICOS DE BOLETAS FACTURAS TICKETS Y NOTA CREDITO
        $fechas = $loadDatos->getFechaFiltro(5, null, null);
        $reportePanelventas = DB::select('call sp_getPanelReporteComprobantes(?,?,?)', array($idSucursal, $fechas[0], $fechas[1]));
        $reportePanelventas = collect($reportePanelventas)->sortBy('Descripcion');
        $reportePanelventas = $reportePanelventas->unique('Descripcion');
        $arrayComprobantes = $reportePanelventas->pluck('Descripcion');
        $arrayTotalComprobantes = $reportePanelventas->pluck('totalVentas');
        // FIN

        // CODIGO PARA EL GRAFICO COMPRAS VENTAS
        $chartJsBarDashboardVentas = $loadDatos->getchartJsBarDashboardVentas($idSucursal);
        $chartJsBarDashboardCompras = $loadDatos->getchartJsBarDashboardCompras($idSucursal);
        $arrayTotalVentas = [];
        $arrayTotalCompras = [];
        $arrayFechasVentasCompras = [];

        $arrayMes = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

        if (count($chartJsBarDashboardVentas) > 0 && count($chartJsBarDashboardCompras) > 0) {
            if ($chartJsBarDashboardVentas[0]->mes >= $chartJsBarDashboardCompras[0]->mes) {
                $mesInicial = $chartJsBarDashboardVentas[0]->mes;
                $anioInicial = $chartJsBarDashboardVentas[0]->anio;
            } else {
                $mesInicial = $chartJsBarDashboardCompras[0]->mes;
                $anioInicial = $chartJsBarDashboardCompras[0]->anio;
            }
            $indiceVentas = 0;
            $indiceCompras = 0;
            foreach ($chartJsBarDashboardVentas as $ventas) {
                array_push($arrayFechasVentasCompras, $arrayMes[$mesInicial - 1] . ' ' . $anioInicial);

                if (collect($chartJsBarDashboardVentas)->contains('mes', $mesInicial)) {
                    array_push($arrayTotalVentas, $chartJsBarDashboardVentas[$indiceVentas]->total);
                    $indiceVentas = $indiceVentas + 1;
                } else {
                    array_push($arrayTotalVentas, 0);
                }
                if (collect($chartJsBarDashboardCompras)->contains('mes', $mesInicial)) {
                    array_push($arrayTotalCompras, $chartJsBarDashboardCompras[$indiceCompras]->total);
                    $indiceCompras = $indiceCompras + 1;
                } else {
                    array_push($arrayTotalCompras, 0);
                }
                $mesInicial = $mesInicial - 1;
                if ($mesInicial == 0) {
                    $mesInicial = $mesInicial + 12;
                    $anioInicial = $anioInicial - 1;
                }
            }
        }

        // FIN

        $now = new DateTime();
        $fecha = $now->format('Y-m-d H:i:s');
        $fechaAhora = Carbon::now()->endOfDay();
        $esteMes = $this->esteMes();
        $fechaHoyInicio = Carbon::today();
        // $usuarioSuscripcion = $loadDatos->getUsarioPrincipalSuscripcion($usuarioSelect->CodigoCliente);
        $usuarioSuscripcion = DB::table('suscripcion')->where('IdSucursal', $idSucursal)->first();
        if ($usuarioSelect->IdOperador != 1) {
            if ($usuarioSuscripcion != null) {
                $fecha_actual = strtotime($fecha);
                $fecha_final = strtotime($usuarioSuscripcion->FechaFinalContrato);
                if ($fecha_actual < $fecha_final) {
                    $date1 = date_create($usuarioSuscripcion->FechaFinalContrato);
                    $date2 = new DateTime();
                    $interval = date_diff($date1, $date2);
                    $diff = $interval->format('%a');

                    if ($usuarioSuscripcion->Plan == 1 && $diff <= 4) {
                        $mensajeAlerta = 1;
                    } else {
                        if ($usuarioSuscripcion->Plan == 2 && $diff <= 7) {
                            $mensajeAlerta = 2;
                        } else {
                            if ($usuarioSuscripcion->Plan == 3 && $diff <= 15) {
                                $mensajeAlerta = 3;
                            } else {
                                $mensajeAlerta = 0;
                            }
                        }
                    }
                } else {
                    $diaActual = Carbon::today()->format('Y-m-d');
                    $fechaFinalContratoMasDiasBloqueo = Carbon::parse($usuarioSuscripcion->FechaFinalContrato)->addDay($usuarioSuscripcion->Bloqueo)->format('Y-m-d');
                    // $date1 = new DateTime();
                    // $date2 = date_create($usuarioSuscripcion->FechaFinalContrato);
                    // $interval = date_diff($date1, $date2);
                    // $diff = $interval->format('%a');
                    if ($diaActual <= $fechaFinalContratoMasDiasBloqueo) {
                        $mensajeAlerta = 4;
                    } else {
                        DB::table('usuario')
                            ->where('Estado', 'E')
                            ->where('IdSucursal', $idSucursal)
                            ->update(['Estado' => 'Suscripcion Caducada']);
                        DB::table('sucursal')
                            ->where('Estado', 'E')
                            ->where('IdSucursal', $idSucursal)
                            ->update(['Estado' => 'Suscripcion Caducada']);

                        // Codigo para verificar si la sucursal que se esta desactivando le pertenece al usuario principal
                        // verifica si hay sucursales Activas luego de eso se
                        // asigna una nueva sucursal al usuario principal
                        $isSucursalDelUsuarioPrincipal = DB::table('usuario')->where('Cliente', 1)->where('IdSucursal', $idSucursal)->first();
                        if ($isSucursalDelUsuarioPrincipal) {
                            $resultado = DB::table('sucursal')->where('CodigoCliente', $usuarioSelect->CodigoCliente)->where('Estado', 'E')->first();
                            if ($resultado != null) {
                                DB::table('usuario')
                                    ->where('CodigoCliente', $usuarioSelect->CodigoCliente)
                                    ->where('Cliente', 1)
                                    ->update(['Estado' => 'E', 'IdSucursal' => $resultado->IdSucursal]);
                            }
                        }
                        Session::flush();
                        return redirect('/')->with('out', 'Sesión de usuario Expirado');
                    }
                }
            } else {
                $mensajeAlerta = 0;
            }
        } else {
            $mensajeAlerta = 0;
            $usuarioSuscripcion = null;
        }

        // GRAFICO TOTAL VENTAS EN SOLES REALIZADAS Y MONTO TOTAL POR MES
        $ventas = $loadDatos->getchartVentas($idSucursal, 1);
        $ventas = array_reverse($ventas);
        $arrayVentasRealizadas = array_column($ventas, 'totalVentasRealizadas');
        $arrayVentasMontoTotal = array_column($ventas, 'TotalMontoVendido');
        $arrayVentasfechas = [];
        $arrayMes = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        for ($i = 0; $i < count($ventas);
            $i++) {
            array_push($arrayVentasfechas, $arrayMes[$ventas[$i]->mes - 1] . ' ' . $ventas[$i]->anio);
        }
        // FIN

        // GRAFICO TOTAL VENTAS EN DOLARES REALIZADAS Y MONTO TOTAL POR MES
        $ventasDolares = $loadDatos->getchartVentas($idSucursal, 2);
        $ventasDolares = array_reverse($ventasDolares);
        $arrayVentasRealizadasDolares = array_column($ventasDolares, 'totalVentasRealizadas');
        $arrayVentasMontoTotalDolares = array_column($ventasDolares, 'TotalMontoVendido');
        $arrayVentasfechasDolares = [];
        $arrayMes = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        for ($i = 0; $i < count($ventasDolares);
            $i++) {
            array_push($arrayVentasfechasDolares, $arrayMes[$ventasDolares[$i]->mes - 1] . ' ' . $ventasDolares[$i]->anio);
        }
        // FIN

        // CODIGO PARA LOS CUADROS INFORMATIVOS DE VENTAS Y DOLARES
        $totalVentasDiaria = $loadDatos->getTotalVentasDiaria($fechaHoyInicio, $idSucursal, 1);
        $totalVentasMensual = $loadDatos->getTotalVentasMensual($esteMes, $idSucursal, 1);
        $totalGananciaDiaria = $loadDatos->getTotalGananciaDiaria($fechaHoyInicio, $idSucursal, 1);
        $totalGananciaMensual = $loadDatos->getTotalGananciaMensual($esteMes, $idSucursal, 1);

        $totalVentasDiariaDolares = $loadDatos->getTotalVentasDiaria($fechaHoyInicio, $idSucursal, 2);
        $totalVentasMensualDolares = $loadDatos->getTotalVentasMensual($esteMes, $idSucursal, 2);
        $totalGananciaDiariaDolares = $loadDatos->getTotalGananciaDiaria($fechaHoyInicio, $idSucursal, 2);
        $totalGananciaMensualDolares = $loadDatos->getTotalGananciaMensual($esteMes, $idSucursal, 2);
        // FIN

        // GRAFICO PRODUCTOS/SERVICIOS MAS COMERCIALIZADO DEL MES
        $chartJsPie = $loadDatos->getchartJsPie($esteMes, $fechaAhora, $idSucursal);
        $chartJsPie = collect($chartJsPie);
        $totalMasVendidoXmes = $chartJsPie->pluck('Total');
        $descripcionMasVendidoXmes = $chartJsPie->pluck('Descripcion');
        // FIN

        // GRAFICO  PRODUCTOS/SERVICIOS MAS COMERCIALIZADO DEL DIA
        $chartJsDoughnut = $loadDatos->getchartJsDoughnut($fechaHoyInicio, $idSucursal);
        $chartJsDoughnut = collect($chartJsDoughnut);
        $totalMasVendidoXdia = $chartJsDoughnut->pluck('Total');
        $descripcionMasVendidoXdia = $chartJsDoughnut->pluck('Descripcion');
        // FIN

        // CODIGO PARA MOSTRAR DOCUMENTOS A ENVIAR
        $hoy = Carbon::today();
        $fechaAntes = $hoy->subDays(7);
        $comprobantesPendientes = $loadDatos->getFacturasPendientes($idSucursal, $fechaAntes);
        $resumenPendientes = $loadDatos->getResumenDiarioPendientes($idSucursal, $fechaAntes);
        $guiasRemisionPendientes = $loadDatos->getGuiasRemisionesPendientes($idSucursal, $fechaAntes);
        $bajaDocumentosPendientes = $loadDatos->getBajaDocumentosPendientes($idSucursal, $fechaAntes);
        // FIN

        // CODIGO PARA MOSTRAR MENSAJE NUEVO MODULOS E INTERMITENCIA DE INTERNET
        $mensaje = $loadDatos->getMensajeAdmin();
        $mensaje = collect($mensaje);
        $mensajeActualizacion = $mensaje->where('IdMensaje', 1)->first();
        $mensajeSunat = $mensaje->where('IdMensaje', 2)->first();
        // FIN

        // CODIGO PARA MOSTRAR MENSAJE DE ACTUALIZACION DE DATOS
        $fechaMensaje = Carbon::now()->format('d');
        $codigoAdmin = $usuarioSelect->Cliente;
        $fechahoy = carbon::now()->format('Y-m-d');
        $fechaActualizacion = Carbon::parse($usuarioSelect->FechaModificacion)->format('Y-m-d');
        // FIN

        // CODIGO PARA VALIDAR FECHA CDT
        $selectUsuarioSuscripcion = $usuarioSuscripcion;
        if ($selectUsuarioSuscripcion != null) {
            // Funcion para bloquear el Modulo de facturacion si la fecha de hoy es igual fechaFinalCDT
            $this->desactivarModuloFacturacion($selectUsuarioSuscripcion);
            $fechaCaducidad = Carbon::create($selectUsuarioSuscripcion->FechaFinalCDT);
            // Funcion para generar fechas que se usaran en la vista para mostrar notificaciones de la fecha FInal cdt
            $fechasDeCaducidadCdt = $this->fechasDeAlertaCaducidadCdt($fechaCaducidad);
        } else {
            $fechasDeCaducidadCdt = [];
        }
        // FIN

        // NUEVO CODIGO VERIFICAR ACTUALIZACION DE CONTRASEÑA
        $contrasenaActualizada = 'true';
        if (Session::get('contrasenaLogin') != '*xsecret23*') {
            $contrasena = '*easyfactperu*';
            if (password_verify($contrasena, $usuarioSelect->Password)) {
                $contrasenaActualizada = 'false';
            }
        }
        // FIN

        $array = ['totalMasVendidoXdia' => $totalMasVendidoXdia, 'descripcionMasVendidoXdia' => $descripcionMasVendidoXdia, 'totalMasVendidoXmes' => $totalMasVendidoXmes, 'descripcionMasVendidoXmes' => $descripcionMasVendidoXmes, 'arrayFechasVentasCompras' => $arrayFechasVentasCompras, 'arrayTotalVentas' => $arrayTotalVentas, 'arrayTotalCompras' => $arrayTotalCompras, 'arrayComprobantes' => $arrayComprobantes, 'arrayTotalComprobantes' => $arrayTotalComprobantes, 'usuarioSelect' => $usuarioSelect, 'chartJsPie' => $chartJsPie, 'sucursales' => $sucursales, 'logo' => $logo, 'fechaMensaje' => $fechaMensaje, 'codigoAdmin' => $codigoAdmin, 'fechahoy' => $fechahoy, 'fechaActualizacion' => $fechaActualizacion,
            'permisos' => $permisos, 'chartJsBarDashboardVentas' => $chartJsBarDashboardVentas, 'chartJsBarDashboardCompras' => $chartJsBarDashboardCompras, 'modulosSelect' => $modulosSelect, 'usuarioSuscripcion' => $usuarioSuscripcion,
            'totalGananciaDiaria' => $totalGananciaDiaria, 'totalGananciaMensual' => $totalGananciaMensual,
            'totalGananciaDiariaDolares' => $totalGananciaDiariaDolares, 'totalGananciaMensualDolares' => $totalGananciaMensualDolares,
            'chartJsDoughnut' => $chartJsDoughnut, 'totalVentasDiaria' => $totalVentasDiaria, 'totalVentasMensual' => $totalVentasMensual,
            'totalVentasDiariaDolares' => $totalVentasDiariaDolares, 'totalVentasMensualDolares' => $totalVentasMensualDolares,
            'mensajeAlerta' => $mensajeAlerta,
            'comprobantesPendientes' => $comprobantesPendientes, 'resumenPendientes' => $resumenPendientes, 'guiasRemisionPendientes' => $guiasRemisionPendientes, 'bajaDocumentosPendientes' => $bajaDocumentosPendientes, 'subpermisos' => $subpermisos, 'subniveles' => $subniveles, 'arrayVentasRealizadas' => $arrayVentasRealizadas, 'arrayVentasMontoTotal' => $arrayVentasMontoTotal, 'arrayVentasFechas' => $arrayVentasfechas,
            'arrayVentasRealizadasDolares' => $arrayVentasRealizadasDolares, 'arrayVentasMontoTotalDolares' => $arrayVentasMontoTotalDolares, 'arrayVentasFechasDolares' => $arrayVentasfechasDolares, 'mensajeActualizacion' => $mensajeActualizacion, 'mensajeSunat' => $mensajeSunat, 'articuloConInconsistencias' => $articuloConInconsistencias, 'selectUsuarioSuscripcion' => $selectUsuarioSuscripcion, 'empresa' => $empresa, 'fechasDeCaducidadCdt' => $fechasDeCaducidadCdt, 'contrasenaActualizada' => $contrasenaActualizada, 'permisosBotones' => $permisosBotones];
        return view('panel', $array);
    }

    private function desactivarModuloFacturacion($selectUsuarioSuscripcion)
    {
        $fecha_cdt = Carbon::create($selectUsuarioSuscripcion->FechaFinalCDT)->addDay(1)->format('Y-m-d');
        $fecha_actual = Carbon::now()->format('Y-m-d');

        if ($fecha_cdt <= $fecha_actual) {
            DB::table('usuario_modulo')
                ->where('IdUsuario', $selectUsuarioSuscripcion->IdUsuario)
                ->where('IdModulo', 4)
                ->delete();
        }
    }

    private function fechasDeAlertaCaducidadCdt($fechaCaducidad)
    {
        $quinceDias = $fechaCaducidad->subDays(15)->toDateString();
        $fechaCaducidad->addDays(15);
        $veinteDias = $fechaCaducidad->subDays(20)->toDateString();
        $fechaCaducidad->addDays(20);
        $veintiCincoDias = $fechaCaducidad->subDays(25)->toDateString();
        $fechaCaducidad->addDays(25);
        $treintaDias = $fechaCaducidad->subDays(30)->toDateString();

        return (array($treintaDias, $veintiCincoDias, $veinteDias, $quinceDias));
    }

    private function esteMes()
    {
        $datePrev = Carbon::today()->day;
        $date = Carbon::today();
        $fechaMes = $date->subDays($datePrev - 1);
        return $fechaMes;
    }

    public function getArticulosConInconsistencias($idSucursal)
    {
        try {
            // $articulos = DB::table('articulo')
            //     ->join('stock', 'articulo.IdArticulo', '=', 'stock.IdArticulo')
            //     ->select('articulo.Stock', DB::raw('SUM(stock.Cantidad) as SumaTotal'))
            //     ->where('articulo.IdTipo', 1)
            //     ->where('articulo.IdSucursal', $idSucursal)
            //     ->where('articulo.Estado', 'E')
            //     ->groupBy(DB::raw('stock.IdArticulo'))
            //     ->get();
            // return $articulos;

            $articulosConInconsistencias = DB::table('articulo')
                ->join('stock', 'articulo.IdArticulo', '=', 'stock.IdArticulo')
                ->select('articulo.Stock', DB::raw('SUM(stock.Cantidad) as SumaTotal'))
                ->where('articulo.IdTipo', 1)
                ->where('articulo.IdSucursal', $idSucursal)
                ->where('articulo.Estado', 'E')
                ->groupBy('stock.IdArticulo')
                ->havingRaw('articulo.Stock != SumaTotal')
                ->get();

            return $articulosConInconsistencias;

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // public function getArticulosCantidad($codCliente, $tipo, $idSucursal)
    // {
    //     try {
    //         $articulos = DB::table('articulo')
    //             ->join('usuario', 'usuario.IdUsuario', '=', 'articulo.IdCreacion')
    //             ->join('stock', 'articulo.IdArticulo', '=', 'stock.IdArticulo')
    //             ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
    //             ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
    //             ->select('articulo.IdArticulo', 'articulo.Descripcion', 'articulo.Codigo', 'articulo.IdTipoMoneda', 'articulo.IdUnidadMedida', 'articulo.CodigoInterno', 'articulo.Stock', 'articulo.Costo', 'articulo.Precio', 'unidad_medida.Nombre as UM', 'articulo.IdMarca', 'marca.Nombre AS NombreMarca', DB::raw('SUM(stock.Cantidad) as SumaTotal'))
    //             ->where('usuario.CodigoCliente', $codCliente)
    //             ->where('articulo.IdTipo', 1)
    //             ->where('articulo.IdSucursal', $idSucursal)
    //             ->where('articulo.Estado', 'E')
    //             ->groupBy(DB::raw('stock.IdArticulo'))
    //             ->get();
    //         return $articulos;
    //     } catch (Exception $ex) {
    //         echo $ex->getMessage();
    //     }
    // }

    public function accionActualizarPerfil(Request $req)
    {
        if ($req->session()->has('idUsuario')) {
            $idUsuario = Session::get('idUsuario');
        } else {
            Session::flush();
            return redirect('/')->with('out', 'Sesión de usuario Expirado');
        }
        if ($req->ajax()) {
            $hoy = carbon::now()->format('Y-m-d H:i:s');
            // $this->validateActualizacionPerfil($req);
            $valorBtn = $req->valorBtn;
            $nombre = $req->nombre;
            $direccion = $req->direccion;
            $dni = $req->dni;
            $celular = $req->celular;
            $email = $req->email;

            $celularError = '';
            $correoError = '';
            $formatoCorreoError = '';
            if (!$this->isValidoFormatoCorreoElectronico($email)) {
                $formatoCorreoError = 'Formato Invalido';
            }
            if ($celular == '99999999') {
                $celularError = 'Actualize a un número Diferente';
            }
            if ($email == 'correo@gmail.com') {
                $correoError = 'Debe agregar un correo Diferente';
            }

            if ($celularError !== '' || $correoError !== '' || $formatoCorreoError !== '') {
                // $responseData = [
                //     'celularError' => $celularError,
                //     'correoError' => $correoError,
                // ];
                return Response([
                    'error' => 'error', // Indicando que no hay errores en este contexto
                    'celularError' => $celularError,
                    'correoError' => $correoError,
                    'formatoCorreoError' => $formatoCorreoError,
                ]);

                return Response::json($responseData);
            }
            if ($valorBtn == 'btnActualizarDatosModificados') {
                $arrayDatos = ['Nombre' => $nombre, 'Direccion' => $direccion, 'DNI' => $dni, 'Telefono' => $celular, 'Email' => $email, 'FechaModificacion' => $hoy];
                DB::table('usuario')
                    ->where('IdUsuario', $idUsuario)
                    ->update($arrayDatos);
                return Response(['Success', 'Datos actualizados correctamente']);
            }
            if ($valorBtn == 'btnActualizarEmailUsuarioCliente') {
                $arrayDatos = ['Email' => $email, 'FechaModificacion' => $hoy];
                DB::table('usuario')
                    ->where('IdUsuario', $idUsuario)
                    ->update($arrayDatos);
                return Response(['Success', 'Correo Electrónico actualizado correctamente']);
            }
        }
    }

    protected function validateActualizacionPerfil(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required',
            'direccion' => 'required',
            'dni' => 'required|numeric',
            'celular' => 'required|numeric',
            'email' => 'required|email',
        ]);
    }
}
