<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Modules\Inventory\Exports\KardexExport;
use Illuminate\Http\Request;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Company;
use App\Models\Tenant\Kardex;
use App\Models\Tenant\Item;
use Carbon\Carbon;
use Modules\Inventory\Models\InventoryKardex;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Http\Resources\ReportKardexCollection;


class ReportKardexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $models = [
        "App\Models\Tenant\Document", 
        "App\Models\Tenant\Purchase", 
        "App\Models\Tenant\SaleNote", 
        "Modules\Inventory\Models\Inventory"
    ];

    public function index() {
        
        $items = Item::query()
            ->where('item_type_id', '01')
            ->latest()
            ->get();
        
        $warehouses = Warehouse::all();
        $warehouse_id = Warehouse::where('establishment_id', auth()->user()->establishment_id)->first()->id;
            
        return view('inventory::reports.kardex.index', compact('items', 'warehouses', 'warehouse_id'));
    }
    
    /**
     * Search
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request) { 

        $balance = 0;
        
        $items = Item::query()
            ->where('item_type_id', '01')
            ->latest()
            ->get();
    
        $warehouse_id = $request->selWarehouse;
        $warehouses = Warehouse::all();

        $reports = InventoryKardex::with(['inventory_kardexable'])
                                    ->where([['item_id', $request->item_id],['warehouse_id', $warehouse_id]])  
                                    ->orderBy('id')
                                    ->get();

        // echo json_encode($reports); exit;
        
        $models = $this->models;

        return view('inventory::reports.kardex.index', compact('items', 'reports', 'balance','models', 'warehouses', 'warehouse_id'));
    }
    
    /**
     * PDF
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function pdf(Request $request) {

        $balance = 0;
        $company = Company::first();
        
        $warehouse_id = $request->warehouse_id;
        $warehouse = Warehouse::find($request->warehouse_id);
        $establishment = Establishment::where('id', $warehouse->establishment_id)->first();
        
        $reports = InventoryKardex::with(['inventory_kardexable'])
                                    ->where([['item_id', $request->item_id],['warehouse_id', $warehouse_id]])  
                                    ->orderBy('id')     
                                    ->get();

        $models = $this->models;
        
        $pdf = PDF::loadView('inventory::reports.kardex.report_pdf', compact("reports", "company", "establishment", "balance","models"));
        $filename = 'Reporte_Kardex'.date('YmdHis');
        
        return $pdf->download($filename.'.pdf');
    }
    
    /**
     * Excel
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function excel(Request $request) {

        $balance = 0;
        $company = Company::first();
        $establishment = Establishment::first();
        
        $warehouse_id = $request->warehouse_id;
        $warehouse = Establishment::where('id', $warehouse_id)->first();

        $records = InventoryKardex::with(['inventory_kardexable'])
                                    ->where([['item_id', $request->item_id],['warehouse_id', $warehouse->id]])  
                                    ->orderBy('id')     
                                    ->get();

        $models = $this->models;
        
        return (new KardexExport)
            ->balance($balance)
            ->records($records)
            ->models($models)
            ->company($company)
            ->establishment($establishment)
            ->download('ReporteKar'.Carbon::now().'.xlsx');
    }
}
