<?php

namespace App\Console\Commands;

use App\Exports\ExcelReporteVentasProducto;

// usar phpMailer
use App\Exports\ExcelReporteVentasProductosXcorreo;
use App\Http\Controllers\DatosController;
use DB;
use Excel;
use Illuminate\Console\Command;
use PHPMailer\PHPMailer\PHPMailer;

// Fin

class cronEnviarCorreoExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'correo:excel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // CODIGO PARA CARGAR LOS DATOS EN EL EXCEL
        $loadDatos = new DatosController();
        $fechas = $loadDatos->getFechaFiltro(1, null, null);
        $resultadoFiltro = DB::table('filtros_correo')->select('IdCategoria', 'NombreCorreoExcel', 'IdSucursal', 'Estado')->get();
        $resultadoFiltro = collect($resultadoFiltro);
        $estadoCheckEnvio = $resultadoFiltro->pluck('Estado')->first();

        if ($estadoCheckEnvio == "Activado") {
            $nombreCorreo = $resultadoFiltro->pluck('NombreCorreoExcel')->first();
            $idCategoria = $resultadoFiltro->pluck('IdCategoria')->first();
            $idSucursal = $resultadoFiltro->pluck('IdSucursal')->first();

            $productosVentas = DB::table('ventas as v')
                ->join('ventas_articulo as va', 'v.IdVentas', '=', 'va.IdVentas')
                ->join('usuario', 'v.IdCreacion', '=', 'usuario.IdUsuario')
                ->join('cliente', 'v.IdCliente', '=', 'cliente.IdCliente')
                ->join('distrito', 'cliente.Ubigeo', '=', 'distrito.IdDistrito')
                ->join('articulo', 'va.IdArticulo', '=', 'articulo.IdArticulo')
                ->join('categoria', 'articulo.IdCategoria', '=', 'categoria.IdCategoria')
                ->select('distrito.Nombre AS nombreDistrito', 'cliente.Direccion', 'v.FechaCreacion', 'v.IdTipoPago', 'v.TipoVenta', 'v.IdTipoMoneda', 'v.Total', 'v.Estado', 'va.IdArticulo as IdArticulo', 'articulo.Descripcion', 'va.Detalle', 'va.PrecioUnidadReal', 'va.Cantidad', 'usuario.Nombre as Usuario', 'cliente.Nombre as NombresCliente', 'cliente.NumeroDocumento as Documento', 'articulo.Costo as PrecioCosto', 'articulo.IdCategoria', DB::raw("CONCAT(Serie, '-', Numero) AS Correlativo"), DB::raw("'' AS DocumentoAfectado"), 'categoria.Nombre as nombreCategoria', DB::raw("'Ventas' AS Operacion"))
                ->where('v.IdSucursal', $idSucursal)
                ->whereBetween('v.FechaCreacion', [$fechas[0], $fechas[1]])
                ->whereNotIn('v.IdVentas', function ($query) use ($idCategoria) {
                    $query->select('va.IdVentas')
                        ->from('ventas_articulo as va')
                        ->join('articulo', 'va.IdArticulo', '=', 'articulo.IdArticulo')
                        ->where(function ($query) use ($idCategoria) {
                            $query->where('articulo.IdCategoria', '!=', $idCategoria)
                                ->orWhereNull('articulo.IdCategoria')
                                ->orWhere('articulo.IdCategoria', '=', 0);
                        });
                })->get();

            $productosNotaCredito = DB::table('nota_credito_debito as ncd')
                ->join('ventas as v', 'ncd.IdVentas', '=', 'v.IdVentas')
                ->join('ventas_articulo as va', 'v.IdVentas', '=', 'va.IdVentas')
                ->join('usuario', 'v.IdCreacion', '=', 'usuario.IdUsuario')
                ->join('cliente', 'v.IdCliente', '=', 'cliente.IdCliente')
                ->join('distrito', 'cliente.Ubigeo', '=', 'distrito.IdDistrito')
                ->join('articulo', 'va.IdArticulo', '=', 'articulo.IdArticulo')
                ->join('categoria', 'articulo.IdCategoria', '=', 'categoria.IdCategoria')
                ->select('distrito.Nombre AS nombreDistrito', 'cliente.Direccion', 'ncd.FechaCreacion', 'v.IdTipoPago', 'v.TipoVenta', 'v.IdTipoMoneda', 'v.Total', 'ncd.Estado', 'va.IdArticulo as IdArticulo', 'articulo.Descripcion', 'va.Detalle', 'va.PrecioUnidadReal', 'va.Cantidad', 'usuario.Nombre as Usuario', 'cliente.Nombre as NombresCliente', 'cliente.NumeroDocumento as Documento', 'articulo.Costo as PrecioCosto', 'articulo.IdCategoria', DB::raw("CONCAT(ncd.Serie, '-', ncd.Numero) AS Correlativo"), DB::raw("ncd.DocModificado AS DocumentoAfectado"), 'categoria.Nombre as nombreCategoria', DB::raw("'Nota-Crédito' AS Operacion"))
                ->where('ncd.IdSucursal', '=', $idSucursal)
                ->whereBetween('ncd.FechaCreacion', [$fechas[0], $fechas[1]])
                ->whereNotIn('v.IdVentas', function ($query) use ($idCategoria) {
                    $query->select('va.IdVentas')
                        ->from('ventas_articulo as va')
                        ->join('articulo', 'va.IdArticulo', '=', 'articulo.IdArticulo')
                        ->where(function ($query) use ($idCategoria) {
                            $query->where('articulo.IdCategoria', '!=', $idCategoria)
                                ->orWhereNull('articulo.IdCategoria')
                                ->orWhere('articulo.IdCategoria', '=', 0);
                        });
                })->get();

            $reporteProductos = $productosVentas->merge($productosNotaCredito);
            if (count($reporteProductos) >= 1) {
                $datos = Excel::raw(new ExcelReporteVentasProductosXcorreo($reporteProductos), \Maatwebsite\Excel\Excel::XLSX);

                // CODIGO ENVIAR EL EMAIL
                // $correo = "roischavez.02@gmail.com";
                // $correo = "informes@autocontrol.pe";
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->Host = 'mail.easyfactperu.pe';
                $mail->SMTPAuth = true;
                $mail->Username = 'facturacion@easyfactperu.pe';
                $mail->Debugoutput = 'html';
                $mail->Password = 'gV.S=o=Q,bl2';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                $mail->From = 'facturacion@easyfactperu.pe';
                $mail->FromName = 'EASYFACT PERÚ S.A.C.  - Facturación Electrónica';
                $mail->addAddress($nombreCorreo, $nombreCorreo);
                $mail->addAddress('pruebasoporte@autocontrol.pe', 'pruebasoporte@autocontrol.pe');
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';
                $mail->Subject = 'Motoquad Reporte VENTAS-NOTASCRÉDITO-EXCEL';
                $mail->addStringAttachment($datos, 'ProductosXcategoria.xlsx');

                $mail->msgHTML('<div>Envio de correo, Reporte de Productos de VENTAS-NOTASCRÉDITO por Categoria</div>');
                $enviado = $mail->send();
                // FIN
            }
        }
    }
}
