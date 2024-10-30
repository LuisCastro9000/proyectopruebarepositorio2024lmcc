<?php
namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait ArticulosTrait
{
    /**
     * @param Collection $articulosNuevos
     * @param Collection $articulosAnteriores
     * @return Collection
     */

    private function buscarArticulosParaActualizar($articulosNuevos, $articulosAnteriores)
    {
        // Obtener los mismos articulos
        return $articulosNuevos->whereIn('Codigo', $articulosAnteriores->pluck('Codigo'));
    }

    private function buscarArticulosParaInsertar($articulosNuevos, $articulosAnteriores)
    {
        // Obtener los nuevos articulos
        return $articulosNuevos->whereNotIn('Codigo', $articulosAnteriores->pluck('Codigo'));
    }

    private function buscarArticulosParaEliminar($articulosNuevos, $articulosAnteriores)
    {
        // Obtener los articulos que han sido eliminados
        return $articulosAnteriores->whereNotIn('Codigo', $articulosNuevos->pluck('Codigo'));
    }

    private function buscarArticulosPaquetesPromoParaInsertar($articulosNuevos, $articulosAnteriores)
    {
        // Obtener los articulos de los nuevos Paquetes que han sido agregados
        return $articulosNuevos->whereNotIn('IdPaquetePromocional', $articulosAnteriores->pluck('IdPaquetePromocional'));
    }

    private function buscarArticulosPaquetesPromoParaEliminar($articulosNuevos, $articulosAnteriores)
    {
        // Obtener los articulos de los Paquetes que han sido eliminados
        return $articulosAnteriores->whereNotIn('IdPaquetePromocional', $articulosNuevos->pluck('IdPaquetePromocional'));
    }
}
