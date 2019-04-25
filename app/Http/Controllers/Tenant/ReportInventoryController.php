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

        $establishment = Establishment::where('id', auth()->user()->establishment_id)->first();

        $reports = $this->calcular_stock_x_establecimiento($establishment->id);
        
        return view('tenant.reports.inventories.index', compact('reports', 'establishment'));
    }
    
    /**
     * Search
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
        $reports = Item::query()
            ->with(['unit_type'])
            ->where('item_type_id', '01')
            ->latest()
            ->get();
        
        return view('tenant.reports.inventories.index', compact('reports'));
    }
    
    /**
     * PDF
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function pdf(Request $request) {
        $company = Company::first();
        $establishment = Establishment::where('id', auth()->user()->establishment_id)->first();
        
        // $reports = Item::query()
        //     ->with(['unit_type'])
        //     ->where('item_type_id', '01')
        //     ->latest()
        //     ->get();

        $reports = $this->calcular_stock_x_establecimiento($establishment->id);
        
        $pdf = PDF::loadView('tenant.reports.inventories.report_pdf', compact("reports", "company", "establishment"));
        $filename = 'Reporte_Inventario'.date('YmdHis');
        
        return $pdf->download($filename.'.pdf');
    }
    
    /**
     * Excel
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function excel(Request $request) {
        $company = Company::first();
        $establishment = Establishment::where('id', auth()->user()->establishment_id)->first();
        
        // $records = Item::query()
        //     ->with(['unit_type'])
        //     ->where('item_type_id', '01')
        //     ->latest()
        //     ->get();
        $records = $this->calcular_stock_x_establecimiento($establishment->id);
        
        return (new InventoryExport)
            ->records($records)
            ->company($company)
            ->establishment($establishment)
            ->download('ReporteInv'.Carbon::now().'.xlsx');
    }

    public function calcular_stock_x_establecimiento($establishment_id)
    {
        $sql = "SELECT ite.id,  ite.`description`, eit.quantity as stock
        FROM items ite
        INNER JOIN establishment_items eit ON eit.item_id = ite.id
        WHERE eit.establishment_id = ? ORDER BY created_at desc";

        $items = DB::connection('tenant')->select($sql, array($establishment_id));

        return $items;
    }
}
