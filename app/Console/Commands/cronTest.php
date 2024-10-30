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

class cronTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cronTest:test';

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
        $texto = "Enviando xml por tarea programada nuevamente 2023";
        // Storage::disk('local')->put("archivo.txt", $texto);
        Storage::append("archivo.txt", $texto);

        // $array = ['Nombre' => 'Tarea'];
        // DB::table('prueba_transaccion')->insert($array);

        $loadDatos = new DatosController();
        $fechas = $loadDatos->getFechaFiltro(1, null, null);
        $resultadoFiltro = DB::table('filtros_correo')->select('IdCategoria', 'NombreCorreoXml', 'IdSucursal', 'IdUsuario', 'Estado')->get();
        $resultadoFiltro = collect($resultadoFiltro);
        $estadoCheckEnvio = $resultadoFiltro->pluck('Estado')->first();
        if ($estadoCheckEnvio == "Activado") {

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
            $mail->addAddress('pruebasoporte@autocontrol.pe', 'Comprobante Automatico');
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Motoquad XML Desde el Entorno de Desarrollo';
            $mail->msgHTML('<div>Envio de correo automatico XML</div>');
            $enviado = $mail->send();

            // \Log::info('Tarea programada Ejecutado correctamente');

            // $texto = "Enviando xml por tarea programada";
            // // Storage::disk('local')->put("archivo.txt", $texto);
            // Storage::append("archivo.txt", $texto);

        }
    }
}
