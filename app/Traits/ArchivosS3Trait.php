<?php
namespace App\Traits;

use App\Traits\GestionarDirectoriosAndObjetosS3Trait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ArchivosS3Trait
{
    use GestionarDirectoriosAndObjetosS3Trait;

    public function storePdfWhatsAppS3($pdf, $nombrePdf, $directorio, $rucEmpresa)
    {
        $nuevoDirectorio = $this->generarUbicacionArchivo($directorio, $rucEmpresa);
        // CREAR DIRECTORIO
        $this->crearDirectorioS3($nuevoDirectorio);

        $nombrePdf = $nuevoDirectorio . $nombrePdf . '.pdf';
        Storage::disk('s3')->put($nombrePdf, $pdf->output(), 'public');
        $urlPdf = Storage::disk('s3')->url($nombrePdf);
        return parse_url($urlPdf, PHP_URL_PATH);
    }

    public function storeImagenFormatoFileS3($imagen, $imagenAnterior, $nombreImagen, $directorio, $rucEmpresa, $accion)
    {
        $nuevoDirectorio = $this->generarUbicacionArchivo($directorio, $rucEmpresa);
        // CREAR DIRECTORIO
        $this->crearDirectorioS3($nuevoDirectorio);
        // ELIMINAR IMAGEN ANTERIOR
        if ($accion == 'edit') {
            $this->eliminarObjetoS3($imagenAnterior);
        }

        $nombreImagen = $nuevoDirectorio . $nombreImagen . '.' . $imagen->getClientOriginalExtension();
        Storage::disk('s3')->put($nombreImagen, file_get_contents($imagen), 'public');
        $urlImagen = Storage::disk('s3')->url($nombreImagen);
        return parse_url($urlImagen, PHP_URL_PATH);
    }

    public function deleteImagenArticulo($imagenUrl, $idArticulo)
    {
        if ($imagenUrl !== null && str_contains($imagenUrl, config('variablesGlobales.urlDominioAmazonS3'))) {
            Storage::disk('s3')->delete(parse_url($imagenUrl, PHP_URL_PATH));
        } elseif (!str_contains($imagenUrl, 'not-found.png')) {
            // Eliminar la imagen solamente si no contiene 'not-found.png' en la URL
            Storage::disk('s3')->delete($imagenUrl);
        }
        DB::table('articulo')
            ->where('IdArticulo', $idArticulo)
            ->update(['Imagen' => 'https://easyfactperu.pe/facturacion/assets/img/not-found.png']);
    }

    public function storeImagenFormatoBase64($imagen, $imagenAnterior, $nombreImagen, $directorio, $rucEmpresa, $accion)
    {
        $nuevoDirectorio = $this->generarUbicacionArchivo($directorio, $rucEmpresa);
        // CREAR DIRECTORIO
        $this->crearDirectorioS3($nuevoDirectorio);
        // ELIMINAR IMAGEN ANTERIOR
        if ($accion == 'edit') {
            $this->eliminarObjetoS3($imagenAnterior);
        }
        $encoded_image = explode(',', $imagen)[1];
        $decoded_image = base64_decode($encoded_image);
        $extensionImagenPng = explode('/', explode(':', substr($imagen, 0, strpos($imagen, ';')))[1])[1];

        $nombreImagen = $nuevoDirectorio . $nombreImagen . '.' . $extensionImagenPng;
        Storage::disk('s3')->put($nombreImagen, $decoded_image, 'public');
        $urlImagen = Storage::disk('s3')->url($nombreImagen);
        return parse_url($urlImagen, PHP_URL_PATH);
    }

    // GENERAR UBICACION
    public function generarUbicacionArchivo($directorio, $rucEmpresa)
    {
        $fecha = Carbon::now();
        $anio = $fecha->year;
        $mes = Str::ucfirst($fecha->locale('es')->monthName);
        return Config::get('variablesGlobales.bucketS3') . $directorio . "$anio/$mes/$rucEmpresa/";
    }

}
