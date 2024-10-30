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

class ExcelReporteVentasFormato implements FromCollection, WithEvents, WithHeadings, WithColumnWidths, WithStyles
{
    /*public function view(): View
    {
    	$loadDatos = new DatosController();
    	$idSucursal = Session::get('idSucursal');
    	$_facturasVentas = $loadDatos->getVentasAll($idSucursal);
    	$facturasVentas = collect($_facturasVentas);
        return view('excel.excelReporteRegistroVentas', [
            'facturasVentas' => $facturasVentas
        ]);
    }*/

    public function __construct($id_sucursal, $fecha1, $fecha2)
    {
        $this->idSucursal = $id_sucursal;
        $this->fecha1 = $fecha1;
        $this->fecha2 = $fecha2;
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
            'PERIODO', 'CUO', 'CORRELATIVO', 'F. EMISIÓN', 'F. VCTO.', 'T. COMP.', 'SERIE', 'NUM. INICIAL', 'NUM. FINAL', 'T. DOC', 'NÚMERO', 'RAZÓN SOCIAL', 'EXPORT.', 'B. I. GRAV.', 'DSCTO', 'IGV', 'DSCTO IGV', 'EXONERADA', 'INAFECTA', 'I.S.C.', 'B. I. ARROZ', 'I. V. ARROZ', 'BOLSAS', 'OTROS', 'TOTAL', 'MONEDA', 'T. C.', 'F. EM. MOD.', 'T. COMPMOD.', 'SERIE', 'NUMERO', 'CONTRA.', 'ERROR 1', 'IND. COMP.', 'ESTADO'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,      
            'C' => 20,
            'D' => 15,
            'E' => 15,
            'F' => 10,      
            'G' => 15,
            'H' => 15,
            'I' => 15,
            'J' => 15,      
            'K' => 20,
            'L' => 40,
            'M' => 15,
            'N' => 15,      
            'O' => 15,
            'P' => 15,
            'Q' => 15,
            'R' => 15,
            'S' => 15,
            'T' => 15,
            'U' => 15,
            'V' => 15,
            'W' => 15,
            'X' => 15,
            'Y' => 15,
            'Z' => 15,
            'AA' => 15,
            'AB' => 15,
            'AC' => 15,
            'AD' => 15,
            'AE' => 15,
            'AF' => 15,
            'AG' => 15,
            'AH' => 15,
            'AI' => 15
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],

            'A:AI' => ['alignment' => ['horizontal' => 'center']],
            // Styling a specific cell by coordinate.
            //'B2' => ['font' => ['italic' => true]],

            // Styling an entire column.
            //'C'  => ['font' => ['size' => 16]],
        ];
    }

    public function collection()
    {
    	$loadDatos = new DatosController();
    	$idSucursal = Session::get('idSucursal');
    	$idUsuario = Session::get('idUsuario');

        $_facturasVentas = DB::select("(SELECT '-' as 'Periodo', '-' as 'Cuo', '-' as 'Correlativo', DATE_FORMAT(v.FechaCreacion, '%d/%m/%Y') as 'FechaCreacion', IF(v.IdTipoPago=2,  DATE_FORMAT(DATE_ADD(v.FechaCreacion, interval v.PlazoCredito DAY), '%d/%m/%Y'), '-') as 'FechaVencimiento', v.IdTipoSunat as 'Documento', v.Serie, v.Numero, '-' as 'NumFinal', td.CodigoSunat, c.NumeroDocumento as 'DocumentoReceptor', c.RazonSocial, '-' as Export, v.Subtotal, '-' as 'Descuento', v.IGV, '-' as 'descIGV', '-' as Exoner, '-' as Inafecta, '-' as ISC, '-' as BIArroz, '-' as IVArroz, '0.00' as Bolsas, '-' as Otros, (v.Total + v.Amortizacion) as 'ImporteTotal', tm.CodigoMoneda, '-' as TC, '-' as FEMod, '-' as TCompMod, '-' as Serie2, '-' as Numero2, '-' as Contra, '-' as 'Error1', '-' as 'IndComp', '-' as Estado 
        FROM 
        ventas v inner join cliente c ON v.IdCliente = c.IdCliente inner join tipo_moneda tm ON v.IdTipoMoneda = tm.IdTipoMoneda inner join tipo_documento td ON c.IdTipoDocumento = td.IdTipoDocumento WHERE v.IdTipoComprobante != 3 AND v.IdTipoMoneda = 1 AND v.IdSucursal= ? AND v.FechaCreacion BETWEEN ? AND ?)
        union ALL 
        (SELECT '-' as 'Periodo', '-' as 'Cuo', '-' as 'Correlativo', DATE_FORMAT(ncd.FechaCreacion, '%d/%m/%Y') as 'FechaCreacion', '-' as 'FechaVencimiento', ncd.IdTipoSunat as 'Documento', ncd.Serie, ncd.Numero, '-' as 'NumFinal', td.CodigoSunat, c.NumeroDocumento as 'DocumentoReceptor', c.RazonSocial, '-' as Export, ncd.Subtotal, '-' as 'Descuento', ncd.IGV, '-' as 'descIGV', '-' as Exoner, '-' as Inafecta, '-' as ISC, '-' as BIArroz, '-' as IVArroz, '0.00' as Bolsas, '-' as Otros, ncd.Total as 'ImporteTotal', tm.CodigoMoneda, '-' as TC, '-' as FEMod, '-' as TCompMod, SUBSTRING_INDEX(ncd.DocModificado, '-', 1),  SUBSTRING_INDEX(ncd.DocModificado, '-', -1), '-' as Contra, '-' as 'Error1', '-' as 'IndComp', '-' as Estado
        FROM 
        nota_credito_debito ncd inner join cliente c ON ncd.IdCliente = c.IdCliente inner join tipo_nota tn ON ncd.IdTipoNota = tn.IdTipoNota inner join tipo_moneda tm ON ncd.IdTipoMoneda = tm.IdTipoMoneda inner join tipo_documento td ON c.IdTipoDocumento = td.IdTipoDocumento WHERE ncd.IdTipoMoneda = 1 AND ncd.IdSucursal = ? AND ncd.FechaCreacion BETWEEN ? AND ?) order by FechaCreacion desc",
        [$this->idSucursal, $this->fecha1, $this->fecha2, $this->idSucursal, $this->fecha1, $this->fecha2]);

        if(count($_facturasVentas) > 0)
        {
			$i = 0;
			foreach($_facturasVentas  as $fact )
			{
                if($fact->Documento == '07'){
                    $comprobante = DB::table('ventas')
                                ->where('Serie', $fact->Serie2)
                                ->where('Numero', $fact->Numero2)
                                ->where('IdSucursal', $idSucursal)
                                ->first();
                    $fecha = date_create($comprobante->FechaCreacion);
                    $formatoFecha = date_format($fecha, 'd/m/Y');
                    $_facturasVentas[$i]->FEMod = $formatoFecha;
                    $_facturasVentas[$i]->TCompMod = $comprobante->IdTipoSunat;
                }
                
				$i++;
			}
	    }
        $facturasVentas = collect($_facturasVentas);
        return $facturasVentas;
    }
}