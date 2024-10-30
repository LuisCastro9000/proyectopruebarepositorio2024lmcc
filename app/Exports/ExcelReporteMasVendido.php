<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Session;
use DB;
use App\Http\Controllers\DatosController;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExcelReporteMasVendido implements FromView
{
    public function view(): View
    {
    	
     $reporteComprasVentas = collect($this->resultArray);
        return view('excel.reporteGerencialMasVendidoExcel', [
            'reporteMasVendidos' =>   $reporteComprasVentas
        ]);
    }

    public function __construct($_resultArray)
    {
        $this->resultArray = $_resultArray;
    }

}