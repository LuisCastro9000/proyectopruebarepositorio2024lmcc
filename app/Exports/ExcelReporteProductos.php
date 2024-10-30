<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExcelReporteProductos implements FromView
{
    public function view(): View
    {

        $productos = collect($this->resultArray);
        return view('excel.excelReporteProductos', [
            'productos' => $productos,
        ]);
    }

    public function __construct($_resultArray)
    {
        $this->resultArray = $_resultArray;
    }

}
