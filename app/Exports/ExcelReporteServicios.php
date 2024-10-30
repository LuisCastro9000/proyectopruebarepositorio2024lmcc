<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExcelReporteServicios implements FromView
{
    public function view(): View
    {

        $servicios = collect($this->resultArray);
        return view('excel.excelReporteServicios', [
            'servicios' => $servicios,
        ]);
    }

    public function __construct($_resultArray)
    {
        $this->resultArray = $_resultArray;
    }

}
