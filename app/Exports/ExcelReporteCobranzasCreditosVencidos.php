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

class ExcelReporteCobranzasCreditosVencidos implements FromCollection, WithEvents, WithHeadings, WithColumnWidths, WithStyles
{
    /*public function view(): View
    {
    	$creditosVencidos = collect($this->resultArray);
        return view('excel.excelCreditosVencidos', [
            'creditosVencidos' => $creditosVencidos
        ]);
    }*/

    public function __construct($_resultArray)
    {
        $this->resultArray = $_resultArray;
    }

    public function registerEvents(): array
    {
        return [            
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->freezePane('A2', 'A2');
            },
        ];
    }

    public function headings(): array
    {
        return [
            'Cliente', 'Documento', 'Fecha de Emisión', 'Fecha Vencimiento', 'Ultima Fecha Pagada', 'Tipo de Moneda', 'Importe', 'Deuda', 'Días Atrasados'  
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 50,
            'B' => 20,      
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 15,
            'G' => 20,
            'H' => 15,
            'I' => 20
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],

            'A:Z' => ['alignment' => ['horizontal' => 'center']],
            // Styling a specific cell by coordinate.
            //'B2' => ['font' => ['italic' => true]],

            // Styling an entire column.
            //'C'  => ['font' => ['size' => 16]],
        ];
    }

    public function collection()
    {
    	$creditosVencidos = $this->resultArray;
        return $creditosVencidos;
    }

}