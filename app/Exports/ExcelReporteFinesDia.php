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

class ExcelReporteFinesDia implements FromView
{
    public function view(): View
    {
    	//$loadDatos = new DatosController();
    	//$_facturasVentas = $loadDatos->getVentasAll($idSucursal);
        //$reporteClientes = $this->getGuiasClientesFiltrados($this->idSucursal, $this->cliente, $this->tipoPago, $this->fecha1, $this->fecha2 );
        $reporteFinesDia = collect($this->resultArray);
        $idTipoMoneda = $this->idTipoMoneda;
        return view('excel.excelGerencialFinesDeDia', [
            'reporteFinesDia' => $reporteFinesDia,
            'idTipoMoneda' => $idTipoMoneda,
        ]);
    }

    public function __construct($_resultArray, $_idTipoMoneda)
    {
        $this->resultArray = $_resultArray;
        $this->idTipoMoneda = $_idTipoMoneda;
    }

}