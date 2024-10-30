<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DateTime;
use DB;
use Storage;

class DatosController extends Controller
{
    public function getPaquetesSuscripcion()
    {
        $paquetesSuscripcion = DB::table('planes_suscripcion')->where('Estado', 'E')->get();
        return $paquetesSuscripcion;
    }
    // OBTENER DATOS PARA LOS PERMISOS DE BOTONES ADMINISTRATIVOS
    public function getPermisosBotones($id)
    {
        $botones = DB::table('permisos_botones_usuarios AS pbu')
            ->select('IdPermisoBoton', 'Nombre')
            ->join('permisos_botones', 'pbu.IdPermisoBoton', '=', 'permisos_botones.Id')
            ->where('pbu.IdUsuario', $id)
            ->where('pbu.Estado', 'E')
            ->get();
        return $botones;
    }
    public function getPermisosSubBotones($id)
    {
        $subBotones = DB::table('permisos_subbotones_usuarios AS psu')
            ->select('IdPermisoSubBoton', 'Nombre')
            ->join('permisos_subbotones', 'psu.IdPermisoSubBoton', '=', 'permisos_subbotones.Id')
            ->where('psu.IdUsuario', $id)
            ->where('psu.Estado', 'E')
            ->get();
        return $subBotones;
    }
    public function getAllPermisosBotonesUsuario($id)
    {
        $botones = DB::table('permisos_botones_usuarios')
            ->select('IdPermisoBoton', 'Estado')
            ->where('IdUsuario', $id)
            ->get();
        return $botones;
    }
    public function getAllPermisosSubBotonesUsuario($id)
    {
        $subBotones = DB::table('permisos_subbotones_usuarios')
            ->select('IdPermisoSubBoton', 'Estado')
            ->where('IdUsuario', $id)
            ->get();
        return $subBotones;
    }

    public function getDatosPermisosSubBotonesDelSistema($idSubBotones)
    {
        try {
            $subBotones = DB::table('permisos_subbotones')
                ->select('Id as IdPermisoSubBoton', 'IdPermisoBoton')
                ->where('Estado', 1)
                ->whereIn('Id', $idSubBotones)
                ->get();
            return $subBotones;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getAllPermisosBotonesDelSistema()
    {
        try {
            $botones = DB::table('permisos_botones')
                ->select('Id', 'Nombre')
                ->where('Estado', 1)
                ->get();
            return $botones;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getAllPermisosSubBotonesDelSistema()
    {
        try {
            $subBotones = DB::table('permisos_subbotones')
                ->select('Id', 'IdPermisoBoton', 'Nombre')
                ->where('Estado', 1)
                ->get();
            return $subBotones;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // OBTENER DATOS PERMISOS DE BOTONES ADMINISTRATIVOS PARA LOS PLANES DE SUSCRIPCION
    public function getAllPermisosBotonesPlanSucripcion($id)
    {
        $datos = DB::table('permisos_botones_plan_suscripciones')
            ->select('IdPermisoBoton', 'Estado')
            ->where('IdPlanSuscripcion', $id)
            ->get();
        return $datos;
    }
    public function getAllPermisosSubBotonesPlanSucripcion($id)
    {
        $datos = DB::table('permisos_subbotones_plan_suscripciones')
            ->select('IdPermisoSubBoton', 'Estado')
            ->where('IdPlanSuscripcion', $id)
            ->get();
        return $datos;
    }

    public function getRucEmpresa($idUsuario)
    {
        try {
            $empresa = DB::table('usuario')
                ->join('empresa', 'usuario.CodigoCliente', '=', 'empresa.CodigoCliente')
                ->select('IdUsuario', 'empresa.Ruc', 'empresa.Nombre', 'usuario.CodigoCliente')
                ->where('IdUsuario', $idUsuario)
                ->first();
            return $empresa;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getPermisosActivadosPlanSuscripcion($id)
    {
        try {
            $datos = DB::table('permiso_planSuscripcion')
                ->where('Estado', 'E')
                ->where('IdPlanSuscripcion', $id)
                ->get();
            return $datos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getSubPermisosActivadosPlanSuscripcion($id)
    {
        try {
            $datos = DB::table('subPermiso_planSuscripcion')
                ->where('Estado', 'E')
                ->where('IdPlanSuscripcion', $id)
                ->get();
            return $datos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getSubNivelesActivadosPlanSuscripcion($id)
    {
        try {
            $datos = DB::table('subNivel_planSuscripcion')
                ->where('Estado', 'E')
                ->where('IdPlanSuscripcion', $id)
                ->get();
            return $datos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getConsultaOrdenesCompras($idSucursal, $tipoPago, $fechaInicial, $fechaFinal)
    {
        if ($tipoPago == 0) {
            $resultado = DB::table('orden_compra')
                ->join('proveedor', 'orden_compra.IdProveedor', '=', 'proveedor.IdProveedor')
                ->select('orden_compra.*', 'proveedor.Nombre as NombreProveedor', 'proveedor.NumeroDocumento', DB::raw("DATE_FORMAT(orden_compra.FechaRecepcion, '%Y-%m-%d' )AS FechaRecepcion"))
                ->where('orden_compra.IdSucursal', $idSucursal)
                ->whereBetween('orden_compra.FechaEmision', [$fechaInicial, $fechaFinal])
                ->get();
            return $resultado;
        } else {
            $resultado = DB::table('orden_compra')
                ->join('proveedor', 'orden_compra.IdProveedor', '=', 'proveedor.IdProveedor')
                ->select('orden_compra.*', 'proveedor.Nombre as NombreProveedor', 'proveedor.NumeroDocumento', DB::raw("DATE_FORMAT(orden_compra.FechaRecepcion, '%Y-%m-%d' )AS FechaRecepcion"))
                ->where('orden_compra.IdSucursal', $idSucursal)
                ->where('orden_compra.IdTipoPago', $tipoPago)
                ->whereBetween('orden_compra.FechaEmision', [$fechaInicial, $fechaFinal])
                ->get();
            return $resultado;
        }
    }
    public function getCajaAperturas($idSucursal, $tipoMoneda)
    {
        try {

            $resultado = DB::table('caja')
                ->join('usuario', 'caja.IdUsuario', '=', 'usuario.IdUsuario')
                ->select('caja.*', 'usuario.Nombre as Usuario', DB::raw("DATE_FORMAT(caja.FechaCierre, '%d-%m-%Y' )AS fechaCierreCaja"))
                ->where('caja.IdSucursal', $idSucursal)
                ->where('caja.Estado', 'E')
                ->get();

            $array = [];
            for ($i = 0; $i < count($resultado); $i++) {
                $array[$i] = DB::table('caja_ventas')
                    ->select(DB::raw("SUM(ventas.Total) as ImporteTotal"), DB::raw("SUM(ventas.MontoEfectivo) as Efectivo"), DB::raw("SUM(ventas.MontoTarjeta) as Tarjeta"))
                    ->join('caja', 'caja_ventas.IdCaja', '=', 'caja.IdCaja')
                    ->join('ventas', 'caja_ventas.IdVentas', '=', 'ventas.IdVentas')
                    ->where('caja_ventas.IdCaja', $resultado[$i]->IdCaja)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->get();
                $array[$i]->Cobranzas = DB::table('fecha_pago')
                    ->join('ventas', 'fecha_pago.IdVenta', '=', 'ventas.IdVentas')
                    ->select(DB::raw("SUM(fecha_pago.MontoEfectivo) as EfectivoCobranzas"), DB::raw("SUM(fecha_pago.MontoTarjeta) as TarjetaCobranzas"))
                    ->where('fecha_pago.IdCaja', $resultado[$i]->IdCaja)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->get();
                $array[$i]->Ingreso = DB::table('ingresoegreso')
                    ->select(DB::raw("SUM(Monto) as MontoIngreso"))
                    ->where('Tipo', 'I')
                    ->where('IdCaja', $resultado[$i]->IdCaja)
                    ->get();
                $array[$i]->Egreso = DB::table('ingresoegreso')
                    ->select(DB::raw("SUM(Monto) as MontoEgreso"))
                    ->where('Tipo', 'E')
                    ->where('IdCaja', $resultado[$i]->IdCaja)
                    ->get();
                $array[$i]->Amortizacion = DB::table('amortizacion')
                    ->select(DB::raw("SUM(Monto) as MontoAmortizacion"))
                    ->where('FormaPago', 1)
                // ->where('IdTipoMoneda', 1)
                    ->where('IdCaja', $resultado[$i]->IdCaja)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->get();

                $array[$i]->Inicial = $resultado[$i]->Inicial;
                $array[$i]->Usuario = $resultado[$i]->Usuario;
                $array[$i]->FechaApertura = $resultado[$i]->FechaApertura;
                $array[$i]->MontoIngreso = $array[$i]->Ingreso[0]->MontoIngreso;
                $array[$i]->MontoEgreso = $array[$i]->Egreso[0]->MontoEgreso;
                $array[$i]->MontoAmortizacion = $array[$i]->Amortizacion[0]->MontoAmortizacion;
                $array[$i]->EfectivoCobranzas = number_format($array[$i]->Cobranzas[0]->EfectivoCobranzas, 2, '.', ',');
                // suma total de Efectivo por Cajero en soles
                $totalEfectivoXcajeroSoles = floatval($resultado[$i]->Inicial) + floatval($array[$i][0]->Efectivo) + floatval($array[$i]->Cobranzas[0]->EfectivoCobranzas) + floatval($array[$i]->Ingreso[0]->MontoIngreso) - floatval($array[$i]->Egreso[0]->MontoEgreso) + floatval($array[$i]->Amortizacion[0]->MontoAmortizacion);
                $array[$i]->TotalEfectivoXcajeroSoles = $totalEfectivoXcajeroSoles;
                // Fin

                // suma total de Efectivo por Cajero en Dolares
                $totalEfectivoXcajeroDolares = floatval($array[$i][0]->Efectivo) + floatval($array[$i]->Cobranzas[0]->EfectivoCobranzas) + floatval($array[$i]->Amortizacion[0]->MontoAmortizacion);
                $array[$i]->TotalEfectivoXcajeroDolares = $totalEfectivoXcajeroDolares;
                // Fin

                $array[$i]->Estado = $resultado[$i]->Estado;
                $array[$i]->idCaja = $resultado[$i]->IdCaja;
                $array[$i]->TotalEfectivoContado = $array[$i][0]->Efectivo;
                $array[$i]->TotalEfectivoCobranzas = number_format($array[$i]->Cobranzas[0]->EfectivoCobranzas, 2, '.', ',');
                $array[$i]->TotalEfectivoAmortizacion = number_format($array[$i]->Amortizacion[0]->MontoAmortizacion, 2, '.', ',');
            }
            //dd($array[0]->MontoIngreso);
            return $array;

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCierreDeCajaXMesActual($idSucursal, $fecha, $tipoMoneda)
    {
        try {

            $resultado = DB::table('caja')
                ->join('usuario', 'caja.IdUsuario', '=', 'usuario.IdUsuario')
                ->select('caja.*', 'usuario.Nombre as Usuario', DB::raw("DATE_FORMAT(caja.FechaCierre, '%d-%m-%Y' )AS fechaCierreCaja "))
                ->where('caja.IdSucursal', $idSucursal)
                ->where('caja.Estado', 'D')
                ->where('FechaCierre', '>', $fecha)
            // ->groupBy(DB::raw("fechaCierreCaja"))
                ->get();

            $array = [];
            for ($i = 0; $i < count($resultado); $i++) {
                $array[$i] = DB::table('caja_ventas')
                    ->select(DB::raw("SUM(ventas.Total) as ImporteTotal"), DB::raw("SUM(ventas.MontoEfectivo) as Efectivo"), DB::raw("SUM(ventas.MontoTarjeta) as Tarjeta"))
                    ->join('caja', 'caja_ventas.IdCaja', '=', 'caja.IdCaja')
                    ->join('ventas', 'caja_ventas.IdVentas', '=', 'ventas.IdVentas')
                    ->where('caja_ventas.IdCaja', $resultado[$i]->IdCaja)
                    ->where('ventas.IdTipoMoneda', $tipoMoneda)
                    ->get();
                $array[$i]->Cobranzas = DB::table('fecha_pago')
                    ->join('ventas', 'fecha_pago.IdVenta', '=', 'ventas.IdVentas')
                    ->select(DB::raw("SUM(fecha_pago.MontoEfectivo) as EfectivoCobranzas"), DB::raw("SUM(fecha_pago.MontoTarjeta) as TarjetaCobranzas"))
                    ->where('fecha_pago.IdCaja', $resultado[$i]->IdCaja)
                    ->where('ventas.IdTipoMoneda', $tipoMoneda)
                    ->get();
                $array[$i]->Ingreso = DB::table('ingresoegreso')
                    ->select(DB::raw("SUM(Monto) as MontoIngreso"))
                    ->where('Tipo', 'I')
                    ->where('IdCaja', $resultado[$i]->IdCaja)
                    ->get();
                $array[$i]->Egreso = DB::table('ingresoegreso')
                    ->select(DB::raw("SUM(Monto) as MontoEgreso"))
                    ->where('Tipo', 'E')
                    ->where('IdCaja', $resultado[$i]->IdCaja)
                    ->get();
                $array[$i]->Amortizacion = DB::table('amortizacion')
                    ->select(DB::raw("SUM(Monto) as MontoAmortizacion"))
                    ->where('FormaPago', 1)
                // ->where('IdTipoMoneda', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('IdCaja', $resultado[$i]->IdCaja)
                    ->get();

                $array[$i]->Inicial = $resultado[$i]->Inicial;
                $array[$i]->Usuario = $resultado[$i]->Usuario;
                $array[$i]->FechaApertura = $resultado[$i]->FechaApertura;
                $array[$i]->FechaCierre = $resultado[$i]->FechaCierre;
                $array[$i]->MontoIngreso = $array[$i]->Ingreso[0]->MontoIngreso;
                $array[$i]->MontoEgreso = $array[$i]->Egreso[0]->MontoEgreso;
                $array[$i]->MontoAmortizacion = $array[$i]->Amortizacion[0]->MontoAmortizacion;
                $array[$i]->EfectivoCobranzas = number_format($array[$i]->Cobranzas[0]->EfectivoCobranzas, 2, '.', ',');
                // suma total de Efectivo por Cajero en soles
                $totalEfectivo = floatval($resultado[$i]->Inicial) + floatval($array[$i][0]->Efectivo) + floatval($array[$i]->Cobranzas[0]->EfectivoCobranzas) + floatval($array[$i]->Ingreso[0]->MontoIngreso) - floatval($array[$i]->Egreso[0]->MontoEgreso) + floatval($array[$i]->Amortizacion[0]->MontoAmortizacion);
                $array[$i]->TotalEfectivo = $totalEfectivo;
                // Fin

                // suma total de Efectivo por Cajero en Dolares
                $totalEfectivoXcajeroDolares = floatval($array[$i][0]->Efectivo) + floatval($array[$i]->Cobranzas[0]->EfectivoCobranzas) + floatval($array[$i]->Amortizacion[0]->MontoAmortizacion);
                $array[$i]->TotalEfectivoXcajeroDolares = $totalEfectivoXcajeroDolares;
                // Fin

                $totalTarjeta = floatval($array[$i][0]->Tarjeta) + floatval($array[$i]->Cobranzas[0]->TarjetaCobranzas);
                $array[$i]->Totaltarjeta = number_format($totalTarjeta, 2, '.', ',');
                $array[$i]->Estado = $resultado[$i]->Estado;
                $array[$i]->fechaCierre = $resultado[$i]->fechaCierreCaja;
            }
            //dd($array[0]->MontoIngreso);
            return $array;

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // finnnn

    public function getMensajeAdmin()
    {
        try {
            $mensaje = DB::table('mensaje')
                ->get();
            return $mensaje;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function setImage($image)
    {
        $getimageName = time() . '.' . $image->getClientOriginalExtension();
        Storage::disk('s3')->put($getimageName, file_get_contents($image), 'public');
        $urlImagen = Storage::disk('s3')->url($getimageName);
        return parse_url($urlImagen, PHP_URL_PATH);
    }

    public function setImageCheckListCarro($image, $extension)
    {
        $getimageName = 'checklist/' . 'C-' . time() . '.' . $extension;
        Storage::disk('s3')->put($getimageName, $image, 'public');
        return Storage::disk('s3')->url($getimageName);
    }

    public function storeImagenFormatoBase64($carpeta, $nombreImagen, $imagenAnomalia, $imagenAnomaliaAnterior, $accion)
    {
        // $getimageName = 'checklist/' . 'C-' . time() . '.' . $extension;
        // Storage::disk('s3')->put($getimageName, $image, 'public');
        // return Storage::disk('s3')->url($getimageName);

        $directorio = "AnomaliasVehiculares/$carpeta";
        // CREAR DIRECTORIO
        if (!Storage::disk('s3')->exists($directorio)) {
            Storage::disk('s3')->makeDirectory($directorio, 'public');
        }
        $imagenBase = $this->procesarImagenBase64($directorio, $nombreImagen, $imagenAnomalia);
        Storage::disk('s3')->put($imagenBase, $decoded_image, 'public');
        $urlAnomalia = Storage::disk('s3')->url($imagenBase);
        return parse_url($urlAnomalia, PHP_URL_PATH);
    }
    private function procesarImagenBase64($directorio, $nombreImagen, $imagenAnomalia)
    {
        $encoded_image = explode(',', $imagenAnomalia)[1];
        $decoded_image = base64_decode($encoded_image);
        $extensionImagenPng = explode('/', explode(':', substr($imagenAnomalia, 0, strpos($imagenAnomalia, ';')))[1])[1];
        $getimageName = $directorio . $nombreImagen . '.' . $extensionImagenPng;
        return $getimageName;
    }

    // public function storePdfWhatsApp($pdf, $rucEmpresa, $idDoc, $serie, $numeroCerosIzquierda)
    // {
    //     $getPdf = 'pdfWhatsApp/' . $rucEmpresa . '-' . $idDoc . '-' . $serie . '-' . $numeroCerosIzquierda . '.pdf';
    //     Storage::disk('s3')->put($getPdf, $pdf->output(), 'public');
    //     return Storage::disk('s3')->url($getPdf);
    // }

    // public function storePdfWhatsAppCheckList($pdf, $serie, $correlativo)
    // {
    //     $getPdf = 'pdfWhatsApp/' . $serie . '-' . $correlativo . '.pdf';
    //     Storage::disk('s3')->put($getPdf, $pdf->output(), 'public');
    //     return Storage::disk('s3')->url($getPdf);
    // }

    // Funcion usada para almacenar los Pdf enviados por whatsApp
    // public function storePdfWhatsAppS3($pdf, $carpeta, $rucEmpresa, $serie, $correlativo)
    // {
    //     if (!Storage::disk('s3')->exists("pdfWhatsApp/$carpeta/$rucEmpresa")) {
    //         Storage::disk('s3')->makeDirectory("pdfWhatsApp/$carpeta/$rucEmpresa", 'public');
    //     }
    //     $nombrePdf = "pdfWhatsApp/$carpeta/$rucEmpresa/" . $serie . '-' . $correlativo . '.pdf';
    //     Storage::disk('s3')->put($nombrePdf, $pdf->output(), 'public');
    //     $urlPdf = Storage::disk('s3')->url($nombrePdf);
    //     return parse_url($urlPdf, PHP_URL_PATH);
    // }

    public function isMobileDevice()
    {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }

    // Funcion usada para alamacenar todas las firmas digitales
    public function storeFirmaDigital($rucEmpresa, $idUsuario, $carpeta, $imagenFirma, $imagenFirmaAnterior, $accion)
    {
        // CREAR DIRECTORIO
        if (!Storage::disk('s3')->exists("FirmasDigitales/$carpeta/$rucEmpresa")) {
            Storage::disk('s3')->makeDirectory("FirmasDigitales/$carpeta/" . $rucEmpresa, 'public');
        }
        // ELIMINAR FIRMA
        if ($accion == 'editar') {
            if (!empty($imagenFirmaAnterior)) {
                if (str_contains($imagenFirmaAnterior, config('variablesGlobales.urlDominioAmazonS3'))) {
                    Storage::disk('s3')->delete(parse_url($imagenFirmaAnterior, PHP_URL_PATH));
                } else {
                    Storage::disk('s3')->delete($imagenFirmaAnterior);
                }
            }
        }
        $ruta = "FirmasDigitales/$carpeta/$rucEmpresa/";
        $nombreFirma = $rucEmpresa . '-' . $idUsuario;

        $encoded_image = explode(',', $imagenFirma)[1];
        $decoded_image = base64_decode($encoded_image);
        $extensionImagenPng = explode('/', explode(':', substr($imagenFirma, 0, strpos($imagenFirma, ';')))[1])[1];

        $getimageName = $ruta . $nombreFirma . '.' . $extensionImagenPng;
        Storage::disk('s3')->put($getimageName, $decoded_image, 'public');
        $urlImagenFirma = Storage::disk('s3')->url($getimageName);
        return parse_url($urlImagenFirma, PHP_URL_PATH);
    }

    public function getDatosUsuarioSuscripcion($idUsuario)
    {
        try {
            $usuario = DB::table('suscripcion')
                ->where('IdUsuario', $idUsuario)
                ->get();
            return $usuario;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getSuscripcionSelect($idSuscripcion)
    {
        try {
            $suscripcion = DB::table('suscripcion')
                ->where('IdSuscripcion', $idSuscripcion)
                ->first();
            return $suscripcion;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getSuscripciones()
    {
        try {
            $usuario = DB::table('suscripcion')
                ->join('usuario', 'suscripcion.IdUsuario', '=', 'usuario.IdUsuario')
                ->select('suscripcion.*', 'usuario.Nombre', 'usuario.CodigoCliente')
                ->where('usuario.Estado', 'E')
                ->get();
            return $usuario;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getUsarioPrincipalSuscripcion($codigoCliente)
    {
        try {
            $usuario = DB::table('usuario')
                ->join('suscripcion', 'suscripcion.IdUsuario', '=', 'usuario.IdUsuario')
                ->select('suscripcion.*', 'usuario.Nombre', 'usuario.CodigoCliente')
                ->where('usuario.CodigoCliente', $codigoCliente)
                ->where('usuario.Estado', 'E')
                ->where('usuario.Cliente', '1')
                ->first();
            return $usuario;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getDatosEmpresa($codigoCliente)
    {
        try {
            $empresa = DB::table('empresa')
                ->join('sucursal', 'empresa.CodigoCliente', '=', 'sucursal.CodigoCliente')
                ->join('distrito', 'empresa.Ubigeo', '=', 'distrito.IdDistrito')
                ->join('provincia', 'distrito.IdProvincia', '=', 'provincia.IdProvincia')
                ->join('departamento', 'provincia.IdDepartamento', '=', 'departamento.IdDepartamento')
                ->leftJoin('planes_suscripcion as ps', 'empresa.IdPlanSuscripcion', '=', 'ps.IdPlanSuscripcion')
                ->select('empresa.*', 'ps.Nombre as NombrePlanSucripcion', 'distrito.Nombre as Distrito', 'provincia.Nombre as Provincia', 'provincia.IdProvincia', 'departamento.Nombre as Departamento', 'departamento.IdDepartamento', 'sucursal.Direccion as DirPrincipal', 'sucursal.CodFiscal as CodigoFiscal')
                ->where('empresa.CodigoCliente', $codigoCliente)
                ->where('sucursal.Principal', 1)
                ->first();
            return $empresa;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getInicioComprobante($idSucursal)
    {
        try {
            $empresa = DB::table('inicio_comprobantes')
                ->select('inicio_comprobantes.*')
                ->where('IdSucursal', $idSucursal)
                ->get();
            return $empresa;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getVentaRapida($codigoCliente)
    {
        try {
            $empresa = DB::table('empresa')
                ->select('empresa.VentaRapida')
                ->where('CodigoCliente', $codigoCliente)
                ->first();
            return $empresa;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function TipoDocumento()
    {
        try {
            $tipoDoc = DB::table('tipo_documento')
                ->get();
            return $tipoDoc;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getSelectTipoDocumento($idTipo)
    {
        try {
            $tipoDoc = DB::table('tipo_documento')
                ->where('idTipoDocumento', $idTipo)
                ->first();
            return $tipoDoc;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getTipoComprobante()
    {
        try {
            $tipoCom = DB::table('tipo_comprobante')
                ->get();
            return $tipoCom;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getTipoMoneda()
    {
        try {
            $tipoMoneda = DB::table('tipo_moneda')
                ->whereNotIn('CodigoMoneda', ['EUR'])
                ->get();
            return $tipoMoneda;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getTipoMonedaSelect($idTipoMoneda)
    {
        try {
            $tipoMoneda = DB::table('tipo_moneda')
                ->where('IdTipoMoneda', $idTipoMoneda)
                ->first();
            return $tipoMoneda;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getDateTime()
    {
        $now = new DateTime();
        return $now->format('Y-m-d H:i:s');
    }

    public function getDepartamentos()
    {
        try {
            $departamento = DB::table('departamento')
                ->get();
            return $departamento;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getProvincias($id)
    {
        try {
            $provincia = DB::table('provincia')
                ->where('IdDepartamento', $id)
                ->get();
            return $provincia;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getDistritos($id)
    {
        try {
            $distrito = DB::table('distrito')
                ->where('IdProvincia', $id)
                ->get();
            return $distrito;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getTiposAtenciones()
    {
        try {
            $atenciones = DB::table('tipo_atencion')
                ->where('Estado', 'E')
                ->get();
            return $atenciones;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getEstadosCotizacion()
    {
        try {
            $atenciones = DB::table('estados_cotizacion')
                ->where('Estado', 'E')
                ->get();
            return $atenciones;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getUsuariosEstados($idCotizacion)
    {
        try {
            $estados = DB::table('registro_estados')
                ->join('usuario', 'registro_estados.IdUsuario', '=', 'usuario.IdUsuario')
                ->select('registro_estados.*', 'usuario.Nombre')
                ->where('IdCotizacion', $idCotizacion)
                ->get();
            return $estados;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getAlmacenes($codigoCliente, $idSucursal)
    {
        try {
            $almacen = DB::table('almacen')
                ->where('CodigoCliente', $codigoCliente)
                ->where('IdSucursal', $idSucursal)
                ->where('Estado', 'E')
                ->get();
            return $almacen;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getAlmacenSelect($id)
    {
        try {
            $almacen = DB::table('almacen')
                ->where('IdAlmacen', $id)
                ->first();
            return $almacen;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getClientes($idSucursal)
    {
        try {
            $clientes = DB::table('cliente')
                ->join('tipo_documento', 'cliente.IdTipoDocumento', '=', 'tipo_documento.IdTipoDocumento')
                ->join('distrito', 'cliente.Ubigeo', '=', 'distrito.IdDistrito')
                ->select('cliente.*', 'tipo_documento.Descripcion', 'distrito.Nombre as Distrito')
                ->where('Estado', 'E')
                ->where('IdSucursal', $idSucursal)
                ->orderBy('IdCliente', 'asc')
                ->get();
            return $clientes;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getClienteSelect($id)
    {
        try {
            $cliente = DB::table('cliente')
                ->join('tipo_documento', 'cliente.IdTipoDocumento', '=', 'tipo_documento.IdTipoDocumento')
                ->join('distrito', 'cliente.Ubigeo', '=', 'distrito.IdDistrito')
                ->join('provincia', 'distrito.IdProvincia', '=', 'provincia.IdProvincia')
                ->join('departamento', 'provincia.IdDepartamento', '=', 'departamento.IdDepartamento')
                ->select('cliente.*', 'distrito.Nombre as Distrito', 'distrito.IdDistrito', 'tipo_documento.CodigoSunat', 'provincia.Nombre as Provincia', 'provincia.IdProvincia', 'departamento.Nombre as  Departamento', 'departamento.IdDepartamento')
                ->where('IdCliente', $id)
                ->first();
            return $cliente;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getTipoClientes($idTipo, $idSucursal)
    {
        try {
            if ($idTipo == 1) {
                $tiposClientes = DB::table('cliente')
                    ->whereIn('IdTipoDocumento', [1, 3, 4])
                    ->where('IdSucursal', $idSucursal)
                    ->where('Estado', 'E')
                    ->get();
            }
            if ($idTipo == 2) {
                $tiposClientes = DB::table('cliente')
                    ->where('IdTipoDocumento', 2)
                    ->where('IdSucursal', $idSucursal)
                    ->where('Estado', 'E')
                    ->get();
            }
            if ($idTipo == 3) {
                $tiposClientes = DB::table('cliente')
                    ->where('IdSucursal', $idSucursal)
                    ->where('Estado', 'E')
                    ->get();
            }
            return $tiposClientes;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getProveedores($idSucursal)
    {
        try {
            $proveedores = DB::table('proveedor')
                ->join('tipo_documento', 'proveedor.IdTipoDocumento', '=', 'tipo_documento.IdTipoDocumento')
                ->select('proveedor.*', 'tipo_documento.Descripcion')
                ->where('Estado', 'E')
                ->where('IdSucursal', $idSucursal)
                ->orderBy('IdProveedor', 'asc')
                ->get();
            return $proveedores;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getProveedorSelect($id)
    {
        try {
            $proveedor = DB::table('proveedor')
                ->join('tipo_documento', 'proveedor.IdTipoDocumento', '=', 'tipo_documento.IdTipoDocumento')
                ->join('distrito', 'proveedor.Ubigeo', '=', 'distrito.IdDistrito')
                ->join('provincia', 'distrito.IdProvincia', '=', 'provincia.IdProvincia')
                ->join('departamento', 'provincia.IdDepartamento', '=', 'departamento.IdDepartamento')
                ->select('proveedor.*', 'distrito.Nombre as Distrito', 'provincia.Nombre as Provincia', 'provincia.IdProvincia', 'departamento.Nombre as  Departamento', 'departamento.IdDepartamento')
                ->where('IdProveedor', $id)
                ->first();
            return $proveedor;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getTipoProveedores($idTipo, $idSucursal)
    {
        try {
            if ($idTipo == 1) {
                $tiposProveedores = DB::table('proveedor')
                    ->where('IdTipoDocumento', 1)
                    ->where('IdSucursal', $idSucursal)
                    ->where('Estado', 'E')
                    ->get();
            }
            if ($idTipo == 2) {
                $tiposProveedores = DB::table('proveedor')
                    ->where('IdTipoDocumento', 2)
                    ->where('IdSucursal', $idSucursal)
                    ->where('Estado', 'E')
                    ->get();
            }
            if ($idTipo == 3) {
                $tiposProveedores = DB::table('proveedor')
                    ->where('IdSucursal', $idSucursal)
                    ->where('Estado', 'E')
                    ->get();
            }
            return $tiposProveedores;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getSeguros($idSucursal)
    {
        try {
            $seguros = DB::table('seguros')
                ->where('IdSucursal', $idSucursal)
                ->where('Estado', 'E')
                ->get();
            return $seguros;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCategorias($codigoCliente)
    {
        try {
            $categorias = DB::table('categoria')
                ->join('sucursal', 'categoria.IdSucursal', '=', 'sucursal.IdSucursal')
                ->select('categoria.*')
                ->where('sucursal.CodigoCliente', $codigoCliente)
                ->where('categoria.Estado', 'E')
                ->orderBy('categoria.Nombre', 'asc')
                ->get();
            return $categorias;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // Nueva funcion traer categoria y marcas por sucursal
    public function getCategoriasSucursal($idSusursal)
    {
        try {
            $categorias = DB::table('categoria')
                ->select('categoria.*')
                ->where('categoria.IdSucursal', $idSusursal)
                ->where('categoria.Estado', 'E')
                ->orderBy('categoria.Nombre', 'asc')
                ->get();
            return $categorias;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getMarcasSucursal($idSucursal)
    {
        try {
            $marcas = DB::table('marca')
                ->join('sucursal', 'marca.IdSucursal', '=', 'sucursal.IdSucursal')
                ->select('marca.*')
                ->where('marca.IdSucursal', $idSucursal)
                ->where('marca.Estado', 'E')
                ->orderBy('marca.Nombre', 'asc')
                ->get();
            return $marcas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    // Fin

    public function getMarcaSelect($id)
    {
        try {
            $marca = DB::table('marca')
                ->where('IdMarca', $id)
                ->first();
            return $marca;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getVehiculoSelect($id)
    {
        try {
            $operarios = DB::table('vehiculo')
                ->join('marca_general', 'vehiculo.IdMarcaVehiculo', '=', 'marca_general.IdMarcaGeneral')
                ->join('modelo_general', 'vehiculo.IdModeloVehiculo', '=', 'modelo_general.IdModeloGeneral')
                ->join('seguros', 'vehiculo.IdSeguro', '=', 'seguros.IdSeguro')
                ->select('vehiculo.*', 'marca_general.NombreMarca', 'modelo_general.NombreModelo', 'seguros.Descripcion as Seguro')
                ->where('IdVehiculo', $id)
                ->first();
            return $operarios;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getOperarios($idSucursal)
    {
        try {
            $operarios = DB::table('operario')
                ->join('rol_operario', 'rol_operario.IdRolOperario', '=', 'operario.IdRolOperario')
                ->select('operario.*', 'rol_operario.Descripcion as Rol')
                ->where('IdSucursal', $idSucursal)
                ->where('operario.Estado', 'E')
                ->orderBy('IdOperario', 'desc')
                ->get();
            return $operarios;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getOperarioSelect($idOperario)
    {
        try {
            $operario = DB::table('operario')
                ->where('IdOperario', $idOperario)
                ->first();
            return $operario;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getPlacas($idSucursal)
    {
        try {
            $placas = DB::table('vehiculo')
                ->where('IdSucursal', $idSucursal)
                ->orderBy('IdVehiculo', 'desc')
                ->get();
            return $placas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getDescripcionCheckIn($grupo, $tipoVehiculo)
    {
        try {
            $operario = DB::table('descripcion_checkin')
                ->where('Grupo', $grupo)
                ->where('TipoVehiculo', $tipoVehiculo)
                ->get();
            return $operario;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getSegurosVehiculares($idSucursal)
    {
        try {
            $seguros = DB::table('seguros')
                ->where('IdSucursal', $idSucursal)
                ->where('Estado', 'E')
                ->get();
            return $seguros;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getSeguroVehicularSelect($idSeguro)
    {
        try {
            $seguros = DB::table('seguros')
                ->join('distrito', 'seguros.Ubigeo', '=', 'distrito.IdDistrito')
                ->join('provincia', 'distrito.IdProvincia', '=', 'provincia.IdProvincia')
                ->join('departamento', 'provincia.IdDepartamento', '=', 'departamento.IdDepartamento')
                ->select('seguros.*', 'distrito.Nombre as Distrito', 'provincia.Nombre as Provincia', 'provincia.IdProvincia', 'departamento.Nombre as  Departamento', 'departamento.IdDepartamento')
                ->where('IdSeguro', $idSeguro)
                ->first();
            return $seguros;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getInventarios($idSucursal)
    {
        try {
            $inventario = DB::table('check_in')
                ->join('cliente', 'check_in.IdCliente', '=', 'cliente.IdCliente')
                ->join('vehiculo', 'vehiculo.PlacaVehiculo', '=', 'check_in.PlacaVehiculo')
                ->select('check_in.*', 'vehiculo.*', 'cliente.RazonSocial', 'cliente.NumeroDocumento')
                ->where('check_in.IdSucursal', $idSucursal)
                ->get();
            return $inventario;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getInventarioSelect($id, $idSucursal)
    {
        try {
            $inventario = DB::table('check_in')
                ->join('cliente', 'check_in.IdCliente', '=', 'cliente.IdCliente')
                ->join('usuario', 'check_in.IdUsuario', '=', 'usuario.IdUsuario')
                ->join('vehiculo', 'check_in.Placa', '=', 'vehiculo.PlacaVehiculo')
                ->join('marca_general', 'vehiculo.IdMarcaVehiculo', '=', 'marca_general.IdMarcaGeneral')
                ->join('modelo_general', 'vehiculo.IdModeloVehiculo', '=', 'modelo_general.IdModeloGeneral')
                ->select('check_in.*', 'vehiculo.*', 'cliente.RazonSocial', 'modelo_general.NombreModelo', 'marca_general.NombreMarca', 'usuario.Nombre', 'cliente.NumeroDocumento', 'cliente.Direccion', 'cliente.Telefono')
                ->where('check_in.IdCheckIn', $id)
                ->where('vehiculo.IdSucursal', $idSucursal)
                ->first();
            return $inventario;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function autorizaciones($idInventario)
    {
        try {
            $inventario = DB::table('autorizacion_checkin')
                ->where('IdCheckIn', $idInventario)
                ->get();
            return $inventario;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function accesoriosExternos($idInventario)
    {
        try {
            $inventario = DB::table('accesorios_externos')
                ->join('descripcion_checkin', 'accesorios_externos.IdDescripcionCheckIn', '=', 'descripcion_checkin.IdDescripcionCheckIn')
                ->select('accesorios_externos.*', 'descripcion_checkin.Descripcion')
                ->where('IdCheckIn', $idInventario)
                ->get();
            return $inventario;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function accesoriosInternos($idInventario)
    {
        try {
            $inventario = DB::table('accesorios_internos')
                ->join('descripcion_checkin', 'accesorios_internos.IdDescripcionCheckIn', '=', 'descripcion_checkin.IdDescripcionCheckIn')
                ->select('accesorios_internos.*', 'descripcion_checkin.Descripcion')
                ->where('IdCheckIn', $idInventario)
                ->get();
            return $inventario;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function herramientas($idInventario)
    {
        try {
            $inventario = DB::table('herramientas')
                ->join('descripcion_checkin', 'herramientas.IdDescripcionCheckIn', '=', 'descripcion_checkin.IdDescripcionCheckIn')
                ->select('herramientas.*', 'descripcion_checkin.Descripcion')
                ->where('IdCheckIn', $idInventario)
                ->get();
            return $inventario;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function documentosVehiculo($idInventario)
    {
        try {
            $inventario = DB::table('documento_vehiculo')
                ->join('descripcion_checkin', 'documento_vehiculo.IdDescripcionCheckIn', '=', 'descripcion_checkin.IdDescripcionCheckIn')
                ->select('documento_vehiculo.*', 'descripcion_checkin.Descripcion')
                ->where('IdCheckIn', $idInventario)
                ->get();
            return $inventario;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getListaGastos($tipo, $idSucursal)
    {
        try {
            $gastos = DB::table('lista_gastos')
                ->where('Tipo', $tipo)
                ->whereIn('lista_gastos.IdSucursal', [0, $idSucursal])
                ->where('Estado', 'E')
                ->get();
            return $gastos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getObtenerGastos($idSucursal, $fecha)
    {
        try {
            $gastos = DB::table('gastos')
                ->Join("lista_gastos", "gastos.IdListaGastos", "=", "lista_gastos.IdListaGastos")
                ->select('*')
                ->where('gastos.IdSucursal', $idSucursal)
                ->where('gastos.FechaCreacion', '>', $fecha)
                ->where('gastos.Estado', 'E')
                ->get();
            return $gastos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getBienesServicios()
    {
        try {
            $bienServicio = DB::table('bienes_servicios')
                ->where('Estado', 'E')
                ->get();
            return $bienServicio;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCodigoBienServicioSelect($id)
    {
        try {
            $bienServicio = DB::table('bienes_servicios')
                ->where('IdBienesServicios', $id)
                ->first();
            return $bienServicio;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getMedioPagos()
    {
        try {
            $medioPago = DB::table('medio_pago')
                ->where('Estado', 'E')
                ->get();
            return $medioPago;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCodigoMedioPagoSelect($id)
    {
        try {
            $medioPago = DB::table('medio_pago')
                ->where('IdMedioPago', $id)
                ->first();
            return $medioPago;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getBuscarMarcasPagination($codigoCliente, $textoBuscar)
    {
        try {
            $marcas = DB::table('marca')
                ->join('sucursal', 'marca.IdSucursal', '=', 'sucursal.IdSucursal')
                ->select('marca.*')
                ->where('sucursal.CodigoCliente', $codigoCliente)
                ->where('marca.Estado', 'E')
                ->where('marca.Nombre', 'like', '%' . $textoBuscar . '%')
                ->orderBy('marca.IdMarca', 'desc')
                ->paginate(12);
            return $marcas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCategoriasPagination($codigoCliente)
    {
        try {
            $categorias = DB::table('categoria')
                ->join('sucursal', 'categoria.IdSucursal', '=', 'sucursal.IdSucursal')
                ->select('categoria.*')
                ->where('sucursal.CodigoCliente', $codigoCliente)
                ->where('categoria.Estado', 'E')
                ->orderBy('categoria.IdCategoria', 'desc')
                ->paginate(12);
            return $categorias;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCategoriaSelect($id)
    {
        try {
            $categoria = DB::table('categoria')
                ->where('IdCategoria', $id)
                ->first();
            return $categoria;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getBuscarCategoriasPagination($codigoCliente, $textoBuscar)
    {
        try {
            $categorias = DB::table('categoria')
                ->join('sucursal', 'categoria.IdSucursal', '=', 'sucursal.IdSucursal')
                ->select('categoria.*')
                ->where('sucursal.CodigoCliente', $codigoCliente)
                ->where('categoria.Estado', 'E')
                ->where('categoria.Nombre', 'like', '%' . $textoBuscar . '%')
                ->orderBy('categoria.IdCategoria', 'desc')
                ->paginate(12);
            return $categorias;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getMarcas($codigoCliente)
    {
        try {
            $categorias = DB::table('marca')
                ->join('sucursal', 'marca.IdSucursal', '=', 'sucursal.IdSucursal')
                ->select('marca.*')
                ->where('sucursal.CodigoCliente', $codigoCliente)
                ->where('marca.Estado', 'E')
            // ->orderBy('marca.IdMarca','desc')
                ->orderBy('marca.Nombre', 'asc')
                ->get();
            return $categorias;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getUnidadMedida()
    {
        try {
            $unidadMedida = DB::table('unidad_medida')
                ->where('Estado', 'E')
                ->get();
            return $unidadMedida;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getRolesOperarios()
    {
        try {
            $roles = DB::table('rol_operario')
                ->where('Estado', 'E')
                ->get();
            return $roles;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /////////////////////// esta funcion fue agregada por martin desde el test3
    public function getUnidadesPorMayor()
    {
        try {
            $unidadMedida = DB::table('unidad_medida')
                ->where('Estado', 'S')
                ->get();
            return $unidadMedida;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getMarcasPagination($codigoCliente)
    {
        try {
            $categorias = DB::table('marca')
                ->join('sucursal', 'marca.IdSucursal', '=', 'sucursal.IdSucursal')
                ->select('marca.*')
                ->where('sucursal.CodigoCliente', $codigoCliente)
                ->where('marca.Estado', 'E')
                ->orderBy('marca.IdMarca', 'desc')
                ->paginate(12);
            return $categorias;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getProductosAlmacen($idAlmacen)
    {
        try {
            $productos = DB::table('almacen_producto')
                ->join('marca', 'almacen_producto.IdMarca', '=', 'marca.IdMarca')
                ->join('articulo', 'almacen_producto.IdArticulo', '=', 'articulo.IdArticulo')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->select('almacen_producto.IdAlmacenProducto as IdArticulo', 'almacen_producto.Descripcion', 'almacen_producto.Codigo', 'almacen_producto.Stock', 'marca.Nombre as Marca', 'almacen_producto.CodigoInterno', 'almacen_producto.IdTipoMoneda', 'articulo.IdUnidadMedida', 'unidad_medida.Nombre as UM')
                ->where('almacen_producto.IdAlmacen', $idAlmacen)
                ->orderBy('IdAlmacenProducto', 'desc')
                ->get();
            return $productos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getProductos($idSucursal)
    {
        try {
            $productos = DB::table('articulo')
                ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->select('articulo.*', 'marca.Nombre as Marca', 'unidad_medida.Nombre as UM')
                ->where('IdTipo', 1)
                ->where('articulo.IdSucursal', $idSucursal)
                ->where('articulo.Estado', 'E')
                ->orderBy('articulo.Descripcion', 'asc')
                ->get();
            return $productos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getAllProductosPagination($idSucursal, $texto)
    {
        try {
            $productos = DB::table('articulo')
                ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->join('categoria', 'articulo.IdCategoria', '=', 'categoria.IdCategoria')
                ->join('sucursal', 'articulo.IdSucursal', '=', 'sucursal.IdSucursal')
                ->select('articulo.*', 'sucursal.Exonerado as valorCheckSucursalFactExonerado', 'marca.Nombre as Marca', 'categoria.Nombre as Categoria', 'unidad_medida.Nombre as UM', DB::raw('IFNULL(articulo.Costo, 0.0) as Costo'), DB::raw('IFNULL(articulo.Precio, 0.0) as Precio'), DB::raw('IFNULL(articulo.Stock, 0) as Stock'))
                ->where('IdTipo', 1)
                ->where('articulo.IdSucursal', $idSucursal)
                ->where('articulo.Descripcion', 'like', '%' . $texto . '%')
                ->where('articulo.Estado', 'E')
                ->orderBy('articulo.Descripcion', 'asc')
                ->paginate(12);
            return $productos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function paginarAjaxProdSucursal($idSucursal, $texto, $tipoMoneda, $idCategoria)
    {
        try {
            if ($idCategoria == 0) {
                $productos = DB::table('articulo')
                    ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                    ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                    ->join('categoria', 'articulo.IdCategoria', '=', 'categoria.IdCategoria')
                    ->join('sucursal', 'articulo.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->select('articulo.*', 'sucursal.Exonerado as valorCheckSucursalFactExonerado', 'marca.Nombre as Marca', 'categoria.Nombre as Categoria', 'unidad_medida.Nombre as UM', DB::raw('IFNULL(articulo.Costo, 0.0) as Costo'), DB::raw('IFNULL(articulo.Precio, 0.0) as Precio'), DB::raw('IFNULL(articulo.Stock, 0) as Stock'))
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Descripcion', 'like', '%' . $texto . '%')
                    ->where('articulo.Estado', 'E')
                    ->orWhere('marca.Nombre', 'like', '%' . $texto . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orderBy('articulo.Descripcion', 'asc')
                    ->paginate(12);
            } else {
                $productos = DB::table('articulo')
                    ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                    ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                    ->join('categoria', 'articulo.IdCategoria', '=', 'categoria.IdCategoria')
                    ->join('sucursal', 'articulo.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->select('articulo.*', 'sucursal.Exonerado as valorCheckSucursalFactExonerado', 'marca.Nombre as Marca', 'categoria.Nombre as Categoria', 'unidad_medida.Nombre as UM', DB::raw('IFNULL(articulo.Costo, 0.0) as Costo'), DB::raw('IFNULL(articulo.Precio, 0.0) as Precio'), DB::raw('IFNULL(articulo.Stock, 0) as Stock'))
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('categoria.IdCategoria', $idCategoria)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Descripcion', 'like', '%' . $texto . '%')
                    ->where('articulo.Estado', 'E')
                    ->orWhere('marca.Nombre', 'like', '%' . $texto . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('categoria.IdCategoria', $idCategoria)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orderBy('articulo.Descripcion', 'asc')
                    ->paginate(12);
            }
            return $productos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getProductosPagination($idSucursal, $textoBuscar, $tipoMoneda, $idCategoria)
    {
        try {
            if ($idCategoria == 0) {
                $productos = DB::table('articulo')
                    ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                    ->join('categoria', 'articulo.IdCategoria', '=', 'categoria.IdCategoria')
                    ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                    ->join('sucursal', 'articulo.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->select('articulo.*', 'sucursal.Exonerado as valorCheckSucursalFactExonerado', 'marca.Nombre as Marca', 'categoria.Nombre as Categoria', 'unidad_medida.Nombre as UM', DB::raw('IFNULL(articulo.Costo, 0.0) as Costo'), DB::raw('IFNULL(articulo.Precio, 0.0) as Precio'), DB::raw('IFNULL(articulo.Stock, 0) as Stock'))
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Descripcion', 'like', '%' . $textoBuscar . '%')
                    ->where('articulo.Estado', 'E')
                    ->orWhere('marca.Nombre', 'like', '%' . $textoBuscar . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orderBy('articulo.Descripcion', 'asc')
                    ->paginate(12);
            } else {
                $productos = DB::table('articulo')
                    ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                    ->join('categoria', 'articulo.IdCategoria', '=', 'categoria.IdCategoria')
                    ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                    ->join('sucursal', 'articulo.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->select('articulo.*', 'sucursal.Exonerado as valorCheckSucursalFactExonerado', 'marca.Nombre as Marca', 'categoria.Nombre as Categoria', 'unidad_medida.Nombre as UM', DB::raw('IFNULL(articulo.Costo, 0.0) as Costo'), DB::raw('IFNULL(articulo.Precio, 0.0) as Precio'), DB::raw('IFNULL(articulo.Stock, 0) as Stock'))
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('categoria.IdCategoria', $idCategoria)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Descripcion', 'like', '%' . $textoBuscar . '%')
                    ->where('articulo.Estado', 'E')
                    ->orWhere('marca.Nombre', 'like', '%' . $textoBuscar . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('categoria.IdCategoria', $idCategoria)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orderBy('articulo.Descripcion', 'asc')
                    ->paginate(12);
            }
            return $productos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getProductosPaginationNoMarca($idSucursal, $textoBuscar, $tipoMoneda, $idCategoria)
    {
        try {
            if ($idCategoria == 0) {
                $productos = DB::table('articulo')
                    ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                    ->join('categoria', 'articulo.IdCategoria', '=', 'categoria.IdCategoria')
                    ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                    ->select('articulo.*', 'marca.Nombre as Marca', 'unidad_medida.Nombre as UM', 'categoria.Nombre as Categoria', DB::raw('IFNULL(articulo.Costo, 0.0) as Costo'), DB::raw('IFNULL(articulo.Precio, 0.0) as Precio'), DB::raw('IFNULL(articulo.Stock, 0) as Stock'))
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Descripcion', 'like', '%' . $textoBuscar . '%')
                    ->where('articulo.Estado', 'E')
                    ->orderBy('articulo.Descripcion', 'asc')
                    ->paginate(12);
            } else {
                $productos = DB::table('articulo')
                    ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                    ->join('categoria', 'articulo.IdCategoria', '=', 'categoria.IdCategoria')
                    ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                    ->select('articulo.*', 'marca.Nombre as Marca', 'unidad_medida.Nombre as UM', 'categoria.Nombre as Categoria', DB::raw('IFNULL(articulo.Costo, 0.0) as Costo'), DB::raw('IFNULL(articulo.Precio, 0.0) as Precio'), DB::raw('IFNULL(articulo.Stock, 0) as Stock'))
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('categoria.IdCategoria', $idCategoria)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Descripcion', 'like', '%' . $textoBuscar . '%')
                    ->where('articulo.Estado', 'E')
                    ->orderBy('articulo.Descripcion', 'asc')
                    ->paginate(12);
            }

            return $productos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function paginarAjaxProdSucursalNoMarca($idSucursal, $texto, $tipoMoneda, $idCategoria)
    {
        try {
            if ($idCategoria == 0) {
                $productos = DB::table('articulo')
                    ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                    ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                    ->join('categoria', 'articulo.IdCategoria', '=', 'categoria.IdCategoria')
                    ->select('articulo.*', 'marca.Nombre as Marca', 'unidad_medida.Nombre as UM', 'categoria.Nombre as Categoria', DB::raw('IFNULL(articulo.Costo, 0.0) as Costo'), DB::raw('IFNULL(articulo.Precio, 0.0) as Precio'), DB::raw('IFNULL(articulo.Stock, 0) as Stock'))
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Descripcion', 'like', '%' . $texto . '%')
                    ->where('articulo.Estado', 'E')
                    ->orderBy('articulo.Descripcion', 'asc')
                    ->paginate(12);
            } else {
                $productos = DB::table('articulo')
                    ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                    ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                    ->join('categoria', 'articulo.IdCategoria', '=', 'categoria.IdCategoria')
                    ->select('articulo.*', 'marca.Nombre as Marca', 'unidad_medida.Nombre as UM', 'categoria.Nombre as Categoria', DB::raw('IFNULL(articulo.Costo, 0.0) as Costo'), DB::raw('IFNULL(articulo.Precio, 0.0) as Precio'), DB::raw('IFNULL(articulo.Stock, 0) as Stock'))
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('categoria.IdCategoria', $idCategoria)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Descripcion', 'like', '%' . $texto . '%')
                    ->where('articulo.Estado', 'E')
                    ->orderBy('articulo.Descripcion', 'asc')
                    ->paginate(12);
            }
            return $productos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getBuscarProductosPagination($textoBuscar, $idSucursal)
    {
        try {
            $productos = DB::table('articulo')
                ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                ->select('articulo.*', 'marca.Nombre as Marca')
                ->where('articulo.Descripcion', 'like', '%' . $textoBuscar . '%')
                ->where('IdTipo', 1)
                ->where('articulo.IdSucursal', $idSucursal)
                ->where('articulo.Estado', 'E')
                ->orWhere('marca.Nombre', 'like', '%' . $textoBuscar . '%')
                ->where('IdTipo', 1)
                ->where('articulo.IdSucursal', $idSucursal)
                ->where('articulo.Estado', 'E')
                ->orderBy('articulo.Descripcion', 'asc')
                ->paginate(12);
            return $productos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getUltimoStock($id)
    {
        try {
            $stock = DB::table('stock')
                ->where('IdArticulo', $id)
                ->orderBy('IdStock', 'desc')
                ->first();
            return $stock;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getProductoUltimoStock($idSucursal)
    {
        try {
            $producto = DB::table('articulo')
                ->where('IdSucursal', $idSucursal)
                ->where('IdTipo', 1)
                ->where('Estado', 'E')
                ->orderBy('IdArticulo', 'desc')
                ->first();
            return $producto;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getProductoCodigoInterno($codigoInterno, $idSucursal)
    {
        $productos = DB::table('articulo')
            ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
            ->select('articulo.*', 'unidad_medida.Nombre as UM')
            ->where('articulo.CodigoInterno', $codigoInterno)
            ->where('articulo.IdSucursal', $idSucursal)
            ->where('articulo.Estado', 'E')
            ->get();
        return $productos;
    }

    public function getProductoStockSelect($id)
    {
        try {
            $stock = DB::table('stock')
                ->where('IdArticulo', $id)
                ->where('Cantidad', '>=', 0)
                ->orderBy('IdStock', 'desc')
                ->limit(2)
                ->get();
            return $stock;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getBuscarCodigoProductoVentas($textoBuscar, $tipoMoneda, $idSucursal)
    {
        try {
            $productos = DB::table('articulo')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->select('articulo.*', 'unidad_medida.Nombre as UM')
                ->where('articulo.Codigo', $textoBuscar)
                ->where('articulo.IdTipoMoneda', $tipoMoneda)
                ->where('articulo.IdSucursal', $idSucursal)
                ->where('articulo.Estado', 'E')
                ->get();
            return $productos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getBuscarProductosVentas($textoBuscar, $tipoMoneda, $idSucursal, $idCategoria)
    {
        try {
            if ($idCategoria == 0) {
                $productos = DB::table('articulo')
                    ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                    ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                    ->join('categoria', 'articulo.IdCategoria', '=', 'categoria.IdCategoria')
                    ->join('sucursal', 'articulo.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->select('articulo.*', 'marca.Nombre as Marca', 'unidad_medida.Nombre as UM', 'categoria.Nombre as Categoria', 'sucursal.Exonerado as valorCheckSucursalFactExonerado')
                    ->where('articulo.Descripcion', 'like', '%' . $textoBuscar . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orWhere('articulo.Codigo', 'like', '%' . $textoBuscar . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orWhere('articulo.Precio', 'like', '%' . $textoBuscar . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orWhere('marca.Nombre', 'like', '%' . $textoBuscar . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orderBy('articulo.Descripcion', 'asc')
                    ->paginate(12);
            } else {
                $productos = DB::table('articulo')
                    ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                    ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                    ->join('categoria', 'articulo.IdCategoria', '=', 'categoria.IdCategoria')
                    ->join('sucursal', 'articulo.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->select('articulo.*', 'marca.Nombre as Marca', 'unidad_medida.Nombre as UM', 'categoria.Nombre as Categoria', 'sucursal.Exonerado as valorCheckSucursalFactExonerado')
                    ->where('articulo.Descripcion', 'like', '%' . $textoBuscar . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('categoria.IdCategoria', $idCategoria)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orWhere('articulo.Codigo', 'like', '%' . $textoBuscar . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('categoria.IdCategoria', $idCategoria)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orWhere('articulo.Precio', 'like', '%' . $textoBuscar . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('categoria.IdCategoria', $idCategoria)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orWhere('marca.Nombre', 'like', '%' . $textoBuscar . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('categoria.IdCategoria', $idCategoria)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orderBy('articulo.Descripcion', 'asc')
                    ->paginate(12);
            }

            return $productos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getBuscarProdNoMarcas($textoBuscar, $tipoMoneda, $idSucursal, $idCategoria)
    {
        try {
            if ($idCategoria == 0) {
                $productos = DB::table('articulo')
                    ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                    ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                    ->join('categoria', 'articulo.IdCategoria', '=', 'categoria.IdCategoria')
                    ->select('articulo.*', 'marca.Nombre as Marca', 'unidad_medida.Nombre as UM', 'categoria.Nombre as Categoria')
                    ->where('articulo.Descripcion', 'like', '%' . $textoBuscar . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orWhere('articulo.Codigo', 'like', '%' . $textoBuscar . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orWhere('articulo.Precio', 'like', '%' . $textoBuscar . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orWhere('marca.Nombre', 'like', '%' . $textoBuscar . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orderBy('articulo.Descripcion', 'asc')
                    ->paginate(12);

            } else {
                $productos = DB::table('articulo')
                    ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                    ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                    ->join('categoria', 'articulo.IdCategoria', '=', 'categoria.IdCategoria')
                    ->select('articulo.*', 'marca.Nombre as Marca', 'unidad_medida.Nombre as UM', 'categoria.Nombre as Categoria')
                    ->where('articulo.Descripcion', 'like', '%' . $textoBuscar . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('categoria.IdCategoria', $idCategoria)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orWhere('articulo.Codigo', 'like', '%' . $textoBuscar . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('categoria.IdCategoria', $idCategoria)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orWhere('articulo.Precio', 'like', '%' . $textoBuscar . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('categoria.IdCategoria', $idCategoria)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orWhere('marca.Nombre', 'like', '%' . $textoBuscar . '%')
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('categoria.IdCategoria', $idCategoria)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orderBy('articulo.Descripcion', 'asc')
                    ->paginate(12);
            }
            return $productos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getProductoSelect($id)
    {
        try {
            $producto = DB::table('articulo')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->select('articulo.*', 'unidad_medida.Descripcion as MedidaSunat', 'unidad_medida.Nombre as TextUnidad')
                ->where('IdArticulo', $id)
                ->first();
            return $producto;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getServicios($idSucursal)
    {
        try {
            $servicios = DB::table('articulo')
                ->where('IdTipo', 2)
                ->where('Estado', 'E')
                ->where('IdSucursal', $idSucursal)
                ->orderBy('articulo.Descripcion', 'asc')
                ->get();
            return $servicios;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function paginarAjaxServSucursal($idSucursal, $tipoMoneda, $textoBuscar)
    {
        try {
            $servicios = DB::table('articulo')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->join('sucursal', 'articulo.IdSucursal', '=', 'sucursal.IdSucursal')
                ->select('articulo.*', 'unidad_medida.Nombre as UM', 'sucursal.Exonerado as valorCheckSucursalFactExonerado')
                ->where('articulo.Descripcion', 'like', '%' . $textoBuscar . '%')
                ->where('IdTipo', 2)
                ->where('IdTipoMoneda', $tipoMoneda)
                ->where('articulo.IdSucursal', $idSucursal)
                ->where('articulo.Estado', 'E')
                ->orderBy('articulo.Descripcion', 'asc')
                ->paginate(12);
            return $servicios;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getServiciosPagination($idSucursal, $tipoMoneda, $textoBuscar)
    {
        try {
            $servicios = DB::table('articulo')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->join('sucursal', 'articulo.IdSucursal', '=', 'sucursal.IdSucursal')
                ->select('articulo.*', 'unidad_medida.Nombre as UM', 'sucursal.Exonerado as valorCheckSucursalFactExonerado')
                ->where('articulo.Descripcion', 'like', '%' . $textoBuscar . '%')
                ->where('articulo.IdTipo', 2)
                ->where('articulo.IdTipoMoneda', $tipoMoneda)
                ->where('articulo.IdSucursal', $idSucursal)
                ->where('articulo.Estado', 'E')
                ->orderBy('articulo.Descripcion', 'asc')
                ->paginate(12);
            return $servicios;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    public function getBuscarServiciosPagination($textoBuscar, $tipoMoneda, $idSucursal)
    {
        try {
            $productos = DB::table('articulo')
                ->join('sucursal', 'articulo.IdSucursal', '=', 'sucursal.IdSucursal')
                ->select('articulo.*', 'sucursal.Exonerado as valorCheckSucursalFactExonerado')
                ->where('articulo.IdTipo', 2)
                ->where('articulo.Estado', 'E')
                ->where('articulo.IdSucursal', $idSucursal)
                ->where('articulo.IdTipoMoneda', $tipoMoneda)
                ->where('articulo.Descripcion', 'like', '%' . $textoBuscar . '%')
                ->orderBy('articulo.Descripcion', 'asc')
                ->paginate(12);
            return $productos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // public function getBuscarServiciosPagination($textoBuscar, $tipoMoneda, $idSucursal)
    // {
    //     try {
    //         $productos = DB::table('articulo')
    //             ->where('IdTipo', 2)
    //             ->where('Estado', 'E')
    //             ->where('IdSucursal', $idSucursal)
    //             ->where('IdTipoMoneda', $tipoMoneda)
    //             ->where('Descripcion', 'like', '%' . $textoBuscar . '%')
    //             ->orderBy('articulo.Descripcion', 'asc')
    //             ->paginate(6);
    //         return $productos;
    //     } catch (Exception $ex) {
    //         echo $ex->getMessage();
    //     }
    // }

    public function getBuscarServiciosVentas($textoBuscar, $tipoMoneda, $idSucursal)
    {
        try {
            $productos = DB::table('articulo')
                ->where('Descripcion', 'like', '%' . $textoBuscar . '%')
                ->where('IdTipo', 2)
                ->where('IdTipoMoneda', $tipoMoneda)
                ->where('Estado', 'E')
                ->where('IdSucursal', $idSucursal)
                ->orWhere('Precio', 'like', '%' . $textoBuscar . '%')
                ->where('IdTipo', 2)
                ->where('IdTipoMoneda', $tipoMoneda)
                ->where('Estado', 'E')
                ->where('IdSucursal', $idSucursal)
                ->orWhere('Codigo', 'like', '%' . $textoBuscar . '%')
                ->where('IdTipo', 2)
                ->where('IdTipoMoneda', $tipoMoneda)
                ->where('Estado', 'E')
                ->where('IdSucursal', $idSucursal)
                ->orderBy('articulo.Descripcion', 'asc')
                ->paginate(12);
            return $productos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getPrecios($idSucursal, $tipo)
    {
        try {
            if ($tipo == 1) {
                $productos = DB::table('articulo')
                    ->join('tipo', 'articulo.IdTipo', '=', 'tipo.IdTipo')
                    ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                    ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                    ->select('articulo.*', 'tipo.Descripcion as Tipo', 'marca.Nombre as Marca', 'unidad_medida.Nombre as NombreUnidadMedida')
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.IdTipo', $tipo)
                    ->where('articulo.Estado', 'E')
                    ->orderBy('articulo.Descripcion', 'asc')
                    ->get();
            } else {
                $productos = DB::table('articulo')
                    ->join('tipo', 'articulo.IdTipo', '=', 'tipo.IdTipo')
                    ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                    ->join('sucursal', 'articulo.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->select('articulo.*', 'tipo.Descripcion as Tipo', 'sucursal.Nombre', 'unidad_medida.Nombre as NombreUnidadMedida')
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.IdTipo', $tipo)
                    ->where('articulo.Estado', 'E')
                    ->orderBy('articulo.Descripcion', 'asc')
                    ->get();
            }
            return $productos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getSucursales($codigoCliente, $idOperador)
    {
        try {
            if ($idOperador == 1) {
                $sucursales = DB::table('sucursal')
                    ->select('sucursal.*')
                    ->whereIn('sucursal.Estado', ['E', 'Suscripcion Caducada'])
                    ->orderBy('sucursal.IdSucursal', 'desc')
                    ->get();
            } else {
                $sucursales = DB::table('sucursal')
                    ->select('sucursal.*')
                    ->where('sucursal.Estado', 'E')
                    ->where('sucursal.CodigoCliente', $codigoCliente)
                    ->orderBy('sucursal.IdSucursal', 'desc')
                    ->get();
            }
            return $sucursales;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getSucursalesFiltrado($tipo)
    {
        if ($tipo == 1) {
            $sucursales = DB::table('sucursal')
                ->join('usuario', 'sucursal.CodigoCliente', '=', 'usuario.CodigoCliente')
                ->select('sucursal.*', 'usuario.Nombre as NombreUsuario')
                ->where('sucursal.Estado', 'E')
                ->where('sucursal.Principal', 1)
                ->where('usuario.Cliente', 1)
                ->orderBy('sucursal.IdSucursal', 'desc')
                ->get();
        } else {
            $sucursales = DB::table('sucursal')
                ->join('usuario', 'sucursal.CodigoCliente', '=', 'usuario.CodigoCliente')
                ->select('sucursal.*', 'usuario.Nombre as NombreUsuario')
                ->where('sucursal.Estado', 'E')
                ->where('usuario.Cliente', 1)
                ->orderBy('sucursal.IdSucursal', 'desc')
                ->get();
        }
        return $sucursales;
    }

    public function getSucursalesRestantes($idSucursal, $codigoCliente)
    {
        try {
            $sucursales = DB::table('sucursal')
                ->select('sucursal.*')
                ->where('sucursal.Estado', 'E')
                ->where('sucursal.CodigoCliente', $codigoCliente)
                ->whereNotIn('sucursal.IdSucursal', [$idSucursal])
                ->get();
            return $sucursales;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getSucursalSelect($id)
    {
        try {
            $sucursal = DB::table('sucursal')
                ->join('distrito', 'sucursal.Ubigeo', '=', 'distrito.IdDistrito')
                ->join('provincia', 'distrito.IdProvincia', '=', 'provincia.IdProvincia')
                ->join('departamento', 'provincia.IdDepartamento', '=', 'departamento.IdDepartamento')
                ->join('sucursal as  s2', 'sucursal.CodigoCliente', '=', 's2.CodigoCliente')
                ->select('sucursal.*', 's2.Direccion as DirPrin', 'distrito.Nombre as Distrito', 'distrito.IdDistrito', 'provincia.Nombre as Provincia', 'provincia.IdProvincia', 'departamento.Nombre as  Departamento', 'departamento.IdDepartamento')
                ->where('sucursal.IdSucursal', $id)
                ->where('s2.Orden', 1)
                ->first();
            return $sucursal;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getRoles()
    {
        try {
            $operador = DB::table('operador')
                ->where('Estado', 'E')
                ->where('IdOperador', '>', 1)
                ->get();
            return $operador;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getOperadorSelect($id)
    {
        try {
            $operador = DB::table('operador')
                ->where('IdOperador', $id)
                ->first();
            return $operador;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getVerificarUsuario($login)
    {
        try {
            $usuario = DB::table('usuario')
                ->select(DB::raw("count(*) as usuarioTotal"))
                ->where('Login', $login)
                ->first();
            return $usuario;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getUsuariosClientes()
    {
        try {
            $usuarios = DB::table('usuario')
                ->select('Email', 'IdUsuario')
                ->whereIn('usuario.Estado', ['E', 'Suscripcion Caducada'])
                ->where('usuario.Cliente', 1)
                ->orderBy('usuario.IdUsuario', 'desc')
                ->get();
            return $usuarios;
        } catch (\Throwable $th) {
            echo $ex->getMessage();
        }
    }

    public function getUsuarios($idOperador, $codigoCliente)
    {
        try {
            if ($idOperador == 1) {
                // $usuarios = DB::table('usuario')
                //     ->join('operador', 'usuario.IdOperador', '=', 'operador.IdOperador')
                //     ->join('sucursal', 'usuario.IdSucursal', '=', 'sucursal.IdSucursal')
                //     ->select('usuario.*', 'operador.Rol as Rol', 'sucursal.Nombre as Sucursal')
                //     ->whereIn('usuario.Estado', ['E', 'D', 'Suscripcion Caducada'])
                //     ->where('usuario.Cliente', 1)
                //     ->orderBy('usuario.IdUsuario', 'desc')
                //     ->get();
                $usuarios = DB::table('usuario')
                    ->join('operador', 'usuario.IdOperador', '=', 'operador.IdOperador')
                    ->join('sucursal', 'usuario.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->leftJoin('suscripcion', 'usuario.IdUsuario', '=', 'suscripcion.IdUsuario')
                    ->select('usuario.*', 'operador.Rol as Rol', 'sucursal.Nombre as Sucursal', 'suscripcion.FechaFinalCDT')
                    ->whereIn('usuario.Estado', ['E', 'D', 'Suscripcion Caducada'])
                    ->where('usuario.Cliente', 1)
                    ->groupBy('usuario.IdUsuario')
                    ->orderBy('usuario.IdUsuario', 'desc')
                    ->get();

            } else {
                $usuarios = DB::table('usuario')
                    ->join('operador', 'usuario.IdOperador', '=', 'operador.IdOperador')
                    ->join('sucursal', 'usuario.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->select('usuario.*', 'operador.Rol as Rol', 'sucursal.Nombre as Sucursal')
                    ->whereIn('usuario.Estado', ['E', 'D', 'Suscripcion Caducada'])
                    ->where('usuario.Cliente', 0)
                    ->where('usuario.CodigoCliente', $codigoCliente)
                    ->orderBy('usuario.IdUsuario', 'desc')
                    ->get();
            }
            return $usuarios;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getUsuarioSelect($id)
    {
        try {
            $usuario = DB::table('usuario')
                ->join('operador', 'usuario.IdOperador', '=', 'operador.IdOperador')
                ->join('sucursal', 'usuario.IdSucursal', '=', 'sucursal.IdSucursal')
                ->select('usuario.*', 'operador.Rol as Rol', 'operador.IdOperador', 'sucursal.Nombre as Sucursal', 'sucursal.Estado as EstadoSucursal')
                ->where('IdUsuario', $id)
                ->first();
            return $usuario;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getArticulosCantidad($codCliente, $tipo, $sucursal)
    {
        try {
            if ($tipo == 1) {
                $articulos = DB::table('articulo')
                    ->join('usuario', 'usuario.IdUsuario', '=', 'articulo.IdCreacion')
                    ->join('stock', 'articulo.IdArticulo', '=', 'stock.IdArticulo')
                    ->select('articulo.IdArticulo', 'articulo.Descripcion', 'articulo.Stock', 'articulo.Costo', 'articulo.Precio', DB::raw('SUM(stock.Cantidad) as SumaTotal'))
                    ->where('usuario.CodigoCliente', $codCliente)
                    ->where('articulo.IdTipo', 1)
                    ->where('articulo.IdSucursal', $sucursal)
                    ->where('articulo.Estado', 'E')
                    ->groupBy(DB::raw("stock.IdArticulo"))
                    ->get();
            } else {
                $articulos = DB::table('articulo')
                    ->join('usuario', 'usuario.IdUsuario', '=', 'articulo.IdCreacion')
                    ->join('stock', 'articulo.IdArticulo', '=', 'stock.IdArticulo')
                    ->select('articulo.IdArticulo', 'articulo.Descripcion', 'articulo.Stock', 'articulo.Costo', 'articulo.Precio', DB::raw('SUM(stock.Cantidad) as SumaTotal'))
                    ->where('articulo.Stock', '>', 0)
                    ->where('articulo.IdTipo', 1)
                    ->where('articulo.IdSucursal', $sucursal)
                    ->where('usuario.CodigoCliente', $codCliente)
                    ->where('articulo.Estado', 'E')
                    ->groupBy(DB::raw("stock.IdArticulo"))
                    ->get();
            }

            return $articulos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function verificarStock($codCliente)
    {
        try {

            /*$stock = DB::table('stock')
            ->select('stock.*', DB::raw('SUM(stock.Cantidad) as SumaTotal'))
            ->where('stock.IdArticulo', $idArticulo)
            ->groupBy(DB::raw("stock.IdArticulo"))
            ->get();*/
            $stock = DB::table('stock')
                ->join('articulo', 'articulo.IdArticulo', '=', 'stock.IdArticulo')
                ->join('usuario', 'usuario.IdUsuario', '=', 'articulo.IdCreacion')
                ->select('stock.*', DB::raw('SUM(stock.Cantidad) as SumaTotal'))
                ->where('articulo.IdTipo', 1)
                ->where('usuario.CodigoCliente', $codCliente)
                ->where('articulo.Estado', 'E')
                ->groupBy(DB::raw("stock.IdArticulo"))
                ->get();

            return $stock;
        } catch (Exception $ex) {
            return [];
        }
    }

    public function getVentasElectronicas($idSucursal, $tipoPago, $fecha, $fechaIni, $fechaFin)
    {
        try {
            $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);

            $ventas = DB::select("(SELECT v.FechaCreacion, v.Serie, v.Numero, v.IdTipoPago, v.TipoVenta, v.TipoNota as TipoNota, v.Total, v.Estado, c.RazonSocial, c.Nombre as Nombres, s.Nombre as Sucursal, u.Nombre as Usuario, tc.Descripcion as Descripcion
            FROM
            ventas v inner join cliente c ON v.IdCliente = c.IdCliente inner join sucursal s ON v.IdSucursal = s.IdSucursal inner join tipo_comprobante tc ON v.IdTipoComprobante = tc.IdTipoComprobante inner join usuario u ON v.IdCreacion = u.IdUsuario WHERE v.IdSucursal= ? AND v.FechaCreacion BETWEEN ? AND ?)
            union ALL
            (SELECT ncd.FechaCreacion, ncd.Serie, ncd.Numero, ve.IdTipoPago, ncd.TipoVenta, '' as TipoNota, ncd.Total, ncd.Estado, c.RazonSocial, c.Nombre as Nombres, s.Nombre as Sucursal, u.Nombre as Usuario, tn.Descripcion as Descripcion
            FROM
            nota_credito_debito ncd inner join cliente c ON ncd.IdCliente = c.IdCliente inner join sucursal s ON ncd.IdSucursal = s.IdSucursal inner join tipo_nota tn ON ncd.IdTipoNota = tn.IdTipoNota inner join usuario u ON ncd.IdUsuarioCreacion = u.IdUsuario inner join ventas ve ON ncd.IdVentas = ve.IdVentas WHERE ncd.IdSucursal = ? AND ncd.FechaCreacion BETWEEN ? AND ?) order by FechaCreacion desc",
                [$idSucursal, $fechas[0], $fechas[1], $idSucursal, $fechas[0], $fechas[1]]);

            $facturasVentas = collect($ventas);
            return $facturasVentas;

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getVentasAll($idSucursal)
    {
        try {
            $ventas = DB::table('ventas')
                ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                ->join('tipo_comprobante', 'tipo_comprobante.IdTipoComprobante', '=', 'ventas.IdTipoComprobante')
                ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                ->select('ventas.*', 'cliente.RazonSocial as RazonSocial', 'cliente.Nombre as Nombres', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario', 'tipo_comprobante.Descripcion')
                ->where('ventas.IdSucursal', $idSucursal)
                ->whereRaw('YEAR(ventas.FechaCreacion) = YEAR(NOW())')
                ->whereRaw('MONTH(ventas.FechaCreacion) = MONTH(NOW())')
                ->orderBy('ventas.FechaCreacion', 'desc')
                ->get();
            return $ventas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getVentasAllFiltrado($idSucursal, $tipoPago, $fecha, $fechaIni, $fechaFin)
    {
        try {
            $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            if ($tipoPago == 0) {
                $ventas = DB::table('ventas')
                    ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                    ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->join('tipo_comprobante', 'tipo_comprobante.IdTipoComprobante', '=', 'ventas.IdTipoComprobante')
                    ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                    ->select('ventas.*', 'cliente.RazonSocial as RazonSocial', 'cliente.Nombre as Nombres', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario', 'tipo_comprobante.Descripcion')
                    ->where('ventas.IdSucursal', $idSucursal)
                    ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->orderBy('ventas.FechaCreacion', 'desc')
                    ->get();
                return $ventas;
            } else {
                $ventas = DB::table('ventas')
                    ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                    ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->join('tipo_comprobante', 'tipo_comprobante.IdTipoComprobante', '=', 'ventas.IdTipoComprobante')
                    ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                    ->select('ventas.*', 'cliente.RazonSocial as RazonSocial', 'cliente.Nombre as Nombres', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario', 'tipo_comprobante.Descripcion')
                    ->where('ventas.IdSucursal', $idSucursal)
                    ->where('ventas.IdTipoPago', $tipoPago)
                    ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->orderBy('ventas.FechaCreacion', 'desc')
                    ->get();
                return $ventas;
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getVentasVendedores($idSucursal)
    {
        try {
            $ventas = DB::table('ventas')
                ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                ->select('ventas.*', 'cliente.Nombre as Nombres', 'usuario.Nombre as Usuario')
                ->where('ventas.IdSucursal', $idSucursal)
                ->orderBy('ventas.FechaCreacion', 'desc')
                ->get();
            return $ventas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getFinDeDia($idSucursal)
    {
        try {
            $ventas = DB::table('ventas')
                ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                ->join('ventas_articulo', 'ventas.IdVentas', '=', 'ventas_articulo.IdVentas')
                ->select('ventas.*', 'cliente.Nombre as Nombres', DB::raw("SUM(ventas_articulo.Importe) as Total"), DB::raw("SUM(ventas_articulo.Descuento) as TotalDescuento"), 'usuario.Nombre as Usuario')
                ->where('ventas.IdSucursal', $idSucursal)
                ->groupBy(DB::raw("usuario.IdUsuario"), DB::raw("ventas.IdTipoPago"))
                ->orderBy('ventas.FechaCreacion', 'desc')
                ->get();
            return $ventas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getVentas($idSucursal, $idUsuario, $IdTipoComp)
    {
        try {
            $ventas = DB::table('ventas')
                ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                ->where('ventas.IdSucursal', $idSucursal)
                ->where('ventas.IdCreacion', $idUsuario)
                ->where('ventas.IdTipoComprobante', $IdTipoComp)
                ->orderBy('ventas.Numero', 'desc')
                ->get();
            return $ventas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getVentaselect($idVenta)
    {
        try {
            $venta = DB::table('ventas')
                ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                ->join('tipo_documento', 'cliente.IdTipoDocumento', '=', 'tipo_documento.IdTipoDocumento')
                ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                ->join('tipo_comprobante', 'tipo_comprobante.IdTipoComprobante', '=', 'ventas.IdTipoComprobante')
                ->join('tipo_moneda', 'ventas.IdTipoMoneda', '=', 'tipo_moneda.IdTipoMoneda')
                ->join('distrito', 'cliente.Ubigeo', '=', 'distrito.IdDistrito')
                ->join('provincia', 'distrito.IdProvincia', '=', 'provincia.IdProvincia')
                ->join('departamento', 'provincia.IdDepartamento', '=', 'departamento.IdDepartamento')
                ->select('ventas.*', 'cliente.IdTipoDocumento', 'cliente.Nombre as Nombres', 'cliente.RazonSocial', 'cliente.Direccion as DirCliente', 'cliente.NumeroDocumento', 'cliente.Ubigeo', 'cliente.Email', 'cliente.Telefono as TelfCliente', 'tipo_comprobante.Descripcion as TipoComp', 'distrito.IdDistrito', 'provincia.IdProvincia', 'departamento.IdDepartamento', 'sucursal.Direccion as Local', 'sucursal.Nombre as Sucursal', 'sucursal.Ciudad', 'sucursal.Telefono as TelfSucursal', 'sucursal.Principal', 'usuario.Nombre as Usuario', 'tipo_documento.Descripcion as TipoDoc', 'tipo_moneda.Nombre as Moneda', 'ventas.Serie as nuevo')
                ->where('ventas.IdVentas', $idVenta)
                ->first();
            return $venta;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getItemsVentas($idVenta)
    {
        try {
            $ventas = DB::table('ventas_articulo')
                ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->select('ventas_articulo.*', 'ventas_articulo.Codigo as Cod', 'articulo.*', 'unidad_medida.Nombre as UniMedida', 'unidad_medida.Descripcion as CodSunatMedida')
                ->where('ventas_articulo.IdVentas', $idVenta)
                ->get();
            return $ventas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getItemsVentasNuevo($idVenta)
    {
        try {
            $ventas = DB::table('ventas_articulo')
                ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->select('ventas_articulo.*', 'ventas_articulo.Codigo as Cod', 'articulo.*', 'unidad_medida.Nombre as UniMedida', 'unidad_medida.Descripcion as CodSunatMedida')
                ->where('ventas_articulo.IdVentas', $idVenta)
                ->get();

            for ($i = 0; $i < count($ventas); $i++) {
                if ($ventas[$i]->IdTipo == 1) {
                    $ventas[$i]->NombreMarca = DB::table('marca')
                        ->join('articulo', 'marca.IdMarca', '=', 'articulo.IdMarca')
                        ->select('marca.Nombre as nombreMarca')
                        ->where('IdArticulo', $ventas[$i]->IdArticulo)
                        ->first();
                } else {
                    $ventas[$i]->NombreMarca = "";
                }
            }
            return $ventas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getItemsVentas2($idVenta)
    {
        try {
            $ventas = DB::table('ventas_articulo')
                ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->select('ventas_articulo.*', 'ventas_articulo.Codigo as Cod', 'articulo.*', 'unidad_medida.Nombre as UniMedida', 'unidad_medida.Descripcion as CodSunatMedida', 'articulo.Ubicacion', 'marca.Nombre as nombreMarca', 'articulo.codigo AS codigoBarra')
                ->where('ventas_articulo.IdVentas', $idVenta)
                ->get();
            return $ventas;
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

    public function getAmortizacionesTotales($idSucursal, $idUsuario, $fechaHoy, $tipoMoneda)
    {
        try {
            $amortizaciones = DB::table('amortizacion')
                ->where('IdSucursal', $idSucursal)
                ->where('IdUsuario', $idUsuario)
                ->where('IdTipoMoneda', $tipoMoneda)
                ->where('FechaIngreso', '>', $fechaHoy)
                ->get();
            return $amortizaciones;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getAmortizaciones($idSucursal, $idUsuario, $fechaInicial, $fechaFinal)
    {
        try {
            $amortizaciones = DB::table('amortizacion')
                ->where('IdSucursal', $idSucursal)
                ->where('IdUsuario', $idUsuario)
                ->whereBetween('FechaIngreso', [$fechaInicial, $fechaFinal])
                ->get();
            return $amortizaciones;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCajaSelect($id)
    {
        $caja = DB::table('caja')
            ->select('caja.*')
            ->where('IdCaja', '=', $id)
            ->first();
        return $caja;
    }

    public function getVentasAperturaCierreCaja($idSucursal, $idUsuario, $fecha)
    {
        try {
            /*$resultado = DB::table('ventas_articulo')
            ->join('ventas','ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
            ->join('usuario','ventas.IdCreacion', '=', 'usuario.IdUsuario')
            ->join('cliente','ventas.IdCliente', '=', 'cliente.IdCliente')
            ->join('articulo','ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
            ->select('ventas.*','ventas_articulo.IdArticulo as IdArticulo', 'articulo.Descripcion', 'usuario.Nombre as Usuario', 'cliente.Nombre as Nombres')
            ->where('articulo.IdSucursal',$idSucursal)
            ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
            ->groupBy(DB::raw("ventas.IdVentas"))
            ->get();
            return $resultado;*/

            $ventas = DB::table('ventas')
                ->select('ventas.*')
                ->where('ventas.FechaCreacion', '>=', $fecha)
                ->where('ventas.IdSucursal', '=', $idSucursal)
                ->where('ventas.IdCreacion', '=', $idUsuario)
                ->get();
            return $ventas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getComprasAll($idSucursal)
    {
        try {
            $compras = DB::table('compras')
                ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
                ->join('sucursal', 'compras.IdSucursal', '=', 'sucursal.IdSucursal')
                ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
                ->select('compras.*', 'proveedor.Nombre as Nombres', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario')
                ->where('compras.IdSucursal', $idSucursal)
                ->whereRaw('YEAR(compras.FechaCreacion)=YEAR(NOW())')
                ->whereRaw('MONTH(compras.FechaCreacion) = MONTH(NOW())')
                ->orderBy('IdCompras', 'desc')
                ->get();
            return $compras;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getComprasAllFiltrado($idSucursal, $tipoPago, $fecha, $fechaIni, $fechaFin)
    {
        try {
            $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            if ($tipoPago == 0) {
                $compras = DB::table('compras')
                    ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
                    ->join('sucursal', 'compras.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
                    ->select('compras.*', 'proveedor.Nombre as Nombres', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario')
                    ->where('compras.IdSucursal', $idSucursal)
                    ->whereBetween('compras.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->orderBy('IdCompras', 'desc')
                    ->get();
                return $compras;
            } else {
                $compras = DB::table('compras')
                    ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
                    ->join('sucursal', 'compras.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
                    ->select('compras.*', 'proveedor.Nombre as Nombres', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario')
                    ->where('compras.IdSucursal', $idSucursal)
                    ->where('compras.IdTipoPago', $tipoPago)
                    ->whereBetween('compras.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->orderBy('IdCompras', 'desc')
                    ->get();
                return $compras;
            }

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCompras($idSucursal, $IdTipoComp)
    {
        try {
            $compras = DB::table('compras')
                ->join('sucursal', 'compras.IdSucursal', '=', 'sucursal.IdSucursal')
                ->where('compras.IdSucursal', $idSucursal)
                ->where('compras.IdTipoComprobante', $IdTipoComp)
                ->orderBy('IdCompras', 'desc')
                ->get();
            return $compras;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCompraselect($idCompra)
    {
        try {
            $compra = DB::table('compras')
                ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
                ->join('tipo_documento', 'proveedor.IdTipoDocumento', '=', 'tipo_documento.IdTipoDocumento')
                ->join('sucursal', 'compras.IdSucursal', '=', 'sucursal.IdSucursal')
                ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
                ->join('tipo_comprobante', 'tipo_comprobante.IdTipoComprobante', '=', 'compras.IdTipoComprobante')
                ->join('tipo_moneda', 'compras.IdTipoMoneda', '=', 'tipo_moneda.IdTipoMoneda')
                ->select('compras.*', 'proveedor.Nombre as Nombres', 'proveedor.RazonSocial', 'proveedor.Direccion as DirProveedor', 'proveedor.NumeroDocumento', 'proveedor.Email', 'proveedor.Telefono as TelfProveedor', 'tipo_comprobante.Descripcion as TipoComp', 'proveedor.PersonaContacto',
                    'sucursal.Direccion as Local', 'sucursal.Nombre as Sucursal', 'sucursal.Ciudad', 'sucursal.Telefono as TelfSucursal', 'usuario.Nombre as Usuario', 'usuario.DNI as DNI', 'tipo_documento.Descripcion as TipoDoc', 'tipo_moneda.Nombre as Moneda')
                ->where('compras.IdCompras', $idCompra)
                ->first();
            return $compra;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getItemsCompras($idCompra)
    {
        try {
            $compras = DB::table('compras_articulo')
                ->join('articulo', 'compras_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->select('compras_articulo.*', 'compras_articulo.Codigo as Cod', 'articulo.*', 'unidad_medida.Nombre as UniMedida')
                ->where('compras_articulo.IdCompras', $idCompra)
                ->get();

            for ($i = 0; $i < count($compras); $i++) {
                if ($compras[$i]->IdTipo == 1) {
                    $compras[$i]->NombreMarca = DB::table('marca')
                        ->join('articulo', 'marca.IdMarca', '=', 'articulo.IdMarca')
                        ->select('marca.Nombre as nombreMarca')
                        ->where('IdArticulo', $compras[$i]->IdArticulo)
                        ->first();
                } else {
                    $compras[$i]->NombreMarca = "";
                }
            }
            return $compras;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getUsuariosPermisos($usuarioSelect, $estado)
    {
        try {
            if ($usuarioSelect->IdOperador == 1) {
                $usuarios = DB::table('usuario')
                    ->join('operador', 'usuario.IdOperador', '=', 'operador.IdOperador')
                    ->join('sucursal', 'usuario.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->join('empresa', 'usuario.CodigoCliente', '=', 'empresa.CodigoCliente')
                    ->leftJoin('planes_suscripcion as ps', 'empresa.IdPlanSuscripcion', '=', 'ps.IdPlanSuscripcion')
                    ->select('usuario.*', 'operador.IdOperador', 'operador.Rol as Rol', 'sucursal.Nombre as Sucursal', 'empresa.Ruc', 'empresa.IdRubro', 'ps.Nombre as NombrePlan', 'ps.IdPlanSuscripcion')
                    ->where('usuario.Estado', $estado)
                    ->where('usuario.Cliente', 1)
                    ->get();
            } else {
                $usuarios = DB::table('usuario')
                    ->join('operador', 'usuario.IdOperador', '=', 'operador.IdOperador')
                    ->join('sucursal', 'usuario.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->select('usuario.*', 'operador.IdOperador', 'operador.Rol as Rol', 'sucursal.Nombre as Sucursal')
                    ->where('usuario.Estado', $estado)
                    ->where('usuario.CodigoCliente', $usuarioSelect->CodigoCliente)
                    ->where('usuario.Cliente', 0)
                    ->get();
            }

            return $usuarios;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getPermisos($idUsuario)
    {
        try {
            $permisos = DB::table('usuario_permisos')
                ->join('usuario', 'usuario_permisos.IdUsuario', '=', 'usuario.IdUsuario')
                ->join('permiso', 'usuario_permisos.IdPermiso', '=', 'permiso.IdPermiso')
                ->select('permiso.*', 'usuario_permisos.*')
                ->where('usuario.Estado', 'E')
                ->where('usuario_permisos.Estado', 'E')
                ->where('usuario.IdUsuario', $idUsuario)
                ->get();
            return $permisos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getAllPermisos()
    {
        try {
            $permisos = DB::table('permiso')
                ->where('permiso.Estado', 'E')
                ->get();
            return $permisos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getModulos()
    {
        try {
            $modulos = DB::table('modulo')
                ->where('modulo.Estado', 'E')
                ->get();
            return $modulos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getModulosSelect($codigoCliente)
    {
        try {
            $modulos = DB::table('usuario_modulo')
                ->join('usuario', 'usuario_modulo.IdUsuario', '=', 'usuario.IdUsuario')
                ->join('modulo', 'usuario_modulo.IdModulo', '=', 'modulo.IdModulo')
                ->select('modulo.*', 'usuario_modulo.*')
                ->where('usuario.CodigoCliente', $codigoCliente)
                ->get();
            return $modulos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getUsuarioModulos($idUsuario)
    {
        try {
            $modulos = DB::table('usuario_modulo')
                ->join('usuario', 'usuario_modulo.IdUsuario', '=', 'usuario.IdUsuario')
                ->join('modulo', 'usuario_modulo.IdModulo', '=', 'modulo.IdModulo')
                ->select('modulo.*', 'usuario_modulo.*')
                ->where('usuario.IdUsuario', $idUsuario)
                ->get();
            return $modulos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getTotalUsuarios($codigo)
    {
        try {
            $usuarios = DB::table('usuario')
                ->where('CodigoCliente', $codigo)
                ->get();
            return $usuarios;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getTotalSucursales($codigoCliente)
    {
        try {
            $sucursales = DB::table('sucursal')
                ->select(DB::raw("count(sucursal.IdSucursal) as Total"))
                ->where('Estado', 'E')
                ->where('CodigoCliente', $codigoCliente)
                ->first();
            return $sucursales;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getchartsJsBalanceAndCreditVentas($idUsuario, $idSucursal)
    {
        try {
            $resultado = DB::select('SELECT DAY(FechaCreacion) AS dia, MONTH(FechaCreacion) AS mes, count(*) AS total FROM ventas '
                . 'where IdSucursal =' . $idSucursal . ' and IdCreacion = ' . $idUsuario . '
                    GROUP BY DAY(FechaCreacion)
                    ORDER BY DAY(FechaCreacion) ASC
                    LIMIT 30');
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getchartsJsBalanceAndCreditCompras($idUsuario, $idSucursal)
    {
        try {
            $resultado = DB::select('SELECT DAY(FechaCreacion) AS dia, MONTH(FechaCreacion) AS mes, count(*) AS total FROM compras '
                . 'where IdSucursal =' . $idSucursal . ' and IdCreacion = ' . $idUsuario . '
                    GROUP BY DAY(FechaCreacion)
                    ORDER BY DAY(FechaCreacion) ASC
                    LIMIT 30');
            return $resultado;

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getchartJsPie($dateini, $datefin, $idSucursal)
    {
        try {
            $resultado = DB::table('ventas_articulo')
                ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                ->join('ventas', 'ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
                ->select(DB::raw("TRUNCATE(SUM(CantidadReal),0) as Total"), 'ventas_articulo.IdArticulo as IdArticulo', 'articulo.Descripcion')
                ->where('articulo.Estado', 'E')
                ->where(function ($query) {
                    $query->whereNull('ventas.MotivoAnulacion')
                        ->orWhere('ventas.MotivoAnulacion', '=', '');
                })
                ->where('ventas.Nota', '!=', 1)
                ->where('articulo.IdSucursal', $idSucursal)
                ->whereBetween('ventas.FechaCreacion', [$dateini, $datefin])
                ->groupBy(DB::raw("ventas_articulo.IdArticulo"))
                ->orderBy('Total', 'desc')
                ->limit(5)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // ventas
    public function ventas($dateini, $datefin, $idSucursal)
    {
        try {
            $resultado = DB::table('ventas_articulo')
                ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                ->join('ventas', 'ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
                ->select(DB::raw("TRUNCATE(SUM(CantidadReal),0) as Total"), 'ventas_articulo.IdArticulo as IdArticulo', 'articulo.Descripcion', 'DATE_FORMAT(ventas.FechaCreacion, %H:%i) as fecha')
                ->where('articulo.Estado', 'E')
                ->where(function ($query) {
                    $query->whereNull('ventas.MotivoAnulacion')
                        ->orWhere('ventas.MotivoAnulacion', '=', '');
                })
                ->where('ventas.Nota', '!=', 1)
                ->where('articulo.IdSucursal', $idSucursal)
                ->whereBetween('ventas.FechaCreacion', [$dateini, $datefin])
                ->groupBy(DB::raw("ventas_articulo.IdArticulo"))
                ->orderBy('Total', 'desc')
                ->limit(5)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    // fin

    public function getchartJsBarDashboardVentas($idSucursal)
    {
        try {
            $resultado = DB::select('SELECT MONTH(FechaCreacion) AS mes, YEAR(FechaCreacion) AS anio, count(*) AS total FROM ventas where IdSucursal =' . $idSucursal . '
                                    GROUP BY YEAR(FechaCreacion), MONTH(FechaCreacion)
                                    ORDER BY YEAR(FechaCreacion) DESC, MONTH(FechaCreacion) DESC
                                    LIMIT 6');
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

// GRAFICO VENTAS PANEL
    public function getchartVentas($idSucursal, $tipoMoneda)
    {
        try {
            $resultado = DB::select('SELECT MONTH(FechaCreacion) AS mes, YEAR(FechaCreacion) AS anio, count(*) AS totalVentasRealizadas, SUM(Total) TotalMontoVendido FROM ventas where IdSucursal =' . $idSucursal . '   and  IdTipoComprobante in (1,2,3)  and Nota NOT IN (1)  and Estado NOT in ("Rechazo", "Baja Aceptado", "Baja Pendiente", "Baja Ticket") and  IdTipoMoneda = ' . $tipoMoneda . '
                                    GROUP BY YEAR(FechaCreacion), MONTH(FechaCreacion)
                                    ORDER BY YEAR(FechaCreacion) DESC, MONTH(FechaCreacion) DESC
                                    LIMIT 6');
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getchartCompras($idSucursal)
    {
        try {
            $resultado = DB::select('SELECT MONTH(FechaCreacion) AS mes, YEAR(FechaCreacion) AS anio, count(*) AS total FROM compras where IdSucursal =' . $idSucursal . '
                                    GROUP BY YEAR(FechaCreacion), MONTH(FechaCreacion)
                                    ORDER BY YEAR(FechaCreacion) DESC, MONTH(FechaCreacion) DESC
                                    LIMIT 6');
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
// FIN

    public function countcharJsBarDashboardVentas($dateini, $idSucursal)
    {
        try {
            $resultado = DB::table('ventas')
                ->select(DB::raw("count(IdVentas) as Cantidad"))
                ->where('FechaCreacion', '>=', $dateini)
                ->where('IdSucursal', $idSucursal)
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getchartJsBarDashboardCompras($idSucursal)
    {
        try {
            /*$resultado = DB::select('(SELECT
            SUM(IF(MONTH(FechaCreacion) = 1,  1, 0)) AS Ene,
            SUM(IF(MONTH(FechaCreacion) = 2,  1, 0)) AS Feb,
            SUM(IF(MONTH(FechaCreacion) = 3,  1, 0)) AS Mar,
            SUM(IF(MONTH(FechaCreacion) = 4,  1, 0)) AS Abr,
            SUM(IF(MONTH(FechaCreacion) = 5,  1, 0)) AS May,
            SUM(IF(MONTH(FechaCreacion) = 6,  1, 0)) AS Jun,
            SUM(IF(MONTH(FechaCreacion) = 7,  1, 0)) AS Jul,
            SUM(IF(MONTH(FechaCreacion) = 8,  1, 0)) AS Ago,
            SUM(IF(MONTH(FechaCreacion) = 9,  1, 0)) AS Sep,
            SUM(IF(MONTH(FechaCreacion) = 10, 1, 0)) AS Oct,
            SUM(IF(MONTH(FechaCreacion) = 11, 1, 0)) AS Nov,
            SUM(IF(MONTH(FechaCreacion) = 12, 1, 0)) AS Dic
            FROM compras
            WHERE IdSucursal=60 AND FechaCreacion BETWEEN "2021-11-01" AND "2022-04-31") union
            (SELECT
            SUM(IF(MONTH(FechaCreacion) = 1,  1, 0)) AS Ene,
            SUM(IF(MONTH(FechaCreacion) = 2,  1, 0)) AS Feb,
            SUM(IF(MONTH(FechaCreacion) = 3,  1, 0)) AS Mar,
            SUM(IF(MONTH(FechaCreacion) = 4,  1, 0)) AS Abr,
            SUM(IF(MONTH(FechaCreacion) = 5,  1, 0)) AS May,
            SUM(IF(MONTH(FechaCreacion) = 6,  1, 0)) AS Jun,
            SUM(IF(MONTH(FechaCreacion) = 7,  1, 0)) AS Jul,
            SUM(IF(MONTH(FechaCreacion) = 8,  1, 0)) AS Ago,
            SUM(IF(MONTH(FechaCreacion) = 9,  1, 0)) AS Sep,
            SUM(IF(MONTH(FechaCreacion) = 10, 1, 0)) AS Oct,
            SUM(IF(MONTH(FechaCreacion) = 11, 1, 0)) AS Nov,
            SUM(IF(MONTH(FechaCreacion) = 12, 1, 0)) AS Dic
            FROM ventas
            WHERE IdSucursal=60 AND FechaCreacion BETWEEN "2021-11-01" AND "2022-04-31")');*/

            $resultado = DB::select('SELECT MONTH(FechaCreacion) AS mes, YEAR(FechaCreacion) AS anio, count(*) AS total FROM compras where IdSucursal =' . $idSucursal . '
                                    GROUP BY YEAR(FechaCreacion), MONTH(FechaCreacion)
                                    ORDER BY YEAR(FechaCreacion) DESC, MONTH(FechaCreacion) DESC
                                    LIMIT 6');

            /*$resultado = DB::select('(SELECT MONTH(FechaCreacion) AS mes, YEAR(FechaCreacion) AS anio, count(*) AS total, 1 as Tipo FROM compras where IdSucursal =' . $idSucursal . ' GROUP BY YEAR(FechaCreacion), MONTH(FechaCreacion) ORDER BY YEAR(FechaCreacion) DESC, MONTH(FechaCreacion) DESC LIMIT 6) UNION ALL
            (SELECT MONTH(FechaCreacion) AS mes, YEAR(FechaCreacion) AS anio, count(*) AS total, 2 as Tipo FROM ventas where IdSucursal =' . $idSucursal . ' GROUP BY YEAR(FechaCreacion), MONTH(FechaCreacion) ORDER BY YEAR(FechaCreacion) DESC, MONTH(FechaCreacion) DESC LIMIT 6)');*/

            /*$resultado = DB::select('SELECT mes.Nombre, IFNULL(T1.mes, mes.IdMes) as _mes, T1.anio as _anio, IFNULL(T1.total, 0) as total FROM mes
            LEFT JOIN (SELECT MONTH(FechaCreacion) as mes, YEAR(FechaCreacion) as anio, count(*) as total FROM compras
            GROUP BY mes) T1 ON T1.Mes = mes.IdMes');*/

            /*$resultado = DB::select('SELECT
            YEAR(FechaCreacion) AS year,
            MONTH(FechaCreacion) AS month,
            COUNT(*) AS cnt
            FROM compras
            LEFT JOIN mes
            ON MONTH(FechaCreacion) = mes.IdMes
            GROUP BY MONTH(FechaCreacion)
            ORDER BY YEAR(FechaCreacion), MONTH(FechaCreacion)');*/

            /*$resultado = DB::select('SELECT
            SUM(CASE WHEN MONTH(FechaCreacion) = 1 THEN 1 ELSE 0 END) AS "Januari",
            SUM(CASE WHEN MONTH(FechaCreacion) = 2 THEN 1 ELSE 0 END) AS "Februari",
            SUM(CASE WHEN MONTH(FechaCreacion) = 3 THEN 1 ELSE 0 END) AS "Maart",
            SUM(CASE WHEN MONTH(FechaCreacion) = 4 THEN 1 ELSE 0 END) AS "April",
            SUM(CASE WHEN MONTH(FechaCreacion) = 5 THEN 1 ELSE 0 END) AS "Mei",
            SUM(CASE WHEN MONTH(FechaCreacion) = 6 THEN 1 ELSE 0 END) AS "Juni",
            SUM(CASE WHEN MONTH(FechaCreacion) = 7 THEN 1 ELSE 0 END) AS "Juli",
            SUM(CASE WHEN MONTH(FechaCreacion) = 8 THEN 1 ELSE 0 END) AS "Augustus",
            SUM(CASE WHEN MONTH(FechaCreacion) = 9 THEN 1 ELSE 0 END) AS "September",
            SUM(CASE WHEN MONTH(FechaCreacion) = 10 THEN 1 ELSE 0 END) AS "Oktober",
            SUM(CASE WHEN MONTH(FechaCreacion) = 11 THEN 1 ELSE 0 END) AS "November",
            SUM(CASE WHEN MONTH(FechaCreacion) = 12 THEN 1 ELSE 0 END) AS "December"
            FROM compras
            WHERE FechaCreacion BETWEEN Date_add(NOW(), interval - 12 month) AND  NOW()');*/
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCantidadVentasMensual($fecha, $idSucursal)
    {
        try {
            $resultado = DB::table('ventas')
                ->select(DB::raw("count(IdVentas) as Cantidad"))
                ->where('FechaCreacion', '>', $fecha)
                ->where('IdSucursal', $idSucursal)
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCantidadComprasMensual($fecha, $idSucursal)
    {
        try {
            $resultado = DB::table('compras')
                ->select(DB::raw("count(IdCompras) as Cantidad"))
                ->where('FechaCreacion', '>', $fecha)
                ->where('IdSucursal', $idSucursal)
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCobranzas($idSucursal)
    {
        try {
            $resultado = DB::table('ventas')
                ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                ->join('fecha_pago', 'ventas.IdVentas', '=', 'fecha_pago.IdVenta')
                ->select('ventas.*', 'fecha_pago.Estado as TipoEstado', 'fecha_pago.ImportePagado', 'fecha_pago.DiasPasados', DB::raw("(ventas.PlazoCredito) as Dias"), 'cliente.Nombre as Cliente')
                ->where('IdTipoPago', 2)
                ->where('ventas.IdSucursal', $idSucursal)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCobranzasFiltrados($idSucursal, $cliente, $fecha1, $fecha2)
    {
        try {
            //$fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            if ($cliente == '0') {
                $resultado = DB::table('ventas')
                    ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                    ->join('fecha_pago', 'ventas.IdVentas', '=', 'fecha_pago.IdVenta')
                    ->select('ventas.*', 'fecha_pago.Estado as TipoEstado', 'fecha_pago.ImportePagado', 'fecha_pago.DiasPasados', DB::raw("(ventas.PlazoCredito) as Dias"), 'cliente.Nombre as Cliente')
                    ->where('IdTipoPago', 2)
                    ->whereBetween('ventas.FechaCreacion', [$fecha1, $fecha2])
                    ->where('ventas.IdSucursal', $idSucursal)
                    ->get();
                return $resultado;
            } else {
                $resultado = DB::table('ventas')
                    ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                    ->join('fecha_pago', 'ventas.IdVentas', '=', 'fecha_pago.IdVenta')
                    ->select('ventas.*', 'fecha_pago.Estado as TipoEstado', 'fecha_pago.ImportePagado', 'fecha_pago.DiasPasados', DB::raw("(ventas.PlazoCredito) as Dias"), 'cliente.Nombre as Cliente')
                    ->where('IdTipoPago', 2)
                    ->where('cliente.Nombre', $cliente)
                    ->whereBetween('ventas.FechaCreacion', [$fecha1, $fecha2])
                    ->where('ventas.IdSucursal', $idSucursal)
                    ->get();

                return $resultado;
            }

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getDetalleCobranzas($idVentas)
    {
        try {
            $resultado = DB::table('fecha_pago')
                ->join('ventas', 'fecha_pago.IdVenta', '=', 'ventas.IdVentas')
                ->join('estados_cobranza', 'fecha_pago.Estado', '=', 'estados_cobranza.IdEstadoCobranza')
                ->select('fecha_pago.*', 'estados_cobranza.Descripcion as NombreEstado', 'ventas.IdTipoMoneda')
                ->where('IdVenta', $idVentas)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getDetallePagos($idCompras)
    {
        try {
            $resultado = DB::table('fecha_compras')
                ->join('compras', 'fecha_compras.IdCompras', '=', 'compras.IdCompras')
                ->select('fecha_compras.*')
                ->where('fecha_compras.IdCompras', $idCompras)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getDeudasTotalesCompras($idCompras)
    {
        try {
            $resultado = DB::table('fecha_compras')
                ->select(DB::raw("SUM(Importe) as ImporteTotal"), DB::raw("SUM(Importe - ImportePagado) as TotalDeuda"))
                ->where('IdCompras', $idCompras)
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getchartJsDoughnut($fecha, $idSucursal)
    {
        try {
            $resultado = DB::table('ventas_articulo')
                ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                ->join('ventas', 'ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
                ->select(DB::raw("SUM(Cantidad) as Total, DATE_FORMAT(ventas.FechaCreacion, '%H:%i' ) as horaCreacion"), 'ventas_articulo.Importe as montoTotal', 'ventas_articulo.IdArticulo as IdArticulo', 'articulo.Descripcion', 'ventas.FechaCreacion', 'Precio') //lo cambie por cantidad Real
                ->where('articulo.Estado', 'E')
                ->where(function ($query) {
                    $query->whereNull('ventas.MotivoAnulacion')
                        ->orWhere('ventas.MotivoAnulacion', '=', '');
                })
                ->where('ventas.Nota', '!=', 1)
                ->where('articulo.IdSucursal', $idSucursal)
                ->where('ventas.FechaCreacion', '>', $fecha)
                ->groupBy(DB::raw("ventas_articulo.IdArticulo"))
                ->orderBy('Total', 'desc')
                ->limit(5)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // VENTAS  DIARIAS
    public function ventasDiarias($fecha, $agruparPor, $idTipoMoneda, $idSucursal)
    {
        try {
            $resultado = DB::table('ventas')
                ->select(DB::raw("count(*) as totalVentasDiarias,SUM(Total) as TotalVentasDinero, HOUR(ventas.FechaCreacion ) as horaCreacion, DATE_FORMAT(ventas.FechaCreacion, '%d-%m-%Y') as FechaCreacion , Date_format(FechaCreacion,'%W') as dia"))

                ->where(function ($query) {
                    $query->whereNull('ventas.MotivoAnulacion')
                        ->orWhere('ventas.MotivoAnulacion', '=', '');
                })
                ->where('ventas.Nota', '!=', 1)
                ->where('IdSucursal', $idSucursal)
                ->where('ventas.FechaCreacion', '>', $fecha)
                ->where('ventas.IdTipoMoneda', $idTipoMoneda)
            // ->groupBy(DB::raw("horaCreacion"))
                ->groupBy(DB::raw($agruparPor))
                ->orderBy('FechaCreacion', 'asc')
            // ->limit(5)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function ventasMesAnterior($fechaIni, $fechaFin, $idTipoMoneda, $idSucursal)
    {
        try {
            $resultado = DB::table('ventas')
                ->select(DB::raw("count(*) as totalVentasDelMes, Total"))
                ->where(function ($query) {
                    $query->whereNull('ventas.MotivoAnulacion')
                        ->orWhere('ventas.MotivoAnulacion', '=', '');
                })
                ->where('ventas.Nota', '=', 0)
                ->where('IdSucursal', $idSucursal)
                ->where('ventas.IdTipoMoneda', $idTipoMoneda)
                ->whereNotIn('ventas.Estado', ['Baja Aceptado', 'Baja Pendiente', 'Baja Ticket'])
                ->whereBetween('ventas.FechaCreacion', [$fechaIni, $fechaFin])
            // ->limit(5)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // FIN

    // public function getchartJsDoughnut($fecha, $idSucursal)
    // {
    //     try {
    //         $resultado = DB::table('ventas_articulo')
    //             ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
    //             ->join('ventas', 'ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
    //             ->select(DB::raw("SUM(CantidadReal) as Total"), 'Total as montoTotal', 'ventas_articulo.IdArticulo as IdArticulo', 'articulo.Descripcion') //lo cambie por cantidad Real
    //             ->where('articulo.Estado', 'E')
    //             ->where(function ($query) {
    //                 $query->whereNull('ventas.MotivoAnulacion')
    //                     ->orWhere('ventas.MotivoAnulacion', '=', '');
    //             })
    //             ->where('ventas.Nota', '!=', 1)
    //             ->where('articulo.IdSucursal', $idSucursal)
    //             ->where('ventas.FechaCreacion', '>', $fecha)
    //             ->groupBy(DB::raw("ventas_articulo.IdArticulo"))
    //             ->orderBy('Total', 'desc')
    //             ->limit(5)
    //             ->get();
    //         return $resultado;
    //     } catch (Exception $ex) {
    //         echo $ex->getMessage();
    //     }
    // }

    public function getDetalleCaja($idSucursal, $idUsuario, $fechaHoy, $tipo, $tipoMoneda)
    {
        try {
            $resultado = DB::table('ventas')
                ->select(DB::raw("SUM(Total) as ImporteTotal"))
                ->where('FechaCreacion', '>', $fechaHoy)
                ->where('IdSucursal', $idSucursal)
                ->where('IdCreacion', $idUsuario)
                ->where('IdTipoPago', $tipo)
                ->where('IdTipoMoneda', $tipoMoneda)
                ->whereNotIn('Estado', ['Rechazo'])
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getDetalleCajaCobranzas($idSucursal, $idUsuario, $fechaHoy, $tipoMoneda)
    {
        try {
            $resultado = DB::table('ventas')
                ->join('fecha_pago', 'ventas.IdVentas', '=', 'fecha_pago.IdVenta')
                ->join('pagos_detalle', 'fecha_pago.IdFechaPago', '=', 'pagos_detalle.IdFechaPago')
                ->select(DB::raw("SUM(pagos_detalle.Efectivo) as Efectivo"), DB::raw("SUM(pagos_detalle.Tarjeta) as Tarjeta"), DB::raw("SUM(pagos_detalle.CuentaBancaria) as CuentaBancaria"), DB::raw("(SUM(pagos_detalle.Efectivo) + SUM(pagos_detalle.Tarjeta) + SUM(pagos_detalle.CuentaBancaria)) as TotalCobranza"))
                ->where('pagos_detalle.FechaPago', '>', $fechaHoy)
                ->where('IdSucursal', $idSucursal)
                ->where('pagos_detalle.IdUsuario', $idUsuario)
                ->where('IdTipoMoneda', $tipoMoneda)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getDetalleCajaContado($idSucursal, $idUsuario, $fechaHoy, $tipoMoneda)
    {
        try {
            $resultado = DB::table('ventas')
                ->select(DB::raw("SUM(MontoEfectivo) as Efectivo"), DB::raw("SUM(MontoTarjeta) as Tarjeta"), DB::raw("SUM(MontoCuentaBancaria) as CuentaBancaria"), DB::raw("(SUM(MontoEfectivo) + SUM(MontoTarjeta)) as CajaTotal"))
                ->where('FechaCreacion', '>', $fechaHoy)
                ->where('IdSucursal', $idSucursal)
                ->where('IdCreacion', $idUsuario)
                ->where('IdTipoMoneda', $tipoMoneda)
                ->whereNotIn('Estado', ['Rechazo'])
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCierreCaja($idSucursal, $idUsuario)
    {
        try {
            $resultado = DB::table('caja')
                ->where('IdSucursal', $idSucursal)
                ->where('IdUsuario', $idUsuario)
                ->where('Estado', 'E')
                ->orderBy('FechaApertura', 'desc')
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCierreCajaUltimo($idSucursal, $idUsuario)
    {
        try {
            $resultado = DB::table('caja')
                ->where('IdSucursal', $idSucursal)
                ->where('IdUsuario', $idUsuario)
                ->orderBy('FechaApertura', 'desc')
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getVentasCajas($idSucursal, $fecha)
    {
        try {
            $resultado = DB::table('caja')
                ->join('usuario', 'caja.IdUsuario', '=', 'usuario.IdUsuario')
                ->select('caja.*', 'usuario.Nombre as Usuario')
                ->where('caja.IdSucursal', $idSucursal)
                ->where('FechaApertura', '>', $fecha)
                ->groupBy(DB::raw("caja.IdCaja"))
                ->get();
            $array = [];
            for ($i = 0; $i < count($resultado); $i++) {
                $array[$i] = DB::table('caja_ventas')
                    ->select(DB::raw("SUM(ventas.Total) as ImporteTotal"), DB::raw("SUM(ventas.MontoEfectivo) as Efectivo"), DB::raw("SUM(ventas.MontoTarjeta) as Tarjeta"))
                    ->join('caja', 'caja_ventas.IdCaja', '=', 'caja.IdCaja')
                    ->join('ventas', 'caja_ventas.IdVentas', '=', 'ventas.IdVentas')
                    ->where('caja_ventas.IdCaja', $resultado[$i]->IdCaja)
                    ->get();
                $array[$i]->Cobranzas = DB::table('fecha_pago')
                    ->select(DB::raw("SUM(fecha_pago.MontoEfectivo) as EfectivoCobranzas"), DB::raw("SUM(fecha_pago.MontoTarjeta) as TarjetaCobranzas"))
                    ->where('fecha_pago.IdCaja', $resultado[$i]->IdCaja)
                    ->get();
                $array[$i]->Ingreso = DB::table('ingresoegreso')
                    ->select(DB::raw("SUM(Monto) as MontoIngreso"))
                    ->where('Tipo', 'I')
                    ->where('IdCaja', $resultado[$i]->IdCaja)
                    ->get();
                $array[$i]->Egreso = DB::table('ingresoegreso')
                    ->select(DB::raw("SUM(Monto) as MontoEgreso"))
                    ->where('Tipo', 'E')
                    ->where('IdCaja', $resultado[$i]->IdCaja)
                    ->get();
                $array[$i]->Inicial = $resultado[$i]->Inicial;
                $array[$i]->Usuario = $resultado[$i]->Usuario;
                $array[$i]->FechaApertura = $resultado[$i]->FechaApertura;
                $array[$i]->FechaCierre = $resultado[$i]->FechaCierre;
                $array[$i]->MontoIngreso = $array[$i]->Ingreso[0]->MontoIngreso;
                $array[$i]->MontoEgreso = $array[$i]->Egreso[0]->MontoEgreso;
                $array[$i]->EfectivoCobranzas = number_format($array[$i]->Cobranzas[0]->EfectivoCobranzas, 2, '.', ',');
                $totalEfectivo = floatval($resultado[$i]->Inicial) + floatval($array[$i][0]->Efectivo) + floatval($array[$i]->Cobranzas[0]->EfectivoCobranzas) + floatval($array[$i]->Ingreso[0]->MontoIngreso) - floatval($array[$i]->Egreso[0]->MontoEgreso);
                $array[$i]->TotalEfectivo = number_format($totalEfectivo, 2, '.', ',');
                $totalTarjeta = floatval($array[$i][0]->Tarjeta) + floatval($array[$i]->Cobranzas[0]->TarjetaCobranzas);
                $array[$i]->Totaltarjeta = number_format($totalTarjeta, 2, '.', ',');
                $array[$i]->Estado = $resultado[$i]->Estado;
            }

            return $array;

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getVentasCajasFiltradoNuevo($idSucursal, $vendedor, $fechaIni, $fechaFin)
    {
        try {
            if ($vendedor == null) {
                $resultado = DB::table('caja')
                    ->join('usuario', 'caja.IdUsuario', '=', 'usuario.IdUsuario')
                    ->select('caja.*', 'usuario.Nombre as Usuario')
                    ->where('caja.IdSucursal', $idSucursal)
                    ->whereBetween('FechaApertura', [$fechaIni, $fechaFin])
                    ->groupBy(DB::raw("caja.IdCaja"))
                    ->get();
                return $resultado;
            } else {
                $resultado = DB::table('caja')
                    ->join('usuario', 'caja.IdUsuario', '=', 'usuario.IdUsuario')
                    ->select('caja.*', 'usuario.Nombre as Usuario')
                    ->where('caja.IdSucursal', $idSucursal)
                    ->where('usuario.Nombre', $vendedor)
                    ->whereBetween('FechaApertura', [$fechaIni, $fechaFin])
                    ->groupBy(DB::raw("caja.IdCaja"))
                    ->get();
                return $resultado;
            }

            $array = [];
            for ($i = 0; $i < count($resultado); $i++) {
                $array[$i] = DB::table('caja_ventas')
                    ->select(DB::raw("SUM(ventas.Total) as ImporteTotal"), DB::raw("SUM(ventas.MontoEfectivo) as Efectivo"), DB::raw("SUM(ventas.MontoTarjeta) as Tarjeta"))
                    ->join('caja', 'caja_ventas.IdCaja', '=', 'caja.IdCaja')
                    ->join('ventas', 'caja_ventas.IdVentas', '=', 'ventas.IdVentas')
                    ->where('caja_ventas.IdCaja', $resultado[$i]->IdCaja)
                    ->get();
                $array[$i]->Cobranzas = DB::table('fecha_pago')
                    ->select(DB::raw("SUM(fecha_pago.MontoEfectivo) as EfectivoCobranzas"), DB::raw("SUM(fecha_pago.MontoTarjeta) as TarjetaCobranzas"))
                    ->where('fecha_pago.IdCaja', $resultado[$i]->IdCaja)
                    ->get();
                $array[$i]->Ingreso = DB::table('ingresoegreso')
                    ->select(DB::raw("SUM(Monto) as MontoIngreso"))
                    ->where('Tipo', 'I')
                    ->where('IdCaja', $resultado[$i]->IdCaja)
                    ->get();
                $array[$i]->Egreso = DB::table('ingresoegreso')
                    ->select(DB::raw("SUM(Monto) as MontoEgreso"))
                    ->where('Tipo', 'E')
                    ->where('IdCaja', $resultado[$i]->IdCaja)
                    ->get();
                $array[$i]->Amortizacion = DB::table('amortizacion')
                    ->select(DB::raw("SUM(Monto) as MontoAmortizacion"))
                    ->where('FormaPago', 1)
                    ->where('IdTipoMoneda', 1)
                    ->where('IdCaja', $resultado[$i]->IdCaja)
                    ->get();

                $array[$i]->Inicial = $resultado[$i]->Inicial;
                $array[$i]->Usuario = $resultado[$i]->Usuario;
                $array[$i]->FechaApertura = $resultado[$i]->FechaApertura;
                $array[$i]->FechaCierre = $resultado[$i]->FechaCierre;
                $array[$i]->MontoIngreso = $array[$i]->Ingreso[0]->MontoIngreso;
                $array[$i]->MontoEgreso = $array[$i]->Egreso[0]->MontoEgreso;
                $array[$i]->MontoAmortizacion = $array[$i]->Amortizacion[0]->MontoAmortizacion;
                $array[$i]->EfectivoCobranzas = number_format($array[$i]->Cobranzas[0]->EfectivoCobranzas, 2, '.', ',');
                $totalEfectivo = floatval($resultado[$i]->Inicial) + floatval($array[$i][0]->Efectivo) + floatval($array[$i]->Cobranzas[0]->EfectivoCobranzas) + floatval($array[$i]->Ingreso[0]->MontoIngreso) - floatval($array[$i]->Egreso[0]->MontoEgreso) + floatval($array[$i]->Amortizacion[0]->MontoAmortizacion);
                $array[$i]->TotalEfectivo = number_format($totalEfectivo, 2, '.', ',');
                $totalTarjeta = floatval($array[$i][0]->Tarjeta) + floatval($array[$i]->Cobranzas[0]->TarjetaCobranzas);
                $array[$i]->Totaltarjeta = number_format($totalTarjeta, 2, '.', ',');
                $array[$i]->Estado = $resultado[$i]->Estado;
            }
            //dd($array[0]->MontoIngreso);
            return $array;

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getVentasCajasFiltrado($resultado, $idTipoMoneda)
    {
        try {

            $array = [];
            for ($i = 0; $i < count($resultado); $i++) {
                $array[$i] = DB::table('caja_ventas')
                    ->select(DB::raw("SUM(ventas.Total) as ImporteTotal"), DB::raw("SUM(ventas.MontoEfectivo) as Efectivo"), DB::raw("SUM(ventas.MontoTarjeta) as Tarjeta"), DB::raw("SUM(MontoCuentaBancaria) as CuentaBancaria"))
                    ->join('caja', 'caja_ventas.IdCaja', '=', 'caja.IdCaja')
                    ->join('ventas', 'caja_ventas.IdVentas', '=', 'ventas.IdVentas')
                    ->where('caja_ventas.IdCaja', $resultado[$i]->IdCaja)
                    ->where('ventas.IdTipoMoneda', $idTipoMoneda)
                    ->whereNotIn('ventas.Estado', ['Rechazo'])
                    ->get();
                $array[$i]->Cobranzas = DB::table('fecha_pago')
                    ->join('pagos_detalle', 'fecha_pago.IdFechaPago', '=', 'pagos_detalle.IdFechaPago')
                    ->select(DB::raw("SUM(pagos_detalle.Efectivo) as EfectivoCobranzas"), DB::raw("SUM(pagos_detalle.Tarjeta) as TarjetaCobranzas"), DB::raw("SUM(pagos_detalle.CuentaBancaria) as CuentaBancariaCobranzas"))
                    ->where('fecha_pago.IdCaja', $resultado[$i]->IdCaja)
                    ->whereBetween('pagos_detalle.FechaPago', [$resultado[$i]->FechaApertura, $resultado[$i]->FechaCierre])
                    ->get();
                $array[$i]->Ingreso = DB::table('ingresoegreso')
                    ->select(DB::raw("SUM(Monto) as MontoIngreso"))
                    ->where('Tipo', 'I')
                    ->where('IdCaja', $resultado[$i]->IdCaja)
                    ->where('IdTipoMoneda', $idTipoMoneda)
                    ->get();
                $array[$i]->Egreso = DB::table('ingresoegreso')
                    ->select(DB::raw("SUM(Monto) as MontoEgreso"))
                    ->where('Tipo', 'E')
                    ->where('IdCaja', $resultado[$i]->IdCaja)
                    ->where('IdTipoMoneda', $idTipoMoneda)
                    ->get();
                $array[$i]->Amortizacion = DB::table('amortizacion')
                    ->select(DB::raw("SUM(Monto) as MontoAmortizacion"), DB::raw("SUM(CuentaBancaria) as CuentaBancariaAmortizacion"))
                    ->where('FormaPago', 1)
                    ->where('IdTipoMoneda', $idTipoMoneda)
                    ->where('IdCaja', $resultado[$i]->IdCaja)
                    ->get();

                $array[$i]->Inicial = $resultado[$i]->Inicial;
                $array[$i]->InicialDolares = $resultado[$i]->InicialDolares;
                $array[$i]->Usuario = $resultado[$i]->Usuario;
                $array[$i]->FechaApertura = $resultado[$i]->FechaApertura;
                $array[$i]->FechaCierre = $resultado[$i]->FechaCierre;
                if ($idTipoMoneda == 1) {
                    $inicial = floatval($resultado[$i]->Inicial);
                } else {
                    $inicial = floatval($resultado[$i]->InicialDolares);
                }
                $array[$i]->MontoIngreso = number_format($array[$i]->Ingreso[0]->MontoIngreso, 2, '.', ',');
                $array[$i]->MontoEgreso = number_format($array[$i]->Egreso[0]->MontoEgreso, 2, '.', ',');
                $array[$i]->MontoAmortizacion = number_format($array[$i]->Amortizacion[0]->MontoAmortizacion, 2, '.', ',');
                $array[$i]->CuentaBancariaAmortizacion = number_format($array[$i]->Amortizacion[0]->CuentaBancariaAmortizacion, 2, '.', ',');
                $array[$i]->EfectivoCobranzas = number_format($array[$i]->Cobranzas[0]->EfectivoCobranzas, 2, '.', ',');
                $array[$i]->CuentaBancariaCobranzas = number_format($array[$i]->Cobranzas[0]->CuentaBancariaCobranzas, 2, '.', ',');
                $totalEfectivo = $inicial + floatval($array[$i][0]->Efectivo) + floatval($array[$i]->Cobranzas[0]->EfectivoCobranzas) + floatval($array[$i]->Ingreso[0]->MontoIngreso) - floatval($array[$i]->Egreso[0]->MontoEgreso) + floatval($array[$i]->Amortizacion[0]->MontoAmortizacion);
                $array[$i]->TotalEfectivo = number_format($totalEfectivo, 2, '.', ',');
                $totalTarjeta = floatval($array[$i][0]->Tarjeta) + floatval($array[$i]->Cobranzas[0]->TarjetaCobranzas);
                $array[$i]->Totaltarjeta = number_format($totalTarjeta, 2, '.', ',');
                $array[$i]->Estado = $resultado[$i]->Estado;
                // Nueco codigo agregar idcaja
                $array[$i]->IdCaja = $resultado[$i]->IdCaja;
                // Fin
            }
            //dd($array[0]->MontoIngreso);
            return $array;

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getIngresosEgresos($idSucursal, $idUsuario, $fecha)
    {
        try {
            $resultado = DB::table('ingresoegreso')
                ->join('caja', 'ingresoegreso.IdCaja', '=', 'caja.IdCaja')
                ->join('usuario', 'caja.IdUsuario', '=', 'usuario.IdUsuario')
                ->select('caja.*', 'ingresoegreso.*', 'usuario.Nombre as Usuario')
                ->where('caja.IdSucursal', $idSucursal)
                ->where('caja.IdUsuario', $idUsuario)
                ->where('caja.Estado', 'E')
                ->where('caja.FechaApertura', '>=', $fecha)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // REPORTE  EGRESOS X DIA
    public function getReporteEgresos($idSucursal, $fecha)
    {
        try {
            $resultado = DB::table('ingresoegreso')
                ->join('caja', 'ingresoegreso.IdCaja', '=', 'caja.IdCaja')
                ->join('usuario', 'caja.IdUsuario', '=', 'usuario.IdUsuario')
                ->select('caja.*', 'ingresoegreso.*', 'usuario.Nombre as Usuario', DB::raw("DATE_FORMAT(ingresoegreso.Fecha, '%d-%m-%Y' )AS fechaCreacion,COUNT(*) AS totalIngresoEgreso, Date_format(Fecha,'%W %d %M %Y') as dia, sum(Monto) as  totalMonto"))
                ->where('caja.IdSucursal', $idSucursal)
                ->where('ingresoegreso.Tipo', 'E')
                ->where('ingresoegreso.Fecha', '>', $fecha)
                ->groupBy(DB::raw('dia'))
                ->orderBy('ingresoegreso.Fecha', 'asc')
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    // FIN

    // public function getTotalIngresosEgresos($idSucursal, $idUsuario, $fecha, $tipo, $idTipoMoneda)
    // {
    //     try {
    //         $resultado = DB::table('ingresoegreso')
    //             ->join('caja', 'ingresoegreso.IdCaja', '=', 'caja.IdCaja')
    //             ->join('usuario', 'caja.IdUsuario', '=', 'usuario.IdUsuario')
    //             ->select(DB::raw("SUM(ingresoegreso.Monto) as Monto"))
    //             ->where('caja.IdSucursal', $idSucursal)
    //             ->where('caja.IdUsuario', $idUsuario)
    //             ->where('ingresoegreso.Tipo', $tipo)
    //             ->where('ingresoegreso.IdTipoMoneda', $idTipoMoneda)
    //             ->where('caja.FechaApertura', '>=', $fecha)
    //             ->get();
    //         return $resultado;
    //     } catch (Exception $ex) {
    //         echo $ex->getMessage();
    //     }
    // }

    public function getTotalIngresosEgresos($idSucursal, $idUsuario, $fecha, $tipo, $tipoMoneda)
    {
        try {
            $resultado = DB::table('ingresoegreso')
                ->join('caja', 'ingresoegreso.IdCaja', '=', 'caja.IdCaja')
                ->join('usuario', 'caja.IdUsuario', '=', 'usuario.IdUsuario')
                ->select(DB::raw("SUM(ingresoegreso.Monto) as Monto"))
                ->where('caja.IdSucursal', $idSucursal)
                ->where('caja.IdUsuario', $idUsuario)
                ->where('ingresoegreso.Tipo', $tipo)
                ->where('ingresoegreso.IdTipoMoneda', $tipoMoneda)
                ->where('caja.FechaApertura', '>=', $fecha)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getTotalVentas($idSucursal, $idUsuario)
    {
        try {
            $ventas = DB::table('ventas')
                ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                ->where('ventas.IdSucursal', $idSucursal)
                ->where('ventas.IdCreacion', $idUsuario)
                ->get();
            return $ventas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getTotalNotas($idSucursal)
    {
        try {
            $notas = DB::table('nota_credito_debito')
                ->where('IdSucursal', $idSucursal)
                ->orderBy('IdCreditoDebito', 'desc')
                ->get();
            return $notas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getTotalGuias($idSucursal)
    {
        try {
            $notas = DB::table('guia_remision')
                ->where('IdSucursal', $idSucursal)
                ->get();
            return $notas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getTotalVentasDiaria($fecha, $idSucursal, $tipoMoneda)
    {
        try {
            /* $resultado = DB::table('ventas')
            ->select(DB::raw("SUM(Total) as ImporteTotal"))
            ->where('FechaCreacion','>',$fecha)
            ->where('IdSucursal',$idSucursal)
            ->get(); */
            $resultado = DB::select('SELECT sum(Importe) as ImporteTotal FROM ventas
                                    inner join ventas_articulo on ventas.IdVentas = ventas_articulo.IdVentas
                                    WHERE (ventas.MotivoAnulacion = "" OR ventas.MotivoAnulacion IS NULL) AND Nota NOT IN (1)  and Estado NOT in ("Rechazo", "Baja Aceptado", "Baja Pendiente", "Baja Ticket") AND ventas_articulo.Gratuito = 0 AND FechaCreacion > "' . $fecha . '" and IdSucursal = ' . $idSucursal . ' and IdTipoMoneda = ' . $tipoMoneda . '');
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getTotalVentasMensual($fecha, $idSucursal, $tipoMoneda)
    {
        try {
            $resultado = DB::select('SELECT sum(Importe) as ImporteTotal FROM ventas
                                    inner join ventas_articulo on ventas.IdVentas = ventas_articulo.IdVentas
                                    WHERE (ventas.MotivoAnulacion = "" OR ventas.MotivoAnulacion IS NULL) AND Nota NOT IN (1)  and Estado NOT in ("Rechazo", "Baja Aceptado", "Baja Pendiente", "Baja Ticket") AND ventas_articulo.Gratuito = 0 AND FechaCreacion >= "' . $fecha . '" and IdSucursal = ' . $idSucursal . ' and IdTipoMoneda = ' . $tipoMoneda . '');
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getTotalGananciaDiaria($fecha, $idSucursal, $tipoMoneda)
    {
        try {
            /*  $resultado = DB::table('ventas')
            ->join('ventas_articulo','ventas.IdVentas', '=', 'ventas_articulo.IdVentas')
            ->select(DB::raw("SUM(Ganancia) as GananciaTotal"))
            ->where('FechaCreacion','>',$fecha)
            ->where('IdSucursal',$idSucursal)
            ->get(); */
            $resultado = DB::select('SELECT sum(Ganancia) as GananciaTotal FROM ventas
                                    inner join ventas_articulo on ventas.IdVentas = ventas_articulo.IdVentas
                                    WHERE (ventas.MotivoAnulacion = "" OR ventas.MotivoAnulacion IS NULL) AND Nota NOT IN (1)  and Estado NOT in ("Rechazo", "Baja Aceptado", "Baja Pendiente", "Baja Ticket") AND ventas_articulo.Gratuito = 0 AND FechaCreacion >= "' . $fecha . '" and IdSucursal = ' . $idSucursal . ' and IdTipoMoneda = ' . $tipoMoneda . '');
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getTotalGananciaMensual($fecha, $idSucursal, $tipoMoneda)
    {
        try {
            $resultado = DB::select('SELECT sum(Ganancia) as GananciaTotal FROM ventas
                                    inner join ventas_articulo on ventas.IdVentas = ventas_articulo.IdVentas
                                    WHERE (ventas.MotivoAnulacion = "" OR ventas.MotivoAnulacion IS NULL) AND Nota NOT IN (1)  and Estado NOT in ("Rechazo", "Baja Aceptado", "Baja Pendiente", "Baja Ticket") AND ventas_articulo.Gratuito = 0 AND FechaCreacion >= "' . $fecha . '" and IdSucursal = ' . $idSucursal . ' and IdTipoMoneda = ' . $tipoMoneda . '');
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getchartJsHorizontalBar($idSucursal)
    {
        try {
            $resultado = DB::table('articulo')
                ->where('IdTipo', 1)
                ->where('Estado', 'E')
                ->where('IdSucursal', $idSucursal)
                ->orderBy('Stock', 'asc')
                ->limit(10)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getFacturasPendientes($idSucursal, $date)
    {
        try {
            $facturas = DB::select('(select ventas.IdVentas as IdDoc, 1 as tipo, ventas.FechaCreacion, tipo_comprobante.Descripcion as Comprobante, cliente.Nombre as Nombres, cliente.NumeroDocumento as NroDoc, Serie, Numero, Total, Amortizacion, ventas.Estado
							          from ventas inner join cliente on ventas.IdCliente = cliente.IdCliente
									  inner join tipo_comprobante on ventas.IdTipoComprobante = tipo_comprobante.IdTipoComprobante
									  inner join tipo_documento on tipo_documento.IdTipoDocumento = cliente.IdTipoDocumento
									  where ventas.IdTipoComprobante = 2 and ventas.Estado = "Pendiente" and ventas.IdSucursal = ' . $idSucursal . ' and ventas.FechaCreacion > "' . $date . '") union
                        			(select nota_credito_debito.IdCreditoDebito as IdDoc, 2 as tipo, nota_credito_debito.FechaCreacion, tipo_nota.Descripcion as Comprobante, cliente.Nombre as Nombres, cliente.NumeroDocumento as NroDoc, Serie, Numero, Total, 0.0 as Amortizacion, nota_credito_debito.Estado
									  from nota_credito_debito inner join cliente on nota_credito_debito.IdCliente = cliente.IdCliente
									  inner join tipo_nota on nota_credito_debito.IdTipoNota = tipo_nota.IdTipoNota
									  inner join tipo_documento on tipo_documento.IdTipoDocumento = cliente.IdTipoDocumento
									  where nota_credito_debito.IdDocModificado = 2 and nota_credito_debito.Estado = "Pendiente" and nota_credito_debito.IdSucursal = ' . $idSucursal . ' and nota_credito_debito.FechaCreacion > "' . $date . '") order by FechaCreacion desc');
            return $facturas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getFacturasPendientesFiltrados($idSucursal, $fechaIni, $fechaFin)
    {
        try {
            $facturas = DB::table('ventas')
                ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                ->select('ventas.*', 'cliente.Nombre as Nombres')
                ->where('ventas.IdTipoComprobante', 2)
                ->where('ventas.Estado', 'Pendiente')
                ->where('ventas.IdSucursal', $idSucursal)
                ->whereBetween('ventas.FechaCreacion', [$fechaIni, $fechaFin])
                ->orderBy('IdVentas', 'desc')
                ->get();
            return $facturas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getGuiaRemisionPendientes($idSucursal, $date)
    {
        try {
            $guiasRemision = DB::table('guia_remision')
                ->join('cliente', 'guia_remision.IdCliente', '=', 'cliente.IdCliente')
                ->select('guia_remision.*', 'cliente.Nombre as Nombres')
                ->where('guia_remision.IdSucursal', $idSucursal)
                ->where('guia_remision.Estado', 'Pendiente')
                ->where('guia_remision.FechaCreacion', '>=', $date)
                ->orderBy('IdGuiaRemision', 'desc')
                ->get();

            return $guiasRemision;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getGuiaRemisionesPendientes($idSucursal, $date)
    {
        try {
            $guiasRemision = DB::table('guia_remision')
                ->join('cliente', 'guia_remision.IdCliente', '=', 'cliente.IdCliente')
                ->select('guia_remision.*', 'cliente.Nombre as Nombres')
                ->where('guia_remision.IdSucursal', $idSucursal)
                ->where('guia_remision.Estado', 'Pendiente')
                ->where('guia_remision.FechaCreacion', '>=', $date)
                ->orderBy('IdGuiaRemision', 'desc')
                ->get();

            return $guiasRemision;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getVendedores($idSucursal)
    {
        try {
            $vendedores = DB::table('usuario')
                ->where('IdSucursal', $idSucursal)
                ->whereIn('IdOperador', [2, 6, 8, 10])
                ->get();
            return $vendedores;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getVentasVendedoresFiltrados($idSucursal, $vendedor, $tipoPago, $fecha, $fechaIni, $fechaFin)
    {
        try {
            $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            if ($vendedor == 0) {
                if ($tipoPago == 0) {
                    $ventas = DB::table('ventas')
                        ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                        ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                        ->select('ventas.*', 'cliente.Nombre as Nombres', 'cliente.NumeroDocumento as Documento', 'usuario.Nombre as Usuario')
                        ->where('ventas.IdSucursal', $idSucursal)
                        ->where('ventas.MotivoAnulacion', null)
                        ->where('ventas.Nota', 0)
                        ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                        ->orderBy('IdVentas', 'desc')
                        ->get();
                    for ($i = 0; $i < count($ventas); $i++) {
                        if ($ventas[$i]->IdCotizacion != null) {
                            $cotizacion = DB::table('cotizacion')
                                ->select(DB::raw("CONCAT(Serie, '-', Numero) As codigo"))
                                ->where('IdCotizacion', $ventas[$i]->IdCotizacion)
                                ->first();
                            $ventas[$i]->codigoCotizacion = $cotizacion->codigo;
                        } else {
                            $ventas[$i]->codigoCotizacion = "-";
                        }
                    }
                    return $ventas;
                } else {
                    $ventas = DB::table('ventas')
                        ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                        ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                        ->select('ventas.*', 'cliente.Nombre as Nombres', 'cliente.NumeroDocumento as Documento', 'usuario.Nombre as Usuario')
                        ->where('ventas.IdSucursal', $idSucursal)
                        ->where('ventas.MotivoAnulacion', null)
                        ->where('ventas.Nota', 0)
                        ->where('ventas.IdTipoPago', $tipoPago)
                        ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                        ->orderBy('IdVentas', 'desc')
                        ->get();
                    for ($i = 0; $i < count($ventas); $i++) {
                        if ($ventas[$i]->IdCotizacion != null) {
                            $cotizacion = DB::table('cotizacion')
                                ->select(DB::raw("CONCAT(Serie, '-', Numero) As codigo"))
                                ->where('IdCotizacion', $ventas[$i]->IdCotizacion)
                                ->first();
                            $ventas[$i]->codigoCotizacion = $cotizacion->codigo;
                        } else {
                            $ventas[$i]->codigoCotizacion = "-";
                        }
                    }
                    return $ventas;
                }
            } else {
                if ($tipoPago == 0) {
                    $ventas = DB::table('ventas')
                        ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                        ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                        ->select('ventas.*', 'cliente.Nombre as Nombres', 'cliente.NumeroDocumento as Documento', 'usuario.Nombre as Usuario')
                        ->where('ventas.IdSucursal', $idSucursal)
                        ->where('ventas.MotivoAnulacion', null)
                        ->where('ventas.Nota', 0)
                        ->where('usuario.IdUsuario', $vendedor)
                        ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                        ->orderBy('IdVentas', 'desc')
                        ->get();
                    for ($i = 0; $i < count($ventas); $i++) {
                        if ($ventas[$i]->IdCotizacion != null) {
                            $cotizacion = DB::table('cotizacion')
                                ->select(DB::raw("CONCAT(Serie, '-', Numero) As codigo"))
                                ->where('IdCotizacion', $ventas[$i]->IdCotizacion)
                                ->first();
                            $ventas[$i]->codigoCotizacion = $cotizacion->codigo;
                        } else {
                            $ventas[$i]->codigoCotizacion = "-";
                        }
                    }
                    return $ventas;

                } else {
                    $ventas = DB::table('ventas')
                        ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                        ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                        ->select('ventas.*', 'cliente.Nombre as Nombres', 'cliente.NumeroDocumento as Documento', 'usuario.Nombre as Usuario')
                        ->where('ventas.IdSucursal', $idSucursal)
                        ->where('ventas.MotivoAnulacion', null)
                        ->where('ventas.Nota', 0)
                        ->where('usuario.IdUsuario', $vendedor)
                        ->where('ventas.IdTipoPago', $tipoPago)
                        ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                        ->orderBy('IdVentas', 'desc')
                        ->get();
                    for ($i = 0; $i < count($ventas); $i++) {
                        if ($ventas[$i]->IdCotizacion != null) {
                            $cotizacion = DB::table('cotizacion')
                                ->select(DB::raw("CONCAT(Serie, '-', Numero) As codigo"))
                                ->where('IdCotizacion', $ventas[$i]->IdCotizacion)
                                ->first();
                            $ventas[$i]->codigoCotizacion = $cotizacion->codigo;
                        } else {
                            $ventas[$i]->codigoCotizacion = "-";
                        }
                    }
                    return $ventas;
                }
            }

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // Funcion de prueba
    public function grafgetVentasVendedoresFiltradosGrafico($idSucursal, $vendedor, $tipoPago, $fecha, $fechaIni, $fechaFin)
    {

        try {
            //$fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            if ($vendedor == 0) {
                if ($tipoPago == 0) {

                    $ventas = DB::table('ventas as v')
                        ->join('usuario', 'v.IdCreacion', '=', 'usuario.IdUsuario')
                        ->where('v.IdSucursal', $idSucursal)
                    // ->select(DB::raw('count(*) as total, usuario.Nombre ', 'SUM(v.total) as totalventas', 'v.total'))
                        ->select(DB::raw('count(*) as total, SUBSTRING_INDEX(Nombre , " ", 1) AS Nombre , SUM(v.total) as totalventas, v.IdTipoPago'))
                        ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw("usuario.Nombre"))
                        ->get();

                    return $ventas;
                } else {

                    $ventas = DB::table('ventas as v')
                        ->join('usuario', 'v.IdCreacion', '=', 'usuario.IdUsuario')
                        ->where('v.IdSucursal', $idSucursal)
                        ->select(DB::raw('count(*) as total, SUBSTRING_INDEX(Nombre , " ", 1) AS Nombre , SUM(v.total) as totalventas, v.IdTipoPago'))
                        ->where('v.IdTipoPago', $tipoPago)
                        ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw("usuario.Nombre"))
                        ->get();

                    return $ventas;
                }
            } else {
                if ($tipoPago == 0) {

                    $ventas = DB::table('ventas as v')
                        ->join('usuario', 'v.IdCreacion', '=', 'usuario.IdUsuario')
                        ->select(DB::raw('count(*) as total, SUBSTRING_INDEX(Nombre , " ", 1) AS Nombre , SUM(v.total) as totalventas, v.IdTipoPago'))
                        ->where('v.IdSucursal', $idSucursal)
                        ->where('usuario.IdUsuario', $vendedor)
                        ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw("usuario.Nombre"))
                        ->get();

                    return $ventas;

                } else {

                    $ventas = DB::table('ventas as v')
                        ->join('usuario', 'v.IdCreacion', '=', 'usuario.IdUsuario')
                        ->select(DB::raw('count(*) as total, SUBSTRING_INDEX(Nombre , " ", 1) AS Nombre , SUM(v.total) as totalventas, v.IdTipoPago'))
                        ->where('v.IdSucursal', $idSucursal)
                        ->where('usuario.IdUsuario', $vendedor)
                        ->where('v.IdTipoPago', $tipoPago)
                        ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw("usuario.Nombre"))
                        ->get();

                    return $ventas;
                }
            }

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    // Fin

    public function grafgetVentasVendedoresFiltrados($idSucursal, $vendedor, $tipoPago, $fecha, $fechaIni, $fechaFin)
    {
        /* $clientes = DB::table('ventas as v')
        ->join('cliente','v.IdCliente', '=', 'cliente.IdCliente')
        ->where('v.IdSucursal',$idSucursal)
        ->select(DB::raw('count(*) as total, cliente.Nombre '))
        ->whereBetween('v.FechaCreacion', [$fechas[0], $fechas[1]])
        ->groupBy(DB::raw("cliente.Nombre"))
        ->get();

        $vendedores = DB::table('ventas as v')
        ->join('usuario','v.IdCreacion', '=', 'usuario.IdUsuario')
        ->where('v.IdSucursal',$idSucursal)
        ->select(DB::raw('count(*) as total, usuario.Nombre '))
        ->groupBy(DB::raw("usuario.Nombre"))
        ->get(); */

        try {
            //$fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            if ($vendedor == 0) {
                if ($tipoPago == 0) {

                    $ventas = DB::table('ventas as v')
                        ->join('usuario', 'v.IdCreacion', '=', 'usuario.IdUsuario')
                        ->where('v.IdSucursal', $idSucursal)
                    // ->select(DB::raw('count(*) as total, usuario.Nombre ', 'SUM(v.total) as totalventas', 'v.total'))
                        ->select(DB::raw('count(*) as total, SUBSTRING_INDEX(Nombre , " ", 1) AS Nombre , SUM(v.total) as totalventas, v.IdTipoPago'))
                        ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw("usuario.Nombre"))
                        ->get();

                    /* $ventas= DB::table('ventas')
                    ->join('cliente','ventas.IdCliente', '=', 'cliente.IdCliente')
                    ->join('sucursal','ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->join('usuario','ventas.IdCreacion', '=', 'usuario.IdUsuario')
                    ->select('ventas.*', 'cliente.Nombre as Nombres','usuario.Nombre as Usuario')
                    ->where('ventas.IdSucursal', $idSucursal)
                    ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->orderBy('IdVentas','desc')
                    ->get(); */
                    return $ventas;
                } else {

                    $ventas = DB::table('ventas as v')
                        ->join('usuario', 'v.IdCreacion', '=', 'usuario.IdUsuario')
                        ->where('v.IdSucursal', $idSucursal)
                        ->select(DB::raw('count(*) as total, SUBSTRING_INDEX(Nombre , " ", 1) AS Nombre , SUM(v.total) as totalventas, v.IdTipoPago'))
                        ->where('v.IdTipoPago', $tipoPago)
                        ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw("usuario.Nombre"))
                        ->get();
                    /*
                    $ventas= DB::table('ventas')
                    ->join('cliente','ventas.IdCliente', '=', 'cliente.IdCliente')
                    ->join('sucursal','ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->join('usuario','ventas.IdCreacion', '=', 'usuario.IdUsuario')
                    ->select('ventas.*', 'cliente.Nombre as Nombres', 'usuario.Nombre as Usuario')
                    ->where('ventas.IdSucursal', $idSucursal)
                    ->where('ventas.IdTipoPago', $tipoPago)
                    ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->orderBy('IdVentas','desc')
                    ->get(); */
                    return $ventas;
                }
            } else {
                if ($tipoPago == 0) {

                    $ventas = DB::table('ventas as v')
                        ->join('usuario', 'v.IdCreacion', '=', 'usuario.IdUsuario')
                        ->select(DB::raw('count(*) as total, SUBSTRING_INDEX(Nombre , " ", 1) AS Nombre , SUM(v.total) as totalventas, v.IdTipoPago'))
                        ->where('v.IdSucursal', $idSucursal)
                        ->where('usuario.IdUsuario', $vendedor)
                        ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw("usuario.Nombre"))
                        ->get();

                    /*
                    $ventas= DB::table('ventas')
                    ->join('cliente','ventas.IdCliente', '=', 'cliente.IdCliente')
                    ->join('sucursal','ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->join('usuario','ventas.IdCreacion', '=', 'usuario.IdUsuario')
                    ->select('ventas.*', 'cliente.Nombre as Nombres', 'usuario.Nombre as Usuario')
                    ->where('ventas.IdSucursal', $idSucursal)
                    ->where('usuario.Nombre', $vendedor)
                    ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->orderBy('IdVentas','desc')
                    ->get(); */
                    return $ventas;

                } else {

                    $ventas = DB::table('ventas as v')
                        ->join('usuario', 'v.IdCreacion', '=', 'usuario.IdUsuario')
                        ->select(DB::raw('count(*) as total, SUBSTRING_INDEX(Nombre , " ", 1) AS Nombre , SUM(v.total) as totalventas, v.IdTipoPago'))
                        ->where('v.IdSucursal', $idSucursal)
                        ->where('usuario.IdUsuario', $vendedor)
                        ->where('v.IdTipoPago', $tipoPago)
                        ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw("usuario.Nombre"))
                        ->get();

                    /* $ventas= DB::table('ventas')
                    ->join('cliente','ventas.IdCliente', '=', 'cliente.IdCliente')
                    ->join('sucursal','ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->join('usuario','ventas.IdCreacion', '=', 'usuario.IdUsuario')
                    ->select('ventas.*', 'cliente.Nombre as Nombres', 'usuario.Nombre as Usuario')
                    ->where('ventas.IdSucursal', $idSucursal)
                    ->where('usuario.Nombre', $vendedor)
                    ->where('ventas.IdTipoPago', $tipoPago)
                    ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->orderBy('IdVentas','desc')
                    ->get(); */
                    return $ventas;
                }
            }

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // public function grafgetVentasVendedoresFiltrados($idSucursal, $vendedor, $tipoPago, $fecha, $fechaIni, $fechaFin){
    //     /* $clientes = DB::table('ventas as v')
    //                 ->join('cliente','v.IdCliente', '=', 'cliente.IdCliente')
    //                 ->where('v.IdSucursal',$idSucursal)
    //                 ->select(DB::raw('count(*) as total, cliente.Nombre '))
    //                 ->whereBetween('v.FechaCreacion', [$fechas[0], $fechas[1]])
    //                 ->groupBy(DB::raw("cliente.Nombre"))
    //                 ->get();

    //     $vendedores = DB::table('ventas as v')
    //         ->join('usuario','v.IdCreacion', '=', 'usuario.IdUsuario')
    //         ->where('v.IdSucursal',$idSucursal)
    //         ->select(DB::raw('count(*) as total, usuario.Nombre '))
    //         ->groupBy(DB::raw("usuario.Nombre"))
    //         ->get(); */

    //     try{
    //         //$fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
    //         if($vendedor == 0){
    //             if($tipoPago == 0){

    //                 $ventas = DB::table('ventas as v')
    //                     ->join('usuario','v.IdCreacion', '=', 'usuario.IdUsuario')
    //                     ->where('v.IdSucursal',$idSucursal)
    //                     // ->select(DB::raw('count(*) as total, usuario.Nombre ', 'SUM(v.total) as totalventas', 'v.total'))
    //                     ->select(DB::raw('count(*) as total, usuario.Nombre , SUM(v.total) as totalventas, v.IdTipoPago'))
    //                     ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
    //                     ->groupBy(DB::raw("usuario.Nombre"))
    //                     ->get();

    //                 /* $ventas= DB::table('ventas')
    //                     ->join('cliente','ventas.IdCliente', '=', 'cliente.IdCliente')
    //                     ->join('sucursal','ventas.IdSucursal', '=', 'sucursal.IdSucursal')
    //                     ->join('usuario','ventas.IdCreacion', '=', 'usuario.IdUsuario')
    //                     ->select('ventas.*', 'cliente.Nombre as Nombres','usuario.Nombre as Usuario')
    //                     ->where('ventas.IdSucursal', $idSucursal)
    //                     ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
    //                     ->orderBy('IdVentas','desc')
    //                     ->get(); */
    //                 return $ventas;
    //             }else{

    //                 $ventas = DB::table('ventas as v')
    //                     ->join('usuario','v.IdCreacion', '=', 'usuario.IdUsuario')
    //                     ->where('v.IdSucursal',$idSucursal)
    //                     ->select(DB::raw('count(*) as total, usuario.Nombre , SUM(v.total) as totalventas'))
    //                     ->where('v.IdTipoPago', $tipoPago)
    //                     ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
    //                     ->groupBy(DB::raw("usuario.Nombre"))
    //                     ->get();
    //             /*
    //                 $ventas= DB::table('ventas')
    //                     ->join('cliente','ventas.IdCliente', '=', 'cliente.IdCliente')
    //                     ->join('sucursal','ventas.IdSucursal', '=', 'sucursal.IdSucursal')
    //                     ->join('usuario','ventas.IdCreacion', '=', 'usuario.IdUsuario')
    //                     ->select('ventas.*', 'cliente.Nombre as Nombres', 'usuario.Nombre as Usuario')
    //                     ->where('ventas.IdSucursal', $idSucursal)
    //                     ->where('ventas.IdTipoPago', $tipoPago)
    //                     ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
    //                     ->orderBy('IdVentas','desc')
    //                     ->get(); */
    //                 return $ventas;
    //             }
    //         }else{
    //             if($tipoPago == 0){

    //                 $ventas = DB::table('ventas as v')
    //                     ->join('usuario','v.IdCreacion', '=', 'usuario.IdUsuario')
    //                     ->select(DB::raw('count(*) as total, usuario.Nombre , SUM(v.total) as totalventas'))
    //                     ->where('v.IdSucursal',$idSucursal)
    //                     ->where('usuario.IdUsuario', $vendedor)
    //                     ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
    //                     ->groupBy(DB::raw("usuario.Nombre"))
    //                     ->get();

    //                 /*
    //                 $ventas= DB::table('ventas')
    //                     ->join('cliente','ventas.IdCliente', '=', 'cliente.IdCliente')
    //                     ->join('sucursal','ventas.IdSucursal', '=', 'sucursal.IdSucursal')
    //                     ->join('usuario','ventas.IdCreacion', '=', 'usuario.IdUsuario')
    //                     ->select('ventas.*', 'cliente.Nombre as Nombres', 'usuario.Nombre as Usuario')
    //                     ->where('ventas.IdSucursal', $idSucursal)
    //                     ->where('usuario.Nombre', $vendedor)
    //                     ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
    //                     ->orderBy('IdVentas','desc')
    //                     ->get(); */
    //                 return $ventas;

    //             }else{

    //                 $ventas = DB::table('ventas as v')
    //                     ->join('usuario','v.IdCreacion', '=', 'usuario.IdUsuario')
    //                     ->select(DB::raw('count(*) as total, usuario.Nombre , SUM(v.total) as totalventas'))
    //                     ->where('v.IdSucursal',$idSucursal)
    //                     ->where('usuario.IdUsuario', $vendedor)
    //                     ->where('v.IdTipoPago', $tipoPago)
    //                     ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
    //                     ->groupBy(DB::raw("usuario.Nombre"))
    //                     ->get();

    //                 /* $ventas= DB::table('ventas')
    //                     ->join('cliente','ventas.IdCliente', '=', 'cliente.IdCliente')
    //                     ->join('sucursal','ventas.IdSucursal', '=', 'sucursal.IdSucursal')
    //                     ->join('usuario','ventas.IdCreacion', '=', 'usuario.IdUsuario')
    //                     ->select('ventas.*', 'cliente.Nombre as Nombres', 'usuario.Nombre as Usuario')
    //                     ->where('ventas.IdSucursal', $idSucursal)
    //                     ->where('usuario.Nombre', $vendedor)
    //                     ->where('ventas.IdTipoPago', $tipoPago)
    //                     ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
    //                     ->orderBy('IdVentas','desc')
    //                     ->get(); */
    //                 return $ventas;
    //             }
    //         }

    //     } catch (Exception $ex) {
    //         echo $ex->getMessage();
    //     }
    // }

    public function getVentasProductos($idSucursal)
    {
        try {
            /*$ventas= DB::table('ventas')
            ->join('cliente','ventas.IdCliente', '=', 'cliente.IdCliente')
            ->join('sucursal','ventas.IdSucursal', '=', 'sucursal.IdSucursal')
            ->join('usuario','ventas.IdCreacion', '=', 'usuario.IdUsuario')
            ->join('ventas_articulo','ventas.IdVentas', '=', 'ventas_articulo.IdVentas')
            ->select('ventas.*', 'cliente.Nombre as Nombres',  'usuario.Nombre as Usuario')
            ->where('ventas.IdSucursal', $idSucursal)
            ->groupBy(DB::raw("ventas.IdVentas"))
            ->orderBy('ventas.FechaCreacion','desc')
            ->get();
            return $ventas;*/

            $resultado = DB::table('ventas_articulo')
                ->join('ventas', 'ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
                ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                ->select('ventas.*', 'ventas_articulo.IdArticulo as IdArticulo', 'articulo.Descripcion', 'usuario.Nombre as Usuario', 'cliente.NumeroDocumento as Documento', 'cliente.Nombre as Nombres')
                ->where('articulo.IdSucursal', $idSucursal)
                ->groupBy(DB::raw("ventas.IdVentas"))
                ->orderBy('ventas.FechaCreacion', 'desc')
                ->get();
            return $resultado;

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getProductosVendidos($idVentas)
    {
        try {
            $productos = DB::table('ventas_articulo')
                ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                ->select('articulo.Descripcion as Articulo', 'ventas_articulo.Detalle', 'articulo.Precio', 'ventas_articulo.Cantidad')
                ->where('ventas_articulo.IdVentas', $idVentas)
                ->get();
            return $productos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getVentasProductosFiltrados($idSucursal, $producto, $tipoPago, $fecha, $fechaIni, $fechaFin)
    {
        try {
            //$fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            if ($producto == 0) {
                if ($tipoPago == 0) {
                    $resultado = DB::table('ventas_articulo')
                        ->join('ventas', 'ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
                        ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                        ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                        ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                        ->select('ventas.*', 'ventas_articulo.IdArticulo as IdArticulo', 'articulo.Descripcion', 'usuario.Nombre as Usuario', 'cliente.Nombre as Nombres', 'cliente.NumeroDocumento as Documento')
                        ->where('articulo.IdSucursal', $idSucursal)
                        ->whereBetween('ventas.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw("ventas.IdVentas"))
                        ->get();
                    return $resultado;
                } else {
                    $resultado = DB::table('ventas_articulo')
                        ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                        ->join('ventas', 'ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
                        ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                        ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                        ->select('ventas.*', 'ventas_articulo.IdArticulo as IdArticulo', 'articulo.Descripcion', 'usuario.Nombre as Usuario', 'cliente.Nombre as Nombres', 'cliente.NumeroDocumento as Documento')

                        ->where('articulo.IdSucursal', $idSucursal)
                        ->where('ventas.IdTipoPago', $tipoPago)
                        ->whereBetween('ventas.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw("ventas.IdVentas"))
                        ->get();
                    return $resultado;
                }
            } else {
                if ($tipoPago == 0) {
                    $resultado = DB::table('ventas_articulo')
                        ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                        ->join('ventas', 'ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
                        ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                        ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                        ->select('ventas.*', 'ventas_articulo.IdArticulo as IdArticulo', 'articulo.Descripcion', 'usuario.Nombre as Usuario', 'cliente.Nombre as Nombres', 'cliente.NumeroDocumento as Documento')
                        ->where('articulo.IdSucursal', $idSucursal)
                        ->where('articulo.IdArticulo', $producto)
                        ->whereBetween('ventas.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw("ventas.IdVentas"))
                        ->get();
                    return $resultado;
                } else {
                    $resultado = DB::table('ventas_articulo')
                        ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                        ->join('ventas', 'ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
                        ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                        ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                        ->select('ventas.*', 'ventas_articulo.IdArticulo as IdArticulo', 'articulo.Descripcion', 'usuario.Nombre as Usuario', 'cliente.Nombre as Nombres', 'cliente.NumeroDocumento as Documento')

                        ->where('articulo.IdSucursal', $idSucursal)
                        ->where('articulo.IdArticulo', $producto)
                        ->where('ventas.IdTipoPago', $tipoPago)
                        ->whereBetween('ventas.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw("ventas.IdVentas"))
                        ->get();
                    return $resultado;
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // public function grafgetFechasFiltrados($idSucursal, $producto, $fecha, $fechaIni, $fechaFin)
    // {
    //     try {

    //         if ($fecha == 0) {
    //             $resultado = DB::table('ventas as v')
    //                 ->join('ventas_articulo as va', 'v.IdVentas', '=', 'va.IdVentas')
    //                 ->join('articulo as a', 'va.IdArticulo', '=', 'a.IdArticulo')
    //                 ->where('v.IdSucursal', $idSucursal)
    //                 ->select(DB::raw('count(va.Cantidad) as total, a.Descripcion, YEAR(v.FechaCreacion), count(Descripcion) as cantDescripcion'))
    //                 ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
    //                 ->groupBy(DB::raw("va.IdArticulo"))
    //                 ->orderBy('total', 'desc')
    //                 ->limit(25)
    //                 ->get();
    //             return $resultado;
    //         } elseif ($fecha == 4) {
    //             $resultado = DB::table('ventas as v')
    //                 ->join('ventas_articulo as va', 'v.IdVentas', '=', 'va.IdVentas')
    //                 ->join('articulo as a', 'va.IdArticulo', '=', 'a.IdArticulo')
    //                 ->where('v.IdSucursal', $idSucursal)
    //                 ->select(DB::raw('count(va.Cantidad) as total, MONTH(v.FechaCreacion)'))
    //                 ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
    //                 ->groupBy(DB::raw("va.IdArticulo"))
    //                 ->orderBy('total', 'desc')
    //                 ->limit(25)
    //                 ->get();
    //             return $resultado;
    //         }

    //     } catch (Exception $ex) {
    //         echo $ex->getMessage();
    //     }
    // }

    public function grafgetVentasProductosFiltrados($idSucursal, $producto, $tipoPago, $fecha, $fechaIni, $fechaFin)
    {
        try {
            //$fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            if ($producto == 0) {
                if ($tipoPago == 0) {

                    $resultado = DB::table('ventas as v')
                        ->join('ventas_articulo as va', 'v.IdVentas', '=', 'va.IdVentas')
                        ->join('articulo as a', 'va.IdArticulo', '=', 'a.IdArticulo')
                        ->where('v.IdSucursal', $idSucursal)
                        ->select(DB::raw('count(va.Cantidad) as total, a.Descripcion, v.FechaCreacion, count(Descripcion) as cantDescripcion'))
                        ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw("va.IdArticulo"))
                        ->orderBy('total', 'desc')
                        ->limit(25)
                        ->get();

                    /* $resultado = DB::table('ventas_articulo')
                    ->join('ventas','ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
                    ->join('usuario','ventas.IdCreacion', '=', 'usuario.IdUsuario')
                    ->join('cliente','ventas.IdCliente', '=', 'cliente.IdCliente')
                    ->join('articulo','ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                    ->select('ventas.*','ventas_articulo.IdArticulo as IdArticulo', 'articulo.Descripcion', 'usuario.Nombre as Usuario', 'cliente.Nombre as Nombres')
                    ->where('articulo.IdSucursal',$idSucursal)
                    ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->groupBy(DB::raw("ventas.IdVentas"))
                    ->get(); */
                    return $resultado;
                } else {

                    $resultado = DB::table('ventas as v')
                        ->join('ventas_articulo as va', 'v.IdVentas', '=', 'va.IdVentas')
                        ->join('articulo as a', 'va.IdArticulo', '=', 'a.IdArticulo')
                        ->select(DB::raw('count(va.Cantidad) as total, a.Descripcion , v.FechaCreacion, count(Descripcion) as cantDescripcion'))
                        ->where('v.IdTipoPago', $tipoPago)
                        ->where('v.IdSucursal', $idSucursal)
                        ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw("va.IdArticulo"))
                        ->orderBy('total', 'desc')
                        ->limit(25)
                        ->get();

                    /* $resultado = DB::table('ventas_articulo')
                    ->join('articulo','ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                    ->join('ventas','ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
                    ->join('usuario','ventas.IdCreacion', '=', 'usuario.IdUsuario')
                    ->join('cliente','ventas.IdCliente', '=', 'cliente.IdCliente')
                    ->select('ventas.*','ventas_articulo.IdArticulo as IdArticulo', 'articulo.Descripcion', 'usuario.Nombre as Usuario', 'cliente.Nombre as Nombres')
                    ->where('articulo.IdSucursal',$idSucursal)
                    ->where('ventas.IdTipoPago', $tipoPago)
                    ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->groupBy(DB::raw("ventas.IdVentas"))
                    ->get(); */
                    return $resultado;
                }
            } else {
                if ($tipoPago == 0) {
                    $resultado = DB::table('ventas as v')
                        ->join('ventas_articulo as va', 'v.IdVentas', '=', 'va.IdVentas')
                        ->join('articulo as a', 'va.IdArticulo', '=', 'a.IdArticulo')
                        ->select(DB::raw('count(va.Cantidad) as total, a.Descripcion , v.FechaCreacion, count(Descripcion) as cantDescripcion'))
                        ->where('a.IdArticulo', $producto)
                        ->where('v.IdSucursal', $idSucursal)
                        ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw("va.IdArticulo"))
                        ->orderBy('total', 'desc')
                        ->get();
                    /* $resultado = DB::table('ventas_articulo')
                    ->join('articulo','ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                    ->join('ventas','ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
                    ->join('usuario','ventas.IdCreacion', '=', 'usuario.IdUsuario')
                    ->join('cliente','ventas.IdCliente', '=', 'cliente.IdCliente')
                    ->select('ventas.*','ventas_articulo.IdArticulo as IdArticulo', 'articulo.Descripcion', 'usuario.Nombre as Usuario', 'cliente.Nombre as Nombres')
                    ->where('articulo.IdSucursal',$idSucursal)
                    ->where('articulo.Descripcion', 'like', '%'.$producto.'%')
                    ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->groupBy(DB::raw("ventas.IdVentas"))
                    ->get(); */
                    return $resultado;
                } else {
                    $resultado = DB::table('ventas as v')
                        ->join('ventas_articulo as va', 'v.IdVentas', '=', 'va.IdVentas')
                        ->join('articulo as a', 'va.IdArticulo', '=', 'a.IdArticulo')
                        ->select(DB::raw('count(va.Cantidad) as total, a.Descripcion ,v.FechaCreacion, count(Descripcion) as cantDescripcion'))
                        ->where('a.IdArticulo', $producto)
                        ->where('v.IdSucursal', $idSucursal)
                        ->where('v.IdTipoPago', $tipoPago)
                        ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw("va.IdArticulo"))
                        ->orderBy('total', 'desc')
                        ->get();
                    /* $resultado = DB::table('ventas_articulo')
                    ->join('articulo','ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                    ->join('ventas','ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
                    ->join('usuario','ventas.IdCreacion', '=', 'usuario.IdUsuario')
                    ->join('cliente','ventas.IdCliente', '=', 'cliente.IdCliente')
                    ->select('ventas.*','ventas_articulo.IdArticulo as IdArticulo', 'articulo.Descripcion', 'usuario.Nombre as Usuario', 'cliente.Nombre as Nombres')
                    ->where('articulo.IdSucursal',$idSucursal)
                    ->where('articulo.Descripcion', 'like', '%'.$producto.'%')
                    ->where('ventas.IdTipoPago', $tipoPago)
                    ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->groupBy(DB::raw("ventas.IdVentas"))
                    ->get(); */
                    return $resultado;
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getVentasClientes($idSucursal)
    {
        try {
            $ventas = DB::table('ventas')
                ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                ->select('ventas.*', 'cliente.Nombre as Nombres', 'usuario.Nombre as Usuario')
                ->where('ventas.IdSucursal', $idSucursal)
                ->orderBy('ventas.FechaCreacion', 'desc')
                ->get();
            return $ventas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getVentasClientesFiltrados($idSucursal, $cliente, $tipoPago, $fecha, $fechaIni, $fechaFin)
    {
        try {
            $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            if ($cliente == null) {
                if ($tipoPago == 0) {
                    $ventas = DB::table('ventas')
                        ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                        ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                        ->select('ventas.*', 'cliente.Nombre as Nombres', 'usuario.Nombre as Usuario')
                        ->where('ventas.IdSucursal', $idSucursal)
                        ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                        ->orderBy('IdVentas', 'desc')
                        ->get();
                    return $ventas;
                } else {
                    $ventas = DB::table('ventas')
                        ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                        ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                        ->select('ventas.*', 'cliente.Nombre as Nombres', 'usuario.Nombre as Usuario')
                        ->where('ventas.IdSucursal', $idSucursal)
                        ->where('ventas.IdTipoPago', $tipoPago)
                        ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                        ->orderBy('IdVentas', 'desc')
                        ->get();
                    return $ventas;
                }
            } else {
                if ($tipoPago == 0) {
                    $ventas = DB::table('ventas')
                        ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                        ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                        ->select('ventas.*', 'cliente.Nombre as Nombres', 'usuario.Nombre as Usuario')
                        ->where('ventas.IdSucursal', $idSucursal)
                        ->where('cliente.Nombre', $cliente)
                        ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                        ->orderBy('IdVentas', 'desc')
                        ->get();
                    return $ventas;

                } else {
                    $ventas = DB::table('ventas')
                        ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                        ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                        ->select('ventas.*', 'cliente.Nombre as Nombres', 'usuario.Nombre as Usuario')
                        ->where('ventas.IdSucursal', $idSucursal)
                        ->where('cliente.Nombre', $cliente)
                        ->where('ventas.IdTipoPago', $tipoPago)
                        ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                        ->orderBy('IdVentas', 'desc')
                        ->get();
                    return $ventas;
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function grafgetVentasClientesFiltradosFechas($idSucursal, $cliente, $tipoPago, $fecha, $fechaIni, $fechaFin, $diferencia, $tipoMoneda)
    {
        try {
            if (($fecha >= 1 && $fecha <= 6) || ($fecha == 9 && $diferencia <= 31)) {
                $condicion = 'MONTH(v.FechaCreacion), DAY(FechaCreacion)';
            }
            if (($fecha == 7 || $fecha == 8 || $fecha == 0 || $fecha == 10) || ($fecha == 9 && $diferencia > 31)) {
                $condicion = 'YEAR(v.FechaCreacion), MONTH(FechaCreacion)';
            }
            // $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            if ($cliente == 0) {
                if ($tipoPago == 0) {

                    $clientes = DB::table('ventas as v')
                        ->join('cliente', 'v.IdCliente', '=', 'cliente.IdCliente')
                        ->where('v.IdSucursal', $idSucursal)
                        ->select(DB::raw('count(*) as total,v.FechaCreacion, cliente.Nombre , DAY(v.FechaCreacion) as dia, MONTH(v.FechaCreacion) as mes, YEAR(v.FechaCreacion) AS anio, v.IdTipoMoneda'))
                        ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
                    // ->whereBetween('v.FechaCreacion', [$fechas[0], $fechas[1]])
                    // ->groupBy(DB::raw("cliente.Nombre"))
                        ->where('v.IdTipoMoneda', $tipoMoneda)
                        ->groupBy(DB::raw($condicion))
                        ->orderBy(DB::raw($condicion), 'asc')
                        ->limit(10)
                        ->get();

                    /* $ventas= DB::table('ventas')
                    ->join('cliente','ventas.IdCliente', '=', 'cliente.IdCliente')
                    ->join('sucursal','ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->join('usuario','ventas.IdCreacion', '=', 'usuario.IdUsuario')
                    ->select('ventas.*', 'cliente.Nombre as Nombres', 'usuario.Nombre as Usuario')
                    ->where('ventas.IdSucursal', $idSucursal)
                    ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->orderBy('IdVentas','desc')
                    ->get(); */
                    return $clientes;
                } else {
                    $clientes = DB::table('ventas as v')
                        ->join('cliente', 'v.IdCliente', '=', 'cliente.IdCliente')
                        ->where('v.IdSucursal', $idSucursal)
                        ->where('v.IdTipoPago', $tipoPago)
                        ->select(DB::raw('count(*) as total,v.FechaCreacion, cliente.Nombre , DAY(v.FechaCreacion) as dia, MONTH(v.FechaCreacion) as mes, YEAR(v.FechaCreacion) AS anio, v.IdTipoMoneda'))
                        ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
                    // ->groupBy(DB::raw("cliente.Nombre"))
                        ->where('v.IdTipoMoneda', $tipoMoneda)
                        ->groupBy(DB::raw($condicion))
                        ->orderBy(DB::raw($condicion), 'asc')
                        ->limit(10)
                        ->get();

                    /* $ventas= DB::table('ventas')
                    ->join('cliente','ventas.IdCliente', '=', 'cliente.IdCliente')
                    ->join('sucursal','ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->join('usuario','ventas.IdCreacion', '=', 'usuario.IdUsuario')
                    ->select('ventas.*', 'cliente.Nombre as Nombres', 'usuario.Nombre as Usuario')
                    ->where('ventas.IdSucursal', $idSucursal)
                    ->where('ventas.IdTipoPago', $tipoPago)
                    ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->orderBy('IdVentas','desc')
                    ->get(); */
                    return $clientes;
                }
            } else {
                if ($tipoPago == 0) {

                    $clientes = DB::table('ventas as v')
                        ->join('cliente', 'v.IdCliente', '=', 'cliente.IdCliente')
                        ->select(DB::raw('count(*) as total,v.FechaCreacion, cliente.Nombre , DAY(v.FechaCreacion) as dia, MONTH(v.FechaCreacion) as mes, YEAR(v.FechaCreacion) AS anio, v.IdTipoMoneda'))
                        ->where('v.IdSucursal', $idSucursal)
                        ->where('cliente.IdCliente', $cliente)
                        ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
                    // ->groupBy(DB::raw("cliente.Nombre"))
                        ->where('v.IdTipoMoneda', $tipoMoneda)
                        ->groupBy(DB::raw($condicion))
                        ->orderBy(DB::raw($condicion), 'asc')
                        ->limit(10)
                        ->get();
                    /* $ventas= DB::table('ventas')
                    ->join('cliente','ventas.IdCliente', '=', 'cliente.IdCliente')
                    ->join('sucursal','ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->join('usuario','ventas.IdCreacion', '=', 'usuario.IdUsuario')
                    ->select('ventas.*', 'cliente.Nombre as Nombres',  'usuario.Nombre as Usuario')
                    ->where('ventas.IdSucursal', $idSucursal)
                    ->where('cliente.Nombre', $cliente)
                    ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->orderBy('IdVentas','desc')
                    ->get(); */
                    return $clientes;

                } else {

                    $clientes = DB::table('ventas as v')
                        ->join('cliente', 'v.IdCliente', '=', 'cliente.IdCliente')
                        ->where('v.IdSucursal', $idSucursal)
                        ->where('cliente.IdCliente', $cliente)
                        ->where('v.IdTipoPago', $tipoPago)
                        ->select(DB::raw('count(*) as total,v.FechaCreacion, cliente.Nombre , DAY(v.FechaCreacion) as dia, MONTH(v.FechaCreacion) as mes, YEAR(v.FechaCreacion) AS anio, v.IdTipoMoneda'))
                        ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
                    // ->groupBy(DB::raw("cliente.Nombre"))
                        ->where('v.IdTipoMoneda', $tipoMoneda)
                        ->groupBy(DB::raw($condicion))
                        ->orderBy(DB::raw($condicion), 'asc')
                        ->limit(10)
                        ->get();

                    /* $ventas= DB::table('ventas')
                    ->join('cliente','ventas.IdCliente', '=', 'cliente.IdCliente')
                    ->join('sucursal','ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->join('usuario','ventas.IdCreacion', '=', 'usuario.IdUsuario')
                    ->select('ventas.*', 'cliente.Nombre as Nombres', 'usuario.Nombre as Usuario')
                    ->where('ventas.IdSucursal', $idSucursal)
                    ->where('cliente.Nombre', $cliente)
                    ->where('ventas.IdTipoPago', $tipoPago)
                    ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->orderBy('IdVentas','desc')
                    ->get(); */
                    return $clientes;
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function grafgetVentasClientesFiltrados($idSucursal, $cliente, $tipoPago, $fecha, $fechaIni, $fechaFin)
    {
        try {
            // $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            if ($cliente == 0) {
                if ($tipoPago == 0) {

                    $clientes = DB::table('ventas as v')
                        ->join('cliente', 'v.IdCliente', '=', 'cliente.IdCliente')
                        ->where('v.IdSucursal', $idSucursal)
                        ->select(DB::raw('count(*) as total, cliente.Nombre '))
                    // ->whereBetween('v.FechaCreacion', [$fechas[0], $fechas[1]])
                        ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw("cliente.Nombre"))
                        ->limit(12)
                        ->get();

                    /* $ventas= DB::table('ventas')
                    ->join('cliente','ventas.IdCliente', '=', 'cliente.IdCliente')
                    ->join('sucursal','ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->join('usuario','ventas.IdCreacion', '=', 'usuario.IdUsuario')
                    ->select('ventas.*', 'cliente.Nombre as Nombres', 'usuario.Nombre as Usuario')
                    ->where('ventas.IdSucursal', $idSucursal)
                    ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->orderBy('IdVentas','desc')
                    ->get(); */
                    return $clientes;
                } else {
                    $clientes = DB::table('ventas as v')
                        ->join('cliente', 'v.IdCliente', '=', 'cliente.IdCliente')
                        ->where('v.IdSucursal', $idSucursal)
                        ->where('v.IdTipoPago', $tipoPago)
                        ->select(DB::raw('count(*) as total, cliente.Nombre '))
                        ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw("cliente.Nombre"))
                        ->limit(12)
                        ->get();

                    /* $ventas= DB::table('ventas')
                    ->join('cliente','ventas.IdCliente', '=', 'cliente.IdCliente')
                    ->join('sucursal','ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->join('usuario','ventas.IdCreacion', '=', 'usuario.IdUsuario')
                    ->select('ventas.*', 'cliente.Nombre as Nombres', 'usuario.Nombre as Usuario')
                    ->where('ventas.IdSucursal', $idSucursal)
                    ->where('ventas.IdTipoPago', $tipoPago)
                    ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->orderBy('IdVentas','desc')
                    ->get(); */
                    return $clientes;
                }
            } else {
                if ($tipoPago == 0) {

                    $clientes = DB::table('ventas as v')
                        ->join('cliente', 'v.IdCliente', '=', 'cliente.IdCliente')
                        ->select(DB::raw('count(*) as total, cliente.Nombre '))
                        ->where('v.IdSucursal', $idSucursal)
                        ->where('cliente.IdCliente', $cliente)
                        ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw("cliente.Nombre"))
                        ->limit(12)
                        ->get();
                    /* $ventas= DB::table('ventas')
                    ->join('cliente','ventas.IdCliente', '=', 'cliente.IdCliente')
                    ->join('sucursal','ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->join('usuario','ventas.IdCreacion', '=', 'usuario.IdUsuario')
                    ->select('ventas.*', 'cliente.Nombre as Nombres',  'usuario.Nombre as Usuario')
                    ->where('ventas.IdSucursal', $idSucursal)
                    ->where('cliente.Nombre', $cliente)
                    ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->orderBy('IdVentas','desc')
                    ->get(); */
                    return $clientes;

                } else {

                    $clientes = DB::table('ventas as v')
                        ->join('cliente', 'v.IdCliente', '=', 'cliente.IdCliente')
                        ->where('v.IdSucursal', $idSucursal)
                        ->where('cliente.IdCliente', $cliente)
                        ->where('v.IdTipoPago', $tipoPago)
                        ->select(DB::raw('count(*) as total, cliente.Nombre '))
                        ->whereBetween('v.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw("cliente.Nombre"))
                        ->limit(12)
                        ->get();

                    /* $ventas= DB::table('ventas')
                    ->join('cliente','ventas.IdCliente', '=', 'cliente.IdCliente')
                    ->join('sucursal','ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->join('usuario','ventas.IdCreacion', '=', 'usuario.IdUsuario')
                    ->select('ventas.*', 'cliente.Nombre as Nombres', 'usuario.Nombre as Usuario')
                    ->where('ventas.IdSucursal', $idSucursal)
                    ->where('cliente.Nombre', $cliente)
                    ->where('ventas.IdTipoPago', $tipoPago)
                    ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->orderBy('IdVentas','desc')
                    ->get(); */
                    return $clientes;
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getComprasProductos($idSucursal)
    {
        try {
            /*$compras= DB::table('compras')
            ->join('proveedor','compras.IdProveedor', '=', 'proveedor.IdProveedor')
            ->join('sucursal','compras.IdSucursal', '=', 'sucursal.IdSucursal')
            ->join('usuario','compras.IdCreacion', '=', 'usuario.IdUsuario')
            ->join('compras_articulo','compras.IdCompras', '=', 'compras_articulo.IdCompras')
            ->select('compras.*', 'proveedor.Nombre as Nombres', 'usuario.Nombre as Usuario')
            ->where('compras.IdSucursal', $idSucursal)
            ->groupBy(DB::raw("compras.IdCompras"))
            ->orderBy('IdCompras','desc')
            ->get();*/

            $compras = DB::table('compras_articulo')
                ->join('compras', 'compras_articulo.IdCompras', '=', 'compras.IdCompras')
                ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
                ->join('sucursal', 'compras.IdSucursal', '=', 'sucursal.IdSucursal')
                ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
                ->join('articulo', 'compras_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                ->select('compras.*', 'compras_articulo.IdArticulo', 'articulo.Descripcion', 'usuario.Nombre as Usuario', 'proveedor.Nombre as Nombres')
                ->where('articulo.IdSucursal', $idSucursal)
                ->groupBy(DB::raw("compras.IdCompras"))
                ->orderBy('IdCompras', 'desc')
                ->get();
            return $compras;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getProductosComprados($idCompras)
    {
        try {
            $productos = DB::table('compras_articulo')
                ->join('articulo', 'compras_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                ->select('articulo.Descripcion as Articulo', 'articulo.Costo')
                ->where('compras_articulo.IdCompras', $idCompras)
                ->get();
            return $productos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getProductosCompradosCostos($idCompras)
    {
        try {
            $productos = DB::table('compras_articulo')
                ->join('articulo', 'compras_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                ->select('compras_articulo.IdCompras', DB::raw('Sum(articulo.Costo) As sumaCosto'))
                ->where('compras_articulo.IdCompras', $idCompras)
                ->get();
            return $productos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

// --------------------------------PROVEEEDORES
    public function getComprasProductosFiltrados($idSucursal, $producto, $tipoPago, $fecha, $fechaIni, $fechaFin, $agruparPor)
    {
        try {
            //$fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            if ($producto == 0) {
                if ($tipoPago == 0) {
                    $compras = DB::table('compras_articulo')
                        ->join('articulo', 'compras_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                        ->join('compras', 'compras_articulo.IdCompras', '=', 'compras.IdCompras')
                        ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
                        ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
                    // Agrege en el Select el atributo -->NumeroDocumento
                        ->select('compras.*', 'proveedor.Nombre as Nombres', 'proveedor.NumeroDocumento', 'articulo.Descripcion', 'usuario.Nombre as Usuario', 'compras_articulo.PrecioCosto', 'compras_articulo.Cantidad', 'articulo.IdArticulo', DB::raw("DATE_FORMAT( compras.FechaCreacion,  '%d-%m-%Y') AS fechaCompras,SUM(compras_articulo.Cantidad) AS totalProductos "))
                        ->where('compras.IdSucursal', $idSucursal)
                        ->whereBetween('compras.FechaCreacion', [$fechaIni, $fechaFin])
                    // ->groupBy(DB::raw("compras_articulo.IdArticulo"))
                        ->groupBy(DB::raw($agruparPor))
                        ->get();
                    return $compras;
                } else {
                    $compras = DB::table('compras_articulo')
                        ->join('articulo', 'compras_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                        ->join('compras', 'compras_articulo.IdCompras', '=', 'compras.IdCompras')
                        ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
                        ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
                    // Agrege en el Select el atributo -->NumeroDocumento
                        ->select('compras.*', 'proveedor.Nombre as Nombres', 'proveedor.NumeroDocumento', 'articulo.Descripcion', 'usuario.Nombre as Usuario', 'compras_articulo.PrecioCosto', 'compras_articulo.Cantidad', 'articulo.IdArticulo', DB::raw("DATE_FORMAT( compras.FechaCreacion,  '%d-%m-%Y') AS fechaCompras,SUM(compras_articulo.Cantidad) AS totalProductos "))
                        ->where('compras.IdSucursal', $idSucursal)
                        ->where('compras.IdTipoPago', $tipoPago)
                        ->whereBetween('compras.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw($agruparPor))
                        ->get();
                    return $compras;
                }
            } else {
                if ($tipoPago == 0) {
                    $compras = DB::table('compras_articulo')
                        ->join('articulo', 'compras_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                        ->join('compras', 'compras_articulo.IdCompras', '=', 'compras.IdCompras')
                        ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
                        ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
                    // Agrege en el Select el atributo -->NumeroDocumento
                        ->select('compras.*', 'proveedor.Nombre as Nombres', 'proveedor.NumeroDocumento', 'articulo.Descripcion', 'usuario.Nombre as Usuario', 'compras_articulo.PrecioCosto', 'compras_articulo.Cantidad', 'articulo.IdArticulo', DB::raw("DATE_FORMAT( compras.FechaCreacion,  '%d-%m-%Y') AS fechaCompras,SUM(compras_articulo.Cantidad) AS totalProductos "))
                        ->where('compras.IdSucursal', $idSucursal)
                        ->where('articulo.IdArticulo', $producto)
                        ->whereBetween('compras.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw($agruparPor))
                        ->get();
                    return $compras;
                } else {
                    $compras = DB::table('compras_articulo')
                        ->join('articulo', 'compras_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                        ->join('compras', 'compras_articulo.IdCompras', '=', 'compras.IdCompras')
                        ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
                        ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
                    // Agrege en el Select el atributo -->NumeroDocumento
                        ->select('compras.*', 'proveedor.Nombre as Nombres', 'proveedor.NumeroDocumento', 'articulo.Descripcion', 'usuario.Nombre as Usuario', 'compras_articulo.PrecioCosto', 'compras_articulo.Cantidad', 'articulo.IdArticulo', DB::raw("DATE_FORMAT( compras.FechaCreacion,  '%d-%m-%Y') AS fechaCompras,SUM(compras_articulo.Cantidad) AS totalProductos "))
                        ->where('compras.IdSucursal', $idSucursal)
                        ->where('articulo.Descripcion', $producto)
                        ->where('compras.IdTipoPago', $tipoPago)
                        ->whereBetween('compras.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw($agruparPor))
                        ->get();
                    return $compras;
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getComprasProductosFiltradosExcel($idSucursal, $producto, $tipoPago, $fecha, $fechaIni, $fechaFin)
    {
        try {
            // $loadDatos = new DatosController();
            // $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            if ($producto == 0) {
                if ($tipoPago == 0) {
                    $compras = DB::table('compras_articulo')
                        ->join('articulo', 'compras_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                        ->join('compras', 'compras_articulo.IdCompras', '=', 'compras.IdCompras')
                        ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
                        ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
                    // Agrege en el Select el atributo -->NumeroDocumento
                        ->select('compras.*', 'proveedor.Nombre as Nombres', 'proveedor.NumeroDocumento', 'compras_articulo.PrecioCosto', 'compras_articulo.Cantidad', 'articulo.Descripcion', 'usuario.Nombre as Usuario')
                        ->where('compras.IdSucursal', $idSucursal)
                        ->whereBetween('compras.FechaCreacion', [$fechaIni[0], $fechaFin[1]])
                    // ->whereBetween('compras.FechaCreacion', [$fechas[0], $fechas[1]])
                        ->get();
                    return $compras;
                } else {
                    $compras = DB::table('compras_articulo')
                        ->join('articulo', 'compras_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                        ->join('compras', 'compras_articulo.IdCompras', '=', 'compras.IdCompras')
                        ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
                        ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
                    // Agrege en el Select el atributo -->NumeroDocumento
                        ->select('compras.*', 'proveedor.Nombre as Nombres', 'proveedor.NumeroDocumento', 'compras_articulo.PrecioCosto', 'compras_articulo.Cantidad', 'articulo.Descripcion', 'usuario.Nombre as Usuario')
                        ->where('compras.IdSucursal', $idSucursal)
                        ->where('compras.IdTipoPago', $tipoPago)
                        ->whereBetween('compras.FechaCreacion', [$fechaIni[0], $fechaFin[1]])
                    // ->whereBetween('compras.FechaCreacion', [$fechas[0], $fechas[1]])
                        ->get();
                    return $compras;
                }
            } else {
                if ($tipoPago == 0) {
                    $compras = DB::table('compras_articulo')
                        ->join('articulo', 'compras_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                        ->join('compras', 'compras_articulo.IdCompras', '=', 'compras.IdCompras')
                        ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
                        ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
                    // Agrege en el Select el atributo -->NumeroDocumento
                        ->select('compras.*', 'proveedor.Nombre as Nombres', 'proveedor.NumeroDocumento', 'compras_articulo.PrecioCosto', 'compras_articulo.Cantidad', 'articulo.Descripcion', 'usuario.Nombre as Usuario')
                        ->where('compras.IdSucursal', $idSucursal)
                        ->where('articulo.IdArticulo', $producto)
                        ->whereBetween('compras.FechaCreacion', [$fechaIni[0], $fechaFin[1]])
                    // ->whereBetween('compras.FechaCreacion', [$fechas[0], $fechas[1]])
                        ->get();
                    return $compras;
                } else {
                    $compras = DB::table('compras_articulo')
                        ->join('articulo', 'compras_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                        ->join('compras', 'compras_articulo.IdCompras', '=', 'compras.IdCompras')
                        ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
                        ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
                    // Agrege en el Select el atributo -->NumeroDocumento
                        ->select('compras.*', 'proveedor.Nombre as Nombres', 'proveedor.NumeroDocumento', 'compras_articulo.PrecioCosto', 'compras_articulo.Cantidad', 'articulo.Descripcion', 'usuario.Nombre as Usuario')
                        ->where('compras.IdSucursal', $idSucursal)
                        ->where('articulo.Descripcion', $producto)
                        ->where('compras.IdTipoPago', $tipoPago)
                        ->whereBetween('compras.FechaCreacion', [$fechaIni[0], $fechaFin[1]])
                    // ->whereBetween('compras.FechaCreacion', [$fechas[0], $fechas[1]])
                        ->get();
                    return $compras;
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // public function getComprasProductosFiltrados($idSucursal, $producto, $tipoPago, $fecha, $fechaIni, $fechaFin)
    // {
    //     try {
    //         //$fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
    //         if ($producto == 0) {
    //             if ($tipoPago == 0) {
    //                 $compras = DB::table('compras_articulo')
    //                     ->join('articulo', 'compras_articulo.IdArticulo', '=', 'articulo.IdArticulo')
    //                     ->join('compras', 'compras_articulo.IdCompras', '=', 'compras.IdCompras')
    //                     ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
    //                     ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
    //                 // Agrege en el Select el atributo -->NumeroDocumento
    //                     ->select('compras.*', 'proveedor.Nombre as Nombres', 'proveedor.NumeroDocumento', 'articulo.Descripcion', 'usuario.Nombre as Usuario')
    //                     ->where('compras.IdSucursal', $idSucursal)
    //                     ->whereBetween('compras.FechaCreacion', [$fechaIni, $fechaFin])
    //                     ->groupBy(DB::raw("compras.IdCompras"))
    //                     ->get();
    //                 return $compras;
    //             } else {
    //                 $compras = DB::table('compras_articulo')
    //                     ->join('articulo', 'compras_articulo.IdArticulo', '=', 'articulo.IdArticulo')
    //                     ->join('compras', 'compras_articulo.IdCompras', '=', 'compras.IdCompras')
    //                     ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
    //                     ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
    //                 // Agrege en el Select el atributo -->NumeroDocumento
    //                     ->select('compras.*', 'proveedor.Nombre as Nombres', 'proveedor.NumeroDocumento', 'articulo.Descripcion', 'usuario.Nombre as Usuario')
    //                     ->where('compras.IdSucursal', $idSucursal)
    //                     ->where('compras.IdTipoPago', $tipoPago)
    //                     ->whereBetween('compras.FechaCreacion', [$fechaIni, $fechaFin])
    //                     ->groupBy(DB::raw("compras.IdCompras"))
    //                     ->get();
    //                 return $compras;
    //             }
    //         } else {
    //             if ($tipoPago == 0) {
    //                 $compras = DB::table('compras_articulo')
    //                     ->join('articulo', 'compras_articulo.IdArticulo', '=', 'articulo.IdArticulo')
    //                     ->join('compras', 'compras_articulo.IdCompras', '=', 'compras.IdCompras')
    //                     ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
    //                     ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
    //                 // Agrege en el Select el atributo -->NumeroDocumento
    //                     ->select('compras.*', 'proveedor.Nombre as Nombres', 'proveedor.NumeroDocumento', 'articulo.Descripcion', 'usuario.Nombre as Usuario')
    //                     ->where('compras.IdSucursal', $idSucursal)
    //                     ->where('articulo.IdArticulo', $producto)
    //                     ->whereBetween('compras.FechaCreacion', [$fechaIni, $fechaFin])
    //                     ->groupBy(DB::raw("compras.IdCompras"))
    //                     ->get();
    //                 return $compras;
    //             } else {
    //                 $compras = DB::table('compras_articulo')
    //                     ->join('articulo', 'compras_articulo.IdArticulo', '=', 'articulo.IdArticulo')
    //                     ->join('compras', 'compras_articulo.IdCompras', '=', 'compras.IdCompras')
    //                     ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
    //                     ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
    //                 // Agrege en el Select el atributo -->NumeroDocumento
    //                     ->select('compras.*', 'proveedor.Nombre as Nombres', 'proveedor.NumeroDocumento', 'articulo.Descripcion', 'usuario.Nombre as Usuario')
    //                     ->where('compras.IdSucursal', $idSucursal)
    //                     ->where('articulo.Descripcion', $producto)
    //                     ->where('compras.IdTipoPago', $tipoPago)
    //                     ->whereBetween('compras.FechaCreacion', [$fechaIni, $fechaFin])
    //                     ->groupBy(DB::raw("compras.IdCompras"))
    //                     ->get();
    //                 return $compras;
    //             }
    //         }
    //     } catch (Exception $ex) {
    //         echo $ex->getMessage();
    //     }
    // }

    public function getComprasProveedores($idSucursal)
    {
        try {
            $compras = DB::table('compras')
                ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
                ->join('sucursal', 'compras.IdSucursal', '=', 'sucursal.IdSucursal')
                ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
                ->select('compras.*', 'proveedor.Nombre as Nombres', 'usuario.Nombre as Usuario')
                ->where('compras.IdSucursal', $idSucursal)
                ->orderBy('compras.FechaCreacion', 'desc')
                ->get();
            return $compras;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getComprasProveedorFiltrados($idSucursal, $proveedor, $tipoPago, $fecha, $fechaIni, $fechaFin)
    {
        try {
            $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            if ($proveedor == null) {
                if ($tipoPago == 0) {
                    $compras = DB::table('compras')
                        ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
                        ->join('sucursal', 'compras.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
                        ->select('compras.*', 'proveedor.Nombre as Nombres', 'usuario.Nombre as Usuario')
                        ->where('compras.IdSucursal', $idSucursal)
                        ->whereBetween('compras.FechaCreacion', [$fechas[0], $fechas[1]])
                        ->orderBy('compras.FechaCreacion', 'desc')
                        ->get();
                    return $compras;
                } else {
                    $compras = DB::table('compras')
                        ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
                        ->join('sucursal', 'compras.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
                        ->select('compras.*', 'proveedor.Nombre as Nombres', 'usuario.Nombre as Usuario')
                        ->where('compras.IdSucursal', $idSucursal)
                        ->where('compras.IdTipoPago', $tipoPago)
                        ->whereBetween('compras.FechaCreacion', [$fechas[0], $fechas[1]])
                        ->orderBy('compras.FechaCreacion', 'desc')
                        ->get();
                    return $compras;
                }
            } else {
                if ($tipoPago == 0) {
                    $compras = DB::table('compras')
                        ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
                        ->join('sucursal', 'compras.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
                        ->select('compras.*', 'proveedor.Nombre as Nombres', 'usuario.Nombre as Usuario')
                        ->where('compras.IdSucursal', $idSucursal)
                        ->where('proveedor.Nombre', $proveedor)
                        ->whereBetween('compras.FechaCreacion', [$fechas[0], $fechas[1]])
                        ->orderBy('compras.FechaCreacion', 'desc')
                        ->get();
                    return $compras;
                } else {
                    $compras = DB::table('compras')
                        ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
                        ->join('sucursal', 'compras.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
                        ->select('compras.*', 'proveedor.Nombre as Nombres', 'usuario.Nombre as Usuario')
                        ->where('compras.IdSucursal', $idSucursal)
                        ->where('proveedor.Nombre', $proveedor)
                        ->where('compras.IdTipoPago', $tipoPago)
                        ->whereBetween('compras.FechaCreacion', [$fechas[0], $fechas[1]])
                        ->orderBy('compras.FechaCreacion', 'desc')
                        ->get();
                    return $compras;
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // Grafico de proveedores por fechas
    public function getComprasUnicoProveedorFiltrados($idSucursal, $proveedor, $tipoPago, $fecha, $fechaIni, $fechaFin, $diferencia, $idTipoMoneda)
    {
        try {
            // $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            if (($fecha >= 1 && $fecha <= 6) || ($fecha == 9 && $diferencia <= 31)) {
                $condicion = 'MONTH(compras.FechaCreacion), DAY(FechaCreacion)';
            }
            if (($fecha == 7 || $fecha == 8 || $fecha == 0 || $fecha == 10) || ($fecha == 9 && $diferencia > 31)) {
                $condicion = 'YEAR(compras.FechaCreacion), MONTH(FechaCreacion)';
            }
            if ($proveedor == 0) {
                if ($tipoPago == 0) {
                    $compras = DB::table('compras')
                        ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
                        ->join('sucursal', 'compras.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
                        ->select('compras.*', 'proveedor.Nombre as Nombres', 'usuario.Nombre as Usuario', DB::raw(' DAY(compras.FechaCreacion) as dia, MONTH(compras.FechaCreacion) as mes, YEAR(compras.FechaCreacion) AS anio, count(*) AS totalCompras'))
                        ->where('compras.IdSucursal', $idSucursal)
                        ->whereBetween('compras.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw($condicion))
                        ->orderBy(DB::raw($condicion), 'asc')
                        ->get();
                    return $compras;
                } else {
                    $compras = DB::table('compras')
                        ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
                        ->join('sucursal', 'compras.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
                        ->select('compras.*', 'proveedor.Nombre as Nombres', 'usuario.Nombre as Usuario', DB::raw(' DAY(compras.FechaCreacion) as dia, MONTH(compras.FechaCreacion) as mes, YEAR(compras.FechaCreacion) AS anio, count(*) AS totalCompras'))
                        ->where('compras.IdSucursal', $idSucursal)
                        ->where('compras.IdTipoPago', $tipoPago)
                        ->whereBetween('compras.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw($condicion))
                        ->orderBy(DB::raw($condicion), 'asc')
                        ->get();
                    return $compras;
                }
            } else {
                if ($tipoPago == 0) {
                    $compras = DB::table('compras')
                        ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
                        ->join('sucursal', 'compras.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
                        ->select('compras.*', 'proveedor.Nombre as Nombres', 'usuario.Nombre as Usuario', DB::raw(' DAY(compras.FechaCreacion) as dia, MONTH(compras.FechaCreacion) as mes, YEAR(compras.FechaCreacion) AS anio, count(*) AS totalCompras'))
                        ->where('compras.IdSucursal', $idSucursal)
                        ->where('compras.IdTipoMoneda', $idTipoMoneda)
                        ->where('proveedor.IdProveedor', $proveedor)
                        ->whereBetween('compras.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw($condicion))
                        ->orderBy(DB::raw($condicion), 'asc')
                        ->get();
                    return $compras;
                } else {
                    $compras = DB::table('compras')
                        ->join('proveedor', 'compras.IdProveedor', '=', 'proveedor.IdProveedor')
                        ->join('sucursal', 'compras.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario', 'compras.IdCreacion', '=', 'usuario.IdUsuario')
                        ->select('compras.*', 'proveedor.Nombre as Nombres', 'usuario.Nombre as Usuario', DB::raw(' DAY(compras.FechaCreacion) as dia, MONTH(compras.FechaCreacion) as mes, YEAR(compras.FechaCreacion) AS anio, count(*) AS totalCompras'))
                        ->where('compras.IdSucursal', $idSucursal)
                        ->where('compras.IdTipoMoneda', $idTipoMoneda)
                        ->where('proveedor.IdProveedor', $proveedor)
                        ->where('compras.IdTipoPago', $tipoPago)
                        ->whereBetween('compras.FechaCreacion', [$fechaIni, $fechaFin])
                        ->groupBy(DB::raw($condicion))
                        ->orderBy(DB::raw($condicion), 'asc')
                        ->get();
                    return $compras;
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    // Fin

    public function getClientesTop($idSucursal)
    {
        $ventas = DB::table('ventas')
            ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
            ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
            ->join('tipo_documento', 'cliente.IdTipoDocumento', '=', 'tipo_documento.IdTipoDocumento')
            ->select(DB::raw("count(ventas.IdCliente) as Cantidad"), 'ventas.*', 'cliente.*', 'usuario.Nombre as Usuario', 'tipo_documento.Descripcion as Documento')
            ->where('ventas.IdSucursal', $idSucursal)
            ->groupBy(DB::raw("ventas.IdCliente"), DB::raw("ventas.IdTipoPago"))
            ->orderBy('Cantidad', 'desc')
            ->limit(30)
            ->get();
        return $ventas;
    }

    public function getClientesTopFiltrados($idSucursal, $tipoPago, $fecha, $fechaIni, $fechaFin)
    {
        try {
            $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            if ($tipoPago == 0) {
                $resultado = DB::table('ventas')
                    ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                    ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                    ->join('tipo_documento', 'cliente.IdTipoDocumento', '=', 'tipo_documento.IdTipoDocumento')
                    ->select(DB::raw("count(ventas.IdCliente) as Cantidad"), 'ventas.*', 'cliente.*', 'usuario.Nombre as Usuario', 'tipo_documento.Descripcion as Documento')
                    ->where('ventas.IdSucursal', $idSucursal)
                    ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->groupBy(DB::raw("ventas.IdCliente"), DB::raw("ventas.IdTipoPago"))
                    ->orderBy('Cantidad', 'desc')
                    ->limit(30)
                    ->get();
                return $resultado;
            } else {
                $resultado = DB::table('ventas')
                    ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                    ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                    ->join('tipo_documento', 'cliente.IdTipoDocumento', '=', 'tipo_documento.IdTipoDocumento')
                    ->select(DB::raw("count(ventas.IdCliente) as Cantidad"), 'ventas.*', 'cliente.*', 'usuario.Nombre as Usuario', 'tipo_documento.Descripcion as Documento')
                    ->where('ventas.IdSucursal', $idSucursal)
                    ->where('ventas.IdTipoPago', $tipoPago)
                    ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->groupBy(DB::raw("ventas.IdCliente"), DB::raw("ventas.IdTipoPago"))
                    ->orderBy('Cantidad', 'desc')
                    ->limit(30)
                    ->get();
                return $resultado;
            }

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getMasVendidos($idSucursal)
    {
        try {
            $resultado = DB::table('ventas_articulo')
                ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                ->join('ventas', 'ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
                ->select(DB::raw("SUM(Cantidad) as Total"), 'ventas_articulo.IdArticulo as IdArticulo', 'articulo.*', 'ventas.IdTipoPago')
                ->where('articulo.IdTipo', 1)
                ->where('articulo.Estado', 'E')
                ->where('articulo.IdSucursal', $idSucursal)
                ->groupBy(DB::raw("ventas_articulo.IdArticulo"))
                ->orderBy('Total', 'desc')
                ->limit(30)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getMasVendidosFiltradosFechas($idSucursal, $tipoPago, $fecha, $cantRegistros, $fechaIni, $fechaFin)
    {
        try {
            if ($fecha >= 1 && $fecha <= 6) {
                $condicion = 'MONTH(ventas.FechaCreacion), DAY(FechaCreacion)';
            }
            if ($fecha == 7 || $fecha == 8 || $fecha == 0) {
                $condicion = 'YEAR(ventas.FechaCreacion), MONTH(FechaCreacion)';
            }
            $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            if ($tipoPago == 0) {
                $resultado = DB::table('ventas_articulo')
                    ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                    ->join('ventas', 'ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
                    ->select(DB::raw('SUM(Cantidad) as Total, ventas_articulo.IdArticulo as IdArticulo, articulo.*, ventas.IdTipoPago, DAY(ventas.FechaCreacion) as dia, MONTH(ventas.FechaCreacion) as mes, YEAR(ventas.FechaCreacion) AS anio'))
                    ->where('articulo.IdTipo', 1)
                    ->where('articulo.Estado', 'E')
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->groupBy(DB::raw($condicion))
                    ->orderBy(DB::raw($condicion), 'asc')
                    ->limit($cantRegistros)
                    ->get();
                return $resultado;
            } else {
                $resultado = DB::table('ventas_articulo')
                    ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                    ->join('ventas', 'ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
                    ->select(DB::raw('SUM(Cantidad) as Total, ventas_articulo.IdArticulo as IdArticulo, articulo.*, ventas.IdTipoPago, DAY(ventas.FechaCreacion) as dia, MONTH(ventas.FechaCreacion) as mes, YEAR(ventas.FechaCreacion) AS anio'))
                    ->where('articulo.IdTipo', 1)
                    ->where('articulo.Estado', 'E')
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('ventas.IdTipoPago', $tipoPago)
                    ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->groupBy(DB::raw($condicion))
                    ->orderBy(DB::raw($condicion), 'asc')
                    ->limit($cantRegistros)
                    ->get();
                return $resultado;
            }

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getMasVendidosFiltrados($idSucursal, $tipoPago, $fecha, $cantRegistros, $fechaIni, $fechaFin)
    {
        try {
            $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            if ($tipoPago == 0) {
                $resultado = DB::table('ventas_articulo')
                    ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                    ->join('ventas', 'ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
                    ->select(DB::raw("SUM(Cantidad) as Total"), 'ventas_articulo.IdArticulo as IdArticulo', 'articulo.*', 'ventas.IdTipoPago')
                    ->where('articulo.IdTipo', 1)
                    ->where('articulo.Estado', 'E')
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->groupBy(DB::raw("ventas_articulo.IdArticulo"))
                    ->orderBy('Total', 'desc')
                    ->limit($cantRegistros)
                    ->get();
                return $resultado;
            } else {
                $resultado = DB::table('ventas_articulo')
                    ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
                    ->join('ventas', 'ventas_articulo.IdVentas', '=', 'ventas.IdVentas')
                    ->select(DB::raw("SUM(Cantidad) as Total"), 'ventas_articulo.IdArticulo as IdArticulo', 'articulo.*', 'ventas.IdTipoPago')
                    ->where('articulo.IdTipo', 1)
                    ->where('articulo.Estado', 'E')
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('ventas.IdTipoPago', $tipoPago)
                    ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->groupBy(DB::raw("ventas_articulo.IdArticulo"))
                    ->orderBy('Total', 'desc')
                    ->limit($cantRegistros)
                    ->get();
                return $resultado;
            }

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getComprasVentas($idSucursal)
    {
        try {
            $resultado = DB::select('(select if(IdCompras > 0, "Compra", null) as Comprobante, FechaCreacion, IdTipoPago, Serie, Numero, SUM(Total) as Total, Estado from compras where IdSucursal = ' . $idSucursal . ' group by IdTipoPago, Estado) union
                        (select if(IdVentas > 0, "Venta", null) as Comprobante, FechaCreacion, IdTipoPago, Serie, Numero, SUM(Total) as Total, Estado from ventas where IdSucursal = ' . $idSucursal . ' group by IdTipoPago, Estado) order by FechaCreacion desc');
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getComprasVentasFiltrados($idSucursal, $tipoPago, $fecha, $fechaIni, $fechaFin)
    {
        $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        if ($tipoPago == 0) {
            try {
                $resultado = DB::select('(select if(IdCompras > 0, "Compra", null) as Comprobante, FechaCreacion, IdTipoPago, Serie, Numero, SUM(Total) as Total, Estado from compras where IdSucursal = ' . $idSucursal . ' and FechaCreacion between "' . $fechas[0] . '" and "' . $fechas[1] . '" group by IdTipoPago, Estado) union
                            (select if(IdVentas > 0, "Venta", null) as Comprobante, FechaCreacion, IdTipoPago, Serie, Numero, SUM(Total) as Total, Estado from ventas where IdSucursal = ' . $idSucursal . ' and FechaCreacion between "' . $fechas[0] . '" and "' . $fechas[1] . '" group by IdTipoPago, Estado)');
                return $resultado;
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        } else {
            try {
                $resultado = DB::select('(select if(IdCompras > 0, "Compra", null) as Comprobante, FechaCreacion, IdTipoPago, Serie, Numero, SUM(Total) as Total, Estado from compras where IdSucursal = ' . $idSucursal . ' and IdTipoPago = ' . $tipoPago . ' and FechaCreacion between "' . $fechas[0] . '" and "' . $fechas[1] . '" group by IdTipoPago, Estado) union
                            (select if(IdVentas > 0, "Venta", null) as Comprobante, FechaCreacion, IdTipoPago, Serie, Numero, SUM(Total) as Total, Estado from ventas where IdSucursal = ' . $idSucursal . ' and IdTipoPago = ' . $tipoPago . ' and FechaCreacion between "' . $fechas[0] . '" and "' . $fechas[1] . '" group by IdTipoPago, Estado)');
                return $resultado;
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        }

    }

    public function getGanancias($idSucursal, $idTipoMoneda)
    {
        try {
            $resultado = DB::select('select SUM(Ganancia) as Ganancia, SUM(Importe) as Precio, SUM(Descuento) as Descuento, SUM(Importe-Ganancia) as Costo, FechaCreacion, IdTipoPago
									 from ventas_articulo
									 inner join
									 ventas on ventas_articulo.IdVentas = ventas.IdVentas where (ventas.MotivoAnulacion = "" OR ventas.MotivoAnulacion IS NULL) AND ventas.Nota!=1 AND ventas_articulo.Gratuito = 0 AND ventas.IdTipoMoneda = ' . $idTipoMoneda . ' AND ventas.IdSucursal = ' . $idSucursal . ' group by IdTipoPago');
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // FUNCION  getGananciasFiltrados MODIFICADO
    // public function getGananciasFiltradoss($idSucursal, $fecha, $fechaIni, $fechaFin)
    // {
    //     $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);

    //     try {
    //         $resultado = DB::select('select SUM(Ganancia) as Ganancia, SUM(Importe) as Precio, SUM(Descuento) as Descuento, SUM(Importe - Ganancia) as Costo, FechaCreacion, IdTipoPago, IdTipoMoneda as tipoMoneda,
    //             (select sum(Monto) AS montoGasto FROM gastos
    //             WHERE gastos. IdSucursal = ' . $idSucursal . '  and FechaCreacion between "' . $fechas[0] . '" and "' . $fechas[1] . '" ) AS montoGasto
    //                                     from ventas_articulo
    //                                     inner join ventas on ventas_articulo.IdVentas = ventas.IdVentas where (ventas.MotivoAnulacion = "" OR ventas.MotivoAnulacion IS NULL) AND ventas.Nota!=1 AND ventas_articulo.Gratuito = 0 AND IdSucursal = ' . $idSucursal . ' and FechaCreacion between "' . $fechas[0] . '" and "' . $fechas[1] . '" group by IdTipoPago , IdTipoMoneda ORDER by IdTipoPago');
    //         return $resultado;
    //     } catch (Exception $ex) {
    //         echo $ex->getMessage();
    //     }
    // }
    public function getGananciasFiltradoss($idSucursal, $fecha, $fechaIni, $fechaFin)
    {
        $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);

        try {
            $resultado = DB::select('select SUM(Ganancia) as Ganancia, SUM(Importe) as Precio, SUM(Descuento) as Descuento, SUM(Importe - Ganancia) as Costo, FechaCreacion, IdTipoPago, IdTipoMoneda as tipoMoneda
                                        from ventas_articulo
                                        inner join ventas on ventas_articulo.IdVentas = ventas.IdVentas where (ventas.MotivoAnulacion = "" OR ventas.MotivoAnulacion IS NULL) AND ventas.Nota!=1 AND ventas_articulo.Gratuito = 0 AND IdSucursal = ' . $idSucursal . ' and FechaCreacion between "' . $fechas[0] . '" and "' . $fechas[1] . '" group by IdTipoPago , IdTipoMoneda ORDER by IdTipoPago');
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    // Fin

    public function getGananciasFiltrados($idSucursal, $tipoPago, $idTipoMoneda, $fecha, $fechaIni, $fechaFin)
    {
        $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        if ($tipoPago == 0) {
            try {
                $resultado = DB::select('select SUM(Ganancia) as Ganancia, SUM(Importe) as Precio, SUM(Descuento) as Descuento, SUM(Importe - Ganancia) as Costo, FechaCreacion, IdTipoPago
										 from ventas_articulo inner join ventas on ventas_articulo.IdVentas = ventas.IdVentas where (ventas.MotivoAnulacion = "" OR ventas.MotivoAnulacion IS NULL) AND ventas.Nota!=1 AND ventas_articulo.Gratuito = 0 AND ventas.IdTipoMoneda = ' . $idTipoMoneda . ' AND IdSucursal = ' . $idSucursal . ' and FechaCreacion between "' . $fechas[0] . '" and "' . $fechas[1] . '" group by IdTipoPago');
                return $resultado;
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        } else {
            try {
                $resultado = DB::select('select SUM(Ganancia) as Ganancia, SUM(Importe) as Precio, SUM(Descuento) as Descuento, SUM(Importe - Ganancia) as Costo, FechaCreacion, IdTipoPago
										 from ventas_articulo inner join ventas on ventas_articulo.IdVentas = ventas.IdVentas where (ventas.MotivoAnulacion = "" OR ventas.MotivoAnulacion IS NULL) AND ventas.Nota!=1 AND ventas_articulo.Gratuito = 0 AND ventas.IdTipoMoneda = ' . $idTipoMoneda . ' AND IdSucursal = ' . $idSucursal . ' and FechaCreacion between "' . $fechas[0] . '" and "' . $fechas[1] . '" group by IdTipoPago');
                return $resultado;
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        }
    }

    public function getStockProductos($idSucursal)
    {
        try {
            $resultado = DB::table('articulo')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                ->join('categoria', 'categoria.IdCategoria', '=', 'articulo.IdCategoria')
                ->select('articulo.*', 'unidad_medida.Nombre', 'marca.nombre as NombreMar', 'categoria.Nombre as NombreCat')
                ->where('IdTipo', 1)
                ->where('articulo.Estado', 'E')
                ->where('articulo.IdSucursal', $idSucursal)
                ->orderBy('articulo.Descripcion', 'asc')
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getStockProductosFiltrados($idSucursal, $producto)
    {
        try {
            $resultado = DB::table('articulo')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                ->join('categoria', 'categoria.IdCategoria', '=', 'articulo.IdCategoria')
                ->select('articulo.*', 'unidad_medida.Nombre', 'marca.nombre as NombreMar', 'categoria.Nombre as NombreCat')
                ->where('IdTipo', 1)
                ->where('articulo.Estado', 'E')
                ->where('articulo.IdSucursal', $idSucursal)
                ->where('articulo.Descripcion', 'like', '%' . $producto . '%')
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getStockCritico($idSucursal)
    {
        try {
            $resultado = DB::table('articulo')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->select('articulo.*', 'unidad_medida.Nombre')
                ->where('IdTipo', 1)
                ->where('articulo.Estado', 'E')
                ->where('IdSucursal', $idSucursal)
                ->where('Stock', '<', 10)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getKardexProductos($idSucursal, $idProd, $verificarCodigo, $codigo, $fecha1, $fecha2)
    {
        try {
            if ($verificarCodigo == 0) {
                $resultado = DB::table('kardex')
                    ->join('usuario', 'usuario.IdUsuario', '=', 'kardex.usuario_movimiento')
                    ->join('movimiento_kardex', 'kardex.tipo_movimiento', '=', 'movimiento_kardex.IdMovimientoKardex')
                    ->join('articulo', 'kardex.IdArticulo', '=', 'articulo.IdArticulo')
                    ->select('kardex.*', 'usuario.Nombre', 'movimiento_kardex.Descripcion', 'movimiento_kardex.EstadoStock', 'articulo.Stock')
                    ->where('kardex.IdArticulo', $idProd)
                    ->where('kardex.IdSucursal', $idSucursal)
                    ->whereBetween('kardex.fecha_movimiento', [$fecha1, $fecha2])
                    ->get();
            } else {
                $resultado = DB::table('kardex')
                    ->join('usuario', 'usuario.IdUsuario', '=', 'kardex.usuario_movimiento')
                    ->join('movimiento_kardex', 'kardex.tipo_movimiento', '=', 'movimiento_kardex.IdMovimientoKardex')
                    ->join('articulo', 'kardex.IdArticulo', '=', 'articulo.IdArticulo')
                    ->select('kardex.*', 'usuario.Nombre', 'movimiento_kardex.Descripcion', 'movimiento_kardex.EstadoStock', 'articulo.Stock')
                    ->where('articulo.Codigo', $codigo)
                    ->where('kardex.IdSucursal', $idSucursal)
                    ->whereBetween('kardex.fecha_movimiento', [$fecha1, $fecha2])
                    ->get();
            }
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // NUEVA FUNCION LISTA KARDEX PRODUCTOS CON MARCAS
    public function getKardexListaProductos($idSucursal, $idProd, $idMarca, $inputCodigoBarra, $estado, $fecha1, $fecha2)
    {
        try {
            if ($inputCodigoBarra == 'vacio') {
                if ($idProd == 0 && $idMarca == 0) {
                    $resultado = [];
                } elseif ($idProd != 0 && $idMarca == 0) {

                    $resultado = DB::table('kardex')
                        ->join('usuario', 'usuario.IdUsuario', '=', 'kardex.usuario_movimiento')
                        ->join('movimiento_kardex', 'kardex.tipo_movimiento', '=', 'movimiento_kardex.IdMovimientoKardex')
                        ->join('articulo', 'kardex.IdArticulo', '=', 'articulo.IdArticulo')
                        ->select('kardex.*', 'usuario.Nombre', 'movimiento_kardex.Descripcion', 'movimiento_kardex.EstadoStock', 'articulo.Stock', 'articulo.Codigo')
                        ->where('kardex.IdArticulo', $idProd)
                        ->where('kardex.IdSucursal', $idSucursal)
                        ->where('kardex.estado', $estado)
                        ->whereBetween('kardex.fecha_movimiento', [$fecha1, $fecha2])
                        ->get();
                } elseif ($idProd == 0 && $idMarca != 0) {
                    $resultado = DB::table('kardex')
                        ->join('usuario', 'usuario.IdUsuario', '=', 'kardex.usuario_movimiento')
                        ->join('movimiento_kardex', 'kardex.tipo_movimiento', '=', 'movimiento_kardex.IdMovimientoKardex')
                        ->join('articulo', 'kardex.IdArticulo', '=', 'articulo.IdArticulo')
                        ->select('kardex.*', 'usuario.Nombre', 'movimiento_kardex.Descripcion', 'movimiento_kardex.EstadoStock', 'articulo.Stock', 'articulo.Codigo')
                        ->where('articulo.IdMarca', $idMarca)
                        ->where('kardex.IdSucursal', $idSucursal)
                        ->where('kardex.estado', $estado)
                        ->whereBetween('kardex.fecha_movimiento', [$fecha1, $fecha2])
                        ->get();
                } elseif ($idProd != 0 && $idMarca != 0) {
                    $resultado = DB::table('kardex')
                        ->join('usuario', 'usuario.IdUsuario', '=', 'kardex.usuario_movimiento')
                        ->join('movimiento_kardex', 'kardex.tipo_movimiento', '=', 'movimiento_kardex.IdMovimientoKardex')
                        ->join('articulo', 'kardex.IdArticulo', '=', 'articulo.IdArticulo')
                        ->select('kardex.*', 'usuario.Nombre', 'movimiento_kardex.Descripcion', 'movimiento_kardex.EstadoStock', 'articulo.Stock', 'articulo.Codigo')
                        ->where('kardex.IdSucursal', $idSucursal)
                        ->where('kardex.IdArticulo', $idProd)
                        ->where('kardex.estado', $estado)
                        ->where('articulo.IdMarca', $idMarca)
                        ->whereBetween('kardex.fecha_movimiento', [$fecha1, $fecha2])
                        ->get();
                }
            } else {
                if ($inputCodigoBarra != null && $idMarca == 0) {
                    $resultado = DB::table('kardex')
                        ->join('usuario', 'usuario.IdUsuario', '=', 'kardex.usuario_movimiento')
                        ->join('movimiento_kardex', 'kardex.tipo_movimiento', '=', 'movimiento_kardex.IdMovimientoKardex')
                        ->join('articulo', 'kardex.IdArticulo', '=', 'articulo.IdArticulo')
                        ->select('kardex.*', 'usuario.Nombre', 'movimiento_kardex.Descripcion', 'movimiento_kardex.EstadoStock', 'articulo.Stock', 'articulo.Codigo')
                        ->where('articulo.Codigo', $inputCodigoBarra)
                        ->where('kardex.IdSucursal', $idSucursal)
                        ->where('kardex.estado', $estado)
                        ->whereBetween('kardex.fecha_movimiento', [$fecha1, $fecha2])
                        ->get();
                } elseif ($inputCodigoBarra != null && $idMarca != 0) {
                    $resultado = DB::table('kardex')
                        ->join('usuario', 'usuario.IdUsuario', '=', 'kardex.usuario_movimiento')
                        ->join('movimiento_kardex', 'kardex.tipo_movimiento', '=', 'movimiento_kardex.IdMovimientoKardex')
                        ->join('articulo', 'kardex.IdArticulo', '=', 'articulo.IdArticulo')
                        ->select('kardex.*', 'usuario.Nombre', 'movimiento_kardex.Descripcion', 'movimiento_kardex.EstadoStock', 'articulo.Stock', 'articulo.Codigo')
                        ->where('articulo.Codigo', $inputCodigoBarra)
                        ->where('kardex.IdSucursal', $idSucursal)
                        ->where('articulo.IdMarca', $idMarca)
                        ->where('kardex.estado', $estado)
                        ->whereBetween('kardex.fecha_movimiento', [$fecha1, $fecha2])
                        ->get();
                }
            }
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    // public function getKardexListaProductos($idSucursal, $idProd, $idMarca, $inputCodigoBarra, $fecha1, $fecha2)
    // {
    //     try {
    //         if($inputCodigoBarra == 0){
    //             if ($idProd == 0 && $idMarca == 0) {
    //                 $resultado = DB::table('kardex')
    //                 ->join('usuario', 'usuario.IdUsuario', '=', 'kardex.usuario_movimiento')
    //                 ->join('movimiento_kardex', 'kardex.tipo_movimiento', '=', 'movimiento_kardex.IdMovimientoKardex')
    //                 ->join('articulo', 'kardex.IdArticulo', '=', 'articulo.IdArticulo')
    //                 ->select('kardex.*', 'usuario.Nombre', 'movimiento_kardex.Descripcion', 'movimiento_kardex.EstadoStock', 'articulo.Stock' , 'articulo.Codigo', 'articulo.IdMarca')
    //                 ->where('kardex.IdSucursal', $idSucursal)
    //                 ->whereBetween('kardex.fecha_movimiento', [$fecha1, $fecha2])
    //                 ->get();
    //             }elseif ($idProd != 0 && $idMarca == 0) {
    //                 $resultado = DB::table('kardex')
    //                 ->join('usuario', 'usuario.IdUsuario', '=', 'kardex.usuario_movimiento')
    //                 ->join('movimiento_kardex', 'kardex.tipo_movimiento', '=', 'movimiento_kardex.IdMovimientoKardex')
    //                 ->join('articulo', 'kardex.IdArticulo', '=', 'articulo.IdArticulo')
    //                 ->select('kardex.*', 'usuario.Nombre', 'movimiento_kardex.Descripcion', 'movimiento_kardex.EstadoStock', 'articulo.Stock', 'articulo.Codigo')
    //                 ->where('kardex.IdArticulo', $idProd)
    //                 ->where('kardex.IdSucursal', $idSucursal)
    //                 ->whereBetween('kardex.fecha_movimiento', [$fecha1, $fecha2])
    //                 ->get();
    //             }elseif ($idProd == 0 && $idMarca != 0) {
    //                 $resultado = DB::table('kardex')
    //                 ->join('usuario', 'usuario.IdUsuario', '=', 'kardex.usuario_movimiento')
    //                 ->join('movimiento_kardex', 'kardex.tipo_movimiento', '=', 'movimiento_kardex.IdMovimientoKardex')
    //                 ->join('articulo', 'kardex.IdArticulo', '=', 'articulo.IdArticulo')
    //                 ->select('kardex.*', 'usuario.Nombre', 'movimiento_kardex.Descripcion', 'movimiento_kardex.EstadoStock', 'articulo.Stock', 'articulo.Codigo')
    //                 ->where('articulo.IdMarca', $idMarca)
    //                 ->where('kardex.IdSucursal', $idSucursal)
    //                 ->whereBetween('kardex.fecha_movimiento', [$fecha1, $fecha2])
    //                 ->get();
    //             }elseif ($idProd != 0 && $idMarca != 0) {
    //                 $resultado = DB::table('kardex')
    //                 ->join('usuario', 'usuario.IdUsuario', '=', 'kardex.usuario_movimiento')
    //                 ->join('movimiento_kardex', 'kardex.tipo_movimiento', '=', 'movimiento_kardex.IdMovimientoKardex')
    //                 ->join('articulo', 'kardex.IdArticulo', '=', 'articulo.IdArticulo')
    //                 ->select('kardex.*', 'usuario.Nombre', 'movimiento_kardex.Descripcion', 'movimiento_kardex.EstadoStock', 'articulo.Stock', 'articulo.Codigo')
    //                 ->where('kardex.IdSucursal', $idSucursal)
    //                 ->where('kardex.IdArticulo', $idProd)
    //                 ->where('articulo.IdMarca', $idMarca)
    //                 ->whereBetween('kardex.fecha_movimiento', [$fecha1, $fecha2])
    //                 ->get();
    //             }
    //         }else{
    //             if ($inputCodigoBarra != 0 && $idMarca == 0) {
    //                 $resultado = DB::table('kardex')
    //                             ->join('usuario', 'usuario.IdUsuario', '=', 'kardex.usuario_movimiento')
    //                             ->join('movimiento_kardex', 'kardex.tipo_movimiento', '=', 'movimiento_kardex.IdMovimientoKardex')
    //                             ->join('articulo', 'kardex.IdArticulo', '=', 'articulo.IdArticulo')
    //                             ->select('kardex.*', 'usuario.Nombre', 'movimiento_kardex.Descripcion', 'movimiento_kardex.EstadoStock', 'articulo.Stock', 'articulo.Codigo')
    //                             ->where('articulo.Codigo', $inputCodigoBarra)
    //                             ->where('kardex.IdSucursal', $idSucursal)
    //                             ->whereBetween('kardex.fecha_movimiento', [$fecha1, $fecha2])
    //                             ->get();
    //             }elseif ($inputCodigoBarra != 0 && $idMarca != 0) {
    //                 $resultado = DB::table('kardex')
    //                 ->join('usuario', 'usuario.IdUsuario', '=', 'kardex.usuario_movimiento')
    //                 ->join('movimiento_kardex', 'kardex.tipo_movimiento', '=', 'movimiento_kardex.IdMovimientoKardex')
    //                 ->join('articulo', 'kardex.IdArticulo', '=', 'articulo.IdArticulo')
    //                 ->select('kardex.*', 'usuario.Nombre', 'movimiento_kardex.Descripcion', 'movimiento_kardex.EstadoStock', 'articulo.Stock', 'articulo.Codigo')
    //                 ->where('articulo.Codigo', $inputCodigoBarra)
    //                 ->where('kardex.IdSucursal', $idSucursal)
    //                 ->where('articulo.IdMarca', $idMarca)
    //                 ->whereBetween('kardex.fecha_movimiento', [$fecha1, $fecha2])
    //                 ->get();
    //                 }
    //         }
    //         return $resultado;
    //     } catch (Exception $ex) {
    //         echo $ex->getMessage();
    //     }
    // }
    // Fin

    public function getKardexProductosFiltrados($idSucursal, $producto, $categoria, $marca)
    {
        try {
            $resultado = DB::table('articulo')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                ->join('categoria', 'articulo.IdCategoria', '=', 'categoria.IdCategoria')
                ->select('articulo.*', 'unidad_medida.Nombre', 'marca.Nombre as Marca', 'categoria.Nombre as Categoria', DB::raw("(articulo.Precio - articulo.Costo) as Ganancia"))
                ->where('IdTipo', 1)
                ->where('articulo.Estado', 'E')
                ->where('articulo.IdSucursal', $idSucursal)
                ->where('articulo.Descripcion', 'like', '%' . $producto . '%')
                ->where('categoria.Nombre', 'like', '%' . $categoria . '%')
                ->where('marca.Nombre', 'like', '%' . $marca . '%')
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCreditosVencidos($idSucursal)
    {
        try {
            $resultado = DB::table('fecha_pago')
                ->join('ventas', 'fecha_pago.IdVenta', '=', 'ventas.IdVentas')
                ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                ->select('cliente.Nombre as Cliente', DB::raw("concat_ws('-', ventas.Serie, ventas.Numero) as Documento"), 'fecha_pago.FechaInicio', 'fecha_pago.FechaUltimo', 'fecha_pago.FechaPago', DB::raw("if(ventas.IdTipoMoneda = 1, 'Soles', 'Dólares') as Moneda"), 'fecha_pago.Importe', DB::raw("(fecha_pago.Importe - fecha_pago.ImportePagado) as Deuda"), 'fecha_pago.DiasPasados')
                ->where('ventas.IdSucursal', $idSucursal)
                ->whereNotIn('fecha_pago.Estado', [2])
                ->whereNotIn('ventas.Estado', ["Rechazo", "Baja Aceptado", "Baja Pendiente", "Baja Ticket"])
                ->whereNotIn('ventas.Nota', [1])
                ->where('fecha_pago.DiasPasados', '>', 0)
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
                ->select('cliente.Nombre as Cliente', DB::raw("concat_ws('-', ventas.Serie, ventas.Numero) as Documento"), 'fecha_pago.FechaInicio', 'fecha_pago.FechaUltimo', 'fecha_pago.FechaPago', 'ventas.IdTipoMoneda', 'fecha_pago.Importe', DB::raw("(fecha_pago.Importe - fecha_pago.ImportePagado) as Deuda"), 'fecha_pago.DiasPasados')
                ->where('ventas.IdSucursal', $idSucursal)
                ->whereNotIn('fecha_pago.Estado', [2])
                ->whereNotIn('ventas.Estado', ["Rechazo", "Baja Aceptado", "Baja Pendiente", "Baja Ticket"])
                ->whereNotIn('ventas.Nota', [1])
                ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                ->where('fecha_pago.DiasPasados', '>', 0)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getClientesMorosos($idSucursal)
    {
        try {
            $resultado = DB::table('fecha_pago')
                ->join('ventas', 'fecha_pago.IdVenta', '=', 'ventas.IdVentas')
                ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                ->join('estados_cobranza', 'fecha_pago.Estado', '=', 'estados_cobranza.IdEstadoCobranza')
                ->select('cliente.Nombre as Cliente', 'fecha_pago.*', 'ventas.Serie', 'ventas.Numero', 'ventas.Nota', 'ventas.IdTipoMoneda', 'estados_cobranza.Descripcion as NombreEstado', 'estados_cobranza.Color', DB::raw("(fecha_pago.Importe - fecha_pago.ImportePagado) as Deuda"))
                ->where('ventas.IdSucursal', $idSucursal)
                ->whereNotIn('ventas.Estado', ["Rechazo", "Baja Aceptado", "Baja Pendiente", "Baja Ticket"])
                ->whereNotIn('ventas.Nota', [1])
                ->whereNotIn('fecha_pago.Estado', [2])
                ->where('fecha_pago.DiasPasados', '>', 0)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getClientesMorososFiltrados($idSucursal, $fecha, $fechaIni, $fechaFin)
    {
        $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        try {
            $resultado = DB::table('fecha_pago')
                ->join('ventas', 'fecha_pago.IdVenta', '=', 'ventas.IdVentas')
                ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                ->join('estados_cobranza', 'fecha_pago.Estado', '=', 'estados_cobranza.IdEstadoCobranza')
                ->select('cliente.Nombre as Cliente', 'fecha_pago.*', 'ventas.Serie', 'ventas.Numero', 'ventas.Nota', 'ventas.IdTipoMoneda', 'estados_cobranza.Descripcion as NombreEstado', DB::raw("(fecha_pago.Importe - fecha_pago.ImportePagado) as Deuda"))
                ->where('ventas.IdSucursal', $idSucursal)
                ->whereNotIn('ventas.Estado', ["Rechazo", "Baja Aceptado", "Baja Pendiente", "Baja Ticket"])
                ->whereNotIn('ventas.Nota', [1])
                ->whereNotIn('fecha_pago.Estado', [2])
                ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                ->where('fecha_pago.DiasPasados', '>', 0)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getListaBancos()
    {
        try {
            $resultado = DB::table('lista_banco')
                ->where('Estado', 'E')
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCuentasCorrientes($codigoCliente, $tipoMoneda)
    {
        try {
            if ($tipoMoneda != null) {
                $resultado = DB::table('banco')
                    ->join('lista_banco', 'banco.IdListaBanco', '=', 'lista_banco.IdListaBanco')
                    ->join('tipo_moneda', 'banco.IdTipoMoneda', '=', 'tipo_moneda.IdTipoMoneda')
                    ->select('banco.*', 'lista_banco.Nombre as Banco', 'tipo_moneda.Nombre as Moneda')
                    ->where('CodigoCliente', $codigoCliente)
                    ->where('banco.IdTipoMoneda', $tipoMoneda)
                    ->where('banco.Estado', 'E')
                    ->get();
            } else {
                $resultado = DB::table('banco')
                    ->join('lista_banco', 'banco.IdListaBanco', '=', 'lista_banco.IdListaBanco')
                    ->join('tipo_moneda', 'banco.IdTipoMoneda', '=', 'tipo_moneda.IdTipoMoneda')
                    ->select('banco.*', 'lista_banco.Nombre as Banco', 'tipo_moneda.Nombre as Moneda')
                    ->where('CodigoCliente', $codigoCliente)
                    ->where('banco.Estado', 'E')
                    ->get();
            }
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCuentaDetracciones($codigoCliente, $idListaBanco)
    {
        try {
            $resultado = DB::table('banco')
                ->select('NumeroCuenta')
                ->where('IdListaBanco', $idListaBanco)
                ->where('CodigoCliente', $codigoCliente)
                ->where('Estado', 'E')
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCuentaCorrienteSelect($idBanco)
    {
        try {
            $resultado = DB::table('banco')
                ->where('IdBanco', $idBanco)
                ->where('banco.Estado', 'E')
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getDetallesCuentaCorriente($idBanco)
    {
        try {
            $resultado = DB::table('banco_detalles')
                ->where('IdBanco', $idBanco)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getPagosParciales($idSucursal)
    {
        try {
            $resultado = DB::table('fecha_pago')
                ->join('ventas', 'fecha_pago.IdVenta', '=', 'ventas.IdVentas')
                ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                ->join('pagos_detalle', 'fecha_pago.IdFechaPago', '=', 'pagos_detalle.IdFechaPago')
                ->select('cliente.Nombre as Cliente', 'fecha_pago.*', 'ventas.Serie', 'ventas.Numero', 'ventas.Nota', 'ventas.IdTipoMoneda', DB::raw("if(DiasPasados < 0, 0,DiasPasados) as DiasAtrasados"), DB::raw("SUM(pagos_detalle.CuentaBancaria) as CuentaBancaria"))
                ->where('ventas.IdSucursal', $idSucursal)
                ->whereNotIn('fecha_pago.Estado', [2])
                ->whereNotIn('ventas.Estado', ["Rechazo", "Baja Aceptado", "Baja Pendiente", "Baja Ticket"])
                ->whereNotIn('ventas.Nota', [1])
                ->where('fecha_pago.ImportePagado', '>', 0)
                ->groupBy(DB::raw('fecha_pago.IdFechaPago'))
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getPagosParcialesFiltrados($idSucursal, $fecha, $fechaIni, $fechaFin)
    {
        $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        try {
            /*$resultado = DB::select('select if(DiasPasados < 0, 0,DiasPasados) as DiasAtrasados, fecha_pago.* from fecha_pago inner join ventas on fecha_pago.IdVenta = ventas.IdVentas where ventas.IdSucursal = '.$idSucursal.' and fecha_pago.Estado != 2 and FechaCreacion between "'.$fechas[0].'" and "'.$fechas[1].'" and fecha_pago.ImportePagado > 0');
            return $resultado;*/

            $resultado = DB::table('fecha_pago')
                ->join('ventas', 'fecha_pago.IdVenta', '=', 'ventas.IdVentas')
                ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                ->join('pagos_detalle', 'fecha_pago.IdFechaPago', '=', 'pagos_detalle.IdFechaPago')
                ->select('cliente.Nombre as Cliente', 'fecha_pago.*', 'ventas.Serie', 'ventas.Numero', 'ventas.Nota', 'ventas.IdTipoMoneda', DB::raw("if(DiasPasados < 0, 0,DiasPasados) as DiasAtrasados"), DB::raw("SUM(pagos_detalle.CuentaBancaria) as CuentaBancaria"))
                ->where('ventas.IdSucursal', $idSucursal)
                ->whereNotIn('fecha_pago.Estado', [2])
                ->whereNotIn('ventas.Estado', ["Rechazo", "Baja Aceptado", "Baja Pendiente", "Baja Ticket"])
                ->whereNotIn('ventas.Nota', [1])
                ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                ->where('fecha_pago.ImportePagado', '>', 0)
                ->groupBy(DB::raw("fecha_pago.IdFechaPago"))
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getPagosTotales($idSucursal)
    {
        try {
            $resultado = DB::table('fecha_pago')
                ->join('ventas', 'fecha_pago.IdVenta', '=', 'ventas.IdVentas')
                ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                ->join('pagos_detalle', 'fecha_pago.IdFechaPago', '=', 'pagos_detalle.IdFechaPago')
                ->select('cliente.Nombre as Cliente', 'fecha_pago.*', 'ventas.Serie', 'ventas.Numero', 'ventas.Nota', 'ventas.IdTipoMoneda', DB::raw("if(DiasPasados < 0, 0,DiasPasados) as DiasAtrasados"), DB::raw("SUM(pagos_detalle.CuentaBancaria) as CuentaBancaria"))
                ->where('ventas.IdSucursal', $idSucursal)
                ->whereNotIn('ventas.Estado', ["Rechazo", "Baja Aceptado", "Baja Pendiente", "Baja Ticket"])
                ->whereNotIn('ventas.Nota', [1])
                ->where('fecha_pago.Estado', 2)
                ->groupBy(DB::raw("fecha_pago.IdFechaPago"))
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getPagosTotalesFiltrados($idSucursal, $fecha, $fechaIni, $fechaFin)
    {
        $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
        try {

            $resultado = DB::table('fecha_pago')
                ->join('ventas', 'fecha_pago.IdVenta', '=', 'ventas.IdVentas')
                ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                ->join('pagos_detalle', 'fecha_pago.IdFechaPago', '=', 'pagos_detalle.IdFechaPago')
                ->select('cliente.Nombre as Cliente', 'fecha_pago.*', 'ventas.Serie', 'ventas.Numero', 'ventas.Nota', 'ventas.IdTipoMoneda', DB::raw("if(DiasPasados < 0, 0,DiasPasados) as DiasAtrasados"), DB::raw("SUM(pagos_detalle.CuentaBancaria) as CuentaBancaria"))
                ->where('ventas.IdSucursal', $idSucursal)
                ->whereNotIn('ventas.Estado', ["Rechazo", "Baja Aceptado", "Baja Pendiente", "Baja Ticket"])
                ->whereNotIn('ventas.Nota', [1])
                ->where('fecha_pago.Estado', 2)
                ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                ->groupBy(DB::raw("fecha_pago.IdFechaPago"))
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getVentasCobrar($idSucursal, $idTipoMoneda)
    {
        try {
            $resultado = DB::table('fecha_pago')
                ->join('ventas', 'fecha_pago.IdVenta', '=', 'ventas.IdVentas')
                ->select(DB::raw("SUM(fecha_pago.Importe - fecha_pago.ImportePagado) as TotalCobrar"))
                ->where('ventas.IdSucursal', $idSucursal)
                ->where('ventas.IdTipoMoneda', $idTipoMoneda)
                ->where('ventas.Nota', '<>', 1)
                ->whereNull('ventas.MotivoAnulacion')
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getPagosProveedoresTotales($idSucursal, $fechaHoy)
    {
        try {
            $resultado = DB::table('fecha_compras')
                ->join('compras', 'fecha_compras.IdCompras', '=', 'compras.IdCompras')
                ->select('fecha_compras.*', DB::raw("DATEDIFF('" . $fechaHoy . "', fecha_compras.FechaInicio) as Dias"))
                ->where('compras.IdSucursal', $idSucursal)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCobranzasTotales($idSucursal, $fechaHoy)
    {
        try {
            //$resultado = DB::select('select if(DATEDIFF(NOW(),fecha_pago.FechaUltimo) < -50, SUM(fecha_pago.Importe - fecha_pago.ImportePagado), null) as TotalCobrar from fecha_pago inner join ventas on fecha_pago.IdVenta = ventas.IdVentas where ventas.IdSucursal = '.$idSucursal);
            $resultado = DB::table('fecha_pago')
                ->join('ventas', 'fecha_pago.IdVenta', '=', 'ventas.IdVentas')
                ->select('fecha_pago.*', DB::raw("DATEDIFF('" . $fechaHoy . "',fecha_pago.FechaUltimo) as Dias"))
                ->where('ventas.IdSucursal', $idSucursal)
                ->whereNotIn('ventas.Estado', ["Rechazo", "Baja Aceptado", "Baja Pendiente", "Baja Ticket"])
                ->whereNotIn('ventas.Nota', [1])
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCobranzasTotalesFiltrado($idSucursal, $cliente, $fecha, $fechaIni, $fechaFin)
    {
        try {
            $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            //$resultado = DB::select('select if(DATEDIFF(NOW(),fecha_pago.FechaUltimo) < -50, SUM(fecha_pago.Importe - fecha_pago.ImportePagado), null) as TotalCobrar from fecha_pago inner join ventas on fecha_pago.IdVenta = ventas.IdVentas where ventas.IdSucursal = '.$idSucursal);
            $resultado = DB::table('fecha_pago')
                ->join('ventas', 'fecha_pago.IdVenta', '=', 'ventas.IdVentas')
                ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                ->select('fecha_pago.*', DB::raw("(ventas.PlazoCredito) as Dias"), 'cliente.Nombre as Cliente')
                ->where('cliente.Nombre', $cliente)
                ->whereBetween('ventas.FechaCreacion', [$fechas[0], $fechas[1]])
                ->where('ventas.IdSucursal', $idSucursal)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCobranzasNoVencidos($idSucursal, $idTipoMoneda)
    {
        try {
            //$resultado = DB::select('select if(DATEDIFF(NOW(),fecha_pago.FechaUltimo) < -50, SUM(fecha_pago.Importe - fecha_pago.ImportePagado), null) as TotalCobrar from fecha_pago inner join ventas on fecha_pago.IdVenta = ventas.IdVentas where ventas.IdSucursal = '.$idSucursal);
            $resultado = DB::table('fecha_pago')
                ->join('ventas', 'fecha_pago.IdVenta', '=', 'ventas.IdVentas')
                ->select(DB::raw("SUM(fecha_pago.Importe - fecha_pago.ImportePagado) as TotalNoVencido"), DB::raw("count(IdFechaPago) as Cantidad"))
                ->where('ventas.IdSucursal', $idSucursal)
                ->where('ventas.IdTipoMoneda', $idTipoMoneda)
                ->where('ventas.Nota', '<>', 1)
                ->whereNull('ventas.MotivoAnulacion')
                ->where('fecha_pago.DiasPasados', '<=', 0)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCobranzasVencidos($idSucursal, $idTipoMoneda)
    {
        try {
            //$resultado = DB::select('select if(DATEDIFF(NOW(),fecha_pago.FechaUltimo) < -50, SUM(fecha_pago.Importe - fecha_pago.ImportePagado), null) as TotalCobrar from fecha_pago inner join ventas on fecha_pago.IdVenta = ventas.IdVentas where ventas.IdSucursal = '.$idSucursal);
            $resultado = DB::table('fecha_pago')
                ->join('ventas', 'fecha_pago.IdVenta', '=', 'ventas.IdVentas')
                ->select(DB::raw("SUM(fecha_pago.Importe - fecha_pago.ImportePagado) as TotalVencido"), DB::raw("count(IdFechaPago) as Cantidad"))
                ->where('ventas.IdSucursal', $idSucursal)
                ->where('ventas.IdTipoMoneda', $idTipoMoneda)
                ->where('ventas.Nota', '<>', 1)
                ->whereNull('ventas.MotivoAnulacion')
                ->where('fecha_pago.DiasPasados', '>', 0)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCuotaPagar($idCuota)
    {
        try {
            $resultado = DB::table('fecha_pago')
                ->select('fecha_pago.*', DB::raw("(Importe - ImportePagado) as TotalDeuda"))
                ->where('IdFechaPago', $idCuota)
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }

    }

    public function getCuotaPagarProveedor($idCuota)
    {
        try {
            $resultado = DB::table('fecha_compras')
                ->select('fecha_compras.*', DB::raw("(Importe - ImportePagado) as TotalDeuda"))
                ->where('IdFechaCompras', $idCuota)
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function pagosDetalle($id)
    {
        try {
            $resultado = DB::table('pagos_detalle')
                ->join('banco', 'pagos_detalle.IdBanco', '=', 'banco.IdBanco')
                ->join('lista_banco', 'banco.IdListaBanco', '=', 'lista_banco.IdListaBanco')
                ->select('pagos_detalle.*', 'banco.NumeroCuenta', 'lista_banco.Nombre')
                ->where('IdFechaPago', $id)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function pagosProveedoresDetalle($id)
    {
        try {
            $resultado = DB::table('detalles_pagos_proveedor')
                ->join('banco', 'detalles_pagos_proveedor.IdBanco', '=', 'banco.IdBanco')
                ->join('lista_banco', 'banco.IdListaBanco', '=', 'lista_banco.IdListaBanco')
                ->select('detalles_pagos_proveedor.*', 'banco.NumeroCuenta', 'lista_banco.Nombre')
                ->where('IdFechaCompras', $id)
                ->get();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getDeudasTotales($idVenta)
    {
        try {
            $resultado = DB::table('fecha_pago')
                ->select(DB::raw("SUM(Importe) as ImporteTotal"), DB::raw("SUM(Importe - ImportePagado) as TotalDeuda"))
                ->where('IdVenta', $idVenta)
                ->first();
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /***************************************separacion de  resumenes martin 21/02/2020********************************************/
    public function getResumenDiarioBoletas($idSucursal, $idTipoMoneda, $idResumenDiario, $fecha)
    {
        try {
            $resultado = DB::select('(select ventas.IdVentas, ventas.FechaCreacion, tipo_comprobante.Descripcion as Comprobante, cliente.Nombre as Nombres, tipo_documento.CodigoSunat as CodigoDoc, cliente.NumeroDocumento as NroDoc, NULL as IdDocModificado, NULL as DocModificado, Serie, Numero, Subtotal, Gratuita, ventas.Exonerada as Descuento, IGV, Total, ventas.Estado
		 							from ventas inner join cliente on ventas.IdCliente = cliente.IdCliente inner join tipo_comprobante on ventas.IdTipoComprobante = tipo_comprobante.IdTipoComprobante inner join tipo_documento on tipo_documento.IdTipoDocumento = cliente.IdTipoDocumento where ventas.IdTipoComprobante = 1 and ventas.Estado = "Pendiente" and ventas.IdResumenDiario = ' . $idResumenDiario . ' and ventas.IdSucursal = ' . $idSucursal . ' and ventas.IdTipoMoneda = ' . $idTipoMoneda . ' and ventas.FechaCreacion > "' . $fecha . '")');

            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getResumenDiarioNotas($idSucursal, $idTipoMoneda, $idResumenDiario, $fecha)
    {
        try {
            $resultado = DB::select('(select nota_credito_debito.IdCreditoDebito, nota_credito_debito.FechaCreacion, tipo_nota.Descripcion as Comprobante, cliente.Nombre as Nombres, tipo_documento.CodigoSunat as CodigoDoc, cliente.NumeroDocumento as NroDoc, IdDocModificado, DocModificado, Serie, Numero, Subtotal, nota_credito_debito.Descuento as Descuento, IGV, Total, nota_credito_debito.Estado
									from nota_credito_debito inner join cliente on nota_credito_debito.IdCliente = cliente.IdCliente inner join tipo_nota on nota_credito_debito.IdTipoNota = tipo_nota.IdTipoNota inner join tipo_documento on tipo_documento.IdTipoDocumento = cliente.IdTipoDocumento where nota_credito_debito.IdDocModificado = 1 and nota_credito_debito.Estado = "Pendiente" and nota_credito_debito.IdResumenDiario = ' . $idResumenDiario . ' and nota_credito_debito.IdSucursal = ' . $idSucursal . ' and nota_credito_debito.IdTipoMoneda = ' . $idTipoMoneda . ' and nota_credito_debito.FechaCreacion > "' . $fecha . '")');

            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getResumenDiarioBajas($idSucursal, $idTipoMoneda, $fecha)
    {
        try {
            $resultado = DB::select('(select ventas.IdVentas, ventas.FechaCreacion, tipo_comprobante.Descripcion as Comprobante, cliente.Nombre as Nombres, tipo_documento.CodigoSunat as CodigoDoc, cliente.NumeroDocumento as NroDoc, NULL as IdDocModificado, NULL as DocModificado, Serie, Numero, Subtotal, ventas.Exonerada as Descuento, IGV, Total, ventas.Estado
		 				from ventas inner join cliente on ventas.IdCliente = cliente.IdCliente inner join tipo_comprobante on ventas.IdTipoComprobante = tipo_comprobante.IdTipoComprobante inner join tipo_documento on tipo_documento.IdTipoDocumento = cliente.IdTipoDocumento where ventas.IdTipoComprobante = 1 and ventas.Estado = "Baja Pendiente" and ventas.IdSucursal = ' . $idSucursal . ' and ventas.IdTipoMoneda = ' . $idTipoMoneda . ' and ventas.FechaCreacion > "' . $fecha . '")');

            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getResumenDiarioBoletasFiltrado($idSucursal, $idTipoMoneda, $idResumenDiario, $fechaIni, $fechaFin)
    {
        try {
            $resultado = DB::select('(select ventas.IdVentas, ventas.FechaCreacion, tipo_comprobante.Descripcion as Comprobante, cliente.Nombre as Nombres, tipo_documento.CodigoSunat as CodigoDoc, cliente.NumeroDocumento as NroDoc, NULL as IdDocModificado, NULL as DocModificado, Serie, Numero, Subtotal, Gratuita, ventas.Exonerada as Descuento, IGV, Total, Amortizacion, TipoVenta, ventas.Estado from ventas inner join cliente on ventas.IdCliente = cliente.IdCliente inner join tipo_comprobante on ventas.IdTipoComprobante = tipo_comprobante.IdTipoComprobante inner join tipo_documento on tipo_documento.IdTipoDocumento = cliente.IdTipoDocumento where ventas.IdTipoComprobante = 1 and ventas.Estado = "Pendiente" and ventas.IdResumenDiario = ' . $idResumenDiario . ' and ventas.IdSucursal = ' . $idSucursal . ' and ventas.IdTipoMoneda = ' . $idTipoMoneda . ' and ventas.FechaCreacion between "' . $fechaIni . '" and "' . $fechaFin . '")');

            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }

    }
    public function getResumenDiarioNotasFiltrado($idSucursal, $idTipoMoneda, $idResumenDiario, $fechaIni, $fechaFin)
    {
        try {
            $resultado = DB::select('(select nota_credito_debito.IdVentas, nota_credito_debito.IdTipoNota, nota_credito_debito.IdCreditoDebito, nota_credito_debito.FechaCreacion, tipo_nota.Descripcion as Comprobante, cliente.Nombre as Nombres, tipo_documento.CodigoSunat as CodigoDoc, cliente.NumeroDocumento as NroDoc, IdDocModificado, DocModificado, Serie, Numero, Subtotal, nota_credito_debito.Descuento as Descuento, IGV, Total, nota_credito_debito.Estado, TipoVenta from nota_credito_debito inner join cliente on nota_credito_debito.IdCliente = cliente.IdCliente inner join tipo_nota on nota_credito_debito.IdTipoNota = tipo_nota.IdTipoNota inner join tipo_documento on tipo_documento.IdTipoDocumento = cliente.IdTipoDocumento where nota_credito_debito.IdDocModificado = 1 and nota_credito_debito.Estado = "Pendiente" and nota_credito_debito.IdResumenDiario = ' . $idResumenDiario . ' and nota_credito_debito.IdSucursal = ' . $idSucursal . ' and nota_credito_debito.IdTipoMoneda = ' . $idTipoMoneda . ' and nota_credito_debito.FechaCreacion between "' . $fechaIni . '" and "' . $fechaFin . '")');

            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }

    }
    public function getResumenDiarioBajasFiltrado($idSucursal, $idTipoMoneda, $fechaIni, $fechaFin)
    {
        try {
            $resultado = DB::select('(select ventas.IdVentas, ventas.FechaCreacion, tipo_comprobante.Descripcion as Comprobante, cliente.Nombre as Nombres, tipo_documento.CodigoSunat as CodigoDoc, cliente.NumeroDocumento as NroDoc, NULL as IdDocModificado, NULL as DocModificado, Serie, Numero, Subtotal, ventas.Exonerada as Descuento, IGV, Total, Amortizacion, ventas.Estado, TipoVenta from ventas inner join cliente on ventas.IdCliente = cliente.IdCliente inner join tipo_comprobante on ventas.IdTipoComprobante = tipo_comprobante.IdTipoComprobante inner join tipo_documento on tipo_documento.IdTipoDocumento = cliente.IdTipoDocumento where ventas.IdTipoComprobante = 1 and  ventas.Estado = "Baja Pendiente" and ventas.IdSucursal = ' . $idSucursal . ' and ventas.IdTipoMoneda = ' . $idTipoMoneda . ' and ventas.FechaCreacion between "' . $fechaIni . '" and "' . $fechaFin . '")');

            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }

    }

    /*****************************************************************************************************************************/

    public function getResumenDiario($idSucursal, $fecha)
    {
        try {

            $resultado = DB::select('(select ventas.IdVentas, ventas.FechaCreacion, tipo_comprobante.Descripcion as Comprobante, cliente.Nombre as Nombres, tipo_documento.CodigoSunat as CodigoDoc, cliente.NumeroDocumento as NroDoc, NULL as IdDocModificado, NULL as DocModificado, Serie, Numero, Subtotal, ventas.Exonerada as Descuento, IGV, Total, ventas.Estado from ventas inner join cliente on ventas.IdCliente = cliente.IdCliente inner join tipo_comprobante on ventas.IdTipoComprobante = tipo_comprobante.IdTipoComprobante inner join tipo_documento on tipo_documento.IdTipoDocumento = cliente.IdTipoDocumento where ventas.IdTipoComprobante = 1 and (ventas.Estado = "Pendiente" or  ventas.Estado = "Baja Pendiente" )and ventas.IdSucursal = ' . $idSucursal . ' and ventas.FechaCreacion > "' . $fecha . '") union
                        (select nota_credito_debito.IdCreditoDebito, nota_credito_debito.FechaCreacion, tipo_nota.Descripcion as Comprobante, cliente.Nombre as Nombres, tipo_documento.CodigoSunat as CodigoDoc, cliente.NumeroDocumento as NroDoc, IdDocModificado, DocModificado, Serie, Numero, Subtotal, nota_credito_debito.Descuento as Descuento, IGV, Total, nota_credito_debito.Estado from nota_credito_debito inner join cliente on nota_credito_debito.IdCliente = cliente.IdCliente inner join tipo_nota on nota_credito_debito.IdTipoNota = tipo_nota.IdTipoNota inner join tipo_documento on tipo_documento.IdTipoDocumento = cliente.IdTipoDocumento where nota_credito_debito.IdDocModificado = 1 and nota_credito_debito.Estado = "Pendiente" and nota_credito_debito.IdSucursal = ' . $idSucursal . ' and nota_credito_debito.FechaCreacion > "' . $fecha . '") order by FechaCreacion desc');

            /*$resultado = DB::select('(select ventas.FechaCreacion from ventas where ventas.IdSucursal = '.$idSucursal.' and ventas.FechaCreacion > "'.$fecha.'") union
            (select nota_credito_debito.FechaCreacion from nota_credito_debito where nota_credito_debito.IdSucursal = '.$idSucursal.' and nota_credito_debito.FechaCreacion > "'.$fecha.'") order by FechaCreacion desc');*/

            return $resultado;
            /*$ventas= DB::table('ventas')
        ->join('cliente','ventas.IdCliente', '=', 'cliente.IdCliente')
        ->join('sucursal','ventas.IdSucursal', '=', 'sucursal.IdSucursal')
        ->join('usuario','ventas.IdCreacion', '=', 'usuario.IdUsuario')
        ->select('ventas.*', 'cliente.Nombre as Nombres')
        ->where('ventas.IdSucursal', $idSucursal)
        ->where('ventas.FechaCreacion', '>', $fecha)
        ->orderBy('ventas.FechaCreacion','desc')
        ->get();
        return $ventas;*/
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getReportesResumenDiario($idSucursal, $fecha)
    {
        try {
            $resumen = DB::table('resumen_diario')
                ->where('resumen_diario.IdSucursal', $idSucursal)
                ->where('resumen_diario.FechaEmitida', '>=', $fecha)
                ->orderBy('resumen_diario.FechaEnviada', 'desc')
                ->get();
            return $resumen;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getResumenDiarioFiltrado($idSucursal, $fechaIni, $fechaFin)
    {
        try {
            $resultado = DB::select('(select ventas.IdVentas, ventas.FechaCreacion, tipo_comprobante.Descripcion as Comprobante, cliente.Nombre as Nombres, tipo_documento.CodigoSunat as CodigoDoc, cliente.NumeroDocumento as NroDoc, NULL as IdDocModificado, NULL as DocModificado, Serie, Numero, Subtotal, ventas.Exonerada as Descuento, IGV, Total, ventas.Estado from ventas inner join cliente on ventas.IdCliente = cliente.IdCliente inner join tipo_comprobante on ventas.IdTipoComprobante = tipo_comprobante.IdTipoComprobante inner join tipo_documento on tipo_documento.IdTipoDocumento = cliente.IdTipoDocumento where ventas.IdTipoComprobante = 1 and (ventas.Estado = "Pendiente" or ventas.Estado = "Baja Pendiente" )and ventas.IdSucursal = ' . $idSucursal . ' and ventas.FechaCreacion between "' . $fechaIni . '" and "' . $fechaFin . '") union
                        (select nota_credito_debito.IdCreditoDebito, nota_credito_debito.FechaCreacion, tipo_nota.Descripcion as Comprobante, cliente.Nombre as Nombres, tipo_documento.CodigoSunat as CodigoDoc, cliente.NumeroDocumento as NroDoc, IdDocModificado, DocModificado, Serie, Numero, Subtotal, nota_credito_debito.Descuento as Descuento, IGV, Total, nota_credito_debito.Estado from nota_credito_debito inner join cliente on nota_credito_debito.IdCliente = cliente.IdCliente inner join tipo_nota on nota_credito_debito.IdTipoNota = tipo_nota.IdTipoNota inner join tipo_documento on tipo_documento.IdTipoDocumento = cliente.IdTipoDocumento where nota_credito_debito.IdDocModificado = 1 and nota_credito_debito.Estado = "Pendiente" and nota_credito_debito.IdSucursal = ' . $idSucursal . ' and nota_credito_debito.FechaCreacion between "' . $fechaIni . '" and "' . $fechaFin . '") order by FechaCreacion desc');

            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getResumenDiarioPendientes($idSucursal, $date)
    {
        try {
            $resultado = DB::select('(select DAY(ventas.FechaCreacion) as Day, count(*) as Total from ventas where ventas.IdTipoComprobante = 1 and ventas.Estado = "Pendiente" and ventas.IdSucursal = ' . $idSucursal . ' and ventas.FechaCreacion > "' . $date . '" GROUP BY DAY(FechaCreacion)) union
                        (select DAY(nota_credito_debito.FechaCreacion) as Day, count(*) as Total from nota_credito_debito where nota_credito_debito.IdDocModificado = 1 and nota_credito_debito.Estado = "Pendiente" and nota_credito_debito.IdSucursal = ' . $idSucursal . ' and nota_credito_debito.FechaCreacion > "' . $date . '" GROUP BY DAY(FechaCreacion))');
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getUltimoResumenDiario($idSucursal)
    {
        try {
            $resumen = DB::table('resumen_diario')
                ->where('resumen_diario.IdSucursal', $idSucursal)
                ->orderBy('resumen_diario.IdResumenDiario', 'desc')
                ->get();
            return $resumen;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getTiposCambios($idSucursal)
    {
        try {
            $resumen = DB::table('tipo_cambio')
                ->where('IdSucursal', $idSucursal)
                ->orderBy('FechaCreacion', 'desc')
                ->get();
            return $resumen;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getTipoCambioHoy($idSucursal, $fecha)
    {
        try {
            $res = DB::table('tipo_cambio')
                ->where('IdSucursal', $idSucursal)
                ->where('FechaCreacion', $fecha)
                ->get();
            return $res;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getResumenDiarioSelect($id)
    {
        try {
            $resumen = DB::table('resumen_diario')
                ->where('resumen_diario.IdResumenDiario', $id)
                ->first();
            return $resumen;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getGuiasRemisionesPendientes($idSucursal, $date)
    {
        try {
            $resultado = DB::select('(select DAY(guia_remision.FechaCreacion) as Day, count(*) as Total from guia_remision where guia_remision.Estado = "Pendiente" and guia_remision.IdSucursal = ' . $idSucursal . ' and guia_remision.FechaCreacion >= "' . $date . '" GROUP BY DAY(FechaCreacion))');
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getBajaDocumentosPendientes($idSucursal, $date)
    {
        try {
            $resultado = DB::select('select *from ventas where ventas.Estado = "Baja Pendiente" and ventas.IdSucursal = ' . $idSucursal . ' and ventas.FechaCreacion > "' . $date . '"');
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getVentasAceptadas($idSucursal, $fechaAnterior, $idVentas)
    {
        try {

            // $ventas = DB::table('ventas')
            //     ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
            //     ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
            //     ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
            //     ->select('ventas.*', 'cliente.Nombre as Nombres', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario')
            //     ->where('ventas.IdSucursal', $idSucursal)
            //     ->where('ventas.Nota', 0)
            //     ->where('ventas.Estado', 'Aceptado')
            //     ->where('ventas.FechaCreacion', '>', $fechaAnterior)
            //     ->whereIn('ventas.IdtipoComprobante', [1, 2])
            //     ->orderBy('ventas.IdVentas', 'desc')
            //     ->get();

            $ventas = DB::table('ventas')
                ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                ->select('ventas.*', 'cliente.Nombre as Nombres', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario')
                ->when($idVentas, function ($query) use ($idVentas) {
                    $query->where('ventas.IdVentas', $idVentas);
                }, function ($query) use ($idSucursal, $fechaAnterior) {
                    $query->where('ventas.IdSucursal', $idSucursal)
                        ->where('ventas.Nota', 0)
                        ->where('ventas.Estado', 'Aceptado')
                        ->where('ventas.FechaCreacion', '>', $fechaAnterior)
                        ->whereIn('ventas.IdtipoComprobante', [1, 2])
                        ->orderBy('ventas.IdVentas', 'desc');
                })

                ->get();

            return $ventas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getVentasAceptadasGuias($idSucursal, $fechaAnterior)
    {
        try {
            $ventas = DB::table('ventas')
                ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                ->select('ventas.*', 'cliente.Nombre as Nombres', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario')
                ->where('ventas.IdSucursal', $idSucursal)
                ->where('ventas.Guia', 0)
                ->where('ventas.Estado', 'Aceptado')
                ->where('ventas.FechaCreacion', '>', $fechaAnterior)
                ->whereIn('ventas.IdtipoComprobante', [1, 2])
                ->orderBy('ventas.IdVentas', 'desc')
                ->limit(500)
                ->get();
            return $ventas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getMotivos($tipo, $idMotivo)
    {
        try {
            $motivos = DB::table('motivo')
                ->where('TipoMotivo', $tipo)
                ->whereNotIn('IdMotivo', [$idMotivo])
                ->where('Estado', 'E')
                ->get();
            return $motivos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getSelectMotivo($idMotivo, $tipo)
    {
        try {
            $motivos = DB::table('motivo')
                ->where('IdMotivo', $idMotivo)
                ->where('Estado', 'E')
                ->first();
            return $motivos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getNotasCreditoDebito($codigo, $factura, $tipo)
    {
        try {
            $notas = DB::table('nota_credito_debito')
                ->join('sucursal', 'nota_credito_debito.IdSucursal', '=', 'sucursal.IdSucursal')
                ->where('IdTipoNota', $tipo)
                ->where('sucursal.CodigoCliente', $codigo)
                ->where('IdDocModificado', $factura)
                ->orderBy('IdCreditoDebito', 'desc')
                ->get();
            return $notas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getNotasAll($idSucursal)
    {
        try {
            $notas = DB::table('nota_credito_debito')
                ->join('cliente', 'nota_credito_debito.IdCliente', '=', 'cliente.IdCliente')
                ->join('tipo_nota', 'nota_credito_debito.IdTipoNota', '=', 'tipo_nota.IdTipoNota')
                ->join('motivo', 'nota_credito_debito.IdMotivo', '=', 'motivo.IdMotivo')
                ->select('nota_credito_debito.*', 'tipo_nota.Descripcion as TipoNota', 'motivo.Descripcion as Motivo', 'cliente.Nombre as Nombres')
                ->where('nota_credito_debito.IdSucursal', $idSucursal)
                ->whereRaw('YEAR(nota_credito_debito.FechaCreacion)=YEAR(NOW())')
                ->whereRaw('MONTH(nota_credito_debito.FechaCreacion) = MONTH(NOW())')
                ->orderBy('IdCreditoDebito', 'desc')
                ->get();
            return $notas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getNotasAllFiltrado($idSucursal, $tipoNota, $fecha, $fechaIni, $fechaFin)
    {
        try {
            $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            if ($tipoNota == 0) {
                $notas = DB::table('nota_credito_debito')
                    ->join('cliente', 'nota_credito_debito.IdCliente', '=', 'cliente.IdCliente')
                    ->join('tipo_nota', 'nota_credito_debito.IdTipoNota', '=', 'tipo_nota.IdTipoNota')
                    ->join('motivo', 'nota_credito_debito.IdMotivo', '=', 'motivo.IdMotivo')
                    ->select('nota_credito_debito.*', 'tipo_nota.Descripcion as TipoNota', 'motivo.Descripcion as Motivo', 'cliente.Nombre as Nombres')
                    ->where('nota_credito_debito.IdSucursal', $idSucursal)
                    ->whereBetween('nota_credito_debito.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->orderBy('IdCreditoDebito', 'desc')
                    ->get();
                return $notas;
            } else {
                $notas = DB::table('nota_credito_debito')
                    ->join('cliente', 'nota_credito_debito.IdCliente', '=', 'cliente.IdCliente')
                    ->join('tipo_nota', 'nota_credito_debito.IdTipoNota', '=', 'tipo_nota.IdTipoNota')
                    ->join('motivo', 'nota_credito_debito.IdMotivo', '=', 'motivo.IdMotivo')
                    ->select('nota_credito_debito.*', 'tipo_nota.Descripcion as TipoNota', 'motivo.Descripcion as Motivo', 'cliente.Nombre as Nombres')
                    ->where('nota_credito_debito.IdSucursal', $idSucursal)
                    ->where('nota_credito_debito.IdTipoNota', $tipoNota)
                    ->whereBetween('nota_credito_debito.FechaCreacion', [$fechas[0], $fechas[1]])
                    ->orderBy('IdCreditoDebito', 'desc')
                    ->get();
                return $notas;
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getNotaSelect($id)
    {
        try {
            $nota = DB::table('nota_credito_debito')
                ->join('cliente', 'nota_credito_debito.IdCliente', '=', 'cliente.IdCliente')
                ->join('tipo_documento', 'cliente.IdTipoDocumento', '=', 'tipo_documento.IdTipoDocumento')
                ->join('sucursal', 'nota_credito_debito.IdSucursal', '=', 'sucursal.IdSucursal')
                ->join('usuario', 'nota_credito_debito.IdUsuarioCreacion', '=', 'usuario.IdUsuario')
                ->join('tipo_nota', 'tipo_nota.IdTipoNota', '=', 'nota_credito_debito.IdTipoNota')
                ->join('motivo', 'motivo.IdMotivo', '=', 'nota_credito_debito.IdMotivo')
                ->join('ventas', 'ventas.IdVentas', '=', 'nota_credito_debito.IdVentas')
                ->join('tipo_moneda', 'ventas.IdTipoMoneda', '=', 'tipo_moneda.IdTipoMoneda')
                ->select('nota_credito_debito.*', 'ventas.IdTipoPago as TipoPago', 'ventas.IdTipoComprobante as TipoComprobante', 'ventas.PorcentajeDetraccion as PorcentajeDetraccion', 'ventas.Retencion as Retencion', 'ventas.PlazoCredito as PlazoCredito', 'cliente.Nombre as Nombres', 'cliente.RazonSocial', 'cliente.Direccion as DirCliente', 'cliente.NumeroDocumento', 'cliente.Email', 'cliente.Telefono as TelfCliente', 'tipo_nota.Descripcion as TipoNota', 'tipo_moneda.CodigoMoneda as CodMoneda',
                    'sucursal.Direccion as Local', 'sucursal.Nombre as Sucursal', 'sucursal.Ciudad', 'sucursal.Telefono as TelfSucursal', 'usuario.Nombre as Usuario', 'tipo_documento.Descripcion as TipoDoc', 'motivo.Descripcion as Motivo')
                ->where('nota_credito_debito.IdCreditoDebito', $id)
                ->first();
            return $nota;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getItemsNotas($id)
    {
        try {
            $ventas = DB::table('nota_detalle')
                ->join('articulo', 'nota_detalle.IdArticulo', '=', 'articulo.IdArticulo')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->select('nota_detalle.*', 'nota_detalle.Codigo as Cod', 'articulo.*', 'unidad_medida.Nombre as UniMedida', 'nota_detalle.Descripcion as Descrip')
                ->where('nota_detalle.IdCreditoDebito', $id)
                ->get();
            return $ventas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getNcParciales($id)
    {
        try {
            $ncParciales = DB::table('nota_credito_debito')
                ->where('IdVentas', $id)
                ->get();
            return $ncParciales;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getItemsTotalNCComprobantes($id)
    {
        try {
            $ventas = DB::table('nota_detalle')
                ->join('nota_credito_debito', 'nota_detalle.IdCreditoDebito', '=', 'nota_credito_debito.IdCreditoDebito')
                ->select('nota_detalle.*')
                ->where('nota_credito_debito.IdVentas', $id)
                ->whereIn('nota_credito_debito.Estado', ['Aceptado', 'Baja Pendiente'])
                ->get();
            return $ventas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getGuiasRemision($idSucursal)
    {
        try {
            $guias = DB::table('guia_remision')
                ->join('cliente', 'guia_remision.IdCliente', '=', 'cliente.IdCliente')
                ->join('motivo', 'guia_remision.IdMotivo', '=', 'motivo.IdMotivo')
                ->join('tipo_documento', 'guia_remision.IdTipoDocumento', '=', 'tipo_documento.CodigoSunat')
                ->select('guia_remision.*', 'motivo.Descripcion as Motivo', 'cliente.Nombre as Nombres', 'tipo_documento.Descripcion as TipoDocumento', 'tipo_documento.CodigoSunat as CodigoSunatTipoDoc')
                ->where('guia_remision.IdSucursal', $idSucursal)
                ->orderBy('IdGuiaRemision', 'desc')
                ->get();
            return $guias;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getGuiasRemisionFiltrado($idSucursal, $fecha, $fechaIni, $fechaFin)
    {
        try {
            $fechas = $this->getFechaFiltro($fecha, $fechaIni, $fechaFin);
            $guias = DB::table('guia_remision')
                ->join('cliente', 'guia_remision.IdCliente', '=', 'cliente.IdCliente')
                ->join('motivo', 'guia_remision.IdMotivo', '=', 'motivo.IdMotivo')
                ->join('tipo_documento', 'guia_remision.IdTipoDocumento', '=', 'tipo_documento.CodigoSunat')
                ->select('guia_remision.*', 'motivo.Descripcion as Motivo', 'cliente.Nombre as Nombres', 'tipo_documento.Descripcion as TipoDocumento', 'tipo_documento.CodigoSunat as CodigoSunatTipoDoc')
                ->where('guia_remision.Estado', 'Aceptado')
                ->where('guia_remision.IdSucursal', $idSucursal)
                ->whereBetween('guia_remision.FechaEmision', [$fechas[0], $fechas[1]])
                ->orWhere('guia_remision.Estado', 'Rechazo')
                ->where('guia_remision.IdSucursal', $idSucursal)
                ->whereBetween('guia_remision.FechaEmision', [$fechas[0], $fechas[1]])
                ->orderBy('IdGuiaRemision', 'desc')
                ->get();
            return $guias;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getGuiaRemisionSelect($id)
    {
        try {
            $notas = DB::table('guia_remision')
                ->join('cliente', 'guia_remision.IdCliente', '=', 'cliente.IdCliente')
                ->join('motivo', 'guia_remision.IdMotivo', '=', 'motivo.IdMotivo')
                ->join('tipo_documento', 'guia_remision.IdTipoDocumento', '=', 'tipo_documento.CodigoSunat')
                ->join('sucursal', 'guia_remision.IdSucursal', '=', 'sucursal.IdSucursal')
                ->join('usuario', 'guia_remision.IdUsuario', '=', 'usuario.IdUsuario')
                ->select('guia_remision.*', 'motivo.Descripcion as Motivo', 'cliente.Nombre as Nombres', 'tipo_documento.Descripcion as TipoDocumento', 'tipo_documento.CodigoSunat as CodigoSunatTipoDoc', 'sucursal.Direccion as Local', 'sucursal.Nombre as Sucursal', 'sucursal.Principal', 'sucursal.Ciudad', 'sucursal.Telefono as TelfSucursal',
                    'cliente.RazonSocial', 'cliente.Direccion as DirCliente', 'cliente.NumeroDocumento as NumDocumento', 'cliente.Email', 'cliente.Telefono as TelfCliente', 'usuario.Nombre as Usuario')
                ->where('guia_remision.IdGuiaRemision', $id)
                ->orderBy('IdGuiaRemision', 'desc')
                ->first();
            return $notas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getItemsGuias($id)
    {
        try {
            $ventas = DB::table('guia_detalle')
                ->join('articulo', 'guia_detalle.IdArticulo', '=', 'articulo.IdArticulo')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->select('guia_detalle.*', 'guia_detalle.Codigo as Cod', 'articulo.*', 'unidad_medida.Nombre as UniMedida')
                ->where('guia_detalle.IdGuiaRemision', $id)
                ->get();
            return $ventas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getDocumentos($idSucursal, $fecha)
    {
        try {
            $ventas = DB::table('ventas')
                ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                ->select('ventas.*', 'cliente.Nombre as Nombres', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario')
                ->where('ventas.IdSucursal', $idSucursal)
                ->where('ventas.FechaModificacion', '>', $fecha)
                ->where('ventas.Estado', 'Baja Pendiente')
                ->orderBy('ventas.FechaCreacion', 'desc')
                ->get();
            return $ventas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getDocumentosFactura($idSucursal, $fecha)
    { //esto se agrego el 21-01-2020
        try {
            $ventas = DB::table('ventas')
                ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                ->select('ventas.*', 'cliente.Nombre as Nombres', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario')
                ->where('ventas.IdSucursal', $idSucursal)
                ->where('ventas.FechaModificacion', '>', $fecha)
                ->where('ventas.IdTipoComprobante', 2)
                ->where('ventas.Estado', 'Baja Pendiente')
                ->orderBy('ventas.FechaCreacion', 'desc')
                ->get();
            return $ventas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getBajaDocumentos($idSucursal, $estado)
    {
        try {
            if ($estado == null) {
                $bajas = DB::table('baja_documentos')
                    ->where('IdSucursal', $idSucursal)
                    ->orderBy('FechaEnviada', 'desc')
                    ->get();

                for ($i = 0; $i < count($bajas); $i++) {
                    if ($bajas[$i]->TipoDocumento == 1) {
                        $bajas[$i]->Documento = DB::table('ventas')
                            ->select('Serie', 'Numero', 'Total', 'IdTipoMoneda')
                            ->where('IdVentas', $bajas[$i]->IdVentas)
                            ->first();
                    } else {
                        $bajas[$i]->Documento = DB::table('nota_credito_debito')
                            ->select('Serie', 'Numero', 'Total', 'IdTipoMoneda')
                            ->where('IdCreditoDebito', $bajas[$i]->IdVentas)
                            ->first();
                    }
                }
            } else {
                $bajas = DB::table('baja_documentos')
                    ->select('baja_documentos.*')
                    ->where('IdSucursal', $idSucursal)
                    ->where('Estado', $estado)
                    ->orderBy('FechaEnviada', 'desc')
                    ->get();
            }

            return $bajas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getBajaDocumentoSelect($id)
    {
        try {
            $baja = DB::table('baja_documentos')
                ->where('IdBajaDoc', $id)
                ->first();
            return $baja;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function obtenerBajaDocumentosEnviar($idSucursal, $fecha1, $fecha2)
    {
        try {
            $ventas = DB::table('ventas')
                ->join('cliente', 'ventas.IdCliente', '=', 'cliente.IdCliente')
                ->select('ventas.*', 'cliente.Nombre as Nombres')
                ->where('ventas.IdSucursal', $idSucursal)
                ->whereBetween('ventas.FechaModificacion', [$fecha1, $fecha2])
                ->where('ventas.Estado', 'Baja Pendiente')
                ->orderBy('ventas.FechaModificacion', 'desc')
                ->get();
            return $ventas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getFacturaBajaActual($idSucursal, $fecha)
    { //esto se agrego el 21-01-2020
        try {

            $resultado = DB::select('(select ventas.FechaCreacion as Fecha, cliente.Nombre as Nombres, ventas.Serie, ventas.Numero, ventas.MotivoAnulacion as Motivo, ventas.Estado,  "Factura" as Tipo from ventas inner join cliente on ventas.IdCliente = cliente.IdCliente where ventas.IdSucursal = ' . $idSucursal . ' and ventas.FechaCreacion > "' . $fecha . '" and ventas.IdTipoComprobante = 2 and ventas.Estado = "Baja Pendiente") union
                                    (select nota_credito_debito.FechaCreacion as Fecha, cliente.Nombre as Nombres, nota_credito_debito.Serie, nota_credito_debito.Numero, nota_credito_debito.MotivoBaja as Motivo, nota_credito_debito.Estado, "Nota de Crédito" as Tipo from nota_credito_debito inner join cliente on nota_credito_debito.IdCliente = cliente.IdCliente where nota_credito_debito.IdSucursal = ' . $idSucursal . ' and nota_credito_debito.FechaCreacion > "' . $fecha . '" and nota_credito_debito.IdDocModificado = 2 and nota_credito_debito.Estado = "Baja Pendiente") order by Fecha desc');

            /*$resultado = DB::select('(select if(IdCompras > 0, "Compra", null) as Comprobante, FechaCreacion, IdTipoPago, Serie, Numero, SUM(Total) as Total, Estado from compras where IdSucursal = '.$idSucursal.' group by IdTipoPago, Estado) union
            (select if(IdVentas > 0, "Venta", null) as Comprobante, FechaCreacion, IdTipoPago, Serie, Numero, SUM(Total) as Total, Estado from ventas where IdSucursal = '.$idSucursal.' group by IdTipoPago, Estado) order by FechaCreacion desc');
             */
            /*$ventas= DB::table('ventas')
            ->join('cliente','ventas.IdCliente', '=', 'cliente.IdCliente')
            ->select('ventas.*', 'cliente.Nombre as Nombres')
            ->where('ventas.IdSucursal', $idSucursal)
            ->where('ventas.FechaCreacion', '>', $fecha)
            ->where('ventas.IdTipoComprobante', 2)
            ->where('ventas.Estado','Baja Pendiente')
            ->orderBy('ventas.FechaCreacion','desc')
            ->get();
            return $ventas;*/
            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function obtenerBajaDocumentosEnviarFactura($idSucursal, $fecha1, $fecha2)
    { //  se puso el 21-01-2020
        try {

            $resultado = DB::select('(select ventas.IdVentas as IdDoc, ventas.FechaCreacion as Fecha, cliente.Nombre as Nombres, ventas.Serie, ventas.Numero, ventas.MotivoAnulacion as Motivo, ventas.Estado, "Factura" as Tipo from ventas inner join cliente on ventas.IdCliente = cliente.IdCliente where ventas.IdSucursal = ' . $idSucursal . ' and ventas.FechaCreacion between "' . $fecha1 . '" and "' . $fecha2 . '" and ventas.IdTipoComprobante = 2 and ventas.Estado = "Baja Pendiente") union
                                    (select nota_credito_debito.IdCreditoDebito as IdDoc, nota_credito_debito.FechaCreacion as Fecha, cliente.Nombre as Nombres, nota_credito_debito.Serie, nota_credito_debito.Numero, nota_credito_debito.MotivoBaja as Motivo, nota_credito_debito.Estado, "Nota de Crédito" as Tipo from nota_credito_debito inner join cliente on nota_credito_debito.IdCliente = cliente.IdCliente where nota_credito_debito.IdSucursal = ' . $idSucursal . ' and nota_credito_debito.FechaCreacion between "' . $fecha1 . '" and "' . $fecha2 . '" and nota_credito_debito.IdDocModificado = 2 and nota_credito_debito.Estado = "Baja Pendiente") order by Fecha desc');

            return $resultado;

            /*$ventas= DB::table('ventas')
        ->join('cliente','ventas.IdCliente', '=', 'cliente.IdCliente')
        ->select('ventas.*', 'cliente.Nombre as Nombres')
        ->where('ventas.IdSucursal', $idSucursal)
        ->whereBetween('ventas.FechaCreacion', [$fecha1, $fecha2])
        ->where('ventas.IdTipoComprobante', 2)
        ->where('ventas.Estado','Baja Pendiente')
        ->orderBy('ventas.FechaModificacion','desc')
        ->get();
        return $ventas;*/
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getCorrelativoBajaDocumento($idSucursal, $fecha)
    {
        try {
            $ventas = DB::table('baja_documentos')
                ->select(DB::raw("count(IdBajaDoc) as Cantidad"))
                ->where('IdSucursal', $idSucursal)
                ->where('FechaEnviada', '>', $fecha)
                ->orderBy('FechaEnviada', 'desc')
                ->first();
            return $ventas;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getRubros()
    {
        try {
            $rubros = DB::table('rubro')
                ->where('Estado', 'E')
                ->get();
            return $rubros;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getBuscarProductoSucursal($idSucursal)
    {
        try {
            $productos = DB::table('articulo')
                ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                ->select('articulo.*', 'marca.Nombre as Marca')
                ->where('articulo.IdSucursal', $idSucursal)
                ->where('articulo.IdTipo', 1)
                ->where('articulo.Estado', 'E')
                ->orderBy('articulo.IdArticulo', 'desc')
                ->get();
            return $productos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getBuscarProducto($descripcion, $idSucursal)
    {
        try {
            $productos = DB::table('articulo')
                ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                ->select('articulo.*', 'marca.Nombre as Marca')
                ->where('articulo.Descripcion', $descripcion)
                ->where('articulo.IdSucursal', $idSucursal)
                ->where('articulo.Estado', 'E')
                ->orderBy('IdArticulo', 'desc')
                ->get();
            return $productos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getBuscarProductoAlmacen($descripcion, $idAlmacen)
    {
        try {
            //$productos = DB::select('select *from almacen_producto where levenshtein("'.$descripcion.'", "Descripcion") between 0 and 4');
            $productos = DB::table('almacen_producto')
                ->join('marca', 'almacen_producto.IdMarca', '=', 'marca.IdMarca')
                ->select('almacen_producto.IdAlmacenProducto as IdArticulo', 'almacen_producto.Descripcion', 'almacen_producto.Stock', 'marca.Nombre as Marca')
                ->where('almacen_producto.Descripcion', $descripcion)
                ->where('almacen_producto.IdAlmacen', $idAlmacen)
                ->orderBy('IdAlmacenProducto', 'desc')
                ->get();
            return $productos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getProductoNew($idSucursal)
    {
        try {
            //if ($tipo == 1) {

            $productos = DB::table('articulo')
                ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                ->select('articulo.*', 'marca.Nombre as Marca', 'unidad_medida.Nombre as UM')
                ->where('IdTipo', 1)
                ->where('articulo.IdSucursal', $idSucursal)
                ->where('articulo.Estado', 'E')
                ->orderBy('articulo.Descripcion', 'asc')
                ->get();
            return $productos;
            /*} else {
        $productos = DB::table('articulo')
        ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
        ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
        ->select('articulo.*', 'marca.Nombre as Marca', 'unidad_medida.Nombre as UM')
        ->where('IdTipo', 1)
        ->where('articulo.IdSucursal', $idSucursal)
        ->where('articulo.Estado', 'E')
        ->orderBy('articulo.Descripcion', 'asc')
        ->get();
        return $productos;
        }*/
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    /*****************************************buscar productos nueva  forma  para transpasos *****************/
    public function getBuscarProductoNew($codInterno, $idSucursal, $tipo)
    {
        try {
            if ($tipo == 1) {
                $productos = DB::table('articulo')
                    ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                    ->select('articulo.*', 'marca.Nombre as Marca')
                    ->where('IdTipo', 1)
                    ->where('articulo.CodigoInterno', $codInterno)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orderBy('IdArticulo', 'desc')
                    ->get();
                return $productos;
            } else {
                $productos = DB::table('articulo')
                    ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                    ->select('articulo.*', 'marca.Nombre as Marca')
                    ->where('articulo.CodigoInterno', $codInterno)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->orderBy('IdArticulo', 'desc')
                    ->get();
                return $productos;
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getBuscarProductoAlmacenNew($codInterno, $idAlmacen)
    {
        try {
            //$productos = DB::select('select *from almacen_producto where levenshtein("'.$descripcion.'", "Descripcion") between 0 and 4');
            $productos = DB::table('almacen_producto')
                ->join('marca', 'almacen_producto.IdMarca', '=', 'marca.IdMarca')
                ->join('articulo', 'articulo.IdArticulo', '=', 'almacen_producto.IdArticulo')
                ->select('almacen_producto.IdArticulo', 'almacen_producto.Descripcion', 'almacen_producto.Stock', 'almacen_producto.IdMarca', 'almacen_producto.Codigo', 'almacen_producto.CodigoInterno', 'marca.Nombre as Marca', 'articulo.Precio')
                ->where('almacen_producto.CodigoInterno', $codInterno)
                ->where('almacen_producto.IdAlmacen', $idAlmacen)
                ->orderBy('IdAlmacenProducto', 'desc')
                ->get();
            return $productos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /*****************************************************************************************************/

    /*********************************funciones  para  sub permisos y  sub  niveles******************************************/
    public function getSubPermisos($idUsuario)
    {
        try {
            $subpermisos = DB::table('usuario_sub_permisos')
                ->where('IdUsuario', $idUsuario)
                ->where('Estado', 'E')
                ->get();
            return $subpermisos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getSubNiveles($idUsuario)
    {
        try {
            $subniveles = DB::table('usuario_sub_nivel')
                ->where('IdUsuario', $idUsuario)
                ->where('estado', 'E')
                ->get();
            return $subniveles;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /****************************ajax  de buscar y paginacion a productos de sucursales secundarias*************************/
    public function buscarAjaxProdSucursal($texto, $tipoMoneda, $idSucursal, $idCategoria)
    {
        try {
            if ($idCategoria == 0) {
                $articulos = DB::table('articulo')
                    ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                    ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                    ->join('categoria', 'articulo.IdCategoria', '=', 'categoria.IdCategoria')
                    ->join('sucursal', 'articulo.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->select('articulo.*', 'marca.Nombre as Marca', 'unidad_medida.Nombre as UM', 'categoria.Nombre as Categoria', 'sucursal.Exonerado as valorCheckSucursalFactExonerado')
                    ->where('articulo.Descripcion', 'like', '%' . $texto . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orWhere('articulo.Codigo', 'like', '%' . $texto . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orWhere('articulo.Precio', 'like', '%' . $texto . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orWhere('marca.Nombre', 'like', '%' . $texto . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orderBy('articulo.Descripcion', 'asc')
                    ->paginate(12);
            } else {
                $articulos = DB::table('articulo')
                    ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                    ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                    ->join('categoria', 'articulo.IdCategoria', '=', 'categoria.IdCategoria')
                    ->join('sucursal', 'articulo.IdSucursal', '=', 'sucursal.IdSucursal')
                    ->select('articulo.*', 'marca.Nombre as Marca', 'unidad_medida.Nombre as UM', 'categoria.Nombre as Categoria', 'sucursal.Exonerado as valorCheckSucursalFactExonerado')
                    ->where('articulo.Descripcion', 'like', '%' . $texto . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('categoria.IdCategoria', $idCategoria)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orWhere('articulo.Codigo', 'like', '%' . $texto . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('categoria.IdCategoria', $idCategoria)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orWhere('articulo.Precio', 'like', '%' . $texto . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('categoria.IdCategoria', $idCategoria)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orWhere('marca.Nombre', 'like', '%' . $texto . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('categoria.IdCategoria', $idCategoria)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orderBy('articulo.Descripcion', 'asc')
                    ->paginate(12);
            }
            return $articulos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function buscarAjaxProdNoMarcaSucursal($texto, $tipoMoneda, $idSucursal, $idCategoria)
    {
        try {
            if ($idCategoria == 0) {
                $articulos = DB::table('articulo')
                    ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                    ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                    ->join('categoria', 'articulo.IdCategoria', '=', 'categoria.IdCategoria')
                    ->select('articulo.*', 'marca.Nombre as Marca', 'unidad_medida.Nombre as UM', 'categoria.Nombre as Categoria')
                    ->where('articulo.Descripcion', 'like', '%' . $texto . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orWhere('articulo.Codigo', 'like', '%' . $texto . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orWhere('articulo.Precio', 'like', '%' . $texto . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orWhere('marca.Nombre', 'like', '%' . $texto . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orderBy('articulo.Descripcion', 'asc')
                    ->paginate(12);
            } else {
                $articulos = DB::table('articulo')
                    ->join('marca', 'articulo.IdMarca', '=', 'marca.IdMarca')
                    ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
                    ->join('categoria', 'articulo.IdCategoria', '=', 'categoria.IdCategoria')
                    ->select('articulo.*', 'marca.Nombre as Marca', 'unidad_medida.Nombre as UM', 'categoria.Nombre as Categoria')
                    ->where('articulo.Descripcion', 'like', '%' . $texto . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('categoria.IdCategoria', $idCategoria)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orWhere('articulo.Codigo', 'like', '%' . $texto . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('categoria.IdCategoria', $idCategoria)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orWhere('articulo.Precio', 'like', '%' . $texto . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('categoria.IdCategoria', $idCategoria)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orWhere('marca.Nombre', 'like', '%' . $texto . '%')
                    ->where('IdTipo', 1)
                    ->where('IdTipoMoneda', $tipoMoneda)
                    ->where('categoria.IdCategoria', $idCategoria)
                    ->where('articulo.IdSucursal', $idSucursal)
                    ->where('articulo.Estado', 'E')
                    ->orderBy('articulo.Descripcion', 'asc')
                    ->paginate(12);
            }
            return $articulos;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getItemsYpaquetePromocional($idVentas)
    {
        $items = DB::table('ventas_articulo')
            ->join('articulo', 'ventas_articulo.IdArticulo', '=', 'articulo.IdArticulo')
            ->join('unidad_medida', 'articulo.IdUnidadMedida', '=', 'unidad_medida.IdUnidadMedida')
            ->select('ventas_articulo.*', 'ventas_articulo.Codigo as Cod', 'articulo.*', 'articulo.Descripcion as Descripcion')
            ->where('ventas_articulo.IdVentas', $idVentas)
            ->whereNull('ventas_articulo.IdPaquetePromocional')
            ->get();

        $itemsPaquetePromocional = DB::table('ventas_articulo')
            ->join('paquetes_promocionales', 'ventas_articulo.IdPaquetePromocional', '=', 'paquetes_promocionales.IdPaquetePromocional')
            ->select('ventas_articulo.*', 'ventas_articulo.Codigo as Cod', 'paquetes_promocionales.*', 'paquetes_promocionales.NombrePaquete as Descripcion')
            ->where('ventas_articulo.IdVentas', $idVentas)
            ->where('ventas_articulo.IdPaquetePromocional', '>', 0)
            ->get();
        $ventas = $items->concat($itemsPaquetePromocional);

        for ($i = 0; $i < count($ventas); $i++) {
            if ($ventas[$i]->IdTipo == 1) {
                $ventas[$i]->NombreMarca = DB::table('marca')
                    ->join('articulo', 'marca.IdMarca', '=', 'articulo.IdMarca')
                    ->select('marca.Nombre as nombreMarca')
                    ->where('IdArticulo', $ventas[$i]->IdArticulo)
                    ->first();
            } else {
                $ventas[$i]->NombreMarca = "";
            }
        }

        return $ventas;

    }

    public function getArchivosXML($idSucursal, $tipoComprobante, $anio, $mes)
    {
        try {
            if ($anio == 1) {
                if ($tipoComprobante == 1) {
                    $resultado = DB::table('ventas')
                        ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('tipo_comprobante', 'tipo_comprobante.IdTipoComprobante', '=', 'ventas.IdTipoComprobante')
                        ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                        ->select('ventas.*', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario', 'tipo_comprobante.Descripcion')
                        ->where('ventas.IdSucursal', $idSucursal)
                        ->whereNull('ventas.RutaXml')
                        ->whereIn('ventas.IdTipoComprobante', [1, 2])
                        ->orderBy('ventas.FechaCreacion', 'desc')
                        ->get();
                }
                if ($tipoComprobante == 2) {
                    $resultado = DB::table('nota_credito_debito')
                        ->join('sucursal', 'nota_credito_debito.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('tipo_nota', 'tipo_nota.IdTipoNota', '=', 'nota_credito_debito.IdTipoNota')
                        ->join('usuario', 'nota_credito_debito.IdUsuarioCreacion', '=', 'usuario.IdUsuario')
                        ->select('nota_credito_debito.*', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario', 'tipo_nota.Descripcion')
                        ->where('nota_credito_debito.IdSucursal', $idSucursal)
                        ->whereNull('nota_credito_debito.RutaXml')
                        ->orderBy('nota_credito_debito.FechaCreacion', 'desc')
                        ->get();
                }
                if ($tipoComprobante == 3) {
                    $resultado = DB::table('guia_remision')
                        ->join('sucursal', 'guia_remision.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario', 'guia_remision.IdUsuario', '=', 'usuario.IdUsuario')
                        ->select('guia_remision.*', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario')
                        ->where('guia_remision.IdSucursal', $idSucursal)
                        ->whereNull('guia_remision.RutaXml')
                        ->orderBy('guia_remision.FechaCreacion', 'desc')
                        ->get();
                }
                if ($tipoComprobante == 4) {
                    $resultado = DB::table('resumen_diario')
                        ->join('sucursal', 'resumen_diario.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->select('resumen_diario.*', 'sucursal.Nombre as Sucursal', 'resumen_diario.FechaEmitida as FechaCreacion')
                        ->where('resumen_diario.IdSucursal', $idSucursal)
                        ->whereNull('resumen_diario.RutaXml')
                        ->orderBy('resumen_diario.FechaEmitida', 'desc')
                        ->get();
                }
                if ($tipoComprobante == 5) {
                    $resultado = DB::table('baja_documentos')
                        ->join('sucursal', 'baja_documentos.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario', 'baja_documentos.IdUsuario', '=', 'usuario.IdUsuario')
                        ->select('baja_documentos.*', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario', 'baja_documentos.FechaEmitida as FechaCreacion')
                        ->where('baja_documentos.IdSucursal', $idSucursal)
                        ->whereNull('baja_documentos.RutaXml')
                        ->orderBy('baja_documentos.FechaEmitida', 'desc')
                        ->get();
                }
            } else {
                if ($mes == 0) {
                    if ($tipoComprobante == 1) {
                        //->whereRaw('MONTH(ventas.FechaCreacion) = '.$mes) ->Condicion mensual
                        $resultado = DB::table('ventas')
                            ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                            ->join('tipo_comprobante', 'tipo_comprobante.IdTipoComprobante', '=', 'ventas.IdTipoComprobante')
                            ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                            ->select('ventas.*', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario', 'tipo_comprobante.Descripcion')
                            ->where('ventas.IdSucursal', $idSucursal)
                            ->whereNull('ventas.RutaXml')
                            ->whereRaw('YEAR(ventas.FechaCreacion) = ' . $anio)
                            ->whereIn('ventas.IdTipoComprobante', [1, 2])
                            ->orderBy('ventas.FechaCreacion', 'desc')
                            ->get();
                    }
                    if ($tipoComprobante == 2) {
                        $resultado = DB::table('nota_credito_debito')
                            ->join('sucursal', 'nota_credito_debito.IdSucursal', '=', 'sucursal.IdSucursal')
                            ->join('tipo_nota', 'tipo_nota.IdTipoNota', '=', 'nota_credito_debito.IdTipoNota')
                            ->join('usuario', 'nota_credito_debito.IdUsuarioCreacion', '=', 'usuario.IdUsuario')
                            ->select('nota_credito_debito.*', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario', 'tipo_nota.Descripcion')
                            ->where('nota_credito_debito.IdSucursal', $idSucursal)
                            ->whereNull('nota_credito_debito.RutaXml')
                            ->whereRaw('YEAR(nota_credito_debito.FechaCreacion) = ' . $anio)
                            ->orderBy('nota_credito_debito.FechaCreacion', 'desc')
                            ->get();
                    }
                    if ($tipoComprobante == 3) {
                        $resultado = DB::table('guia_remision')
                            ->join('sucursal', 'guia_remision.IdSucursal', '=', 'sucursal.IdSucursal')
                            ->join('usuario', 'guia_remision.IdUsuario', '=', 'usuario.IdUsuario')
                            ->select('guia_remision.*', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario')
                            ->where('guia_remision.IdSucursal', $idSucursal)
                            ->whereNull('guia_remision.RutaXml')
                            ->whereRaw('YEAR(guia_remision.FechaCreacion) = ' . $anio)
                            ->orderBy('guia_remision.FechaCreacion', 'desc')
                            ->get();
                    }
                    if ($tipoComprobante == 4) {
                        $resultado = DB::table('resumen_diario')
                            ->join('sucursal', 'resumen_diario.IdSucursal', '=', 'sucursal.IdSucursal')
                            ->select('resumen_diario.*', 'sucursal.Nombre as Sucursal', 'resumen_diario.FechaEmitida as FechaCreacion')
                            ->where('resumen_diario.IdSucursal', $idSucursal)
                            ->whereNull('resumen_diario.RutaXml')
                            ->whereRaw('YEAR(resumen_diario.FechaEmitida) = ' . $anio)
                            ->orderBy('resumen_diario.FechaEmitida', 'desc')
                            ->get();
                    }
                    if ($tipoComprobante == 5) {
                        $resultado = DB::table('baja_documentos')
                            ->join('sucursal', 'baja_documentos.IdSucursal', '=', 'sucursal.IdSucursal')
                            ->join('usuario', 'baja_documentos.IdUsuario', '=', 'usuario.IdUsuario')
                            ->select('baja_documentos.*', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario', 'baja_documentos.FechaEmitida as FechaCreacion')
                            ->where('baja_documentos.IdSucursal', $idSucursal)
                            ->whereNull('baja_documentos.RutaXml')
                            ->whereRaw('YEAR(baja_documentos.FechaEmitida) = ' . $anio)
                            ->orderBy('baja_documentos.FechaEmitida', 'desc')
                            ->get();
                    }
                } else {
                    if ($tipoComprobante == 1) {
                        $resultado = DB::table('ventas')
                            ->join('sucursal', 'ventas.IdSucursal', '=', 'sucursal.IdSucursal')
                            ->join('tipo_comprobante', 'tipo_comprobante.IdTipoComprobante', '=', 'ventas.IdTipoComprobante')
                            ->join('usuario', 'ventas.IdCreacion', '=', 'usuario.IdUsuario')
                            ->select('ventas.*', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario', 'tipo_comprobante.Descripcion')
                            ->where('ventas.IdSucursal', $idSucursal)
                            ->whereNull('ventas.RutaXml')
                            ->whereRaw('YEAR(ventas.FechaCreacion) = ' . $anio)
                            ->whereRaw('MONTH(ventas.FechaCreacion) = ' . $mes)
                            ->whereIn('ventas.IdTipoComprobante', [1, 2])
                            ->orderBy('ventas.FechaCreacion', 'desc')
                            ->get();
                    }
                    if ($tipoComprobante == 2) {
                        $resultado = DB::table('nota_credito_debito')
                            ->join('sucursal', 'nota_credito_debito.IdSucursal', '=', 'sucursal.IdSucursal')
                            ->join('tipo_nota', 'tipo_nota.IdTipoNota', '=', 'nota_credito_debito.IdTipoNota')
                            ->join('usuario', 'nota_credito_debito.IdUsuarioCreacion', '=', 'usuario.IdUsuario')
                            ->select('nota_credito_debito.*', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario', 'tipo_nota.Descripcion')
                            ->where('nota_credito_debito.IdSucursal', $idSucursal)
                            ->whereNull('nota_credito_debito.RutaXml')
                            ->whereRaw('YEAR(nota_credito_debito.FechaCreacion) = ' . $anio)
                            ->whereRaw('MONTH(nota_credito_debito.FechaCreacion) = ' . $mes)
                            ->orderBy('nota_credito_debito.FechaCreacion', 'desc')
                            ->get();
                    }
                    if ($tipoComprobante == 3) {
                        $resultado = DB::table('guia_remision')
                            ->join('sucursal', 'guia_remision.IdSucursal', '=', 'sucursal.IdSucursal')
                            ->join('usuario', 'guia_remision.IdUsuario', '=', 'usuario.IdUsuario')
                            ->select('guia_remision.*', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario')
                            ->where('guia_remision.IdSucursal', $idSucursal)
                            ->whereNull('guia_remision.RutaXml')
                            ->whereRaw('YEAR(guia_remision.FechaCreacion) = ' . $anio)
                            ->whereRaw('MONTH(guia_remision.FechaCreacion) = ' . $mes)
                            ->orderBy('guia_remision.FechaCreacion', 'desc')
                            ->get();
                    }
                    if ($tipoComprobante == 4) {
                        $resultado = DB::table('resumen_diario')
                            ->join('sucursal', 'resumen_diario.IdSucursal', '=', 'sucursal.IdSucursal')
                            ->select('resumen_diario.*', 'sucursal.Nombre as Sucursal', 'resumen_diario.FechaEmitida as FechaCreacion')
                            ->where('resumen_diario.IdSucursal', $idSucursal)
                            ->whereNull('resumen_diario.RutaXml')
                            ->whereRaw('YEAR(resumen_diario.FechaEmitida) = ' . $anio)
                            ->whereRaw('MONTH(resumen_diario.FechaEmitida) = ' . $mes)
                            ->orderBy('resumen_diario.FechaEmitida', 'desc')
                            ->get();
                    }
                    if ($tipoComprobante == 5) {
                        $resultado = DB::table('baja_documentos')
                            ->join('sucursal', 'baja_documentos.IdSucursal', '=', 'sucursal.IdSucursal')
                            ->join('usuario', 'baja_documentos.IdUsuario', '=', 'usuario.IdUsuario')
                            ->select('baja_documentos.*', 'sucursal.Nombre as Sucursal', 'usuario.Nombre as Usuario', 'baja_documentos.FechaEmitida as FechaCreacion')
                            ->where('baja_documentos.IdSucursal', $idSucursal)
                            ->whereNull('baja_documentos.RutaXml')
                            ->whereRaw('YEAR(baja_documentos.FechaEmitida) = ' . $anio)
                            ->whereRaw('MONTH(baja_documentos.FechaEmitida) = ' . $mes)
                            ->orderBy('baja_documentos.FechaEmitida', 'desc')
                            ->get();
                    }
                }
            }

            return $resultado;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getMes($num)
    {
        $arrayMes = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        return $arrayMes[$num - 1];
    }

    public function getFechaFiltro($fecha, $fechaIni, $fechaFin)
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
            if ($datePrev == 31) {
                $fechaInicio = Carbon::today()->subDays(31)->firstOfMonth();
                $date1 = Carbon::today();

                //$fechaFinal = Carbon::today()->subDays(31)->endOfMonth();

                $fechaFinal = Carbon::now()->firstOfMonth();
                //dd($fechaFinal);
            } else {
                $fechaInicio = Carbon::today()->subMonths(1)->firstOfMonth();
                $date1 = Carbon::today();
                //$fechaFinal = $date1->subDays($datePrev - 1);
                $fechaFinal = Carbon::now()->firstOfMonth();
            }
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
        // Filtro ultimos seis meses
        if ($fecha == 10) {
            $fechaInicio = Carbon::today()->firstOfMonth()->subMonths(6);
            $fechaFinal = Carbon::now();
            return array($fechaInicio, $fechaFinal);
        }

        // Filtro ultimos dos años
        if ($fecha == 11) {
            $fechaInicio = Carbon::today()->firstOfYear()->subYears(3);
            $fechaFinal = Carbon::today()->firstOfYear()->subSeconds(1);
            return array($fechaInicio, $fechaFinal);
        }

    }
}
