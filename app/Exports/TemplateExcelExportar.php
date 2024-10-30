<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TemplateExcelExportar implements FromView
{
    protected $resultArray;
    protected $viewName;
    public $datosOpcionales;

    /**
     * Constructor de la clase.
     *
     * @param Collection $_resultArray Los datos que se exportarán a Excel.
     * @param string $viewName Nombre de la vista Blade que formateará los datos.
     * @param array $datosOpcionales  como un array clave-valor no es necesario pasar como parametro si no lo requiere.
     */

    public function __construct($_resultArray, $viewName, array $datosOpcionales = [])
    {
        $this->resultArray = $_resultArray;
        $this->viewName = $viewName;
        $this->datosOpcionales = $datosOpcionales;
    }

    /**
     * Retorna una instancia de la vista que será utilizada para generar el Excel.
     *
     * @return View
     */
    public function view(): View
    {
        $datosExportar = collect($this->resultArray);
        return view($this->viewName, [
            'datosExportar' => $datosExportar,
            'datosOpcionales' => $this->datosOpcionales,
        ]);
    }
}
