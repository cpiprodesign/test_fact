<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use App\Exports\InventoryExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\{
    Establishment,
    Company,
    Item
};
use Carbon\Carbon;

class ReportInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $establishments = Establishment::all();

        $establishment_id = Establishment::where('id', auth()->user()->establishment_id)->first()->id;

        return view('tenant.reports.inventories.index', compact('establishments', 'establishment_id'));
    }
    
    /**
     * Search
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request) {

        if($request->has('selEstablishment'))
        {
            $establishments = Establishment::all();
            $establishment_id = $request->selEstablishment;

            $reports = $this->calcular_stock_x_establecimiento($establishment_id);

            return view('tenant.reports.inventories.index', compact('reports', 'establishments', 'establishment_id'));
        }        
    }
    
    /**
     * PDF
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function pdf(Request $request) {

        if($request->has('establishment_id'))
        {
            $company = Company::first();
            $establishment_id = $request->establishment_id;
            $establishment = Establishment::where('id', $establishment_id)->first();
            
            $reports = $this->calcular_stock_x_establecimiento($establishment_id);
            
            $pdf = PDF::loadView('tenant.reports.inventories.report_pdf', compact("reports", "company", "establishment"));
            $filename = 'Reporte_Inventario'.date('YmdHis');
            
            return $pdf->download($filename.'.pdf');
        }
    }
    
    /**
     * Excel
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function excel(Request $request)
    {
        if($request->has('establishment_id'))
        {
            $company = Company::first();
            $establishment_id = $request->establishment_id;
            
            $records = $this->calcular_stock_x_establecimiento($establishment_id);

            $establishment = Establishment::where('id', $establishment_id)->first();
            
            return (new InventoryExport)
            ->records($records)
            ->company($company)
            ->establishment($establishment)
            ->download('ReporteInv'.Carbon::now().'.xlsx');
        }
    }

    public function calcular_stock_x_establecimiento($establishment_id)
    {
        $sql = "SELECT ite.id, ite.internal_id,  ite.`description`, eit.quantity as stock
        FROM items ite
        INNER JOIN establishment_items eit ON eit.item_id = ite.id
        WHERE eit.establishment_id = ? ORDER BY created_at desc";

        $items = DB::connection('tenant')->select($sql, array($establishment_id));

        return $items;
    }
}
