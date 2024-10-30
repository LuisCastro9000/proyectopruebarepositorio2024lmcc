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

class ExcelReporteTraspasos implements FromView
{
    public function view(): View
    {
    	$traspasos = collect($this->resultArray);
        $tipoTraspaso = $this->tipoTraspaso;
        return view('excel.excelTraspasos', [
            'traspasos' => $traspasos,
            'tipoTraspaso' => $tipoTraspaso
        ]);
    }

    public function __construct($_resultArray, $_tipoTraspaso)
    {
        $this->resultArray = $_resultArray;
        $this->tipoTraspaso = $_tipoTraspaso;
    }

}