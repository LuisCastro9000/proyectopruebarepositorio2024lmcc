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

class cronEnviarCorreoXmlNotaCredito extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enviarCorreo:xml-nota-credito';

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
        $resultadoFiltro = DB::table('filtros_correo')->select('IdCategoria', 'NombreCorreoXml', 'IdSucursal', 'IdUsuario', 'Estado')->get();
        $resultadoFiltro = collect($resultadoFiltro);
        $estadoCheckEnvio = $resultadoFiltro->pluck('Estado')->first();

        if ($estadoCheckEnvio == "Activado") {
            $nombreCorreoXml = $resultadoFiltro->pluck('NombreCorreoXml')->first();
            $idSucursal = $resultadoFiltro->pluck('IdSucursal')->first();
            $idUsuario = $resultadoFiltro->pluck('IdUsuario')->first();
            $idCategoria = $resultadoFiltro->pluck('IdCategoria')->first();

            //  Datos del RUC
            $usuarioSelect = $loadDatos->getUsuarioSelect($idUsuario);
            $empresa = $loadDatos->getDatosEmpresa($usuarioSelect->CodigoCliente);
            $rucEmpresa = $empresa->Ruc;
            // Fin

            $notasCreditos = DB::table('nota_credito_debito as ncd')
                ->select('ncd.IdVentas', 'v.IdTipoComprobante', 'ncd.Serie', 'ncd.Numero', 'ncd.RutaXml')
                ->join('ventas as v', 'ncd.IdVentas', '=', 'v.IdVentas')
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

            if (count($notasCreditos) >= 1) {
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
                $mail->addAddress($nombreCorreoXml, 'Comprobante');
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';
                $mail->Subject = 'Motoquad NOTASCREDITOS-XML';

                for ($i = 0; $i < count($notasCreditos); $i++) {

                    if ($notasCreditos[$i]->IdTipoComprobante == 1) {
                        if (Storage::disk('s3')->exists($notasCreditos[$i]->RutaXml)) {
                            $rutaXmlS3 = Storage::disk('s3')->get($notasCreditos[$i]->RutaXml);
                            file_put_contents($rucEmpresa . '-03-' . $notasCreditos[$i]->Serie . '-' . $notasCreditos[$i]->Numero . '.xml', $rutaXmlS3);
                            $mail->addAttachment($rucEmpresa . '-03-' . $notasCreditos[$i]->Serie . '-' . $notasCreditos[$i]->Numero . '.xml');
                        }
                    }

                    if ($notasCreditos[$i]->IdTipoComprobante == 2) {
                        if (Storage::disk('s3')->exists($notasCreditos[$i]->RutaXml)) {
                            $rutaXmlS3 = Storage::disk('s3')->get($notasCreditos[$i]->RutaXml);
                            file_put_contents($rucEmpresa . '-01-' . $notasCreditos[$i]->Serie . '-' . $notasCreditos[$i]->Numero . '.xml', $rutaXmlS3);
                            $mail->addAttachment($rucEmpresa . '-01-' . $notasCreditos[$i]->Serie . '-' . $notasCreditos[$i]->Numero . '.xml');
                        }
                    }
                }
                $mail->msgHTML('<div>Envio de correo automático NOTASCRÉDITO-XML</div>');
                $enviado = $mail->send();
                if ($enviado) {
                    for ($i = 0; $i < count($notasCreditos); $i++) {
                        if ($notasCreditos[$i]->IdTipoComprobante == 1) {
                            if (unlink($rucEmpresa . '-03-' . $notasCreditos[$i]->Serie . '-' . $notasCreditos[$i]->Numero . '.xml')) {
                            }
                        }
                        if ($notasCreditos[$i]->IdTipoComprobante == 2) {
                            if (unlink($rucEmpresa . '-01-' . $notasCreditos[$i]->Serie . '-' . $notasCreditos[$i]->Numero . '.xml')) {
                            }
                        }
                    }
                }
            }

        }

    }
}
