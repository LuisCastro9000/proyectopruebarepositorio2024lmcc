<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExcelReporteVentasProductosXcorreo implements FromView
{
    public function view(): View
    {
        //$loadDatos = new DatosController();
        //$_facturasVentas = $loadDatos->getVentasAll($idSucursal);
        //$reporteClientes = $this->getGuiasClientesFiltrados($this->idSucursal, $this->cliente, $this->tipoPago, $this->fecha1, $this->fecha2 );
        $reporteProductos = collect($this->resultArray);
        return view('excel.excelReporteProductosXcorreo', [
            'reporteProductos' => $reporteProductos,
        ]);
    }

    public function __construct($_resultArray)
    {
        $this->resultArray = $_resultArray;
    }

    /*public function registerEvents(): array
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
'Fecha Emitida', 'Cliente', 'Documento', 'Numero de Guía', 'Estado', 'Código Error'
];
}

public function columnWidths(): array
{
return [
'A' => 18,
'B' => 40,
'C' => 40,
'D' => 45,
'E' => 60,
'F' => 20
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
}*/

}
