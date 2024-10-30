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

class ExcelReporteGuia implements FromView
{
    public function view(): View
    {
    	// $loadDatos = new DatosController();
    	// $idSucursal = Session::get('idSucursal');
    	// //$_facturasVentas = $loadDatos->getVentasAll($idSucursal);
        // $reporteClientes = $this->getGuiasClientesFiltrados($this->idSucursal, $this->cliente, $this->tipoPago, $this->fecha1, $this->fecha2 );
    	// $reporteClientes = collect($reporteClientes);
        // return view('excel.reporteGuiasExcel', [
        //     'reporteClientes' => $reporteClientes
        // ]);

        $reporteClientes = collect($this->resultArray);
        return view('excel.reporteGuiasExcel', [
            'reporteClientes' => $reporteClientes
        ]);
    }
    public function __construct($_resultArray)
    {
        $this->resultArray = $_resultArray;
    }


    // public function __construct($id_sucursal, $cliente, $tipoPago, $fecha1, $fecha2)
    // {
    //     $this->idSucursal = $id_sucursal;
    //     $this->cliente = $cliente;
    //     $this->tipoPago = $tipoPago;
    //     $this->fecha1 = $fecha1;
    //     $this->fecha2 = $fecha2;
    // }

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

    /*public function collection()
    {
    	$loadDatos = new DatosController();
    	$idSucursal = Session::get('idSucursal');
    	$idUsuario = Session::get('idUsuario');
    	//$permisos = $loadDatos->getPermisos($idUsuario);
    	$_facturasVentas = DB::select("(SELECT DATE_FORMAT(v.FechaCreacion, '%d-%m-%Y') as 'FechaCreacion', v.IdTipoSunat as 'Documento', v.Serie, v.Numero , c.RazonSocial, c.NumeroDocumento as 'DocumentoReceptor', v.Estado, IF(v.TipoVenta=1, 'Gravada', 'Exonerada') as 'TipoOperacion', IF(v.CodigoDoc=0, '-' ,v.CodigoDoc) as 'CodError', IF(v.IdTipoPago=1, 'Contado','Credito') as 'TipoPago', IF(v.IdTipoPago=2,  DATE_FORMAT(DATE_ADD(v.FechaCreacion, interval v.PlazoCredito DAY), '%d-%m-%Y'), '-') as 'FechaVencimiento', v.Subtotal as 'BaseImponible', v.IGV, v.Total as 'ImporteTotal', tm.CodigoMoneda, if(1 > 1, '-', '-') as 'SerieRef', if(1 > 1, '-', '-') as 'NumeroRef' 
							FROM 
							ventas v inner join cliente c ON v.IdCliente = c.IdCliente inner join tipo_moneda tm ON v.IdTipoMoneda = tm.IdTipoMoneda WHERE v.IdSucursal= ? AND v.FechaCreacion BETWEEN ? AND ?)
							union ALL 
							(SELECT DATE_FORMAT(ncd.FechaCreacion, '%d-%m-%Y') as fecha, ncd.IdTipoSunat, ncd.Serie, ncd.Numero, c.RazonSocial, c.NumeroDocumento, ncd.Estado, IF(ncd.TipoVenta=1, 'Gravada', 'Exonerada') as 'TipoOperacion', IF(ncd.CodigoDoc=0, '-' ,ncd.CodigoDoc), '-', '-', ncd.Subtotal, ncd.IGV, ncd.Total, 'PEN', SUBSTRING_INDEX(ncd.DocModificado, '-', 1),  SUBSTRING_INDEX(ncd.DocModificado, '-', -1) 
							FROM 
							nota_credito_debito ncd inner join cliente c ON ncd.IdCliente = c.IdCliente inner join tipo_nota tn ON ncd.IdTipoNota = tn.IdTipoNota WHERE ncd.IdSucursal = ? AND ncd.FechaCreacion BETWEEN ? AND ?) order by FechaCreacion desc",
    		[$this->idSucursal, $this->fecha1, $this->fecha2, $this->idSucursal, $this->fecha1, $this->fecha2]);
    	$facturasVentas = collect($_facturasVentas);
        return $facturasVentas;
    }*/

    private function getGuiasClientesFiltrados($idSucursal, $cliente, $tipoPago, $fechaIni, $fechaFin) {
        try{
            if($cliente == 0){
                 if($tipoPago == 0){
                    $ventas= DB::table('guia_remision as gr')
                        ->join('cliente','gr.IdCliente', '=', 'cliente.IdCliente')
                        ->join('sucursal','gr.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario','gr.IdUsuario', '=', 'usuario.IdUsuario')
                        ->select('gr.*', 'cliente.Nombre as Nombres', 'usuario.Nombre as Usuario' ,'cliente.RazonSocial')
                        ->where('gr.IdSucursal', $idSucursal)
                        ->whereBetween('gr.FechaEmision', [$fechaIni, $fechaFin])
                        ->orderBy('IdGuiaRemision','desc')
                        ->get();
                    return $ventas;
                }
            }else{
                if($tipoPago == 0){
                    $ventas= DB::table('guia_remision as gr')
                        ->join('cliente','gr.IdCliente', '=', 'cliente.IdCliente')
                        ->join('sucursal','gr.IdSucursal', '=', 'sucursal.IdSucursal')
                        ->join('usuario','gr.IdUsuario', '=', 'usuario.IdUsuario')
                        ->select('gr.*', 'cliente.Nombre as Nombres',  'usuario.Nombre as Usuario' ,'cliente.RazonSocial')
                        ->where('gr.IdSucursal', $idSucursal)
                        ->where('cliente.RazonSocial', $cliente)
                        ->whereBetween('gr.FechaEmision', [$fechaIni, $fechaFin])
                        ->orderBy('IdGuiaRemision','desc')
                        ->get();
                    return $ventas;

                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}