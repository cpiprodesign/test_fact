<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use App\Exports\KardexExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\{
    Establishment,
    Company,
    Kardex,
    Item
};
use Carbon\Carbon;

class ReportKardexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $establishments = Establishment::all();
        $establishment_id = Establishment::where('id', auth()->user()->establishment_id)->first()->id;
        
        $items = Item::query()
            ->where('item_type_id', '01')
            ->latest()
            ->get();
            
        return view('tenant.reports.kardex.index', compact('items', 'establishments', 'establishment_id'));
    }
    
    /**
     * Search
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
        
        $balance = 0;
        
        $establishments = Establishment::all();
        $establishment_id = $request->selEstablishment;

        $items = Item::query()
            ->where('item_type_id', '01')
            ->latest()
            ->get();
        
        $reports = $this->records($request->selEstablishment, $request->item_id);
        
        return view('tenant.reports.kardex.index', compact('items', 'reports', 'balance', 'establishments', 'establishment_id'));
    }
    
    /**
     * PDF
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function pdf(Request $request) {
        $balance = 0;
        $company = Company::first();
        $establishment_id = $request->establishment_id;
        $establishment = Establishment::where('id', $establishment_id)->first();
        
        $reports = $this->records($request->establishment_id, $request->item_id);
        
        $pdf = PDF::loadView('tenant.reports.kardex.report_pdf', compact("reports", "company", "establishment", "balance"));
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
        $establishment_id = $request->establishment_id;
        $establishment = Establishment::where('id', $establishment_id)->first();
       
        $records = $this->records($request->establishment_id, $request->item_id);
        
        return (new KardexExport)
            ->balance($balance)
            ->records($records)
            ->company($company)
            ->establishment($establishment)
            ->download('ReporteKar'.Carbon::now().'.xlsx');
    }

    public function records($establishment_id, $item_id)
    {
        $sql = "SELECT kar.id, kar.type, doc.series, doc.number, kar.quantity, kar.created_at
                FROM kardex kar
                INNER JOIN documents doc ON doc.id = kar.`document_id`
                WHERE doc.establishment_id = $establishment_id AND kar.item_id = $item_id
                UNION ALL
                SELECT kar.id, kar.type, pur.series, pur.number, kar.quantity, kar.created_at
                FROM kardex kar
                INNER JOIN purchases pur ON pur.id = kar.`purchase_id`
                WHERE pur.establishment_id = $establishment_id AND kar.item_id = $item_id
                ORDER BY created_at";

        $items = DB::connection('tenant')->select($sql);

        return $items;
    }
}
