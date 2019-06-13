<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Warehouse;
use App\Models\Tenant\Company;
use App\Models\Tenant\Item;
use Modules\Inventory\Models\ItemWarehouse;
use Modules\Inventory\Exports\InventoryExport;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class ReportInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        // $reports = ItemWarehouse::with(['item'=>function($q){
        //                             $q->where('item_type_id', '01');
        //                         }])->latest()->get();
        
        $warehouses = Warehouse::all();
        $warehouse_id = Warehouse::where('establishment_id', auth()->user()->establishment_id)->first()->id;

        return view('inventory::reports.inventory.index', compact('reports', 'warehouses', 'warehouse_id'));
    }
    
    /**
     * Search
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
        
        $warehouse_id = $request->selWarehouse;
        $warehouses = Warehouse::all();
        
        $reports = $this->records($warehouse_id);
        
        return view('inventory::reports.inventory.index', compact('reports', 'warehouses', 'warehouse_id'));
    }
    
    /**
     * PDF
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function pdf(Request $request) {

        $company = Company::first();
        
        $warehouse_id = $request->warehouse_id;
        $warehouse = Warehouse::find($request->warehouse_id);
        $establishment = Establishment::where('id', $warehouse->establishment_id)->first();
        
        $reports = $this->records($warehouse_id);
        
        $pdf = PDF::loadView('inventory::reports.inventory.report_pdf', compact("reports", "company", "establishment"));
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
        $warehouse_id = $request->warehouse_id;
        $warehouse = Warehouse::find($request->warehouse_id);
        $establishment = Establishment::where('id', $warehouse->establishment_id)->first();
       
        $records = $this->records($warehouse_id);

        return (new InventoryExport)
            ->records($records)
            ->company($company)
            ->establishment($establishment)
            ->download('ReporteInv'.Carbon::now().'.xlsx');
    }

    public function records($warehouse_id)
    {
        $sql = "SELECT ite.id, ite.internal_id,  ite.`description` AS item, itw.stock, cut.`description` AS unit, ite.`purchase_unit_price`
        FROM item_warehouse itw
        INNER JOIN items ite ON ite.id = itw.item_id
        INNER JOIN cat_unit_types cut ON cut.id = ite.unit_type_id
        WHERE itw.warehouse_id = ? AND ite.item_type_id = 1 ORDER BY ite.id";

        $items = DB::connection('tenant')->select($sql, array($warehouse_id));

        return $items;
    }
}
