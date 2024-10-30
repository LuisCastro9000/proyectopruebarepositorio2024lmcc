<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExcelReporteStockPorFecha implements FromView
{
    public function view(): View
    {
        $datosStock = collect($this->resultArray);
        return view('excel.excelReporteStockPorFecha', [
            'datosStock' => $datosStock,
        ]);
    }

    public function __construct($_resultArray)
    {
        $this->resultArray = $_resultArray;
    }

}
