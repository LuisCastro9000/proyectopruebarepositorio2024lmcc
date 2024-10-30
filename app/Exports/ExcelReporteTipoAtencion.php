<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExcelReporteTipoAtencion implements FromView
{
    public function view(): View
    {
        $atencionesVehiculares = collect($this->resultArray);
        return view('excel.excelReporteTipoAtencion', [
            'atencionesVehiculares' => $atencionesVehiculares,
        ]);
    }

    public function __construct($_resultArray)
    {
        $this->resultArray = $_resultArray;
    }

}
