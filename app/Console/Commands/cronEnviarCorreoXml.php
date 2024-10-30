<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
// Uses
use App\Http\Controllers\DatosController;
use DB;
use Illuminate\Console\Command;
use PHPMailer\PHPMailer\PHPMailer;
use Storage;

// Fin

class cronEnviarCorreoXml extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enviarCorreo:archivoXml';

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
        $loadDatos = new DatosController();
        $fechas = $loadDatos->getFechaFiltro(1, null, null);
        $ventasFiltro = DB::table('filtros_correo')->select('IdCategoria', 'NombreCorreoXml', 'IdSucursal', 'IdUsuario', 'Estado')->get();
        $ventasFiltro = collect($ventasFiltro);
        $estadoCheckEnvio = $ventasFiltro->pluck('Estado')->first();

        if ($estadoCheckEnvio == "Activado") {
            $nombreCorreoXml = $ventasFiltro->pluck('NombreCorreoXml')->first();
            $idSucursal = $ventasFiltro->pluck('IdSucursal')->first();
            $idUsuario = $ventasFiltro->pluck('IdUsuario')->first();
            $idCategoria = $ventasFiltro->pluck('IdCategoria')->first();

            //  Datos del RUC
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
            $rucEmpresa = $empresa->Ruc;
            // Fin
            $ventas = DB::table('ventas as v')
                ->select('IdVentas', 'IdTipoComprobante', 'Serie', 'Numero', 'v.RutaXml')
                ->where('v.IdSucursal', '=', $idSucursal)
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
                })
                ->get();

            if (count($ventas) >= 1) {
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
                $mail->addAddress($nombreCorreoXml, $nombreCorreoXml);
                $mail->addAddress('pruebasoporte@autocontrol.pe', 'pruebasoporte@autocontrol.pe');
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';
                $mail->Subject = 'Motoquad VENTAS-XML';

                for ($i = 0; $i < count($ventas); $i++) {

                    if ($ventas[$i]->IdTipoComprobante == 1) {
                        if (Storage::disk('s3')->exists($ventas[$i]->RutaXml)) {
                            $rutaXmlS3 = Storage::disk('s3')->get($ventas[$i]->RutaXml);
                            file_put_contents($rucEmpresa . '-03-' . $ventas[$i]->Serie . '-' . $ventas[$i]->Numero . '.xml', $rutaXmlS3);
                            $mail->addAttachment($rucEmpresa . '-03-' . $ventas[$i]->Serie . '-' . $ventas[$i]->Numero . '.xml');
                        }
                    }

                    if ($ventas[$i]->IdTipoComprobante == 2) {
                        if (Storage::disk('s3')->exists($ventas[$i]->RutaXml)) {
                            $rutaXmlS3 = Storage::disk('s3')->get($ventas[$i]->RutaXml);
                            file_put_contents($rucEmpresa . '-01-' . $ventas[$i]->Serie . '-' . $ventas[$i]->Numero . '.xml', $rutaXmlS3);
                            $mail->addAttachment($rucEmpresa . '-01-' . $ventas[$i]->Serie . '-' . $ventas[$i]->Numero . '.xml');
                        }
                    }
                }
                $mail->msgHTML('<div>Envio de correo automatico XML</div>');
                $enviado = $mail->send();
                if ($enviado) {
                    for ($i = 0; $i < count($ventas); $i++) {
                        if ($ventas[$i]->IdTipoComprobante == 1) {
                            if (unlink($rucEmpresa . '-03-' . $ventas[$i]->Serie . '-' . $ventas[$i]->Numero . '.xml')) {
                            }
                        }
                        if ($ventas[$i]->IdTipoComprobante == 2) {
                            if (unlink($rucEmpresa . '-01-' . $ventas[$i]->Serie . '-' . $ventas[$i]->Numero . '.xml')) {
                            }
                        }

                    }
                }

            }
        }
    }
}
