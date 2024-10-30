<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class cronEnviarCorreosDocumentosDigitales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CronJob:EnviarCorreosDocumentosDigitales';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('queue:work', [
            '--tries' => 3, // Número de intentos antes de fallar el trabajo
            '--stop-when-empty' => true, // Detiene la ejecución cuando la cola esté vacía
            '--queue' => 'default', // Nombre de la cola
        ]);

        // $mail = new PHPMailer();
        // $mail->isSMTP();
        // $mail->Host = 'mail.easyfactperu.pe';
        // $mail->SMTPAuth = true;
        // $mail->Username = 'facturacion@easyfactperu.pe';
        // $mail->Debugoutput = 'html';
        // $mail->Password = 'gV.S=o=Q,bl2';
        // $mail->SMTPSecure = 'tls';
        // $mail->Port = 587;
        // $mail->From = 'facturacion@easyfactperu.pe';
        // $mail->FromName = 'EASYFACT PERÚ S.A.C.  - Facturación Electrónica';
        // $mail->addAddress('pruebasoporte@autocontrol.pe', 'Comprobante Automatico');
        // $mail->isHTML(true);
        // $mail->CharSet = 'UTF-8';
        // $mail->Subject = 'Motoquad XML Desde el Entorno de Desarrollo';
        // $mail->msgHTML('<div>Envio de correo automatico XML</div>');
        // $enviado = $mail->send();

    }
}
