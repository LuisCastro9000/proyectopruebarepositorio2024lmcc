<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
// Uses
use Illuminate\Console\Command;
use PHPMailer\PHPMailer\PHPMailer;

// Fin

class tareaPrueba extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tareaPrueba';

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
        $mail->addAddress('roischavez.02@gmail.com', 'Prueba de envio de correo');
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Realizando prueba de envio de correo desde el Entorno de Desarrollo';
        $mail->msgHTML('<div>Envio de correo desde laravel verison 10</div>');
        $enviado = $mail->send();

    }
}
