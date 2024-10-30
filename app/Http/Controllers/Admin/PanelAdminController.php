<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PanelAdminController extends Controller
{
    public function __invoke(Request $req)
    {
        $cantidadPagosSinVerificar = $this->getDatosPagos('Sin Verificar');
        $cantidadPagosRenovados = $this->getDatosPagos('Renovado');
        return view('admin/panel-admin', compact('cantidadPagosSinVerificar', 'cantidadPagosRenovados'));
    }

    private function getDatosPagos($estado)
    {
        return DB::table('pagos_plataforma')->where('Estado', $estado)->count();
    }
}
