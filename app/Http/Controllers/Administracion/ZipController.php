<?php
namespace App\Http\Controllers\Administracion;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use Illuminate\Http\Request;
use ZipFile;
use Greenter\Zip\CompressInterface;

class ZipController extends Controller
{
    public function descargar(){
        $zipFile = new \PhpZip\ZipFile();
        $datos = "";
        $zipFile->openFile($datos);
        dd('xml');
    }
}