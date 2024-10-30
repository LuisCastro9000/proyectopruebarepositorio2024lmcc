<?php
namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait GestionarDirectoriosAndObjetosS3Trait
{

    private function crearDirectorioS3($directorio)
    {
        if (!Storage::disk('s3')->exists($directorio)) {
            Storage::disk('s3')->makeDirectory($directorio, 'public');
        }
    }
    private function eliminarObjetoS3($urlObjeto)
    {
        if ($urlObjeto !== null && str_contains($urlObjeto, config('variablesGlobales.urlDominioAmazonS3'))) {
            Storage::disk('s3')->delete(parse_url($urlObjeto, PHP_URL_PATH));
        } else {
            Storage::disk('s3')->delete($urlObjeto);
        }
    }

}
